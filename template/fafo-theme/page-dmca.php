<?php
/**
 * Template Name: DMCA / Takedowns
 *
 * Editable: WP Admin → Pages → DMCA / Takedowns
 */
if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.2rem;font-weight:900;color:#FFD700;margin:0;"><?php the_title(); ?></h1>
</div>

<div class="entry-content" style="max-width:100%;">
    <?php the_content(); ?>
</div>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
