<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
        $this->load->helper('crypt');
        $this->load->helper('pos');
        $this->load->model('leads_model');
        $this->load->model('access_model');
        $this->load->model('app_model');
        $this->load->model('order_model');
    }

    public function network_authentication()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-gateway.ngenius-payments.com/identity/auth/access-token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/vnd.ni-identity.v1+json',
                'Host: api-gateway.ngenius-payments.com',
                'Content-Length: 0',
                'Authorization: Basic MmZjMzIzMTMtNTlmNy00NzFmLTk3MmQtMzU0YTYwNjEzYzc3OjhkOGQ5YzU2LWY5YTItNDA1MC1hNDE3LTBlYzRlZTAyYTlkNQ=='
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function getOutletRef($user_id)
    {
        try {

            $employee_id = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'employee_id');

            $curl = curl_init();

            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/getOnlineMerchantInfo/' . $employee_id,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                )
            );

            $auth = curl_exec($curl);

            curl_close($curl);

            $auth_obj = json_decode($auth);

            if ($auth_obj->Data->outletReference) {
                $outLet = $auth_obj->Data->outletReference;
                return $outLet;
            } else {
                // echo $auth;
                // exit();
                redirect("/payment/failure?title=Payment Not Initiated&desc=Insufficient access to generate payment link.");
            }
        } catch (Exception $e) {
            redirect("/payment/failure?title=Payment is not Initiated&desc=Please check with coordinator to get valid payment link.");
        }
    }

    public function pay($amount, $order_id, $customer_name, $email, $action_id)
    {
        $response_curlerror = "";
        $response_curl = "";

        $user_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'action_by');
        $status_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'status_id');
        // $role_id = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'role_id');
        $lead_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'lead_id');
        $branch_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'branch_id');

        $cc_company = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'cc_company_key');
        $cc_company_outletref = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'cc_company_outletref');
        if($cc_company != '' && $cc_company != NULL && $cc_company_outletref != '' && $cc_company_outletref != NULL){
            $outLet = $cc_company_outletref;
        } else if ($branch_id == 138) {
            $outLet = "35672f6e-a2a8-4a8a-a1fd-28b0565fe646";
        } else {
            $outLet = $this->getOutletRef($user_id);
        }

        $action_log = [
            "action_id" => 426,
            "lead_id" => $order_id,
            "action_amount" => $amount,
            "remarks" => "Customer Attempt to Pay",
            "action_by" => $user_id,
            "status_id" => $status_id
        ];

        $attempt_action_id = $this->mcommon->common_insert('lead_action_log', $action_log);

        $return_path = base_url() . "payment/payment_process?code=" . $order_id . "&order_id=" . encrypt_decrypt($amount) . "&act=" . $action_id . "&email=" . $email . "&attempt_action=" . $attempt_action_id;

        $query = "select ontime_branches.branch_name,ontime_branches.branch_code from leads inner join ontime_branches on ontime_branches.branch_code = leads.branch_id where leads.id = " . $order_id;
        $data = $this->db->query($query);
        $data = $data->first_row();

        if (false) {
            if ($data->branch_code == 107) {
                $arr_sendRequest = array();
                // if (config_item('payment_mode') == 'production') {
                $ch = curl_init("https://ipg.comtrust.ae:2443");
                $arr_sendRequest['UserName'] = "OTBS_SIBIN";
                $arr_sendRequest['Password'] = "EPG_OTGBS@2022";
                $arr_sendRequest['Customer'] = "OTBS OPC";
                $arr_sendRequest['Store'] = "0000";
                $arr_sendRequest['Terminal'] = "0000";
            } else {
                $arr_sendRequest = array();
                // if (config_item('payment_mode') == 'production') {
                $ch = curl_init("https://ipg.comtrust.ae:2443");
                $arr_sendRequest['UserName'] = "OTG_Joseph02";
                $arr_sendRequest['Password'] = "EPG_Gat@pay#$202K";
                $arr_sendRequest['Customer'] = "ON TIME GOVERNMENT";
                $arr_sendRequest['Store'] = "0000";
                $arr_sendRequest['Terminal'] = "0000";
            }


            // if (config_item('payment_mode') == 'demo') {
            // $ch = curl_init("https://demo-ipg.ctdev.comtrust.ae:2443");
            // $arr_sendRequest['UserName'] = "Demo_fY9c";
            // $arr_sendRequest['Password'] = "Comtrust@20182018";
            // $arr_sendRequest['Customer'] = "Demo Merchant";
            // $arr_sendRequest['Store'] = "0000";
            // $arr_sendRequest['Terminal'] = "0000";
            // }


            $arr_sendRequest['Currency'] = "AED";
            $arr_sendRequest['TransactionHint'] = "CPT:Y;VCC:Y;";
            $arr_sendRequest['OrderID'] = "OTLDPMET" . $order_id;
            $arr_sendRequest['OrderInfo'] = "OnTime Service Payment";
            $arr_sendRequest['OrderName'] = "OTG - CRM Payment";
            $arr_sendRequest['Channel'] = "Web";
            $arr_sendRequest['Amount'] = $amount;

            $arr_sendRequest['ReturnPath'] = $return_path;

            // print_r($arr_sendRequest);
            // exit();

            $jonRequest = json_encode(array('Registration' => $arr_sendRequest), JSON_FORCE_OBJECT);
            //print "Json-submitted" . $jonRequest;
            curl_setopt($ch, CURLOPT_HEADER, 0); // Set to 1 to see HTTP headers, otherwise 0 or XML reading will not work 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept:text/xml-standard-api'));
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            //curl_setopt($ch, CURLOPT_PORT, 2443);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jonRequest);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response_curl = curl_exec($ch);
            if (curl_errno($ch)) {
                $response_curlerror = curl_error($ch);
            }
            curl_close($ch);
            $view_data['response_curl'] = $response_curl;
            $view_data['response_curlerror'] = $response_curlerror;
            // echo $response_curl;
            // exit();
            // $data = array(
            //     'page_title' => 'OnTime Payment',
            //     'title' => 'OnTime Payment',
            //     'content' => $this->load->view('pages/payment', $view_data, TRUE),
            // );
            return $this->load->view('pages/payment', $view_data);
        } else {
            $response = $this->network_authentication();
            $res = json_decode($response);
            $access_token = $res->access_token;
            // exit();
            // echo $access_token;
            // exit();

            $cc_company = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'cc_company_key');
            $cc_company_outletref = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'cc_company_outletref');
            if($cc_company != '' && $cc_company != NULL && $cc_company_outletref != '' && $cc_company_outletref != NULL){
                $outLet = $cc_company_outletref;
            } else if ($branch_id == 138) {
                $outLet = "35672f6e-a2a8-4a8a-a1fd-28b0565fe646";
            } else {
                $outLet = $this->getOutletRef($user_id);
            }

            // if ($user_id == 1752723831) {
            //     $outLet = "35672f6e-a2a8-4a8a-a1fd-28b0565fe646";
            // } else {
            //     $outLet = $this->getOutletRef($user_id);
            // }

            $curl = curl_init();
            $reqData = '{"action":"SALE","amount":{"currencyCode":"AED","value":' . ($amount * 100) . '},"merchantAttributes":{"redirectUrl":"' . $return_path . '","skip3DS":true,"skipConfirmationPage": true}}';
            // echo $reqData;
            $headers = array(
                'Authorization: Bearer ' . $access_token,
                'Content-Length: ' . strlen($reqData),
                'Host: api-gateway.ngenius-payments.com',
                'Accept: application/vnd.ni-payment.v2+json',
                'Content-Type: application/vnd.ni-payment.v2+json'
            );
            // print_r($headers);
            // exit();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-gateway.ngenius-payments.com/transactions/outlets/' . $outLet . '/orders',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $reqData,
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);

            // print_r($response);
            curl_close($curl);
            // exit();
            $resp = json_decode($response);
            // payment_ref
            $this->mcommon->common_edit('lead_action_log', ["payment_ref" => $resp->reference, "pg_req" => $reqData, "pg_res" => $response], array('id' => $attempt_action_id));

            $this->mcommon->common_edit('lead_action_log', ["payment_ref" => $resp->reference], array('id' => $action_id));

            $payment_link = $resp->_links->payment->href;
            redirect($payment_link);
        }
    }

    public function ccpay()
    {
        $ref = $_GET["ref"];
        $ref = explode("-", $ref);
        $amount = round((float)encrypt_decrypt($_GET["token"], "decrypt"), 2);
        $action_id = (int)encrypt_decrypt($_GET["identity"], "decrypt");
        $user_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'action_by');
        $role_id = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'role_id');
        $lead_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'lead_id');
        $branch_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'branch_id');

        if ($branch_id == 138) {
            $query = "SELECT md5(REVERSE(CONCAT(leads.id,'-',lead_users.email,'-','@OnTimeCRM11..'))) as token2,leads.id,lead_users.email,lead_users.first_name as customer_name from leads JOIN lead_users on leads.customer_id=lead_users.user_id join lead_action_log on lead_action_log.lead_id=leads.id where md5(CONCAT(`leads`.`id`,'-',`lead_users`.`email`,'-@OnTimeCRM11..'))='" . $ref[0] . "' and lead_action_log.action_id=412 and lead_action_log.action_amount=" . $amount . " and (lead_action_log.payment_id = 0 OR lead_action_log.payment_id IS NULL) and lead_action_log.action_on > SUBDATE(NOW(), INTERVAL 3 hour) order by leads.id desc";
        } else {
            $query = "SELECT md5(REVERSE(CONCAT(leads.id,'-',lead_users.email,'-','@OnTimeCRM11..'))) as token2,leads.id,lead_users.email,lead_users.first_name as customer_name from leads JOIN lead_users on leads.customer_id=lead_users.user_id join lead_action_log on lead_action_log.lead_id=leads.id where md5(CONCAT(`leads`.`id`,'-',`lead_users`.`email`,'-@OnTimeCRM11..'))='" . $ref[0] . "' and (lead_action_log.action_id=412 OR lead_action_log.action_id=443 )and lead_action_log.action_amount=" . $amount . " and (lead_action_log.payment_id = 0 OR lead_action_log.payment_id IS NULL) and lead_action_log.updated_at > subdate(now(), interval 48 hour) and leads.lead_status != 306 and lead_action_log.link_active IS NULL order by leads.id desc";
        }
        // $query = "SELECT md5(REVERSE(CONCAT(leads.id,'-',lead_users.email,'-','@OnTimeCRM11..'))) as token2,leads.id,lead_users.email,lead_users.first_name as customer_name from leads JOIN lead_users on leads.customer_id=lead_users.user_id join lead_action_log on lead_action_log.lead_id=leads.id where md5(CONCAT(`leads`.`id`,'-',`lead_users`.`email`,'-@OnTimeCRM11..'))='" . $ref[0] . "' and lead_action_log.action_id=412 and lead_action_log.action_amount=" . $amount . " and (lead_action_log.payment_id = 0 OR lead_action_log.payment_id IS NULL) and lead_action_log.action_on > subdate(now(), interval 48 hour) order by leads.id desc";
        // echo $query;
        // echo "<br>";
        $data = [];
        $data = $this->db->query($query);
        $data = $data->first_row();
        // echo $amount;
        // echo "<br><pre>";
        // print_r($data);
        // echo "</pre>";
        // exit();
        // if($lead_id=='187434'){
        //     // echo $query;
        //     echo $amount;
        // echo "<br><pre>";
        // print_r($data);
        // echo "</pre>";
        // exit();
        // }

        if ($data != NULL) {
            if ($data->token2 == $ref[1]) {
                // $this->session->set_userdata("user_data", $datum->user_id);
                // return $this->pay($datum->total_amount,$datum->pcr_order_id,$datum->first_name);

                return $this->pay($amount, $data->id, $data->customer_name, $data->email, $action_id);
            } else {
                // print("/pcr/info?title=Expired !&desc=This Payment link is expired or Invalid.");
                // exit();
                redirect("/payment/failure?title=Expired !&desc=This Payment link is expired or Invalid1.");
            }
        } else {
            // print("/pcr/info?title=Expired !&desc=This Payment link is expired or Invalid.");
            // exit();
            redirect("/payment/failure?title=Expired !&desc=This Payment link is expired or Invalid2.");
        }
    }

    public function test()
    {
        $lead_id = 7519;
        $lead = $this->leads_model->lead_details($lead_id);
        echo $this->auth_first_name . " ==> " . $this->auth_employee_id;
        echo "<pre>";
        $emp = $this->db->select("*")->from("users")->where("user_id", $this->auth_user_id)->get()->first_row();
        $employee = $emp->first_name . " " . $emp->last_name . "(" . $emp->employee_id . ")";
        echo "</pre>";
    }

    public function payment_process()
    {
        //$user_id = $this->session->userdata('user_id');
        if (isset($_POST)) {
            $response_curlerror = "";
            $response_curl = "";
            $user_id = $this->input->get('code');
            $order_id = $this->input->get('order_id');
            $action_id = $this->input->get('act');
            $cust_email = $this->input->get('email');
            $attempt_action_id = $this->input->get('attempt_action');
            $payment_ref = $this->mcommon->specific_row_value('lead_action_log', array('id' => $attempt_action_id), 'payment_ref');
            $emp_user_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $attempt_action_id), 'action_by');

            $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $emp_user_id), 'pos_user_id');   // $this->auth_user_id
            if ($user_pos == 0 || $user_pos == NULL) {
                $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $emp_user_id), 'employee_id');   // $this->auth_user_id
            }
            // if ($user_pos == 0 || $user_pos == NULL){
            //     $user_pos = "crmonline";
            // }
            // $role_id = $this->mcommon->specific_row_value('users', array('user_id' =>  $emp_user_id), 'role_id');
            $lead_id = $this->mcommon->specific_row_value('lead_action_log', array('id' => $attempt_action_id), 'lead_id');
            $branch_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'branch_id');

            $outLet = NULL;
            $cc_company = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'cc_company_key');
            $cc_company_outletref = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id), 'cc_company_outletref');
            if($cc_company != '' && $cc_company != NULL && $cc_company_outletref != '' && $cc_company_outletref != NULL){
                $outLet = $cc_company_outletref;
            } else if ($outLet == NULL){
                $pg_res = $this->mcommon->specific_row_value('lead_action_log', array('id' => $attempt_action_id), 'pg_res');
                $pg_res = json_decode($pg_res);
                $outLet = $pg_res->outletId;
            } else if ($branch_id == 138) {
                $outLet = "35672f6e-a2a8-4a8a-a1fd-28b0565fe646";
            } else {
                $outLet = $this->getOutletRef($emp_user_id);
            } 

            

            // if ($emp_user_id == 1752723831) {
            //     $outLet = "35672f6e-a2a8-4a8a-a1fd-28b0565fe646";
            // } else {
            //     $outLet = $this->getOutletRef($emp_user_id);
            // }
            // print_r($_GET);
            // exit();
            $response = $this->network_authentication();
            $auth = json_decode($response);

            // $arr_sendRequest = array();

            // if (config_item('payment_mode') == 'production') {

            $order_det = $this->leads_model->lead_details($user_id);

            $payment_branch = "businesssetup";
            if ($order_det["branch_id"] == 107 || $order_det["branch_id"] == 107 || $order_det["branch_id"] == 6 ||  $order_det["branch_id"] == 13 ||  $order_det["branch_id"] == 14 ||  $order_det["branch_id"] == 20 ||  $order_det["branch_id"] == 21) {
                // $ch = curl_init("https://ipg.comtrust.ae:2443");
                // $arr_sendRequest['UserName'] = "OTBS_SIBIN";
                // $arr_sendRequest['Password'] = "EPG_OTGBS@2022";
                // $arr_sendRequest['Customer'] = "OTBS OPC";
            } else {
                // $ch = curl_init("https://ipg.comtrust.ae:2443");
                // $arr_sendRequest['UserName'] = "OTG_Joseph02";
                // $arr_sendRequest['Password'] = "EPG_Gat@pay#$202K";
                // $arr_sendRequest['Customer'] = "ON TIME GOVERNMENT";
                $payment_branch = "nonbusinesssetup";
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-gateway.ngenius-payments.com/transactions/outlets/' . $outLet . '/orders/' . $payment_ref,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $auth->access_token,
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $responseData = json_decode($response, true);

        //    if($lead_id == 294539){
        //     echo '<pre>';print_r("OUt let ref : ");
        //         echo '<pre>';print_r($outLet);
        //         echo '<pre>';print_r("Pyament ref");
        //         echo '<pre>';print_r($payment_ref);
        //         echo '<pre>';print_r("Result  : ");
        //         echo '<pre>';print_r($responseData); exit;
        //    }


            $paymentState = $responseData['_embedded']['payment'][0]['state'];
            $paymentAmount = $responseData['_embedded']['payment'][0]['amount']['value'];

            $this->mcommon->common_edit('lead_action_log', ["pg_callback" => $response], array('id' => $attempt_action_id));

            // $ch = curl_init("https://demo-ipg.ctdev.comtrust.ae:2443");
            // $arr_sendRequest['UserName'] = "Demo_fY9c";
            // $arr_sendRequest['Password'] = "Comtrust@20182018";
            // $arr_sendRequest['Customer'] = "Demo Merchant";
            // $arr_sendRequest['Store'] = "0000";
            // $arr_sendRequest['Terminal'] = "0000";
            // }
            // }

            // if (config_item('payment_mode') == 'demo') {
            // $ch = curl_init("https://demo-ipg.ctdev.comtrust.ae:2443");
            // $arr_sendRequest['UserName'] = "Demo_fY9c";
            // $arr_sendRequest['Password'] = "Comtrust@20182018";
            // $arr_sendRequest['Customer'] = "Demo Merchant";
            // }


            $ResponseCode = "";
            $ResponseClass = "";
            $ResponseDescription = $response;
            $ResponseClassDescription = $paymentState;
            $Language = "";
            $ApprovalCode = "";
            $Account = "";
            $Balance = "";
            $OrderID = "OTLDPMET" . $user_id;
            $Amount = ((float)$paymentAmount) / 100;
            $Fees = "";
            $CardNumber = "";
            $Payer = "";
            $CardToken = "";
            $CardBrand = "";
            $CardExpiry = "";
            $CardType = "";
            $UniqueID = $payment_ref;

            if ($paymentState == "CAPTURED") {      // || $paymentState == "STARTED"
                $date = date('d-m-Y');
                $date1 = date("d-m-Y h:i:sa");
                $time = date('h:i A', strtotime($date1));
                $desc = 'nill';
                $total = 0;

                $req['pg_response'] = array(
                    'Language'  => $responseData['language'],
                    'TransactionID' => $responseData['reference'],
                    'TransactionState'  => $responseData['_embedded']['payment'][0]['state'],
                    'PaymentLink'  => $responseData['merchantAttributes']['redirectUrl'],
                    'ResponseCode'  => $responseData['_embedded']['payment'][0]['authResponse']['resultCode'],
                    'ResponseMessage'  => $responseData['_embedded']['payment'][0]['authResponse']['resultMessage'],
                    'ApprovalCode' => $responseData['_embedded']['payment'][0]['authResponse']['authorizationCode'],
                    'AuthorizationCode' => $responseData['_embedded']['payment'][0]['authResponse']['authorizationCode'],
                    'MID' => $responseData['_embedded']['payment'][0]['authResponse']['mid'],
                    'Account' => $responseData['_embedded']['payment'][0]['paymentMethod']['name'],
                    'CardBrand' => $responseData['_embedded']['payment'][0]['paymentMethod']['name'],
                    'CardNumber' => $responseData['_embedded']['payment'][0]['paymentMethod']['pan'],
                    'PayerInformation' => $responseData['_embedded']['payment'][0]['paymentMethod']['pan'],
                    'CardToken' => $responseData['_embedded']['payment'][0]['paymentMethod']['pan'],
                    'CardHolderName' => $responseData['_embedded']['payment'][0]['paymentMethod']['cardholderName'],
                    'CardExpiry' => $responseData['_embedded']['payment'][0]['paymentMethod']['expiry'],
                    'UniqueID' => $responseData['_id'],
                    'CurrencyCode' => $responseData['_embedded']['payment'][0]['amount']['currencyCode'],
                    'Amount' => $Amount,    
                    'Action' => $responseData['action'],
                    'OutletID' => $responseData['outletId'],
                    'MerchantReference' => $responseData['merchantDetails']['reference'],
                    'MerchantName' => $responseData['merchantDetails']['name'],
                    'MerchantCompanyURL' => $responseData['merchantDetails']['companyUrl'],
                    'MerchantEmail' => $responseData['merchantDetails']['email'],
                    'MerchantMobile' => $responseData['merchantDetails']['mobile'],
                );

                $payments_data = array(
                    'user_id' => $user_id,
                    'response_code' => $ResponseCode,
                    'response_class' => $ResponseClass,
                    'response_description' => $ResponseDescription,
                    'response_class_description' => $ResponseClassDescription,
                    'language' => $Language,
                    'approval_code' => $ApprovalCode,
                    'account' => $Account,
                    'balance' => $Balance,
                    'lead_id' => $OrderID,
                    'amount' => $Amount,
                    'fees' => $Fees,
                    'card_number' => $CardNumber,
                    'payer' => $Payer,
                    'card_token' => $CardToken,
                    'card_brand' => $CardBrand,
                    'card_expiry' => $CardExpiry,
                    'card_type' => $CardType,
                    'unique_id' => $UniqueID,
                    'created_date' => $date,
                    'pg_response' => json_encode($req['pg_response']),
                );

                $lead_id = str_replace("OTLDPMET", "", $OrderID);

                $payment_insert_id = $this->mcommon->common_insert('lead_payments', $payments_data);

                $so_order = NULL;
                $raw_salesorder = NULL;
                $pmt_response = NULL;
                $raw_pmtnumber = NULL;
                $pos_cust_key = NULL;
                $lead_det = $this->leads_model->lead_details($lead_id);

                if ($user_pos == 0 || $user_pos == NULL) {
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'pos_user_id');
                    if ($user_pos == 0 || $user_pos == NULL)
                        $user_pos = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                }

                $log_insert_array = array('action_id' => 445, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => "Online payment is completed from the Payment Gateway", 'action_by' => $emp_user_id, 'status_id' => 644);
                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                if ($lead_det["category_id"] == 125) {



                    // $curl = curl_init();
                    // // http://185.56.89.101:82
                    // // http://192.168.61.52:81
                    // $curl_url = 'http://185.56.89.101:82/POSInvoice/PushPayment?custmobile=' . $lead_det["customer_mobile"] . '&custname=' . rawurlencode($lead_det["customer_name"]) . '&email=' . $lead_det["customer_email"] . '&amount=' . $Amount . '&orderref=' . $payment_insert_id . '-' . $OrderID . '&servicedesc=' . rawurlencode($lead_det["service_name"]) . '&createso=true&botid=' . $lead_det["bot_id"];
                    // curl_setopt_array($curl, array(
                    //     CURLOPT_URL => $curl_url,
                    //     CURLOPT_RETURNTRANSFER => true,
                    //     CURLOPT_ENCODING => '',
                    //     CURLOPT_MAXREDIRS => 10,
                    //     CURLOPT_TIMEOUT => 0,
                    //     CURLOPT_HTTPHEADER => array('Content-Length: 0'),
                    //     CURLOPT_FOLLOWLOCATION => true,
                    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    //     CURLOPT_CUSTOMREQUEST => 'POST',
                    // ));

                    $customer_name = $lead_det["invoice_to"] ?? $lead_det["customer_name"];
                    $req["Customer"] = array("Cust_EngName" => $customer_name, "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["OrderRef"] = $payment_insert_id . '-' . $OrderID;
                    // $req["Payment"] = array("ActAmt" => $Amount, "OnlinePaymentRef" => $UniqueID);

                    // $action_id
                    $logs = $this->db->select("*")->from("lead_payment_details")->where("lead_action_log_id", $action_id)->get()->result_array();

                    $so_bots = [];
                    $services = "";
                    foreach ($logs as $log) {
                        $services .= $log["service_name"] . ",";
                        array_push($so_bots, ["Id" => $log["bot_id"], "DiscAmt" => $log["discount"], "AddTypingFee" => $log["typing_fee"]]);
                    }

                    $req["ServDescription"] = $services;
                    $req["salesorderdtl"] = $so_bots;
                    $req["Payment_Type"] = 'ONLINE';
                    // $req["User"] = ["User_ID"=>"crmonline"];
                    if ($branch_id == 138) {
                        $req["User"] = ["User_ID" => 1002];   
                    } else if ($lead_det["branch_id"] == 119 && $lead_det["otg_paylater"] == 1) {
                        $req["User"] = ["User_ID" => 1001];   
                    } else{
                        $req["User"] = ["User_ID" => $user_pos]; 
                    }

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                    $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');

                    if(!empty($lead_det["lead_zoho_id"])){
                        
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                            $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $Amount, 
                            "OnlinePaymentRef" => $UniqueID,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],  // campaign Name
                            "ZohoLeadSource" => $lead_det["lead_source"],       // Facebook Ads
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],   // campaign Id
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],          // Zoho CRM Lead ID
                            "LeadFrom" => 'Zoho',
                            "CRMLeadId" => $lead_id,
                            "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,                // email
                            "MainServiceId" => $lead_det['main_service_type']
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $Amount, 
                            "OnlinePaymentRef" => $UniqueID,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],                   // Baraha, OntimeGOV, Goldencube
                            "CRMLeadId" => $lead_id,            
                            "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                            "MainServiceId" => $lead_det['main_service_type']
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

                    if(!empty($lead_det['applicant_name']) && $lead_det['applicant_name'] != null){
                        $req["ApplicantName"] = $lead_det['applicant_name'];
                    }

                    // $test_log_insert_array = array("pos_userid" => $user_pos, "lead_id" => $lead_id, "response" => $req);

                    // $insert_log = $this->mcommon->common_insert('test_payment_log', $test_log_insert_array);


                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=1',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($req),
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                    }
                    $res_json = json_decode($raw_response);

                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));

                    if(isset($res_json->ResponseCode) && $res_json->ResponseCode == 1){
                        if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                            $res_message = $res_json->ResponseMsg;
                            $log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'action_by' => $emp_user_id, 'status_id' => 304);
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        }
                        
                        if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg == "Error! Duplicate Online Payment Reference!"){
                            $res_message = $res_json->ResponseMsg;
                            header("Location: /payment/failure?title=Payment Success&reason=" . $res_message);
                            exit();
                        } else if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg == "Error! Same payment found for this customer within the last 10 minutes!"){
                            $res_message = $res_json->ResponseMsg;
                            header("Location: /payment/failure?title=Payment Success&reason=" . $res_message);
                            exit();
                        } else if(isset($res_json->ResponseMsg)){
                            $res_message = $res_json->ResponseMsg;
                            header("Location: /payment/failure?title=Payment Success&reason=" . $res_message);
                            exit();
                        } else {
                            header("Location: /payment/failure?title=Payment Success&desc=Please try again later");
                            exit();
                        }
                    }

                    if (isset($res_json->Data->SLO_Headnum)) {
                        $so_order = $res_json->Data->SLO_Headnum;
                        $raw_salesorder = $so_order;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $so_order = "under the salesorder " . $so_order . "</b>";
                    }

                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 310, "pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    $update = $this->mcommon->common_edit("leads", array("lead_status" => 310, "pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_salesorder, "pos_cust_key" =>  $pos_cust_key), array("lead_parent_id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();
                } else {
                    $customer_name = $lead_det["invoice_to"] ?? $lead_det["customer_name"];
                    $req["Customer"] = array("Cust_EngName" => $customer_name, "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                    $req["OrderRef"] = $payment_insert_id . '-' . $OrderID;
                    $req["Payment"] = array("ActAmt" => $Amount, "OnlinePaymentRef" => $UniqueID);
                    $req["ServDescription"] = $lead_det["service_name"];
                    $req["salesorderdtl"] = [];
                    // $req["User"] = ["User_ID"=>"crmonline"];
                    if ($lead_det["branch_id"] == 138) {
                        $req["User"] = ["User_ID" => 1002];   
                    } else if ($lead_det["branch_id"] == 119 && $lead_det["otg_paylater"] == 1) {
                        $req["User"] = ["User_ID" => 1001];   
                    } else{
                        $req["User"] = ["User_ID" => $user_pos]; 
                    }
                    $req["MainServiceId"] =  $lead_det['main_service_type'];

                    if ($lead_det["branch_id"] == 138) {
                        $req["DomainName"] = "bookings.ontimegov.com";
                    } else if ($lead_det["branch_id"] == 119) {
                        $req["DomainName"] = "ontimegov.com";
                    } else {
                        $req["DomainName"] = "crm.ontimegroup.com";
                    }
                    $req["Payment_Type"] = 'ONLINE';

                    // POS Changes 
                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                    $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                    $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');

                    if(!empty($lead_det["lead_zoho_id"])){
                        $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                        if(!empty($lead_created_by)){
                            $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                            $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                        } else {
                            $created_by_user = '';
                        }

                        $req["Payment"] = array(
                            "ActAmt" => $Amount, 
                            "OnlinePaymentRef" => $UniqueID,
                            "CampaignSource" => $lead_det["lead_ad_campaign"],
                            "ZohoLeadSource" => $lead_det["lead_source"],
                            "CampaignId" => $lead_det["lead_ad_campaign_id"],
                            "ZohoLeadId" => $lead_det["lead_zoho_id"],
                            "LeadFrom" => 'Zoho',
                            "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                            "MainServiceId" => $lead_det['main_service_type']
                        );
                        
                    } else {
                        $req["Payment"] = array(
                            "ActAmt" => $Amount, 
                            "OnlinePaymentRef" => $UniqueID,
                            "LeadSource" => 'Website',
                            "LeadFrom" => $lead_det["lead_from"],
                            "CRMLeadId" => $lead_id,
                            "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                            "MainServiceId" => $lead_det['main_service_type']
                        );
                    }

                    if(!empty($lead_det["pos_cust_key"])){
                        $req["Cust_Key"] = $lead_det["pos_cust_key"];
                    }

                    if(!empty($lead_det['applicant_name']) && $lead_det['applicant_name'] != null){
                        $req["ApplicantName"] = $lead_det['applicant_name'];
                    }

                    $curl = curl_init();

                    // $test_log_insert_array = array("pos_userid" => $user_pos, "lead_id" => $lead_id, "response" => $req);

                    // $insert_log = $this->mcommon->common_insert('test_payment_log', $test_log_insert_array);

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($req),
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);

                    // $response = '{"ResponseCode":1,"ResponseMsg":"Duplicate Order Reference Number!!","Data":{"PMT_Number":"RCT-216583","SLO_Headnum":"SO-239571","SLI_Headnum":""}}';
                    // $response = curl_exec($curl);
                    $raw_response = $response;
                    // echo $curl_url;
                    if (curl_errno($curl)) {
                        $response = json_encode($req) . "<br>" . curl_error($curl);
                        // print_r(curl_error($curl));
                        curl_close($curl);
                    } else {
                        $response = json_encode($req) . "<br>" . $response;
                        curl_close($curl);
                        $pmt_response = $response;
                    }
                    $res_json = json_decode($raw_response);

                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response), array("id" => $lead_id));
                    
                    if(!isset($res_json->ResponseCode) || (isset($res_json->ResponseCode) && $res_json->ResponseCode == 1) || empty($response)){
                        // if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                        //     $res_message = $res_json->ResponseMsg;
                        //     $log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'action_by' => $emp_user_id, 'status_id' => 304);
                        //     $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        // }

                        $lead_subject = "Live OntimeCRM - Receipt is not Generated against the Lead #" . $lead_id;
                        
                        $lead_message_content = "Dear Team,<br /><br />";
                        if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                            $lead_message_content .= "<br>POS Error Message <strong>: " . $res_json->ResponseMsg. "</strong>";
                        }

                        $lead_message_content .= "<br /><br><br>Lead Details:<br>";
                        $lead_message_content .= "<br>Customer Name: " . $lead_det["customer_name"];
                        $lead_message_content .= "<br>Customer Country Code: " . $lead_det["customer_country_code"];
                        $lead_message_content .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                        $lead_message_content .= "<br>Customer Email: " . $lead_det["customer_email"];
                        $lead_message_content .= "<br>Amount : " . $Amount;
                        $lead_message_content .= "<br>Online Payment Ref: " . $UniqueID;
                        $lead_message_content .= "<br>Attempt Action ID: " . $attempt_action_id;

                        $sublead_cc_email = [];
                        array_push($sublead_cc_email, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Manikandan"]);
                        array_push($sublead_cc_email, ["email" => "dravidan.v@mitrahsoft.in", "name" => "Dravidan"]);
                        array_push($sublead_cc_email, ["email" => "Sahala.k@ontech.digital", "name" => "Sahala"]);
                        // array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   
        
                        $email_array = array(
                            'email' =>  "hanna.h@egovllc.com",
                            'cc' => $sublead_cc_email,
                            'subject' => $lead_subject,
                            'template' => 'mails/template',
                            'from_name' => "Ontime CRM",
                            'message' => $lead_message_content,
                            'branch_id' => $lead_det["branch_id"],
                        );
        
                        $send_mail = send_template_email($email_array);
                        log_message('error', $send_mail);

                        if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                            $fetch_pay_url = "https://crm.ontimegroup.com/payment/payment_process?code=".trim($lead_id)."&order_id=".trim($lead_id)."&act=".trim($attempt_action_id)."&attempt_action=".trim($attempt_action_id)."&email=".trim($lead_det["customer_email"]);
                            $res_message = $res_json->ResponseMsg. "<br><a target='_blank' href=". $fetch_pay_url ."' class='p-2 pl-4 pr-4 btn btn-primary'>Fetch Payment Status</a>";
                            $log_insert_array = array('action_id' => 446, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'action_by' => $emp_user_id, 'status_id' => 645, 'is_fetch_pay_status' => 1);
                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                        }

                        if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg == "Error! Duplicate Online Payment Reference!"){
                            $res_message = $res_json->ResponseMsg;
                            header("Location: /payment/failure?title=Payment Success&reason=" . $res_message);
                            exit();
                        } else if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg == "Error! Same payment found for this customer within the last 10 minutes!"){
                            $res_message = $res_json->ResponseMsg;
                            header("Location: /payment/failure?title=Payment Success&reason=" . $res_message);
                            exit();
                        } else if(isset($res_json->ResponseMsg)){
                            $res_message = $res_json->ResponseMsg;
                            header("Location: /payment/failure?title=Payment Success&reason=" . $res_message);
                            exit();
                        } else {
                            header("Location: /payment/failure?title=Payment Success&desc=Please try again later");
                            exit();
                        }
                    }

                    if (isset($res_json->Data->PMT_Number)) {
                        $so_order = $res_json->Data->PMT_Number;
                        $raw_pmtnumber = $so_order;
                        $pos_cust_key = $res_json->Data->Cust_Key;
                        $so_order = "under the Payment Receipt " . $so_order . "</b>";
                    }

                    // $update = $this->mcommon->common_edit("leads", array("lead_status" => 310, "pos_salesorder" => $raw_salesorder), array("id" => $lead_id));
                    // echo "There Update--> ".$raw_salesorder." <==> ".$lead_id." ==> ".$update;
                    // echo "<br>".$so_order;
                    // exit();
                }

                if ($lead_det["category_id"] != 125 && !empty($raw_pmtnumber)) {

                    //Update Payment ID
                    $customer_id = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');

                    $log_insert_array = array("action_id" => 415, "lead_id" => $lead_id, "action_amount" => $Amount, "payment_id" => $payment_insert_id, "remarks" => "Customer paid " . $Amount . " AED by " . $Payer . " Card for <b>#" . $req["OrderRef"] . " " . $so_order . "</b>", "action_by" => $customer_id, "status_id" => 310, "pos_pmt_response" => $pmt_response, "pos_pmt_number" => $raw_pmtnumber);

                    $is_additional_payment = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id, 'lead_id' => $lead_id), 'is_additional_payment', 'id DESC');
                    if($is_additional_payment == 'true'){
                        $log_insert_array['is_additional_payment'] = 'true';
                    }

                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    // $updates = $this->mcommon->common_edit("lead_action_log", array("lead_id" => $lead_id, "action_id" => 412, "action_amount" => $Amount), array("payment_id" => $payment_insert_id));

                    $updates = $this->mcommon->common_edit("lead_action_log", array("payment_id" => $payment_insert_id),array("lead_id" => $lead_id, "action_id" => 412, "action_amount" => $Amount) );


                    $action = $this->db->where(['id' => $action_id])->get('lead_action_log')->first_row();
                    // print_r($action);
                    $customer = $this->db->where(['user_id' => $customer_id])->get('lead_users')->first_row();
                    $customer_contact = substr($customer->mobile, -10);
                    if (strlen($customer_contact) == 9) {
                        $customer_contact = '0' . $customer_contact;
                    }

                    $html = preg_replace('/<div.*iv>/', '<b>Payment Done for #' . $req["OrderRef"] . ' amount ' . $Amount . ' ' . $so_order . '</b>', strval($action->remarks));
                    $cc_usermail = [];
                    $csa_usermail = $this->mcommon->specific_row_value('users', array('user_id' => $action->action_by), "email");
                    // print_r($action);
                    // print_r(count($cc_usermail));
                    // exit();
                    $param_array_for_pos = array('customerPhoneNumber' => $customer_contact, 'customerName' => $customer->first_name . ' ' . $customer->last_name, 'templateMasterPriceList' => array(array('serviceName' => "Custom", 'govtFee' => '0.00', 'typingFee' => $Amount, 'templateId' => 13, 'quantity' => 1, 'paymentRefNo' => $ApprovalCode, 'orderReferenceList' => [$OrderID])));
                    $final_param_json = json_encode($param_array_for_pos);
                    log_message('error', $final_param_json);
                }

                if ($lead_det["category_id"] == 125) {
                    //Update Payment ID
                    $customer_id = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');

                    $log_insert_array = array("action_id" => 415, "lead_id" => $lead_id, "action_amount" => $Amount, "payment_id" => $payment_insert_id, "remarks" => "Customer paid " . $Amount . " AED by " . $Payer . " Card for <b>#" . $req["OrderRef"] . " " . $so_order . "</b>", "action_by" => $customer_id, "status_id" => 310, "pos_pmt_response" => $pmt_response, "pos_pmt_number" => $raw_pmtnumber);

                    $is_additional_payment = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id, 'lead_id' => $lead_id), 'is_additional_payment', 'id DESC');
                    if($is_additional_payment == 'true'){
                        $log_insert_array['is_additional_payment'] = 'true';
                    }

                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $updates = $this->mcommon->common_edit("lead_action_log", array("payment_id" => $payment_insert_id), array("lead_id" => $lead_id, "action_id" => 412, "action_amount" => $Amount));

                    $action = $this->db->where(['id' => $action_id])->get('lead_action_log')->first_row();

                    $customer = $this->db->where(['user_id' => $customer_id])->get('lead_users')->first_row();
                    $customer_contact = substr($customer->mobile, -10);
                    if (strlen($customer_contact) == 9) {
                        $customer_contact = '0' . $customer_contact;
                    }

                    $html = preg_replace('/<div.*iv>/', '<b>Payment Done for #' . $req["OrderRef"] . ' amount ' . $Amount . ' ' . $so_order . '</b>', strval($action->remarks));
                    $cc_usermail = [];
                    $csa_usermail = $this->mcommon->specific_row_value('users', array('user_id' => $action->action_by), "email");

                    $param_array_for_pos = array('customerPhoneNumber' => $customer_contact, 'customerName' => $customer->first_name . ' ' . $customer->last_name, 'templateMasterPriceList' => array(array('serviceName' => "Custom", 'govtFee' => '0.00', 'typingFee' => $Amount, 'templateId' => 13, 'quantity' => 1, 'paymentRefNo' => $ApprovalCode, 'orderReferenceList' => [$OrderID])));
                    $final_param_json = json_encode($param_array_for_pos);
                    log_message('error', $final_param_json);
                }

                // print_r($cc_usermail);
                // exit();
                // $this->load->helper('pos_helper');
                // $token = get_access_token();

                // $curl = curl_init();
                // curl_setopt_array($curl, array(
                // 	CURLOPT_URL => config_item('post_to_pos_url'),
                // 	CURLOPT_RETURNTRANSFER => true,
                // 	CURLOPT_ENCODING => '',
                // 	CURLOPT_MAXREDIRS => 10,
                // 	CURLOPT_TIMEOUT => 0,
                // 	CURLOPT_FOLLOWLOCATION => true,
                // 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                // 	CURLOPT_CUSTOMREQUEST => 'POST',
                // 	CURLOPT_POSTFIELDS => $final_param_json,
                // 	CURLOPT_HTTPHEADER => array(
                // 		'Content-Type: application/json',
                // 		'Authorization: Bearer ' . $token
                // 	),
                // ));
                // $response = curl_exec($curl);
                // $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                // curl_close($curl);

                // array_push($cc_usermail, ["email" => "ahmed.ha@ontimegroup.com"]);

                if ($lead_det["branch_id"] == 107 || $lead_det["branch_id"] == 6 ||  $lead_det["branch_id"] == 13 ||  $lead_det["branch_id"] == 14 ||  $lead_det["branch_id"] == 20 ||  $lead_det["branch_id"] == 21) {
                    // array_push($cc_usermail, ["email" => "ilyas.n@ontimegov.com", "name" => "ILYAS NALAKATH VADAKKEKAYIL"]);
                    array_push($cc_usermail, ["email" => "sandy@ontimebiz.com", "name" => "Sandy Blason Loable"]);
                    array_push($cc_usermail, ["email" => "khairi@ontimebiz.com", "name" => "Khairi Reda Khairi Mahmoud"]);
                    //array_push($cc_usermail, ["email" => "muthu.v@egovllc.com"]);
                    // array_push($cc_usermail, ["email" => "hanna.h@egovllc.com"]);
                } 
                if ($lead_det["branch_id"] == 106 && (!empty($raw_pmtnumber) && $raw_pmtnumber != '' && $raw_pmtnumber != NULL)) {
                    // comment for now requested by hanna 3 email users
                    // array_push($cc_usermail, ["email" => "sibin.d@ontimegroup.com", "name" => "Sibin Daniel"]);
                    // array_push($cc_usermail, ["email" => "alfred.c@ontimegroup.com", "name" => "Alfred Choorakkattu Antony"]);
                    // array_push($cc_usermail, ["email" => "muhammed.m@ontimegroup.com", "name" => "Muhammed Mustak Pakkan"]);
                    // array_push($cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey Siega"]);
                    array_push($cc_usermail, ["email" => "Dineli.s@goldencube.ae", "name" => "Dineli Sewwandi Gunaratna"]);
                    //array_push($cc_usermail, ["email" => "muthu.v@egovllc.com"]);
                    // array_push($cc_usermail, ["email" => "hanna.h@egovllc.com"]);

                    // This function is for Create the sublead while the Online payment only for the Goldencube
                    // $this->db->select('is_additional_payment')
                    //     ->from('lead_action_log') ->where(array('action_id' => 412, 'lead_id' => $lead_id))
                    //     ->order_by('id', 'DESC')->limit(1);
                    // $query = $this->db->get();
                    // $is_add_pay_results = $query->row();
                    // $is_additional_payment = $is_add_pay_results->is_additional_payment;
                    
                    $is_additional_payment = $this->mcommon->specific_row_value('lead_action_log', array('id' => $action_id, 'lead_id' => $lead_id), 'is_additional_payment');

                    if($is_additional_payment != 'true' && $lead_det["branch_id"] == 106){
                        $lead_package_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id, 'lead_parent_id' => 0), 'package_id');
                        $package_det = $this->leads_model->get_lead_package_entries($lead_id, $lead_package_id);
                        $lead_package_name = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_package_name');

                        $package_created_by = $package_det[0]['created_by'];
                        $created_group_id = $this->mcommon->specific_row_value('group_members', array('user_id' => $package_created_by, 'is_primary_group_id' => 1), 'group_id');

                        $package_det_count = count($package_det);

                        $update_lead_array = array(
                            'total_no_subleads' => $package_det_count,
                            'no_of_open_subleads' => $package_det_count,
                            'no_of_closed_subleads' => 0
                        );
                        $update = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $lead_id));

                        for ($i = 0; $i < $package_det_count; $i++) {
                            $card_amount = ($package_det[$i]['govt_fee'] * (2.25 / 100));
                            if(in_array($lead_package_id, [199,204,205,258,259,260, 200,201,206,207,208,209,261,262,263, 222,223,224, 225,226,227, 228,229,230])){ // insurance, valuation, translation, attestation, shipping
                                $insert_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'service_id' => $package_det[$i]['service_id']), 'id');
                            } else {
                                $insert_lead_array = array(
                                    'customer_id' => $lead_det['customer_id'],
                                    'branch_id' => $lead_det["branch_id"],
                                    'category_id' => $package_det[$i]['category_id'],
                                    'service_id' => $package_det[$i]['service_id'],
                                    'lead_created_by' => $package_det[$i]['created_by'],
                                    'lead_added_on' => date('Y-m-d H:i:s'),
                                    'contactable_date' => date('Y-m-d H:i:s'),
                                    'lead_status' => 301,
                                    'package_id' => $package_det[$i]['package_id'],
                                    'order_receipt' => 0,
                                    'remarks' => $package_det[$i]['service_name'],
                                    'is_assigned' => 0,
                                    'lead_parent_id' => $lead_id,
                                    "is_direct_invoice" => $package_det[$i]['is_direct_invoice'],
                                    "govt_fee" => $package_det[$i]['govt_fee'],
                                    "typing_fee" => $package_det[$i]['typing_fee'],
                                    "msd_key" => $package_det[$i]['msd_key'],
                                    "is_pos_typing_fee" => $package_det[$i]['is_pos_typing_fee'],
                                    "card_amount" => $card_amount,
                                    "lead_package_name" => $lead_package_name,
                                    'created_group_id' => $created_group_id,
                                );
                                $insert_lead_id = $this->mcommon->common_insert('leads', $insert_lead_array);
                            }
                            $gc_service_id = $this->mcommon->specific_row_value('ontime_category_services_', array('service_id' => $package_det[$i]['service_id']), 'gc_service_id');

                            if($gc_service_id == 94 || $gc_service_id == 2304 || $gc_service_id == 2303 || $package_det[$i]['is_direct_invoice'] > 0){
                                $sublead_id = $insert_lead_id;  // $_GET["code"];
                                $inovice_id = $package_det[$i]['is_direct_invoice'];    //$_GET["inovice_id"];
                                $pos_invoice_id = $this->mcommon->specific_row('pos_direct_invoice_list', ["id" => $inovice_id]);
                                $sublead = $this->mcommon->specific_row('leads', ["id" => $sublead_id]);
                                $lead = $this->mcommon->specific_row('leads', ["id" => $sublead["lead_parent_id"]]);
                                $pmt = !empty($raw_pmtnumber) ? $raw_pmtnumber : $lead["pos_pmt_number"];
                        
                                $lead_det = $this->leads_model->lead_details($lead["id"]);

                                if($gc_service_id == 94){
                                    $typing_fee = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead["id"], 'service_id' => $package_det[$i]['service_id']), 'typing_fee');
                                } else {
                                    $typing_fee = $this->mcommon->specific_row_value('leads', array('id' => $sublead_id), 'typing_fee');
                                }
                        
                                $req["Customer"] = array("Cust_EngName" => $lead_det["customer_name"], "Cust_Mobile" => $lead_det["customer_country_code"] . $lead_det["customer_mobile"], "Cust_Email" => $lead_det["customer_email"]);
                                $req["PMTNumber"] = $pmt;
                                $req["OrderRef"] = $sublead["id"] . '-OTLDDI' . $lead["id"];
                                $_SubLeadId = $sublead["id"] ? $sublead["id"] : $lead["id"];
                        
                                // $action_id
                                $req["ServDescription"] = $lead_det["category_code"] . " - " . $lead_det["service_name"];
                                // $req["salesorderdtl"] = [["Id" => $pos_invoice_id['pos_invoice_id'], "AddTypingFee" => 0, "SubLeadId" => $_SubLeadId]];
                                $req["salesorderdtl"] = [["Id" => 1094, "AddTypingFee" => $typing_fee, "SubLeadId" => $_SubLeadId]];
                        
                                $req["User"] = ["User_ID" => $user_pos];
                        
                                // POS Changes 
                                $lead_id = $lead["id"];
                                $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
                                $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                                $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                        
                                if(!empty($lead_det["lead_zoho_id"])){
                                    $lead_created_by = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_zoho_created_by_id');
                                    if(!empty($lead_created_by)){
                                        $created_by_user = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'email');
                                        $created_by_user_emp_id = $this->mcommon->specific_row_value('users', array('user_id' => $lead_created_by), 'employee_id');
                                    } else {
                                        $created_by_user = '';
                                    }
                        
                                    $req["Payment"] = array(
                                        "CampaignSource" => $lead_det["lead_ad_campaign"],
                                        "ZohoLeadSource" => $lead_det["lead_source"],
                                        "CampaignId" => $lead_det["lead_ad_campaign_id"],
                                        "ZohoLeadId" => $lead_det["lead_zoho_id"],
                                        "LeadFrom" => 'Zoho',
                                        "CRMLeadId" => $lead_id,
                                        "ZohoCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                                    );
                                    
                                } else {
                                    $req["Payment"] = array(
                                        "LeadSource" => 'Website',
                                        "LeadFrom" => $lead_det["lead_from"],
                                        "CRMLeadId" => $lead_id,
                                        "LeadCreatedBy" => $created_by_user_emp_id ? $created_by_user . ' (' . $created_by_user_emp_id . ')' : $created_by_user,
                                    );
                                }
                        
                                if(!empty($lead_det["pos_cust_key"])){
                                    $req["Cust_Key"] = $lead_det["pos_cust_key"];
                                }

                                if(!empty($lead_det['applicant_name']) && $lead_det['applicant_name'] != null){
                                    $req["ApplicantName"] = $lead_det['applicant_name'];
                                }
                        
                                $curl = curl_init();
                        
                                curl_setopt_array(
                                    $curl,
                                    array(
                                        CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=1',
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => json_encode($req),
                                        CURLOPT_HTTPHEADER => array(
                                            'Content-Type: application/json',
                                        ),
                                    )
                                );
                        
                                $response = curl_exec($curl);
                        
                                $raw_response = $response;
                                if (curl_errno($curl)) {
                                    $response = json_encode($req) . "<br>" . curl_error($curl);
                                    curl_close($curl);
                                } else {
                                    $response = json_encode($req) . "<br>" . $response;
                                    curl_close($curl);
                                }
                        
                                $res_json = json_decode($raw_response);
                                if(isset($res_json->ResponseCode) && $res_json->ResponseCode != 1){
                                    if (isset($res_json->Data->SLI_Headnum)) {
                                        $so_order = $res_json->Data->SLI_Headnum;
                                        $pos_cust_key = $res_json->Data->Cust_Key;
                                        $sub_raw_salesorder = $so_order;
                                        $so_order = "under the payment receipt " . $so_order . "</b>";
                                    }
                                    $this->mcommon->common_edit("leads", array("pos_so_response" => $response, "pos_invresponse" => $sub_raw_salesorder, "pos_pmt_number" => $pmt,"pos_cust_key" =>  $pos_cust_key), array("id" => $sublead["id"]));

                                    if(isset($res_json->Data->LeadDetails)){
                                        $pos_lead_details = $res_json->Data->LeadDetails;
                                        $pos_govt_fee = $pos_lead_details[0]->govt_fee;
                                        $pos_typing_fee = $pos_lead_details[0]->typing_fee;
                                        $pos_card_amnt = $pos_lead_details[0]->Card_Amnt;
                                        $pos_disc_amnt = $pos_lead_details[0]->Disc_Amnt;
                                        $pos_tax_amnt = $pos_lead_details[0]->Tax_Amnt;
                                        $pos_tot_revn = $pos_lead_details[0]->Tot_Revn;
                                        $pos_ref1 = $pos_lead_details[0]->Ref1;
                                        $pos_ref2 = $pos_lead_details[0]->Ref2;
                                        $pos_username = $pos_lead_details[0]->pos_username;
                                        $pos_tot_amnt = $pos_lead_details[0]->Tot_Amt;

                                        $this->mcommon->common_edit("leads", array("pos_GovtFees" => $pos_govt_fee, "pos_TypeFee" => $pos_typing_fee, "pos_Card_Amnt" => $pos_card_amnt, "pos_Disc_Amnt" => $pos_disc_amnt, "pos_Tax_Amnt" => $pos_tax_amnt, "pos_Tot_Revn" => $pos_tot_revn, "ref1" => $pos_ref1, "ref2" => $pos_ref2, "pos_username" => $pos_username, "pos_Tot_Amt" => $pos_tot_amnt), array("id" => $sublead["id"]));
                                    }
                            
                                    $order_desc = 'Order completed. <strong>ORDER#:</strong> ' . $sub_raw_salesorder;
                            
                                    $log_insert_array = array('action_id' => 410, 'lead_id' => $sublead["id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $order_desc, 'action_by' => $emp_user_id, 'status_id' => 305);
                                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                    if ($insert_log > 0) {
                                        $update_lead_array = array('lead_status' => 305, 'order_receipt' => $sub_raw_salesorder, "completed_by" => $emp_user_id);
                                        $update_lead = $this->mcommon->common_edit('leads', $update_lead_array, array('id' => $sublead["id"]));
                            
                                        if ($lead_det["lead_parent_id"] != 0) {
                                            $this->db->where('id', $lead_det["lead_parent_id"]);
                                            $this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
                                            $this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
                                            $this->db->update('leads');
                            
                                            // Email process for send the visa process mail
                                            $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'remarks');
                                            $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["lead_parent_id"], 'msd_key' => '66'), 'id');
                                            $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;
                            
                                            $sub_lead_message = "<br><br>Kindly proceed with completing the <strong>" . $service_name . "</strong> for the lead listed below <br>";
                                            $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                                            $sub_lead_message .= "<br><br>Lead Description:<br>";
                                            $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                                            $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                                            $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                                            $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                                            $sub_lead_message .= "<br>Receipt Number: <strong>" . $lead_det["pos_pmt_number"] . "</strong>";
                                            $sub_lead_message .= "<br>Remarks: " . $service_name;
                            
                                            if ($lead_det['msd_key'] == 69) {
                                                array_push($sublead_cc_usermail, ["email" => "Fawziya.h@ontimegov.com", "name" => "Fawziya"]);    // 980422236
                                                array_push($sublead_cc_usermail, ["email" => "Abdulaziz.a@goldencube.ae", "name" => "Abdulaziz Ali"]);    // 2411946200
                                                // array_push($sublead_cc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Abdulaziz Ali"]);
                                                $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Fawziya and Abdulaziz Ali";
                                            }
                            
                                            $email_array = array(
                                                'email' =>  $sublead_cc_usermail,
                                                'cc' => [["name" => "GoldenCube", "email" => "team@goldencube.ae"]],
                                                'subject' => $sub_lead_subject,
                                                'template' => 'mails/template',
                                                'from_name' => "Golden Cube",
                                                'message' => $sub_lead_message,
                                                "branch_id" => 106,
                                            );
                            
                                            $action_by = 178140614;        // info@ontimegroup.com
                                            $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_det["lead_parent_id"], 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                                            $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                            
                                            $send_mail = send_template_email($email_array);
                                            log_message('error', $send_mail);
                                        } else if ($lead_det["id"] != 0 && $lead_det["lead_parent_id"] == 0) {
                                            $this->db->where('id', $lead_det["id"]);
                                            $this->db->set('no_of_closed_subleads', 'no_of_closed_subleads+1', FALSE);
                                            $this->db->set('no_of_open_subleads', 'no_of_open_subleads-1', FALSE);
                                            $this->db->update('leads');
                                        }
                                    }
                                } else if(isset($res_json->ResponseCode) && $res_json->ResponseCode == 1){
                                    if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                                        $res_message = $res_json->ResponseMsg;
                                        $log_insert_array = array('action_id' => 408, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'pos_pmt_response'=>$response, 'action_by' => $emp_user_id, 'status_id' => 304);
                                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                                    }
                                }
                            }
                        }

                        $update_lpd = $this->mcommon->common_edit('lead_package_details', array('paid_status' => 'paid'), 
                            array('lead_id' => $lead_id, 'package_id' => $lead_package_id, 'payment_type' => 'online'));
                            
                       
                    }
                }

                //  comment for testing purpose
                //    if ($lead_det["branch_id"] == 107 || $lead_det["branch_id"] == 6 ||  $lead_det["branch_id"] == 13 ||  $lead_det["branch_id"] == 14 ||  $lead_det["branch_id"] == 20 ||  $lead_det["branch_id"] == 21) {
                //         // array_push($cc_usermail, ["email" => "ilyas.n@ontimegov.com", "name" => "ILYAS NALAKATH VADAKKEKAYIL"]);
                //         array_push($cc_usermail, ["email" => "sandy@ontimebiz.com", "name" => "Sandy Blason Loable"]);
                //         array_push($cc_usermail, ["email" => "khairi@ontimebiz.com", "name" => "Khairi Reda Khairi Mahmoud"]);
                //         //array_push($cc_usermail, ["email" => "muthu.v@egovllc.com"]);
                //         // array_push($cc_usermail, ["email" => "hanna.h@egovllc.com"]);
                //     } else {
                //         // comment for now requested by hanna 3 email users
                //         // array_push($cc_usermail, ["email" => "sibin.d@ontimegroup.com", "name" => "Sibin Daniel"]);
                //         // array_push($cc_usermail, ["email" => "alfred.c@ontimegroup.com", "name" => "Alfred Choorakkattu Antony"]);
                //         // array_push($cc_usermail, ["email" => "muhammed.m@ontimegroup.com", "name" => "Muhammed Mustak Pakkan"]);
                //         array_push($cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey Siega"]);
                //         //array_push($cc_usermail, ["email" => "muthu.v@egovllc.com"]);
                //         // a rray_push($cc_usermail, ["email" => "hanna.h@egovllc.com"]);
                //     }


                // array_push($cc_usermail, ["email" => "muthuvenkatesh808@gmail.com"]);
                if ($csa_usermail != NULL) {
                    array_push($cc_usermail, ["email" => $csa_usermail]);
                }
                // array_push($cc_usermail, ["email" => "sibin.d@ontimegroup.com"]);
                // print_r($cc_usermail);
                // exit();
                $lead = $this->db->where(['id' => $lead_id])->get('leads')->first_row();
                // print_r($lead);

                $update = $this->mcommon->common_edit("leads", array("lead_status" => 310), array("id" => $lead_id));

                if ($lead_det["category_id"] != 125 && !empty($raw_pmtnumber)) {
                    $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("lead_parent_id" => $lead_id));
                }

                if ($lead_det["category_id"] == 125) {
                    $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));
                    $update = $this->mcommon->common_edit("leads", array("pos_salesorder" => $raw_salesorder, "pos_pmt_number" => $raw_pmtnumber, "pos_cust_key" =>  $pos_cust_key), array("lead_parent_id" => $lead_id));
                }

                $template = 'emails/payment_done';
                if ($lead->category_id == 107) {
                    $template = "emails/payment_done";
                    $log_insert_array = array("action_id" => 410, "lead_id" => $lead_id, "action_amount" => $Amount, "remarks" => "Payment Done agianst the Translation Invoice", "action_by" => $customer_id, "status_id" => 305);

                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $updates = $this->mcommon->common_edit("leads", array("status_id" => 305), array("id" => $lead_id));
                }

                if($lead_det['branch_id'] == 109){  // 109 - Tanfeeth
                    array_push($cc_usermail, ["email" => "shyam.s@ontimebiz.com", "name" => "Shyam Sathyan"]); // 1370197719
                    array_push($cc_usermail, ["email" => "Fadlu.R@ontimegroup.com", "name" => "Fadlu Rahman Madathipara"]); // 1007159822
                }

                $details = $this->db->select("lead_package_details.*,count(lead_package_details.lead_package_detail_id) as qty,ontime_category_services_.service_name")->from("lead_package_details")->join("ontime_category_services_", "ontime_category_services_.service_id = lead_package_details.service_id")->where("lead_package_details.lead_id", $lead_id)->group_by("lead_package_details.service_id")->get()->result_array();

                $email_array = array(
                    'name' => $lead_det["invoice_to"] ?? $lead_det["customer_name"],
                    'email' => $lead_det["customer_email"], //$cust_email,
                    'mobile' => $lead_det["customer_mobile"],
                    // 'subject' => 'OnTime Group - Payment Completion - #' . $OrderID,
                    'is_corporate' => $lead_det["is_corporate"],
                    'applicant_name' => $lead_det["applicant_name"],
                    'subject' => 'OnTime Group - Payment Completion - ' . $OrderID .' ##RE-'.trim($lead_det['email_request_id']).'##',
                    'template' => $template,
                    'from_name' => "OnTime Group",
                    'from_email' => "crm@ontimegroup.com",
                    'cc' => $cc_usermail,
                    'message' => $html,
                    "reference" => $payment_insert_id . '-' . $OrderID,
                    "so_order" => ($raw_salesorder == "" || $raw_salesorder == NULL) ? $raw_pmtnumber : $raw_salesorder,
                    "service" => $lead_det["category_code"] . '-' . $lead_det["service_name"],
                    "amount" => $Amount,
                    "branch_id" => $lead_det["branch_id"],
                    'details' => $details,
                    "is_terms_pdf" => true,
                );
                $send_mail = send_lead_template_email($email_array);
                log_message('error', $send_mail);

                // need to change package id
                if($lead_det["branch_id"] == 106 && in_array($lead_det["package_id"], [198,202,203, 199,204,205,258,259,260, 200,201,206,207,208,209,261,262,263, 210,211,212, 222,223,224, 213,214,215,219,220,221,216,217,218, 225,226,227, 228,229,230, 193,194,195, 93,94,106,166,167,168])){ 
                    $insurance_category = '';
                    if(in_array($lead_det["package_id"], [200,201,206,207,208,209,261,262,263])){
                        $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_det["id"]), 'id');
                        $insurance_category = $this->mcommon->specific_row_value('leads', array('id' => $sub_lead_id), 'block_category');
                        if($insurance_category == 'basic'){
                            $subject = 'Golden Cube - Payment Confirmation for Basic Insurance';
                        } else {
                            $subject = 'Golden Cube - Payment Confirmation for Comprehensive Insurance';
                        }
                    } else {
                        $subject = 'Golden Cube - Payment Confirmation';
                    }
                    $package_payment_templates = array(
                        198 => 'mails/gc_block_payment_done',
                        202 => 'mails/gc_block_payment_done',
                        203 => 'mails/gc_block_payment_done',

                        200 => 'mails/gc_insurance_payment_done',
                        201 => 'mails/gc_insurance_payment_done',
                        206 => 'mails/gc_insurance_payment_done',
                        207 => 'mails/gc_insurance_payment_done',
                        208 => 'mails/gc_insurance_payment_done',
                        209 => 'mails/gc_insurance_payment_done',
                        261 => 'mails/gc_insurance_payment_done',
                        262 => 'mails/gc_insurance_payment_done',
                        263 => 'mails/gc_insurance_payment_done',

                        199 => 'mails/gc_valuation_payment_done',
                        204 => 'mails/gc_valuation_payment_done',
                        205 => 'mails/gc_valuation_payment_done',
                        258 => 'mails/gc_valuation_payment_done',
                        259 => 'mails/gc_valuation_payment_done',
                        260 => 'mails/gc_valuation_payment_done',

                        193 => 'mails/gc_taskeen_payment_done',
                        194 => 'mails/gc_taskeen_payment_done',
                        195 => 'mails/gc_taskeen_payment_done',

                        210 => 'mails/gc_titledeed_payment_done',
                        211 => 'mails/gc_titledeed_payment_done',
                        212 => 'mails/gc_titledeed_payment_done',

                        222 => 'mails/gc_translation_payment_done',
                        223 => 'mails/gc_translation_payment_done',
                        224 => 'mails/gc_translation_payment_done',

                        225 => 'mails/gc_attestation_payment_done',
                        226 => 'mails/gc_attestation_payment_done',
                        227 => 'mails/gc_attestation_payment_done',

                        228 => 'mails/gc_shipping_payment_done',
                        229 => 'mails/gc_shipping_payment_done',
                        230 => 'mails/gc_shipping_payment_done',

                        213 => 'mails/gc_updategdrfa_payment_done',
                        214 => 'mails/gc_updategdrfa_payment_done',
                        215 => 'mails/gc_updategdrfa_payment_done',

                        219 => 'mails/gc_replaceeid_payment_done',
                        220 => 'mails/gc_replaceeid_payment_done',
                        221 => 'mails/gc_replaceeid_payment_done',

                        216 => 'mails/gc_updateicp_payment_done',
                        217 => 'mails/gc_updateicp_payment_done',
                        218 => 'mails/gc_updateicp_payment_done',

                        93 => 'mails/gc_visacancel_payment_done',
                        94 => 'mails/gc_visacancel_payment_done',
                        106 => 'mails/gc_visacancel_payment_done',
                        166 => 'mails/gc_visacancel_payment_done',
                        167 => 'mails/gc_visacancel_payment_done',
                        168 => 'mails/gc_visacancel_payment_done'
                    );

                    $gc_message = array(
                        'customer_name' => $lead_det["customer_name"],
                    );
                    
                    if(!empty($package_payment_templates[$lead_det["package_id"]])){
                        $template = $package_payment_templates[$lead_det["package_id"]];
                        $cc = [["email"=>'Team@goldencube.ae']];
                        if($lead_det["is_corporate"] == "Corporate"){
                            $cc = [["email"=>'Corporate@goldencube.ae']];
                        }
                        $gc_email_array = array(
                            'email' => $lead_det["customer_email"],
                            'subject' => $subject, 
                            'template' => $template, 
                            'from_name' => "Golden Cube",
                            'message' => $gc_message,
                            'branch_id' => '106',
                            'cc' => $cc,
                        );
                        $send_mail = send_lead_template_email($gc_email_array);
                        log_message('error', $send_mail);
                    }

                } else {
                    if ($lead_det["branch_id"] == 106){
                        $gc_message = array(
                            'customer_name' => $lead_det["customer_name"],
                        );
                        $cc = [["email"=>'Team@goldencube.ae']];
                        if($lead_det["is_corporate"] == "Corporate"){
                            $cc = [["email"=>'Corporate@goldencube.ae']];
                        }

                        $gc_email_array = array(
                            'email' => $lead_det["customer_email"],
                            'subject' => 'Golden Cube Payment Confirmation', 
                            'template' => 'mails/gc_payment_done', 
                            'from_name' => "Golden Cube",
                            'message' => $gc_message,
                            'branch_id' => '106',
                            'cc' => $cc,
                        );
                        $send_mail = send_lead_template_email($gc_email_array);
                        log_message('error', $send_mail);

                        if(in_array($lead_det['package_id'], [70,109,110, 74,113,114, 154,159,160, 156,163,164])) {
                            $template = in_array($lead_det['package_id'], [70,109,110, 74,113,114]) ? 'mails/gc_family_with_m_rept' : 'mails/gc_family_without_m_rept';
                            $email_array = array(
                                'name' => $lead_det["customer_name"],
                                'email' => $lead_det["customer_email"], //$cust_email,
                                'subject' => 'Next Steps for Your UAE Residence Visa Application',
                                'template' =>  $template,
                                'message' => $gc_message,
                                "branch_id" => '106',
                                'cc' => $cc,
                            );
                            $send_mail = send_lead_template_email($email_array);
                            log_message('error', $send_mail);
                        }
                    }
                }


                if ($lead_det["branch_id"] == 106) {
                    $service_name = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'remarks');
                    $sub_lead_id = $this->mcommon->specific_row_value('leads', array('lead_parent_id' => $lead_id, 'msd_key' => '64'), 'id');

                    if ($service_name != "" && $service_name != null) {
                        $sub_lead_subject = "Complete the transaction for the Lead #" . $sub_lead_id;

                        $sub_lead_message .= "<br>Dear Ishti<br>";
                        $sub_lead_message .= "<br><br>Kindly proceed with completing the <strong> DLD fees </strong> transaction for the lead listed below <br>";
                        $sub_lead_message .= "<br><br>Please login into CRM and go to https://crm.ontimegroup.com/leads/lead/view/" . $sub_lead_id . "<br>";
                        $sub_lead_message .= "<br><br>Lead Description:<br>";
                        $sub_lead_message .= "<br>Customer Name: " . $lead_det["customer_name"];
                        $sub_lead_message .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                        $sub_lead_message .= "<br>Customer Email: " . $lead_det["customer_email"];
                        $sub_lead_message .= "<br>Customer Service: " . $lead_det["category_code"] . " - " . $service_name;
                        $sub_lead_message .= "<br>Receipt Number: <strong>" . $raw_pmtnumber . "</strong>";
                        $sub_lead_message .= "<br>Remarks: " . $service_name;

                        $sub_lead_message .= "<br><br><br><br>Dear Reem<br><br>";
                        $sub_lead_message .= "<br>Please proceed with completing the <strong> DLD Certificate </strong> transaction for the customer once the lead is completed<br><br>";

                        $sublead_cc_usermail = [];
                        // After Payment Completion for DLD Fees
                        // array_push($sublead_cc_usermail, ["email" => "jeffrey.s@goldencube.ae", "name" => "Jeffrey"]);    // 2879029976
                        array_push($sublead_cc_usermail, ["email" => "Dineli.s@goldencube.ae", "name" => "Dineli Sewwandi Gunaratna"]); // 796909261
                        array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                        // array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   // 1143711453

                        $email_array = array(
                            'email' => $sublead_cc_usermail,
                            'cc' => [["name" => "GoldenCube", "email" => "team@goldencube.ae"]],
                            'subject' => $sub_lead_subject,
                            'template' => 'mails/template',
                            'from_name' => "CRM ALERT",
                            'message' => $sub_lead_message,
                            "branch_id" => 106,
                        );

                        $send_mail = send_template_email($email_array);

                        $action_by = 178140614;        // info@ontimegroup.com
                        $email_remarks = "Complete the transaction for the " . $service_name . " service against the lead #" . $sub_lead_id . " - shared the email to Reem and Ishti";
                        $log_insert_array = array('action_id' => 430, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $email_remarks, 'action_by' => $action_by, 'status_id' => 629);
                        $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                        $update_sla_flag_array = ['sla_violation_status' => 'red',];
                        $this->mcommon->common_edit('leads', $update_sla_flag_array, ['id' => $lead_id]);

                        log_message('error', $send_mail);
                    }
                }

                if ($lead_det["branch_id"] == 138) {
                    // $lead_id=152932;
                    $userId = $emp_user_id;
                    // $lead_det = $this->leads_model->lead_details($lead_id);
                    $appointment = $this->db->select("*")->from("calendar_appointment")->where("lead_id", $lead_id)->get()->first_row();
                    $appointment_id = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'id');
                    $booking_id = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'booking_id');
                    $time_slot = $this->mcommon->specific_row_value('calendar_appointment', array('lead_id' => $lead_id), 'booking_timeslot');


                    $log_insert_array = array('appointment_id' => $appointment_id, 'remark' => "Booking Confirmed / Paid Updated", 'created_at' => date('Y-m-d H:i:s'), 'status_code' => 903, 'status_description' => "Payemnt Done Through Online", 'created_by' => $userId, 'updated_at' => date('Y-m-d H:i:s'));
                    $insert_log = $this->mcommon->common_insert('calendar_log', $log_insert_array);

                    $update = $this->mcommon->common_edit("calendar_appointment", array("status" => 903, "updated_at" => date('Y-m-d H:i:s')), array("id" => $appointment_id));

                    $postData = [
                        'id' => $appointment_id,
                        'booking_id' => $booking_id,
                        'status' => 903,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];

                    // Convert the data to JSON format
                    $fields = json_encode($postData);

                    // Initialize cURL session
                    $ch = curl_init();

                    // Set cURL options
                    curl_setopt($ch, CURLOPT_URL, 'https://ontimegov.com/digital/api/v1/baraha/Order/paid');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Accept: application/json',
                        'api-key: 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',
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    // Execute cURL request
                    $response = curl_exec($ch);
                    $error = curl_error($ch);

                    // Close cURL session
                    curl_close($ch);
                    $time = $time_slot;
                    // Step 1: Explode the string by commas to separate the date and times
                    $parts = explode(', ', $time);
                    // Step 2: The first part is the date, and the rest are the time slots
                    $date = $parts[0]; // Extract the date
                    $time_slots = array_slice($parts, 0); // Get all the time slots (excluding the date)
                    // Step 3: Check if time slots are null or empty
                    //if ($time_slots == NULL || empty($time_slots)) {
                    if ($time == NULL) {
                        // If no time slots are provided, set default start and end times in 12-hour format
                        $time_range = '09:00 AM - 08:00 PM'; // 9 AM to 6 PM
                    }
                    // Step 4: Check if there is only one time slot
                    else if (count($time_slots) == 1) {
                        // If only one time slot, calculate the end time as the start time plus one hour
                        $first_time = $time_slots[0];

                        // Convert time to DateTime object
                        $start_time = new DateTime($first_time);

                        // Add one hour to the start time
                        $end_time = clone $start_time;
                        $end_time->modify('+1 hour');

                        // Format time range in 12-hour format with AM/PM
                        $time_range = $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
                    }
                    // Step 5: Handle multiple time slots
                    else {
                        // Get the first and last time slots
                        $first_time = new DateTime($time_slots[0]);
                        $last_time = new DateTime($time_slots[count($time_slots) - 1]);

                        // Format time range in 12-hour format with AM/PM
                        $time_range = $first_time->format('h:i A') . ' - ' . $last_time->format('h:i A');
                    }


                    $baraha_van_cc = [];
                    //     // After Payment Completion for DLD Fees
                    array_push($baraha_van_cc, ["email" => "mobile.medical@ontimegov.com", "name" => "MobileMedical"]);   
                    //     array_push($sublead_cc_usermail, ["email" => "reem.a@goldencube.ae", "name" => "Reem"]);    // 617196601
                    $pos_pmt_number_mail = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'pos_pmt_number');
                    $email_array1 = array(
                        'name' => $lead_det["customer_name"],
                        'email' => $lead_det["customer_email"], //$cust_email,
                        'subject' => 'Appointment Confirmed - Payment Received & Receipt Attached',
                        'template' => 'emails/bv_payment_received',
                        'from_name' => "ONTIME GOV ALERT",
                        'from_email' => 'mobile.medical@ontimegov.com',
                        'cc' => $baraha_van_cc,
                        // 'message' => $html,
                        'booking_reference_number' => $appointment->id ?? 'N/A',
                        'service_name' =>  !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
                        'package_name' =>  !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
                        'date' => $appointment->booking_date  ?? 'N/A',
                        'time' => $time_range  ?? 'N/A',
                        'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
                        'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
                        'pos_pmt_number' => !empty($pos_pmt_number_mail) ? $pos_pmt_number_mail : 'N/A',
                        // 'name' => $user->first_name . $user->last_name,
                    );

                    $send_mail1 = send_template_email($email_array1);
                    log_message('error', $send_mail1);
                    $current_timestamp = date('Y-m-d H:i:s');
                    $action_message1 = "Appointment id " . $appointment->id . " has been Booked and Payment is Done . Amount : " . $appointment->amount . " AED";
                    $log_insert_array = array(
                        'lead_id' => $lead_id,
                        'action_amount' =>  $appointment->amount,
                        'action_id' => 434,
                        'status_id' => 633,
                        'action_by' => $userId,
                        'action_on' => $current_timestamp,
                        'remarks' => '<pre>' . $action_message1 . '</pre>',
                        "bot_id" => 0
                    );
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                    $user = $this->db->where("user_id", $userId)->from("users")->get()->first_row();
                    $email_array = array(
                        'name' => $user->email,
                        'email' => $user->first_name . $user->last_name,
                        // 'name' => "mathan",
                        // 'email' => "mathanraj.g@mitrahsoft.in", //$cust_email,
                        'subject' => 'Appointment Confirmed - Payment Received & Receipt Attached',
                        'template' => 'emails/bv_payment_received',
                        'from_name' => "ONTIME GOV ALERT",
                        'from_email' => 'mobile.medical@ontimegov.com',
                        'cc' => $baraha_van_cc,
                        // 'message' => $html,
                        'booking_reference_number' => $appointment->id ?? 'N/A',
                        'service_name' =>  !empty($appointment->service_info) ? $appointment->service_info : 'N/A',
                        'package_name' =>  !empty($appointment->package_name) ? $appointment->package_name : 'N/A',
                        'date' => $appointment->booking_date  ?? 'N/A',
                        'time' => $time_range  ?? 'N/A',
                        'location' => !empty($appointment->location_url) ? $appointment->location_url : 'N/A',
                        'total_amount' => !empty($appointment->amount) ? $appointment->amount : 'N/A',
                        'pos_pmt_number' => !empty($pos_pmt_number_mail) ? $pos_pmt_number_mail : 'N/A',
                        // 'name' => $user->first_name . $user->last_name,
                    );

                    $send_mail = send_template_email($email_array);
                    log_message('error', $send_mail);

                    $is_exist = $this->mcommon->specific_record_counts('leads', array('lead_parent_id' => $lead_id));

                    if ($is_exist > 2) {
                        $sub_lead_reschedule_data = $this->mcommon->specific_fields_records_all('leads', ["lead_parent_id" => $lead_id, "remarks" => "Reschedule"]);

                        // var_dump($sub_lead_reschedule_data);
                        // exit;
                        foreach ($sub_lead_reschedule_data as $value) {
                            if ($value["remarks"] == "Reschedule" && empty($value["pos_invresponse"]) && empty($value["order_receipt"])) {

                                $url = 'https://crm.ontimegroup.com/api/v1/order/subleadcompleteapi';
                                $getData = [
                                    'code' => $value["id"],
                                    'invoice_id' => $value["is_direct_invoice"],
                                    'from' => "api",
                                    'user_id' => $userId,
                                ];

                                // Append query parameters to URL
                                $urlWithParams = $url . '?' . http_build_query($getData);

                                // Initialize cURL session
                                $curl = curl_init();

                                // cURL options
                                curl_setopt_array($curl, [
                                    CURLOPT_URL => $urlWithParams,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'GET',
                                ]);

                                // Execute the cURL request
                                $response = curl_exec($curl);

                                // Check for cURL errors
                                if (curl_errno($curl)) {
                                    $error_msg = curl_error($curl);
                                    curl_close($curl);
                                    log_message('error', 'cURL Error: ' . $error_msg);
                                    $sub_lead_message = 'Test Mail sublead not closed reschedule' . $error_msg;
                                    $sub_lead_subject .= "The API details Failed sublead reshedule closeing.";
                                    $email_array = array(
                                        'email' =>  'mathanraj.g@mitrahsoft.in',
                                        'subject' => $sub_lead_subject,
                                        'template' => 'mails/template',
                                        'from_name' => "mathan",
                                        'message' => $sub_lead_message,
                                    );
                                    $send_mail = send_template_email($email_array);
                                    // return false; // Handle error appropriately
                                } else {
                                    // Close the cURL session
                                    curl_close($curl);

                                    // Decode JSON response (if applicable)
                                    $responseData = json_decode($response, true);
                                    $sub_lead_message = 'Test Mail sublead  reschedule' . $response;
                                    $sub_lead_subject .= "The API details sucess sublead reshedule completed .";
                                    $email_array = array(
                                        'email' =>  'mathanraj.g@mitrahsoft.in',
                                        'subject' => $sub_lead_subject,
                                        'template' => 'mails/template',
                                        'from_name' => "mathan",
                                        'message' => $sub_lead_message,
                                    );
                                    $send_mail = send_template_email($email_array);

                                    $log_insert_array = array('appointment_id' => $appointment_id, 'remark' => "Booking Confirmed / Closed sub lead", 'created_at' => date('Y-m-d H:i:s'), 'status_code' => 910, 'status_description' => "Payemnt Done Through Online and sublead closed", 'created_by' => $userId, 'updated_at' => date('Y-m-d H:i:s'));
                                    $insert_log = $this->mcommon->common_insert('calendar_log', $log_insert_array);

                                    $update = $this->mcommon->common_edit("calendar_appointment", array("status" => 910, "updated_at" => date('Y-m-d H:i:s')), array("id" => $appointment_id));

                                    $postData = [
                                        'id' => $appointment_id,
                                        'booking_id' => $booking_id,
                                        'status' => 910,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    ];

                                    // Convert the data to JSON format
                                    $fields = json_encode($postData);

                                    // Initialize cURL session
                                    $ch = curl_init();

                                    // Set cURL options
                                    curl_setopt($ch, CURLOPT_URL, 'https://ontimegov.com/digital/api/v1/baraha/Order/paid');
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Accept: application/json',
                                        'api-key: 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',
                                        'Content-Type: application/json'
                                    ]);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_HEADER, false);
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                                    // Execute cURL request
                                    $response = curl_exec($ch);
                                    $error = curl_error($ch);

                                    // Close cURL session
                                    curl_close($ch);
                                }
                            }
                        }
                    }
                }

                if ($lead_det["lead_parent_id"] != NULL) {
                    $this->db->where('id', $lead_det["id"]);
                    $this->db->set('additional_govt_fee', 'additional_govt_fee+' . $Amount, FALSE);
                    $this->db->update('leads');
                }

                header("Location: /payment/success?response=200");
                exit();
            } else {
                // $remove = $this->mcommon->common_delete('paid_pcr_appointments', array('order_id' => $order_id));
                //log_message('error',$response_curl);
                $auth_response = isset($responseData['_embedded']['payment'][0]['authResponse']) ? $responseData['_embedded']['payment'][0]['authResponse']['resultMessage'] : $paymentState;

                $res_message = "Payment Gateway Error: " . $auth_response;
                $log_insert_array = array('action_id' => 445, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'action_by' => $emp_user_id, 'status_id' => 644, 'is_fetch_pay_status' => 1);
                $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);
                
                header("Location: /payment/failure?title=Payment Failure.&desc=Please try again later.&reason=" . $auth_response);
                exit();
            }
        } else {
            //log_message('error',$response_curl);
            //redirect to failure method
            header("Location: /payment/failure?title=POST FAILURE");
            exit();
        }
    }

    public function ontimegov_payment_process()
    {
        if (isset($_POST)) {
            $code = $this->input->get('code');
            $lead_id = $this->input->get('lead_id');
            $action_log_data = $this->mcommon->specific_row('lead_action_log', array('id' => $code));
            $req = $action_log_data['pos_pmt_response'];
            $curl = curl_init();
            curl_setopt_array(
                $curl,
                array(
                    CURLOPT_URL => 'https://ontimesmartpos.net/api/ApiPos/CreatePaymentfromCRM?createso=0',
                    // CURLOPT_URL => 'https://paymentintegration.egovllc.com:8071/api/ApiPos/CreatePaymentfromCRM?createso=0',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $req,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                )
            );

            $response = curl_exec($curl);

            $raw_response = $response;
            // print_r($raw_response);
            // exit;
            $res_json = json_decode($raw_response);
            // print_r($res_json);
            if (isset($res_json->Data->PMT_Number)) {
                $so_order = $res_json->Data->PMT_Number;
                $raw_salesorder = $so_order;
                $pos_cust_key = $res_json->Data->Cust_Key;
                $so_order = "under the payment receipt " . $so_order . "</b>";
            }
            // echo $curl_url;
            if (curl_errno($curl)) {
                $response = json_encode($req) . "<br>" . curl_error($curl);
                // print_r(curl_error($curl));
                curl_close($curl);
            } else {
                $response = json_encode($req) . "<br>" . $response;
                curl_close($curl);
            }
            $update = $this->mcommon->common_edit("leads", array("lead_status" => 312, "pos_pmt_number" => $raw_salesorder, "pos_so_response" => json_encode($req) . "<br>" . $raw_response, "pos_cust_key" =>  $pos_cust_key), array("id" => $lead_id));

            if(isset($res_json->ResponseCode) && $res_json->ResponseCode == 1){
                $lead_det = $this->leads_model->lead_details($lead_id);
                $lead_subject = "Live OntimeCRM - Receipt is not Generated against the OntimeGOV Lead #" . $lead_id;
                
                $lead_message_content = "Dear Team,<br /><br />";
                if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                    $lead_message_content .= "<br>POS Error Message <strong>: " . $res_json->ResponseMsg. "</strong>";
                }

                $lead_message_content .= "<br /><br><br>Lead Details:<br>";
                $lead_message_content .= "<br>Customer Name: " . $lead_det["customer_name"];
                $lead_message_content .= "<br>Customer Country Code: " . $lead_det["customer_country_code"];
                $lead_message_content .= "<br>Customer Contact: " . $lead_det["customer_mobile"];
                $lead_message_content .= "<br>Customer Email: " . $lead_det["customer_email"];
                // $lead_message_content .= "<br>Amount : " . $net_total;
                // $lead_message_content .= "<br>Online Payment Ref: " . $transaction_number;

                $sublead_cc_usermail = [];
                array_push($sublead_cc_usermail, ["email" => "manikandan.tm@mitrahsoft.in", "name" => "Manikandan"]);
                array_push($sublead_cc_usermail, ["email" => "hanna.h@egovllc.com",  "name" => "Hanna"]);   

                $email_array = array(
                    'email' =>  $sublead_cc_usermail,
                    'subject' => $lead_subject,
                    'template' => 'mails/template',
                    'from_name' => "Ontime CRM",
                    'message' => $lead_message_content,
                );

                $send_mail = send_template_email($email_array);
                log_message('error', $send_mail);

                if(isset($res_json->ResponseMsg) && $res_json->ResponseMsg){
                    $res_message = $res_json->ResponseMsg;
                    $log_insert_array = array('action_id' => 446, 'lead_id' => $lead_id, 'action_on' => date('Y-m-d H:i:s'), 'remarks' => $res_message, 'action_by' => 178140614, 'status_id' => 645, 'is_fetch_pay_status' => 1, 'pos_pmt_response' => json_encode($req));
                    $insert_log = $this->mcommon->common_insert('lead_action_log', $log_insert_array);

                    $fetch_pay_url = "https://crm.ontimegroup.com/payment/ontimegov_payment_process?code=".trim($insert_log)."&lead_id=".trim($lead_id);
                    $res_message = $res_json->ResponseMsg. "<br><a target='_blank' href=". $fetch_pay_url ."' class='p-2 pl-4 pr-4 btn btn-primary'>Fetch OntimeGOV Payment Status</a>";
                    $update_log = $this->mcommon->common_edit('lead_action_log', array('remarks' => $res_message), array('id' => $insert_log));
                }

                $res_message = $res_json->ResponseMsg;
                header("Location: /payment/failure?title=Payment Success&desc=Please try again later.&reason=" . $res_message);
                exit();
            }
            header("Location: /payment/success?response=200");
            exit();
        } else {
            //log_message('error',$response_curl);
            //redirect to failure method
            header("Location: /payment/failure?title=POST FAILURE");
            exit();
        }
    }

    public function send_payment_receipt($user_id, $order_id)
    {
        $receiver_email = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'email');
        $receiver_mobile = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'mobile');
        $receiver_name = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'first_name');
        $order_details = $this->mcommon->specific_row('pcr_order', array('pcr_order_id' => $order_id));
        $order_item_details = $this->mcommon->specific_fields_records_all('pcr_order_items', array('pcr_order_id' => $order_id));

        /*print_r($order_details);
        print_r($order_item_details);
        print_r($receiver_email);
        print_r($receiver_name);*/



        $message = "Dear " . $receiver_name . ",<br /><br /> Thank you for choosing Weqayati Smart Medical Examination Center. Your PCR order's payment details as follow as:<br /><br />";
        $message_details = '<strong>Email:</strong>&nbsp; ' . $receiver_email . '<br />';
        $message_details .= '<strong>Mobile:</strong>&nbsp; ' . $receiver_mobile . '<br />';
        $message_details .= '<strong>Sub Category:</strong>&nbsp;' . get_pcr_sub_category_name($order_details["pcr_sub_category_id"]) . '<br /><br />';
        $message_details .= "<table cellpadding='10' width='100%' cellspacing='0' border='1'>";
        $message_details .= "<thead><tr><th>Tracking ID</th><th>Examinee</th><th>Amount</th></tr></thead>";
        $message_details .= "<tbody>";

        foreach ($order_item_details as $key => $value) {
            $message_details .= "<tr><td>PCR" . $value['pcr_order_id'] . "-" . $value['pcr_order_item_id'] . "</td><td>" . $value['examinee_name'] . "</td><td align='right'>AED " . $value['amount'] . "</td></tr>";
            # code...
            $last_order_item_id = $value['pcr_order_item_id'];
        }
        $message_details .= "<tr><td align='right' colspan='2'>Total</td><td align='right'>AED " . $order_details['total_amount'] . "</td></tr>";

        $message_details .= "</tbody></table>";
        $get_slot_id = $this->mcommon->specific_row_value('paid_pcr_appointments', array('examinee_id' => $last_order_item_id), 'timeslot_id');
        $slot_timing = $this->mcommon->specific_row_value('paid_pcr_time_slots', array('id' => $get_slot_id), 'slot_timings');
        $message_details .= "<br /><br />Your appointment is confirmed for " . date('d M Y', strtotime($order_details["preferred_date"])) . ". Your slot timing is: " . $slot_timing;
        $message_details .= "<br /><br />Note: Kindly come to the center before 20 minutes on your appointment for registration process.<br/><br/></br>";
        $message .= $message_details;

        //SEND EMAIL TO CUSTOMER
        $email_array = array(
            'email' => $receiver_email,
            'subject' => 'PCR TEST - Payment Successful - Baraha.ae',
            'template' => 'mails/template',
            'from_name' => 'BARAHA',
            'message' => $message,
        );
        $send_mail = send_template_email($email_array);
        log_message('error', $send_mail);
    }


    public function success()
    {


        $view_data = array();
        // $order_id = $this->input->get('booking_id');
        // if($order_id!='')
        // {
        // 	//get customer id
        // 	$customer_id = $this->session->userdata('customer_id');
        // 	//customer mobile get
        // 	$customer_mobile = $this->mcommon->specific_row_value('free_pcr',array('id'=>$customer_id),'customer_mobile');
        // 	$booking_date = $this->mcommon->specific_row_value('free_pcr_appointments',array('order_id'=>$order_id),'booked_date');
        // 	$booking_time_slot_id = $this->mcommon->specific_row_value('free_pcr_appointments',array('order_id'=>$order_id),'timeslot_id');
        // 	$booking_time_slot = $this->mcommon->specific_row_value('free_pcr_time_slots',array('id'=>$booking_time_slot_id),'slot_timings');
        // 	$examinee_id = $this->mcommon->specific_row_value('free_pcr_order_items',array('order_id'=>$order_id),'id');
        // 	$ref_number = 'FVB'.$order_id.'-'.$examinee_id;
        // 	//send SMS
        // 	$this->load->helper('sms_helper');
        // 	$sms_message = $this->lang->line('booking_confirmation_sms').' '.$booking_date.' '.$booking_time_slot.'. '.$this->lang->line('reference_number_is').' '.$ref_number;
        // 	$customer_mobile =str_replace("+", "", $customer_mobile);
        // 	sendsms($customer_mobile,$sms_message);

        // 	//send data to view
        // 	$view_data['order_data'] = $this->mcommon->specific_fields_records_all('free_pcr_orders',array('id'=>$order_id));
        // 	$view_data['order_item_data'] = $this->mcommon->specific_fields_records_all('free_pcr_order_items',array('order_id'=>$order_id));
        // 	$view_data['appointment_data'] = $this->mcommon->specific_fields_records_all('free_pcr_order_items',array('order_id'=>$order_id));

        // }
        $data = array(
            'page_title' => 'Success',
            'title' => 'Success',
            'content' => $this->load->view('pages/success', $view_data, TRUE),
        );
        //$this->load->view('free/template', $data);
        $this->load->view('template/new_login_template', $data);
    }

    public function failure()
    {

        $view_data = array();
        $view_data['title'] = $this->input->get('title');
        $view_data['desc'] = $this->input->get('desc');
        echo "<!-- ";
        print_r($_GET);
        echo " -->";
        $data = array(
            'page_title' => 'Failure',
            'title' => 'Failure',
            'content' => $this->load->view('pages/failure', $view_data, TRUE),
        );
        //$this->load->view('free/template', $data);
        $this->load->view('template/new_login_template', $data);
    }

    public function zoho_contact()
    {

        $view_data = array();
        $view_data['contactnumber'] = $this->input->get('contactnumber');
        $view_data['contactname'] = $this->input->get('contactname');
        $view_data['contactemail'] = $this->input->get('contactemail');
        echo "<!-- ";
        print_r($_GET);
        echo " -->";
        $data = array(
            'page_title' => 'Zoho Contact',
            'title' => 'Zoho Contact',
            'content' => $this->load->view('pages/zoho_contact', $view_data, TRUE),
        );
        //$this->load->view('free/template', $data);
        $this->load->view('template/new_login_template', $data);
    }
}
