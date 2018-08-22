<?php  
class ControllerSellerDownload extends Controller {  
	
	private $error = array();
  	
  	public function index() {
    	$this->downloadRedirect('');
		$this->load->language('seller/download');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/download');
    	$this->getList();
  	}

	public function autocomplete() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('seller/download');
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);
			$results = $this->model_seller_download->getDownloads($filter_data,$this->seller->getId());
			foreach ($results as $result) {
				$json[] = array(
					'download_id' => $result['download_id'],
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

	private function downloadRedirect($endpoint) {
		
    	if (!$this->seller->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('seller/download/'.trim($endpoint), '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    	}
	}

  	public function insert() {
		$this->downloadRedirect('insert');
		$this->load->language('seller/download');
    	$this->document->setTitle($this->language->get('heading_title1'));
		$this->load->model('seller/download');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_seller_download->addDownload($this->request->post);
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
			$this->response->redirect($this->url->link('seller/download', $url, 'SSL'));
		}
    	$this->getForm();
  	}
  	public function update() {
		$this->downloadRedirect('');
		$this->load->language('seller/download');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/download');
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_seller_download->editDownload($this->request->get['download_id'], $this->request->post);
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
			$this->response->redirect($this->url->link('seller/download', $url, 'SSL'));
		}
    	$this->getForm();
  	}
  	public function delete() {
		$this->downloadRedirect('');
		$this->load->language('seller/download');
    	$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/download');
    	if (isset($this->request->post['selected']) && $this->validateDelete()) {	  
			foreach ($this->request->post['selected'] as $download_id) {
				$this->model_seller_download->deleteDownload($download_id);
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
			$this->response->redirect($this->url->link('seller/download', $url, 'SSL'));
    	}
    	$this->getList();
  	}
  	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'dd.name';
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
			'href'      => $this->url->link('common/home','', 'SSL'),       		
      		'separator' => false
   		);
		$data['breadcrumbs'][] = array(
       		'text'      => 'Account',
			'href'      => $this->url->link('seller/account', '', 'SSL'),       		
      		'separator' => ' :: '
   		);
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/download',$url, 'SSL'),
      		'separator' => ' :: '
   		);
		$data['insert'] = $this->url->link('seller/download/insert', $url, 'SSL');
		$data['delete'] = $this->url->link('seller/download/delete', $url, 'SSL');	
		$data['downloads'] = array();
		$data1 = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		$download_total = $this->model_seller_download->getTotalDownloads();
		$sellerid= $this->seller->getId();
		$results = $this->model_seller_download->getDownloads($data1,$sellerid);
    	foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('seller/download/update', '&download_id=' . $result['download_id'] . $url, 'SSL')
			);
			$data['downloads'][] = array(
				'download_id' => $result['download_id'],
				'name'        => $result['name'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'        => $this->url->link('seller/download/update', 'download_id=' . $result['download_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('seller/download/delete','download_id=' . $result['download_id'] . $url, 'SSL')
			);
		}	
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get(' text_confirm');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
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
		$data['sort_name'] = $this->url->link('seller/download', '&sort=dd.name' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('seller/download', '&sort=d.date_added' . $url, 'SSL');
		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $download_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('seller/download', $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($download_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($download_total - $this->config->get('config_limit_admin'))) ? $download_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $download_total, ceil($download_total / $this->config->get('config_limit_admin')));
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/download_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/download_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/download_list.tpl', $data));
		}
  	}
  	private function getForm() {
    	$data['heading_title1'] = $this->language->get('heading_title1');
    	$data['entry_name'] = $this->language->get('entry_name');
    	$data['entry_filename'] = $this->language->get('entry_filename');
		$data['entry_mask'] = $this->language->get('entry_mask');
    	$data['entry_remaining'] = $this->language->get('entry_remaining');
    	$data['entry_update'] = $this->language->get('entry_update');
		$data['help_filename'] = $this->language->get('help_filename');
		$data['help_mask'] = $this->language->get('help_mask');
		$data['text_form'] = !isset($this->request->get['download_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_loading'] = $this->language->get('text_loading');
    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
  		$data['button_upload'] = $this->language->get('button_upload');
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
  		if (isset($this->error['filename'])) {
			$data['error_filename'] = $this->error['filename'];
		} else {
			$data['error_filename'] = '';
		}
  		if (isset($this->error['mask'])) {
			$data['error_mask'] = $this->error['mask'];
		} else {
			$data['error_mask'] = '';
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
			'href'      => $this->url->link('common/home','', 'SSL'),
      		'separator' => false
   		);
		$data['breadcrumbs'][] = array(
       		'text'      => 'Account',
			'href'      => $this->url->link('seller/account', '', 'SSL'),       		
      		'separator' => ' :: '
   		);
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title1'),
			'href'      => $this->url->link('seller/download', $url, 'SSL'),      		
      		'separator' => ' :: '
   		);
		if (!isset($this->request->get['download_id'])) {
			$data['action'] = $this->url->link('seller/download/insert', $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('seller/download/update', '&download_id=' . $this->request->get['download_id'] . $url, 'SSL');
		}
		$data['cancel'] = $this->url->link('seller/download', $url, 'SSL');
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$sellerid=$this->seller->getId();
    	if (isset($this->request->get['download_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$download_info = $this->model_seller_download->getDownload($this->request->get['download_id'],$sellerid);
    	}
  		//$data['token'] = $this->session->data['token'];
  		if (isset($this->request->get['download_id'])) {
			$data['download_id'] = $this->request->get['download_id'];
		} else {
			$data['download_id'] = 0;
		}
		if (isset($this->request->post['download_description'])) {
			$data['download_description'] = $this->request->post['download_description'];
		} elseif (isset($this->request->get['download_id'])) {
			$data['download_description'] = $this->model_seller_download->getDownloadDescriptions($this->request->get['download_id']);
		} else {
			$data['download_description'] = array();
		}   
    	if (isset($this->request->post['filename'])) {
    		$data['filename'] = $this->request->post['filename'];
    	} elseif (!empty($download_info)) {
      		$data['filename'] = $download_info['filename'];
		} else {
			$data['filename'] = '';
		}
    	if (isset($this->request->post['mask'])) {
    		$data['mask'] = $this->request->post['mask'];
    	} elseif (!empty($download_info)) {
      		$data['mask'] = $download_info['mask'];		
		} else {
			$data['mask'] = '';
		}
    	if (isset($this->request->post['update'])) {
      		$data['update'] = $this->request->post['update'];
    	} else {
      		$data['update'] = false;
    	}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/download_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/download_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/download_form.tpl', $data));
		}	
  	}
  	private function validateForm() { 
    	foreach ($this->request->post['download_description'] as $language_id => $value) {
      		if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 64)) {
        		$this->error['name'][$language_id] = $this->language->get('error_name');
      		}
    	}	
		if ((utf8_strlen($this->request->post['filename']) < 3) || (utf8_strlen($this->request->post['filename']) > 128)) {
			$this->error['filename'] = $this->language->get('error_filename');
		}	
		if (!file_exists(DIR_DOWNLOAD . $this->request->post['filename']) && !is_file(DIR_DOWNLOAD . $this->request->post['filename'])) {
			$this->error['filename'] = $this->language->get('error_exists');
		}
		if ((utf8_strlen($this->request->post['mask']) < 3) || (utf8_strlen($this->request->post['mask']) > 128)) {
			$this->error['mask'] = $this->language->get('error_mask');
		}	
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	private function validateDelete() {
		$this->load->model('seller/product');
		foreach ($this->request->post['selected'] as $download_id) {
  			$product_total = $this->model_seller_product->getTotalProductsByDownloadId($download_id);
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
	public function upload12() {
		$this->load->language('seller/download');
		$json = array();
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			}	  	
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		if (!isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$ext = md5(mt_rand());
				$json['filename'] = $filename . '.' . $ext;
				$json['mask'] = $filename;
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $filename . '.' . $ext);
			}
			$json['success'] = $this->language->get('text_upload');
		}	
		$this->response->setOutput(json_encode($json));
	}
	public function upload() {
		$this->load->language('seller/download');
		$json = array();
		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
				// Validate the filename length
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
					$json['error'] = $this->language->get('error_filename');
				}
				// Allowed file extension types
				$allowed = array();
				$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));
				$filetypes = explode("\n", $extension_allowed);
				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}
				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
				// Allowed file mime types
				$allowed = array();
				$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));
				$filetypes = explode("\n", $mime_allowed);
				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}
				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['file']['tmp_name']);
				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = $this->language->get('error_filetype');
				}
				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}
		if (!$json) {
		$ext = md5(mt_rand());
				$json['filename'] = $filename . '.' . $ext;
				$json['mask'] = $filename;
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $filename . '.' . $ext);
			$json['success'] = $this->language->get('text_upload');
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}
?>