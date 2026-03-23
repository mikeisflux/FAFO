<?php
/**
 * Plugin Name: FAFO Email Client
 * Plugin URI:  https://foramericafirstonly.com
 * Description: Full-featured inbound email client for FAFO News via SendGrid Inbound Parse. Gmail-like UI with CRUD, attachments, forwarding, and webhook signature verification.
 * Version:     1.0.0
 * Author:      FAFO News
 * License:     Private
 * Text Domain: fafo-email
 *
 * SendGrid Inbound Parse Setup:
 *   - Receiving Domain: inbound.foramericafirstonly.com
 *   - Webhook URL: https://yourdomain.com/wp-json/fafo-email/v1/inbound
 *   - Check "POST the raw, full MIME message" = OFF (use parsed)
 *   - Enable "Spam Check" = optional
 *   - Signing key stored in wp-config.php as SENDGRID_WEBHOOK_SECRET
 *   - SendGrid API key stored in wp-config.php as SENDGRID_API_KEY
 *   - From/reply address: anything @inbound.foramericafirstonly.com
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'FAFO_EMAIL_VERSION',     '1.0.0' );
define( 'FAFO_EMAIL_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'FAFO_EMAIL_PLUGIN_URL',  plugin_dir_url( __FILE__ ) );
define( 'FAFO_EMAIL_UPLOAD_DIR',  WP_CONTENT_DIR . '/fafo-email-uploads' );
define( 'FAFO_EMAIL_UPLOAD_URL',  WP_CONTENT_URL . '/fafo-email-uploads' );

// Load component files
require_once FAFO_EMAIL_PLUGIN_DIR . 'includes/class-database.php';
require_once FAFO_EMAIL_PLUGIN_DIR . 'includes/class-sendgrid.php';
require_once FAFO_EMAIL_PLUGIN_DIR . 'includes/class-webhook.php';
require_once FAFO_EMAIL_PLUGIN_DIR . 'includes/class-rest-api.php';

// ============================================================
// ACTIVATION / DEACTIVATION
// ============================================================
register_activation_hook( __FILE__,   [ 'FAFO_Email_Database', 'install' ] );
register_deactivation_hook( __FILE__, function() { /* keep data on deactivate */ } );

// ============================================================
// INIT
// ============================================================
add_action( 'plugins_loaded', 'fafo_email_init' );
function fafo_email_init() {
    FAFO_Email_REST_API::register_routes();
}

// ============================================================
// ADMIN MENU
// ============================================================
add_action( 'admin_menu', 'fafo_email_admin_menu' );
function fafo_email_admin_menu() {
    $unread = FAFO_Email_Database::get_unread_count();
    $badge  = $unread ? ' <span class="awaiting-mod">' . intval($unread) . '</span>' : '';

    add_menu_page(
        __( 'FAFO Email', 'fafo-email' ),
        __( 'FAFO Email', 'fafo-email' ) . $badge,
        'manage_options',
        'fafo-email',
        'fafo_email_admin_page',
        'dashicons-email-alt',
        25
    );
    add_submenu_page( 'fafo-email', 'Inbox',    'Inbox',    'manage_options', 'fafo-email',          'fafo_email_admin_page' );
    add_submenu_page( 'fafo-email', 'Sent',     'Sent',     'manage_options', 'fafo-email-sent',     'fafo_email_folder_page' );
    add_submenu_page( 'fafo-email', 'Drafts',   'Drafts',   'manage_options', 'fafo-email-drafts',   'fafo_email_folder_page' );
    add_submenu_page( 'fafo-email', 'Starred',  'Starred',  'manage_options', 'fafo-email-starred',  'fafo_email_folder_page' );
    add_submenu_page( 'fafo-email', 'Trash',    'Trash',    'manage_options', 'fafo-email-trash',    'fafo_email_folder_page' );
    add_submenu_page( 'fafo-email', 'Spam',     'Spam',     'manage_options', 'fafo-email-spam',     'fafo_email_folder_page' );
    add_submenu_page( 'fafo-email', 'Settings', 'Settings', 'manage_options', 'fafo-email-settings', 'fafo_email_settings_page' );
}

function fafo_email_admin_page()   { include FAFO_EMAIL_PLUGIN_DIR . 'admin/page-email-client.php'; }
function fafo_email_folder_page()  { include FAFO_EMAIL_PLUGIN_DIR . 'admin/page-email-client.php'; }
function fafo_email_settings_page(){ include FAFO_EMAIL_PLUGIN_DIR . 'admin/page-email-settings.php'; }

// ============================================================
// ADMIN ASSETS
// ============================================================
add_action( 'admin_enqueue_scripts', 'fafo_email_admin_assets' );
function fafo_email_admin_assets( $hook ) {
    if ( strpos( $hook, 'fafo-email' ) === false ) return;

    wp_enqueue_style(
        'fafo-email-client',
        FAFO_EMAIL_PLUGIN_URL . 'admin/css/email-client.css',
        [],
        FAFO_EMAIL_VERSION
    );

    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );

    wp_enqueue_script(
        'fafo-email-client',
        FAFO_EMAIL_PLUGIN_URL . 'admin/js/email-client.js',
        [ 'jquery' ],
        FAFO_EMAIL_VERSION,
        true
    );

    wp_localize_script( 'fafo-email-client', 'fafoEmailData', [
        'restUrl'   => esc_url_raw( rest_url('fafo-email/v1/') ),
        'nonce'     => wp_create_nonce('wp_rest'),
        'adminUrl'  => admin_url('admin.php'),
        'fromEmail' => defined('FAFO_EMAIL_FROM') ? FAFO_EMAIL_FROM : get_option('admin_email'),
        'fromName'  => get_option('blogname'),
        'uploadUrl' => admin_url('admin-ajax.php') . '?action=fafo_email_upload',
        'ajaxNonce' => wp_create_nonce('fafo_email_ajax'),
    ] );
}

// ============================================================
// AJAX: File upload for attachments
// ============================================================
add_action( 'wp_ajax_fafo_email_upload', 'fafo_email_handle_upload' );
function fafo_email_handle_upload() {
    check_ajax_referer( 'fafo_email_ajax', 'nonce' );
    if ( ! current_user_can('manage_options') ) wp_die('Forbidden', 403);

    if ( empty( $_FILES['file'] ) ) {
        wp_send_json_error(['message' => 'No file uploaded.']);
    }

    $upload_dir = FAFO_EMAIL_UPLOAD_DIR . '/outgoing';
    if ( ! file_exists($upload_dir) ) {
        wp_mkdir_p($upload_dir);
        file_put_contents( $upload_dir . '/.htaccess', "Deny from all\n" );
    }

    $file     = $_FILES['file'];
    $filename = sanitize_file_name( $file['name'] );
    $destname = uniqid() . '_' . $filename;
    $destpath = $upload_dir . '/' . $destname;

    if ( ! move_uploaded_file( $file['tmp_name'], $destpath ) ) {
        wp_send_json_error(['message' => 'Failed to save file.']);
    }

    wp_send_json_success([
        'filename'     => $filename,
        'stored_name'  => $destname,
        'path'         => $destpath,
        'size'         => filesize($destpath),
        'content_type' => $file['type'],
    ]);
}

// ============================================================
// AJAX: Mark read/star via quick action
// ============================================================
add_action( 'wp_ajax_fafo_email_quick_action', 'fafo_email_quick_action' );
function fafo_email_quick_action() {
    check_ajax_referer('fafo_email_ajax','nonce');
    if ( ! current_user_can('manage_options') ) wp_die('Forbidden', 403);

    $id     = intval( $_POST['id'] ?? 0 );
    $action = sanitize_text_field( $_POST['email_action'] ?? '' );

    if ( ! $id ) wp_send_json_error(['message' => 'Invalid ID']);

    switch ( $action ) {
        case 'mark_read':
            FAFO_Email_Database::update_email( $id, ['is_read' => 1] );
            break;
        case 'mark_unread':
            FAFO_Email_Database::update_email( $id, ['is_read' => 0] );
            break;
        case 'star':
            FAFO_Email_Database::update_email( $id, ['is_starred' => 1] );
            break;
        case 'unstar':
            FAFO_Email_Database::update_email( $id, ['is_starred' => 0] );
            break;
        case 'trash':
            FAFO_Email_Database::update_email( $id, ['folder' => 'trash'] );
            break;
        case 'restore':
            FAFO_Email_Database::update_email( $id, ['folder' => 'inbox'] );
            break;
        case 'spam':
            FAFO_Email_Database::update_email( $id, ['folder' => 'spam'] );
            break;
        case 'delete':
            FAFO_Email_Database::delete_email( $id, true );
            break;
        default:
            wp_send_json_error(['message' => 'Unknown action']);
    }

    wp_send_json_success(['message' => 'Done']);
}
