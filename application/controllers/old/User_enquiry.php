
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_enquiry extends MY_Controller
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
				'page_title' => 'Enquriy',
				'title' => 'Enquriy',
				'content' => $this->load->view('pages/user_enquiry/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function user_services(){
        $this->db->select("*,e.email as e_email");
        $this->db->from("assigned_enquriy as sa");
       // $this->db->join("enquiry_datails as d","d.assign_id=sa.service_id",'left');
        $this->db->join("enquiry as e","e.id=sa.service_id");
		$this->db->join("users as u","u.user_id=sa.assigned_user");
       $this->db->where("sa.assigned_user",$this->auth_user_id);
	   $this->db->order_by("sa.as_id","desc");
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
        $update=$this->mm->update_enquriy_status($id,$update_array);

        if($update){
			$this->session->set_flashdata('alert_success', 'Enquiry Accepted successfully');
		}else{
			$this->session->set_flashdata('alert_danger','Enquiry Not Accepted');
		}
        redirect("user_enquiry");
    }

    public function reject(){
        $id=$this->uri->segment(3);
        $update_array=array(
            'status'=>5,
        );
        $update=$this->mm->update_enquriy_status($id,$update_array);
        if($update){
			$this->session->set_flashdata('alert_success', 'Enquiry Rejected successfully');
		}else{
			$this->session->set_flashdata('alert_danger','Enquiry Not Rejected');
		}
        redirect("user_enquiry");
    }
    public function edit(){
		
		$final_status=$this->input->post('final_status');
		$remarks=$this->input->post('remarks');
		$assign_id=$this->input->post('assign_id');
		if($assign_id==1)
		{
					$insert_array=array(
			'closed_convert'=>$final_status,
			'remarks'=>$remarks,
			'assign_id'=>$assign_id,
			'closed_date'=> date('Y-m-d H:i:s'),
		);
		$insert=$this->db->insert("enquiry_datails",$insert_array);

		 $update_array=array(
            'status'=>3,
        );
        $update=$this->mm->update_enquriy_status($assign_id,$update_array);

		}else
		{
			$insert_array=array(
			'closed_convert'=>$final_status,
			'remarks'=>$remarks,
			'assign_id'=>$assign_id,
			'closed_date'=> date('Y-m-d H:i:s'),
		);
		$insert=$this->db->insert("enquiry_datails",$insert_array);

		 $update_array=array(
            'status'=>4,
        );
        $update=$this->mm->update_enquriy_status($assign_id,$update_array);
		}

		if($insert){
			$this->session->set_flashdata('alert_success', 'Status updated successfully');
			redirect('user_enquiry');
		}else{
			$this->session->set_flashdata('alert_danger','user not assigned ');
		}
	}

	public function enquiry_by_id($id){
		$this->db->select("*");
		$this->db->from("enquiry as e");
		$this->db->where("e.enquiry",1);
		$this->db->order_by("e.id", "desc");
		$this->db->where("e.id",$id);
		$query=$this->db->get();
		return $query->result();
	}

	public function view_enquiry($id){

		$view_data['enquiry']=$this->enquiry_by_id($id);
		$data = array(
			'page_title' => 'View Enquiry',
			'title' => 'View Enquiry',
			'content' => $this->load->view('pages/enquiry/csa_enquiry_details', $view_data, TRUE),
		);
		$this->load->view('template/base_template', $data);
	}

	public function update_status(){

		$enquiry_id=$this->input->post("enquiry_id");
		$status=$this->input->post("status");
		$review=$this->input->post("review");
		$service_id=$this->input->post("service_id");
		// print_r($_POST);
		// die();
		$update_array=array(
			'status'=>$status,
		);
		$this->db->where("as_id",$enquiry_id);
		$update=$this->db->update("assigned_enquriy",$update_array);
		if($update){
			$date=date('d-m-Y  H:i:s');
			$remarks_update=array(
				'remarks'=>$review,
				'closed_time'=>date('h:i A', strtotime($date))
			);
			$this->db->where("id",$enquiry_id);
			$update=$this->db->update("enquiry",$remarks_update);
			$this->session->set_flashdata('alert_success', 'Status Updated Successfully');
			redirect('user_enquiry');
		}else{
			$this->session->set_flashdata('alert_danger','Status Not Updated Successfully');
		}
	}

}
