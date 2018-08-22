<?php
class ModelReportSellerTransactions extends Model {


	public function getPaymentInfos() {
		$sql = "SELECT v.username AS name, vp.payment_info AS details, vp.payment_amount, vp.payment_date,v.seller_id FROM `" . DB_PREFIX . "seller_payment` vp LEFT JOIN `" . DB_PREFIX . "sellers` v ON (vp.seller_id = v.seller_id) ";
		$sql .= " ORDER BY vp.payment_date DESC LIMIT 10";		
		$query = $this->db->query($sql);		
		return $query->rows;
	}

	public function addPaymentToSellerId($data,$seller_info) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "seller_transaction SET seller_id = '" . (int)$data['seller_id'] . "',
			description = '" . $this->db->escape($data['description']) . "',
			amount = '-" . (float)$data['seller_amount']. "',
			transaction_status ='5', 
			date_added = NOW()");

		$this->language->load('mail/seller');
		$store_name = $this->config->get('config_name');		
		$message  = "You have received ".$this->currency->format($data['seller_amount'], $this->config->get('config_currency'))." credit in your bank"."\n\n";
		$message  .= "Comment:\n\n";
		$message  .= $data['description'];
		$message  .= "\n\n";

		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($seller_info['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($store_name);
		$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}
	
	public function getOrderStatus($order_id) {
		$sql = "SELECT  * FROM " . DB_PREFIX . "order_history oh LEFT JOIN " . DB_PREFIX . "order_status os ON(oh.order_status_id = os.order_status_id) WHERE order_id= '" . $order_id. "'";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}	

	public function getSellerOrders($data = array(),$seller_access) {
		if(empty($seller_access)){$seller_access=0;}
		$sql = "SELECT op.product_id AS product_id, o.date_added AS date, o.order_id AS order_id, o.order_status_id AS order_status, pd.name AS product_name, op.price AS price,op.quantity AS quantity, op.commission AS commission,op.seller_total AS amount, op.total AS total, op.seller_paid_status AS paid_status FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (op.product_id = pd.product_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_eligible_status_id'])) {
			$sql .= " AND op.product_status_id IN (".$data['filter_eligible_status_id'].")";
		} else {
			$sql .= " AND op.product_status_id > '0'";
		}

		if (!empty($data['filter_seller_group'])) {
			$sql .= " AND op.seller_id IN (" . (int)$data['filter_seller_group'] . ")";
		} elseif ($seller_access) {
			$sql .= " AND op.seller_id IN (" . $seller_access . ")";
		}

		if (!empty($data['filter_paid_status'])) {
			$sql .= " AND op.seller_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} elseif (!is_null($data['filter_paid_status']) && $data['filter_seller_group']) {
			$sql .= " AND op.seller_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} else {
			$sql .= " AND op.seller_paid_status = '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= " ORDER BY o.order_id DESC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}	
	
	
	
	
	
	public function getSellersOrders( $data = array(), $seller_access ) {
		
		if( empty( $seller_access ) ){ 
			$seller_access=0; 
		}
		
		$sql = "SELECT 
		st.date_added AS date,
		st.order_id AS order_id,
		st.seller_transaction_id,
		o.order_status_id AS order_status,
		op.name AS product_name,	
		op.model,
		op.price AS price,
		op.quantity AS quantity, 
		st.commission AS commission,
		st.fixed_rate AS fixed_rate,
		op.total AS total,
		st.amount AS amount,
		st.sub_total,
		st.transaction_status AS paid_status 
		FROM " . DB_PREFIX . "seller_transaction st 
		LEFT JOIN " . DB_PREFIX . "order_product op ON (st.order_product_id=op.order_product_id)
		LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = op.order_id) WHERE (st.seller_id IN (" . $seller_access . ") AND op.seller_id IN (" . $seller_access . ") AND op.order_id=st.order_id)  AND o.order_status_id > '0' AND st.transaction_status='0'";
		
		if ( !empty($data['filter_eligible_status_id']) ) {

			$sql .= " AND op.product_status_id IN (".$data['filter_eligible_status_id'].")";
		} else {
			$sql .= " AND op.product_status_id > '0'";
		}
		
		$sql .= " ORDER BY o.order_id DESC";
		
		$query = $this->db->query($sql);

		return $query->rows;
	}

	
	public function getSellerTotalOrders12( $data = array(), $seller_access ) {
		if( empty( $seller_access ) ){
			$seller_access=0;
		}
		$sql = "SELECT COUNT(*) AS total 
		FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id)
		LEFT JOIN `" . DB_PREFIX . "sellers` vds ON (op.seller_id = vds.seller_id)";
		
		if ( !empty($data['filter_order_status_id']) ) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if ( !empty($data['filter_eligible_status_id']) ) {

			$sql .= " AND op.product_status_id IN (".$data['filter_eligible_status_id'].")";

		} else {

			$sql .= " AND op.product_status_id > '0'";

		}

		$sql .= " AND op.seller_id IN (" . $seller_access . ")";

		$sql .= " AND op.seller_paid_status = '5'";
		
		
		$sql .= " group by op.seller_id";
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	
	public function getSellerTotalOrders($data = array(),$seller_access) {
		if(empty($seller_access)){$seller_access=0;}
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o 
		LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) 
		LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (op.product_id = pd.product_id)";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		if (!empty($data['filter_eligible_status_id'])) {
			$sql .= " AND op.product_status_id IN (".$data['filter_eligible_status_id'].")";
		} else {
			$sql .= " AND op.product_status_id > '0'";
		}

		if (!empty($data['filter_seller_group'])) {
			$sql .= " AND op.seller_id IN ('" . (int)$data['filter_seller_group'] . "')";
		} elseif ($seller_access) {
			$sql .= " AND op.seller_id IN (" . $seller_access . ")";
		}
		
		if (!empty($data['filter_paid_status'])) {
			$sql .= " AND op.seller_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} elseif (!is_null($data['filter_paid_status']) && $data['filter_seller_group']) {
			$sql .= " AND op.seller_paid_status = '" . (int)$data['filter_paid_status'] . "'";
		} else {
			$sql .= " AND op.seller_paid_status = '0'";
		}
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$query = $this->db->query($sql);
		
		return $query->row['total'];
	}
	
	
	public function updateorderproduct($seller_id,$opid) {

		$query = $this->db->query("SELECT *	FROM `" . DB_PREFIX . "order` o 
			LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) 
			where op.order_product_id = '" . (int)$opid . "' AND op.seller_id ='" . (int)$seller_id . "' AND 
			op.seller_paid_status = '0' AND o.order_id = op.order_id");
		if ($query->rows) {
			foreach ($query->rows AS $data) {

				$this->db->query("UPDATE " . DB_PREFIX . "order_product  SET seller_paid_status = '5' WHERE order_id = '" . (int)$data['order_id'] . "'"); 
			}

		}

		return 1;
		
	}
	
	public function updatetransaction($seller_id,$amt) {

		if ($seller_id) {

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seller_transaction`  WHERE seller_id = '" . (int)$seller_id . "'");
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "seller_payment SET seller_id = '" . (int)$seller_id . "', 
				payment_info = 'Paypal Payment', payment_amount = '" . (float)$amt . "', payment_status = '5', payment_date = Now()");
			
			if ($query->rows) {
				foreach ($query->rows AS $data) {

					$this->db->query("UPDATE " . DB_PREFIX . "seller_transaction  SET transaction_status = '5' WHERE 
						seller_transaction_id = '" . (int)$data['seller_transaction_id'] . "'");      

				}	
			}
			
			
			
			return 1;
		}
	}
	
	public function getSellerTotalAmount( $data = array(), $seller_access) {
		
		$filter_eligible_status_id = $this->config->get('config_seller_payments');

		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		} else {
			$filter_eligible_status_id = 0;
		}

		if( empty( $seller_access) ){ $seller_access=0; }

		$sql = "SELECT SUM(st.amount) AS seller_amount, SUM(st.commission) AS commission, 
		SUM(op.price)
		AS gross_amount,SUM(op.quantity) AS quantity, st.seller_id AS seller_id,
		CONCAT(vds.firstname, ' ', vds.lastname) AS company,
		GROUP_CONCAT(st.seller_transaction_id) AS seller_transaction_id
		FROM " . DB_PREFIX . "seller_transaction st 
		LEFT JOIN " . DB_PREFIX . "order_product op ON (op.order_product_id=st.order_product_id)
		LEFT JOIN " . DB_PREFIX . "sellers vds ON (st.seller_id=vds.seller_id)
		LEFT JOIN `" . DB_PREFIX . "order` o ON (o.order_id = st.order_id)		
		WHERE ((st.seller_id IN (".$seller_access.") AND o.order_status_id IN (".$filter_eligible_status_id.") AND st.transaction_status IN (".$filter_eligible_status_id.")) OR (st.seller_id IN (".$seller_access.") AND st.order_id=0))";
		$sql .= " group by st.seller_id";
			
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getSellerpaidTotalAmount123($data = array(),$seller_access) {
		if(empty($seller_access)){$seller_access=0;}
		
		$sql = "SELECT  SUM(st.amount) AS seller_amount, SUM(st.commission) AS commission, 
		st.seller_id AS seller_id,
		CONCAT(vds.firstname, ' ', vds.lastname) AS company,
		vds.paypal_email AS paypal_email
		FROM " . DB_PREFIX . "seller_transaction st 
		LEFT JOIN " . DB_PREFIX . "sellers vds ON (st.seller_id=vds.seller_id)
		WHERE st.seller_id IN (" . $seller_access . ")  AND st.order_id=0 ";
		
		
		$sql .= " group by st.seller_id";
		
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getSellerpaidTotalAmount12($data = array(),$seller_access) {
		if(empty($seller_access)){$seller_access=0;}
		
		$sql = "SELECT  SUM(st.amount) AS seller_amount, SUM(st.commission) AS commission, 
		st.seller_id AS seller_id,
		CONCAT(vds.firstname, ' ', vds.lastname) AS company,
		vds.paypal_email AS paypal_email
		FROM " . DB_PREFIX . "seller_transaction st 
		LEFT JOIN " . DB_PREFIX . "sellers vds ON (st.seller_id=vds.seller_id)
		WHERE st.seller_id IN (" . $seller_access . ")  AND st.order_id=0   AND st.transaction_status='5'";
		
		
		$sql .= " group by st.seller_id";
		
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getSellersName1() {
		$sql = "SELECT distinct(seller_id) FROM `" . DB_PREFIX . "sellers`";
		$query = $this->db->query($sql);
		$sellers = array();
		if(count($query->rows)>0){
			foreach($query->rows as $seller){
				$sellers[] = $seller['seller_id'];
			}
		}
		return $sellers;
	}

	public function getSellersName($data = array(),$seller_access) {
		if( empty($seller_access) ){
			$seller_access=0;
		}
		
		$sql = "SELECT distinct(c.seller_id) as seller_id, CONCAT(c.firstname, ' ', c.lastname) AS name FROM `" . DB_PREFIX . "sellers` c , `" . DB_PREFIX . "seller` s where s.seller_id = c.seller_id";

		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getname($seller_id) {
		
		$sql = "SELECT distinct CONCAT(c.firstname, ' ', c.lastname) AS name FROM `" . DB_PREFIX . "sellers` c where seller_id='" . (int)$seller_id . "'";

		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	
	
	
	
	
	
	
	
	
	public function getcustomerinfo($orderid) {
		$sql = "SELECT * from `" . DB_PREFIX . "order` where order_id = '" . (int)$orderid . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function addPaymentToSeller($payments,$order_details) {
		foreach ($payments AS $data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "seller_payment SET seller_id = '" . (int)$data['seller_id'] . "', payment_info = '" . $order_details . "', payment_amount = '" . (float)$data['paid_amount'] . "', payment_status = '5', payment_date = Now()");
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$data['seller_id'] . "', description = 'PayPal Payment', amount = '-" . (float)$data['paid_amount'] . "',transaction_status =5 , date_added = NOW()");
		}
		
		foreach (unserialize($order_details) AS $details) {
			if ($details['order_id']) {
				$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET seller_paid_status = '1' WHERE op.order_id = '" . (int)$details['order_id'] . "' AND op.product_id = '" . (int)$details['product_id'] . "'");
			}
		}
		
	}
	
	public function getPaymentHistory($data = array(),$seller_access) {
		if(empty($seller_access)){$seller_access=0;}
		$sql = "SELECT CONCAT(v.firstname, ' ', v.lastname) AS name, vp.payment_id AS payment_id, vp.payment_info AS details, vp.payment_amount, vp.payment_date FROM `" . DB_PREFIX . "seller_payment` vp LEFT JOIN `" . DB_PREFIX . "customer` v ON (vp.seller_id = v.customer_id) ";

		if  ($seller_access) {
			$sql .= " WHERE v.customer_id IN (" . $seller_access . ")";
		}
		
		$sql .= " ORDER BY vp.payment_date DESC LIMIT 10";
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function removeHistory($payment_id) {
		if ($payment_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "vendor_payment` vp WHERE vp.payment_id = '" . (int)$payment_id . "'");
			
			if ($query->row['payment_info']) {
				foreach (unserialize($query->row['payment_info']) AS $payment_details) {
					$this->db->query("UPDATE " . DB_PREFIX . "order_product op SET vendor_paid_status = '0' WHERE op.order_id = '" . (int)$payment_details['order_id'] . "' AND op.product_id = '" . (int)$payment_details['product_id'] . "'");
				}
				$this->db->query("DELETE FROM " . DB_PREFIX . "vendor_payment WHERE payment_id = '" . (int)$payment_id . "'");
			}
		}
	}
	
	public function editProduct($product_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['model']) . "', sku = '" . $this->db->escape($data['sku']) . "', upc = '" . $this->db->escape($data['upc']) . "', location = '" . $this->db->escape($data['location']) . "', quantity = '" . (int)$data['quantity'] . "', minimum = '" . (int)$data['minimum'] . "', subtract = '" . (int)$data['subtract'] . "', stock_status_id = '" . (int)$data['stock_status_id'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', manufacturer_id = '" . (int)$data['manufacturer_id'] . "', shipping = '" . (int)$data['shipping'] . "', price = '" . (float)$data['price'] . "', points = '" . (int)$data['points'] . "', weight = '" . (float)$data['weight'] . "', weight_class_id = '" . (int)$data['weight_class_id'] . "', length = '" . (float)$data['length'] . "', width = '" . (float)$data['width'] . "', height = '" . (float)$data['height'] . "', length_class_id = '" . (int)$data['length_class_id'] . "', status = '" . (int)$data['status'] . "', tax_class_id = '" . $this->db->escape($data['tax_class_id']) . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");
	}
	public function getTransactions($data = array(),$seller_id) {
		$filter_eligible_status_id = $this->config->get('config_seller_payments');
		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		}else{
			$filter_eligible_status_id = 0;
		}

		$sql = "SELECT  distinct(seller_transaction_id),st.seller_id,st.order_id,st.order_product_id,st.description,st.amount,st.commission,
		st.sub_total,st.date_added,st.transaction_status FROM " . DB_PREFIX . "seller_transaction st LEFT JOIN " . DB_PREFIX . "order_product op ON (st.seller_id=op.seller_id) WHERE (st.seller_id = '" . (int)$seller_id. "' AND op.seller_id= '" . (int)$seller_id. "' AND op.order_id=st.order_id AND op.product_status_id IN (".$filter_eligible_status_id.")) OR (st.seller_id = '" . (int)$seller_id. "' AND st.order_id=0)";

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) = '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		$sort_data = array(
			'amount',
			'description',
			'seller_transaction_id',
			'date_added'
			);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTransactionDetails($order_product_id) {
		$sql = "SELECT * FROM 
		" . DB_PREFIX . "order_product  WHERE order_product_id= '" . $order_product_id. "'";
		$query = $this->db->query($sql);
		return $query->row;
	}	
	public function getPending($seller_id) {
		$filter_eligible_status_id = $this->config->get('config_seller_payments');
		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		} else {
			$filter_eligible_status_id = 0;
		}
		$query = $this->db->query("SELECT SUM(st.amount) AS pamount FROM " . DB_PREFIX . "seller_transaction st LEFT JOIN `" . DB_PREFIX . "order` o ON (st.order_id=o.order_id) WHERE (st.seller_id = '" . (int)$seller_id. "' AND o.order_status_id IN (".$filter_eligible_status_id.") AND st.transaction_status IN (".$filter_eligible_status_id.")) OR (st.seller_id = '" .(int)$seller_id. "' AND st.order_id=0)");
		return $query->row;
	}
	
	
	
	
	public function getTotalTransactions($data,$seller_id ) {
		$filter_eligible_status_id = $this->config->get('config_seller_payments');
		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		}else{
			$filter_eligible_status_id = 0;
		}

		$query = $this->db->query("SELECT distinct(seller_transaction_id) FROM " . DB_PREFIX . "seller_transaction st INNER JOIN " . DB_PREFIX . "order_product op ON (st.seller_id=op.seller_id) WHERE (st.seller_id = '" . (int)$seller_id . "' AND op.seller_id= '" . (int)$seller_id . "' AND op.order_id=st.order_id AND op.product_status_id IN (".$filter_eligible_status_id.") AND st.transaction_status IN (".$filter_eligible_status_id.")) OR (st.seller_id = '" . (int)$seller_id . "' AND st.order_id=0)");
		return $query->num_rows;
	}	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>
