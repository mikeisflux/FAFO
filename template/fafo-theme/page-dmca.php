<?php
/**
 * Template Name: DMCA / Takedowns
 */
get_header(); ?>
<main class="site-content" id="main" role="main">
<div class="container"><div class="content-area"><div class="main-content">
<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.2rem;font-weight:900;color:#FFD700;margin:0 0 8px;">DMCA &amp; COPYRIGHT POLICY</h1>
    <p style="color:rgba(255,255,255,0.7);font-size:0.9rem;margin:0;">Digital Millennium Copyright Act Compliance</p>
</div>

<?php
$sections = [
    'DMCA Compliance' => 'FAFO News respects intellectual property rights and complies with the Digital Millennium Copyright Act (DMCA), 17 U.S.C. § 512. We will respond promptly to valid DMCA takedown notices.',

    'Copyright Infringement Policy' => 'FAFO News does not knowingly post copyrighted material without permission. All original content published on the Site is owned by FAFO News or its contributors and is protected by copyright law.

When we use third-party images, videos, or other content, we do so under:
• Fair Use doctrine (17 U.S.C. § 107) for news reporting and commentary
• Creative Commons licenses
• Written permission from the copyright holder
• Content in the public domain',

    'Filing a DMCA Takedown Notice' => 'If you believe that content on our Site infringes your copyright, please send a written notice to our Designated Agent containing all of the following:

1. A physical or electronic signature of the copyright owner or authorized agent
2. Identification of the copyrighted work claimed to have been infringed
3. Identification of the material that is claimed to be infringing, with enough information for us to locate it (URL preferred)
4. Your contact information: name, address, telephone number, and email address
5. A statement that you have a good faith belief that the use of the material is not authorized by the copyright owner, its agent, or the law
6. A statement, made under penalty of perjury, that the information in your notification is accurate and that you are the copyright owner or authorized to act on their behalf

Send DMCA notices to: legal@foramericafirstonly.com
Subject: DMCA Takedown Notice',

    'Counter-Notification' => 'If you believe your material was removed in error, you may file a counter-notification with our Designated Agent. A counter-notification must include:

1. Your physical or electronic signature
2. Identification of the material removed and where it appeared before removal
3. A statement under penalty of perjury that you have a good faith belief the material was removed by mistake or misidentification
4. Your name, address, and telephone number
5. A statement consenting to jurisdiction of federal district court in your district (or if outside the U.S., any judicial district in which FAFO News may be found)
6. A statement that you will accept service of process from the party who filed the original DMCA notice',

    'Repeat Infringers' => 'FAFO News will terminate the accounts of users who are repeat infringers of copyright in appropriate circumstances.',

    'Fair Use' => 'FAFO News frequently exercises fair use rights under 17 U.S.C. § 107 to use copyrighted materials for news reporting, commentary, criticism, and education. The use of brief excerpts, images related to news stories, and quotations from public documents is generally protected fair use.',

    'Contact Our Legal Team' => 'All copyright and legal matters:
legal@foramericafirstonly.com',
];

foreach ( $sections as $title => $content ) : ?>
<section style="margin-bottom:32px;padding-bottom:32px;border-bottom:1px solid #E8E8E8;">
    <h2 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;margin:0 0 14px;"><?php echo esc_html($title); ?></h2>
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
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('cookie-policy') ); ?>">Cookie Policy</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('corrections') ); ?>">Corrections Policy</a></li>
                <li style="padding:6px 0;"><a href="<?php echo esc_url( fafo_page_link('dmca') ); ?>" style="color:#002868;font-weight:600;">DMCA / Takedowns</a></li>
            </ul>
        </div>
    </div>
</aside>
</div></div></div>
</main>
<?php get_footer(); ?>
