<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: https://crm.ontimegroup.com/auth/api/v1/Auth/register');

class Auth extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('authentication_model');
        $this->load->model('profile_model');
    }

    public function register_post()
    {

        /// print_r("hai");exit('hhhhh');
        $first_name = $this->post('first_name');
        $last_name = $this->post('last_name');
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $mobile_number = $this->post('mobile_number');
        $email_id = $this->post('email');
        $auth_level = '1';
        $referal_code = $this->random_strings(10);
        //check empty
        if ($mobile_number == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {

            if ($password != $confirm_password) {
                $error_array = array('status' => 'error', 'message' => 'Passwords do not match');
                $this->response(array($error_array), 403);
            }

            $user_hashed_password = $this->authentication->hash_passwd($password);


            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $mobile_number,
                'referal_code' => $referal_code,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'passwd' => $user_hashed_password,
                'email' => $email_id,
                'confirm_password' => $user_hashed_password,

            ];
            // Load resources
            $this->load->helper('auth');
            $this->load->model('authentication_model');
            $this->load->model('validation_callables');
            $this->load->library('form_validation');

            $this->form_validation->set_data($user_data);
            $check = $this->mcommon->specific_row_value('users', array('mobile' => $mobile_number), 'mobile');

            if ($check == '') {
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';

                $result = $this->mcommon->common_insert("users", $user_data);

                if ($result == 0) {
                    $this->_sendRegistrationEmail($user_data['user_id']);
                    $error_array = array('status' => 'success', 'message' => 'Register success');
                    $this->response(array($error_array), 200);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'Mobile Already Used');
                $this->response(array($error_array), 400);
            }
        }
    }

    public function vipregister_post()
    {
        /// print_r("hai");exit('hhhhh');
        $first_name = $this->post('first_name');
        $last_name = $this->post('last_name');
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');
        $mobile_number = $this->post('mobile_number');
        $email_id = $this->post('email');
        $auth_level = '1';
        $referal_code = $this->random_strings(10);
        //check empty
        if ($mobile_number == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {

            if ($password != $confirm_password) {
                $error_array = array('status' => 'error', 'message' => 'Passwords do not match');
                $this->response(array($error_array), 403);
            }

            $user_hashed_password = $this->authentication->hash_passwd($password);


            $user_data = [
                'auth_level' => $auth_level,
                'mobile' => $mobile_number,
                'referal_code' => $referal_code,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'passwd' => $user_hashed_password,
                'email' => $email_id,
                'confirm_password' => $user_hashed_password,

            ];
            // Load resources
            $this->load->helper('auth');
            $this->load->model('authentication_model');
            $this->load->model('validation_callables');
            $this->load->library('form_validation');

            $this->form_validation->set_data($user_data);
            $check = '';
            // $check = $this->mcommon->specific_row_value('users', array('mobile' => $mobile_number), 'mobile');

            if ($check == '') {
                $user_data['user_id'] = $this->authentication_model->get_unused_id();
                $user_data['created_at'] = date('Y-m-d H:i:s');
                $user_data['otp'] = rand(1000, 9000);
                $user_data['banned'] = '0';

                $result = $this->mcommon->common_insert("users", $user_data);

                if ($result == 0) {
                    $this->_sendRegistrationEmail($user_data['user_id']);
                    $error_array = array('status' => 'success', 'message' => 'Register success');
                    $this->response(array($error_array), 200);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'Mobile Already Used');
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
        $this->load->library('email');

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

        $this->email->initialize($config);
        $user_table_data = $this->authentication_model->get_user_data($user_id);
        if (!empty($user_table_data)) {
            $receiver_name = ucfirst($user_table_data['first_name'] . ' ' . $user_table_data['last_name']);
            $receiver_email = $user_table_data['email'];
        }
        $view_data['name'] = $receiver_name;
        $view_data['user_id'] = $user_id;
        $msg = $this->load->view('registration', $view_data, true);

        $this->email->from('team@baraha.ae', 'Weqayati');
        $this->email->to("trichy@alphasoftz.com");
        $this->email->subject('Verification');
        $this->email->message($msg);
        $mail = $this->email->send();
        return $mail;
    }

    public function testEmail_get()
    {
        // print_r('gdiugidgdig');exit('gdtdit');
        $user_id = $this->get('user_id');
        echo $this->_sendRegistrationEmail($user_id);
    }

    public function login_post()
    {


        $email = $this->post('email');
        $password = $this->post('password');

        if ($email == '' || $password == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        } else {
            $requirement = '1';

            if ($auth_data = $this->authentication_model->get_auth_data($email)) {
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

    public function verify_otp_get()
    {
        $user_id = $this->get('user_id');
        $votp = $this->get('otp');
        if ($user_id != '' && $votp != '') {
            $otp = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'otp');
            if ($otp == $votp) {
                $update_array = array(
                    'otp_verified' => 1,
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

    public function update_user_mobile_post()
    {
        $user_id = $this->post('user_id');
        $mobile = $this->post('mobile');
        if ($user_id != '' && $mobile != '') {
            $update_array = array(
                'mobile' => $mobile,
            );
            $mobile_update = $this->mcommon->common_edit('users', $update_array, array('user_id' => $user_id));
            if ($mobile_update) {
                $error_array = array('status' => 'Success', 'message' => 'Mobile Updated');
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
    public function update_user_details_post()
    {
        $user_id = $this->post('user_id');
        $first_name = $this->post('first_name');
        $last_name = $this->post('last_name');
        $email = $this->post('email');
        $password = $this->post('password');
        $mobile = $this->post('mobile');
        if ($user_id != '' && $mobile != '' && $first_name != '' && $last_name != '' && $email != '' && $password != '') {
            $password = $this->authentication->hash_passwd($password);
            $update_array = array(
                'mobile' => $mobile,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'passwd' => $password,
            );
            $mobile_update = $this->mcommon->common_edit('users', $update_array, array('user_id' => $user_id));
            if ($mobile_update) {
                $error_array = array('status' => 'Success', 'message' => 'Users details Update');
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
    public function resend_otp_post()
    {
        $user_id = $this->post('user_id');
        $otp = rand(1000, 9000);

        $data = array(
            'otp' => $otp,
        );

        $view_update_mobile = $this->mcommon->common_edit('users', $data, array('user_id' => $user_id));

        if ($view_update_mobile == 1) {

            $mobile_number = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'mobile');
            $otp = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'otp');

            if ($otp) {
                $otp = "Your OTP for OnTimeGroup is " . ' ' . $otp;
                $encodemsg = urlencode($otp);

                $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=ALPHAS&apikey=2SQpniQWa8ZGG4meLjgJ&mobile=$mobile_number&message=$encodemsg&senderid=ALPHAS&type=txt";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);

                $this->response([array('status' => "Succssfully")], REST_Controller::HTTP_OK);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Something went wrong. Please try again later');
                $this->response(array($error_array), 500);
            }
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
}
