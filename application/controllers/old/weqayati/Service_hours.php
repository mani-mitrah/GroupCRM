<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_hours extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
         $this->lang->load("app", "arabic");
         $this->lang->load("app", "english");
        $this->is_logged_in();
        $this->load->library('form_validation');

    }
    
    public function index()
    {
        if ($this->verify_min_level(1)) {
            $view_data               = array();
            $view_data['default']    = $this->mm->service_hours();
            $view_data['page_title'] = "Service Hours";
            $data                    = array(
                'title' => 'Service Hours',
                'content' => $this->load->view('admin/service/show', $view_data, TRUE)
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
                $service_hour = $this->input->post('service_hour');
                $service_hour_arabic = $this->input->post('service_hour_arabic');
                //Set validation Rules 
                $this->form_validation->set_rules('service_hour', 'Service Hours', 'required');
                $this->form_validation->set_rules('service_hour_arabic', 'ساعات الخدمة', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'service_hour' => $service_hour,
                        'service_hour_arabic' => $service_hour_arabic,
                    );
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('m_service_hours', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Service Hours added successfully!');
                        redirect('weqayati/service_hours');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Service Hours";
            $data                    = array(
                'title' => 'Service Hours',
                'content' => $this->load->view('admin/service/add', $view_data, TRUE)
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
                $service_hour = $this->input->post('service_hour');
                $service_hour_arabic = $this->input->post('service_hour_arabic');
                $is_active     = $this->input->post('is_active');
                
                //Set validation Rules 
                $this->form_validation->set_rules('service_hour', 'Service Hours', 'required');
                $this->form_validation->set_rules('service_hour_arabic', 'ساعات الخدمة', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'service_hour' => $service_hour,
                        'service_hour_arabic' => $service_hour_arabic,
                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('m_service_hours', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Service Hours updated successfully!');
                        redirect('weqayati/service_hours');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('m_service_hours', array(
                'id' => $id
            ));
            $view_data['page_title'] = "Service Hours";
            $data                    = array(
                'title' => 'Service Hours',
                'content' => $this->load->view('admin/service/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($id)
    {
        
        $current_status = $this->mcommon->specific_row_value('m_service_hours', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('m_service_hours', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('weqayati/service_hours');
    }
}