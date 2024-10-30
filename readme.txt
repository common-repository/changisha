=== Changisha ===

Contributors: eneokazi254
Donate link: https://eneokazi.com/donate
Tags: donate, donation, subscription, subscribe, e-commerce, store, sales, sell, woo, shop, cart, checkout, downloadable, downloads, payments, paypal, storefront, stripe, woo commerce, mpesa, safaricom, gateway, api
Requires at least: 4.7
Tested up to: 5.4
Requires PHP: 7.0
Stable tag: 1.0.0
License: GPLv3 or later License
URI: http://www.gnu.org/licenses/gpl-3.0.html

Changisha is an MPESA donation plugin built for WordPress.

== Description ==

Changisha is an MPESA donation plugin built for WordPress. It allows all types of organisations to setup and collect donations via their website's using MPESA as their preferred choice of banking solution.

The plugin enables a user to donate money using Lipa na MPESA mobile money service from a WordPress site that has this plugin installed and activated. 

So as to use the plugin, one must get a Lipa na MPESA Till number, which is a unique number that acts as an account where all donations made by your users on your website get channeled. 

After this, one must create an account on the Safaricom's Daraja Portal and use this plugin to link their Lipa na MPESA Till number from their created account on the portal to their website. 

The portal enables you to get the following:

-	Passkey

-	Consumer Key

-	Consumer Secret

-	And API Endpoints i.e. Sandbox/Production URLs which authenticate and process a donation request.

These details are to be filled after activating the plugin and are stored on your website.

The above setup is to ensure that it is the site owner who has full control over the payment details of their Till number.

When the user fills out a donation form on a page, the plugin initiates a donation request which is sent to their mobile phone. 

The user then either accepts or declines the donation request directly from their mobile phone and a callback gets sent from the portal with the details of the user's action. 

It is then used to determine whether to change the status of their donation or not.

#### How to use: ####

1.	Upload Changisha plugin files to the wordpress plugins directory (/wp-content/plugins/), or install the plugin from the WordPress admin plugin screen.

2.	Activate the plugin.

3.	On the WordPress admin, navigate to Changisha > Settings > then fill in the fields provided in order for the plugin to work.

#### Plugin features: ####

* Compatible with WordPress themes.

* Easy to use.

* Lightweight.

* Supports all modern browsers.

= Minimum Requirements =

* PHP 7.2 or greater is recommended
* MySQL 5.6 or greater is recommended

== Screenshots ==

1. The plugin allows a user to add the donate widget form on the footer or sidebar of their website as shown here.

2. The plugin also allows a user to add the donate widget form on a post or page of their website as shown here.

3. The plugin has a dashboard page where administrators can get the donate widget shortcode and some other useful information as shown here.

4. The plugin has a settings page where administrators can enter their MPESA API settings to fully setup the plugin as shown here.

== Installation ==

1. Unzip files.

2. Upload the folder into your plugins directory.

3. Activate the plugin.

4. Update the settings.


== Upgrade Notice ==

This is the first version.

== Disclaimer ==

This plugin does not have any relation with MPESA. The plugin's purpose is to help out in linking the WordPress website with the MPESA payment method to facilitate donations. In the plugin description there are links to other websites which are not under the control of Changisha Plugin. We have no control over the nature, content and availability of those sites. The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.

== Changelog ==

= 1.0.0 =
* First version of the plugin.

== Frequently Asked Questions ==

How does the user authenticate the donation?

The user receives a Sim Application Toolkit push to authenticate their donation on your website which is quite secure since the customer does this over their personal mobile phone.