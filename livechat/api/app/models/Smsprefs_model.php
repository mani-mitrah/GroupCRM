<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Smsprefs_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();      
        $pre = array();
        $CI = &get_instance();

        if ($this->config->item("useDatabaseConfig")) {
			
            $this->db->select('*');
            $this->db->from('m_sms_gateway');
            $this->db->where('is_active','1');
            $result = $this->db->get()->row_array();
			
            foreach ($result as $key => $value) {
                $pre[addslashes($key)] = addslashes($value);
            }   
        }
        else
        {
            $pre = (object) $CI->config->config;
        }   
        $CI->smspref = (object) $pre;      
    } 

}
