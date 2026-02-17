<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Templates extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
	}

	public function manage()
    {
        if ($this->verify_min_level(1)) {
            $view_data               = array();
            $view_data['default']    = $this->mcommon->specific_fields_records_all('lead_message_templates', array('user_id'=>$this->auth_user_id));
            $view_data['page_title'] = "Lead Message Templates";
            $data                    = array(
                'title' => 'Lead Message Templates',
                'content' => $this->load->view('leads/templates/show', $view_data, TRUE)
            );
            $this->load->view('template/base_template_v2', $data);
            
        } else {
            redirect('login');
        }
    }

    public function add() {
    	if ($this->verify_min_level(1)) {
            $view_data = array();
            if (isset($_POST['submit'])) 
            {
                //Receive Values
                $template_type = $this->input->post('template_type');
                $template_name = $this->input->post('template_name');
                $template_content = $this->input->post('template_content');
                
                //Set validation Rules 
                $this->form_validation->set_rules('template_type', 'Template Type', 'required');
                $this->form_validation->set_rules('template_name', 'Template Name', 'required');
                $this->form_validation->set_rules('template_content', 'Template Content', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'template_type' => $template_type,
                        'template_name' => $template_name,
                        'template_content' => $template_content,
                        'user_id' => $this->auth_user_id
                    );

                    //insert values in database
                    $insert       = $this->mcommon->common_insert('lead_message_templates', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Template added successfully!');
                        redirect('leads/templates/manage');
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Something went wrong. Please try again later');
                    }
                }
                else
                {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }
            }
            $view_data['page_title'] = "Add New Message Template";
            $data                    = array(
                'title' => 'Add New Message Template',
                'content' => $this->load->view('leads/templates/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template_v2', $data);
            
        } else {
            redirect('login');
        }
    }

    public function edit($id) {
        if ($this->verify_min_level(1)) {
            $view_data = array();
            if (isset($_POST['submit'])) {
                //Receive Values
                $template_type = $this->input->post('template_type');
                $template_name = $this->input->post('template_name');
                $template_content = $this->input->post('template_content');
                
                //Set validation Rules 
                $this->form_validation->set_rules('template_type', 'Template Type', 'required');
                $this->form_validation->set_rules('template_name', 'Template Name', 'required');
                $this->form_validation->set_rules('template_content', 'Template Content', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'template_type' => $template_type,
                        'template_name' => $template_name,
                        'template_content' => $template_content,
                        'user_id' => $this->auth_user_id
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('lead_message_templates', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Template updated successfully!');
                        redirect('leads/templates/manage');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('lead_message_templates', array(
                'id' => $id
            ));
            $view_data['page_title'] = "Edit Template";
            $data                    = array(
                'title' => 'Template',
                'content' => $this->load->view('leads/templates/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template_v2', $data);
            
        } else {
            redirect('login');
        }
    }

    public function status_change($id)
    { 
        $current_status = $this->mcommon->specific_row_value('lead_message_templates', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('lead_message_templates', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('leads/templates/manage');
    }
}