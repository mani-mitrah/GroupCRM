<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Property extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->helper('queue');
		$this->load->model('order_model');
		$this->load->model('user_model');
		$this->load->model('authentication_model');
	}

	public function list_services()
	{
		if ($this->verify_min_level(1)) {
			$list = $this->db->select("id, service_name as text")->from("ontime_category_services_")->where("category_id", 113)->get()->result_array();
			echo json_encode($list);
		}
	}

	public function addService()
	{
		// print_r($_POST);
		if (isset($_POST["service_name"]) && $_POST["service_name"] != NULL) {
			$category_id = 113;
			$service = $_POST["service_name"];
			$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

			if ($service_exist) {
				$service_id = $service_exist["id"];
				echo json_encode(["id" => $service_id, "text" => $service, "status" => "exist"]);
			} else {
				$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
				$service_id = (int)$service_id;
				$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id, "order_status" => "created", "assigned_to" => 3580346557], ["id" => $service_id]);
				echo json_encode(["id" => $service_id, "text" => $service, "status" => "created"]);
			}
			return;
		} else {
			echo json_encode(["status" => "error", "message" => "Service name missing"]);
		}
	}

	public function new()
	{
		if ($this->verify_min_level(1)) {
			if ($this->auth_user_role == 2) {

				if (isset($_POST["created_by"])) {
					// echo json_encode($_POST);
					$req = $_POST;
					$service = $this->db->select("service_name")->from("ontime_category_services_")->where("id=", $req["service_id"])->get()->first_row();
					// print_r($service->service_name);
					$req["service_name"] = $service->service_name;

					$customer = $this->mcommon->common_insert("property_customers", $req);
					if ($customer == 0) {
						if ($this->db->error()) {
							$err = $this->db->error();
							if ($err->code == 1054) {

								echo json_encode(["status" => "Exception Occured", "message" => json_encode($err)]);
							}
						} else {
							echo json_encode(["status" => "Exception Occured", "message" => "Something went wrong"]);
						}
						return;
					}
					$name = $req["customer_name"];
					$mobile = $req["customer_mobile"];
					$email = $req["customer_email"];
					$token = create_token($name, $mobile, $email);
					$token_number = $token->Content;
					// exit();
					// $token_number = 1000 + $customer;
					$token = $this->mcommon->common_edit("property_customers", ["token" => $token_number, "token_api" => json_encode($token)], ["customer_id" => $customer]);

					echo json_encode(["status" => "true", "message" => "Token: " . $token_number]);

					return;
				}

				$list = $this->db->select("id, service_name as text")->from("ontime_category_services_")->where("category_id", 113)->get()->result_array();
				$view_data["services"] = $list;
				$data = array(
					'page_title' => 'Property Registration - Customer',
					'title' => 'Customer Registration',
					'content' => $this->load->view('pages/orders/property/customer_registration', $view_data, TRUE),
				);
				$this->load->view('template/base_template_v2', $data);
			} else {
				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'You no have access for this page.');
				redirect('/property');
			}
		}
	}

	public function index()
	{
		if ($this->verify_min_level(1)) {
			if ($this->auth_user_role == 2) {

				return redirect("/orders/property/new");
			}

			if ($_POST["lead"] || $_POST["sales"]) {
				if ($_POST["sales"]) {

					$customer = $this->db->select("*")->from("property_customers")->where("customer_id", $_POST["customer_id"])->get()->first_row();

					// print_r($_POST);
					// exit();
					try {

						$pcr_id = "OTDEPROP" . $_POST["customer_id"];
						if ($customer->order_status != "order") {

							// print_r($pcr);
							// exit();
							$curl = curl_init();

							curl_setopt_array($curl, [
								CURLOPT_URL => 'http://185.56.89.101:1001/api/Authentication/Login',
								CURLOPT_RETURNTRANSFER => true,
								CURLOPT_ENCODING => '',
								CURLOPT_MAXREDIRS => 10,
								CURLOPT_TIMEOUT => 0,
								CURLOPT_FOLLOWLOCATION => true,
								CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
								CURLOPT_CUSTOMREQUEST => 'POST',
								CURLOPT_POSTFIELDS => '{
												"username": "CRMONLINE",
												"password": "2424"
												}',
								CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
							]);

							$response = curl_exec($curl);

							curl_close($curl);

							$token = json_decode($response);
							$token_status = $token->isSuccess;
							if ($token_status) {
								$token = $token->data->token;
								$req = [];
								$req['customer']['name'] = $customer->customer_name;
								$req['customer']['phoneNumber'] = $customer->customer_mobile;
								$req['customer']['email'] = $customer->customer_email;

								$req['sloHeader'] = [
									'remarks' => 'OTDEPROP-' . $pcr_id . ' - ' . $customer->service_name,
									'isEDirham' => false,
									'tokenNo' => '',
									'is_CrossSales' => false,
									'is_OnlineSales' => true,
									'ordeRefOnline' => "OTDEPROP" . $pcr_id,
								];

								$req['sloDetail'] = [];

								$govt_fee = 0;

								$actual_amount = $_POST['service_amount'] - $govt_fee;
								$tax = $actual_amount / 105;
								$actual_amount = $actual_amount - $tax;
								$tmp_details = [
									'servicename' => $_POST["service_name"],
									'servicenamear' => $_POST["service_name"],
									'fees' => 0,
									'typing_Amnt' => 0,
									'services_Amnt' => 0,
									'disc_Amnt' => 0,
									'edirham_Amnt' => 0,

									'addFees_Amnt' => $govt_fee,
									'addRev_Amnt' => $actual_amount,
									'tax_Amnt' => $tax,
									'ref_no' => 'OTDEPROP-' . $pcr_id . '-' . $customer->service_id,
									'ref_no1' => $customer->created_by,
									'card_Amnt' => 0,
								];
								array_push($req['sloDetail'], $tmp_details);

								$req['sloPayments'] = [
									'ref_No' => 'OTDEPROP-' . $pcr_id,
									'amount' => $customer->service_amount,
									'remarks' => $customer->service_name . '-' . $customer->service_id,
									'token' => $customer->token,
									'interCompany' => true,
									'actualAmt' => $customer->service_amount,
									'cheque_Date' => '',
									'eidNumber' => '',
								];

								// print_r(json_encode($req));
								// return;

								$curl = curl_init();

								curl_setopt_array($curl, [
									CURLOPT_URL => 'http://185.56.89.101:1001/api/SmartOrder2022/Order',
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_ENCODING => '',
									CURLOPT_MAXREDIRS => 10,
									CURLOPT_TIMEOUT => 0,
									CURLOPT_FOLLOWLOCATION => true,
									CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									CURLOPT_CUSTOMREQUEST => 'POST',
									CURLOPT_POSTFIELDS => json_encode($req),
									CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Type: application/json'],
								]);

								$response = curl_exec($curl);

								curl_close($curl);
								// echo $response;
								// echo $response;
								// exit();
								$json_response = json_decode($response);
								// echo $response;
								if ($json_response->isSuccess == true || $json_response->isSuccess == "true") {

									$invoice = $json_response->data->sliHeader->docPrefix . $json_response->data->sliHeader->headnum;

									// $update = $this->mcommon->common_edit('property_customers', ['order_status' => "order", 'csa_remarks' => $_POST["csa_remarks"], 'service_amount' => $_POST['service_amount']], ['customer_id' => $pcr_id]);

									// print_r($update);
									// $this->mcommon->common_edit('pcr_order_items', ['is_completed' => 1], ['pcr_order_id' => $pcr_id]);

									// $this->db->where(["pcr_order_id" => $pcr_id])->update("pcr_order",["pcr_status"=>1,"is_completed"=>1,"so_api_response"=>$response,"so_api_called_at"=>date("Y-m-y H:i:s"),"so_api_called_by"=>$this->auth_user_id,"so_invoice"=> $json_response->data->sliHeader->docPrefix.$json_response->data->sliHeader->cust_Key]);
									// $this->db->where(["pcr_order_id" => $pcr_id])->update("pcr_order_items",["is_completed"=>1]);

									// echo json_encode(['status' => 'true', 'message' => 'Property registration Order marked as completed and Invoice Generated.', 'request_json' => 
									complete_token($_POST["token_id"], "complete");

									$update = $this->mcommon->common_edit("property_customers", ["order_status" => "order", "service_id" => $_POST["service_id"], "service_name" => $_POST["service_name"], "service_amount" => $_POST["service_amount"], 'soi_api_response' => $response, 'invoice_ref' => $invoice], ["customer_id" => $_POST["customer_id"]]);
									// print_r($update);
									$this->session->set_flashdata('alert', 'success');
									$this->session->set_flashdata('alert_message', 'Property registration Order marked as completed and Invoice #' . $invoice . ' Generated.');


									return redirect('/orders/property');
									// json_encode($req)]);
									exit();
								} else {
									// echo "Failed";
									// echo $response;
									echo json_encode(['status' => 'false', 'message' => 'Something Went Wrong. Please try after sometime.', 'request_json' => json_encode($req), 'api_response' => $response]);

									$this->session->set_flashdata('alert', 'danger');
									$this->session->set_flashdata('alert_message', 'Something went wrong on Invoice creation. Please contact system administrator.');
									return redirect('/orders/property');
									exit();
								}
							} else {
								$this->session->set_flashdata('alert', 'warning');
								$this->session->set_flashdata('alert_message', 'Authentication Failed. Contact System Administrator.');
								return redirect('/orders/property');

								echo json_encode(['status' => 'false', 'message' => 'Authentication Failed. Contact System Administrator.']);
								exit();
							}
						} else {
							$this->session->set_flashdata('alert', 'warning');
							$this->session->set_flashdata('alert_message', 'Property registration Order already marked as completed.');
							return redirect('/orders/property');
							// echo json_encode(['status' => 'true', 'message' => 'Property registration Order already marked as completed.']);
							// exit();
						}
						// print_r($pcr);
						// echo json_encode(["status"=>"true","message"=>"PCR Order marked as completed and Invoice Generated."]);
					} catch (Exception $e) {
						// echo json_encode(['status' => 'false', 'message' => 'Exception Occured. Contact System Administrator.', 'exception' => $e->getMessage()]);
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Something went wrong. Please try again or contact administrator...');
						return redirect('/orders/property');
						// exit();
					}

					// try {
					// 	if (isset($_GET['code'])) {
					// 		$pcr = $this->db
					// 			->select('*')
					// 			->from('pcr_order')
					// 			->where('pcr_order_id', $_GET['code'])
					// 			->get()
					// 			->first_row();

					// 		if ($pcr) {
					// 			// print_r($pcr);
					// 			$curl = curl_init();

					// 			curl_setopt_array($curl, [
					// 				CURLOPT_URL => 'https://verify.ontime-pos.com/POSInvoice/Login?username=admin',
					// 				CURLOPT_RETURNTRANSFER => true,
					// 				CURLOPT_ENCODING => '',
					// 				CURLOPT_MAXREDIRS => 10,
					// 				CURLOPT_TIMEOUT => 0,
					// 				CURLOPT_FOLLOWLOCATION => true,
					// 				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					// 				CURLOPT_CUSTOMREQUEST => 'GET',
					// 			]);

					// 			$response = curl_exec($curl);

					// 			curl_close($curl);
					// 			$res = json_decode($response);

					// 			$token = $res->Token;

					// 			$curl = curl_init();

					// 			$curl_url = 'https://verify.ontime-pos.com/POSInvoice/GetPaidStatus/' . $pcr->so_invoice;
					// 			// echo $curl_url;
					// 			// echo "<br>";
					// 			// echo $token;

					// 			curl_setopt_array($curl, [
					// 				CURLOPT_URL => $curl_url,
					// 				CURLOPT_RETURNTRANSFER => true,
					// 				CURLOPT_ENCODING => '',
					// 				CURLOPT_MAXREDIRS => 10,
					// 				CURLOPT_TIMEOUT => 0,
					// 				CURLOPT_FOLLOWLOCATION => true,
					// 				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					// 				CURLOPT_CUSTOMREQUEST => 'POST',
					// 				CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Length: 0'],
					// 			]);

					// 			// curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 50000'));



					// 			// curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=utf-8"));


					// 			// curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0'));


					// 			$response = curl_exec($curl);

					// 			curl_close($curl);
					// 			$res = json_decode($response);
					// 			// echo "<br>";
					// 			// echo "There: ".$response;
					// 			// print_r(curl_error($curl));
					// 			// exit();
					// 			if (!$res->Url) {
					// 				$this->session->set_flashdata('alert', 'danger');
					// 				$this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PCR Order');
					// 				return redirect('/orders/property/list#completed');
					// 			}
					// 			// print_r($res);

					// 			// if($res->Status)
					// 			header("location: " . $res->Url);

					// 			if (curl_error($curl)) {
					// 				// echo curl_error($curl);
					// 				$this->session->set_flashdata('alert', 'danger');
					// 				$this->session->set_flashdata('alert_message', 'Exception: ' . curl_error($curl));
					// 				return redirect('/orders/property/list#completed');
					// 			}
					// 		} else {
					// 			$this->session->set_flashdata('alert', 'danger');
					// 			$this->session->set_flashdata('alert_message', 'PCR Order doesnot exist for fetch invoice.');
					// 			return redirect('/orders/property/list#completed');
					// 		}
					// 	} else {
					// 		$this->session->set_flashdata('alert', 'danger');
					// 		$this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PCR Order');
					// 		return redirect('/orders/property/list#completed');
					// 	}
					// } catch (Exception $e) {

					// 	$this->session->set_flashdata('alert', 'danger');
					// 	$this->session->set_flashdata('alert_message', 'Something went wrong. Please try again or contact administrator.....');
					// 	// return redirect('/orders/property');
					// }






					// $this->mcommon->common_edit("property_customers", ["order_status" => "order"], ["customer_id" => $_POST["customer_id"]]);
				} else {

					complete_token($_POST["token_id"], "hold");

					$customer = $this->db->select("*")->from("property_customers")->where("customer_id", $_POST["customer_id"])->get()->first_row();
					// echo "<pre>";
					// // echo $this->auth_username;
					// print_r($customer);
					// echo "</pre>";
					// return;

					$branch_id = 100;
					$lead_type = "normal";
					$lead_name = $customer->customer_name;
					$lead_contact = $customer->customer_mobile;
					$lead_email = $customer->customer_email;
					$lead_remarks = $_POST["csa_remarks"];
					$lead_by_pos_user = $this->auth_user_id;
					$lead_by_post_user_name = $this->auth_username;



					$str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
					$random_string = substr(str_shuffle($str_result), 0, 10);


					$category_id = 113;
					$service = $customer->service_name;
					$service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

					if ($service_exist) {
						$service_id = $service_exist["id"];
					} else {
						$service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
						$service_id = (int)$service_id;
						$update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
					}

					$check_mobile_exists = 0;
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

						$user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
						//return $user_id;
					}

					if ($user_id != 0) {
						$uploaded_file_name = '';
						// else create one lead for selected category & service
						$lead_remarks = "<b>" . $_POST["service_name"] . "</b><br>" . $_POST["recep_remarks152"] . "<br><br>" . $_POST["csa_remarks"];

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
							'lead_by_post_user_name' => $lead_by_post_user_name
						);
						$inserts = $this->db->insert("leads", $insert_lead_array);
						// echo $this->db->last_query();
						// echo "Inserts==> ";
						// print_r($inserts);
						$lead_id = $this->db->insert_id();

						$normal_lead_count = 1;
						$this->session->set_flashdata('alert', 'success');
						$this->session->set_flashdata('alert_message', $normal_lead_count . ' lead(s) has been created.');
						$this->mcommon->common_edit("property_customers", ["order_status" => "lead"], ["customer_id" => $customer->customer_id]);
					} else {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
					}
				}
				// return;
			} else if ($_POST["skip"]) {
				$token_id = $_POST["token_id"];
				complete_token($token_id, "noshow");
				$this->mcommon->common_edit("property_customers", ["order_status" => "not_came"], ["customer_id" => $_POST["customer_id"]]);
			}

			$customer = $this->db->select("*")->from("property_customers")->where("order_status", "assigned")->where("assigned_to", $this->auth_user_id)->order_by("customer_id", "asc")->limit(1)->get()->first_row();

			// print_r($customer);
			// exit();
			if ($customer == NULL) {
				$called_token = json_decode(call_token());

				log_message('error', json_encode($called_token));
				// print_r($called_token);
				// exit();
				$token = $called_token->Content->TokenNo;

				if ($token == null) {
					$view_data["customer"] = NULL;
					$view_data["message"] = $called_token->Message;
					$data = array(
						'page_title' => 'Property Orders',
						'title' => 'Property Registration',
						'content' => $this->load->view('pages/orders/property/get_customer', $view_data, TRUE),
					);
					return $this->load->view('template/base_template_v2', $data);
				}
				$customer = $this->db->select("*")->from("property_customers")->where("order_status", "created")->where("token", $token)->order_by("customer_id", "asc")->limit(1)->get()->first_row();

				$this->mcommon->common_edit("property_customers", ["assigned_to" => $this->auth_user_id, "order_status" => "assigned", "token_api" => json_encode($called_token)], ["customer_id" => $customer->customer_id]);
			}

			// print_r($customer);
			// exit();
			$view_data["customer"] = $customer; //$this->order_model->property();
			$list = $this->db->select("id, service_name as text")->from("ontime_category_services_")->where("category_id", 113)->get()->result_array();
			$view_data["services"] = $list;
			$data = array(
				'page_title' => 'Property Orders',
				'title' => 'Property Registration',
				'content' => $this->load->view('pages/orders/property/get_customer', $view_data, TRUE),
			);
			return $this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function list()
	{
		$view_data = [];
		if ($this->verify_min_level(1)) {
			$view_data['order_details'] = $this->order_model->pr_orders();
			$data = [
				'page_title' => 'Property Registration Order List',
				'title' => 'Property Registration Order List',
				'content' => $this->load->view('pages/orders/property/list', $view_data, true),
			];
			return $this->load->view('template/base_template_v2', $data);
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
					'content' => $this->load->view('pages/orders/aladheed/unassigned_list', $view_data, TRUE),
				);
				return $this->load->view('template/base_template_v2', $data);
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
			$view_data['order_details'] = $this->order_model->aladheed_order_item_get($order_item_id);
			$view_data['order_statuses'] = $this->mcommon->specific_fields_records_all('otg_order_status', array('id>' => 101, 'is_active' => 1));

			$data = array(
				'page_title' => 'Orders',
				'title' => 'Orders',
				'content' => $this->load->view('pages/orders/aladheed/view_order', $view_data, TRUE),
			);
			return $this->load->view('template/full_template', $data);
		} else {
			redirect('login');
		}
	}


	public function getinvoice()
	{
		try {
			if (isset($_GET['code'])) {
				// $pcr = $this->db
				// 	->select('*')
				// 	->from('property_customers')
				// 	->where('customer_id', $_GET['code'])
				// 	->get()
				// 	->first_row();

				if ($_GET['code']) {
					// print_r($pcr);
					$curl = curl_init();

					curl_setopt_array($curl, [
						CURLOPT_URL => 'https://verify.ontime-pos.com/POSInvoice/Login?username=admin',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'GET',
					]);

					$response = curl_exec($curl);

					curl_close($curl);
					$res = json_decode($response);
					log_message("https://verify.ontime-pos.com/POSInvoice/Login?username=admin", $response);
					$token = $res->Token;

					$curl = curl_init();

					$curl_url = 'https://verify.ontime-pos.com/POSInvoice/GetPaidStatus/' . $_GET['code'];
					// echo $curl_url;
					// echo "<br>";
					// echo $token;

					curl_setopt_array($curl, [
						CURLOPT_URL => $curl_url,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => '',
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => 'POST',
						CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Length: 0'],
					]);

					// curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 50000'));



					// curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=utf-8"));


					// curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0'));


					$response = curl_exec($curl);

					curl_close($curl);
					$res = json_decode($response);
					// echo "<br>";
					// echo "There: ".$response;
					// print_r(curl_error($curl));
					// exit();
					if (!$res->Url) {
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PR Order');
						return redirect('/orders/property/list#completed');
					}
					// print_r($res);

					// if($res->Status)
					header("location: " . $res->Url);

					if (curl_error($curl)) {
						echo curl_error($curl);
						exit();
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Exception: ' . curl_error($curl));
						return redirect('/orders/property/list#completed');
					}
				} else {
					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', 'PR Order doesnot exist for fetch invoice.');
					return redirect('/orders/property/list#completed');
				}
			} else {
				$this->session->set_flashdata('alert', 'danger');
				$this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PR Order');
				return redirect('/orders/property/list#completed');
			}
		} catch (Exception $e) {
			$this->session->set_flashdata('alert', 'danger');
			$this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PR Order');
			return redirect('/orders/property/list#completed');
		}
	}
}
