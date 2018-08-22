<?php
class ControllerSellerSuccess extends Controller {
	public function index() {
		$this->load->language('seller/success');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['heading_title'] = $this->language->get('heading_title');
		$seller_group_info1=$this->config->get('config_seller_autoapprove');
		if($seller_group_info1){
	       	$data['text_message'] = sprintf($this->language->get('text_approval1'),
	       	HTTP_SERVER1, $this->url->link('information/contact'));
		}else{
			$data['text_message'] = sprintf($this->language->get('text_message'),
			HTTP_SERVER1, $this->url->link('information/contact'));
		}
		$data['button_continue'] = $this->language->get('button_continue');
		if ($this->cart->hasProducts()) {
			$data['continue'] = $this->url->link('checkout/cart');
		} else {
			$data['continue'] = $this->url->link('seller/account', '', 'SSL');
		}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}
}