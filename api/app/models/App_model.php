<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class App_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_agent($user_id='') 
    {
    	$this->db->select('*');
        $this->db->from('users as u');
        if($user_id!='')
        {
			$this->db->where('u.user_id',$user_id);                	
        }
        $this->db->where('u.role_id',4);
        $results = $this->db->get()->result_array();
        return $results;
           
    }

    public function get_last_conversation($timestamp='')
    {
        $this->db->select('last_updated_at');
        $this->db->from('lc_temp_conversations');
        if($timestamp!='')
        {
            $this->db->where('last_updated_at>=',$timestamp);
        }
        //$this->db->where('temp_status',0);
        $this->db->order_by('last_updated_at','desc');
        $this->db->limit('1');
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_paused_conversations() {
        $this->db->select('*');
        $this->db->from('lc_temp_conversations');
        $this->db->where('temp_status',0);
        $this->db->order_by('last_updated_at','desc');
        $result_array = $this->db->get()->result_array();
        return $result_array;
    }

    public function get_ongoing_conversations($agent_id)
    {
        $this->db->select('*');
        $this->db->from('lc_agent_chats as ac');
        $this->db->join('lc_conversations as c','ac.conversation_id=c.pg_id');
        $this->db->join('users as u','ac.agent_id=u.user_id');
        $this->db->where('ac.agent_id',$agent_id);
        $this->db->where('c.paused',1);
        $this->db->order_by('c.last_heard_on','desc');
        $result_array = $this->db->get()->result_array();
        return $result_array;   
    }


    function get_random_quote()
    {
        $this->db->select('quote');
        $this->db->order_by('id', 'RANDOM');
        $this->db->order_by('rand()');
        $this->db->limit(1);
        $query = $this->db->get('lc_random_quotes');
        return $query->result_array();

    }

    public function get_history($agent_id)
    {
        $this->db->select('*');
        $this->db->from('lc_agent_chats as ac');
        $this->db->join('lc_conversations as c','ac.conversation_id=c.pg_id');
        //$this->db->join('users as u','ac.agent_id=u.user_id');
        //$this->db->join('user_profile as up','u.user_id=up.user_id');
        $this->db->where('ac.agent_id',$agent_id);
        $this->db->where('c.is_completed',1);
        $this->db->order_by('c.last_heard_on','desc');
        $result_array = $this->db->get()->result_array();
        return $result_array;   
    }
}