<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');

class Integration extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->library('session');
        $this->load->model('commonauth_model');
    }


    public function customer_get()
    {
    	$order_item_id = $this->get('order_id');

    	if($order_item_id=='')
    	{
    		$this->response("Parameters missing",400);
    	}

    	$order_id = $this->mcommon->specific_row_value('order_items',array('item_id'=>$order_item_id),'order_id'); 
    	if($order_id=='')
    	{
    		$this->response('Unable to find the order',404);
    	}
    	$user_id = $this->mcommon->specific_row_value('orders',array('o_id'=>$order_id),'user_id');
    	
    	$user_data = $this->commonauth_model->specific_row('users',array('user_id'=>$user_id));
    	$customer_data = array('customer_name'=>$user_data['first_name'],'customer_email'=>$user_data['email'],'customer_mobile'=>$user_data['mobile']);
    	$this->response(array($customer_data),200);
    }

    public function refs_get()
    {
    	$ref1 = $this->get('ref1');
    	$ref2 = $this->get('ref2');
    	$order_item_id = $this->get('order_id');
    	if($ref1=='' || $ref2=='')
    	{
    		$this->response("Parameters missing",400);
    	}
    	$check_order_exists = $this->mcommon->specific_record_counts('order_items',array('item_id'=>$order_item_id));
    	if($check_order_exists == 0)
    	{
    		$this->response("Order not found",404);
    	}

    	$update = $this->mcommon->common_edit('order_items',array('med_number'=>$ref1,'ref2'=>$ref2),array('item_id'=>$order_item_id)); 
    	if($update > 0)
    	{
    		$this->response("Reference numbers updated successfully",200);
    	}
    	else
    	{
    		$this->response("Unable to update reference numbers at this moment",500);
    	}
    }

    public function ref1_get()
    {
    	$ref1 = $this->get('ref1');
    	if($ref1=='')
    	{
    		$this->response("Parameters missing",400);
    	}

    	$check_ref1_exists = $this->mcommon->specific_record_counts('wq_mrn',array('mrn_number'=>$ref1));
    	if($check_ref1_exists > 0)
    	{
    		$this->response("Reference number already exists",409);
    	}

    	$update = $this->mcommon->common_insert('wq_mrn',array('mrn_number'=>$ref1)); 
    	if($update >= 1)
    	{
    		$this->response("Reference number inserted successfully",200);
    	}
    	else
    	{
    		$this->response("Unable to insert reference number at this moment",500);
    	}
    }

    public function create_category_post()
    {
        $category_id = $this->post('category_id');
        $category_name = $this->post('category_name');
        $category_code = $this->post('category_code');
        $is_active = '1';

        if($category_id=='' || $category_name=='' || $category_code=='')
        {
            $this->response('Parameters Missing',400);
        }
        //check record exists
        $check = $this->mcommon->specific_record_counts('ontime_categories',array('category_id'=>$category_id));
        if($check > 0)
        {
            $this->response('Category exists already',409);
        }

        //insert category into db
        $insert_array = array('category_id' => $category_id,'category_name' => $category_name,'category_code' => $category_code,'is_active'=>$is_active);
        $insert_category = $this->mcommon->common_insert('ontime_categories',$insert_array);
        if($insert_category > 0)
        {
            $this->response('Category created successfully!',200);
        }
        else
        {
            $this->response('Unable to create category at this moment. Please contact support',500);   
        }
    }

    public function categories_get()
    {
        $categories = $this->mcommon->specific_fields_records_all('ontime_categories',array('is_active'=>1));
        $this->response($categories,200);
    }

    public function update_category_post()
    {
        $category_id = $this->post('category_id');
        $category_name = $this->post('category_name');
        $category_code = $this->post('category_code');
        $is_active = '1';
        if($category_id=='')
        {
            $this->response('You must pass category id to update',400);
        }

        if($category_name=='' && $category_code=='')
        {
           $this->response('You must pass either category name or category code to update',400); 
        }
        //check record exists
        $check = $this->mcommon->specific_record_counts('ontime_categories',array('category_id'=>$category_id));
        if($check == 0)
        {
            $this->response('This category does not exists',204);
        }

        //insert category into db
        $edit_array = array('category_name' => $category_name,'category_code' => $category_code);
        $edit_category = $this->mcommon->common_edit('ontime_categories',$edit_array,array('category_id'=>$category_id));
        if($edit_category)
        {
            $this->response('Category updated successfully!',200);
        }
        else
        {
            $this->response('Unable to update category at this moment. Please contact support',500);   
        }
    }

    public function create_service_post()
    {
        $category_id = $this->post('category_id');
        $service_id = $this->post('service_id');
        $service_name = $this->post('service_name');
        $govt_fee = $this->post('govt_fee');
        $typing_fee = $this->post('typing_fee');
        $is_active = '1';
        if($category_id=='' || $service_id=='' || $service_name=='' || $govt_fee=='' || $typing_fee=='')
        {
            $this->response('Parameters Missing',400);
        }

        //check record exists
        $check = $this->mcommon->specific_record_counts('ontime_category_services_',array('category_id'=>$category_id,'service_id'=>$service_id));
        if($check > 0)
        {
            $this->response('Service exists already',409);
        }

        //insert category into db
        $insert_array = array('category_id' => $category_id,'service_id' => $service_id,'service_name' => $service_name,'govt_fee' => $govt_fee,'typing_fee' => $typing_fee,'is_active'=>$is_active);
        $insert_service = $this->mcommon->common_insert('ontime_category_services_',$insert_array);
        if($insert_service > 0)
        {
            $this->response('Service created successfully!',200);
        }
        else
        {
            $this->response('Unable to create service at this moment. Please contact support',500);   
        }
    }

    public function services_get()
    {
        $category_id = $this->input->get('category_id');
        if($category_id=='')
        {
            $this->response('Category id is missing',400);
        }
        
        $services = $this->mcommon->specific_fields_records_all('ontime_category_services_',array('category_id'=>$category_id,'is_active'=>1));
        $this->response($services,200);
    }

    public function get_category_get()
    {
        $category_id = $this->input->get('category_id');
        if($category_id=='')
        {
            $this->response('Category id is missing',400);
        }
        
        $categories = $this->mcommon->specific_fields_records_all('ontime_categories',array('category_id'=>$category_id));
        $this->response($categories,200);
    }

    public function get_service_get()
    {
        $service_id = $this->input->get('service_id');
        if($service_id=='')
        {
            $this->response('Service id is missing',400);
        }
        $services = $this->mcommon->specific_fields_records_all('ontime_category_services_',array('service_id'=>$service_id,'is_active'=>1));
        $this->response($services,200);
    }

    public function update_service_post()
    {
        $category_id = $this->post('category_id');
        $service_id = $this->post('service_id');
        $service_name = $this->post('service_name');
        $govt_fee = $this->post('govt_fee');
        $typing_fee = $this->post('typing_fee');
        $is_active = '1';
        if($service_id=='')
        {
            $this->response('You must pass service id to update',400);
        }

        if($category_id=='' && $service_name=='' && $govt_fee=='' && $typing_fee=='')
        {
           $this->response('You must pass atleast one field to update',400); 
        }
        //check record exists
        $check = $this->mcommon->specific_record_counts('ontime_category_services_',array('service_id'=>$service_id));
        if($check == 0)
        {
            $this->response('This service does not exists',204);
        }

        //insert category into db
        $edit_array = array('category_id' => $category_id,'service_name' => $service_name,'govt_fee' => $govt_fee,'typing_fee' => $typing_fee);
        $edit_service = $this->mcommon->common_edit('ontime_category_services_',$edit_array,array('service_id' => $service_id,));
        if($edit_service)
        {
            $this->response('Service updated successfully!',200);
        }
        else
        {
            $this->response('Unable to update service at this moment. Please contact support',500);   
        }
    }

    public function create_package_post()
    {
        $package_id = $this->post('package_id');
        $package_name = $this->post('package_name');
        $is_active = '1';
        
        if($package_id=='' || $package_name=='')
        {
            $this->response('Parameters Missing',400);
        }

        //check record exists
        $check = $this->mcommon->specific_record_counts('lead_packages',array('package_id'=>$package_id));
        if($check > 0)
        {
            $this->response('Package exists already',409);
        }

        //insert category into db
        $insert_array = array('package_id' => $package_id,'package_name' => $package_name,'is_active'=>$is_active);
        $package = $this->mcommon->common_insert('lead_packages',$insert_array);
        if($package > 0)
        {
            $this->response('Package created successfully!',200);
        }
        else
        {
            $this->response('Unable to create package at this moment. Please contact support',500);   
        }
    }

    public function update_package_post()
    {
        $package_id = $this->post('package_id');
        $package_name = $this->post('package_name');
        $is_active = '1';
        if($package_id=='' && $package_name=='')
        {
            $this->response('You must pass service id to update',400);
        }

        //check record exists
        $check = $this->mcommon->specific_record_counts('lead_packages',array('package_id'=>$package_id));
        if($check == 0)
        {
            $this->response('This package does not exists',204);
        }

        //insert category into db
        $edit_array = array('package_name' => $package_name);
        $edit_package = $this->mcommon->common_edit('lead_packages',$edit_array,array('package_id' => $package_id,));
        if($edit_package)
        {
            $this->response('Package updated successfully!',200);
        }
        else
        {
            $this->response('Unable to update package at this moment. Please contact support',500);   
        }
    }

    public function add_service_to_package_post($value='')
    {
        $package_id = $this->post('package_id');
        $service_id = $this->post('service_id');
        $govt_fee = $this->post('govt_fee');
        $typing_fee = $this->post('typing_fee');
        $is_active = '1';

        
        if($package_id=='' || $service_id=='' || $govt_fee=='' || $typing_fee=='')
        {
            $this->response('Parameters Missing',400);
        }

        //check record exists
        $check = $this->mcommon->specific_record_counts('lead_package_services',array('package_id'=>$package_id,'service_id'=>$service_id));
        if($check > 0)
        {
            $this->response('Service already mapped to the package',409);
        }

        //insert service and package into db
        $insert_array = array('package_id' => $package_id,'service_id' => $service_id,'govt_fee' => $govt_fee,'typing_fee' => $typing_fee);
        $insert_service = $this->mcommon->common_insert('lead_package_services',$insert_array);
        if($insert_service > 0)
        {
            $this->response('Service added to package successfully!',200);
        }
        else
        {
            $this->response('Unable to map service to the package at this moment. Please contact support',500);   
        }
    }

    public function remove_service_from_package_post($value='')
    {
        $package_id = $this->post('package_id');
        $service_id = $this->post('service_id');

        if($package_id=='' || $service_id=='')
        {
            $this->response('Parameters Missing',400);
        }

        //check record exists
        $check = $this->mcommon->specific_record_counts('lead_package_services',array('package_id'=>$package_id,'service_id'=>$service_id));
        if($check==0)
        {
            $this->response('Service is not mapped to the package',204);
        }
        $delete_array = array('package_id' => $package_id,'service_id' => $service_id);
        $delete = $this->mcommon->common_delete('lead_package_services',$delete_array);
        if($delete)
        {
            $this->response('Service has been remvoed from package',200);
        }
        else
        {
            $this->response('Unable to remove service from the package at this moment. Please contact support',500);   
        }
    }

}