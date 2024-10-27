=== Ad Buttons ===
Contributors: mindnl
Donate link: http://blogio.net/blog/donate/
Tags: ads, buttons, advertising, monetizing, AdSense, 125, widget, sidebar, plugin, links, admin, google
Requires at least: 2.8.0
Tested up to: 4.9
Stable tag: 3.1

The Ad Buttons plugin displays a number of graphical ads in a sidebar widget.

== Description ==
The Ad Buttons plugin displays a number of graphical ads in a sidebar widget

The current version contains the following functionality:

Add new ad buttons:
By entering image URL, link URL and link text a new ad button will be created

Enable/disable individual ad buttons:
Each ad button can be enabled or disabled from the admin panel

Select how many ad buttons to display in the sidebar widget.
Displaying the ad buttons on your blog is done by randomly selecting ads from your total list of active ads. You can select how many ads are displayed on your blog.

See how many times each ad button has been displayed and clicked.
Ad performance is an important measurement, especially when your ads link to affiliate programs. The number of views, clicks and CTR (click thru rate) are displayed for each ad button. Views by search engine bots are automatically filtered from the count.

A Google AdSense 125 x 125 ad unit can be displayed by filling in your AdSense publisher ID. AdSense ad colors can be controlled right from the Ad Buttons admin panel.


== Installation ==

To install this plugin unzip the downloaded file and upload the entire ad buttons folder to your blogs plugin folder ( \wp-content\plugins\ ) the plugin should now show up in the WordPress plugins panel, available for activation.

== Changelog ==

= 3.1 =
* 02-08-2018
* bugfix to enable/disable individual ads

= 3.0 =
* 29-06-2018
* complete overhaul to make the plugin more secure 

= 2.3.2 =
* 24-01-2017
* added Nonce to admin pages 
* bugfix for XSS vulnerability
* bugfix to the colorpicker

= 2.3.1 =
* 08-09-2014
* updated ip2nation database

= 2.3 =
* 15-07-2014
* added Ad Buttons Ad Network signup page

= 2.2.1 =
* 09-07-2014
* bugfix to the database upgrade process

= 2.2 =
* 08-07-2014
* updated ip2nation database 
* cleanup of historical statistics

= 2.1.8 =
* 23-04-2014
* updated ip2nation database 
* added the option to ommit logged-in users' views and clicks from being counted

= 2.1.7 =
* 23-05-2011
* updated ip2nation database 
* changed the installation process of the ip2nation database so you can see it is still working and has not timed out

= 2.1.6 =
* 14-12-2009
* fixed a bug in the css that messed up the layout in some themes

= 2.1.5 =
* 14-12-2009
* fixed a bug in the css that messed up the layout in some themes

= 2.1.4 =
* 13-11-2009
* added the option to display the ads in a fixed order

= 2.1.3 =
* 09-11-2009
* updated ip2nation database

= 2.1.2 =
* 01-11-2009
* fixed a bug that messed up affiliate urls with an extra http or https in the url parameters

= 2.1.1 =
* 21-10-2009
* changed the 'compatible up to' version information to match the latest wordpress release

= 2.1 =
* 30-09-2009
* extended the stats graphing to be able to switch to other months

= 2.0 =
* 26-09-2009
* incorporated adbuttons.net ad network. 

= 1.7.1 =
* 23-09-2009
* select a custom url for the 'your ad here' button

= 1.7 =
* 14-09-2009
* added geo targeting capabilities

= 1.6.6 =
* 12-09-2009
* statistics graphic output bug fixed

= 1.6.5 =
* 10-09-2009
* changed the plugindir retrieval to fix errors when installation directory differs from blog directory

= 1.6.4 =
* 05-09-2009
* fixed some bugs in the statistics graphic output

= 1.6.3 =
* 04-09-2009
* added support for https links

= 1.6.2 =
* 03-09-2009
* Stats have been improved 

= 1.6.1 =
* 02-09-2009
* graphical view and click statistics have been added

= 1.6.0 =
* 01-09-2009
* detailed view and click tracking will be stored in a separate table. Viewing the statistics will be incorporated into a future release. The views and clicks as seen on the admin page can be altered, this does not affect detailed stats and should be seen as a trip meter.

= 1.5.3 =
* 31-08-2009
* Added a little note to the widget, so people can find the Ad Buttons settings menu

= 1.5.2 =
* 24-08-2009
* css styling can now be disabled on the settings page since it can break the layout on some wordpress themes

= 1.5.1 =
* 22-08-2009
* Database has been changed to accomodate very long URL's like the affiliate links from amazon.com et al. 

= 1.5.0 =
* 20-08-2009
* a 'your ad here' button has been added that can be linked to an existing page on your wordpress site

= 1.4.3 =
* 19-08-2009
* custom widget title was deleted when any other option on the settings page was changed: fixed.

= 1.4.2 =
* 18-08-2009
* small 'powered by Ad Buttons' link, can be disabled if needed. 

= 1.4.1 =
* 17-08-2009
* optional nofollow tag selectable on the settings page

= 1.4.0 =
* 17-08-2009
* ad scheduling by specifying a start and end date has been added

= 1.3.0 =
* 15-08-2009
* edit function has been added to change individual ad buttons

= 1.2.2 =
* 13-08-2009
* fixed error message on the permalinks screen caused by this plugin

= 1.2.1 =
* 12-08-2009
* incorporated color picker by http://www.dhtmlgoodies.com to control Google AdSense ad colors

= 1.2.0 =
* 11-08-2009
* ad layout properties can now be controlled from within the admin menu with a handy preview 

= 1.1.0 =
* 11-08-2009
* google AdSense ads have been incorporated

= 1.0.5 =
* 09-08-2009
* fixed permission problem in the activation/deactivation of ads and added a delete option

= 1.0.4 =
* 08-08-2009
* link target attribute is now controlled by a configuration setting. ( _top, _blank or not used)
* menu structure has been changed, everything related to the ad buttons plugin is now located under the Ad Buttons top-level menu.
* added a shiny new wordpress style custom icon to the admin menu
* division by zero bug on the stats page has been suppressed

= 1.0.3 =
* 07-08-2009
* installation bug fixed that prevented the installation procedure, which creates the database table, from running

= 1.0.2 =
* 06-08-2009
* custom widget title bug fixed

= 1.0.1 =
* 21-07-2009
* minor bugfix to prevent double count of clicks

= 1.0 =
* 20-07-2009 
* first release
