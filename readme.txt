=== Plugin Name ===
Contributors: exoboy
Plugin Name: WXY Tools Media Replace
Plugin URI: https://www.wxytools.com
Author URI: https://www.bowmandesignworks.com/
Author: WXY Tools
Tags: admin, media, library, attachments, replace, uploads, media replace
Donate link:
Requires at least: 4.0
Tested up to: 5.6.2
Requires PHP: 7.0
Stable tag: 1.0.0
Version: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically replace duplicate uploaded content in your media library while preserving any associated meta data.

== Description ==
How many times have you been given revisions to an image or documents for your WordPress Media Library and needed to manually remove the old versions from the site before you could upload the new image attachments?

Well, this plugin works quietly in the background on any attachment upload screen and looks for a duplicate matching attachment and replaces it with your new attachment. No need to go to a special window or panel just to replace an image, the plugin automatically intercepts the upload and takes cares of the housekeeping.

Matching is based on filenames, so the new and old filenames must be the same. This plugin searches all of your uploads folders and subfolders to find every instance of your old attachment and replaces them with the new version of your attachment so you don't need to find and replace all your linked images in your website.

New thumbnails are also generated which will reflect the new content of your latest upload(s). Also, if you have saved any meta data in the upload's Attachment Details, these will be preserved and moved to the new upload's Attachment Details.

Works with single or multiple file uploads. The plugin also only loads if you are signed in to the admin area and have upload privileges.

Temporarily turning the plugin on or off, or hiding the status messages is easy. There is a settings button on all messages or you can go to the setting menu and select the Media Replace option. See screenshots below for more details.

== Installation ==
Automatic Plugin Installation

To add a WordPress Plugin using the built-in plugin installer:
1. Go to Plugins > Add New.

2. Type in the name of the WordPress Plugin or descriptive keyword, author, or tag in Search Plugins box or click a tag link below the screen.

3. Find the WordPress Plugin you wish to install.

4. Click Details for more information about the Plugin and instructions you may wish to print or save to help setup the Plugin.

5. Click Install Now to install the WordPress Plugin.

6. The resulting installation screen will list the installation as successful or note any problems during the install.

7. If successful, click Activate Plugin to activate it, or Return to Plugin Installer for further actions.

== Frequently Asked Questions ==

= Who needs this plugin? =

Anyone who updates the attachments in their media library and don't want to chase down links throughout their site to update them with the new image filename. Eliminate the time spent finding your old attachments, manually deleting them, then uploading a new one and manually verifying that WordPress did not assign a new "-1", "-2", "-3", etc. addition to its filename.

== Screenshots ==

1. WXY Tools Media Replace - Post Edit Meta Box
2. WXY Tools Media Replace - Page Edit Meta Box
3. WXY Tools Media Replace - Media Library Status Bar
4. WXY Tools Media Replace - Settings Area

== Changelog ==

= 0.0.1 =
* This is the first release of this plugin.

= 0.0.2 =
* Fixed issue where some meta data for certain non-image file types was not being preserved.
* Added fallback to refresh page when upload is completed, to make sure the Media Library's thumbnails are properly updated.

= 1.0.0 =
* Fixed support for attachments using nested folders for storage in the uploads folder

== Copyright ==
WXY Tools Media Replace is Copyright 2017-Present Clarence "exoboy" Bowman and wxytools.com
WXY Tools Media Replace is distributed under the terms of the GNU GPL

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
