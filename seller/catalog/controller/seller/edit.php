<?php
class ControllerSellerEdit extends Controller {
	private $error = array();
	public function index() {
		if (!$this->seller->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('seller/edit', '', 'SSL');
			$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
		}
		$this->language->load('seller/edit');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('seller/seller');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_seller_seller->editSeller($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('seller/account', '', 'SSL'));
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),     	
			'separator' => false
			); 
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('seller/account', '', 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_edit'),
			'href'      => $this->url->link('seller/edit', '', 'SSL')
			);
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['entry_tin_no'] = $this->language->get('entry_tin_no');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['text_desc'] = $this->language->get('text_desc');
		$data['entry_aboutus'] = $this->language->get('entry_aboutus');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
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
		$data['action'] = $this->url->link('seller/edit', '', 'SSL');
		$this->load->model('tool/image');
		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$seller_info = $this->model_seller_seller->getSeller($this->seller->getId());
		}
		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($seller_info)) {
			$data['firstname'] = $seller_info['firstname'];
		} else {
			$data['firstname'] = '';
		}
		if (isset($this->request->post['tin_no'])) {			$data['tin_no'] = $this->request->post['tin_no'];		} elseif (isset($seller_info)) {			$data['tin_no'] = $seller_info['tin_no'];		} else {			$data['tin_no'] = '';		}
		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (isset($seller_info)) {
			$data['lastname'] = $seller_info['lastname'];
		} else {
			$data['lastname'] = '';
		}
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (isset($seller_info)) {
			$data['email'] = $seller_info['email'];
		} else {
			$data['email'] = '';
		}
		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($seller_info)) {
			$data['telephone'] = $seller_info['telephone'];
		} else {
			$data['telephone'] = '';
		}
		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (isset($seller_info)) {
			$data['fax'] = $seller_info['fax'];
		} else {
			$data['fax'] = '';
		}
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($seller_info)) {
			$data['image'] =$seller_info['image'];
		} else {
			$data['image'] = '';
		}
		if (isset($this->request->post['aboutus'])) {
			$data['aboutus'] = $this->request->post['aboutus'];
		}elseif (isset($seller_info)) {
			$data['aboutus'] = $seller_info['aboutus'];
		}   else {
			$data['aboutus'] = '';
		}
		$this->load->model('tool/image');
		$foldername = $this->model_seller_seller->getfoldername($this->seller->getId());
		if (isset($this->request->post['image'])) {
			$data['thumb'] = $this->request->post['image'];
		}
		elseif (!empty($seller_info) && $seller_info['image'] && file_exists(DIR_IMAGE . $seller_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($seller_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}
		$data['back'] = $this->url->link('seller/account', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/edit.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/edit.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/edit.tpl', $data));
		}
	}
	public function upload() {
		$this->language->load('seller/product');	
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
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_IMAGE .$foldername.'/'. $file);
			}
			$json['success'] = $this->language->get('text_upload');
		}	
		$this->response->setOutput(json_encode($json));		
	}
	public function download() {
		$this->language->load('seller/product');
		$json = array();
		if (!empty($this->request->files['file']['name'])) {
			$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
			if ((strlen($filename) < 3) || (strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			}	  	
			$allowed = array();
			$filetypes = explode(',','zip');
			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}
			if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
				$json['error'] = $this->language->get('error_filetype1');
			}	
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
		if (!$json) {
			$this->load->model('seller/download');
			$data = array();
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$file = basename($filename) . '.' . md5(rand());
				// Hide the uploaded file name so people can not link to it directly.
				$json['file']		 = basename($filename);
				$json['download_id'] = $file;
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $file);
				if (file_exists(DIR_DOWNLOAD . $file)) {
					$data['download']	= $file;
					$data['mask']		= $this->request->files['file']['name'];
					$download_id		= $this->model_seller_download->addDownload($data);
					$json['download_id']= $download_id;
				}
			}
			$json['success'] = $this->language->get('text_upload');
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
		if (($this->seller->getEmail() != $this->request->post['email']) && $this->model_seller_seller->getTotalSellersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}
		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>