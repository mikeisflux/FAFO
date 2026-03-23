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

// ============================================================
// INCLUDE SETUP / HELPERS
// ============================================================
require_once get_template_directory() . '/inc/setup-categories.php';
require_once get_template_directory() . '/inc/template-tags.php';

// ============================================================
// VIDEO CUSTOM POST TYPE
// ============================================================
function fafo_register_video_post_type() {
    register_post_type( 'fafo_video', [
        'labels' => [
            'name'               => __( 'Videos', 'fafo' ),
            'singular_name'      => __( 'Video', 'fafo' ),
            'add_new'            => __( 'Add New Video', 'fafo' ),
            'add_new_item'       => __( 'Add New Video', 'fafo' ),
            'edit_item'          => __( 'Edit Video', 'fafo' ),
            'new_item'           => __( 'New Video', 'fafo' ),
            'view_item'          => __( 'View Video', 'fafo' ),
            'search_items'       => __( 'Search Videos', 'fafo' ),
            'not_found'          => __( 'No videos found', 'fafo' ),
            'not_found_in_trash' => __( 'No videos in trash', 'fafo' ),
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => [ 'slug' => 'video' ],
        'supports'     => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments' ],
        'menu_icon'    => 'dashicons-video-alt3',
        'show_in_rest' => true,
    ] );

    // Video taxonomy for categories
    register_taxonomy( 'video_category', 'fafo_video', [
        'labels' => [
            'name'          => __( 'Video Categories', 'fafo' ),
            'singular_name' => __( 'Video Category', 'fafo' ),
        ],
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'video-category' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'fafo_register_video_post_type' );

// ============================================================
// VIDEO: Meta box for embed URL
// ============================================================
function fafo_video_meta_boxes() {
    add_meta_box(
        'fafo_video_url',
        __( 'Video URL (YouTube, Rumble, Vimeo, etc.)', 'fafo' ),
        'fafo_video_url_callback',
        'fafo_video',
        'normal',
        'high'
    );
    add_meta_box(
        'fafo_video_duration',
        __( 'Video Details', 'fafo' ),
        'fafo_video_details_callback',
        'fafo_video',
        'side'
    );
}
add_action( 'add_meta_boxes', 'fafo_video_meta_boxes' );

function fafo_video_url_callback( $post ) {
    wp_nonce_field( 'fafo_video_meta', 'fafo_video_nonce' );
    $url = get_post_meta( $post->ID, '_fafo_video_url', true );
    ?>
    <p>
        <label for="fafo_video_url" style="font-weight:600;">Paste video URL from YouTube, Rumble, Vimeo, or direct MP4:</label><br>
        <input type="url" id="fafo_video_url" name="fafo_video_url" value="<?php echo esc_attr( $url ); ?>"
               placeholder="https://rumble.com/embed/..." style="width:100%;margin-top:6px;">
    </p>
    <p style="color:#666;font-size:12px;">Supports: YouTube, Rumble, Vimeo, Dailymotion, and direct MP4/WebM URLs.</p>
    <?php
}

function fafo_video_details_callback( $post ) {
    $duration = get_post_meta( $post->ID, '_fafo_video_duration', true );
    $source   = get_post_meta( $post->ID, '_fafo_video_source', true );
    ?>
    <p>
        <label style="font-weight:600;">Duration:</label><br>
        <input type="text" name="fafo_video_duration" value="<?php echo esc_attr( $duration ); ?>"
               placeholder="e.g. 12:34" style="width:100%;">
    </p>
    <p>
        <label style="font-weight:600;">Source:</label><br>
        <select name="fafo_video_source" style="width:100%;">
            <option value="rumble" <?php selected( $source, 'rumble' ); ?>>Rumble</option>
            <option value="youtube" <?php selected( $source, 'youtube' ); ?>>YouTube</option>
            <option value="vimeo" <?php selected( $source, 'vimeo' ); ?>>Vimeo</option>
            <option value="direct" <?php selected( $source, 'direct' ); ?>>Direct MP4</option>
            <option value="other" <?php selected( $source, 'other' ); ?>>Other</option>
        </select>
    </p>
    <?php
}

function fafo_video_meta_save( $post_id ) {
    if ( ! isset( $_POST['fafo_video_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['fafo_video_nonce'], 'fafo_video_meta' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['fafo_video_url'] ) ) {
        update_post_meta( $post_id, '_fafo_video_url', esc_url_raw( $_POST['fafo_video_url'] ) );
    }
    if ( isset( $_POST['fafo_video_duration'] ) ) {
        update_post_meta( $post_id, '_fafo_video_duration', sanitize_text_field( $_POST['fafo_video_duration'] ) );
    }
    if ( isset( $_POST['fafo_video_source'] ) ) {
        update_post_meta( $post_id, '_fafo_video_source', sanitize_text_field( $_POST['fafo_video_source'] ) );
    }
}
add_action( 'save_post', 'fafo_video_meta_save' );

// ============================================================
// VIDEO: Render player from URL
// ============================================================
function fafo_render_video_player( $post_id = null ) {
    $post_id = $post_id ?? get_the_ID();
    $url     = get_post_meta( $post_id, '_fafo_video_url', true );
    $source  = get_post_meta( $post_id, '_fafo_video_source', true );

    if ( empty( $url ) ) return '';

    // Direct MP4 / WebM
    if ( $source === 'direct' || preg_match( '/\.(mp4|webm|ogv|ogg)(\?|$)/i', $url ) ) {
        return '<div class="fafo-video-player">'
             . '<video controls preload="metadata" style="width:100%;max-width:100%;border-radius:4px;background:#000;">'
             . '<source src="' . esc_url( $url ) . '" type="video/mp4">'
             . 'Your browser does not support HTML5 video.'
             . '</video></div>';
    }

    // Rumble embed
    if ( strpos( $url, 'rumble.com' ) !== false ) {
        // Convert watch URL to embed URL
        if ( strpos( $url, '/embed/' ) === false ) {
            preg_match( '/rumble\.com\/([a-zA-Z0-9_-]+)/', $url, $m );
            if ( ! empty( $m[1] ) ) {
                $url = 'https://rumble.com/embed/' . $m[1] . '/';
            }
        }
        return '<div class="fafo-video-player" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">'
             . '<iframe src="' . esc_url( $url ) . '" frameborder="0" allowfullscreen '
             . 'style="position:absolute;top:0;left:0;width:100%;height:100%;border-radius:4px;"></iframe></div>';
    }

    // WordPress oEmbed handles YouTube, Vimeo, Dailymotion, etc.
    $oembed = wp_oembed_get( $url );
    if ( $oembed ) {
        return '<div class="fafo-video-player">' . $oembed . '</div>';
    }

    // Generic iframe fallback
    return '<div class="fafo-video-player" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;">'
         . '<iframe src="' . esc_url( $url ) . '" frameborder="0" allowfullscreen '
         . 'style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe></div>';
}

// ============================================================
// HELPER: Get category link by slug safely
// ============================================================
function fafo_cat_link( $slug ) {
    $cat = get_category_by_slug( $slug );
    if ( $cat ) return get_category_link( $cat->term_id );
    return home_url( '/category/' . $slug . '/' );
}

function fafo_page_link( $slug ) {
    $page = get_page_by_path( $slug );
    if ( $page ) return get_permalink( $page->ID );
    return home_url( '/' . $slug . '/' );
}

// ============================================================
// THEME ACTIVATION: create categories and pages
// ============================================================
add_action( 'after_switch_theme', 'fafo_theme_activation_setup' );
function fafo_theme_activation_setup() {
    fafo_create_categories();
    fafo_create_video_categories();
    fafo_create_pages();
    flush_rewrite_rules();
}

// Run once on init if not already done
add_action( 'init', 'fafo_maybe_run_setup', 999 );
function fafo_maybe_run_setup() {
    if ( ! get_option( 'fafo_setup_complete' ) ) {
        fafo_create_categories();
        fafo_create_video_categories();
        fafo_create_pages();
        update_option( 'fafo_setup_complete', '1.0' );
        flush_rewrite_rules();
    }
}

// ============================================================
// ENQUEUE: Video player CSS additions
// ============================================================
add_action( 'wp_enqueue_scripts', function() {
    wp_add_inline_style( 'fafo-style', '
        .fafo-video-player { margin: 0 0 24px; }
        .fafo-video-player iframe,
        .fafo-video-player video { max-width: 100%; border-radius: 4px; }
        .video-card { background:#fff; border-radius:6px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08); transition:transform .2s,box-shadow .2s; }
        .video-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,.13); }
        .video-card-thumb { position:relative; padding-bottom:56.25%; background:#000; overflow:hidden; }
        .video-card-thumb img { position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; }
        .video-play-btn { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:60px; height:60px; background:rgba(200,16,46,.9); border-radius:50%; display:flex; align-items:center; justify-content:center; pointer-events:none; }
        .video-play-btn::after { content:""; border-left:22px solid #fff; border-top:13px solid transparent; border-bottom:13px solid transparent; margin-left:4px; }
        .video-duration { position:absolute; bottom:8px; right:8px; background:rgba(0,0,0,.75); color:#fff; font-size:11px; padding:2px 6px; border-radius:3px; }
        .video-card-body { padding:16px; }
        .video-card-body h3 { font-size:1rem; margin:0 0 8px; line-height:1.4; }
        .video-card-body h3 a { color:#002868; text-decoration:none; }
        .video-card-body h3 a:hover { color:#C8102E; }
        .video-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:24px; }
        .video-featured-player { background:#000; border-radius:8px; overflow:hidden; margin-bottom:32px; }
        @media(max-width:600px){ .video-grid { grid-template-columns:1fr; } }
    ' );
}, 20 );

