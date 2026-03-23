<?php
/**
 * Template Name: Tip Line
 *
 * Editable: WP Admin → Pages → Tip Line (intro content)
 */

// Handle tip submission before headers are sent
$tip_success = false;
$tip_error   = '';
if ( isset( $_POST['tip_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tip_nonce'] ) ), 'fafo_tip' ) ) {
    $tip_category = sanitize_text_field(    $_POST['tip_category'] ?? '' );
    $tip_message  = sanitize_textarea_field( $_POST['tip_message']  ?? '' );
    $tip_contact  = sanitize_textarea_field( $_POST['tip_contact']  ?? 'Anonymous' );
    $tip_urgency  = sanitize_text_field(    $_POST['tip_urgency']  ?? 'Normal' );

    if ( $tip_message ) {
        $to      = fafo_contact_email('tips') ?: get_option('admin_email');
        $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
        $body    = '<p><strong>CONFIDENTIAL TIP RECEIVED</strong></p>'
                 . '<p><strong>Category:</strong> ' . esc_html($tip_category) . '</p>'
                 . '<p><strong>Urgency:</strong> '  . esc_html($tip_urgency) . '</p>'
                 . '<p><strong>Contact (optional):</strong><br>' . nl2br(esc_html($tip_contact)) . '</p>'
                 . '<p><strong>Tip Details:</strong><br>' . nl2br(esc_html($tip_message)) . '</p>'
                 . '<p><em>Submitted via FAFO Confidential Tip Line</em></p>';
        wp_mail( $to, '[FAFO CONFIDENTIAL TIP] ' . $tip_category . ' — ' . $tip_urgency, $body, $headers );
        $tip_success = true;
    } else {
        $tip_error = 'Please describe the information you want to share.';
    }
}

if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);padding:60px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <i class="fas fa-shield-alt" style="font-size:3rem;color:#FFD700;margin-bottom:16px;display:block;"></i>
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">CONFIDENTIAL TIP LINE</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:580px;margin:0 auto;">
        Government corruption. Election fraud. Deep State operations. Border coverups. If you have information that needs to get out — we want to hear from you. Your identity is protected.
    </p>
</div>

<!-- Security notice (always shown) -->
<div style="background:#0d1117;border:1px solid #30363d;border-radius:8px;padding:24px;margin-bottom:32px;display:flex;gap:16px;align-items:flex-start;">
    <i class="fas fa-lock" style="color:#3fb950;font-size:1.8rem;flex-shrink:0;margin-top:2px;"></i>
    <div>
        <h4 style="font-family:var(--font-head);font-size:0.85rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#3fb950;margin:0 0 8px;">Security &amp; Confidentiality Notice</h4>
        <p style="font-size:0.88rem;color:#8b949e;line-height:1.7;margin:0 0 6px;">All tips submitted through this form are confidential. We use TLS encryption on all submissions. We do not share tip-source information with any government agency or third party without a court order we intend to fight.</p>
        <p style="font-size:0.88rem;color:#8b949e;line-height:1.7;margin:0;"><strong style="color:#c9d1d9;">For maximum anonymity:</strong> Use the <a href="https://www.torproject.org/" target="_blank" rel="noopener noreferrer" style="color:#58a6ff;">Tor Browser</a> over public Wi-Fi not associated with you. Do not use an employer-issued device.</p>
    </div>
</div>

<!-- Optional intro from WordPress editor -->
<?php
$content = get_the_content();
if ( ! empty( trim( strip_tags( $content ) ) ) ) : ?>
<div class="entry-content" style="margin-bottom:32px;"><?php the_content(); ?></div>
<?php endif; ?>

<?php if ( $tip_success ) : ?>
<div style="background:#1c3a1c;border:1px solid #3fb950;color:#3fb950;padding:20px 24px;border-radius:6px;margin-bottom:24px;">
    <i class="fas fa-check-circle" style="font-size:1.2rem;margin-right:10px;"></i>
    <strong>Tip received securely.</strong> Thank you, patriot. Your information has been delivered to our investigative team.
</div>
<?php elseif ( $tip_error ) : ?>
<div style="background:#3d1a1a;border:1px solid #f85149;color:#f85149;padding:16px 20px;border-radius:6px;margin-bottom:24px;">
    <i class="fas fa-exclamation-triangle"></i> <?php echo esc_html($tip_error); ?>
</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:40px;">

<div>
    <h2 style="font-family:var(--font-head);font-size:1.3rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;border-bottom:3px solid #C8102E;padding-bottom:10px;margin-bottom:24px;">Submit Your Tip</h2>
    <form method="post" action="">
        <?php wp_nonce_field('fafo_tip','tip_nonce'); ?>
        <div style="margin-bottom:16px;">
            <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Category</label>
            <select name="tip_category" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;">
                <option>Government Corruption</option>
                <option>Election Fraud / Irregularity</option>
                <option>Border / Immigration</option>
                <option>FBI / DOJ / Deep State</option>
                <option>Classified Information / Whistleblower</option>
                <option>Local Government</option>
                <option>Corporate / Media Collusion</option>
                <option>Military / Intelligence</option>
                <option>Censorship / Big Tech</option>
                <option>Other</option>
            </select>
        </div>
        <div style="margin-bottom:16px;">
            <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Urgency</label>
            <select name="tip_urgency" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;">
                <option>Normal</option>
                <option>High — Time Sensitive</option>
                <option>Critical — Breaking / Imminent</option>
            </select>
        </div>
        <div style="margin-bottom:16px;">
            <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Your Information <span style="font-weight:400;color:#888;">(Optional — leave blank to remain anonymous)</span></label>
            <textarea name="tip_contact" rows="3" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;resize:vertical;" placeholder="Secure email, Signal number, etc.&#10;Leave blank to submit anonymously."></textarea>
        </div>
        <div style="margin-bottom:20px;">
            <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Tip Details <span style="color:#C8102E;">*</span></label>
            <textarea name="tip_message" required rows="10" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;resize:vertical;" placeholder="Describe the information you have. Be as specific as possible:&#10;&#10;• Who is involved?&#10;• What happened?&#10;• When and where?&#10;• What evidence exists?"></textarea>
        </div>
        <button type="submit" style="background:#C8102E;color:#fff;border:none;padding:14px 32px;font-family:var(--font-head);font-weight:700;font-size:0.95rem;letter-spacing:0.08em;text-transform:uppercase;border-radius:4px;cursor:pointer;">
            <i class="fas fa-shield-alt"></i> Submit Tip Securely
        </button>
    </form>
</div>

<div>
    <h2 style="font-family:var(--font-head);font-size:1.3rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;border-bottom:3px solid #C8102E;padding-bottom:10px;margin-bottom:24px;">Source Protection</h2>
    <div style="display:flex;flex-direction:column;gap:14px;">
        <div style="background:#F5F5F0;padding:16px;border-radius:6px;border-left:3px solid #002868;">
            <h4 style="font-family:var(--font-head);font-size:0.8rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#002868;margin:0 0 6px;"><i class="fas fa-gavel"></i> &nbsp;Legal Protection</h4>
            <p style="font-size:0.82rem;color:#555;line-height:1.6;margin:0;">FAFO asserts journalist shield protections and will fight any subpoena seeking to identify our sources.</p>
        </div>
        <div style="background:#F5F5F0;padding:16px;border-radius:6px;border-left:3px solid #C8102E;">
            <h4 style="font-family:var(--font-head);font-size:0.8rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#002868;margin:0 0 6px;"><i class="fas fa-user-secret"></i> &nbsp;Anonymous Submission</h4>
            <p style="font-size:0.82rem;color:#555;line-height:1.6;margin:0;">You are never required to identify yourself. We can act on tips with zero information about your identity.</p>
        </div>
        <div style="background:#002868;color:#fff;padding:16px;border-radius:6px;">
            <h4 style="font-family:var(--font-head);font-size:0.8rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#FFD700;margin:0 0 8px;">What We Cover</h4>
            <ul style="font-size:0.83rem;color:rgba(255,255,255,0.8);line-height:1.8;margin:0;padding-left:20px;">
                <li>Government corruption at any level</li>
                <li>Election fraud and irregularities</li>
                <li>Border and immigration coverups</li>
                <li>Military and intelligence abuses</li>
                <li>Deep State operations</li>
                <li>Corporate and media collusion</li>
            </ul>
        </div>
    </div>
</div>

</div>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-envelope"></i> Contact Newsroom</h3>
        <div class="widget-body">
            <p style="font-size:0.88rem;color:#555;margin:0 0 12px;">For non-sensitive tips or general contact:</p>
            <a href="<?php echo esc_url( fafo_page_link('contact') ); ?>" style="display:block;background:#002868;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;">Contact Newsroom</a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
