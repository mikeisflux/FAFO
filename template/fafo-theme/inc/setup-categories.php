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
    }
}
