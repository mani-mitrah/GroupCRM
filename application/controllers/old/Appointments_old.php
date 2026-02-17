<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointments extends MY_Controller
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
			$view_data['appointments'] = $this->appointments();
			// print_r($view_data);
			// die();
			$data = array(
				'page_title' => 'Appointments',
				'title' => 'Appointments',
				'content' => $this->load->view('pages/appointments/view', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function appointments(){
		$this->db->select("*,sa.user_id as user");
		$this->db->from("service_appointment as sa");
		$this->db->join("order_items as oi","sa.order_item_id=oi.item_id");
		$this->db->join("orders as o","o.o_id=oi.order_id");
	    $this->db->join("m_category as c","c.id=oi.category");
		$this->db->join("m_sub_category as sc","sc.id=oi.sub_category",'left');
		$this->db->join("m_sub_categorytwo as sct","sct.id=oi.sub_category_two",'left');
		$this->db->join("m_service_hours as h","h.id=oi.service_time");
		$query=$this->db->get();
		return $query->result();
	}
}
