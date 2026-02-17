<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('authentication_model');
        $this->load->model('profile_model');
        $this->load->library('Push_notification');
    }

    public function register_post()
    {
        $first_name = $this->post('first_name');
        $last_name = $this->post('last_name');
        $email = $this->post('email');
        $username = $this->post('username');
        $password = $this->post('password');
        $mobile_number = $this->post('mobile_number');
        $company_id = $this->post('company_id');
        $auth_level = '1';
        $profile = $this->input->post('profile');
        $referal_code = $this->random_strings(10);
        //check empty
        $company = $this->mcommon->specific_row('em_companies', array('id' => $company_id));
        if ($first_name == '' || $last_name == '' || $email == '' || $mobile_number == '' || $company_id == '' || $username == '' || $password == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {
            $check_email_exisitance = $this->mcommon->specific_row_value('users', array('company_id' => $company_id, 'email' => $email), "email");
            if (!$check_email_exisitance) {

                $mobile = $this->mcommon->specific_row_value('users', array('company_id' => $company_id, 'mobile' => $mobile_number), 'mobile');
                if (!$mobile) {
                    $user_data = [
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'username' => $username,
                        'company_id' => $company_id,
                        'email' => $email,
                        'auth_level' => $auth_level,
                        'mobile' => $mobile_number,

                    ];

                    // Load resources
                    $this->load->helper('auth');
                    $this->load->model('authentication_model');
                    $this->load->model('validation_callables');
                    $this->load->library('form_validation');

                    $this->form_validation->set_data($user_data);

                    $validation_rules = [
                        [
                            'field' => 'email',
                            'label' => 'email',
                            'rules' => 'trim|required|valid_email',
                        ],
                        [
                            'field' => 'auth_level',
                            'label' => 'auth_level',
                            'rules' => 'required|integer|in_list[1,6,9]',
                        ],
                    ];

                    $this->form_validation->set_rules($validation_rules);

                    if ($this->form_validation->run()) {

                        // echo validation_errors();

                        if ($profile) {

                            if (!is_dir('../api/uploads/users/')) {
                                mkdir('../api/uploads/users/', 0777, true);
                            }
                            $upload_path = '../api/uploads/users/';
                            $time = time() . '.jpg';
                            $file_name = $upload_path;
                            $upload_path_table = base_url() . 'uploads/users/';
                            $file_name_table = $upload_path_table;

                            $image = base64_decode($profile);
                            $photo = imagecreatefromstring($image);
                            imagejpeg($photo, $file_name . $time, 100);
                            $profile_pic = $file_name_table . $time;
                        } else {
                            $profile_pic = "";
                        }

                        $user_data['passwd'] = $this->authentication->hash_passwd($password);
                        $user_data['user_id'] = $this->authentication_model->get_unused_id();
                        $user_data['created_at'] = date('Y-m-d H:i:s');
                        $user_data['profile_pic'] = $profile_pic;
                        $user_data['otp'] = rand(1000, 9000);
                        $user_data['banned'] = '0';
                        $user_data['referal_code'] = $referal_code;
                        // print_r($user_data);
                        // die();

                        $this->db->insert('users', $user_data);

                        if ($this->db->affected_rows() == 1) {
                            $user_table_data = $this->authentication_model->get_user_data($user_data['user_id']);

                            $user_array = array(
                                'basic' => $user_table_data,
                            );

                            //ENABLE THIS WHEN SMS GATEWAY IS READY
                            $url = base_url() . 'v1/sms/sendOtp?user_id=' . $user_data['user_id'] . '&company_id=' . $company_id;
                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $curl_scraped_page = curl_exec($ch);
                            if (curl_errno($ch)) {
                                $error_msg = curl_error($ch);
                            }
                            if (isset($error_msg)) {
                                print_r($error_msg);
                                die();
                            }
                            curl_close($ch);

                            $this->load->library('email');
                            $config['protocol'] = 'smtp';
                            $config['smtp_host'] = 'smtp-relay.sendinblue.com';
                            $config['smtp_port'] = '587';
                            $config['smtp_timeout'] = '7';
                            $config['smtp_user'] = 'down2earthche@gmail.com';
                            $config['smtp_pass'] = '9XtRgJG05AYwHqDP';
                            $config['charset'] = 'utf-8';
                            $config['newline'] = "\r\n";
                            $config['mailtype'] = 'html'; // or html
                            $config['validation'] = true; // bool whether to validate email or not

                            $this->email->initialize($config);
                            $user_data['username'] = $first_name;
                            $user_data['company_name'] = $company['company_name'];
                            $user_data['company_logo'] = $company['company_logo'];
                            $user_data['company_short_name'] = $company['company_short_name'];
                            $user_data['company_email'] = $company['company_email'];
                            $user_data['whats_app_number'] = $company['whats_app_number'];
                            $user_data['help_line_number'] = $company['help_line_number'];
                            $user_data['facebook_link'] = $company['facebook_link'];
                            $user_data['twitter_link'] = $company['twitter_link'];
                            $user_data['linkedin_link'] = $company['linkedin_link'];
                            $msg = $this->load->view('welcome_msg', $user_data, true);

                            $subject = "Welcome to" . ' ' . $company['company_short_name'];
                            $this->email->from($company['company_email'], $company['company_name']);
                            $this->email->to($email);
                            $this->email->subject($subject);
                            $this->email->message($msg);
                            $this->email->send();
                            $this->response(array($user_array), 200);
                        } else {
                            $error_array = array('status' => 'error', 'message' => 'Something went wrong. Please try again later');
                            $this->response(array($error_array), 500);
                        }
                    } else {
                        $this->response(array($this->form_validation->error_array()), 403);
                    }
                } else {
                    $error_array = array('status' => 'error', 'message' => 'Mobile Number Already Used');
                    $this->response(array($error_array), 400);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'Email Already exist');
                $this->response(array($error_array), 400);
            }

        }
    }

    public function password_post()
    {
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $user_id = $this->post('user_id');

        if ($password == '' || $confirm_password == '' || $user_id == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else if ($password != $confirm_password) {
            $error_array = array('status' => 'error', 'message' => 'Passwords do not match');
            $this->response(array($error_array), 403);
        } else {
            $user_hashed_password = $this->authentication->hash_passwd($password);
            $password_update = $this->mcommon->common_edit('users', array('passwd' => $user_hashed_password), array('user_id' => $user_id));

            if ($password_update) {
                //$this->_sendRegistrationEmail($user_id);
                $success_array = array('status' => 'success', 'message' => 'Password changed successfully');
                $this->response(array($success_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Something went wrong. Please try again later');
                $this->response(array($error_array), 500);
            }

        }

    }

    public function _sendRegistrationEmail($user_id)
    {
        $user_table_data = $this->authentication_model->get_user_data($user_id);
        if (!empty($user_table_data)) {
            $receiver_name = ucfirst($user_table_data['first_name'] . ' ' . $user_table_data['last_name']);
            $receiver_email = $user_table_data['email'];
            $this->load->library('email');
            $fromemail = "info@templesinsouthindia.com";
            $toemail = $receiver_email;
            $subject = "Welcome to Temples in South India";
            $data = array();
            $view_data['name'] = $receiver_name;
            $mesg = $this->load->view('mails/registration', $view_data, true);

            $config = array(
                'charset' => 'utf-8',
                'wordwrap' => true,
                'mailtype' => 'html',
            );

            $this->email->initialize($config);
            $this->email->to($toemail);
            $this->email->from($fromemail, "TISI");
            $this->email->subject($subject);
            $this->email->message($mesg);
            $mail = $this->email->send();
            return $mail;
        }
    }

    public function testEmail_get()
    {
        $user_id = $this->get('user_id');
        echo $this->_sendRegistrationEmail($user_id);
    }

    public function login_post()
    {
        $email = $this->post('email');
        $password = $this->post('password');
        $company_id = $this->post('company_id');
        if ($email == '' || $password == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {
            $requirement = '1';

            if ($auth_data = $this->authentication_model->get_auth_data($email, $company_id)) {

                if (!$this->_user_confirmed($auth_data, $requirement, $password)) {
                    $this->response(['status' => "Password or Email is wrong"], 404);
                } else {
                    $this->response($this->profile_model->basic($auth_data->user_id), REST_Controller::HTTP_OK);
                }
            } else {
                $this->response(['status' => "User Not Found"], 400);
            }

        }
    }

    protected function _user_confirmed($auth_data, $requirement, $passwd = false)
    {
        // Check if user is banned
        $is_banned = ($auth_data->banned === '1');

        // Is this a login attempt
        if ($passwd) {
            // Check if the posted password matches the one in the user record
            $wrong_password = (!$this->check_passwd($auth_data->passwd, $passwd));
        }

        // Else we are checking login status
        else {
            // Password check doesn't apply to a login status check
            $wrong_password = false;
        }

        // Check if the user has the appropriate user level
        $wrong_level = (is_int($requirement) && $auth_data->auth_level < $requirement);

        // Check if the user has the appropriate role
        $wrong_role = (is_array($requirement) && !in_array($this->roles[$auth_data->auth_level], $requirement));

        // If anything wrong
        if ($is_banned or $wrong_level or $wrong_role or $wrong_password) {
            return false;
        }

        return true;
    }

    public function check_passwd($hash, $password)
    {
        if (is_php('5.5') && password_verify($password, $hash)) {
            return true;
        } else if ($hash === crypt($password, $hash)) {
            return true;
        }

        return false;
    }

    public function check_in_post()
    {
        $user_id = $this->post("user_id");
        if ($user_id) {
            $insert_array = array(
                'user_id' => $user_id,
                'is_check' => 1,
                'created_time' => date("g:i A"),
                'created_date' => date('d-m-Y'),
            );
            $result = $this->mcommon->common_insert("check_in", $insert_array);
            if ($result) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Error Occured');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function is_checked_get()
    {

        $user_id = $this->get("user_id");
        $date = $this->get("date");
        if ($user_id && $date) {
            $result = $this->mcommon->specific_row('check_in', array('user_id' => $user_id, 'created_date' => $date, "is_check" => 1));
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

    public function resend_otp_post()
    {
        $user_id = $this->post('user_id');
        if (!empty($user_id)) {
            $company_id = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'company_id');
            $company_name = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'company_name');
            $apikey = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'api_key');
            $senderid = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'sender_id');
            $username = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'sms_user');
            $mobile = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'mobile');
            $otp = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'otp');
            if ($otp) {
                $body_message = "Hello, Your OTP for $company_name is $otp";
                $encodemsg = urlencode($body_message);

                $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=$username&apikey=$apikey&mobile=$mobile&message=$encodemsg&senderid=$senderid&type=txt";
                // print_r($apikey);
                // die();
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                $this->response([array('status' => "Succssfully")], REST_Controller::HTTP_OK);
            } else {
                $this->response([array('status' => "Failure")], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function forgotpass_post()
    {
        $CI = &get_instance();
        $email = $this->input->post("email");
        $company_id = $this->input->post("company_id");
        $user = $this->pm->get_user_by_email($email);
        $flag = $this->pm->check_email_existance_company($email, $company_id);
        if ($flag != 0) {
            //$_POST['pass']=$this->authentication->hash_passwd($this->input->post('password'));
            $password = $this->randomPassword();
            $encrypted_pass = $this->authentication->hash_passwd($password);
            // print_r($encrypted_pass);
            // die();
            $company = $this->mcommon->specific_row('em_companies', array('id' => $company_id));

            $this->load->library('email');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'smtp-relay.sendinblue.com';
            $config['smtp_port'] = '587';
            $config['smtp_timeout'] = '7';
            $config['smtp_user'] = 'down2earthche@gmail.com';
            $config['smtp_pass'] = '9XtRgJG05AYwHqDP';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['mailtype'] = 'html'; // or html
            $config['validation'] = true; // bool whether to validate email or not

            $this->email->initialize($config);

            $user_data['password'] = $password;
            $user_data['username'] = $user[0]->username;
            $user_data['company_name'] = $company['company_name'];
            $user_data['company_logo'] = $company['company_logo'];
            $user_data['company_short_name'] = $company['company_short_name'];
            $user_data['company_email'] = $company['company_email'];
            $user_data['whats_app_number'] = $company['whats_app_number'];
            $user_data['help_line_number'] = $company['help_line_number'];
            $user_data['facebook_link'] = $company['facebook_link'];
            $user_data['twitter_link'] = $company['twitter_link'];
            $user_data['linkedin_link'] = $company['linkedin_link'];
            $user_id = $user[0]->user_id;

            $msg = $this->load->view('email_forgot_pass', $user_data, true);
            $this->email->from($company['company_email'], $company['company_name']);
            $this->email->to($email);
            $this->email->subject('Password Recovery');
            $this->email->message($msg);

            //Notification Starts//
            if ($user_data['username']) {
                $username = $user_data['username'];
            } else {
                $username = $user_data['first_name'];
            }
            $body_message = "Dear $username,please check your email for password recovery";
            $push = array(
                'id' => $user_id,
                'body' => $body_message,
                'title' => "Password Recovery",
            );
            $push_result = $this->push_notification->send_push($push, $user_id);
            $notification = array(
                'user_id' => $user_id,
                'title' => "Password Recovery",
                'notification' => $body_message,
                'response' => $push_result,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->push_notification->submit_notification_result($notification);

            //Notification Ends//
            if ($this->email->send()) {
                $updated_pass = $this->pm->update_user_password($email, $encrypted_pass);
                if ($updated_pass) {

                    $this->response([array('status' => "Succssfully")], REST_Controller::HTTP_OK);
                } else {
                    $this->response([array('status' => "Failure")], REST_Controller::HTTP_NOT_FOUND);
                }
                $this->response([array('status' => "Succssfully")], REST_Controller::HTTP_OK);
            } else {
                echo $this->email->print_debugger();
            }

        } else {
            $this->set_response([array('status' => "Failure")], REST_Controller::HTTP_NOT_FOUND);
        }

    }

    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function verify_otp_get()
    {
        $user_id = $this->get('user_id');
        $votp = $this->get('otp');
        if ($user_id != '' && $votp != '') {
            $otp = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'otp');
            if ($otp == $votp) {
                $update_array = array(
                    'mobile_verified' => 1,
                );
                $password_update = $this->mcommon->common_edit('users', $update_array, array('user_id' => $user_id));
                $error_array = array('status' => 'Success', 'message' => 'OTP Verified');
                $this->response(array($error_array), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'OTP NOT VERIFIED');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function update_password_post()
    {
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $user_id = $this->post('user_id');

        //check empty
        if ($password == '' || $confirm_password == '' || $user_id == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {
            // Load resources
            $this->load->helper('auth');
            $this->load->model('authentication_model');
            $this->load->model('validation_callables');
            $this->load->library('form_validation');

            if ($password == $confirm_password) {
                $user = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'user_id');
                if ($user) {
                    $user_data['passwd'] = $this->authentication->hash_passwd($password);
                    $pass_update = $this->mcommon->common_edit('users', $user_data, array('user_id' => $user_id));
                    if ($pass_update) {
                        $user_table_data = $this->authentication_model->get_user_data($user_id);
                        $user_array = array(
                            'basic' => $user_table_data,
                        );
                        $this->response(array($user_array), 200);
                    } else {
                        $error_array = array('status' => 'error', 'message' => 'Password Not Updated');
                        $this->response(array($error_array), 500);
                    }
                } else {
                    $error_array = array('status' => 'error', 'message' => 'User Not Found');
                    $this->response(array($error_array), 500);
                }

            } else {
                $error_array = array('status' => 'error', 'message' => 'Password and Confirm Password Not Matched');
                $this->response(array($error_array), 500);
            }
        }
    }

    public function random_strings($length_of_string)
    {
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }

    public function helpline_get()
    {
        $company_id = $this->get('company_id');
        if ($company_id != '') {
            $helpline = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'help_line_number');
            if ($helpline) {
                $this->response($helpline, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Helpline not found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function notification_get()
    {
        $user_id = $this->get('user_id');
        if ($user_id != '') {
            $notification = $this->profile_model->get_notification($user_id);
            if ($notification) {
                $this->response($notification, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No notification Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function notification_count_get()
    {
        $user_id = $this->get('user_id');
        if ($user_id != '') {
            $notification = $this->mcommon->records_all('web_push_notification', array('user_id' => $user_id));
            if ($notification) {
                $this->response(count($notification), 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'No notification Found');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

}