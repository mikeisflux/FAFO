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
// WOOCOMMERCE THEME SUPPORT
// ============================================================
function fafo_woocommerce_setup() {
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 4,
        ],
    ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'fafo_woocommerce_setup' );

// Disable WooCommerce default stylesheet — we supply our own via inline CSS.
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

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
        'name'          => __( 'Shop Sidebar', 'fafo' ),
        'id'            => 'sidebar-shop',
        'description'   => __( 'Widgets on WooCommerce shop/product pages.', 'fafo' ),
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
// TEAM MEMBER CPT
// ============================================================
add_action( 'init', function() {
    register_post_type( 'fafo_team', [
        'labels' => [
            'name'               => __( 'Team Members', 'fafo' ),
            'singular_name'      => __( 'Team Member', 'fafo' ),
            'add_new_item'       => __( 'Add Team Member', 'fafo' ),
            'edit_item'          => __( 'Edit Team Member', 'fafo' ),
            'new_item'           => __( 'New Team Member', 'fafo' ),
            'view_item'          => __( 'View Team Member', 'fafo' ),
            'search_items'       => __( 'Search Team Members', 'fafo' ),
            'not_found'          => __( 'No team members found', 'fafo' ),
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'thumbnail', 'page-attributes' ],
        'menu_icon'     => 'dashicons-groups',
        'rewrite'       => false,
    ] );
} );

add_action( 'add_meta_boxes', function() {
    add_meta_box( 'fafo_team_details', __( 'Team Member Details', 'fafo' ),
        'fafo_team_meta_callback', 'fafo_team', 'normal', 'high' );
} );

function fafo_team_meta_callback( $post ) {
    wp_nonce_field( 'fafo_team_meta', 'fafo_team_nonce' );
    $role   = get_post_meta( $post->ID, '_fafo_team_role',     true );
    $dept   = get_post_meta( $post->ID, '_fafo_team_dept',     true );
    $bio    = get_post_meta( $post->ID, '_fafo_team_bio',      true );
    $tw     = get_post_meta( $post->ID, '_fafo_team_twitter',  true );
    $fb     = get_post_meta( $post->ID, '_fafo_team_facebook', true );
    ?>
    <table class="form-table" style="width:100%;">
        <tr><th style="width:160px;"><label>Role / Title</label></th>
            <td><input type="text" name="fafo_team_role" value="<?php echo esc_attr($role); ?>" style="width:100%;" placeholder="e.g. Editor-in-Chief"></td></tr>
        <tr><th><label>Department</label></th>
            <td>
                <select name="fafo_team_dept" style="width:100%;">
                    <?php foreach ( ['Leadership','Editorial','Reporters','Opinion','Video & Multimedia','Technology','Operations'] as $d ) : ?>
                    <option value="<?php echo esc_attr($d); ?>" <?php selected($dept,$d); ?>><?php echo esc_html($d); ?></option>
                    <?php endforeach; ?>
                </select>
            </td></tr>
        <tr><th><label>Bio</label></th>
            <td><textarea name="fafo_team_bio" rows="4" style="width:100%;"><?php echo esc_textarea($bio); ?></textarea></td></tr>
        <tr><th><label>Twitter / X URL</label></th>
            <td><input type="url" name="fafo_team_twitter" value="<?php echo esc_attr($tw); ?>" style="width:100%;" placeholder="https://x.com/username"></td></tr>
        <tr><th><label>Facebook URL</label></th>
            <td><input type="url" name="fafo_team_facebook" value="<?php echo esc_attr($fb); ?>" style="width:100%;" placeholder="https://facebook.com/username"></td></tr>
    </table>
    <p style="color:#666;font-size:.85rem;margin-top:12px;">
        <strong>Photo:</strong> Set the Featured Image (top-right panel) to use a team member photo.
        <strong>Display order:</strong> Use the Order field in Page Attributes.
    </p>
    <?php
}

add_action( 'save_post_fafo_team', function( $post_id ) {
    if ( ! isset( $_POST['fafo_team_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fafo_team_nonce'] ) ), 'fafo_team_meta' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    $fields = [ 'fafo_team_role' => '_fafo_team_role', 'fafo_team_dept' => '_fafo_team_dept',
                'fafo_team_bio'  => '_fafo_team_bio',  'fafo_team_twitter' => '_fafo_team_twitter',
                'fafo_team_facebook' => '_fafo_team_facebook' ];
    foreach ( $fields as $key => $meta ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $meta, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
        }
    }
} );

// ============================================================
// JOB LISTING CPT
// ============================================================
add_action( 'init', function() {
    register_post_type( 'fafo_job', [
        'labels' => [
            'name'               => __( 'Job Listings', 'fafo' ),
            'singular_name'      => __( 'Job Listing', 'fafo' ),
            'add_new_item'       => __( 'Add Job Listing', 'fafo' ),
            'edit_item'          => __( 'Edit Job Listing', 'fafo' ),
            'not_found'          => __( 'No job listings found', 'fafo' ),
        ],
        'public'        => false,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'editor', 'page-attributes' ],
        'menu_icon'     => 'dashicons-businessperson',
        'rewrite'       => false,
    ] );
} );

add_action( 'add_meta_boxes', function() {
    add_meta_box( 'fafo_job_details', __( 'Job Details', 'fafo' ),
        'fafo_job_meta_callback', 'fafo_job', 'side', 'high' );
} );

function fafo_job_meta_callback( $post ) {
    wp_nonce_field( 'fafo_job_meta', 'fafo_job_nonce' );
    $type  = get_post_meta( $post->ID, '_fafo_job_type',     true );
    $loc   = get_post_meta( $post->ID, '_fafo_job_location', true );
    $dept  = get_post_meta( $post->ID, '_fafo_job_dept',     true );
    $reqs  = get_post_meta( $post->ID, '_fafo_job_reqs',     true );
    ?>
    <p><label style="font-weight:600;display:block;margin-bottom:4px;">Job Type</label>
        <select name="fafo_job_type" style="width:100%;">
            <?php foreach ( ['Full-Time','Part-Time','Part-Time / Contributing','Contract'] as $t ) : ?>
            <option value="<?php echo esc_attr($t); ?>" <?php selected($type,$t); ?>><?php echo esc_html($t); ?></option>
            <?php endforeach; ?>
        </select></p>
    <p><label style="font-weight:600;display:block;margin-bottom:4px;">Location</label>
        <input type="text" name="fafo_job_location" value="<?php echo esc_attr($loc); ?>" style="width:100%;" placeholder="Remote / Washington D.C."></p>
    <p><label style="font-weight:600;display:block;margin-bottom:4px;">Department</label>
        <select name="fafo_job_dept" style="width:100%;">
            <?php foreach ( ['Editorial','Opinion','Video & Multimedia','Technology','Operations','Sales'] as $d ) : ?>
            <option value="<?php echo esc_attr($d); ?>" <?php selected($dept,$d); ?>><?php echo esc_html($d); ?></option>
            <?php endforeach; ?>
        </select></p>
    <p><label style="font-weight:600;display:block;margin-bottom:4px;">Requirements <small style="font-weight:400;">(one per line)</small></label>
        <textarea name="fafo_job_reqs" rows="6" style="width:100%;" placeholder="5+ years experience&#10;Strong writing skills"><?php echo esc_textarea($reqs); ?></textarea></p>
    <p style="color:#666;font-size:.8rem;">Use the main editor above for the full job description.</p>
    <?php
}

add_action( 'save_post_fafo_job', function( $post_id ) {
    if ( ! isset( $_POST['fafo_job_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fafo_job_nonce'] ) ), 'fafo_job_meta' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    foreach ( [ 'fafo_job_type' => '_fafo_job_type', 'fafo_job_location' => '_fafo_job_location',
                'fafo_job_dept' => '_fafo_job_dept',  'fafo_job_reqs' => '_fafo_job_reqs' ] as $k => $m ) {
        if ( isset( $_POST[$k] ) ) update_post_meta( $post_id, $m, sanitize_textarea_field( wp_unslash( $_POST[$k] ) ) );
    }
} );

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
// SIDEBAR WIDGET: About FAFO (reads from FAFO Settings)
// ============================================================
function fafo_widget_about() {
    $image_id = get_option( 'fafo_about_widget_image' );
    $title    = get_option( 'fafo_about_widget_title', 'About FAFO' );
    $text     = get_option( 'fafo_about_widget_text',  'For America First Only — Your #1 source for bold, unapologetic conservative news and commentary. No spin. No agenda. Just the truth.' );
    ?>
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-flag"></i> <?php echo esc_html( $title ); ?></h3>
        <div class="widget-body">
            <div class="about-widget">
                <?php if ( $image_id ) :
                    echo wp_get_attachment_image( $image_id, 'medium', false, [
                        'style' => 'max-width:100%;height:auto;display:block;margin:0 auto 12px;border-radius:4px;',
                        'alt'   => esc_attr( get_bloginfo('name') ),
                    ] );
                else : ?>
                <div class="fafo-big">F<span>A</span>FO</div>
                <?php endif; ?>
                <p><?php echo esc_html( $text ); ?></p>
            </div>
        </div>
    </div>
    <?php
}

// ============================================================
// SIDEBAR WIDGET: Newsletter signup (reads from FAFO Settings)
// ============================================================
function fafo_widget_newsletter() {
    $heading  = get_option( 'fafo_newsletter_heading',  'JOIN THE MOVEMENT' );
    $subtext  = get_option( 'fafo_newsletter_subtext',  'Get FAFO breaking news delivered straight to your inbox. No censorship.' );
    $btn_text = get_option( 'fafo_newsletter_btn_text', 'SUBSCRIBE FREE' );
    ?>
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-envelope"></i> Stay Informed</h3>
        <div class="newsletter-widget">
            <h4><?php echo esc_html( $heading ); ?></h4>
            <p><?php echo esc_html( $subtext ); ?></p>
            <form id="fafoNewsletterForm" action="#" method="post">
                <input type="email" name="email" placeholder="Your email address..." required>
                <button type="submit">
                    <i class="fas fa-bolt"></i> <?php echo esc_html( $btn_text ); ?>
                </button>
            </form>
        </div>
    </div>
    <?php
}

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

    // MailPoet integration — subscribe to the first active list.
    if ( class_exists( '\MailPoet\API\API' ) ) {
        try {
            $mp    = \MailPoet\API\API::MP( 'v1' );
            $lists = $mp->getLists();
            if ( ! empty( $lists ) ) {
                $list_id = $lists[0]['id'];
                try {
                    $mp->addSubscriber( [ 'email' => $email ], [ $list_id ] );
                } catch ( \MailPoet\API\MP\v1\APIException $e ) {
                    // Code 4 = already subscribed — treat as success.
                    if ( $e->getCode() !== 4 ) {
                        wp_send_json_error( [ 'message' => $e->getMessage() ] );
                    }
                }
            }
        } catch ( \Exception $e ) {
            // MailPoet not fully configured — still return success so UX isn't broken.
        }
    }

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
    // Yoast SEO outputs its own full OG/Twitter block — avoid duplicates.
    if ( defined( 'WPSEO_VERSION' ) ) return;
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
require_once get_template_directory() . '/inc/admin-settings.php';

// ============================================================
// HELPER: Social media URL from settings (falls back to '#')
// ============================================================
function fafo_social( $network ) {
    static $defaults = [
        'twitter'  => '#', 'facebook' => '#', 'truth'    => '#',
        'rumble'   => '#', 'telegram' => '#', 'youtube'  => '',
    ];
    $val = get_option( 'fafo_social_' . $network, '' );
    return $val ?: ( $defaults[ $network ] ?? '#' );
}

// ============================================================
// HELPER: Contact email from settings
// ============================================================
function fafo_contact_email( $type ) {
    $d = 'foramericafirstonly.com';
    static $defaults = null;
    if ( null === $defaults ) {
        $d2 = 'foramericafirstonly.com';
        $defaults = [
            'newsroom'  => "news@{$d2}",  'tips'      => "tips@{$d2}",
            'advertise' => "advertise@{$d2}", 'legal' => "legal@{$d2}",
            'careers'   => "careers@{$d2}",  'press' => "press@{$d2}",
        ];
    }
    $val = get_option( 'fafo_contact_' . $type, '' );
    return $val ?: ( $defaults[ $type ] ?? '' );
}

// ============================================================
// DYNAMIC CSS: Color overrides from FAFO Settings
// ============================================================
add_action( 'wp_head', function() {
    $map = [
        '--clr-red'      => get_option( 'fafo_color_red' ),
        '--clr-navy'     => get_option( 'fafo_color_navy' ),
        '--clr-gold'     => get_option( 'fafo_color_gold' ),
        '--clr-darkred'  => get_option( 'fafo_color_darkred' ),
        '--clr-darknavy' => get_option( 'fafo_color_darknavy' ),
    ];
    $vars = array_filter( $map );
    if ( empty( $vars ) ) return;
    $css = ':root{';
    foreach ( $vars as $var => $val ) {
        $css .= $var . ':' . esc_attr( $val ) . ';';
    }
    $css .= '}';
    echo '<style id="fafo-color-overrides">' . $css . "</style>\n";
}, 20 );

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
        fafo_create_pages();   // also populates empty page content
        update_option( 'fafo_setup_complete', '1.0' );
        flush_rewrite_rules();
    }
}

// ============================================================
// WOOCOMMERCE: Breadcrumb branding
// ============================================================
add_filter( 'woocommerce_breadcrumb_defaults', function( $defaults ) {
    $defaults['delimiter']   = ' <i class="fas fa-chevron-right" style="font-size:10px;opacity:.6;"></i> ';
    $defaults['wrap_before'] = '<nav class="fafo-breadcrumb" aria-label="Breadcrumb"><p>';
    $defaults['wrap_after']  = '</p></nav>';
    return $defaults;
} );

// ============================================================
// WOOCOMMERCE: Move add-to-cart beneath single product summary
// ============================================================
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );

// ============================================================
// ENQUEUE: Video player CSS additions
// ============================================================
add_action( 'wp_enqueue_scripts', function() {
    wp_add_inline_style( 'fafo-style', '
        /* ---- Custom logo (uploaded via Customize → Site Identity) ---- */
        .site-logo--custom { display:flex; align-items:center; gap:14px; text-decoration:none; }
        .site-logo--custom .custom-logo-link { display:flex; align-items:center; flex-shrink:0; }
        .site-logo--custom .custom-logo { max-height:80px; width:auto; max-width:320px; display:block; }
        .site-logo--custom .site-tagline--custom { font-family:var(--font-head); font-size:1.95rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; color:rgba(255,255,255,.65); display:block; margin-top:4px; }
        @media(max-width:600px){ .site-logo--custom .custom-logo { max-height:52px; } }

        /* ---- Video player ---- */
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

    // WooCommerce styles
    if ( class_exists( 'WooCommerce' ) ) {
        wp_add_inline_style( 'fafo-style', '
            /* ---- WooCommerce Base ---- */
            .woocommerce-breadcrumb,.fafo-breadcrumb{font-family:var(--font-ui);font-size:.8rem;color:#999;padding:10px 0 4px;margin-bottom:20px;}
            .woocommerce-breadcrumb a,.fafo-breadcrumb a{color:#002868;}
            .woocommerce-breadcrumb a:hover,.fafo-breadcrumb a:hover{color:#C8102E;}

            /* ---- Notices ---- */
            .woocommerce-message,.woocommerce-info,.woocommerce-error{border-left:4px solid #002868;background:#fff;padding:14px 18px;margin-bottom:20px;border-radius:4px;font-family:var(--font-ui);font-size:.9rem;list-style:none;}
            .woocommerce-error{border-color:#C8102E;background:#fff5f5;}
            .woocommerce-message{border-color:#155724;background:#f0fff4;}

            /* ---- Product Grid (shop archive) ---- */
            .woocommerce ul.products{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:24px;list-style:none;padding:0;margin:0 0 40px;}
            .woocommerce ul.products li.product{background:#fff;border-radius:8px;border:1px solid #E8E8E8;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);transition:transform .2s,box-shadow .2s;position:relative;}
            .woocommerce ul.products li.product:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(0,0,0,.12);}
            .woocommerce ul.products li.product img{width:100%;height:220px;object-fit:cover;display:block;}
            .woocommerce ul.products li.product .woocommerce-loop-product__title{font-family:var(--font-head);font-size:1rem;font-weight:700;color:#002868;padding:14px 16px 4px;margin:0;text-transform:uppercase;letter-spacing:.03em;}
            .woocommerce ul.products li.product .price{font-family:var(--font-head);font-size:1.2rem;font-weight:900;color:#C8102E;padding:0 16px 10px;display:block;}
            .woocommerce ul.products li.product .price del{color:#999;font-size:.85rem;}
            .woocommerce ul.products li.product .onsale{position:absolute;top:10px;right:10px;background:#FFD700;color:#002868;font-family:var(--font-head);font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;padding:4px 10px;border-radius:20px;z-index:2;}
            .woocommerce ul.products li.product .button,.woocommerce ul.products .add_to_cart_button{display:block;width:calc(100% - 32px);margin:0 16px 16px;background:#002868;color:#fff;border:none;padding:10px;font-family:var(--font-head);font-size:.82rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:4px;text-align:center;cursor:pointer;transition:background .2s;}
            .woocommerce ul.products li.product .button:hover,.woocommerce ul.products .add_to_cart_button:hover{background:#C8102E;color:#fff;}

            /* ---- Single Product ---- */
            .woocommerce div.product .woocommerce-product-gallery{margin-bottom:24px;}
            .woocommerce div.product div.summary{padding-left:32px;}
            .woocommerce div.product .product_title{font-family:var(--font-head);font-size:1.8rem;font-weight:900;color:#002868;text-transform:uppercase;margin-bottom:12px;}
            .woocommerce div.product p.price,.woocommerce div.product span.price{font-family:var(--font-head);font-size:1.8rem;font-weight:900;color:#C8102E;display:block;margin:12px 0;}
            .woocommerce div.product .woocommerce-product-details__short-description{font-family:var(--font-body);font-size:.95rem;line-height:1.7;color:#444;margin-bottom:20px;}
            .woocommerce div.product .cart .qty{border:2px solid #E8E8E8;border-radius:4px;padding:8px 12px;font-size:1rem;width:70px;text-align:center;}
            .woocommerce div.product .cart .single_add_to_cart_button,.woocommerce #respond input#submit,.woocommerce a.button,.woocommerce button.button,.woocommerce input.button{background:#C8102E;color:#fff;font-family:var(--font-head);font-size:.9rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border:none;border-radius:4px;padding:12px 28px;cursor:pointer;transition:background .2s;}
            .woocommerce div.product .cart .single_add_to_cart_button:hover,.woocommerce a.button:hover,.woocommerce button.button:hover{background:#002868;}
            .woocommerce div.product .woocommerce-tabs ul.tabs{border-bottom:3px solid #002868;margin-bottom:20px;padding:0;list-style:none;display:flex;gap:4px;}
            .woocommerce div.product .woocommerce-tabs ul.tabs li{background:#F5F5F0;border:1px solid #E8E8E8;border-bottom:none;border-radius:4px 4px 0 0;}
            .woocommerce div.product .woocommerce-tabs ul.tabs li.active{background:#002868;}
            .woocommerce div.product .woocommerce-tabs ul.tabs li a{font-family:var(--font-head);font-size:.82rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#333;padding:10px 18px;display:block;text-decoration:none;}
            .woocommerce div.product .woocommerce-tabs ul.tabs li.active a{color:#fff;}

            /* ---- Cart & Checkout ---- */
            .woocommerce table.shop_table{width:100%;border-collapse:collapse;font-family:var(--font-ui);font-size:.9rem;}
            .woocommerce table.shop_table th{background:#002868;color:#fff;font-family:var(--font-head);font-size:.78rem;letter-spacing:.08em;text-transform:uppercase;padding:12px 16px;}
            .woocommerce table.shop_table td{border-bottom:1px solid #E8E8E8;padding:14px 16px;vertical-align:middle;}
            .woocommerce-cart .wc-proceed-to-checkout a.checkout-button{background:#C8102E;color:#fff;font-family:var(--font-head);font-size:1rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;padding:14px 32px;border-radius:4px;display:block;text-align:center;text-decoration:none;margin-top:12px;}
            .woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover{background:#002868;}

            /* ---- Order/Checkout form ---- */
            .woocommerce form .form-row label{font-family:var(--font-ui);font-size:.85rem;font-weight:600;color:#333;display:block;margin-bottom:4px;}
            .woocommerce form .form-row input.input-text,.woocommerce form .form-row select,.woocommerce form .form-row textarea{width:100%;border:2px solid #E8E8E8;border-radius:4px;padding:10px 14px;font-family:var(--font-ui);font-size:.9rem;transition:border-color .2s;}
            .woocommerce form .form-row input.input-text:focus,.woocommerce form .form-row select:focus{border-color:#002868;outline:none;}

            /* ---- Results count + ordering ---- */
            .woocommerce-result-count{font-family:var(--font-ui);font-size:.85rem;color:#999;margin:0 0 16px;}
            .woocommerce-ordering select{border:2px solid #E8E8E8;border-radius:4px;padding:8px 12px;font-family:var(--font-ui);font-size:.85rem;}

            /* ---- Shop category filter sidebar ---- */
            .widget_product_categories ul{list-style:none;padding:0;}
            .widget_product_categories ul li a{color:#333;font-size:.88rem;padding:5px 0;display:block;border-bottom:1px solid #eee;}
            .widget_product_categories ul li a:hover{color:#C8102E;}

            /* ---- Responsive ---- */
            @media(max-width:768px){
                .woocommerce ul.products{grid-template-columns:repeat(2,1fr);}
                .woocommerce div.product div.summary{padding-left:0;margin-top:20px;}
            }
            @media(max-width:480px){
                .woocommerce ul.products{grid-template-columns:1fr;}
            }
        ' );
    }
}, 20 );

// ============================================================
// [Admin settings page is in inc/admin-settings.php]
// ============================================================

// phpcs:disable -- legacy function kept inside dead-code block so it can be safely deleted later
if ( false ) { function fafo__legacy_settings_page_unused() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    // Handle save
    if ( isset( $_POST['fafo_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fafo_settings_nonce'] ) ), 'fafo_save_settings' ) ) {

        if ( isset( $_POST['fafo_ticker_items'] ) ) {
            update_option( 'fafo_ticker_items', sanitize_textarea_field( wp_unslash( $_POST['fafo_ticker_items'] ) ) );
        }
        if ( isset( $_POST['fafo_alert_text'] ) ) {
            update_option( 'fafo_alert_text', sanitize_text_field( wp_unslash( $_POST['fafo_alert_text'] ) ) );
        }
        if ( isset( $_POST['fafo_header_tagline'] ) ) {
            update_option( 'fafo_header_tagline', sanitize_text_field( wp_unslash( $_POST['fafo_header_tagline'] ) ) );
            // Sync to customizer setting
            set_theme_mod( 'fafo_header_tagline', sanitize_text_field( wp_unslash( $_POST['fafo_header_tagline'] ) ) );
        }
        // Reset setup flag so categories/pages get re-checked
        if ( isset( $_POST['fafo_reset_setup'] ) ) {
            delete_option( 'fafo_setup_complete' );
        }

        echo '<div class="notice notice-success is-dismissible"><p><strong>FAFO Settings saved!</strong></p></div>';
    }

    $ticker_items = get_option( 'fafo_ticker_items', '' );
    $alert_text   = get_option( 'fafo_alert_text', '' );
    $tagline      = get_theme_mod( 'fafo_header_tagline', 'FOR AMERICA FIRST ONLY' );
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span style="color:#C8102E;">&#9873;</span> FAFO Theme Settings
        </h1>
        <p style="color:#666;">Configure your breaking ticker, alert bar, and site branding from one place.</p>

        <form method="post" action="">
            <?php wp_nonce_field( 'fafo_save_settings', 'fafo_settings_nonce' ); ?>

            <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-top:20px;">

                <!-- LEFT COLUMN -->
                <div>

                    <!-- BREAKING NEWS TICKER -->
                    <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:24px;margin-bottom:20px;">
                        <h2 style="margin-top:0;font-size:1.1rem;border-bottom:3px solid #C8102E;padding-bottom:8px;">
                            📡 Breaking News Ticker
                        </h2>
                        <p style="color:#666;font-size:.88rem;margin-bottom:12px;">
                            One headline per line. These scroll across the red ticker bar at the top of the site.
                            Leave blank to use the default placeholder headlines.
                        </p>
                        <textarea name="fafo_ticker_items" rows="10"
                            style="width:100%;font-family:monospace;font-size:.88rem;padding:10px;border:2px solid #ddd;border-radius:4px;resize:vertical;"
                            placeholder="BREAKING: Your headline here&#10;EXCLUSIVE: Another story&#10;FAFO REPORT: Third headline"><?php echo esc_textarea( $ticker_items ); ?></textarea>
                        <p style="color:#888;font-size:.8rem;margin:6px 0 0;">
                            Currently showing <strong><?php echo count( fafo_get_ticker_items() ); ?></strong> headlines.
                            <?php if ( empty( $ticker_items ) ) echo ' <em>(Using built-in defaults — add your own above to override.)</em>'; ?>
                        </p>
                    </div>

                    <!-- ALERT BAR -->
                    <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:24px;margin-bottom:20px;">
                        <h2 style="margin-top:0;font-size:1.1rem;border-bottom:3px solid #002868;padding-bottom:8px;">
                            ⚠️ Alert Bar
                        </h2>
                        <p style="color:#666;font-size:.88rem;margin-bottom:12px;">
                            Displays a full-width alert banner below the main navigation. Leave blank to hide it.
                        </p>
                        <input type="text" name="fafo_alert_text" value="<?php echo esc_attr( $alert_text ); ?>"
                            style="width:100%;padding:10px 14px;border:2px solid #ddd;border-radius:4px;font-size:.95rem;"
                            placeholder="e.g. BREAKING: Site is live! Welcome to FAFO News.">
                        <p style="color:#888;font-size:.8rem;margin:6px 0 0;">
                            <?php if ( $alert_text ) echo '<strong style="color:#C8102E;">Alert is currently visible.</strong>'; else echo 'Alert is currently hidden.'; ?>
                        </p>
                    </div>

                    <!-- BRANDING -->
                    <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:24px;margin-bottom:20px;">
                        <h2 style="margin-top:0;font-size:1.1rem;border-bottom:3px solid #FFD700;padding-bottom:8px;">
                            🦅 Site Branding
                        </h2>
                        <label style="display:block;font-weight:600;margin-bottom:6px;">Header Tagline</label>
                        <input type="text" name="fafo_header_tagline" value="<?php echo esc_attr( $tagline ); ?>"
                            style="width:100%;padding:10px 14px;border:2px solid #ddd;border-radius:4px;font-size:.95rem;"
                            placeholder="FOR AMERICA FIRST ONLY">
                        <p style="color:#888;font-size:.8rem;margin:6px 0 0;">Displayed under the FAFO logo in the site header.</p>
                    </div>

                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <div style="background:#002868;color:#fff;border-radius:4px;padding:20px;margin-bottom:20px;">
                        <h3 style="margin:0 0 10px;color:#FFD700;font-size:1rem;">Quick Links</h3>
                        <ul style="margin:0;padding:0;list-style:none;font-size:.88rem;line-height:2;">
                            <li><a href="<?php echo esc_url( admin_url('customize.php') ); ?>" style="color:#B8D0FF;">&#9998; Full Customizer</a></li>
                            <li><a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>" style="color:#B8D0FF;">&#9776; Navigation Menus</a></li>
                            <li><a href="<?php echo esc_url( admin_url('widgets.php') ); ?>" style="color:#B8D0FF;">&#9724; Sidebar Widgets</a></li>
                            <?php if ( class_exists('WooCommerce') ) : ?>
                            <li><a href="<?php echo esc_url( admin_url('admin.php?page=wc-settings') ); ?>" style="color:#B8D0FF;">&#128722; WooCommerce Settings</a></li>
                            <?php endif; ?>
                            <?php if ( defined('MAILPOET_VERSION') || class_exists('\MailPoet\API\API') ) : ?>
                            <li><a href="<?php echo esc_url( admin_url('admin.php?page=mailpoet-newsletters') ); ?>" style="color:#B8D0FF;">&#128140; MailPoet Newsletters</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div style="background:#fff;border:1px solid #ccd0d4;border-radius:4px;padding:20px;margin-bottom:20px;">
                        <h3 style="margin:0 0 10px;font-size:.95rem;">Plugin Status</h3>
                        <?php
                        $plugins = [
                            'WooCommerce'   => class_exists('WooCommerce'),
                            'Printful'      => class_exists('Printful_Integration') || defined('PRINTFUL_VERSION'),
                            'MailPoet'      => class_exists('\MailPoet\API\API') || defined('MAILPOET_VERSION'),
                            'FluentSMTP'    => defined('FLUENTMAIL') || function_exists('FluentMail'),
                            'Yoast SEO'     => defined('WPSEO_VERSION'),
                            'Jetpack'       => class_exists('Jetpack'),
                        ];
                        foreach ( $plugins as $name => $active ) : ?>
                        <div style="display:flex;align-items:center;gap:8px;padding:5px 0;border-bottom:1px solid #eee;font-size:.85rem;">
                            <span style="color:<?php echo $active ? '#155724' : '#999'; ?>;font-size:1rem;"><?php echo $active ? '✓' : '○'; ?></span>
                            <span style="color:<?php echo $active ? '#333' : '#999'; ?>;"><?php echo esc_html($name); ?></span>
                            <span style="margin-left:auto;font-size:.75rem;color:<?php echo $active ? '#155724' : '#999'; ?>;"><?php echo $active ? 'Active' : 'Inactive'; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:4px;padding:16px;">
                        <h3 style="margin:0 0 8px;font-size:.9rem;color:#856404;">🔄 Re-run Setup</h3>
                        <p style="font-size:.82rem;color:#856404;margin:0 0 10px;">Re-creates any missing categories and pages. Safe to run at any time.</p>
                        <label style="display:flex;align-items:center;gap:8px;font-size:.85rem;cursor:pointer;">
                            <input type="checkbox" name="fafo_reset_setup" value="1"> Trigger setup on next save
                        </label>
                    </div>
                </div>

            </div>

            <p>
                <button type="submit" class="button button-primary button-large" style="background:#C8102E;border-color:#a50d24;font-size:1rem;padding:8px 28px;">
                    💾 Save FAFO Settings
                </button>
            </p>

        </form>
    </div>
    <?php
} } // end if(false) legacy block

// Sync customizer setting reads with our admin page option
add_filter( 'theme_mod_fafo_header_tagline', function( $value ) {
    if ( ! $value ) {
        $opt = get_option( 'fafo_header_tagline', '' );
        if ( $opt ) return $opt;
    }
    return $value;
} );

