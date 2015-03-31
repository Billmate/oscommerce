                                                                     
                                                                     
                                                                     
                                             
=== OSCOMMERCE BILLMATE GATEWAY ===

By Billmate AB - http://billmate.se/

Documentation with instructions on how to setup the plugin can be found at https://billmate.se/plugins/oscommerce/Instruktionsmanual_Oscommerce_Billmate_Plugin.pdf


== DESCRIPTION ==

Billmate Gateway is a plugin that extends OsCommerce, allowing your customers to get their products first and pay by invoice to Billmate later (http://www.billmate.com/). This plugin utilizes Billmate Invoice, Billmate Card and Billmate Part Payment (Standard Integration type).

When the order is passed to Billmate a credit record of the customer is made. If the check turns out all right, Billmate creates an invoice in their system. After you (as the merchant) completes the order in OsCommerce, you need to log in to Billmate to approve/send the invoice.

Billmate is a great payment alternative for merchants and customers in Sweden.


== IMPORTANT NOTE ==

This plugin does not currently support Campaigns or Mobile payments.

The plugin only works if the currency is set to Swedish Krona and the Base country is set to Sweden.

PCLASSES AND BILLMATE PART PAYMENT
To enable Billmate Part Payment you need to store your available billmatepclasses in the file billmatepclasses.json located in oscommerce-gateway-billmate/srv/. Make sure that read and write permissions for the directory "srv" is set to 777 in order to fetch the available PClasses from Billmate. To retrieve your PClasses from Billmate go to --> Modules --> Payment --> Billmate Part Payment and click on "Click here to update your pclasses".

If you want to, you can also manually upload your billmatepclasses.json file via ftp.


INVOICE FEE HANDLING
Since of version 1.13 the Invoice Fee for Billmate Invoice are added as a simple (hidden) product. This is to match order total in OsCommerce and your billmate part payment (in earlier versions the invoice fee only were added to Billmate).

To create a Invoice fee product: 
- Add a simple (hidden) product. Mark it as a taxable product.
- Go to the Billmate Gateway settings page and add the ID of the Invoice Fee product. The ID can be found by hovering the Invoice Fee product on the Products page in OsCommerce.



== INSTALLATION	 ==

1. Download and unzip the latest release zip file.