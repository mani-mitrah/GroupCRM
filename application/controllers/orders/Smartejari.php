<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Smartejari extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->helper('crypt_helper');
		$this->load->model('order_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		if ($this->verify_min_level(1)) {

			if ($this->auth_user_role == 6) {
				$view_data['order_details'] = $this->order_model->smartejari_coord_orders();
			}

			if ($this->auth_user_role == 2) {
				//TODO
				$view_data['order_details'] = $this->order_model->smartejari_coord_orders();
			}

			$data = array(
				'page_title' => 'Orders',
				'title' => 'Orders',
				'content' => $this->load->view('pages/orders/smartejari/list', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}


	public function assign()
	{
		if ($this->verify_min_level(1)) {
			//if super administrator or coordinator
			if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
				$view_data['unassigned_orders'] = $this->order_model->aladheed_unassigned_orders();
				$view_data['baraha_csas'] = $this->user_model->website_csas(502);
				log_message('error', $this->db->last_query());
				$data = array(
					'page_title' => 'Un-assigned Orders',
					'title' => 'Un-assigned Orders',
					'content' => $this->load->view('pages/orders/smartejari/unassigned_list', $view_data, TRUE),
				);
				$this->load->view('template/full_template', $data);
			}
		} else {
			redirect('login');
		}
	}

	public function view()
	{
		if ($this->verify_min_level(1)) {
			$order_item_id = $_GET['code'];
			$this->load->model('order_model');
			$view_data['timeline'] = $this->order_model->smartejari_timeline($order_item_id);
			$view_data['order_details'] = $this->order_model->smartejari_order_item_get($order_item_id);
			$view_data['order_statuses'] = $this->mcommon->specific_fields_records_all('smartejari_order_status', array('id>' => 101, 'is_active' => 1));
			// echo "<pre>";
			// print_r($view_data['order_statuses']);
			// echo "</pre>";
			// exit();
			$view_data["page_title"] = "SmartEjari Orders";

			$data = array(
				'page_title' => 'SmartEjari Orders',
				'title' => 'SmartEjari Orders',
				'content' => $this->load->view('pages/orders/smartejari/view_order', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}


	public function action_email($se_order_id)
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_email'])) {
				$from_email = $this->input->post('from_email');
				$customer_email = $this->input->post('customer_email');
				$agent_email = $this->input->post('agent_email');
				$email_subject = $this->input->post('email_subject');
				$email_message = $this->input->post('email_message');
				$email_remarks = $this->input->post('email_remarks');
				$contactable_date = $this->input->post('contactable_date');

				//construct message
				$customer_name = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
				$agent_name = $this->mcommon->specific_row_value('users', array('email' => $agent_email), 'first_name');
				$message = "Dear " . $customer_name . ",<br /><br />";
				$message .= $email_message;
				$message .= "Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

				// print_r($_POST);
				if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '') {
					//SEND EMAIL TO CUSTOMER

					$email_array = array(
						'email' => $customer_email,
						'subject' => $email_subject,
						'template' => 'mails/lead_template',
						'from_name' => $this->auth_first_name,
						'from_email' => $this->auth_email,
						'message' => $message,
						'reply_to' => $this->auth_email,
						// 'cc' => $this->auth_email
					);
					$send_mail = send_lead_template_email($email_array);
					// exit();

					log_message('error', $send_mail);
					if ($send_mail) {
						//add entry in lead action log
						$log_insert_array = array('action_id' => 404, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Sent an email to ' . $customer_email . '. <br /><br /><strong>Remarks:</strong>' . $email_remarks . '<br /><br />Email Message as follow as<br /><br /><pre>' . $message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 404);
						$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
						if ($insert_log > 0) {
							//update lead status and contactable date in lead table
							$update_lead_array = array('order_status' => 404);
							$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
							$this->session->set_flashdata('alert', 'success');
							$this->session->set_flashdata('alert_message', 'Email sent successfully. You will receive the copy of email.');
						} else {
							$this->session->set_flashdata('alert', 'danger');
							$this->session->set_flashdata('alert_message', 'Email sent successfully. But unable to update the lead record. Contact support.');
						}
					} else {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
					}

					redirect('orders/smartejari/view?code=' . $se_order_id);
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
				}
			}
		} else {
			redirect('login');
		}
	}


	public function action_payment($se_order_id)
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_payment'])) {
				// // echo "<pre>";
				// print_r($_POST);
				// // echo "</pre>";
				// exit();
				$from_email = $this->input->post('from_email');
				$customer_email = $this->input->post('customer_email');
				$agent_email = $this->input->post('agent_email');
				$email_subject = $this->input->post('email_subject');
				$email_message = $this->input->post('email_message');


				$email_remarks = $this->input->post('email_remarks');
				$contactable_date = $this->input->post('contactable_date');

				$amount_payment = $this->input->post('amount_payment');
				$crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
				// echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
				// exit();
				//construct message
				$customer_name = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
				$agent_name = $this->mcommon->specific_row_value('users', array('email' => $agent_email), 'first_name');
				$message = "Dear " . $customer_name . ",<br /><br />";
				$message .= $email_message;

				$pre_token = $se_order_id . "-" . $customer_email . "-@OnTimeCRM11..";
				// echo $pre_token;
				// echo "<br><br>";
				// exit();
				$token1 = md5($pre_token);
				$token2 = md5(strrev($pre_token));
				$token = $token1 . "-" . $token2;

				$msg = $amount_payment;
				$key = $token2;
				// print_r($token);
				// exit();

				//add entry in lead action log

				$current_timestamp = date('Y-m-d H:i:s');

				$action_message = $message . "<p></p><div class='payment'><a href='#'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p></div><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

				$log_insert_array = array('action_id' => 412, 'action_amount' => $amount_payment, 'se_order_id' => $se_order_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 412);

				$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
				$action_id = $this->db->insert_id();
				$log_id = encrypt_decrypt($action_id);

				$payment_message = "<p></p><a href='https://crm.ontimegroup.com/payment/ccpay?ref=" . $token . "&token=" . $crypt_amount . "&identity=" . $log_id . "'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

				$message .= $payment_message;


				if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '' && $contactable_date != '') {
					//SEND EMAIL TO CUSTOMER
					$email_array = array(
						'email' => $customer_email,
						'subject' => $email_subject,
						'template' => 'mails/lead_template',
						'from_name' => $this->auth_first_name,
						'from_email' => $this->auth_email,
						'message' => $message,
						'reply_to' => $this->auth_email,
						'cc' => $this->auth_email
					);
					$send_mail = send_lead_template_email($email_array);
					log_message('error', $send_mail);

					if ($send_mail) {


						// $email_array = array(
						// 	'email' => $_SESSION["email"],
						// 	'subject' => 'PCR TEST - Payment Link - Baraha.ae',
						// 	'template' => 'mails/template',
						// 	'from_name' => 'BARAHA',
						// 	'message' => $msg,
						// );
						// $send_mail = send_template_email($email_array);

						if ($insert_log > 0) {
							//update lead status and contactable date in lead table
							$update_lead_array = array('order_status' => 412);
							$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
							$this->session->set_flashdata('alert', 'success');
							$this->session->set_flashdata('alert_message', 'Payment Detail Email sent successfully. You will receive the copy of email.');
						} else {
							$this->session->set_flashdata('alert', 'danger');
							$this->session->set_flashdata('alert_message', 'Email sent successfully. But unable to update the lead record. Contact support.');
						}
					} else {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
					}

					redirect('orders/smartejari/view?code=' . $se_order_id);
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
				}
			}
		} else {
			redirect('login');
		}
	}


	/**
	 * [action_call Lead followup through call]
	 * @param  [type] $se_order_id [lead's id]
	 * @return [type]          [Update the action log by the remarks of logged in user]
	 */
	public function action_call($se_order_id)
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_call'])) {

				// print_r($_POST);
				// exit();
				$call_remarks = $this->input->post('call_remarks');
				$contactable_date = $this->input->post('contactable_date');
				if ($call_remarks != '' && $contactable_date != '') {
					$log_insert_array = array('action_id' => 405, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $call_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 405);
					$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
					if ($insert_log > 0) {
						//update lead status and contactable date in lead table
						$update_lead_array = array('order_status' => 405);
						$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
						$this->session->set_flashdata('alert', 'success');
						$this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');
					} else {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
					}
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'All fields are required');
				}
				redirect('orders/smartejari/view?code=' . $se_order_id);
			}
		} else {
			redirect('login');
		}
	}

	/**
	 * [action_sms Lead followup through sms]
	 * @param  [type] $se_order_id [lead's id]
	 * @return [type]          [Send sms to customer]
	 */
	public function action_sms($se_order_id)
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_sms'])) {
				$mobile_number = $this->input->post('mobile_number');
				$message_body = $this->input->post('message_body');
				$sms_remarks = $this->input->post('sms_remarks');
				$contactable_date = $this->input->post('contactable_date');
				if ($mobile_number != '' && $contactable_date != '' && $message_body != '') {
					$mobile_number = str_replace("+", "", $mobile_number);
					$sms_gateway_data = sendsms($mobile_number, $message_body);
					$result_array = json_decode($sms_gateway_data);

					if (isset($result_array->jobId)) {
						$log_insert_array = array('action_id' => 406, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Remarks:<br />' . $sms_remarks . '<br />SMS Content:<br />' . $message_body, 'action_by' => $this->auth_user_id, 'status_id' => 406);
						$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
						if ($insert_log > 0) {
							//update lead status and contactable date in lead table
							$update_lead_array = array('order_status' => 406);
							$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
							$this->session->set_flashdata('alert', 'success');
							$this->session->set_flashdata('alert_message', 'SMS sent successfully. You can see the progress in timeline.');
						} else {
							$this->session->set_flashdata('alert', 'danger');
							$this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
						}
					} else {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Unable to send SMS at this moment. Please contact support.');
					}
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'All fields are required');
				}
				redirect('orders/smartejari/view?code=' . $se_order_id);
			}
		} else {
			redirect('login');
		}
	}

	/**
	 * [action_meeting setup meeting with the customer]
	 * @param  [type] $se_order_id [lead's id]
	 * @return [type]          [setup meeting in lead_meetings table]
	 */
	public function action_meeting($se_order_id)
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_meeting'])) {
				$meeting_remarks = $this->input->post('meeting_remarks');
				$contactable_date = $this->input->post('contactable_date');
				if ($meeting_remarks != '' && $contactable_date != '') {
					//create meeting

					$crm_user_id = $this->auth_user_id;
					$customer_id = $this->mcommon->specific_row_value('smartejari_orders', array('se_order_id' => $se_order_id), 'customer_id');
					$meeting_date_time = $contactable_date;
					$remarks = $meeting_remarks;
					$is_complete = 0;
					$created_at  = date('Y-m-d H:i:s');
					$last_updated = date('Y-m-d H:i:s');

					$meeting_insert_array = array(
						'se_order_id' => $se_order_id,
						'crm_user_id' => $crm_user_id,
						'customer_id' => $customer_id,
						'meeting_date_time' => $meeting_date_time,
						'remarks' => $remarks,
						'is_complete' => $is_complete,
						'created_at' => $created_at,
						'last_updated' => $last_updated
					);
					$meeting_insert = $this->mcommon->common_insert('lead_meetings', $meeting_insert_array);
					if ($meeting_insert > 0) {
						//TODO: schedule an email before 15 mins of the meeting.

						$log_insert_array = array('action_id' => 407, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $meeting_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 407);
						$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
						if ($insert_log > 0) {
							//update lead status and contactable date in lead table
							$update_lead_array = array('order_status' => 407);
							$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
							$this->session->set_flashdata('alert', 'success');
							$this->session->set_flashdata('alert_message', 'Meeting scheduled successfully. You can see the progress in timeline.');
						} else {
							$this->session->set_flashdata('alert', 'danger');
							$this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
						}
					} else {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Unable to setup meeting at this moment. Please contact support.');
					}
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'All fields are required');
				}
				redirect('orders/smartejari/view?code=' . $se_order_id);
			}
		} else {
			redirect('login');
		}
	}

	/**
	 * [action_meeting Logged in user can update about the lead for future reference]
	 * @param  [type] $se_order_id [lead's id]
	 * @return [type]          [updated in timeline of the lead]
	 */
	public function action_custom($se_order_id)
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_custom'])) {

				$custom_remarks = $this->input->post('custom_remarks');
				$contactable_date = $this->input->post('contactable_date');
				if ($custom_remarks != '' && $contactable_date != '') {
					$log_insert_array = array('action_id' => 408, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $custom_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 408);
					// print_r($log_insert_array);
					$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
					// print_r($insert_log);
					if ($insert_log > 0) {
						//update lead status and contactable date in lead table
						$update_lead_array = array('order_status' => 408);
						$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
						print_r($update_lead_array);
						$this->session->set_flashdata('alert', 'success');
						$this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');
					} else {
						// print_r($this->db->error());
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
					}
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'All fields are required');
				}
				redirect('orders/smartejari/view?code=' . $se_order_id);
			}
		} else {
			redirect('login');
		}
	}

	/**
	 * [action_meeting Logged in user can update about the lead for future reference]
	 * @param  [type] $se_order_id [lead's id]
	 * @return [type]          [updated in timeline of the lead]
	 */
	public function action_meeting_minutes()
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_meeting_minutes'])) {
				$meeting_update_remarks = $this->input->post('meeting_update_remarks');
				$meeting_contactable_date = $this->input->post('meeting_contactable_date');
				$se_order_id = $this->input->post('se_order_id');
				$meeting_id = $this->input->post('meeting_id');


				if ($meeting_update_remarks != '' && $meeting_contactable_date != '' && $se_order_id != '' && $meeting_id != '') {
					$meeting_update = $this->mcommon->common_edit('lead_meetings', array('is_complete' => 1, 'last_updated' => date('Y-m-d H:i:s')), array('se_order_id' => $se_order_id, 'id' => $meeting_id));
					if ($meeting_update) {

						$log_insert_meeting_array = array('action_id' => 409, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $meeting_update_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 409);
						$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_meeting_array);
						if ($insert_log > 0) {
							//update lead status and contactable date in lead table
							$update_lead_array = array('order_status' => 409, 'contactable_date' => $meeting_contactable_date);
							$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
							$this->session->set_flashdata('alert', 'success');
							$this->session->set_flashdata('alert_message', 'Minutes of meeting updated successfully. You can see the progress in timeline.');
						} else {
							$this->session->set_flashdata('alert', 'danger');
							$this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
						}
					} else {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Unable to update meeting minutes. Please contact support team.');
					}
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'All fields are required');
				}
				redirect('orders/smartejari/view?code=' . $se_order_id);
			}
		} else {
			redirect('login');
		}
	}


	/**
	 * [action_meeting Logged in user can update about the lead for future reference]
	 * @param  [type] $se_order_id [lead's id]
	 * @return [type]          [updated in timeline of the lead]
	 */
	public function action_order($se_order_id)
	{
		// print_r($_POST);
		if ($this->verify_min_level(1)) {
			if (isset($_POST['order_id'])) {
				$order_id = $this->input->post('order_id');
				$file_url = "";
				if ($order_id != '') {
					if (isset($_FILES['file']['name'])) {
						$attachment_name = $this->input->post('attachment_name');
						log_message('error', 'inside files uploads');
						// Count total files
						// print_r($_FILES);
						$countfiles = 1;
						log_message('error', 'SmartEjari Order File' . $countfiles);

						// Looping all files

						// Define new $_FILES array - $_FILES['file']
						$_FILES['file']['name'] = $_FILES['file']['name'];
						$_FILES['file']['type'] = $_FILES['file']['type'];
						$_FILES['file']['tmp_name'] = $_FILES['file']['tmp_name'];
						$_FILES['file']['error'] = $_FILES['file']['error'];
						$_FILES['file']['size'] = $_FILES['file']['size'];

						// Set preference
						$config['upload_path'] = 'uploads/leads';
						$config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
						$config['max_size'] = '5000'; // max_size in kb
						$config['file_name'] = $_FILES['file']['name'];

						//Load upload library
						$this->load->library('upload', $config);

						// File upload
						if ($this->upload->do_upload('file')) {

							// Get data about the file
							$uploadData = $this->upload->data();
							$filename = $uploadData['file_name'];
							log_message('error', 'no of files' . $filename);
							// Initialize array
							$data['filenames'][] = $filename;

							$file_url = base_url() . 'uploads/leads/' . $filename;

							$insert_attachment_array = array('se_order_id' => $se_order_id, 'attachment_name' => $attachment_name, 'attachment_url' => $file_url);
							$attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
						}
					}
					// print_r($_POST);
					$order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $se_order_id;
					if ($file_url != "") {
						$order_desc = $order_desc . "<br> Reference File: <a target='_blank' href='" . $file_url . "' class='p-2 pl-4 pr-4 btn btn-primary'>File</a>";
					}
					// echo $order_desc;
					// exit();
					$log_insert_array = array('action_id' => 410, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $this->auth_user_id, 'status_id' => 410);
					$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
					if ($insert_log > 0) {
						$update_lead_array = array('order_status' => 410, 'crm_remarks' => $order_id);
						$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
						$this->session->set_flashdata('alert_complete', 'success');
						$this->session->set_flashdata('alert_complete_message', 'Order data updated successfully. You can see the progress in timeline.');
					} else {
						$this->session->set_flashdata('alert_complete', 'danger');
						$this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
					}
				} else {
					$this->session->set_flashdata('alert_complete', 'danger');
					$this->session->set_flashdata('alert_complete_message', 'All fields are required');
				}
				//If its a Sub Lead
				$parent_id = $this->mcommon->specific_row_value("leads", ["id" => $se_order_id], "lead_parent_id");
				if ($parent_id == 0) {
					// redirect('orders/smartejari/view?code=' . $se_order_id);
				} else {
					// redirect('orders/smartejari/view?code=' . $parent_id);
				}
				// redirect('orders/smartejari/view?code=' . $se_order_id);
			}
		} else {
			redirect('login');
		}
	}

	/**
	 * [action_meeting Logged in user can update about the lead for future reference]
	 * @param  [type] $se_order_id [lead's id]
	 * @return [type]          [updated in timeline of the lead]
	 */
	public function action_close($se_order_id)
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['action_close'])) {

				$close_remarks = $this->input->post('close_remarks');

				if ($close_remarks != '') {
					$log_insert_array = array('action_id' => 411, 'se_order_id' => $se_order_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 411);
					$insert_log = $this->mcommon->common_insert('smartejari_order_action_log', $log_insert_array);
					if ($insert_log > 0) {
						$update_lead_array = array('order_status' => 411, 'remarks' => $close_remarks);
						$update_lead = $this->mcommon->common_edit('smartejari_orders', $update_lead_array, array('se_order_id' => $se_order_id));
						$this->session->set_flashdata('alert_complete', 'success');
						$this->session->set_flashdata('alert_complete_message', 'Lead has been closed successfully. You can see the progress in timeline.');
					} else {
						$this->session->set_flashdata('alert_complete', 'danger');
						$this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
					}
				} else {
					$this->session->set_flashdata('alert_complete', 'danger');
					$this->session->set_flashdata('alert_complete_message', 'All fields are required');
				}
				$parent_id = $this->mcommon->specific_row_value("leads", ["id" => $se_order_id], "lead_parent_id");
				if ($parent_id == 0) {
					redirect('orders/smartejari/view?code=' . $se_order_id);
				} else {
					redirect('orders/smartejari/view?code=' . $parent_id);
				}
			}
		} else {
			redirect('login');
		}
	}
}
