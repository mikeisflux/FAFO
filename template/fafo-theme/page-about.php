<?php
/**
 * Template Name: About FAFO
 *
 * Editable: WP Admin → Pages → About FAFO
 */
if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:60px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:3rem;font-weight:900;letter-spacing:0.04em;color:#FFD700;margin:0 0 12px;">
        ABOUT <span style="color:#C8102E;">FAFO</span> NEWS
    </h1>
    <p style="font-size:1.2rem;color:rgba(255,255,255,0.85);max-width:600px;margin:0 auto;line-height:1.7;">
        For America First Only — Bold, unapologetic conservative journalism for the American patriot.
    </p>
</div>

<div class="entry-content" style="max-width:100%;">
    <?php the_content(); ?>
</div>

<!-- JOIN THE MOVEMENT CTA -->
<section style="background:linear-gradient(135deg,#C8102E 0%,#8b0000 100%);padding:40px;border-radius:8px;text-align:center;color:#fff;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.8rem;font-weight:900;margin:0 0 12px;">JOIN THE MOVEMENT</h3>
    <p style="font-size:1rem;opacity:0.9;margin:0 0 24px;">Get FAFO breaking news in your inbox. No spin. No censorship. Just the truth.</p>
    <a href="<?php echo esc_url( fafo_page_link('newsletter') ); ?>"
       style="display:inline-block;background:#FFD700;color:#002868;padding:12px 32px;font-family:var(--font-head);font-weight:700;font-size:0.95rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;text-decoration:none;">
        <i class="fas fa-bolt"></i> SUBSCRIBE FREE
    </a>
</section>

</div><!-- .main-content -->

<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-flag"></i> Quick Links</h3>
        <div class="widget-body">
            <ul style="list-style:none;padding:0;margin:0;">
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('team') ); ?>"><i class="fas fa-users" style="width:18px;color:#C8102E;"></i> Our Team</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('contact') ); ?>"><i class="fas fa-envelope" style="width:18px;color:#C8102E;"></i> Contact Us</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('advertise') ); ?>"><i class="fas fa-chart-bar" style="width:18px;color:#C8102E;"></i> Advertise</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('press') ); ?>"><i class="fas fa-newspaper" style="width:18px;color:#C8102E;"></i> Press Room</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('tip-line') ); ?>"><i class="fas fa-shield-alt" style="width:18px;color:#C8102E;"></i> Tip Line</a></li>
                <li style="padding:6px 0;"><a href="<?php echo esc_url( fafo_page_link('careers') ); ?>"><i class="fas fa-briefcase" style="width:18px;color:#C8102E;"></i> Careers</a></li>
            </ul>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>

</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
