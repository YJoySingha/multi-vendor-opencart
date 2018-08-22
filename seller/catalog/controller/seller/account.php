<?php 
class ControllerSellerAccount extends Controller { 

	public function index() {
		$this->load->model('seller/order');
		if (!$this->seller->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('seller/account', '', 'SSL');
			$this->response->redirect($this->url->link('seller/login', '', 'SSL'));
		} 
		$this->language->load('seller/account');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['logged'] =  $this->seller->isLogged();
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
			); 
		$data['breadcrumbs'][] = array(       	
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('seller/account', '', 'SSL')
			);
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_my_account'] = $this->language->get('text_my_account');
		$data['text_my_orders'] = $this->language->get('text_my_orders');
		$data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_reward'] = $this->language->get('text_reward');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_messagebox'] = $this->language->get('text_messagebox');
		$data['text_checkmessage'] = $this->language->get('text_checkmessage');
		$data['edit'] = $this->url->link('seller/edit', '', 'SSL');
		$data['password'] = $this->url->link('seller/password', '', 'SSL');
		$data['currency_code']= $this->session->data['currency'];
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_view'] = $this->language->get('button_view');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_recent_activity'] = $this->language->get('text_recent_activity');
		$data['text_seller_recent_orders'] = $this->language->get('text_seller_recent_orders');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_comment'] = $this->language->get('column_comment');
		$address_id  = $this->seller->getAddressId();		
		$seller_id  = $this->seller->getId();		
		$data['smembership_module_status'] = $this->config->get('membership_status');
		if ($this->config->get('membership_status')=='1'){
			$data['text_plan'] = $this->language->get('text_plan');
			$data['plan'] = $this->url->link('seller/plan', '', 'SSL');
		}
		$data['text_images'] = $this->language->get('text_images');
		$data['images'] = $this->url->link('seller/uploadimages', 'seller_id=' . $seller_id, 'SSL');
		$data['address'] = $this->url->link('seller/address/update', 'address_id=' . $address_id, 'SSL');
		$data['text_export'] = $this->language->get('text_export');
		$data['export'] = $this->url->link('seller/smartexportimport','', 'SSL');
		$data['wishlist'] = $this->url->link('seller/wishlist');
		$data['order'] = $this->url->link('seller/order', '', 'SSL');
		$data['download'] = $this->url->link('seller/download', '', 'SSL');
		$data['return'] = $this->url->link('seller/return', '', 'SSL');
		$data['transaction'] = $this->url->link('seller/transaction', '', 'SSL');
		$data['newsletter'] = $this->url->link('seller/newsletter', '', 'SSL');
		$data['orders_link'] = $this->url->link('seller/order', '', 'SSL');
		$data['products_link'] = $this->url->link('seller/product', '', 'SSL');
		$data['messagebox'] = $this->url->link('seller/messages', '', 'SSL');
		$data['text_plan'] = $this->language->get('text_plan');
		$data['text_my_plan'] = $this->language->get('text_my_plan');
		$data['plan'] = $this->url->link('seller/plan', '', 'SSL');
		$data['offer'] = $this->url->link('seller/offer', '', 'SSL');	
		$data['attributes'] = $this->url->link('seller/attribute', '', 'SSL');	
		$data['text_attributes'] = $this->language->get('text_attributes');	
		$data['text_offer'] = $this->language->get('text_offer');	
         // Last 5 Orders
		$data['orders'] = array();
		$filter_data = array(
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
			);
		$data['order_total'] = $this->model_seller_order->getTotalOrders();
		$results = $this->model_seller_order->getOrders($filter_data);

		foreach ($results as $result) {
			$product_total = $this->model_seller_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_seller_order->getTotalOrderVouchersByOrderId($result['order_id']);	
			$prodtotal = $this->model_seller_order->getOrderProductsSum($result['order_id']);
			$orderstatus = $this->model_seller_order->getOrderStatus($result['order_id']);
			if($orderstatus){
				$order_status_id = $orderstatus['order_status_id'];
				$StatusName = $this->model_seller_order->getOrderStatusName($order_status_id);
			} else {
				$StatusName = $result['status'];
			}

			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'customer'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $StatusName,
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($prodtotal, $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('seller/order/info', 'order_id=' . $result['order_id'], 'SSL'),
				'invoice'    => $this->url->link('seller/order/invoice', 'order_id=' . $result['order_id'], 'SSL')
				);
		}
		//transactions
		$this->load->model('seller/transaction');
		$data['transactions'] = array();
		$filter_data = array(		
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
			);
		$results = $this->model_seller_transaction->getTransactions($filter_data);
 		//var_dump($results);
		foreach ($results as $result) {
			$data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),								'sub_total'         => $this->currency->format($result['sub_total'], $this->config->get('config_currency')),								'commission'         => $this->currency->format($result['commission'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'order_id' => $result['order_id'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
		}
		//balance
		$this->load->model('seller/seller');
		//transactions
		$this->load->model('seller/transaction');
		$data['transaction_total'] = $this->currency->format($this->model_seller_seller->getTransactionTotal($this->seller->getId()), $this->config->get('config_currency'));
		$pending = $this->model_seller_transaction->getPending($this->seller->getId());
		$data['balance'] = $this->currency->format($pending['pamount'],$this->config->get('config_currency'));
		//get total products
		$this->load->model('seller/product');
		$data['seller_total_products'] = $this->model_seller_product->getTotalProducts1();
		$seller_total_sale = $this->model_seller_transaction->getSellerTotalSales($this->seller->getId());
		$data['seller_total_sales'] = $this->currency->format($seller_total_sale['totalSales'],$this->config->get('config_currency'));

		$data['option'] = $this->url->link('seller/option', '', 'SSL');
		$data['category'] = $this->url->link('seller/category', '', 'SSL');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_category'] = $this->language->get('text_category');	
		$data['text_download'] = $this->language->get('text_download');
		if ($this->config->get('reward_status')) {
			$data['reward'] = $this->url->link('seller/reward', '', 'SSL');
		} else {
			$data['reward'] = '';
		}
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller/account.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller/account.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller/account.tpl', $data));
		}
	}
}
?>