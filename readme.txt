=== Multiple Image Upload ===
Contributors: bharatbhola
Donate link: https://www.paypal.me/bharat55441
Tags: multiple, image, upload
Requires at least: 2.8
Tested up to: 4.9.8
Requires PHP: 5.3
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

This plugin allow to upload multiple images for posts, pages or custom post. easy to enable/disable image upload option in post, page and custom post type. Also you can arrange images order using drag and drop.

== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png

== get_miu_images() ==

This function can be called from any template file to get attached images for the page/post being viewed.
It returns an array of the attached image URL or attached ID.

It take only one argument and it is default: 

1. **post_id** (integer) to get images linked to a specific post or default take current post images