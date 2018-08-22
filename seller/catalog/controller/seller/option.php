<?php
class ControllerSellerOption extends Controller {
	private $error = array();  
	public function index() {
	  if (!$this->seller->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('seller/option', '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    	}
		$this->load->language('seller/option');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/option');
		$this->getList();
	}
	public function insert() {
		$this->load->language('seller/option');
		$this->document->setTitle($this->language->get('heading_title1'));
		$this->load->model('seller/option');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_seller_option->addOption($this->request->post);
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
			$this->response->redirect($this->url->link('seller/option', $url, 'SSL'));
		}
		$this->getForm();
	}
	public function update() {
		$this->load->language('seller/option');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/option');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_seller_option->editOption($this->request->get['option_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_modify');
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
			$this->response->redirect($this->url->link('seller/option', $url, 'SSL'));
		}
		$this->getForm();
	}
	public function delete() {
		$this->load->language('seller/option');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/option');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $option_id) {
				$this->model_seller_option->deleteOption($option_id);
			}
			$this->session->data['success'] = $this->language->get('text_modify');
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
			$this->response->redirect($this->url->link('seller/option',$url, 'SSL'));
		}
		$this->getList();
	}
	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'od.name';
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
			'href'      => $this->url->link('common/home', '', 'SSL'),
      		'separator' => false
   		);
		$data['breadcrumbs'][] = array(
       		'text'      => 'Account',
			'href'      => $this->url->link('seller/account', '', 'SSL'),       		
      		'separator' => ' :: '
   		);
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/option', $url, 'SSL'),
      		'separator' => ' :: '
   		);
		$data['insert'] = $this->url->link('seller/option/insert', $url, 'SSL');
		$data['delete'] = $this->url->link('seller/option/delete', $url, 'SSL');
		$data['options'] = array();
		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$option_total = $this->model_seller_option->getTotalOptions();
		$results = $this->model_seller_option->getallOptions($filter_data);
		foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('seller/option/update', '' . '&option_id=' . $result['option_id'] . $url, 'SSL')
			);
			$data['options'][] = array(
				'option_id'  => $result['option_id'],
				'name'       => $result['name'],
				'seller_id'       => $result['seller_id'],
				'approve'       => $result['approve'],
				'sort_order' => $result['sort_order'],
				'edit'        => $this->url->link('seller/option/update', 'option_id=' . $result['option_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('seller/option/delete','option_id=' . $result['option_id'] . $url, 'SSL')
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
		$data['sort_name'] = $this->url->link('seller/option', '' . '&sort=od.name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('seller/option', '' . '&sort=o.sort_order' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $option_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('seller/option', '' . $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['results'] = sprintf($this->language->get('text_pagination'), ($option_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($option_total - $this->config->get('config_limit_admin'))) ? $option_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $option_total, ceil($option_total / $this->config->get('config_limit_admin')));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/option_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/option_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/option_list.tpl', $data));
		}
	}
	private function getForm() {
		$data['heading_title1'] = $this->language->get('heading_title1');
		$data['text_choose'] = $this->language->get('text_choose');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_radio'] = $this->language->get('text_radio');
		$data['text_checkbox'] = $this->language->get('text_checkbox');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_input'] = $this->language->get('text_input');
		$data['text_text'] = $this->language->get('text_text');
		$data['text_textarea'] = $this->language->get('text_textarea');
		$data['text_file'] = $this->language->get('text_file');
		$data['text_date'] = $this->language->get('text_date');
		$data['text_datetime'] = $this->language->get('text_datetime');
		$data['text_time'] = $this->language->get('text_time');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');	
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_type'] = $this->language->get('entry_type');
		$data['entry_value'] = $this->language->get('entry_value');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_option_value'] = $this->language->get('button_add_option_value');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['tab_general'] = $this->language->get('tab_general');
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
 		if (isset($this->error['option_value'])) {
			$data['error_option_value'] = $this->error['option_value'];
		} else {
			$data['error_option_value'] = array();
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
			'href'      => $this->url->link('seller/option', '' . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		if (!isset($this->request->get['option_id'])) {
			$data['action'] = $this->url->link('seller/option/insert', $url, 'SSL');
		} else { 
			$data['action'] = $this->url->link('seller/option/update', '' . '&option_id=' . $this->request->get['option_id'] . $url, 'SSL');
		}
		$data['cancel'] = $this->url->link('seller/option',$url, 'SSL');
		if (isset($this->request->get['option_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$option_info = $this->model_seller_option->getOption($this->request->get['option_id']);
			if(!$option_info){
				$data['action'] = $this->url->link('seller/option/insert', $url, 'SSL');
			}
    	}
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->request->post['option_description'])) {
			$data['option_description'] = $this->request->post['option_description'];
		} elseif (!empty($option_info)) {
			$data['option_description'] = $this->model_seller_option->getOptionDescriptions($this->request->get['option_id']);
		} else {
			$data['option_description'] = array();
		}	
		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} elseif (!empty($option_info)) {
			$data['type'] = $option_info['type'];
		} else {
			$data['type'] = '';
		}
		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($option_info)) {
			$data['sort_order'] = $option_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}
		if (isset($this->request->post['option_value'])) {
			$option_values = $this->request->post['option_value'];
		} elseif (!empty($option_info)) {
			$option_values = $this->model_seller_option->getOptionValueDescriptions($this->request->get['option_id']);
		} else {
			$option_values = array();
		}
		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['option_values'] = array();
		foreach ($option_values as $option_value) {
			if ($option_value['image'] && file_exists(DIR_IMAGE .$option_value['image'])) {
				$image = $option_value['image'];
			} else {
				$image = 'no_image.png';
			}
			$data['option_values'][] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value['option_value_description'],
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($image, 100, 100),
				'sort_order'               => $option_value['sort_order']
			);
		}
      $data['back'] = $this->url->link('seller/option', '', 'SSL');
		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/option_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/option_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/option_form.tpl', $data));
		}
	}
	private function validateForm() {
		foreach ($this->request->post['option_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 128)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}
		if (($this->request->post['type'] == 'select' || $this->request->post['type'] == 'radio' || $this->request->post['type'] == 'checkbox') && !isset($this->request->post['option_value'])) {
			$this->error['warning'] = $this->language->get('error_type');
		}
		if (isset($this->request->post['option_value'])) {
			foreach ($this->request->post['option_value'] as $option_value_id => $option_value) {
				foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
					if ((utf8_strlen($option_value_description['name']) < 1) || (utf8_strlen($option_value_description['name']) > 128)) {
						$this->error['option_value'][$option_value_id][$language_id] = $this->language->get('error_option_value'); 
					}					
				}
			}	
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function upload() {
		$this->language->load('seller/extension');	
		$json = array();
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
			if ((strlen($filename) < 3) || (strlen($filename) > 128)) {
        		$json['error'] = $this->language->get('error_filename');
	  		}	  	
			$allowed = array();
			$filetypes = explode("\n", $this->config->get('config_file_ext_allowed'));
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
       		}
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		if (!$json) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				//$file = basename($filename) . '.' . md5(rand());
				$file = basename($filename);
				// Hide the uploaded file name so people can not link to it directly.
				$json['file'] = $file;
				$this->load->model('seller/seller');
				$foldername = $this->model_seller_seller->getfoldername($this->seller->getId());
				$json['foldername'] = $foldername.'/';
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_IMAGE .$foldername.'/'.  $file);
			}
			$json['success'] = $this->language->get('text_upload');
		}	
		$this->response->setOutput(json_encode($json));		
	}
	private function validateDelete() {
		$this->load->model('seller/product');
		foreach ($this->request->post['selected'] as $option_id) {
			$product_total = $this->model_seller_product->getTotalProductsByOptionId($option_id);
			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}	
	public function autocomplete() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->language('seller/option');
			$this->load->model('seller/option');
			$this->load->model('tool/image');
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 20
			);
			$seller_id = $this->seller->getId();
			$options = $this->model_seller_option->getOptions1f($filter_data,$seller_id);
			foreach ($options as $option) {
				$option_value_data = array();
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$option_values = $this->model_seller_option->getOptionValues($option['option_id']);
					foreach ($option_values as $option_value) {
						if ($option_value['image'] && file_exists(DIR_IMAGE . $option_value['image'])) {
							$image = $this->model_tool_image->resize($option_value['image'], 50, 50);
						} else {
							$image = '';
						}
						$option_value_data[] = array(
							'option_value_id' => $option_value['option_value_id'],
							'name'            => html_entity_decode($option_value['name'], ENT_QUOTES, 'UTF-8'),
							'image'           => $image					
						);
					}
					$sort_order = array();
					foreach ($option_value_data as $key => $value) {
						$sort_order[$key] = $value['name'];
					}
					array_multisort($sort_order, SORT_ASC, $option_value_data);					
				}
				$type = '';
				if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
					$type = $this->language->get('text_choose');
				}
				if ($option['type'] == 'text' || $option['type'] == 'textarea') {
					$type = $this->language->get('text_input');
				}
				if ($option['type'] == 'file') {
					$type = $this->language->get('text_file');
				}
				if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
					$type = $this->language->get('text_date');
				}
				$json[] = array(
					'option_id'    => $option['option_id'],
					'name'         => strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8')),
					'category'     => $type,
					'type'         => $option['type'],
					'option_value' => $option_value_data
				);
			}
		}
		$sort_order = array();
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		array_multisort($sort_order, SORT_ASC, $json);
		$this->response->setOutput(json_encode($json));
	}
}
?>