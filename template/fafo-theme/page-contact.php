<?php
/**
 * Template Name: Contact Us
 *
 * Editable: WP Admin → Pages → Contact Us (intro text)
 * Contact emails: WP Admin → FAFO Settings → Contact tab
 */

// Handle form submission (must be before get_header)
$contact_success = false;
$contact_error   = '';
if ( isset( $_POST['fafo_contact_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fafo_contact_nonce'] ) ), 'fafo_contact' ) ) {
    $name    = sanitize_text_field( $_POST['contact_name']    ?? '' );
    $email   = sanitize_email(      $_POST['contact_email']   ?? '' );
    $subject = sanitize_text_field( $_POST['contact_subject'] ?? '' );
    $dept    = sanitize_text_field( $_POST['contact_dept']    ?? 'General' );
    $message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

    if ( $name && $email && $message && is_email( $email ) ) {
        $to      = fafo_contact_email('newsroom') ?: get_option('admin_email');
        $headers = [ 'Content-Type: text/html; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' ];
        $body    = '<p><strong>From:</strong> ' . esc_html($name) . ' (' . esc_html($email) . ')</p>'
                 . '<p><strong>Department:</strong> ' . esc_html($dept) . '</p>'
                 . '<p><strong>Subject:</strong> ' . esc_html($subject) . '</p>'
                 . '<p><strong>Message:</strong><br>' . nl2br( esc_html($message) ) . '</p>';
        wp_mail( $to, '[FAFO Contact] ' . $subject, $body, $headers );
        $contact_success = true;
    } else {
        $contact_error = 'Please fill out all required fields with a valid email address.';
    }
}

if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">CONTACT FAFO NEWS</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;">
        We want to hear from patriots. Tips, corrections, story ideas, advertising inquiries — reach out.
    </p>
</div>

<?php if ( $contact_success ) : ?>
<div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:16px 20px;border-radius:4px;margin-bottom:24px;">
    <i class="fas fa-check-circle"></i> <strong>Message received!</strong> Thank you, patriot. We'll be in touch soon.
</div>
<?php endif; ?>
<?php if ( $contact_error ) : ?>
<div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:16px 20px;border-radius:4px;margin-bottom:24px;">
    <i class="fas fa-exclamation-triangle"></i> <?php echo esc_html($contact_error); ?>
</div>
<?php endif; ?>

<!-- Optional intro from the WordPress page editor -->
<?php
$content = get_the_content();
if ( ! empty( trim( strip_tags( $content ) ) ) ) : ?>
<div class="entry-content" style="margin-bottom:32px;"><?php the_content(); ?></div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;">

    <!-- Contact Form -->
    <div>
        <h2 style="font-family:var(--font-head);font-size:1.4rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;border-bottom:3px solid #C8102E;padding-bottom:10px;margin-bottom:24px;">Send Us a Message</h2>
        <form method="post" action="">
            <?php wp_nonce_field('fafo_contact','fafo_contact_nonce'); ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div>
                    <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Your Name <span style="color:#C8102E;">*</span></label>
                    <input type="text" name="contact_name" required style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;" placeholder="John Smith">
                </div>
                <div>
                    <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Email Address <span style="color:#C8102E;">*</span></label>
                    <input type="email" name="contact_email" required style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;" placeholder="john@example.com">
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Department</label>
                <select name="contact_dept" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;">
                    <option>General Inquiry</option>
                    <option>News Tip</option>
                    <option>Letters to the Editor</option>
                    <option>Advertising</option>
                    <option>Press &amp; Media</option>
                    <option>Technical Support</option>
                    <option>Corrections</option>
                    <option>Legal / DMCA</option>
                </select>
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Subject <span style="color:#C8102E;">*</span></label>
                <input type="text" name="contact_subject" required style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;" placeholder="What's this about?">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;">Message <span style="color:#C8102E;">*</span></label>
                <textarea name="contact_message" required rows="7" style="width:100%;padding:10px 14px;border:1px solid #ddd;border-radius:4px;font-size:0.95rem;resize:vertical;" placeholder="Tell us what's on your mind..."></textarea>
            </div>
            <button type="submit" style="background:#C8102E;color:#fff;border:none;padding:14px 32px;font-family:var(--font-head);font-weight:700;font-size:0.95rem;letter-spacing:0.08em;text-transform:uppercase;border-radius:4px;cursor:pointer;">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
        </form>
    </div>

    <!-- Contact Info -->
    <div>
        <h2 style="font-family:var(--font-head);font-size:1.4rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;border-bottom:3px solid #C8102E;padding-bottom:10px;margin-bottom:24px;">Other Ways to Reach Us</h2>
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div style="background:#F5F5F0;padding:18px;border-radius:6px;border-left:4px solid #002868;">
                <h4 style="font-family:var(--font-head);font-size:0.82rem;letter-spacing:0.1em;text-transform:uppercase;color:#002868;margin:0 0 6px;"><i class="fas fa-newspaper"></i> &nbsp;Newsroom</h4>
                <p style="margin:0;font-size:0.9rem;color:#555;"><?php echo esc_html( fafo_contact_email('newsroom') ); ?></p>
            </div>
            <div style="background:#F5F5F0;padding:18px;border-radius:6px;border-left:4px solid #C8102E;">
                <h4 style="font-family:var(--font-head);font-size:0.82rem;letter-spacing:0.1em;text-transform:uppercase;color:#002868;margin:0 0 6px;"><i class="fas fa-chart-bar"></i> &nbsp;Advertising</h4>
                <p style="margin:0;font-size:0.9rem;color:#555;"><?php echo esc_html( fafo_contact_email('advertise') ); ?></p>
            </div>
            <div style="background:#F5F5F0;padding:18px;border-radius:6px;border-left:4px solid #FFD700;">
                <h4 style="font-family:var(--font-head);font-size:0.82rem;letter-spacing:0.1em;text-transform:uppercase;color:#002868;margin:0 0 6px;"><i class="fas fa-gavel"></i> &nbsp;Legal</h4>
                <p style="margin:0;font-size:0.9rem;color:#555;"><?php echo esc_html( fafo_contact_email('legal') ); ?></p>
            </div>
            <div style="background:#F5F5F0;padding:18px;border-radius:6px;border-left:4px solid #666;">
                <h4 style="font-family:var(--font-head);font-size:0.82rem;letter-spacing:0.1em;text-transform:uppercase;color:#002868;margin:0 0 6px;"><i class="fas fa-shield-alt"></i> &nbsp;Confidential Tips</h4>
                <p style="margin:0;font-size:0.9rem;color:#555;">Use our <a href="<?php echo esc_url( fafo_page_link('tip-line') ); ?>" style="color:#C8102E;font-weight:600;">secure tip line</a> for sensitive information.</p>
            </div>
        </div>
        <div style="margin-top:24px;">
            <h4 style="font-family:var(--font-head);font-size:0.82rem;letter-spacing:0.1em;text-transform:uppercase;color:#002868;margin:0 0 12px;"><i class="fas fa-share-alt"></i> &nbsp;Follow FAFO</h4>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <?php if ( fafo_social('twitter') && fafo_social('twitter') !== '#' ) : ?><a href="<?php echo esc_url( fafo_social('twitter') ); ?>" style="background:#000;color:#fff;padding:8px 14px;text-decoration:none;border-radius:4px;font-size:0.85rem;display:flex;align-items:center;gap:6px;"><i class="fab fa-x-twitter"></i> Twitter/X</a><?php endif; ?>
                <?php if ( fafo_social('facebook') && fafo_social('facebook') !== '#' ) : ?><a href="<?php echo esc_url( fafo_social('facebook') ); ?>" style="background:#1877F2;color:#fff;padding:8px 14px;text-decoration:none;border-radius:4px;font-size:0.85rem;display:flex;align-items:center;gap:6px;"><i class="fab fa-facebook-f"></i> Facebook</a><?php endif; ?>
                <?php if ( fafo_social('truth') && fafo_social('truth') !== '#' ) : ?><a href="<?php echo esc_url( fafo_social('truth') ); ?>" style="background:#85532B;color:#fff;padding:8px 14px;text-decoration:none;border-radius:4px;font-size:0.85rem;display:flex;align-items:center;gap:6px;"><i class="fas fa-flag"></i> Truth Social</a><?php endif; ?>
                <?php if ( fafo_social('rumble') && fafo_social('rumble') !== '#' ) : ?><a href="<?php echo esc_url( fafo_social('rumble') ); ?>" style="background:#85C742;color:#fff;padding:8px 14px;text-decoration:none;border-radius:4px;font-size:0.85rem;display:flex;align-items:center;gap:6px;"><i class="fas fa-video"></i> Rumble</a><?php endif; ?>
            </div>
        </div>
    </div>

</div>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-shield-alt"></i> Secure Tip Line</h3>
        <div class="widget-body">
            <p style="font-size:0.88rem;color:#555;line-height:1.6;margin:0 0 12px;">Have sensitive information? Use our secure, confidential tip line.</p>
            <a href="<?php echo esc_url( fafo_page_link('tip-line') ); ?>" style="display:block;background:#002868;color:#FFD700;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;">
                <i class="fas fa-shield-alt"></i> Submit a Tip
            </a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
