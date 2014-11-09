##OpenCart InvoiceFox integration

This is a first version of OpenCart plugin that automatically adds clients and creates invoices every time your OpenCart orders are marked "Complete" (for example).

###Status

The first alpha version.

###Install & setup

* Get VQMOD installed to your OpenCart
* Copy files with full paths to OpenCart root folder
* Go to *ADMINISTRATION > Extensions > Modules* and press Install at *InvoiceFox Mod*.
* Edit the $CONF in the ./admin/model/invoicefox/hooks.php. You get your API key in InvoiceFox/Čebelca.biz under *Access/Dostop*.

* To create invoice go to "Admin > Sales > Orders > View (at a specified order) > History" and set order status to "Complete". 
  The invoice will be created and comment about this will be added to history.

###General config

* Get tha API key from the program and enter it (API_KEY)
* Set the right domain (API_DOMAIN)
* Choose what documents to make (document_to_make)
* Choose what the default payment period should be for the customers that get added  (customer_general_payment_period)
* Decide if you want documents to have document numbers provided by opencart or the program (use_shop_document_numbers)
* Decide on which status do you want the document created (create_invfox_document_on_status). Go to "Admin > Sales > Orders > View (at a specified order) > History" to see the possible statuses.

###Creating the the inventory sales document

* To get the Warehouse ID (in case you create Inventory sales documents out of orders) go to InvoiceFox/Cebelca > Inventory > Warehouses and click on Inventory/Zaloga at selected warehouse. Look at and URL where ?wh=X (where X is warehouse id)

###Creating the proforma invoice

* Select the number of days the proforma is valid (proforma_days_valid)

###Where does this plugin work

http://www.invoicefox.com , http://www.cebelca.biz , www.invoicefox.co.nz , www.invoicefox.com.au , www.abelie.biz

###Feature requests

Email me at janko.itm@gmail.com

###License

GNU GPL v2

