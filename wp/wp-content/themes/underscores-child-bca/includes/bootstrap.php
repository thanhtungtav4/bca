<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Bootstrap the BCA child theme.
 *
 * Namespaced classes (Theme\Child\) autoload via the parent composer PSR-4 map.
 * Procedural helpers + ACF loader are plain requires.
 *
 * Page-specific hooks (Home, About, Services, Projects, Research, News, Career,
 * Leadership, Contact, Privacy) are registered here once their files are added
 * under app/Hooks/.
 */

// Procedural helpers (used as template tags / hook callbacks).
require_once BCA_CHILD_THEME_INCLUDES_PATH . '/functions/common-functions.php';
require_once BCA_CHILD_THEME_INCLUDES_PATH . '/functions/performance-functions.php';
require_once BCA_CHILD_THEME_INCLUDES_PATH . '/functions/template-functions.php';
require_once BCA_CHILD_THEME_INCLUDES_PATH . '/functions/bca-enqueue.php';

// Register hook + ACF classes.
\Theme\Child\Acf\LocalJson::register();
\Theme\Child\Hooks\PerformanceHook::register();
\Theme\Child\Hooks\ThemeHook::register();

// Custom Post Types — BCA Partners.
\Theme\Child\PostTypes\ServicePostType::register();
\Theme\Child\PostTypes\ProjectPostType::register();
\Theme\Child\PostTypes\ResearchPostType::register();
\Theme\Child\PostTypes\CareerPostType::register();
\Theme\Child\PostTypes\LeaderPostType::register();

// Taxonomies.
\Theme\Child\Taxonomies\LeaderGroupTaxonomy::register();

// Page-specific hooks — register here as pages are scaffolded.
// \Theme\Child\Hooks\HomePageHook::register();
// \Theme\Child\Hooks\AboutPageHook::register();
// \Theme\Child\Hooks\ServicesPageHook::register();
// \Theme\Child\Hooks\ProjectsPageHook::register();
// \Theme\Child\Hooks\ResearchPageHook::register();
// \Theme\Child\Hooks\NewsPageHook::register();
// \Theme\Child\Hooks\CareerPageHook::register();
// \Theme\Child\Hooks\LeadershipPageHook::register();
// \Theme\Child\Hooks\ContactPageHook::register();
// \Theme\Child\Hooks\PrivacyPageHook::register();

// WP-CLI scaffolder (global classes, dev tooling only).
if (defined('WP_CLI') && WP_CLI) {
    require_once BCA_CHILD_THEME_INCLUDES_PATH . '/classes/class-child-scaffold-generator.php';
    require_once BCA_CHILD_THEME_INCLUDES_PATH . '/classes/class-child-cli.php';

    if (class_exists('Underscores_Child_CLI')) {
        Underscores_Child_CLI::get_instance();
    }
}
