<?php
/**
 * FAFO Email Client - Settings Page
 */
if ( ! defined('ABSPATH') ) exit;
if ( ! current_user_can('manage_options') ) wp_die('Forbidden');

// Save settings
if ( isset($_POST['fafo_email_settings_nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fafo_email_settings_nonce'] ) ), 'fafo_email_settings' ) ) {
    $fields = [
        'fafo_sendgrid_api_key'          => 'sanitize_text_field',
        'fafo_sendgrid_webhook_secret'   => 'sanitize_text_field',
        'fafo_email_from_address'        => 'sanitize_email',
        'fafo_email_from_name'           => 'sanitize_text_field',
        'fafo_email_notify_admin'        => 'sanitize_text_field',
    ];
    foreach ( $fields as $key => $sanitizer ) {
        if ( isset($_POST[$key]) ) {
            update_option( $key, $sanitizer( $_POST[$key] ) );
        }
    }
    $saved = true;
}

$api_key       = get_option('fafo_sendgrid_api_key', '');
$webhook_sec   = get_option('fafo_sendgrid_webhook_secret', '');
$from_addr     = get_option('fafo_email_from_address', get_option('admin_email'));
$from_name     = get_option('fafo_email_from_name', get_option('blogname'));
$notify_admin  = get_option('fafo_email_notify_admin', '0');
$webhook_url   = rest_url('fafo-email/v1/inbound');
?>
<div class="wrap fec-settings-wrap">
    <h1 style="font-size:22px;font-weight:700;margin-bottom:20px;"><i class="fas fa-cog" style="color:#002868;"></i> FAFO Email Settings</h1>

    <?php if ( ! empty($saved) ) : ?>
    <div class="notice notice-success is-dismissible"><p><strong>Settings saved.</strong></p></div>
    <?php endif; ?>

    <form method="post" action="">
        <?php wp_nonce_field('fafo_email_settings','fafo_email_settings_nonce'); ?>

        <!-- SendGrid API -->
        <div class="fec-settings-section">
            <h3><i class="fas fa-key"></i> SendGrid API Configuration</h3>

            <div class="fec-form-row">
                <label>SendGrid API Key</label>
                <input type="password" name="fafo_sendgrid_api_key"
                       value="<?php echo esc_attr($api_key); ?>"
                       placeholder="SG.xxxxxxxxxxxx...">
                <div class="fec-form-desc">
                    Found in your SendGrid account under Settings → API Keys. Requires <strong>Mail Send</strong> permission.
                    Alternatively, define <code>SENDGRID_API_KEY</code> in wp-config.php.
                </div>
            </div>

            <div class="fec-form-row">
                <label>Webhook Signing Secret</label>
                <input type="password" name="fafo_sendgrid_webhook_secret"
                       value="<?php echo esc_attr($webhook_sec); ?>"
                       placeholder="Signing secret from SendGrid Event Settings">
                <div class="fec-form-desc">
                    Optional but recommended. Found in SendGrid → Settings → Mail Settings → Event Webhook → Signing Key.
                    Used to verify that webhook requests genuinely come from SendGrid.
                    Alternatively, define <code>SENDGRID_WEBHOOK_SECRET</code> in wp-config.php.
                </div>
            </div>
        </div>

        <!-- Inbound Parse -->
        <div class="fec-settings-section">
            <h3><i class="fas fa-inbox"></i> SendGrid Inbound Parse Setup</h3>

            <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:16px;">
                <tr style="background:#f8f9fa;border-bottom:1px solid #e0e0e0;">
                    <th style="padding:10px 14px;text-align:left;font-weight:600;width:220px;">Setting</th>
                    <th style="padding:10px 14px;text-align:left;font-weight:600;">Value</th>
                </tr>
                <tr style="border-bottom:1px solid #f1f1f1;">
                    <td style="padding:10px 14px;font-weight:600;">Receiving Domain</td>
                    <td style="padding:10px 14px;"><code style="background:#f1f3f4;padding:2px 8px;border-radius:3px;font-size:13px;">inbound.foramericafirstonly.com</code></td>
                </tr>
                <tr style="border-bottom:1px solid #f1f1f1;">
                    <td style="padding:10px 14px;font-weight:600;">MX Record</td>
                    <td style="padding:10px 14px;"><code style="background:#f1f3f4;padding:2px 8px;border-radius:3px;font-size:13px;">MX 10 mx.sendgrid.net</code> <em style="color:#5f6368;">(set on inbound.foramericafirstonly.com)</em></td>
                </tr>
                <tr style="border-bottom:1px solid #f1f1f1;">
                    <td style="padding:10px 14px;font-weight:600;">Destination Webhook URL</td>
                    <td style="padding:10px 14px;">
                        <code style="background:#f1f3f4;padding:2px 8px;border-radius:3px;font-size:13px;"><?php echo esc_html($webhook_url); ?></code>
                        <button type="button" onclick="navigator.clipboard.writeText('<?php echo esc_js($webhook_url); ?>');this.textContent='Copied!';" style="margin-left:8px;padding:3px 8px;font-size:12px;cursor:pointer;border:1px solid #dadce0;border-radius:3px;background:#fff;">Copy</button>
                    </td>
                </tr>
                <tr style="border-bottom:1px solid #f1f1f1;">
                    <td style="padding:10px 14px;font-weight:600;">Check Spam</td>
                    <td style="padding:10px 14px;">Enabled (recommended)</td>
                </tr>
                <tr>
                    <td style="padding:10px 14px;font-weight:600;">Post full MIME</td>
                    <td style="padding:10px 14px;">OFF (use parsed fields)</td>
                </tr>
            </table>

            <div style="background:#e8f5e9;border:1px solid #a5d6a7;border-radius:6px;padding:14px 18px;">
                <strong style="color:#2e7d32;"><i class="fas fa-info-circle"></i> Setup Instructions</strong>
                <ol style="font-size:13px;color:#444;margin:8px 0 0;padding-left:20px;line-height:2;">
                    <li>Log into SendGrid → Settings → Inbound Parse</li>
                    <li>Click <strong>Add Host &amp; URL</strong></li>
                    <li>Enter hostname: <code>inbound.foramericafirstonly.com</code></li>
                    <li>Paste the webhook URL above as the destination</li>
                    <li>At your DNS registrar, add: <code>inbound.foramericafirstonly.com MX 10 mx.sendgrid.net</code></li>
                    <li>Wait for DNS to propagate (up to 48 hours)</li>
                    <li>Test by emailing <code>anything@inbound.foramericafirstonly.com</code></li>
                </ol>
            </div>
        </div>

        <!-- Sender Settings -->
        <div class="fec-settings-section">
            <h3><i class="fas fa-paper-plane"></i> Outbound Email Settings</h3>

            <div class="fec-form-row">
                <label>From Email Address</label>
                <input type="email" name="fafo_email_from_address"
                       value="<?php echo esc_attr($from_addr); ?>"
                       placeholder="noreply@foramericafirstonly.com">
                <div class="fec-form-desc">Must be verified in your SendGrid Sender Authentication.</div>
            </div>

            <div class="fec-form-row">
                <label>From Name</label>
                <input type="text" name="fafo_email_from_name"
                       value="<?php echo esc_attr($from_name); ?>"
                       placeholder="FAFO News">
            </div>
        </div>

        <!-- Notifications -->
        <div class="fec-settings-section">
            <h3><i class="fas fa-bell"></i> Notifications</h3>
            <div class="fec-form-row">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="fafo_email_notify_admin" value="1"
                           <?php checked($notify_admin, '1'); ?> style="width:auto;">
                    Email WordPress admin when a new inbound email is received
                </label>
                <div class="fec-form-desc">Admin email: <?php echo esc_html(get_option('admin_email')); ?></div>
            </div>
        </div>

        <!-- Status -->
        <div class="fec-settings-section">
            <h3><i class="fas fa-database"></i> Database Status</h3>
            <?php
            global $wpdb;
            $email_count = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}fafo_emails");
            $att_count   = (int)$wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}fafo_email_attachments");
            $unread      = FAFO_Email_Database::get_unread_count();
            ?>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                <div style="background:#f8f9fa;border-radius:6px;padding:16px;text-align:center;">
                    <div style="font-size:2rem;font-weight:700;color:#002868;"><?php echo number_format($email_count); ?></div>
                    <div style="font-size:13px;color:#5f6368;">Total Emails</div>
                </div>
                <div style="background:#f8f9fa;border-radius:6px;padding:16px;text-align:center;">
                    <div style="font-size:2rem;font-weight:700;color:#C8102E;"><?php echo number_format($unread); ?></div>
                    <div style="font-size:13px;color:#5f6368;">Unread</div>
                </div>
                <div style="background:#f8f9fa;border-radius:6px;padding:16px;text-align:center;">
                    <div style="font-size:2rem;font-weight:700;color:#5f6368;"><?php echo number_format($att_count); ?></div>
                    <div style="font-size:13px;color:#5f6368;">Attachments</div>
                </div>
            </div>
            <p style="margin-top:14px;font-size:13px;color:#5f6368;">
                Attachment storage: <code><?php echo esc_html(FAFO_EMAIL_UPLOAD_DIR); ?></code>
                (<?php echo file_exists(FAFO_EMAIL_UPLOAD_DIR) ? '<span style="color:#2e7d32;font-weight:600;">exists</span>' : '<span style="color:#c62828;font-weight:600;">missing — will be created on first upload</span>'; ?>)
            </p>
            <p style="margin-bottom:0;">
                <button type="button" onclick="if(confirm('Reinstall DB tables?')) { window.location.href='<?php echo esc_url( admin_url('admin.php?page=fafo-email-settings&fafo_reinstall=1&_nonce=' . wp_create_nonce('fafo_reinstall')) ); ?>'; }" style="padding:6px 14px;font-size:13px;border:1px solid #dadce0;border-radius:4px;background:#fff;cursor:pointer;">
                    Reinstall DB Tables
                </button>
            </p>
            <?php
            if ( ! empty($_GET['fafo_reinstall']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_nonce'] ?? '' ) ), 'fafo_reinstall' ) ) {
                FAFO_Email_Database::install();
                echo '<p style="color:#2e7d32;font-weight:600;"><i class="fas fa-check-circle"></i> Tables reinstalled.</p>';
            }
            ?>
        </div>

        <p>
            <button type="submit" class="fec-save-btn">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </p>
    </form>
</div>
