<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leads_model extends CI_Model
{
    public function __construct()
    {
        $parents_array = array();
    }

    public function lead_services($category_id='')
    {
        $this->db->select("ls.*,lc.category_name,lc.category_code");
        $this->db->from('ontime_category_services_ as ls');
        $this->db->join('ontime_categories as lc','ls.category_id=lc.category_id');
        $this->db->where('ls.is_active',1);
        if($category_id!='')
        {
            $this->db->where('ls.category_id',$category_id);
        }
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function lead_timeline($lead_id) {
        $this->db->select("la.action_name, lal.*");
        $this->db->from('lead_action_log as lal');
        $this->db->join('lead_actions as la','lal.action_id=la.id');
        $this->db->where('lal.lead_id', $lead_id);
        $this->db->order_by('lal.action_on', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }
    function get_customers_details($mobile){
        $this->db->select("user_id");
        $this->db->from('lead_users');
        $this->db->where('mobile', $mobile);
        $results = $this->db->get()->result_array();
        return $results;   
    }

    public function get_workflow_entries($service_id)
    {
        $this->db->select("lw.*,lwe.target_service_id,ls.category_id");
        $this->db->from('lead_workflows as lw');
        $this->db->join('lead_workflow_entries as lwe','lwe.workflow_id=lw.id');
        $this->db->join('ontime_category_services_ as ls','ls.service_id=lwe.target_service_id');
        $this->db->where('lw.parent_service_id',$service_id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function get_package_entries($package_id)
    {
        $this->db->select("lp.*,lps.service_id,ls.category_id");
        $this->db->from('lead_packages as lp');
        $this->db->join('lead_package_services as lps','lps.package_id=lp.package_id');
        $this->db->join('ontime_category_services_ as ls','ls.service_id=lps.service_id');
        $this->db->where('lp.package_id',$package_id);
        $results = $this->db->get()->result_array();
        return $results;   
    }

    public function unassigned_leads()
    {
        $this->db->select('l.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u','u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob','ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc','lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls','ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat','lstat.id=l.lead_status');
        $this->db->where('l.is_assigned',0);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function unassigned_leads_for_user($user_categories)
    {
        $this->db->select('l.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u','u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob','ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc','lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls','ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat','lstat.id=l.lead_status');
        $this->db->where('l.is_assigned',0);
        $this->db->where_in('l.category_id',$user_categories);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function user_leads()
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u','u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob','ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc','lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls','ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat','lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la','la.lead_id=l.id');
        $this->db->where('l.is_assigned',1);
        $this->db->where('l.order_receipt',"0");
        $this->db->where('l.lead_status <',305);
        $this->db->where('la.assigned_to',$this->auth_user_id);
        $this->db->order_by('l.contactable_date','DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function converted_leads()
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u','u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob','ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc','lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls','ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat','lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la','la.lead_id=l.id');
        $this->db->where('l.is_assigned',1);
        $this->db->where('l.lead_status',305);
        $this->db->where('la.assigned_to',$this->auth_user_id);
        $this->db->order_by('l.contactable_date','DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function disqualified_leads()
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u','u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob','ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc','lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls','ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat','lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la','la.lead_id=l.id');
        $this->db->where('l.is_assigned',1);
        $this->db->where('l.lead_status',306);
        $this->db->where('la.assigned_to',$this->auth_user_id);
        $this->db->order_by('l.contactable_date','DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }
    

    public function user_lead_categories()
    {
        $this->db->select('category_id');
        $this->db->from('ontime_user_categories');
        $this->db->where('user_id',$this->auth_user_id);
        $this->db->where('is_active',1);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function lead_details($lead_id)
    {
        $this->db->select('l.*,if((l.lead_parent_id=0),0,1) as is_sublead,IFNULL(l.total_no_subleads, 0) as _total_no_subleads
,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,la.assigned_to,ob.branch_sender_id as sender_id');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id',"left");
        $this->db->where('l.id', $lead_id);
        $results = $this->db->get()->row_array();
        // print_r($results);
        // exit();
        return $results;
    }
    public function lead_details_mobile($user_id)
    {
    $this->db->select('l.id as lead_id, l.lead_parent_id, l.total_no_subleads, l.lead_status, l.pos_salesorder, l.pos_invresponse, 
        u.country_code as customer_country_code, u.first_name as customer_name, u.mobile as customer_mobile, u.email as customer_email, 
        u.customer_type, u.customer_address, u.alt_mobile, u.alt_email, u.trn_no, u.trade_no, 
        ob.branch_name as branch_name, lc.category_name as category_name, lc.category_code, 
        ls.service_name as service_name, ls.bot_id as bot_id, lstat.status_name as current_status, 
        la.assigned_to, ob.branch_sender_id as sender_id');

    $this->db->from('leads as l');
    $this->db->join('lead_users as u', 'u.user_id = l.customer_id');
    $this->db->join('ontime_branches as ob', 'ob.branch_code = l.branch_id');
    $this->db->join('ontime_categories as lc', 'lc.category_id = l.category_id');
    $this->db->join('ontime_category_services_ as ls', 'ls.service_id = l.service_id');
    $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
    $this->db->join('leads_assigned as la', 'la.lead_id = l.id', 'left');

    $this->db->where('l.customer_id', $user_id);
    $this->db->where('l.lead_parent_id', 0);

    $status_ids = [301, 302, 303, 304, 307, 308, 309, 310, 311, 312, 320, 321, 322, 323, 325, 326, 327, 328, 329, 600, 601, 602, 603, 604, 605, 607, 608, 609, 610, 611, 612, 613, 614, 615, 616, 617, 618, 619, 620, 621, 622, 625, 626];
    $this->db->where_in('l.lead_status', $status_ids);

    $result = $this->db->get()->row_array();
    return $result;
}

    
    /**
     * [get_lead_meetings description]
     * @param  [type] $type        [upcoming=>0,completed=1,all=-1]
     * @param  string $crm_user_id [description]
     * @return [type]              [description]
     */
    public function get_lead_meetings($type,$crm_user_id='')
    {
        $this->db->select('lm.*,u.first_name');
        $this->db->from('lead_meetings as lm');
        $this->db->join('lead_users as u','u.user_id=lm.customer_id');
        $this->db->join('leads as l','l.id=lm.lead_id');
        if($type!="-1")
        {
            $this->db->where('lm.is_complete',$type);    
        }
        if($crm_user_id!='')
        {
            $this->db->where('lm.crm_user_id',$crm_user_id);            
        }
        $results = $this->db->get()->result_array();
        return $results;
    }

    
}