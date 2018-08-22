<?php 
class ControllerSellerPay extends Controller {
	private $error = array();
	public function index() {
	$this->language->load('seller/register');
	// if (!$this->seller->isLogged()) {
	// 		$this->session->data['redirect'] = $this->url->link('seller/pay', '', 'SSL');
	// 		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
	// }
	$data['lc'] = $this->config->get('config_admin_language');		
	$data['return'] = $this->url->link('seller/register', '', 'SSL');		
				$data['notify_url'] = $this->url->link('seller/pay/callback', '', 'SSL');		
				$data['cancelURL'] = $this->url->link('seller/register', '', 'SSL');
	  if (!$this->config->get('pp_standard_test')) {
		$data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
	  } else {
		$data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	  }	
	$data['paymentaction'] = 'Payment';			
	if (isset($this->request->get['cmt'])) {		
		$data['amount'] = $this->request->get['cmt'];		  
	} else {			$data['amount'] = '';		}			
	if (isset($this->request->get['customs'])) {			
		$data['customs'] = $this->request->get['customs'];	
	}
	else {
	 $data['customs'] = '';
	}					
	if (isset($this->request->get['gid'])) {		
		$data['gid'] = $this->request->get['gid'];		
	}
	else {
		$data['gid'] = '';
	}	
	     $query = $this->db->query("SELECT email FROM " . DB_PREFIX . "sellers WHERE 
		 seller_id = '" . (int)$this->request->get['customs'] . "'");
		$data['email'] =  $query->row['email'];
		$data['config_currency'] =  $this->config->get('config_currency');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/pay.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/pay.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/pay.tpl', $data));
		}
	}			
			public function callback() {	
			$seller_id	= 0;
		$plan_id	= 0;
		if (isset($this->request->post['custom'])) {
			$sid = explode("#",$this->request->post['custom']);
			if(count($sid) == 2){
				$seller_id	= $sid[0];
				$plan_id	= $sid[1];
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
			  } else {
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
		    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE 	commission_id = '".(int)$plan_id."'");
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
			$this->db->query("INSERT INTO " . DB_PREFIX . "upgraded_sellers SET 
			seller_id = '" . (int)$seller_id . "',
			old_commission_id = '" . (int)$oldgroup_id . "',
			commission_id = '" . (int)$plan_id . "', 
			amount = '" . (float)$query->row['amount'] . "', 
			upgrade_date = NOW(), expiry_date = '" . $expirydate. "', upgradedby = '".$seller_id."'");
			$this->db->query("UPDATE  " . DB_PREFIX . "sellers
			SET 
			commission_id = '" . (int)$plan_id. "',
			expiry_date = '".$expirydate."',
			payment_status = 1,
			approved='1',
			status='1'
			WHERE seller_id = '" . (int)$seller_id . "'");
			} 
			curl_close($curl);
		}		
        $this->response->redirect($this->url->link('seller/account', '', 'SSL'));
	}
}
?>