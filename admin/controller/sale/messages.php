<?php    
class ControllerSaleMessages extends Controller { 
	private $error = array();
 
  	public function index() {
		$this->load->language('sale/seller');
		 
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/seller');
		
    	$this->getList();
  	}
  
  	public function delete() {
		$this->load->language('sale/seller');

    	$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('sale/seller');
			
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $seller_id) {
				$this->model_sale_seller->deleteSeller($seller_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_seller_group_id'])) {
				$url .= '&filter_seller_group_id=' . $this->request->get['filter_seller_group_id'];
			}
			
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
			
			if (isset($this->request->get['filter_approved'])) {
				$url .= '&filter_approved=' . $this->request->get['filter_approved'];
			}	
				
			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}
					
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->response->redirect($this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
    	}
    
    	$this->getList();
  	}  
  	private function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$filter_approved = $this->request->get['filter_approved'];
		} else {
			$filter_approved = null;
		}
			
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}		
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
						
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}
		
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}	
		
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$data['delete'] = $this->url->link('sale/seller/delete', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$data['sellers'] = array();

		$data1 = array(
			'filter_name'              => $filter_name, 
			'filter_email'             => $filter_email, 
			'filter_status'            => $filter_status, 
			'filter_approved'          => $filter_approved, 
			'filter_date_added'        => $filter_date_added,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);
		
		$seller_total = $this->model_sale_seller->getTotalSellers($data1);
	
		$results = $this->model_sale_seller->getSellers($data1);
		//for messages
		//$this->model_sale_seller->getMessages();
 
    	foreach ($results as $result) {
			$action = array();
			$this->load->model('sale/seller');
			$balance_total = $this->model_sale_seller->getBalanceTotal($result['seller_id']);
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/seller/update', 'user_token=' . $this->session->data['user_token'] . '&seller_id=' . $result['seller_id'] . $url, 'SSL')
			);
			
			$this->load->model('report/seller_transactions');
			$pending = $this->model_report_seller_transactions->getPending($result['seller_id']);
			
			$data['sellers'][] = array(
				'seller_id'    => $result['seller_id'],
				'name'           => $result['name'],
				'payment_status'           => $result['payment_status'],
				'email'          => $result['email'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'approved'       => ($result['approved'] ? $this->language->get('text_yes') : $this->language->get('text_no')),
				'ip'             => $result['ip'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'           => $this->url->link('sale/seller/update', 'user_token=' . $this->session->data['user_token'] . '&seller_id=' . $result['seller_id'] . $url, 'SSL'),
				'approve'        => $this->url->link('sale/seller/approve', 'user_token=' . $this->session->data['user_token'] . '&seller_id=' . $result['seller_id'] . $url, 'SSL'),
				'approvepayment'        => $this->url->link('sale/seller/approvepayment', 'user_token=' . $this->session->data['user_token'] . '&seller_id=' . $result['seller_id'] . $url, 'SSL'),
				'approved'       => $result['approved'],
				'balance_total'       => $balance_total,
				 'amount'       => $this->currency->format($pending['pamount'], $this->config->get('config_currency')),
				 'transaction' =>$this->url->link('report/seller_transactions/Seller','&seller_id='.$result['seller_id']. '&user_token=' . $this->session->data['user_token'],'SSL')
			);
		}	
					
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');	
		$data['text_select'] = $this->language->get('text_select');	
		$data['text_default'] = $this->language->get('text_default');		
		$data['text_no_results'] = $this->language->get('text_no_results');
         $data['text_login'] = $this->language->get('text_login');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_login'] = $this->language->get('text_login');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_seller_group'] = $this->language->get('column_seller_group');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_approved'] = $this->language->get('column_approved');
		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_login'] = $this->language->get('column_login');
		$data['column_action'] = $this->language->get('column_action');		
		
		$data['button_approve'] = $this->language->get('button_approve');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_edit'] = $this->language->get('button_edit');

		$data['user_token'] = $this->session->data['user_token'];

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
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_seller_group_id'])) {
			$url .= '&filter_seller_group_id=' . $this->request->get['filter_seller_group_id'];
		}
			
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}	
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
				
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_name'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, 'SSL');
		$data['sort_email'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&sort=c.email' . $url, 'SSL');
		$data['sort_seller_group'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&sort=seller_group' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&sort=c.status' . $url, 'SSL');
		$data['sort_approved'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&sort=c.approved' . $url, 'SSL');
		$data['sort_ip'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&sort=c.ip' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&sort=c.date_added' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_seller_group_id'])) {
			$url .= '&filter_seller_group_id=' . $this->request->get['filter_seller_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
				
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $seller_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination'] = $pagination->render();

		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($seller_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($seller_total - $this->config->get('config_limit_admin'))) ? $seller_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $seller_total, ceil($seller_total / $this->config->get('config_limit_admin')));

		
		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;

		$data['filter_status'] = $filter_status;
		$data['filter_approved'] = $filter_approved;
		$data['filter_date_added'] = $filter_date_added;
	
		$this->load->model('setting/store');
		
		$data['stores'] = $this->model_setting_store->getStores();
				
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

	
		$this->response->setOutput($this->load->view('sale/seller_list', $data));
  	}
  
  	private function getForm() {
    	$data['heading_title'] = $this->language->get('heading_title');
    	$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
    	$data['text_wait'] = $this->language->get('text_wait');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_add_blacklist'] = $this->language->get('text_add_blacklist');
		$data['text_remove_blacklist'] = $this->language->get('text_remove_blacklist');
		
		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		
    	$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_aboutus'] = $this->language->get('entry_aboutus');
		
		$data['entry_image'] = $this->language->get('entry_image');
		
		$data['text_clear'] = $this->language->get('text_clear');
		
		$data['text_browse'] = $this->language->get('text_browse');
		
		$data['text_image_manager'] = $this->language->get('text_image_manager');		

		
    	$data['entry_lastname'] = $this->language->get('entry_lastname');
    	$data['entry_email'] = $this->language->get('entry_email');
    	$data['entry_telephone'] = $this->language->get('entry_telephone');
    	$data['entry_fax'] = $this->language->get('entry_fax');
    	$data['entry_password'] = $this->language->get('entry_password');
    	$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$data['entry_seller_group'] = $this->language->get('entry_seller_group');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_company_id'] = $this->language->get('entry_company_id');
		$data['entry_tax_id'] = $this->language->get('entry_tax_id');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_default'] = $this->language->get('entry_default');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_points'] = $this->language->get('entry_points');
 		$data['entry_description'] = $this->language->get('entry_description');
		
		
		$data['entry_address_2'] = $this->language->get('entry_address_2');
    	$data['entry_postcode2'] = $this->language->get('entry_postcode');
    	$data['entry_city2'] = $this->language->get('entry_city');
    	$data['entry_country2'] = $this->language->get('entry_country');
    	$data['entry_zone2'] = $this->language->get('entry_zone');
    	$data['entry_paypalemail'] = $this->language->get('entry_paypalemail');
		$data['entry_username'] = $this->language->get('entry_username');
 		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
    	$data['button_add_address'] = $this->language->get('button_add_address');
		$data['button_add_transaction'] = $this->language->get('button_add_transaction');
		$data['button_add_reward'] = $this->language->get('button_add_reward');
    	$data['button_remove'] = $this->language->get('button_remove');
	
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_address'] = $this->language->get('tab_address');
		$data['tab_transaction'] = $this->language->get('tab_transaction');
		$data['tab_reward'] = $this->language->get('tab_reward');
		$data['tab_ip'] = $this->language->get('tab_ip');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['entry_bankname'] = $this->language->get('entry_bankname');
		$data['entry_accountnumber'] = $this->language->get('entry_accountnumber');
		$data['entry_accountname'] = $this->language->get('entry_accountname');
		$data['entry_branch'] = $this->language->get('entry_branch');
		$data['entry_ifsccode'] = $this->language->get('entry_ifsccode');
		$data['entry_cheque'] = $this->language->get('entry_cheque');
		
		if (isset($this->error['cheque'])) {
			$data['error_cheque'] = $this->error['cheque'];
		} else {
			$data['error_cheque'] = '';
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
		
		if (isset($this->error['paypalemail'])) {
			$data['error_paypalemail'] = $this->error['paypalemail'];
		} else {
			$data['error_paypalemail'] = '';
		}

		if (isset($this->request->get['seller_id'])) {
			$data['seller_id'] = $this->request->get['seller_id'];
		} else {
			$data['seller_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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
		
		if (isset($this->error['username12'])) {
		$data['error_username12'] = $this->error['username12'];
		} else {
			$data['error_username12'] = '';
		}
		
		if (isset($this->error['bank_name'])) {
			$data['error_bankname'] = $this->error['bank_name'];
		} else {
			$data['error_bankname'] = '';
		}

		if (isset($this->error['account_number'])) {
			$data['error_accountnumber'] = $this->error['account_number'];
		} else {
			$data['error_accountnumber'] = '';
		}

		if (isset($this->error['account_name'])) {
			$data['error_accountname'] = $this->error['account_name'];
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

		if (isset($this->error['error_commission'])) {
			$data['error_commission'] = $this->error['commission'];
		} else {
			$data['error_commission'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}
		
			if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = array();
		}

		
		
		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}
		
	
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_approved'])) {
			$url .= '&filter_approved=' . $this->request->get['filter_approved'];
		}	
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
      		'separator' => false
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['seller_id'])) {
			$data['action'] = $this->url->link('sale/seller/insert', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('sale/seller/update', 'user_token=' . $this->session->data['user_token'] . '&seller_id=' . $this->request->get['seller_id'] . $url, 'SSL');
		}
		  
    	$data['cancel'] = $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

    	if (isset($this->request->get['seller_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$sellerinfo = $this->model_sale_seller->getAddresses($this->request->get['seller_id']);
			foreach($sellerinfo as $seller_info){}
    	}
		
		
		
		
		
		
			
    	if (isset($this->request->post['firstname'])) {
      		$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($seller_info)) { 
			$data['firstname'] = $seller_info['firstname'];
		} else {
      		$data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($seller_info)) { 
			$data['lastname'] = $seller_info['lastname'];
		} else {
      		$data['lastname'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$data['email'] = $this->request->post['email'];
    	} elseif (!empty($seller_info)) { 
			$data['email'] = $seller_info['email'];
		} else {
      		$data['email'] = '';
    	}

    	if (isset($this->request->post['telephone'])) {
      		$data['telephone'] = $this->request->post['telephone'];
    	} elseif (!empty($seller_info)) { 
			$data['telephone'] = $seller_info['telephone'];
		} else {
      		$data['telephone'] = '';
    	}if (isset($this->request->post['tin_no'])) {      		$data['tin_no'] = $this->request->post['tin_no'];    	} elseif (!empty($seller_info)) { 			$data['tin_no'] = $seller_info['tin_no'];		} else {      		$data['tin_no'] = '';    	}

    	if (isset($this->request->post['fax'])) {
      		$data['fax'] = $this->request->post['fax'];
    	} elseif (!empty($seller_info)) { 
			$data['fax'] = $seller_info['fax'];
		} else {
      		$data['fax'] = '';
    	}

    	if (isset($this->request->post['username'])) {
      		$data['username'] = $this->request->post['username'];
    	} elseif (!empty($seller_info)) { 
			$data['username'] = $seller_info['username'];
		} else {
      		$data['username'] = '';
    	}
		
		if (isset($this->request->post['bank_name'])) {
			$data['bank_name'] = $this->request->post['bank_name'];
		} elseif (!empty($seller_info)) {
      		$data['bank_name'] = $seller_info['bank_name'];
    	} else {
			$data['bank_name'] = '';
		}
		
		if (isset($this->request->post['cheque'])) {
      		$data['cheque'] = $this->request->post['cheque'];
    	}  elseif (!empty($seller_info)) {
      		$data['cheque'] = $seller_info['payee_name'];
    	} else {
      		$data['cheque'] = '';
    	}

		if (isset($this->request->post['account_number'])) {
			$data['account_number'] = $this->request->post['account_number'];
		} elseif (!empty($seller_info)) {
      		$data['account_number'] = $seller_info['account_number'];
    	} else {
			$data['account_number'] = '';
		}

		if (isset($this->request->post['account_name'])) {
			$data['account_name'] = $this->request->post['account_name'];
		} elseif (!empty($seller_info)) {
      		$data['account_name'] = $seller_info['account_name'];
    	} else {
			$data['account_name'] = '';
		}

		if (isset($this->request->post['branch'])) {
			$data['branch'] = $this->request->post['branch'];
		} elseif (!empty($seller_info)) {
      		$data['branch'] = $seller_info['branch'];
    	} else {
			$data['branch'] = '';
		}

		if (isset($this->request->post['ifsccode'])) {
			$data['ifsccode'] = $this->request->post['ifsccode'];
		} elseif (!empty($seller_info)) {
      		$data['ifsccode'] = $seller_info['ifsccode'];
    	} else {
			$data['ifsccode'] = '';
		}

		
		if (isset($this->request->post['commission_id'])) {
      		$data['commission_id'] = $this->request->post['commission_id'];
    	} elseif (!empty($seller_info)) { 
			$data['commission_id'] = $seller_info['commission_id'];
		} else {
      		$data['commission_id'] = '';
    	}

		
		
		if (isset($this->request->post['aboutus'])) {
      		$data['aboutus'] = $this->request->post['aboutus'];
    	} elseif (!empty($seller_info)) { 
			$data['aboutus'] = $seller_info['aboutus'];
		} else {
      		$data['aboutus'] = '';
    	}
		
		
		
		if (isset($this->request->post['aboutus'])) {
    		$data['aboutus'] = $this->request->post['aboutus'];
		}elseif (isset($seller_info)) {
			$data['aboutus'] = $seller_info['aboutus'];
		}   else {
			$data['aboutus'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		}elseif(!empty($seller_info)) {	
			$data['image'] = $seller_info['image'];
			}
			else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');
		$this->load->model('sale/seller');
	 if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($seller_info) && $seller_info['image'] && file_exists(DIR_IMAGE . $seller_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($seller_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		
		
    	if (isset($this->request->post['status'])) {
      		$data['status'] = $this->request->post['status'];
    	} elseif (!empty($seller_info)) { 
			$data['status'] = $seller_info['status'];
		} else {
      		$data['status'] = 1;
    	}

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
		
		$this->load->model('localisation/country');
		
		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$data['commissions'] = $this->model_sale_seller->getCommissions();
			
		if (isset($this->request->post['address'])) { 
      		$data['addresses'] = $this->request->post['address'];
		} elseif (isset($this->request->get['seller_id'])) {
			$data['addresses'] = $this->model_sale_seller->getAddresses1($this->request->get['seller_id']);
		} else {
			$data['addresses'] = array();
    	}

	

    	if (isset($this->request->post['address_id'])) {
      		$data['address_id'] = $this->request->post['address_id'];
    	} elseif (!empty($seller_info)) { 
			$data['address_id'] = $seller_info['address_id'];
		} else {
      		$data['address_id'] = '';
    	}
		
		
		
		
		
	

    	if (isset($this->request->post['address_2'])) {
      		$data['address_2'] = $this->request->post['address_2'];
    	} elseif (!empty($seller_info)) {
			$data['address_2'] = $seller_info['address_2'];
		} else {
      		$data['address_2'] = '';
    	}

     
		

    	if (isset($this->request->post['postcode2'])) {
      		$data['postcode2'] = $this->request->post['postcode2'];
    	} elseif (!empty($seller_info)) {
			$data['postcode2'] = $seller_info['postcode2'];			
		} else {
      		$data['postcode2'] = '';
    	}

    	if (isset($this->request->post['city2'])) {
      		$data['city2'] = $this->request->post['city2'];
    	} elseif (!empty($seller_info)) {
			$data['city2'] = $seller_info['city2'];
		} else {
      		$data['city2'] = '';
    	}

    	if (isset($this->request->post['country_id2'])) {
      		$data['country_id2'] = $this->request->post['country_id2'];
    	}  elseif (!empty($seller_info)) {
      		$data['country_id2'] = $seller_info['country_id2'];			
    	} else {
      		$data['country_id2'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id2'])) {
      		$data['zone_id2'] = $this->request->post['zone_id2'];
    	}  elseif (!empty($seller_info)) {
      		$data['zone_id2'] = $seller_info['zone_id2'];
    	} else {
      		$data['zone_id2'] = '';
    	}

		if (isset($this->request->post['paypal_email'])) {
      		$data['paypal_email'] = $this->request->post['paypal_email'];
    	}  elseif (!empty($seller_info)) {
      		$data['paypal_email'] = $seller_info['paypal_email'];
    	} else {
      		$data['paypal_email'] = '';
    	}

		if (isset($this->request->post['paypalorcheque'])) {
      		$data['paypalorcheque'] = $this->request->post['paypalorcheque'];
    	}  elseif (!empty($seller_info)) {
      		$data['paypalorcheque'] = $seller_info['paypalorcheque'];
    	} else {
      		$data['paypalorcheque'] = 1;
    	}
		
		$data['ips'] = array();
    	
		if (!empty($seller_info)) {
			$results = $this->model_sale_seller->getIpsBySellerId($this->request->get['seller_id']);
		
			foreach ($results as $result) {
				$blacklist_total = $this->model_sale_seller->getTotalBlacklistsByIp($result['ip']);
				
				$data['ips'][] = array(
					'ip'         => $result['ip'],
					'total'      => $this->model_sale_seller->getTotalSellersByIp($result['ip']),
					'date_added' => date('d/m/y', strtotime($result['date_added'])),
					'filter_ip'  => $this->url->link('sale/seller', 'user_token=' . $this->session->data['user_token'] . '&filter_ip=' . $result['ip'], 'SSL'),
					'blacklist'  => $blacklist_total
				);
			}
		}		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/seller_form', $data));
	}
			 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/seller')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}
		
		
		
		

    	if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
		$seller_info = $this->model_sale_seller->getSellerByEmail($this->request->post['email']);
		
		if (!isset($this->request->get['seller_id'])) {
			if ($seller_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($seller_info && ($this->request->get['seller_id'] != $seller_info['seller_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}
		
    	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
		
		if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 32)) {
      		$this->error['username12'] = $this->language->get('error_username');
    	}
		
		
		if (empty($this->request->post['commission_id'])) {
      		$this->error['commission'] = $this->language->get('error_commission');
    	}

    	if ($this->request->post['password'] || (!isset($this->request->get['seller_id']))) {
      		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		
    	}

			if (isset($this->request->post['address'])) {
			foreach ($this->request->post['address'] as $key => $value) {
				if ((utf8_strlen($value['firstname']) < 1) || (utf8_strlen($value['firstname']) > 32)) {
					$this->error['address'][$key]['firstname'] = $this->language->get('error_firstname');
				}

				if ((utf8_strlen($value['lastname']) < 1) || (utf8_strlen($value['lastname']) > 32)) {
					$this->error['address'][$key]['lastname'] = $this->language->get('error_lastname');
				}

				if ((utf8_strlen($value['address_1']) < 3) || (utf8_strlen($value['address_1']) > 128)) {
					$this->error['address'][$key]['address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($value['city']) < 2) || (utf8_strlen($value['city']) > 128)) {
					$this->error['address'][$key]['city'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($value['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($value['postcode']) < 2 || utf8_strlen($value['postcode']) > 10)) {
					$this->error['address'][$key]['postcode'] = $this->language->get('error_postcode');
				}

				if ($value['country_id'] == '') {
					$this->error['address'][$key]['country'] = $this->language->get('error_country');
				}

				if (!isset($value['zone_id']) || $value['zone_id'] == '') {
					$this->error['address'][$key]['zone'] = $this->language->get('error_zone');
				}

				
			}
		}
		
		
		
		if ($this->request->post['paypalorcheque'] == 1) {
			if ((utf8_strlen($this->request->post['paypal_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['paypal_email'])) 
			{
				$this->error['error_paypalemail'] = $this->language->get('error_paypalemail');
			}
		}
		elseif ($this->request->post['paypalorcheque'] == 2) {
			if ((utf8_strlen($this->request->post['bank_name']) < 3) || (utf8_strlen($this->request->post['bank_name']) > 32)) {
				$this->error['bank_name'] = $this->language->get('error_bankname');
			}

			if ((utf8_strlen($this->request->post['account_number']) < 3) || (utf8_strlen($this->request->post['account_number']) > 32)) {
				$this->error['account_number'] = $this->language->get('error_accountnumber');
			}

			if ((utf8_strlen($this->request->post['account_name']) < 3) || (utf8_strlen($this->request->post['account_name']) > 32)) {
				$this->error['account_name'] = $this->language->get('error_accountname');
			}

			if ((utf8_strlen($this->request->post['branch']) < 3) || (utf8_strlen($this->request->post['branch']) > 32)) {
				$this->error['branch'] = $this->language->get('error_branch');
			}

			if ((utf8_strlen($this->request->post['ifsccode']) < 3) || (utf8_strlen($this->request->post['ifsccode']) > 32)) {
				$this->error['ifsccode'] = $this->language->get('error_ifsccode');
			}

		}

		else{
			if ((utf8_strlen($this->request->post['cheque']) < 3) || (utf8_strlen($this->request->post['cheque']) > 128)) {
				$this->error['cheque'] = $this->language->get('error_cheque');
			}
		}
		
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}    
	
  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/seller')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	} 
	
}
?>