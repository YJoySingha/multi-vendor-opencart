<?php 
class ControllerSellerProduct extends Controller {
	
	private $error = array(); 
	
	public function index() {
	
		$this->productRedirect('');
	
		$this->load->language('seller/product');
	
		$this->document->setTitle($this->language->get('heading_title')); 
	
		$this->load->model('seller/product');
	
		$this->getList();
	
	}

	private function productRedirect($endpoint) {
		
    	if (!$this->seller->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('seller/product/'.trim($endpoint), '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    	}
	}
	
	private function OverMaxLimit() { 
	
		$this->load->model('seller/product');
	
		$maxproducts = $this->model_seller_product->getTotalProducts1();
	
		$assignLimit = $this->model_seller_product->getAssignLimit();
	
		if ($maxproducts > $assignLimit - 1) {
	
			$this->error['warning'] = $this->language->get('error_max_warning');
	
		}
	
		if (isset($this->request->post['selected'])) {
	
			if (($maxproducts + (isset($this->request->post['selected']) ? count($this->request->post['selected']) : 0)) > $assignLimit) {
	
				$this->error['warning'] = $this->language->get('error_max_warning');
	
			}
	
		}
	
		if ($this->error) {
	
			return true;
	
		} else {
			return false;
	
		}
	}
	
	public function add() {
	
		$this->productRedirect('add');
	
		$this->load->language('seller/product');
	
		$this->document->setTitle($this->language->get('heading_title1')); 
	
		$this->load->model('seller/product');
	
		if (!$this->OverMaxLimit()) {
	
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	
				$this->model_seller_product->addProduct($this->request->post);
	
				if ($this->config->get('config_product_autoapprove')) {
	
					$this->session->data['success'] = $this->language->get('text_success1');
	
				} else {
	
					$this->session->data['success'] = $this->language->get('text_success');
				}
				$url = '';
				
				if (isset($this->request->get['filter_name1'])) {
				
					$url .= '&filter_name1=' . $this->request->get['filter_name1'];
				
				}
				
				if (isset($this->request->get['filter_model'])) {
				
					$url .= '&filter_model=' . $this->request->get['filter_model'];
				
				}
				
				if (isset($this->request->get['filter_price'])) {
				
					$url .= '&filter_price=' . $this->request->get['filter_price'];
				
				}
				
				if (isset($this->request->get['filter_quantity'])) {
				
					$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
				
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
				
				$this->response->redirect($this->url->link('seller/product', $url, 'SSL'));
			
			}

			
			$this->getForm();
		
		} else {
		
			$this->getList();
		
		}
	}

	public function update() {

		$this->productRedirect('update');

		$this->load->language('seller/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('seller/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_seller_product->editProduct($this->request->get['product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_modify');

			$url = '';

			if (isset($this->request->get['filter_name1'])) {

				$url .= '&filter_name1=' . $this->request->get['filter_name1'];

			}

			if (isset($this->request->get['filter_model'])) {

				$url .= '&filter_model=' . $this->request->get['filter_model'];

			}

			if (isset($this->request->get['filter_price'])) {

				$url .= '&filter_price=' . $this->request->get['filter_price'];

			}

			if (isset($this->request->get['filter_quantity'])) {

				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];

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

			$this->response->redirect($this->url->link('seller/product', $url, 'SSL'));

		}

		$this->getForm();

	}

	public function delete() {

		$this->productRedirect('delete');

		$this->load->language('seller/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('seller/product');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {

			foreach ($this->request->post['selected'] as $product_id) {

				$this->model_seller_product->deleteProduct($product_id);

			}

			$this->session->data['success'] = $this->language->get('text_delete');

			$url = '';

			if (isset($this->request->get['filter_name1'])) {

				$url .= '&filter_name1=' . $this->request->get['filter_name1'];

			}

			if (isset($this->request->get['filter_model'])) {

				$url .= '&filter_model=' . $this->request->get['filter_model'];

			}

			if (isset($this->request->get['filter_price'])) {

				$url .= '&filter_price=' . $this->request->get['filter_price'];

			}

			if (isset($this->request->get['filter_quantity'])) {

				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];

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

			$this->response->redirect($this->url->link('seller/product',$url, 'SSL'));

		}

		$this->getList();

	}

	public function copy() {

		$this->productRedirect('copy');
		$this->load->language('seller/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('seller/product');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {

			foreach ($this->request->post['selected'] as $product_id) {

				$this->model_seller_product->copyProduct($product_id);

			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name1'])) {

				$url .= '&filter_name1=' . $this->request->get['filter_name1'];

			}

			if (isset($this->request->get['filter_model'])) {

				$url .= '&filter_model=' . $this->request->get['filter_model'];

			}

			if (isset($this->request->get['filter_price'])) {

				$url .= '&filter_price=' . $this->request->get['filter_price'];

			}

			if (isset($this->request->get['filter_quantity'])) {

				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];

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

			$this->response->redirect($this->url->link('seller/product', $url, 'SSL'));

		}

		$this->getList();

	}

	private function getList() {				

		if (isset($this->request->get['filter_name1'])) {

			$filter_name1 = $this->request->get['filter_name1'];

		} else {

			$filter_name1 = null;

		}

		if (isset($this->request->get['filter_model'])) {

			$filter_model = $this->request->get['filter_model'];

		} else {

			$filter_model = null;

		}

		if (isset($this->request->get['filter_sku'])) {

			$filter_sku = $this->request->get['filter_sku'];

		} else {

			$filter_sku = null;

		}

		if (isset($this->request->get['filter_price'])) {

			$filter_price = $this->request->get['filter_price'];

		} else {

			$filter_price = null;

		}

		if (isset($this->request->get['filter_quantity'])) {

			$filter_quantity = $this->request->get['filter_quantity'];

		} else {

			$filter_quantity = null;

		}

		if (isset($this->request->get['filter_seller'])) {

			$filter_seller = $this->request->get['filter_seller'];

		} else {

			$filter_seller = NULL;

		}

		if (isset($this->request->get['sort'])) {

			$sort = $this->request->get['sort'];

		} else {

			$sort = 'pd.name';

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

		if (isset($this->request->get['filter_name1'])) {

			$url .= '&filter_name1=' . $this->request->get['filter_name1'];

		}

		if (isset($this->request->get['filter_model'])) {

			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_sku'])) {

			$url .= '&filter_sku=' . $this->request->get['filter_sku'];

		}

		if (isset($this->request->get['filter_price'])) {

			$url .= '&filter_price=' . $this->request->get['filter_price'];

		}

		if (isset($this->request->get['filter_quantity'])) {

			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];

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
			'href'      => $this->url->link('common/home', '', 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text'      => 'Account',
			'href'      => $this->url->link('seller/account', '', 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/product', '', 'SSL')
			);
		$data['insert'] = $this->url->link('seller/product/add', '', 'SSL');
		$data['copy'] = $this->url->link('seller/product/copy','', 'SSL');	
		$data['delete'] = $this->url->link('seller/product/delete', '', 'SSL');
		$data['products'] = array();
		$filterdata = array(
			'filter_name1'	  => $filter_name1, 
			'filter_model'	  => $filter_model,
			'filter_sku'	  => $filter_sku,
			'filter_seller'   => $filter_seller, 
			'filter_price'	  => $filter_price,
			'filter_quantity' => $filter_quantity,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
			);
		$seller_id = $this->seller->getId();
		$this->load->model('tool/image');
		$product_total = $this->model_seller_product->getTotalProducts($filterdata,$this->seller->getId());
		$results = $this->model_seller_product->getProducts($filterdata,$this->seller->getId());
		//var_dump($results);
		
		foreach ($results as $result) {
			$action = array();
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('seller/product/update', 'product_id=' . $result['product_id'] . $url, 'SSL')
				);
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_seller_product->getProductSpecialsBySeller($result['product_id'],$this->seller->getId());

			foreach ($product_specials  as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

					break;
				}					
			}

			$edit = $this->url->link('seller/product/update', 'product_id=' . $result['product_id'] . $url, 'SSL');
			$this->load->model('seller/download');
			$data['products'][] = array(
				'approve'     => ($result['approve'] ? $this->language->get('text_approved') : $this->language->get('text_pending')),
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'date_added'       => $result['date_added'],
				'model'      => $result['model'],
				'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'sku'      	 => $result['sku'],
				'special'    => $special,
				'image'      => $image,
				'quantity'   => $result['quantity'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['product_id'], $this->request->post['selected']),
				'edit'       => $edit
				);
		}
		$data['heading_title'] = $this->language->get('heading_title');						
		$data['new_extensions'] = sprintf($this->language->get('new_extensions'), $this->url->link('seller/product/add', '', 'SSL'));	
		$data['text_enabled'] = $this->language->get('text_enabled');		
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_pending']  = $this->language->get('text_pending');
		$data['approved']  = $this->language->get('approved');
		$data['text_delete']	= $this->language->get('text_delete');
		$data['text_no_results'] = $this->language->get('text_no_results');		
		$data['text_image_manager'] = $this->language->get('text_image_manager');		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');		
		$data['column_action'] = $this->language->get('column_action');		
		$data['column_downloads'] = $this->language->get('column_downloads');		
		$data['button_copy'] = $this->language->get('button_copy');		
		$data['button_insert'] = $this->language->get('button_insert');		
		$data['button_delete'] = $this->language->get('button_delete');		
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_edit'] = $this->language->get('button_edit');
		/**NEW ADDED CODE**/
		$data['column_image'] = $this->language->get('column_image');		
		$data['column_model'] = $this->language->get('column_model');		
		$data['column_price'] = $this->language->get('column_price');		
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_approve'] = $this->language->get('column_approve');
		$data['text_list'] = $this->language->get('text_list');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_status'] = $this->language->get('entry_status');
		if (isset($this->session->data['token'])) {
			$data['token'] = $this->session->data['token'];
		} else {
			$data['token'] = '';
		}
		/**/
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

		if (isset($this->request->get['filter_name1'])) {
			$url .= '&filter_name1=' . $this->request->get['filter_name1'];
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('seller/product', 'sort=pd.name' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('seller/product', 'sort=p.model' . $url, 'SSL');
		$data['sort_sku'] = $this->url->link('seller/product', 'sort=p.sku' . $url, 'SSL');
		$data['sort_price'] = $this->url->link('seller/product', 'sort=p.price' . $url, 'SSL');
		$data['sort_quantity'] = $this->url->link('seller/product', 'sort=p.quantity' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('seller/product', 'sort=p.sort_order' . $url, 'SSL');
		$data['sort_approve'] = $this->url->link('seller/product', 'sort=p.sort_approve' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name1'])) {
			$url .= '&filter_name1=' . $this->request->get['filter_name1'];
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('seller/product', $url . '&page={page}', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));
		$data['filter_name1']	= $filter_name1;
		$data['filter_model'] = $filter_model;
		$data['filter_sku']	= $filter_sku;
		$data['filter_price'] = $filter_price;
		$data['filter_quantity'] = $filter_quantity;
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/product_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/product_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/product_list.tpl', $data));
		}
	}
	public function captcha() {
		$this->load->library('captcha');
		$captcha = new Captcha();
		$this->session->data['captcha'] = $captcha->getCode();
		$captcha->showImage();
	}	
	private function getForm() {
		$data['heading_title1'] = $this->language->get('heading_title1');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_upc'] = $this->language->get('entry_upc');
		$data['entry_ean'] = $this->language->get('entry_ean');
		$data['entry_jan'] = $this->language->get('entry_jan');
		$data['entry_isbn'] = $this->language->get('entry_isbn');
		$data['entry_mpn'] = $this->language->get('entry_mpn');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_points'] = $this->language->get('entry_points');
		$data['entry_option_points'] = $this->language->get('entry_option_points');
		$data['entry_subtract'] = $this->language->get('entry_subtract');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_weight'] = $this->language->get('entry_weight');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_download'] = $this->language->get('entry_download');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_filter'] = $this->language->get('entry_filter');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_attribute'] = $this->language->get('entry_attribute');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_option_value'] = $this->language->get('entry_option_value');
		$data['entry_required'] = $this->language->get('entry_required');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_recurring'] = $this->language->get('entry_recurring');
		//wholesale,commission,retail price
		$data['entry_commission']    = $this->language->get('Commission');
		$data['entry_wholesale_price']  = $this->language->get('Wholesale price');
		$data['entry_retail_price']    = $this->language->get('Retail price');
		$data['fixed_commission']       = $this->language->get('Fixed');
		$data['percent_commission']     = $this->language->get('Percent');
		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_sku'] = $this->language->get('help_sku');
		$data['help_upc'] = $this->language->get('help_upc');
		$data['help_ean'] = $this->language->get('help_ean');
		$data['help_jan'] = $this->language->get('help_jan');
		$data['help_isbn'] = $this->language->get('help_isbn');
		$data['help_mpn'] = $this->language->get('help_mpn');
		$data['help_minimum'] = $this->language->get('help_minimum');
		$data['help_manufacturer'] = $this->language->get('help_manufacturer');
		$data['help_stock_status'] = $this->language->get('help_stock_status');
		$data['help_points'] = $this->language->get('help_points');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_filter'] = $this->language->get('help_filter');
		$data['help_download'] = $this->language->get('help_download');
		$data['help_related'] = $this->language->get('help_related');
		$data['help_tag'] = $this->language->get('help_tag');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_attribute_add'] = $this->language->get('button_attribute_add');
		$data['button_option_add'] = $this->language->get('button_option_add');
		$data['button_option_value_add'] = $this->language->get('button_option_value_add');
		$data['button_discount_add'] = $this->language->get('button_discount_add');
		$data['button_special_add'] = $this->language->get('button_special_add');
		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_recurring_add'] = $this->language->get('button_recurring_add');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_attribute'] = $this->language->get('tab_attribute');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_recurring'] = $this->language->get('tab_recurring');
		$data['tab_discount'] = $this->language->get('tab_discount');
		$data['tab_special'] = $this->language->get('tab_special');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_reward'] = $this->language->get('tab_reward');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_openbay'] = $this->language->get('tab_openbay');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');
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
		if (isset($this->error['meta_description'])) {
			$data['error_meta_description'] = $this->error['meta_description'];
		} else {
			$data['error_meta_description'] = array();
		}		
		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}	
		if (isset($this->error['price'])) {
			$data['error_price'] = $this->error['price'];
		} else {
			$data['error_price'] = "";
		}
		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = "";
		}
		if (isset($this->error['captcha'])) {
			$data['error_captcha'] = $this->error['captcha'];
		} else {
			$data['error_captcha'] = '';
		}			
		if (isset($this->error['date_available'])) {
			$data['error_date_available'] = $this->error['date_available'];
		} else {
			$data['error_date_available'] = '';
		}
		$data['store_currency'] =$this->currency->getSymbolLeft($this->session->data['currency']);
		/**NEW ADDED CODE**/
		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}	
		$url = '';
		if (isset($this->request->get['filter_name1'])) {
			$url .= '&filter_name1=' . $this->request->get['filter_name1'];
		}
		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
		}
		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}
		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . $this->request->get['filter_model'];
		}
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
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
		if (isset($this->request->post['captcha'])) {
			$data['captcha'] = $this->request->post['captcha'];
		} else {
			$data['captcha'] = '';
		}
		if (isset($this->error['download'])) {
			$data['error_download'] = $this->error['download'];
		} else {
			$data['error_download'] = '';
		}		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', '', 'SSL'),
			'separator' => false
			);
		$data['breadcrumbs'][] = array(
			'text'      => 'Account',
			'href'      => $this->url->link('seller/account',$url, 'SSL'),
			'separator' => ' :: '
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/product','', 'SSL'),
			'separator' => ' :: '
			);
		if (!isset($this->request->get['product_id'])) {
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title1'),
				'href'      => $this->url->link('seller/product/add',$url, 'SSL'),
				'separator' => ' :: '
				);
		} else {

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title1'),
				'href'      => $this->url->link('seller/product/update',$url, 'SSL'),
				'separator' => ' :: '
				);
		}
		
		if (!isset($this->request->get['product_id'])) {
		
			$data['action'] = $this->url->link('seller/product/add',  $url, 'SSL');
		
		} else {
		
			$data['action'] = $this->url->link('seller/product/update','&product_id=' . $this->request->get['product_id'] . $url, 'SSL');
		
		}
		
		$data['cancel'] = $this->url->link('seller/product',$url, 'SSL');
		
		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
		
			$product_info = $this->model_seller_product->getProduct($this->request->get['product_id'],$this->seller->getId());
		
			if(empty($product_info) && isset($this->request->get['product_id'])){
		
				$productinfo = $this->model_seller_product->getProduct($this->request->get['product_id']);
		
				if($productinfo){
		
					if($productinfo['seller_id'] != $this->seller->getId()){
		
						$this->response->redirect($this->url->link('seller/product/details', 'product_id='.$this->request->get['product_id'], 'SSL'));
		
					}
		
				}
		
			}
		
		}
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['product_description'])) {
		
			$data['product_description'] = $this->request->post['product_description'];
		
		} elseif (isset($this->request->get['product_id'])) {
		
			$data['product_description'] = $this->model_seller_product->getProductDescriptions($this->request->get['product_id']);
		
		} else {
		
			$data['product_description'] = array();
		
		}

		if (isset($this->request->post['meta_title'])) {

			$data['meta_title'] = $this->request->post['meta_title'];

		} elseif (!empty($product_info)) {

			$data['meta_title'] = $product_info['meta_title'];

		} else {

			$data['meta_title'] = '';

		}

		/**NEW ADDED CODE**/
		if (isset($this->request->post['sku'])) {
			$data['sku'] = $this->request->post['sku'];
		} elseif (!empty($product_info)) {

			$data['sku'] = $product_info['sku'];
		} else {
			$data['sku'] = '';
		}

		if (isset($this->request->post['weight'])) {

			$data['weight'] = $this->request->post['weight'];

		} elseif (!empty($product_info)) {

			$data['weight'] = $product_info['weight'];

		} else {

			$data['weight'] = '';

		} 

		$this->load->model('seller/weight_class');

		$data['weight_classes'] = $this->model_seller_weight_class->getWeightClasses();

		if (isset($this->request->post['weight_class_id'])) {
			$data['weight_class_id'] = $this->request->post['weight_class_id'];
		} elseif (!empty($product_info)) {
			$data['weight_class_id'] = $product_info['weight_class_id'];
		} else {
			$data['weight_class_id'] = $this->config->get('config_weight_class_id');
		}

		if (isset($this->request->post['length'])) {
			$data['length'] = $this->request->post['length'];
		} elseif (!empty($product_info)) {
			$data['length'] = $product_info['length'];
		} else {
			$data['length'] = '';
		}

		if (isset($this->request->post['width'])) {

			$data['width'] = $this->request->post['width'];

		} elseif (!empty($product_info)) {	

			$data['width'] = $product_info['width'];

		} else {

			$data['width'] = '';

		}

		if (isset($this->request->post['height'])) {

			$data['height'] = $this->request->post['height'];

		} elseif (!empty($product_info)) {

			$data['height'] = $product_info['height'];

		} else {

			$data['height'] = '';

		}

		$this->load->model('seller/length_class');

		$data['length_classes'] = $this->model_seller_length_class->getLengthClasses();

		if (isset($this->request->post['length_class_id'])) {

			$data['length_class_id'] = $this->request->post['length_class_id'];

		} elseif (!empty($product_info)) {

			$data['length_class_id'] = $product_info['length_class_id'];

		} else {

			$data['length_class_id'] = $this->config->get('config_length_class_id');

		}

		$this->load->model('seller/manufacturer');

		$data['manufacturers'] = $this->model_seller_manufacturer->getManufacturers();

		if (isset($this->request->post['manufacturer_id'])) {

			$data['manufacturer_id'] = $this->request->post['manufacturer_id'];

		} elseif (!empty($product_info)) {

			$data['manufacturer_id'] = $product_info['manufacturer_id'];

		} else {

			$data['manufacturer_id'] = 0;

		}

		if (isset($this->request->post['manufacturer'])) {

			$data['manufacturer'] = $this->request->post['manufacturer'];

		} elseif (!empty($product_info)) {

			$manufacturer_info = $this->model_seller_manufacturer->getManufacturer($product_info['manufacturer_id'
		]);
		
			if ($manufacturer_info) {
		

				$data['manufacturer'] = $manufacturer_info['name'];
		
			} else {
		
				$data['manufacturer'] = '';
		
			}
		
		} else {
		
			$data['manufacturer'] = '';
		
		}
		
		$this->load->model('seller/offer');
		
		if (isset($this->request->post['product_discount'])) {
		
			$data['product_discounts'] = $this->request->post['product_discount'];
		
		} elseif (isset($this->request->get['product_id'])) {
		
		 $data['product_discounts'] = $this->model_seller_offer->getProductDiscounts(
			$this->request->get['product_id']);
		
		} else {
			
			$data['product_discounts'] = array();

		}

		if (isset($this->request->post['product_special'])) {

			$data['product_specials'] = $this->request->post['product_special'];

		} elseif (isset($this->request->get['product_id'])) {

			$data['product_specials'] = $this->model_seller_offer->getProductSpecials($this->request->get['product_id'],$this->seller->getId());
		
		} else {

			$data['product_specials'] = array();
		
		}
		
		$this->load->model('seller/customer_group');
		
		$data['customer_groups'] = $this->model_seller_customer_group->getCustomerGroups();
		
		$this->load->model('tool/image');
		
		/**END OF NEW ADDED CODE**/
		if (isset($this->request->post['image'])) {
		
			$data['image'] = $this->request->post['image'];
		
		} elseif (!empty($product_info)) {
		
			$image= explode('/',$product_info['image']);		
		
			$data['image'] = $product_info['image'];
		
		} else {
		
			$data['image'] = '';
		
		}
		
		$this->load->model('seller/seller');
		
		$foldername = $this->model_seller_seller->getfoldername($this->seller->getId());
		
		$data['foldername'] = $foldername;
		
		if (isset($this->request->post['image'])) {
		
			$data['thumb'] = $this->request->post['image'];
		
		}
		
		elseif (!empty($product_info) && $product_info['image'] && file_exists(DIR_IMAGE . $product_info['image'])) {
			
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		if (isset($this->request->post['license'])) {
			$data['license'] = $this->request->post['license'];
		}elseif (!empty($product_info)) {
			if($product_info['price'] > 0){
				$data['license'] = 1;
			}
		} else {
			$data['license'] = 0;
		}
		  //used for retail price
		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$data['price'] = $product_info['price'];
		} else {
			$data['price'] = '';
		}
		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['upc'])) {
			$data['upc'] = $this->request->post['upc'];
		} elseif (!empty($product_info)) {
			$data['upc'] = $product_info['upc'];
		} else {
			$data['upc'] = '';
		}

		if (isset($this->request->post['ean'])) {
			$data['ean'] = $this->request->post['ean'];
		} elseif (!empty($product_info)) {
			$data['ean'] = $product_info['ean'];
		} else {
			$data['ean'] = '';
		}

		if (isset($this->request->post['jan'])) {
			$data['jan'] = $this->request->post['jan'];
		} elseif (!empty($product_info)) {
			$data['jan'] = $product_info['jan'];
		} else {
			$data['jan'] = '';
		}

		if (isset($this->request->post['isbn'])) {
			$data['isbn'] = $this->request->post['isbn'];
		} elseif (!empty($product_info)) {
			$data['isbn'] = $product_info['isbn'];
		} else {
			$data['isbn'] = '';
		}

		if (isset($this->request->post['mpn'])) {
			$data['mpn'] = $this->request->post['mpn'];
		} elseif (!empty($product_info)) {
			$data['mpn'] = $product_info['mpn'];
		} else {
			$data['mpn'] = '';
		}

		if (isset($this->request->post['location'])) {
			$data['location'] = $this->request->post['location'];
		} elseif (!empty($product_info)) {
			$data['location'] = $product_info['location'];
		} else {
			$data['location'] = '';
		}

		if (isset($this->request->post['documentation'])) {
			$data['documentation'] = $this->request->post['documentation'];
		} elseif (!empty($product_info)) {
			$data['documentation'] = $product_info['documentation'];
		} else {
			$data['documentation'] = '';
		}

		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
		} else {
			$data['date_available'] = date('Y-m-d');
		}

		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$data['quantity'] = $product_info['quantity'];
		} else {
			$data['quantity'] = 1;
		}
		if (isset($this->request->post['minimum'])) {
			$data['minimum'] = $this->request->post['minimum'];
		} elseif (!empty($product_info)) {
			$data['minimum'] = $product_info['minimum'];
		} else {
			$data['minimum'] = 1;
		}
		
		if (isset($this->request->post['subtract'])) {
			$data['subtract'] = $this->request->post['subtract'];
		} elseif (!empty($product_info)) {
			$data['subtract'] = $product_info['subtract'];
		} else {
			$data['subtract'] = 0;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		if (isset($this->request->post['shipping'])) {
			$data['shipping'] = $this->request->post['shipping'];
		} elseif (!empty($product_info)) {
			$data['shipping'] = $product_info['shipping'];
		} else {
			//$data['shipping'] = 1;
			$data['shipping'] = 0;
		}
		
		$this->load->model('seller/stock_status');
		
		$data['stock_statuses'] = $this->model_seller_stock_status->getStockStatuses();

		if (isset($this->request->post['stock_status_id'])) {
			$data['stock_status_id'] = $this->request->post['stock_status_id'];
		} elseif (!empty($product_info)) {
			$data['stock_status_id'] = $product_info['stock_status_id'];
		} else {
			$data['stock_status_id'] = 0;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		if (isset($this->request->post['points'])) {
			$data['points'] = $this->request->post['points'];
		} elseif (!empty($product_info)) {
			$data['points'] = $product_info['points'];
		} else {
			$data['points'] = 0;
		}
		
		if (isset($this->request->post['status'])) {
		
			$data['status'] = $this->request->post['status'];
		
		} elseif (!empty($product_info)) {
		
			$data['status'] = $product_info['status'];
		
		} else {
		
			$data['status'] = 1;
		
		}
		
		$this->load->model('seller/product');
		
		$this->load->model('seller/offer');
		
		// Attributes
		$this->load->model('seller/attribute');
		
		if (isset($this->request->post['product_attribute'])) {
		
			$product_attributes = $this->request->post['product_attribute'];
		
		} elseif (isset($this->request->get['product_id'])) {
		
			$product_attributes = $this->model_seller_offer->getProductAttributes($this->request->get['product_id'
		]);
		
		} else {
		
			$product_attributes = array();
		
		}
		
		$data['product_attributes'] = array();
		
		foreach ($product_attributes as $product_attribute) {
		
			$attribute_info = $this->model_seller_attribute->getAttribute($product_attribute['attribute_id']);
		
			if ($attribute_info) {
		
				$data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
					);
		
			}
		
		}
		
		if (isset($this->request->post['product_option'])) {
		
			$product_options = $this->request->post['product_option'];
		
		} elseif (isset($this->request->get['product_id'])) {
		
			$product_options = $this->model_seller_offer->getProductOptions($this->request->get['product_id'],$this->seller->getId());		
		
		} else {
		
			$product_options = array();
		
		}			
		
		$data['product_options'] = array();
		
		foreach ($product_options as $product_option) {
		
			$product_option_value_data = array();
		
			if (isset($product_option['product_option_value'])) {
		
				foreach ($product_option['product_option_value'] as $product_option_value) {
		
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
						);
				}
			}
		
			$data['product_options'][] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => isset($product_option['value']) ? $product_option['value'] : '',
				'required'             => $product_option['required']
				);
		}
		
		$data['option_values'] = array();
		
		foreach ($product_options as $product_option) {
		
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
		
				if (!isset($data['option_values'][$product_option['option_id']])) {
		
					$data['option_values'][$product_option['option_id']] = $this->model_seller_product->getOptionValues($product_option['option_id']);
		
				}
		
			}
		
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		// Images
		if (isset($this->request->post['product_image'])) {
		
			$product_images = $this->request->post['product_image'];
		
		} elseif (isset($this->request->get['product_id'])) {
		
			$product_images = $this->model_seller_product->getProductImages($this->request->get['product_id']);
		
		} else {
		
			$product_images = array();
		
		}
		
		$data['product_images'] = array();
		
		foreach ($product_images as $product_image) {
		
			if (is_file(DIR_IMAGE . $product_image['image'])) {
		
				$image = $product_image['image'];
		
				$thumb = $product_image['image'];
		
			} else {
		
				$image = '';
		
				$thumb = 'no_image.png';
		
			}
		
			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order' => $product_image['sort_order']
				);
		
		}
		
		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		$this->load->model('seller/download');		
		
		if (isset($this->request->post['product_download'])) {
		
			$product_downloads = $this->request->post['product_download'];
		
		} elseif (isset($this->request->get['product_id'])) {
		
			$product_downloads = $this->model_seller_product->getProductDownloads($this->request->get['product_id'
		]);
		
		} else {
		
			$product_downloads = array();
		
		}
		
		$data['product_downloads'] = array();
		
		foreach ($product_downloads as $download_id) {
		
			$download_info = $this->model_seller_download->getDownload($download_id,$this->seller->getId());
		
			//var_dump($download_info);
		
			if ($download_info) {
		
				$data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
					);
		
			}
		
		}
		
		$this->load->model('seller/category');
		
		// Categories
		
		$this->load->model('catalog/category');
		
		if (isset($this->request->post['product_category'])) {
		
			$categories = $this->request->post['product_category'];
		
		} elseif (isset($this->request->get['product_id'])) {
		
			$categories = $this->model_seller_product->getProductCategories($this->request->get['product_id']);
		
		} else {
		
			$categories = array();
		
		}
		
		$data['product_categories'] = array();
		
		foreach ($categories as $category_id) {
		
			$category_info = $this->model_seller_category->getCategory1($category_id);
		
			if ($category_info) {
		
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
					);
		
			}
		
		}
		
		$data['categories']= $this->model_seller_category->getApproveCategories(0,$this->seller->getId());
		
		$config_product_categories = $this->config->get('config_product_category');
		
		if($config_product_categories){
		
			foreach($config_product_categories as $config_product_category){
		
				$data['categories'][] = $this->model_seller_category->getCategory1($config_product_category);
		
			}
		
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');

		$data['footer'] = $this->load->controller('common/footer');
		
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/product_form.tpl')) {
		
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/product_form.tpl', $data));
		
		} else {
		
			$this->response->setOutput($this->load->view('default/template/seller/product_form.tpl', $data));
		
		}
	} 
	public function upload() {

		$this->productRedirect('');

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
		
		$this->productRedirect('');
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
	private function validateForm() {
		//echo "<pre/>";print_r($_POST);die;
		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}
		if ((utf8_strlen($this->request->post['model']) < 3) || (utf8_strlen($this->request->post['model']) > 25)) {
			$this->error['model'] = $this->language->get('error_model');
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
	private function validateCopy() {
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	public function manuautocomplete() {
		$json = array();
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('seller/manufacturer');
			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
				);
			$results = $this->model_seller_manufacturer->getManufacturers($filter_data);
			foreach ($results as $result) {
				$json[] = array(
					'manufacturer_id' => $result['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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
	public function autocomplete() {
		if (isset($this->request->get['filter_name1']) || isset($this->request->get['filter_model'])) {
			$this->load->model('seller/product');
			$this->load->model('seller/option');
			if (isset($this->request->get['filter_name1'])) {
				$filter_name1 = $this->request->get['filter_name1'];
			} else {
				$filter_name1 = '';
			}
			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}
			$filter_data = array(
				'filter_name1'  => $filter_name1,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
				);
			$seller = $this->seller->getId();
			$results = $this->model_seller_product->getProducts($filter_data,$seller);
			foreach ($results as $result) {
				$option_data = array();
				$product_options = $this->model_seller_product->getProductOptions($result['product_id'],$this->seller->getId());	
				foreach ($product_options as $product_option) {
					$option_info = $this->model_seller_option->getOption($product_option['option_id'],$this->seller->getId());
					if ($option_info) {
						$product_option_value_data = array();
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_seller_option->getOptionValue($product_option_value['option_value_id']);
							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
									);
							}
						}
						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
							);
					}
				}
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
					);
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function details() {
		if (!$this->seller->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('seller/offer', '', 'SSL');
			$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
		} 
		$this->load->language('seller/offer');
		$this->document->setTitle($this->language->get('heading_title2')); 
		$data['heading_title1'] = $this->language->get('heading_title2');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_upc'] = $this->language->get('entry_upc');
		$data['entry_ean'] = $this->language->get('entry_ean');
		$data['entry_jan'] = $this->language->get('entry_jan');
		$data['entry_isbn'] = $this->language->get('entry_isbn');
		$data['entry_mpn'] = $this->language->get('entry_mpn');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_minimum'] = $this->language->get('entry_minimum');
		$data['entry_shipping'] = $this->language->get('entry_shipping');
		$data['entry_date_available'] = $this->language->get('entry_date_available');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_stock_status'] = $this->language->get('entry_stock_status');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_points'] = $this->language->get('entry_points');
		$data['entry_option_points'] = $this->language->get('entry_option_points');
		$data['entry_subtract'] = $this->language->get('entry_subtract');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_weight'] = $this->language->get('entry_weight');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		$data['entry_download'] = $this->language->get('entry_download');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_filter'] = $this->language->get('entry_filter');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_attribute'] = $this->language->get('entry_attribute');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_option'] = $this->language->get('entry_option');
		$data['entry_option_value'] = $this->language->get('entry_option_value');
		$data['entry_required'] = $this->language->get('entry_required');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_priority'] = $this->language->get('entry_priority');
		$data['entry_tag'] = $this->language->get('entry_tag');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_reward'] = $this->language->get('entry_reward');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_recurring'] = $this->language->get('entry_recurring');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_attribute_add'] = $this->language->get('button_attribute_add');
		$data['button_option_add'] = $this->language->get('button_option_add');
		$data['button_option_value_add'] = $this->language->get('button_option_value_add');
		$data['button_discount_add'] = $this->language->get('button_discount_add');
		$data['button_special_add'] = $this->language->get('button_special_add');
		$data['button_image_add'] = $this->language->get('button_image_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_recurring_add'] = $this->language->get('button_recurring_add');
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_attribute'] = $this->language->get('tab_attribute');
		$data['tab_option'] = $this->language->get('tab_option');
		$data['tab_recurring'] = $this->language->get('tab_recurring');
		$data['tab_discount'] = $this->language->get('tab_discount');
		$data['tab_special'] = $this->language->get('tab_special');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_links'] = $this->language->get('tab_links');
		$data['tab_reward'] = $this->language->get('tab_reward');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_openbay'] = $this->language->get('tab_openbay');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');
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
		if (isset($this->error['price'])) {
			$data['error_price'] = $this->error['price'];
		} else {
			$data['error_price'] = "";
		}
		$url = '';
		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}
		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}	
		if (isset($this->request->get['product_id'])) {
			$url .= '&product_id=' . $this->request->get['product_id'];
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
			'href'      => $this->url->link('common/home', '', 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('seller/account','', 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/offer','', 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title1'),
			'href'      => $this->url->link('seller/offer/show',$url, 'SSL')
			);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title2'),
			'href'      => $this->url->link('seller/offer/details',$url, 'SSL')
			);
		if(isset($this->request->get['product_id'])){					
			$data['action'] = $this->url->link('seller/offer/insert',$url, 'SSL');		
			$data['product_id'] = $this->request->get['product_id'];	
		}
		else{		
			$data['action'] = $this->url->link('seller/offer','', 'SSL');
			$data['product_id'] =0;
		}
		$data['insert'] = $this->url->link('seller/extension/insert',  '', 'SSL');
		$data['cancel'] = $this->url->link('seller/offer','', 'SSL');
		$this->load->model('seller/offer');
		if (isset($this->request->get['product_id'])) {
			$product_info = $this->model_seller_offer->getProduct1($this->request->get['product_id']);
		}
		if (isset($this->request->post['meta_title'])) {
			$data['meta_title'] = $this->request->post['meta_title'];
		} elseif (!empty($product_info)) {
			$data['meta_title'] = $product_info['meta_title'];
		} else {
			$data['meta_title'] = '';
		}
		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$data['price'] = $product_info['sprice'];
		} else {
			$data['price'] = '';
		}
		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$data['quantity'] = $product_info['squantity'];
		} else {
			$data['quantity'] = 1;
		}
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($product_info)) {
			$data['status'] = $product_info['status'];
		} else {
			$data['status'] = 1;
		}

		$this->load->model('localisation/tax_class');

		$this->model_localisation_tax_class->getTaxClasses();

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['tax_class_id'])) {
			$data['tax_class_id'] = $this->request->post['tax_class_id'];
		} elseif (!empty($product_info)) {
			$data['tax_class_id'] = $product_info['tax_class_id'];
		} else {
			$data['tax_class_id'] = 0;
		}

		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('seller/attribute');
		
		if (isset($this->request->post['product_attribute'])) {
			
			$product_attributes = $this->request->post['product_attribute'];
		
		}elseif (!empty($product_info)) {
			
			$product_attributes = $this->model_seller_offer->getProductAttributes($this->request->get['product_id']);
		
		} else {
		
			$product_attributes = array();
		
		}
		
		$data['product_attributes'] = array();
		
		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_seller_attribute->getAttribute($product_attribute['attribute_id']);
			if ($attribute_info) {
				$data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
					);
			}
		}
		$this->load->model('account/customer_group');
		$data['customer_groups'] = $this->model_account_customer_group->getCustomerGroups();
		if (isset($this->request->post['product_discount'])) {
			$data['product_discounts'] = $this->request->post['product_discount'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_discounts'] = $this->model_seller_offer->getProductDiscounts($this->request->get['product_id']);
		} else {
			$data['product_discounts'] = array();
		}
		if (isset($this->request->post['product_special'])) {
			$data['product_specials'] = $this->request->post['product_special'];
		} elseif (isset($this->request->get['product_id'])) {
			$data['product_specials'] = $this->model_seller_offer->getProductSpecials($this->request->get['product_id'],$this->seller->getId());
		} else {
			$data['product_specials'] = array();
		}
		if (isset($this->request->post['product_option'])) {
			$product_options = $this->request->post['product_option'];
		} elseif(isset($this->request->get['product_id'])) {
			$product_options = $this->model_seller_offer->getProductOptions($this->request->get['product_id'],$this->seller->getId());			
		} else {
			$product_options = array();
		}			
		$data['product_options'] = array();
		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();
				foreach ($product_option['product_option_value'] as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],						
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']	
						);						
				}
				$data['product_options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'required'             => $product_option['required']
					);				
			} else {
				$data['product_options'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
					);				
			}
		}
		$data['option_values'] = array();
		foreach ($product_options as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (!isset($data['option_values'][$product_option['option_id']])) {
					$data['option_values'][$product_option['option_id']] = $this->model_seller_offer->getOptionValues($product_option['option_id']);
				}
			}
		}									
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/detail_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/detail_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/detail_form.tpl', $data));
		}	
	}
}
?>