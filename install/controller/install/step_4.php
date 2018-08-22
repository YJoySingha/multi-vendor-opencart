<?php
class ControllerInstallStep4 extends Controller {
	public function index() {
		$this->load->language('install/step_4');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_step_4'] = $this->language->get('text_step_4');
		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['text_admin'] = $this->language->get('text_admin');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_mail'] = $this->language->get('text_mail');
		$data['text_mail_description'] = $this->language->get('text_mail_description');
		$data['text_openbay'] = $this->language->get('text_openbay');
		$data['text_maxmind'] = $this->language->get('text_maxmind');
		$data['text_facebook'] = $this->language->get('text_facebook');
		$data['text_facebook_description'] = $this->language->get('text_facebook_description');
		$data['text_facebook_visit'] = $this->language->get('text_facebook_visit');
		$data['text_forum'] = $this->language->get('text_forum');
		$data['text_forum_description'] = $this->language->get('text_forum_description');
		$data['text_forum_visit'] = $this->language->get('text_forum_visit');
		$data['text_commercial'] = $this->language->get('text_commercial');
		$data['text_commercial_description'] = $this->language->get('text_commercial_description');
		$data['text_commercial_visit'] = $this->language->get('text_commercial_visit');
		$data['text_view'] = $this->language->get('text_view');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_downloads'] = $this->language->get('text_downloads');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_view'] = $this->language->get('text_view');

		$data['button_mail'] = $this->language->get('button_mail');
		$data['button_setup'] = $this->language->get('button_setup');
		$data['error_warning'] = $this->language->get('error_warning');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['maxmind'] = $this->url->link('3rd_party/maxmind');
		$data['openbay'] = $this->url->link('3rd_party/openbay');
		$data['extension'] = $this->url->link('3rd_party/extension');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$this->createTables();
		$this->sellerAltTables();
		$this->setDefaultSeller();
		$this->response->setOutput($this->load->view('install/step_4', $data));
	}

	private function createTables()
	{
		$order_trans = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "order_transaction` (
		`order_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
		`customer_id` int(11) NOT NULL,
		`order_id` int(11) NOT NULL,
		`description` text COLLATE utf8_bin NOT NULL,
		`amount` decimal(15,4) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`order_transaction_id`) 
		)";
		$this->db->query($order_trans);

		$s_address = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "saddress` (
		`address_id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL,
		`firstname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`lastname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`company` varchar(32) COLLATE utf8_bin NOT NULL,
		`company_id` varchar(32) COLLATE utf8_bin NOT NULL,
		`tax_id` varchar(32) COLLATE utf8_bin NOT NULL,
		`address_1` varchar(128) COLLATE utf8_bin NOT NULL,
		`address_2` varchar(128) COLLATE utf8_bin NOT NULL,
		`city` varchar(128) COLLATE utf8_bin NOT NULL,
		`postcode` varchar(10) COLLATE utf8_bin NOT NULL,
		`country_id` int(11) NOT NULL DEFAULT '0',
		`zone_id` int(11) NOT NULL DEFAULT '0',
		`city2` varchar(128) COLLATE utf8_bin NOT NULL,
		`postcode2` varchar(10) COLLATE utf8_bin NOT NULL,
		`country_id2` int(11) NOT NULL DEFAULT '0',
		`zone_id2` int(11) NOT NULL DEFAULT '0',
		PRIMARY KEY (`address_id`),
		KEY `seller_id` (`seller_id`)
		)";
		$this->db->query($s_address);

		$s_seller = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller` (
		`seller_id` int(11) NOT NULL,
		`vproduct_id` int(11) NOT NULL,
		`expiry_date` datetime NOT NULL,
		`pay_status` int(11) NOT NULL
		)";
		$this->db->query($s_seller);

		$seller_group = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_group` (
		`seller_group_id` int(11) NOT NULL AUTO_INCREMENT,
		`approval` int(1) NOT NULL,
		`company_id_display` int(1) NOT NULL,
		`company_id_required` int(1) NOT NULL,
		`tax_id_display` int(1) NOT NULL,
		`tax_id_required` int(1) NOT NULL,
		`sort_order` int(3) NOT NULL,
		PRIMARY KEY (`seller_group_id`)
		)";
		$this->db->query($seller_group);

		$s_group_desc = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_group_description` (
		`seller_group_id` int(11) NOT NULL,
		`language_id` int(11) NOT NULL,
		`name` varchar(32) COLLATE utf8_bin NOT NULL,
		`description` text COLLATE utf8_bin NOT NULL,
		PRIMARY KEY (`seller_group_id`,`language_id`)
		)";
		$this->db->query($s_group_desc);

		$seller_ip = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_ip` (
		`seller_ip_id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL,
		`ip` varchar(40) COLLATE utf8_bin NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`seller_ip_id`),
		KEY `ip` (`ip`)
		)";
		$this->db->query($seller_ip);

		$seller_message = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_message` ( `message_id` INT(11) NOT NULL AUTO_INCREMENT ,
		`seller_id` VARCHAR(20) NOT NULL ,
		`customer` VARCHAR(255) NOT NULL , 
		`product_name` VARCHAR(255) NOT NULL , 
		`email` VARCHAR(30) NOT NULL , 
		`phone` VARCHAR(20) NOT NULL ,
		`message` TEXT NOT NULL,
		`reply` TEXT NOT NULL ,
		`date_added` DATETIME NOT NULL,
		`date_reply` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY (`message_id`))";
		$this->db->query($seller_message);

		$reply_message = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_message_reply` ( `reply_id` INT(11) NOT NULL AUTO_INCREMENT ,
		`seller_id` VARCHAR(11) NOT NULL ,
		`message_id` VARCHAR(11) NOT NULL ,
		`content` TEXT NOT NULL ,
		`email` VARCHAR(100) NOT NULL,
		`date_added` DATETIME NOT NULL,
		PRIMARY KEY  (`reply_id`)
		)";
		$this->db->query($reply_message);
		$ip_blacklist = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_ip_blacklist` (
		`seller_ip_blacklist_id` int(11) NOT NULL AUTO_INCREMENT,
		`ip` varchar(40) COLLATE utf8_bin NOT NULL,
		PRIMARY KEY (`seller_ip_blacklist_id`),
		KEY `ip` (`ip`)
		)";
		$this->db->query($ip_blacklist);

		$seller_online = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_online` (
		`ip` varchar(40) COLLATE utf8_bin NOT NULL,
		`seller_id` int(11) NOT NULL,
		`url` text COLLATE utf8_bin NOT NULL,
		`referer` text COLLATE utf8_bin NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`ip`)
		)";
		$this->db->query($seller_online);
		$seller_payment = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_payment` (
		`payment_id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL,
		`payment_info` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		`payment_status` tinyint(5) NOT NULL,
		`payment_amount` decimal(15,4) NOT NULL DEFAULT '0.0000',
		`payment_date` datetime NOT NULL,
		PRIMARY KEY (`payment_id`)
		)";
		$this->db->query($seller_payment);
		$seller_reward = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_reward` (
		`seller_reward_id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL DEFAULT '0',
		`order_id` int(11) NOT NULL DEFAULT '0',
		`description` text COLLATE utf8_bin NOT NULL,
		`points` int(8) NOT NULL DEFAULT '0',
		`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY (`seller_reward_id`)
		)";
		$this->db->query($seller_reward);

		$seller_trans = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_transaction` (
		`seller_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL,
		`order_id` int(11) NOT NULL,
		`order_product_id` int(11) NOT NULL,
		`description` text COLLATE utf8_bin NOT NULL,
		`amount` decimal(15,4) NOT NULL,
		`sub_total` decimal(15,4) NOT NULL,
		`commission` decimal(15,4) NOT NULL,
		`date_added` datetime NOT NULL,
		`transaction_status` int(11) NOT NULL,
		PRIMARY KEY (`seller_transaction_id`)
		)";
		$this->db->query($seller_trans);

		$up_sellers = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."upgraded_sellers` (
		`upgrade_id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL,
		`commission_id` int(11) NOT NULL,
		`old_commission_id` int(11) NOT NULL,
		`amount` float(15,4) NOT NULL,
		`upgrade_date` datetime NOT NULL,
		`expiry_date` datetime NOT NULL,
		`upgradedby` varchar(100) NOT NULL,
		PRIMARY KEY (`upgrade_id`)
		)";
		$this->db->query($up_sellers);

		$seller_activity = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_activity` (
		`activity_id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL,
		`key` varchar(64) NOT NULL,
		`data` text NOT NULL,
		`ip` varchar(40) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`activity_id`)
		)";
		$this->db->query($seller_activity);

		$seller_rev = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "sellerreview` (
		`review_id` int(11) NOT NULL AUTO_INCREMENT,
		`customer_id` int(11) NOT NULL,
		`order_id` int(11) NOT NULL,
		`seller_id` int(11) NOT NULL,
		`author` varchar(64) NOT NULL,
		`text` text NOT NULL,
		`rating` int(1) NOT NULL,
		`status` tinyint(1) NOT NULL DEFAULT '0',
		`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		PRIMARY KEY (`review_id`,`order_id`,`seller_id`)
		)";
		$this->db->query($seller_rev);

		$seller_pd = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "sellers_products` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`seller_id` int(11) NOT NULL,
		`product_id` int(11) NOT NULL,
		`price` decimal(15,4) NOT NULL DEFAULT '0.0000',
		`quantity` int(11) NOT NULL,
		`date_added` datetime NOT NULL,
		`status` int(11) NOT NULL DEFAULT '1',
		PRIMARY KEY (`id`)
		)";
		$this->db->query($seller_pd);

		$comm_table = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "commission` (
		`commission_id` int(11) NOT NULL AUTO_INCREMENT,
		`commission_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		`commission_type` tinyint(4) NOT NULL,
		`commission` int(11) NOT NULL,
		`product_limit` int(11) NOT NULL,
		`sort_order` int(11) NOT NULL,
		`date_add` datetime NOT NULL,
		`amount` float NOT NULL,
		`commission_discount` int(11) NOT NULL,
		`per` int(11) NOT NULL,
		`duration_id` varchar(255) NOT NULL,
		`description` text NOT NULL,
		PRIMARY KEY (`commission_id`)
		)";
		$this->db->query($comm_table);

		$comm_rates = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "commission_rates` (
		`category_id` int(11) NOT NULL,
		`commission_id` int(11) NOT NULL,
		`commission_rate` int(11) NOT NULL
		)";
		$this->db->query($comm_rates);

		$seller_table = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "sellers` (
		`seller_id` int(11) NOT NULL AUTO_INCREMENT,
		`commission_id` int(11) NOT NULL,
		`store_id` int(11) NOT NULL DEFAULT '0',
		`username` varchar(128) COLLATE utf8_bin NOT NULL,
		`firstname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`lastname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`email` varchar(96) COLLATE utf8_bin NOT NULL DEFAULT '',
		`paypal_email` varchar(96) COLLATE utf8_bin NOT NULL,
		`payee_name` varchar(255) COLLATE utf8_bin NOT NULL,
		`tin_no` varchar(255) COLLATE utf8_bin NOT NULL,
		`bank_name` varchar(96) COLLATE utf8_bin NOT NULL DEFAULT '',
		`account_number` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`account_name` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`branch` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`ifsccode` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`telephone` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`fax` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
		`password` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
		`salt` varchar(9) COLLATE utf8_bin NOT NULL DEFAULT '',
		`cart` text COLLATE utf8_bin,
		`wishlist` text COLLATE utf8_bin,
		`paypalorcheque` tinyint(1) NOT NULL DEFAULT '0',
		`address_id` int(11) NOT NULL DEFAULT '0',
		`seller_group_id` int(11) NOT NULL,
		`ip` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
		`status` tinyint(1) NOT NULL,
		`approved` tinyint(1) NOT NULL,
		`token` varchar(255) COLLATE utf8_bin NOT NULL,
		`date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
		`foldername` varchar(255) COLLATE utf8_bin DEFAULT NULL,
		`aboutus` text COLLATE utf8_bin NOT NULL,
		`expiry_date` datetime NOT NULL,
		`pay_status` int(11) NOT NULL,
		PRIMARY KEY (`seller_id`)
		)";
		$this->db->query($seller_table);
	}

	//alt Seller db
	private function sellerAltTables() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "commission`");
		if(!$query->num_rows){
			$sqls22 = "INSERT INTO `" .DB_PREFIX. "commission` (`commission_id`, `commission_name`, `commission_type`, `commission`, `product_limit`, `sort_order`, `date_add`) VALUES
			(1,	'Standard',	0,	10,	100,	2,	'0000-00-00 00:00:00'),
			(2,	'Business',	1,	20,	500,	3,	'0000-00-00 00:00:00'),
			(3,	'Pro',	0,	30,	10,	1000,	'0000-00-00 00:00:00');";
			$this->db->query($sqls22);
		}
		$abc = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."category' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc->num_rows==0){
			$a_1_sql = "ALTER TABLE " .DB_PREFIX. "category ADD COLUMN seller_id int(11) NOT NULL default 0";
			$this->db->query($a_1_sql);
		}
		$abc1 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND 
			TABLE_NAME='".DB_PREFIX."category' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc1->num_rows==0){
			$a_11_sql = "ALTER TABLE " .DB_PREFIX. "category ADD COLUMN approve smallint(6) NOT NULL default 0";
			$this->db->query($a_11_sql);
		}
		$abc2 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc2->num_rows==0){
			$psa_11_sql = "ALTER TABLE " .DB_PREFIX. "product ADD COLUMN approve smallint(6) NOT NULL default 0";
			$this->db->query($psa_11_sql);
		}
		$abc3 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."option' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc3->num_rows==0){
			$a_12_sql = "ALTER TABLE `" .DB_PREFIX. "option` ADD COLUMN seller_id int(11) NOT NULL default 0";
			$this->db->query($a_12_sql);
		}

		$abc4 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND TABLE_NAME='".DB_PREFIX."option' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc4->num_rows==0){
			$a_13_sql = "ALTER TABLE `" .DB_PREFIX. "option` ADD COLUMN approve smallint(6) NOT NULL default 0";
			$this->db->query($a_13_sql);
		}
		$abc5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."download' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc5->num_rows==0){
			$a_14_sql = "ALTER TABLE " .DB_PREFIX. "download ADD COLUMN seller_id int(11) NOT NULL default 0";
			$this->db->query($a_14_sql);
		}

		$abcc5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='commission' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abcc5->num_rows==0){
			$a_15_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD commission decimal(15,4) NOT NULL DEFAULT 0.0000";
			$this->db->query($a_15_sql);
		}
		$abccp5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='commissionper' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abccp5->num_rows==0){
			$a_17_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD commissionper decimal(15,4) NOT NULL DEFAULT 0.0000";
			$this->db->query($a_17_sql);
		}
		$abcst5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_total' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abcst5->num_rows==0){
			$a_18_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD seller_total decimal(15,4) NOT NULL DEFAULT 0.0000";
			$this->db->query($a_18_sql);
		}
		$abc64 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_paid_status' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc64->num_rows==0){
			$a_19_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD seller_paid_status tinyint(1) NOT NULL DEFAULT 0";
			$this->db->query($a_19_sql);
		}
		$abc65 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='product_status_id' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc65->num_rows==0){
			$a_20_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD product_status_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_20_sql);
		}
		$abc15 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_description' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc15->num_rows==0){
			$a_21_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD payment_description text NOT NULL";
			$this->db->query($a_21_sql);
		}
		$abc25 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_date' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc25->num_rows==0){
			$a_22_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD payment_date datetime NOT NULL";
			$this->db->query($a_22_sql);
		}
		$abc35 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc35->num_rows==0){
			$a_23_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_23_sql);
		}
		$abc25 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc25->num_rows==0){
			$a_24_sql = "ALTER TABLE " .DB_PREFIX. "product ADD seller_id int(11) NOT NULL DEFAULT 1";
			$this->db->query($a_24_sql);
		}
		$abcd5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='documentation' AND TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abcd5->num_rows==0){
			$a_25_sql = "ALTER TABLE " .DB_PREFIX. "product ADD documentation text COLLATE utf8_bin NOT NULL";
			$this->db->query($a_25_sql);
		}
		$abcoh5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."order_history' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abcoh5->num_rows==0){
			$a_26_sql = "ALTER TABLE " .DB_PREFIX. "order_history ADD seller_id int(11) NOT NULL DEFAULT 1";
			$this->db->query($a_26_sql);
		}
		$abcr5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."review' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abcr5->num_rows==0){
			$a_27_sql = "ALTER TABLE " .DB_PREFIX. "review ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_27_sql);
		}
		$abcd5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."product_discount' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abcd5->num_rows==0){
			$a_28_sql = "ALTER TABLE " .DB_PREFIX. "product_discount ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_28_sql);
		}
		$abco5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."product_option' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abco5->num_rows==0){
			$a_29_sql = "ALTER TABLE " .DB_PREFIX. "product_option ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_29_sql);
		}
		$alt_option_value = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."product_option_value' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($alt_option_value->num_rows==0){
			$a_30_sql = "ALTER TABLE " .DB_PREFIX. "product_option_value ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_30_sql);
		}
		$alt_product_special = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."product_special' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($alt_product_special->num_rows==0){
			$a_31_sql = "ALTER TABLE " .DB_PREFIX. "product_special ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_31_sql);
		}
		$abc60 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."product_attribute' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc60->num_rows==0){
			$a_32_sql = "ALTER TABLE " .DB_PREFIX. "product_attribute ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_32_sql);
		}
		$abc59 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."attribute' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc59->num_rows==0){
			$a_33_sql = "ALTER TABLE " .DB_PREFIX. "attribute ADD seller_id int(11) NOT NULL DEFAULT 0";
			$this->db->query($a_33_sql);
		}
		$abc58 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND TABLE_NAME='".DB_PREFIX."attribute' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc58->num_rows==0){
			$a_34_sql = "ALTER TABLE " .DB_PREFIX. "attribute ADD approve smallint(6) NOT NULL DEFAULT 0";
			$this->db->query($a_34_sql);
		}
		$abc57 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='amount' AND TABLE_NAME='".DB_PREFIX."commission' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc57->num_rows==0){
			$a_44_sql = "ALTER TABLE `".DB_PREFIX."commission`  ADD amount float NOT NULL";
			$this->db->query($a_44_sql);
		}
		$abc56 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='per' AND TABLE_NAME='".DB_PREFIX."commission' AND TABLE_SCHEMA='".DB_DATABASE."'");

		if($abc56->num_rows==0){
			$a_45_sql = "ALTER TABLE `".DB_PREFIX."commission`  ADD per int(11) NOT NULL";
			$this->db->query($a_45_sql);
		}

		$abc55 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='duration_id' AND TABLE_NAME='".DB_PREFIX."commission' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc55->num_rows==0){
			$a_46_sql = "ALTER TABLE `".DB_PREFIX."commission`  ADD  duration_id varchar(255) NOT NULL";
			$this->db->query($a_46_sql);
		}

		$abc54 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='expiry_date' AND TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc54->num_rows==0){
			$a_47_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  expiry_date datetime NOT NULL";
			$this->db->query($a_47_sql);
		}

		$abc53 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_status' AND TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc53->num_rows==0){
			$a_48_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  payment_status int(11) NOT NULL";
			$this->db->query($a_48_sql);
		}
		$abc52 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_date' AND TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc52->num_rows==0){
			$a_49_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  payment_date datetime NOT NULL";
			$this->db->query($a_49_sql);
		}
		$abc51 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_amount' AND TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc51->num_rows==0){
			$a_50_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  payment_amount float NOT NULL";
			$this->db->query($a_50_sql);
		}

		$abc50 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc50->num_rows==0){
			$asp_50_sql = "ALTER TABLE `".DB_PREFIX."product`  ADD approve int(11) NOT NULL";
			$this->db->query($asp_50_sql);
		}

		$abc501 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND TABLE_NAME='".DB_PREFIX."cart' AND TABLE_SCHEMA='".DB_DATABASE."'");
		if($abc501->num_rows==0){
			$asp_501_sql = "ALTER TABLE `".DB_PREFIX."cart`  ADD seller_id int(11) NOT NULL";
			$this->db->query($asp_501_sql);
		}
		$a_51_sql = "UPDATE " .DB_PREFIX. "category SET approve=1";
		$this->db->query($a_51_sql);
		$a_52_sql = "UPDATE " .DB_PREFIX. "product SET approve=1";
		$this->db->query($a_52_sql);
		$a_53_sql = "UPDATE `" .DB_PREFIX. "option` SET approve=1";
		$this->db->query($a_53_sql);
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` limit 1");
		if($query->num_rows){
			$userquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE LOWER(username) = '" . $this->db->escape(strtolower($query->row['firstname'].'_'.$query->row['lastname'])) . "'");
			if(!$userquery->num_rows){
				$emailquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE LOWER(email) = '" . $this->db->escape(strtolower($query->row['email'])) . "'");
				if(!$emailquery->num_rows){
					$config_sellercommission_id = $this->config->get('config_sellercommission_id');
					if(!$config_sellercommission_id){
						$config_sellercommission_id = 1;
					}
					$folderName = strtolower($query->row['firstname']).'_'.strtolower($query->row['lastname']);
					//$path = HTTPS_SERVER;//throwing error
					$fPath = DIR_IMAGE. $folderName;
					$exist = is_dir($fPath);
					if(!$exist) {
						mkdir("$fPath");
						chmod("$fPath", 0777);
					}
					$this->db->query("INSERT INTO " . DB_PREFIX . "sellers SET store_id = '" . (int)$this->config->get('config_store_id') . "',
						username = '" . $this->db->escape(strtolower($query->row['firstname'].'_'.$query->row['lastname'])) . "', firstname = '" . $this->db->escape($query->row['firstname']) . "', 
						lastname = '" . $this->db->escape($query->row['lastname']) . "', email = '" . $this->db->escape($query->row['email']) . "'
						, 
						salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "',
						password = '" . $this->db->escape($query->row['password']) . "',    
						foldername = '" . $this->db->escape(strtolower($query->row['firstname'].'_'.$query->row['lastname'])) . "', 
						commission_id = '" . (int)$config_sellercommission_id . "', 
						ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved='1',pay_status='1',payment_status='1',date_added = NOW()");
					$seller_id = $this->db->getLastId();
					$username = $query->row['firstname'].'_'.$query->row['lastname'];
					if($username) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET language_id = '1', query = 'seller_id=" . (int)$seller_id . "', keyword = '" . $this->db->escape(strtolower($username)) . "'");
					}
					$this->db->query("INSERT INTO " . DB_PREFIX . "saddress SET 
						seller_id = '" . (int)$seller_id . "', 
						firstname = '" . $this->db->escape($query->row['firstname']) . "', 
						lastname = '" . $this->db->escape($query->row['lastname']) . "'
						");
					$address_id = $this->db->getLastId();
					$this->db->query("UPDATE " . DB_PREFIX . "sellers SET address_id = '" . (int)$address_id . "' WHERE
						seller_id = '" . (int)$seller_id . "'");
					$store_id = 0;
					$code = "config";
					$key = "config_defaultseller_id";
					$value = $seller_id;
					$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
				}
			}
		}
		
	}

	public function setDefaultSeller()
	{
		$defaultseller = $this->config->get('config_defaultseller_id');

		if(!$defaultseller){
			$default_seller_id = 1;
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product");
			foreach($query->rows as $row){
				$default_sql1 = "SELECT * FROM " . DB_PREFIX . "sellers_products WHERE 		
				product_id = '" . (int)$row['product_id'] . "'";
				$product_query = $this->db->query($default_sql1);
				if(!$product_query->num_rows){
					$update_products = "UPDATE " . DB_PREFIX . "product 
					SET seller_id = '" . (int)$default_seller_id . "' 
					WHERE product_id = '" . (int)$row['product_id'] . "'";
					$this->db->query($update_products);

					$insert_into_seller = "INSERT INTO " . DB_PREFIX . "seller SET 
					vproduct_id = '" . (int)$row['product_id'] . "', 
					seller_id = '" . (int)$default_seller_id . "'";
					$this->db->query($insert_into_seller);

					$update_pd_option = "UPDATE " . DB_PREFIX . "product_option SET seller_id = '" . (int)$default_seller_id . "' WHERE product_id = '" . (int)$row['product_id'] . "'";
					$this->db->query($update_pd_option);

					$update_pd_option_val = "UPDATE " . DB_PREFIX . "product_option_value SET seller_id = '" . (int)$default_seller_id . "' WHERE product_id = '" . (int)$row['product_id'] . "'";
					$this->db->query($update_pd_option_val);

					$update_pd_att = "UPDATE " . DB_PREFIX . "product_attribute SET seller_id = '" . (int)$default_seller_id . "' WHERE product_id = '" . (int)$row['product_id'] . "'";
					$this->db->query($update_pd_att);

					$update_pd_discount = "UPDATE " . DB_PREFIX . "product_discount SET seller_id = '" . (int)$default_seller_id . "' WHERE product_id = '" . (int)$row['product_id'] . "'";
					$this->db->query($update_pd_discount);

					$update_pd_special = "UPDATE " . DB_PREFIX . "product_special SET seller_id = '" . (int)$default_seller_id . "' WHERE product_id = '" . (int)$row['product_id'] . "'";
					$this->db->query($update_pd_special);

					$update_pd_special = "INSERT INTO " . DB_PREFIX . "sellers_products SET
					product_id = '" . (int)$row['product_id'] . "', 
					seller_id = '" . (int)$default_seller_id . "',
					quantity = '" . (int)$row['quantity'] . "',
					price = '" . (float)$row['price'] . "',
					date_added = NOW()" ;
					$this->db->query($update_pd_special);

				}
			}
		}
		else {
			echo "Default seller not set!";
		}
	}

	private function addSellerPermissions()
	{
		# TO DO
	}
}
