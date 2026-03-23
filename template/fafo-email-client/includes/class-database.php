<?php
/**
 * FAFO Email - Database Layer
 */

if ( ! defined('ABSPATH') ) exit;

class FAFO_Email_Database {

    // ============================================================
    // INSTALL / CREATE TABLES
    // ============================================================
    public static function install() {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();

        $sql_emails = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fafo_emails (
            id             BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            message_id     VARCHAR(500)  DEFAULT NULL,
            thread_id      VARCHAR(500)  DEFAULT NULL,
            folder         ENUM('inbox','sent','drafts','trash','spam','starred') NOT NULL DEFAULT 'inbox',
            from_email     VARCHAR(255)  NOT NULL DEFAULT '',
            from_name      VARCHAR(255)  DEFAULT NULL,
            to_email       TEXT          NOT NULL,
            cc             TEXT          DEFAULT NULL,
            bcc            TEXT          DEFAULT NULL,
            reply_to       VARCHAR(255)  DEFAULT NULL,
            subject        VARCHAR(1000) DEFAULT NULL,
            body_html      LONGTEXT      DEFAULT NULL,
            body_text      LONGTEXT      DEFAULT NULL,
            is_read        TINYINT(1)    NOT NULL DEFAULT 0,
            is_starred     TINYINT(1)    NOT NULL DEFAULT 0,
            has_attachments TINYINT(1)   NOT NULL DEFAULT 0,
            created_at     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
            received_at    DATETIME      DEFAULT NULL,
            sendgrid_raw   LONGTEXT      DEFAULT NULL,
            PRIMARY KEY (id),
            KEY idx_folder  (folder),
            KEY idx_read    (is_read),
            KEY idx_starred (is_starred),
            KEY idx_message_id (message_id(191))
        ) $charset;";

        $sql_attachments = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}fafo_email_attachments (
            id           BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            email_id     BIGINT(20) UNSIGNED NOT NULL,
            filename     VARCHAR(500) NOT NULL,
            content_type VARCHAR(200) DEFAULT NULL,
            file_size    BIGINT(20)   DEFAULT 0,
            stored_path  VARCHAR(1000) DEFAULT NULL,
            content_id   VARCHAR(500) DEFAULT NULL,
            created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_email_id (email_id)
        ) $charset;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql_emails );
        dbDelta( $sql_attachments );

        // Ensure upload directory exists
        $upload_dir = FAFO_EMAIL_UPLOAD_DIR;
        if ( ! file_exists($upload_dir) ) {
            wp_mkdir_p($upload_dir);
            file_put_contents( $upload_dir . '/.htaccess', "Deny from all\n" );
            file_put_contents( $upload_dir . '/index.php', "<?php // silence\n" );
        }

        update_option( 'fafo_email_db_version', FAFO_EMAIL_VERSION );
    }

    // ============================================================
    // INSERT EMAIL
    // ============================================================
    public static function insert_email( array $data ): int {
        global $wpdb;

        $defaults = [
            'message_id'      => null,
            'thread_id'       => null,
            'folder'          => 'inbox',
            'from_email'      => '',
            'from_name'       => null,
            'to_email'        => '',
            'cc'              => null,
            'bcc'             => null,
            'reply_to'        => null,
            'subject'         => null,
            'body_html'       => null,
            'body_text'       => null,
            'is_read'         => 0,
            'is_starred'      => 0,
            'has_attachments' => 0,
            'received_at'     => current_time('mysql'),
            'sendgrid_raw'    => null,
        ];

        $row = array_merge( $defaults, $data );
        $wpdb->insert( $wpdb->prefix . 'fafo_emails', $row );
        return (int) $wpdb->insert_id;
    }

    // ============================================================
    // GET EMAILS (list)
    // ============================================================
    public static function get_emails( array $args = [] ): array {
        global $wpdb;
        $tbl = $wpdb->prefix . 'fafo_emails';

        $defaults = [
            'folder'   => 'inbox',
            'search'   => '',
            'starred'  => null,
            'per_page' => 50,
            'page'     => 1,
            'order'    => 'DESC',
        ];
        $args = array_merge( $defaults, $args );

        $where  = [];
        $params = [];

        if ( $args['starred'] === true ) {
            $where[] = 'is_starred = 1';
        } elseif ( $args['folder'] ) {
            $where[] = 'folder = %s';
            $params[] = $args['folder'];
        }

        if ( $args['search'] ) {
            $s        = '%' . $wpdb->esc_like( $args['search'] ) . '%';
            $where[]  = '(subject LIKE %s OR from_email LIKE %s OR from_name LIKE %s OR body_text LIKE %s)';
            $params   = array_merge( $params, [ $s, $s, $s, $s ] );
        }

        $where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $order     = in_array( strtoupper($args['order']), ['ASC','DESC'] ) ? $args['order'] : 'DESC';
        $limit     = max(1, intval($args['per_page']));
        $offset    = max(0, ($args['page'] - 1) * $limit);

        $sql = "SELECT * FROM $tbl $where_sql ORDER BY received_at $order LIMIT %d OFFSET %d";
        $params[] = $limit;
        $params[] = $offset;

        return $wpdb->get_results( $wpdb->prepare( $sql, $params ) );
    }

    // ============================================================
    // GET SINGLE EMAIL
    // ============================================================
    public static function get_email( int $id ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}fafo_emails WHERE id = %d",
            $id
        ) );
    }

    // ============================================================
    // UPDATE EMAIL
    // ============================================================
    public static function update_email( int $id, array $data ): bool {
        global $wpdb;
        $result = $wpdb->update(
            $wpdb->prefix . 'fafo_emails',
            $data,
            [ 'id' => $id ]
        );
        return $result !== false;
    }

    // ============================================================
    // DELETE EMAIL (move to trash or permanent)
    // ============================================================
    public static function delete_email( int $id, bool $permanent = false ): bool {
        global $wpdb;

        if ( $permanent ) {
            // Delete attachments first
            $atts = self::get_attachments($id);
            foreach ( $atts as $att ) {
                if ( $att->stored_path && file_exists($att->stored_path) ) {
                    @unlink( $att->stored_path );
                }
            }
            $wpdb->delete( $wpdb->prefix . 'fafo_email_attachments', ['email_id' => $id] );
            $wpdb->delete( $wpdb->prefix . 'fafo_emails', ['id' => $id] );
            return true;
        }

        // Move to trash
        return self::update_email( $id, ['folder' => 'trash'] );
    }

    // ============================================================
    // COUNT EMAILS
    // ============================================================
    public static function count_emails( string $folder = 'inbox', bool $unread_only = false ): int {
        global $wpdb;
        $tbl    = $wpdb->prefix . 'fafo_emails';
        $where  = $wpdb->prepare( 'folder = %s', $folder );
        if ( $unread_only ) $where .= ' AND is_read = 0';
        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM $tbl WHERE $where" );
    }

    public static function get_unread_count(): int {
        return self::count_emails('inbox', true);
    }

    // ============================================================
    // GET FOLDER COUNTS
    // ============================================================
    public static function get_folder_counts(): array {
        global $wpdb;
        $tbl     = $wpdb->prefix . 'fafo_emails';
        $rows    = $wpdb->get_results( "SELECT folder, COUNT(*) as total, SUM(CASE WHEN is_read=0 THEN 1 ELSE 0 END) as unread FROM $tbl GROUP BY folder" );
        $counts  = [];
        foreach ( $rows as $row ) {
            $counts[ $row->folder ] = [ 'total' => (int)$row->total, 'unread' => (int)$row->unread ];
        }
        // Also count starred across folders
        $starred = (int) $wpdb->get_var("SELECT COUNT(*) FROM $tbl WHERE is_starred=1");
        $counts['starred'] = [ 'total' => $starred, 'unread' => 0 ];
        return $counts;
    }

    // ============================================================
    // ATTACHMENTS
    // ============================================================
    public static function insert_attachment( array $data ): int {
        global $wpdb;
        $wpdb->insert( $wpdb->prefix . 'fafo_email_attachments', [
            'email_id'     => $data['email_id'],
            'filename'     => $data['filename'],
            'content_type' => $data['content_type'] ?? 'application/octet-stream',
            'file_size'    => $data['size'] ?? 0,
            'stored_path'  => $data['stored_path'] ?? null,
            'content_id'   => $data['content_id'] ?? null,
        ] );
        return (int) $wpdb->insert_id;
    }

    public static function get_attachments( int $email_id ): array {
        global $wpdb;
        return $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}fafo_email_attachments WHERE email_id = %d",
            $email_id
        ) );
    }

    public static function get_attachment( int $att_id ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}fafo_email_attachments WHERE id = %d",
            $att_id
        ) );
    }
}
