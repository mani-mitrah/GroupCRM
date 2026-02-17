<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Baraha extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
        $this->load->helper('datatables');
        $this->load->model('order_model');
        $this->load->model('user_model');
    }

    public function freepcr()
    {
        $view_data = [];
        if ($this->verify_min_level(1)) {
            $view_data['order_details'] = $this->order_model->free_pcr_orders();
            $data = [
                'page_title' => 'FREE PCR ORDERS',
                'title' => 'FREE PCR ORDERS',
                'content' => $this->load->view('pages/orders/baraha/free_pcr_list', $view_data, true),
            ];
            $this->load->view('template/full_template', $data);
        } else {
            redirect('login');
        }
    }

    public function vaccine()
    {
        $view_data = [];
        if ($this->verify_min_level(1)) {
            $view_data['order_details'] = $this->order_model->free_vaccine_orders();
            $data = [
                'page_title' => 'FREE VACCINE ORDERS',
                'title' => 'FREE VACCINE ORDERS',
                'content' => $this->load->view('pages/orders/baraha/free_vaccine_list', $view_data, true),
            ];
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function vaccine_view()
    {
        $code = $this->input->get('code');
        $view_data = [];
        if ($this->verify_min_level(1)) {
            $view_data['order_details'] = $this->order_model->free_vaccine_order_details($code);
            $data = [
                'page_title' => 'FREE VACCINE ORDERS',
                'title' => 'FREE VACCINE ORDERS',
                'content' => $this->load->view('pages/orders/baraha/free_vaccine_view', $view_data, true),
            ];
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function assign()
    {
        if ($this->verify_min_level(1)) {
            //if super administrator or coordinator
            if ($this->auth_user_role == 1 || $this->auth_user_role > 5) {
                $view_data['unassigned_orders'] = $this->order_model->baraha_unassigned_orders();
                $view_data['baraha_csas'] = $this->user_model->website_csas(501);
                log_message('error', $this->db->last_query());
                $data = [
                    'page_title' => 'Un-assigned Orders',
                    'title' => 'Un-assigned Orders',
                    'content' => $this->load->view('pages/orders/baraha/unassigned_list', $view_data, true),
                ];
                $this->load->view('template/full_template', $data);
            }
        } else {
            redirect('login');
        }
    }

    public function pcr_assign()
    {
        if ($this->verify_min_level(1)) {
            //if super administrator or coordinator
            if ($this->auth_user_role == 1 || $this->auth_user_role > 5) {
                $view_data['unassigned_orders'] = $this->order_model->baraha_unassigned_pcr_orders();
                $view_data['baraha_csas'] = $this->user_model->baraha_pcr_csas();
                log_message('error', $this->db->last_query());
                $data = [
                    'page_title' => 'Un-assigned Enquiries',
                    'title' => 'Un-assigned pEnquiries',
                    'content' => $this->load->view('pages/orders/baraha/unassigned_pcr_list', $view_data, true),
                ];
                $this->load->view('template/full_template', $data);
            }
        } else {
            redirect('login');
        }
    }

    public function index()
    {
        if ($this->verify_min_level(1)) {
            //$view_data['user'] = $this->user();
            //$view_data['users'] = $this->users();
            if ($this->auth_user_role == 1) {
                $view_data['order_details'] = $this->order_model->baraha_admin_orders();
            }

            if ($this->auth_user_role > 5) {
                $view_data['order_details'] = $this->order_model->baraha_coord_orders();
            }

            if ($this->auth_user_role == 2) {
                $view_data['order_details'] = $this->order_model->baraha_csa_orders();
            }

            $data = [
                'page_title' => 'Orders',
                'title' => 'Orders',
                'content' => $this->load->view('pages/orders/baraha/list', $view_data, true),
            ];
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function pcr_index()
    {
        $view_data = [];
        if ($this->verify_min_level(1)) {
            if (isset($_POST['check_appointment'])) {
            }

            if ($this->auth_user_role > 5) {
                $view_data['order_details'] = $this->order_model->baraha_coord_pcr_orders();
                //baraha_csa_pcr_orders();
                $view_data['today_order_details'] = $this->order_model->baraha_coord_pcr_today_orders();
                $view_data['upcoming_order_details'] = $this->order_model->baraha_coord_pcr_upcoming_orders();
                $view_data['completed_order_details'] = $this->order_model->baraha_coord_pcr_completed_orders();
                $view_data['incomplete_order_details'] = $this->order_model->baraha_coord_pcr_incomplete_orders();
                $view_data['unpaid_order_details'] = $this->order_model->baraha_coord_pcr_unpaid_orders();

                //counts
                $view_data['today_total_order'] = count($view_data['today_order_details']);
                $view_data['today_total_examinees'] = count($this->order_model->baraha_coord_pcr_today_examinees());
                $view_data['total_orders'] = $this->mcommon->specific_record_counts('pcr_order', ['payment_id!=' => null]);
                $view_data['completed_orders'] = $this->mcommon->specific_record_counts('pcr_order', ['is_completed' => 1]);
                $view_data['upcoming_orders_count'] = count($view_data['upcoming_order_details']);
		
            }



            if ($this->auth_user_role == 2) {
                $view_data['order_details'] = $this->order_model->baraha_csa_pcr_orders();
                $view_data['today_order_details'] = $this->order_model->baraha_csa_pcr_today_orders();
                $view_data['today_total_examinees'] = count($this->order_model->baraha_csa_pcr_today_examinees());
                $view_data['upcoming_order_details'] = $this->order_model->baraha_csa_pcr_upcoming_orders();
                $view_data['completed_order_details'] = $this->order_model->baraha_csa_pcr_completed_orders();
                $view_data['incomplete_order_details'] = $this->order_model->baraha_csa_pcr_incomplete_orders();
            }

            $data = [
                'page_title' => 'PCR Test Orders',
                'title' => 'PCR Test Orders',
                'content' => $this->load->view('pages/orders/baraha/pcr_test_list', $view_data, true),
            ];
            $this->load->view('template/base_template_modal_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function new()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['med_number'])) {
                $med_number = $this->input->post('med_number');
                $ref2 = $this->input->post('ref2');
                $item_id = $this->input->post('item_id');

                if ($med_number != '' && $ref2 != '' && $item_id != '') {
                    $update_mrn = $this->mcommon->common_edit('order_items', ['med_number' => $med_number, 'ref2' => $ref2], ['item_id' => $item_id]);
                    if ($update_mrn) {
                        //get customer_id
                        $order_id = $this->mcommon->specific_row_value('order_items', ['item_id' => $item_id], 'order_id');
                        $user_id = $this->mcommon->specific_row_value('orders', ['o_id' => $order_id], 'user_id');

                        $customer_name = $this->mcommon->specific_row_value('users', ['user_id' => $user_id], 'first_name');
                        $customer_email = $this->mcommon->specific_row_value('users', ['user_id' => $user_id], 'email');

                        //send email to customer
                        $receiver_name = $customer_name;
                        $receiver_email = $customer_email;

                        $message = 'Dear ' . $receiver_name . ',<br /><br /> Medical typing has been done for your order.Please login to your account and book appointment<br />';
                        /*$message_details = "<table cellpadding='10' width='100%' cellspacing='0' border='0'>";
                        $message_details .= "<tbody>";
                        $message_details .= "<tr><td><strong>Email Address</strong></td><td>".$email_id."</td></tr>";
                        $message_details .= "<tr><td><strong>Password</strong></td><td>".$password."</td></tr>";
                        $message_details .= "<tr><td><strong>Registered Mobile</strong></td><td>+971 ".$mobile_number."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For some financial transactions to verify your email we may ask you for EMAIL OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>Email OTP</strong></td><td>".$user_data['email_otp']."</td></tr>";
                        $message_details .= "<tr><td colspan='2'>For order confirmations sometimes we need to verify your mobile number with OTP.</td></tr>";
                        $message_details .= "<tr><td><strong>MOBILE OTP</strong></td><td>".$user_data['otp']."</td></tr>";
                        $message_details .= "</tbody></table>";*/
                        $message_details = '<br /><br /><a href="https://crm.ontimegroup.com/login">Login to book appointment</a><br /><br />';
                        $message_details .= '<br /><br />As each person’s requirement is different, our team of consultants is available round-the-clock ensuring to be your best and the most well-informed guide.<br /><br />';
                        $message .= $message_details;

                        //SEND EMAIL TO CUSTOMER
                        $email_array = [
                            'email' => $receiver_email,
                            'subject' => 'Medical Typing Completed - Book Appointment',
                            'template' => 'mails/template',
                            'from_name' => 'BARAHA',
                            'message' => $message,
                        ];
                        $send_mail = send_template_email($email_array);
                        log_message('error', $send_mail);
                    } else {
                        //send error message to CSA
                    }
                } else {
                    //send error message
                }
            }

            $view_data['group'] = $this->get_group();
            $view_data['user_services'] = $this->user_lead();
            $view_data['user'] = $this->user();
            $view_data['users'] = $this->users();

            /*$tmpl = array('table_open' => '<table id="orderTableId" cellpadding="2" cellspacing="1" class="table table-striped">');
             $this->table->set_template($tmpl);*/
            //$this->table->set_heading('Lead Details');
            $view_data['dataTableUrl'] = 'orders/baraha/datatable';

            $data = [
                'page_title' => 'Orders',
                'title' => 'Orders',
                'content' => $this->load->view('pages/orders/baraha/view', $view_data, true),
            ];
            $this->load->view('template/full_template', $data);
        } else {
            redirect('login');
        }
    }

    public function view()
    {
        if ($this->verify_min_level(1)) {
            if (isset($_POST['submit_mrn'])) {
                $med_number = $this->input->post('med_number');
                $ref2 = $this->input->post('ref2');
                $item_id = $this->input->post('item_id');

                if ($med_number != '' && $ref2 != '' && $item_id != '') {
                    $update_mrn = $this->mcommon->common_edit('order_items', ['med_number' => $med_number, 'ref2' => $ref2, 'item_status' => 102], ['item_id' => $item_id]);
                    if ($update_mrn) {
                        //get customer_id
                        $order_id = $this->mcommon->specific_row_value('order_items', ['item_id' => $item_id], 'order_id');
                        $user_id = $this->mcommon->specific_row_value('orders', ['o_id' => $order_id], 'user_id');

                        $customer_name = $this->mcommon->specific_row_value('users', ['user_id' => $user_id], 'first_name');
                        $customer_email = $this->mcommon->specific_row_value('users', ['user_id' => $user_id], 'email');

                        //send email to customer
                        $receiver_name = $customer_name;
                        $receiver_email = $customer_email;

                        $message = 'Dear ' . $receiver_name . ',<br /><br /> Medical typing has been done for your order. To proceed to your medical examination please login to your account and book appointment.<br />';

                        $message_details = '<br /><br /><a href="https://crm.ontimegroup.com/appointment">Login to book appointment</a><br /><br />';
                        $message_details .= '<br /><br />As each person’s requirement is different, our team of consultants is available round-the-clock ensuring to be your best and the most well-informed guide.<br /><br />';
                        $message .= $message_details;

                        //SEND EMAIL TO CUSTOMER
                        $email_array = [
                            'email' => $receiver_email,
                            'subject' => 'Medical Typing Completed - Book Appointment',
                            'template' => 'mails/template',
                            'from_name' => 'BARAHA',
                            'message' => $message,
                        ];
                        $send_mail = send_template_email($email_array);
                        log_message('error', $send_mail);
                        if ($send_email) {
                            $this->session->set_flashdata('alert', 'success');
                            $this->session->set_flashdata('alert_message', 'Medical Reference Number has been updated. And en email has been send to the customer for appointment booking.');
                        }
                    } else {
                        //send error message to CSA
                        $this->session->set_flashdata('alert', 'error');
                        $this->session->set_flashdata('alert_message', 'Unable to update Medical Reference Number');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'error');
                    $this->session->set_flashdata('alert_message', 'All fields are required. Please try again');
                }
            }

            $order_item_id = $_GET['code'];
            $this->load->model('order_model');
            $view_data['order_details'] = $this->order_model->order_item_get($order_item_id);
            $view_data['appointments'] = $this->mcommon->specific_row('service_appointment', ['order_item_id' => $order_item_id]);
            $data = [
                'page_title' => 'Orders',
                'title' => 'Orders',
                'content' => $this->load->view('pages/orders/baraha/view_order', $view_data, true),
            ];
            $this->load->view('template/full_template', $data);
        } else {
            redirect('login');
        }
    }

    public function pcr_view()
    {
        if ($this->verify_min_level(1)) {
            $order_id = $_GET['code'];

            if (isset($_POST['update_appointment'])) {
                $timeslot_id = $this->input->post('timeslot_id');
                $appointment_date = $this->input->post('appointment_date');
                $total_examinees = $this->input->post('total_examinees');
                $order_id = $this->input->post('order_id');

                if ($timeslot_id != '' && $appointment_date != '' && $total_examinees != '' && $order_id != '') {
                    //update order table
                    $order_update = $this->mcommon->common_edit('pcr_order', ['preferred_date' => $appointment_date], ['pcr_order_id' => $order_id]);
                    if ($order_update) {
                        //update appointments table
                        $appt_update = $this->mcommon->common_edit('paid_pcr_appointments', ['booked_date' => $appointment_date, 'timeslot_id' => $timeslot_id], ['order_id' => $order_id]);

                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('alert_message', 'Appointment date updated successfully');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Unable to update appointments. Please contact support');
                }
            }

            //order data
            $view_data['order_data'] = $this->mcommon->specific_row('pcr_order', ['pcr_order_id' => $order_id]);

            //examinee data
            $this->load->model('order_model');
            $view_data['examinee_data'] = $this->order_model->paid_examinees($order_id);

            //payment data
            $payment_id = $this->mcommon->specific_row_value('pcr_order', ['pcr_order_id' => $order_id], 'payment_id');
            $view_data['payment_data'] = $this->mcommon->specific_row('payments', ['pid' => $payment_id]);

            //user data
            $user_id = $this->mcommon->specific_row_value('pcr_order', ['pcr_order_id' => $order_id], 'user_id');
            $view_data['customer_data'] = $this->mcommon->specific_row('users', ['user_id' => $user_id]);

            //category data
            $category_id = $this->mcommon->specific_row_value('pcr_order', ['pcr_order_id' => $order_id], 'pcr_category_id');
            $view_data['category_name'] = $this->mcommon->specific_row_value('pcr_categories', ['pcr_cat_id' => $category_id], 'category_name');

            $sub_category_id = $this->mcommon->specific_row_value('pcr_order', ['pcr_order_id' => $order_id], 'pcr_sub_category_id');
            $view_data['sub_category_name'] = $this->mcommon->specific_row_value('pcr_subcategories', ['pcr_cat_id' => $category_id], 'subcategory_name');

            $user_id = $this->mcommon->specific_row_value('pcr_order', ['pcr_order_id' => $order_id], 'user_id');
            $data = [
                'page_title' => 'Orders',
                'title' => 'Orders',
                'content' => $this->load->view('pages/orders/baraha/pcr_view_order', $view_data, true),
            ];
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function datatable_list()
    {
        $this->datatables
            ->select('CONCAT("<strong>",u.first_name,"</strong><br />",u.mobile) as customer,o.o_id as order_id,o.created_date as created_date,o.order_amount,oi.category,oi.gender,oi.item_status,oi.item_id,u.country_code', false)
            ->from('order_items as oi')
            ->join('orders as o', 'o.o_id = oi.order_id', 'left')
            ->join('m_category as c', 'oi.category = c.id', 'left')
            ->join('m_sub_category as sc', 'oi.sub_category = sc.id', 'left')
            ->join('m_sub_categorytwo as ssc', 'oi.sub_category_two = ssc.id', 'left')
            ->join('users as u', 'u.user_id = o.user_id', 'left')
            ->join('m_service_hours as sh', 'oi.service_time = sh.id', 'left');
        $this->db->order_by('oi.item_id', 'DESC');
        //$this->datatables->edit_column('orders', '$1', 'getOrderDetailsList(orders)');
        echo $this->datatables->generate();
    }

    public function datatable()
    {
        //$this->datatables->select('CONCAT(oi.item_id, ",",oi.order_id, ",",COALESCE(oi.med_number,""), ",",COALESCE(oi.ref2,""), ",",oi.category, ",",COALESCE(oi.sub_category,""), ",",COALESCE(oi.sub_category_two,""), ",",COALESCE(oi.gender,""), ",",COALESCE(oi.pregnancy,""), ",",COALESCE(oi.menstrual_period,""), ",",COALESCE(oi.abortion,""), ",",COALESCE(oi.contraceptive_pills,""), ",",COALESCE(oi.x_ray,""), ",",COALESCE(oi.attachment_no,""), ",",COALESCE(oi.service_time,""), ",",oi.amount, ",",oi.created_date, ",",oi.source, ",",oi.remarks, ",",oi.created_time, ",",o.o_id, ",",o.user_id, ",",o.company_id, ",", ",",o.status, ",",o.priority, ",",c.category_name, ",",sc.sub_category_name, ",",ssc.sub_categorytwo, ",",sh.service_hour, ",",u.first_name, ",",u.mobile, ",") as orders', FALSE)
        $this->datatables
            ->select('CONCAT(u.first_name,",",u.mobile,",",o.o_id,",",o.created_date,",",o.order_amount,",",oi.category,",",oi.gender,",",oi.item_status,",",oi.item_id,",",u.country_code) as orders', false)
            ->from('order_items as oi')
            ->join('orders as o', 'o.o_id = oi.order_id', 'left')
            ->join('m_category as c', 'oi.category = c.id', 'left')
            ->join('m_sub_category as sc', 'oi.sub_category = sc.id', 'left')
            ->join('m_sub_categorytwo as ssc', 'oi.sub_category_two = ssc.id', 'left')
            ->join('users as u', 'u.user_id = o.user_id', 'left')
            ->join('m_service_hours as sh', 'oi.service_time = sh.id', 'left');
        $this->db->order_by('oi.item_id', 'DESC');
        $this->datatables->edit_column('orders', '$1', 'getOrderDetailsArr(orders)');
        echo $this->datatables->generate();
    }

    public function get_group_users()
    {
        $group_id = $this->input->post('group_id');
        $this->db->select('*');
        $this->db->from('group_users as gu');
        $this->db->join('users as u', 'u.user_id=gu.user_id');
        $this->db->where('gu.group_id', $group_id);
        $query = $this->db->get();
        $result = $query->result();
        echo json_encode($result);
    }

    public function get_group()
    {
        $this->db->select('*');
        $this->db->from('group_master');
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function user_lead()
    {
        $this->db->select('*,o.user_id as order_user,o.created_date as c_date');
        $this->db->from('orders as o');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        $this->db->join('order_items as oi', 'oi.order_id=o.o_id');
        $this->db->join('order_attachments as oa', 'oa.order_item_id=oi.item_id');
        $this->db->join('m_category as c', 'c.id=oi.category');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as h', 'h.id=oi.service_time');

        $query = $this->db->get()->result();
        $img = [];
        foreach ($query as $q) {
            $img[] = $q->attachment;
        }

        $this->db->select('*,o.user_id as order_user,o.created_date as c_date');
        $this->db->from('orders as o');
        //$this->db->join("users as u","u.user_id=o.user_id");
        $this->db->join('order_items as oi', 'oi.order_id=o.o_id');
        //$this->db->join("order_attachments as oa","oa.order_item_id=oi.item_id");
        $this->db->join('m_category as c', 'c.id=oi.category');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as h', 'h.id=oi.service_time');

        $result = $this->db->get()->result();
        foreach ($result as $r) {
            $data[] = [
                'img' => $img,
                'user_id' => $r->order_user,
                // 'mobile'=>$r->mobile,
                // 'email'=>$r->email,
                'order_id' => $r->o_id,
                'item_id' => $r->item_id,
                'category_name' => $r->category_name,
                'sub_category_name' => $r->sub_category_name,
                'sub_categorytwo' => $r->sub_categorytwo,
                'gender' => $r->gender,
                'service_hour' => $r->service_hour,
                'amount' => $r->amount,
                'c_date' => $r->c_date,
                'priority' => $r->priority,
            ];
        }

        return $data;
    }

    public function users()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('auth_level', 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function assign_user()
    {
        $service_id = $this->input->post('service_id');
        $assigned_user = $this->input->post('users');
        $group_id = $this->input->post('group');
        $date = date('d-m-Y  H:i:s');
        $insert_array = [
            'order_id' => $service_id,
            'assigned_user' => $assigned_user,
            'group_id' => $group_id,
            'status' => 1,
            'created_date' => date('d-m-Y'),
            'created_time' => date('h:i A', strtotime($date)),
        ];
        // print_r($insert_array);
        // die();
        $insert = $this->db->insert('assigned_order', $insert_array);
        if ($insert) {
            $this->session->set_flashdata('alert_success', 'user assigned successfully');
        } else {
            $this->session->set_flashdata('alert_danger', 'user not assigned ');
        }
        redirect('leads');
    }

    public function set_priority()
    {
        // print_r($_POST);
        // die();
        $enquiry_id = $this->input->post('enquiry_id');
        $priority = $this->input->post('priority');
        $update_array = [
            'priority' => $priority,
        ];
        $this->db->where('o_id', $enquiry_id);
        $update = $this->db->update('orders', $update_array);
        if ($update) {
            $this->session->set_flashdata('alert_success', 'Priority set successfully');
        } else {
            $this->session->set_flashdata('alert_danger', 'user not assigned ');
        }
        redirect('leads');
    }

    public function user()
    {
        $this->db->select('o.user_id');
        $this->db->from('orders as o');
        $res = $this->db->get()->result();
        foreach ($res as $r) {
            $user_id = $r->user_id;
        }

        //print_r($_SESSION['services']);exit('aaaa');
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $hostname = 'localhost';
            $username = 'chatbot_user';
            $password = 'chatbot_user';
            $database = 'ontimedigital_oneauth';
            $environment = 'development';
        } else {
            $hostname = 'localhost';
            $username = 'chatbot_user';
            $password = '5700d$A4';
            $database = 'ontimedigital_oneauth';
            $environment = 'development';
        }

        // Create connection
        $conn = new mysqli($hostname, $username, $password, $database);
        // Check connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }
        //$user_id=3724832765;
        $sql = 'SELECT * FROM users WHERE user_id=' . $user_id;
        $result = $conn->query($sql);

        if (!empty($result)) {
            while ($row1 = $result->fetch_assoc()) {
                $f_name = $row1['first_name'];
                return $f_name;
            }
        }
    }

    public function view_orders($id)
    {
        $this->db->select('*,o.user_id as order_user,o.created_date as c_date');
        $this->db->from('orders as o');
        // $this->db->join("users as u", "u.user_id=o.user_id");
        $this->db->join('order_items as oi', 'oi.order_id=o.o_id');
        $this->db->join('order_attachments as oa', 'oa.order_item_id=oi.item_id');
        $this->db->join('m_category as c', 'c.id=oi.category');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as h', 'h.id=oi.service_time');
        $this->db->where('oi.item_id', $id);
        $query = $this->db->get()->result();
        $img = [];
        foreach ($query as $q) {
            $img[] = $q->attachment;
        }

        // print_r($query);
        // die();
        $this->db->select('*,o.user_id as order_user,o.created_date as c_date');
        $this->db->from('orders as o');
        //$this->db->join("users as u","u.user_id=o.user_id");
        $this->db->join('order_items as oi', 'oi.order_id=o.o_id');
        //$this->db->join("order_attachments as oa","oa.order_item_id=oi.item_id");
        $this->db->join('m_category as c', 'c.id=oi.category');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as h', 'h.id=oi.service_time');
        $this->db->where('oi.item_id', $id);
        $result = $this->db->get()->result();
        foreach ($result as $r) {
            $data[] = [
                'img' => $img,
                'user_id' => $r->order_user,
                'order_id' => $r->o_id,
                'med_number' => $r->med_number,
                'pregnancy' => $r->pregnancy,
                'menstrual_period' => $r->menstrual_period,
                'abortion' => $r->abortion,
                'contraceptive_pills' => $r->contraceptive_pills,
                'x_ray' => $r->x_ray,
                'source' => $r->source,
                'remarks' => $r->remarks,
                'category_name' => $r->category_name,
                'sub_category_name' => $r->sub_category_name,
                'sub_categorytwo' => $r->sub_categorytwo,
                'gender' => $r->gender,
                'service_hour' => $r->service_hour,
                'amount' => $r->amount,
                'c_date' => $r->c_date,
                'created_time' => $r->created_time,
            ];
        }
        $view_data['data'] = $data;
        $view_data['payment_details'] = $this->payment_details($id);
        $data = [
            'page_title' => 'Leads',
            'title' => 'Leads',
            'content' => $this->load->view('pages/baraha/orders/details_new', $view_data, true),
        ];
        $this->load->view('template/base_template', $data);
    }

    public function payment_details($id)
    {
        $this->db->select('*');
        $this->db->from('payments');
        $this->db->where('order_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function check_appointment()
    {
        if (isset($_POST['appointment_date'])) {
            $service_id = 1003;
            $appointment_date = $this->input->post('appointment_date');
            $total_examinees = $this->input->post('total_examinees');

            /**
             *  APPOINTMENT LOGIC
             */

            #Step:1 find total examinees
            $dayofweek = date('N', strtotime($appointment_date));
            $doc_availability_fn = $this->mcommon->specific_row_value('paid_pcr_doc_availability', ['day_of_week' => $dayofweek, 'day_session' => 1, 'service_id' => $service_id], 'total_avl_appointments');
            $doc_availability_an = $this->mcommon->specific_row_value('paid_pcr_doc_availability', ['day_of_week' => $dayofweek, 'day_session' => 2, 'service_id' => $service_id], 'total_avl_appointments');

            //$afternoon_appt_count =

            $time_slots = $this->mcommon->specific_fields_records_all('paid_pcr_time_slots', ['is_active' => 1]);

            $forenoon_array = [];
            $afternoon_array = [];
            foreach ($time_slots as $key => $value) {
                if ($value['day_session'] == 1) {
                    $timeslot_id = $value['id'];
                    $slot_timings = $value['slot_timings'];

                    //check existing appointments in this slot
                    $existing_appointments = $this->mcommon->specific_record_counts('paid_pcr_appointments', ['timeslot_id' => $timeslot_id, 'booked_date' => date('Y-m-d', strtotime($appointment_date))]);
                    $available_appts = $doc_availability_fn - $existing_appointments;

                    $fn_array = [];
                    $fn_array['timeslot_id'] = $timeslot_id;
                    $fn_array['slot_timing'] = $slot_timings;
                    $fn_array['appt_date'] = $appointment_date;
                    $fn_array['availability'] = $available_appts >= $total_examinees ? 'Available(' . $available_appts . ')' : 'Not Available';
                    $fn_array['color'] = $available_appts >= $total_examinees ? 'success' : 'danger';
                    array_push($forenoon_array, $fn_array);
                }

                if ($value['day_session'] == 2) {
                    $timeslot_id = $value['id'];
                    $slot_timings = $value['slot_timings'];

                    //check existing appointments in this slot
                    $existing_appointments = $this->mcommon->specific_record_counts('paid_pcr_appointments', ['timeslot_id' => $timeslot_id, 'booked_date' => date('Y-m-d', strtotime($appointment_date))]);
                    $available_appts = $doc_availability_an - $existing_appointments;
                    $an_array = [];
                    $an_array['timeslot_id'] = $timeslot_id;
                    $an_array['slot_timing'] = $slot_timings;
                    $an_array['appt_date'] = $appointment_date;
                    $an_array['availability'] = $available_appts >= $total_examinees ? 'Available(' . $available_appts . ')' : 'Not Available';
                    $an_array['color'] = $available_appts >= $total_examinees ? 'success' : 'danger';
                    array_push($afternoon_array, $an_array);
                }
            }

            $result_array = ['forenoon' => $forenoon_array, 'afternoon' => $afternoon_array];
            echo json_encode($result_array);
        }
    }

    public function test()
    {
        $this->load->model('order_model');
        $orders = $this->order_model->test();
        //echo json_encode($orders);
        echo count($orders) . '<br />';
        echo date('Y-m-d') . '<br />';
        foreach ($orders as $key => $value) {
            echo $value['pcr_order_id'] . ' - ' . $value['preferred_date'] . ' - ' . $value['is_completed'] . ' - ' . $value['slot_timings'] . '<br />';
        }
    }

    public function completion()
    {
        try {
            if (isset($_POST)) {
                if (isset($_POST['code'])) {
                    $pcr_id = $_POST['code'];
                    $pcr = $this->db
                        ->select('pcr_order.*,pcr_categories.category_name,pcr_subcategories.subcategory_name,payments.approval_code')
                        ->from('pcr_order')
                        ->join('pcr_categories', 'pcr_categories.pcr_cat_id = pcr_order.pcr_category_id')
                        ->join('pcr_subcategories', 'pcr_subcategories.pcr_subcat_id = pcr_order.pcr_sub_category_id')
                        ->join('payments', 'payments.pid = pcr_order.payment_id')
                        ->where('pcr_order_id', $pcr_id)
                        ->get()
                        ->first_row();
                    if ($pcr->is_completed == 0 || $pcr->is_completed == 1) {
                        $customer = $this->db
                            ->select('*')
                            ->from('users')
                            ->where('user_id', $pcr->user_id)
                            ->get()
                            ->first_row();

                        // print_r($pcr);
                        // exit();payment_Type
                        $curl = curl_init();

                        curl_setopt_array($curl, [
                            CURLOPT_URL => 'http://185.56.89.101:1001/api/Authentication/Login',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => '{
                                "username": "CRMONLINE",
                                "password": "2424"
                            }',
                            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
                        ]);

                        $response = curl_exec($curl);

                        curl_close($curl);
                        $token = json_decode($response);
                        $token_status = $token->isSuccess;
                        if ($token_status) {
                            $token = $token->data->token;
                            $req = [];
                            $req['customer']['name'] = $customer->first_name . ' ' . $customer->last_name;
                            $req['customer']['phoneNumber'] = $customer->mobile;
                            $req['customer']['email'] = $customer->email;

                            $req['sloHeader'] = [
                                'remarks' => 'PCR-' . $pcr->pcr_order_id . ' - ' . $pcr->category_name . ' - ' . $pcr->subcategory_name,
                                'isEDirham' => false,
                                'tokenNo' => '',
                                'is_CrossSales' => false,
                                'is_OnlineSales' => true,
                                "payment_Type" => "ONLINE",
                                'ordeRefOnline' => $pcr->approval_code,
                            ];

                            $req['sloDetail'] = [];

                            $pcr_details = $this->db
                                ->select('*')
                                ->from('pcr_order_items')
                                ->where('pcr_order_id', $pcr->pcr_order_id)
                                ->get()
                                ->result_array();
                            foreach ($pcr_details as $pcr_detail) {
                                $govt_fee = 0;
                                if ($pcr_detail['amount'] > 100) {
                                    $govt_fee = 50;
                                }
                                $actual_amount = $pcr_detail['amount'] - $govt_fee;
                                $tax = round((($actual_amount*5)/105),2);
                                $actual_amount = $actual_amount - $tax;
                                $tmp_details = [
                                    'servicename' => $pcr->category_name . ' - ' . $pcr->subcategory_name,
                                    'servicenamear' => $pcr->category_name . ' - ' . $pcr->subcategory_name,

                                    'fees' => 0,
                                    'typing_Amnt' => 0,
                                    'services_Amnt' => 0,
                                    'disc_Amnt' => 0,
                                    'edirham_Amnt' => 0,

                                    'addFees_Amnt' => $govt_fee,
                                    'addRev_Amnt' => $actual_amount,
                                    'tax_Amnt' => $tax,
                                    'ref_no' => 'PCR-' . $pcr->pcr_order_id . '-' . $pcr_detail['pcr_order_item_id'],
                                    'ref_no1' => $pcr_detail['pcr_order_item_id'],
                                    'card_Amnt' => 0,
                                ];
                                array_push($req['sloDetail'], $tmp_details);
                            }

                            $req['sloPayments'] = [
                                'ref_No' => 'PCR-' . $pcr->pcr_order_id,
                                'amount' => $pcr->total_amount,
                                'remarks' => $pcr->category_name . ' - ' . $pcr->subcategory_name,
                                'token' => $pcr->pcr_order_id,
                                'interCompany' => true,
                                'actualAmt' => $pcr->total_amount,
                                'cheque_Date' => '',
                                'eidNumber' => '',
                            ];

                            // print_r(json_encode($req));
                            // // return;
                            // exit();

                            $curl = curl_init();

                            curl_setopt_array($curl, [
                                CURLOPT_URL => 'http://185.56.89.101:1001/api/SmartOrder2022/Order',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => json_encode($req),
                                CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Type: application/json'],
                            ]);

                            $response = curl_exec($curl);

                            curl_close($curl);
                            // echo $response;

                            $json_response = json_decode($response);
                            if ($json_response->isSuccess) {
                                // echo $json_response->data->sliHeader->docPrefix.$json_response->data->sliHeader->cust_Key;

                                $this->mcommon->common_edit('pcr_order', ['pcr_status' => 1, 'is_completed' => 1, 'so_api_response' => $response, 'so_api_called_at' => date('Y-m-y H:i:s'), 'so_api_called_by' => $this->auth_user_id, 'so_invoice' => $json_response->data->sliHeader->docPrefix . $json_response->data->sliHeader->headnum], ['pcr_order_id' => $pcr_id]);

                                $this->mcommon->common_edit('pcr_order_items', ['is_completed' => 1], ['pcr_order_id' => $pcr_id]);

                                // $this->db->where(["pcr_order_id" => $pcr_id])->update("pcr_order",["pcr_status"=>1,"is_completed"=>1,"so_api_response"=>$response,"so_api_called_at"=>date("Y-m-y H:i:s"),"so_api_called_by"=>$this->auth_user_id,"so_invoice"=> $json_response->data->sliHeader->docPrefix.$json_response->data->sliHeader->cust_Key]);
                                // $this->db->where(["pcr_order_id" => $pcr_id])->update("pcr_order_items",["is_completed"=>1]);

                                echo json_encode(['status' => 'true', 'message' => 'PCR Order marked as completed and Invoice Generated.', 'request_json' => json_encode($req)]);
                                exit();
                            } else {
                                // echo "Failed";
                                // echo $response;
                                echo json_encode(['status' => 'false', 'message' => 'Something Went Wrong. Please try after sometime.', 'request_json' => json_encode($req), 'api_response' => $response]);
                                exit();
                            }
                        } else {
                            echo json_encode(['status' => 'false', 'message' => 'Authentication Failed. Contact System Administrator.']);
                            exit();
                        }
                    } else {
                        echo json_encode(['status' => 'true', 'message' => 'PCR Order already marked as completed.']);
                        exit();
                    }
                    // print_r($pcr);
                    // echo json_encode(["status"=>"true","message"=>"PCR Order marked as completed and Invoice Generated."]);
                } else {
                    echo json_encode(['status' => 'false', 'message' => "'code' parameter is missing."]);
                    exit();
                }
            } else {
                echo json_encode(['status' => 'false', 'message' => 'Method not support. POST method required']);
                exit();
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'false', 'message' => 'Exception Occured. Contact System Administrator.', 'exception' => $e->getMessage()]);
            exit();
        }
    }

    public function getinvoice()
    {
        try {
            if (isset($_GET['code'])) {
                $pcr = $this->db
                    ->select('*')
                    ->from('pcr_order')
                    ->where('pcr_order_id', $_GET['code'])
                    ->get()
                    ->first_row();

                if ($pcr) {
                    // print_r($pcr);
                    $curl = curl_init();

                    curl_setopt_array($curl, [
                        CURLOPT_URL => 'https://verify.ontime-pos.com/POSInvoice/Login?username=admin',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ]);

                    $response = curl_exec($curl);

                    curl_close($curl);
                    $res = json_decode($response);

                    $token = $res->Token;

                    $curl = curl_init();

                    $curl_url = 'https://verify.ontime-pos.com/POSInvoice/GetPaidStatus/' . $pcr->so_invoice;
                    // echo $curl_url;
                    // echo "<br>";
                    // echo $token;

                    curl_setopt_array($curl, [
                        CURLOPT_URL => $curl_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token, 'Content-Length: 0'],
                    ]);

                    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 50000'));



                    // curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=utf-8"));


                    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: 0'));


                    $response = curl_exec($curl);

                    curl_close($curl);
                    $res = json_decode($response);
                    // echo "<br>";
                    // echo "There: ".$response;
                    // print_r(curl_error($curl));
                    // exit();
                    if (!$res->Url) {
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PCR Order');
                        return redirect('/orders/baraha/pcr_index#completed');
                    }
                    // print_r($res);

                    // if($res->Status)
                    header("location: " . $res->Url);

                    if (curl_error($curl)) {
                        // echo curl_error($curl);
                        $this->session->set_flashdata('alert', 'danger');
                        $this->session->set_flashdata('alert_message', 'Exception: ' . curl_error($curl));
                        return redirect('/orders/baraha/pcr_index#completed');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'PCR Order doesnot exist for fetch invoice.');
                    return redirect('/orders/baraha/pcr_index#completed');
                }
            } else {
                $this->session->set_flashdata('alert', 'danger');
                $this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PCR Order');
                return redirect('/orders/baraha/pcr_index#completed');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('alert', 'danger');
            $this->session->set_flashdata('alert_message', 'Cannot fetch invoice for the PCR Order');
            return redirect('/orders/baraha/pcr_index#completed');
        }
    }


    public function pcr_date_wise()
    {
        $date = NULL;
        if (isset($_POST["date"])) {
            $date = $_POST["date"];
        }
        $view_data['preferred_date'] = $date;
        $view_data['today_order_details'] = $this->order_model->baraha_coord_pcr_today_orders($date);
        $data = [
            'page_title' => 'Baraha - PCR Orders - Date wize',
            'title' => 'Baraha - PCR Orders - Date wiz',
            'content' => $this->load->view('pages/orders/baraha/pcr_date_wize', $view_data, true),
        ];
        $this->load->view('template/base_template_v2', $data);
    }
}