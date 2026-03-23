<?php get_header(); ?>

<!-- CATEGORY / ARCHIVE HEADER -->
<div class="category-header">
    <div class="container">
        <?php
        if ( is_category() ) {
            echo '<h1>' . single_cat_title( '', false ) . '</h1>';
            $cat_desc = category_description();
            if ( $cat_desc ) { echo wp_kses_post( $cat_desc ); }
        } elseif ( is_tag() ) {
            echo '<h1>Tag: ' . single_tag_title( '', false ) . '</h1>';
        } elseif ( is_author() ) {
            echo '<h1>Author: ' . get_the_author() . '</h1>';
        } elseif ( is_date() ) {
            echo '<h1>' . get_the_date( 'F Y' ) . '</h1>';
        } else {
            echo '<h1>Archives</h1>';
        }
        ?>
    </div>
</div>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

    <?php if ( have_posts() ) : ?>

    <div class="news-grid">
        <?php while ( have_posts() ) : the_post(); ?>
        <article class="news-card">
            <div class="news-card-thumb">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'fafo-card', [ 'alt' => get_the_title() ] ); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php the_permalink(); ?>"><?php echo fafo_placeholder_img( 600, 338 ); ?></a>
                <?php endif; ?>
                <?php
                $cats = get_the_category();
                if ( ! empty( $cats ) ) :
                ?>
                <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="card-category">
                    <?php echo esc_html( $cats[0]->name ); ?>
                </a>
                <?php endif; ?>
            </div>
            <div class="news-card-body">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 22, '...' ); ?></p>
                <div class="post-meta">
                    <span class="meta-author"><?php the_author(); ?></span>
                    <span class="meta-separator">&bull;</span>
                    <span><?php echo get_the_date( 'M j, Y' ); ?></span>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
    </div>

    <?php
    the_posts_pagination( [
        'prev_text' => '<i class="fas fa-chevron-left"></i> Previous',
        'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
    ] );
    ?>

    <?php else : ?>
    <div style="text-align:center;padding:60px 20px;">
        <h2>No Stories Found</h2>
        <p style="color:#999;">No articles found in this section. Check back soon.</p>
    </div>
    <?php endif; ?>

</div><!-- .main-content -->

<aside class="sidebar" role="complementary">
    <?php fafo_widget_about(); ?>

    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-fire"></i> Trending</h3>
        <div class="widget-body">
            <?php
            $trending = new WP_Query( [ 'posts_per_page' => 5, 'orderby' => 'comment_count', 'order' => 'DESC' ] );
            $t = 1;
            ?>
            <div class="trending-list">
                <?php while ( $trending->have_posts() ) : $trending->the_post(); ?>
                <div class="trending-item">
                    <div class="trending-num"><?php echo $t++; ?></div>
                    <div class="trending-text">
                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <span><?php echo get_the_date('M j'); ?></span>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>

    <?php if ( is_active_sidebar( 'sidebar-main' ) ) dynamic_sidebar( 'sidebar-main' ); ?>
</aside>

</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
