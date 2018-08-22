<?php
class ModelCatalogOctestimonial extends Model {
    public function install(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `".DB_PREFIX."testimonial` (
            `testimonial_id` int(11) NOT NULL AUTO_INCREMENT,
            `status` int(1) NOT NULL default 0,
            `sort_order` int(11) NOT NULL default 0,
            PRIMARY KEY (`testimonial_id`)
            )DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
        $this->db->query(
            "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."testimonial_description`(
                `testimonial_id` int(10) unsigned NOT NULL,
                `language_id` int(10) unsigned NOT NULL,
                `image` varchar(255) collate utf8_bin ,
                `customer_name` varchar(255) collate utf8_bin NOT NULL,
                `content` text collate utf8_bin NOT NULL,
                PRIMARY KEY (`testimonial_id`,`language_id`)
                )
                DEFAULT CHARSET=utf8;");
        $sql =  array();
        $sql[] = "TRUNCATE TABLE ".DB_PREFIX."testimonial;";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('1', '1', '1');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('2', '1', '2');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('3', '1', '3');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('4', '1', '4');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('5', '1', '5');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('6', '1', '6');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('7', '1', '7');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial VALUES ('8', '1', '8');";
        $sql[] = "TRUNCATE TABLE ".DB_PREFIX."testimonial_description;";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('1', '1', 'catalog/testimonial/testimonial_1.png', 'Rebecka Filson', 'This is Photoshops version  of Lorem Ipsum. Proin gravida nibh vel velit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. In molestie augue magna. Pellentesque felis lorem, pulvinar sed eros non, sagittis consequat urna. Proin id dui tempor, imperdiet nisi et, hendrerit quam. Quisque tempus lorem nisl, non adipiscing arcu tristique ac. Sed eget mollis tellus, a varius diam. In a consectetur tellus, quis molestie ligula. Vivamus sit amet sem faucibus, dignissim augue ac, interdum metus.');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('2', '1', 'catalog/testimonial/testimonial_1.png', 'Nathanael Jaworski', 'Mauris blandit, metus a venenatis lacinia, felis enim tincidunt est, condimentum vulputate orci augue eu metus. Fusce dictum, nisi et semper ultricies, felis tortor blandit odio, egestas consequat purus nisi eu est. Morbi porttitor porta nunc in elementum. Aliquam congue, nibh at dignissim scelerisque, tortor nisl placerat tortor, sit amet suscipit augue nisi quis elit. Nam dapibus, diam at blandit molestie, dolor dui vulputate ante, a tincidunt leo turpis quis enim. Etiam facilisis adipiscing lorem eget aliquam. Aliquam ac nisi vulputate, mattis nunc non, sollicitudin lorem. Nulla velit leo, dictum non massa vitae, commodo facilisis lacus. Vivamus ultricies urna a massa aliquet, et congue neque commodo.');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('3', '1', 'catalog/testimonial/testimonial_1.png', 'Magdalena Valencia', 'Sed vel urna at dui iaculis gravida. Maecenas pretium, velit vitae placerat faucibus, velit quam facilisis elit, sit amet lacinia est est id ligula. Duis feugiat quam non justo faucibus, in gravida diam tempor. Nam viverra enim non ipsum ornare, condimentum blandit diam mattis. Maecenas gravida molestie felis ac tincidunt. Vivamus auctor magna sit amet nisl luctus consequat. Donec viverra leo viverra, auctor justo eu, venenatis eros. Praesent metus lectus, tempor id leo vel, convallis lobortis tellus.');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('4', '1', 'catalog/testimonial/testimonial_1.png', 'Alva Ono', 'Vivamus a lobortis ipsum, vel condimentum magna. Etiam id turpis tortor. Nunc scelerisque, nisi a blandit varius, nunc purus venenatis ligula, sed venenatis orci augue nec sapien. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin mattis, enim blandit molestie molestie, nisl quam bibendum nisi, sed luctus felis justo ut nisl. In hac habitasse platea dictumst. Duis quis aliquam lectus, ac dapibus turpis. Nulla convallis vel felis eget porttitor. Morbi nisl metus, bibendum vitae luctus accumsan, consequat id quam.');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('5', '1', 'catalog/testimonial/testimonial_1.png', 'Dewey Tetzlaff', 'This is Photoshops version  of Lorem Ipsum. Proin gravida nibh vel velit.Lorem ipsum dolor sit amet, consectetur adipiscing elit. In molestie augue magna. Pellentesque felis lorem, pulvinar sed eros non, sagittis consequat urna. Proin id dui tempor, imperdiet nisi et, hendrerit quam. Quisque tempus lorem nisl, non adipiscing arcu tristique ac. Sed eget mollis tellus, a varius diam. In a consectetur tellus, quis molestie ligula. Vivamus sit amet sem faucibus, dignissim augue ac, interdum metus.');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('6', '1', 'catalog/testimonial/testimonial_1.png', 'Lavina Wilderman', 'Mauris blandit, metus a venenatis lacinia, felis enim tincidunt est, condimentum vulputate orci augue eu metus. Fusce dictum, nisi et semper ultricies, felis tortor blandit odio, egestas consequat purus nisi eu est. Morbi porttitor porta nunc in elementum. Aliquam congue, nibh at dignissim scelerisque, tortor nisl placerat tortor, sit amet suscipit augue nisi quis elit. Nam dapibus, diam at blandit molestie, dolor dui vulputate ante, a tincidunt leo turpis quis enim. Etiam facilisis adipiscing lorem eget aliquam. Aliquam ac nisi vulputate, mattis nunc non, sollicitudin lorem. Nulla velit leo, dictum non massa vitae, commodo facilisis lacus. Vivamus ultricies urna a massa aliquet, et congue neque commodo.');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('7', '1', 'catalog/testimonial/testimonial_1.png', 'Amber Laha', 'Sed vel urna at dui iaculis gravida. Maecenas pretium, velit vitae placerat faucibus, velit quam facilisis elit, sit amet lacinia est est id ligula. Duis feugiat quam non justo faucibus, in gravida diam tempor. Nam viverra enim non ipsum ornare, condimentum blandit diam mattis. Maecenas gravida molestie felis ac tincidunt. Vivamus auctor magna sit amet nisl luctus consequat. Donec viverra leo viverra, auctor justo eu, venenatis eros. Praesent metus lectus, tempor id leo vel, convallis lobortis tellus.');";
        $sql[] = "INSERT INTO ".DB_PREFIX."testimonial_description VALUES ('8', '1', 'catalog/testimonial/testimonial_1.png', 'Lindsy Neloms', 'Vivamus a lobortis ipsum, vel condimentum magna. Etiam id turpis tortor. Nunc scelerisque, nisi a blandit varius, nunc purus venenatis ligula, sed venenatis orci augue nec sapien. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin mattis, enim blandit molestie molestie, nisl quam bibendum nisi, sed luctus felis justo ut nisl. In hac habitasse platea dictumst. Duis quis aliquam lectus, ac dapibus turpis. Nulla convallis vel felis eget porttitor. Morbi nisl metus, bibendum vitae luctus accumsan, consequat id quam.');";
        foreach($sql as $q ){
            $this->db->query($q);
        }
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "testimonial`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "testimonial_description`");
    }
    public function addTestimonial($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "testimonial SET status = '" . (int)$this->request->post['status'] . "', sort_order = '" . (int)$this->request->post['sort_order'] . "'");
        $testimonial_id = $this->db->getLastId();
        //	foreach ($data['testimonial_description'] as $language_id => $value) {
        //  var_dump( $data['testimonial_description']);die;
        $this->db->query("INSERT INTO " . DB_PREFIX . "testimonial_description SET testimonial_id = '" . (int)$testimonial_id . "', customer_name = '" . $data['testimonial_description']['customer_name'] . "',image = '".$this->db->escape($data['image'])."', content = '" . $data['testimonial_description']['content'] . "'");
        //}
        $this->cache->delete('testimonial');
    }

    public function editTestimonial($testimonial_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "testimonial SET status = '" . (int)$this->request->post['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE testimonial_id = '" . (int)$testimonial_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "testimonial_description WHERE testimonial_id = '" . (int)$testimonial_id . "'");
        //	foreach ($data['testimonial_description'] as $language_id => $value) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "testimonial_description SET testimonial_id = '" . (int)$testimonial_id . "', customer_name = '" . $data['testimonial_description']['customer_name'] . "',image = '".$this->db->escape($data['image'])."', content = '" .  $data['testimonial_description']['content'] . "'");
        //	}
    }

    public function deleteTestimonial($testimonial_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "testimonial WHERE testimonial_id = '" . (int)$testimonial_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "testimonial_description WHERE testimonial_id = '" . (int)$testimonial_id . "'");

        $this->cache->delete('testimonial');
    }


    public function getTestimonial($testimonial_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) WHERE t.testimonial_id = '" . (int)$testimonial_id . "' ");
        return $query->row;
    }

    public function getTestimonials($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "testimonial_description td LEFT JOIN " . DB_PREFIX . "testimonial t ON (t.testimonial_id = td.testimonial_id)";

            $sort_data = array(
                'td.customer_name',
                't.sort_order'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY td.customer_name";
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
            $testimonial_data = $this->cache->get('testimonial.' . $this->config->get('config_language_id'));
            if (!$testimonial_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "testimonial t LEFT JOIN " . DB_PREFIX . "testimonial_description td ON (t.testimonial_id = td.testimonial_id) ORDER BY td.customer_name ASC");
                $testimonial_data = $query->rows;
                $this->cache->set('testimonial.' . $this->config->get('config_language_id'), $testimonial_data);
            }
            return $testimonial_data;
        }
    }

    public function getTestimonialDescriptions($testimonial_id) {
        $testimonial_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "testimonial_description WHERE testimonial_id = '" . (int)$testimonial_id . "'");

        foreach ($query->rows as $result) {
            $testimonial_description_data= array(
                'customer_name'       => $result['customer_name'],
                'content' => $result['content']
            );
        }

        return $testimonial_description_data;
    }

    public function getTotalTestimonials() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "testimonial");

        return $query->row['total'];
    }

}
?>