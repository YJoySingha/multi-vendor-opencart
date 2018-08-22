<?php 
class ModelCatalogOcproductrotator extends Model 
{
	public function installProductRotator() {
		$check_sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_image` LIKE 'is_rotator'";
        $query = $this->db->query($check_sql);
        if($query->rows) {
            return;
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "product_image` ADD `is_rotator` tinyint(1) DEFAULT 0";
            $this->db->query($sql);
            return;
        }
	}
}