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
		// var_dump($_POST);exit;
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
					$insert_pre_array = array(
						'role_id' => $insert,
						'lead_reopen' => $this->input->post('lead_reopen'),
						'lead_reassign' => $this->input->post('lead_reassign'),
						'request_followup' => $this->input->post('request_followup'),
						'request_status_update'=> $this->input->post('request_status_update'),
					);
					if($this->input->post('request_followup') == 'enable'){
						$req_follow_up_arr = array(
							'setup_meeting'=> $this->input->post('setup_meeting') == 'true' ? 'true' : 'false',
							'followup_through_email' => $this->input->post('followup_through_email') == 'true' ? 'true' : 'false',
							'followup_through_call' =>$this->input->post('followup_through_call') == 'true' ? 'true' : 'false',
							'custom' => $this->input->post('custom') == 'true' ? 'true' : 'false',
							'through_payment_link' => $this->input->post('through_payment_link') == 'true' ? 'true' : 'false',
						);
						$insert_pre_array = $insert_pre_array + $req_follow_up_arr;
					}

					if($this->input->post('request_status_update') == 'enable'){
						$req_status_arr = array(
							'eligibility_check' => $this->input->post('eligibility_check') == 'true' ? 'true' : 'false',
							'enquiry' => $this->input->post('enquiry') == 'true' ? 'true' : 'false',
							'missing_documents' => $this->input->post('missing_documents') == 'true' ? 'true' : 'false',
							'order_confirm' => $this->input->post('order_confirm') == 'true' ? 'true' : 'false',
							'disqualified' => $this->input->post('disqualified') == 'true' ? 'true' : 'false',
							'lead_hold' => $this->input->post('lead_hold') == 'true' ? 'true' : 'false',
						);
						$insert_pre_array = $insert_pre_array + $req_status_arr;
					}

					if($this->input->post('calendar_role') == 'enable'){
						$req_status_cal = array(
							'calendar_role' => 'enable',
							'add_appointment_menu' => $this->input->post('add_appointment_menu') == 'true' ? 'true' : 'false',
							'booked' => $this->input->post('booked') == 'true' ? 'true' : 'false',
							'confirmed' => $this->input->post('confirmed') == 'true' ? 'true' : 'false',
							'rescheduled' => $this->input->post('rescheduled') == 'true' ? 'true' : 'false',
							'canceled' => $this->input->post('canceled') == 'true' ? 'true' : 'false',
							'no_show' => $this->input->post('no_show') == 'true' ? 'true' : 'false',
							'all_day_booking' => $this->input->post('all_day_booking') == 'true' ? 'true' : 'false',
							'allow_to_select_multiple_slot' => $this->input->post('allow_to_select_multiple_slot') == 'true' ? 'true' : 'false',
							'booking_info' => $this->input->post('booking_info') == 'true' ? 'true' : 'false',
							'show_user_fields' => $this->input->post('show_user_fields') == 'true' ? 'true' : 'false',
							'additional_info' => $this->input->post('additional_info') == 'true' ? 'true' : 'false'
						);
						$insert_pre_array = $insert_pre_array + $req_status_cal;
					} 

					$roles_pre_insert = $this->mcommon->common_insert('m_roles_privilege',$insert_pre_array);

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
					$update_pre_array = array(
						'role_id' => $id,
						'lead_reopen' => $this->input->post('lead_reopen'),
						'lead_reassign' => $this->input->post('lead_reassign'),
						'request_followup' => $this->input->post('request_followup'),
						'request_status_update'=> $this->input->post('request_status_update'),
					);
					if($this->input->post('request_followup') == 'enable'){
						$req_follow_up_arr = array(
							'setup_meeting'=> $this->input->post('setup_meeting') == 'true' ? 'true' : 'false',
							'followup_through_email' => $this->input->post('followup_through_email') == 'true' ? 'true' : 'false',
							'followup_through_call' =>$this->input->post('followup_through_call') == 'true' ? 'true' : 'false',
							'custom' => $this->input->post('custom') == 'true' ? 'true' : 'false',
							'through_payment_link' => $this->input->post('through_payment_link') == 'true' ? 'true' : 'false',
						);
					} else {
						$req_follow_up_arr = array(
							'setup_meeting'=> 'false',
							'followup_through_email' => 'false',
							'followup_through_call' => 'false',
							'custom' => 'false',
							'through_payment_link' => 'false',
						);
					}

					if($this->input->post('request_status_update') == 'enable'){
						$req_status_arr = array(
							'eligibility_check' => $this->input->post('eligibility_check') == 'true' ? 'true' : 'false',
							'enquiry' => $this->input->post('enquiry') == 'true' ? 'true' : 'false',
							'missing_documents' => $this->input->post('missing_documents') == 'true' ? 'true' : 'false',
							'order_confirm' => $this->input->post('order_confirm') == 'true' ? 'true' : 'false',
							'disqualified' => $this->input->post('disqualified') == 'true' ? 'true' : 'false',
							'lead_hold' => $this->input->post('lead_hold') == 'true' ? 'true' : 'false',
						);
					} else {
						$req_status_arr = array(
							'eligibility_check' => 'false',
							'enquiry' => 'false',
							'missing_documents' => 'false',
							'order_confirm' => 'false',
							'disqualified' => 'false',
							'lead_hold' => 'false',
						);
					}

					if($this->input->post('calendar_role') == 'enable'){
						$req_cal_arr = array(
							'calendar_role' => 'enable',
							'add_appointment_menu' => $this->input->post('add_appointment_menu') == 'true' ? 'true' : 'false',
							'booked' => $this->input->post('booked') == 'true' ? 'true' : 'false',
							'confirmed' => $this->input->post('confirmed') == 'true' ? 'true' : 'false',
							'rescheduled' => $this->input->post('rescheduled') == 'true' ? 'true' : 'false',
							'canceled' => $this->input->post('canceled') == 'true' ? 'true' : 'false',
							'no_show' => $this->input->post('no_show') == 'true' ? 'true' : 'false',
							'all_day_booking' => $this->input->post('all_day_booking') == 'true' ? 'true' : 'false',
							'allow_to_select_multiple_slot' => $this->input->post('allow_to_select_multiple_slot') == 'true' ? 'true' : 'false',
							'booking_info' => $this->input->post('booking_info') == 'true' ? 'true' : 'false',
							'show_user_fields' => $this->input->post('show_user_fields') == 'true' ? 'true' : 'false',
							'additional_info' => $this->input->post('additional_info') == 'true' ? 'true' : 'false'
						);
					} else {
						$req_cal_arr = array(
							'add_appointment_menu' => 'false',
							'booked' => 'false',
							'confirmed' => 'false',
							'rescheduled' => 'false',
							'canceled' => 'false',
							'no_show' => 'false',
							'all_day_booking' => 'false',
							'allow_to_select_multiple_slot' => 'false',
							'booking_info' => 'false',
							'show_user_fields' => 'false',
							'additional_info' => 'false',
						);
					}



					$update_pre_array = $update_pre_array + $req_follow_up_arr + $req_status_arr+$req_cal_arr;
					$is_exists_count = $this->mcommon->specific_record_counts('m_roles_privilege',array('role_id'=>$id));
					if($is_exists_count > 0){
						$roles_pre_update = $this->mcommon->common_edit('m_roles_privilege', $update_pre_array, array('role_id'=>$id));
					} else {
						$roles_pre_insert = $this->mcommon->common_insert('m_roles_privilege',$update_pre_array);
					}

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
			$view_data['default_privilege']=$this->mcommon->specific_row('m_roles_privilege',array('role_id'=>$id));
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