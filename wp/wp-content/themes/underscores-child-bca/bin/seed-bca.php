<?php
/**
 * BCA Partners — Seed data via wp-cli (simulating user admin input).
 *
 * Run: wp eval-file bin/seed-bca.php
 *
 * Does:
 *   1. Upload all images from assets/ to media library → get IDs
 *   2. Set Theme Settings (general, header, footer, social)
 *   3. Create nav menu with 8 items
 *   4. Create 5 pages with templates + ACF data
 *   5. Create 6 service CPT posts + ACF
 *   6. Create 4 project CPT posts + ACF
 *   7. Create 3 research CPT posts + ACF
 *   8. Create 2 career CPT posts + ACF
 *   9. Create 2 leader_group taxonomy terms
 *  10. Create 5 leader CPT posts + ACF
 *  11. Create 4 categories + 6 news posts
 *  12. Set static front page + posts page in Reading settings
 *
 * Idempotent: re-running skips already-created content.
 *
 * @package BCA_Child
 */

defined('WP_CLI') || exit('Run via wp eval-file');

if (!function_exists('update_field')) {
    WP_CLI::error('ACF Pro not active.');
}

$LOG = [];

/**
 * Find or create by title + post type.
 */
function bca_find_or_create_post(string $title, string $post_type, array $args = []): int
{
    $existing = get_posts([
        'post_type'      => $post_type,
        'title'          => $title,
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ]);
    if (!empty($existing)) {
        return (int) $existing[0];
    }
    $post_id = wp_insert_post(array_merge([
        'post_title'  => $title,
        'post_type'    => $post_type,
        'post_status'  => 'publish',
    ], $args), true);
    if (is_wp_error($post_id)) {
        WP_CLI::warning("Failed to create $post_type: $title — " . $post_id->get_error_message());
        return 0;
    }
    return (int) $post_id;
}

/**
 * Upload an image to media library from a file path. Returns attachment ID.
 */
function bca_upload_image(string $file_path, string $title = ''): int
{
    if (!file_exists($file_path)) {
        return 0;
    }
    $existing = get_posts([
        'post_type'      => 'attachment',
        'title'          => $title ?: basename($file_path),
        'posts_per_page' => 1,
        'post_status'    => 'inherit',
        'fields'         => 'ids',
    ]);
    if (!empty($existing)) {
        return (int) $existing[0];
    }
    $upload = wp_upload_dir();
    $filename = basename($file_path);
    $dest = $upload['path'] . '/' . $filename;
    if (!copy($file_path, $dest)) {
        return 0;
    }
    $wp_filetype = wp_check_filetype($filename);
    $attachment = [
        'guid'           => $upload['url'] . '/' . $filename,
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => $title ?: pathinfo($filename, PATHINFO_FILENAME),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];
    $attach_id = wp_insert_attachment($attachment, $dest);
    if (!$attach_id) {
        return 0;
    }
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attach_id, $dest);
    wp_update_attachment_metadata($attach_id, $attach_data);
    return (int) $attach_id;
}

/**
 * Find or create a term.
 */
function bca_find_or_create_term(string $name, string $taxonomy): int
{
    $existing = term_exists($name, $taxonomy);
    if ($existing) {
        return (int) $existing['term_id'];
    }
    $created = wp_insert_term($name, $taxonomy);
    if (is_wp_error($created)) {
        return 0;
    }
    return (int) $created['term_id'];
}

WP_CLI::log('===== 1. Upload images =====');

$ASSETS = '/Users/macbook/Desktop/BCA Partners Design System/wp/wp-content/themes/underscores-child-bca/assets/images';
$IMG = [];  // name => attachment id

// Hero + branding
$IMG['hero']       = bca_upload_image("$ASSETS/hero.jpg", 'Hero');
$IMG['contact-bg'] = bca_upload_image("$ASSETS/contact-bg.jpg", 'Contact Background');
$IMG['career-hero']= bca_upload_image("$ASSETS/career-hero.jpg", 'Career Hero');
$IMG['leadership-hero'] = bca_upload_image("$ASSETS/leadership-hero.jpg", 'Leadership Hero');

// Service images
$IMG['service-strategy']    = bca_upload_image("$ASSETS/service-strategy.jpg", 'Service Strategy');
$IMG['service-market-entry']= bca_upload_image("$ASSETS/service-market-entry.jpg", 'Service Market Entry');
$IMG['service-research']    = bca_upload_image("$ASSETS/service-research.jpg", 'Service Research');
$IMG['service-capital']     = bca_upload_image("$ASSETS/service-capital.jpg", 'Service Capital Raising');
$IMG['service-restructuring']= bca_upload_image("$ASSETS/service-restructuring.jpg", 'Service Restructuring');
$IMG['service-extra']       = bca_upload_image("$ASSETS/project-strategy.jpg", 'Service Research Strategy');

// Project images
$IMG['project-strategy']      = bca_upload_image("$ASSETS/project-strategy.jpg", 'Project MM Technology');
$IMG['project-dragonfruit']   = bca_upload_image("$ASSETS/project-dragonfruit.jpg", 'Project Agritech');
$IMG['project-market-entry']  = bca_upload_image("$ASSETS/service-market-entry.jpg", 'Project Global Payments');
$IMG['project-restructuring'] = bca_upload_image("$ASSETS/service-restructuring.jpg", 'Project Regional Manufacturer');

// Research images
$IMG['research-fintech']  = bca_upload_image("$ASSETS/research-1.jpg", 'Research Fintech');
$IMG['research-msme']     = bca_upload_image("$ASSETS/feature-msme.jpg", 'Research MSME');
$IMG['research-agri']     = bca_upload_image("$ASSETS/project-dragonfruit.jpg", 'Research Agriculture');

// About page images
$IMG['about-hero']    = bca_upload_image("$ASSETS/about-hero.jpg", 'About Hero');
$IMG['about-vision']  = bca_upload_image("$ASSETS/about-vision.jpg", 'About Vision');
$IMG['about-mission'] = bca_upload_image("$ASSETS/about-mission.jpg", 'About Mission');
$IMG['about-belief']  = bca_upload_image("$ASSETS/about-belief.jpg", 'About Belief');
$IMG['about-strip']   = bca_upload_image("$ASSETS/about-strip.jpg", 'About Strip');

// Strength icons
$IMG['icon-strength-1'] = bca_upload_image("$ASSETS/icon-strength-1.png", 'Strength Icon 1');
$IMG['icon-strength-2'] = bca_upload_image("$ASSETS/icon-strength-2.png", 'Strength Icon 2');
$IMG['icon-strength-3'] = bca_upload_image("$ASSETS/icon-strength-3.png", 'Strength Icon 3');

// Vision/Mission/Values additional images
$IMG['vmcv-1'] = $IMG['about-vision'];
$IMG['vmcv-2'] = $IMG['about-mission'];
$IMG['vmcv-3'] = $IMG['service-extra'];
$IMG['vmcv-4'] = $IMG['service-research'];
$IMG['vmcv-5'] = $IMG['service-strategy'];
$IMG['vmcv-6'] = $IMG['service-restructuring'];

// News images
$IMG['news-1'] = bca_upload_image("$ASSETS/project-strategy.jpg", 'News E-wallet');
$IMG['news-2'] = bca_upload_image("$ASSETS/leadership-hero.jpg", 'News Team Expansion');
$IMG['news-3'] = bca_upload_image("$ASSETS/service-market-entry.jpg", 'News M&A Forum');
$IMG['news-4'] = bca_upload_image("$ASSETS/research-1.jpg", 'News Market Entry');
$IMG['news-5'] = bca_upload_image("$ASSETS/project-dragonfruit.jpg", 'News Agriculture');
$IMG['news-6'] = bca_upload_image("$ASSETS/service-restructuring.jpg", 'News Growth');

// Leader photos
$IMG['leader-binh']  = bca_upload_image("$ASSETS/leader-binh.jpg", 'Binh Pham');
$IMG['leader-thuy']  = bca_upload_image("$ASSETS/leader-thuy.png", 'Thuy Huynh');
$IMG['leader-chau']  = bca_upload_image("$ASSETS/leader-chau.png", 'Chau Tran');
$IMG['leader-andy']  = bca_upload_image("$ASSETS/leader-andy.png", 'Andy Phan');
$IMG['leader-nga']   = bca_upload_image("$ASSETS/leader-nga.png", 'Nga Do');

WP_CLI::log('Images uploaded: ' . count(array_filter($IMG)));

WP_CLI::log('===== 2. Theme Settings =====');

// Logo (use leadership-hero for now as a placeholder; user will replace)
$logo_id = bca_upload_image("$ASSETS/contact-form.jpg", 'BCA Partners Logo');
$logo_white_id = bca_upload_image("$ASSETS/hero.jpg", 'BCA Partners Logo White');
$favicon_id = $logo_id;

update_field('general_section', [
    'logo'         => $logo_id,
    'logo_white'   => $logo_white_id,
    'favicon'      => $favicon_id,
    'site_name'    => 'BCA Partners',
    'slogan'       => 'Fostering Business Evolution',
    'hotline'      => '+84 28 3512 4414',
    'email'        => 'info@bcapartners.com.vn',
    'address'      => "Unit G2, FOSCO Building\n06 Phung Khac Khoan\nDakao Ward, District 1, HCMC, Vietnam",
    'copyright'    => '© 2024 BCA Partners. All rights reserved.',
], 'option');

update_field('header_section', [
    'cta_label' => 'Contact us',
    'cta_url'   => home_url('/contact/'),
], 'option');

update_field('footer_section', [
    'description'    => 'A Vietnam-based consulting firm specialising in M&As, Strategy, Corporate Restructuring, Market Entry, Capital Raising and Financial Solutions.',
    'office_label'   => 'Office',
    'office_address' => "Unit G2, FOSCO Building\n06 Phung Khac Khoan\nDakao Ward, District 1\nHo Chi Minh City, Vietnam",
], 'option');

update_field('social_links', [
    [
        'platform' => 'linkedin',
        'url'      => 'https://www.linkedin.com/company/bca-partners',
    ],
    [
        'platform' => 'facebook',
        'url'      => 'https://www.facebook.com/bcapartners',
    ],
], 'option');

WP_CLI::log('Theme settings written.');

WP_CLI::log('===== 3. Create nav menu =====');

$menu_name = 'Primary Navigation';
$menu_id = wp_get_nav_menu_object($menu_name);
if (!$menu_id) {
    $menu_id = wp_create_nav_menu($menu_name);
    if (is_wp_error($menu_id)) {
        WP_CLI::error('Failed to create menu: ' . $menu_id->get_error_message());
    }
}
WP_CLI::log("Menu ID: $menu_id");

$nav_items = [
    ['title' => 'Home',         'url' => home_url('/')],
    ['title' => 'About us',     'url' => home_url('/about/')],
    ['title' => 'Our services', 'url' => home_url('/services/')],
    ['title' => 'Projects',     'url' => home_url('/projects/')],
    ['title' => 'Research',     'url' => home_url('/research/')],
    ['title' => 'News',         'url' => home_url('/news/')],
    ['title' => 'Career',       'url' => home_url('/career/')],
    ['title' => 'Contact us',   'url' => home_url('/contact/')],
];

// Check existing items
$existing_items = wp_get_nav_menu_items($menu_id);
if (empty($existing_items)) {
    foreach ($nav_items as $item) {
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'  => $item['title'],
            'menu-item-url'    => $item['url'],
            'menu-item-status' => 'publish',
        ]);
    }
    WP_CLI::log('Menu items added: ' . count($nav_items));
} else {
    WP_CLI::log('Menu items already exist, skipping.');
}

$locations = get_theme_mod('nav_menu_locations');
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);

WP_CLI::log('===== 4. Create pages =====');

// 4a. Home (static front page) — WP creates a placeholder
$home_id = bca_find_or_create_post('Home', 'page', ['post_content' => '']);
update_option('show_on_front', 'page');
update_option('page_on_front', $home_id);

// 4b. News list page (used for blog posts)
$news_page_id = bca_find_or_create_post('News', 'page', ['post_content' => '']);
update_option('page_for_posts', $news_page_id);

// 4c. About page
$about_id = bca_find_or_create_post('About', 'page', [
    'post_content' => 'BCA Partners is a consulting firm specialising in M&As, Strategy, Corporate Restructuring, Market Entry, Capital Raising and other Financial Solutions in the Vietnam market. We deliver value to customers through practical solutions and best service experience.',
]);
update_post_meta($about_id, '_wp_page_template', 'page-template/template-about.php');

update_field('hero_settings', [
    'is_show'    => 1,
    'heading'    => 'Company Overview',
    'subheading' => 'BCA Partners is a consulting firm specialising in M&As, Strategy, Corporate Restructuring, Market Entry, and other Financial Solutions in the Vietnam market.',
    'image'      => $IMG['about-hero'],
], $about_id);

update_field('strengths_settings', [
    'is_show'    => 1,
    'heading'    => 'Our Strengths',
    'side_image' => $IMG['about-vision'],
    'items'      => [
        [
            'icon'  => $IMG['icon-strength-3'],
            'title' => 'Local market insights',
            'body'  => "BCA's professional team, with extensive working experience in the local market, is knowledgeable about the business environment and regulations in Vietnam.",
        ],
        [
            'icon'  => $IMG['icon-strength-1'],
            'title' => 'Strong expertise and highly diversified team members',
            'body'  => "BCA's founders and Advisory Board together have many years of combined experience across different business sectors, allowing BCA to provide clients with practical solutions that are highly applicable to the Vietnam market environment.",
        ],
        [
            'icon'  => $IMG['icon-strength-2'],
            'title' => 'Extensive domestic and oversea relationships',
            'body'  => 'BCA has close relationships with funds, investment banks, commercial banks, auditing companies, securities firms, and domestic and foreign economic organizations — making the firm an ideal one-stop solution to the problems faced by its clients.',
        ],
    ],
], $about_id);

update_field('belief_settings', [
    'is_show' => 1,
    'heading' => 'Our Belief',
    'quote'   => 'Whether you want to make a great change, tackle a challenge, or capture an opportunity, BCA is with you. We believe that our expertise, experience, and network can help provide the most practical advice and bring value to your organization.',
    'image'   => $IMG['about-belief'],
], $about_id);

update_field('vmcv_settings', [
    'is_show' => 1,
    'heading' => 'Vision, Mission and Core Values',
    'items'   => [
        ['image' => $IMG['vmcv-1'], 'title' => 'Vision',  'body' => 'To become one of the most trusted advisors to corporates in Vietnam.'],
        ['image' => $IMG['vmcv-2'], 'title' => 'Mission', 'body' => 'To deliver value to customers through practical solutions and best service experience.'],
        ['image' => $IMG['vmcv-3'], 'title' => 'Integrity', 'body' => 'Integrity is always our top priority. Sustainable success is achieved with integrity — across employees, clients and partners.'],
        ['image' => $IMG['vmcv-4'], 'title' => 'Social Responsibility', 'body' => 'We are committed to long-term development and sustainability, pairing economic benefits with social responsibility.'],
        ['image' => $IMG['vmcv-5'], 'title' => 'Passion', 'body' => 'Passion is the driving force that moves us forward. It is our honour to help clients solve their business problems.'],
        ['image' => $IMG['vmcv-6'], 'title' => 'Professionalism', 'body' => 'With professionalism, we bring value to clients and build trust with partners and stakeholders.'],
    ],
], $about_id);

update_field('contact_band_settings', [
    'is_show' => 1,
    'image'   => $IMG['contact-bg'],
], $about_id);

WP_CLI::log("About page: $about_id");

// 4d. Services page
$services_id = bca_find_or_create_post('Services', 'page', [
    'post_content' => "Satisfying our clients' needs is our top priority. BCA understands that our clients' successes are our success, so we do our best to bring value to every engagement — building relationships on honesty and fairness.",
]);
update_post_meta($services_id, '_wp_page_template', 'page-template/template-services.php');
update_field('hero_settings', [
    'is_show'    => 1,
    'heading'    => 'Our Services',
    'subheading' => "Satisfying our clients' needs is our top priority. BCA understands that our clients' successes are our success, so we do our best to bring value to every engagement — building relationships on honesty and fairness.",
], $services_id);
update_field('contact_band_settings', [
    'is_show' => 1,
    'image'   => $IMG['contact-bg'],
], $services_id);
WP_CLI::log("Services page: $services_id");

// 4e. Contact page
$contact_id = bca_find_or_create_post('Contact', 'page', [
    'post_content' => '',
]);
update_post_meta($contact_id, '_wp_page_template', 'page-template/template-contact.php');
update_field('hero_settings', [
    'is_show'    => 1,
    'heading'    => 'Contact Us',
    'subheading' => 'Fill out the form to contact our team — we will get back within one business day.',
    'image'      => $IMG['contact-bg'],
], $contact_id);
update_field('form_settings', [
    'is_show'         => 1,
    'heading'         => 'Get in touch',
    'intro'           => 'Tell us about your business challenge. We will respond within one business day.',
    'recipient_email' => 'info@bcapartners.com.vn',
    'success_message' => 'Thank you — your message has been sent. We will be in touch shortly.',
], $contact_id);
WP_CLI::log("Contact page: $contact_id");

// 4f. Privacy page
$privacy_id = bca_find_or_create_post('Privacy Policy', 'page');
update_post_meta($privacy_id, '_wp_page_template', 'page-template/template-privacy.php');
update_field('hero_settings', [
    'is_show'      => 1,
    'heading'      => 'Privacy Policy',
    'last_updated' => 'Last updated: 1 January 2024',
], $privacy_id);
update_field('content_settings', [
    'body' => '<p>BCA Partners ("we", "us", "our") is committed to protecting the privacy of visitors to our website and clients who share information with us. This policy explains what we collect, how we use it, and the choices you have.</p>
<h2>Information we collect</h2>
<p>We collect information you provide directly — such as your name, company, email, phone number and any message you send through our contact forms — as well as limited technical data (e.g. IP address, browser type) collected automatically for security and analytics.</p>
<h2>How we use your information</h2>
<ul>
<li>To respond to enquiries and provide our advisory services.</li>
<li>To communicate updates, insights and news you have requested.</li>
<li>To operate, maintain and improve our website.</li>
<li>To comply with legal and regulatory obligations.</li>
</ul>
<h2>Sharing &amp; disclosure</h2>
<p>We do not sell your personal data. We may share information with trusted service providers who support our operations, or where required by law. Any partners are bound by confidentiality obligations.</p>
<h2>Data retention &amp; security</h2>
<p>We retain personal data only as long as necessary for the purposes described above, and apply appropriate technical and organisational measures to protect it against unauthorised access, loss or disclosure.</p>
<h2>Your rights</h2>
<p>You may request access to, correction of, or deletion of your personal data, and may withdraw consent to marketing communications at any time. To exercise these rights, contact us using the details below.</p>
<h2>Contact us</h2>
<p>Questions about this policy can be directed to <a href="mailto:info@bcapartners.com.vn">info@bcapartners.com.vn</a>, or Unit G2, FOSCO Building, 06 Phung Khac Khoan, Dakao Ward, District 1, HCMC, Vietnam.</p>',
], $privacy_id);
WP_CLI::log("Privacy page: $privacy_id");

// 4g. Vision/Mission/Values — full page (redirect or content-only)
$vmcv_page_id = bca_find_or_create_post('Vision, Mission & Core Values', 'page', [
    'post_content' => '<p>This page mirrors the section on the About page — please visit <a href="' . home_url('/about/') . '">About us</a> to read our Vision, Mission and Core Values.</p>',
]);
WP_CLI::log("Vision/Mission page: $vmcv_page_id");

WP_CLI::log('===== 5. Create services (CPT) =====');

$services_data = [
    ['title' => 'Mergers & Acquisitions (M&As)',         'image' => $IMG['service-strategy'],      'items' => ['Sell-side advisory', 'Buy-side advisory', 'Post-M&A business strategy', 'Leveraged buyouts']],
    ['title' => 'Strategy',                                'image' => $IMG['service-market-entry'],  'items' => ['Strategy consulting', 'Pre-IPO & IPO strategies', 'Project Management Office (PMO)']],
    ['title' => 'Market Entry',                            'image' => $IMG['service-research'],      'items' => ['Market intelligence', 'Market entry strategy', 'Diagnosis of local partner', 'Partner search']],
    ['title' => 'Capital Raising',                         'image' => $IMG['service-capital'],       'items' => ['Fund raising for listed & private companies', 'Medium and long-term loans', 'Bond issuances']],
    ['title' => 'Corporate Restructuring',                 'image' => $IMG['service-restructuring'], 'items' => ['Financial restructuring', 'Operational restructuring', 'Crisis management']],
    ['title' => 'Research',                                'image' => $IMG['service-extra'],         'items' => ['Industry & market research', 'Feasibility studies', 'Data & trend analysis']],
];

$service_ids = [];
foreach ($services_data as $svc) {
    $sid = bca_find_or_create_post($svc['title'], 'service', [
        'post_content' => 'We help clients ' . strtolower($svc['title']) . ' with deep local expertise and a global network of partners.',
    ]);
    if ($sid) {
        set_post_thumbnail($sid, $svc['image']);
        update_field('items', array_map(fn($i) => ['label' => $i], $svc['items']), $sid);
        $service_ids[$svc['title']] = $sid;
        WP_CLI::log("  Service: {$svc['title']} = $sid");
    }
}

WP_CLI::log('===== 6. Create projects (CPT) =====');

$projects_data = [
    [
        'title' => 'Strategy consulting & Implementation support',
        'eyebrow' => 'FINTECH PROJECTS',
        'client' => 'MM TECHNOLOGY',
        'image' => $IMG['project-strategy'],
        'challenge' => 'A leading e-wallet client in Vietnam needed independent strategic guidance to scale its user base and improve unit economics in an increasingly competitive market.',
        'approach' => 'BCA provided ongoing advisory across product, pricing and partnership strategy. We ran a deep-dive diagnostic, benchmarked against regional peers, and developed a 12-month roadmap with quarterly checkpoints.',
        'outcome' => 'The client achieved a 40% increase in MAU and secured a strategic partnership that unlocked a new growth vertical.',
    ],
    [
        'title' => 'Dragon fruit value chain Restructuring',
        'eyebrow' => 'MARKET RESEARCH SERVICES',
        'client' => 'AGRITECH VIETNAM',
        'image' => $IMG['project-dragonfruit'],
        'challenge' => 'A national staple export facing low peak-season prices, high logistics and storage costs.',
        'approach' => 'We designed a more resilient, higher-value chain and financing model — re-allocating cold-storage investment and introducing cooperative-grade post-harvest handling.',
        'outcome' => 'A roadmap ready for a multi-stakeholder pilot, expected to lift farmer net income by 15-20%.',
    ],
    [
        'title' => 'Vietnam market entry advisory',
        'eyebrow' => 'MARKET ENTRY',
        'client' => 'GLOBAL PAYMENTS',
        'image' => $IMG['project-market-entry'],
        'challenge' => 'An international payments leader entering the Vietnamese market needed clarity on regulatory landscape, partner landscape and go-to-market sequencing.',
        'approach' => 'BCA delivered a regulatory landscape review, identified 12 potential local partners, and built a phased go-to-market plan aligned with SBV licensing timelines.',
        'outcome' => 'A 24-month market entry roadmap that the client is currently executing with our continued support.',
    ],
    [
        'title' => 'Operational & financial restructuring',
        'eyebrow' => 'CORPORATE RESTRUCTURING',
        'client' => 'REGIONAL MANUFACTURER',
        'image' => $IMG['project-restructuring'],
        'challenge' => 'A regional manufacturer with declining margins and over-leveraged balance sheet required turnaround planning to restore profitability.',
        'approach' => 'BCA led a turnaround diagnostic covering cost optimisation, working capital release and balance-sheet restructuring options.',
        'outcome' => 'Identified 18% EBITDA uplift potential and prepared the business for an inbound growth-capital process.',
    ],
];

$project_ids = [];
foreach ($projects_data as $prj) {
    $pid = bca_find_or_create_post($prj['title'], 'project', [
        'post_content' => $prj['outcome'],
    ]);
    if ($pid) {
        set_post_thumbnail($pid, $prj['image']);
        update_field('eyebrow',  $prj['eyebrow'],  $pid);
        update_field('client',   $prj['client'],   $pid);
        update_field('challenge', $prj['challenge'], $pid);
        update_field('approach',  $prj['approach'],  $pid);
        update_field('outcome',   $prj['outcome'],   $pid);
        $project_ids[$prj['title']] = $pid;
        WP_CLI::log("  Project: {$prj['title']} = $pid");
    }
}

WP_CLI::log('===== 7. Create research (CPT) =====');

$research_data = [
    [
        'title' => "The future of payment in Vietnam's Fintech market",
        'eyebrow' => 'FINTECH',
        'image' => $IMG['research-fintech'],
        'excerpt' => "High internet penetration and a young, mobile-first population are fostering the next wave of digital payments — and reshaping who wins in financial services.",
        'content' => '<p>Vietnam\'s fintech market is in a rapid growth phase, with internet penetration exceeding 75% and a young, mobile-first consumer base driving the next wave of digital payments.</p><p>This research examines the regulatory landscape, key players, and the structural shifts reshaping competitive dynamics in payments, lending and wealthtech.</p><h2>Key findings</h2><ul><li>E-wallets are approaching saturation in Tier 1 — growth is moving to Tier 2/3.</li><li>QR-based interoperability is accelerating merchant adoption.</li><li>Embedded finance is the next battleground, not standalone apps.</li></ul>',
    ],
    [
        'title' => 'Closing the credit gap for Vietnamese MSMEs',
        'eyebrow' => 'MSME FINANCE',
        'image' => $IMG['research-msme'],
        'excerpt' => 'Micro, small and medium enterprises remain underserved by traditional bank lending. We map the barriers and the emerging alternative-finance models.',
        'content' => '<p>Vietnamese MSMEs contribute roughly 40% of GDP but receive a disproportionately small share of formal credit.</p><h2>What is blocking credit supply</h2><ul><li>Limited financial statements and collateral</li><li>Risk-aversion among commercial banks</li><li>Underdeveloped credit bureau coverage</li></ul><h2>What is changing</h2><ul><li>Alternative lenders using cash-flow underwriting</li><li>Embedded credit via e-commerce platforms</li><li>Government credit guarantee schemes</li></ul>',
    ],
    [
        'title' => 'Restructuring the dragon fruit value chain',
        'eyebrow' => 'AGRICULTURE',
        'image' => $IMG['research-agri'],
        'excerpt' => 'A national staple export facing low peak-season prices and high logistics costs — and a roadmap for a more resilient, higher-value chain.',
        'content' => '<p>Vietnam\'s dragon fruit industry has seen volatile pricing and rising logistics costs. This research proposes structural interventions across the value chain.</p><h2>Where value is lost</h2><ul><li>Cold-chain capacity is concentrated in low-volume periods</li><li>Post-harvest handling is inconsistent</li><li>Export market access is undiversified</li></ul><h2>Proposed roadmap</h2><ul><li>Shared cold-storage at the district level</li><li>Cooperative-led post-harvest standards</li><li>Diversified export channels (EU, US, regional)</li></ul>',
    ],
];

$research_ids = [];
foreach ($research_data as $r) {
    $rid = bca_find_or_create_post($r['title'], 'research', [
        'post_content' => $r['content'],
    ]);
    if ($rid) {
        set_post_thumbnail($rid, $r['image']);
        update_field('eyebrow', $r['eyebrow'], $rid);
        update_field('excerpt', $r['excerpt'], $rid);
        $research_ids[$r['title']] = $rid;
        WP_CLI::log("  Research: {$r['title']} = $rid");
    }
}

WP_CLI::log('===== 8. Create careers (CPT) =====');

$careers_data = [
    [
        'title' => 'Investment Banking Analyst / Associate',
        'type' => 'Full-time',
        'location' => 'District 1, HCMC',
        'desc' => 'Support M&A, capital raising and corporate restructuring engagements — financial modelling, valuation, due diligence and client materials.',
        'responsibilities' => '<ul><li>Build and maintain financial models for M&A and capital raising transactions</li><li>Prepare client materials including pitch decks, info memos and valuation summaries</li><li>Conduct industry and company research</li><li>Support due diligence workstreams</li></ul>',
        'requirements' => '<ul><li>Bachelor\'s degree in Finance, Economics, Business or related field</li><li>2-4 years of experience in investment banking, transaction advisory or related</li><li>Strong financial modelling and Excel skills</li><li>Fluency in Vietnamese and English</li></ul>',
    ],
    [
        'title' => 'Intern',
        'type' => 'Internship',
        'location' => 'District 1, HCMC',
        'desc' => 'Work alongside our consulting team on live market research and analysis. A hands-on introduction to advisory work in Vietnam.',
        'responsibilities' => '<ul><li>Support research projects across our service lines</li><li>Help prepare client deliverables and presentations</li><li>Conduct market and competitor analysis</li><li>Contribute to internal research initiatives</li></ul>',
        'requirements' => '<ul><li>Penultimate or final year undergraduate / graduate student</li><li>Strong analytical and writing skills</li><li>Curiosity about Vietnam\'s business landscape</li><li>Available 3-6 months full-time</li></ul>',
    ],
];

foreach ($careers_data as $c) {
    $cid = bca_find_or_create_post($c['title'], 'career', [
        'post_content' => $c['desc'],
    ]);
    if ($cid) {
        update_field('type',               $c['type'],               $cid);
        update_field('location',           $c['location'],           $cid);
        update_field('short_description',  $c['desc'],               $cid);
        update_field('responsibilities',   $c['responsibilities'],   $cid);
        update_field('requirements',       $c['requirements'],       $cid);
        WP_CLI::log("  Career: {$c['title']} = $cid");
    }
}

WP_CLI::log('===== 9. Create leader_group terms + leaders (CPT) =====');

$mgmt_term_id = bca_find_or_create_term('Management Team', 'leader_group');
$advisor_term_id = bca_find_or_create_term('Advisors', 'leader_group');
WP_CLI::log("  Terms: Management Team=$mgmt_term_id, Advisors=$advisor_term_id");

$leaders_data = [
    [
        'title' => 'Binh Pham, MBA, CFA',
        'role' => 'Managing Director',
        'credentials' => 'First Class Honors, Nanyang Technological University; MBA, University of Chicago Booth School of Business; CFA Charterholder since 2011',
        'photo' => $IMG['leader-binh'],
        'group' => $mgmt_term_id,
        'order' => 1,
        'bio' => "Before founding BCA Partners, Mr. Binh served as CEO at Vietnam International Securities (VIS). With over 14 years of combined experience in Banking, Finance, and Investment, he has extensive relationships in various sectors including Private Equity, Investment Banking, Commercial Banks, and Investment Advisory firms both in Vietnam and overseas.\n\nPreviously, Mr. Binh was Deputy Head of Restructuring Committee, Chief Investment Officer cum Deputy CEO at DongA Securities (DAS). He made great contributions to DAS during his tenor at the firm, especially in the areas of Research, Corporate Finance Advisory, and Investment. Before joining DAS, Mr. Binh worked at several MNCs, including Global Foundries & OCBC Bank in Singapore. He was Investment Manager at TIM Investment & Management, Research Manager at Vinasecurities, and Strategy Director at DongA Bank.\n\nMr. Binh graduated with a First Class Honors Degree from Nanyang Technological University, Singapore. He obtained an MBA Degree (with Honors Distinction) from the University of Chicago Booth School of Business — USA. He has been a certified CFA Charterholder since 2011.",
    ],
    [
        'title' => 'Thuy Huynh, PhD',
        'role' => 'Director, Operations & Transformation',
        'credentials' => 'PhD in Management',
        'photo' => $IMG['leader-thuy'],
        'group' => $mgmt_term_id,
        'order' => 2,
        'bio' => "Dr. Thuy Huynh leads BCA's operations and transformation practice. She brings deep expertise in organisational design, change management and operational excellence across financial services, manufacturing and retail.",
    ],
    [
        'title' => 'Chau Tran, Msc',
        'role' => 'Director, Finance & Human Capital',
        'credentials' => 'Msc in Finance',
        'photo' => $IMG['leader-chau'],
        'group' => $mgmt_term_id,
        'order' => 3,
        'bio' => 'Ms. Chau Tran heads BCA\'s finance and human capital practice. She has advised on a number of capital raising, restructuring and talent strategy engagements across Southeast Asia.',
    ],
    [
        'title' => 'Andy Phan, PhD, MBA',
        'role' => 'Advisor, Financial Market & Strategy',
        'credentials' => 'PhD, MBA',
        'photo' => $IMG['leader-andy'],
        'group' => $advisor_term_id,
        'order' => 4,
        'bio' => 'Dr. Andy Phan is an advisor to BCA on financial market dynamics and strategy. He has held senior positions in both commercial and investment banking in Vietnam and overseas.',
    ],
    [
        'title' => 'Nga Do, MBA',
        'role' => 'Advisor, Risk Management & Strategy',
        'credentials' => 'MBA',
        'photo' => $IMG['leader-nga'],
        'group' => $advisor_term_id,
        'order' => 5,
        'bio' => 'Ms. Nga Do advises BCA on risk management and corporate strategy. Her career spans risk leadership roles at multinational banks and consulting firms.',
    ],
];

foreach ($leaders_data as $l) {
    $lid = bca_find_or_create_post($l['title'], 'leader', [
        'post_content' => $l['bio'],
    ]);
    if ($lid) {
        set_post_thumbnail($lid, $l['photo']);
        update_field('role',         $l['role'],         $lid);
        update_field('credentials',  $l['credentials'],  $lid);
        update_field('display_order', $l['order'],       $lid);
        wp_set_object_terms($lid, [$l['group']], 'leader_group');
        WP_CLI::log("  Leader: {$l['title']} = $lid (group={$l['group']})");
    }
}

WP_CLI::log('===== 10. Create news categories + posts (post core) =====');

$cat_term_ids = [
    'DEAL'           => bca_find_or_create_term('DEAL', 'category'),
    'COMPANY'        => bca_find_or_create_term('COMPANY', 'category'),
    'EVENT'          => bca_find_or_create_term('EVENT', 'category'),
    'PRESS RELEASE'  => bca_find_or_create_term('PRESS RELEASE', 'category'),
];
WP_CLI::log('  Categories: ' . implode(', ', array_keys($cat_term_ids)));

$news_data = [
    [
        'title' => 'BCA Partners advises on landmark e-wallet transaction',
        'cat' => 'DEAL',
        'date' => '2024-04-18',
        'image' => $IMG['news-1'],
        'excerpt' => 'Acting as independent advisor, BCA supported a leading fintech client through a major strategic transaction.',
        'content' => '<p>BCA Partners acted as independent financial advisor to a leading Vietnamese e-wallet company on a landmark strategic transaction.</p><p>The transaction, which closed in Q2 2024, is one of the largest fintech deals in the Vietnam market this year and underscores the maturity of the country\'s digital payments sector.</p>',
    ],
    [
        'title' => 'BCA Partners expands its advisory team',
        'cat' => 'COMPANY',
        'date' => '2024-04-02',
        'image' => $IMG['news-2'],
        'excerpt' => 'Three senior advisors join to strengthen our capabilities across financial markets and risk.',
        'content' => '<p>BCA Partners is pleased to announce the addition of three senior advisors to our team, strengthening our capabilities across financial markets, risk management, and corporate strategy.</p><p>The new advisors bring decades of combined experience from leading financial institutions and consulting firms across the region.</p>',
    ],
    [
        'title' => 'BCA Partners at the Vietnam M&A Forum 2024',
        'cat' => 'EVENT',
        'date' => '2024-03-21',
        'image' => $IMG['news-3'],
        'excerpt' => 'Our Managing Director shared perspectives on cross-border deal-making in Southeast Asia.',
        'content' => '<p>BCA Partners was a featured speaker at the Vietnam M&A Forum 2024, where our Managing Director shared perspectives on cross-border deal-making in Southeast Asia.</p>',
    ],
    [
        'title' => 'New market-entry practice launched',
        'cat' => 'PRESS RELEASE',
        'date' => '2024-03-12',
        'image' => $IMG['news-4'],
        'excerpt' => 'A dedicated practice to help international clients enter and scale in the Vietnamese market.',
        'content' => '<p>BCA Partners has launched a dedicated market-entry practice to help international clients enter and scale in the Vietnamese market.</p><p>The practice combines regulatory, partnership and go-to-market expertise, drawing on BCA\'s deep local network and regional insights.</p>',
    ],
    [
        'title' => 'Agriculture value-chain restructuring completed',
        'cat' => 'DEAL',
        'date' => '2024-02-28',
        'image' => $IMG['news-5'],
        'excerpt' => 'BCA delivered a restructuring roadmap for a national staple-export supply chain.',
        'content' => '<p>BCA Partners has completed a value-chain restructuring engagement for a national staple-export supply chain.</p><p>The roadmap, which targets improvements across cold-chain, post-harvest handling and export channels, is expected to deliver measurable income improvements to participating farmers.</p>',
    ],
    [
        'title' => 'BCA Partners marks another year of growth',
        'cat' => 'COMPANY',
        'date' => '2024-02-15',
        'image' => $IMG['news-6'],
        'excerpt' => 'Reflecting on a year of new mandates, new talent and deeper client relationships.',
        'content' => '<p>As we mark another year of growth at BCA Partners, we want to thank our clients, partners and team for an outstanding year of new mandates, new talent and deeper client relationships.</p>',
    ],
];

foreach ($news_data as $n) {
    $existing = get_posts([
        'post_type' => 'post',
        'title'     => $n['title'],
        'post_status' => 'any',
        'fields'    => 'ids',
    ]);
    if (!empty($existing)) {
        WP_CLI::log("  News exists: {$n['title']}");
        continue;
    }
    $nid = wp_insert_post([
        'post_title'   => $n['title'],
        'post_type'    => 'post',
        'post_status'  => 'publish',
        'post_date'    => $n['date'] . ' 10:00:00',
        'post_excerpt' => $n['excerpt'],
        'post_content' => $n['content'],
    ]);
    if ($nid) {
        set_post_thumbnail($nid, $n['image']);
        wp_set_object_terms($nid, [$cat_term_ids[$n['cat']]], 'category');
        WP_CLI::log("  News: {$n['title']} = $nid (cat={$n['cat']})");
    }
}

WP_CLI::log('===== 11. Set Home page ACF (front-page) =====');

update_field('hero_settings', [
    'is_show'    => 1,
    'heading'    => 'Fostering Business Evolution',
    'subheading' => 'Trusted partner for your most important business challenges.',
    'image'      => $IMG['hero'],
    'cta_label'  => 'Contact Us',
    'cta_url'    => home_url('/contact/'),
], $home_id);

update_field('services_settings', [
    'is_show'    => 1,
    'heading'    => 'Our Services',
    'subheading' => 'A focused set of advisory capabilities, designed to move your most important business questions forward.',
    'service_ids' => array_values($service_ids),
], $home_id);

update_field('projects_settings', [
    'is_show'    => 1,
    'heading'    => 'Highlighted Projects',
    'subheading' => 'A selection of engagements where BCA Partners turned complex business challenges into practical, measurable outcomes.',
    'project_ids' => array_values($project_ids),
], $home_id);

update_field('leadership_settings', [
    'is_show'    => 1,
    'heading'    => 'Our Leadership',
    'subheading' => 'A team with deep local insight and global experience — committed to the work, accountable to the outcome.',
    'leader_ids' => array_map(fn($l) => bca_find_or_create_post($l['title'], 'leader', ['post_type' => 'leader', 'post_status' => 'any']), $leaders_data),
], $home_id);

update_field('contact_band_settings', [
    'is_show'    => 1,
    'heading'    => 'How can we help you succeed?',
    'subheading' => 'Tell us about your business challenge. We will respond within one business day.',
    'image'      => $IMG['contact-bg'],
    'cta_label'  => 'Contact Us',
    'cta_url'    => home_url('/contact/'),
], $home_id);

WP_CLI::log('Home page ACF written.');

WP_CLI::success('===== Seed complete! =====');
WP_CLI::log('Pages: ' . count(['home' => $home_id, 'about' => $about_id, 'services' => $services_id, 'contact' => $contact_id, 'privacy' => $privacy_id]));
WP_CLI::log('Services: ' . count($service_ids));
WP_CLI::log('Projects: ' . count($project_ids));
WP_CLI::log('Research: ' . count($research_ids));
WP_CLI::log('Careers: ' . count($careers_data));
WP_CLI::log('Leaders: ' . count($leaders_data));
WP_CLI::log('News: ' . count($news_data));
