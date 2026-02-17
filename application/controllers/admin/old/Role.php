<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();
			$view_data['default'] = $this->mm->role_name();
			$view_data['page_title'] = 'Role';
			$data = array(
				'title' => 'Role',
				'content' => $this->load->view('admin/role/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function add()
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();


		if(isset($_POST['submit']))
		{
			//Receive Values
			$role_name = $this->input->post('role_name');
			$role_description = $this->input->post('role_description');

			//Set validation Rules 
			$this->form_validation->set_rules('role_name', 'Role Name', 'required');
			//$this->form_validation->set_rules('role_description', 'Role Description', 'required');

			//check is the validation returns no error
            if ($this->form_validation->run() == TRUE)
            {
            	//prepare insert array
            	$insert_array = array('role_name'=>$role_name,'role_description'=>$role_description,'created_date'=>date('d-m-Y'));
            	//insert values in database
            	$insert = $this->mcommon->common_insert('m_roles',$insert_array);

            	if($insert > '0')
            	{
            		$this->session->set_flashdata('alert_success', 'Role added successfully!');
            		redirect('admin/role');
            	}
            	else
            	{
            		$this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
            	}
            }
        } 
        $view_data['page_title'] = 'Add Role';
			$data = array(
				'title' => 'Add new Role',
				'content' => $this->load->view('admin/role/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
	public function edit($id)
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();


		if(isset($_POST['submit']))
		{
			//Receive Values
			$role_name = $this->input->post('role_name');
			$role_description = $this->input->post('role_description');
			$is_active = $this->input->post('is_active');

			//Set validation Rules 
			$this->form_validation->set_rules('role_name', 'Role Name', 'required');
			//$this->form_validation->set_rules('role_description', 'Role Description', 'required');

			//check is the validation returns no error
            if ($this->form_validation->run() == TRUE)
            {
            	//prepare insert array
            	$update_array = array(
            		'role_name'=>$role_name,
            		'role_description'=>$role_description,
            		'is_active'=>$is_active,
            		'created_date'=>date('d-m-Y'));
            	//insert values in database
            	$update = $this->mcommon->common_edit('m_roles',$update_array,array('role_id'=>$id));

            	if($update > '0')
            	{
            		$this->session->set_flashdata('alert_success', 'Role updated successfully!');
            		redirect('admin/role');
            	}
            	else
            	{
            		$this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
            	}
            }
        }
            $view_data['default']=$this->mcommon->specific_row('m_roles',array('role_id'=>$id));
            $view_data['page_title'] = 'Edit Role';
			$data = array(
				'title' => 'Edit Role',
				'content' => $this->load->view('admin/role/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
	public function status_change($id)
	{

		$current_status = $this->mcommon->specific_row_value('m_roles',array('role_id'=>$id),'is_active');
		$change_status = ($current_status==1)?0:1;
		$delete = $this->mcommon->common_edit('m_roles',array('is_active'=>$change_status),array('role_id'=>$id));
		redirect('admin/role');
	}
}