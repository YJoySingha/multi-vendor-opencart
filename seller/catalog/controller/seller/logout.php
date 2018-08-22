<?php 
class ControllerSellerLogout extends Controller {
	public function index() {
		if ($this->seller->isLogged()) {
			$this->seller->logout();
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_country_id']);
			unset($this->session->data['shipping_zone_id']);
			unset($this->session->data['shipping_postcode']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_address_id']);
			unset($this->session->data['payment_country_id']);
			unset($this->session->data['payment_zone_id']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			$this->response->redirect($this->url->link('seller/logout', '', 'SSL'));
		}
		$this->language->load('seller/logout');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
			'separator' => false
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('seller/account', '', 'SSL'),       	
			'separator' => $this->language->get('text_separator')
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_logout'),
			'href'      => $this->url->link('seller/logout', '', 'SSL'),
			'separator' => $this->language->get('text_separator')
			);	
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_message'] = $this->language->get('text_message');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['name'] = $this->config->get('config_name');	
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = HTTP_SERVER1. 'image/' . $this->config->get('config_logo');
		} else {			$data['logo'] = '';		}
		$this->load->language('common/header');
		$this->load->language('seller/login');
		$data['text_home'] = $this->language->get('text_home');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['home'] = $this->url->link('common/home');
		$data['register'] = $this->url->link('seller/register');
		$data['text_new_seller'] = $this->language->get('text_new_seller');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_register_account'] = $this->language->get('text_register_account');
		$data['text_returning_seller'] = $this->language->get('text_returning_seller');
		$data['text_i_am_returning_seller'] = $this->language->get('text_i_am_returning_seller');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_login'] = $this->language->get('button_login');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['action'] = $this->url->link('seller/login', '', 'SSL');
		$data['register'] = $this->url->link('seller/register', '', 'SSL');
		$data['forgotten'] = $this->url->link('seller/forgotten', '', 'SSL');
		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];
			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}
		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/login.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/login.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/login.tpl', $data));
		}	
	}
}
?>