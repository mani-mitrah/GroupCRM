<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Notification extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('authentication_model');
        $this->load->model('Notification_model', 'nm');
        header("Access-Control-Allow-Origin: *");
    }

    public function token_post()
    {
        $token = $this->post('token');
        $user_id = $this->post('user_id');

        if ($token == '' || $user_id == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response($error_array, 400);
        } else {
            $user_exists = $this->mcommon->specific_record_counts('users', array('user_id' => $user_id));
            if ($user_exists > 0) {
                $update_array = array('user_id' => $user_id, 'firebase_instance_id' => $token);
                $token_update = $this->mcommon->common_edit('users', $update_array, array('user_id' => $user_id));
                if ($token_update) {
                    $success_array = array('status' => 'success', 'message' => 'Token updated');
                    $this->response(array($success_array), 200);
                } else {
                    $error_array = array('status' => 'error', 'message' => 'Something went wrong. Please try again later');
                    $this->response(array($error_array), 500);
                }
            } else {
                $error_array = array('status' => 'error', 'message' => 'User doesn\'t exist');
                $this->response(array($error_array), 404);
            }
        }
    }

    public function notifications_update_get()
    {
        $notification_id = $this->get("notification_id");
        if ($notification_id) {
            $result = $this->nm->update_notifications($notification_id);
            if (!empty($result)) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Update Error');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function notifications_count_get()
    {
        $user_id = $this->get("user_id");
        if ($user_id) {
            $result = $this->nm->get_notifications_count($user_id);
            if (!empty($result)) {
                $this->response($result, 200);
            } else {
                $error_array = array('status' => 'error', 'message' => 'Update Error');
                $this->response(array($error_array), 400);
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameters Missing');
            $this->response(array($error_array), 400);
        }
    }

    public function notification_get()
    {
        $user_id = $this->get('user_id');
        if ($user_id != '') {
            $notification = $this->nm->notification_all($user_id);
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

}