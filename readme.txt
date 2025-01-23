=== Min Max Control - Min Max Quantity & Step Control for WooCommerce===
Contributors: codersaiful,codeastrology,mdibrahimk48,unikforce,fazlebari,rafiul17
Donate link: https://donate.stripe.com/6oE9E33TD92Xav66or
Tags: woocommerce limit quantities, WC Min Max Quantities, min and max to purchase, limit quantity, customize woocommerce quantity
Requires at least: 4.0.0
Tested up to: 6.7.1
Requires PHP: 5.4
Stable tag: 6.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Min Max Control plugin offers to set product's minimum, maximum quantity and step of each product individually.

== Description ==
[Doc](https://codeastrology.com/min-max-quantity/documentation/) | [Demo](https://codeastrology.com/min-max-quantity/product/hoodie) | [Get Premium](https://codeastrology.com/downloads/min-max-step-control-wc/) | [Try Live](https://wpprincipal.xyz/?site=wcmmq)

*Min Max Control*(Min Max Quantity & Step Control for WooCommerce) plugin offers to set product’s 

🎉 Minimum Quantity<br>
🎉 Maximum Quantity<br>
🎉 Step Quantity<br>
🎉 WPML supported<br>
🎉 Cart Page Quantity *(Premium)*<br>
🎉 Category wise cart page Condition *(Premium)*<br>
🎉 Category wise cart page Condition *(Premium)*<br>
🎉 Stock Manage - Bulk Edit
🎉 Min Max Step Manage - Bulk Edit
🎉 Default Quantity [To enable: `add_filter('wcmmq_default_qty_option', '__return_true');` ]<br>

You can set values for all products globally. The same values will apply to all products.
You can set values by category. In this case, min, max, and step values will apply only to the chosen categories.<br>
Also, you can set values for each product individually. Product label values will override global and category values.

*Go to Dashboard -> Min Max Control* menu

**Useful Links**

* [Offical Site](https://codeastrology.com/min-max-quantity/)
* [Demo](https://codeastrology.com/min-max-quantity/shop)
* [Documentation](https://codeastrology.com/min-max-quantity/documentation/)
* [Purchase Premium Version](https://codeastrology.com/min-max-quantity/pricing/)  

**Useful Hook**
`wcmmq_custom_validation_msg`: To modify custom validation for your browser, use `add_filter('wcmmq_custom_validation_msg', '__return_true');`

**Example Hook**
`add_filter('wcmmq_single_product_min_max_condition','wcmmq_custom_handle_args', 10, 3);
function wcmmq_custom_handle_args( $args, $product, $Min_Max_Controller )
{
    //You can control based on $product or $Min_Max_Controller or existing $args
    return $args;
}`

**Example for Custom Validation Message**
`add_filter( 'wcmmq_custom_validation_msg', '__return_true' );`

🔅 [Set Global Condition on Whole Shop](https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/)
🔅 [Global Condition On Categories](https://codeastrology.com/min-max-quantity/global-condition-on-categories/)
🔅 [Set Conditions To A Specific Category (Premium) ](https://codeastrology.com/min-max-quantity/set-conditions-to-a-specific-category/)
🔅 [Global Condition On Tag (Premium) ](https://codeastrology.com/min-max-quantity/global-condition-on-tag/)
🔅 [Set Values To The Product Individually](https://codeastrology.com/min-max-quantity/set-values-to-the-product-individually/)
🔅 [Multiply Price By Quantity (Premium) ](https://codeastrology.com/min-max-quantity/multiply-price-by-quantity/)
🔅 [Decimal Quantity (Premium) ](https://codeastrology.com/min-max-quantity/decimal-quantity/)
🔅 [Set values on each variation (Premium) ](https://codeastrology.com/min-max-quantity/set-values-on-each-variation/)
🔅 [Set Conditions On Cart Page (Premium) ](https://codeastrology.com/min-max-quantity/set-conditions-on-cart-page/)
🔅 [Exclude/Include Products On Cart Page (Premium) ](https://codeastrology.com/min-max-quantity/exclude-include-products-on-cart-page/)


**🏆 World #1 WordPress Minimum Maximum Quantity & Step Control Plugin for WooCommerce. Most active and installed plugins than others.**

**Min Max Quantity & Step Control Plugin Has Received 5 (⭐⭐⭐⭐⭐ ) Reviews. Let's See What Our Users Said:**
🎉 “Easy to use and works perfect.”<br>
🎉 “Working perfect, fantasstic support.”<br>
🎉 “Awesome plugin and even better support”<br>
🎉 “Super quick and helpful. Thank you!”<br>
🎉 “Best Min Max Quantity & Step Control plugin for WooCommerce”<br>

**🥇🥇🥇Success Stories of People We Are Proud to Know :**
👨 "After submitting a ticket late on a Sunday afternoon. The last thing I expected was an almost immediate response from a patient and professional Fazle Bari. He assisted me with my issue. and resolved it in almost no time at all.Min Max Step is a handy plugging & the support is great.thanks again Fazel."

**👣 KEY OF MAIN FEATURES**

👉 Set minimum and maximum quantity and Steps for all products globally.<br>
👉 Set minimum and maximum quantity and Steps conditions by categories,tags or taxonomy.<br>
👉 Set product quantity step (per item or globally quantity step).<br>
👉 Set product default quantity for all store products.<br>
👉 Set a different minimum/maximum quantity for each variation<br>
👉 Set decimal quantities in your store.<br>
👉 Set  minimum/maximum amount restriction on the cart total.<br>
👉 Set different messages of the minimum/maximum quantity restriction.<br>

**🚩 FEATURES OF GLOBAL CONDITIONS:**
👉 Set global values for the whole shop. This condition will apply to all products. [Click here](https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/)<br>
👉 Set conditions category-wise. [Click here](https://codeastrology.com/min-max-quantity/global-condition-on-categories/)<br>

**🚩 FEATURES OF SET CONDITIONS ON SINGLE PRODUCT**
🔅 You can set values for each product individually.<br>
👉 [Demo](https://codeastrology.com/min-max-quantity/product/hoodie/)<br>
👉 [Documentations](https://codeastrology.com/min-max-quantity/set-values-on-each-variation/)

🔅 You can increase or decrease a product's quantity on the single product page, and the price will increase or decrease in accordance with the quantity.<br>
👉 [Demo](https://codeastrology.com/min-max-quantity/product/hoodie/)
👉 [Documentations](https://codeastrology.com/min-max-quantity/multiply-price-by-quantity/)

🔅 You can set a decimal quantity For your products with the help of our plugin.<br>
👉 [Demo](https://codeastrology.com/min-max-quantity/product/sunglasses/)
👉 [Documentations](https://codeastrology.com/min-max-quantity/decimal-quantity/)

🔅 You can set values for every variation of a single product<br>
👉 [Demo](https://codeastrology.com/min-max-quantity/product/hoodie/)
👉 [Documentations](https://codeastrology.com/min-max-quantity/set-values-on-each-variation/)


**🚩 FEATURES OF VARIABLE PRODUCT QUANTITY**

--> Set minimum variable product order quantities (cart total minimum quantity or per item minimum quantity).<br>
--> Set maximum variable product order quantities (cart total maximum quantity or per item maximum quantity).<br>
[👉 CLICK HERE TO LEARN MORE 👈](https://codeastrology.com/min-max-quantity/set-values-on-each-variation/)

**🚩 FEATURES OF TAXONOMY:**
--> You can set conditions for a specific category. [Click here](https://codeastrology.com/min-max-quantity/set-conditions-to-a-specific-category/)<br>
--> You can set conditions for a specific Tag. [Click here](https://codeastrology.com/min-max-quantity/global-condition-on-tag/)<br>
 
**🚩 FEATURES OF CONDITIONS SET ON CART PAGE (PRO)**
--> Set a minimum and maximum  order quantity required to checkout. [Click here](https://codeastrology.com/min-max-quantity/set-conditions-on-cart-page/)<br>
--> Set a minimum and maximum price for purchasing products. [Click here](https://codeastrology.com/min-max-quantity/set-conditions-on-cart-page/)(<br>
--> Exclude/Include Products On Cart Page. [Click here](https://codeastrology.com/min-max-quantity/exclude-include-products-on-cart-page/)<br>

**🚩 MORE OPTIONS AND FLEXIBILITY IN CUSTOMIZING**

🔅 You can customize the MESSAGE seen by your customer.<br>
👉 Minimum Quantity Validation Message.<br>	
👉 Maximum Quantity Validation Message.<br>
👉 Maximum Quantity Validation Message.<br>	
👉 Minimum Quantity message for shop page.<br>	
👉 Step validation error message.<br>

**💥PREMIUM VERSION  DO MORE💥**
👉 Set conditions category wise.
👉 Set a minimum and maximum order quantity and price conditions on the cart page.<br>
👉You can set or remove Cart Page conditions by  Exclude/Include Products options.<br>
👉 When you change the quantity of a product on the single product page, you'll notice that the price doesn't change as you change the quantity. You can do it by the pro version with our Multiply Price By Quantity option.<br>
👉 You can customize the CART PAGE NOTICES seen by your customer.<br>

    --> Cart Minimum Price Validation Message.<br>	
    --> Cart Maximum Price Validation Message.<br>
    --> Cart Minimum Quantity Validation Message.<br>	
    --> Cart Maximum Quantity Validation Message.<br>

👉 You can add QUANTITY PREFIX/SUFFIX . Show it after and before the quantity. (Example: Per Order $10 KG). [Click here](https://www.youtube.com/watch?v=0wSO8pUupMc&list=PLV1ooYxywkSV84cfl6PKw81Fspy72_KYm&index=7)<br>

**[👉 Purchase Premium Version 👈](https://codeastrology.com/min-max-quantity/pricing/)**

**🚩FAST, SECURE AND EASY 🚩**
*With a Min Max Quantity & Step Control, you can be confident that your business processes will go quickly and easily. User-friendly and adaptable to all popular themes.

**Translation**<br>

* French (Français) - fr_FR
* Bengali (বাংলা) - bn_BD
* Russian (Русский) - ru_RU
* Ukrainian(Українська) - uk
* Spanish(Español) - es_ES

**🔅OUR OTHER FREE WOOCOMMERCE PLUGINS🔅**

* [Product Table for WooCommerce (wooproducttable)](https://wordpress.org/plugins/woo-product-table/)
* [UltraAddons Elementor Lite (Header & Footer Builder, Menu Builder, Cart Icon, Shortcode)](https://wordpress.org/plugins/ultraaddons-elementor-lite/)
* [Quantity Plus Minus Button for WooCommerce](https://wordpress.org/plugins/wc-quantity-plus-minus-button/)
* [Add to Cart Text Changer and Customize Button, Add Custom Icon](https://wordpress.org/plugins/woo-add-to-cart-text-change/)

**👷 HONORABLE CONTRIBUTOR - [GitHub](https://github.com/codersaiful/woo-min-max-quantity-step-control-single/graphs/contributors) 👷**<br>

* [codersaiful](https://github.com/codersaiful) (161 commits 22,681 ++ 5,794 -- And Pro: 50+ commits )
* [unikforceit](https://github.com/unikforceit) (14 commits 315 ++ And Pro: 10+ commits )
* [fazlebarisn](https://github.com/fazlebarisn) (9 commits 71 ++ And Pro: 10+ commits )
* [mdibrahimk48](https://github.com/mdibrahimk48) (8 commits 20 ++ And Pro: 10+ commits )
* [rafiul](https://github.com/rafiul) (7 commits 1,718 ++  )
* [zbandhan](https://github.com/zbandhan) (1 commits  )
* 👉 [You can join here](https://github.com/codersaiful/woo-min-max-quantity-step-control-single/fork)

**🥇 CONTRIBUTE 🥇**<br>
You are welcome to contribute  to this project. Join with us [Fork Github repository](https://github.com/codersaiful/woo-min-max-quantity-step-control-single/fork). If you contribute 1 commit, We will add your name to our plugin's Contributor table/list of WordPress Plugin too.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/WC_Min_Max_Quantity_Single` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Woo Table->Setting screen to configure the plugin's default setting.


== Frequently Asked Questions ==

= Does Min/Max Quantities work with variable products? =
Yes, it work with  variable, to learn click on this [tutorial link.](https://codeastrology.com/min-max-quantity/set-values-on-each-variation/)

= Is it working on WooCommerce Cart/Checkout block? =
Yes, it is. 

= Is it Compabile with WPML? =
Yes, it is. Min max step need to set only to main product.

= Can I enable ajax add to cart on single product? =
Yes, use `add_filter('wcmmq_ajax_cart_single_page', '__return_true');` for enable ajax add to cart on single page.

= Can I disable ajax add to cart on archive/shop/taxonomy page? =
Yes, Disable ajax add to cart from WooCommerce setting. uncheck [WooCommerce -> Products(Tab) -> Enable AJAX add to cart buttons on archives]

= Can I set a minimum, maximum, default, or step for all products at once? =
Yes, to learn more, please [click](https://codeastrology.com/min-max-quantity/set-global-condition-on-whole-shop/) on this link.

= Does the cart have a separate minimum, maximum, or step limit rule? =
Yes, you need to set a separate cart rule.

= Can I add a custom message for cart rules violations? =
Yes, you can add a custom message on the cart page for violations.

= Can I set the Min/Max quantity limit from the category/Tags/Brand/Color ? =
Yes, you can.

= Can I enable decimal quantities in my shop? =
Yes, you can. This option is available on the Pro version.

= Can I set a minimum, maximum, default, or step when specifying products or categories? =
Yes, you can. 

= Is there any shortcode? =
No need shortcode.

= Does it support with any Side cart plugin or any theme? =
Yes, It's support. Actually we developed our plugin using WooCommerce filter hook, so it will support with any theme and plugin.

= Where will I find the setting menu for Min Max and step? =
Very easy. Just add a new product or edit an existing product. You will see a tab “Min Max & Step '' in the product data panel. You can set your product quantity and steps easily.

= Does it Support the 3rd party plugin? =
Yes.

= Where do I get to buy the Min Max Quantity & Step Control plugin pro version? =
To buy the Pro version, click on this [link](https://codeastrology.com/min-max-quantity/pricing/).

= Is it suitable for any theme ? =
Yes. The Max Quantity & Step Control plugin will adapt to your theme design.

= Where can I find documentation and user guides? =
You will get all the useful [documents](https://codeastrology.com/min-max-quantity/documentation/) on our site.

= Where can I get the video tutorials? =
You can watch our video tutorial on CodeAstrology's  [YouTube](https://www.youtube.com/watch?v=keKRQlxVxmU&list=PLV1ooYxywkSV84cfl6PKw81Fspy72_KYm) channels.

= How can I get support? =
We’ll provide support via [support ticket](https://codeastrology.com/my-support/), [live chat](https://prnt.sc/jDcsSL1Q57s8), Skype, or email (support@codeastrology.com)


== Screenshots ==


== Changelog ==

= 6.6 =
* Fixed: Pro demo link fixed. 
* Bug fixed

= 6.5 =
* Compabile with Latest WooCommerce version
* Compabile with Latest WordPress version
* Installation output issue fixed.
* Fixed: Code Optimized
* Bug Fixed.

= 6.4 =
* Fully Compabile with wpml.
* Fixed: Code Optimized
* Bug Fixed.

= 6.3 =
* filter hook `wcmmq_zero_min_issue` optiomized 
* Fixed: Code Optimized
* Bug Fixed.

= 6.2 =
* filter hook `wcmmq_zero_min_issue` added 
* Min Min Controller added for Downloadable product.

= 6.2 =
* Added: Broser input's step validation message is customize from setting.
* Fixed: Variable Product's input, min, max Optimized.
* Fixed: Double error message issue fixed.
* Load Speed Optimized.
* Compatibility issue fix on [Product Stock sync with Google Sheet for WooCommerce](https://codeastrology.com/downloads/product-sync-master-sheet-premium/)
* Fixed: min,max and step on WooCommerce Minicart Block issue has been fixed.
* Fixed: Min,Max,Step issue on WooCommerce Cart Block has been fixed 
* Fixed: Min,Max,Step issue on WooCommerce Checkout Block has been fixed 
* Bug Fixed.

= 6.0 & 6.1 =
* Fixed: min,max and step on WooCommerce Minicart Block issue has been fixed.
* Fixed: Min,Max,Step issue on WooCommerce Cart Block has been fixed 
* Fixed: Min,Max,Step issue on WooCommerce Checkout Block has been fixed 
* Bug Fixed.

= 5.8 & 5.9 =
* Compabile: Google Sheet Syncronize Feature Compatibility Added.
* Cart page issue specially for Varition and simple product confliction on cart item has been solved.
* Admin area organized and Optimized.
* Removed: BlackFriday Offer notice has been removed.

= 5.7 =
* Modified: filter hook `wcmmq_single_product_min_max_condition` modified. added third param the $Min_Max_Controller object. Now user handle more effectively.
* Ajax add to cart from shop/archive/taxonomy page has been fixed.
* Bug Fixed.

= 5.6 =
* Spelling fix. 
* Bug Fixed.

= 5.5 =
* Compatibility: Compabile with HPOS.
* Bug Fixed.

= 5.4 =
* Fixed: Cart Page Min value Zero issue fixed when set min value 0.
* Spelling fixed.

= 5.3 =
* Fixed: Min value Zero issue fixed for shop page.
* Fixed: Confliction with variation for Product table has been fixed, when no enable third party plugin support.
* Code Optimized.

= 5.2 =
* Fixed: Confliction with variation for Product table has been fixed.
* Bug Fixed.

= 5.1 =
* Fixed: $product object checking issue fixed.
* Bug Fixed.

= 5.0 =
* Variation min max Confliction with [Woo Product Table](https://wordpress.org/plugins/woo-product-table/) input box issue has beeen solved for variation product.
* Bug Fixed.

= 4.9 =
* Backorder issue solved on variation
* Backend Design update.
* Bug Fixed.

= 4.8 =
* Confliction issue with [Woo Product Table](https://wordpress.org/plugins/woo-product-table/) has been solved.
* Backend Design update.
* Bug Fixed.

= 4.7 =
* Fixed: Add to cart min value issue on Shop/Archive page has been solved
* Code Optimized and spelling fix.
* Bug fixed 

= 4.5 =
* Added: Live Chatbox On/Off button added. Easily able to on or off.
* WPML Language wise message issue fixed.
* Variable product's min max and step issue in free version has been fixed.
* use `add_filter('wcmmq_ajax_cart_single_page', '__return_true');` for enable ajax add to cart on single page.
* Update decimal separator option
* Code Optimized

= 4.4 =
* Quanity issues on side cart has been solved.
* Group product ajax cart adding issue has been solved
* Added new filter hook `wcmmq_default_qty_option` to control default value. By default it's hidden now. If you want to show,
following code to your functions.php file of your child theme.
`add_filter('wcmmq_default_qty_option', '__return_true');`<br>
Even you can add using codesnippet or using any custom plugin.

= 4.3 =
* Spelling fixed. 
* Code Optimized

= 4.2 =
* Min max step issue on Order Edit page has been fixed.
* Order Page issue fixex for pro version. 
* Code Optimized

= 4.1 =
* Fixed: Default quantity issue fixed
* Fixed: Min quantity issue fixed.

= 4.0 =
* Fixed: Taxonomy min max issue has been solved. 

= 3.8 and 3.9 =
* step issue fixed
* Fixed: non-numeric encounter issue has been fixed 
* Fixed: Variable product qty issue fix
* Fixed: min max and step getting location confusion has been fixed.
* Code Optimized
* Bug Fixed

= 3.7 =
* Code Optimized
* Bug Fixed

= 3.6 =
* Quanity Box added for Archive or Taxonomy page. Like: Category, Tag page.
* Code Optimized 
* Bug Fixed

= 3.5 =
* 4 new translated Language file added. 
* Added: Ukrainian(Українська) translated file added for frontend. helped by *Lucas*.
* Added: Spanish(Español) translated file added for frontend.
* Added: Russian (Русский) translated file added for frontend. Helped us [Vasiliy Kotov](https://www.linkedin.com/in/vasiliy-kotov-10542b47/) to translate Russian Language.
* Added: French (Français) translated file added for frontend. Helped us [ozapp.app](https://ozapp.app/) to translate French Language.
* Added: New Option added to change Quantity Separator for WooCommerce Quantity Box.
* Deciaml Separator issue fixed for comma separator.
* Javascript script Optimized.
* Compatibility Checked and tested @WC 7.2.3 and @WP 6.1.1

= 3.4 =
* Fixed: Issue - Call to a member function is_sold_individually() raise a 505 error in ajax ajax. Fixed by [@dibix](https://wordpress.org/support/topic/call-to-a-member-function-is_sold_individually-raise-a-505-error-in-ajax-ajax/)
* Compatibility Checked @WC 7.1

= 3.3 =
* Loop quantity box min max issue fix.
* But fixed 
* Minor change.

= 3.2 =
* Added: New filter hook `wcmmq_message_replaced_shortcode_args` added for Error Message replace shortcode arguments.
* Added: [step_quantity] New Shortcode for Error/Success message for all Message
* Added: [variation_name] New Shortcode for Error/Success message for all Message
* Added:  [should_min],[should_next],[product_name],[variation_name],[quantity],[min_quantity],[step_quantity] for Step validation error message
* Fixed: msg_max_limit error message default message fix.
* Bug Fix

= 3.1 =
* Quantity steps changing to big value - problem has been fixed. 
* Bug Fix
* Minor issues check

= 3.0 =
* Bug Fix
* * Minor issues check

= 2.9 =
* Check in latest WordPress and WooCommerce version
* Bug Fix
* Minor issues check

= 2.8 =
* Check in latest WordPress and WooCommerce version
* Bug Fix

= 2.5 =
 * Fix: Ajax issue fix
 * Integrate with thirdparty plugin 
 * Bug Fix

= 2.4 =
* Fixed: min quantity on mini cart and other plugins
* Fixed: step quantity on mini cart and other plugins
* Bug Fixed

= 2.3 =
* Fixed: min quantity should message error fixed.
* Bug Fixed

= 2.0 =
* Added: Modern user interface
* Improved: Code optiomized
* Added: Min max step control added for single and global
* Added: Category / archive based min max step control 
* Fixed: Bug fixed

= 1.9 =
 * Version update
 * Cart page fatal error solved

= 1.8 =
 * Cart Validation bug fix

= 1.6 =
* Cart Validation bug fix

= 1.5 =
* Bug Fix

= 1.4 =
* 0 quantity supported
* Quanity Checking bug fix

= 1.3 =
* Cart's page Quantity issue fixed

= 1.1 =
* Bug Fix

= 1.0 =
* Just Start First version.