<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ucategories extends MY_Controller
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
            $view_data['default']    = $this->mm->user_categories();
            $view_data['page_title'] = "Lead User Categories";
            $data                    = array(
                'title' => 'Lead User Categories',
                'content' => $this->load->view('leads/ucategories/show', $view_data, TRUE)
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
                $user_id = $this->input->post('user_id');
                $category_id=$this->input->post('chkid');
                
                //Set validation Rules 
                $this->form_validation->set_rules('user_id', 'User', 'required');
                //$this->form_validation->set_rules('chkid', 'Category', 'required');

                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {
                    $add_count=0;
                    $exist_count=0;
                    for($i=0;$i<count($category_id);$i++)
                    {
                        $count = $this->mcommon->specific_record_counts("lead_user_categories", array("user_id"=>$user_id, "category_id"=>$category_id[$i]));
                        if($count == 0) 
                        {
                            //prepare insert array
                            $insert_array = array(
                                'user_id' => $user_id,
                                'category_id' => $category_id[$i],
                                'is_active' => 1
                            );
                            //insert values in database
                            $insert       = $this->mcommon->common_insert('lead_user_categories', $insert_array);
                            if ($insert > 0) 
                            {
                                $add_count++;
                            }
                        }
                        else
                        {
                            $exist_count++;
                        }
                    
                    }

                    if($add_count > 0)
                    {
                        $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', $add_count.'categories has been mapped to the user successfully!');
                    }
                    else
                    {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', $exist_count.' categories are already mapped to the selected user.');
                    }  
                    redirect('leads/settings/ucategories/add');
                   
                } 
                else
                {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }
            }

            $view_data['page_title'] = "Add lead user category";
            $view_data['users'] = $this->mcommon->specific_fields_records_all('users', array("role_id"=>4));
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('lead_categories', array("is_active"=>1));
            $data                    = array(
                'title' => 'Add lead user category',
                'content' => $this->load->view('leads/ucategories/add', $view_data, TRUE)
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
            $view_data['services'] = $this->mcommon->specific_fields_records_all('lead_services', array("is_active"=>1));
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

    public function status_change($id)
    {
        $current_status = $this->mcommon->specific_row_value('lead_user_categories', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('lead_user_categories', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        redirect('leads/settings/ucategories');
    }


    public function delete($id)
    {
        $delete = $this->mcommon->common_delete('lead_user_categories', array('id'=>$id));
        redirect('leads/settings/ucategories');
    }

}