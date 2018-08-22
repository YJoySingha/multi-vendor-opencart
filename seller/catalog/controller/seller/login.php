<?php
class ControllerSellerLogin extends Controller {
	private $error = array();
	public function index() {
		$this->load->model('seller/seller');
		// Login override for admin users
		if (!empty($this->request->get['token'])) {
			$this->event->trigger('pre.seller.login');
			$this->seller->logout();
			$this->cart->clear();
			unset($this->session->data['wishlist']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			$seller_info = $this->model_seller_seller->getSellerByToken($this->request->get['token']);
			if ($seller_info && $this->seller->login($seller_info['email'], '', true)) {
				// Default Addresses
				$this->load->model('seller/address');
				if ($this->config->get('config_tax_seller') == 'payment') {
					$this->session->data['payment_address'] = $this->model_seller_address->getAddress($this->seller->getAddressId());
				}
				if ($this->config->get('config_tax_seller') == 'shipping') {
					$this->session->data['shipping_address'] = $this->model_seller_address->getAddress($this->seller->getAddressId());
				}
				$this->event->trigger('post.seller.login');
				$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
			}
		}
		if ($this->seller->isLogged()) {
			$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
		}
		$this->load->language('seller/login');
		$this->document->setTitle($this->language->get('heading_title'));
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			unset($this->session->data['guest']);
			// Default Shipping Address
			$this->load->model('seller/address');
			if ($this->config->get('config_tax_seller') == 'payment') {
				$this->session->data['payment_address'] = $this->model_seller_address->getAddress($this->seller->getAddressId());
			}
			if ($this->config->get('config_tax_seller') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_seller_address->getAddress($this->seller->getAddressId());
			}
			// Add to activity log
			$this->load->model('seller/activity');
			$activity_data = array(
				'seller_id' => $this->seller->getId(),
				'name'        => $this->seller->getFirstName() . ' ' . $this->seller->getLastName()
				);
			$this->model_seller_activity->addActivity('login', $activity_data);
			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
			}
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
			);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('seller/account', '', 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_login'),
			'href' => $this->url->link('seller/login', '', 'SSL')
			);
		$data['name'] = $this->config->get('config_name');	
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = HTTP_SERVER1. 'image/' . $this->config->get('config_logo');
		} else {			$data['logo'] = '';		}
		$this->load->language('common/header');
		$data['text_home'] = $this->language->get('text_home');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['home'] = HTTP_SERVER1;
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
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/login.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/login.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/login.tpl', $data));
		}
	}
	protected function validate() {
		if (!$this->seller->login($this->request->post['email'], $this->request->post['password'])) {
			$this->error['warning'] = $this->language->get('error_login');
		}
		$seller_info = $this->model_seller_seller->getSellerByEmail($this->request->post['email']);
		if ($seller_info && !$seller_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}