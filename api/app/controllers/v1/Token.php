<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');

class Token extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function token_get()
    {
      $response_array = array('token_number'=>"1234",'branch_name'=>"ALBARAHA");
      // return token number in 200 OK response
      $this->response(array($response_array), 200);
    }

    public function generate_token_post()
    {
        $access_token_obj = $this->get_access_token();
        if (isset($_POST))
        {
            log_message('error',print_r($_POST,TRUE));
            if (isset($access_token_obj->access_token))
            {
                // generate API access token
                $access_token = $access_token_obj->access_token;

                // read POST params
                $mobile = $_POST['mobile'];
                $email = $_POST['email'];
                $name = $_POST['name'];
                $category = $_POST['category'];
                $sub_category = $_POST['sub_category'];
                $sub_category3 = $_POST['sub_category3'];

                $is_female = isset($_POST['is_female']) ? $_POST['is_female']:'';
                $is_pregnant = isset($_POST['is_pregnant']) ? $_POST['is_pregnant']:'';
                $lmp = isset($_POST['lmp']) ? $_POST['lmp']:'';
                $abortion_history = isset($_POST['abortion_history']) ? $_POST['abortion_history']:'';
                $taking_pills = isset($_POST['taking_pills']) ? $_POST['taking_pills']:'';
                $xray_agreed = isset($_POST['xray_agreed']) ? $_POST['xray_agreed']:'';
                $is_vip = isset($_POST['is_vip']) ? $_POST['is_vip']:'';
                $user_id = isset($_POST['user_id']) ? $_POST['user_id']:'';
                $order_id = isset($_POST['order_id']) ? $_POST['order_id']:'';
                $order_item_id = isset($_POST['order_item_id']) ? $_POST['order_item_id']:'';
                $mrn_number = isset($_POST['mrn_number']) ? $_POST['mrn_number']:'';
                $appointment_id = isset($_POST['appointment_id']) ? $_POST['appointment_id']:'';

                $booking_ref_no = 'B'.$order_id.'-'.$order_item_id;

                $post_string = array(
                    'BranchCode'=>config_item('branch_code'),
                    'LineId'=>config_item('line_id'),
                    'FirstName'=>$name,
                    'Mobile'=> "971".substr($mobile,-9),
                    'Email'=>$email,
                    'ClientType'=>$category,
                    'ClientCategory'=>$sub_category3,
                    'ApplicationType'=>$sub_category,
                    'BookingReference'=>$booking_ref_no,
                    'ApplicationNo'=>$mrn_number, 
                    'IsFemale'=>$is_female,
                    'IsPregnant'=>$is_pregnant,
                    'LastMenstrualPeriod'=>$lmp,
                    'IsAbortionHistory'=>$abortion_history,
                    'IsTakingPills'=>$taking_pills,
                    'IsXrayAgreed'=>$xray_agreed,
                    'IsVip'=>$is_vip);

                log_message('error',json_encode($post_string));
                log_message('error',config_item('crm_token_url'));
                log_message('error',$access_token);

                // generate CRM token
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => config_item('crm_token_url') ,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($post_string),
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $access_token,
                        'Content-Type: application/json'
                    ) ,
                ));
                $response = curl_exec($curl);
                curl_close($curl);

                // parse response
                $response_obj = json_decode($response);
                if (isset($response_obj->Content))
                {
                    // save token response in CRM db
                    $last_id = $this->save_response($response_obj, $user_id, $order_id, $order_item_id, $mrn_number, $appointment_id, config_item('branch_code') , config_item('line_id'));
                    //log error if db entry fails
                    if ($last_id <= 0)
                    {
                        error_log("Failed to create entry in CRM database");
                    }
                    $token_no = $response_obj->Content;
                    $response_array = array('token_number'=>$token_no);
                    // return token number in 200 OK response
                   return $this->response($response_array , 200);
                    //$this->response($response_obj->Content->TokenNo, 200);
                }
                else
                {
                    $error_array = array(
                        'status' => 'error',
                        'message' => 'Token number not set in response'
                    );
                    return $this->response(array(
                        $error_array
                    ) , 500);
                }
            }
            else
            {
                $error_array = array(
                    'status' => 'error',
                    'message' => 'Failed to generate access token'
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
                'message' => 'No post params'
            );
            $this->response(array(
                $error_array
            ) , 400);
        }
    }
    

    public function get_access_token()
    {
        // get API access token
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config_item('access_token_url') ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'Username=' . config_item('access_token_username').'&Password='.config_item('access_token_password').'&grant_type='.config_item('grant_type_password'),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ) ,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function save_response($response_obj, $user_id, $order_id, $order_item_id, $mrn_number, $appointment_id, $branch_code, $line_id)
    {

        //INSERT INTO `wq_tokens`(`id`, `user_id`, `order_id`, `order_item_id`, `mrn_number`, `appointment_id`, `branch_code`, `line_id`, `token_id`, `token_key`, `token_number`, `branch_name`, `date_issued`, `time_issued`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10],[value-11],[value-12],[value-13],[value-14])
        $id = $this
            ->mcommon
            ->common_insert('wq_tokens', array(
            'user_id' => $user_id,
            'order_id' => $order_id,
            'order_item_id' => $order_item_id,
            'mrn_number' => $mrn_number,
            'appointment_id' => $appointment_id,
            'branch_code' => $branch_code,
            'line_id' => $line_id,
            'token_id' => $response_obj
                ->Content->TokenId,
            'token_key' => $response_obj
                ->Content->TokenKey,
            'token_number' => $response_obj
                ->Content->TokenNo,
            'branch_name' => $response_obj
                ->Content->BranchName,
            'date_issued' => $response_obj
                ->Content->DateIssued,
            'time_issued' => $response_obj
                ->Content
                ->TimeIssued
        ));
        return $id;
    }

}

