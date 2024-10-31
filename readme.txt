=== Sales Cheater ===
Contributors: queeez
Donate link: https://sites.google.com/site/manfredfettinger/divers/wordpress
Tags: sell generator, sales cheater, cheat, cheater, internet marketing
Requires at least: 2.8
Tested up to: 3.4.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Tells your visitor how many pieces of a product were sold - even if no one was sold.

== Description ==

You are running Internet marketing and want to sell your products? This plugin helps you to make your products more interesting. There is a text anywhere to put in your website, which indicates how many pieces you have now sold.
A customer buys more naturally when other do and this is the big advantage of using the plugin. Of course, the daily sales figures have to be different that it really works well.
With this plugin, you can determine how often your fictitious sales are updated and in what number of pieces. Here you choose one of the upper and lower limit.


== Installation ==

1. Upload directory `salescheater` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to `WP-Admin -> Sales Cheater`


== Frequently Asked Questions ==

= How does it work? =

You define a time period how often a "sell" should be generated and your minumum and maximum "sales" for each time period.
At each page visit, the plugin checks if there are already "sells" are generated for today. 
If no "sells" were already generated, the plugin will automatically generate "sells" for each time period you entered in the settings-menu.
The generated "sells" are randomly determined between the minimum and maximum value from the settings-menu. That means "sells" are 
generated every day when the first visitor visits your homepage!

After that check the plugin sums up all sells until the actual time and will replace the content [salescheater] with the output message.

= Are sells the same every day? =

No, because sells are generated through a random method!

= Where can i change the displayed text =

In the Admin-Panel there is the SalesCheater Menu. Please go to settings and change the text template. You can insert there your html-code
you want. Also you can use follwing variables:

* %SC_DATE% - Displays the actual date
* %SC_SELLS% - Displays the sells until now

== Upgrade Notice ==

Before upgrading deinstall and install afterwards new!

1. Deactivate `Sales Cheater` Plugin
2. Delete `Sales Cheater` Plugin
3. Upgrade
4. Upload directory `salescheater` to the `/wp-content/plugins/` directory
5. Activate the plugin through the 'Plugins' menu in WordPress
6. Go to `WP-Admin -> Sales Cheater`

== Screenshots ==

1. Admin - Settings Menu
2. Admin - Maintainance Menu
3. Admin - Unintstall Menu
4. How to insert code into an article
5. Output Example

== Changelog ==

= Version 1.00 (09-11-2012) =
* NEW: Initial Release



