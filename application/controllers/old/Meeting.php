<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
	}

	public function index()
	{
		if( $this->verify_min_level(1))
		{
			$view_data['group']=$this->get_group();
			$view_data['meeting']=$this->meeting();
			$view_data['user_services'] = $this->user_lead();
			$view_data['users']=$this->users();
			$view_data['assigned_meeting'] = $this->assigned_meeting();
			$view_data['company_user'] = $this->company_user();
			$data = array(
				'page_title' => 'Meeting',
				'title' => 'Meeting',
				'content' => $this->load->view('pages/meeting/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function set_priority(){
		// print_r($_POST);
		// die();
		$enquiry_id=$this->input->post('enquiry_id');
		$priority=$this->input->post('priority');
		$update_array=array(
			'priority'=>$priority,
		);
		$this->db->where("id",$enquiry_id);
		$update=$this->db->update("enquiry",$update_array);
		if($update){
			$this->session->set_flashdata('alert_success', 'Priority set successfully');
		}else{
			$this->session->set_flashdata('alert_danger','user not assigned ');
		}
		redirect('meeting');
	}

	public function get_group(){
		$this->db->select("*");
		$this->db->from("group_master");
		$this->db->where("status",1);
		$query=$this->db->get();
		return $query->result();
	}

	public function get_group_users(){
		$group_id=$this->input->post("group_id");
		$this->db->select("*");
		$this->db->from("group_users as gu");
		$this->db->join("users as u","u.user_id=gu.user_id");
		$this->db->where("gu.group_id",$group_id);
		$query=$this->db->get();
		$result=$query->result();
		echo json_encode($result);
	}

	public function user_lead(){
		$this->db->select("*");
		$this->db->from("enquiry as e");
		$result=$this->db->get()->result();

	return $result;
	}
	 public function assigned_meeting(){
		$this->db->select("*");
		$this->db->from("assigned_meeting as e");
		$result=$this->db->get()->result();

	return $result;
	}
	public function company_user()
	{
		$this->db->select("*");
		$this->db->from("users as u");
		$this->db->join("company_users as c", "c.user_id=u.user_id");
		$this->db->where("u.auth_level",1);
		$this->db->where("c.company_id",30);
		$query=$this->db->get();
		return $query->result();

	}


	public function meeting(){
		$this->db->select("*");
		$this->db->from("enquiry");
		$this->db->where("enquiry",3);
		$query=$this->db->get();
		return $query->result();
	}
	public function users(){
		$this->db->select("*");
		$this->db->from("users");
		$this->db->where("auth_level",1);
		$query=$this->db->get();
		return $query->result();
	}

	public function assign_user(){
		
		$service_id=$this->input->post('service_id');
		$assigned_user=$this->input->post('users');
		$group_id=$this->input->post('group');
		$date=date('d-m-Y  H:i:s');
		$insert_array=array(
			'service_id'=>$service_id,
			'assigned_user'=>$assigned_user,
			'group_id'=>$group_id,
			'status' => 1,
			'assigned_date'=> date('Y-m-d H:i:s'),
			'assigned_time'=>date('h:i A', strtotime($date)),
		);
		$insert=$this->db->insert("assigned_meeting",$insert_array);
		if($insert){
			$this->session->set_flashdata('alert_success', 'user assigned successfully');
		}else{
			$this->session->set_flashdata('alert_danger','user not assigned ');
		}
		 redirect('meeting');
	}


	public function view_meeting($id){

		$view_data['enquiry']=$this->enquiry_by_id($id);
		// print_r($view_data);
		// die();
		$data = array(
			'page_title' => 'View Meeting',
			'title' => 'View Meeting',
			'content' => $this->load->view('pages/meeting/details', $view_data, TRUE),
		);
		$this->load->view('template/base_template', $data);
	}


	public function enquiry_by_id($id){
		$this->db->select("*");
		$this->db->from("enquiry as e");
		$this->db->where("e.enquiry",3);
		$this->db->order_by("e.id", "desc");
		$this->db->where("e.id",$id);
		$query=$this->db->get();
		return $query->result();
	}


}
