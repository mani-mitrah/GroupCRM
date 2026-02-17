<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assign_user extends MY_Controller
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
            $view_data['default']    = $this->mm->assign_user();
            $view_data['page_title'] = 'Assign User';
            $data                    = array(
                'title' => 'Assign User',
                'content' => $this->load->view('admin/assign_user/show', $view_data, TRUE)
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
                $user_id    = $this->input->post('user_id');
                $company_id = $this->input->post('company_id');
                //$industry = $this->input->post('industry');
                
                //Set validation Rules 
                $this->form_validation->set_rules('user_id', 'User Name', 'required');
                $this->form_validation->set_rules('company_id[]', 'Company Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    $company_count = count($company_id);
                    //prepare insert array
                    for ($i = 0; $i < $company_count; $i++) {
                        $insert_array = array(
                            'user_id' => $user_id,
                            'company_id' => $company_id[$i]
                        );
                        //insert values in database
                        $insert       = $this->mcommon->common_insert('company_users', $insert_array);
                    }
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', ' New Assign User added successfully!');
                        redirect('admin/assign_user');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['user']       = $this->mm->users_active();
            $view_data['company']    = $this->mm->company_name_active();
            $view_data['page_title'] = 'Add New Assign User';
            
            $data = array(
                'title' => 'Assign User',
                'content' => $this->load->view('admin/assign_user/add', $view_data, TRUE)
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


                //Receive Values
                $user_id    = $this->input->post('user_id');
                $company_id = $this->input->post('company_id');
                //Set validation Rules 
                $this->form_validation->set_rules('user_id', 'User Name', 'required');
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
                            'user_id' => $user_id,
                            'company_id' => $company_id[$i]
                        );
                        //insert values in database
                        $update       = $this->mcommon->common_edit('company_users', $update_array, array(
                            'id' => $id
                        ));
                    }
                    //print_r($update);exit('up');
                    if ($update > '0') 
                    {
                        $this->session->set_flashdata('alert_success', 'Update Assign User successfully!');
                        redirect('admin/assign_user');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }

                
            }
            $view_data['default']    = $this->mcommon->specific_row('company_users', array(
                'id' => $id
            ));
            $view_data['user']       = $this->mm->users_active();
            $view_data['company']    = $this->mm->company_name_active();
            $view_data['page_title'] = 'Edit Assign User';
            $data                    = array(
                'title' => 'Assign User',
                'content' => $this->load->view('admin/assign_user/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
}