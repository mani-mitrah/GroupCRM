<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

header('Access-Control-Allow-Origin: *');

class Master extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function languages_get()
    {
        $language_array = array();
        $language = array();
        $languages = $this->mcommon->specific_fields_records_all('m_languages',array('is_active'=>1));
        foreach ($languages as $key => $value) 
        {
            $language['title'] = $value['language_name'];
            $language['value'] = $value['id'];  
            array_push($language_array, $language);     
        }
        $this->response($language_array,200);
    }


    public function category_get()
    {
        $category_array = array();
        $category = array();
        $language = $this->get('language');
        if($language=='')
        {
            $error_array = array("Language parameter missing");
            $this->response($error_array,400);
        }

        $categories = $this->mcommon->specific_fields_records_all('m_category',array('is_active'=>1));
        if($language=='1')
        {
            foreach ($categories as $key => $value) 
            {
                $category['title'] = $value['category_name'];
                $category['value'] = $value['id'];  
                array_push($category_array, $category);     
            }
        }

        if($language=='2')
        {
            foreach ($categories as $key => $value) 
            {

                $category['title'] = $value['category_arabic'];
                $category['value'] = $value['id']; 
                array_push($category_array, $category);   
            }
        }
        
        $this->response($category_array,200);
    }

    public function subcategory_get()
    {
        $sub_category_array = array();
        $sub_category = array();
        $language = $this->get('language');
        $category_id = $this->get('category_id');
        if($language=='' || $category_id=='')
        {
            $error_array = array();
            if($language=='')
            {
                array_push($error_array, "Language parameter is missing");
            }

            if($category_id=='')
            {
                array_push($error_array, "Category id is missing");   
            }
            $this->response($error_array,400);
        }

        $sub_categories = $this->mcommon->specific_fields_records_all('m_sub_category',array('is_active'=>1,'main_category'=>$category_id));
        if($language=='1')
        {
            foreach ($sub_categories as $key => $value) 
            {
                $sub_category['title'] = $value['sub_category_name'];
                $sub_category['value'] = $value['id'];  
                array_push($sub_category_array, $sub_category);    
            }
        }

        if($language=='2')
        {
            foreach ($sub_categories as $key => $value) 
            {

                $sub_category['title'] = $value['sub_cat_arabic'];
                $sub_category['value'] = $value['id']; 
                array_push($sub_category_array, $sub_category);   
            }
        }
        
        $this->response($sub_category_array,200);
    }

    public function subsubcategory_get()
    {
        $sub_sub_category_array = array();
        $sub_sub_category = array();
        $language = $this->get('language');
        $category_id = $this->get('category_id');
        $sub_category_id = $this->get('sub_category_id');
        if($language=='' || $category_id=='' || $sub_category_id=='')
        {
            $error_array = array();
            if($language=='')
            {
                array_push($error_array, "Language parameter is missing");
            }

            if($category_id=='')
            {
                array_push($error_array, "Category id is missing");   
            }

            if($sub_category_id=='')
            {
                array_push($error_array, "Sub Category id is missing");   
            }
            $this->response($error_array,400);
        }

        $sub_sub_categories = $this->mcommon->specific_fields_records_all('m_sub_categorytwo',array('is_active'=>1,'main_category'=>$category_id,'sub_category'=>$sub_category_id));
        if($language=='1')
        {
            foreach ($sub_sub_categories as $key => $value) 
            {
                $sub_sub_category['title'] = $value['sub_categorytwo'];
                $sub_sub_category['value'] = $value['id'];  
                array_push($sub_sub_category_array, $sub_sub_category);    
            }
        }

        if($language=='2')
        {
            foreach ($sub_sub_categories as $key => $value) 
            {

                $sub_sub_category['title'] = $value['sub_cattwo_arabic'];
                $sub_sub_category['value'] = $value['id']; 
                array_push($sub_sub_category_array, $sub_sub_category);   
            }
        }
        
        $this->response($sub_sub_category_array,200);
    }

    public function service_get()
    {
        $service_array = array();
        $service = array();
        $language = $this->get('language');
        if($language=='')
        {
            $error_array = array("Language parameter missing");
            $this->response($error_array,400);
        }

        $services = $this->mcommon->specific_fields_records_all('m_service_hours',array('is_active'=>1));
        if($language=='1')
        {
            foreach ($services as $key => $value) 
            {
                $service['title'] = $value['service_hour'];
                $service['value'] = $value['id'];  
                array_push($service_array, $service);     
            }
        }

        if($language=='2')
        {
            foreach ($services as $key => $value) 
            {

                $service['title'] = $value['service_hour_arabic'];
                $service['value'] = $value['id']; 
                array_push($service_array, $service);   
            }
        }
        
        $this->response($service_array,200);
    }

    public function sentence_get()
    {
        $sentence_array = array();
        $language = $this->get('language');
        $chatbot = $this->get('chatbot');

        if($language=='' || $chatbot=='')
        {
            $error_array = array();
            if($language=='')
            {
                array_push($error_array, "Language parameter is missing");
            }

            if($chatbot=='')
            {
                array_push($error_array, "Chatbot id is missing");   
            }
            $this->response($error_array,400);
        }

        $chatbot_id = $this->mcommon->specific_row_value('m_chatbots',array('chatbot_name'=>$chatbot),'id');
        $sentence_texts = $this->mcommon->specific_fields_records_all('m_chatbot_conversations',array('chatbot_id'=>$chatbot_id,'language_id'=>$language));
        foreach ($sentence_texts as $key => $value) 
        {
            $sentence_label = $value['sentence_label'];
            $sentence = $value['sentence'];
            $sentence_array[$sentence_label] = $sentence;
        }
        if(!empty($sentence_array))
        {
            $this->response(array($sentence_array),200);
        }
        else
        {
            $this->response('Nothing found',204);
        }
    }

    public function attachments_get()
    {
        $attachment_array = array();
        $attachments = array();
        $document_array = array();
        $documents = array();
        $language = $this->get('language');
        $category_id = $this->get('category_id');
        $sub_category_id = $this->get('sub_category_id');
        $gender = $this->get('gender');

        if($language=='' || $category_id=='' || $sub_category_id=='' || $gender=='')
        {
            $error_array = array();
            if($language=='')
            {
                array_push($error_array, "Language parameter is missing");
            }

            if($category_id=='')
            {
                array_push($error_array, "Category id is missing");   
            }

            if($sub_category_id=='')
            {
                array_push($error_array, "Sub Category id is missing");   
            }

            if($gender=='')
            {
                array_push($error_array, "Gender is missing");   
            }
            
            $this->response($error_array,400);
        }

        $attachments = $this->mcommon->specific_fields_records_all('attachments',array('category'=>$category_id,'sub_category'=>$sub_category_id,'gender'=>$gender));
        if($language=='1')
        {

            foreach ($attachments as $key => $value) 
            {
                $attachment_count = $value['attachment_number'];
                $attachment_labels = explode(',',$value['labels']);     
            }
            $i=1;
            foreach ($attachment_labels as $key => $value) 
            {
                $documents['docid']=$i;
                $documents['docname']=$value;
                array_push($document_array, $documents);
                $i++;
            }
            $attachment_array['document_count'] = $attachment_count;
            $attachment_array['document_list'] = $document_array; 
        }

        if($language=='2')
        {
            foreach ($attachments as $key => $value) 
            {
                $attachment_count = $value['attachment_number'];
                $attachment_labels = explode(',',$value['labels_arabic']);     
            }
            $i=1;
            foreach ($attachment_labels as $key => $value) 
            {
                $documents['docid']=$i;
                $documents['docname']=$value;
                array_push($document_array, $documents);
                $i++;
            }
            $attachment_array['document_count'] = $attachment_count;
            $attachment_array['document_list'] = $document_array; 
        }
        
        $this->response(array($attachment_array),200);

    }

    public function amount_get()
    {

        $mrf_no = $this->get('mrf_no');

        if($mrf_no=='')
        {
            $this->response('Medical reference number parameter is missing',400);
        }

        $category_id = $this->get('category_id');
        $sub_category_id = $this->get('sub_category_id');
        $sub_sub_category_id = $this->get('sub_sub_category_id');
        $sub_sub_sub_category_id = $this->get('sub_sub_sub_category_id');
        $service_hours = $this->get('service_hours');
        $gender = $this->get('gender');
        $amount = $this->mcommon->specific_row_value('wq_service_amount',array('category'=>$category_id,'sub_category'=>$sub_category_id,'sub_category_two'=>$sub_sub_category_id,'sub_category_three'=>$sub_sub_sub_category_id,'gender'=>$gender,'service_hours'=>$service_hours,'medical_ref'=>$mrf_no),'service_amount'); 

        $final_amount = ($amount!='')?$amount:'0.00';
        $this->response(array(array('amount'=>$final_amount)),200); 
    }

    public function subsubsubcategory_get()
    {
        $sub_sub_category_array = array();
        $sub_sub_category = array();
        $language = $this->get('language');
        $category_id = $this->get('category_id');
        $sub_category_id = $this->get('sub_category_id');
        $sub_sub_category_id = $this->get('sub_sub_category_id');
        
        if($language=='' || $category_id =='' || $sub_category_id == '' || $sub_sub_category_id == '')
        {
            $error_array = array();
            if($language=='')
            {
                array_push($error_array, "Missing parameters");
            }
            $this->response($error_array,400);
        }

        $sub_sub_sub_categories = $this->mcommon->specific_fields_records_all('wq_subcategory_three',array('is_active'=>1, 'main_category_id'=>$category_id,'subcategory_id'=>$sub_category_id,'subcategory_two_id'=>$sub_sub_category_id));

        if($language=='1')
        {
            foreach ($sub_sub_sub_categories as $key => $value) 
            {
                $sub_sub_category['title'] = $value['subcategory_three'];
                $sub_sub_category['value'] = $value['id'];  
                array_push($sub_sub_category_array, $sub_sub_category);    
            }
        }

        if($language=='2')
        {
            foreach ($sub_sub_sub_categories as $key => $value) 
            {

                $sub_sub_category['title'] = $value['subcategory_three_arabic'];
                $sub_sub_category['value'] = $value['id']; 
                array_push($sub_sub_category_array, $sub_sub_category);   
            }
        }
        
        $this->response($sub_sub_category_array,200);
    }

    public function show_preg_questions_get() {
        $category_id = $this->get('category_id');
        $sub_category_id = $this->get('sub_category_id');
        $sub_sub_category_id = $this->get('sub_sub_category_id');
        
        //if individual -> all females except servant
        //if corporate -> all females
        if($category_id=="2") {
            $this->response("true",200);
        } else {
            $sc3_text = $this->mcommon->specific_row_value('wq_subcategory_three',array('main_category_id'=>$category_id,'subcategory_id'=>$sub_category_id,'subcategory_two_id'=>$sub_sub_category_id),'subcategory_three');
            //$this->response(strtoupper($sc3_text),200);
            if(strtoupper($sc3_text)=="NONE") {
                $this->response("true",200);
            }
        }

        $this->response("false",400);
    }
}