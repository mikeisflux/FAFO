<?php
/**
 * FAFO Theme - Category, Taxonomy, and Page Setup
 * Runs on theme activation (and once on init if not yet run).
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// CREATE MAIN NEWS CATEGORIES
// ============================================================
function fafo_create_categories() {
    $categories = [
        [
            'name'        => 'Politics',
            'slug'        => 'politics',
            'description' => 'Breaking political news, legislative updates, and Washington D.C. coverage from an America First perspective.',
        ],
        [
            'name'        => 'Economy & Finance',
            'slug'        => 'economy',
            'description' => 'Economic news, market updates, jobs reports, inflation, and fiscal policy affecting American families.',
        ],
        [
            'name'        => 'National Security',
            'slug'        => 'national-security',
            'description' => 'Military, intelligence, foreign policy, and homeland security news.',
        ],
        [
            'name'        => 'Border & Immigration',
            'slug'        => 'border-immigration',
            'description' => 'Border security, immigration enforcement, and immigration policy news.',
        ],
        [
            'name'        => '2nd Amendment',
            'slug'        => '2nd-amendment',
            'description' => 'Gun rights, Second Amendment news, legislation, and self-defense stories.',
        ],
        [
            'name'        => 'Faith & Culture',
            'slug'        => 'faith-culture',
            'description' => 'Religious freedom, culture war news, education, and American values.',
        ],
        [
            'name'        => 'Election Integrity',
            'slug'        => 'election-integrity',
            'description' => 'Election security, voting rights, voter fraud, and electoral system news.',
        ],
        [
            'name'        => 'Opinion',
            'slug'        => 'opinion',
            'description' => 'Conservative opinion, analysis, and commentary from FAFO contributors.',
        ],
        [
            'name'        => 'Deep State',
            'slug'        => 'deep-state',
            'description' => 'Government accountability, bureaucratic overreach, and establishment exposés.',
        ],
        [
            'name'        => 'Big Tech',
            'slug'        => 'big-tech',
            'description' => 'Tech censorship, Silicon Valley bias, social media, and free speech.',
        ],
        [
            'name'        => 'Healthcare',
            'slug'        => 'healthcare',
            'description' => 'Medical freedom, health policy, FDA/CDC news, and healthcare legislation.',
        ],
        [
            'name'        => 'Energy',
            'slug'        => 'energy',
            'description' => 'Energy independence, oil, gas, coal, and anti-green-energy policy news.',
        ],
    ];

    foreach ( $categories as $cat ) {
        if ( ! term_exists( $cat['slug'], 'category' ) ) {
            wp_insert_term( $cat['name'], 'category', [
                'slug'        => $cat['slug'],
                'description' => $cat['description'],
            ] );
        }
    }
}

// ============================================================
// CREATE VIDEO TAXONOMY TERMS
// ============================================================
function fafo_create_video_categories() {
    $video_cats = [
        [ 'name' => 'Breaking News',       'slug' => 'video-breaking'       ],
        [ 'name' => 'Interviews',          'slug' => 'video-interviews'      ],
        [ 'name' => 'Opinion & Analysis',  'slug' => 'video-opinion'         ],
        [ 'name' => 'Investigations',      'slug' => 'video-investigations'  ],
        [ 'name' => 'Events & Rallies',    'slug' => 'video-events'          ],
        [ 'name' => 'Live Coverage',       'slug' => 'video-live'            ],
        [ 'name' => 'Short Clips',         'slug' => 'video-clips'           ],
    ];

    foreach ( $video_cats as $cat ) {
        if ( ! term_exists( $cat['slug'], 'video_category' ) ) {
            wp_insert_term( $cat['name'], 'video_category', [ 'slug' => $cat['slug'] ] );
        }
    }
}

// ============================================================
// CREATE STATIC PAGES
// ============================================================
function fafo_create_pages() {
    $pages = [
        // FAFO Network pages
        [ 'title' => 'About FAFO',        'slug' => 'about',        'template' => 'page-about.php'       ],
        [ 'title' => 'Our Team',          'slug' => 'team',         'template' => 'page-team.php'        ],
        [ 'title' => 'Advertise',         'slug' => 'advertise',    'template' => 'page-advertise.php'   ],
        [ 'title' => 'Press Room',        'slug' => 'press',        'template' => 'page-press.php'       ],
        [ 'title' => 'Contact Us',        'slug' => 'contact',      'template' => 'page-contact.php'     ],
        [ 'title' => 'Careers',           'slug' => 'careers',      'template' => 'page-careers.php'     ],
        [ 'title' => 'Newsletter',        'slug' => 'newsletter',   'template' => 'page-newsletter.php'  ],
        [ 'title' => 'FAFO Merch',        'slug' => 'merch',        'template' => 'page-merch.php'       ],
        // Legal pages
        [ 'title' => 'Privacy Policy',    'slug' => 'privacy-policy',   'template' => 'page-privacy.php'      ],
        [ 'title' => 'Terms of Service',  'slug' => 'terms-of-service', 'template' => 'page-terms.php'        ],
        [ 'title' => 'Cookie Policy',     'slug' => 'cookie-policy',    'template' => 'page-cookie.php'       ],
        [ 'title' => 'Corrections Policy','slug' => 'corrections',      'template' => 'page-corrections.php'  ],
        [ 'title' => 'DMCA / Takedowns',  'slug' => 'dmca',             'template' => 'page-dmca.php'         ],
        [ 'title' => 'Tip Line',          'slug' => 'tip-line',         'template' => 'page-tipline.php'      ],
        // Video hub
        [ 'title' => 'Video',             'slug' => 'video-hub',        'template' => 'page-video.php'        ],
    ];

    foreach ( $pages as $page_data ) {
        $existing = get_page_by_path( $page_data['slug'] );
        if ( ! $existing ) {
            $id = wp_insert_post( [
                'post_title'   => $page_data['title'],
                'post_name'    => $page_data['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ] );
            if ( $id && ! is_wp_error( $id ) ) {
                update_post_meta( $id, '_wp_page_template', $page_data['template'] );
            }
        }
        // After creating a page (or if it already exists), populate empty content
        $existing_id = $existing ? $existing->ID : ( isset($id) && ! is_wp_error($id) ? $id : 0 );
        if ( $existing_id ) {
            fafo_populate_single_page_content( $existing_id, $page_data['slug'] );
        }
    }
}

// ============================================================
// POPULATE PAGE EDITOR CONTENT (runs once per page, only if empty)
// ============================================================
function fafo_populate_single_page_content( $page_id, $slug ) {
    $page = get_post( $page_id );
    if ( ! $page ) return;

    // Skip if page already has content
    if ( ! empty( trim( strip_tags( $page->post_content ) ) ) ) return;

    $html = fafo_get_default_page_content( $slug );
    if ( ! $html ) return;

    wp_update_post( [
        'ID'           => $page_id,
        'post_content' => wp_slash( $html ),
    ] );
}

function fafo_get_default_page_content( $slug ) {
    switch ( $slug ) {

        case 'about':
            return '<!-- wp:heading --><h2>Our Mission</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News was founded on a single principle: <strong>America First.</strong> We exist to deliver the news that the mainstream media refuses to cover — the stories about border security, Second Amendment rights, election integrity, economic sovereignty, and the values that made this nation great.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>In an era of media censorship, shadow-banning, and institutional bias, FAFO News stands as an unapologetic voice for conservative Americans. We don\'t apologize for our perspective. We don\'t sanitize our reporting to appease globalists, activists, or Big Tech overlords.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>Our reporters, editors, and contributors come from newsrooms, military service, law enforcement, and the heartland of America. We understand the issues because we live them. We cover them because no one else will.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>FAFO isn\'t just a news outlet. It\'s a movement. A community of patriots who believe in the Constitution, the rule of law, and the unshakeable idea that America is worth fighting for.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Our Core Values</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>America First</strong> — Every story is filtered through the lens of what is best for the American people — not globalist interests, not foreign donors, not corporate advertisers.</li><li><strong>Truth Over Comfort</strong> — We report the facts as they are, not as the establishment wishes them to be. Uncomfortable truths are still truths.</li><li><strong>Editorial Independence</strong> — No government grants. No Soros funding. No corporate strings. FAFO is funded by patriots, for patriots.</li><li><strong>Community First</strong> — Our readers aren\'t just consumers — they are our community, our tipsters, our family.</li><li><strong>Corrections Policy</strong> — When we get something wrong, we say so clearly and correct it prominently. Accountability matters.</li><li><strong>Source Protection</strong> — Whistleblowers and patriots who bring us stories can trust that we will protect their identities.</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>What We Cover</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>Politics &amp; Government</strong> — Washington D.C. news, legislation, executive actions, and political analysis from a conservative lens.</li><li><strong>Economy &amp; Finance</strong> — Jobs, inflation, trade policy, fiscal responsibility, and economic stories that affect your wallet.</li><li><strong>National Security</strong> — Military strength, foreign threats, intelligence, and homeland defense.</li><li><strong>Border &amp; Immigration</strong> — The southern border crisis, immigration enforcement, and America\'s sovereignty.</li><li><strong>2nd Amendment Rights</strong> — Gun rights legislation, self-defense stories, and the constant fight to protect your rights.</li><li><strong>Faith &amp; Culture</strong> — Religious liberty, the culture war, education, and traditional American values.</li><li><strong>Election Integrity</strong> — Voter ID, election security, fraud investigations, and protecting the ballot.</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>Our Story</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News was born from frustration. Frustration with a mainstream media that had abandoned journalism for activism. Frustration with Big Tech platforms that silenced conservative voices. Frustration with a political class that had forgotten who they work for.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>A small group of patriot journalists, veterans, and concerned citizens came together with one goal: build the news outlet that America actually needs. Not another controlled-opposition outlet that pretends to be conservative while playing by establishment rules. A real outlet, with real reporters, covering real stories.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>FAFO — For America First Only — is more than a name. It\'s a statement. It\'s a commitment. It\'s a challenge to the fake news industrial complex that says patriots don\'t deserve a voice. We\'re here. We\'re not going anywhere. <strong>FAFO.</strong></p><!-- /wp:paragraph -->';

        case 'advertise':
            return '<!-- wp:heading --><h2>Our Audience</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News reaches millions of engaged, patriotic American conservatives every month. Our readers are decision-makers, veterans, small business owners, and politically active Americans who trust FAFO News for their daily briefings.</p><!-- /wp:paragraph -->
<!-- wp:list --><ul><li><strong>2.4M+</strong> Monthly Unique Visitors</li><li><strong>8.7M+</strong> Monthly Page Views</li><li><strong>340K+</strong> Email Subscribers</li><li><strong>94%</strong> Conservative / Center-Right audience</li><li><strong>72%</strong> Ages 35–65</li><li><strong>68%</strong> College educated</li><li><strong>64%</strong> Household income $75K+</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>Advertising Packages</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We offer multiple advertising formats to fit every budget and campaign goal:</p><!-- /wp:paragraph -->
<!-- wp:list --><ul><li><strong>Banner Advertising</strong> — Display banners (728×90, 300×250, 300×600) across all pages. Guaranteed impressions with detailed reporting.</li><li><strong>Newsletter Sponsorship</strong> — Reach our 340,000+ email subscribers with dedicated sponsorship placements in our daily and weekly briefings.</li><li><strong>Sponsored Content</strong> — Long-form branded articles written by our team, clearly labeled as sponsored, published on our platform.</li><li><strong>Podcast Sponsorship</strong> — Audio ads and live reads on FAFO audio content.</li><li><strong>Social Media Amplification</strong> — Boosted posts and mentions across our social channels.</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>Why Advertise With FAFO?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Brand-safe conservative media. Your ads appear alongside content that aligns with American values — not woke corporate messaging. Our audience is fiercely loyal and highly engaged, with average session times well above industry benchmarks.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>To request a media kit or discuss custom packages, email us at <strong>advertise@foramericafirstonly.com</strong> or use our contact form.</p><!-- /wp:paragraph -->';

        case 'press':
            return '<!-- wp:heading --><h2>Press &amp; Media Resources</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News is one of the fastest-growing conservative media outlets in America. Our reporting has been cited by members of Congress, syndicated by regional outlets, and shared by millions of American patriots.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Media Kit</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Our media kit includes audience demographics, reach statistics, advertising rates, and editorial guidelines. To request a copy, email <strong>press@foramericafirstonly.com</strong>.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Interview Requests</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO editorial staff and contributing analysts are available for interviews, panels, and commentary. Please submit all requests to our press team with your outlet name, topic, and desired date/time.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Editorial Guidelines</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News follows AP Style with conservative editorial standards. We correct errors promptly and prominently. We do not accept advertiser influence on editorial decisions. Full guidelines available upon request.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Press Contact</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Email: <strong>press@foramericafirstonly.com</strong><br>For urgent press inquiries, include URGENT in the subject line.</p><!-- /wp:paragraph -->';

        case 'careers':
            return '<!-- wp:paragraph --><p>FAFO News is one of the fastest-growing conservative media outlets in America, and we\'re always looking for bold, fearless talent who refuses to be silenced. We offer competitive compensation, editorial freedom, and the satisfaction of doing journalism that actually matters.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p>We are a remote-first team of patriots, veterans, and journalists who understand that the fight for America\'s future is also a fight for the truth. If you believe in the mission — apply.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Why Work at FAFO?</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li>Editorial freedom — no corporate gatekeepers, no censorship of conservative voices</li><li>Remote-first with flexible schedules for most roles</li><li>Competitive pay commensurate with experience</li><li>Be part of a growing, mission-driven newsroom</li><li>Work with a team that puts America First — every day</li></ul><!-- /wp:list -->';

        case 'newsletter':
            return '<!-- wp:heading --><h2>Why Subscribe?</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>Breaking News Alerts</strong> — Be the first to know when major stories break, before Big Tech can bury them.</li><li><strong>Daily Briefing</strong> — Our morning briefing covers the top stories you need to know, curated by FAFO editors.</li><li><strong>Exclusive Investigations</strong> — Subscribers get early access to our investigative reports before they go live.</li><li><strong>Weekly Deep Dives</strong> — Long-form analysis of the biggest issues facing America, delivered every Sunday.</li><li><strong>Zero Censorship</strong> — Email is the last uncensored channel. No algorithm decides what you see.</li></ul><!-- /wp:list -->
<!-- wp:paragraph --><p>Join over 340,000 patriotic Americans who trust FAFO News for their daily dose of truth. No spin. No agenda. Just the facts — and the stories the mainstream media refuses to tell.</p><!-- /wp:paragraph -->';

        case 'contact':
            return '<!-- wp:paragraph --><p>We want to hear from patriots. Whether you have a news tip, a story idea, a correction, or a general inquiry — our team reads every message. Use the form below or reach out directly to the appropriate department.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Contact Departments</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>Newsroom / General</strong> — news@foramericafirstonly.com</li><li><strong>News Tips</strong> — tips@foramericafirstonly.com (confidential)</li><li><strong>Advertising</strong> — advertise@foramericafirstonly.com</li><li><strong>Legal / DMCA</strong> — legal@foramericafirstonly.com</li><li><strong>Careers</strong> — careers@foramericafirstonly.com</li><li><strong>Press / Media</strong> — press@foramericafirstonly.com</li></ul><!-- /wp:list -->';

        case 'tip-line':
            return '<!-- wp:paragraph --><p>Do you have information about government corruption, election fraud, Deep State operations, border coverups, or stories the mainstream media refuses to touch? We want to hear from you. Your identity is fully protected.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Security &amp; Confidentiality</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>All tips submitted through our tip line are confidential. We use TLS encryption on all form submissions. We do not share tip-source information with any government agency, law enforcement, or third party without a court order we will fight with every legal tool available.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Maximum Anonymity Tips</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li>Use the <a href="https://www.torproject.org/" rel="noopener noreferrer">Tor Browser</a> to access this page</li><li>Connect over a public Wi-Fi network not associated with you</li><li>Do not use a device issued by your employer</li><li>Do not include identifying information in your tip unless necessary</li><li>Consider using a new ProtonMail or Tutanota account if emailing directly</li></ul><!-- /wp:list -->';

        case 'team':
            return '<!-- wp:paragraph --><p>Patriots, veterans, and journalists committed to delivering the truth without apology. Our team spans the country — from Washington D.C. to the southern border — bringing you the stories that matter to real Americans.</p><!-- /wp:paragraph -->';

        case 'privacy-policy':
            return '<!-- wp:paragraph --><p><em>Last Updated: January 1, 2025 | Effective Date: January 1, 2025</em></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Introduction</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News ("FAFO," "we," "us," or "our") operates the website at foramericafirstonly.com. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our site or use our services. If you disagree with its terms, please discontinue use of the site.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Information We Collect</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p><strong>Information You Provide:</strong> When you subscribe to our newsletter, submit a contact form, post a comment, or interact with our site, we collect information you directly provide, including name and email address.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>Automatically Collected Information:</strong> When you visit our site, we automatically collect information about your device including your web browser, IP address, and time zone. We also collect information about individual web pages you view and referring URLs.</p><!-- /wp:paragraph -->
<!-- wp:paragraph --><p><strong>Cookies:</strong> We use cookies and similar tracking technologies to collect information about your browsing behavior. You can instruct your browser to refuse all cookies or indicate when a cookie is being sent.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>How We Use Your Information</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li>To send newsletters, breaking news alerts, and communications you opted into</li><li>To respond to inquiries and customer service requests</li><li>To analyze site usage and improve our content and services</li><li>To process transactions when applicable</li><li>To comply with legal obligations</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>Information Sharing</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We do not sell, trade, or rent your personal information to third parties. We may share information with trusted service providers who assist in operating our website (hosting, email delivery, analytics), subject to confidentiality agreements. We may disclose information if required by law or to protect our rights.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Data Retention</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We retain your personal information for as long as your account is active or as needed to provide services. You may request deletion of your personal data by contacting us at legal@foramericafirstonly.com.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Your Rights</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>You have the right to access, correct, or delete your personal information. You may opt out of marketing communications at any time using the unsubscribe link in any email or by contacting us directly. California residents may have additional rights under the CCPA.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Contact Us</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>For privacy-related questions, contact us at: legal@foramericafirstonly.com</p><!-- /wp:paragraph -->';

        case 'terms-of-service':
            return '<!-- wp:paragraph --><p><em>Last Updated: January 1, 2025</em></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Acceptance of Terms</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>By accessing or using FAFO News (foramericafirstonly.com), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our site.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Use of the Site</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>You may use our site for lawful purposes only. You may not use the site to transmit unlawful, harmful, or objectionable content. You may not attempt to gain unauthorized access to any portion of the site. You may not use automated tools to scrape or harvest content without permission.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Intellectual Property</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>All content on this site — including articles, graphics, logos, and videos — is the property of FAFO News or its content suppliers and is protected by copyright law. You may share links to our content. You may not reproduce our articles in full without written permission.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Disclaimer of Warranties</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News is provided "as is" without warranties of any kind. We do not warrant that the site will be uninterrupted or error-free. We are not responsible for the accuracy of user-submitted content or comments.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Limitation of Liability</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News shall not be liable for any indirect, incidental, or consequential damages arising from your use of this site. Our total liability to you shall not exceed $100.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Changes to Terms</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We reserve the right to modify these terms at any time. Continued use of the site after changes constitutes acceptance of the new terms.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Contact</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Questions about these terms? Contact us at: legal@foramericafirstonly.com</p><!-- /wp:paragraph -->';

        case 'cookie-policy':
            return '<!-- wp:paragraph --><p><em>Last Updated: January 1, 2025</em></p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>What Are Cookies?</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Cookies are small text files placed on your device when you visit a website. They allow websites to recognize your browser and remember certain information about your visit.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>How We Use Cookies</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>Essential Cookies</strong> — Required for the site to function. These include session cookies and security tokens.</li><li><strong>Analytics Cookies</strong> — Help us understand how visitors use the site (e.g., Google Analytics). These are anonymized.</li><li><strong>Preference Cookies</strong> — Remember your settings and preferences between visits.</li><li><strong>Marketing Cookies</strong> — Used to deliver relevant advertisements. You can opt out at any time.</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>Managing Cookies</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>You can control and manage cookies through your browser settings. Note that disabling cookies may affect the functionality of our site. Most browsers allow you to refuse cookies, delete existing cookies, or be notified when a cookie is being set.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Third-Party Cookies</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We may use third-party services (such as Google Analytics, social media share buttons, and advertising networks) that set their own cookies. These are subject to the privacy policies of those third parties.</p><!-- /wp:paragraph -->';

        case 'corrections':
            return '<!-- wp:heading --><h2>Our Commitment to Accuracy</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News is committed to accurate, fair, and truthful reporting. When we make errors, we correct them promptly, prominently, and transparently. This policy governs how we handle corrections, clarifications, and updates to our reporting.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Reporting an Error</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>If you believe we have made an error in a story, we encourage you to contact us. Provide the URL of the article, the specific error, and any supporting evidence. We review every correction request and respond within 48 hours. Email: corrections@foramericafirstonly.com</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Types of Corrections</h2><!-- /wp:heading -->
<!-- wp:list --><ul><li><strong>Factual Corrections</strong> — When verifiable facts are wrong, we correct the article and add a correction notice at the top noting what was changed and when.</li><li><strong>Clarifications</strong> — When language was ambiguous or unclear (but not factually wrong), we clarify and note the update.</li><li><strong>Updates</strong> — When a story develops after publication, we update the article and note when it was updated.</li><li><strong>Retractions</strong> — In rare cases where a story cannot be substantiated, we retract it entirely and publish a retraction notice.</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>Editorial Standards</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News distinguishes between news reporting and opinion/commentary. Opinion pieces are clearly labeled. All factual claims in news articles are subject to this corrections policy. Opinions are not "corrected" — they may be responded to, rebutted, or updated with new information.</p><!-- /wp:paragraph -->';

        case 'dmca':
            return '<!-- wp:heading --><h2>DMCA Notice &amp; Takedown Policy</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>FAFO News respects the intellectual property rights of others and expects the same from users of our site. In accordance with the Digital Millennium Copyright Act (DMCA), we will respond to notices of alleged copyright infringement that comply with applicable law.</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Filing a DMCA Takedown Notice</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>If you believe content on our site infringes your copyright, send a written notice to our DMCA Agent containing:</p><!-- /wp:paragraph -->
<!-- wp:list --><ul><li>Your physical or electronic signature</li><li>Identification of the copyrighted work claimed to be infringed</li><li>Identification of the infringing material and its URL on our site</li><li>Your contact information (name, address, phone, email)</li><li>A statement that you have a good faith belief that the use is not authorized</li><li>A statement that the information is accurate and you are the copyright owner or authorized agent</li></ul><!-- /wp:list -->
<!-- wp:heading --><h2>DMCA Agent Contact</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>DMCA Agent: Legal Department<br>Email: legal@foramericafirstonly.com<br>Subject line: DMCA Takedown Notice</p><!-- /wp:paragraph -->
<!-- wp:heading --><h2>Counter-Notice</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>If you believe your content was removed in error, you may file a counter-notice. A valid counter-notice must include your contact information, identification of the removed content, a statement under penalty of perjury that the content was removed by mistake, and consent to jurisdiction. Email: legal@foramericafirstonly.com</p><!-- /wp:paragraph -->';

        default:
            return '';
    }
}
