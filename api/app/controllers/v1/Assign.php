<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: https://crm.ontimegroup.com/auth/api/v1/Auth/register');

class Assign extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('leads_model');
    }

    public function order_post()
    {
        $item_id = $this->post('item_id');
        $assigned_to = $this->post('assigned_to');
        $assigned_by = $this->post('assigned_by');

        if ($item_id == '' || $assigned_to == '' || $assigned_by == '') {
            $this->response('Parameters Missing', 400);
        } else {
            $insert = $this->mcommon->common_insert('assigned_order', array('order_item_id' => $item_id, 'assigned_user' => $assigned_to, 'assigned_by' => $assigned_by));
            if ($insert >= 1) {
                //update assigned table
                $update = $this->mcommon->common_edit('company_orders', array('is_assigned' => 1), array('order_item_id' => $item_id));
                if ($update) {
                    //TODO: send email to CSA
                    $csa_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_to));
                    $coord_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_by));

                    $receiver_email = $csa_user['email'];
                    $receiver_name =  $csa_user['first_name'];
                    $sender_email = $coord_user['email'];
                    $sender_name =  $coord_user['first_name'];

                    $subject = "NEW ORDER - Baraha's new order has been assigned to you!";
                    $message = "Dear " . $receiver_name . ",<br /><br />Al-Baraha's new order has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/orders/baraha/index.";
                    $email_array = array(
                        'email' => $receiver_email,
                        'subject' => $subject,
                        'template' => 'mails/template',
                        'from_name' => "CRM ALERT",
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                    log_message('error', $send_mail);
                    $this->response('Order has been assigned', 200);
                } else {
                    $delete = $this->mcommon->common_delete('assigned_order', array('order_item_id' => $item_id));
                    $this->response('Unable to assign order', 500);
                }
            } else {
                $this->response('Unable to assign order at present.', 500);
            }
        }
    }


    public function otgorder_post()
    {
        $item_id = $this->post('lead_id');
        $assigned_to = $this->post('assigned_to');
        $assigned_by = $this->post('assigned_by');

        if ($item_id == '' || $assigned_to == '' || $assigned_by == '') {
            $this->response('Parameters Missing', 400);
        } else {
            //update assigned table
            $update = $this->mcommon->common_edit('otg_orders', array('is_assigned' => 1, "item_status" => 103, "assigned_user" => $assigned_to), array('id' => $item_id));
            if ($update) {
                //TODO: send email to CSA
                $csa_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_to));
                $coord_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_by));

                $receiver_email = $csa_user['email'];
                $receiver_name =  $csa_user['first_name'];
                $sender_email = $coord_user['email'];
                $sender_name =  $coord_user['first_name'];

                $subject = "OnTimeGov Order - Assigned to You!";
                $message = "Dear " . $receiver_name . ",<br /><br />OnTimeGov new order has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/orders/ontimegov";
                $email_array = array(
                    'email' => $receiver_email,
                    'subject' => $subject,
                    'template' => 'mails/template',
                    'from_name' => "CRM ALERT",
                    'message' => $message,
                );
                $send_mail = send_template_email($email_array);
                log_message('error', $send_mail);
                $this->response('Order has been assigned', 200);
            } else {
                $this->response('Unable to assign order', 500);
            }
        }
    }

    public function pcr_order_post()
    {
        $item_id = $this->post('item_id');
        $assigned_to = $this->post('assigned_to');
        $assigned_by = $this->post('assigned_by');

        if ($item_id == '' || $assigned_to == '' || $assigned_by == '') {
            $this->response('Parameters Missing', 400);
        } else {
            $insert = $this->mcommon->common_insert('assigned_pcr_order', array('pcr_order_id' => $item_id, 'assigned_user' => $assigned_to, 'assigned_by' => $assigned_by));
            if ($insert >= 1) {
                //update assigned table
                $update = $this->mcommon->common_edit('pcr_order', array('is_assigned' => 1), array('pcr_order_id' => $item_id));
                if ($update) {
                    //TODO: send email to CSA
                    $csa_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_to));
                    $coord_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_by));

                    $receiver_email = $csa_user['email'];
                    $receiver_name =  $csa_user['first_name'];
                    $sender_email = $coord_user['email'];
                    $sender_name =  $coord_user['first_name'];

                    $subject = "NEW PCR TEST ORDER - Baraha's new PCR TEST order has been assigned to you!";
                    $message = "Dear " . $receiver_name . ",<br /><br />Al-Baraha's new PCR TEST order has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/orders/baraha/pcr_index.";
                    $email_array = array(
                        'email' => $receiver_email,
                        'subject' => $subject,
                        'template' => 'mails/template',
                        'from_name' => "CRM ALERT",
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                    log_message('error', $send_mail);
                    $this->response('PCR Order has been assigned', 200);
                } else {
                    $delete = $this->mcommon->common_delete('assigned_pcr_order', array('pcr_order_id' => $item_id));
                    $this->response('Unable to assign order', 500);
                }
            } else {
                $this->response('Unable to assign order at present.', 500);
            }
        }
    }

    public function enquiry_post()
    {
        $item_id = $this->post('item_id');
        $assigned_to = $this->post('assigned_to');
        $assigned_by = $this->post('assigned_by');

        if ($item_id == '' || $assigned_to == '' || $assigned_by == '') {
            $this->response('Parameters Missing', 400);
        } else {
            $insert = $this->mcommon->common_insert('assigned_enquiries', array('enquiry_id' => $item_id, 'assigned_user' => $assigned_to, 'assigned_by' => $assigned_by));
            if ($insert >= 1) {
                //update assigned table
                $update = $this->mcommon->common_edit('company_enquiries', array('is_assigned' => 1), array('enquiry_id' => $item_id));
                if ($update) {
                    //TODO: send email to CSA
                    $csa_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_to));
                    $coord_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_by));

                    $receiver_email = $csa_user['email'];
                    $receiver_name =  $csa_user['first_name'];
                    $sender_email = $coord_user['email'];
                    $sender_name =  $coord_user['first_name'];

                    $subject = "NEW ENQUIRY - Baraha's new enquiry has been assigned to you!";
                    $message = "Dear " . $receiver_name . ",<br /><br />Al-Baraha's new enquiry has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/enquiries/baraha/index.";
                    $email_array = array(
                        'email' => $receiver_email,
                        'subject' => $subject,
                        'template' => 'mails/template',
                        'from_name' => "CRM ALERT",
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                    log_message('error', $send_mail);
                    $this->response('Enquiry has been assigned', 200);
                } else {
                    $delete = $this->mcommon->common_delete('assigned_enquiries', array('enquiry_id' => $item_id));
                    $this->response('Unable to assign enquiry', 500);
                }
            } else {
                $this->response('Unable to assign enquiry at present.', 500);
            }
        }
    }


    public function complaint_post()
    {
        $item_id = $this->post('item_id');
        $assigned_to = $this->post('assigned_to');
        $assigned_by = $this->post('assigned_by');

        if ($item_id == '' || $assigned_to == '' || $assigned_by == '') {
            $this->response('Parameters Missing', 400);
        } else {
            $insert = $this->mcommon->common_insert('assigned_complaints', array('enquiry_id' => $item_id, 'assigned_user' => $assigned_to, 'assigned_by' => $assigned_by));
            if ($insert >= 1) {
                //update assigned table
                $update = $this->mcommon->common_edit('company_complaints', array('is_assigned' => 1), array('enquiry_id' => $item_id));
                if ($update) {
                    //TODO: send email to CSA
                    $csa_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_to));
                    $coord_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_by));

                    $receiver_email = $csa_user['email'];
                    $receiver_name =  $csa_user['first_name'];
                    $sender_email = $coord_user['email'];
                    $sender_name =  $coord_user['first_name'];

                    $subject = "NEW COMPLAINT - Baraha's customer complaint has been assigned to you!";
                    $message = "Dear " . $receiver_name . ",<br /><br />Al-Baraha's customer complaint has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/complaints/baraha/index.";
                    $email_array = array(
                        'email' => $receiver_email,
                        'subject' => $subject,
                        'template' => 'mails/template',
                        'from_name' => "CRM ALERT",
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                    log_message('error', $send_mail);

                    $this->response('Complaint has been assigned', 200);
                } else {
                    $delete = $this->mcommon->common_delete('assigned_complaints', array('enquiry_id' => $item_id));
                    $this->response('Unable to assign complaint', 500);
                }
            } else {
                $this->response('Unable to assign complaint at present.', 500);
            }
        }
    }


    public function meeting_post()
    {
        $item_id = $this->post('item_id');
        $assigned_to = $this->post('assigned_to');
        $assigned_by = $this->post('assigned_by');

        if ($item_id == '' || $assigned_to == '' || $assigned_by == '') {
            $this->response('Parameters Missing', 400);
        } else {
            $insert = $this->mcommon->common_insert('assigned_meetings', array('enquiry_id' => $item_id, 'assigned_user' => $assigned_to, 'assigned_by' => $assigned_by));
            if ($insert >= 1) {
                //update assigned table
                $update = $this->mcommon->common_edit('company_meetings', array('is_assigned' => 1), array('enquiry_id' => $item_id));
                if ($update) {
                    //TODO: send email to CSA
                    $csa_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_to));
                    $coord_user = $this->mcommon->specific_row('users', array('user_id' => $assigned_by));

                    $receiver_email = $csa_user['email'];
                    $receiver_name =  $csa_user['first_name'];
                    $sender_email = $coord_user['email'];
                    $sender_name =  $coord_user['first_name'];

                    $subject = "NEW MEETING - Baraha's new meeting request has been assigned to you!";
                    $message = "Dear " . $receiver_name . ",<br /><br />Al-Baraha's new meeting request by customer has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/meetings/baraha/index.";
                    $email_array = array(
                        'email' => $receiver_email,
                        'subject' => $subject,
                        'template' => 'mails/template',
                        'from_name' => "CRM ALERT",
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                    log_message('error', $send_mail);

                    $this->response('Meeting has been assigned', 200);
                } else {
                    $delete = $this->mcommon->common_delete('assigned_meetings', array('enquiry_id' => $item_id));
                    $this->response('Unable to assign meeting', 500);
                }
            } else {
                $this->response('Unable to assign meeting at present.', 500);
            }
        }
    }

    public function lead_post()
    {
        $lead_id = $this->post('lead_id');
        $assigned_to = $this->post('assigned_to');
        $assigned_by = $this->post('assigned_by');
        // echo "<pre>";
        // print_r($this->db);
        // echo "</pre>";
        // exit();
        if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
            $this->response('Parameters Missing', 400);
        } else {
            if($assigned_to == 'Unassigned' || $assigned_to == 'unassigned') {
                $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                // $update = $this->mcommon->common_edit('leads', array('created_group_id' => 58, 'assigned_group_id' => 58, 'is_assigned' => 0), array('id' => $lead_id));	
                $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => 58, 'is_assigned' => 0), array('id' => $lead_id));	
                $log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => "lead has been moved to Unassigned", 'action_by' => $this->auth_user_id, 'status_id' => 304);
                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                $this->response('Lead has been unassigned successfully!', 200);
            }
            $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
            $insert_array = array(
                'lead_id' => $lead_id,
                'assigned_by' => $assigned_by,
                'assigned_to' => $assigned_to,
                'assigned_on' => date('Y-m-d H:i:s')
            );
            // echo "<br>";
            // echo "<br> ";
            // print_r($insert_array);
            $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

            $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
            $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();
            $prev_lead_details = $this->mcommon->specific_row('leads',array('id'=>$lead_id));

            $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
            // print_r($log_insert_array);
            $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

            $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));	

            // echo "Log: ".$log_insert."<br>";
            // echo "ERROR: ";
            // print_r($this->db->error());
            // exit();
            if ($insert > 0) {
                $lead_status = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_status');
                if($lead_status == 642 || $lead_status == 643 || $lead_status == 305){
                    $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1), array('id' => $lead_id));
                } else {
                    $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));
                }
                $task_created = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'task_created');
                if($task_created == 1){
                    $update = $this->mcommon->common_edit('leads', array('task_assigned' => $assigned_to), array('id' => $lead_id)); 
                } else {
                    $lead_parent_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_parent_id');
                    if($lead_parent_id != 0){
                        $service_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'service_id');
                        $service_details = $this->db->select("ocs.service_id, os.*")
                                                ->from("ontime_services os")
                                                ->join("ontime_category_services_ ocs", "os.id = ocs.gc_service_id")
                                                ->where("ocs.service_id", $service_id)
                                                ->get()->result_array();
                        if(!empty($service_details)) {
                            $is_task = $service_details[0]['is_task'];
                            $gc_service_id = $service_details[0]['id'];
                            // if($is_task == 1 && in_array($gc_service_id,[95,93,2])) {
                                $task_created = 1;
                                $log_insert_task_array = array('action_id' => 447, 'lead_id' => $lead_parent_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => "The task for Sublead-".$lead_id." has been assigned to " . $csa->first_name, 'action_by' => $assigned_by, 'status_id' => 646);
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_task_array);
                                $update_task_array = array('task_created' => 1, 'task_created_at' => date('Y-m-d H:i:s'), 'task_assigned' => $assigned_to,'task_status'=>'open');
                                $update_lead = $this->mcommon->common_edit('leads', $update_task_array, array('id' => $lead_id));
                            // }
                        }
                    }
                }

                if ($update) {

                    $data = array(
                        'userid' => $assigned_to,
                        'leadid' => $lead_id
                    );

                    $addBizLead = $this->biznew($data);

                    if($addBizLead){
                    $updatebiz = $this->mcommon->common_edit('leads', array('is_biz_lead' => 1), array('id' => $lead_id));
                        //create action log
                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead moved to Business Setup <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    }

                    //create action log
                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $receiver_email = $csa->email;
                    $receiver_name =  $csa->first_name;
                    $sender_email = $coordinator->email;
                    $sender_name =  $coordinator->first_name;

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
                    if ($lead_det["pos_pmt_number"] != NULL) $message .= "<br>Receipt Number: " . $lead_det["pos_pmt_number"]; 
                    else if($parent_lead_det["pos_pmt_number"] != NULL) $message .= "<br>Receipt Number: " . $parent_lead_det["pos_pmt_number"]; 

                    $message .= "<br>Remarks: " . $lead_det["remarks"];

                    $email_array = array(
                        'email' => $receiver_email,
                        'subject' => $subject,
                        'template' => 'mails/template',
                        'from_name' => "CRM ALERT",
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                    log_message('error', $send_mail);

                    if($csa->role_id == 84 && $coordinator->role_id == 84 && $prev_lead_details['lead_status'] == 320){
                        $update = $this->mcommon->common_edit('leads', array('lead_status' => 320), array('id' => $lead_id));
                    
                        $elgi_check_log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'GoldenCube WebApp Profile Submitted for Eligibility Checking.', 'action_by' => $assigned_by, 'status_id' => 320);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $elgi_check_log_insert_array);
                    }

                    $this->response('Lead has been assigned successfully!', 200);
                } else {
                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                    // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                    $this->response('Unable to assign the lead at present. Please try again later', 500);
                }
            } else {
                $this->response('Unable to assign lead at present.', 500);
            }
        }
    }


    public function biznew($data=''){

            $user_id = $data['userid'];
            $leadid = $data['leadid'];
            $group_branch_id = 107;

                $this->db->select('*');
                $this->db->from('groups g');
                $this->db->join('group_members gm', 'gm.group_id = g.group_id');
                $this->db->where('gm.user_id', $user_id);
                $this->db->where('g.group_branch_id', $group_branch_id);

                $query = $this->db->get();
                $assigned_group = $query->row_array();

                if (!empty($assigned_group) && $assigned_group['group_branch_id'] == $group_branch_id) {
                    //$result = true;

                    $this->db->select('l.id, CONCAT(lu.first_name, " ", lu.last_name) as name, CONCAT(lu.country_code, "", lu.mobile) as contact, lu.email, l.remarks');
                    $this->db->from('leads l');
                    $this->db->join('lead_users lu', 'lu.user_id = l.customer_id');
                    $this->db->where('l.id', $leadid);

                    $query2 = $this->db->get();
                    $results = $query2->row_array();

                    if (!empty($results)) {

                            $curl = curl_init();
                            $req = "lead_name=" . $results['name'] . "&lead_email=" . $results['email'] . "&lead_contact=" . $results['contact'] . "&lead_remarks=" . $results['remarks'] . "&group_crm_lead_id=" . $leadid;
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

                            $result = json_decode($response);

                    }

                } else {
                    $result = false;
                }

                return $result;

    }

    /*private function send_assigned_email($assigned_by,$assigned_to,$type)
    {
        
        $csa_user = $this->mcommon->specific_row('users',array('user_id'=>$assigned_to));
        $coord_user = $this->mcommon->specific_row('users',array('user_id'=>$assigned_by));

        $receiver_email = $csa_user['email'];
        $receiver_name =  $csa_user['first_name'];
        $sender_email = $coord_user['email'];
        $sender_name =  $coord_user['first_name'];


        if($type == 'order')
        {
            $subject = "NEW ORDER - Baraha's new order has been assigned to you!";
            $message = "Dear ".$receiver_name.",<br /><br />Al-Baraha's new order has been assigned to you by <strong>".$sender_name."</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/orders/baraha/index.";
        }

        if($type == 'meeting')
        {
            $subject = "NEW MEETING - Baraha's new meeting request has been assigned to you!";
            $message = "Dear ".$receiver_name.",<br /><br />Al-Baraha's new meeting request by customer has been assigned to you by <strong>".$sender_name."</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/meetings/baraha/index.";

        }

        if($type == 'complaint')
        {
            $subject = "NEW COMPLAINT - Baraha's customer complaint has been assigned to you!";
            $message = "Dear ".$receiver_name.",<br /><br />Al-Baraha's customer complaint has been assigned to you by <strong>".$sender_name."</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/complaints/baraha/index.";
        }

        if($type == 'enquiry')
        {
            $subject = "NEW ENQUIRY - Baraha's new enquiry has been assigned to you!";
            $message = "Dear ".$receiver_name.",<br /><br />Al-Baraha's new enquiry has been assigned to you by <strong>".$sender_name."</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/enquiries/baraha/index.";
        }

        //SEND EMAIL TO CUSTOMER
        $email_array = array(
            'email' => $receiver_email,
            'subject' => $subject,
            'template' => 'mails/template',
            'from_name' => "CRM ALERT",
            'message' => $message,
        );
        $send_mail = send_template_email($email_array);
        log_message('error', $send_mail);

    }*/
}
