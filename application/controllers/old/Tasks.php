<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends MY_Controller
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
			$view_data = array();
			$data = array(
				'page_title' => 'Tasks',
				'title' => 'Tasks',
				'content' => $this->load->view('pages/tasks/index', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
}
