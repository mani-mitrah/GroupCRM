<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_category extends MY_Controller
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
            $view_data['default']    = $this->mm->sub_category_name();
           //print_r( $view_data['default'] );exit();
            $view_data['page_title'] = "Sub Category";
            $data                    = array(
                'title' => 'Sub_category',
                'content' => $this->load->view('admin/sub_category/show', $view_data, TRUE)
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
                $main_category = $this->input->post('main_category');
                $sub_category = $this->input->post('sub_category');
                $sub_cat_arabic = $this->input->post('sub_cat_arabic');
                $is_active='1';


 
                //Set validation Rules 
                $this->form_validation->set_rules('main_category', 'main_category Name', 'required');
                $this->form_validation->set_rules('sub_category', 'sub_category Name', 'required');
                $this->form_validation->set_rules('sub_cat_arabic', 'sub_cat_arabic Name', 'required');

                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'main_category' => $main_category,
                        'sub_category_name' => $sub_category,
                        'sub_cat_arabic' => $sub_cat_arabic,
                        'is_active' => $is_active


                    );
                     // print_r($insert_array);exit('a');
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('m_sub_category', $insert_array);
                    //print_r($inser);exit('insert');
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Sub Category added successfully!');
                        redirect('weqayati/sub_category');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['m_category'] = $this->mcommon->records_all('m_category', array('is_active'=>'1'), $order_by='');

            $view_data['page_title'] = "Add New Sub Category";
            $data                    = array(
                'title' => 'Sub Category',
                'content' => $this->load->view('admin/sub_category/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    
    public function edit($id)
    {
       // print_r($id);exit();

        if ($this->verify_min_level(1)) {
            $view_data = array();
            
            
            if (isset($_POST['submit'])) {
                //Receive Values
                $main_category = $this->input->post('main_category');
                $sub_category_name = $this->input->post('sub_category');

                $sub_cat_arabic = $this->input->post('sub_cat_arabic');
                $is_active     = $this->input->post('is_active');
            
                //Set validation Rules 
                $this->form_validation->set_rules('sub_category', 'Sub_Category Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                       'main_category' => $main_category,
                        'sub_category_name' => $sub_category_name,
                        'sub_cat_arabic' => $sub_cat_arabic,
                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('m_sub_category', $update_array, array(
                        'id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', ' Sub Category updated successfully!');
                        redirect('weqayati/sub_category');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
        
            $view_data['category']    = $this->mm->category_name();

            $view_data['default']    = $this->mm->sub_category_edit($id);
            $view_data['page_title'] = "Edit Sub Category";
            $data                    = array(
                'title' => 'Category',
                'content' => $this->load->view('admin/sub_category/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($sub_id)
    {
        $current_status = $this->mcommon->specific_row_value('m_sub_category', array(
            'id' => $sub_id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('m_sub_category', array(
            'is_active' => $change_status
        ), array(
            'id' => $sub_id
        ));
        redirect('weqayati/sub_category');
    }
}