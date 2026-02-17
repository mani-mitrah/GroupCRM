<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lstatus extends MY_Controller
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
            $view_data['default']    = $this->mcommon->specific_fields_records_all('lead_status');
            $view_data['page_title'] = "Lead Status";
            $data                    = array(
                'title' => 'Lead Status',
                'content' => $this->load->view('leads/status/show', $view_data, TRUE)
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
                $status_name = $this->input->post('status_name');
                
                //Set validation Rules 
                $this->form_validation->set_rules('status_name', 'Status Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'status_name' => $status_name
                    );
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('lead_status', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Status added successfully!');
                        redirect('leads/settings/lstatus/');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Add Lead Status";
            $data                    = array(
                'title' => 'Add new Status',
                'content' => $this->load->view('leads/status/add', $view_data, TRUE)
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
                $status_name = $this->input->post('status_name');
                $is_active     = $this->input->post('is_active');
                
                //Set validation Rules 
                $this->form_validation->set_rules('status_name', 'Status Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'status_name' => $status_name,
                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('lead_status', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Status updated successfully!');
                        redirect('leads/settings/lstatus');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('lead_status', array(
                'id' => $id
            ));
            $view_data['page_title'] = "Edit Status";
            $data                    = array(
                'title' => 'Status',
                'content' => $this->load->view('leads/status/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($id)
    {
        $current_status = $this->mcommon->specific_row_value('lead_status', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('lead_status', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('leads/settings/lstatus');
    }
}