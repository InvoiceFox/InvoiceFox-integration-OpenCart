<?php  
class ControllerModuleHelloworld extends Controller { 
	protected function index() {
		$this->language->load('module/invoicefox');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_mymod'] 		= $this->language->get('text_mymod');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/invoicefox.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/invoicefox.tpl';
		} else {
			$this->template = 'default/template/module/invoicefox.tpl';
		}
		
		$this->render();
	}
}
?>