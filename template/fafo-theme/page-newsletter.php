<?php
/**
 * Template Name: Newsletter
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#C8102E 0%,#8b0000 100%);padding:60px 40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <i class="fas fa-bolt" style="font-size:3rem;color:#FFD700;margin-bottom:16px;display:block;"></i>
    <h1 style="font-family:var(--font-head);font-size:2.6rem;font-weight:900;color:#FFD700;margin:0 0 10px;">STAY INFORMED. STAY FREE.</h1>
    <p style="font-size:1.15rem;color:rgba(255,255,255,0.9);max-width:550px;margin:0 auto 24px;">
        Get FAFO's breaking news, investigative reports, and daily briefings delivered straight to your inbox — before Big Tech can censor it.
    </p>
    <?php
    if ( isset($_POST['nl_nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nl_nonce'] ) ), 'fafo_nl' ) ) {
        $nl_email = sanitize_email( $_POST['nl_email'] ?? '' );
        if ( is_email( $nl_email ) ) { $nl_success = true; }
        else { $nl_error = true; }
    }
    if ( ! empty( $nl_success ) ) : ?>
    <div style="background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.3);color:#fff;padding:14px 20px;border-radius:6px;margin-bottom:16px;display:inline-block;">
        <i class="fas fa-check-circle"></i> <strong>You're in, patriot!</strong> Check your inbox for a confirmation email.
    </div>
    <?php else : ?>
    <form method="post" action="" style="display:flex;gap:12px;max-width:480px;margin:0 auto;justify-content:center;">
        <?php wp_nonce_field('fafo_nl','nl_nonce'); ?>
        <input type="email" name="nl_email" placeholder="Enter your email address..." required
               style="flex:1;padding:14px 18px;border:none;border-radius:4px;font-size:1rem;min-width:0;">
        <button type="submit" style="background:#FFD700;color:#002868;border:none;padding:14px 24px;font-family:var(--font-head);font-weight:700;font-size:0.9rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;cursor:pointer;white-space:nowrap;">
            SUBSCRIBE FREE
        </button>
    </form>
    <p style="font-size:0.78rem;color:rgba(255,255,255,0.6);margin:10px 0 0;">No spam. No censorship. Unsubscribe anytime.</p>
    <?php endif; ?>
</div>

<!-- WHAT YOU GET -->
<section style="margin-bottom:48px;">
    <div class="section-header"><h2>What You'll Receive</h2></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:20px;">
        <?php
        $newsletters = [
            [
                'icon'  => 'fa-bolt',
                'color' => '#C8102E',
                'name'  => 'FAFO Breaking Alert',
                'freq'  => 'As it happens',
                'desc'  => 'Instant alerts when major breaking news hits — before the mainstream media can spin it.',
            ],
            [
                'icon'  => 'fa-sun',
                'color' => '#002868',
                'name'  => 'America First Morning Brief',
                'freq'  => 'Daily — 6:00 AM ET',
                'desc'  => 'Start your day with the top 5 stories you need to know. Concise. Accurate. Unfiltered.',
            ],
            [
                'icon'  => 'fa-newspaper',
                'color' => '#555',
                'name'  => 'FAFO Weekly Digest',
                'freq'  => 'Every Sunday',
                'desc'  => 'The week\'s top stories, deep-dive investigations, and the best opinion pieces from our writers.',
            ],
            [
                'icon'  => 'fa-video',
                'color' => '#85C742',
                'name'  => 'FAFO Video Highlights',
                'freq'  => 'Weekly',
                'desc'  => 'The week\'s most-watched videos, exclusive interviews, and must-see clips you may have missed.',
            ],
            [
                'icon'  => 'fa-search',
                'color' => '#FF8C00',
                'name'  => 'The Investigator',
                'freq'  => 'Monthly',
                'desc'  => 'Deep investigative reports, exclusive documents, and major exposés from the FAFO newsroom.',
            ],
            [
                'icon'  => 'fa-tag',
                'color' => '#6A0DAD',
                'name'  => 'Patriot Deals',
                'freq'  => 'Weekly',
                'desc'  => 'Exclusive merch discounts, partner offers, and deals for the FAFO community.',
            ],
        ];
        foreach ( $newsletters as $nl ) : ?>
        <div style="background:#fff;border-radius:8px;border:1px solid #E8E8E8;padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.05);">
            <div style="width:50px;height:50px;background:<?php echo esc_attr($nl['color']); ?>;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:14px;">
                <i class="fas <?php echo esc_attr($nl['icon']); ?>" style="color:#fff;font-size:1.2rem;"></i>
            </div>
            <h4 style="font-family:var(--font-head);font-size:0.95rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;margin:0 0 4px;"><?php echo esc_html($nl['name']); ?></h4>
            <div style="font-size:0.75rem;color:#C8102E;font-weight:600;margin-bottom:10px;"><?php echo esc_html($nl['freq']); ?></div>
            <p style="font-size:0.85rem;color:#555;line-height:1.6;margin:0;"><?php echo esc_html($nl['desc']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- TESTIMONIALS -->
<section style="background:#F5F5F0;padding:36px;border-radius:8px;margin-bottom:48px;">
    <div class="section-header"><h2>What Patriots Are Saying</h2></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:20px;">
        <?php
        $testimonials = [
            [ 'quote' => 'FAFO\'s morning brief is the first thing I read every day. It\'s the only news source I trust to give it to me straight.',           'author' => 'Robert M., Texas' ],
            [ 'quote' => 'Finally, a newsletter that doesn\'t treat me like an idiot. Real news, real fast, no agenda.',                                       'author' => 'Patricia H., Florida' ],
            [ 'quote' => 'I\'ve been a subscriber since day one. The breaking alerts alone are worth it — I\'m always first to know.',                         'author' => 'Mike D., Ohio' ],
        ];
        foreach ( $testimonials as $t ) : ?>
        <div style="background:#fff;border-radius:6px;padding:20px;border:1px solid #E8E8E8;position:relative;">
            <i class="fas fa-quote-left" style="color:#E8E8E8;font-size:2rem;position:absolute;top:14px;left:16px;"></i>
            <p style="font-size:0.9rem;color:#444;line-height:1.7;margin:0 0 14px;padding-top:12px;position:relative;z-index:1;"><?php echo esc_html($t['quote']); ?></p>
            <div style="font-family:var(--font-head);font-size:0.78rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#C8102E;">— <?php echo esc_html($t['author']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget" style="background:linear-gradient(135deg,#002868,#001540);color:#fff;border-radius:8px;padding:20px;">
        <h3 style="font-family:var(--font-head);font-size:0.85rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#FFD700;margin:0 0 12px;"><i class="fas fa-envelope"></i> Quick Subscribe</h3>
        <form method="post" action="">
            <?php wp_nonce_field('fafo_nl','nl_nonce'); ?>
            <input type="email" name="nl_email" placeholder="Your email..." required style="width:100%;padding:10px 14px;border:none;border-radius:4px;font-size:0.9rem;margin-bottom:8px;box-sizing:border-box;">
            <button type="submit" style="width:100%;background:#C8102E;color:#fff;border:none;padding:10px;font-family:var(--font-head);font-weight:700;font-size:0.85rem;letter-spacing:0.06em;text-transform:uppercase;border-radius:4px;cursor:pointer;">
                <i class="fas fa-bolt"></i> SUBSCRIBE FREE
            </button>
        </form>
        <p style="font-size:0.72rem;color:rgba(255,255,255,0.5);margin:8px 0 0;text-align:center;">No spam. Unsubscribe anytime.</p>
    </div>
    <?php if ( is_active_sidebar('sidebar-main') ) dynamic_sidebar('sidebar-main'); ?>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
