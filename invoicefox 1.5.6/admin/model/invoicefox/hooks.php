<?php 

//if(file_exists(__DIR__."/hooksExtensions.php")) { include_once 'hooksExtensions.php'; } 

/*
  Plugin Name: OpenCart+InvoiceFox
*/

// Status: Alpha

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
require_once(dirname(__FILE__).'/lib/invfoxapi.php');
require_once(dirname(__FILE__).'/lib/strpcapi.php');

function opencart_invfox__trace($x) { error_log(print_r($x,true)."-----"); } // todo -- move to class


class ModelInvoiceFoxHooks extends Model { 

  var $CONF;
  
  public function __construct($registry)
  {
    parent::__construct($registry);


   
    // THE MYCONFIGURATION
	$invoicefox_use_shop_document_numbers = ($this->config->get('invoicefox_use_shop_document_numbers')=="1")?true:false;
	$invoicefox_add_post_content_in_item_descr = ($this->config->get('invoicefox_add_post_content_in_item_descr')=="1")?true:false;
	$display_partial_sum_label = ($this->config->get('invoicefox_display_partial_sum_label')=="1")?true:false;
	$display_product_option_label = ($this->config->get('invoicefox_display_product_option_label')=="1")?true:false;
	
    $this->CONF = array(
			// you get it in InvoiceFox/Cebelca/Abelie/..., on page "access" after you activate the API
			'API_KEY'=>$this->config->get('invoicefox_api_key'),
			// options: "www.invoicefox.com" "www.invoicefox.co.uk" "www.invoicefox.com.au" "www.cebelca.biz" "www.abelie.biz" 
			'API_DOMAIN'=>$this->config->get('invoicefox_api_domain'),
			'APP_NAME'=>$this->config->get('invoicefox_app_name'),
			// options: "invoice" "proforma" "inventory"
			'document_to_make'=>$this->config->get('invoicefox_document_to_make'),
			'proforma_days_valid'=>$this->config->get('invoicefox_proforma_days_valid'),
			'customer_general_payment_period'=>$this->config->get('invoicefox_customer_general_payment_period'),
			'add_post_content_in_item_descr'=>$invoicefox_add_post_content_in_item_descr,
			// Leave empty for NO 'partial sum' line
			'partial_sum_label'=>$this->config->get('invoicefox_partial_sum_label'),
			'display_partial_sum_label'=>$display_partial_sum_label,
			'display_product_option_label'=>$display_product_option_label,
			'round_calculated_taxrate_to'=>$this->config->get('invoicefox_round_calculated_taxrate_to'),
			'round_calculated_netprice_to'=>$this->config->get('invoicefox_round_calculated_netprice_to'),
			// Find the ID of the InvoiceFox inventory this goes out (look at README)
			'from_warehouse_id'=>$this->config->get('invoicefox_from_warehouse_id'),
			'tax_rate_on_shipping'=>$this->config->get('invoicefox_tax_rate_on_shipping'),
			// Use the opencart ID for document number or the InvoiceFox/Cebelca system
			'use_shop_document_numbers'=>$invoicefox_use_shop_document_numbers,
			// You can change this, look at avaliable statuses at "Sales > Orders > View > History"
			'create_invfox_document_on_status'=>$this->config->get('invoicefox_create_invfox_document_on_status'),
			'close_invfox_document_on_status'=>$this->config->get('invoicefox_close_invfox_document_on_status'),
			
			'tax_id'=>$this->config->get('invoicefox_tax_id'),
			'tax_name'=>$this->config->get('invoicefox_tax_name'),
			'tax_location'=>$this->config->get('invoicefox_tax_location'),
			'tax_register'=>$this->config->get('invoicefox_tax_register'),
			'fiscalize'=>$this->config->get('invoicefox_fiscalize'),
			);
    

    // END OF THE MYCONFIGURATION


  }
  
  public function changeOrderStatusHook($status_id, $comment, $order_id) {

    $newComment = '';
    error_log("IN ORDER STATUS HOOK", E_USER_ERROR);
	
	$this->load->model('sale/order');
    $this->load->model('localisation/order_status');
    
    $order_status_info = $this->model_localisation_order_status->getOrderStatus($status_id);
    if ($order_status_info){ $data['order_status'] = $order_status_info['name']; } else { $data['order_status'] = ''; }
    
    opencart_invfox__trace($data['order_status']);
	
	$invoicefox_id = $this->model_sale_order->getInvoicefoxId($order_id); 
    if ($data['order_status'] == $this->CONF['create_invfox_document_on_status'] && $invoicefox_id=='') {
      
      $newComment = $this->makeInvoiceFromOrder($order_id);
      
      error_log("IN ORDER STATUS HOOK END", E_USER_ERROR);
    }

	if ($data['order_status'] == $this->CONF['close_invfox_document_on_status']) {
      
      $api = new InvfoxAPI($this->CONF['API_KEY'], $this->CONF['API_DOMAIN'], true);
      $api->setDebugHook("opencart_invfox__trace");
	  
	  $this->load->model('sale/order');
	  $order = $this->model_sale_order->getOrder($order_id);
	  $invoice_no = $this->CONF['use_shop_document_numbers'] ? $order['invoice_prefix'] . $order['order_id'] : '-';
	  $r = $api->markInvoicePaid($order['order_id']);
      
      error_log("IN ORDER STATUS HOOK END", E_USER_ERROR);
    }

    return $comment.($comment?"\n\n":"").$newComment;
  }

  public function getLanguageDirectory() {

  }

  public function finalizeInvoice($order_id){
	  
	  $header['id']=0;
	  $header['id_invoice_sent_ext']=$order_id;
	  $header['id_register']=$this->CONF['tax_register']; // id of register
	  $header['fiscalize']=$this->CONF['fiscalize'];	// should fiscalize or not / cash invoice or not (send to FURS / Tax Office)
	  $header['id_location']=$this->CONF['tax_location']; // id of location
	  $header['op-tax-id']=$this->CONF['tax_id'];
	  $header['op-name']=$this->CONF['tax_name']; 
	  $api = new InvfoxAPI($this->CONF['API_KEY'], $this->CONF['API_DOMAIN'], true);
      $api->setDebugHook("opencart_invfox__trace");
	  $r = $api->finalizeInvoice($header);
	  $result=array();
	  $result['status']='fail';
	  if(is_array($r)){
		if(isset($r[0]['err'])){
			$result['error']=$r[0]['err'];
		}
		elseif(isset($r[0]['docnum'])){
			$result['status']='success';
			$result['docnum']=$r[0]['docnum'];
			$result['eor']=$r[0]['eor'];
			
			$this->load->model('sale/order');
			$this->model_sale_order->updateInvoiceFinalized($order_id); 
			$this->model_sale_order->updateInvoiceDocNum($order_id,$r[0]['docnum']); 
			
		}
		else{
			$result['error']='Error occured';
		}
		
	  }
	  else{
		  $result['error']='Error occured';
	  }
	  return $result;
  }
  
  public function getFiscalInfo($order_id){
	  $api = new InvfoxAPI($this->CONF['API_KEY'], $this->CONF['API_DOMAIN'], true);
      $api->setDebugHook("opencart_invfox__trace");
	  $r = $api->getFiscalInfo($order_id);
	  return $r;
  }

  private function makeInvoiceFromOrder($order_id) {

	$comment = '';

    $data = array();
    
	$this->load->language('module/invoicefox');
    $this->load->model('sale/order');
    $this->load->model('catalog/product');
    
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
	$subtotal = 0;
	$producttax = 0;
	foreach( $products as $product ) {
	  $productMore = $this->model_catalog_product->getProduct($product['order_product_id']);
	  
	  $product_options_text='';
	  if($this->CONF['display_product_option_label']){
		$product_options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);
		if($product_options)
			foreach($product_options as $product_option){
				$product_options_text .= $product_option['name']. ':'.$product_option['value']."\n";
			}
	  }
	  $subtotal = $subtotal + round($product['price'], 2);
	  opencart_invfox__trace($product,0);
	  $body2[] = array(
			   'code' => isset($productMore['sku'])?$productMore['sku']:'',
			   'title' => $product['name']." ".$product['model']."\n".$product_options_text,// $product->post->post_title.($this->CONF['add_post_content_in_item_descr']?"\n".$product->post->post_content:""),
			   'qty' => $product['quantity'],
			   'mu' => '',
			   'price' => round($product['price'], 2),
			   'vat' => round($product['tax'] / $product['price'] * 100, $this->CONF['round_calculated_taxrate_to']),
			   'discount' => 0
			   );
	  $producttax = round($product['tax'] / $product['price'] * 100, $this->CONF['round_calculated_taxrate_to']);
	}

	$shipping = $this->findShipping($this->model_sale_order->getOrderTotals($order_id));
	$coupon = $this->findCoupon($this->model_sale_order->getOrderTotals($order_id));
	
	if ($this->CONF['document_to_make'] != 'inventory' && $shipping['value'] > 0) {
	  
	  if ($shipping) {
	    opencart_invfox__trace("============ INVFOX:: adding shipping ============");
	    
		$discount = 0;
		if ($coupon) {
			$discount = $coupon['value'];
		}
		
		/*
		if ($this->CONF['partial_sum_label'] && $this->CONF['display_partial_sum_label']) {
	      $body2[] = array(
			       'title' => "= ".$this->CONF['partial_sum_label'],
			       'qty' => 1,
			       'mu' => '',
			       'price' => 0,
			       'vat' => 0,
			       'discount' => 0
			       );
	    }
		*/
		
	    
	    $body2[] = array(
			     'title' => $shipping['title'],
			     'qty' => 1,
			     'mu' => '',
			     'price' => $shipping['value'],
			     'vat' => $this->CONF['tax_rate_on_shipping'], //round($order->order_shipping_tax / $order->order_shipping * 100, $this->CONF['round_calculated_taxrate_to']),
			     'discount' => 0
			     );
	  }
	  if ($coupon) {
		  //$couponvat = round($product['tax'] / $coupon['value'] * 100, $this->CONF['round_calculated_taxrate_to']);
		  $body2[] = array(
			     'title' => $coupon['title'],
			     'qty' => 1,
			     'mu' => '',
			     'price' => $coupon['value'],
			     'vat' =>$producttax,
			     'discount' => 0
			     );
	  }
	}
      }
      /*      */
      opencart_invfox__trace("============ INVFOX::before create invoice call ============");

      // TODO -- it can make it's own INVOICENUMS OR INVFOX CAN MAKE THEM
	 
      $invid = $this->CONF['use_shop_document_numbers'] ? $order['invoice_prefix'] . $order['order_id'] : '';
	  $invoice_no = $order['invoice_prefix'] . $order['order_id'] ;
      opencart_invfox__trace($invoice_no);
      if ($this->CONF['document_to_make'] == 'invoice') {
		$r2 = $api->createInvoice(
					  array(
						'title' => $invid,
						'date_sent' => $date1,
						'date_to_pay' => $date1,
						'date_served' => $date1, // MAY NOT BE NULL IN SOME VERSIONS OF USER DBS
						'id_partner' => $clientId,
						'taxnum' => '-',
						'doctype' => 0,
						'id_document_ext' => $order['order_id'],
						 'pub_notes' => $invoice_no
						),
					  $body2
					  );


		if ($r2->isOk()) {    
		  $invA = $r2->getData();
		  $this->model_sale_order->insertInvoicefoxId($order_id,$invA[0]['id']);
		  //$comment .= "- invoice # {$invA[0]['title']} was created at {$this->CONF['APP_NAME']}.";
		  $comment .= "- ".$this->language->get('text_invoiceno')." $invoice_no was created at {$this->CONF['APP_NAME']}.";
		} 

      } elseif ($this->CONF['document_to_make'] == 'proforma') {
			$r2 = $api->createProFormaInvoice(
							  array(
								'title' => $invoice_no,
								'date_sent' => $date1,
								'days_valid' => $this->CONF['proforma_days_valid'],
								'id_partner' => $clientId,
								'taxnum' => '-',
								 'pub_notes' => $invoice_no
								),
							  $body2
							  );


			if ($r2->isOk()) {    
			  $invA = $r2->getData();
			  $comment .= "- pro forma invoice # {$invA[0]['title']} was created at {$this->CONF['APP_NAME']}.";
			} 

      } elseif ($this->CONF['document_to_make'] == 'inventory') {

			$invoice_no = $invoice_no == "-" ? "" : $invoice_no ;

			$r2 = $api->createInventorySale(
							array(
								  'docnum' => $invoice_no,
								  'date_created' => $date1,
								  'id_contact_to' => $clientId,
								  'id_contact_from' => $this->CONF['from_warehouse_id'],
								  'taxnum' => '-',
								  'doctype' => 1,
								   'pub_notes' => $invoice_no
								  ),
							$body2
							);
			if ($r2->isOk()) {    
			  $invA = $r2->getData();
			  $comment .= "Inventory sales document No. {$invA[0]['docnum']} was created at {$this->CONF['APP_NAME']}.";
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

  private function findCoupon($totals) {
    foreach ($totals as $total) {
      if ($total['code'] == 'coupon') {
			return $total;
      }
    }
    return null;
  }


  
  
  public function downloadPDF($id, $path, $res='invoice-sent',$hstyle='basicVER3'){
		$this->load->model('sale/order');
		$this->load->model('catalog/product');

		$api = new InvfoxAPI($this->CONF['API_KEY'], $this->CONF['API_DOMAIN'], true);
		$api->downloadPDF($id,$res,$hstyle);
    
		//$order = $this->model_sale_order->getOrder($order_id);
    
  }

  public function printInvoice($order_id, $res='invoice-sent',$hstyle='basicVER3'){
		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		
		if($order_id){
			$api = new InvfoxAPI($this->CONF['API_KEY'], $this->CONF['API_DOMAIN'], true);
			$api->printInvoice($order_id,$res,$hstyle);
		}
		else{
			echo "Error : There is no invoice generated";
		}
    
		//$order = $this->model_sale_order->getOrder($order_id);
    
  }

  

  
    
}
