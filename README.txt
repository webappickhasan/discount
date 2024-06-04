=== Disco – Dynamic Discount Plugin for WooCommerce ===
Contributors: webappick,wahid0003
Donate link: https://webappick.com/
Tags: woocommerce, discount, dynamic discount, automatic discount, discount generator
Requires at least: 6.0
Tested up to: 6.5.3
Stable tag: 1.0.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 7.4

Create logical, dynamic, and automated discounts for your WooCommerce Store based on product, cart, and cart item information.

== Description ==

Enhance your WooCommerce store with the power of dynamic, automated discounts and conditional offers. Disco, a revolutionary WooCommerce extension, is designed to empower store owners like you to effortlessly create a variety of discounts based on Product Attributes, Cart Information, and Item Details in the cart.

=== ☞ Key Features: ===

* **Dynamic Discount Generation:** Seamlessly create discounts that adapt to your customer's shopping behavior. With Disco, offer percentage or fixed amount discounts on specific products or categories based on the product attributes.

* **IFTTT (If This, Then That) Conditional Logic:** Our IFTTT feature takes discounts to the next level. Set up conditions based on Product Attributes, Cart Information, and Cart Item details. For instance, offer a discount if a customer purchases a certain quantity of a product or reaches a specific cart total. The possibilities are limitless and all within your control.

* **User-Friendly Interface:** We understand the importance of a straightforward, no-fuss experience. Disco has a user-friendly interface that makes setting up and managing your discount rules easy.

* **Versatile Cart-Based Discounts:** Encourage larger purchases with cart-based discounts. Apply discounts when the cart meets specific criteria, such as total value, product combinations, or quantities.

* **Product Attribute-Based Discounts:** Target specific product attributes for discounts. This method is ideal for promoting product variations or clearing specific stock.

* **Advanced Reporting:** Keep track of the discounts applied. Our advanced reporting system lets you monitor the performance of your discount campaigns, helping you make informed decisions.

=== ☞ Why Choose Disco? ===

* **Increase Sales and Customer Loyalty:** By offering dynamic discounts and conditional offers, you encourage customers to buy more and return to your store.

* **Fully Customizable:** Tailor discounts to fit your store's unique needs and marketing strategies.

* **Easy Integration with WooCommerce:** Disco integrates seamlessly with your existing WooCommerce setup, ensuring a smooth operation without hiccups.

* **Responsive Support:** Our dedicated support team can assist you with any queries or issues.

=== ☞ Ideal For: ===

* Online Retailers are looking to boost sales and customer engagement.
* Store owners want to clear stock efficiently through targeted discounts.
* E-commerce Businesses aiming to offer personalized shopping experiences.
* Enhance your WooCommerce experience with Disco – the ultimate tool for dynamic discounts and conditional offers. Elevate your store's potential today!

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'disco'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `disco.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `disco.zip`
2. Extract the `disco` directory to your computer
3. Upload the `disco` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Upgrade Notice ==
= 1.0.2 =

== ☞  Build Process ==

This plugin bundles the JavaScript files using Webpack. The source files are located in the `assets/src` directory.

To build the files, you need to have Node.js and npm installed on your system. Once you have these, you can install the dependencies and run the build command:


``# Navigate to the plugin directory

``cd wp-content/plugins/disco

``# Run the build command

``npm run publish

= PHP Libraries Used =

This plugin uses several PHP libraries to provide its functionality. Below is a list of these libraries, along with links to their respective source code:

1. **Inpsyde Assets**: This library manages and integrates assets in WordPress. It provides a convenient way to register and enqueue scripts and styles. [Source Code](https://github.com/inpsyde/assets)

2. **Inpsyde WP Context**: This library provides a set of classes to help determine the current WordPress context. [Source Code](https://github.com/inpsyde/wp-context)

3. **Micropackage Requirements**: This library checks server requirements for plugins or themes. [Source Code](https://github.com/micropackage/requirements)

4. **Seravo WP Custom Bulk Actions**: This library allows you to create custom bulk actions in WordPress easily. [Source Code](https://github.com/Seravo/wp-custom-bulk-actions)



= JS Libraries Used =

The non-compiled version of JavaScript and/or CSS-related source code for each package can be found at the following links:

1. **uuid**: For the creation of RFC4122 UUIDs. [Source Code](https://www.npmjs.com/package/uuid)

2. **@headlessui/react**: A completely unstyled, fully accessible UI component library, designed to integrate beautifully with Tailwind CSS. [Source Code](https://www.npmjs.com/package/@headlessui/react)

3. **@heroicons/react**: A set of free MIT-licensed high-quality SVG icons for UI development. [Source Code](https://www.npmjs.com/package/@heroicons/react)

4. **@reduxjs/toolkit**: The official, opinionated, batteries-included toolset for efficient Redux development. [Source Code](https://www.npmjs.com/package/@reduxjs/toolkit)

5. **@wordpress/i18n**: Internationalization utilities for client-side localization. [Source Code](https://www.npmjs.com/package/@wordpress/i18n)

6. **classnames**: A simple JavaScript utility for conditionally joining classNames together. [Source Code](https://www.npmjs.com/package/classnames)

7. **moment**: A JavaScript date library for parsing, validating, manipulating, and formatting dates. [Source Code](https://www.npmjs.com/package/moment)

8. **react**: A JavaScript library for building user interfaces. [Source Code](https://www.npmjs.com/package/react)

9. **react-beautiful-dnd**: Beautiful and accessible drag and drop for lists with React.js. [Source Code](https://www.npmjs.com/package/react-beautiful-dnd)

10. **react-color**: A Collection of Color Pickers from Sketch, Photoshop, Chrome, Github, Twitter, Material Design & more. [Source Code](https://www.npmjs.com/package/react-color)

11. **react-dom**: Serves as the entry point to the DOM and server renderers for React. [Source Code](https://www.npmjs.com/package/react-dom)

12. **react-redux**: Official React bindings for Redux. [Source Code](https://www.npmjs.com/package/react-redux)

13. **react-router-dom**: DOM bindings for React Router. [Source Code](https://www.npmjs.com/package/react-router-dom)

14. **react-select**: A flexible and customizable Select Input control for ReactJS with multiselect, autocomplete and ajax support. [Source Code](https://www.npmjs.com/package/react-select)

15. **react-toastify**: Allows you to add notifications to your app with ease. [Source Code](https://www.npmjs.com/package/react-toastify)

16. **tinycolor2**: A tiny color manipulation library for JavaScript. [Source Code](https://www.npmjs.com/package/tinycolor2)


== Frequently Asked Questions ==

= What types of discounts I can create using DISCO? =

Using DISCO, you have the flexibility to craft discounts such as product-based discounts, cart-based discounts, shipping discounts, BOGO (Buy One Get One) promotions, bulk discounts, and a variety of others.

= Can I apply discounts to specific products or categories? =

Absolutely, with our product filtering feature, you can effortlessly apply discounts to specific products, categories, tags, attributes, and various other filters tailored to your needs.

= Does the discount apply to product variants? =

Yes, the discount applies to product variants. So, if you’ve got different flavors of a product, the discount works for all of them!

= Can I schedule discounts to run during specific times or dates? =

Yes, you have the flexibility to schedule discounts to run during specific times or dates, allowing you to plan and automate your promotional activities with precision.

= Can I offer bulk discounts for wholesale customers? =

Yes, in the Disco plugin, there is a dedicated feature for creating bulk discounts.

= Can I set up a BOGO (Buy One Get One) offer for my customers using DISCO? =

Absolutely! You can create enticing BOGO (Buy One Get One) offers for your customers using the DISCO plugin. in the Disco plugin, there is a dedicated feature for creating BOGO offers.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.3 (June 03 2024) =
* Added : Favicon added.
* Fixed : Discount price not effecting into cart.


= 1.0.2 (2024-05-29) =
* Sidebar removed

= 1.0.1 (2024-05-28) =
* Status message problem solve.

= 1.0.0 (2024-05-27) =
* Initial Release.

