<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lead extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
        $this->load->helper('datatables');
        $this->load->helper('crypt');
        $this->load->model('app_model');
        $this->load->model('access_model');
        $this->load->model('user_model');
        $this->load->model('master_model');
        $this->load->model('leads_model');
        $this->load->model('authentication_model');
        $this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        // $this->load->library('encrypt');
    }

public function inactiveleads(){
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('is_active',1);
        $this->db->where('role_id !=',4);
        $this->db->where('role_id !=',1);
        $users = $this->db->get()->result_array();
        // echo "There";
        foreach ($users as $user) {
            // echo "Username: " . $user->username . "<br>";
            
            // if($user[""]) 
            
            // echo $user["email"] . "===> " . $lead_counts['count'] . "==>" . $lead_counts["assigned_to"] . "<br>";
            if($user["role_id"] == 7){

                $this->db->select('gm.user_id');
                $this->db->from('group_members as gm');
                $this->db->where('gm.group_id IN (SELECT gm_inner.group_id FROM users AS u JOIN group_members AS gm_inner ON gm_inner.user_id = u.user_id WHERE u.user_id = '.$user['user_id'].')', NULL, FALSE);
                $this->db->where('gm.user_id !=', $user['user_id']);
                $query = $this->db->get();
                $result = $query->result_array();

                $group_mem = [];

                foreach($result as $res){
                    array_push($group_mem, $res['user_id']);
                }

                $this->db->select('*');
                $this->db->from('nonactivity_leads');
                $this->db->where_in("assigned_to", $group_mem);
                $lead_counts = $this->db->get()->result_array();
                $lead_count_remark = "<table>";
                $i = 1;
                foreach($lead_counts as $leads){
                    if ($leads["count"] == 0) continue;
                    if($i == 1){
                        $lead_count_remark .= "<tr><td>Name</td><td>Inactivity Leads Count</td></tr>"; 
                    }
                    $lead_count_remark .= "<tr><td>".$leads["assigned_to"]."</td><td>".$leads["count"]."</td></tr>"; 
                    $i = $i+1;
                }
                if($lead_count_remark == "<table>") continue;
                $subject = "Lead Inactivity Alert!";
                $message = "Dear ".$users["first_name"].",<br /><br />You & Your Groups have some inactivity leads are there. Please check below to know more. <br />".$lead_count_remark."<br /><br />Please login into CRM and do activity on assigned leads https://crm.ontimegroup.com/leads/lead/manage#manage.";
                $email_array = array(
                    //'email' => "huida@ontimebiz.com",
                    'email' => $user["email"],
                    'cc' => ["email" => "muthuvenkatesh808@gmail.com","email" => "jothish.s@egovllc.com"],
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM - Lead Inactivity ALERT",
                    'message' => $message,
                );
                $send_mail = send_template_email($email_array);
                $email_array = array(
                    //'email' => "huida@ontimebiz.com",
                    'email' => "muthuvenkatesh808@gmail.com",
                    'cc' => ["email" => "muthuvenkatesh808@gmail.com","email" => "jothish.s@egovllc.com"],
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM - Lead Inactivity ALERT",
                    'message' => $message,
                );
                $send_mail = send_template_email($email_array);
                
                $email_array = array(
                    //'email' => "huida@ontimebiz.com",
                    'email' => "jothish.s@egovllc.com",
                    'cc' => ["email" => "muthuvenkatesh808@gmail.com","email" => "jothish.s@egovllc.com"],
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM - Lead Inactivity ALERT",
                    'message' => $message,
                );
                $send_mail = send_template_email($email_array);

                log_message('debug', "Group Notification: " . $send_mail);

                echo "true";
            }
            else {
                $this->db->select('*');
                $this->db->from('nonactivity_leads');
                $this->db->where_in("assigned_to", [$user['user_id']]);
                $lead_counts = $this->db->get()->result_array();
                if(count($lead_counts) == 1){
                    $lead_counts = $lead_counts[0];
                }
                if (count($lead_counts) == 0) continue;

                $subject = "Lead Inactivity Alert!";
                $message = "Dear ".$users["first_name"].",<br /><br />You have <strong>".$lead_counts["count"]."</strong> inactivity leads are there. <br /><br />Please login into CRM and do activity on assigned leads https://crm.ontimegroup.com/leads/lead/manage#manage.";
                $email_array = array(
                    //'email' => "huida@ontimebiz.com",
                    'email' => $user["email"],
                    'cc' => ["muthuvenkatesh808@gmail.com","jothish.s@egovllc.com"],
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM - Lead Inactivity ALERT",
                    'message' => $message,
                );
                $send_mail = send_template_email($email_array);
                $email_array = array(
                    //'email' => "huida@ontimebiz.com",
                    'email' => "muthuvenkatesh808@gmail.com",
                    'cc' => ["email" => "muthuvenkatesh808@gmail.com","email" => "jothish.s@egovllc.com"],
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM - Lead Inactivity ALERT",
                    'message' => $message,
                );
                $send_mail = send_template_email($email_array);
                
                $email_array = array(
                    //'email' => "huida@ontimebiz.com",
                    'email' => "jothish.s@egovllc.com",
                    'cc' => ["email" => "muthuvenkatesh808@gmail.com","email" => "jothish.s@egovllc.com"],
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM - Lead Inactivity ALERT",
                    'message' => $message,
                );
                $send_mail = send_template_email($email_array);

                echo "true";
                // exit();
                // echo "true";
                log_message('debug', "Group Notification: " . $send_mail);
            }
        
        }
            // sleep(2);
    }

    public function manage()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['rejection_submit'])) {
                $close_remarks = $this->input->post('rejection_remarks');
                $lead_id = $this->input->post('lead_id');

                if ($close_remarks != '') {
                    $log_insert_array = array('action_id' => 411, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 306);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        $assign_lead_array = array('lead_id' => $lead_id, 'assigned_by' => $this->auth_user_id, 'assigned_to' => $this->auth_user_id, 'assigned_on' => date('Y-m-d H:i:s'));
                        $assign_lead = $this->mcommon->common_insert('leads_assigned', $assign_lead_array);
                        if ($assign_lead > 0) {
                            $update_lead_array = array('is_assigned' => 1, 'lead_status' => 306, 'remarks' => $close_remarks);
                            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                            $this->session->set_flashdata('alert_complete', 'success');
                            $this->session->set_flashdata('alert_complete_message', 'Lead has been rejected and added to disqualified leads.');
                        } else {
                            $this->session->set_flashdata('alert_complete', 'danger');
                            $this->session->set_flashdata('alert_complete_message', 'Unable to update the lead record. Contact support.');
                        }
                    } else {
                        $this->session->set_flashdata('alert_complete', 'danger');
                        $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert_complete', 'danger');
                    $this->session->set_flashdata('alert_complete_message', 'All fields are required');
                }
            }
            $category_array = $this->access_model->get_my_access_categories();
            // echo "<pre>";
            // print_r($user_categories);
            // echo "</pre>";

            // // exit();
            // $category_array = array();
            // foreach ($user_categories as $key => $value) {
            //     array_push($category_array, $value['category_id']);
            // }
            if (!empty($category_array)) {
                $view_data['unassigned_leads'] = $this->leads_model->unassigned_leads_for_user($category_array);
                $view_data['accepted_leads'] = $this->leads_model->user_leads();
                if ($this->auth_user_role > 5) {
                    $view_data['assigned_leads'] = $this->leads_model->leads_assigned_by_coordinator();
                    $view_data['converted_leads'] = $this->leads_model->converted_leads_cordinator();
                    $view_data['disqualified_leads'] = $this->leads_model->coordinator_disqualified_leads();
                } else {
                    $view_data['converted_leads'] = $this->leads_model->converted_leads();
                    $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads();
                }
            } else {
                $view_data['unassigned_leads'] = array();
                $view_data['accepted_leads'] = array();
                $view_data['converted_leads'] = array();
                $view_data['disqualified_leads'] = array();
            }

            //print_r($this->db->last_query());

            // echo "<pre>";
            // print_r($category_array);
            // echo "</pre>";

            // exit();

            $data = array(
                'page_title' => 'Manage Leads',
                'title' => 'Manage Leads',
                'content' => $this->load->view('leads/lead/manage', $view_data, true),
            );
            $this->load->view('template/base_template_modal_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function pos_leads()
    {
        if ($this->verify_min_level(1)) {
            $pos_data["data"] = $this->leads_model->pos_leads_coordinator();
            // print_r($pos_data);
            echo json_encode($pos_data);
        }
    }

    public function manage_leads()
    {
        if ($this->verify_min_level(1)) {
            //   print_r($this->auth_user_id);exit(); 

            if (isset($_POST['rejection_submit'])) {
                $close_remarks = $this->input->post('rejection_remarks');
                $lead_id = $this->input->post('lead_id');

                if ($close_remarks != '') {
                    $log_insert_array = array('action_id' => 411, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 306);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        $assign_lead_array = array('lead_id' => $lead_id, 'assigned_by' => $this->auth_user_id, 'assigned_to' => $this->auth_user_id, 'assigned_on' => date('Y-m-d H:i:s'));
                        $assign_lead = $this->mcommon->common_insert('leads_assigned', $assign_lead_array);
                        if ($assign_lead > 0) {
                            $update_lead_array = array('is_assigned' => 1, 'lead_status' => 306, 'remarks' => $close_remarks);
                            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                            $this->session->set_flashdata('alert_complete', 'success');
                            $this->session->set_flashdata('alert_complete_message', 'Lead has been rejected and added to disqualified leads.');
                        } else {
                            $this->session->set_flashdata('alert_complete', 'danger');
                            $this->session->set_flashdata('alert_complete_message', 'Unable to update the lead record. Contact support.');
                        }
                    } else {
                        $this->session->set_flashdata('alert_complete', 'danger');
                        $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert_complete', 'danger');
                    $this->session->set_flashdata('alert_complete_message', 'All fields are required');
                }
            }
            $category_array = $this->access_model->get_my_access_categories($this->auth_user_id);
            if (!empty($category_array)) {
                $view_data['unassigned_leads'] = $this->leads_model->unassigned_leads_for_user($category_array);
                $view_data['accepted_leads'] = $this->leads_model->user_leads();
                if ($this->auth_user_role > 5) {
                    $view_data['assigned_leads'] = $this->leads_model->assigned_by_coordinator();
                    //print_r($view_data['assigned_leads']);exit('test');
                    $view_data['converted_leads'] = $this->leads_model->converted_leads_cordinator();
                    $view_data['disqualified_leads'] = $this->leads_model->coordinator_disqualified_leads();
                } else {
                    $view_data['converted_leads'] = $this->leads_model->converted_leads();
                    $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads();
                }
            } else {
                $view_data['unassigned_leads'] = array();
                $view_data['accepted_leads'] = array();
                $view_data['converted_leads'] = array();
                $view_data['disqualified_leads'] = array();
            }

            // echo "<pre>";
            // print_r($category_array);
            // echo "</pre>";

            // exit();
            $view_data["groups"] = $this->leads_model->get_userGroup($this->auth_user_id);
            // echo "<pre>";
            // print_r($view_data["group"]);
            // echo "</pre>";
            $view_data["branches"] = $this->db->select("*")->from("ontime_branches")->where("is_active", 1)->get()->result_array();

            $data = array(
                'page_title' => 'Manage Leads',
                'title' => 'Manage Leads',
                'content' => $this->load->view('leads/lead/manage_leads', $view_data, true),
            );
            $this->load->view('template/base_template_modal_v2', $data);
        } else {
            redirect('login');
        }
    }
    public function leads_getData()
    {
        if (isset($_POST['submit'])) {
            $group_id = $this->input->post('group_id');
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
            //print_r($group_id);exit('test');
            $view_data["assigned_leads"] = $this->leads_model->get_leadsDatas($group_id, $from_date, $to_date);
            $view_data['converted_leads'] = $this->leads_model->converted_leads_cordinator();
            $view_data['disqualified_leads'] = $this->leads_model->coordinator_disqualified_leads();
            // $view_data['assigned_leads'] = $this->leads_model->get_leadsDatas();
            //echo "<pre>"; print_r($view_data['assigned_leads']);exit('test');
            //   }
            $data = array(
                'page_title' => 'Manage Leads',
                'title' => 'Manage Leads',
                'content' => $this->load->view('leads/lead/manage_leads', $view_data, true),
            );
            $this->load->view('template/base_template_modal_v2', $data);
        }
    }


    public function accept($lead_id)
    {
        if ($this->verify_min_level(1)) {
            //insert into leads_assigned
            $insert_array = array(
                'lead_id' => $lead_id,
                'assigned_by' => $this->auth_user_id,
                'assigned_to' => $this->auth_user_id,
                'assigned_on' => date('Y-m-d H:i:s'),
            );
            $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);
            if ($insert > 0) {
                $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));
                if ($update) {
                    //create action log
                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $this->auth_first_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 302);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Lead has been assigned to you successfully!');
                } else {
                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                    // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Unable to assign the lead at present. Please try again later');
                }
            } else {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('alert_message', 'Unable to assign the lead at present. Please try again later.');
            }
            redirect('leads/lead/manage');
        } else {
            redirect('login');
        }
    }

    function new()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                log_message('error', 'create lead');

                $branch_id = $this->input->post('branch_id');
                $lead_type = $this->input->post('lead_type');
                if ($lead_type == 'normal') {
                    $category_id = $this->input->post('category_id');
                    $service_id = $this->input->post('service_id');

                    $this->form_validation->set_rules('category_id', 'Category', 'required');
                    $this->form_validation->set_rules('service_id', 'Service', 'required');
                }

                if ($lead_type == 'package') {
                    $package_id = $this->input->post('package_id');
                    $category_id = $package_id;
                    $this->form_validation->set_rules('package_id', 'Package', 'required');
                    $service_id = "1009";
                }

                $lead_name = $this->input->post('lead_name');
                $lead_contact = $this->input->post('lead_contact');
                $lead_email = $this->input->post('lead_email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal' || $lead_type == 'package') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                //if there are existing workflows for selected category & service, create lead entry for each workflow entry
                                foreach ($workflows as $key => $value) {
                                    $parent_service_id = $value['parent_service_id'];
                                    $target_service_id = $value['target_service_id'];
                                    $category_id = $value['category_id'];

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $category_id,
                                        'service_id' => $target_service_id,
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => 0,
                                        'order_receipt' => 0,
                                        'remarks' => $lead_remarks,
                                        'is_assigned' => 0,
                                    );
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    if ($insert_lead_id > 0) {
                                        if (isset($_FILES['files']['name'])) {

                                            $attachment_name = $this->input->post('attachment_name');
                                            log_message('error', 'inside files uploads');
                                            // Count total files
                                            $countfiles = count($_FILES['files']['name']);
                                            log_message('error', 'no of files' . $countfiles);

                                            // Looping all files
                                            for ($i = 0; $i < $countfiles; $i++) {

                                                if (!empty($_FILES['files']['name'][$i])) {
                                                    log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                                    // Define new $_FILES array - $_FILES['file']
                                                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                                    // Set preference
                                                    $config['upload_path'] = 'uploads/leads';
                                                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                                    $config['max_size'] = '5000'; // max_size in kb
                                                    $config['file_name'] = $_FILES['files']['name'][$i];

                                                    //Load upload library
                                                    $this->load->library('upload', $config);

                                                    // File upload
                                                    if ($this->upload->do_upload('file')) {

                                                        // Get data about the file
                                                        $uploadData = $this->upload->data();
                                                        $filename = $uploadData['file_name'];
                                                        log_message('error', 'no of files' . $filename);
                                                        // Initialize array
                                                        $data['filenames'][] = $filename;

                                                        $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                        $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                                    }
                                                }
                                            }
                                        }
                                        //get branch name
                                        $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $normal_lead_count++;
                                    }
                                }
                            } else {
                                // else create one lead for selected category & service
                                $insert_lead_array = array(
                                    'customer_id' => $user_id,
                                    'branch_id' => $branch_id,
                                    'category_id' => $category_id,
                                    'service_id' => $service_id,
                                    'lead_created_by' => $this->auth_user_id,
                                    'lead_added_on' => date('Y-m-d H:i:s'),
                                    'contactable_date' => date('Y-m-d H:i:s'),
                                    'lead_status' => 301,
                                    'package_id' => 0,
                                    'order_receipt' => 0,
                                    'remarks' => $lead_remarks,
                                    'is_assigned' => 0,
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;
                            }

                            if ($normal_lead_count > 0) {
                                if (isset($_FILES['files']['name'])) {
                                    $attachment_name = $this->input->post('attachment_name');

                                    log_message('error', 'inside files uploads');
                                    // Count total files
                                    $countfiles = count($_FILES['files']['name']);
                                    log_message('error', 'no of files' . $countfiles);

                                    // Looping all files
                                    for ($i = 0; $i < $countfiles; $i++) {

                                        if (!empty($_FILES['files']['name'][$i])) {
                                            log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                            // Define new $_FILES array - $_FILES['file']
                                            $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                            $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                            $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                            $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                            // Set preference
                                            $config['upload_path'] = 'uploads/leads';
                                            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                            $config['max_size'] = '5000'; // max_size in kb
                                            $config['file_name'] = time() . "-" . $_FILES['files']['name'][$i];

                                            //Load upload library
                                            $this->load->library('upload', $config);

                                            // File upload
                                            if ($this->upload->do_upload('file')) {

                                                // Get data about the file
                                                $uploadData = $this->upload->data();
                                                $filename = $uploadData['file_name'];
                                                log_message('error', 'no of files' . $filename);
                                                // Initialize array
                                                $data['filenames'][] = $filename;

                                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                            }
                                        }
                                    }
                                }
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $normal_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }

                        // if ($lead_type == 'package') {
                        //     $package = $this->db->select("*")->from("lead_packages")->where("package_id", $package_id)->get()->first_row();
                        //     $insert_lead_array = array(
                        //         'customer_id' => $user_id,
                        //         'branch_id' => $branch_id,
                        //         'category_id' => $package->package_category_id,
                        //         'service_id' => 10009,
                        //         'lead_created_by' => $this->auth_user_id,
                        //         'lead_added_on' => date('Y-m-d H:i:s'),
                        //         'contactable_date' => date('Y-m-d H:i:s'),
                        //         'lead_status' => 301,
                        //         'package_id' => $package_id,
                        //         'order_receipt' => 0,
                        //         'remarks' => $lead_remarks,
                        //         'is_assigned' => 0
                        //     );
                        //     $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        //     $parent_lead_id = $insert_lead_id;
                        //     $normal_lead_count = 1;

                        //     $package_lead_count = 0;
                        //     $packages = $this->leads_model->get_package_entries($package_id);
                        //     foreach ($packages as $key => $value) {
                        //         $target_service_id = $value['service_id'];
                        //         $category_id = $value['category_id'];
                        //         $serv_remarks = $value['service_desc'];

                        //         $insert_lead_array = array(
                        //             'customer_id' => $user_id,
                        //             'branch_id' => $branch_id,
                        //             'category_id' => $category_id,
                        //             'service_id' => $target_service_id,
                        //             'lead_created_by' => $this->auth_user_id,
                        //             'lead_added_on' => date('Y-m-d H:i:s'),
                        //             'contactable_date' => date('Y-m-d H:i:s'),
                        //             'lead_status' => 301,
                        //             'package_id' => $package_id,
                        //             'order_receipt' => 0,
                        //             'remarks' => $serv_remarks,
                        //             'is_assigned' => 0,
                        //             'lead_parent_id' => $parent_lead_id
                        //         );
                        //         $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        //         if ($insert_lead_id > 0) {
                        //             //get branch name
                        //             $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                        //             //create action log
                        //             $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                        //             $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        //             $package_lead_count++;
                        //         }
                        //     }

                        //     $insert_lead_id = $parent_lead_id;

                        //     if ($package_lead_count > 0) {
                        //         $this->session->set_flashdata('alert', 'success');
                        //         $this->session->set_flashdata('alert_message', $package_lead_count . ' lead(s) has been created.');
                        //     } else {
                        //         $this->session->set_flashdata('alert', 'danger');
                        //         $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                        //     }
                        // }
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }

                if (isset($_POST["assign_to"]) || isset($_POST["assign_group"])) {
                    if (isset($_POST["assign_to"]) && $_POST["assign_to"] != "") {
                        $assigned_to = $_POST["assign_to"];
                    } else {
                        $group = $this->db->select("group_members.user_id")->from("group_members")->join("users", "users.user_id=group_members.user_id")->where("group_members.group_id", $_POST["assign_group"])->where("users.is_active", 1)->order_by('rand()')->limit(1)->get()->first_row();
                        $assigned_to = $group->user_id;
                    }
                    $lead_id = $insert_lead_id;

                    $assigned_by = $this->auth_user_id;
                    $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                    $insert_array = array(
                        'lead_id' => $lead_id,
                        'assigned_by' => $assigned_by,
                        'assigned_to' => $assigned_to,
                        'assigned_on' => date('Y-m-d H:i:s'),
                    );
                    // echo "<br>";
                    // echo "<br> ";
                    // print_r($insert_array);
                    $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                    $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                    $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();
                    



                    $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                    // print_r($log_insert_array);
                    $log_insert = $this->db->insert('lead_action_log', $log_insert_array);


                    if($branch_id==107){
                        //Send Business Setup Leads;
                        $post_bizlead = array(
                                        'new_lead_type' => $lead_type,
                                        'group_crm_lead_id' => $lead_id,
                                        'new_lead_name' => $lead_name,
                                        'new_lead_contact' => $lead_contact,
                                        'new_lead_email' => $lead_email,
                                        'new_lead_remarks' => $lead_remarks,
                                        'new_lead_country_code' => $lead_country_code
                                );

                        $this->biznew($post_bizlead);
                        $updatebiz = $this->mcommon->common_edit('leads', array('is_biz_lead' => 1), array('id' => $lead_id));
                    }
                    


                    // echo "Log: ".$log_insert."<br>";
                    // echo "ERROR: ";
                    // print_r($this->db->error());
                    // exit();
                    if ($insert > 0) {
                        //if group id 88 / biz setup update created by to assigned by
                         $user_group = $this->db->where("user_id", $assigned_to)
                                        ->where("group_id", 88)
                                        ->from("group_members")->get()->first_row();

                        //echo "<pre>";
                        //print_r($user_group);

                        if($user_group->group_id == 88 ){
                            $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302,'biz_assigned'=>$assigned_to), array('id' => $lead_id));
                        }else{
                            $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));
                        }

                        

                        if ($update) {


                            //create action log
                            $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $this->auth_first_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 302);
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                            $receiver_email = $csa->email;
                            $receiver_name = $csa->first_name;
                            $sender_email = $coordinator->email;
                            $sender_name = $coordinator->first_name;

                            $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you !";
                            $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
                            $email_array = array(
                                'email' => $receiver_email,
                                'subject' => $subject,
                                'template' => 'mails/template',
                                'from_name' => "CRM ALERT",
                                'message' => $message,
                            );
                            $send_mail = send_template_email($email_array);
                            log_message('error', $send_mail);

                            // $this->response('Lead has been assigned successfully!', 200);
                        } else {
                            $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                            // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                            // $this->response('Unable to assign the lead at present. Please try again later', 500);
                        }
                    }
                }
            }

            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['nationality'] = $this->mcommon->specific_fields_records_all('nationalities', array('active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            $view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1));

            $data = array(
                'page_title' => 'New Lead',
                'title' => 'New Lead',
                'content' => $this->load->view('leads/lead/new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function subleadcomplete()
    {
        $sublead_id = $_GET["code"];
        $inovice_id = $_GET["inovice_id"];
        $pos_invoice_id = $this->mcommon->specific_row('pos_direct_invoice_list', ["id" => $inovice_id]);
        $sublead = $this->mcommon->specific_row('leads', ["id" => $sublead_id]);
        $lead = $this->mcommon->specific_row('leads', ["id" => $sublead["lead_parent_id"]]);
        $pmt = $lead["pos_pmt_number"];

        $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
        if ($user_pos == 0 || $user_pos == NULL)
            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
        if ($user_pos == 0 || $user_pos == NULL)
            $user_pos = "crmonline";

        $lead_det = $this->leads_model->lead_details($lead["id"]);


        $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
        $req["PMTNumber"] = $pmt;
        $req["OrderRef"] = $sublead["id"] . '-OTLDDI' . $lead["id"];
        $_SubLeadId = $sublead["id"]?$sublead["id"]:$lead["id"];

        // $action_id
        $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
        $req["salesorderdtl"] = [["Id" => $pos_invoice_id['pos_invoice_id'], "AddTypingFee" => 0, "SubLeadId"=>$_SubLeadId]];

        $req["User"] = ["User_ID" => $user_pos];

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=1',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($req),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            )
        );

        $response = curl_exec($curl);

        // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
        // $response = curl_exec($curl);
        $raw_response = $response;
        // echo $curl_url;
        if (curl_errno($curl)) {
            $response = json_encode($req) . "<br>" . curl_error($curl);
            // print_r(curl_error($curl));
            curl_close($curl);
        } else {
            $response = json_encode($req) . "<br>" . $response;
            curl_close($curl);
        }
        // print_r($response);
        // exit();

        $res_json = json_decode($raw_response);
        if (isset($res_json->Data->SLI_Headnum)) {
            $so_order = $res_json->Data->SLI_Headnum;
            $raw_salesorder = $so_order;
            $so_order = "under the payment receipt " . $so_order . "</b>";
        }
        $this->mcommon->common_edit("leads", array("pos_so_response" => $response, "pos_invresponse" => $raw_salesorder,"pos_pmt_number"=>$pmt), array("id" => $sublead["id"]));

        $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $raw_salesorder;

        $log_insert_array = array('action_id' => 410, 'lead_id' => $sublead["id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $this->auth_user_id, 'status_id' => 305);
        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
        if ($insert_log > 0) {
            $update_lead_array = array('lead_status' => 305, 'order_receipt' => $raw_salesorder, "completed_by" => $this->auth_user_id);
            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $sublead["id"]));
            $this->session->set_flashdata('alert_complete', 'success');
            $this->session->set_flashdata('alert_complete_message', 'Order data updated successfully. You can see the progress in timeline.');
        } else {
            $this->session->set_flashdata('alert_complete', 'danger');
            $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
        }

        return redirect("/leads/lead/view/" . $lead["id"]);
    }

    // Unused function
    public function subleadcomplete2()
    {
        $sublead_id = $_GET["code"];
        $sublead = $this->mcommon->specific_row('leads', ["id" => $sublead_id]);
        $lead = $this->mcommon->specific_row('leads', ["id" => $sublead["lead_parent_id"]]);
        $pmt = $lead["pos_pmt_number"];

        $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
        if ($user_pos == 0 || $user_pos == NULL)
            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
        if ($user_pos == 0 || $user_pos == NULL)
            $user_pos = "crmonline";

        $lead_det = $this->leads_model->lead_details($lead["id"]);


        $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
        $req["PMTNumber"] = $pmt;
        $req["OrderRef"] = $sublead["id"] . '-OTLDDI' . $lead["id"];

        // $action_id
        $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
        $req["salesorderdtl"] = [["Id" => 1230, "AddTypingFee" => 0,"SubLeadId"=>$_SubLeadId]];

        $req["User"] = ["User_ID" => $user_pos];

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=1',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($req),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            )
        );

        $response = curl_exec($curl);

        // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
        // $response = curl_exec($curl);
        $raw_response = $response;
        // echo $curl_url;
        if (curl_errno($curl)) {
            $response = json_encode($req) . "<br>" . curl_error($curl);
            // print_r(curl_error($curl));
            curl_close($curl);
        } else {
            $response = json_encode($req) . "<br>" . $response;
            curl_close($curl);
        }
        // print_r($response);
        // exit();

        $res_json = json_decode($raw_response);
        if (isset($res_json->Data->SLI_Headnum)) {
            $so_order = $res_json->Data->SLI_Headnum;
            $raw_salesorder = $so_order;
            $so_order = "under the payment receipt " . $so_order . "</b>";
        }
        $this->mcommon->common_edit("leads", array("pos_so_response" => $response, "pos_invresponse" => $raw_salesorder,"pos_pmt_number"=>$pmt), array("id" => $sublead["id"]));

        $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $raw_salesorder;

        $log_insert_array = array('action_id' => 410, 'lead_id' => $sublead["id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $this->auth_user_id, 'status_id' => 305);
        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
        if ($insert_log > 0) {
            $update_lead_array = array('lead_status' => 305, 'order_receipt' => $raw_salesorder, "completed_by" => $this->auth_user_id);
            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $sublead["id"]));
            $this->session->set_flashdata('alert_complete', 'success');
            $this->session->set_flashdata('alert_complete_message', 'Order data updated successfully. You can see the progress in timeline.');
        } else {
            $this->session->set_flashdata('alert_complete', 'danger');
            $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
        }

        return redirect("/leads/lead/view/" . $lead["id"]);
    }

    public function resendReceipt($action_id)
    {
        if ($this->verify_min_level(1)) {

            $action = $this->db->select("*")->from("lead_action_log")->where("id", $action_id)->get()->row_array();
            $lead_id = $action["lead_id"];
            $getLeadInfo = $this->db->select("*")->from("leads")->where("id", $lead_id)->get()->row_array();

            $payment_type = "Online";
            if ($action["action_id"] == 417)
                $payment_type = "CASH";
            if ($action["action_id"] == 418)
                $payment_type = "CARD";

            $details=array();

            if($getLeadInfo["branch_id"]!=25){
            $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
            }

            $lead_det = $this->leads_model->lead_details($lead_id);
            if ($action["pos_order_ref"] != NULL && $action["pos_order_ref"] != "")
                $OrderID = $action["pos_order_ref"];
            else
                $OrderID = $action_id . "-OTLDPMET" . $lead_id;
            $raw_salesorder = $action["pos_pmt_number"];
            if ($raw_salesorder == NULL || $raw_salesorder == "")
                $raw_salesorder = $lead_det["pos_salesorder"];
            if ($raw_salesorder == NULL || $raw_salesorder == "")
                $raw_salesorder = $lead_det["pos_pmt_number"];
            $service_payment = $action["action_amount"];
            $email_array = array(
                'name' => $lead_det["customer_name"],
                'email' => $lead_det["customer_email"],
                'mobile' => $lead_det["customer_mobile"],
                'subject' => 'OnTime Group - Payment Receipt Copy - #' . $raw_salesorder,
                'template' => "emails/payment_done",
                'from_name' => "OnTime Group",
                'from_email' => "crm@ontimegroup.com",
                'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                // 'message' => $html,
                "reference" => $OrderID,
                "so_order" => $raw_salesorder,
                "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                "amount" => $service_payment,
                "payment_type" => $payment_type,
                "details" => $details,
                "branch_id" => $lead_det["branch_id"]
            );
            // return ($this->load->view('emails/payment_done', $email_array));
            $send_mail = send_lead_template_email($email_array);
            $this->session->set_flashdata('alert', 'success');
            $this->session->set_flashdata('alert_message', 'Receipt Resent successfully.');

            if ($lead_det["lead_parent_id"] != 0)
                $lead_id = $lead_det["lead_parent_id"];
            redirect("/leads/lead/view/" . $lead_id);
        } else {
            redirect('login');
        }
    }


    public function reprintReceipt($action_id)
    {
        if ($this->verify_min_level(1)) {

            $action = $this->db->select("*")->from("lead_action_log")->where("id", $action_id)->get()->row_array();
            $lead_id = $action["lead_id"];
            $getLeadInfo = $this->db->select("*")->from("leads")->where("id", $lead_id)->get()->row_array();
            //echo "<pre>";
            //print_r($getLeadInfo);
           // die();


            $payment_type = "Online";
            if ($action["action_id"] == 417)
                $payment_type = "CASH";
            if ($action["action_id"] == 418)
                $payment_type = "CARD";

            $details=array();

            if($getLeadInfo["branch_id"]!=25){
            $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
            }

           // print_r($details);
            //die();

            if ($action["pos_order_ref"] != NULL && $action["pos_order_ref"] != "")
                $OrderID = $action["pos_order_ref"];
            else
                $OrderID = $action_id . "-OTLDPMET" . $lead_id;

            $lead_det = $this->leads_model->lead_details($lead_id);
            $raw_salesorder = $action["pos_pmt_number"];
            if ($raw_salesorder == NULL || $raw_salesorder == "")
                $raw_salesorder = $lead_det["pos_salesorder"];
            if ($raw_salesorder == NULL || $raw_salesorder == "")
                $raw_salesorder = $lead_det["pos_pmt_number"];

            $service_payment = $action["action_amount"];
            $email_array = array(
                'name' => $lead_det["customer_name"],
                'email' => $lead_det["customer_email"],
                'mobile' => $lead_det["customer_mobile"],
                'subject' => 'OnTime Group - Payment Receipt Copy - #' . $raw_salesorder,
                'template' => "emails/payment_done",
                'from_name' => "OnTime Group",
                'from_email' => "crm@ontimebiz.com",
                'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                // 'message' => $html,
                "reference" => $OrderID,
                "so_order" => $raw_salesorder,
                "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                "amount" => $service_payment,
                "payment_type" => $payment_type,
                "details" => $details,
                "branch_id" => $lead_det["branch_id"]
            );
            return ($this->load->view('emails/payment_done', $email_array));
            $send_mail = send_lead_template_email($email_array);
            if ($lead_det["lead_parent_id"] != 0)
                $lead_id = $lead_det["lead_parent_id"];
            redirect("/leads/lead/view/" . $lead_id);
        } else {
            redirect('login');
        }
    }

    public function golden_cube_new()
    {

        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                // print_r($this->auth_user_id);
                // exit();

                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";
                // echo "<pre>";
                // print_r($_POST);
                // echo "<pre>";
                // exit();
                log_message('error', 'create lead - Golden Cube');

                $branch_id = $this->input->post('branch_id');
                $lead_type = $this->input->post('lead_type');
                if ($lead_type == 'normal') {
                    $category_id = $this->input->post('category_id');
                    $service_id = $this->input->post('service_id');

                    $this->form_validation->set_rules('category_id', 'Category', 'required');
                    $this->form_validation->set_rules('service_id', 'Service', 'required');
                }

                if ($lead_type == 'package') {
                    $package_id = $this->input->post('package_id');
                    $this->form_validation->set_rules('package_id', 'Package', 'required');
                }

                $lead_name = $this->input->post('lead_name');
                $lead_contact = $this->input->post('lead_contact');
                $lead_email = $this->input->post('lead_email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                //if there are existing workflows for selected category & service, create lead entry for each workflow entry
                                foreach ($workflows as $key => $value) {
                                    $parent_service_id = $value['parent_service_id'];
                                    $target_service_id = $value['target_service_id'];
                                    $category_id = $value['category_id'];

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $category_id,
                                        'service_id' => $target_service_id,
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => 0,
                                        'order_receipt' => 0,
                                        'remarks' => $lead_remarks,
                                        'is_assigned' => 0,
                                    );
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    if ($insert_lead_id > 0) {
                                        if (isset($_FILES['files']['name'])) {

                                            $attachment_name = $this->input->post('attachment_name');
                                            log_message('error', 'inside files uploads');
                                            // Count total files
                                            $countfiles = count($_FILES['files']['name']);
                                            log_message('error', 'no of files' . $countfiles);

                                            // Looping all files
                                            for ($i = 0; $i < $countfiles; $i++) {

                                                if (!empty($_FILES['files']['name'][$i])) {
                                                    log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                                    // Define new $_FILES array - $_FILES['file']
                                                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                                    // Set preference
                                                    $config['upload_path'] = 'uploads/leads';
                                                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                                    $config['max_size'] = '5000'; // max_size in kb
                                                    $config['file_name'] = $_FILES['files']['name'][$i];

                                                    //Load upload library
                                                    $this->load->library('upload', $config);

                                                    // File upload
                                                    if ($this->upload->do_upload('file')) {

                                                        // Get data about the file
                                                        $uploadData = $this->upload->data();
                                                        $filename = $uploadData['file_name'];
                                                        log_message('error', 'no of files' . $filename);
                                                        // Initialize array
                                                        $data['filenames'][] = $filename;

                                                        $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                        $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                                    }
                                                }
                                            }
                                        }
                                        //get branch name
                                        $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $normal_lead_count++;
                                    }
                                }
                            } else {
                                // else create one lead for selected category & service
                                $insert_lead_array = array(
                                    'customer_id' => $user_id,
                                    'branch_id' => $branch_id,
                                    'category_id' => $category_id,
                                    'service_id' => $service_id,
                                    'lead_created_by' => $this->auth_user_id,
                                    'lead_added_on' => date('Y-m-d H:i:s'),
                                    'contactable_date' => date('Y-m-d H:i:s'),
                                    'lead_status' => 301,
                                    'package_id' => 0,
                                    'order_receipt' => 0,
                                    'remarks' => $lead_remarks,
                                    'is_assigned' => 0,
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;
                            }

                            if ($normal_lead_count > 0) {
                                if (isset($_FILES['files']['name'])) {
                                    $attachment_name = $this->input->post('attachment_name');

                                    log_message('error', 'inside files uploads');
                                    // Count total files
                                    $countfiles = count($_FILES['files']['name']);
                                    log_message('error', 'no of files' . $countfiles);

                                    // Looping all files
                                    for ($i = 0; $i < $countfiles; $i++) {

                                        if (!empty($_FILES['files']['name'][$i])) {
                                            log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                            // Define new $_FILES array - $_FILES['file']
                                            $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                            $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                            $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                            $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                            // Set preference
                                            $config['upload_path'] = 'uploads/leads';
                                            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                            $config['max_size'] = '5000'; // max_size in kb
                                            $config['file_name'] = time() . "-" . $_FILES['files']['name'][$i];

                                            //Load upload library
                                            $this->load->library('upload', $config);

                                            // File upload
                                            if ($this->upload->do_upload('file')) {

                                                // Get data about the file
                                                $uploadData = $this->upload->data();
                                                $filename = $uploadData['file_name'];
                                                log_message('error', 'no of files' . $filename);
                                                // Initialize array
                                                $data['filenames'][] = $filename;

                                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                            }
                                        }
                                    }
                                }
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $normal_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }

                        if ($lead_type == 'package') {
                            $package = $this->db->select("*")->from("lead_packages")->where("package_id", $package_id)->get()->first_row();

                            $service_ids = $_POST["service_id"];
                            $service_names = $_POST["service_name"];
                            $service_qtys = $_POST["service_qty"];
                            $govt_fees = $_POST["govt_fee"];
                            $typing_fees = $_POST["typing_fee"];
                            $msd_key = $_POST["msd_key"];
                            $sub_totals = $_POST["sub_total"];
                            $is_direct_invoice = $_POST["is_direct_invoice"];
                            $is_pos_typing_fee = $_POST["is_pos_typing_fee"];

                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $package->package_category_id,
                                'service_id' => 10009,
                                'lead_created_by' => $this->auth_user_id,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => $package_id,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'total_no_subleads' => count($service_ids),
                                'no_of_open_subleads' => count($service_ids),
                                'no_of_closed_subleads' => 0
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);


                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {
                                

                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                }else if ($payment_type == "online") {
                                     $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                }else{
                                    $card_amount = 0;
                                }
                                $sub_totals[$i] = $govt_fees[$i] + $typing_fees[$i] + $card_amount;


                                for ($ii = 0; $ii < $service_qtys[$i]; $ii++) {
                                    $package_detail = array(
                                        "lead_id" => $parent_lead_id,
                                        "package_id" => $package_id,
                                        "service_id" => $service_ids[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "card_amount" => $card_amount,
                                        "sub_total" => $sub_totals[$i],
                                        "payment_type" => $payment_type,
                                        "created_by" => $this->auth_user_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                    $this->mcommon->common_insert('lead_package_details', $package_detail);

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $package->package_category_id,
                                        'service_id' => $service_ids[$i],
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => $package_id,
                                        'order_receipt' => 0,
                                        'remarks' => $service_names[$i],
                                        'is_assigned' => 0,
                                        'lead_parent_id' => $parent_lead_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                  if ($payment_type != "cash") {
                                       $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
                                        //$insert_lead_array["card_amount"] = $card_amount;
                                   }
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    // print_r($insert_lead_array);
                                    // exit();
                                    if ($insert_lead_id > 0) {
                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $package_lead_count++;

                                        if (isset($_POST["slot_user_id"][$service_ids[$i]])) {
                                            $slot = $this->mcommon->specific_row_value('crm_timeslots', array('timeslot_id' => $_POST["slot"][$service_ids[$i]]), 'timeslot_name');
                                            $contactable_date = $_POST["slot_date"][$service_ids[$i]];
                                            $meeting_remarks = "Meeting Scheduled at " . $slot;
                                            if ($meeting_remarks != '' && $contactable_date != '') {
                                                //create meeting
                                                $l_det = $this->leads_model->lead_details($insert_lead_id);

                                                $assigned_to = $_POST["slot_user_id"][$service_ids[$i]];
                                                $assigned_by = $this->auth_user_id;
                                                $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $insert_lead_id));
                                                $insert_array = array(
                                                    'lead_id' => $insert_lead_id,
                                                    'assigned_by' => $assigned_by,
                                                    'assigned_to' => $assigned_to,
                                                    'assigned_on' => date('Y-m-d H:i:s'),
                                                );
                                                // echo "<br>";
                                                // echo "<br> ";
                                                // print_r($insert_array);
                                                $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                                                $crm_user_id = $this->auth_user_id;
                                                $customer_id = $this->mcommon->specific_row_value('leads', array('id' => $insert_lead_id), 'customer_id');
                                                $meeting_date_time = $contactable_date;
                                                $remarks = $meeting_remarks;
                                                $is_complete = 0;
                                                $created_at = date('Y-m-d H:i:s');
                                                $last_updated = date('Y-m-d H:i:s');

                                                $meeting_insert_array = array(
                                                    'lead_id' => $insert_lead_id,
                                                    'crm_user_id' => $crm_user_id,
                                                    'customer_id' => $customer_id,
                                                    'meeting_date_time' => $meeting_date_time,
                                                    'remarks' => $remarks,
                                                    'is_complete' => $is_complete,
                                                    'slot_id' => $_POST["slot"][$service_ids[$i]],
                                                    'created_at' => $created_at,
                                                    'last_updated' => $last_updated,
                                                );
                                                $meeting_insert = $this->mcommon->common_insert('lead_meetings', $meeting_insert_array);

                                                if ($meeting_insert > 0) {
                                                    //TODO: schedule an email before 15 mins of the meeting.

                                                    $log_insert_array = array('action_id' => 407, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $meeting_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                                    if ($insert_log > 0) {
                                                        //update lead status and contactable date in lead table
                                                        $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                                                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $insert_lead_id));

                                                        $receiver_email = $l_det["customer_email"];
                                                        $subject = "Your Appointment is scheduled on Al Baraha Medical Examination Center";
                                                        $message = "Dear " . $l_det["customer_name"] . ",<br /><br />Thank You for choosing our Golden Cube Branch.<br><br>Your appointment for Medical Test is confirmed at our Al Baraha Branch, please find the details of your appointment below:<br>";
                                                        $message .= "Date: " . $meeting_date_time . "<br>";
                                                        $message .= "Time: " . $slot . "<br>";
                                                        $message .= "<u>Address:</u> <br>Al Baraha Smart Medical Excamination Center <br>for Residency - Weqayati<br>Gate No. 2, Inside Al Baraha Hospital<br>Tel: 60057007<br>";
                                                        $email_array = array(
                                                            'email' => $receiver_email,
                                                            'subject' => $subject,
                                                            'template' => 'mails/template',
                                                            'from_name' => "OnTime - Golden Cube",
                                                            'message' => $message,
                                                        );
                                                        $send_mail = send_template_email($email_array);
                                                        //log_message('error', $send_mail);





                                                        // echo "Lead==> ".$lead_id;
                                                        // print_r($l_det);
                                                        // exit();
                                                        $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();

                                                        $subject = "Lead #" . $insert_lead_id . " Appointment is scheduled to you!!";
                                                        $message = "An appointment has been arranged to you by " . $coordinator->first_name . "<br><br>Lead Description:";

                                                        $message .= "<br>Customer Name: " . $l_det["customer_name"];
                                                        $message .= "<br>Customer Contact: " . $l_det["customer_mobile"];
                                                        $message .= "<br>Customer Email: " . $l_det["customer_email"];
                                                        $message .= "<br>Service: " . $l_det["category_code"] . " - " . $l_det["service_name"];
                                                        $message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $insert_lead_id . " .<br>";

                                                        $email_array = array(
                                                            'email' => $coordinator->email,
                                                            'subject' => $subject,
                                                            'template' => 'mails/template',
                                                            'from_name' => "CRM ALERT",
                                                            'message' => $message,
                                                        );


                                                        $send_mail = send_template_email($email_array);
                                                        log_message('error', $send_mail);






                                                        $this->session->set_flashdata('alert', 'success');
                                                        $this->session->set_flashdata('alert_message', 'Meeting scheduled successfully. You can see the progress in timeline.');
                                                    } else {
                                                        $this->session->set_flashdata('alert', 'danger');
                                                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                                                    }
                                                } else {
                                                    $this->session->set_flashdata('alert', 'danger');
                                                    $this->session->set_flashdata('alert_message', 'Unable to setup meeting at this moment. Please contact support.');
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            // foreach ($packages as $key => $value) {
                            //     $target_service_id = $value['service_id'];
                            //     $category_id = $value['category_id'];
                            //     $serv_remarks = $value['service_desc'];

                            //     $insert_lead_array = array(
                            //         'customer_id' => $user_id,
                            //         'branch_id' => $branch_id,
                            //         'category_id' => $category_id,
                            //         'service_id' => $target_service_id,
                            //         'lead_created_by' => $this->auth_user_id,
                            //         'lead_added_on' => date('Y-m-d H:i:s'),
                            //         'contactable_date' => date('Y-m-d H:i:s'),
                            //         'lead_status' => 301,
                            //         'package_id' => $package_id,
                            //         'order_receipt' => 0,
                            //         'remarks' => $serv_remarks,
                            //         'is_assigned' => 0,
                            //         'lead_parent_id' => $parent_lead_id,
                            //     );
                            //     $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            //     if ($insert_lead_id > 0) {
                            //         //get branch name
                            //         $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                            //         //create action log
                            //         $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                            //         $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                            //         $package_lead_count++;
                            //     }
                            // }

                            $insert_lead_id = $parent_lead_id;

                            if ($package_lead_count > 0) {
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $package_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }

                if (isset($_POST["assign_to"]) || isset($_POST["assign_group"])) {
                    if (isset($_POST["assign_to"]) && $_POST["assign_to"] != "") {
                        $assigned_to = $_POST["assign_to"];
                    } else {
                        $group = $this->db->select("group_members.user_id")->from("group_members")->join("users", "users.user_id=group_members.user_id")->where("group_members.group_id", $_POST["assign_group"])->where("users.is_active", 1)->order_by('rand()')->limit(1)->get()->first_row();
                        $assigned_to = $group->user_id;
                    }
                    $lead_id = $insert_lead_id;

                    $assigned_by = $this->auth_user_id;
                    $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                    $insert_array = array(
                        'lead_id' => $lead_id,
                        'assigned_by' => $assigned_by,
                        'assigned_to' => $assigned_to,
                        'assigned_on' => date('Y-m-d H:i:s'),
                    );
                    // echo "<br>";
                    // echo "<br> ";
                    // print_r($insert_array);
                    $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                    $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                    $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

                    $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                    // print_r($log_insert_array);
                    $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                    // echo "Log: ".$log_insert."<br>";
                    // echo "ERROR: ";
                    // print_r($this->db->error());
                    // exit();
                    if ($insert > 0) {
                        $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                        if ($update) {
                            //create action log
                            $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $this->auth_first_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 302);
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                            $receiver_email = $csa->email;
                            $receiver_name = $csa->first_name;
                            $sender_email = $coordinator->email;
                            $sender_name = $coordinator->first_name;

                            $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you !";
                            $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
                            $email_array = array(
                                'email' => $receiver_email,
                                'subject' => $subject,
                                'template' => 'mails/template',
                                'from_name' => "CRM ALERT",
                                'message' => $message,
                            );
                            $send_mail = send_template_email($email_array);
                            log_message('error', $send_mail);

                            // $this->response('Lead has been assigned successfully!', 200);
                        } else {
                            $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                            // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                            // $this->response('Unable to assign the lead at present. Please try again later', 500);
                        }
                    }
                }

                $from_email = $this->auth_email;
                $customer_email = $this->input->post('lead_email');
                $agent_email = $this->auth_email;
                $email_subject = "Golden Cube - Followup regarding - Payment";
                $email_message = $this->input->post('email_message');
                $payment_type = $this->input->post('payment_type');
                if (!isset($bot_id)) {
                    $bot_id = 0;
                }

                $approval_code = $this->input->post('approval_code');

                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                $service_payment = $this->input->post('amount_payment');
                $card_amount = $this->input->post('card_amount');

                $amount_payment = $service_payment + $card_amount;

                $crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
                // echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
                // exit();
                //construct message
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
                $agent_name = $this->mcommon->specific_row_value('users', array('email' => $agent_email), 'first_name');
                $message = "Dear " . $customer_name . ",<br /><br />";
                $message .= $email_message;

                $pre_token = $lead_id . "-" . $customer_email . "-@OnTimeCRM11..";
                // echo $pre_token;
                // echo "<br><br>";
                // exit();
                $token1 = md5($pre_token);
                $token2 = md5(strrev($pre_token));
                $token = $token1 . "-" . $token2;

                $msg = $amount_payment;
                $key = $token2;
                // print_r($token);
                // exit();
                $current_timestamp = date('Y-m-d H:i:s');
                $emp = $this->db->select("*")->from("users")->where("user_id", $this->auth_user_id)->get()->first_row();
                $employee = $emp->first_name . " " . $emp->last_name . "(" . $emp->employee_id . ")";
                //add entry in lead action log
                if ($payment_type == "online") {

                    $action_message = $message . "<p></p><div class='payment'><a href='#'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p></div><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $log_insert_array = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307, "bot_id" => $bot_id);

                    // print_r($log_insert_array);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $this->db->insert_id();
                    $log_id = encrypt_decrypt($action_id);
                    $payment_link = "https://crm.ontimegroup.com/payment/ccpay?ref=" . $token . "&token=" . $crypt_amount . "&identity=" . $log_id;

                    $this->mcommon->common_edit('lead_action_log', ["payment_link" => $payment_link], array('id' => $action_id));

                    $payment_message = "<p></p><a href='" . $payment_link . "'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $message .= $payment_message;

                    if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '') {
                        //SEND EMAIL TO CUSTOMER
                        // $customer_email = "muthuvenkatesh808@gmail.com";
                        $email_array = array(
                            'email' => $customer_email,
                            'subject' => $email_subject,
                            'template' => 'mails/lead_template',
                            'from_name' => $this->auth_first_name,
                            'from_email' => $this->auth_email,
                            'message' => $message,
                            'reply_to' => $this->auth_email,
                            'branch_id' => $branch_id
                            // 'cc' => [["email"=>$this->auth_email]]
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);

                        if ($send_mail) {

                            if ($insert_log > 0) {
                                //update lead status and contactable date in lead table
                                $update_lead_array = array('lead_status' => 307, 'contactable_date' => $contactable_date);
                                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', 'Payment Detail Email sent successfully. You will receive the copy of email.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Email sent successfully. But unable to update the lead record. Contact support.');
                            }
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        }

                        redirect(
                            'leads/lead/view/' . $lead_id
                        );
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        redirect('leads/lead/view/' . $lead_id);
                    }
                } else if ($payment_type == "cash") {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 417, 'action_amount' => $service_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $service_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    $curl = curl_init();

                    curl_setopt_array(
                        $curl,
                        array(
                            CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($req),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                            ),
                        )
                    );

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                    }
                    // print_r($response);
                    // exit();
                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));
                    $details=array();
                    if($branch_id!=25){
                    $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    // echo "<pre>";
                    // print_r($details);
                    // echo "</pre>";
                    // exit();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Golden Cube - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Golden Cube",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $service_payment,
                        "payment_type" => "CASH",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Cash to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    redirect('leads/lead/view/' . $lead_id);
                } else {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 418, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311, 'approval_code' => $approval_code);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];
                    $curl = curl_init();

                    curl_setopt_array(
                        $curl,
                        array(
                            CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($req),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                            ),
                        )
                    );

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                    }
                    // print_r($response);
                    // exit();
                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));
                    $details=array();
                    if($branch_id!=25){
                    $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Golden Cube - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Golden Cube",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $amount_payment,
                        "payment_type" => "CARD",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Card to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    redirect('leads/lead/view/' . $lead_id);
                }
            }

            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            $this->db->select("lead_packages.*,sum(lead_package_services.govt_fee+lead_package_services.typing_fee) as no_card_amount")->from("lead_packages");
            $this->db->join("lead_package_services", "lead_package_services.package_id=lead_packages.package_id");
            $this->db->where("lead_packages.package_branch", 106);
            $this->db->where("lead_packages.is_active", 1);
            $this->db->group_by("lead_packages.package_id");
            $package_list = $this->db->get()->result_array();


            $this->db->select("count(crm_user_timeslots.user_timeslot_id) as slot_days,sum(crm_user_timeslots.user_timeslot_slot_count) as total_slots,users.first_name as first_name,users.last_name as last_name,crm_timeslots.timeslot_name,creator.first_name as creator,users.user_id,crm_user_timeslots.created_at,crm_user_timeslots.user_timeslot_status as status,users.employee_id")->from("crm_user_timeslots");
            $this->db->join("crm_timeslots", "crm_timeslots.timeslot_id=crm_user_timeslots.user_timeslot_slot_id");
            $this->db->join("users", "users.user_id = crm_user_timeslots.user_timeslot_user_id");
            $this->db->join("users as creator", "creator.user_id = crm_user_timeslots.updated_by");
            // $this->db->where("crm_user_timeslots.user_timeslot_status = 1");
            $this->db->group_by("crm_user_timeslots.user_timeslot_user_id");
            $timeslots = $this->db->get()->result_array();

            $view_data['slot_users'] = $timeslots;
            //$view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1, "package_branch" => 106));
            $view_data['packages'] = $package_list;
            $data = array(
                'page_title' => 'Golden Cube - New Sale',
                'title' => 'Golden Cube - New Sale',
                'content' => $this->load->view('leads/lead/golden_cube_new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

public function besmart_new()
    {

        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                // print_r($this->auth_user_id);
                // exit();

                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";
                // echo "<pre>";
                // print_r($_POST);
                // echo "<pre>";
                // exit();
                log_message('error', 'create lead - Be Smart');

                $branch_id = $this->input->post('branch_id');
                $lead_type = $this->input->post('lead_type');
                if ($lead_type == 'normal') {
                    $category_id = $this->input->post('category_id');
                    $service_id = $this->input->post('service_id');

                    $this->form_validation->set_rules('category_id', 'Category', 'required');
                    $this->form_validation->set_rules('service_id', 'Service', 'required');
                }

                if ($lead_type == 'package') {
                    $package_id = $this->input->post('package_id');
                    $this->form_validation->set_rules('package_id', 'Package', 'required');
                }

                $lead_name = $this->input->post('lead_name');
                $lead_contact = $this->input->post('lead_contact');
                $lead_email = $this->input->post('lead_email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                //if there are existing workflows for selected category & service, create lead entry for each workflow entry
                                foreach ($workflows as $key => $value) {
                                    $parent_service_id = $value['parent_service_id'];
                                    $target_service_id = $value['target_service_id'];
                                    $category_id = $value['category_id'];

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $category_id,
                                        'service_id' => $target_service_id,
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => 0,
                                        'order_receipt' => 0,
                                        'remarks' => $lead_remarks,
                                        'is_assigned' => 0,
                                    );
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    if ($insert_lead_id > 0) {
                                        if (isset($_FILES['files']['name'])) {

                                            $attachment_name = $this->input->post('attachment_name');
                                            log_message('error', 'inside files uploads');
                                            // Count total files
                                            $countfiles = count($_FILES['files']['name']);
                                            log_message('error', 'no of files' . $countfiles);

                                            // Looping all files
                                            for ($i = 0; $i < $countfiles; $i++) {

                                                if (!empty($_FILES['files']['name'][$i])) {
                                                    log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                                    // Define new $_FILES array - $_FILES['file']
                                                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                                    // Set preference
                                                    $config['upload_path'] = 'uploads/leads';
                                                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                                    $config['max_size'] = '5000'; // max_size in kb
                                                    $config['file_name'] = $_FILES['files']['name'][$i];

                                                    //Load upload library
                                                    $this->load->library('upload', $config);

                                                    // File upload
                                                    if ($this->upload->do_upload('file')) {

                                                        // Get data about the file
                                                        $uploadData = $this->upload->data();
                                                        $filename = $uploadData['file_name'];
                                                        log_message('error', 'no of files' . $filename);
                                                        // Initialize array
                                                        $data['filenames'][] = $filename;

                                                        $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                        $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                                    }
                                                }
                                            }
                                        }
                                        //get branch name
                                        $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $normal_lead_count++;
                                    }
                                }
                            } else {
                                // else create one lead for selected category & service
                                $insert_lead_array = array(
                                    'customer_id' => $user_id,
                                    'branch_id' => $branch_id,
                                    'category_id' => $category_id,
                                    'service_id' => $service_id,
                                    'lead_created_by' => $this->auth_user_id,
                                    'lead_added_on' => date('Y-m-d H:i:s'),
                                    'contactable_date' => date('Y-m-d H:i:s'),
                                    'lead_status' => 301,
                                    'package_id' => 0,
                                    'order_receipt' => 0,
                                    'remarks' => $lead_remarks,
                                    'is_assigned' => 0,
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;
                            }

                            if ($normal_lead_count > 0) {
                                if (isset($_FILES['files']['name'])) {
                                    $attachment_name = $this->input->post('attachment_name');

                                    log_message('error', 'inside files uploads');
                                    // Count total files
                                    $countfiles = count($_FILES['files']['name']);
                                    log_message('error', 'no of files' . $countfiles);

                                    // Looping all files
                                    for ($i = 0; $i < $countfiles; $i++) {

                                        if (!empty($_FILES['files']['name'][$i])) {
                                            log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                            // Define new $_FILES array - $_FILES['file']
                                            $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                            $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                            $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                            $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                            // Set preference
                                            $config['upload_path'] = 'uploads/leads';
                                            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                            $config['max_size'] = '5000'; // max_size in kb
                                            $config['file_name'] = time() . "-" . $_FILES['files']['name'][$i];

                                            //Load upload library
                                            $this->load->library('upload', $config);

                                            // File upload
                                            if ($this->upload->do_upload('file')) {

                                                // Get data about the file
                                                $uploadData = $this->upload->data();
                                                $filename = $uploadData['file_name'];
                                                log_message('error', 'no of files' . $filename);
                                                // Initialize array
                                                $data['filenames'][] = $filename;

                                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                            }
                                        }
                                    }
                                }
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $normal_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }

                        if ($lead_type == 'package') {
                            $package = $this->db->select("*")->from("lead_packages")->where("package_id", $package_id)->get()->first_row();

                            $service_ids = $_POST["service_id"];
                            $service_names = $_POST["service_name"];
                            $service_qtys = $_POST["service_qty"];
                            $govt_fees = $_POST["govt_fee"];
                            $typing_fees = $_POST["typing_fee"];
                            $msd_key = $_POST["msd_key"];
                            $sub_totals = $_POST["sub_total"];
                            $is_direct_invoice = $_POST["is_direct_invoice"];
                            $is_pos_typing_fee = $_POST["is_pos_typing_fee"];

                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $package->package_category_id,
                                'service_id' => 10009,
                                'lead_created_by' => $this->auth_user_id,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => $package_id,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'total_no_subleads' => count($service_ids),
                                'no_of_open_subleads' => count($service_ids),
                                'no_of_closed_subleads' => 0
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);


                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {
                                

                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                }else if ($payment_type == "online") {
                                     $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                }else{
                                    $card_amount = 0;
                                }
                                $sub_totals[$i] = $govt_fees[$i] + $typing_fees[$i] + $card_amount;


                                for ($ii = 0; $ii < $service_qtys[$i]; $ii++) {
                                    $package_detail = array(
                                        "lead_id" => $parent_lead_id,
                                        "package_id" => $package_id,
                                        "service_id" => $service_ids[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "card_amount" => $card_amount,
                                        "sub_total" => $sub_totals[$i],
                                        "payment_type" => $payment_type,
                                        "created_by" => $this->auth_user_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                    $this->mcommon->common_insert('lead_package_details', $package_detail);

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $package->package_category_id,
                                        'service_id' => $service_ids[$i],
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => $package_id,
                                        'order_receipt' => 0,
                                        'remarks' => $service_names[$i],
                                        'is_assigned' => 0,
                                        'lead_parent_id' => $parent_lead_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                  if ($payment_type != "cash") {
                                       $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
                                        //$insert_lead_array["card_amount"] = $card_amount;
                                   }
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    // print_r($insert_lead_array);
                                    // exit();
                                    if ($insert_lead_id > 0) {
                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $package_lead_count++;

                                        if (isset($_POST["slot_user_id"][$service_ids[$i]])) {
                                            $slot = $this->mcommon->specific_row_value('crm_timeslots', array('timeslot_id' => $_POST["slot"][$service_ids[$i]]), 'timeslot_name');
                                            $contactable_date = $_POST["slot_date"][$service_ids[$i]];
                                            $meeting_remarks = "Meeting Scheduled at " . $slot;
                                            if ($meeting_remarks != '' && $contactable_date != '') {
                                                //create meeting
                                                $l_det = $this->leads_model->lead_details($insert_lead_id);

                                                $assigned_to = $_POST["slot_user_id"][$service_ids[$i]];
                                                $assigned_by = $this->auth_user_id;
                                                $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $insert_lead_id));
                                                $insert_array = array(
                                                    'lead_id' => $insert_lead_id,
                                                    'assigned_by' => $assigned_by,
                                                    'assigned_to' => $assigned_to,
                                                    'assigned_on' => date('Y-m-d H:i:s'),
                                                );
                                                // echo "<br>";
                                                // echo "<br> ";
                                                // print_r($insert_array);
                                                $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                                                $crm_user_id = $this->auth_user_id;
                                                $customer_id = $this->mcommon->specific_row_value('leads', array('id' => $insert_lead_id), 'customer_id');
                                                $meeting_date_time = $contactable_date;
                                                $remarks = $meeting_remarks;
                                                $is_complete = 0;
                                                $created_at = date('Y-m-d H:i:s');
                                                $last_updated = date('Y-m-d H:i:s');

                                                $meeting_insert_array = array(
                                                    'lead_id' => $insert_lead_id,
                                                    'crm_user_id' => $crm_user_id,
                                                    'customer_id' => $customer_id,
                                                    'meeting_date_time' => $meeting_date_time,
                                                    'remarks' => $remarks,
                                                    'is_complete' => $is_complete,
                                                    'slot_id' => $_POST["slot"][$service_ids[$i]],
                                                    'created_at' => $created_at,
                                                    'last_updated' => $last_updated,
                                                );
                                                $meeting_insert = $this->mcommon->common_insert('lead_meetings', $meeting_insert_array);

                                                if ($meeting_insert > 0) {
                                                    //TODO: schedule an email before 15 mins of the meeting.

                                                    $log_insert_array = array('action_id' => 407, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $meeting_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                                    if ($insert_log > 0) {
                                                        //update lead status and contactable date in lead table
                                                        $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                                                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $insert_lead_id));

                                                        $receiver_email = $l_det["customer_email"];
                                                        $subject = "Your Appointment is scheduled on Al Baraha Medical Examination Center";
                                                        $message = "Dear " . $l_det["customer_name"] . ",<br /><br />Thank You for choosing our Be Smart Branch.<br><br>Your appointment for Medical Test is confirmed at our Al Baraha Branch, please find the details of your appointment below:<br>";
                                                        $message .= "Date: " . $meeting_date_time . "<br>";
                                                        $message .= "Time: " . $slot . "<br>";
                                                        $message .= "<u>Address:</u> <br>Al Baraha Smart Medical Excamination Center <br>for Residency - Weqayati<br>Gate No. 2, Inside Al Baraha Hospital<br>Tel: 60057007<br>";
                                                        $email_array = array(
                                                            'email' => $receiver_email,
                                                            'subject' => $subject,
                                                            'template' => 'mails/template',
                                                            'from_name' => "OnTime - Be Smart",
                                                            'message' => $message,
                                                        );
                                                        $send_mail = send_template_email($email_array);
                                                        //log_message('error', $send_mail);





                                                        // echo "Lead==> ".$lead_id;
                                                        // print_r($l_det);
                                                        // exit();
                                                        $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();

                                                        $subject = "Lead #" . $insert_lead_id . " Appointment is scheduled to you!!";
                                                        $message = "An appointment has been arranged to you by " . $coordinator->first_name . "<br><br>Lead Description:";

                                                        $message .= "<br>Customer Name: " . $l_det["customer_name"];
                                                        $message .= "<br>Customer Contact: " . $l_det["customer_mobile"];
                                                        $message .= "<br>Customer Email: " . $l_det["customer_email"];
                                                        $message .= "<br>Service: " . $l_det["category_code"] . " - " . $l_det["service_name"];
                                                        $message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $insert_lead_id . " .<br>";

                                                        $email_array = array(
                                                            'email' => $coordinator->email,
                                                            'subject' => $subject,
                                                            'template' => 'mails/template',
                                                            'from_name' => "CRM ALERT",
                                                            'message' => $message,
                                                        );


                                                        $send_mail = send_template_email($email_array);
                                                        log_message('error', $send_mail);






                                                        $this->session->set_flashdata('alert', 'success');
                                                        $this->session->set_flashdata('alert_message', 'Meeting scheduled successfully. You can see the progress in timeline.');
                                                    } else {
                                                        $this->session->set_flashdata('alert', 'danger');
                                                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                                                    }
                                                } else {
                                                    $this->session->set_flashdata('alert', 'danger');
                                                    $this->session->set_flashdata('alert_message', 'Unable to setup meeting at this moment. Please contact support.');
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            // foreach ($packages as $key => $value) {
                            //     $target_service_id = $value['service_id'];
                            //     $category_id = $value['category_id'];
                            //     $serv_remarks = $value['service_desc'];

                            //     $insert_lead_array = array(
                            //         'customer_id' => $user_id,
                            //         'branch_id' => $branch_id,
                            //         'category_id' => $category_id,
                            //         'service_id' => $target_service_id,
                            //         'lead_created_by' => $this->auth_user_id,
                            //         'lead_added_on' => date('Y-m-d H:i:s'),
                            //         'contactable_date' => date('Y-m-d H:i:s'),
                            //         'lead_status' => 301,
                            //         'package_id' => $package_id,
                            //         'order_receipt' => 0,
                            //         'remarks' => $serv_remarks,
                            //         'is_assigned' => 0,
                            //         'lead_parent_id' => $parent_lead_id,
                            //     );
                            //     $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            //     if ($insert_lead_id > 0) {
                            //         //get branch name
                            //         $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                            //         //create action log
                            //         $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                            //         $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                            //         $package_lead_count++;
                            //     }
                            // }

                            $insert_lead_id = $parent_lead_id;

                            if ($package_lead_count > 0) {
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $package_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }

                if (isset($_POST["assign_to"]) || isset($_POST["assign_group"])) {
                    if (isset($_POST["assign_to"]) && $_POST["assign_to"] != "") {
                        $assigned_to = $_POST["assign_to"];
                    } else {
                        $group = $this->db->select("group_members.user_id")->from("group_members")->join("users", "users.user_id=group_members.user_id")->where("group_members.group_id", $_POST["assign_group"])->where("users.is_active", 1)->order_by('rand()')->limit(1)->get()->first_row();
                        $assigned_to = $group->user_id;
                    }
                    $lead_id = $insert_lead_id;

                    $assigned_by = $this->auth_user_id;
                    $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                    $insert_array = array(
                        'lead_id' => $lead_id,
                        'assigned_by' => $assigned_by,
                        'assigned_to' => $assigned_to,
                        'assigned_on' => date('Y-m-d H:i:s'),
                    );
                    // echo "<br>";
                    // echo "<br> ";
                    // print_r($insert_array);
                    $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                    $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                    $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

                    $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                    // print_r($log_insert_array);
                    $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                    // echo "Log: ".$log_insert."<br>";
                    // echo "ERROR: ";
                    // print_r($this->db->error());
                    // exit();
                    if ($insert > 0) {
                        $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                        if ($update) {
                            //create action log
                            $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $this->auth_first_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 302);
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                            $receiver_email = $csa->email;
                            $receiver_name = $csa->first_name;
                            $sender_email = $coordinator->email;
                            $sender_name = $coordinator->first_name;

                            $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you !";
                            $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
                            $email_array = array(
                                'email' => $receiver_email,
                                'subject' => $subject,
                                'template' => 'mails/template',
                                'from_name' => "CRM ALERT",
                                'message' => $message,
                            );
                            $send_mail = send_template_email($email_array);
                            log_message('error', $send_mail);

                            // $this->response('Lead has been assigned successfully!', 200);
                        } else {
                            $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                            // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                            // $this->response('Unable to assign the lead at present. Please try again later', 500);
                        }
                    }
                }

                $from_email = $this->auth_email;
                $customer_email = $this->input->post('lead_email');
                $agent_email = $this->auth_email;
                $email_subject = "Be Smart - Followup regarding - Payment";
                $email_message = $this->input->post('email_message');
                $payment_type = $this->input->post('payment_type');
                if (!isset($bot_id)) {
                    $bot_id = 0;
                }

                $approval_code = $this->input->post('approval_code');

                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                $service_payment = $this->input->post('amount_payment');
                $card_amount = $this->input->post('card_amount');

                $amount_payment = $service_payment + $card_amount;

                $crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
                // echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
                // exit();
                //construct message
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
                $agent_name = $this->mcommon->specific_row_value('users', array('email' => $agent_email), 'first_name');
                $message = "Dear " . $customer_name . ",<br /><br />";
                $message .= $email_message;

                $pre_token = $lead_id . "-" . $customer_email . "-@OnTimeCRM11..";
                // echo $pre_token;
                // echo "<br><br>";
                // exit();
                $token1 = md5($pre_token);
                $token2 = md5(strrev($pre_token));
                $token = $token1 . "-" . $token2;

                $msg = $amount_payment;
                $key = $token2;
                // print_r($token);
                // exit();
                $current_timestamp = date('Y-m-d H:i:s');
                $emp = $this->db->select("*")->from("users")->where("user_id", $this->auth_user_id)->get()->first_row();
                $employee = $emp->first_name . " " . $emp->last_name . "(" . $emp->employee_id . ")";
                //add entry in lead action log
                if ($payment_type == "online") {

                    $action_message = $message . "<p></p><div class='payment'><a href='#'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p></div><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $log_insert_array = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307, "bot_id" => $bot_id);

                    // print_r($log_insert_array);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $this->db->insert_id();
                    $log_id = encrypt_decrypt($action_id);
                    $payment_link = "https://crm.ontimegroup.com/payment/ccpay?ref=" . $token . "&token=" . $crypt_amount . "&identity=" . $log_id;

                    $this->mcommon->common_edit('lead_action_log', ["payment_link" => $payment_link], array('id' => $action_id));

                    $payment_message = "<p></p><a href='" . $payment_link . "'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $message .= $payment_message;

                    if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '') {
                        //SEND EMAIL TO CUSTOMER
                        // $customer_email = "muthuvenkatesh808@gmail.com";
                        $email_array = array(
                            'email' => $customer_email,
                            'subject' => $email_subject,
                            'template' => 'mails/lead_template',
                            'from_name' => $this->auth_first_name,
                            'from_email' => $this->auth_email,
                            'message' => $message,
                            'reply_to' => $this->auth_email,
                            'branch_id' => $branch_id
                            // 'cc' => [["email"=>$this->auth_email]]
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);

                        if ($send_mail) {

                            if ($insert_log > 0) {
                                //update lead status and contactable date in lead table
                                $update_lead_array = array('lead_status' => 307, 'contactable_date' => $contactable_date);
                                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', 'Payment Detail Email sent successfully. You will receive the copy of email.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Email sent successfully. But unable to update the lead record. Contact support.');
                            }
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        }

                        redirect(
                            'leads/lead/view/' . $lead_id
                        );
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        redirect('leads/lead/view/' . $lead_id);
                    }
                } else if ($payment_type == "cash") {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 417, 'action_amount' => $service_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $service_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    $curl = curl_init();

                    curl_setopt_array(
                        $curl,
                        array(
                            CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($req),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                            ),
                        )
                    );

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                    }
                    // print_r($response);
                    // exit();
                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));
                    $details=array();
                    if($branch_id!=25){
                    $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    // echo "<pre>";
                    // print_r($details);
                    // echo "</pre>";
                    // exit();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Be Smart - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Be Smart",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $service_payment,
                        "payment_type" => "CASH",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Cash to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    redirect('leads/lead/view/' . $lead_id);
                } else {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 418, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311, 'approval_code' => $approval_code);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];
                    $curl = curl_init();

                    curl_setopt_array(
                        $curl,
                        array(
                            CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($req),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                            ),
                        )
                    );

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                    }
                    // print_r($response);
                    // exit();
                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));
                    $details=array();
                    if($branch_id!=25){
                    $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Be Smart - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Be Smart",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $amount_payment,
                        "payment_type" => "CARD",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Card to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    redirect('leads/lead/view/' . $lead_id);
                }
            }

            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            $this->db->select("lead_packages.*,sum(lead_package_services.govt_fee+lead_package_services.typing_fee) as no_card_amount")->from("lead_packages");
            $this->db->join("lead_package_services", "lead_package_services.package_id=lead_packages.package_id");
            $this->db->where("lead_packages.package_branch", 125);
            $this->db->where("lead_packages.is_active", 1);
            $this->db->group_by("lead_packages.package_id");
            $package_list = $this->db->get()->result_array();


            $this->db->select("count(crm_user_timeslots.user_timeslot_id) as slot_days,sum(crm_user_timeslots.user_timeslot_slot_count) as total_slots,users.first_name as first_name,users.last_name as last_name,crm_timeslots.timeslot_name,creator.first_name as creator,users.user_id,crm_user_timeslots.created_at,crm_user_timeslots.user_timeslot_status as status,users.employee_id")->from("crm_user_timeslots");
            $this->db->join("crm_timeslots", "crm_timeslots.timeslot_id=crm_user_timeslots.user_timeslot_slot_id");
            $this->db->join("users", "users.user_id = crm_user_timeslots.user_timeslot_user_id");
            $this->db->join("users as creator", "creator.user_id = crm_user_timeslots.updated_by");
            // $this->db->where("crm_user_timeslots.user_timeslot_status = 1");
            $this->db->group_by("crm_user_timeslots.user_timeslot_user_id");
            $timeslots = $this->db->get()->result_array();

            $view_data['slot_users'] = $timeslots;
            //$view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1, "package_branch" => 106));
            $view_data['packages'] = $package_list;
            $data = array(
                'page_title' => 'Be Smart - New Sale',
                'title' => 'Be Smart - New Sale',
                'content' => $this->load->view('leads/lead/besmart_cube_new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function golden_cube_lead($lead_id)
    {
        // echo $lead_id;
        // exit();
        $lead_det = $this->leads_model->lead_details($lead_id);
        // print_r($lead_det);
        // exit();
        if ($lead_det["lead_parent_id"] != 0 || ($lead_det["branch_id"] != 106 && $lead_det["category_id"] != 10020) || ($lead_det["total_no_subleads"] != 0 && $lead_det["total_no_subleads"] != NULL) || ($lead_det["pos_salesorder"] != NULL && $lead_det["pos_pmt_number"] != NULL)) {
            $this->session->set_flashdata('alert', 'danger');
            $this->session->set_flashdata('alert_message', 'This lead is not qualify lead to generate order with this.');
            return redirect("/leads/lead/view/" . $lead_id);
        }

        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                // print_r($this->auth_user_id);
                // exit();
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";
                // echo "<pre>";
                // print_r($_POST);
                // echo "<pre>";
                // exit();
                log_message('error', 'create lead - Golden Cube');

                $branch_id = $this->input->post('branch_id');
                $lead_type = $this->input->post('lead_type');
                if ($lead_type == 'normal') {
                    $category_id = $this->input->post('category_id');
                    $service_id = $this->input->post('service_id');

                    $this->form_validation->set_rules('category_id', 'Category', 'required');
                    $this->form_validation->set_rules('service_id', 'Service', 'required');
                }

                if ($lead_type == 'package') {
                    $package_id = $this->input->post('package_id');
                    $this->form_validation->set_rules('package_id', 'Package', 'required');
                }

                $lead_name = $this->input->post('lead_name');
                $lead_contact = $this->input->post('lead_contact');
                $lead_email = $this->input->post('lead_email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                //if there are existing workflows for selected category & service, create lead entry for each workflow entry
                                foreach ($workflows as $key => $value) {
                                    $parent_service_id = $value['parent_service_id'];
                                    $target_service_id = $value['target_service_id'];
                                    $category_id = $value['category_id'];


                                    $insert_lead_id = $lead_id;
                                    if ($insert_lead_id > 0) {
                                        if (isset($_FILES['files']['name'])) {

                                            $attachment_name = $this->input->post('attachment_name');
                                            log_message('error', 'inside files uploads');
                                            // Count total files
                                            $countfiles = count($_FILES['files']['name']);
                                            log_message('error', 'no of files' . $countfiles);

                                            // Looping all files
                                            for ($i = 0; $i < $countfiles; $i++) {

                                                if (!empty($_FILES['files']['name'][$i])) {
                                                    log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                                    // Define new $_FILES array - $_FILES['file']
                                                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                                    // Set preference
                                                    $config['upload_path'] = 'uploads/leads';
                                                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                                    $config['max_size'] = '5000'; // max_size in kb
                                                    $config['file_name'] = $_FILES['files']['name'][$i];

                                                    //Load upload library
                                                    $this->load->library('upload', $config);

                                                    // File upload
                                                    if ($this->upload->do_upload('file')) {

                                                        // Get data about the file
                                                        $uploadData = $this->upload->data();
                                                        $filename = $uploadData['file_name'];
                                                        log_message('error', 'no of files' . $filename);
                                                        // Initialize array
                                                        $data['filenames'][] = $filename;

                                                        $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                        $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                                    }
                                                }
                                            }
                                        }
                                        //get branch name
                                        $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $normal_lead_count++;
                                    }
                                }
                            } else {
                                // else create one lead for selected category & service

                                $insert_lead_id = $lead_id;
                                $normal_lead_count = 1;
                            }

                            if ($normal_lead_count > 0) {
                                if (isset($_FILES['files']['name'])) {
                                    $attachment_name = $this->input->post('attachment_name');

                                    log_message('error', 'inside files uploads');
                                    // Count total files
                                    $countfiles = count($_FILES['files']['name']);
                                    log_message('error', 'no of files' . $countfiles);

                                    // Looping all files
                                    for ($i = 0; $i < $countfiles; $i++) {

                                        if (!empty($_FILES['files']['name'][$i])) {
                                            log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                            // Define new $_FILES array - $_FILES['file']
                                            $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                            $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                            $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                            $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                            // Set preference
                                            $config['upload_path'] = 'uploads/leads';
                                            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                            $config['max_size'] = '5000'; // max_size in kb
                                            $config['file_name'] = time() . "-" . $_FILES['files']['name'][$i];

                                            //Load upload library
                                            $this->load->library('upload', $config);

                                            // File upload
                                            if ($this->upload->do_upload('file')) {

                                                // Get data about the file
                                                $uploadData = $this->upload->data();
                                                $filename = $uploadData['file_name'];
                                                log_message('error', 'no of files' . $filename);
                                                // Initialize array
                                                $data['filenames'][] = $filename;

                                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                            }
                                        }
                                    }
                                }
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $normal_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }

                        if ($lead_type == 'package') {
                            $package = $this->db->select("*")->from("lead_packages")->where("package_id", $package_id)->get()->first_row();

                            $service_ids = $_POST["service_id"];
                            $service_names = $_POST["service_name"];
                            $service_qtys = $_POST["service_qty"];
                            $govt_fees = $_POST["govt_fee"];
                            $typing_fees = $_POST["typing_fee"];
                            $sub_totals = $_POST["sub_total"];
                            $is_direct_invoice = $_POST["is_direct_invoice"];
                            $msd_key = $_POST["msd_key"];
                            $is_pos_typing_fee = $_POST["is_pos_typing_fee"];

                            $update_array = array(
                                'category_id' => $package->package_category_id,
                                'service_id' => 10009,
                                'lead_created_by' => $this->auth_user_id,
                                'package_id' => $package_id,
                                'order_receipt' => 0,
                                'is_assigned' => 0,
                                'total_no_subleads' => count($service_ids),
                                'no_of_open_subleads' => count($service_ids),
                                'no_of_closed_subleads' => 0
                            );
                            $update = $this->mcommon->common_edit('leads', $update_array, array('id' => $lead_id));

                            $insert_lead_id = $lead_id;
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);


                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {
                                $card_amount = 0;
                                if ($payment_type != "cash") {
                                    $card_amount = ($govt_fees[$i] * (0 / 100));
                                    $sub_totals[$i] = $govt_fees[$i] + $typing_fees[$i] + $card_amount;
                                }

                                for ($ii = 0; $ii < $service_qtys[$i]; $ii++) {
                                    $package_detail = array(
                                        "lead_id" => $parent_lead_id,
                                        "package_id" => $package_id,
                                        "service_id" => $service_ids[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "card_amount" => $card_amount,
                                        "sub_total" => $sub_totals[$i],
                                        "payment_type" => $payment_type,
                                        "created_by" => $this->auth_user_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                    $this->mcommon->common_insert('lead_package_details', $package_detail);

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $package->package_category_id,
                                        'service_id' => $service_ids[$i],
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => $package_id,
                                        'order_receipt' => 0,
                                        'remarks' => $service_names[$i],
                                        'is_assigned' => 0,
                                        'lead_parent_id' => $parent_lead_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                    if ($payment_type != "cash") {
                                        $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
                                    }
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    // print_r($insert_lead_array);
                                    // exit();
                                    if ($insert_lead_id > 0) {
                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $package_lead_count++;
                                    }
                                }
                            }

                            // foreach ($packages as $key => $value) {
                            //     $target_service_id = $value['service_id'];
                            //     $category_id = $value['category_id'];
                            //     $serv_remarks = $value['service_desc'];

                            //     $insert_lead_array = array(
                            //         'customer_id' => $user_id,
                            //         'branch_id' => $branch_id,
                            //         'category_id' => $category_id,
                            //         'service_id' => $target_service_id,
                            //         'lead_created_by' => $this->auth_user_id,
                            //         'lead_added_on' => date('Y-m-d H:i:s'),
                            //         'contactable_date' => date('Y-m-d H:i:s'),
                            //         'lead_status' => 301,
                            //         'package_id' => $package_id,
                            //         'order_receipt' => 0,
                            //         'remarks' => $serv_remarks,
                            //         'is_assigned' => 0,
                            //         'lead_parent_id' => $parent_lead_id,
                            //     );
                            //     $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            //     if ($insert_lead_id > 0) {
                            //         //get branch name
                            //         $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                            //         //create action log
                            //         $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                            //         $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                            //         $package_lead_count++;
                            //     }
                            // }

                            $insert_lead_id = $parent_lead_id;

                            if ($package_lead_count > 0) {
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $package_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }

                if (isset($_POST["assign_to"]) || isset($_POST["assign_group"])) {
                    if (isset($_POST["assign_to"]) && $_POST["assign_to"] != "") {
                        $assigned_to = $_POST["assign_to"];
                    } else {
                        $group = $this->db->select("group_members.user_id")->from("group_members")->join("users", "users.user_id=group_members.user_id")->where("group_members.group_id", $_POST["assign_group"])->where("users.is_active", 1)->order_by('rand()')->limit(1)->get()->first_row();
                        $assigned_to = $group->user_id;
                    }
                    $lead_id = $insert_lead_id;

                    $assigned_by = $this->auth_user_id;
                    $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                    $insert_array = array(
                        'lead_id' => $lead_id,
                        'assigned_by' => $assigned_by,
                        'assigned_to' => $assigned_to,
                        'assigned_on' => date('Y-m-d H:i:s'),
                    );
                    // echo "<br>";
                    // echo "<br> ";
                    // print_r($insert_array);
                    $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                    $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                    $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

                    $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                    // print_r($log_insert_array);
                    $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                    // echo "Log: ".$log_insert."<br>";
                    // echo "ERROR: ";
                    // print_r($this->db->error());
                    // exit();
                    if ($insert > 0) {
                        $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                        if ($update) {
                            //create action log
                            $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $this->auth_first_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 302);
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                            $receiver_email = $csa->email;
                            $receiver_name = $csa->first_name;
                            $sender_email = $coordinator->email;
                            $sender_name = $coordinator->first_name;

                            $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you !";
                            $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";
                            $email_array = array(
                                'email' => $receiver_email,
                                'subject' => $subject,
                                'template' => 'mails/template',
                                'from_name' => "CRM ALERT",
                                'message' => $message,
                            );
                            $send_mail = send_template_email($email_array);
                            log_message('error', $send_mail);

                            // $this->response('Lead has been assigned successfully!', 200);
                        } else {
                            $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                            // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                            // $this->response('Unable to assign the lead at present. Please try again later', 500);
                        }
                    }
                }

                $from_email = $this->auth_email;
                $customer_email = $this->input->post('lead_email');
                $agent_email = $this->auth_email;
                $email_subject = "Golden Cube - Followup regarding - Payment";
                $email_message = $this->input->post('email_message');
                $payment_type = $this->input->post('payment_type');
                if (!isset($bot_id)) {
                    $bot_id = 0;
                }

                $approval_code = $this->input->post('approval_code');

                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                $service_payment = $this->input->post('amount_payment');
                $card_amount = $this->input->post('card_amount');

                $amount_payment = $service_payment + $card_amount;

                $crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
                // echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
                // exit();
                //construct message
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
                $agent_name = $this->mcommon->specific_row_value('users', array('email' => $agent_email), 'first_name');
                $message = "Dear " . $customer_name . ",<br /><br />";
                $message .= $email_message;

                $pre_token = $lead_id . "-" . $customer_email . "-@OnTimeCRM11..";
                // echo $pre_token;
                // echo "<br><br>";
                // exit();
                $token1 = md5($pre_token);
                $token2 = md5(strrev($pre_token));
                $token = $token1 . "-" . $token2;

                $msg = $amount_payment;
                $key = $token2;
                // print_r($token);
                // exit();
                $current_timestamp = date('Y-m-d H:i:s');
                $emp = $this->db->select("*")->from("users")->where("user_id", $this->auth_user_id)->get()->first_row();
                $employee = $emp->first_name . " " . $emp->last_name . "(" . $emp->employee_id . ")";
                //add entry in lead action log
                if ($payment_type == "online") {

                    $action_message = $message . "<p></p><div class='payment'><a href='#'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p></div><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $log_insert_array = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307, "bot_id" => $bot_id);

                    // print_r($log_insert_array);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $this->db->insert_id();
                    $log_id = encrypt_decrypt($action_id);
                    $payment_link = "https://crm.ontimegroup.com/payment/ccpay?ref=" . $token . "&token=" . $crypt_amount . "&identity=" . $log_id;

                    $this->mcommon->common_edit('lead_action_log', ["payment_link" => $payment_link], array('id' => $action_id));

                    $payment_message = "<p></p><a href='" . $payment_link . "'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $message .= $payment_message;

                    if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '') {
                        //SEND EMAIL TO CUSTOMER
                        // $customer_email = "muthuvenkatesh808@gmail.com";
                        $email_array = array(
                            'email' => $customer_email,
                            'subject' => $email_subject,
                            'template' => 'mails/lead_template',
                            'from_name' => $this->auth_first_name,
                            'from_email' => $this->auth_email,
                            'message' => $message,
                            'reply_to' => $this->auth_email,
                            'branch_id' => $branch_id
                            // 'cc' => [["email"=>$this->auth_email]]
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);

                        if ($send_mail) {

                            if ($insert_log > 0) {
                                //update lead status and contactable date in lead table
                                $update_lead_array = array('lead_status' => 307, 'contactable_date' => $contactable_date);
                                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', 'Payment Detail Email sent successfully. You will receive the copy of email.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Email sent successfully. But unable to update the lead record. Contact support.');
                            }
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        }

                        redirect(
                            'leads/lead/view/' . $lead_id
                        );
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        redirect('leads/lead/view/' . $lead_id);
                    }
                } else if ($payment_type == "cash") {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 417, 'action_amount' => $service_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $service_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    $curl = curl_init();

                    curl_setopt_array(
                        $curl,
                        array(
                            CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($req),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                            ),
                        )
                    );

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                    }
                    // print_r($response);
                    // exit();
                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));

                    $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();

                    // echo "<pre>";
                    // print_r($details);
                    // echo "</pre>";
                    // exit();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Golden Cube - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Golden Cube",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $service_payment,
                        "payment_type" => "CASH",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Cash to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    redirect('leads/lead/view/' . $lead_id);
                } else {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 418, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311, 'approval_code' => $approval_code);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];
                    $curl = curl_init();

                    curl_setopt_array(
                        $curl,
                        array(
                            CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => json_encode($req),
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                            ),
                        )
                    );

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                    }
                    // print_r($response);
                    // exit();
                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));

                    $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Golden Cube - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Golden Cube",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $amount_payment,
                        "payment_type" => "CARD",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Card to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    redirect('leads/lead/view/' . $lead_id);
                }
            }
            $view_data['lead'] = $lead_det;
            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            $this->db->select("lead_packages.*,sum(lead_package_services.govt_fee+lead_package_services.typing_fee) as no_card_amount")->from("lead_packages");
            $this->db->join("lead_package_services", "lead_package_services.package_id=lead_packages.package_id");
            $this->db->where("lead_packages.package_branch", 106);
            $this->db->where("lead_packages.is_active", 1);
            $this->db->group_by("lead_packages.package_id");
            $package_list = $this->db->get()->result_array();

            //$view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1, "package_branch" => 106));
            $view_data['packages'] = $package_list;
            $data = array(
                'page_title' => 'Golden Cube - Lead Sale',
                'title' => 'Golden Cube - Lead Sale',
                'content' => $this->load->view('leads/lead/golden_cube_lead', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function general_pack_sale_new()
    {

        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                // print_r($this->auth_user_id);
                // exit();

                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";
                // echo "<pre>";
                // print_r($_POST);
                // echo "<pre>";
                // exit();
                

                $branch_id = $this->input->post('branch_id');
                $lead_type = $this->input->post('lead_type');
                log_message('error', 'create lead - General Pckage='.$branch_id);
                if ($lead_type == 'normal') {
                    $category_id = $this->input->post('category_id');
                    $service_id = $this->input->post('service_id');

                    $this->form_validation->set_rules('category_id', 'Category', 'required');
                    $this->form_validation->set_rules('service_id', 'Service', 'required');
                }

                if ($lead_type == 'package') {
                    $package_id = $this->input->post('package_id');
                    $this->form_validation->set_rules('package_id', 'Package', 'required');
                }

                $lead_name = $this->input->post('lead_name');
                $lead_contact = $this->input->post('lead_contact');
                $lead_email = $this->input->post('lead_email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                //if there are existing workflows for selected category & service, create lead entry for each workflow entry
                                foreach ($workflows as $key => $value) {
                                    $parent_service_id = $value['parent_service_id'];
                                    $target_service_id = $value['target_service_id'];
                                    $category_id = $value['category_id'];

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $category_id,
                                        'service_id' => $target_service_id,
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => 0,
                                        'order_receipt' => 0,
                                        'remarks' => $lead_remarks,
                                        'is_assigned' => 0,
                                    );
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    if ($insert_lead_id > 0) {
                                        if (isset($_FILES['files']['name'])) {

                                            $attachment_name = $this->input->post('attachment_name');
                                            log_message('error', 'inside files uploads');
                                            // Count total files
                                            $countfiles = count($_FILES['files']['name']);
                                            log_message('error', 'no of files' . $countfiles);

                                            // Looping all files
                                            for ($i = 0; $i < $countfiles; $i++) {

                                                if (!empty($_FILES['files']['name'][$i])) {
                                                    log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                                    // Define new $_FILES array - $_FILES['file']
                                                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                                    // Set preference
                                                    $config['upload_path'] = 'uploads/leads';
                                                    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                                    $config['max_size'] = '5000'; // max_size in kb
                                                    $config['file_name'] = $_FILES['files']['name'][$i];

                                                    //Load upload library
                                                    $this->load->library('upload', $config);

                                                    // File upload
                                                    if ($this->upload->do_upload('file')) {

                                                        // Get data about the file
                                                        $uploadData = $this->upload->data();
                                                        $filename = $uploadData['file_name'];
                                                        log_message('error', 'no of files' . $filename);
                                                        // Initialize array
                                                        $data['filenames'][] = $filename;

                                                        $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                        $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                                    }
                                                }
                                            }
                                        }
                                        //get branch name
                                        $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $normal_lead_count++;
                                    }
                                }
                            } else {
                                // else create one lead for selected category & service
                                $insert_lead_array = array(
                                    'customer_id' => $user_id,
                                    'branch_id' => $branch_id,
                                    'category_id' => $category_id,
                                    'service_id' => $service_id,
                                    'lead_created_by' => $this->auth_user_id,
                                    'lead_added_on' => date('Y-m-d H:i:s'),
                                    'contactable_date' => date('Y-m-d H:i:s'),
                                    'lead_status' => 301,
                                    'package_id' => 0,
                                    'order_receipt' => 0,
                                    'remarks' => $lead_remarks,
                                    'is_assigned' => 0,
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;
                            }

                            if ($normal_lead_count > 0) {
                                if (isset($_FILES['files']['name'])) {
                                    $attachment_name = $this->input->post('attachment_name');

                                    log_message('error', 'inside files uploads');
                                    // Count total files
                                    $countfiles = count($_FILES['files']['name']);
                                    log_message('error', 'no of files' . $countfiles);

                                    // Looping all files
                                    for ($i = 0; $i < $countfiles; $i++) {

                                        if (!empty($_FILES['files']['name'][$i])) {
                                            log_message('error', 'no of files' . $_FILES['files']['name'][$i]);

                                            // Define new $_FILES array - $_FILES['file']
                                            $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                                            $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                                            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                                            $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                                            $_FILES['file']['size'] = $_FILES['files']['size'][$i];

                                            // Set preference
                                            $config['upload_path'] = 'uploads/leads';
                                            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                                            $config['max_size'] = '5000'; // max_size in kb
                                            $config['file_name'] = time() . "-" . $_FILES['files']['name'][$i];

                                            //Load upload library
                                            $this->load->library('upload', $config);

                                            // File upload
                                            if ($this->upload->do_upload('file')) {

                                                // Get data about the file
                                                $uploadData = $this->upload->data();
                                                $filename = $uploadData['file_name'];
                                                log_message('error', 'no of files' . $filename);
                                                // Initialize array
                                                $data['filenames'][] = $filename;

                                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name[$i], 'attachment_url' => base_url() . 'uploads/leads/' . $filename);
                                                $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                                            }
                                        }
                                    }
                                }
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $normal_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }

                        if ($lead_type == 'package') {
                            $package = $this->db->select("*")->from("lead_packages")->where("package_id", $package_id)->get()->first_row();

                            $service_ids = $_POST["service_id"];
                            $service_names = $_POST["service_name"];
                            $service_qtys = $_POST["service_qty"];
                            $govt_fees = $_POST["govt_fee"];
                            $typing_fees = $_POST["typing_fee"];
                            $msd_key = $_POST["msd_key"];
                            $sub_totals = $_POST["sub_total"];
                            $is_direct_invoice = $_POST["is_direct_invoice"];
                            $is_pos_typing_fee = $_POST["is_pos_typing_fee"];

                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $package->package_category_id,
                                'service_id' => 10009,
                                'lead_created_by' => $this->auth_user_id,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => $package_id,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'total_no_subleads' => count($service_ids),
                                'no_of_open_subleads' => count($service_ids),
                                'no_of_closed_subleads' => 0
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);


                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {
                                //$card_amount = 0;
                                //if ($payment_type != "cash") {
                                //    $card_amount = ($govt_fees[$i] * (0 / 100));
                                 //   $sub_totals[$i] = $govt_fees[$i] + $typing_fees[$i] + $card_amount;
                                //}

                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                }else if ($payment_type == "online") {
                                     $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                }else{
                                    $card_amount = 0;
                                }
                                $sub_totals[$i] = $govt_fees[$i] + $typing_fees[$i] + $card_amount;

                                for ($ii = 0; $ii < $service_qtys[$i]; $ii++) {
                                    $package_detail = array(
                                        "lead_id" => $parent_lead_id,
                                        "package_id" => $package_id,
                                        "service_id" => $service_ids[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "card_amount" => $card_amount,
                                        "sub_total" => $sub_totals[$i],
                                        "payment_type" => $payment_type,
                                        "created_by" => $this->auth_user_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                    $this->mcommon->common_insert('lead_package_details', $package_detail);

                                    $insert_lead_array = array(
                                        'customer_id' => $user_id,
                                        'branch_id' => $branch_id,
                                        'category_id' => $package->package_category_id,
                                        'service_id' => $service_ids[$i],
                                        'lead_created_by' => $this->auth_user_id,
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => $package_id,
                                        'order_receipt' => 0,
                                        'remarks' => $service_names[$i],
                                        'is_assigned' => 0,
                                        'lead_parent_id' => $parent_lead_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "govt_fee" => $govt_fees[$i],
                                        "typing_fee" => $typing_fees[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                    //if ($payment_type != "cash") {
                                    //    $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
                                    //}
                                     $insert_lead_array["card_amount"] = $card_amount;

                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                    // print_r($insert_lead_array);
                                    // exit();
                                    if ($insert_lead_id > 0) {
                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $package_lead_count++;

                                        if (isset($_POST["slot_user_id"][$service_ids[$i]])) {
                                            $slot = $this->mcommon->specific_row_value('crm_timeslots', array('timeslot_id' => $_POST["slot"][$service_ids[$i]]), 'timeslot_name');
                                            $contactable_date = $_POST["slot_date"][$service_ids[$i]];
                                            $meeting_remarks = "Meeting Scheduled at " . $slot;
                                            if ($meeting_remarks != '' && $contactable_date != '') {
                                                //create meeting
                                                $l_det = $this->leads_model->lead_details($insert_lead_id);

                                                $assigned_to = $_POST["slot_user_id"][$service_ids[$i]];
                                                $assigned_by = $this->auth_user_id;
                                                $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $insert_lead_id));
                                                $insert_array = array(
                                                    'lead_id' => $insert_lead_id,
                                                    'assigned_by' => $assigned_by,
                                                    'assigned_to' => $assigned_to,
                                                    'assigned_on' => date('Y-m-d H:i:s'),
                                                );
                                                // echo "<br>";
                                                // echo "<br> ";
                                                // print_r($insert_array);
                                                $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                                                $crm_user_id = $this->auth_user_id;
                                                $customer_id = $this->mcommon->specific_row_value('leads', array('id' => $insert_lead_id), 'customer_id');
                                                $meeting_date_time = $contactable_date;
                                                $remarks = $meeting_remarks;
                                                $is_complete = 0;
                                                $created_at = date('Y-m-d H:i:s');
                                                $last_updated = date('Y-m-d H:i:s');

                                                $meeting_insert_array = array(
                                                    'lead_id' => $insert_lead_id,
                                                    'crm_user_id' => $crm_user_id,
                                                    'customer_id' => $customer_id,
                                                    'meeting_date_time' => $meeting_date_time,
                                                    'remarks' => $remarks,
                                                    'is_complete' => $is_complete,
                                                    'slot_id' => $_POST["slot"][$service_ids[$i]],
                                                    'created_at' => $created_at,
                                                    'last_updated' => $last_updated,
                                                );
                                                $meeting_insert = $this->mcommon->common_insert('lead_meetings', $meeting_insert_array);

                                                if ($meeting_insert > 0) {
                                                    //TODO: schedule an email before 15 mins of the meeting.

                                                    $log_insert_array = array('action_id' => 407, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $meeting_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                                    if ($insert_log > 0) {
                                                        //update lead status and contactable date in lead table
                                                        $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                                                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $insert_lead_id));

                                                        $receiver_email = $l_det["customer_email"];
                                                        $subject = "Your Appointment is scheduled on Al Baraha Medical Examination Center";
                                                        $message = "Dear " . $l_det["customer_name"] . ",<br /><br />Thank You for choosing OnTime Services.<br><br>Your appointment for Medical Test is confirmed at our Al Baraha Branch, please find the details of your appointment below:<br>";
                                                        $message .= "Date: " . $meeting_date_time . "<br>";
                                                        $message .= "Time: " . $slot . "<br>";
                                                        $message .= "<u>Address:</u> <br>Al Baraha Smart Medical Excamination Center <br>for Residency - Weqayati<br>Gate No. 2, Inside Al Baraha Hospital<br>Tel: 60057007<br>";
                                                        $email_array = array(
                                                            'email' => $receiver_email,
                                                            'subject' => $subject,
                                                            'template' => 'mails/template',
                                                            'from_name' => "OnTime - Golden Cube",
                                                            'message' => $message,
                                                        );
                                                        $send_mail = send_template_email($email_array);
                                                        //log_message('error', $send_mail);





                                                        // echo "Lead==> ".$lead_id;
                                                        // print_r($l_det);
                                                        // exit();
                                                        $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();

                                                        $subject = "Lead #" . $insert_lead_id . " Appointment is scheduled to you!!";
                                                        $message = "An appointment has been arranged to you by " . $coordinator->first_name . "<br><br>Lead Description:";

                                                        $message .= "<br>Customer Name: " . $l_det["customer_name"];
                                                        $message .= "<br>Customer Contact: " . $l_det["customer_mobile"];
                                                        $message .= "<br>Customer Email: " . $l_det["customer_email"];
                                                        $message .= "<br>Service: " . $l_det["category_code"] . " - " . $l_det["service_name"];
                                                        $message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $insert_lead_id . " .<br>";

                                                        $email_array = array(
                                                            'email' => $coordinator->email,
                                                            'subject' => $subject,
                                                            'template' => 'mails/template',
                                                            'from_name' => "CRM ALERT",
                                                            'message' => $message,
                                                        );


                                                        $send_mail = send_template_email($email_array);
                                                        log_message('error', $send_mail);






                                                        $this->session->set_flashdata('alert', 'success');
                                                        $this->session->set_flashdata('alert_message', 'Meeting scheduled successfully. You can see the progress in timeline.');
                                                    } else {
                                                        $this->session->set_flashdata('alert', 'danger');
                                                        $this->session->set_flashdat