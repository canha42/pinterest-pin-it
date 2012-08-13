<?php
/*
Plugin Name: Pinterest Pin It Button For Images
Plugin URI: http://www.canha.net
Description: Displays a Pin It button directly over your images.
Author: Canha
Author URI: http://www.canha.net
Version: 1.0.1
*/

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'ppibfi_pinterest.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Stop! Hammer time!'); //Plugin shouldn't be accessed directly

define("XCPIN_VERSION", "1.0.1");
define("XCPIN_PATH", WP_PLUGIN_URL . "/" . plugin_basename( dirname(__FILE__) ) . "/" );
define("XCPIN_NAME", "Pinterest Pin It Button For Images");

include("ppibfi_admin.php"); //Config page
include("ppibfi_meta.php"); //Custom meta boxes for Posts and Pages
	 
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

	/*
		==================
			Issue #1
		==================
		Content width getting real screwed up.
		
		Solution: On install, see what the width is. If it's non-equal to 1024, add that value to the content width box option. In config panel, create an empty input where user can insert the width. On the pibfi_CheckContentWidth() function, see if this box is empty AND if the width is 1024 / false. Only then show the error. On the wp_head() function, get always that boxes value. 
		Working on it (@canha)
	*/


/* 
==============
	Engine
==============
*/

function pibfi_Engine($content) {
	global $post;
	$post_url = get_option('ppibfi_url'); //Get the post URL
	$post_title = get_option('ppibfi_title'); //Get the post title
	$pinterest_base_url = 'http://pinterest.com/pin/create/button/'; //Pinterests URL to create a Pin
	
	
	/*
		==================
			Issue #4
		==================
		If user populates <?php get_option("pibfi_ShowButton"); ?> with an array of CSS classes, only images with those classes will recieve the button. The Issue #3 is ignored in this case.
		
		Possible solution: Only execute the str_replace and Issue #3 on all images if get_option("pibfi_ShowButton") is FALSE. Else, execure str_replace only on images with classes described in get_option("pibfi_ShowButton");
	*/
	
	// Normalize relative vs absolute image paths to absolute (required for the plugin)
	$matches = array();
	preg_match_all('/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i', $content, $matches);
	foreach($matches[2] as $match)
		if($match{0} == "/")
			$content = str_replace($match, get_bloginfo("siteurl") . $match, $content);	

	// Show on index.php / home page:
	if (get_option('ppibfi_pg_index') == "on" && is_home()) {
		// Issue #2 code that doesn't work:
		$isOpted = get_post_meta($post->ID, 'xcp_optin_post');
		/*
+++++++++++++++++++++++++++++++++++++++
About the issue #2:
I just uncommment this links bellow and it worked as well.
At the admin I checked the option Show "Pin It" button on following pages: Index / home
And also checked checked the option Opt-out on single pages: Enable opt-out
+++++++++++++++++++++++++++++++++++++++
		*/
		if ($isOpted[0] != "on") {
			$content = pibfi_Engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
		}
	}
	
	// Show on single.php:
	elseif (get_option('ppibfi_pg_single') == "on" && is_single()) {
		$isOpted = get_post_meta($post->ID, 'xcp_optin_post');
		if ($isOpted[0] != "on") {
			$content = pibfi_Engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
		}
	}
	
	// Show on page.php:
	elseif (get_option('ppibfi_pg_page') == "on" && is_page()) {
		$isOpted = get_post_meta($post->ID, 'xcp_optin_post');
		if ($isOpted[0] != "on"){
			$content = pibfi_Engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
		}
	}
	
	// Show on category.php / archive.php:
	elseif (get_option('ppibfi_pg_cat') == "on" && is_category()) {
		$content = pibfi_Engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
	}
	
	// Print out the content with the changes on images
	return $content;
}

/* This function adds the pin at each post's image */
function pibfi_Engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title ){
	// I had to change this string in order to use the sprintf function.
	// By victorjohnson:
	$replacement = '
		<span class="pibfi_pinterest">
		<img%1$ssrc="%2$s.%3$s"%4$s>
			<span class="xc_pin" onclick="pin_this(event, \''.
			$pinterest_base_url.
			'?url='.urlencode($post_url).
			'&media=%2$s.%3$s'.'&description='.urlencode($post_title).'\')">
			</span>
		</span>';
	// Regular expression that finds all post's images
	$pattern = '/<img(.*?)src=[\'"](.*?).(bmp|gif|jpeg|jpg|png)[\'"](.*?)>/i';
	// Array to store the regular expression matches 
	$matches = array();
	// Execute the regular expression
	preg_match_all($pattern, $content, $matches);
	// Array to store the images
	$images = array();
	// Image count
	$image_count = 0;
	// Loop to join the tag image properties with its matches
	for( $i = 0; $i < sizeof($matches[0]); $i++ ){
		$images[ $image_count ]['tag'] = $matches[0][$i];
		$images[ $image_count ][1] = $matches[1][$i];
		$images[ $image_count ][2] = $matches[2][$i];
		$images[ $image_count ][3] = $matches[3][$i];
		$images[ $image_count ][4] = $matches[4][$i];
		$image_count++;
	}
	// Loop to replace the normal tag by the html with the pin, if it is necessary
	foreach( $images as $image ){
		// Check if the image should or shoudn't has the pin
		if( pibfi_Engine_should_it_has_pin( $image['tag'] ) ){
			// If it shoud do the replacement
			$image_tag = sprintf( $replacement, $image[1], $image[2], $image[3], $image[4]);
			$content = str_replace( $image['tag'], $image_tag, $content);
		}
	}
	return $content;
}

/* This function checks if the image has a specific class, such as .wp_smiley, wp-thumb if it does the plugin shouldn't add the button to this particular image.  */
function pibfi_Engine_should_it_has_pin( $tag ){
	$has_forbidden_class = false;
	// Array with not allowed css classes
	$forbidden_classes = array( 'wp-smiley', 'wp-thumb' );
	foreach( $forbidden_classes as $class ){
		if( strpos( $tag, $class ) > 0 ){
			$has_forbidden_class = true;
		}
	}
	return !$has_forbidden_class;
}

/* 
=========================
	Add to wp_head();
=========================
*/

/* This function prints a global javascript var that defines the width of the content. In this case, the width is defined by the largest size for an image. This will enable jQuery to calculate where the button is placed whatever the alignment of the image */

function cWGlobal() {
	echo "<script type='text/javascript'> var ContentWidth = '".get_option('ppibfi_content_width')."'; </script>\n";
}

/* 
=======================
	Admin functions
=======================
*/

//Adds menu to WordPress dashboard
function pibfi_Engine_menu() {
	add_options_page(
		'Pinterest Pin It Button For Images', //Page title
		'Pinterest Pin It', //Menu name
		'activate_plugins', //Who can see it
		'pibfi_Engine_id', // Unique ID
		'pibfi_Engine_configs', //The page
		'' //Icon
	);
}

//Show the config page for the plugin in dashboard
if (is_admin()) {
	pibfi_CheckContentWidth();
	wp_enqueue_style('pibfi_pinterest', XCPIN_PATH.'ppibfi_config.css'); 
	if (get_option('ppibfi_opt_enable') == "on") add_action( 'add_meta_boxes', 'xcp_optin' ); //ppibfi_meta.php
	
}


// Create menu:
add_action('admin_menu', 'pibfi_Engine_menu');

//Only run the script on the blog (i.e. not dashboard) and if the user is *not* accessing via mobile
if (!is_admin() && stripos($_SERVER['HTTP_USER_AGENT'], 'mobile') != true) {
	wp_enqueue_style('pibfi_pinterest', XCPIN_PATH.'ppibfi_pinterest.css'); 
	wp_enqueue_script('pibfi_pinterest', XCPIN_PATH.'ppibfi_pinterest.js', array('jquery')); 
	add_filter('the_content', 'pibfi_Engine', 100); // The engine
	add_action('wp_head', 'cWGlobal'); // Add function to wp_head();
}

/* 
===============
	Install
===============
*/

function xc_pin_intstall() {	
	// On install, check if options exist. If not, set defaults 
	if(get_option('ppibfi_pg_method') == false) update_option('ppibfi_pg_method', 'popup');
	if(get_option('ppibfi_pg_index') == false) update_option('ppibfi_pg_index', 'on');
	if(get_option('ppibfi_pg_single') == false) update_option('ppibfi_pg_single', 'on');
	if(get_option('ppibfi_pg_page') == false) update_option('ppibfi_pg_page', 'on');
	if(get_option('ppibfi_pg_cat') == false) update_option('ppibfi_pg_cat', 'on');
	$dontShowButtonsOn = array("wp_smiley", "nopin"); //Default classes to *ignore* the button
	if(get_option('pibfi_NoShowButton') == false) update_option('pibfi_NoShowButton', $dontShowButtonsOn);
	pibfi_CheckImagesWidth();
}

// Run hook:
register_activation_hook(__FILE__,'xc_pin_intstall');
?>