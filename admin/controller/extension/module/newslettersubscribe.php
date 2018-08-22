<?php
class ControllerExtensionModuleNewslettersubscribe extends Controller {
	private $error = array(); 
	
	public function index() {   
	
		$this->load->language('extension/module/newslettersubscribe');

		$this->document->setTitle($this->language->get('page_title'));
		
		$this->load->model('setting/setting');
		$this->load->model('setting/module');
		$this->load->model('jacklb/newslettersubscribe');
		
		$this->model_jacklb_newslettersubscribe->check_db();
		$this->document->addScript('view/javascript/newsletter/jquery-ui.js');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('newslettersubscribe', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
				
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_customer_all'] = $this->language->get('text_customer_all');	
		$data['text_customer'] = $this->language->get('text_customer');	
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_affiliate_all'] = $this->language->get('text_affiliate_all');	
		$data['text_affiliate'] = $this->language->get('text_affiliate');	
		$data['text_product'] = $this->language->get('text_product');	
		$data['text_lbnewsletter'] = $this->language->get('text_lbnewsletter');	
		$data['text_sendall'] = $this->language->get('text_sendall');	
		$data['text_mail_success'] = $this->language->get('text_mail_success');	
		$data['text_info'] = $this->language->get('text_info');	
				
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_list'] = $this->language->get('tab_list');
		$data['tab_mail'] = $this->language->get('tab_mail');
		
		$data['entry_admin'] = $this->language->get('entry_admin');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_unsubscribe'] = $this->language->get('entry_unsubscribe');
		$data['entry_thickbox'] = $this->language->get('entry_thickbox');
		$data['entry_registered'] = $this->language->get('entry_registered');	
		$data['entry_mail'] = $this->language->get('entry_mail');
		$data['entry_options'] = $this->language->get('entry_options');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_to'] = $this->language->get('entry_to');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_subject'] = $this->language->get('entry_subject');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_popup'] = $this->language->get('entry_popup');
		
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add_module'] = $this->language->get('button_add_module');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_send'] = $this->language->get('button_send');
		
		//Errors
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

  		//Breadcrumb
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/newslettersubscribe', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/newslettersubscribe', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/newslettersubscribe', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/newslettersubscribe', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info['name'])) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info['status'])) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		
		
		if (isset($this->request->post['popup'])) {
			$data['popup'] = $this->request->post['popup'];
		} elseif (!empty($module_info['popup'])) {
			$data['popup'] = $module_info['popup'];
		} else {
			$data['popup'] = '';
		}
		
		if (isset($this->request->post['newslettersubscribe_unsubscribe'])) {
			$data['newslettersubscribe_unsubscribe'] = $this->request->post['newslettersubscribe_unsubscribe'];
		} elseif (!empty($module_info)) {
			$data['newslettersubscribe_unsubscribe'] = $module_info['newslettersubscribe_unsubscribe'];
		} else {
			$data['newslettersubscribe_unsubscribe'] = '';
		}
		
		if (isset($this->request->post['newslettersubscribe_registered'])) {
			$data['newslettersubscribe_registered'] = $this->request->post['newslettersubscribe_registered'];
		}  elseif (!empty($module_info)) {
			$data['newslettersubscribe_registered'] = $module_info['newslettersubscribe_registered'];
		} else {
			$data['newslettersubscribe_registered'] = '';
		}
		
		if (isset($this->request->post['newslettersubscribe_mail_status'])) {
			$data['newslettersubscribe_mail_status'] = $this->request->post['newslettersubscribe_mail_status'];
		} elseif (!empty($module_info)) {
			$data['newslettersubscribe_mail_status'] = $module_info['newslettersubscribe_mail_status'];
		} else {
			$data['newslettersubscribe_mail_status'] = '';
		}
		
		if (isset($this->request->post['newslettersubscribe_thickbox'])) {
			$data['newslettersubscribe_thickbox'] = $this->request->post['newslettersubscribe_thickbox'];
		}  elseif (!empty($module_info)) {
			$data['newslettersubscribe_thickbox'] = $module_info['newslettersubscribe_thickbox'];
		} else {
			$data['newslettersubscribe_thickbox'] = '';
		}

		//Get User List
		
		$user_total = $this->model_jacklb_newslettersubscribe->getTotalUsers();
		
		if (isset($this->request->get['page'])) {
			
			$page = $this->request->get['page'];
			
		} else {
			
			$page = 1;
			
		}
		
		$data['page_nav'] = ($page-1) * $this->config->get('config_limit_admin');
		
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['pages'] = array(
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);
		
		$results = $this->model_jacklb_newslettersubscribe->getList($data);
		
		$data['users'] = array();
		
		foreach ($results as $result) {
		 
		 	$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_delete'),
				'href' => $this->url->link('extension/module/newslettersubscribe/unsubscribe', 'user_token=' . $this->session->data['user_token'] . '&user_id=' . $result['id'] . $url, true)
			);
			
			$data['users'][] = array(
				'id' 			=> $result['id'],
				'name'       	=> $result['name'],
				'email_id'      => $result['email_id'],
				'selected'   	=> isset($this->request->post['selected']) && in_array($result['id'], $this->request->post['selected']),
				'action'     	=> $action,
				'start'         => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'         => $this->config->get('config_limit_admin')
			);
		
		}

		//Mail
		$this->load->model('setting/store');
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('customer/customer_group');
				
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups(0);

		//Graph
		
	
		$data['jack'] = array();
		
		//Pagination 
		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = 100;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extension/module/newslettersubscribe', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
			
		$data['pagination'] = $pagination->render();
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/newslettersubscribe', $data));
	
	}
	
	public function unsubscribe() {
		
		$this->load->model('jacklb/newslettersubscribe');
		
		$data = '';
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			
			foreach ($this->request->post['selected'] as $user_id) {
				$this->model_jacklb_newslettersubscribe->delete($user_id);                
	  		}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('extension/module/newslettersubscribe', 'user_token=' . $this->session->data['user_token'] . $url, true));
			
		} else if( isset($this->request->get['user_id']) ) {
			
			$data = $this->request->get['user_id'];
			
			$this->model_jacklb_newslettersubscribe->delete($data);
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}
			
			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->response->redirect($this->url->link('extension/module/newslettersubscribe', 'user_token=' . $this->session->data['user_token'] . $url, true));
			
		
		} else {
		
			$this->index();
			
		}
		
		
	}
	
	//Send Mail Function
	
	public function send() {
		$this->language->load('extension/module/newslettersubscribe');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'extension/module/newslettersubscribe')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}
					
			if (!$this->request->post['subject']) {
				$json['error']['subject'] = $this->language->get('error_subject');
			}
	
			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}
			
			if (!$json) {
				
				$this->load->model('setting/store');
			
				$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);			
				
				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}
	
				$this->load->model('customer/customer');
				
				$this->load->model('customer/customer_group');
				
				//$this->load->model('sale/affiliate');
	
				$this->load->model('sale/order');
				
				$this->load->model('jacklb/newslettersubscribe');
	
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}
								
				$email_total = 0;
							
				$emails = array();
				
				switch ($this->request->post['to']) {
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);
						
						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);
							
						$results = $this->model_customer_customer->getCustomers($customer_data);
					
						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					/* Start : Bhavin */	
					case 'sendall':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);
						
						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);
							
						$results = $this->model_customer_customer->getCustomers($customer_data);
						
						$email_total1 = $this->model_jacklb_newslettersubscribe->getTotalUsers();
							
						$results1 = $this->model_jacklb_newslettersubscribe->getList($customer_data);
						
						$email_total = $email_total + $email_total1;
						
						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						
						foreach ($results1 as $result1) {
							$emails1[] = $result1['email_id'];
						}
						
						$emails = array_merge($emails,$emails1);
						
						break;
					case 'lbnewsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);
						
						$email_total = $this->model_jacklb_newslettersubscribe->getTotalUsers();
							
						$results = $this->model_jacklb_newslettersubscribe->getList($customer_data);
						
						foreach ($results as $result) {
							$emails[] = $result['email_id'];
						}
						break;
					/* End : Bhavin */
					case 'customer_all':
						$customer_data = array(
							'start'  => ($page - 1) * 10,
							'limit'  => 10
						);
									
						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);
										
						$results = $this->model_customer_customer->getCustomers($customer_data);
				
						foreach ($results as $result) {
							$emails[] = $result['email'];
						}						
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id'],
							'start'                    => ($page - 1) * 10,
							'limit'                    => 10
						);
						
						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);
										
						$results = $this->model_customer_customer->getCustomers($customer_data);
				
						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}						
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {					
							foreach ($this->request->post['customer'] as $customer_id) {
								$customer_info = $this->model_customer_customer->getCustomer($customer_id);
								
								if ($customer_info) {
									$emails[] = $customer_info['email'];
								}
							}
						}
						break;
					case 'product':
						if (isset($this->request->post['product'])) {
							$email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);	
							
							$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);
													
							foreach ($results as $result) {
								$emails[] = $result['email'];
							}
						}
						break;												
				}
				
				if ($emails) {
					$start = ($page - 1) * 10;
					$end = $start + 10;
					
					if ($end < $email_total) {
						$json['success'] = sprintf($this->language->get('text_sent'), $start, $email_total);
					} else { 
						$json['success'] = $this->language->get('text_mail_success');
					}				
						
					if ($end < $email_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/module/newslettersubscribe/send', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true));
					} else {
						$json['next'] = '';
					}
										
					$message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";
					
					foreach ($emails as $email) {
						$mail = new Mail();	
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');				
						$mail->setTo($email);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender($store_name);
						$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));					
						$mail->setHtml($message);
						$mail->send();
					}
				} 
			}
		}
		
		$this->response->setOutput(json_encode($json));	
	}
	
	// Gives you dates between start and end
	public function GetDays($sStartDate, $sEndDate){  
      $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));  
      $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));  
      
      $aDays[] = $sStartDate;  
      
      $sCurrentDate = $sStartDate;  
      
      while($sCurrentDate < $sEndDate){  
        $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
      
        $aDays[] = $sCurrentDate;  
      }  
      return $aDays;  
    }  
		
	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'extension/module/newslettersubscribe')) {
      		$this->error['warning'] = $this->language->get('error_permission');  
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/newslettersubscribe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		return !$this->error;	
	}
}