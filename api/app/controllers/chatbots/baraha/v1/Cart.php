<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');

class Cart extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create_get()
    {
    	$user_id = $this->get('user_id');
    	$company_id = 30;
    	$amount = $this->get('amount');
    	$status = 1;
    	$created_date = date('d-m-Y');
    	error_log("create cart:");

    	if($user_id=='' || $amount=='')
        {
            $error_array = array();
            if($user_id=='')
            {
                array_push($error_array, "User id parameter is missing");
            }

            if($amount=='')
            {
                array_push($error_array, "Transaction amount is missing");   
            }
            $this->response($error_array,400);
        }
        $insert_array = array('user_id'=>$user_id,'company_id'=>$company_id,'amount'=>$amount,'status'=>$status,'created_date'=>$created_date);
        $cart_id = $this->mcommon->common_insert('cart',$insert_array);
        if($cart_id > 1)
        {
        	error_log("create cart: success".$cart_id);
        	$this->response(array(array('cart_id'=>$cart_id)),200);
        	error_log("create cart: success".json_encode(array(array('cart_id'=>$cart_id))));
        }
        else
        {
        	error_log("create cart: error");
        	$this->response("Internal server error",500);
        }
    }

    public function add_post()
    {
    	$inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        error_log("ADD: ".print_r($input,true));
        if(empty($input))
        {
        	$this->response('Post data is missing',400);
        }
    	$cart_id = $input['cart_id'];
    	$med_number = $input['mrn_no'];
    	$service_time=$input['service_time'];
    	$amount = $input['amount'];
    	$created_date = date('d-m-Y');
        $category = $input['category_id'];


    	if($cart_id=='' || $med_number=='' || $service_time=='' || $amount=='')
        {
            $error_array = array();
            if($cart_id=='')
            {
                array_push($error_array, "Cart id parameter is missing");
            }

            if($med_number=='')
            {
                array_push($error_array, "Medical reference number boolean  is missing");   
            }

            if($service_time=='')
            {
                array_push($error_array, "Service time is missing");   
            }

            if($amount=='')
            {
                array_push($error_array, "Amount is missing");   
            }
            error_log("loop 1".print_r($error_array,true));
            $this->response($error_array,400);
        }

    	

    	if($med_number==0)
    	{
    		
	    	$sub_category = $input['sub_category_id'];
	    	$sub_category_two = $input['sub_sub_category_id'];
            $sub_category_three = $input['sub_sub_sub_category_id'];
	    	$gender = $input['gender'];
	    	$pregnancy = $input['pregnancy'];
	    	$menstrual_period = $input['menstrual_period'];
	    	$abortion = $input['abortion'];
	    	$contraceptive_pills = $input['contraceptive_pills'];
	    	$x_ray=$input['x_ray'];

	    	if($category=='' || $sub_category=='' || $sub_category_two=='' || $gender=='')
	        {
	            $error_array = array();
	            if($category=='')
	            {
	                array_push($error_array, "Category id parameter is missing");
	            }

	            if($sub_category=='')
	            {
	                array_push($error_array, "Sub category id is missing");   
	            }

	            if($sub_category_two=='')
	            {
	                array_push($error_array, "Sub sub category id is missing");   
	            }

                if($sub_category_three=='')
                {
                    array_push($error_array, "Sub sub sub category id is missing");   
                }

	            if($gender=='')
	            {
	                array_push($error_array, "Gender is missing");   
	            }
	            if($gender==2)
	            {
	            	if($pregnancy=='')
		            {
		                array_push($error_array, "Pregnancy data is missing");   
		            }
		            if($menstrual_period=='')
		            {
		                array_push($error_array, "LMV data is missing");   
		            }
		            if($abortion=='')
		            {
		                array_push($error_array, "Abortion data is missing");   
		            }
		            if($contraceptive_pills=='')
		            {
		                array_push($error_array, "Pills data is missing");   
		            }
		            if($x_ray=='')
		            {
		                array_push($error_array, "X-Ray data is missing");   
		            }
	            }
	            error_log("loop 2");
	            $this->response($error_array,400);
	        }


	    	$attachment_no = $this->mcommon->specific_row_value('attachments',array('category'=>$category_id,'sub_category'=>$sub_category,'gender'=>$gender),'attachment_number');

	    	$cart_insert_array = array(
	    								'cart_id'=>$cart_id,
	    								'med_number'=>$med_number,
	    								'med_number_value'=>'',
	    								'category'=>$category,
	    								'sub_category'=>$sub_category,
	    								'sub_category_two'=>$sub_category_two,
                                        'sub_category_three'=>$sub_category_three,
	    								'gender'=>$gender,
	    								'pregnancy'=>$pregnancy,
	    								'menstrual_period'=>$menstrual_period,
	    								'abortion'=>$abortion,
	    								'contraceptive_pills'=>$contraceptive_pills,
	    								'x_ray'=>$x_ray,
	    								'attachment_no'=>$attachment_no,
	    								'service_time'=>$service_time,
	    								'amount'=>$amount,
	    								'created_date'=>$created_date
	    							   );
	    	$cart_item_id = $this->mcommon->common_insert('cart_items',$cart_insert_array);
            error_log("cart_item_id".$cart_item_id);
	    	if($cart_item_id > 0)
	    	{
                error_log("cart_item_id success");
	    		$cart_data = $this->mcommon->specific_row('cart_items',array('item_id'=>$cart_item_id));
	    		$this->response($cart_data,200);
	    	}
	    	else
	    	{
                error_log("cart_item_id failure");
	    		$this->response("Unable to add to cart",500);	
	    	}
	    	
    	}
    	else
    	{
    		$med_number_value = $input['mrn_no_value'];

    		$cart_insert_array = array(
	    								'cart_id'=>$cart_id,
	    								'med_number'=>$med_number,
                                        'category'=>$category,
	    								'med_number_value'=>$med_number_value,
	    								'service_time'=>$service_time,
	    								'amount'=>$amount,
	    								'created_date'=>$created_date
	    							   );
	    	$cart_item_id = $this->mcommon->common_insert('cart_items',$cart_insert_array);
	    	if($cart_item_id > 0)
	    	{
	    		$cart_data = $this->mcommon->specific_row('cart_items',array('item_id'=>$cart_item_id));
                error_log("CART DATA:".print_r($cart_data,true));
	    		$this->response($cart_data,200);
	    	}
	    	else
	    	{
                error_log("UNABLE TO ADD CART:".print_r($cart_insert_array,true));
	    		$this->response("Unable to add to cart",500);	
	    	}
    	}

    	

    	//dotu need to do this change
    	//once amount is selected dotu need to create a cart and get cart id so that cart_items and cart attachments can be added.
    	
    	
    		
    	//take the insert id which is the cart table increment id and then add to cart items table - cart_items
    		//	item_id -AI
    		//	order_id - cart_id (insert id)
    		//	category - category_id
    		//	sub_category - sub_category_id
    		//	sub_category_two -  sub_sub_category_id
    		//	gender - 1/2
    		//	pregency - 0/1
    		//	menstrual_period - date
    		//	abortion - 0/1
    		//	contraceptive_pills - 0
    		//	x_ray -0/1
    		//	attachment_no
    		//	service_time - service_id
    		//	amount - service_amount
    		//	created_date - date in format dd-mm-yyyy
    		//	
    	// then add to attachment_table
    		// 	order_item_id = cart_id
    		// 	attachment = url of the attachment
    		// 	attachment_date = dd-mm-yyyy
    	
    }
}