<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- PATRIOT STRIPE -->
<div class="patriot-stripe" aria-hidden="true"></div>

<!-- ALERT BAR (customizer-driven) -->
<?php $alert = get_theme_mod( 'fafo_alert_text', '' ); if ( $alert ) : ?>
<div class="alert-bar">
    <i class="fas fa-exclamation-triangle"></i>
    <?php echo esc_html( $alert ); ?>
    <i class="fas fa-exclamation-triangle"></i>
</div>
<?php endif; ?>

<!-- TOP BAR -->
<div class="top-bar">
    <div class="container">
        <div class="top-date">
            <i class="far fa-calendar-alt"></i>
            <?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?>
        </div>
        <div class="top-social">
            <a href="<?php echo esc_url( fafo_social('twitter') ); ?>" aria-label="Twitter/X"><i class="fab fa-x-twitter"></i> Twitter</a>
            <a href="<?php echo esc_url( fafo_social('facebook') ); ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="<?php echo esc_url( fafo_social('truth') ); ?>" aria-label="Truth Social"><i class="fas fa-flag"></i> Truth</a>
            <a href="<?php echo esc_url( fafo_social('rumble') ); ?>" aria-label="Rumble"><i class="fas fa-play-circle"></i> Rumble</a>
            <a href="<?php echo esc_url( fafo_social('telegram') ); ?>" aria-label="Telegram"><i class="fab fa-telegram-plane"></i> Telegram</a>
        </div>
    </div>
</div>

<!-- BREAKING NEWS TICKER -->
<div class="breaking-ticker" role="marquee" aria-live="polite">
    <div class="ticker-label">
        <i class="fas fa-bolt"></i>&nbsp; BREAKING
    </div>
    <div class="ticker-wrapper">
        <div class="ticker-items" id="fafoTicker">
            <?php foreach ( fafo_get_ticker_items() as $item ) : ?>
                <span><?php echo esc_html( $item ); ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- SITE HEADER / MASTHEAD -->
<header class="site-header" role="banner">
    <div class="container">
        <div class="header-inner">

            <!-- Logo: custom upload takes priority, SVG badge is the fallback -->
            <?php if ( has_custom_logo() ) : ?>
                <div class="site-logo site-logo--custom">
                    <?php the_custom_logo(); ?>
                    <span class="site-tagline site-tagline--custom">
                        <?php echo esc_html( get_theme_mod( 'fafo_header_tagline', 'FOR AMERICA FIRST ONLY' ) ); ?>
                    </span>
                </div>
            <?php else : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">

                <!-- Eagle SVG Emblem (default fallback) -->
                <svg class="logo-emblem" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <!-- Shield background -->
                    <path d="M50 5 L90 22 L90 58 Q90 82 50 96 Q10 82 10 58 L10 22 Z" fill="#002868"/>
                    <!-- Red stripes on shield -->
                    <clipPath id="shieldClip">
                        <path d="M50 5 L90 22 L90 58 Q90 82 50 96 Q10 82 10 58 L10 22 Z"/>
                    </clipPath>
                    <g clip-path="url(#shieldClip)">
                        <rect x="0"  y="0" width="16" height="100" fill="#C8102E"/>
                        <rect x="32" y="0" width="16" height="100" fill="#C8102E"/>
                        <rect x="64" y="0" width="16" height="100" fill="#C8102E"/>
                    </g>
                    <!-- Shield border -->
                    <path d="M50 5 L90 22 L90 58 Q90 82 50 96 Q10 82 10 58 L10 22 Z" fill="none" stroke="#FFD700" stroke-width="3"/>
                    <!-- Stars cluster -->
                    <text x="50" y="45" text-anchor="middle" fill="#FFD700" font-size="10" font-family="Arial">★ ★ ★</text>
                    <text x="50" y="58" text-anchor="middle" fill="#FFD700" font-size="10" font-family="Arial">★ ★ ★ ★</text>
                    <text x="50" y="71" text-anchor="middle" fill="#FFD700" font-size="10" font-family="Arial">★ ★ ★</text>
                    <!-- Top arc text -->
                    <path id="topArc" d="M 20 35 A 30 30 0 0 1 80 35" fill="none"/>
                    <text font-size="7" font-family="Arial" font-weight="bold" fill="#FFFFFF" letter-spacing="1">
                        <textPath href="#topArc" startOffset="15%">AMERICA FIRST</textPath>
                    </text>
                </svg>

                <div class="logo-text">
                    <span class="site-title">F<span>A</span>FO</span>
                    <span class="site-tagline">
                        <?php echo esc_html( get_theme_mod( 'fafo_header_tagline', 'FOR AMERICA FIRST ONLY' ) ); ?>
                    </span>
                </div>
            </a>
            <?php endif; ?>

            <!-- Flag stripes decoration -->
            <div class="header-flags" aria-hidden="true">
                <?php for ( $i = 0; $i < 13; $i++ ) : ?>
                <div class="flag-stripe"></div>
                <?php endfor; ?>
            </div>

        </div><!-- .header-inner -->
    </div><!-- .container -->
</header>

<!-- PRIMARY NAVIGATION -->
<nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'fafo' ); ?>">
    <div class="container">

        <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="primaryMenu" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>

        <?php
        wp_nav_menu( [
            'theme_location' => 'primary',
            'menu_id'        => 'primaryMenu',
            'menu_class'     => 'nav-menu',
            'container'      => false,
            'fallback_cb'    => 'fafo_fallback_nav',
        ] );
        ?>

        <div class="nav-search" role="search">
            <form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input
                    type="search"
                    name="s"
                    placeholder="<?php esc_attr_e( 'Search FAFO News...', 'fafo' ); ?>"
                    value="<?php echo esc_attr( get_search_query() ); ?>"
                    aria-label="<?php esc_attr_e( 'Search', 'fafo' ); ?>"
                >
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

    </div><!-- .container -->
</nav>

<?php
// Fallback nav when no menu assigned
function fafo_fallback_nav() {
    echo '<ul class="nav-menu">';
    $pages = [
        'Home'              => home_url('/'),
        'Politics'          => fafo_cat_link('politics'),
        'Economy'           => fafo_cat_link('economy'),
        'National Security' => fafo_cat_link('national-security'),
        'Border'            => fafo_cat_link('border-immigration'),
        'Opinion'           => fafo_cat_link('opinion'),
        'Video'             => get_post_type_archive_link('fafo_video') ?: home_url('/video/'),
        'About'             => fafo_page_link('about'),
    ];
    foreach ( $pages as $label => $url ) {
        echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}
?>
