<?php
/*
=====================
	Control panel
=====================
*/

if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'ppibfi_admin.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) die ( 'Stop! Hammer time!' );

add_action( 'admin_init', 'ppibfi_load_langs' );

function pibfi_engine_configs() {
	if ( ! empty( $_POST['submit'] ) && check_admin_referer( 'ppibfi_update_option','ppibfi_post_option' ) ) {
		update_option( 'ppibfi_pg_index', htmlspecialchars( strip_tags( $_POST['ppibfi_pg_index'] ) ) );
		update_option( 'ppibfi_pg_single', htmlspecialchars( strip_tags( $_POST['ppibfi_pg_single'] ) ) );
		update_option( 'ppibfi_pg_page', htmlspecialchars( strip_tags( $_POST['ppibfi_pg_page'] ) ) );
		update_option( 'ppibfi_pg_cat', htmlspecialchars( strip_tags( $_POST['ppibfi_pg_cat'] ) ) );
		update_option( 'ppibfi_img_pinthis', htmlspecialchars( strip_tags( $_POST['ppibfi_img_pinthis'] ) ) );
		update_option( 'ppibfi_description', htmlspecialchars( strip_tags( $_POST['ppibfi_description'] ) ) );
		$exclude_posts = array_filter( array_map ('htmlspecialchars', explode(',', strip_tags( $_POST['ppibfi_exclude'] ) ) ) );
		sort($exclude_posts);
		update_option( 'pibfi_no_show_button', $exclude_posts );

		?><div class="updated"><p><strong><?=__( 'Options saved.', 'ppibfi_translate' ); ?></strong></p></div><?php
	}

	$xcp_index = ( 'on' == get_option( 'ppibfi_pg_index' ) ) ? 'checked' : '';
	$xcp_single = ( 'on' == get_option( 'ppibfi_pg_single' ) ) ? 'checked' : '';
	$xcp_page = ( 'on' == get_option( 'ppibfi_pg_page' ) ) ? 'checked' : '';
	$xcp_img = ( 'on' == get_option( 'ppibfi_img_pinthis' ) ) ? 'checked' : '';
	$xcp_cat = ( 'on' == get_option( 'ppibfi_pg_cat' ) ) ? 'checked' : '';
	$ppibfi_exclude = get_option( 'pibfi_no_show_button' );
	$ppibfi_description = get_option( 'ppibfi_description' );
?>
<script type="text/javascript">checked=false;
function checkedAll () {var aa= document.getElementById('pinpages');checked = !checked;for (var i =0; i < aa.elements.length; i++) {aa.elements[i].checked = checked;}}</script>
	<div class="wrap">

		<h2><?=__( 'Pinterest Pin It Button For Images', 'ppibfi_translate' ); ?></h2>
		<div class="xcpinc">
		<h3><?=__( 'Settings', 'ppibfi_translate' ); ?> </h3>
		<p><?=__( 'Here you may change some settings for your Pinterest PIBFI plugin, although it is not necessary (we already have the optimal settings done for you).', 'ppibfi_translate' ); ?></p>
		<form method="post" action="#" id="frm1">

		<fieldset id="pinpages">
			<legend><?=__( 'Show "Pin It" button on following pages:', 'ppibfi_translate' ); ?> </legend>
			<p>
			<input type="checkbox" name="ppibfi_pg_all" id="ppibfi_pg_all" onclick="checkedAll();" />
			<label for="ppibfi_pg_all"><?=__( 'All pages', 'ppibfi_translate' ); ?> </label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_index" id="ppibfi_pg_index" <?php echo $xcp_index?> />
			<label for="ppibfi_pg_index"><?=__( 'Index / home', 'ppibfi_translate' ); ?> </label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_single" id="ppibfi_pg_single" <?php echo $xcp_single?> />
			<label for="ppibfi_pg_single"><?=__( 'Single post', 'ppibfi_translate' ); ?> </label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_cat" id="ppibfi_pg_cat" <?php echo $xcp_cat?> />
			<label for="ppibfi_pg_cat"><?=__( 'Category page', 'ppibfi_translate' ); ?>	</label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_page" id="ppibfi_pg_page" <?php echo $xcp_page?> />
			<label for="ppibfi_pg_page"><?=__( 'Page', 'ppibfi_translate' ); ?> </label>
			</p>
		</fieldset>
		
		<fieldset>
			<legend><?=__( 'Default description:','ppibfi_translate' ); ?> </legend>
			<p><?=__( 'Select what will be sent as image description on pinned image', 'ppibfi_translate' ); ?> </p>
			<p>
			<select name="ppibfi_description" id="ppibfi_description">
				<option value="post_title" <?php if ( 'post_title' == $ppibfi_description ) echo 'selected'; ?>><?=__( 'Post title','ppibfi_translate' ); ?></option>
				<option value="post_title_link" <?php if ( 'post_title_link' == $ppibfi_description ) echo 'selected'; ?>><?=__( 'Post title | post link','ppibfi_translate' ); ?></option>
				<option value="post_link" <?php if ( 'post_link' == $ppibfi_description ) echo 'selected'; ?>><?=__( 'Post link','ppibfi_translate' ); ?></option>
				<option value="post_sitename" <?php if ( 'post_sitename' == $ppibfi_description ) echo 'selected'; ?>><?=__( 'Website name','ppibfi_translate' ); ?></option>
				<option value="post_sitename_sitelink" <?php if ( 'post_sitename_sitelink' == $ppibfi_description ) echo 'selected'; ?>><?=__( 'Website name | website link','ppibfi_translate' ); ?></option>
				<option value="post_sitename_title_link" <?php if ( 'post_sitename_title_link' == $ppibfi_description ) echo 'selected'; ?>><?=__( 'Website name | post name | post link','ppibfi_translate' ); ?></option>
			</select>
			
			</p>
		</fieldset>

		<?php wp_nonce_field('ppibfi_update_option','ppibfi_post_option'); ?>
		<span class="xcp_submitbkg">
			<input type="submit" name="submit" value="<?=__( 'Save', 'ppibfi_translate' ); ?>" class="xcp_submit" />
			<div class="xcp_c"></div>
		</span>
		<div class="xcp_c"></div>

	</div><!-- xcpinc -->
	<div class="xcpf">
		<h3><?=__( 'Advanced', 'ppibfi_translate' ); ?> </h3>
		<fieldset id="advanced">
			<legend><?=__( 'Exclude classes:', 'ppibfi_translate' ); ?> </legend>
			<p><?=__( 'Images with the following "class" attributes won\'t display the button (comma separated):', 'ppibfi_translate' ); ?> </p>
			<input type="text" name="ppibfi_exclude" id="ppibfi_exclude" value="<?php foreach ( $ppibfi_exclude as &$value)  echo $value.','; ?>" />
		</fieldset>

		<fieldset>
			<legend><?=__( 'Selected images only:', 'ppibfi_translate' ); ?> </legend>
			<p>
			<input type="checkbox" name="ppibfi_img_pinthis" id="ppibfi_img_pinthis" <?=$xcp_img?> />
			<label for="ppibfi_img_pinthis"><?=__( 'Show "Pin it" button only on images with class="pinthis"', 'ppibfi_translate' ); ?> </label>
			</p>
			<p><em><?=__( 'This option will overide the "Exclude classes" option.', 'ppibfi_translate' ); ?></em></p>
		</fieldset>

		<span class="xcp_submitbkg">
			<input type="submit" name="submit" value="<?=__( 'Save', 'ppibfi_translate' ); ?>" class="xcp_submit" />
			<div class="xcp_c"></div>
		</span>

		</form>
		<div class="xcp_c"></div>
	</div><!-- /xcpf (advanced) -->
	
	<div class="xcpf_help">
		<h3><?=__( 'Care to help?', 'ppibfi_translate' ); ?> </h3>
		<p><?=__( 'This plugin takes up a *lot* of my spare time and has cost me around US$ 500 in development out of my own pocket. Any donation amount (like five bucks) will be well appreciated as it\'ll give me more reason to work hard on new updates. Please consider donating! If you can\'t spare anything, consider helping spread the word about this awesome plugin via Twitter and Facebook.', 'ppibfi_translate' ); ?> </p>
		<p><?=__( 'Thanks!', 'ppibfi_translate' ); ?> </p>
		<div class="xcp_redes"><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wordpress.org/extend/plugins/pinterest-pin-it-button-for-images/" data-text="I'm using &quot;Pinterest Pin It Button For Images&quot; WP plugin on my site!" data-count="vertical" data-hashtags="pibfi">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
		<div class="xcp_redes"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwordpress.org%2Fextend%2Fplugins%2Fpinterest-pin-it-button-for-images%2F&amp;send=false&amp;layout=box_count&amp;width=60&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=127437144025252" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:90px;" allowTransparency="true"></iframe></div>
		<div class="xcp_redes">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="REQ63YWT5L46Q">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>



		</div><!-- xcp_redes -->
		<div class="xcp_redes">
		<script src="http://coinwidget.com/widget/coin.js"></script>
<script>
CoinWidgetCom.go({
	wallet_address: "1Pt3RzyygFUsnJhUbzewEB7eMRTijRQgm4"
	, currency: "bitcoin"
	, counter: "ammount"
	, alignment: "bl"
	, qrcode: true
	, auto_show: false
	, lbl_button: "Bitcoin"
	, lbl_address: "Donate w/ Bitcoin. My address:"
	, lbl_count: "donations"
	, lbl_amount: "BTC"
});
</script>
		</div>
		
		<div class="xcp_redes">
		<script src="http://coinwidget.com/widget/coin.js"></script>
<script>
CoinWidgetCom.go({
	wallet_address: "LTu15xN4rnKYnmfrmsCZLRBnp3mzLZJToa"
	, currency: "litecoin"
	, counter: "ammount"
	, alignment: "ar"
	, qrcode: true
	, auto_show: false
	, lbl_button: "Litecoin"
	, lbl_address: "Donate w/ Litecoin. My address:"
	, lbl_count: "donations"
	, lbl_amount: "LTC"
});
</script>
		</div>
		<div class="xcp_c"></div>
		<p><?=__( 'This plugin was developed by Sean "Canha" Berg @ <a href="http://canha.net" title="WordPress themes and plugins">canha.net</a>', 'ppibfi_translate' ); ?> </p>
	</div><!-- xcpf -->

	</div><!-- wrap -->
<?php
} //pibfi_engine_configs

function ppibfi_load_langs() {
	load_plugin_textdomain( 'ppibfi_translate', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
}


function ppibfi_media_script() {
	echo "
		<script type=\"text/javascript\">
			function ppibfi_media_popup() {
				window.send_to_editor = function(html) {
					imgurl = jQuery('img',html).attr('src');
					jQuery('#chosen_image').val(imgurl);
					jQuery('#chosen_image_display').attr('src', imgurl);
					jQuery('#chosen_image_link').attr('href', imgurl);
					jQuery('#chosen_image_info').show();
					tb_remove();
				}

				formfield = jQuery('#chosen_image').attr('name');
				tb_show('', '".get_admin_url()."media-upload.php?type=image&tab=library&TB_iframe=true');
				return false;
			}
		</script>";
}

function ppibfi_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );

	wp_enqueue_script( 'media-upload' );
}

function ppibfi_replace_thickbox() {
	global $pagenow;
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
		add_filter( 'gettext', 'ppibfi_replace_thickbox_text' , 1, 2 );
	}
}

function ppibfi_replace_thickbox_text( $translated_text, $text ) {
	if ( 'Insert into Post' == $text || __('Insert into Post') == $text ) {
		if ( false !== strpos( wp_get_referer(), 'pibfi_engine' ) ) {
			return __( 'Use this image', 'ppibfi_translate' );
		}
	}

	return $translated_text;
}

?>