<?php
/**
 * Single Video template for fafo_video post type
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<?php while ( have_posts() ) : the_post();
    $vid_url      = get_post_meta( get_the_ID(), '_fafo_video_url', true );
    $vid_duration = get_post_meta( get_the_ID(), '_fafo_video_duration', true );
    $vid_source   = get_post_meta( get_the_ID(), '_fafo_video_source', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!-- Category + Meta Header -->
    <div class="single-post-header">
        <?php
        $vcats = get_the_terms( get_the_ID(), 'video_category' );
        if ( ! empty($vcats) && ! is_wp_error($vcats) ) :
        ?>
        <a href="<?php echo esc_url( get_term_link($vcats[0]) ); ?>" class="post-category-link"><?php echo esc_html($vcats[0]->name); ?></a>
        <?php endif; ?>

        <h1><?php the_title(); ?></h1>

        <div class="post-meta">
            <span class="meta-author">
                <i class="fas fa-user-shield"></i> <?php the_author(); ?>
            </span>
            <span class="meta-separator">&bull;</span>
            <span><i class="far fa-calendar-alt"></i> <?php echo get_the_date( 'F j, Y \a\t g:i a' ); ?></span>
            <?php if ( $vid_duration ) : ?>
            <span class="meta-separator">&bull;</span>
            <span><i class="fas fa-clock"></i> <?php echo esc_html($vid_duration); ?></span>
            <?php endif; ?>
            <?php if ( $vid_source ) : ?>
            <span class="meta-separator">&bull;</span>
            <span style="text-transform:uppercase;font-weight:600;"><i class="fas fa-play-circle"></i> <?php echo esc_html($vid_source); ?></span>
            <?php endif; ?>
            <span class="meta-separator">&bull;</span>
            <span><i class="far fa-comment-alt"></i> <?php comments_number('0 Comments','1 Comment','% Comments'); ?></span>
        </div>
    </div>

    <!-- VIDEO PLAYER -->
    <?php if ( $vid_url ) : ?>
    <div style="background:#000;border-radius:8px;overflow:hidden;margin-bottom:24px;">
        <?php echo fafo_render_video_player( get_the_ID() ); ?>
    </div>
    <?php elseif ( has_post_thumbnail() ) : ?>
    <figure style="margin-bottom:24px;">
        <?php the_post_thumbnail('fafo-hero', ['class' => 'single-hero', 'alt' => get_the_title()]); ?>
    </figure>
    <?php endif; ?>

    <!-- Share Bar -->
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
        <button class="share-btn copy" id="copyLinkBtn"><i class="far fa-copy"></i> Copy Link</button>
    </div>

    <!-- Article Body -->
    <div class="post-content">
        <?php the_content(); ?>
    </div>

    <!-- Tags -->
    <?php $tags = get_the_tags(); if ( $tags ) : ?>
    <div style="margin-top:24px;padding-top:18px;border-top:2px solid #E8E8E8;">
        <span style="font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#999;margin-right:8px;">
            <i class="fas fa-tags"></i> Topics:
        </span>
        <div class="tag-cloud" style="display:inline-flex;flex-wrap:wrap;gap:6px;">
            <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link($tag->term_id) ); ?>"><?php echo esc_html($tag->name); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Share bar bottom -->
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

    <!-- Author box -->
    <?php
    $author_id  = get_the_author_meta('ID');
    $author_bio = get_the_author_meta('description', $author_id);
    ?>
    <div style="background:#F5F5F0;border-left:5px solid var(--red,#C8102E);padding:20px;margin:32px 0;display:flex;gap:18px;align-items:flex-start;">
        <?php echo get_avatar($author_id, 64, '', '', ['style' => 'border-radius:3px;flex-shrink:0;']); ?>
        <div>
            <div style="font-family:var(--font-head);font-size:0.72rem;letter-spacing:0.14em;text-transform:uppercase;color:#999;margin-bottom:4px;">Reporter</div>
            <strong style="font-family:var(--font-head);font-size:1.1rem;color:#002868;">
                <a href="<?php echo esc_url( get_author_posts_url($author_id) ); ?>" style="color:#002868;"><?php echo esc_html( get_the_author() ); ?></a>
            </strong>
            <?php if ( $author_bio ) : ?>
            <p style="font-size:0.85rem;color:#666;margin-top:6px;line-height:1.6;"><?php echo esc_html($author_bio); ?></p>
            <?php endif; ?>
        </div>
    </div>

</article>

<!-- Related Videos -->
<?php
$related = new WP_Query([
    'post_type'      => 'fafo_video',
    'posts_per_page' => 3,
    'post__not_in'   => [ get_the_ID() ],
    'orderby'        => 'rand',
]);
if ( $related->have_posts() ) : ?>
<section style="margin-top:40px;" aria-label="More Videos">
    <div class="section-header"><h2>More Videos</h2></div>
    <div class="video-grid" style="margin-top:20px;">
        <?php while ( $related->have_posts() ) : $related->the_post();
            $r_dur = get_post_meta( get_the_ID(), '_fafo_video_duration', true );
        ?>
        <article class="video-card">
            <a href="<?php the_permalink(); ?>" style="display:block;text-decoration:none;">
                <div class="video-card-thumb">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail('fafo-card', ['alt' => get_the_title()]); ?>
                    <?php else : ?>
                        <?php echo fafo_placeholder_img(600,338,get_the_title()); ?>
                    <?php endif; ?>
                    <div class="video-play-btn"></div>
                    <?php if ( $r_dur ) : ?><span class="video-duration"><?php echo esc_html($r_dur); ?></span><?php endif; ?>
                </div>
            </a>
            <div class="video-card-body">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="post-meta">
                    <span><?php the_author(); ?></span>
                    <span class="meta-separator">•</span>
                    <span><?php echo get_the_date('M j'); ?></span>
                </div>
            </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php endif; ?>

<!-- Comments -->
<?php if ( comments_open() || get_comments_number() ) comments_template(); ?>

<?php endwhile; ?>

</div><!-- .main-content -->

<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-play-circle"></i> More Video</h3>
        <div class="widget-body">
            <?php
            $sidebar_vids = new WP_Query(['post_type' => 'fafo_video','posts_per_page' => 4,'post__not_in' => [get_the_ID()]]);
            if ( $sidebar_vids->have_posts() ) :
                while ( $sidebar_vids->have_posts() ) : $sidebar_vids->the_post();
                    $sd = get_post_meta(get_the_ID(),'_fafo_video_duration',true);
            ?>
            <div style="display:flex;gap:10px;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #eee;">
                <div style="flex-shrink:0;width:80px;height:55px;position:relative;overflow:hidden;border-radius:3px;background:#000;">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail('thumbnail',['style'=>'width:100%;height:100%;object-fit:cover;']); ?>
                    <?php else : ?>
                        <div style="width:100%;height:100%;background:#002868;display:flex;align-items:center;justify-content:center;"><i class="fas fa-play" style="color:#FFD700;font-size:1rem;"></i></div>
                    <?php endif; ?>
                    <?php if ( $sd ) : ?><span style="position:absolute;bottom:2px;right:2px;background:rgba(0,0,0,.8);color:#fff;font-size:9px;padding:1px 4px;border-radius:2px;"><?php echo esc_html($sd); ?></span><?php endif; ?>
                </div>
                <div>
                    <h5 style="margin:0 0 4px;font-size:0.8rem;line-height:1.4;"><a href="<?php the_permalink(); ?>" style="color:#002868;text-decoration:none;"><?php the_title(); ?></a></h5>
                    <span style="font-size:0.72rem;color:#888;"><?php echo get_the_date('M j'); ?></span>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); endif; ?>
        </div>
    </div>

    <?php fafo_widget_about(); ?>

    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>

</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
