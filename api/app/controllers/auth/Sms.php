<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Sms extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function sendOtp_get()
    {
        $user_id = $this->get('user_id');
        $mobile  = $this->get('mobile');
        $language=$this->get('language');
        //get mobile number
        $sent_otp = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'otp');
        $first_name=$this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'first_name');
        $last_name=$this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'last_name');
        $name=$first_name.' '.$last_name;
        if($language=='arabic'){
            $body_message = "مرحباً $name ،OTP الخاص بك لـ WEQAYATI هو.شكرًا لك على تسجيل حسابك في وقايتي (الخدمات الطبية الذكية). يرجى تنزيل تطبيقنا للحصول على تجربة أفضل [رابط]. للحصول على أي مساعدة ، اتصل بنا على xxxxx أو قم بزيارة موقعنا على الإنترنت www.Wiqayati.co";
        }else{
            $body_message = "Dear $name, your OTP for weqayati is $sent_otp.Thank you for registering your account with Wiqayati (Smart Medical Services).Please download our app for a better experience [LINK]. For any assistance call us at 600500007 or visit our website www.Wiqayati.com";
        }
       
        $encodemsg = urlencode($body_message);
        
        $username='ALPHAS';
        $apikey='2SQpniQWa8ZGG4meLjgJ';
        $senderid='ALPHAS';


        $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=$username&apikey=$apikey&mobile=$mobile&message=$encodemsg&senderid=$senderid&type=txt";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, 'text/plain;charset=UTF-8');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        $curl_scraped_page = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);

        if (isset($error_msg)) {
            $error_array = array('status' => 'error', 'message' => $error_msg);
            $this->response(array($error_array), 500);
        } else {
            $success_array = array('status' => 'success', 'message' => 'OTP sent successfully');
            $this->response(array($success_array), 200);
        }

    }

    public function sendOtp_order_get()
    {
        $order_id = $this->get('order_id');
        $company_id = $this->get('company_id');
        $company_name = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'company_name');
        $apikey = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'api_key');
        $senderid = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'sender_id');
        $username = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'sms_user');
        $customer_id = $this->mcommon->specific_row_value('orders', array('o_id' => $order_id), 'customer');
        $customer_name = $this->mcommon->specific_row_value('users', array('user_id' => $customer_id), 'first_name');
        $order_amount = $this->mcommon->specific_row_value('orders', array('o_id' => $order_id), 'order_amount');
        $delivery_charge = $this->mcommon->specific_row_value('orders', array('o_id' => $order_id), 'delivery_amount');
        $final_amount = $order_amount + $delivery_charge;
        $pay_mode = $this->mcommon->specific_row_value('payment_details', array('order_id' => $order_id), 'payment_mode');
        $mobile_no = $this->mcommon->specific_row_value('users', array('user_id' => $customer_id), 'mobile');
        if ($pay_mode == 1) {
            $body_message = "Hi $customer_name, your order # $order_id for the amount Rs. $final_amount using online payment has been placed sucessfully.";
        } else {
            $body_message = "Hi $customer_name, your order # $order_id for the amount Rs. $final_amount has been placed sucessfully. You can pay the amount using card/cash on the time of delivery";

        }
        $encodemsg = urlencode($body_message);

        $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=$username&apikey=$apikey&mobile=$mobile_no&message=$encodemsg&senderid=$senderid&type=txt";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curl_scraped_page = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);

        if (isset($error_msg)) {
            $error_array = array('status' => 'error', 'message' => $error_msg);
            $this->response(array($error_array), 500);
        } else {
            $rupees = "&#8377";
            $mobile_no = $this->mcommon->specific_row_value('em_companies', array('id' => $company_id), 'company_phone_number');
            $url = "http://reseller.alphasoftz.info/api/sendsms.php?user=$username&apikey=$apikey&mobile=$mobile_no&message=$encodemsg&senderid=$senderid&type=txt";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            curl_close($ch);

            $success_array = array('status' => 'success', 'message' => 'OTP sent successfully');
            $this->response(array($success_array), 200);
        }

    }

}