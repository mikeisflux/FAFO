<?php
/**
 * Template Name: Corrections Policy
 */
get_header(); ?>
<main class="site-content" id="main" role="main">
<div class="container"><div class="content-area"><div class="main-content">
<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.2rem;font-weight:900;color:#FFD700;margin:0 0 8px;">CORRECTIONS POLICY</h1>
    <p style="color:rgba(255,255,255,0.85);max-width:550px;margin:0 auto;font-size:1rem;">Accuracy matters. When we get something wrong, we own it and fix it.</p>
</div>

<section style="margin-bottom:32px;">
    <p style="font-size:1.05rem;line-height:1.8;color:#333;">
        FAFO News is committed to accuracy in all of our reporting. We take our responsibility to readers seriously. When errors occur — and they do in any newsroom — we correct them promptly, transparently, and without burying the correction.
    </p>
</section>

<?php
$sections = [
    'Our Commitment' => 'FAFO News strives for factual accuracy in every article, video, and opinion piece we publish. We conduct thorough fact-checking and source verification before publication. However, we are human, and errors occasionally occur. When they do, we believe in full transparency with our readers.',

    'Types of Corrections' => 'Factual Errors: Corrections for errors of fact (incorrect names, dates, statistics, quotes, or other demonstrably false information) will be published at the top of the original article with a clear label reading "CORRECTION."

Clarifications: When an article is accurate but could be read in a misleading way, we will add a clarification note explaining the ambiguity.

Updates: When a story evolves after publication (e.g., charges are dropped, a statement is updated), we will add an "UPDATE" note to the article with the new information.

Retractions: In rare cases where an entire story cannot be corrected and must be removed, we will post a retraction notice explaining why the story was removed.',

    'How Corrections Are Published' => 'Corrections appear at the top of the original article, not buried at the bottom. The correction note will clearly state what was incorrect and what the correct information is. We do not simply edit articles silently — every correction is noted and dated. We do not delete articles to avoid accountability.',

    'How to Request a Correction' => 'If you believe FAFO News has published incorrect information, please contact us:

Email: corrections@foramericafirstonly.com
Subject line: "Correction Request — [Article Title]"

Please include:
• The URL of the article in question
• The specific claim you believe is incorrect
• Evidence or documentation supporting your correction request
• Your name and contact information (for follow-up)

We will review all correction requests within 2 business days.',

    'Editorial Independence' => 'Our corrections policy applies equally to all stories regardless of political sensitivity. We do not selectively correct based on ideological convenience. A correction is issued when information is factually wrong — not when a reader simply disagrees with our reporting or editorial perspective.',

    'Corrections Archive' => 'We maintain a record of significant corrections. We believe in accountability and will not pretend errors never happened.',
];

foreach ( $sections as $title => $content ) : ?>
<section style="margin-bottom:32px;padding-bottom:32px;border-bottom:1px solid #E8E8E8;">
    <h2 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;margin:0 0 14px;"><?php echo esc_html($title); ?></h2>
    <div style="font-size:0.92rem;color:#444;line-height:1.8;white-space:pre-line;"><?php echo esc_html($content); ?></div>
</section>
<?php endforeach; ?>

<section style="background:#F5F5F0;padding:24px;border-radius:8px;border-left:5px solid #C8102E;margin-bottom:40px;">
    <h3 style="font-family:var(--font-head);font-size:0.9rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#002868;margin:0 0 10px;">Submit a Correction Request</h3>
    <p style="font-size:0.9rem;color:#555;margin:0 0 12px;">Found an error? Email us at <a href="mailto:corrections@foramericafirstonly.com" style="color:#C8102E;font-weight:600;">corrections@foramericafirstonly.com</a> or use our <a href="<?php echo esc_url( fafo_page_link('contact') ); ?>" style="color:#C8102E;font-weight:600;">contact form</a>.</p>
</section>

</div><!-- .main-content -->
<aside class="sidebar" role="complementary">
    <div class="widget">
        <h3 class="widget-title"><i class="fas fa-gavel"></i> Legal</h3>
        <div class="widget-body">
            <ul style="list-style:none;padding:0;margin:0;font-size:0.88rem;">
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('privacy-policy') ); ?>">Privacy Policy</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('terms-of-service') ); ?>">Terms of Service</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('cookie-policy') ); ?>">Cookie Policy</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('corrections') ); ?>" style="color:#002868;font-weight:600;">Corrections Policy</a></li>
                <li style="padding:6px 0;"><a href="<?php echo esc_url( fafo_page_link('dmca') ); ?>">DMCA / Takedowns</a></li>
            </ul>
        </div>
    </div>
</aside>
</div></div></div>
</main>
<?php get_footer(); ?>
