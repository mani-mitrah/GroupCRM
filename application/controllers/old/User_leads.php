
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_leads extends MY_Controller
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
			$view_data['category'] = $this->category();
			$view_data['sub_category'] = $this->sub_category();
			$view_data['sub_category2'] = $this->sub_category2();
			$view_data['service_hours'] = $this->service_hours();
			$view_data['users']=$this->users(); 
			$data = array(
				'page_title' => 'Leads',
				'title' => 'Leads',
				'content' => $this->load->view('pages/user_leads/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function category(){
		$this->db->select("*");
		$this->db->from("m_category");
		$this->db->where("is_active",1);
		$query=$this->db->get();
		return $query->result();
	}

	public function sub_category(){
		$this->db->select("*");
		$this->db->from("m_sub_category");
		$this->db->where("is_active",1);
		$query=$this->db->get();
		return $query->result();
	}

	public function service_hours(){
		$this->db->select("*");
		$this->db->from("m_service_hours");
		$this->db->where("is_active",1);
		$query=$this->db->get();
		return $query->result();
	}

	public function sub_category2(){
		$this->db->select("*");
		$this->db->from("m_sub_categorytwo");
		$this->db->where("is_active",1);
		$query=$this->db->get();
		return $query->result();
	}

	public function application_edit(){
		$service_id=$this->input->post('service_id');
		$category=$this->input->post('category');
		$sub_category=$this->input->post('sub_category');
		$sub_categoty_two=$this->input->post('sub_categoty_two');
		$gender=$this->input->post('gender');
		$service_time=$this->input->post('service_time');
		$status=$this->input->post('status');
		$med_number=$this->input->post("med_number");

		$update_array=array(
			'category'=>$category,
			'sub_category'=>$sub_category,
			'sub_category_two'=>$sub_categoty_two,
			'gender'=>$gender,
			'service_time'=>$service_time,
			'med_number'=>$med_number?$med_number:0
		);

		$this->db->where("order_id",$service_id);
		$this->db->update("order_items",$update_array);

		$this->db->where("service_id",$service_id);
		$update=$this->db->update("assigned_service",array('status'=>$status));

		if($update){
			$this->session->set_flashdata('alert_success', 'Service Updated successfully');
		}else{
			$this->session->set_flashdata('alert_danger','Service Not Updated');
		}
		redirect("user_leads");
		
	}

	public function user_services(){
        $this->db->select("*,o.created_date as payment_date");
        $this->db->from("assigned_service as sa");
		$this->db->join("orders as o","o.o_id=sa.service_id");
		$this->db->join("order_items as oi","oi.order_id=o.o_id");
		//$this->db->join("users as u","u.user_id=o.user_id");
		$this->db->join("m_category as mc","mc.id=oi.category");
		$this->db->join("m_sub_category as ms","ms.id=oi.sub_category");
		$this->db->join("m_sub_categorytwo as sc","sc.id=oi.sub_category_two");
		$this->db->join("m_service_hours as msh","msh.id=oi.service_time");
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
            'status'=>1,
        );
        $update=$this->mcommon->update_service_status($id,$update_array);
        if($update){
			$this->session->set_flashdata('alert_success', 'Service Accepted successfully');
		}else{
			$this->session->set_flashdata('alert_danger','Service Not Accepted');
		}
        redirect("user_leads");
    }

    public function reject(){
        $id=$this->uri->segment(3);
        $update_array=array(
            'status'=>0,
        );
        $update=$this->mcommon->update_service_status($id,$update_array);
        if($update){
			$this->session->set_flashdata('alert_success', 'Service Rejected successfully');
		}else{
			$this->session->set_flashdata('alert_danger','Service Not Rejected');
		}
        redirect("user_leads");
    }

	public function assign_user(){
		
		$service_id=$this->input->post('service_id');
		$assigned_user=$this->input->post('users');
		$insert_array=array(
			'service_id'=>$service_id,
			'assigned_user'=>$assigned_user,
			'assigned_date'=>date('d-m-Y'),
		);
		$insert=$this->db->insert("assigned_service",$insert_array);
		if($insert){
			$this->session->set_flashdata('alert_success', 'user assigned successfully');
		}else{
			$this->session->set_flashdata('alert_danger','user not assigned ');
		}
		$view_data['user_services'] = $this->user_services();
			$view_data['users']=$this->users(); 
			$data = array(
				'page_title' => 'Leads',
				'title' => 'Leads',
				'content' => $this->load->view('pages/leads/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
	}
	public function user_lead(){
		$this->db->select("*,o.created_date as c_date");
		$this->db->from("orders as o");
		$this->db->join("users as u","u.user_id=o.user_id");
		$this->db->join("order_items as oi","oi.order_id=o.o_id");
		$this->db->join("order_attachments as oa","oa.order_item_id=oi.item_id",'left');
	    $this->db->join("m_category as c","c.id=oi.category");
		$this->db->join("m_sub_category as sc","sc.id=oi.sub_category",'left');
		$this->db->join("m_sub_categorytwo as sct","sct.id=oi.sub_category_two",'left');
		$this->db->join("m_service_hours as h","h.id=oi.service_time");
		$query=$this->db->get();
		return $query->result();
	} 

}
