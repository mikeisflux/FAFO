<?php
/**
 * Template Name: Cookie Policy
 */
get_header(); ?>
<main class="site-content" id="main" role="main">
<div class="container"><div class="content-area"><div class="main-content">
<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.2rem;font-weight:900;color:#FFD700;margin:0 0 8px;">COOKIE POLICY</h1>
    <p style="color:rgba(255,255,255,0.7);font-size:0.9rem;margin:0;">Last Updated: January 1, 2025</p>
</div>
<?php
$sections = [
    'What Are Cookies?' => 'Cookies are small text files that are stored on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently, as well as to provide information to the owners of the site.',
    'How We Use Cookies' => 'FAFO News uses cookies for the following purposes:

Essential Cookies: These are necessary for the Site to function properly. They enable core functionality such as page navigation and access to secure areas. The Site cannot function without these cookies.

Performance Cookies: These collect information about how visitors use the Site, such as which pages they visit most often and whether they receive error messages. All information collected is aggregated and anonymous.

Functionality Cookies: These allow the Site to remember choices you make (such as your username or language preference) and provide enhanced, personalized features.

Targeting Cookies: These cookies may be set through our Site by advertising partners. They may be used by those companies to build a profile of your interests and show you relevant ads on other sites.',
    'Specific Cookies We Use' => 'WordPress Session: Manages your logged-in session if you have an account
Comment Author: Remembers your name/email if you post comments
Analytics (Google Analytics): Tracks page views, session duration, and user behavior anonymously
Newsletter Preferences: Remembers your newsletter subscription preferences',
    'Third-Party Cookies' => 'Some cookies are placed by third-party services that appear on our pages, including Google Analytics, social media sharing buttons, and embedded video players (YouTube, Rumble). These third parties have their own privacy and cookie policies.',
    'Managing Cookies' => 'You can control and manage cookies in various ways:

Browser Settings: Most browsers allow you to refuse or delete cookies. Doing so may affect the functionality of this website. Consult your browser\'s help documentation for instructions.

Opt-Out Tools: For Google Analytics opt-out, visit: tools.google.com/dlpage/gaoptout

Do Not Track: We respect Do Not Track browser settings where technically feasible.',
    'Contact' => 'Questions about our Cookie Policy: privacy@foramericafirstonly.com',
];
foreach ( $sections as $title => $content ) : ?>
<section style="margin-bottom:32px;padding-bottom:32px;border-bottom:1px solid #E8E8E8;">
    <h2 style="font-family:var(--font-head);font-size:1.15rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;margin:0 0 14px;"><?php echo esc_html($title); ?></h2>
    <div style="font-size:0.92rem;color:#444;line-height:1.8;white-space:pre-line;"><?php echo esc_html($content); ?></div>
</section>
<?php endforeach; ?>
</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-gavel"></i> Legal</h3>
        <div class="widget-body">
            <ul style="list-style:none;padding:0;margin:0;font-size:0.88rem;">
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('privacy-policy') ); ?>">Privacy Policy</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('terms-of-service') ); ?>">Terms of Service</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('cookie-policy') ); ?>" style="color:#002868;font-weight:600;">Cookie Policy</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('corrections') ); ?>">Corrections Policy</a></li>
                <li style="padding:6px 0;"><a href="<?php echo esc_url( fafo_page_link('dmca') ); ?>">DMCA / Takedowns</a></li>
            </ul>
        </div>
    </div>
</aside>
</div></div></div>
</main>
<?php get_footer(); ?>
