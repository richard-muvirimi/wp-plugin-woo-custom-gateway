# Custom Payment Gateways for WooCommerce

- **_ Contributors: _** @tygalive
- **_ Tags: _** woocommerce gateway, gateway, payment, gateways, payment gateways, payment gateway, woocommerce payment gateway, woocommerce payment gateways, custom gateway
- **_ Donate link: _** [Buy Me A Coffee](https://buymeacoffee.com/fpjyrXk)
- **_ Requires at least: _** 4.0.0
- **_ Tested up to: _** 6.9
- **_ Requires PHP: _** 7.3
- **_ WC tested up to: _** 10.4
- **_ Stable tag: _** 1.6.5
- **_ License: _** GPLv2 or later
- **_ License URI: _** [GPL-2.0](http://www.gnu.org/licenses/gpl-2.0.html)

Add multiple custom payment gateways to WooCommerce e-commerce plugin.

## Description

This plugin allows you to create an infinite number of custom payment gateways for WooCommerce. After a payment gateway is created, you can edit its instructions and email sent to the customer directly in the WooCommerce payment settings menu.

Features:

* Create unlimited custom payment gateways
* Edit payment gateway instructions and customer emails in WooCommerce settings
* Easy customization of gateway options for customers
* Add multiple URLs to ping after an order is created
* Simple creation process for new payment gateways

Note: You will need to manually update the order status as the order progresses from On Hold/Processing, as this gateway has no way of tracking the order.

This plugin also includes non-intrusive ads (shown only in the backend) that help support the ongoing development and maintenance of the plugin. Please see our privacy policy for information on data collection.

## Installation

### Automatic installation

The easiest way to install Custom Payment Gateways for WooCommerce is through the automatic installation option. To do this, simply log in to your WordPress dashboard, navigate to the Plugins menu, and click on "Add New."

In the search field, type "Custom Payment Gateways for WooCommerce," and click on "Search Plugins." Once you find our plugin, you can view details such as the rating, description, and the current version. To install the plugin, click on "Install Now," and let WordPress handle the file transfer for you.

### Manual installation

1. Download the plugin zip file from the source (such as the WordPress plugin repository).
2. Log in to your WordPress dashboard, navigate to the Plugins menu, and click “Add New.”
3. Click the “Upload Plugin” button at the top of the page.
4. Choose the plugin zip file you downloaded and click “Install Now.”
5. Once the installation is complete, click the “Activate Plugin” button.
6. You can now access the plugin through "Custom Payment Gateways" option in the admin menu.

## Frequently Asked Questions

#### What is this all about?

- This plugin allows you to create custom payment gateways for WooCommerce, including options for adding a logo and customizing email instructions to customers after payment. This makes it easy to add local payment solutions in your area, and you can easily update the order status of the order as the payment process progresses.

#### So where do I start?

- After you install and activate the plugin, a new menu item "Custom Payment Gateways" will appear in the WooCommerce menu in the WordPress admin sidebar. Click on this menu item to create a payment gateway, and then further customize the payment gateway in the WooCommerce payment settings.

#### Is there a limit on the number of gateways I can create?

- Currently, there is no limit. You can create as many custom payment gateways as you need.

## Screenshots

![Screenshot](https://ps.w.org/woo-custom-gateway/assets/screenshot-1.png?rev=2131125)
Adding and Editing Screen. A good name, logo and desciption will help the admin to identify this gateway when customising in WooCommerce payment settings.

![Screenshot](https://ps.w.org/woo-custom-gateway/assets/screenshot-2.png?rev=2131125)
A list of all your Custom Payment Gateways. Here you can delete, edit or directly goto payment gateway settings in WooCommerce.

![Screenshot](https://ps.w.org/woo-custom-gateway/assets/screenshot-3.png?rev=2131125)
Custom payment gateway settings in WooCommerce. These settings will to shown to the customer at the appropriate times.

![Screenshot](https://ps.w.org/woo-custom-gateway/assets/screenshot-4.png?rev=2131125)
Custom payment gateway displayed to the customer highlighted inside the red rectangle.

## Changelog

**_ 1.6.4 _**
- Add WooCommerce Blocks checkout support
- Optimize package dependencies and remove unused imports
- Improve TypeScript configuration and type definitions
- Replace unreliable current() function with array_key_first()
- Add automated version tagging in deployment workflow
- Update to latest PHP polyfills (7.4-8.5)

**_ 1.6.3 _**

- Rename plugin to comply with WooCommerce trademark guidelines.
- Add WooCommerce plugin dependency.

**_ 1.6.1 - 1.6.2 _**

- Add support for High-Performance Order Storage.
- Minor Bug Fixes

**_ 1.6.0 _**

- Send instructions email for specific order statuses.

**_ 1.5.0 - 1.5.10 _**

- Set minimum supported version to 7.3.
- Allow opting in to usage analytics collection.

**_ 1.4.0 - 1.4.5 _**

- Allow use of html for gateway instructions and description.

**_ 1.3.0 - 1.3.1 _**

- Separate Email instructions from Thank you page instructions in gateway settings.
- Add ability to ping a url after order is completed.

**_ 1.2.3 _**

- Add gateway id filter

**_ 1.2.0 - 1.2.2 _**

- Add payment proof field

**_ 1.1.2 - 1.1.3 _**

- Minor Bug Fixes

**_ 1.1.0 - 1.1.1 _**

- Allow setting any supported order status after order is created

**_ 1.0.7 _**

- Support WordPress 5.5

**_ 1.0.6 _**

- Fix unintended payment gateways limit (thanks to @robertbrow)

**_ 1.0.1 - 1.0.5 _**

- Minor Bug Fixes

**_ 1.0.0 _**

- Initial release.

## Upgrade Notice

With each upgrade we try to resolve all issues and add new features.
