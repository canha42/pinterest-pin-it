=== Pinterest Pin It Button For Images ===
Contributors: canha, pererinha, vhf
Donate link: http://desgn.me/pinterest
Tags: pinterest, pin-it, button, image, images, pinit, social media, hover, click, photo, photography, photographer
Requires at least: 3.2.1
Tested up to: 3.8
Stable tag: 1.1.0
License: GPLv2 or later

Add a "Pin It" button over your images, so users can add it to Pinterest easily.
== Description ==

Don't like the "Pin It" button underneath your post? Want to be able to add images directly to Pinterest by just clicking on the image? Your problems are over!

Have the user just hover the mouse over the image they want to pin and watch your visits grow!

*Long awaited version 1.1 with new features and better engine!*

I'm finally announcing version 1.1 with brand new features, a new front-end and a better engine. Just enable the plugin. The optimal configurations are set, but if you wish, we have a little panel under "Settings > Pinterest Pin It"
	
*Found any bugs? Head on to https://github.com/canha42/pinterest-pin-it and report it, please.*

Check out the new features in the Changelog.

Please consider donating any spare change to help me work on this plugin more. Donations can be made in the plugin settings page.

(This is an unofficial plugin and is not related to or endorsed by Pinterest or it's affiliates)

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configuration interface can be found under `Settings - Pinterest Pin It`

== Frequently Asked Questions ==

= Where can I change the plugins settings? =

After installed, go to your "Settings" menu and select "Pinterest Pin It". There, you'll have several settings you can change.

= How do I add the button only to specific images? =
On the plugin settings page, check the box under the "Advanced" section called "Selected images only". Head on to your post and edit it. If you are using HTML view: just add `class="pinthis"` to the image. If a "class" already exists, just add a space to the end of it and type `pinthis`. If you are using Visual view, click once on your image, select the "Edit image" button, click on the "Advanced settings" tab and add to the "CSS Class" field `pinthis`.

= Images with specific classes shouldn't show the button. Can this be done? =
Yes. In the plugin settings window, just type in the class you want to exclude from recieving the button. You can also use the default `nopin` class on your images.

= Can I disable the button on all images of a post? =
Yes. In your post editing, you will see a "Pinterest plugin" box to the right side of your window. There you can select "Disable Pinterest Pin It button on all images in this page".

= Can I add / change the "Pin This" button? =
Yes. Just open the ppibfi_pinterest.css file. You'll find instructions there on how to change your button.

= Where do I report bugs? =
In the WordPress forum (http://wordpress.org/support/plugin/pinterest-pin-it-button-for-images) or, even better (as I check it more often), our bug tracker at https://github.com/canha42/pinterest-pin-it/issues (requires registry). 

= Can I e-mail you with my problems? =
I enjoy writting this plugin and maintaining it, but it's not something I can afford on a daily basis. Please take into account I have a day job and helping people individually is not possible. Please use the forum listed above to post your problem so even other people can help you. Thanks for your comprehension! :)

== Screenshots ==

1. Button that shows on image hover
2. New settings panel
3. Button redirects to Pinterest, enabling the pinning.

== Changelog ==

= 1.1.0 =
* Released 2013-1-10
* Feature: New engine running - no longer JavaScript / JQuery will be needed to show the pin-it button.
* Feature: Annoying "Image is thought to be 1024 pixels" message removed
* Feature: Plugin translation (currently available : Brazilian Portuguese. Please help translating this plugin!)
* Feature: Opt-out option on single post and pages
* Feature: Select what classes of images won't have a button
* Feature: Show button only on images with class="pinthis"
* Feature: Uninstalls properly, removing options and cleaning stuff after removal
* Bugfix: CSS exported to a separate file (not anymore showing in the <head>)
* Bugfix: Now works with `short_open_tag` disabled
* Bugfix: Now option fields are sanitized
* Bugfix: Exclude- and opt-in- classes now correctly detected
* Bugfix: Button won't show on non-WP-inserted images (i.e. other social media icons, "buy this" buttons, etc.) anymore
* Bugfix: Post title / descriptions with ? and & would break Pinterests' description
* Bugfix: <?php is now being used instead of <?
* Bugfix: Now working with Lazy Load plugin
* Bugfix: On image hover wouldn't change the button's opacity in some cases
* Bugfix: HTML code in "Exclude classes" causing crashes
* Bugfix: My coffee machine was broken


= 1.0.1 =
* Released 2012-8-15
* Security: index.php file created, so directory won't be visible.
* Security: PHP files protected from direct loading.
* Feature: Warning is shown if content width is WordPress standard, or no width detected.
* Feature: Ability to set content width on plugin settings page.
* Bug: "All pages" bug, selecting all checkboxes on screen.
* Bug: Text shadow in plugin settings page removed
* Feature: Plugin translation-ready
* Feature: Exclude certain classes from showing the button (such as "wp-smiley")
* Feature: Only show button on images with "pinthis" class

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
If you have made changes to the button, please make sure to back it up before updating. Update will most likely wipe any changes you've made to the plugin.