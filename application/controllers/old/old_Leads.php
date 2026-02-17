
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Leads extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
	}

	public function index()
	{
		if ($this->verify_min_level(1)) {
			$view_data['group']=$this->get_group();
			$view_data['user_services'] = $this->user_lead();
			// print_r($view_data['user_services']);
			// die();
			$view_data['user'] = $this->user();
			//echo "<pre>"; print_r($view_data['user_services']);exit();
			$view_data['users'] = $this->users();
			$data = array(
				'page_title' => 'Orders',
				'title' => 'Orders',
				'content' => $this->load->view('pages/leads/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
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

	public function get_group(){
		$this->db->select("*");
		$this->db->from("group_master");
		$this->db->where("status",1);
		$query=$this->db->get();
		return $query->result();
	}


	public function user_lead()
	{
		$this->db->select("*,o.user_id as order_user,o.created_date as c_date");
		$this->db->from("orders as o");
		$this->db->join("users as u", "u.user_id=o.user_id");
		$this->db->join("order_items as oi", "oi.order_id=o.o_id");
		$this->db->join("order_attachments as oa", "oa.order_item_id=oi.item_id");
		$this->db->join("m_category as c", "c.id=oi.category");
		$this->db->join("m_sub_category as sc", "sc.id=oi.sub_category", 'left');
		$this->db->join("m_sub_categorytwo as sct", "sct.id=oi.sub_category_two", 'left');
		$this->db->join("m_service_hours as h", "h.id=oi.service_time");

		$query = $this->db->get()->result();
		$img = array();
		foreach ($query as $q) {
			$img[] = $q->attachment;
		}

		$this->db->select("*,o.user_id as order_user,o.created_date as c_date");
		$this->db->from("orders as o");
		//$this->db->join("users as u","u.user_id=o.user_id");
		$this->db->join("order_items as oi", "oi.order_id=o.o_id");
		//$this->db->join("order_attachments as oa","oa.order_item_id=oi.item_id");
		$this->db->join("m_category as c", "c.id=oi.category");
		$this->db->join("m_sub_category as sc", "sc.id=oi.sub_category", 'left');
		$this->db->join("m_sub_categorytwo as sct", "sct.id=oi.sub_category_two", 'left');
		$this->db->join("m_service_hours as h", "h.id=oi.service_time");

		$result = $this->db->get()->result();
		foreach ($result as $r) {
			$data[] = array(
				'img' => $img,
				'user_id' => $r->order_user,
				// 'mobile'=>$r->mobile,
				// 'email'=>$r->email,
				'order_id' => $r->o_id,
				'item_id'=>$r->item_id,
				'category_name' => $r->category_name,
				'sub_category_name' => $r->sub_category_name,
				'sub_categorytwo' => $r->sub_categorytwo,
				'gender' => $r->gender,
				'service_hour' => $r->service_hour,
				'amount' => $r->amount,
				'c_date' => $r->c_date,
				'priority'=>$r->priority
			);
		}


		return $data;
	}

	public function users()
	{
		$this->db->select("*");
		$this->db->from("users");
		$this->db->where("auth_level", 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function assign_user()
	{

		$service_id=$this->input->post('service_id');
		$assigned_user=$this->input->post('users');
		$group_id=$this->input->post('group');
		$date=date('d-m-Y  H:i:s');
		$insert_array=array(
			'order_id'=>$service_id,
			'assigned_user'=>$assigned_user,
			'group_id'=>$group_id,
			'status' => 1,
			'created_date'=> date('d-m-Y'),
			'created_time'=>date('h:i A', strtotime($date)),
		);
		// print_r($insert_array);
		// die();
		$insert=$this->db->insert("assigned_order",$insert_array);
		if($insert){
			$this->session->set_flashdata('alert_success', 'user assigned successfully');
		}else{
			$this->session->set_flashdata('alert_danger','user not assigned ');
		}
		 redirect('leads');
	}


	public function set_priority(){
		// print_r($_POST);
		// die();
		$enquiry_id=$this->input->post('enquiry_id');
		$priority=$this->input->post('priority');
		$update_array=array(
			'priority'=>$priority,
		);
		$this->db->where("o_id",$enquiry_id);
		$update=$this->db->update("orders",$update_array);
		if($update){
			$this->session->set_flashdata('alert_success', 'Priority set successfully');
		}else{
			$this->session->set_flashdata('alert_danger','user not assigned ');
		}
		redirect('leads');
	}


	public function user()
	{

		$this->db->select("o.user_id");
		$this->db->from("orders as o");
		$res = $this->db->get()->result();
		foreach ($res as $r) {
			$user_id = $r->user_id;
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
		$sql = "SELECT * FROM users WHERE user_id=" . $user_id;
		$result = $conn->query($sql);

		if (!empty($result)) {
			while ($row1 = $result->fetch_assoc()) {

				$f_name = $row1['first_name'];
				return $f_name;
			}
		}
	}

	public function view_orders($id)
	{
		$this->db->select("*,o.user_id as order_user,o.created_date as c_date");
		$this->db->from("orders as o");
		// $this->db->join("users as u", "u.user_id=o.user_id");
		$this->db->join("order_items as oi", "oi.order_id=o.o_id");
		$this->db->join("order_attachments as oa", "oa.order_item_id=oi.item_id");
		$this->db->join("m_category as c", "c.id=oi.category");
		$this->db->join("m_sub_category as sc", "sc.id=oi.sub_category", 'left');
		$this->db->join("m_sub_categorytwo as sct", "sct.id=oi.sub_category_two", 'left');
		$this->db->join("m_service_hours as h", "h.id=oi.service_time");
		$this->db->where("oi.item_id", $id);
		$query = $this->db->get()->result();
		$img = array();
		foreach ($query as $q) {
			$img[] = $q->attachment;
		}

		// print_r($query);
		// die();
		$this->db->select("*,o.user_id as order_user,o.created_date as c_date");
		$this->db->from("orders as o");
		//$this->db->join("users as u","u.user_id=o.user_id");
		$this->db->join("order_items as oi", "oi.order_id=o.o_id");
		//$this->db->join("order_attachments as oa","oa.order_item_id=oi.item_id");
		$this->db->join("m_category as c", "c.id=oi.category");
		$this->db->join("m_sub_category as sc", "sc.id=oi.sub_category", 'left');
		$this->db->join("m_sub_categorytwo as sct", "sct.id=oi.sub_category_two", 'left');
		$this->db->join("m_service_hours as h", "h.id=oi.service_time");
		$this->db->where("oi.item_id", $id);
		$result = $this->db->get()->result();
		foreach ($result as $r) {
			$data[] = array(
				'img' => $img,
				'user_id' => $r->order_user,
				'order_id' => $r->o_id,
				'med_number'=>$r->med_number,
				'pregnancy'=>$r->pregnancy,
				'menstrual_period'=>$r->menstrual_period,
				'abortion'=>$r->abortion,
				'contraceptive_pills'=>$r->contraceptive_pills,
				'x_ray'=>$r->x_ray,
				'source'=>$r->source,
				'remarks'=>$r->remarks,
				'category_name' => $r->category_name,
				'sub_category_name' => $r->sub_category_name,
				'sub_categorytwo' => $r->sub_categorytwo,
				'gender' => $r->gender,
				'service_hour' => $r->service_hour,
				'amount' => $r->amount,
				'c_date' => $r->c_date,
				'created_time'=>$r->created_time,
				
			);
		}
		$view_data['data']=$data;
		$view_data['payment_details']=$this->payment_details($id);
		$data = array(
			'page_title' => 'Leads',
			'title' => 'Leads',
			'content' => $this->load->view('pages/leads/details_new', $view_data, TRUE),
		);
		$this->load->view('template/base_template', $data);
	}

	public function payment_details($id){
		$this->db->select("*");
		$this->db->from("payments");
		$this->db->where("order_id",$id);
		$query=$this->db->get();
		return $query->result();
	}
}
