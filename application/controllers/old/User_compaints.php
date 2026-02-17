
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_compaints extends MY_Controller
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
			$view_data['user_services'] = $this->user_services();
			$view_data['users']=$this->users(); 
			$data = array(
				'page_title' => 'Complaints',
				'title' => 'Complaints',
				'content' => $this->load->view('pages/user_compaints/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}


	public function update_status(){
		// print_r($_POST);
		// die();
		$enquiry_id=$this->input->post("enquiry_id");
		$status=$this->input->post("status");
		$review=$this->input->post("review");
		$update_array=array(
			'status'=>$status,
		);
		$this->db->where("as_id",$enquiry_id);
		$update=$this->db->update("assigned_complaints",$update_array);
		if($update){
			$remarks_update=array(
				'remarks'=>$review,
			);
			$this->db->where("id",$enquiry_id);
			$update=$this->db->update("enquiry",$remarks_update);
			$this->session->set_flashdata('alert_success', 'Status Updated Successfully');
			redirect('user_compaints');
		}else{
			$this->session->set_flashdata('alert_danger','Status Not Updated Successfully');
		}
	}

	public function user_services(){
        $this->db->select("*,e.email as e_email,sa.status as complaint_status");
        $this->db->from("assigned_complaints as sa");
        $this->db->join("enquiry as e","e.id=sa.service_id");
		$this->db->join("users as u","u.user_id=sa.assigned_user");
        $this->db->where("sa.assigned_user",$this->auth_user_id);
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

    public function accept(){
        $id=$this->uri->segment(3);
        $update_array=array(
            'status'=>2,
        );
        $update=$this->mm->update_complaints_status($id,$update_array);
        if($update){
			$this->session->set_flashdata('alert_success', 'Complaints Accepted successfully');
		}else{
			$this->session->set_flashdata('alert_danger','Complaints Not Accepted');
		}
        redirect("user_compaints");
    }

    public function reject(){
        $id=$this->uri->segment(3);
        $update_array=array(
            'status'=>0,
        );
        $update=$this->mm->update_complaints_status($id,$update_array);
        if($update){
			$this->session->set_flashdata('alert_success', 'Complaints Rejected successfully');
		}else{
			$this->session->set_flashdata('alert_danger','Complaints Not Rejected');
		}
        redirect("user_compaints");
    }
}
