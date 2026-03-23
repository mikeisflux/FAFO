<?php
/**
 * FAFO Email - REST API Routes
 */

if ( ! defined('ABSPATH') ) exit;

class FAFO_Email_REST_API {

    public static function register_routes(): void {
        add_action( 'rest_api_init', function() {

            $ns = 'fafo-email/v1';

            // --- INBOUND WEBHOOK (no auth — SendGrid posts here) ---
            register_rest_route( $ns, '/inbound', [
                'methods'             => 'POST',
                'callback'            => [ 'FAFO_Email_Webhook', 'handle_inbound' ],
                'permission_callback' => '__return_true',
            ]);

            // --- LIST EMAILS ---
            register_rest_route( $ns, '/emails', [
                'methods'             => 'GET',
                'callback'            => [ __CLASS__, 'list_emails' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
                'args'                => [
                    'folder'   => [ 'type' => 'string', 'default' => 'inbox', 'sanitize_callback' => 'sanitize_text_field' ],
                    'search'   => [ 'type' => 'string', 'default' => '',      'sanitize_callback' => 'sanitize_text_field' ],
                    'starred'  => [ 'type' => 'boolean','default' => false ],
                    'per_page' => [ 'type' => 'integer','default' => 50  ],
                    'page'     => [ 'type' => 'integer','default' => 1   ],
                ],
            ]);

            // --- GET SINGLE EMAIL ---
            register_rest_route( $ns, '/emails/(?P<id>\d+)', [
                'methods'             => 'GET',
                'callback'            => [ __CLASS__, 'get_email' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

            // --- CREATE / SEND EMAIL ---
            register_rest_route( $ns, '/emails', [
                'methods'             => 'POST',
                'callback'            => [ __CLASS__, 'send_email' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

            // --- UPDATE EMAIL (read/star/move folder) ---
            register_rest_route( $ns, '/emails/(?P<id>\d+)', [
                'methods'             => 'PATCH',
                'callback'            => [ __CLASS__, 'update_email' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

            // --- DELETE EMAIL ---
            register_rest_route( $ns, '/emails/(?P<id>\d+)', [
                'methods'             => 'DELETE',
                'callback'            => [ __CLASS__, 'delete_email' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

            // --- FORWARD EMAIL ---
            register_rest_route( $ns, '/emails/(?P<id>\d+)/forward', [
                'methods'             => 'POST',
                'callback'            => [ __CLASS__, 'forward_email' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

            // --- DOWNLOAD ATTACHMENT ---
            register_rest_route( $ns, '/emails/(?P<id>\d+)/attachments/(?P<att_id>\d+)', [
                'methods'             => 'GET',
                'callback'            => [ __CLASS__, 'download_attachment' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

            // --- FOLDER COUNTS ---
            register_rest_route( $ns, '/counts', [
                'methods'             => 'GET',
                'callback'            => [ __CLASS__, 'get_counts' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

            // --- BULK ACTION ---
            register_rest_route( $ns, '/emails/bulk', [
                'methods'             => 'POST',
                'callback'            => [ __CLASS__, 'bulk_action' ],
                'permission_callback' => [ __CLASS__, 'auth_check' ],
            ]);

        });
    }

    public static function auth_check(): bool {
        return current_user_can('manage_options');
    }

    // ============================================================
    // LIST EMAILS
    // ============================================================
    public static function list_emails( WP_REST_Request $req ): WP_REST_Response {
        $args = [
            'folder'   => $req->get_param('folder'),
            'search'   => $req->get_param('search'),
            'starred'  => (bool) $req->get_param('starred'),
            'per_page' => (int)  $req->get_param('per_page'),
            'page'     => (int)  $req->get_param('page'),
        ];

        if ( $args['starred'] ) $args['folder'] = null;

        $emails = FAFO_Email_Database::get_emails($args);
        $counts = FAFO_Email_Database::get_folder_counts();

        // Attach attachment list to each email summary
        $result = array_map( function($email) {
            $email->attachments = FAFO_Email_Database::get_attachments((int)$email->id);
            return $email;
        }, $emails );

        return new WP_REST_Response([
            'emails' => $result,
            'counts' => $counts,
        ], 200);
    }

    // ============================================================
    // GET SINGLE EMAIL
    // ============================================================
    public static function get_email( WP_REST_Request $req ): WP_REST_Response {
        $id    = (int) $req->get_param('id');
        $email = FAFO_Email_Database::get_email($id);

        if ( ! $email ) {
            return new WP_REST_Response(['error' => 'Not found'], 404);
        }

        // Auto-mark as read when opened
        if ( ! $email->is_read ) {
            FAFO_Email_Database::update_email($id, ['is_read' => 1]);
            $email->is_read = 1;
        }

        $email->attachments = FAFO_Email_Database::get_attachments($id);
        return new WP_REST_Response($email, 200);
    }

    // ============================================================
    // SEND / SAVE EMAIL
    // ============================================================
    public static function send_email( WP_REST_Request $req ): WP_REST_Response {
        $body     = $req->get_json_params() ?: $req->get_body_params();
        $is_draft = (bool) ($body['draft'] ?? false);

        $to      = sanitize_text_field( $body['to']       ?? '' );
        $cc      = sanitize_text_field( $body['cc']       ?? '' );
        $bcc     = sanitize_text_field( $body['bcc']      ?? '' );
        $subject = sanitize_text_field( $body['subject']  ?? '(no subject)' );
        $html    = wp_kses_post(         $body['body_html'] ?? '' );
        $text    = sanitize_textarea_field( $body['body_text'] ?? '' );
        $att_ids = array_map('intval', (array)($body['attachment_ids'] ?? []));

        if ( ! $to && ! $is_draft ) {
            return new WP_REST_Response(['error' => 'Recipient required.'], 400);
        }

        // Collect pre-uploaded attachments
        $attachments = [];
        foreach ( $att_ids as $att_id ) {
            if ( $att_id ) {
                $att = FAFO_Email_Database::get_attachment($att_id);
                if ( $att ) $attachments[] = (array)$att;
            }
        }

        if ( $is_draft ) {
            // Save as draft
            $email_id = FAFO_Email_Database::insert_email([
                'folder'          => 'drafts',
                'from_email'      => get_option('admin_email'),
                'from_name'       => get_option('blogname'),
                'to_email'        => $to,
                'cc'              => $cc ?: null,
                'bcc'             => $bcc ?: null,
                'subject'         => $subject,
                'body_html'       => $html,
                'body_text'       => $text,
                'is_read'         => 1,
                'has_attachments' => count($attachments) > 0 ? 1 : 0,
                'received_at'     => current_time('mysql'),
            ]);
            return new WP_REST_Response(['id' => $email_id, 'draft' => true], 201);
        }

        // Send via SendGrid
        $result = FAFO_Email_SendGrid::send_email([
            'to'          => $to,
            'cc'          => $cc,
            'bcc'         => $bcc,
            'subject'     => $subject,
            'body_html'   => $html,
            'body_text'   => $text,
            'attachments' => $attachments,
        ]);

        if ( ! $result['success'] ) {
            return new WP_REST_Response(['error' => $result['message']], 500);
        }

        // Save to sent folder
        $email_id = FAFO_Email_Database::insert_email([
            'folder'          => 'sent',
            'from_email'      => get_option('admin_email'),
            'from_name'       => get_option('blogname'),
            'to_email'        => $to,
            'cc'              => $cc ?: null,
            'bcc'             => $bcc ?: null,
            'subject'         => $subject,
            'body_html'       => $html,
            'body_text'       => $text,
            'is_read'         => 1,
            'has_attachments' => count($attachments) > 0 ? 1 : 0,
            'received_at'     => current_time('mysql'),
        ]);

        return new WP_REST_Response(['id' => $email_id, 'sent' => true], 201);
    }

    // ============================================================
    // UPDATE EMAIL
    // ============================================================
    public static function update_email( WP_REST_Request $req ): WP_REST_Response {
        $id   = (int) $req->get_param('id');
        $body = $req->get_json_params() ?: $req->get_body_params();

        $allowed = ['is_read', 'is_starred', 'folder'];
        $update  = [];
        foreach ( $allowed as $field ) {
            if ( array_key_exists($field, $body) ) {
                if ( $field === 'folder' ) {
                    $valid_folders = ['inbox','sent','drafts','trash','spam','starred'];
                    if ( in_array($body[$field], $valid_folders) ) {
                        $update[$field] = $body[$field];
                    }
                } else {
                    $update[$field] = (int)(bool)$body[$field];
                }
            }
        }

        if ( empty($update) ) {
            return new WP_REST_Response(['error' => 'No valid fields to update.'], 400);
        }

        FAFO_Email_Database::update_email($id, $update);
        return new WP_REST_Response(['updated' => true], 200);
    }

    // ============================================================
    // DELETE EMAIL
    // ============================================================
    public static function delete_email( WP_REST_Request $req ): WP_REST_Response {
        $id        = (int) $req->get_param('id');
        $permanent = (bool) $req->get_param('permanent');

        $email = FAFO_Email_Database::get_email($id);
        if ( ! $email ) {
            return new WP_REST_Response(['error' => 'Not found'], 404);
        }

        // If already in trash, delete permanently
        if ( $email->folder === 'trash' || $permanent ) {
            FAFO_Email_Database::delete_email($id, true);
        } else {
            FAFO_Email_Database::delete_email($id, false);
        }

        return new WP_REST_Response(['deleted' => true], 200);
    }

    // ============================================================
    // FORWARD EMAIL
    // ============================================================
    public static function forward_email( WP_REST_Request $req ): WP_REST_Response {
        $id   = (int) $req->get_param('id');
        $body = $req->get_json_params() ?: $req->get_body_params();
        $to   = sanitize_text_field( $body['to']   ?? '' );
        $note = sanitize_textarea_field( $body['note'] ?? '' );

        if ( ! $to ) {
            return new WP_REST_Response(['error' => 'Forward-to address required.'], 400);
        }

        $result = FAFO_Email_SendGrid::forward_email($id, $to, $note);

        if ( ! $result['success'] ) {
            return new WP_REST_Response(['error' => $result['message']], 500);
        }

        return new WP_REST_Response(['forwarded' => true], 200);
    }

    // ============================================================
    // DOWNLOAD ATTACHMENT
    // ============================================================
    public static function download_attachment( WP_REST_Request $req ): void {
        $att_id = (int) $req->get_param('att_id');
        $att    = FAFO_Email_Database::get_attachment($att_id);

        if ( ! $att || empty($att->stored_path) || ! file_exists($att->stored_path) ) {
            status_header(404);
            echo 'Attachment not found';
            exit;
        }

        $filename = $att->filename ?: 'attachment';
        $ctype    = $att->content_type ?: 'application/octet-stream';

        header( 'Content-Type: ' . sanitize_text_field($ctype) );
        header( 'Content-Disposition: attachment; filename="' . addslashes($filename) . '"' );
        header( 'Content-Length: ' . filesize($att->stored_path) );
        header( 'Cache-Control: no-cache' );

        readfile($att->stored_path);
        exit;
    }

    // ============================================================
    // GET FOLDER COUNTS
    // ============================================================
    public static function get_counts( WP_REST_Request $req ): WP_REST_Response {
        return new WP_REST_Response( FAFO_Email_Database::get_folder_counts(), 200 );
    }

    // ============================================================
    // BULK ACTION
    // ============================================================
    public static function bulk_action( WP_REST_Request $req ): WP_REST_Response {
        $body   = $req->get_json_params() ?: $req->get_body_params();
        $ids    = array_map('intval', (array)($body['ids'] ?? []));
        $action = sanitize_text_field( $body['action'] ?? '' );

        if ( empty($ids) || ! $action ) {
            return new WP_REST_Response(['error' => 'IDs and action required.'], 400);
        }

        $processed = 0;
        foreach ( $ids as $id ) {
            if ( ! $id ) continue;
            switch ( $action ) {
                case 'mark_read':
                    FAFO_Email_Database::update_email($id, ['is_read' => 1]);
                    break;
                case 'mark_unread':
                    FAFO_Email_Database::update_email($id, ['is_read' => 0]);
                    break;
                case 'star':
                    FAFO_Email_Database::update_email($id, ['is_starred' => 1]);
                    break;
                case 'unstar':
                    FAFO_Email_Database::update_email($id, ['is_starred' => 0]);
                    break;
                case 'trash':
                    FAFO_Email_Database::update_email($id, ['folder' => 'trash']);
                    break;
                case 'spam':
                    FAFO_Email_Database::update_email($id, ['folder' => 'spam']);
                    break;
                case 'inbox':
                    FAFO_Email_Database::update_email($id, ['folder' => 'inbox']);
                    break;
                case 'delete':
                    FAFO_Email_Database::delete_email($id, true);
                    break;
            }
            $processed++;
        }

        return new WP_REST_Response(['processed' => $processed], 200);
    }
}
