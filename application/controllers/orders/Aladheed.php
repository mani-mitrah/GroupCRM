<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Aladheed extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->model('order_model');
		$this->load->model('user_model');
	}

	public function index()
	{
		if ($this->verify_min_level(1)) 
		{
			
			if($this->auth_user_role==6)
			{
				$view_data['order_details'] = $this->order_model->aladheed_coord_orders();	
			}

			if($this->auth_user_role==2)
			{
				//TODO
				$view_data['order_details'] = $this->order_model->aladheed_csa_orders();	
			}

			$data = array(
				'page_title' => 'Orders',
				'title' => 'Orders',
				'content' => $this->load->view('pages/orders/aladheed/list', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}


	public function assign()
	{
		if ($this->verify_min_level(1)) 
		{
			//if super administrator or coordinator
			if($this->auth_user_role==1 || $this->auth_user_role==6)
			{
				$view_data['unassigned_orders'] = $this->order_model->aladheed_unassigned_orders();
				$view_data['baraha_csas'] = $this->user_model->website_csas(502);
				log_message('error',$this->db->last_query());
				$data = array(
					'page_title' => 'Un-assigned Orders',
					'title' => 'Un-assigned Orders',
					'content' => $this->load->view('pages/orders/aladheed/unassigned_list', $view_data, TRUE),
				);
				$this->load->view('template/base_template_v2', $data);
			}
		}
		else
		{
			redirect('login');
		}
	}
	
	public function view()
	{
		if ($this->verify_min_level(1)) 
		{
			/*if(isset($_POST['submit_mrn']))
			{
				$med_number = $this->input->post('med_number');
				$ref2 = $this->input->post('ref2');
				$item_id = $this->input->post('item_id');

				if($med_number!='' && $ref2!='' && $item_id!='')
				{
					$update_mrn= $this->mcommon->common_edit('order_items',array('med_number'=>$med_number,'ref2'=>$ref2,'item_status'=>102),array('item_id'=>$item_id));
					if($update_mrn)
					{
						//get customer_id
						$order_id = $this->mcommon->specific_row_value('order_items',array('item_id'=>$item_id),'order_id');
						$user_id = $this->mcommon->specific_row_value('orders',array('o_id'=>$order_id),'user_id');

						$customer_name = $this->mcommon->specific_row_value('users',array('user_id'=>$user_id),'first_name');
						$customer_email = $this->mcommon->specific_row_value('users',array('user_id'=>$user_id),'email');
						
						//send email to customer
						$receiver_name = $customer_name;
                        $receiver_email = $customer_email;

                        $message = "Dear ".$receiver_name.",<br /><br /> Medical typing has been done for your order. To proceed to your medical examination please login to your account and book appointment.<br />";
                        
                        $message_details = '<br /><br /><a href="https://crm.ontimegroup.com/appointment">Login to book appointment</a><br /><br />';
                        $message_details .= "<br /><br />As each person’s requirement is different, our team of consultants is available round-the-clock ensuring to be your best and the most well-informed guide.<br /><br />";
                        $message .= $message_details;


                        //SEND EMAIL TO CUSTOMER
                        $email_array = array(
                            'email' => $receiver_email,
                            'subject' => 'Medical Typing Completed - Book Appointment',
                            'template' => 'mails/template',
                            'from_name' => 'BARAHA',
                            'message' => $message,
                        );
                        $send_mail = send_template_email($email_array);
                        log_message('error', $send_mail);
                        if($send_email)
                        {
                        	$this->session->set_flashdata('alert', 'success');
							$this->session->set_flashdata('alert_message','Medical Reference Number has been updated. And en email has been send to the customer for appointment booking.');
                        }
					}
					else
					{
						//send error message to CSA
						$this->session->set_flashdata('alert', 'error');
						$this->session->set_flashdata('alert_message','Unable to update Medical Reference Number');
					}
				}
				else
				{
					$this->session->set_flashdata('alert', 'error');
					$this->session->set_flashdata('alert_message','All fields are required. Please try again');
				}
			}*/
			
			$order_item_id=$_GET['code'];
			$this->load->model('order_model');
		    $view_data['order_details'] = $this->order_model->aladheed_order_item_get($order_item_id);
		    $view_data['order_statuses'] = $this->mcommon->specific_fields_records_all('otg_order_status',array('id>'=>101,'is_active'=>1));
	// 		echo "<pre>";
	// 		print_r($view_data);
	// 		echo "</pre>";
    //   exit();
			$data = array(
				'page_title' => 'Orders',
				'title' => 'Orders',
				'content' => $this->load->view('pages/orders/aladheed/view_order', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}
}