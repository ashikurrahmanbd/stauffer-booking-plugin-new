# Adding Coupon Code Feature to the Stauffer Booking Plugin

I need to add a **Coupon Code** feature to the Stauffer Booking Plugin.

The right side of the booking form currently displays the booking summary. Before the **Bruttogesamt — 150 CHF** amount, I want to add a coupon code input field.

The coupon section should contain:

* An input field with the placeholder **"Coupon Code"**
* A button labeled **"Apply Coupon"**
* The button should match the plugin's existing color scheme

There will be predefined coupon codes, and a coupon should only be applied if the entered code matches an existing and valid coupon.

When a valid coupon is applied, the discount should be calculated based on the current total amount, and the total price should be updated accordingly.

## Coupon Management

Instead of hardcoding the coupon codes, I want to add a new **Coupons** menu under the Stauffer Booking Plugin in the WordPress admin area.

The plugin currently has two menus:

* Booking Form — currently used for translations and other customization options
* Settings — currently not being used

A new **Coupons** menu should be added.

Under **Coupons**, there should be two submenu items:

* **All Coupons**
* **Add New Coupon**

### All Coupons

The **All Coupons** page should display all existing coupon codes.

For each coupon, I should be able to:

* View the coupon code
* Edit the coupon
* Delete the coupon
* See the discount type
* See the discount value
* See the validity/expiration date

### Add New Coupon

The **Add New Coupon** page should allow me to create a new coupon with the following options:

* Coupon Code
* Discount Type:

  * Percentage (%)
  * Fixed/Flat Discount
* Discount Amount
* Expiration Date

For example:

* `WELCOME10` → 10% discount
* `SAVE20` → 20 CHF fixed discount

The coupon should only be applied if it is currently valid.

## Coupon Validation Messages

If the entered coupon code does not exist, display:

**"Coupon code is not correct."**

If the coupon exists but has expired, display:

**"Coupon code has been expired."**

If the coupon is valid, apply the discount and update the booking's total amount immediately.

## Email Template

After the coupon feature is implemented, the applied coupon information should also be included in the booking confirmation email.

For example:

**Applied Coupon:** WELCOME10

The email should also display the final price **after the coupon discount has been applied**.

The final amount shown in the email must match the final amount displayed in the booking form.

## Translation / Customization

The plugin currently uses English as the default language, and the **Booking Form → Customization** section provides options for translating the plugin's frontend text.

The new coupon feature should follow the same translation system.

All new coupon-related text should be written in English in the code by default, but **every user-facing text related to the coupon feature must be translatable through the existing Booking Form → Customization translation settings**.

This includes:

* Coupon Code
* Apply Coupon
* Coupon code is not correct.
* Coupon code has been expired.
* Applied Coupon
* Any other frontend text introduced by the coupon feature

Please integrate the coupon functionality into the plugin's existing architecture and translation system rather than creating a separate translation mechanism.

The existing booking functionality, pricing calculations, email templates, and other features should continue to work exactly as they currently do. Dont break any previous code or feature.