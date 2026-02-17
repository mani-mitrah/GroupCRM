<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');

class Validation extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function email_get()
    {
    	$email = $this->get('email');
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->response(array('message'=>'Invalid Email'),400);         
        }
        else{
            $this->response(array('message'=>'Valid Email'),200); 
        }

    }

    public function mobile_get()
    {
    	$mobile = $this->get('mobile');

        $filtered_phone_number = filter_var($mobile, FILTER_SANITIZE_NUMBER_INT);
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        if (strlen($phone_to_check) < 7 || strlen($phone_to_check) > 14) 
        {
            $this->response(array('message'=>'Invalid mobile'),400);                
        } 
        else 
        {
            $this->response(array('message'=>'Valid mobile'),200);   
        }

    }


    public function password_get()
	{
		$min_chars_for_password = 8;

		$max_chars_for_password = 0;

		$min_digits_for_password = 1;

		$min_lowercase_chars_for_password = 1;

		$min_uppercase_chars_for_password = 1;

		$min_non_alphanumeric_chars_for_password = 0;

		$password = $this->get('password');
		// Password length
		$max = $max_chars_for_password > 0
			? $max_chars_for_password 
			: '';
		$regex = '(?=.{' . $min_chars_for_password . ',' . $max . '})';
		$error = 'Thats not a safe password. Password must have at least ' . $min_chars_for_password . ' characters. ';

		if( $max_chars_for_password > 0 )
			$error .= 'Not more than ' . $max_chars_for_password . ' characters. ';
		
		// Digit(s) required
		if( $min_digits_for_password > 0 )
		{
			$regex .= '(?=(?:.*[0-9].*){' . $min_digits_for_password . ',})';
			$plural = $min_digits_for_password > 1 ? 's' : '';
			$error .= ' '.$min_digits_for_password . ' number' . $plural ;
		}
		
		// Lower case letter(s) required
		if( $min_lowercase_chars_for_password > 0 )
		{
			$regex .= '(?=(?:.*[a-z].*){' . $min_lowercase_chars_for_password . ',})';
			$plural = $min_lowercase_chars_for_password > 1 ? 's' : '';
			$error .= ' '.$min_lowercase_chars_for_password . ' lower case letter' . $plural.' ';
		}
		
		// Upper case letter(s) required
		if( $min_uppercase_chars_for_password > 0 )
		{
			$regex .= '(?=(?:.*[A-Z].*){' . $min_uppercase_chars_for_password . ',})';
			$plural = $min_uppercase_chars_for_password > 1 ? 's' : '';
			$error .= ' '.$min_uppercase_chars_for_password . ' upper case letter' . $plural .' ';
		}
		
		// Non-alphanumeric char(s) required
		if( $min_non_alphanumeric_chars_for_password > 0 )
		{
			$regex .= '(?=(?:.*[^a-zA-Z0-9].*){' . $min_non_alphanumeric_chars_for_password . ',})';
			$plural = $min_non_alphanumeric_chars_for_password > 1 ? 's' : '';
			$error .= ' '.$min_non_alphanumeric_chars_for_password . ' non-alphanumeric character' . $plural .' ';
		}
		
		if( preg_match( '/^' . $regex . '.*$/', $password ) )
		{
			$this->response(TRUE,200);
		}

		$this->response($error,400);
	}
}