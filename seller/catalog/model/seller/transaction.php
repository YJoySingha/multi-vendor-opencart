<?php
class ModelSellerTransaction extends Model {	
	public function getTransactions($data = array()) {
		$filter_eligible_status_id = $this->config->get('config_seller_payments');
		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		}else{
			$filter_eligible_status_id = 0;
		}
		$sql = "SELECT  distinct(seller_transaction_id),st.seller_id,st.order_id,st.description,st.amount,st.date_added,st.transaction_status,st.sub_total,st.commission FROM " . DB_PREFIX . "seller_transaction st LEFT JOIN " . DB_PREFIX . "order_product op ON (st.seller_id=op.seller_id) WHERE (st.seller_id = '" . (int)$this->seller->getId(). "' AND op.seller_id= '" . (int)$this->seller->getId(). "' AND op.order_id=st.order_id AND op.product_status_id IN (".$filter_eligible_status_id.")) OR (st.seller_id = '" . (int)$this->seller->getId(). "' AND st.order_id=0)";
		 if (!empty($data['filter_order_id'])) {
			$sql .= " AND order_id = '" . (int)$data['filter_order_id'] . "'";
		}
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(date_added) = '" . $this->db->escape($data['filter_date_start']) . "'";
		}
		$sort_data = array(
			'amount',
			'description',
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
	public function getTotalTransactions($data) {
      	$filter_eligible_status_id = $this->config->get('config_seller_payments');
		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		}else{
			$filter_eligible_status_id = 0;
		}
      	$seller_id = $this->seller->getId();
		$query = $this->db->query("SELECT distinct(seller_transaction_id) FROM " . DB_PREFIX . "seller_transaction st INNER JOIN " . DB_PREFIX . "order_product op ON (st.seller_id=op.seller_id) WHERE (st.seller_id = '" . (int)$seller_id . "' AND op.seller_id= '" . (int)$seller_id . "' AND op.order_id=st.order_id AND op.product_status_id IN (".$filter_eligible_status_id.")) OR (st.seller_id = '" . (int)$seller_id . "' AND st.order_id=0)");
		return $query->num_rows;
	}	
	public function getTotalAmount() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM `" . DB_PREFIX . "seller_transaction` WHERE seller_id = '" . (int)$this->seller->getId() . "' GROUP BY seller_id");
		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;	
		}
	}
	public function getSellerOrders($data = array()) {
		$sql = "SELECT op.product_id AS product_id, o.date_added AS date, o.order_id AS order_id, o.order_status_id AS order_status, pd.name AS product_name, op.price AS price,op.quantity AS quantity, op.commision AS commision,op.seller_total AS amount, op.total AS total, op.seller_paid_status AS paid_status FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (op.product_id = pd.product_id) AND op.seller_id = '" . (int)$this->seller->getId() . "' ORDER BY o.order_id DESC";
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
	public function getSellerTotalOrders($data = array(),$seller_access) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) LEFT JOIN `" . DB_PREFIX . "product_description` pd ON (op.product_id = pd.product_id)";
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		if (!empty($data['filter_seller_group'])) {
			$sql .= " AND op.seller_id IN ('" . (int)$data['filter_seller_group'] . "')";
		} elseif ($seller_access) {
			$sql .= " AND op.seller_id IN ('" . $seller_access . "')";
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
	public function getSellerTotalAmount($data = array(),$seller_access) {
		if (empty($data['filter_seller_group'])) {
			$sql = "SELECT SUM(op.seller_total) AS seller_amount, SUM(op.commision) AS commision, SUM(op.total) AS gross_amount FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) LEFT JOIN `" . DB_PREFIX . "seller` vds ON (op.seller_id = vds.seller_id)";
		}
		else{
			$sql = "SELECT SUM(op.seller_total) AS seller_amount, SUM(op.commision) AS commision, SUM(op.total) AS gross_amount, op.seller_id ,CONCAT(vds.firstname, ' ', vds.lastname) AS company, vds.paypal_email AS paypal_email FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "order_product` op ON (o.order_id = op.order_id) LEFT JOIN `" . DB_PREFIX . "seller` vds ON (op.seller_id = vds.seller_id)";
		}
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		if (!empty($data['filter_seller_group'])) {
			$sql .= " AND op.seller_id IN ('" . (int)$data['filter_seller_group'] . "')";
		} elseif ($seller_access) {
			$sql .= " AND op.seller_id IN ('" . $seller_access . "')";
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
		if (!empty($data['filter_seller_group'])) {
			$sql .= " group by op.seller_id";
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getPending($seller_id) {
		$filter_eligible_status_id = $this->config->get('config_seller_payments');
		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		}else{
			$filter_eligible_status_id = 0;
		}
		$query = $this->db->query("SELECT SUM(st.amount) AS pamount FROM " . DB_PREFIX . "seller_transaction st LEFT JOIN `" . DB_PREFIX . "order` o ON (st.order_id=o.order_id) WHERE (st.seller_id = '" . (int)$seller_id. "' AND o.order_status_id IN (".$filter_eligible_status_id.") AND st.transaction_status IN (".$filter_eligible_status_id.")) OR (st.seller_id = '" .(int)$seller_id. "' AND st.order_id=0)");
		return $query->row;
	}
	public function getSellerTotalSales($seller_id) {
		$sql = "SELECT SUM(op.price) as totalSales FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` ord ON(ord.order_id = op.order_id) WHERE op.seller_id = '" .(int)$seller_id. "' AND ord.order_status_id >0";
		$query = $this->db->query($sql);
		return $query->row;
	}
}
?>
