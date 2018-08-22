<?php
class ControllerCatalogCommission extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/commission');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_commission->addCommission($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/commission', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_commission->editCommission($this->request->get['commission_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/commission', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/commission');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/commission');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $commission_id) {
				$this->model_catalog_commission->deleteCommission($commission_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/commission', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'commission_type';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/commission', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'), 
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$data['insert'] = $this->url->link('catalog/commission/insert', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/commission/delete', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$data['commissions'] = array();

		$data_filter = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
		
		$commissions_total = $this->model_catalog_commission->getTotalCommissions($data_filter);  //count commission per page
		$results = $this->model_catalog_commission->getCommissions($data_filter); //get total commission name

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/commission/update', 'user_token=' . $this->session->data['user_token'] . '&commission_id=' . $result['commission_id'] . $url, 'SSL')
			
			);
			
			$total_agents = $this->model_catalog_commission->getTotalAgentsByCommissionId($result['commission_id']);
				
			$data['commissions'][] = array(
				'commission_id' 	=> $result['commission_id'],
				'commission_name' 	=> $result['commission_name'],
				
				'commission'    	=> $result['commission'],
				'product_limit'    	=> $result['product_limit'],
				'total_agents'		=> $total_agents,
				'sort_order'    	=> $result['sort_order'],
				'edit'           => $this->url->link('catalog/commission/update', 'user_token=' . $this->session->data['user_token'] . '&commission_id=' . $result['commission_id'] . $url, 'SSL'),
				
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['commission_id'], $this->request->post['selected']),
				'action'     		=> $action
			);
    	}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_commission'] = $this->language->get('column_commission');
		
		$data['column_product_limit'] = $this->language->get('column_product_limit');
		
    	$data['column_total_agents'] = $this->language->get('column_total_agents');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$data['text_percentage'] = $this->language->get('text_percentage');

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

		
		if (isset($this->request->get['filter_commission_type'])) {
			$filter_commission_type = $this->request->get['filter_commission_type'];
		} else {
			$filter_commission_type = NULL;
		}
		
		if (isset($this->request->get['filter_commission_percentage'])) {
			$filter_commission_percentage = $this->request->get['filter_commission_percentage'];
		} else {
			$filter_commission_percentage = NULL;
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$filter_sort_order = $this->request->get['filter_sort_order'];
		} else {
			$filter_sort_order = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_commission_type'])) {
			$url .= '&filter_commission_type=' . $this->request->get['filter_commission_type'];
		}
		
		if (isset($this->request->get['filter_commission_percentage'])) {
			$url .= '&filter_commission_percentage=' . $this->request->get['filter_commission_percentage'];
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$url .= '&filter_sort_order=' . $this->request->get['filter_sort_order'];
		} 

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_commission_name'] = $this->url->link('catalog/commission&user_token=' . $this->session->data['user_token'] . '&sort=commission_name' . $url, 'SSL');
		$data['sort_commission_type'] = $this->url->link('catalog/commission&user_token=' . $this->session->data['user_token'] . '&sort=commission_type' . $url, 'SSL');
		$data['sort_commission'] = $this->url->link('catalog/commission&user_token=' . $this->session->data['user_token'] . '&sort=commission' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/commission&user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $commissions_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/commission', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($commissions_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($commissions_total - $this->config->get('config_limit_admin'))) ? $commissions_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $commissions_total, ceil($commissions_total / $this->config->get('config_limit_admin')));

		$data['filter_commission_type'] = $filter_commission_type;
		//$data['filter_commision_percentage'] = $filter_commision_percentage;
			
		$data['sort'] = $sort;
		$data['order'] = $order;

		
	$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/commission_list', $data));
  	}

  	private function getForm() {
    	$data['heading_title'] = $this->language->get('heading_title');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_commission'] = $this->language->get('entry_commission');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['text_fixed_rate'] = $this->language->get('text_fixed_rate');
		$data['text_percentage'] = $this->language->get('text_percentage');
		
    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['entry_product_limit'] = $this->language->get('entry_product_limit');
		
		
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_duration'] = $this->language->get('entry_duration');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_plan'] = $this->language->get('tab_plan');
		
    	
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['commission_name'])) {
			$data['error_commission_name'] = $this->error['commission_name'];
		} else {
			$data['error_commission_name'] = '';
		}
		
		if (isset($this->error['commission'])) {
			$data['error_commission'] = $this->error['commission'];
		} else {
			$data['error_commission'] = '';
		}

   		if (isset($this->error['sort_order'])) {
			$data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$data['error_sort_order'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_commission_type'])) {
			$url .= '&filter_commission_type=' . $this->request->get['filter_commission_type'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
			'separator' => FALSE
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('catalog/commission', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		if (!isset($this->request->get['commission_id'])) {
			$data['action'] = $this->url->link('catalog/commission/insert', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/commission/update', 'user_token=' . $this->session->data['user_token'] . '&commission_id=' . $this->request->get['commission_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/commission', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('catalog/category');			
		$data['categories'] = $this->model_catalog_category->getCategories12(0);
		
		if (isset($this->request->get['commission_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$commissions_info = $this->model_catalog_commission->getCommission($this->request->get['commission_id']);
    	}
		
		if (isset($this->request->post['commission_name'])) {
      		$data['commission_name'] = $this->request->post['commission_name'];
    	} elseif (isset($commissions_info)) {
			$data['commission_name'] = $commissions_info['commission_name'];
		} else {	
      		$data['commission_name'] = '';
    	}

		if(isset($this->request->post['commission'])) {
      		$data['commission'] = $this->request->post['commission'];
    	} elseif (isset($commissions_info)) {
			$data['commission'] =$commissions_info['commission'];
		} else {	
      		$data['commission'] = "";
    	}
		
		
		if(isset($this->request->post['product_limit'])) {
      		$data['product_limit'] = $this->request->post['product_limit'];
    	} elseif (isset($commissions_info)) {
			$data['product_limit'] =$commissions_info['product_limit'];
		} else {	
      		$data['product_limit'] = "";
    	}
		
		
		

		if(isset($this->request->post['commission_rate'])) {
      		$data['commission_rate'] = $this->request->post['commission_rate'];
    	} elseif (isset($commissions_info)) {
			$data['commission_rate'] =$commissions_info['commission_rate'];
		} else {	
      		$data['commission_rate'] = array();
    	}
		
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($commissions_info)) {
			$data['sort_order'] = $commissions_info['sort_order'];
		} else {	
      		$data['sort_order'] = '';
    	}
		
		
		
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_discount'] = $this->language->get('entry_discount');
		$data['entry_duration'] = $this->language->get('entry_duration');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_plan'] = $this->language->get('tab_plan');
		
		
	
			
		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		} elseif (!empty($commissions_info)) {
			$data['amount'] = $commissions_info['amount'];
		} else {
			$data['amount'] = '';
		}
		
		
		
		
		if (isset($this->request->post['per'])) {
			$data['per'] = $this->request->post['per'];
		} elseif (!empty($commissions_info)) {
			$data['per'] = $commissions_info['per'];
		} else {
			$data['per'] = '';
		}
		
		
		if (isset($this->request->post['duration_id'])) {
      		$data['duration_id'] = $this->request->post['duration_id'];
    	} elseif (!empty($commissions_info)) {
			$data['duration_id'] = $commissions_info['duration_id'];
		} else {	
      		$data['duration_id'] = '';
    	}
		
		$data['durations'][0]['duration_id'] = 'd'; 
		$data['durations'][0]['duration_name'] = 'Day(s)'; 
		
		$data['durations'][1]['duration_id'] = 'w'; 
		$data['durations'][1]['duration_name'] = 'Week(s)'; 
		
		$data['durations'][2]['duration_id'] = 'm'; 
		$data['durations'][2]['duration_name'] = 'Month(s)'; 
		
		$data['durations'][3]['duration_id'] = 'y'; 
		$data['durations'][3]['duration_name'] = 'Year(s)'; 
		
		$data['durations'][4]['duration_id'] = 'l'; 
		$data['durations'][4]['duration_name'] = 'Life time';
		
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/commission_form', $data));
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/commission')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['commission_name'])) < 1) || (strlen(utf8_decode($this->request->post['commission_name'])) > 64)) {
      		$this->error['commission_name'] = $this->language->get('error_commission_name');
    	}
		
    	if (!$this->error) {
			return TRUE;
    	} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/commission')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/commission');

		foreach ($this->request->post['selected'] as $commission_id) {
  			$commissions_total = $this->model_catalog_commission->getTotalAgentsByCommissionId($commission_id);
    		if ($commissions_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_delete'), $commissions_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

}
?>