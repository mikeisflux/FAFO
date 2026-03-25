<?php get_header(); ?>

<main class="site-content" id="main" role="main">

<!-- ============================================================
     HERO: 4 featured posts — 1 large left + 3 stacked right
     ============================================================ -->
<?php
$hero_query = new WP_Query( [
    'posts_per_page' => 4,
    'post_status'    => 'publish',
    'meta_query'     => [ [ 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ] ],
] );
$hero_count = 0;
?>
<?php if ( $hero_query->have_posts() ) : ?>
<section class="hero-section" aria-label="Top Stories">
    <div class="hero-grid">
        <?php while ( $hero_query->have_posts() ) : $hero_query->the_post(); $hero_count++; ?>

        <?php if ( $hero_count === 1 ) : // ── MAIN HERO ── ?>
        <article class="hero-main">
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'fafo-hero', [ 'alt' => '', 'loading' => 'eager' ] ); ?>
            </a>
            <div class="hero-main-content">
                <?php echo fafo_category_badge(); ?>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="post-meta">
                    <span class="meta-author"><i class="fas fa-user"></i> <?php the_author(); ?></span>
                    <span class="meta-separator">|</span>
                    <span><i class="far fa-clock"></i> <?php echo get_the_date(); ?></span>
                    <span class="meta-separator">|</span>
                    <span><?php echo fafo_reading_time(); ?></span>
                </div>
            </div>
        </article>

        <?php else : // ── SIDEBAR THUMBS ── ?>
        <article class="hero-thumb">
            <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php the_post_thumbnail( 'fafo-card', [ 'alt' => '' ] ); ?>
            </a>
            <div class="hero-thumb-content">
                <?php echo fafo_category_badge(); ?>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="post-meta post-meta--hero-thumb">
                    <span><i class="far fa-clock"></i> <?php echo get_the_date( 'M j' ); ?></span>
                </div>
            </div>
        </article>
        <?php endif; ?>

        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     SECONDARY STRIP: next 4 posts — dark headline bar
     ============================================================ -->
<?php
$strip_query = new WP_Query( [
    'posts_per_page' => 4,
    'offset'         => 4,
    'post_status'    => 'publish',
] );
?>
<?php if ( $strip_query->have_posts() ) : ?>
<div class="secondary-strip">
    <div class="container">
        <div class="secondary-grid">
            <?php while ( $strip_query->have_posts() ) : $strip_query->the_post(); ?>
            <article class="secondary-card">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="secondary-card-thumb">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'fafo-thumb', [ 'alt' => get_the_title() ] ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="secondary-card-body">
                    <?php echo fafo_category_badge(); ?>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="post-meta">
                        <span><?php echo get_the_date( 'M j, Y' ); ?></span>
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================
     MAIN: CONTENT + SIDEBAR
     ============================================================ -->
<div class="container">
<div class="content-area">

<!-- ─── LEFT: MAIN CONTENT ─────────────────────────────────── -->
<div class="main-content">

    <!-- LATEST STORIES: 1 featured + 6 compact -->
    <?php
    $latest_query = new WP_Query( [
        'posts_per_page' => 7,
        'offset'         => 8,
        'post_status'    => 'publish',
    ] );
    ?>
    <?php if ( $latest_query->have_posts() ) : ?>
    <section aria-label="Latest Stories">
        <div class="section-header">
            <h2><?php echo esc_html( get_option( 'fafo_homepage_section1_title', 'Latest Stories' ) ); ?></h2>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="view-all">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="primary-news-layout">
            <?php $pn = 0; while ( $latest_query->have_posts() ) : $latest_query->the_post(); $pn++; ?>

            <?php if ( $pn === 1 ) : // ── FEATURED ── ?>
            <article class="primary-featured">
                <div class="primary-featured-img">
                    <a href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) :
                            the_post_thumbnail( 'fafo-card', [ 'alt' => get_the_title() ] );
                        else :
                            echo fafo_placeholder_img( 600, 338 );
                        endif; ?>
                    </a>
                    <?php $cats = get_the_category(); if ( ! empty( $cats ) ) : ?>
                    <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="card-category">
                        <?php echo esc_html( $cats[0]->name ); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="primary-featured-body">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 32, '...' ); ?></p>
                    <div class="post-meta">
                        <span class="meta-author"><?php the_author(); ?></span>
                        <span class="meta-separator">&bull;</span>
                        <span><?php echo get_the_date(); ?></span>
                        <span class="meta-separator">&bull;</span>
                        <span><?php echo fafo_reading_time(); ?></span>
                    </div>
                </div>
            </article>
            <div class="compact-news-list">
            <?php else : // ── COMPACT ITEMS ── ?>
            <article class="compact-news-item">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="compact-news-thumb">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'fafo-mini', [ 'alt' => get_the_title() ] ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="compact-news-body">
                    <?php echo fafo_category_badge(); ?>
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <div class="post-meta">
                        <span><?php the_author(); ?></span>
                        <span class="meta-separator">&bull;</span>
                        <span><?php echo get_the_date( 'M j' ); ?></span>
                    </div>
                </div>
            </article>
            <?php endif; ?>

            <?php endwhile; wp_reset_postdata(); ?>
            </div><!-- .compact-news-list -->

        </div><!-- .primary-news-layout -->
    </section>
    <?php endif; ?>

    <!-- OPINION & ANALYSIS: horizontal list -->
    <?php
    $opinion_query = new WP_Query( [
        'posts_per_page' => 5,
        'offset'         => 15,
        'post_status'    => 'publish',
    ] );
    ?>
    <?php if ( $opinion_query->have_posts() ) : ?>
    <section aria-label="Opinion and Analysis" style="margin-top:36px;">
        <div class="section-header">
            <h2><?php echo esc_html( get_option( 'fafo_homepage_section2_title', 'Opinion & Analysis' ) ); ?></h2>
            <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="news-list">
            <?php while ( $opinion_query->have_posts() ) : $opinion_query->the_post(); ?>
            <article class="news-list-item">
                <div class="news-list-thumb">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'fafo-mini', [ 'alt' => get_the_title() ] ); ?></a>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>"><?php echo fafo_placeholder_img( 120, 90 ); ?></a>
                    <?php endif; ?>
                </div>
                <div class="news-list-body">
                    <?php echo fafo_category_badge(); ?>
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <div class="post-meta">
                        <span class="meta-author"><?php the_author(); ?></span>
                        <span class="meta-separator">&bull;</span>
                        <span><?php echo get_the_date(); ?></span>
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </section>
    <?php endif; ?>

</div><!-- .main-content -->

<!-- ─── RIGHT: SIDEBAR ──────────────────────────────────────── -->
<aside class="sidebar" role="complementary" aria-label="Sidebar">

    <?php fafo_widget_about(); ?>

    <!-- TRENDING NOW -->
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
                <?php $t = 1; while ( $trending->have_posts() ) : $trending->the_post(); ?>
                <div class="trending-item">
                    <div class="trending-num"><?php echo $t++; ?></div>
                    <div class="trending-text">
                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                        <span><i class="far fa-clock"></i> <?php echo get_the_date( 'M j, Y' ); ?></span>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php fafo_widget_newsletter(); ?>

    <!-- HOT TOPICS -->
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-tags"></i> Hot Topics</h3>
        <div class="widget-body">
            <div class="tag-cloud">
                <?php
                $tags = get_tags( [ 'number' => 20, 'orderby' => 'count', 'order' => 'DESC' ] );
                if ( ! empty( $tags ) ) :
                    foreach ( $tags as $tag ) :
                ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
                <?php
                    endforeach;
                else :
                    foreach ( [ 'America First', 'MAGA', '2nd Amendment', 'Border Security', 'Free Speech', 'Election Integrity', 'Deep State', 'Media', 'Patriots', 'Constitution', 'God & Country', 'Make America Great' ] as $dt ) :
                ?>
                    <a href="#"><?php echo esc_html( $dt ); ?></a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>

    <?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-main' ); ?>
    <?php endif; ?>

</aside><!-- .sidebar -->

</div><!-- .content-area -->
</div><!-- .container -->

</main>
<?php get_footer(); ?>
