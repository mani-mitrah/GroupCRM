<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  ONTIMEGOV
     */
    public function ontimegov_coord_orders()
    {
        $this->db->select('o.*,u.*,oc.category_name,os.status_name,o.created_date as order_item_date,ocs.service_name,us.first_name as as_fname,us.last_name as as_lname,us.employee_id as as_emp_id');
        $this->db->from('otg_orders as o');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        $this->db->join('users as us', 'us.user_id=o.assigned_user', "left");
        $this->db->join('ontime_categories as oc', 'oc.category_id=o.pos_category_id', 'left');
        $this->db->join('ontime_category_services as ocs', 'ocs.service_id=o.pos_service_id', 'left');
        $this->db->join('otg_order_status as os', 'os.id=o.item_status', 'left');
        $this->db->group_by("o.id");
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function ontimegov_unassigned_orders()
    {
        $this->db->select('o.*,u.*,oc.category_name,os.status_name,o.created_date as order_item_date,ocs.service_name,us.first_name as as_fname,us.last_name as as_lname,us.employee_id as as_emp_id');
        $this->db->from('otg_orders as o');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        $this->db->join('users as us', 'us.user_id=o.assigned_user', "left");
        $this->db->join('ontime_categories as oc', 'oc.category_id=o.pos_category_id', 'left');
        $this->db->join('ontime_category_services as ocs', 'ocs.service_id=o.pos_service_id', 'left');
        $this->db->join('otg_order_status as os', 'os.id=o.item_status', 'left');
        // $this->db->where('otg_orders.is_assigned',0);
        $this->db->group_by("o.id");

        $results = $this->db->get()->result_array();
        return $results;
    }

    public function ontimegov_csa_orders()
    {
        $this->db->select('o.*,u.*,oc.category_name,os.status_name,o.created_date as order_item_date,ocs.service_name,us.first_name as as_fname,us.last_name as as_lname,us.employee_id as as_emp_id');
        $this->db->from('otg_orders as o');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        $this->db->join('users as us', 'us.user_id=o.assigned_user', "left");
        $this->db->join('ontime_categories as oc', 'oc.category_id=o.pos_category_id', 'left');
        $this->db->join('ontime_category_services as ocs', 'ocs.service_id=o.pos_service_id', 'left');
        $this->db->join('otg_order_status as os', 'os.id=o.item_status', 'left');
        $this->db->where('o.assigned_user',$this->auth_user_id);
        $this->db->group_by("o.id");
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function ontimegov_order_item_get($item_id)
    {
        $this->db->select('oi.*,u.*,wqos.status_name,wqos.status_description');
        $this->db->from('otg_orders as oi');
        $this->db->join('users as u', 'u.user_id=oi.user_id', 'left');
        $this->db->join('otg_order_status as wqos', 'wqos.id=oi.item_status', 'left');
        $this->db->where('oi.id', $item_id);
        $results = $this->db->get()->row_array();
        return $results;
    }

    public function aladheed_order_item_get($item_id)
    {

        $this->db->select('oi.*,u.*,wqos.status_name,wqos.status_description');
        $this->db->from('aladheed_orders as oi');
        $this->db->join('lead_users as u', 'u.user_id=oi.customer_id', 'left');
        $this->db->join('aladheed_order_status as wqos', 'wqos.id=oi.order_status', 'left');
        $this->db->where('oi.alad_order_id', $item_id);
        $results = $this->db->get()->row_array();
        return $results;
    }

    public function aladheed_coord_orders()
    {
        $this->db->select('o.*,u.*,os.status_name');
        $this->db->from('aladheed_orders as o');
        $this->db->join('lead_users as u', 'u.user_id=o.customer_id', 'left');
        // $this->db->join('ontime_categories as oc','oc.category_id=o.pos_category_id','left');
        // $this->db->join('ontime_category_services as ocs','ocs.service_id=o.pos_service_id','left');
        $this->db->join('aladheed_order_status as os', 'os.id=o.order_status', 'left');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();

        return $results;
    }


    public function smartejari_coord_orders()
    {
        $this->db->select('o.*,u.*,os.status_name');
        $this->db->from('smartejari_orders as o');
        $this->db->join('lead_users as u', 'u.user_id=o.customer_id', 'left');
        // $this->db->join('ontime_categories as oc','oc.category_id=o.pos_category_id','left');
        // $this->db->join('ontime_category_services as ocs','ocs.service_id=o.pos_service_id','left');
        $this->db->join('smartejari_order_status as os', 'os.id=o.order_status', 'left');
        $this->db->group_by("o.order_id");
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();

        return $results;
    }

    public function smartejari_timeline($lead_id)
    {
        // print_r($lead_ids);
        // exit();
        $this->db->select("la.action_name, lal.*");
        $this->db->from('smartejari_order_action_log as lal');
        $this->db->join('order_actions as la', 'lal.action_id=la.order_action_id');
        $this->db->where('lal.se_order_id', $lead_id);
        $this->db->order_by('lal.se_order_log_id', 'DESC');
        $results = $this->db->get();
        // print_r($this->db->last_query());
        $results = $results->result_array();
        return $results;
    }


    public function smartejari_order_item_get($item_id)
    {

        $this->db->select('oi.*,u.*,wqos.status_name,wqos.status_description');
        $this->db->from('smartejari_orders as oi');
        $this->db->join('lead_users as u', 'u.user_id=oi.customer_id', 'left');
        $this->db->join('smartejari_order_status as wqos', 'wqos.id=oi.order_status', 'left');
        $this->db->where('oi.se_order_id', $item_id);
        $results = $this->db->get()->row_array();
        return $results;
    }
    public function baraha_admin_orders()
    {
        $this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo,os.status_name,oi.created_date as order_item_date');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o', 'o.o_id=oi.order_id', 'left');
        $this->db->join('payments as p', 'p.order_id=oi.item_id', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('m_category as c', 'c.id=oi.category', 'left');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as sh', 'sh.id=oi.service_time', 'left');
        $this->db->join('wq_order_status as os', 'os.id=oi.item_status', 'left');
        $this->db->join('assigned_order as ao', 'ao.order_item_id=oi.item_id', 'left');
        $this->db->where('oi.is_assigned', 1);
        $this->db->where('oi.is_complete', 0);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function free_pcr_orders()
    {
        $this->db->select('oi.*,o.*,u.*,oi.id as examinee_id,o.id as free_order_id,oi.created_date as order_item_date');
        $this->db->from('free_pcr_order_items as oi');
        $this->db->join('free_pcr_orders as o', 'o.id=oi.order_id', 'left');
        $this->db->join('free_pcr as u', 'u.id=o.customer_id', 'left');
        $this->db->where('o.service_id', 1001);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function free_vaccine_orders()
    {
        $this->db->select('oi.*,o.*,u.*,fa.*,ft.*,oi.id as examinee_id,o.id as free_order_id,oi.created_date as order_item_date');
        $this->db->from('free_pcr_order_items as oi');
        $this->db->join('free_pcr_orders as o', 'o.id=oi.order_id', 'left');
        $this->db->join('free_pcr as u', 'u.id=o.customer_id', 'left');
        $this->db->join('free_pcr_appointments as fa', 'fa.examinee_id=oi.id', 'left');
        $this->db->join('free_pcr_time_slots as ft', 'ft.id=fa.timeslot_id', 'left');
        $this->db->where('o.service_id', 1002);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function free_vaccine_order_details($id)
    {
        $this->db->select('oi.*,o.*,u.*,fa.*,ft.*,oi.id as examinee_id,o.id as free_order_id,oi.created_date as order_item_date');
        $this->db->from('free_pcr_order_items as oi');
        $this->db->join('free_pcr_orders as o', 'o.id=oi.order_id', 'left');
        $this->db->join('free_pcr as u', 'u.id=o.customer_id', 'left');
        $this->db->join('free_pcr_appointments as fa', 'fa.examinee_id=oi.id', 'left');
        $this->db->join('free_pcr_time_slots as ft', 'ft.id=fa.timeslot_id', 'left');
        $this->db->where('o.id', $id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_coord_orders()
    {

        $this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo,os.status_name,oi.created_date as order_item_date');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o', 'o.o_id=oi.order_id');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        $this->db->join('payments as p', 'p.order_id=oi.item_id', 'left');
        //get the names
        $this->db->join('m_category as c', 'c.id=oi.category', 'left');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as sh', 'sh.id=oi.service_time', 'left');
        $this->db->join('wq_order_status as os', 'os.id=oi.item_status', 'left');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();
        return $results;
    }

    //deprecated
    public function baraha_orders()
    {
        $this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo,os.status_name,oi.created_date as order_item_date');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o', 'o.o_id=oi.order_id', 'left');
        $this->db->join('payments as p', 'p.order_id=oi.item_id', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('m_category as c', 'c.id=oi.category', 'left');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as sh', 'sh.id=oi.service_time', 'left');
        $this->db->join('wq_order_status as os', 'os.id=oi.item_status', 'left');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_csa_orders()
    {
        $this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo,os.status_name,oi.created_date as order_item_date');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o', 'o.o_id=oi.order_id', 'left');
        $this->db->join('payments as p', 'p.order_id=oi.item_id', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('m_category as c', 'c.id=oi.category', 'left');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as sh', 'sh.id=oi.service_time', 'left');
        $this->db->join('wq_order_status as os', 'os.id=oi.item_status', 'left');
        $this->db->join('assigned_order as ao', 'ao.order_item_id=oi.item_id', 'left');
        $this->db->where('ao.assigned_user', $this->auth_user_id);
        $results = $this->db->get()->result_array();
        return $results;
    }



    public function order_item_get($item_id)
    {
        $this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo,wqos.status_name,wqos.status_description');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o', 'o.o_id=oi.order_id', 'left');
        $this->db->join('payments as p', 'p.pid=o.payment_id', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        $this->db->join('wq_order_status as wqos', 'wqos.id=oi.item_status', 'left');
        //get the names
        $this->db->join('m_category as c', 'c.id=oi.category', 'left');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as sh', 'sh.id=oi.service_time', 'left');
        $this->db->where('oi.item_id', $item_id);
        $results = $this->db->get()->row_array();
        return $results;
    }

    public function baraha_unassigned_orders()
    {
        //ins for dotu: 
        //i need to join the company_orders table and put that condition that take all orders which are not assigned
        $this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo,os.status_name,oi.created_date as order_item_date');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o', 'o.o_id=oi.order_id', 'left');
        $this->db->join('payments as p', 'p.order_id=oi.item_id', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('m_category as c', 'c.id=oi.category', 'left');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as sh', 'sh.id=oi.service_time', 'left');
        $this->db->join('wq_order_status as os', 'os.id=oi.item_status', 'left');
        $this->db->join('company_orders as co', 'co.order_item_id=oi.item_id', 'left');
        $this->db->where('co.is_assigned', 0);
        $this->db->where('co.website_id', 501);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_unassigned_pcr_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->where('o.is_assigned', 0);
        $this->db->where('o.payment_id!=', NULL);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_csa_pcr_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('o.payment_id!=', NULL);
        $this->db->where('ao.assigned_user', $this->auth_user_id);
        $results = $this->db->get()->result_array();

        return $results;
    }
    public function baraha_coord_pcr_today_orders($date = NULL)
    {
        if ($date == NULL) $date = date("Y-m-d");
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,pts.slot_timings,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('paid_pcr_appointments as ppa', 'ppa.order_id=o.pcr_order_id', 'right');
        $this->db->join('paid_pcr_time_slots as pts', 'pts.id=ppa.timeslot_id', 'right');
        // $this->db->where('o.is_completed',0);
        $this->db->where('o.payment_id!=', NULL);
        $this->db->where('o.preferred_date', $date);
        $this->db->group_by('o.pcr_order_id');
        $this->db->order_by('pts.id', 'ASC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        return $results;
    }
    public function baraha_csa_pcr_today_orders()
    {

        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,pts.slot_timings,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('paid_pcr_appointments as ppa', 'ppa.order_id=o.pcr_order_id', 'right');
        $this->db->join('paid_pcr_time_slots as pts', 'pts.id=ppa.timeslot_id', 'right');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('ao.assigned_user', $this->auth_user_id);
        $this->db->where('o.is_completed', 0);
        $this->db->where('o.payment_id!=', NULL);
        $this->db->where('o.preferred_date', date('Y-m-d'));
        $this->db->group_by('o.pcr_order_id');
        $this->db->order_by('pts.id', 'ASC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_csa_pcr_today_examinees()
    {
        $this->db->select('*');
        $this->db->from('pcr_order as o');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('paid_pcr_appointments as ppa', 'ppa.order_id=o.pcr_order_id', 'right');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('ao.assigned_user', $this->auth_user_id);
        $this->db->where('o.is_completed', 0);
        $this->db->where('o.payment_id!=', NULL);
        $this->db->where('o.preferred_date', date('Y-m-d'));
        $this->db->group_by('o.pcr_order_id');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_coord_pcr_today_examinees()
    {
        $query = $this->db->query("select pcr_order_items.pcr_order_item_id from pcr_order_items JOIN pcr_order on pcr_order.pcr_order_id = pcr_order_items.pcr_order_id JOIN users on users.user_id = pcr_order.user_id where DATE(pcr_order.preferred_date) = CURRENT_DATE() and pcr_order.payment_id IS NOT NULL and pcr_order_items.is_completed = 0
        ");
        $results = $query->result_array();
        return $results;
    }


    public function baraha_coord_pcr_upcoming_orders($value = '')
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('o.payment_id!=', NULL);
        $this->db->where('o.is_completed', 0);
        $this->db->where('o.preferred_date >', date('Y-m-d'));
        $results = $this->db->get()->result_array();
        return $results;
    }
    public function baraha_csa_pcr_upcoming_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('ao.assigned_user', $this->auth_user_id);
        $this->db->where('o.is_completed', 0);
        $this->db->where('o.payment_id!=', NULL);
        $this->db->where('o.preferred_date >', date('Y-m-d'));
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_coord_pcr_completed_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('o.is_completed', 1);
        $this->db->where('o.payment_id!=', NULL);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_coord_pcr_incomplete_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('o.is_completed', 0);
        $this->db->where('o.preferred_date <', date('Y-m-d'));
        $this->db->where('o.payment_id!=', NULL);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_csa_pcr_completed_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('ao.assigned_user', $this->auth_user_id);
        $this->db->where('o.is_completed', 1);
        $this->db->where('o.payment_id!=', NULL);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_csa_pcr_incomplete_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('ao.assigned_user', $this->auth_user_id);
        $this->db->where('o.is_completed', 0);
        $this->db->where('o.preferred_date <', date('Y-m-d'));
        $this->db->where('o.payment_id!=', NULL);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function baraha_coord_pcr_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('o.payment_id!=', NULL);
        $results = $this->db->get()->result_array();
        // echo "<pre>";
        // print_r($results);
        // echo "</pre>";
        // exit();
        return $results;
    }

    public function baraha_coord_pcr_unpaid_orders()
    {
        $this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,o.referred');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('assigned_pcr_order as ao', 'ao.pcr_order_id=o.pcr_order_id', 'left');
        $this->db->where('o.payment_id', NULL);
        $results = $this->db->get()->result_array();
        // echo "<pre>";
        // print_r($results);
        // echo "</pre>";
        // exit();
        return $results;
    }



    public function pcr_order_get($user_id)
    {
        $this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo,wqos.status_name,wqos.status_description,o.referred');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o', 'o.o_id=oi.order_id', 'left');
        $this->db->join('payments as p', 'p.order_id=oi.item_id', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        $this->db->join('wq_order_status as wqos', 'wqos.id=oi.item_status', 'left');
        //get the names
        $this->db->join('m_category as c', 'c.id=oi.category', 'left');
        $this->db->join('m_sub_category as sc', 'sc.id=oi.sub_category', 'left');
        $this->db->join('m_sub_categorytwo as sct', 'sct.id=oi.sub_category_two', 'left');
        $this->db->join('m_service_hours as sh', 'sh.id=oi.service_time', 'left');
        $this->db->where('o.user_id', $user_id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    //Paid pcr examinees
    public function paid_examinees($order_id)
    {
        $this->db->select('*');
        $this->db->from('pcr_order_items as poi');
        $this->db->join('paid_pcr_appointments as poa', 'poi.pcr_order_item_id=poa.examinee_id');
        $this->db->join('paid_pcr_time_slots as pos', 'poa.timeslot_id=pos.id');
        $this->db->where('poi.pcr_order_id', $order_id);
        $results = $this->db->get()->result_array();
        return $results;
    }


    //pcr counts
    /*public function todays_total_examinees()
    {
        $this->db->select('*');
        $this->db->from('pcr_order');
        $this->db->where(array());
        $num_results = $this->db->count_all_results();
        return $num_results;
    }*/

    public function test()
    {
        //$this->db->select('CONCAT(u.first_name," ",u.last_name) as customer_name, u.mobile, o.pcr_order_id, c.category_name, sc.subcategory_name, o.total_amount, o.pcr_status, o.preferred_date,o.is_completed,pts.slot_timings');
        $this->db->select('o.pcr_order_id, o.preferred_date,o.is_completed,pts.slot_timings');
        $this->db->from('pcr_order as o');
        $this->db->join('payments as p', 'o.payment_id=p.pid', 'left');
        $this->db->join('users as u', 'u.user_id=o.user_id');
        //get the names
        $this->db->join('pcr_categories as c', 'c.pcr_cat_id=o.pcr_category_id', 'left');
        $this->db->join('pcr_subcategories as sc', 'sc.pcr_subcat_id=o.pcr_sub_category_id', 'left');
        $this->db->join('paid_pcr_appointments as ppa', 'ppa.order_id=o.pcr_order_id', 'right');
        $this->db->join('paid_pcr_time_slots as pts', 'pts.id=ppa.timeslot_id', 'right');
        $this->db->where('o.is_completed', 0);
        /*$this->db->where('o.preferred_date',date('Y-m-d'));*/
        $this->db->group_by('o.pcr_order_id');
        $this->db->order_by('pts.id', 'ASC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function pr_orders()
    {
        $this->db->select("*")->from("property_customers");
        $results = $this->db->get()->result_array();
        return $results;
    }
}
