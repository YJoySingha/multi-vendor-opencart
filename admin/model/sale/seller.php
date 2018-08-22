<?php
class ModelSaleSeller extends Model {


	public function getSellers1($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . "sellers c where c.approved=0";	
		$sort_data = array(
			'name',
			'c.email',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
			);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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

	public function editSeller($seller_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET firstname = '" . $this->db->escape($data['firstname']) . "',
			lastname = '" . $this->db->escape($data['lastname']) . "',
			username = '" . $this->db->escape($data['username']) . "', 
			aboutus = '" . $this->db->escape($data['aboutus']) . "', 		 
			email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "', tin_no = '" . $this->db->escape($data['tin_no']) . "',  status = '" . (int)$data['status'] . "' WHERE seller_id = '" . (int)$seller_id . "'");
		
		
		if($data['status']){
			$this->db->query("UPDATE " . DB_PREFIX . "sellers_products SET status = '1' WHERE seller_id = '" . (int)$seller_id . "'");
		}else{
			$this->db->query("UPDATE " . DB_PREFIX . "sellers_products SET status = '0' WHERE seller_id = '" . (int)$seller_id . "'");
		}
		

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE seller_id = '" . (int)$seller_id . "'");
		}
		
		$query = $this->db->query("SELECT foldername FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$seller_id . "'");
		
		$foldername = $query->row['foldername'];
		

		if($foldername){

			if (isset($data['image'])) {
				$this->db->query("UPDATE " . DB_PREFIX . "sellers SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE seller_id = '" . (int)$seller_id . "'");
			}
		}

		if (isset($data['commission_id'])) {

			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET commission_id = '" . (int)$data['commission_id']. "' 
				WHERE seller_id = '" . (int)$seller_id . "'");
		}

		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET paypalorcheque = '".(int)$data['paypalorcheque']."', 
			paypal_email = '" . $this->db->escape($data['paypal_email']) . "',
			bank_name = '" . $this->db->escape($data['bank_name']). "',
			payee_name = '" . $this->db->escape($data['cheque']). "',
			account_number = '" . $this->db->escape($data['account_number']). "', 
			account_name = '" . $this->db->escape($data['account_name']). "', 
			branch = '" . $this->db->escape($data['branch']). "', 
			ifsccode = '" . $this->db->escape($data['ifsccode']). "'		WHERE seller_id = '" . (int)$seller_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "saddress WHERE seller_id = '" . (int)$seller_id . "'");

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "saddress SET address_id = '" . (int)$address['address_id'] . "', 
					seller_id = '" . (int)$seller_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', 
					lastname = '" . $this->db->escape($data['lastname']) . "', 
					company = '" . $this->db->escape($address['company']) . "', 

					address_1 = '" . $this->db->escape($address['address_1']) . "', 
					city = '" . $this->db->escape($address['city']) . "',
					postcode = '" . $this->db->escape($address['postcode']) . "', 
					country_id = '" . (int)$address['country_id'] . "',
					zone_id = '" . (int)$address['zone_id'] . "'");

				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE " . DB_PREFIX . "sellers SET address_id = '" . (int)$address_id . "' WHERE 
						seller_id = '" . (int)$seller_id . "'");
				}

				if (isset($data['zone_id2'])) {

					$this->db->query("UPDATE " . DB_PREFIX . "saddress SET zone_id2 = '" . (int)$data['zone_id2'] . "'
						WHERE seller_id = '" . (int)$seller_id . "'");
				}
			}
		}

	}

	public function addSeller($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sellers SET firstname = '" . $this->db->escape($data['firstname']) . "',
			lastname = '" . $this->db->escape($data['lastname']) . "',
			username = '" . $this->db->escape($data['username']) . "', 
			foldername = '" . $this->db->escape($data['username']) . "', 
			aboutus = '" . $this->db->escape($data['aboutus']) . "', 		 
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			date_added = NOW(),
			fax = '" . $this->db->escape($data['fax']) . "', status = '" . (int)$data['status'] . "'");

		$folderName =  $data['username'];
		
		$path = DIR_IMAGE;
		
		$fPath = $path.$folderName;
		$exist = is_dir($fPath);
		if(!$exist) {
			mkdir("$fPath");
			chmod("$fPath", 0777);
		}
		
		$seller_id = $this->db->getLastId();

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET 
				salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
				password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' WHERE 
				seller_id = '" . (int)$seller_id . "'");
		}

		if($data['username']) {

			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET language_id = '1', query = 'seller_id=" . (int)$seller_id . "', keyword = '" . $this->db->escape($data['username']) . "'");	
		}	
		
		
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' WHERE seller_id = '" . (int)$seller_id . "'");
		}

		if (isset($data['commission_id'])) {

			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET commission_id = '" . (int)$data['commission_id']. "' 
				WHERE seller_id = '" . (int)$seller_id . "'");
		}

		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET paypalorcheque = '".(int)$data['paypalorcheque']."', 
			paypal_email = '" . $this->db->escape($data['paypal_email']) . "',bank_name = '" . $this->db->escape($data['bank_name']). "',
			payee_name = '" . $this->db->escape($data['cheque']). "',
			account_number = '" . $this->db->escape($data['account_number']). "', 
			account_name = '" . $this->db->escape($data['account_name']). "', 
			branch = '" . $this->db->escape($data['branch']). "', 
			ifsccode = '" . $this->db->escape($data['ifsccode']). "'
			WHERE seller_id = '" . (int)$seller_id . "'");

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "saddress SET address_id = '" . (int)$address['address_id'] . "', 
					seller_id = '" . (int)$seller_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', 
					lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', 

					address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "',
					city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', 
					country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "'");

				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE " . DB_PREFIX . "sellers SET address_id = '" . (int)$address_id . "' WHERE seller_id = '" . (int)$seller_id . "'");
				}	
			}
		}
	}

	public function editToken($seller_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET token = '" . $this->db->escape($token) . "' WHERE seller_id = '" . (int)$seller_id . "'");
	}
	
	public function getfoldername($seller_id) {
		$query = $this->db->query("SELECT foldername FROM `" . DB_PREFIX . "sellers` WHERE seller_id = '" . (int)$seller_id . "'");
		
		return $query->row['foldername'];
	}
	
	public function deleteSeller($seller_id) {

		$query = $this->db->query("SELECT foldername FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$seller_id . "'");
		
		if($query->row['foldername']){
			$folderName =  $query->row['foldername'];

			$path = DIR_IMAGE;

			$dir = $path.$folderName;

			$files = array_diff(scandir($dir), array('.','..'));
			foreach ($files as $file) {
				(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
			}
			rmdir($dir); 

		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$seller_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seller_reward WHERE seller_id = '" . (int)$seller_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seller_transaction WHERE seller_id = '" . (int)$seller_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "seller_ip WHERE seller_id = '" . (int)$seller_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "saddress WHERE seller_id = '" . (int)$seller_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sellers_products WHERE seller_id = '" . (int)$seller_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE seller_id = '" . (int)$seller_id . "'");
		

	}
	
	public function getSeller($seller_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$seller_id . "'");

		return $query->row;
	}
	
	public function getSellerByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sellers WHERE LCASE(email) = '" . $this->db->escape(strtolower($email)) . "'");

		return $query->row;
	}

	public function getSellers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . "sellers c";

		$implode = array();
		
		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(c.email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "%'";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}	
		
		if (isset($data['filter_approved']) && !empty($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}	

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " where " . implode(" AND ", $implode);
		}
		
		$sort_data = array(
			'name',
			'c.email',
			'c.status',
			'c.approved',
			'c.ip',
			'c.date_added'
			);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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
	
	public function approve($seller_id) {
		$seller_info = $this->getSeller($seller_id);

		if ($seller_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET approved = '1' WHERE seller_id = '" . (int)$seller_id . "'");

			$this->db->query("UPDATE " . DB_PREFIX . "sellers_products SET status = '1' WHERE seller_id = '" . (int)$seller_id . "'");


			$this->load->language('mail/seller');
			
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($seller_info['store_id']);
			
			if ($store_info) {
				$store_name = $store_info['name'];
				$store_url = $store_info['url'] . 'index.php?route=seller/login';
			} else {
				$store_name = $this->config->get('config_name');
				$store_url = HTTP_CATALOG1 . 'index.php?route=seller/login';
			}

			$message  = sprintf($this->language->get('text_approve_welcome'), $store_name) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= $store_url . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $store_name;

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
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_approve_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}		
	}
	

	public function approvepayment($seller_id) {
		$seller_info = $this->getSeller($seller_id);
		if ($seller_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET payment_status = '1' WHERE seller_id = '" . (int)$seller_id . "'");
		}		
	}

	public function getAddress($address_id) {

		$address_query = $this->db->query("SELECT sa.*,s.payee_name,s.paypal_email,s.paypalorcheque,s.username,s.commission_id,s.status,s.password,s.tin_no,
			s.email,s.fax,s.telephone,s.firstname,s.lastname,s.salt,s.approved,s.token,
			s.date_added,s.aboutus,s.image ,s.bank_name,s.account_number,
			s.account_name,
			s.branch,
			s.ifsccode
			FROM " . DB_PREFIX . "saddress as sa," . DB_PREFIX . "sellers as s WHERE sa.address_id = '" . (int)$address_id . "' AND sa.seller_id = s.seller_id AND sa.address_id = s.address_id limit 1");
	//	$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "saddress WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");
			
			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';	
				$address_format = '';
			}
			
			
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}
			$country_query2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id2'] . "'");
			
			if ($country_query2->num_rows) {
				$country2 = $country_query2->row['name'];
				
			} else {
				$country2 = '';
				$iso_code_2 = '';
				$iso_code_3 = '';	
				$address_format = '';
			}


			$zone_query2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id2'] . "'");
			
			if ($zone_query2->num_rows) {
				$zone2 = $zone_query2->row['name'];
				$zone_code2 = $zone_query2->row['code'];
			} else {
				$zone2 = '';
				$zone_code2 = '';
			}			

			return array(
				'address_id'     => $address_query->row['address_id'],
				'seller_id'    => $address_query->row['seller_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'company_id'     => $address_query->row['company_id'],
				'tax_id'         => $address_query->row['tax_id'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'postcode'       => $address_query->row['postcode'],
				'city'           => $address_query->row['city'],
				'tin_no'           => $address_query->row['tin_no'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'paypal_email'      => $address_query->row['paypal_email'],
				'paypalorcheque'       => $address_query->row['paypalorcheque'],
				'image'       => $address_query->row['image'],
				'username'      => $address_query->row['username'],
				'payee_name'      => $address_query->row['payee_name'],
				'email'      => $address_query->row['email'],
				'telephone'       => $address_query->row['telephone'],
				'fax'      => $address_query->row['fax'],
				
				
				'ifsccode'      => $address_query->row['ifsccode'],
				'branch'      => $address_query->row['branch'],
				'account_name'       => $address_query->row['account_name'],
				'account_number'      => $address_query->row['account_number'],
				'bank_name'       => $address_query->row['bank_name'],
				
				
				
				
				'commission_id'      => $address_query->row['commission_id'],
				'aboutus'      => $address_query->row['aboutus'],
				'status'      => $address_query->row['status'],
				'postcode2'       => $address_query->row['postcode2'],
				'city2'           => $address_query->row['city2'],
				'zone_id2'        => $address_query->row['zone_id2'],
				'zone2'           => $zone2,
				'zone_code2'      => $zone_code2,
				'country_id2'     => $address_query->row['country_id2'],
				'country2'        => $country2,	
				'password'        => $address_query->row['password'],
				
				);
		}
	}
	
	public function getCommissions($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "commission";
			$sort_data = array(
				'commission_name',
				'commission_type',
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

	public function getAddresses($seller_id) {
		$address_data = array();
		
		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "saddress WHERE seller_id = '" . (int)$seller_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}


		
		return $address_data;
	}	

	public function getAddresses1($seller_id) {
		$address_data = array();
		
		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "saddress WHERE seller_id = '" . (int)$seller_id . "'");

		foreach ($query->rows as $result) {
			$address_data = $this->getAddress($result['address_id']);

			break;
		}


		
		return $address_data;
	}	

	public function getTotalSellers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sellers";
		
		$implode = array();
		
		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(firstname, ' ', lastname)) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "%'";
		}
		
		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_seller_group_id'])) {
			$implode[] = "seller_group_id = '" . (int)$data['filter_seller_group_id'] . "'";
		}	
		
		if (!empty($data['filter_ip'])) {
			$implode[] = "seller_id IN (SELECT seller_id FROM " . DB_PREFIX . "seller_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}			
		
		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}		

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalSellersAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sellers WHERE status = '0' OR approved = '0'");

		return $query->row['total'];
	}
	
	public function getTotalAddressesBySellerId($seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "saddress WHERE seller_id = '" . (int)$seller_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "saddress WHERE country_id = '" . (int)$country_id . "'");
		
		return $query->row['total'];
	}	
	
	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "saddress WHERE zone_id = '" . (int)$zone_id . "'");
		
		return $query->row['total'];
	}
	
	public function getTotalSellersBySellerGroupId($seller_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sellers WHERE seller_group_id = '" . (int)$seller_group_id . "'");
		
		return $query->row['total'];
	}

	public function addTransaction($seller_id, $description = '', $amount = '', $order_id = 0) {
		$seller_info = $this->getSeller($seller_id);
		
		if ($seller_info) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "seller_transaction SET seller_id = '" . (int)$seller_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "',
				date_added = NOW()");

			$this->language->load('mail/seller');
			
			if ($seller_info['store_id']) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($seller_info['store_id']);
				
				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}	
			} else {
				$store_name = $this->config->get('config_name');
			}

			$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
			$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($seller_id)));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($seller_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}
	
	public function deleteTransaction($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "seller_transaction WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function getTransactions($seller_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 10;
		}	

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seller_transaction WHERE seller_id = '" . (int)$seller_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalTransactions($seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "seller_transaction WHERE seller_id = '" . (int)$seller_id . "'");

		return $query->row['total'];
	}
	public function getBalanceTotal($seller_id) {
		
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "seller_transaction WHERE seller_id = '" . (int)$this->seller_id . "'");

		return $query->row['total'];
	}	
	public function getTransactionTotal($seller_id) {
		$filter_eligible_status_id = $this->config->get('config_seller_payments');
		if(!empty($filter_eligible_status_id))
		{	
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);
		}else{
			$filter_eligible_status_id = 0;
		}
		$query1 = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "seller_transaction WHERE seller_id = '" . (int)$this->seller_id . "' AND order_id=0");
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "seller_transaction s INNER JOIN " . DB_PREFIX . "order_product op ON (s.order_id=op.order_id)  WHERE s.seller_id = '" . (int)$this->seller_id. "'  AND op.product_status_id IN (".$filter_eligible_status_id.")");
		return $query->row['total']+$query1->row['total'];
	}
	
	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_transaction WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}	

	public function addReward($seller_id, $description = '', $points = '', $order_id = 0) {
		$seller_info = $this->getSeller($seller_id);

		if ($seller_info) { 
			$this->db->query("INSERT INTO " . DB_PREFIX . "seller_reward SET seller_id = '" . (int)$seller_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

			$this->language->load('mail/seller');
			
			if ($order_id) {
				$this->load->model('sale/order');

				$order_info = $this->model_sale_order->getOrder($order_id);
				
				if ($order_info) {
					$store_name = $order_info['store_name'];
				} else {
					$store_name = $this->config->get('config_name');
				}	
			} else {
				$store_name = $this->config->get('config_name');
			}		

			$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
			$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($seller_id));

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');
			$mail->setTo($seller_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($store_name);
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_reward_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
	}

	public function deleteReward($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "seller_reward WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public function getRewards($seller_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seller_reward WHERE seller_id = '" . (int)$seller_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
	
	public function getTotalRewards($seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_reward WHERE seller_id = '" . (int)$seller_id . "'");

		return $query->row['total'];
	}
	
	public function getSellerName($seller_id) {
		if ($seller_id == 0) {
			$seller_id = $this->config->get('config_defaultseller_id');
		}
		$query = $this->db->query("SELECT username AS name FROM " . DB_PREFIX . "sellers WHERE 
			seller_id = '" . $seller_id . "'");

		return $query->row['name'];
	}

	public function getRewardTotal($seller_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "seller_reward WHERE seller_id = '" . (int)$seller_id . "'");

		return $query->row['total'];
	}		
	
	public function getTotalSellerRewardsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_reward WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
	
	public function getIpsBySellerId($seller_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seller_ip WHERE seller_id = '" . (int)$seller_id . "'");

		return $query->rows;
	}

	public function getaddressid($seller_id) {
		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$seller_id . "'");

		return $query->row['address_id'];
	}	
	
	public function getTotalSellersByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "seller_ip WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}
	
	public function addBlacklist($ip) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seller_ip_blacklist` SET `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function deleteBlacklist($ip) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seller_ip_blacklist` WHERE `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function getTotalBlacklistsByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "seller_ip_blacklist` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}

	public function getMessages()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "seller_message_reply";
		$query = $this->db->query($sql);
		return $query->rows;
	}	
}
?>