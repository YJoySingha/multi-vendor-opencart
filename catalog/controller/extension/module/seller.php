<?php
class ControllerExtensionModuleSeller extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/seller');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('seller/seller');

		$this->load->model('tool/image');

		$data['sellers'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		

		if (!empty($setting['seller'])) {
			$sellers = array_slice($setting['seller'], 0, (int)$setting['limit']);


			foreach ($sellers as $seller_id) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sellers WHERE seller_id = '" . (int)$seller_id . "'");
				if ($query->num_rows) {
					$seller_info = $query->row; 
					if ($seller_info['image']) {
						$image = $this->model_tool_image->resize($seller_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}//var_dump($seller_info);
					$data['sellers'][] = array(
						'seller_id' 	=> $seller_info['seller_id'],
						'name'        	=> $seller_info['firstname'].' '.$seller_info['lastname'],
						'thumb'       	=>$image,
						'href'        	=> $this->url->link('product/seller', 'seller_id=' . $seller_info['seller_id'])
					);
				}
			}
		}

	

		if ($data['sellers']) {
			return $this->load->view('extension/module/seller', $data);
		}
	}
}