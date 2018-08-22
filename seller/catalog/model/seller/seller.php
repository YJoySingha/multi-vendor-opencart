<?php
class ModelSellerSeller extends Model {
	public function addFolder($parent,$data) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seller_folder` WHERE folder_id = '" . (int)$parent . "'");
		$path = $query->row['path'];
		$parent_folder = $query->row['parent_folder'];
		/*$fPath = "image/".$path;
		$exist = is_dir($fPath);
		if($exist) {
			$folder = $fPath."/".$data['folder_name'];
			if(!is_dir($folder)){
				//echo "okokoko";die;
				mkdir("$folder");
				chmod("$folder", 0777);
			}
		}*/
		$fPath1 = DIR_IMAGE. $path;
		$exist1 = is_dir($fPath1);
		if($exist1) {
			$folder1 = $fPath1."/".$data['folder_name'];
			if(!is_dir($folder1)){
				//echo "okokoko";die;
				mkdir("$folder1");
				chmod("$folder1", 0777);
			}
		}
		$folder = $path."/".$data['folder_name'];
		$this->db->query("INSERT INTO " . DB_PREFIX . "seller_folder SET seller_id='" . (int)$this->seller->getId() . "', parent_folder = '" . $this->db->escape($parent_folder) . "', folder_name = '" . $this->db->escape($data['folder_name']) . "', path = '".$folder."'");
	}
	public function addImages($folder_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sellers` WHERE seller_id = '" . (int)$folder_id . "'");
		return $query->row['foldername'];
	}
	public function getfolders($data=array(),$seller_id) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "sellers` WHERE seller_id = '" . (int)$seller_id . "'";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getTotalFolders($data=array(),$seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sellers where seller_id = '" . (int)$seller_id . "'");
		return $query->row['total'];
	}
	public function deleteImages($folder_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seller_folder WHERE folder_id = '" . (int)$folder_id . "'");
		if($query->row['path']){
			$dir = DIR_IMAGE.$query->row['path'];
			$this->removeDirectory1($dir);
			$dir1 = DIR_IMAGE1.$query->row['path'];
			$this->removeDirectory1($dir1);
		}
	}
	public function deleteFolder($folder_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seller_folder WHERE folder_id = '" . (int)$folder_id . "'");
		if($query->row['path']){
			$dir = DIR_IMAGE.$query->row['path'];
			$this->removeDirectory($dir);
			$dir1 = DIR_IMAGE1.$query->row['path'];
			$this->removeDirectory($dir1);
			$this->db->query("DELETE FROM " . DB_PREFIX . "seller_folder WHERE folder_id = '" . (int)$folder_id . "'");
		}
	}

	public function removeDirectory($dir) {
		//tell whether the filename is a directory
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir")
						$this->removeDirectory($dir."/".$object);
					//else delete a file
					else unlink($dir."/".$object);
				}
			}
			reset($objects);
			//remove directory
			rmdir($dir);
		}
	}
	public function removeDirectory1($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (filetype($dir."/".$object) == "dir")
						$this->removeDirectory1($dir."/".$object);
					else unlink($dir."/".$object);
				}
			}
			reset($objects);
		}
	}
	public function addFolderForSeller($seller_id) {
		$query = $this->db->query("SELECT username FROM `" . DB_PREFIX . "sellers` WHERE seller_id = '" . (int)$seller_id . "'");
		$folderName = $query->row['username'];
		$fPath = "image/" . $folderName;
		$exist = is_dir($fPath);
		if(!$exist) {
			mkdir("$fPath");
			chmod("$fPath", 0777);
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "seller_folder SET seller_id='" . (int)$seller_id . "', parent_folder = '" . $this->db->escape($folderName) . "', folder_name = '" . $this->db->escape($folderName) . "', path = '".$folderName."'");
	}
	public function getcommissions() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission ORDER BY sort_order ASC");
		return $query->rows;
	}
	public function updateexpirycusts(){
		$config_sellercommission_id = $this->config->get('config_sellercommission_id');
		if(!$config_sellercommission_id){
			$config_sellercommission_id = 1;
		}
		$this->db->query("UPDATE  `" . DB_PREFIX . "sellers` set commission_id = '" . (int)$config_sellercommission_id . "',
			expiry_date = '0000-00-00 00:00:00' WHERE
			commission_id !='".(int)$config_sellercommission_id."' AND expiry_date != '0000-00-00 00:00:00' AND expiry_date < NOW()");
	}
	public function updatePlan($seller_id,$plan_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE 	commission_id = '".(int)$plan_id."'");
		$durationid = $query->row['duration_id'];
		$days = $query->row['per'];
		$Date = date("Y-m-d H:i:s");
		$expirydate = "0000-00-00 00:00:00";
		if($durationid == 'd'){
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days days"));
		}
		if($durationid == 'm'){
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days months"));
		}
		if($durationid == 'y'){
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days years"));
		}
		if($durationid == 'w'){
			$days = $days*7;
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days days"));
		}
		$oldgroup_id = 0;
		$custquery = $this->db->query("select commission_id from " . DB_PREFIX . "sellers where seller_id = '".(int)$seller_id."'");
		if($custquery->row){
			$oldgroup_id = $custquery->row['commission_id'];
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "upgraded_members SET seller_id = '" . (int)$seller_id . "',
			old_commission_id = '" . (int)$oldgroup_id . "',
			commission_id = '" . (int)$plan_id . "', 
			amount = '" . (float)$query->row['amount'] . "', 
			upgrade_date = NOW(), expiry_date = '" . $expirydate. "', upgradedby = '".$seller_id."'");
		$this->db->query("UPDATE  " . DB_PREFIX . "sellers
			SET commission_id = '" . (int)$plan_id. "',
			expiry_date = '".$expirydate."',pay_status = 1
			WHERE seller_id = '" . (int)$seller_id . "'");
	}
	public function updateplanseller($data,$seller_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET commission_id = '" . (int)$data['commission_id'] . "'
			WHERE seller_id = '" . (int)$seller_id . "'");
	}
	public function addSeller($data) {
		$this->event->trigger('pre.seller.add', $data);
		$config_sellercommission_id = $this->config->get('config_sellercommission_id');
		if(!$config_sellercommission_id){
			$config_sellercommission_id = 1;
		}
		$folderName =  $data['username'];
		$path = HTTPS_SERVER1;
		$fPath = DIR_IMAGE. $folderName;
		$exist = is_dir($fPath);

		if(!$exist) {
			mkdir("$fPath");
			chmod("$fPath", 0777);
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "sellers SET store_id = '" . (int)$this->config->get('config_store_id') . "',
			username = '" . $this->db->escape($data['username']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "'
			, telephone = '" . $this->db->escape($data['telephone']) . "', 
			salt = '" . $this->db->escape($salt = token(9)) . "',
			password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "',    
			foldername = '" . $this->db->escape($data['username']) . "', 
			commission_id = '" . (int)$config_sellercommission_id . "', 
			ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', date_added = NOW()");
		$seller_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "saddress SET seller_id = '" . (int)$seller_id . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "'
			");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET address_id = '" . (int)$address_id . "' WHERE
			seller_id = '" . (int)$seller_id . "'");

		if ($this->config->get('config_seller_autoapprove')) {
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET approved = 1,status=1
				WHERE	seller_id = '" . (int)$seller_id . "'");
		}

		$this->load->language('mail/seller');

		if (!$this->config->get('config_seller_autoapprove')) {
			$subject = sprintf($this->language->get('text_approvalsubject'), $this->config->get('config_name'));
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_approval') . "\n";
		} else {
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";
		}

		$message .= $this->url->link('seller/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$mail->setTo($this->config->get('config_email'));
			$mail->send();
			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));
			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
		return $seller_id;
		$this->event->trigger('post.seller.add', $seller_id);
	}
	public function updateexpirycusts1(){
		$config_sellercommission_id = $this->config->get('config_sellercommission_id');
		if(!$config_sellercommission_id){
			$config_sellercommission_id = 1;
		}
		$this->db->query("UPDATE  `" . DB_PREFIX . "sellers` set commission_id = '" . (int)$config_sellercommission_id . "',
			expiry_date = '0000-00-00 00:00:00' WHERE
			commission_id !='".(int)$config_sellercommission_id."' AND expiry_date != '0000-00-00 00:00:00' AND expiry_date < NOW()");
	}
	public function updatePlan1($seller_id,$plan_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "commission WHERE 	commission_id = '".(int)$plan_id."'");
		$durationid = $query->row['duration_id'];
		$days = $query->row['per'];
		$Date = date("Y-m-d H:i:s");
		$expirydate = "0000-00-00 00:00:00";
		if($durationid == 'd'){
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days days"));
		}
		if($durationid == 'm'){
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days months"));
		}
		if($durationid == 'y'){
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days years"));
		}
		if($durationid == 'w'){
			$days = $days*7;
			$expirydate = date('Y-m-d H:i:s', strtotime($Date. " + $days days"));
		}
		$oldgroup_id = 0;
		$custquery = $this->db->query("select commission_id from " . DB_PREFIX . "sellers where seller_id = '".(int)$seller_id."'");
		if($custquery->row){
			$oldgroup_id = $custquery->row['commission_id'];
		}
		$this->db->query("INSERT INTO " . DB_PREFIX . "upgraded_sellers SET seller_id = '" . (int)$seller_id . "',
			old_commission_id = '" . (int)$oldgroup_id . "',
			commission_id = '" . (int)$plan_id . "', 
			amount = '" . (float)$query->row['amount'] . "', 
			upgrade_date = NOW(), expiry_date = '" . $expirydate. "', upgradedby = '".$seller_id."'");
		$this->db->query("UPDATE  " . DB_PREFIX . "sellers
			SET commission_id = '" . (int)$plan_id. "',
			expiry_date = '".$expirydate."',payment_status = 1
			WHERE seller_id = '" . (int)$seller_id . "'");

	}
	public function updateplanseller1($data,$seller_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET commission_id = '" . (int)$data['commission_id'] . "'
			WHERE seller_id = '" . (int)$seller_id . "'");
	}

	public function editSeller($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', 
			aboutus = '" . $this->db->escape($data['aboutus']). "',tin_no = '" . $this->db->escape($data['tin_no']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "'
			WHERE seller_id = '" . (int)$this->seller->getId() . "'");
		$query = $this->db->query("SELECT foldername FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$this->seller->getId() . "'");
		$foldername = $query->row['foldername'];
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET 
				image = '". $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' 
				WHERE seller_id = '" . (int)$this->seller->getId() . "'");
		}
	}
	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE email = '" . $this->db->escape($email) . "'");
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET newsletter = '" . (int)$newsletter . "' WHERE seller_id = '" . (int)$this->seller->getId() . "'");
	}

	public function getSeller($seller_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$seller_id . "'");
		return $query->row;
	}

	public function getSellerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE email = '" . $this->db->escape($email) . "'");
		return $query->row;
	}

	public function getSellerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE token = '" . $this->db->escape($token) . "' AND token != ''");
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET token = ''");
		return $query->row;
	}

	public function getSellers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cg.name AS seller_group FROM " . DB_PREFIX . "sellers c LEFT JOIN " . DB_PREFIX . "seller_group cg ON (c.seller_group_id = cg.seller_group_id) ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "c.email = '" . $this->db->escape($data['filter_email']) . "'";
		}
		
		if (isset($data['filter_seller_group_id']) && !is_null($data['filter_seller_group_id'])) {
			$implode[] = "cg.seller_group_id = '" . $this->db->escape($data['filter_seller_group_id']) . "'";
		}
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}
		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}
		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.seller_id IN (SELECT seller_id FROM " . DB_PREFIX . "seller_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}
		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$sort_data = array(
			'name',
			'c.email',
			'seller_group',
			'c.status',
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
	public function getTotalSellersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sellers WHERE LOWER(email) = '" . $this->db->escape(strtolower($email)) . "'");
		return $query->row['total'];
	}
	public function getTotalSellersByUsername($username) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sellers WHERE LOWER(username) = '" . $this->db->escape(strtolower($username)) . "'");
		return $query->row['total'];
	}
	public function getSellerByUsername($username) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE LOWER(username) = '" . $this->db->escape(strtolower($username)) . "'");
		return $query->row;
	}
	public function getIps($seller_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seller_ip` WHERE seller_id = '" . (int)$seller_id . "'");
		return $query->rows;
	}
	public function getfoldername($seller_id) {
		$query = $this->db->query("SELECT foldername FROM `" . DB_PREFIX . "sellers` WHERE seller_id = '" . (int)$seller_id . "'");
		return $query->row['foldername'];
	}
	public function isBlacklisted($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seller_ip_blacklist` WHERE ip = '" . $this->db->escape($ip) . "'");
		return $query->num_rows;
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
	public function getTotalTransactions($seller_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "seller_transaction WHERE seller_id = '" . (int)$seller_id . "'");
		return $query->row['total'];
	}
	//  public function getTotalSalesBySellerId()
	// {
	//     $sql = 'SELECT SUM(op.total) AS totalSales FROM `'.DB_PREFIX.'order` o
	//      LEFT JOIN `'.DB_PREFIX.'order_product` op ON op.order_id = o.order_id
	//      LEFT JOIN `'.DB_PREFIX.'product_to_seller` pts ON pts.product_id = op.product_id';
	//      //WHERE pts.seller_id = '".$this->customer->getId()."' AND o.order_status_id > 0
	//     $sql .= " WHERE pts.seller_id = '".$this->customer->getId()."' AND o.order_status_id > 0";
	//      $query = $this->db->query($sql);
	//      return $query->row['totalSales'];
	// }
}
?>