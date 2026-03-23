<?php
/**
 * FAFO Email - SendGrid API wrapper
 * Handles sending emails, forwarding, and querying.
 */

if ( ! defined('ABSPATH') ) exit;

class FAFO_Email_SendGrid {

    private static function get_api_key(): string {
        if ( defined('SENDGRID_API_KEY') ) return SENDGRID_API_KEY;
        return get_option('fafo_sendgrid_api_key', '');
    }

    private static function get_from_email(): string {
        if ( defined('FAFO_EMAIL_FROM') ) return FAFO_EMAIL_FROM;
        return get_option('fafo_email_from_address', get_option('admin_email'));
    }

    private static function get_from_name(): string {
        if ( defined('FAFO_EMAIL_FROM_NAME') ) return FAFO_EMAIL_FROM_NAME;
        return get_option('fafo_email_from_name', get_option('blogname'));
    }

    // ============================================================
    // SEND EMAIL via SendGrid API v3
    // ============================================================
    public static function send_email( array $params ): array {
        $api_key = self::get_api_key();
        if ( empty($api_key) ) {
            return [ 'success' => false, 'message' => 'SendGrid API key not configured.' ];
        }

        // Parse "to" field — support comma-separated list
        $to_raw = $params['to'] ?? '';
        $to_list = array_filter( array_map('trim', explode(',', $to_raw)) );
        if ( empty($to_list) ) {
            return [ 'success' => false, 'message' => 'No recipient specified.' ];
        }

        $personalizations = [
            'to' => array_map( function($email) {
                $email = trim($email);
                if ( preg_match('/^(.+?)\s*<(.+?)>$/', $email, $m) ) {
                    return [ 'email' => sanitize_email($m[2]), 'name' => sanitize_text_field($m[1]) ];
                }
                return [ 'email' => sanitize_email($email) ];
            }, $to_list ),
        ];

        // CC
        if ( ! empty($params['cc']) ) {
            $cc_list = array_filter( array_map('trim', explode(',', $params['cc'])) );
            if ( $cc_list ) {
                $personalizations['cc'] = array_map( function($e) {
                    return [ 'email' => sanitize_email(trim($e)) ];
                }, $cc_list );
            }
        }

        // BCC
        if ( ! empty($params['bcc']) ) {
            $bcc_list = array_filter( array_map('trim', explode(',', $params['bcc'])) );
            if ( $bcc_list ) {
                $personalizations['bcc'] = array_map( function($e) {
                    return [ 'email' => sanitize_email(trim($e)) ];
                }, $bcc_list );
            }
        }

        $body = [
            'personalizations' => [ $personalizations ],
            'from' => [
                'email' => self::get_from_email(),
                'name'  => self::get_from_name(),
            ],
            'subject' => wp_strip_all_tags( $params['subject'] ?? '(no subject)' ),
            'content' => [],
        ];

        // Reply-To
        if ( ! empty($params['reply_to']) ) {
            $body['reply_to'] = [ 'email' => sanitize_email($params['reply_to']) ];
        }

        // Body
        if ( ! empty($params['body_html']) ) {
            $body['content'][] = [ 'type' => 'text/html',  'value' => $params['body_html'] ];
        }
        if ( ! empty($params['body_text']) ) {
            $body['content'][] = [ 'type' => 'text/plain', 'value' => $params['body_text'] ];
        }
        if ( empty($body['content']) ) {
            $body['content'][] = [ 'type' => 'text/plain', 'value' => '' ];
        }

        // Attachments
        if ( ! empty($params['attachments']) && is_array($params['attachments']) ) {
            $body['attachments'] = [];
            foreach ( $params['attachments'] as $att ) {
                if ( empty($att['stored_path']) || ! file_exists($att['stored_path']) ) continue;
                $content = base64_encode( file_get_contents($att['stored_path']) );
                if ( ! $content ) continue;
                $body['attachments'][] = [
                    'content'     => $content,
                    'filename'    => $att['filename'],
                    'type'        => $att['content_type'] ?? 'application/octet-stream',
                    'disposition' => 'attachment',
                ];
            }
        }

        $response = wp_remote_post( 'https://api.sendgrid.com/v3/mail/send', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json',
            ],
            'body'    => wp_json_encode($body),
            'timeout' => 30,
        ] );

        if ( is_wp_error($response) ) {
            return [ 'success' => false, 'message' => $response->get_error_message() ];
        }

        $code = wp_remote_retrieve_response_code($response);
        if ( $code >= 200 && $code < 300 ) {
            return [ 'success' => true, 'message' => 'Email sent.' ];
        }

        $resp_body = wp_remote_retrieve_body($response);
        $decoded   = json_decode($resp_body, true);
        $msg       = $decoded['errors'][0]['message'] ?? "SendGrid error (HTTP $code)";
        return [ 'success' => false, 'message' => $msg, 'http_code' => $code, 'raw' => $resp_body ];
    }

    // ============================================================
    // FORWARD EMAIL
    // ============================================================
    public static function forward_email( int $email_id, string $to, string $note = '' ): array {
        $email = FAFO_Email_Database::get_email( $email_id );
        if ( ! $email ) {
            return [ 'success' => false, 'message' => 'Email not found.' ];
        }

        $fwd_header = '---------- Forwarded message ----------<br>'
                    . 'From: ' . esc_html($email->from_name . ' <' . $email->from_email . '>') . '<br>'
                    . 'Date: ' . esc_html($email->received_at) . '<br>'
                    . 'Subject: ' . esc_html($email->subject) . '<br>'
                    . 'To: ' . esc_html($email->to_email) . '<br><br>';

        $body_html = '';
        if ( $note ) {
            $body_html .= '<p>' . nl2br(esc_html($note)) . '</p><hr>';
        }
        $body_html .= $fwd_header . ( $email->body_html ?: nl2br(esc_html($email->body_text ?? '')) );

        $attachments = FAFO_Email_Database::get_attachments( $email_id );

        $result = self::send_email([
            'to'          => $to,
            'subject'     => 'Fwd: ' . $email->subject,
            'body_html'   => $body_html,
            'body_text'   => ( $note ? $note . "\n\n---------- Forwarded message ----------\n" : '' ) . ( $email->body_text ?? '' ),
            'attachments' => $attachments,
        ]);

        if ( $result['success'] ) {
            // Save forwarded copy in sent folder
            $fwd_id = FAFO_Email_Database::insert_email([
                'folder'          => 'sent',
                'from_email'      => self::get_from_email(),
                'from_name'       => self::get_from_name(),
                'to_email'        => $to,
                'subject'         => 'Fwd: ' . $email->subject,
                'body_html'       => $body_html,
                'body_text'       => $email->body_text,
                'is_read'         => 1,
                'has_attachments' => count($attachments) > 0 ? 1 : 0,
                'received_at'     => current_time('mysql'),
            ]);

            // Mark original as forwarded
            FAFO_Email_Database::update_email( $email_id, ['is_read' => 1] );
        }

        return $result;
    }

    // ============================================================
    // VERIFY SENDGRID WEBHOOK SIGNATURE
    // ============================================================
    public static function verify_webhook_signature(
        string $payload,
        string $signature,
        string $timestamp
    ): bool {
        $secret = defined('SENDGRID_WEBHOOK_SECRET') ? SENDGRID_WEBHOOK_SECRET
                : get_option('fafo_sendgrid_webhook_secret', '');

        if ( empty($secret) ) {
            // If no secret configured, log warning but allow (not recommended for production)
            error_log('[FAFO Email] WARNING: No webhook signing secret configured. Skipping signature verification.');
            return true;
        }

        // SendGrid Event Webhook Signature Verification:
        // They use ECDSA P-256 signatures. For Inbound Parse, they use HMAC-SHA256.
        // Method: HMAC-SHA256( timestamp + payload, secret )
        $expected = hash_hmac( 'sha256', $timestamp . $payload, $secret );
        return hash_equals( $expected, strtolower($signature) );
    }
}
