<?php
/*
=====================
	Control panel
=====================
*/

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'ppibfi_admin.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Stop! Hammer time!');

function pibfi_Engine_configs() {
	if (isset($_POST['submit'])) {
		update_option('ppibfi_pg_index', htmlspecialchars(strip_tags($_POST['ppibfi_pg_index'])));
		update_option('ppibfi_pg_single', htmlspecialchars(strip_tags($_POST['ppibfi_pg_single'])));
		update_option('ppibfi_pg_page', htmlspecialchars(strip_tags($_POST['ppibfi_pg_page'])));
		update_option('ppibfi_pg_cat', htmlspecialchars(strip_tags($_POST['ppibfi_pg_cat'])));
		update_option('ppibfi_img_pinthis', htmlspecialchars(strip_tags($_POST['ppibfi_img_pinthis'])));
		update_option('ppibfi_opt_enable', htmlspecialchars(strip_tags($_POST['ppibfi_opt_enable'])));
		update_option('ppibfi_content_width', (int) $_POST['ppibfi_content_width']);
		$exclude_posts = array_filter(array_map('htmlspecialchars', explode(',', strip_tags($_POST['ppibfi_exclude']))));
		sort($exclude_posts);
		update_option('pibfi_NoShowButton', $exclude_posts);
		?><div class="updated"><p><strong><?=__('Options saved.', 'ppibfi_translate'); ?></strong></p></div><?php
	}
	 
	if (get_option('ppibfi_pg_index') == "on") $xcp_index = 'checked';
	if (get_option('ppibfi_pg_single') == "on") $xcp_single = 'checked';
	if (get_option('ppibfi_pg_page') == "on") $xcp_page = 'checked';
	if (get_option('ppibfi_img_pinthis') == "on") $xcp_img = 'checked';
	if (get_option('ppibfi_pg_cat') == "on") $xcp_cat = 'checked';
	if (get_option('ppibfi_opt_enable') == "on") $xcp_opt_enable = 'checked';
	$ppibfi_content_width = get_option('ppibfi_content_width');
	$ppibfi_exclude = get_option('pibfi_NoShowButton');
?>
<script type="text/javascript">checked=false;
function checkedAll () {var aa= document.getElementById('pinpages');checked = !checked;for (var i =0; i < aa.elements.length; i++) {aa.elements[i].checked = checked;}}</script>
	<div class="wrap">

		<h2>Pinterest Pin It Button For Images</h2>
		<div class="xcpinc">
		<p><?=__('Here you may change some settings for your Pinterest PIBFI plugin, although it is not necessary (we already have the optimal settings done for you).', 'ppibfi_translate'); ?></p>
		<form method="post" action="#" id="frm1">
		
		<fieldset>
			<legend><?=__('Content width:','ppibfi_translate'); ?>	</legend>
			<p><?=__('The plugin needs to know the width of the content area in pixels to place the button (only numbers).','ppibfi_translate'); ?> </p>
			<p>
			<input type="number" name="ppibfi_content_width" id="ppibfi_content_width" value="<?=$ppibfi_content_width?>" />
			</p>
		</fieldset>
		
		<fieldset id="pinpages">
			<legend><?=__('Show "Pin It" button on following pages:','ppibfi_translate'); ?> </legend>
			<p>
			<input type="checkbox" name="ppibfi_pg_all" id="ppibfi_pg_all" onclick="checkedAll();" />
			<label for="ppibfi_pg_all"><?=__('All pages','ppibfi_translate'); ?> </label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_index" id="ppibfi_pg_index" <?=$xcp_index?> />
			<label for="ppibfi_pg_index"><?=__('Index / home','ppibfi_translate'); ?> </label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_single" id="ppibfi_pg_single" <?=$xcp_single?> />
			<label for="ppibfi_pg_single"><?=__('Single post','ppibfi_translate'); ?> </label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_cat" id="ppibfi_pg_cat" <?=$xcp_cat?> />
			<label for="ppibfi_pg_cat"><?=__('Category page','ppibfi_translate'); ?>	</label>
			</p>
			<p>
			<input type="checkbox" name="ppibfi_pg_page" id="ppibfi_pg_page" <?=$xcp_page?> />
			<label for="ppibfi_pg_page"><?=__('Page','ppibfi_translate'); ?> </label>
			</p>
		</fieldset>
		
		<fieldset>
			<legend><?=__('Opt-out on single pages:','ppibfi_translate'); ?> </legend>
			<p><?=__('Option to enable a checkbox on singles and pages that will let you choose if the plugin will be deactivated on that particular page','ppibfi_translate'); ?> </p>
			<p>
			<input type="checkbox" name="ppibfi_opt_enable" id="ppibfi_opt_enable" value="on" <?=$xcp_opt_enable?> />
			<label for="ppibfi_opt_enable"><?=__('Enable opt-out','ppibfi_translate'); ?> </label>
			</p>
		</fieldset>
		
		<input type="submit" name="submit" value="<?=__('Save', 'ppibfi_translate'); ?>" class="xcp_submit" />
		
	</div><!-- xcpinc -->
	<div class="xcpf">
		<h3><?=__('Advanced','ppibfi_translate'); ?> </h3>
		<fieldset id="advanced">
			<legend><?=__('Exclude classes:','ppibfi_translate'); ?> </legend>
			<p><?=__('Images with the following "class" attribute won\'t display the button (comma separated):','ppibfi_translate'); ?> </p>
			<input type="text" name="ppibfi_exclude" id="ppibfi_exclude" value="<?php echo implode(',', $ppibfi_exclude); ?>" />
		</fieldset>
		
		<fieldset>
			<legend><?=__('Selected images only:','ppibfi_translate'); ?> </legend>
			<p>
			<input type="checkbox" name="ppibfi_img_pinthis" id="ppibfi_img_pinthis" <?=$xcp_img?> />
			<label for="ppibfi_img_pinthis"><?=__('Show "Pin it" button only on images with "pinthis" class','ppibfi_translate'); ?> </label>
			</p>
			<p><?=__('This option will overide the "Exclude classes" and "Enable opt-out" options.','ppibfi_translate'); ?> </p>
		</fieldset>
		
		<input type="submit" name="submit" value="<?=__('Save', 'ppibfi_translate'); ?>" class="xcp_submit" />
		
		</form>
	</div><!-- /xcpf (advanced) -->
	<div class="xcpf">
		<h3><?=__('Care to help?','ppibfi_translate'); ?> </h3>
		<p><?=__('This plugin takes up a *lot* of my spare time and has cost me around US$ 500 in development out of my own pocket. Any donation amount (like five bucks) will be well appreciated as it\'ll give me more reason to work hard on new updates. Please consider donating! Link to PayPal below.','ppibfi_translate'); ?> </p>
		<p><?=__('If you are a programmer willing to help out with the development, please drop me a line at canha (at) design.blog.br. But if you don\'t have any "mad skillz", you can also help by reporting bugs at <a href="https://github.com/canha42/pinterest-pin-it/issues">GitHub</a>. Please help spread the word about this plugin!','ppibfi_translate'); ?> </p>
		<p><?=__('Thanks!','ppibfi_translate'); ?> </p>
		<div class="xcp_redes"><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wordpress.org/extend/plugins/pinterest-pin-it-button-for-images/" data-text="I'm using &quot;Pinterest Pin It Button For Images&quot; WP plugin on my site!" data-size="large" data-hashtags="pibfi">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
		<div class="xcp_redes"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwordpress.org%2Fextend%2Fplugins%2Fpinterest-pin-it-button-for-images%2F&amp;send=false&amp;layout=box_count&amp;width=100&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90&amp;appId=127437144025252" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:90px;" allowTransparency="true"></iframe></div>
		<div class="xcp_redes">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/pt_BR/i/scr/pixel.gif" width="1" height="1">
</form>


		</div><!-- xcp_redes -->
		<br style="clear:both" />
		
	</div><!-- xcpf -->

	</div><!-- wrap -->
<?php
} //pibfi_Engine_configs

?>