<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wentries extends MY_Controller
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
            $view_data['default']    = $this->mm->workflow_entries();
            $view_data['page_title'] = "Workflow Entries";
            $data                    = array(
                'title' => 'Lead Workflow Definition',
                'content' => $this->load->view('leads/wentries/show', $view_data, TRUE)
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
                $workflow_id = $this->input->post('workflow_id');
                $target_service_id = $this->input->post('target_service_id');
               
                //Set validation Rules 
                $this->form_validation->set_rules('workflow_id', 'Workflow', 'required');
                $this->form_validation->set_rules('target_service_id', 'Service', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {

                   $count = $this->mcommon->specific_record_counts("lead_workflow_entries", array("target_service_id"=>$target_service_id,"workflow_id"=>$workflow_id));
                   if($count == 0) {
                        //prepare insert array
                        $insert_array = array(
                            'workflow_id' => $workflow_id,
                            'target_service_id' => $target_service_id
                        );
                        //insert values in database
                        $insert = $this->mcommon->common_insert('lead_workflow_entries', $insert_array);
                        
                        if ($insert > 0) {
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'Service has been mapped to the selected workflow');
                            redirect('leads/settings/wentries/add');
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Something went wrong. Please try again later');
                        }
                   } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'The selected service has been already mapped to the selected workflow.');
                   } 
                } 
                else
                {
                    $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message',validation_errors());
                }
            }

            $view_data['page_title'] = "Define Workflow";
            $view_data['services'] = $this->mcommon->specific_fields_records_all('ontime_category_services_', array("is_active"=>1));
            $view_data['workflows'] = $this->mcommon->specific_fields_records_all('lead_workflows', array("is_active"=>1));
            $data = array(
                'title' => 'Define workflow',
                'content' => $this->load->view('leads/wentries/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }

    public function delete($id)
    {
        $delete = $this->mcommon->common_delete('lead_workflow_entries', array('id'=>$id));
        $this->session->set_flashdata('alert', 'success');
        $this->session->set_flashdata('alert_message', 'Service has been removed from the selected workflow');
        redirect('leads/settings/wentries');
    }

    public function get_existing()
    {
        $workflow_id=$this->input->get('workflow_id');
        $workflow_entries = $this->mm->workflow_entries($workflow_id);
        echo json_encode($workflow_entries);
    }
}