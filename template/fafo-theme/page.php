<?php get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
    <figure style="margin-bottom:28px;">
        <?php the_post_thumbnail( 'fafo-hero', [ 'class' => 'single-hero', 'alt' => get_the_title() ] ); ?>
    </figure>
    <?php endif; ?>

    <div class="single-post-header">
        <h1><?php the_title(); ?></h1>
    </div>

    <div class="post-content">
        <?php the_content(); ?>
        <?php
        wp_link_pages( [
            'before' => '<div class="page-links"><strong>' . __( 'Pages:', 'fafo' ) . '</strong>',
            'after'  => '</div>',
        ] );
        ?>
    </div>

</article>

<?php if ( comments_open() ) comments_template(); ?>

<?php endwhile; ?>

</div><!-- .main-content -->

<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-flag"></i> About FAFO</h3>
        <div class="widget-body">
            <div class="about-widget">
                <div class="fafo-big">F<span>A</span>FO</div>
                <p>For America First Only &mdash; Bold conservative news without compromise.</p>
            </div>
        </div>
    </div>

    <?php if ( is_active_sidebar( 'sidebar-main' ) ) dynamic_sidebar( 'sidebar-main' ); ?>
</aside>

</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
