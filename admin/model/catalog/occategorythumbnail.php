<?php
class ModelCatalogOccategorythumbnail extends Model
{
    public function installCategoryThumbnail() {
        $flag = false;
        $cate_exist = array();

        // Thumbnail Image
        $check_sql1 = "SHOW COLUMNS FROM `" . DB_PREFIX . "category` LIKE 'thumbnail_image'";
        $query1 = $this->db->query($check_sql1);
        if($query1->rows) {
            $flag = true;
            array_push($cate_exist, 'Thumbnail Image');
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "category` ADD `thumbnail_image` varchar(255) DEFAULT NULL";
            $this->db->query($sql);
        }

        // Home Thumbnail Image
        $check_sql2 = "SHOW COLUMNS FROM `" . DB_PREFIX . "category` LIKE 'homethumb_image'";
        $query2 = $this->db->query($check_sql2);
        if($query2->rows) {
            $flag = true;
            array_push($cate_exist, 'Homethumb Image');
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "category` ADD `homethumb_image` varchar(255) DEFAULT NULL";
            $this->db->query($sql);
        }

        //Featured Category
        $check_sql3 = "SHOW COLUMNS FROM `" . DB_PREFIX . "category` LIKE 'featured'";
        $query3 = $this->db->query($check_sql3);
        if($query3->rows) {
            $flag = true;
            array_push($cate_exist, 'Featured');
        } else {
            $sql = "ALTER TABLE `" . DB_PREFIX . "category` ADD `featured` tinyint(1) DEFAULT 0";
            $this->db->query($sql);
        }

        if($flag) {
            $info_text = implode(", ", $cate_exist);
            $info_text .= " column(s) already exist in database.";
            if($info_text != "") {
                $this->session->data['information'] = $info_text;
            }
        }        

        return;
    }

    public function editCategoryThumbnail($category_id, $data) {
        $sql1 = "UPDATE " . DB_PREFIX . "category SET thumbnail_image = '" . $this->db->escape($data['thumbnail_image']) . "' WHERE category_id = '" . (int)$category_id . "'";

        $this->db->query($sql1);

        $sql2 = "UPDATE " . DB_PREFIX . "category SET homethumb_image = '" . $this->db->escape($data['homethumb_image']) . "' WHERE category_id = '" . (int)$category_id . "'";

        $this->db->query($sql2);       
        
        $sql3 = "UPDATE " . DB_PREFIX . "category SET featured = '" . (int) $data['featured'] . "' WHERE category_id = '" . (int)$category_id . "'";

        $this->db->query($sql3);
    }

    public function getCategory($category_id) {
        $query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }


    public function getCategories($data = array()) {
        $sql = "SELECT c1.thumbnail_image, c1.homethumb_image, c1.featured, cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
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
}