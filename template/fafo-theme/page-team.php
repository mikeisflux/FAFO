<?php
/**
 * Template Name: Our Team
 *
 * Editable: WP Admin → Team Members (add/edit members there).
 * If no Team Members exist yet, the hardcoded defaults are shown.
 * The page title/intro can be edited in WP Admin → Pages → Our Team.
 */

// Page intro text from the WordPress editor
$_fafo_intro = '';
if ( have_posts() ) { while ( have_posts() ) { the_post(); $_fafo_intro = get_the_content(); } }
$_fafo_has_intro = ! empty( trim( wp_strip_all_tags( $_fafo_intro ) ) );

// Team members from CPT
$cpt_members = get_posts( [
    'post_type'      => 'fafo_team',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
] );
$use_cpt = ! empty( $cpt_members );

get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:50px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;letter-spacing:0.04em;color:#FFD700;margin:0 0 10px;">OUR TEAM</h1>
    <?php if ( $_fafo_has_intro ) : ?>
        <div style="font-size:1.05rem;color:rgba(255,255,255,0.85);max-width:600px;margin:0 auto;"><?php echo wp_kses_post( apply_filters( 'the_content', $_fafo_intro ) ); ?></div>
    <?php else : ?>
        <p style="font-size:1.1rem;color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;">
            Patriots, veterans, and journalists committed to delivering the truth without apology.
        </p>
    <?php endif; ?>
</div>

<?php if ( $use_cpt ) :
    // ── CPT-driven team members ──────────────────────────────
    $departments = [];
    foreach ( $cpt_members as $member ) {
        $dept = get_post_meta( $member->ID, '_fafo_team_dept', true ) ?: 'Staff';
        $departments[ $dept ][] = $member;
    }
    foreach ( $departments as $dept_name => $members ) : ?>
    <section style="margin-bottom:48px;">
        <div class="section-header"><h2><?php echo esc_html( $dept_name ); ?></h2></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;margin-top:20px;">
            <?php foreach ( $members as $member ) :
                $role  = get_post_meta( $member->ID, '_fafo_team_role',    true );
                $bio   = get_post_meta( $member->ID, '_fafo_team_bio',     true );
                $tw    = get_post_meta( $member->ID, '_fafo_team_twitter',  true );
                $thumb = get_the_post_thumbnail_url( $member->ID, 'fafo-thumb' );
            ?>
            <div style="background:#fff;border-radius:8px;border:1px solid #E8E8E8;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">
                <div style="height:180px;background:linear-gradient(135deg,#002868,#001540);display:flex;align-items:center;justify-content:center;overflow:hidden;">
                    <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($member->post_title); ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else : ?>
                        <div style="width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;border:3px solid #FFD700;">
                            <i class="fas fa-user" style="font-size:2.2rem;color:rgba(255,255,255,.7);"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="padding:20px;">
                    <h3 style="font-family:var(--font-head);font-size:1.05rem;font-weight:700;color:#002868;margin:0 0 4px;"><?php echo esc_html( $member->post_title ); ?></h3>
                    <?php if ( $role ) : ?>
                    <div style="font-family:var(--font-head);font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C8102E;margin-bottom:12px;"><?php echo esc_html( $role ); ?></div>
                    <?php endif; ?>
                    <?php if ( $bio ) : ?>
                    <p style="font-size:0.85rem;color:#555;line-height:1.65;margin:0 0 14px;"><?php echo esc_html( $bio ); ?></p>
                    <?php endif; ?>
                    <?php if ( $tw ) : ?>
                    <div style="display:flex;gap:10px;">
                        <a href="<?php echo esc_url($tw); ?>" style="width:32px;height:32px;background:#002868;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;" title="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach;

else :
    // ── Hardcoded defaults (shown until CPT members are added) ──
    $team = [
        'Leadership' => [
            [ 'name' => 'Colonel James "Hawk" Harrington (Ret.)', 'title' => 'Editor-in-Chief',
              'bio'  => 'A 26-year Army veteran and former intelligence officer, Col. Harrington founded FAFO News after watching the mainstream media abandon the American people.' ],
            [ 'name' => 'Sarah "Liberty" Caldwell', 'title' => 'Executive Editor',
              'bio'  => 'Former network television journalist who left a six-figure career because she refused to spike stories that made the establishment uncomfortable.' ],
            [ 'name' => 'Marcus J. Reynolds', 'title' => 'Managing Editor',
              'bio'  => 'With 18 years in political journalism covering Congress, state legislatures, and three presidential campaigns.' ],
        ],
        'Reporters' => [
            [ 'name' => 'Debra Fontaine',       'title' => 'Border & Immigration Correspondent', 'bio' => 'Years embedded along the southern border documenting what the government refuses to acknowledge.' ],
            [ 'name' => 'Cpl. Tony Vasquez (Ret.)', 'title' => 'Military & Veterans Affairs Reporter', 'bio' => 'A Marine Corps veteran covering the Pentagon, VA policy, and veterans stories the mainstream ignores.' ],
            [ 'name' => 'Rachel Drummond',      'title' => 'Economy & Finance Reporter',          'bio' => 'A former Wall Street analyst translating complex economic news into plain-spoken truth.' ],
            [ 'name' => 'Pastor Elijah Monroe', 'title' => 'Faith & Culture Correspondent',       'bio' => 'Covers the attack on religious liberty and traditional American values with biblical clarity.' ],
            [ 'name' => 'Brittany Kane',         'title' => 'Election Integrity Reporter',         'bio' => 'Investigated voter roll irregularities and election law violations in more than a dozen states.' ],
        ],
        'Opinion & Commentary' => [
            [ 'name' => 'Dr. William Prescott', 'title' => 'Senior Political Analyst', 'bio' => 'A constitutional scholar and former senior White House policy advisor.' ],
            [ 'name' => "Maggie O'Brien",       'title' => 'Opinion Columnist',        'bio' => 'Sharp-tongued, sharp-minded conservative writer whose weekly column is one of our most-shared.' ],
        ],
        'Video & Multimedia' => [
            [ 'name' => 'Chase Rutherford', 'title' => 'Video Editor & Producer', 'bio' => 'Produces FAFO video content from breaking news clips to long-form investigations across 30 states.' ],
        ],
    ];
    foreach ( $team as $department => $members ) : ?>
    <section style="margin-bottom:48px;">
        <div class="section-header"><h2><?php echo esc_html( $department ); ?></h2></div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;margin-top:20px;">
            <?php foreach ( $members as $member ) : ?>
            <div style="background:#fff;border-radius:8px;border:1px solid #E8E8E8;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.06);">
                <div style="height:180px;background:linear-gradient(135deg,#002868,#001540);display:flex;align-items:center;justify-content:center;">
                    <div style="width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;border:3px solid #FFD700;">
                        <i class="fas fa-user" style="font-size:2.2rem;color:rgba(255,255,255,.7);"></i>
                    </div>
                </div>
                <div style="padding:20px;">
                    <h3 style="font-family:var(--font-head);font-size:1.05rem;font-weight:700;color:#002868;margin:0 0 4px;"><?php echo esc_html( $member['name'] ); ?></h3>
                    <div style="font-family:var(--font-head);font-size:0.75rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#C8102E;margin-bottom:12px;"><?php echo esc_html( $member['title'] ); ?></div>
                    <p style="font-size:0.85rem;color:#555;line-height:1.65;margin:0;"><?php echo esc_html( $member['bio'] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endforeach;
endif; ?>

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
            <a href="<?php echo esc_url( fafo_page_link('contact') ); ?>" style="display:block;background:#C8102E;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;">Contact Us</a>
            <a href="<?php echo esc_url( fafo_page_link('tip-line') ); ?>" style="display:block;background:#002868;color:#fff;text-align:center;padding:10px;text-decoration:none;font-family:var(--font-head);font-weight:700;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;font-size:0.88rem;margin-top:8px;"><i class="fas fa-shield-alt"></i> Secure Tip Line</a>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
