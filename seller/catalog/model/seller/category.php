<?php

class ModelSellerCategory extends Model {

	public function editCategory($category_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', 
		`top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW() WHERE category_id = '" . (int)$category_id . "'");
		
		if (isset($data['image'])) {
		
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape($data['image']) . "' WHERE category_id = '" . (int)$category_id . "'");
		
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($data['category_description'] as $language_id => $value) {
		
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		
		}
		
		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE path_id = '" . (int)$category_id .
		 "' ORDER BY level ASC");
		
		if ($query->rows) {
			
			foreach ($query->rows as $category_path) {
			
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' AND level < '" . (int)$category_path['level'] . "'");
			
				$path = array();
			
				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
			
				foreach ($query->rows as $result) {
			
					$path[] = $result['path_id'];
			
				}
			
				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_path['category_id'] . "' ORDER BY level ASC");
			
				foreach ($query->rows as $result) {
			
					$path[] = $result['path_id'];
			
				}
				// Combine the paths with a new level
				$level = 0;
				foreach ($path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_path['category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");
					$level++;
				}
			}

		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category_id . "'");
			// Fix for records with no paths
			$level = 0;
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");
			
			foreach ($query->rows as $result) {
			
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");
				$level++;
			
			}
			
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', level = '" . (int)$level . "'");
		
		}
	
	}

	public function addCategory($data) {
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "category SET parent_id = '" . (int)$data['parent_id'] . "', 
		`top` = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', `column` = '" . (int)$data['column'] . "',
		sort_order = '" . (int)$data['sort_order'] . "', date_modified = NOW(),status = 1,date_added = NOW(),seller_id='".(int)$this->seller->getId()."'");
		
		$category_id = $this->db->getLastId();
		
		if (isset($data['image'])) {
		
			$this->db->query("UPDATE " . DB_PREFIX . "category SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE category_id = '" . (int)$category_id . "'");
		
		}
		
		foreach ($data['category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category_id . "', 
			language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', 
			meta_title = '" . $this->db->escape($value['meta_title']) . "',
			meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "'");
		}
		$level = 0;
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");
		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");
			$level++;
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category_id . "', `path_id` = '" . (int)$category_id . "', `level` = '" . (int)$level . "'");
		
		if (isset($data['category_filter'])) {
		
			foreach ($data['category_filter'] as $filter_id) {
		
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_filter SET category_id = '" . (int)$category_id . "', filter_id = '" . (int)$filter_id . "'");
		
			}
		
		}
		if (isset($data['category_store'])) {
			foreach ($data['category_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		// Set which layout to use with this category
		if (isset($data['category_layout'])) {
			foreach ($data['category_layout'] as $store_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "category_to_layout SET category_id = '" . (int)$category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}
		
		$this->load->language('seller/category');
		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
		$message .= $this->language->get('text_approval') . "\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');
		$mail->setTo($this->config->get('config_email'));
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		//$mail->send();
		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$emails = explode(',', $this->config->get('config_alert_emails'));
			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					//$mail->send();
				}
			}
		}
		$this->cache->delete('category');
	}
	public function getCategory($category_id,$seller) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND seller_id IN('" . $seller . "') AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	}
	public function getCategory1($category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR ' &gt; ') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row;
	}
	// public function getCategory($category_id) {
	// $query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
	// return $query->row;
	// }
	public function getCategories($parent_id = 0,$seller) {
		
		$query = $this->db->query("SELECT c.*,cd.language_id,cd.name,cd.description,cd.meta_description,cd.meta_keyword
		FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd 
		ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
		WHERE c.parent_id = '" . (int)$parent_id . "' AND c.approve=1 AND c.seller_id = '" . $seller . "' AND 
		cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, 
		LCASE(cd.name)");
		
		return $query->rows;
	
	}
	
	public function getallCategories1($data,$seller) {
		$cats = $this->config->get('config_product_category');
		if(empty($cats)){
			$cats = 0;
		}else{ $cats = implode(",",$cats);}
		$sql = "SELECT c1.approve,cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name,
		c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON 
		(cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id)
		WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'  
		AND c1.status=1 AND c1.seller_id = '" . $seller . "'";
		
		$sql .= " GROUP BY cp.category_id";
		
		$sort_data = array(
			'name',
			'sort_order'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		
			$sql .= " ORDER BY " . $data['sort'];
		
		} else {
		
			$sql .= " ORDER BY sort_order";
		
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

	
	public function getallCategories($data) {
	
		$cats = $this->config->get('config_product_category');
		
		if( empty($cats) ){
		
			$cats = 0;
		
		} else {

		 $cats = implode(",",$cats);

		}
		
		$sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name,
		c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON 
		(cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) 
		LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id)
		WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'  
		AND c1.status=1 AND c1.approve=1 AND cp.category_id IN (".$cats.")";

		if ( !empty( $data['filter_name'] ) ) {
			
			$sql .= " AND cd2.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		
		} 
		
		$sql .= " GROUP BY cp.category_id";
		
		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		
			$sql .= " ORDER BY " . $data['sort'];
		
		} else {
		
			$sql .= " ORDER BY sort_order";
		
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
	
	public function getApproveCategories($parent_id = 0) {
		
		$query = $this->db->query("SELECT c.*,cd.language_id,cd.name,cd.description,cd.meta_description,cd.meta_keyword
		FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd 
		ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
		WHERE c.approve=1 AND c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, 
		LCASE(cd.name)");
		
		return $query->rows;
	
	}
	public function getCategories1($parent_id = 0,$seller) {
	   
	    if ($seller) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)  LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.approve = '1' AND ( c.seller_id = '" . $seller . "' OR c.seller_id =0 ) AND c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, LCASE(cd.name)");
		
		} else {
			
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd 
		ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
		WHERE c.approve = '1'  AND c.parent_id = '" . (int)$parent_id . "'  ORDER BY c.sort_order, LCASE(cd.name)");
		
		}
		
		return $query->rows;
	}

	public function getPath($category_id) {
		
		$query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.sort_order, cd.name ASC");
		
		if ($query->row['parent_id']) {
		
			return $this->getPath($query->row['parent_id'], $this->config->get('config_language_id')) . $this->language->get('text_separator') . $query->row['name'];
		
		} else {
		
			return $query->row['name'];
		
		}
	}
	
	public function getCategoryDescriptions($category_id) {
		
		$category_description_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
			
			$category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'description'      => $result['description']
			);
		
		}
		
		return $category_description_data;
	}
	public function getCategoryStores($category_id) {
		
		$category_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_store WHERE category_id = '" . (int)$category_id . "'");
		
		foreach ($query->rows as $result) {
		
			$category_store_data[] = $result['store_id'];
		
		}
		
		return $category_store_data;
	
	}
	
	public function getCategoryLayouts($category_id) {
	
		$category_layout_data = array();
	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_to_layout WHERE category_id = '" . (int)$category_id . "'");
	
		foreach ($query->rows as $result) {
	
			$category_layout_data[$result['store_id']] = $result['layout_id'];
	
		}
	
		return $category_layout_data;
	
	}
	
	public function getTotalCategories1($seller_id) {
	
		$cats = $this->config->get('config_product_category');
	
		if(empty($cats)){
	
			$cats = 0;
	
		} else { 

			$cats = implode(",",$cats);}
		
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category where 
		status=1 AND seller_id = '" . (int)$seller_id . "'");
		
		return $query->row['total'];
	
	}
	
	public function getTotalCategories() {
	
		$cats = $this->config->get('config_product_category');
	
		if(empty($cats)){
	
			$cats = 0;
	
		} else {

		$cats = implode(",",$cats);}
      	
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category where status=1 AND approve=1 AND category_id IN (".$cats.")");
		
		return $query->row['total'];
	
	}
	
	public function getTotalCategoriesByImageId($image_id) {
    
      	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category WHERE image_id = '" . (int)$image_id . "'");
	
		return $query->row['total'];
	
	}
	
	public function getTotalCategoriesByLayoutId($layout_id) {
	
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category_to_layout WHERE layout_id = '" . (int)$layout_id . "'");
	
		return $query->row['total'];
	
	}
}
?>