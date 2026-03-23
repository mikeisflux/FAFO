<?php
/**
 * Template Name: Newsletter
 *
 * Editable: WP Admin → Pages → Newsletter (body content/benefits)
 */

// Handle form submission before headers are sent
$nl_success = false;
$nl_error   = false;
if ( isset( $_POST['nl_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nl_nonce'] ) ), 'fafo_nl' ) ) {
    $nl_email = sanitize_email( $_POST['nl_email'] ?? '' );
    if ( is_email( $nl_email ) ) {
        $nl_success = true;
    } else {
        $nl_error = true;
    }
}

if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<!-- HERO with Signup Form -->
<div style="background:linear-gradient(135deg,#C8102E 0%,#8b0000 100%);padding:60px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <i class="fas fa-bolt" style="font-size:3rem;color:#FFD700;margin-bottom:16px;display:block;"></i>
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">STAY INFORMED. STAY FREE.</h1>
    <p style="font-size:1.15rem;color:rgba(255,255,255,0.9);max-width:550px;margin:0 auto 24px;">
        Get FAFO's breaking news, investigative reports, and daily briefings delivered straight to your inbox — before Big Tech can censor it.
    </p>
    <?php if ( $nl_success ) : ?>
    <div style="background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.3);color:#fff;padding:14px 20px;border-radius:6px;display:inline-block;">
        <i class="fas fa-check-circle"></i> <strong>You're in, patriot!</strong> Check your inbox for a confirmation email.
    </div>
    <?php else : ?>
    <form method="post" action="" style="display:flex;gap:12px;max-width:480px;margin:0 auto;justify-content:center;">
        <?php wp_nonce_field('fafo_nl','nl_nonce'); ?>
        <input type="email" name="nl_email" placeholder="Enter your email address..." required
               style="flex:1;padding:14px 18px;border:none;border-radius:4px;font-size:1rem;min-width:0;">
        <button type="submit" style="background:#FFD700;color:#002868;border:none;padding:14px 24px;font-family:var(--font-head);font-weight:700;font-size:0.9rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;cursor:pointer;white-space:nowrap;">
            SUBSCRIBE FREE
        </button>
    </form>
    <p style="font-size:0.78rem;color:rgba(255,255,255,0.6);margin:10px 0 0;">No spam. No censorship. Unsubscribe anytime.</p>
    <?php endif; ?>
</div>

<!-- Page content from WordPress editor (benefits, description, etc.) -->
<div class="entry-content" style="max-width:100%;">
    <?php the_content(); ?>
</div>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget" style="background:linear-gradient(135deg,#002868,#001540);color:#fff;border-radius:8px;padding:20px;">
        <h3 style="font-family:var(--font-head);font-size:0.85rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#FFD700;margin:0 0 12px;"><i class="fas fa-envelope"></i> Quick Subscribe</h3>
        <form method="post" action="">
            <?php wp_nonce_field('fafo_nl','nl_nonce'); ?>
            <input type="email" name="nl_email" placeholder="Your email..." required
                   style="width:100%;padding:10px 14px;border:none;border-radius:4px;font-size:0.9rem;margin-bottom:8px;box-sizing:border-box;">
            <button type="submit" style="width:100%;background:#C8102E;color:#fff;border:none;padding:10px;font-family:var(--font-head);font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;cursor:pointer;">
                <i class="fas fa-bolt"></i> SUBSCRIBE FREE
            </button>
        </form>
        <p style="font-size:0.72rem;color:rgba(255,255,255,0.5);margin:8px 0 0;text-align:center;">No spam. Unsubscribe anytime.</p>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
