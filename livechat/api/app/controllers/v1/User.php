<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class User extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model("profile_model", "pm");
        $this->load->library('Push_notification');
    }

    public function get_user_get()
    {
        $user_id = $this->get('user_id');
        if ($user_id) {
            $user = $this->pm->basic($user_id);
            if (!empty($user)) {
                $this->response($user, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function update_user_post()
    {

        $user_id = $this->pm->check_user_existance($_POST['user_id']);
        if ($user_id == 0) {
            $error_array = array('status' => 'error', 'message' => 'User Not Found');
            $this->response(array($error_array), 400);
        } else {

            $profile = $this->input->post('profile_pic');

            if ($profile != "") {

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
                $profile_pic = $this->mcommon->specific_row_value('users', array('user_id' => $_POST['user_id']), 'profile_pic');
            }
            $update_array = array(
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'firebase_instance_id' => $_POST['device_id'],
                'mobile' => $_POST['mobile'],
                'profile_pic' => $profile_pic,
            );
            $result = $this->mcommon->common_edit('users', $update_array, array('user_id' => $_POST['user_id']));
            if (!empty($result)) {
                $this->response("Successfully updated", 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'User Not Updated');
                $this->response(array($error_array), 400);
            }
        }

    }

    public function forgotpass_post()
    {
        $CI = &get_instance();
        $email = $this->input->post("email");
        $user = $this->pm->get_user_by_email($email);
        $flag = $this->pm->check_email_existance($email);
        if ($flag != 0) {
            //$_POST['pass']=$this->authentication->hash_passwd($this->input->post('password'));
            $password = $this->randomPassword();
            $encrypted_pass = $this->authentication->hash_passwd($password);
            // print_r($encrypted_pass);
            // die();

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
            $user_id = $user[0]->user_id;

            $msg = $this->load->view('email_forgot_pass', $user_data, true);

            $this->email->from('down2earthche@gmail.com', 'Down2earth');
            $this->email->to($email);
            $this->email->subject('Password Recovery');
            $this->email->message($msg);

            //Notification Starts//
            $username = $user_data['username'];
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
                'created_date' => date('Y-m-d H:i:s'),
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

    public function dashboard_post()
    {
        $user_id = $this->post("user_id");
        if ($user_id) {
            $user = $this->pm->check_user_existance($_POST['user_id']);
            if ($user == 0) {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            } else {
                $result = $this->pm->get_dashboard($user_id);
                if ($result) {
                    $this->response($result, 200);
                } else {
                    $this->response($result, 400);
                }
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }

    }

    public function update_device_id_post()
    {
        $user_id = $this->post("user_id");
        $device_id = $this->post("device_id");
        if ($user_id && $device_id) {
            $user = $this->pm->check_user_existance($_POST['user_id']);
            if ($user == 0) {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            } else {
                $result = $this->pm->update_device_id($user_id, $device_id);
                if ($result) {
                    $this->response($result, 200);
                } else {
                    $error_array = array('status' => 'error', 'message' => 'Error');
                    $this->response(array($error_array), 400);
                }
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    function priya_get() {
        $this->response(array("user"=>"priya"), 200);
    }

}