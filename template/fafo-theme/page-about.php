<?php
/**
 * Template Name: About FAFO
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<!-- PAGE HERO -->
<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:60px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:3rem;font-weight:900;letter-spacing:0.04em;color:#FFD700;margin:0 0 12px;">
        ABOUT <span style="color:#C8102E;">FAFO</span> NEWS
    </h1>
    <p style="font-size:1.2rem;color:rgba(255,255,255,0.85);max-width:600px;margin:0 auto;line-height:1.7;">
        For America First Only — Bold, unapologetic conservative journalism for the American patriot.
    </p>
</div>

<!-- MISSION -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>Our Mission</h2></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;">
        <div>
            <p style="font-size:1.05rem;line-height:1.8;color:#333;">
                FAFO News was founded on a single principle: <strong>America First.</strong> We exist to deliver the news that the mainstream media refuses to cover — the stories about border security, Second Amendment rights, election integrity, economic sovereignty, and the values that made this nation great.
            </p>
            <p style="font-size:1.05rem;line-height:1.8;color:#333;">
                In an era of media censorship, shadow-banning, and institutional bias, FAFO News stands as an unapologetic voice for conservative Americans. We don't apologize for our perspective. We don't sanitize our reporting to appease globalists, activists, or Big Tech overlords.
            </p>
        </div>
        <div>
            <p style="font-size:1.05rem;line-height:1.8;color:#333;">
                Our reporters, editors, and contributors come from newsrooms, military service, law enforcement, and the heartland of America. We understand the issues because we live them. We cover them because no one else will.
            </p>
            <p style="font-size:1.05rem;line-height:1.8;color:#333;">
                FAFO isn't just a news outlet. It's a movement. A community of patriots who believe in the Constitution, the rule of law, and the unshakeable idea that America is worth fighting for.
            </p>
        </div>
    </div>
</section>

<!-- VALUES -->
<section style="background:#F5F5F0;padding:40px;border-radius:8px;margin-bottom:48px;">
    <div class="section-header"><h2>Our Core Values</h2></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:24px;">
        <?php
        $values = [
            [ 'icon' => 'fa-flag',           'title' => 'America First',       'desc' => 'Every story is filtered through the lens of what is best for the American people — not globalist interests, not foreign donors, not corporate advertisers.' ],
            [ 'icon' => 'fa-balance-scale',  'title' => 'Truth Over Comfort',  'desc' => 'We report the facts as they are, not as the establishment wishes them to be. Uncomfortable truths are still truths.' ],
            [ 'icon' => 'fa-shield-alt',     'title' => 'Editorial Independence', 'desc' => 'No government grants. No Soros funding. No corporate strings. FAFO is funded by patriots, for patriots.' ],
            [ 'icon' => 'fa-users',          'title' => 'Community First',     'desc' => 'Our readers aren\'t just consumers — they are our community, our tipsters, our family. We serve them, not the other way around.' ],
            [ 'icon' => 'fa-book',           'title' => 'Corrections Policy',  'desc' => 'When we get something wrong, we say so clearly and correct it prominently. Accountability matters.' ],
            [ 'icon' => 'fa-lock',           'title' => 'Source Protection',   'desc' => 'Whistleblowers and patriots who bring us stories can trust that we will protect their identities with every means at our disposal.' ],
        ];
        foreach ( $values as $v ) : ?>
        <div style="text-align:center;padding:20px;">
            <div style="width:60px;height:60px;background:#002868;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                <i class="fas <?php echo esc_attr($v['icon']); ?>" style="color:#FFD700;font-size:1.4rem;"></i>
            </div>
            <h4 style="font-family:var(--font-head);font-size:0.95rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#002868;margin:0 0 8px;"><?php echo esc_html($v['title']); ?></h4>
            <p style="font-size:0.88rem;color:#555;line-height:1.6;margin:0;"><?php echo esc_html($v['desc']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- COVERAGE AREAS -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>What We Cover</h2></div>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-top:20px;">
        <?php
        $topics = [
            'Politics & Government'  => 'Washington D.C. news, legislation, executive actions, and political analysis from a conservative lens.',
            'Economy & Finance'      => 'Jobs, inflation, trade policy, fiscal responsibility, and economic stories that affect your wallet.',
            'National Security'      => 'Military strength, foreign threats, intelligence, and homeland defense.',
            'Border & Immigration'   => 'The southern border crisis, immigration enforcement, and America\'s sovereignty.',
            '2nd Amendment Rights'  => 'Gun rights legislation, self-defense stories, and the constant fight to protect your rights.',
            'Faith & Culture'        => 'Religious liberty, the culture war, education, and traditional American values.',
            'Election Integrity'     => 'Voter ID, election security, fraud investigations, and protecting the ballot.',
            'Video & Multimedia'     => 'Exclusive video reports, interviews, and live event coverage from the front lines.',
        ];
        foreach ( $topics as $title => $desc ) : ?>
        <div style="background:#fff;border:1px solid #E8E8E8;border-left:4px solid #C8102E;padding:16px 20px;border-radius:0 4px 4px 0;">
            <strong style="font-family:var(--font-head);font-size:0.9rem;letter-spacing:0.05em;text-transform:uppercase;color:#002868;"><?php echo esc_html($title); ?></strong>
            <p style="font-size:0.85rem;color:#666;margin:4px 0 0;line-height:1.5;"><?php echo esc_html($desc); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- HISTORY -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>Our Story</h2></div>
    <p style="font-size:1.05rem;line-height:1.8;color:#333;">
        FAFO News was born from frustration. Frustration with a mainstream media that had abandoned journalism for activism. Frustration with Big Tech platforms that silenced conservative voices. Frustration with a political class that had forgotten who they work for.
    </p>
    <p style="font-size:1.05rem;line-height:1.8;color:#333;">
        A small group of patriot journalists, veterans, and concerned citizens came together with one goal: build the news outlet that America actually needs. Not another controlled-opposition outlet that pretends to be conservative while playing by establishment rules. A real outlet, with real reporters, covering real stories.
    </p>
    <p style="font-size:1.05rem;line-height:1.8;color:#333;">
        FAFO — For America First Only — is more than a name. It's a statement. It's a commitment. It's a challenge to the fake news industrial complex that says patriots don't deserve a voice.
    </p>
    <p style="font-size:1.05rem;line-height:1.8;color:#333;">
        We're here. We're not going anywhere. <strong>FAFO.</strong>
    </p>
</section>

<!-- CTA -->
<section style="background:linear-gradient(135deg,#C8102E 0%,#8b0000 100%);padding:40px;border-radius:8px;text-align:center;color:#fff;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:1.8rem;font-weight:900;margin:0 0 12px;">JOIN THE MOVEMENT</h3>
    <p style="font-size:1rem;opacity:0.9;margin:0 0 24px;">Get FAFO breaking news in your inbox. No spin. No censorship. Just the truth.</p>
    <form action="#" method="post" style="display:flex;gap:12px;max-width:480px;margin:0 auto;">
        <input type="email" name="email" placeholder="Your email address..." required
               style="flex:1;padding:12px 16px;border:none;border-radius:4px;font-size:1rem;">
        <button type="submit" style="background:#FFD700;color:#002868;border:none;padding:12px 24px;font-family:var(--font-head);font-weight:700;font-size:0.9rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;cursor:pointer;white-space:nowrap;">
            <i class="fas fa-bolt"></i> SUBSCRIBE FREE
        </button>
    </form>
</section>

</div><!-- .main-content -->

<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-flag"></i> Quick Links</h3>
        <div class="widget-body">
            <ul style="list-style:none;padding:0;margin:0;">
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('team') ); ?>"><i class="fas fa-users" style="width:18px;color:#C8102E;"></i> Our Team</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('contact') ); ?>"><i class="fas fa-envelope" style="width:18px;color:#C8102E;"></i> Contact Us</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('advertise') ); ?>"><i class="fas fa-chart-bar" style="width:18px;color:#C8102E;"></i> Advertise</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('press') ); ?>"><i class="fas fa-newspaper" style="width:18px;color:#C8102E;"></i> Press Room</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('tip-line') ); ?>"><i class="fas fa-shield-alt" style="width:18px;color:#C8102E;"></i> Tip Line</a></li>
                <li style="padding:6px 0;"><a href="<?php echo esc_url( fafo_page_link('careers') ); ?>"><i class="fas fa-briefcase" style="width:18px;color:#C8102E;"></i> Careers</a></li>
            </ul>
        </div>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>

</div><!-- .content-area -->
</div><!-- .container -->
</main>

<?php get_footer(); ?>
