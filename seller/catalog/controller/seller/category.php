<?php 
class ControllerSellerCategory extends Controller { 
	
	private $error = array();
	
	public function index() {
	
	    if (!$this->seller->isLogged()) {
	
	  		$this->session->data['redirect'] = $this->url->link('seller/category', '', 'SSL');
	
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    
    	}
	
		$this->load->language('seller/category');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('seller/category');
	
		$this->getList();
	
	}

	private function categoryRedirect($endpoint) {
		
    	if (!$this->seller->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('seller/category/'.trim($endpoint), '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    	}
	}
	
	public function add() {

		$this->categoryRedirect('add');

		$this->load->language('seller/category');
	
		$this->document->setTitle($this->language->get('heading_title1'));
	
		$this->load->model('seller/category');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	
			$this->model_seller_category->addCategory($this->request->post);
	
			$this->session->data['success'] = $this->language->get('text_success');
	
			$this->response->redirect($this->url->link('seller/category', '', 'SSL')); 
	
		}
	
		$this->getForm();
	
	}
	
	public function update() {
	
		$this->categoryRedirect('update');
		$this->load->language('seller/category');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('seller/category');
	
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	
			$this->model_seller_category->editCategory($this->request->get['category_id'], $this->
				request->post);
			$this->session->data['success'] = $this->language->get('text_modify');
			
			$this->response->redirect($this->url->link('seller/category', '', 'SSL'));
		
		}
		
		$this->getForm();
	
	}
	
	public function delete() {
	
		$this->categoryRedirect('delete');
		$this->load->language('seller/category');
	
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->load->model('seller/category');
	
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
	
			foreach ($this->request->post['selected'] as $category_id) {
	
				$this->model_seller_category->deleteCategory($category_id);
	
			}
	
			$this->session->data['success'] = $this->language->get('text_modify');
	
			$this->response->redirect($this->url->link('seller/category', '', 'SSL'));
	
		}
	
		$this->getList();
	
	}
	
	private function getList() {
	
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
			'href'      => $this->url->link('common/home', '' ,'SSL'),
      		'separator' => false
   		);
	
		$data['breadcrumbs'][] = array(
       		'text'      => 'Account',
			'href'      => $this->url->link('seller/account', '', 'SSL'),       		
      		'separator' => ' :: '
   		);
   	
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/category', '', 'SSL'),
      		'separator' => ' :: '
   		);
	
		$data['insert'] = $this->url->link('seller/category/add', $url, 'SSL');
	
		$data['categories'] = array();
	
		$filter_data = array(
		'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
	
		$results = $this->model_seller_category->getallCategories1($filter_data,$this->seller->getId());
	
		$category_total = $this->model_seller_category->getTotalCategories1($this->seller->getId());
	
		foreach ($results as $result) {
	
			$action = array();
	
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('seller/category/update', '' . '&category_id=' . $result['category_id']. $url, 'SSL')
			);
	
			$data['categories'][] = array(
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'approve'        => $result['approve'],
				'sort_order'  => $result['sort_order'],
				'edit'        => $this->url->link('seller/category/update', 'category_id=' . $result['category_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('seller/category/delete','category_id=' . $result['category_id'] . $url, 'SSL')
			);
	
		}
	
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_edit'] = $this->language->get('button_edit');
 	
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
	
		$data['sort_name'] = $this->url->link('seller/category', 'sort=name' . $url, 'SSL');
	
		$data['sort_sort_order'] = $this->url->link('seller/category', 'sort=sort_order' . $url, 'SSL'
	);
	
		$url = '';
	
		if (isset($this->request->get['sort'])) {
	
			$url .= '&sort=' . $this->request->get['sort'];
	
		}
	
		if (isset($this->request->get['order'])) {
	
			$url .= '&order=' . $this->request->get['order'];
	
		}
	
		$pagination = new Pagination();
	
		$pagination->total = $category_total;
	
		$pagination->page = $page;
	
		$pagination->limit = $this->config->get('config_admin_limit');
	
		$pagination->text = $this->language->get('text_pagination');
	
		$pagination->url = $this->url->link('seller/category', $url . '&page={page}', 'SSL');
	
		$data['pagination'] = $pagination->render();
	
		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($category_total - $this->config->get('config_limit_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $category_total, ceil($category_total / $this->config->get('config_limit_admin')));
    
        $data['sort'] = $sort;
	
		$data['order'] = $order;
	
		$data['column_left'] = $this->load->controller('common/column_left');
		
		$data['footer'] = $this->load->controller('common/footer');
	
		$data['header'] = $this->load->controller('common/header');
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/category_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/category_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/category_list.tpl', $data));
		}
	}
	private function getForm() {
		
		$data['heading_title1'] = $this->language->get('heading_title1');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');		
		$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_parent'] = $this->language->get('entry_parent');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_top'] = $this->language->get('entry_top');
		$data['entry_column'] = $this->language->get('entry_column');		
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['help_filter'] = $this->language->get('help_filter');
		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_top'] = $this->language->get('help_top');
		$data['help_column'] = $this->language->get('help_column');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['button_continue'] = $this->language->get('button_continue');
    	$data['button_back'] = $this->language->get('button_back');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
    	$data['tab_general'] = $this->language->get('tab_general');
    	$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');
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
		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}
  		$data['breadcrumbs'] = array();
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', '', 'SSL'),
      		'separator' => false
   		);
		$data['breadcrumbs'][] = array(
       		'text'      => 'Account',
			'href'      => $this->url->link('seller/account', '', 'SSL'),       		
      		'separator' => ' :: '
   		);
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title1'),
			'href'      => $this->url->link('seller/category', '', 'SSL'),
      		'separator' => ' :: '
   		);
		if (!isset($this->request->get['category_id'])) {
			$data['action'] = $this->url->link('seller/category/add', '', 'SSL');
		} else {
			$data['action'] = $this->url->link('seller/category/update', '' . '&category_id=' . $this->request->get['category_id'], 'SSL');
		}
		$data['cancel'] = $this->url->link('seller/category', '', 'SSL');
		if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$category_info = $this->model_seller_category->getCategory($this->request->get['category_id'],$this->seller->getId());
    	}
    	//var_dump($category_info);
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->post['category_description'])) {
			$data['category_description'] = $this->request->post['category_description'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_description'] = $this->model_seller_category->getCategoryDescriptions($this->request->get['category_id']);
		} else {
			$data['category_description'] = array();
		}
		if (isset($this->request->post['path'])) {
			$data['path'] = $this->request->post['path'];
		} elseif (!empty($category_info)) {
			$data['path'] = $category_info['path'];
		} else {
			$data['path'] = '';
		}
		$categories = $this->model_seller_category->getCategories(0,$this->seller->getId());
		// Remove own id from list
		if (!empty($category_info)) {
			foreach ($categories as $key => $category) {
				if ($category['category_id'] == $category_info['category_id']) {
					unset($categories[$key]);
				}
			}
		}
		$data['categories'] = $categories;
		if (isset($this->request->post['parent_id'])) {
			$data['parent_id'] = $this->request->post['parent_id'];
		} elseif (!empty($category_info)) {
			$data['parent_id'] = $category_info['parent_id'];
		} else {
			$data['parent_id'] = 0;
		}
		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();
		if (isset($this->request->post['category_store'])) {
			$data['category_store'] = $this->request->post['category_store'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_store'] = $this->model_seller_category->getCategoryStores($this->request->get['category_id']);
		} else {
			$data['category_store'] = array(0);
		}
		if (isset($this->request->post['meta_keyword'])) {
			$data['meta_keyword'] = $this->request->post['meta_keyword'];
		} elseif (!empty($category_info)) {
			$data['meta_keyword'] = $category_info['meta_keyword'];
		} else {
			$data['meta_keyword'] = '';
		}
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($category_info)) {
			$data['image'] = $category_info['image'];
		} else {
			$data['image'] = '';
		}
		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$this->load->model('seller/seller');
		$foldername = $this->model_seller_seller->getfoldername($this->seller->getId());
		if (isset($this->request->post['image'])) {
			$data['thumb'] =  $this->request->post['image'];
		} elseif (!empty($category_info) && file_exists(DIR_IMAGE . $category_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($category_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		if (isset($this->request->post['top'])) {
			$data['top'] = $this->request->post['top'];
		} elseif (!empty($category_info)) {
			$data['top'] = $category_info['top'];
		} else {
			$data['top'] = 0;
		}
		if (isset($this->request->post['column'])) {
			$data['column'] = $this->request->post['column'];
		} elseif (!empty($category_info)) {
			$data['column'] = $category_info['column'];
		} else {
			$data['column'] = 1;
		}
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($category_info)) {
			$data['sort_order'] = $category_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($category_info)) {
			$data['status'] = $category_info['status'];
		} else {
			$data['status'] = 1;
		}
		if (isset($this->request->post['category_layout'])) {
			$data['category_layout'] = $this->request->post['category_layout'];
		} elseif (isset($this->request->get['category_id'])) {
			$data['category_layout'] = $this->model_seller_category->getCategoryLayouts($this->request->get['category_id']);
		} else {
			$data['category_layout'] = array();
		}
		//$this->load->model('design/layout');
		//$data['layouts'] = $this->model_design_layout->getLayouts();
		$data['back'] = $this->url->link('seller/category', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/category_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/category_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/category_form.tpl', $data));
		}
	}
	private function validateForm() {
		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
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
		if (!$this->error) {
			return true; 
		} else {
			return false;
		}
	}
	public function autocomplete() {
		
		$json = array();
		
		if ( isset( $this->request->get['filter_name'] ) ) {
		
			$this->load->model('seller/category');
		
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 100
			);
		
			$results= $this->model_seller_category->getallCategories( $filter_data );
		
			foreach ($results as $result) {
		
				$json[] = array(
					'category_id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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
?>