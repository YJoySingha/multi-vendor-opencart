<?php
class ControllerAccountReview extends Controller {
	private $error = array();
 
	public function index() {
		$this->language->load('account/review');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('account/review');

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_account_review->addReview($this->request->post);		
			$this->session->data['success'] = $this->language->get('text_success');						
			$this->response->redirect($this->url->link('account/order'));
		}
		if(isset($this->request->get['seller_id']) && isset($this->request->get['order_id'])) {
			if($this->model_account_review->getReview($this->request->get['seller_id'],$this->request->get['order_id'])){		
				$this->session->data['warning'] = 'You already submitted review for this order';						
				$this->response->redirect($this->url->link('account/order'));
			}
			if(!$this->model_account_review->getOrder($this->request->get['seller_id'],$this->request->get['order_id'])){		
				$this->session->data['warning'] = 'This order not related to you';						
				$this->response->redirect($this->url->link('account/order'));
			}
		}

		$this->getForm();
	}

	
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_select'] = $this->language->get('text_select');

		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_author'] = $this->language->get('entry_author');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_bad'] = $this->language->get('entry_bad');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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
		
		
		if (isset($this->error['order'])) {
			$data['error_order'] = $this->error['order'];
		} else {
			$data['error_order'] = '';
		}

		if (isset($this->error['seller'])) {
			$data['error_seller'] = $this->error['seller'];
		} else {
			$data['error_seller'] = '';
		}
		
 		if (isset($this->error['author'])) {
			$data['error_author'] = $this->error['author'];
		} else {
			$data['error_author'] = '';
		}
		
 		if (isset($this->error['text'])) {
			$data['error_text'] = $this->error['text'];
		} else {
			$data['error_text'] = '';
		}
		
 		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}

		$url = '';
		
		if (isset($this->request->get['seller_id'])) {
			$url .= '&seller_id=' . $this->request->get['seller_id'];
		}

		if (isset($this->request->get['order_id'])) {
			$url .= '&order_id=' . $this->request->get['order_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
				
   		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/review'),
      		'separator' => ' :: '
   		);
										
		if (!isset($this->request->get['review_id'])) { 
			$data['action'] = $this->url->link('account/review',$url);
		} 

		$data['cancel'] = $this->url->link('account/order');

		if (isset($this->request->get['review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$review_info = $this->model_account_review->getReview($this->request->get['review_id']);
		}
		
		
	
		if (isset($this->request->post['seller_id'])) {
			$data['seller_id'] = $this->request->post['seller_id'];
		}elseif (isset($this->request->get['seller_id'])) {
			$data['seller_id'] = $this->request->get['seller_id'];
		}else {
			$data['seller_id'] = '';
		}

		if (isset($this->request->post['order_id'])) {
			$data['order_id'] = $this->request->post['order_id'];
		}elseif (isset($this->request->get['order_id'])) {
			$data['order_id'] = $this->request->get['order_id'];
		}else {
			$data['order_id'] = '';
		}
				
		if (isset($this->request->post['author'])) {
			$data['author'] = $this->request->post['author'];
		}else {
			$data['author'] =$this->customer->getFirstName();
		}

		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		}else {
			$data['text'] = '';
		}

		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		}else {
			$data['rating'] = '';
		}
		
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		}else {
			$data['status'] = '';
		}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/review_form', $data));


		
	}
	
	protected function validateForm() {
		
		if (!$this->request->post['seller_id']) {
			$this->error['warning'] = $this->language->get('error_seller');
		}

		if (!$this->request->post['order_id']) {
			$this->error['warning'] = $this->language->get('error_order');
		}
		
		if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (utf8_strlen($this->request->post['text']) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}
				
		if (!isset($this->request->post['rating'])) {
			$this->error['rating'] = $this->language->get('error_rating');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

}
?>