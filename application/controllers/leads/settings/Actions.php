<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
        $this->load->library('form_validation');
    }
    
    public function index()
    {
        if ($this->verify_min_level(1)) {
            $view_data               = array();
            $view_data['default']    = $this->mcommon->specific_fields_records_all('lead_actions');
            $view_data['page_title'] = "Lead Actions";
            $data                    = array(
                'title' => 'Lead Actions',
                'content' => $this->load->view('leads/actions/show', $view_data, TRUE)
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
                $action_name = $this->input->post('action_name');
                
                //Set validation Rules 
                $this->form_validation->set_rules('action_name', 'Action Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'action_name' => $action_name
                    );
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('lead_actions', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Action added successfully!');
                        redirect('leads/settings/actions/');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Add Lead Action";
            $data                    = array(
                'title' => 'Add new Action',
                'content' => $this->load->view('leads/actions/add', $view_data, TRUE)
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
                $action_name = $this->input->post('action_name');
                $is_active     = $this->input->post('is_active');
                
                //Set validation Rules 
                $this->form_validation->set_rules('action_name', 'Action Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'action_name' => $action_name,
                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('lead_actions', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Action updated successfully!');
                        redirect('leads/settings/actions');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('lead_actions', array(
                'id' => $id
            ));
            $view_data['page_title'] = "Edit Action";
            $data                    = array(
                'title' => 'Actions',
                'content' => $this->load->view('leads/actions/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($id)
    {
        $current_status = $this->mcommon->specific_row_value('lead_actions', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('lead_actions', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('leads/settings/actions');
    }
}