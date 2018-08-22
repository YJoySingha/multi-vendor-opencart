<?php
class ControllerSellerForgotten extends Controller {
	private $error = array();
	public function index() {
		if ($this->seller->isLogged()) {
			$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
		}
		$this->load->language('seller/forgotten');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/seller');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->language('mail/forgotten');
			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
			$this->model_seller_seller->editPassword($this->request->post['email'], $password);
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;
			$mail = new Mail($this->config->get('config_mail'));
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject($subject);
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			$this->session->data['success'] = $this->language->get('text_success');
			// Add to activity log
			$seller_info = $this->model_seller_seller->getSellerByEmail($this->request->post['email']);
			if ($seller_info) {
				$this->load->model('seller/activity');
				$activity_data = array(
					'seller_id' => $seller_info['seller_id'],
					'name'        => $seller_info['firstname'] . ' ' . $seller_info['lastname']
					);
				$this->model_seller_activity->addActivity('forgotten', $activity_data);
			}
			$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
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
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('seller/forgotten', '', 'SSL')
			);
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_your_email'] = $this->language->get('text_your_email');
		$data['text_email'] = $this->language->get('text_email');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['name'] = $this->config->get('config_name');	
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = HTTP_SERVER1. 'image/' . $this->config->get('config_logo');
		} else {			$data['logo'] = '';		}
		$this->load->language('common/header');
		$this->load->language('seller/login');
		$data['text_home'] = $this->language->get('text_home');
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
		$data['action'] = $this->url->link('seller/forgotten', '', 'SSL');
		$data['back'] = $this->url->link('seller/login', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/forgotten.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/forgotten.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/forgotten.tpl', $data));
		}
	}
	protected function validate() {
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_seller_seller->getTotalSellersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}
		return !$this->error;
	}
}