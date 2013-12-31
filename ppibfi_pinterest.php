<?php
/*
UNSTABLE COPY. FOR DEVELOPMENT PURPOSES ONLY


Plugin Name: Pinterest Pin It Button For Images
Plugin URI: http://www.canha.net
Description: Displays a Pin It button directly over your images.
Author: Canha
Author URI: http://www.canha.net
Version: 1.1-nightly
*/

//Plugin shouldn't be accessed directly
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'ppibfi_pinterest.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) die ( 'Stop! Hammer time!' );

define( "XCPIN_VERSION", "1.0.1" );
define( "XCPIN_PATH", WP_PLUGIN_URL . "/" . plugin_basename( dirname( __FILE__ ) ) . "/" );
define( "XCPIN_NAME", "Pinterest Pin It Button For Images" );

include( "ppibfi_admin.php" ); //Config page
include( "ppibfi_meta.php" );  //Custom meta boxes for Posts and Pages

/**
	* Special thanks:

	* Daniel Camargo (http://profiles.wordpress.org/pererinha) for the enormous help
	* @link http://danielcamargo.com

	* vhf/victor felder
	* @link http://github.com/vhf

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

function pibfi_engine( $content ) {
	global $post;
	$post_url = get_permalink(); //Get the post URL
	$post_title = get_the_title( $post->ID ); //Get the post title
	$pinterest_base_url = 'http://pinterest.com/pin/create/button/'; //Pinterests URL to create a Pin

	$content = pibfi_engine_normalize_image_paths( $content );

	// Show the pin only on images with the 'pinthis' class
	if ( 'on' == get_option( 'ppibfi_img_pinthis' ) ) {
		$content = pibfi_engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
	} else {

		// Show on index.php / home page:
		if ( 'on' == get_option( 'ppibfi_pg_index' ) && is_home() ) {
			$isOpted = get_post_meta( $post->ID, 'xcp_optin_post' );
			if ( 'on' != $isOpted[0] ) {
				$content = pibfi_engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
			}
		}

		// Show on single.php:
		elseif ( 'on' == get_option( 'ppibfi_pg_single' ) && is_single() ) {
			$isOpted = get_post_meta( $post->ID, 'xcp_optin_post' );
			if ( 'on' != $isOpted[0] ) {
				$content = pibfi_engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
			}
		}

		// Show on page.php:
		elseif ( 'on' == get_option( 'ppibfi_pg_page' ) && is_page() ) {
			$isOpted = get_post_meta( $post->ID, 'xcp_optin_post' );
			if ( 'on' != $isOpted[0] ){
				$content = pibfi_engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
			}
		}

		// Show on category.php / archive.php:
		elseif ( 'on' == get_option( 'ppibfi_pg_cat' ) && is_category() ) {
		elseif ( 'on' == get_option( 'ppibfi_pg_cat' ) && is_category() || is_archive() || is_search() || is_author()) {
			$content = pibfi_engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title );
		}
	}
	// Print out the content with the changes on images
	return $content;
}

// This function normalize relative vs absolute image paths to absolute (required for the plugin)
function pibfi_engine_normalize_image_paths( $content ){
	$matches = array();
	preg_match_all(  '/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i', $content, $matches );
	foreach( $matches[2] as $match ){
		if( $match{0} == "/" ){
			$content = str_replace( $match, get_bloginfo( "siteurl" ) . $match, $content );
		}
	}
	return $content;
}

/* This function adds the pin at each post's image */
function pibfi_engine_add_pin( $content, $pinterest_base_url, $post_url, $post_title ) {
	$replacement = '
		<span class="pibfi_pinterest">
		<img%1$ssrc="%2$s.%3$s"%4$s>
			<span class="xc_pin" onclick="pin_this(event, \''.
			$pinterest_base_url.
			'?url='.esc_url( $post_url ).
			'&media=%2$s.%3$s'.'&description='.$post_title.'\')">
			</span>
		</span>';

	// Regular expression that finds all post's images
	$pattern = '/<img(.*?)src=[\'"](.*?).(bmp|gif|jpeg|jpg|png)[\'"](.*?)>/i';

	// Array to store the images that matches
	$matches = array();

	// Execute the regular expression
	preg_match_all( $pattern, $content, $matches );

	// Array to store the images and its properties
	$images = array();

	// Image count
	$image_count = 0;

	// Loop to join the tag image properties with its matches
	for( $i = 0; $i < sizeof( $matches[0] ); $i++ ){
		$images[ $image_count ]['tag'] = $matches[0][$i];
		$images[ $image_count ][1] = $matches[1][$i]; // match 1 - content before the src attr
		$images[ $image_count ][2] = $matches[2][$i]; // match 2 - image url without extension
		$images[ $image_count ][3] = $matches[3][$i]; // match 3 - the extension
		$images[ $image_count ][4] = $matches[4][$i]; // match 4 - content after the src attr
		$image_count++;
	}

	// Loop to check if any image has the 'needed' pin class: pinthis (pibfi_ShowButton)
	$any_image_has_the_needed_pin_class = ( 'on' == get_option( 'ppibfi_img_pinthis' ) ) ? true : false;
	
	$pinthis_class = ( get_option( "pibfi_ShowButton" ) ) ? get_option( "pibfi_ShowButton" ) : 'pinthis';

	for( $i=0; $i < sizeof( $images ); $i++ ) {
		$needed = pibfi_engine_img_tag_has_class( $images[ $i ][ 'tag' ], $pinthis_class );
		if( $needed ){
			$any_image_has_the_needed_pin_class = true;
		}
		$images[ $i ][ 'pinthis' ] = $needed;
	}

	// Loop to replace the normal tag by the html with the pin, if it is necessary
	foreach( $images as $image ) {
		// If the post has any image with the (pibfi_ShowButton) class, the pin'll be showed in just these images
		if( $any_image_has_the_needed_pin_class ){
			if( $image[ 'pinthis' ] ){
				$image_tag = sprintf( $replacement, $image[1], $image[2], $image[3], $image[4] );
				$content = str_replace( $image['tag'], $image_tag, $content );
			}
		} else { // Check if the image should or shoudn't have the pin
			$forbidden_classes = get_option( 'pibfi_no_show_button' );
			
			// If the option's value is a string explode it
			if( ! is_array( $forbidden_classes ) ){
				$forbidden_classes = explode( ',', $forbidden_classes );
			}
			if( ! empty( $forbidden_classes ) ) {
				// If image has not forbidden class AND image has one of default WP inserted media tag, add pin-it button
				if( ! pibfi_engine_img_tag_has_class( $image['tag'], $forbidden_classes ) and pibfi_engine_img_tag_is_wp_inserted( $image['tag'] ) ) {
					$image_tag = sprintf( $replacement, $image[1], $image[2], $image[3], $image[4] );
					$content = str_replace( $image['tag'], $image_tag, $content );
				}
			}
		}
	}
	return $content;
}

/* This function checks if the image tag $tag has class $class. $class could be an array as well  */
function pibfi_engine_img_tag_has_class( $tag, $class ) {
	$needle_classes = is_array($class) ? $class : array($class);

	preg_match( '/class=[\'"]([^\'"]*)[\'"]/i', $tag, $matches );

	if( empty( $matches[1] ) ) {
		return false;
	}

	$haystack_classes = explode( ' ', $matches[1] );

	foreach( $needle_classes as $needle_class ){
		if( in_array( $needle_class, $haystack_classes ) ) {
			return true;
		}
	}

	return false;
}


/* This function checks if the image tag $tag has class $class. $class could be an array as well  */
function pibfi_engine_img_tag_is_wp_inserted( $tag) {
	$needle_classes = array(
			'alignnone',
			'alignleft',
			'aligncenter',
			'alignright',
			'size-thumbnail',
			'size-medium',
			'size-full',
			'attachment-thumbnail'
	);

	preg_match( '/class=[\'"]([^\'"]*)[\'"]/i', $tag, $matches );

	if( empty( $matches[1] ) ) {
		return false;
	}

	$haystack_classes = explode( ' ', $matches[1] );

	foreach( $needle_classes as $needle_class ) {
		if( in_array( $needle_class, $haystack_classes ) ) {
			return true;
		}
	}
	
	foreach($haystack_classes as $class) {
		if( preg_match( '/wp-image-[0-9]+/i', $class ) ) {
			return true;
		}
	}

	return false;
}

/*
=========================
	Add to wp_head();
=========================
*/

function cWGlobal() {
	echo "<script type='text/javascript'> var ContentWidth = '".get_option( 'ppibfi_content_width' )."'; </script>\n";
}

function ppibfi_css() {
	//Should not be the case if installed properly and nothing tricky done otherwise, but just in case. We don't want the CSS to be broken, as it's the main visible part !
	//if( false === ($ppibfi_img_button = get_option( 'ppibfi_img_button' ) ) ) {
		$ppibfi_img_button = array( 'file' => XCPIN_PATH.'ppibfi_button.png', 'width' => 80, 'height' => 50 );
		update_option( 'ppibfi_img_button', $ppibfi_img_button );
	//}

echo '<style type="text/css">
/*	-----------
		BASE STYLES
		-----------
*/

.pibfi_pinterest {
	position: relative;
	display: block;
}
.pibfi_pinterest .xc_pin {
	/* Width and height of "Pin It" image button */
	width: '.$ppibfi_img_button['width'].'px; height: '.$ppibfi_img_button['height'].'px;
	/* Image button */
	background-image: url("'.$ppibfi_img_button['file'].'"); background-repeat: none;
	position: absolute;
	top: 5px;
	margin-left: -1px;
	opacity: 0;
	cursor: pointer;
	display: none;
}
.pibfi_pinterest img.left + .xc_pin {
	margin-left: 0;
}

/*	-----------------
		INTERACTIONS / FX
		-----------------
*/

.pibfi_pinterest .xc_pin,
.pibfi_pinterest img {
	-webkit-transition:opacity .2s ease-out; -moz-transition:opacity .2s ease-out; transition:opacity .2s ease-out;
}
.pibfi_pinterest img:hover + .xc_pin,
.pibfi_pinterest_hover {
	opacity: .7;
}
.pibfi_pinterest .xc_pin:hover {
	opacity: 1;
}</style>';

}

/*
=======================
	Admin functions
=======================
*/

//Adds menu to WordPress dashboard
function pibfi_engine_menu() {
	add_options_page(
		__( 'Pinterest Pin It Button For Images', 'ppibfi_translate'), //Page title
		__('Pinterest Pin It', 'ppibfi_translate'), //Menu name
		'activate_plugins', //Who can see it
		'pibfi_engine_id', // Unique ID
		'pibfi_engine_configs', //The page
		'' //Icon
	);
}

//Show the config page for the plugin in dashboard
if ( is_admin() ) {
	pibifi_check_content_width();
	wp_enqueue_style( 'pibfi_pinterest', XCPIN_PATH.'ppibfi_config.css' );
	if ( 'on' == get_option( 'ppibfi_opt_enable' ) ) add_action( 'add_meta_boxes', 'xcp_optin' ); //ppibfi_meta.php

}


// Create menu:
add_action( 'admin_menu', 'pibfi_engine_menu' );

//Only run the script on the blog (i.e. not dashboard) and if the user is *not* accessing via mobile
if ( ! is_admin() && ! stripos( $_SERVER['HTTP_USER_AGENT'], 'mobile' ) ) {
	wp_enqueue_script( 'pibfi_pinterest', XCPIN_PATH.'ppibfi_pinterest.js', array( 'jquery' ) );
	add_filter( 'the_content', 'pibfi_engine', 100 ); // The engine
	add_action( 'wp_head', 'cWGlobal' ); // Add function to wp_head();
	add_action( 'wp_head', 'ppibfi_css' ); // Add CSS to wp_head();
}

/*
===============
	Install
===============
*/

function xc_pin_install() {
	// On install, check if options exist. If not, set defaults
	if( false === get_option( 'ppibfi_pg_method' ) ) update_option( 'ppibfi_pg_method', 'popup' );
	if( false === get_option( 'ppibfi_pg_index' ) ) update_option( 'ppibfi_pg_index', 'on' );
	if( false === get_option( 'ppibfi_pg_single' ) ) update_option( 'ppibfi_pg_single', 'on' );
	if( false === get_option( 'ppibfi_pg_page' ) ) update_option( 'ppibfi_pg_page', 'on' );
	if( false === get_option( 'ppibfi_pg_cat' ) ) update_option( 'ppibfi_pg_cat', 'on' );
	if( false === get_option( 'ppibfi_img_button' ) ) update_option( 'ppibfi_img_button', array( 'file' => XCPIN_PATH.'ppibfi_button.png', 'width' => 80, 'height' => 50 ) );
	$dont_show_buttons_on = array( "wp-smiley", "nopin" ); //Default classes to *ignore* the button
	if( false === get_option( 'pibfi_no_show_button' ) ) update_option( 'pibfi_no_show_button', $dont_show_buttons_on );
	pibfi_CheckImagesWidth();
}

// Run hook:
register_activation_hook( __FILE__, 'xc_pin_install' );
?>