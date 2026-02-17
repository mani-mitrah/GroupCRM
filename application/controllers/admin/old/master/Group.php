<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends MY_Controller
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
            $view_data['default']    = $this->mm->get_group();
            $view_data['page_title'] = "Group";
            $data                    = array(
                'title' => 'Group',
                'content' => $this->load->view('admin/group/show', $view_data, TRUE)
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
                $group = $this->input->post('group');
                
                //Set validation Rules 
                $this->form_validation->set_rules('group', 'Group Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'group_name' => $group,
                        'status'=>1,
                        'created_date'=>date('d-m-Y'),
                    );
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('group_master', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Group added successfully!');
                        redirect('admin/master/group');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Add Group";
            $data                    = array(
                'title' => 'Add new Group',
                'content' => $this->load->view('admin/group/add', $view_data, TRUE)
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
                // print_r($_POST);
                // die();
                //Receive Values
                $group_name = $this->input->post('group_name');
                $status     = $this->input->post('status');
                
                //Set validation Rules 
                $this->form_validation->set_rules('group_name', 'Group Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'group_name' => $group_name,
                        'status' => $status
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('group_master', $update_array, array(
                        'g_id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Group updated successfully!');
                        redirect('admin/master/group');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('group_master', array(
                'g_id' => $id
            ));
            $view_data['page_title'] = "Edit Group";
            $data                    = array(
                'title' => 'Group',
                'content' => $this->load->view('admin/group/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($id)
    {
        
        $current_status = $this->mcommon->specific_row_value('group_master', array(
            'g_id' => $id
        ), 'status');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('group_master', array(
            'status' => $change_status
        ), array(
            'g_id' => $id
        ));
        redirect('admin/master/group');
    }
}