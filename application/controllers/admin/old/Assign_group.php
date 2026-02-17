<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Assign_group extends MY_Controller
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
            $view_data['default']    = $this->mm->group_users();
            $view_data['page_title'] = 'Assign Group';
            $data                    = array(
                'title' => 'Assign group',
                'content' => $this->load->view('admin/assign_group/show', $view_data, TRUE)
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
              //  print_r($_POST);
                //Receive Values
                $group_name    = $this->input->post('group_name');
                $user_id = $this->input->post('user_id');
                //$industry = $this->input->post('industry');
                
                //Set validation Rules 
                $this->form_validation->set_rules('group_name', 'Group Name', 'required');
                $this->form_validation->set_rules('user_id[]', 'User Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    $user_count = count($user_id);
                    //prepare insert array
                    for ($i = 0; $i < $user_count; $i++) {
                        $insert_array = array(
                            'group_id' => $group_name,
                            'user_id' => $user_id[$i],
                            'created_date'=>date('d-m-Y'),
                        );
                        //insert values in database
                        $insert       = $this->mcommon->common_insert('group_users', $insert_array);
                    }
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'User Assigned successfully!');
                        redirect('admin/assign_group');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
           
            $view_data['group']    = $this->mm->get_active_groups();
            $view_data['user']       = $this->mm->users_active();
            $view_data['page_title'] = 'Assign Group';
            
            $data = array(
                'title' => 'Assign Group',
                'content' => $this->load->view('admin/assign_group/add', $view_data, TRUE)
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


                $group_name    = $this->input->post('group_id');
                $user_id = $this->input->post('user_id');
                //$industry = $this->input->post('industry');
                
                //Set validation Rules 
                $this->form_validation->set_rules('group_id', 'Group Name', 'required');
                $this->form_validation->set_rules('user_id[]', 'User Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {
                    //prepare insert array
                    $user_count = count($user_id);
                    //prepare insert array
                    for ($i = 0; $i < $user_count; $i++) 
                    {

                        $update_array = array(
                            'group_id' => $group_name,
                            'user_id' => $user_id[$i],
                        );

                        // print_r($update_array);
                        // die();
                        //insert values in database
                        $update       = $this->mcommon->common_edit('group_users', $update_array, array(
                            'gu_id' => $id
                        ));
                    }
                    //print_r($update);exit('up');
                    if ($update > '0') 
                    {
                        $this->session->set_flashdata('alert_success', 'Group Assigned successfully!');
                        redirect('admin/assign_group');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }

                
            }
            $view_data['default']    = $this->mcommon->specific_row('group_users', array(
                'gu_id' => $id
            ));
            $view_data['group']    = $this->mm->get_active_groups();
            $view_data['user']       = $this->mm->users_active();
            $view_data['page_title'] = 'Assign Group';
            $data                    = array(
                'title' => 'Assign Group',
                'content' => $this->load->view('admin/assign_group/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }

    public function get_group_user(){
        $group_id=$this->input->post("group_id");
        $this->db->select("*");
        $this->db->from("group_company");
        $this->db->where("group_id",$group_id);
        $query = $this->db->get();
        $ret = $query->row();
        $company_id=$ret->company_id;

        $this->db->select("*");
        $this->db->from("company_users as cu");
        $this->db->join("users as u","u.user_id=cu.user_id");
        $this->db->where("cu.company_id",$company_id);
        //$this->db->group_by("cu.user_id");
        $query1=$this->db->get();
        $result=$query1->result();
        echo json_encode($result);
    }
}