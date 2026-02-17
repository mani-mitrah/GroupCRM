<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Enquiry_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


	public function enquiry_timeline($enquiry_id) {
        $this->db->select("la.action_name, lal.*");
        $this->db->from('enquiry_action_log as lal');
        $this->db->join('enquiry_actions as la','lal.action_id=la.id');
        $this->db->where('lal.enquiry_id', $enquiry_id);
        $this->db->order_by('lal.action_on', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }
}