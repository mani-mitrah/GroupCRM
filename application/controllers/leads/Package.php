<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Package extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->helper('crypt');
		$this->load->model('app_model');
		$this->load->model('access_model');
		$this->load->model('user_model');
		$this->load->model('master_model');
		$this->load->model('leads_model');
		$this->load->model('authentication_model');
		// $this->load->library('encrypt');
	}

	public function index()
	{
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			$view_data['packages'] = $this->db->select("lead_packages.*,ontime_branches.branch_name")->from("lead_packages")->join("ontime_branches", "ontime_branches.branch_code=lead_packages.package_branch")->where("lead_packages.package_branch", 106)->get()->result_array();
			// echo "<pre>";
			// print_r($view_data['packages']);
			// echo "</pre>";
			// exit();
			$data = array(
				'page_title' => 'Package List',
				'title' => 'Package List',
				'content' => $this->load->view('leads/packages/index', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}
	public function besmart_packages(){
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			$view_data['packages'] = $this->db->select("lead_packages.*,ontime_branches.branch_name")->from("lead_packages")->join("ontime_branches", "ontime_branches.branch_code=lead_packages.package_branch")->where("lead_packages.package_branch", 125)->get()->result_array();
			// echo "<pre>";
			// print_r($view_data['packages']);
			// echo "</pre>";
			// exit();
			$data = array(
				'page_title' => 'Package List',
				'title' => 'Package List',
				'content' => $this->load->view('leads/packages/indexbesmart', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function barahavan_packages(){
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			$view_data['packages'] = $this->db->select("lead_packages.*,ontime_branches.branch_name")->from("lead_packages")->join("ontime_branches", "ontime_branches.branch_code=lead_packages.package_branch")->where("lead_packages.package_branch", 138)->get()->result_array();
			// echo "<pre>";
			// print_r($view_data['packages']);
			// echo "</pre>";
			// exit();
			$data = array(
				'page_title' => 'Package List',
				'title' => 'Package List',
				'content' => $this->load->view('leads/packages/indexbarahavan', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}
	public function direct_invoice(){
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			$view_data['invoices'] = $this->db->select("*")->from("pos_direct_invoice_list")->get()->result_array();
			// echo "<pre>";
			// print_r($view_data['packages']);
			// echo "</pre>";
			// exit();
			$data = array(
				'page_title' => 'POS Direct Invoices',
				'title' => 'POS Direct Invoices List',
				'content' => $this->load->view('directinvoice/index', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}
	function delete_inv(){
		$id=$_GET['id'];
		$remove = $this->mcommon->common_delete('pos_direct_invoice_list', array('id' => $id));

		$this->session->set_flashdata('alert', 'warning');
		$this->session->set_flashdata('alert_message', 'Invoice Removed');
		return redirect("/leads/package/direct_invoice");
	}
	public function invedit()
	{
		$action = $this->input->get('action');
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				//Updation operation
				//log_message('error', 'create lead package');
				$id = $_GET["id"];
				$invoice["pos_invoice_id"] = $_POST["pos_invoice_id"];
				$invoice["amount"] = $_POST["amount"];
				//$invoice["created_by"] = $this->auth_user_id;
				
				$this->mcommon->common_edit('pos_direct_invoice_list', $invoice, ["id" => $id]);
				
				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Invoice Details Successfully Modified');

				return redirect("/leads/package/direct_invoice");
				
			}
			//data fetching using id passed in url
			$datas = $this->db->select("*")->from("pos_direct_invoice_list")->where("id", $_GET["id"])->get()->first_row();
			$view_data["data"] = $datas;
			
			$data = array(
				'page_title' => 'Edit Invoice',
				'title' => 'Edit Invoice',
				'content' => $this->load->view('directinvoice/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}
	public function newbesmart(){
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$msd_dep = $_POST["msd_dep"];
				$is_meeting_contain = $_POST["is_meeting_contain"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 1;

				$category_id = $this->mcommon->common_insert("ontime_categories", $category);
				$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
				$package["package_category_id"] = $category_id;

				// print_r($package);
				// exit();
				$package_id = $this->mcommon->common_insert('lead_packages', $package);

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {

					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					$service_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);

					$service["package_id"] = $package_id;
					$service["service_id"] = $service_id;
					$service["service_id"] = $service_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_meeting_contain"] = $is_meeting_contain[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];

					array_push($services, $service);
					$this->mcommon->common_insert("lead_package_services", $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "create";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Successfully Created');
				return redirect("/leads/packages/besmart_packages");
			}


			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
			$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
			$view_data['pos_direct_invoice_list'] = $this->mcommon->specific_fields_records_all('pos_direct_invoice_list');
			$data = array(
				'page_title' => 'New Package Creation',
				'title' => 'New Package Creation',
				'content' => $this->load->view('leads/packages/addbesmart', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function newbarahavan(){
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {

			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$msd_dep = $_POST["msd_dep"];
				$is_meeting_contain = $_POST["is_meeting_contain"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 1;

				$category_id = $this->mcommon->common_insert("ontime_categories", $category);
				$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
				$package["package_category_id"] = $category_id;

				// print_r($package);
				// exit();
				$package_id = $this->mcommon->common_insert('lead_packages', $package);

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {

					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					$service_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);

					$service["package_id"] = $package_id;
					$service["service_id"] = $service_id;
					$service["service_id"] = $service_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_meeting_contain"] = $is_meeting_contain[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];

					array_push($services, $service);
					$this->mcommon->common_insert("lead_package_services", $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "create";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Successfully Created');
				return redirect("/leads/package/barahavan_packages");
			}


			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
			$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
			$view_data['pos_direct_invoice_list'] = $this->mcommon->specific_fields_records_all('pos_direct_invoice_list');
			$data = array(
				'page_title' => 'New Package Creation',
				'title' => 'New Package Creation',
				'content' => $this->load->view('leads/packages/addbarahavan', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}


	public function new ()
	{
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];
				$package["package_type"] = $_POST["package_type"];

				$gcservice_id = $_POST["service_id"];
				$service_name = $_POST["service_name"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$msd_dep = $_POST["msd_dep"];
				$is_meeting_contain = $_POST["is_meeting_contain"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];

				$sla = $_POST["sla"];

				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				$category_id = $this->mcommon->common_insert("ontime_categories", $category);
				$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
				$package["package_category_id"] = $category_id;

				// print_r($package);
				// exit();
				$package_id = $this->mcommon->common_insert('lead_packages', $package);

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {

					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["gc_service_id"] = $gcservice_id[$i];
					$serve["is_active"] = 1;
					$service_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);

					$service["package_id"] = $package_id;
					$service["service_id"] = $service_id;
					$service["service_id"] = $service_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_meeting_contain"] = $is_meeting_contain[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];
					$service["sla"] = $sla[$i];

					array_push($services, $service);
					$this->mcommon->common_insert("lead_package_services", $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "create";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Successfully Created');
				return redirect("/leads/package");
			}


			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
			$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
			$view_data['pos_direct_invoice_list'] = $this->mcommon->specific_fields_records_all('pos_direct_invoice_list');
			$view_data['services'] = $this->mcommon->specific_fields_records_all('ontime_services', array('is_active' => 1, 'service_branch' => 106));
			$data = array(
				'page_title' => 'New Package Creation',
				'title' => 'New Package Creation',
				'content' => $this->load->view('leads/packages/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}


	public function edit()
	{
		//echo "<pre>";
		//print_r($_POST);
		//die();
		$action = $this->input->get('action');
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package_id = $_GET["id"];
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];
				$package["package_type"] = $_POST["package_type"];

				$gc_service_id = $_POST["gc_service_id"];
				$service_name = $_POST["service_name"];
				$service_id = $_POST["service_id"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$is_meeting_contain = $_POST["is_meeting_contain"];
				$msd_dep = $_POST["msd_dep"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];
				$sla = $_POST["sla"];

				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				if( (isset($action) & $action =='duplicate') || $_POST['submit'][0]=="Save As"){
					$category_id = $this->mcommon->common_insert("ontime_categories", $category);
					$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
					$package["package_category_id"] = $category_id;
					$package_id = $this->mcommon->common_insert('lead_packages', $package);
					
				}else{
					$this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
					$category_id = $_POST["package_category_id"];
					$this->mcommon->common_edit('lead_packages', $package, ["package_id" => $package_id]);
				}

				$this->db->where('package_id', $package_id);
				$this->db->where_not_in('service_id', $service_id);
				$this->db->delete('lead_package_services');

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {
					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve['gc_service_id'] = $gc_service_id[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As")  {
						$serve_id = $service_id[$i];
						$this->mcommon->common_edit("ontime_category_services_", $serve, ["service_id" => $service_id[$i]]);
					} else {
						$serve_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					}
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $serve_id], ["id" => $serve_id]);
					$service["package_id"] = $package_id;
					$service["service_id"] = $serve_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i] ?? 0;
					$service["msd_key"] = $msd_dep[$i] ?? 0;
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];
					$service["sla"] = $sla[$i];
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As" ) {
						//print_r('updated leads');
						$this->mcommon->common_edit("lead_package_services", $service, ["service_id" => $serve_id, "package_id" => $package_id]);
						//echo $this->db->last_query()."<br>";
					} else {
						//print_r('added leads');
						$this->mcommon->common_insert("lead_package_services", $service);
					}
					array_push($services, $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "edit";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Details Successfully Updated');

				return redirect("/leads/package");
			}

			$data = $this->db->select("*")->from("lead_packages")->where("package_id", $_GET["id"])->get()->first_row();
			$details = $this->db->select("lead_package_services.*,ontime_category_services_.service_name, ontime_category_services_.gc_service_id")->from("lead_package_services")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_id", $_GET["id"])->get()->result_array();
			// echo "<pre>";
			// print_r($data);
			// echo "<br>";
			// print_r($details);
			// echo "</pre>";
			// exit();
			$view_data["data"] = $data;
			$view_data["details"] = $details;
			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
				$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
				$view_data['pos_direct_invoice_list'] = $this->mcommon->specific_fields_records_all('pos_direct_invoice_list');
				$view_data['services'] = $this->mcommon->specific_fields_records_all('ontime_services', array('is_active' => 1, 'service_branch' => 106));
			$data = array(
				'page_title' => 'Edit Package',
				'title' => 'Edit Package',
				'content' => $this->load->view('leads/packages/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}
	public function editbesmart()
	{
		
		$action = $this->input->get('action');
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package_id = $_GET["id"];
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$service_id = $_POST["service_id"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$is_meeting_contain = $_POST["is_meeting_contain"];
				$msd_dep = $_POST["msd_dep"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				if( (isset($action) & $action =='duplicate') || $_POST['submit'][0]=="Save As"){
					$category_id = $this->mcommon->common_insert("ontime_categories", $category);
					$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
					$package["package_category_id"] = $category_id;
					$package_id = $this->mcommon->common_insert('lead_packages', $package);
					
				}else{
					$this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
					$category_id = $_POST["package_category_id"];
					$this->mcommon->common_edit('lead_packages', $package, ["package_id" => $package_id]);
				}

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {
					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As")  {
						$serve_id = $service_id[$i];
						$this->mcommon->common_edit("ontime_category_services_", $serve, ["service_id" => $service_id[$i]]);
					} else {
						$serve_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					}
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $serve_id], ["id" => $serve_id]);
					$service["package_id"] = $package_id;
					$service["service_id"] = $serve_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As" ) {
						//print_r('updated leads');
						$this->mcommon->common_edit("lead_package_services", $service, ["service_id" => $serve_id, "package_id" => $package_id]);
						//echo $this->db->last_query()."<br>";
					} else {
						//print_r('added leads');
						$this->mcommon->common_insert("lead_package_services", $service);
					}
					array_push($services, $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "edit";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Details Successfully Updated');

				return redirect("/leads/package/besmart_packages");
			}

			$data = $this->db->select("*")->from("lead_packages")->where("package_id", $_GET["id"])->get()->first_row();
			$details = $this->db->select("lead_package_services.*,ontime_category_services_.service_name")->from("lead_package_services")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_id", $_GET["id"])->get()->result_array();
			// echo "<pre>";
			// print_r($data);
			// echo "<br>";
			// print_r($details);
			// echo "</pre>";
			// exit();
			$view_data["data"] = $data;
			$view_data["details"] = $details;
			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
				$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
				$view_data['pos_direct_invoice_list'] = $this->mcommon->specific_fields_records_all('pos_direct_invoice_list');
			$data = array(
				'page_title' => 'Edit Package',
				'title' => 'Edit Package',
				'content' => $this->load->view('leads/packages/editbesmart', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function editbarahavan()
	{
		
		$action = $this->input->get('action');
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package_id = $_GET["id"];
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$service_id = $_POST["service_id"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$is_meeting_contain = $_POST["is_meeting_contain"];
				$msd_dep = $_POST["msd_dep"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				if( (isset($action) & $action =='duplicate') || $_POST['submit'][0]=="Save As"){
					$category_id = $this->mcommon->common_insert("ontime_categories", $category);
					$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
					$package["package_category_id"] = $category_id;
					$package_id = $this->mcommon->common_insert('lead_packages', $package);
					
				}else{
					$this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
					$category_id = $_POST["package_category_id"];
					$this->mcommon->common_edit('lead_packages', $package, ["package_id" => $package_id]);
				}

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {
					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As")  {
						$serve_id = $service_id[$i];
						$this->mcommon->common_edit("ontime_category_services_", $serve, ["service_id" => $service_id[$i]]);
					} else {
						$serve_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					}
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $serve_id], ["id" => $serve_id]);
					$service["package_id"] = $package_id;
					$service["service_id"] = $serve_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As" ) {
						//print_r('updated leads');
						$this->mcommon->common_edit("lead_package_services", $service, ["service_id" => $serve_id, "package_id" => $package_id]);
						//echo $this->db->last_query()."<br>";
					} else {
						//print_r('added leads');
						$this->mcommon->common_insert("lead_package_services", $service);
					}
					array_push($services, $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "edit";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Details Successfully Updated');

				return redirect("/leads/package/barahavan_packages");
			}

			$data = $this->db->select("*")->from("lead_packages")->where("package_id", $_GET["id"])->get()->first_row();
			$details = $this->db->select("lead_package_services.*,ontime_category_services_.service_name")->from("lead_package_services")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_id", $_GET["id"])->get()->result_array();
			// echo "<pre>";
			// print_r($data);
			// echo "<br>";
			// print_r($details);
			// echo "</pre>";
			// exit();
			$view_data["data"] = $data;
			$view_data["details"] = $details;
			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
				$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
				$view_data['pos_direct_invoice_list'] = $this->mcommon->specific_fields_records_all('pos_direct_invoice_list');
			$data = array(
				'page_title' => 'Edit Package',
				'title' => 'Edit Package',
				'content' => $this->load->view('leads/packages/editbarahavan', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function getPackageDetailsAll()
	{
		// $package["status"] = true;
		$package["results"] = $this->db->select("ontime_category_services_.service_name as text,lead_package_services.package_service_id as id")->from('lead_package_services')->join("lead_packages", "lead_package_services.package_id=lead_packages.package_id")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_packages.package_branch", 109)->get()->result_array();
		echo json_encode($package);
		exit();
	}


	public function getGCPackageDetailsAll()
	{
		//$branch_id = getUserBranch($this->auth_user_id);
		// $package["status"] = true;
		$package["results"] = $this->db->select("ontime_category_services_.service_name as text,lead_package_services.package_service_id as id")->from('lead_package_services')->join("lead_packages", "lead_package_services.package_id=lead_packages.package_id")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_packages.package_branch", 106)->get()->result_array();
		echo json_encode($package);
		exit();
	}
	public function new_getPackageDetails()
	{
		//print_r($_GET);
		$payment_type = $_GET['payment_type'];
		$branch = $_GET['branch'];
		$term ="";
		if(isset($_GET['term'])){
			$term = $_GET['term'];
		}
		if($branch==109 OR $branch==111){

			$branch= array(109,111);
				if(!empty($term)){
					$package["results"] = $this->db->select("concat(ontime_category_services_.service_name, ' - ', (lead_package_services.total - lead_package_services.card_amount)) as text,lead_package_services.package_service_id as id")->from('lead_package_services')->join("lead_packages", "lead_package_services.package_id=lead_packages.package_id")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where_in("lead_packages.package_branch", $branch)->where("lead_packages.is_active", 1)->where("lead_packages.payment_type", $payment_type)->like("ontime_category_services_.service_name", $term)->get()->result_array();
				}else{
					$package["results"] = $this->db->select("concat(ontime_category_services_.service_name, ' - ', (lead_package_services.total - lead_package_services.card_amount)) as text,lead_package_services.package_service_id as id")->from('lead_package_services')->join("lead_packages", "lead_package_services.package_id=lead_packages.package_id")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where_in("lead_packages.package_branch", $branch)->where("lead_packages.is_active", 1)->where("lead_packages.payment_type", $payment_type)->get()->result_array();
				}

				

		}else{
			if(!empty($term)){
			$package["results"] = $this->db->select("concat(ontime_category_services_.service_name, ' - ', (lead_package_services.total - lead_package_services.card_amount)) as text,lead_package_services.package_service_id as id")->from('lead_package_services')->join("lead_packages", "lead_package_services.package_id=lead_packages.package_id")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_packages.package_branch", $branch)->where("lead_packages.is_active", 1)->where("lead_packages.payment_type", $payment_type)->like("ontime_category_services_.service_name", $term)->get()->result_array();
			}else{
				$package["results"] = $this->db->select("concat(ontime_category_services_.service_name, ' - ', (lead_package_services.total - lead_package_services.card_amount)) as text,lead_package_services.package_service_id as id")->from('lead_package_services')->join("lead_packages", "lead_package_services.package_id=lead_packages.package_id")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_packages.package_branch", $branch)->where("lead_packages.is_active", 1)->where("lead_packages.payment_type", $payment_type)->get()->result_array();
			}
		}
		//die();
		//$branch_id = getUserBranch($this->auth_user_id);
		// $package["status"] = true;
		
		echo json_encode($package);
		exit();
	}
	
	public function getGPPackageDetailsAll()
	{
    $branch_id = getUserBranch($this->auth_user_id);
    
    // Check if the $branch_id array exists and is not empty
    if (!empty($branch_id)) {
        $package["results"] = $this->db->select("ontime_category_services_.service_name as text,lead_package_services.package_service_id as id")->from('lead_package_services')->join("lead_packages", "lead_package_services.package_id=lead_packages.package_id")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where_in("lead_packages.package_branch", $branch_id)->get()->result_array();
        $package["status"] = true;
    } else {
        $package["status"] = false;
    }
    
    echo json_encode($package);
    exit();
	}


	public function getPackageDetail()
	{
		if (isset($_GET["service_id"])) {
			$package = $this->db->select("lead_package_services.*,ontime_category_services_.service_name")->from('lead_package_services')->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_service_id", $_GET["service_id"])->get()->first_row();
			echo json_encode($package);
			exit();
		} else {
			echo "false";
			exit();
		}
	}

	public function getPackageDetails()
	{
		if (isset($_GET["package_id"])) {
			$package["status"] = true;
			$package["data"] = $this->db->select("lead_package_services.*,ontime_category_services_.service_name")->from('lead_package_services')->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_id", $_GET["package_id"])->get()->result_array();
			echo json_encode($package);
			exit();
		} else {
			echo json_encode(["status" => "error", "message" => "Invalid Package ID."]);
			exit();
		}
	}


	public function labindex()
	{
		if ($this->verify_min_level(1)) {
			$view_data['packages'] = $this->db->select("lead_packages.*,ontime_branches.branch_name")->from("lead_packages")->join("ontime_branches", "ontime_branches.branch_code=lead_packages.package_branch")->where_in("lead_packages.package_branch", array(109,111))->get()->result_array();
			// echo "<pre>";
			// print_r($view_data['packages']);
			// echo "</pre>";
			// exit();
			$data = array(
				'page_title' => 'OnTime Lab Package List',
				'title' => 'OnTime Lab Package List',
				'content' => $this->load->view('leads/labpackages/index', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function labnew()
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$msd_dep = $_POST["msd_dep"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				$category_id = $this->mcommon->common_insert("ontime_categories", $category);
				$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
				$package["package_category_id"] = $category_id;

				// print_r($package);
				// exit();
				$package_id = $this->mcommon->common_insert('lead_packages', $package);

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {

					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					$service_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);

					$service["package_id"] = $package_id;
					$service["service_id"] = $service_id;
					$service["service_id"] = $service_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];

					array_push($services, $service);
					$this->mcommon->common_insert("lead_package_services", $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "create";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Successfully Created');
				return redirect("/leads/package/labindex");
			}


			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
				$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
			$data = array(
				'page_title' => 'OnTime Lab - New Package Creation',
				'title' => 'OnTime Lab - New Package Creation',
				'content' => $this->load->view('leads/labpackages/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function labedit()
	{

		//echo "<pre>";
		//print_r($_POST);
		//die();
		$action = $this->input->get('action');


		if ($this->verify_min_level(1)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create OnTime Lab lead package');
				$package_id = $_GET["id"];
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_category_name"] = $_POST["category_name"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$service_id = $_POST["service_id"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$msd_dep = $_POST["msd_dep"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				if( (isset($action) & $action =='duplicate') || $_POST['submit'][0]=="Save As"){
					$category_id = $this->mcommon->common_insert("ontime_categories", $category);
					$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
					$package["package_category_id"] = $category_id;
					$package_id = $this->mcommon->common_insert('lead_packages', $package);
				}else{
					$this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
					$category_id = $_POST["package_category_id"];
					$this->mcommon->common_edit('lead_packages', $package, ["package_id" => $package_id]);
				}

				$this->db->where('package_id', $package_id);
				$this->db->where_not_in('service_id', $service_id);
				$this->db->delete('lead_package_services');

				$services = [];
				//echo "<pre>";
				//print_r($service_name);
				//print_r($service_id);
				for ($i = 0; $i < count($service_name); $i++) {
					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As") {
						$serve_id = $service_id[$i];
						$this->mcommon->common_edit("ontime_category_services_", $serve, ["service_id" => $service_id[$i]]);
					} else {
						$serve_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					}
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $serve_id], ["id" => $serve_id]);
					$service["package_id"] = $package_id;
					$service["service_id"] = $serve_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As") {
						//print_r('updated leads');
						$this->mcommon->common_edit("lead_package_services", $service, ["service_id" => $serve_id, "package_id" => $package_id]);
						//echo $this->db->last_query()."<br>";
					} else {
						//print_r('added leads');
						$this->mcommon->common_insert("lead_package_services", $service);
					}
					array_push($services, $service);
				}

				//die();
				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "edit";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Details Successfully Updated');
				//exit();
				return redirect("/leads/package/labindex");
			}

			$data = $this->db->select("*")->from("lead_packages")->where("package_id", $_GET["id"])->get()->first_row();
			$details = $this->db->select("lead_package_services.*,ontime_category_services_.service_name")->from("lead_package_services")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_id", $_GET["id"])->get()->result_array();
			// echo "<pre>";
			// print_r($data);
			// echo "<br>";
			// print_r($details);
			// echo "</pre>";
			// exit();
			$view_data["data"] = $data;
			$view_data["details"] = $details;
			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
				$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
			$data = array(
				'page_title' => 'OnTime Lab - Edit Package',
				'title' => 'OnTime Lab - Edit Package',
				'content' => $this->load->view('leads/labpackages/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}



	public function labgetPackageDetails()
	{
		if (isset($_GET["package_id"])) {
			$package["status"] = true;
			$package["data"] = $this->db->select("lead_package_services.*,ontime_category_services_.service_name")->from('lead_package_services')->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_id", $_GET["package_id"])->get()->result_array();
			echo json_encode($package);
			exit();
		} else {
			echo json_encode(["status" => "error", "message" => "Invalid Package ID."]);
			exit();
		}
	}




	//General Packages

	public function general()
	{
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			$view_data['packages'] = $this->db->select("lead_packages.*,ontime_branches.branch_name")->from("lead_packages")->join("ontime_branches", "ontime_branches.branch_code=lead_packages.package_branch")->where("lead_packages.package_branch NOT IN (106,109)")->get()->result_array();
			// echo "<pre>";
			// print_r($view_data['packages']);
			// echo "</pre>";
			// exit();
			$data = array(
				'page_title' => 'General Package List',
				'title' => 'General Package List',
				'content' => $this->load->view('leads/package-general/index', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function generalnew ()
	{
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				// $is_direct_invoice = $_POST["is_direct_invoice"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$msd_dep = $_POST["msd_dep"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				$category_id = $this->mcommon->common_insert("ontime_categories", $category);
				$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
				$package["package_category_id"] = $category_id;

				// print_r($package);
				// exit();
				$package_id = $this->mcommon->common_insert('lead_packages', $package);

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {

					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					$service_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);

					$service["package_id"] = $package_id;
					$service["service_id"] = $service_id;
					$service["service_id"] = $service_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];

					array_push($services, $service);
					$this->mcommon->common_insert("lead_package_services", $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "create";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Successfully Created');
				return redirect("/leads/package/general");
			}


			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
				$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
			$data = array(
				'page_title' => 'New General Package Creation',
				'title' => 'New General Package Creation',
				'content' => $this->load->view('leads/package-general/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}


	public function generaledit()
	{
		$action = $this->input->get('action');
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package_id = $_GET["id"];
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
				$package["payment_type"] = $_POST["payment_type"];
				$package["package_branch"] = $_POST["under_branch"];
				$package["package_description"] = $_POST["package_desc"];
				$package["package_amount"] = $_POST["package_amount"];
				$package["package_service_count"] = count($_POST["service_name"]);
				$package["created_by"] = $this->auth_user_id;
				$package["is_active"] = $_POST["is_active"];

				$service_name = $_POST["service_name"];
				$service_id = $_POST["service_id"];
				$typing_fee = $_POST["typing_fee"];
				$govt_fee = $_POST["govt_fee"];
				$card_amount = $_POST["card_amount"];
				$total = $_POST["total"];
				$service_desc = $_POST["service_desc"];
				$is_direct_invoice = $_POST["is_direct_invoice"];
				$msd_dep = $_POST["msd_dep"];
				$is_pos_typing_fee = $_POST["is_pos_typing_fee"];


				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				if(isset($action) & $action=='duplicate' || $_POST['submit'][0]=="Save As"){
					$category_id = $this->mcommon->common_insert("ontime_categories", $category);
					$this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
					$package["package_category_id"] = $category_id;
					$package_id = $this->mcommon->common_insert('lead_packages', $package);
				}else{
					$this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
					$category_id = $_POST["package_category_id"];
					$this->mcommon->common_edit('lead_packages', $package, ["package_id" => $package_id]);
				}
				

				//$this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
				
				// print_r($package);
				// exit();
			

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {
					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As") {
						$serve_id = $service_id[$i];
						$this->mcommon->common_edit("ontime_category_services_", $serve, ["service_id" => $service_id[$i]]);
					} else
						$serve_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $serve_id], ["id" => $serve_id]);
					$service["package_id"] = $package_id;
					$service["service_id"] = $serve_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];
					$service["is_direct_invoice"] = $is_direct_invoice[$i];
					$service["msd_key"] = $msd_dep[$i];
					$service["is_pos_typing_fee"] = $is_pos_typing_fee[$i];
					if (!empty($service_id[$i]) && !isset($action) && $_POST['submit'][0]!="Save As") {
						$this->mcommon->common_edit("lead_package_services", $service, ["service_id" => $serve_id, "package_id" => $package_id]);
						//echo $this->db->last_query()."<br>";
					} else {
						$this->mcommon->common_insert("lead_package_services", $service);
					}

					//$this->mcommon->common_edit("lead_package_services", $service, ["service_id" => $serve_id, "package_id" => $package_id]);
					array_push($services, $service);
				}

				$log["lead_package_id"] = $package_id;
				$log["lead_package"] = json_encode($package);
				$log["lead_package_details"] = json_encode($services);
				$log["created_by"] = $this->auth_user_id;
				$log["action"] = "edit";

				$this->mcommon->common_insert("lead_package_logs", $log);

				$this->session->set_flashdata('alert', 'success');
				$this->session->set_flashdata('alert_message', 'Package Details Successfully Updated');

				return redirect("/leads/package/general");
			}

			$data = $this->db->select("*")->from("lead_packages")->where("package_id", $_GET["id"])->get()->first_row();
			$details = $this->db->select("lead_package_services.*,ontime_category_services_.service_name")->from("lead_package_services")->join("ontime_category_services_", "ontime_category_services_.service_id=lead_package_services.service_id")->where("lead_package_services.package_id", $_GET["id"])->get()->result_array();
			// echo "<pre>";
			// print_r($data);
			// echo "<br>";
			// print_r($details);
			// echo "</pre>";
			// exit();
			$view_data["data"] = $data;
			$view_data["details"] = $details;
			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
				$view_data['msd_dep'] = $this->mcommon->specific_fields_records_all('msd_dep');
			$data = array(
				'page_title' => 'Edit General Package',
				'title' => 'Edit General Package',
				'content' => $this->load->view('leads/package-general/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function save_lead_order() {
	    $lead_parent_id = $this->input->post('parent_lead_id');
	    $sublead_ids = $this->input->post('input_sublead_id');
	    $govt_fees = $this->input->post('input_govt_fee');
	    $typing_fees = $this->input->post('input_typing_fee');

	    $previousOverallTotal = $this->input->post('previousOverallTotal');
	    $totalamount = $this->input->post('totalamount');

	    $TotalValidation = $this->mcommon->specific_fields_records_all('leads', array('lead_parent_id' => $lead_parent_id));

	    $OrderTotalfromDB = 0;
	    foreach ($TotalValidation as $value){ $OrderTotalfromDB += $value['govt_fee']+$value['typing_fee']; }

	    if($OrderTotalfromDB == $totalamount){


		    // Iterate over the input values and update the corresponding records in the database
		    for ($i = 0; $i < count($sublead_ids); $i++) {
		        $sublead_id = $sublead_ids[$i];
		        $govt_fee = $govt_fees[$i];
		        $typing_fee = $typing_fees[$i];
		        
		        $update = [
		            'govt_fee' => $govt_fee,
		            'typing_fee' => $typing_fee
		        ];
		        
		        $where = [
		            'id' => $sublead_id,
		            'lead_parent_id' => $lead_parent_id
		        ];
		        
		        $this->mcommon->common_edit('leads', $update, $where);
		        

		    }
		    echo json_encode(['status' => 'success']);


	    }else{
	    	 echo json_encode(['status' => 'error']);

	    }

	    //echo "<pre>";
	    //print_r($_POST);
	    //print_r($TotalValidation);
	    //print_r($OrderTotalfromDB);
	    //die();


	   
	}









}