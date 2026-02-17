<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Referal_code extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model("Referal_model", "rm");
        header("Access-Control-Allow-Origin: *");
    }

    public function apply_referal_post()
    {
        $user_id = $this->post('user_id');
        $referal_code = $this->post('referal_code');
        if ($user_id == '' && $referal_code == '') {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        } else {
            $check_validity = $this->rm->check_applied_code($referal_code);
            if ($check_validity == 0) {
                $check_code = $this->rm->check_referal_code($referal_code);
                if ($check_code != 0) {
                    $insert_array = array(
                        'user_id' => $user_id,
                        'referal_code' => $referal_code,
                        'created_time' => date("g:i A"),
                        'created_date' => date('d-m-Y'),
                    );
                    $result = $this->rm->apply_referal_code($insert_array);
                    if ($result) {
                        $this->response("Code Applied", 200);
                    } else {
                        $error_array = array('status' => 'error', 'message' => 'Referal Code Not Applied');
                        $this->response(array($error_array), 400);
                    }
                } else {
                    $error_array = array('status' => 'error', 'message' => 'Referal Code Not Valid');
                    $this->response(array($error_array), 400);
                }

            } else {
                $error_array = array('status' => 'error', 'message' => 'Referal Code Already Applied');
                $this->response(array($error_array), 400);
            }

        }
    }

    public function get_referal_get()
    {
        $user_id = $this->get("user_id");
        if ($user_id) {
            $user = $this->rm->check_user_existance($user_id);
            if ($user == 0) {
                $error_array = array('status' => 'error', 'message' => 'User Not Found');
                $this->response(array($error_array), 400);
            } else {
                $code = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'referal_code');
                if ($code) {
                    $this->response($code, 200);
                } else {
                    $error_array = array('status' => 'error', 'message' => 'No Code Found');
                    $this->response(array($error_array), 400);
                }
            }
        } else {
            $error_array = array('status' => 'error', 'message' => 'Parameter Missing');
            $this->response(array($error_array), 400);
        }
    }

}