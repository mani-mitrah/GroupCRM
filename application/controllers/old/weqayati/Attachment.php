<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attachment extends MY_Controller
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
            $view_data['default']    = $this->mm->attachments();
            $view_data['page_title'] = "Attachment";
            $data                    = array(
                'title' => 'Attachment',
                'content' => $this->load->view('admin/attachment/show', $view_data, TRUE)
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
                $category = $this->input->post('category');
                $sub_category = $this->input->post('sub_category');
                $gender = $this->input->post('gender');
                $gender_arabic = $this->input->post('gender_arabic');
                $no_of_attachments = $this->input->post('no_of_attachments');
                $label = $this->input->post('label');
                $label_arabic = $this->input->post('label_arabic');
                //Set validation Rules 
                $this->form_validation->set_rules('category', 'Category Name', 'required');
                $this->form_validation->set_rules('sub_category', 'Sub Category Name', 'required');
                $this->form_validation->set_rules('gender', 'Gender', 'required');
                $this->form_validation->set_rules('gender_arabic', 'Gender Arabic', 'required');
                $this->form_validation->set_rules('no_of_attachments', 'No of Attachments', 'required');
                                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $insert_array = array(
                        'category' => $category,
                        'sub_category'=>$sub_category,
                        'gender'=>$gender,
                        'gender_arabic'=>$gender_arabic,
                        'attachment_number'=>$no_of_attachments,
                        'labels'=>implode(', ',$label),
                        'labels_arabic'=>implode(', ',$label_arabic),
                        'created_date'=>date('d-m-Y'),
                    );
                    //insert values in database
                    $insert = $this->mcommon->common_insert('attachments', $insert_array);
                    
                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Attachments added successfully!');
                        redirect('weqayati/attachment');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Attachments";
            $view_data['category']=$this->category();
            $view_data['sub_category']=$this->sub_category();
            $data                    = array(
                'title' => 'Attachments',
                'content' => $this->load->view('admin/attachment/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }

    public function category(){
        $this->db->select("*");
        $this->db->from("m_category");
        $this->db->where("is_active",1);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_sub_category(){
        $category_id=$this->input->post("category_id");
        $this->db->select("*");
        $this->db->from("m_sub_category");
        $this->db->where("main_category",$category_id);
        $this->db->where("is_active",1);
        $query=$this->db->get();
        $result=$query->result();
        echo json_encode($result);
    }

    public function sub_category(){
        $this->db->select("*");
        $this->db->from("m_sub_category");
        $this->db->where("is_active",1);
        $query=$this->db->get();
        return $query->result();
    }
    
    public function edit($id)
    {
        if ($this->verify_min_level(1)) {
            $view_data = array();
            
            
            if (isset($_POST['submit'])) {
               
              //Receive Values
              $category = $this->input->post('category');
              $sub_category = $this->input->post('sub_category');
              $gender = $this->input->post('gender');
              $gender_arabic = $this->input->post('gender_arabic');
              $no_of_attachments = $this->input->post('no_of_attachments');
              $label = $this->input->post('label');
              $label_arabic = $this->input->post('label_arabic');
              //Set validation Rules 
              $this->form_validation->set_rules('category', 'Category Name', 'required');
              $this->form_validation->set_rules('sub_category', 'Sub Category Name', 'required');
              $this->form_validation->set_rules('gender', 'Gender', 'required');
              $this->form_validation->set_rules('gender_arabic', 'Gender Arabic', 'required');
              $this->form_validation->set_rules('no_of_attachments', 'No of Attachments', 'required');
                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'category' => $category,
                        'sub_category'=>$sub_category,
                        'gender'=>$gender,
                        'gender_arabic'=>$gender_arabic,
                        'attachment_number'=>$no_of_attachments,
                        'labels'=>implode(', ',$label),
                        'labels_arabic'=>implode(', ',$label_arabic),
                    );

                    // print_r($id);
                    // die();
                    //insert values in database
                    $update       = $this->mcommon->common_edit('attachments', $update_array, array(
                        'a_id' => $id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Attachments updated successfully!');
                        redirect('weqayati/attachment');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('attachments', array(
                'a_id' => $id
            ));
            $view_data['labels']=$this->mcommon->specific_row_value('attachments',array('a_id'=>$id),'labels');
            $view_data['labels_arabic']=$this->mcommon->specific_row_value('attachments',array('a_id'=>$id),'labels_arabic');
            $view_data['page_title'] = "Edit Attachments";
            $view_data['category']=$this->category();
            $view_data['sub_category']=$this->sub_category();

            $data = array(
                'title' => 'Edit Attachments',
                'content' => $this->load->view('admin/attachment/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }

    public function delete($id){
        $this->db->where("a_id",$id);
        $status=$this->db->delete("attachments");
   
            if ($status > '0') {
                $this->session->set_flashdata('alert_success', 'Attachments Deleted successfully!');
                redirect('weqayati/attachment');
            } else {
                $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
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