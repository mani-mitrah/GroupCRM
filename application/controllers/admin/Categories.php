<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends MY_Controller
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
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories');
            $view_data['page_title']="CRM Categories";
			$data = array(
				'title' => 'CRM Categories',
				'content' => $this->load->view('admin/categories', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
}