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
    function sublead_details_mobile($lead_id)
    {
        $status_ids = [301, 302, 303, 304, 307, 308, 309, 310, 311, 312, 320, 321, 322, 323, 325, 326, 327, 328, 329, 600, 601, 602, 603, 604, 605, 607, 608, 609, 610, 611, 612, 613, 614, 615, 616, 617, 618, 619, 620, 621, 622, 625, 626];
        $this->db->select("count(*) as cnt")->from("leads");
        $this->db->where("lead_parent_id", $lead_id);
        $this->db->where_in('lead_status', $status_ids);
        $results = $this->db->get()->result_array();
        return $results[0]['cnt'];
    }

    public function lead_details($lead_id)
    {
        $this->db->select('l.*,if((l.lead_parent_id=0),0,1) as is_sublead,IFNULL(l.total_no_subleads, 0) as _total_no_subleads,
            u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,
            u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,
            lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,
            lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,la.assigned_to,ob.branch_sender_id as sender_id,
            lstat.id as status_code');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id',"left");
        $this->db->where('l.id', $lead_id);
        $results = $this->db->get()->row_array();
        return $results;
    }

    public function bv_lead_details($lead_id)
    {
        $this->db->select('l.*,if((l.lead_parent_id=0),0,1) as is_sublead,IFNULL(l.total_no_subleads, 0) as _total_no_subleads,
            u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,
            u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,
            lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,
            lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,la.assigned_to,ob.branch_sender_id as sender_id,
            lstat.id as status_code,lal.payment_link as payment_link');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id',"left");
        $this->db->join('lead_action_log as lal', 'lal.lead_id=l.id and lal.action_id = 412 and l.lead_status = 307',"left");
        $this->db->where('l.id', $lead_id);
        $this->db->where('l.branch_id', 138);
        $results = $this->db->get()->row_array();
        return $results;
    }

    public function bv_lead_inv($data)
    {
        $lead_id = $data;
        $this->db->select('customer_id');
        $this->db->from('leads');
        $this->db->where('id',$lead_id);
        $lead_data = $this->db->get()->result_array();
     
        if($lead_data){
            $this->db->select(' l.id as lead_id,u.first_name,ls.service_name,l.created_at,l.updated_at,lpd.payment_type,
                l.no_of_open_subleads,l.no_of_closed_subleads,l.order_receipt,l.lead_status,lstat.status_name,l.lead_parent_id,
                 MAX(lal.payment_link) AS payment_link,lal.action_id as action_log_actionid,lal.id as action_log_id,l.pos_pmt_number');
            $this->db->from('leads as l');
            $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
            // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
            // $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
            $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
            $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
            $this->db->join('lead_package_details as lpd', 'lpd.lead_id=l.id' ,'left');
            $this->db->join('lead_action_log as lal', 'lal.lead_id=l.id' ,'left');
            $this->db->where('l.customer_id', $lead_data[0]['customer_id']);
            $this->db->where('l.branch_id', 138);
            // $this->db->where('(`l`.`lead_status` in 
            //     (302,301,303,304,305,306,309,307,308,310,311,312,320,321,322,323,324,325,326,327,328,329))');
            // $this->db->where('l.lead_status = lal.status_id');
            $this->db->where('(lal.action_id in (401,402,403,411,412,420,415,417,418) or l.lead_parent_id >0)');
            $this->db->group_by('l.id, u.first_name, ls.service_name, l.created_at, l.updated_at,
                lpd.payment_type, l.no_of_open_subleads, l.no_of_closed_subleads, 
                l.order_receipt, l.lead_status, lstat.status_name, l.lead_parent_id');
        }

        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query()); exit;
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

    // For Goldencube API purpose
    public function created_leads()
    {
        $cats = $this->user_categories();
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", 1634110538)->get()->result_array();
            $group_ids = [];
            foreach ($groups as $group) {
                array_push($group_ids, $group["group_id"]);
            }
            $this->db->select("distinct(group_members.user_id)")->from("group_members");
            $this->db->join("users", "users.user_id=group_members.user_id");
            // $this->db->where("users.is_active", 1);
            $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
            $group_mems = [];
            foreach ($members as $mem) {
                array_push($group_mems, $mem["user_id"]);
            }

        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,
            u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,
            lstat.status_name as current_status,la.assigned_to as assigned_to,
            concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->where('(l.lead_created_by in (' . implode(",", $group_mems) . '))');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624) and `l`.`branch_id`!=100)');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();
        return $results;
    }
    
    public function lead_inv($data)
    {
        $lead_id = $data;
        $this->db->select('customer_id');
        $this->db->from('leads');
        $this->db->where('id',$lead_id);
        $lead_data = $this->db->get()->result_array();
        // if($lead_data){
        //     $this->db->select(' DISTINCT (l.id) as lead_id,u.first_name,ls.service_name,l.created_at,l.updated_at,lpd.payment_type,
        //         l.no_of_open_subleads,l.no_of_closed_subleads,l.order_receipt,l.lead_status,lstat.status_name,l.lead_parent_id,
        //         lal.payment_link,lal.action_id as action_log_actionid,lal.id as action_log_id');
        //     $this->db->from('leads as l');
        //     $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        //     // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        //     // $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        //     $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        //     $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        //     $this->db->join('lead_package_details as lpd', 'lpd.lead_id=l.id' ,'left');
        //     $this->db->join('lead_action_log as lal', 'lal.lead_id=l.id');
        //     $this->db->where('l.customer_id', $lead_data[0]['customer_id']);
        //     $this->db->where('l.branch_id', 106);
        //     $this->db->where('(`l`.`lead_status` in 
        //         (302,301,303,304,305,306,309,307,308,310,311,312,320,321,322,323,324,325,326,327,328,329))');
        //     $this->db->where('l.lead_status = lal.status_id');
        //     $this->db->where('(lal.action_id in (401,402,403,411,412,420,415,417,418) or l.lead_parent_id >0)');

        // }
        if($lead_data){
            $this->db->select(' l.id as lead_id,u.first_name,ls.service_name,l.created_at,l.updated_at,lpd.payment_type,
                l.no_of_open_subleads,l.no_of_closed_subleads,l.order_receipt,l.lead_status,lstat.status_name,l.lead_parent_id,
                 MAX(lal.payment_link) AS payment_link,lal.action_id as action_log_actionid,lal.id as action_log_id');
            $this->db->from('leads as l');
            $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
            // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
            // $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
            $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
            $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
            $this->db->join('lead_package_details as lpd', 'lpd.lead_id=l.id' ,'left');
            $this->db->join('lead_action_log as lal', 'lal.lead_id=l.id' ,'left');
            $this->db->where('l.customer_id', $lead_data[0]['customer_id']);
            $this->db->where('l.branch_id', 106);
            $this->db->where('(`l`.`lead_status` in 
                (302,301,303,304,305,306,309,307,308,310,311,312,320,321,322,323,324,325,326,327,328,329))');
            // $this->db->where('l.lead_status = lal.status_id');
            $this->db->where('(lal.action_id in (401,402,403,411,412,420,415,417,418) or l.lead_parent_id >0)');
            $this->db->group_by('l.id, u.first_name, ls.service_name, l.created_at, l.updated_at,
                lpd.payment_type, l.no_of_open_subleads, l.no_of_closed_subleads, 
                l.order_receipt, l.lead_status, lstat.status_name, l.lead_parent_id');
        }
        $results = $this->db->get()->result_array();
        return $results;
    }

    function get_customers_data($lead_id){
        $this->db->select('customer_id');
        $this->db->from('leads');
        $this->db->where('id',$lead_id);
        $customer_data = $this->db->get()->row_array();
        return $customer_data;   
    }
    public function leaddata_fromcustomer_baraha($cus_id){
        $this->db->select('id');
        $this->db->from('leads');
        $this->db->where('customer_id',$cus_id);
        $this->db->where('branch_id',138);
        $lead_ids = $this->db->get()->result_array();
        $lead_datas = [];
        for ($i=0; $i < count($lead_ids); $i++) { 
            array_push($lead_datas, $lead_ids[$i]['id']);
        }

        return $lead_datas;
    }

    public function leaddata_fromcustomer($data){
        $customer_id = $data['customer_id'];
        $this->db->select('id');
        $this->db->from('leads');
        $this->db->where('customer_id',$customer_id);
        $lead_ids = $this->db->get()->result_array();
        $lead_datas = [];
        for ($i=0; $i < count($lead_ids); $i++) { 
            array_push($lead_datas, $lead_ids[$i]['id']);
        }

        return $lead_datas;
        $final_array = [];
        foreach($lead_datas as $new_array){
            $final_array= implode(',', $new_array);
        }
        
        $this->db->select('l.*,if((l.lead_parent_id=0),0,1) as is_sublead,IFNULL(l.total_no_subleads, 0) as _total_no_subleads, 
            u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,
            u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,
            lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,
            lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,la.assigned_to,ob.branch_sender_id as sender_id');
            $this->db->from('leads as l');
            $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
            $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
            $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
            $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
            $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id',"left");
            $this->db->where('l.id in', $lead_datas);
            $results = $this->db->get()->result_array();

        return $results;
    }

    public function invoice_leads_count($customer_id){
        $this->db->select("count(*) as cnt")->from("leads");
        $this->db->where("lead_parent_id", 0);
        $this->db->where_in('customer_id', $customer_id);
        $this->db->where_in('lead_status', 305);
        $results = $this->db->get()->result_array();
        return $results[0]['cnt'];
    }
    public function lead_count_year($customer_id, $type=""){
        // var_dump($customer_id); exit;
        $this->db->select("COUNT(*) as total_leads, YEAR(lead_added_on) AS year")->from("leads");
        $this->db->where("lead_parent_id", 0);
        if(!empty($type) && $type="baraha"){
            $this->db->where("branch_id", 138);
        }
        $this->db->where_in('customer_id', $customer_id);
        $this->db->group_by('YEAR(lead_added_on)');
        // $this->db->where_in('lead_status', 305);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function lead_calender_details($lead_id){
        $this->db->select("ca.*");
        $this->db->from('leads as l');
        $this->db->join('calendar_appointment as ca','l.id=ca.lead_id'); 
        $this->db->where('l.id', $lead_id);
        $this->db->order_by('l.lead_added_on', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function lead_inv_dld($data)
    {
        $lead_id = $data;
        $this->db->select('customer_id');
        $this->db->from('leads');
        $this->db->where('id',$lead_id);
        $lead_data = $this->db->get()->result_array();

        if($lead_data){
            $this->db->select(' l.id as lead_id,u.first_name,ls.service_name,l.created_at,l.updated_at,lpd.payment_type,
                l.no_of_open_subleads,l.no_of_closed_subleads,l.order_receipt,l.lead_status,lstat.status_name,l.lead_parent_id,
                 MAX(lal.payment_link) AS payment_link,lal.action_id as action_log_actionid,lal.id as action_log_id');
            $this->db->from('leads as l');
            $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
            // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
            // $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
            $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
            $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
            $this->db->join('lead_package_details as lpd', 'lpd.lead_id=l.id' ,'left');
            $this->db->join('lead_action_log as lal', 'lal.lead_id=l.id' ,'left');
            $this->db->where('l.customer_id', $lead_data[0]['customer_id']);
            $this->db->where('l.branch_id', 113);
            // $this->db->where('l.lead_from', 'DLD');
            $this->db->where('(`l`.`lead_status` in 
                (302,301,303,304,305,306,309,307,308,310,311,312,320,321,322,323,324,325,326,327,328,329))');
            // $this->db->where('l.lead_status = lal.status_id');
            $this->db->where('(lal.action_id in (401,402,403,411,412,420,415,417,418) or l.lead_parent_id >0)');
            $this->db->group_by('l.id, u.first_name, ls.service_name, l.created_at, l.updated_at,
                lpd.payment_type, l.no_of_open_subleads, l.no_of_closed_subleads, 
                l.order_receipt, l.lead_status, lstat.status_name, l.lead_parent_id');
        }
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function lead_details_dld($lead_id)
    {
        $this->db->select('l.*,if((l.lead_parent_id=0),0,1) as is_sublead,IFNULL(l.total_no_subleads, 0) as _total_no_subleads,
            u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,
            u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,
            lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,
            lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,ob.branch_sender_id as sender_id,
            lstat.id as status_code');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la', 'la.lead_id=l.id',"left");
        $this->db->where('l.id', $lead_id);
        $this->db->where('l.branch_id', 113);
        $results = $this->db->get()->row_array();
        return $results;
    }
    
}