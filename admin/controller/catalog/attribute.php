<?php
class ControllerCatalogAttribute extends Controller {
	private $error = array();


            /*START OVICKO MULTISELLER*/
			public function approve() {		
			$this->load->language('catalog/attribute');		
			$this->document->setTitle($this->language->get('heading_title'));		
			$this->load->model('catalog/attribute');		
			if (!$this->user->hasPermission('modify', 'catalog/attribute')) {			
			$this->error['warning'] = $this->language->get('error_permission');		
			} elseif (isset($this->request->post['selected'])) {			
			$approved = 0;			
			foreach ($this->request->post['selected'] as $attribute_id) {										
			$attribute_info = $this->model_catalog_attribute->getAttribute($attribute_id);								
			if ($attribute_info && !$attribute_info['approve']) {					
			$this->model_catalog_attribute->approveData($attribute_id);
			$approved++;				}			} 						
			$this->session->data['success'] = sprintf($this->language->get('approve_success'), $approved);			
			$url = '';			if (isset($this->request->get['filter_name'])) {				
			$url .= '&filter_name=' . $this->request->get['filter_name'];			}			
			if (isset($this->request->get['filter_email'])) {				
			$url .= '&filter_email=' . $this->request->get['filter_email'];			}		
			if (isset($this->request->get['filter_status'])) {				
			$url .= '&filter_status=' . $this->request->get['filter_status'];			}		
			if (isset($this->request->get['filter_approve'])) {				
			$url .= '&filter_approve=' . $this->request->get['filter_approve'];			
			}			if (isset($this->request->get['filter_date_added'])) {			
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];			
			}			if (isset($this->request->get['sort'])) {				
			$url .= '&sort=' . $this->request->get['sort'];			}		
			if (isset($this->request->get['order'])) {				
			$url .= '&order=' . $this->request->get['order'];			
			}			
			if (isset($this->request->get['page'])) {				
			$url .= '&page=' . $this->request->get['page'];			}				
			$this->response->redirect($this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));					}		
			$this->getList();	
			}
			/*END OVICKO MULTISELLER*/
			
	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ad.name';
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

            /*START OVICKO MULTISELLER*/
				$data['approve'] = $this->url->link('catalog/attribute/approve', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
			/*END OVICKO MULTISELLER*/


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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/attribute/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/attribute/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['attributes'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$attribute_total = $this->model_catalog_attribute->getTotalAttributes();

		$results = $this->model_catalog_attribute->getAttributes($filter_data);

		foreach ($results as $result) {

            /*START OVICKO MULTISELLER*/
			$this->load->model('catalog/category');
			$sellers = $this->model_catalog_category->getSellers();
			$data['sellers'] = $sellers;
			$sellername = "";
			if(isset($sellers[$result['seller_id']])){
			$sellername = $sellers[$result['seller_id']];
			}
			/*END OVICKO MULTISELLER*/

			$data['attributes'][] = array(

            /*START OVICKO MULTISELLER*/
					'seller_name'=> $sellername,
				'approve'     => ($result['approve'] ? $this->language->get('approve_enabled'):$this->language->get('approve_disabled')),
			/*END OVICKO MULTISELLER*/

				'attribute_id'    => $result['attribute_id'],
				'name'            => $result['name'],
				'attribute_group' => $result['attribute_group'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('catalog/attribute/edit', 'user_token=' . $this->session->data['user_token'] . '&attribute_id=' . $result['attribute_id'] . $url, true)
			);
		}


            /*START OVICKO MULTISELLER*/
		$data['column_approve'] = $this->language->get('column_approve');
		$data['column_approved'] = $this->language->get('column_approved');
		$data['button_approve'] = $this->language->get('button_approve');
		$data['approve_enabled'] = $this->language->get('approve_enabled');
		$data['approve_disabled'] = $this->language->get('approve_disabled');
		$data['column_seller'] = $this->language->get('column_seller');
			/*END OVICKO MULTISELLER*/

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . '&sort=ad.name' . $url, true);
		$data['sort_attribute_group'] = $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . '&sort=attribute_group' . $url, true);
		$data['sort_sort_order'] = $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . '&sort=a.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $attribute_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($attribute_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($attribute_total - $this->config->get('config_limit_admin'))) ? $attribute_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $attribute_total, ceil($attribute_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_list', $data));
	}

	public function index() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute->addAttribute($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['attribute_group_id']) {
			$this->error['attribute_group'] = $this->language->get('error_attribute_group');
		}

		foreach ($this->request->post['attribute_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['attribute_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['attribute_group'])) {
			$data['error_attribute_group'] = $this->error['attribute_group'];
		} else {
			$data['error_attribute_group'] = '';
		}

		$url = '';

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
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['attribute_id'])) {
			$data['action'] = $this->url->link('catalog/attribute/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/attribute/edit', 'user_token=' . $this->session->data['user_token'] . '&attribute_id=' . $this->request->get['attribute_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['attribute_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($this->request->get['attribute_id']);
		}

            /*START OVICKO MULTISELLER*/
				$data['entry_approve'] = $this->language->get('entry_approve');
				$data['approve_enabled'] = $this->language->get('approve_enabled');
				$data['approve_disabled'] = $this->language->get('approve_disabled');
				$data['approve_success'] = $this->language->get('approve_success');
				$data['column_approve'] = $this->language->get('column_approve');
				if (isset($this->request->post['approve'])) {
					$data['approve'] = $this->request->post['approve'];
				} elseif (!empty($option_info)) {
					$data['approve'] = $option_info['approve'];
				} else {
					$data['approve'] = '';
				}
			/*END OVICKO MULTISELLER*/


		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['attribute_description'])) {
			$data['attribute_description'] = $this->request->post['attribute_description'];
		} elseif (isset($this->request->get['attribute_id'])) {
			$data['attribute_description'] = $this->model_catalog_attribute->getAttributeDescriptions($this->request->get['attribute_id']);
		} else {
			$data['attribute_description'] = array();
		}

		if (isset($this->request->post['attribute_group_id'])) {
			$data['attribute_group_id'] = $this->request->post['attribute_group_id'];
		} elseif (!empty($attribute_info)) {
			$data['attribute_group_id'] = $attribute_info['attribute_group_id'];
		} else {
			$data['attribute_group_id'] = '';
		}

		$this->load->model('catalog/attribute_group');

		$data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($attribute_info)) {
			$data['sort_order'] = $attribute_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_form', $data));
	}

	public function edit() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute->editAttribute($this->request->get['attribute_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $attribute_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByAttributeId($attribute_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/attribute');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_attribute->getAttributes($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'attribute_id'    => $result['attribute_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute_group' => $result['attribute_group']
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
