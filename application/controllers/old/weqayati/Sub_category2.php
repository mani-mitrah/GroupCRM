<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_category2 extends MY_Controller
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
            $view_data['default']    = $this->mm->sub_category2_name();
            $view_data['page_title'] = "Sub Category Two";
            $data                    = array(
                'title' => 'Sub_category2',
                'content' => $this->load->view('admin/sub_category2/show', $view_data, TRUE)
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
                $sub_categorytwo = $this->input->post('sub_categorytwo');
                $sub_cattwo_arabic = $this->input->post('sub_cattwo_arabic');
                $is_active='1';

 
                //Set validation Rules 
                $this->form_validation->set_rules('main_category', 'main_category Name', 'required');
                $this->form_validation->set_rules('sub_category', 'sub_category Name', 'required');
                $this->form_validation->set_rules('sub_categorytwo', 'sub_categorytwo Name', 'required');
                $this->form_validation->set_rules('sub_cattwo_arabic', 'sub_cat_arabic Name', 'required');


                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'main_category' => $main_category,
                        'sub_category' => $sub_category,
                        'sub_categorytwo' => $sub_categorytwo,
                        'sub_cattwo_arabic' => $sub_cattwo_arabic,

                        'is_active' => $is_active


                    );
                    //insert values in database
                    $insert       = $this->mcommon->common_insert('m_sub_categorytwo', $insert_array);
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Sub Category two added successfully!');
                        redirect('weqayati/sub_category2');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['m_category'] = $this->mcommon->records_all('m_category', array('is_active'=>'1'), $order_by='');

            $view_data['page_title'] = "Addv New Sub Category Two";
            $data                    = array(
                'title' => 'Sub Category',
                'content' => $this->load->view('admin/sub_category2/add', $view_data, TRUE)
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
               $main_category = $this->input->post('main_category');
                $sub_category = $this->input->post('sub_category');
                $sub_categorytwo = $this->input->post('sub_categorytwo');

                $sub_cattwo_arabic = $this->input->post('sub_cattwo_arabic');
                $is_active     = $this->input->post('is_active');
            
                //Set validation Rules 
                $this->form_validation->set_rules('sub_categorytwo', 'Sub_Category Name', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'main_category' => $main_category,
                        'sub_category' => $sub_category,
                        'sub_categorytwo' => $sub_categorytwo,
                        'sub_cattwo_arabic' => $sub_cattwo_arabic,

                        'is_active' => $is_active
                    );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('m_sub_categorytwo', $update_array, array(
                        'id' => $id
                    ));
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', ' Sub Category Two updated successfully!');
                        redirect('weqayati/sub_category2');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
                     

             $view_data['default']    = $this->mm->sub_categorytwo_edit($id);
             $view_data['category']    = $this->mm->category_name();
             $view_data['sub_cat']    = $this->mm->sub_category_name();

            $view_data['page_title'] = "Edit Sub Category Two";
            $data                    = array(
                'title' => 'Sub Category Two',
                'content' => $this->load->view('admin/sub_category2/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    public function status_change($id)
    {

        
        $current_status = $this->mcommon->specific_row_value('m_sub_categorytwo', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('m_sub_categorytwo', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('weqayati/sub_category2');
    }
    public function get_subcategory()
    {

        $main_category = $this->input->post("main_category");
       // print_r($main_category);exit(); 
         $this->db->select('*');
         $this->db->from('m_sub_category as s');
         //$this->db->join('m_category as c','c.id=s.main_category');
         $this->db->where('s.main_category',$main_category);
         $this->db->where('s.is_active',1);
         $query = $this->db->get()->result();
         echo json_encode($query);
    }
}