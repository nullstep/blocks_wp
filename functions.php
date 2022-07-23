<?php

//  ▀█████████▄    ▄█         ▄██████▄    ▄████████     ▄█   ▄█▄      ▄█     █▄      ▄███████▄  
//    ███    ███  ███        ███    ███  ███    ███    ███ ▄███▀     ███     ███    ███    ███  
//    ███    ███  ███        ███    ███  ███    █▀     ███▐██▀       ███     ███    ███    ███  
//   ▄███▄▄▄██▀   ███        ███    ███  ███          ▄█████▀        ███     ███    ███    ███  
//  ▀▀███▀▀▀██▄   ███        ███    ███  ███         ▀▀█████▄        ███     ███  ▀█████████▀   
//    ███    ██▄  ███        ███    ███  ███    █▄     ███▐██▄       ███     ███    ███         
//    ███    ███  ███▌    ▄  ███    ███  ███    ███    ███ ▀███▄     ███ ▄█▄ ███    ███         
//  ▄█████████▀   █████▄▄██   ▀██████▀   ████████▀     ███   ▀█▀      ▀███▀███▀    ▄████▀       

function bwp_setup() {
	add_theme_support('wp-block-styles');
}

function start_wp_head_buffer() {
	ob_start();
}

function end_wp_head_buffer() {
	$content = '<html><body>' . ob_get_clean() . '</body></html>';
	libxml_use_internal_errors(true);
	$dom = new DOMDocument;
	$dom->strictErrorChecking = false;
	$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS);
	$styles = '';
	$tags = $dom->getElementsByTagName('style');
	for ($i = $tags->length; --$i >= 0;) {
		$tag = $tags->item($i);
		$styles .= trim($tag->nodeValue);
		$tag->parentNode->removeChild($tag);
	}
	$dom->replaceChild($dom->firstChild->firstChild->firstChild, $dom->firstChild);
	$style = $dom->createElement('style', $styles);
	$dom->appendChild($style);
	echo $dom->saveHTML();
}

add_action('after_setup_theme', 'bwp_setup');
add_action('wp_head','start_wp_head_buffer', 0);
add_action('wp_head','end_wp_head_buffer', PHP_INT_MAX);

remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'noindex', 1);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_resource_hints', 2);
remove_action('wp_head', 'wp_oembed_add_host_js');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'oa_social_login_add_javascripts');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('rest_api_init', 'wp_oembed_register_route');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('shutdown', 'wp_ob_end_flush_all', 1);
remove_action('wp_footer', 'the_block_template_skip_link');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

//remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');

add_filter('show_admin_bar', '__return_false');

remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_filter('the_excerpt', 'wpautop');
remove_filter('wp_robots', 'wp_robots_max_image_preview_large');

// EOF