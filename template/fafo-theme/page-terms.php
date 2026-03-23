<?php
/**
 * Template Name: Terms of Service
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.2rem;font-weight:900;color:#FFD700;margin:0 0 8px;">TERMS OF SERVICE</h1>
    <p style="color:rgba(255,255,255,0.7);font-size:0.9rem;margin:0;">Last Updated: January 1, 2025</p>
</div>

<?php
$sections = [
    'Acceptance of Terms' => 'By accessing or using the FAFO News website (foramericafirstonly.com), you agree to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, please do not use the Site. We reserve the right to modify these Terms at any time. Continued use of the Site following any modification constitutes your acceptance of the revised Terms.',

    'Description of Service' => 'FAFO News provides an online news publication offering news articles, opinion pieces, video content, and related services. The Site is intended for informational and entertainment purposes. Nothing on the Site constitutes legal, financial, medical, or professional advice.',

    'User Conduct' => 'You agree not to use the Site to:
• Post, transmit, or share content that is defamatory, obscene, fraudulent, or otherwise unlawful
• Harass, threaten, or intimidate other users or FAFO staff
• Attempt to gain unauthorized access to any part of the Site
• Use automated tools to scrape, crawl, or harvest content without permission
• Post spam, chain letters, or pyramid schemes
• Impersonate any person or entity
• Violate any applicable federal, state, or local law or regulation

We reserve the right to terminate access for any user who violates these rules.',

    'Intellectual Property' => 'All content on the FAFO News website — including articles, images, videos, graphics, logos, and software — is the property of FAFO News or its content suppliers and is protected by United States and international copyright, trademark, and other intellectual property laws.

You may share individual articles for non-commercial, personal use with proper attribution (linking back to the original article). Reproducing, republishing, or distributing FAFO content in bulk, for commercial purposes, or without attribution is strictly prohibited.',

    'User-Generated Content' => 'By posting comments or other content on the Site, you grant FAFO News a non-exclusive, royalty-free, worldwide license to use, display, and distribute your content in connection with the Site. You represent that you own or have the right to post such content and that it does not violate any third-party rights or applicable law.',

    'Comments Policy' => 'FAFO News welcomes patriotic commentary from our readers. We moderate comments for spam, threats, and illegal content. We do not remove comments simply because they are critical of our coverage or editorial positions. All comments are the opinion of the individual poster, not FAFO News.',

    'Disclaimer of Warranties' => 'THE SITE AND ITS CONTENT ARE PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, EXPRESS OR IMPLIED. FAFO NEWS DOES NOT WARRANT THAT THE SITE WILL BE UNINTERRUPTED OR ERROR-FREE, OR THAT THE SITE IS FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.',

    'Limitation of Liability' => 'TO THE MAXIMUM EXTENT PERMITTED BY LAW, FAFO NEWS SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO LOST PROFITS, LOST DATA, OR BUSINESS INTERRUPTION, ARISING OUT OF YOUR USE OF OR INABILITY TO USE THE SITE.',

    'Governing Law' => 'These Terms shall be governed by and construed in accordance with the laws of the United States and the state in which FAFO News is domiciled, without regard to its conflict of law provisions. Any dispute arising from these Terms shall be resolved exclusively in the courts of competent jurisdiction in that state.',

    'Contact' => 'Questions about these Terms of Service should be directed to:
legal@foramericafirstonly.com',
];

foreach ( $sections as $title => $content ) : ?>
<section style="margin-bottom:32px;padding-bottom:32px;border-bottom:1px solid #E8E8E8;">
    <h2 style="font-family:var(--font-head);font-size:1.15rem;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#002868;margin:0 0 14px;"><?php echo esc_html($title); ?></h2>
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
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('terms-of-service') ); ?>" style="color:#002868;font-weight:600;">Terms of Service</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('cookie-policy') ); ?>">Cookie Policy</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('corrections') ); ?>">Corrections Policy</a></li>
                <li style="padding:6px 0;"><a href="<?php echo esc_url( fafo_page_link('dmca') ); ?>">DMCA / Takedowns</a></li>
            </ul>
        </div>
    </div>
</aside>
</div><!-- .content-area -->
</div><!-- .container -->
</main>
<?php get_footer(); ?>
