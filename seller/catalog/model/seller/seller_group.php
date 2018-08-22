<?php
class ModelSellerSellerGroup extends Model {
	public function getSellerGroup($seller_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "seller_group WHERE seller_group_id = '" . (int)$seller_group_id . "'");
		return $query->row;
	}
	public function getSellerGroups() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seller_group cg LEFT JOIN " . DB_PREFIX . "seller_group_description cgd ON (cg.seller_group_id = cgd.seller_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cg.sort_order ASC, cgd.name ASC");
		return $query->rows;
	}
}
?>