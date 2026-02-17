<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Company_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_companies($company_id)
    {
        $this->db->select("*");
        $this->db->from("em_companies");
        $this->db->where('id', $company_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_slider($company_id)
    {
        $this->db->select("*");
        $this->db->from("slider");
        $this->db->where('company_id', $company_id);
        $this->db->where('status', 1);
        $query = $this->db->get();
        return $query->result();
    }

}