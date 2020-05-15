=== Force Thumbscale ===
Contributors: bastianfiessinger
Tags: media, attachments, images, image, thumbnails, thumbnail, aspect ratio, scale
Requires at least: 3.5.1
Tested up to: 5.4.1
Stable tag: 1.0.0
License: GPL3

Maintains aspect ratio of thumbnails even if the source image is smaller than the registerd image size

== Description ==
Maintain aspect ratio of media attachments that are smaller than the registered image size. WordPress by default does not resize media attachments that are 
smaller than the registered image size, that means if you upload a featured image to a post and want to display that image in the same format every time, it
won't work if your original image is smaller than the image size registered in your theme.

This plugin won't upscale your Thumbnails but guarantees that the aspect ratio will be maintained!

Important: Your thumbnails format must have the crop setting enabled to be processed by this plugin.

**Usage**

After you have enabled Thumbnail Upscale plugin, all your future uploaded images will be scaled.
For existing images, install and run a Plugin like [Regenerate Thumnails Advanced](https://de.wordpress.org/plugins/regenerate-thumbnails-advanced/)

== Requirements ==
* PHP 5.2 or higher strongly recommended

== Translations ==
None. The plugin has no translateable strings.

== Installation ==
1. Upload the `thumbscale` folder to `/wp-content/plugins/`
2. Activate the plugin (Thumbscale) through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0.0 =
Initial release