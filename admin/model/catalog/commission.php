<?php
class ModelCatalogCommission extends Model {
	public function addCommission($data) {		
		$this->db->query("INSERT INTO " . DB_PREFIX . "commission SET commission_name = '" . $this->db->escape($data['commission_name']) . "', 
		commission = '" . (int)$data['commission'] . "',
		product_limit = '" . (int)$data['product_limit'] . "',
		sort_order = '" . (int)$data['sort_order'] . "'");
		$commission_id = $this->db->getLastId();
		if(isset($data['commission_rate'])){
			foreach($data['commission_rate'] as $category_id=>$comm_rate){
				if($comm_rate>0){
					$this->db->query("INSERT INTO " . DB_PREFIX . "commission_rates SET category_id = '".(int)$category_id."',
					commission_id = '" . (int)$commission_id. "',commission_rate = '" . (int)$comm_rate. "'");
				}
			}
		}
		
		
				if (isset($data['amount'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "commission SET amount = '" . $this->db->escape($data['amount']) . "' WHERE
			commission_id = '" . (int)$commission_id . "'");
		}
		
		if (isset($data['duration_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "commission SET duration_id = '" . $this->db->escape($data['duration_id']) . "' WHERE commission_id = '" . (int)$commission_id . "'");
		}
		
		if (isset($data['per'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "commission SET per = '" . $this->db->escape($data['per']) . "' WHERE commission_id = '" . (int)$commission_id . "'");
		}
		
		
		
		
		
		
		
		$this->cache->delete('commission');
	}

	public function editCommission($commission_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "commission SET commission_name = '" . $this->db->escape($data['commission_name']) . "', 
		product_limit = '" . (int)$data['product_limit'] . "',
		commission = '" . (int)$data['commission'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE commission_id = '" . (int)$commission_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "commission_rates WHERE commission_id = '" . (int)$commission_id . "'");
		if(isset($data['commission_rate'])){
			foreach($data['commission_rate'] as $category_id=>$comm_rate){
				if($comm_rate>0){
					$this->db->query("INSERT INTO " . DB_PREFIX . "commission_rates SET category_id = '".(int)$category_id."',commission_id = '" . (int)$commission_id. "',commission_rate = '" . (int)$comm_rate. "'");
				}
			}
		}
		
		
				if (isset($data['amount'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "commission SET amount = '" . $this->db->escape($data['amount']) . "' WHERE commission_id = '" . (int)$commission_id . "'");
		}
		
		if (isset($data['per'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "commission SET per = '" . $this->db->escape($data['per']) . "' WHERE commission_id = '" . (int)$commission_id . "'");
		}
		
		if (isset($data['duration_id'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "commission SET duration_id = '" . $this->db->escape($data['duration_id']) . "' WHERE commission_id = '" . (int)$commission_id . "'");
		}
		
		
		
		
		
		$this->cache->delete('commission');
	}

	public function deleteCommission($commission_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "commission WHERE commission_id = '" . (int)$commission_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "commission_rates WHERE commission_id = '" . (int)$commission_id . "'");
		$this->cache->delete('commission');
	}
	
	public function getCommission($commission_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE commission_id = '" . (int)$commission_id . "'");
		$rates = array();
		if($query->row){
			$queryrate = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission_rates WHERE commission_id = '" . (int)$commission_id . "'");
			foreach($queryrate->rows as $rate){
				$rates[$rate['category_id']] = $rate['commission_rate'];
			}
		}
		$query->row['commission_rate'] = $rates; 
		
		return $query->row;
	}
	
	public function getCommissions($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "commission";
			$sort_data = array(
				'commission_name',
				'commission',
				'sort_order'
			);	
			
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];	
			} else {
				$sql .= " ORDER BY commission_name";	
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
			
		} else {
			$commission_data = $this->cache->get('commission');
			if (!$commission_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission ORDER BY commission_id");
				$commission_data = $query->rows;
				$this->cache->set('commission', $commission_data);
			}
			return $commission_data;
		}
	}
	public function getTotalAgentsByCommissionId($commission_id) {
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sellers WHERE commission_id = '" . (int)$commission_id . "'");

		return $query->row['total'];
	}

	public function getTotalCommissions($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "commission";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
}
?>