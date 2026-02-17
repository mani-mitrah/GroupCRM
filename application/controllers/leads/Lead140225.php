<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lead extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
        $this->load->helper('datatables');
        date_default_timezone_set('Asia/Dubai');
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
    public function sendemail()
    {
        $receiver_email = 'app.zenerom@gmail.com';
        $receiver_name = 'Akhil';
        $sender_email = 'hanna.h@egovllc.com';
        $sender_name = 'Hanna';

        $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you !";
        $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:<br>";

        $lead_det = $this->leads_model->lead_details($lead_id);

        if ($lead_det["lead_parent_id"] != 0) {
            $parent_lead_det = $this->leads_model->lead_details($lead_det["lead_parent_id"]);
        }

        $message .= "Customer Name: " . $lead_det["customer_name"];
        $message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
        $message .= "<br>Customer Email: " . $lead_det["customer_email"];
        $message .= "<br>Service:  " . $lead_det["category_code"] . " - " . $lead_det["service_name"];
        if ($lead_det["pos_pmt_number"] != NULL)
            $message .= "<br>Receipt Number: " . $lead_det["pos_pmt_number"];
        else if ($parent_lead_det["pos_pmt_number"] != NULL)
            $message .= "<br>Receipt Number: " . $parent_lead_det["pos_pmt_number"];

        $message .= "<br>Remarks: " . $lead_det["remarks"];

        $email_array = array(
            'email' => $receiver_email,
            'subject' => $subject,
            'template' => 'mails/template',
            'from_name' => "CRM ALERT",
            'message' => $message,
        );
        $send_mail = send_template_email($email_array);
        echo $send_mail;
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

                            $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
					        $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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

            $created_from_date = $this->input->post("created_from_date") ?: date('Y-m-d', strtotime('-30 days'));
            $created_to_date = $this->input->post("created_to_date") ?: date('Y-m-d');

            // // Fetch "ReAssigned Leads" date range
            $reassigned_from_date = $this->input->post("reassigned_from_date") ?: date('Y-m-d', strtotime('-30 days'));
            $reassigned_to_date = $this->input->post("reassigned_to_date") ?: date('Y-m-d');

            // Fetch "Assigned Leads" date range
            $assigned_from_date = $this->input->post("assigned_from_date") ?: date('Y-m-d', strtotime('-30 days'));
            $assigned_to_date = $this->input->post("assigned_to_date") ?: date('Y-m-d');

            // Fetch "Your Leads" date range
            $yourleads_from_date = $this->input->post("your_lead_from_date") ?: date('Y-m-d', strtotime('-30 days'));
            $yourleads_to_date = $this->input->post("your_lead_to_date") ?: date('Y-m-d');

            // Fetch "Converted Leads" date range
            $converted_from_date = $this->input->post("converted_from_date") ?: date('Y-m-d', strtotime('-30 days'));
            $converted_to_date = $this->input->post("converted_to_date") ?: date('Y-m-d');

            // Fetch "Disqualified Leads" date range
            $disqualified_from_date = $this->input->post("disqualified_from_date") ?: date('Y-m-d', strtotime('-30 days'));
            $disqualified_to_date = $this->input->post("disqualified_to_date") ?: date('Y-m-d');

            // Ensure dates are passed to the view
            $view_data["request"] = [
                "created_from_date" => $created_from_date,
                "created_to_date" => $created_to_date,
                "reassigned_from_date" => $reassigned_from_date,
                "reassigned_to_date" => $reassigned_to_date,
                "assigned_from_date" => $assigned_from_date,
                "assigned_to_date" => $assigned_to_date,
                "your_lead_from_date" => $yourleads_from_date,
                "your_lead_to_date" => $yourleads_to_date,
                "converted_from_date" => $converted_from_date,
                "converted_to_date" => $converted_to_date,
                "disqualified_from_date" => $disqualified_from_date,
                "disqualified_to_date" => $disqualified_to_date
            ];

            if (!empty($category_array)) {
                $view_data['unassigned_leads'] = $this->leads_model->unassigned_leads_for_user($category_array);
                $view_data['accepted_leads'] = $this->leads_model->user_leads($yourleads_from_date, $yourleads_to_date);
                $view_data['accepted_leads_count'] = $view_data['accepted_leads'][0]['count'];
                if ($this->auth_user_role > 4) {
                    $view_data['created_leads'] = $this->leads_model->created_leads($created_from_date, $created_to_date);
                    $view_data['created_leads_count'] = $view_data['created_leads'][0]['count'];
                    $view_data['reassigned_leads'] = $this->leads_model->leads_reassigned($reassigned_from_date, $reassigned_to_date);
                    $view_data['reassigned_leads_count'] = $view_data['reassigned_leads'][0]['count'];
                    $view_data['assigned_leads'] = $this->leads_model->leads_assigned_by_coordinator($assigned_from_date, $assigned_to_date);
                    $view_data['assigned_leads_count'] = $view_data['assigned_leads'][0]['count'];
                    if (isset($_POST['lead_type'])) {
                        $lead_type = $_POST['lead_type'];
                        $user_type = $_POST['user_type'];
                        if ($lead_type == "disqualified") {
                            $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads($disqualified_from_date, $disqualified_to_date, $user_type);
                            $view_data['disqualified_leads_count'] = $view_data['disqualified_leads'][0]['count'];
                            $view_data['converted_leads'] = $this->leads_model->converted_leads($converted_from_date, $converted_to_date);
                            $view_data['converted_leads_count'] = $view_data['converted_leads'][0]['count'];
                        }
                        if ($lead_type == "converted") {
                            $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads($disqualified_from_date, $disqualified_to_date);
                            $view_data['disqualified_leads_count'] = $view_data['disqualified_leads'][0]['count'];
                            $view_data['converted_leads'] = $this->leads_model->converted_leads($converted_from_date, $converted_to_date, $user_type);
                            $view_data['converted_leads_count'] = $view_data['converted_leads'][0]['count'];
                        }
                    } else {
                        $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads($disqualified_from_date, $disqualified_to_date);
                        $view_data['disqualified_leads_count'] = $view_data['disqualified_leads'][0]['count'];
                        $view_data['converted_leads'] = $this->leads_model->converted_leads($converted_from_date, $converted_to_date);
                        $view_data['converted_leads_count'] = $view_data['converted_leads'][0]['count'];
                    }
                } else {
                    $view_data['created_leads'] = $this->leads_model->created_leads($created_from_date, $created_to_date);
                    if (isset($_POST['lead_type'])) {
                        $lead_type = $_POST['lead_type'];
                        $user_type = $_POST['user_type'];
                        if ($lead_type == "disqualified") {
                            $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads($disqualified_from_date, $disqualified_to_date, $user_type);
                            $view_data['disqualified_leads_count'] = $view_data['disqualified_leads'][0]['count'];
                            $view_data['converted_leads'] = $this->leads_model->converted_leads($converted_from_date, $converted_to_date);
                            $view_data['converted_leads_count'] = $view_data['converted_leads'][0]['count'];
                        }
                        if ($lead_type == "converted") {
                            $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads($disqualified_from_date, $disqualified_to_date);
                            $view_data['disqualified_leads_count'] = $view_data['disqualified_leads'][0]['count'];
                            $view_data['converted_leads'] = $this->leads_model->converted_leads($converted_from_date, $converted_to_date, $user_type);
                            $view_data['converted_leads_count'] = $view_data['converted_leads'][0]['count'];
                        }
                    } else {
                        $view_data['converted_leads'] = $this->leads_model->converted_leads($converted_from_date, $converted_to_date);
                        $view_data['converted_leads_count'] = $view_data['converted_leads'][0]['count'];
                        $view_data['disqualified_leads'] = $this->leads_model->disqualified_leads($disqualified_from_date, $disqualified_to_date);
                        $view_data['disqualified_leads_count'] = $view_data['disqualified_leads'][0]['count'];
                    }
                }
            } else {
                $view_data['unassigned_leads'] = array();
                $view_data['accepted_leads'] = array();
                $view_data['converted_leads'] = array();
                $view_data['disqualified_leads'] = array();

                $view_data['unassigned_leads_count'] = 0;
                $view_data['accepted_leads_count'] = 0;
                $view_data['converted_leads_count'] = 0;
                $view_data['disqualified_leads_count'] = 0;
            }

            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
            $group_ids = [];
            foreach ($groups as $group) {
                array_push($group_ids, $group["group_id"]);
            }
            $view_data['group_ids'] = $group_ids;

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

                            $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
					        $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
                    $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
                $lead_email = $this->input->post('email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');
                $cust_id = ($this->input->post('cust_id')) ? $this->input->post('cust_id') : null;

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, $cust_id);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal' || $lead_type == 'package') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'created_group_id' => $created_group_id,
                                        'pos_cust_key' => $cust_id
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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                                    }
                                }
                            } else {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

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
                                    'created_group_id' => $created_group_id,
                                    'pos_cust_key' => $cust_id
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                if (isset($_POST["assign_to"]) || isset($_POST["assign_group"]) || isset($_POST["assign_type"])) {

                    if ($_POST["assign_type"] == "group") {
                        if (isset($_POST["assign_to"]) && $_POST["assign_to"] != "") {
                            $assigned_to = $_POST["assign_to"];
                        } else {
                            $group = $this->db->select("group_members.user_id")->from("group_members")->join("users", "users.user_id=group_members.user_id")->where("group_members.group_id", $_POST["assign_group"])->where("users.is_active", 1)->order_by('rand()')->limit(1)->get()->first_row();
                            $assigned_to = $group->user_id;
                        }
                    } else {
                        $assigned_to = $this->auth_user_id;
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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	


                    $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                    // print_r($log_insert_array);
                    $log_insert = $this->db->insert('lead_action_log', $log_insert_array);


                    if ($branch_id == 107) {
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

                        if ($user_group->group_id == 88) {
                            $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302, 'biz_assigned' => $assigned_to), array('id' => $lead_id));
                        } else {
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


        $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
        $req["PMTNumber"] = $pmt;
        $req["OrderRef"] = $sublead["id"] . '-OTLDDI' . $lead["id"];
        $_SubLeadId = $sublead["id"] ? $sublead["id"] : $lead["id"];

        // $action_id
        $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
        $req["salesorderdtl"] = [["Id" => $pos_invoice_id['pos_invoice_id'], "AddTypingFee" => 0, "SubLeadId" => $_SubLeadId]];

        $req["User"] = ["User_ID" => $user_pos];

        // POS Changes 
        $lead_id = $lead["id"];
        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
        $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

        if(!empty($lead_det["lead_zoho_id"])){
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
            if(!empty($lead_created_by)){
                $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
            } else {
                $created_by_user = '';
            }

            $req["Payment"] = array(
                "CampaignSource" => $lead_det["lead_ad_campaign"],
                "ZohoLeadSource" => $lead_det["lead_source"],
                "CampaignId" => $lead_det["lead_ad_campaign_id"],
                "ZohoLeadId" => $lead_det["lead_zoho_id"],
                "LeadFrom" => 'Zoho',
                "CRMLeadId" => $lead_id,
                "ZohoCreatedBy" => $created_by_user
            );
            
        } else {
            $req["Payment"] = array(
                "LeadSource" => 'Website',
                "LeadFrom" => $lead_det["lead_from"],
                "CRMLeadId" => $lead_id,
                "LeadCreatedBy" => $created_by_user,
            );
        }

        if(!empty($lead_det["pos_cust_key"])){
            $req["Cust_Key"] = $lead_det["pos_cust_key"];
        }


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
            $pos_cust_key = $res_json->Data->Cust_Key;
            $raw_salesorder = $so_order;
            $so_order = "under the payment receipt " . $so_order . "</b>";
        }
        $this->mcommon->common_edit("leads", array("pos_so_response" => $response, "pos_invresponse" => $raw_salesorder, "pos_pmt_number" => $pmt,"pos_cust_key" =>  $pos_cust_key), array("id" => $sublead["id"]));

        $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $raw_salesorder;

        $log_insert_array = array('action_id' => 410, 'lead_id' => $sublead["id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $this->auth_user_id, 'status_id' => 305);
        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
        if ($insert_log > 0) {
            $update_lead_array = array('lead_status' => 305, 'order_receipt' => $raw_salesorder, "completed_by" => $this->auth_user_id);
            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $sublead["id"]));

            if ($lead_det["lead_parent_id"] != 0) {
                $this->db->where('id', $lead_det["lead_parent_id"]);
                $this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
                $this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
                $this->db->update('leads');

                // Email process for send the visa process mail
                $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'remarks');
                $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'id');
                $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong>" . $service_name . "</strong> for the lead listed below <br>";
                $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                $sub_lead_message .= "<br><br>Lead Description:<br>";
                $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                $sub_lead_message .= "<br>Receipt Number: <strong>" . $lead_det["pos_pmt_number"] . "</strong>";
                $sub_lead_message .= "<br>Remarks: " . $service_name;

                if ($lead_det['msd_key'] == 69) {
                    array_push($sublead_cc_usermail, ["email" => "Fawziya.h@ontimegov.com", "name" => "Fawziya"]);    // 980422236
                    array_push($sublead_cc_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali"]);    // 2411946200
                    $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Fawziya and Abdulaziz Ali";
                }
                // array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

                $email_array = array(
                    'email' =>  $sublead_cc_usermail,
                    'subject' => $sub_lead_subject,
                    'template' => 'mails/template',
                    'from_name' => "Golden Cube",
                    'message' => $sub_lead_message,
                );

                $action_by = 178140614;        // info@ontimegroup.com
                $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_det["lead_parent_id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                $send_mail = send_template_email($email_array);
                log_message('error', $send_mail);
            } else if ($lead_det["id"] != 0 && $lead_det["lead_parent_id"] == 0) {
                $this->db->where('id', $lead_det["id"]);
                $this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
                $this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
                $this->db->update('leads');
            }

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


        $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
        $req["PMTNumber"] = $pmt;
        $req["OrderRef"] = $sublead["id"] . '-OTLDDI' . $lead["id"];

        // $action_id
        $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
        $req["salesorderdtl"] = [["Id" => 1230, "AddTypingFee" => 0, "SubLeadId" => $_SubLeadId]];

        $req["User"] = ["User_ID" => $user_pos];

        // POS Changes 
        $lead_id = $lead["id"];
        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
        $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

        if(!empty($lead_det["lead_zoho_id"])){
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
            if(!empty($lead_created_by)){
                $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
            } else {
                $created_by_user = '';
            }

            $req["Payment"] = array(
                "CampaignSource" => $lead_det["lead_ad_campaign"],
                "ZohoLeadSource" => $lead_det["lead_source"],
                "CampaignId" => $lead_det["lead_ad_campaign_id"],
                "ZohoLeadId" => $lead_det["lead_zoho_id"],
                "LeadFrom" => 'Zoho',
                "CRMLeadId" => $lead_id,
                "ZohoCreatedBy" => $created_by_user
            );
            
        } else {
            $req["Payment"] = array(
                 "LeadSource" => 'Website',
                "LeadFrom" => $lead_det["lead_from"],
                "CRMLeadId" => $lead_id,
                "LeadCreatedBy" => $created_by_user,
            );
        }

        if(!empty($lead_det["pos_cust_key"])){
            $req["Cust_Key"] = $lead_det["pos_cust_key"];
        }


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
            $pos_cust_key = $res_json->Data->Cust_Key;
            $raw_salesorder = $so_order;
            $so_order = "under the payment receipt " . $so_order . "</b>";
        }
        $this->mcommon->common_edit("leads", array("pos_so_response" => $response, "pos_invresponse" => $raw_salesorder, "pos_pmt_number" => $pmt, "pos_cust_key" =>  $pos_cust_key), array("id" => $sublead["id"]));

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

            $details = array();

            if ($getLeadInfo["branch_id"] != 25) {
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

            $details = array();

            if ($getLeadInfo["branch_id"] != 25) {
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
                "branch_id" => $lead_det["branch_id"],
                "action" => $action
            );

            // This email is for the GC Payment Reciept ==============================================
            /* $lead_remarks = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "remarks");
            $remarks_data = html_entity_decode($lead_remarks);
            $result = explode(',',$remarks_data);
            $package_name = str_replace('Package:','',$result[1]);
            $gc_message = array(
                'customer_name' => $lead_det["customer_name"],
                'transaction_id' => $raw_salesorder,
                'payment_date' => $action["action_on"],
                'amount' => $service_payment,
            );

            $email_array = array(
                'email' => $customer_email,
                'subject' => 'Goldencube - Payment Recieved',
                'template' => 'mails/gc_application_payment_received',
                'from_name' => "Golden Cube",
                'message' => $gc_message,
            );
            $send_mail = send_template_email($email_array); */

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

        // $user_init_id=3020140166;
        // $user_init_email="basel.a@goldencube.ae";
        // $user_init_firstname="Basel";

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
                $lead_id = $this->input->post('lead_id');

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

                if ($lead_type == 'goldencube_package') {
                    $package_id = $this->input->post('package_id1');
                    $this->form_validation->set_rules('package_id1', 'Package', 'required');
                }

                $lead_name = $this->input->post('lead_name');
                $lead_contact = $this->input->post('lead_contact');
                $lead_email = $this->input->post('lead_email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');
                $cust_id = ($this->input->post('cust_id')) ? $this->input->post('cust_id') : null;

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, $cust_id);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'created_group_id' => $created_group_id,
                                        'pos_cust_key' => $cust_id
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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                                    }
                                }
                            } else {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                    'created_group_id' => $created_group_id,
                                    'pos_cust_key' => $cust_id
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                        if ($lead_type == 'package'  || $lead_type == 'goldencube_package') {
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
                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

                            if ($lead_id) {
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
                                    'no_of_closed_subleads' => 0,
                                    'created_group_id' => $created_group_id
                                );
                                $update = $this->mcommon->common_edit('leads', $insert_lead_array, array('id' => $lead_id));
                                $parent_lead_id = $lead_id;
                            } else {
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
                                    'no_of_closed_subleads' => 0,
                                    'created_group_id' => $created_group_id,
                                    'pos_cust_key' => $cust_id

                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $parent_lead_id = $insert_lead_id;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                            }

                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);


                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {


                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                } else if ($payment_type == "online") {
                                    $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                } else {
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
                                        "card_amount" => $card_amount,  // $packages->card_amount,
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
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i],
                                        "card_amount" => $card_amount,
                                        'pos_cust_key' => $cust_id
                                    );
                                    if ($payment_type != "cash") {
                                        // $insert_lead_array["card_amount"] = $packages->card_amount;
                                        // $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
                                        $insert_lead_array["card_amount"] = $card_amount;
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

                                                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $insert_lead_id));

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

                            $postData = array(
                                'lead_id' => $insert_lead_id,
                            );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Accept: application/json',
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                            curl_setopt($ch, CURLOPT_HEADER, false); 
                            curl_setopt($ch, CURLOPT_POST, true);  
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                            $response = curl_exec($ch);
                            if(!empty($response))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = array();
                    if ($branch_id != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    // echo "<pre>";
                    // print_r($details);
                    // echo "</pre>";
                    // exit();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;

                    // $bcc_usermail = [];
                    // array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.com", "name" => "Mani"]);

                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Golden Cube - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Golden Cube",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // "bcc" => $bcc_usermail,
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $service_payment,
                        "payment_type" => "CASH",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"],
                        "is_terms_pdf" => true,
                    );
                    $send_mail = send_lead_template_email($email_array);

                    // Sub lead completion email process
                    $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'remarks');
                    $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'id');

                    if ($service_name != "" && $service_name != null) {
                        $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                        $sub_lead_message .= "<br>Dear Ishti<br>";
                        $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong> DLD fees </strong> transaction for the lead listed below <br>";
                        $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                        $sub_lead_message .= "<br><br>Lead Description:<br>";
                        $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                        $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                        $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                        $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                        $sub_lead_message .= "<br>Receipt Number: <strong>" . $raw_salesorder . "</strong>";
                        $sub_lead_message .= "<br>Remarks: " . $service_name;

                        $sub_lead_message .= "<br><br><br><br>Dear Reem<br><br>";
                        $sub_lead_message .= "<br>Please proceed with completing the <strong> DLD Certificate </strong> transaction for the customer once the lead is completed<br><br>";

                        $sublead_cc_usermail = [];
                        // After Payment Completion for DLD Fees
                        array_push($sublead_cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);    // 2879029976
                        array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                        // array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

                        $email_array = array(
                            'email' => $sublead_cc_usermail,
                            'subject' => $sub_lead_subject,
                            'template' => 'mails/template',
                            'from_name' => "CRM ALERT",
                            'message' => $sub_lead_message,
                        );

                        $send_mail = send_template_email($email_array);

                        $action_by = 178140614;        // info@ontimegroup.com
                        $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Reem and Ishti";
                        $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        log_message('error', $send_mail);
                    }

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Cash to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

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
                    $details = array();
                    if ($branch_id != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;

                    // $bcc_usermail = [];
                    // array_push($bcc_usermail, ["email" => "manikandan.tm@mitrahsoft.com", "name" => "Mani"]);

                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'Golden Cube - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "Golden Cube",
                        'from_email' => "info@goldencube.ae",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // "bcc" => $bcc_usermail,
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $amount_payment,
                        "payment_type" => "CARD",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"],
                        "is_terms_pdf" => true,
                    );
                    $send_mail = send_lead_template_email($email_array);

                    // Sub lead completion email process
                    $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'remarks');
                    $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'id');

                    if ($service_name != "" && $service_name != null) {
                        $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                        $sub_lead_message .= "<br>Dear Ishti<br>";
                        $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong> DLD fees </strong> transaction for the lead listed below <br>";
                        $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                        $sub_lead_message .= "<br><br>Lead Description:<br>";
                        $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                        $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                        $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                        $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                        $sub_lead_message .= "<br>Receipt Number: <strong>" . $raw_salesorder . "</strong>";
                        $sub_lead_message .= "<br>Remarks: " . $service_name;

                        $sub_lead_message .= "<br><br><br><br>Dear Reem<br><br>";
                        $sub_lead_message .= "<br>Please proceed with completing the <strong> DLD Certificate </strong> transaction for the customer once the lead is completed<br><br>";

                        $sublead_cc_usermail = [];
                        // After Payment Completion for DLD Fees
                        array_push($sublead_cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);    // 2879029976
                        array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                        // array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

                        $email_array = array(
                            'email' => $sublead_cc_usermail,
                            'subject' => $sub_lead_subject,
                            'template' => 'mails/template',
                            'from_name' => "CRM ALERT",
                            'message' => $sub_lead_message,
                        );

                        $send_mail = send_template_email($email_array);

                        $action_by = 178140614;        // info@ontimegroup.com
                        $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Reem and Ishti";
                        $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        log_message('error', $send_mail);
                    }

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Card to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, null);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'created_group_id' => $created_group_id,
                                    );
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                                    $postData = array(
                                        'lead_id' => $insert_lead_id,
                                    );
        
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Accept: application/json',
                                        'Content-Type: application/json'
                                    ]);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                    curl_setopt($ch, CURLOPT_HEADER, false); 
                                    curl_setopt($ch, CURLOPT_POST, true);  
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                    $response = curl_exec($ch);
                                    if(!empty($response))
                                        $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

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
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                    'created_group_id' => $created_group_id,
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

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
                                'no_of_closed_subleads' => 0,
                                'created_group_id' => $created_group_id,
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $postData = array(
                                'lead_id' => $insert_lead_id,
                            );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Accept: application/json',
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                            curl_setopt($ch, CURLOPT_HEADER, false); 
                            curl_setopt($ch, CURLOPT_POST, true);  
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                            $response = curl_exec($ch);
                            if(!empty($response))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);


                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {


                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                } else if ($payment_type == "online") {
                                    $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                } else {
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

                                                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $insert_lead_id));

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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

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
                    $details = array();
                    if ($branch_id != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    // echo "<pre>";
                    // print_r($details);
                    // echo "</pre>";
                    // exit();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = array();
                    if ($branch_id != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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

                if ($lead_type == 'goldencube_package') {
                    $package_id = $this->input->post('package_id1');
                    $this->form_validation->set_rules('package_id1', 'Package', 'required');
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
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, null);
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

                        if ($lead_type == 'package' || $lead_type == 'goldencube_package') {
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
                                // 'lead_created_by' => $this->auth_user_id,
                                'package_id' => $package_id,
                                'order_receipt' => 0,
                                'is_assigned' => 0,
                                'total_no_subleads' => count($service_ids),
                                'no_of_open_subleads' => count($service_ids),
                                'no_of_closed_subleads' => 0
                            );
                            $update = $this->mcommon->common_edit('leads', $update_array, array('id' => $lead_id));
                            $lead_package_name = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_package_name');

                            $insert_lead_id = $lead_id;
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);


                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type3"];
                            for ($i = 0; $i < count($service_ids); $i++) {
                                $card_amount = 0;
                                // if ($payment_type != "cash") {
                                //     $card_amount = ($govt_fees[$i] * (0 / 100));
                                //     $sub_totals[$i] = $govt_fees[$i] + $typing_fees[$i] + $card_amount;
                                // }
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                } else if ($payment_type == "online") {
                                    $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                } else {
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
                                        "card_amount" => $card_amount,  // $packages->card_amount
                                        "sub_total" => $sub_totals[$i],
                                        "payment_type" => $payment_type,
                                        "created_by" => $this->auth_user_id,
                                        "is_direct_invoice" => $is_direct_invoice[$i],
                                        "msd_key" => $msd_key[$i],
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i]
                                    );
                                    $this->mcommon->common_insert('lead_package_details', $package_detail);

                                    $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

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
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i],
                                        "lead_package_name" => $lead_package_name,
                                        'created_group_id' => $created_group_id,
                                        "card_amount" => $card_amount,
                                    );
                                    if ($payment_type != "cash") {
                                        $insert_lead_array["card_amount"] = $card_amount;
                                        // $insert_lead_array["card_amount"] = $packages->card_amount;
                                        // $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
                                    }
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                                    $postData = array(
                                        'lead_id' => $insert_lead_id,
                                    );
        
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Accept: application/json',
                                        'Content-Type: application/json'
                                    ]);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                    curl_setopt($ch, CURLOPT_HEADER, false); 
                                    curl_setopt($ch, CURLOPT_POST, true);  
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                    $response = curl_exec($ch);
                                    if(!empty($response))
                                        $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                            $postData = array(
                                'lead_id' => $insert_lead_id,
                            );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Accept: application/json',
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                            curl_setopt($ch, CURLOPT_HEADER, false); 
                            curl_setopt($ch, CURLOPT_POST, true);  
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                            $response = curl_exec($ch);
                            if(!empty($response))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
                $payment_type = $this->input->post('payment_type3');
                // $payment_type = $this->input->post('payment_type');

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
                $lead_user_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('user_id' => $lead_user_id), 'first_name');
                // $customer_name = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
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

                    $update_sla_flag_array = ['sla_violation_status' => 'red',];
                    $this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $lead_id]);

                    $payment_message = "<p></p><a href='" . $payment_link . "'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $message .= $payment_message;

                    if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '') {
                        //SEND EMAIL TO CUSTOMER

                        // This email is for the Eligible & Paynow email
                        $lead_remarks = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "remarks");
                        $remarks_data = html_entity_decode($lead_remarks);
                        $result = explode(',', $remarks_data);
                        $package_name = str_replace('Package:', '', $result[1]);
                        $gc_message = array(
                            'package_name' => $package_name,
                            'customer_name' => $lead_name,
                            'payment_url' => $payment_link,
                            'amount' => $amount_payment,
                        );

                        $gc_email_array = array(
                            'email' => $customer_email,
                            'subject' => 'Goldencube - Application is Eligible',
                            'template' => 'mails/gc_application_eligible',
                            'from_name' => "Golden Cube",
                            'message' => $gc_message,
                        );
                        $send_mail = send_template_email($gc_email_array);

                        // $customer_email = "muthuvenkatesh808@gmail.com";
                        /*$email_array = array(
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
                        log_message('error', $send_mail);   */

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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                        $pos_cust_key = $res_json->Data->Cust_Key;
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

                    // Sub lead completion email process
                    $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'remarks');
                    $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'id');

                    if ($service_name != "" && $service_name != null) {
                        $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                        $sub_lead_message .= "<br>Dear Ishti<br>";
                        $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong> DLD fees </strong> transaction for the lead listed below <br>";
                        $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                        $sub_lead_message .= "<br><br>Lead Description:<br>";
                        $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                        $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                        $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                        $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                        $sub_lead_message .= "<br>Receipt Number: <strong>" . $raw_salesorder . "</strong>";
                        $sub_lead_message .= "<br>Remarks: " . $service_name;

                        $sub_lead_message .= "<br><br><br><br>Dear Reem<br><br>";
                        $sub_lead_message .= "<br>Please proceed with completing the <strong> DLD Certificate </strong> transaction for the customer once the lead is completed<br><br>";

                        $sublead_cc_usermail = [];
                        // After Payment Completion for DLD Fees
                        array_push($sublead_cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);    // 2879029976
                        array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                        //  array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

                        $email_array = array(
                            'email' => $sublead_cc_usermail,
                            'subject' => $sub_lead_subject,
                            'template' => 'mails/template',
                            'from_name' => "CRM ALERT",
                            'message' => $sub_lead_message,
                        );

                        $send_mail = send_template_email($email_array);

                        $action_by = 178140614;        // info@ontimegroup.com
                        $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Reem and Ishti";
                        $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        log_message('error', $send_mail);
                    }

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Cash to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));

                    $update_sla_flag_array = ['sla_violation_status' => 'red',];
                    $this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $lead_id]);

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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                        $pos_cust_key = $res_json->Data->Cust_Key;
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

                    // Sub lead completion email process
                    $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'remarks');
                    $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'id');

                    if ($service_name != "" && $service_name != null) {
                        $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                        $sub_lead_message .= "<br>Dear Ishti<br>";
                        $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong> DLD fees </strong> transaction for the lead listed below <br>";
                        $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                        $sub_lead_message .= "<br><br>Lead Description:<br>";
                        $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                        $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                        $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                        $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                        $sub_lead_message .= "<br>Receipt Number: <strong>" . $raw_salesorder . "</strong>";
                        $sub_lead_message .= "<br>Remarks: " . $service_name;

                        $sub_lead_message .= "<br><br><br><br>Dear Reem<br><br>";
                        $sub_lead_message .= "<br>Please proceed with completing the <strong> DLD Certificate </strong> transaction for the customer once the lead is completed<br><br>";

                        $sublead_cc_usermail = [];
                        // After Payment Completion for DLD Fees
                        array_push($sublead_cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);    // 2879029976
                        array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                        //  array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

                        $email_array = array(
                            'email' => $sublead_cc_usermail,
                            'subject' => $sub_lead_subject,
                            'template' => 'mails/template',
                            'from_name' => "CRM ALERT",
                            'message' => $sub_lead_message,
                        );

                        $send_mail = send_template_email($email_array);

                        $action_by = 178140614;        // info@ontimegroup.com
                        $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Reem and Ishti";
                        $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        log_message('error', $send_mail);
                    }

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Card to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder, 
                    "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));

                    $update_sla_flag_array = ['sla_violation_status' => 'red',];
                    $this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $lead_id]);

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
                log_message('error', 'create lead - General Pckage=' . $branch_id);
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
                $cust_id = ($this->input->post('cust_id')) ? $this->input->post('cust_id') : null;

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, $cust_id);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'created_group_id' => $created_group_id,
                                        'pos_cust_key' => $cust_id
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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                                    }
                                }
                            } else {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                    'created_group_id' => $created_group_id,
                                    'pos_cust_key' => $cust_id
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

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
                                'no_of_closed_subleads' => 0,
                                'created_group_id' => $created_group_id,
                                'pos_cust_key' => $cust_id
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);

                            $postData = array(
                                'lead_id' => $insert_lead_id,
                            );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Accept: application/json',
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                            curl_setopt($ch, CURLOPT_HEADER, false); 
                            curl_setopt($ch, CURLOPT_POST, true);  
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                            $response = curl_exec($ch);
                            if(!empty($response))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

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
                                } else if ($payment_type == "online") {
                                    $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                } else {
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
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i],
                                        'pos_cust_key' => $cust_id
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

                                                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $insert_lead_id));	

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
                                                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                                                    }
                                                } else {
                                                    $this->session->set_flashdata('alert', 'danger');
                                                    $this->session->set_flashdata('alert_message', 'Unable to setup meeting at this moment. Please contact support.');
                                                }
                                            }
                                        }

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
                $email_subject = "OnTime CRM - Followup regarding - Payment";
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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = array();
                    if ($branch_id != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    // echo "<pre>";
                    // print_r($details);
                    // echo "</pre>";
                    // exit();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime CRM - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime CRM",
                        'from_email' => "info@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = array();
                    if ($branch_id != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime CRM - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime CRM",
                        'from_email' => "info@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
            $this->db->where("(lead_packages.package_branch not in (106,109))");
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
                'page_title' => 'General Package - New Sale',
                'title' => 'General Package - New Sale',
                'content' => $this->load->view('leads/lead/general_pack_sale_new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function general_pack_sale_new_draft()
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
                //echo "<pre>";
                //print_r($_POST);
                //die();
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
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, null);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'created_group_id' => $created_group_id,
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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                                    }
                                }
                            } else {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                    'created_group_id' => $created_group_id,
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

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
                                'no_of_closed_subleads' => 0,
                                'created_group_id' => $created_group_id,
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);

                            $postData = array(
                                'lead_id' => $insert_lead_id,
                            );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Accept: application/json',
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                            curl_setopt($ch, CURLOPT_HEADER, false); 
                            curl_setopt($ch, CURLOPT_POST, true);  
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                            $response = curl_exec($ch);
                            if(!empty($response))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

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

                                                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $insert_lead_id));	

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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
                $email_subject = "OnTime CRM - Followup regarding - Payment";
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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];

                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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

                    $details = array();
                    if ($branch_id != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    // echo "<pre>";
                    // print_r($details);
                    // echo "</pre>";
                    // exit();

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTimeGroup - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTimeGroup",
                        'from_email' => "crm@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

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
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTimeGroup - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTimeGroup",
                        'from_email' => "crm@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
            $this->db->where("(lead_packages.package_branch not in (106,109))");
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
                'page_title' => 'General Package - New Sale',
                'title' => 'General Package - New Sale',
                'content' => $this->load->view('leads/lead/general_pack_sale_new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function lab_new_enbd()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";
                log_message('error', 'create lead - OnTime Lab');

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
                $cust_id = ($this->input->post('cust_id')) ? $this->input->post('cust_id') : null;

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, $cust_id);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'created_group_id' => $created_group_id,
                                        'pos_cust_key' => $cust_id
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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                                    }
                                }
                            } else {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                    'created_group_id' => $created_group_id,
                                    'pos_cust_key' => $cust_id
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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
                            $msd_key = $_POST["msd_key"];
                            $is_pos_typing_fee = $_POST["is_pos_typing_fee"];
                            $is_direct_invoice = $_POST["is_direct_invoice"];

                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

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
                                'no_of_closed_subleads' => 0,
                                'created_group_id' => $created_group_id,
                                'pos_cust_key' => $cust_id
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);

                            $postData = array(
                                'lead_id' => $insert_lead_id,
                            );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Accept: application/json',
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                            curl_setopt($ch, CURLOPT_HEADER, false); 
                            curl_setopt($ch, CURLOPT_POST, true);  
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                            $response = curl_exec($ch);
                            if(!empty($response))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {
                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                } else if ($payment_type == "online") {
                                    $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                } else {
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
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i],
                                        'pos_cust_key' => $cust_id

                                    );
                                    //if ($payment_type != "cash") {
                                    //   $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
                $email_subject = "OnTime CRM - Followup regarding - Payment";
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
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        redirect('leads/lead/view/' . $lead_id);
                    }
                    redirect('leads/lead/view/' . $lead_id);
                } else if ($payment_type == "cash") {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 417, 'action_amount' => $service_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $service_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

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
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime CRM - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime CRM",
                        'from_email' => "info@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 308, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    // redirect('leads/lead/view/' . $lead_id);
                } else {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 418, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311, 'approval_code' => $approval_code);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

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
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime CRM - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime CRM",
                        'from_email' => "info@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    // redirect('leads/lead/view/' . $lead_id);
                }

                $lead_det = $this->leads_model->lead_details($lead_id);

                $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you !";
                $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:";

                $message .= "<br>Customer Name: " . $lead_det["customer_name"];
                $message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                $message .= "<br>Customer Email: " . $lead_det["customer_email"];
                $message .= "<br>Service:  " . $lead_det["category_code"] . " - " . $lead_det["service_name"];
                $message .= "<br>Amount:  " . $req["Payment"]["ActAmt"];
                $message .= "<br>Payment Type: " . $payment_type;
                $message .= "<br>Remarks: " . $lead_det["remarks"];
                $message .= "<br>Receipt No:  " . $raw_salesorder;

                $email_array = array(
                    'email' => $receiver_email,
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM ALERT",
                    'message' => $message,
                );
                // print_r("");
                $send_mail = send_template_email($email_array);
                log_message('error', $send_mail);

                redirect(
                    'leads/lead/view/' . $lead_id
                );
            }

            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            //$view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1, "package_branch" => array(109,111)));
            $view_data['packages'] = $this->db->select("*")->from('lead_packages')->where("is_active", 1)->where_in("package_branch", array(109, 111))->get()->result_array();
            $data = array(
                'page_title' => 'OnTime Group - New Sale',
                'title' => 'OnTime Group - New Sale',
                'content' => $this->load->view('leads/lead/ontimelab_new_enbd', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }
    public function lab_new()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                // echo "<pre>";
                // print_r($_POST);
                // echo "<pre>";
                // exit();
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";
                log_message('error', 'create lead - OnTime Lab');

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
                $cust_id = ($this->input->post('cust_id')) ? $this->input->post('cust_id') : null;

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, $cust_id);
                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'created_group_id' => $created_group_id,
                                        'pos_cust_key' => $cust_id
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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                                    }
                                }
                            } else {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                    'created_group_id' => $created_group_id,
                                    'pos_cust_key' => $cust_id
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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
                            $msd_key = $_POST["msd_key"];
                            $is_pos_typing_fee = $_POST["is_pos_typing_fee"];
                            $is_direct_invoice = $_POST["is_direct_invoice"];

                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

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
                                'no_of_closed_subleads' => 0,
                                'created_group_id' => $created_group_id,
                                'pos_cust_key' => $cust_id
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $parent_lead_id = $insert_lead_id;
                            $normal_lead_count = 1;

                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);

                            $postData = array(
                                'lead_id' => $insert_lead_id,
                            );

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                'Accept: application/json',
                                'Content-Type: application/json'
                            ]);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                            curl_setopt($ch, CURLOPT_HEADER, false); 
                            curl_setopt($ch, CURLOPT_POST, true);  
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                            $response = curl_exec($ch);
                            if(!empty($response))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

                            // $card_amounts = $_POST["card_amount"];
                            $payment_type = $_POST["payment_type"];
                            for ($i = 0; $i < count($service_ids); $i++) {
                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i] * (1 / 100));
                                } else if ($payment_type == "online") {
                                    $card_amount = ($govt_fees[$i] * (2.25 / 100));
                                } else {
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
                                        "is_pos_typing_fee" => $is_pos_typing_fee[$i],

                                    );
                                    //if ($payment_type != "cash") {
                                    //   $insert_lead_array["card_amount"] = $govt_fees[$i] * (1 / 100);
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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
                $email_subject = "OnTime CRM - Followup regarding - Payment";
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
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        redirect('leads/lead/view/' . $lead_id);
                    }
                    redirect('leads/lead/view/' . $lead_id);
                } else if ($payment_type == "cash") {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 417, 'action_amount' => $service_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $service_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $service_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $service_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

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
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime CRM - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime CRM",
                        'from_email' => "info@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 308, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    // redirect('leads/lead/view/' . $lead_id);
                } else {
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    $log_insert_array = array('action_id' => 418, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311, 'approval_code' => $approval_code);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = $payment_type;
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

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
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime CRM - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime CRM",
                        'from_email' => "info@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    // redirect('leads/lead/view/' . $lead_id);
                }

                $lead_det = $this->leads_model->lead_details($lead_id);

                $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you !";
                $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:";

                $message .= "<br>Customer Name: " . $lead_det["customer_name"];
                $message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                $message .= "<br>Customer Email: " . $lead_det["customer_email"];
                $message .= "<br>Service:  " . $lead_det["category_code"] . " - " . $lead_det["service_name"];
                $message .= "<br>Amount:  " . $req["Payment"]["ActAmt"];
                $message .= "<br>Payment Type: " . $payment_type;
                $message .= "<br>Remarks: " . $lead_det["remarks"];
                $message .= "<br>Receipt No:  " . $raw_salesorder;

                $email_array = array(
                    'email' => $receiver_email,
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM ALERT",
                    'message' => $message,
                );
                // print_r("");
                $send_mail = send_template_email($email_array);
                log_message('error', $send_mail);

                redirect(
                    'leads/lead/view/' . $lead_id
                );
            }

            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            //$view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1, "package_branch" => array(109,111)));
            $view_data['packages'] = $this->db->select("*")->from('lead_packages')->where("is_active", 1)->where_in("package_branch", array(109, 111))->get()->result_array();
            $data = array(
                'page_title' => 'OnTime Group - New Sale',
                'title' => 'OnTime Group - New Sale',
                'content' => $this->load->view('leads/lead/ontimelab_new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }


    public function biznew($value = '')
    {
        //echo "bizinsert_lead2=connected<br>";

        //echo "<pre>";
        //print_r($value);
        if ($this->verify_min_level(1)) {
            // echo "bizinsert_lead2=verify_min_level<br>";
            if (isset($_POST['submit']) or isset($value)) {
                //   echo "bizinsert_lead2=_POST_submit<br>";
                log_message('error', 'create lead');
                $branch_id = 107;
                $lead_type = isset($value) ? $value['new_lead_type'] : $this->input->post('lead_type');
                $category_id = 109;
                $service_id = 1009;

                $lead_name =  isset($value) ? $value['new_lead_name'] : $this->input->post('lead_name');
                $lead_contact = isset($value) ? $value['new_lead_contact'] : $this->input->post('lead_contact');
                $lead_email = isset($value) ? $value['new_lead_email'] : $this->input->post('lead_email');
                $lead_remarks = isset($value) ? $value['new_lead_remarks'] : $this->input->post('lead_remarks');
                $lead_country_code = isset($value) ? $value['new_lead_country_code'] : $this->input->post('lead_country_code');
                $group_crm_lead_id = isset($value) ? $value['group_crm_lead_id'] : '';
                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $this->db->trans_start();
                    try {
                        $random_email_name = strtolower($this->random_strings(10));
                        $random_email = $random_email_name . '@ontimecustomer.com';
                        $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                        $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, null);
                        if ($user_id != 0) {
                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $service_id,
                                'group_crm_lead_id' => $group_crm_lead_id,
                                'lead_created_by' => $this->auth_user_id,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => 0,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                            );
                            $insert_lead_id = $this->mcommon->common_insert('biz_leads', $insert_lead_array);
                            // print_r($insert_lead_id);
                            // print_r($this->db->error());
                            // exit();
                            $curl = curl_init();
                            $req = "lead_name=" . $lead_name . "&lead_email=" . $lead_email . "&lead_contact=" . $lead_contact . "&lead_remarks=" . $lead_remarks . "&group_crm_lead_id=" . $group_crm_lead_id;
                            curl_setopt_array(
                                $curl,
                                array(
                                    CURLOPT_URL => "https://crm.ontimebiz.com/api/v1/lead/biz",
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "POST",
                                    CURLOPT_POSTFIELDS => $req,
                                    CURLOPT_HTTPHEADER => array(
                                        "cache-control: no-cache",
                                        "content-type: application/x-www-form-urlencoded",
                                    ),
                                )
                            );

                            $response = curl_exec($curl);
                            log_message('debug', $req . "<br/>" . $response);
                            $err = curl_error($curl);

                            curl_close($curl);

                            $res = json_decode($response);

                            //  echo "bizinsert_lead2=success<br>";

                            $this->mcommon->common_edit('biz_leads', array('is_assigned' => 1, 'biz_lead_id' => $res->lead_id, 'lead_status' => 602), array('id' => $insert_lead_id));

                            $this->db->trans_commit();
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'Lead has been created.');
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                        }
                    } catch (Exception $e) {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                        $this->db->trans_rollback();
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }
                $this->db->trans_commit();
            }
            $view_data['nationality'] = $this->mcommon->specific_fields_records_all('nationalities', array('active' => 1));

            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            $view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1));
            $data = array(
                'page_title' => 'New Business Setup Lead',
                'title' => 'New Business Setup Lead',
                'content' => $this->load->view('leads/lead/biz_new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function attestationnew()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit'])) {
                log_message('error', 'create lead');

                $branch_id = 103;
                $lead_type = "normal";
                if ($lead_type == 'normal') {
                    $category_id = 125;
                    $service = $this->input->post("service");
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service, "bot_id" => $_POST["bot_id"]));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }
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
                $lead_source_type = $this->input->post("lead_type");
                $discount_percentage = $this->input->post("lead_emp_discount_per");
                $employee_id = $this->input->post("lead_emp_id");

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($lead_source_type == "cross sales") {
                    $this->form_validation->set_rules('lead_cross_sale_pmt', 'Cross Sales PMT Number', 'required');
                }

                if ($lead_source_type == "emp") {
                    $this->form_validation->set_rules('lead_emp_id', 'Employee ID', 'required');
                }

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, null);

                    // $update_user = $this->db->update)
                    $update_user = $this->mcommon->common_edit('lead_users', [
                        "customer_type" => $this->input->post("customer_type"),
                        "customer_address" => $this->input->post("customer_address"),
                        "alt_mobile" => $this->input->post("alt_mobile"),
                        "alt_email" => $this->input->post("alt_email"),
                        "trn_no" => $this->input->post("trn_no"),
                        "trade_no" => $this->input->post("trade_no"),
                    ], array('user_id' => $user_id));

                    if ($user_id != 0) {
                        //process lead type
                        if ($lead_type == 'normal') {
                            $normal_lead_count = 0;
                            //get the workflow for the service.
                            $workflows = $this->leads_model->get_workflow_entries($service_id);

                            if (!empty($workflows)) {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                        'lead_type' => $lead_source_type,
                                        'created_group_id' => $created_group_id
                                    );

                                    if ($lead_source_type == "cross sales") {
                                        $insert_lead_array["lead_cross_sale_pmt"] = $this->input->post("lead_cross_sale_pmt");
                                    }

                                    if ($lead_source_type == "emp") {
                                        $insert_lead_array["lead_emp_id"] = $this->input->post("lead_emp_id");
                                    }

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

                                        $postData = array(
                                            'lead_id' => $insert_lead_id,
                                        );
            
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                        curl_setopt($ch, CURLOPT_HEADER, false); 
                                        curl_setopt($ch, CURLOPT_POST, true);  
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                        $response = curl_exec($ch);
                                        if(!empty($response))
                                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
                                    }
                                }
                            } else {
                                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
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
                                    'lead_type' => $lead_source_type,
                                    'created_group_id' => $created_group_id,
                                );
                                // echo "<pre>";
                                // print_r($insert_lead_array);
                                // echo "</pre>";
                                // exit();
                                if ($lead_source_type == "cross sales") {
                                    $insert_lead_array["lead_cross_sale_pmt"] = $this->input->post("lead_cross_sale_pmt");
                                }

                                if ($lead_source_type == "emp") {
                                    $insert_lead_array["lead_emp_id"] = $this->input->post("lead_emp_id");
                                    $insert_lead_array["lead_emp_discount_per"] = $this->input->post("lead_emp_discount_per");
                                }

                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                $normal_lead_count = 1;

                                $postData = array(
                                    'lead_id' => $insert_lead_id,
                                );
    
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                    'Accept: application/json',
                                    'Content-Type: application/json'
                                ]);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                                curl_setopt($ch, CURLOPT_HEADER, false); 
                                curl_setopt($ch, CURLOPT_POST, true);  
                                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                                $response = curl_exec($ch);
                                if(!empty($response))
                                    $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));
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
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }
                $_POST["assign_group"] = 77;
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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
                            // $receiver_email = "muthuvenkatesh808@gmail.com";
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

                            $groups = get_groups($this->auth_user_id);

                            if (in_array(77, $groups)) {
                                return redirect("leads/lead/view/" . $insert_lead_id);
                            }

                            redirect('leads/lead/attestationnew');
                        } else {
                            $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                            // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                            // $this->response('Unable to assign the lead at present. Please try again later', 500);
                        }
                    }
                }
            }

            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/getBotButtons/41',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    // CURLOPT_POSTFIELDS => '{"CcKey": 755}',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                )
            );

            $response = curl_exec($curl);
            if ($response === false) {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('alert_message', 'Exception Occured on Get Services. #');
                // echo 'Curl error: ' . curl_error($curl);
                redirect("/leads/lead/attestationnew");
            }
            curl_close($curl);
            $json_data = json_decode($response);

            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $json_data;
            $data = array(
                'page_title' => 'New Attestation Lead',
                'title' => 'New Attestation Lead',
                'content' => $this->load->view('leads/lead/attestation_new', $view_data, true),
            );
            $this->load->view('template/base_template_modal_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function bizlist()
    {

        $view_data = [];
        if ($this->verify_min_level(1)) {
            $leads = json_decode($this->leads_model->get_biz_leads());
            $view_data['biz_leads'] = $leads;
            // print_r($leads);
            // exit();
            $data = [
                'page_title' => 'Business Setup Leads',
                'title' => 'Business Setup Leads',
                'content' => $this->load->view('leads/lead/biz_list', $view_data, true),
            ];
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function translator()
    {
        if ($this->verify_min_level(1)) {
            // print_r($this->auth_email);
            // exit();
            if (isset($_POST['submit'])) {
                try {
                    // echo "Wait Man";
                    // echo "<pre>";
                    // print_r($_POST);
                    $data = [
                        "customer_name" => $_POST["customer_name"],
                        "customer_mobile" => $_POST["lead_country_code"] . $_POST["lead_contact"],
                        "customer_email" => $_POST["customer_email"],
                        "pos_reference" => $_POST["pos_reference"],
                        "invoice_amount" => $_POST["invoice_amount"],
                        "remarks" => $_POST["remarks"],
                    ];
                    $cust_id = ($this->input->post('cust_id')) ? $this->input->post('cust_id') : null;
                    $trans_id = $this->mcommon->common_insert('translation_leads', $data);
                    $user_id = $this->customer_handle($data["customer_name"], $_POST["lead_contact"], $_POST["customer_email"], $_POST["lead_country_code"], $cust_id);
                    $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');

                    // echo $trans_id." <==> ".$user_id;
                    // exit();
                    $insert_lead_array = array(
                        'customer_id' => $user_id,
                        'branch_id' => 102,
                        'category_id' => 107,
                        'service_id' => 1003,
                        'lead_created_by' => $this->auth_user_id,
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 303,
                        'package_id' => 0,
                        'order_receipt' => 0,
                        'remarks' => "Translation Amount ID: " . $trans_id . "<br>" . $_POST["remarks"],
                        'lead_parent_id' => 0,
                        'is_assigned' => 0,
                        'created_group_id' => $created_group_id,
                        'pos_cust_key' => $cust_id
                    );
                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                    $this->mcommon->common_edit('translation_leads', array('lead_id' => $insert_lead_id), array('trans_lead_id' => $trans_id));

                    $postData = array(
                        'lead_id' => $insert_lead_id,
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://crm.ontimegroup.com/api/v1/Order/add_request'); 
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Accept: application/json',
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                    curl_setopt($ch, CURLOPT_HEADER, false); 
                    curl_setopt($ch, CURLOPT_POST, true);  
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));  
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
                    $response = curl_exec($ch);
                    if(!empty($response))
                        $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $insert_lead_id));

                    $lead_id = $insert_lead_id;
                    $insert_array = array(
                        'lead_id' => $insert_lead_id,
                        'assigned_by' => $this->auth_user_id,
                        'assigned_to' => $this->auth_user_id,
                        'assigned_on' => date('Y-m-d H:i:s'),
                    );
                    $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $this->auth_user_id, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $insert_lead_id));	

                    $log_insert_array = array('action_id' => 403, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been self assigned', 'action_by' => $this->auth_user_id, 'status_id' => 303);
                    // print_r($log_insert_array);
                    $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                    $this->mcommon->common_edit('leads', array('lead_status' => 303, "is_assigned" => 1), array('id' => $insert_lead_id));

                    // echo "</pre>";
                    // return false;
                    log_message('Log', 'Create Translator Invoice Payment lead created agains the Order #' . $trans_id);
                    // $req = $_POST;

                    // return false;

                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Translation Invoice Amount Record created #' . $trans_id . ", LID #" . $insert_lead_id);

                    // print_r($req);
                    // return;
                } catch (Exception $e) {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Something went wrong.Exception Occured');
                }

                try {
                    $from_email = $this->auth_email;
                    $customer_email = $_POST["customer_email"];
                    $agent_email = $this->auth_email;
                    $email_subject = "ONTIME ##LD" . $insert_lead_id . "## - Followup regarding Translation - Translation Services";
                    $email_message = $_POST["remarks"];

                    // $email_remarks = $this->input->post('email_remarks');
                    // $contactable_date = $this->input->post('contactable_date');

                    $amount_payment = $_POST["invoice_amount"];
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

                    //add entry in lead action log

                    $current_timestamp = date('Y-m-d H:i:s');

                    $action_message = $message . "<p></p><div class='payment'><a href='#'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p></div><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $log_insert_array = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307);

                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $this->db->insert_id();
                    $log_id = encrypt_decrypt($action_id);
                    $payment_link = "https://crm.ontimegroup.com/payment/ccpay?ref=" . $token . "&token=" . $crypt_amount . "&identity=" . $log_id;

                    $this->mcommon->common_edit('lead_action_log', ["payment_link" => $payment_link], array('id' => $action_id));

                    $payment_message = "<p></p><a href='" . $payment_link . "'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $message .= $payment_message;

                    if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '') {

                        //SEND EMAIL TO CUSTOMER
                        $email_array = array(
                            'email' => $customer_email,
                            'subject' => $email_subject,
                            'template' => 'mails/translation_template',
                            'from_name' => $this->auth_first_name,
                            'from_email' => $this->auth_email,
                            'message' => $message,
                            'reply_to' => $this->auth_email,
                            // 'cc' => [["email"=>$this->auth_email]]
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Payment Detail Email sent successfully. You will receive the copy of email.');
                        // echo $send_mail . "<br>";
                        // print_r($token);
                        // exit();
                        if ($send_mail) {

                            // $email_array = array(
                            //     'email' => $_SESSION["email"],
                            //     'subject' => 'OnTime Digital - Payment Link',
                            //     'template' => 'mails/template',
                            //     'from_name' => 'OnTime Digital',
                            //     'message' => $msg,
                            // );
                            // $send_mail = send_template_email($email_array);

                            if ($insert_log > 0) {
                                //update lead status and contactable date in lead table
                                $update_lead_array = array('lead_status' => 307, 'contactable_date' => date("Y-m-d"));
                                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', 'Payment Detail Email sent successfully. You will receive the copy of email.');
                                // echo "Hellow";
                                // exit();
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Email sent successfully. But unable to update the lead record. Contact support.');
                            }
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                        }

                        redirect('leads/lead/view/' . $insert_lead_id);
                    }
                } catch (Exception $e) {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Exception Occured on Payment Email Send. #' . $e);
                }
            }
            $view_data = [];
            $data = array(
                'page_title' => 'New Translation Invoice Payment',
                'title' => 'New Translation Invoice Payment',
                'content' => $this->load->view('leads/lead/translator_new', $view_data, true),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function new_child()
    {

        echo "<pre>";
        print_r($_POST);
        // die();

        if ($this->verify_min_level(1)) {

            if (isset($_POST['submit'])) {
                log_message('error', 'create lead');

                $branch_id = $this->input->post('branch_id');
                $lead_type = $this->input->post('lead_type');
                if ($lead_type == 'normal') {
                    $category_id = $this->input->post('category_id') ? $this->input->post('category_id') : 109;
                    $service_id = $this->input->post('service_id') ? $this->input->post('service_id') : 1009;

                    //  $this->form_validation->set_rules('category_id', 'Category', 'required');
                    //  $this->form_validation->set_rules('service_id', 'Service', 'required');
                }

                if ($lead_type == 'package') {
                    $package_id = $this->input->post('package_id');
                    $package_service_id = $this->input->post('package_service_id');
                    $this->form_validation->set_rules('package_id', 'Package', 'required');
                }

                $lead_name = $this->input->post('lead_name');
                $lead_contact = $this->input->post('lead_contact');
                $lead_email = $this->input->post('lead_email');
                $lead_remarks = $this->input->post('lead_remarks');
                $lead_country_code = $this->input->post('lead_country_code');
                $lead_parent_id = $this->input->post('lead_parent_id');

                $this->form_validation->set_rules('lead_name', 'Customer Name', 'required');
                $this->form_validation->set_rules('lead_contact', 'Contact Number', 'required');
                $this->form_validation->set_rules('lead_country_code', 'Country code cannot be null', 'required');
                $this->form_validation->set_rules('lead_remarks', 'Remarks', 'required');

                if ($this->form_validation->run() == true) {
                    $random_email_name = strtolower($this->random_strings(10));
                    $random_email = $random_email_name . '@ontimecustomer.com';
                    $lead_email = ($lead_email == '') ? $random_email : $lead_email;
                    $user_id = $this->customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, null);
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
                                        'lead_parent_id' => $lead_parent_id,
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
                                    'lead_parent_id' => $lead_parent_id,
                                    'is_assigned' => 0,
                                );
                                // print_r($insert_lead_array);
                                // exit();
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
                                $this->session->set_flashdata('alert', 'success');
                                $this->session->set_flashdata('alert_message', $normal_lead_count . ' lead(s) has been created.');
                            } else {
                                $this->session->set_flashdata('alert', 'danger');
                                $this->session->set_flashdata('alert_message', 'Unable to create leads at this moment.Please try again.');
                            }
                        }

                        if ($lead_type == 'package') {
                            $package_lead_count = 0;
                            $packages = $this->leads_model->get_package_entries($package_id);
                            foreach ($packages as $key => $value) {
                                $insert_lead_array = array(
                                    'customer_id' => $user_id,
                                    'branch_id' => $branch_id,
                                    'category_id' => $value['category_id'],
                                    'service_id' => $value['service_id'],

                                    'msd_key' => $value['msd_key'],
                                    'is_direct_invoice' => $value['is_direct_invoice'],
                                    'govt_fee' => $value['govt_fee'],
                                    'typing_fee' => $value['typing_fee'],
                                    'is_pos_typing_fee' => $value['is_pos_typing_fee'],
                                    'card_amount' => $value['card_amount'],

                                    'lead_created_by' => $this->auth_user_id,
                                    'lead_added_on' => date('Y-m-d H:i:s'),
                                    'contactable_date' => date('Y-m-d H:i:s'),
                                    'lead_status' => 301,
                                    'package_id' => $package_id,
                                    'order_receipt' => 0,
                                    'remarks' => $lead_remarks,
                                    'lead_parent_id' => $lead_parent_id,
                                    'is_assigned' => 0,
                                );

                                // echo "<pre>";
                                // print_r($insert_lead_array);
                                // die();
                                // print_r($value['service_id'].'=='.$package_service_id);
                                // die();
                                if ($value['service_id'] == $package_service_id) {
                                    //   print_r($value['service_id'].'=='.$package_service_id);
                                    //  die();

                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                                    if ($insert_lead_id > 0) {
                                        //get branch name
                                        $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                        //create action log
                                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $this->auth_first_name . '</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $this->auth_user_id, 'status_id' => 301);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                        $package_lead_count++;
                                    }
                                } else {
                                    $this->session->set_flashdata('alert_message', 'Unable to create lead. There exist a problem in creating customer record. Please try again.');
                                }
                            }

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

                if (isset($_POST["assign_to"])) {
                    $lead_id = $insert_lead_id;
                    $assigned_to = $_POST["assign_to"];
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

                    $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
					$update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

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
            }

            // if(!isset($_GET["lead_parent_id"])){
            //     $this->session->set_flashdata('alert', 'danger');
            //     $this->session->set_flashdata('alert_message', 'Parent Lead Id is missing.');
            //     redirect($_SERVER['HTTP_REFERER']);
            // }
            // else {
            // $lead_parent = $this->db->from("leads")->where("id",$_GET["lead_parent_id"])->get()->first_row();
            // $lead_customer = $this->db->from("lead_users")->where("user_id",$lead_parent->customer_id)->get()->first_row();
            $this->session->set_flashdata('alert', 'success');
            $this->session->set_flashdata('alert_message', 'Sub Lead Successfully Created');

            redirect($_SERVER['HTTP_REFERER']);

            // }
            // print_r($lead_customer);
            // exit();

            //     $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            //     $view_data['services'] = $this->leads_model->lead_services();
            //     $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            //     $view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1));
            //     $view_data['lead_parent_id'] = $_GET["lead_parent_id"];
            //     // $view_data["lead_customer"] = $lead_customer;
            //     $data = array(
            //         'page_title' => 'New Lead - Child',
            //         'title' => 'New Lead - Child',
            //         'content' => $this->load->view('leads/lead/new_child', $view_data, TRUE),
            //     );
            //     $this->load->view('template/base_template', $data);
            // } else {
            //     redirect('login');
            // }
        }
    }

    public function view($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['update_customer_info'])) {
                $customer_name = $this->input->post('customer_name');
                $customer_email = $this->input->post('customer_email');
                $customer_country_code = $this->input->post('customer_country_code');
                $customer_mobile = $this->input->post('customer_mobile');
                $lead_id = $this->input->post('lead_id');

                if ($customer_name != '' || $customer_email != '' || $customer_country_code !== '' || $customer_mobile != '') {
                    //check customer email exist
                    $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $customer_email));

                    if ($check_email_exists != 0) {
                        $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'user_id');
                        //if user_id is present update the lead record
                        $update1 = $this->mcommon->common_edit('leads', array('customer_id' => $user_id), array('id' => $lead_id));
                    }

                    //check customer mobile exist
                    $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $customer_mobile));
                    if ($check_mobile_exists != 0) {
                        $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $customer_mobile), 'user_id');
                        //if user_id is present update the lead record
                        $update2 = $this->mcommon->common_edit('leads', array('customer_id' => $user_id), array('id' => $lead_id));
                    }

                    //fetch the fresh user_id from leads
                    $user_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');

                    $update_customer_record_array = array('first_name' => $customer_name, 'email' => $customer_email, 'country_code' => $customer_country_code, 'mobile' => $customer_mobile);

                    // $update_record = $this->mcommon->common_edit('lead_users',$update_customer_record_array,array('user_id'=>$user_id));

                    // $this->db->set($update_customer_record_array);
                    $this->db->where("user_id", $user_id);

                    $update_record = $this->db->update("lead_users", $update_customer_record_array);
                    // echo "Result: ";
                    // print_r($update_record);
                    // exit();
                    if ($update_record) {
                        $log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => "Updated the customer information", 'action_by' => $this->auth_user_id, 'status_id' => 304);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $this->session->set_flashdata('alert_cu', 'success');
                        $this->session->set_flashdata('alert_message_cu', 'Customer information has been updated.');
                    } else {
                        $this->session->set_flashdata('alert_cu', 'danger');
                        $this->session->set_flashdata('alert_message_cu', 'Unable to update the Customer details at this moment.Please try again.');
                    }
                } else {
                    $this->session->set_flashdata('alert_cu', 'danger');
                    $this->session->set_flashdata('alert_message_cu', 'All the fields are required.');
                }
            }

            $view_data['timeline'] = $this->leads_model->lead_timeline($lead_id);
            $view_data['lead_details'] = $this->leads_model->lead_details($lead_id);
            // print_r($view_data['lead_details']);
            // exit();
            //    echo "<pre>"; print_r($view_data["timeline"]);
            //     exit();
            if ($view_data['lead_details']["category_id"] == 125 || $view_data['lead_details']["service_id"] == 103) {
                $curl = curl_init();

                curl_setopt_array(
                    $curl,
                    array(
                        CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/getBotButtons/41',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        // CURLOPT_POSTFIELDS => '{"CcKey": 755}',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                        ),
                    )
                );

                $response = curl_exec($curl);
                if ($response === false) {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Exception Occured on Get Services. #');
                    // echo 'Curl error: ' . curl_error($curl);
                    // redirect("/leads/lead/attestationnew");
                }
                curl_close($curl);
                $json_data = json_decode($response);
                $view_data['attestation_services'] = $json_data;
            }

            $created_by_user_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $view_data['lead_by_details'] = $this->mcommon->specific_row('users', array('user_id' => $created_by_user_id));
            $view_data['followup_actions'] = $this->db->select("*")->from("lead_actions")->where_not_in("id", array(401, 402, 403, 409, 410, 411))->where("is_active", 1)->order_by("action_name", "desc")->get()->result_array();
            // $view_data['followup_actions'] = $this->mcommon->specific_fields_records_all('lead_actions',array('id >'=>403,'id <'=>409,'id ='));
            $view_data['complete_actions'] = $this->mcommon->specific_fields_records_all('lead_actions', array('id >' => 409, 'id <=' => 411));
            $view_data['sms_templates'] = $this->mcommon->specific_fields_records_all('lead_message_templates', array('user_id' => $this->auth_user_id, 'is_active' => 1, 'template_type' => 2));
            $view_data['email_templates'] = $this->mcommon->specific_fields_records_all('lead_message_templates', array('user_id' => $this->auth_user_id, 'is_active' => 1, 'template_type' => 1));
            $view_data['upcoming_meetings'] = $this->mcommon->specific_fields_records_all('lead_meetings', array('lead_id' => $lead_id, 'crm_user_id' => $this->auth_user_id, 'is_complete' => 0));
            $view_data['lead_attachments'] = $this->mcommon->specific_fields_records_all('lead_attachments', array('lead_id' => $lead_id));

            $lead_parent = $this->db->from("leads")->where("id", $lead_id)->get()->first_row();
            $lead_customer = $this->db->from("lead_users")->where("user_id", $lead_parent->customer_id)->get()->first_row();
            //  print_r($lead_customer);
            //  exit();
            $view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $view_data['services'] = $this->leads_model->lead_services();
            $view_data['categories'] = $this->mcommon->specific_fields_records_all('ontime_categories', array('is_active' => 1));
            $view_data['packages'] = $this->mcommon->specific_fields_records_all('lead_packages', array('is_active' => 1));
            $view_data["lead_customer"] = $lead_customer;

            $view_data["package_details"] = $this->mcommon->specific_row('lead_packages', array("package_category_id" => $view_data['lead_details']["category_id"]));
            // print_r($view_data["package_details"]);
            // exit();
            $query = "select leads.*,ontime_categories.category_name as category,ontime_category_services_.service_name as service,concat(users.first_name,' ',users.last_name,'<br>',users.email) as reassigned_to,leads_assigned.assigned_to as assigned_user_id from leads inner join ontime_categories on ontime_categories.id = leads.category_id inner join ontime_category_services_ on ontime_category_services_.service_id = leads.service_id left join leads_assigned on leads_assigned.lead_id = leads.id left join users on users.user_id=leads_assigned.assigned_to where leads.lead_parent_id = " . $lead_id;
            // echo $query;
            $sub_leads = $this->db->query($query)->result();
            $sub_leads_convert_invoice_count = 0;
            if (count($sub_leads) > 0) {
                $sub_leads_with_att = [];
                foreach ($sub_leads as $key => $value) {
                    if ($value->msd_key != 69 && $value->lead_status == 305) {
                        $sub_leads_convert_invoice_count = (int)$sub_leads_convert_invoice_count + 1;
                    }

                    $view_data['sub_leads'] = $sub_leads;
                }
            }
            $view_data['sub_leads_convert_invoice_count'] = $sub_leads_convert_invoice_count;

            $view_data["lead_dld_status"] = $this->mcommon->specific_fields_records_all("lead_status", ["is_dld_status" => 1]);
            $view_data['auth_user_privilege'] = $this->mcommon->specific_row('m_roles_privilege', array('role_id' => $this->auth_user_role));
            $view_data['auth_user_data'] = $this->authentication_model->get_user_data($this->auth_user_id);

            $view_data['lead_assigned_by_details'] = $this->mcommon->specific_row('users', array('user_id' => $view_data['lead_details']["assigned_by_user"]));
            $view_data['lead_assigned_to_details'] = $this->mcommon->specific_row('users', array('user_id' => $view_data['lead_details']["assigned_to_user"]));

            $email_request_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'email_request_id');
            if(!empty($email_request_id)){
                $email_request_id = (int) $email_request_id;
                $get_convo = $this->request_conversation($email_request_id);
                $view_data['conversations'] = $get_convo;
                $view_data['request_id'] = $email_request_id;
            }

            // print_r($view_data['sub_leads']);
            // exit();
            // print_r($lead_customer);
            // echo "<pre>";
            // print_r($view_data['attestation_services']);
            // echo "</pre>";
            // exit();
            // echo "<pre>"; print_r($view_data["sub_leads"]);
            //  exit();

            $data = array(
                'page_title' => 'View Lead',
                'title' => 'View Lead',
                'content' => $this->load->view('leads/lead/view_v2', $view_data, true),
            );
            $this->load->view('template/base_template_modal_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function attestconvert($lead_id)
    {
        if ($this->verify_min_level(1)) {
            $lead = $this->leads_model->lead_details($lead_id);
            // 419
            if ($lead["category_id"] == 125) {
                // 109
                $this->mcommon->common_edit('leads', array('category_id' => 109), array('id' => $lead_id));
                $remark = "Lead Converted to Normal Lead";
                $log_insert_array = array('action_id' => 419, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $remark, 'action_by' => $this->auth_user_id, 'status_id' => $lead["lead_status"]);

                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                $this->session->set_flashdata('alert', 'success');
                $this->session->set_flashdata('alert_message', 'Lead Successfully converted to Normal Lead');
                return redirect("/leads/lead/view/" . $lead_id);;
            } else {
                $this->mcommon->common_edit('leads', array('category_id' => 125), array('id' => $lead_id));
                $remark = "Lead Converted to Attestation Lead";
                $log_insert_array = array('action_id' => 419, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $remark, 'action_by' => $this->auth_user_id, 'status_id' => $lead["lead_status"]);

                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                $this->session->set_flashdata('alert', 'success');
                $this->session->set_flashdata('alert_message', 'Lead Successfully converted to Attestation Lead');
                return redirect("/leads/lead/view/" . $lead_id);;
            }
        } else {
            redirect('login');
        }
    }


    public function preview($lead_id)
    {
        if ($this->verify_min_level(1)) {
            $view_data['timeline'] = $this->leads_model->lead_timeline($lead_id);
            $view_data['lead_details'] = $this->leads_model->lead_preview($lead_id);
            $created_by_user_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $view_data['lead_by_details'] = $this->mcommon->specific_row('users', array('user_id' => $created_by_user_id));
            //$view_data['branches'] = $this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));
            $this->db->select('groups.group_id as gid, ontime_branches.branch_name,ontime_branches.branch_code, ontime_branches.id, users_group_accessables.ug_user_id');
            $this->db->from('groups');
            $this->db->join('users_group_accessables', 'groups.group_id = users_group_accessables.ug_group_id');
            $this->db->join('ontime_branches', 'ontime_branches.id = groups.group_branch_id');
            $this->db->where('users_group_accessables.ug_user_id', $this->auth_user_id);
            $this->db->group_by('ontime_branches.branch_name');

            $query = $this->db->get();
            $view_data['branches'] = $query->result_array(); //$this->mcommon->specific_fields_records_all('ontime_branches', array('is_active' => 1));

            $view_data['lead_attachments'] = $this->mcommon->specific_fields_records_all('lead_attachments', array('lead_id' => $lead_id));
            echo $this->load->view('leads/lead/preview', $view_data, true);
            // $data = array(
            //     'page_title' => 'Preview Lead',
            //     'title' => 'Preview Lead',
            //     'content' => $this->load->view('leads/lead/preview', $view_data, TRUE),
            // );
            // $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }

    // leads/lead/statusUpdate
    public function statusUpdate($lead_id)
    {
        if ($this->verify_min_level(1)) {
            $view_data['lead_details'] = $this->leads_model->lead_preview($lead_id);
            $view_data["lead_dld_status"] = $this->mcommon->specific_fields_records_all("lead_status", ["is_dld_status" => 1]);

            echo $this->load->view('leads/lead/lead_status_change', $view_data, true);
            // $data = array(
            //     'page_title' => 'Preview Lead',
            //     'title' => 'Preview Lead',
            //     'content' => $this->load->view('leads/lead/preview', $view_data, TRUE),
            // );
            // $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }

    public function get_services()
    {
        $category_id = $this->input->get('category_id');
        $services = $this->leads_model->lead_services($category_id);
        echo json_encode($services);
    }

    public function customer_handle($lead_name, $lead_contact, $lead_email, $lead_country_code, $cust_id = null)
    {
        $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name));
        // print_r($is_exist);
        // exit();
        if ($is_exist != 0) 
        {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name), 'user_id');
            $this->mcommon->common_edit('lead_users', array('pos_cust_id' => $cust_id), array('user_id' => $user_id));
            return $user_id;
        }

        if ($is_exist == 0) {
            $password = 'Welcome@123';
            $confirm_password = 'Welcome@123';
            $auth_level = '1';
            $referal_code = $this->random_strings(10);
            $user_hashed_password = $this->authentication->hash_passwd($password);
            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $lead_contact,
                'referal_code' => $referal_code,
                'first_name' => $lead_name,
                'passwd' => $user_hashed_password,
                'email' => $lead_email,
                'confirm_password' => $user_hashed_password,
                'pos_cust_id' => $cust_id
            ];
            // print_r($user_data);
            // exit();
            $user_data['user_id'] = $this->leads_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');
            $user_data['otp'] = rand(1000, 9000);
            $user_data['email_otp'] = rand(1000, 9000);
            $user_data['banned'] = '0';
            $user_data['role_id'] = '4';
            $user_data['country'] = 'United Arab Emirates';
            $user_data['country_code'] = $lead_country_code;

            $insert = $this->mcommon->common_insert("lead_users", $user_data);

            $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name), 'user_id');

            return $user_id;
        }
    }

    public function random_strings($length_of_string)
    {
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }

    /**
     * [Function for getting email template content by supplying template id]
     * @return [json] [email template content]
     */
    public function get_template()
    {
        $template_id = $this->input->get('template_id');
        $template_content = $this->mcommon->specific_row_value('lead_message_templates', array('id' => $template_id), 'template_content');
        echo $template_content;
    }

    /**
     * [action_email Lead followup through email]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [sends an email using SMTP and updates the action log]
     */
    public function action_email($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_email'])) {
                $from_email = $this->input->post('from_email');
                $customer_email = $this->input->post('customer_email');
                $agent_email = $this->input->post('agent_email');
                $email_subject = $this->input->post('email_subject');
                $email_message = $this->input->post('email_message');
                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                //construct message
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
                $agent_name = $this->mcommon->specific_row_value('users', array('email' => $agent_email), 'first_name');
                $message = "Dear " . $customer_name . ",<br /><br />";
                $message .= $email_message;
                $message .= "Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                // print_r($_POST);
                if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '') {
                    //SEND EMAIL TO CUSTOMER

                    $email_array = array(
                        'email' => $customer_email,
                        'subject' => $email_subject,
                        'template' => 'mails/lead_template',
                        'from_name' => $this->auth_first_name,
                        'from_email' => $this->auth_email,
                        'message' => $message,
                        'reply_to' => $this->auth_email,
                        'cc' => [["email" => $this->auth_email]],
                    );
                    //print_r($email_array);
                    $send_mail = send_lead_template_email($email_array);
                    // exit();

                    log_message('error', $send_mail);
                    if ($send_mail) {
                        //add entry in lead action log
                        $log_insert_array = array('action_id' => 404, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Sent an email to ' . $customer_email . '. <br /><br /><strong>Remarks:</strong>' . $email_remarks . '<br /><br />Email Message as follow as<br /><br /><pre>' . $message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 304);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        if ($insert_log > 0) {
                            //update lead status and contactable date in lead table
                            $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'Email sent successfully. You will receive the copy of email.');
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Email sent successfully. But unable to update the lead record. Contact support.');
                        }
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                    }

                    redirect('leads/lead/view/' . $lead_id);
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                }
            }
        } else {
            redirect('login');
        }
    }

    public function action_payment($lead_id)
    {
        if ($this->verify_min_level(1)) {

            if (isset($_POST['action_payment'])) {
                // echo "Hi";
                // // echo "<pre>";
                // // echo "</pre>";
                // exit();
                $from_email = $this->input->post('from_email');
                $customer_email = $this->input->post('customer_email');
                $agent_email = $this->input->post('agent_email');
                $email_subject = $this->input->post('email_subject');
                $email_message = $this->input->post('email_message');
                $bot_id = $this->input->post('bot_id');
                if (!isset($bot_id)) {
                    $bot_id = 0;
                }

                $service_name = $this->input->post('at_service_name');

                if ($service_name != null) {
                    // print_r($service_name);
                    // print_r($bot_id);
                    // exit();
                    $service = $service_name;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => 125, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => 125, "service_name" => $service, "bot_id" => $_POST["bot_id"]));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }
                    $this->mcommon->common_edit('leads', ["service_id" => $service_id], array('id' => $lead_id));
                }

                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                $amount_payment = $this->input->post('amount_payment');
                $crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
                // echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
                // exit();
                //construct message
                $get_customer_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('user_id' => $get_customer_id), 'first_name');

                $customer_name1 = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
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

                //add entry in lead action log

                $current_timestamp = date('Y-m-d H:i:s');

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

                if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '' && $contactable_date != '') {
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
                        // 'cc' => [["email"=>$this->auth_email]]
                    );
                    $send_mail = send_lead_template_email($email_array);
                    log_message('error', $send_mail);

                    if ($send_mail) {

                        // $email_array = array(
                        //     'email' => $_SESSION["email"],
                        //     'subject' => 'OnTime Digital - Payment Link',
                        //     'template' => 'mails/template',
                        //     'from_name' => 'OnTime Digital',
                        //     'message' => $msg,
                        // );
                        // $send_mail = send_template_email($email_array);

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
            }
        } else {
            redirect('login');
        }
    }


    public function action_addpayment($lead_id)
    {
        if ($this->verify_min_level(1)) {

            if (isset($_POST['action_payment'])) {
                // echo "Hi";
                //  echo "<pre>";
                //  echo "</pre>";
                // exit();
                $from_email = $this->input->post('from_email');
                $customer_email = $this->input->post('customer_email');
                $agent_email = $this->input->post('agent_email');
                $email_subject = $this->input->post('email_subject');
                $email_message = $this->input->post('email_message');
                $payment_type = $this->input->post('payment_type');
                $bot_id = $this->input->post('bot_id');
                if (!isset($bot_id)) {
                    $bot_id = 0;
                }

                $service_name = $this->input->post('at_service_name');
                $sub_lead_id = $this->input->post('sub_lead_id');
                $main_service_type = $this->input->post('main_service_type');
                if($main_service_type !="23"){
                    $main_service = NULL;
                } else{
                    $main_service = $main_service_type;
                }

                $ad_gov_fee = $this->input->post('ad_gov_fee');
                $ad_typing_fees = $this->input->post('ad_typing_fees');
                $ad_vendor_com = $this->input->post('ad_vendor_com');
                $ad_online_charge = $this->input->post('ad_online_charge');
                $ad_tax = $this->input->post('ad_tax');

                $this->mcommon->common_edit("leads", array("main_service_type"=>$main_service, "additional_govt_fee" => $this->input->post('amount_payment'), "ad_gov_fee" => $ad_gov_fee, "ad_typing_fees" => $ad_typing_fees, "ad_vendor_com" => $ad_vendor_com, "ad_online_charge" => $ad_online_charge, "ad_tax" => $ad_tax), array("id" => $sub_lead_id));
                if ($service_name != null) {
                    // print_r($service_name);
                    // print_r($bot_id);
                    // exit();
                    $service = $service_name;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => 125, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => 125, "service_name" => $service, "bot_id" => $_POST["bot_id"]));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }
                    $this->mcommon->common_edit('leads', ["service_id" => $service_id], array('id' => $lead_id));
                }
                $this->mcommon->common_edit('leads', ["main_service_type" => $main_service], array('id' => $lead_id));

                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                $amount_payment = $this->input->post('amount_payment');
                $crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
                // echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
                // exit();
                //construct message
                $get_customer_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('user_id' => $get_customer_id), 'first_name');

                $customer_name1 = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
                $agent_name = $this->mcommon->specific_row_value('users', array('email' => $agent_email), 'first_name');
                $message = "Dear " . $customer_name . ",<br /><br />";
                $message .= $email_message;

                $pre_token = $lead_id . "-" . $customer_email . "-@OnTimeCRM11..";
                // echo $pre_token;
                // echo "<br><br>";
                // exit();

                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";
                $emp = $this->db->select("*")->from("users")->where("user_id", $this->auth_user_id)->get()->first_row();
                $employee = $emp->first_name . " " . $emp->last_name . "(" . $emp->employee_id . ")";

                $token1 = md5($pre_token);
                $token2 = md5(strrev($pre_token));
                $token = $token1 . "-" . $token2;

                $msg = $amount_payment;
                $key = $token2;
                // print_r($token);
                // exit();

                //add entry in lead action log

                $current_timestamp = date('Y-m-d H:i:s');

                if ($payment_type == "online") {
                    $action_message1 = "Additional Government fees : " . $ad_gov_fee . "<br />Additional typing Fees :" . $ad_typing_fees . "<br />Additional Vendor Commission : " . $ad_vendor_com . "<br />Online charges : " . $ad_online_charge . "<br />VAT : " . $ad_tax;
                    $log_insert_array1 = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message1 . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307, "bot_id" => $bot_id);
                    // print_r($log_insert_array);
                    $insert_log1 = $this->mcommon->common_insert('lead_action_log', $log_insert_array1);

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

                    if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '' && $contactable_date != '') {
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
                            // 'cc' => [["email"=>$this->auth_email]]
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);

                        if ($send_mail) {

                            // $email_array = array(
                            //     'email' => $_SESSION["email"],
                            //     'subject' => 'OnTime Digital - Payment Link',
                            //     'template' => 'mails/template',
                            //     'from_name' => 'OnTime Digital',
                            //     'message' => $msg,
                            // );
                            // $send_mail = send_template_email($email_array);

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
                    }
                } else if ($payment_type == "cash") {
                    $lead_det = $this->leads_model->lead_details($lead_id);
                    $getsub_service_id = $this->mcommon->specific_row_value('leads', array('id' => $sub_lead_id), 'main_service_type');

                    $log_insert_array = array('action_id' => 417, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $action_message1 = "Additional Government fees : " . $ad_gov_fee . "<br /> Additional typing Fees :" . $ad_typing_fees . "<br /> Additional Vendor Commission : " . $ad_vendor_com . "<br />Online charges : " . $ad_online_charge . "<br />VAT : " . $ad_tax;
                    $log_insert_array1 = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message1 . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307, "bot_id" => $bot_id);
                    // print_r($log_insert_array);
                    $insert_log1 = $this->mcommon->common_insert('lead_action_log', $log_insert_array1);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];
                    $req["MainServiceId"] = $getsub_service_id;

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user,
                            "MainServiceId"=>$getsub_service_id
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                            "MainServiceId"=>$getsub_service_id
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = [];

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime Group - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime Group",
                        'from_email' => "crm@ontimegroup.com",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                        "amount" => $amount_payment,
                        "payment_type" => "CASH",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Cash to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    $this->db->where('id', $lead_id);
                    $this->db->set('additional_govt_fee', 'additional_govt_fee+' . $amount_payment, FALSE);
                    $this->db->update('leads');
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

                    $log_insert_array = array('action_id' => 418, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Card to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $action_message1 = "Additional Government fees : " . $ad_gov_fee . "<br /> Additional typing Fees :" . $ad_typing_fees . "<br /> Additional Vendor Commission : " . $ad_vendor_com . "<br />Online charges : " . $ad_online_charge . "<br />VAT : " . $ad_tax;
                    $log_insert_array1 = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message1 . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307, "bot_id" => $bot_id);
                    // print_r($log_insert_array);
                    $insert_log1 = $this->mcommon->common_insert('lead_action_log', $log_insert_array1);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CARD";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                    $getsub_service_id = $this->mcommon->specific_row_value('leads', array('id' => $sub_lead_id), 'main_service_type');
                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user,
                            "MainServiceId"=>$getsub_service_id
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                            "MainServiceId"=>$getsub_service_id
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = [];
                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the payment receipt " . $so_order . "</b>";
                    }
                    $OrderID = $action_id . "-OTLDPMET" . $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime Group - Payment Receipt - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime Group",
                        'from_email' => "team@ontimegroup.com",
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    $this->db->where('id', $lead_id);
                    $this->db->set('additional_govt_fee', 'additional_govt_fee+' . $amount_payment, FALSE);
                    $this->db->update('leads');

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Payment Receipt Created.');
                    redirect('leads/lead/view/' . $lead_id);
                }
                redirect(
                    'leads/lead/view/' . $lead_id
                );
            } else {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('alert_message', 'Unable to send email at this moment.Please try again.');
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }


    public function action_goldebcube_payment($lead_id)
    {
        if ($this->verify_min_level(1)) {

            if (isset($_POST['action_payment'])) {
                // echo "Hi";
                // // echo "<pre>";
                // // echo "</pre>";
                // exit();
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";

                $from_email = $this->input->post('from_email');
                $customer_email = $this->input->post('customer_email');
                $agent_email = $this->input->post('agent_email');
                $email_subject = $this->input->post('email_subject');
                $email_message = $this->input->post('email_message');
                $bot_id = $this->input->post('bot_id');
                $payment_type = $this->input->post('payment_type');
                if (!isset($bot_id)) {
                    $bot_id = 0;
                }

                $service_name = $this->input->post('at_service_name');
                $approval_code = $this->input->post('approval_code');

                if ($service_name != null) {
                    // print_r($service_name);
                    // print_r($bot_id);
                    // exit();
                    $service = $service_name;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => 125, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => 125, "service_name" => $service, "bot_id" => $_POST["bot_id"]));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }
                    $this->mcommon->common_edit('leads', ["service_id" => $service_id], array('id' => $lead_id));
                }

                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                $amount_payment = $this->input->post('amount_payment');
                $crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
                // echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
                // exit();
                //construct message
                $get_customer_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('user_id' => $get_customer_id), 'first_name');

                $customer_name1 = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
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

                    if ($from_email != '' && $customer_email != '' && $agent_email != '' && $email_subject != '' && $email_message != '' && $contactable_date != '') {
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
                            // 'cc' => [["email"=>$this->auth_email]]
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);

                        if ($send_mail) {

                            // $email_array = array(
                            //     'email' => $_SESSION["email"],
                            //     'subject' => 'OnTime Digital - Payment Link',
                            //     'template' => 'mails/template',
                            //     'from_name' => 'OnTime Digital',
                            //     'message' => $msg,
                            // );
                            // $send_mail = send_template_email($email_array);

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

                    $log_insert_array = array('action_id' => 417, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Payment ' . $amount_payment . ' Paid by Cash to ' . $employee, 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CASH";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = [];
                    if ($lead_det["package_id"] != 0) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
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
                        "payment_type" => "CASH",
                        "details" => $details,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Payment Receipt <b>#' . $raw_salesorder . '</b> Created for Pay by Cash to ' . $employee], ["id" => $action_id]);

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["Payment_Type"] = "CARD";
                    $req["OrderRef"] = $action_id . '-OTLDPMET' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $amount_payment);

                    // $action_id
                    $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $amount_payment, 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }


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
                    $details = [];
                    if ($lead_det["package_id"] != 0) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }
                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $pos_cust_key = $res_json->Data->Cust_Key;
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

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 311, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
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
        } else {
            redirect('login');
        }
    }

    public function action_attest_payment($lead_id)
    {
        if ($this->verify_min_level(1)) {

            if (isset($_POST['action_payment'])) {

                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $this->auth_user_id), 'employee_id');
                if ($user_pos == 0 || $user_pos == NULL)
                    $user_pos = "crmonline";

                $from_email = $this->input->post('from_email');
                $customer_email = $this->input->post('customer_email');
                $agent_email = $this->input->post('agent_email');
                $email_subject = $this->input->post('email_subject');
                $email_message = $this->input->post('email_message');
                $bot_id = $this->input->post('bot_id');
                if (!isset($bot_id)) {
                    $bot_id = 0;
                }

                $service_name = $this->input->post('at_service_name');
                $payment_type = $this->input->post('payment_type');
                if ($service_name != null) {
                    // print_r($service_name);
                    // print_r($bot_id);
                    // exit();
                    $service = $service_name;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => 125, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => 125, "service_name" => $service, "bot_id" => $_POST["bot_id"]));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }
                    $this->mcommon->common_edit('leads', ["service_id" => $service_id], array('id' => $lead_id));
                }

                $email_remarks = $this->input->post('email_remarks');
                $contactable_date = $this->input->post('contactable_date');

                $amount_payment = $this->input->post('amount_payment');
                $crypt_amount = encrypt_decrypt($amount_payment, 'encrypt');
                // echo "<br>Your Decrypted password is = ". $this->encrypt_decrypt($pwd, 'decrypt');
                // exit();
                //construct message
                $get_customer_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');
                $customer_name = $this->mcommon->specific_row_value('lead_users', array('user_id' => $get_customer_id), 'first_name');

                $customer_name1 = $this->mcommon->specific_row_value('lead_users', array('email' => $customer_email), 'first_name');
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

                //add entry in lead action log

                $current_timestamp = date('Y-m-d H:i:s');
                if ($payment_type == "online") {

                    $action_message = $message . "<p></p><div class='payment'><a href='#'><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+48 hours')) . " to prevent from expire.</p></div><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";

                    $log_insert_array = array('action_id' => 412, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 307);

                    // print_r($log_insert_array);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $this->db->insert_id();

                    $service_count = count($_POST["attest_service"]);
                    for ($i = 0; $i < $service_count; $i++) {
                        $serv_name = $_POST["attest_service"][$i];
                        $botid = $_POST["bot_id"][$i];
                        $service_amount = $_POST["service_amount"][$i];
                        $tp_fee = $_POST["tp_fee"][$i];
                        $typing_fee = $_POST["typing_fee"][$i];
                        $discount = $_POST["discount"][$i];

                        $payment_detail["lead_id"] = $lead_id;
                        $payment_detail["lead_action_log_id"] = $action_id;
                        $payment_detail["bot_id"] = $botid;
                        $payment_detail["service_name"] = $serv_name;
                        $payment_detail["service_amount"] = $service_amount;
                        $payment_detail["thirdparty_fee"] = $tp_fee;
                        $payment_detail["typing_fee"] = $typing_fee;

                        if (isset($discount)) {
                            $payment_detail["discount"] = $discount;
                        } else {
                            $payment_detail["discount"] = 0;
                        }

                        // print_r($payment_detail);
                        // exit();
                        $insert_log = $this->mcommon->common_insert('lead_payment_details', $payment_detail);
                    }

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
                            // 'cc' => [["email"=>$this->auth_email]]
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);

                        if ($send_mail) {

                            // $email_array = array(
                            //     'email' => $_SESSION["email"],
                            //     'subject' => 'OnTime Digital - Payment Link',
                            //     'template' => 'mails/template',
                            //     'from_name' => 'OnTime Digital',
                            //     'message' => $msg,
                            // );
                            // $send_mail = send_template_email($email_array);

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
                } else {
                    // echo $payment_type;
                    // exit();
                    $lead_det = $this->leads_model->lead_details($lead_id);
                    // $curl = curl_init();
                    // // http://185.56.89.101:82
                    // // http://192.168.61.52:81
                    // $curl_url = 'http://185.56.89.101:82/POSInvoice/PushPayment?custmobile=' . $lead_det["customer_mobile"] . '&custname=' . rawurlencode($lead_det["customer_name"]) . '&email=' . $lead_det["customer_email"] . '&amount=' . $Amount . '&orderref=' . $payment_insert_id . '-' . $OrderID . '&servicedesc=' . rawurlencode($lead_det["service_name"]) . '&createso=true&botid=' . $lead_det["bot_id"];
                    // curl_setopt_array($curl, array(
                    //     CURLOPT_URL => $curl_url,
                    //     CURLOPT_RETURNTRANSFER => true,
                    //     CURLOPT_ENCODING => '',
                    //     CURLOPT_MAXREDIRS => 10,
                    //     CURLOPT_TIMEOUT => 0,
                    //     CURLOPT_HTTPHEADER => array('Content-Length: 0'),
                    //     CURLOPT_FOLLOWLOCATION => true,
                    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     CURLOPT_CUSTOMREQUEST => 'POST',
                    // ));
                    $lead_action_vid = 311;
                    if ($payment_type == "BANKTRNSFR" || $payment_type == "CHEQUE") {
                        $lead_action_vid = 312;
                    }

                    $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => 'Sales Order Created for Pay by Cash', 'action_by' => $this->auth_user_id, 'status_id' => $lead_action_vid);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $action_id = $insert_log;

                    $service_count = count($_POST["attest_service"]);
                    for ($i = 0; $i < $service_count; $i++) {
                        $serv_name = $_POST["attest_service"][$i];
                        $botid = $_POST["bot_id"][$i];
                        $service_amount = $_POST["service_amount"][$i];
                        $tp_fee = $_POST["tp_fee"][$i];
                        $typing_fee = $_POST["typing_fee"][$i];
                        $discount = $_POST["discount"][$i];

                        $payment_detail["lead_id"] = $lead_id;
                        $payment_detail["lead_action_log_id"] = $action_id;
                        $payment_detail["bot_id"] = $botid;
                        $payment_detail["service_name"] = $serv_name;
                        $payment_detail["service_amount"] = $service_amount;
                        $payment_detail["thirdparty_fee"] = $tp_fee;
                        $payment_detail["typing_fee"] = $typing_fee;

                        if (isset($discount)) {
                            $payment_detail["discount"] = $discount;
                        } else {
                            $payment_detail["discount"] = 0;
                        }

                        // print_r($payment_detail);
                        // exit();
                        $insert_log = $this->mcommon->common_insert('lead_payment_details', $payment_detail);
                    }

                    // echo $action_id;
                    // exit();
                    $OrderID = $lead_id;
                    $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["OrderRef"] = 'SO' . $action_id . '-' . $OrderID;
                    $req["Payment"] = array("ActAmt" => "0");
                    if ($payment_type != "cross") {
                        $req["Payment_Type"] = $payment_type;
                    }

                    // $action_id
                    $logs = $this->db->select("*")->from("lead_payment_details")->where("lead_action_log_id", $action_id)->get()->result_array();

                    $so_bots = [];
                    $services = "";
                    foreach ($logs as $log) {
                        $services .= $log["service_name"] . ",";
                        array_push($so_bots, ["Id" => $log["bot_id"], "DiscAmt" => $log["discount"], "AddTypingFee" => $log["typing_fee"], "SubLeadId" => $lead_id]);
                    }

                    $req["ServDescription"] = $services;
                    $req["salesorderdtl"] = $so_bots;

                    if ($lead_det["lead_type"] == "cross sales") {
                        $req["Is_CrossSales"] = 1;
                        $req["PMTNumber"] = $lead_det["lead_cross_sale_pmt"];
                    }
                    $req["User"] = ["User_ID" => $user_pos];

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => "0", 
                            // "OnlinePaymentRef" => $transaction_number,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => "0", 
                            // "OnlinePaymentRef" => $transaction_number,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user,
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

                    $curl = curl_init();

                    curl_setopt_array(
                        $curl,
                        array(
                            // https://ontimesmartpos.net
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
                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));

                    $res_json = json_decode($raw_response);
                    if (isset($res_json->Data->SLO_Headnum)) {
                        $so_order = $res_json->Data->SLO_Headnum;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $raw_salesorder = $so_order;
                        $so_order = "under the salesorder " . $so_order . "</b>";
                    }
                    $OrderID = $lead_id;
                    $email_array = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"],
                        'mobile' => $lead_det["customer_mobile"],
                        'subject' => 'OnTime Group - Sales Order - #' . $raw_salesorder,
                        'template' => "emails/payment_done",
                        'from_name' => "OnTime Group",
                        'from_email' => "crm@ontimegroup.com",
                        'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                        // 'message' => $html,
                        "reference" => $OrderID,
                        "so_order" => $raw_salesorder,
                        "service" => $services,
                        "amount" => $amount_payment,
                        "payment_type" => $payment_type,
                        "branch_id" => $lead_det["branch_id"]
                    );
                    $send_mail = send_lead_template_email($email_array);

                    if ($lead_det["lead_type"] == "cross sales") {
                        $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Sales Order <b>#' . $raw_salesorder . '</b> Created for Cross Sale Receipt of #' . $lead_det["lead_cross_sale_pmt"], "pos_pmt_response" => $response], ["id" => $action_id]);
                    } else {
                        $insert_log = $this->mcommon->common_edit('lead_action_log', ["remarks" => 'Sales Order <b>#' . $raw_salesorder . '</b> Created for Pay by ' . $payment_type, "pos_pmt_response" => $response], ["id" => $action_id]);
                    }

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => $lead_action_vid, "pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    // exit();

                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();

                    // $log_insert_array = array('action_id' => 416, 'action_amount' => $amount_payment, 'lead_id' => $lead_id, 'action_on' => $current_timestamp, 'remarks' => '<pre>' . $action_message . '</pre>', 'action_by' => $this->auth_user_id, 'status_id' => 311);
                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Email sent successfully and Sales Order Created.');
                    redirect('leads/lead/view/' . $lead_id);
                }
                redirect('leads/lead/view/' . $lead_id);
            }
            redirect('leads/lead/view/' . $lead_id);
        } else {
            redirect('login');
        }
    }

    /**
     * [action_call Lead followup through call]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [Update the action log by the remarks of logged in user]
     */
    public function action_call($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_call'])) {

                $call_remarks = $this->input->post('call_remarks');
                $contactable_date = $this->input->post('contactable_date');
                if ($call_remarks != '' && $contactable_date != '') {
                    $log_insert_array = array('action_id' => 405, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $call_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        //update lead status and contactable date in lead table
                        $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    /**
     * [action_sms Lead followup through sms]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [Send sms to customer]
     */
    public function action_sms($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_sms'])) {
                $mobile_number = $this->input->post('mobile_number');
                $message_body = $this->input->post('message_body');
                $sms_remarks = $this->input->post('sms_remarks');
                $contactable_date = $this->input->post('contactable_date');
                if ($mobile_number != '' && $contactable_date != '' && $message_body != '') {
                    $mobile_number = str_replace("+", "", $mobile_number);
                    $sms_gateway_data = sendsms($mobile_number, $message_body);
                    $result_array = json_decode($sms_gateway_data);

                    if (isset($result_array->jobId)) {
                        $log_insert_array = array('action_id' => 406, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Remarks:<br />' . $sms_remarks . '<br />SMS Content:<br />' . $message_body, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        if ($insert_log > 0) {
                            //update lead status and contactable date in lead table
                            $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'SMS sent successfully. You can see the progress in timeline.');
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                        }
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to send SMS at this moment. Please contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    /**
     * [action_meeting setup meeting with the customer]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [setup meeting in lead_meetings table]
     */
    public function action_meeting($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_meeting'])) {
                $meeting_remarks = $this->input->post('meeting_remarks');
                $contactable_date = $this->input->post('contactable_date');
                if ($meeting_remarks != '' && $contactable_date != '') {
                    //create meeting

                    $crm_user_id = $this->auth_user_id;
                    $customer_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');
                    $meeting_date_time = $contactable_date;
                    $remarks = $meeting_remarks;
                    $is_complete = 0;
                    $created_at = date('Y-m-d H:i:s');
                    $last_updated = date('Y-m-d H:i:s');

                    $meeting_insert_array = array(
                        'lead_id' => $lead_id,
                        'crm_user_id' => $crm_user_id,
                        'customer_id' => $customer_id,
                        'meeting_date_time' => $meeting_date_time,
                        'remarks' => $remarks,
                        'is_complete' => $is_complete,
                        'created_at' => $created_at,
                        'last_updated' => $last_updated,
                    );
                    $meeting_insert = $this->mcommon->common_insert('lead_meetings', $meeting_insert_array);
                    if ($meeting_insert > 0) {
                        //TODO: schedule an email before 15 mins of the meeting.

                        $log_insert_array = array('action_id' => 407, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $meeting_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        if ($insert_log > 0) {
                            //update lead status and contactable date in lead table
                            $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
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
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    /**
     * [action_meeting Logged in user can update about the lead for future reference]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [updated in timeline of the lead]
     */
    public function action_custom($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_custom'])) {

                $custom_remarks = $this->input->post('custom_remarks');
                $contactable_date = $this->input->post('contactable_date');
                if ($custom_remarks != '' && $contactable_date != '') {
                    $log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $custom_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        //update lead status and contactable date in lead table
                        $update_lead_array = array('lead_status' => 304, 'contactable_date' => $contactable_date);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    public function action_status($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_status'])) {

                $status_id = $this->input->post('status_id');
                // print_r($status_id);
                // exit();
                if ($status_id) {
                    $status_name = $this->mcommon->specific_row_value("lead_status", ["id" => $status_id], "status_name");
                    $name = $this->mcommon->specific_row_value("users", ["user_id" => $this->auth_user_id], "first_name");

                    $custom_remarks = "<b>" . $status_name . "</b> Status updated by " . $name . ".";

                    $log_insert_array = array('action_id' => 421, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $custom_remarks, 'action_by' => $this->auth_user_id, 'status_id' => $status_id);

                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        //update lead status and contactable date in lead table
                        $update_lead_array = array('lead_status' => $status_id);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    public function action_dld_status($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_status'])) {

                $status_id = $this->input->post('status_id');
                // print_r($status_id);
                // exit();
                if ($status_id) {
                    $status_name = $this->mcommon->specific_row_value("lead_status", ["id" => $status_id], "status_name");
                    $name = $this->mcommon->specific_row_value("users", ["user_id" => $this->auth_user_id], "first_name");

                    $custom_remarks = "<b>" . $status_name . "</b> Status updated by " . $name . ".";

                    $log_insert_array = array('action_id' => 421, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $custom_remarks, 'action_by' => $this->auth_user_id, 'status_id' => $status_id);

                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        //update lead status and contactable date in lead table
                        $update_lead_array = array('lead_status' => $status_id);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/manage');
            }
        } else {
            redirect('login');
        }
    }


    public function action_eligible($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_custom'])) {

                $eligible = $this->input->post('is_eligible');
                $remark = $this->input->post('custom_remarks');
                if ($eligible != '' && $remark != '') {
                    if ($eligible == 0) {
                        $lead_remarks = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "remarks");
                        $lead_customer_id = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "customer_id");
                        $lead_customer_name = $this->mcommon->specific_row_value("lead_users", ["user_id" => $lead_customer_id], "first_name");
                        $lead_email = $this->mcommon->specific_row_value("lead_users", ["user_id" => $lead_customer_id], "email");
                        $remarks_data = html_entity_decode($lead_remarks);
                        $result = explode(',', $remarks_data);
                        $package_name = str_replace('Package:', '', $result[1]);
                        $gc_message = array(
                            'package_name' => $package_name,
                            'customer_name' => $lead_customer_name,
                        );
                        $email_array = array(
                            'email' => $lead_email,
                            'subject' => 'Goldencube - Application is Ineligible',
                            'template' => 'mails/gc_application_ineligible',
                            'from_name' => "Golden Cube",
                            'message' => $gc_message,
                        );
                        $send_mail = send_template_email($email_array);
                    }
                    $log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $remark, 'action_by' => $this->auth_user_id, 'status_id' => 320);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        //update lead status and contactable date in lead table
                        $update_lead_array = array('lead_status' => 321);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');

                        $req_url = 'https://goldencube.devenvironment.space/api/eligible?lead_id=' . $lead_id . '&is_eligible=' . $eligible . '&eligible_desc=' . $remark;

                        $curl = curl_init();

                        curl_setopt_array(
                            $curl,
                            array(
                                CURLOPT_URL => $req_url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                            )
                        );

                        $response = curl_exec($curl);

                        curl_close($curl);
                        // echo $response;
                        log_message('log', "GC REQ: " . $req_url);
                        log_message('log', "GC RES: " . $response);
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    public function action_accepted($lead_id)
    {
        if ($this->verify_min_level(1)) {
            $custom_remarks = "Lead Accepted";
            $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $custom_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 302);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
            if ($insert_log > 0) {
                //update lead status and contactable date in lead table
                $update_lead_array = array('lead_status' => 302);
                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                $this->session->set_flashdata('alert', 'success');
                $this->session->set_flashdata('alert_message', 'Lead data updated successfully. You can see the progress in timeline.');
            } else {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
            }

            redirect('leads/lead/view/' . $lead_id);
        } else {
            redirect('login');
        }
    }


    /**
     * [action_meeting Logged in user can update about the lead for future reference]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [updated in timeline of the lead]
     */
    public function action_meeting_minutes()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_meeting_minutes'])) {
                $meeting_update_remarks = $this->input->post('meeting_update_remarks');
                $meeting_contactable_date = $this->input->post('meeting_contactable_date');
                $lead_id = $this->input->post('lead_id');
                $meeting_id = $this->input->post('meeting_id');

                if ($meeting_update_remarks != '' && $meeting_contactable_date != '' && $lead_id != '' && $meeting_id != '') {
                    $meeting_update = $this->mcommon->common_edit('lead_meetings', array('is_complete' => 1, 'last_updated' => date('Y-m-d H:i:s')), array('lead_id' => $lead_id, 'id' => $meeting_id));
                    if ($meeting_update) {

                        $log_insert_meeting_array = array('action_id' => 409, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $meeting_update_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 304);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_meeting_array);
                        if ($insert_log > 0) {
                            //update lead status and contactable date in lead table
                            $update_lead_array = array('lead_status' => 304, 'contactable_date' => $meeting_contactable_date);
                            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'Minutes of meeting updated successfully. You can see the progress in timeline.');
                        } else {
                            $this->session->set_flashdata('alert', 'danger');
                            $this->session->set_flashdata('alert_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                        }
                    } else {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Unable to update meeting minutes. Please contact support team.');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'All fields are required');
                }
                redirect('leads/lead/view/' . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    /**
     * [action_meeting Logged in user can update about the lead for future reference]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [updated in timeline of the lead]
     */
    public function action_order($lead_id)
    {
        try {
            if ($this->verify_min_level(1)) {
                if (isset($_POST['order_id'])) {
                    if (gettype($_POST["order_id"]) == "array") {
                        $_POST["order_id"] = implode(",", $_POST['order_id']);
                    }
                    $order_id = $this->input->post('order_id');
                    $file_url = "";
                    if ($order_id != '') {
                        // print_r($_POST);
                        // exit();
                        if (isset($_FILES['file']['name'])) {
                            $attachment_name = $this->input->post('attachment_name');
                            log_message('log', 'inside files uploads');
                            // Count total files
                            // print_r($_FILES);
                            $countfiles = 1;
                            log_message('log', 'Lead Order File' . $countfiles);

                            // Looping all files

                            // Define new $_FILES array - $_FILES['file']
                            $_FILES['file']['name'] = $_FILES['file']['name'];
                            $_FILES['file']['type'] = $_FILES['file']['type'];
                            $_FILES['file']['tmp_name'] = $_FILES['file']['tmp_name'];
                            $_FILES['file']['error'] = $_FILES['file']['error'];
                            $_FILES['file']['size'] = $_FILES['file']['size'];

                            // Set preference
                            $config['upload_path'] = 'uploads/leads';
                            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                            $config['max_size'] = '5000'; // max_size in kb
                            $config['file_name'] = $_FILES['file']['name'];

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

                                $file_url = base_url() . 'uploads/leads/' . $filename;

                                $insert_attachment_array = array('lead_id' => $lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => $file_url);
                                $attachment_insert = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                            }
                        }
                        // print_r($_POST);
                        $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $order_id;
                        if ($file_url != "") {
                            $order_desc = $order_desc . "<br> Reference File: <a target='_blank' href='" . $file_url . "' class='p-2 pl-4 pr-4 btn btn-primary'>File</a>";
                        }
                        // echo $order_desc;
                        // exit();
                        $log_insert_array = array('action_id' => 410, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $this->auth_user_id, 'status_id' => 305);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        if ($insert_log > 0) {
                            $update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id, "completed_by" => $this->auth_user_id);
                            $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));


                            $lead_det = $this->leads_model->lead_details($lead_id);


                            // echo "Lead==> ".$lead_id;
                            //print_r($lead_det);
                            //exit();
                            $subject = "Lead #" . $lead_id . " is completed!!";
                            $message = "Lead #" . $lead_id . " is completed!!<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:";

                            $message .= "<br>Customer Name: " . $lead_det["customer_name"];
                            $message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                            $message .= "<br>Customer Email: " . $lead_det["customer_email"];
                            $message .= "<br>Service Completion:";
                            $message .= "<br><table border='1' style='text-align: center;'><tr><td>Service Name<td>Invoice Reference No.</td></tr><tr><td>" . $lead_det["category_code"] . " - " . $lead_det["service_name"] . "</td><td>" . $lead_det["order_receipt"] . "</td></tr></table>";

                            $group_mems = [];
                            array_push($group_mems, $this->auth_user_id);
                            array_push($group_mems, $lead_det["lead_created_by"]);

                            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();

                            // print_r($lead_det["lead_created_by"]);
                            $group_ids = [];
                            foreach ($groups as $group) {
                                array_push($group_ids, $group["group_id"]);
                            }
                            $this->db->select("distinct(group_members.user_id)")->from("group_members");
                            $this->db->join("users", "users.user_id=group_members.user_id");
                            $this->db->where("users.is_active", 1);
                            // $this->db->where("users.role_id", 7);
                            $this->db->where_in('users.role_id', [7, 86]);
                            $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
                            // print_r($this->db->last_query());
                            // print_r($members);
                            // // print_r($group_ids);
                            // // print_r($members);
                            // exit();
                            foreach ($members as $mem) {
                                array_push($group_mems, $mem["user_id"]);
                            }

                            $users = $this->db->select("*")->from("users")->where("(users.user_id in (" . implode(',', $group_mems) . "))")->get()->result_array();

                            $cc_usermail = [];
                            foreach ($users as $user) {
                                array_push($cc_usermail, ["email" => $user["email"], "name" => $user["first_name"]]);
                            }

                            // This email is for GC Order Completion ==============================================================

                            if ($lead_det["branch_id"] == 106) {
                                $lead_remarks = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "remarks");
                                $remarks_data = html_entity_decode($lead_remarks);
                                $result = explode(',', $remarks_data);
                                $package_name = str_replace('Package:', '', $result[1]);
                                $gc_message = array(
                                    'customer_name' => $lead_det["customer_name"],
                                    'package_name' => $package_name,
                                    // 'visa_number' => $action["action_on"],
                                    // 'issue_date' => $service_payment,
                                    // 'expiry_date' => $service_payment,
                                );
                                $email_array = array(
                                    'email' => $lead_det["customer_email"],
                                    'subject' => 'Goldencube - Payment Recieved',
                                    'template' => 'mails/gc_application_payment_received',
                                    'from_name' => "Golden Cube",
                                    'message' => $gc_message,
                                );
                                $send_mail = send_template_email($email_array);
                            }


                            /* $email_array = array(
                                'email' => $cc_usermail,
                                'subject' => $subject,
                                'template' => 'mails/template',
                                'from_name' => "CRM ALERT",
                                'message' => $message,
                            );

                            $send_mail = send_template_email($email_array); */
                            log_message('error', $send_mail);

                            if ($lead_det["category_id"] == 10020 || $lead_det["branch_id"] == 106) {

                                $subject = "Your Order is " . $lead_id;
                                // $message = "Dear " . $lead_det['customer_name'] . ".,<br><br>GoldenCube Order Service #" . $lead_id . " is completed!!<br>";
                                $message = "Your Order is " . $lead_id;

                                // $message .= "<br>Customer Name: " . $lead_det["customer_name"];
                                // $message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                                // $message .= "<br>Customer Email: " . $lead_det["customer_email"];
                                $message .= "<br>Service Completion:";
                                $message .= "<br><table border='1' style='text-align: center;'><tr><td>Service Name<td>Invoice Reference No.</td></tr><tr><td>" . $lead_det["category_code"] . " - " . $lead_det["service_name"] . "</td><td>" . $lead_det["order_receipt"] . "</td></tr></table>";

                                $s_email_array = array(
                                    'email' => $lead_det["customer_email"],
                                    'subject' => $subject,
                                    'template' => 'mails/gc_template',
                                    'from_name' => $lead_det["branch_name"],
                                    'message' => $message,
                                );

                                // print_r($lead_det);
                                // print_r($email_array);
                                // exit();

                                $send_mail = send_template_email($s_email_array);
                                log_message('error', $send_mail);

                                $sms["message"] = "Your Sub order number OTLD-" . $lead_det["id"] . " is completed.";
                                $sms["mobile_no"] = substr($lead_det["customer_mobile"], -9);
                                $sms["sender_id"] = "GoldenCube";
                                // print_r($sms);
                                // exit();
                                $send_sms = send_trans_sms($sms);
                                log_message('error', $send_sms);
                            }

                            // if ($lead_det["lead_parent_id"] != 0) {
                            //     $childs = $this->db->select("*")->from("leads")->where("lead_parent_id", $lead_det["lead_parent_id"])->where("lead_status !=", 305)->get()->result_array();
                            //     if (count($childs) == 0) {
                            //         $update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id);
                            //         $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_det["lead_parent_id"]));
                            //     }
                            // }

                            if ($lead_det["lead_parent_id"] != 0) {
                                $this->db->where('id', $lead_det["lead_parent_id"]);
                                $this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
                                $this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
                                $this->db->update('leads');

                                $childs = $this->db->select("*")->from("leads")->where("lead_parent_id", $lead_det["lead_parent_id"])->where("lead_status !=", 305)->get()->result_array();
                                if (count($childs) == 0) {
                                    $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $order_id;
                                    $log_insert_array = array('action_id' => 410, 'lead_id' => $lead_det["lead_parent_id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => 178140614, 'status_id' => 305);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $update_lead_array = array('lead_status' => 305, 'order_receipt' => $order_id, "completed_by" => $this->auth_user_id);
                                    $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_det["lead_parent_id"]));

                                    $lead_parent_det = $this->leads_model->lead_details($lead_det["lead_parent_id"]);

                                    if ($lead_parent_det["category_id"] == 10020 || $lead_parent_det["branch_id"] == 106) {

                                        $subject = "Your Order is " . $lead_det["lead_parent_id"];
                                        // $message = "Dear " . $lead_det['customer_name'] . ".,<br><br>GoldenCube Order Service #" . $lead_det["lead_parent_id"] . " is completed!!<br>";
                                        $message = "Your Order is " . $lead_det["lead_parent_id"];

                                        // $message .= "<br>Customer Name: " . $lead_parent_det["customer_name"];
                                        // $message .= "<br>Customer Contact: " . $lead_parent_det["customer_mobile"];
                                        // $message .= "<br>Customer Email: " . $lead_parent_det["customer_email"];
                                        $message .= "<br>Service Completion:";
                                        $message .= "<br><table border='1' style='text-align: center;'><tr><td>Service Name<td>Invoice Reference No.</td></tr><tr><td>" . $lead_parent_det["category_code"] . " - " . $lead_parent_det["service_name"] . "</td><td>" . $lead_parent_det["order_receipt"] . "</td></tr></table>";

                                        $email_array = array(
                                            'email' => $lead_parent_det["customer_email"],
                                            'subject' => $subject,
                                            'template' => 'mails/gc_template',
                                            'from_name' => $lead_parent_det["branch_name"],
                                            'message' => $message,
                                        );

                                        // print_r($lead_parent_det);
                                        // print_r($email_array);
                                        // exit();

                                        $send_mail = send_template_email($email_array);
                                        log_message('error', $send_mail);

                                        $sms["message"] = "Your Sub order number OTLD-" . $lead_parent_det["id"] . " is completed.";
                                        $sms["mobile_no"] = substr($lead_parent_det["customer_mobile"], -9);;
                                        $sms["sender_id"] = "GoldenCube";
                                        // print_r($sms);
                                        // exit();
                                        $send_sms = send_trans_sms($sms);
                                        log_message('error', $send_sms);
                                        //print_r($send_sms);
                                        //exit();
                                    }
                                }
                            }

                            $this->session->set_flashdata('alert_complete', 'success');
                            $this->session->set_flashdata('alert_complete_message', 'Order data updated successfully. You can see the progress in timeline.');
                        } else {
                            $this->session->set_flashdata('alert_complete', 'danger');
                            $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                        }
                    } else {
                        $this->session->set_flashdata('alert_complete', 'danger');
                        $this->session->set_flashdata('alert_complete_message', 'All fields are required');
                    }
                    //If its a Sub Lead
                    $parent_id = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "lead_parent_id");
                    if ($parent_id == 0) {
                        redirect('leads/lead/view/' . $lead_id);
                    } else {
                        redirect('leads/lead/view/' . $parent_id);
                    }
                    // redirect('leads/lead/view/' . $lead_id);
                }
            } else {
                redirect('login');
            }
        } catch (Exception $e) {
            print_r($e);
            exit();
        }
    }

    /**
     * [action_meeting Logged in user can update about the lead for future reference]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [updated in timeline of the lead]
     */
    public function action_close($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_close'])) {
                $close_remarks = $this->input->post('close_remarks');

                if ($close_remarks != '') {
                    $log_insert_array = array('action_id' => 411, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 306);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        $update_lead_array = array('lead_status' => 306, 'close_remarks' => $close_remarks);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert_complete', 'success');
                        $this->session->set_flashdata('alert_complete_message', 'Lead has been closed successfully. You can see the progress in timeline.');
                    } else {
                        $this->session->set_flashdata('alert_complete', 'danger');
                        $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert_complete', 'danger');
                    $this->session->set_flashdata('alert_complete_message', 'All fields are required');
                }
                $parent_id = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "lead_parent_id");
                if ($parent_id == 0) {
                    redirect('leads/lead/view/' . $lead_id);
                } else {
                    redirect('leads/lead/view/' . $parent_id);
                }
            }
        } else {
            redirect('login');
        }
    }

    /**
     * [action_meeting Logged in user can update about the lead for future reference]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [updated in timeline of the lead]
     */
    public function action_enquiry($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_close'])) {
                $close_remarks = $this->input->post('close_remarks');

                if ($close_remarks != '') {
                    $log_insert_array = array('action_id' => 427, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 627);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        $update_lead_array = array('lead_status' => 627, 'close_remarks' => $close_remarks);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert_complete', 'success');
                        $this->session->set_flashdata('alert_complete_message', 'Lead has been updated successfully. You can see the progress in timeline.');
                    } else {
                        $this->session->set_flashdata('alert_complete', 'danger');
                        $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert_complete', 'danger');
                    $this->session->set_flashdata('alert_complete_message', 'All fields are required');
                }
                $parent_id = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "lead_parent_id");
                if ($parent_id == 0) {
                    redirect('leads/lead/view/' . $lead_id);
                } else {
                    redirect('leads/lead/view/' . $parent_id);
                }
            }
        } else {
            redirect('login');
        }
    }

    /**
     * [action_meeting Logged in user can update about the lead for future reference]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [updated in timeline of the lead]
     */
    public function action_lead_hold($lead_id)
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['action_close'])) {
                $close_remarks = $this->input->post('close_remarks');

                if ($close_remarks != '') {
                    $log_insert_array = array('action_id' => 431, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 630);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        $update_lead_array = array('lead_status' => 630, 'close_remarks' => $close_remarks);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                        $this->session->set_flashdata('alert_complete', 'success');
                        $this->session->set_flashdata('alert_complete_message', 'Lead has been updated successfully. You can see the progress in timeline.');
                    } else {
                        $this->session->set_flashdata('alert_complete', 'danger');
                        $this->session->set_flashdata('alert_complete_message', 'Log updated successfully. But unable to update the lead record. Contact support.');
                    }
                } else {
                    $this->session->set_flashdata('alert_complete', 'danger');
                    $this->session->set_flashdata('alert_complete_message', 'All fields are required');
                }
                $parent_id = $this->mcommon->specific_row_value("leads", ["id" => $lead_id], "lead_parent_id");
                if ($parent_id == 0) {
                    redirect('leads/lead/view/' . $lead_id);
                } else {
                    redirect('leads/lead/view/' . $parent_id);
                }
            }
        } else {
            redirect('login');
        }
    }

    /**
     * [action_meeting Logged in user can update about the lead for future reference]
     * @param  [type] $lead_id [lead's id]
     * @return [type]          [updated in timeline of the lead]
     */
    public function action_countryoptions()
    {
        $lead_id = $this->input->post('lead_id');
        $country_options = $this->input->post('country_options');
        $close_remarks = "Coutry Option has been updated as " . $country_options;
        if ($lead_id != '' && $country_options != '') {
            $action_by = 178140614;        // info@ontimegroup.com
            $log_insert_array = array('action_id' => 429, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $action_by, 'status_id' => 628);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
            if ($insert_log > 0) {
                // $update_lead_array = array('lead_status' => 626);
                $update_lead_array = array('country_options' => $country_options);
                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));

                if ($country_options == 'insideCountry') {
                    $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '69'), 'remarks'); // 66
                    $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '69'), 'id');       // 66
                    $lead_det = $this->leads_model->lead_details($sub_lead_id);

                    if ($service_name != "" && $service_name != null) {

                        $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                        $sub_lead_message .= "<br>Dear Ishti<br>";
                        $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong>" . $service_name . "</strong> for the lead listed below <br>";
                        $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                        $sub_lead_message .= "<br><br>Lead Description:<br>";
                        $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                        $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                        $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                        $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                        $sub_lead_message .= "<br>Receipt Number: <strong>" . $lead_det["pos_pmt_number"] . "</strong>";
                        $sub_lead_message .= "<br>Remarks: " . $service_name;

                        $cc_usermail = [];
                        // array_push($cc_usermail, ["email" => "Fawziya.h@ontimegov.com", "name" => "Fawziya"]);	// 980422236
                        // array_push($cc_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali"]);	// 2411946200
                        array_push($cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);  // 2879029976
                        // array_push($cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453


                        $email_array = array(
                            'email' => $cc_usermail,
                            'subject' => $sub_lead_subject,
                            'template' => 'mails/template',
                            'from_name' => "CRM ALERT",
                            'message' => $sub_lead_message,
                        );
                        $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Ishti";   
                        $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $send_mail = send_template_email($email_array);
                    }
                }
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function action_leadreopen()
    {
        $lead_id = $this->input->post('lead_id');
        $close_remarks = "Lead has been reopened.";
        if ($lead_id != '') {
            $log_insert_array = array('action_id' => 428, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $close_remarks, 'action_by' => $this->auth_user_id, 'status_id' => 626);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
            if ($insert_log > 0) {
                $update_lead_array = array('lead_status' => 626);
                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                echo json_encode(['status' => 'success']);
                exit;
            } else {
                echo json_encode(['status' => 'error']);
            }
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function test_sms()
    {
        $mobileno = '971551416027';
        $message_body = "Test SMS from ONTIME CRM. if you receive this SMS. Send screenshot in whatsapp group - Ganesh";
        $sms_gateway_data = sendsms($mobileno, $message_body);
        $result_array = json_decode($sms_gateway_data);

        if (isset($result_array->jobId)) {
            echo "1";
        } else {
            echo "0";
        }
    }

    //unused code
    /*public function assign()
    {
    if ($this->verify_min_level(1))
    {
    //if super administrator or coordinator
    if($this->auth_user_role==1 || $this->auth_user_role==6)
    {
    $view_data['enquiries'] = $this->app_model->baraha_unassigned_enquiries();
    log_message('error',$this->db->last_query());
    $view_data['baraha_csas'] = $this->user_model->baraha_csas();
    $data = array(
    'page_title' => 'Un-assigned Enquiries',
    'title' => 'Un-assigned Enquiries',
    'content' => $this->load->view('pages/enquiries/baraha/unassigned_list', $view_data, TRUE),
    );
    $this->load->view('template/full_template', $data);
    }
    }
    else
    {
    redirect('login');
    }
    }*/

    /*public function index()
    {
    if ($this->verify_min_level(1))
    {
    if($this->auth_user_role==1 || $this->auth_user_role==6)
    {
    $view_data['enquiries'] = $this->mcommon->specific_fields_records_all('enquiry',array('company_id'=>30, 'enquiry'=>1));
    }
    else
    {
    $view_data['enquiries'] = $this->app_model->baraha_csa_enquiries();
    }
    $data = array(
    'page_title' => 'Enquiries',
    'title' => 'Enquiries',
    'content' => $this->load->view('pages/enquiries/baraha/list', $view_data, TRUE),
    );
    $this->load->view('template/full_template', $data);
    } else {
    redirect('login');
    }
    }*/

    public function complete_lead($lead_id)
    {
        if ($this->verify_min_level(1)) {
            $lead = $this->leads_model->lead_details($lead_id);
            if ($lead["pos_salesorder"] != '') {

                $so = explode("-", $lead["pos_salesorder"]);
                $so = end($so);

                $invoice = "";
                $error_msg = "";

                $curl = curl_init();

                curl_setopt_array(
                    $curl,
                    array(
                        // CURLOPT_URL => 'https://ontimesmartpos.net1/api/ApiPos/ConvertToInvoice/' . $so,
                        CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/ConvertToInvoice/' . $so,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                        ),
                    )
                );

                $response = curl_exec($curl);

                curl_close($curl);
                $res_json = json_decode($response);
                // echo $response;
                $update_lead_array = array('pos_invresponse' => $response);
                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                if ($res_json->ResponseCode == 0) {
                    $inv = $res_json->Data->SLI_Headnum;
                    $invoice = $inv;
                } else {
                    $inv = $res_json->Data->SLI_Headnum;
                    if ($inv != "" || $inv != null) {
                        $invoice = $inv;
                    } else {
                        $error_msg = $res_json->ResponseMsg;
                    }
                }
                if ($invoice != "") {
                    $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $invoice;
                    $log_insert_array = array('action_id' => 410, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $this->auth_user_id, 'status_id' => 305);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    if ($insert_log > 0) {
                        $update_lead_array = array('lead_status' => 305, 'order_receipt' => $invoice, "completed_by" => $this->auth_user_id);
                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
                    }
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Lead converted to order and Invoice generated. Please check in the below Timeline.');
                    return redirect("/leads/lead/view/" . $lead_id);
                }
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('alert_message', $error_msg);
                return redirect("/leads/lead/view/" . $lead_id);
            }
        } else {
            redirect('login');
        }
    }

    public function getinvoice()
    {
        try {
            if (isset($_GET['so'])) {
                $so = $_GET['so'];
                if ($so) {
                    echo "Authourization Denied";
                    exit();
                    // print_r($pcr);
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => 'https://verify.ontime-pos.com/POSInvoice/Login?username=admin',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ]);

                    $response = curl_exec($curl);

                    curl_close($curl);
                    $res = json_decode($response);

                    $token = $res->Token;

                    $curl = curl_init();

                    $curl_url = 'https://verify.ontime-pos.com/POSInvoice/GetPaidStatus/' . $so;
                    // echo $curl_url;
                    // echo "<br>";
                    // echo $token;

                    curl_setopt_array($curl, [
                        CURLOPT_URL => $curl_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Length: 0'],
                    ]);

                    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 50000'));

                    // curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=utf-8"));

                    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0'));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    $res = json_decode($response);
                    // echo "<br>";
                    // echo "There: ".$response;
                    // print_r(curl_error($curl));
                    // exit();
                    if (!$res->Url) {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PCR Order');
                        return redirect('/leads/lead/manage');
                    }
                    // print_r($res);

                    // if($res->Status)
                    header("location: " . $res->Url);

                    if (curl_error($curl)) {
                        // echo curl_error($curl);
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Exception: ' . curl_error($curl));
                        return redirect('/leads/lead/manage');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Invoice Id does not exist in this Lead.');
                    return redirect('/leads/lead/manage');
                }
            } else {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('alert_message', 'Cannot fetch invoice for this Lead');
                return redirect('/leads/lead/manage');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('alert', 'danger');
            $this->session->set_flashdata('alert_message', 'Cannot fetch invoice for this Lead');
            return redirect('/leads/lead/manage');
        }
    }

    public function rerun()
    {

        $query = 'SELECT leads.id,leads.created_at,lead_users.first_name,lead_users.last_name,lead_users.email,lead_users.mobile,ontime_categories.category_name,leads.remarks,leads.zoho_response FROM `leads` join lead_users on lead_users.user_id = leads.customer_id join ontime_categories on ontime_categories.category_id = leads.category_id where leads.category_id = 111 and month(leads.created_at) = 7 and branch_id = 100 and zoho_response not like "%SUCCESS%" ORDER BY `lead_users`.`first_name` DESC';
        $res = $this->db->query($query)->result_array();

        $prev_req = [];
        $prev_req["First_name"] = null;
        $prev_req["remarks"] = null;
        foreach ($res as $r) {
            $lead_id = $r["id"];
            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);
            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';

            $req = [];
            $req["First_Name"] = substr($r['first_name'], 0, 39);
            $req["Last_Name"] = substr($r['first_name'], strlen($req["First_Name"]));
            if ($req["Last_Name"] == null || $req["Last_Name"] == "") {
                $req["Last_Name"] = $req["First_Name"];
            }

            $req["Description"] = $r['remarks'];
            if ($prev_req["First_name"] == $req["First_name"] && $prev_req["remarks"] == $req["Description"]) {
                continue;
            }

            // $req["First_Name"] = $r['first_name'];
            // $req["Last_Name"] = $r['last_name'];
            $req["Email"] = $r['email'];
            if (strpos($req["Email"], '@ontimecustomer.com') !== false) {
                $req["Email"] = $random_email;
            }
            $req["Phone"] = $r['mobile'];
            $req["Lead_Source"] = "POS";
            $prev_req = $req;

            $branch_id = 100;
            $lead_type = "normal";
            $lead_name = $req["First_Name"] . "" . $req["Last_Name"];
            $lead_contact = $req["Phone"];
            $lead_email = $req["Email"];
            $lead_remarks = $req["Description"];
            $lead_by_pos_user = 178140614;
            $lead_by_post_user_name = "WEB API";

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            //check category exist
            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(["status" => "false", "message" => "branch_id doesn't exist. Update branch first to create the lead"], 404);
            }

            $category_id = 111;

            try {

                $curl = curl_init();

                curl_setopt_array(
                    $curl,
                    array(
                        CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array('client_id' => '1000.SNAU7L0CEEQYLH6F3XELSEFMZ5IZZQ', 'client_secret' => 'f69319cf08f0939f5d0b1e8da486d587a52c1d4e9c', 'refresh_token' => '1000.3622f1ae558acda56a3626ece07c30f7.a89e6e874627e8f238e17a3d5773a4df', 'grant_type' => 'refresh_token'),
                    )
                );

                $response = curl_exec($curl);
                log_message('log', 'ZOHO Token: ' . $response);
                curl_close($curl);
                // echo "<br>";
                // // echo $response;
                // echo "<br>";
                $res_json = json_decode($response);

                $token = $res_json->access_token;

                // print_r($res_json);
                // echo "<br><br>";
                // print($req);
                // exit();
                // $req = [];
                $name = trim($lead_name);
                $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
                $first_name = trim(preg_replace('#' . preg_quote($last_name, '#') . '#', '', $name));

                if ($last_name == null || trim($last_name) == "") {
                    $last_name = "NA";
                }

                // $req["First_Name"] = $first_name;
                // $req["Last_Name"] = $last_name;
                // $req["Email"] = $lead_email;
                // $req["Phone"] = $lead_contact;
                // $req["Description"] = $lead_remarks;
                // $req["Lead_Source"] = "POS";
                $curl = curl_init();
                log_message('log', 'ZOHO Req: ' . $req);

                $data["data"] = [$req];
                // echo "<br>";
                // echo "<pre>";
                // echo json_encode($data);
                // echo "</pre>";
                // continue;
                // exit();
                curl_setopt_array(
                    $curl,
                    array(
                        CURLOPT_URL => 'https://www.zohoapis.com/crm/v2/Leads',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($data),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . $token,
                            'Content-Type: application/json',
                        ),
                    )
                );

                $response = curl_exec($curl);

                curl_close($curl);
                log_message('log', 'ZOHO CRM Res: ' . $response);
                // print_r($response);
                // echo "<br>";
                if (strpos($response, 'SUCCESS') == false) {
                    echo json_encode($data);
                    echo "<br>This is not fair";
                    exit();
                }
                exit();
                $zoho_res = $response;
                $update = $this->mcommon->common_edit('leads', array('zoho_response' => $response, 'lead_status' => 309), array('id' => $lead_id));

                $log_insert_array = array('action_id' => 414, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Zoho CRM Lead Record Created', 'action_by' => $lead_by_pos_user, 'status_id' => 309);
                // print_r($log_insert_array);
                $log_insert = $this->db->insert('lead_action_log', $log_insert_array);
            } catch (Exception $e) {
                // print_r($e);
                $zoho_res = $e->getMessage();
                $update = $this->mcommon->common_edit('leads', array('zoho_response' => $e->getMessage()), array('id' => $lead_id));
            }
            // $this->response(["status" => true, "message" => 'Lead has been created and assigned successfully!'], 200);

            // $this->response(["status" => true, "message" => "Lead successfully created."], 200);

        }
        // exit();
    }

    public function getcrosssales()
    {
        if ($this->verify_min_level(1)) {

            if (!isset($_GET["RCT"])) {
                echo json_encode(["status" => false, "message" => "RCT number is missing."]);
                exit();
            }

            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/getPaymentDetails/1/24-07-1998/25-07-1022/' . $_GET["RCT"],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                )
            );

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
            exit();
        } else {
            echo json_encode(["status" => false, "message" => "SessionOut"]);
            exit();
        }
    }

    public function getEmpCustomers()
    {
        if ($this->verify_min_level(1)) {
            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/getEmpCustomers/61',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                )
            );

            $response = curl_exec($curl);

            curl_close($curl);
            $res = json_decode($response);
            $data["data"] = $res->Data;
            echo json_encode($data);
            exit();
        } else {
            echo json_encode(["status" => false, "message" => "SessionOut"]);
            exit();
        }
    }

    public function countsync()
    {
        $parent_leads = $this->db->select("distinct(lead_parent_id) as id")->from("leads")->where("lead_parent_id !=", 0)->get()->result_array();
        foreach ($parent_leads as $lead) {
            // echo "<p>" . $lead["id"] . "</p>";
            $lead_details = $this->db->select("sum(if(leads.lead_status=305,1,0)) as closed,sum(if(leads.lead_status=305,0,1)) as open,count(leads.id) as total")->from("leads")->where("leads.lead_parent_id", $lead["id"])->get()->first_row();
            // print_r($lead_details);
            // exit();
            echo $lead["id"] . "=> " . $lead_details->open . "+" . $lead_details->closed . "==>" . $lead_details->total . "<br>";
            $update_lead_array = array('total_no_subleads' => $lead_details->total, 'no_of_open_subleads' => $lead_details->open, 'no_of_closed_subleads' => $lead_details->closed);
            $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead["id"]));
        }
    }
    public function test()
    {
        // if ($lead_det["branch_id"] == 138) {
        $lead_id=158855;
        $userId = 1752723831;
        $lead_det = $this->leads_model->lead_details($lead_id);
        $appointment = $this->db->select("*")->from("calendar_appointment")->where("lead_id", $lead_id)->get()->first_row();
        $appointment_id = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'id');
        $booking_id = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'booking_id');
        $time_slot = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'booking_timeslot');


        $log_insert_array = array('appointment_id' => $appointment_id, 'remark' => "Booking Confirmed / Paid Updated", 'created_at' => date('Y-m-d H:i:s'), 'status_code' => 903, 'status_description' => "Payemnt Done Through Online", 'created_by' => $userId, 'updated_at' => date('Y-m-d H:i:s'));
        // $insert_log = $this->mcommon->common_insert('calendar_log', $log_insert_array);

        // $update = $this->mcommon->common_edit("calendar_appointment", array("status" => 903, "updated_at" => date('Y-m-d H:i:s')), array("id" => $appointment_id));

        // $postData = [
        //     'id' => $appointment_id,
        //     'booking_id' => $booking_id,
        //     'status' => 903,
        //     'updated_at' => date('Y-m-d H:i:s'),
        // ];

        // // Convert the data to JSON format
        // $fields = json_encode($postData);

        // // Initialize cURL session
        // $ch = curl_init();

        // // Set cURL options
        // curl_setopt($ch, CURLOPT_URL, 'https://ontimegov.com/digital/api/v1/baraha/Order/paid');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Accept: application/json',
        //     'api-key: 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',
        //     'Content-Type: application/json'
        // ]);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HEADER, false);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // // Execute cURL request
        // $response = curl_exec($ch);
        // $error = curl_error($ch);

        // // Close cURL session
        // curl_close($ch);
        $time = $time_slot;
        // Step 1: Explode the string by commas to separate the date and times
        $parts = explode(', ', $time);
        // Step 2: The first part is the date, and the rest are the time slots
        $date = $parts[0]; // Extract the date
        $time_slots = array_slice($parts, 0); // Get all the time slots (excluding the date)
        // Step 3: Check if time slots are null or empty
        //if ($time_slots == NULL || empty($time_slots)) {
        if ($time == NULL) {
            // If no time slots are provided, set default start and end times in 12-hour format
            $time_range = '09:00 AM - 08:00 PM'; // 9 AM to 6 PM
        }
        // Step 4: Check if there is only one time slot
        else if (count($time_slots) == 1) {
            // If only one time slot, calculate the end time as the start time plus one hour
            $first_time = $time_slots[0];

            // Convert time to DateTime object
            $start_time = new DateTime($first_time);

            // Add one hour to the start time
            $end_time = clone $start_time;
            $end_time->modify('+1 hour');

            // Format time range in 12-hour format with AM/PM
            $time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
        }
        // Step 5: Handle multiple time slots
        else {
            // Get the first and last time slots
            $first_time = new DateTime($time_slots[0]);
            $last_time = new DateTime($time_slots[count($time_slots) - 1]);

            // Format time range in 12-hour format with AM/PM
            $time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
        }


        // $cc_usermail = [];
        //     // After Payment Completion for DLD Fees
        //     array_push($sublead_cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);    // 2879029976
        //     array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
        $pos_pmt_number_mail = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'pos_pmt_number');
        $email_array1 = array(
            'name' => $lead_det["customer_name"],
            'email' => $lead_det["customer_email"], //$cust_email,
            'subject' => 'Payment Received – Appointment Confirmed',
            'template' => 'emails/bv_payment_received',
            'from_name' => "ONTIME GOV ALERT",
            'from_email' => 'mobile.medical@ontimegov.com',
            // 'cc' => $cc_usermail,
            // 'message' => $html,
            'booking_reference_number' => $appointment->id ?? 'N/A',
            'service_name' =>  !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
            'package_name' =>  !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
            'date' => $appointment->booking_date  ?? 'N/A',
            'time' => $time_range  ?? 'N/A',
            'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
            'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
            'pos_pmt_number' => !empty($pos_pmt_number_mail) ? $pos_pmt_number_mail : 'N/A',
            // 'name' => $user->first_name . $user->last_name,
        );

        $send_mail1 = send_template_email($email_array1);
        log_message('error', $send_mail1);
        $current_timestamp = date('Y-m-d H:i:s');
        $action_message1 = "Appointment id " . $appointment->id . " has been Booked and Payment is Done . Amount : " . $appointment->amount . " AED";
        $log_insert_array = array(
            'lead_id' => $lead_id,
            'action_amount' =>  $appointment->amount,
            'action_id' => 434,
            'status_id' => 633,
            'action_by' => $userId,
            'action_on' => $current_timestamp,
            'remarks' => '<pre>' . $action_message1 . '</pre>',
            "bot_id" => 0
        );
        // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
        $user = $this->db->where("user_id", $userId)->from("users")->get()->first_row();
        $email_array = array(
            // 'name' => $user->email,
            // 'email' => $user->first_name . $user->last_name,
            'name' => "mathan",
            'email' => "mathanraj.g@mitrahsoft.com", //$cust_email,
            'subject' => 'Payment Received – Appointment Confirmed',
            'template' => 'emails/bv_payment_received',
            'from_name' => "ONTIME GOV ALERT",
            'from_email' => 'mobile.medical@ontimegov.com',
            // 'cc' => $cc_usermail,
            // 'message' => $html,
            'booking_reference_number' => $appointment->id ?? 'N/A',
            'service_name' =>  !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
            'package_name' =>  !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
            'date' => $appointment->booking_date  ?? 'N/A',
            'time' => $time_range  ?? 'N/A',
            'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
            'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
            'pos_pmt_number' => !empty($pos_pmt_number_mail) ? $pos_pmt_number_mail : 'N/A',
            // 'name' => $user->first_name . $user->last_name,
        );

        $send_mail = send_template_email($email_array);
        log_message('error', $send_mail);
        var_dump("complete");exit;
    }

    public function request_conversation($request_id){
        // $url = 'https://94.200.55.118:2080/api/v3/requests/'.$request_id.'/conversations';
        // $api_key = 'D488A202-F17D-41FE-A52B-11973561539B';

        $url = 'https://94.200.55.118:5000/api/v3/requests/'.$request_id.'/conversations';
        $api_key = '171016F2-9943-418C-AD51-56E7E7C7DF4E';

        $headers = [
            "authtoken: $api_key"
        ];
        $ch = curl_init();
        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url); // Use the URL with query parameters
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the headers
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (like verify=False in requests)
        // Execute the cURL session
        $response = curl_exec($ch);
        // echo '<pre>';print_r($response);exit;
        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            // Print the response
            $result = json_decode($response);
            $conversations = $result->conversations;
            return $conversations;
        }

        curl_close($ch);
    }

    public function conversation_description($content_url = null){
        $content_url = $content_url ?: $this->input->get('content_url');
        if(!empty($content_url)){
            // $url = 'https://94.200.55.118:2080'.$content_url;
            // $api_key = 'D488A202-F17D-41FE-A52B-11973561539B';

            $url = 'https://94.200.55.118:5000'.$content_url;
            $api_key = '171016F2-9943-418C-AD51-56E7E7C7DF4E';
            $headers = [
                "authtoken: $api_key"
            ];
            $ch = curl_init();
            // Set the cURL options
            curl_setopt($ch, CURLOPT_URL, $url); // Use the URL with query parameters
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the headers
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (like verify=False in requests)
            // Execute the cURL session
            $response = curl_exec($ch);
            if ($response === false) {
                echo 'cURL Error: ' . curl_error($ch);
            } else {
                // $result = json_decode($response);
                // return $result;
                echo json_encode($response);
                exit();
            }
            curl_close($ch);
        }
    }

    public function send_reply($request_id,$lead_id){
        if ($this->verify_min_level(1)) {
            if (isset($_POST['reply_btn'])) {
                $this->form_validation->set_rules('to_mail', 'To', 'required');
                // $this->form_validation->set_rules('cc_mail', 'cc', 'required');
                $this->form_validation->set_rules('subject', 'Subject', 'required');
                $this->form_validation->set_rules('email_message', 'Email body', 'trim|required');
                if ($this->form_validation->run() == true) {
                    $to_mail = $this->input->post('to_mail');
                    $cc_mail = $this->input->post('cc_mail');
                    $email_subject = $this->input->post('subject');
                    $email_message = $this->input->post('email_message');

                    $cc_usermail = [];
                    array_push($cc_usermail, ["email" => $cc_mail]);

                    $bcc_usermail = [];
                    array_push($bcc_usermail, ["email" => "crm@ontimegroup.com", "name" => "CRM"]);

                    if(!empty($cc_mail)){
                        $email_array = array(
                            'email' => $to_mail,
                            'subject' => $email_subject,
                            'template' => 'mails/blank_template',
                            'message' => $email_message,
                            'cc' => $cc_usermail,
                            "bcc" => $bcc_usermail,
                        );
                    } else {
                        $email_array = array(
                            'email' => $to_mail,
                            'subject' => $email_subject,
                            'template' => 'mails/blank_template',
                            'message' => $email_message,
                            "bcc" => $bcc_usermail,
                        );
                    }

                    $send_mail = send_lead_template_email($email_array);

                    
                    $assigned_to = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');
                    $assigned_to_email = $this->mcommon->specific_row_value('users', array('user_id' => $assigned_to), 'email');
                    $assigned_to_name = $this->mcommon->specific_row_value('users', array('user_id' => $assigned_to), 'first_name');
                    $subject = "Ontiem CRM ALERT - Customer Replied Your Email";

                    $message = "Dear " . $assigned_to_name . ",<br/>
                    <br/>Customer is Replied your mail. <br/>
                    <br/>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .";

                    $email_array = array(
                        'email' => $assigned_to_email,
                        'subject' => $subject,
                        'template' => 'mails/lead_template',
                        'from_name' => "ONTIME CRM ALERT",
                        'message' => $message,
                    );
                    $send_mail = send_lead_template_email($email_array);

                    // log_message('error', $send_mail);
                    $this->session->set_flashdata('alert_reply', 'success');
                    $this->session->set_flashdata('alert_message', 'Reply sent successfully.');
                } else {
                    $this->session->set_flashdata('alert_reply', 'danger');
                    $this->session->set_flashdata('alert_message', validation_errors());
                }
            }
            redirect('leads/lead/view/' . $lead_id);
        }
    }

    public function download_attachment($content_url = null){
        $content_url = $content_url ?: $this->input->get('content_url');
        $content_type = $this->input->get('content_type');
        $image_name = $this->input->get('image_name');
        if(!empty($content_url)){
            $url = 'https://94.200.55.118:5000'.$content_url;   //.'/'.$image_name;
            $api_key = '171016F2-9943-418C-AD51-56E7E7C7DF4E';
            $headers = [
                "authtoken: $api_key"
            ];
        
            $ch = curl_init();
            // Set the cURL options
            curl_setopt($ch, CURLOPT_URL, $url); // Use the URL with query parameters
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the headers
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (like verify=False in requests)
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            if(curl_errno($ch)) {
                // Handle error (e.g., display an error message)
                echo 'Error: ' . curl_error($ch);
            } else {
                // Set headers to prompt the file download in the browser
                header('Content-Type: application/octet-stream'); // Content type (change based on your file type)
                header('Content-Disposition: attachment; filename="attachment.pdf"'); // File name in download prompt
                header('Content-Length: ' . strlen($response)); // Length of the file to be downloaded
                
                // Output the content directly to the browser for download
                echo $response;
            }
            curl_close($ch);
        }
    }
}