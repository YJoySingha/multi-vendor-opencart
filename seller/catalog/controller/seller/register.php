<?php
class ControllerSellerRegister extends Controller {
	private $error = array();
  	public function index() {
		if ($this->seller->isLogged()) {
	  		$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
    	}
    	$this->language->load('seller/register');
		$this->document->setTitle($this->language->get('heading_title'));
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		$this->load->model('seller/seller');
		$data['name'] = $this->config->get('config_name');
		$data['home'] = HTTP_SERVER1;
		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
				$data['logo'] = HTTP_SERVER1. 'image/' . $this->config->get('config_logo');
		} else {			$data['logo'] = '';		}
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$sellid =  $this->model_seller_seller->addSeller($this->request->post);
			unset($this->session->data['guest']);
			// Default Shipping Address
			if ($this->config->get('config_tax_seller') == 'shipping') {
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
			}
			// Default Payment Address
			if ($this->config->get('config_tax_seller') == 'payment') {
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
			}
			if(!empty($this->request->post['new_commission_id']) && !empty($sellid)){
				$customs = $sellid;
               $query = $this->db->query("SELECT amount FROM " . DB_PREFIX . "commission 
				WHERE commission_id = '" . (int)$this->request->post['new_commission_id'] . "'");
				if($query->row['amount'] > 0){
					$cmt = $query->row['amount'];
				$this->response->redirect($this->url->link('seller/pay&cmt=' . $cmt.'&gid=' . $this->request->post['new_commission_id']
				 .'&customs=' . $customs));
				}else{
				$this->response->redirect($this->url->link('seller/success'));
				}
			}else{
			$this->response->redirect($this->url->link('seller/success'));
			}
    	}
    	$data['heading_title'] = $this->language->get('heading_title');
		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('seller/login', '', 'SSL'));
		$data['text_your_details'] = $this->language->get('text_your_details');
    	$data['text_your_address'] = $this->language->get('text_your_address');
    	$data['text_your_password'] = $this->language->get('text_your_password');
		$data['text_paid'] = $this->language->get('text_paid');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['entry_username'] = $this->language->get('entry_username');
    	$data['entry_firstname'] = $this->language->get('entry_firstname');
    	$data['entry_lastname'] = $this->language->get('entry_lastname');
    	$data['entry_email'] = $this->language->get('entry_email');
    	$data['entry_telephone'] = $this->language->get('entry_telephone');
    	$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_seller_group'] = $this->language->get('entry_seller_group');
		$data['entry_company_id'] = $this->language->get('entry_company_id');
		$data['entry_tax_id'] = $this->language->get('entry_tax_id');
    	$data['entry_address_1'] = $this->language->get('entry_address_1');
    	$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_postcode2'] = $this->language->get('entry_postcode2');
    	$data['entry_cheque'] = $this->language->get('entry_cheque');
    	$data['entry_country2'] = $this->language->get('entry_country2');
		$data['entry_zone2'] = $this->language->get('entry_zone2');
    	$data['entry_postcode'] = $this->language->get('entry_postcode');
    	$data['entry_city'] = $this->language->get('entry_city');
    	$data['entry_country'] = $this->language->get('entry_country');
    	$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$data['entry_password'] = $this->language->get('entry_password');
    	$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['entry_paypalorcheque'] = $this->language->get('entry_paypalorcheque');
		$data['entry_captcha'] = $this->language->get('entry_captcha');
		$data['entry_paypalemail'] = $this->language->get('entry_paypalemail');
		$data['entry_bankname'] = $this->language->get('entry_bankname');
		$data['entry_accountnumber'] = $this->language->get('entry_accountnumber');
		$data['entry_accountname'] = $this->language->get('entry_accountname');
		$data['entry_branch'] = $this->language->get('entry_branch');
		$data['entry_ifsccode'] = $this->language->get('entry_ifsccode');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_desc'] = $this->language->get('text_desc');
		$data['entry_aboutus'] = $this->language->get('entry_aboutus');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}
		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}
		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}
		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}
		if (isset($this->error['error_paypalemail'])) {
			$data['error_paypalemail'] = $this->error['error_paypalemail'];
		} else {
			$data['error_paypalemail'] = '';
		}
		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}
		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}
 		if (isset($this->error['cheque'])) {
			$data['error_cheque'] = $this->error['cheque'];
		} else {
			$data['error_cheque'] = '';
		}
  		if (isset($this->error['company_id'])) {
			$data['error_company_id'] = $this->error['company_id'];
		} else {
			$data['error_company_id'] = '';
		}
  		if (isset($this->error['tax_id'])) {
			$data['error_tax_id'] = $this->error['tax_id'];
		} else {
			$data['error_tax_id'] = '';
		}
  		if (isset($this->error['address_1'])) {
			$data['error_address_1'] = $this->error['address_1'];
		} else {
			$data['error_address_1'] = '';
		}
		if (isset($this->error['address_2'])) {
			$data['error_address_2'] = $this->error['address_2'];
		} else {
			$data['error_address_2'] = '';
		}
		if (isset($this->error['city2'])) {
			$data['error_city2'] = $this->error['city2'];
		} else {
			$data['error_city2'] = '';
		}
		if (isset($this->error['postcode2'])) {
			$data['error_postcode2'] = $this->error['postcode2'];
		} else {
			$data['error_postcode2'] = '';
		}
		if (isset($this->error['country2'])) {
			$data['error_country2'] = $this->error['country2'];
		} else {
			$data['error_country2'] = '';
		}
		if (isset($this->error['zone2'])) {
			$data['error_zone2'] = $this->error['zone2'];
		} else {
			$data['error_zone2'] = '';
		}
		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}
		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}
		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}
		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}
		if (isset($this->error['bankname'])) {
			$data['error_bankname'] = $this->error['bankname'];
		} else {
			$data['error_bankname'] = '';
		}
		if (isset($this->error['accountnumber'])) {
			$data['error_accountnumber'] = $this->error['accountnumber'];
		} else {
			$data['error_accountnumber'] = '';
		}
		if (isset($this->error['accountname'])) {
			$data['error_accountname'] = $this->error['accountname'];
		} else {
			$data['error_accountname'] = '';
		}
		if (isset($this->error['branch'])) {
			$data['error_branch'] = $this->error['branch'];
		} else {
			$data['error_branch'] = '';
		}
		if (isset($this->error['ifsccode'])) {
			$data['error_ifsccode'] = $this->error['ifsccode'];
		} else {
			$data['error_ifsccode'] = '';
		}
		if (isset($this->error['captcha'])) {
			$data['error_captcha'] = $this->error['captcha'];
		} else {
			$data['error_captcha'] = '';
		}
    	$data['action'] = $this->url->link('seller/register', '', 'SSL');
		if (isset($this->request->post['username'])) {
    		$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}
		if (isset($this->request->post['firstname'])) {
    		$data['firstname'] = $this->request->post['firstname'];
		} else {
			$data['firstname'] = '';
		}
		if (isset($this->request->post['lastname'])) {
    		$data['lastname'] = $this->request->post['lastname'];
		} else {
			$data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
    		$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}
		if (isset($this->request->post['paypal_email'])) {
			$data['paypal_email'] = $this->request->post['paypal_email'];
		} else {
			$data['paypal_email'] = '';
		}
		if (isset($this->request->post['paypalorcheque'])) {
    		$data['paypalorcheque'] = $this->request->post['paypalorcheque'];
		} else {
			$data['paypalorcheque'] = 1;
		}
		if (isset($this->request->post['bank_name'])) {
			$data['bank_name'] = $this->request->post['bank_name'];
		} else {
			$data['bank_name'] = '';
		}
		if (isset($this->request->post['account_number'])) {
			$data['account_number'] = $this->request->post['account_number'];
		} else {
			$data['account_number'] = '';
		}
		if (isset($this->request->post['account_name'])) {
			$data['account_name'] = $this->request->post['account_name'];
		} else {
			$data['account_name'] = '';
		}
		if (isset($this->request->post['branch'])) {
			$data['branch'] = $this->request->post['branch'];
		} else {
			$data['branch'] = '';
		}
		if (isset($this->request->post['ifsccode'])) {
			$data['ifsccode'] = $this->request->post['ifsccode'];
		} else {
			$data['ifsccode'] = '';
		}
		if (isset($this->request->post['telephone'])) {
    		$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}
		if (isset($this->request->post['fax'])) {
    		$data['fax'] = $this->request->post['fax'];
		} else {
			$data['fax'] = '';
		}
		if (isset($this->request->post['company'])) {
    		$data['company'] = $this->request->post['company'];
		} else {
			$data['company'] = '';
		}
		if (isset($this->request->post['aboutus'])) {
    		$data['aboutus'] = $this->request->post['aboutus'];
		} else {
			$data['aboutus'] = '';
		}
		// Company ID
		if (isset($this->request->post['company_id'])) {
    		$data['company_id'] = $this->request->post['company_id'];
		} else {
			$data['company_id'] = '';
		}
		// Tax ID
		if (isset($this->request->post['tax_id'])) {
    		$data['tax_id'] = $this->request->post['tax_id'];
		} else {
			$data['tax_id'] = '';
		}
		if (isset($this->request->post['address_1'])) {
    		$data['address_1'] = $this->request->post['address_1'];
		} else {
			$data['address_1'] = '';
		}
		if (isset($this->request->post['postcode'])) {
    		$data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['shipping_postcode'])) {
			$data['postcode'] = $this->session->data['shipping_postcode'];
		} else {
			$data['postcode'] = '';
		}
		if (isset($this->request->post['city'])) {
    		$data['city'] = $this->request->post['city'];
		} else {
			$data['city'] = '';
		}
    	if (isset($this->request->post['country_id'])) {
      		$data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['shipping_country_id'])) {
			$data['country_id'] = $this->session->data['shipping_country_id'];
		} else {
      		$data['country_id'] = $this->config->get('config_country_id');
    	}
		if (isset($this->request->post['cheque'])) {
    		$data['cheque'] = $this->request->post['cheque'];
		} else {
			$data['cheque'] = '';
		}
    	if (isset($this->request->post['zone_id'])) {
      		$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($this->session->data['shipping_zone_id'])) {
			$data['zone_id'] = $this->session->data['shipping_zone_id'];
		} else {
      		$data['zone_id'] = '';
    	}
		$this->load->model('localisation/country');
    	$data['countries'] = $this->model_localisation_country->getCountries();
		if (isset($this->request->post['password'])) {
    		$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}
		if (isset($this->request->post['confirm'])) {
    		$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}
		if ($this->config->get('config_selleraccount_id')) {
			$this->load->model('catalog/information');
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_selleraccount_id'));
			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_selleraccount_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}
		if (isset($this->request->post['captcha'])) {
			$data['captcha'] = $this->request->post['captcha'];
		} else {
			$data['captcha'] = '';
		}
		if (isset($this->request->post['agree'])) {
      		$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = false;
		}
		$data['allplan'] = $this->url->link('seller/plan1', '', 'SSL');
			  if (isset($this->request->post['new_commission_id'])) {
			$data['new_commission_id'] = $this->request->post['new_commission_id'];
		} else {
			$data['new_commission_id'] = $this->config->get('config_sellercommission_id');
		}
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission ORDER BY commission_id");
			$data['ncommissions'] = array();
			$ncommissions = $query->rows;
			foreach ($ncommissions as $result) {
				   $data['ncommissions'][] = array(
					'commission_id' 	=> $result['commission_id'],
					'commission_name' 	=> $result['commission_name'],
					'amount' 	=>$result['amount'],
					'commission'    	=> $result['commission'],
					'per'    	=> $result['per'],
					'duration_id'    	=> $result['duration_id'],
					'product_limit'    	=> $result['product_limit'],
					'amt'    	=> $result['amount'],
					'amount1' =>  $this->currency->format($result['amount'])
				);
			}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] =
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/register.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/register.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/register.tpl', $data));
		}
  	}
	public function availability() {
		$json = array();
		$this->language->load('seller/register');
		$this->load->model('seller/seller');
		if ((utf8_strlen($this->request->post['username']) < 4) || (utf8_strlen($this->request->post['username']) > 32)){
      		$json['error'] = $this->language->get('error_username');
    	} elseif(!preg_match('/^[a-zA-Z0-9!@$^]*$/',$this->request->post['username'])) {
			$json['error'] = $this->language->get('error_invalidusername');
		} elseif($this->model_seller_seller->getTotalSellersByUsername($this->request->post['username'])) {
      		$json['error'] = $this->language->get('error_userexists');
    	} else {
			$json['success'] = $this->language->get('error_available');
		}
		$this->response->setOutput(json_encode($json));
	}
  	private function validate() {
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}
    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}
    	if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		if ((utf8_strlen($this->request->post['username']) < 4) || (utf8_strlen($this->request->post['username']) > 32)) {
      		$this->error['username'] = $this->language->get('error_username');
    	}
    	if ($this->model_seller_seller->getTotalSellersByEmail($this->request->post['email'])) {
      		$this->error['warning'] = $this->language->get('error_exists');
    	}
		if ($this->model_seller_seller->getTotalSellersByUsername($this->request->post['username'])) {
      		$this->error['warning'] = $this->language->get('error_userexists');
    	}
    	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
    	if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
      		$this->error['password'] = $this->language->get('error_password');
    	}
		if ($this->config->get('config_selleraccount_id')) {
			$this->load->model('catalog/information');
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_selleraccount_id'));
			if ($information_info && !isset($this->request->post['agree'])) {
      			$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
    	if (!$this->error) {
      		return true;
    	} else {
      		return false;
    	}
  	}
	public function country() {
		$json = array();
		$this->load->model('localisation/country');
    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		if ($country_info) {
			$this->load->model('localisation/zone');
			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}
		$this->response->setOutput(json_encode($json));
	}
	public function captcha() {
		$this->load->library('captcha');
		$captcha = new Captcha();
		$this->session->data['captcha'] = $captcha->getCode();
		$captcha->showImage();
	}
}
?>