<?php
/**
 * Template Name: Advertise
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">ADVERTISE WITH FAFO</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:600px;margin:0 auto;">
        Reach millions of patriotic, engaged American conservatives who trust FAFO News.
    </p>
</div>

<!-- AUDIENCE STATS -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>Our Audience</h2></div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-top:20px;">
        <?php
        $stats = [
            [ 'num' => '2.4M+',   'label' => 'Monthly Unique Visitors',    'icon' => 'fa-users' ],
            [ 'num' => '8.7M+',   'label' => 'Monthly Page Views',          'icon' => 'fa-eye' ],
            [ 'num' => '340K+',   'label' => 'Email Subscribers',           'icon' => 'fa-envelope' ],
            [ 'num' => '94%',     'label' => 'Conservative/Center-Right',   'icon' => 'fa-flag' ],
        ];
        foreach ( $stats as $s ) : ?>
        <div style="text-align:center;background:#F5F5F0;padding:24px 16px;border-radius:8px;border-top:4px solid #C8102E;">
            <i class="fas <?php echo esc_attr($s['icon']); ?>" style="font-size:1.8rem;color:#002868;margin-bottom:10px;display:block;"></i>
            <div style="font-family:var(--font-head);font-size:2rem;font-weight:900;color:#C8102E;line-height:1;"><?php echo esc_html($s['num']); ?></div>
            <div style="font-size:0.8rem;color:#666;margin-top:6px;line-height:1.4;"><?php echo esc_html($s['label']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:20px;">
        <?php
        $demo = [
            [ 'label' => 'Average Age',               'val' => '38-55',   'sub' => 'Peak demographic' ],
            [ 'label' => 'Avg. Household Income',      'val' => '$85K+',   'sub' => 'Above national median' ],
            [ 'label' => 'Avg. Time on Site',          'val' => '4:32',    'sub' => 'Minutes per session' ],
        ];
        foreach ( $demo as $d ) : ?>
        <div style="background:#fff;border:1px solid #E8E8E8;padding:20px;border-radius:6px;text-align:center;">
            <div style="font-family:var(--font-head);font-size:1.6rem;font-weight:900;color:#002868;"><?php echo esc_html($d['val']); ?></div>
            <div style="font-weight:600;font-size:0.88rem;margin:4px 0;"><?php echo esc_html($d['label']); ?></div>
            <div style="font-size:0.78rem;color:#888;"><?php echo esc_html($d['sub']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- AD PACKAGES -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>Advertising Packages</h2></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:20px;">
        <?php
        $packages = [
            [
                'name'  => 'Patriot',
                'price' => '$499/mo',
                'desc'  => 'Perfect for small businesses and local campaigns',
                'color' => '#666',
                'items' => [
                    '1 display ad placement',
                    '300x250 sidebar position',
                    '50,000 impressions/month',
                    'Basic performance report',
                ],
            ],
            [
                'name'  => 'Eagle',
                'price' => '$1,499/mo',
                'desc'  => 'Most popular — ideal for national brands',
                'color' => '#002868',
                'items' => [
                    '3 display ad placements',
                    'Homepage + category pages',
                    '250,000 impressions/month',
                    '1 sponsored article/month',
                    'Weekly performance report',
                    'Social media mention',
                ],
                'featured' => true,
            ],
            [
                'name'  => 'Commander',
                'price' => 'Custom',
                'desc'  => 'Full-spectrum partnership for major brands',
                'color' => '#C8102E',
                'items' => [
                    'Unlimited ad placements',
                    'Homepage takeover options',
                    'Sponsored content series',
                    'Email newsletter placement',
                    'Video pre-roll / mid-roll',
                    'Dedicated account manager',
                    'Custom campaign strategy',
                ],
            ],
        ];
        foreach ( $packages as $pkg ) : ?>
        <div style="background:#fff;border-radius:8px;border:2px solid <?php echo esc_attr( $pkg['featured'] ?? false ? '#002868' : '#E8E8E8' ); ?>;overflow:hidden;<?php echo ( $pkg['featured'] ?? false ) ? 'box-shadow:0 8px 24px rgba(0,40,104,.15);transform:translateY(-4px);' : ''; ?>">
            <?php if ( $pkg['featured'] ?? false ) : ?>
            <div style="background:#002868;color:#FFD700;text-align:center;padding:6px;font-family:var(--font-head);font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;">
                ★ MOST POPULAR ★
            </div>
            <?php endif; ?>
            <div style="padding:28px 24px;">
                <div style="font-family:var(--font-head);font-size:0.75rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:<?php echo esc_attr($pkg['color']); ?>;margin-bottom:6px;"><?php echo esc_html($pkg['name']); ?></div>
                <div style="font-family:var(--font-head);font-size:2rem;font-weight:900;color:#002868;margin-bottom:8px;"><?php echo esc_html($pkg['price']); ?></div>
                <p style="font-size:0.85rem;color:#666;margin:0 0 20px;line-height:1.5;"><?php echo esc_html($pkg['desc']); ?></p>
                <ul style="list-style:none;padding:0;margin:0 0 24px;display:flex;flex-direction:column;gap:8px;">
                    <?php foreach ( $pkg['items'] as $item ) : ?>
                    <li style="font-size:0.85rem;color:#444;display:flex;align-items:flex-start;gap:8px;">
                        <i class="fas fa-check" style="color:#4CAF50;margin-top:2px;flex-shrink:0;"></i>
                        <?php echo esc_html($item); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url( fafo_page_link('contact') ); ?>?dept=Advertising" style="display:block;background:<?php echo esc_attr($pkg['color']); ?>;color:#fff;text-align:center;padding:12px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;">
                    Get Started
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- AD SPECS -->
<section style="background:#F5F5F0;padding:32px;border-radius:8px;margin-bottom:48px;">
    <div class="section-header"><h2>Ad Specifications</h2></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:16px;">
        <div>
            <h4 style="font-family:var(--font-head);font-size:0.85rem;letter-spacing:0.08em;text-transform:uppercase;color:#002868;margin:0 0 12px;">Display Ads</h4>
            <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                <tr style="background:#E8E8E8;"><th style="padding:8px;text-align:left;font-weight:600;">Placement</th><th style="padding:8px;text-align:left;font-weight:600;">Size (px)</th></tr>
                <tr style="border-bottom:1px solid #ddd;"><td style="padding:8px;">Leaderboard</td><td style="padding:8px;">728 × 90</td></tr>
                <tr style="border-bottom:1px solid #ddd;"><td style="padding:8px;">Rectangle</td><td style="padding:8px;">300 × 250</td></tr>
                <tr style="border-bottom:1px solid #ddd;"><td style="padding:8px;">Wide Skyscraper</td><td style="padding:8px;">160 × 600</td></tr>
                <tr><td style="padding:8px;">Mobile Banner</td><td style="padding:8px;">320 × 50</td></tr>
            </table>
        </div>
        <div>
            <h4 style="font-family:var(--font-head);font-size:0.85rem;letter-spacing:0.08em;text-transform:uppercase;color:#002868;margin:0 0 12px;">File Requirements</h4>
            <ul style="list-style:none;padding:0;margin:0;font-size:0.85rem;display:flex;flex-direction:column;gap:8px;">
                <li><strong>Formats:</strong> JPG, PNG, GIF, HTML5</li>
                <li><strong>Max file size:</strong> 150KB (static), 500KB (animated)</li>
                <li><strong>Animation:</strong> Max 15 seconds, max 3 loops</li>
                <li><strong>Accepted:</strong> Third-party ad tags (DFP, DoubleClick)</li>
                <li><strong>Lead time:</strong> 5 business days</li>
            </ul>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section style="text-align:center;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.6rem;font-weight:900;color:#002868;margin:0 0 10px;">READY TO REACH AMERICAN PATRIOTS?</h3>
    <p style="color:#555;margin:0 0 20px;">Contact our advertising team for a custom proposal and media kit.</p>
    <a href="mailto:ads@foramericafirstonly.com" style="display:inline-block;background:#C8102E;color:#fff;padding:14px 36px;font-family:var(--font-head);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;border-radius:4px;margin-right:12px;">
        <i class="fas fa-envelope"></i> Email Advertising Team
    </a>
    <a href="<?php echo esc_url( fafo_page_link('contact') ); ?>" style="display:inline-block;background:#002868;color:#fff;padding:14px 36px;font-family:var(--font-head);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;border-radius:4px;">
        <i class="fas fa-paper-plane"></i> Contact Form
    </a>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-download"></i> Media Kit</h3>
        <div class="widget-body">
            <p style="font-size:0.88rem;color:#555;line-height:1.6;margin:0 0 12px;">Download our full media kit with audience demographics, ad specs, and pricing.</p>
            <a href="#" style="display:block;background:#002868;color:#FFD700;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;">
                <i class="fas fa-file-pdf"></i> Download Media Kit
            </a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
