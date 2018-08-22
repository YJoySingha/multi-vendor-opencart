<?php
class ModelAccountReview extends Model {
	public function addReview($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sellerreview SET author = '" . $this->db->escape($data['author']) . "',customer_id= '" . $this->customer->getId() . "', seller_id = '".(int)$data['seller_id']."', order_id = '".(int)$data['order_id']."', text = '" . $this->db->escape(strip_tags($data['text'])) . "', rating = '" . (int)$data['rating'] . "',status=1, date_added = NOW()");	
		$this->cache->delete('product');
	}
	
	public function getReview($seller_id,$order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellerreview WHERE seller_id = '".(int)$seller_id."' AND order_id = '".(int)$order_id."'");	
		return $query->row;
	}
	
	public function getOrder($seller_id,$order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product as op," . DB_PREFIX . "order as o WHERE op.seller_id = '".(int)$seller_id."' AND op.order_id = '".(int)$order_id."' AND op.order_id = o.order_id AND o.customer_id='".(int)$this->customer->getId()."'");	
		return $query->rows;
	}
}
?>