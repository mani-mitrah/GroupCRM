<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pservices extends MY_Controller
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
            $view_data = array();
            $view_data['default']    = $this->mm->package_services();
            $view_data['page_title'] = "Lead Package Services";
            $data                    = array(
                'title' => 'Lead Package Services',
                'content' => $this->load->view('leads/pservices/show', $view_data, TRUE)
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
                $package_id = $this->input->post('package_id');
                $service_id = $this->input->post('service_id');
                $govt_fee = $this->input->post('govt_fee');
                $typing_fee = $this->input->post('typing_fee');
                
                //Set validation Rules 
                $this->form_validation->set_rules('service_id', 'Service', 'required');
                $this->form_validation->set_rules('package_id', 'Package', 'required');
                $this->form_validation->set_rules('govt_fee', 'Government Fee', 'required');
                $this->form_validation->set_rules('typing_fee', 'Typing Fee', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {

                   $count = $this->mcommon->specific_record_counts("lead_package_services", array("package_id"=>$package_id, "service_id"=>$service_id));
                   if($count == 0) {
                        //prepare insert array
                        $insert_array = array(
                            'package_id' => $package_id,
                            'service_id' => $service_id,
                            'govt_fee' => $govt_fee,
                            'typing_fee' => $typing_fee
                        );
                        //insert values in database
                        $insert       = $this->mcommon->common_insert('lead_package_services', $insert_array);
                        
                        if ($insert > '0') {
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'Service has been mapped to package successfully!');
                            redirect('leads/settings/pservices/add');
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Something went wrong. Please try again later');
                        }
                   } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'This service is already mapped to the selected package. Please choose a different one and try again.');
                   } 
                } 
                else
                {
                    print_r($_POST);
                }
            }

            $view_data['page_title'] = "Add Lead package service";
            $view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array("is_active"=>1));
            $view_data['services'] = $this->mcommon->specific_fields_records_all('ontime_category_services_', array("is_active"=>1));
            $data                    = array(
                'title' => 'Add new package service',
                'content' => $this->load->view('leads/pservices/add', $view_data, TRUE)
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
                $package_id = $this->input->post('package_id');
                $service_id = $this->input->post('service_id');
                $govt_fee = $this->input->post('govt_fee');
                $typing_fee = $this->input->post('typing_fee');
                
                //Set validation Rules 
                $this->form_validation->set_rules('service_id', 'Service', 'required');
                $this->form_validation->set_rules('package_id', 'Package', 'required');
                $this->form_validation->set_rules('govt_fee', 'Government Fee', 'required');
                $this->form_validation->set_rules('typing_fee', 'Typing Fee', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'package_id' => $package_id,
                        'service_id' => $service_id,
                        'govt_fee' => $govt_fee,
                        'typing_fee' => $typing_fee
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('lead_package_services', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Package service mapping has been updated successfully!');
                        redirect('leads/settings/pservices');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('lead_package_services', array(
                'id' => $id
            ));
            $view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array("is_active"=>1));
            $view_data['services'] = $this->mcommon->specific_fields_records_all('ontime_category_services_', array("is_active"=>1));
            $view_data['page_title'] = "Edit Package Service";
            $data                    = array(
                'title' => 'Package Service',
                'content' => $this->load->view('leads/pservices/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    //deprecated
    public function delete($id)
    {
        $delete = $this->mcommon->common_delete('lead_package_services', array('id'=>$id));
        redirect('leads/settings/pservices');
    }

}