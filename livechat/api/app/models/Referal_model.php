<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Referal_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_applied_code($user_id, $referal_code)
    {
        $this->db->select("*");
        $this->db->from("applied_referal_code");
        $this->db->where("user_id", $user_id);
        $this->db->where("referal_code", $referal_code);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function apply_referal_code($insert_array)
    {
        $this->db->insert("applied_referal_code", $insert_array);
        return $this->db->insert_id();
    }

    public function check_user_existance($id)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("user_id", $id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_referal_code($referal_code)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("referal_code", $referal_code);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_used_referal_codes($user_id)
    {
        $referal_code = $this->mcommon->specific_row_value('users', array('user_id' => $user_id), 'referal_code');
        $this->db->select("*");
        $this->db->from("applied_referal_code as af");
        $this->db->join("users as u", "u.user_id=af.user_id");
        $this->db->where("af.referal_code", $referal_code);
        $this->db->where("af.is_used", 1);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "user_id" => $res->user_id,
                "referal_code" => $res->referal_code,
                "is_used" => $res->is_used,
                "used_by" => $res->first_name . ' ' . $res->last_name,
                "mobile" => $res->mobile,
                "applied_time" => $res->created_time,
                "applied_date" => $res->created_date,
                "credited" => $res->credited,
            );
        }
        return $result_array;
    }

}