<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Websites extends MY_Controller
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
            $view_data['websites'] = $this->mcommon->specific_fields_records_all('ontime_websites');
            $view_data['page_title']="ONTIME Websites";
			$data = array(
				'title' => 'ONTIME Websites',
				'content' => $this->load->view('admin/websites', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
}