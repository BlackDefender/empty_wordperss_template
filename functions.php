<?php

add_theme_support('post-thumbnails');
add_theme_support('menus');

require_once 'autoload.php';

require_once 'functions/post-types.php';
require_once 'functions/meta-fields-engine.php';
require_once 'functions/additional-settings.php';
require_once 'functions/translations.php';

// глобальные переменные
$templateUri = get_template_directory_uri();
$contactsData = get_option('contacts_data');

function enqueue_assets()
{	
    wp_enqueue_style( 'bundle', Utils::getAssetUrlWithTimestamp('/css/bundle.css'), [], null);
	wp_deregister_script('jquery');
    wp_deregister_script('wp-embed');
    wp_enqueue_script('bundle', Utils::getAssetUrlWithTimestamp('/js/bundle.js'), [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_assets');

function theme_register_nav_menu()
{
    register_nav_menu( 'main-menu', 'Главное меню' );
}
add_action( 'after_setup_theme', 'theme_register_nav_menu' );

// убираем комментарии Yoast SEO
add_action('wp_head',function() { ob_start(function($o) {
    return preg_replace('/^\n?<!--.*?[Y]oast.*?-->\n?$/mi','',$o);
}); },~PHP_INT_MAX);


remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rest_output_link_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');