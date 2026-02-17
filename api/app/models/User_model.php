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

    public function website_coords($website_id)
    {

        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
        $this->db->select('u.user_id,u.first_name,u.email');
        $this->db->from('ontime_user_categories as ouc');
        $this->db->join('ontime_website_category_map as owcm','owcm.category_id=ouc.category_id');
        $this->db->join('users as u','u.user_id=ouc.user_id');
        $this->db->where('owcm.website_id',$website_id);
        $this->db->where('u.role_id',6);
        $this->db->where('u.banned',0);
        $this->db->group_by('u.user_id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}