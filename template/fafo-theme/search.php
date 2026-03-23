<?php get_header(); ?>

<div class="category-header">
    <div class="container">
        <h1>
            <i class="fas fa-search" style="color:var(--gold);margin-right:10px;"></i>
            Search Results: &ldquo;<?php echo esc_html( get_search_query() ); ?>&rdquo;
        </h1>
        <p><?php printf( __( '%d stories found', 'fafo' ), $wp_query->found_posts ); ?></p>
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

<?php the_posts_pagination( [
    'prev_text' => '<i class="fas fa-chevron-left"></i> Previous',
    'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
] ); ?>

<?php else : ?>
<div style="text-align:center;padding:60px 20px;">
    <h2 style="font-size:2rem;">No Results Found</h2>
    <p style="color:#999;margin:16px 0 24px;">Try a different search term or browse our categories.</p>
    <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" style="max-width:480px;margin:0 auto;display:flex;gap:8px;">
        <input type="search" name="s" placeholder="Search FAFO News..."
               style="flex:1;padding:12px;border:2px solid #E8E8E8;font-size:1rem;outline:none;"
               value="">
        <button type="submit" style="background:#C8102E;color:#fff;border:none;padding:12px 20px;font-family:var(--font-head);font-weight:700;letter-spacing:0.1em;cursor:pointer;">
            SEARCH
        </button>
    </form>
</div>
<?php endif; ?>

</div><!-- .main-content -->

<aside class="sidebar" role="complementary">
    <?php fafo_widget_about(); ?>
    <?php if ( is_active_sidebar( 'sidebar-main' ) ) dynamic_sidebar( 'sidebar-main' ); ?>
</aside>

</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
