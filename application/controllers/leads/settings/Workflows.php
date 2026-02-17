<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Workflows extends MY_Controller
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
        if ($this->verify_min_level(1)) {
            $view_data = array();
            $view_data['default']    = $this->mm->workflows();
            $view_data['page_title'] = "Workflows";
            $data                    = array(
                'title' => 'Lead Workflows',
                'content' => $this->load->view('leads/workflows/show', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }

    public function add()
    {
        if ($this->verify_min_level(1)) {
            $view_data = array(); 

            if (isset($_POST['submit'])) {
                //Receive Values
                $workflow_name = $this->input->post('workflow_name');
                $workflow_description = $this->input->post('workflow_description');
                $service_id = $this->input->post('service_id');
   
                //Set validation Rules 
                $this->form_validation->set_rules('workflow_name', 'Workflow Name', 'required');
                $this->form_validation->set_rules('workflow_description', 'Workflow Description', 'required');
                $this->form_validation->set_rules('service_id', 'Service', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {

                   $count = $this->mcommon->specific_record_counts("lead_workflows", array("parent_service_id"=>$service_id));
                   if($count == 0) {
                        //prepare insert array
                        $insert_array = array(
                            'workflow_name' => $workflow_name,
                            'workflow_desc' => $workflow_description,
                            'parent_service_id' => $service_id
                        );
                        //insert values in database
                        $insert = $this->mcommon->common_insert('lead_workflows', $insert_array);
                        
                        if ($insert > 0) {
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'Workflow has been created successfully!');
                            redirect('leads/settings/workflows/add');
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Something went wrong. Please try again later');
                        }
                   } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'There is an existing workflow for the selected service.');
                   } 
                } 
                else
                {
                    $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message',validation_errors());
                }
            }

            $view_data['page_title'] = "Add Workflow";
            $view_data['services'] = $this->mcommon->specific_fields_records_all('ontime_category_services_', array("is_active"=>1));
            $data                    = array(
                'title' => 'Add new package service',
                'content' => $this->load->view('leads/workflows/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }

    public function edit($id)
    {
        if ($this->verify_min_level(1)) {
            $view_data = array(); 

            if (isset($_POST['submit'])) {
                //Receive Values
                $workflow_name = $this->input->post('workflow_name');
                $workflow_description = $this->input->post('workflow_description');
                $service_id = $this->input->post('service_id');
   
                //Set validation Rules 
                $this->form_validation->set_rules('workflow_name', 'Workflow Name', 'required');
                $this->form_validation->set_rules('workflow_description', 'Workflow Description', 'required');
                $this->form_validation->set_rules('service_id', 'Service', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {

                    //prepare insert array
                    $update_array = array(
                        'workflow_name' => $workflow_name,
                        'workflow_desc' => $workflow_description,
                        'parent_service_id' => $service_id
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('lead_workflows', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Lead workflow has been updated successfully!');
                        redirect('leads/settings/workflows');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                } 
                else
                {
                    $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message',validation_errors());
                }
            }

            $view_data['default']    = $this->mcommon->specific_row('lead_workflows', array(
                'id' => $id
            ));
            $view_data['services'] = $this->mcommon->specific_fields_records_all('ontime_category_services_', array("is_active"=>1));
            $view_data['page_title'] = "Edit Workflow";
            $data                    = array(
                'title' => 'Workflows',
                'content' => $this->load->view('leads/workflows/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }

    public function status($id)
    {
        $current_status = $this->mcommon->specific_row_value('lead_workflows', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('lead_workflows', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('leads/settings/workflows');
    }
}