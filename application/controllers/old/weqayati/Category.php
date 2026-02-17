<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller
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
            $view_data['default']    = $this->mm->category_name();
            $view_data['page_title'] = "Category";
            $data                    = array(
                'title' => 'Category',
                'content' => $this->load->view('admin/category/show', $view_data, TRUE)
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
                $category_name = $this->input->post('category');
                 $category_arabic = $this->input->post('category_arabic');
                //print_r($category_name);exit('a');
                //Set validation Rules 
                $this->form_validation->set_rules('category', 'Category Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'category_name' => $category_name,
                        'category_arabic' => $category_arabic
                    );
                     // print_r($insert_array);exit('a');
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('m_category', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Category added successfully!');
                        redirect('weqayati/category');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Add New Category";
            $data                    = array(
                'title' => 'Category',
                'content' => $this->load->view('admin/category/add', $view_data, TRUE)
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
                $category_name = $this->input->post('category');
                $category_arabic = $this->input->post('category_arabic');
                $is_active     = $this->input->post('is_active');
                
                //Set validation Rules 
                $this->form_validation->set_rules('category', 'Category Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'category_name' => $category_name,
                        'category_arabic' => $category_arabic,
                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('m_category', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Category updated successfully!');
                        redirect('weqayati/category');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('m_category', array(
                'id' => $id
            ));
            $view_data['page_title'] = "Edit Category";
            $data                    = array(
                'title' => 'Category',
                'content' => $this->load->view('admin/category/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($id)
    {
        
        $current_status = $this->mcommon->specific_row_value('m_category', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('m_category', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('weqayati/category');
    }
}