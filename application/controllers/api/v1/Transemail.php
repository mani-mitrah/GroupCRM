<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require FCPATH . 'vendor/autoload.php';
//https://github.com/sendinblue/APIv3-php-library/issues/76
class Transemail extends REST_Controller 
{

    public function account_get()
    {
    	// Configure API key authorization: api-key
		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', config_item('sib_api_key'));
		// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
		// $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('api-key', 'Bearer');
		// Configure API key authorization: partner-key
		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', config_item('sib_api_key'));
		// Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
		// $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('partner-key', 'Bearer');

		$apiInstance = new SendinBlue\Client\Api\AccountApi(
		    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
		    // This is optional, `GuzzleHttp\Client` will be used as default.
		    new GuzzleHttp\Client(),
		    $config
		);

		try {
		    $result = $apiInstance->getAccount();
		    return $this->response(json_decode($result),200);
		} catch (Exception $e) {
		    return $this->response('Exception when calling AccountApi->getAccount: ', $e->getMessage(), PHP_EOL,500);
		}
    }

    public function welcome_get()
    {
    	$client_name = $this->get('client_name');
    	$client_email = $this->get('client_email');
		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', config_item('sib_api_key'));
		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', config_item('sib_api_key'));
		$apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
		    new GuzzleHttp\Client(),
		    $config
		);
		$data = array('trial_start_date'=>date('d-m-Y'),'trial_end_date'=>date('d-m-Y',strtotime(date('d-m-Y'). ' + 14 days')),'support_email'=>config_item('support_email'),'client_name'=>$client_name,'client_email'=>$client_email);

    	//return $this->load->view('emails/welcome',$data);
		$html_template = $this->load->view('emails/welcome',$data,true);
		$headers = ['Content-Type'=>'text/html', 'charset'=>'iso-8859-1'];
		$dataSmtpEmail['headers'] = $headers;
        $dataSmtpEmail['htmlContent'] = $html_template;
        $dataSmtpEmail['subject'] = "Welcome to ARPAREC";
        $dataSmtpEmail['sender'] = new \SendinBlue\Client\Model\SendSmtpEmailSender([
            'name' =>config_item('from_name'),
            'email' =>config_item('from_email'),
        ]);
        $dataSmtpEmail['replyTo'] = new \SendinBlue\Client\Model\SendSmtpEmailReplyTo([
            'name' =>config_item('from_name'),
            'email' =>config_item('from_email'),
        ]);
        $dataSmtpEmail['to'] = [
            new \SendinBlue\Client\Model\SendSmtpEmailTo(['email' => $data['client_email']])
        ];
		$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail($dataSmtpEmail); // 

		try {
			if(config_item('send_mails'))
			{
			    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
			    return $this->response(json_decode($result),200);
			}
			else
			{
				$result = array("Email sending is disabled");
				return $this->response($result,200);
			}
		} catch (Exception $e) {
			return $this->response($e->getMessage(),404);
		}
    }

    public function otp_get()
    {

    	$client_name = $this->get('client_name');
    	$client_email = $this->get('client_email');
    	$otp = $this->mcommon->specific_row_value('users',array('email'=>$client_email),'otp');
		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', config_item('sib_api_key'));
		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', config_item('sib_api_key'));
		$apiInstance = new SendinBlue\Client\Api\TransactionalEmailsApi(
		    new GuzzleHttp\Client(),
		    $config
		);
		$data = array('trial_start_date'=>date('d-m-Y'),'trial_end_date'=>date('d-m-Y',strtotime(date('d-m-Y'). ' + 14 days')),'support_email'=>config_item('support_email'),'client_name'=>$client_name,'client_email'=>$client_email,'otp'=>$otp);

		$html_template = $this->load->view('emails/otp',$data,true);
		$headers = ['Content-Type'=>'text/html', 'charset'=>'iso-8859-1'];
		$dataSmtpEmail['headers'] = $headers;
        $dataSmtpEmail['htmlContent'] = $html_template;
        $dataSmtpEmail['subject'] = config_item('app_name')." Verification Code";
        $dataSmtpEmail['sender'] = new \SendinBlue\Client\Model\SendSmtpEmailSender([
            'name' =>config_item('from_name'),
            'email' =>config_item('from_email'),
        ]);
        $dataSmtpEmail['replyTo'] = new \SendinBlue\Client\Model\SendSmtpEmailReplyTo([
            'name' =>config_item('from_name'),
            'email' =>config_item('from_email'),
        ]);
        $dataSmtpEmail['to'] = [
            new \SendinBlue\Client\Model\SendSmtpEmailTo(['email' => $data['client_email']])
        ];
		$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail($dataSmtpEmail); // 

		try {
			if(config_item('send_mails'))
			{
				$result = $apiInstance->sendTransacEmail($sendSmtpEmail);	
				return $this->response(json_decode($result),200);
			}
			else
			{
				$result = array("Email sending is disabled");
				return $this->response($result,200);
			}

		} catch (Exception $e) {
			return $this->response($e->getMessage(),404);
		}
    }
}