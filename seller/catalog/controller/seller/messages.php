<?php 
class ControllerSellerMessages extends Controller {
	
	private $error = array();
	
	public function index() {
    	$this->messageRedirect('');		
		$this->language->load('seller/messages');		
		$this->load->model('seller/messages');
    	$this->document->setTitle($this->language->get('heading_title'));
    	
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_enquiry'] = $this->language->get('text_enquiry');
		$data['text_product_name'] = $this->language->get('text_product');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['button_reply'] = $this->language->get('button_reply');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_reorder'] = $this->language->get('button_reorder');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['column_message_id'] = $this->language->get('column_message_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_message'] = $this->language->get('column_message');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_view'] = $this->language->get('button_view');
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$filter_data = array(
			'start' => ($page - 1) * 10,
			'limit' =>  20
			 );

		$data['messages'] = array();
		$message_total = $this->seller->getSellerTotalMessages($this->seller->getId(),$filter_data);
		//var_dump($message_total);
		$results  = $this->seller->getSellerMessages($this->seller->getId(),$filter_data);
		//var_dump($results);
		foreach ($results as $result) {
			$data['messages'][] = array(
				'message_id'   => $result['message_id'],
				'customer'	=> $result['customer'],
				'product_name'       => $result['product_name'],
				'message'     => $result['message'],
				'reply'     =>'',
				'href'      => $this->url->link('seller/messages/message_info','&message_id=' . $result['message_id'], 'SSL'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $message_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/order', 'page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($message_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($message_total - $this->config->get('config_limit_admin'))) ? $message_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $message_total, ceil($message_total / $this->config->get('config_limit_admin')));
		$data['continue'] = $this->url->link('seller/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/messages_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/message_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/messages_list.tpl', $data));
		}	
	}
	public function reply() {
		$this->messageRedirect('');
		$this->language->load('seller/messages');		
		$this->load->model('seller/messages');

		if (isset($this->request->post['seller_id'])) {
			$seller_id = $this->request->post['seller_id'];
		} else {
			$seller_id = 0;
		}
		if (isset($this->request->post['customer_email'])) {
			$customer_email = $this->request->post['customer_email'];
		} else {
			$customer_email = '';
		}
		if (isset($this->request->post['replyContent'])) {
			$replyContent = $this->request->post['replyContent'];
		} else {
			$replyContent = '';
		}
		if (isset($this->request->post['message_id'])) {
			$message_id = $this->request->post['message_id'];
		} else {
			$message_id = '';
		}

		$json = array();

		$reply_body = array(
			//'messages'   => $this->request->post['messageBody'],
			'seller'	 => $seller_id,
			'email'  	 => $customer_email, 
			'content'    => $replyContent, 
			'message_id' => $message_id,  
			);
		$save_message = $this->seller->replyCustomer($reply_body);
		if ($save_message === true) {
			//save to the database
			//send email later
			$json['success']  = $this->language->get('text_message_send');
			$json['message']  = $reply_body;
		} else {
			$json['error']  = $this->language->get('error_send_message');
		}
	
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    	
	}
	private function validate() {
		$this->messageRedirect('');
	}
	public function deleteMessage() {
		$this->messageRedirect('');
	}

	private function messageRedirect($endpoint) {
		
    	if (!$this->seller->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('seller/messages/'.trim($endpoint), '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    	}
	}
    
    public function message_info() {
    	$this->messageRedirect('');

		$this->language->load('seller/messages');		
		$this->load->model('seller/messages');
    	$this->document->setTitle($this->language->get('heading_title'));
    	$data['heading_title'] = $this->language->get('heading_title');
    	$data['text_customer'] = $this->language->get('text_customer');
    	$data['text_enquiry'] = $this->language->get('text_enquiry');
    	$data['text_product_name'] = $this->language->get('text_product');
    	$data['text_email'] = $this->language->get('text_email');
    	$data['text_phone'] = $this->language->get('text_phone');
    	$data['text_no_reply'] = $this->language->get('text_no_reply');
    	$data['button_continue'] = $this->language->get('button_continue');
    	//get message ID,sellerId
    	if (isset($this->request->get['message_id'])) {
    		$message_id = $this->request->get['message_id'];
    	}
    	else {
    		$message_id = 0;
    	}
    	$messageInfo = $this->seller->getSellerMessageInfo($message_id,$this->seller->getId());
    	$data['history'] = $this->seller->getMessageHistory($message_id,$this->seller->getId());
    	
    	$data['messageInfo'] = $messageInfo;

    	$data['continue'] = $this->url->link('seller/account', '', 'SSL');
    	$data['column_left'] = $this->load->controller('common/column_left');
    	$data['content_top'] = $this->load->controller('common/content_top');
    	$data['content_bottom'] = $this->load->controller('common/content_bottom');
    	$data['footer'] = $this->load->controller('common/footer');
    	$data['header'] = $this->load->controller('common/header');

    	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/message_info.tpl')) {
    		$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/message_info.tpl', $data));
    	} else {
    		$this->response->setOutput($this->load->view('default/template/seller/message_info.tpl', $data));
    	}
    }
}
?>