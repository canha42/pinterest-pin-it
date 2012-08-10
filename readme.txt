=== Pinterest Pin It Button For Images ===
Contributors: canha
Donate link: http://desgn.me/pinterest
Tags: pinterest, pin-it, button, image, images, pinit, social media, hover, click, photo, photography, photographer
Requires at least: 3.2.1
Tested up to: 3.4.1
Stable tag: 1.0.1
License: GPLv2 or later

Add a "Pin It" button over your images, so users can add it to Pinterest easily.

== Description ==

Don't like the "Pin It" button underneath your post? Want to be able to add images directly to Pinterest by just clicking on the image? Your problems are over!

Have the user just hover the mouse over the image they want to pin and watch your visits grow!

The description goes automatically as the name of the post, and so does the link of the post.

Just enable the plugin. The optimal configurations are set, but if you wish, we have a little panel under "Settings > Pinterest Pin It"
	
*Found any bugs? Head on to http://canha.net/bugs/ and report it, please*

Check out the new features in the Changelog.

Please consider donating any spare change to help me work on this plugin more. Donations can be made at: http://desgn.me/pinterest

(This is an unofficial plugin and is not related to or endorsed by Pinterest or it's affiliates)

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configuration interface can be found under `Settings - Pinterest Pin It`

== Frequently Asked Questions ==

= Are there any know bugs? =

Yes. Websites with many images per post or floating may present problems. If you have the "smileys" activated, those also may present problems. Opt-out only works on single and pages, not on index. Button can't be changed (yet!)

= Are you going to solve these bugs? =
Yes. I am constantly trying to make the plugin better, but as it's a free plugin I don't get much time to work on it. Please consider donating any spare change to help me work on this plugin more. Donations can be made at: http://desgn.me/pinterest

= Where do I report bugs? =
In the WordPress forum (http://wordpress.org/support/plugin/pinterest-pin-it-button-for-images) or, even better (as I check it more often), our bug tracker at http://canha.net/bugs/ 

== Screenshots ==

1. Button that shows on image hover
2. New settings panel

== Changelog ==

= 1.0.1 =
* index.php file created, so directory won't be visible.
* PHP files protected from direct loading.
* Warning is shown if content width is WordPress standard, or no width detected

= 1.0 =
* Released 2012-8-07
* Code re-written from scratch.
* Images won't be anymore inside DIVs.
* SPANs are appended to the image, not ruining the code or layout.
* All code run by jQuery, avoiding slowness on servers side.
* Settings window created.
* Change which pages the Pin It button will show on (single, index, category, page).
* Change how to button will react (open in new tab, same tab, popup window).
* Won't load if mobile version is being viewed (avoids crashing)
* Now working with captioned images
* Ability to opt-out on singles and pages
* Works with most well known image galleries / shortcodes

= 0.3.1 =
* Released 2012-5-30
* Fixed bug that will, in some cases, crash the site.

= 0.3 =
* Released 2012-5-29
* Pinterest will open in a new, pop-up window (so the user doesn't have to click to go back)

= 0.2 =
* Released 2012-4-9
* Make sure the plugin only loads in the blog, not the WordPress Admin page.
* Images with links will have the original link preserved
* Works with Fancybox (Thanks gleenk for the heads-up)

= 0.1 =
* Released 2012-3-25
* Open beta test

== Upgrade Notice ==