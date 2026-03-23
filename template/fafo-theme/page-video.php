<?php
/**
 * Template Name: Video Hub Page
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">

<div style="background:linear-gradient(135deg,#1a1a2e 0%,#002868 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <i class="fas fa-play-circle" style="font-size:3rem;color:#C8102E;margin-bottom:14px;display:block;"></i>
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">FAFO VIDEO</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;">
        Exclusive video reports, interviews, investigations, and live coverage. The news the cameras refuse to show.
    </p>
</div>

<!-- VIDEO CATEGORIES NAV -->
<?php
$video_cats = get_terms( [ 'taxonomy' => 'video_category', 'hide_empty' => false ] );
if ( ! empty( $video_cats ) && ! is_wp_error( $video_cats ) ) : ?>
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px;justify-content:center;">
    <a href="<?php echo esc_url( get_post_type_archive_link('fafo_video') ); ?>"
       style="background:#002868;color:#fff;padding:8px 18px;border-radius:20px;text-decoration:none;font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
        All Videos
    </a>
    <?php foreach ( $video_cats as $vcat ) : ?>
    <a href="<?php echo esc_url( get_term_link($vcat) ); ?>"
       style="background:#F5F5F0;color:#333;padding:8px 18px;border-radius:20px;text-decoration:none;font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
        <?php echo esc_html($vcat->name); ?>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- FEATURED VIDEO -->
<?php
$featured_video = new WP_Query([
    'post_type'      => 'fafo_video',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
]);
if ( $featured_video->have_posts() ) :
    $featured_video->the_post();
    $vid_url      = get_post_meta( get_the_ID(), '_fafo_video_url', true );
    $vid_duration = get_post_meta( get_the_ID(), '_fafo_video_duration', true );
?>
<section style="margin-bottom:48px;">
    <div class="section-header">
        <h2><i class="fas fa-star" style="color:#FFD700;margin-right:8px;"></i>Featured Video</h2>
    </div>
    <div style="display:grid;grid-template-columns:1.6fr 1fr;gap:32px;margin-top:20px;">
        <div class="video-featured-player">
            <?php echo fafo_render_video_player( get_the_ID() ); ?>
        </div>
        <div style="display:flex;flex-direction:column;justify-content:flex-start;padding-top:4px;">
            <?php echo fafo_category_badge(); ?>
            <h2 style="font-family:var(--font-head);font-size:1.4rem;font-weight:700;color:#002868;margin:8px 0 12px;line-height:1.3;">
                <a href="<?php the_permalink(); ?>" style="color:#002868;text-decoration:none;"><?php the_title(); ?></a>
            </h2>
            <?php if ( $vid_duration ) : ?>
            <div style="display:inline-flex;align-items:center;gap:6px;background:#002868;color:#fff;padding:4px 10px;border-radius:4px;font-size:0.8rem;margin-bottom:12px;width:fit-content;">
                <i class="fas fa-clock"></i> <?php echo esc_html($vid_duration); ?>
            </div>
            <?php endif; ?>
            <p style="font-size:0.9rem;color:#555;line-height:1.7;flex:1;"><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 40, '...' ); ?></p>
            <div class="post-meta" style="margin-top:12px;padding-top:12px;border-top:1px solid #eee;">
                <span class="meta-author"><i class="fas fa-user"></i> <?php the_author(); ?></span>
                <span class="meta-separator">•</span>
                <span><i class="far fa-calendar"></i> <?php echo get_the_date(); ?></span>
            </div>
            <a href="<?php the_permalink(); ?>" style="display:inline-flex;align-items:center;gap:8px;background:#C8102E;color:#fff;padding:12px 24px;text-decoration:none;font-family:var(--font-head);font-weight:700;font-size:0.88rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;margin-top:16px;width:fit-content;">
                <i class="fas fa-play"></i> Watch Full Video
            </a>
        </div>
    </div>
</section>
<?php wp_reset_postdata(); endif; ?>

<!-- VIDEO GRID -->
<?php
$videos = new WP_Query([
    'post_type'      => 'fafo_video',
    'posts_per_page' => 12,
    'offset'         => 1,
    'post_status'    => 'publish',
]);
?>
<?php if ( $videos->have_posts() ) : ?>
<section style="margin-bottom:48px;">
    <div class="section-header">
        <h2>Latest Videos</h2>
        <a href="<?php echo esc_url( get_post_type_archive_link('fafo_video') ); ?>" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="video-grid" style="margin-top:20px;">
        <?php while ( $videos->have_posts() ) : $videos->the_post();
            $dur = get_post_meta( get_the_ID(), '_fafo_video_duration', true );
            $src = get_post_meta( get_the_ID(), '_fafo_video_source', true );
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
                    <?php if ( $dur ) : ?><span class="video-duration"><?php echo esc_html($dur); ?></span><?php endif; ?>
                    <?php if ( $src ) : ?>
                    <span style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,.7);color:#fff;font-size:0.68rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:3px 8px;border-radius:3px;">
                        <?php echo esc_html( strtoupper($src) ); ?>
                    </span>
                    <?php endif; ?>
                </div>
            </a>
            <div class="video-card-body">
                <?php
                $vcats = get_the_terms( get_the_ID(), 'video_category' );
                if ( ! empty($vcats) && ! is_wp_error($vcats) ) : ?>
                <a href="<?php echo esc_url(get_term_link($vcats[0])); ?>" style="font-family:var(--font-head);font-size:0.68rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C8102E;text-decoration:none;"><?php echo esc_html($vcats[0]->name); ?></a>
                <?php endif; ?>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <div class="post-meta">
                    <span class="meta-author"><?php the_author(); ?></span>
                    <span class="meta-separator">•</span>
                    <span><?php echo get_the_date('M j'); ?></span>
                </div>
            </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
</section>
<?php else : ?>
<div style="background:#F5F5F0;padding:48px;border-radius:8px;text-align:center;margin-bottom:40px;">
    <i class="fas fa-video" style="font-size:3rem;color:#ddd;margin-bottom:16px;display:block;"></i>
    <h3 style="color:#999;">Video content coming soon!</h3>
    <p style="color:#aaa;">Check back soon for exclusive FAFO video reports, interviews, and investigations.</p>
</div>
<?php endif; ?>

</div><!-- .container -->
</main>
<?php get_footer(); ?>
