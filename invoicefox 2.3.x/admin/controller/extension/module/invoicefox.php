<?php
class ControllerExtensionModuleInvoiceFox extends Controller {
	private $error = array(); 
	
	public function index() { 
		
		 $sql = "
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "invoicefox` (
			  `id` int(11) unsigned AUTO_INCREMENT  PRIMARY KEY,
			  `invoicefox_id` int(11) NOT NULL,
			  `order_id` int(11) NOT NULL,
			  `doc_num` varchar(50) NOT NULL,
			  `tax_id` varchar(50) NOT NULL,
			  `is_finalize` int(1) NOT NULL,
			  `status` enum('active','deleted') NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8";
		
		$this->db->query($sql);

		$this->load->language('module/invoicefox');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('localisation/order_status');

		

				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('invoicefox', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');


			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
						
		}
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');


		
		$data['entry_api_key'] = $this->language->get('entry_api_key');
		$data['entry_api_domain'] = $this->language->get('entry_api_domain');
		$data['entry_app_name'] = $this->language->get('entry_app_name');
		$data['entry_document_to_make'] = $this->language->get('entry_document_to_make');

		$data['entry_proforma_days_valid'] = $this->language->get('entry_proforma_days_valid');
		$data['entry_customer_general_payment_period'] = $this->language->get('entry_customer_general_payment_period');
		$data['entry_add_post_content_in_item_descr'] = $this->language->get('entry_add_post_content_in_item_descr');
		$data['entry_partial_sum_label'] = $this->language->get('entry_partial_sum_label');
		$data['entry_display_product_option_label'] = $this->language->get('entry_display_product_option_label');
		
		
		$data['entry_round_calculated_taxrate_to'] = $this->language->get('entry_round_calculated_taxrate_to');
		$data['entry_round_calculated_netprice_to'] = $this->language->get('entry_round_calculated_netprice_to');
		$data['entry_from_warehouse_id'] = $this->language->get('entry_from_warehouse_id');
		$data['entry_tax_rate_on_shipping'] = $this->language->get('entry_tax_rate_on_shipping');
		$data['entry_use_shop_document_numbers'] = $this->language->get('entry_use_shop_document_numbers');
		$data['entry_create_invfox_document_on_status'] = $this->language->get('entry_create_invfox_document_on_status');
		$data['entry_close_invfox_document_on_status'] = $this->language->get('entry_close_invfox_document_on_status');

		$data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$data['entry_tax_name'] = $this->language->get('entry_tax_name');
		$data['entry_tax_location'] = $this->language->get('entry_tax_location');
		$data['entry_tax_register'] = $this->language->get('entry_tax_register');
		$data['entry_fiscalize'] = $this->language->get('entry_fiscalize');
			
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_finalize'] = $this->language->get('button_finalize');
		$data['button_getfiscalizeinfo'] = $this->language->get('button_getfiscalizeinfo');


 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['api_key'])) {
			$data['error_api_key'] = $this->error['api_key'];
		} else {
			$data['error_api_key'] = '';
		}
		
		if (isset($this->error['api_domain'])) {
			$data['error_api_domain'] = $this->error['api_domain'];
		} else {
			$data['error_api_domain'] = '';
		}
		
		if (isset($this->error['app_name'])) {
			$data['error_app_name'] = $this->error['app_name'];
		} else {
			$data['error_app_name'] = '';
		}

		if (isset($this->error['document_to_make'])) {
			$data['error_document_to_make'] = $this->error['document_to_make'];
		} else {
			$data['error_document_to_make'] = '';
		}


		if (isset($this->error['proforma_days_valid'])) {
			$data['error_proforma_days_valid'] = $this->error['proforma_days_valid'];
		} else {
			$data['error_proforma_days_valid'] = '';
		}

		if (isset($this->error['customer_general_payment_period'])) {
			$data['error_customer_general_payment_period'] = $this->error['customer_general_payment_period'];
		} else {
			$data['error_customer_general_payment_period'] = '';
		}

		if (isset($this->error['add_post_content_in_item_descr'])) {
			$data['error_add_post_content_in_item_descr'] = $this->error['add_post_content_in_item_descr'];
		} else {
			$data['error_add_post_content_in_item_descr'] = '';
		}

		if (isset($this->error['partial_sum_label'])) {
			$data['error_partial_sum_label'] = $this->error['partial_sum_label'];
		} else {
			$data['error_partial_sum_label'] = '';
		}

		if (isset($this->error['tax_id'])) {
			$data['error_tax_id'] = $this->error['tax_id'];
		} else {
			$data['error_tax_id'] = '';
		}

		if (isset($this->error['tax_name'])) {
			$data['error_tax_name'] = $this->error['tax_name'];
		} else {
			$data['error_tax_name'] = '';
		}

		if (isset($this->error['tax_location'])) {
			$data['error_tax_location'] = $this->error['tax_location'];
		} else {
			$data['error_tax_location'] = '';
		}

		if (isset($this->error['tax_register'])) {
			$data['error_tax_register'] = $this->error['tax_register'];
		} else {
			$data['error_tax_register'] = '';
		}
		

		if (isset($this->error['create_invfox_document_on_status'])) {
			$data['error_create_invfox_document_on_status'] = $this->error['create_invfox_document_on_status'];
		} else {
			$data['error_create_invfox_document_on_status'] = '';
		}




  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/invoicefox', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['action'] = $this->url->link('extension/module/invoicefox', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');
				
		$data['modules'] = array();
		
		
		if (isset($this->request->post['invoicefox_api_key'])) {
			$data['invoicefox_api_key'] = $this->request->post['invoicefox_api_key'];
		} elseif($this->config->get('invoicefox_api_key')){ 
			$data['invoicefox_api_key'] = $this->config->get('invoicefox_api_key');
		}else { 
			$data['invoicefox_api_key'] = '';
		}

		if (isset($this->request->post['invoicefox_api_domain'])) {
			$data['invoicefox_api_domain'] = $this->request->post['invoicefox_api_domain'];
		}elseif($this->config->get('invoicefox_api_domain')){ 
			$data['invoicefox_api_domain'] = $this->config->get('invoicefox_api_domain');
		} else { 
			$data['invoicefox_api_domain'] = 'www.cebelca.biz';
		}

		if (isset($this->request->post['invoicefox_app_name'])) {
			$data['invoicefox_app_name'] = $this->request->post['invoicefox_app_name'];
		}elseif($this->config->get('invoicefox_app_name')){ 
			$data['invoicefox_app_name'] = $this->config->get('invoicefox_app_name');
		} else { 
			$data['invoicefox_app_name'] = 'Cebelca.biz';
		}

		if (isset($this->request->post['invoicefox_document_to_make'])) {
			$data['invoicefox_document_to_make'] = $this->request->post['invoicefox_document_to_make'];
		}elseif($this->config->get('invoicefox_document_to_make')){ 
			$data['invoicefox_document_to_make'] = $this->config->get('invoicefox_document_to_make');
		} else { 
			$data['invoicefox_document_to_make'] = 'inventory';
		}

		if (isset($this->request->post['invoicefox_proforma_days_valid'])) {
			$data['invoicefox_proforma_days_valid'] = $this->request->post['invoicefox_proforma_days_valid'];
		}elseif($this->config->get('invoicefox_proforma_days_valid')!==''){ 
			$data['invoicefox_proforma_days_valid'] = $this->config->get('invoicefox_proforma_days_valid');
		} else { 
			$data['invoicefox_proforma_days_valid'] = '10';
		}

		if (isset($this->request->post['invoicefox_customer_general_payment_period'])) {
			$data['invoicefox_customer_general_payment_period'] = $this->request->post['invoicefox_customer_general_payment_period'];
		}elseif($this->config->get('invoicefox_customer_general_payment_period')!==''){ 
			$data['invoicefox_customer_general_payment_period'] = $this->config->get('invoicefox_customer_general_payment_period');
		} else { 
			$data['invoicefox_customer_general_payment_period'] = '5';
		}
		
		/*
		if (isset($this->request->post['invoicefox_add_post_content_in_item_descr'])) {
			$data['invoicefox_add_post_content_in_item_descr'] = $this->request->post['invoicefox_add_post_content_in_item_descr'];
		}elseif($this->config->get('invoicefox_add_post_content_in_item_descr')!==''){ 
			$data['invoicefox_add_post_content_in_item_descr'] = $this->config->get('invoicefox_add_post_content_in_item_descr');
		} else { 
			$data['invoicefox_add_post_content_in_item_descr'] = '0';
		}
		*/

		if (isset($this->request->post['invoicefox_display_product_option_label'])) {
			$data['invoicefox_display_product_option_label'] = $this->request->post['invoicefox_display_product_option_label'];
		}elseif(!$this->config->get('invoicefox_display_product_option_label')!==''){ 
			$data['invoicefox_display_product_option_label'] = $this->config->get('invoicefox_display_product_option_label');
		} else { 
			$data['invoicefox_display_product_option_label'] = '0';
		}


		if (isset($this->request->post['invoicefox_partial_sum_label'])) {
			$data['invoicefox_partial_sum_label'] = $this->request->post['invoicefox_partial_sum_label'];
		}elseif($this->config->get('invoicefox_partial_sum_label')){ 
			$data['invoicefox_partial_sum_label'] = $this->config->get('invoicefox_partial_sum_label');
		} else { 
			$data['invoicefox_partial_sum_label'] = 'Skupaj';
		}

		if (isset($this->request->post['invoicefox_round_calculated_taxrate_to'])) {
			$data['invoicefox_round_calculated_taxrate_to'] = $this->request->post['invoicefox_round_calculated_taxrate_to'];
		}elseif($this->config->get('invoicefox_round_calculated_taxrate_to')!==''){ 
			$data['invoicefox_round_calculated_taxrate_to'] = $this->config->get('invoicefox_round_calculated_taxrate_to');
		} else { 
			$data['invoicefox_round_calculated_taxrate_to'] = '1';
		}

		if (isset($this->request->post['invoicefox_round_calculated_netprice_to'])) {
			$data['invoicefox_round_calculated_netprice_to'] = $this->request->post['invoicefox_round_calculated_netprice_to'];
		}elseif($this->config->get('invoicefox_round_calculated_netprice_to')!==''){ 
			$data['invoicefox_round_calculated_netprice_to'] = $this->config->get('invoicefox_round_calculated_netprice_to');
		} else { 
			$data['invoicefox_round_calculated_netprice_to'] = '3';
		}

		if (isset($this->request->post['invoicefox_from_warehouse_id'])) {
			$data['invoicefox_from_warehouse_id'] = $this->request->post['invoicefox_from_warehouse_id'];
		}elseif($this->config->get('invoicefox_from_warehouse_id')!==''){ 
			$data['invoicefox_from_warehouse_id'] = $this->config->get('invoicefox_from_warehouse_id');
		} else { 
			$data['invoicefox_from_warehouse_id'] = '3';
		}


		if (isset($this->request->post['invoicefox_tax_rate_on_shipping'])) {
			$data['invoicefox_tax_rate_on_shipping'] = $this->request->post['invoicefox_tax_rate_on_shipping'];
		}elseif($this->config->get('invoicefox_tax_rate_on_shipping')!==''){ 
			$data['invoicefox_tax_rate_on_shipping'] = $this->config->get('invoicefox_tax_rate_on_shipping');
		} else { 
			$data['invoicefox_tax_rate_on_shipping'] = '17.5';
		}

		if (isset($this->request->post['invoicefox_use_shop_document_numbers'])) {
			$data['invoicefox_use_shop_document_numbers'] = $this->request->post['invoicefox_use_shop_document_numbers'];
		}elseif($this->config->get('use_shop_document_numbers')!==''){ 
			$data['invoicefox_use_shop_document_numbers'] = $this->config->get('invoicefox_use_shop_document_numbers');
		} else { 
			$data['invoicefox_use_shop_document_numbers'] = '0';
		}

		if (isset($this->request->post['invoicefox_create_invfox_document_on_status'])) {
			$data['invoicefox_create_invfox_document_on_status'] = $this->request->post['invoicefox_create_invfox_document_on_status'];
		}elseif($this->config->get('invoicefox_create_invfox_document_on_status')){ 
			$data['invoicefox_create_invfox_document_on_status'] = $this->config->get('invoicefox_create_invfox_document_on_status');
		} else { 
			$data['invoicefox_create_invfox_document_on_status'] = 'Processed';
		}

		if (isset($this->request->post['invoicefox_close_invfox_document_on_status'])) {
			$data['invoicefox_close_invfox_document_on_status'] = $this->request->post['invoicefox_close_invfox_document_on_status'];
		}elseif($this->config->get('invoicefox_close_invfox_document_on_status')){ 
			$data['invoicefox_close_invfox_document_on_status'] = $this->config->get('invoicefox_close_invfox_document_on_status');
		} else { 
			$data['invoicefox_close_invfox_document_on_status'] = 'Complete';
		}

		if (isset($this->request->post['invoicefox_tax_id'])) {
			$data['invoicefox_tax_id'] = $this->request->post['invoicefox_tax_id'];
		}elseif($this->config->get('invoicefox_tax_id')!==''){ 
			$data['invoicefox_tax_id'] = $this->config->get('invoicefox_tax_id');
		} else { 
			$data['invoicefox_tax_id'] = '';
		}

		if (isset($this->request->post['invoicefox_tax_name'])) {
			$data['invoicefox_tax_name'] = $this->request->post['invoicefox_tax_name'];
		}elseif($this->config->get('invoicefox_tax_name')!==''){ 
			$data['invoicefox_tax_name'] = $this->config->get('invoicefox_tax_name');
		} else { 
			$data['invoicefox_tax_name'] = '';
		}

		if (isset($this->request->post['invoicefox_tax_location'])) {
			$data['invoicefox_tax_location'] = $this->request->post['invoicefox_tax_location'];
		}elseif($this->config->get('invoicefox_tax_location')!==''){ 
			$data['invoicefox_tax_location'] = $this->config->get('invoicefox_tax_location');
		} else { 
			$data['invoicefox_tax_location'] = '';
		}

		if (isset($this->request->post['invoicefox_tax_register'])) {
			$data['invoicefox_tax_register'] = $this->request->post['invoicefox_tax_register'];
		}elseif($this->config->get('invoicefox_tax_register')!==''){ 
			$data['invoicefox_tax_register'] = $this->config->get('invoicefox_tax_register');
		} else { 
			$data['invoicefox_tax_register'] = '1';
		}

		if (isset($this->request->post['invoicefox_fiscalize'])) {
			$data['invoicefox_fiscalize'] = $this->request->post['invoicefox_fiscalize'];
		}elseif($this->config->get('invoicefox_fiscalize')!==''){ 
			$data['invoicefox_fiscalize'] = $this->config->get('invoicefox_fiscalize');
		} else { 
			$data['invoicefox_fiscalize'] = '0';
		}


		
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/invoicefox.tpl', $data));
		
		
	}

	public function print_invoice() {   
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = null;
		}

		if (isset($this->request->get['hstyle'])) {
			$hstyle = $this->request->get['hstyle'];
		} else {
			$hstyle = 'basicVER3'; //basicVER3UPN
		}

		$this->load->language('module/invoicefox');
		if(file_exists(DIR_CATALOG."model/invoicefox/hooks.php"))
        {                          
              require_once(DIR_CATALOG."model/invoicefox/hooks.php");
              $hooks = new ModelInvoiceFoxHooks( $this->registry );
              $hooks->printInvoice($order_id, 'invoice-sent',$hstyle);
        }

	}

	public function finalize_invoice() {  
		
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = null;
		}

		$result = array();
		$this->load->language('module/invoicefox');
		if(file_exists(DIR_CATALOG."model/invoicefox/hooks.php") && $order_id)
        {                          
			  require_once(DIR_CATALOG."model/invoicefox/hooks.php");
              $hooks = new ModelInvoiceFoxHooks( $this->registry );
			  $result = $hooks->finalizeInvoice($order_id);
			  echo json_encode($result);
			  exit;
	  
        }
		else{
			echo json_encode($result);
		}

	}

	public function get_fiscal_info() {  
		
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = null;
		}
		
		if($order_id){
			$this->load->language('module/invoicefox');
			if(file_exists(DIR_CATALOG."model/invoicefox/hooks.php"))
			{                          
				  require_once(DIR_CATALOG."model/invoicefox/hooks.php");
				  $hooks = new ModelInvoiceFoxHooks( $this->registry );
				  $result = $hooks->getFiscalInfo($order_id);
				  echo "<pre>";
				  print_R($result);
				  exit;
			}
		}
		else{
			echo "Please enter id";
		}

	}
	
	

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/invoicefox')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['invoicefox_api_key']) {
			$this->error['api_key'] = $this->language->get('error_api_key');
		}

		if (!$this->request->post['invoicefox_api_domain']) {
			$this->error['api_domain'] = $this->language->get('error_api_domain');
		}

		if (!$this->request->post['invoicefox_app_name']) {
			$this->error['app_name'] = $this->language->get('error_app_name');
		}

		if (!$this->request->post['invoicefox_document_to_make']) {
			$this->error['document_to_make'] = $this->language->get('error_document_to_make');
		}

		if (!$this->request->post['invoicefox_proforma_days_valid']) {
			$this->error['proforma_days_valid'] = $this->language->get('error_proforma_days_valid');
		}

		if (!$this->request->post['invoicefox_customer_general_payment_period']) {
			$this->error['customer_general_payment_period'] = $this->language->get('error_customer_general_payment_period');
		}

		

		if (!$this->request->post['invoicefox_partial_sum_label']) {
			$this->error['partial_sum_label'] = $this->language->get('error_partial_sum_label');
		}

		if (!$this->request->post['invoicefox_create_invfox_document_on_status']) {
			$this->error['create_invfox_document_on_status'] = $this->language->get('error_create_invfox_document_on_status');
		}

		if ($this->request->post['invoicefox_tax_id']==='') {
			$this->error['tax_id'] = $this->language->get('error_tax_id');
		}

		if ($this->request->post['invoicefox_tax_name']==='') {
			$this->error['tax_name'] = $this->language->get('error_tax_name');
		}

		if ($this->request->post['invoicefox_tax_location']==='') {
			$this->error['tax_location'] = $this->language->get('error_tax_location');
		}

		if ($this->request->post['invoicefox_tax_register']==='') {
			$this->error['tax_register'] = $this->language->get('error_tax_register');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>