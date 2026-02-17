<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Auth extends REST_Controller 
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
    }

    public function register_post()
    {
    	if(isset($_POST['agreement']))
    	{
    		$first_name = $this->post('first_name');
	    	$last_name = $this->post('last_name');
	    	$email = $this->post('email');
	    	$password = $this->post('password');
	    	$confirm_password = $this->post('confirm_password');
	    	$company = $this->post('company');
	    	$industry = $this->post('industry');
	    	$country = $this->post('country');
	    	$country_code = $this->post('country_code');
	    	$mobile = $this->post('mobile');
	    	$agreement = $this->post('agreement');
	    	$otp = rand(1000,9999); 
	    	$domain = $this->post('domain');
	    	if($first_name!='' && $last_name!='' && $email!='' && $password!='' && $confirm_password!='' && $company!='' && $industry!='' && $country!='' && $country_code !='' && $mobile!='' && $agreement!='' && $domain!='')
	    	{
	    		$user_array = array('first_name'=>$first_name,
	    						'last_name'=>$last_name,
	    						'country'=>$country,
	    						'company'=>$company,
	    						'country_code'=>'+'.$country_code,
	    						'mobile'=>$mobile,
	    						'otp'=>$otp,
	    						'email'=>$email,
	    						'industry'=>$industry,
	    						'auth_level'=>'8',
	    						'password'=>$this->apimodel->hash_passwd($password),
	    						'package'=>'1',
	    						'domain'=>$domain);
	    		$result = $this->apimodel->register($user_array);
	    		return $this->response($result,200);
	    	}
	    	else
	    	{
	    		return $this->response("Unable to create account right now",500);
	    	}
	    	
    	}
    	else
    	{
    		return $this->response('invalid request');
    	}
    	
    }

    public function checkemail_get()
    {
    	$email = $this->get('email');
    	$check_email = $this->mcommon->specific_record_counts('users',array('email'=>$email));
    	return $this->response($check_email,200);
    }
}