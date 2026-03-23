<?php
/**
 * Template Name: Careers
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">JOIN THE FAFO TEAM</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;">
        Work with patriots who put America First. No censorship. No agenda. Just truth.
    </p>
</div>

<section style="margin-bottom:36px;">
    <p style="font-size:1.05rem;line-height:1.8;color:#333;">
        FAFO News is one of the fastest-growing conservative media outlets in America, and we're always looking for bold, fearless talent who refuses to be silenced. We offer competitive compensation, editorial freedom, and the satisfaction of doing journalism that actually matters.
    </p>
</section>

<?php
$jobs = [
    [
        'title'    => 'Senior Political Reporter',
        'type'     => 'Full-Time',
        'location' => 'Remote / Washington D.C.',
        'dept'     => 'Editorial',
        'desc'     => 'We are seeking an experienced political reporter to cover Congress, the White House, and the ongoing battle for America\'s future. You will have direct access to top conservative leaders and be expected to break stories that the establishment media buries.',
        'reqs'     => [
            '5+ years political reporting experience',
            'Deep source network in Washington D.C.',
            'Ability to write quickly and accurately under deadline',
            'Thorough understanding of conservative policy priorities',
            'Experience with AP Style',
        ],
    ],
    [
        'title'    => 'Video Producer / Editor',
        'type'     => 'Full-Time',
        'location' => 'Remote',
        'dept'     => 'Video & Multimedia',
        'desc'     => 'FAFO Video is expanding rapidly. We need a skilled producer/editor to create compelling video content, from breaking news clips to long-form investigations. You\'ll work with our reporters in the field and edit footage from across the country.',
        'reqs'     => [
            '3+ years video production experience',
            'Proficiency in Adobe Premiere Pro or Final Cut',
            'Experience with news or political content preferred',
            'Motion graphics skills a plus',
            'Reliable high-speed internet connection',
        ],
    ],
    [
        'title'    => 'Border & Immigration Correspondent',
        'type'     => 'Full-Time',
        'location' => 'Texas / Arizona (Border Region)',
        'dept'     => 'Editorial',
        'desc'     => 'The southern border is the biggest story in America. We need a fearless journalist willing to embed on the front lines and report what the legacy media refuses to show. This is a demanding, high-impact role.',
        'reqs'     => [
            '3+ years journalism experience',
            'Willingness to work in challenging field conditions',
            'Spanish language skills strongly preferred',
            'Understanding of immigration law and enforcement',
            'Own reliable vehicle required',
        ],
    ],
    [
        'title'    => 'Opinion Columnist (Contributing)',
        'type'     => 'Part-Time / Contributing',
        'location' => 'Remote',
        'dept'     => 'Opinion',
        'desc'     => 'FAFO publishes bold, intelligent conservative commentary. We are seeking contributing opinion columnists to submit 2-4 pieces per month on politics, culture, economics, or any topic where you have expertise and something important to say.',
        'reqs'     => [
            'Strong writing and argumentation skills',
            'Unique conservative perspective or area of expertise',
            'Ability to meet deadlines consistently',
            'Prior published work preferred',
        ],
    ],
    [
        'title'    => 'Web Developer (WordPress)',
        'type'     => 'Part-Time / Contract',
        'location' => 'Remote',
        'dept'     => 'Technology',
        'desc'     => 'We need a skilled WordPress developer to maintain and improve FAFO News as we grow. This includes theme development, plugin management, performance optimization, and ensuring the site stays online and secure.',
        'reqs'     => [
            '3+ years WordPress development',
            'Proficiency in PHP, HTML, CSS, JavaScript',
            'Experience with high-traffic news sites preferred',
            'Security best practices knowledge',
            'Hosting and server management experience a plus',
        ],
    ],
];

foreach ( $jobs as $job ) : ?>
<article style="background:#fff;border:1px solid #E8E8E8;border-radius:8px;padding:28px;margin-bottom:24px;border-left:5px solid #C8102E;">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;flex-wrap:wrap;gap:10px;">
        <h3 style="font-family:var(--font-head);font-size:1.2rem;font-weight:700;color:#002868;margin:0;"><?php echo esc_html($job['title']); ?></h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <span style="background:#002868;color:#fff;font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:4px 10px;border-radius:20px;"><?php echo esc_html($job['type']); ?></span>
            <span style="background:#F5F5F0;color:#555;font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:20px;"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($job['location']); ?></span>
            <span style="background:#C8102E;color:#fff;font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:4px 10px;border-radius:20px;"><?php echo esc_html($job['dept']); ?></span>
        </div>
    </div>
    <p style="font-size:0.9rem;color:#444;line-height:1.7;margin:0 0 14px;"><?php echo esc_html($job['desc']); ?></p>
    <h5 style="font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#666;margin:0 0 10px;">Requirements</h5>
    <ul style="font-size:0.85rem;color:#555;line-height:1.6;margin:0 0 18px;padding-left:20px;">
        <?php foreach ( $job['reqs'] as $req ) : ?>
        <li><?php echo esc_html($req); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="mailto:careers@foramericafirstonly.com?subject=Application: <?php echo esc_attr($job['title']); ?>" style="display:inline-block;background:#C8102E;color:#fff;padding:10px 24px;text-decoration:none;font-family:var(--font-head);font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;">
        <i class="fas fa-paper-plane"></i> Apply Now
    </a>
</article>
<?php endforeach; ?>

<!-- GENERAL APPLICATION -->
<section style="background:linear-gradient(135deg,#002868,#001540);padding:36px;border-radius:8px;text-align:center;color:#fff;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.5rem;font-weight:900;color:#FFD700;margin:0 0 10px;">DON'T SEE YOUR ROLE?</h3>
    <p style="opacity:0.85;margin:0 0 20px;">We're always interested in talented patriots. Send us your resume and tell us what you can do.</p>
    <a href="mailto:careers@foramericafirstonly.com" style="display:inline-block;background:#C8102E;color:#fff;padding:12px 28px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;border-radius:4px;">
        <i class="fas fa-envelope"></i> Send General Application
    </a>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-briefcase"></i> Open Positions</h3>
        <div class="widget-body">
            <ul style="list-style:none;padding:0;margin:0;font-size:0.88rem;">
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><i class="fas fa-circle" style="font-size:6px;color:#C8102E;margin-right:8px;vertical-align:middle;"></i>Senior Political Reporter</li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><i class="fas fa-circle" style="font-size:6px;color:#C8102E;margin-right:8px;vertical-align:middle;"></i>Video Producer / Editor</li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><i class="fas fa-circle" style="font-size:6px;color:#C8102E;margin-right:8px;vertical-align:middle;"></i>Border Correspondent</li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><i class="fas fa-circle" style="font-size:6px;color:#C8102E;margin-right:8px;vertical-align:middle;"></i>Opinion Columnist</li>
                <li style="padding:6px 0;"><i class="fas fa-circle" style="font-size:6px;color:#C8102E;margin-right:8px;vertical-align:middle;"></i>WordPress Developer</li>
            </ul>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
