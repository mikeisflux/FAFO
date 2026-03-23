<?php
/**
 * Template Name: Privacy Policy
 */
get_header(); ?>

<main class="site-content" id="main" role="main">
<div class="container">
<div class="content-area">
<div class="main-content">

<div style="background:linear-gradient(135deg,#002868 0%,#001540 100%);padding:40px;border-radius:8px;margin-bottom:40px;text-align:center;color:#fff;">
    <h1 style="font-family:var(--font-head);font-size:2.2rem;font-weight:900;color:#FFD700;margin:0 0 8px;">PRIVACY POLICY</h1>
    <p style="color:rgba(255,255,255,0.7);font-size:0.9rem;margin:0;">Last Updated: January 1, 2025 | Effective Date: January 1, 2025</p>
</div>

<?php
$sections = [
    'Introduction' => 'FAFO News ("FAFO," "we," "us," or "our") operates the website located at foramericafirstonly.com (the "Site"). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our Site or use our services. Please read this policy carefully. If you disagree with its terms, please discontinue use of the Site.',

    'Information We Collect' => 'We may collect information about you in various ways. Information You Provide: When you subscribe to our newsletter, submit a contact form, post a comment, or otherwise interact with our Site, we collect information you directly provide, including name, email address, and any other information you choose to share.

Automatically Collected Information: When you visit our Site, we automatically collect certain information about your device, including information about your web browser, IP address, time zone, and some of the cookies installed on your device. Additionally, as you browse the Site, we collect information about the individual web pages you view, referring URLs, and information about how you interact with the Site.

Cookies and Tracking Technologies: We use cookies, web beacons, pixel tags, and similar technologies to collect information about your browsing behavior. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.',

    'How We Use Your Information' => 'We use the information we collect to:
• Send you newsletters, breaking news alerts, and other communications you have opted into
• Respond to your inquiries and customer service requests
• Analyze Site usage and improve our content and services
• Prevent fraudulent transactions, monitor against theft, and protect against criminal activity
• Comply with applicable law and legal process
• Enforce our Terms of Service

We do NOT sell your personal information to third parties.',

    'Information Sharing' => 'We may share your information in the following circumstances:
• Service Providers: We may share information with third-party service providers who assist us in operating the Site, conducting our business, or serving you (e.g., email delivery services, analytics providers)
• Legal Requirements: We may disclose your information if required to do so by law or in response to valid legal requests
• Business Transfers: In the event of a merger, acquisition, or sale of assets, your information may be transferred
• With Your Consent: We may disclose your information for any other purpose with your consent

We will never share, sell, or rent your personal information to political organizations, advertisers, or data brokers without your explicit consent.',

    'Data Retention' => 'We retain personal information for as long as necessary to fulfill the purposes for which it was collected, including any legal, accounting, or reporting requirements. Newsletter subscriber data is retained until you unsubscribe. Contact form submissions are retained for 2 years.',

    'Your Rights' => 'Depending on your location, you may have the following rights regarding your personal information:
• The right to access the personal information we hold about you
• The right to request correction of inaccurate personal information
• The right to request deletion of your personal information
• The right to opt out of marketing communications at any time
• The right to data portability

To exercise these rights, contact us at privacy@foramericafirstonly.com.',

    'Children\'s Privacy' => 'Our Site is not directed to children under the age of 13. We do not knowingly collect personal information from children under 13. If you believe we have inadvertently collected such information, please contact us immediately.',

    'Third-Party Links' => 'Our Site may contain links to third-party websites. We are not responsible for the privacy practices of those sites and encourage you to review their privacy policies.',

    'Security' => 'We implement reasonable security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the Internet is 100% secure. We cannot guarantee absolute security.',

    'Changes to This Policy' => 'We reserve the right to modify this Privacy Policy at any time. Changes will be posted to this page with an updated revision date. Your continued use of the Site after any such changes constitutes your acceptance of the new Privacy Policy.',

    'Contact Us' => 'If you have questions about this Privacy Policy or our privacy practices, contact us at:

FAFO News
Email: privacy@foramericafirstonly.com
For America First Only',
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
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('privacy-policy') ); ?>" style="color:#002868;font-weight:600;">Privacy Policy</a></li>
                <li style="padding:6px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( fafo_page_link('terms-of-service') ); ?>">Terms of Service</a></li>
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
