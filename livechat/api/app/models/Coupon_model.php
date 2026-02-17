<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Coupon_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_coupons($company_id)
    {
        $this->db->select("*");
        $this->db->from("coupon");
        $this->db->where("company_id", $company_id);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            if (strtotime($res->expiry_date) > strtotime('now')) {
                $result_array[] = $res;
            }
        }
        return $result_array;
    }

    public function check_validity($coupon_id)
    {
        $this->db->select("*");
        $this->db->from("coupon");
        //$this->db->where('expiry_date >', date('d-m-Y'));
        $this->db->where('status', 0);
        $this->db->where('id', $coupon_id);
        $query = $this->db->get();
        $ret = $query->row();
        $expiry = $ret->expiry_date;
        if (strtotime($expiry) > strtotime('now')) {
            return $query->num_rows();
        }

    }

    public function apply_coupon($user_id, $coupon_id)
    {
        $insert_array = array(
            'user_id' => $user_id,
            'coupon_id' => $coupon_id,
            'applied_time' => date("g:i A"),
            'applied_date' => date('d-m-Y'),
        );

        $this->db->insert("applied_coupon", $insert_array);
        return $this->db->insert_id();

    }

    public function check_existance($user_id, $coupon_id)
    {
        $this->db->select("*");
        $this->db->from("applied_coupon");
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_user_id($cart_id)
    {
        $this->db->select("*");
        $this->db->from("bag");
        $this->db->where('bag_id', $cart_id);
        $query = $this->db->get();
        $ret = $query->row();
        $user_id = $ret->user_id;
        return $user_id;
    }

}