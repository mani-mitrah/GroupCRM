<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Access_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_my_access_categories()
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");


        $this->db->select("DISTINCT(group_categories.category_id)")->from("group_categories");
        $this->db->join("groups", "groups.group_id=group_categories.group_id and groups.status=1");
        $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
        $query = $this->db->get();
        // print_r($this->db->last_query());
        $categories = $query->result_array();
        $cats = [];
        foreach ($categories as $category) {
            array_push($cats, $category["category_id"]);
        }
        return $cats;
    }

    public function get_my_access($user_id)
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

        if ($this->auth_user_role == 6) {

            $this->db->select("DISTINCT(ontime_websites.id)")->from("group_categories");
            $this->db->join("groups", "groups.group_id=group_categories.group_id and groups.status=1");
            $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
            $this->db->join("ontime_website_category_map", "ontime_website_category_map.category_id = group_categories.category_id and ontime_website_category_map.is_active = 1");
            $this->db->join("ontime_websites", "ontime_websites.id = ontime_website_category_map.website_id and ontime_websites.is_active = 1");
            $query = $this->db->get();
            $categories = $query->result_array();
            $cats = [];
            foreach ($categories as $category) {
                array_push($cats, $category["id"]);
            }
        } else if ($this->auth_user_role == 7) {
            $cats = [];
            $group_ids = $this->db->select("group_id")->from("group_members")->where("user_id", $this->auth_user_id)->group_by("group_members.group_id")->get()->result_array();

            $g_ids = [];
            foreach ($group_ids as $g_id) {
                array_push($g_ids, $g_id["group_id"]);
            }

            $result = $this->db->select("DISTINCT(group_websites.website_id) as id")->from("group_websites")->where_in("group_websites.group_id", $g_ids)->get()->result_array();
            foreach ($result as $res) {
                array_push($cats, $res["id"]);
            }
        }
        // $this->db->select("DISTINCT(ontime_websites.id)")->from("group_categories");
        // $this->db->join("groups","groups.group_id=group_categories.group_id and groups.status=1");
        // $this->db->join("group_members","groups.group_id=group_members.group_id and group_members.user_id = ".$this->auth_user_id." and group_members.status=1");
        // $this->db->join("ontime_website_category_map","ontime_website_category_map.category_id = group_categories.category_id and ontime_website_category_map.is_active = 1");
        // $this->db->join("ontime_websites","ontime_websites.id = ontime_website_category_map.website_id and ontime_websites.is_active = 1");
        // $query = $this->db->get();
        // $categories = $query->result_array();
        // $cats = [];
        // foreach($categories as $category){
        //     array_push($cats,$category["id"]);
        // }

        return $cats;
    }

    public function get_my_access_sites($user_id)
    {
        $this->db->select("DISTINCT(ontime_websites.id) as id,ontime_websites.website_name,ontime_websites.short_name")->from("group_categories");
        $this->db->join("groups", "groups.group_id=group_categories.group_id and groups.status=1");
        $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
        $this->db->join("ontime_website_category_map", "ontime_website_category_map.category_id = group_categories.category_id and ontime_website_category_map.is_active = 1");
        $this->db->join("ontime_websites", "ontime_websites.id = ontime_website_category_map.website_id and ontime_websites.is_active = 1");
        $query = $this->db->get();
        $categories = $query->result_array();
        return $categories;
    }

    public function get_my_orderable_access_sites($user_id)
    {
        if ($this->auth_user_role == 6) {
            $this->db->select("DISTINCT(ontime_websites.id) as id,ontime_websites.website_name,ontime_websites.short_name,ontime_websites.is_orders_contains")->from("group_categories");
            $this->db->join("groups", "groups.group_id=group_categories.group_id and groups.status=1");
            $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
            $this->db->join("ontime_website_category_map", "ontime_website_category_map.category_id = group_categories.category_id and ontime_website_category_map.is_active = 1");
            $this->db->join("ontime_websites", "ontime_websites.id = ontime_website_category_map.website_id and ontime_websites.is_active = 1");
            $this->db->where("ontime_websites.is_orders_contains", 1);
            $query = $this->db->get();
        } else {
            $this->db->select("DISTINCT(ontime_websites.id) as id,ontime_websites.website_name,ontime_websites.short_name,ontime_websites.is_orders_contains")->from("group_websites");
            $this->db->join("groups", "group_websites.group_id = groups.group_id");
            $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
            $this->db->join("ontime_websites", "ontime_websites.id = group_websites.website_id");
            $this->db->where("ontime_websites.is_orders_contains", 1);
            $query = $this->db->get();
        }

        $categories = $query->result_array();
        // print_r($categories);
        // exit();
        return $categories;
    }

    public function get_user_display_data($user_id)
    {
        $this->db->select('u.user_id,u.first_name,u.last_name,u.mobile,u.email');
        $this->db->from('users as u');
        $this->db->where('u.user_id', $user_id);
        $results = $this->db->get()->row_array();
        return $results;
    }
}