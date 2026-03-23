<?php
/**
 * FAFO Theme Functions
 * For America First Only - Conservative News
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// THEME SETUP
// ============================================================
function fafo_setup() {
    load_theme_textdomain( 'fafo', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', [ 'search-form','comment-form','comment-list','gallery','caption','style','script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    add_theme_support( 'custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    // Thumbnail sizes
    add_image_size( 'fafo-hero',     1200, 680, true );
    add_image_size( 'fafo-card',     600,  338, true );
    add_image_size( 'fafo-thumb',    300,  225, true );
    add_image_size( 'fafo-mini',     120,  90,  true );

    // Menus
    register_nav_menus( [
        'primary'  => __( 'Primary Navigation', 'fafo' ),
        'footer'   => __( 'Footer Navigation',  'fafo' ),
        'top-bar'  => __( 'Top Bar Links',       'fafo' ),
    ] );
}
add_action( 'after_setup_theme', 'fafo_setup' );

// ============================================================
// CONTENT WIDTH
// ============================================================
if ( ! isset( $content_width ) ) $content_width = 1280;

// ============================================================
// ENQUEUE SCRIPTS & STYLES
// ============================================================
function fafo_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'fafo-fonts',
        'https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700;900&family=Source+Serif+Pro:wght@400;600&family=Open+Sans:wght@400;600;700&display=swap',
        [],
        null
    );

    // Theme stylesheet
    wp_enqueue_style( 'fafo-style', get_stylesheet_uri(), [ 'fafo-fonts' ], '1.0.0' );

    // FontAwesome (icons)
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );

    // Theme JS
    wp_enqueue_script( 'fafo-main', get_template_directory_uri() . '/assets/js/main.js', [ 'jquery' ], '1.0.0', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_localize_script( 'fafo-main', 'fafoData', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'fafo_nonce' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'fafo_scripts' );

// ============================================================
// REGISTER WIDGET AREAS / SIDEBARS
// ============================================================
function fafo_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Main Sidebar', 'fafo' ),
        'id'            => 'sidebar-main',
        'description'   => __( 'Primary sidebar shown on most pages.', 'fafo' ),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3><div class="widget-body">',
    ] );

    register_sidebar( [
        'name'          => __( 'Footer Column 2', 'fafo' ),
        'id'            => 'footer-2',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5><ul>',
    ] );

    register_sidebar( [
        'name'          => __( 'Footer Column 3', 'fafo' ),
        'id'            => 'footer-3',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5>',
        'after_title'   => '</h5><ul>',
    ] );
}
add_action( 'widgets_init', 'fafo_widgets_init' );

// ============================================================
// CUSTOM POST TYPES
// ============================================================
function fafo_register_post_types() {
    // Opinion pieces
    register_post_type( 'opinion', [
        'labels' => [
            'name'          => __( 'Opinions', 'fafo' ),
            'singular_name' => __( 'Opinion', 'fafo' ),
        ],
        'public'      => true,
        'has_archive' => true,
        'supports'    => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ],
        'rewrite'     => [ 'slug' => 'opinion' ],
        'menu_icon'   => 'dashicons-format-quote',
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'fafo_register_post_types' );

// ============================================================
// EXCERPT LENGTH
// ============================================================
function fafo_excerpt_length( $length ) { return 25; }
add_filter( 'excerpt_length', 'fafo_excerpt_length' );

function fafo_excerpt_more( $more ) { return '...'; }
add_filter( 'excerpt_more', 'fafo_excerpt_more' );

// ============================================================
// BREAKING NEWS TICKER (stored as option, editable in Customizer)
// ============================================================
function fafo_get_ticker_items() {
    $default = [
        'BREAKING: America First policies continue to gain momentum across the nation',
        'EXCLUSIVE: Conservative leaders unite against globalist agenda',
        'FAFO REPORT: Border security reaches record levels under patriot leadership',
        'ANALYSIS: Why the mainstream media is losing the information war',
        'FAFO EXCLUSIVE: Patriots rally in record numbers across 50 states',
    ];
    $saved = get_option( 'fafo_ticker_items', '' );
    if ( ! empty( $saved ) ) {
        return array_filter( array_map( 'trim', explode( "\n", $saved ) ) );
    }
    return $default;
}

// ============================================================
// CUSTOMIZER SETTINGS
// ============================================================
function fafo_customize_register( $wp_customize ) {

    // --- FAFO Panel ---
    $wp_customize->add_panel( 'fafo_panel', [
        'title'    => __( 'FAFO Theme Options', 'fafo' ),
        'priority' => 30,
    ] );

    // Breaking Ticker section
    $wp_customize->add_section( 'fafo_ticker', [
        'title' => __( 'Breaking News Ticker', 'fafo' ),
        'panel' => 'fafo_panel',
    ] );

    $wp_customize->add_setting( 'fafo_ticker_items', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ] );

    $wp_customize->add_control( 'fafo_ticker_items', [
        'label'       => __( 'Ticker Headlines (one per line)', 'fafo' ),
        'section'     => 'fafo_ticker',
        'type'        => 'textarea',
    ] );

    // Alert bar
    $wp_customize->add_section( 'fafo_alert', [
        'title' => __( 'Alert Bar', 'fafo' ),
        'panel' => 'fafo_panel',
    ] );

    $wp_customize->add_setting( 'fafo_alert_text', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );

    $wp_customize->add_control( 'fafo_alert_text', [
        'label'   => __( 'Alert Bar Text (leave blank to hide)', 'fafo' ),
        'section' => 'fafo_alert',
        'type'    => 'text',
    ] );

    // Site tagline override
    $wp_customize->add_section( 'fafo_branding', [
        'title' => __( 'FAFO Branding', 'fafo' ),
        'panel' => 'fafo_panel',
    ] );

    $wp_customize->add_setting( 'fafo_header_tagline', [
        'default'           => 'FOR AMERICA FIRST ONLY',
        'sanitize_callback' => 'sanitize_text_field',
    ] );

    $wp_customize->add_control( 'fafo_header_tagline', [
        'label'   => __( 'Header Tagline', 'fafo' ),
        'section' => 'fafo_branding',
        'type'    => 'text',
    ] );
}
add_action( 'customize_register', 'fafo_customize_register' );

// ============================================================
// HELPER: Get reading time
// ============================================================
function fafo_reading_time( $post_id = null ) {
    $content    = get_post_field( 'post_content', $post_id ?? get_the_ID() );
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes    = max( 1, (int) ceil( $word_count / 200 ) );
    return sprintf( _n( '%d min read', '%d min read', $minutes, 'fafo' ), $minutes );
}

// ============================================================
// HELPER: Post category badge
// ============================================================
function fafo_category_badge( $post_id = null ) {
    $cats = get_the_category( $post_id ?? get_the_ID() );
    if ( empty( $cats ) ) return '';
    $cat = $cats[0];
    return '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="post-category-link">'
         . esc_html( $cat->name ) . '</a>';
}

// ============================================================
// HELPER: Fallback placeholder image SVG
// ============================================================
function fafo_placeholder_img( $width = 600, $height = 338, $label = 'FAFO NEWS' ) {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">'
         . '<rect width="100%" height="100%" fill="#002868"/>'
         . '<text x="50%" y="44%" text-anchor="middle" fill="#C8102E" font-family="Impact,sans-serif" font-size="' . (int)($width/6) . '" font-weight="900">FAFO</text>'
         . '<text x="50%" y="62%" text-anchor="middle" fill="rgba(255,255,255,0.5)" font-family="Arial,sans-serif" font-size="' . (int)($width/28) . '">' . esc_attr( $label ) . '</text>'
         . '</svg>';
}

// ============================================================
// BODY CLASSES
// ============================================================
function fafo_body_classes( $classes ) {
    if ( is_singular() )  $classes[] = 'is-singular';
    if ( is_category() )  $classes[] = 'is-category';
    if ( is_home() )      $classes[] = 'is-home-blog';
    return $classes;
}
add_filter( 'body_class', 'fafo_body_classes' );

// ============================================================
// AJAX: Newsletter signup (stub)
// ============================================================
function fafo_newsletter_signup() {
    check_ajax_referer( 'fafo_nonce', 'nonce' );
    $email = sanitize_email( $_POST['email'] ?? '' );
    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Invalid email address.', 'fafo' ) ] );
    }
    // TODO: integrate with email provider (Mailchimp, ConvertKit, etc.)
    wp_send_json_success( [ 'message' => __( 'Thank you, patriot! You\'re subscribed.', 'fafo' ) ] );
}
add_action( 'wp_ajax_nopriv_fafo_newsletter', 'fafo_newsletter_signup' );
add_action( 'wp_ajax_fafo_newsletter',        'fafo_newsletter_signup' );

// ============================================================
// SECURITY: Remove WordPress version from head
// ============================================================
remove_action( 'wp_head', 'wp_generator' );

// ============================================================
// SEO: Add Open Graph meta (basic)
// ============================================================
function fafo_open_graph() {
    if ( is_singular() ) {
        global $post;
        $title       = get_the_title();
        $description = get_the_excerpt() ?: wp_trim_words( get_the_content(), 30, '...' );
        $image       = get_the_post_thumbnail_url( $post->ID, 'fafo-hero' );
        $url         = get_permalink();
        ?>
        <meta property="og:type"        content="article" />
        <meta property="og:title"       content="<?php echo esc_attr( $title ); ?>" />
        <meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
        <meta property="og:url"         content="<?php echo esc_url( $url ); ?>" />
        <?php if ( $image ) : ?>
        <meta property="og:image"       content="<?php echo esc_url( $image ); ?>" />
        <?php endif; ?>
        <meta name="twitter:card"       content="summary_large_image" />
        <meta name="twitter:title"      content="<?php echo esc_attr( $title ); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>" />
        <?php
    }
}
add_action( 'wp_head', 'fafo_open_graph' );
