<?php
/**
 * Template Name: Press Room
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">PRESS ROOM</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;">
        Media resources, press releases, and contact information for journalists covering FAFO News.
    </p>
</div>

<!-- PRESS RELEASES -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>Latest Press Releases</h2></div>
    <?php
    $releases = [
        [
            'date'    => 'March 15, 2025',
            'title'   => 'FAFO News Surpasses 2 Million Monthly Readers, Announces Video Expansion',
            'summary' => 'FAFO News today announced it has surpassed 2 million unique monthly readers, making it one of the fastest-growing conservative news outlets in the United States. The milestone coincides with the launch of FAFO Video, a dedicated platform for exclusive video journalism.',
        ],
        [
            'date'    => 'February 28, 2025',
            'title'   => 'FAFO News Launches Secure Whistleblower Tip Line for Government Accountability Reporting',
            'summary' => 'FAFO News has launched an encrypted, anonymous tip line to protect whistleblowers who expose government corruption, election irregularities, and other matters of public interest.',
        ],
        [
            'date'    => 'January 10, 2025',
            'title'   => 'FAFO News Named Top Conservative News Source by Readers\' Choice Award',
            'summary' => 'For the second consecutive year, FAFO News has been named the #1 most trusted conservative news source by an independent survey of over 50,000 American readers.',
        ],
    ];
    foreach ( $releases as $r ) : ?>
    <article style="border-bottom:1px solid #E8E8E8;padding:20px 0;">
        <div style="font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C8102E;margin-bottom:6px;"><?php echo esc_html($r['date']); ?></div>
        <h3 style="font-family:var(--font-head);font-size:1.15rem;font-weight:700;color:#002868;margin:0 0 8px;"><?php echo esc_html($r['title']); ?></h3>
        <p style="font-size:0.9rem;color:#555;line-height:1.65;margin:0 0 10px;"><?php echo esc_html($r['summary']); ?></p>
        <a href="#" style="font-size:0.82rem;color:#C8102E;font-weight:600;text-decoration:none;">Read Full Release <i class="fas fa-arrow-right"></i></a>
    </article>
    <?php endforeach; ?>
</section>

<!-- MEDIA RESOURCES -->
<section style="background:#F5F5F0;padding:32px;border-radius:8px;margin-bottom:48px;">
    <div class="section-header"><h2>Media Resources</h2></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:20px;">
        <?php
        $resources = [
            [ 'icon' => 'fa-file-image',  'title' => 'Logo Package',     'desc' => 'PNG, SVG, and EPS versions of the FAFO News logo in all color variations.',                    'btn' => 'Download Logos' ],
            [ 'icon' => 'fa-file-pdf',    'title' => 'Media Kit',        'desc' => 'Full audience demographics, reach statistics, editorial standards, and advertising rates.',      'btn' => 'Download Media Kit' ],
            [ 'icon' => 'fa-photo-video', 'title' => 'Brand Guidelines', 'desc' => 'Official brand colors, typography, usage guidelines, and do\'s and don\'ts for media use.',     'btn' => 'Download Guidelines' ],
        ];
        foreach ( $resources as $r ) : ?>
        <div style="background:#fff;border-radius:6px;padding:24px;text-align:center;border:1px solid #E8E8E8;">
            <i class="fas <?php echo esc_attr($r['icon']); ?>" style="font-size:2rem;color:#002868;margin-bottom:12px;display:block;"></i>
            <h4 style="font-family:var(--font-head);font-size:0.9rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#002868;margin:0 0 8px;"><?php echo esc_html($r['title']); ?></h4>
            <p style="font-size:0.83rem;color:#666;line-height:1.5;margin:0 0 14px;"><?php echo esc_html($r['desc']); ?></p>
            <a href="#" style="display:inline-block;background:#C8102E;color:#fff;padding:8px 18px;text-decoration:none;font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;"><?php echo esc_html($r['btn']); ?></a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- PRESS CONTACTS -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>Press Contacts</h2></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:20px;">
        <div style="background:#fff;border:1px solid #E8E8E8;padding:24px;border-radius:6px;">
            <h4 style="font-family:var(--font-head);font-size:0.9rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#002868;margin:0 0 14px;">Media Inquiries</h4>
            <p style="font-size:0.9rem;margin:0 0 6px;"><strong>Name:</strong> Sarah Caldwell, Executive Editor</p>
            <p style="font-size:0.9rem;margin:0 0 6px;"><strong>Email:</strong> press@foramericafirstonly.com</p>
            <p style="font-size:0.9rem;margin:0;"><strong>Response time:</strong> Within 24 hours on business days</p>
        </div>
        <div style="background:#fff;border:1px solid #E8E8E8;padding:24px;border-radius:6px;">
            <h4 style="font-family:var(--font-head);font-size:0.9rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#002868;margin:0 0 14px;">Interview Requests</h4>
            <p style="font-size:0.9rem;margin:0 0 6px;"><strong>Name:</strong> Col. James Harrington, Editor-in-Chief</p>
            <p style="font-size:0.9rem;margin:0 0 6px;"><strong>Email:</strong> interviews@foramericafirstonly.com</p>
            <p style="font-size:0.9rem;margin:0;"><strong>Availability:</strong> By appointment, 48-hour advance notice</p>
        </div>
    </div>
</section>

<!-- IN THE NEWS -->
<section style="margin-bottom:40px;">
    <div class="section-header"><h2>FAFO In the News</h2></div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-top:20px;align-items:center;">
        <?php for ( $i = 0; $i < 6; $i++ ) : ?>
        <div style="background:#F5F5F0;border-radius:6px;padding:20px;text-align:center;height:60px;display:flex;align-items:center;justify-content:center;">
            <span style="font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#aaa;">AS SEEN IN</span>
        </div>
        <?php endfor; ?>
    </div>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-envelope"></i> Press Contact</h3>
        <div class="widget-body">
            <p style="font-size:0.88rem;margin:0 0 6px;"><strong>Email:</strong> press@foramericafirstonly.com</p>
            <p style="font-size:0.88rem;color:#666;margin:0 0 12px;">For media inquiries only. Please include your publication and deadline.</p>
            <a href="<?php echo esc_url( fafo_page_link('contact') ); ?>" style="display:block;background:#C8102E;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;">Contact Form</a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
