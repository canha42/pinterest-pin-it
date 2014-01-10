/*
Plugin Name: Pinterest Pin It Button For Images
Plugin URI: http://wordpress.org/plugins/pinterest-pin-it-button-for-images/
Description: Displays a Pin It button directly over your images.
Author: Canha
Author URI: http://www.canha.net
This function shows the pin-it dialogue
*/
// By vhf
function pin_this(e, url) {
	jQuery(window).ready(function(jQuery) {
		window.open(url, 'pinterest', 'screenX=100,screenY=100,height=340,width=730');
		e.preventDefault();
		e.stopPropagation();
	});
//});
}