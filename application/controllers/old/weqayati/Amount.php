<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Amount extends MY_Controller
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
            $view_data['default']    = $this->mm->get_amount();
            $view_data['page_title'] = "Amount";
            $data                    = array(
                'title' => 'Amount',
                'content' => $this->load->view('admin/amount/show', $view_data, TRUE)
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
                $service_hours = $this->input->post('service_hours');
                $amount = $this->input->post('amount');
                $medical_ref = $this->input->post('medical_ref');
                $sub_category_two = $this->input->post('sub_category_two');

                //Set validation Rules 
                $this->form_validation->set_rules('category', 'Category Name', 'required');

                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {

                    if ($medical_ref) {
                        $insert_array = array(
                            'category_id' => $category,
                            'service_hours' => $service_hours,
                            'amount' => $amount,
                            'is_active' => 1
                        );
                        $insert = $this->mcommon->common_insert('service_amount_mrn_yes', $insert_array);
                    } else {
                        //prepare insert array
                        $insert_array = array(
                            'category' => $category,
                            'sub_category' => $sub_category,
                            'sub_category_two' => $sub_category_two,
                            'gender' => $gender,
                            'medical_ref' => $medical_ref,
                            'service_hours' => $service_hours,
                            'amount' => $amount,
                            'created_date' => date('d-m-Y'),
                        );
                        //insert values in database
                        $insert = $this->mcommon->common_insert('service_amount', $insert_array);
                    }


                    if ($insert > '0') {
                        $this->session->set_flashdata('alert_success', 'Amount added successfully!');
                        redirect('weqayati/amount');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['page_title'] = "Amount";
            $view_data['category'] = $this->category();
            $view_data['sub_category'] = $this->sub_category();
            $view_data['m_sub_categorytwo'] = $this->m_sub_categorytwo();
            $view_data['service_hours'] = $this->service_hours();
            $data                    = array(
                'title' => 'Amount',
                'content' => $this->load->view('admin/amount/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }

    public function category()
    {
        $this->db->select("*");
        $this->db->from("m_category");
        $this->db->where("is_active", 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function sub_category()
    {
        $this->db->select("*");
        $this->db->from("m_sub_category");
        $this->db->where("is_active", 1);
        $query = $this->db->get();
        return $query->result();
    }
    public function m_sub_categorytwo()
    {
        $this->db->select("*");
        $this->db->from("m_sub_categorytwo");
        $this->db->where("is_active", 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function service_hours()
    {
        $this->db->select("*");
        $this->db->from("m_service_hours");
        $this->db->where("is_active", 1);
        $query = $this->db->get();
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
                $service_hours = $this->input->post('service_hours');
                $amount = $this->input->post('amount');
                $medical_ref = $this->input->post('medical_ref');
                $sub_category_two = $this->input->post('sub_category_two');

                $this->form_validation->set_rules('category', 'Category Name', 'required');

                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) {
                    //prepare insert array
                    $update_array = array(
                        'category' => $category,
                        'sub_category' => $sub_category,
                        'sub_category_two' => $sub_category_two,
                        'gender' => $gender,
                        'medical_ref' => $medical_ref,
                        'service_hours' => $service_hours,
                        'amount' => $amount,
                    );
                    //insert values in database
                    $update      = $this->mcommon->common_edit('service_amount', $update_array, array(
                        'sa_id' => $id
                    ));

                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Service Amount updated successfully!');
                        redirect('weqayati/amount');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
            }
            $view_data['default']    = $this->mcommon->specific_row('service_amount', array(
                'sa_id' => $id
            ));

            $view_data['page_title'] = "Edit Amount";
            $view_data['category'] = $this->category();
            $view_data['sub_category'] = $this->sub_category();
            $view_data['sub_category'] = $this->sub_category();
            $view_data['service_hours'] = $this->service_hours();
            $data = array(
                'title' => 'Edit Amount',
                'content' => $this->load->view('admin/amount/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }

    public function delete($id)
    {
        $this->db->where("sa_id", $id);
        $status = $this->db->delete("service_amount");
        if ($status > '0') {
            $this->session->set_flashdata('alert_success', 'Amount Deleted successfully!');
            redirect('weqayati/amount');
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
    public function get_sub_category_two()
    {
        $sub_category_id = $this->input->post("category_id");
        $this->db->select("*");
        $this->db->from("m_sub_categorytwo");
        $this->db->where("sub_category", $sub_category_id);
        $this->db->where("is_active", 1);
        $query = $this->db->get();
        $result = $query->result();
        echo json_encode($result);
    }
}
