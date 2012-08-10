<?php
/*
Plugin Name: Pinterest Pin It Button For Images
Plugin URI: http://www.canha.net
Description: Displays a Pin It button directly over your images.
Author: Canha
Author URI: http://www.canha.net
Version: 1.0.1
*/

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'ppibfi_pinterest.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Stop! Hammer time!');


define("XCPIN_PATH", WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__) ) . "/" );
define("XCPIN_NAME", "Pinterest Pin It Button For Images");
define("XCPIN_VERSION", "1.0.1");
include("ppibfi_admin.php");
include("ppibfi_meta.php");
	 
/** 
	* Special thanks:
	
	* Third Mind for giving an alternative to my script
	* @link http://thirdmind.com

	* David Cowgill for the regex:
	* @link http://docs.appthemes.com/tutorials/automatically-add-rel-attribute-to-wordpress-images/

	* Thiago Galesi (@dsracoon) and Joao Ricardo (@JoaoRicardo_RM) for the help with regex
	
	* Every one who has been reporting bugs.
	
	* Super special thanks: to YOU, for donating *wink, wink*
	
	* Usefull links:
	* @link http://wordpress.stackexchange.com/questions/2126/at-what-priority-does-add-filter-overwrite-core-functions
	* @link http://www.ilovecolors.com.ar/how-to-detect-iphone-browser-natively-in-wordpress/
	
*/

/* On install, check if options exist. If not, set defaults */
function xc_pin_intstall() {
	if(get_option('xc_pg_method') == false) update_option('xc_pg_method', 'popup');
	if(get_option('xc_pg_index') == false) update_option('xc_pg_index', 'on');
	if(get_option('xc_pg_single') == false) update_option('xc_pg_single', 'on');
	if(get_option('xc_pg_page') == false) update_option('xc_pg_page', 'on');
	if(get_option('xc_pg_cat') == false) update_option('xc_pg_cat', 'on');
}

//Still not working:
function pin_Warning($errornum) {
	switch($errornum) {
		case "001":
			echo "<div class='error'><p>Your maximum image width is thought to be 1024 pixels. This may be incorrect and might affect the funcionality of the Pinterest Pin It plugin. Please check the plugins settings page under Settings > Pinterest Pin It</p></div>"; 
		break;
		
		case "002":
			echo "<div class='error'><p>".__('No maximum image width has been detected. This might affect the funcionality of the Pinterest Pin It plugin. Please check the plugins settings page under Settings > Pinterest Pin It', 'xcp_translate')."</p></div>"; 
		break;
	}
}

/* 
==============
	Engine
==============
*/

function xcake_pinterest($content) {
	global $post;
	$post_url = get_permalink(); //Get the post URL
	$post_title = get_the_title(); //Get the post title
	$pinterest_base_url = 'http://pinterest.com/pin/create/button/'; //Pinterests URL to create a Pin
	
	// The pinterest button container and button
	$pin_container_start = 	'<span class="xc_pinterest">'; //Before <img>
	$pin_button_start = 			'<span class="xc_pin"'; //After <img> closes, before <xc_pinterest> closes
	$pin_button_base_url = 			'data-xc_pinterest_base_url'; // = $pinterest_base_url
	$pin_button_post_url = 			'data-xc_pinterest_post_url'; //The link to original post
	$pin_button_media = 				'data-xc_pinterest_media'; //The image URL to be pinned
	$pin_button_description = 	'data-xc_pinterest_description'; //Post title as the Pin Description
	$pin_button_end =				'></span>'; // End <xc_pin>
	$pin_container_end = 	'</span>'; //End <xc_pinterest>
	
	// Begin - Replace post image
	// Normalize relative vs absolute image paths to absolute (required for the plugin)
	// Add pinterest button elements
	$pattern = '/<img(.*?)src=[\'"](.*?).(bmp|gif|jpeg|jpg|png)[\'"](.*?)>/i';
	
	$matches = array();
	preg_match_all('/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i', $content, $matches);

	foreach($matches[2] as $match)
		if($match{0} == "/")
			$content = str_replace($match, get_bloginfo("siteurl") . $match, $content);	
	
	$replacement =
	$pin_container_start.
		'<img$1src="$2.$3"$4>'.
		$pin_button_start.' '.
			$pin_button_base_url.'="'.$pinterest_base_url.'" '.
			$pin_button_post_url.'="'.$post_url.'" '.
			$pin_button_media.'="'.'$2'.'.$3'.'" '.
			$pin_button_description.'="'.$post_title.'"'.
		$pin_button_end.
	$pin_container_end;

	if (get_option('xc_pg_index') == "on" && is_home()) {
		$content = preg_replace( $pattern, $replacement, $content );
	}
	elseif (get_option('xc_pg_single') == "on" && is_single()) {
		$isOpted = get_post_meta($post->ID, 'xcp_optin_post');
		if ($isOpted[0] != "on") $content = preg_replace( $pattern, $replacement, $content );
	}
	elseif (get_option('xc_pg_page') == "on" && is_page()) {
		$isOpted = get_post_meta($post->ID, 'xcp_optin_post');
		if ($isOpted[0] != "on") $content = preg_replace( $pattern, $replacement, $content );
	}	
	elseif (get_option('xc_pg_cat') == "on" && is_category()) {
		$content = preg_replace( $pattern, $replacement, $content );
	}
	return $content;
	
}


//Call the menu
function xcake_pinterest_menu() {
	add_options_page(
		'Pinterest Pin It Button For Images', //Page title
		'Pinterest Pin It', //Menu name
		'activate_plugins', //Who can see it
		'xcake_pinterest_id', // Unique ID
		'xcake_pinterest_configs', //The page
		'' //Icon
	);
}

//Set the content width as global, so our js can use it
function cWGlobal() {
	echo "<script type='text/javascript'> var ContentWidth = '".get_option('large_size_w')."'; </script>\n";
}

//If this is not the admin page and not mobile, put out the script
if (!is_admin() && stripos($_SERVER['HTTP_USER_AGENT'], 'mobile') != true) {
	wp_enqueue_style('xc_pinterest', XCPIN_PATH.'ppibfi_pinterest.css'); 
	wp_enqueue_script('xc_pinterest', XCPIN_PATH.'ppibfi_pinterest.js', array('jquery')); 
	add_filter('the_content', 'xcake_pinterest', 100);
	add_action('wp_head', 'cWGlobal');
}


//Get config page:
if (is_admin()) {
	wp_enqueue_style('xc_pinterest', XCPIN_PATH.'ppibfi_config.css'); 
	if (get_option('xc_opt_enable') == "on") add_action( 'add_meta_boxes', 'xcp_optin' );
}
register_activation_hook(__FILE__,'xc_pin_intstall');
add_action('admin_menu', 'xcake_pinterest_menu');
?>