## NO LONGER UPDATED / SUPPORTED
### Billmate no longer updates or offers support for this payment module.


#Billmate Payment Gateway for osCommerce
------

By Billmate AB - [https://billmate.se](https://billmate.se/ "billmate.se")

Documentation with instructions on how to setup the plugin can be found at:
[https://billmate.se/plugins/manual/Installation_Manual_Oscommerce_Billmate.pdf](https://billmate.se/plugins/manual/Installation_Manual_Oscommerce_Billmate.pdf)

## Description

Billmate Gateway is a plugin that extends osCommerce, allowing your customers to get their products first and pay by invoice to Billmate later (https://www.billmate.se/). This plugin utilizes Billmate Invoice, Billmate Card, Billmate Bank and Billmate Part Payment.

## Known Issues

* Some osCommerce installation uses Fileencoding ISO-8859-1 and some utilizes UTF-8, If there are some wierd characters, there is a solution to save files as the other format. If the sites fileencoding is ISO-8859-1 you need to make sure the files in /includes/languages/[language] has fileencoding ISO-8859-1
* The folders in /includes/languages have to match your installed language folder name. For example if you have named your folder when added the language to svenska you have to rename /includes/languages/swedish to svenska.
* You will need to set a order status for bank and card payment methods.

## Changelog

### 2.0.2 (2015-11-03)

* Fix - Paymentplan table.
* Translation - Payment method title for invoice.

### 2.0.1 (2015-10-13)

* Fix - Utf8 encoding issues on order confirmation.
* Fix - Products ordered on order mail for card and bankpayments. 

### 2.0 (2015-06-22)
Upgrading to the new API
36 commits and 30 issues closed

* Fix - jQuery issues in some situations.
* Fix - Address validation is managed by Ajax, but fallback to the default behaviour
* Enchancement - Invoice fee in Title
* Enchancement - Localized Payment logos
* Enchancement - Improved curreny handling
* Translation - Improved Translations
* Cleaned up the plugin




