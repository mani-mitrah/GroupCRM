<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Meeting extends MY_Controller
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

	public function manage()
	{
		if ($this->verify_min_level(1)) 
		{
			if($this->auth_user_role==1 || $this->auth_user_role==6)
			{
		    	$view_data['upcoming_meetings'] = $this->leads_model->get_lead_meetings(0);
		    	$view_data['completed_meetings'] = $this->leads_model->get_lead_meetings(1);
		    }
		    else
		    {
		    	$view_data['upcoming_meetings'] = $this->leads_model->get_lead_meetings(0,$this->auth_user_id);
		    	$view_data['completed_meetings'] = $this->leads_model->get_lead_meetings(1,$this->auth_user_id);
		    }

			$data = array(
				'page_title' => 'Meetings',
				'title' => 'Meetings',
				'content' => $this->load->view('leads/meetings/list', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}
}