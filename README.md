##OpenCart InvoiceFox integration

This is a first version of OpenCart plugin that automatically adds clients and creates invoices every time your OpenCart orders are marked "Complete" (for example).

###Status

The first alpha version.

###Install & setup

* Get VQMOD installed to your OpenCart
* Copy files with full paths to OpenCart root folder
* Go to *ADMINISTRATION > Extensions > Modules* and press Install at *InvoiceFox Mod*.
* Edit the $CONF in the ./admin/model/invoicefox/hooks.php. You get your API key in InvoiceFox/Čebelca.biz under *Access/Dostop*.

* To create invoice go to *Admin > Sales > Orders > View (at a specified order) > History" and set order status to "Complete". 
  The invoice will be created and comment about this will be added to history.

###Config notes

* To get the Warehouse ID (in case you create Inventory sales documents out of orders) go to InvoiceFox/Cebelca > Inventory > Warehouses and click on Inventory/Zaloga at selected warehouse. Look at and URL where ?wh=X (where X is warehouse id)

###Where does this work

http://www.invoicefox.com , http://www.cebelca.biz , www.invoicefox.co.nz , www.invoicefox.com.au , www.abelie.biz

###Feature requests

Email me at janko.itm@gmail.com

###License

GNU GPL v2

