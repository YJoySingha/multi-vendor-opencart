<?php
class ControllerReportSellerTransactions extends Controller {
	
	private $error = array();
	
	public function index() {  
		
		$this->load->language('report/seller_transactions');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('report/seller_transactions');
		
		if (isset($this->request->get['filter_date_start'])) {
		
			$filter_date_start = $this->request->get['filter_date_start'];
		
		} else {
		
			$filter_date_start = '';
		
		}
		
		if (isset($this->request->get['filter_date_end'])) {
		
			$filter_date_end = $this->request->get['filter_date_end'];
		
		} else {
		
			$filter_date_end = '';
		
		}
		
		if (isset($this->request->get['filter_seller_group'])) {
		
			$filter_seller_group = $this->request->get['filter_seller_group'];
		
		} else {
		
			$filter_seller_group = 0;
		
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
		
			$filter_paid_status = $this->request->get['filter_paid_status'];
		
		} else {
		
			$filter_paid_status = 0;
		
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
		
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		
		} else {
		
			$filter_order_status_id = 0;
		
		}	
		
		if (isset($this->request->get['filter_eligible_status_id'])) {
		
			$filter_eligible_status_id = $this->request->get['filter_eligible_status_id'];
		
		}  elseif(!is_null($this->config->get('config_seller_payments'))) {
            		
			$filter_eligible_status_id = implode(",",$this->config->get('config_seller_payments'));
            		//var_dump($this->config->get('config_seller_payments'));
        	
		}else{
		
			$filter_eligible_status_id = 0;
		
		}
		
		if (isset($this->request->get['page'])) {
		
			$page = $this->request->get['page'];
		
		} else {
		
			$page = 1;
		
		}
		
		$url = '';
		
		if (isset($this->request->get['filter_date_start'])) {
		
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		
		}
		
		if (isset($this->request->get['filter_date_end'])) {
		
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		
		}
		
		if (isset($this->request->get['filter_seller_group'])) {
		
			$url .= '&filter_seller_group=' . $this->request->get['filter_seller_group'];
		
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
		
			$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
		
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
		
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		
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
			'href'      => $this->url->link('report/seller_transactions', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'),
			'separator' => ' :: '
			);
		$data['isseller'] = false;

		$seller_access = $this->model_report_seller_transactions->getSellersName1();

		$seller_access = implode(",",$seller_access);

		$this->load->model('report/seller_transactions');

		$data['orders']    = array();

		$data['incomes']   = array();

		$data1 = array(
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_seller_group'    => $filter_seller_group,
			'filter_paid_status'     => $filter_paid_status,
			'filter_eligible_status_id' => $filter_eligible_status_id,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
			);

		$balances = $this->model_report_seller_transactions->getSellerTotalAmount($data1,$seller_access);

		foreach ($balances as $balance) {	
			$data['incomes'][] = array (
				'seller_id'			=> $balance['seller_id'],
				'seller_transaction_id'=> $balance['seller_transaction_id'],
				'company'			=> $balance['company'],				
				'quantity'			=> $balance['quantity'],
				'paid_amount'		=> $balance['seller_amount'],
				'amounttopay1'=> $balance['seller_amount'],
				'seller_amount'  	=> $this->currency->format($balance['seller_amount'], $this->config->get('config_currency')),
				'commission'  		=> $this->currency->format($balance['commission'], $this->config->get('config_currency')),
				'gross_amount'  	=> $this->currency->format($balance['gross_amount'], $this->config->get('config_currency')),
				'href'      => $this->url->link('report/seller_transactions/insert', 'seller_id='.$balance['seller_id'].'&seller_amount='.$balance['seller_amount'].'&user_token=' . $this->session->data['user_token'], 'SSL'),
				'transaction' =>$this->url->link('report/seller_transactions/Seller','&seller_id='.$balance['seller_id']. '&user_token=' . $this->session->data['user_token'],'SSL')
				);
		}
		$data['paidincomes']  = array();
		$paidbalances = $this->model_report_seller_transactions->getSellerpaidTotalAmount123($data1,$seller_access);

		$paid_total = count($paidbalances);

		foreach ($paidbalances AS $pbalance) {
			$data['paidincomes'][] = array (
				'seller_id'			=> $pbalance['seller_id'],
				'paypal_email'		=> $pbalance['paypal_email'],
				'company'			=> $pbalance['company'],
				'paid_amount'		=> $pbalance['seller_amount'],				
				'seller_amount'  	=> $this->currency->format($pbalance['seller_amount'], $this->config->get('config_currency'))
				);
		}
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_all_status'] = $this->language->get('text_all_status');
		$data['text_all_sellers'] = $this->language->get('text_all_sellers');
		$data['text_gross_incomes'] = $this->language->get('text_gross_incomes');
		$data['text_commision'] = $this->language->get('text_commision');
		$data['text_seller_earning'] = $this->language->get('text_seller_earning');
		$data['text_payment_history'] = $this->language->get('text_payment_history');
		$data['text_seller_payment_history'] = $this->language->get('text_seller_payment_history');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['column_buyer_id'] = $this->language->get('column_buyer_id');
		$data['column_buyer_name'] = $this->language->get('column_buyer_name');
		$data['column_buy_date'] = $this->language->get('column_buy_date');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_product_name'] = $this->language->get('column_product_name');
		$data['column_unit_price'] = $this->language->get('column_unit_price');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_commision'] = $this->language->get('column_commision');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_transaction_status'] = $this->language->get('column_transaction_status');
		$data['column_paid_status'] = $this->language->get('column_paid_status');
		$data['column_seller_name'] = $this->language->get('column_seller_name');
		$data['column_seller_id'] = $this->language->get('column_seller_id');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_payment_amount'] = $this->language->get('column_payment_amount');
		$data['column_payment_date'] = $this->language->get('column_payment_date');
		$data['column_order_product'] = $this->language->get('column_order_product');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_group'] = $this->language->get('entry_group');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['button_Paypal'] = $this->language->get('button_Paypal');
		$data['button_addPayment'] = $this->language->get('button_addPayment');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_seller_group'])) {
			$url .= '&filter_seller_group=' . $this->request->get['filter_seller_group'];
		}
		
		if (isset($this->request->get['filter_paid_status'])) {
			$url .= '&filter_paid_status=' . $this->request->get['filter_paid_status'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		$pagination = new Pagination();
		
		$pagination->total = $paid_total;
		
		$pagination->page = $page;
		
		$pagination->limit = $this->config->get('config_admin_limit');
		
		$pagination->text = $this->language->get('text_pagination');
		
		$pagination->url = $this->url->link('report/seller_transactions', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');
		
		$data['pagination'] = $pagination->render();	
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($paid_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($paid_total - $this->config->get('config_limit_admin'))) ? $paid_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $paid_total, ceil($paid_total / $this->config->get('config_limit_admin')));
		
		$data['filter_date_start'] = $filter_date_start;
		
		$data['filter_date_end'] = $filter_date_end;	
		
		$data['filter_seller_group'] = $filter_seller_group;	
		
		$data['filter_paid_status'] = $filter_paid_status;		
		
		$data['filter_order_status_id'] = $filter_order_status_id;

		$data['addPayment'] = $this->url->link('report/seller_transactions/insert', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('report/seller_transactions', $data));
	}

	public function insert() {

		$this->load->language('report/seller_transactions');

		$this->document->setTitle('Pay to seller'); 

		$data['heading_title'] = 'Pay to seller';

		$this->load->model('report/seller_transactions');

		$this->load->model('sale/seller');

		if( isset( $this->session->data['user_token'] ) ) {

			$data['user_token'] = $this->session->data['user_token'];

		} else {

			$data['user_token'] = '';

		}
		$url = '';
		if (isset($this->error['warning'])) {

			$data['error_warning'] = $this->error['warning'];

		} else {
			$data['error_warning'] = '';

		}

		if( isset( $this->request->get['seller_transaction_ids'] ) ) {

			$data['seller_transaction_ids'] = $this->request->get['seller_transaction_ids'];
		
		} else {

			$data['seller_transaction_ids'] = '';
		
		}

		if( isset( $this->request->get['seller_amount'] ) ) {

			$data['seller_amount'] = $this->request->get['seller_amount'];
		
		} elseif( isset( $this->request->post['seller_amount'] ) ) {

			$data['seller_amount'] = $this->request->post['seller_amount'];;
		
		} else {

			$data['seller_amount'] = '';
		
		}

		if ( isset( $this->request->get['seller_transaction_ids'] ) ) {

			$url .= '&seller_transaction_ids=' . $this->request->get['seller_transaction_ids'];
		
		}

		if ( isset( $this->request->get['seller_amount'] ) ) {

			$url .= '&seller_amount=' . $this->request->get['seller_amount'];
		
		}

		if ( isset( $this->request->get['seller_id'] ) ) {

			$url .= '&seller_id=' . $this->request->get['seller_id'];
		
		}
		
		$data['cancel'] = $this->url->link('report/seller_transactions', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		
		$data['insert'] = $this->url->link('report/seller_transactions/insert', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		
		if ( ( $this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate() ) {

			$seller_info = $this->model_sale_seller->getSeller($this->request->post['seller_id']);
			
			$this->model_report_seller_transactions->addPaymentToSellerId($this->request->post,$seller_info);
			
			$this->response->redirect($this->url->link('report/seller_transactions', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		
		}
		
		if ( isset( $this->error['seller_amount'] ) ) {
 			
 			$data['error_seller_amount'] = $this->error['seller_amount'];
		
		} else {

			$data['error_seller_amount'] = '';
		
		}

		if ( isset( $this->error['description'] ) ) {

			$data['error_description'] = $this->error['description'];
		
		} else {

			$data['error_description'] = '';

		}	

		if( isset( $this->request->get['seller_id'] ) ) {

			$data['seller_id'] = $this->request->get['seller_id'];
		
		} else {

			$data['seller_id'] = 0;

		}
		
		$seller_info = $this->model_sale_seller->getSeller($data['seller_id']);	
		
		$data['bank_name']	= "";
		
		$data['account_number']= "";
		
		$data['account_name']	= "";
		
		$data['branch']		= "";
		
		$data['ifsccode']		= "";
		
		$data['telephone']	= "";
		
		$data['business_name']= "";
		
		$data['vat_number']	= "";
		
		if( $seller_info ) {

			$data['bank_name']	= $seller_info['bank_name'];
			
			$data['account_number']=$seller_info['account_number'];
			
			$data['account_name']	= $seller_info['account_name'];
			
			$data['branch']		= $seller_info['branch'];
			
			$data['ifsccode']		= $seller_info['ifsccode'];
			
			$data['telephone']	= $seller_info['telephone'];
			
			$data['name']= $seller_info['firstname']." ".$seller_info['lastname'];
		}

		$data['header'] = $this->load->controller('common/header');

		$data['column_left'] = $this->load->controller('common/column_left');
		
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('report/seller_payment1', $data));			
	}

	public function callback() {	
		
		if ( isset( $this->request->post['custom'] ) ) {

			$sid = explode('#',$this->request->post['custom']);
			
			$order_product_id = explode(',',$sid['0']);
			
			$seller_id = $sid['1'];
		
		} else {
			
			$order_product_id =array();
			
			$seller_id = 0;
		
		}		
		
		$this->load->model('report/seller_transactions');		
		
		if( $order_product_id ) {
			
			foreach ( $order_product_id as $key => $value ) {

				$this->model_report_seller_transactions->updateorderproduct($seller_id,$value);
			
			}

		}	
		
		$url = "";				
		
		$this->model_report_seller_transactions->updatetransaction($seller_id,$this->request->post['mc_gross']);		
		$this->response->redirect($this->url->link('report/seller_transactions', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
	}
	public function removeHistory() {
		$this->language->load('report/seller_transactions');
		$this->load->model('report/seller_transactions');
		$json = array();
		if (!$this->user->hasPermission('modify', 'report/seller_transactions')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['payment_id'])) {
				$this->model_report_seller_transactions->removeHistory($this->request->get['payment_id']);
			}
		}
		$this->response->setOutput(json_encode($json));
	}
	public function Seller() {
		$this->load->language('report/transaction');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => ' :: '
			);
		$data['breadcrumbs'][] = array(
			'text'      => 'Seller Transactions',
			'href'      => $this->url->link('report/seller_transactions', 'user_token=' . $this->session->data['user_token'] , 'SSL'),
			'separator' => ' :: '
			);
		$data['breadcrumbs'][] = array(
			'text'      => 'Your Transactions',
			'href'      => $this->url->link('report/seller_transactions/Seller', 'user_token=' . $this->session->data['user_token'] , 'SSL'),
			'separator' => ' :: '
			);
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
		if (isset($this->request->get['seller_id'])) {
			$seller_id = $this->request->get['seller_id'];
		}
		$this->load->model('report/seller_transactions');
		$results = $this->model_report_seller_transactions->getname($seller_id);
		$data['heading_title'] = $results['name']."'s"."&nbsp;&nbsp;Transactions";
		$as=$results['name']."'s"."&nbsp;&nbsp;Transactions";
		$this->document->setTitle($as);
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_amount'] = sprintf($this->language->get('column_amount'), $this->config->get('config_currency'));
		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');
		$data['button_continue'] = $this->language->get('button_continue');
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$data['transactions'] = array();
		$data1 = array(	
			'filter_date_start'	     => $filter_date_start, 
			'filter_date_end'	     => $filter_date_end, 
			'filter_order_id'	     => $filter_order_id, 		
			'sort'  => 'seller_transaction_id',
			'order' => 'DESC',
			'start' => ($page - 1) * 100,
			'limit' => 100
			);
		$this->load->model('report/seller_transactions');			 
		$pending = $this->model_report_seller_transactions->getPending($seller_id);
		$data['pending'] = $this->currency->format($pending['pamount'], $this->config->get('config_currency'));
		$transaction_total = $this->model_report_seller_transactions->getTotalTransactions($data1,$seller_id);
		$results = $this->model_report_seller_transactions->getTransactions($data1,$seller_id);
		foreach ($results as $result) {
			if($result['order_product_id']>0){
				$details = $this->model_report_seller_transactions->getTransactionDetails($result['order_product_id']);
				$order_status = $this->model_report_seller_transactions->getOrderStatus($result['order_id']);		
				$data['transactions'][] = array(
					'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
					'description' => $result['description'],
					'seller_transaction_id' => $result['seller_transaction_id'],
					'order_id' => $result['order_id'],
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'model' => $details['model'],
					'name' => $details['name'],
					'quantity' => $details['quantity'],
					'invoice_id'  	=> '',				
					'pending'      => $this->currency->format($pending['pamount'], $this->config->get('config_currency')),
					'sub_total'         => $this->currency->format($result['sub_total'], $this->config->get('config_currency')),
					'order_status' => $order_status,
					'weight' => '',
					'unitprice' => $this->currency->format($details['price'], $this->config->get('config_currency')),
					'price' => number_format($details['price'],2),
					'total' => number_format($details['price'],2),
					'price1'         => $this->currency->format($details['price'], $this->config->get('config_currency')),
					'commission1'  	=> $this->currency->format($details['commission'], $this->config->get('config_currency')),
					'total1'			=> $this->currency->format($details['price'], $this->config->get('config_currency')),
					'commission' => number_format($details['commission'],2),
					'fixed_rate' => '',
					'shippingcost' => '',
					'shipping_cost'=>'',
					'marketplace_fee' => '',
					'service_tax' => '',
					'net_pay' => number_format($result['amount'],2)
					);
			} else {
				$data['transactions'][] = array(
					'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
					'description' => $result['description'],
					'seller_transaction_id' => $result['seller_transaction_id'],
					'order_id' => $result['order_id'],
					'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'model' => '',
					'quantity' => 1,
					'invoice_id'  	=> '',
					'pending'      => $this->currency->format($pending['pamount'], $this->config->get('config_currency')),
					'quantity' => 1,
					'sub_total' => $this->currency->format($result['sub_total'], $this->config->get('config_currency')),
					'order_status' => '',
					'weight' => '',
					'unitprice' => '',
					'price' => '',
					'total' => '',
					'total1' => '',
					'name' => '',
					'price1' => '',
					'commission' => '',
					'fixed_rate' => '',
					'shippingcost' => '',
					'shipping_cost'=>'',
					'commission1'=>'',
					'marketplace_fee' => '',
					'service_taxper' => '',
					'service_tax' => '',
					'net_pay' => number_format($result['amount'],2)
					);
			}
		}	
		//var_dump($pending);
		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 100; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/seller_transactions/Seller','&seller_id='.$seller_id.'&user_token=' . $this->session->data['user_token'] . '&page={page}', 'SSL');
		$url = '';
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;		
		$data['filter_order_id'] = $filter_order_id;
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * 100) + 1 : 0, ((($page - 1) * 100) > ($transaction_total - 100)) ? $transaction_total : ((($page - 1) * 100) + 100), $transaction_total, ceil($transaction_total / 100));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('report/transactions', $data));
	}
	protected function validate() {
		$this->load->model('report/seller_transactions');
		$orders = $this->model_report_seller_transactions->getPending($this->request->get['seller_id']);
		$pamount=$orders['pamount'];
		if ($this->request->post['seller_amount']>$pamount) {
			$this->error['seller_amount'] = 'Amount should be less than balance';
		}
		if (empty($this->request->post['description'])) {
			$this->error['description']  = 'Please write some note for Reference';
		}
		return !$this->error;
	}
}
?>
