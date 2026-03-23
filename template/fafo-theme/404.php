<?php get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">

<div class="error-404">
    <div class="error-num">404</div>
    <h2>PAGE NOT FOUND</h2>
    <p>
        The page you're looking for has gone AWOL.<br>
        Maybe the mainstream media buried it &mdash; but FAFO never stops searching.
    </p>
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-primary">
        <i class="fas fa-home"></i> BACK TO FAFO NEWS
    </a>

    <div style="margin-top:50px;max-width:500px;margin-left:auto;margin-right:auto;">
        <p style="font-family:var(--font-head);font-size:0.82rem;letter-spacing:0.12em;text-transform:uppercase;color:#999;margin-bottom:12px;">
            Try searching instead:
        </p>
        <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>" style="display:flex;gap:8px;">
            <input type="search" name="s" placeholder="Search FAFO News..."
                   style="flex:1;padding:12px;border:2px solid #E8E8E8;font-size:1rem;outline:none;font-family:var(--font-body);">
            <button type="submit" style="background:#C8102E;color:#fff;border:none;padding:12px 20px;font-family:var(--font-head);font-weight:700;letter-spacing:0.1em;cursor:pointer;text-transform:uppercase;">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div style="margin-top:50px;">
        <p style="font-family:var(--font-head);font-size:0.82rem;letter-spacing:0.12em;text-transform:uppercase;color:#999;margin-bottom:16px;">
            Latest Stories:
        </p>
        <?php
        $recent = new WP_Query( [ 'posts_per_page' => 4, 'post_status' => 'publish' ] );
        if ( $recent->have_posts() ) :
        ?>
        <div class="news-grid" style="max-width:900px;margin:0 auto;">
            <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
            <article class="news-card" style="text-align:left;">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="news-card-thumb">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'fafo-card', [ 'alt' => get_the_title() ] ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="news-card-body">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="post-meta">
                        <span><?php echo get_the_date('M j, Y'); ?></span>
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>
    </div>

</div><!-- .error-404 -->

</div><!-- .container -->
</main>

<?php get_footer(); ?>
