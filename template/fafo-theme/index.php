<?php get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">

<!-- ============================================================
     HERO: FEATURED STORIES (top 3 posts)
     ============================================================ -->
<?php
$hero_query = new WP_Query( [
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'meta_query'     => [ [
        'key'     => '_thumbnail_id',
        'compare' => 'EXISTS',
    ] ],
] );
?>

<?php if ( $hero_query->have_posts() ) : ?>
<section class="hero-section" aria-label="Featured Stories">
    <div class="hero-grid">

        <?php
        $hero_count = 0;
        while ( $hero_query->have_posts() ) :
            $hero_query->the_post();
            $hero_count++;

            if ( $hero_count === 1 ) :
                // ---- MAIN HERO ----
        ?>
        <article class="hero-main">
            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail( 'fafo-hero', [ 'alt' => get_the_title() ] ); ?>
                </a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php echo fafo_placeholder_img( 800, 500, get_the_title() ); ?>
                </a>
            <?php endif; ?>

            <div class="hero-main-content">
                <?php echo fafo_category_badge(); ?>
                <h2>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="post-meta">
                    <span class="meta-author">
                        <i class="fas fa-user"></i>
                        <?php the_author(); ?>
                    </span>
                    <span class="meta-separator">|</span>
                    <span>
                        <i class="far fa-clock"></i>
                        <?php echo get_the_date(); ?>
                    </span>
                    <span class="meta-separator">|</span>
                    <span><?php echo fafo_reading_time(); ?></span>
                </div>
            </div>
        </article>

        <?php
            else :
                // ---- HERO THUMBS ----
        ?>
        <article class="hero-thumb">
            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail( 'fafo-card', [ 'alt' => get_the_title() ] ); ?>
                </a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php echo fafo_placeholder_img( 400, 280, get_the_title() ); ?>
                </a>
            <?php endif; ?>

            <div class="hero-thumb-content">
                <?php echo fafo_category_badge(); ?>
                <h3>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>
                <div class="post-meta" style="color:rgba(255,255,255,0.6);margin-top:5px;">
                    <span><i class="far fa-clock"></i> <?php echo get_the_date(); ?></span>
                </div>
            </div>
        </article>
        <?php
            endif;
        endwhile;
        wp_reset_postdata();
        ?>

    </div><!-- .hero-grid -->
</section>
<?php endif; ?>

<!-- ============================================================
     TWO-COLUMN: MAIN CONTENT + SIDEBAR
     ============================================================ -->
<div class="content-area">

    <div class="main-content">

        <!-- LATEST NEWS GRID -->
        <section aria-label="Latest Stories">
            <div class="section-header">
                <h2><?php echo esc_html( get_option( 'fafo_homepage_section1_title', 'Latest Stories' ) ); ?></h2>
                <a href="<?php echo esc_url( get_permalink( get_option('page_for_posts') ) ); ?>" class="view-all">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <?php
            $latest_query = new WP_Query( [
                'posts_per_page' => 6,
                'offset'         => 3,
                'post_status'    => 'publish',
            ] );
            ?>

            <?php if ( $latest_query->have_posts() ) : ?>
            <div class="news-grid">
                <?php while ( $latest_query->have_posts() ) : $latest_query->the_post(); ?>
                <article class="news-card">
                    <div class="news-card-thumb">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'fafo-card', [ 'alt' => get_the_title() ] ); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php echo fafo_placeholder_img( 600, 338 ); ?>
                            </a>
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
                        <h3>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 22, '...' ); ?></p>
                        <div class="post-meta">
                            <span class="meta-author"><?php the_author(); ?></span>
                            <span class="meta-separator">&bull;</span>
                            <span><?php echo get_the_date( 'M j' ); ?></span>
                            <span class="meta-separator">&bull;</span>
                            <span><?php echo fafo_reading_time(); ?></span>
                        </div>
                    </div>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div><!-- .news-grid -->
            <?php endif; ?>

        </section>

        <!-- OPINION & ANALYSIS LIST -->
        <?php
        $opinion_query = new WP_Query( [
            'posts_per_page' => 5,
            'offset'         => 9,
            'post_status'    => 'publish',
        ] );
        ?>

        <?php if ( $opinion_query->have_posts() ) : ?>
        <section aria-label="Opinion and Analysis" style="margin-top:40px;">
            <div class="section-header">
                <h2><?php echo esc_html( get_option( 'fafo_homepage_section2_title', 'Opinion & Analysis' ) ); ?></h2>
                <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="news-list">
                <?php while ( $opinion_query->have_posts() ) : $opinion_query->the_post(); ?>
                <article class="news-list-item">
                    <div class="news-list-thumb">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail( 'fafo-mini', [ 'alt' => get_the_title() ] ); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php echo fafo_placeholder_img( 120, 90 ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="news-list-body">
                        <?php echo fafo_category_badge(); ?>
                        <h4>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <div class="post-meta">
                            <span class="meta-author"><?php the_author(); ?></span>
                            <span class="meta-separator">&bull;</span>
                            <span><?php echo get_the_date(); ?></span>
                        </div>
                    </div>
                </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div><!-- .news-list -->
        </section>
        <?php endif; ?>

        <!-- STANDARD LOOP PAGINATION -->
        <?php if ( have_posts() ) : ?>
        <?php the_posts_pagination( [
            'mid_size'           => 2,
            'prev_text'          => '<i class="fas fa-chevron-left"></i> Previous',
            'next_text'          => 'Next <i class="fas fa-chevron-right"></i>',
            'before_page_number' => '',
            'class'              => 'pagination',
        ] ); ?>
        <?php endif; ?>

    </div><!-- .main-content -->

    <!-- SIDEBAR -->
    <aside class="sidebar" role="complementary" aria-label="Sidebar">

        <?php fafo_widget_about(); ?>
        <?php fafo_widget_newsletter(); ?>

        <!-- TRENDING WIDGET -->
        <div class="widget">
            <h3 class="widget-title"><i class="fas fa-fire"></i> Trending Now</h3>
            <div class="widget-body">
                <?php
                $trending = new WP_Query( [
                    'posts_per_page' => 6,
                    'orderby'        => 'comment_count',
                    'order'          => 'DESC',
                ] );
                ?>
                <?php if ( $trending->have_posts() ) : ?>
                <div class="trending-list">
                    <?php $t_num = 1; while ( $trending->have_posts() ) : $trending->the_post(); ?>
                    <div class="trending-item">
                        <div class="trending-num"><?php echo $t_num++; ?></div>
                        <div class="trending-text">
                            <h5>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                            <span>
                                <i class="far fa-clock"></i>
                                <?php echo get_the_date( 'M j, Y' ); ?>
                            </span>
                        </div>
                    </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- TAG CLOUD -->
        <div class="widget">
            <h3 class="widget-title"><i class="fas fa-tags"></i> Hot Topics</h3>
            <div class="widget-body">
                <div class="tag-cloud">
                    <?php
                    $tags = get_tags( [ 'number' => 20, 'orderby' => 'count', 'order' => 'DESC' ] );
                    if ( ! empty( $tags ) ) :
                        foreach ( $tags as $tag ) :
                    ?>
                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">
                            <?php echo esc_html( $tag->name ); ?>
                        </a>
                    <?php
                        endforeach;
                    else :
                        $default_tags = ['America First','MAGA','2nd Amendment','Border Security','Free Speech','Election Integrity','Deep State','Mainstream Media','Patriots','Constitution','God & Country','Make America Great'];
                        foreach ( $default_tags as $dt ) :
                    ?>
                        <a href="#"><?php echo esc_html( $dt ); ?></a>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>

        <!-- DYNAMIC SIDEBAR WIDGETS -->
        <?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
            <?php dynamic_sidebar( 'sidebar-main' ); ?>
        <?php endif; ?>

    </aside><!-- .sidebar -->

<?php get_footer(); ?>
