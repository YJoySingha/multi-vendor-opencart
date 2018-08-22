<?php
class ControllerSellerMembership extends Controller {
	private $error = array();
	public function index() {
		if (!$this->seller->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('seller/membership', '', 'SSL');
			$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
		}
		$this->language->load('seller/plan');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/seller');
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
			'text'      => $this->language->get('text_plan'),
			'href'      => $this->url->link('seller/membership', '', 'SSL'),       	
			'separator' => $this->language->get('text_separator')
			);
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_your_imagegallery'] = $this->language->get('text_your_imagegallery');
		$data['text_plan'] = $this->language->get('text_plan');
		$data['column_plan'] = $this->language->get('column_plan');
		$data['column_duration'] = $this->language->get('column_duration');
		$data['column_charges'] = $this->language->get('column_charges');
		$data['column_upgrade'] = $this->language->get('column_upgrade');
		$data['column_about'] = $this->language->get('column_about');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_video_url'] = $this->language->get('entry_video_url');
		$data['button_add_image'] = $this->language->get('button_add_image');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['testmode'] = $this->config->get('pp_standard_test');
		if (!$this->config->get('pp_standard_test')) {
			$data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
		} else {
			$data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		$data['business'] = $this->config->get('pp_standard_email');
		$custquery = $this->db->query("select commission_id from " . DB_PREFIX . "sellers where seller_id = '".(int)$this->seller->getId()."'");		$data['currentamount'] =0;				if($custquery->row){				$plan_id = $custquery->row['commission_id'];				  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE 	commission_id = '".(int)$plan_id."'");						$data['currentamount'] = $query->row['amount'];		  		}
		$results = $this->model_seller_seller->getcommissions();	   	   	   
		$data['getmemberships'] =array();
		foreach ($results as $result) {				
			$data['getmemberships'][] = array(
				'commission_id' 	=> $result['commission_id'],
				'commission_name' 	=> $result['commission_name'],
				'amount' 	=> $this->currency->format($result['amount']),				
				'commission'    	=> $result['commission'],								'product_limit'    	=> $result['product_limit'],
				'per'    	=> $result['per'],
				'duration_id'    	=> $result['duration_id'],
				'amt'    	=> $result['amount']
				);
		}
		$seller_info = $this->model_seller_seller->getSeller($this->seller->getId());
		$data['durations']['d'] = 'Day(s)'; 
		$data['durations']['w'] = 'Week(s)'; 
		$data['durations']['m'] = 'Month(s)'; 
		$data['durations']['y'] = 'Year(s)';
		$data['durations']['l'] = 'Lifetime';
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['currency_code'] = $this->config->get('config_currency');
		$data['lc'] = $this->session->data['language'];
		$data['custom'] = $this->seller->getId();
		$data['email'] = $seller_info['email'];
		$data['return'] = $this->url->link('seller/membership', 'seller_id=' . $this->seller->getId(), 'SSL');
		$data['notify_url'] = $this->url->link('seller/membership/callback', 'seller_id=' . $this->seller->getId(), 'SSL');
		$data['cancelURL'] = $this->url->link('seller/membership', 'seller_id=' . $this->seller->getId(), 'SSL');
		$data['paymentaction'] = 'Payment';		
		if (isset($this->request->post['commission_id'])) {
			$data['commission_id'] = $this->request->post['commission_id'];
		}elseif($seller_info) {
			$data['commission_id'] =$seller_info['commission_id'];
		} else {
			$data['commission_id'] = '';
		}
		$data['back'] = $this->url->link('seller/account', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/membership.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/membership.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/membership.tpl', $data));
		}
	}
	public function updatesellers(){
		$this->load->model('seller/seller');
		$this->model_seller_seller->updateexpirycusts();
	}
	public function callback() {
		$seller_id	= 0;
		$membership_id	= 0;
		if (isset($this->request->post['custom'])) {
			$sid = explode("#",$this->request->post['custom']);
			if(count($sid) == 2) {	
				$seller_id	= $sid[0];	
				$membership_id	= $sid[1];
			}
		} 
		$this->load->model('seller/seller');
		if($seller_id>0) {
			$request = 'cmd=_notify-validate';
			foreach ($this->request->post as $key => $value) {
				$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
			}

			if (!$this->config->get('pp_standard_test')) {
				$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
			}
			else {
				$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}										
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$response = curl_exec($curl);
			if (!$response) {
				$this->log->write('PP_STANDARD :: CURL failed ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
			}
			if ($this->config->get('pp_standard_debug')) {
				$this->log->write('PP_STANDARD :: IPN REQUEST: ' . $request);
				$this->log->write('PP_STANDARD :: IPN RESPONSE: ' . $response);
			}
			if (isset($this->request->post['payment_status'])) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE 	commission_id = '".(int)$membership_id."'");
				$durationid = $query->row['duration_id'];
				$days = $query->row['per'];	
				$Date = date("Y-m-d H:i:s");
				$expirydate = "0000-00-00 00:00:00";
				if($durationid == 'd'){	
					$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days days"));
				}
				if($durationid == 'm'){	
					$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days months"));
				}
				if($durationid == 'y'){	
					$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days years"));
				}
				if($durationid == 'w'){	
					$days = $days*7;
					$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days days"));
				}
				$oldgroup_id = 0;
				$custquery = $this->db->query("select commission_id from " . DB_PREFIX . "sellers where seller_id = '".(int)$seller_id."'");
				if($custquery->row){
					$oldgroup_id = $custquery->row['commission_id'];
				}

				$this->db->query("INSERT INTO " . DB_PREFIX . "upgraded_sellers SET seller_id = '" . (int)$seller_id . "',	old_commission_id = '" . (int)$oldgroup_id . "',commission_id = '" . (int)$membership_id . "', amount = '" . (float)$query->row['amount'] . "',upgrade_date = NOW(), expiry_date = '" . $expirydate. "', upgradedby = '".$seller_id."'");
				$this->db->query("UPDATE  " . DB_PREFIX . "sellers	SET commission_id = '" . (int)$membership_id. "',expiry_date = '".$expirydate."',payment_status = 1,approved='1',status='1'	WHERE seller_id = '" . (int)$seller_id . "'");
			} 

			curl_close($curl);
		}

		$this->response->redirect($this->url->link('seller/account', '', 'SSL'));			
	}
	public function update(){
		$json = array();
		$this->load->model('seller/seller');
		$seller_info = $this->model_seller_seller->getSeller($this->seller->getId());
		if ($seller_info) {
			$this->model_seller_seller->updateplanseller($this->request->post,$this->seller->getId());
			$json['success'] = "Your membership is successfully updated";
		} else {
			$json['error'] = "Your membership is not updated";
		}
		$this->response->setOutput(json_encode($json));
	}
}
?>