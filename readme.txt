=== Woo Custom Gateway ===
Contributors: tygalive
Tags: woocommerce, custom, woocommerce gateway, gateway, payment, gateways, payment gateways, payment gateway, woocommerce payment gateway, woocommerce payment gateways, woocommerce, woo custom gateway, woo gateway
Donate link: https://www.buymeacoffee.com/fpjyrXk
Requires at least: 4.0.0
Tested up to: 6.0
Requires PHP: 5.6
WC tested up to: 6.5.1
Stable tag: 1.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The plugin helps you create an infinite number of custom payment gateways.

== Description ==
The plugin helps you create an infinite number of custom payment gateways. After a payment gateway is created, its instructions and email sent to customer can be edited in the WooCommerce payment settings menu.
The steps of creating a payment gateway entail:

1. Creating a payment gateway in WooCommerce → Payment Methods → Add New
2. Customising the gateway in WooCommerce payment settings. (These options are shown to the customer).
3. Add a few urls to ping after an order is created.
4. Creating another.

Note: You will have to update the order status as the order progresses from On Hold/Processing as this gateway has no way of tracking the order.

== Installation ==
= Automatic installation =

Automatic installation is the easiest option -- WordPress will handle the file transfer, and you won’t need to leave your web browser. To do an automatic install of Woo Custom Gateway, log in to your WordPress dashboard, navigate to the Plugins menu, and click “Add New.”
 
In the search field type “Woo Custom Gateway,” then click “Search Plugins.” Once you’ve found us,  you can view details about it such as the point release, rating, and description. Most importantly of course, you can install it by! Clicking “Install Now,” and WordPress will take it from there.

= Manual installation =

1. Upload \'woo-custom-gateway\' to the \'/wp-content/plugins/\' directory
2. Activate the plugin through the \'Plugins\' menu in WordPress
3. Access \'edit.php?post_type=woocg-post\' in order to add new custom payment gateways

== Frequently Asked Questions ==
1. What is this all about?
♦ This plugin addresses cases where you might want a payment gateway such as \"pay on deliver\" or \"cheque\" but this plugin goes a step further as it allows you to customize with a logo and email instructions after customer pays. This allows you to add local payment solutions in your area and you can easily update the order status of the order as the payment process progresses.

2. So where do I start?
♦ After you install and activate plugin a menu option \"Payment Gateways\" is added under \"WooCommerce Menu Options\" in Admin sidebar, you then have to create a payment gateway. After you are done you go to the gateways settings in WooCommerce payment settings and further customize the payment gateway.

3. Is there a limit on number of gateways I can create
♦ Currently there\'s no limit. You can create as many as you can.

== Screenshots ==
1. Adding and Editing Screen. A good name, logo and desciption will help the admin to identify this gateway when customising in WooCommerce payment settings.
2. A list of all your Woo Custom Gateways. Here you can delete, edit or directly goto payment gateway settings in WooCommerce.
3. Custom payment gateway settings in WooCommerce. These settings will to shown to the customer at the appropriate times.
4. Custom payment gateway displayed to the customer highlighted inside the red rectangle.

== Changelog ==
= 1.4.0 - 1.4.3 =
* Allow use of html for gateway instructions and description.

= 1.3.0 - 1.3.1 =
* Separate Email instructions from Thank you page instructions in gateway settings.
* Add ability to ping a url after order is completed.

= 1.2.3 =
* Add gateway id filter

= 1.2.0 - 1.2.2 =
* Add payment proof field

= 1.1.2 - 1.1.3 =
* Minor Bug Fixes

= 1.1.0 - 1.1.1 =
* Allow setting any supported order status after order is created

= 1.0.7 =
* Support WordPress 5.5

= 1.0.6 =
* Fix unintended payment gateways limit (thanks to @robertbrow)

= 1.0.1 - 1.0.5 =
* Minor Bug Fixes

= 1.0.0 =
* Initial release.

== Upgrade Notice ==
With each upgrade we try to resolve all issues and add new features.