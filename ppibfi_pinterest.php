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

	* Daniel Camargo (http://profiles.wordpress.org/pererinha) for the enormous help
	* @link http://danielcamargo.com
	
	* Victor (http://profiles.wordpress.org/kortchnoi) for the JS changes
		
	* Third Mind for giving an alternative to my script
	* @link http://thirdmind.com

	* David Cowgill for the regex:
	* @link http://docs.appthemes.com/tutorials/automatically-add-rel-attribute-to-wordpress-images/

	* Thiago Galesi (@dsracoon) and Joao Ricardo (@JoaoRicardo_RM) for the help with early regex
	
	* Every one who has been reporting bugs.
	
	* Super special thanks: to YOU, for donating *wink, wink*	
*/


/* 
==============
	Engine
==============
*/

function pibfi_Engine($content) {
	global $post;
	$post_url = get_permalink(); //Get the post URL
	$post_title = get_the_title($ID); //Get the post title
	$pinterest_base_url = 'http://pinterest.com/pin/create/button/'; //Pinterests URL to create a Pin

	$content = pibfi_Engine_normalize_image_paths( $content );

	// Show the pin just in images with the 'pinthis' class
	if ( get_option('ppibfi_img_pinthis') == 'on' ) {
		$content = pibfi_Engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
	} else {

		// Show on index.php / home page:
		if (get_option('ppibfi_pg_index') == "on" && is_home()) {
			$isOpted = get_post_meta($post->ID, 'xcp_optin_post');
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
	}
	// Print out the content with the changes on images
	return $content;
}

// This function normalize relative vs absolute image paths to absolute (required for the plugin)
function pibfi_Engine_normalize_image_paths( $content ){
	$matches = array();
	preg_match_all('/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i', $content, $matches);
	foreach($matches[2] as $match){
		if($match{0} == "/"){
			$content = str_replace($match, get_bloginfo("siteurl") . $match, $content);	
		}
	}
	return $content;
}

/* This function adds the pin at each post's image */
function pibfi_Engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title ){
	// I had to change this string in order to use the sprintf function.
	// By kortchnoi:
	$replacement = '
		<span class="pibfi_pinterest">
		<img%1$ssrc="%2$s.%3$s"%4$s>
			<span class="xc_pin" onclick="pin_this(event, \''.
			$pinterest_base_url.
			'?url='.esc_url($post_url).
			'&media=%2$s.%3$s'.'&description='.$post_title.'\')">
			</span>
		</span>';
	// Regular expression that finds all post's images
	$pattern = '/<img(.*?)src=[\'"](.*?).(bmp|gif|jpeg|jpg|png)[\'"](.*?)>/i';
	// Array to store the images that matches 
	$matches = array();
	// Execute the regular expression
	preg_match_all($pattern, $content, $matches);
	// Array to store the images and its properties
	$images = array();
	// Image count
	$image_count = 0;
	// Loop to join the tag image properties with its matches
	for( $i = 0; $i < sizeof($matches[0]); $i++ ){
		$images[ $image_count ]['tag'] = $matches[0][$i];
		$images[ $image_count ][1] = $matches[1][$i]; // match 1 - content before the src attr
		$images[ $image_count ][2] = $matches[2][$i]; // match 2 - image url without extension
		$images[ $image_count ][3] = $matches[3][$i]; // match 3 - the extension
		$images[ $image_count ][4] = $matches[4][$i]; // match 4 - content after the src attr
		$image_count++;
	}
	// Loop to check if any image has the 'needed' pin class: pinthis (pibfi_ShowButton)
	$any_image_has_the_needed_pin_class = ( get_option('ppibfi_img_pinthis') == 'on' ) ? true : false;
	for( $i=0; $i < sizeof( $images ); $i++ ){
		$needed = pibfi_Engine_check_if_the_image_has_pinthis_class( $images[ $i ][ 'tag' ] );
		if( $needed ){
			$any_image_has_the_needed_pin_class = true;
		}
		$images[ $i ][ 'pinthis' ] = $needed; 
	}
	// Loop to replace the normal tag by the html with the pin, if it is necessary
	foreach( $images as $image ){
		// If the post has any image with the (pibfi_ShowButton) class, the pin'll be showed in just these images
		if( $any_image_has_the_needed_pin_class ){
			if( $image[ 'pinthis' ] ){
				$image_tag = sprintf( $replacement, $image[1], $image[2], $image[3], $image[4]);
				$content = str_replace( $image['tag'], $image_tag, $content);
			}
		} else {
			// Check if the image should or shoudn't has the pin
			if( pibfi_Engine_check_if_the_image_has_the_forbidden_class( $image['tag'] ) ){
				// If it shoud do the replacement
				$image_tag = sprintf( $replacement, $image[1], $image[2], $image[3], $image[4]);
				$content = str_replace( $image['tag'], $image_tag, $content);
			}
		}
	}
	return $content;
}

/* This function checks if the image has the 'pinthis' class  */
function pibfi_Engine_check_if_the_image_has_pinthis_class( $tag ){
	$pinthis = ( get_option("pibfi_ShowButton") ) ? get_option("pibfi_ShowButton") : 'pinthis';
	preg_match( '/class=[\'"](.*)?' . trim( $pinthis ) . '(.*)?[\'"]/i', $tag, $matches );
	if( $matches && pibfi_Util_the_array_has_content( $matches ) ){
		return true;
	}
	return false;
}

/* This function checks if the image has a specific class, such as .wp-smiley, wp-thumb if it does the plugin shouldn't add the button to this particular image.  */
function pibfi_Engine_check_if_the_image_has_the_forbidden_class( $tag ){
	$has_forbidden_class = false;
	// Array/String with not allowed css classes
	$forbidden_classes = get_option( 'pibfi_NoShowButton' );
	// If the option's value is a string explode it
	if( !is_array( $forbidden_classes ) ){
		$forbidden_classes = explode( ',', $forbidden_classes );
	}
	foreach( $forbidden_classes as $class ){
		if( $class != '' ){
			// Regular expression to detect if the tag has the forbbiden class
			preg_match( '/class=[\'"](.*)?' . trim( $class ) . '(.*)?[\'"]/i', $tag, $matches );
			if( pibfi_Util_the_array_has_content( $matches) ){
				$has_forbidden_class = true;
				break;
			}
		}
	}
	return !$has_forbidden_class;
}

/* This fuction checks if the array has any value or is just an empty array */
function pibfi_Util_the_array_has_content( $arr ){
	foreach( $arr as $ar ){
		if( is_array( $ar ) ){
			return pibfi_Util_the_array_has_content( $ar );
		} else {
			if( $ar != '' ){
				return true;
			}
		}
	}
}

/* 
=========================
	Add to wp_head();
=========================
*/

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
	if(get_option('ppibfi_pg_method') != false) update_option('ppibfi_pg_method', 'popup');
	if(get_option('ppibfi_pg_index') != false) update_option('ppibfi_pg_index', 'on');
	if(get_option('ppibfi_pg_single') != false) update_option('ppibfi_pg_single', 'on');
	if(get_option('ppibfi_pg_page') != false) update_option('ppibfi_pg_page', 'on');
	if(get_option('ppibfi_pg_cat') != false) update_option('ppibfi_pg_cat', 'on');
	$dontShowButtonsOn = array("wp-smiley", "nopin"); //Default classes to *ignore* the button
	if(get_option('pibfi_NoShowButton') == false) update_option('pibfi_NoShowButton', $dontShowButtonsOn);
	pibfi_CheckImagesWidth();
}

// Run hook:
register_activation_hook(__FILE__,'xc_pin_intstall');
?>