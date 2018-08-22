<?php
class ModelSellerOffer extends Model {
	public function addoffer($data) {
		$price = $data['price'];
		$product_id = $data['product_id'];
		$this->db->query("INSERT INTO " . DB_PREFIX . "sellers_products SET price='".(float)$price."', 
			seller_id='".(int)$this->seller->getId()."',
			product_id = '" . (int)$data['product_id'] . "',quantity ='" . (int)$data['quantity'] . "'
			date_added = NOW()");
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "',seller_id = '" . (int)$this->seller->getId() . "'");
					$product_option_id = $this->db->getLastId();
					if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "'");
						} 
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}
	}
	public function editoffer($product_id,$data) {		
		$price = $data['price'];
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("UPDATE  " . DB_PREFIX . "sellers_products SET 		
			price='".(float)$price."', 
			seller_id='".(int)$this->seller->getId()."',
			product_id = '" . (int)$data['product_id'] . "',quantity ='" . (int)$data['quantity'] . "'		
			WHERE sproduct_id = '" . (int)$product_id . "'
			");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' AND seller_id = '" . (int)$this->seller->getId() . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND seller_id = '" . (int)$this->seller->getId() . "'");
		if (isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "',seller_id = '" . (int)$this->seller->getId()."'");
					$product_option_id = $this->db->getLastId();
					if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "', seller_id = '" . (int)$this->seller->getId() . "'");
						} 
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "',seller_id = '" . (int)$this->seller->getId()."'");
				}
			}
		}
	}
	public function addProduct($data) {
		$product_id = $data['product_id'];
		$price = $data['price'];	
		$query = $this->db->query("SELECT *  FROM " . DB_PREFIX . "sellers_products  WHERE 
			seller_id = '" . (int)$this->seller->getId() . "'
			AND product_id = '" . (int)$data['product_id'] . "'");
		if ($query->num_rows){
			$this->db->query("UPDATE  " . DB_PREFIX . "sellers_products SET price='".(float)$price."',
				quantity = '" .(float)$data['quantity']. "' WHERE seller_id = '" . (int)$this->seller->getId() . "' AND product_id = '" . (int)$data['product_id'] . "'");
			$this->db->query("UPDATE  " . DB_PREFIX . "product SET quantity = '" .(float)$data['quantity']. "',price='".(float)$price."' WHERE seller_id = '" . (int)$this->seller->getId() . "' AND product_id = '" . (int)$data['product_id'] . "'");
		}else{		
			$this->db->query("INSERT INTO " . DB_PREFIX . "sellers_products SET product_id = '" . (int)$data['product_id'] . "',price='".(float)$price."',seller_id='".(int)$this->seller->getId()."',quantity = '" .(float)$data['quantity']. "',date_added = NOW()");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' AND seller_id='".(int)$this->seller->getId()."'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "' AND seller_id='".(int)$this->seller->getId()."'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND seller_id='".(int)$this->seller->getId()."'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND seller_id='".(int)$this->seller->getId()."'");
		if (isset($data['product_special'])) {
			foreach ($data['product_special'] as $product_special) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_id . "', 
					customer_group_id = '" . (int)$product_special['customer_group_id'] . "', priority = '" . (int)$product_special['priority'] . "', 
					price = '" . (float)$product_special['price'] . "', date_start = '" . $this->db->escape($product_special['date_start']) . "', 
					date_end = '" . $this->db->escape($product_special['date_end']) . "',seller_id='".(int)$this->seller->getId()."'");
			}
		}
		if (isset($data['product_discount'])) {
			foreach ($data['product_discount'] as $product_discount) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_discount SET product_id = '" . (int)$product_id . "', customer_group_id = '" . (int)$product_discount['customer_group_id'] . "', quantity = '" . (int)$product_discount['quantity'] . "', priority = '" . (int)$product_discount['priority'] . "', price = '" . (float)$product_discount['price'] . "', date_start = '" . $this->db->escape($product_discount['date_start']) . "', date_end = '" . $this->db->escape($product_discount['date_end']) . "',seller_id='".(int)$this->seller->getId()."'");
			}
		}
		if(isset($data['product_option'])) {
			foreach ($data['product_option'] as $product_option) {
				if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', required = '" . (int)$product_option['required'] . "',seller_id = '" . (int)$this->seller->getId() . "'");
					$product_option_id = $this->db->getLastId();
					if (isset($product_option['product_option_value']) && count($product_option['product_option_value']) > 0 ) {
						foreach ($product_option['product_option_value'] as $product_option_value) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value_id = '" . (int)$product_option_value['option_value_id'] . "', quantity = '" . (int)$product_option_value['quantity'] . "', subtract = '" . (int)$product_option_value['subtract'] . "', price = '" . (float)$product_option_value['price'] . "', price_prefix = '" . $this->db->escape($product_option_value['price_prefix']) . "', points = '" . (int)$product_option_value['points'] . "', points_prefix = '" . $this->db->escape($product_option_value['points_prefix']) . "', weight = '" . (float)$product_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($product_option_value['weight_prefix']) . "',seller_id = '" . (int)$this->seller->getId() . "'");
						} 
					}else{
						$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_option_id = '".$product_option_id."'");
					}
				} else { 
					$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$product_option['option_id'] . "', option_value = '" . $this->db->escape($product_option['option_value']) . "', required = '" . (int)$product_option['required'] . "'");
				}
			}
		}
	}
	public function getoffer($product_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "seller_product p 
			WHERE p.sproduct_id = '" . (int)$product_id . "'");
		return $query->row;
	}
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, 
			(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') 
			AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd
			ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	}
	public function getProduct1($product_id) {		
		$query = $this->db->query("SELECT DISTINCT *,sp.price as sprice,sp.quantity as squantity 
			FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd
			ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "sellers_products sp
			ON (p.product_id = sp.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND 
			sp.seller_id = '" . (int)$this->seller->getId() . "'
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");	
		return $query->row;
	}
	public function getsellerProduct($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers_products
			WHERE product_id = '" . (int)$product_id . "'
			AND seller_id = '" . (int)$this->seller->getId() . "'");
		return $query->row;
	}
	public function getProducts($data = array()) {
		$sql = "SELECT p.*,pd.* FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd 
		ON (p.product_id = pd.product_id)"; 
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND status=1 AND approve=1"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$sql .= " GROUP BY p.product_id";
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
			);	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getpendingTotalProducts($data = array()) {
		/*code start*/
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_product pd"; 
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND pd.offer_status = '1' AND  pd.approve = '0'
		AND pd.product_id='0'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getpendingProducts($data = array()) {
		$sql = "SELECT pd.* FROM " . DB_PREFIX . "seller_product pd"; 
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND pd.offer_status = '1'  AND  pd.approve = '0'
		AND pd.product_id='0'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$sort_data = array(
			'pd.name',
			'pd.price',
			'pd.status',
			'pd.quantity',
			'pd.date_added'
			);	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
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
	public function getscheduleTotalProducts($data = array()) {
		/*code start*/
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_product pd"; 			
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND pd.offer_status = '3' AND  pd.approve = '1'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getscheduleProducts($data = array()) {
		$sql = "SELECT pd.* FROM " . DB_PREFIX . "seller_product pd"; 
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND pd.offer_status = '3'  AND  pd.approve = '1'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$sort_data = array(
			'pd.name',
			'pd.price',
			'pd.schedule_date_start',
			'pd.quantity',
			'pd.schedule_date_end'
			);	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
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
	public function getarchiveTotalProducts($data = array()) {
		/*code start*/
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_product pd"; 			
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND pd.offer_status = '2' AND  pd.approve = '1'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getarchiveProducts($data = array()) {
		$sql = "SELECT pd.* FROM " . DB_PREFIX . "seller_product pd"; 
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND pd.offer_status = '2'  AND  pd.approve = '1'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$sort_data = array(
			'pd.name',
			'pd.price',
			'pd.quantity'
			);	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
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
	public function getTotalProducts($data = array()) {
		$today = date('Y-m-d');
		/*code start*/
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_product pd"; 
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND  
		pd.approve = '1' AND pd.offer_status = '1' AND pd.date_end < '".$today."'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getinactiveProducts($data = array()) {
		$today = date('Y-m-d');
		$sql = "SELECT pd.* FROM " . DB_PREFIX . "seller_product pd"; 
		$sql .= " WHERE pd.seller_id = '" . (int)$this->seller->getId(). "' AND  pd.approve = '1'
		AND pd.offer_status = '1' AND pd.date_end < '".$today."'"; 
		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		$sort_data = array(
			'pd.name',
			'pd.price',
			'pd.status',
			'pd.quantity'
			);	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.name";	
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
	public function getProductsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");
		return $query->rows;
	} 
	/*code start*/
	public function getVendorsByVendorId($seller_id) {
		$data = array();
		$query = $this->db->query("SELECT *, CONCAT(v.firstname, ' ',v.lastname) AS vname, CONCAT(v.address_1, ',', v.address_2, ',' , v.city, ',', v.postcode) AS address FROM " . DB_PREFIX . "sellers v WHERE v.seller_id = '" . (int)$seller_id . "'");
		$data = $query->row;
		if ($query->num_rows) {
			$data = array_merge($data,array('country_name' => $this->getCountryName($data['country_id'])));
			$data = array_merge($data,array('zone_name' => $this->getZoneName($data['zone_id'])));
		}
		return $data;
	}
	/*code end*/
	public function getProductDescriptions($product_id) {
		$product_description_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'tag' => $result['tag']
				);
		}
		return $product_description_data;
	}
	public function getProductAttributes($product_id) {
		$product_attribute_data = array();
		$product_attribute_query = $this->db->query("SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.seller_id='".(int)$this->seller->getId()."' GROUP BY pa.attribute_id");
		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();
			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}
			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'name'                          => $product_attribute['name'],
				'product_attribute_description' => $product_attribute_description_data
				);
		}
		return $product_attribute_data;
	}
	public function getProductAttributes1($product_id) {
		$product_attribute_data = array();
		$product_attribute_query = $this->db->query("SELECT pa.attribute_id, ad.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY pa.attribute_id");
		foreach ($product_attribute_query->rows as $product_attribute) {
			$product_attribute_description_data = array();
			$product_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "' AND attribute_id = '" . (int)$product_attribute['attribute_id'] . "'");
			foreach ($product_attribute_description_query->rows as $product_attribute_description) {
				$product_attribute_description_data[$product_attribute_description['language_id']] = array('text' => $product_attribute_description['text']);
			}
			$product_attribute_data[] = array(
				'attribute_id'                  => $product_attribute['attribute_id'],
				'name'                          => $product_attribute['name'],
				'product_attribute_description' => $product_attribute_description_data
				);
		}
		return $product_attribute_data;
	}
	public function getProductSpecials($product_id,$sellerid) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "' AND 
			seller_id = '".(int)$sellerid."' ORDER BY priority, price");
		return $query->rows;
	}
	public function getOptionValues1($option_id) {
		$option_value_data = array();
		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order ASC");
		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
				);
		}
		return $option_value_data;
	}
	public function getProductOptions($product_id,$sellerid) {	
		$product_option_data = array();
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND po.seller_id = '" . (int)$sellerid . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();	
				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND pov.seller_id = '" . (int)$sellerid . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
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
				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
					);				
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['option_value'],
					'required'          => $product_option['required']
					);				
			}
		}	
		return $product_option_data;
	}
	public function getOptionValues($option_id) {
		$option_value_data = array();
		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value ov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE ov.option_id = '" . (int)$option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order ASC");
		foreach ($option_value_query->rows as $option_value) {
			$option_value_data[] = array(
				'option_value_id' => $option_value['option_value_id'],
				'name'            => $option_value['name'],
				'image'           => $option_value['image'],
				'sort_order'      => $option_value['sort_order']
				);
		}
		return $option_value_data;
	}
	public function getOptionValueDescriptions($option_id) {
		$option_value_data = array();
		$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value WHERE option_id = '" . (int)$option_id . "'");
		foreach ($option_value_query->rows as $option_value) {
			$option_value_description_data = array();
			$option_value_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE option_value_id = '" . (int)$option_value['option_value_id'] . "'");			
			foreach ($option_value_description_query->rows as $option_value_description) {
				$option_value_description_data[$option_value_description['language_id']] = array('name' => $option_value_description['name']);
			}
			$option_value_data[] = array(
				'option_value_id'          => $option_value['option_value_id'],
				'option_value_description' => $option_value_description_data,
				'image'                    => $option_value['image'],
				'sort_order'               => $option_value['sort_order']
				);
		}
		return $option_value_data;
	}
	public function getProductImages($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}
	public function getProductDiscounts($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "' AND seller_id='".(int)$this->seller->getId()."' ORDER BY quantity, priority, price");
		return $query->rows;
	}
	public function getProductRewards($product_id) {
		$product_reward_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}
		return $product_reward_data;
	}
	public function getProductDownloads($product_id) {
		$product_download_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_download_data[] = $result['download_id'];
		}
		return $product_download_data;
	}
	public function getDownload($downloadid) {
		$download_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download as d WHERE d.download_id = '" . (int)$downloadid . "'");
		return $query->row;
	}
	public function getDownloadesc($downloadid) {
		$download_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download as d," . DB_PREFIX . "download_description as dd WHERE d.download_id = '" . (int)$downloadid . "' AND d.download_id = dd.download_id AND dd.language_id=1");
		return $query->row;
	}
	public function getProductStores($product_id) {
		$product_store_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_store_data[] = $result['store_id'];
		}
		return $product_store_data;
	}
	public function getProductLayouts($product_id) {
		$product_layout_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_layout_data[$result['store_id']] = $result['layout_id'];
		}
		return $product_layout_data;
	}
	public function getProductCategories($product_id) {
		$product_category_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_category_data[] = $result['category_id'];
		}
		return $product_category_data;
	}
	public function getProductRelated($product_id) {
		$product_related_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}
		return $product_related_data;
	}
	public function getProductTags($product_id) {
		$product_tag_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_tag WHERE product_id = '" . (int)$product_id . "'");
		$tag_data = array();
		foreach ($query->rows as $result) {
			$tag_data[$result['language_id']][] = $result['tag'];
		}
		foreach ($tag_data as $language => $tags) {
			$product_tag_data[$language] = implode(',', $tags);
		}
		return $product_tag_data;
	}
	public function getTotalProductsByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE tax_class_id = '" . (int)$tax_class_id . "'");
		return $query->row['total'];
	}
	public function getTotalProductsByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE stock_status_id = '" . (int)$stock_status_id . "'");
		return $query->row['total'];
	}
	public function getTotalProductsByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE weight_class_id = '" . (int)$weight_class_id . "'");
		return $query->row['total'];
	}
	public function getTotalProductsByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE length_class_id = '" . (int)$length_class_id . "'");
		return $query->row['total'];
	}
	public function getTotalProductsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_download WHERE download_id = '" . (int)$download_id . "'");
		return $query->row['total'];
	}
	public function getTotalProductsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		return $query->row['total'];
	}
	public function getTotalProductsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");
		return $query->row['total'];
	}	
	public function getTotalProductsByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_option WHERE option_id = '" . (int)$option_id . "'");
		return $query->row['total'];
	}	
	public function getTotalProductsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
		return $query->row['total'];
	}		
	/*code start*/
	public function getVendors($data = array()) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers v ORDER BY v.seller_name");
		$sellers_data = $query->rows;
		return $sellers_data;
		$this->cache->set('product', $sellers_data);
	}
	public function getCountry($data = array()) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country c ORDER BY c.name");
		$country_data = $query->rows;
		return $country_data;
		$this->cache->set('product', $country_data);
	}
	public function getCourier($data = array()) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "courier cr ORDER BY cr.courier_name");
		$couriers_data = $query->rows;
		return $couriers_data;
		$this->cache->set('product', $couriers_data);
	}
	public function getCountryName($country_id) {
		$country_name = array();
		$query = $this->db->query("SELECT name AS CountryName FROM " . DB_PREFIX . "country c WHERE c.country_id = '" . (int)$country_id . "'");
		foreach ($query->rows as $result) {
			$country_name[] = $result['CountryName'];
		}
		return $country_name;
	}
	public function getZoneName($zone_id) {
		$zone_name = array();
		$query = $this->db->query("SELECT name AS ZoneName FROM " . DB_PREFIX . "zone z WHERE z.zone_id = '" . (int)$zone_id . "'");
		foreach ($query->rows as $result) {
			$zone_name[] = $result['ZoneName'];
		}
		return $zone_name;
	}
	public function ValidateVendorUpdate($product_id, $seller_access) {
		$query = $this->db->query("SELECT COUNT(*) AS total_product FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "seller vd ON (p.product_id = vd.vproduct_id) WHERE p.product_id = '" . (int)$product_id . "' AND vd.seller_id IN('" . $seller_access . "')");
		return $query->row['total_product'];
	}
	public function getSellerProducts($seller) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "seller vd ON (pd.product_id = vd.vproduct_id) LEFT JOIN " . DB_PREFIX . "customer vds ON (vd.seller_id = vds.customer_id) WHERE vd.seller_id IN('" . $seller . "') AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pd.name ASC");
		$product_data = $query->rows;
		return $product_data;
	}
	public function getsellers() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers where status=1 ORDER BY firstname ");
		$sellers_data = $query->rows;
		return $sellers_data;
		$this->cache->set('product', $sellers_data);
	}
	/*code end*/
}
?>