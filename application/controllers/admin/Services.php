<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Services extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->model('app_model');
	}
	
	public function index()
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();
            $view_data['services'] = $this->mm->lead_services();
            $view_data['page_title']="CRM Services";
			$data = array(
				'title' => 'CRM Services',
				'content' => $this->load->view('admin/services', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
}