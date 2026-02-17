<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Livechat extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->model('app_model');
		$this->load->model('user_model');
		$this->load->model('leads_model');
		$this->load->model('authentication_model');
	}

	public function index()
	{
		if ($this->verify_min_level(1)) 
		{
			/*if($this->auth_user_role==1 || $this->auth_user_role==6)
			{
		    	$view_data['enquiries'] = $this->mcommon->specific_fields_records_all('enquiry',array('company_id'=>30, 'enquiry'=>1));
		    }
		    else
		    {
		    	$view_data['enquiries'] = $this->app_model->baraha_csa_enquiries();	
		    }*/

			$data = array(
				'page_title' => 'Live Chat',
				'title' => 'Live Chat',
				'content' => $this->load->view('support/dashboard', $view_data, TRUE),
			);
			$this->load->view('template/chat_template', $data);
		} else {
			redirect('login');
		}
	}
}