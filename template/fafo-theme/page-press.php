<?php
/**
 * Template Name: Press Room
 *
 * Editable: WP Admin → Pages → Press Room
 */
if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">PRESS ROOM</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:600px;margin:0 auto;">
        Media resources, editorial guidelines, and press inquiries for FAFO News.
    </p>
</div>

<div class="entry-content" style="max-width:100%;">
    <?php the_content(); ?>
</div>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-envelope"></i> Press Contact</h3>
        <div class="widget-body">
            <p style="font-size:0.88rem;color:#555;margin:0 0 12px;">For press inquiries, interview requests, and media kit requests:</p>
            <a href="mailto:<?php echo esc_attr( fafo_contact_email('press') ); ?>"
               style="display:block;background:#002868;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.85rem;">
                <?php echo esc_html( fafo_contact_email('press') ); ?>
            </a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
