<?php
/**
 * Template Name: Advertise
 *
 * Editable: WP Admin → Pages → Advertise
 */
if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">ADVERTISE WITH FAFO</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:600px;margin:0 auto;">
        Reach millions of patriotic, engaged American conservatives who trust FAFO News.
    </p>
</div>

<div class="entry-content" style="max-width:100%;">
    <?php the_content(); ?>
</div>

<section style="background:#F5F5F0;padding:36px;border-radius:8px;text-align:center;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.5rem;font-weight:900;color:#002868;margin:0 0 10px;">READY TO ADVERTISE?</h3>
    <p style="color:#555;margin:0 0 20px;">Contact our advertising team for rates, media kit, and custom packages.</p>
    <a href="mailto:<?php echo esc_attr( fafo_contact_email('advertise') ); ?>"
       style="display:inline-block;background:#C8102E;color:#fff;padding:12px 32px;font-family:var(--font-head);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;border-radius:4px;">
        <i class="fas fa-envelope"></i> <?php echo esc_html( fafo_contact_email('advertise') ); ?>
    </a>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
