<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Labpackage extends MY_Controller
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
		if ($this->verify_min_level(1)) {
			$view_data['packages'] = $this->db->select("lead_packages.*,ontime_branches.branch_name")->from("lead_packages")->join("ontime_branches", "ontime_branches.branch_code=lead_packages.package_branch")->where("lead_packages.package_branch", 109)->get()->result_array();
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

	public function new()
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
				$package["package_category_name"] = $_POST["category_name"];
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
				return redirect("/leads/labpackage");
			}


			$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
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


	public function edit()
	{
		if ($this->verify_min_level(1)) {
			if (isset($_POST['submit'])) {
				log_message('error', 'create OnTime Lab lead package');
				$package_id = $_GET["id"];
				$package["package_name"] = $_POST["package_name"];
				$package["is_active"] = 1;
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

				$category["category_name"] = $_POST["category_name"];
				$category["category_code"] = $_POST["category_name"] . " - " . $_POST["package_name"];
				$category["is_active"] = 0;

				$category_id = $this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
				$this->mcommon->common_edit("ontime_categories", $category, ["category_id" => $_POST["package_category_id"]]);
				$category_id = $_POST["package_category_id"];
				// print_r($package);
				// exit();
				$this->mcommon->common_edit('lead_packages', $package, ["package_id" => $package_id]);

				$services = [];
				for ($i = 0; $i < count($service_name); $i++) {
					$serve["category_id"] = $category_id;
					$serve["service_name"] = $service_name[$i];
					$serve["govt_fee"] = $govt_fee[$i];
					$serve["typing_fee"] = $typing_fee[$i];
					$serve["is_active"] = 1;
					if (isset($service_id[$i])) {
						$serve_id = $service_id[$i];
						$this->mcommon->common_edit("ontime_category_services_", $serve, ["service_id" => $service_id[$i]]);
					} else $serve_id = $this->mcommon->common_insert("ontime_category_services_", $serve);
					$this->mcommon->common_edit("ontime_category_services_", ["service_id" => $serve_id], ["id" => $serve_id]);
					$service["package_id"] = $package_id;
					$service["service_id"] = $serve_id;
					$service["govt_fee"] = $govt_fee[$i];
					$service["typing_fee"] = $typing_fee[$i];
					$service["card_amount"] = $card_amount[$i];
					$service["total"] = $total[$i];
					$service["service_desc"] = $service_desc[$i];

					$this->mcommon->common_edit("lead_package_services", $service, ["service_id" => $serve_id, "package_id" => $package_id]);
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

				return redirect("/leads/labpackage");
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
}
