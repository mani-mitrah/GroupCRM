<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->library('form_validation');
	}
	
	public function manage()
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();
            $view_data['default'] = $this->mm->company_name();
            $view_data['page_title']="Company";
			$data = array(
				'title' => 'Company',
				'content' => $this->load->view('admin/company/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function add()
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();
			if(isset($_POST['submit']))
			{
				//Receive Values
				$company_name = $this->input->post('company_name');
				$website = $this->input->post('website');
				$industry = $this->input->post('industry');
				
				//Set validation Rules 
				$this->form_validation->set_rules('company_name', 'company Name', 'required');
				$this->form_validation->set_rules('website', 'company website', 'required');
				$this->form_validation->set_rules('industry', 'Industry', 'required');

				//check is the validation returns no error
	            if ($this->form_validation->run() == TRUE)
	            {

	            	  if ($_FILES['company_logo']['name']) {
	                    if (!is_dir('./uploads/images/')) {
	                        mkdir('./uploads/images/', 0777, true);
	                    }
	                    $upload_path = './uploads/images/';
	                    $upload_path_table = base_url() . 'uploads/images/';
	                    $banner = $_FILES['company_logo']['name'];
	                    $expbanner = explode('.', $banner);
	                    $bannerexptype = $expbanner[1];
	                    $date = date('m/d/Yh:i:sa', time());
	                    $rand = rand(10000, 99999);
	                    $encname = $date . $rand;
	                    $bannername = md5($encname) . '.' . $bannerexptype;
	                    $bannerpath = $upload_path . $bannername;
	                    move_uploaded_file($_FILES["company_logo"]["tmp_name"], $bannerpath);
	                    $cover_pic = $upload_path_table . $bannername;
	                } else {
	                    $cover_pic = "";
	                }

	            	//prepare insert array
	            	$insert_array = array(
	            		'company_name'=>$company_name,
	            		'company_logo'=>$cover_pic,
	            		'industry'=>$industry,
	            		'website'=>$website,
	            	);
	            	//insert values in database
	            	$insert = $this->mcommon->common_insert('companies',$insert_array);
	            	
	            	if($insert > '0')
	            	{
	            		$this->session->set_flashdata('alert_success', 'Company added successfully!');
	            		redirect('admin/company/manage');
	            	}
	            	else
	            	{
	            		$this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
	            	}
	            }
	        }
	        $view_data['industry'] = $this->mm->industry_name_active();
	        $view_data['page_title'] = 'Add New Company';
			$data = array(
				'title' => 'Add new Company',
				'content' => $this->load->view('admin/company/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
	
	public function edit($id)
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();


		if(isset($_POST['submit']))
		{

			//Receive Values
			$company_name = $this->input->post('company_name');
			$website = $this->input->post('website');
			$industry = $this->input->post('industry');
			$is_active = $this->input->post('is_active');

			//Set validation Rules 
			$this->form_validation->set_rules('company_name', 'company Name', 'required');

			//check is the validation returns no error
            if ($this->form_validation->run() == TRUE)
            {

            	if ($_FILES['company_logo']['name']) {
                    if (!is_dir('./uploads/images/')) {
                        mkdir('./uploads/images/', 0777, true);
                    }
                    $upload_path = './uploads/images/';
                    $upload_path_table = base_url() . 'uploads/images/';
                    $banner = $_FILES['company_logo']['name'];
                    $expbanner = explode('.', $banner);
                    $bannerexptype = $expbanner[1];
                    $date = date('m/d/Yh:i:sa', time());
                    $rand = rand(10000, 99999);
                    $encname = $date . $rand;
                    $bannername = md5($encname) . '.' . $bannerexptype;
                    $bannerpath = $upload_path . $bannername;
                    move_uploaded_file($_FILES["company_logo"]["tmp_name"], $bannerpath);
                    $cover_pic = $upload_path_table . $bannername;
                } else {
                    $cover_pic = "";
                }
            	//prepare insert array
            	$update_array = array(
            		'company_name'=>$company_name,
            		'company_logo'=>$cover_pic,
            		'industry'=>$industry,
            		'website'=>$website,
            		'is_active'=>$is_active,
            		);
            	//insert values in database
            	$update = $this->mcommon->common_edit('companies',$update_array,array('id'=>$id));

            	if($update > '0')
            	{
            		$this->session->set_flashdata('alert_success', 'company updated successfully!');
            		redirect('admin/company/manage');
            	}
            	else
            	{
            		$this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
            	}
            }
        }
              $view_data['default']=$this->mcommon->specific_row('companies',array('id'=>$id));
              $view_data['industry'] = $this->mm->industry_name_active();
              $view_data['page_title']="Edit Company";
			$data = array(
				'title' => 'Company',
				'content' => $this->load->view('admin/company/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
	public function status_change($id)
	{

		$current_status = $this->mcommon->specific_row_value('companies',array('id'=>$id),'is_active');
		$change_status = ($current_status==1)?0:1;
		$delete = $this->mcommon->common_edit('companies',array('is_active'=>$change_status),array('id'=>$id));
		redirect('admin/company/manage');
	}
}