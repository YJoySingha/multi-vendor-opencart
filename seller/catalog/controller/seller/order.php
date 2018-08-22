<?php 
class ControllerSellerOrder extends Controller {

	private $error = array();

	public function index() {

    	if (!$this->seller->isLogged()) {
      		$this->session->data['redirect'] = $this->url->link('seller/order', '', 'SSL');
	  		$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
    	}

		$this->language->load('seller/order');

		$this->load->model('seller/order');

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end ='';
		}

        if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}
		
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

 		$data['order_statuses'] = $this->model_seller_order->getOrderStatuses(array());

		if (isset($this->request->get['order_id'])) {

			$order_info = $this->model_seller_order->getOrder($this->request->get['order_id']);
			if ($order_info) {

				$order_products = $this->model_seller_order->getOrderProducts($this->request->get['order_id']);

				foreach ($order_products as $order_product) {

					$option_data = array();

					$order_options = $this->model_seller_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);

					foreach ($order_options as $order_option) {

						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];

						} elseif ($order_option['type'] == 'checkbox') {

							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];

						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {

							$option_data[$order_option['product_option_id']] = $order_option['value'];	
						} elseif ($order_option['type'] == 'file') {

							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
						}
					}
					$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->request->get['order_id']);
					$this->cart->add($order_product['product_id'], $order_product['quantity'], $option_data);
				}
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}
    	$this->document->setTitle($this->language->get('heading_title'));
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
		$url = '';
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('seller/order', $url, 'SSL'),        	
        	'separator' => $this->language->get('text_separator')
      	);
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_products'] = $this->language->get('text_products');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');
		$data['button_view'] = $this->language->get('button_view');
		$data['button_reorder'] = $this->language->get('button_reorder');
		$data['button_continue'] = $this->language->get('button_continue');
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$data1 = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_order_id'	     => $filter_order_id, 
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$data['orders'] = array();

		$order_total = $this->model_seller_order->getTotalOrders($data1);

		$results = $this->model_seller_order->getOrders($data1);

		foreach ( $results as $result ) {

			$product_total = $this->model_seller_order->getTotalOrderProductsByOrderId($result['order_id']);

			$voucher_total = $this->model_seller_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$prodtotal = $this->model_seller_order->getOrderProductsSum($result['order_id']);

			$orderstatus = $this->model_seller_order->getOrderStatus($result['order_id']);

			if( $orderstatus ){
				$order_status_id = $orderstatus['order_status_id'];

				$StatusName = $this->model_seller_order->getOrderStatusName($order_status_id);

			} else {
				$StatusName = $result['status'];
			}

			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $StatusName,
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($prodtotal, $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('seller/order/info', 'order_id=' . $result['order_id'], 'SSL'),
				'invoice'    => $this->url->link('seller/order/invoice', 'order_id=' . $result['order_id'], 'SSL')
			);
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('seller/order', 'page={page}', 'SSL');
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;		
		$data['filter_order_id'] = $filter_order_id;
		$data['pagination'] = $pagination->render();
		$data['continue'] = $this->url->link('seller/account', '', 'SSL');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/order_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/order_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/order_list.tpl', $data));
		}				
	}

	public function info() {

		$this->language->load('seller/order');

		$this->load->model('seller/order');

		if (isset($this->request->get['order_id'])) {

			$order_id = $this->request->get['order_id'];

		} else {

			$order_id = 0;
		}

		$data['action'] = $this->url->link('seller/order/info','order_id=' . (int)$this->request->get['order_id'], 'SSL');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {

			$this->model_seller_order->addOrderHistory($this->request->get['order_id'], $this->request->post);

		}

		$order_statuses = $this->model_seller_order->getOrderStatuses(array());

		$config_seller_orderstatuses = $this->config->get('config_seller_orderstatuses');

		$data['order_statuses'] = array();

		foreach( $order_statuses as $order_status ){

			if (in_array($order_status['order_status_id'], $config_seller_orderstatuses)){
				$data['order_statuses'][] = $order_status;
			}

		}

		$order_info = $this->model_seller_order->getOrder($order_id);

		if (isset($this->request->post['order_status_id'])) {

      		$data['order_status_id'] = $this->request->post['order_status_id'];

    	} elseif ( !empty($order_info) ) {

			$orderstatus = $this->model_seller_order->getOrderStatus($order_id);

			if( $orderstatus ){

				$data['order_status_id'] = $orderstatus['order_status_id'];

			} else {

				$data['order_status_id'] = $order_info['order_status_id'];
			}

		} else {

      		$data['order_status_id'] = '';
    	}

		$data['invoice'] = $this->url->link('seller/order/invoice','order_id=' . (int)$this->request->get['order_id'], 'SSL');

		if (!$this->seller->isLogged()) {

			$this->session->data['redirect'] = $this->url->link('seller/order/info', 'order_id=' . $order_id, 'SSL');
			$this->response->redirect($this->url->link('seller/login', '', 'SSL'));

    	}

		if ( $order_info ) {

			$this->document->setTitle($this->language->get('text_order'));
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
			$url = '';
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('seller/order', $url, 'SSL'),      	
				'separator' => $this->language->get('text_separator')
			);
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_order'),
				'href'      => $this->url->link('seller/order/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL'),
				'separator' => $this->language->get('text_separator')
			);
      		$data['heading_title'] = $this->language->get('text_order');
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
    		$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
      		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
      		$data['text_payment_method'] = $this->language->get('text_payment_method');
      		$data['text_payment_address'] = $this->language->get('text_payment_address');
      		$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');
      		$data['column_name'] = $this->language->get('column_name');
      		$data['column_model'] = $this->language->get('column_model');
      		$data['column_quantity'] = $this->language->get('column_quantity');
      		$data['column_price'] = $this->language->get('column_price');
      		$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
      		$data['column_status'] = $this->language->get('column_status');
      		$data['column_comment'] = $this->language->get('column_comment');
			$data['button_return'] = $this->language->get('button_return');
      		$data['button_continue'] = $this->language->get('button_continue');

			if ($order_info['invoice_no']) {

				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];

			} else {
				$data['invoice_no'] = '';
			}

			$data['order_id'] = $this->request->get['order_id'];

			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
      			$format = $order_info['payment_address_format'];
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
	  			'firstname' => $order_info['payment_firstname'],
	  			'lastname'  => $order_info['payment_lastname'],
	  			'company'   => $order_info['payment_company'],
      			'address_1' => $order_info['payment_address_1'],
      			'address_2' => $order_info['payment_address_2'],
      			'city'      => $order_info['payment_city'],
      			'postcode'  => $order_info['payment_postcode'],
      			'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
      			'country'   => $order_info['payment_country']  
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

      		$data['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
      			$format = $order_info['shipping_address_format'];
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
	  			'firstname' => $order_info['shipping_firstname'],
	  			'lastname'  => $order_info['shipping_lastname'],
	  			'company'   => $order_info['shipping_company'],
      			'address_1' => $order_info['shipping_address_1'],
      			'address_2' => $order_info['shipping_address_2'],
      			'city'      => $order_info['shipping_city'],
      			'postcode'  => $order_info['shipping_postcode'],
      			'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
      			'country'   => $order_info['shipping_country']  
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];

			$data['products'] = array();

			$products = $this->model_seller_order->getOrderProducts($this->request->get['order_id']);

      		foreach ($products as $product) {

				$option_data = array();

				$options = $this->model_seller_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

         		foreach ($options as $option) {

          			if ($option['type'] != 'file') {

						$value = $option['value'];

					} else {

						$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));

					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);					
        		}

        		$data['products'][] = array(
          			'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'quantity' => $product['quantity'],
          			'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'return'   => $this->url->link('seller/return/insert', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
        		);
      		}
			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_seller_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {

				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);

			}
			// Totals
			$data['totals'] = array();

			$totals = $this->model_seller_order->getOrderTotals($this->request->get['order_id']);

			foreach ( $totals as $total ) {

				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			$data['histories'] = array();

			$results = $this->model_seller_order->getOrderHistories($this->request->get['order_id']);

      		foreach ($results as $result) {

        		$data['histories'][] = array(
          			'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
          			'status'     => $result['status'],
          			'comment'    => nl2br($result['comment'])
        		);

      		}

      		$data['continue'] = $this->url->link('seller/order', '', 'SSL');
			$data['column_left'] = $this->load->controller('common/column_left');

			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/seller/order_info.tpl', $data));
			}	

    	} else {
			$this->document->setTitle($this->language->get('text_order'));
      		$data['heading_title'] = $this->language->get('text_order');
      		$data['text_error'] = $this->language->get('text_error');
      		$data['button_continue'] = $this->language->get('button_continue');
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
				'href'      => $this->url->link('seller/order', '', 'SSL'),
				'separator' => $this->language->get('text_separator')
			);
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_order'),
				'href'      => $this->url->link('seller/order/info', 'order_id=' . $order_id, 'SSL'),
				'separator' => $this->language->get('text_separator')
			);

      		$data['continue'] = $this->url->link('seller/order', '', 'SSL');
			$data['column_left'] = $this->load->controller('common/column_left');

			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
		}				
    	}
  	}

	public function invoice() {
		$this->language->load('seller/invoiceorder');
		$data['title'] = $this->language->get('heading_title');
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		$data['direction'] = $this->language->get('direction');
		$data['language'] = $this->language->get('code');
		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_to'] = $this->language->get('text_to');
		$data['text_company_id'] = $this->language->get('text_company_id');
		$data['text_tax_id'] = $this->language->get('text_tax_id');		
		$data['text_ship_to'] = $this->language->get('text_ship_to');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_comment'] = $this->language->get('column_comment');
		$this->load->model('seller/order');
		$this->load->model('seller/setting');
		$data['orders'] = array();
		$orders = array();
		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}
		$this->load->model('catalog/seller');
		$this->load->model('seller/address');
		foreach ($orders as $order_id) {
			$order_info = $this->model_seller_order->getOrder($order_id);
			if ($order_info) {
				$store_info = $this->model_seller_setting->getSetting('config', $order_info['store_id']);
				$seller_info = $this->model_catalog_seller->getSeller($this->seller->getId());
				 $data['addresses'] = array();
		        $results = $this->model_seller_address->getAddresses12($this->seller->getId());
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
					$addresses = array(
					'address_id' => $result['address_id'],
					'address'    => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
					'update'     => $this->url->link('seller/address/update', 'address_id=' . $result['address_id'], 'SSL'),
					'delete'     => $this->url->link('seller/address/delete', 'address_id=' . $result['address_id'], 'SSL')
					);
					}
				if ($seller_info) {
					$store_address = $addresses['address'];
					$store_email = $seller_info['email'];
					$store_telephone = $seller_info['telephone'];
					$store_fax = $seller_info['fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
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
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);
				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
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
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);
				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				$product_data = array();
				$products = $this->model_seller_order->getOrderProducts($order_id);
				foreach ($products as $product) {
					$option_data = array();
					$options = $this->model_seller_order->getOrderOptions($order_id, $product['order_product_id']);
					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));
						}
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);								
					}
					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}
				$voucher_data = array();
				$vouchers = $this->model_seller_order->getOrderVouchers($order_id);
				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])			
					);
				}
					$total_data = array();
				$totals = $this->model_seller_order->getOrderTotals($order_id);
foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
					);
				}
				$data['orders'][] = array(
					'order_id'	         => $order_id,
					'invoice_no'         => $invoice_no,
					'date_added'         => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'         => $order_info['store_name'],
					'store_url'          => rtrim($order_info['store_url'], '/'),
					'store_address'      => nl2br($store_address),
					'store_email'        => $store_email,
					'store_telephone'    => $store_telephone,
					'store_fax'          => $store_fax,
					'email'              => $order_info['email'],
					'telephone'          => $order_info['telephone'],
					'shipping_address'   => $shipping_address,
					'shipping_method'    => $order_info['shipping_method'],
					'payment_address'    => $payment_address,
					'payment_company_id' => '',
					'payment_tax_id'     => '',
					'payment_method'     => $order_info['payment_method'],
					'product'            => $product_data,
					'voucher'            => $voucher_data,
					'total'              => $total_data,
					'comment'            => nl2br($order_info['comment'])
				);
			}
		}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = 
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/order_invoice.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/order_invoice.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/order_invoice.tpl', $data));
		}
	}
}
?>