=== ELI's SHAREABLE Widget and In-Bound Link Tracking ===
Plugin URI: http://wordpress.ieonly.com/category/my-plugins/terribl-widget/
Author: Eli Scheetz
Author URI: http://wordpress.ieonly.com/category/my-plugins/
Contributors: scheeeli
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8VWNB5QEJ55TJ
Tags: Eli, Shareable, share, referral, sidebar, widget, trading, track, in-bound, links, clicks, impressions, stats, list, referer ,plugin
Stable tag: 1.2.11.20
Version: 1.2.11.20
Requires at least: 2.6
Tested up to: 3.4.2

Place the SHAREABLE Widget on your sidebar to display a link to your site, that visitors can copy to their own site (features easy-to-copy link code).

== Description ==

SHAREABLE makes it easy to distribute links to your site. Just place the Widget on your sidebar to display a link to your site, that visitors can copy to their own site (features easy-to-copy link code). You can even customize the style of the link and use external images.

After installation this Plugin will start collecting in-bound clicks and impression statistics immediately.

Updated Nov-7th

== Installation ==

1. Download and unzip the plugin into your WordPress plugins directory (usually `/wp-content/plugins/`).
1. Activate the plugin through the 'Plugins' menu in your WordPress Admin.
1. Place the widget on your sidebar through the 'Widgets' menu in your WordPress Admin.

== Frequently Asked Questions ==

= What do I do after I activate the Plugin? =

Go to the Widgets menu in the WordPress Admin and add the "In-Bound Links" Widget to your Sidebar or Footer Area.

= Why am I not seeing the Widget on my site after I add it to the sidebar? =

The Widgets only shows up after the plugin has logged traffic from another site. Try clicking to your site from a link on another site or search for your site on Google and click on the link to your site.

= Why do Blocked Sites still show in the In-Bound Links Report in my admin? =

You cannot block a site from linking to your site and the report shows all the In-Bound Links that the plugin has logged. By blocking a site you are telling the plugin not link back to this site, no matter what, even if they keep on linking to you.

= Why do the Google links on the Widget just loop back to my site? =

In October of 2011 Google announced that signed-in users will, by default, be routed to the SSL version of Google Search. So now, when someone finds your site through Google's SSL Search, the HTTP_REFERER is not actually the page that they found your site on but rather a special redirect page that forwards traffic to your site without passing query information. This means that if you try to go back to the page that they came from it just loops back to your site.

== Screenshots ==

1. The SHAREABLE Link Widget with a simple example link for [wordpress.ieonly.com](http://wordpress.ieonly.com/).

2. The same Widget, when you hover over the link, the easy-to-copy code is displayed.

3. The In-Bound Links Widget with examples of returned links and a fancy example of a SHAREABLE link to [GOTMLS.NET](http://gotmls.net/).

4. The SHAREABLE Link Settings page with all settings for [GOTMLS.NET](http://gotmls.net/) link.

5. The In-Bound Links Widget settings on the Widget Admin page with the options showing.

6. The TERRIBL Stats page with some example stats.

== Changelog ==

= 1.2.11.20 =
* Fixed conflicts with the image uploader and opther plugins by not setting POST variables.

= 1.2.11.05 =
* Improved load time and impression counting techniques.

= 1.2.11.04 =
* Added a whole new Widget that makes link sharing easy and customizable.

= 1.2.04.22 =
* Changed the In-Bound Links Report and Widget to order Validated Links by Impressions first and then by clicks.
* Added a freature to Re-Validate All Valid Links with one click to make it easy to clear out sites that don't link to you anymore.

= 1.2.02.22 =
* Fixed link verification to fail sites that are not available when checked.
* Changed the In-Bound Link Report to only show you the Validated Links unless you check the box to see them all.

= 1.2.02.03 =
* Fixed menu icon path and removed debug output for known errors.

= 1.2.01.30 =
* Improved link validation for better compatibility with return links from other blogs using this plugin.

= 1.2.01.26 =
* Fixed the recheck link on the setting page so that it will remove the link from the Widget if it does find a returning link.
* Moved all the Stats to the Stats page except for the Referring Sites list.
* Added a few different options for where to place the Menu Item to the Settings and Stats Pages.

= 1.1.12.23 =
* Fixed the Illegal mix of collations error produced on some servers that defaulted to non-utf8 charsets.

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

= 1.2.11.20 =
Fixed conflicts with the image uploader and opther plugins by not setting POST variables.

= 1.2.11.05 =
Improved load time and impression counting techniques.

= 1.2.11.04 =
Added a whole new Widget that makes link sharing easy and customizable.

= 1.2.04.22 =
Changed the In-Bound Links Report and Widget sort order and added a freature to Re-Validate All Valid Links.

= 1.2.02.22 =
Fixed link verification to fail sites that are not available when checked and changed the In-Bound Link Report to only show you the Validated Links.

= 1.2.02.03 =
Fixed menu icon path and removed debug output for known errors.

= 1.2.01.30 =
Improved link validation for better compatibility with return links from other blogs using this plugin.

= 1.2.01.26 =
Fixed the recheck link on the setting page, moved all the Stats to the Stats, and added a few different options for Menu Item placement.

= 1.1.12.23 =
Fixed the Illegal mix of collations error produced on some servers that defaulted to non-utf8 charsets.

= 1.1.12.21 =
Minor cosmetic changes to the stats pages and removed useless code.

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

