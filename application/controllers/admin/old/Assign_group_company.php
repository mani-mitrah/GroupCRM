<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assign_group_company extends MY_Controller
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
            $view_data['default']    = $this->mm->group_company();
            $view_data['page_title'] = 'Assign Company';
            $data                    = array(
                'title' => 'Assign Company',
                'content' => $this->load->view('admin/assign_group_company/show', $view_data, TRUE)
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
                $group_name    = $this->input->post('group_name');
                $company_id = $this->input->post('company_id');
                //$industry = $this->input->post('industry');

                //Set validation Rules 
                $this->form_validation->set_rules('group_name', 'Group Name', 'required');
                $this->form_validation->set_rules('company_id[]', 'Company Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    $company_count = count($company_id);
                    //prepare insert array
                    for ($i = 0; $i < $company_count; $i++) {
                        $insert_array = array(
                            'group_id' => $group_name,
                            'company_id' => $company_id[$i],
                            'created_date'=>date('d-m-Y'),
                        );
                        //insert values in database
                        $insert       = $this->mcommon->common_insert('group_company', $insert_array);
                    }
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Company Added to Group successfully!');
                        redirect('admin/assign_user');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['group']       = $this->mm->get_active_groups();
            $view_data['company']    = $this->mm->company_name_active();
            $view_data['page_title'] = 'Add Group Company';
            
            $data = array(
                'title' => 'Assign Group Company',
                'content' => $this->load->view('admin/assign_group_company/add', $view_data, TRUE)
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
            
            
            if (isset($_POST['submit'])) 
            {

                // print_r($_POST);
                // die();
                //Receive Values
                $group_id    = $this->input->post('group_id');
                $company_id = $this->input->post('company_id');
                //Set validation Rules 
                $this->form_validation->set_rules('group_id', 'Group Name', 'required');
                $this->form_validation->set_rules('company_id[]', 'Company Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {
                    //prepare insert array
                    $company_count = count($company_id);
                    //prepare insert array
                    for ($i = 0; $i < $company_count; $i++) 
                    {

                        $update_array = array(
                            'group_id' => $group_id,
                            'company_id' => $company_id[$i]
                        );
                        //insert values in database
                        $update       = $this->mcommon->common_edit('group_company', $update_array, array(
                            'gc_id' => $id
                        ));
                    }
                    //print_r($update);exit('up');
                    if ($update > '0') 
                    {
                        $this->session->set_flashdata('alert_success', 'Company Assigned to Group successfully!');
                        redirect('admin/assign_group_company');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }

                
            }
            $view_data['default']    = $this->mcommon->specific_row('group_company', array(
                'gc_id' => $id
            ));
            $view_data['group']       = $this->mm->get_active_groups();
            $view_data['company']    = $this->mm->company_name_active();
            $view_data['page_title'] = 'Edit Group Company';
            $data                    = array(
                'title' => 'Edit Group Company',
                'content' => $this->load->view('admin/assign_group_company/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
}