<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branches extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
        $this->load->library('form_validation');
        $this->load->helper('datatables');
        $this->load->helper(array('form','url'));
        $this->load->model('user_model');
        $this->load->model('app_model');
        $this->load->model('leads_model');
    }
    
    public function index()
    {
        if ($this->verify_min_level(1)) {
            $view_data = array();
            $view_data['default']    = $this->app_model->branches();
            //print_r($view_data);die;
            $view_data['page_title'] = "CRM Branches";
            $data                    = array(
                'title' => 'CRM Branches',
                'content' => $this->load->view('admin/branch/show', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }
    
    public function branch_add()
    {
        if ($this->verify_min_level(1)) {
            $view_data = array(); 

            if (isset($_POST['submit'])) {
                //Receive Values
                $branch_name = $this->input->post('branch_name');
                $branch_code = $this->input->post('branch_code');
                $is_active= $this->input->post('is_active');
                //Set validation Rules 
                $this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
                //$this->form_validation->set_rules('chkid', 'Category', 'required');

                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {
                    $config['upload_path']='./uploads';
                    $config['allowed_types']='gif|jpg|jpeg|png';
                    $config['max_size']=100;
                    $config['max_width']=1024;
                    $config['max_height']=768;
                    $this->load->library('upload',$config);
                    if(!$this->upload->do_upload('branch_icon'))
                    {
                        $error=array('error'=>$this->upload->display_errors());
                        $view_data['errors'] = $error;
                                              //print_r($view_data);die;
                    }
                    else if($this->upload->do_upload('branch_icon'))
                    {
                        $upload_data=$this->upload->data();
                        $file_name =$upload_data['file_name'];
                        //echo $file_name;die;
                        $payment_allowed = $this->input->post('is_allow_payment');
                        if ($payment_allowed === 'Yes') {
                            $payment_allowed = 1;
                        } elseif ($payment_allowed === 'No') {
                            $payment_allowed = 0;
                        }
                               $file_name="";
                        $user_id = $this->input->post('user_id');
                         $count = $this->mcommon->specific_record_counts("ontime_branches", array("branch_name"=>$branch_name));
                          $file_name =$upload_data['file_name'];
                        if($count == 0) 
                        {
                            //prepare insert array
                            $insert_array = array(
                                'branch_name' => $branch_name,
                                'branch_code' => $branch_code,
                                'branch_icon'=>$file_name,
                                'is_active' => 1,
                                'is_allow_payment' =>$payment_allowed                            
                            );
                            //insert values in database
                           // print_r($insert_array);die;
                            $insert       = $this->mcommon->common_insert('ontime_branches', $insert_array);
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_success', 'Branch has been Created successfully!');
                            redirect('admin/branch');
                       
                    } else {
						// print_r($insert_array);
						// exit();
						// log_message('error',print_r($insert_array));
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_danger', 'Branch Name or Branch Code already Exist');
                        redirect('admin/branch');
					}
                }
            }
        }
            $view_data['page_title'] = "Add Branch";
            
            log_message('error',$this->db->last_query());
            $data                    = array(
                'title' => 'Add Branch',
                'content' => $this->load->view('admin/branch/add', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
    

    
    
    }  
    else {
            redirect('login');
        }
    }

    //deprecated
    public function branch_edit($id)
    {
        if ($this->verify_min_level(1)) {
            $view_data = array();
            if (isset($_POST['submit'])) {
                //Receive Values
                 $branch_name = $this->input->post('branch_name');
                $branch_code = $this->input->post('branch_code');
                $is_active= $this->input->post('is_active');
                //Set validation Rules 
                $this->form_validation->set_rules('branch_name', 'Branch Name', 'required');
                //$this->form_validation->set_rules('chkid', 'Category', 'required');

                
                //check is the validation returns no error
                if ($this->form_validation->run() == TRUE) 
                {
                    $config['upload_path']='./uploads';
                    $config['allowed_types']='gif|jpg|jpeg|png';
                    $config['max_size']=100;
                    $config['max_width']=1024;
                    $config['max_height']=768;
                    $this->load->library('upload',$config);
                 //COMMENT THE CODE BECAUSE IT NOT SHOWS THE VALIDATION ERROR FOR UNSUPPORTED FILE FORMAT.
                     if(!$this->upload->do_upload('branch_icon'))
                    {
                         $branch_id = $id;
                        $error=array('error'=>$this->upload->display_errors());
                       // $view_data['errors'] = $error; 
                      //  if($error[0]="You did not select a file to upload.")
                        // {
                        //     $error=array();
                        //      $view_data['errors'] = $error;
                        // }
                        // Normalize the error string and compare
                        if (isset($error['error']) && strip_tags(trim($error['error'])) === "You did not select a file to upload.") {
                            $error = array(); // Clear the error array
                        }
                        $view_data['errors'] = $error;
                        $cnt=count($view_data['errors']); 
                        if($cnt>0)
                        {
                           
                        }
                        else
                        {
                         $payment_allowed = $this->input->post('is_allow_payment');
                        
                        $is_active = $this->input->post('is_active');
                    //prepare insert array
                      
                        $update_array = array(
                                'branch_name' => $branch_name,
                                'branch_code' => $branch_code,
                                'is_active' => $is_active,
                                'is_allow_payment' =>$payment_allowed,
                                'updated_by'=> $this->auth_user_id                             
                            );
                    //insert values in database
                      //  print_r($update_array);die;
                    $update       = $this->mcommon->common_edit('ontime_branches', $update_array, array(
                        'id' => $branch_id
                    ));
                    
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Branch has been updated successfully!');
                        redirect('/admin/branch');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                    }
                    }
                    // if (!$this->upload->do_upload('branch_icon')) {
                    //     $branch_id = $id;
                    //     $error = array('error' => $this->upload->display_errors());
                    //     $view_data['errors'] = $error;
                    
                    //     // Check if the error is about no file selected
                    //     if (trim($error['error']) == "You did not select a file to upload.") { 
                    //         $error = array(); // Reset error array
                    //         $view_data['errors'] = $error;
                    //     }
                    
                    //     // Count errors and handle accordingly
                    //     $cnt = count($view_data['errors']); 
                    //     if ($cnt > 0) {
                           
                    //     } else {
                    //         // Continue with update logic
                    //         $payment_allowed = $this->input->post('is_allow_payment');
                    //         $is_active = $this->input->post('is_active');
                    
                    //         $update_array = array(
                    //             'branch_name' => $branch_name,
                    //             'branch_code' => $branch_code,
                    //             'is_active' => $is_active,
                    //             'is_allow_payment' => $payment_allowed,
                    //             'updated_by' => $this->auth_user_id
                    //         );
                    
                    //         $update = $this->mcommon->common_edit('ontime_branches', $update_array, array(
                    //             'id' => $branch_id
                    //         ));
                    
                    //         if ($update > 0) {
                    //             $this->session->set_flashdata('alert_success', 'Branch has been updated successfully!');
                    //             redirect('/admin/branch');
                    //         } else {
                    //             $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later.');
                    //         }
                    //     }
                    // }
                    else if($this->upload->do_upload('branch_icon'))
                    {
                        $upload_data=$this->upload->data();
                        $file_name =$upload_data['file_name'];
                        $branch_id = $id;
                       $payment_allowed = $this->input->post('is_allow_payment');
                        $is_active = $this->input->post('is_active');
                    //prepare insert array
                       $file_name =$upload_data['file_name'];
                        $update_array = array(
                                'branch_name' => $branch_name,
                                'branch_code' => $branch_code,
                                'branch_icon'=>$file_name,
                                'is_active' => $is_active,
                                'is_allow_payment' =>$payment_allowed,
                                'updated_by'=> $this->auth_user_id                                                          
                            );
                    //insert values in database
                    $update       = $this->mcommon->common_edit('ontime_branches', $update_array, array(
                        'id' => $branch_id
                    ));
                  
                    if ($update > '0') {
                        $this->session->set_flashdata('alert_success', 'Branch has been updated successfully!');
                        redirect('/admin/branch');
                    } else {
                        $this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
                    }
                }
                
                
            }
         }    
            $view_data['page_title'] = "Edit Branch";
            $view_data['branch_id'] = $id;
             $view_data['default']    = $this->mcommon->specific_row('ontime_branches', array(
                'id' => $id
            ));
          // echo "<pre>";
           //print_r($view_data);die;
            $data                    = array(
                'title' => 'Branch',
                'content' => $this->load->view('admin/branch/edit', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
    
    }
        else {
            redirect('login');
        }
     }   
    public function status_change($id)
    {
        $current_status = $this->mcommon->specific_row_value('ontime_branches', array(
            'id' => $id
        ), 'is_active');
        $change_status  = ($current_status == 1) ? 0 : 1;
        $delete         = $this->mcommon->common_edit('ontime_branches', array(
            'is_active' => $change_status
        ), array(
            'id' => $id
        ));
        $this->session->set_flashdata('alert_success', 'Status Successfully Updated.');
        redirect('admin/branch');
    }


    public function branch_delete($id)
    {
        $delete = $this->mcommon->common_delete('ontime_branches', array('id'=>$id));
        redirect('admin/branch');
    }
     public function mails($lead_id)
    {
        $view_data['lead_details'] = $this->leads_model->lead_details($lead_id);
        $view_data['lead_id']=$lead_id;
        $view_data['page_title'] = "Contact Form";
            $data                    = array(
                'title' => 'Contact Form',
                'content' => $this->load->view('admin/branch/contact_form', $view_data, TRUE)
            );
            $this->load->view('template/base_template', $data);
            
          
    }

       

}