<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Document extends REST_Controller {

    function __construct()
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
    }

    public function test_get($value='')
    {
        $this->response(FCPATH . '../uploads',200);
    }

    public function upload_post()
    {
        error_log("INSIDE UPLOADS");
        error_log("files input".print_r(file_get_contents("php://input"),true));
        //error_log("files input".print_r($_FILES,true));

        if(!empty($_FILES))
        {
            error_log("FILENAME: ". $_FILES['file']['name']);
            error_log("POST: ". print_r($_POST,TRUE));
            error_log("FILE: ". print_r($_FILES,TRUE));

            $upload_files_path = FCPATH . '../uploads';
            error_log("UPLOAD PATH: ".$upload_files_path);
            $config['allowed_types'] = 'doc|jpg|pdf|docx|png|svg|jpeg';
            $config['upload_path'] = $upload_files_path;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file'))
            {
                $error = array('error' => $this->upload->display_errors());
                error_log("UPLOAD ERROR: ".print_r($error,TRUE));
                $this->response($error,400);
            }
            else
            {
                $uploaded_data = $this->upload->data();
                error_log("uploaded_data".print_r($uploaded_data,TRUE));
                $cart_id = $this->input->post('cart_item_id');
                $document_id = $this->input->post('document_id');
                $document_name = $this->input->post('document_name');

                //add record to document table
                $document_insert_array = array('cart_item_id'=>$cart_id,
                    'attachment'=>'https://crm.ontimegroup.com/beta/uploads/'.$uploaded_data['file_name'],'attachment_date'=>date('d-m-Y'));
                $document_insert = $this->mcommon->common_insert('cart_attachments',$document_insert_array);
                if($document_insert > 0)
                {
                    $uploaded_document = $this->mcommon->specific_row('cart_attachments',array('a_id'=>$document_insert));
                    $this->response($uploaded_document,200);              
                }
                else
                {
                    error_log("Document upload error".print_r($document_insert_array,TRUE));
                    $this->response('unable to upload document',500);
                }
            }

        }
        else
        {
            error_log("UPLOAD ERROR: ");
           $this->response('post empty',400 ); 
        }
    }

    public function delete_post()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE); //convert JSON into array

        $document_upload_id = $input['document_upload_id'];

        $delete = $this->mcommon->common_delete(config_item('order_document_table'),array('orderdocumentid'=>$document_upload_id));

        if($delete)
        {
            $this->response([array('message'=>'document deleted successfuly')],200);
        }
        else
        {
            $this->response([array('message'=>'unable to delete document')],400);
        }
    }
}

                
