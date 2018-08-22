<?php

class Seller {

	private $seller_id;
	private $username;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $seller_group_id;
	private $address_id;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		if (isset($this->session->data['seller_id'])) { 
			$seller_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$this->session->data['seller_id'] . "' AND status = '1'");
			if ($seller_query->num_rows) {
				$this->seller_id = $seller_query->row['seller_id'];
				$this->username = $seller_query->row['username'];
				$this->firstname = $seller_query->row['firstname'];
				$this->lastname = $seller_query->row['lastname'];
				$this->email = $seller_query->row['email'];
				$this->telephone = $seller_query->row['telephone'];
				$this->fax = $seller_query->row['fax'];
				$this->seller_group_id = $seller_query->row['seller_group_id'];
				$this->address_id = $seller_query->row['address_id'];
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seller_ip WHERE seller_id = '" . (int)$this->session->data['seller_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "seller_ip SET seller_id = '" . (int)$this->session->data['seller_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false) {

		if ($override) {

			$seller_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers where LOWER(email) = '" . $this->db->escape(strtolower($email)) . "' AND status = '1'");
		} else {

			$seller_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE LOWER(email) = '" . $this->db->escape(strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		}

		if ($seller_query->num_rows) {

			$this->session->data['seller_id'] = $seller_query->row['seller_id'];	
			$this->seller_id = $seller_query->row['seller_id'];
			$this->username = $seller_query->row['username'];
			$this->firstname = $seller_query->row['firstname'];
			$this->lastname = $seller_query->row['lastname'];
			$this->email = $seller_query->row['email'];
			$this->telephone = $seller_query->row['telephone'];
			$this->fax = $seller_query->row['fax'];
			$this->seller_group_id = $seller_query->row['seller_group_id'];
			$this->address_id = $seller_query->row['address_id'];
			$this->db->query("UPDATE " . DB_PREFIX . "sellers SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE seller_id = '" . (int)$this->seller_id . "'");
			return true;
		} else {
			return false;
		}
	}

	public function logout() {
		unset($this->session->data['seller_id']);
		$this->seller_id = '';
		$this->username  = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->seller_group_id = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->seller_id;
	}

	public function getId() {
		return $this->seller_id;
	}
	public function getUserName() {
		return $this->username;
	}
	public function getFirstName() {
		return $this->firstname;
	}
	public function getLastName() {
		return $this->lastname;
	}
	public function getEmail() {
		return $this->email;
	}
	public function getTelephone() {
		return $this->telephone;
	}
	public function getFax() {
		return $this->fax;
	}
	public function getSellerGroupId() {
		return $this->seller_group_id;	
	}
	public function getAddressId() {
		return $this->address_id;	
	}
	public function getBalance() {
		
		$filter_eligible_status_id = $this->config->get('config_seller_payments');		
		if(!empty($filter_eligible_status_id))		{				
			$filter_eligible_status_id = implode(",",$filter_eligible_status_id);		
		}else{			
			$filter_eligible_status_id = 0;		
		}			
		$seller_id = $this->seller_id;		
		$query = $this->db->query("SELECT SUM(st.amount) AS pamount 
			FROM " . DB_PREFIX . "seller_transaction st 
			LEFT JOIN `" . DB_PREFIX . "order` o ON (st.order_id=o.order_id) 
			WHERE (st.seller_id = '" . (int)$seller_id. "' AND o.order_status_id IN (".$filter_eligible_status_id.")) 
			OR (st.seller_id = '" .(int)$seller_id. "' AND st.order_id=0)");		
		return $query->row['pamount'];
	}

	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "seller_reward WHERE seller_id = '" . (int)$this->seller_id . "'");
		return $query->row['total'];	
	}

	public function getSellerMessages($seller_id,$data = array()){

		$sql = "SELECT*FROM " . DB_PREFIX . "seller_message WHERE seller_id = '" .(int)$seller_id. "' ";

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) 
		{			
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
		}
		if (isset($data['order']) && ($data['order'] == 'ASC')) {
			$sql .= " ASC";
		} else {			
			$sql .= " DESC";
		}
		if (isset($data['start']) || isset($data['limit']))
		{
			if ($data['start'] < 0) 
			{				
				$data['start'] = 0;
			}
			if ($data['limit'] < 1)
			{
				$data['limit'] = 20;
			}			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getSellerTotalMessages($seller_id,$data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "seller_message` WHERE seller_id = '" . (int)$seller_id . "'" ;

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) 
		{			
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY message_id";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {			
			$sql .= " ASC";
		}
		if (isset($data['start']) || isset($data['limit']))
		{
			if ($data['start'] < 0) 
			{				
				$data['start'] = 0;
			}
			if ($data['limit'] < 1)
			{
				$data['limit'] = 20;
			}			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		
		$query = $this->db->query($sql);		

		return $query->row['total'];
	}

	public function getSellerMessageInfo($messageId,$seller_id){
		$sql  = "SELECT * FROM " . DB_PREFIX . "seller_message WHERE seller_id = '" . (int)$this->seller_id . "' AND message_id = '" . (int)$messageId . "' ";
		$query = $this->db->query($sql);
		return $query->row;
	}	

	public function deleteSellerMessage($messageId,$seller_id){
  			# code...
	}	

	public function replyCustomer($data = array())
	{
		$sql = "INSERT INTO " . DB_PREFIX . "seller_message_reply SET 
		seller_id = '" .$this->db->escape($data['seller']) . "',
		message_id = '" .$this->db->escape($data['message_id']) . "',
		content = '" . $this->db->escape($data['content']) . "',
		date_added =  NOW(),
		email = '" . $this->db->escape($data['email']) . "'";
		$query  = $this->db->query($sql);
		if($query){
			return true;
		} else {
			return false;
		}
	}

	public function getMessageHistory($messageId,$seller_id)
	{
		$sql  = "SELECT * FROM " . DB_PREFIX . "seller_message_reply WHERE seller_id = '" . $this->db->escape($seller_id) . "' AND message_id = '" . $this->db->escape($messageId) . "' ORDER BY date_added DESC ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
}
?>
