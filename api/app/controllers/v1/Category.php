<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');

class Category extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('authentication_model');
        $this->load->model('profile_model', 'pm');
        header("Access-Control-Allow-Origin: *");
        $this->load->library('session');
        $this->load->model('user_model');
    }

    public function category_post()
    {
        $category_name = $this->post('category');
        $category_arabic = $this->post('category_arabic');


        //check empty
        if ($category_name == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {


            $user_data = [
                'category_name' => $category_name,
                'category_arabic' => $category_arabic,
                'is_active' => '1',

            ];
            // Load resources
            $this->load->helper('auth');
            $this->load->model('authentication_model');
            $this->load->model('validation_callables');
            $this->load->library('form_validation');

            $this->form_validation->set_data($user_data);
            $check = $this->mcommon->specific_row_value('m_category', array('category_name' => $category_name), 'category_name');

            if ($check == '') {

                $result = $this->mcommon->common_insert("m_category", $user_data);
                if ($result > 0) {

                    $error_array = array('status' => 'success', 'message' => 'Category added successfully');
                    $this->response(array($error_array), 200);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'Category  Already Used');
                $this->response(array($error_array), 400);
            }
        }
    }
    public function sub_category_post()
    {
        $category_id = $this->post('category_id');
        $sub_category_name = $this->post('sub_category_name');
        $sub_cat_arabic = $this->post('sub_cat_arabic');

        //check empty
        if ($sub_category_name == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {


            $user_data = [
                'main_category' => $category_id,
                'sub_category_name' => $sub_category_name,
                'sub_cat_arabic' => $sub_cat_arabic,

                'is_active' => '1',

            ];
            // Load resources
            $this->load->helper('auth');
            $this->load->model('authentication_model');
            $this->load->model('validation_callables');
            $this->load->library('form_validation');

            $this->form_validation->set_data($user_data);
            $check = $this->mcommon->specific_row_value('m_sub_category', array('sub_category_name' => $sub_category_name), 'sub_category_name');

            if ($check == '') {

                $result = $this->mcommon->common_insert("m_sub_category", $user_data);
                if ($result > 0) {

                    $error_array = array('status' => 'success', 'message' => 'Sub Category added successfully');
                    $this->response(array($error_array), 200);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'Sub category  Already Used');
                $this->response(array($error_array), 400);
            }
        }
    }
    public function sub_category_two_post()
    {
        $category_id = $this->post('category_id');
        $sub_category_id = $this->post('sub_category_id');
        $sub_categorytwo_name = $this->post('sub_categorytwo_name');
        $sub_categorytwo_name_arabic = $this->post('sub_categorytwo_name_arabic');

        //check empty
        if ($sub_categorytwo_name == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {


            $user_data = [
                'main_category' => $category_id,
                'sub_category' => $sub_category_id,
                'sub_categorytwo' => $sub_categorytwo_name,

                'sub_cattwo_arabic' => $sub_categorytwo_name_arabic,

                'is_active' => '1',

            ];
            // Load resources
            $this->load->helper('auth');
            $this->load->model('authentication_model');
            $this->load->model('validation_callables');
            $this->load->library('form_validation');

            $this->form_validation->set_data($user_data);
            $check = $this->mcommon->specific_row_value('m_sub_categorytwo', array('sub_categorytwo' => $sub_categorytwo_name), 'sub_categorytwo');

            if ($check == '') {

                $result = $this->mcommon->common_insert("m_sub_categorytwo", $user_data);
                if ($result > 0) {

                    $error_array = array('status' => 'success', 'message' => 'Sub Category Two added successfully');
                    $this->response(array($error_array), 200);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'Sub category Two Already Used');
                $this->response(array($error_array), 400);
            }
        }
    }
    public function service_hours_post()
    {
        $service_hour = $this->post('service_hours');

        //print_r($service_hours);exit();
        //check empty
        if ($service_hour == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {


            $user_data = [
                'service_hour' => $service_hour,
                'is_active' => '1',

            ];
            // Load resources
            $this->load->helper('auth');
            $this->load->model('authentication_model');
            $this->load->model('validation_callables');
            $this->load->library('form_validation');

            $this->form_validation->set_data($user_data);
            $check = $this->mcommon->specific_row_value('m_service_hours', array('service_hour' => $service_hour), 'service_hour');

            if ($check == '') {

                $result = $this->mcommon->common_insert("m_service_hours", $user_data);
                if ($result > 0) {

                    $error_array = array('status' => 'success', 'message' => 'service_hours added successfully');
                    $this->response(array($error_array), 200);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'service_hours  Already Used');
                $this->response(array($error_array), 400);
            }
        }
    }

    public function get_category_get()
    {
        $result = $this->mcommon->records_all('m_category', array('is_active' => 1));
        if ($result) {
            $this->response($result, 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Not Yet Checked');
            $this->response(array($error_array), 400);
        }
    }

    public function get_sub_category_get()
    {
        $category_id = $this->get("category_id");
        if ($category_id) {
            $result = $this->pm->get_sub_category($category_id);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Not Yet Checked');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function get_sub_category_two_get()
    {
        $category_id = $this->get("category_id");
        $sub_category = $this->get("sub_category");
        if ($category_id && $sub_category) {
            $result = $this->pm->get_sub_category_two($category_id, $sub_category);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No Result Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    //depricated
    public function old_get_service_amount_get()
    {
        $category_id = $this->get("category");
        $sub_category = $this->get("sub_category");
        $gender = $this->get("gender");
        $hours = $this->get("hours");

        if ($category_id && $sub_category && $gender && $hours) {
            $result = $this->pm->get_service_amount($category_id, $sub_category, $gender, $hours);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No Result Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function get_service_amount_get()
    {

        //category_id=1&sub_category_id=4&sub_sub_category_id=4&sub_sub_sub_category_id=1&gender=1&service_hours=1&mrf_no=0

        $mrf_no = $this->get('mrf_no');
        $category_id = $this->get('category_id');
        $sub_category_id = $this->get('sub_category_id');
        $sub_sub_category_id = $this->get('sub_sub_category_id');
        $sub_sub_sub_category_id = $this->get('sub_sub_sub_category_id');
        $service_hours = $this->get('service_hours');
        $gender = $this->get('gender');
        if ($mrf_no != '' && $category_id != '' && $sub_category_id != '' && $sub_sub_category_id != '' && $sub_sub_sub_category_id != '' && $service_hours != '' && $gender != '') {
            $amount = $this->mcommon->specific_row_value('wq_service_amount', array('category' => $category_id, 'sub_category' => $sub_category_id, 'sub_category_two' => $sub_sub_category_id, 'sub_category_three' => $sub_sub_sub_category_id, 'gender' => $gender, 'service_hours' => $service_hours, 'medical_ref' => $mrf_no), 'service_amount');
            $final_amount = ($amount != '') ? $amount : '0.00';
            $this->response(array(array('amount' => $final_amount)), 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing mrf_no: ' . $mrf_no . ' category_id: ' .  $category_id . ' sub_category_id: ' . $sub_category_id . ' sub_sub_category_id: ' . $sub_sub_category_id . ' sub_sub_sub_category_id: ' . $sub_sub_sub_category_id . ' service_hours: ' . $service_hours . ' gender: ' . $gender);
            $this->response(array($error_array), 400);
        }
    }


    //depricated
    public function get_medical_ref_amount_get()
    {
        $category_id = $this->get("category");
        $hours = $this->get("hours");

        if ($category_id && $hours) {
            $result = $this->pm->get_medical_amount($category_id, $hours);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No Result Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }



    public function get_service_hours_get()
    {
        $this->db->select('*');
        $this->db->from("m_service_hours");
        $this->db->where("is_active", 1);
        $query = $this->db->get();
        $result = $query->result();

        if ($result) {
            $this->response($result, 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Not Yet Checked');
            $this->response(array($error_array), 400);
        }
    }


    public function get_service_get()
    {

        $service_id = $this->get('service_id');

        if ($service_id == '') {

            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {

            $service = $this->pm->services($service_id);

            if (!empty($service)) {
                $this->response($service, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Service Not Found');
                $this->response(array($error_array), 400);
            }
        }
    }

    public function services_post()
    {
        $user_id = $this->post('user_id');
        $company = $this->post('company');
        $medical_examination_number = $this->post('medical_examination_number');
        $sub_category = $this->post('sub_category');
        $category = $this->post('category');
        $sub_category_two = $this->post("sub_category_two");
        $visa_type = $this->post('visa_type');
        $gender = $this->post('gender');
        $pregency = $this->post('pregency');
        $menstrual_period = $this->post('menstrual_period');
        $abortion = $this->post('abortion');
        $contraceptive_pills = $this->post('contraceptive_pills');
        $x_ray = $this->post('x_ray');
        $attachment_no = $this->post('attachment_no');
        $attachment = $this->post('attachment');
        $service_time = $this->post('service_time');
        $amount = $this->post('amount');

        if ($user_id) {
            $u = NULL;
        } else {
            $u = $this->session_generated();
        }

        if ($user_id && $u) {
            $update_array = array(
                'user_id' => $user_id,
                'session_id' => NULL,
            );
            $update = $this->pm->update_session_cart($update_array, $u);
        }

        // if($company){
        $insert_array = array(
            'user_id' => $user_id,
            'company' => $company,
            'medical_examination_number' => $medical_examination_number,
            'sub_category' => $sub_category,
            'category' => $category,
            'sub_category_two' => $sub_category_two,
            'visa_type' => $visa_type,
            'gender' => $gender,
            'pregency' => $pregency,
            'menstrual_period' => $menstrual_period,
            'abortion' => $abortion,
            'contraceptive_pills' => $contraceptive_pills,
            'x_ray' => $x_ray,
            'attachment_no' => $attachment_no,
            'service_time' => $service_time,
            'amount' => $amount,
            'session_id' => $u,
            'created_date' => date('d-m-Y'),
        );

        $result = $this->mcommon->common_insert("services_cart", $insert_array);
        $services = $this->mcommon->specific_row("services_cart", array('sid' => $result));
        if ($attachment != '') {
            if (!is_dir('../attachments/services/')) {
                mkdir('../attachments/services/', 0777, true);
            }
        }
        //for ($i = 0; $i < count($_FILES['file']['attachment']); $i++) {
        $upload_path = '../attachments/services/';
        $time = time() . '.jpg';
        $file_name = $upload_path;
        $url = explode('/api', base_url());
        array_pop($url);
        $url = implode('', $url);
        $upload_path_table = $url . '/attachments/services/';
        $file_name_table = $upload_path_table;
        $image = base64_decode($_POST['attachment']);
        $photo = imagecreatefromstring($image);
        imagejpeg($photo, $file_name . $time, 100);
        $profile = $file_name_table . $time;

        $service_attachment = array(
            'attachment' => $profile,
            'service_id' => $result,
            'attachment_date' => date('d-m-Y')
        );
        $attachment_insert = $this->mcommon->common_insert("service_attachment", $service_attachment);
        //}
        if ($result > 0) {
            $this->response($services, 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'Error Occured!');
            $this->response(array($error_array), 400);
        }


        // }else{
        //     $error_array = array('status' => 'error', 'message' => 'parameter Missing');
        //     $this->response(array($error_array), 400);
        // }


    }

    public function session_generated()
    {
        if ($_SESSION['user_code']) {
            return $_SESSION['user_code'];
        } else {
            $kode_spl = 'SESS';
            $tgl = strtotime(date('d-m-y'));
            $codes = $kode_spl . $tgl . rand(100, 1000);
            $this->session->set_userdata('user_code', $codes);
            return $codes;
        }
    }

    public function get_attachments_post()
    {
        $category = $this->post('category');
        $sub_category = $this->post('sub_category');
        $gender = $this->post('gender');
        if ($category && $sub_category && $gender) {
            $attachments = $this->pm->get_attachments($category, $sub_category, $gender);
            if (!empty($attachments)) {
                $this->response($attachments, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Service Not Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function get_user_cart_count_get()
    {
        $user_id = $this->get("user_id");
        if ($user_id) {
            $result = $this->pm->get_user_cart_count($user_id);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No Result Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }


    public function get_sess_cart_count_get()
    {
        $sess_id = $this->get("sess_id");
        if ($sess_id) {
            $result = $this->pm->get_sess_cart_count($sess_id);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No Result Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function get_user_service_cart_get()
    {
        $user_id = $this->get("user_id");
        if ($user_id) {
            $result = $this->pm->get_user_service_cart($user_id);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No Result Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function get_sess_service_cart_get()
    {
        $sess_id = $this->get("sess_id");
        if ($sess_id) {
            $result = $this->pm->get_sess_service_cart($sess_id);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No Result Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function payment_submit_post()
    {
        $PaymentPage = $this->post("PaymentPage");
        $TransactionID = $this->post("TransactionID");
        $Payer = $this->post("Payer");
        $UniqueID = $this->post("UniqueID");
        $userId = $this->post("userId");
        if ($PaymentPage && $TransactionID && $Payer && $UniqueID && $userId) {
            $insert_array = array(
                'payment_page' => $PaymentPage,
                'transaction_id' => $TransactionID,
                'payer' => $Payer,
                'unique_id' => $UniqueID,
                'user_id' => $userId,
                'created_date' => date('d-m-Y')
            );
            $result = $this->pm->payment_success($insert_array, $userId);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Insert Error');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function enquiry_post()
    {

        $name = $this->post('name');
        $phone = $this->post('phone');
        $email = $this->post('email');
        $enquiry = $this->post('enquiry');
        $subject = $this->post('subject');
        $website_id = $this->post('website_id');        //check empty
        // $time=$this->post('current_time');
        $meeting_date = $this->post('meeting_date');

        if ($enquiry == 3 || $meeting_date == '') {
            $meeting_date = date('Y-m-d');
        }

        if ($enquiry == 1) {
            $email_enquiry_type = "Enquiry";
        } else if ($enquiry == 2) {
            $email_enquiry_type = "Complaint";
        } else if ($enquiry == 3) {
            $email_enquiry_type = "Meeting Request";
        }

        if ($subject == NULL) {
            $subject = $email_enquiry_type . " Received";
        }

        $user_data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'enquiry' => $enquiry,
            'subject' => $subject,
            'website_id' => $website_id,
            'created_time' => date('H:i A'),
            'created_date' => date('Y-m-d H:i:s'),
            'source'      => "Web Site",
            'contactable_date' => date('Y-m-d H:i:s'),
            'enquiry_status' => 301,
            'meeting_proposed_time' => $meeting_date
        ];

        if ($name == '' || $phone == '' || $enquiry == '' || $subject == '' || $website_id == '' || $email == '') {
            $this->response(array("status" => "error", "result" => "All fields are required."), 400);
        }

        // Load resources
        $website_name = $this->mcommon->specific_row_value('ontime_websites', array('id' => $website_id), 'website_name');


        $results = $this->mcommon->common_insert("enquiry", $user_data);
        log_message('error', $this->db->last_query());
        if ($results > 0) {
            $log_insert_array = array('action_id' => 401, 'enquiry_id' => $results, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Enquiry has been created from <strong>' . $website_name . '</strong>', 'action_by' => 2251243126, 'status_id' => 301);
            $insert_log = $this->mcommon->common_insert('enquiry_action_log', $log_insert_array);
            //
            if (config_item('send_coordinator_emails') == 1) {
                //Send email to coordinators
                $coords = $this->user_model->website_coords($website_id);

                //SEND EMAIL TO COORDINATORS
                foreach ($coords as $key => $value) {

                    $receiver_name = $value['first_name'];
                    $receiver_email = $value['email'];

                    $message = "Dear " . $receiver_name . ",<br /><br /> We have received new <strong>" . $email_enquiry_type . "</strong> from <strong>" . $website_name . " Website</strong>.<br />Please login into ONTIME CRM and assign to the CSA for further processing.";

                    $message_details = '<br /><br /><a href="https://crm.ontimegroup.com/">Click here to login into ONTIME CRM</a><br /><br />';
                    $message .= $message_details;
                    $email_array = array(
                        'from_email' => 'crm@ontimegroup.com',
                        'email' => $value['email'],
                        'subject' => $website_name . ' - New ' . $email_enquiry_type,
                        'template' => 'mails/crm_template',
                        'from_name' => 'ONTIME CRM',
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                }
            }


            $this->response(array("status" => "success", "result" => "Enquiry has been created successfully."), 200);
        } else {
            $this->response(array("status" => "error", "result" => "Unable to create enquiry. Please contact support."), 500);
        }
    }


    public function attestenquiry_post()
    {
        $fname = $this->post("firstname");
        $lname = $this->post("lastname");
        $name = $fname . " " . $lname;
        $document = $this->post('document');
        $phone = $this->post('phone');
        $email = $this->post('email');
        $enquiry = 1;
        $subject = $this->post('subject');
        $website_id = 519;        //check empty
        $meeting_date = $this->post('meeting_date');

        if ($enquiry == 3 || $meeting_date == '') {
            $meeting_date = date('Y-m-d');
        }

        if ($enquiry == 1) {
            $email_enquiry_type = "Enquiry";
        } else if ($enquiry == 2) {
            $email_enquiry_type = "Complaint";
        } else if ($enquiry == 3) {
            $email_enquiry_type = "Meeting Request";
        }

        if ($subject == NULL) {
            $subject = $email_enquiry_type . " Received";
        }

        $user_data = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'enquiry' => $enquiry,
            'subject' => $subject,
            'website_id' => $website_id,
            'created_time' => date('H:i A'),
            'created_date' => date('Y-m-d H:i:s'),
            'source'      => "Web Site",
            'contactable_date' => date('Y-m-d H:i:s'),
            'enquiry_status' => 301,
            'meeting_proposed_time' => $meeting_date
        ];

        if ($name == '' || $phone == '' || $enquiry == '' || $subject == '' || $website_id == '' || $email == '') {
            $this->response(array("status" => "error", "result" => "All fields are required."), 400);
        }

        // Load resources
        $website_name = $this->mcommon->specific_row_value('ontime_websites', array('id' => $website_id), 'website_name');


        $results = $this->mcommon->common_insert("enquiry", $user_data);
        log_message('error', $this->db->last_query());
        if ($results > 0) {
            $log_insert_array = array('action_id' => 401, 'enquiry_id' => $results, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => 'Enquiry has been created from <strong>' . $website_name . '</strong>', 'action_by' => 2251243126, 'status_id' => 301);
            $insert_log = $this->mcommon->common_insert('enquiry_action_log', $log_insert_array);
            //
            if (config_item('send_coordinator_emails') == 1) {
                //Send email to coordinators
                $coords = $this->user_model->website_coords($website_id);

                //SEND EMAIL TO COORDINATORS
                foreach ($coords as $key => $value) {

                    $receiver_name = $value['first_name'];
                    $receiver_email = $value['email'];

                    $message = "Dear " . $receiver_name . ",<br /><br /> We have received new <strong>" . $email_enquiry_type . "</strong> from <strong>" . $website_name . " Website</strong>.<br />Please login into ONTIME CRM and assign to the CSA for further processing.";

                    $message_details = '<br /><br /><a href="https://crm.ontimegroup.com/">Click here to login into ONTIME CRM</a><br /><br />';
                    $message .= $message_details;
                    $email_array = array(
                        'from_email' => 'crm@ontimegroup.com',
                        'email' => $value['email'],
                        'subject' => $website_name . ' - New ' . $email_enquiry_type,
                        'template' => 'mails/crm_template',
                        'from_name' => 'ONTIME CRM',
                        'message' => $message,
                    );
                    $send_mail = send_template_email($email_array);
                }
            }


            $this->response(array("status" => "success", "result" => "Enquiry has been created successfully."), 200);
        } else {
            $this->response(array("status" => "error", "result" => "Unable to create enquiry. Please contact support."), 500);
        }
    }



    public function get_sub_category_three_get()
    {
        $category_id = $this->get('category_id');
        $sub_category_id = $this->get('sub_category_id');
        $sub_category_two_id = $this->get('sub_category_two_id');

        if ($category_id == "" || $sub_category_id == "" || $sub_category_two_id == "") {
            $error_array = array('status' => 'error', 'message' => 'Missing parameters');
            $this->response(array($error_array), 400);
        }

        $result = $this->pm->get_sub_category_three($category_id, $sub_category_id, $sub_category_two_id);

        if ($result) {
            $this->response($result, 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'No Result Found');
            $this->response(array($error_array), 204);
        }
    }

    public function get_service_id_post()
    {

        //CREATE API IN CRM TO GET THE SERVICE ID using (category, sub_category,sub_category_two,sub_category_three,gender,medical_ref,service_hours) -> get service_id
        $category_id = $this->post('category_id');
        $sub_category_id = $this->post('sub_category_id');
        $sub_category_two_id = $this->post('sub_category_two_id');
        $sub_category_three_id = $this->post('sub_category_three_id');
        $gender = $this->post('gender');
        $med_ref_no = $this->post('med_ref_no');
        $service_hours = $this->post('service_hours');

        if ($category_id == "" || $sub_category_id == "" || $sub_category_two_id == "" || $sub_category_three_id == "" || $gender == "" || $med_ref_no == "" || $service_hours == "") {
            $error_array = array('status' => 'error', 'message' => 'Missing parameters');
            $this->response(array($error_array), 400);
        }

        $result = $this->mcommon->specific_row(
            'wq_service_amount',
            array(
                'category' => $category_id,
                'sub_category' => $sub_category_id,
                'sub_category_two' => $sub_category_two_id,
                'sub_category_three' => $sub_category_three_id,
                'gender' => $gender,
                'medical_ref' => $med_ref_no,
                'service_hours' => $service_hours
            )
        );
        if ($result) {
            $this->response($result, 200);
        } else {
            $error_array = array('status' => 'error', 'message' => 'No Result Found');
            $this->response(array($error_array), 204);
        }
    }
}
