<?php get_header(); ?>

<div class="category-header">
    <div class="container" style="display:flex;align-items:center;gap:22px;">
        <?php echo get_avatar( get_the_author_meta('ID'), 80, '', '', ['style'=>'border-radius:4px;border:3px solid #FFD700;flex-shrink:0;'] ); ?>
        <div>
            <h1 style="color:white;"><?php the_author(); ?></h1>
            <?php $bio = get_the_author_meta('description'); if ($bio) : ?>
            <p><?php echo esc_html($bio); ?></p>
            <?php endif; ?>
            <p style="color:rgba(255,255,255,0.5);font-size:0.8rem;margin-top:6px;">
                <?php printf( __( '%d published articles', 'fafo' ), count_user_posts( get_the_author_meta('ID') ) ); ?>
            </p>
        </div>
    </div>
</div>

<main class="site-content" id="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<?php if ( have_posts() ) : ?>
<div class="news-grid">
    <?php while ( have_posts() ) : the_post(); ?>
    <article class="news-card">
        <div class="news-card-thumb">
            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('fafo-card'); ?></a>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>"><?php echo fafo_placeholder_img(600,338); ?></a>
            <?php endif; ?>
        </div>
        <div class="news-card-body">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 22, '...'); ?></p>
            <div class="post-meta">
                <span><?php echo get_the_date('M j, Y'); ?></span>
                <span class="meta-separator">&bull;</span>
                <span><?php echo fafo_reading_time(); ?></span>
            </div>
        </div>
    </article>
    <?php endwhile; ?>
</div>
<?php the_posts_pagination(['prev_text'=>'&laquo; Previous','next_text'=>'Next &raquo;']); ?>
<?php else : ?>
<p style="padding:40px 0;color:#999;">No articles found for this author.</p>
<?php endif; ?>

</div>
<aside class="sidebar">
    <?php fafo_widget_about(); ?>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div>
</div>
</main>

<?php get_footer(); ?>
