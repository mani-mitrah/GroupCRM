<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
Header('Access-Control-Allow-Origin: *');
Header('Access-Control-Allow-Headers: *');
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this
            ->load
            ->model('authentication_model');
        $this
            ->load
            ->model('profile_model');
    }



    public function checkemail_get()
    {
        $email = $this->get('email');
        $check_email = $this
            ->mcommon
            ->specific_record_counts('users', array(
            'email' => $email
        ));
        return $this->response($check_email, 200);
    }

    public function checkmobile_get()
    {
        $mobile = $this->get('mobile');
        $check_mobile = $this
            ->mcommon
            ->specific_record_counts('users', array(
            'mobile' => $mobile
        ));
        return $this->response($check_mobile, 200);
    }

    public function get_user_id_by_mobile_get()
    {
        $mobile = $this->get('mobile');
        $user_id=0;
        $check_mobile = $this
            ->mcommon
            ->specific_record_counts('users', array(
            'mobile' => $mobile
        ));
        if($check_mobile > 0)
        {
            $user_id = $this->mcommon->specific_row_value('users',array(
            'mobile' => $mobile),'user_id');
        }
        return $this->response($user_id, 200);
    }

    public function get_user_id_by_email_get()
    {
        $email = $this->get('email');
        $check_email = $this
            ->mcommon
            ->specific_record_counts('users', array(
            'email' => $email
        ));
        if($check_email > 0)
        {
            $user_id = $this->mcommon->specific_row_value('users',array(
            'email' => $email),'user_id');
        }
        return $this->response($user_id, 200);
    }

    public function validate_password_get()
    {
        $this
            ->load
            ->model('auth/validation_callables');
        $this
            ->load
            ->library('form_validation');
        $password = $this->get('password');
        $check_password = $this
            ->validation_callables
            ->_check_password_strength($password);
        return $this->response($check_password, 200);
    }

    public function register_post()
    {
        $language = $this->post('session');
        $first_name = $this->post('first_name');
        //$last_name = $this->post('last_name');
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $mobile_number = $this->post('mobile_number');
        $email_id = $this->post('email');
        $auth_level = '1';
        $referal_code = $this->random_strings(10);
        //check empty
        if ($mobile_number == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else
        {

            if ($password != $confirm_password)
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Passwords do not match'
                );
                $this->response(array(
                    $error_array
                ) , 403);
            }

            $user_hashed_password = $this
                ->authentication
                ->hash_passwd($password);

            $user_data = ['auth_level' => $auth_level, 'mobile' => $mobile_number, 'referal_code' => $referal_code, 'first_name' => $first_name,
            //'last_name' => $last_name,
            'passwd' => $user_hashed_password, 'email' => $email_id, 'confirm_password' => $user_hashed_password,

            ];

            // Load resources
            $this
                ->load
                ->helper('auth');
            $this
                ->load
                ->model('authentication_model');
            $this
                ->load
                ->model('validation_callables');
            $this
                ->load
                ->library('form_validation');

            $this
                ->form_validation
                ->set_data($user_data);
            $check = $this
                ->mcommon
                ->specific_row_value('users', array(
                'mobile' => $mobile_number
            ) , 'mobile');
            // print_r($check);
            // die();
            $check_email = $this
                ->mcommon
                ->specific_row_value('users', array(
                'email' => $email_id
            ) , 'email');

            if ($check_email == '')
            {

                if ($check == '')
                {
                    $user_data['user_id'] = $this
                        ->authentication_model
                        ->get_unused_id();
                    $user_data['created_at'] = date('Y-m-d H:i:s');
                    $user_data['otp'] = rand(1000, 9000);
                    $user_data['email_otp'] = rand(1000, 9000);
                    $user_data['banned'] = '0';
                    $user_data['role_id'] = '4';
                    $user_data['country'] = 'United Arab Emirates';
                    $user_data['country_code'] = '+971';

                    $result = $this
                        ->mcommon
                        ->common_insert("users", $user_data);

                    if ($result == 0)
                    {
                        //ENABLE THIS WHEN SMS GATEWAY IS READY
                        /*$url = base_url() . 'v1/sms/sendOtp?user_id=' . $user_data['user_id'] . '&mobile=' . $mobile_number . '&language=' . $language;
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        if (curl_errno($ch))
                        {
                            echo "string";
                            $error_msg = curl_error($ch);
                            print_r($error_msg);
                        }
                        if (isset($error_msg))
                        {
                            print_r($error_msg);
                            die();
                        }
                        curl_close($ch);*/
                        //SMS ENDS//
                        

                        $receiver_name = $first_name;
                        $receiver_email = $email_id;

                        $message = "Dear ".$receiver_name.",<br /><br /> Thank you for registering with Weqayati Smart Medical Health Center. Your account details as follow as:<br />";
                        $message_details = "<table cellpadding='10' width='100%' cellspacing='0' border='0'>";
                        $message_details .= "<tbody>";
                        $message_details .= "<tr><td><strong>Email Address</strong></td><td>".$email_id."</td></tr>";
                        $message_details .= "<tr><td><strong>Password</strong></td><td>".$password."</td></tr>";
                        $message_details .= "<tr><td><strong>Registered Mobile</strong></td><td>+971 ".$mobile_number."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For some financial transactions to verify your email we may ask you for EMAIL OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>Email OTP</strong></td><td>".$user_data['email_otp']."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For order confirmations sometimes we need to verify your mobile number with OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>MOBILE OTP</strong></td><td>".$user_data['otp']."</td></tr>";
                        $message_details .= "</tbody></table>";
                        $message_details .= "<br /><br />As each person’s requirement is different, our team of consultants is available round-the-clock ensuring to be your best and the most well-informed guide.<br /><br />";
                        $message .= $message_details;


                        //SEND EMAIL TO CUSTOMER
                        $email_array = array(
                            'email' => $receiver_email,
                            'subject' => 'Registration Successful - Baraha.ae',
                            'template' => 'mails/template',
                            'from_name' => 'BARAHA',
                            'message' => $message,
                        );
                        $send_mail = send_template_email($email_array);
                        log_message('error', $send_mail);

                        /*$this
                            ->load
                            ->library('email');

                        $config['protocol'] = 'smtp';
                        $config['smtp_host'] = 'smtp-relay.sendinblue.com';
                        $config['smtp_port'] = '587';
                        $config['smtp_timeout'] = '7';
                        $config['smtp_user'] = 'ricedall20@gmail.com';
                        $config['smtp_pass'] = 'C9I4PxYU2jE8Fmzf';
                        $config['charset'] = 'utf-8';
                        $config['newline'] = "\r\n";
                        $config['mailtype'] = 'html'; // or html
                        $config['validation'] = true; // bool whether to validate email or not
                        $fromemail = "team@baraha.ae";
                        $toemail = $receiver_email;
                        $subject = "Welcome to Wiqayati";
                        $data = array();
                        $view_data['name'] = $receiver_name;
                        $view_data['otp'] = $user_data['email_otp'];
                        $view_data['user_id'] = $user_data['user_id'];
                        $view_data['language'] = $language;
                        $mesg = $this
                            ->load
                            ->view('mails/registration', $view_data, true);

                        $this
                            ->email
                            ->initialize($config);
                        $this
                            ->email
                            ->to($toemail);
                        $this
                            ->email
                            ->from($fromemail, "WIQAYATI");
                        $this
                            ->email
                            ->subject($subject);
                        $this
                            ->email
                            ->message($mesg);
                        $mail = $this
                            ->email
                            ->send();
                        if (!$mail)
                        {
                            $mail_error = $this
                                ->email
                                ->print_debugger();
                            // print_r($mail_error);
                            // die();
                            
                        }*/

                        //  print_r($curl_scraped_page);
                        //  die();
                        $error_array = array(
                            'status' => 'success',
                            'message' => 'Register success'
                        );
                        $this->response(array(
                            $error_array
                        ) , 200);
                    }

                }
                else
                {
                    $error_array = array(
                        'status' => 'error',
                        'message' => 'Mobile Already Used'
                    );
                    $this->response(array(
                        $error_array
                    ) , 400);
                }
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Email Already Used'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }
        }
    }

    public function vipregister_post()
    {
        $language = $this->post('session');
        $first_name = $this->post('first_name');
        //$last_name = $this->post('last_name');
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $mobile_number = $this->post('mobile_number');
        $email_id = $this->post('email');
        $auth_level = '1';
        $referal_code = $this->random_strings(10);
        //check empty
        if ($mobile_number == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else
        {

            if ($password != $confirm_password)
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Passwords do not match'
                );
                $this->response(array(
                    $error_array
                ) , 403);
            }

            $user_hashed_password = $this
                ->authentication
                ->hash_passwd($password);

            $user_data = ['auth_level' => $auth_level, 'mobile' => $mobile_number, 'referal_code' => $referal_code, 'first_name' => $first_name,
            //'last_name' => $last_name,
            'passwd' => $user_hashed_password, 'email' => $email_id, 'confirm_password' => $user_hashed_password,

            ];

            // Load resources
            $this
                ->load
                ->helper('auth');
            $this
                ->load
                ->model('authentication_model');
            $this
                ->load
                ->model('validation_callables');
            $this
                ->load
                ->library('form_validation');

            $this
                ->form_validation
                ->set_data($user_data);
            // $check = $this
            //     ->mcommon
            //     ->specific_row_value('users', array(
            //     'mobile' => $mobile_number
            // ) , 'mobile');
            // print_r($check);
            // die();
            $check = '';
            $check_email = $this
                ->mcommon
                ->specific_row_value('users', array(
                'email' => $email_id
            ) , 'email');

            if ($check_email == '')
            {

                if ($check == '')
                {
                    $user_data['user_id'] = $this
                        ->authentication_model
                        ->get_unused_id();
                    $user_data['created_at'] = date('Y-m-d H:i:s');
                    $user_data['otp'] = rand(1000, 9000);
                    $user_data['email_otp'] = rand(1000, 9000);
                    $user_data['banned'] = '0';
                    $user_data['role_id'] = '4';
                    $user_data['country'] = 'United Arab Emirates';
                    $user_data['country_code'] = '+971';

                    $result = $this
                        ->mcommon
                        ->common_insert("users", $user_data);

                    if ($result == 0)
                    {
                        //ENABLE THIS WHEN SMS GATEWAY IS READY
                        /*$url = base_url() . 'v1/sms/sendOtp?user_id=' . $user_data['user_id'] . '&mobile=' . $mobile_number . '&language=' . $language;
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        if (curl_errno($ch))
                        {
                            echo "string";
                            $error_msg = curl_error($ch);
                            print_r($error_msg);
                        }
                        if (isset($error_msg))
                        {
                            print_r($error_msg);
                            die();
                        }
                        curl_close($ch);*/
                        //SMS ENDS//
                        

                        $receiver_name = $first_name;
                        $receiver_email = $email_id;

                        $message = "Dear ".$receiver_name.",<br /><br /> Thank you for registering with Weqayati Smart Medical Health Center. Your account details as follow as:<br />";
                        $message_details = "<table cellpadding='10' width='100%' cellspacing='0' border='0'>";
                        $message_details .= "<tbody>";
                        $message_details .= "<tr><td><strong>Email Address</strong></td><td>".$email_id."</td></tr>";
                        $message_details .= "<tr><td><strong>Password</strong></td><td>".$password."</td></tr>";
                        $message_details .= "<tr><td><strong>Registered Mobile</strong></td><td>+971 ".$mobile_number."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For some financial transactions to verify your email we may ask you for EMAIL OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>Email OTP</strong></td><td>".$user_data['email_otp']."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For order confirmations sometimes we need to verify your mobile number with OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>MOBILE OTP</strong></td><td>".$user_data['otp']."</td></tr>";
                        $message_details .= "</tbody></table>";
                        $message_details .= "<br /><br />As each person’s requirement is different, our team of consultants is available round-the-clock ensuring to be your best and the most well-informed guide.<br /><br />";
                        $message .= $message_details;


                        //SEND EMAIL TO CUSTOMER
                        $email_array = array(
                            'email' => $receiver_email,
                            'subject' => 'Registration Successful - Baraha.ae',
                            'template' => 'mails/template',
                            'from_name' => 'BARAHA',
                            'message' => $message,
                        );
                        $send_mail = send_template_email($email_array);
                        log_message('error', $send_mail);

                        /*$this
                            ->load
                            ->library('email');

                        $config['protocol'] = 'smtp';
                        $config['smtp_host'] = 'smtp-relay.sendinblue.com';
                        $config['smtp_port'] = '587';
                        $config['smtp_timeout'] = '7';
                        $config['smtp_user'] = 'ricedall20@gmail.com';
                        $config['smtp_pass'] = 'C9I4PxYU2jE8Fmzf';
                        $config['charset'] = 'utf-8';
                        $config['newline'] = "\r\n";
                        $config['mailtype'] = 'html'; // or html
                        $config['validation'] = true; // bool whether to validate email or not
                        $fromemail = "team@baraha.ae";
                        $toemail = $receiver_email;
                        $subject = "Welcome to Wiqayati";
                        $data = array();
                        $view_data['name'] = $receiver_name;
                        $view_data['otp'] = $user_data['email_otp'];
                        $view_data['user_id'] = $user_data['user_id'];
                        $view_data['language'] = $language;
                        $mesg = $this
                            ->load
                            ->view('mails/registration', $view_data, true);

                        $this
                            ->email
                            ->initialize($config);
                        $this
                            ->email
                            ->to($toemail);
                        $this
                            ->email
                            ->from($fromemail, "WIQAYATI");
                        $this
                            ->email
                            ->subject($subject);
                        $this
                            ->email
                            ->message($mesg);
                        $mail = $this
                            ->email
                            ->send();
                        if (!$mail)
                        {
                            $mail_error = $this
                                ->email
                                ->print_debugger();
                            // print_r($mail_error);
                            // die();
                            
                        }*/

                        //  print_r($curl_scraped_page);
                        //  die();
                        $error_array = array(
                            'status' => 'success',
                            'message' => 'Register success'
                        );
                        $this->response(array(
                            $error_array
                        ) , 200);
                    }

                }
                else
                {
                    $error_array = array(
                        'status' => 'error',
                        'message' => 'Mobile Already Used'
                    );
                    $this->response(array(
                        $error_array
                    ) , 400);
                }
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Email Already Used'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }
        }
    }

    public function password_post()
    {
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $user_id = $this->post('user_id');

        if ($password == '' || $confirm_password == '' || $user_id == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else if ($password != $confirm_password)
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Passwords do not match'
            );
            $this->response(array(
                $error_array
            ) , 403);
        }
        else
        {
            $user_hashed_password = $this
                ->authentication
                ->hash_passwd($password);
            $password_update = $this
                ->mcommon
                ->common_edit('users', array(
                'passwd' => $user_hashed_password
            ) , array(
                'user_id' => $user_id
            ));

            if ($password_update)
            {
                //$this->_sendRegistrationEmail($user_id);
                $success_array = array(
                    'status' => 'success',
                    'message' => 'Password changed successfully'
                );
                $this->response(array(
                    $success_array
                ) , 200);
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later'
                );
                $this->response(array(
                    $error_array
                ) , 500);
            }

        }

    }

    

    public function login_post()
    {

        $email = $this->post('email');
        $password = $this->post('password');

        if ($email == '' || $password == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else
        {
            $requirement = '1';
            $check_email = $this
                ->mcommon
                ->specific_row_value('users', array(
                'email' => $email
            ) , 'email');
            if ($check_email != '')
            {
                if ($auth_data = $this
                    ->authentication_model
                    ->get_auth_data($email))
                {
                    if (!$this->_user_confirmed($auth_data, $requirement, $password))
                    {
                        $this->response(['status' => "Password wrong"], 404);
                    }
                    else
                    {
                        $this->response($this
                            ->profile_model
                            ->basic($auth_data->user_id) , REST_Controller::HTTP_OK);
                    }
                }
                else
                {
                    $this->response(['status' => "Login Error"], 400);
                }
            }
            else
            {
                $this->response(['status' => "Email Not Found"], 400);
            }

        }

    }

    public function weblogin_post()
    {
        //400 parameters missing
        //200 success
        //404 account not found
        //204 password or email wrong

        $email = $this->post('email');
        $password = $this->post('password');

        if ($email == '' || $password == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else
        {
            $requirement = '1';
            $check_email = $this
                ->mcommon
                ->specific_row_value('users', array(
                'email' => $email
            ) , 'email');
            if ($check_email != '')
            {
                if ($auth_data = $this
                    ->authentication_model
                    ->get_auth_data($email))
                {
                    if (!$this->_user_confirmed($auth_data, $requirement, $password))
                    {
                        $this->response(['status' => "Password wrong"], 204);
                    }
                    else
                    {
                        $this->response($this
                            ->profile_model
                            ->basic($auth_data->user_id) , REST_Controller::HTTP_OK);
                    }
                }
                else
                {
                    $this->response(['status' => "Login Error"], 204);
                }
            }
            else
            {
                $this->response(['status' => "Email Not Found"], 404);
            }

        }

    }

    protected function _user_confirmed($auth_data, $requirement, $passwd = false)
    {

        $is_banned = ($auth_data->banned === '1');
        // Is this a login attempt
        if ($passwd)
        {
            // Check if the posted password matches the one in the user record
            $wrong_password = (!$this->check_passwd($auth_data->passwd, $passwd));
        }

        // Else we are checking login status
        else
        {
            // Password check doesn't apply to a login status check
            $wrong_password = false;
        }

        // Check if the user has the appropriate user level
        $wrong_level = (is_int($requirement) && $auth_data->auth_level < $requirement);

        // Check if the user has the appropriate role
        $wrong_role = (is_array($requirement) && !in_array($this->roles[$auth_data->auth_level], $requirement));

        // If anything wrong
        if ($is_banned or $wrong_level or $wrong_role or $wrong_password)
        {
            return false;
        }

        return true;
    }

    public function check_passwd($hash, $password)
    {
        if (is_php('5.5') && password_verify($password, $hash))
        {
            return true;
        }
        else if ($hash === crypt($password, $hash))
        {
            return true;
        }

        return false;
    }

    public function check_in_post()
    {
        $user_id = $this->post("user_id");
        if ($user_id)
        {
            $insert_array = array(
                'user_id' => $user_id,
                'is_check' => 1,
                'created_time' => date("g:i A") ,
                'created_date' => date('d-m-Y') ,
            );
            $result = $this
                ->mcommon
                ->common_insert("check_in", $insert_array);
            if ($result)
            {
                $this->response($result, 200);
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Error Occured'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }
        }
        else
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameter Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
    }

    public function is_checked_get()
    {

        $user_id = $this->get("user_id");
        $date = $this->get("date");
        if ($user_id && $date)
        {
            $result = $this
                ->mcommon
                ->specific_row('check_in', array(
                'user_id' => $user_id,
                'created_date' => $date,
                "is_check" => 1
            ));
            if ($result)
            {
                $this->response($result, 200);
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Not Yet Checked'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }
        }
        else
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameter Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
    }

    public function verify_otp_get()
    {
        $user_id = $this->get('user_id');
        $votp = $this->get('otp');
        if ($user_id != '' && $votp != '')
        {
            $otp = $this
                ->mcommon
                ->specific_row_value('users', array(
                'user_id' => $user_id
            ) , 'otp');
            if ($otp == $votp)
            {
                $update_array = array(
                    'otp_verified' => 1,
                );
                $password_update = $this
                    ->mcommon
                    ->common_edit('users', $update_array, array(
                    'user_id' => $user_id
                ));
                $error_array = array(
                    'status' => 'Success',
                    'message' => 'OTP Verified'
                );
                $this->response(array(
                    $error_array
                ) , 200);
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'OTP NOT VERIFIED'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }
        }
        else
        {

            $error_array = array(
                'status' => 'error',
                'message' => 'Parameter Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
    }

    public function update_user_mobile_post()
    {
        $user_id = $this->post('user_id');
        $mobile = $this->post('mobile');
        if ($user_id != '' && $mobile != '')
        {
            $update_array = array(
                'mobile' => $mobile,
            );
            $mobile_update = $this
                ->mcommon
                ->common_edit('users', $update_array, array(
                'user_id' => $user_id
            ));
            if ($mobile_update)
            {
                $error_array = array(
                    'status' => 'Success',
                    'message' => 'Mobile Updated'
                );
                $this->response(array(
                    $error_array
                ) , 200);
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'OTP NOT VERIFIED'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }

        }
        else
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameter Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
    }
    public function update_user_details_post()
    {
        $user_id = $this->post('user_id');
        $first_name = $this->post('first_name');
        $last_name = $this->post('last_name');
        $email = $this->post('email');
        $password = $this->post('password');
        $mobile = $this->post('mobile');
        if ($user_id != '' && $mobile != '' && $first_name != '' && $last_name != '' && $email != '' && $password != '')
        {
            $update_array = array(
                'mobile' => $mobile,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'passwd' => $password,
            );
            $mobile_update = $this
                ->mcommon
                ->common_edit('users', $update_array, array(
                'user_id' => $user_id
            ));
            if ($mobile_update)
            {
                $error_array = array(
                    'status' => 'Success',
                    'message' => 'Mobile Updated'
                );
                $this->response(array(
                    $error_array
                ) , 200);
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'OTP NOT VERIFIED'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }

        }
        else
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameter Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
    }
    public function resend_otp_post()
    {
        $user_id = $this->post('user_id');
        $otp = rand(1000, 9000);

        $data = array(
            'otp' => $otp,
        );

        $view_update_mobile = $this
            ->mcommon
            ->common_edit('users', $data, array(
            'user_id' => $user_id
        ));

        if ($view_update_mobile == 1)
        {

            $mobile_number = $this
                ->mcommon
                ->specific_row_value('users', array(
                'user_id' => $user_id
            ) , 'mobile');
            $otp = $this
                ->mcommon
                ->specific_row_value('users', array(
                'user_id' => $user_id
            ) , 'otp');

            if ($otp)
            {
                $otp = "Your OTP for OnTimeGroup is " . ' ' . $otp;
                $encodemsg = urlencode($otp);

                $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=ALPHAS&apikey=2SQpniQWa8ZGG4meLjgJ&mobile=$mobile_number&message=$encodemsg&senderid=ALPHAS&type=txt";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);

                $this->response([array(
                    'oup' => "Succssfully"
                ) ], REST_Controller::HTTP_OK);
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later'
                );
                $this->response(array(
                    $error_array
                ) , 500);
            }
        }
    }

    public function update_password_post()
    {
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $user_id = $this->post('user_id');

        //check empty
        if ($password == '' || $confirm_password == '' || $user_id == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else
        {
            // Load resources
            $this
                ->load
                ->helper('auth');
            $this
                ->load
                ->model('authentication_model');
            $this
                ->load
                ->model('validation_callables');
            $this
                ->load
                ->library('form_validation');

            if ($password == $confirm_password)
            {
                $user = $this
                    ->mcommon
                    ->specific_row_value('users', array(
                    'user_id' => $user_id
                ) , 'user_id');
                if ($user)
                {
                    $user_data['passwd'] = $this
                        ->authentication
                        ->hash_passwd($password);
                    $pass_update = $this
                        ->mcommon
                        ->common_edit('users', $user_data, array(
                        'user_id' => $user_id
                    ));
                    if ($pass_update)
                    {
                        $user_table_data = $this
                            ->authentication_model
                            ->get_user_data($user_id);
                        $user_array = array(
                            'basic' => $user_table_data,
                        );
                        $this->response(array(
                            $user_array
                        ) , 200);
                    }
                    else
                    {
                        $error_array = array(
                            'status' => 'error',
                            'message' => 'Password Not Updated'
                        );
                        $this->response(array(
                            $error_array
                        ) , 500);
                    }
                }
                else
                {
                    $error_array = array(
                        'status' => 'error',
                        'message' => 'User Not Found'
                    );
                    $this->response(array(
                        $error_array
                    ) , 500);
                }

            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Password and Confirm Password Not Matched'
                );
                $this->response(array(
                    $error_array
                ) , 500);
            }
        }
    }

    public function random_strings($length_of_string)
    {
        $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result) , 0, $length_of_string);
    }

    public function get_user_by_email_get()
    {
        $email = $this->get('email');
        if ($email != '')
        {
            $row = $this
                ->mcommon
                ->specific_row('users', array(
                'email' => $email
            ));
            $this->response($row, 200);
        }
        else
        {
            $this->response(array(
                $error_array
            ) , 400);
        }
    }

    public function get_user_by_id_get()
    {
        $user_id = $this->get('user_id');
        if ($user_id != '')
        {
            $row = $this
                ->mcommon
                ->specific_row('users', array(
                'user_id' => $user_id
            ));
            $this->response($row, 200);
        }
        else
        {
            $this->response(array(
                $error_array
            ) , 400);
        }
    }

    public function get_user_by_mobile_get()
    {
        $mobile = $this->get('mobile');
        if ($mobile != '')
        {
            $row = $this
                ->mcommon
                ->specific_row('users', array(
                'mobile' => $mobile
            ));
            $this->response($row, 200);
        }
        else
        {
            $this->response(array(
                $error_array
            ) , 400);
        }
    }

    public function autologin_post()
    {
        $user_id = $this->post('user_id');

        if ($user_id == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else
        {
            $requirement = '1';
            $check_email = $this
                ->mcommon
                ->specific_row_value('users', array(
                'user_id' => $user_id
            ) , 'email');
            if ($check_email != '')
            {
                if ($auth_data = $this
                    ->authentication_model
                    ->get_auth_data($check_email))
                {
                    if (!$this->_user_confirmed($auth_data, $requirement, $password))
                    {
                        $this->response(['status' => "Password wrong"], 204);
                    }
                    else
                    {
                        $this->response($this
                            ->profile_model
                            ->basic($auth_data->user_id) , REST_Controller::HTTP_OK);
                    }
                }
                else
                {
                    $this->response(['status' => "Login Error"], 204);
                }
            }
            else
            {
                $this->response(['status' => "Email Not Found"], 404);
            }

        }
    }


    public function ccregister_post()
    {
        $language = $this->post('session');
        $first_name = $this->post('first_name');
        //$last_name = $this->post('last_name');
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $mobile_number = $this->post('mobile_number');
        $email_id = $this->post('email');
        $auth_level = '1';
        $referal_code = $this->random_strings(10);
        //check empty
        if ($mobile_number == '')
        {
            $error_array = array(
                'status' => 'error',
                'message' => 'Parameters Missing'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
        else
        {

            if ($password != $confirm_password)
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Passwords do not match'
                );
                $this->response(array(
                    $error_array
                ) , 403);
            }

            $user_hashed_password = $this
                ->authentication
                ->hash_passwd($password);

            $user_data = ['auth_level' => $auth_level, 'mobile' => $mobile_number, 'referal_code' => $referal_code, 'first_name' => $first_name,
            //'last_name' => $last_name,
            'passwd' => $user_hashed_password, 'email' => $email_id, 'confirm_password' => $user_hashed_password,

            ];

            // Load resources
            $this
                ->load
                ->helper('auth');
            $this
                ->load
                ->model('authentication_model');
            $this
                ->load
                ->model('validation_callables');
            $this
                ->load
                ->library('form_validation');

            $this
                ->form_validation
                ->set_data($user_data);
            // $check = $this
            //     ->mcommon
            //     ->specific_row_value('users', array(
            //     'mobile' => $mobile_number
            // ) , 'mobile');
            $check = '';
            // print_r($check);
            // die();
            $check_email = $this
                ->mcommon
                ->specific_row_value('users', array(
                'email' => $email_id
            ) , 'email');

            if ($check_email == '')
            {

                if ($check == '')
                {
                    $user_data['user_id'] = $this
                        ->authentication_model
                        ->get_unused_id();
                    $user_data['created_at'] = date('Y-m-d H:i:s');
                    $user_data['otp'] = rand(1000, 9000);
                    $user_data['email_otp'] = rand(1000, 9000);
                    $user_data['banned'] = '0';
                    $user_data['role_id'] = '4';
                    $user_data['country'] = 'United Arab Emirates';
                    $user_data['country_code'] = '+971';

                    $result = $this
                        ->mcommon
                        ->common_insert("users", $user_data);

                    if ($result == 0)
                    {
                        //ENABLE THIS WHEN SMS GATEWAY IS READY
                        /*$url = base_url() . 'v1/sms/sendOtp?user_id=' . $user_data['user_id'] . '&mobile=' . $mobile_number . '&language=' . $language;
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        if (curl_errno($ch))
                        {
                            echo "string";
                            $error_msg = curl_error($ch);
                            print_r($error_msg);
                        }
                        if (isset($error_msg))
                        {
                            print_r($error_msg);
                            die();
                        }
                        curl_close($ch);*/
                        //SMS ENDS//
                        

                        $receiver_name = $first_name;
                        $receiver_email = $email_id;

                        $message = "Dear ".$receiver_name.",<br /><br /> Thank you for registering with Weqayati Smart Medical Health Center. Your account details as follow as:<br />";
                        $message_details = "<table cellpadding='10' width='100%' cellspacing='0' border='0'>";
                        $message_details .= "<tbody>";
                        $message_details .= "<tr><td><strong>Email Address</strong></td><td>".$email_id."</td></tr>";
                        $message_details .= "<tr><td><strong>Password</strong></td><td>".$password."</td></tr>";
                        $message_details .= "<tr><td><strong>Registered Mobile</strong></td><td>+971 ".$mobile_number."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For some financial transactions to verify your email we may ask you for EMAIL OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>Email OTP</strong></td><td>".$user_data['email_otp']."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For order confirmations sometimes we need to verify your mobile number with OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>MOBILE OTP</strong></td><td>".$user_data['otp']."</td></tr>";
                        $message_details .= "</tbody></table>";
                        $message_details .= "<br /><br />As each person’s requirement is different, our team of consultants is available round-the-clock ensuring to be your best and the most well-informed guide.<br /><br />";
                        $message .= $message_details;


                        //SEND EMAIL TO CUSTOMER
                        $email_array = array(
                            'email' => $receiver_email,
                            'subject' => 'Registration Successful - Baraha.ae',
                            'template' => 'mails/template',
                            'from_name' => 'BARAHA',
                            'message' => $message,
                        );
                        $send_mail = send_template_email($email_array);
                        log_message('error', $send_mail);

                        /*$this
                            ->load
                            ->library('email');

                        $config['protocol'] = 'smtp';
                        $config['smtp_host'] = 'smtp-relay.sendinblue.com';
                        $config['smtp_port'] = '587';
                        $config['smtp_timeout'] = '7';
                        $config['smtp_user'] = 'ricedall20@gmail.com';
                        $config['smtp_pass'] = 'C9I4PxYU2jE8Fmzf';
                        $config['charset'] = 'utf-8';
                        $config['newline'] = "\r\n";
                        $config['mailtype'] = 'html'; // or html
                        $config['validation'] = true; // bool whether to validate email or not
                        $fromemail = "team@baraha.ae";
                        $toemail = $receiver_email;
                        $subject = "Welcome to Wiqayati";
                        $data = array();
                        $view_data['name'] = $receiver_name;
                        $view_data['otp'] = $user_data['email_otp'];
                        $view_data['user_id'] = $user_data['user_id'];
                        $view_data['language'] = $language;
                        $mesg = $this
                            ->load
                            ->view('mails/registration', $view_data, true);

                        $this
                            ->email
                            ->initialize($config);
                        $this
                            ->email
                            ->to($toemail);
                        $this
                            ->email
                            ->from($fromemail, "WIQAYATI");
                        $this
                            ->email
                            ->subject($subject);
                        $this
                            ->email
                            ->message($mesg);
                        $mail = $this
                            ->email
                            ->send();
                        if (!$mail)
                        {
                            $mail_error = $this
                                ->email
                                ->print_debugger();
                            // print_r($mail_error);
                            // die();
                            
                        }*/

                        //  print_r($curl_scraped_page);
                        //  die();
                        
                        $this->response(array('user_id'=>$user_data['user_id'],'email'=>$receiver_email,'mobile'=>$mobile_number), 200);
                    }

                }
                else
                {
                    $error_array = array(
                        'status' => 'error',
                        'message' => 'Mobile Already Used'
                    );
                    $this->response(array(
                        $error_array
                    ) , 400);
                }
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Email Already Used'
                );
                $this->response(array(
                    $error_array
                ) , 400);
            }
        }
    }

}