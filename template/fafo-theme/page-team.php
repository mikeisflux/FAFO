<?php
/**
 * Template Name: Our Team
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;letter-spacing:0.04em;color:#FFD700;margin:0 0 10px;">OUR TEAM</h1>
    <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;">
        Patriots, veterans, and journalists committed to delivering the truth without apology.
    </p>
</div>

<?php
$team = [
    'Leadership' => [
        [
            'name'  => 'Colonel James "Hawk" Harrington (Ret.)',
            'title' => 'Editor-in-Chief',
            'bio'   => 'A 26-year Army veteran and former intelligence officer, Col. Harrington founded FAFO News after watching the mainstream media abandon the American people. He leads the editorial team with the same discipline and conviction he brought to three combat deployments.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
        [
            'name'  => 'Sarah "Liberty" Caldwell',
            'title' => 'Executive Editor',
            'bio'   => 'Former network television journalist who left a six-figure career because she refused to spike stories that made the establishment uncomfortable. Sarah has broken some of FAFO\'s biggest investigative pieces.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
        [
            'name'  => 'Marcus J. Reynolds',
            'title' => 'Managing Editor',
            'bio'   => 'With 18 years in political journalism covering Congress, state legislatures, and three presidential campaigns, Marcus ensures FAFO\'s political coverage is fast, accurate, and unflinching.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
    ],
    'Reporters' => [
        [
            'name'  => 'Debra Fontaine',
            'title' => 'Border & Immigration Correspondent',
            'bio'   => 'Debra has spent years embedded along the southern border documenting what the government refuses to acknowledge. Her ground-level reporting has been cited by members of Congress and law enforcement agencies.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
        [
            'name'  => 'Cpl. Tony Vasquez (Ret.)',
            'title' => 'Military & Veterans Affairs Reporter',
            'bio'   => 'A Marine Corps veteran who came home and picked up a press badge. Tony covers the Pentagon, VA policy, and the stories of veterans that the mainstream media ignores.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
        [
            'name'  => 'Rachel Drummond',
            'title' => 'Economy & Finance Reporter',
            'bio'   => 'A former Wall Street analyst turned journalist, Rachel translates complex economic and financial news into plain-spoken truth for hardworking American families.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
        [
            'name'  => 'Pastor Elijah Monroe',
            'title' => 'Faith & Culture Correspondent',
            'bio'   => 'A faith leader and cultural commentator who covers the attack on religious liberty and traditional American values with biblical clarity and journalistic precision.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
        [
            'name'  => 'Brittany Kane',
            'title' => 'Election Integrity Reporter',
            'bio'   => 'Brittany has investigated voter roll irregularities, election equipment vulnerabilities, and election law violations in more than a dozen states. Her work has triggered formal investigations.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
    ],
    'Opinion & Commentary' => [
        [
            'name'  => 'Dr. William Prescott',
            'title' => 'Senior Political Analyst',
            'bio'   => 'A constitutional scholar and former senior White House policy advisor, Dr. Prescott provides deep analysis on legislation, executive power, and the ongoing effort to preserve the American republic.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
        [
            'name'  => 'Maggie O\'Brien',
            'title' => 'Opinion Columnist',
            'bio'   => 'A sharp-tongued, sharp-minded conservative writer whose weekly column is one of the most-shared pieces on FAFO. Maggie takes no prisoners and suffers no fools.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
    ],
    'Video & Multimedia' => [
        [
            'name'  => 'Chase Rutherford',
            'title' => 'Video Editor & Producer',
            'bio'   => 'Chase produces FAFO\'s video content, from breaking news clips to long-form documentary investigations. He has shot footage in 30 states and four countries.',
            'social'=> [ 'twitter' => '#', 'email' => '#' ],
        ],
    ],
];

foreach ( $team as $department => $members ) : ?>
<section style="margin-bottom:48px;">
    <div class="section-header">
        <h2><?php echo esc_html( $department ); ?></h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;margin-top:20px;">
        <?php foreach ( $members as $member ) : ?>
        <div style="background:#fff;border-radius:8px;border:1px solid #E8E8E8;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">
            <!-- Avatar placeholder -->
            <div style="height:180px;background:linear-gradient(135deg,#002868,#001540);display:flex;align-items:center;justify-content:center;">
                <div style="width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;border:3px solid #FFD700;">
                    <i class="fas fa-user" style="font-size:2.2rem;color:rgba(255,255,255,.7);"></i>
                </div>
            </div>
            <div style="padding:20px;">
                <h3 style="font-family:var(--font-head);font-size:1.05rem;font-weight:700;color:#002868;margin:0 0 4px;"><?php echo esc_html( $member['name'] ); ?></h3>
                <div style="font-family:var(--font-head);font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C8102E;margin-bottom:12px;"><?php echo esc_html( $member['title'] ); ?></div>
                <p style="font-size:0.85rem;color:#555;line-height:1.65;margin:0 0 14px;"><?php echo esc_html( $member['bio'] ); ?></p>
                <div style="display:flex;gap:10px;">
                    <a href="<?php echo esc_url( $member['social']['twitter'] ); ?>" style="width:32px;height:32px;background:#002868;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                    <a href="<?php echo esc_url( $member['social']['email'] ); ?>" style="width:32px;height:32px;background:#C8102E;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;" title="Email"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endforeach; ?>

<!-- JOIN TEAM CTA -->
<section style="background:#F5F5F0;padding:40px;border-radius:8px;text-align:center;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.6rem;font-weight:900;color:#002868;margin:0 0 10px;">WANT TO JOIN THE FAFO TEAM?</h3>
    <p style="color:#555;margin:0 0 20px;">We're always looking for bold, fearless journalists, writers, and creators who refuse to be silenced.</p>
    <a href="<?php echo esc_url( fafo_page_link('careers') ); ?>" style="display:inline-block;background:#C8102E;color:#fff;padding:12px 32px;font-family:var(--font-head);font-weight:700;letter-spacing:0.08em;text-transform:uppercase;text-decoration:none;border-radius:4px;">
        <i class="fas fa-briefcase"></i> View Open Positions
    </a>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-envelope"></i> Contact the Newsroom</h3>
        <div class="widget-body">
            <p style="font-size:0.88rem;color:#555;line-height:1.6;margin:0 0 12px;">Have a tip, correction, or story idea? Our team wants to hear from you.</p>
            <a href="<?php echo esc_url( fafo_page_link('contact') ); ?>" class="btn" style="display:block;background:#C8102E;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;">
                Contact Us
            </a>
            <a href="<?php echo esc_url( fafo_page_link('tip-line') ); ?>" class="btn" style="display:block;background:#002868;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;margin-top:8px;">
                <i class="fas fa-shield-alt"></i> Secure Tip Line
            </a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
