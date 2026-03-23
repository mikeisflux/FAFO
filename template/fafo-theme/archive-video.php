<?php
/**
 * Archive template for fafo_video post type
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">

<div style="background:linear-gradient(135deg,#1a1a2e 0%,#002868 100%);padding:40px;border-radius:8px;margin-bottom:32px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2rem;font-weight:900;color:#FFD700;margin:0 0 8px;">
        <i class="fas fa-play-circle" style="color:#C8102E;margin-right:10px;"></i>
        <?php
        if ( is_tax('video_category') ) {
            echo 'FAFO VIDEO: ' . esc_html( single_term_title('', false) );
        } else {
            echo 'FAFO VIDEO';
        }
        ?>
    </h1>
    <p style="color:rgba(255,255,255,0.7);margin:0;">Exclusive video reports, interviews, and investigations</p>
</div>

<!-- Category filters -->
<?php
$video_cats = get_terms( [ 'taxonomy' => 'video_category', 'hide_empty' => false ] );
$current_cat = is_tax('video_category') ? get_queried_object() : null;
if ( ! empty($video_cats) && ! is_wp_error($video_cats) ) : ?>
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;justify-content:center;">
    <a href="<?php echo esc_url( get_post_type_archive_link('fafo_video') ); ?>"
       style="background:<?php echo $current_cat ? '#F5F5F0' : '#002868'; ?>;color:<?php echo $current_cat ? '#333' : '#fff'; ?>;padding:8px 18px;border-radius:20px;text-decoration:none;font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
        All Videos
    </a>
    <?php foreach ( $video_cats as $vcat ) : ?>
    <a href="<?php echo esc_url( get_term_link($vcat) ); ?>"
       style="background:<?php echo ($current_cat && $current_cat->term_id === $vcat->term_id) ? '#C8102E' : '#F5F5F0'; ?>;color:<?php echo ($current_cat && $current_cat->term_id === $vcat->term_id) ? '#fff' : '#333'; ?>;padding:8px 18px;border-radius:20px;text-decoration:none;font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
        <?php echo esc_html($vcat->name); ?>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if ( have_posts() ) : ?>
<div class="video-grid">
    <?php while ( have_posts() ) : the_post();
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
            <p style="font-size:0.83rem;color:#666;line-height:1.5;margin:6px 0 8px;"><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 18, '...' ); ?></p>
            <div class="post-meta">
                <span class="meta-author"><?php the_author(); ?></span>
                <span class="meta-separator">•</span>
                <span><?php echo get_the_date('M j, Y'); ?></span>
            </div>
        </div>
    </article>
    <?php endwhile; ?>
</div>

<?php the_posts_pagination([
    'mid_size'   => 2,
    'prev_text'  => '<i class="fas fa-chevron-left"></i> Previous',
    'next_text'  => 'Next <i class="fas fa-chevron-right"></i>',
    'class'      => 'pagination',
]); ?>

<?php else : ?>
<div style="background:#F5F5F0;padding:48px;border-radius:8px;text-align:center;">
    <i class="fas fa-video" style="font-size:3rem;color:#ddd;margin-bottom:16px;display:block;"></i>
    <h3 style="color:#999;">No videos found.</h3>
    <p style="color:#aaa;">Check back soon for exclusive FAFO video content.</p>
</div>
<?php endif; ?>

</div><!-- .container -->
</main>
<?php get_footer(); ?>
