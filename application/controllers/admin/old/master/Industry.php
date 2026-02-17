<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Industry extends MY_Controller
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
            $view_data['default']    = $this->mm->industry_name();
            $view_data['page_title'] = "Industry";
            $data                    = array(
                'title' => 'Industry',
                'content' => $this->load->view('admin/industry/show', $view_data, TRUE)
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
                $industry_name = $this->input->post('industry_name');
                
                //Set validation Rules 
                $this->form_validation->set_rules('industry_name', 'Industry Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'industry' => $industry_name
                    );
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('m_industries', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Industry added successfully!');
                        redirect('admin/master/industry');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Add Industry";
            $data                    = array(
                'title' => 'Add new Industry',
                'content' => $this->load->view('admin/industry/add', $view_data, TRUE)
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
                $industry_name = $this->input->post('industry_name');
                $is_active     = $this->input->post('is_active');
                
                //Set validation Rules 
                $this->form_validation->set_rules('industry_name', 'Industry Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'industry' => $industry_name,
                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('m_industries', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Industry updated successfully!');
                        redirect('admin/master/industry');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('m_industries', array(
                'id' => $id
            ));
            $view_data['page_title'] = "Edit Industry";
            $data                    = array(
                'title' => 'Industry',
                'content' => $this->load->view('admin/industry/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($id)
    {
        
        $current_status = $this->mcommon->specific_row_value('m_industries', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('m_industries', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('admin/master/industry');
    }
}