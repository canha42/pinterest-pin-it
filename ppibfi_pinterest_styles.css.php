<?php header("Content-type: text/css; charset: UTF-8");

//Hook this file to wordpress
require '../../../wp-blog-header.php';

//Should not be the case if installed properly and nothing tricky done otherwise, but just in case. We don't want the CSS to be broken, as it's the main visible part !
if( false === ($ppibfi_img_button = get_option( 'ppibfi_img_button' ) ) ) {
	$ppibfi_img_button = array( 'file' => 'ppibfi_button.png', 'width' => 80, 'height' => 50 );
	update_option( 'ppibfi_img_button', $ppibfi_img_button );
}

?>
/*	-----------
		BASE STYLES
		-----------
*/

/* If you feel comfortable editing here, go ahead. I promise to add a function soon to easily change the button */

.pibfi_pinterest {
	position: relative;
	display: block;
}
.pibfi_pinterest .xc_pin {
	/* Width and height of "Pin It" image button */
	width: <?php echo $ppibfi_img_button['width']; ?>px; height: <?php echo $ppibfi_img_button['width']; ?>px;
	/* Image button */
	background-image: url('<?php echo $ppibfi_img_button['file']; ?>'); background-repeat: none;
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
}
