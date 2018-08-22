<?php
class ModelSellerActivity extends Model {
	public function addActivity($key, $data) {
		if (isset($data['seller_id'])) {
			$seller_id = $data['seller_id'];
		} else {
			$seller_id = 0;
		}
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seller_activity` SET `seller_id` = '" . (int)$seller_id . "',
		`key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', 
		`ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
	}
}