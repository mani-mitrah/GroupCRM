<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');

class Lead extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('leads_model');
		$this->load->model('authentication_model');
		$this->load->model('leads_model');
		$this->load->library('form_validation');
	}

	// public function zohoupdate($lead_info, $id, $order_id = '')
	// {
	//     if (isset($lead_info["lead_id"])) {
	//         $lead_id = $lead_info["lead_id"];
	//         $lead_reason_for_lost_mql = "";
	//         $lead_zoho_reason = "";
	//         if (isset($lead_info["lead_reason_for_lost_mql"])) {
	//             $lead_reason_for_lost_mql = $lead_info["lead_reason_for_lost_mql"];
	//         }
	//         if (isset($lead_info["lead_zoho_reason"])) {
	//             $lead_zoho_reason = $lead_info["lead_zoho_reason"];
	//         }

	//         try {

	//             if ($order_id != "99999999999999") {
	//                 if (!isset($lead_info["status"])) {
	//                     $this->response(["status" => false, "message" => "Status is required"]);
	//                     exit();
	//                 }
	//             }
	//             $lead = $this->db->select("*")->from("leads")->where("id", $lead_info["lead_id"])->get()->first_row();
	//             $zoho_id = $lead->lead_zoho_id;
	//             //echo $zoho_id;
	//             //print_r($lead);
	//             //exit();
	//             $status = $lead_info["status"];
	//             $action_name = $lead_info["action_name"];
	//             $curl = curl_init();

	//             curl_setopt_array($curl, array(
	//                 CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
	//                 CURLOPT_RETURNTRANSFER => true,
	//                 CURLOPT_ENCODING => '',
	//                 CURLOPT_MAXREDIRS => 10,
	//                 CURLOPT_TIMEOUT => 0,
	//                 CURLOPT_FOLLOWLOCATION => true,
	//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	//                 CURLOPT_CUSTOMREQUEST => 'POST',
	//                 CURLOPT_POSTFIELDS => array('client_id' => '1000.SNAU7L0CEEQYLH6F3XELSEFMZ5IZZQ', 'client_secret' => 'f69319cf08f0939f5d0b1e8da486d587a52c1d4e9c', 'refresh_token' => '1000.1b1a23457ab5384f47211da537d49497.00ecb1aed2d72f8a1ac45b760b9a8fd8', 'grant_type' => 'refresh_token')
	//             ));

	//             $response = curl_exec($curl);
	//             log_message('log', 'ZOHO Token: ' . $response);
	//             curl_close($curl);
	//             //echo "<br>";
	//             //echo $response;
	//             // echo "<br>";
	//             $res_json = json_decode($response);

	//             $token = $res_json->access_token;
	//             // echo $token;die;
	//             $data = array();
	//             // print_r($res_json);
	//             // echo "<br><br>";
	//             // print($req);
	//             // exit();
	//             // echo "<br>";
	//             // echo "<pre>";
	//             // echo json_encode($data);
	//             // echo "</pre>";
	//             // exit();
	//             $req = [];
	//             if (isset($lead_info["Lead_Source"])) $req["Lead_Source"] = $lead_info["Lead_Source"];
	//             if (isset($lead_info["Lead_Channel"])) $req["Lead_Channel"] = $lead_info["Lead_Channel"];
	//             if (isset($lead_info["Business_Activity"])) $req["Business_Activity"] = $lead_info["Business_Activity"];
	//             if (isset($lead_info["assigned_to"])) {
	//                 $as_to = $lead_info["assigned_to"];
	//                 $ass_to = $this->db->select("*")->from("users")->where("user_id", $as_to)->get()->first_row();
	//                 if ($ass_to) {
	//                     $username = $ass_to->first_name . " " . $ass_to->last_name;
	//                     $req["Consultant_Name_OnTimeBiz"] = $username;
	//                 }
	//             }
	//             if ($lead_reason_for_lost_mql != "" || $lead_zoho_reason != "") {
	//                 $req["Lead_Status"] = $status;

	//                 $req["lead_reason_for_lost_mql"] = $lead_reason_for_lost_mql;
	//                 $req["lead_zoho_reason"] = $lead_zoho_reason;
	//                 $req["OnTimeBiz_CRM_Status"] = $action_name;
	//                 // $req["Description"] = $lead_remarks;
	//                 // $req["Lead_Source"] = "POS";
	//                 $data["data"] = [$req];
	//                 // $data["data"] = [["Lead_Status" => $status]];
	//             } else {
	//                 $req["Lead_Status"] = $status;
	//                 $req["OnTimeBiz_CRM_Status"] = $action_name;
	//                 // $data["data"] = [["Lead_Status" => $status]];
	//                 $data["data"] = [$req];
	//             }

	//             //  echo $zoho_id;
	//             // print_r(json_encode($data));
	//             //exit();
	//             $curl = curl_init();
	//             $zoho_url =  'https://www.zohoapis.com/crm/v2/Leads/' . $zoho_id;
	//             // echo $zoho_url;
	//             curl_setopt_array($curl, array(
	//                 CURLOPT_URL => $zoho_url,
	//                 CURLOPT_RETURNTRANSFER => true,
	//                 CURLOPT_ENCODING => '',
	//                 CURLOPT_MAXREDIRS => 10,
	//                 CURLOPT_TIMEOUT => 0,
	//                 CURLOPT_FOLLOWLOCATION => true,
	//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	//                 CURLOPT_CUSTOMREQUEST => 'PUT',
	//                 CURLOPT_POSTFIELDS => json_encode($data),
	//                 CURLOPT_HTTPHEADER => array(
	//                     'Authorization: Bearer ' . $token,
	//                     'Content-Type: application/json'
	//                 ),
	//             ));

	//             $response = curl_exec($curl);
	//             curl_error($curl);
	//             $response = str_replace('\\', '', $response);
	//             $reqs = json_encode($data);
	//             $update_lead_array = array('zoho_request' => $reqs, 'zoho_response' => $response);
	//             $update_lead = $this->mcommon->common_edit('lead_action_log', $update_lead_array, array('id' => $id));

	//             // echo $lead_id;print_r($update_lead_array);die;
	//             // print_r($response);
	//             // die;
	//             curl_close($curl);
	//             log_message('log', 'ZOHO CRM Res: ' . $response);
	//             //$update = $this->mcommon->common_edit('leads', array(), array('id' => $lead_id));
	//             //$lead_zoho_status="Deal Closed";
	//             //$update_lead_array = array( 'order_receipt' => $order_id,'lead_zoho_status' => $lead_zoho_status,'zoho_response' => $response);
	//             //        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));

	//             try {
	//                 $zoho_res = json_decode($response);
	//                 $zoho_id = $zoho_res->data[0]->details->id;
	//                 $update = $this->mcommon->common_edit('leads', array('zoho_response' => $response), array('id' => $lead_id));
	//             } catch (Exception $e) {
	//                 print_r($e);
	//                 $update = $this->mcommon->common_edit('leads', array('zoho_response' => $response), array('id' => $lead_id));
	//             }
	//         } catch (Exception $e) {
	//             $zoho_res = $e->getMessage();
	//             print_r($e);
	//             $update = $this->mcommon->common_edit('leads', array('zoho_response' => $e->getMessage()), array('id' => $lead_id));
	//         }
	//     } else {
	//         //$this->response(["status" => false, "message" => "lead_id param is mandatory"]);
	//     }
	// }
	public function zoholead_post()
	{

		$service_id = 1009;


		$branch_id = 128;
		$lead_name = $this->post('first_name') . " " . $this->post('last_name');
		$lead_contact = $this->post('lead_contact');
		$lead_email = $this->post('lead_email');
		$lead_remarks = $this->post("lead_remarks");
		$lead_by_pos_user = 178140614; //Zoho CRM

		$lead_owner =  $this->mcommon->specific_row_value('users', array('email' => $this->post("lead_created_by")), 'user_id');

		$lead_by_post_user_name = "Zoho CRM";
		$country = $this->post('lead_country');

		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$random_string = substr(str_shuffle($str_result), 0, 10);

		//check category exist
		$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
		if ($branch_exist == 0) {
			$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
		}

		$category_id = 109;

		// $this->form_validation->set_rules('service_name', 'service_name', 'required');

		$this->form_validation->set_rules('lead_country', 'lead_country', 'required');
		// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');

		if ($this->form_validation->run() == true) {
			$random_email_name = strtolower($random_string);
			$random_email = $random_email_name . '@ontimecustomer.com';
			$lead_email = ($lead_email == '') ? $random_email : $lead_email;
			$lead_email = trim($lead_email);
			//create or get customer
			//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

			// $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
			$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

			if ($check_email_exists != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');

				//return $user_id;
			}

			// if ($check_mobile_exists != 0) {
			//     $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact), 'user_id');
			//     //return $user_id;
			// }

			if ($check_email_exists == 0) {
				$password = 'Welcome@123';
				$confirm_password = 'Welcome@123';
				$auth_level = '1';
				$referal_code = $random_string;
				$user_hashed_password = $this->authentication->hash_passwd($password);
				$user_data = [
					'auth_level' => $auth_level,
					'mobile' => $lead_contact,
					'referal_code' => $referal_code,
					'country' => $country,
					'first_name' => $lead_name,
					'passwd' => $user_hashed_password,
					'email' => trim($lead_email),
					'confirm_password' => $user_hashed_password,
				];
				$user_data['user_id'] = $this->authentication_model->get_unused_id();
				$user_data['created_at'] = date('Y-m-d H:i:s');
				$user_data['otp'] = rand(1000, 9000);
				$user_data['email_otp'] = rand(1000, 9000);
				$user_data['banned'] = '0';
				$user_data['role_id'] = '4';
				$user_data['country'] = 'United Arab Emirates';
				$user_data['country_code'] = '+971';

				$insert = $this->mcommon->common_insert("lead_users", $user_data);

				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
				//return $user_id;
			}

			if ($user_id != 0) {
				$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_owner, 'is_primary_group_id' => 1), 'group_id');

				$uploaded_file_name = '';
				//process lead type
				$insert_lead_array = array(
					'customer_id' => $user_id,
					'branch_id' => $branch_id,
					'category_id' => $category_id,
					'service_id' => (int) $service_id,
					'lead_created_by' => $lead_owner,
					'lead_added_on' => date('Y-m-d H:i:s'),
					'contactable_date' => date('Y-m-d H:i:s'),
					'lead_status' => 607,
					'package_id' => 0,
					'order_receipt' => 0,
					'remarks' => $lead_remarks,
					'is_assigned' => 1,
					'lead_source' => $this->post("lead_source"),
					'lead_zoho_id' => $this->post("lead_zoho_id"),
					'lead_zoho_status' => $this->post("lead_zoho_status"),
					'created_group_id' => $created_group_id,
				);
				$inserts = $this->db->insert("leads", $insert_lead_array);
				// echo $this->db->last_query();
				// echo "Inserts==> ";
				// print_r($inserts);
				$lead_id = $this->db->insert_id();


				if ($lead_id > 0) {

					//$assigned_to = 503941962;
					$assigned_to = 632746755;
					$assigned_by = $lead_by_pos_user;

					$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
					$insert_array = array(
						'lead_id' => $lead_id,
						'assigned_by' => $assigned_by,
						'assigned_to' => $assigned_to,
						'assigned_on' => date('Y-m-d H:i:s'),
					);
					// echo "<br>";
					// echo "<br> ";
					// print_r($insert_array);
					$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

					$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

					$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
					$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

					$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
					// print_r($log_insert_array);
					$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

					// echo "Log: ".$log_insert."<br>";
					// echo "ERROR: ";
					// print_r($this->db->error());
					// exit();
					if ($insert > 0) {
						$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 602), array('id' => $lead_id));

						if ($update) {
							//create action log
							$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
							$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							$id = $this->db->insert_id();
							$action_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $id), 'action_id');
							$action_name = $this->mcommon->specific_row_value('lead_actions', array('id' => $action_id), 'action_name');

							$lead_info["lead_id"] = $lead_id;
							$lead_info["assigned_to"] = $assigned_to;

							$lead_info["action_name"] = $action_name;
							//$this->zohoupdate($lead_info, $id);

							// $receiver_email = $csa->email;
							$receiver_email = $csa->email;
							$receiver_name = $csa->first_name;
							$sender_email = $coordinator->email;
							$sender_name = $coordinator->first_name;

							// $subject = "ONTIME BIZ ALERT";

							// $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimebiz.com/leads/lead/view/" . $lead_id . " .";
							// $email_array = array(
							//     'email' => $receiver_email,
							//     'subject' => $subject,
							//     'template' => 'mails/template',
							//     'from_name' => "ONTIME BIZ ALERT",
							//     'message' => $message,
							// );
							// $send_mail = send_template_email($email_array);
							// log_message('error', $send_mail);

							$this->response(["status" => true, "message" => 'Enquiry has been created and assigned successfully!', "lead_id" => $lead_id], 200);
						} else {
							$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
							// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

							$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present. Please try again later'], 500);
						}
					} else {
						$this->response(["status" => false, "message" => 'Unable to assign enquiry at present.', "exception" => $this->db->error()], 500);
					}

					$this->response(["status" => true, "message" => "Enquiry successfully created.", "lead_id" => $lead_id], 200);
				} else {
					$this->response(["status" => false, "message" => 'Unable to create enquirys at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}

				//     // $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimebiz.com/uploads/leads/' . $uploaded_file_name);

			} else {
				$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error(), "data" => $user_data], 500);
			}
		} else {
			$this->response(["status" => false, "message" => validation_errors()], 400);
		}
	}

	public function paid_leads_get()
	{
		try {
			$this->db->select('leads.id,concat("OTLDPMET",leads.id) as reference,lead_users.first_name,lead_users.last_name,lead_users.email,concat(lead_users.country_code,lead_users.mobile) as mobile,lead_action_log.action_amount as paid_amount,lead_action_log.created_at')->from("leads");
			$this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
			$this->db->join("lead_action_log", "lead_action_log.lead_id = leads.id");
			$this->db->where("lead_action_log.status_id = 308 and (leads.lead_status < 305 OR leads.lead_status = 307 OR leads.lead_status = 308)");
			$result = $this->db->get()->result_array();
			// echo $this->db->last_query();
			// exit();
			return $this->response(["status" => true, "data" => $result, "message" => false]);
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	public function sub_lead_pendingEmail($lead_id)
	{
		$lead_det = $this->leads_model->lead_details($lead_id);

		$gc_lead_subject = "SLA Violation for the " . $lead_det["customer_name"] . " 'Golden Cube' with sublead #" . $lead_id;
		$gc_lead_message = "<br>Dear Team<br>";
		$gc_lead_message .= "<br><br>We regret to inform you that there has been a violation of our Service Level Agreement (SLA) for the sublead #" . $lead_id;

		$gc_lead_message .= "<br><br><strong>Details of the Violation:</strong><br>";
		$gc_lead_message .= "<br>Sublead ID: " . $lead_det["id"];
		// $gc_lead_message .= "<br>Expected Completion Date: " . $lead_det["lead_added_on"];

		$gc_lead_message .= "<br><br>Thank you for your understanding and continued trust in Goldencube.ae";

		$cc_usermail = [];
		array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
		// array_push($cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453
		// array_push($cc_usermail, ["email" => "dravidan.v@mitrahsoft.in", "name" => "Dravidan"]);
		array_push($cc_usermail, ["email" => "team@goldencube.ae", "name" => "GoldenCube"]);

		$to_usermail = [];
		array_push($to_usermail, ["email" => "Aseel.m@goldencube.ae", "name" => "Aseel"]);	// 617196601
		array_push($to_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);	// 617196601
		array_push($to_usermail, ["email" => "anil.d@ontimegroup.com", "name" => "Anil"]);	// 2233119808
		array_push($to_usermail, ["email" => "fatima@ontimebiz.com", "name" => "Fatima Salih"]);	// 2845782377
		// array_push($to_usermail, ["email" => "fatma@ontimebiz.com", "name" => "Fatma Abdollah"]);	// 3654913804
		// array_push($to_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		// $bcc_usermail = [];
		// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		$email_array = array(
			'email' => $to_usermail,
			'cc' => $cc_usermail,
			// "bcc" => $bcc_usermail,
			'subject' => $gc_lead_subject,
			'template' => 'mails/gc_template',
			'from_name' => "Golden Cube",
			'message' => $gc_lead_message,
			'branch_id' => $lead_det["branch_id"],
		);
		$send_mail = send_template_email($email_array);
		log_message('error', $send_mail);
	}

	public function goldencube_pending_sub_leads_post()
	{
		try {
			$this->db->select('DISTINCT (l.id) as id')->from("leads as l");
			$this->db->join("lead_package_services as lp", "l.package_id = lp.package_id");
			$this->db->join("(SELECT * FROM `leads` where branch_id = 106 and lead_parent_id = 0 and (country_options != 'outsideCountry' or country_options is NULL)) as pl", "pl.id = l.lead_parent_id");
			$this->db->where("l.lead_parent_id != 0 and l.branch_id = 106 and l.lead_status not in (305, 306, 630) and 
			DATE(l.created_at) <= DATE_SUB(DATE(NOW()), INTERVAL IF(lp.sla = 0, 2, IFNULL(lp.sla, 2)) DAY) 
			and pl.lead_created_by = 3946368694
			and (l.sla_violation_status != 'red' or l.sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$this->db->group_by('l.id');
			$this->db->order_by('l.id', 'DESC');

			// DATE_SUB(DATE(NOW()), INTERVAL IFNULL(lp.sla, 2) DAY)
			// DATE_SUB(DATE(NOW()), INTERVAL IF(lp.sla = 0, 2, IFNULL(lp.sla, 2)) DAY)

			$result = $this->db->get()->result_array();

			$leads_array = [];
			foreach ($result as $data) {
				array_push($leads_array, $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
				$sub_lead_pending_email = $this->sub_lead_pendingEmail($data['id']);
			}

			return $this->response(["status" => true, "data" => $leads_array, "message" => "Email Send Successfully"]);
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	public function goldencube_pending_leads_post()
	{
		try {
			$leads_array = [];
			$leads_array['10 years golden Visa'] = [];
			$leads_array['5 years golden Visa'] = [];
			$leads_array['Dependent Visa'] = [];
			$leads_array['Property Valuation'] = [];
			$leads_array['Update Personal Information'] = [];
			$leads_array['Medical Insurance'] = [];
			$leads_array['Visit Visa'] = [];
			$leads_array['Visa Cancellation'] = [];

			// 10 years visa	- 7-10 days
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 7 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = '10 years golden Visa' and lead_created_by = 3946368694 
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['10 years golden Visa'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// 5 years visa		- 7-10 days
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 7 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = '5 years golden Visa' and lead_created_by = 3946368694
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['5 years golden Visa'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// Dependent Visa	- 5 days
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 5 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = 'Dependent Visa' and lead_created_by = 3946368694
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['Dependent Visa'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// Property Evaluation	- 2 days
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = 'Property Valuation' and lead_created_by = 3946368694
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['Property Valuation'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// Update information	- 1 days
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 1 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = 'Update Personal Information' and lead_created_by = 3946368694
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['Update Personal Information'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// Medical insurance	-- 1 day
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 1 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = 'Medical Insurance' and lead_created_by = 3946368694
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['Medical Insurance'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// Visit visa	– 1 day
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 1 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = 'Visit Visa' and lead_created_by = 3946368694
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['Visit Visa'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// Visa cancellation	– 1 day
			$this->db->select('*')->from("leads");
			$this->db->where("lead_parent_id = 0 and branch_id = 106 and DATE( created_at ) <= DATE_SUB(DATE(NOW()), INTERVAL 1 DAY) and 
				lead_status not in (305, 306, 630) and lead_package_name = 'Visa Cancellation' and lead_created_by = 3946368694
				and (sla_violation_status != 'red' or sla_violation_status is null)");	// goldencubeweb@goldencube.ae
			$result = $this->db->get()->result_array();

			foreach ($result as $data) {
				$main_lead_pending_email = $this->main_lead_pendingEmail($data['id']);
				array_push($leads_array['Visa Cancellation'], $data['id']);
				$update_sla_flag_array = ['sla_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
			}

			// echo $this->db->last_query();
			// exit();

			return $this->response(["status" => true, "data" => $leads_array, "message" => "Email Send Successfully"]);
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	public function main_lead_pendingEmail($lead_id)
	{
		$lead_det = $this->leads_model->lead_details($lead_id);

		/*$gc_lead_subject = "Lead Not Completed for ". $lead_det["customer_name"] ." 'Golden Cube' with #" . $lead_id ;
		$gc_lead_message = "Lead #" . $lead_id . " is not completed!!<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:";

		$gc_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
		$gc_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
		$gc_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];	*/

		$gc_lead_subject = "SLA Violation for the " . $lead_det["customer_name"] . " 'Golden Cube' with Lead #" . $lead_id;
		$gc_lead_message = "<br>Dear Team<br>";
		$gc_lead_message .= "We regret to inform you that there has been a violation of our Service Level Agreement (SLA) for the Lead #" . $lead_id;

		$gc_lead_message .= "<br><br><strong>Details of the Violation:</strong><br>";
		$gc_lead_message .= "<br>Lead ID: " . $lead_det["id"];
		// $gc_lead_message .= "<br>Expected Completion Date: " . $lead_det["lead_added_on"];

		$gc_lead_message .= "<br><br>Thank you for your understanding and continued trust in Goldencube.ae";

		$cc_usermail = [];
		array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
		// array_push($cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453
		// array_push($cc_usermail, ["email" => "dravidan.v@mitrahsoft.in", "name" => "Dravidan"]);
		array_push($cc_usermail, ["email" => "team@goldencube.ae", "name" => "GoldenCube"]);

		$to_usermail = [];
		array_push($to_usermail, ["email" => "Aseel.m@goldencube.ae", "name" => "Aseel"]);
		array_push($to_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);	// 617196601
		array_push($to_usermail, ["email" => "anil.d@ontimegroup.com", "name" => "Anil"]);	// 2233119808
		array_push($to_usermail, ["email" => "fatima@ontimebiz.com", "name" => "Fatima Salih"]);	// 2845782377
		// array_push($to_usermail, ["email" => "fatma@ontimebiz.com", "name" => "Fatma Abdollah"]);	// 3654913804
		// array_push($to_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		$email_array = array(
			'email' => $to_usermail,
			'cc' => $cc_usermail,
			'subject' => $gc_lead_subject,
			'template' => 'mails/gc_template',
			'from_name' => "Golden Cube",
			'message' => $gc_lead_message,
			'branch_id' => 106,
		);
		$send_mail = send_template_email($email_array);
		log_message('error', $send_mail);
	}

	/*	public function main_lead_completeEmail($lead_id)
	{
		$lead_det = $this->leads_model->lead_details($lead_id);

		$this->db->select("*")->from("leads");
		$this->db->where("lead_status = 305");
		$sub_lead_details =  $this->db->where("lead_parent_id", $lead_id)->get()->result_array();

		// for Customer
		$lead_total_amount = 0;
		$i = 1;
		$sub_lead_message = "<br><table border='1' style='text-align: center; border-collapse: collapse'>
			<tr><td>S.No<td>Service<td>Qty</td></tr>";
		// <td>Govt Fee(Incl CardAmt)<td>Typing Fee(Incl VAT)<td>Total
		foreach ($sub_lead_details as $key => $data) {
			$sub_lead_message .= "
			<tr>
				<td>" . $i . "</td>
				<td>" . $data["remarks"] . "</td>
				<td> 1 </td>
				
			</tr>";
			$lead_total_amount = (float)$lead_total_amount + (float)((float)$data["govt_fee"] + (float)$data["typing_fee"]);
			$i = (int)$i + 1;
		}
		$sub_lead_message .= "</table>";

	
			// <td>" . $data["govt_fee"] . "</td>
			// <td>" . $data["typing_fee"] . "</td>
			// <td>" . (float)((float)$data["govt_fee"] + (float)$data["typing_fee"]) . "</td>
		

		$gc_cus_lead_subject = "Your Order Package is Complete! ";
		$gc_cus_lead_message = "<br>Dear " . $lead_det["customer_name"] . "<br>";
		$gc_cus_lead_message .= "<br><br>We’re happy to let you know that your order <strong>" . $lead_det['pos_pmt_number'] . "</strong> has been successfully processed and is now complete. Here’s a summary of your order:<br>";
		$gc_cus_lead_message .= "<br><strong>Order Summary:</strong><br>";
		$gc_cus_lead_message .= "<br>Order Number: <strong>" . $lead_det['pos_pmt_number'] . "</strong><br>";

		$gc_cus_lead_message .= "<br>Service Completion:";
		$gc_cus_lead_message .= "<br><table border='1' style='text-align: center; border-collapse: collapse'>
			<tr><td>Description</td></tr>
			<tr><td>" . $lead_det["category_code"] . " - " . $lead_det["service_name"] . "</td>
				
			</tr></table>";

		// <td>Unit Price<td>Total
		// <td>" . $lead_total_amount . "</td><td>" . $lead_total_amount . "</td>

		$gc_cus_lead_message .= "<br><br><strong>Service Payment Deatils:</strong>";
		$gc_cus_lead_message .= $sub_lead_message;

		$gc_cus_lead_message .= "<br><br>We hope everything is to your satisfaction. If you have any questions, concerns, or need further assistance, please don't hesitate to contact us at team@goldencube.ae<br>";
		$gc_cus_lead_message .= "<br><br>Thank you for choosing <strong>Goldencube</strong>! We look forward to serving you again.<br>";

		// $cc_usermail = [];
		// array_push($cc_usermail, ["email" => "parthasarathy.d@mitrahsoft.in", "name" => "Sarathy"]);

		// $to_usermail = [];
		// array_push($to_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		$bcc_usermail = [];
		// array_push($bcc_usermail, ["email" => "anil.d@ontimegroup.com", "name" => "Anil"]);	// 2233119808
		array_push($bcc_usermail, ["email" => "fatima@ontimebiz.com", "name" => "Fatima Salih"]);	// 2845782377
		// array_push($bcc_usermail, ["email" => "fatma@ontimebiz.com", "name" => "Fatma Abdollah"]);	// 3654913804
		array_push($bcc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
		// array_push($bcc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453
		// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		$email_array = array(
			'email' => $lead_det["customer_email"],
			// 'cc' => $cc_usermail,
			'cc' => [["name" => "GoldenCube", "email" => "team@goldencube.ae"]],
			"bcc" => $bcc_usermail,
			'subject' => $gc_cus_lead_subject,
			'template' => 'mails/gc_template',
			'from_name' => "Golden Cube",
			'message' => $gc_cus_lead_message,
		);
		$send_mail = send_template_email($email_array);
		log_message('error', $send_mail);
	}	*/

	public function main_lead_completeEmail($lead_id){
		$lead_det = $this->leads_model->lead_details($lead_id);
		$bcc_usermail = [];
		// array_push($bcc_usermail, ["email" => "anil.d@ontimegroup.com", "name" => "Anil"]);	// 2233119808
		array_push($bcc_usermail, ["email" => "fatima@ontimebiz.com", "name" => "Fatima Salih"]);	// 2845782377
		array_push($bcc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
		// array_push($bcc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453
		// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		$gc_cus_lead_message = array(
			'customer_name' => $lead_det["customer_name"],
		);

		if($lead_det["is_corporate"] == 'Corporate'){
			$gc_cus_lead_message['customer_name'] = $lead_det["applicant_name"];
		}

		$subleads = $this->db->select("*")->from("leads")->where("lead_parent_id", $lead_id)->where('order_receipt is not null')->get()->result_array();

		$attachments = [];
		foreach ($subleads as $sublead) {
			$order_receipt = $sublead["order_receipt"];
			if(!empty($order_receipt)){
				$inv_url = "https://ontimesmartpos.net/Invoice/GetPosInvReport?invnum=" . $order_receipt;
				$attachment = [
					'name'=> $order_receipt,
					'file_name'=> $order_receipt.'.pdf',
                    'filePath'=> $inv_url
				];
				array_push($attachments,$attachment);
			}
		}

		$cc = [["email"=>'Team@goldencube.ae']];
		if($lead_det["is_corporate"] == 'Corporate'){
			$gc_message['customer_name'] = $leadDetails["applicant_name"];
			$cc = [["email"=>'Corporate@goldencube.ae']];
		}

		$email_array = array(
			'email' => $lead_det["customer_email"],
			'cc' => $cc,
			"bcc" => $bcc_usermail,
			'subject' => 'Congratulations! Your Golden Visa Process Is Complete',
			'template' => 'mails/gc_application_done',
			'from_name' => "Golden Cube",
			'message' => $gc_cus_lead_message,
			'branch_id' => '106',
			'attachments' => $attachments,
		);
		$send_mail = send_template_email($email_array);
	}

	public function completeEmail($lead_id)
	{
		$lead_det = $this->leads_model->lead_details($lead_id);
		// echo "Lead==> ".$lead_id;
		// print_r($lead_det);
		// exit();
		$subject = "Lead #" . $lead_id . " is completed!!";
		$message = "Lead #" . $lead_id . " is completed!!<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:";

		$message .= "<br>Customer Name: " . $lead_det["customer_name"];
		$message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
		$message .= "<br>Customer Email: " . $lead_det["customer_email"];
		$message .= "<br>Service Completion:";
		$message .= "<br><table border='1' style='text-align: center;'><tr><td>Service Name<td>Invoice Reference No.</td></tr><tr><td>" . $lead_det["category_code"] . " - " . $lead_det["service_name"] . "</td><td>" . $lead_det["order_receipt"] . "</td></tr></table>";

		$group_mems = [];
		// array_push($group_mems, $this->auth_user_id);
		// if ($lead_det["assigned_to"] != NULL) array_push($group_mems, $lead_det["assigned_to"]);
		// array_push($group_mems, $lead_det["lead_created_by"]);

		// if ($lead_det["assigned_to"] != NULL) $lead_user = $lead_det["assigned_to"];
		// else $lead_user = $lead_det["lead_created_by"];
		// $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $lead_user)->get()->result_array();

		// print_r($lead_det["lead_created_by"]);
		$group_ids = [];
		// foreach ($groups as $group) {
		// 	array_push($group_ids, $group["group_id"]);
		// }
		array_push($group_ids, 74); // 74 is the group id for Golden Cube

		$this->db->select("distinct(group_members.user_id)")->from("group_members");
		$this->db->join("users", "users.user_id=group_members.user_id");
		$this->db->where("users.is_active", 1);
		// $this->db->where("users.role_id", 7);
		$this->db->where_in('users.role_id', [7, 86]);
		$members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
		// print_r($this->db->last_query());
		// print_r($members);
		// // print_r($group_ids);
		// // print_r($members);
		// exit();
		foreach ($members as $mem) {
			array_push($group_mems, $mem["user_id"]);
		}

		$users = $this->db->select("*")->from("users")->where("(users.user_id in (" . implode(',', $group_mems) . "))")->get()->result_array();

		$cc_usermail = [];
		foreach ($users as $user) {
			if ($user["email"] == "fatma@ontimebiz.com" || $user['email'] == "anil.d@ontimegroup.com" 
				|| strpos($user["email"], 'zoho') === 0) {
				continue;
			}
			array_push($cc_usermail, ["email" => $user["email"], "name" => $user["first_name"]]);
		}

		$mail_branch_id = '';
		if($lead_det["branch_id"] == 106 || $lead_det['assigned_group_id'] == 74){
			$mail_branch_id = 106;
		}

		$email_array = array(
			'email' => $cc_usermail,
			'subject' => $subject,
			'template' => $mail_branch_id == '106' ? 'mails/gc_template' : 'mails/template',
			'from_name' => "CRM ALERT",
			'message' => $message,
			'branch_id' => $mail_branch_id,
		);
		// print_r("");
		// echo "<pre>";
		// print_r($email_array);
		// echo "</pre>";
		// exit();
		$send_mail = send_template_email($email_array);

		if ($lead_det["lead_parent_id"] != 0 && $lead_det["branch_id"] == 106) {

			$service_name = "";
			if ($lead_det['msd_key'] == 64) {
				$service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'remarks');
				$sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'id');
			} else if ($lead_det['msd_key'] == 69) {
				$service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'remarks');
				$sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'id');
			} else if ($lead_det['msd_key'] == 66) {
				$service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '67'), 'remarks');
				$sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '67'), 'id');
			}
			// else if($lead_det['msd_key'] == 67){
			// 	$service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '69'), 'remarks');
			// 	$sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '69'), 'id');
			// }

			// echo "service_name==> ".$service_name;
			// echo "sub_lead_id==> ".$sub_lead_id;
			// exit();

			if ($service_name != "" && $service_name != null) {

				if ($lead_det['msd_key'] == 64) {
					$sub_lead_subject = "Update the Country Status for the Lead #" . $lead_det["lead_parent_id"];

					$sub_lead_message .= "<br>Dear Mounira<br>";
					$sub_lead_message .= "<br><br>Kindly update status for whether the customer is Inside Country or Outside Country";
					$sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_det["lead_parent_id"] . "<br>";
				} else {
					$sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

					if ($lead_det['msd_key'] == 69) {
						$sub_lead_message .= "<br>Dear User<br>";
					} else if ($lead_det['msd_key'] == 66) {
						$sub_lead_message .= "<br>Dear ZAHUR<br>";
					}
					// else if($lead_det['msd_key'] == 67){
					// 	$sub_lead_message .= "<br>Dear Ishti<br>" ;
					// } 
					else {
						$sub_lead_message .= "<br>Dear User<br>";
					}

					$sub_lead_message .= "<br><br>Kindly proceed with completing the <strong>" . $service_name . "</strong> for the lead listed below <br>";
					$sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
					$sub_lead_message .= "<br><br>Lead Description:<br>";
					$sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
					$sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
					$sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
					$sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
					$sub_lead_message .= "<br>Receipt Number: <strong>" . $lead_det["pos_pmt_number"] . "</strong>";
					$sub_lead_message .= "<br>Remarks: " . $service_name;
				}

				$sublead_cc_usermail = [];
				// if($lead_det['msd_key'] == 64){	// After DLD Fees - GC DLD Completion for Visa processing
				// 	array_push($cc_usermail, ["email" => "Fawziya.h@ontimegov.com", "name" => "Fawziya"]);	// 980422236
				// array_push($cc_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali "]);	// 2411946200
				// }

				if ($lead_det['msd_key'] == 64) {
					array_push($sublead_cc_usermail, ["email" => "Jan.j@goldencube.ae", "name" => "Jan"]);	// 1631471526
					$email_remarks = "Update the Country Status in the DLD Fees service against the lead #" . $lead_det["lead_parent_id"] . " - shared the email to Jan";
				}
				if ($lead_det['msd_key'] == 69) {
					array_push($sublead_cc_usermail, ["email" => "Fawziya.h@ontimegov.com", "name" => "Fawziya"]);	// 980422236
					// array_push($sublead_cc_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali "]);	// 2411946200
					$email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Fawziya and Vernie";
				}
				if ($lead_det['msd_key'] == 66) {
					array_push($sublead_cc_usermail, ["email" => "zahur.m@goldencube.ae", "name" => "ZAHUR"]);	// 3151010821
					$email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to ZAHUR";
				}
				// if($lead_det['msd_key'] == 67){	
				// 	array_push($sublead_cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);	// 2879029976
				// 	$email_remarks = "Complete the transaction for the ". $service_name ." service against the lead #". $sub_lead_id ." - shared the email to Ishti";
				// }
				// array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

				$email_array = array(
					'email' =>  $sublead_cc_usermail,
					'cc' => [["name" => "GoldenCube", "email" => "team@goldencube.ae"]],
					'subject' => $sub_lead_subject,
					'template' => 'mails/template',
					'from_name' => "Golden Cube",
					'message' => $sub_lead_message,
					'branch_id' => $lead_det["branch_id"],
				);

				$action_by = 178140614;		// info@ontimegroup.com
				$log_insert_array = array('action_id' => 430, 'lead_id' => $lead_det["lead_parent_id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
				$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

				$send_mail = send_template_email($email_array);
			}
		}

		if($lead_det["branch_id"] == 106 && (in_array($lead_det['msd_key'], [68, 67, 66, 1, 11, 12, 32])) && !in_array($lead_det['package_id'], [43,46,47,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,165,166,167,168])) {	// 64,

			$gc_message = array(
				'customer_name' => $lead_det["customer_name"],
			);

			$gc_template = '';
			$gc_subject = '';

			// if ($lead_det['msd_key'] == 64){
			// 	$gc_template = 'mails/gc_dld_done';
			// 	$gc_subject = 'DLD Approval Complete-Initiating Immigration Procedures';
			// } else
			if ($lead_det['msd_key'] == 68 || $lead_det['msd_key'] == 11 || $lead_det['msd_key'] == 32) {
				$gc_template = 'mails/gc_medical_done';
				$gc_subject = 'Golden Cube: Medical Test Completed';
			} else if ($lead_det['msd_key'] == 67 || $lead_det['msd_key'] == 12) {
				$gc_template = 'mails/gc_eid_application';
				$gc_subject = 'Golden Cube: Emirates ID Application Ready';
			} else if ($lead_det['msd_key'] == 66 || $lead_det['msd_key'] == 1) {
				$gc_template = 'mails/gc_dld_done';
				$gc_subject = 'DLD Approval Complete-Initiating Immigration Procedures';
			}

			// else if ($lead_det['msd_key'] == 66 || $lead_det['msd_key'] == 1) {
			// 	$gc_template = 'mails/gc_application_done';
			// 	$gc_subject = 'Congratulations! Your Golden Visa Process Is Complete';
			// }

			$is_corporate = $this->mcommon->specific_row_value('leads', array('id' => $lead_det["lead_parent_id"]), 'is_corporate');
			if($is_corporate == 'Corporate'){
				$applicant_name = $this->mcommon->specific_row_value('leads', array('id' => $lead_det["lead_parent_id"]), 'applicant_name');
				$gc_message['customer_name'] = $applicant_name;
			}

			$email_array = array(
				'email' =>  $lead_det["customer_email"],
				'subject' => $gc_subject,
				'template' => $gc_template,
				'from_name' => "Golden Cube",
				'message' => $gc_message,
				'branch_id' => $lead_det["branch_id"],
			);
			$send_mail = send_template_email($email_array);
		}

		/*	if ($lead_det["category_id"] == 10020 || $lead_det["branch_id"] == 106) {



			$subject = "Your Order is " . $lead_id;
			// $message = "Dear " . $lead_det['customer_name'] . ".,<br><br>GoldenCube Order Service #" . $lead_id . " is completed!!<br>";
			$message = "Your Order is " . $lead_id;

			// $message .= "<br>Customer Name: " . $lead_det["customer_name"];
			// $message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
			// $message .= "<br>Customer Email: " . $lead_det["customer_email"];
			$message .= "<br>Service Completion:";
			$message .= "<br><table border='1' style='text-align: center;'><tr><td>Service Name<td>Invoice Reference No.</td></tr><tr><td>" . $lead_det["category_code"] . " - " . $lead_det["service_name"] . "</td><td>" . $lead_det["order_receipt"] . "</td></tr></table>";

			$email_array = array(
				'email' => $lead_det["customer_email"],
				'subject' => $subject,
				'template' => 'mails/gc_template',
				'from_name' => $lead_det["branch_name"],
				'message' => $message,
			);

			// print_r($lead_det);
			// print_r($email_array);
			// exit();

			$send_mail = send_template_email($email_array);
			log_message('error', $send_mail);

			$sms["message"] = "Your Sub order number OTLD-" . $lead_det["id"] . " is completed.";
			$sms["mobile_no"] = substr($lead_det["customer_mobile"], -9);;
			$sms["sender_id"] = "GoldenCube";
			// print_r($sms);
			// exit();
			$send_sms = send_trans_sms($sms);
			log_message('error', $send_sms);

			// for customer only
			if ($lead_det['lead_parent_id'] != 0) {
				$this->db->select("*")->from("leads");
				// $this->db->where("lead_status = 305");
				$sub_lead_details =  $this->db->where("lead_parent_id", $lead_det['lead_parent_id'])->get()->result_array();

				// for Customer
				$lead_total_amount = 0;
				$sub_lead_message = "<br><table border='1' style='text-align: center; border-collapse: collapse'>
					<tr><td>S.No<td>Service<td>Qty<td>Status</td></tr>";

				$next_service_name = '';
				if ($lead_det['msd_key'] == 64 || $lead_det['msd_key'] == 69) {
					$next_service_name = 'Visa Process';
				} else if ($lead_det['msd_key'] == 66) {
					$next_service_name = 'Emirates ID Process';
				}
				if ($lead_det['msd_key'] == 64) {
					$i = 1;
					foreach ($sub_lead_details as $key => $data) {
						if ($data['msd_key'] != 69 && $data['msd_key'] != 11) {
							$service_name = $data["remarks"];
							if ($service_name == 'DLD fees') {
								$service_name = 'DLD Process';
							} else if ($service_name == 'EID') {
								$service_name = 'EID Process';
							} else if ($service_name == 'Medical MOH') {
								$service_name = 'Medical MOH Process';
							} else if ($service_name == 'Visa Processing') {
								$service_name = 'Visa Process';
							} else if ($service_name == 'Medical DHA') {
								$service_name = 'Medical DHA Process';
							}

							$sub_lead_message .= "
							<tr>
								<td>" . $i . "</td>
								<td>" . $service_name . "</td>
								<td> 1 </td>";
							if ($data['msd_key'] == 64) {
								$sub_lead_message .= "<td> Completed </td> ";
							} else {
								$sub_lead_message .= "<td> Pending to start </td> ";
							}
							$sub_lead_message .= " </tr>";
							$i++;
						}
					}
					$next_service_name = 'Visa Process';
				}

				if ($lead_det['msd_key'] == 69) {
					$i = 1;
					foreach ($sub_lead_details as $key => $data) {
						if ($data['msd_key'] != 69 && $data['msd_key'] != 11) {
							$service_name = $data["remarks"];
							if ($service_name == 'DLD fees') {
								$service_name = 'DLD Process';
							} else if ($service_name == 'EID') {
								$service_name = 'EID Process';
							} else if ($service_name == 'Medical MOH') {
								$service_name = 'Medical MOH Process';
							} else if ($service_name == 'Visa Processing') {
								$service_name = 'Visa Process';
							} else if ($service_name == 'Medical DHA') {
								$service_name = 'Medical DHA Process';
							}

							$sub_lead_message .= "
							<tr>
								<td>" . $i . "</td>
								<td>" . $service_name . "</td>
								<td> 1 </td>";
							if ($data['msd_key'] == 64) {
								$sub_lead_message .= "<td> Completed </td> ";
							} else if ($data['msd_key'] == 66) {
								$sub_lead_message .= "<td> Started </td> ";
							} else {
								$sub_lead_message .= "<td> Pending to start </td> ";
							}
							$sub_lead_message .= " </tr>";
							$i++;
						}
					}
					$next_service_name = 'Visa Process';
				}

				if ($lead_det['msd_key'] == 66) {
					$i = 1;
					foreach ($sub_lead_details as $key => $data) {
						if ($data['msd_key'] != 69 && $data['msd_key'] != 11) {
							$service_name = $data["remarks"];
							if ($service_name == 'DLD fees') {
								$service_name = 'DLD Process';
							} else if ($service_name == 'EID') {
								$service_name = 'EID Process';
							} else if ($service_name == 'Medical MOH') {
								$service_name = 'Medical MOH Process';
							} else if ($service_name == 'Visa Processing') {
								$service_name = 'Visa Process';
							} else if ($service_name == 'Medical DHA') {
								$service_name = 'Medical DHA Process';
							}

							$sub_lead_message .= "
							<tr>
								<td>" . $i . "</td>
								<td>" . $service_name . "</td>
								<td> 1 </td>";
							if ($data['msd_key'] == 64 || $data['msd_key'] == 66) {
								$sub_lead_message .= "<td> Completed </td> ";
							} else if ($data['msd_key'] == 67) {
								$sub_lead_message .= "<td> Started </td> ";
							} else {
								$sub_lead_message .= "<td> Pending to start </td> ";
							}
							$sub_lead_message .= " </tr>";
							$i++;
						}
					}
					$next_service_name = 'Emirates ID Process';
				}

				if ($lead_det['msd_key'] == 67) {
					$i = 1;
					foreach ($sub_lead_details as $key => $data) {
						if ($data['msd_key'] != 69 && $data['msd_key'] != 11) {
							$service_name = $data["remarks"];
							if ($service_name == 'DLD fees') {
								$service_name = 'DLD Process';
							} else if ($service_name == 'EID') {
								$service_name = 'EID Process';
							} else if ($service_name == 'Medical MOH') {
								$service_name = 'Medical MOH Process';
							} else if ($service_name == 'Visa Processing') {
								$service_name = 'Visa Process';
							} else if ($service_name == 'Medical DHA') {
								$service_name = 'Medical DHA Process';
							}

							$sub_lead_message .= "
							<tr>
								<td>" . $i . "</td>
								<td>" . $service_name . "</td>
								<td> 1 </td>";
							if ($data['msd_key'] == 64 || $data['msd_key'] == 66 || $data['msd_key'] == 67) {
								$sub_lead_message .= "<td> Completed </td> ";
							} else {
								$next_service_name = $service_name;
								$sub_lead_message .= "<td> Started </td> ";
							}
							$sub_lead_message .= " </tr>";
							$i++;
						}
					}
				}

				if ($lead_det['msd_key'] == 32) {
					$i = 1;
					foreach ($sub_lead_details as $key => $data) {
						if ($data['msd_key'] != 69 && $data['msd_key'] != 11) {
							$service_name = $data["remarks"];
							if ($service_name == 'DLD fees') {
								$service_name = 'DLD Process';
							} else if ($service_name == 'EID') {
								$service_name = 'EID Process';
							} else if ($service_name == 'Medical MOH') {
								$service_name = 'Medical MOH Process';
							} else if ($service_name == 'Visa Processing') {
								$service_name = 'Visa Process';
							} else if ($service_name == 'Medical DHA') {
								$service_name = 'Medical DHA Process';
							}

							$sub_lead_message .= "
							<tr>
								<td>" . $i . "</td>
								<td>" . $service_name . "</td>
								<td> 1 </td>";
							$sub_lead_message .= "<td> Completed </td> ";
							$sub_lead_message .= " </tr>";
							$i++;
						}
					}
				}



				$sub_lead_message .= "</table>";

				$gc_cus_lead_subject = "From Your Order Number " . $lead_det["order_receipt"] . " with the Sublead id " . $lead_det["id"] . " and " . $lead_det["category_name"] . " Category is Completed";

				$gc_cus_lead_message .= "<br>Dear " . $lead_det["customer_name"] . "<br>";
				// if($lead_det['msd_key'] == 32 || $lead_det['msd_key'] == 11){
				$gc_cus_lead_message .= "<br><br>We’re happy to let you know that your " . $lead_det['service_name'] . " has been processed successfully. Here’s a summary of your order:<br>";
				// } else {
				// 	$gc_cus_lead_message .= "<br><br>We’re happy to let you know that your ". $lead_det['service_name']. " has been processed successfully, and we will proceed with the next steps as part of ". $next_service_name .". Here’s a summary of your order:<br>";
				// }

				$gc_cus_lead_message .= "<br><strong>Order Summary:</strong><br>";
				$gc_cus_lead_message .= "<br>Order Number:" . $lead_det['pos_pmt_number'] . "<br>";
				$gc_cus_lead_message .= "<br><strong>Service Completion:</strong>";
				$gc_cus_lead_message .= "<br><table border='1' style='text-align: center; border-collapse: collapse'>
					<tr><td>Description</tr>
						<tr><td>" . $lead_det["category_code"] . " - " . $lead_det["service_name"] . "</td>
					</tr></table>";

				$gc_cus_lead_message .= "<br><br><strong>Service Payment Deatils:</strong>";
				$gc_cus_lead_message .= $sub_lead_message;

				$gc_cus_lead_message .= "<br><br>We hope everything is to your satisfaction. If you have any questions, concerns, or need further assistance, please don't hesitate to contact us at team@goldencube.ae<br>";
				$gc_cus_lead_message .= "<br><br>Thank you for choosing <strong>Goldencube</strong>! We look forward to serving you again.<br>";


				// old
				// $gc_cus_lead_message .= "<br>Dear ". $lead_det["customer_name"] ."<br>" ;
				// $gc_cus_lead_message .= "<br><br>We’re happy to let you know that your order <strong>". $lead_det["order_receipt"] ."</strong> with Sub Lead id ". $lead_det["id"] ." and <strong>". $lead_det["category_name"] ."</strong> Category  has been successfully processed and is now complete. Here’s a summary of your order:<br>";
				// $gc_cus_lead_message .= "<br><strong>Order Summary:</strong><br>";
				// $gc_cus_lead_message .= "<br>Order Number: <strong>".$lead_det['order_receipt']."</strong><br>";

				// $gc_cus_lead_message .= "<br>Service Completion:";
				// $gc_cus_lead_message .= "<br><table border='1' style='text-align: center; border-collapse: collapse'>
				// 	<tr><td>S.No<td>Service<td>Qty<td>Govt Fee(Incl CardAmt)<td>Typing Fee(Incl VAT)<td>Total</td></tr>
				// 	<tr><td> 1 </td>
				// 		<td>" . $lead_det["remarks"] . "</td>
				// 		<td> 1 </td>
				// 		<td>" . $lead_det['govt_fee'] . "</td>
				// 		<td>" . $lead_det['typing_fee'] . "</td>
				// 		<td>" . (float)((float)$lead_det['govt_fee'] + (float)$lead_det['typing_fee']). "</td>
				// 	</tr></table>";

				// $gc_cus_lead_message .= "<br><br>Service Payment Deatils:";
		
				// $gc_cus_lead_message .= "<br><br>We hope everything is to your satisfaction. If you have any questions, concerns, or need further assistance, please don't hesitate to contact us at team@goldencube.ae<br>";
				// $gc_cus_lead_message .= "<br><br>Thank you for choosing <strong>Goldencube</strong>! We look forward to serving you again.<br>";

				// $bcc_usermail = [];
				// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

				$email_array = array(
					'email' => $lead_det["customer_email"],
					// "bcc" => $bcc_usermail,
					'cc' => [["name" => "GoldenCube", "email" => "team@goldencube.ae"]],
					'subject' => $gc_cus_lead_subject,
					'template' => 'mails/gc_template',
					'from_name' => $lead_det["branch_name"],
					'message' => $gc_cus_lead_message,
				);

				$send_mail = send_template_email($email_array);
				log_message('error', $send_mail);
			}
		}	*/


		return true;
	}

	public function upload_attachment_post()
	{
		try {
			$post_data = $_POST;
			if (isset($post_data["lead_id"])) {
				$lead = explode("-", $post_data["lead_id"]);
				$lead_endpoint = end($lead);
				$lead_id = str_replace("OTLDPMET", "", $lead_endpoint);

				if (isset($_FILES['attachment']['name'])) {
					$filename = $_FILES['attachment']['name'];
					$ext = substr($filename, strrpos($filename, '.') + 1);
					$fname = date("dmYHis");
					$fname .= uniqid() . "." . $ext;
					$ext = strtolower($ext);

					$config = array(
						'upload_path' => FCPATH . "../uploads/leads",
						'allowed_types' => "gif|jpg|png|jpeg|pdf",
						'file_name' => $fname
					);

					$this->load->library('upload', $config);
					if ($this->upload->do_upload('attachment')) {
						$data = $this->upload->data();
						$filename = $data['file_name'];
						$path = $config['upload_path'] . '/' . $filename;

						$insert_image_array = array('lead_id' => $lead_id, 'attachment_name' => "Sublead Attachment from POS", 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $filename);
						$insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_image_array);
						if ($insert_attachment > 0) {
							return $this->response(["status" => true, "message" => "Attachment uploaded successfully"], 200);
						} else {
							return $this->response(["status" => true, "message" => "Unable to upload"], 500);
						}
					}
				} else {
					return $this->response(["status" => false, "message" => "attachment missing. "], 422);
				}
			} else {
				return $this->response(["status" => false, "message" => "lead_id parameter missing. "], 422);
			}
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	public function complete_post()
	{
		try {

			$post_data = $_POST;
			if (isset($post_data["lead_id"]) && isset($post_data["lead_receipt"])) {
				$lead = explode("-", $post_data["lead_id"]);
				$lead_endpoint = end($lead);
				$lead_id = str_replace("OTLDPMET", "", $lead_endpoint);
				$lead = $this->db->select("*")->from("leads")->where("id", $lead_id)->get()->first_row();

				$test_log_insert_array = array('lead_id' => $lead_id, 'response' => $post_data, 'api_from' => 'Complete API');
				$test_insert_log = $this->mcommon->common_insert('test_payment_log', $test_log_insert_array);

				if ($lead) {
					if ($lead->lead_status == 305 || $lead->lead_status == 306) {
						return $this->response(["status" => true, "message" => "Lead already converted."], 200);
					} else {
						$lead_id = $lead_id;
						$order_id = $post_data['lead_receipt'];
						if ($order_id != '') {
							/* if (isset($_FILES['attachment']['name'])) {
								$filename=$_FILES['attachment']['name'];
								$ext=substr($filename,strrpos($filename,'.')+1);
								$fname=date("dmYHis");
								$fname.=uniqid().".".$ext;
								$ext=strtolower($ext);

								$config = array(
									'upload_path' => FCPATH."../uploads/leads",
									'allowed_types' => "gif|jpg|png|jpeg|pdf",
									'file_name' => $fname
								);
							
								$this->load->library('upload', $config);
								if ($this->upload->do_upload('attachment')) {
									$data = $this->upload->data();
									$filename = $data['file_name'];
									$path = $config['upload_path'] . '/' . $filename;
					
									$insert_image_array = array('lead_id' => $lead_id, 'attachment_name' => "Sublead Attachment from POS", 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $filename);
									$insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_image_array);
								} 
							} */
							$order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $order_id;
							$log_insert_array = array('action_id' => 410, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => 178140614, 'status_id' => 305);
							$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							// echo "Hello#";
							// print_r($this->db->error());
							if ($insert_log > 0) {
								// $update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id, "completed_by" => 178140614);
								// if ($_POST["pos_username"] != "") $update_lead_array["pos_username"] = $_POST["pos_username"];
								// if ($_POST["ref1"] != "") $update_lead_array["ref1"] = $_POST["ref1"];
								// if ($_POST["ref2"] != "") $update_lead_array["ref2"] = $_POST["ref2"];
								// if ($_POST["pos_GovtFees"] != "") $update_lead_array["pos_GovtFees"] = $_POST["pos_GovtFees"];
								// if ($_POST["pos_TypeFee"] != "") $update_lead_array["pos_TypeFee"] = $_POST["pos_TypeFee"];
								// if ($_POST["pos_Card_Amnt"] != "") $update_lead_array["pos_Card_Amnt"] = $_POST["pos_Card_Amnt"];
								// if ($_POST["pos_Disc_Amnt"] != "") $update_lead_array["pos_Disc_Amnt"] = $_POST["pos_Disc_Amnt"];
								// if ($_POST["pos_Tax_Amnt"] != "") $update_lead_array["pos_Tax_Amnt"] = $_POST["pos_Tax_Amnt"];
								// if ($_POST["pos_Tot_Amt"] != "") $update_lead_array["pos_Tot_Amt"] = $_POST["pos_Tot_Amt"];
								// if ($_POST["pos_Tot_Revn"] != "") $update_lead_array["pos_Tot_Revn"] = $_POST["pos_Tot_Revn"];
								//if ($_POST["pos_username"] != "") $update_lead_array["pos_CreatedBy"] = $_POST["pos_CreatedBy"];

								$update_lead_array = array(
									'lead_status' => 305,
									'order_receipt' => $order_id,
									'completed_by' => 178140614
								);

								if (isset($_POST["pos_username"]) && $_POST["pos_username"] != "") {
									$update_lead_array["pos_username"] = $_POST["pos_username"];
								}
								if (isset($_POST["ref1"]) && $_POST["ref1"] != "") {
									$update_lead_array["ref1"] = $_POST["ref1"];
								}
								if (isset($_POST["ref2"]) && $_POST["ref2"] != "") {
									$update_lead_array["ref2"] = $_POST["ref2"];
								}
								if (isset($_POST["pos_GovtFees"]) && $_POST["pos_GovtFees"] != "") {
									$update_lead_array["pos_GovtFees"] = $_POST["pos_GovtFees"];
								}
								if (isset($_POST["pos_TypeFee"]) && $_POST["pos_TypeFee"] != "") {
									$update_lead_array["pos_TypeFee"] = $_POST["pos_TypeFee"];
								}
								if (isset($_POST["pos_Card_Amnt"]) && $_POST["pos_Card_Amnt"] != "") {
									$update_lead_array["pos_Card_Amnt"] = $_POST["pos_Card_Amnt"];
								}
								if (isset($_POST["pos_Disc_Amnt"]) && $_POST["pos_Disc_Amnt"] != "") {
									$update_lead_array["pos_Disc_Amnt"] = $_POST["pos_Disc_Amnt"];
								}
								if (isset($_POST["pos_Tax_Amnt"]) && $_POST["pos_Tax_Amnt"] != "") {
									$update_lead_array["pos_Tax_Amnt"] = $_POST["pos_Tax_Amnt"];
								}
								if (isset($_POST["pos_Tot_Amt"]) && $_POST["pos_Tot_Amt"] != "") {
									$update_lead_array["pos_Tot_Amt"] = $_POST["pos_Tot_Amt"];
								}
								if (isset($_POST["pos_Tot_Revn"]) && $_POST["pos_Tot_Revn"] != "") {
									$update_lead_array["pos_Tot_Revn"] = $_POST["pos_Tot_Revn"];
								}
								if (!empty($_POST["pos_username"])) {
									$pos_CreatedBy = $_POST["pos_username"];
									preg_match('/\d+/', $pos_CreatedBy, $matches);
									$pouser = $matches[0];
									$usr = $this->db->select("*")->from("users")->where("employee_id", $pouser)->get()->result_array();
									if (!empty($usr)) {
										$pos_completed_user = $usr[0]['user_id'];
										//$update_lead_array["pos_completed_user"] = $pos_completed_user;
										$update_lead_array["completed_by"] = $pos_completed_user;
									}
								}
								//print_r($update_lead_array);
								if ($lead->lead_parent_id != 0) {

									$update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
									//$update_lead =$this->mcommon->update_lead($lead_id, $update_lead_array);
									$service_details = $this->db->select("ocs.service_id, ocs.gc_service_id")
											->from("ontime_services os")
											->join("ontime_category_services_ ocs", "os.id = ocs.gc_service_id")
											->where("ocs.service_id", $lead->service_id)
											->get()->result_array();

									if($service_details[0]['gc_service_id'] != 138 && $service_details[0]['gc_service_id'] !=137){
										$completion_email = $this->completeEmail($lead_id);
									}
									if($service_details[0]['gc_service_id'] == 93 && $lead->branch_id == 106){
										$leadDetails = $this->leads_model->lead_details($lead->lead_parent_id);
										$package_type = $this->mcommon->specific_row_value('lead_packages', array('package_id' => $lead->package_id), 'package_type');
										$this->db->select('*');
										$this->db->from('lead_action_log');
										$this->db->where('lead_id', $lead->lead_parent_id);
										$this->db->where('action_id', 429);
										$this->db->like('remarks', 'updated as outsideCountry');
										$query = $this->db->get();
										$row_count = $query->num_rows();

										$template = ($package_type == 'dependent' || $row_count >= 1)?'gc_visa_depend_outside':'gc_visa_submitted';
										$gc_message = array(
											'customer_name' => $leadDetails["customer_name"],
										);
										if($leadDetails["is_corporate"] == 'Corporate'){
											$gc_message['customer_name'] = $leadDetails["applicant_name"];
										}
										$cc = [["email"=>'Team@goldencube.ae']];
										if($leadDetails["is_corporate"] == 'Corporate'){
											$gc_message['customer_name'] = $leadDetails["applicant_name"];
											$cc = [["email"=>'Corporate@goldencube.ae']];
										}
										$email_array = array(
											'email' => $leadDetails["customer_email"],
											'subject' => 'Visa application submitted',
											'template' => 'mails/'.$template,
											'from_name' => "Golden Cube",
											'message' => $gc_message,
											'branch_id' => '106',
											'cc' => $cc
										);
										$send_mail = send_lead_template_email($email_array);
									}
									$childs = $this->db->select("*")->from("leads")->where("lead_parent_id", $lead->lead_parent_id)->where("lead_status !=", 305)->get()->result_array();
									$this->db->where('id', $lead->lead_parent_id);
									$this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
									$this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
									$this->db->update('leads');

									if (count($childs) == 0) {

										$order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $order_id;
										$log_insert_array = array('action_id' => 410, 'lead_id' => $lead->lead_parent_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => 178140614, 'status_id' => 305);
										//$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

										$update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id, "completed_by" => 178140614);
										$this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead->lead_parent_id));

										$completion_email = $this->completeEmail($lead->lead_parent_id);
										if ($lead->branch_id == 106 && !in_array($lead->package_id, [165,166,193,194,195, 198,202,203, 199,204,205,258,259,260, 200,201,206,207,208,209,261,262,263, 210,211,212, 222,223,224, 213,214,215,219,220,221,216,217,218, 225,226,227, 228,229,230, 255,256,257])) {
											$main_lead_completion_email = $this->main_lead_completeEmail($lead->lead_parent_id);
										}
									}
								} else {
									//AADITHYA 05-06-2023
									if ($lead->lead_status != 305) {

										$order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $order_id;
										$log_insert_array = array('action_id' => 410, 'lead_id' => $lead->id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => 178140614, 'status_id' => 305);
										$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

										//$update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id, "completed_by" => 178140614);

										$this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead->id));

										$completion_email = $this->completeEmail($lead->id);
										
									}
									//AADITHYA 05-06-2023
								}

								// task creation start
								$service_id = $lead->service_id;
								// $service_id != 3919
								$lead_category = $this->mcommon->specific_row_value('leads', array('id' => $lead->id), 'block_category');
								// if (!in_array($lead->package_id, [222, 223, 226, 227, 228]) || ($lead->package_id == 222 && $lead_category == 'basic')) {
								if (!in_array($lead->package_id, [199,204,205,258,259,260, 222,223,224, 225,226,227, 228,229,230]) || (in_array($lead->package_id,[200,201,206,207,208,209,261,262,263]) && $lead_category == 'basic')) {
									$service_details = $this->db->select("ocs.service_id, os.*")
										->from("ontime_services os")
										->join("ontime_category_services_ ocs", "os.id = ocs.gc_service_id")
										->where("ocs.service_id", $service_id)
										->get()->result_array();
									if (!empty($service_details)) {
										$is_task = $service_details[0]['is_task'];
										if ($is_task == 1) {
											// $service_group = $service_details[0]['service_group'];
											// $this->db->select('u.user_id,u.first_name,u.last_name,u.role_id,group_concat(REPLACE(groups.group_name," ","")," ") as group_s,u.employee_id');
											// $this->db->from('users as u');
											// $this->db->join("group_members", "group_members.user_id=u.user_id");
											// $this->db->join("groups", "groups.group_id=group_members.group_id");
											// $this->db->where('u.is_active', 1);
											// $this->db->where('groups.group_id', $service_group);
											// // $this->db->where('u.role_id', 6);
											// $this->db->order_by("u.first_name");
											// $this->db->group_by('u.user_id,group_members.user_id');
											// $get_group_members =  $this->db->get()->result_array();
											// $user_id = 0;
											// if(in_array($service_details[0]['id'], [95,2303,2305,2307,179])){
											// 	$user_id = '4213254981'; //salam
											// } else if(in_array($service_details[0]['id'], [2304])) {
											// 	$user_id = '2411946200'; //abdul
											// } else if (in_array($service_details[0]['id'], [165,166,2306,1516,93])){
											// 	$user_id = '1631471526'; //Jan
											// } else if (in_array($service_details[0]['id'], [114,2308,2])){
											// 	$user_id = '2685781987'; //abubakar
											// } else if (in_array($service_details[0]['id'], [1516])){
											// 	$user_id = '2808527829'; //rowdan
											// }
											
											// $this->db->select('u.user_id,u.first_name,u.last_name,u.role_id,u.employee_id');
											// $this->db->from('users as u');
											// $this->db->where('u.is_active', 1);
											// $this->db->where('u.user_id', $user_id);
											// // $this->db->where('u.role_id', 6);
											// $this->db->order_by("u.first_name");
											// $this->db->group_by('u.user_id');
											// $get_group_members =  $this->db->get()->result_array();


											// if (!empty($get_group_members) && !empty($user_id)) {
											// 	$task_assigned_to = $get_group_members[0]['user_id'];
											// 	$task_assigned_to_name = $get_group_members[0]['first_name'] . " " . $get_group_members[0]['last_name'];
											// 	$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
											$is_assigned = $this->mcommon->specific_record_counts('leads_assigned', array('lead_id' => $lead_id));
											$task_created = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'task_created');
											if (empty($task_created)) {
												$parent_lead_assigned = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead->lead_parent_id), 'assigned_to');
												$user_name = $this->mcommon->specific_row('users', array('user_id' => $parent_lead_assigned));

												$task_assigned_to = $parent_lead_assigned;
												$task_assigned_to_name = $user_name['first_name'] . " " . $user_name['last_name'];

												$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
												$assigned_to = $task_assigned_to;
												$assigned_by = 178140614; // web API user id
												$insert_array = array(
													'lead_id' => $lead_id,
													'assigned_by' => $assigned_by,
													'assigned_to' => $assigned_to,
													'assigned_on' => date('Y-m-d H:i:s')
												);
												$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);
												if ($insert > 0) {
													$log_insert_array = array('action_id' => 447, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => "The task for Sublead-" . $lead_id . " has been assigned to " . $task_assigned_to_name, 'action_by' => $assigned_by, 'status_id' => 646);
													$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

													$update_task_array = array('task_created' => 1, 'task_created_at' => date('Y-m-d H:i:s'), 'task_assigned' => $assigned_to, 'task_status' => 'open');
													$update_lead = $this->mcommon->common_edit('leads', $update_task_array, array('id' => $lead_id));
												}
											}
											if($service_details[0]['gc_service_id'] == 93){ // visa processing
												$update_task_array = array('visa_status' => '3');
												$update_lead = $this->mcommon->common_edit('leads', $update_task_array, array('id' => $lead_id));
											}
											// }
										}
									}
								}
								// task creation end
							}
							return $this->response(["status" => true, "message" => "Lead converted to Order."], 200);
						} else {
							return $this->response(["status" => false, "message" => "lead_receipt parameter missing. "], 422);
						}
					}
				} else {
					return $this->response(["status" => false, "message" => "INVALID lead_id"], 422);
				}
			} else {
				if (!isset($post_data["lead_id"]) && !isset($post_data["lead_receipt"])) {
					return $this->response(["status" => false, "message" => "lead_id & lead_receipt parameters missing. "], 422);
				}
				if (!isset($post_data["lead_id"])) {
					return $this->response(["status" => false, "message" => "lead_id parameter missing. "], 422);
				}
				if (!isset($post_data["lead_receipt"])) {
					return $this->response(["status" => false, "message" => "lead_receipt parameter missing. "], 422);
				}
			}
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	public function packagecomplete_post()
	{
		try {

			$post_data = $_POST;
			if (isset($post_data["lead_id"]) && isset($post_data["lead_receipt"]) && isset($post_data["service_name"])) {
				$service = $post_data["service_name"];
				$lead = explode("-", $post_data["lead_id"]);
				$lead_endpoint = end($lead);
				$lead_id = str_replace("OTLDPMET", "", $lead_endpoint);
				$lead = $this->db->select("leads.*")->from("leads")->join("ontime_category_services_", "ontime_category_services_.service_id=leads.service_id")->where("leads.lead_parent_id", $lead_id)->like("ontime_category_services_.service_name", $service)->where("leads.lead_status !=", 305)->where("leads.lead_status !=", 306)->get()->first_row();
				if ($lead) {
					if ($lead->lead_status == 305 || $lead->lead_status == 306) {
						return $this->response(["status" => true, "message" => "Lead already converted."], 200);
					} else {
						$lead_id = $lead->id;
						$order_id = $post_data['lead_receipt'];
						if ($order_id != '') {
							$order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $order_id;
							$log_insert_array = array('action_id' => 410, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => 178140614, 'status_id' => 305);
							$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							// echo "Hello#";
							// print_r($this->db->error());
							if ($insert_log > 0) {
								$update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id, "completed_by" => 178140614);
								if ($_POST["pos_GovtFees"] != "") $update_lead_array["pos_GovtFees"] = $_POST["pos_GovtFees"];
								if ($_POST["pos_TypeFee"] != "") $update_lead_array["pos_TypeFee"] = $_POST["pos_TypeFee"];
								if ($_POST["pos_Card_Amnt"] != "") $update_lead_array["pos_Card_Amnt"] = $_POST["pos_Card_Amnt"];
								if ($_POST["pos_Disc_Amnt"] != "") $update_lead_array["pos_Disc_Amnt"] = $_POST["pos_Disc_Amnt"];
								if ($_POST["pos_Tax_Amnt"] != "") $update_lead_array["pos_Tax_Amnt"] = $_POST["pos_Tax_Amnt"];
								if ($_POST["pos_Tot_Amt"] != "") $update_lead_array["pos_Tot_Amt"] = $_POST["pos_Tot_Amt"];
								if ($_POST["pos_Tot_Revn"] != "") $update_lead_array["pos_Tot_Revn"] = $_POST["pos_Tot_Revn"];
								if ($_POST["pos_username"] != "") $update_lead_array["pos_CreatedBy"] = $_POST["pos_CreatedBy"];

								$update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));

								if ($lead->lead_parent_id != 0) {
									$childs = $this->db->select("*")->from("leads")->where("lead_parent_id", $lead->lead_parent_id)->where("lead_status !=", 305)->get()->result_array();
									$this->db->where('id', $lead->lead_parent_id);
									$this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
									$this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
									$this->db->update('leads');

									if (count($childs) == 0) {
										$order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $order_id;
										$log_insert_array = array('action_id' => 410, 'lead_id' => $lead->lead_parent_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => 178140614, 'status_id' => 305);
										$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

										$update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id, "completed_by" => 178140614);
										$this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead->lead_parent_id));
									}
								}
							}
							return $this->response(["status" => true, "message" => "Lead converted to Order."], 200);
						} else {
							return $this->response(["status" => false, "message" => "lead_receipt parameter missing. "], 422);
						}
					}
				} else {
					return $this->response(["status" => false, "message" => "Invalid lead_id or No Service like that to complete."], 422);
				}
			} else {
				if (!isset($post_data["lead_id"]) && !isset($post_data["lead_receipt"])) {
					return $this->response(["status" => false, "message" => "lead_id & lead_receipt & service_name parameters missing. "], 422);
				}
				if (!isset($post_data["lead_id"])) {
					return $this->response(["status" => false, "message" => "lead_id parameter missing. "], 422);
				}
				if (!isset($post_data["lead_receipt"])) {
					return $this->response(["status" => false, "message" => "lead_receipt parameter missing. "], 422);
				}
			}
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}


	public function create_post()
	{
		// return $this->response(["status"=>"False"]);
		try {
			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);

			$branch_id = $this->post('branch_id');
			$lead_type = ($this->post('lead_type')) ? $this->post('lead_type') : "normal";
			$attachment_name = $this->post('attachment_name');
			$attachment_name = ($attachment_name == '') ? 'Attachment' : $attachment_name;
			$lead_by_pos_user = $this->post('lead_assigned_by');
			$lead_by_post_user_name = $this->post('lead_assigned_by_name');
			$lead_type = trim($lead_type);
			// echo "Type:".$lead_type;
			// exit();
			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
			}

			if ($lead_type == "normal") {
				// echo "Normal Lead";
				// exit();
				$category_id = $this->post('category_id');
				$service_id = $this->post('service_id');

				//check category exist
				$cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
				if ($cateogry_exist == 0) {
					$this->response("Category doesn't exist. Update category first to create the lead", 404);
				}

				$service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
				if ($service_exist == 0) {

					$this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
				}

				$this->form_validation->set_rules('category_id', 'Category', 'required');
				$this->form_validation->set_rules('service_id', 'Service', 'required');
			}

			// echo $category_id."<br>";
			// echo $service_id."<br>";

			if ($lead_type == 'package') {
				$package_id = $this->input->post('package_id');
				$package_exist = $this->mcommon->specific_record_counts('lead_packages', array('package_id' => $package_id));
				if ($package_exist == 0) {
					$this->response("Package doesn't exist. Update package first to create the lead", 404);
				}
				$this->form_validation->set_rules('package_id', 'Package', 'required');
			}

			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			$lead_remarks = $this->post('lead_remarks');

			$this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
			$this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
			//$this->form_validation->set_rules('lead_email', 'Email Address', 'required');
			$this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

			if ($this->form_validation->run() == TRUE) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					// return $user_id;
				}

				// echo $is_exist;


				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					// return $user_id;
				}



				if ($user_id != 0) {
					$uploaded_file_name = '';
					//Upload document and get the file name
					if (isset($_FILES['files']['name'])) {
						$config = array(
							'upload_path' => "../uploads/leads",
							'allowed_types' => "gif|jpg|png|jpeg|pdf",
							'file_name' => sha1(time())
						);
						$this->load->library('upload', $config);

						if ($this->upload->do_upload('files')) {
							$data = array('upload_data' => $this->upload->data());
							$path = $config['upload_path'] . '/' . $data['upload_data']['file_name'];
							$uploaded_file_name = $data['upload_data']['file_name'];
						}
					}
					//process lead type
					if ($lead_type == 'normal') {
						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 301,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									'created_group_id' => $created_group_id,
								);
								$insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
								if ($insert_lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									$insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							// else create one lead for selected category & service
							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => $service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								'created_group_id' => $created_group_id
							);
							$insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
							$normal_lead_count = 1;
						}

						if ($normal_lead_count > 0) {
							$lead_id = $insert_lead_id;
							if ($branch_id == 132) {
								$insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
								$insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

								$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));

								$assigned_by = $lead_by_pos_user;
								$assigned_to = 1167483351;
								$insert_array = array(
									'lead_id' => $lead_id,
									'assigned_by' => $assigned_by,
									'assigned_to' => $assigned_to,
									'assigned_on' => date('Y-m-d H:i:s')
								);
								// echo "<br>";
								// echo "<br> ";
								// print_r($insert_array);
								$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

								$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
								$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

								$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
								$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

								$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
								// print_r($log_insert_array);
								$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

								// echo "Log: ".$log_insert."<br>";
								// echo "ERROR: ";
								// print_r($this->db->error());
								// exit();
								if ($insert > 0) {
									$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

									if ($update) {
										//create action log
										$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
										$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

										$receiver_email = $csa->email;
										$receiver_name = $csa->first_name;
										$sender_email = $coordinator->email;
										$sender_name = $coordinator->first_name;

										$subject = "CRM ALERT";
										$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
										$email_array = array(
											'email' => $receiver_email,
											'subject' => $subject,
											'template' => 'mails/template',
											'from_name' => "CRM ALERT",
											'message' => $message,
										);
									}
								}
							}
							$this->response($normal_lead_count . ' lead(s) has been created.', 200);
						} else {
							$this->response('Unable to create leads at this moment.Please try again.', 500);
						}
					}


					if ($lead_type == 'package') {
						$package_lead_count = 0;
						$packages = $this->leads_model->get_package_entries($package_id);
						$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
						foreach ($packages as $key => $value) {
							$target_service_id = $value['service_id'];
							$category_id = $value['category_id'];

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => $target_service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => $package_id,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								'created_group_id' => $created_group_id,
							);
							$insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
							if ($insert_lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API User</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

								//handle attachment
								$insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
								$insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
								$package_lead_count++;
							}
						}


						if ($package_lead_count > 0) {
							$this->response($package_lead_count . ' lead(s) has been created.', 200);
						} else {
							$this->response('Unable to create leads at this moment.Please try again.', 500);
						}
					}
				} else {
					$this->response('Unable to create leads at this moment.Please try again.', 500);
				}
			} else {
				$this->response(validation_errors(), 400);
			}
		} catch (Exception $e) {
			$this->response(['status' => false, 'message' => $e->getMessage()]);
		}
	}

	public function biz_post()
	{
		// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
		// print_r($this->post());
		// exit();
		$service_id = 101;
		// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
		// print_r($this->post());
		// exit();

		$branch_id = 101;
		$lead_type = "normal";
		$lead_name = $this->post('lead_name');
		$lead_contact = $this->post('lead_contact');
		$lead_email = $this->post('lead_email');
		$lead_email = trim($lead_email);
		$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
		$lead_by_pos_user = 4203160735; //Campaign Marketing
		$lead_by_post_user_name = "Marketing Campaign";

		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$random_string = substr(str_shuffle($str_result), 0, 10);


		//check category exist
		$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
		if ($branch_exist == 0) {
			$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
		}

		$category_id = 101;

		// print_r($service_id);
		// exit();
		// echo "<br>================================<br>";
		//check category exist

		// $this->form_validation->set_rules('service_name', 'service_name', 'required');


		$lead_name = $this->post('lead_name');
		$lead_contact = $this->post('lead_contact');
		// $lead_email = $this->post('lead_email');
		// $lead_remarks = $this->post('lead_remarks');

		$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
		// $this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
		$this->form_validation->set_rules('lead_email', 'lead_email', 'required');
		// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

		if ($this->form_validation->run() == TRUE) {
			$random_email_name = strtolower($random_string);
			$random_email = $random_email_name . '@ontimecustomer.com';
			$lead_email = ($lead_email == '') ? $random_email : $lead_email;
			//create or get customer
			//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

			// $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
			$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

			$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

			if ($is_exist != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
				return $user_id;
			}

			if ($is_exist == 0) {
				$password = 'Welcome@123';
				$confirm_password = 'Welcome@123';
				$auth_level = '1';
				$referal_code = $random_string;
				$user_hashed_password = $this->authentication->hash_passwd($password);
				$user_data = [
					'auth_level' => $auth_level,
					'mobile' => $lead_contact,
					'referal_code' => $referal_code,
					'first_name' => $lead_name,
					'passwd' => $user_hashed_password,
					'email' => trim($lead_email),
					'confirm_password' => $user_hashed_password,
				];
				$user_data['user_id'] = $this->authentication_model->get_unused_id();
				$user_data['created_at'] = date('Y-m-d H:i:s');
				$user_data['otp'] = rand(1000, 9000);
				$user_data['email_otp'] = rand(1000, 9000);
				$user_data['banned'] = '0';
				$user_data['role_id'] = '4';
				$user_data['country'] = 'United Arab Emirates';
				$user_data['country_code'] = '+971';

				$insert = $this->mcommon->common_insert("lead_users", $user_data);

				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
				//return $user_id;
			}

			if ($user_id != 0) {
				$uploaded_file_name = '';
				//process lead type
				if ($lead_type == 'normal') {

					$normal_lead_count = 0;
					//get the workflow for the service.
					$workflows = $this->leads_model->get_workflow_entries($service_id);

					if (!empty($workflows)) {
						$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');
						//if there are existing workflows for selected category & service, create lead entry for each workflow entry
						foreach ($workflows as $key => $value) {
							$parent_service_id = $value['parent_service_id'];
							$target_service_id = $value['target_service_id'];
							$category_id = $value['category_id'];
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => $target_service_id,
								'lead_created_by' => $lead_by_pos_user,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								'created_group_id' => $created_group_id
							);
							$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

							if ($lead_id > 0) {

								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
								// print_r($log_insert_array);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

								// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
								// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

								$normal_lead_count++;
							}
						}
					} else {
						// else create one lead for selected category & service
						$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
						$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');

						$insert_lead_array = array(
							'customer_id' => $user_id,
							'branch_id' => $branch_id,
							'category_id' => $category_id,
							'service_id' => (int)$service_id,
							'lead_created_by' => $lead_by_pos_user,
							'lead_added_on' => date('Y-m-d H:i:s'),
							'contactable_date' => date('Y-m-d H:i:s'),
							'lead_status' => 301,
							'package_id' => 0,
							'order_receipt' => 0,
							'remarks' => $lead_remarks,
							'is_assigned' => 0,
							'lead_by_pos_user' => $lead_by_pos_user,
							'lead_by_post_user_name' => $lead_by_post_user_name,
							'created_group_id' => $created_group_id,
						);
						$inserts = $this->db->insert("leads", $insert_lead_array);
						// echo $this->db->last_query();
						// echo "Inserts==> ";
						// print_r($inserts);
						$lead_id = $this->db->insert_id();

						$normal_lead_count = 1;
					}


					if ($lead_id > 0) {

						$rec = $this->db->select("*")->from("leads_assigned")->where("assigned_by", $lead_by_pos_user)->order_by("assigned_on", "desc")->get()->first_row();
						if ($rec) {
							// print_r($rec);
							$assignable_user = $rec->assigned_to;
							if ($assignable_user == 3212920395) $assignable_user = 1564456050;
							else $assignable_user = 3212920395;
						} else $assignable_user = 1564456050;
						// print_r($assignable_user);
						// exit();

						// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
						// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
						// $assigned_to = 3586925300; // paul.g@ontimebusinesssetup.com

						$assigned_to = $assignable_user; // mahmoud.a@ontimebusinesssetup.com
						//$assigned_to = 1564456050; // aisha.s@ontimebusinesssetup.com
						// $assigned_to = 678064846;
						$assigned_by = $lead_by_pos_user;

						$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
						$insert_array = array(
							'lead_id' => $lead_id,
							'assigned_by' => $assigned_by,
							'assigned_to' => $assigned_to,
							'assigned_on' => date('Y-m-d H:i:s')
						);
						// echo "<br>";
						// echo "<br> ";
						// print_r($insert_array);
						$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

						$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
						$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

						$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
						$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


						$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
						// print_r($log_insert_array);
						$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

						// echo "Log: ".$log_insert."<br>";
						// echo "ERROR: ";
						// print_r($this->db->error());
						// exit();
						if ($insert > 0) {
							$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

							if ($update) {
								//create action log
								$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

								// $receiver_email = $csa->email;
								$receiver_email = $csa->email;
								$receiver_name =  $csa->first_name;
								$sender_email = $coordinator->email;
								$sender_name =  $coordinator->first_name;

								$subject = "CRM ALERT";

								$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
								$email_array = array(
									'email' => $receiver_email,
									'subject' => $subject,
									'template' => 'mails/template',
									'from_name' => "CRM ALERT",
									'message' => $message,
									'branch_id' => $branch_id,
								);
								$send_mail = send_template_email($email_array);
								log_message('error', $send_mail);

								$postData = array(
									'lead_id' => $lead_id,
								);
					
								$ch = curl_init();
								curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
								curl_setopt($ch, CURLOPT_HTTPHEADER, [
									'Accept: application/json',
									'Content-Type: application/json'
								]);
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
								curl_setopt($ch, CURLOPT_HEADER, false); 
								curl_setopt($ch, CURLOPT_POST, true);  
								curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
								curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
								$response = curl_exec($ch);
								if(!empty($response))
									$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

								$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
							} else {
								$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
								// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

								$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
							}
						} else {
							$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
						}


						$this->response(["status" => true, "message" => "Lead successfully created."], 200);
					} else {
						$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
					}

					// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

				} else {
					$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
				}
			} else {
				$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error(), "data" => $user_data], 500);
			}
		} else {
			$this->response(["status" => false, "message" => validation_errors()], 400);
		}
	}

	public function dlded_post()
	{
		// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
		// print_r($this->post());
		// exit();
		// 101, 102, 103, 104, 108 - POS Leads
		// 106, 110 - OntimeGOV
		// 105, 107, 106 - Abdulaziz.a@goldencube.ae is the Assigned to User 
		$serv_id = $this->post("service_id");
		$zoho_res = "None";
		if ($serv_id == 101) {
			$branch_id = 100;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			$lead_by_pos_user = 178140614;
			$lead_by_post_user_name = "WEB API";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 110;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			$this->form_validation->set_rules('service_name', 'service_name', 'required');


			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			// $lead_remarks = $this->post('lead_remarks');

			$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
			$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
			// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
			// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

			if ($this->form_validation->run() == TRUE) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 301,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									// 'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id,
								);
								$insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($insert_lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								// 'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}

						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 2964119540; // mohammad.a@ontimetrustee.com
							// $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));

							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
									);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));
									// $send_mail = send_template_email($email_array);
									// log_message('error', $send_mail);

									// try {

									// 	$curl = curl_init();

									// 	curl_setopt_array($curl, array(
									// 		CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
									// 		CURLOPT_RETURNTRANSFER => true,
									// 		CURLOPT_ENCODING => '',
									// 		CURLOPT_MAXREDIRS => 10,
									// 		CURLOPT_TIMEOUT => 0,
									// 		CURLOPT_FOLLOWLOCATION => true,
									// 		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									// 		CURLOPT_CUSTOMREQUEST => 'POST',
									// 		CURLOPT_POSTFIELDS => array('client_id' => '1000.SNAU7L0CEEQYLH6F3XELSEFMZ5IZZQ', 'client_secret' => 'f69319cf08f0939f5d0b1e8da486d587a52c1d4e9c', 'refresh_token' => '1000.2a0b05ee4d64d9ab1e85940b0409e361.a77af540fa8a7b43aacb7b90a3d4fe23', 'grant_type' => 'refresh_token')
									// 	));

									// 	$response = curl_exec($curl);
									// 	log_message('log', 'ZOHO Token: ' . $response);
									// 	curl_close($curl);
									// 	// echo "<br>";				
									// 	// // echo $response;
									// 	// echo "<br>";
									// 	$res_json = json_decode($response);

									// 	$token = $res_json->access_token;

									// 	// print_r($res_json);
									// 	// echo "<br><br>";
									// 	// print($req);
									// 	// exit();
									// 	$req = [];
									// 	$req["First_Name"] = $lead_name;
									// 	$req["Last_Name"] = $lead_name;
									// 	$req["Email"] = $lead_email;
									// 	$req["Phone"] = $lead_contact;
									// 	$req["Description"] = $lead_remarks;
									// 	$req["Lead_Source"] = "POS";
									// 	$curl = curl_init();
									// 	log_message('log', 'ZOHO Req: ' . $req);

									// 	$data["data"] = [$req];
									// 	// echo "<br>";
									// 	// echo "<pre>";
									// 	// echo json_encode($data);
									// 	// echo "</pre>";
									// 	// exit();
									// 	curl_setopt_array($curl, array(
									// 		CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/Leads',
									// 		CURLOPT_RETURNTRANSFER => true,
									// 		CURLOPT_ENCODING => '',
									// 		CURLOPT_MAXREDIRS => 10,
									// 		CURLOPT_TIMEOUT => 0,
									// 		CURLOPT_FOLLOWLOCATION => true,
									// 		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									// 		CURLOPT_CUSTOMREQUEST => 'POST',
									// 		CURLOPT_POSTFIELDS => json_encode($data),
									// 		CURLOPT_HTTPHEADER => array(
									// 			'Authorization: Bearer ' . $token,
									// 			'Content-Type: application/json'
									// 		),
									// 	));

									// 	$response = curl_exec($curl);

									// 	curl_close($curl);
									// 	log_message('log', 'ZOHO CRM Res: ' . $response);
									// 	// print_r($response);
									// 	$zoho_res = $response;
									// 	$update = $this->mcommon->common_edit('leads', array('zoho_response' => $response), array('id' => $lead_id));
									// } catch (Exception $e) {
									// 	// print_r($e);
									// 	$zoho_res = $e->getMessage();
									// 	$update = $this->mcommon->common_edit('leads', array('zoho_response' => $e->getMessage()), array('id' => $lead_id));
									// }


									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!', "zoho_response" => $zoho_res], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}
					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				$this->response(["status" => false, "message" => validation_errors()], 400);
			}
		} else if ($serv_id == 102) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			// print_r($this->post());
			// exit();

			$branch_id = 100;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			$lead_by_pos_user = 178140614;
			$lead_by_post_user_name = "WEB API";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 111;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');


			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			// $lead_remarks = $this->post('lead_remarks');

			$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
			$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
			// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
			// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

			if ($this->form_validation->run() == TRUE) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 301,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									// 'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
								// echo "There ==> " . $lead_id;
								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								// 'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							// $assigned_to = 3586925300; // paul.g@ontimebusinesssetup.com
							// //$assigned_to = 1564456050; // aisha.s@ontimebusinesssetup.com
							// $assigned_to = 3212920395; // mahmoud.a@ontimebusinesssetup.com
							$assigned_to = 3771749283; // huida@ontimebiz.com
							// $assigned_to = 678064846; // muthu.v@egovllc.com
							$assigned_to = 2804455979; // lyn@ontimebiz.com

							// // $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// // echo "<br>";
							// // echo "<br> ";
							// // print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// // print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								//$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));
								$update = 1;
								if ($update) {

									if ($user_id != 0) {
										$insert_lead_array = array(
											'customer_id' => $user_id,
											'branch_id' => $branch_id,
											'category_id' => $category_id,
											'service_id' => $service_id,
											'lead_created_by' => $this->auth_user_id,
											'lead_added_on' => date('Y-m-d H:i:s'),
											'contactable_date' => date('Y-m-d H:i:s'),
											'lead_status' => 301,
											'package_id' => 0,
											'order_receipt' => 0,
											'remarks' => $lead_remarks,
											'is_assigned' => 0,
										);
										$insert_lead_id = $this->mcommon->common_insert('biz_leads', $insert_lead_array);
										// print_r($insert_lead_id);
										// print_r($this->db->error());
										// exit();
										$curl = curl_init();
										$req = "lead_name=" . $lead_name . "&lead_email=" . $lead_email . "&lead_contact=" . $lead_contact . "&lead_remarks=" . $lead_remarks . "&is_pos=1";
										curl_setopt_array($curl, array(
											CURLOPT_URL => "https://crm.ontimebiz.com/api/v1/lead/biz",
											CURLOPT_RETURNTRANSFER => true,
											CURLOPT_ENCODING => "",
											CURLOPT_MAXREDIRS => 10,
											CURLOPT_TIMEOUT => 30,
											CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
											CURLOPT_CUSTOMREQUEST => "POST",
											CURLOPT_POSTFIELDS => $req,
											CURLOPT_HTTPHEADER => array(
												"cache-control: no-cache",
												"content-type: application/x-www-form-urlencoded",
											),
										));

										$response = curl_exec($curl);
										log_message('debug', $req . "<br/>" . $response);
										$err = curl_error($curl);

										curl_close($curl);

										$postData = array(
											'lead_id' => $lead_id,
										);
							
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
										curl_setopt($ch, CURLOPT_HTTPHEADER, [
											'Accept: application/json',
											'Content-Type: application/json'
										]);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
										curl_setopt($ch, CURLOPT_HEADER, false); 
										curl_setopt($ch, CURLOPT_POST, true);  
										curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
										$response = curl_exec($ch);
										if(!empty($response))
											$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

										$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
									} else {
										$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
										// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

										$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
									}
								} else {
									$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
								}


								$this->response(["status" => true, "message" => "Lead successfully created."], 200);
							} else {
								$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again..', "exception" => $this->db->error()], 500);
							}

							// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

						} else {
							$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
						}
					} else {
						$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => validation_errors()], 400);
				}
			}
		} else if ($serv_id == 103) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			//print_r($this->post());
			//exit();

			$branch_id = 100;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			$lead_by_pos_user = 1721304764;
			$lead_by_post_user_name = "WEB API";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 110;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');





			if (!empty($lead_name)) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 301,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									// 'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id,
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								// 'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 1721304764; // shahzar.k@ontimehealthcare.com
							// $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
										'branch_id' => $branch_id,
									);
									$send_mail = send_template_email($email_array);
									log_message('error', $send_mail);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}

						// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				//$this->response(["status" => false, "message" => validation_errors()], 400);
				$this->response(["status" => false, "message" => "Missing Values"], 400);
			}
		} else if ($serv_id == 110) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			//print_r($this->post());
			//exit();

			$branch_id = 100;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			$lead_by_pos_user = 4294967295;
			$lead_by_post_user_name = "Ontime Gov";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 110;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');





			if (!empty($lead_name)) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 4294967295,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 301,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id,
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 4294967295,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 3573695398; // moustafa.a@ontimegov.com
							// $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
										'branch_id' => $branch_id,
									);
									$send_mail = send_template_email($email_array);
									log_message('error', $send_mail);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}

						// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				//$this->response(["status" => false, "message" => validation_errors()], 400);
				$this->response(["status" => false, "message" => "Missing Values"], 400);
			}
		} else if ($serv_id == 108) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			//print_r($this->post());
			//exit();

			$branch_id = 100;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			$lead_remarks = "Notary Public <br /><br />" . $this->post("service_name") . "<br /><br />" . $this->post('remarks');
			$lead_by_pos_user = 1721304764;
			$lead_by_post_user_name = "WEB API";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 110;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');





			if (!empty($lead_name)) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist > 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					//return $user_id;
				} else if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = "Notary Public <br /><br />" . $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 301,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									// 'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id,
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = "Notary Public <br /><br />" . $this->post("service_name") . "<br><br>" . $this->post('remarks');

							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								// 'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 551550256;	// Ali.s@altasweyaat.com
								//123546987; // ahmed.e@altaswyat.com
							// $assigned_to = 678064846;
							$assigned_by = 178140614;	//$lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
										'branch_id' => $branch_id,
									);
									$send_mail = send_template_email($email_array);
									log_message('error', $send_mail);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully! Lead ID ', "Lead ID" => $lead_id], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}

						// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				//$this->response(["status" => false, "message" => validation_errors()], 400);
				$this->response(["status" => false, "message" => "Missing Values"], 400);
			}
		} else if ($serv_id == 104) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			// print_r($this->post());
			// exit();

			$branch_id = 100;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			// $lead_by_pos_user = 1721304764;
			$lead_by_pos_user = 178140614;
			$lead_by_post_user_name = "WEB API";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 2237;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');


			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			// $lead_remarks = $this->post('lead_remarks');

			$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
			$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
			// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
			// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

			if ($this->form_validation->run() == TRUE) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 301,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									// 'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id,
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								// 'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 826368930; // Queen Insurance User
							// $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
										'branch_id' => $branch_id,
									);
									$send_mail = send_template_email($email_array);
									log_message('error', $send_mail);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}

						// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				$this->response(["status" => false, "message" => validation_errors()], 400);
			}
		} 
		/*else if ($serv_id == 105) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			// print_r($this->post());
			// exit();

			$branch_id = 106;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			$lead_by_pos_user = 178140614;
			$lead_by_post_user_name = "WEB API";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 10020;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');


			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			// $lead_remarks = $this->post('lead_remarks');

			$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
			$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
			// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
			// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

			if ($this->form_validation->run() == TRUE) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 322,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									// 'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								// 'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 2411946200; // Abdulaziz.a@goldencube.ae
							// $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 322), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
									);
									$send_mail = send_template_email($email_array);
									log_message('error', $send_mail);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}

						// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				$this->response(["status" => false, "message" => validation_errors()], 400);
			}
		} 
		else if ($serv_id == 106) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			// print_r($this->post());
			// exit();

			$branch_id = 106;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			$lead_by_pos_user = 4294967295;
			$lead_by_post_user_name = "Ontime Gov";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 10020;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');


			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			// $lead_remarks = $this->post('lead_remarks');

			$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
			$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
			// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
			// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

			if ($this->form_validation->run() == TRUE) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 4294967295,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 303,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id,
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 4294967295,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 2411946200; // Ver
							// $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 303), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
									);
									$send_mail = send_template_email($email_array);
									log_message('error', $send_mail);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}

						// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				$this->response(["status" => false, "message" => validation_errors()], 400);
			}
		} */
		else if ($serv_id == 107) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			// print_r($this->post());
			// exit();

			$branch_id = 120;
			$lead_type = "normal";
			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
			$lead_by_pos_user = 178140614;
			$lead_by_post_user_name = "WEB API";



			$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
			$random_string = substr(str_shuffle($str_result), 0, 10);


			//check category exist
			$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
			if ($branch_exist == 0) {
				$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
			}

			$category_id = 107;
			$service = $this->post("service_name");
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
			}
			// print_r($service_id);
			// exit();
			// echo "<br>================================<br>";
			//check category exist

			// $this->form_validation->set_rules('service_name', 'service_name', 'required');


			$lead_name = $this->post('lead_name');
			$lead_contact = $this->post('lead_contact');
			$lead_email = $this->post('lead_email');
			$lead_email = trim($lead_email);
			// $lead_remarks = $this->post('lead_remarks');

			$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
			$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
			// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
			// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

			if ($this->form_validation->run() == TRUE) {
				$random_email_name = strtolower($random_string);
				$random_email = $random_email_name . '@ontimecustomer.com';
				$lead_email = ($lead_email == '') ? $random_email : $lead_email;
				//create or get customer
				//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

				$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
				$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

				$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

				if ($is_exist != 0) {
					$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					return $user_id;
				}

				if ($is_exist == 0) {
					$password = 'Welcome@123';
					$confirm_password = 'Welcome@123';
					$auth_level = '1';
					$referal_code = $random_string;
					$user_hashed_password = $this->authentication->hash_passwd($password);
					$user_data = [
						'auth_level' => $auth_level,
						'mobile' => $lead_contact,
						'referal_code' => $referal_code,
						'first_name' => $lead_name,
						'passwd' => $user_hashed_password,
						'email' => trim($lead_email),
						'confirm_password' => $user_hashed_password,
					];
					$user_data['user_id'] = $this->authentication_model->get_unused_id();
					$user_data['created_at'] = date('Y-m-d H:i:s');
					$user_data['otp'] = rand(1000, 9000);
					$user_data['email_otp'] = rand(1000, 9000);
					$user_data['banned'] = '0';
					$user_data['role_id'] = '4';
					$user_data['country'] = 'United Arab Emirates';
					$user_data['country_code'] = '+971';

					$insert = $this->mcommon->common_insert("lead_users", $user_data);

					$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
					//return $user_id;
				}

				if ($user_id != 0) {
					$uploaded_file_name = '';
					//process lead type
					if ($lead_type == 'normal') {

						$normal_lead_count = 0;
						//get the workflow for the service.
						$workflows = $this->leads_model->get_workflow_entries($service_id);

						if (!empty($workflows)) {
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');
							//if there are existing workflows for selected category & service, create lead entry for each workflow entry
							foreach ($workflows as $key => $value) {
								$parent_service_id = $value['parent_service_id'];
								$target_service_id = $value['target_service_id'];
								$category_id = $value['category_id'];
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => $target_service_id,
									'lead_created_by' => 178140614,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => 303,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									// 'lead_from' => 'OntimeGOV',
									'created_group_id' => $created_group_id
								);
								$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

								if ($lead_id > 0) {

									//get branch name
									$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

									//create action log
									$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
									// print_r($log_insert_array);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
									// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

									$normal_lead_count++;
								}
							}
						} else {
							// else create one lead for selected category & service
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => 178140614,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => 301,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								// 'lead_from' => 'OntimeGOV',
								'created_group_id' => $created_group_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							// echo $this->db->last_query();
							// echo "Inserts==> ";
							// print_r($inserts);
							$lead_id = $this->db->insert_id();

							if ($lead_id > 0) {
								//get branch name
								$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

								//create action log
								$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WEB API</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => 178140614, 'status_id' => 301);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$normal_lead_count = 1;
						}


						if ($lead_id > 0) {
							// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
							// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
							$assigned_to = 2411946200; // Ver
							// $assigned_to = 678064846;
							$assigned_by = $lead_by_pos_user;

							$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
							$insert_array = array(
								'lead_id' => $lead_id,
								'assigned_by' => $assigned_by,
								'assigned_to' => $assigned_to,
								'assigned_on' => date('Y-m-d H:i:s')
							);
							// echo "<br>";
							// echo "<br> ";
							// print_r($insert_array);
							$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

							$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
							$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

							$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
							$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


							$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
							// print_r($log_insert_array);
							$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

							// echo "Log: ".$log_insert."<br>";
							// echo "ERROR: ";
							// print_r($this->db->error());
							// exit();
							if ($insert > 0) {
								$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 303), array('id' => $lead_id));

								if ($update) {
									//create action log
									$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									$receiver_email = $csa->email;
									$receiver_name =  $csa->first_name;
									$sender_email = $coordinator->email;
									$sender_name =  $coordinator->first_name;

									$subject = "CRM ALERT";
									$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
									$email_array = array(
										'email' => $receiver_email,
										'subject' => $subject,
										'template' => 'mails/template',
										'from_name' => "CRM ALERT",
										'message' => $message,
										'branch_id' => $branch_id,
									);
									$send_mail = send_template_email($email_array);
									log_message('error', $send_mail);

									$postData = array(
										'lead_id' => $lead_id,
									);
						
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
									curl_setopt($ch, CURLOPT_HTTPHEADER, [
										'Accept: application/json',
										'Content-Type: application/json'
									]);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
									curl_setopt($ch, CURLOPT_HEADER, false); 
									curl_setopt($ch, CURLOPT_POST, true);  
									curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
									$response = curl_exec($ch);
									if(!empty($response))
										$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

									$this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);
								} else {
									$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
									// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);
						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}

						// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

					} else {
						$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				$this->response(["status" => false, "message" => validation_errors()], 400);
			}
		} 
		else {
			$this->response(["status" => false, "message" => "Unable to create the lead. Please send valid service_id"], 401);
		}
	}

	public function generate_post()
	{
		// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
		//print_r($this->post());
		// exit();
		$serv_id = $this->post("api_id");

		if ($serv_id == NULL) {
			$serv_id = $this->post("service_id");
			if ($serv_id == NULL) {
				$this->response(["status" => false, "message" => "'api_id' parameter is missing."], 500);
			}
		}

		$settings = $this->db->where("lead_api_id", $serv_id)->from("api_lead_settings")->get()->first_row();
		// print_r($settings->lead_api_branch);

		$zoho_res = "None";
		if ($settings != NULL) {
			// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
			// print_r($this->post());
			// exit();
			if($settings->lead_api_id == '212'){	// this lead will create as Unassigned Lead
				$branch_id = 123;	//$settings->lead_api_branch;
				$lead_type = "normal";
				$lead_name = $this->post('lead_name');
				$lead_contact = $this->post('lead_contact');
				$lead_email = $this->post('lead_email');
				$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
				$lead_by_pos_user = $settings->lead_api_created_by;
				$lead_by_post_user_name = "WEB API";

				$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
				$random_string = substr(str_shuffle($str_result), 0, 10);

				$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
				if ($branch_exist == 0) {
					$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
				}

				$category_id = $settings->lead_api_category_id;
				$service = $this->post("service_name");
				$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

				if ($service_exist) {
					$service_id = $service_exist["id"];
				} else {
					$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
					$service_id = (int)$service_id;
					$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
				}

				if ($lead_name != "") {
					$random_email_name = strtolower($random_string);
					$random_email = $random_email_name . '@ontimecustomer.com';
					$lead_email = ($lead_email == '') ? $random_email : $lead_email;

					$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
					$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

					$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

					if ($is_exist != 0) {
						$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					}

					if ($is_exist == 0) {
						$password = 'Welcome@123';
						$confirm_password = 'Welcome@123';
						$auth_level = '1';
						$referal_code = $random_string;
						$user_hashed_password = $this->authentication->hash_passwd($password);
						$user_data = [
							'auth_level' => $auth_level,
							'mobile' => $lead_contact,
							'referal_code' => $referal_code,
							'first_name' => $lead_name,
							'passwd' => $user_hashed_password,
							'email' => $lead_email,
							'confirm_password' => $user_hashed_password,
						];
						$user_data['user_id'] = $this->authentication_model->get_unused_id();
						$user_data['created_at'] = date('Y-m-d H:i:s');
						$user_data['otp'] = rand(1000, 9000);
						$user_data['email_otp'] = rand(1000, 9000);
						$user_data['banned'] = '0';
						$user_data['role_id'] = '4';
						$user_data['country'] = 'United Arab Emirates';
						$user_data['country_code'] = '+971';

						$insert = $this->mcommon->common_insert("lead_users", $user_data);
						$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
					}

					if ($user_id != 0) {
						$uploaded_file_name = '';
						if ($lead_type == 'normal') {
							$normal_lead_count = 0;
							$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
							$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $settings->lead_api_created_by, 'is_primary_group_id' => 1), 'group_id');

							$insert_lead_array = array(
								'customer_id' => $user_id,
								'branch_id' => $branch_id,
								'category_id' => $category_id,
								'service_id' => (int)$service_id,
								'lead_created_by' => $settings->lead_api_created_by,
								'lead_added_on' => date('Y-m-d H:i:s'),
								'contactable_date' => date('Y-m-d H:i:s'),
								'lead_status' => $settings->lead_api_status,
								'package_id' => 0,
								'order_receipt' => 0,
								'remarks' => $lead_remarks,
								'is_assigned' => 0,
								'lead_by_pos_user' => $lead_by_pos_user,
								'lead_by_post_user_name' => $lead_by_post_user_name,
								'created_group_id' => 41,	// $created_group_id,
								'assigned_group_id' => 41,
								'is_pos_lead' => 1,
								'pos_api_id' => $settings->lead_api_id,
							);
							$inserts = $this->db->insert("leads", $insert_lead_array);
							$lead_id = $this->db->insert_id();

							$normal_lead_count = 1;

							if ($lead_id > 0) {
								$this->response(["status" => true, "lead_id" => $lead_id, "message" => "Lead successfully created."], 200);
							} else {
								$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
							}
						} else {
							$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
						}
					} else {
						$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => validation_errors()], 400);
				}
			}

			if ($settings->lead_api_crm == "groupcrm") {
				$branch_id = $settings->lead_api_branch;
				$lead_type = "normal";
				$lead_name = $this->post('lead_name');
				$lead_contact = $this->post('lead_contact');
				$lead_email = $this->post('lead_email');
				$lead_email = trim($lead_email);
				$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
				$lead_by_pos_user = $settings->lead_api_created_by;
				$lead_by_post_user_name = "WEB API";

				$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
				$random_string = substr(str_shuffle($str_result), 0, 10);


				//check category exist
				$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
				if ($branch_exist == 0) {
					$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
				}

				$category_id = $settings->lead_api_category_id;
				$service = $this->post("service_name");
				$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

				if ($service_exist) {
					$service_id = $service_exist["id"];
				} else {
					$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
					$service_id = (int)$service_id;
					$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
				}
				//$lead_name = $this->post('lead_name');
				//$lead_contact = $this->post('lead_contact');
				//$lead_email = $this->post('lead_email');
				// $lead_remarks = $this->post('lead_remarks');

				//$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
				//$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
				// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
				// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

				if ($lead_name != "") {
					$random_email_name = strtolower($random_string);
					$random_email = $random_email_name . '@ontimecustomer.com';
					$lead_email = ($lead_email == '') ? $random_email : $lead_email;
					//create or get customer
					//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

					$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
					$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

					$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact,'email' => $lead_email));

					if ($is_exist != 0) {
						$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
						// return $user_id;
					}

					if ($is_exist == 0) {
						$password = 'Welcome@123';
						$confirm_password = 'Welcome@123';
						$auth_level = '1';
						$referal_code = $random_string;
						$user_hashed_password = $this->authentication->hash_passwd($password);
						$user_data = [
							'auth_level' => $auth_level,
							'mobile' => $lead_contact,
							'referal_code' => $referal_code,
							'first_name' => $lead_name,
							'passwd' => $user_hashed_password,
							'email' => trim($lead_email),
							'confirm_password' => $user_hashed_password,
						];
						$user_data['user_id'] = $this->authentication_model->get_unused_id();
						$user_data['created_at'] = date('Y-m-d H:i:s');
						$user_data['otp'] = rand(1000, 9000);
						$user_data['email_otp'] = rand(1000, 9000);
						$user_data['banned'] = '0';
						$user_data['role_id'] = '4';
						$user_data['country'] = 'United Arab Emirates';
						$user_data['country_code'] = '+971';

						$insert = $this->mcommon->common_insert("lead_users", $user_data);

						$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
						// return $user_id;
					}

					// print_r($user_id);
					if ($user_id != 0) {
						$uploaded_file_name = '';
						//process lead type
						if ($lead_type == 'normal') {

							$normal_lead_count = 0;
							//get the workflow for the service.
							$workflows = $this->leads_model->get_workflow_entries($service_id);

							if (!empty($workflows)) {
								$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $settings->lead_api_created_by, 'is_primary_group_id' => 1), 'group_id');
								//if there are existing workflows for selected category & service, create lead entry for each workflow entry
								foreach ($workflows as $key => $value) {
									$parent_service_id = $value['parent_service_id'];
									$target_service_id = $value['target_service_id'];
									$category_id = $value['category_id'];
									$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');

									$insert_lead_array = array(
										'customer_id' => $user_id,
										'branch_id' => $branch_id,
										'category_id' => $category_id,
										'service_id' => $target_service_id,
										'lead_created_by' => $settings->lead_api_created_by,
										'lead_added_on' => date('Y-m-d H:i:s'),
										'contactable_date' => date('Y-m-d H:i:s'),
										'lead_status' => $settings->lead_api_status,
										'package_id' => 0,
										'order_receipt' => 0,
										'remarks' => $lead_remarks,
										'is_assigned' => 0,
										'lead_by_pos_user' => $lead_by_pos_user,
										'lead_by_post_user_name' => $lead_by_post_user_name,
										'created_group_id' => $created_group_id,
										'is_pos_lead' => 1,
										'pos_api_id' => $settings->lead_api_id,
									);
									$lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

									if ($lead_id > 0) {

										//get branch name
										$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

										//create action log
										$log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
										// print_r($log_insert_array);
										$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

										// $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
										// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

										$normal_lead_count++;
									}
								}
							} else {
								// else create one lead for selected category & service
								$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
								$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $settings->lead_api_created_by, 'is_primary_group_id' => 1), 'group_id');

								$insert_lead_array = array(
									'customer_id' => $user_id,
									'branch_id' => $branch_id,
									'category_id' => $category_id,
									'service_id' => (int)$service_id,
									'lead_created_by' => $settings->lead_api_created_by,
									'lead_added_on' => date('Y-m-d H:i:s'),
									'contactable_date' => date('Y-m-d H:i:s'),
									'lead_status' => $settings->lead_api_status,
									'package_id' => 0,
									'order_receipt' => 0,
									'remarks' => $lead_remarks,
									'is_assigned' => 0,
									'lead_by_pos_user' => $lead_by_pos_user,
									'lead_by_post_user_name' => $lead_by_post_user_name,
									'created_group_id' => $created_group_id,
									'is_pos_lead' => 1,
									'pos_api_id' => $settings->lead_api_id,
								);
								$inserts = $this->db->insert("leads", $insert_lead_array);
								// print_r($insert_lead_array);
								// echo "Inserts==> ";
								// print_r($inserts);
								$lead_id = $this->db->insert_id();

								$normal_lead_count = 1;
							}


							if ($lead_id > 0) {
								// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
								// $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
								$assigned_to = $settings->lead_api_assigned_to; // Ver
								// $assigned_to = 678064846;
								$assigned_by = $lead_by_pos_user;

								$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
								$insert_array = array(
									'lead_id' => $lead_id,
									'assigned_by' => $assigned_by,
									'assigned_to' => $assigned_to,
									'assigned_on' => date('Y-m-d H:i:s')
								);
								// echo "<br>";
								// echo "<br> ";
								// print_r($insert_array);
								$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

								$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
								$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

								$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
								$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


								$log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
								// print_r($log_insert_array);
								$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

								// echo "Log: ".$log_insert."<br>";
								// echo "ERROR: ";
								// print_r($this->db->error());
								// exit();
								if ($insert > 0) {
									$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => $settings->lead_api_status), array('id' => $lead_id));
									$log_insert_array = array('action_id' => 421, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead Status updated by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => $settings->lead_api_status);
									$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

									if ($update) {
										//create action log
										$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
										$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
										// $settings->lead_api_status
										$receiver_email = $csa->email;
										$receiver_name =  $csa->first_name;
										$sender_email = $coordinator->email;
										$sender_name =  $coordinator->first_name;

										$subject = "CRM ALERT";
										$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
										$email_array = array(
											'email' => $receiver_email,
											'subject' => $subject,
											'template' => 'mails/template',
											'from_name' => "CRM ALERT",
											'message' => $message,
											'branch_id' => $branch_id,
										);
										$send_mail = send_template_email($email_array);
										log_message('error', $send_mail);

										$postData = array(
											'lead_id' => $lead_id,
										);
							
										$ch = curl_init();
										curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
										curl_setopt($ch, CURLOPT_HTTPHEADER, [
											'Accept: application/json',
											'Content-Type: application/json'
										]);
										curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
										curl_setopt($ch, CURLOPT_HEADER, false); 
										curl_setopt($ch, CURLOPT_POST, true);  
										curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
										curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
										$response = curl_exec($ch);
										if(!empty($response))
											$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

										$this->response(["status" => true, "lead_id" => $lead_id, "message" => 'Lead has been created and assigned successfully!'], 200);
									} else {
										$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
										// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

										$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
									}
								} else {
									$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
								}


								$this->response(["status" => true, "message" => "Lead successfully created."], 200);
							} else {
								$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
							}

							// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);

						} else {
							$this->response(["status" => false, "message" => 'Unable to create package leads at this moment.Please try again with normal leads.', "exception" => $this->db->error()], 500);
						}
					} else {
						$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
					}
				} else {
					$this->response(["status" => false, "message" => validation_errors()], 400);
				}
			} else {
				// $this->response(["status"=> true,"message"=>'Unable to create leads at this moment.Please try again.'], 500);
				// print_r($this->post());
				// exit();


				$branch_id = 100;
				$lead_type = "normal";
				$lead_name = $this->post('lead_name');
				$lead_contact = $this->post('lead_contact');
				$lead_email = $this->post('lead_email');
				$lead_remarks = $this->post("service_name") . "<br><br>" . $this->post('remarks');
				$lead_by_pos_user = $settings->lead_api_created_by;
				$lead_by_post_user_name = "WEB API";



				$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
				$random_string = substr(str_shuffle($str_result), 0, 10);


				//check category exist
				$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
				if ($branch_exist == 0) {
					$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
				}

				$category_id = 111;
				$service = $this->post("service_name");
				$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

				if ($service_exist) {
					$service_id = $service_exist["id"];
				} else {
					$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
					$service_id = (int)$service_id;
					$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
				}
				// print_r($service_id);
				// exit();
				// echo "<br>================================<br>";
				//check category exist

				// $this->form_validation->set_rules('service_name', 'service_name', 'required');


				$lead_name = $this->post('lead_name');
				$lead_contact = $this->post('lead_contact');
				$lead_email = $this->post('lead_email');
				$lead_email = trim($lead_email);
				// $lead_remarks = $this->post('lead_remarks');

				$this->form_validation->set_rules('lead_name', 'lead_name', 'required');
				$this->form_validation->set_rules('lead_contact', 'lead_contact', 'required');
				// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');
				// $this->form_validation->set_rules('lead_remarks', 'lead_remarks', 'required');

				if ($this->form_validation->run() == TRUE) {
					$random_email_name = strtolower($random_string);
					$random_email = $random_email_name . '@ontimecustomer.com';
					$lead_email = ($lead_email == '') ? $random_email : $lead_email;
					//create or get customer
					//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

					$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
					$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

					$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

					if ($is_exist != 0) {
						$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
						// return $user_id;
					}

					if ($is_exist == 0) {
						$password = 'Welcome@123';
						$confirm_password = 'Welcome@123';
						$auth_level = '1';
						$referal_code = $random_string;
						$user_hashed_password = $this->authentication->hash_passwd($password);
						$user_data = [
							'auth_level' => $auth_level,
							'mobile' => $lead_contact,
							'referal_code' => $referal_code,
							'first_name' => $lead_name,
							'passwd' => $user_hashed_password,
							'email' => trim($lead_email),
							'confirm_password' => $user_hashed_password,
						];
						$user_data['user_id'] = $this->authentication_model->get_unused_id();
						$user_data['created_at'] = date('Y-m-d H:i:s');
						$user_data['otp'] = rand(1000, 9000);
						$user_data['email_otp'] = rand(1000, 9000);
						$user_data['banned'] = '0';
						$user_data['role_id'] = '4';
						$user_data['country'] = 'United Arab Emirates';
						$user_data['country_code'] = '+971';

						$insert = $this->mcommon->common_insert("lead_users", $user_data);

						$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
						//return $user_id;
						print_r($user_id);
					}

					if ($user_id != 0) {
						$uploaded_file_name = '';
						//process lead type
						if ($lead_type == 'normal') {

							$normal_lead_count = 0;
							//get the workflow for the service.
							$workflows = $this->leads_model->get_workflow_entries($service_id);

							$assigned_to = $settings->lead_api_assigned_to; //
							$assigned_by = $lead_by_pos_user;
							$update = 1;
							if ($update) {

								if ($user_id != 0) {
									$insert_lead_array = array(
										'customer_id' => $user_id,
										'branch_id' => $branch_id,
										'category_id' => $category_id,
										'service_id' => $service_id,
										'lead_created_by' => $assigned_by,
										'lead_added_on' => date('Y-m-d H:i:s'),
										'contactable_date' => date('Y-m-d H:i:s'),
										'lead_status' => $settings->lead_api_status,
										'package_id' => 0,
										'order_receipt' => 0,
										'remarks' => $lead_remarks,
										'is_assigned' => 0,
									);
									$insert_lead_id = $this->mcommon->common_insert('biz_leads', $insert_lead_array);

									$curl = curl_init();
									$req = "lead_name=" . $lead_name . "&lead_email=" . $lead_email . "&lead_contact=" . $lead_contact . "&lead_remarks=" . $lead_remarks . "&is_pos=1";
									curl_setopt_array($curl, array(
										CURLOPT_URL => "https://crm.ontimebiz.com/api/v1/lead/biz",
										CURLOPT_RETURNTRANSFER => true,
										CURLOPT_ENCODING => "",
										CURLOPT_MAXREDIRS => 10,
										CURLOPT_TIMEOUT => 30,
										CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
										CURLOPT_CUSTOMREQUEST => "POST",
										CURLOPT_POSTFIELDS => $req,
										CURLOPT_HTTPHEADER => array(
											"cache-control: no-cache",
											"content-type: application/x-www-form-urlencoded",
										),
									));

									$response = curl_exec($curl);
									log_message('debug', $req . "<br/>" . $response);
									$err = curl_error($curl);

									curl_close($curl);
									$res = json_decode($response);
									$this->mcommon->common_edit('biz_leads', array('is_assigned' => 1, 'biz_lead_id' => $res->lead_id, 'lead_status' => 602), array('id' => $insert_lead_id));


									$this->response(["status" => true, "lead_id" => $insert_lead_id, "message" => 'Lead has been created and assigned successfully!'], 200);
								} else {
									$this->response(["status" => false, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
								}
							} else {
								$this->response(["status" => false, "message" => 'Unable to assign lead at present.', "exception" => $this->db->error()], 500);
							}


							$this->response(["status" => true, "message" => "Lead successfully created."], 200);


							// 	// $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);


						} else {
							$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error()], 500);
						}
					} else {
						$this->response(["status" => false, "message" => validation_errors()], 400);
					}
				}
			}
		} else {
			$this->response(["status" => false, "message" => "Unable to create the lead. Please send valid service_id Or Contact CRM administrator"], 401);
		}
	}

	public function dldedupdate_post()
	{
		$lead_id = $this->input->post('lead_id');
		$status_id = $this->input->post('status_id');
		$remarks = $this->input->post('remarks');
		if ($lead_id != '' && $remarks != '' && $status_id != '') {
			$is_dld = $this->mcommon->specific_row_value('lead_status', array('id' => $status_id), 'is_dld_status');

			if ($is_dld) {
				$log_insert_array = array('action_id' => 421, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $remarks, 'action_by' => 2906815795, 'status_id' => $status_id);
				$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
				if ($insert_log > 0) {
					//update lead status and contactable date in lead table
					$update_lead_array = array('lead_status' => $status_id);
					$update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));

					$this->response(["status" => true, "message" => "Status successfully updated"], 200);
				} else {
					$this->response(["status" => false, "message" => "Something went wrong. Please check your data or Contact Support."], 500);
				}
			} else {
				$this->response(["status" => false, "message" => "Staus_id is not DLD Status ID. Please check or contact Support"], 401);
			}
		} else {
			$this->response(["status" => false, "message" => "`lead_id`,`status_id`,`remarks` are mandatory"], 422);
		}
	}


	public function generate_get()
	{
		$this->db->select("api_lead_settings.lead_api_id,api_lead_settings.lead_api_name,ontime_branches.branch_name,ontime_categories.category_name,users.first_name,api_lead_settings.lead_api_crm,lead_status.status_name,api_lead_settings.lead_api_desc,api_lead_settings.lead_api_created_at,api_lead_settings.lead_api_updated_at")->from("api_lead_settings");

		$this->db->join("ontime_branches", "ontime_branches.branch_code = api_lead_settings.lead_api_branch");
		$this->db->join("ontime_categories", "ontime_categories.category_id = api_lead_settings.lead_api_category_id");
		$this->db->join("users", "users.user_id = api_lead_settings.lead_api_assigned_to", "left");
		$this->db->join("lead_status", "lead_status.id = api_lead_settings.lead_api_status");
		/* select  from api_lead_settings
		join ontime_branches on ontime_branches.branch_code = api_lead_settings.lead_api_branch
		join ontime_categories on ontime_categories.category_id = api_lead_settings.lead_api_category_id
		left join users on users.user_id = api_lead_settings.lead_api_assigned_to
		join lead_status on lead_status.id = api_lead_settings.lead_api_status
		where api_lead_settings.status = 1 */
		$this->db->where("api_lead_settings.status", 1);
		$settings = $this->db->get()->result_array();
		$this->response(["data" => $settings], 200);
	}


	public function upload_post()
	{
		$lead_id = $this->post('lead_id');
		$attachment_name = $this->post('attachment_name');
		if (isset($_FILES['files']['name'])) {
			$config = array(
				'upload_path' => "../uploads/leads",
				'allowed_types' => "gif|jpg|png|jpeg|pdf",
				'file_name' => sha1(time())
			);
			$this->load->library('upload', $config);

			if ($this->upload->do_upload('files')) {
				$data = array('upload_data' => $this->upload->data());
				$path = $config['upload_path'] . '/' . $data['upload_data']['file_name'];

				$insert_image_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $data['upload_data']['file_name']);
				$insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_image_array);
				if ($insert_attachment > 0) {
					$this->response("Attachment uploaded successfully", 200);
				} else {
					$this->response("Unable to upload. Please contact support", 500);
				}
			} else {
				$error = array('error' => $this->upload->display_errors());
				$returndata = array('data' => $error, 'message' => 'attachment upload failed');
				$this->set_response($returndata, 200);
			}
		} else {
			$this->response('No file');
		}
	}


	/**
	 * [visa_post exclusively for ontimevisa.com website]
	 * @param [invoice_no]
	 * @return [lead_status]
	 */
	public function salesreturn_post()
	{
		try {
			$post_data = $this->input->post();

			if (!isset($post_data['lead_receipt'])) {
				return $this->response([
					'status' => false,
					'message' => 'Parameter `lead_receipt` is missing.'
				], 422);
			}

			$invoice_no = $post_data['lead_receipt'];
			$sub_leads_arr = $post_data['sub_leads'];
			$sub_lead_val = explode(',', $sub_leads_arr);

			$lead = $this->db
				->select('*')
				->from('leads')
				->where('order_receipt', $invoice_no)
				->get()
				->first_row();

			if (!$lead) {
				return $this->response([
					'status' => false,
					'message' => 'Invalid `lead_receipt`.'
				], 422);
			}

			if ($lead->lead_status == 626) {
				return $this->response([
					'status' => true,
					'message' => 'Lead already reopened.'
				], 200);
			}

			if ($invoice_no != '' && strpos($invoice_no, 'INV-') !== false) {
				$updated_lead_id = $lead->id;

				if ($lead->lead_parent_id == 0) {
					$update_lead_array = [
						'lead_status' => 626,
						'order_receipt' => null,
						'pos_GovtFees' => 0,
						'pos_TypeFee' => 0,
						'pos_Card_Amnt' => 0,
						'pos_Disc_Amnt' => 0,
						'pos_Tax_Amnt' => 0,
						'pos_Tot_Amt' => 0,
						'pos_Tot_Revn' => 0,
						'completed_by' => 178140614,
					];
					$this->mcommon->common_edit('leads', $update_lead_array, ['id' => $updated_lead_id]);
				} else {
					$parent_id = $lead->lead_parent_id;
					$update_lead_array = [
						'lead_status' => 626,
						'order_receipt' => null,
						'pos_GovtFees' => 0,
						'pos_TypeFee' => 0,
						'pos_Card_Amnt' => 0,
						'pos_Disc_Amnt' => 0,
						'pos_Tax_Amnt' => 0,
						'pos_Tot_Amt' => 0,
						'pos_Tot_Revn' => 0,
						'completed_by' => 178140614,
					];
					$this->mcommon->common_edit('leads', $update_lead_array, ['id' => $parent_id]);
				}


				foreach ($sub_lead_val as $sub_id) {
					$update_sub_lead_array = [
						'lead_status' => 626,
						'order_receipt' => null,
						'pos_GovtFees' => 0,
						'pos_TypeFee' => 0,
						'pos_Card_Amnt' => 0,
						'pos_Disc_Amnt' => 0,
						'pos_Tax_Amnt' => 0,
						'pos_Tot_Amt' => 0,
						'pos_Tot_Revn' => 0,
						'completed_by' => 178140614,
					];
					$this->mcommon->common_edit('leads', $update_sub_lead_array, ['id' => $sub_id, 'order_receipt' => $invoice_no]);
				}

				$log_insert_array = [
					'action_id' => 425,
					'lead_id' => $updated_lead_id,
					'action_on' => date('Y-m-d H:i:s'),
					// 'remarks' => $invoice_no . ' - Sales order returned & reopened lead <strong>via POS system</strong>',
					'remarks' => $invoice_no . ' - Sales order returned & reopened lead <strong>via POS system</strong> and ' . $sub_leads_arr . ' subleads reponed',
					'action_by' => 178140614,
					'status_id' => 626,
				];

				$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

				return $this->response([
					'status' => true,
					'lead_id' => $updated_lead_id,
					'message' => 'Sales return completed.',
				], 200);
			} else {
				return $this->response([
					'status' => false,
					'message' => 'Unable to complete sales return. Please try again.',
				], 500);
			}
		} catch (Exception $e) {
			return $this->response([
				'status' => false,
				'message' => 'Something went wrong.',
			]);
		}
	}

	/**
	 * [visa_post exclusively for ontimevisa.com website]
	 * @param [type] $[name] [<description>]
	 * @return [lead_type] [description]
	 */
	public function visa_post()
	{

		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$random_string = substr(str_shuffle($str_result), 0, 10);
		$branch_id = '18';
		$lead_type = $this->post('lead_type'); //visa or uae_visa
		$lead_name = $this->post('customer_name');
		$lead_country = $this->post('country');
		$lead_country_code = $this->post('country_code');
		$lead_contact = $this->post('customer_mobile');
		$lead_email = $this->post('customer_email');
		$lead_remarks = $this->post('description');
		$lead_email = trim($lead_email);

		if (strtolower($lead_type) == 'visa') {
			$category_id = '100';
			$service_id = '1001';
		}

		if (strtolower($lead_type) == 'uae_visa') {
			$category_id = '100';
			$service_id = '1002';
		}

		//check category exist
		$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
		if ($branch_exist == 0) {
			$this->response(array("status" => 'error', "result" => "Branch doesn't exist. Update branch first to create the lead"), 404);
		}


		//check category exist
		$cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
		if ($cateogry_exist == 0) {
			$this->response(array("status" => 'error', "result" => "Category doesn't exist. Provide lead type to create the lead"), 404);
		}



		$service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
		if ($service_exist == 0) {
			$this->response(array("status" => 'error', "result" => "Service is not mapped to the category or  doesn't exist. Provide lead type to create the lead"), 404);
		}


		$this->form_validation->set_rules('country_code', 'Country phone code', 'required');
		$this->form_validation->set_rules('country', 'Country', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$this->form_validation->set_rules('customer_mobile', 'Contact Number', 'required');
		$this->form_validation->set_rules('customer_email', 'Email Address', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('lead_type', 'Lead Type', 'required');


		if ($this->form_validation->run() == TRUE) {


			$check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
			$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

			$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

			if ($is_exist != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
				return $user_id;
			}

			if ($is_exist == 0) {
				$password = 'Welcome@123';
				$confirm_password = 'Welcome@123';
				$auth_level = '1';
				$referal_code = $random_string;
				$user_hashed_password = $this->authentication->hash_passwd($password);
				$user_data = [
					'auth_level' => $auth_level,
					'mobile' => $lead_contact,
					'referal_code' => $referal_code,
					'first_name' => $lead_name,
					'passwd' => $user_hashed_password,
					'email' => trim($lead_email),
					'confirm_password' => $user_hashed_password,
				];
				$user_data['user_id'] = $this->authentication_model->get_unused_id();
				$user_data['created_at'] = date('Y-m-d H:i:s');
				$user_data['otp'] = rand(1000, 9000);
				$user_data['email_otp'] = rand(1000, 9000);
				$user_data['banned'] = '0';
				$user_data['role_id'] = '4';
				$user_data['country'] = $lead_country;
				$user_data['country_code'] = $lead_country_code;

				$insert = $this->mcommon->common_insert("lead_users", $user_data);

				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
				//return $user_id;
			}

			if ($user_id != 0) {
				$normal_lead_count = 0;
				$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

				$insert_lead_array = array(
					'customer_id' => $user_id,
					'branch_id' => $branch_id,
					'category_id' => $category_id,
					'service_id' => $service_id,
					'lead_created_by' => 178140614,
					'lead_added_on' => date('Y-m-d H:i:s'),
					'contactable_date' => date('Y-m-d H:i:s'),
					'lead_status' => 301,
					'package_id' => 0,
					'order_receipt' => 0,
					'remarks' => $lead_remarks,
					'is_assigned' => 0,
					'lead_by_pos_user' => '',
					'created_group_id' => $created_group_id
				);
				$insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
				$normal_lead_count = 1;

				if ($normal_lead_count > 0) {
					//create action log
					$log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created from <strong>OnTime Visa website</strong>', 'action_by' => 178140614, 'status_id' => 301);
					$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
					$this->response(array("status" => "success", "result" => 'Lead has been created.'), 200);
				} else {
					$this->response(array("status" => "error", "result" => 'Unable to create leads at this moment.Please try again.'), 500);
				}
			} else {
				$this->response(array("status" => "error", "result" => 'Unable to create leads at this moment.Please try again.'), 500);
			}
		} else {
			$this->response(array("status" => "error", "result" => validation_errors()), 400);
		}
	}

	function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

	public function usersummary_get()
	{
		if (isset($_GET["emp_id"])) {
			$emp_id = $_GET["emp_id"];
			$employee = $this->db->select("users.first_name,users.last_name,users.employee_id")->where("employee_id=", $emp_id)->from("users")->get()->result_array();
			// print_r($employee);
			// exit();
			// if($_GET[""])
			if ($employee) {
				if ($_GET["from"]) {
					if (!$this->validateDate($_GET["from"])) {
						$this->response(["status" => false, "message" => ["Please give valid date in 'yyyy-mm-dd' format for `from` parameter"]]);
					}
				}
				if ($_GET["to"]) {
					if (!$this->validateDate($_GET["to"])) {
						$this->response(["status" => false, "message" => ["Please give valid date in 'yyyy-mm-dd' format for `to` parameter"]]);
					}
				}

				$created = $this->db->select("sum(IF(req_type = 'Lead',1,0)) as leads,sum(IF(req_type = 'Cross-Sale',1,0)) as cross_sale,sum(IF(req_type = 'Transaction',1,0)) as transaction")->from("all_lead_completed_report")->where("creator_emp_id", $emp_id);

				if ($_GET["from"]) {
					$created = $created->where("date(created_time) >= ", $_GET["from"]);
				}
				if ($_GET["to"]) {
					$created = $created->where("date(created_time) <= ", $_GET["to"]);
				}
				$created = $created->get()->result_array();

				$completed = $this->db->select("sum(IF(req_type = 'Lead',1,0)) as leads,sum(IF(req_type = 'Cross-Sale',1,0)) as cross_sale,sum(IF(req_type = 'Transaction',1,0)) as transaction")->from("all_lead_completed_report")->where("completed_emp_id", $emp_id);
				if ($_GET["from"]) {
					$completed = $completed->where("date(completed_time) >= ", $_GET["from"]);
				}
				if ($_GET["to"]) {
					$completed = $completed->where("date(completed_time) <= ", $_GET["to"]);
				}
				$completed = $completed->get()->result_array();
				$this->response(["status" => true, "employee" => $employee[0], "created" => $created, "completed" => $completed]);
			} else {
				$this->response(["status" => false, "message" => ["Employee not found in OnTime Group CRM"]]);
			}
		} else {
			$this->response(["status" => false, "message" => ["'emp_id' is missing", "'from' is optional (yyyy-mm-dd)", "'to' is optional (yyyy-mm-dd)"]]);
		}
	}

	public function empsummary_get()
	{
		if (isset($_GET["emp_id"])) {
			$emp_id = $_GET["emp_id"];
			$employee = $this->db->select("users.first_name,users.last_name,users.employee_id")->where("employee_id=", $emp_id)->from("users")->get()->result_array();
			// print_r($employee);
			// exit();
			// if($_GET[""])
			if ($employee) {
				if ($_GET["from"]) {
					if (!$this->validateDate($_GET["from"])) {
						$this->response(["status" => false, "message" => ["Please give valid date in 'yyyy-mm-dd' format for `from` parameter"]]);
					}
				}
				if ($_GET["to"]) {
					if (!$this->validateDate($_GET["to"])) {
						$this->response(["status" => false, "message" => ["Please give valid date in 'yyyy-mm-dd' format for `to` parameter"]]);
					}
				}

				$created = $this->db->select("sum(IF(req_type = 'Lead',1,0)) as leads,sum(IF(req_type = 'Cross-Sale',1,0)) as cross_sale,sum(IF(req_type = 'Transaction',1,0)) as transaction")->from("employee_summary")->where("creator_emp_id", $emp_id)->where("lead_parent_id", 0);

				if ($_GET["from"]) {
					$created = $created->where("date(created_time) >= ", $_GET["from"]);
				}
				if ($_GET["to"]) {
					$created = $created->where("date(created_time) <= ", $_GET["to"]);
				}
				$created = $created->get()->result_array();

				$completed = $this->db->select("sum(IF(req_type = 'Lead',1,0)) as leads,sum(IF(req_type = 'Cross-Sale',1,0)) as cross_sale,sum(IF(req_type = 'Transaction',1,0)) as transaction")->from("employee_summary")->where("completed_by_emp_id", $emp_id)->where("status", "Completed");
				if ($_GET["from"]) {
					$completed = $completed->where("date(completed_time) >= ", $_GET["from"]);
				}
				if ($_GET["to"]) {
					$completed = $completed->where("date(completed_time) <= ", $_GET["to"]);
				}
				$completed = $completed->get()->result_array();
				$this->response(["status" => true, "employee" => $employee[0], "created" => $created, "completed" => $completed]);
			} else {
				$this->response(["status" => false, "message" => ["Employee not found in OnTime Group CRM"]]);
			}
		} else {
			$this->response(["status" => false, "message" => ["'emp_id' is missing", "'from' is optional (yyyy-mm-dd)", "'to' is optional (yyyy-mm-dd)"]]);
		}
	}
	public function details_get()
	{
		if (isset($_GET["order_ref"])) {
			$ref = end(explode("-", $_GET["order_ref"]));
			$lead_id = preg_replace("/[^0-9]/", "", $ref);
			$lead_det = $this->leads_model->lead_details($lead_id);

			if (!empty($lead_det)) {

				$lead["lead_id"] = $lead_det["id"];
				$lead["customer_name"] = $lead_det["customer_name"];
				$lead["customer_mobile"] = $lead_det["customer_mobile"];
				$lead["customer_email"] = $lead_det["customer_email"];
				$lead["branch_id"] = $lead_det["branch_id"];
				$lead["branch_name"] = $lead_det["branch_name"];
				$lead["category_code"] = $lead_det["category_code"];
				$lead["category_name"] = $lead_det["category_name"];
				$lead["service_name"] = $lead_det["service_name"];
				$lead["status_id"] = $lead_det["lead_status"];
				$lead["current_status"] = $lead_det["current_status"];
				$lead["govt_fee"] = $lead_det["govt_fee"];
				$lead["typing_fee"] = $lead_det["typing_fee"];
				$lead["typing_fee_no_vat"] = sprintf('%.2f', ((float)$lead["typing_fee"] / 1.05));
				$lead["card_amount"] = $lead_det["card_amount"];
				$lead["additional_govt_fee"] = $lead_det["additional_govt_fee"];
				$lead["remarks"] = $lead_det["remarks"];
				$lead["is_direct_invoice"] = $lead_det["is_direct_invoice"];
				$lead["is_sublead"] = $lead_det["is_sublead"];
				$lead["lead_parent_id"] = $lead_det["lead_parent_id"];
				$lead["total_no_subleads"] = $lead_det["_total_no_subleads"];
				$lead["no_of_open_subleads"] = $lead_det["no_of_open_subleads"];
				$lead["no_of_closed_subleads"] = $lead_det["no_of_closed_subleads"];
				$lead["pos_GovtFees"] = $lead_det["pos_GovtFees"];
				$lead["pos_TypeFee"] = $lead_det["pos_TypeFee"];
				$lead["pos_Card_Amnt"] = $lead_det["pos_Card_Amnt"];
				$lead["pos_Disc_Amnt"] = $lead_det["pos_Disc_Amnt"];
				$lead["pos_Tax_Amnt"] = $lead_det["pos_Tax_Amnt"];
				$lead["pos_Tot_Amt"] = $lead_det["pos_Tot_Amt"];
				$lead["pos_Tot_Revn"] = $lead_det["pos_Tot_Revn"];
				$lead["pos_username"] = $lead_det["pos_username"];
				$lead["msd_key"] = $lead_det["msd_key"];
				$lead["is_pos_typing_fee"] = $lead_det["is_pos_typing_fee"];
				$lead["otg_order_id"] = $lead_det["otg_order_id"];








				$lead["open_subleads"] = [];
			
				$childs = $this->db->select("*")->from("leads")->where("lead_parent_id", $lead_det["id"])->where("lead_status !=", 305)->get()->result_array();  
			
				if (count($childs) > 0) {
					foreach ($childs as $sublead) {
						$sl = $this->leads_model->lead_details($sublead["id"]);
						$sub_lead = [];
						$sub_lead["lead_id"] = $sl["id"];
						$sub_lead["customer_name"] = $sl["customer_name"];
						$sub_lead["customer_country_code"] = $sl["customer_country_code"];
						$sub_lead["customer_mobile"] = $sl["customer_mobile"];
						$sub_lead["customer_email"] = $sl["customer_email"];
						$sub_lead["branch_name"] = $sl["branch_name"];
						$sub_lead["category_code"] = $sl["category_code"];
						$sub_lead["category_name"] = $sl["category_name"];
						$sub_lead["service_name"] = $sl["service_name"];
						$sub_lead["current_status"] = $sl["current_status"];
						$sub_lead["total_no_subleads"] = $sl["total_no_subleads"];
						$sub_lead["no_of_open_subleads"] = $sl["no_of_open_subleads"];
						$sub_lead["no_of_closed_subleads"] = $sl["no_of_closed_subleads"];
						$sub_lead["govt_fee"] = $sl["govt_fee"];
						$sub_lead["typing_fee"] = $sl["typing_fee"];
						$sub_lead["typing_fee_no_vat"] = sprintf('%.2f', ((float)$sl["typing_fee"] / 1.05));
						$sub_lead["card_amount"] = $sl["card_amount"];
						$sub_lead["additional_govt_fee"] = $sl["additional_govt_fee"];
						$sub_lead["remarks"] = $sl["remarks"];
						$sub_lead["is_direct_invoice"] = $sl["is_direct_invoice"];


						$sub_lead["pos_GovtFees"] = $sl["pos_GovtFees"];
						$sub_lead["pos_TypeFee"] = $sl["pos_TypeFee"];
						$sub_lead["pos_Card_Amnt"] = $sl["pos_Card_Amnt"];
						$sub_lead["pos_Disc_Amnt"] = $sl["pos_Disc_Amnt"];
						$sub_lead["pos_Tax_Amnt"] = $sl["pos_Tax_Amnt"];
						$sub_lead["pos_Tot_Amt"] = $sl["pos_Tot_Amt"];
						$sub_lead["pos_Tot_Revn"] = $sl["pos_Tot_Revn"];
						$sub_lead["pos_username"] = $sl["pos_username"];
						$sub_lead["msd_key"] = $sl["msd_key"];
						$sub_lead["is_pos_typing_fee"] = $sl["is_pos_typing_fee"];
						$sub_lead["tot_fee"] = $sl["gov_service_amount"];
						array_push($lead["open_subleads"], $sub_lead);
					}
				}

				 $this->response(["status" => true, "data" => $lead ], 200);
			} else {
				$this->response(["status" => false, "message" => 'order_ref incorrect. Please contact system administrator.'], 200);
			}
		} else {
			$this->response(["status" => false, "message" => 'order_ref parameter missing'], 400);
		}
	}



	public function leadpack_get()
	{
		if (isset($_GET["lead_id"])) {
			$lead_id = $_GET["lead_id"];
			$lead_det = $this->leads_model->lead_details($lead_id);

			if (!empty($lead_det)) {

				$lead["lead"] = $lead_det;

				$lead["subleads"] = [];
				$childs = $this->db->select("*")->from("leads")->where("lead_parent_id", $lead_det["id"])->get()->result_array();
				if (count($childs) > 0) {
					foreach ($childs as $sublead) {
						$sl = $this->leads_model->lead_details($sublead["id"]);
						array_push($lead["subleads"], $sl);
					}
				}

				$this->response(["status" => true, "data" => $lead], 200);
			} else {
				$this->response(["status" => false, "message" => 'order_ref incorrect. Please contact system administrator.'], 412);
			}
		} else {
			$this->response(["status" => false, "message" => 'order_ref parameter missing'], 400);
		}
	}

	public function zohonotify_get()
	{
		$subject = "Lead Assigned - Some Zoho Leads are assigned to you !";
		$message = "Dear Mandeep,<br /><br />Some Zoho Lead(s) are has been assigned to you by <strong> ZOHO</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/manage .";
		$email_array = array(
			//'email' => "huida@ontimebiz.com",
			'email' => "mandeep.s@ontimegroup.com",
			'subject' => $subject,
			'template' => 'mails/template',
			'from_name' => "CRM - Zoho Lead Creation ALERT",
			'message' => $message,
		);
		$send_mail = send_template_email($email_array);



		$subject = "Lead Assigned - Some Zoho Leads are assigned to you !";
		$message = "Dear Mandeep,<br /><br />Some Zoho Lead(s) are has been assigned to you by <strong> ZOHO</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/manage .";
		$email_array = array(
			//'email' => "huida@ontimebiz.com",
			'email' => "jothish.s@egovllc.com",
			'subject' => $subject,
			'template' => 'mails/template',
			'from_name' => "CRM - Zoho Lead Creation ALERT",
			'message' => $message,
		);
		$send_mail = send_template_email($email_array);

		$subject = "Lead Assigned - Some Zoho Leads are assigned to you !";
		$message = "Dear Mandeep,<br /><br />Some Zoho Lead(s) are has been assigned to you by <strong> ZOHO</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/manage .";
		$email_array = array(
			//'email' => "huida@ontimebiz.com",
			'email' => "mail2freelancc@gmail.com",
			'subject' => $subject,
			'template' => 'mails/template',
			'from_name' => "CRM - Zoho Lead Creation ALERT",
			'message' => $message,
		);
		$send_mail = send_template_email($email_array);

		echo "true";
		log_message('debug', "Zoho Notification: " . $send_mail);
	}

	public function zohobizlead_post()
	{
		$lead_data = json_decode($this->post("lead_data"));

		$service_id = 1009;
		$branch_id = 128;

		$lead_name = $lead_data->first_name . " " . $lead_data->last_name;
		$lead_contact = $lead_data->lead_contact;
		$lead_email = $lead_data->lead_email;
		$lead_remarks = $lead_data->lead_remarks;
		$lead_by_pos_user = 178140614; //Zoho CRM

		$lead_owner =  $this->mcommon->specific_row_value('users', array('email' => $lead_data->lead_created_by), 'user_id');

		$lead_by_post_user_name = "Zoho CRM";
		$country = $lead_data->lead_country;

		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$random_string = substr(str_shuffle($str_result), 0, 10);

		//check category exist
		$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
		if ($branch_exist == 0) {
			$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
		}

		$category_id = 109;

		// $this->form_validation->set_rules('service_name', 'service_name', 'required');

		// $this->form_validation->set_rules('lead_country', 'lead_country', 'required');
		// $this->form_validation->set_rules('lead_email', 'lead_email', 'required');

		// if ($this->form_validation->run() == true) {
		$random_email_name = strtolower($random_string);
		$random_email = $random_email_name . '@ontimecustomer.com';
		$lead_email = ($lead_email == '') ? $random_email : $lead_email;
		$lead_email = trim($lead_email);
		//create or get customer
		//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

		// $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
		$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

		if ($check_email_exists != 0) {
			$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');

			//return $user_id;
		}

		// if ($check_mobile_exists != 0) {
		//     $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact), 'user_id');
		//     //return $user_id;
		// }

		if ($check_email_exists == 0) {
			$password = 'Welcome@123';
			$confirm_password = 'Welcome@123';
			$auth_level = '1';
			$referal_code = $random_string;
			$user_hashed_password = $this->authentication->hash_passwd($password);
			$user_data = [
				'auth_level' => $auth_level,
				'mobile' => $lead_contact,
				'referal_code' => $referal_code,
				'country' => $country,
				'first_name' => $lead_name,
				'passwd' => $user_hashed_password,
				'email' => trim($lead_email),
				'confirm_password' => $user_hashed_password,
			];
			$user_data['user_id'] = $this->authentication_model->get_unused_id();
			$user_data['created_at'] = date('Y-m-d H:i:s');
			$user_data['otp'] = rand(1000, 9000);
			$user_data['email_otp'] = rand(1000, 9000);
			$user_data['banned'] = '0';
			$user_data['role_id'] = '4';
			$user_data['country'] = 'United Arab Emirates';
			$user_data['country_code'] = '+971';

			$insert = $this->mcommon->common_insert("lead_users", $user_data);

			$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
			//return $user_id;
		}

		if ($user_id != 0) {
			$uploaded_file_name = '';
			$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_owner, 'is_primary_group_id' => 1), 'group_id');
			//process lead type
			$insert_lead_array = array(
				'customer_id' => $user_id,
				'branch_id' => $branch_id,
				'category_id' => $category_id,
				'service_id' => (int) $service_id,
				'lead_created_by' => $lead_owner,
				'lead_added_on' => date('Y-m-d H:i:s'),
				'contactable_date' => date('Y-m-d H:i:s'),
				'lead_status' => 607,
				'package_id' => 0,
				'order_receipt' => 0,
				'remarks' => $lead_remarks,
				'is_assigned' => 1,
				'lead_source' => $lead_data->lead_source,
				'lead_zoho_id' => $lead_data->lead_zoho_id,
				'lead_zoho_status' => $lead_data->lead_zoho_status,
				'created_group_id' => $created_group_id,
			);
			$inserts = $this->db->insert("leads", $insert_lead_array);
			// echo $this->db->last_query();
			// echo "Inserts==> ";
			// print_r($inserts);
			$lead_id = $this->db->insert_id();


			if ($lead_id > 0) {

				//$assigned_to = 503941962;
				$assigned_to = 632746755;
				$assigned_by = $lead_by_pos_user;

				$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
				$insert_array = array(
					'lead_id' => $lead_id,
					'assigned_by' => $assigned_by,
					'assigned_to' => $assigned_to,
					'assigned_on' => date('Y-m-d H:i:s'),
				);
				// echo "<br>";
				// echo "<br> ";
				// print_r($insert_array);
				$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

				$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
				$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

				$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
				$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

				$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
				// print_r($log_insert_array);
				$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

				// echo "Log: ".$log_insert."<br>";
				// echo "ERROR: ";
				// print_r($this->db->error());
				// exit();
				if ($insert > 0) {
					$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 602), array('id' => $lead_id));

					if ($update) {
						//create action log
						$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
						$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
						$id = $this->db->insert_id();
						$action_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $id), 'action_id');
						$action_name = $this->mcommon->specific_row_value('lead_actions', array('id' => $action_id), 'action_name');

						$lead_info["lead_id"] = $lead_id;
						$lead_info["assigned_to"] = $assigned_to;

						$lead_info["action_name"] = $action_name;
						//$this->zohoupdate($lead_info, $id);

						// $receiver_email = $csa->email;
						$receiver_email = $csa->email;
						$receiver_name = $csa->first_name;
						$sender_email = $coordinator->email;
						$sender_name = $coordinator->first_name;

						// $subject = "ONTIME BIZ ALERT";

						// $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimebiz.com/leads/lead/view/" . $lead_id . " .";
						// $email_array = array(
						//     'email' => $receiver_email,
						//     'subject' => $subject,
						//     'template' => 'mails/template',
						//     'from_name' => "ONTIME BIZ ALERT",
						//     'message' => $message,
						// );
						// $send_mail = send_template_email($email_array);
						// log_message('error', $send_mail);

						$this->response(["status" => true, "message" => 'Enquiry has been created and assigned successfully!', "lead_id" => $lead_id], 200);
					} else {
						$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
						// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

						$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present. Please try again later'], 500);
					}
				} else {
					$this->response(["status" => false, "message" => 'Unable to assign enquiry at present.', "exception" => $this->db->error()], 500);
				}

				$this->response(["status" => true, "message" => "Enquiry successfully created.", "lead_id" => $lead_id], 200);
			} else {
				$this->response(["status" => false, "message" => 'Unable to create enquirys at this moment.Please try again.', "exception" => $this->db->error()], 500);
			}

			//     // $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimebiz.com/uploads/leads/' . $uploaded_file_name);

		} else {
			$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error(), "data" => $user_data], 500);
		}
		// } else {
		//     $this->response(["status" => false, "message" => validation_errors()], 400);
		// }
	}

	public function zoholead_webchats_post()
	{
		$lead_data = json_decode($this->post("lead_data"));
		$lead_branch = $lead_data->lead_brand;
		$lead_department = $lead_data->lead_departments;
		$lead_Designation = $lead_data->lead_designation;
		$crm_lead_id = $lead_data->lead_ontimecrm_id;
		$zoho_lead_created_by = $lead_data->lead_zoho_createdby;
		// print_r ($lead_data); exit;

		$lead_zoho_created_by_id = '';
		if(!empty($zoho_lead_created_by) && $zoho_lead_created_by != 'Zoho Missed' 
			&& $zoho_lead_created_by != 'Zoho AOH' && $zoho_lead_created_by != 'Zoho Chats'
			&& $zoho_lead_created_by != "webchats@ontimegroup.com" &&  $zoho_lead_created_by != "sargunam.sn@ontimegroup.com"){
			$zoho_assigned_user_id = $this->mcommon->specific_row_value('users', array('email' => $zoho_lead_created_by), 'user_id');
			if(!empty($zoho_assigned_user_id)){
				$lead_zoho_created_by_id = $zoho_assigned_user_id;
			}
		}

		// Check Chat id Already Exist in OntimeCRM
		if(!empty($lead_data->lead_zoho_chat_id)){
			// $exist_crm_id = $this->mcommon->specific_row_value('leads', array('lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id), 'id');
			$exist_crm_id = $this->mcommon->specific_row_value('leads', array('lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id,
				'lead_added_on >=', date('Y-m-d H:i:s', strtotime('-24 hours'))), 'id');
			if(!empty($exist_crm_id) && $exist_crm_id != null){
				$lead_zoho_id = $this->mcommon->specific_row_value('leads', array('id' => $exist_crm_id), 'lead_zoho_id');
				$this->response(["status" => false, "message" => 'Enquiry already added with Exist Lead_zoho_id : '. $lead_zoho_id .' instead of New Lead_zoho_id : '. $lead_data->lead_zoho_id .' with the Zoho Chat Id : '. $lead_data->lead_zoho_chat_id .'!', "lead_id" => $exist_crm_id], 200);
			}
		}
		
		if(!empty($crm_lead_id) && $lead_branch == "Ontime"){
			$lead_from =  $this->mcommon->specific_row_value('leads', array('id' =>  $crm_lead_id), 'lead_from');

			// if($lead_from == 'Baraha Van' || $lead_from == 'GoldenCube' || $lead_from == 'OntimeGOV'){
				$update_lead_array = array(
					'lead_zoho_id' =>  $lead_data->lead_zoho_id,
					'lead_ad_campaign_id' => $lead_data->lead_ad_campaign_id ? $lead_data->lead_ad_campaign_id : '',
					'lead_zoho_created_by' => $lead_data->lead_zoho_createdby ? $lead_data->lead_zoho_createdby : '',
					'lead_zoho_channel' => $lead_data->lead_zoho_channel ? $lead_data->lead_zoho_channel : '',
					'lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id ? $lead_data->lead_zoho_chat_id : '',
					'lead_zoho_conversation_id' => $lead_data->lead_zoho_conversation_id ? $lead_data->lead_zoho_conversation_id : '',
					'lead_zoho_question' => $lead_data->lead_zoho_question ? $lead_data->lead_zoho_question : '',
					'lead_ad_campaign' => $lead_data->lead_zoho_campaign_source ? $lead_data->lead_zoho_campaign_source : '',
					'lead_zoho_created_by_id' => $lead_zoho_created_by_id,
				);
				$update = $this->mcommon->common_edit('leads', $update_lead_array, ['id' => $crm_lead_id]);
				if($update){
					$zoho_remarks - 'Zoho Updation :';
					if($lead_data->lead_zoho_id != null && $lead_data->lead_zoho_id != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Lead Id : '. $lead_data->lead_zoho_id;
					}
					if($lead_data->lead_ad_campaign_id != null && $lead_data->lead_ad_campaign_id != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Campaign Id : '. $lead_data->lead_ad_campaign_id;
					}
					if($lead_data->lead_zoho_createdby != null && $lead_data->lead_zoho_createdby != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Created By : '. $lead_data->lead_zoho_createdby;
					}
					if($lead_data->lead_zoho_channel != null && $lead_data->lead_zoho_channel != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Channel : '. $lead_data->lead_zoho_channel;
					}
					if($lead_data->lead_zoho_chat_id != null && $lead_data->lead_zoho_chat_id != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Chat Id : '. $lead_data->lead_zoho_chat_id;
					}
					if($lead_data->lead_zoho_conversation_id != null && $lead_data->lead_zoho_conversation_id != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Conversation Id : '. $lead_data->lead_zoho_conversation_id;
					}
					if($lead_data->lead_zoho_question != null && $lead_data->lead_zoho_question != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Question : '. $lead_data->lead_zoho_question;
					}
					if($lead_data->lead_zoho_campaign_source != null && $lead_data->lead_zoho_campaign_source != ''){
						$zoho_remarks = $zoho_remarks . '</b></br>Zoho Campaign Source : '. $lead_data->lead_zoho_campaign_source;
					}
					$zoho_log_insert_array = array('action_id' => 408, 'lead_id' => $crm_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $zoho_remarks, 'action_by' => 3053338403, 'status_id' => 304);
					$insert_log = $this->mcommon->common_insert('lead_action_log', $zoho_log_insert_array);

					$this->response(["status" => true, "message" => 'Enquiry has been updated successfully!', "lead_id" => $crm_lead_id], 200);
				} else {
					$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present while update. Please try again later'], 500);
				}
			/*	} else {
				$insert_lead_array = array(
					'lead_id' => $crm_lead_id,
					'lead_zoho_id' =>  $lead_data->lead_zoho_id,
					'lead_ad_campaign_id' => $lead_data->lead_ad_campaign_id ? $lead_data->lead_ad_campaign_id : '',
					'lead_zoho_created_by' => $lead_data->lead_zoho_createdby ? $lead_data->lead_zoho_createdby : '',
					'lead_zoho_channel' => $lead_data->lead_zoho_channel ? $lead_data->lead_zoho_channel : '',
					'lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id ? $lead_data->lead_zoho_chat_id : '',
					'lead_zoho_question' => $lead_data->lead_zoho_question ? $lead_data->lead_zoho_question : '',
					'lead_ad_campaign' => $lead_data->lead_zoho_campaign_source ? $lead_data->lead_zoho_campaign_source : '',
					'lead_zoho_created_by_id' => $lead_zoho_created_by_id,
					'lead_zoho_conversation_id' => $lead_data->lead_zoho_conversation_id ? $lead_data->lead_zoho_conversation_id : '',
					'lead_remarks' => $lead_data->lead_remarks ? $lead_data->lead_remarks : '',
				);

				$insert = $this->mcommon->common_insert("z_marketing_leads", $insert_lead_array);
				if($insert){
					$this->response(["status" => true, "message" => 'Enquiry has been created successfully in Marketing Leads!', "lead_id" => $crm_lead_id], 200);
				} else {
					$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present while update in Marketing Leads. Please try again later'], 500);
				}
			}	*/
		}

		$lead_owner = 3053338403;	// zohochats@ontimegroup.com
		$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
		$lead_by_post_user_name = "Zoho Chats";

		// if($lead_Designation== 'Zoho Missed'){
		// 	$lead_by_pos_user = 3183269420;	// zohosocialmissedchats@ontimegroup.com
		// 	$lead_by_post_user_name = "Zoho Missed";
		// 	$branch_id = 135;
		// 	$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
		// }

		if ($lead_branch === "OnTime Healthcare" || $lead_branch == "Ontime Healthcare") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 2251530784;	// zohomissedchatshealthcare@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["586833641"];
				// 586833641 - Pavan.s@doctorontime.ae
				$assigned_to_arr = [ "1184498292"];
				// 1721304764 - shahzar.k@ontimehealthcare.com
				// 2767523564 - Muhammad.f@ontimegroup.com
				// 1184498292 - Aya.r@ontimehealthcare.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 2865026562;	// zohochatsaohhealthcare@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["586833641"];
				$assigned_to_arr = [ "1184498292"];
				// 586833641 - Pavan.s@doctorontime.ae
				// $assigned_to_arr = ["1721304764", "2767523564", "1184498292"];
				// 1721304764 - shahzar.k@ontimehealthcare.com
				// 2767523564 - Muhammad.f@ontimegroup.com
				// 1184498292 - Aya.r@ontimehealthcare.com
			} else {
				$lead_owner = 1946530965;	// zohochatshealthcare@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				// $assigned_to_arr = ["586833641"];
				$assigned_to_arr = [ "1184498292"];
				// 586833641 - Pavan.s@doctorontime.ae
				// $assigned_to_arr = ['1184498292', '2767523564'];
				// 1184498292 - Aya.r@ontimehealthcare.com
				// 2767523564 - Muhammad.f@ontimegroup.com
			}
		}

		/*	if ($lead_branch === "Doctor OnTime" || $lead_branch == "Doctor Ontime") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 4100784304;	// zohomissedchatsdoctorontime@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = [ '3696977214'];
				// 3696977214 - Samantha.m@doctorontime.ae
				// 586833641 - Pavan.s@doctorontime.ae
				// 2767523564 - Muhammad.f@ontimegroup.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 403816897;	// zohochatsaohdoctorontime@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ['3696977214'];
				// 3696977214 - Samantha.m@doctorontime.ae
				// 586833641 - Pavan.s@doctorontime.ae
				// 2767523564 - Muhammad.f@ontimegroup.com
			} else {
				$lead_owner = 2427896404;	// zohochatsdoctorontime@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ['3696977214'];
				// 586833641 - Pavan.s@doctorontime.ae
				// 3696977214 - Samantha.m@doctorontime.ae
			}
		}	*/

		if ($lead_branch === "OnTime Trustee" || $lead_branch == "Trustee") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 1314175476;	// zohomissedchatsontimetrustee@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = [ "3390723150", "995962999"];
					// 3390723150 - charlene.d@ontimetrustee.com
				// 995962999 - hafssah.a@ontimetrustee.com
				// 3862636972 - Raphael.r@ontimetrustee.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 2051359793;	// zohochatsaohontimetrustee@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = [ "3390723150", "995962999"];
					// 3390723150 - charlene.d@ontimetrustee.com
				// 995962999 - hafssah.a@ontimetrustee.com
				// 3862636972 - Raphael.r@ontimetrustee.com
			} else {
				$lead_owner = 2262990662;	// zohochatsontimetrustee@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ['3390723150'];
					// 3390723150 - charlene.d@ontimetrustee.com
				// 3862636972 - Raphael.r@ontimetrustee.com
			}
		}

		if ($lead_branch === "Golden Cube" || $lead_branch === "GoldenCube") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 2270324950;	// zohomissedchatsgoldencube@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				// $branch_id = 136;
				$branch_id = 106;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["4213254981", "2411946200"];
				// 4213254981 - Salam.A@goldencube.ae
				// 2411946200 - Abdulaziz.a@goldencube.ae
				// 2845782377 - fatima@goldencube.ae
				// 883057153 - Fitoun.F@goldencube.ae
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 3773720372;	// zohochatsaohgoldencube@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				// $branch_id = 137;
				$branch_id = 106;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["4213254981", "2411946200"];
				// 4213254981 - Salam.A@goldencube.ae
				// 2411946200 - Abdulaziz.a@goldencube.ae
				// 2845782377 - fatima@goldencube.ae
				// 883057153 - Fitoun.F@goldencube.ae
			} else {
				$lead_owner = 2504392213;	// zohochatsgoldencube@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				// $branch_id = 134; 
				$branch_id = 106; 
				// $assigned_to_arr = ['883057153'];	// 883057153 - Fitoun.F@goldencube.ae
				$assigned_to_arr = ["4213254981", "2411946200"];
				// 4213254981 - Salam.A@goldencube.ae
				// 2411946200 - Abdulaziz.a@goldencube.ae
			}
		}

		if ($lead_branch === "Baraha" || $lead_branch === "Al Baraha Government Services") {
			if ($lead_data->lead_departments === "Baraha MOH") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 2510679666;	// zohomissedchatsbaraha@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["2826791041", "3431855600"];
					// 2826791041 - husna.a@baraha.ae
					// 3431855600 - hiba.s@ontimegov.com
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 3333914955;	// zohochatsaohbaraha@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["2826791041", "3431855600"];
					// 2826791041 - husna.a@baraha.ae
					// 3431855600 - hiba.s@ontimegov.com
				} else {
					$lead_owner = 4149970195;	// zohochatsbaraha@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134; // 113;
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ['1501975080', '3828530144'];
					// 1501975080 - alaa.m@baraha.ae
					// 3828530144 - Rabia.m@baraha.ae
				}
			}
			if ($lead_data->lead_departments === "Baraha Tasheel") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 2510679666;	// zohomissedchatsbaraha@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["1699333399", "270523625", "2826791041"];
					// 1699333399 - Leena.A@baraha.ae
					// 270523625 - amani.m@baraha.ae
					// 2826791041 - husna.a@baraha.ae
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 3333914955;	// zohochatsaohbaraha@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["1699333399", "270523625", "2826791041"];
					// 1699333399 - Leena.A@baraha.ae
					// 270523625 - amani.m@baraha.ae
					// 2826791041 - husna.a@baraha.ae
				} else {
					$lead_owner = 4149970195;	// zohochatsbaraha@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ['270523625', '1699333399'];
					// 270523625 - amani.m@baraha.ae
					// 1699333399 - Leena.A@baraha.ae
				}
			}
			if ($lead_data->lead_departments === "Baraha DED") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 2510679666;	// zohomissedchatsbaraha@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["1475122687", "1462653913", "2826791041"];
					// 1475122687 - aysha.m@baraha.ae
					// 1462653913 - eman.m@baraha.ae
					// 2826791041 - husna.a@baraha.ae
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 3333914955;	// zohochatsaohbaraha@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["1475122687", "1462653913", "2826791041"];
					// 1475122687 - aysha.m@baraha.ae
					// 1462653913 - eman.m@baraha.ae
					// 2826791041 - husna.a@baraha.ae
				} else {
					$lead_owner = 4149970195;	// zohochatsbaraha@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ['1475122687', '1462653913'];
					// 1475122687 - aysha.m@baraha.ae
					// 1462653913 - eman.m@baraha.ae
				}
			}
			if ($lead_data->lead_departments === "Baraha DLD") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 2510679666;	// zohomissedchatsbaraha@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 3333914955;	// zohochatsaohbaraha@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
				} else {
					$lead_owner = 4149970195;	// zohochatsbaraha@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ['4149970195'];
					// 4149970195 - zohochatsbaraha@ontimegroup.com
				}
			}
			if ($lead_data->lead_departments === "Baraha Amer") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 2510679666;	// zohomissedchatsbaraha@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["1699333399", "270523625", "2826791041"];
					// 1699333399 - Leena.A@baraha.ae
					// 270523625 - amani.m@baraha.ae
					// 2826791041 - husna.a@baraha.ae
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 3333914955;	// zohochatsaohbaraha@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					// $assigned_to_arr = ["2826791041"]; // husna.a@baraha.ae
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ["1699333399", "270523625", "2826791041"];
					// 1699333399 - Leena.A@baraha.ae
					// 270523625 - amani.m@baraha.ae
					// 2826791041 - husna.a@baraha.ae
				} else {
					$lead_owner = 4149970195;	// zohochatsbaraha@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
					// $assigned_to_arr = ['270523625', '1699333399'];
					// 270523625 - amani.m@baraha.ae
					// 1699333399 - Leena.A@baraha.ae
				}
			}
		}

		if ($lead_branch === "Business Lounge" || $lead_branch === "Baraha Business Lounge") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 2659921308;	// zohomissedchatsbusinesslounge@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 1760187357;	// zohochatsaohbusinesslounge@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
			} else {
				$lead_owner = 3920055395;	// zohochatsbusinesslounge@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ["2300187101"]; // ehab.a@baraha.ae
			}
		}

		if ($lead_branch === "Attestation") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 1605679272;	// zohomissedchatsattestation@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["3587668495", "2813192886"];
				// 3587668495 - Febinshad.M@ontimetranslation.ae
				// 2813192886 - team@ontimeattestation.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 1745574705;	// zohochatsaohattestation@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["3587668495", "2813192886"];
				// 3587668495 - Febinshad.M@ontimetranslation.ae
				// 2813192886 - team@ontimeattestation.com
			} else {
				$lead_owner = 3081550412;	// zohochatsattestation@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ['3587668495', '1378964183'];
				// 3587668495 - Febinshad.M@ontimetranslation.ae
				// 1378964183 - rovi.r@ontimetranslation.ae
			}
		}

		if ($lead_branch === "Translation") {
			if ($lead_Designation == 'Zoho Missed') {
				$$lead_owner = 1768123900;	// zohomissedchatstranslation@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["3587668495", "1378964183", "395713116"];
				// 3587668495 - Febinshad.M@ontimetranslation.ae
				//  1378964183 - rovi.r@ontimetranslation.ae
				// 395713116 - asma.b@ontimetranslation.ae
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 2169670035;	// zohochatsaohtranslation@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["3587668495", "1378964183", "395713116"];
				// 3587668495 - Febinshad.M@ontimetranslation.ae
				//  1378964183 - rovi.r@ontimetranslation.ae
				// 395713116 - asma.b@ontimetranslation.ae
			} else {
				$lead_owner = 2342780622;	// zohochatstranslation@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ['3587668495', '1378964183'];
				// 3587668495 - Febinshad.M@ontimetranslation.ae
				// 1378964183 - rovi.r@ontimetranslation.ae
			}
		}

		if ($lead_branch === "AlTasweyyat" || $lead_branch == "Al Tasweyaat" || $lead_branch == "Al Tasweyyat") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 4060002775;	// zohomissedchatsaltasweyyat@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["1464543532", "551550256"];
				// 1464543532 - Hossameldin.M@altasweyaat.com
				// 551550256 - Ali.s@altasweyaat.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 1660673241;	// zohochatsaohaltasweyyat@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["1464543532", "551550256"];
				// 1464543532 - Hossameldin.M@altasweyaat.com
				// 551550256 - Ali.s@altasweyaat.com
			} else {
				$lead_owner = 676831489;	// zohochatsaltasweyyat@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ['452236392', '1677539193'];
				// 452236392 - Mostafa.k@ontimegov.com
				// 1677539193 - Mohamed.as@ontimegov.com
			}
		}

		if ($lead_branch === "Notary Public") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 1612665960;	// zohomissedchatsnotarypublic@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["3334586807", "4110428549"];
				// 3334586807 - sahar.a@thenotarypublic.ae
				// 4110428549 - atef.a@ontimegov.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 699244772;	// zohochatsaohnotarypublic@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				$assigned_to_arr = ["3334586807", "4110428549"];
				// 3334586807 - sahar.a@thenotarypublic.ae
				// 4110428549 - atef.a@ontimegov.com
			} else {
				$lead_owner = 359855322;	// zohochatsnotarypublic@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ['4110428549'];
				// 4110428549 - atef.a@ontimegov.com
			}
		}

		if ($lead_branch === "BurDubai") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 4274595312;	// zohomissedchatsburdubai@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["4258687411", "1645400700", "2644571391", "4184195673", "468190806", "2854190512"];
				// 4258687411 - ahmed.h@ontimegov.com
				//  1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 468190806 - Frebel.m@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 3737630290;	// zohochatsaohburdubai@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["4258687411", "1645400700", "2644571391", "4184195673", "468190806", "2854190512"];
				// 4258687411 - ahmed.h@ontimegov.com
				//  1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 468190806 - Frebel.m@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
			} else {
				$lead_owner = 3789290748;	// zohochatsburdubai@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['468190806', '3862377651', '4258687411'];
				// 468190806 - Frebel.m@ontimegov.com
				// 3862377651 - Sakhiyani.a@ontimegov.com
				// 4258687411 - ahmed.h@ontimegov.com
			}
		}

		if ($lead_branch === "RTA") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 1465304282;	// zohomissedchatsrta@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 3285639483;	// zohochatsaohrta@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
			} else {
				$lead_owner = 274501700;	// zohochatsrta@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['2295122845', '3016117228', '2019994271'];
				// 2295122845 - riyaz.i@ontimegov.com
				// 3016117228 - katia.k@ontimegov.com
				// 2019994271 - wedad.m@ontimegov.com
			}
		}

		if ($lead_branch === "OnTime Gov") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 1465304282;	// zohomissedchatsrta@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				$assigned_to_arr = ["2446367346"]; 
				// 3865339251 - Hassan.ab@ontimegov.com
				// 2446367346 - Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 3285639483;	// zohochatsaohrta@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				$assigned_to_arr = ["2446367346"]; 
				// 3865339251 - Hassan.ab@ontimegov.com
				// 2446367346 - Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
			} else {
				$lead_owner = 274501700;	// zohochatsrta@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ["2446367346"]; 
				// 3865339251 - Hassan.ab@ontimegov.com
				// 2446367346 - Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['2295122845', '3016117228', '2019994271'];
				// 2295122845 - riyaz.i@ontimegov.com
				// 3016117228 - katia.k@ontimegov.com
				// 2019994271 - wedad.m@ontimegov.com
			}
		}

		if ($lead_branch === "MOH") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 368862656;	// zohomissedchatsmoh@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				$assigned_to_arr = ["3206568724"]; // jeramil.p@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 3939129301;	// zohochatsaohmoh@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				$assigned_to_arr = ["3206568724"]; // jeramil.p@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			} else {
				$lead_owner = 1186411751;	// zohochatsmoh@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ["3206568724"]; // jeramil.p@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['2295122845', '3016117228', '811882298'];
				// 3431855600 - hiba.s@ontimegov.com
				// 3206568724 - jeramil.p@ontimegov.com
				// 811882298 - Ibrahim.k@ontimehealthcare.com
			}
		}

		if ($lead_branch === "Al Manara") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 430014405;	// zohomissedchatsalmanara@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
				// 3573695398 - moustafa.a@ontimegov.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 2067867171;	// zohochatsaohalmanara@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
				// 3573695398 - moustafa.a@ontimegov.com
			} else {
				$lead_owner = 4021135980;	// zohochatsalmanara@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['1290872561', '3029758041', '1858966354'];
				// 1290872561 - dipeeka.j@ontimegov.com
				// 3029758041 - sonali.k@ontimegov.com
				// 1858966354 - noushad.k@ontimegov.com
			}
		}

		if ($lead_branch === "Mazaya Govermement Services") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 393815654;	// zohomissedchatsmazaya@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"]; 
				// 2446367346 - Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
				// 3573695398 - moustafa.a@ontimegov.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 2763984202;	// zohochatsaohmazaya@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"]; 
				// 2446367346 - Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
				// 3573695398 - moustafa.a@ontimegov.com
			} else {
				$lead_owner = 2183443295;	// zohochatsmazaya@ontimegroup.com
				if ($lead_data->lead_departments === "Mazaya DED") {
					// $lead_by_pos_user = 732310633; //Zoho Mazaya - zohomazaya@ontimegroup.com
					//	$lead_by_post_user_name = "Zoho Mazaya Governement Services";
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134; // 9;
					// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
					$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					// $assigned_to_arr = ['2893869692', '4269082088'];
					// 2893869692 - khawla.j@ontimegov.com
					// 4269082088 - suhaid.p@goldencube.ae
				}
				if ($lead_data->lead_departments === "Mazaya MOH") {
					// $lead_by_pos_user = 732310633; //Zoho Mazaya - zohomazaya@ontimegroup.com
					// $lead_by_post_user_name = "Zoho Mazaya Governement Services";
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134; // 113;
					// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
					$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					// $assigned_to_arr = ['268519743'];	// mohammed.s@ontimegov.com
				}
				if ($lead_data->lead_departments === "Mazaya Tasheel") {
					// $lead_by_pos_user = 732310633; //Zoho Mazaya - zohomazaya@ontimegroup.com
					//$lead_by_post_user_name = "Zoho Mazaya Governement Services";
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134; // 113;
					// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
					$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					// $assigned_to_arr = ['1038272360'];	// Rula.K@ontimetasheel.com
				}
				if ($lead_data->lead_departments === "Mazaya DLD") {
					// $lead_by_pos_user = 732310633; //Zoho Mazaya - zohomazaya@ontimegroup.com
					//	$lead_by_post_user_name = "Zoho Mazaya Governement Services";
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134; // 10;
					// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
					$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					// $assigned_to_arr = ['2634455476', '444200582'];
					// 2634455476 - Mohsen.M@ontimegov.com
					// 444200582 - Omar.h@ontimegov.com
				}
				if ($lead_data->lead_departments === "Mazay Amer" || $lead_data->lead_departments === "Mazaya Amer") {
					// $lead_by_pos_user = 732310633; //Zoho Mazaya - zohomazaya@ontimegroup.com
					//$lead_by_post_user_name = "Zoho Mazaya Governement Services";
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134; // 8;
					// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
					$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					// $assigned_to_arr = ['2315531738', '2268544902'];
					// 2315531738 - Mohammad.Sh@ontimegov.com
					// 2268544902 - Irene.b@ontimegov.com
				}
			}
		}

		if ($lead_branch === "Mazaya MOH") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 1314175476;	// zohomissedchatsontimetrustee@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
				// 3573695398 - moustafa.a@ontimegov.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 2051359793;	// zohochatsaohontimetrustee@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398"];
				// 1645400700 - Vanna.F@ontimegov.com
				// 2644571391 - Rehab.I@ontimegov.com
				// 4184195673 - Noraisa.a@ontimegov.com
				// 2854190512 - Maryam.y@ontimegov.com
				// 3573695398 - moustafa.a@ontimegov.com
			} else {
				// $lead_by_pos_user = 732310633; //Zoho Mazaya - zohomazaya@ontimegroup.com
				// $lead_by_post_user_name = "Zoho Mazaya Governement Services";
				$lead_owner = 2183443295;	// zohochatsmazaya@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; // 113;
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['268519743'];	// mohammed.s@ontimegov.com
			}
		}

		if ($lead_branch === "BusinessVenue Government Services") {
			if ($lead_data->lead_departments === "BV DED") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 1009942117;	// zohomissedchatsbv@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					//$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					// $assigned_to_arr = ["3517656483", "2954366799", "535863704", "1645400700", "1824936467", "2371658098", "2461533137", "2644571391", "1311906838", "2239356983", "1377003497", "211722233", "2630468162", "738269495", "4184195673", "3573695398", "3082369116", "2625821649"];
					// 3517656483 - Asma.M@ontimegov.com
					// 2954366799 - Majed.I@ontimegov.com
					// 535863704 - Nawaf.M@ontimegov.com
					// 1645400700 - Vanna.F@ontimegov.com
					// 1824936467 - Hussein.G@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 2461533137 - Usra.Y@ontimegov.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 1311906838 - Ihab.m@ontimegov.com
					// 2239356983 - Nahed.a@ontimegov.com
					// 1377003497 - Melody.t@ontimegov.com
					// 211722233 - Bassem.m@ontimegov.com
					// 2630468162 - mona.m@ontimegov.com
					// 738269495 - waad.r@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
					// 3082369116 - Nehal.a@Ontimegov.com
					// 2625821649 - asma.k@ontimegov.com
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 158384070;	// zohochatsaohbv@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["3517656483", "2954366799", "535863704", "1645400700", "1824936467", "2371658098", "2461533137", "2644571391", "1311906838", "2239356983", "1377003497", "211722233", "2630468162", "738269495", "4184195673", "3573695398", "3082369116", "2625821649"];
					// 3517656483 - Asma.M@ontimegov.com
					// 2954366799 - Majed.I@ontimegov.com
					// 535863704 - Nawaf.M@ontimegov.com
					// 1645400700 - Vanna.F@ontimegov.com
					// 1824936467 - Hussein.G@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 2461533137 - Usra.Y@ontimegov.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 1311906838 - Ihab.m@ontimegov.com
					// 2239356983 - Nahed.a@ontimegov.com
					// 1377003497 - Melody.t@ontimegov.com
					// 211722233 - Bassem.m@ontimegov.com
					// 2630468162 - mona.m@ontimegov.com
					// 738269495 - waad.r@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
					// 3082369116 - Nehal.a@Ontimegov.com
					// 2625821649 - asma.k@ontimegov.com
				} else {
					$lead_owner = 797821886;	// zohochatsbv@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					// $assigned_to_arr = ['797821886'];
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
				}
			} else if ($lead_data->lead_departments === "BV MOH") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 1009942117;	// zohomissedchatsbv@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398", "4182493274", "2371658098", "1320602942"];
					// 1645400700 - Vanna.F@ontimegov.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
					// 4182493274 - Basel.A@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 158384070;	// zohochatsaohbv@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398", "4182493274", "2371658098", "1320602942"];
					// 1645400700 - Vanna.F@ontimegov.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
					// 4182493274 - Basel.A@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
				} else {
					$lead_owner = 797821886;	// zohochatsbv@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					//$assigned_to_arr = ['797821886'];
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com

				}
			} else if ($lead_data->lead_departments === "BV Tasheel") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 1009942117;	// zohomissedchatsbv@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398", "4182493274", "2371658098", "1320602942"];
					// 1645400700 - Vanna.F@ontimegov.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
					// 4182493274 - Basel.A@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 158384070;	// zohochatsaohbv@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["1645400700", "2644571391", "4184195673", "2854190512", "3573695398", "4182493274", "2371658098", "1320602942"];
					// 1645400700 - Vanna.F@ontimegov.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
					// 4182493274 - Basel.A@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
				} else {
					$lead_owner = 797821886;	// zohochatsbv@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					//$assigned_to_arr = ['797821886'];
				}
			} else if ($lead_data->lead_departments === "BV DLD") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 1009942117;	// zohomissedchatsbv@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["4182493274", "1645400700", "2371658098", "1320602942", "2644571391", "4184195673", "2854190512", "3573695398"];
					// 4182493274 - Basel.A@ontimegov.com
					// 1645400700 - Vanna.F@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 158384070;	// zohochatsaohbv@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["4182493274", "1645400700", "2371658098", "1320602942", "2644571391", "4184195673", "2854190512", "3573695398"];
					// 4182493274 - Basel.A@ontimegov.com
					// 1645400700 - Vanna.F@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
				} else {
					$lead_owner = 797821886;	// zohochatsbv@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					//$assigned_to_arr = ['797821886'];
				}
			} else if ($lead_data->lead_departments === "BV Amer") {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 1009942117;	// zohomissedchatsbv@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["4182493274", "1645400700", "2371658098", "1320602942", "2644571391", "4184195673", "2854190512", "3573695398"];
					// 4182493274 - Basel.A@ontimegov.com
					// 1645400700 - Vanna.F@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 158384070;	// zohochatsaohbv@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
					//$assigned_to_arr = ["4182493274", "1645400700", "2371658098", "1320602942", "2644571391", "4184195673", "2854190512", "3573695398"];
					// 4182493274 - Basel.A@ontimegov.com
					// 1645400700 - Vanna.F@ontimegov.com
					// 2371658098 - Mohamed.sa@ontimegov.com
					// 1320602942 - Gharzaddeen.a@ontimegroup.com
					// 2644571391 - Rehab.I@ontimegov.com
					// 4184195673 - Noraisa.a@ontimegov.com
					// 2854190512 - Maryam.y@ontimegov.com
					// 3573695398 - moustafa.a@ontimegov.com
				} else {
					$lead_owner = 797821886;	// zohochatsbv@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
					//$assigned_to_arr = ['797821886'];
				}
			} else {
				if ($lead_Designation == 'Zoho Missed') {
					$lead_owner = 1009942117;	// zohomissedchatsbv@ontimegroup.com
					$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Missed";
					$branch_id = 136;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
				} else if ($lead_Designation == 'After Office Hours') {
					$lead_owner = 158384070;	// zohochatsaohbv@ontimegroup.com
					$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
					$lead_by_post_user_name = "Zoho AOH";
					$branch_id = 137;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
				} else {
					$lead_owner = 797821886;	// zohochatsbv@ontimegroup.com
					$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
					$lead_by_post_user_name = "Zoho Chats";
					$branch_id = 134;
					$assigned_to_arr = ["2446367346"]; // Franklin.v@ontimegov.com
				}
			}
		}

		if ($lead_branch === "OntimeGOV DLD Department") {
			if ($lead_Designation == 'Zoho Missed') {
				$lead_owner = 2992496368;	// zohomissedchatsontimegovdld@ontimegroup.com
				$lead_by_pos_user = 2832844267;	// zohomissedchats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Missed";
				$branch_id = 136;
				$assigned_to_arr = ["2992496368"]; // zohomissedchatsontimegovdld@ontimegroup.com
			} else if ($lead_Designation == 'After Office Hours') {
				$lead_owner = 28697335;	// zohochatsaohontimegovdld@ontimegroup.com
				$lead_by_pos_user = 636892613;	// zohochatsaoh@ontimegroup.com
				$lead_by_post_user_name = "Zoho AOH";
				$branch_id = 137;
				$assigned_to_arr = ["28697335"]; // zohochatsaohontimegovdld@ontimegroup.com
			} else {
				$lead_owner = 1817738597;	// zohochatsontimegovdld@ontimegroup.com
				$lead_by_pos_user = 3053338403;	// zohochats@ontimegroup.com
				$lead_by_post_user_name = "Zoho Chats";
				$branch_id = 134; 
				$assigned_to_arr = ["1817738597"]; // zohochatsontimegovdld@ontimegroup.com
			}
		}

		if($lead_zoho_created_by_id == '' || $lead_zoho_created_by_id == null || $lead_zoho_created_by_id == 0){
			$lead_zoho_created_by_id = $lead_owner;
		}

		// var_dump($lead_data); exit;
		$service_id = 1009;

		// $branch_id = 9;
		$lead_name =  trim($lead_data->first_name . " " .  $lead_data->last_name);
		$lead_contact =  $lead_data->lead_contact;
		$lead_countrycode = $lead_data->lead_countrycode;
		$lead_email =  $lead_data->lead_email;
		$lead_remarksData =  $lead_data->lead_remarks;
		$lead_remarks = !empty($lead_remarksData) ? $lead_remarksData : "";
		$lead_nationality = $lead_data->lead_nationality;

		// $lead_owner =  $this->mcommon->specific_row_value('users', array('email' =>  $lead_data->lead_created_by), 'user_id');
		// if(!$lead_owner){
		//     $lead_owner = 3206431770;   
		// }

		$country =  $lead_data->lead_country;

		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$random_string = substr(str_shuffle($str_result), 0, 10);

		//check category exist
		$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
		if ($branch_exist == 0) {
			$this->response(["status" => "false", "branch_id" => $branch_id, "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
		}

		$category_id = 109;

		if (true) {
			$random_email_name = strtolower($random_string);
			$random_email = $random_email_name . '@ontimecustomer.com';
			$lead_email = ($lead_email == '') ? $random_email : $lead_email;
			$lead_email = trim($lead_email);

			//create or get customer
			//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

			// $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
			/*$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

			if ($check_email_exists != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
				//return $user_id;
			}	*/

			// if ($check_mobile_exists != 0) {
			//     $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact), 'user_id');
			//     //return $user_id;
			// }

			$check_lead_user = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact,'email' => trim($lead_email)));

			if ($check_lead_user != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact,'email' => trim($lead_email)), 'user_id');
			}

			// if ($check_email_exists == 0) {
			if ($check_lead_user == 0) {
				$password = 'Welcome@123';
				$confirm_password = 'Welcome@123';
				$auth_level = '1';
				$referal_code = $random_string;
				$user_hashed_password = $this->authentication->hash_passwd($password);
				$user_data = [
					'auth_level' => $auth_level,
					'mobile' => $lead_contact,
					'referal_code' => $referal_code,
					'country' => $country,
					'first_name' => $lead_name,
					'last_name' => '',
					'language' => '',
					'customer_type' => 'individual',
					'device_id' => '',
					'passwd' => $user_hashed_password,
					'email' => trim($lead_email),
					'passwd' => $user_hashed_password,
					'confirm_password' => $user_hashed_password,
					'language' => 0,
					'profile_pic' => '',
				];
				$user_data['user_id'] = $this->authentication_model->get_unused_id();
				$user_data['created_at'] = date('Y-m-d H:i:s');
				$user_data['modified_at'] = date('Y-m-d H:i:s');
				$user_data['otp'] = rand(1000, 9000);
				$user_data['email_otp'] = rand(1000, 9000);
				$user_data['banned'] = '0';
				$user_data['role_id'] = '4';
				$user_data['country'] = ($country != NULL && trim($country) != '') ? $country : 'United Arab Emirates';
				$user_data['country_code'] = ($lead_countrycode != NULL && trim($lead_countrycode) != '') ? $lead_countrycode : "+971";
				$user_data['nationality'] = ($lead_nationality != NULL && trim($lead_nationality) != '') ? $lead_nationality : "";

				$insert = $this->mcommon->common_insert("lead_users", $user_data);
				// echo $this->db->last_query();
				// echo "Inserts==> ";
				// print_r($insert); exit;
				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => trim($lead_email), 'mobile' => $lead_contact), 'user_id');
				//return $user_id;
				// print_r($user_data); exit;
			}

			if ($user_id != 0) {
				$uploaded_file_name = '';
				//process lead type
				$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_owner, 'is_primary_group_id' => 1), 'group_id');
				$insert_lead_array = array(
					'customer_id' => $user_id,
					'branch_id' => $branch_id,
					'category_id' => $category_id,
					'service_id' => (int) $service_id,
					'lead_created_by' => $lead_owner,
					'lead_added_on' => date('Y-m-d H:i:s'),
					'contactable_date' => date('Y-m-d H:i:s'),
					'lead_status' => 602,
					'package_id' => 0,
					'order_receipt' => 0,
					'remarks' => $lead_remarks,
					'is_assigned' => 1,
					'lead_by_pos_user' => $lead_by_pos_user,
					'lead_by_post_user_name' => $lead_by_post_user_name,
					'lead_source' =>  $lead_data->lead_source,
					'lead_ad_campaign' =>  $lead_data->lead_ad_campaign ? $lead_data->lead_ad_campaign : '',
					'lead_ad_campaign_id' => $lead_data->lead_ad_campaign_id ? $lead_data->lead_ad_campaign_id : '',
					'lead_time_frame_to_start' =>  $lead_data->lead_time_frame_to_start ? $lead_data->lead_time_frame_to_start : '',
					'lead_zoho_id' =>  $lead_data->lead_zoho_id,
					'lead_form' =>  $lead_data->lead_form ? $lead_data->lead_form : '',
					'lead_reason_for_lost_mql' =>  $lead_data->lead_reason_for_lost_mql ? $lead_data->lead_reason_for_lost_mql : '',
					'lead_country' => $lead_data->lead_country,
					'lead_zoho_leadid' => $lead_data->lead_zoho_leadid,
					'lead_zoho_status' => $lead_data->lead_zoho_status,
					'lead_zoho_reason' => $lead_data->lead_zoho_reason,
					'lead_business_activity' => $lead_data->lead_business_activity,
					'lead_zoho_visa_type' => $lead_data->lead_visa_type,
					'lead_zoho_layout' => $lead_data->lead_layout_name,
					'lead_from' => $lead_by_post_user_name,
					'created_group_id' => $created_group_id,
					'lead_zoho_created_by' => $lead_data->lead_zoho_createdby ? $lead_data->lead_zoho_createdby : '',
					'lead_zoho_channel' => $lead_data->lead_zoho_channel ? $lead_data->lead_zoho_channel : '',
					'lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id ? $lead_data->lead_zoho_chat_id : '',
					'lead_zoho_question' => $lead_data->lead_zoho_question ? $lead_data->lead_zoho_question : '',
					'lead_zoho_created_by_id' => $lead_zoho_created_by_id,
					'lead_zoho_conversation_id' => $lead_data->lead_zoho_conversation_id ? $lead_data->lead_zoho_conversation_id : '',
					// 'lead_zoho_bank_statement' => $lead_data->lead_bank_statement,
				);
				$inserts = $this->db->insert("leads", $insert_lead_array);
				// echo $this->db->last_query();
				// echo "Inserts==> ";
				// print_r($inserts); exit;
				$lead_id = $this->db->insert_id();


				if ($lead_id > 0) {
					//$assigned_to = 503941962;
					// $assigned_to = 2819412677;	// zohoontimevisa@ontimecrm.com

					// Adil.S@ontimevisa.com  - 1342769445
					// maroua@ontimevisa.com  - 2085249465
					// abdullokh.i@ontimevisa.com  - 4045309987

					// $assigned_to_arr = ['1108567014', '4269082088'];
					$randomKey = array_rand($assigned_to_arr);
					$assigned_to = $assigned_to_arr[$randomKey];

					$assigned_by = $lead_by_pos_user;

					if ($lead_branch === "Golden Cube" || $lead_branch === "GoldenCube") {
						if(!empty($zoho_lead_created_by) && $zoho_lead_created_by != 'Zoho Missed' 
						&& $zoho_lead_created_by != 'Zoho AOH' && $zoho_lead_created_by != 'Zoho Chats'
						&& $zoho_lead_created_by != "webchats@ontimegroup.com" &&  $zoho_lead_created_by != "sargunam.sn@ontimegroup.com"){
							$assigned_user_id = $this->mcommon->specific_row_value('users', array('email' => $zoho_lead_created_by), 'user_id');
							if(!empty($assigned_user_id)){
								$assigned_to = $assigned_user_id;
							}
						}
					}

					$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
					$insert_array = array(
						'lead_id' => $lead_id,
						'assigned_by' => $assigned_by,
						'assigned_to' => $assigned_to,
						'assigned_on' => date('Y-m-d H:i:s'),
					);
					// echo "<br>";
					// echo "<br> ";
					// print_r($insert_array);
					$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

					$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

					$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
					$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

					$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
					// print_r($log_insert_array);
					$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

					if ($insert > 0) {
						$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 602), array('id' => $lead_id));

						if ($update) {
							//create action log
							$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
							$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							$id = $this->db->insert_id();
							$action_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $id), 'action_id');
							$action_name = $this->mcommon->specific_row_value('lead_actions', array('id' => $action_id), 'action_name');

							$lead_info["lead_id"] = $lead_id;
							$lead_info["assigned_to"] = $assigned_to;

							$lead_info["action_name"] = $action_name;

							// $receiver_email = $csa->email;
							$receiver_email = $csa->email;
							$receiver_name = $csa->first_name;
							$sender_email = $coordinator->email;
							$sender_name = $coordinator->first_name;

							

							$subject = "ONTIME CRM ALERT";

							$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";

							if($lead_branch === "Baraha" || $lead_branch === "Al Baraha Government Services"){

								$baraha_cc_usermail = [];
								array_push($baraha_cc_usermail, ["email" => "husna.a@baraha.ae", "name" => "Husna"]);	// 2826791041
								array_push($baraha_cc_usermail, ["email" => "Alaa.F@baraha.ae", "name" => "Alaa"]);	// 3325968771

								// $bcc_usermail = [];
								// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

								$email_array = array(
									'email' => $receiver_email,
									'cc' => $baraha_cc_usermail,
									// "bcc" => $bcc_usermail,
									'subject' => $subject,
									'template' => 'mails/template',
									'from_name' => "ONTIME CRM ALERT",
									'message' => $message,
								);
							} else {
								// $bcc_usermail = [];
								// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

								$email_array = array(
									'email' => $receiver_email,
									// "bcc" => $bcc_usermail,
									'subject' => $subject,
									'template' => 'mails/template',
									'from_name' => "ONTIME CRM ALERT",
									'message' => $message,
								);
							}

							$send_mail = send_template_email($email_array);
							log_message('error', $send_mail);

							$postData = array(
								'lead_id' => $lead_id,
							);
				
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
							curl_setopt($ch, CURLOPT_HTTPHEADER, [
								'Accept: application/json',
								'Content-Type: application/json'
							]);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
							curl_setopt($ch, CURLOPT_HEADER, false); 
							curl_setopt($ch, CURLOPT_POST, true);  
							curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
							$response = curl_exec($ch);
							if(!empty($response))
								$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

							curl_close($ch);
							if($lead_data->lead_zoho_conversation_id != ''){
								$zoho_conversation_id = $lead_data->lead_zoho_conversation_id;
								$zoho_conv_url = "https://salesiq.zoho.com/ontimegroup/allchats/" . $zoho_conversation_id;
								$zoho_conv_message = "<br /><a target='_blank' href=". $zoho_conv_url ." class='p-2 pl-4 pr-4 btn btn-primary'>Redirect Zoho Conversation</a>";
								$log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $zoho_conv_message, 'action_by' => $lead_by_pos_user, 'status_id' => 304);
								$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							}

							$this->response(["status" => true, "message" => 'Enquiry has been created and assigned successfully!', "lead_id" => $lead_id], 200);
						} else {
							$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
							$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present. Please try again later'], 500);
						}
					} else {
						$this->response(["status" => false, "message" => 'Unable to assign enquiry at present.', "exception" => $this->db->error()], 500);
					}
					$this->response(["status" => true, "message" => "Enquiry successfully created.", "lead_id" => $lead_id], 200);
				} else {
					$this->response(["status" => false, "message" => 'Unable to create enquirys at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}
			} else {
				$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error(), "data" => $user_data], 500);
			}
		} else {
			$this->response(["status" => false, "message" => validation_errors()], 400);
		}
	}

	public function zoholead_social_post()
	{
		$lead_data = json_decode($this->post("lead_data"));
		$lead_branch = $lead_data->lead_brand;
		$lead_layout = $lead_data->lead_layout_name;
		$lead_department = $lead_data->lead_departments;
		$lead_Designation = $lead_data->lead_designation;

		$crm_lead_id = $lead_data->lead_ontimecrm_id;
		// print_r ($lead_data); exit;

		// Check Chat id Already Exist in OntimeCRM
		if(!empty($lead_data->lead_zoho_chat_id)){
			// $exist_crm_id = $this->mcommon->specific_row_value('leads', array('lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id), 'id');
			$exist_crm_id = $this->mcommon->specific_row_value('leads', array('lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id,
				'lead_added_on >=', date('Y-m-d H:i:s', strtotime('-24 hours'))), 'id');
			if(!empty($exist_crm_id) && $exist_crm_id != null ){
				$lead_zoho_id = $this->mcommon->specific_row_value('leads', array('id' => $exist_crm_id), 'lead_zoho_id');
				$this->response(["status" => false, "message" => 'Enquiry already added with Exist Lead_zoho_id : '. $lead_zoho_id .' instead of New Lead_zoho_id : '. $lead_data->lead_zoho_id .' with the Zoho Chat Id : '. $lead_data->lead_zoho_chat_id .'!', "lead_id" => $exist_crm_id], 200);
			}
		}
		
		if(!empty($crm_lead_id)){
			$lead_from =  $this->mcommon->specific_row_value('leads', array('id' =>  $crm_lead_id), 'lead_from');

			// if($lead_from == 'Baraha Van' || $lead_from == 'GoldenCube' || $lead_from == 'OntimeGOV'){
				$update_lead_array = array(
					'lead_zoho_id' =>  $lead_data->lead_zoho_id,
					'lead_ad_campaign_id' => $lead_data->lead_ad_campaign_id ? $lead_data->lead_ad_campaign_id : '',
					'lead_zoho_created_by' => $lead_data->lead_zoho_createdby ? $lead_data->lead_zoho_createdby : '',
					'lead_zoho_channel' => $lead_data->lead_zoho_channel ? $lead_data->lead_zoho_channel : '',
					'lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id ? $lead_data->lead_zoho_chat_id : '',
					'lead_zoho_question' => $lead_data->lead_zoho_question ? $lead_data->lead_zoho_question : '',
					'lead_ad_campaign' => $lead_data->lead_zoho_campaign_source ? $lead_data->lead_zoho_campaign_source : '',
				);

				$update = $this->mcommon->common_edit('leads', $update_lead_array, ['id' => $crm_lead_id]);
				if($update){
					$this->response(["status" => true, "message" => 'Enquiry has been updated successfully!', "lead_id" => $crm_lead_id], 200);
				} else {
					$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present while update. Please try again later'], 500);
				}
			/*	} else {
				$insert_lead_array = array(
					'lead_id' => $crm_lead_id,
					'lead_zoho_id' =>  $lead_data->lead_zoho_id,
					'lead_ad_campaign_id' => $lead_data->lead_ad_campaign_id ? $lead_data->lead_ad_campaign_id : '',
					'lead_zoho_created_by' => $lead_data->lead_zoho_createdby ? $lead_data->lead_zoho_createdby : '',
					'lead_zoho_channel' => $lead_data->lead_zoho_channel ? $lead_data->lead_zoho_channel : '',
					'lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id ? $lead_data->lead_zoho_chat_id : '',
					'lead_zoho_question' => $lead_data->lead_zoho_question ? $lead_data->lead_zoho_question : '',
					'lead_ad_campaign' => $lead_data->lead_zoho_campaign_source ? $lead_data->lead_zoho_campaign_source : '',
					'lead_zoho_conversation_id' => $lead_data->lead_zoho_conversation_id ? $lead_data->lead_zoho_conversation_id : '',
					'lead_remarks' => $lead_data->lead_remarks ? $lead_data->lead_remarks : '',
				);

				$insert = $this->mcommon->common_insert("z_marketing_leads", $insert_lead_array);
				if($insert){
					$this->response(["status" => true, "message" => 'Enquiry has been created successfully!', "lead_id" => $crm_lead_id], 200);
				} else {
					$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present while update. Please try again later'], 500);
				}
			}	*/
		}

		$lead_owner = 1323107434;	// zohosocial@ontimegroup.com
		$lead_by_pos_user = 1323107434;	// zohosocial@ontimegroup.com
		$lead_by_post_user_name = "Zoho Social";

		if (
			$lead_branch == "OnTime Healthcare" || $lead_branch == "Ontime Healthcare"
			|| $lead_branch == "OnTime Healthcare Setup - DHA Consultancy" ||
			$lead_layout == "OnTime Healthcare" || $lead_layout == "Ontime Healthcare"
			|| $lead_layout == "OnTime Healthcare Setup - DHA Consultancy"
		) {
			$assigned_to_arr = [ "1184498292"];
			// $assigned_to_arr = [ "586833641"];
			// 586833641 - Pavan.s@doctorontime.ae
			// $assigned_to_arr = ['1184498292', '2767523564'];
			// 1184498292 - Aya.r@ontimehealthcare.com
			// 2767523564 - Muhammad.f@ontimegroup.com
		}

		/*	if ($lead_branch == "Doctor OnTime" || $lead_branch == "Doctor Ontime") {
			$assigned_to_arr = ['3696977214'];
			// 586833641 - Pavan.s@doctorontime.ae
			// 3696977214 - Samantha.m@doctorontime.ae
		}	*/

		if ($lead_branch === "OnTime Trustee") {
			$assigned_to_arr = ['3390723150'];
			// 3390723150 - charlene.d@ontimetrustee.com
			// 3862636972 - Raphael.r@ontimetrustee.com
		}


		if ($lead_branch === "Golden Cube" || $lead_branch === "GoldenCube") {
			// $assigned_to_arr = ['883057153'];
			// 883057153 - Fitoun.F@goldencube.ae
			$assigned_to_arr = ["4213254981", "2411946200"];
			// 4213254981 - Salam.A@goldencube.ae
			// 2411946200 - Abdulaziz.a@goldencube.ae
		}

		if ($lead_branch === "Baraha" || $lead_branch === "Al Baraha Government Services" || $lead_branch == "Al Baraha") {
			if ($lead_data->lead_departments === "Baraha MOH") {
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['1501975080', '3828530144'];
				// 1501975080 - alaa.m@baraha.ae
				// 3828530144 - Rabia.m@baraha.ae
			}
			else if ($lead_data->lead_departments === "Baraha Tasheel") {
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['270523625', '1699333399'];
				// 270523625 - amani.m@baraha.ae
				// 1699333399 - Leena.A@baraha.ae
			}
			else if ($lead_data->lead_departments === "Baraha DED") {
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['1475122687', '1462653913'];
				// 1475122687 - aysha.m@baraha.ae
				// 1462653913 - eman.m@baraha.ae
			}
			else if ($lead_data->lead_departments === "Baraha DLD") {
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['1323107434'];
				// 1323107434 - zohosocial@ontimegroup.com
			}
			else if ($lead_data->lead_departments === "Baraha Amer") {
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['270523625', '1699333399'];
				// 270523625 - amani.m@baraha.ae
				// 1699333399 - Leena.A@baraha.ae
			} else {
				$assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			}
		}

		if ($lead_branch === "Attestation") {
			$assigned_to_arr = ['3587668495', '1378964183'];
			// 3587668495 - Febinshad.M@ontimetranslation.ae
			// 1378964183 - rovi.r@ontimetranslation.ae
		}

		if ($lead_branch === "Translation") {
			$assigned_to_arr = ['3587668495', '1378964183'];
			// 3587668495 - Febinshad.M@ontimetranslation.ae
			// 1378964183 - rovi.r@ontimetranslation.ae
		}

		if ($lead_branch === "AlTasweyyat" || $lead_branch == "Al Tasweyaat") {
			// $assigned_to_arr = ['452236392', '1677539193'];
			$assigned_to_arr = ['1464543532'];
			// 452236392 - Mostafa.k@ontimegov.com
			// 1677539193 - Mohamed.as@ontimegov.com
			// 1464543532 - Hossameldin.M@altasweyaat.com
		}

		if ($lead_branch === "Notary Public") {
			$assigned_to_arr = ['4110428549'];
			// 4110428549 - atef.a@ontimegov.com
		}

		if ($lead_branch === "BurDubai") {
			$assigned_to_arr = ['2446367346', '2300187101'];
			// 2446367346 - Franklin.v@ontimegov.com
			// 3865339251 - Hassan.ab@ontimegov.com
			// 2300187101 - ehab.a@baraha.ae
			// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			// $assigned_to_arr = ['468190806', '3862377651', '4258687411'];
			// 468190806 - Frebel.m@ontimegov.com
			// 3862377651 - Sakhiyani.a@ontimegov.com
			// 4258687411 - ahmed.h@ontimegov.com
		}

		if ($lead_branch === "RTA") {
			$assigned_to_arr = ['2446367346', '2300187101'];
			// 2446367346 - Franklin.v@ontimegov.com
			// 3865339251 - Hassan.ab@ontimegov.com
			// 2300187101 - ehab.a@baraha.ae
			// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			// $assigned_to_arr = ['2295122845', '3016117228', '2019994271'];
			// 2295122845 - riyaz.i@ontimegov.com
			// 3016117228 - katia.k@ontimegov.com
			// 2019994271 - wedad.m@ontimegov.com
		}

		if ($lead_branch === "MOH") {
			$assigned_to_arr = ["3206568724"]; // jeramil.p@ontimegov.com
			// $assigned_to_arr = ['2446367346', '2300187101'];
			// 2446367346 - Franklin.v@ontimegov.com
			// 3865339251 - Hassan.ab@ontimegov.com
			// 2300187101 - ehab.a@baraha.ae
			// $assigned_to_arr = ['3431855600', '811882298'];
			// 3431855600 - hiba.s@ontimegov.com
			// 811882298 - ibrahim.kh@ontimegov.com
			// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			// $assigned_to_arr = ['2295122845', '3016117228', '811882298'];
			// 3431855600 - hiba.s@ontimegov.com
			// 3206568724 - jeramil.p@ontimegov.com
			// 811882298 - Ibrahim.k@ontimehealthcare.com
		}

		if ($lead_branch === "Al Manara") {
			$assigned_to_arr = ['2446367346', '2300187101'];
			// 2446367346 - Franklin.v@ontimegov.com
			// 3865339251 - Hassan.ab@ontimegov.com
			// 2300187101 - ehab.a@baraha.ae
			// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			// $assigned_to_arr = ['1290872561', '3029758041', '1858966354'];
			// 1290872561 - dipeeka.j@ontimegov.com
			// 3029758041 - sonali.k@ontimegov.com
			// 1858966354 - noushad.k@ontimegov.com
		}

		if ($lead_branch === "Mazaya Govermement Services") {
			if ($lead_data->lead_departments === "Mazaya DED") {
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['2893869692', '4269082088'];
				// 2893869692 - khawla.j@ontimegov.com
				// 4269082088 - suhaid.p@goldencube.ae
			}
			if ($lead_data->lead_departments === "Mazaya MOH") {
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['268519743'];	// mohammed.s@ontimegov.com
			}
			if ($lead_data->lead_departments === "Mazaya Tasheel") {
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['1038272360'];	// Rula.K@ontimetasheel.com
			}
			if ($lead_data->lead_departments === "Mazaya DLD") {
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['2634455476', '444200582'];
				// 2634455476 - Mohsen.M@ontimegov.com
				// 444200582 - Omar.h@ontimegov.com
			}
			if ($lead_data->lead_departments === "Mazay Amer" || $lead_data->lead_departments === "Mazaya Amer") {
				// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
				$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
				// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
				// $assigned_to_arr = ['2315531738', '2268544902'];
				// 2315531738 - Mohammad.Sh@ontimegov.com
				// 2268544902 - Irene.b@ontimegov.com
			}
		}

		if ($lead_branch === "Mazaya MOH") {
			// $assigned_to_arr = ["3865339251"]; // Hassan.ab@ontimegov.com
			$assigned_to_arr = ["2446367346"];  //  Franklin.v@ontimegov.com
			// $assigned_to_arr = ["2113278237"]; // cc@ontimegroup.com
			// $assigned_to_arr = ['268519743'];	// mohammed.s@ontimegov.com
		}

		if ($lead_branch === "BusinessVenue Government Services") {
			if ($lead_data->lead_departments === "BV DED") {
				$assigned_to_arr = ['1323107434']; // zohosocial@ontimegroup.com
			} else if ($lead_data->lead_departments === "BV MOH") {
				$assigned_to_arr = ['1323107434']; // zohosocial@ontimegroup.com
			} else if ($lead_data->lead_departments === "BV Tasheel") {
				$assigned_to_arr = ['1323107434']; // zohosocial@ontimegroup.com
			} else if ($lead_data->lead_departments === "BV DLD") {
				$assigned_to_arr = ['1323107434']; // zohosocial@ontimegroup.com
			} else if ($lead_data->lead_departments === "BV Amer") {
				$assigned_to_arr = ['1323107434']; // zohosocial@ontimegroup.com
			} else {
				$assigned_to_arr = ['1323107434']; // zohosocial@ontimegroup.com
			}
		}

		$service_id = 1009;
		$branch_id = 135;
		if ($lead_branch === "Golden Cube" || $lead_branch === "GoldenCube") {
			$branch_id = 106;
		}

		// $branch_id = 130;
		$lead_name =  trim($lead_data->first_name . " " .  $lead_data->last_name);
		$lead_contact =  $lead_data->lead_contact;
		$lead_countrycode = $lead_data->lead_countrycode;
		$lead_email =  $lead_data->lead_email;
		$lead_remarks =  $lead_data->lead_remarks;
		$lead_nationality = $lead_data->lead_nationality;

		// if($lead_data->lead_layout_name == 'Ontime Healthcare'){
		// 	$lead_owner = 1837294313;
		// 	$lead_by_pos_user = 2994811; 	// zohoontimehealthcare@ontimegroup.com
		// 	$lead_by_post_user_name = "Zoho HealthCare";

		// 	$branch_id = 136;
		// 	$assigned_to_arr = ['1184498292', '2283655705'];
		// } 
		// if($lead_data->lead_layout_name == 'Smart Cube'){
		// 	$lead_owner = 1837294313;	
		// 	$lead_by_pos_user = 1650926312; // zohosmartcube@ontimegroup.com
		// 	$lead_by_post_user_name = "Zoho Smart Cube";

		// 	$branch_id = 136;
		// 	$assigned_to_arr = ['1650926312'];
		// }
		// if($lead_data->lead_layout_name == 'Al Baraha'){
		// 	$lead_owner = 1837294313;	
		// 	$lead_by_pos_user = 2242077623; // zohoalbaraha@ontimegroup.com
		// 	$lead_by_post_user_name = "Zoho Al Baraha";

		// 	$branch_id = 136;
		// 	$assigned_to_arr = ['3325968771', '3828530144', '270523625','1475122687','1462653913'];
		// }

		

		$country =  $lead_data->lead_country;

		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$random_string = substr(str_shuffle($str_result), 0, 10);

		//check category exist
		$branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
		if ($branch_exist == 0) {
			$this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
		}

		$category_id = 109;

		if (true) {
			$random_email_name = strtolower($random_string);
			$random_email = $random_email_name . '@ontimecustomer.com';
			$lead_email = ($lead_email == '') ? $random_email : $lead_email;
			$lead_email = trim($lead_email);

			//create or get customer
			//$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);

			// $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
			/*$check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

			if ($check_email_exists != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
				//return $user_id;
			}	*/

			// if ($check_mobile_exists != 0) {
			//     $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact), 'user_id');
			//     //return $user_id;
			// }

			$check_lead_user = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact,'email' => trim($lead_email)));

			if ($check_lead_user != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact,'email' => trim($lead_email)), 'user_id');
			}

			// if ($check_email_exists == 0) {
			if ($check_lead_user == 0) {
				$password = 'Welcome@123';
				$confirm_password = 'Welcome@123';
				$auth_level = '1';
				$referal_code = $random_string;
				$user_hashed_password = $this->authentication->hash_passwd($password);
				$user_data = [
					'auth_level' => $auth_level,
					'mobile' => $lead_contact,
					'referal_code' => $referal_code,
					'country' => $country,
					'first_name' => $lead_name,
					'last_name' => '',
					'language' => '',
					'customer_type' => 'individual',
					'device_id' => '',
					'passwd' => $user_hashed_password,
					'email' => trim($lead_email),
					'passwd' => $user_hashed_password,
					'confirm_password' => $user_hashed_password,
					'language' => 0,
					'profile_pic' => '',
				];
				$user_data['user_id'] = $this->authentication_model->get_unused_id();
				$user_data['created_at'] = date('Y-m-d H:i:s');
				$user_data['modified_at'] = date('Y-m-d H:i:s');
				$user_data['otp'] = rand(1000, 9000);
				$user_data['email_otp'] = rand(1000, 9000);
				$user_data['banned'] = '0';
				$user_data['role_id'] = '4';
				$user_data['country'] = ($country != NULL && trim($country) != '') ? $country : 'United Arab Emirates';
				$user_data['country_code'] = ($lead_countrycode != NULL && trim($lead_countrycode) != '') ? $lead_countrycode : "+971";
				$user_data['nationality'] = ($lead_nationality != NULL && trim($lead_nationality) != '') ? $lead_nationality : "";

				$insert = $this->mcommon->common_insert("lead_users", $user_data);
				// echo $this->db->last_query();
				// echo "Inserts==> ";
				// print_r($insert); exit;
				// $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
				$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => trim($lead_email), 'mobile' => $lead_contact), 'user_id');
				//return $user_id;
				// print_r($user_data); exit;

			}

			if ($user_id != 0) {
				$uploaded_file_name = '';
				//process lead type
				$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_owner, 'is_primary_group_id' => 1), 'group_id');

				$insert_lead_array = array(
					'customer_id' => $user_id,
					'branch_id' => $branch_id,
					'category_id' => $category_id,
					'service_id' => (int) $service_id,
					'lead_created_by' => $lead_owner,
					'lead_added_on' => date('Y-m-d H:i:s'),
					'contactable_date' => date('Y-m-d H:i:s'),
					'lead_status' => 602,
					'package_id' => 0,
					'order_receipt' => 0,
					'remarks' => $lead_remarks,
					'is_assigned' => 1,
					'lead_by_pos_user' => $lead_by_pos_user,
					'lead_by_post_user_name' => $lead_by_post_user_name,
					'lead_source' =>  $lead_data->lead_source,
					'lead_ad_campaign' =>  $lead_data->lead_ad_campaign ? $lead_data->lead_ad_campaign : '',
					'lead_ad_campaign_id' => $lead_data->lead_ad_campaign_id ? $lead_data->lead_ad_campaign_id : '',
					'lead_ad_name' =>  $lead_data->lead_ad_name ? $lead_data->lead_ad_name : '',
					'lead_time_frame_to_start' =>  $lead_data->lead_time_frame_to_start ? $lead_data->lead_time_frame_to_start : '',
					'lead_zoho_id' =>  $lead_data->lead_zoho_id,
					'lead_form' =>  $lead_data->lead_form ? $lead_data->lead_form : '',
					'lead_reason_for_lost_mql' =>  $lead_data->lead_reason_for_lost_mql ? $lead_data->lead_reason_for_lost_mql : '',
					'lead_country' => $lead_data->lead_country,
					'lead_zoho_leadid' => $lead_data->lead_zoho_leadid,
					'lead_zoho_status' => $lead_data->lead_zoho_status,
					'lead_zoho_reason' => $lead_data->lead_zoho_reason,
					'lead_business_activity' => $lead_data->lead_business_activity,
					'lead_zoho_visa_type' => $lead_data->lead_visa_type,
					'lead_zoho_layout' => $lead_data->lead_layout_name,
					'lead_zoho_social_id' => $lead_data->lead_zoho_social_id,
					'lead_from' => 'Zoho Social',
					'created_group_id' => $created_group_id,
					'lead_zoho_chat_id' => $lead_data->lead_zoho_chat_id ? $lead_data->lead_zoho_chat_id : '',
					'lead_zoho_conversation_id' => $lead_data->lead_zoho_conversation_id ? $lead_data->lead_zoho_conversation_id : '',
					// 'lead_zoho_bank_statement' => $lead_data->lead_bank_statement,
				);
				$inserts = $this->db->insert("leads", $insert_lead_array);
				// echo $this->db->last_query();
				// echo "Inserts==> ";
				// print_r($inserts);
				$lead_id = $this->db->insert_id();


				if ($lead_id > 0) {

					//$assigned_to = 503941962;
					// $assigned_to = 2819412677;	// zohoontimevisa@ontimecrm.com

					// Adil.S@ontimevisa.com  - 1342769445
					// maroua@ontimevisa.com  - 2085249465
					// abdullokh.i@ontimevisa.com  - 4045309987

					// $assigned_to_arr = ['1184498292', '2283655705'];
					// $randomKey = array_rand($assigned_to_arr);
					$randomKey = array_rand($assigned_to_arr);
					$assigned_to = $assigned_to_arr[$randomKey];
					$assigned_by = $lead_by_pos_user;

					$delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
					$insert_array = array(
						'lead_id' => $lead_id,
						'assigned_by' => $assigned_by,
						'assigned_to' => $assigned_to,
						'assigned_on' => date('Y-m-d H:i:s'),
					);
					// echo "<br>";
					// echo "<br> ";
					// print_r($insert_array);

					$insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

					$assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id, 'created_group_id' => $assigned_group_id), array('id' => $lead_id));

					$coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
					$csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

					$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
					// print_r($log_insert_array);
					$log_insert = $this->db->insert('lead_action_log', $log_insert_array);

					// echo "Log: ".$log_insert."<br>";
					// echo "ERROR: ";
					// return ($this->db->error());
					// exit();
					if ($insert > 0) {
						$update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 602), array('id' => $lead_id));

						if ($update) {
							//create action log
							$log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
							$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
							$id = $this->db->insert_id();
							$action_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $id), 'action_id');
							$action_name = $this->mcommon->specific_row_value('lead_actions', array('id' => $action_id), 'action_name');

							$lead_info["lead_id"] = $lead_id;
							$lead_info["assigned_to"] = $assigned_to;

							$lead_info["action_name"] = $action_name;
							// $this->zohoupdate($lead_info, $id);

							// $receiver_email = $csa->email;
							$receiver_email = $csa->email;
							$receiver_name = $csa->first_name;
							$sender_email = $coordinator->email;
							$sender_name = $coordinator->first_name;

							$subject = "ONTIME CRM ALERT";

							$message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
							$email_array = array(
								'email' => $receiver_email,
								'subject' => $subject,
								'template' => 'mails/template',
								'from_name' => "ONTIME CRM ALERT",
								'message' => $message,
							);
							$send_mail = send_template_email($email_array);
							log_message('error', $send_mail);

							$postData = array(
								'lead_id' => $lead_id,
							);
				
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
							curl_setopt($ch, CURLOPT_HTTPHEADER, [
								'Accept: application/json',
								'Content-Type: application/json'
							]);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
							curl_setopt($ch, CURLOPT_HEADER, false); 
							curl_setopt($ch, CURLOPT_POST, true);  
							curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
							$response = curl_exec($ch);
							if(!empty($response))
								$update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

							$this->response(["status" => true, "message" => 'Enquiry has been created and assigned successfully!', "lead_id" => $lead_id], 200);
						} else {
							$delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
							// $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

							$this->response(["status" => false, "message" => 'Unable to assign the enquiry at present. Please try again later'], 500);
						}
					} else {
						$this->response(["status" => false, "message" => 'Unable to assign enquiry at present.', "exception" => $this->db->error()], 500);
					}

					$this->response(["status" => true, "message" => "Enquiry successfully created.", "lead_id" => $lead_id], 200);
				} else {
					$this->response(["status" => false, "message" => 'Unable to create enquirys at this moment.Please try again.', "exception" => $this->db->error()], 500);
				}

				//     // $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimebiz.com/uploads/leads/' . $uploaded_file_name);

			} else {
				$this->response(["status" => false, "message" => 'Unable to create leads at this moment.Please try again.', "exception" => $this->db->error(), "data" => $user_data], 500);
			}
		} else {
			$this->response(["status" => false, "message" => validation_errors()], 400);
		}
	}

	public function get_booking_zohotoken($scope){
		$apiUrl = 'https://accounts.zoho.com/oauth/v2/token';

		// This is from the Webchats zoho login
		$data = [
            "client_id" => '1000.6WCLOT6V37DJRKXNIVODWIZ75ENLHB',	
			"client_secret" => 'a13c91feaca699214277818eaf8fd381933f24e4b1',	
			"refresh_token" => "1000.729f23dc268be0cce0a016de246d0ba4.0c62a8847aebec7ab4374445d288ffd4",		
            "grant_type" => 'refresh_token',
        ];

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            return json_decode($response, true);
        } else {
            return ['error' => 'Login failed'];
        }
	}

	public function zoho_fetch_booking_post(){
		if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
			$this->response([array('message'=>'Invalid Method')],REST_Controller::HTTP_BAD_REQUEST);
		}

		$service_id = "4633210000000037054";		// Goldencube Meeting
		$staff_id = "4633210000000037016";			// Webchats Staff id
		// $resource_id = "4633785000000042022";

		$api_url = "https://www.zohoapis.com/bookings/v1/json/fetchappointment";

		$scope = "zohobookings.data.CREATE";
		$get_token = $this->get_booking_zohotoken($scope);
		$accessToken = $get_token['access_token'];
        // echo '<pre>';print_r($get_token);exit;
		// $accessToken = '1000.9af2a4f2dbb09dae3647f25d2945fea5.a40e7b8c172e2b6d9a1e5f73fbb86f39';  

        $from_date = date('d-M-Y 00:00:00');
        // $to_date = date('d-M-Y 23:59:59');

		// $from_date = date('d-M-Y 00:00:00', strtotime('+1 day'));         
		$to_date = date('d-M-Y 23:59:59', strtotime('+7 days'));

        // Form data
        $post_data = [
            // 'service_id' => $service_id,
            // 'staff_id' => $staff_id,
            // 'from_time' => $from_time,
            // 'to_time' => $to_time,
            // 'timezone' => $timezone,
            // 'customer_details' => json_encode([
            //     'name' => $customer_name,
            //     'email' => $customer_email,
            //     'phone_number' => $customer_phone
            // ]),
            // 'notes' => $notes
            'data' => json_encode([
                'service_id' => $service_id,
                // 'staff_id' => $staff_id,
                // 'from_time' => date('d-M-Y 00:00:00'),
                // 'to_time' => date('d-M-Y 23:59:59'),
                'from_time' => $from_date,
                'to_time' => $to_date,
            ])
        ];

        // Initialize cURL
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Zoho-oauthtoken ' . $accessToken
        ]);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'cURL error: ' . $error_msg]));
            return;
        }

        // Close cURL resource
        curl_close($ch);

        // Decode response
        $result = json_decode($response, true);

        // if (isset($result['response']['status']) && ($result['response']['status'] == 'success') && 
        //     isset($result['response']['returnvalue']['response'])  && ($result['response']['returnvalue']['response'] == 'No Match Found')) {
        //     echo '<pre>';print_r($result['response']['returnvalue']['response']);exit;
        // } 

        // Check response status
        if (isset($result['response']['status']) && $result['response']['status'] == 'success') {
            $load = $result['response']['returnvalue']['response'];

            $dsn = array(
                'dsn'   => 'mysql:host=localhost;dbname=test',
                'hostname' => 'localhost',
                'username' => 'crm_root',
                'password' => 'df240b484c',
                'database' => 'test',
                'dbdriver' => 'pdo',  // Use 'pdo' for PDO connection or 'mysqli' for MySQLi
                'dbprefix' => '',
                'pconnect' => FALSE,
                'db_debug' => (ENVIRONMENT !== 'production'),
                'cache_on'  => FALSE,
                'cachedir'  => '',
                'char_set'  => 'utf8',
                'dbcollat'  => 'utf8_general_ci',
                'return_type' => 'array',
                'save_queries' => TRUE
            );

            $this->db1 = $this->load->database($dsn, true);

            $lead_id = 0;
            foreach ($load as $data) {
                // echo '<pre>';print_r($data);exit;
                $lead = [];
                $crm_response ='';
                $lead["lead_remarks"] = '';

                $zoho_id = $data["booking_id"];
                try {
                    $check_zohoid_exists = $this->db1->where(array('zoho_id' => $zoho_id))->from('zoho_booking_leads')->count_all_results();
                } catch (Exception $e) {
                    $check_zohoid_exists = 0;
                }

                if ($check_zohoid_exists != 0) {
                    continue;
                }

                $customer_mobile = $data['customer_contact_no'];
				if (!empty($customer_mobile) && $customer_mobile !== null) {
					$customer_mobile = trim($customer_mobile);
					if ($customer_mobile[0] !== '+') {
						$customer_mobile = '+' . $customer_mobile;
					}
				}

                // preg_match('/^\+(\d{1,4})/', $customer_mobile, $matches);
                // $countryCode = '+' . $matches[1];
                // $customer_mobile = preg_replace('/^\+971|\+91/', '', $customer_mobile);
                // $lead["lead_contact"] = $customer_mobile;
                // $lead["lead_countrycode"] = $countryCode;

				$mobilePattern = '/^(?:\+971|00971|971|0)?(\d{7,8})$/';
				$landlinePattern = '/^(?:\+971|00971|971|0)?(\d{7})$/';
				$internationalPattern = '/^\+(\d{1,3})[\s-]?(\d+[\s-]?[\d\s-]*)$/';

				$mobile_no = '';
				$countryCode = '';

				if (preg_match($mobilePattern, $customer_mobile, $matches)) {
					$mobile_no = $matches[1];
				} elseif (preg_match($landlinePattern, $customer_mobile, $matches)) {
					$mobile_no = $matches[1];
				} elseif (preg_match($internationalPattern,  $customer_mobile, $matches)) {
					$mobile_no = $matches[2];
					$countryCode =  '+' . $matches[1];
				} else {
					$mobile_no = $customer_mobile;
				}

				$lead["lead_contact"] = $mobile_no;
				$lead["lead_countrycode"] = $countryCode;

                $lead["lead_email"] = $data['customer_email'];
                $lead["lead_customer_name"] = $data["customer_name"];
                $lead["lead_customer_timezone"] = $data['customer_booking_time_zone'];
                
                $lead["lead_type"] = "zoho_meeting";
                $lead["lead_zoho_booking_id"] = $data['booking_id'];
                $lead["lead_zoho_booking_type"] = $data['booking_type'];
                $lead["lead_zoho_booking_duration"] = $data['duration'];
                $lead["lead_zoho_booking_time"] = $data['customer_booking_start_time'];
                $lead["lead_zoho_booking_status"] = $data['status'];
                $lead["lead_zoho_booking_created_on"] = $data['booked_on'];

                $lead["lead_zoho_booking_staff_name"] = $data['staff_name'];
                $lead["lead_zoho_booking_staff_email"] = $data['staff_email'];
                $lead["lead_zoho_booking_service"] = $data['service_name'];
                $lead["lead_zoho_booking_workspace"] = $data['workspace_name'];
                $lead["lead_zoho_booking_triggered_by"] = $data['triggered_by'];
                $lead["lead_zoho_booking_link"] = $data['meeting_info']['start_link'];
                $lead["lead_zoho_booking_summaryurl"] = $data['summary_url'];

                $lead["lead_remarks"] = '';
                if (isset($data['workspace_name'])) {
                    $lead["lead_remarks"] = "WorkSpace : " . $data['workspace_name'];
                }
                if (isset($data['service_name'])) {
                    $lead["lead_remarks"] .= "</b></br>ServcieName : " . $data["service_name"];
                }
                if (isset($data['booking_id'])) {
                    $lead["lead_remarks"] .= "</b></br>Booking ID : " . $data["booking_id"];
                }
                if (isset($data['customer_booking_start_time'])) {
					date_default_timezone_set('Asia/Dubai');
                    $formatted = date('M d, Y h:i A', strtotime($data['customer_booking_start_time']));
                    $lead["lead_remarks"] .= "</b></br>Meeting Start Time : " . $formatted;
                }
                if (isset($data['duration'])) {
                    $lead["lead_remarks"] .= "</b></br>Meeting Duration : " . $data['duration'];
                }
                if (isset($data['meeting_info']['start_link'])) {
                    // $lead["lead_remarks"] .= "</b></br>Meeting URL : " . $data['meeting_info']['start_link'];
                    $lead["lead_remarks"] .= "</b><br>Meeting URL : <a href='" . $data['meeting_info']['start_link'] . "' target='_blank'>" . $data['meeting_info']['start_link'] . "</a>";
                }

                $insert_array = array(
                    "zoho_id" => $data["booking_id"],
                    "zoho_response" => json_encode($data),
                    "crm_req" => json_encode($lead),
                    "page_no" => 1,
                );

                $insert_zoho_leads = $this->db1->insert('zoho_booking_leads', $insert_array);

                $lead_owner = 541294986;	// zohomeeting@ontimegroup.com
                $lead_by_pos_user = 541294986;	// zohomeeting@ontimegroup.com
                $lead_by_post_user_name = "Zoho Meeting";

                $branch_id = 106;
                $service_id = 1009;
                $category_id = 109;

                $lead_name =  $lead["lead_customer_name"];
                $lead_contact = $lead["lead_contact"];
                $lead_countrycode = $lead["lead_countrycode"];
                $lead_email = trim($lead["lead_email"]);
                $lead_remarks = !empty($lead["lead_remarks"]) ? $lead["lead_remarks"] : "";
                $lead_nationality = '';
                $country =  '';

                $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
                $random_string = substr(str_shuffle($str_result), 0, 10);

                //check category exist
                $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
                if ($branch_exist == 0) {
                    $this->response(["status" => "false", "branch_id" => $branch_id, "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
                }

                if (true) {
                    $random_email_name = strtolower($random_string);
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;

                    $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email, 'mobile' => $lead_contact));

                    if ($check_email_exists != 0) {
                        $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email, 'mobile' => $lead_contact), 'user_id');
                    }

                    if ($check_email_exists == 0) {
                        $password = 'Welcome@123';
                        $confirm_password = 'Welcome@123';
                        $auth_level = '1';
                        $referal_code = $random_string;
                        $user_hashed_password = $this->authentication->hash_passwd($password);
                        $user_data = [
                            'auth_level' => $auth_level,
                            'mobile' => $lead_contact,
                            'referal_code' => $referal_code,
                            'country' => $country,
                            'first_name' => $lead_name,
                            'last_name' => '',
                            'language' => '',
                            'customer_type' => 'individual',
                            'device_id' => '',
                            'passwd' => $user_hashed_password,
                            'email' => trim($lead_email),
                            'passwd' => $user_hashed_password,
                            'confirm_password' => $user_hashed_password,
                            'language' => 0,
                            'profile_pic' => '',
                        ];
                        $user_data['user_id'] = $this->authentication_model->get_unused_id();
                        $user_data['created_at'] = date('Y-m-d H:i:s');
                        $user_data['modified_at'] = date('Y-m-d H:i:s');
                        $user_data['otp'] = rand(1000, 9000);
                        $user_data['email_otp'] = rand(1000, 9000);
                        $user_data['banned'] = '0';
                        $user_data['role_id'] = '4';
                        $user_data['country'] = ($country != NULL && trim($country) != '') ? $country : 'United Arab Emirates';
                        $user_data['country_code'] = ($lead_countrycode != NULL && trim($lead_countrycode) != '') ? $lead_countrycode : "+971";
                        $user_data['nationality'] = ($lead_nationality != NULL && trim($lead_nationality) != '') ? $lead_nationality : "";

                        $insert = $this->mcommon->common_insert("lead_users", $user_data);
                        $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email, 'mobile' => $lead_contact), 'user_id');
                    }

                    if ($user_id != 0) {
                        $uploaded_file_name = '';
                        //process lead type

                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => (int) $service_id,
                            'lead_created_by' => $lead_owner,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 602,
                            'package_id' => 0,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 1,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                            'lead_source' => 'Zoho Booking',
                            'lead_form' => 'Zoho Booking',
                            'lead_from' => 'Zoho Booking',
                            'lead_zoho_bookingid' => $data['booking_id'],
                            'lead_zoho_booking_staffemail' => $data['staff_email'],
                            'lead_zoho_booking_triggeredby' => $data['triggered_by'],
                            'lead_zoho_booking_created_on' => $data['booked_on'],
                            'lead_zoho_booking_summaryurl' => $data['summary_url'],
							'created_group_id' => 74,
							'assigned_group_id' => 74,
                        );
						
                        $inserts = $this->db->insert("leads", $insert_lead_array);
                        // echo $this->db->last_query();
                        // echo "Inserts==> ";
                        // print_r($inserts); exit;
                        $lead_id = $this->db->insert_id();

                        if ($lead_id > 0) {
                            // $randomKey = array_rand($assigned_to_arr);
                            // $assigned_to = $assigned_to_arr[$randomKey];

                            $assigned_to = 4213254981;	// Salam.A@goldencube.ae
                            $assigned_by = $lead_by_pos_user;

                            $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                            $insert_array = array(
                                'lead_id' => $lead_id,
                                'assigned_by' => $assigned_by,
                                'assigned_to' => $assigned_to,
                                'assigned_on' => date('Y-m-d H:i:s'),
                            );
                            // echo "<br>";
                            // echo "<br> ";
                            // print_r($insert_array);
                            $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                            $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                            $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

                            $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
                            // print_r($log_insert_array);
                            $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                            if ($insert > 0) {
                                $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 602), array('id' => $lead_id));

                                if ($update) {
                                    //create action log
                                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 602);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                    $id = $this->db->insert_id();
                                    $action_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $id), 'action_id');
                                    $action_name = $this->mcommon->specific_row_value('lead_actions', array('id' => $action_id), 'action_name');

                                    $lead_info["lead_id"] = $lead_id;
                                    $lead_info["assigned_to"] = $assigned_to;
                                    $lead_info["action_name"] = $action_name;

                                    // $receiver_email = $csa->email;
                                    $receiver_email = $csa->email;
                                    $receiver_name = $csa->first_name;
                                    $sender_email = $coordinator->email;
                                    $sender_name = $coordinator->first_name;

                                    $subject = "Ontime CRM ALERT";

                                    $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
                                    $email_array = array(
                                        'email' => $receiver_email,
                                        'subject' => $subject,
                                        'template' => 'mails/template',
                                        'from_name' => "ONTIME CRM ALERT",
                                        'message' => $message,
                                    );
                                    // $send_mail = send_template_email($email_array);
                                    // log_message('error', $send_mail);

                                    $crm_response = ["status" => true, "message" => 'Enquiry has been created and assigned successfully!', "lead_id" => $lead_id];
                                } else {
                                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                                    $crm_response = ["status" => false, "message" => 'Unable to assign the enquiry at present. Please try again later'];
                                }
                            } else {
                                $crm_response = ["status" => false, "message" => 'Unable to assign enquiry at present.', "exception" => $this->db->error()];
                            }
                                $crm_response = ["status" => true, "message" => "Enquiry successfully created.", "lead_id" => $lead_id];
                        } else {
                            $crm_response = ["status" => false, "message" => 'Unable to create enquirys at this moment.Please try again.', "exception" => $this->db->error()];
                        }
                
                    }
                }

                if($crm_response['lead_id'] != 0 && $crm_response['lead_id'] != NULL){
                    array_push($leads_array, $crm_response['lead_id']);
                }
                $update_array = array(
                    "crm_response" => json_encode($crm_response)
                );

                $this->db1->where('zoho_id', $zoho_id);  // Specify the condition
                $update_zoho_leads = $this->db1->update('zoho_booking_leads', $update_array);
                // echo '<pre>';print_r($update_zoho_leads);exit;
            }
            echo '<pre>';print_r($leads_array);exit;
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'response' => $result['response'], 'message' => 'Failed to book the appointment']));
        }
	}

	public function zoho_fetch_dld_booking_post(){
		if (strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
			$this->response([array('message'=>'Invalid Method')],REST_Controller::HTTP_BAD_REQUEST);
		}

		// $service_id = "4633210000000037054";		// Goldencube Meeting
		$service_id = "4633210000000944030";		// DLD Meeting
		$staff_id = "4633210000000037016";			// Webchats Staff id
		// $resource_id = "4633785000000042022";

		$api_url = "https://www.zohoapis.com/bookings/v1/json/fetchappointment";

		$scope = "zohobookings.data.CREATE";
		$get_token = $this->get_booking_zohotoken($scope);
		$accessToken = $get_token['access_token'];
        // echo '<pre>';print_r($accessToken);exit;
		// $accessToken = '1000.1ea8e269515bb95fa48fa3bd24928e7d.2ea975a425bde69f16182d6930adc7d9';  

        $from_date = date('d-M-Y 00:00:00');
        // $to_date = date('d-M-Y 23:59:59');

		// $from_date = date('d-M-Y 00:00:00', strtotime('+1 day'));
		$to_date = date('d-M-Y 23:59:59', strtotime('+7 days'));
        // Form data
        $post_data = [
            'data' => json_encode([
                'service_id' => $service_id,
                // 'staff_id' => $staff_id,
                'from_time' => $from_date,
                'to_time' => $to_date,
            ])
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Zoho-oauthtoken ' . $accessToken
        ]);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'message' => 'cURL error: ' . $error_msg]));
            return;
        }

        curl_close($ch);
        $result = json_decode($response, true);

        if (isset($result['response']['status']) && $result['response']['status'] == 'success') {
            $load = $result['response']['returnvalue']['response'];

            $dsn = array(
                'dsn'   => 'mysql:host=localhost;dbname=test',
                'hostname' => 'localhost',
                'username' => 'crm_root',
                'password' => 'df240b484c',
                'database' => 'test',
                'dbdriver' => 'pdo',  // Use 'pdo' for PDO connection or 'mysqli' for MySQLi
                'dbprefix' => '',
                'pconnect' => FALSE,
                'db_debug' => (ENVIRONMENT !== 'production'),
                'cache_on'  => FALSE,
                'cachedir'  => '',
                'char_set'  => 'utf8',
                'dbcollat'  => 'utf8_general_ci',
                'return_type' => 'array',
                'save_queries' => TRUE
            );

            $this->db1 = $this->load->database($dsn, true);

            $lead_id = 0;
            foreach ($load as $data) {
                // echo '<pre>';print_r($data);exit;
                $lead = [];
                $crm_response ='';
                $lead["lead_remarks"] = '';

                $zoho_id = $data["booking_id"];
                try {
                    $check_zohoid_exists = $this->db1->where(array('zoho_id' => $zoho_id))->from('zoho_booking_leads')->count_all_results();
                } catch (Exception $e) {
                    $check_zohoid_exists = 0;
                }

                if ($check_zohoid_exists != 0) {
                    continue;
                }

				$countryCode = '';
                $customer_mobile = $data['customer_contact_no'];

				if (!empty($customer_mobile) && $customer_mobile !== null) {
					$customer_mobile = trim($customer_mobile);
					if ($customer_mobile[0] !== '+') {
						$customer_mobile = '+' . $customer_mobile;
					}
				}

                // preg_match('/^\+(\d{1,4})/', $customer_mobile, $matches);
                // $countryCode = '+' . $matches[1];
                // $customer_mobile = preg_replace('/^\+971|\+91/', '', $customer_mobile);
                // $lead["lead_contact"] = $customer_mobile;
                // $lead["lead_countrycode"] = $countryCode;

				$mobilePattern = '/^(?:\+971|00971|971|0)?(\d{7,8})$/';
				$landlinePattern = '/^(?:\+971|00971|971|0)?(\d{7})$/';
				$internationalPattern = '/^\+(\d{1,3})[\s-]?(\d+[\s-]?[\d\s-]*)$/';

				$mobile_no = '';

				if (preg_match($mobilePattern, $customer_mobile, $matches)) {
					$mobile_no = $matches[1];
				} elseif (preg_match($landlinePattern, $customer_mobile, $matches)) {
					$mobile_no = $matches[1];
				} elseif (preg_match($internationalPattern,  $customer_mobile, $matches)) {
					$mobile_no = $matches[2];
					$countryCode =  '+' . $matches[1];
				} else {
					$mobile_no = $customer_mobile;
				}

				$lead["lead_contact"] = $mobile_no;
				$lead["lead_countrycode"] = $countryCode;

                $lead["lead_email"] = $data['customer_email'];
                $lead["lead_customer_name"] = $data["customer_name"];
                $lead["lead_customer_timezone"] = $data['customer_booking_time_zone'];
                
                $lead["lead_type"] = "zoho_meeting";
                $lead["lead_zoho_booking_id"] = $data['booking_id'];
                $lead["lead_zoho_booking_type"] = $data['booking_type'];
                $lead["lead_zoho_booking_duration"] = $data['duration'];
                $lead["lead_zoho_booking_time"] = $data['customer_booking_start_time'];
                $lead["lead_zoho_booking_status"] = $data['status'];
                $lead["lead_zoho_booking_created_on"] = $data['booked_on'];

                $lead["lead_zoho_booking_staff_name"] = $data['staff_name'];
                $lead["lead_zoho_booking_staff_email"] = $data['staff_email'];
                $lead["lead_zoho_booking_service"] = $data['service_name'];
                $lead["lead_zoho_booking_workspace"] = $data['workspace_name'];
                $lead["lead_zoho_booking_triggered_by"] = $data['triggered_by'];
                $lead["lead_zoho_booking_link"] = $data['meeting_info']['start_link'];
                $lead["lead_zoho_booking_summaryurl"] = $data['summary_url'];

                $lead["lead_remarks"] = '';
                if (isset($data['workspace_name'])) {
                    $lead["lead_remarks"] = "WorkSpace : " . $data['workspace_name'];
                }
                if (isset($data['service_name'])) {
                    $lead["lead_remarks"] .= "</b></br>ServcieName : " . $data["service_name"];
                }
                if (isset($data['notes'])) {
                    $lead["lead_remarks"] .= "</b></br>Notes : " . $data["notes"];
                }
                if (isset($data['booking_id'])) {
                    $lead["lead_remarks"] .= "</b></br>Booking ID : " . $data["booking_id"];
                }
                if (isset($data['customer_booking_start_time'])) {
					date_default_timezone_set('Asia/Dubai');
                    $formatted = date('M d, Y h:i A', strtotime($data['customer_booking_start_time']));
                    $lead["lead_remarks"] .= "</b></br>Meeting Start Time : " . $formatted;
                }
                if (isset($data['duration'])) {
                    $lead["lead_remarks"] .= "</b></br>Meeting Duration : " . $data['duration'];
                }
                if (isset($data['meeting_info']['start_link'])) {
                    $lead["lead_remarks"] .= "</b><br>Meeting URL : <a href='" . $data['meeting_info']['start_link'] . "' target='_blank'>" . $data['meeting_info']['start_link'] . "</a>";
                }

                $insert_array = array(
                    "zoho_id" => $data["booking_id"],
                    "zoho_response" => json_encode($data),
                    "crm_req" => json_encode($lead),
                    "page_no" => 1,
                );

                $insert_zoho_leads = $this->db1->insert('zoho_booking_leads', $insert_array);

                $lead_owner = 1403060325;	// dldzohomeeting@ontimegroup.com
                $lead_by_pos_user = 1403060325;	// dldzohomeeting@ontimegroup.com
                $lead_by_post_user_name = "Zoho Meeting";

                $branch_id = 113;
                $service_id = 1009;
                $category_id = 109;

                $lead_name =  $lead["lead_customer_name"];
                $lead_contact = $lead["lead_contact"];
                $lead_countrycode = $lead["lead_countrycode"];
                $lead_email = trim($lead["lead_email"]);
                $lead_remarks = !empty($lead["lead_remarks"]) ? $lead["lead_remarks"] : "";
                $lead_nationality = '';
                $country =  '';

                $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
                $random_string = substr(str_shuffle($str_result), 0, 10);

                //check category exist
                $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
                if ($branch_exist == 0) {
                    $this->response(["status" => "false", "branch_id" => $branch_id, "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
                }

                if (true) {
                    $random_email_name = strtolower($random_string);
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;

                    $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email, 'mobile' => $lead_contact));

                    if ($check_email_exists != 0) {
                        $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email, 'mobile' => $lead_contact), 'user_id');
                    }

                    if ($check_email_exists == 0) {
                        $password = 'Welcome@123';
                        $confirm_password = 'Welcome@123';
                        $auth_level = '1';
                        $referal_code = $random_string;
                        $user_hashed_password = $this->authentication->hash_passwd($password);
                        $user_data = [
                            'auth_level' => $auth_level,
                            'mobile' => $lead_contact,
                            'referal_code' => $referal_code,
                            'country' => $country,
                            'first_name' => $lead_name,
                            'last_name' => '',
                            'language' => '',
                            'customer_type' => 'individual',
                            'device_id' => '',
                            'passwd' => $user_hashed_password,
                            'email' => $lead_email,
                            'passwd' => $user_hashed_password,
                            'confirm_password' => $user_hashed_password,
                            'language' => 0,
                            'profile_pic' => '',
                        ];
                        $user_data['user_id'] = $this->authentication_model->get_unused_id();
                        $user_data['created_at'] = date('Y-m-d H:i:s');
                        $user_data['modified_at'] = date('Y-m-d H:i:s');
                        $user_data['otp'] = rand(1000, 9000);
                        $user_data['email_otp'] = rand(1000, 9000);
                        $user_data['banned'] = '0';
                        $user_data['role_id'] = '4';
                        $user_data['country'] = ($country != NULL && trim($country) != '') ? $country : 'United Arab Emirates';
                        $user_data['country_code'] = ($lead_countrycode != NULL && trim($lead_countrycode) != '') ? $lead_countrycode : "+971";
                        $user_data['nationality'] = ($lead_nationality != NULL && trim($lead_nationality) != '') ? $lead_nationality : "";

                        $insert = $this->mcommon->common_insert("lead_users", $user_data);
                        $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email, 'mobile' => $lead_contact), 'user_id');
                    }

                    if ($user_id != 0) {
                        $uploaded_file_name = '';
						$created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 1403060325, 'is_primary_group_id' => 1), 'group_id');
                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => (int) $service_id,
                            'lead_created_by' => $lead_owner,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 602,
                            'package_id' => 0,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                            'lead_source' => 'Zoho Booking',
                            'lead_form' => 'Zoho Booking',
                            'lead_from' => 'Zoho Booking',
                            'lead_zoho_bookingid' => $data['booking_id'],
                            'lead_zoho_booking_staffemail' => $data['staff_email'],
                            'lead_zoho_booking_triggeredby' => $data['triggered_by'],
                            'lead_zoho_booking_created_on' => $data['booked_on'],
                            'lead_zoho_booking_summaryurl' => $data['summary_url'],
							'created_group_id' => $created_group_id,
                        );

                        $inserts = $this->db->insert("leads", $insert_lead_array);
                        $lead_id = $this->db->insert_id();

                        if ($lead_id > 0) {
							$crm_response = ["status" => true, "message" => "Enquiry successfully created", "lead_id" => $lead_id];
                        } else {
                            $crm_response = ["status" => false, "message" => 'Unable to create enquirys at this moment.Please try again.', "exception" => $this->db->error()];
                        }
                    }
                }

                if($crm_response['lead_id'] != 0 && $crm_response['lead_id'] != NULL){
                    array_push($leads_array, $crm_response['lead_id']);
                }
                $update_array = array(
                    "crm_response" => json_encode($crm_response)
                );

                $this->db1->where('zoho_id', $zoho_id);  // Specify the condition
                $update_zoho_leads = $this->db1->update('zoho_booking_leads', $update_array);
                // echo '<pre>';print_r($update_zoho_leads);exit;
            }
            echo '<pre>';print_r($leads_array);exit;
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'response' => $result['response'], 'message' => 'Failed to book the appointment']));
        }
	}

	public function lead_data()
	{
		// $this->db->distinct();
		$this->db->distinct();
		$this->db->select('leads.id AS id, lead_users.email, lead_users.first_name AS customer_name');
		$this->db->from('leads');
		$this->db->join('lead_users', 'leads.customer_id = lead_users.user_id');
		$this->db->join('lead_action_log', 'lead_action_log.lead_id = leads.id');
		$this->db->where('lead_action_log.action_id', 412);
		$this->db->where('leads.branch_id', 138);
		$this->db->where('leads.lead_status', 307);
		$this->db->group_start();
		$this->db->where('lead_action_log.payment_id', 0);
		$this->db->or_where('lead_action_log.payment_id IS NULL');
		$this->db->group_end();
		$this->db->where('lead_action_log.action_on <', 'NOW() - INTERVAL 3 HOUR', false);

		// Use raw SQL for the NOT EXISTS condition for reschedule need to check i think want to remove exists condition
		$this->db->where('NOT EXISTS (
			SELECT 1
			FROM lead_action_log AS sub_log
			WHERE sub_log.lead_id = leads.id
			AND sub_log.action_id = 415
		)', null, false);

		$query = $this->db->get();
		$lead_details = $query->result();

		$processed_leads = [];
		foreach ($lead_details as $lead) {
			$processed_leads[] = [
				'id' => $lead->id,
				'email' => $lead->email,
				'customer_name' => $lead->customer_name
			];
		}
		return $processed_leads;
	}

	public function calendar_cancel_leads_post()
	{
		$test = $this->lead_data();
		// var_dump(is_array($test) && !empty($test));
		// exit;
		if (is_array($test) && !empty($test)) {
			$get_lead_ids = [];
			foreach ($test as $lead) {
				$get_lead_ids[] = $lead['id'];
				$appointment = $this->db->where("lead_id", $lead['id'])->from("calendar_appointment")->get()->first_row();
				$status_desc = $this->mcommon->specific_row_value('BookingStatus', array('StatusCode' => 909), 'StatusDescription');
				$user_id = $this->mcommon->specific_row_value('lead_action_log', array('lead_id' =>  $lead['id'], 'action_id' => 412), 'action_by');
				$user = $this->db->where("user_id", $user_id)->from("users")->get()->first_row();
				$leadDetails = $this->leads_model->lead_details($lead['id']);

				$update = $this->mcommon->common_edit('calendar_appointment', array('status' => 909, 'updated_at' => date('Y-m-d H:i:s')), array('id' => $appointment->id));
				$calendar_log = array('appointment_id' => $appointment->id, 'remark' => "Booking Cancelled Updated", 'status_code' => 909, 'status_description' => $status_desc, 'created_by' => $user_id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));
				$insert_log = $this->mcommon->common_insert('calendar_log', $calendar_log);

				$postData = [
					'id' => $appointment->id,
					'booking_id' => $appointment->booking_id,
					'status' => 909,
					'updated_at' => date('Y-m-d H:i:s'),

				];

				// Call the sendOrderUpdate function to make the cURL request
				$response = $this->sendOrderCancelledNotpaid($postData);

				if (!empty($appointment->lead_id)) {
					$action_message1 = "Appointment id " . $appointment->id . " Booking Cancelled ";
					$lead_action_log = array('lead_id' => $appointment->lead_id, 'action_amount' => 0, 'action_id' => 440, 'status_id' => 639, 'action_by' => $user_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => '<pre>' . $action_message1 . '</pre>', 'bot_id' => 0);
					$insert_log = $this->mcommon->common_insert('lead_action_log', $lead_action_log);
					$update = $this->mcommon->common_edit('leads', array('lead_status' => 639), array('id' => $appointment->lead_id));
				}

				$email_setting = $this->db->where("id", 1)->from("calendar_settings")->get()->first_row();
				if ($email_setting && $email_setting->c_email_booked == 1) {
					if (!empty($appointment->email)) {
						$time = $appointment->booking_timeslot;
						// Step 1: Explode the string by commas to separate the date and times
						$parts = explode(', ', $time);
						// Step 2: The first part is the date, and the rest are the time slots
						$date = $parts[0]; // Extract the date
						$time_slots = array_slice($parts, 0); // Get all the time slots (excluding the date)
						// Step 3: Check if time slots are null or empty
						//if ($time_slots == NULL || empty($time_slots)) {
						if ($time == NULL) {
							// If no time slots are provided, set default start and end times in 12-hour format
							$time_range = '09:00 AM - 08:00 PM'; // 9 AM to 6 PM
						}
						// Step 4: Check if there is only one time slot
						else if (count($time_slots) == 1) {
							// If only one time slot, calculate the end time as the start time plus one hour
							$first_time = $time_slots[0];

							// Convert time to DateTime object
							$start_time = new DateTime($first_time);

							// Add one hour to the start time
							$end_time = clone $start_time;
							$end_time->modify('+1 hour');

							// Format time range in 12-hour format with AM/PM
							$time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
						}
						// Step 5: Handle multiple time slots
						else {
							// Get the first and last time slots
							$first_time = new DateTime($time_slots[0]);
							$last_time = new DateTime($time_slots[count($time_slots) - 1]);

							// Format time range in 12-hour format with AM/PM
							$time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
						}
						$appointment = $this->db->where("id", $appointment->id)->from("calendar_appointment")->get()->first_row();
						$cc_usermail = [];
						//     // After Payment Completion for DLD Fees
						array_push($sublead_cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);

						$email_array1 = array(
							'name' => $leadDetails["customer_name"],
							'email' => $leadDetails["customer_email"], //$cust_email,
							'subject' => 'Mobile Medical Examination - Appointment Cancelled',
							'template' => 'mails/bv_cancel_not_paid',
							'from_name' => "ONTIME GOV ALERT",
							'from_email' => 'mobile.medical@ontimegov.com',
							'cc' => $cc_usermail,
							// 'message' => $html,
							'booking_reference_number' => $appointment->id ?? 'N/A',
							'service_name' =>  !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
							'package_name' =>  !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
							'date' => $appointment->booking_date  ?? 'N/A',
							'time' => $time_range  ?? 'N/A',
							'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
							'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
							// 'name' => $user->first_name . $user->last_name,
						);

						$send_mail1 = send_template_email($email_array1);
						log_message('error', $send_mail1);
						$calendar_log = array('appointment_id' => $appointment->id, 'remark' => 'Booking Cancel / Not Paid Updated and email sent to ' . $appointment->email, 'status_code' => 909, 'status_description' => $status_desc, 'created_by' => $user_id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));
						$insert_log = $this->mcommon->common_insert('calendar_log', $calendar_log);
					}
				}
				if ($email_setting && $email_setting->s_email_booked == 1) {
					if (!empty($user->email)) {
						$time = $appointment->booking_timeslot;
						// Step 1: Explode the string by commas to separate the date and times
						$parts = explode(', ', $time);
						// Step 2: The first part is the date, and the rest are the time slots
						$date = $parts[0]; // Extract the date
						$time_slots = array_slice($parts, 0); // Get all the time slots (excluding the date)
						// Step 3: Check if time slots are null or empty
						//if ($time_slots == NULL || empty($time_slots)) {
						if ($time == NULL) {
							// If no time slots are provided, set default start and end times in 12-hour format
							$time_range = '09:00 AM - 08:00 PM'; // 9 AM to 6 PM
						}
						// Step 4: Check if there is only one time slot
						else if (count($time_slots) == 1) {
							// If only one time slot, calculate the end time as the start time plus one hour
							$first_time = $time_slots[0];

							// Convert time to DateTime object
							$start_time = new DateTime($first_time);

							// Add one hour to the start time
							$end_time = clone $start_time;
							$end_time->modify('+1 hour');

							// Format time range in 12-hour format with AM/PM
							$time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
						}
						// Step 5: Handle multiple time slots
						else {
							// Get the first and last time slots
							$first_time = new DateTime($time_slots[0]);
							$last_time = new DateTime($time_slots[count($time_slots) - 1]);

							// Format time range in 12-hour format with AM/PM
							$time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
						}
						$appointment = $this->db->where("id", $appointment->id)->from("calendar_appointment")->get()->first_row();
						$cc_usermail = [];
						//     // After Payment Completion for DLD Fees
						array_push($sublead_cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);

						$email_array = array(
							// 'name' => $user->email,
							// 'email' => $user->first_name . $user->last_name,
							'name' => "mathan",
							'email' => "mathanraj.g@mitrahsoft.in", //$cust_email,
							'subject' => 'Mobile Medical Examination - Appointment Cancelled',
							'template' => 'mails/bv_cancel_not_paid',
							'from_name' => "ONTIME GOV ALERT",
							'from_email' => 'mobile.medical@ontimegov.com',
							'cc' => $cc_usermail,
							// 'message' => $html,
							'booking_reference_number' => $appointment->id ?? 'N/A',
							'service_name' =>  !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
							'package_name' =>  !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
							'date' => $appointment->booking_date  ?? 'N/A',
							'time' => $time_range  ?? 'N/A',
							'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
							'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
							// 'name' => $user->first_name . $user->last_name,
						);

						$send_mail = send_template_email($email_array);
						log_message('error', $send_mail);

						$calendar_log = array('appointment_id' => $appointment->id, 'remark' => 'Booking Cancel / Not Paid Updated and email sent to ' . $appointment->email, 'status_code' => 909, 'status_description' => $status_desc, 'created_by' => $user_id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'));
						$insert_log = $this->mcommon->common_insert('calendar_log', $calendar_log);
					}
				}

				// if ($lead['id'] == $lead_id) {
				// 	// Update the lead_status to 307
				// 	$this->db->where('id', $lead_id);
				// 	$this->db->update('leads', ['lead_status' => 307]);
				// 	break;
				// }
			}
			$this->response(["status" => true, "message" => $get_lead_ids], 200);
		} else {
			$this->response(["status" => false, "message" => 'NO leads'], 500);
		}
	}


	public function sendOrderCancelledNotpaid($postData)
	{
		// Convert the data to JSON format
		$fields = json_encode($postData);

		// Initialize cURL session
		$ch = curl_init();

		// Set cURL options
		curl_setopt($ch, CURLOPT_URL, 'https://ontimegov.com/digital/api/v1/baraha/Order/cancel_not_paid',);  // Verify this URL is correct

		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Accept: application/json',
			'api-key: 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',
			'Content-Type: application/json'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response instead of outputting it
		curl_setopt($ch, CURLOPT_HEADER, false);  // Do not include the header in the output
		curl_setopt($ch, CURLOPT_POST, true);  // Use POST method
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);  // Attach the JSON data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification (useful for local environments)

		// Execute cURL request
		$response = curl_exec($ch);

		return $response;
	}

	public function subleadcreationapi_get()
	{
		$parent_lead_id = $_GET['lead_id'];
		$lead_remarks = $_GET['remark'];
		$is_direct_invoice = $_GET['is_direct_invoice'];
		$user_id = $_GET['user_id'];

		$lead_det = $this->leads_model->lead_details($parent_lead_id);
		$branch_id = $lead_det['branch_id']; //138
		// $lead_type = $lead_det['lead_type'];//lead//normal
		$category_id = $lead_det['category_id']; //109
		$service_id = $lead_det['service_id']; //1009

		$insert_lead_array = array(
			'customer_id' => $lead_det['customer_id'],
			'branch_id' => $branch_id,
			'category_id' => $category_id,
			'service_id' => $service_id,
			'lead_created_by' => $user_id,
			'lead_added_on' => date('Y-m-d H:i:s'),
			'contactable_date' => date('Y-m-d H:i:s'),
			'lead_status' => 301,
			'package_id' => 0,
			'order_receipt' => 0,
			'remarks' => $lead_remarks,
			'lead_parent_id' => $parent_lead_id,
			'is_assigned' => 0,
			'is_direct_invoice' => $is_direct_invoice, //ask hanna
			'govt_fee' => 0.00,
			'card_amount' => 0.00
		);
		$insert_sub_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

		$branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');
		$user_name = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'concat(first_name, " ", last_name)');
		if ($insert_sub_lead_id > 0) {
			//create action log
			$log_insert_array = array('action_id' => 401, 'lead_id' => $insert_sub_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $user_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $user_id, 'status_id' => 301);
			$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

			return $this->response(["status" => true, "message" => "Sub Lead Successfully Created", "newsublead_id" => $insert_sub_lead_id]);
		} else {
			return $this->response(["status" => false, "message" => "sublead not created."]);
		}
	}

	public function calendar_reminder_leads_post()
	{
		// Fetch appointments
		$appointments = $this->db->select("*")->from("calendar_appointment")->where("status", 903)->get()->result();
		foreach ($appointments as $appointment) {
			// Get calendar settings based on calendar_id
			$calendarSettings = $this->db->select("*")
				->from("calendar_settings")
				->where("calendar_id", $appointment->calendar_id)
				->get()
				->row();

			if ($calendarSettings) {
				// Decode JSON columns for staff reminders
				$s_email_before_num = json_decode($calendarSettings->s_email_before_num, true);
				$s_email_before_num_value = json_decode($calendarSettings->s_email_before_num_value, true);

				// Decode JSON columns for customer reminders
				$c_email_before_num = json_decode($calendarSettings->c_email_before_num, true);
				$c_email_before_num_value = json_decode($calendarSettings->c_email_before_num_value, true);

				// Combine keys and values for staff email settings
				$staffEmailSettings = [];
				foreach ($s_email_before_num as $key => $num) {
					if (isset($s_email_before_num_value[$key]) && !empty($num) && !empty($s_email_before_num_value[$key])) {
						$staffEmailSettings[$s_email_before_num_value[$key]] = (int)$num;
					}
				}

				// Combine keys and values for customer email settings
				$customerEmailSettings = [];
				foreach ($c_email_before_num as $key => $num) {
					if (isset($c_email_before_num_value[$key]) && !empty($num) && !empty($c_email_before_num_value[$key])) {
						$customerEmailSettings[$c_email_before_num_value[$key]] = (int)$num;
					}
				}

				function convertToMinutes($settings)
				{
					$totalMinutes = ($settings['days'] * 24 * 60) + ($settings['hours'] * 60) + $settings['minutes'];
					return $totalMinutes;
				}
				$staffTotalMinutes = convertToMinutes($staffEmailSettings);
				$customerTotalMinutes = convertToMinutes($customerEmailSettings);

				// Calculate time difference
				$appointmentDateTime = new DateTime($appointment->booking_date . ' ' . $appointment->booking_timeslot);

				// Set Dubai timezone for current time
				$timezoneDubai = new DateTimeZone('Asia/Dubai');
				$currentTime = new DateTime("now", $timezoneDubai);

				// Function to convert DateTime to total minutes since Unix epoch
				function convertToMinutes_dubai(DateTime $dateTime)
				{
					return (int)($dateTime->getTimestamp() / 60);  // Convert timestamp (seconds) to minutes
				}

				$appointmentMinutes = convertToMinutes_dubai($appointmentDateTime);
				$currentMinutes = convertToMinutes_dubai($currentTime);

				$differenceInMinutes = $appointmentMinutes - $currentMinutes;

				// var_dump(["diff"=>$differenceInMinutes,"stafftm"=>$staffTotalMinutes,"customertm"=>$customerTotalMinutes,"appointmenttm"=>$appointmentMinutes,"currentmins"=>$currentMinutes,"dubaitime"=>$currentTime,"appointmenttime"=>$appointmentDateTime]);exit;

				$time = $appointment->booking_timeslot;
				// Step 1: Explode the string by commas to separate the date and times
				$parts = explode(', ', $time);
				// Step 2: The first part is the date, and the rest are the time slots
				$date = $parts[0]; // Extract the date
				$time_slots = array_slice($parts, 0); // Get all the time slots (excluding the date)
				// Step 3: Check if time slots are null or empty
				//if ($time_slots == NULL || empty($time_slots)) {
				if ($time == NULL) {
					// If no time slots are provided, set default start and end times in 12-hour format
					$time_range = '09:00 AM - 08:00 PM'; // 9 AM to 6 PM
				}
				// Step 4: Check if there is only one time slot
				else if (count($time_slots) == 1) {
					// If only one time slot, calculate the end time as the start time plus one hour
					$first_time = $time_slots[0];

					// Convert time to DateTime object
					$start_time = new DateTime($first_time);

					// Add one hour to the start time
					$end_time = clone $start_time;
					$end_time->modify('+1 hour');

					// Format time range in 12-hour format with AM/PM
					$time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
				}
				// Step 5: Handle multiple time slots
				else {
					// Get the first and last time slots
					$first_time = new DateTime($time_slots[0]);
					$last_time = new DateTime($time_slots[count($time_slots) - 1]);

					// Format time range in 12-hour format with AM/PM
					$time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
				}
				// Check conditions to send emails for staff

				if ($differenceInMinutes == $staffTotalMinutes && $appointment->reminder_admin_mail != "1") {
					$emp_user_id = $this->mcommon->specific_row_value('lead_action_log', array('lead_id' => $appointment->lead_id, "status_id" => 307), 'action_by');
					$user_pos_name = $this->mcommon->specific_row_value('users', array('user_id' => $emp_user_id), 'concat(first_name, " ", last_name)');
					$user_pos_email = $this->mcommon->specific_row_value('users', array('user_id' => $emp_user_id), 'email');
					$cc_usermail = [];
					//     // After Payment Completion for DLD Fees
					array_push($sublead_cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);
					$email_array1 = array(
						'name' => $user_pos_name,
						'email' => 'mathanraj.g@mitrahsoft.in', //$user_pos_email, // $cust_email,
						'subject' => 'Mobile Medical Examination - Reminder: Upcoming Appointment on [' . $appointment->booking_date . '] – [' . $appointment->id . ']',
						'template' => 'mails/bv_reminder',
						'from_name' => "ONTIME GOV ALERT",
						'from_email' => 'mobile.medical@ontimegov.com',
						'cc' => $cc_usermail,
						// 'message' => $html,
						'booking_reference_number' => $appointment->id ?? 'N/A',
						'service_name' => !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
						'package_name' => !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
						'date' => $appointment->booking_date ?? 'N/A',
						'time' => $time_range ?? 'N/A',
						'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
						'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
						// 'name' => $user->first_name . $user->last_name,
					);
					$send_mail1 = send_template_email($email_array1);
					log_message('error', $send_mail1);
					$update = $this->mcommon->common_edit('calendar_appointment', array('reminder_admin_mail' => 1), array('id' => $appointment->id));
				}

				// Check conditions to send emails for customers

				if ($differenceInMinutes == $customerTotalMinutes && $appointment->reminder_cus_mail != "1") {
					$leadDetails = $this->leads_model->lead_details($appointment->lead_id);
					$cc_usermail = [];
					//     // After Payment Completion for DLD Fees
					array_push($sublead_cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);
					$email_array1 = array(
						'name' => $leadDetails["customer_name"],
						'email' => $leadDetails["customer_email"], // $cust_email,
						'subject' => 'Mobile Medical Examination - Reminder: Upcoming Appointment on [' . $appointment->booking_date . '] – [' . $appointment->id . ']',
						'template' => 'mails/bv_reminder',
						'from_name' => "ONTIME GOV ALERT",
						'from_email' => 'mobile.medical@ontimegov.com',
						'cc' => $cc_usermail,
						// 'message' => $html,
						'booking_reference_number' => $appointment->id ?? 'N/A',
						'service_name' => !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
						'package_name' => !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
						'date' => $appointment->booking_date ?? 'N/A',
						'time' => $time_range ?? 'N/A',
						'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
						'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
						// 'name' => $user->first_name . $user->last_name,
					);

					$send_mail1 = send_template_email($email_array1);
					log_message('error', $send_mail1);
					$update = $this->mcommon->common_edit('calendar_appointment', array('reminder_cus_mail' => 1), array('id' => $appointment->id));
				}
			}
		}
	}

	public function creating_invoice_post()
	{
		$post_data = $this->post();
		if (!empty($post_data)) {
			$inv_number = $post_data['InvoiceNo'];
			$RCT_number = $post_data['ReceiptNo'];
			$cust_key = $post_data['Cust_Key'];
			$lead_parent_id = $this->mcommon->specific_row_value('leads', ['pos_cust_key' => $cust_key, 'lead_parent_id' => 0], 'id');
			if (empty($lead_parent_id)) {
				// Return an error message if no data is found
				$success = false;
				$message = "customer key not found in CRM DB";
				return $this->response(["status" => $success, "message" => $message]);
			}
			$details = $post_data['Details'];
			$success = true;
			$message = "Data inserted successfully.";
			$jsonString = json_encode($post_data);
			foreach ($details as $detail) {
				$data = [
					'inv_number' => $inv_number,
					'RCT_number' => $RCT_number,
					'cust_key' => $cust_key,
					'inv_detail_number' => $detail['Inv_DLinenum'],
					'service_name' => $detail['TransactionName'],
					'amount' => $detail['Amount'],
					'request' => $jsonString,
					'lead_parent_id' => $lead_parent_id
				];
				$insert = $this->mcommon->common_insert('invoice_details', $data);
				if (!empty($insert)) {
					$transactionName = $detail['TransactionName'];

					if (stripos($transactionName, 'medical') !== false) {
						$sub_lead_id = $this->mcommon->specific_row_value('leads', ['pos_cust_key' => $cust_key, 'remarks LIKE' => "%medical%", 'lead_parent_id' => $lead_parent_id], 'id');
						$update_sublead_id = $this->mcommon->common_edit('invoice_details', array('lead_id' => $sub_lead_id), array('id' => $insert));
					} elseif (stripos($transactionName, 'van') !== false) {

						$sub_lead_id = $this->mcommon->specific_row_value('leads', ['pos_cust_key' => $cust_key, 'remarks LIKE' => "%van%", 'lead_parent_id' => $lead_parent_id], 'id');
						$update = $this->mcommon->common_edit('leads', array('pos_invresponse' => $inv_number, 'order_receipt' => $inv_number, 'lead_status' => 305), array('id' => $sub_lead_id));
						$update_sublead_id = $this->mcommon->common_edit('invoice_details', array('lead_id' => $sub_lead_id), array('id' => $insert));
					} else {
						// Handle case when neither keyword is found
						$sub_lead_message = 'Test Mail not updated in leads table'.'<br>'. $jsonString;
						$sub_lead_subject .= "The API details no match found in strpos.";
						$email_array = array(
							'email' =>  'mathanraj.g@mitrahsoft.in',
							'subject' => $sub_lead_subject,
							'template' => 'mails/template',
							'from_name' => "mathan",
							'message' => $sub_lead_message,
						);
						$send_mail = send_template_email($email_array);
					}
				}
				if (!$insert) {
					$success = false;
					$message = "Failed to insert data for invoice detail number ";
					return $this->response(["status" => $success, "message" => $message, "data" => $data]);
				}
			}
			// $update = $this->mcommon->common_edit('leads', array('pos_invresponse' => $inv_number, 'order_receipt' => $inv_number), array('pos_cust_key' => $cust_key));
			// $lead_parent_id = $this->mcommon->specific_row_value('leads', ['pos_cust_key' => $cust_key, 'lead_parent_id' => 0], 'id');
			// if (empty($lead_parent_id)) {
			// 	// Return an error message if no data is found
			// 	$success = false;
			// 	$message = "customer key not found in CRM DB";
			// 	return $this->response(["status" => $success, "message" => $message]);
			// }
			// // $lead = $this->mcommon->specific_row('leads', ["lead_parent_id" => $lead_parent_id]);
			// $lead_action = $this->mcommon->specific_row('lead_action_log', ["lead_id" => $lead_parent_id], ['action_id' => 412], ['status_id' => 307]);
			// $user_id = $lead_action['action_by'];
			// $sub_leads = $this->mcommon->specific_fields_records_all('leads', ['lead_parent_id' => $lead_parent_id]);
			// $update = $this->mcommon->common_edit('leads', array('lead_status' => 305), array('lead_parent_id' => $lead_parent_id));
			// if (!empty($sub_leads)) {
			// 	foreach ($sub_leads as $sublead) {
			// 		$order_desc = 'Order completed <strong>Sublead_id#:</strong>' . $sublead["id"] . '<strong>ORDER#:</strong> ' . $RCT_number . ' <strong>Invoice#:</strong> ' . $inv_number;
			// 		$log_insert_array = [
			// 			'action_id' => 410,
			// 			'lead_id' => $sublead["id"],
			// 			'action_on' => date('Y-m-d H:i:s'),
			// 			'remarks' => $order_desc,
			// 			'action_by' => $user_id,
			// 			'status_id' => 305
			// 		];
			// 		$insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
			// 	}
			return $this->response(["status" => $success, "message" => $message]);
			// }
		} else {
			$sub_lead_message = 'Test Mail' . $post_data . print_r($_POST, true);
			$sub_lead_subject .= "The API details are empty.";
			$email_array = array(
				'email' =>  'mathanraj.g@mitrahsoft.in',
				'subject' => $sub_lead_subject,
				'template' => 'mails/template',
				'from_name' => "mathan",
				'message' => $sub_lead_message,
			);
			$send_mail = send_template_email($email_array);
			$success = false;
			$message = "We could not find datas ";
			return $this->response(["status" => $success, "message" => $message]);
		}
	}

	// public function  complete_calendar_post()
	// {
	// 	$post_data = $this->post();

	// 	if (!empty($post_data)) {
	// 		$lead_id = $post_data['lead_id'];
	// 		// $cust_key = $post_data['Cust_Key'];

	// 		$emp_user_id = $this->mcommon->specific_row_value('lead_action_log', array('lead_id' => $lead_id, "status_id" => 307), 'action_by');

	// 		$user = $this->db->where("user_id", $emp_user_id)->from("users")->get()->first_row();

	// 		$appointment = $this->db->where("lead_id", $lead_id)->from("calendar_appointment")->get()->first_row();

	// 		$status = $this->mcommon->specific_row_value('BookingStatus', ['StatusCode' => 908], 'StatusDescription');

	// 		$leadDetails = $this->leads_model->lead_details($lead_id);

	// 		$subleadDetails = $this->db
	// 			->select('id, order_receipt, pos_invresponse, remarks')
	// 			->where('lead_parent_id', $lead_id)
	// 			->get('leads')
	// 			->result();


	// 		$subleadInfo = [];
	// 		if (!empty($subleadDetails)) {
	// 			foreach ($subleadDetails as $sublead) {
	// 				$subleadInfo[] = [
	// 					'id' => $sublead->id,
	// 					'order_receipt' => $sublead->order_receipt,
	// 					'remarks' => $sublead->remarks,
	// 					'pos_invresponse' => $sublead->pos_invresponse,
	// 				];
	// 			}
	// 		}

	// 		// Retrieve medical sub-leads
	// 		$sub_leads_medical = $this->db
	// 			->select('id, remarks, lead_status')
	// 			->where('lead_parent_id', $lead_id)
	// 			->like('remarks', 'Medical')
	// 			->get('leads')
	// 			->result();


	// 		if (!empty($sub_leads_medical)) {
	// 			foreach ($sub_leads_medical as $sub_lead) {
	// 				if ($sub_lead->lead_status == 301) {
	// 					$success = false;
	// 					$message = "Medical typing fee is not completed.";
	// 					return $this->response(["status" => $success, "message" => $message]);
	// 				}
	// 			}
	// 		}
	// 		$sub_leads = DB::table('leads')
	// 			->select('id')
	// 			->where('lead_parent_id', $lead_id)
	// 			->where('lead_status', 301)
	// 			->get();

	// 		if (!empty($sub_leads)) {

	// 			return response()->json([
	// 				'success' => false,
	// 				'message' => "Sub lead are not closed successfully.Please contact support"
	// 			]);
	// 		}


	// 		if ($appointment->status == 912 || $appointment->status == 907) {
	// 			// Update the status in the calendar_appointment table
	// 			$this->db->where('id', $this->input->post('id'))
	// 				->update('calendar_appointment', [
	// 					'status' => 908,
	// 					'updated_at' => date('Y-m-d H:i:s'),
	// 				]);

	// 			// Insert log data into the calendar_log table
	// 			$calendar_log_data = [
	// 				'appointment_id' => $appointment->id,
	// 				'remark' => 'Booking Completed Updated',
	// 				'status_code' => 908,
	// 				'status_description' => $status,
	// 				'created_by' => $userId,
	// 				'created_at' => date('Y-m-d H:i:s'),
	// 				'updated_at' => date('Y-m-d H:i:s'),
	// 			];
	// 			$this->db->insert('calendar_log', $calendar_log_data);

	// 			// Prepare post data
	// 			$postData = [
	// 				'id' => $appointment->id,
	// 				'booking_id' => $appointment->booking_id,
	// 				'status' => 908,
	// 				'updated_at' => date('Y-m-d H:i:s'),
	// 			];

	// 			// Call the sendOrderComplete function to make the cURL request
	// 			$response = $this->sendOrderComplete($postData);

	// 			if (!empty($appointment->lead_id)) {
	// 				$action_message1 = "Appointment id " . $appointment->id . " Booking Completed ";

	// 				// Insert data into lead_action_log
	// 				$lead_action_log_data = [
	// 					'lead_id' => $lead_id,
	// 					'action_amount' => $appointment->amount,
	// 					'action_id' => 439,
	// 					'status_id' => 638,
	// 					'action_by' => $emp_user_id,
	// 					'action_on' => date('Y-m-d H:i:s'),
	// 					'remarks' => '<pre>' . $action_message1 . '</pre>',
	// 					'bot_id' => 0,
	// 				];
	// 				$this->db->insert('lead_action_log', $lead_action_log_data);
	// 			}

	// 			// Fetch email settings
	// 			$email_setting = $this->db->where('id', 1)->get('calendar_settings')->row();
	// 			if ($email_setting && $email_setting->c_email_booked == 1) {
	// 				if (!empty($appointment->email)) {
	// 					$time = $appointment->booking_timeslot;
	// 					// Step 1: Explode the string by commas to separate the date and times
	// 					$parts = explode(', ', $time);
	// 					// Step 2: The first part is the date, and the rest are the time slots
	// 					$date = $parts[0]; // Extract the date
	// 					$time_slots = array_slice($parts, 0); // Get all the time slots (excluding the date)
	// 					// Step 3: Check if time slots are null or empty
	// 					if ($time == NULL) {
	// 						$time_range = '09:00 AM - 08:00 PM'; // Default time range
	// 					} else if (count($time_slots) == 1) {
	// 						$first_time = $time_slots[0];
	// 						$start_time = DateTime::createFromFormat('H:i', $first_time);
	// 						$end_time = clone $start_time;
	// 						$end_time->modify('+1 hour');
	// 						$time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
	// 					} else {
	// 						$first_time = DateTime::createFromFormat('H:i', $time_slots[0]);
	// 						$last_time = DateTime::createFromFormat('H:i', $time_slots[count($time_slots) - 1]);
	// 						$time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
	// 					}

	// 					$this->db->where('id', $this->input->post('id'));
	// 					$appointment = $this->db->get('calendar_appointment')->row();


	// 					array_push($sublead_cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);
	// 					$email_array1 = array(
	// 						'name' => $leadDetails['customer_name'],
	// 						'email' => $leadDetails['customer_email'], //$user_pos_email, // $cust_email,
	// 						'subject' => 'Mobile Medical Examination : Appointment Completed - Invoice Attached',
	// 						'template' => 'mails/bv_payment_received',
	// 						'from_name' => "ONTIME GOV ALERT",
	// 						'from_email' => 'mobile.medical@ontimegov.com',
	// 						'cc' => $cc_usermail,
	// 						// 'message' => $html,
	// 						'booking_reference_number' => $appointment->id ?? 'N/A',
	// 						'service_name' => !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
	// 						'package_name' => !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
	// 						'date' => $appointment->booking_date ?? 'N/A',
	// 						'time' => $time_range ?? 'N/A',
	// 						'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
	// 						'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
	// 						// 'name' => $user->first_name . $user->last_name,
	// 						'payment_status' => 'Paid',
	// 						'name' => $leadDetails['customer_name'],
	// 						'subleadInfo' => $subleadInfo,
	// 						'pos_pmt_number' => $leadDetails['pos_pmt_number'],
	// 					);
	// 					$send_mail1 = send_template_email($email_array1);


	// 					if ($send_mail) {
	// 						$this->db->insert('calendar_log', array(
	// 							'appointment_id' => $appointment->id,
	// 							'remark' => 'Booking Completed and email sent to ' . $appointment->email,
	// 							'status_code' => 908,
	// 							'status_description' => $status,
	// 							'created_by' => $emp_user_id,
	// 							'created_at' => date('Y-m-d H:i:s'),
	// 							'updated_at' => date('Y-m-d H:i:s'),
	// 						));
	// 					}
	// 				}
	// 			}

	// 			if ($email_setting && $email_setting->s_email_booked == 1) {
	// 				if (!empty($user->email)) {
	// 					$time = $appointment->booking_timeslot;
	// 					$parts = explode(', ', $time);
	// 					$date = $parts[0];
	// 					$time_slots = array_slice($parts, 0);

	// 					if ($time == NULL) {
	// 						$time_range = '09:00 AM - 08:00 PM';
	// 					} else if (count($time_slots) == 1) {
	// 						$first_time = $time_slots[0];
	// 						$start_time = DateTime::createFromFormat('H:i', $first_time);
	// 						$end_time = clone $start_time;
	// 						$end_time->modify('+1 hour');
	// 						$time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
	// 					} else {
	// 						$first_time = DateTime::createFromFormat('H:i', $time_slots[0]);
	// 						$last_time = DateTime::createFromFormat('H:i', $time_slots[count($time_slots) - 1]);
	// 						$time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
	// 					}

	// 					$this->db->where('id', $this->input->post('id'));
	// 					$appointment = $this->db->get('calendar_appointment')->row();

	// 					array_push($sublead_cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);
	// 					$email_array1 = array(
	// 						'name' => $leadDetails['customer_name'],
	// 						'email' => $user->email, //$user_pos_email, // $cust_email,
	// 						'subject' => 'Mobile Medical Examination : Appointment Completed - Invoice Attached',
	// 						'template' => 'mails/bv_payment_received',
	// 						'from_name' => "ONTIME GOV ALERT",
	// 						'from_email' => 'mobile.medical@ontimegov.com',
	// 						'cc' => $cc_usermail,
	// 						// 'message' => $html,
	// 						'booking_reference_number' => $appointment->id ?? 'N/A',
	// 						'service_name' => !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
	// 						'package_name' => !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
	// 						'date' => $appointment->booking_date ?? 'N/A',
	// 						'time' => $time_range ?? 'N/A',
	// 						'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
	// 						'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
	// 						// 'name' => $user->first_name . $user->last_name,
	// 						'payment_status' => 'Paid',
	// 						'name' => $leadDetails['customer_name'],
	// 						'subleadInfo' => $subleadInfo,
	// 						'pos_pmt_number' => $leadDetails['pos_pmt_number'],
	// 					);
	// 					$send_mail1 = send_template_email($email_array1);


	// 					if ($send_mail) {
	// 						$this->db->insert('calendar_log', array(
	// 							'appointment_id' => $appointment->id,
	// 							'remark' => 'Booking completed and email sent to ' . $user->email,
	// 							'status_code' => 908,
	// 							'status_description' => $status,
	// 							'created_by' => $emp_user_id,
	// 							'created_at' => date('Y-m-d H:i:s'),
	// 							'updated_at' => date('Y-m-d H:i:s'),
	// 						));
	// 					}
	// 				}
	// 			}

	// 			return response()->json([
	// 				'success' => true,
	// 				'message' => 'Booking completed successfully!'
	// 			]);
	// 		} else {
	// 			return response()->json([
	// 				'success' => false,
	// 				'message' => 'Calendar not in secheduled for refund'
	// 			]);
	// 		}
	// 	}
	// }

	public function  complete_calendar_post()
    {
        $post_data = $this->post();

        if (!empty($post_data)) {
            $lead_id = $post_data['lead_id'];
            // $cust_key = $post_data['Cust_Key'];

            $emp_user_id = $this->mcommon->specific_row_value('lead_action_log', array('lead_id' => $lead_id, "status_id" => 307), 'action_by');

            $user = $this->db->where("user_id", $emp_user_id)->from("users")->get()->first_row();

            $appointment = $this->db->where("lead_id", $lead_id)->from("calendar_appointment")->get()->first_row();

            $status = $this->mcommon->specific_row_value('BookingStatus', ['StatusCode' => 914], 'StatusDescription');

            $leadDetails = $this->leads_model->lead_details($lead_id);
            
            // $subleadDetails = $this->db
            //  ->select('id, order_receipt, pos_invresponse, remarks')
            //  ->where('lead_parent_id', $lead_id)
            //  ->get('leads')
            //  ->result();


            // $subleadInfo = [];
            // if (!empty($subleadDetails)) {
            //  foreach ($subleadDetails as $sublead) {
            //      $subleadInfo[] = [
            //          'id' => $sublead->id,
            //          'order_receipt' => $sublead->order_receipt,
            //          'remarks' => $sublead->remarks,
            //          'pos_invresponse' => $sublead->pos_invresponse,
            //      ];
            //  }
            // }

            // // Retrieve medical sub-leads
            // $sub_leads_medical = $this->db
            //  ->select('id, remarks, lead_status')
            //  ->where('lead_parent_id', $lead_id)
            //  ->like('remarks', 'Medical')
            //  ->get('leads')
            //  ->result();


            // if (!empty($sub_leads_medical)) {
            //  foreach ($sub_leads_medical as $sub_lead) {
            //      if ($sub_lead->lead_status == 301) {
            //          $success = false;
            //          $message = "Medical typing fee is not completed.";
            //          return $this->response(["status" => $success, "message" => $message]);
            //      }
            //  }
            // }
            // $sub_leads = DB::table('leads')
            //  ->select('id')
            //  ->where('lead_parent_id', $lead_id)
            //  ->where('lead_status', 301)
            //  ->get();

            // if (!empty($sub_leads)) {

            //  return response()->json([
            //      'success' => false,
            //      'message' => "Sub lead are not closed successfully.Please contact support"
            //  ]);
            // }

            if ($appointment->status == 912 || $appointment->status == 907) {
                // Update the status in the calendar_appointment table
                $this->db->where('id', $appointment->id)
                    ->update('calendar_appointment', [
                        'status' => 914,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    
                // Insert log data into the calendar_log table
                $calendar_log_data = [
                    'appointment_id' => $appointment->id,
                    'remark' => 'Refund Sucessfully',
                    'status_code' => 914,
                    'status_description' => $status,
                    'created_by' => $user->user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $this->db->insert('calendar_log', $calendar_log_data);

                // Prepare post data
                $postData = [
                    'id' => $appointment->id,
                    'booking_id' => $appointment->booking_id,
                    'status' => 914,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                // Call the sendOrderComplete function to make the cURL request
                $response = $this->sendOrderComplete($postData);

                if (!empty($appointment->lead_id)) {
                    $action_message1 = "Appointment id " . $appointment->id . " Refunded successfully ";

                    // Insert data into lead_action_log
                    $lead_action_log_data = [
                        'lead_id' => $lead_id,
                        'action_amount' => $appointment->amount,
                        'action_id' => 441,
                        'status_id' => 640,
                        'action_by' => $emp_user_id,
                        'action_on' => date('Y-m-d H:i:s'),
                        'remarks' => '<pre>' . $action_message1 . '</pre>',
                        'bot_id' => 0,
                    ];
                    $this->db->insert('lead_action_log', $lead_action_log_data);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Calendar not in secheduled for refund'
                ]);
            }
        }
    }
	public function sendOrderComplete($postData)
	{
		// Convert the data to JSON format
		$fields = json_encode($postData);

		// Initialize cURL session
		$ch = curl_init();

		// Set cURL options
		curl_setopt($ch, CURLOPT_URL, 'https://ontimegov.com/digital/api/v1/baraha/Order/completed',);  // Verify this URL is correct

		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Accept: application/json',
			'api-key: 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',
			'Content-Type: application/json'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response instead of outputting it
		curl_setopt($ch, CURLOPT_HEADER, false);  // Do not include the header in the output
		curl_setopt($ch, CURLOPT_POST, true);  // Use POST method
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);  // Attach the JSON data
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification (useful for local environments)

		// Execute cURL request
		$response = curl_exec($ch);

		return $response;
	}

	public function zoho_fetchleaddata_get()
	{
		if(isset($_GET["lead_id"])){
			$lead_id = $_GET["lead_id"];
			try {
				$this->db->select('leads.id as lead_id,
				concat(lead_users.first_name," ",lead_users.last_name) as customer_name, 
				lead_users.email as customer_email,
				concat(lead_users.country_code," ",lead_users.mobile) as customer_mobile,
				ontime_branches.branch_name as branch, 
				leads.lead_zoho_department as department,
				leads.lead_zoho_description as description')->from("leads");
				$this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
				$this->db->join("ontime_branches", "ontime_branches.branch_code = leads.branch_id");
				$this->db->where("leads.id", $lead_id);
				$result = $this->db->get()->result_array();
				// print_r($this->db->last_query());
				// exit();
				$lead_data = array(
					'email' => $result[0]['customer_email'],
					'name' => $result[0]['customer_name'],
					'mobile' => $result[0]['customer_mobile'],
					'description' => $result[0]['remarks'] ? $result[0]['remarks'] : '',
					'group' => $result[0]['department'] ? $result[0]['department'] : '',
					'company' => 'Ontime',
					'branch' => $result[0]['branch'],
					'lead_id' => $result[0]['lead_id']
				);
				return $this->response(["status" => true, "data" => $lead_data, "message" => "Data Fetched"]);
			} catch (Exception $e) {
				return $this->response(["status" => false, "message" => "Something went wrong."]);
			}
		} else {
			$this->response(["message" => 'Parameters missing'], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function zoho_responsetime_sla_violation_post()
	{
		try {
			$this->db->select('DISTINCT (l.id) as id')->from("leads as l");
			$this->db->join("leads_assigned as la", "l.id = la.lead_id");
			$this->db->where("l.lead_zoho_id is not null 
                and l.lead_by_post_user_name in ('Zoho CRM', 'Zoho Missed', 'Zoho AOH', 'Zoho Chats', 'Zoho Social')
                and l.lead_parent_id = 0 and l.lead_status in (602, 609) and l.lead_status not in (630)
                and TIMESTAMPDIFF(HOUR, l.lead_added_on, NOW()) > 1 and l.lead_added_on > '2025-01-08'
                and (l.sla_response_time_status != 'Not initiated within a hour' or l.sla_response_time_status is null)");	
			$this->db->group_by('l.id');
			$this->db->order_by('l.id', 'DESC');

			$result = $this->db->get()->result_array();

			$leads_array = [];
			foreach ($result as $data) {
				array_push($leads_array, $data['id']);
				$update_sla_flag_array = ['sla_response_time_status' => 'Not initiated within a hour',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
				$zoho_response_sla_email = $this->zoho_res_time_sla_email($data['id'], 'ReponseTime');
			}

			return $this->response(["status" => true, "data" => $leads_array, "message" => "Email Send Successfully"]);
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

    public function zoho_res_time_sla_email($lead_id, $type)
	{
		$sla_lead_subject = "SLA Violation for the 'Zoho Lead' with Lead #" . $lead_id;
		$sla_lead_message = "<br>Dear Team<br>";
		$sla_lead_message .= "<br><br>We regret to inform you that there has been a violation of our Service Level Agreement (SLA) for the Lead #" . $lead_id;

		$sla_lead_message .= "<br><br><strong>Details of the Violation:</strong><br>";
		$sla_lead_message .= "<br>LEAD ID: " . $lead_id;
		if($type == 'ReponseTime'){
			$sla_lead_message .= "<br>Violation Reason: Lead Not Initiated within a hour";
		} else if($type == 'RequestTime'){
			$sla_lead_message .= "<br>Violation Reason: Lead Not Complted within 2 days";
		} 

		$sla_lead_message .= "<br><br>Thank you for your understanding and continued trust in Ontime CRM";

		$assigned_to_id = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');
		$assigned_to_email =  $this->mcommon->specific_row_value('users', array('user_id' => $assigned_to_id), 'email');

		// $cc_usermail = [];
		// array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
		// array_push($cc_usermail, ["email" => "dravidan.v@mitrahsoft.in", "name" => "Dravidan"]);

		// $bcc_usermail = [];
		// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		$email_array = array(
			'email' => $assigned_to_email, 
			// 'cc' => $cc_usermail,
			// "bcc" => $bcc_usermail,
			'subject' => $sla_lead_subject,
			'template' => 'mails/template',
			'from_name' => "Ontime CRM - Zoho Leads",
			'message' => $sla_lead_message,
		);
		$send_mail = send_template_email($email_array);
		log_message('error', $send_mail);
	}

	public function zoho_resolution_time_sla_violation_post()
	{
		try {
			$this->db->select('DISTINCT (l.id) as id')->from("leads as l");
			$this->db->join("leads_assigned as la", "l.id = la.lead_id");
			$this->db->where("l.lead_zoho_id IS NOT NULL AND l.lead_parent_id = 0
                AND l.lead_by_post_user_name in ('Zoho CRM', 'Zoho Missed', 'Zoho AOH', 'Zoho Chats', 'Zoho Social')
                AND l.lead_status NOT IN (305,306, 606, 623, 624, 630) AND DATEDIFF(NOW(), l.lead_added_on) > 2
				AND l.lead_added_on > '2025-01-08'
                AND (l.sla_resolution_time_status != 'Not completed within 2 days' or l.sla_resolution_time_status is null)");	
			$this->db->group_by('l.id');
			$this->db->order_by('l.id', 'DESC');

			$result = $this->db->get()->result_array();

			$leads_array = [];
			foreach ($result as $data) {
				array_push($leads_array, $data['id']);
				$update_sla_flag_array = ['sla_resolution_time_status' => 'Not completed within 2 days',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
				$zoho_response_sla_email = $this->zoho_res_time_sla_email($data['id'], 'RequestTime');
			}

			return $this->response(["status" => true, "data" => $leads_array, "message" => "Email Send Successfully"]);
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	public function convert_next_contactable_leads_to_active_post(){
        $this->db->select("
            l.id,
            IF(lu.first_name IS NOT NULL, `lu`.`first_name`,lu.last_name) AS customer_name,
            lu.mobile customer_mobile,
            DATEDIFF(l.contactable_date, now()) remain_days,
            l.contactable_date,
            u.first_name as assigned_user,
            u.email as assigned_email
        ");
        $this->db->from("leads l");
        $this->db->join("lead_users lu",'lu.user_id = l.customer_id');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        $this->db->join("users u",'u.user_id = la.assigned_to','left');
        $this->db->where_in("l.lead_status", [630]);
        $this->db->where("l.lead_parent_id", 0);
        $this->db->where("l.contactable_date >=", date('Y-m-d H:i:s'));
        $contactable_leads = $this->db->get()->result_array();

        if(!empty($contactable_leads)){
            foreach ($contactable_leads as $key => $value) {
                $today = date("Y-m-d");
                $contactable_date = date('Y-m-d', strtotime($value['contactable_date']));

                if($today == $contactable_date){
                    $lead_id = $value['id'];

                    $update_lead_array = array('lead_status' => 303);
                    $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));

                    $subject = "Contactable Date for Lead #" . $lead_id;
                    $message = "<br>Dear ".$value['assigned_user'];
                    $message .= "<br><br>This is to inform you that today(".$today."), is the scheduled contactable date for the Lead #" . $lead_id;
                    $message .= "<br><br><strong>Details of the Lead:</strong><br>";
                    $message .= "<br>LEAD ID: " . $lead_id;
                    $message .= "<br>Customer Name: " . $value['customer_name'];
                    $message .= "<br>Customer Mobile: " . $value['customer_mobile'];

                    $message .= "<br><br>Thank you for your understanding and continued trust in Ontime Biz";

                    $cc_usermail = [];

                    $email_array = array(
                        'email' => $value['assigned_email'],
                        // 'cc' => $cc_usermail,
                        'subject' => $subject,
                        'template' => 'mails/template',
                        'from_name' => "CRM ALERT",
                        'message' => $message,
                    );

                    $send_mail = send_template_email($email_array);
                   echo '<pre>';print_r('done');
                }
            }
        }
    }

	public function create_user_get(){
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle($str_result), 0, 10);
        $random_email_name = strtolower($random_string);
        $random_email = $random_email_name . '@ontimecustomer.com';
        $lead_email =  "Bader.jaballah@gmail.com";	// $random_email;	// Change email here
        $password = 'Welcome@123';
        $confirm_password = 'Welcome@123';
        $auth_level = '1';
        $referal_code = $random_string;
        $user_hashed_password = $this->authentication->hash_passwd($password);
        $user_data = [
            'auth_level' => $auth_level,
            'mobile' => '545867334',	// Change email here
            'referal_code' => $referal_code,
            'country' => 'United Arab Emirates',	// Change country here
            'first_name' => "BADR JABALLAH",	//$random_email_name,	// Change name here
            'passwd' => $user_hashed_password,
            'email' => $lead_email,
            'confirm_password' => $user_hashed_password,
        ];
        $user_data['user_id'] = $this->authentication_model->get_unused_id();
        $user_data['created_at'] = date('Y-m-d H:i:s');
        $user_data['otp'] = rand(1000, 9000);
        $user_data['email_otp'] = rand(1000, 9000);
        $user_data['banned'] = '0';
        $user_data['role_id'] = '4';
        $user_data['nationality'] = 'United Arab Emirates';	// Change country here
        $user_data['country_code'] = '+971'; //'+971';


        $insert = $this->mcommon->common_insert("lead_users", $user_data);
        var_dump($insert);exit;
    }

	public function create_lead_user($name, $email, $mobile){
		$check_lead_user = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile,'email' => trim($email),'first_name' => trim($name)));
		if($check_lead_user > 0){
			return true;
		}
		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle($str_result), 0, 10);
        $random_email_name = strtolower($random_string);
        $random_email = $random_email_name . '@ontimecustomer.com';
        $lead_email =  $email;	// $random_email;	// Change email here
        $password = 'Welcome@123';
        $confirm_password = 'Welcome@123';
        $auth_level = '1';
        $referal_code = $random_string;
        $user_hashed_password = $this->authentication->hash_passwd($password);
        $user_data = [
            'auth_level' => $auth_level,
            'mobile' => $mobile,	// Change email here
            'referal_code' => $referal_code,
            'country' => 'United Arab Emirates',	// Change country here
            'first_name' => $name,	//$random_email_name,	// Change name here
            'passwd' => $user_hashed_password,
            'email' => $lead_email,
            'confirm_password' => $user_hashed_password,
        ];
        $user_data['user_id'] = $this->authentication_model->get_unused_id();
        $user_data['created_at'] = date('Y-m-d H:i:s');
        $user_data['otp'] = rand(1000, 9000);
        $user_data['email_otp'] = rand(1000, 9000);
        $user_data['banned'] = '0';
        $user_data['role_id'] = '4';
        $user_data['nationality'] = 'United Arab Emirates';	// Change country here
        $user_data['country_code'] = '+971'; //'+971';


        $insert = $this->mcommon->common_insert("lead_users", $user_data);
		return $insert;
	}

	public function extract_csv_data_post()
    {
        if (empty($_FILES['data_file']['name'])) {
            echo json_encode([
                'success' => false,
                'message' => 'CSV file is required'
            ]);
            return;
        }
 
        $csv = array_map('str_getcsv', file($_FILES['data_file']['tmp_name']));
        if (count($csv) <= 1) {
            echo json_encode([
                'success' => false,
                'message' => 'CSV file has no data'
            ]);
            return;
        }
 
        // Normalize headers
        $headers = array_map(function ($h) {
            return strtolower(trim($h));
        }, $csv[0]);
 
        array_shift($csv);
 
        $successCount = 0;
        $failed = [];
		// echo '<pre>';print_r($csv); //exit;
        foreach ($csv as $index => $row) {
 
            if (count($row) !== count($headers)) {
                $failed[] = [
                    'row' => $index + 2,
                    'reason' => 'Column mismatch'
                ];
                continue;
            }
 
            $data = array_combine($headers, $row);
			// echo '<pre>';print_r($data); exit;
 
            $name   = trim($data['cust_engname'] ?? '');
            $email  = trim($data['cust_email'] ?? '');
            $mobile = trim($data['cust_mobile'] ?? '');
 
            // Basic validation
            if ($name === '' || ($email === '' && $mobile === '')) {
                $failed[] = [
                    'row' => $index + 2,
                    'reason' => 'Name and (Email or Mobile) required'
                ];
                continue;
            }

			if (substr($mobile, 0, 1) === '0') {
				$mobile = substr($mobile, 1);
			}
 
            // Call your function
            $result = $this->create_lead_user($name, $email, $mobile);
 
            if ($result === true || (is_array($result) && ($result['status'] ?? false))) {
                $successCount++;
            } else {
                $failed[] = [
                    'row' => $index + 2,
                    'reason' => is_array($result) ? ($result['message'] ?? 'Create user failed') : 'Create user failed'
                ];
            }
        }
 
        echo json_encode([
            'success' => true,
            'created' => $successCount,
            'failed'  => count($failed),
            'errors'  => $failed
        ]);
    }

	public function resource_violation_leads_email($lead_id)
	{
		$lead_det = $this->leads_model->lead_details($lead_id);
		$lead_assigned_to = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');
		$assigned_user_detail = $this->mcommon->specific_row('users', array('user_id' => $lead_assigned_to));

		$lead_subject = "Resource Violation for the " . $lead_det["customer_name"] . " with Lead #" . $lead_id;
		$lead_message = "<br>Dear Team<br>";
		$lead_message .= "<br><br>We regret to inform you that there has been a violation of No Action against the Lead #" . $lead_id;

		$lead_message .= "<br><br><strong>Details of the Violation:</strong><br>";
		$lead_message .= "<br>Lead ID: " . $lead_det["id"];
		// $lead_message .= "<br>Reason: No Action 4 hours from the lead creation";

		$lead_message .= "<br><br>Thank you for your understanding and continued trust in OntimeGroup CRM";

		// $cc_usermail = [];
		// array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);
		// array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126

		$to_usermail = [];
		array_push($to_usermail, ["email" => $assigned_user_detail['email'], "name" => $assigned_user_detail['first_name']]);	// 2233119808

		// $bcc_usermail = [];
		// array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

		$email_array = array(
			'email' => $to_usermail,
			// 'cc' => $cc_usermail,
			// "bcc" => $bcc_usermail,
			'subject' => $lead_subject,
			'template' => 'mails/template',
			'from_name' => "CRM ALERT",
			'message' => $lead_message,
		);
		$send_mail = send_template_email($email_array);
		log_message('error', $send_mail);
	}

	public function resource_violation_leads_post()
	{
		try {
			$this->db->select('DISTINCT (l.id) as id')->from("leads as l");
			$this->db->join("lead_action_log AS lal", "l.id = lal.lead_id");
			$this->db->where("l.created_at > (NOW() - INTERVAL 4 HOUR)
  				AND l.id NOT IN (SELECT lead_id FROM lead_action_log WHERE status_id in (304, 307, 310, 311))
  				AND l.lead_status not in (305,623,306,606,624, 630) AND l.lead_parent_id = 0
				AND (l.sla_resource_violation_status != 'red' or l.sla_resource_violation_status is null)");	
			$this->db->group_by('l.id');
			$this->db->order_by('l.id', 'DESC');

			$result = $this->db->get()->result_array();

			$leads_array = [];
			foreach ($result as $data) {
				array_push($leads_array, $data['id']);
				$update_sla_flag_array = ['sla_resource_violation_status' => 'red',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
				$sub_lead_pending_email = $this->resource_violation_leads_email($data['id']);
			}

			return $this->response(["status" => true, "data" => $leads_array, "message" => "Email Send Successfully"]);
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	public function ccleadcreate_get() {
		$data = $this->db->select("*")->from("contacts_callcenter")->limit(150,5701)->get()->result_array();
        foreach($data as $key => $value) {
			// var_dump($value['cust_engname']);exit;

            $test = $this->create_lead_cc($value['cust_engname'], $value['cust_mobile'], $value['cust_email'], $value['cust_key']);
        }
		// return $this->response(["status" => true, "message" => "success"]);
    }

    public function create_lead_cc($lead_name, $lead_contact="", $lead_email, $cust_id)
    {
        $branch_id = 123;

        $lead_type = 'normal';
        if ($lead_type == 'normal') {
            $category_id = 109;
            $service_id = 1009;
        }


        // $lead_name = $this->input->post('lead_name');
        // $lead_contact = $this->input->post('lead_contact');
        // $lead_email = $this->input->post('email');
        $lead_remarks = 'call-center contacts';
        $lead_country_code = '+971';
        // $cust_id = ($this->input->post('cust_id')) ? $this->input->post('cust_id') : null;
        $lead_source = 'cc-contact';

		$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
		$random_string = substr(str_shuffle($str_result), 0, 10);

            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($lead_email == '') ? $random_email : $lead_email;
            // $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, $cust_id);
			$is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

			if ($is_exist != 0) {
				$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
			}


			if ($is_exist == 0) {
				$password = 'Welcome@123';
				$confirm_password = 'Welcome@123';
				$auth_level = '1';
				$referal_code = $random_string;
				$user_hashed_password = $this->authentication->hash_passwd($password);
				$user_data = [
					'auth_level' => $auth_level,
					'mobile' => $lead_contact,
					'referal_code' => $referal_code,
					'first_name' => $lead_name,
					'passwd' => $user_hashed_password,
					'email' => trim($lead_email),
					'confirm_password' => $user_hashed_password,
				];
				$user_data['user_id'] = $this->authentication_model->get_unused_id();
				$user_data['created_at'] = date('Y-m-d H:i:s');
				$user_data['otp'] = rand(1000, 9000);
				$user_data['email_otp'] = rand(1000, 9000);
				$user_data['banned'] = '0';
				$user_data['role_id'] = '4';
				$user_data['country'] = 'United Arab Emirates';
				$user_data['country_code'] = '+971';

				$insert = $this->mcommon->common_insert("lead_users", $user_data);

				$user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
				// return $user_id;
			}

            if ($user_id != 0) {
                //process lead type
                if ($lead_type == 'normal' || $lead_type == 'package') {
                    $normal_lead_count = 0;
                    $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 2113278237, 'is_primary_group_id' => 1), 'group_id');
                    $insert_lead_array = array(
                        'customer_id' => $user_id,
                        'branch_id' => $branch_id,
                        'category_id' => $category_id,
                        'service_id' => $service_id,
                        'lead_created_by' => 2113278237, // cc@ontimegroup.com
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 301,
                        'package_id' => 0,
                        'order_receipt' => 0,
                        'remarks' => $lead_remarks,
                        'is_assigned' => 0,
                        'pos_cust_key' => $cust_id,
                        'lead_source' => $lead_source,
                        'created_group_id' => $created_group_id,
                    );
                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                    $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created ', 'action_by' => 2113278237, 'status_id' => 301);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $normal_lead_count = 1;


                    $postData = array(
                            'lead_id' => $insert_lead_id,
                        );

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Accept: application/json',
                            'Content-Type: application/json'
                        ]);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                        curl_setopt($ch, CURLOPT_HEADER, false); 
                        curl_setopt($ch, CURLOPT_POST, true);  
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                        $response = curl_exec($ch);
                        if(!empty($response))
                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

                        echo $normal_lead_count.'-'.$insert_lead_id;
                }

                
            } else {
                // $this->session->set_flashdata('alert', 'danger');
                echo 'Unable to create lead. There exist a problem in creating customer record. Please try again.'.$lead_name;
            }
    }
	
	// public function update_completed_leads_with_pos_data_post()
	// {
	// 	try {
	// 		// Step 1: Fetch leads
	// 		// $this->db->select('id, order_receipt')  // get id + invoice number
	// 		// ->from('leads')
	// 		// ->where('lead_status', 305)
	// 		// ->where('lead_parent_id !=', 0)
	// 		// ->group_start()
	// 		// 	->where('ref1 IS NULL')
	// 		// 	->or_where('ref1', '')
	// 		// ->group_end()
	// 		// ->group_start()
	// 		// 	->where('ref2 IS NULL')
	// 		// 	->or_where('ref2', '')
	// 		// ->group_end()
	// 		// ->where('DATE(lead_added_on) >=', '2024-10-01')
	// 		// ->where('DATE(lead_added_on) <=', '2025-09-21')
	// 		// ->where('order_receipt IS NOT NULL')   // not null
	// 		// ->where('order_receipt !=', '')        // not empty
	// 		// ->where('order_receipt !=', '0')         // not zero
	// 		// ->order_by('id', 'DESC');
			
	// 		$this->db->select('*')
	// 		->from('leads')
	// 		->where('lead_status', 305)
	// 		->where('lead_parent_id', 0)
	// 		->group_start()
	// 		    ->where('ref1 IS NULL')
	// 		    ->or_where('ref1', '')
	// 		->group_end()
	// 		->group_start()
	// 		    ->where('ref2 IS NULL')
	// 		    ->or_where('ref2', '')
	// 		->group_end()
	// 		->where('DATE(lead_added_on) >=', '2024-10-01')
	// 		->where_not_in('id', '(SELECT lead_parent_id FROM leads)', FALSE) // subquery
	// 		->group_start()
	// 		    ->where('order_receipt IS NOT NULL')   // not null
	// 			->where('order_receipt !=', '')        // not empty
	// 			->where('order_receipt !=', '0')         // not zero
	// 		->group_end()
	// 		->order_by('order_receipt', 'ASC');



	// 		$query  = $this->db->get();
	// 		$leads  = $query->result();

	// 		if (empty($leads)) {
	// 			return $this->response([
	// 				"status"  => true,
	// 				"message" => "No leads found for update."
	// 			]);
	// 		}

	// 		$not_updated = [];
	// 		$updated     = [];

	// 		// Step 2: Loop through each lead
	// 		foreach ($leads as $lead) {
	// 			$lead_id    = $lead->id;
	// 			$invoice_no = $lead->order_receipt;

	// 			if (empty($invoice_no)) {
	// 				$not_updated[] = $lead_id;
	// 				continue;
	// 			}

	// 			// Step 3: Call external POS API
	// 			$payload = json_encode([
	// 				"lead_id"    => $lead_id,
	// 				"invoice_no" => $invoice_no
	// 			]);

	// 			$ch = curl_init();
	// 			curl_setopt_array($ch, [
	// 				CURLOPT_URL            => 'https://ontimesmartpos.net/api/ApiPos/ValidateInvoiceForCRM',
	// 				CURLOPT_RETURNTRANSFER => true,
	// 				CURLOPT_ENCODING       => '',
	// 				CURLOPT_MAXREDIRS      => 10,
	// 				CURLOPT_TIMEOUT        => 0,
	// 				CURLOPT_FOLLOWLOCATION => true,
	// 				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
	// 				CURLOPT_CUSTOMREQUEST  => 'POST',
	// 				CURLOPT_POSTFIELDS     => $payload,
	// 				CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
	// 			]);

	// 			$response = curl_exec($ch);
	// 			curl_close($ch);

	// 			$apiResp = json_decode($response, true);
				

	// 			// Step 4: Check API response
	// 			if (!$apiResp || !isset($apiResp['ResponseCode'])) {
	// 				$not_updated[] = $lead_id;
	// 				continue;
	// 			}

	// 			if ($apiResp['ResponseCode'] == 1) {
	// 				// Failed
	// 				$not_updated[] = $lead_id;
	// 			} elseif ($apiResp['ResponseCode'] == 0 && isset($apiResp['Data'])) {
	// 				// Success → update DB
	// 				$data = $apiResp['Data'];

	// 				$update_lead_array = [
	// 					"pos_GovtFees"   => $data['govt_fee'],
	// 					"pos_TypeFee"    => $data['typing_fee'],
	// 					"pos_Card_Amnt"  => $data['Card_Amnt'],
	// 					"pos_Disc_Amnt"  => $data['Disc_Amnt'],
	// 					"pos_Tax_Amnt"   => $data['Tax_Amnt'],
	// 					"pos_Tot_Amt"	 => $data['Tot_Amt'],
	// 					"pos_Tot_Revn"   => $data['Tot_Revn'],
	// 					"ref1"           => $data['Ref1'],
	// 					"ref2"           => $data['Ref2'],
	// 					"pos_username"   => $data['pos_username'],
	// 					"pos_cust_key"	 => $data['Cust_Key']
	// 				];

	// 				$update = $this->mcommon->common_edit('leads', $update_lead_array, ['id' => $lead_id, 'order_receipt' => $invoice_no]);
	// 				$updated[] = $lead_id;
	// 			} else {
	// 				$not_updated[] = $lead_id;
	// 			}

	// 			// var_dump(["updated" => $updated, "not_updated"  => $not_updated, "lead_id"  => $lead_id, "invoice_no"  => $invoice_no]);
	// 			// exit;
	// 		}

	// 		// Step 5: Return result
	// 		return $this->response([
	// 			"status"       => true,
	// 			"updated"      => $updated,
	// 			"not_updated"  => $not_updated,
	// 			"message"      => "Process completed. Updated: " . count($updated) . ", Not updated: " . count($not_updated)
	// 		]);
	// 	} catch (Exception $e) {
	// 		return $this->response([
	// 			"status"  => false,
	// 			"message" => "Something went wrong: " . $e->getMessage()
	// 		]);
	// 	}
	// }
// public function export_csv_updated_report_get()
// {
//     $this->db->select("
//         id, 
//         order_receipt,
//         lead_parent_id,
//         pos_GovtFees,
//         pos_TypeFee,
//         pos_Card_Amnt,
//         pos_Disc_Amnt,
//         pos_Tax_Amnt,
//         pos_Tot_Amt,
//         pos_Tot_Revn,
//         ref1,
//         ref2,
//         pos_username,
//         pos_cust_key
//     ");
//     $this->db->from("leads");

//     $this->db->where("lead_status", 305);
//     $this->db->where("lead_parent_id", 0);
// 	 $this->db->where_not_in('id', '(SELECT lead_parent_id FROM leads)', FALSE);
//     $this->db->where("updated_at >=", "2025-09-21 10:03:28");

//     $data = $this->db->get()->result_array();

//     $this->export_to_csv($data, 'lead_export_' . date('Ymd_His') . '.csv');
// }

// private function export_to_csv($data, $filename = "export.csv")
// {
//     header('Content-Type: text/csv');
//     header("Content-Disposition: attachment; filename=\"$filename\"");
//     header("Pragma: no-cache");
//     header("Expires: 0");

//     if (empty($data)) {
//         echo "No data available.";
//         exit;
//     }

//     // Define the exact columns in the same order as query
//     $headers = [
//         "id",
//         "order_receipt",
//         "lead_parent_id",
//         "pos_GovtFees",
//         "pos_TypeFee",
//         "pos_Card_Amnt",
//         "pos_Disc_Amnt",
//         "pos_Tax_Amnt",
//         "pos_Tot_Amt",
//         "pos_Tot_Revn",
//         "ref1",
//         "ref2",
//         "pos_username",
//         "pos_cust_key"
//     ];

//     $output = fopen("php://output", "w");

//     // Write header row
//     fputcsv($output, $headers);

//     // Write data rows
//     foreach ($data as $row) {
//         $csv_row = [];
//         foreach ($headers as $col) {
//             $csv_row[] = isset($row[$col]) ? $row[$col] : '';
//         }
//         fputcsv($output, $csv_row);
//     }

//     fclose($output);
//     exit;
// }

	public function investor_package_send_email_cust_post(){
		try {

			$this->db->select('l.id as id, lu.email, lu.first_name');
			$this->db->from("leads as l");
			$this->db->join("ontime_category_services_ as ocs", "ocs.service_id = l.service_id");
			$this->db->join("lead_packages as lp", "lp.package_id = l.package_id and lp.package_type = 'investor'");
			$this->db->join("lead_package_services as lps", "l.package_id = lps.package_id");
			$this->db->join("lead_action_log as lal", "lal.lead_id = l.id and lal.action_id = 449");
			$this->db->join("lead_users as lu", "lu.user_id = l.customer_id");
			$this->db->where("ocs.gc_service_id", 2);
			$this->db->where_in("l.package_id", [68,115,116, 153,157,158, 72,119,120, 155,161,162]);
			$this->db->where("l.lead_parent_id != 0 and l.branch_id = 106 and l.lead_status in (305) and l.investor_email_sent is NULL");
			$this->db->where('lal.created_at <= DATE_SUB(NOW(), INTERVAL 30 MINUTE)', null, false);
			$this->db->where("l.task_status", 'completed');
			$this->db->where("l.created_at >=", '2026-01-01');
			$this->db->group_by('l.id');
			$this->db->order_by('l.id', 'DESC');

			$result = $this->db->get()->result_array();

			$leads_array = [];

			foreach ($result as $data) {
				array_push($leads_array, $data['id']);
				$update_sla_flag_array = ['investor_email_sent' => '1',];
				$this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $data['id']]);
				$gc_cus_lead_message = array(
					'customer_name' => $data["first_name"],
				);

				$email_array = array(
					'email' => $data['email'],
					// 'cc' => $cc_usermail,
					'cc' => [["email"=>'Team@goldencube.ae']],
					'subject' => 'You Are Now Eligible to Sponsor Your Family',
					'template' => 'mails/gc_after_investor_comp',
					'from_name' => "Golden Cube",
					'message' => $gc_cus_lead_message,
					'branch_id' => '106',
				);
				$send_mail = send_template_email($email_array);
			}
			

			return $this->response(["status" => true, "data" => $leads_array, "message" => "Email Send Successfully"]);
		} catch (Exception $e) {
			return $this->response(["status" => false, "message" => "Something went wrong."]);
		}
	}

	
}


