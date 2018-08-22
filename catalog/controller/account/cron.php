<?php
class ControllerAccountCron extends Controller {
	public function index() {
			define('DIR_OPENCART', str_replace('\\', '/', realpath(DIR_APPLICATION . '../')) . '/');
			if (!file_exists(DIR_OPENCART . 'seller/config.php')) {
				echo 'Warning: seller/config.php does not exist. You need to create 1 file in  seller/config.php!';die;
			} elseif (!is_writable(DIR_OPENCART . 'seller/config.php')) {
				echo 'Warning: seller/config.php needs to be writable for multiseller to be installed!';die;
			}
			$output  = '<?php' . "\n";
			$output .= '// HTTP' . "\n";
			$output .= 'define(\'HTTP_SERVER\', \'' . HTTP_SERVER . 'seller/\');' . "\n\n";
			$output .= '// HTTPS' . "\n";
			$output .= 'define(\'HTTPS_SERVER\', \'' . HTTP_SERVER . 'seller/\');' . "\n\n";
			$output .= 'define(\'HTTP_SERVER1\', \'' . HTTP_SERVER . '\');' . "\n\n";
			$output .= 'define(\'HTTPS_SERVER1\', \'' . HTTP_SERVER . '\');' . "\n\n";
			$output .= '// DIR' . "\n";
			$output .= 'define(\'DIR_APPLICATION\', \'' . DIR_OPENCART . 'seller/catalog/\');' . "\n";
			$output .= 'define(\'DIR_SYSTEM\', \'' . DIR_OPENCART . 'seller/system/\');' . "\n";
			$output .= 'define(\'DIR_IMAGE\', \'' . DIR_OPENCART . 'image/\');' . "\n";
			$output .= 'define(\'DIR_LANGUAGE\', \'' . DIR_OPENCART . 'seller/catalog/language/\');' . "\n";
			$output .= 'define(\'DIR_TEMPLATE\', \'' . DIR_OPENCART . 'seller/catalog/view/theme/\');' . "\n";
			$output .= 'define(\'DIR_CONFIG\', \'' . DIR_OPENCART . 'seller/system/config/\');' . "\n";
			$output .= 'define(\'DIR_CACHE\', \'' . DIR_OPENCART . 'seller/system/storage/cache/\');' . "\n";
			$output .= 'define(\'DIR_DOWNLOAD\', \'' . DIR_OPENCART . 'seller/system/storage/download/\');' . "\n";
			$output .= 'define(\'DIR_LOGS\', \'' . DIR_OPENCART . 'seller/system/storage/logs/\');' . "\n";
			$output .= 'define(\'DIR_MODIFICATION\', \'' . DIR_OPENCART . 'seller/system/storage/modification/\');' . "\n";
			$output .= 'define(\'DIR_UPLOAD\', \'' . DIR_OPENCART . 'seller/system/storage/upload/\');' . "\n\n";
			$output .= '// DB' . "\n";
			$output .= 'define(\'DB_DRIVER\', \'' . addslashes(DB_DRIVER) . '\');' . "\n";
			$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes(DB_HOSTNAME) . '\');' . "\n";
			$output .= 'define(\'DB_USERNAME\', \'' . addslashes(DB_USERNAME) . '\');' . "\n";
			$output .= 'define(\'DB_PASSWORD\', \'' . addslashes(DB_PASSWORD) . '\');' . "\n";
			$output .= 'define(\'DB_DATABASE\', \'' . addslashes(DB_DATABASE) . '\');' . "\n";
			$output .= 'define(\'DB_PORT\', \'' . addslashes(DB_PORT) . '\');' . "\n";
			$output .= 'define(\'DB_PREFIX\', \'' . addslashes(DB_PREFIX) . '\');' . "\n";
			$file = fopen(DIR_OPENCART . 'seller/config.php', 'w');
			fwrite($file, $output);
			fclose($file);
			$sql = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "order_transaction` (
			`order_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
			`customer_id` int(11) NOT NULL,
			`order_id` int(11) NOT NULL,
			`description` text COLLATE utf8_bin NOT NULL,
			`amount` decimal(15,4) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`order_transaction_id`) 
			)";
		$this->db->query($sql);
		$sql1 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_activity` (
		 `activity_id` int(11) NOT NULL AUTO_INCREMENT,
		  `seller_id` int(11) NOT NULL,
		  `key` varchar(64) NOT NULL,
		  `data` text NOT NULL,
		  `ip` varchar(40) NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`activity_id`)
		)";
		$this->db->query($sql1);
		$sql12 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "sellerreview` (
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
	$this->db->query($sql12);
		$sql13 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "sellers_products` (
		 `id` int(11) NOT NULL AUTO_INCREMENT,
		  `seller_id` int(11) NOT NULL,
		  `product_id` int(11) NOT NULL,
		  `price` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  `quantity` int(11) NOT NULL,
		  `date_added` datetime NOT NULL,
		  `status` int(11) NOT NULL DEFAULT '1',
		  PRIMARY KEY (`id`)
		)";
	$this->db->query($sql13);
      $sql22 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "commission` (
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
		$this->db->query($sql22);
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "commission`");
		if($query->num_rows){
		}else{
		$sqls22 = "INSERT INTO `" .DB_PREFIX. "commission` (`commission_id`, `commission_name`, `commission_type`, `commission`, `product_limit`, `sort_order`, `date_add`) VALUES
		(1,	'Gold',	0,	10,	20,	2,	'0000-00-00 00:00:00'),
		(2,	'Silver',	1,	20,	5,	3,	'0000-00-00 00:00:00'),
		(3,	'Bronze',	0,	30,	10,	1,	'0000-00-00 00:00:00');";
		$this->db->query($sqls22);
		}
		$sql23 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "commission_rates` (
		`category_id` int(11) NOT NULL,
		`commission_id` int(11) NOT NULL,
		`commission_rate` int(11) NOT NULL
		)";
	$this->db->query($sql23);
		$sql24 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "saddress` (
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
	$this->db->query($sql24);
		$sql25 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller` (
		`seller_id` int(11) NOT NULL,
		  `vproduct_id` int(11) NOT NULL,
		  `expiry_date` datetime NOT NULL,
		  `pay_status` int(11) NOT NULL
		)";
	$this->db->query($sql25);
		$sql26 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "sellers` (
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
	$this->db->query($sql26);
		$sql27 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_group` (
		`seller_group_id` int(11) NOT NULL AUTO_INCREMENT,
		  `approval` int(1) NOT NULL,
		  `company_id_display` int(1) NOT NULL,
		  `company_id_required` int(1) NOT NULL,
		  `tax_id_display` int(1) NOT NULL,
		  `tax_id_required` int(1) NOT NULL,
		  `sort_order` int(3) NOT NULL,
		  PRIMARY KEY (`seller_group_id`)
		)";
	$this->db->query($sql27);
		$sql28 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_group_description` (
		`seller_group_id` int(11) NOT NULL,
		  `language_id` int(11) NOT NULL,
		  `name` varchar(32) COLLATE utf8_bin NOT NULL,
		  `description` text COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`seller_group_id`,`language_id`)
		)";
	$this->db->query($sql28);
		$sql30 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_ip` (
		`seller_ip_id` int(11) NOT NULL AUTO_INCREMENT,
		  `seller_id` int(11) NOT NULL,
		  `ip` varchar(40) COLLATE utf8_bin NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`seller_ip_id`),
		  KEY `ip` (`ip`)
		)";
	$this->db->query($sql30);
		$sql31 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_ip_blacklist` (
		`seller_ip_blacklist_id` int(11) NOT NULL AUTO_INCREMENT,
		  `ip` varchar(40) COLLATE utf8_bin NOT NULL,
		  PRIMARY KEY (`seller_ip_blacklist_id`),
		  KEY `ip` (`ip`)
		)";
	$this->db->query($sql31);
		$sql32 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_online` (
		 `ip` varchar(40) COLLATE utf8_bin NOT NULL,
		  `seller_id` int(11) NOT NULL,
		  `url` text COLLATE utf8_bin NOT NULL,
		  `referer` text COLLATE utf8_bin NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`ip`)
		)";
	$this->db->query($sql32);
	$sql33 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_payment` (
		`payment_id` int(11) NOT NULL AUTO_INCREMENT,
		  `seller_id` int(11) NOT NULL,
		  `payment_info` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		  `payment_status` tinyint(5) NOT NULL,
		  `payment_amount` decimal(15,4) NOT NULL DEFAULT '0.0000',
		  `payment_date` datetime NOT NULL,
		  PRIMARY KEY (`payment_id`)
		)";
	$this->db->query($sql33);
	$sql34 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_reward` (
		 `seller_reward_id` int(11) NOT NULL AUTO_INCREMENT,
		  `seller_id` int(11) NOT NULL DEFAULT '0',
		  `order_id` int(11) NOT NULL DEFAULT '0',
		  `description` text COLLATE utf8_bin NOT NULL,
		  `points` int(8) NOT NULL DEFAULT '0',
		  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		  PRIMARY KEY (`seller_reward_id`)
		)";
	$this->db->query($sql34);
	$sql35 = "CREATE TABLE IF NOT EXISTS `" .DB_PREFIX. "seller_transaction` (
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
	$this->db->query($sql35);
	$sql100 = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX."upgraded_sellers` (
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
	$this->db->query($sql100);
			/**********************************************************************************/		
			/*Here we create alter the table *************************************************************/
			$abc = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."category' AND TABLE_SCHEMA='".DB_DATABASE."'");
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
			$abc2 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND 
			TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc2->num_rows==0){
			$psa_11_sql = "ALTER TABLE " .DB_PREFIX. "product ADD COLUMN approve smallint(6) NOT NULL default 0";
			$this->db->query($psa_11_sql);
			}
			$abc3 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."option' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc3->num_rows==0){			
			$a_12_sql = "ALTER TABLE " .DB_PREFIX. "option ADD COLUMN seller_id int(11) NOT NULL default 0";
			$this->db->query($a_12_sql);
			}
			$abc4 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND 
			TABLE_NAME='".DB_PREFIX."option' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc4->num_rows==0){		
			$a_13_sql = "ALTER TABLE " .DB_PREFIX. "option ADD COLUMN approve smallint(6) NOT NULL default 0";
			$this->db->query($a_13_sql);
			}
			$abc5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."download' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc5->num_rows==0){	
			$a_14_sql = "ALTER TABLE " .DB_PREFIX. "download ADD COLUMN seller_id int(11) NOT NULL default 0";
		$this->db->query($a_14_sql);
		}
		$abcc5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='commission' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcc5->num_rows==0){	
			$a_15_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD commission decimal(15,4) NOT NULL DEFAULT 0.0000";
		$this->db->query($a_15_sql);
		}
		$abccp5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='commissionper' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abccp5->num_rows==0){	
			$a_17_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD commissionper decimal(15,4) NOT NULL DEFAULT 0.0000";
		$this->db->query($a_17_sql);
		}
		$abcst5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_total' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcst5->num_rows==0){	
			$a_18_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD seller_total decimal(15,4) NOT NULL DEFAULT 0.0000";
		$this->db->query($a_18_sql);
		}
			$abc64 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_paid_status' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc64->num_rows==0){	
			$a_19_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD seller_paid_status tinyint(1) NOT NULL DEFAULT 0";
		$this->db->query($a_19_sql);
			}
			$abc65 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='product_status_id' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc65->num_rows==0){	
			$a_20_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD product_status_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_20_sql);
		}
		$abc15 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_description' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc15->num_rows==0){	
			$a_21_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD payment_description text NOT NULL";
		$this->db->query($a_21_sql);
		}
		$abc25 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_date' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc25->num_rows==0){	
			$a_22_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD payment_date datetime NOT NULL";
		$this->db->query($a_22_sql);
		}
			$abc35 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."order_product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc35->num_rows==0){	
			$a_23_sql = "ALTER TABLE " .DB_PREFIX. "order_product ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_23_sql);
		}
		$abc25 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc25->num_rows==0){	
			$a_24_sql = "ALTER TABLE " .DB_PREFIX. "product ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_24_sql);
		}
			$abcd5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='documentation' AND 
			TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcd5->num_rows==0){	
			$a_25_sql = "ALTER TABLE " .DB_PREFIX. "product ADD documentation text COLLATE utf8_bin NOT NULL";
		$this->db->query($a_25_sql);
		}
		$abcoh5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."order_history' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcoh5->num_rows==0){	
			$a_26_sql = "ALTER TABLE " .DB_PREFIX. "order_history ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_26_sql);
		}
		$abcr5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."review' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcr5->num_rows==0){	
			$a_27_sql = "ALTER TABLE " .DB_PREFIX. "review ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_27_sql);
			}
			$abcd5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."product_discount' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcd5->num_rows==0){	
		   $a_28_sql = "ALTER TABLE " .DB_PREFIX. "product_discount ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_28_sql);
		}
		$abco5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."product_option' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abco5->num_rows==0){	
			$a_29_sql = "ALTER TABLE " .DB_PREFIX. "product_option ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_29_sql);
		}
		$abcv5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."product_option_value' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcv5->num_rows==0){	
			$a_30_sql = "ALTER TABLE " .DB_PREFIX. "product_option_value ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_30_sql);
		}
		$abcs5 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."product_special' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abcs5->num_rows==0){	
			$a_31_sql = "ALTER TABLE " .DB_PREFIX. "product_special ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_31_sql);
		}
		$abc60 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."product_attribute' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc60->num_rows==0){	
			$a_32_sql = "ALTER TABLE " .DB_PREFIX. "product_attribute ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_32_sql);
		}
		$abc59 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."attribute' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc59->num_rows==0){	
			$a_33_sql = "ALTER TABLE " .DB_PREFIX. "attribute ADD seller_id int(11) NOT NULL DEFAULT 0";
		$this->db->query($a_33_sql);
		}
		$abc58 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND 
			TABLE_NAME='".DB_PREFIX."attribute' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc58->num_rows==0){	
			$a_34_sql = "ALTER TABLE " .DB_PREFIX. "attribute ADD approve smallint(6) NOT NULL DEFAULT 0";
		$this->db->query($a_34_sql);
			}
			$abc57 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='amount' AND 
			TABLE_NAME='".DB_PREFIX."commission' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc57->num_rows==0){	
			$a_44_sql = "ALTER TABLE `".DB_PREFIX."commission`  ADD amount float NOT NULL";
			$this->db->query($a_44_sql);}
			$abc56 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='per' AND 
			TABLE_NAME='".DB_PREFIX."commission' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc56->num_rows==0){	
			$a_45_sql = "ALTER TABLE `".DB_PREFIX."commission`  ADD per int(11) NOT NULL";
		$this->db->query($a_45_sql);}
			$abc55 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='duration_id' AND 
			TABLE_NAME='".DB_PREFIX."commission' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc55->num_rows==0){	
			$a_46_sql = "ALTER TABLE `".DB_PREFIX."commission`  ADD  duration_id varchar(255) NOT NULL";
		$this->db->query($a_46_sql);}
			$abc54 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='expiry_date' AND 
			TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc54->num_rows==0){	
			$a_47_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  expiry_date datetime NOT NULL";
		$this->db->query($a_47_sql);}
			$abc53 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_status' AND 
			TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc53->num_rows==0){	
			$a_48_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  payment_status int(11) NOT NULL";
		$this->db->query($a_48_sql);}
			$abc52 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_date' AND 
			TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc52->num_rows==0){	
			$a_49_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  payment_date datetime NOT NULL";
		$this->db->query($a_49_sql);}
			$abc51 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='payment_amount' AND 
			TABLE_NAME='".DB_PREFIX."sellers' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc51->num_rows==0){	
			$a_50_sql = "ALTER TABLE `".DB_PREFIX."sellers`  ADD  payment_amount float NOT NULL";
		$this->db->query($a_50_sql);}
			$abc50 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='approve' AND 
			TABLE_NAME='".DB_PREFIX."product' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc50->num_rows==0){	
			$asp_50_sql = "ALTER TABLE `".DB_PREFIX."product`  ADD approve int(11) NOT NULL";
		$this->db->query($asp_50_sql);}
		$abc501 = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE COLUMN_NAME='seller_id' AND 
			TABLE_NAME='".DB_PREFIX."cart' AND TABLE_SCHEMA='".DB_DATABASE."'");
			if($abc501->num_rows==0){	
			$asp_501_sql = "ALTER TABLE `".DB_PREFIX."cart`  ADD seller_id int(11) NOT NULL";
		$this->db->query($asp_501_sql);}
			$a_51_sql = "UPDATE " .DB_PREFIX. "category SET approve=1";
		$this->db->query($a_51_sql);
			$a_52_sql = "UPDATE " .DB_PREFIX. "product SET approve=1";
		$this->db->query($a_52_sql);
			$a_53_sql = "UPDATE " .DB_PREFIX. "option SET approve=1";
		$this->db->query($a_53_sql);
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user` limit 1");
		if($query->num_rows){
		$userquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE LOWER(username) = '" . $this->db->escape(strtolower($query->row['firstname'].'_'.$query->row['lastname'])) . "'");
		if($userquery->num_rows){
		  echo "User name already exist";
		}else{
		   $emailquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE LOWER(email) = '" . $this->db->escape(strtolower($query->row['email'])) . "'");
		   if($emailquery->num_rows){
		      echo "Email already exist";
		   }else{
			$config_sellercommission_id = $this->config->get('config_sellercommission_id');
			if(!$config_sellercommission_id){
			$config_sellercommission_id = 1;
			}
			$folderName = strtolower($query->row['firstname']).'_'.strtolower($query->row['lastname']);
			$path = HTTPS_SERVER;
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
			 echo "Installation success";
		   }
		   }
		}
		$defaultseller = $this->config->get('config_defaultseller_id');
		if($defaultseller){
		  $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product");
		    foreach($query->rows as $row){
				$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers_products WHERE 				
				product_id = '" . (int)$row['product_id'] . "'");
				if($product_query->num_rows){
				}else{
				$this->db->query("UPDATE " . DB_PREFIX . "product 
				SET seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "' 
				WHERE product_id = '" . (int)$row['product_id'] . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "seller SET 
				vproduct_id = '" . (int)$row['product_id'] . "', 
				seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "product_option SET seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "' WHERE product_id = '" . (int)$row['product_id'] . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "product_option_value SET seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "' WHERE product_id = '" . (int)$row['product_id'] . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "product_attribute SET seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "' WHERE product_id = '" . (int)$row['product_id'] . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "product_discount SET seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "' WHERE product_id = '" . (int)$row['product_id'] . "'");
				$this->db->query("UPDATE " . DB_PREFIX . "product_special SET seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "' WHERE product_id = '" . (int)$row['product_id'] . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "sellers_products SET
				product_id = '" . (int)$row['product_id'] . "', 
				seller_id = '" . (int)$this->config->get('config_defaultseller_id') . "',
				quantity = '" . (int)$row['quantity'] . "',
				price = '" . (float)$row['price'] . "',
				date_added = NOW()");
				}
			 }
		}
	}
}
