<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: https://crm.ontimegroup.com/auth/api/v1/Auth/register');

class Order extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('authentication_model');
        $this->load->model('leads_model');
    }

    public function item_get()
    {
        $order_item_id = $this->get('item_id');
        $order_item_details = $this->order_model->order_item_get($order_item_id);
        $this->response($order_item_details, 200);
    }
    public function attachments_get()
    {
        $order_item_id = $this->get('item_id');
        $order_attachments = $this->order_model->order_item_attachments($order_item_id);
        $this->response($order_attachments, 200);
    }

    public function baraha_confirm_get()
    {
        $order_id = $this->get('order_id');
        $order_id = strtolower($order_id);
        if (substr($order_id, 0, 1) !== "b") {
            $this->response(array('status' => 'error', 'result' => 'Order id format is wrong. It should start with the character B'), 400);
        }
        $order_id = str_replace("b", "", $order_id);
        $order_id = str_replace("-", ",", $order_id);
        $oe_id = explode(",", $order_id);
        $order_id = $oe_id[0];
        $order_item_id = $oe_id[1];

        if ($order_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order id is missing'), 400);
        }
        if ($order_item_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order item id is missing'), 400);
        }

        $order_exist = $this->mcommon->specific_record_counts('orders', array('o_id' => $order_id));
        if ($order_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " doesn't exist"), 204);
        }

        //check order_item_id exist
        $order_item_exist = $this->mcommon->specific_record_counts('order_items', array('item_id' => $order_item_id, 'order_id' => $order_id));
        if ($order_item_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " and item #" . $order_item_id . " doesn't exist"), 204);
        }

        $oid = $this->order_model->order_item_for_queue($order_item_id);

        $mp = date('Y-m-d', strtotime($oid['menstrual_period']));
        if ($mp == '1970-01-01') {
            $mp = '';
        }
        $sc3 = $oid['subcategory_three'];
        if ($sc3 == 'None' || $sc3 == 'none') {
            $sc3 = 'Default';
        }

        $order_details_array = array(
            'BranchCode' => 'BMC',
            'IdNo' => 'null',
            'FirstName' => $oid['first_name'],
            'LastName' => $oid['last_name'],
            'Mobile' => "971" . substr($oid['customer_mobile'], -9),
            'Email' => $oid['customer_email'],
            'ClientType' => $oid['category_name'],
            'ClientCategory' => $sc3,
            'ApplicationType' => $oid['sub_category_name'],
            'BookingReference' => 'B' . $oid['order_id'] . '-' . $oid['item_id'],
            'ApplicationNo' => $oid['med_number'],
            'IsFemale' => ($oid['gender'] == 1) ? 'Male' : 'Female',
            'IsPregnant' => ($oid['pregnancy'] == 1) ? 'true' : 'false',
            'LastMenstrualPeriod' => $mp,
            'IsAbortionHistory' => ($oid['abortion'] == 1) ? 'true' : 'false',
            'IsTakingPills' => ($oid['contraceptive_pills'] == 1) ? 'true' : 'false',
            'IsXrayAgreed' => ($oid['x_ray'] == 1) ? 'true' : 'false',
        );
        $this->response($order_details_array, 200);
    }

    public function update_mrn_post()
    {
        $order_id = $this->post('order_id');
        $mrn = $this->post('mrn');
        $ref2 = $this->post('ref2');

        if ($mrn == '' || $order_id == '' || $ref2 == '') {
            $this->response(array('status' => 'error', 'result' => 'All parameters required. (order_id,mrn,ref2)'), 400);
        }

        $order_id = strtolower($order_id);
        if (substr($order_id, 0, 1) !== "b") {
            $this->response(array('status' => 'error', 'result' => 'Order id format is wrong. It should start with the character B'), 400);
        }
        $order_id = str_replace("b", "", $order_id);
        $order_id = str_replace("-", ",", $order_id);
        $oe_id = explode(",", $order_id);
        $order_id = $oe_id[0];
        $order_item_id = $oe_id[1];



        if ($order_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order id is missing'), 400);
        }
        if ($order_item_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order item id is missing'), 400);
        }

        $order_exist = $this->mcommon->specific_record_counts('orders', array('o_id' => $order_id));
        if ($order_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " doesn't exist"), 204);
        }

        //check order_item_id exist
        $order_item_exist = $this->mcommon->specific_record_counts('order_items', array('item_id' => $order_item_id, 'order_id' => $order_id));
        if ($order_item_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " and item #" . $order_item_id . " doesn't exist"), 204);
        }


        $update_mrn = $this->mcommon->common_edit('order_items', array('med_number' => $mrn, 'ref2' => $ref2, 'item_status' => 102), array('item_id' => $order_item_id, 'order_id' => $order_id));


        if ($update_mrn) {
            //get customer_id
            $user_id = $this->mcommon->specific_row_value('orders', array('o_id' => $order_id), 'user_id');

            $customer_name = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'first_name');
            $customer_email = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'email');

            //send email to customer
            $receiver_name = $customer_name;
            $receiver_email = $customer_email;

            $message = "Dear " . $receiver_name . ",<br /><br /> Medical typing has been done for your order. To proceed to your medical examination please login to your account and book appointment.<br />";

            $message_details = '<br /><br /><a href="https://crm.ontimegroup.com/appointment">Login to book appointment</a><br /><br />';
            $message_details .= "<br /><br />As each person�s requirement is different, our team of consultants is available round-the-clock ensuring to be your best and the most well-informed guide.<br /><br />";
            $message .= $message_details;


            //SEND EMAIL TO CUSTOMER
            $email_array = array(
                'email' => $receiver_email,
                'subject' => 'Medical Typing Completed - Book Appointment',
                'template' => 'mails/template',
                'from_name' => 'BARAHA',
                'message' => $message,
            );
            $send_mail = send_template_email($email_array);

            log_message('error', $send_mail);

            $this->response(array('status' => 'success', 'result' => 'Medical reference number and Ref2 has been updated. And an email has been sent to customer'), 200);
        } else {
            $this->response(array('status' => 'error', 'result' => 'Unable to updaete order.'), 500);
        }
    }

    public function baraha_complete_get()
    {
        $order_id = $this->get('order_id');
        $order_id = strtolower($order_id);
        if (substr($order_id, 0, 1) !== "b") {
            $this->response(array('status' => 'error', 'result' => 'Order id format is wrong. It should start with the character B'), 400);
        }
        $order_id = str_replace("b", "", $order_id);
        $order_id = str_replace("-", ",", $order_id);
        $oe_id = explode(",", $order_id);
        $order_id = $oe_id[0];
        $order_item_id = $oe_id[1];

        if ($order_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order id is missing'), 400);
        }
        if ($order_item_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order item id is missing'), 400);
        }

        $order_exist = $this->mcommon->specific_record_counts('orders', array('o_id' => $order_id));
        if ($order_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " doesn't exist"), 204);
        }

        //check order_item_id exist
        $order_item_exist = $this->mcommon->specific_record_counts('order_items', array('item_id' => $order_item_id, 'order_id' => $order_id));
        if ($order_item_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " and item #" . $order_item_id . " doesn't exist"), 204);
        }

        //check if the order closed already
        $order_closed = $this->mcommon->specific_record_counts('order_items', array('item_id' => $order_item_id, 'order_id' => $order_id, 'is_complete' => 1));
        if ($order_closed == 1) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " and item #" . $order_item_id . " has been already set as completed"), 200);
        }

        $update_order = $this->mcommon->common_edit('order_items', array('is_complete' => 1, 'item_status' => 105), array('item_id' => $order_item_id, 'order_id' => $order_id));
        if ($update_order) {
            $this->response(array('status' => 'success', 'result' => "Order completed successfully"), 200);
        } else {
            $this->response(array('status' => 'error', 'result' => "Unable to complete the order."), 500);
        }
    }

    public function pcr_get()
    {
        $order_id = $this->get('order_id');

        //Logic to remove prefix
        $order_id = strtolower($order_id);
        $order_id = str_replace("pcr", "", $order_id);

        if (substr($order_id, 0, 3) !== "pcr") {
            $this->response(array('status' => 'error', 'result' => 'Order id format is wrong. It should start with the character PCR'), 400);
        }

        //check whethere there is examinee id
        if (strpos($order_id, '-') !== false) {
            $order_id = str_replace("-", ",", $order_id);
            $oe_id = explode(",", $order_id);
            $order_id = $oe_id[0];
            $examinee_id = $oe_id[1];
        } else {
            $examinee_id = 0;
        }



        if ($order_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order id is missing'), 400);
        }

        //check order_item_id exist
        $order_exist = $this->mcommon->specific_record_counts('pcr_order', array('pcr_order_id' => $order_id));
        if ($order_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order item with the number #" . $order_id . " doesn't exist"), 204);
        }

        $pcr_order = $this->mcommon->specific_row('pcr_order', array('pcr_order_id' => $order_id));
        $category = $this->mcommon->specific_row_value('pcr_categories', array('pcr_cat_id' => $pcr_order['pcr_category_id']), 'category_name');
        $sub_category = $this->mcommon->specific_row_value('pcr_subcategories', array('pcr_subcat_id' => $pcr_order['pcr_sub_category_id']), 'subcategory_name');
        $order_amount = 'AED ' . $pcr_order['total_amount'];

        if ($examinee_id == 0) {
            $pcr_order_items = $this->mcommon->specific_fields_records_all('pcr_order_items', array('pcr_order_id' => $order_id));
        } else {
            //check order_item_id exist
            $examinee_exist = $this->mcommon->specific_record_counts('pcr_order_items', array('pcr_order_item_id' => $examinee_id));
            if ($examinee_exist == 0) {
                $this->response(array('status' => 'error', 'result' => "Examinee with the number #" . $examinee_id . " doesn't exist"), 204);
            }

            $pcr_order_items = $this->mcommon->specific_fields_records_all('pcr_order_items', array('pcr_order_id' => $order_id, 'pcr_order_item_id' => $examinee_id));
        }

        $pcr_payment_details = $this->mcommon->specific_fields_records_all('payments', array('pid' => $pcr_order['payment_id']));
        $completed_tests = $this->mcommon->specific_record_counts('pcr_order_items', array('pcr_order_id' => $order_id, 'is_completed' => 1));
        $pending_tests = $this->mcommon->specific_record_counts('pcr_order_items', array('pcr_order_id' => $order_id, 'is_completed' => 0));

        $order_details = array('order_id' => $pcr_order['pcr_order_id'], 'category' => $category, 'sub_category' => $sub_category, 'order_amount' => $order_amount, 'preferred_date' => $pcr_order['preferred_date'], 'is_completed' => $pcr_order['is_completed'], 'no_of_examinees' => count($pcr_order_items), 'completed_tests' => $completed_tests, 'pending_tests' => $pending_tests);
        $order_array = array('order' => $order_details, 'payment' => $pcr_payment_details, 'examinees' => $pcr_order_items);
        $this->response(array($order_array), 200);
    }

    public function pcr_complete_get()
    {
        $order_id = $this->get('order_id');
        //Logic to remove prefix
        $order_id = strtolower($order_id);
        if (substr($order_id, 0, 3) !== "pcr") {
            $this->response(array('status' => 'error', 'result' => 'Order id format is wrong. It should start with the character PCR'), 400);
        }

        $order_id = str_replace("pcr", "", $order_id);
        $order_id = str_replace("-", ",", $order_id);
        $oe_id = explode(",", $order_id);
        $order_id = $oe_id[0];
        $examinee_id = $oe_id[1];

        if ($order_id == '' || $examinee_id == '') {
            $this->response(array('status' => 'error', 'result' => 'Order id or examinee id is missing'), 400);
        }

        //check order_item_id exist
        $order_exist = $this->mcommon->specific_record_counts('pcr_order', array('pcr_order_id' => $order_id));
        if ($order_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " doesn't exist"), 204);
        }

        //check order_item_id exist
        $examinee_exist = $this->mcommon->specific_record_counts('pcr_order_items', array('pcr_order_item_id' => $examinee_id));
        if ($examinee_exist == 0) {
            $this->response(array('status' => 'error', 'result' => "Examinee with the number #" . $examinee_id . " doesn't exist"), 204);
        }

        $update_order_item = $this->mcommon->common_edit('pcr_order_items', array('is_completed' => 1), array('pcr_order_item_id' => $examinee_id));
        if ($update_order_item) {
            //check for open examinies
            $examinee_order_count = $this->mcommon->specific_record_counts('pcr_order_items', array('is_completed' => 0, 'pcr_order_id' => $order_id));
            //if all examinees are set completed change the order status to completed
            if ($examinee_order_count == 0) {
                $update_order = $this->mcommon->common_edit('pcr_order', array('is_completed' => 1), array('pcr_order_id' => $order_id));
            }
            $this->response(array(array('status' => 'success', 'result' => "Examinee test status is set as completed")), 200);
        } else {
            $this->response(array('status' => 'error', 'result' => "Unable to set the status."), 500);
        }
    }

    public function se_complete_post()
    {
        // exit();

        try {
            $order_id = $this->post('order_id');

            $order_id = trim($order_id);
            if (substr($order_id, 0, 4) !== "SEOT") {
                $this->response(array('status' => 'error', 'result' => 'Order id format is wrong. It should start with the character SEOT'), 400);
            }

            $order_id = str_replace("SEOT", "", $order_id);
            $order_id = (int) $order_id;

            $order_remarks = $this->post('remarks');
            //Logic to remove prefix

            if ($order_id == '') {
                $this->response(array('status' => 'error', 'result' => 'Order id is missing'), 400);
            }

            //check order_item_id exist
            $order_exist = $this->mcommon->specific_record_counts('smartejari_orders', array('order_id' => $order_id));
            if ($order_exist == 0) {
                $this->response(array('status' => 'error', 'result' => "Order with the number #" . $order_id . " doesn't exist"), 403);
            }

            $update_order = $this->mcommon->common_edit('smartejari_orders', array('is_completed' => 1, 'order_status' => 101, "pos_remarks" => $order_remarks), array('order_id' => $order_id));
            if ($update_order) {
                $this->response(array(array('status' => 'success', 'result' => "Order status is set as completed")), 200);
            } else {
                $this->response(array('status' => 'error', 'result' => "Unable to set the status."), 500);
            }
        } catch (Exception $e) {
            $this->response(array('status' => 'error', 'result' => "Informal Request. Kindly check the request params."), 500);
        }
    }

    /**
     *  ONTIME GOV
     */
    public function sendemail_get(){
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
                            'branch_id' => $lead_det["branch_id"],
                        );
                        $send_mail = send_template_email($email_array);
                        echo $send_mail;

    }
    public function ontimegov_post()
    {
        $name = $this->post('name');
        $email = $this->post('email');
        $mobile = $this->post('mobile');
        $invoice_to = $this->post('invoice_to');
        $order_ref = $this->post('order_ref');
        $trans_name = $this->post('trans_name');
        $card_num = $this->post('card_num');
        $transaction_number = $this->post('transaction_number');
        $receipt_no = $this->post('receipt_no');
        $sla = $this->post('sla');
        $order_id = $this->post('order_id');
        $order_details_id = $this->post('order_details_id');
        $service_id = $this->post('service_id');
        $net_total = $this->post('net_total');
        $description = $this->post('description');
        $order_details = json_decode($this->post('order_details'));
        $order_status = 301;
        $pos_category_id = $this->post('pos_category_id');
        $pos_service_id = $this->post('pos_service_id');
        $payment_mode = $this->post('payment_mode');
        $postgovt_fee = $this->post('govt_fee');
        $posttyping_fee = $this->post('typing_fee');
        $postother_fee = $this->post('other_fee');
        $postdelivery_fee = 115;
        $postdelivery_id= $this->post('delivery_id');
        $number =  $mobile ; // mobile number
        $number = (strpos($number, '971') === 0) ? substr($number, 3) : $number;
        $mobile = $number;

        // if($order_id=="6756"){
        //     $net_total="1235.89";
        // }


        if($payment_mode=='offline'){

            if ($name == '' || $email == '' || $mobile == '' || $trans_name == '' || $sla == '' || $order_id == '' || $order_details_id == '' || $service_id == '' || $net_total == '' || $description == '') {
                $this->response('Offline - Parameters Missing', 400);
            }

        }else{

            if ($name == '' || $email == '' || $mobile == '' || $order_ref == '' || $trans_name == '' || $card_num == '' || $transaction_number == '' || $receipt_no == '' || $sla == '' || $order_id == '' || $order_details_id == '' || $service_id == '' || $net_total == '' || $description == '') {
                $this->response('Online - Parameters Missing', 400);
            }

        }

        $order_exist = $this->mcommon->specific_record_counts('leads', array('otg_order_id' => $order_id));
        if ($order_exist != 0) {
            $exist_lead_id = $this->mcommon->specific_row_value('leads', array('otg_order_id' => $order_id), 'id');
            $this->response('Already lead exists against this - ORDER' . $exist_lead_id, 200);
            // $this->response(array('status' => 'error', 'result' => "Already lead exists against the order id"), 204);
        }
        

        //Customer Logic
        // $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile));
        // $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $email));

        // if ($check_email_exists != 0) {
        //     $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        // }

        // if ($check_mobile_exists != 0) {
        //     $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $mobile), 'user_id');
        // }
        $check_lead_user = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile,'email' => $email));

        if ($check_lead_user != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email,'mobile' => $mobile), 'user_id');
        }

        // if ($check_mobile_exists == 0 && $check_email_exists == 0) {
        if ($check_lead_user == 0) {
            $password = 'Welcome@123';
            $confirm_password = 'Welcome@123';
            $auth_level = '4';
            $referal_code = time();
            $user_hashed_password = $this->authentication->hash_passwd($password);
            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $mobile,
                'referal_code' => $referal_code,
                'first_name' => $name,
                'passwd' => $user_hashed_password,
                'email' => trim($email),
                'confirm_password' => $user_hashed_password,
            ];
            $user_data['user_id'] = $this->authentication_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');
            $user_data['otp'] = rand(1000, 9000);
            $user_data['email_otp'] = rand(1000, 9000);
            $user_data['banned'] = '0';
            $user_data['role_id'] = '4';
            $user_data['country'] = 'United Arab Emirates';
            $user_data['country_code'] = '971';
            $insert = $this->mcommon->common_insert("lead_users", $user_data);

            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }


        if ($user_id != '') {
            $insert_array = array(
                'user_id' => $user_id,
                'pos_category_id' => $pos_category_id,
                'pos_service_id' => $pos_service_id,
                'order_ref' => $order_ref,
                'trans_name' => $trans_name,
                'card_num' => $card_num,
                'transaction_number' => $transaction_number,
                'receipt_no' => $receipt_no,
                'sla' => $sla,
                'order_id' => $order_id,
                'order_details_id' => $order_details_id,
                'service_id' => $service_id,
                'net_total' => $net_total,
                'description' => $description,
                'order_details' => json_encode($order_details),
                'item_status' => $order_status,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert = $this->mcommon->common_insert('otg_orders', $insert_array);

            $branch_id = 119;
            $category_id = 10009;
            $service = $trans_name;
            $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

            if (!empty($service_exist)) {
                $service_id = $service_exist["id"];
            } else {
                $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                $service_id = (int) $service_id;
                $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
            }
            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');

            //Leads
            $insert_lead_array = array(
                'customer_id' => $user_id,
                'branch_id' => $branch_id,
                'category_id' => $category_id,
                'service_id' => $service_id,
                'lead_created_by' => 4294967295,
                'lead_added_on' => date('Y-m-d H:i:s'),
                'contactable_date' => date('Y-m-d H:i:s'),
                'lead_status' => 301,
                'order_receipt' => 0,
                'remarks' => $description,
                'is_assigned' => 0,
                'otg_order_id' => $order_id,
                'otg_order_detail_id' => $order_details_id,
                'lead_from' => 'OntimeGOV',
                'created_group_id' => $created_group_id,
                'invoice_to' => $invoice_to
            );
            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
            $parent_lead_id = $insert_lead_id;

            $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTimeGov Web Order #' . $order_id, 'action_by' => 4294967295, 'status_id' => 301);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

            $lead_id = $parent_lead_id;

            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'pos_user_id');     //  info@ontimegov.com
            if ($user_pos == 0 || $user_pos == NULL)
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'employee_id');     // info@ontimegov.com
            // if ($user_pos == 0 || $user_pos == NULL)
            //     $user_pos = "crmonline";

            $pg_reponse = $this->post('pg_response');
            $responseData = json_decode($pg_reponse, true);

            $paymentAmount = $responseData['_embedded']['payment'][0]['amount']['value'];
            $Amount = ((float)$paymentAmount) / 100;

            $req['pg_response'] = array(
                'Language'  => $responseData['language'],
                'TransactionID' => $responseData['reference'],
                'TransactionState'  => $responseData['_embedded']['payment'][0]['state'],
                'PaymentLink'  => $responseData['merchantAttributes']['redirectUrl'],
                'ResponseCode'  => $responseData['_embedded']['payment'][0]['authResponse']['resultCode'],
                'ResponseMessage'  => $responseData['_embedded']['payment'][0]['authResponse']['resultMessage'],
                'ApprovalCode' => $responseData['_embedded']['payment'][0]['authResponse']['authorizationCode'],
                'AuthorizationCode' => $responseData['_embedded']['payment'][0]['authResponse']['authorizationCode'],
                'MID' => $responseData['_embedded']['payment'][0]['authResponse']['mid'],
                'Account' => $responseData['_embedded']['payment'][0]['paymentMethod']['name'],
                'CardBrand' => $responseData['_embedded']['payment'][0]['paymentMethod']['name'],
                'CardNumber' => $responseData['_embedded']['payment'][0]['paymentMethod']['pan'],
                'PayerInformation' => $responseData['_embedded']['payment'][0]['paymentMethod']['pan'],
                'CardToken' => $responseData['_embedded']['payment'][0]['paymentMethod']['pan'],
                'CardHolderName' => $responseData['_embedded']['payment'][0]['paymentMethod']['cardholderName'],
                'CardExpiry' => $responseData['_embedded']['payment'][0]['paymentMethod']['expiry'],
                'UniqueID' => $responseData['_id'],
                'CurrencyCode' => $responseData['_embedded']['payment'][0]['amount']['currencyCode'],
                'Amount' => $Amount,    //$responseData['_embedded']['payment'][0]['amount']['value'],
                'Action' => $responseData['action'],
                'OutletID' => $responseData['outletId'],
                'MerchantReference' => $responseData['merchantDetails']['reference'],
                'MerchantName' => $responseData['merchantDetails']['name'],
                'MerchantCompanyURL' => $responseData['merchantDetails']['companyUrl'],
                'MerchantEmail' => $responseData['merchantDetails']['email'],
                'MerchantMobile' => $responseData['merchantDetails']['mobile'],
            );

            // $req["Customer"] = array("Cust_EngName" => $name, "Cust_Mobile" => $mobile, "Cust_Email" => $email);
            $req["Customer"] = array("Cust_EngName" => $invoice_to, "Cust_Mobile" => $mobile, "Cust_Email" => $email);
            $req["OrderRef"] = "SE" . $order_id . "-" . $insert_log . '-OTLDPMET' . $parent_lead_id;
            // $req["Payment"] = array("ActAmt" => $net_total, "OnlinePaymentRef" => $transaction_number);

            $this->db->update("lead_action_log", array('remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTimeGov Web Order #SE' . $order_id . " under the OrderRef #" . $req["OrderRef"]), array("id" => $insert_log));

            // $action_id
            $req["ServDescription"] = $trans_name;
            $req["salesorderdtl"] = [];

            // $req["User"] = ["User_ID" => "crmonline"];
            $req["User"] = ["User_ID"=> $user_pos];
            $req["Payment_Type"] = "ONLINE";

            // POS Changes 
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
            $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
            $lead_det = $this->leads_model->lead_details($lead_id);

            if(!empty($lead_det["lead_zoho_id"])){
                $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                if(!empty($lead_created_by)){
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                    $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                } else {
                    $created_by_user = '';
                }

                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "OnlinePaymentRef" => $transaction_number,
                    "CampaignSource" => $lead_det["lead_ad_campaign"],
                    "ZohoLeadSource" => $lead_det["lead_source"],
                    "CampaignId" => $lead_det["lead_ad_campaign_id"],
                    "ZohoLeadId" => $lead_det["lead_zoho_id"],
                    "LeadFrom" => 'Zoho',
                    "CRMLeadId" => $lead_id,
                    "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                );
                
            } else {
                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "OnlinePaymentRef" => $transaction_number,
                    "LeadSource" => 'Website',
                    "LeadFrom" => $lead_det["lead_from"],
                    "CRMLeadId" => $lead_id,
                    "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
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
            // print_r($raw_response);
            $res_json = json_decode($raw_response);
            // print_r($res_json);
            if (isset($res_json->Data->PMT_Number)) {
                $so_order = $res_json->Data->PMT_Number;
                $raw_salesorder = $so_order;
                $pos_cust_key = $res_json->Data->Cust_Key;
                $so_order = "under the payment receipt " . $so_order . "</b>";
            }
            // echo $curl_url;
            if (curl_errno($curl)) {
                $response = json_encode($req) . "<br>" . curl_error($curl);
                // print_r(curl_error($curl));
                curl_close($curl);
            } else {
                $response = json_encode($req) . "<br>" . $response;
                curl_close($curl);
            }
            $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));

            // $insert_log
            $this->mcommon->common_edit("lead_action_log", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $insert_log));

            if(isset($res_json->ResponseCode) && $res_json->ResponseCode == 1){
                $lead_subject = "Live OntimeCRM - Receipt is not Generated against the OntimeGOV Lead #" . $lead_id;
                
                $lead_message_content = "Dear Team,<br /><br />";
                if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                    $lead_message_content .= "<br>POS Error Message <strong>: " . $res_json->ResponseMsg. "</strong>";
                }

                $lead_message_content .= "<br /><br><br>Lead Details:<br>";
                $lead_message_content .= "<br>Customer Name: " . $lead_det["customer_name"];
                $lead_message_content .= "<br>Customer Country Code: " . $lead_det["customer_country_code"];
                $lead_message_content .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                $lead_message_content .= "<br>Customer Email: " . $lead_det["customer_email"];
                $lead_message_content .= "<br>Amount : " . $net_total;
                $lead_message_content .= "<br>Online Payment Ref: " . $transaction_number;
                // $lead_message_content .= "<br>Attempt Action ID: " . $attempt_action_id;

                $pos_cc_email = [];
                array_push($pos_cc_email, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Manikandan"]);

                $email_array = array(
                    'email' =>  "hanna.h@egovllc.com",
                    'cc' => $pos_cc_email,
                    'subject' => $lead_subject,
                    'template' => 'mails/template',
                    'from_name' => "Ontime CRM",
                    'message' => $lead_message_content,
                );

                $send_mail = send_template_email($email_array);
                log_message('error', $send_mail);

                if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg || empty($raw_response)){
                    // $fetch_pay_url = "https://crm.ontimegroup.com/payment/payment_process?code=".trim($lead_id)."&order_id=".trim($lead_id)."&act=".trim($attempt_action_id)."&attempt_action=".trim($attempt_action_id)."&email=".trim($lead_det["customer_email"]);
                    // $res_message = $res_json->ResponseMsg. "<br><a target='_blank' href=". $fetch_pay_url ."' class='p-2 pl-4 pr-4 btn btn-primary'>Fetch Payment Status</a>";
                    $res_message = $res_json->ResponseMsg;
                    $log_insert_array = array('action_id' => 446, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'action_by' => 4294967295, 'status_id' => 645, 'is_fetch_pay_status' => 1, 'pos_pmt_response' => json_encode($req));
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $fetch_pay_url = "https://crm.ontimegroup.com/payment/ontimegov_payment_process?code=".trim($insert_log)."&lead_id=".trim($lead_id);
                    $res_message = $res_json->ResponseMsg. "<br><a target='_blank' href=". $fetch_pay_url ."' class='p-2 pl-4 pr-4 btn btn-primary'>Fetch OntimeGOV Payment Status</a>";
                    $update_log = $this->mcommon->common_edit('lead_action_log', array('remarks' => $res_message), array('id' => $insert_log));
                }
            }

            // $log_insert_array = array("action_id" => 415, "lead_id" => $lead_id, "remarks" => "Customer paid " . $net_total . " AED by Online Card for <b>#" . $req["OrderRef"] . " " . $so_order . "</b>", "action_by" => $user_id, "status_id" => 310, "pos_pmt_response" => $raw_response, "pos_pmt_number" => $raw_salesorder,"pos_order_ref"=>$req["OrderRef"]);

            // $log_act_insert = $this->db->insert('lead_action_log', $log_insert_array);

            $postData = array(
                'lead_id' => $lead_id,
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
                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));


            // $assigned_to = 3573695398;
            $assigned_to = 2113278237; //cc@ontimegroup.com
            $assigned_by = 4294967295;
            // echo "<pre>";
            // print_r($this->db);
            // echo "</pre>";
            // exit();
            if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                $this->response('Parameters Missing', 400);
            } else {
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

                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

                $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                // print_r($log_insert_array);
                $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                // echo "Log: ".$log_insert."<br>";
                // echo "ERROR: ";
                // print_r($this->db->error());
                // exit();

                $ontimegov_otgdata = array(
                    'name' => $name, 
                    'email' => $email, 
                    'mobile' => $mobile , 
                    'order_ref' => $order_ref, 
                    'trans_name' => $trans_name, 
                    'card_num' => $card_num, 
                    'transaction_number' => $transaction_number, 
                    'receipt_no' => $receipt_no, 
                    'sla' => $sla, 
                    'order_id' => $order_id, 
                    'order_details_id' => $order_details_id, 
                    'service_id' => $service_id, 
                    'net_total' => $net_total, 
                    'description' => $description, 
                    'pos_category_id' => $order_details, 
                    'pos_service_id' => $pos_service_id, 
                    'payment_mode' => $payment_mode,
                    'order_details' => $order_details,
                    'govt_fee'=>$postgovt_fee,
                    'typing_fee'=>$posttyping_fee,
                    'delivery_id'=>$postdelivery_id,
                    'lead_id'=>$parent_lead_id,
                    'assigned_by' => $assigned_by,
                    'assigned_to' => $assigned_to,
                    // 'request_response' =>json_encode($raw_response)

                );
                // $ontimegov_log_db = $this->mcommon->common_insert('ontime_lead_log', array('app' => 'Ontimegov', 'requested' => "1", 'response' => "2"));
                $request_ontimegov_data = json_encode($ontimegov_otgdata);

                $ontimegov_log_db = $this->mcommon->common_insert('ontime_lead_log', array('app' => 'Ontimegov', 'gov_orderid' =>$order_id, 'requested' => $request_ontimegov_data, 'response' => $raw_response));

                // $CI->db->insert("ontime_lead_log", ["app" => "Ontimegov", "requested" => json_encode($ontimegov_otgdata), "response" => $raw_response]);


                
                if ($insert > 0) {
                    $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                    if ($update) {
                        //create action log
                        $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $receiver_email = $csa->email;
                        $receiver_email = 'cc@ontimegroup.com';
                        $receiver_name = $csa->first_name;
                        $sender_email = $coordinator->email;
                        $sender_name = $coordinator->first_name;

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

                        // $textdemo = $lead_det["remarks"];
                        // $parts = explode('https://ontimegov.com/assets/uploads/', $textdemo);
                        // $fnms = [];

                        // foreach ($parts as $key => $part) {
                        //     if ($key > 0) { // Skip the first part as it won't have a valid filename
                        //         // Extract filename until the first space or closing tag
                        //         $fileName = strtok($part, ' ">'); 
                        //         $fnms[] = $fileName;
                        //         $message .=$fileName;
                        //     }
                        // }

                        



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

                        $this->response('Unable to assign the lead at present. Please try again later', 500);
                    }
                } else {
                    $this->response('Unable to assign lead at present.', 500);
                }

                //Log
                // print_r($order_details);
                // $order_details = json_decode($order_details);
                // exit();
                     
                foreach ($order_details as $orderdet) {
                    // print_r($orderdet);
                    // exit();
                    $service = $orderdet->transname;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }



                    $sub_remark = "";
                    $sub_remark .= "Applicant Name: <strong>" . $orderdet->applicantname . "</strong><br>";
                    $sub_remark .= "Invoice To: <strong>" . $orderdet->invoice_to . "</strong><br>";
                    // $sub_remark .= $orderdet->type1name . " / " . $orderdet->type2name . " / " . $orderdet->type3name . " / <strong>" . $service . "</strong><br><br>";
                    // foreach ($orderdet->order_doc as $doc) {
                    //     $sub_remark .= "<strong>" . $doc->docname . "</strong>: <a target='_blank' href='" . $doc->docname . "'>File Download</a><br>";
                    // }

                    //SubLeads
                    $insert_lead_array = array(
                        'customer_id' => $user_id,
                        'branch_id' => $branch_id,
                        'category_id' => $category_id,
                        'service_id' => $service_id,
                        'lead_created_by' => 4294967295,
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 301,
                        'order_receipt' => 0,
                        'remarks' => $sub_remark,
                        'is_assigned' => 0,
                        'govt_fee' => $postgovt_fee,
                        'typing_fee' => $posttyping_fee,
                        'other_charges' => $postother_fee,
                        'lead_parent_id' => $parent_lead_id,
                        'gov_service_amount' => ($orderdet->step_data_pages > 0) ? ($orderdet->amount * $orderdet->step_data_pages) : $orderdet->amount,
                    );
                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                   /* $insert_lead_array1 = array(
                        'customer_id' => $user_id,
                        'branch_id' => $branch_id,
                        'category_id' => $category_id,
                        'service_id' => $service_id,
                        'lead_created_by' => 4294967295,
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 301,
                        'order_receipt' => 0,
                        'remarks' => $sub_remark,
                        'is_assigned' => 0,
                        
                        'lead_parent_id' => $parent_lead_id,
                    );
                    $insert_lead_id1 = $this->mcommon->common_insert('leads', $insert_lead_array1);*/

                    if($postdelivery_id != '0' && $postdelivery_id != null){
                    $insert_lead_array2 = array(
                        'customer_id' => $user_id,
                        'branch_id' => $branch_id,
                        'category_id' => $category_id,
                        'service_id' => 3965,   //$service_id,
                        'lead_created_by' => 4294967295,
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 301,
                        'order_receipt' => 0,
                        'remarks' => $sub_remark,
                        'is_assigned' => 0,
                        'additional_govt_fee' => $postdelivery_fee,
                        'lead_parent_id' => $parent_lead_id,
                        'gov_service_amount' => $postdelivery_fee,
                    );
                    $insert_lead_id2 = $this->mcommon->common_insert('leads', $insert_lead_array2);
                    }
                    // print_r($insert_lead_array);
                    // exit();
                    if ($insert_lead_id > 0) {
                        //create action log
                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => 4294967295, 'status_id' => 301);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    }
                }


                if ($insert > 0) {
                     $this->response('Order creation successfull - ORDER'.$lead_id, 200);
                } else {
                    $this->response('Unable to create customer record.', 500);
                }
            }
            // $request_ontimegov_data = json_encode($ontimegov_otgdata);
            // $ontimegov_log_db = $this->mcommon->common_insert('ontime_lead_log', array('app' => 'Ontimegov', 'requested' => "1", 'response' => "2"));


            // $ontimegov_log_db = $this->mcommon->common_insert('ontime_lead_log', array('app' => 'Ontimegov', 'requested' => $request_ontimegov_data, 'response' => json_encode($raw_response)));
    
        }
       
    }

    public function ontimegov_withoutpay_post()
    {
        $name = $this->post('name');
        $email = $this->post('email');
        $mobile = $this->post('mobile');
        $invoice_to = $this->post('invoice_to');
        $order_ref = $this->post('order_ref');
        $trans_name = $this->post('trans_name');
        $card_num = $this->post('card_num');
        $transaction_number = $this->post('transaction_number');
        $receipt_no = $this->post('receipt_no');
        $sla = $this->post('sla');
        $order_id = $this->post('order_id');
        $order_details_id = $this->post('order_details_id');
        $service_id = $this->post('service_id');
        $net_total = $this->post('net_total');
        $description = $this->post('description');
        $order_details = json_decode($this->post('order_details'));
        $order_status = 301;
        $pos_category_id = $this->post('pos_category_id');
        $pos_service_id = $this->post('pos_service_id');
        $payment_mode = 'offline'; // $this->post('payment_mode');
        $postgovt_fee = $this->post('govt_fee');
        $posttyping_fee = $this->post('typing_fee');
        $postother_fee = $this->post('other_fee');
        $postdelivery_fee = ($this->post('delivery_amount') != NULL) ? $this->post('delivery_amount') : 115;
        $postdelivery_id = $this->post('delivery_id');
        $number =  $mobile; // mobile number
        $number = (strpos($number, '971') === 0) ? substr($number, 3) : $number;
        $mobile = $number;

        $order_exist = $this->mcommon->specific_record_counts('leads', array('otg_order_id' => $order_id));
        if ($order_exist != 0) {
            $exist_lead_id = $this->mcommon->specific_row_value('leads', array('otg_order_id' => $order_id), 'id');  
            $this->response('Already lead exists against this - ORDER' . $exist_lead_id, 200);
        }

        if ($payment_mode == 'offline') {
            if ($name == '' || $email == '' || $mobile == '' || $trans_name == '' || $sla == '' || $order_id == '' 
                || $order_details_id == '' || $service_id == '' || $description == '') // $net_total == '' ||
            {
                $this->response('Offline - Parameters Missing', 400);
            }
        } else {
            $this->response('Error, Online Payment process not proceed', 200);
            /* if ($name == '' || $email == '' || $mobile == '' || $order_ref == '' || $trans_name == '' || $card_num == '' || $transaction_number == '' || $receipt_no == '' || $sla == '' || $order_id == '' || $order_details_id == '' || $service_id == '' || $net_total == '' || $description == '') {
                $this->response('Online - Parameters Missing', 400);
            }   */
        }

        $order_exist = $this->mcommon->specific_record_counts('leads', array('otg_order_id' => $order_id));
        if ($order_exist != 0) {
            $exist_lead_id = $this->mcommon->specific_row_value('leads', array('otg_order_id' => $order_id), 'id');  
            $this->response('Already lead exists against this - ORDER' . $exist_lead_id, 200);
        }

        //Customer Logic
        /* $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile));
        $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $email));

        if ($check_email_exists != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }

        if ($check_mobile_exists != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $mobile), 'user_id');
        }   */

        $check_lead_user = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile,'email' => $email));

        if ($check_lead_user != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email,'mobile' => $mobile), 'user_id');
        }

        // if ($check_mobile_exists == 0 && $check_email_exists == 0) {
        if ($check_lead_user == 0) {
            $password = 'Welcome@123';
            $confirm_password = 'Welcome@123';
            $auth_level = '4';
            $referal_code = time();
            $user_hashed_password = $this->authentication->hash_passwd($password);
            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $mobile,
                'referal_code' => $referal_code,
                'first_name' => $name,
                'passwd' => $user_hashed_password,
                'email' => trim($email),
                'confirm_password' => $user_hashed_password,
            ];
            $user_data['user_id'] = $this->authentication_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');
            $user_data['otp'] = rand(1000, 9000);
            $user_data['email_otp'] = rand(1000, 9000);
            $user_data['banned'] = '0';
            $user_data['role_id'] = '4';
            $user_data['country'] = 'United Arab Emirates';
            $user_data['country_code'] = '971';
            $insert = $this->mcommon->common_insert("lead_users", $user_data);

            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }

        if ($user_id != '') {
            $insert_array = array(
                'user_id' => $user_id,
                'pos_category_id' => $pos_category_id,
                'pos_service_id' => $pos_service_id,
                'order_ref' => $order_ref,
                'trans_name' => $trans_name,
                'card_num' => $card_num,
                'transaction_number' => $transaction_number,
                'receipt_no' => $receipt_no,
                'sla' => $sla,
                'order_id' => $order_id,
                'order_details_id' => $order_details_id,
                'service_id' => $service_id,
                'net_total' => $net_total,
                'description' => $description,
                'order_details' => json_encode($order_details),
                'item_status' => $order_status,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert = $this->mcommon->common_insert('otg_orders', $insert_array);

            $branch_id = 119;
            $category_id = 10009;
            $service = $trans_name;
            $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

            if (!empty($service_exist)) {
                $service_id = $service_exist["id"];
            } else {
                $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                $service_id = (int) $service_id;
                $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
            }
            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');

            //Leads
            $insert_lead_array = array(
                'customer_id' => $user_id,
                'branch_id' => $branch_id,
                'category_id' => $category_id,
                'service_id' => $service_id,
                'lead_created_by' => 4294967295,
                'lead_added_on' => date('Y-m-d H:i:s'),
                'contactable_date' => date('Y-m-d H:i:s'),
                'lead_status' => 301,
                'order_receipt' => 0,
                'remarks' => $description,
                'is_assigned' => 0,
                'otg_order_id' => $order_id,
                'otg_order_detail_id' => $order_details_id,
                'otg_paylater' => 1,
                'lead_from' => 'OntimeGOV',
                'created_group_id' => $created_group_id,
                'invoice_to' => $invoice_to
            );
            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
            $parent_lead_id = $insert_lead_id;

            $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTimeGov Web Order #' . $order_id, 'action_by' => 4294967295, 'status_id' => 301);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

            $lead_id = $parent_lead_id;

            $postData = array(
                'lead_id' => $lead_id,
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
                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

            /* $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'pos_user_id');     //  info@ontimegov.com
            if ($user_pos == 0 || $user_pos == NULL)
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'employee_id');     // info@ontimegov.com
            // if ($user_pos == 0 || $user_pos == NULL)
            //     $user_pos = "crmonline";


            $req["Customer"] = array("Cust_EngName" => $name, "Cust_Mobile" => $mobile, "Cust_Email" => $email);
            $req["OrderRef"] = "SE" . $order_id . "-" . $insert_log . '-OTLDPMET' . $parent_lead_id;
            // $req["Payment"] = array("ActAmt" => $net_total, "OnlinePaymentRef" => $transaction_number);

            $this->db->update("lead_action_log", array('remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTimeGov Web Order #SE' . $order_id . " under the OrderRef #" . $req["OrderRef"]), array("id" => $insert_log));

            // $action_id
            $req["ServDescription"] = $trans_name;
            $req["salesorderdtl"] = [];

            // $req["User"] = ["User_ID" => "crmonline"];
            $req["User"] = ["User_ID" => $user_pos];
            $req["Payment_Type"] = "ONLINE";

            // POS Changes 
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
            $lead_det = $this->leads_model->lead_details($lead_id);

            if (!empty($lead_det["lead_zoho_id"])) {
                $req["Payment"] = array(
                    "ActAmt" => $net_total,
                    "OnlinePaymentRef" => $transaction_number,
                    "CampaignSource" => $lead_det["lead_ad_campaign"],
                    "ZohoLeadSource" => $lead_det["lead_source"],
                    "CampaignId" => $lead_det["lead_ad_campaign_id"],
                    "ZohoLeadId" => $lead_det["lead_zoho_id"],
                    "LeadFrom" => 'Zoho',
                    "ZohoCreatedBy" => $created_by_user
                );
            } else {
                $req["Payment"] = array(
                    "ActAmt" => $net_total,
                    "OnlinePaymentRef" => $transaction_number,
                    "LeadSource" => 'Website',
                    "LeadFrom" => $lead_det["lead_from"],
                    "CRMLeadId" => $lead_id,
                    "ZohoCreatedBy" => $created_by_user,
                    "LeadCreatedBy" => $created_by_user,
                );
            }

            if (!empty($lead_det["pos_cust_key"])) {
                $req["Cust_Key"] = $lead_det["pos_cust_key"];
            }

            $curl = curl_init();
            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'http://94.200.55.118:8011/api/ApiPos/CreatePaymentfromCRM?createso=0',
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
            $res_json = json_decode($raw_response);
            if (isset($res_json->Data->PMT_Number)) {
                $so_order = $res_json->Data->PMT_Number;
                $raw_salesorder = $so_order;
                $pos_cust_key = $res_json->Data->Cust_Key;
                $so_order = "under the payment receipt " . $so_order . "</b>";
            }

            if (curl_errno($curl)) {
                $response = json_encode($req) . "<br>" . curl_error($curl);
                curl_close($curl);
            } else {
                $response = json_encode($req) . "<br>" . $response;
                curl_close($curl);
            }
            $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));

            $this->mcommon->common_edit("lead_action_log", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $insert_log)); */

            // $assigned_to = 3573695398;
            $assigned_to = 2113278237; //cc@ontimegroup.com
            $assigned_by = 4294967295;

            if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                $this->response('Parameters Missing', 400);
            } else {
                $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                $insert_array = array(
                    'lead_id' => $lead_id,
                    'assigned_by' => $assigned_by,
                    'assigned_to' => $assigned_to,
                    'assigned_on' => date('Y-m-d H:i:s')
                );
  
                $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

                $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);

                $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                if ($insert > 0) {
                    $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                    if ($update) {
                        $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $receiver_email = $csa->email;
                        $receiver_email = 'cc@ontimegroup.com';
                        $receiver_name = $csa->first_name;
                        $sender_email = $coordinator->email;
                        $sender_name = $coordinator->first_name;

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
                        // $this->response('Lead has been assigned successfully!', 200);
                    } else {
                        $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));

                        $this->response('Unable to assign the lead at present. Please try again later', 500);
                    }
                } else {
                    $this->response('Unable to assign lead at present.', 500);
                }

                /* foreach ($order_details as $orderdet) {
                    $service = $orderdet->transname;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }

                    $sub_remark = "";
                    $sub_remark .= "Applicant Name: <strong>" . $orderdet->applicantname . "</strong><br>";

                    //SubLeads
                    $insert_lead_array = array(
                        'customer_id' => $user_id,
                        'branch_id' => $branch_id,
                        'category_id' => $category_id,
                        'service_id' => $service_id,
                        'lead_created_by' => 4294967295,
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 301,
                        'order_receipt' => 0,
                        'remarks' => $sub_remark,
                        'is_assigned' => 0,
                        'govt_fee' => $postgovt_fee,
                        'typing_fee' => $posttyping_fee,
                        'other_charges' => $postother_fee,
                        'lead_parent_id' => $parent_lead_id,
                        'gov_service_amount' => ($orderdet->step_data_pages > 0) ? ($orderdet->amount * $orderdet->step_data_pages) : $orderdet->amount,
                    );
                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                    if ($postdelivery_id != '0' && $postdelivery_id != null) {
                        $insert_lead_array2 = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => 3965,   // $service_id,
                            'lead_created_by' => 4294967295,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'order_receipt' => 0,
                            'remarks' => $sub_remark,
                            'is_assigned' => 0,
                            'additional_govt_fee' => $postdelivery_fee,
                            'lead_parent_id' => $parent_lead_id,
                            'gov_service_amount' => $postdelivery_fee,
                        );
                        $insert_lead_id2 = $this->mcommon->common_insert('leads', $insert_lead_array2);
                    }

                    if ($insert_lead_id > 0) {
                        //create action log
                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => 4294967295, 'status_id' => 301);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    }
                } */

                if ($insert > 0) {
                    $this->response('Order creation successfull - ORDER' . $lead_id, 200);
                } else {
                    $this->response('Unable to create customer record.', 500);
                }
            }
        }
    }

    public function otgorder_get()
    {
        if (isset($_GET["order_id"]) || isset($_GET["order_detail_id"])) {
            if ($_GET["order_id"] != 0 && $_GET["order_id"] != '0') {
                $this->db->select('l.id as lead_id,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,u.customer_type,u.customer_address,ob.branch_name as branch_name,lc.category_name as category_name,lc.category_code,ls.service_name as service_name,l.lead_status,lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse');
                $this->db->from('leads as l');
                $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
                $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
                $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
                $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
                $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
                $this->db->join('leads_assigned as la', 'la.lead_id=l.id', "left");
                $this->db->where('l.otg_order_id', $_GET["order_id"]);
                $this->db->group_by("l.id");
                $results = $this->db->get()->result_array();
                if (empty($results)) {
                    return $this->response(['status' => false, "message" => "No order found on order_id #" . $_GET["order_id"]], 500);
                }
                return $this->response(['status' => true, "data" => $results], 200);
            } else if ($_GET["order_detail_id"] != 0 && $_GET["order_detail_id"] != '0') {
                $this->db->select('l.id as lead_id,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,u.customer_type,u.customer_address,ob.branch_name as branch_name,lc.category_name as category_name,lc.category_code,ls.service_name as service_name,l.lead_status,lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse');
                $this->db->from('leads as l');
                $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
                $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
                $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
                $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
                $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
                $this->db->join('leads_assigned as la', 'la.lead_id=l.id', "left");
                $this->db->where('l.otg_order_detail_id', $_GET["order_detail_id"]);
                $this->db->group_by("l.id");
                $results = $this->db->get()->result_array();
                if (empty($results)) {
                    return $this->response(['status' => false, "message" => "No order found on order_detail_id #" . $_GET["order_detail_id"]], 500);
                }
                return $this->response(['status' => true, "data" => $results], 200);
            } else {
                return $this->response(['status' => false, "message" => "'order_id' & 'order_detail_id' both should not be 0"], 500);
            }
        } else {
            return $this->response(['status' => false, "message" => "'order_id' or 'order_detail_id' parameter missing"], 500);
        }
    }


    /**
     *  ONTIME GOV
     */
    public function ontimetranslation_post()
    {
        $name = $this->post('name');
        $email = $this->post('email');
        $mobile = $this->post('mobile');
        $trans_name = $this->post('trans_name');
        $order_id = $this->post('order_id');
        $order_details_id = $this->post('order_details_id');
        $net_total = $this->post('net_total');
        $description = $this->post('description');
        $order_details = json_decode($this->post('order_details'));

        if ($name == '' || $email == '' || $mobile == '' || $trans_name == '' || $order_id == '' || $order_details_id == '' || $net_total == '' || $description == '' || $order_details == '') {
            $this->response('Parameters Missing', 400);
        }

        $order_exist = $this->mcommon->specific_record_counts('leads', array('otg_order_id' => $order_id));
        if ($order_exist != 0) {
            $this->response(array('status' => 'error', 'result' => "Already lead exists against the order id"), 204);
        }

        //Customer Logic
        $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile));
        $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $email));

        if ($check_email_exists != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }

        if ($check_mobile_exists != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $mobile), 'user_id');
        }

        if ($check_mobile_exists == 0 && $check_email_exists == 0) {
            $password = 'Welcome@123';
            $confirm_password = 'Welcome@123';
            $auth_level = '4';
            $referal_code = time();
            $user_hashed_password = $this->authentication->hash_passwd($password);
            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $mobile,
                'referal_code' => $referal_code,
                'first_name' => $name,
                'passwd' => $user_hashed_password,
                'email' => trim($email),
                'confirm_password' => $user_hashed_password,
            ];
            $user_data['user_id'] = $this->authentication_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');
            $user_data['otp'] = rand(1000, 9000);
            $user_data['email_otp'] = rand(1000, 9000);
            $user_data['banned'] = '0';
            $user_data['role_id'] = '4';
            $user_data['country'] = 'United Arab Emirates';
            $user_data['country_code'] = '971';
            $insert = $this->mcommon->common_insert("lead_users", $user_data);

            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }


        if ($user_id != '') {

            $branch_id = 120;
            $category_id = 10022;
            $service = $trans_name;
            $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

            if ($service_exist) {
                $service_id = $service_exist["id"];
            } else {
                $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                $service_id = (int) $service_id;
                $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
            }

            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');

            //Leads
            $insert_lead_array = array(
                'customer_id' => $user_id,
                'branch_id' => $branch_id,
                'category_id' => $category_id,
                'service_id' => $service_id,
                'lead_created_by' => 4294967295,
                'lead_added_on' => date('Y-m-d H:i:s'),
                'contactable_date' => date('Y-m-d H:i:s'),
                'lead_status' => 301,
                'order_receipt' => 0,
                'remarks' => $description,
                'is_assigned' => 0,
                'otg_trans_id' => $order_id,
                'otg_trans_details_id' => $order_details_id,
                'lead_from' => 'OntimeGOV',
                'created_group_id' => $created_group_id
            );
            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
            $parent_lead_id = $insert_lead_id;

            $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTime Translation Web Order #' . $order_id, 'action_by' => 4294967295, 'status_id' => 301);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

            $lead_id = $parent_lead_id;

            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'pos_user_id');     //  info@ontimegroup.com
            if ($user_pos == 0 || $user_pos == NULL)
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'employee_id');     // info@ontimegroup.com
            // if ($user_pos == 0 || $user_pos == NULL)
            //     $user_pos = "crmonline";


            $req["Customer"] = array("Cust_EngName" => $name, "Cust_Mobile" => $mobile, "Cust_Email" => $email);
            $req["OrderRef"] = "OTS" . $order_id . "-" . $insert_log . '-OTLDPMET' . $parent_lead_id;
            // $req["Payment"] = array("ActAmt" => $net_total);

            $this->db->update("lead_action_log", array('remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTime Translation Web Order #' . $order_id . " under the OrderRef #" . $req["OrderRef"]), array("id" => $insert_log));

            // $action_id
            $req["ServDescription"] = $trans_name;
            $req["salesorderdtl"] = [];

            // $req["User"] = ["User_ID" => "crmonline"];
            $req["User"] = ["User_ID"=> $user_pos];
            $req["Payment_Type"] = "ONLINE";

            // POS Changes 
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
            $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
            $lead_det = $this->leads_model->lead_details($lead_id);

            if(!empty($lead_det["lead_zoho_id"])){
                $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                if(!empty($lead_created_by)){
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                    $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                } else {
                    $created_by_user = '';
                }

                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "CampaignSource" => $lead_det["lead_ad_campaign"],
                    "ZohoLeadSource" => $lead_det["lead_source"],
                    "CampaignId" => $lead_det["lead_ad_campaign_id"],
                    "ZohoLeadId" => $lead_det["lead_zoho_id"],
                    "LeadFrom" => 'Zoho',
                    "CRMLeadId" => $lead_id,
                    "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                );
                
            } else {
                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "LeadSource" => 'Website',
                    "LeadFrom" => $lead_det["lead_from"],
                    "CRMLeadId" => $lead_id,
                    "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
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
            // print_r($raw_response);
            $res_json = json_decode($raw_response);
            // print_r($res_json);
            if (isset($res_json->Data->PMT_Number)) {
                $so_order = $res_json->Data->PMT_Number;
                $pos_cust_key = $res_json->Data->Cust_Key;
                $raw_salesorder = $so_order;
                $so_order = "under the payment receipt " . $so_order . "</b>";
            }
            // echo $curl_url;
            if (curl_errno($curl)) {
                $response = json_encode($req) . "<br>" . curl_error($curl);
                // print_r(curl_error($curl));
                curl_close($curl);
            } else {
                $response = json_encode($req) . "<br>" . $response;
                curl_close($curl);
            }

            $raw_salesorder = "Raw_SO";
            $raw_response = "Raw_RE";
            
            $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));

            // $insert_log
            $this->mcommon->common_edit("lead_action_log", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $insert_log));

            // $log_insert_array = array("action_id" => 415, "lead_id" => $lead_id, "remarks" => "Customer paid " . $net_total . " AED by Online Card for <b>#" . $req["OrderRef"] . " " . $so_order . "</b>", "action_by" => $user_id, "status_id" => 310, "pos_pmt_response" => $raw_response, "pos_pmt_number" => $raw_salesorder,"pos_order_ref"=>$req["OrderRef"]);

            // $log_act_insert = $this->db->insert('lead_action_log', $log_insert_array);

            $postData = array(
                'lead_id' => $lead_id,
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
                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

            // $assigned_to = 3573695398;
            $assigned_to = 3375144658; //mahmoudadel.e@ontimegov.com
            $assigned_by = 4294967295;
            // echo "<pre>";
            // print_r($this->db);
            // echo "</pre>";
            // exit();
            if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                $this->response('Parameters Missing', 400);
            } else {
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

                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

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
                        $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $receiver_email = $csa->email;
                        $receiver_name = $csa->first_name;
                        $sender_email = $coordinator->email;
                        $sender_name = $coordinator->first_name;

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
                        log_message('error', $send_mail);

                        // $this->response('Lead has been assigned successfully!', 200);
                    } else {
                        $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                        // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                        $this->response('Unable to assign the lead at present. Please try again later', 500);
                    }
                } else {
                    $this->response('Unable to assign lead at present.', 500);
                }

                //Log
                // print_r($order_details);
                // $order_details = json_decode($order_details);
                // exit();
                foreach ($order_details as $orderdet) {
                    // print_r($orderdet);
                    // exit();
                    $service = $orderdet->categoryname;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }

                    $sub_remark = "";
                    $sub_remark .= $orderdet->description;

                    // $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');

                    //SubLeads
                    $insert_lead_array = array(
                        'customer_id' => $user_id,
                        'branch_id' => $branch_id,
                        'category_id' => $category_id,
                        'service_id' => $service_id,
                        'lead_created_by' => 4294967295,
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 301,
                        'order_receipt' => 0,
                        'remarks' => $sub_remark,
                        'is_assigned' => 0,
                        'lead_parent_id' => $parent_lead_id,
                        'otg_trans_id' => $order_id,
                        'otg_trans_details_id' => $orderdet->order_detail_id,
                        'lead_from' => 'OntimeGOV',
                        // 'created_group_id' => $created_group_id,
                    );
                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                    // print_r($insert_lead_array);
                    // exit();
                    if ($insert_lead_id > 0) {
                        //create action log
                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => 4294967295, 'status_id' => 301);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    }
                }


                if ($insert > 0) {
                    $this->response('Order creation successfull', 200);
                } else {
                    $this->response('Unable to create customer record.', 500);
                }
            }
        }
    }


    //GoldenCube
    public function goldencube_post()
    {
        try {
            $profile = json_decode($this->post("profile"));
            $user = json_decode($this->post("user"));
            $document = json_decode($this->post("document"));
            $depend = json_decode($this->post("dependent_data"));
            $ontime_transid = json_decode($this->post("transid"));
            $queue = json_decode($this->post("queue_data"));
            $document_remark = json_decode($this->post("document_remark"), true);

            if ($profile == '' || $document == '' || $user == '') {
                $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
            }

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            $branch_id = 106;
            $lead_type = 'normal';
            $lead_by_pos_user = '3946368694'; // goldencubeweb@goldencube.ae
            // '323374798'; // Hind.H@ontimegroup.com
            // 3020140166; // basel.a@goldencube.ae
            //178140614;   // info@ontimegroup.com
            $lead_by_post_user_name = 'Golden Cube WEB';    //"Web API";

            //check category exist
            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
            }

            if ($lead_type == 'normal') {
                $category_id = 109;
                $service_id = 1009;

                //check category exist
                $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
                if ($cateogry_exist == 0) {
                    $this->response("Category doesn't exist. Update category first to create the lead", 404);
                }

                $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
                if ($service_exist == 0) {
                    $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
                }
            }

            $lead_name = $user->first_name . " " . $user->last_name;
            $lead_contact = ($user->mobile != NULL && trim($user->mobile) != '') ? $user->mobile : "520000000";
            $lead_email = $user->email;
            $lead_countrycode = ($user->countrycode != NULL && trim($user->countrycode) != '') ? $user->countrycode : "+971";
            $lead_remarks = "<br><b><u>GoldenCube Eligibility Check - Website Lead</u></b>,<br>";

            if ($user->is_corporate == true && $user->is_corporate != NULL){
                $lead_remarks .= "Is Corporate:&nbsp;<b> Yes </b>,</br>";
                $lead_remarks .= "Applicant Name:&nbsp;<b>" . $user->applicant_name . "</b>,</br>";
            }
            
            $customer_mail_package_name = '';
            if($ontime_transid == 266){
                $lead_remarks .= "Package:&nbsp;<b>" . $depend->package_name . "</b>,</br>";
                $lead_remarks .= "Dependent Package Name:&nbsp;<b>" . $depend->dependent_package_name. "</b>,</br>";
                
                $lead_remarks .= "Name:&nbsp;<b>". $depend->dependent_first_name . " " . $depend->dependent_last_name . "</b>,</br>";
                $lead_remarks .= "Gender:&nbsp;<b>" . strtoupper($depend->dependent_gender). "</b>,</br>";
                $lead_remarks .= "Relationship:&nbsp;<b>" . $depend->dependent_relationship. "</b>,</br>";
                $lead_remarks .= "Nationality:&nbsp;<b>" . $depend->dependent_nationality. "</b></br></br>";
                $customer_mail_package_name = $depend->dependent_package_name;
            } else {
                $lead_remarks .= "Package:&nbsp;<b>" . $profile->package_name . "</b>,</br>";
                $lead_remarks .= "Name:&nbsp;<b>" . $profile->first_name . " " . $profile->last_name . "</b>,</br>";
                $lead_remarks .= "Gender:&nbsp;<b>" . strtoupper($profile->gender) . "</b>,</br>";
                // $lead_remarks .= "DOB:&nbsp;<b>" . $profile->date_of_birth . " / " . $profile->age . "</b>,</br>";
                $lead_remarks .= "Nationality:&nbsp;<b>" . $profile->nationality . "</b></br></br>";
    
                if ($profile->property_address != "" && $profile->property_address != NULL)
                    $lead_remarks .= "Address:&nbsp;<b>" . $profile->property_address . "</b>,</br>";
                if ($profile->property_communication_email != "" && $profile->property_communication_email != NULL)
                    $lead_remarks .= "Communication Email:&nbsp;<b>" . $profile->property_communication_email . "</b>,</br>";
                if ($profile->property_mobile_number != "" && $profile->property_mobile_number != NULL)
                    $lead_remarks .= "Mobile number:&nbsp;<b>" . $profile->property_mobile_number . "</b>,</br>";
                $customer_mail_package_name = $profile->package_name;
            }

            if(!empty($queue->payment_type)){
                $lead_remarks .= "Payment Type : &nbsp;<b>" . $queue->payment_type . "</b>,</br>";
                $lead_remarks .= "IBAN :&nbsp;<b>" . $queue->iban_no . "</b>,</br>";
                $lead_remarks .= "Token :&nbsp;<b>" . $queue->token . "</b>,</br></br>";
                // $lead_remarks .= "Token Id :&nbsp;<b>" . $queue->token_id . "</b>,</br></br>";
            }

            if($profile->package_name == 'Visit Visa'){
                $lead_remarks .= "Sponser Name:&nbsp;<b>" . $profile->sponser_name . "</b>,</br>";
                $lead_remarks .= "Dependent's Relationship:&nbsp;<b>" . $profile->dependent_relationship . "</b>,</br>";
                $lead_remarks .= "Visa Duration:&nbsp;<b>" . $profile->visa_duration . "</b>,</br></br>";
            }

            if($profile->package_name == 'Visa Cancellation'){
                $lead_remarks .= "Making Visa Cancellation:&nbsp;<b>" . $profile->making_visa_cancellation . "</b>,</br>";
                if ($profile->making_visa_cancellation == "Outside UAE"){
                    $lead_remarks .= "Applicant Outside UAE for more then a Year:&nbsp;<b>" . $profile->outside_uae_more_then_year . "</b>,</br>";
                    $lead_remarks .= "Current Visa Valid or Expired:&nbsp;<b>" . $profile->current_visa_valid_expired . "</b>,</br>";
                }
                $lead_remarks .= "Applicant's Current Visa Type:&nbsp;<b>" . $profile->current_visa_type . "</b>,</br>";
                $lead_remarks .= "Currently sponsoring Family Member/Maid under your sponsorship:&nbsp;<b>" . $profile->sponsering_the_family . "</b>,</br>";
                if ($profile->sponsering_the_family == "Yes" || $profile->sponsering_the_family == "YES")
                    $lead_remarks .= "Cancel or Hold Family/Maid visa:&nbsp;<b>" . $profile->cancel_or_hold_maidvisa . "</b>,</br></br>";
            }

            /* $prop_doc_count = count($document->property_document);
            $prop_doc_data = $document->property_document;

            $off_doc_count = count($document->official_document);
            $off_doc_data = $document->official_document;

            $depd_doc_count = count($document->dependency_document);
            $depd_doc_data = $document->dependency_document;

            $spon_doc_count = count($document->sponser_document);
            $spon_doc_data = $document->sponser_document;

            $fam_res_count = count($document->family_residence);
            $fam_res_data = $document->family_residence;

            $pare_res_count = count($document->parent_residence);
            $pare_res_data = $document->parent_residence;

            $per_info_trans_visa_res_count = count($document->update_personal_info_transfer_visa);
            $per_info_trans_visa_res_data = $document->update_personal_info_transfer_visa;

            $per_info_change_nationality_res_count = count($document->update_personal_info_change_nationality);
            $per_info_change_nationality_res_data = $document->update_personal_info_change_nationality;

            $per_info_lost_passport_res_count = count($document->update_personal_info_lost_passport);
            $per_info_lost_passport_res_data = $document->update_personal_info_lost_passport;

            $per_info_trans_visa_depen_res_count = count($document->update_personal_info_transfer_visa_dependent);
            $per_info_trans_visa_depen_res_data = $document->update_personal_info_transfer_visa_dependent;

            $per_info_change_nationality_depen_res_count = count($document->update_personal_info_change_nationality_dependent);
            $per_info_change_nationality_depen_res_data = $document->update_personal_info_change_nationality_dependent;

            $per_info_lost_passport_depen_res_count = count($document->update_personal_info_lost_passport_dependent);
            $per_info_lost_passport_depen_res_data = $document->update_personal_info_lost_passport_dependent;

            $pe_apartment_unit_res_count = count($document->property_evaluation_apartment_unit);
            $pe_apartment_unit_res_data = $document->property_evaluation_apartment_unit;

            $pe_villa_res_count = count($document->property_evaluation_villa);
            $pe_villa_res_data = $document->property_evaluation_villa;

            $pe_empty_land_res_count = count($document->property_evaluation_empty_land);
            $pe_empty_land_res_data = $document->property_evaluation_empty_land;

            $visit_visa_res_count = count($document->visit_visa);
            $visit_visa_res_data = $document->visit_visa;

            $visa_cancel_res_count = count($document->visa_cancellation);
            $visa_cancel_res_data = $document->visa_cancellation;

            $visa_cancel_family_res_count = count($document->visa_cancel_family_maid);
            $visa_cancel_family_res_data = $document->visa_cancel_family_maid;

            $other_doc_count = count($document->documents);
            $other_doc_data = $document->documents;

            // foreach ($document as $doc) {
            //     // $lead_remarks .= $doc->profile_doc_name . " : <a href=" . $doc->profile_doc_file . " target='_blank'>View File</a><br>";
            // }

            if($prop_doc_count > 0){
                $lead_remarks .= "<br><b><u>Property Documents</u></b><br>";
                foreach($prop_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($off_doc_count > 0){
                $lead_remarks .= "<br><b><u>Official Documents</u></b><br>";
                foreach($off_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($depd_doc_count > 0){
                $lead_remarks .= "<br><b><u>Dependency Documents</u></b><br>";
                foreach($depd_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($spon_doc_count > 0){
                $lead_remarks .= "<br><b><u>Sponser Documents</u></b><br>";
                foreach($spon_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($fam_res_count > 0){
                $lead_remarks .= "<br><b><u>Family Residence</u></b><br>";
                foreach($fam_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pare_res_count > 0){
                $lead_remarks .= "<br><b><u>Parents Residence</u></b><br>";
                foreach($pare_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_trans_visa_res_count > 0){
                $lead_remarks .= "<br><b><u>Transfer visa from old to new Passport - Documents</u></b><br>";
                foreach($per_info_trans_visa_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_change_nationality_res_count > 0){
                $lead_remarks .= "<br><b><u>Change Nationality Documents</u></b><br>";
                foreach($per_info_change_nationality_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_lost_passport_res_count > 0){
                $lead_remarks .= "<br><b><u>Lost Passport Documents</u></b><br>";
                foreach($per_info_lost_passport_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_trans_visa_depen_res_count > 0){
                $lead_remarks .= "<br><b><u>Transfer visa from old to new Passport for Dependent Documents</u></b><br>";
                foreach($per_info_trans_visa_depen_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_change_nationality_depen_res_count > 0){
                $lead_remarks .= "<br><b><u>Change Nationality for Dependent Documents</u></b><br>";
                foreach($per_info_change_nationality_depen_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_lost_passport_depen_res_count > 0){
                $lead_remarks .= "<br><b><u>Lost Passport for Dependent Documents</u></b><br>";
                foreach($per_info_lost_passport_depen_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pe_apartment_unit_res_count > 0){
                $lead_remarks .= "<br><b><u>Apartment unit Documents</u></b><br>";
                foreach($pe_apartment_unit_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pe_villa_res_count > 0){
                $lead_remarks .= "<br><b><u>Villa Documents</u></b><br>";
                foreach($pe_villa_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pe_empty_land_res_count > 0){
                $lead_remarks .= "<br><b><u>Empty Land Documents</u></b><br>";
                foreach($pe_empty_land_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($visit_visa_res_count > 0){
                $lead_remarks .= "<br><b><u>Visit Visa Documents</u></b><br>";
                foreach($visit_visa_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($visa_cancel_res_count > 0){
                $lead_remarks .= "<br><b><u>Visa Cancellation Documents</u></b><br>";
                foreach($visa_cancel_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($visa_cancel_family_res_count > 0){
                $lead_remarks .= "<br><b><u>Visa Cancellation - Cancel the Family/Maid Visa Documents</u></b><br>";
                foreach($visa_cancel_family_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($other_doc_count > 0){
                foreach($other_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }   */

            $output_pdf_image = "";
            if (!empty($document_remark) && is_array($document_remark)) {
                $output_pdf_image = '<br><b><u>Document Analysis Report</u></b><br>';

                foreach ($document_remark as $doc_name => $doc_data) {
                    // Check if this is a dependent block like dependent0, dependent1, etc.
                    if (strpos($doc_name, 'dependent') === 0 && is_array($doc_data)) {
                        $output_pdf_image .= '<br><b><u>' . htmlspecialchars($doc_name) . '</u></b><br>';

                        foreach ($doc_data as $sub_doc_name => $sub_doc_data) {
                            $output_pdf_image .= '<br><b><u>' . htmlspecialchars($sub_doc_name) . '</u></b><br>';

                            // --- PDF Analysis ---
                            if (!empty($sub_doc_data['PDF'])) {
                                $pdf = $sub_doc_data['PDF'];
                                $pdfScoreColor = ($pdf['score'] >= 4) ? 'success' : (($pdf['score'] >= 3) ? 'warning' : 'danger');
                                $pdfMessage = is_array($pdf['message']) ? implode(", ", array_map('htmlspecialchars', $pdf['message'])) : htmlspecialchars($pdf['message']);

                                $amounts = "";
                                if (!empty($pdf['amount']) && is_array($pdf['amount'])) {
                                    foreach ($pdf['amount'] as $page => $amt) {
                                        if (!empty($amt)) {
                                            $amounts .= "<li>Page $page: <b>" . htmlspecialchars($amt) . "</b></li>";
                                        }
                                    }
                                }

                                $output_pdf_image .= '<br><b><u>PDF Analysis</u></b><br>';
                                $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Score: ' . $pdf['score'] . '</span><br>';
                                $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Message: ' . $pdfMessage . '</span><br>';
                                if (!empty($amounts)) {
                                    $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Amounts Found:</span><br><ul>' . $amounts . '</ul>';
                                }
                                $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Quality Message: ' . htmlspecialchars($pdf['qualityMessage']) . '</span><br>';
                            }

                            // --- IMAGE Analysis ---
                            if (!empty($sub_doc_data['Image'])) {
                                $image = $sub_doc_data['Image'];
                                $imgScoreColor = ($image['final_quality_score'] >= 4) ? 'success' : (($image['final_quality_score'] >= 3) ? 'warning' : 'danger');

                                $output_pdf_image .= '<br><b><u>IMAGE Analysis</u></b><br>';
                                $output_pdf_image .= '<span class="badge badge-' . $imgScoreColor . '" style="white-space: normal; word-wrap: break-word;">Score: ' . $image['final_quality_score'] . '</span><br>';
                                $output_pdf_image .= '<span class="badge badge-' . $imgScoreColor . '" style="white-space: normal; word-wrap: break-word;">Resolution: ' . $image['width'] . 'x' . $image['height'] . '</span><br>';
                                $output_pdf_image .= '<span class="badge badge-' . $imgScoreColor . '" style="white-space: normal; word-wrap: break-word;">Quality Message: ' . htmlspecialchars($image['quality_message']) . '</span><br>';
                            }
                        }
                    } else {
                        // Handle main-level documents like "sponsorPassport"
                        $output_pdf_image .= '<br><b><u>' . htmlspecialchars($doc_name) . '</u></b><br>';

                        if (!empty($doc_data['PDF'])) {
                            $pdf = $doc_data['PDF'];
                            $pdfScoreColor = ($pdf['score'] >= 4) ? 'success' : (($pdf['score'] >= 3) ? 'warning' : 'danger');
                            $pdfMessage = is_array($pdf['message']) ? implode(", ", array_map('htmlspecialchars', $pdf['message'])) : htmlspecialchars($pdf['message']);

                            $amounts = "";
                            if (!empty($pdf['amount']) && is_array($pdf['amount'])) {
                                foreach ($pdf['amount'] as $page => $amt) {
                                    if (!empty($amt)) {
                                        $amounts .= "<li>Page $page: <b>" . htmlspecialchars($amt) . '</b></li>';
                                    }
                                }
                            }

                            $output_pdf_image .= '<br><b><u>PDF Analysis</u></b><br>';
                            $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Score: ' . $pdf['score'] . '</span><br>';
                            $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Message: ' . $pdfMessage . '</span><br>';
                            if (!empty($amounts)) {
                                $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Amounts Found:</span><br><ul>' . $amounts . '</ul>';
                            }
                            $output_pdf_image .= '<span class="badge badge-' . $pdfScoreColor . '" style="white-space: normal; word-wrap: break-word;">Quality Message: ' . htmlspecialchars($pdf['qualityMessage']) . '</span><br>';
                        }

                        if (!empty($doc_data['Image'])) {
                            $image = $doc_data['Image'];
                            $imgScoreColor = ($image['final_quality_score'] >= 4) ? 'success' : (($image['final_quality_score'] >= 3) ? 'warning' : 'danger');

                            $output_pdf_image .= '<br><b><u>IMAGE Analysis</u></b><br>';
                            $output_pdf_image .= '<span class="badge badge-' . $imgScoreColor . '" style="white-space: normal; word-wrap: break-word;">Score: ' . $image['final_quality_score'] . '</span><br>';
                            $output_pdf_image .= '<span class="badge badge-' . $imgScoreColor . '" style="white-space: normal; word-wrap: break-word;">Resolution: ' . $image['width'] . 'x' . $image['height'] . '</span><br>';
                            $output_pdf_image .= '<span class="badge badge-' . $imgScoreColor . '" style="white-space: normal; word-wrap: break-word;">Quality Message: ' . htmlspecialchars($image['quality_message']) . '</span><br>';
                        }
                    }
                }

                // Finally attach the compiled remark to the main remark field
                $lead_remarks .= $output_pdf_image;
            }

            if($profile->package_name != 'Interior Design' && $profile->package_name != "Medical Insurance")
                $lead_remarks .= "<br><b><u>Documents</u></b>,<br>";

            if (!empty($document)) {
                foreach ($document as $doc) {
                    $lead_remarks .= $doc->attachment_name . " : <a href='" . $doc->attachment_url . "' target='_blank'>" . $doc->attachment_name . "</a><br>";
                }
            }

            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($lead_email == '') ? $random_email : $lead_email;
            $lead_email = trim($lead_email);
            //create or get customer
            //$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);
            $user_id = 0;
            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

            $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name));

            if ($is_exist != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email, 'first_name' => $lead_name), 'user_id');
            }

            if ($is_exist == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '1';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $lead_contact,
                    'referal_code' => $referal_code,
                    'first_name' => $lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = 'United Arab Emirates';
                $user_data['country_code'] = $lead_countrycode;    //'+971';
                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                //return $user_id;
            }


            if ($user_id != 0) {
                $uploaded_file_name = '';
                //Upload document and get the file name
                if (isset($_FILES['files']['name'])) {
                    $config = array(
                        'upload_path' => "../uploads/leads",
                        'allowed_types' => "gif|jpg|png|jpeg|pdf",
                        'file_name' => sha1(time())
                    );
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('files')) {
                        $data = array('upload_data' => $this->upload->data());
                        $path = $config['upload_path'] . '/' . $data['upload_data']['file_name'];
                        $uploaded_file_name = $data['upload_data']['file_name'];
                    }
                }
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
                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');

                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $target_service_id,
                                'lead_created_by' => $lead_by_pos_user, //178140614,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => 0,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'lead_by_pos_user' => $lead_by_pos_user,
                                'lead_by_post_user_name' => $lead_by_post_user_name,
                                'lead_package_name' => $customer_mail_package_name,
                                'lead_from' => 'GoldenCube',
                                'created_group_id' => $created_group_id,
                            );
                            if($user->is_corporate == 'true' || $user->is_corporate == true){
                                $insert_lead_array['is_corporate'] = 'Corporate';
                            }
                            if($user->applicant_name != "" && $user->applicant_name != NULL){
                                $insert_lead_array['applicant_name'] = $user->applicant_name;
                            }
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            if ($insert_lead_id > 0) {

                                //get branch name
                                $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                //create action log
                                $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 178140614
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
                                $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

                                $normal_lead_count++;
                            }
                        }
                    } else {
                        $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');

                        // else create one lead for selected category & service
                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => $service_id,
                            'lead_created_by' => $lead_by_pos_user, //178140614,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => 0,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                            'lead_package_name' => $customer_mail_package_name,
                            'lead_from' => 'GoldenCube',
                            'created_group_id' => $created_group_id,
                        );
                        if($user->is_corporate == 'true' || $user->is_corporate == true){
                            $insert_lead_array['is_corporate'] = 'Corporate';
                        }
                        if($user->applicant_name != "" && $user->applicant_name != NULL){
                            $insert_lead_array['applicant_name'] = $user->applicant_name;
                        }
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        $normal_lead_count = 1;

                        // 22587768 - Rawia
                    }
                    $lead_id = $insert_lead_id;
                    if ($normal_lead_count > 0) {

                        // $assigned_to = 2547057536; // moid.u@goldencube.ae
                        $assigned_to = $lead_by_pos_user;   // 188880683; - Mohamad.k@ontimegov.com   //    3020140166; - Basel.a@goldencube.ae
                        $assigned_by = $lead_by_pos_user;   //178140614;
                        // echo "<pre>";
                        // print_r($this->db);
                        // echo "</pre>";
                        // exit();
                        if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                            $this->response('Parameters Missing', 400);
                        } else {
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

                            $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

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
                                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $receiver_email = $csa->email;
                                    $receiver_name = $csa->first_name;
                                    $sender_email = $coordinator->email;
                                    $sender_name = $coordinator->first_name;

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

                                    $cc_usermail = [];
		                            array_push($cc_usermail, ["email" => "Hind.H@ontimegroup.com", "name" => "Hind"]);	// 323374798
		                            array_push($cc_usermail, ["email" => "team@goldencube.ae", "name" => "GoldenCube"]);	

                                     $email_array = array(
                                        'email' => $receiver_email,
                                        'cc' => $cc_usermail,
                                        'subject' => $subject,
                                        'template' => 'mails/template',
                                        'from_name' => "CRM ALERT",
                                        'message' => $message,
                                        'branch_id' => $lead_det["branch_id"],
                                    );
                                    $send_mail = send_template_email($email_array);

                                    $email_array = array(
                                        'email' => $lead_email,  
                                        'subject' => 'Goldencube - New Application created for you !',
                                        'template' => 'mails/gc_application_submit',
                                        'from_name' => "Golden Cube",
                                        'message' => $customer_mail_package_name,
                                        'branch_id' => $lead_det["branch_id"],
                                    );
                                    $send_mail = send_template_email($email_array);
                                    log_message('error', $send_mail);

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

                                    $queue_json = "";
                                    if(!empty($queue->payment_type) && !empty($queue->token_id)){
                                        $queue_token_id = $queue->token_id;
                                        // $queue_url = "https://paymentintegration.egovllc.com:8001/api/UpdateGoldenCube/crm/".$queue_token_id; // Testing URL
                                        $queue_url = "https://ontime.egovqueue.com/api/UpdateGoldenCube/crm/".$queue_token_id;  // Live URL
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $queue_url);
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        $response = curl_exec($ch);
                                        curl_close($ch);
                                        $queue_json = json_decode($response);
                                    }

                                    // Sending mail to the user for newly created leads from the Website
                                    $gc_lead_subject = "Lead Created for " . $lead_name . " 'Golden Cube' with ". $lead_id;
                                    $gc_lead_message = "<br>Dear User<br/><br/>" ;
                                    $gc_lead_message .= "A new lead has been created for the customer <strong> Golden Cube </strong> under the lead ID ". $lead_id .". Please review the details and take the necessary actions to follow up.";

                                    $cc_usermail = [];
                                    
                                    array_push($cc_usermail, ["email" => "fatima@ontimebiz.com", "name" => "Fatima Salih"]);	// 2845782377
                                    // array_push($cc_usermail, ["email" => "fatma@ontimebiz.com", "name" => "Fatma Abdollah"]);	// 3654913804
                                    // array_push($cc_usermail, ["email" => "anil.d@ontimegroup.com", "name" => "Anil"]);	// 2233119808
                                    array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
                                    array_push($cc_usermail, ["email" => "team@goldencube.ae", "name" => "GoldenCube"]);

                                    $to_usermail = [];
                                    array_push($to_usermail, ["email" => "Jan.j@goldencube.ae", "name" => "Jan"]);	// 1631471526
                                    array_push($to_usermail, ["email" => "Fitoun.F@goldencube.ae", "name" => "Fitoun"]);	// 883057153
                                    array_push($to_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali"]);	// 2411946200
                                    array_push($to_usermail, ["email" => "Salam.A@goldencube.ae", "name" => "Salam"]);	// 4213254981

                                    $email_array = array(
                                        'email' => $to_usermail,
                                        'cc' => $cc_usermail,
                                        'subject' => $gc_lead_subject,
                                        'template' => 'mails/template',
                                        'from_name' => "Golden Cube",
                                        'message' => $gc_lead_message,
                                        'branch_id' => $lead_det["branch_id"],
                                    );
                                    $send_mail = send_template_email($email_array);
                                    log_message('error', $send_mail);


                                    // $update = $this->mcommon->common_edit('leads', array('lead_status' => 320), array('id' => $lead_id));

                                    // $log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'GoldenCube WebApp Profile Submitted for Eligibility Checking.', 'action_by' => $assigned_by, 'status_id' => 320);
                                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $this->response(["lead_id" => $lead_id, "message" => 'Lead has been assigned successfully!'], 200);
                                } else {
                                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                                    // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                                    $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
                                }
                            } else {
                                $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign lead at present.'], 500);
                            }
                        }


                        $this->response(["lead_id" => $insert_lead_id, "message" => 'Lead has been created.',"statuscode"=>200], 200);
                    } else {
                        $this->response(["message"=>'Unable to create leads at this moment.',"statuscode"=>500], 500);
                    }
                }
            } else {
                $this->response(["message"=>'Unable to create leads at this moment.Please try again.',"statuscode"=>500], 500);
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    //Queue Goldencube
    public function queue_goldencube_post()
    {

        try {
            $profile = json_decode($this->post("profile"));
            $user = json_decode($this->post("user"));
            $document = json_decode($this->post("document"));
            $depend = json_decode($this->post("dependent_data"));
            $ontime_transid = json_decode($this->post("transid"));
            $queue = json_decode($this->post("queue_data"));

            if ($profile == '' || $document == '' || $user == '') {
                $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
            }

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            $branch_id = 106;
            $lead_type = 'normal';
            $lead_by_pos_user = '3729322111'; // goldencubewalkin@goldencube.ae
            // $lead_by_pos_user = '3946368694'; // goldencubeweb@goldencube.ae
            // '323374798'; // Hind.H@ontimegroup.com
            // 3020140166; // basel.a@goldencube.ae
            //178140614;   // info@ontimegroup.com
            $lead_by_post_user_name = 'Golden Cube Walkin';    //"Web API";

            //check category exist
            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
            }

            if ($lead_type == 'normal') {
                $category_id = 109;
                $service_id = 1009;

                //check category exist
                $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
                if ($cateogry_exist == 0) {
                    $this->response("Category doesn't exist. Update category first to create the lead", 404);
                }

                $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
                if ($service_exist == 0) {
                    $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
                }
            }

            $lead_name = $user->first_name . " " . $user->last_name;
            $lead_contact = ($user->mobile != NULL && trim($user->mobile) != '') ? $user->mobile : "520000000";
            $lead_email = $user->email;
            $lead_countrycode = ($user->countrycode != NULL && trim($user->countrycode) != '') ? $user->countrycode : "+971";
            $lead_remarks = "<br><b><u>GoldenCube Eligibility Check - Website Lead</u></b>,<br>";
            
            $customer_mail_package_name = '';
            if($ontime_transid == 266){
                $lead_remarks .= "Package:&nbsp;<b>" . $depend->package_name . "</b>,</br>";
                $lead_remarks .= "Dependent Package Name:&nbsp;<b>" . $depend->dependent_package_name. "</b>,</br>";
                $lead_remarks .= "Additional Service :&nbsp;<b>" . $depend->AdditionalService . "</b>,</br></br>";
                $lead_remarks .= "Name:&nbsp;<b>". $depend->dependent_first_name . " " . $depend->dependent_last_name . "</b>,</br>";
                $lead_remarks .= "Gender:&nbsp;<b>" . strtoupper($depend->dependent_gender). "</b>,</br>";
                $lead_remarks .= "Relationship:&nbsp;<b>" . $depend->dependent_relationship. "</b>,</br>";
                $lead_remarks .= "Nationality:&nbsp;<b>" . $depend->dependent_nationality. "</b></br></br>";
                $customer_mail_package_name = $depend->dependent_package_name;
            } else {
                $lead_remarks .= "Package:&nbsp;<b>" . $profile->package_name . "</b>,</br>";
                $lead_remarks .= "Additional Service :&nbsp;<b>" . $profile->AdditionalService . "</b>,</br></br>";
                $lead_remarks .= "Name:&nbsp;<b>" . $profile->first_name . " " . $profile->last_name . "</b>,</br>";
                $lead_remarks .= "Gender:&nbsp;<b>" . strtoupper($profile->gender) . "</b>,</br>";
                // $lead_remarks .= "DOB:&nbsp;<b>" . $profile->date_of_birth . " / " . $profile->age . "</b>,</br>";
                $lead_remarks .= "Nationality:&nbsp;<b>" . $profile->nationality . "</b></br></br>";
    
                if ($profile->property_address != "" && $profile->property_address != NULL)
                    $lead_remarks .= "Address:&nbsp;<b>" . $profile->property_address . "</b>,</br>";
                if ($profile->property_communication_email != "" && $profile->property_communication_email != NULL)
                    $lead_remarks .= "Communication Email:&nbsp;<b>" . $profile->property_communication_email . "</b>,</br>";
                if ($profile->property_mobile_number != "" && $profile->property_mobile_number != NULL)
                    $lead_remarks .= "Mobile number:&nbsp;<b>" . $profile->property_mobile_number . "</b>,</br>";
                $customer_mail_package_name = $profile->package_name;
            }

            if(!empty($queue->payment_type)){
                $lead_remarks .= "Payment Type : &nbsp;<b>" . $queue->payment_type . "</b>,</br>";
                $lead_remarks .= "IBAN :&nbsp;<b>" . $queue->iban_no . "</b>,</br>";
                $lead_remarks .= "Token :&nbsp;<b>" . $queue->token . "</b>,</br></br>";
                // $lead_remarks .= "Token Id :&nbsp;<b>" . $queue->token_id . "</b>,</br></br>";
            }

            if($profile->package_name == 'Visit Visa'){
                $lead_remarks .= "Sponser Name:&nbsp;<b>" . $profile->sponser_name . "</b>,</br>";
                $lead_remarks .= "Dependent's Relationship:&nbsp;<b>" . $profile->dependent_relationship . "</b>,</br>";
                $lead_remarks .= "Visa Duration:&nbsp;<b>" . $profile->visa_duration . "</b>,</br></br>";
            }

            if($profile->package_name == 'Visa Cancellation'){
                $lead_remarks .= "Making Visa Cancellation:&nbsp;<b>" . $profile->making_visa_cancellation . "</b>,</br>";
                if ($profile->making_visa_cancellation == "Outside UAE"){
                    $lead_remarks .= "Applicant Outside UAE for more then a Year:&nbsp;<b>" . $profile->outside_uae_more_then_year . "</b>,</br>";
                    $lead_remarks .= "Current Visa Valid or Expired:&nbsp;<b>" . $profile->current_visa_valid_expired . "</b>,</br>";
                }
                $lead_remarks .= "Applicant's Current Visa Type:&nbsp;<b>" . $profile->current_visa_type . "</b>,</br>";
                $lead_remarks .= "Currently sponsoring Family Member/Maid under your sponsorship:&nbsp;<b>" . $profile->sponsering_the_family . "</b>,</br>";
                if ($profile->sponsering_the_family == "Yes" || $profile->sponsering_the_family == "YES")
                    $lead_remarks .= "Cancel or Hold Family/Maid visa:&nbsp;<b>" . $profile->cancel_or_hold_maidvisa . "</b>,</br></br>";
            }

            if($profile->package_name != 'Interior Design' && $profile->package_name != "Medical Insurance")
                $lead_remarks .= "<br><b><u>Documents</u></b>,<br>";

            $prop_doc_count = count($document->property_document);
            $prop_doc_data = $document->property_document;

            $off_doc_count = count($document->official_document);
            $off_doc_data = $document->official_document;

            $depd_doc_count = count($document->dependency_document);
            $depd_doc_data = $document->dependency_document;

            $spon_doc_count = count($document->sponser_document);
            $spon_doc_data = $document->sponser_document;

            $fam_res_count = count($document->family_residence);
            $fam_res_data = $document->family_residence;

            $pare_res_count = count($document->parent_residence);
            $pare_res_data = $document->parent_residence;

            $per_info_trans_visa_res_count = count($document->update_personal_info_transfer_visa);
            $per_info_trans_visa_res_data = $document->update_personal_info_transfer_visa;

            $per_info_change_nationality_res_count = count($document->update_personal_info_change_nationality);
            $per_info_change_nationality_res_data = $document->update_personal_info_change_nationality;

            $per_info_lost_passport_res_count = count($document->update_personal_info_lost_passport);
            $per_info_lost_passport_res_data = $document->update_personal_info_lost_passport;

            $per_info_trans_visa_depen_res_count = count($document->update_personal_info_transfer_visa_dependent);
            $per_info_trans_visa_depen_res_data = $document->update_personal_info_transfer_visa_dependent;

            $per_info_change_nationality_depen_res_count = count($document->update_personal_info_change_nationality_dependent);
            $per_info_change_nationality_depen_res_data = $document->update_personal_info_change_nationality_dependent;

            $per_info_lost_passport_depen_res_count = count($document->update_personal_info_lost_passport_dependent);
            $per_info_lost_passport_depen_res_data = $document->update_personal_info_lost_passport_dependent;

            $pe_apartment_unit_res_count = count($document->property_evaluation_apartment_unit);
            $pe_apartment_unit_res_data = $document->property_evaluation_apartment_unit;

            $pe_villa_res_count = count($document->property_evaluation_villa);
            $pe_villa_res_data = $document->property_evaluation_villa;

            $pe_empty_land_res_count = count($document->property_evaluation_empty_land);
            $pe_empty_land_res_data = $document->property_evaluation_empty_land;

            $visit_visa_res_count = count($document->visit_visa);
            $visit_visa_res_data = $document->visit_visa;

            $visa_cancel_res_count = count($document->visa_cancellation);
            $visa_cancel_res_data = $document->visa_cancellation;

            $visa_cancel_family_res_count = count($document->visa_cancel_family_maid);
            $visa_cancel_family_res_data = $document->visa_cancel_family_maid;

            $other_doc_count = count($document->documents);
            $other_doc_data = $document->documents;

            // foreach ($document as $doc) {
            //     // $lead_remarks .= $doc->profile_doc_name . " : <a href=" . $doc->profile_doc_file . " target='_blank'>View File</a><br>";
            // }

            if($prop_doc_count > 0){
                $lead_remarks .= "<br><b><u>Property Documents</u></b><br>";
                foreach($prop_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($off_doc_count > 0){
                $lead_remarks .= "<br><b><u>Official Documents</u></b><br>";
                foreach($off_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($depd_doc_count > 0){
                $lead_remarks .= "<br><b><u>Dependency Documents</u></b><br>";
                foreach($depd_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($spon_doc_count > 0){
                $lead_remarks .= "<br><b><u>Sponser Documents</u></b><br>";
                foreach($spon_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($fam_res_count > 0){
                $lead_remarks .= "<br><b><u>Family Residence</u></b><br>";
                foreach($fam_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pare_res_count > 0){
                $lead_remarks .= "<br><b><u>Parents Residence</u></b><br>";
                foreach($pare_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_trans_visa_res_count > 0){
                $lead_remarks .= "<br><b><u>Transfer visa from old to new Passport - Documents</u></b><br>";
                foreach($per_info_trans_visa_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_change_nationality_res_count > 0){
                $lead_remarks .= "<br><b><u>Change Nationality Documents</u></b><br>";
                foreach($per_info_change_nationality_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_lost_passport_res_count > 0){
                $lead_remarks .= "<br><b><u>Lost Passport Documents</u></b><br>";
                foreach($per_info_lost_passport_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_trans_visa_depen_res_count > 0){
                $lead_remarks .= "<br><b><u>Transfer visa from old to new Passport for Dependent Documents</u></b><br>";
                foreach($per_info_trans_visa_depen_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_change_nationality_depen_res_count > 0){
                $lead_remarks .= "<br><b><u>Change Nationality for Dependent Documents</u></b><br>";
                foreach($per_info_change_nationality_depen_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($per_info_lost_passport_depen_res_count > 0){
                $lead_remarks .= "<br><b><u>Lost Passport for Dependent Documents</u></b><br>";
                foreach($per_info_lost_passport_depen_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pe_apartment_unit_res_count > 0){
                $lead_remarks .= "<br><b><u>Apartment unit Documents</u></b><br>";
                foreach($pe_apartment_unit_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pe_villa_res_count > 0){
                $lead_remarks .= "<br><b><u>Villa Documents</u></b><br>";
                foreach($pe_villa_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($pe_empty_land_res_count > 0){
                $lead_remarks .= "<br><b><u>Empty Land Documents</u></b><br>";
                foreach($pe_empty_land_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($visit_visa_res_count > 0){
                $lead_remarks .= "<br><b><u>Visit Visa Documents</u></b><br>";
                foreach($visit_visa_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($visa_cancel_res_count > 0){
                $lead_remarks .= "<br><b><u>Visa Cancellation Documents</u></b><br>";
                foreach($visa_cancel_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($visa_cancel_family_res_count > 0){
                $lead_remarks .= "<br><b><u>Visa Cancellation - Cancel the Family/Maid Visa Documents</u></b><br>";
                foreach($visa_cancel_family_res_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            if($other_doc_count > 0){
                foreach($other_doc_data as $data){
                    $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
                }
            }

            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($lead_email == '') ? $random_email : $lead_email;
            $lead_email = trim($lead_email);
            //create or get customer
            //$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);
            $user_id = 0;
            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

            $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

            if ($is_exist != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
            }

            if ($is_exist == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '1';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $lead_contact,
                    'referal_code' => $referal_code,
                    'first_name' => $lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = 'United Arab Emirates';
                $user_data['country_code'] = $lead_countrycode;    //'+971';
                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                //return $user_id;
            }


            if ($user_id != 0) {
                $uploaded_file_name = '';
                //Upload document and get the file name
                if (isset($_FILES['files']['name'])) {
                    $config = array(
                        'upload_path' => "../uploads/leads",
                        'allowed_types' => "gif|jpg|png|jpeg|pdf",
                        'file_name' => sha1(time())
                    );
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('files')) {
                        $data = array('upload_data' => $this->upload->data());
                        $path = $config['upload_path'] . '/' . $data['upload_data']['file_name'];
                        $uploaded_file_name = $data['upload_data']['file_name'];
                    }
                }
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
                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');

                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $target_service_id,
                                'lead_created_by' => $lead_by_pos_user, //178140614,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => 0,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'lead_by_pos_user' => $lead_by_pos_user,
                                'lead_by_post_user_name' => $lead_by_post_user_name,
                                'lead_package_name' => $customer_mail_package_name,
                                'lead_from' => 'GoldenCube',
                                'created_group_id' => $created_group_id,
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            if ($insert_lead_id > 0) {

                                //get branch name
                                $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                //create action log
                                $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 178140614
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                // $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
                                // $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

                                $normal_lead_count++;
                            }
                        }
                    } else {
                        $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');

                        // else create one lead for selected category & service
                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => $service_id,
                            'lead_created_by' => $lead_by_pos_user, //178140614,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => 0,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                            'lead_package_name' => $customer_mail_package_name,
                            'lead_from' => 'GoldenCube',
                            'created_group_id' => $created_group_id,
                        );
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        $normal_lead_count = 1;

                        // 22587768 - Rawia
                    }
                    $lead_id = $insert_lead_id;
                    if ($normal_lead_count > 0) {

                        // $assigned_to = 2547057536; // moid.u@goldencube.ae
                        $assigned_to = 796909261; 
                        // 796909261 - Dineli.s@goldencube.ae
                        // 2879029976 - jeffrey.s@goldencube.ae 
                        // 188880683; - Mohamad.k@ontimegov.com  
                        // 3020140166; - Basel.a@goldencube.ae
                        $assigned_by = $lead_by_pos_user;   //178140614;
                        // echo "<pre>";
                        // print_r($this->db);
                        // echo "</pre>";
                        // exit();
                        if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                            $this->response('Parameters Missing', 400);
                        } else {
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

                            $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

                            $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                            // print_r($log_insert_array);
                            $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                            // echo "Log: ".$log_insert."<br>";
                            // echo "ERROR: ";
                            // print_r($this->db->error());
                            // exit();

                            $postData = array(
                                'lead_id' => $lead_id,
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
                            $email_request_id = trim($response);
                            if(!empty($email_request_id))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $email_request_id), array('id' => $lead_id));

                            if ($insert > 0) {
                                $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                                if ($update) {
                                    //create action log
                                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $receiver_email = $csa->email;
                                    $receiver_name = $csa->first_name;
                                    $sender_email = $coordinator->email;
                                    $sender_name = $coordinator->first_name;

                                    $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you ! - ##RE-".trim($email_request_id)."##";
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

                                    $cc_usermail = [];
		                            array_push($cc_usermail, ["email" => "Hind.H@ontimegroup.com", "name" => "Hind"]);	// 323374798
                                    array_push($cc_usermail, ["email" => "team@goldencube.ae", "name" => "GoldenCube"]);

                                    $bcc_usermail = [];
                                    array_push($bcc_usermail, ["email" => "crm@ontimegroup.com", "name" => "CRM"]);

                                     $email_array = array(
                                        'email' => $receiver_email,
                                        'cc' => $cc_usermail,
                                        "bcc" => $bcc_usermail,
                                        'subject' => $subject,
                                        'template' => 'mails/template',
                                        'from_name' => "CRM ALERT",
                                        'message' => $message,
                                        'branch_id' => $lead_det["branch_id"],
                                    );
                                    $send_mail = send_template_email($email_array);

                                    $ccc_usermail = [];
		                            array_push($ccc_usermail, ["email" => "team@goldencube.ae", "name" => "Team"]);

                                    $email_array = array(
                                        'email' => $lead_email,  
                                        'cc' => $ccc_usermail,
                                        "bcc" => $bcc_usermail,
                                        'subject' => 'Goldencube - New Application created for you !- ##RE-'.trim($email_request_id).'##',
                                        'template' => 'mails/gc_application_submit',
                                        'from_name' => "Golden Cube",
                                        'message' => $customer_mail_package_name,
                                        'branch_id' => $lead_det["branch_id"],
                                    );
                                    $send_mail = send_template_email($email_array);
                                    log_message('error', $send_mail);

                                    if(!empty($queue->is_doc_upload) && $queue->is_doc_upload == 'no'){
                                        $email_array = array(
                                            'email' => $lead_email,
                                            'cc' => $ccc_usermail,
                                            "bcc" => $bcc_usermail,
                                            'subject' => 'Required Documents for Your Golden Visa Application- ##RE-'.trim($email_request_id). '##',
                                            'template' => 'mails/gc_document_submit',
                                            'from_name' => "Golden Cube",
                                            'message' => $customer_mail_package_name,
                                            'documents'=> $queue->doc_list,
                                            'branch_id' => $lead_det["branch_id"],
                                        );
                                        $send_mail = send_template_email($email_array);
                                        log_message('error', $send_mail);
                                    }

                                    $queue_json = "";
                                    if(!empty($queue->payment_type) && !empty($queue->token_id)){
                                        $queue_token_id = $queue->token_id;
                                        // $queue_url = "https://paymentintegration.egovllc.com:8001/api/UpdateGoldenCube/crm/".$queue_token_id; // Testing URL
                                        $queue_url = "https://ontime.egovqueue.com/api/UpdateGoldenCube/crm/".$queue_token_id;  // Live URL
                                        $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $queue_url);
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        $response = curl_exec($ch);
                                        curl_close($ch);
                                        $queue_json = json_decode($response);
                                    }

                                    // Sending mail to the user for newly created leads from the Website
                                    $gc_lead_subject = "Lead Created for " . $lead_name . " 'Golden Cube' with ". $lead_id . "- ##RE-".trim($email_request_id)."##";
                                    $gc_lead_message = "<br>Dear User<br/><br/>" ;
                                    $gc_lead_message .= "A new lead has been created for the customer <strong> Golden Cube </strong> under the lead ID ". $lead_id .". Please review the details and take the necessary actions to follow up.";

                                    $cc_usermail = [];
                                    
                                    // array_push($cc_usermail, ["email" => "fatima@ontimebiz.com", "name" => "Fatima Salih"]);	// 2845782377
                                    // array_push($cc_usermail, ["email" => "fatma@ontimebiz.com", "name" => "Fatma Abdollah"]);	// 3654913804
                                    // array_push($cc_usermail, ["email" => "anil.d@ontimegroup.com", "name" => "Anil"]);	// 2233119808
                                    // array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
                                    array_push($cc_usermail, ["email" => "fatima@goldencbe.ae", "name" => "Fatima"]);	// 2251243126

                                    $to_usermail = [];
                                    // array_push($to_usermail, ["email" => "Jan.j@goldencube.ae", "name" => "Jan"]);	// 1631471526
                                    // array_push($to_usermail, ["email" => "Fitoun.F@goldencube.ae", "name" => "Fitoun"]);	// 883057153
                                    // array_push($to_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali"]);	// 2411946200
                                    // array_push($to_usermail, ["email" => "Salam.A@goldencube.ae", "name" => "Salam"]);	// 4213254981
                                    array_push($to_usermail, ["email" => "team@goldencube.ae", "name" => "Team Goldencube"]);	// 4213254981


                                    $email_array = array(
                                        'email' => $to_usermail,
                                        'cc' => $cc_usermail,
                                        "bcc" => $bcc_usermail,
                                        'subject' => $gc_lead_subject,
                                        'template' => 'mails/template',
                                        'from_name' => "Golden Cube",
                                        'message' => $gc_lead_message,
                                        'branch_id' => '106',
                                    );
                                    $send_mail = send_template_email($email_array);
                                    log_message('error', $send_mail);


                                    // $update = $this->mcommon->common_edit('leads', array('lead_status' => 320), array('id' => $lead_id));

                                    // $log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'GoldenCube WebApp Profile Submitted for Eligibility Checking.', 'action_by' => $assigned_by, 'status_id' => 320);
                                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $this->response(["lead_id" => $lead_id, "message" => 'Lead has been assigned successfully!'], 200);
                                } else {
                                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                                    // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                                    $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
                                }
                            } else {
                                $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign lead at present.'], 500);
                            }
                        }


                        $this->response(["lead_id" => $insert_lead_id, "message" => 'Lead has been created.',"statuscode"=>200], 200);
                    } else {
                        $this->response(["message"=>'Unable to create leads at this moment.',"statuscode"=>500], 500);
                    }
                }
            } else {
                $this->response(["message"=>'Unable to create leads at this moment.Please try again.',"statuscode"=>500], 500);
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    //for geting status update form crm api call in golencube
    public function goldencubeleadstatus_post(){
        try{
            $lead_id = json_decode($this->post('lead_id'));
            $lead_det = $this->leads_model->lead_details($lead_id);
            if($lead_det){ 
                $this->response(["lead_data" => $lead_det, "message" => 'Lead status fetched sucessfully'], 200);
            } else {
                $this->response('Unable to fetch the leads at this moment.', 500);
            }

        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function goldencubeinvoicelead_post(){
        try{
            $lead_id = json_decode($this->post('lead_id'));
            $lead_det = $this->leads_model->lead_inv($lead_id);

           if($lead_det){ 
                $this->response(["lead_data" => $lead_det, "message" => 'Lead status fetched sucessfully'], 200);
            } else {
                $this->response('Unable to fetch the leads at this moment.', 500);
            }

        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function goldencubedashboard_post(){
        try{
            $lead_id = json_decode($this->post('lead_id'));
            $customer_data = $this->leads_model->get_customers_data($lead_id);
            $get_lead_det = $this->leads_model->leaddata_fromcustomer($customer_data);
            $lead_year = $this->leads_model->lead_count_year($customer_data);
            if($get_lead_det){
                $arr = []; $lead_timeline = [];
                foreach($get_lead_det as $data){
                    $lead_det = $this->leads_model->lead_details($data);
                    $lead_timeline_det = $this->leads_model->lead_timeline($data);
                    array_push($arr, $lead_det);
                    array_push($lead_timeline, $lead_timeline_det);
                }
            }
            $invoice_leads = $this->leads_model->invoice_leads_count($customer_data);
            
            if($arr){ 
                $this->response(["lead_data" => $arr, 
                    "timeline_data" => $lead_timeline, 
                    "invoice_count" => $invoice_leads, 
                    "lead_year" => $lead_year,
                    "message" => 'Lead status fetched sucessfully'], 200);
            } else {
                $this->response('Unable to fetch the leads at this moment.', 500);
            }

        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function goldencubeorder_post()
    {
        try {

            $req = json_decode($_POST["request"]);
            //$payment = json_decode($_POST["payment"]);
            $request = $req;

            // $payment = $payment;

            $crm_id = $req[0]->crm_id;
            $payment = $req[0]->payment;

            // echo $crm_id . "<br>";
            $lead_det = $this->leads_model->lead_details($crm_id);
            // print_r($lead_det);
            // exit();
            // echo "<br>".$lead_det["customer_id"];
            // print_r($request);
            foreach ($request as $item) {
                // print_r($item);
                $package = $item->package;
                $pack_name = $package->package_name;

                $category = "GC - " . $pack_name;
                $category_exist = $this->mcommon->specific_row('ontime_categories', array("category_name" => $category));

                if ($category_exist) {
                    $category_id = $category_exist["id"];
                } else {
                    $category_id = $this->mcommon->common_insert("ontime_categories", array("category_name" => $category));
                    $category_id = (int) $category_id;
                    $update_service = $this->mcommon->common_edit("ontime_categories", ["category_id" => $category_id], ["id" => $category_id]);
                }

                // print_r($item->payment_details);
                // exit();
                foreach ($item->payment_details as $paid_item) {

                    // print_r($category_id);
                    // print_r($paid_item);
                    $service = $paid_item->pack_service_name;
                    $service_desc = $paid_item->pack_service_short_desc;
                    $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

                    if ($service_exist) {
                        $service_id = $service_exist["id"];
                    } else {
                        $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                        $service_id = (int) $service_id;
                        $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                    }

                    $lead_remarks = "";
                    $lead_remarks .= "Name: " . $item->depend->dependent_first_name . " " . $item->depend->dependent_last_name;
                    $lead_remarks .= "<br>Gender: " . $item->depend->dependent_gender;
                    $lead_remarks .= "<br>Relationship: " . $item->depend->dependent_relationship;
                    $lead_remarks .= "<br>Nationality: " . $item->depend->dependent_nationality;
                    $lead_remarks .= "<br>Age: " . $item->depend->dependent_age . "<br><hr><br><b>Files</b><br>";

                    foreach ($item->doc as $ddoc) {
                        $lead_remarks .= $ddoc->ddoc_name . ": <a href='" . $ddoc->ddoc_file . "' target='_blank'>View File</a><br>";
                    }
                    // print_r($service_id);die;
                    // global $lead_det;
                    // print_r($lead_det["customer_id"]);
                    // $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 178140614, 'is_primary_group_id' => 1), 'group_id');

                    $insert_lead_array = array(
                        'customer_id' => $lead_det["customer_id"],
                        'branch_id' => $lead_det["branch_id"],
                        'category_id' => $category_id,
                        'service_id' => $service_id,
                        'lead_created_by' => 178140614,
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_depd_type' => $item->depend->dependent_relationship,
                        'lead_status' => 301,
                        'package_id' => 0,
                        'order_receipt' => 0,
                        'remarks' => $lead_remarks,
                        'lead_parent_id' => $crm_id,
                        'is_assigned' => 0,
                        'lead_by_pos_user' => $lead_det["lead_by_pos_user"],
                        'lead_by_post_user_name' => $lead_det["lead_by_post_user_name"],
                        // 'created_group_id' => $created_group_id,
                    );
                    // print_r($insert_lead_array);
                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                    // echo $insert_lead_id . "<br>";


                }
            }

            $lead_id = $crm_id;
            $order_id = $payment->profile_id;

            $log_insert_array = array('action_id' => 401, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead(s) has been created by <strong> Web API</strong> based on GoldenCube Profile #' . $order_id, 'action_by' => 178140614, 'status_id' => 301);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 178140614), 'pos_user_id');     //  info@ontimegroup.com
            if ($user_pos == 0 || $user_pos == NULL)
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 178140614), 'employee_id');     // info@ontimegroup.com
            // if ($user_pos == 0 || $user_pos == NULL)
            //     $user_pos = "crmonline";

            $name = $lead_det["customer_name"];
            $mobile = $lead_det["customer_mobile"];
            $email = $lead_det["customer_email"];
            $net_total = $payment->amount;


            $pos_req["Customer"] = array("Cust_EngName" => $name, "Cust_Mobile" => $mobile, "Cust_Email" => $email);
            $pos_req["OrderRef"] = "GC" . $order_id . "-OTLDPMET" . $lead_id;
            // $pos_req["Payment"] = array("ActAmt" => $net_total);

            $this->db->update("lead_action_log", array('remarks' => 'Lead has been created by <strong> Web API</strong> based on GoldenCube Web Order #GC' . $order_id . " under the OrderRef #" . $pos_req["OrderRef"]), array("id" => $insert_log));

            // $action_id
            $pos_req["ServDescription"] = $payment->package_name;
            $pos_req["salesorderdtl"] = [];

            // $pos_req["User"] = ["User_ID" => "crmonline"];
            $req["User"] = ["User_ID"=> $user_pos];
            $req["Payment_Type"] = "ONLINE";

            // POS Changes 
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
            $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
            $lead_det = $this->leads_model->lead_details($lead_id);

            if(!empty($lead_det["lead_zoho_id"])){
                $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                if(!empty($lead_created_by)){
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                    $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                } else {
                    $created_by_user = '';
                }

                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "CampaignSource" => $lead_det["lead_ad_campaign"],
                    "ZohoLeadSource" => $lead_det["lead_source"],
                    "CampaignId" => $lead_det["lead_ad_campaign_id"],
                    "ZohoLeadId" => $lead_det["lead_zoho_id"],
                    "LeadFrom" => 'Zoho',
                    "CRMLeadId" => $lead_id,
                    "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                );
                
            } else {
                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "LeadSource" => 'Website',
                    "LeadFrom" => $lead_det["lead_from"],
                    "CRMLeadId" => $lead_id,
                    "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
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
                    CURLOPT_POSTFIELDS => json_encode($pos_req),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                )
            );

            $response = curl_exec($curl);
            log_message('debug', $response);

            // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
            // $response = curl_exec($curl);
            $raw_response = $response;
            // print_r($raw_response);
            $res_json = json_decode($raw_response);
            // print_r($res_json);
            if (isset($res_json->Data->PMT_Number)) {
                $so_order = $res_json->Data->PMT_Number;
                $pos_cust_key = $res_json->Data->Cust_Key;
                $raw_salesorder = $so_order;
                $so_order = "under the payment receipt " . $so_order . "</b>";
            }
            // echo $curl_url;
            if (curl_errno($curl)) {
                $response = json_encode($req) . "<br>" . curl_error($curl);
                // print_r(curl_error($curl));
                curl_close($curl);
            } else {
                $response = json_encode($req) . "<br>" . $response;
                curl_close($curl);
            }

            $log_insert_array = array('action_id' => 415, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Online Payment completed for the GoldenCube Profile #' . $order_id . " " . $so_order, 'action_by' => 178140614, 'status_id' => 308);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);


            $update = $this->mcommon->common_edit("leads", array("lead_status" => 310, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($pos_req) . "<br>" . $raw_response . "<br>==>" . json_encode($payment),
            "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));



            $assigned_to = 22587768;
            $assigned_by = 178140614;
            $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
            $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();
            $receiver_email = $csa->email;
            $receiver_name = $csa->first_name;
            $sender_email = $coordinator->email;
            $sender_name = $coordinator->first_name;

            $subject = "Lead Payment Completed - #" . $lead_id;
            $message = "Dear " . $receiver_name . ",<br /><br />A Lead Payment is has been Completed <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:<br>";

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
                'branch_id' => $lead_det["branch_id"],
            );
            $send_mail = send_template_email($email_array);
            log_message('error', $send_mail);


            $this->response(["lead_id" => $crm_id, "message" => 'Sub Leads has been created'], 200);
        } catch (Exception $e) {
            $this->response(["lead_id" => $crm_id, "exception" => $e->getMessage()], 500);
        }
    }


    
       //  Baraha Van API start

       public function barahavan_post()
       {     
           try {
               $profile = json_decode($this->post("profile"));
               $user = json_decode($this->post("user"));
               $document = json_decode($this->post("document"));
               $depend = json_decode($this->post("dependent_data"));
               $ontime_transid = json_decode($this->post("transid"));
               $getapplicant_info = json_decode($this->post("customer_info"));
               $profession_info_detail = json_decode($this->post("profession_info_detail"));
               $booking_id_info =  json_decode($this->post("booking_id_info"));

               $booking_add_data = $this->mcommon->specific_row('calendar_appointment', array('booking_id' => $booking_id_info));

               $booking_created_time = $booking_add_data['created_at'];
               $booking_location = $booking_add_data['location_url'];
               $booking_package_info = $booking_add_data['package_name'];


               // $residency_documentsInfo = json_deoce($this->post("residency_documents"));
               // $embassy_documentsInfo = json_deoce($this->post("embassy_documents")); 
   
               if ($profile == '' || $document == '' || $user == '') {
                   $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
               }
   
               $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
               $random_string = substr(str_shuffle($str_result), 0, 10);
   
               $branch_id = 138;
               $lead_type = 'normal';
               $lead_by_pos_user = '2436357893';   //4242785381; //3020140166; //178140614;
               $lead_by_post_user_name = 'Calendar WEB';    //"Web API";
   
               $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
               if ($branch_exist == 0) {
                   $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
               }
               if ($lead_type == 'normal') {
                   $category_id = 109;
                   $service_id = 1009;
   
                   //check category exist
                   $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
                   if ($cateogry_exist == 0) {
                       $this->response("Category doesn't exist. Update category first to create the lead", 404);
                   }
   
                   $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
                   if ($service_exist == 0) {
                       $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
                   }
               }
   
               $lead_name = $user->first_name . " " . $user->last_name;
               $lead_contact = ($user->mobile != NULL && trim($user->mobile) != '') ? $user->mobile : "520000000";
               $lead_email = $user->email;
               $lead_countrycode = ($user->countrycode != NULL && trim($user->countrycode) != '') ? $user->countrycode : "+971";
               $lead_remarks = "<br><b><u>Mobile Medical Examination Appointment Booking - Website Lead</u></b>,<br>";
   
               $customer_mail_package_name = '';
   
               $lead_remarks .= "Package:&nbsp;<b>" . $profile->package_name . "</b>,</br>";
               $lead_remarks .= "Name:&nbsp;<b>" . $profile->first_name . " " . $profile->last_name . "</b>,</br>";

               if ($profile->gender != "" && $profile->gender != NULL)
               $lead_remarks .= "Gender:&nbsp;<b>" . strtoupper($profile->gender) . "</b>,</br>";
               // $lead_remarks .= "DOB:&nbsp;<b>" . $profile->date_of_birth . " / " . $profile->age . "</b>,</br>";
               if ($profile->nationality != "" && $profile->nationality != NULL)
               $lead_remarks .= "Nationality:&nbsp;<b>" . $profile->nationality . "</b></br></br>";
   
               if ($profile->property_address != "" && $profile->property_address != NULL)
                   $lead_remarks .= "Address:&nbsp;<b>" . $profile->property_address . "</b>,</br>";
               if ($profile->property_communication_email != "" && $profile->property_communication_email != NULL)
                   $lead_remarks .= "Communication Email:&nbsp;<b>" . $profile->property_communication_email . "</b>,</br>";
               if ($profile->property_mobile_number != "" && $profile->property_mobile_number != NULL)
                   $lead_remarks .= "Mobile number:&nbsp;<b>" . $profile->property_mobile_number . "</b>,</br>";
               $customer_mail_package_name = $profile->package_name;
   
               $residency_doc_count = count($document->residency_documents);
   
               $applicant_name = $getapplicant_info;

                /* Newly Added Code */
                $lead_remarks .= "<br><b><u>Applicant Information</u></b><br>";
                foreach ($applicant_name as $key => $entry) {
                    $lead_remarks .= "<br><b><u>Applicant Name</u></b><br>";
                    $lead_remarks .= "Name:&nbsp;<b>". $entry->full_name. "</b>,</br>";
                }
                /* Newly Added Code */

               foreach ($applicant_name as $key => $seriveInfo) {
   
               }
               $lead_remarks .= "<br><b><u>Serivce Package Information</u></b>,<br>";
               $lead_remarks .= "Service Name:&nbsp;<b>". $seriveInfo->package. "</b>,</br>";

               if ($seriveInfo->service_name != "" && $seriveInfo->service_name != NULL)
               $lead_remarks .= "Service Type:&nbsp;<b>".$seriveInfo->service_name. "</b>,</br>";

               if ($seriveInfo->sesidency_type != "" && $seriveInfo->sesidency_type != NULL)
               $lead_remarks .= "Visa issued from:&nbsp;<b>".$seriveInfo->sesidency_type. "</b>,</br>";

               if ($seriveInfo->package_name != "" && $seriveInfo->package_name != NULL)
               $lead_remarks .= "Package Name:&nbsp;<b>".$seriveInfo->package_name. "</b>,</br>";

               if ($seriveInfo->package_amount != "" && $seriveInfo->package_amount != NULL)
               $lead_remarks .= "Package Amount:&nbsp;<b>".$seriveInfo->package_amount. "</b>,</br>";
            
               if ($profession_info_detail != "" && $profession_info_detail != NULL)
               $lead_remarks .= "Profession Info: &nbsp;<b>".$profession_info_detail."</b></br>";

               if ($seriveInfo->address != "" && $seriveInfo->address != NULL)
               $lead_remarks .= "Address Info: &nbsp;<b>".$seriveInfo->address. "</b>,</br>";

               if ($seriveInfo->location_url != "" && $seriveInfo->location_url != NULL)
               $lead_remarks .= 'Location URL: &nbsp;<b><a href="'.$seriveInfo->location_url.'" target="_blank">'.$seriveInfo->location_url.'</a></b>,</br>';
   
   
               $lead_remarks .= "<br><b><u>Applicant Information</u></b><br>";
   
               foreach ($applicant_name as $key => $entry) {
   
                    // print_r($entry->documents->embassy);
                    $lead_remarks .= "<br><b><u>Applicant Name</u></b><br>";
                    $lead_remarks .= "Name:&nbsp;<b>". $entry->full_name. "</b>,</br>";
                    //  print_r($entry->documents->residency_documents);

                
                }
                 $lead_remarks .= "<br><b><u>Documents</u></b>,<br>";

                foreach ($applicant_name as $key => $entry) {

                    if(!empty($entry->documents->residency_documents)){
                    $lead_remarks .= "<br><b><u>Residency Documents</u></b><br>";

                        foreach ($entry->documents->residency_documents as $residency_doc_array) {
                            foreach ($residency_doc_array as $data) {  // Loop through each document object
                                $lead_remarks .= $data->filename . " : <a href='" . $data->doc_file . "' target='_blank'>".$data->filename."</a><br>";
                            }
                        }
                    }

                    if(!empty($entry->documents->embassy_documents)){
                        $lead_remarks .= "<br><b><u>Embassy Documents</u></b><br>";

                        foreach ($entry->documents->embassy_documents as $embassy_doc_array) {
                            foreach ($embassy_doc_array as $data) {  // Loop through each document object
                                $lead_remarks .= $data->filename . " : <a href='" . $data->doc_file . "' target='_blank'>".$data->filename."</a><br>";
                            }
                        }
                    } 
                }
   
               // print_r($applicant_name); exit;
               $residency_doc_data = $document->residency_documents;
   
               $embassy_doc_count = count($document->embassy);
               $embassy_doc_data = $document->embassy;
   
               // if($residency_doc_count > 0){
               //     $lead_rmearks .= "<br><b><u>Residency Documents</u></b><br>";
               //     foreach($residency_doc_data as $data){
               //         $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
   
               //         // print_r($lead_remarks); exit;
               //     }
               // } 
   
               // if($embassy_doc_count > 0){
               //     $lead_remarks .= "<br><b><u>Embassy Documents</u></b><br>";
               //     foreach($embassy_doc_data as $data){
               //         $lead_remarks .= $data->filename . " : <a href=" . $data->doc_file . " target='_blank'>".$data->filename."</a><br>";
   
               //         // print_r($lead_remarks);
               //     }
               // } 
               
               $random_email_name = strtolower($random_string);
               $random_email = $random_email_name . '@ontimecustomer.com';
               $lead_email = ($lead_email == '') ? $random_email : $lead_email;
               $lead_email = trim($lead_email);
               //create or get customer
               //$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);
               $user_id = 0;
               $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
               $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));
   
               $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));
   
               if ($is_exist != 0) {
                   $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
               }
   
               if ($is_exist == 0) {
                   $password = 'Welcome@123';
                   $confirm_password = 'Welcome@123';
                   $auth_level = '1';
                   $referal_code = $random_string;
                   $user_hashed_password = $this->authentication->hash_passwd($password);
                   $user_data = [
                       'auth_level' => $auth_level,
                       'mobile' => $lead_contact,
                       'referal_code' => $referal_code,
                       'first_name' => $lead_name,
                       'passwd' => $user_hashed_password,
                       'email' => trim($lead_email),
                       'confirm_password' => $user_hashed_password,
                   ];
                   $user_data['user_id'] = $this->authentication_model->get_unused_id();
                   $user_data['created_at'] = date('Y-m-d H:i:s');
                   $user_data['otp'] = rand(1000, 9000);
                   $user_data['email_otp'] = rand(1000, 9000);
                   $user_data['banned'] = '0';
                   $user_data['role_id'] = '4';
                   $user_data['country'] = 'United Arab Emirates';
                   $user_data['country_code'] = $lead_countrycode;    //'+971';
                   $insert = $this->mcommon->common_insert("lead_users", $user_data);
   
                   $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                   //return $user_id;
               }
   
   
               if ($user_id != 0) {
                   $uploaded_file_name = '';
                   //Upload document and get the file name
                   if (isset($_FILES['files']['name'])) {
                       $config = array(
                           'upload_path' => "../uploads/leads",
                           'allowed_types' => "gif|jpg|png|jpeg|pdf",
                           'file_name' => sha1(time())
                       );
                       $this->load->library('upload', $config);
   
                       if ($this->upload->do_upload('files')) {
                           $data = array('upload_data' => $this->upload->data());
                           $path = $config['upload_path'] . '/' . $data['upload_data']['file_name'];
                           $uploaded_file_name = $data['upload_data']['file_name'];
                       }
                   }

                   $getsubpackages = $seriveInfo->package_name." - ".$seriveInfo->package;

                   $package = $this->db->select("*")->from("lead_packages")->where("package_name", $getsubpackages)->get()->first_row();
                   $package_id =  $package->package_id;
                   $service_ids = $this->db->select("service_id")->from("lead_package_services")->where("package_id", $package_id)->get()->result_array();
                   $govt_fees = $this->db->select("govt_fee")->from("lead_package_services")->where("package_id", $package_id)->get()->result_array();
                   $typing_fees = $this->db->select("typing_fee")->from("lead_package_services")->where("package_id", $package_id)->get()->result_array();
                   $is_direct_invoice = $this->db->select("is_direct_invoice")->from("lead_package_services")->where("package_id", $package_id)->get()->result_array();
                   $msd_key = $this->db->select("msd_key")->from("lead_package_services")->where("package_id", $package_id)->get()->result_array();
                   $is_pos_typing_fee = $this->db->select("is_pos_typing_fee")->from("lead_package_services")->where("package_id", $package_id)->get()->result_array();
                //    var_dump($is_pos_typing_fee[0]['is_pos_typing_fee']); exit;
                   //process lead type
                   if ($lead_type == 'normal') {
                       $normal_lead_count = 0;
                       //get the workflow for the service.
                       $workflows = $this->leads_model->get_workflow_entries($service_id);
   
                       if (!empty($workflows)) {
                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');
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
                                   'lead_created_by' => $lead_by_pos_user, //178140614,
                                   'lead_added_on' => date('Y-m-d H:i:s'),
                                   'contactable_date' => date('Y-m-d H:i:s'),
                                   'lead_status' => 301,
                                   'package_id' => $package_id,
                                   'order_receipt' => 0,
                                   'remarks' => $lead_remarks,
                                   'is_assigned' => 0,
                                   'lead_by_pos_user' => $lead_by_pos_user,
                                   'lead_by_post_user_name' => $lead_by_post_user_name,
                                   'total_no_subleads' => count($service_ids),
                                   'no_of_open_subleads' => count($service_ids),
                                   'no_of_closed_subleads' => 0,
                                   'lead_from' => 'Baraha Van',
                                   'created_group_id' => $created_group_id
                               );
                               $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                               if ($insert_lead_id > 0) {
   
                                   //get branch name
                                   $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');
   
                                   //create action log
                                   $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 178140614
                                   $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
   
                                   $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
                                   $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
   
                                   $normal_lead_count++;
                               }
                           }
                       } else {
                            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');
                           // else create one lead for selected category & service
                           $insert_lead_array = array(
                               'customer_id' => $user_id,
                               'branch_id' => $branch_id,
                               'category_id' => $category_id,
                               'service_id' => $service_id,
                               'lead_created_by' => $lead_by_pos_user, //178140614,
                               'lead_added_on' => date('Y-m-d H:i:s'),
                               'contactable_date' => date('Y-m-d H:i:s'),
                               'lead_status' => 301,
                               'package_id' => $package_id,
                               'order_receipt' => 0,
                               'remarks' => $lead_remarks,
                               'is_assigned' => 0,
                               'lead_by_pos_user' => $lead_by_pos_user,
                               'lead_by_post_user_name' => $lead_by_post_user_name,
                               'total_no_subleads' => (count($service_ids) * count($getapplicant_info)),
                               'no_of_open_subleads' => (count($service_ids) * count($getapplicant_info)),
                               'no_of_closed_subleads' => 0,
                               'lead_from' => 'Baraha Van',
                               'created_group_id' => $created_group_id
                           );
                           $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                           $normal_lead_count = 1;
   
                           // 22587768 - Rawia
                       }
                       $lead_id = $insert_lead_id;
                       $parent_lead_id = $insert_lead_id;
                       $package_lead_count = 0;
                       $payment_type = 'online';

                       for ($ii = 0; $ii < count($getapplicant_info); $ii++) {
                            for ($i = 0; $i < count($service_ids); $i++) {
                                $card_amount = 0;
                                if ($payment_type == "card") {
                                    $card_amount = ($govt_fees[$i]['govt_fee'] * (1 / 100));
                                }else if ($payment_type == "online") {
                                        $card_amount = ($govt_fees[$i]['govt_fee'] * (2.25 / 100));
                                }else{
                                    $card_amount = 0;
                                }
                                $sub_totals[$i] = $govt_fees[$i]['govt_fee'] + $typing_fees[$i]['typing_fee'] + $card_amount;

                                $package_detail = array(
                                    "lead_id" => $parent_lead_id,
                                    "package_id" => $package_id,
                                    "service_id" => $service_ids[$i]['service_id'],
                                    "govt_fee" => $govt_fees[$i]['govt_fee'],
                                    "typing_fee" => $typing_fees[$i]['typing_fee'],
                                    "card_amount" => $card_amount,
                                    "sub_total" => $sub_totals[$i],
                                    "payment_type" => $payment_type,
                                    "created_by" => $lead_by_pos_user,
                                    "is_direct_invoice" => $is_direct_invoice[$i]['is_direct_invoice'],
                                    "msd_key" => $msd_key[$i]['msd_key'],
                                    "is_pos_typing_fee" => $is_pos_typing_fee[$i]['is_pos_typing_fee']
                                );
                                $this->mcommon->common_insert('lead_package_details', $package_detail);

                                $service_name = $this->mcommon->specific_row_value('ontime_category_services_', array('service_id' => $service_ids[$i]['service_id']), 'service_name');
                                $insert_lead_array = array(
                                    'customer_id' => $user_id,
                                    'branch_id' => $branch_id,
                                    'category_id' => $package->package_category_id,
                                    'service_id' => $service_ids[$i]['service_id'],
                                    'lead_created_by' => $lead_by_pos_user,
                                    'lead_added_on' => date('Y-m-d H:i:s'),
                                    'contactable_date' => date('Y-m-d H:i:s'),
                                    'lead_status' => 301,
                                    'package_id' => $package_id,
                                    'order_receipt' => 0,
                                    'remarks' => $service_name,
                                    'is_assigned' => 0,
                                    'lead_parent_id' => $parent_lead_id,
                                    "is_direct_invoice" => $is_direct_invoice[$i]['is_direct_invoice'],
                                    "govt_fee" => $govt_fees[$i]['govt_fee'],
                                    "typing_fee" => $typing_fees[$i]['typing_fee'],
                                    "msd_key" => $msd_key[$i]['msd_key'],
                                    "is_pos_typing_fee" => $is_pos_typing_fee[$i]['is_pos_typing_fee']
                                );
                                if ($payment_type != "cash") {
                                    $insert_lead_array["card_amount"] = $govt_fees[$i]['govt_fee'] * (1 / 100);
                                }
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            
                                if ($insert_lead_id > 0) {
                                    //create action log
                                    $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong>' . $user_init_firstname . '</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => $user_init_id, 'status_id' => 301);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                    $package_lead_count++;
                                }
                            }
                        }

                       if ($normal_lead_count > 0) {
   
                           // $assigned_to = 2547057536; // moid.u@goldencube.ae
                           $assigned_to = $lead_by_pos_user;   // 188880683; - Mohamad.k@ontimegov.com   //    3020140166; - Basel.a@goldencube.ae
                           $assigned_by = $lead_by_pos_user;   //178140614;
                           // echo "<pre>";
                           // print_r($this->db);
                           // echo "</pre>";
                           // exit();
                           if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                               $this->response('Parameters Missing', 400);
                           } else {
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

                               $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                                $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));
      
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
                                       $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                                       $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
   
                                       $receiver_email = $csa->email;
                                       $receiver_name = $csa->first_name;
                                       $sender_email = $coordinator->email;
                                       $sender_name = $coordinator->first_name;
   
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

                                   
   
                                       // $update = $this->mcommon->common_edit('leads', array('lead_status' => 320), array('id' => $lead_id));
   
                                       // $log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'GoldenCube WebApp Profile Submitted for Eligibility Checking.', 'action_by' => $assigned_by, 'status_id' => 320);
                                       // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
   
                                       $this->response(["lead_id" => $lead_id, "message" => 'Lead has been assigned successfully!'], 200);
                                   } else {
                                       $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                                       // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));
   
                                       $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
                                   }
                               } else {
                                   $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign lead at present.'], 500);
                               }
                           }
   
   
                           $this->response(["lead_id" => $insert_lead_id, "message" => 'Lead has been created.',"statuscode"=>200], 200);
                       } else {
                           $this->response(["message"=>'Unable to create leads at this moment.',"statuscode"=>500], 500);
                       }
                   }
               } else {
                   $this->response(["message"=>'Unable to create leads at this moment.Please try again.',"statuscode"=>500], 500);
               }
           } catch (Exception $e) {
               $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
           }
       }
   
       public function barahavandashboard_post(){
   
           try{
               $lead_id = json_decode($this->post('lead_id'));
               $customer_data = $this->leads_model->get_customers_data($lead_id);

               $get_lead_det = $this->leads_model->leaddata_fromcustomer_baraha($customer_data['customer_id']);
               $lead_year = $this->leads_model->lead_count_year($customer_data,"baraha");

               if($get_lead_det){
                   $arr = []; $lead_timeline = []; $lead_calender = [];
                   foreach($get_lead_det as $data){
                       $lead_det = $this->leads_model->bv_lead_details($data);
                       $lead_timeline_det = $this->leads_model->lead_timeline($data);
                       $lead_calender_det = $this->leads_model->lead_calender_details($data);
                       array_push($arr, $lead_det);
                       array_push($lead_timeline, $lead_timeline_det);
                       array_push($lead_calender, $lead_calender_det);
                   }
               }
               $invoice_leads = $this->leads_model->invoice_leads_count($customer_data);
               
               if($arr){ 
                   $this->response(["lead_data" => $arr, 
                       "calender_data" => $lead_calender, 
                       "timeline_data" => $lead_timeline, 
                       "invoice_count" => $invoice_leads, 
                       "lead_year" => $lead_year,
                       "message" => 'Lead status fetched sucessfully'], 200);
               } else {
                   $this->response('Unable to fetch the leads at this moment.', 500);
               }
   
           } catch (Exception $e) {
               $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
           }
       }
   
   
   
       public function barahavan_send_booking_crm_post(){
           try{
               $bookinfo = json_decode($this->post('booking_info'));
               $bookingId = json_decode($this->post('booking_id'));

               $crmbookStatus = json_decode($this->post('status'));
               $crmbookrefund_amt = json_decode($this->post('refund_amt'));
               $crmBookDate =  json_decode($this->post('booking_date'));
               $cromBookSlot =  json_decode($this->post('booking_timeslot'));
               $crmBookFine_amt = json_decode($this->post('fine_amt'));
               $crmBookfinalamt =  json_decode($this->post('amount'));


               $cromBookSlat =  json_decode($this->post('loc_latitude'));
               $cromBookSlong =  json_decode($this->post('loc_longitude'));
               $cromBookurl =  json_decode($this->post('location_url'));
               $cromBookLankmark =  json_decode($this->post('landmark'));
               $cromBookParking =  json_decode($this->post('is_parking'));
               $cromBookAddress =  json_decode($this->post('address'));
               $crmBookTyped = json_decode($this->post('if_typed'));


               $createdon = date('Y-m-d H:i:s');
               $recentbooked_data = array(
                //    'id'=> $bookinfo->id,
                   'booking_id'=>$bookingId,
                   'parent_customerid' => $bookinfo->parent_customerid,
                   'booking_branch'=>$bookinfo->booking_branch,
                   'booking_date' => $bookinfo->booking_date,
                   'booking_timeslot' => $bookinfo->booking_timeslot,
                   'booking_type' => $bookinfo->booking_type,
                   'location_url' => $bookinfo->location_url,
                   'landmark' => $bookinfo->landmark,
                   'is_parking' => $bookinfo->is_parking,
                   'status' => 901,
                   'updated_at'=>$bookinfo->updated_at,
                   'full_name'=>$bookinfo->full_name,
                   'phone'=> $bookinfo->phone,
                   'email'=> $bookinfo->email,
                   'source_type'=>$bookinfo->source_type,
                   'amount'=>$bookinfo->amount,
                   'lead_id'=>$bookinfo->lead_id,
                //    'booking_fullday'=>$bookinfo->booking_fullday,
                   'remark'=>$bookinfo->remark,
                   'calendar_id'=>$bookinfo->calendar_id,
                   'created_at	'=>$bookinfo->created_at,
                   'updated_at'=>$bookinfo->updated_at,
                   'created_by'=>$bookinfo->created_by,
                   'loc_latitude'=> $bookinfo->loc_latitude,
				   'loc_longitude'=>$bookinfo->loc_longitude,
                   'calendar_id'=>1,
                   'booking_fullday'=>'false',
                   'created_at'=>$createdon,
                   'created_by'=>'2436357893',
                   'calendar_name'=>'Baraha',
                   'service_info'=>$bookinfo->service_info,
					'package_name'=>$bookinfo->package_name,
				    'profession_info'=>$bookinfo->profession_info,
                    'applicant_id'=>$bookinfo->applicant_id,
                    'applicant_count'=>$bookinfo->applicant_count,
                    'parking_comments'=>$bookinfo->parking_comments,
                    'address'=>$bookinfo->address,
   
               );

            //    print_r($recentbooked_data); exit;

            $or_edit = array(
                'lead_id'=>$bookinfo->lead_id,
            );

            $result_type = $this->mcommon->records_all(('calendar_appointment'), array('booking_id' =>$bookingId));


            // $lead_info = $this->mcommon->specific_row('calendar_appointment', array('lead_id' => $bookinfo->lead_id));

           if(!empty($result_type[0]->booking_id)){

            $createdon = date('Y-m-d H:i:s');
            $crmCreatedBy ='2436357893';
            $crmCalendar_id= 1;
            $crmBooking_fullday= 'false';
            $crmCreated_at = $createdon;
            $crmCalendar_name= 'Baraha';



                $this->mcommon->common_edit('calendar_appointment', $or_edit,array('orderid' => $order_id));

                if($crmbookStatus == 902){
                    $log_insert_array = array('action_id' => 433, 'lead_id' => $result_type[0]->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been booked successfully without payment', 'action_by' => 4242785381, 'status_id' => 632);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 632), array('id' => $result_type[0]->lead_id));

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been booked successfully without payment',
                        'status_code' => 902, 
                        'status_description' => 'Booking Confirmed / Not Paid',
                        'created_by' => 2436357893, 
                        'created_at' => date('Y-m-d H:i:s'));
                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);

                    $update_status = $this->mcommon->common_edit('calendar_appointment', array('calendar_id'=>$crmCalendar_id,'booking_fullday'=>$crmBooking_fullday,'created_at'=>$crmCreated_at,'calendar_name'=>$crmCalendar_name,'created_by'=>$crmCreatedBy,'loc_latitude'=>$cromBookSlat,'loc_longitude'=>$cromBookSlong,'location_url'=>$cromBookurl,'landmark'=>$cromBookLankmark,'is_parking'=>$cromBookParking,'address'=>$cromBookAddress,'status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                    // $update_status = $this->mcommon->common_edit('calendar_appointment', array('status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                } else if ($crmbookStatus == 903){
                    $log_insert_array = array('action_id' => 434, 'lead_id' => $result_type[0]->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been booked successfully with payment', 'action_by' => 4242785381, 'status_id' => 633);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 633), array('id' => $result_type[0]->lead_id));

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been booked successfully with payment',
                        'status_code' => 903, 
                        'status_description' => 'Booking Confirmed / Paid',
                        'created_by' => 2436357893, 
                        'created_at' => date('Y-m-d H:i:s'));
                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);
                    $update_status = $this->mcommon->common_edit('calendar_appointment', array('calendar_id'=>$crmCalendar_id,'booking_fullday'=>$crmBooking_fullday,'created_at'=>$crmCreated_at,'calendar_name'=>$crmCalendar_name,'created_by'=>$crmCreatedBy,'loc_latitude'=>$cromBookSlat,'loc_longitude'=>$cromBookSlong,'location_url'=>$cromBookurl,'landmark'=>$cromBookLankmark,'is_parking'=>$cromBookParking,'address'=>$cromBookAddress,'status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                    // $update_status = $this->mcommon->common_edit('calendar_appointment', array('status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                }
                else if ($crmbookStatus == 904){
                    $log_insert_array = array('action_id' => 435, 'lead_id' => $result_type[0]->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been Resheduled', 'action_by' => 4242785381, 'status_id' => 634);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 634), array('id' => $result_type[0]->lead_id));

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been Resheduled',
                        'status_code' => 904, 
                        'status_description' => 'Reshedule',
                        'created_by' => 2436357893, 
                        'created_at' => date('Y-m-d H:i:s'));
                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);
                    $update_status = $this->mcommon->common_edit('calendar_appointment', array('calendar_id'=>$crmCalendar_id,'booking_fullday'=>$crmBooking_fullday,'created_at'=>$crmCreated_at,'calendar_name'=>$crmCalendar_name,'created_by'=>'2436357893','loc_latitude'=>$cromBookSlat,'loc_longitude'=>$cromBookSlong,'status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt,'fine_amt'=>$crmBookFine_amt,'amount'=>$crmBookfinalamt), array('booking_id' => $bookingId));


                    $lead_id = $bresult_type[0]->lead_id;
                    $getData = [
                        'lead_id' => $result_type[0]->lead_id,
                        'remark' => "Reschedule",
                        'is_direct_invoice' => 130,
                        'user_id' => '2436357893'
                    ];

                    $sendcreatesublead = $this->sendcreatesublead($getData);
                    $result = json_decode($sendcreatesublead);
                    $res_fine_amt = 200;

                    if ($result->status == true) {
                        $newsub_lead_id = $result->newsublead_id;

                        $data = [
                            'typing_fee' => $res_fine_amt
                        ];

                        $update_result=$this->mcommon->common_edit('leads',$data,array("id"=>$newsub_lead_id));

                
                
                        $crypt_amount = $this->encrypt($res_fine_amt);

                        $lead_id = $result_type[0]->lead_id;
                        //    $leadDetails = Lead::getLeadDetails($lead_id);
                        $customer_name = $result_type[0]->full_name;
                        $customer_email = $result_type[0]->email;
                        // $customer_email = 'mathanraj.g@mitrahsoft.in';
                        $agent_name = $user[0]->email;
                        $message = "Dear " . $customer_name . ",<br /><br />";
                        $pre_token = $lead_id . "-" . $customer_email . "-@OnTimeCRM11..";

                        $user_pos = $user[0]->pos_user_id;
                        if ($user_pos == 0 || $user_pos == NULL)
                            $user_pos = $user[0]->employee_id;
                        if ($user_pos == 0 || $user_pos == NULL)
                            $user_pos = "crmonline";
                        $user_details = $user->first_name . " " . $user->last_name . "(" . $user->user_id . ")";
                
                        $token1 = md5($pre_token);
                        $token2 = md5(strrev($pre_token));
                        $token = $token1 . "-" . $token2;
                        $current_timestamp = date('Y-m-d H:i:s');
                        $amount_payment = $res_fine_amt;
                
                        $insertId = array(
                            'lead_id' => $lead_id,
                            'action_amount' => $amount_payment,
                            'action_id' => 412,
                            'status_id' => 307,
                            'action_by' => 2436357893,
                            'action_on' => $current_timestamp,
                            'remarks' => '',
                            "bot_id" => 0
                        );

                        $insertId1 = $this->mcommon->common_insert('lead_action_log', $insertId);

                        $inserted_id = $this->db->insert_id();


                        $log_id = $this->encrypt($inserted_id);
                        $payment_link = "https://crm.ontimegroup.com/payment/ccpay?ref=" . $token . "&token=" . $crypt_amount . "&identity=" . $log_id;

                        $action_message = $message . "<p></p><div class='payment'><a href=" . $payment_link . "><button style='font-weight: bold;background: #00287c;color: white;border: 0;padding: 13px 30px;border-radius: 1px;box-shadow: 2px 2px 9px -2px black;'>PAY NOW</button></a><p style='margin-top: 10px;margin-bottom: 30px;'>Note: Please use the above <b>PAY NOW</b> button to pay <b>" . $amount_payment . "</b> AED before " . date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+7 hours')) . " to prevent from expire.</p></div><p></p>Sincerly,<br /><br />" . $agent_name . "<br />ONTIME GROUP";
                        
                            $data = [
                                'payment_link' => $payment_link,
                                'remarks' => '<pre>' . $action_message . '</pre>',
                            ];
    
                            $update_result=$this->mcommon->common_edit('lead_action_log',$data,array("id"=>$inserted_id));


                        $action_message1 = "Appointment id " . $appointment->id . " As Per Policy For Reschedule Due to " . $re_reason . " and Excess Charge : " .  $amount_payment . "AED";
                        
                        $data = [
                            'lead_id' => $lead_id,
                            'action_amount' =>  $amount_payment,
                            'action_id' => 435,
                            'status_id' => 634,
                            'action_by' => $userId,
                            'action_on' => $current_timestamp,
                            'remarks' => '<pre>' . $action_message1 . '</pre>',
                            "bot_id" => 0
                        ];

                        $insertId2=$this->mcommon->common_edit('lead_action_log',$data,array("id"=>$inserted_id));

                        $inserted_id2 = $this->db->insert_id();
                        
                        
                    } else {
                        return response()->json([
                        'success' => false,
                        'message' => $result->message
                        ]);
                    }

                    $startTime_booking_res = DateTime::createFromFormat('g:i a', $cromBookSlot);
                    $endTime_booking_res = clone $startTime_booking_res;
                    $endTime_booking_res->modify('+1 hour');
                    $currentDateTime = new DateTime();
                    $currentDateTime->modify('+7 hours');

                    $bv_message_res = [
                        'booking_reference'=>$bookingId,
                        'customer_name'=> $result_type[0]->full_name,
                        'package_name'=> $result_type[0]->package_name,
                        'service_info'=>$result_type[0]->service_info,
                        'date_time'=>$crmBookDate .' - ' .$startTime_booking_res->format('h:i a') . " - " . $endTime_booking_res->format('h:i a'),
                        'booking_location'=>$result_type[0]->location_url,
                        'fine_amt'=>$res_fine_amt,
                        'payment_link'=>$payment_link,
                        'payment_due'=> $currentDateTime->format('Y-m-d H:i:s'),

                        
                    ];
                    // echo $result_type[0]->email; exit;
                    $cc_usermail = [];
                    array_push($cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "Mobile Medical Examination"]);
                        $email_array_res = array(
                            'email' => $result_type[0]->email,  
                            'cc'=> $cc_usermail,
                            'subject' => 'Mobile Medical Examination : Appointment rescheduled – Payment Pending',
                            'template' => 'mails/bv_slot_confirm',
                            'from_name' => "OntimeGov",
                            'from_email'=>'mobile.medical@ontimegov.com',
                            'message' => $bv_message_res,
                        );
                        $send_mail_res = send_template_email($email_array_res);
                        log_message('error', $send_mail_res);
                       
                       // $update_status = $this->mcommon->common_edit('calendar_appointment', array('status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                }
                else if ($crmbookStatus == 905){
                    $log_insert_array = array('action_id' => 436, 'lead_id' => $result_type[0]->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been cancelled', 'action_by' => 4242785381, 'status_id' => 635);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 635), array('id' => $result_type[0]->lead_id));

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been cancelled',
                        'status_code' => 905, 
                        'status_description' => 'Cancelled',
                        'created_by' => 2436357893, 
                        'created_at' => date('Y-m-d H:i:s'));
                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);
                    $update_status = $this->mcommon->common_edit('calendar_appointment', array('calendar_id'=>$crmCalendar_id,'booking_fullday'=>$crmBooking_fullday,'created_at'=>$crmCreated_at,'calendar_name'=>$crmCalendar_name,'created_by'=>'2436357893','loc_latitude'=>$cromBookSlat,'loc_longitude'=>$cromBookSlong,'status' =>$crmbookStatus,'refund_amt'=>$crmbookrefund_amt,'if_typed'=>$crmBookTyped), array('booking_id' => $bookingId));
                   
                    $startTime_booking_res = DateTime::createFromFormat('g:i a', $cromBookSlot);
                    $endTime_booking_res = clone $startTime_booking_res;
                    $endTime_booking_res->modify('+1 hour');

                    $bv_message_can = [
                        'booking_reference'=>$result_type[0]->id,
                        'customer_name'=> $result_type[0]->full_name,
                        'package_name'=> $result_type[0]->package_name,
                        'service_info'=>$result_type[0]->service_info,
                        'date_time'=>$crmBookDate .' - ' .$startTime_booking_res->format('h:i a') . " - " . $endTime_booking_res->format('h:i a'),
                        'booking_location'=>$result_type[0]->location_url,
                        'refund_amt'=>$crmbookrefund_amt,
                        'fine_amt'=>$crmBookfineamt,
                        
                    ];
                    $cc_usermail = [];
                    array_push($cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "Mobile Medical Examination"]);
                       $email_array = array(
                           'email' => $result_type[0]->email,  
                           'cc'=> $cc_usermail,
                           'subject' => 'Mobile Medical Examination: Appointment Slot is Cancelled',
                           'template' => 'mails/bv_cancel_confirm',
                           'from_name' => "OntimeGov",
                           'from_email'=>'mobile.medical@ontimegov.com',
                           'message' => $bv_message_can,
                       );
                       $send_mail = send_template_email($email_array);
                       log_message('error', $send_mail);
                    // $update_status = $this->mcommon->common_edit('calendar_appointment', array('status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                } else if ($crmbookStatus == 901){



                    // $send_mail = send_template_email($email_array);

                    //    $bv_message = [
                    //     'booking_reference'=>$bookingId,
                    //     'customer_name'=> $bookinfo->full_name,
                    //     'package_name'=> $bookinfo->package_name,
                    //     'service_info'=>$bookinfo->service_info,
                    //     'date_time'=>$bookinfo->booking_date .' - ' .$bookinfo->booking_timeslot,
                    //     'booking_location'=>$bookinfo->location_url,
                        
                    // ];

                    //    $email_array = array(
                    //        'email' => $bookinfo->email,  
                    //        'subject' => 'Baraha Van - New Booking created for you !',
                    //        'template' => 'mails/bv_slot_pending',
                    //        'from_name' => "OntimeGov",
                    //        'from_email'=>'mobiletruck@ontimegov.com',
                    //        'message' => $bv_message,
                    //    );
                    //    $send_mail = send_template_email($email_array);
                    //    log_message('error', $send_mail);



                   
                    $log_insert_array = array('action_id' => 432, 'lead_id' => $result_type[0]->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been booking with pending', 'action_by' => 4242785381, 'status_id' => 631);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 631), array('id' => $result_type[0]->lead_id));

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been booking with pending status',
                        'status_code' => 901, 
                        'status_description' => 'Pending',
                        'created_by' => 2436357893,  //4242785381
                        'created_at' => date('Y-m-d H:i:s'));

                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);
                    $update_status = $this->mcommon->common_edit('calendar_appointment', array('calendar_id'=>$crmCalendar_id,'booking_fullday'=>$crmBooking_fullday,'created_at'=>$crmCreated_at,'calendar_name'=>$crmCalendar_name,'created_by'=>'2436357893','loc_latitude'=>$cromBookSlat,'loc_longitude'=>$cromBookSlong,'location_url'=>$cromBookurl,'landmark'=>$cromBookLankmark,'is_parking'=>$cromBookParking,'address'=>$cromBookAddress,'status' =>$crmbookStatus,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));
                    // print_r($this->db->last_query()); exit;

                    // $update_status = $this->mcommon->common_edit('calendar_appointment', array('status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                } else if ($crmbookStatus == 906){
                    $log_insert_array = array('action_id' => 437, 'lead_id' => $result_type[0]->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been cancelled', 'action_by' => 4242785381, 'status_id' => 636);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 636), array('id' => $result_type[0]->lead_id));

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been cancelled',
                        'status_code' => $crmbookStatus, 
                        'status_description' => 'Cancelled',
                        'created_by' => 2436357893, 
                        'created_at' => date('Y-m-d H:i:s'));
                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);
                    $update_status = $this->mcommon->common_edit('calendar_appointment', array('calendar_id'=>$crmCalendar_id,'booking_fullday'=>$crmBooking_fullday,'created_at'=>$crmCreated_at,'calendar_name'=>$crmCalendar_name,'created_by'=>'2436357893','loc_latitude'=>$cromBookSlat,'loc_longitude'=>$cromBookSlong,'location_url'=>$cromBookurl,'landmark'=>$cromBookLankmark,'is_parking'=>$cromBookParking,'address'=>$cromBookAddress,'status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                    // $update_status = $this->mcommon->common_edit('calendar_appointment', array('status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                }
                else {
                    $log_insert_array = array('action_id' => 438, 'lead_id' => $result_type[0]->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been Noshow with Refund', 'action_by' => 4242785381, 'status_id' => 637);
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 637), array('id' => $result_type[0]->lead_id));

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been cancelled',
                        'status_code' => $crmbookStatus, 
                        'status_description' => 'Cancelled',
                        'created_by' => 2436357893, 
                        'created_at' => date('Y-m-d H:i:s'));
                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);
                    $update_status = $this->mcommon->common_edit('calendar_appointment', array('calendar_id'=>$crmCalendar_id,'booking_fullday'=>$crmBooking_fullday,'created_at'=>$crmCreated_at,'calendar_name'=>$crmCalendar_name,'created_by'=>'2436357893','loc_latitude'=>$cromBookSlat,'loc_longitude'=>$cromBookSlong,'location_url'=>$cromBookurl,'landmark'=>$cromBookLankmark,'is_parking'=>$cromBookParking,'address'=>$cromBookAddress,'status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                    // $update_status = $this->mcommon->common_edit('calendar_appointment', array('status' =>$crmbookStatus,'booking_timeslot'=>$cromBookSlot,'booking_date'=>$crmBookDate,'refund_amt'=>$crmbookrefund_amt), array('booking_id' => $bookingId));

                }

           } else{
         
                if($bookinfo->booking_date != "" &&  $bookinfo->booking_timeslot != "" &&  $bookinfo->lead_id !=''){



                    $insert_log = $this->mcommon->common_insert('calendar_appointment', $recentbooked_data);

                    $inserted_id = $this->db->insert_id();

                    $startTime_booking = DateTime::createFromFormat('g:i a', $bookinfo->booking_timeslot);
                    $endTime_booking = clone $startTime_booking;
                    $endTime_booking->modify('+1 hour');

                    
                    $bv_message = [
                        'booking_reference'=>$inserted_id,
                        'customer_name'=> $bookinfo->full_name,
                        'package_name'=> $bookinfo->package_name,
                        'service_info'=>$bookinfo->service_info,
                        'date_time'=>$bookinfo->booking_date .' - ' .$startTime_booking->format('h:i a') . " - " . $endTime_booking->format('h:i a'),
                        'booking_location'=>$bookinfo->location_url,
                        
                    ];
                    // print_r($bv_message); exit;
                    $cc_usermail = [];
                    array_push($cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "Mobile Medical Examination"]);
                       $email_array = array(
                           'email' => $bookinfo->email,  
                           'cc'=> $cc_usermail,
                           'subject' => 'Mobile Medical Examination - New Booking created for you !',
                           'template' => 'mails/bv_slot_pending',
                           'from_name' => "OntimeGov",
                           'from_email'=>'mobile.medical@ontimegov.com',
                           'message' => $bv_message,
                       );
                       $send_mail = send_template_email($email_array);
                       log_message('error', $send_mail);


                       $bv_alert = "Dear Admin,<br /><br />New Appointment is created for Mobile Medical Examination. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $bookinfo->lead_id . " .<br><br><b>Lead Description:</b><br>";
                        $bv_alert .= "Customer Name: " . $bookinfo->full_name;
                       $bv_alert .= "<br>Customer Contact: " . $bookinfo->email;
                       $bv_alert .= "<br>Customer Email: " . $bookinfo->phone;

                       $bv_alert .= "<br><br><b>Booking Information:</b> ";
                       $bv_alert .= "<br>Booking Reference ID: " . $inserted_id;
                       $bv_alert .= "<br>Package Name: " . $bookinfo->package_name;
                       $bv_alert .= "<br>Service Info: " . $bookinfo->service_info;
                       $bv_alert .= "<br>Booking Location: " . $bookinfo->location_url;
                       $bv_alert .= "<br>Booking Address: " . $bookinfo->address;
                       $bv_alert .= "<br>Booking Date: " . $bookinfo->booking_date;
                       $bv_alert .=  "<br>Booking Time: " . $startTime_booking->format('h:i a') . " - " . $endTime_booking->format('h:i a');
                       $bv_alert .= "<br>Amount :" . $bookinfo->amount." AED";

                       $cc_usermail = [];
                       array_push($cc_usermail, ["email" => "hanna.h@egovllc.com", "name" => "Hanna Hussain"]);
                       array_push($cc_usermail, ["email" => "mobile.medical@ontimegov.com", "name" => "Mobile Medical Examination"]);	// 2251243126


                       $email_array_alert = array(
                        'email' => 'mobile.medical@ontimegov.com',  
                        'cc'=> $cc_usermail,
                        'subject' => 'Mobile Medical Examination - New Booking created Alert !',
                        'template' => 'mails/template',
                        'from_name' => "CRM Alert",
                        // 'from_email'=>'mobile.medical@ontimegov.com',
                        'message' => $bv_alert,
                    );
                    $send_mail = send_template_email($email_array_alert);
                       log_message('error', $send_mail);

                    
                    $log_insert_array = array('action_id' => 432, 'lead_id' => $bookinfo->lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Calendar Appointment has been created by <strong> Web API</strong>', 'action_by' => 2436357893, 'status_id' => 631);
                    $insert_action_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $cal_log_insert_array = array(
                        'appointment_id' => $bookingId, 
                        'remark' => 'Calendar Appointment has been created by <strong> Web API</strong>',
                        'status_code' => 901, 
                        'status_description' => 'Pending for Confirmation',
                        'created_by' => 2436357893, 
                        'created_at' => date('Y-m-d H:i:s'));
                    $insert_cal_action_log = $this->mcommon->common_insert('calendar_log', $cal_log_insert_array);

                    $update = $this->mcommon->common_edit('leads', array('lead_status' => 631), array('id' => $bookinfo->lead_id));

                }
           }
           } catch (Exception $e) {
               $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
           }
       }

       public function barahavaninvoicelead_post(){
            try{
                $lead_id = json_decode($this->post('lead_id'));
                $lead_det = $this->leads_model->bv_lead_inv($lead_id);
                // var_dump($lead_det); exit;

                $customer_data = $this->leads_model->get_customers_data($lead_id);
                $get_lead_det = $this->leads_model->leaddata_fromcustomer($customer_data);

                if($get_lead_det){
                    $lead_calender = [];
                    foreach($get_lead_det as $data){
                        $lead_calender_det = $this->leads_model->lead_calender_details($data);
                        array_push($lead_calender, $lead_calender_det);
                    }
                }
                $invoice_leads = $this->leads_model->invoice_leads_count($customer_data);

            if($lead_det){ 
                    $this->response(["lead_data" => $lead_det, 
                     "calender_data" => $lead_calender,
                     "invoice_count" => $invoice_leads, 
                     "message" => 'Lead status fetched sucessfully'], 200);
                } else {
                    $this->response('Unable to fetch the leads at this moment.', 500);
                }

            } catch (Exception $e) {
                $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
            }
       }

       public function barahavan_lead_details_post(){

        try{

            $lead_id = json_decode($this->post("lead_id"));

            $booked_data = $this->mcommon->records_all(('leads'), array('lead_parent_id' => $lead_id));
         

            // foreach ($booked_data as $lead_data) {   
            //     if ($lead_data->remarks == 'Typing Fee') {
            //             if($lead_data->lead_status){ 
            //                 $this->response([
            //                     "type_fee_status" => $lead_data->lead_status, 
                            
            //                     "message" => 'Lead status fetched sucessfully'], 200);
            //             } 
            //         }
            // }

            foreach ($booked_data as $lead_data) {   
                // Check if remarks starts with or ends with "Medical"
                if (strpos($lead_data->remarks, 'Medical Typing') === 0 || 
                    strpos($lead_data->remarks, 'Embassy Medical') === 0 || 
                    strpos($lead_data->remarks, 'Medical Typing') === (strlen($lead_data->remarks) - strlen('Medical Typing')) || 
                    strpos($lead_data->remarks, 'Embassy Medical') === (strlen($lead_data->remarks) - strlen('Embassy Medical'))) {
                       $typing_fee = $lead_data->typing_fee;
                       $govt_fee= $lead_data->govt_fee;

                    if ($lead_data->lead_status) { 
                        $this->response([
                            "typing_fee"=>$typing_fee,
                            "govt_fee"=>$govt_fee,
                            "type_fee_status" => $lead_data->lead_status, 
                            "message" => 'Lead status fetched successfully'
                        ], 200);
                    } 
                } else {
                    $typing_fee = $lead_data->typing_fee;
                    $govt_fee= $lead_data->govt_fee;
                    $this->response([
                        "typing_fee"=>$typing_fee,
                        "govt_fee"=>$govt_fee,
                        "type_fee_status" => $lead_data->lead_status, 
                        "message" => 'Lead status fetched successfully'
                    ], 200);
                }
            }
            // exit;

        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }

       }


    // Al Adheed
    public function aladheed_post()
    {

        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle($str_result), 0, 10);
        // $branch_id = '18';
        // $lead_type = $this->post('lead_type');//visa or uae_visa
        $lead_name = $this->post('customer_name');
        $lead_country = $this->post('country');
        $lead_country_code = $this->post('country_code');
        $lead_contact = $this->post('customer_mobile');
        $lead_email = $this->post('customer_email');
        $lead_remarks = $this->post('description');
        $order_id = $this->post('order_id');
        $transaction_name = $this->post('transaction_name');
        $lead_email = trim($lead_email);

        $category_id = '106';
        $service_id = '1008';


        $this->form_validation->set_rules('country_code', 'Country phone code', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('customer_mobile', 'Contact Number', 'required');
        $this->form_validation->set_rules('customer_email', 'Email Address', 'required');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        // $this->form_validation->set_rules('lead_type', 'Lead Type', 'required');


        if ($this->form_validation->run() == TRUE) {


            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

            $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

            if ($is_exist != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
                return $user_id;
            }

            if ($is_exist == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '4';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $lead_contact,
                    'referal_code' => $referal_code,
                    'first_name' => $lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = $lead_country;
                $user_data['country_code'] = $lead_country_code;

                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                //return $user_id;
            }

            if ($user_id != 0) {
                $normal_lead_count = 0;

                $insert_lead_array = array(
                    'customer_id' => $user_id,
                    'order_id' => $order_id,
                    'order_date' => date('Y-m-d'),
                    'transaction_name' => $transaction_name,
                    'order_status' => 101,
                    'details' => $lead_remarks,
                );
                $insert_lead_id = $this->mcommon->common_insert('aladheed_orders', $insert_lead_array);
                $normal_lead_count = 1;

                if ($normal_lead_count > 0) {
                    //create action log
                    $this->response(array("status" => "success", "result" => 'Order has been created in CRM.'), 200);
                } else {
                    $this->response(array("status" => "error", "result" => 'Unable to create order at this moment.Please try again.'), 500);
                }
            } else {
                $this->response(array("status" => "error", "result" => 'Unable to create order at this moment.Please try again.'), 500);
            }
        } else {
            $this->response(array("status" => "error", "result" => validation_errors()), 400);
        }
    }


    // Smart Ejari

    public function smartejari_post()
    {

        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle($str_result), 0, 10);
        // $branch_id = '18';
        // $lead_type = $this->post('lead_type');//visa or uae_visa
        $lead_name = $this->post('customer_name');
        $lead_country = "United Arab Emirates";
        $lead_country_code = "+971";
        $lead_contact = $this->post('customer_mobile');
        $lead_email = $this->post('customer_email');
        $lead_remarks = $this->post('description');
        $order_id = $this->post('order_id');
        $transaction_name = $this->post('transaction_name');
        $lead_email = trim($lead_email);

        $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
        $this->form_validation->set_rules('customer_mobile', 'Contact Number', 'required');
        $this->form_validation->set_rules('customer_email', 'Email Address', 'required');
        $this->form_validation->set_rules('order_id', 'Order ID', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        // $this->form_validation->set_rules('lead_type', 'Lead Type', 'required');


        if ($this->form_validation->run() == TRUE) {


            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

            $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

            if ($is_exist != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
                return $user_id;
            }

            if ($is_exist == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '4';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $lead_contact,
                    'referal_code' => $referal_code,
                    'first_name' => $lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = $lead_country;
                $user_data['country_code'] = $lead_country_code;

                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                //return $user_id;
            }

            if ($user_id != 0) {
                $normal_lead_count = 0;

                $insert_lead_array = array(
                    'customer_id' => $user_id,
                    'order_id' => $order_id,
                    'order_date' => date('Y-m-d'),
                    'transaction_name' => $transaction_name,
                    'order_status' => 101,
                    'details' => $lead_remarks,
                );
                $insert_lead_id = $this->mcommon->common_insert('smartejari_orders', $insert_lead_array);
                $normal_lead_count = 1;

                if ($insert_lead_id > 0) {
                    //create action log
                    $this->response(array("status" => "success", "result" => 'Order has been created in CRM.'), 200);
                } else {
                    // echo $this->db->last_query();
                    $this->response(array("status" => "error", "result" => 'Unable to create order at this moment.Please try again.'), 500);
                }
            } else {
                $this->response(array("status" => "error", "result" => 'Unable to create order at this moment.Please try again.'), 500);
            }
        } else {
            $this->response(array("status" => "error", "result" => validation_errors()), 400);
        }
    }
     public function ontimegovservpackage_post()
    {
        $name = $this->post('name');
        $email = $this->post('email');
        $mobile = $this->post('mobile');
        $order_ref = $this->post('order_ref');
        $trans_name = $this->post('trans_name');
        $card_num = $this->post('card_num');
        $transaction_number = $this->post('transaction_number');
        $receipt_no = $this->post('receipt_no');
        $sla = $this->post('sla');
        $order_id = $this->post('order_id');
        $order_details_id = $this->post('order_details_id');
        $service_id = $this->post('service_id');
        $net_total = $this->post('net_total');
        $description = $this->post('description');
        $package_details = json_decode($this->post('package_details'));
        $order_details = json_decode($this->post('order_details'));
        $order_status = 301;
        $pos_category_id = $this->post('pos_category_id');
        $pos_service_id = $this->post('pos_service_id');
        $postgovt_fee = $this->post('govt_fee');
        $posttyping_fee = $this->post('typing_fee');
        $postdelivery_fee = 115;
        $postdelivery_id= $this->post('delivery_id');
        $number =  $mobile ; // mobile number
        $number = (strpos($number, '971') === 0) ? substr($number, 3) : $number;
        $mobile = $number;
        
        

        if (
            $name == '' || $email == '' || $mobile == '' || $order_ref == '' ||
            $trans_name == '' || $sla == '' || $order_id == '' || $order_details_id == '' ||
            $net_total == '' || $description == ''
        ) {
            $this->response('Parameters Missing', 400);
        }

        $order_exist = $this->mcommon->specific_record_counts('leads', array('otg_order_id' => $order_id));
        if ($order_exist != 0) {
            $this->response(array('status' => 'error', 'result' => "Already lead exists against the order id"), 204);
        }

        // Customer Logic
        $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile));
        $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $email));

        if ($check_email_exists != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }

        if ($check_mobile_exists != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $mobile), 'user_id');
        }

        if ($check_mobile_exists == 0 && $check_email_exists == 0) {
            $password = 'Welcome@123';
            $confirm_password = 'Welcome@123';
            $auth_level = '4';
            $referal_code = time();
            $user_hashed_password = $this->authentication->hash_passwd($password);
            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $mobile,
                'referal_code' => $referal_code,
                'first_name' => $name,
                'passwd' => $user_hashed_password,
                'email' => trim($email),
                'confirm_password' => $user_hashed_password,
            ];
            $user_data['user_id'] = $this->authentication_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');
            $user_data['otp'] = rand(1000, 9000);
            $user_data['email_otp'] = rand(1000, 9000);
            $user_data['banned'] = '0';
            $user_data['role_id'] = '4';
            $user_data['country'] = 'United Arab Emirates';
            $user_data['country_code'] = '971';
            $insert = $this->mcommon->common_insert("lead_users", $user_data);

            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }

        if ($user_id != '') {
            $insert_array = array(
                'user_id' => $user_id,
                'pos_category_id' => $pos_category_id,
                'pos_service_id' => $pos_service_id,
                'order_ref' => $order_ref,
                'trans_name' => $trans_name,
                'card_num' => $card_num,
                'transaction_number' => $transaction_number,
                'receipt_no' => $receipt_no,
                'sla' => $sla,
                'order_id' => $order_id,
                'order_details_id' => $order_details_id,
                'service_id' => $service_id,
                'net_total' => $net_total,
                'description' => $description,
                'order_details' => json_encode($order_details),
                'item_status' => $order_status,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert = $this->mcommon->common_insert('otg_orders', $insert_array);

            $branch_id = 119;
            $category_id = 10009;
            $service = $trans_name;
            $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

                            if ($service_exist) {

                                $service_id = $service_exist["id"];
                            
                            } else {
                              
                                $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                                
                                $service_id = (int) $service_id;
                                
                                $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                            }


            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');
            // Leads
            $insert_lead_array = array(
                'customer_id' => $user_id,
                'branch_id' => $branch_id,
                'category_id' => $category_id,
                'service_id' => $service_id,
                'lead_created_by' => 4294967295,
                'lead_added_on' => date('Y-m-d H:i:s'),
                'contactable_date' => date('Y-m-d H:i:s'),
                'lead_status' => 301,
                'order_receipt' => 0,
                'remarks' => $description,
                'is_assigned' => 0,
                'otg_order_id' => $order_id,
                'otg_order_detail_id' => $order_details_id,
                'lead_from' => 'OntimeGOV',
                'created_group_id' => $created_group_id,
            );
            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
            $parent_lead_id = $insert_lead_id;

            $log_insert_array = array(
                'action_id' => 401,
                'lead_id' => $insert_lead_id,
                'action_on' => date('Y-m-d H:i:s'),
                'remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTimeGov Web Order #' . $order_id,
                'action_by' => 4294967295,
                'status_id' => 301
            );
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

            $lead_id = $parent_lead_id; // Parent Lead

            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'pos_user_id');     //  info@ontimegov.com
            if ($user_pos == 0 || $user_pos == NULL)
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => 4294967295), 'employee_id');     // info@ontimegov.com
            // if ($user_pos == 0 || $user_pos == NULL)
            //     $user_pos = "crmonline";

            $req["Customer"] = array("Cust_EngName" => $name, "Cust_Mobile" => $mobile, "Cust_Email" => $email);
            $req["OrderRef"] = "SE" . $order_id . "-" . $insert_log . '-OTLDPMET' . $parent_lead_id;
            $req["Payment"] = array("ActAmt" => $net_total, "OnlinePaymentRef" => $transaction_number);

            $this->db->update("lead_action_log", array('remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTimeGov Web Order #SE' . $order_id . " under the OrderRef #" . $req["OrderRef"]), array("id" => $insert_log));

            $req["ServDescription"] = $trans_name;
            $req["salesorderdtl"] = [];

            // $req["User"] = ["User_ID" => "crmonline"];
            $req["User"] = ["User_ID"=> $user_pos];
            $req["Payment_Type"] = "ONLINE";

            // POS Changes 
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
            $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
            $lead_det = $this->leads_model->lead_details($lead_id);

            if(!empty($lead_det["lead_zoho_id"])){
                $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                if(!empty($lead_created_by)){
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                    $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                } else {
                    $created_by_user = '';
                }

                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "OnlinePaymentRef" => $transaction_number,
                    "CampaignSource" => $lead_det["lead_ad_campaign"],
                    "ZohoLeadSource" => $lead_det["lead_source"],
                    "CampaignId" => $lead_det["lead_ad_campaign_id"],
                    "ZohoLeadId" => $lead_det["lead_zoho_id"],
                    "LeadFrom" => 'Zoho',
                    "CRMLeadId" => $lead_id,
                    "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                );
                
            } else {
                $req["Payment"] = array(
                    "ActAmt" => $net_total, 
                    "OnlinePaymentRef" => $transaction_number,
                    "LeadSource" => 'Website',
                    "LeadFrom" => $lead_det["lead_from"],
                    "CRMLeadId" => $lead_id,
                    "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
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
            $raw_response = $response;
            $res_json = json_decode($raw_response);

            if (isset($res_json->Data->PMT_Number)) {
                $so_order = $res_json->Data->PMT_Number;
                $pos_cust_key = $res_json->Data->Cust_Key;
                $raw_salesorder = $so_order;
                $so_order = "under the payment receipt " . $so_order . "</b>";
            }

            if (curl_errno($curl)) {
                $response = json_encode($req) . "<br>" . curl_error($curl);
                curl_close($curl);
            } else {
                $response = json_encode($req) . "<br>" . $response;
                curl_close($curl);
            }

            $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));

            $this->mcommon->common_edit("lead_action_log", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $insert_log));

            $assigned_to = 2113278237; //cc@ontimegroup.com
            $assigned_by = 4294967295;

            if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                $this->response('Parameters Missing', 400);
            } else {
                $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                $insert_array = array(
                    'lead_id' => $lead_id,
                    'assigned_by' => $assigned_by,
                    'assigned_to' => $assigned_to,
                    'assigned_on' => date('Y-m-d H:i:s')
                );
                $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

                $log_insert_array = array(
                    'action_id' => 403,
                    'lead_id' => $lead_id,
                    'action_on' => date('Y-m-d H:i:s'),
                    'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>',
                    'action_by' => $assigned_by,
                    'status_id' => 303
                );
                $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                if ($insert > 0) {
                    $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                    if ($update) {
                        $log_insert_array = array(
                            'action_id' => 402,
                            'lead_id' => $lead_id,
                            'action_on' => date('Y-m-d H:i:s'),
                            'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>',
                            'action_by' => $assigned_by,
                            'status_id' => 302
                        );
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $receiver_email = $csa->email;
                        // $receiver_email = 'app.zenerom@gmail.com';
                        $receiver_name = $csa->first_name;
                        $sender_email = $coordinator->email;
                        $sender_name = $coordinator->first_name;

                        $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you!";
                        $message = "Dear " . $receiver_name . ",<br /><br />A Lead has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $lead_id . ".<br><br>Lead Description:<br>";

                        $lead_det = $this->leads_model->lead_details($lead_id);

                        $postData = array(
                            'lead_id' => $lead_id,
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
                            $update = $this->mcommon->common_edit('leads', array('email_request_id' => $response), array('id' => $lead_id));

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
                        log_message('error', $send_mail);
                    } else {
                        $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                    }
                }
            }

            if (!empty($package_details->package_description)) {
                $packageArray = explode(", ", $package_details->package_description);

                foreach ($order_details as $orderdet) {
                    if ($orderdet->relation != 'Self') {
                        foreach ($packageArray as $value) {
                            $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $value));

                            if ($service_exist) {
                                $service_id = $service_exist["id"];
                            } else {
                                $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $value));
                                $service_id = (int) $service_id;
                                $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
                            }

                            $sub_remark = "";
                            $sub_remark .= "Package Name: <strong>" . $package_details->package_name . "</strong><br>";
                            $sub_remark .= "Applicant Name: <strong>" . $orderdet->applicantname . "- " . $orderdet->relation . "</strong><br><br>";
                            $sub_remark .= "<h6>Documents</h6><br>";
                            

                            foreach ($orderdet->order_doc as $doc) {
                                $sub_remark .= "<strong>" . $doc->docname . "</strong>: <a target='_blank' href='https://ontimegov.com/test/assets/uploads/" . $doc->documentfilename . "'>File Download</a><br>";
                            }

                            // Create sub-leads
                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $service_id,
                                'lead_created_by' => 4294967295,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'order_receipt' => 0,
                                'govt_fee' => $postgovt_fee,
                                'remarks' => $sub_remark,
                                'is_assigned' => 0,
                                'lead_parent_id' => $parent_lead_id,
                                'lead_from' => 'OntimeGOV'
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);

                             $insert_lead_array1 = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $service_id,
                                'lead_created_by' => 4294967295,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'order_receipt' => 0,
                                'typing_fee' => $posttyping_fee,
                                'remarks' => $sub_remark,
                                'is_assigned' => 0,
                                'lead_parent_id' => $parent_lead_id,
                                'lead_from' => 'OntimeGOV'
                            );
                            $insert_lead_id1 = $this->mcommon->common_insert('leads', $insert_lead_array1);

                            if($postdelivery_id != '0' && $postdelivery_id != null){
                             $insert_lead_array2 = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $service_id,
                                'lead_created_by' => 4294967295,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'order_receipt' => 0,
                                'additional_govt_fee' => $postdelivery_fee,
                                'remarks' => $sub_remark,
                                'is_assigned' => 0,
                                'lead_parent_id' => $parent_lead_id,
                                'lead_from' => 'OntimeGOV'
                            );
                            $insert_lead_id2 = $this->mcommon->common_insert('leads', $insert_lead_array2);
                        }
                            if ($insert_lead_id > 0) {
                                // Create action log
                                $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> from <strong>#OTLD' . $parent_lead_id . '</strong>', 'action_by' => 4294967295, 'status_id' => 301);
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                            }
                        }
                    }


                }
            }



            if ($insert > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Lead created successfully',
                    'order_id' => $order_id,
                    'order_details_id' => $order_details_id,
                    'lead_id' => $lead_id,
                    'order_ref' => $order_ref,
                    'transaction_number' => $transaction_number,
                    'receipt_no' => $receipt_no,
                    'pos_so_response' => $raw_salesorder,
                ], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Failed to create lead'
                ], 400);
            }
        }
    }

    public function fetch_zoho_social_token(){
        $zoho_settings_data = $this->mcommon->specific_row('zoho_settings', ["zoho_type" => 'Zoho Social', 'zoho_email' => 'team@ontimehealthcare.com']);
        if(!empty($zoho_settings_data)){
            $zoho_access_token = $zoho_settings_data['access_token'];
            $zoho_token_response = $zoho_settings_data['token_response'];
            $zoho_expiry_time = $zoho_settings_data['expired_at'];

            $current_time = gmdate('Y-m-d H:i:s');
            if($current_time < $zoho_expiry_time){
                $expires_in = strtotime($zoho_expiry_time) - time();
                $zoho_response = [
                    'access_token' => $zoho_access_token,
                    'token_response' => $zoho_token_response,
                    'expires_in' => $expires_in
                ];
                // $update_zoho_settings_data = $this->mcommon->common_edit('zoho_settings', [
                //     'server_current_time' => gmdate('Y-m-d H:i:s'),
                // ], ['zoho_type' => 'Zoho Social', 'zoho_email' => 'team@ontimehealthcare.com']);
                return $zoho_response;
            } else {
                $token_data = $this->get_zohotoken();
                $accessToken = $token_data['access_token'];
                if($token_data['status'] == 'failure'){
                    $this->response(['status' => 'failure', 'message' => $token_data['error_description']], 500);
                }
                // $expiry_time = gmdate('Y-m-d H:i:s', time() + $token_data['expires_in']);
                $expiry_time = gmdate('Y-m-d H:i:s', strtotime('+1 hour'));
                $update_zoho_settings_data = $this->mcommon->common_edit('zoho_settings', [
                    'access_token' => $accessToken,
                    'token_response' => json_encode($token_data),
                    'expired_at' => $expiry_time,
                    'server_current_time' => gmdate('Y-m-d H:i:s'),
                ], ['zoho_type' => 'Zoho Social', 'zoho_email' => 'team@ontimehealthcare.com']);
                return [
                    'access_token' => $accessToken,
                    'token_response' => json_encode($token_data),
                    'expires_in' => $token_data['expires_in']
                ];
            }
        } 
    }

    public function fetch_zoho_webchats_token(){
        $zoho_settings_data = $this->mcommon->specific_row('zoho_settings', ["zoho_type" => 'Zoho Webchats', 'zoho_email' => 'webchats@ontimegroup.com']);
        if(!empty($zoho_settings_data)){
            $zoho_access_token = $zoho_settings_data['access_token'];
            $zoho_token_response = $zoho_settings_data['token_response'];
            $zoho_expiry_time = $zoho_settings_data['expired_at'];

            $current_time = gmdate('Y-m-d H:i:s');
            if($current_time < $zoho_expiry_time){
                $expires_in = strtotime($zoho_expiry_time) - time();
                $zoho_response = [
                    'access_token' => $zoho_access_token,
                    'token_response' => $zoho_token_response,
                    'expires_in' => $expires_in
                ];
                // $update_zoho_settings_data = $this->mcommon->common_edit('zoho_settings', [
                //     'server_current_time' => gmdate('Y-m-d H:i:s'),
                // ], ['zoho_type' => 'Zoho Webchats', 'zoho_email' => 'webchats@ontimegroup.com']);
                return $zoho_response;
            } else {
                $token_data = $this->get_webchat_zohotoken();
                $accessToken = $token_data['access_token'];
                if($token_data['status'] == 'failure'){
                    $this->response(['status' => 'failure', 'message' => $token_data['error_description']], 500);
                }
                // $expiry_time = gmdate('Y-m-d H:i:s', time() + $token_data['expires_in']);
                $expiry_time = gmdate('Y-m-d H:i:s', strtotime('+1 hour'));
                $update_zoho_settings_data = $this->mcommon->common_edit('zoho_settings', [
                    'access_token' => $accessToken,
                    'token_response' => json_encode($token_data),
                    'expired_at' => $expiry_time,
                    'server_current_time' => gmdate('Y-m-d H:i:s'),
                ], ['zoho_type' => 'Zoho Webchats', 'zoho_email' => 'webchats@ontimegroup.com']);
                return [
                    'access_token' => $accessToken,
                    'token_response' => json_encode($token_data),
                    'expires_in' => $token_data['expires_in']
                ];
            }
        } 
    }

    // webchats
    public function get_webchat_zohotoken()
    {
        $apiUrl = 'https://accounts.zoho.com/oauth/v2/token';
        $data = [
            "client_id" => '1000.6WCLOT6V37DJRKXNIVODWIZ75ENLHB',
            "client_secret" => 'a13c91feaca699214277818eaf8fd381933f24e4b1',
            "grant_type" => 'client_credentials',
            "scope" => 'ZohoCRM.modules.ALL',
            "soid" => 'ZohoCRM.855907088'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            return json_decode($response, true);
        } else {
            return ['error' => 'Login failed'];
        }
    }

    public function zoho_leads_webchats_post()
    {
        $page_count = 0;
        $last_count = 0;
        $leads_array = [];
        try {
            while (true) {
                // $token_data = $this->get_webchat_zohotoken();
                // $accessToken = $token_data['access_token'];

                $token_data = $this->fetch_zoho_webchats_token();
                $accessToken = $token_data['access_token'];

                $api_url = "https://www.zohoapis.com/crm/v2/Leads?sort_order=desc&sort_by=Created_Time&page=" . $page_count;

                $request_headers = [
                    'Content-Type:' . 'application/json',
                    'Accept:' . 'application/json',
                    'Authorization: Zoho-oauthtoken ' . $accessToken
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                $result = curl_exec($ch);
                $response = json_decode($result, true);
                // var_dump($response); exit;
                $load = $response["data"];
                $info = $response["info"];

                if ($last_count == $info["count"]) {
                    $this->response(["created_leads" => $leads_array, "message" => 'No Leads has been created in the Ontime CRM - Zoho CRM', "statuscode" => 200], 200);
                }

                $dsn = array(
                    'dsn'   => 'mysql:host=localhost;dbname=test',
                    'hostname' => 'localhost',
                    'username' => 'crm_root',
                    'password' => 'df240b484c',
                    'database' => 'test',
                    'dbdriver' => 'pdo',  // Use 'pdo' for PDO connection or 'mysqli' for MySQLi
                    'dbprefix' => '',
                    'pconnect' => FALSE,
                    'db_debug' => (ENVIRONMENT !== 'production'),
                    'cache_on'  => FALSE,
                    'cachedir'  => '',
                    'char_set'  => 'utf8',
                    'dbcollat'  => 'utf8_general_ci',
                    'return_type' => 'array',
                    'save_queries' => TRUE
                );

                $this->db1 = $this->load->database($dsn, true);

                $last_count = $info["count"];
                $lead_id = 0;
                foreach ($load as $data) {
                    $lead = [];
                    $lead["lead_remarks"] = '';
                    $layout_details = $data['Layout'];
                    $layout_id = $layout_details['id'];
                    $layout_name = $layout_details['name'];

                    $To_Branch = $data['To_Branch'];
                    $department = $data['Department'];
                    $brand = $data['Brand'];
                    
                    // var_dump($data); 

                    if (($To_Branch != "Mazaya Govermement Services")
                        && ($To_Branch != "BusinessVenue Government Services")
                        && ($To_Branch != "Al Baraha Government Services")
                        && ($To_Branch != "Baraha")
                        && ($To_Branch != "Al Manara")
                        && ($To_Branch != "MOH") 
                        && ($To_Branch != "RTA") 
                        && ($To_Branch != "BurDubai")
                        && ($To_Branch != "Mazaya MOH") 

                        && ($To_Branch != "OnTime Healthcare") && ($To_Branch != "Ontime Healthcare")
                        // && ($To_Branch != "Doctor OnTime") && ($To_Branch != "Doctor Ontime")
                        && ($To_Branch != "OnTime Trustee") && ($To_Branch != "Trustee")
                        && ($To_Branch != "GoldenCube")
                        && ($To_Branch != "Attestation")
                        && ($To_Branch != "Translation")
                        && ($To_Branch != "Al Taweyyat") && ($To_Branch != "Al Tasweyaat") && ($To_Branch != "Al Tasweyyat")
                        && ($To_Branch != "Notary Public")
                        && ($To_Branch != "Ontime")
                        && ($To_Branch != "OnTime Gov")
                        && ($To_Branch != "Business Lounge") && ($To_Branch != "Baraha Business Lounge")
                        && ($To_Branch != "OntimeGOV DLD Department")
                    ){
                        continue;
                    }

                    $createdTime = $data['Created_Time'];  // "2026-02-07T12:02:30+04:00";
                    $createdDate = new DateTime($createdTime);
                    $createdDate->setTime(0, 0, 0);
                    $limitDate = new DateTime("2026-02-10");
                    $limitDate->setTime(0, 0, 0);

                    if ($createdDate < $limitDate) {
                        continue;
                    }

                    $owner_details = $data['Owner'];

                    $lead_owner = $owner_details['email'];
                    $zoho_id = $data["id"];

                    try {
                        $check_zohoid_exists = $this->db1->where(array('zoho_id' => $zoho_id))->from('zoho_leads')->count_all_results();
                    } catch (Exception $e) {
                        // var_dump($e->getMessage()); exit;
                        $check_zohoid_exists = 0;
                    }
                    // var_dump($check_zohoid_exists, 'exists'); exit;

                    if ($check_zohoid_exists != 0) {
                        continue;
                    }

                    // if($data["id"] != '6294215000002929001'){
                    //     continue;
                    // }

                    $lead["first_name"] = $data["First_Name"] == NULL ? '' : $data["First_Name"];
                    $lead["last_name"] = $data["Last_Name"] == NULL ? '' : $data["Last_Name"];
                    $lead["lead_email"] = $data["Email"];

                    // $customer_mobile = $data['Mobile'];
                    $customer_mobile = $data['Mobile'] == NULL ? $data['Phone'] : $data['Mobile'];
                    $countryCode = '';
                    if (!empty($customer_mobile) && $customer_mobile !== null) {
                        $customer_mobile = trim($customer_mobile);
                        if ($customer_mobile[0] !== '+') {
                            $customer_mobile = '+' . $customer_mobile;
                        }
                    }

                    // preg_match('/^\+(\d{1,4})/', $customer_mobile, $matches);
                    // $countryCode = '+' . $matches[1];
                    // $customer_mobile = preg_replace('/^\+971|\+91/', '', $customer_mobile);

                    $mobilePattern = '/^(?:\+971|00971|971|0)?(\d{7,8})$/';
                    $landlinePattern = '/^(?:\+971|00971|971|0)?(\d{7})$/';
                    $internationalPattern = '/^\+(\d{1,3})[\s-]?(\d+[\s-]?[\d\s-]*)$/';

                    $mobile_no = '';

                    if (preg_match($mobilePattern, $customer_mobile, $matches)) {
                        $mobile_no = $matches[1];
                    } elseif (preg_match($landlinePattern, $customer_mobile, $matches)) {
                        $mobile_no = $matches[1];
                    } elseif (preg_match($internationalPattern,  $customer_mobile, $matches)) {
                        $mobile_no = $matches[2];
                        $countryCode =  '+' . $matches[1];
                    } else {
                        $mobile_no = $customer_mobile;
                    }

                    $lead["lead_contact"] = $mobile_no;
                    $lead["lead_countrycode"] = $countryCode;
                    $lead["lead_nationality"] = $data['Nationality'];
                    $lead["lead_country"] = $data["Country"];
                    $lead["lead_type"] = "enquiry";
                    $lead["lead_zoho_id"] = $data['id'];
                    $lead["lead_zoho_status"] = $data['Lead_Status'];
                    $lead["lead_created_by"] = $lead_owner;
                    $lead["lead_ad_campaign"] = isset($data["Campaign"]) ? $data['Campaign'] : "";
                    $lead_campaign = isset($data["Campaign"]) ? $data['Campaign'] : "";

                    // $lead["lead_ad_campaign"] = $checklist_label;

                    // if(isset($data["Ad_campaign"])){
                    //     if($data["Ad_campaign"] != ""){
                    //         $lead["lead_remarks"] = $data["Ad_campaign"] . '</br>' . $data["Lead_Form"];
                    //     } else {
                    //         $lead["lead_remarks"] = $data["Lead_Form"];
                    //     }
                    // } else {
                    //     $lead["lead_remarks"] = $data["Lead_Form"];
                    // }

                    if (isset($layout_name)) {
                        $lead["lead_remarks"] = "Layout : " . $layout_name;
                    }
                    if (isset($data['What_type_of_visa_do_you_need'])) {
                        $lead["lead_remarks"] .= "</b></br>Visa Type : " . $data["What_type_of_visa_do_you_need"];
                    }
                    if (isset($data['What_is_your_monthly_income'])) {
                        $lead["lead_remarks"] .= "</b></br>Monthly Income : " . $data["What_is_your_monthly_income"];
                    }
                    if (isset($data['Bank_Statement'])) {
                        $lead["lead_remarks"] .= "</b></br>Bank Statement : " . $data["Bank_Statement"];
                    }
                    if (isset($data['Description'])) {
                        $lead["lead_remarks"] .= "</b></br> Description: " . $data["Description"];
                    }
                    if (isset($data['Designation'])) {
                        $lead["lead_remarks"] .= "</b></br> Zoho Type: " . $data["Designation"];
                    }
                    if (isset($data['To_Branch'])) {
                        $lead["lead_remarks"] .= "</b></br> Branch: " . $data["To_Branch"];
                    }
                    if (isset($data['Department'])) {
                        $lead["lead_remarks"] .= "</b></br> Department: " . $data["Department"];
                    }
                    if(isset($data['id'])) {
                        $lead["lead_remarks"] .= "</b></br> Zoho ID: " . $data["id"];
                    }
                    if(isset($data['Ad_Campaign_ID'])){
                        $lead["lead_remarks"] .= "</br> Zoho Campaign ID:&nbsp;<b>" . $data['Ad_Campaign_ID'] . "</b>,</br>";
                    }
                    

                    // $zoho_description = ($data["Description"] != NULL && trim($data["Description"]) != '') ? $data["Description"] : '';
                    // $lead["lead_remarks"] .= "</br> Zoho Description:&nbsp;<b>" . $zoho_description . "</b>,</br>";
                    $lead["lead_source"] = $data["Lead_Source"];
                    
                    $lead["lead_description"] = isset($data["Description"]) ? $data['Description'] : "";
                    $lead["lead_time_frame_to_start"] = isset($data["Time_frame_to_start"]) ? $data['Time_frame_to_start'] : "";
                    $lead["lead_form"] = isset($data["Lead_Form"]) ? $data['Lead_Form'] : "";
                    $lead["lead_reason_for_lost_mql"] = isset($data["Reason_for_the_Lost_MQL"]) ? $data['Reason_for_the_Lost_MQL'] : "";
                    $lead["lead_zoho_leadid"] = isset($data["Lead_No"]) ? $data['Lead_No'] : "";
                    $lead["lead_zoho_reason"] = isset($data["Reason_for_the_Junk_MQL"]) ? $data['Reason_for_the_Junk_MQL'] : "";
                    $lead["lead_business_activity"] = isset($checklist_label) ? $checklist_label : "";
                    $lead["lead_visa_type"] = isset($data["What_type_of_visa_do_you_need"]) ? $data['What_type_of_visa_do_you_need'] : "";
                    $lead["lead_layout_name"] = isset($layout_name) ? $layout_name : "";
                    $lead["lead_bank_statement"] = isset($data["Bank_Statement"]) ? $data['Bank_Statement'] : "";
                    $lead["lead_departments"] = isset($data["Department"]) ? $data['Department'] : "";
                    $lead["lead_brand"] = isset($data["To_Branch"]) ? $data['To_Branch'] : "";
                    $lead["lead_designation"] = isset($data["Designation"]) ? $data['Designation'] : "";

                    $lead["lead_ontimecrm_id"] = isset($data["Ontime_CRM_ID"]) ? $data['Ontime_CRM_ID'] : "";
                    $lead["lead_zoho_createdby"] = isset($data["Zoho_Lead_Created_By"]) ? $data['Zoho_Lead_Created_By'] : "";
                    $lead["lead_zoho_channel"] = isset($data["Channel"]) ? $data['Channel'] : "";
                    $lead["lead_zoho_chat_id"] = isset($data["Chat_ID"]) ? $data['Chat_ID'] : "";
                    $lead["lead_zoho_question"] = isset($data["Question"]) ? $data['Question'] : "";
                    $lead["lead_zoho_campaign_source"] = isset($data["Zoho_Campaign_Source"]) ? $data['Zoho_Campaign_Source'] : "";
                    $lead["lead_zoho_conversation_id"] = isset($data["Conversation_ID"]) ? $data['Conversation_ID'] : "";

                    $lead["lead_ad_campaign_id"] = isset($data["Campaign_ID"]) ? $data['Campaign_ID'] : "";
                    if(isset($data['Ad_Campaign_ID'])){
                        $lead["lead_ad_campaign_id"] = $data["Ad_Campaign_ID"];
                    }

                    $lead_attachments = $this->zoho_attachments($accessToken, $zoho_id, $lead["lead_ontimecrm_id"]);
                    if(!empty($lead_attachments)){
                        $lead["lead_remarks"] .= $lead_attachments;
                    }

                    $insert_array = array(
                        "zoho_id" => $data["id"],
                        "zoho_response" => json_encode($data),
                        "crm_req" => json_encode($lead),
                        "page_no" => $page_count,
                    );

                    $insert_zoho_leads = $this->db1->insert('zoho_leads', $insert_array);

                    // if ($this->db1->insert('zoho_leads', $insert_array)) {
                    //     echo "Insert successful!";
                    // } else {
                    //     echo "Insert failed!";
                    //     echo $this->db1->error()['message'];  // Display the error message
                    // }
                    // exit;

                    $lead_array = array(
                        'lead_data' => json_encode($lead)
                    );

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://crm.ontimegroup.com/api/v1/lead/zoholead_webchats',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $lead_array,
                    ));
                    $response = curl_exec($curl);
                    // print_r($response); exit;
                    curl_close($curl);

                    $result = json_decode($response, true);

                    $lead_id = $result['lead_id'];
                    array_push($leads_array, $result['lead_id']);

                    $update_array = array(
                        "crm_response" => json_encode($result)
                    );

                    $this->db1->where('zoho_id', $zoho_id);  // Specify the condition
                    $update_zoho_leads = $this->db1->update('zoho_leads', $update_array);
                    // var_dump($update_zoho_leads); exit;

                    // Update CRM Status in Zoho - START
                    /*$zoho_data = array();
                    $zoho_req = [];
                    $zoho_req["CRM_Status"] = "Fetched";
                    $zoho_data["data"] = [$zoho_req];
                    $zoho_reqs = json_encode($zoho_data);
    
                    $curl = curl_init();
                    $zoho_url =  'https://www.zohoapis.com/crm/v2/Leads/' . $zoho_id;
                    // echo $zoho_url;
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $zoho_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'PUT',
                        CURLOPT_POSTFIELDS => $zoho_reqs,
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer ' . $accessToken,
                            'Content-Type: application/json'
                        ),
                    ));

                    $zoho_response = curl_exec($curl);
                    curl_error($curl);
                    $zoho_response = str_replace('\\', '', $zoho_response);
                    $update_zoho_array = array('updated_zoho_request' => $zoho_reqs, 'updated_zoho_response' => $zoho_response);
                    $this->db1->where('zoho_id', $zoho_id);  // Specify the condition
                    $update_zoho_leads = $this->db1->update('zoho_leads', $update_zoho_array);  */
                    // Update CRM Status in Zoho - END
                }

                // if($lead_id != 0 && $lead_id != NULL){
                //     $notify_url = "https://crm.ontimegroup.com/api/v1/lead/zohonotify";
                //     $ch = curl_init();
                //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //     curl_setopt($ch, CURLOPT_URL,$notify_url);
                //     $result=curl_exec($ch);
                // }

                if ($info["more_records"]) {
                    $page_count = (int)$page_count + 1;
                }
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function get_zohotoken()
    {
        $apiUrl = 'https://accounts.zoho.com/oauth/v2/token';
        $data = [
            "client_id" => '1000.OOBZFWNJPBF50JWC2HUF9YLAK1RJCJ',
            "client_secret" => 'd1808c70da84b4d7ec594a5cc025ccb62c470c2ba7',
            "grant_type" => 'client_credentials',
            "scope" => 'ZohoCRM.modules.ALL',
            "soid" => 'ZohoCRM.787691276'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            return json_decode($response, true);
        } else {
            return ['error' => 'Login failed'];
        }
    }

    public function get_social_zohotoken()
    {
        $apiUrl = 'https://accounts.zoho.com/oauth/v2/token';
        $data = [
            "client_id" => '1000.OOBZFWNJPBF50JWC2HUF9YLAK1RJCJ',
            "client_secret" => 'd1808c70da84b4d7ec594a5cc025ccb62c470c2ba7',
            "grant_type" => 'client_credentials',
            "scope" => 'ZohoCRM.modules.ALL',
            "soid" => 'ZohoCRM.787691276'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            return json_decode($response, true);
        } else {
            return ['error' => 'Login failed'];
        }
    }

    public function zoho_leads_social_post()
    {
        $page_count = 0;
        $last_count = 0;
        $leads_array = [];
        try {
            while (true) {
                // $token_data = $this->get_social_zohotoken();
                // $accessToken = "1000.af578491e84d52b61f3fc120aff83d11.9d49039c8e515fdb99e58ae855dc2668";    //$token_data['access_token'];
                // $token_data = $this->get_zohotoken();
                // $accessToken = $token_data['access_token'];

                $token_data = $this->fetch_zoho_social_token();
                $accessToken = $token_data['access_token'];

                $api_url = "https://www.zohoapis.com/crm/v2/Leads?sort_order=desc&sort_by=Created_Time&page=" . $page_count;
                // $api_url = "https://www.zohoapis.com/crm/v2/Leads/search?criteria=(brand:equals:OnTime Visa)&sort_order=desc&sort_by=Created_Time&page=" . $page_count;

                $request_headers = [
                    'Content-Type:' . 'application/json',
                    'Accept:' . 'application/json',
                    'Authorization: Zoho-oauthtoken ' . $accessToken
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                $result = curl_exec($ch);
                $response = json_decode($result, true);
                // print_r($result); exit;
                // $this->response(["zoho leads" => $response, "message" => 'Zoho leads are fetched', "statuscode"=>200], 200);

                $load = $response["data"];
                $info = $response["info"];
                // print_r($info["count"]); exit;

                if ($last_count == $info["count"]) {
                    $this->response(["created_leads" => $leads_array, "message" => 'No Leads has been created in the Ontime CRM - Zoho CRM', "statuscode" => 200], 200);
                }

                $dsn = array(
                    'dsn'   => 'mysql:host=localhost;dbname=test',
                    'hostname' => 'localhost',
                    'username' => 'crm_root',
                    'password' => 'df240b484c',
                    'database' => 'test',
                    'dbdriver' => 'pdo',  // Use 'pdo' for PDO connection or 'mysqli' for MySQLi
                    'dbprefix' => '',
                    'pconnect' => FALSE,
                    'db_debug' => (ENVIRONMENT !== 'production'),
                    'cache_on'  => FALSE,
                    'cachedir'  => '',
                    'char_set'  => 'utf8',
                    'dbcollat'  => 'utf8_general_ci',
                    'return_type' => 'array',
                    'save_queries' => TRUE
                );

                $this->db1 = $this->load->database($dsn, true);

                $last_count = $info["count"];
                $lead_id = 0;
                foreach ($load as $data) {

                    $layout_details = $data['Layout'];
                    $layout_id = $layout_details['id'];
                    $layout_name = $layout_details['name'];

                    $To_Branch = $data['To_Branch'];
                    $department = $data['Department'];
                    $brand = $data['Brand'];

                    if($brand == 'OnTime Visa' ){   // || $layout_name == "Smart Cube"
                        continue;
                    }

                    if (($brand != "Mazaya Govermement Services")
                        && ($brand != "BusinessVenue Government Services")
                        && ($brand != "Al Baraha Government Services") && ($brand != "Al Baraha")
                        && ($brand != "Baraha")
                        && ($brand != "Al Manara")
                        && ($brand != "MOH") 
                        && ($brand != "RTA") 
                        && ($brand != "BurDubai")
                        && ($brand != "Mazaya MOH") 

                        && ($brand != "OnTime Healthcare") && ($brand != "Ontime Healthcare")
                        && ($brand != "OnTime Healthcare Setup - DHA Consultancy")
                        // && ($brand != "Doctor OnTime") && ($brand != "Doctor Ontime")
                        && ($brand != "OnTime Trustee") && ($brand != "Trustee")
                        && ($brand != "GoldenCube")
                        && ($brand != "Attestation")
                        && ($brand != "Translation")
                        && ($brand != "Al Taweyyat") && ($brand != "Al Tasweyaat") && ($brand != "Al Tasweyyat")
                        && ($brand != "Notary Public")
                        && ($brand != "OnTimeGov")
                        // && ($brand != "Smart Cube")
                    ){
                        continue;
                    }

                    $createdTime = $data['Created_Time'];  // "2026-02-07T12:02:30+04:00";
                    $createdDate = new DateTime($createdTime);
                    $createdDate->setTime(0, 0, 0);
                    $limitDate = new DateTime("2026-02-10");
                    $limitDate->setTime(0, 0, 0);

                    if ($createdDate < $limitDate) {
                        continue;
                    }
                    
                    //   var_dump($data); // exit; 

                    $owner_details = $data['Owner'];

                    $lead_owner = $owner_details['email'];
                    $zoho_id = $data["id"];

                    try {
                        $check_zohoid_exists = $this->db1->where(array('zoho_id' => $zoho_id))->from('zoho_leads')->count_all_results();
                    } catch (Exception $e) {
                        // var_dump($e->getMessage()); exit;
                        $check_zohoid_exists = 0;
                    }
                    // var_dump($check_zohoid_exists, 'exists'); exit;

                    if ($check_zohoid_exists != 0) {
                        continue;
                    }

                    if($data["Lead_Source"] == "OnlineStore"){
                        continue;
                    }

                    // if($data["Lead_No"] != '10969'){
                    //     continue;
                    // }

                    $lead["first_name"] = $data["First_Name"];
                    $lead["last_name"] = $data["Last_Name"];
                    $lead["lead_email"] = $data["Email"];

                    $customer_mobile = $data['Mobile'] == NULL ? $data['Phone'] : $data['Mobile'];
                    $countryCode = '';
                    if (!empty($customer_mobile) && $customer_mobile !== null) {
                        $customer_mobile = trim($customer_mobile);
                        if ($customer_mobile[0] !== '+') {
                            $customer_mobile = '+' . $customer_mobile;
                        }
                    }
                    // preg_match('/^\+(\d{1,4})/', $customer_mobile, $matches);
                    // $countryCode = '+' . $matches[1];
                    // $customer_mobile = preg_replace('/^\+971|\+91/', '', $customer_mobile);

                    $mobilePattern = '/^(?:\+971|00971|971|0)?(\d{7,8})$/';
                    $landlinePattern = '/^(?:\+971|00971|971|0)?(\d{7})$/';
                    $internationalPattern = '/^\+(\d{1,3})[\s-]?(\d+[\s-]?[\d\s-]*)$/';


                    $mobile_no = '';

                    if (preg_match($mobilePattern, $customer_mobile, $matches)) {
                        $mobile_no = $matches[1];
                    } elseif (preg_match($landlinePattern, $customer_mobile, $matches)) {
                        $mobile_no = $matches[1];
                    } elseif (preg_match($internationalPattern,  $customer_mobile, $matches)) {
                        $mobile_no = $matches[2];
                        $countryCode =  '+' . $matches[1];
                    } else {
                        $mobile_no = $customer_mobile;
                    }

                    $lead["lead_contact"] = $mobile_no;
                    $lead["lead_countrycode"] = $countryCode;
                    $lead["lead_nationality"] = $data['Nationality'];
                    $lead["lead_country"] = $data["Country"];
                    $lead["lead_type"] = "enquiry";
                    $lead["lead_zoho_id"] = $data['id'];
                    $lead["lead_zoho_status"] = $data['Lead_Status'];
                    $lead["lead_created_by"] = $lead_owner;
                    $lead["lead_ad_campaign"] = isset($data["Campaign"]) ? $data['Campaign'] : "";
                    $lead_campaign = isset($data["Campaign"]) ? $data['Campaign'] : "";

                    // if (strpos($lead_campaign, 'UK') !== false) {
                    //     $checklist_label = 'UK Checklist';
                    // } elseif (strpos($lead_campaign, 'USA') !== false) {
                    //     $checklist_label = 'USA Checklist';
                    // } elseif (strpos($lead_campaign, 'SCHENGEN') !== false) {
                    //     $checklist_label = 'Schengen Checklist';
                    // } else {
                    //     $checklist_label = 'Other'; // Default if no match
                    // }
                    // $lead["lead_ad_campaign"] = $checklist_label;

                    // if(isset($data["Ad_campaign"])){
                    //     if($data["Ad_campaign"] != ""){
                    //         $lead["lead_remarks"] = $data["Ad_campaign"] . '</br>' . $data["Lead_Form"];
                    //     } else {
                    //         $lead["lead_remarks"] = $data["Lead_Form"];
                    //     }
                    // } else {
                    //     $lead["lead_remarks"] = $data["Lead_Form"];
                    // }


                    if (isset($layout_name)) {
                        $lead["lead_remarks"] = "Layout : " . $layout_name;
                    }
                    if (isset($data['Remarks']) && ($data['Remarks'] != NULL)) {
                        $lead["lead_remarks"] .= "</b></br>Zoho Remarks : " . $data["Remarks"];
                    }
                    if (isset($data['Which_Pharmaceutical_facility_License_do_you_need']) && ($data['Which_Pharmaceutical_facility_License_do_you_need'] != NULL)) {
                        $lead["lead_remarks"] .= "</b></br>Which Pharmaceutical facility License do you need : " . $data["Which_Pharmaceutical_facility_License_do_you_need"];
                    }
                    if (isset($data['Which_clinic_facility_do_you_want_to_setup']) && ($data['Which_clinic_facility_do_you_want_to_setup'] != NULL)) {
                        $lead["lead_remarks"] .= "</b></br>Which clinic facility do you want to setup : " . $data["Which_clinic_facility_do_you_want_to_setup"];
                    }
                    if (isset($data['What_type_of_visa_do_you_need'])) {
                        $lead["lead_remarks"] .= "</b></br>Visa Type : " . $data["What_type_of_visa_do_you_need"];
                    }
                    if (isset($data['What_is_your_monthly_income'])) {
                        $lead["lead_remarks"] .= "</b></br>Monthly Income : " . $data["What_is_your_monthly_income"];
                    }
                    if (isset($data['Bank_Statement'])) {
                        $lead["lead_remarks"] .= "</b></br>Bank Statement : " . $data["Bank_Statement"];
                    }
                    if (isset($data['Description'])) {
                        $lead["lead_remarks"] .= "</b></br> Description: " . $data["Description"];
                    }
                    if (isset($data['Designation'])) {
                        $lead["lead_remarks"] .= "</b></br> Zoho Type: " . $data["Designation"];
                    }
                    if (isset($data['To_Branch'])) {
                        $lead["lead_remarks"] .= "</b></br> Branch: " . $data["To_Branch"];
                    }
                    if (isset($data['Brand'])) {
                        $lead["lead_remarks"] .= "</b></br> Brand: " . $data["Brand"];
                    }
                    if (isset($data['Department'])) {
                        $lead["lead_remarks"] .= "</b></br> Department: " . $data["Department"];
                    }
                    if(isset($data['id'])) {
                        $lead["lead_remarks"] .= "</b></br> Zoho ID: " . $data["id"];
                    }
                    if (isset($data['Lead_Form'])) {
                        $lead["lead_remarks"] .= "</b></br> Lead_Form: " . $data["Lead_Form"];
                    }
                    if (isset($data['Profession'])) {
                        $lead["lead_remarks"] .= "</b></br> Profession: " . $data["Profession"];
                    }
                    if (isset($data['Campaign'])) {
                        $lead["lead_remarks"] .= "</b></br> Campaign: " . $data["Campaign"];
                    }
                    if (isset($data['Ad_Name'])) {
                        $lead["lead_remarks"] .= "</b></br> Ad_Name: " . $data["Ad_Name"];
                    }
                    if(isset($data['Ad_Campaign_ID'])){
                        $lead["lead_remarks"] .= "</br> Zoho Campaign ID:&nbsp;<b>" . $data['Ad_Campaign_ID'] . "</b>,</br>";
                    }
                    // $zoho_description = ($data["Description"] != NULL && trim($data["Description"]) != '') ? $data["Description"] : '';
                    // $lead["lead_remarks"] .= "</br> Zoho Description:&nbsp;<b>" . $zoho_description . "</b>,</br>";
                    $lead["lead_source"] = $data["Lead_Source"];
                    $lead["lead_description"] = isset($data["Description"]) ? $data['Description'] : "";
                    $lead["lead_time_frame_to_start"] = isset($data["Time_frame_to_start"]) ? $data['Time_frame_to_start'] : "";
                    $lead["lead_form"] = isset($data["Lead_Form"]) ? $data['Lead_Form'] : "";
                    $lead["lead_reason_for_lost_mql"] = isset($data["Reason_for_the_Lost_MQL"]) ? $data['Reason_for_the_Lost_MQL'] : "";
                    $lead["lead_zoho_leadid"] = isset($data["Lead_No"]) ? $data['Lead_No'] : "";
                    $lead["lead_zoho_reason"] = isset($data["Reason_for_the_Junk_MQL"]) ? $data['Reason_for_the_Junk_MQL'] : "";
                    $lead["lead_business_activity"] = isset($checklist_label) ? $checklist_label : "";
                    $lead["lead_visa_type"] = isset($data["What_type_of_visa_do_you_need"]) ? $data['What_type_of_visa_do_you_need'] : "";
                    $lead["lead_layout_name"] = isset($layout_name) ? $layout_name : "";
                    $lead["lead_bank_statement"] = isset($data["Bank_Statement"]) ? $data['Bank_Statement'] : "";
                    $lead["lead_departments"] = isset($data["Department"]) ? $data['Department'] : "";
                    $lead["lead_brand"] = isset($data["Brand"]) ? $data['Brand'] : "";
                    $lead["lead_designation"] = isset($data["Designation"]) ? $data['Designation'] : "";
                    $lead["lead_ad_campaign"] = isset($data["Campaign"]) ? $data['Campaign'] : "";
                    $lead["lead_ad_name"] = isset($data["Ad_Name"]) ? $data['Ad_Name'] : "";

                    $lead["lead_ontimecrm_id"] = isset($data["Ontime_CRM_ID"]) ? $data['Ontime_CRM_ID'] : "";
                    $lead["lead_zoho_createdby"] = isset($data["Zoho_Lead_Created_By"]) ? $data['Zoho_Lead_Created_By'] : "";
                    $lead["lead_zoho_channel"] = isset($data["Channel"]) ? $data['Channel'] : "";
                    $lead["lead_zoho_chat_id"] = isset($data["Chat_ID"]) ? $data['Chat_ID'] : "";
                    $lead["lead_zoho_question"] = isset($data["Question"]) ? $data['Question'] : "";
                    $lead["lead_zoho_campaign_source"] = isset($data["Zoho_Campaign_Source"]) ? $data['Zoho_Campaign_Source'] : "";
                    $lead["lead_zoho_social_id"] = isset($data["leadchain0__Social_Lead_ID"]) ? $data['leadchain0__Social_Lead_ID'] : "";
                    $lead["lead_zoho_conversation_id"] = isset($data["Conversation_ID"]) ? $data['Conversation_ID'] : "";

                    $lead["lead_ad_campaign_id"] = isset($data["Campaign_ID"]) ? $data['Campaign_ID'] : "";

                    if(isset($data['Ad_Campaign_ID'])){
                        $lead["lead_ad_campaign_id"] = $data["Ad_Campaign_ID"];
                    }

                    $lead_attachments = $this->zoho_attachments($accessToken, $zoho_id, $lead["lead_ontimecrm_id"]);
                    if(!empty($lead_attachments)){
                        $lead["lead_remarks"] .= $lead_attachments;
                    }

                    $insert_array = array(
                        "zoho_id" => $data["id"],
                        "zoho_response" => json_encode($data),
                        "crm_req" => json_encode($lead),
                        "page_no" => $page_count,
                    );

                    $insert_zoho_leads = $this->db1->insert('zoho_leads', $insert_array);

                    // if ($this->db1->insert('zoho_leads', $insert_array)) {
                    //     echo "Insert successful!";
                    // } else {
                    //     echo "Insert failed!";
                    //     echo $this->db1->error()['message'];  // Display the error message
                    // }
                    // exit;

                    $lead_array = array(
                        'lead_data' => json_encode($lead)
                    );

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://crm.ontimegroup.com/api/v1/lead/zoholead_social',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $lead_array,
                    ));
                    $response = curl_exec($curl);
                    // print_r($response); exit;
                    curl_close($curl);

                    $result = json_decode($response, true);

                    $lead_id = $result['lead_id'];
                    array_push($leads_array, $result['lead_id']);

                    $update_array = array(
                        "crm_response" => json_encode($result)
                    );

                    $this->db1->where('zoho_id', $zoho_id);  // Specify the condition
                    $update_zoho_leads = $this->db1->update('zoho_leads', $update_array);
                    // var_dump($update_zoho_leads); exit;
                }

                // if($lead_id != 0 && $lead_id != NULL){
                //     $notify_url = "https://crm.ontimevisa.com/api/v1/lead/zohonotify";
                //     $ch = curl_init();
                //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //     curl_setopt($ch, CURLOPT_URL,$notify_url);
                //     $result=curl_exec($ch);
                // }

                if ($info["more_records"]) {
                    $page_count = (int)$page_count + 1;
                }
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function zoho_attachments($accessToken, $zoho_id, $ontime_crm_id)
    {
        try {
            // $token_data = $this->get_webchat_zohotoken();
            // $accessToken = "1000.216acd4c280085f258a983ace63d543c.5e95bbfd1df6367ac487c3f938fb0fb3"; //$token_data['access_token'];
            // $zoho_id = '6294215000004341001';

            $request_headers = [
                'Content-Type:' . 'application/json',
                'Accept:' . 'application/json',
                'Authorization: Zoho-oauthtoken ' . $accessToken
            ];

            $attachment_api = "https://www.zohoapis.com/crm/v2/Leads/".$zoho_id."/Attachments";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,$attachment_api);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
            $attachment_result=curl_exec($ch);
            $attachment_response=json_decode($attachment_result, true); 

            $attachment_data = $attachment_response["data"];
            $lead_attachments = '';
            if(!empty($attachment_data)){
                $lead_attachments = "<br><b><u>Zoho Attachments</u></b>,<br>";
                foreach($attachment_data as $attachment){
                    $attachment_id = $attachment['id'];
                    $attachment_url = "https://www.zohoapis.com/crm/v3/Leads/".$zoho_id."/Attachments/".$attachment_id;
    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_URL,$attachment_url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                    $image_result=curl_exec($ch);
    
                    $filename = $attachment['File_Name']; 
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    
                    $filePath = FCPATH .'../uploads/zoho_leads/'.$attachment_id.'.'.$extension;
                    $upload_result = file_put_contents($filePath, $image_result);
                    $attach_url = 'https://crm.ontimegroup.com/uploads/zoho_leads/'.$attachment_id.'.'.$extension;    // base_url() .
    
                    if(isset($ontime_crm_id) && $ontime_crm_id != NULL && $ontime_crm_id != ''){
                        $insert_attachment_array = array('lead_id' => $ontime_crm_id, 'attachment_name' => 'Zoho Attachment', 'attachment_url' => $attach_url);
                        $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);
                    }

                    $lead_attachments .= "Attachment : <a href=" . $attach_url . " target='_blank'>View File</a><br>";
                }
            } 
            return $lead_attachments;
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }



	public function ccpaytest_get()
    {
        // $_GET["url"] = "https://crm.ontimegroup.com/payment/ccpay?ref=982a50ef003f0af95e08a30036b82cf3-b9dc06bf87cc7d6903303a31993ef822&token=eyJpdiI6ImdKbldTamJnS1RXa3ZmRExvcTI0dHc9PSIsInZhbHVlIjoicTVob3FEaExucnZYQmkxVjRLNHJsZz09IiwibWFjIjoiMzMxYjRlZWQ1MjEyN2Y0NGMxYTk3ZDk5ZWFlN2JkOGIzZDFiYzVkZjI0OWFkODcxYjMyZDc4ODE2OTA1YTU5NiIsInRhZyI6IiJ9&identity=eyJpdiI6InZsSERUMFYzeFIxRVN2OVFCc3pXcHc9PSIsInZhbHVlIjoibm5JenB3ZmhQZG10UURwOUVza1hMUT09IiwibWFjIjoiMjQwYjllNTJiOTZlZjdmY2E0NTBiZTdlMmUxZjRlOTlhZjRlOGU2NGVjYzAwYWU5M2EyM2MxYTg1MTJiOWQ1MiIsInRhZyI6IiJ9";
        // https://crm.ontimegroup.com/payment/ccpay?ref=750cb12d5c1ac2a3c7536a9a52e2d446-ad94986ae5c3f3541ca31bbd81126fa7&token=ZUNuRnVaczlNS0xaTU5DdlNiR2RpQT09&identity=d0E3K0VVeUZaY1hQditFSWZzRTRsZz09

        // $ref = explode("-", $ref);
        $_GET['ref'] = "750cb12d5c1ac2a3c7536a9a52e2d446-ad94986ae5c3f3541ca31bbd81126fa7";
        $_GET["token"]= "ZUNuRnVaczlNS0xaTU5DdlNiR2RpQT09";
        $_GET["identity"] = "d0E3K0VVeUZaY1hQditFSWZzRTRsZz09";

        $ref = $_GET["ref"];
        $ref = explode("-", $ref);
        $amount = round((float)$this->encrypt_decrypt($_GET["token"], "decrypt"), 2);
        $action_id = (int)$this->encrypt_decrypt($_GET["identity"], "decrypt");
        var_dump($action_id);
        var_dump($ref);
        var_dump($amount);
        exit;


        $amount = round((float)$this->encrypt_decrypt($_GET["token"], "decrypt"), 2);

        var_dump($amount);
        $action_id = (int)$this->encrypt_decrypt($_GET["identity"], "decrypt");
        $ref = $this->encrypt_decrypt($_GET["ref"], "decrypt");
        var_dump($action_id);
        var_dump($ref);
        exit;
        $query = "SELECT md5(REVERSE(CONCAT(leads.id,'-',lead_users.email,'-','@OnTimeCRM11..'))) as token2,leads.id,lead_users.email,lead_users.first_name as customer_name from leads JOIN lead_users on leads.customer_id=lead_users.user_id join lead_action_log on lead_action_log.lead_id=leads.id where md5(CONCAT(`leads`.`id`,'-',`lead_users`.`email`,'-@OnTimeCRM11..'))='" . $ref[0] . "' and (lead_action_log.action_id=415 or lead_action_log.action_id = 426) and lead_action_log.action_amount=" . $amount . " and (lead_action_log.payment_id = 0 OR lead_action_log.payment_id IS NULL) and lead_action_log.action_on > subdate(now(), interval 48 hour) order by leads.id desc";
        // echo $query;
        // echo "<br>";
        $data = [];
        $data = $this->db->query($query);
        $data = $data->first_row();
        echo $amount;
        echo "<br><pre>";
        print_r($data);
        echo "</pre>";
        exit();

        if ($data != NULL) {
            if ($data->token2 == $ref[1]) {
                // $this->session->set_userdata("user_data", $datum->user_id);
                // return $this->pay($datum->total_amount,$datum->pcr_order_id,$datum->first_name);
                // echo "There";
                // exit();
                $other_actions = $this->db->select("*")->from("lead_action_log")->where("lead_id", $data->id)->where("id>", $action_id)->where("payment_link IS NOT ", NULL)->get()->first_row();
                if ($other_actions) {
                    // print_r($other_actions);
                    // exit();
                    redirect("/payment/failure?title=Expired !&desc=This Payment link is expired. Please check you email / contact Coordinator for latest payment link.");
                }
                return $this->pay($amount, $data->id, $data->customer_name, $data->email, $action_id);
            } else {
                // print("/pcr/info?title=Expired !&desc=This Payment link is expired or Invalid.");
                // exit();
                redirect("/payment/failure?title=Expired !&desc=This Payment link is expired or Invalid.");
            }
        } else {
            // print("/pcr/info?title=Expired !&desc=This Payment link is expired or Invalid.");
            // exit();
            redirect("/payment/failure?title=Expired !&desc=This Payment link is expired or Invalid.");
        }
    }

    function encrypt_decrypt($string, $action = 'encrypt')
	{
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'MBVVCXZSEFUHIRVGHQVJO978454225GHGFX'; // user define private key
		$secret_iv = 'jhdsigjh'; // user define secret key
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
		if ($action == 'encrypt') {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		} else if ($action == 'decrypt') {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}

    public function subleadcompleteapi_get()
    {
        // return "ji";
        // var_dump("hi");exit;
        $sublead_id = $_GET["code"];
        $inovice_id = $_GET["inovice_id"];
        $user_id = $_GET["user_id"];
        $pos_invoice_id = $this->mcommon->specific_row('pos_direct_invoice_list', ["id" => $inovice_id]);
        $sublead = $this->mcommon->specific_row('leads', ["id" => $sublead_id]);
        $lead = $this->mcommon->specific_row('leads', ["id" => $sublead["lead_parent_id"]]);
        $pmt = $lead["pos_pmt_number"];

        $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'pos_user_id');
        if ($user_pos == 0 || $user_pos == NULL)
            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'employee_id');
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
        $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
        $lead_det = $this->leads_model->lead_details($lead_id);

        if(!empty($lead_det["lead_zoho_id"])){
            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
            if(!empty($lead_created_by)){
                $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
            } else {
                $created_by_user = '';
            }

            $req["Payment"] = array(
                // "ActAmt" => $net_total, 
                // "OnlinePaymentRef" => $transaction_number,
                "CampaignSource" => $lead_det["lead_ad_campaign"],
                "ZohoLeadSource" => $lead_det["lead_source"],
                "CampaignId" => $lead_det["lead_ad_campaign_id"],
                "ZohoLeadId" => $lead_det["lead_zoho_id"],
                "LeadFrom" => 'Zoho',
                "CRMLeadId" => $lead_id,
                "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
            );
            
        } else {
            $req["Payment"] = array(
                // "ActAmt" => $net_total, 
                // "OnlinePaymentRef" => $transaction_number,
                "LeadSource" => 'Website',
                "LeadFrom" => $lead_det["lead_from"],
                "CRMLeadId" => $lead_id,
                "LeadCreatedBy" =>$created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
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

        $log_insert_array = array('action_id' => 410, 'lead_id' => $sublead["id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $user_id, 'status_id' => 305);
        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
        if ($insert_log > 0) {
            if(!empty($raw_salesorder)){
                $update_lead_array = array('lead_status' => 305, 'order_receipt' => $raw_salesorder, "completed_by" => $user_id);
                $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $sublead["id"]));
            }
            

            if ($lead_det["lead_parent_id"] != 0) {
                $this->db->where('id', $lead_det["lead_parent_id"]);
                $this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
                $this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
                $this->db->update('leads');

                // Email process for send the visa process mail
                // $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'remarks');
                // $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'id');
                // $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                // $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong>" . $service_name . "</strong> for the lead listed below <br>";
                // $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                // $sub_lead_message .= "<br><br>Lead Description:<br>";
                // $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                // $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                // $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                // $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                // $sub_lead_message .= "<br>Receipt Number: <strong>" . $lead_det["pos_pmt_number"] . "</strong>";
                // $sub_lead_message .= "<br>Remarks: " . $service_name;

                // if ($lead_det['msd_key'] == 69) {
                //     array_push($sublead_cc_usermail, ["email" => "Fawziya.h@ontimegov.com", "name" => "Fawziya"]);    // 980422236
                //     array_push($sublead_cc_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali"]);    // 2411946200
                //     $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Fawziya and Vernie";
                // }
                // array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

                // $email_array = array(
                //     'email' =>  $sublead_cc_usermail,
                //     'subject' => $sub_lead_subject,
                //     'template' => 'mails/template',
                //     'from_name' => "Golden Cube",
                //     'message' => $sub_lead_message,
                // );

                $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_det["lead_parent_id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $user_id, 'status_id' => 629);
                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                // $send_mail = send_template_email($email_array);
                // log_message('error', $send_mail);
            } else if ($lead_det["id"] != 0 && $lead_det["lead_parent_id"] == 0) {
                $this->db->where('id', $lead_det["id"]);
                $this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
                $this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
                $this->db->update('leads');
            }

            if (isset($_GET["from"])) {
                if ($_GET["from"] == "api") {
                    return  $this->response(["status" => true, "message" => "Order data updated successfully. You can see the progress in timeline."]);
                }
            }
        } else {

            if (isset($_GET["from"])) {
                if ($_GET["from"] == "api") {
                    return $this->response(["status" => false, "message" => "Log updated successfully. But unable to update the lead record. Contact support."]);
                }
            } 
           
        }
    }

    public function sendcreatesublead($getData)
    {
      // Convert the data to JSON format
      // $fields = json_encode($getData);
      $queryParams = http_build_query([
        'lead_id' => $getData['lead_id'],
        'remark' => $getData['remark'],
        'is_direct_invoice' => $getData['is_direct_invoice'],
        'user_id' => $getData['user_id']
      ]);
  
      // Construct the full URL with query parameters
      $url = 'https://crm.ontimegroup.com/api/v1/lead/subleadcreationapi?' . $queryParams;
  
      // Initialize cURL session
      $ch = curl_init();
  
      // Set cURL options
      curl_setopt($ch, CURLOPT_URL, $url);  // Set the URL with query parameters
      curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'api-key: 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',
        'Content-Type: application/json'
      ]);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // Return the response instead of outputting it
      curl_setopt($ch, CURLOPT_HEADER, false);  // Do not include the header in the output
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification (useful for local environments)
  
      // Execute cURL request
      $response = curl_exec($ch);
  
      // Close cURL session
      curl_close($ch);
  
      return $response;
    }

    public function encrypt($amount_payment)
    {
      $encrypt_method = "AES-256-CBC";
      $secret_key = 'MBVVCXZSEFUHIRVGHQVJO978454225GHGFX'; // user define private key
      $secret_iv = 'jhdsigjh'; // user define secret key
      $key = hash('sha256', $secret_key);
      $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
      $output = openssl_encrypt($amount_payment, $encrypt_method, $key, 0, $iv);
      $output = base64_encode($output);
      //  $output = openssl_decrypt(base64_decode($amount_payment), $encrypt_method, $key, 0, $iv);
      return $output;
    }

    public function doctorontime_post()
    {     
        try {
            $profile = json_decode($this->post("profile"));
            $user = json_decode($this->post("user"));
            $depend = json_decode($this->post("dependent_data"));
            $ontime_transid = json_decode($this->post("transid"));
            $getapplicant_info = json_decode($this->post("customer_info"));


            if ($profile == ''  || $user == '') {
                $this->response(["message" => 'Parameters Missing or Bad request 123', "status" => false], 400);
            }

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            $branch_id = 139;
            $lead_type = 'normal';
            $lead_by_pos_user = '1380903051';   //4242785381; //3020140166; //178140614;
            $lead_by_post_user_name = 'WEB API';    //"Web API";

            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
            }
            if ($lead_type == 'normal') {
                $category_id = 109;
                $service_id = 1009;

                //check category exist
                $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
                if ($cateogry_exist == 0) {
                    $this->response("Category doesn't exist. Update category first to create the lead", 404);
                }

                $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
                if ($service_exist == 0) {
                    $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
                }
            }

            $lead_name = $user->first_name . " " . $user->last_name;
            $lead_contact = ($user->mobile != NULL && trim($user->mobile) != '') ? $user->mobile : "520000000";
            $lead_email = $user->email;
            $lead_countrycode = ($user->countrycode != NULL && trim($user->countrycode) != '') ? $user->countrycode : "+971";
            $lead_remarks = "<br><b><u>Doctor Ontime Appointment Booking - Website Lead</u></b>,<br>";

            $customer_mail_package_name = '';

            $lead_remarks .= "Name:&nbsp;<b>" . $profile->first_name . " " . $profile->last_name . "</b>,</br>";

            if ($profile->gender != "" && $profile->gender != NULL)
            $lead_remarks .= "Gender:&nbsp;<b>" . strtoupper($profile->gender) . "</b>,</br>";
            // $lead_remarks .= "DOB:&nbsp;<b>" . $profile->date_of_birth . " / " . $profile->age . "</b>,</br>";
            if ($profile->nationality != "" && $profile->nationality != NULL)
            $lead_remarks .= "Nationality:&nbsp;<b>" . $profile->nationality . "</b></br></br>";

            if ($profile->property_address != "" && $profile->property_address != NULL)
                $lead_remarks .= "Address:&nbsp;<b>" . $profile->property_address . "</b>,</br>";
            if ($profile->property_communication_email != "" && $profile->property_communication_email != NULL)
                $lead_remarks .= "Communication Email:&nbsp;<b>" . $profile->property_communication_email . "</b>,</br>";
            if ($profile->property_mobile_number != "" && $profile->property_mobile_number != NULL)
                $lead_remarks .= "Mobile number:&nbsp;<b>" . $profile->property_mobile_number . "</b>,</br>";
            $customer_mail_package_name = $profile->package_name;
          
            $lead_remarks .= "<br><b><u>Applicant Information</u></b><br>";

            $lead_remarks .= "<br><b><u>Applicant Name</u></b><br>";
            $lead_remarks .= "Name:&nbsp;<b>". $getapplicant_info[0]->full_name. "</b>,</br>";
            $lead_remarks .= "Service Info:&nbsp;<b>". $getapplicant_info[0]->service_name. "</b>,</br>";
            $lead_remarks .= "Sub Service Info:&nbsp;<b>". $getapplicant_info[0]->sub_service_name. "</b>,</br>";
         
            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($lead_email == '') ? $random_email : $lead_email;
            $lead_email = trim($lead_email);

            //create or get customer
            //$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);
            $user_id = 0;
            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

            $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

            if ($is_exist != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
            }

            if ($is_exist == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '1';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $lead_contact,
                    'referal_code' => $referal_code,
                    'first_name' => $lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = 'United Arab Emirates';
                $user_data['country_code'] = $lead_countrycode;    //'+971';
                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                //return $user_id;
            }


            if ($user_id != 0) {
                $uploaded_file_name = '';
                //Upload document and get the file name
                if (isset($_FILES['files']['name'])) {
                    $config = array(
                        'upload_path' => "../uploads/leads",
                        'allowed_types' => "gif|jpg|png|jpeg|pdf",
                        'file_name' => sha1(time())
                    );
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('files')) {
                        $data = array('upload_data' => $this->upload->data());
                        $path = $config['upload_path'] . '/' . $data['upload_data']['file_name'];
                        $uploaded_file_name = $data['upload_data']['file_name'];
                    }
                }

                  if ($lead_type == 'normal') {
                    $normal_lead_count = 0;
                    //get the workflow for the service.
                    $workflows = $this->leads_model->get_workflow_entries($service_id);

                    if (!empty($workflows)) {
                        $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');
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
                                'lead_created_by' => $lead_by_pos_user, //178140614,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => $package_id,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'lead_by_pos_user' => $lead_by_pos_user,
                                'lead_by_post_user_name' => $lead_by_post_user_name,
                                'total_no_subleads' => count($service_ids),
                                'no_of_open_subleads' => count($service_ids),
                                'no_of_closed_subleads' => 0,
                                'created_group_id' => $created_group_id,
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            if ($insert_lead_id > 0) {

                                //get branch name
                                $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                //create action log
                                $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 178140614
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
                                $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

                                $normal_lead_count++;
                            }
                        }
                    } else {
                        $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');
                        // else create one lead for selected category & service
                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => $service_id,
                            'lead_created_by' => $lead_by_pos_user, //178140614,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => $package_id,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                            'total_no_subleads' => (count($service_ids) * count($getapplicant_info)),
                            'no_of_open_subleads' => (count($service_ids) * count($getapplicant_info)),
                            'no_of_closed_subleads' => 0,
                            'created_group_id' => $created_group_id,
                        );
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        $normal_lead_count = 1;

                        // 22587768 - Rawia
                    }
                    $lead_id = $insert_lead_id;
                    $parent_lead_id = $insert_lead_id;
                    $package_lead_count = 0;
                    $payment_type = 'online';

                    if ($normal_lead_count > 0) {

                        // $assigned_to = 2547057536; // moid.u@goldencube.ae
                        $assigned_to = $lead_by_pos_user;   // 188880683; - Mohamad.k@ontimegov.com   //    3020140166; - Basel.a@goldencube.ae
                        $assigned_by = $lead_by_pos_user;   //178140614;
                        // echo "<pre>";
                        // print_r($this->db);
                        // echo "</pre>";
                        // exit();
                        if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                            $this->response('Parameters Missing', 400);
                        } else {
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

                            $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

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
                                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $receiver_email = $csa->email;
                                    $receiver_name = $csa->first_name;
                                    $sender_email = $coordinator->email;
                                    $sender_name = $coordinator->first_name;

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

                                

                                    // $update = $this->mcommon->common_edit('leads', array('lead_status' => 320), array('id' => $lead_id));

                                    // $log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'GoldenCube WebApp Profile Submitted for Eligibility Checking.', 'action_by' => $assigned_by, 'status_id' => 320);
                                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $this->response(["lead_id" => $lead_id, "message" => 'Lead has been assigned successfully!'], 200);
                                } else {
                                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                                    // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                                    $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
                                }
                            } else {
                                $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign lead at present.'], 500);
                            }
                        }


                        $this->response(["lead_id" => $insert_lead_id, "message" => 'Lead has been created.',"statuscode"=>200], 200);
                    } else {
                        $this->response(["message"=>'Unable to create leads at this moment.',"statuscode"=>500], 500);
                    }
                }
            } else {
                $this->response(["message"=>'Unable to create leads at this moment.Please try again.',"statuscode"=>500], 500);
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }


    public function doctorrequest_post()
    {     
        try {
            $profile = json_decode($this->post("profile"));
            $user = json_decode($this->post("user"));
            $depend = json_decode($this->post("dependent_data"));
            $ontime_transid = json_decode($this->post("transid"));
            $getapplicant_info = json_decode($this->post("customer_info"));


            if ($profile == ''  || $user == '') {
                $this->response(["message" => 'Parameters Missing or Bad request 123', "status" => false], 400);
            }

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            $branch_id = 139;
            $lead_type = 'normal';
            $lead_by_pos_user = '1380903051';   //4242785381; //3020140166; //178140614;
            $lead_by_post_user_name = 'WEB API';    //"Web API";

            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
            }
            if ($lead_type == 'normal') {
                $category_id = 109;
                $service_id = 1009;

                //check category exist
                $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
                if ($cateogry_exist == 0) {
                    $this->response("Category doesn't exist. Update category first to create the lead", 404);
                }

                $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
                if ($service_exist == 0) {
                    $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
                }
            }

            $lead_name = $user->first_name . " " . $user->last_name;
            $lead_contact = ($user->mobile != NULL && trim($user->mobile) != '') ? $user->mobile : "520000000";
            $lead_email = $user->email;
            $lead_countrycode = ($user->countrycode != NULL && trim($user->countrycode) != '') ? $user->countrycode : "+971";
            $lead_remarks = "<br><b><u>Doctor Ontime Appointment Booking - Website Lead</u></b>,<br>";

            $lead_remarks .= "<br><b><u>Applicant Information</u></b><br>";

            $lead_remarks .= "<br><b><u>Applicant Name</u></b><br>";
            $lead_remarks .= "Name:&nbsp;<b>". $getapplicant_info[0]->full_name. "</b>,</br>";
            $lead_remarks .= "Email Info:&nbsp;<b>". $getapplicant_info[0]->email. "</b>,</br>";
            $lead_remarks .= "Mobile Number Info:&nbsp;<b>". $getapplicant_info[0]->mobile. "</b>,</br>";
            $lead_remarks .= "Message:&nbsp;<b>". $getapplicant_info[0]->message. "</b>,</br>";

            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($lead_email == '') ? $random_email : $lead_email;
            $lead_email = trim($lead_email);

            //create or get customer
            //$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);
            $user_id = 0;
            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

            $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email));

            if ($is_exist != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => $lead_email), 'user_id');
            }

            if ($is_exist == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '1';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $lead_contact,
                    'referal_code' => $referal_code,
                    'first_name' => $lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = 'United Arab Emirates';
                $user_data['country_code'] = $lead_countrycode;    //'+971';
                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                //return $user_id;
            }


            if ($user_id != 0) {
                $uploaded_file_name = '';
                //Upload document and get the file name
                if (isset($_FILES['files']['name'])) {
                    $config = array(
                        'upload_path' => "../uploads/leads",
                        'allowed_types' => "gif|jpg|png|jpeg|pdf",
                        'file_name' => sha1(time())
                    );
                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('files')) {
                        $data = array('upload_data' => $this->upload->data());
                        $path = $config['upload_path'] . '/' . $data['upload_data']['file_name'];
                        $uploaded_file_name = $data['upload_data']['file_name'];
                    }
                }

                  if ($lead_type == 'normal') {
                    $normal_lead_count = 0;
                    //get the workflow for the service.
                    $workflows = $this->leads_model->get_workflow_entries($service_id);

                    if (!empty($workflows)) {
                        $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');
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
                                'lead_created_by' => $lead_by_pos_user, //178140614,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => $package_id,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'lead_by_pos_user' => $lead_by_pos_user,
                                'lead_by_post_user_name' => $lead_by_post_user_name,
                                'total_no_subleads' => count($service_ids),
                                'no_of_open_subleads' => count($service_ids),
                                'no_of_closed_subleads' => 0,
                                'created_group_id' => $created_group_id
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            if ($insert_lead_id > 0) {

                                //get branch name
                                $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                //create action log
                                $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 178140614
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                $insert_attachment_array = array('lead_id' => $insert_lead_id, 'attachment_name' => $attachment_name, 'attachment_url' => 'https://crm.ontimegroup.com/uploads/leads/' . $uploaded_file_name);
                                $insert_attachment = $this->mcommon->common_insert('lead_attachments', $insert_attachment_array);

                                $normal_lead_count++;
                            }
                        }
                    } else {
                        $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $lead_by_pos_user, 'is_primary_group_id' => 1), 'group_id');
                        // else create one lead for selected category & service
                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => $service_id,
                            'lead_created_by' => $lead_by_pos_user, //178140614,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => $package_id,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                            'total_no_subleads' => (count($service_ids) * count($getapplicant_info)),
                            'no_of_open_subleads' => (count($service_ids) * count($getapplicant_info)),
                            'no_of_closed_subleads' => 0,
                            'created_group_id' => $created_group_id,
                        );
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        $normal_lead_count = 1;

                        // 22587768 - Rawia
                    }
                    $lead_id = $insert_lead_id;
                    $parent_lead_id = $insert_lead_id;
                    $package_lead_count = 0;
                    $payment_type = 'online';

                    if ($normal_lead_count > 0) {

                        // $assigned_to = 2547057536; // moid.u@goldencube.ae
                        $assigned_to = $lead_by_pos_user;   // 188880683; - Mohamad.k@ontimegov.com   //    3020140166; - Basel.a@goldencube.ae
                        $assigned_by = $lead_by_pos_user;   //178140614;
                        // echo "<pre>";
                        // print_r($this->db);
                        // echo "</pre>";
                        // exit();
                        if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                            $this->response('Parameters Missing', 400);
                        } else {
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

                            $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                            $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

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
                                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $receiver_email = $csa->email;
                                    $receiver_name = $csa->first_name;
                                    $sender_email = $coordinator->email;
                                    $sender_name = $coordinator->first_name;

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

                                

                                    // $update = $this->mcommon->common_edit('leads', array('lead_status' => 320), array('id' => $lead_id));

                                    // $log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'GoldenCube WebApp Profile Submitted for Eligibility Checking.', 'action_by' => $assigned_by, 'status_id' => 320);
                                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $this->response(["lead_id" => $lead_id, "message" => 'Lead has been assigned successfully!'], 200);
                                } else {
                                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                                    // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                                    $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
                                }
                            } else {
                                $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign lead at present.'], 500);
                            }
                        }


                        $this->response(["lead_id" => $insert_lead_id, "message" => 'Lead has been created.',"statuscode"=>200], 200);
                    } else {
                        $this->response(["message"=>'Unable to create leads at this moment.',"statuscode"=>500], 500);
                    }
                }
            } else {
                $this->response(["message"=>'Unable to create leads at this moment.Please try again.',"statuscode"=>500], 500);
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function zoho_fetchleaddata_get()
	{
		$lead_id = $this->get('lead_id');
		if($lead_id != "" ){
			try {
				// $lead_det = $this->leads_model->lead_details($lead_id);
				// $this->db->select('leads.id,concat("OTLDPMET",leads.id) as reference,lead_users.first_name,lead_users.last_name,lead_users.email,concat(lead_users.country_code,lead_users.mobile) as mobile,lead_action_log.action_amount as paid_amount,lead_action_log.created_at')->from("leads");
				// $this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
				// $this->db->join("lead_action_log", "lead_action_log.lead_id = leads.id");
				// $this->db->where("lead_action_log.status_id = 308 and (leads.lead_status < 305 OR leads.lead_status = 307 OR leads.lead_status = 308)");
				// $result = $this->db->get()->result_array();

				$this->db->select('leads.id as lead_id,
				concat(lead_users.first_name,lead_users.last_name) as customer_name
				lead_users.customer_email,
				concat(lead_users.country_code,lead_users.mobile) as customer_mobile,
				ontime_branches.branch_name as branch, 
				leads.lead_zoho_department as department,
				leads.lead_zoho_description as description')->from("leads");
				$this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
				$this->db->join("ontime_branches", "ontime_branches.branch_code = leads.branch_id");
				$this->db->where("lead_id", $lead_id);
				$result = $this->db->get()->result_array();
		
				$lead_data = array(
					'email' => $result['customer_email'],
					'name' => $result['customer_name'],
					'mobile' => $result['customer_mobile'],
					'description' => $result['remarks'],
					'group' => $result['department'],
					'company' => 'Ontime',
					'branch' => $result['branch']
				);
				return $this->response(["status" => true, "data" => $lead_data, "message" => false]);
			} catch (Exception $e) {
				return $this->response(["status" => false, "message" => "Something went wrong."]);
			}
		} else {
			$this->response(["message" => 'Parameters missing'], REST_Controller::HTTP_BAD_REQUEST);
		}
	}

    public function update_groupid_against_users_get()
    {
        try {
            // $create_groupid_arr = [
            //     'UPDATE leads SET created_group_id = 56 WHERE lead_created_by ="4153040344";',
            //     'UPDATE leads SET created_group_id = 52 WHERE lead_created_by ="4148588821";',
            //     'UPDATE leads SET created_group_id = 103 WHERE lead_created_by ="3949391145";',
            //     'UPDATE leads SET created_group_id = 74 WHERE lead_created_by ="3946368694";',
            //     'UPDATE leads SET created_group_id = 81 WHERE lead_created_by ="3939129301";',
            //     'UPDATE leads SET created_group_id = 111 WHERE lead_created_by ="3865339251";',
            //     'UPDATE leads SET created_group_id = 54 WHERE lead_created_by ="3834211354";',
            //     'UPDATE leads SET created_group_id = 108 WHERE lead_created_by ="3828530144";',
            //     'UPDATE leads SET created_group_id = 57 WHERE lead_created_by ="3623150173";',
            //     'UPDATE leads SET created_group_id = 44 WHERE lead_created_by ="3586925300";',
            //     'UPDATE leads SET created_group_id = 51 WHERE lead_created_by ="3390723150";',
            //     'UPDATE leads SET created_group_id = 47 WHERE lead_created_by ="3375144658";',
            //     'UPDATE leads SET created_group_id = 45 WHERE lead_created_by ="3342462473";',
            //     'UPDATE leads SET created_group_id = 92 WHERE lead_created_by ="3334586807";',
            //     'UPDATE leads SET created_group_id = 101 WHERE lead_created_by ="3333914955";',
            //     'UPDATE leads SET created_group_id = 52 WHERE lead_created_by ="3328625099";',
            //     'UPDATE leads SET created_group_id = 73 WHERE lead_created_by ="3284381121";',
            //     'UPDATE leads SET created_group_id = 44 WHERE lead_created_by ="3212920395";',
            //     'UPDATE leads SET created_group_id = 71 WHERE lead_created_by ="3203195890";',
            //     'UPDATE leads SET created_group_id = 77 WHERE lead_created_by ="3081550412";',
            //     'UPDATE leads SET created_group_id = 70 WHERE lead_created_by ="3065171680";',
            //     'UPDATE leads SET created_group_id = 56 WHERE lead_created_by ="3053338403";',
            //     'UPDATE leads SET created_group_id = 73 WHERE lead_created_by ="3003097802";',
            //     'UPDATE leads SET created_group_id = 67 WHERE lead_created_by ="2883146911";',
            //     'UPDATE leads SET created_group_id = 67 WHERE lead_created_by ="2767523564";',
            //     'UPDATE leads SET created_group_id = 56 WHERE lead_created_by ="2763984202";',
            //     'UPDATE leads SET created_group_id = 55 WHERE lead_created_by ="2732725006";',
            //     'UPDATE leads SET created_group_id = 45 WHERE lead_created_by ="2605952057";',
            //     'UPDATE leads SET created_group_id = 101 WHERE lead_created_by ="2510679666";',
            //     'UPDATE leads SET created_group_id = 109 WHERE lead_created_by ="2446367346";',
            //     'UPDATE leads SET created_group_id = 56 WHERE lead_created_by ="2183443295";',
            //     'UPDATE leads SET created_group_id = 41 WHERE lead_created_by ="2002168679";',
            //     'UPDATE leads SET created_group_id = 45 WHERE lead_created_by ="1912277736";',
            //     'UPDATE leads SET created_group_id = 63 WHERE lead_created_by ="1854592864";',
            //     'UPDATE leads SET created_group_id = 67 WHERE lead_created_by ="1784511105";',
            //     'UPDATE leads SET created_group_id = 91 WHERE lead_created_by ="1778988222";',
            //     'UPDATE leads SET created_group_id = 77 WHERE lead_created_by ="1745574705";',
            //     'UPDATE leads SET created_group_id = 100 WHERE lead_created_by ="1699333399";',
            //     'UPDATE leads SET created_group_id = 58 WHERE lead_created_by ="1668733300";',
            //     'UPDATE leads SET created_group_id = 66 WHERE lead_created_by ="1628315442";',
            //     'UPDATE leads SET created_group_id = 98 WHERE lead_created_by ="1575752556";',
            //     'UPDATE leads SET created_group_id = 108 WHERE lead_created_by ="1501975080";',
            //     'UPDATE leads SET created_group_id = 41 WHERE lead_created_by ="1341021925";',
            //     'UPDATE leads SET created_group_id = 56 WHERE lead_created_by ="1260790013";',
            //     'UPDATE leads SET created_group_id = 45 WHERE lead_created_by ="1184498292";',
            //     'UPDATE leads SET created_group_id = 79 WHERE lead_created_by ="1143711453";',
            //     'UPDATE leads SET created_group_id = 53 WHERE lead_created_by ="1009942117";',
            //     'UPDATE leads SET created_group_id = 79 WHERE lead_created_by ="895881848";',
            //     'UPDATE leads SET created_group_id = 114 WHERE lead_created_by ="883057153";',
            //     'UPDATE leads SET created_group_id = 56 WHERE lead_created_by ="842642567";',
            //     'UPDATE leads SET created_group_id = 53 WHERE lead_created_by ="797821886";',
            //     'UPDATE leads SET created_group_id = 98 WHERE lead_created_by ="732508183";',
            //     'UPDATE leads SET created_group_id = 47 WHERE lead_created_by ="678064846";',
            //     'UPDATE leads SET created_group_id = 44 WHERE lead_created_by ="675675029";',
            //     'UPDATE leads SET created_group_id = 67 WHERE lead_created_by ="632746755";',
            //     'UPDATE leads SET created_group_id = 99 WHERE lead_created_by ="586833641";',
            //     'UPDATE leads SET created_group_id = 78 WHERE lead_created_by ="551550256";',
            //     'UPDATE leads SET created_group_id = 47 WHERE lead_created_by ="468190806";',
            //     'UPDATE leads SET created_group_id = 56 WHERE lead_created_by ="393815654";',
            //     'UPDATE leads SET created_group_id = 107 WHERE lead_created_by ="368862656";',
            //     'UPDATE leads SET created_group_id = 100 WHERE lead_created_by ="270523625";',
            //     'UPDATE leads SET created_group_id = 53 WHERE lead_created_by ="158384070";',
            //     'UPDATE leads SET created_group_id = 78 WHERE lead_created_by ="139700760";'
            // ];

            // foreach ($create_groupid_arr as $sql) {
            //     $this->db->query($sql);
            // }

            // $assigned_groupid_arr = [
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 56 where  la.assigned_to = "4153040344";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 52 where  la.assigned_to = "4148588821";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 103 where  la.assigned_to = "3949391145";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 74 where  la.assigned_to = "3946368694";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 81 where  la.assigned_to = "3939129301";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 111 where  la.assigned_to = "3865339251";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 54 where  la.assigned_to = "3834211354";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 108 where  la.assigned_to = "3828530144";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 57 where  la.assigned_to = "3623150173";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 44 where  la.assigned_to = "3586925300";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 51 where  la.assigned_to = "3390723150";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 47 where  la.assigned_to = "3375144658";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 45 where  la.assigned_to = "3342462473";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 92 where  la.assigned_to = "3334586807";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 101 where  la.assigned_to = "3333914955";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 52 where  la.assigned_to = "3328625099";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 73 where  la.assigned_to = "3284381121";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 44 where  la.assigned_to = "3212920395";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 71 where  la.assigned_to = "3203195890";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 77 where  la.assigned_to = "3081550412";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 70 where  la.assigned_to = "3065171680";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 56 where  la.assigned_to = "3053338403";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 73 where  la.assigned_to = "3003097802";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 67 where  la.assigned_to = "2883146911";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 67 where  la.assigned_to = "2767523564";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 56 where  la.assigned_to = "2763984202";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 55 where  la.assigned_to = "2732725006";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 45 where  la.assigned_to = "2605952057";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 101 where  la.assigned_to = "2510679666";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 109 where  la.assigned_to = "2446367346";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 56 where  la.assigned_to = "2183443295";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 41 where  la.assigned_to = "2002168679";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 45 where  la.assigned_to = "1912277736";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 63 where  la.assigned_to = "1854592864";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 67 where  la.assigned_to = "1784511105";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 91 where  la.assigned_to = "1778988222";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 77 where  la.assigned_to = "1745574705";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 100 where  la.assigned_to = "1699333399";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 58 where  la.assigned_to = "1668733300";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 66 where  la.assigned_to = "1628315442";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 98 where  la.assigned_to = "1575752556";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 108 where  la.assigned_to = "1501975080";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 41 where  la.assigned_to = "1341021925";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 56 where  la.assigned_to = "1260790013";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 45 where  la.assigned_to = "1184498292";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 79 where  la.assigned_to = "1143711453";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 53 where  la.assigned_to = "1009942117";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 79 where  la.assigned_to = "895881848";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 114 where  la.assigned_to = "883057153";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 56 where  la.assigned_to = "842642567";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 53 where  la.assigned_to = "797821886";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 98 where  la.assigned_to = "732508183";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 47 where  la.assigned_to = "678064846";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 44 where  la.assigned_to = "675675029";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 67 where  la.assigned_to = "632746755";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 99 where  la.assigned_to = "586833641";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 78 where  la.assigned_to = "551550256";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 47 where  la.assigned_to = "468190806";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 56 where  la.assigned_to = "393815654";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 107 where  la.assigned_to = "368862656";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 100 where  la.assigned_to = "270523625";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 53 where  la.assigned_to = "158384070";',
            //     'update leads as l join leads_assigned as la on l.id = la.lead_id join users as creator on creator.user_id = la.assigned_to set l.assigned_group_id= 78 where  la.assigned_to = "139700760";',
            // ];

            // foreach ($assigned_groupid_arr as $sql) {
            //     $this->db->query($sql);
            // }

            // $update_primary_group_arr = [
            //     'update group_members set is_primary_group_id =  1 where user_id = "4153040344" and group_id = 56;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "4148588821" and group_id = 52;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3949391145" and group_id = 103;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3946368694" and group_id = 74;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3939129301" and group_id = 81;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3865339251" and group_id = 111;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3834211354" and group_id = 54;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3828530144" and group_id = 108;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3623150173" and group_id = 57;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3586925300" and group_id = 44;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3390723150" and group_id = 51;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3375144658" and group_id = 47;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3342462473" and group_id = 45;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3334586807" and group_id = 92;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3333914955" and group_id = 101;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3328625099" and group_id = 52;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3284381121" and group_id = 73;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3212920395" and group_id = 44;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3203195890" and group_id = 71;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3081550412" and group_id = 77;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3065171680" and group_id = 70;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3053338403" and group_id = 56;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "3003097802" and group_id = 73;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2883146911" and group_id = 67;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2767523564" and group_id = 67;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2763984202" and group_id = 56;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2732725006" and group_id = 55;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2605952057" and group_id = 45;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2510679666" and group_id = 101;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2446367346" and group_id = 109;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2183443295" and group_id = 56;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "2002168679" and group_id = 41;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1912277736" and group_id = 45;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1854592864" and group_id = 63;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1784511105" and group_id = 67;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1778988222" and group_id = 91;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1745574705" and group_id = 77;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1699333399" and group_id = 100;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1668733300" and group_id = 58;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1628315442" and group_id = 66;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1575752556" and group_id = 98;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1501975080" and group_id = 108;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1341021925" and group_id = 41;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1260790013" and group_id = 56;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1184498292" and group_id = 45;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1143711453" and group_id = 79;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "1009942117" and group_id = 53;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "895881848" and group_id = 79;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "883057153" and group_id = 114;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "842642567" and group_id = 56;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "797821886" and group_id = 53;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "732508183" and group_id = 98;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "678064846" and group_id = 47;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "675675029" and group_id = 44;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "632746755" and group_id = 67;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "586833641" and group_id = 99;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "551550256" and group_id = 78;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "468190806" and group_id = 47;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "393815654" and group_id = 56;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "368862656" and group_id = 107;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "270523625" and group_id = 100;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "158384070" and group_id = 53;',
            //     'update group_members set is_primary_group_id =  1 where user_id = "139700760" and group_id = 78;'
            // ];

            // foreach ($update_primary_group_arr as $sql) {
            //     $this->db->query($sql);
            // }

            // created_group_id for the single group users
            $created_groupid_single_group_sql = 'update leads as l JOIN (SELECT u.user_id, u.email, gm.group_id FROM group_members as gm JOIN `groups` as g on g.group_id = gm.group_id JOIN users as u on u.user_id = gm.user_id group by u.user_id HAVING count(gm.group_id) = 1 ORDER BY u.user_id DESC) as gg on gg.user_id = l.lead_created_by set l.created_group_id = gg.group_id where created_group_id = 0';
            $this->db->query($created_groupid_single_group_sql);

            // assigned_group_id for the single group users
            $assigned_groupid_single_group_sql = 'update leads as l join leads_assigned as la on l.id = la.lead_id
            join users as creator on creator.user_id = la.assigned_to JOIN (SELECT u.user_id, u.email, gm.group_id FROM group_members as gm JOIN `groups` as g on g.group_id = gm.group_id JOIN users as u on u.user_id = gm.user_id group by u.user_id 
            HAVING count(gm.group_id) = 1 ORDER BY u.user_id DESC) as gg on gg.user_id = la.assigned_to set l.assigned_group_id = gg.group_id
            where l.assigned_group_id = 0';

            $this->db->query($assigned_groupid_single_group_sql);

            // // primary_group_id for the single group users
            // $primary_group_single_group_sql = 'update `group_members` as gms join (SELECT u.user_id, u.email, gm.group_id, gm.group_member_id FROM group_members as gm JOIN `groups` as g on g.group_id = gm.group_id JOIN users as u on u.user_id = gm.user_id group by u.user_id HAVING count(gm.group_id) = 1 ORDER BY u.user_id DESC) as gg on gg.group_member_id = gms.group_member_id 
            // set is_primary_group_id = 1';

            // $this->db->query($primary_group_single_group_sql);

            // return $this->db->affected_rows();
            return $this->response(["status" => true, "message" => "Updated"]);
        } catch (Exception $e) {
            return $this->response(["status" => false, "message" => "Something went wrong."]);
        }

    }

    public function primary_group_against_user_get()
    {
        $user_id = $this->get('user_id');
        if($user_id != "" ){
			try {
                $primary_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $user_id, 'is_primary_group_id' => 1), 'group_id');
                return $this->response(["status" => true, "primary_group_id" => $primary_group_id]);
			} catch (Exception $e) {
				return $this->response(["status" => false, "message" => "Something went wrong."]);
			}
		} else {
			$this->response(["message" => 'Parameters missing'], REST_Controller::HTTP_BAD_REQUEST);
		}
    }

    public function add_request_post(){
        $lead_id = json_decode($this->post("lead_id"));
        // $url = 'https://94.200.55.118:2080/api/v3/requests/';
        // $api_key = 'D488A202-F17D-41FE-A52B-11973561539B';
        $url = 'https://94.200.55.118:5000/api/v3/requests/';
        $api_key = '171016F2-9943-418C-AD51-56E7E7C7DF4E';
        $headers = [
            "authtoken: $api_key"
        ];

        $input_data = json_encode(array(
            "request" => array(
                "subject" => "New Request Creation - Lead id #".$lead_id,
                "description" => "New Request created against Lead id #".$lead_id,
                "requester" => array(
                    "name" => "admin"
                ),
                "resolution" => array(
                    "content" => "Mail Fetching Server problem has been fixed"
                ),
                "status" => array(
                    "name" => "Open"
                )
            )
        ));
        
        $data = http_build_query(array('input_data' => $input_data));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the headers
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (like verify=False in requests)
        // Execute the cURL session
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            $result = json_decode($response);
            $req_result = $result->request;
            $request_id = $req_result->id;
            echo $request_id;
            exit();
        }
        
        
    }

    public function ontimegov_test_post()
    {
        $name = $this->post('name');
        $email = $this->post('email');
        $mobile = $this->post('mobile');
        $order_ref = $this->post('order_ref');
        $trans_name = $this->post('trans_name');
        $card_num = $this->post('card_num');
        $transaction_number = $this->post('transaction_number');
        $receipt_no = $this->post('receipt_no');
        $sla = $this->post('sla');
        $order_id = $this->post('order_id');
        $order_details_id = $this->post('order_details_id');
        $service_id = $this->post('service_id');
        $net_total = $this->post('net_total');
        $description = $this->post('description');
        $order_details = json_decode($this->post('order_details'));
        $order_status = 301;
        $pos_category_id = $this->post('pos_category_id');
        $pos_service_id = $this->post('pos_service_id');
        $payment_mode = $this->post('payment_mode');
        $postgovt_fee = $this->post('govt_fee');
        $posttyping_fee = $this->post('typing_fee');
        $postdelivery_fee = 115;
        $postdelivery_id= $this->post('delivery_id');
        $number =  $mobile ; // mobile number
        $number = (strpos($number, '971') === 0) ? substr($number, 3) : $number;
        $mobile = $number;

        if($payment_mode=='offline'){

            if ($name == '' || $email == '' || $mobile == '' || $trans_name == '' || $sla == '' || $order_id == '' || $order_details_id == '' || $service_id == '' || $net_total == '' || $description == '') {
                $this->response('Offline - Parameters Missing', 400);
            }

        }else{

            if ($name == '' || $email == '' || $mobile == '' || $order_ref == '' || $trans_name == '' || $card_num == '' || $transaction_number == '' || $receipt_no == '' || $sla == '' || $order_id == '' || $order_details_id == '' || $service_id == '' || $net_total == '' || $description == '') {
                $this->response('Online - Parameters Missing', 400);
            }

        }

        $order_exist = $this->mcommon->specific_record_counts('leads', array('otg_order_id' => $order_id));
        if ($order_exist != 0) {
            $this->response(array('status' => 'error', 'result' => "Already lead exists against the order id"), 204);
        }
        

        //Customer Logic
        // $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile));
        // $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $email));

        // if ($check_email_exists != 0) {
        //     $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        // }

        // if ($check_mobile_exists != 0) {
        //     $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $mobile), 'user_id');
        // }
        $check_lead_user = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $mobile,'email' => $email));

        if ($check_lead_user != 0) {
            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email,'mobile' => $mobile), 'user_id');
        }

        // if ($check_mobile_exists == 0 && $check_email_exists == 0) {
        if ($check_lead_user == 0) {
            $password = 'Welcome@123';
            $confirm_password = 'Welcome@123';
            $auth_level = '4';
            $referal_code = time();
            $user_hashed_password = $this->authentication->hash_passwd($password);
            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $mobile,
                'referal_code' => $referal_code,
                'first_name' => $name,
                'passwd' => $user_hashed_password,
                'email' => trim($email),
                'confirm_password' => $user_hashed_password,
            ];
            $user_data['user_id'] = $this->authentication_model->get_unused_id();
            $user_data['created_at'] = date('Y-m-d H:i:s');
            $user_data['otp'] = rand(1000, 9000);
            $user_data['email_otp'] = rand(1000, 9000);
            $user_data['banned'] = '0';
            $user_data['role_id'] = '4';
            $user_data['country'] = 'United Arab Emirates';
            $user_data['country_code'] = '971';
            $insert = $this->mcommon->common_insert("lead_users", $user_data);

            $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $email), 'user_id');
        }


        if ($user_id != '') {
            $insert_array = array(
                'user_id' => $user_id,
                'pos_category_id' => $pos_category_id,
                'pos_service_id' => $pos_service_id,
                'order_ref' => $order_ref,
                'trans_name' => $trans_name,
                'card_num' => $card_num,
                'transaction_number' => $transaction_number,
                'receipt_no' => $receipt_no,
                'sla' => $sla,
                'order_id' => $order_id,
                'order_details_id' => $order_details_id,
                'service_id' => $service_id,
                'net_total' => $net_total,
                'description' => $description,
                'order_details' => json_encode($order_details),
                'item_status' => $order_status,
                'created_date' => date('Y-m-d H:i:s')
            );
            $insert = $this->mcommon->common_insert('otg_orders', $insert_array);

            $branch_id = 119;
            $category_id = 10009;
            $service = $trans_name;
            $service_exist = $this->mcommon->specific_row('ontime_category_services_', array('category_id' => $category_id, "service_name" => $service));

            if (!empty($service_exist)) {
                $service_id = $service_exist["id"];
            } else {
                $service_id = $this->mcommon->common_insert("ontime_category_services_", array('category_id' => $category_id, "service_name" => $service));
                $service_id = (int) $service_id;
                $update_service = $this->mcommon->common_edit("ontime_category_services_", ["service_id" => $service_id], ["id" => $service_id]);
            }
            $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 4294967295, 'is_primary_group_id' => 1), 'group_id');

            //Leads
            $insert_lead_array = array(
                'customer_id' => $user_id,
                'branch_id' => $branch_id,
                'category_id' => $category_id,
                'service_id' => $service_id,
                'lead_created_by' => 4294967295,
                'lead_added_on' => date('Y-m-d H:i:s'),
                'contactable_date' => date('Y-m-d H:i:s'),
                'lead_status' => 301,
                'order_receipt' => 0,
                'remarks' => $description,
                'is_assigned' => 0,
                'otg_order_id' => $order_id,
                'otg_order_detail_id' => $order_details_id,
                'lead_from' => 'OntimeGOV',
                'created_group_id' => $created_group_id
            );
            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
            $parent_lead_id = $insert_lead_id;

            $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> Web API</strong> based on OnTimeGov Web Order #' . $order_id, 'action_by' => 4294967295, 'status_id' => 301);
            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

            $lead_id = $parent_lead_id;

                // $assigned_to = 3573695398;
                $assigned_to = 2113278237; //cc@ontimegroup.com
                $assigned_by = 4294967295;

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

                $assigned_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $assigned_to, 'is_primary_group_id' => 1), 'group_id');
                $update = $this->mcommon->common_edit('leads', array('assigned_group_id' => $assigned_group_id), array('id' => $lead_id));

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
                        $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    }
                }

        }
        var_dump($lead_id);

    }

    public function queue_lead_create_post()
    {
        try {
            $q_lead_name = $this->post('full_name');
            $q_mobile = $this->post('phone_no');
            $q_country_code = $this->post('country_code');
            $q_email = $this->post('email');
            $q_natinality = $this->post('nationality');
            $GCBTokenNo = $this->post('GCBTokenNo');
            $GCBTokenId = $this->post('GCBTokenId');
            $AdditionalService = $this->post('AdditionalService');
            
            if ($q_lead_name == '' || $q_mobile == '' || $q_email == '' || $q_country_code == '') {
                $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
            }

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            $branch_id = 106;
            $lead_type = 'normal';
            $lead_by_pos_user = '3729322111'; // goldencubewalkin@goldencube.ae
            $lead_by_post_user_name = 'Golden Cube WALKIN';
            //check category exist
            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
            }


            if ($lead_type == 'normal') {
                $category_id = 109;
                $service_id = 1009;

                //check category exist
                $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
                if ($cateogry_exist == 0) {
                    $this->response("Category doesn't exist. Update category first to create the lead", 404);
                }

                $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
                if ($service_exist == 0) {
                    $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
                }
            }

            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($q_email == '') ? $random_email : $q_email;
            $lead_email = trim($lead_email);
            $full_mobile = $q_country_code . $q_mobile;
            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $q_mobile));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));
            $check_mobile_contry_code_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $full_mobile));

            if ($check_email_exists != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
            }

            if ($check_mobile_exists != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $q_mobile), 'user_id');
            }

            if ($check_mobile_contry_code_exists != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $full_mobile), 'user_id');
            }

            if ($check_mobile_exists == 0 && $check_email_exists == 0 && $check_mobile_contry_code_exists == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '1';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $q_mobile,
                    'referal_code' => $referal_code,
                    'first_name' => $q_lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = !empty($q_natinality) ? $q_natinality : 'United Arab Emirates';
                $user_data['country_code'] = $q_country_code;

                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
                //return $user_id;
            }
            $lead_remarks = "<br><b><u>GoldenCube Eligibility Check - Walkin Lead</u></b>,<br>";
            $lead_remarks .= "Token No :&nbsp;<b>" .  $GCBTokenNo . "</b>,</br>";
            $lead_remarks .= "Additional Service :&nbsp;<b>" . $AdditionalService . "</b>,</br></br>"; //change

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
                            if ($lead_by_post_user_name == "POS") {
                                exit();
                            }
                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $target_service_id,
                                'lead_created_by' => $lead_by_pos_user,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => 0,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'lead_by_pos_user' => $lead_by_pos_user,
                                'lead_by_post_user_name' => $lead_by_post_user_name,
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            if ($insert_lead_id > 0) {

                                //get branch name
                                $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                //create action log
                                $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WALKIN USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                $normal_lead_count++;
                            }
                        }
                    } else {

                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => $service_id,
                            'lead_created_by' => $lead_by_pos_user,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => 0,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                        );
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        if ($insert_lead_id > 0) {
                            //get branch name
                            $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                            //create action log
                            $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WALKIN USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 178140614
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        }
                        $normal_lead_count = 1;
                    }
                    $lead_id = $insert_lead_id;
                    if ($normal_lead_count > 0) {

                        // $assigned_to = 2547057536; // moid.u@goldencube.ae
                        $assigned_to = 796909261; 
                        // 796909261 - Dineli.s@goldencube.ae
                        // 2879029976 - jeffrey.s@goldencube.ae 
                        $assigned_by = $lead_by_pos_user;  //178140614;
                        // echo "<pre>";
                        // print_r($this->db);
                        // echo "</pre>";
                        // exit();
                        if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                            $this->response('Parameters Missing', 400);
                        } else {
                            $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                            $insert_array = array(
                                'lead_id' => $lead_id,
                                'assigned_by' => $assigned_by,
                                'assigned_to' => $assigned_to,
                                'assigned_on' => date('Y-m-d H:i:s')
                            );

                            $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                            $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                            $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();

                            $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);
                            $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                            $postData = array(
                                'lead_id' => $lead_id,
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
                            $email_request_id = trim($response);
                            if (!empty($email_request_id))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $email_request_id), array('id' => $lead_id));

                            if ($insert > 0) {
                                $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                                if ($update) {
                                    //create action log
                                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $receiver_email = $csa->email;
                                    $receiver_name = $csa->first_name;
                                    $sender_email = $coordinator->email;
                                    $sender_name = $coordinator->first_name;

                                    $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you ! - ##RE-" . trim($email_request_id) . "##";
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

                                    // $temp_mail = 'mathanraj.g@mitrahsoft.in';

                                    $bcc_usermail = [];
                                    array_push($bcc_usermail, ["email" => "crm@ontimegroup.com", "name" => "CRM"]);

                                    // $cc_usermail = [];
                                    // array_push($cc_usermail, ["email" => "gowtham.sk@mitrahsoft.in", "name" => "Gowtham"]);

                                    $email_array = array(
                                        'email' => $receiver_email,
                                        // 'cc' => $cc_usermail,
                                        "bcc" => $bcc_usermail,
                                        'subject' => $subject,
                                        'template' => 'mails/template',
                                        'from_name' => "CRM ALERT",
                                        'message' => $message,
                                        'branch_id' => $lead_det["branch_id"],
                                    );
                                    $send_mail = send_template_email($email_array);
                                    log_message('error', $send_mail);
                                    // $queue_json = "";
                                    // if (!empty($queue->payment_type) && !empty($queue->token_id)) {
                                    //     $queue_token_id = $queue->token_id;
                                    //     // $queue_url = "https://paymentintegration.egovllc.com:8001/crm/".$queue->token_id;
                                    //     $queue_url = "https://paymentintegration.egovllc.com:8001/api/UpdateGoldenCube/crm/" . $queue_token_id;
                                    //     $ch = curl_init();
                                    //     curl_setopt($ch, CURLOPT_URL, $queue_url);
                                    //     // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                                    //     curl_setopt($ch, CURLOPT_POST, true);
                                    //     // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    //     $response = curl_exec($ch);
                                    //     curl_close($ch);
                                    //     $queue_json = json_decode($response);
                                    // }


                                    // $gc_lead_subject = "Lead Created for " . $lead_name . " 'Golden Cube' with " . $lead_id . "- ##RE-" . trim($email_request_id) . "##";

                                    // $gc_lead_message = "<br>Dear User<br/><br/>";
                                    // $gc_lead_message .= "A new lead has been created for the customer <strong> Golden Cube </strong> under the lead ID " . $lead_id . ". Please review the details and take the necessary actions to follow up.";
                                    // $gc_lead_message .= "Queue Response is : ". $queue_json;


                                    // $cc_usermail = [];
                                    // After Payment Completion for DLD Fees
                                    // array_push($cc_usermail, ["email" => "fatima@ontimebiz.com", "name" => "Fatima Salih"]);	// 2845782377
                                    // array_push($cc_usermail, ["email" => "fatma@ontimebiz.com", "name" => "Fatma Abdollah"]);	// 3654913804
                                    // array_push($cc_usermail, ["email" => "anil.d@ontimegroup.com", "name" => "Anil"]);	// 2233119808
                                    // array_push($cc_usermail, ["email" => "joseph.i@ontimegroup.com", "name" => "Joseph"]);	// 2251243126
                                    // array_push($cc_usermail, ["email" => "parthasarathy.d@mitrahsoft.in", "name" => "Sarathy"]);
                                    // array_push($cc_usermail, ["email" => "gowtham.sk@mitrahsoft.in", "name" => "Gowtham"]);

                                    // $to_usermail = [];
                                    // After Payment Completion for DLD Fees
                                    // array_push($to_usermail, ["email" => "mounira.z@goldencube.ae", "name" => "Mounira"]);	// 2628524949
                                    // array_push($to_usermail, ["email" => "hadeel.a@goldencube.ae", "name" => "Hadeel"]);	// 4267959933
                                    // array_push($to_usermail, ["email" => "vernie.c@goldencube.ae", "name" => "Vernie"]);	// 3001413024
                                    // array_push($to_usermail, ["email" => "Salam.A@goldencube.ae", "name" => "Salam"]);	// 4213254981
                                    // array_push($to_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Mani"]);

                                    // $email_array = array(
                                    //     'email' => $to_usermail,
                                    //     'cc' => $cc_usermail,
                                    //     "bcc" => $bcc_usermail,
                                    //     'subject' => $gc_lead_subject,
                                    //     'template' => 'mails/gc_template',
                                    //     'from_name' => "Golden Cube",
                                    //     'message' => $gc_lead_message,
                                    // );
                                    // $send_mail = send_template_email($email_array);
                                    // log_message('error', $send_mail);

                                    // $update = $this->mcommon->common_edit('leads', array('lead_status' => 320), array('id' => $lead_id));

                                    // $log_insert_array = array('action_id' => 420, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'GoldenCube WebApp Profile Submitted for Eligibility Checking.', 'action_by' => $assigned_by, 'status_id' => 320);
                                    // $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $this->response(["lead_id" => $lead_id, "message" => 'Lead has been assigned successfully!'], 200);
                                } else {
                                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));
                                    // $delete_log = $this->mcommon->common_delete('lead_action_log',array('id'=>$insert_log));

                                    $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
                                }
                            } else {
                                $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign lead at present.'], 500);
                            }
                        }
                        // $this->response(["lead_id" => $insert_lead_id, "message" => 'Lead has been created.', "statuscode" => 200], 200);
                    }
                } else {
                    $this->response(["message" => 'Unable to create leads at this moment.', "statuscode" => 500], 500);
                }
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function gc_book_appointment_post()
    {
        try {
            $q_lead_name = $this->post('full_name');
            $q_mobile = $this->post('phone_no');
            $q_country_code = $this->post('country_code');
            $q_email = $this->post('email');
            $booking_date = $this->post('booking_date');
            $booking_time = $this->post('booking_time');
            $notes = $this->post('notes');
            $summary_url = $this->post('summary_url');
            
            if ($q_lead_name == '' || $q_mobile == '' || $q_email == '') {
                $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
            }

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            $branch_id = 106;
            $lead_type = 'normal';
            $lead_by_pos_user = '574775548';   // goldencubezoho@goldencube.ae
            $lead_by_post_user_name = 'Golden Cube Zoho';
            //check category exist
            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
            }

            if ($lead_type == 'normal') {
                $category_id = 109;
                $service_id = 1009;

                //check category exist
                $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
                if ($cateogry_exist == 0) {
                    $this->response("Category doesn't exist. Update category first to create the lead", 404);
                }

                $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
                if ($service_exist == 0) {
                    $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
                }
            }

            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($q_email == '') ? $random_email : $q_email;
            $lead_email = trim($lead_email);
            $full_mobile = $q_country_code . $q_mobile;
            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $q_mobile));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));
            $check_mobile_contry_code_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $full_mobile));

            if ($check_email_exists != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
            }

            if ($check_mobile_exists != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $q_mobile), 'user_id');
            }

            if ($check_mobile_contry_code_exists != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $full_mobile), 'user_id');
            }

            if ($check_mobile_exists == 0 && $check_email_exists == 0 && $check_mobile_contry_code_exists == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '1';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $q_mobile,
                    'referal_code' => $referal_code,
                    'first_name' => $q_lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = 'United Arab Emirates';
                $user_data['country_code'] = $q_country_code;

                $insert = $this->mcommon->common_insert("lead_users", $user_data);
                $user_id = $this->mcommon->specific_row_value('lead_users', array('email' => $lead_email), 'user_id');
            }
            $lead_remarks = "<br><b><u>GoldenCube - Book Appointment</u></b>,<br>";
            $lead_remarks .= "Booking Date :&nbsp;<b>" .  $booking_date . "</b>,</br>";
            $lead_remarks .= "Booking Time :&nbsp;<b>" .  $booking_time . "</b>,</br>";
            if(!empty($summary_url))
                $lead_remarks .= "Booking Summary URL :&nbsp;<b>" .  $summary_url . "</b>,</br></br>";

            if(!empty($notes))
                $lead_remarks .= "Notes :&nbsp;<b>" . $notes . "</b>,</br></br>"; 

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
                            if ($lead_by_post_user_name == "POS") {
                                exit();
                            }
                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $target_service_id,
                                'lead_created_by' => $lead_by_pos_user,
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => 0,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'lead_by_pos_user' => $lead_by_pos_user,
                                'lead_by_post_user_name' => $lead_by_post_user_name,
                            );
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            if ($insert_lead_id > 0) {

                                $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                                $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WALKIN USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                $normal_lead_count++;
                            }
                        }
                    } else {

                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => $service_id,
                            'lead_created_by' => $lead_by_pos_user,
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => 0,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                        );
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        if ($insert_lead_id > 0) {
                            //get branch name
                            $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');

                            //create action log
                            $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> WALKIN USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 3729322111
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        }
                        $normal_lead_count = 1;
                    }
                    $lead_id = $insert_lead_id;
                    if ($normal_lead_count > 0) {
                        $assigned_to = 4213254981; // Salam.A@goldencube.ae
                        $assigned_by = $lead_by_pos_user;  //3729322111;

                        if ($lead_id == '' || $assigned_to == '' || $assigned_by == '') {
                            $this->response('Parameters Missing', 400);
                        } else {
                            $delete = $this->mcommon->common_delete('leads_assigned', array('lead_id' => $lead_id));
                            $insert_array = array(
                                'lead_id' => $lead_id,
                                'assigned_by' => $assigned_by,
                                'assigned_to' => $assigned_to,
                                'assigned_on' => date('Y-m-d H:i:s')
                            );

                            $insert = $this->mcommon->common_insert('leads_assigned', $insert_array);

                            $coordinator = $this->db->where("user_id", $assigned_by)->from("users")->get()->first_row();
                            $csa = $this->db->where("user_id", $assigned_to)->from("users")->get()->first_row();


                            $log_insert_array = array('action_id' => 403, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been assigned by <strong>' . $coordinator->first_name . '</strong> to <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 303);

                            $log_insert = $this->db->insert('lead_action_log', $log_insert_array);

                            $postData = array(
                                'lead_id' => $lead_id,
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
                            $email_request_id = trim($response);
                            if (!empty($email_request_id))
                                $update = $this->mcommon->common_edit('leads', array('email_request_id' => $email_request_id), array('id' => $lead_id));

                            if ($insert > 0) {
                                $update = $this->mcommon->common_edit('leads', array('is_assigned' => 1, 'lead_status' => 302), array('id' => $lead_id));

                                if ($update) {
                                    $log_insert_array = array('action_id' => 402, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been accepted by <strong>' . $csa->first_name . '</strong>', 'action_by' => $assigned_by, 'status_id' => 302);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                                    $receiver_email = $csa->email;
                                    $receiver_name = $csa->first_name;
                                    $sender_email = $coordinator->email;
                                    $sender_name = $coordinator->first_name;

                                    $subject = "Lead Assigned - " . $sender_name . " Assigned a new Lead to you ! - ##RE-" . trim($email_request_id) . "##";
                                    $message = "Dear " . $receiver_name . ",<br /><br />A Lead is has been assigned to you by <strong>" . $sender_name . "</strong>. <br /><br />Please login into CRM and go to https://crm.ontimegroup.com/testing/leads/lead/view/" . $lead_id . " .<br><br>Lead Description:<br>";

                                    $lead_det = $this->leads_model->lead_details($lead_id);

                                    if ($lead_det["lead_parent_id"] != 0) {
                                        $parent_lead_det = $this->leads_model->lead_details($lead_det["lead_parent_id"]);
                                    }

                                    $message .= "Customer Name: " . $lead_det["customer_name"];
                                    $message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                                    $message .= "<br>Customer Email: " . $lead_det["customer_email"];
                                    // $message .= "<br>Service:  " . $lead_det["category_code"] . " - " . $lead_det["service_name"];
                                    if ($lead_det["pos_pmt_number"] != NULL)
                                        $message .= "<br>Receipt Number: " . $lead_det["pos_pmt_number"];
                                    else if ($parent_lead_det["pos_pmt_number"] != NULL)
                                        $message .= "<br>Receipt Number: " . $parent_lead_det["pos_pmt_number"];

                                    $message .= "<br>Remarks: " . $lead_det["remarks"];

                                    // $temp_mail = 'manikandan.tm@mitrahsoft.in';

                                    $bcc_usermail = [];
                                    array_push($bcc_usermail, ["email" => "crm@ontimegroup.com", "name" => "CRM"]);

                                    $email_array = array(
                                        'email' => $receiver_email, // $temp_mail
                                        // 'cc' => $cc_usermail,
                                        "bcc" => $bcc_usermail,
                                        'subject' => $subject,
                                        'template' => 'mails/template',
                                        'from_name' => "CRM ALERT",
                                        'message' => $message,
                                        'branch_id' => $lead_det["branch_id"],
                                    );
                                    $send_mail = send_template_email($email_array);
                                    log_message('error', $send_mail);

                                    $this->response(["lead_id" => $lead_id, "message" => 'Lead has been created successfully!'], 200);
                                } else {
                                    $delete = $this->mcommon->common_delete('leads_assigned', array('id' => $insert));

                                    $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign the lead at present. Please try again later'], 500);
                                }
                            } else {
                                $this->response(["lead_id" => $lead_id, "message" => 'Unable to assign lead at present.'], 500);
                            }
                        }
                    }
                } else {
                    $this->response(["message" => 'Unable to create leads at this moment.', "statuscode" => 500], 500);
                }
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function get_lead_package_entries($lead_id, $lead_package_id)
    {
        $this->db->select("lp.*,lps.*,ls.category_id, ls.service_name");
        $this->db->from('lead_packages as lp');
        $this->db->join('lead_package_services as lps', 'lps.package_id=lp.package_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=lps.service_id');
        $this->db->join('lead_package_details as lpd', 'lpd.package_id = lp.package_id and lpd.service_id = lps.service_id');
        // $this->db->where('lp.package_id', $package_id);
        $this->db->where('lpd.package_id', $lead_package_id);
        $this->db->where('lpd.lead_id', $lead_id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function sublead_creation_gc_post()
    {
        try{
            $lead_id = $this->post('lead_id');
            var_dump("Main lead id is ");
            var_dump($lead_id); 
            var_dump("Remove this");exit;
            // This function is for Create the sublead while the Online payment only for the Goldencube
            $this->db->select('is_additional_payment')
                ->from('lead_action_log') ->where(array('action_id' => 412, 'lead_id' => $lead_id))
                ->order_by('id', 'DESC')->limit(1);
            $query = $this->db->get();
            $is_add_pay_results = $query->row();
            $is_additional_payment = $is_add_pay_results->is_additional_payment;
            $branch_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'branch_id');
            $customer_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'customer_id');

            if($is_additional_payment == NULL && $branch_id == 106){
                $lead_package_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id, 'lead_parent_id' => 0), 'package_id');
                $package_det = $this->get_lead_package_entries($lead_id, $lead_package_id);
                $lead_package_name = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_package_name');

                $package_created_by = $package_det[0]['created_by'];
                $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $package_created_by, 'is_primary_group_id' => 1), 'group_id');

                for ($i = 0; $i < count($package_det); $i++) {
                    $card_amount = ($package_det[$i]['govt_fee'] * (2.25 / 100));

                    $insert_lead_array = array(
                        'customer_id' => $customer_id,
                        'branch_id' => $branch_id,
                        'category_id' => $package_det[$i]['category_id'],
                        'service_id' => $package_det[$i]['service_id'],
                        'lead_created_by' => $package_det[$i]['created_by'],
                        'lead_added_on' => date('Y-m-d H:i:s'),
                        'contactable_date' => date('Y-m-d H:i:s'),
                        'lead_status' => 301,
                        'package_id' => $package_det[$i]['package_id'],
                        'order_receipt' => 0,
                        'remarks' => $package_det[$i]['service_name'],
                        'is_assigned' => 0,
                        'lead_parent_id' => $lead_id,
                        "is_direct_invoice" => $package_det[$i]['is_direct_invoice'],
                        "govt_fee" => $package_det[$i]['govt_fee'],
                        "typing_fee" => $package_det[$i]['typing_fee'],
                        "msd_key" => $package_det[$i]['msd_key'],
                        "is_pos_typing_fee" => $package_det[$i]['is_pos_typing_fee'],
                        "card_amount" => $card_amount,
                        "lead_package_name" => $lead_package_name,
                        'created_group_id' => $created_group_id,
                    );
                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                    var_dump($insert_lead_id);
                }
                $update_lead_array = array(
                    'total_no_subleads' => count($package_det),
                    'no_of_open_subleads' => count($package_det),
                    'no_of_closed_subleads' => 0
                );
                $update = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));
            }
            exit;
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function update_paymentdetails_post()
    {
        $jsonData_intial = json_decode(file_get_contents("php://input"), true);
        
        if (!empty($jsonData_intial)) {
            // $pos_user_id = $jsonData_intial['emp_id'];
            // $lead_id = $jsonData_intial['lead_id'];
            // $paymentAmount =$jsonData_intial['lead_amount'];
            // $payment_ref =$jsonData_intial['payment_ref'];
            // $cust_key = $jsonData_intial['cust_key'];
            // $pos_pmt_number = $jsonData_intial['pmt_number'];
            $pos_user_id = isset($jsonData_intial['emp_id']) ? $jsonData_intial['emp_id'] : null;
            $lead_id = isset($jsonData_intial['lead_id']) ? $jsonData_intial['lead_id'] : null;
            $paymentAmount = isset($jsonData_intial['lead_amount']) ? $jsonData_intial['lead_amount'] : null;
            $payment_ref = isset($jsonData_intial['payment_ref']) ? $jsonData_intial['payment_ref'] : null;
            $cust_key = isset($jsonData_intial['cust_key']) ? $jsonData_intial['cust_key'] : null;
            $pos_pmt_number = isset($jsonData_intial['pmt_number']) ? $jsonData_intial['pmt_number'] : null;
            $type = isset($jsonData_intial['type']) ? $jsonData_intial['type'] : null;
            $MainServiceId = isset($jsonData_intial['MainServiceId']) ? $jsonData_intial['MainServiceId'] : null;
            $data_encode=json_encode($jsonData_intial);   

            if (is_null($type)) {
                $this->response(array('status' => 'error', 'result' => 'Missing required parameters Type'), 200); 
            }else if($type == "Error"){
                $update = $this->mcommon->common_edit("lead_action_log", array("pg_res" =>$data_encode), array("lead_id" => $lead_id,"payment_ref" => $payment_ref,"action_id" => 442));
                $this->response(array('status' => 'error', 'result' => 'Payment Failed Response Updated in log'), 200); 
            }else{
                $update = $this->mcommon->common_edit("lead_action_log", array("pg_res" =>$data_encode), array("lead_id" => $lead_id,"payment_ref" => $payment_ref,"action_id" => 442));
            }
            
            if (is_null($pos_user_id) || is_null($lead_id) || is_null($paymentAmount) || 
                is_null($payment_ref) || is_null($cust_key) || is_null($pos_pmt_number)) {
                $this->response(array('status' => 'error', 'result' => 'Missing required parameters'), 200); 
            } else {
                $slo_headnum =  $jsonData_intial['slo_headnum'];
                $auth_user_id = $this->mcommon->specific_row_value('users', array('employee_id' => $pos_user_id), 'user_id');
                $action_id = 426;

                $action_log = [
                    "action_id" => $action_id,
                    "lead_id" => $lead_id,
                    "action_amount" => $paymentAmount,
                    "remarks" => "Customer Attempt to Pay",
                    "action_by" => $auth_user_id,
                    "status_id" => 307,
                    "payment_ref" => $payment_ref
                ];

                $attempt_action_id = $this->mcommon->common_insert('lead_action_log', $action_log);
                // var_dump($attempt_action_id, 'attempt_action_id'); exit;
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $auth_user_id), 'pos_user_id');
                if ($user_pos == 0 || $user_pos == NULL) {
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $auth_user_id), 'employee_id');
                }

                $lead_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $attempt_action_id), 'lead_id');
                $branch_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'branch_id');

                $response = json_encode($jsonData_intial); //$this->network_authentication();
                // $auth = json_decode($response);

                $paymentState = "CAPTURED";
                $OrderID = "OTLDPMET" . $lead_id;
                $Amount = ((float)$paymentAmount);
                $Payer = "";
                $UniqueID = $payment_ref;

                if ($paymentState == "CAPTURED" || $paymentState == "STARTED") {
                    $date = date('d-m-Y');
                    $date1 = date("d-m-Y h:i:sa");
                    $time = date('h:i A', strtotime($date1));
                    $desc = 'nill';
                    $total = 0;

                    $payments_data = array(
                        'user_id' => $lead_id,
                        'response_code' => "",
                        'response_class' => "",
                        'response_description' => $response,
                        'response_class_description' => $paymentState,
                        'language' => "",
                        'approval_code' => "",
                        'account' => "",
                        'balance' => "",
                        'lead_id' => $OrderID,
                        'amount' => $Amount,
                        'fees' => "",
                        'card_number' => "",
                        'payer' => "",
                        'card_token' => "",
                        'card_brand' => "",
                        'card_expiry' => "",
                        'card_type' => "",
                        'unique_id' => $UniqueID,
                        'created_date' => $date
                    );

                    $lead_id = str_replace("OTLDPMET", "", $OrderID);

                    $payment_insert_id = $this->mcommon->common_insert('lead_payments', $payments_data);

                    $so_order = NULL;
                    $raw_salesorder = NULL;
                    $pmt_response = NULL;
                    $raw_pmtnumber = NULL;
                    $pos_cust_key = NULL;
                    $lead_det = $this->leads_model->lead_details($lead_id);

                    if($branch_id == 106  && !empty($pos_pmt_number)){
                        $sublead_count = $this->mcommon->specific_record_counts('leads', array('lead_parent_id' => $lead_id));
                        if($sublead_count == 0){
                            // create subleads
                            $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name, ontime_category_services_.category_id")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();


        
                            if(count($details) > 0 && !in_array($lead_det['package_id'], [199,204,205,258,259,260, 200,201,206,207,208,209,261,262,263, 222,223,224, 225,226,227, 228,229,230])) {
                                $this->db->select("ls.service_name, lpd.created_by, lpd.govt_fee, ls.category_id, ls.service_id, lpd.package_id, lpd.is_direct_invoice, lpd.typing_fee, lpd.msd_key, lpd.is_pos_typing_fee, lpd.*, lps.* ");
                                $this->db->from('lead_package_details as lpd');
                                $this->db->join('lead_package_services as lps', 'lpd.service_id = lps.service_id');
                                $this->db->join('ontime_category_services_ as ls', 'ls.service_id=lps.service_id');
                                $this->db->where('lpd.lead_id', $lead_id);
                                $this->db->where('lpd.package_id', $lead_det['package_id']);
                                $this->db->order_by('ISNULL(lpd.show_order), lpd.show_order', 'ASC');
                                $package_det = $this->db->get()->result_array();
                                foreach($package_det as $detail){
                                    $insert_lead_array = array(
                                        'customer_id' => $lead_det["customer_id"],
                                        'branch_id' => $lead_det["branch_id"],
                                        'category_id' => $detail["category_id"],
                                        'service_id' => $detail["service_id"],
                                        'lead_created_by' => $lead_det["lead_created_by"],
                                        'lead_added_on' => date('Y-m-d H:i:s'),
                                        'contactable_date' => date('Y-m-d H:i:s'),
                                        'lead_status' => 301,
                                        'package_id' => $detail["package_id"],
                                        'order_receipt' => 0,
                                        'remarks' => $detail["service_name"],
                                        'is_assigned' => 0,
                                        'lead_parent_id' => $lead_det["id"],
                                        "is_direct_invoice" => $detail["is_direct_invoice"],
                                        "govt_fee" => $detail["govt_fee"],
                                        "typing_fee" => $detail["typing_fee"],
                                        "msd_key" => $detail["msd_key"],
                                        "is_pos_typing_fee" => $detail["is_pos_typing_fee"],
                                    );
                                    $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                                }
                            }
                        }
                    }

                    if ($user_pos == 0 || $user_pos == NULL) {
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                        $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'pos_user_id');
                        if ($user_pos == 0 || $user_pos == NULL)
                            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                    }

                    if ($lead_det["category_id"] == 125) {

                        $req["Customer"] = array(
                            "Cust_EngName" => $lead_det["customer_name"], 
                            "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], 
                            "Cust_Email" => $lead_det["customer_email"]);
                        $req["OrderRef"] = $payment_insert_id . '-' . $OrderID;
                        // $req["Payment"] = array("ActAmt" => $Amount, "OnlinePaymentRef" => $UniqueID);

                        $logs = $this->db->select("*")->from("lead_payment_details")->where("lead_action_log_id", $action_id)->get()->result_array();

                        $so_bots = [];
                        $services = "";
                        foreach ($logs as $log) {
                            $services .= $log["service_name"] . ",";
                            array_push($so_bots, ["Id" => $log["bot_id"], "DiscAmt" => $log["discount"], "AddTypingFee" => $log["typing_fee"]]);
                        }

                        $req["ServDescription"] = $services;
                        $req["salesorderdtl"] = $so_bots;
                        $req["User"] = ["User_ID" => $user_pos];

                        // POS Changes 
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                        $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');

                        if (!empty($lead_det["lead_zoho_id"])) {
                            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                            if(!empty($lead_created_by)){
                                $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                                $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                            } else {
                                $created_by_user = '';
                            }
                            $req["Payment"] = array(
                                "ActAmt" => $Amount,
                                "OnlinePaymentRef" => $UniqueID,
                                "CampaignSource" => $lead_det["lead_ad_campaign"],  // campaign Name
                                "ZohoLeadSource" => $lead_det["lead_source"],       // Facebook Ads
                                "CampaignId" => $lead_det["lead_ad_campaign_id"],   // campaign Id
                                "ZohoLeadId" => $lead_det["lead_zoho_id"],          // Zoho CRM Lead ID
                                "LeadFrom" => 'Zoho',
                                "CRMLeadId" => $lead_id,
                                "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,                 // email
                                "MainServiceId" => $MainServiceId
                            );
                        } else {
                            $req["Payment"] = array(
                                "ActAmt" => $Amount,
                                "OnlinePaymentRef" => $UniqueID,
                                "LeadSource" => 'Website',
                                "LeadFrom" => $lead_det["lead_from"],                   // Baraha, OntimeGOV, Goldencube
                                "CRMLeadId" => $lead_id,
                                "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                                "MainServiceId" => $MainServiceId
                            );
                        }

                        if (!empty($lead_det["pos_cust_key"])) {
                            $req["Cust_Key"] = $lead_det["pos_cust_key"];
                        }

                        // $curl = curl_init();
                        // curl_setopt_array($curl, array(
                        //     CURLOPT_URL => 'http://94.200.55.118:8011/api/ApiPos/CreatePaymentfromCRM?createso=1',
                        //     CURLOPT_RETURNTRANSFER => true,
                        //     CURLOPT_ENCODING => '',
                        //     CURLOPT_MAXREDIRS => 10,
                        //     CURLOPT_TIMEOUT => 0,
                        //     CURLOPT_FOLLOWLOCATION => true,
                        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //     CURLOPT_CUSTOMREQUEST => 'POST',
                        //     CURLOPT_POSTFIELDS => json_encode($req),
                        //     CURLOPT_HTTPHEADER => array(
                        //         'Content-Type: application/json'
                        //     ),
                        // ));

                        // $response = curl_exec($curl);

                        // // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                        // // $response = curl_exec($curl);
                        // $raw_response = $response;
                        // if (curl_errno($curl)) {
                        //     $response = json_encode($req) . "<br>" . curl_error($curl);
                        //     curl_close($curl);
                        // } else {
                        //     $response = json_encode($req) . "<br>" . $response;
                        //     curl_close($curl);
                        // }

                        $response =  json_encod($req)  . "<br>" . json_encode($jsonData_intial);
                        $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));

                        $so_order = $slo_headnum;
                        $raw_salesorder = $so_order;
                        $pos_cust_key = $cust_key;
                        $so_order = "under the salesorder " . $so_order . "</b>";

                        $update = $this->mcommon->common_edit("leads", array("lead_status" => 310, "pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                        $update = $this->mcommon->common_edit("leads", array("lead_status" => 310, "pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("lead_parent_id" => $lead_id));
                        // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                        // echo "<br>".$so_order;
                        // exit();
                    } else {
                        $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                        $req["OrderRef"] = $payment_insert_id . '-' . $OrderID;
                        $req["Payment"] = array("ActAmt" => $Amount, "OnlinePaymentRef" => $UniqueID);
                        $req["ServDescription"] = $lead_det["service_name"];
                        $req["salesorderdtl"] = [];
                        $req["User"] = ["User_ID" => $user_pos];

                        if ($lead_det["branch_id"] == 138) {
                            $req["DomainName"] = "bookings.ontimegroup.com";
                        } else {
                            $req["DomainName"] = "crm.ontimegroup.com";
                        }

                        // POS Changes 
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                        $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                        $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');

                        if (!empty($lead_det["lead_zoho_id"])) {
                            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                            if(!empty($lead_created_by)){
                                $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                                $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                            } else {
                                $created_by_user = '';
                            }
                            $req["Payment"] = array(
                                "ActAmt" => $Amount,
                                "OnlinePaymentRef" => $UniqueID,
                                "CampaignSource" => $lead_det["lead_ad_campaign"],
                                "ZohoLeadSource" => $lead_det["lead_source"],
                                "CampaignId" => $lead_det["lead_ad_campaign_id"],
                                "ZohoLeadId" => $lead_det["lead_zoho_id"],
                                "LeadFrom" => 'Zoho',
                                "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                            );
                        } else {
                            $req["Payment"] = array(
                                "ActAmt" => $Amount,
                                "OnlinePaymentRef" => $UniqueID,
                                "LeadSource" => 'Website',
                                "LeadFrom" => $lead_det["lead_from"],
                                "CRMLeadId" => $lead_id,
                                "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                            );
                        }

                        if (!empty($lead_det["pos_cust_key"])) {
                            $req["Cust_Key"] = $lead_det["pos_cust_key"];
                        }

                        // $curl = curl_init();
                        // curl_setopt_array($curl, array(
                        //     CURLOPT_URL => 'http://94.200.55.118:8011/api/ApiPos/CreatePaymentfromCRM?createso=0',
                        //     CURLOPT_RETURNTRANSFER => true,
                        //     CURLOPT_ENCODING => '',
                        //     CURLOPT_MAXREDIRS => 10,
                        //     CURLOPT_TIMEOUT => 0,
                        //     CURLOPT_FOLLOWLOCATION => true,
                        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        //     CURLOPT_CUSTOMREQUEST => 'POST',
                        //     CURLOPT_POSTFIELDS => json_encode($req),
                        //     CURLOPT_HTTPHEADER => array(
                        //         'Content-Type: application/json'
                        //     ),
                        // ));

                        // $response = curl_exec($curl);

                        // // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                        // // $response = curl_exec($curl);
                        // $raw_response = $response;
                        // if (curl_errno($curl)) {
                        //     $response = json_encode($req) . "<br>" . curl_error($curl);
                        //     curl_close($curl);
                        // } else {
                        //     $response = json_encode($req) . "<br>" . $response;
                        //     curl_close($curl);
                        //     $pmt_response = $response;
                        // }

                        $response =  json_encode($req)  . "<br>" . json_encode($jsonData_intial);

                        $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));


                        $so_order = $lead_id; //so_will not create
                        $raw_pmtnumber = $pos_pmt_number;
                        $pos_cust_key = $cust_key;
                        $so_order = "under the Payment Receipt " . $so_order . "</b>";
                        $pmt_response = $response;


                        // $update = $this->mcommon->common_edit("leads", array("lead_status" => 310, "pos_salesorder" => $raw_salesorder), array("id" => $lead_id));
                        // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                        // echo "<br>".$so_order;
                        // exit();
                    }

                    if ($lead_det["category_id"] != 125 && !empty($raw_pmtnumber)) {

                        $customer_id = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');

                        $log_insert_array = array("action_id" => 418, "lead_id" => $lead_id, "action_amount" => $Amount, "payment_id" => $payment_insert_id, "remarks" => "Customer paid " . $Amount . " AED by " . $Payer . " Card for <b>#" . $req["OrderRef"] . " " . $raw_pmtnumber . "</b>", "action_by" => $customer_id, "status_id" => 311, "pos_pmt_response" => $pmt_response, "pos_pmt_number" => $raw_pmtnumber);

                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        // $updates = $this->mcommon->common_edit("lead_action_log", array("lead_id" => $lead_id, "action_id" => 412, "action_amount" => $Amount), array("payment_id" => $payment_insert_id));

                        $updates = $this->mcommon->common_edit("lead_action_log", array("payment_id" => $payment_insert_id), array("lead_id" => $lead_id, "action_id" => 412, "action_amount" => $Amount));


                        $action = $this->db->where(['id' => $action_id])->get('lead_action_log')->first_row();
                        $customer = $this->db->where(['user_id' => $customer_id])->get('lead_users')->first_row();
                        $customer_contact = substr($customer->mobile, -10);
                        if (strlen($customer_contact) == 9) {
                            $customer_contact = '0' . $customer_contact;
                        }

                        $html = preg_replace('/<div.*iv>/', '<b>Payment Done for #' . $req["OrderRef"] . ' amount ' . $Amount . ' ' . $so_order . '</b>', strval($action->remarks));
                        $cc_usermail = [];
                        $csa_usermail = $this->mcommon->specific_row_value('users', array('user_id' => $action->action_by), "email");

                        $param_array_for_pos = array('customerPhoneNumber' => $customer_contact, 'customerName' => $customer->first_name . ' ' . $customer->last_name, 'templateMasterPriceList' => array(array('serviceName' => "Custom", 'govtFee' => '0.00', 'typingFee' => $Amount, 'templateId' => 13, 'quantity' => 1, 'paymentRefNo' => $ApprovalCode, 'orderReferenceList' => [$OrderID])));
                        $final_param_json = json_encode($param_array_for_pos);
                        log_message('error', $final_param_json);
                    }

                    if ($lead_det["category_id"] == 125) {
                        $customer_id = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');

                        $log_insert_array = array("action_id" => 418, "lead_id" => $lead_id, "action_amount" => $Amount, "payment_id" => $payment_insert_id, "remarks" => "Customer paid " . $Amount . " AED by " . $Payer . " Card for <b>#" . $req["OrderRef"] . " " . $so_order . "</b>", "action_by" => $customer_id, "status_id" => 311, "pos_pmt_response" => $pmt_response, "pos_pmt_number" => $raw_pmtnumber);

                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $updates = $this->mcommon->common_edit("lead_action_log", array("payment_id" => $payment_insert_id), array("lead_id" => $lead_id, "action_id" => 412, "action_amount" => $Amount));

                        $action = $this->db->where(['id' => $action_id])->get('lead_action_log')->first_row();

                        $customer = $this->db->where(['user_id' => $customer_id])->get('lead_users')->first_row();
                        $customer_contact = substr($customer->mobile, -10);
                        if (strlen($customer_contact) == 9) {
                            $customer_contact = '0' . $customer_contact;
                        }

                        $html = preg_replace('/<div.*iv>/', '<b>Payment Done for #' . $req["OrderRef"] . ' amount ' . $Amount . ' ' . $so_order . '</b>', strval($action->remarks));
                        $cc_usermail = [];
                        $csa_usermail = $this->mcommon->specific_row_value('users', array('user_id' => $action->action_by), "email");

                        $param_array_for_pos = array('customerPhoneNumber' => $customer_contact, 'customerName' => $customer->first_name . ' ' . $customer->last_name, 'templateMasterPriceList' => array(array('serviceName' => "Custom", 'govtFee' => '0.00', 'typingFee' => $Amount, 'templateId' => 13, 'quantity' => 1, 'paymentRefNo' => $ApprovalCode, 'orderReferenceList' => [$OrderID])));
                        $final_param_json = json_encode($param_array_for_pos);
                        log_message('error', $final_param_json);
                    }

                    if ($lead_det["branch_id"] == 107 || $lead_det["branch_id"] == 6 ||  $lead_det["branch_id"] == 13 ||  
                        $lead_det["branch_id"] == 14 ||  $lead_det["branch_id"] == 20 ||  $lead_det["branch_id"] == 21) {
                        array_push($cc_usermail, ["email" => "sandy@ontimebiz.com", "name" => "Sandy Blason Loable"]);
                        array_push($cc_usermail, ["email" => "khairi@ontimebiz.com", "name" => "Khairi Reda Khairi Mahmoud"]);
                    } else {
                        array_push($cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey Siega"]);
                    }

                    if ($csa_usermail != NULL) {
                        array_push($cc_usermail, ["email" => $csa_usermail]);
                    }

                    $lead = $this->db->where(['id' => $lead_id])->get('leads')->first_row();

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 310), array("id" => $lead_id));
                    $details = array();
                    if ($lead_det["branch_id"] != 25) {
                        $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                    }

                    if ($lead_det["category_id"] != 125 && !empty($raw_pmtnumber)) {
                        $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                        $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("lead_parent_id" => $lead_id));
                        //due to quotation
                        // $this->mcommon->common_edit("lead_action_log", array('status_id' => 308), array('lead_id' => $lead_id, 'action_id' => 444));
                        $this->mcommon->common_edit("lead_action_log", array('status_id' => 310), array('lead_id' => $lead_id, 'action_id' => 442, 'payment_ref' => $payment_ref));
                        // $parts = explode("-", $raw_pmtnumber);
                        // $PMTNumber = strip_tags(end($parts));
                        // $Quo_Number = $this->mcommon->specific_row_value('trn_quotationheader', array('main_lead_id' => $lead_id,'Is_Paid' => 0), 'DocNumb');
                        // $this->mcommon->common_edit("trn_quotationheader", array('Is_Paid' => 1,"PMTNumber" => $PMTNumber ), array("main_lead_id" => $lead_id,"DocNumb" => $Quo_Number));
                    }

                    if ($lead_det["category_id"] == 125) {
                        $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                        $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("lead_parent_id" => $lead_id));
                        //due to quotation
                        // $this->mcommon->common_edit("lead_action_log", array('status_id' => 308), array('lead_id' => $lead_id, 'action_id' => 444));
                        $this->mcommon->common_edit("lead_action_log", array('status_id' => 310), array('lead_id' => $lead_id, 'action_id' => 442, 'payment_ref' => $payment_ref));
                        // $parts = explode("-", $raw_pmtnumber);
                        // $PMTNumber = strip_tags(end($parts));
                        // $Quo_Number = $this->mcommon->specific_row_value('trn_quotationheader', array('main_lead_id' => $lead_id,'Is_Paid' => 0), 'DocNumb');
                        // $this->mcommon->common_edit("trn_quotationheader", array('Is_Paid' => 1,"PMTNumber" => $PMTNumber ), array("main_lead_id" => $lead_id,"DocNumb" => $Quo_Number));
                    }

                    $template = 'mails/payment_done';
                    if ($lead->category_id == 107) {
                        $template = "mails/payment_done";
                        $log_insert_array = array("action_id" => 410, "lead_id" => $lead_id, "action_amount" => $Amount, "remarks" => "Payment Done agianst the Translation Invoice", "action_by" => $customer_id, "status_id" => 305);

                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        $updates = $this->mcommon->common_edit("leads", array("status_id" => 305), array("id" => $lead_id));
                    }

                    if($lead_det["branch_id"] == 106){
                        $subject = ($raw_salesorder == "" || $raw_salesorder == NULL) ? $raw_pmtnumber : $raw_salesorder;
                        $cc = [["email"=>'Team@goldencube.ae']];
                        if($lead_det["is_corporate"] == 'Corporate'){
                            $gc_message['customer_name'] = $leadDetails["applicant_name"];
                            $cc = [["email"=>'Corporate@goldencube.ae']];
                        }
                        $email_array = array(
                            'name' => $lead_det["customer_name"],
                            'email' =>  $lead_det["customer_email"], //$cust_email,
                            'mobile' => $lead_det["customer_mobile"],
                            'subject' => 'Golden Cube - Payment Receipt - #' .$subject,
                            'template' =>  $template,
                            'from_name' => "Golden Cube",
                            'from_email' => "info@goldencube.ae",
                            // 'cc' => [["name" => $this->auth_first_name, "email" => $this->auth_email]],
                            'cc' => $cc,
                            // "bcc" => $bcc_usermail,
                            'message' => $html,
                            "reference" => $payment_insert_id . '-' . $OrderID,
                            "so_order" =>($raw_salesorder == "" || $raw_salesorder == NULL) ? $raw_pmtnumber : $raw_salesorder,
                            "service" => $lead_det["category_code"] . " - " . $lead_det["service_name"],
                            "amount" => $Amount,
                            "payment_type" => "CARD",
                            "details" => $details,
                            "branch_id" => $lead_det["branch_id"],
                            "is_terms_pdf" => true,
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);

                        if(in_array($lead_det['package_id'], [70,109,110, 74,113,114, 154,159,160, 156,163,164])) {
                            $gc_message = array(
                                'customer_name' => $lead_det["customer_name"],
                            );
                            $template = in_array($lead_det['package_id'], [70,109,110, 74,113,114]) ? 'mails/gc_family_with_m_rept' : 'mails/gc_family_without_m_rept';
                            $email_array = array(
                                'name' => $lead_det["customer_name"],
                                'email' => $lead_det["customer_email"], //$cust_email,
                                'subject' => 'Next Steps for Your UAE Residence Visa Application',
                                'template' =>  $template,
                                'message' => $gc_message,
                                "branch_id" => '106',
                                'cc' => $cc,
                            );
                            $send_mail = send_lead_template_email($email_array);
                            log_message('error', $send_mail);
                        }
                        
                    }else{
                        $email_array = array(
                            'name' => $lead_det["customer_name"],
                            'email' => $lead_det["customer_email"], //$cust_email,
                            'mobile' => $lead_det["customer_mobile"],
                            'subject' => 'OnTime Group - Payment Completion - #' . $OrderID,
                            'template' => $template,
                            'from_name' => "OnTime Group",
                            'from_email' => "crm@ontimegroup.com",
                            'cc' => [["name" => "GoldenCube", "email" => "team@goldencube.ae"]],
                            // 'cc' => $cc_usermail,
                            'message' => $html,
                            "reference" => $payment_insert_id . '-' . $OrderID,
                            "so_order" => ($raw_salesorder == "" || $raw_salesorder == NULL) ? $raw_pmtnumber : $raw_salesorder,
                            "service" => $lead_det["category_code"] . '-' . $lead_det["service_name"],
                            "amount" => $Amount,
                            "details" => $details,
                            "branch_id" => $lead_det["branch_id"],
                            "is_terms_pdf" => true,
                        );
                        $send_mail = send_lead_template_email($email_array);
                        log_message('error', $send_mail);
                        
                    }

                    if ($lead_det["branch_id"] == 106) {
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
                            $sub_lead_message .= "<br>Receipt Number: <strong>" . $raw_pmtnumber . "</strong>";
                            $sub_lead_message .= "<br>Remarks: " . $service_name;

                            $sub_lead_message .= "<br><br><br><br>Dear Reem<br><br>";
                            $sub_lead_message .= "<br>Please proceed with completing the <strong> DLD Certificate </strong> transaction for the customer once the lead is completed<br><br>";

                            $sublead_to_usermail = [];
                            // After Payment Completion for DLD Fees
                            array_push($sublead_to_usermail, ["email" => "ishti.b@goldencube.ae", "name" => "Ishti"]);    // 3644347224
                            array_push($sublead_to_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                            // array_push($sublead_to_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453
                            // array_push($sublead_to_usermail, ["email" => "team@goldencube.ae", "name" => "GoldenCube"]);

                            $cc_usermail = [];
                            array_push($cc_usermail, ["email" => "team@goldencube.ae", "name" => "GoldenCube"]);

                            $email_array = array(
                                'email' => $sublead_to_usermail,
                                'cc' => $cc_usermail,
                                'subject' => $sub_lead_subject,
                                'template' => 'mails/template',
                                'from_name' => "CRM ALERT",
                                'message' => $sub_lead_message,
                                'branch_id' => $lead_det["branch_id"],
                            );

                            $send_mail = send_template_email($email_array);

                            $action_by = 178140614;        // info@ontimegroup.com
                            $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Reem and Ishti";
                            $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                            $update_sla_flag_array = ['sla_violation_status' => 'red',];
                            $this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $lead_id]);

                            log_message('error', $send_mail);
                        }
                    }

                    if ($lead_det["branch_id"] == 138) {
                        // $lead_id=152932;
                        // $userId = $emp_user_id;
                        $userId = $auth_user_id;
                        // $lead_det = $this->leads_model->lead_details($lead_id);
                        $appointment = $this->db->select("*")->from("calendar_appointment")->where("lead_id", $lead_id)->get()->first_row();
                        $appointment_id = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'id');
                        $booking_id = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'booking_id');
                        $time_slot = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'booking_timeslot');


                        $log_insert_array = array('appointment_id' => $appointment_id, 'remark' => "Booking Confirmed / Paid Updated", 'created_at' => date('Y-m-d H:i:s'), 'status_code' => 903, 'status_description' => "Payemnt Done Through Online", 'created_by' => $userId, 'updated_at' => date('Y-m-d H:i:s'));
                        $insert_log = $this->mcommon->common_insert('calendar_log', $log_insert_array);

                        $update = $this->mcommon->common_edit("calendar_appointment", array("status" => 903, "updated_at" => date('Y-m-d H:i:s')), array("id" => $appointment_id));

                        $postData = [
                            'id' => $appointment_id,
                            'booking_id' => $booking_id,
                            'status' => 903,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];

                        // Convert the data to JSON format
                        $fields = json_encode($postData);

                        // Initialize cURL session
                        $ch = curl_init();

                        // Set cURL options
                        curl_setopt($ch, CURLOPT_URL, 'https://ontimegov.com/api/v1/baraha/Order/paid');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Accept: application/json',
                            'api-key: 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',
                            'Content-Type: application/json'
                        ]);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HEADER, false);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                        // Execute cURL request
                        $response = curl_exec($ch);
                        $error = curl_error($ch);

                        curl_close($ch);
                        $time = $time_slot;
                        $parts = explode(', ', $time);
                        $date = $parts[0]; // Extract the date
                        $time_slots = array_slice($parts, 0); // Get all the time slots (excluding the date)
                        if ($time == NULL) {
                            $time_range = '09:00 AM - 08:00 PM'; // 9 AM to 6 PM
                        }
                        else if (count($time_slots) == 1) {
                            $first_time = $time_slots[0];
                            $start_time = new DateTime($first_time);

                            $end_time = clone $start_time;
                            $end_time->modify('+1 hour');

                            $time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
                        }
                        else {
                            $first_time = new DateTime($time_slots[0]);
                            $last_time = new DateTime($time_slots[count($time_slots) - 1]);

                            $time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
                        }

                        $baraha_van_cc = [];
                        array_push($baraha_van_cc, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);    // 3644347224
                        //     array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                        $pos_pmt_number_mail = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'pos_pmt_number');
                        $email_array1 = array(
                            'name' => $lead_det["customer_name"],
                            'email' => $lead_det["customer_email"], //$cust_email,
                            'subject' => 'Appointment Confirmed - Payment Received & Receipt Attached',
                            'template' => 'mails/bv_payment_received',
                            'from_name' => "ONTIME GOV ALERT",
                            'from_email' => 'mobile.medical@ontimegov.com',
                            // 'cc' => $baraha_van_cc,
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
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        $user = $this->db->where("user_id", $userId)->from("users")->get()->first_row();
                        $email_array = array(
                            'name' => $user->email,
                            // 'email' => $user->first_name . $user->last_name,
                            // 'name' => "mathan",
                            'email' => $cust_email,
                            'subject' => 'Appointment Confirmed - Payment Received & Receipt Attached',
                            'template' => 'mails/bv_payment_received',
                            'from_name' => "ONTIME GOV ALERT",
                            'from_email' => 'mobile.medical@ontimegov.com',
                            // 'cc' => $baraha_van_cc,
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

                        // $send_mail = send_template_email($email_array);
                        // log_message('error', $send_mail);

                        $is_exist = $this->mcommon->specific_record_counts('leads', array('lead_parent_id' => $lead_id));

                        if ($is_exist > 2) {
                            $sub_lead_reschedule_data = $this->mcommon->specific_fields_records_all('leads', ["lead_parent_id" => $lead_id, "remarks" => "Reschedule"]);

                            // var_dump($sub_lead_reschedule_data);
                            // exit;
                            foreach ($sub_lead_reschedule_data as $value) {
                                if ($value["remarks"] == "Reschedule" && empty($value["pos_invresponse"]) && empty($value["order_receipt"])) {

                                    $url = 'https://crm.ontimegroup.com/api/v1/order/subleadcompleteapi';
                                    $getData = [
                                        'code' => $value["id"],
                                        'invoice_id' => $value["is_direct_invoice"],
                                        'from' => "api",
                                        'user_id' => $userId,
                                    ];

                                    // Append query parameters to URL
                                    $urlWithParams = $url . '?' . http_build_query($getData);

                                    // Initialize cURL session
                                    $curl = curl_init();

                                    // cURL options
                                    curl_setopt_array($curl, [
                                        CURLOPT_URL => $urlWithParams,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'GET',
                                    ]);

                                    // Execute the cURL request
                                    $response = curl_exec($curl);

                                    // Check for cURL errors
                                    if (curl_errno($curl)) {
                                        $error_msg = curl_error($curl);
                                        curl_close($curl);
                                        log_message('error', 'cURL Error: ' . $error_msg);
                                        $sub_lead_message = 'Test Mail sublead not closed reschedule' . $error_msg;
                                        $sub_lead_subject .= "The API details Failed sublead reshedule closeing.";
                                        $email_array = array(
                                            'email' =>  'mathanraj.g@mitrahsoft.in',
                                            'subject' => $sub_lead_subject,
                                            'template' => 'mails/template',
                                            'from_name' => "mathan",
                                            'message' => $sub_lead_message,
                                        );
                                        // $send_mail = send_template_email($email_array);
                                        // return false; // Handle error appropriately
                                    } else {
                                        // Close the cURL session
                                        curl_close($curl);

                                        // Decode JSON response (if applicable)
                                        $responseData = json_decode($response, true);
                                        $sub_lead_message = 'Test Mail sublead  reschedule' . $response;
                                        $sub_lead_subject .= "The API details sucess sublead reshedule completed .";
                                        $email_array = array(
                                            'email' =>  'mathanraj.g@mitrahsoft.in',
                                            'subject' => $sub_lead_subject,
                                            'template' => 'mails/template',
                                            'from_name' => "mathan",
                                            'message' => $sub_lead_message,
                                        );
                                        // $send_mail = send_template_email($email_array);

                                        $log_insert_array = array('appointment_id' => $appointment_id, 'remark' => "Booking Confirmed / Closed sub lead", 'created_at' => date('Y-m-d H:i:s'), 'status_code' => 910, 'status_description' => "Payemnt Done Through Online and sublead closed", 'created_by' => $userId, 'updated_at' => date('Y-m-d H:i:s'));
                                        $insert_log = $this->mcommon->common_insert('calendar_log', $log_insert_array);

                                        $update = $this->mcommon->common_edit("calendar_appointment", array("status" => 910, "updated_at" => date('Y-m-d H:i:s')), array("id" => $appointment_id));

                                        $postData = [
                                            'id' => $appointment_id,
                                            'booking_id' => $booking_id,
                                            'status' => 910,
                                            'updated_at' => date('Y-m-d H:i:s'),
                                        ];

                                        // Convert the data to JSON format
                                        $fields = json_encode($postData);

                                        // Initialize cURL session
                                        $ch = curl_init();

                                        // Set cURL options
                                        curl_setopt($ch, CURLOPT_URL, 'https://ontimegov.com/api/v1/baraha/Order/paid');
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                            'Accept: application/json',
                                            'api-key: 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',
                                            'Content-Type: application/json'
                                        ]);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_HEADER, false);
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                                        // Execute cURL request
                                        $response = curl_exec($ch);
                                        $error = curl_error($ch);

                                        // Close cURL session
                                        curl_close($ch);
                                    }
                                }
                            }
                        }
                    }

                    if ($lead_det["lead_parent_id"] != NULL) {
                        $this->db->where('id', $lead_det["id"]);
                        $this->db->set('additional_govt_fee', 'additional_govt_fee+' . $Amount, FALSE);
                        $this->db->update('leads');
                    }

                    // Automatically Convert to Invoice Process - START
                    if($lead_det['branch_id'] == 106 && !empty($raw_pmtnumber)){
                        $sublead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'is_direct_invoice >' => 0), 'id');
                        $service_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'is_direct_invoice >' => 0), 'service_id');
                        $gc_service_id = $this->mcommon->specific_row_value('ontime_category_services_', array('service_id' => $service_id), 'gc_service_id');
                        $order_receipt = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'is_direct_invoice >' => 0), 'order_receipt');

                        if((!empty($sublead_id) && $sublead_id != NULL) && ($gc_service_id == 94 || $gc_service_id == 2303 || $gc_service_id == 2304) && $order_receipt == 0){
                            $inovice_id = $this->mcommon->specific_row_value('leads', array('id' => $sublead_id), 'is_direct_invoice');
                            $pos_invoice_id = $this->mcommon->specific_row('pos_direct_invoice_list', ["id" => $inovice_id]);
                            $sublead = $this->mcommon->specific_row('leads', ["id" => $sublead_id]);
                            $lead = $this->mcommon->specific_row('leads', ["id" => $sublead["lead_parent_id"]]);
                            $pmt = !empty($raw_pmtnumber) ? $raw_pmtnumber : $lead["pos_pmt_number"];
                    
                            $lead_det = $this->leads_model->lead_details($lead["id"]);
                    
                            $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                            $req["PMTNumber"] = $pmt;
                            $req["OrderRef"] = $sublead["id"] . '-OTLDDI' . $lead["id"];
                            $_SubLeadId = $sublead["id"] ? $sublead["id"] : $lead["id"];

                            $typing_fee = $this->mcommon->specific_row_value('leads', array('id' => $sublead_id), 'typing_fee');
                    
                            // $action_id
                            $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                            $req["salesorderdtl"] = [["Id" => 1094, "AddTypingFee" => $typing_fee, "SubLeadId" => $_SubLeadId]];
                    
                            $req["User"] = ["User_ID" => $user_pos];
                    
                            // POS Changes 
                            $lead_id = $lead["id"];
                            $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                            $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                    
                            if(!empty($lead_det["lead_zoho_id"])){
                                $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                                if(!empty($lead_created_by)){
                                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                                    $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
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
                                    "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                                );
                                
                            } else {
                                $req["Payment"] = array(
                                    "LeadSource" => 'Website',
                                    "LeadFrom" => $lead_det["lead_from"],
                                    "CRMLeadId" => $lead_id,
                                    "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
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
                    
                            $raw_response = $response;
                            if (curl_errno($curl)) {
                                $response = json_encode($req) . "<br>" . curl_error($curl);
                                curl_close($curl);
                            } else {
                                $response = json_encode($req) . "<br>" . $response;
                                curl_close($curl);
                            }
                    
                            $res_json = json_decode($raw_response);
                            if(isset($res_json->ResponseCode) && $res_json->ResponseCode != 1){
                                if (isset($res_json->Data->SLI_Headnum)) {
                                    $so_order = $res_json->Data->SLI_Headnum;
                                    $pos_cust_key = $res_json->Data->Cust_Key;
                                    $raw_salesorder = $so_order;
                                    $so_order = "under the payment receipt " . $so_order . "</b>";
                                }
                                $this->mcommon->common_edit("leads", array("pos_so_response" => $response, "pos_invresponse" => $raw_salesorder, "pos_pmt_number" => $pmt,"pos_cust_key" =>  $pos_cust_key), array("id" => $sublead["id"]));

                                if(isset($res_json->Data->LeadDetails)){
                                    $pos_lead_details = $res_json->Data->LeadDetails;
                                    $pos_govt_fee = $pos_lead_details[0]->govt_fee;
                                    $pos_typing_fee = $pos_lead_details[0]->typing_fee;
                                    $pos_card_amnt = $pos_lead_details[0]->Card_Amnt;
                                    $pos_disc_amnt = $pos_lead_details[0]->Disc_Amnt;
                                    $pos_tax_amnt = $pos_lead_details[0]->Tax_Amnt;
                                    $pos_tot_revn = $pos_lead_details[0]->Tot_Revn;
                                    $pos_ref1 = $pos_lead_details[0]->Ref1;
                                    $pos_ref2 = $pos_lead_details[0]->Ref2;
                                    $pos_username = $pos_lead_details[0]->pos_username;
                                    $pos_tot_amnt = $pos_lead_details[0]->Tot_Amt;

                                    $this->mcommon->common_edit("leads", array("pos_GovtFees" => $pos_govt_fee, "pos_TypeFee" => $pos_typing_fee, "pos_Card_Amnt" => $pos_card_amnt, "pos_Disc_Amnt" => $pos_disc_amnt, "pos_Tax_Amnt" => $pos_tax_amnt, "pos_Tot_Revn" => $pos_tot_revn, "ref1" => $pos_ref1, "ref2" => $pos_ref2, "pos_username" => $pos_username, "pos_Tot_Amt" => $pos_tot_amnt), array("id" => $sublead["id"]));
                                }
                        
                                $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $raw_salesorder;
                        
                                $log_insert_array = array('action_id' => 410, 'lead_id' => $sublead["id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $emp_user_id, 'status_id' => 305);
                                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                if ($insert_log > 0) {
                                    $update_lead_array = array('lead_status' => 305, 'order_receipt' => $raw_salesorder, "completed_by" => $emp_user_id);
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
                        
                                        $sub_lead_message = "<br><br>Kindly proceed with completing the <strong>" . $service_name . "</strong> for the lead listed below <br>";
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
                        
                                        $email_array = array(
                                            'email' =>  $sublead_cc_usermail,
                                            'subject' => $sub_lead_subject,
                                            'template' => 'mails/template',
                                            'from_name' => "Golden Cube",
                                            'message' => $sub_lead_message,
                                            'branch_id' => $lead_det["branch_id"],
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
                                } 
                            } else if(isset($res_json->ResponseCode) && $res_json->ResponseCode == 1){
                                if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                                    $res_message = $res_json->ResponseMsg;
                                    $res_message = json_encode($req) . "<br>" . $res_message;
                                    $log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'action_by' => $emp_user_id, 'status_id' => 304);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                }
                            }
                        }
                    }
                    // Automatically Convert to Invoice Process - END

                    $this->response(array('status' => 'success', 'result' => 'Payment Success'), 200);

                    // header("Location: /payment/success?response=200");
                    // exit();
                } else {
                    // $remove = $this->mcommon->common_delete('paid_pcr_appointments', array('order_id' => $order_id));
                    //log_message('error',$response_curl);
                    $this->response(array('status' => 'error', 'result' => 'Payment Failure'), 200);
                    // header("Location: /payment/failure?title=Payment Failure.&desc=Please try again later.&reason=" . $auth_response);
                    // exit();
                }
            }
        } else {
            //log_message('error',$response_curl);
            //redirect to failure method
            $this->response(array('status' => 'error', 'result' => 'Method not allowed'), 200);
            // header("Location: /payment/failure?title=POST FAILURE");
            // exit();
        }
    }

    public function export_final_report_post()
    {
        $this->load->database();

        // Set view and target table
        $view_name = 'all_lead_completed_report'; // Replace with your view
        $new_table = 'exported_final_report_data'; // Table you want to create

        // Optional: Drop the table if already exists
        $this->db->query("DROP TABLE IF EXISTS `$new_table`");

        // Create table from view
        $query = "CREATE TABLE `$new_table` AS SELECT * FROM `$view_name`";

        if ($this->db->query($query)) {
            echo "Created SuccessFully";
        } else {
            echo "Failed to create table.";
        }
    }

    public function export_sublead_qualified_report_post()
    {
        $this->load->database();

        // Set view and target table
        $view_name = 'sub_lead_qualified'; // Replace with your view
        $new_table = 'exported_sublead_qualified_report_data'; // Table you want to create

        // Optional: Drop the table if already exists
        $this->db->query("DROP TABLE IF EXISTS `$new_table`");

        // Create table from view
        $query = "CREATE TABLE `$new_table` AS SELECT * FROM `$view_name`";

        if ($this->db->query($query)) {
            echo "Created SuccessFully";
        } else {
            echo "Failed to create table.";
        }
    }

    public function fetch_bizid_userdetails_get()
    {
        $biz_id = $this->get('biz_id');
        if ($biz_id == '') {
            $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
        } else {
            $lead_created_by = $this->mcommon->specific_row_value('biz_leads', array('biz_lead_id' => $biz_id), 'lead_created_by');
            $user_details = $this->mcommon->specific_row('users', array("user_id" => $lead_created_by));
            if (!empty($user_details)) {
                $result['name'] = $user_details['first_name']. ' '. $user_details['last_name'];
                $result['mobile'] = $user_details['country_code'].' '.$user_details['mobile'];
                $result['email'] = $user_details['email'];
                $result['employee_id'] = $user_details['employee_id'];
                $result['user_id'] = $user_details['user_id'];
                $this->response($result, 200);
            } else {
                $this->response(["message" => 'No data found', "status" => false], 404);
            }
        }
    }

    public function fetch_mercatobizid_userdetails_get()
    {
        $biz_id = $this->get('biz_id');
        if ($biz_id == '') {
            $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
        } else {
            $lead_created_by = $this->mcommon->specific_row_value('mercatobiz_leads', array('biz_lead_id' => $biz_id), 'lead_created_by');
            $user_details = $this->mcommon->specific_row('users', array("user_id" => $lead_created_by));
            if (!empty($user_details)) {
                $result['name'] = $user_details['first_name']. ' '. $user_details['last_name'];
                $result['mobile'] = $user_details['country_code'].' '.$user_details['mobile'];
                $result['email'] = $user_details['email'];
                $result['employee_id'] = $user_details['employee_id'];
                $result['user_id'] = $user_details['user_id'];
                $this->response($result, 200);
            } else {
                $this->response(["message" => 'No data found', "status" => false], 404);
            }
        }
    }

    //DLD Project
    public function ontimegov_dld_post()
    {
        try {
            $user = json_decode($this->post("user"));
            $document = json_decode($this->post("document"));

            if ($document == '' || $user == '') {
                $this->response(["message" => 'Parameters Missing or Bad request', "status" => false], 400);
            }

            $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
            $random_string = substr(str_shuffle($str_result), 0, 10);

            $branch_id = 113;
            $lead_type = 'normal';
            $lead_by_pos_user = '2017969016'; // dldweb@ontimegroup.com
            $lead_by_post_user_name = 'DLD Web';   

            //check category exist
            $branch_exist = $this->mcommon->specific_record_counts('ontime_branches', array('branch_code' => $branch_id));
            if ($branch_exist == 0) {
                $this->response(array("Branch doesn't exist. Update branch first to create the lead"), 404);
            }

            $category_id = 109;
            $service_id = 1009;

            //check category exist
            $cateogry_exist = $this->mcommon->specific_record_counts('ontime_categories', array('category_id' => $category_id));
            if ($cateogry_exist == 0) {
                $this->response("Category doesn't exist. Update category first to create the lead", 404);
            }

            $service_exist = $this->mcommon->specific_record_counts('ontime_category_services_', array('service_id' => $service_id, 'category_id' => $category_id));
            if ($service_exist == 0) {
                $this->response("Service is not mapped to the category or  doesn't exist. Update service first to create the lead", 404);
            }

            $lead_name = $user->customer_name;
            $lead_contact = ($user->mobile != NULL && trim($user->mobile) != '') ? $user->mobile : "520000000";
            $lead_email = trim($user->email);
            $lead_countrycode = ($user->countrycode != NULL && trim($user->countrycode) != '') ? $user->countrycode : "+971";
            $lead_remarks = "<br><b><u>DLD - Website Lead</u></b>,<br>";

            $customer_mail_package_name = '';

            // if($user->corporate_checked == true || $user->corporate_user == false){
            //     $lead_remarks .= "Main Service Category:&nbsp;<b>" . $user->company_name . "</b>,</br>";
            //     $lead_remarks .= "Sub Category:&nbsp;<b>" . $user->service_name . "</b>,</br>";
            // }

            if($user->company_name != "" && $user->company_name != NULL){
                $lead_remarks .= "Main Service Category:&nbsp;<b>" . $user->company_name . "</b>,</br>";
            }
            if($user->service_name != "" && $user->service_name != NULL){
                $lead_remarks .= "Sub Category:&nbsp;<b>" . $user->service_name . "</b>,</br>";
            }

            if($user->corporate_user == 'true' || $user->corporate_user == true){
                $lead_remarks .= "Corporate User:&nbsp;<b>Yes</b>,</br>";
            }
            if($user->applicant_name != "" && $user->applicant_name != NULL){
                $lead_remarks .= "Applicant Name:&nbsp;<b>" . $user->applicant_name . "</b>,</br>";
            }
            $lead_remarks .= "Name:&nbsp;<b>" . $user->customer_name . "</b>,</br>";

            if ($user->email != "" && $user->email != NULL)
                $lead_remarks .= "Email:&nbsp;<b>" . $user->email . "</b>,</br>";
            if ($user->mobile != "" && $user->mobile != NULL)
                $lead_remarks .= "Mobile number:&nbsp;<b>" . $user->countrycode . '' . $user->mobile . "</b>,</br>";

            $customer_mail_package_name = $user->service_name;

            $lead_remarks .= "<br><b><u>Documents</u></b>,<br>";      

            if (!empty($document)) {
                foreach ($document as $doc) {
                    $lead_remarks .= $doc->attachment_name . " : <a href='" . $doc->attachment_url . "' target='_blank'> Click here to Download </a><br>";
                }
            }

            $random_email_name = strtolower($random_string);
            $random_email = $random_email_name . '@ontimecustomer.com';
            $lead_email = ($lead_email == '') ? $random_email : $lead_email;
            //create or get customer
            //$user_id = $this->customer_handle($lead_name,$lead_contact,$lead_email);
            $user_id = 0;
            $check_mobile_exists = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact));
            $check_email_exists = $this->mcommon->specific_record_counts('lead_users', array('email' => $lead_email));

            $is_exist = $this->mcommon->specific_record_counts('lead_users', array('mobile' => $lead_contact, 'email' => trim($lead_email)));

            if ($is_exist != 0) {
                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => trim($lead_email)), 'user_id');
            }

            if ($is_exist == 0) {
                $password = 'Welcome@123';
                $confirm_password = 'Welcome@123';
                $auth_level = '1';
                $referal_code = $random_string;
                $user_hashed_password = $this->authentication->hash_passwd($password);
                $user_data = [
                    'auth_level' => $auth_level,
                    'mobile' => $lead_contact,
                    'referal_code' => $referal_code,
                    'first_name' => $lead_name,
                    'passwd' => $user_hashed_password,
                    'email' => trim($lead_email),
                    'confirm_password' => $user_hashed_password,
                ];
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['email_otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';
                $user_data['role_id'] = '4';
                $user_data['country'] = 'United Arab Emirates';
                $user_data['country_code'] = $lead_countrycode;    //'+971';
                $insert = $this->mcommon->common_insert("lead_users", $user_data);

                $user_id = $this->mcommon->specific_row_value('lead_users', array('mobile' => $lead_contact, 'email' => trim($lead_email)), 'user_id');
            }

            if ($user_id != 0) {
                if ($lead_type == 'normal') {
                    $normal_lead_count = 0;
                    $workflows = $this->leads_model->get_workflow_entries($service_id);
                    $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => 2017969016, 'is_primary_group_id' => 1), 'group_id');

                    if (!empty($workflows)) {
                        foreach ($workflows as $key => $value) {
                            $parent_service_id = $value['parent_service_id'];
                            $target_service_id = $value['target_service_id'];
                            $category_id = $value['category_id'];

                            $insert_lead_array = array(
                                'customer_id' => $user_id,
                                'branch_id' => $branch_id,
                                'category_id' => $category_id,
                                'service_id' => $target_service_id,
                                'lead_created_by' => $lead_by_pos_user, 
                                'lead_added_on' => date('Y-m-d H:i:s'),
                                'contactable_date' => date('Y-m-d H:i:s'),
                                'lead_status' => 301,
                                'package_id' => 0,
                                'order_receipt' => 0,
                                'remarks' => $lead_remarks,
                                'is_assigned' => 0,
                                'lead_by_pos_user' => $lead_by_pos_user,
                                'lead_by_post_user_name' => $lead_by_post_user_name,
                                'lead_package_name' => $customer_mail_package_name,
                                'lead_from' => 'DLD',
                                'otg_order_id' => $user->order_id,
                                'otg_paylater' => 1,
                                'created_group_id'  => $created_group_id,
                            );
                            if($user->corporate_user == 'true' || $user->corporate_user == true){
                                $insert_lead_array['is_corporate'] = 'Corporate';
                            }
                            if($user->applicant_name != "" && $user->applicant_name != NULL){
                                $insert_lead_array['applicant_name'] = $user->applicant_name;
                            }
                            $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            $normal_lead_count = 1;
                        }
                    } else {
                        // else create one lead for selected category & service
                        $insert_lead_array = array(
                            'customer_id' => $user_id,
                            'branch_id' => $branch_id,
                            'category_id' => $category_id,
                            'service_id' => $service_id,
                            'lead_created_by' => $lead_by_pos_user, 
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => 0,
                            'order_receipt' => 0,
                            'remarks' => $lead_remarks,
                            'is_assigned' => 0,
                            'lead_by_pos_user' => $lead_by_pos_user,
                            'lead_by_post_user_name' => $lead_by_post_user_name,
                            'lead_package_name' => $customer_mail_package_name,
                            'lead_from' => 'DLD',
                            'otg_order_id' => $user->order_id,
                            'otg_paylater' => 1,
                            'created_group_id' => $created_group_id
                        );
                        if($user->corporate_user == 'true' || $user->corporate_user == true){
                            $insert_lead_array['is_corporate'] = 'Corporate';
                        }
                        if($user->applicant_name != "" && $user->applicant_name != NULL){
                            $insert_lead_array['applicant_name'] = $user->applicant_name;
                        }
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        $normal_lead_count = 1;
                    }
                    $lead_id = $insert_lead_id;
                    if ($normal_lead_count > 0) {
                        $branch_name = $this->mcommon->specific_row_value('ontime_branches', array('branch_code' => $branch_id), 'branch_name');
                        //create action log
                        $log_insert_array = array('action_id' => 401, 'lead_id' => $insert_lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Lead has been created by <strong> API USER</strong> from <strong>' . $branch_name . '</strong>', 'action_by' => $lead_by_pos_user, 'status_id' => 301);   // 2017969016
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        // $this->response(["lead_id" => $lead_id, "message" => 'Lead has been assigned successfully!'], 200);
                        $this->response(["lead_id" => $lead_id, "message" => 'Lead has been created', "statuscode" => 200], 200);
                    } else {
                        $this->response(["message" => 'Unable to create leads at this moment.', "statuscode" => 500], 500);
                    }
                }
            } else {
                $this->response(["message" => 'Unable to create leads at this moment.Please try again.', "statuscode" => 500], 500);
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function dashboard_dld_post()
    {
        try {
            $lead_id = json_decode($this->post('lead_id'));
            $customer_data = $this->leads_model->get_customers_data($lead_id);
            $get_lead_det = $this->leads_model->leaddata_fromcustomer($customer_data);
            $lead_year = $this->leads_model->lead_count_year($customer_data);
            if ($get_lead_det) {
                $arr = [];
                $lead_timeline = [];
                foreach ($get_lead_det as $data) {
                    $lead_det = $this->leads_model->lead_details_dld($data);
                    $lead_timeline_det = $this->leads_model->lead_timeline($data);
                    array_push($arr, $lead_det);
                    array_push($lead_timeline, $lead_timeline_det);
                }
            }
            $invoice_leads = $this->leads_model->invoice_leads_count($customer_data);

            if ($arr) {
                $this->response([
                    "lead_data" => $arr,
                    "timeline_data" => $lead_timeline,
                    "invoice_count" => $invoice_leads,
                    "lead_year" => $lead_year,
                    "message" => 'Lead status fetched sucessfully'
                ], 200);
            } else {
                $this->response('Unable to fetch the leads at this moment.', 500);
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function invoicelead_dld_post()
    {
        try {
            $lead_id = json_decode($this->post('lead_id'));
            $lead_det = $this->leads_model->lead_inv_dld($lead_id);

            if ($lead_det) {
                $this->response(["lead_data" => $lead_det, "message" => 'Lead status fetched sucessfully'], 200);
            } else {
                $this->response('Unable to fetch the leads at this moment.', 500);
            }
        } catch (Exception $e) {
            $this->response(["status" => "exception", "message" => $e->getMessage()], 500);
        }
    }

    public function sublead_missing_creation_post(){
        $lead_id = $_POST['lead_id'];
        $lead_det = $this->leads_model->lead_details($_POST['lead_id']);
        // Automatically Convert to Invoice Process - START

        echo '<pre>';print_r($lead_det);
        exit;

        if($lead_det['branch_id'] == 106 ){
            // $is_additional_payment = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id, 'lead_id' => $lead_id), 'is_additional_payment');

            $sublead_count = $this->mcommon->specific_record_counts('leads', array('lead_parent_id' => $lead_id));
            if($sublead_count == 0){
                $insert_lead_ids= array();
                // create subleads
                $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name, ontime_category_services_.category_id")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();
                if(count($details) > 0 && !in_array($lead_det['package_id'], [199,204,205,258,259,260, 200,201,206,207,208,209,261,262,263, 222,223,224, 225,226,227, 228,229,230])) {
                    foreach($details as $detail){
                        $insert_lead_array = array(
                            'customer_id' => $lead_det["customer_id"],
                            'branch_id' => $lead_det["branch_id"],
                            'category_id' => $detail["category_id"],
                            'service_id' => $detail["service_id"],
                            'lead_created_by' => $lead_det["lead_created_by"],
                            'lead_added_on' => date('Y-m-d H:i:s'),
                            'contactable_date' => date('Y-m-d H:i:s'),
                            'lead_status' => 301,
                            'package_id' => $detail["package_id"],
                            'order_receipt' => 0,
                            'remarks' => $detail["service_name"],
                            'is_assigned' => 0,
                            'lead_parent_id' => $lead_det["id"],
                            "is_direct_invoice" => $detail["is_direct_invoice"],
                            "govt_fee" => $detail["govt_fee"],
                            "typing_fee" => $detail["typing_fee"],
                            "msd_key" => $detail["msd_key"],
                            "is_pos_typing_fee" => $detail["is_pos_typing_fee"],
                        );
                        $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                        $insert_lead_ids[] = $insert_lead_id;
                    }
                    echo '<pre>';print_r($insert_lead_ids);
                }
            } else {
                $this->response('subleads already created.', 500);
            }
            exit;
        }
    }
}
