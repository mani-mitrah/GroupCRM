<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Order_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function order_item_get($item_id)
    {
    	$this->db->select('oi.*,o.*,u.*,c.category_name,sc.sub_category_name,p.unique_id,p.response_code,p.response_description,p.approval_code,p.card_number,p.card_brand,p.response_class_description,p.card_expiry,sh.service_hour,sct.sub_categorytwo');
    	$this->db->from('order_items as oi');
    	$this->db->join('orders as o','o.o_id=oi.order_id','left');
    	//$this->db->join('order_attachments as oa','oa.order_item_id=oi.item_id','left');
    	$this->db->join('payments as p','p.pid=o.payment_id','left');
    	$this->db->join('users as u','u.user_id=o.user_id','left');
    	//get the names
    	$this->db->join('m_category as c','c.id=oi.category','left');
    	$this->db->join('m_sub_category as sc','sc.id=oi.sub_category','left');
    	$this->db->join('m_sub_categorytwo as sct','sct.id=oi.sub_category_two','left');
    	$this->db->join('m_service_hours as sh','sh.id=oi.service_time','left');
    	$this->db->where('oi.item_id',$item_id);
    	$results = $this->db->get()->result_array();
        return $results;
    }

    public function order_item_attachments($item_id)
    {
    	$this->db->select('*');
    	$this->db->from('order_attachments as oa');
    	$this->db->where('oa.order_item_id',$item_id);
    	$results = $this->db->get()->result_array();
        return $results;
    }

    public function order_item_for_queue($item_id)
    {
        $this->db->select('u.first_name,u.last_name,CONCAT(u.country_code," ",u.mobile) as customer_mobile,u.email as customer_email,oi.is_complete as is_completed,c.category_name,sc.sub_category_name,sct.sub_categorytwo,o.user_id,oi.gender,oi.pregnancy,oi.menstrual_period,,oi.abortion,oi.contraceptive_pills,oi.x_ray,wsct.subcategory_three,oi.order_id,oi.item_id,oi.med_number,oi.ref2');
        $this->db->from('order_items as oi');
        $this->db->join('orders as o','o.o_id=oi.order_id','left');
        //$this->db->join('order_attachments as oa','oa.order_item_id=oi.item_id','left');
        $this->db->join('payments as p','p.order_id=oi.item_id','left');
        $this->db->join('users as u','u.user_id=o.user_id','left');
        //get the names
        $this->db->join('m_category as c','c.id=oi.category','left');
        $this->db->join('m_sub_category as sc','sc.id=oi.sub_category','left');
        $this->db->join('m_sub_categorytwo as sct','sct.id=oi.sub_category_two','left');
        $this->db->join('wq_subcategory_three as wsct','wsct.id=oi.sub_category_three','left');
        $this->db->join('m_service_hours as sh','sh.id=oi.service_time','left');
        $this->db->where('oi.item_id',$item_id);
        $results = $this->db->get()->row_array();
        return $results;
    }
}