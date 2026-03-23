<?php
/**
 * FAFO Email - SendGrid Inbound Parse Webhook Handler
 *
 * SendGrid Inbound Parse Config:
 *   Receiving Domain: inbound.foramericafirstonly.com
 *   Destination URL:  https://yourdomain.com/wp-json/fafo-email/v1/inbound
 *
 * MX Record: Set MX record for inbound.foramericafirstonly.com
 *   Priority: 10, Value: mx.sendgrid.net
 *
 * This handler processes emails sent to any address @inbound.foramericafirstonly.com
 * and stores them in the WordPress database.
 */

if ( ! defined('ABSPATH') ) exit;

class FAFO_Email_Webhook {

    /**
     * Process inbound email from SendGrid Inbound Parse POST
     * Handles multipart/form-data with optional file attachments
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public static function handle_inbound( WP_REST_Request $request ): WP_REST_Response {

        // ---- SIGNATURE VERIFICATION ----
        $sig       = $request->get_header('X-Twilio-Email-Event-Webhook-Signature') ?? '';
        $timestamp = $request->get_header('X-Twilio-Email-Event-Webhook-Timestamp') ?? '';

        // For Inbound Parse, SendGrid may not send these headers by default.
        // If they're present, verify; if not and a secret is configured, reject.
        $secret = defined('SENDGRID_WEBHOOK_SECRET') ? SENDGRID_WEBHOOK_SECRET
                : get_option('fafo_sendgrid_webhook_secret','');

        if ( $sig && $timestamp && $secret ) {
            $raw_body = $request->get_body();
            if ( ! FAFO_Email_SendGrid::verify_webhook_signature($raw_body, $sig, $timestamp) ) {
                error_log('[FAFO Email] Webhook signature verification FAILED');
                return new WP_REST_Response(['error' => 'Invalid signature'], 403);
            }
        } elseif ( $secret && empty($sig) ) {
            // We have a secret configured but no signature in header.
            // Some SendGrid plans don't sign Inbound Parse. Log but allow.
            error_log('[FAFO Email] Webhook received without signature header (secret is configured).');
        }

        // ---- PARSE INBOUND PARSE FIELDS ----
        $params = $request->get_params();
        $files  = $request->get_file_params();

        // Core fields from SendGrid Inbound Parse
        $from_raw  = $params['from']    ?? '';
        $to_raw    = $params['to']      ?? '';
        $cc_raw    = $params['cc']      ?? '';
        $subject   = sanitize_text_field( $params['subject']  ?? '(no subject)' );
        $body_html = wp_kses_post(        $params['html']     ?? '' );
        $body_text = sanitize_textarea_field( $params['text'] ?? '' );
        $spam_score = floatval( $params['spam_score'] ?? 0 );
        $spam_report = $params['spam_report'] ?? '';
        $message_id  = sanitize_text_field( $params['headers'] ? self::extract_message_id($params['headers']) : '' );
        $dkim        = $params['dkim']   ?? '';
        $spf         = $params['SPF']    ?? '';

        // Parse "From" into name + email
        [ $from_name, $from_email ] = self::parse_email_address( $from_raw );

        // Determine folder based on spam score
        $folder = 'inbox';
        if ( $spam_score > 5.0 ) {
            $folder = 'spam';
        }

        // Store raw payload for debugging
        $sendgrid_raw = wp_json_encode([
            'from'       => $from_raw,
            'to'         => $to_raw,
            'subject'    => $subject,
            'spam_score' => $spam_score,
            'dkim'       => $dkim,
            'spf'        => $spf,
        ]);

        // ---- INSERT EMAIL ----
        $email_id = FAFO_Email_Database::insert_email([
            'message_id'   => $message_id,
            'folder'       => $folder,
            'from_email'   => sanitize_email($from_email),
            'from_name'    => sanitize_text_field($from_name),
            'to_email'     => sanitize_text_field($to_raw),
            'cc'           => $cc_raw ? sanitize_text_field($cc_raw) : null,
            'reply_to'     => $params['reply-to'] ? sanitize_email($params['reply-to']) : null,
            'subject'      => $subject,
            'body_html'    => $body_html,
            'body_text'    => $body_text,
            'is_read'      => 0,
            'received_at'  => current_time('mysql'),
            'sendgrid_raw' => $sendgrid_raw,
        ]);

        if ( ! $email_id ) {
            error_log('[FAFO Email] Failed to insert inbound email into database');
            return new WP_REST_Response(['error' => 'Database error'], 500);
        }

        // ---- PROCESS ATTACHMENTS ----
        $attachment_count = 0;

        // SendGrid sends attachments as attachmentX files and attachment-info JSON
        $att_info = [];
        if ( ! empty($params['attachment-info']) ) {
            $att_info = json_decode( $params['attachment-info'], true ) ?? [];
        }

        // Also check charsets field for content info
        $num_attachments = intval( $params['attachments'] ?? 0 );

        for ( $i = 1; $i <= max($num_attachments, count($files)); $i++ ) {
            $field_name = 'attachment' . $i;
            if ( empty($files[$field_name]) ) continue;

            $file = $files[$field_name];
            if ( $file['error'] !== UPLOAD_ERR_OK ) continue;

            // Get metadata
            $info     = $att_info['attachment'.$i] ?? [];
            $filename = sanitize_file_name( $info['filename'] ?? $file['name'] ?? "attachment_$i" );
            $ctype    = $info['type']       ?? $file['type']    ?? 'application/octet-stream';
            $cid      = $info['content-id'] ?? null;

            // Store file
            $upload_dir = FAFO_EMAIL_UPLOAD_DIR . '/' . $email_id;
            if ( ! file_exists($upload_dir) ) {
                wp_mkdir_p($upload_dir);
            }

            $stored_name = uniqid() . '_' . $filename;
            $stored_path = $upload_dir . '/' . $stored_name;

            if ( move_uploaded_file( $file['tmp_name'], $stored_path ) ) {
                FAFO_Email_Database::insert_attachment([
                    'email_id'     => $email_id,
                    'filename'     => $filename,
                    'content_type' => $ctype,
                    'size'         => filesize($stored_path),
                    'stored_path'  => $stored_path,
                    'content_id'   => $cid ? sanitize_text_field($cid) : null,
                ]);
                $attachment_count++;
            }
        }

        // Update has_attachments flag
        if ( $attachment_count > 0 ) {
            FAFO_Email_Database::update_email( $email_id, ['has_attachments' => 1] );
        }

        // ---- OPTIONAL: Email notification to admin ----
        if ( get_option('fafo_email_notify_admin', '0') === '1' ) {
            wp_mail(
                get_option('admin_email'),
                '[FAFO Inbox] New email: ' . $subject,
                "New email from $from_name <$from_email>\nSubject: $subject\n\n" . substr($body_text, 0, 500),
                []
            );
        }

        error_log("[FAFO Email] Inbound email stored: ID=$email_id, From=$from_email, Subject=$subject, Folder=$folder, Attachments=$attachment_count");

        return new WP_REST_Response(['status' => 'ok', 'id' => $email_id], 200);
    }

    // ============================================================
    // PARSE EMAIL ADDRESS: "Name <email>" -> [name, email]
    // ============================================================
    private static function parse_email_address( string $raw ): array {
        $raw = trim($raw);
        if ( preg_match('/^(.+?)\s*<([^>]+)>$/', $raw, $m) ) {
            return [ trim($m[1], '" '), trim($m[2]) ];
        }
        return [ '', $raw ];
    }

    // ============================================================
    // EXTRACT MESSAGE-ID FROM HEADERS STRING
    // ============================================================
    private static function extract_message_id( string $headers ): string {
        if ( preg_match('/Message-ID:\s*<([^>]+)>/i', $headers, $m) ) {
            return $m[1];
        }
        return '';
    }
}
