<?php
/**
 * FAFO Theme – Comprehensive Admin Settings Page
 * Every piece of static/configurable content in the theme is editable here.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// ENQUEUE color-picker + media uploader on our settings page
// ============================================================
add_action( 'admin_enqueue_scripts', function( $hook ) {
    if ( $hook !== 'toplevel_page_fafo-settings' ) return;
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_media();
    wp_add_inline_script( 'wp-color-picker', '
    jQuery(function($){
        $(".fafo-color").wpColorPicker();
        $(document).on("click",".fafo-media-btn",function(e){
            e.preventDefault();
            var btn=$(this), inp=btn.data("input"), prev=btn.data("preview");
            var frame=wp.media({title:"Select Image",button:{text:"Use This Image"},multiple:false});
            frame.on("select",function(){
                var a=frame.state().get("selection").first().toJSON();
                $("#"+inp).val(a.id);
                if(prev){$("#"+prev).attr("src",a.url).show();}
            });
            frame.open();
        });
        $(document).on("click",".fafo-media-clear",function(e){
            e.preventDefault();
            var btn=$(this);
            $("#"+btn.data("input")).val("");
            if(btn.data("preview")){$("#"+btn.data("preview")).hide();}
        });
    });
    ' );
} );

// ============================================================
// REGISTER ADMIN MENU
// ============================================================
add_action( 'admin_menu', function() {
    add_menu_page(
        __( 'FAFO Settings', 'fafo' ),
        __( 'FAFO Settings', 'fafo' ),
        'manage_options',
        'fafo-settings',
        'fafo_admin_settings_page',
        'dashicons-flag',
        3
    );
} );

// ============================================================
// SAVE HANDLER
// ============================================================
function fafo_save_all_settings( $post ) {
    // Branding & Colors
    $color_fields = [ 'fafo_color_red', 'fafo_color_navy', 'fafo_color_gold',
                      'fafo_color_darkred', 'fafo_color_darknavy' ];
    foreach ( $color_fields as $f ) {
        if ( isset( $post[ $f ] ) ) {
            $val = sanitize_hex_color( $post[ $f ] );
            if ( $val ) update_option( $f, $val ); else delete_option( $f );
        }
    }

    $text_fields = [
        'fafo_header_tagline', 'fafo_alert_text',
        // About widget
        'fafo_about_widget_title', 'fafo_about_widget_text',
        // Newsletter widget
        'fafo_newsletter_heading', 'fafo_newsletter_subtext', 'fafo_newsletter_btn_text',
        // Patriot quote banner (footer)
        'fafo_patriot_quote_badge', 'fafo_patriot_quote_text',
        // Homepage section titles
        'fafo_homepage_section1_title', 'fafo_homepage_section2_title',
        // Footer text
        'fafo_footer_description', 'fafo_copyright_text',
        // Social
        'fafo_social_twitter', 'fafo_social_facebook', 'fafo_social_truth',
        'fafo_social_rumble', 'fafo_social_telegram', 'fafo_social_youtube',
        // Contact
        'fafo_contact_newsroom', 'fafo_contact_tips', 'fafo_contact_advertise',
        'fafo_contact_legal',   'fafo_contact_careers', 'fafo_contact_press',
    ];
    foreach ( $text_fields as $f ) {
        if ( isset( $post[ $f ] ) ) {
            update_option( $f, sanitize_text_field( wp_unslash( $post[ $f ] ) ) );
            // Keep theme_mod in sync for tagline
            if ( $f === 'fafo_header_tagline' ) {
                set_theme_mod( 'fafo_header_tagline', sanitize_text_field( wp_unslash( $post[ $f ] ) ) );
            }
        }
    }

    $textarea_fields = [ 'fafo_ticker_items' ];
    foreach ( $textarea_fields as $f ) {
        if ( isset( $post[ $f ] ) ) {
            update_option( $f, sanitize_textarea_field( wp_unslash( $post[ $f ] ) ) );
        }
    }

    // Image attachment IDs
    $image_fields = [ 'fafo_footer_logo', 'fafo_og_default_image', 'fafo_about_widget_image' ];
    foreach ( $image_fields as $f ) {
        if ( isset( $post[ $f ] ) ) {
            update_option( $f, absint( $post[ $f ] ) );
        }
    }

    // Re-run setup
    if ( ! empty( $post['fafo_reset_setup'] ) ) {
        delete_option( 'fafo_setup_complete' );
        delete_option( 'fafo_content_populated' );
    }
}

// ============================================================
// MAIN SETTINGS PAGE
// ============================================================
function fafo_admin_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $tab     = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'branding';
    $base    = admin_url( 'admin.php?page=fafo-settings' );
    $saved   = false;

    if ( isset( $_POST['fafo_settings_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fafo_settings_nonce'] ) ), 'fafo_save_settings' ) ) {
        fafo_save_all_settings( $_POST );
        $saved = true;
    }

    $tabs = [
        'branding' => '🎨 Branding',
        'colors'   => '🖌 Colors',
        'images'   => '🖼 Images',
        'social'   => '📱 Social',
        'contact'  => '✉️ Contact',
        'header'   => '📡 Header',
        'footer'   => '🦶 Footer',
        'system'   => '⚙️ System',
    ];
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span style="color:#C8102E;font-size:1.8rem;">⚑</span> FAFO Theme Settings
        </h1>
        <?php if ( $saved ) : ?>
        <div class="notice notice-success is-dismissible"><p><strong>✓ Settings saved!</strong></p></div>
        <?php endif; ?>

        <nav class="nav-tab-wrapper">
            <?php foreach ( $tabs as $t => $label ) : ?>
            <a href="<?php echo esc_url( $base . '&tab=' . $t ); ?>"
               class="nav-tab<?php echo ( $tab === $t ) ? ' nav-tab-active' : ''; ?>">
                <?php echo esc_html( $label ); ?>
            </a>
            <?php endforeach; ?>
        </nav>

        <form method="post" action="<?php echo esc_url( $base . '&tab=' . esc_attr( $tab ) ); ?>"
              style="background:#fff;border:1px solid #ccd0d4;border-top:none;padding:28px 24px;border-radius:0 0 4px 4px;">
            <?php wp_nonce_field( 'fafo_save_settings', 'fafo_settings_nonce' ); ?>

            <?php
            switch ( $tab ) {
                case 'branding': _fafo_tab_branding(); break;
                case 'colors':   _fafo_tab_colors();   break;
                case 'images':   _fafo_tab_images();   break;
                case 'social':   _fafo_tab_social();   break;
                case 'contact':  _fafo_tab_contact();  break;
                case 'header':   _fafo_tab_header();   break;
                case 'footer':   _fafo_tab_footer();   break;
                case 'system':   _fafo_tab_system();   break;
            }
            ?>

            <p style="margin-top:28px;padding-top:16px;border-top:2px solid #eee;">
                <button type="submit" class="button button-primary button-large"
                        style="background:#C8102E;border-color:#a50d24;padding:8px 28px;font-size:1rem;">
                    💾 Save Changes
                </button>
            </p>
        </form>
    </div>
    <?php
}

// ── Shared UI helpers ──────────────────────────────────────
function _fafo_field( $label, $name, $value, $type = 'text', $placeholder = '', $desc = '' ) {
    ?>
    <tr>
        <th scope="row" style="width:220px;"><label for="<?php echo esc_attr($name); ?>"><?php echo esc_html($label); ?></label></th>
        <td>
            <?php if ( $type === 'textarea' ) : ?>
                <textarea id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
                    rows="5" style="width:100%;max-width:600px;"
                    placeholder="<?php echo esc_attr($placeholder); ?>"><?php echo esc_textarea($value); ?></textarea>
            <?php elseif ( $type === 'color' ) : ?>
                <input type="text" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
                    value="<?php echo esc_attr($value); ?>" class="fafo-color"
                    data-default-color="<?php echo esc_attr($placeholder); ?>">
            <?php else : ?>
                <input type="<?php echo esc_attr($type); ?>" id="<?php echo esc_attr($name); ?>"
                    name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>"
                    class="regular-text" placeholder="<?php echo esc_attr($placeholder); ?>">
            <?php endif; ?>
            <?php if ( $desc ) : ?>
            <p class="description"><?php echo wp_kses_post($desc); ?></p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}

function _fafo_image_field( $label, $name, $desc = '' ) {
    $id  = get_option( $name );
    $src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
    $uid = esc_attr( $name . '_preview' );
    ?>
    <tr>
        <th scope="row"><label><?php echo esc_html($label); ?></label></th>
        <td>
            <?php if ( $src ) : ?>
            <img id="<?php echo $uid; ?>" src="<?php echo esc_url($src); ?>"
                 style="max-height:80px;max-width:300px;display:block;margin-bottom:8px;border-radius:4px;border:1px solid #ddd;">
            <?php else : ?>
            <img id="<?php echo $uid; ?>" src="" style="display:none;max-height:80px;max-width:300px;margin-bottom:8px;border-radius:4px;border:1px solid #ddd;">
            <?php endif; ?>
            <input type="hidden" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($id); ?>">
            <button type="button" class="button fafo-media-btn"
                    data-input="<?php echo esc_attr($name); ?>"
                    data-preview="<?php echo $uid; ?>">
                <?php echo $src ? '↩ Replace Image' : '⬆ Upload Image'; ?>
            </button>
            <?php if ( $src ) : ?>
            <button type="button" class="button fafo-media-clear"
                    data-input="<?php echo esc_attr($name); ?>"
                    data-preview="<?php echo $uid; ?>" style="margin-left:6px;">✕ Remove</button>
            <?php endif; ?>
            <?php if ( $desc ) : ?><p class="description"><?php echo wp_kses_post($desc); ?></p><?php endif; ?>
        </td>
    </tr>
    <?php
}

function _fafo_section( $title, $desc = '' ) {
    echo '<h2 style="margin:0 0 4px;font-size:1.15rem;border-bottom:3px solid #002868;padding-bottom:8px;">' . esc_html($title) . '</h2>';
    if ( $desc ) echo '<p style="color:#666;font-size:.88rem;margin:4px 0 16px;">' . esc_html($desc) . '</p>';
    echo '<table class="form-table" style="margin-bottom:28px;">';
}
function _fafo_endsection() { echo '</table>'; }

// ── TAB: Branding ──────────────────────────────────────────
function _fafo_tab_branding() {
    _fafo_section( 'Site Identity', 'Core text and copy displayed throughout the site.' );
    _fafo_field( 'Header Tagline',         'fafo_header_tagline',       get_option('fafo_header_tagline', get_theme_mod('fafo_header_tagline','FOR AMERICA FIRST ONLY')), 'text', 'FOR AMERICA FIRST ONLY', 'Displayed next to the logo in the site header.' );
    _fafo_endsection();

    _fafo_section( '"About FAFO" Sidebar Widget', 'Appears in the sidebar on every page — homepage, articles, category pages, etc. Image is set in the Images tab.' );
    _fafo_field( 'Widget Title',    'fafo_about_widget_title', get_option('fafo_about_widget_title','About FAFO'),  'text', 'About FAFO',  'The widget heading (e.g. "About FAFO").' );
    _fafo_field( 'Widget Body Text','fafo_about_widget_text',  get_option('fafo_about_widget_text', 'For America First Only — Your #1 source for bold, unapologetic conservative news and commentary. No spin. No agenda. Just the truth.'), 'textarea', 'For America First Only...', 'Description shown below the logo/image in the About widget.' );
    _fafo_endsection();

    _fafo_section( '"Stay Informed" Newsletter Widget', 'Sidebar newsletter signup box on the homepage and article pages.' );
    _fafo_field( 'Widget Heading',       'fafo_newsletter_heading',   get_option('fafo_newsletter_heading',   'JOIN THE MOVEMENT'), 'text', 'JOIN THE MOVEMENT', 'Bold headline inside the newsletter widget.' );
    _fafo_field( 'Widget Description',   'fafo_newsletter_subtext',   get_option('fafo_newsletter_subtext',   'Get FAFO breaking news delivered straight to your inbox. No censorship.'), 'textarea', 'Get FAFO breaking news...', 'Short description line beneath the heading.' );
    _fafo_field( 'Subscribe Button Text','fafo_newsletter_btn_text',  get_option('fafo_newsletter_btn_text',  'SUBSCRIBE FREE'), 'text', 'SUBSCRIBE FREE', 'Text on the subscribe button.' );
    _fafo_endsection();

    _fafo_section( 'Patriot Quote Banner', 'The decorative quote strip displayed above the footer on every page.' );
    _fafo_field( 'Banner Badge Text', 'fafo_patriot_quote_badge', get_option('fafo_patriot_quote_badge','★ FAFO NEWS ★'), 'text', '★ FAFO NEWS ★', 'Small heading/badge above the quote.' );
    _fafo_field( 'Quote Text',        'fafo_patriot_quote_text',  get_option('fafo_patriot_quote_text', '"The tree of liberty must be refreshed from time to time with the truth." — America First'), 'textarea', '"The tree of liberty..."', 'The quote displayed in the banner.' );
    _fafo_endsection();

    _fafo_section( 'Homepage Section Titles', 'Headings for the content sections on the homepage.' );
    _fafo_field( 'Section 1 Title (Latest)', 'fafo_homepage_section1_title', get_option('fafo_homepage_section1_title','Latest Stories'),      'text', 'Latest Stories' );
    _fafo_field( 'Section 2 Title (Opinion)','fafo_homepage_section2_title', get_option('fafo_homepage_section2_title','Opinion & Analysis'),   'text', 'Opinion & Analysis' );
    _fafo_endsection();

    _fafo_section( 'Footer Text', 'Text in the footer brand column and copyright bar.' );
    _fafo_field( 'Footer Description', 'fafo_footer_description', get_option('fafo_footer_description','FAFO News delivers bold, unapologetic conservative reporting for American patriots.'), 'textarea', '', 'Paragraph in the footer brand column.' );
    _fafo_field( 'Copyright Line',     'fafo_copyright_text',     get_option('fafo_copyright_text',''), 'text', 'FAFO News — For America First Only. All Rights Reserved.', 'Leave blank for auto-generated year + site name.' );
    _fafo_endsection();
}

// ── TAB: Colors ────────────────────────────────────────────
function _fafo_tab_colors() {
    echo '<p style="background:#FFF3CD;border:1px solid #ffc107;padding:12px 16px;border-radius:4px;margin-bottom:20px;">
        Color changes override the theme defaults site-wide via CSS variables. Clear a field to revert to the default.
    </p>';
    _fafo_section( 'Brand Colors', 'Click a swatch to open the color picker.' );
    _fafo_field( 'Primary Red',   'fafo_color_red',      get_option('fafo_color_red',''),      'color', '#C8102E' );
    _fafo_field( 'Dark Red',      'fafo_color_darkred',  get_option('fafo_color_darkred',''),  'color', '#8B0000' );
    _fafo_field( 'Navy Blue',     'fafo_color_navy',     get_option('fafo_color_navy',''),     'color', '#002868' );
    _fafo_field( 'Dark Navy',     'fafo_color_darknavy', get_option('fafo_color_darknavy',''), 'color', '#001540' );
    _fafo_field( 'Gold / Accent', 'fafo_color_gold',     get_option('fafo_color_gold',''),     'color', '#FFD700' );
    _fafo_endsection();
    echo '<p class="description" style="margin-top:-20px;">
        Defaults: Red <code>#C8102E</code> · Navy <code>#002868</code> · Gold <code>#FFD700</code>
    </p>';
}

// ── TAB: Images ────────────────────────────────────────────
function _fafo_tab_images() {
    echo '<p style="color:#444;margin-bottom:20px;">
        The <strong>header logo</strong> is set via
        <a href="' . esc_url(admin_url('customize.php?autofocus[control]=custom_logo')) . '">Appearance → Customize → Site Identity</a>.
        All other image slots are managed here.
    </p>';
    _fafo_section( 'Sidebar Images' );
    _fafo_image_field( 'About FAFO Widget Image', 'fafo_about_widget_image', 'Image displayed inside the "About FAFO" sidebar widget on every page. Falls back to the styled FAFO text graphic if not set.' );
    _fafo_endsection();
    _fafo_section( 'Footer &amp; Global Images' );
    _fafo_image_field( 'Footer Logo',              'fafo_footer_logo',       'Shown in the footer brand column. Falls back to the text FAFO logo if not set.' );
    _fafo_image_field( 'Default OG / Share Image', 'fafo_og_default_image',  'Used as the Open Graph image on pages that have no featured image.' );
    _fafo_endsection();
}

// ── TAB: Social Media ──────────────────────────────────────
function _fafo_tab_social() {
    _fafo_section( 'Social Media Links', 'These URLs are used in the top-bar, site header, and footer. Enter the full URL or leave blank to hide the link.' );
    _fafo_field( 'Twitter / X',    'fafo_social_twitter',  get_option('fafo_social_twitter','#'),  'url', 'https://x.com/yourhandle' );
    _fafo_field( 'Facebook',       'fafo_social_facebook', get_option('fafo_social_facebook','#'), 'url', 'https://facebook.com/yourpage' );
    _fafo_field( 'Truth Social',   'fafo_social_truth',    get_option('fafo_social_truth','#'),    'url', 'https://truthsocial.com/@yourhandle' );
    _fafo_field( 'Rumble',         'fafo_social_rumble',   get_option('fafo_social_rumble','#'),   'url', 'https://rumble.com/c/yourchannel' );
    _fafo_field( 'Telegram',       'fafo_social_telegram', get_option('fafo_social_telegram','#'), 'url', 'https://t.me/yourchannel' );
    _fafo_field( 'YouTube',        'fafo_social_youtube',  get_option('fafo_social_youtube',''),   'url', 'https://youtube.com/@yourchannel' );
    _fafo_endsection();
}

// ── TAB: Contact ───────────────────────────────────────────
function _fafo_tab_contact() {
    $d = 'foramericafirstonly.com';
    _fafo_section( 'Contact Email Addresses', 'Used on the Contact page, Tip Line, Careers, and email links throughout the site.' );
    _fafo_field( 'Newsroom / General', 'fafo_contact_newsroom',  get_option('fafo_contact_newsroom',  "news@{$d}"),      'email', "news@{$d}" );
    _fafo_field( 'News Tips',          'fafo_contact_tips',      get_option('fafo_contact_tips',      "tips@{$d}"),      'email', "tips@{$d}" );
    _fafo_field( 'Advertising',        'fafo_contact_advertise', get_option('fafo_contact_advertise', "advertise@{$d}"), 'email', "advertise@{$d}" );
    _fafo_field( 'Legal / DMCA',       'fafo_contact_legal',     get_option('fafo_contact_legal',     "legal@{$d}"),     'email', "legal@{$d}" );
    _fafo_field( 'Careers',            'fafo_contact_careers',   get_option('fafo_contact_careers',   "careers@{$d}"),   'email', "careers@{$d}" );
    _fafo_field( 'Press / Media',      'fafo_contact_press',     get_option('fafo_contact_press',     "press@{$d}"),     'email', "press@{$d}" );
    _fafo_endsection();
}

// ── TAB: Header ────────────────────────────────────────────
function _fafo_tab_header() {
    _fafo_section( 'Alert Bar', 'Full-width alert banner displayed below the navigation. Leave blank to hide.' );
    _fafo_field( 'Alert Text', 'fafo_alert_text', get_option('fafo_alert_text', get_theme_mod('fafo_alert_text','')), 'text', 'e.g. BREAKING: Site is live! Welcome to FAFO News.' );
    _fafo_endsection();

    _fafo_section( 'Breaking News Ticker', 'Scrolling headlines in the red ticker bar. One headline per line.' );
    _fafo_field( 'Ticker Headlines', 'fafo_ticker_items', get_option('fafo_ticker_items',''), 'textarea',
        "BREAKING: Your headline here\nEXCLUSIVE: Another story",
        'Leave blank to use the built-in placeholder headlines (' . count( fafo_get_ticker_items() ) . ' currently showing).' );
    _fafo_endsection();
}

// ── TAB: Footer ────────────────────────────────────────────
function _fafo_tab_footer() {
    echo '<p style="color:#444;background:#f0f6ff;border:1px solid #c5d8ff;padding:12px 16px;border-radius:4px;margin-bottom:20px;">
        Footer text (description &amp; copyright) is in the <strong>Branding</strong> tab.<br>
        Footer logo image is in the <strong>Images</strong> tab.
    </p>';

    _fafo_section( 'Footer Navigation Menus', 'Use <a href="' . esc_url(admin_url('nav-menus.php')) . '">Appearance → Menus</a> to edit the footer link columns. Widget areas are managed in <a href="' . esc_url(admin_url('widgets.php')) . '">Appearance → Widgets</a>.' );
    _fafo_endsection();
}

// ── TAB: System ────────────────────────────────────────────
function _fafo_tab_system() {
    $plugins = [
        'WooCommerce'  => class_exists('WooCommerce'),
        'Printful'     => class_exists('Printful_Integration') || defined('PRINTFUL_VERSION'),
        'MailPoet'     => class_exists('\MailPoet\API\API') || defined('MAILPOET_VERSION'),
        'FluentSMTP'   => defined('FLUENTMAIL') || function_exists('FluentMail'),
        'Yoast SEO'    => defined('WPSEO_VERSION'),
        'Jetpack'      => class_exists('Jetpack'),
    ];
    ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

        <!-- Plugin Status -->
        <div style="background:#f9f9f9;border:1px solid #ddd;border-radius:4px;padding:20px;">
            <h3 style="margin:0 0 14px;font-size:1rem;">Plugin Status</h3>
            <?php foreach ( $plugins as $name => $active ) : ?>
            <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid #eee;font-size:.9rem;">
                <span style="color:<?php echo $active?'#155724':'#999'; ?>;font-size:1.1rem;"><?php echo $active?'✓':'○'; ?></span>
                <span><?php echo esc_html($name); ?></span>
                <span style="margin-left:auto;font-size:.78rem;color:<?php echo $active?'#155724':'#999'; ?>;"><?php echo $active?'Active':'Inactive'; ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Quick Links + Tools -->
        <div>
            <div style="background:#002868;color:#fff;border-radius:4px;padding:20px;margin-bottom:16px;">
                <h3 style="margin:0 0 12px;color:#FFD700;font-size:1rem;">Quick Links</h3>
                <ul style="margin:0;padding:0;list-style:none;font-size:.88rem;line-height:2.2;">
                    <li><a href="<?php echo esc_url(admin_url('customize.php?autofocus[control]=custom_logo')); ?>" style="color:#B8D0FF;">🖼 Upload Header Logo</a></li>
                    <li><a href="<?php echo esc_url(admin_url('customize.php')); ?>" style="color:#B8D0FF;">⚙ Full Customizer</a></li>
                    <li><a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" style="color:#B8D0FF;">☰ Navigation Menus</a></li>
                    <li><a href="<?php echo esc_url(admin_url('widgets.php')); ?>" style="color:#B8D0FF;">▦ Sidebar Widgets</a></li>
                    <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=fafo_team')); ?>" style="color:#B8D0FF;">👥 Team Members</a></li>
                    <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=fafo_job')); ?>" style="color:#B8D0FF;">💼 Job Listings</a></li>
                    <?php if (class_exists('WooCommerce')) : ?>
                    <li><a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings')); ?>" style="color:#B8D0FF;">🛒 WooCommerce Settings</a></li>
                    <?php endif; ?>
                    <?php if (class_exists('\MailPoet\API\API') || defined('MAILPOET_VERSION')) : ?>
                    <li><a href="<?php echo esc_url(admin_url('admin.php?page=mailpoet-newsletters')); ?>" style="color:#B8D0FF;">✉ MailPoet Newsletters</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:4px;padding:16px;">
                <h3 style="margin:0 0 8px;font-size:.95rem;color:#856404;">🔄 Re-run Setup</h3>
                <p style="font-size:.82rem;color:#856404;margin:0 0 10px;">
                    Re-creates missing categories, pages, and pre-populates page content from defaults. Safe to run at any time — only fills in content that is currently empty.
                </p>
                <label style="display:flex;align-items:center;gap:8px;font-size:.88rem;cursor:pointer;">
                    <input type="checkbox" name="fafo_reset_setup" value="1">
                    Trigger setup on next save
                </label>
            </div>
        </div>
    </div>
    <?php
}
