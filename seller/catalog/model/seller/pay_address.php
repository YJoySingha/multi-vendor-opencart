<?php
class ModelSellerPayAddress extends Model {	
	public function editAddress($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sellers SET paypalorcheque = '".(int)$data['paypalorcheque']."',
		payee_name = '" . $this->db->escape($data['cheque']). "', 
		paypal_email = '" . $this->db->escape($data['paypal_email']) . "', 
		bank_name = '" . $this->db->escape($data['bank_name']) . "', 
		account_number = '" . $this->db->escape($data['account_number']) . "',
		account_name = '" . $this->db->escape($data['account_name']) . "', 
		branch = '" . $this->db->escape($data['branch']) . "', 
		ifsccode = '" . $this->db->escape($data['ifsccode']) . "' WHERE seller_id = '" . (int)$this->seller->getId() . "'");
	}
	public function getAddress() {
		$address_query = $this->db->query("SELECT sa.*,s.payee_name,s.paypal_email,s.paypalorcheque,s.bank_name,s.account_number,s.account_name,s.branch,s.ifsccode FROM " . DB_PREFIX . "saddress as sa," . DB_PREFIX . "sellers as s WHERE sa.seller_id = '" . (int)$this->seller->getId() . "' AND sa.seller_id = s.seller_id AND sa.address_id = s.address_id limit 1");
		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id2'] . "'");
			if ($country_query->num_rows) {
				$country2 = $country_query->row['name'];
			} else {
				$country2 = '';
			}
			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id2'] . "'");
			if ($zone_query->num_rows) {
				$zone2 = $zone_query->row['name'];
				$zone_code2 = $zone_query->row['code'];
			} else {
				$zone2 = '';
				$zone_code2 = '';
			}		
			$address_data = array(
				'address_id'      => $address_query->row['address_id'],
				'paypal_email'      => $address_query->row['paypal_email'],
				'bank_name'      => $address_query->row['bank_name'],
				'account_number'      => $address_query->row['account_number'],
				'account_name'      => $address_query->row['account_name'],
				'branch'      => $address_query->row['branch'],
				'ifsccode'      => $address_query->row['ifsccode'],
				'paypalorcheque'       => $address_query->row['paypalorcheque'],
				'address_2'      => $address_query->row['address_2'],
				'postcode2'       => $address_query->row['postcode2'],
				'city2'           => $address_query->row['city2'],
				'zone_id2'        => $address_query->row['zone_id2'],
				'payee_name'        => $address_query->row['payee_name'],
				'zone2'           => $zone2,
				'zone_code2'      => $zone_code2,
				'country_id2'     => $address_query->row['country_id2'],
				'country2'        => $country2
			);
			return $address_data;
		} else {
			return false;	
		}
	}
}
?>