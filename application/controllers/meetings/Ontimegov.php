<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ontimegov extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->model('app_model');
		$this->load->model('user_model');
		$this->load->model('enquiry_model');
	}

	public function index()
	{
		if ($this->verify_min_level(1)) 
		{
			$view_data['website_details'] = $this->mcommon->specific_row('ontime_websites', array('id'=>'502'));
			if($this->auth_user_role==1 || $this->auth_user_role==6)
			{
		    	$view_data['enquiries'] = $this->app_model->meetings(502);
		    }
		    else
		    {
		    	$view_data['enquiries'] = $this->app_model->csa_meetings(502);
		    	log_message('error',$this->db->last_query());	
		    }

			$data = array(
				'page_title' => 'Meetings',
				'title' => 'Meetings',
				'content' => $this->load->view('pages/meetings/ontimegov/list', $view_data, TRUE),
			);
			$this->load->view('template/full_template', $data);
		} else {
			redirect('login');
		}
	}


	public function assign()
	{
		if ($this->verify_min_level(1)) 
		{
			$view_data['website_details'] = $this->mcommon->specific_row('ontime_websites', array('id'=>'502'));
			//if super administrator or coordinator
			if($this->auth_user_role==1 || $this->auth_user_role==6)
			{
		    	$view_data['enquiries'] = $this->app_model->unassigned_meetings(502);
		    	log_message('error',$this->db->last_query());
		    	$view_data['csas'] = $this->user_model->website_csas(502);
				$data = array(
					'page_title' => 'Un-assigned Meetings',
					'title' => 'Un-assigned Meetings',
					'content' => $this->load->view('pages/meetings/ontimegov/unassigned_list', $view_data, TRUE),
				);
				$this->load->view('template/full_template', $data);
			}
		} 
		else 
		{
			redirect('login');
		}
	}

	public function view()
	{
		if ($this->verify_min_level(1)) 
		{
			$enquiry_id=$_GET['id'];
			//$this->load->model('order_model');
		    $view_data['enquiry_details'] = $this->mcommon->specific_row('enquiry', array('id'=>$enquiry_id));
		    $view_data['website_details'] = $this->mcommon->specific_row('ontime_websites', array('id'=>'502'));

		    $view_data['timeline'] = $this->enquiry_model->enquiry_timeline($enquiry_id);
			$view_data['followup_actions'] = $this->mcommon->specific_fields_records_all('enquiry_actions',array('id >'=>403,'id <'=>409, 'is_active'=>1));
			$view_data['complete_actions'] = $this->mcommon->specific_fields_records_all('enquiry_actions',array('id >'=>409,'id <='=>411));
			$view_data['sms_templates'] = $this->mcommon->specific_fields_records_all('lead_message_templates',array('user_id'=>$this->auth_user_id,'is_active'=>1,'template_type'=>2));
			$view_data['email_templates'] = $this->mcommon->specific_fields_records_all('lead_message_templates',array('user_id'=>$this->auth_user_id,'is_active'=>1,'template_type'=>1));

			$view_page_url = 'meetings/ontimegov/view/?id=';
			$enquiry_type=3;
			$logged_in_user_id = $this->auth_user_id;

			if(isset($_POST['action_email']))
			{
				$from_email = $this->input->post('from_email');
				$agent_email = $this->input->post('agent_email');
				$email_subject = $this->input->post('email_subject');
				$email_message = $this->input->post('email_message');
				$email_remarks = $this->input->post('email_remarks');
				$contactable_date = $this->input->post('contactable_date');

				action_email($enquiry_type,$enquiry_id,$from_email,$agent_email,$email_subject,$email_message,$email_remarks,$contactable_date,$logged_in_user_id,$view_page_url,502);
			}

			if(isset($_POST['action_call']))
			{
				$call_remarks = $this->input->post('call_remarks');
				$contactable_date = $this->input->post('contactable_date');
				
				action_call($enquiry_type,$enquiry_id,$call_remarks,$contactable_date,$logged_in_user_id,$view_page_url);
			}

			if(isset($_POST['action_sms']))
			{
				$mobile_number = $this->input->post('mobile_number');
				$message_body = $this->input->post('message_body');
				$sms_remarks = $this->input->post('sms_remarks');
				$contactable_date = $this->input->post('contactable_date');
				action_sms($enquiry_id,$mobile_number,$message_body,$sms_remarks,$contactable_date,$logged_in_user_id,$view_page_url);
			}

			if(isset($_POST['action_custom']))
			{
				$custom_remarks = $this->input->post('custom_remarks');
				$contactable_date = $this->input->post('contactable_date');

				action_custom($enquiry_id,$custom_remarks,$contactable_date,$logged_in_user_id,$view_page_url);
			}

			if(isset($_POST['action_order']))
			{
				$order_id = $this->input->post('order_id');
				action_order($enquiry_id,$order_id,$logged_in_user_id,$view_page_url);
			}

			if(isset($_POST['action_close']))
			{
				$close_remarks = $this->input->post('close_remarks');
				action_close($enquiry_id,$close_remarks,$logged_in_user_id,$view_page_url);
			}

			$data = array(
				'page_title' => 'Meetings',
				'title' => 'Meetings',
				'content' => $this->load->view('pages/enquiries/view_enquiry', $view_data, TRUE),
			);
			$this->load->view('template/full_template', $data);
		} else {
			redirect('login');
		}
	}


}