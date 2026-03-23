<?php get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!-- Category badge -->
    <div class="single-post-header">
        <?php echo fafo_category_badge(); ?>

        <h1><?php the_title(); ?></h1>

        <div class="post-meta">
            <span class="meta-author">
                <i class="fas fa-user-shield"></i>
                <?php the_author(); ?>
            </span>
            <span class="meta-separator">&bull;</span>
            <span>
                <i class="far fa-calendar-alt"></i>
                <?php echo get_the_date( 'F j, Y \a\t g:i a' ); ?>
            </span>
            <span class="meta-separator">&bull;</span>
            <span>
                <i class="far fa-clock"></i>
                <?php echo fafo_reading_time(); ?>
            </span>
            <span class="meta-separator">&bull;</span>
            <span>
                <i class="far fa-comment-alt"></i>
                <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?>
            </span>
        </div>
    </div>

    <!-- Hero image -->
    <?php if ( has_post_thumbnail() ) : ?>
    <figure style="margin-bottom:24px;">
        <?php the_post_thumbnail( 'fafo-hero', [ 'class' => 'single-hero', 'alt' => get_the_title() ] ); ?>
        <?php $caption = get_the_post_thumbnail_caption(); if ( $caption ) : ?>
        <figcaption style="font-size:0.78rem;color:#999;padding:6px 0;font-style:italic;">
            <?php echo esc_html( $caption ); ?>
        </figcaption>
        <?php endif; ?>
    </figure>
    <?php endif; ?>

    <!-- Share bar (top) -->
    <div class="share-bar">
        <span><i class="fas fa-share-alt"></i> Share:</span>
        <button class="share-btn twitter" onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(window.location.href)+'&text='+encodeURIComponent(document.title),'_blank')">
            <i class="fab fa-x-twitter"></i> Post
        </button>
        <button class="share-btn facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href),'_blank')">
            <i class="fab fa-facebook-f"></i> Share
        </button>
        <button class="share-btn truth" onclick="window.open('https://truthsocial.com/share?title='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href),'_blank')">
            Truth Social
        </button>
        <button class="share-btn copy" id="copyLinkBtn">
            <i class="far fa-copy"></i> Copy Link
        </button>
    </div>

    <!-- Post content -->
    <div class="post-content">
        <?php the_content( __( 'Read More <i class="fas fa-arrow-right"></i>', 'fafo' ) ); ?>
        <?php
        wp_link_pages( [
            'before' => '<div class="page-links"><strong>' . __( 'Pages:', 'fafo' ) . '</strong>',
            'after'  => '</div>',
        ] );
        ?>
    </div>

    <!-- Tags -->
    <?php $tags = get_the_tags(); if ( $tags ) : ?>
    <div style="margin-top:24px; padding-top:18px; border-top:2px solid #E8E8E8;">
        <span style="font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#999;margin-right:8px;">
            <i class="fas fa-tags"></i> Topics:
        </span>
        <div class="tag-cloud" style="display:inline-flex;flex-wrap:wrap;gap:6px;">
            <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">
                <?php echo esc_html( $tag->name ); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Share bar (bottom) -->
    <div class="share-bar" style="margin-top:28px;">
        <span><i class="fas fa-share-alt"></i> Spread the Truth:</span>
        <button class="share-btn twitter" onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(window.location.href),'_blank')">
            <i class="fab fa-x-twitter"></i> Post
        </button>
        <button class="share-btn facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href),'_blank')">
            <i class="fab fa-facebook-f"></i> Share
        </button>
        <button class="share-btn truth">Truth Social</button>
    </div>

    <!-- Author bio -->
    <?php
    $author_id  = get_the_author_meta( 'ID' );
    $author_bio = get_the_author_meta( 'description', $author_id );
    ?>
    <div style="background:#F5F5F0;border-left:5px solid var(--red, #C8102E);padding:20px;margin:32px 0;display:flex;gap:18px;align-items:flex-start;">
        <?php echo get_avatar( $author_id, 64, '', '', [ 'style' => 'border-radius:3px;flex-shrink:0;' ] ); ?>
        <div>
            <div style="font-family:var(--font-head);font-size:0.72rem;letter-spacing:0.14em;text-transform:uppercase;color:#999;margin-bottom:4px;">About the Author</div>
            <strong style="font-family:var(--font-head);font-size:1.1rem;color:#002868;">
                <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" style="color:#002868;">
                    <?php echo esc_html( get_the_author() ); ?>
                </a>
            </strong>
            <?php if ( $author_bio ) : ?>
            <p style="font-size:0.85rem;color:#666;margin-top:6px;line-height:1.6;">
                <?php echo esc_html( $author_bio ); ?>
            </p>
            <?php endif; ?>
        </div>
    </div>

</article>

<!-- RELATED POSTS -->
<?php
$cats = get_the_category( get_the_ID() );
if ( ! empty( $cats ) ) :
    $related = new WP_Query( [
        'category__in'   => [ $cats[0]->term_id ],
        'post__not_in'   => [ get_the_ID() ],
        'posts_per_page' => 3,
        'orderby'        => 'rand',
    ] );
    if ( $related->have_posts() ) :
?>
<section style="margin-top:40px;" aria-label="Related Stories">
    <div class="section-header">
        <h2>Related Stories</h2>
    </div>
    <div class="news-grid">
        <?php while ( $related->have_posts() ) : $related->the_post(); ?>
        <article class="news-card">
            <div class="news-card-thumb">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'fafo-card', [ 'alt' => get_the_title() ] ); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php the_permalink(); ?>"><?php echo fafo_placeholder_img( 600, 338 ); ?></a>
                <?php endif; ?>
            </div>
            <div class="news-card-body">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="post-meta">
                    <span class="meta-author"><?php the_author(); ?></span>
                    <span class="meta-separator">&bull;</span>
                    <span><?php echo get_the_date( 'M j' ); ?></span>
                </div>
            </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php
    endif;
endif;
?>

<!-- COMMENTS -->
<?php
if ( comments_open() || get_comments_number() ) {
    comments_template();
}
?>

<?php endwhile; ?>

    </div><!-- .main-content -->

    <aside class="sidebar" role="complementary">
        <?php fafo_widget_about(); ?>
        <?php fafo_widget_newsletter(); ?>

        <!-- TRENDING -->
        <div class="widget">
            <h3 class="widget-title"><i class="fas fa-fire"></i> Trending</h3>
            <div class="widget-body">
                <?php
                $trending = new WP_Query( [ 'posts_per_page' => 5, 'orderby' => 'comment_count', 'order' => 'DESC' ] );
                ?>
                <div class="trending-list">
                    <?php $t = 1; while ( $trending->have_posts() ) : $trending->the_post(); ?>
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
