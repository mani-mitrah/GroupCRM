<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usertimeslot extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->library('form_validation');
		$this->load->library('session');
        $this->load->model('user_model');
	}

	public function index()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$view_data['page_title'] = 'User Time Slots';
			$this->db->select("count(crm_user_timeslots.user_timeslot_id) as slot_days,sum(crm_user_timeslots.user_timeslot_slot_count) as total_slots,users.first_name as first_name,users.last_name as last_name,crm_timeslots.timeslot_name,CONCAT(creator.first_name,' ',creator.last_name) as creator,users.user_id,crm_user_timeslots.created_at,crm_user_timeslots.user_timeslot_status as status")->from("crm_user_timeslots");
			$this->db->join("crm_timeslots","crm_timeslots.timeslot_id=crm_user_timeslots.user_timeslot_slot_id");
			$this->db->join("users","users.user_id = crm_user_timeslots.user_timeslot_user_id");
			$this->db->join("users as creator","creator.user_id = crm_user_timeslots.updated_by");
			// $this->db->where("crm_user_timeslots.user_timeslot_status = 1");
			$this->db->group_by("crm_user_timeslots.user_timeslot_user_id");
			$timeslots = $this->db->get()->result_array();
			// echo "<pre>";
			// print_r($timeslots);
			// echo "</pre>";
			// print_r($view_data);
			// exit();
			$view_data["user_timeslots"] = $timeslots;
			$data = array(
				'title' => 'Manage UserTimeSlots',
				'content' => $this->load->view('admin/usertimeslots/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function create()
	{
		if ($this->verify_min_level(1)) {

			if (isset($_POST['submit'])) {
				try {
					$this->db->delete("crm_user_timeslots",["user_timeslot_user_id" => $_POST["user_timeslot_user_id"]]);
					$user_timeslot_user_id = $_POST["user_timeslot_user_id"];
					$user_timeslot_day = $_POST["user_timeslot_day"];
					$user_timeslot_slot_id = $_POST["user_timeslot_slot_id"];
					$user_timeslot_slot_count = $_POST["user_timeslot_slot_count"];

					for ($i = 0; $i < count($user_timeslot_day); $i++) {
						$insert_array = [];
						$insert_array = [
							"user_timeslot_user_id" => $user_timeslot_user_id,
							"user_timeslot_day" => $user_timeslot_day[$i],
							"user_timeslot_slot_id" => $user_timeslot_slot_id[$i],
							"user_timeslot_slot_count" => $user_timeslot_slot_count[$i],
							"updated_by" => $this->auth_user_id
						];

						$insert_id = $this->db->insert("crm_user_timeslots",$insert_array);
					}

					$this->session->set_flashdata('alert', array('message' => "Users Slot Successfully Added", 'class' => 'success'));
					return redirect("/admin/usertimeslot");
					
				} catch (Exception $e) {
					$this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
					return redirect("/admin/usertimeslot/create");
				}
			}
			
			$view_data = array();
			$view_data["timeslot"] = $this->db->select("*")->from("crm_timeslots")->where("status", 1)->get()->result_array();
			$exist_users = $this->db->select("distinct(user_timeslot_user_id) as user_id")->from("crm_user_timeslots")->get()->result_array();
			$existusers = [];
			foreach($exist_users as $eu){
				array_push($existusers, $eu["user_id"]);
			}
			
			$view_data['existusers'] = $existusers;
			$view_data['page_title'] = 'UserTimeSlots - Creation';
			$data = array(
				'title' => 'Timeslot - Creation',
				'content' => $this->load->view('admin/usertimeslots/add', $view_data, TRUE),
			);

			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function edit($id)
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();


			if (isset($_POST['submit'])) {
				try {
					$this->db->delete("crm_user_timeslots",["user_timeslot_user_id" => $id]);
					$user_timeslot_user_id = $id;
					$user_timeslot_day = $_POST["user_timeslot_day"];
					$user_timeslot_slot_id = $_POST["user_timeslot_slot_id"];
					$user_timeslot_slot_count = $_POST["user_timeslot_slot_count"];

					for ($i = 0; $i < count($user_timeslot_day); $i++) {
						$insert_array = [];
						$insert_array = [
							"user_timeslot_user_id" => $user_timeslot_user_id,
							"user_timeslot_day" => $user_timeslot_day[$i],
							"user_timeslot_slot_id" => $user_timeslot_slot_id[$i],
							"user_timeslot_slot_count" => $user_timeslot_slot_count[$i],
							"updated_by" => $this->auth_user_id
						];

						$insert_id = $this->db->insert("crm_user_timeslots",$insert_array);
					}

					$this->session->set_flashdata('alert', array('message' => "Users Slot Successfully Updated", 'class' => 'success'));
					return redirect("/admin/usertimeslot");
					
				} catch (Exception $e) {
					$this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
					return redirect("/admin/usertimeslot/create");
				}
			}

			$timeslots = $this->db->select("*")->from("crm_user_timeslots")->where("user_timeslot_user_id", $id)->get()->result_array();
			
			$view_data["default"] = $timeslots;
			$view_data["timeslot"] = $this->db->select("*")->from("crm_timeslots")->where("status", 1)->get()->result_array();
			$view_data["page_title"] = "Edit Timeslot";
			$data = array(
				'title' => 'Edit Users Timeslot',
				'page_title' => 'Edit Users Timeslot',
				'content' => $this->load->view('admin/usertimeslots/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function status_change($id)
	{
		$current_status = $this->mcommon->specific_row_value('crm_user_timeslots', array('user_timeslot_user_id' => $id), 'user_timeslot_status');
		$change_status = ($current_status == 1) ? 0 : 1;
		$delete = $this->mcommon->common_edit('crm_user_timeslots', array('user_timeslot_status' => $change_status), array('user_timeslot_user_id' => $id));
		$this->session->set_flashdata('alert', array('message' => "Status Successfully Updated", 'class' => 'success'));

		redirect('/admin/usertimeslot');
	}


	public function slots(){
		$user_id = $_GET["user_id"];
		$slot_day = $_GET["day"];
		// echo date('w',strtotime($slot_day))-1;
		$this->db->select("crm_user_timeslots.*,crm_timeslots.timeslot_name")->from("crm_user_timeslots");
		$this->db->join("crm_timeslots","crm_timeslots.timeslot_id=crm_user_timeslots.user_timeslot_slot_id");
		$this->db->where("crm_user_timeslots.user_timeslot_user_id",$user_id);
		$this->db->where("crm_user_timeslots.user_timeslot_day",date('w',strtotime($slot_day))-1);
		$res = $this->db->get()->result_array();

		echo json_encode($res);
	}

}
