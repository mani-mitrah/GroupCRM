<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wcategories extends MY_Controller
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

            $view_data['default']    = $this->mm->website_categories();
            $view_data['page_title'] = "Website Categories";
            $data                    = array(
                'title' => 'Website Categories',
                'content' => $this->load->view('admin/wcategories/show', $view_data, TRUE)
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
                $website_id = $this->input->post('website_id');
                $category_id=$this->input->post('chkid');
                
                //Set validation Rules 
                $this->form_validation->set_rules('website_id', 'User', 'required');
                //$this->form_validation->set_rules('chkid', 'Category', 'required');

                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {
                    $add_count=0;
                    $exist_count=0;
                    for($i=0;$i<count($category_id);$i++)
                    {
                        $count = $this->mcommon->specific_record_counts("ontime_website_category_map", array("website_id"=>$website_id, "category_id"=>$category_id[$i]));
                        if($count == 0) 
                        {
                            //prepare insert array
                            $insert_array = array(
                                'website_id' => $website_id,
                                'category_id' => $category_id[$i],
                                'is_active' => 1
                            );
                            //insert values in database
                            $insert       = $this->mcommon->common_insert('ontime_website_category_map', $insert_array);
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
                            $this->session->set_flashdata('alert_success', $add_count.'categories has been mapped to the website successfully!');
                            redirect('admin/wcategories');
                    }
                    else
                    {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', $exist_count.' categories are already mapped to the selected website.');
                        redirect('admin/wcategories/add');
                    }  
                   
                   
                } 
                else
                {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }
            }

            $view_data['page_title'] = "Add lead user category";
            $view_data['websites'] = $this->mcommon->specific_fields_records_all('ontime_websites', array("is_active"=>1));
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array("is_active"=>1));
            $data                    = array(
                'title' => 'Add website category',
                'content' => $this->load->view('admin/wcategories/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
        } else {
            redirect('login');
        }
    }
    
    

    public function status_change($id)
    {
        $current_status = $this->mcommon->specific_row_value('ontime_website_category_map', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('ontime_website_category_map', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        $this->session->set_flashdata('alert_success','Status Successfully Updated.');
        redirect('admin/wcategories');
    }


    public function delete($id)
    {
        $delete = $this->mcommon->common_delete('ontime_website_category_map', array('id'=>$id));
        $this->session->set_flashdata('alert', 'danger');
        $this->session->set_flashdata('alert_danger','1 categories has been deleted to the website successfully!');
        redirect('admin/wcategories');
    }

}