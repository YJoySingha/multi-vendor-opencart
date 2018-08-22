<?php 
class ControllerSellerPayAddress extends Controller {
	private $error = array();
  	public function index() {
    	if (!$this->seller->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('seller/pay_address', '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL')); 
    	}    
    	$this->language->load('seller/pay_address');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/pay_address');
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
       		$this->model_seller_pay_address->editAddress($this->request->post);
			$this->session->data['success'] = $this->language->get('text_update');
	  		$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
    	} 
		$this->getForm();
  	}
  	private function getForm() {
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
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/pay_address', '', 'SSL'),        	
        	'separator' => $this->language->get('text_separator')
      	);
    	$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit_pay_address'] = $this->language->get('text_edit_pay_address');
    	$data['text_yes'] = $this->language->get('text_yes');
    	$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
    	$data['entry_firstname'] = $this->language->get('entry_firstname');
    	$data['entry_lastname'] = $this->language->get('entry_lastname');
    	$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_company_id'] = $this->language->get('entry_company_id');
		$data['entry_tax_id'] = $this->language->get('entry_tax_id');		
    	$data['entry_address_1'] = $this->language->get('entry_address_1');
    	$data['entry_address_2'] = $this->language->get('entry_address_2');
    	$data['entry_postcode2'] = $this->language->get('entry_postcode');
    	$data['entry_city2'] = $this->language->get('entry_city');
    	$data['entry_country2'] = $this->language->get('entry_country');
    	$data['entry_zone2'] = $this->language->get('entry_zone');
    	$data['entry_paypalemail'] = $this->language->get('entry_paypalemail');
		$data['entry_bankname'] = $this->language->get('entry_bankname');
		$data['entry_accountnumber'] = $this->language->get('entry_accountnumber');
		$data['entry_accountname'] = $this->language->get('entry_accountname');
		$data['entry_branch'] = $this->language->get('entry_branch');
		$data['entry_ifsccode'] = $this->language->get('entry_ifsccode');
    	$data['button_continue'] = $this->language->get('button_continue');
    	$data['button_back'] = $this->language->get('button_back');
		$data['entry_cheque'] = $this->language->get('entry_cheque');
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
		if (isset($this->error['cheque'])) {
			$data['error_cheque'] = $this->error['cheque'];
		} else {
			$data['error_cheque'] = '';
		}
		if (isset($this->error['paypalemail'])) {
			$data['error_paypalemail'] = $this->error['paypalemail'];
		} else {
			$data['error_paypalemail'] = '';
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
    	$data['action'] = $this->url->link('seller/pay_address', '', 'SSL');
    	if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$pay_address_info = $this->model_seller_pay_address->getAddress();
		}
    	if (isset($this->request->post['address_2'])) {
      		$data['address_2'] = $this->request->post['address_2'];
    	} elseif (!empty($pay_address_info)) {
			$data['address_2'] = $pay_address_info['address_2'];
		} else {
      		$data['address_2'] = '';
    	}	
    	if (isset($this->request->post['postcode2'])) {
      		$data['postcode2'] = $this->request->post['postcode2'];
    	} elseif (!empty($pay_address_info)) {
			$data['postcode2'] = $pay_address_info['postcode2'];			
		} else {
      		$data['postcode2'] = '';
    	}
    	if (isset($this->request->post['city2'])) {
      		$data['city2'] = $this->request->post['city2'];
    	} elseif (!empty($pay_address_info)) {
			$data['city2'] = $pay_address_info['city2'];
		} else {
      		$data['city2'] = '';
    	}
    	if (isset($this->request->post['country_id2'])) {
      		$data['country_id2'] = $this->request->post['country_id2'];
    	}  elseif (!empty($pay_address_info)) {
      		$data['country_id2'] = $pay_address_info['country_id2'];			
    	} else {
      		$data['country_id2'] = $this->config->get('config_country_id');
    	}
    	if (isset($this->request->post['cheque'])) {
      		$data['cheque'] = $this->request->post['cheque'];
    	}  elseif (!empty($pay_address_info)) {
      		$data['cheque'] = $pay_address_info['payee_name'];
    	} else {
      		$data['cheque'] = '';
    	}
		if (isset($this->request->post['paypal_email'])) {
      		$data['paypal_email'] = $this->request->post['paypal_email'];
    	}  elseif (!empty($pay_address_info)) {
      		$data['paypal_email'] = $pay_address_info['paypal_email'];
    	} else {
      		$data['paypal_email'] = '';
    	}
		if (isset($this->request->post['paypalorcheque'])) {
      		$data['paypalorcheque'] = $this->request->post['paypalorcheque'];
    	}  elseif (!empty($pay_address_info)) {
      		$data['paypalorcheque'] = $pay_address_info['paypalorcheque'];
    	} else {
      		$data['paypalorcheque'] = 1;
    	}
		if (isset($this->request->post['bank_name'])) {
			$data['bank_name'] = $this->request->post['bank_name'];
		} elseif (!empty($pay_address_info)) {
      		$data['bank_name'] = $pay_address_info['bank_name'];
    	} else {
			$data['bank_name'] = '';
		}
		if (isset($this->request->post['account_number'])) {
			$data['account_number'] = $this->request->post['account_number'];
		} elseif (!empty($pay_address_info)) {
      		$data['account_number'] = $pay_address_info['account_number'];
    	} else {
			$data['account_number'] = '';
		}
		if (isset($this->request->post['account_name'])) {
			$data['account_name'] = $this->request->post['account_name'];
		} elseif (!empty($pay_address_info)) {
      		$data['account_name'] = $pay_address_info['account_name'];
    	} else {
			$data['account_name'] = '';
		}
		if (isset($this->request->post['branch'])) {
			$data['branch'] = $this->request->post['branch'];
		} elseif (!empty($pay_address_info)) {
      		$data['branch'] = $pay_address_info['branch'];
    	} else {
			$data['branch'] = '';
		}
		if (isset($this->request->post['ifsccode'])) {
			$data['ifsccode'] = $this->request->post['ifsccode'];
		} elseif (!empty($pay_address_info)) {
      		$data['ifsccode'] = $pay_address_info['ifsccode'];
    	} else {
			$data['ifsccode'] = '';
		}
		if (isset($this->request->post['address_id'])) {
      		$data['address_id'] = $this->request->post['paypalorcheque'];
    	}  elseif (!empty($pay_address_info)) {
      		$data['address_id'] = $pay_address_info['address_id'];
    	} else {
      		$data['address_id'] = 1;
    	}
		$this->load->model('localisation/country');
    	$data['countries'] = $this->model_localisation_country->getCountries();
    	$data['back'] = $this->url->link('seller/pay_address', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/address_form2.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/address_form2.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/address_form2.tpl', $data));
		}	
  	}
  	private function validateForm() {
    	if ($this->request->post['paypalorcheque'] == 1) {
			if ((utf8_strlen($this->request->post['paypal_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['paypal_email'])) 
			{
				$this->error['error_paypalemail'] = $this->language->get('error_paypalemail');
			}
		}elseif($this->request->post['paypalorcheque'] == 2) {
			if ((utf8_strlen($this->request->post['bank_name']) < 1) || (utf8_strlen($this->request->post['bank_name']) > 32)) {
				$this->error['bankname'] = $this->language->get('error_bankname');
			}
			if ((utf8_strlen($this->request->post['account_number']) < 3) || (utf8_strlen($this->request->post['account_number']) > 32)) {
				$this->error['accountnumber'] = $this->language->get('error_accountnumber');
			}
			if ((utf8_strlen($this->request->post['account_name']) < 1) || (utf8_strlen($this->request->post['account_name']) > 32)) {
				$this->error['accountname'] = $this->language->get('error_accountname');
			}
			if ((utf8_strlen($this->request->post['branch']) < 1) || (utf8_strlen($this->request->post['branch']) > 132)) {
				$this->error['branch'] = $this->language->get('error_branch');
			}
			if ((utf8_strlen($this->request->post['ifsccode']) < 3) || (utf8_strlen($this->request->post['ifsccode']) > 32)) {
				$this->error['ifsccode'] = $this->language->get('error_ifsccode');
			}
		}else{
			if ((utf8_strlen($this->request->post['cheque']) < 3) || (utf8_strlen($this->request->post['cheque']) > 128)) {
				$this->error['cheque'] = $this->language->get('error_cheque');
			}
		}
    	if (!$this->error) {
      		return true;
		} else {
      		return false;
    	}
  	}
  	private function validateDelete() {
    	if ($this->model_seller_pay_address->getTotalAddresses() == 1) {
      		$this->error['warning'] = $this->language->get('error_delete');
    	}
    	if ($this->seller->getAddressId() == $this->request->get['pay_address_id']) {
      		$this->error['warning'] = $this->language->get('error_default');
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
				'pay_address_format'    => $country_info['pay_address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		$this->response->setOutput(json_encode($json));
	}
}
?>