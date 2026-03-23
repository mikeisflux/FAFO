<?php
/**
 * Template Name: Careers
 *
 * Editable: WP Admin → Pages → Careers (intro text)
 * Job listings: WP Admin → Job Listings (add/edit there)
 */
if ( have_posts() ) { while ( have_posts() ) { the_post(); } }
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

<!-- Intro / description from the WordPress page editor -->
<div class="entry-content" style="max-width:100%;margin-bottom:36px;">
    <?php the_content(); ?>
</div>

<?php
// ── CPT-driven job listings ──────────────────────────────────────
$cpt_jobs = get_posts( [
    'post_type'      => 'fafo_job',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
] );

if ( ! empty( $cpt_jobs ) ) :
    foreach ( $cpt_jobs as $job ) :
        $type  = get_post_meta( $job->ID, '_fafo_job_type',     true ) ?: 'Full-Time';
        $loc   = get_post_meta( $job->ID, '_fafo_job_location', true ) ?: 'Remote';
        $dept  = get_post_meta( $job->ID, '_fafo_job_dept',     true ) ?: 'Editorial';
        $reqs  = get_post_meta( $job->ID, '_fafo_job_reqs',     true );
    ?>
    <article style="background:#fff;border:1px solid #E8E8E8;border-radius:8px;padding:28px;margin-bottom:24px;border-left:5px solid #C8102E;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;flex-wrap:wrap;gap:10px;">
            <h3 style="font-family:var(--font-head);font-size:1.2rem;font-weight:700;color:#002868;margin:0;"><?php echo esc_html( $job->post_title ); ?></h3>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <span style="background:#002868;color:#fff;font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:4px 10px;border-radius:20px;"><?php echo esc_html($type); ?></span>
                <span style="background:#F5F5F0;color:#555;font-size:0.72rem;font-weight:600;padding:4px 10px;border-radius:20px;"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($loc); ?></span>
                <span style="background:#C8102E;color:#fff;font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:4px 10px;border-radius:20px;"><?php echo esc_html($dept); ?></span>
            </div>
        </div>
        <div style="font-size:0.9rem;color:#444;line-height:1.7;margin:0 0 14px;"><?php echo wp_kses_post( apply_filters( 'the_content', $job->post_content ) ); ?></div>
        <?php if ( $reqs ) : ?>
        <h5 style="font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#666;margin:0 0 10px;">Requirements</h5>
        <ul style="font-size:0.85rem;color:#555;line-height:1.6;margin:0 0 18px;padding-left:20px;">
            <?php foreach ( array_filter( array_map( 'trim', explode( "\n", $reqs ) ) ) as $req ) : ?>
            <li><?php echo esc_html($req); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <a href="mailto:<?php echo esc_attr( fafo_contact_email('careers') ); ?>?subject=Application: <?php echo esc_attr( $job->post_title ); ?>"
           style="display:inline-block;background:#C8102E;color:#fff;padding:10px 24px;text-decoration:none;font-family:var(--font-head);font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;">
            <i class="fas fa-paper-plane"></i> Apply Now
        </a>
    </article>
    <?php endforeach;

else :
    // Fallback hardcoded listings (shown until CPT jobs are added)
    $jobs = [
        [ 'title' => 'Senior Political Reporter',         'type' => 'Full-Time',            'location' => 'Remote / Washington D.C.', 'dept' => 'Editorial',          'desc' => 'We are seeking an experienced political reporter to cover Congress, the White House, and the ongoing battle for America\'s future. You will have direct access to top conservative leaders and be expected to break stories the establishment media buries.', 'reqs' => [ '5+ years political reporting experience', 'Deep source network in Washington D.C.', 'Ability to write quickly and accurately under deadline', 'Thorough understanding of conservative policy priorities' ] ],
        [ 'title' => 'Video Producer / Editor',           'type' => 'Full-Time',            'location' => 'Remote',                  'dept' => 'Video & Multimedia',  'desc' => 'FAFO Video is expanding rapidly. We need a skilled producer/editor to create compelling video content, from breaking news clips to long-form investigations.',             'reqs' => [ '3+ years video production experience', 'Proficiency in Adobe Premiere Pro or Final Cut', 'Experience with news or political content preferred' ] ],
        [ 'title' => 'Border & Immigration Correspondent', 'type' => 'Full-Time',            'location' => 'Texas / Arizona',         'dept' => 'Editorial',          'desc' => 'The southern border is the biggest story in America. We need a fearless journalist willing to embed on the front lines and report what the legacy media refuses to show.',        'reqs' => [ '3+ years journalism experience', 'Willingness to work in challenging field conditions', 'Spanish language skills strongly preferred' ] ],
        [ 'title' => 'Opinion Columnist',                  'type' => 'Part-Time / Contributing', 'location' => 'Remote',             'dept' => 'Opinion',             'desc' => 'We are seeking contributing opinion columnists to submit 2–4 pieces per month on politics, culture, economics, or any topic where you have expertise.',                      'reqs' => [ 'Strong writing and argumentation skills', 'Unique conservative perspective', 'Prior published work preferred' ] ],
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
        <a href="mailto:<?php echo esc_attr( fafo_contact_email('careers') ); ?>?subject=Application: <?php echo esc_attr($job['title']); ?>"
           style="display:inline-block;background:#C8102E;color:#fff;padding:10px 24px;text-decoration:none;font-family:var(--font-head);font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;">
            <i class="fas fa-paper-plane"></i> Apply Now
        </a>
    </article>
    <?php endforeach;
endif; ?>

<section style="background:linear-gradient(135deg,#002868,#001540);padding:36px;border-radius:8px;text-align:center;color:#fff;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.5rem;font-weight:900;color:#FFD700;margin:0 0 10px;">DON'T SEE YOUR ROLE?</h3>
    <p style="opacity:0.85;margin:0 0 20px;">We're always interested in talented patriots. Send us your resume and tell us what you can do.</p>
    <a href="mailto:<?php echo esc_attr( fafo_contact_email('careers') ); ?>"
       style="display:inline-block;background:#C8102E;color:#fff;padding:12px 28px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;border-radius:4px;">
        <i class="fas fa-envelope"></i> Send General Application
    </a>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-briefcase"></i> Apply Now</h3>
        <div class="widget-body">
            <p style="font-size:0.88rem;color:#555;margin:0 0 12px;">Send your resume and cover letter to our careers team:</p>
            <a href="mailto:<?php echo esc_attr( fafo_contact_email('careers') ); ?>"
               style="display:block;background:#C8102E;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.85rem;word-break:break-all;">
                <?php echo esc_html( fafo_contact_email('careers') ); ?>
            </a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
