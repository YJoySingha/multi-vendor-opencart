<?php 
class ControllerSellerAddress extends Controller {

	private $error = array();

  	public function index() {
    	$this->addressRedirect(''); 
    	$this->language->load('seller/address');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/address');
		$this->getList();
  	}
	private function addressRedirect($endpoint) {
		
    	if (!$this->seller->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('seller/address/'.trim($endpoint), '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    	}
	}
  	public function insert() {
    	$this->addressRedirect('insert'); 
    	$this->language->load('seller/address');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/address');
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_seller_address->addAddress($this->request->post);
      		$this->session->data['success'] = $this->language->get('text_insert');
	  		$this->response->redirect($this->url->link('seller/address', '', 'SSL'));
    	} 
		$this->getForm();
  	}
  	public function update() {
    	$this->addressRedirect('');  
    	$this->language->load('seller/address');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/address');
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
       		$this->model_seller_address->editAddress($this->request->get['address_id'], $this->request->post);
			// Default Shipping Address
			if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];
				unset($this->session->data['shipping_method']);	
				unset($this->session->data['shipping_methods']);
			}
			// Default Payment Address
			if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}
			$this->session->data['success'] = $this->language->get('text_update');
	  		$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
    	} 
		$this->getForm();
  	}
  	public function delete() {
    	$this->addressRedirect('');  
    	$this->language->load('seller/address');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/address');
    	if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->model_seller_address->deleteAddress($this->request->get['address_id']);	
			// Default Shipping Address
			if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
				unset($this->session->data['shipping_address_id']);
				unset($this->session->data['shipping_country_id']);
				unset($this->session->data['shipping_zone_id']);
				unset($this->session->data['shipping_postcode']);				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}
			// Default Payment Address
			if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
				unset($this->session->data['payment_address_id']);
				unset($this->session->data['payment_country_id']);
				unset($this->session->data['payment_zone_id']);				
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
			}
			$this->session->data['success'] = $this->language->get('text_delete');
	  		$this->response->redirect($this->url->link('seller/address', '', 'SSL'));
    	}
		$this->getList();	
  	}
  	private function getList() {
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
			'href'      => $this->url->link('seller/address', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
    	$data['heading_title'] = $this->language->get('heading_title');
    	$data['text_address_book'] = $this->language->get('text_address_book');
    	$data['button_new_address'] = $this->language->get('button_new_address');
    	$data['button_add'] = $this->language->get('button_add');
    	$data['button_edit'] = $this->language->get('button_edit');
    	$data['button_delete'] = $this->language->get('button_delete');
		$data['button_back'] = $this->language->get('button_back');
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
    	$data['addresses'] = array();
		$results = $this->model_seller_address->getAddresses();
    	foreach ($results as $result) {
			if ($result['address_format']) {
      			$format = $result['address_format'];
    		} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}
    		$find = array(
	  			'{firstname}',
	  			'{lastname}',
	  			'{company}',
      			'{address_1}',
      			'{address_2}',
     			'{city}',
      			'{postcode}',
      			'{zone}',
				'{zone_code}',
      			'{country}'
			);
			$replace = array(
	  			'firstname' => $result['firstname'],
	  			'lastname'  => $result['lastname'],
	  			'company'   => $result['company'],
      			'address_1' => $result['address_1'],
      			'address_2' => $result['address_2'],
      			'city'      => $result['city'],
      			'postcode'  => $result['postcode'],
      			'zone'      => $result['zone'],
				'zone_code' => $result['zone_code'],
      			'country'   => $result['country']  
			);
      		$data['addresses'][] = array(
        		'address_id' => $result['address_id'],
        		'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
        		'update'     => $this->url->link('seller/address/update', 'address_id=' . $result['address_id'], 'SSL'),
				'delete'     => $this->url->link('seller/address/delete', 'address_id=' . $result['address_id'], 'SSL')
      		);
    	}
    	$data['insert'] = $this->url->link('seller/address/insert', '', 'SSL');
		$data['back'] = $this->url->link('seller/account', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left'); 
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/address_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/address_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/address_list.tpl', $data));
		}
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
			'href'      => $this->url->link('seller/address', '', 'SSL'),        	
        	'separator' => $this->language->get('text_separator')
      	);
		if (!isset($this->request->get['address_id'])) {
      		$data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_edit_address'),
				'href'      => $this->url->link('seller/address/insert', '', 'SSL'),       		
        		'separator' => $this->language->get('text_separator')
      		);
		} else {
      		$data['breadcrumbs'][] = array(
        		'text'      => $this->language->get('text_edit_address'),
				'href'      => $this->url->link('seller/address/update', 'address_id=' . $this->request->get['address_id'], 'SSL'),       		
        		'separator' => $this->language->get('text_separator')
      		);
		}
    	$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit_address'] = $this->language->get('text_edit_address');
		$data['text_add_address']= $this->language->get('text_add_address');
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
    	$data['entry_postcode'] = $this->language->get('entry_postcode');
    	$data['entry_city'] = $this->language->get('entry_city');
    	$data['entry_country'] = $this->language->get('entry_country');
    	$data['entry_zone'] = $this->language->get('entry_zone');
    	$data['entry_default'] = $this->language->get('entry_default');
    	$data['button_continue'] = $this->language->get('button_continue');
    	$data['button_back'] = $this->language->get('button_back');
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
		if (!isset($this->request->get['address_id'])) {
    		$data['action'] = $this->url->link('seller/address/insert', '', 'SSL');
		} else {
    		$data['action'] = $this->url->link('seller/address/update', 'address_id=' . $this->request->get['address_id'], 'SSL');
		}
    	if (isset($this->request->get['address_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$address_info = $this->model_seller_address->getAddress($this->request->get['address_id']);
		}
    	if (isset($this->request->post['firstname'])) {
      		$data['firstname'] = $this->request->post['firstname'];
    	} elseif (!empty($address_info)) {
      		$data['firstname'] = $address_info['firstname'];
    	} else {
			$data['firstname'] = '';
		}
    	if (isset($this->request->post['lastname'])) {
      		$data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($address_info)) {
      		$data['lastname'] = $address_info['lastname'];
    	} else {
			$data['lastname'] = '';
		}
    	if (isset($this->request->post['company'])) {
      		$data['company'] = $this->request->post['company'];
    	} elseif (!empty($address_info)) {
			$data['company'] = $address_info['company'];
		} else {
      		$data['company'] = '';
    	}
		if (isset($this->request->post['company_id'])) {
    		$data['company_id'] = $this->request->post['company_id'];
    	} elseif (!empty($address_info)) {
			$data['company_id'] = $address_info['company_id'];			
		} else {
			$data['company_id'] = '';
		}
		if (isset($this->request->post['tax_id'])) {
    		$data['tax_id'] = $this->request->post['tax_id'];
    	} elseif (!empty($address_info)) {
			$data['tax_id'] = $address_info['tax_id'];			
		} else {
			$data['tax_id'] = '';
		}
		$this->load->model('seller/seller_group');
		$seller_group_info = $this->model_seller_seller_group->getSellerGroup($this->seller->getSellerGroupId());
		if ($seller_group_info) {
			$data['company_id_display'] = $seller_group_info['company_id_display'];
		} else {
			$data['company_id_display'] = '';
		}
		if ($seller_group_info) {
			$data['tax_id_display'] = $seller_group_info['tax_id_display'];
		} else {
			$data['tax_id_display'] = '';
		}
    	if (isset($this->request->post['address_1'])) {
      		$data['address_1'] = $this->request->post['address_1'];
    	} elseif (!empty($address_info)) {
			$data['address_1'] = $address_info['address_1'];
		} else {
      		$data['address_1'] = '';
    	}
    	if (isset($this->request->post['address_2'])) {
      		$data['address_2'] = $this->request->post['address_2'];
    	} elseif (!empty($address_info)) {
			$data['address_2'] = $address_info['address_2'];
		} else {
      		$data['address_2'] = '';
    	}	
    	if (isset($this->request->post['postcode'])) {
      		$data['postcode'] = $this->request->post['postcode'];
    	} elseif (!empty($address_info)) {
			$data['postcode'] = $address_info['postcode'];			
		} else {
      		$data['postcode'] = '';
    	}
    	if (isset($this->request->post['city'])) {
      		$data['city'] = $this->request->post['city'];
    	} elseif (!empty($address_info)) {
			$data['city'] = $address_info['city'];
		} else {
      		$data['city'] = '';
    	}
    	if (isset($this->request->post['country_id'])) {
      		$data['country_id'] = $this->request->post['country_id'];
    	}  elseif (!empty($address_info)) {
      		$data['country_id'] = $address_info['country_id'];			
    	} else {
      		$data['country_id'] = $this->config->get('config_country_id');
    	}
    	if (isset($this->request->post['zone_id'])) {
      		$data['zone_id'] = $this->request->post['zone_id'];
    	}  elseif (!empty($address_info)) {
      		$data['zone_id'] = $address_info['zone_id'];
    	} else {
      		$data['zone_id'] = '';
    	}
		$this->load->model('localisation/country');
    	$data['countries'] = $this->model_localisation_country->getCountries();
    	if (isset($this->request->post['default'])) {
      		$data['default'] = $this->request->post['default'];
    	} elseif (isset($this->request->get['address_id'])) {
      		$data['default'] = $this->seller->getAddressId() == $this->request->get['address_id'];
    	} else {
			$data['default'] = false;
		}
    	$data['back'] = $this->url->link('seller/address', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/address_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/address_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/address_form.tpl', $data));
		}	
  	}
  	private function validateForm() {
    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}
    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}
    	if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}
    	if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		$this->load->model('localisation/country');
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		if ($country_info) {
			if ($country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
				$this->error['postcode'] = $this->language->get('error_postcode');
			}
			// VAT Validation
			$this->load->helper('vat');
			if ($this->config->get('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
				$this->error['tax_id'] = $this->language->get('error_vat');
			}		
		}
    	if ($this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
    	if ($this->request->post['zone_id'] == '') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}
    	if (!$this->error) {
      		return true;
		} else {
      		return false;
    	}
  	}
  	private function validateDelete() {
    	if ($this->model_seller_address->getTotalAddresses() == 1) {
      		$this->error['warning'] = $this->language->get('error_delete');
    	}
    	if ($this->seller->getAddressId() == $this->request->get['address_id']) {
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
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		$this->response->setOutput(json_encode($json));
	}
}
?>