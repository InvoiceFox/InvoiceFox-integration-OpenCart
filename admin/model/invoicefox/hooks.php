<?php 

//if(file_exists(__DIR__."/hooksExtensions.php")) { include_once 'hooksExtensions.php'; } 

/*
  Plugin Name: OpenCart+InvoiceFox
*/

// Status: Alpha

//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);
require_once(dirname(__FILE__).'/lib/invfoxapi.php');
require_once(dirname(__FILE__).'/lib/strpcapi.php');

function opencart_invfox__trace($x) { error_log(print_r($x,true)."-----"); } // todo -- move to class


class ModelInvoiceFoxHooks extends Model { 

  var $CONF;
  
  public function __construct($registry)
  {
    parent::__construct($registry);
    


    // THE MYCONFIGURATION TODO --- move to class



    $this->CONF = array(
			'API_KEY'=>"59zt*******************************akb8r", // you get it in InvoiceFox/Cebelca/Abelie/..., on page "access" after you activate the API
			'API_DOMAIN'=>"www.cebelca.biz", // options: "www.invoicefox.com" "www.invoicefox.co.uk" "www.invoicefox.com.au" "www.cebelca.biz" "www.abelie.biz" 
			'APP_NAME'=>"Cebelca.biz",
			'document_to_make'=>"invoice", // options: "invoice" "proforma" "inventory"
			'proforma_days_valid'=>10,
			'customer_general_payment_period'=>5,
			'add_post_content_in_item_descr'=>false,
			'partial_sum_label'=>'Skupaj', // Empty for no partial sum line
			'round_calculated_taxrate_to'=>1,
			'tax_rate_on_shipping'=>17.5,
			'use_shop_document_numbers'=>true,
			'create_invfox_document_on_status'=>'Complete'
			);
    

    // END OF THE MYCONFIGURATION

    

  }
  
  public function changeOrderStatusHook($status_id, $comment, $order_id) {
    $newComment = '';
    error_log("IN ORDER STATUS HOOK", E_USER_ERROR);

    $this->load->model('localisation/order_status');
    
    $order_status_info = $this->model_localisation_order_status->getOrderStatus($status_id);
    if ($order_status_info){ $data['order_status'] = $order_status_info['name']; } else { $data['order_status'] = ''; }
    
    opencart_invfox__trace($data['order_status']);

    if ($data['order_status'] == $this->CONF['create_invfox_document_on_status']) {
      
      $newComment = $this->makeInvoiceFromOrder($order_id);
      
      error_log("IN ORDER STATUS HOOK END", E_USER_ERROR);
    }

    return $comment.($comment?"\n\n":"").$newComment;
  }

  public function getLanguageDirectory() {

  }

  public function sample_of_getting_data() {

    $data = array();

    $this->load->model('sale/order');

    $data['order_info'] = $this->model_sale_order->getOrder($order_id);

    if ($data['order_info']) {

      $data['products'] = array();

      $products = $this->model_sale_order->getOrderProducts($order_id);
      
      foreach ($products as $product) {
	$data['products'][] = array(
				    'order_product_id' => $product['order_product_id'],
				    'product_id'       => $product['product_id'],
				    'name' 	   => $product['name'],
				    'model'	   => $product['model'],
				    'quantity'   => $product['quantity'],
				    'price'	   => $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0),
				    'total'	   => $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0),
				    'tax'              => $product['tax'],
				    'reward'           => $product['reward']
				    );
	
      }
      $data['totals'] = $this->model_sale_order->getOrderTotals($order_id);
      print_r($data);

    }
  }
  
  private function makeInvoiceFromOrder($order_id) {

    //// print_r($this->CONF);

    $comment = '';

    $data = array();
    
    $this->load->model('sale/order');
    
    $order = $this->model_sale_order->getOrder($order_id);
    
    if ($order) {

      opencart_invfox__trace($order,0);
  
      $api = new InvfoxAPI($this->CONF['API_KEY'], $this->CONF['API_DOMAIN'], true);
      $api->setDebugHook("opencart_invfox__trace");
  
      opencart_invfox__trace("============ INVFOX::begin ===========");

      $r = $api->assurePartner(array(
				     'name' => $order['payment_firstname']." ".$order['payment_lastname'].($order['payment_company']?", ":"").$order['payment_company'],
				     'street' => $order['payment_address_1']."\n". $order['payment_address_2'],
				     'postal' => $order['payment_postcode'],
				     'city' =>$order['payment_city'],
				     'country' => $order['payment_country'],
				     'vatid' => $order['payment_tax_id'],
				     'phone' => $order['telephone'], //$c->phone.($c->phone_mobile?", ":"").$c->phone_mobile,
				     'website' => "",
				     'email' => $order['email'],
				     'notes' => '',
				     'vatbound' => false,//!!$c->vat_number, TODO -- after (2)
				     'custaddr' => '',
				     'payment_period' => $this->CONF['customer_general_payment_period'],
				     'street2' => ''
				     ));

      opencart_invfox__trace("============ INVFOX::assured partner ============");
      
      if ($r->isOk()) {
    
	$comment .= "- contact added\n";

	opencart_invfox__trace("============ INVFOX::before create invoice ============");
      
	$clientIdA = $r->getData();
	$clientId = $clientIdA[0]['id'];
      
	$date1 = $api->_toSIDate(date('Y-m-d')); //// TODO LONGTERM ... figure out what we do with different Dates on api side (maybe date optionally accepts dbdate format)
	$invid = str_pad($order_id, 5, "0", STR_PAD_LEFT); //todo: ask, check
      
      
	$data['products'] = array();

	$products = $this->model_sale_order->getOrderProducts($order_id);
      
	$body2 = array();

	foreach( $products as $product ) {
	  opencart_invfox__trace($product,0);
	  //$product = $ite$order->get_product_from_item( $item );
	  $body2[] = array(
			   'title' => $product['name']." ".$product['model'],// $product->post->post_title.($this->CONF['add_post_content_in_item_descr']?"\n".$product->post->post_content:""),
			   'qty' => $product['quantity'],
			   'mu' => '',
			   'price' => round($product['price'], 2),
			   'vat' => round($product['tax'] / $product['price'] * 100, $this->CONF['round_calculated_taxrate_to']),
			   'discount' => 0
			   );
	}
	
	$shipping = $this->findShipping($this->model_sale_order->getOrderTotals($order_id));

	if ($shipping) {
	  opencart_invfox__trace("============ INVFOX:: adding shipping ============");
	  if ($this->CONF['partial_sum_label']) {
	    $body2[] = array(
			     'title' => "= ".$this->CONF['partial_sum_label'],
			     'qty' => 1,
			     'mu' => '',
			     'price' => 0,
			     'vat' => 0,
			     'discount' => 0
			     );
	  }
	  
	  $body2[] = array(
			   'title' => $shipping['title'],
			   'qty' => 1,
			   'mu' => '',
			   'price' => $shipping['value'],
			   'vat' => $this->CONF['tax_rate_on_shipping'], //round($order->order_shipping_tax / $order->order_shipping * 100,  $this->CONF['round_calculated_taxrate_to']),
			   'discount' => 0
			   );
	    }
      }
	
      /*      */
      opencart_invfox__trace("============ INVFOX::before create invoice call ============");

      // TODO -- it can make it's own INVOICENUMS OR INVFOX CAN MAKE THEM

      $invoice_no = $this->CONF['use_shop_document_numbers'] ? $order['invoice_prefix'] . $order['order_id'] : '';

      opencart_invfox__trace($invoice_no);
      
      if ($this->CONF['document_to_make'] == 'invoice') {

	$r2 = $api->createInvoice(
				  array(
					'title' => $invoice_no,
					'date_sent' => $date1,
					'date_to_pay' => $date1,
					'date_served' => $date1, // MAY NOT BE NULL IN SOME VERSIONS OF USER DBS
					'id_partner' => $clientId,
					'taxnum' => '-',
					'doctype' => 0
					),
				  $body2
				  );
	if ($r2->isOk()) {    
	  $invA = $r2->getData();
	  $comment .= "- invoice # {$invA[0]['title']} was created at {$this->CONF['APP_NAME']}.";
	} 

      } elseif ($this->CONF['document_to_make'] == 'proforma') {
	$r2 = $api->createProFormaInvoice(
					  array(
						'title' => $invoice_no,
						'date_sent' => $date1,
						'days_valid' => $this->CONF['proforma_days_valid'],
						'id_partner' => $clientId,
						'taxnum' => '-'
						),
					  $body2
					  );
	if ($r2->isOk()) {    
	  $invA = $r2->getData();
	  $comment .= "- pro forma invoice # {$invA[0]['title']} was created at {$this->CONF['APP_NAME']}.";
	} 

      } elseif ($this->CONF['document_to_make'] == 'inventory') {
	$r2 = $api->createProFormaInvoice(
					  array(
						'title' => $invoice_no,
						'date_sent' => $date1,
						'days_valid' => $this->CONF['proforma_days_valid'],
						'id_partner' => $clientId,
						'taxnum' => '-'
						),
					  $body2
					  );
	if ($r2->isOk()) {    
	  $invA = $r2->getData();
	  $comment .= "- inventory sale document # {$invA[0]['title']} was created at {$this->CONF['APP_NAME']}.";
	} 
      }
      opencart_invfox__trace($r2);
      opencart_invfox__trace("============ INVFOX::after create invoice ============");
    }
    
    return $comment;
  }

  private function findShipping($totals) {
    foreach ($totals as $total) {
      if ($total['code'] == 'shipping') {
	return $total;
      }
    }
    return null;
  }
    
}
