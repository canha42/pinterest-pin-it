<?php

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'ppibfi_meta.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Stop! Hammer time!');

add_action( 'save_post', 'xcp_optin_save' );

/* Adds a box to the main column on the Post and Page edit screens */
function xcp_optin() {
	add_meta_box( 
		'xcp_optin_sectionid',
		__( 'Pinterest plugin', 'xcp_translate' ),
		'xcp_optin_custombox',
		'',
		'side'
	);
}

/* Prints the box content */
function xcp_optin_custombox( $post ) {

	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'xcp_optin_noncename' );
	
	$meta_values = get_post_meta($post->ID, 'xcp_optin_post');
	if($meta_values[0] == "on") $xc_check = 'checked="yes"';
	else $xc_check = '';

	// The actual fields for data entry
	echo "<label for='xc_optin_field_disable'>";
	echo '<input type="checkbox" name="xc_optin_field_disable" id="xc_optin_field_disable" value="on" '.$xc_check.' /> ';
	_e('Disable Pinterest Pin It button on this page', 'xcp_translate'); 
}

/* When the post is saved, saves our custom data */
function xcp_optin_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( !wp_verify_nonce( $_POST['xcp_optin_noncename'], plugin_basename( __FILE__ ) ) ) return;
	$meta_values = get_post_meta($post->ID, 'xcp_optin_post');	
	$disable_field = $_POST['xc_optin_field_disable'];
	update_post_meta($post_id, 'xcp_optin_post', $disable_field);
}

?>