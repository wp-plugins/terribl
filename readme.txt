=== Track Every Referer and Return In-Bound Links - TERRIBL ===
Plugin Name: Track Every Referer and Return In-Bound Links - TERRIBL
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/terribl-widget/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/
Contributors: scheeeli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8VWNB5QEJ55TJ
Tags: widget, plugin, sidebar, track, referer, trade, in-bound, links, list, link trader
Stable tag: 1.1.12.21
Version: 1.1.12.21
Requires at least: 2.6
Tested up to: 3.3

This plugin is not terrible it's TERRIBL. It simply Tracks Every Referer and Returns In-Bound Links.

== Description ==

Just place the Widget on your sidebar to display a link to the HTTP_REFERER and any other sites that you would like to trade links with.

This plugin makes link trading easy and helps you track the effectiveness of your in-bound links.

Plugin

== Installation ==

1. Download and unzip the plugin into your WordPress plugins directory (usually `/wp-content/plugins/`).
1. Activate the plugin through the 'Plugins' menu in your WordPress Admin.
1. Place the widget on your sidebar through the 'Widgets' menu in your WordPress Admin.

== Frequently Asked Questions ==

= What do I do after I activate the Plugin? =

Go to the Widgets menu in the WordPress Admin and add the "In-Bound Links" Widget to your Sidebar or Footer Area.

= Why am I not seeing the Widget on my site after I add it to the sidebar? =

The Widgets only shows up after the plugin has logged traffic from another site. Try clicking to your site from a link on another site or search for your site on Google and click on the link to your site.

== Screenshots ==

1. This is a screen shot of the Widget with all it's options showing.

2. This is a screen shot of the Admin Menu with some example stats.

== Changelog ==

= 1.1.12.21 =
* Minor cosmetic changes to the stats pages.
* Removed the Google SSL search result accomodations that never really fixed anything.

= 1.1.12.20 =
* Fixed the widget to use the new field aliases that were changed in the last release.

= 1.1.12.19 =
* Added a "More Stats" page in the admin with some More Stats.
* Expanded accomodations for Google's new SSL search results to all Domains.

= 1.1.11.19 =
* Fixed a bug that prevented new Referers from being added to the Widget automatically.
* Added a feature on the Settings Page to reset and reverify In-Bound Links Status.

= 1.1.11.18 =
* Added CURL call to fix new verification on servers that do not support file_get_contents.

= 1.1.11.17 =
* Fixed ability to override verification and force a Referer to show in the Widget.

= 1.1.11.16 =
* Added In-Bound Link Verification to validate that a Referer actually has a link to your site on their page.
* Improved the reports and added styles to the stats page.

= 1.1.11.12 =
* Added more error checking to create tables that were not created when you upgraded.

= 1.1.11.11 =
* Added Error checking to create tables that were not created if you upgraded without reactivating the plugin.
* Made some accomodations for Google's new SSL search results so that the link does not just loop you back to your site.

= 1.1.11.10 =
* Fixed option to manually block a site.
* Updated Admin Menu icon.
* Added Plugin Updates section to the right side in the Admin pages.

= 1.1.11.08 =
* Added option to manually add a site to kick off your In-Bound Links Widget.
* Added ability to block an undesirable site from ever showing in your In-Bound Links Widget.
* Changed menu image updater to just direct link so it does not take so long to load the page.
* Grouped Links on Widget to prevent duplication and updated stats page to match.

= 1.1.11.06 =
* Updated Menu, Logo, and Links on Admin Pages.
* Resorted links on the Widget so that it displays more recent Referers first.

= 1.1.11.03 =
* Removed the debug output in the Widget that displays if there was no HTTP_REFERER.

= 1.1.11.02 =
* Fixed bugs in the Widget that caused it not to display the links correctly.
* Fixed bugs in the init so that it would log links to the root directory of your site.

= 1.1.11.01 =
* First versions uploaded to WordPress.

== Upgrade Notice ==

= 1.1.12.21 =
* Minor cosmetic changes to the stats pages and removed useless code.

= 1.1.12.20 =
Fixed the widget to use the new field aliases that were changed in the last release.

= 1.1.12.19 =
Added a More Stats page and expanded accomodations for Google SSL search results to all Domains.

= 1.1.11.19 =
Fixed bug that prevented new Referers from being visible on the Widget and added a feature to reset and reverify Links Status.

= 1.1.11.18 =
Added CURL call to fix new verification on servers that do not support file_get_contents.

= 1.1.11.17 =
Fixed ability to override verification and force a Referer to show in the Widget.

= 1.1.11.16 =
Added In-Bound Link Verification to validate that a Refering page actually contains a link to your site and improved the stats page.

= 1.1.11.12 =
Added more error checking to create tables that were not created when you upgraded.

= 1.1.11.11 =
Added Error checking to create tables that were not created if you upgraded without reactivating the plugin.

= 1.1.11.10 =
Fixed option to manually block a sites, updated Admin Menu icon, and added Update checker.

= 1.1.11.08 =
Added options to manually add and block sites and grouped Links on the Widget to prevent duplication.

= 1.1.11.06 =
Updated Menu, Logo, and Links on Admin Pages and Resorted links on the Widget to put recent Referers first.

= 1.1.11.03 =
Removed the debug output in the Widget that displays if there was no HTTP_REFERER.

= 1.1.11.02 =
Fixed lots of bugs in the Widget.

= 1.1.11.01 =
First versions available through WordPress.

