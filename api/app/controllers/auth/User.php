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
        header("Access-Control-Allow-Origin: *");
    }

    public function get_user_get()
    {
        $user_id = $this->get('user_id');

        if ($user_id) {
            $user = $this->pm->profile1($user_id);
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
    public function send_otp_get()
    {
       $user_id = $this->get('customer_id');

       if (!empty($user_id)) {
        $mobile_number = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'mobile');
        $otp = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'otp');

        if ($otp) {
          $otp = "Your OTP for OnTimeGroup is " . ' ' . $otp;
          $encodemsg = urlencode($otp);

        //   $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=ALPHAS&apikey=2SQpniQWa8ZGG4meLjgJ&mobile=$mobile_number&message=$encodemsg&senderid=ALPHAS&type=txt";
            $url = "";
          $ch = curl_init($url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $curl_scraped_page = curl_exec($ch);
          curl_close($ch);

          $this->response([array('oup' => "Succssfully")], REST_Controller::HTTP_OK);
      } else {
        $this->response([array('status' => "Failure")], REST_Controller::HTTP_NOT_FOUND);
    }
} else {
    $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
    $this->response(array($error_array), 400);
}

}
public function email_verification_post()
{
    $CI = &get_instance();
    $email = $this->input->post("email");
    $user = $this->pm->get_user_by_email($email);
    if (!empty($email))
    {



     $username = $this->mcommon->specific_row_value('users', array('email' => $email), 'first_name');
     $otp = $this->mcommon->specific_row_value('users', array('email' => $email), 'otp');

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

            $user_data['otp'] = $otp;
            $user_data['first_name'] =  $username;
            $user_id = $user[0]->user_id;

            $msg = $this->load->view('email_forgot_pass', $user_data, true);

            // $this->email->from('farmvalliblr@gmail.com', 'ON Time ');
            $this->email->to($email);
            $this->email->subject('Password Recovery');
            $this->email->message($msg);
            $this->email->send();

            //Notification Starts//
            $username = $user_data['first_name'];
            $body_message = "Dear $username,please check your email for password recovery";

            $push = array(
                'id' => $user_id,
                'body' => $body_message,
                'title' => "Password Recovery",
            );
        } else 
        {
            $this->set_response([array('status' => "Failure")], REST_Controller::HTTP_NOT_FOUND);
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
                if (!is_dir('../attachments/user_profile/')) {
                    mkdir('../attachments/user_profile/', 0777, true);
                }
                $upload_path = '../attachments/user_profile/';
                $time = time() . '.jpg';
                $file_name = $upload_path;
                $url = explode('/api', base_url());
                array_pop($url);
                $url = implode('', $url);
                $upload_path_table = $url . '/attachments/user_profile/';
                $file_name_table = $upload_path_table;
                $image = base64_decode($_POST['profile_pic']);
                $photo = imagecreatefromstring($image);
                imagejpeg($photo, $file_name . $time, 100);
                $profile = $file_name_table . $time;
            } else {
                $profile = $this->mcommon->specific_row_value('users', array('user_id' => $_POST['user_id']), 'profile_pic');
            }

            $update_array = array(
                'email' => $_POST['email'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'profile_pic' => $profile,
                'username' => $_POST['username'],
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
        if ($flag != 0) 
        {
            $password = $this->randomPassword();
            $encrypted_pass = $this->authentication->hash_passwd($password);

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

            $user_data['otp'] = $password;
            $user_data['first_name'] = $user[0]->first_name;
            $user_id = $user[0]->user_id;

            $msg = $this->load->view('email_forgot_pass', $user_data, true);

            $this->email->from('farmvalliblr@gmail.com', 'Skywin');
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
                'created_date' => date('d-m-Y'),
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

    public function update_device_id_get()
    {
        $user_id = $this->get("user_id");
        $device_id = $this->get("device_id");
        if ($user_id && $device_id) {
            $user = $this->pm->check_user_existance($user_id);
            if ($user == 0) {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            } else {
                $result = $this->pm->update_device_id($user_id, $device_id);
                if ($result) {
                    $this->response($result, 200);
                } else {
                    $error_array = array('status' => 'error', 'message' => 'Update Error');
                    $this->response(array($error_array), 400);
                }
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function add_address_post()
    {
        $door_no = $this->post("door_no");
        $street_name = $this->post("street_name");
        $city = $this->post("city");
        $state = $this->post("state");
        $pincode = $this->post("pincode");
        $address_for = $this->post("address_for");
        $user_id = $this->post("user_id");
        if ($user_id != '' && $door_no != '' && $street_name != '' && $city != '' && $state != '' && $pincode != '' && $address_for != '') {
            $user = $this->pm->check_user_existance($user_id);
            $check_address = $this->pm->check_for_address($user_id, $door_no, $street_name);
            if ($user == 0) {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            } else if ($check_address != 0) {
                $error_array = array('status' => 'error', 'message' => 'User Address Already Added');
                $this->response(array($error_array), 400);
            } else {
                $insert_array = array(
                    'door_no' => $door_no,
                    'street_name' => $street_name,
                    'city' => $city,
                    'state' => $state,
                    'pincode' => $pincode,
                    'address_for' => $address_for,
                    'user_id' => $user_id,
                    'created_time' => date("g:i A"),
                    'created_date' => date('d-m-Y'),
                );
                $result = $this->mcommon->common_insert("user_address", $insert_array);
                if ($result) {
                    $this->response($result, 200);
                } else {
                    $error_array = array('status' => 'error', 'message' => 'Update Error');
                    $this->response(array($error_array), 400);
                }
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function get_address_get()
    {
        $user_id = $this->get("user_id");
        if ($user_id) {
            $user = $this->pm->check_user_existance($user_id);
            if ($user == 0) {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            } else {
                $address = $this->mcommon->records_all('user_address', array('user_id' => $user_id));
                if ($address) {
                    $this->response($address, 200);
                } else {
                    $error_array = array('status' => 'error', 'message' => 'No Address Found');
                    $this->response(array($error_array), 400);
                }
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function delete_address_get()
    {
        $user_id = $this->get("user_id");
        $address_id = $this->get("address_id");
        if ($user_id && $address_id) {
            $user = $this->pm->check_user_existance($user_id);
            if ($user == 0) {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            } else {
                $delete = $this->mcommon->common_delete('user_address', array('id' => $address_id));
                if ($delete) {
                    $this->response("Successfully Deleted", 200);
                } else {
                    $error_array = array('status' => 'error', 'message' => 'No Address Found');
                    $this->response(array($error_array), 400);
                }
            }

        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

}