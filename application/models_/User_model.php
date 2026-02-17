<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    public $parents_array;

    public function __construct()
    {
        parent::__construct();
        //$folder_obj = new Folder();
        $parents_array = array();
    }

    public function website_csas($website_id = "")
    {

        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('u.user_id,u.first_name,u.last_name');
        $this->db->from('ontime_user_categories as ouc');
        // $this->db->join('ontime_website_category_map as owcm','owcm.category_id=ouc.category_id');
        $this->db->join('users as u', 'u.user_id=ouc.user_id');
        // $this->db->where('owcm.website_id',$website_id);
        $this->db->where('u.role_id', 2);
        $this->db->or_where('u.role_id', 6);
        $this->db->group_by('u.user_id');
        $this->db->order_by('u.first_name');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function baraha_pcr_csas()
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('u.user_id,u.first_name');
        $this->db->from('ontime_user_categories as ouc');
        $this->db->join('ontime_website_category_map as owcm', 'owcm.category_id=ouc.category_id');
        $this->db->join('users as u', 'u.user_id=ouc.user_id');
        // $this->db->where('owcm.website_id',501);
        // $this->db->where('owcm.category_id',19);
        $this->db->where('u.role_id', 2);
        $this->db->or_where('u.role_id', 6);
        $this->db->group_by('u.user_id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function crm_csa_coords()
    {
        $this->db->select('*');
        $this->db->from('users as u');
        $this->db->where('u.role_id', 2);
        $this->db->or_where('u.role_id', 6);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_lead_category_groups()
    {
        // print_r($category_id);
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('groups.group_name as group_s,groups.group_branch_id as branch_id');
        $this->db->from('groups');
        $this->db->where("status", 1);
        $this->db->order_by("group_name");
        $query = $this->db->get();
        $result = $query->result_array();
        // print_r($result);
        // exit();
        return $result;
    }


    public function get_accessable_groups()
    {
        // print_r($category_id);
        $this->db->select('groups.group_name as group_s,groups.group_branch_id as branch_id');
        $this->db->from('groups');
        $this->db->join("users_group_accessables","users_group_accessables.ug_group_id=groups.group_id");
        $this->db->where("users_group_accessables.ug_user_id",$this->auth_user_id);
        $this->db->where("status", 1);
        $this->db->order_by("group_name");
        $query = $this->db->get();
        $result = $query->result_array();
        // print_r($result);
        // exit();
        return $result;
    }

    public function get_lead_category_users($category_id)
    {
        // print_r($category_id);
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('u.user_id,u.first_name,u.last_name,u.role_id,group_concat(REPLACE(groups.group_name," ","")," ") as group_s,u.employee_id');
        $this->db->from('users as u');
        $this->db->join("group_members", "group_members.user_id=u.user_id");
        $this->db->join("groups", "groups.group_id=group_members.group_id");
        // $this->db->join('users as u','u.user_id=ouc.user_id');
        // $this->db->where('ouc.category_id',$category_id);
        // $this->db->where('u.user_id!=',$this->auth_user_id);
        $this->db->where('u.is_active', 1);
        $this->db->where_in('u.role_id', [2, 6, 7]);
        $this->db->order_by("u.first_name");
        $this->db->group_by('u.user_id,group_members.user_id');
        $query = $this->db->get();
        $result = $query->result_array();
        // print_r($result);
        // exit();
        return $result;
    }

    public function get_user_categories($user_id)
    {
        $this->db->select('ouc.*,oc.category_name');
        $this->db->from('ontime_user_categories as ouc');
        $this->db->join('ontime_categories as oc', 'oc.category_id=ouc.category_id');
        $this->db->where('ouc.user_id', $user_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
   
}
