<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Verify extends REST_Controller {

    function __construct()
    {
        parent::__construct();
		header("Access-Control-Allow-Origin: *");
    }

    public function otp_post()
    {
    	$otp = $this->post('otp');
    	$user_id = $this->post('user_id');

    	if($otp=='' || $user_id== '')
    	{
    		$error_array = array('status'=>'error','message'=>'Parameters Missing');
            $this->response(array($error_array),400);
    	}
    	else if($user_id !=$this->mcommon->specific_row_value('users',array('user_id'=>$user_id),'user_id'))
    	{
    		$error_array = array('status'=>'error','message'=>'Unable to find the user');
            $this->response(array($error_array),404);
    	}
    	else
    	{
    		$sent_otp = $this->mcommon->specific_row_value('users',array('user_id'=>$user_id),'otp');
    		if($otp==$sent_otp)
    		{
    			$verification_status = $this->mcommon->common_edit('users',array('banned'=>'0','mobile_verified'=>1),array('user_id'=>$user_id));
    			if($verification_status)
    			{
    				$success_array = array('status'=>'success','message'=>'Mobile Number verified successfully');
	            	$this->response(array($success_array),200);    				
    			}
    			else
    			{
    				$error_array = array('status'=>'error','message'=>'Something went wrong. Please try again later');
            		$this->response(array($error_array),500);
    			}
    		}
    		else
    		{
	    		$error_array = array('status'=>'error','message'=>'OTP Mismatch');
	            $this->response(array($error_array),403);
    		}
    	}
    }

    public function email_post()
    {
    	$otp = $this->post('otp');
    	$user_id = $this->post('user_id');

    	if($otp=='' || $user_id== '')
    	{
    		$error_array = array('status'=>'error','message'=>'Parameters Missing');
            $this->response(array($error_array),403);
    	}
    	else if($user_id !=$this->mcommon->specific_row_value('users',array('user_id'=>$user_id),'user_id'))
    	{
    		$error_array = array('status'=>'error','message'=>'Unable to find the user');
            $this->response(array($error_array),403);
    	}
    	else
    	{
    		$sent_otp = $this->mcommon->specific_row_value('users',array('user_id'=>$user_id),'otp');
    		if($otp==$sent_otp)
    		{
    			$verification_status = $this->mcommon->common_edit('users',array('banned'=>'0','mobile_verified'=>1),array('user_id'=>$user_id));
    			if($verification_status)
    			{
    				$success_array = array('status'=>'success','message'=>'Mobile Number verified successfully');
	            	$this->response(array($success_array),200);    				
    			}
    			else
    			{
    				$error_array = array('status'=>'error','message'=>'Something went wrong. Please try again later');
            		$this->response(array($error_array),404);
    			}
    		}
    		else
    		{
	    		$error_array = array('status'=>'error','message'=>'OTP Mismatch');
	            $this->response(array($error_array),403);
    		}
    	}
    }
}