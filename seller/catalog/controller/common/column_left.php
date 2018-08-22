<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		$this->load->model('extension/extension');
		$this->load->language('common/column_left');
		$this->load->model('tool/image');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['logged'] 	= $this->seller->isLogged();
		$data['cancel'] 	= $this->url->link('account/account', '', 'SSL');
		$data['username']	= $this->seller->getFirstname();
		// Menu
		$data['menus'][] = array(
			'id'       => 'menu-dashboard',
			'icon'     => 'fa-dashboard',
			'name'     => $this->language->get('text_seller_dashboard'),
			'href'     => $this->url->link('seller/account', '', 'SSL'),
			'children' => array()
			);
		// Catalog
		$catalog = array(); 
		$catalog[] = array(
			'name'  => $this->language->get('text_seller_categories'),
			'href'  => $this->url->link('seller/category', '', 'SSL'),
			'children' => array()       
			);   
		$catalog[] = array(
			'name'  => $this->language->get('text_seller_products'),
			'href'  => $this->url->link('seller/product', '', 'SSL'),
			'children' => array()       
			);   
		$catalog[] = array(
			'name'  => $this->language->get('text_seller_add_product'),
			'href'  => $this->url->link('seller/product/add', '', 'SSL'),
			'children' => array()       
			);                
		$catalog[] = array(
			'name' => $this->language->get('text_seller_downloads'),
			'href' => $this->url->link('seller/download', '', 'SSL'),
			'children' => array()       
			);
		$catalog[] = array(
			'name'     => $this->language->get('text_seller_attributes'),
			'href'     => $this->url->link('seller/attribute', '', 'SSL'),
			'children' => array()       
			);
		$catalog[] = array(
			'name'  => $this->language->get('text_seller_add_options'),
			'href'  => $this->url->link('seller/option', '', 'SSL'),
			'children' => array()       
			);                
		if ($catalog) {                   
			$data['menus'][] = array(
				'id'       => 'menu-catalog',
				'icon'     => 'fa-tag', 
				'name'     => $this->language->get('text_seller_catalog'),
				'href'     => '',
				'children' => $catalog
				);      
		}   
		 // Sales
		$seller_sales = array();    
		$seller_sales[] = array(
			'name'  => $this->language->get('text_seller_orders'),
			'href'  => $this->url->link('seller/order', '', 'SSL'),
			'children' => array()       
			);                 
		if ($seller_sales) {                   
			$data['menus'][] = array(
				'id'       => 'menu-seller_sales',
				'icon'     => 'fa-shopping-cart', 
				'name'     => $this->language->get('text_seller_sales'),
				'href'     => '',
				'children' => $seller_sales
				);      
		}
		// Profile
		$profile = array();    
		$profile[] = array(
			'name'  => $this->language->get('text_seller_edit'),
			'href'  => $this->url->link('seller/edit', '', 'SSL'),
			'children' => array()
			);
		$profile[] = array(
			'name'  => $this->language->get('text_seller_address'),
			'href'  => $this->url->link('seller/address', '', 'SSL'),
			'children' => array()       
			);
		$profile[] = array(
			'name'  => $this->language->get('text_seller_messages'),
			'href'  => $this->url->link('seller/messages', '', 'SSL'),
			'children' => array()       
			);
		$profile[] = array(
			'name' => $this->language->get('text_seller_membership'),
			'href' => $this->url->link('seller/membership', '', 'SSL'),
			'children' => array()       
			);
		$profile[] = array(
			'name' => $this->language->get('text_seller_password'),
			'href' => $this->url->link('seller/password', '', 'SSL'),
			'children' => array()       
			);
		if ($profile) {                   
			$data['menus'][] = array(
				'id'       => 'menu-profile',
				'icon'     => 'fa-user', 
				'name'     => $this->language->get('text_seller_profile'),
				'href'     => '',
				'children' => $profile
				);      
		}
		// Transactions
		$seller_transactions = array();    
		$seller_transactions[] = array(
			'name'  => $this->language->get('text_seller_transactions'),
			'href'  => $this->url->link('seller/transaction', '', 'SSL'),
			'children' => array()       
			);                  
		$seller_transactions[] = array(
			'name'  => $this->language->get('text_seller_payment_methods'),
			'href'  => $this->url->link('seller/pay_address', '', 'SSL'),
			'children' => array()
			);
		if ($seller_transactions) {                   
			$data['menus'][] = array(
				'id'       => 'menu-seller_transactions',
				'icon'     => 'fa-exchange', 
				'name'     => $this->language->get('text_seller_transactions'),
				'href'     => '',
				'children' => $seller_transactions
				);      
		} 
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_left.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/column_left.tpl', $data);
		} else {
			return $this->load->view('default/template/common/column_left.tpl', $data);
		}
	}
}