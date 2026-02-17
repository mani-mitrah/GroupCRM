<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends MY_Controller
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
            $view_data['default']    = $this->mcommon->specific_fields_records_all('lead_packages');
            $view_data['page_title'] = "Lead Packages";
            $data                    = array(
                'title' => 'Lead Packages',
                'content' => $this->load->view('leads/packages/show', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    //deprecated
    public function add()
    {
        if ($this->verify_min_level(1)) {
            $view_data = array();
            
            
            if (isset($_POST['submit'])) {
                //Receive Values
                $package_name = $this->input->post('package_name');
                
                //Set validation Rules 
                $this->form_validation->set_rules('package_name', 'Package Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'package_name' => $package_name
                    );
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('lead_packages', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Package added successfully!');
                        redirect('leads/settings/packages/');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Add Lead Package";
            $data                    = array(
                'title' => 'Add new Package',
                'content' => $this->load->view('leads/packages/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    //deprecated
    public function edit($id)
    {
        if ($this->verify_min_level(1)) {
            $view_data = array();
            
            
            if (isset($_POST['submit'])) {
                //Receive Values
                $package_name = $this->input->post('package_name');
                $is_active     = $this->input->post('is_active');
                
                //Set validation Rules 
                $this->form_validation->set_rules('package_name', 'Package Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'package_name' => $package_name,
                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('lead_packages', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Package updated successfully!');
                        redirect('leads/settings/packages');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('lead_packages', array(
                'id' => $id
            ));
            $view_data['page_title'] = "Edit Package";
            $data                    = array(
                'title' => 'Package',
                'content' => $this->load->view('leads/packages/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    //deprecated
    public function status_change($id)
    {
        
        $current_status = $this->mcommon->specific_row_value('lead_packages', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('lead_packages', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('leads/settings/packages');
    }
}