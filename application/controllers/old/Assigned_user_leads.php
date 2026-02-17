<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assigned_user_leads extends MY_Controller
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
			//$view_data['enquiry']=$this->enquiry();
			$view_data['enquiry'] = $this->user_lead();
			$view_data['assigned_enquriy'] = $this->assigned_enquriy();
			$view_data['company_user'] = $this->company_user();
			$data = array(
				'page_title' => 'Assigned Leads',
				'title' => 'Assigned Leads',
				'content' => $this->load->view('pages/assigned_user_leads/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function user_lead(){
		$this->db->select("*");
		$this->db->from("enquiry as e");
        $this->db->join("assigned_leads as ae","ae.service_id=e.id");
        // $this->db->where("ae.status",2);
        $this->db->where("ae.assigned_user",$this->auth_user_id);
		$result=$this->db->get()->result();
	    return $result;
	}

    public function assigned_enquriy(){
		$this->db->select("*");
		$this->db->from("assigned_enquriy as e");
		$result=$this->db->get()->result();

	return $result;
	}

	public function enquiry(){
		$this->db->select("*");
		$this->db->from("enquiry as e");
		$this->db->where("e.enquiry",1);
		$this->db->order_by("e.id", "desc");
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


	public function assign_user(){
		$service_id=$this->input->post('service_id');
		$assigned_user=$this->input->post('users');
		$group_id=$this->input->post('group');
        // print_r($_POST);
        // die();
		$insert_array=array(
			'service_id'=>$service_id,
			'group_id'=>$group_id,
			'status' => 1,
			'assigned_user'=>$assigned_user,
			'assigned_date'=> date('d-m-Y'),
		);
		$insert=$this->db->insert("assigned_leads",$insert_array);
		if($insert){
			$this->session->set_flashdata('alert_success', 'user assigned successfully');
		}else{
			$this->session->set_flashdata('alert_danger','user not assigned ');
		}
		redirect('Assigned_leads');
	}

	public function set_priority(){
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
		redirect('enquiry');
	}

		public function user()
	{

		$this->db->select("o.user_id");
		$this->db->from("orders as o");
		$res=$this->db->get()->result();
		foreach($res as $r)
		{
			$user_id=$r->user_id;
		}



            //print_r($_SESSION['services']);exit('aaaa');
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
              $hostname = 'localhost';
              $username = 'chatbot_user';
              $password = 'chatbot_user';
              $database = 'ontimedigital_oneauth';
              $environment = 'development';
            } else {
              $hostname     = 'localhost';
              $username     = 'chatbot_user';
              $password     = '5700d$A4';
              $database     = 'ontimedigital_oneauth';
              $environment  = 'development';
            
            }
            
            // Create connection
            $conn = new mysqli($hostname, $username, $password, $database);
            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }
            //$user_id=3724832765;
            $sql = "SELECT * FROM users WHERE user_id=".$user_id;
            $result = $conn->query($sql);

            if(!empty($result)){
               while($row1 = $result->fetch_assoc()) {
          
            $f_name=$row1['first_name'];
            return $f_name;
             
        }
                            
	} 

}

public function view_enquiry($id){

	$view_data['enquiry']=$this->enquiry_by_id($id);
	$data = array(
		'page_title' => 'View Enquiry',
		'title' => 'View Enquiry',
		'content' => $this->load->view('pages/enquiry/details', $view_data, TRUE),
	);
	$this->load->view('template/base_template', $data);
}

public function get_group(){
	$this->db->select("*");
	$this->db->from("group_master");
	$this->db->where("status",1);
	$query=$this->db->get();
	return $query->result();
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



}
