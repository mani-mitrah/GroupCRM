<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Leads_model extends CI_Model
{
    public function __construct()
    {
        $parents_array = array();
    }

    public function lead_services($category_id = '')
    {
        $this->db->select("ls.*,lc.category_name,lc.category_code");
        $this->db->from('ontime_category_services_ as ls');
        $this->db->join('ontime_categories as lc', 'ls.category_id=lc.category_id');
        $this->db->where('ls.is_active', 1);
        if ($category_id != '') {
            $this->db->where('ls.category_id', $category_id);
        }
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function lead_timeline($lead_id)
    {
        $lead_ids = [$lead_id];
        $add_lead_ids = $this->db->select("id")->from("leads")->where("lead_parent_id", $lead_id)->get()->result_array();
        if (count($add_lead_ids)) {
            foreach ($add_lead_ids as $add_lead) {
                array_push($lead_ids, $add_lead["id"]);
            }
        }
        // print_r($lead_ids);
        // exit();
        $this->db->select("la.action_name, lal.*");
        $this->db->from('lead_action_log as lal');
        $this->db->join('lead_actions as la', 'lal.action_id=la.id');
        $this->db->where_in('lal.lead_id', $lead_ids);
        $this->db->order_by('lal.id', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function get_workflow_entries($service_id)
    {
        $this->db->select("lw.*,lwe.target_service_id,ls.category_id");
        $this->db->from('lead_workflows as lw');
        $this->db->join('lead_workflow_entries as lwe', 'lwe.workflow_id=lw.id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=lwe.target_service_id');
        $this->db->where('lw.parent_service_id', $service_id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function get_package_entries($package_id)
    {
        $this->db->select("lp.*,lps.*,ls.category_id");
        $this->db->from('lead_packages as lp');
        $this->db->join('lead_package_services as lps', 'lps.package_id=lp.package_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=lps.service_id');
        $this->db->where('lp.package_id', $package_id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function unassigned_leads()
    {
        $this->db->select('l.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
                $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->where('l.is_assigned', 0);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.is_pos_lead !=', 1);
        $this->db->group_by('l.id');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function user_categories()
    {
        $this->db->select("DISTINCT(group_categories.category_id)")->from("group_categories");
        $this->db->join("groups", "groups.group_id=group_categories.group_id and groups.status=1");
        $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
        $query = $this->db->get();
        $categories = $query->result_array();
        $cats = [];
        foreach ($categories as $category) {
            array_push($cats, $category["category_id"]);
        }
        return $cats;
    }

    /* public function unassigned_leads_for_user($user_categories)
    {

        $cats = $this->user_categories();
        // print_r($cats);
        // exit();
        // $cats = $user_categories;

        $this->db->select('leads.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lc.category_id as category_id')->from("leads");

        $this->db->join('lead_users as u', 'u.user_id=leads.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=leads.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=leads.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=leads.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=leads.lead_status');
        $this->db->where('leads.is_assigned', 0);
        //$this->db->where('leads.is_assigned',0);
        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
            $this->db->where_in('leads.category_id', $cats);
        } else {
            $this->db->join('leads_assigned as la', 'la.lead_id=leads.id and la.assigned_to = ' . $this->auth_user_id);
        }
        $this->db->where('leads.lead_parent_id', 0);
        $this->db->where('(leads.lead_status not in(305,306,309))');
        $this->db->group_by('leads.id');
        $results = $this->db->get();
        // print_r($this->db->last_query());
        // exit();
        $results = $results->result_array();
        return $results;
    } */

    public function unassigned_leads_for_user($from_date = null, $to_date = null)
    {
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        $this->db->select('leads.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lc.category_id as category_id, concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator, gc.group_name as creator_group')->from("leads");

        $this->db->join('lead_users as u', 'u.user_id=leads.customer_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=leads.branch_id');
        $this->db->join('users as creator', 'creator.user_id=leads.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = leads.created_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=leads.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=leads.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=leads.lead_status');
        $this->db->where('leads.is_assigned', 0);

        $this->db->group_start()
         ->where_not_in('leads.lead_source', ['cc-contact'])
         ->or_where('leads.lead_source IS NULL', null, false)
         ->group_end();

        $this->db->where("(leads.created_group_id in (" . implode(",", $user_group_ids) . ") 
            or leads.assigned_group_id in (" . implode(",", $user_group_ids) . ")
            or leads.lead_created_by = " . $this->auth_user_id . ")");
        
        $this->db->where('leads.lead_parent_id', 0);
        $this->db->where('leads.lead_status not in (305,306,309,624,606)');
        $this->db->where('leads.branch_id != 100');
        $this->db->where('leads.is_pos_lead !=', 1);
        if (!empty($from_date) && $from_date != NULL && !is_array($from_date)) {
            $this->db->where('DATE(leads.created_at) >=', $from_date);
        }
        if (!empty($to_date) && $to_date != NULL && !is_array($to_date)) {
            $this->db->where('DATE(leads.created_at) <=', $to_date);
        }

        $this->db->group_by('leads.id');
        $this->db->order_by('leads.id', 'DESC');
        $results = $this->db->get();
        $results = $results->result_array();
        
        return $results;
    }

    public function user_leads($from_date = null, $to_date = null)
    {
        /*$this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->where("user_id",$this->auth_user_id);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);  */

        //print_r($result);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on');
        // lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        //if($this->auth_user_role == 7){
        //    $this->db->join('leads_assigned as la', 'la.lead_id=l.id AND la.assigned_to IN (' . $assigned_to_ids_str . ')');
        //}else{
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
        //}

        $this->db->where('l.is_assigned', 1);
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();
        //$this->db->where('l.order_receipt', "0");
        // $this->db->where("(l.order_receipt = '0' OR l.order_receipt IS NULL)");
        // $this->db->where('la.assigned_to',$this->auth_user_id);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        //$this->db->where('l.lead_status !=', 306);
        //$this->db->where('l.lead_status !=306 AND l.lead_status !=624');
        $this->db->where('l.lead_status NOT IN (305,306,624,606)');
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();
        return $results;
    }
    public function leads_reassigned($from_date = null, $to_date = null)
    {
        // $cats = $this->user_categories();
        $group_mems = [];

        // if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            // Fetch group IDs for the authenticated user
            $groups = $this->db->select('distinct(group_id)')
                                ->from("group_members")
                                ->where("user_id", $this->auth_user_id)
                                ->get()
                                ->result_array();
            
            // Extract group IDs into an array
            $group_ids = array_column($groups, 'group_id');

            // Fetch distinct user IDs in the same groups
            $members = $this->db->select("distinct(group_members.user_id)")
                                ->from("group_members")
                                ->join("users", "users.user_id=group_members.user_id")
                                ->where_in("group_members.group_id", $group_ids)
                                ->get()
                                ->result_array();

            // Extract user IDs into an array
            $group_mems = array_column($members, 'user_id');
        // }

        // Convert user IDs array to a comma-separated string
        $assigned_to_ids_str = implode(',', $group_mems);

        /*$this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;   */
        // $assigned_to_ids_str = implode(',', $assigned_to_ids);

        // Main leads query
        $this->db->select('count(*) over() as count, l.*, u.country_code as customer_country_code, u.first_name as customer_name, 
        u.mobile as customer_mobile, u.email as customer_email, g.group_name as group_name, lc.category_name as category_name, ls.service_name as service_name, lstat.status_name as current_status, la.assigned_to as assigned_to, 
        concat(creator.first_name, " ", creator.last_name, " (", creator.employee_id, ")") as creator, 
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on');
        // ob.branch_name as branch_name
        // lg.creator_branch, lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id = l.id');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id');
        $this->db->join('users as creator', 'creator.user_id = l.lead_created_by');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code = l.branch_id');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id = l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id = l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id = l.id');
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id');

        // Add conditions for assigned_by and assigned_to
        if (!empty($assigned_to_ids_str)) {
            $this->db->where("la.assigned_by IN ($assigned_to_ids_str)", NULL, FALSE);
            $this->db->where("la.assigned_to NOT IN ($assigned_to_ids_str)", NULL, FALSE);
            //$this->db->where("lg.creator_group_id NOT IN ($assigned_to_ids_str)", NULL, FALSE);
        }
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            //$this->db->where_in('lg.creator_group_id', $group_ids);
            $this->db->where_not_in('l.created_group_id', $group_ids);
        }
        $this->db->where("(l.created_group_id  in (" . implode(",", $group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $group_ids) . ") OR la.assigned_by =" . $this->auth_user_id . " OR 
        (lal.status_id = 303 and lal.action_by =" . $this->auth_user_id . "))");
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');

        if (!empty($from_date)) {
            $this->db->where('DATE(la.assigned_on) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(la.assigned_on) <=', $to_date);
        }
        
        if ($this->auth_user_role > 5) {
            $this->db->where('(l.lead_parent_id = 0)');
        }
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit;

        return $results;
    }

  
    public function leads_assigned_by_coordinator($from_date = null, $to_date = null)
    {
        $cats = $this->user_categories();
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
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
            // echo "<pre>";
            // print_r($group_mems);
            // echo "</pre>";
            // exit();
        }

        $this->db->select('count(*) over() as count, l.*, u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,
        l.created_group_id,gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on');
        // ob.branch_name as branch_name
        // lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');

        if ($this->auth_user_role == 2 || $this->auth_user_role == 6) {

            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            // echo "Hi" . $this->db->last_query();
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('((l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ') and l.lead_created_by not in (' . implode(",", $group_mems) . '))');
        } else {
            // print_r($this->auth_user_id);
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
            // echo "This is working";
            // $this->db->where('la.assigned_to=',$this->auth_user_id);
        }
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->where_in('l.assigned_group_id', $group_ids);
            //$this->db->or_where_in('lg.creator_group_id', $group_ids);
        }

        $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . "))");
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('l.assigned_group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();
        
        if ($this->auth_user_role > 5) {
            $this->db->where('(l.lead_parent_id = 0)');
        }

         if (!empty($from_date)) {
            $this->db->where('DATE(la.assigned_on) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(la.assigned_on) <=', $to_date);
        }


        // $this->db->where('la.assigned_by', $this->auth_user_id);
        $this->db->group_by('l.id');

        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // print_r(count($results));

        // exit();
        return $results;
    }
    public function receipt_not_generated($from_date = null, $to_date = null)
    {
        $cats = $this->user_categories();
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
            $group_ids = [];
            foreach ($groups as $group) {
                array_push($group_ids, $group["group_id"]);
            }
            $this->db->select("distinct(group_members.user_id)")->from("group_members");
            $this->db->join("users", "users.user_id=group_members.user_id");
            $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
            $group_mems = [];
            foreach ($members as $mem) {
                array_push($group_mems, $mem["user_id"]);
            }
        }

        $this->db->select('count(*) over() as count, l.*, u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,
        l.created_group_id,gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on, lal.last_446_remarks');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("
        (
            SELECT lead_id,
                MAX(CASE WHEN rn = 1 AND action_id = 446 THEN remarks END) AS last_446_remarks
            FROM (
                SELECT
                    lead_id,
                    action_id,
                    remarks,
                    ROW_NUMBER() OVER (
                        PARTITION BY lead_id
                        ORDER BY id DESC
                    ) AS rn
                FROM lead_action_log where remarks NOT LIKE '%Duplicate Online Payment Reference%'
            ) t
            WHERE rn <= 2
            GROUP BY lead_id
            HAVING COUNT(*) = 2
            AND SUM(action_id = 446) >= 1
        ) lal ", 'lal.lead_id = l.id', 'INNER', false);

        if ($this->auth_user_role == 2 || $this->auth_user_role == 6) {
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('((l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . '))');
        } else {
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
        }
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->where_in('l.assigned_group_id', $group_ids);
        }
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();

        $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . "))");
        // $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        // $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('l.assigned_group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->where_in('l.lead_status', [310,643]);
        // $this->db->where('l.pos_pmt_number is null');
        $this->db->where('(l.lead_parent_id = 0)');

        if (!empty($from_date)) {
            $this->db->where('DATE(la.assigned_on) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(la.assigned_on) <=', $to_date);
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();

        return $results;
    }
    public function created_leads($from_date = null, $to_date = null)
    {
        // $cats = $this->user_categories();
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.first_name as customer_name,
        u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,
        lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,
        concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on');
        //lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id', 'left');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');
        // $this->db->join('lead_groups as lgg', 'lgg.assigned_group_id in (' . implode(",", $user_group_ids) . ')', 'inner');

        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        // $this->db->where('(l.lead_created_by in (' . implode(",", $group_mems) . '))');
        if($this->auth_user_role == 7 ){
            // $this->db->where('(l.lead_created_by in (' . implode(",", $assigned_to_ids) . ') or la.assigned_to in (' . implode(",", $assigned_to_ids) . '))');
            // $this->db->where('l.lead_created_by in (' . implode(",", $assigned_to_ids) . ')');
            $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . ") or l.lead_zoho_created_by_id = " . $this->auth_user_id . ")");
        } else if ($this->auth_user_role == 2 || $this->auth_user_role == 6){
            $this->db->where('(l.lead_created_by = ' . $this->auth_user_id . ' or la.assigned_to = ' . $this->auth_user_id . ' 
            or l.lead_zoho_created_by_id = ' . $this->auth_user_id . ')');
        } else if($this->auth_user_role == 86 || $this->auth_user_role == 89){
            $this->db->where_in('l.lead_created_by', $assigned_to_ids);
        } else if($this->auth_user_role == 84 && in_array(74, $user_group_ids)) {
            $group_mems = [ $this->auth_user_id, 3946368694 ];  // goldencubeweb@goldencube.ae -- 3946368694 && 74 is GoldenCube Group
            $this->db->where_in('l.lead_created_by', $group_mems);
        } else{
            $this->db->where('l.lead_created_by', $this->auth_user_id);
        }
        // $this->db->where('lg.assigned_group_id in (' . implode(",", $user_group_ids) . ')');
        // $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . "))");
        $this->db->where('l.is_assigned', 1);
        // $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_parent_id = 0 ');
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        
        // if ($this->auth_user_role > 5) {
        //     $this->db->where('(l.lead_parent_id = 0)');
        // }

        // $this->db->where('la.assigned_by', $this->auth_user_id);
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // print_r(count($results));

        // exit();
        return $results;
    }


    public function pos_leads_coordinator()
    {
        $cats = $this->user_categories();
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
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
            // echo "<pre>";
            // print_r($group_mems);
            // echo "</pre>";
            // exit();
        }

        // new
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        // $this->db->where('group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,concat(assigned.first_name," ",assigned.last_name," (",assigned.employee_id,")") as assigned_user,lg.creator_group,lg.assigned_group, la.assigned_on as assigned_on');
        $this->db->from('leads as l');
                $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');

        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {

            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR l.lead_created_by = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            // echo "Hi" . $this->db->last_query();
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $assigned_to_ids) . ') or l.lead_created_by in (' . implode(",", $assigned_to_ids) . ') or l.lead_created_by = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else {
            // print_r($this->auth_user_id);
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
            // echo "This is working";
            // $this->db->where('la.assigned_to=',$this->auth_user_id);
        }
        $this->db->join('users as assigned', 'assigned.user_id=la.assigned_to');

        // $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        // 624 customer not interested / wrong lead
        $this->db->where('(`l`.`lead_status` not in (624,305,306,309,606) and (`l`.`branch_id`=100 OR `l`.`is_pos_lead`=1 ))');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        // $this->db->where('la.assigned_by', $this->auth_user_id);
        $this->db->group_by('l.id');

        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // print_r(count($results));

        // exit();
        return $results;
    }

    public function converted_leads($from_date = null, $to_date = null, $user_type = "")
    {   
        /* $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->where("user_id",$this->auth_user_id);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        // Extract user_id values into a plain array
        $user_ids = array_column($result, 'user_id');
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $group_branch = $this->db->select("groups.group_branch_id")
        ->from("groups")
        ->where_in("groups.group_id", $group_ids)
        ->get()
        ->result_array();
          
        $group_ids_branch = [];
        foreach ($group_branch as $group_branch_id) {
            array_push($group_ids_branch, $group_branch_id["group_branch_id"]);
        }        

        // Check if user_ids is empty to avoid syntax errors in the main query
        if (empty($user_ids)) {
            $user_ids = [0]; // Use a non-existent ID to ensure the query returns no results
        }

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids); */

        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        // $this->db->where('group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status, gc.group_name as creator_group, ga.group_name as assigned_group, 
        concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator, la.assigned_on as assigned_on,
        la.assigned_to as assigned_to');
        // ob.branch_name as branch_name
        // lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id', 'left');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        // $this->db->join('groups as gs', 'gs.group_branch_id=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        if($user_type != ""){
            if($user_type == "created"){
                $this->db->where('l.lead_created_by', $this->auth_user_id);
            }
            if($user_type == "assigned"){
                $this->db->where('la.assigned_by', $this->auth_user_id);
            }
            if($user_type == "completed"){
                $this->db->where('l.completed_by', $this->auth_user_id);
            }
        } else {
            if($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89){
                //$this->db->where_in('l.branch_id', $this->auth_user_id);
                $this->db->where("(la.assigned_to IN ($assigned_to_ids_str) OR l.lead_created_by IN ($assigned_to_ids_str))", NULL, FALSE);
            }else{
                $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id . ')');
            }
            // It will shown the disqualified leads also, so that it commented
            if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            //     //$this->db->where_in('lg.creator_group_id', $groups);
            //     $this->db->where_in('lg.assigned_group_id', $group_ids);
            //     $this->db->or_where_in('lg.creator_group_id', $group_ids);
                $group_ids_str = implode(',', $group_ids);
                $this->db->where("(l.assigned_group_id IN ($group_ids_str) OR l.created_group_id IN ($group_ids_str))", NULL, FALSE);
            }
        }
        //$this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id . ')');
        $this->db->where('l.is_assigned', 1);
        // $this->db->where_in('l.branch_id', $group_ids_branch); // Corrected IN condition
        // $group_ids_str = implode(',', $group_ids);
        // $this->db->where("(lg.assigned_group_id IN ($group_ids_str) OR lg.creator_group_id IN ($group_ids_str))", NULL, FALSE);
        // $this->db->where_in('lg.assigned_group_id', $group_ids);
        $this->db->where('l.lead_status', 305);
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();
        // $this->db->or_where('l.lead_created_by',$this->auth_user_id);
        // $this->db->or_where('l.lead_status',306);

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // echo $this->db->last_query();
        // exit;
        return $results;
    }

    public function converted_leads_cordinator($user_type = "")
    {
        $cats = $this->user_categories();

        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        // print_r($groups);
        // exit();
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
        // echo "<pre>";
        // print_r($group_mems);
        // echo "</pre>";
        // exit();
        // print_r($cats);
        // echo "<br==================================================================</br>";
        // print_r(implode(",", $group_mems));
        // echo "<br==================================================================</br>";
        // print_r($this->auth_user_id);
        // echo "<br==================================================================</br>";
        // exit();
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
                $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        // $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 305);
        if($user_type != ""){
            if($user_type == "created"){
                $this->db->where('l.lead_created_by', $this->auth_user_id);
            }
            if($user_type == "assigned"){
                $this->db->where('la.assigned_by', $this->auth_user_id);
            }
            if($user_type == "completed"){
                $this->db->where('l.completed_by', $this->auth_user_id);
            }
        } else {
            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                $this->db->where('(l.category_id in (' . implode(",", $cats) . ')  or l.lead_created_by = ' . $this->auth_user_id . ' or la.assigned_to = '.$this->auth_user_id.')');
            } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
                $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by in (' . implode(",", $group_mems) . ') or l.lead_created_by = ' . $this->auth_user_id . ' 
                    or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');

            } else {
                $this->db->where("(la.assigned_to in (" . implode(",", $group_mems) . ") or l.lead_created_by in (" . implode(",", $group_mems) . "))");
            }
        }
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();
        // $this->db->where('la.assigned_to',$this->auth_user_id);
        // $this->db->where('la.assigned_by',$this->auth_user_id);
        // $this->db->or_where('l.lead_status',306);
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();
        return $results;
    }
    public function disqualified_leads($from_date = null, $to_date = null, $user_type = "")
    {
        /*
        // Fetch the group IDs for the user
        
        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();
        //print_r($subquery);
        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->where("user_id",$this->auth_user_id);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        // Extract user_id values into a plain array
        $user_ids = array_column($result, 'user_id');

        // Check if user_ids is empty to avoid syntax errors in the main query
        if (empty($user_ids)) {
            $user_ids = [0]; // Use a non-existent ID to ensure the query returns no results
        }
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        // print_r($groups);
        // exit();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids); */

        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        // $this->db->where('group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        // Main leads query
        $this->db->select('count(*) over() as count, l.*, u.country_code as customer_country_code, u.first_name as customer_name, u.mobile as customer_mobile, u.email as customer_email, g.group_name as group_name, lc.category_name as category_name, ls.service_name as service_name, lstat.status_name as current_status,l.created_group_id,l.assigned_group_id, gc.group_name as creator_group, ga.group_name as assigned_group, concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator, la.assigned_on as assigned_on, la.assigned_to as assigned_to');
        //  lg.creator_branch, lg.assigned_branch
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id = l.id', 'left');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code = l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id = l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id = l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id = l.id', 'left');
        // $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 306);
        // $this->db->where('l.lead_status=306 OR l.lead_status=606');
        // $this->db->where('l.lead_status in (306,606,624)');

        if($user_type != ""){
            if($user_type == "created"){
                $this->db->where('l.lead_created_by', $this->auth_user_id);
            }
            if($user_type == "assigned"){
                $this->db->where('la.assigned_by', $this->auth_user_id);
            }
            if($user_type == "completed"){
                $this->db->where('l.completed_by', $this->auth_user_id);
            }
        } else {
            if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
                $this->db->where("(la.assigned_to IN ($assigned_to_ids_str) OR l.lead_created_by IN ($assigned_to_ids_str))", NULL, FALSE);
                // $this->db->where("la.assigned_to IN ($assigned_to_ids_str)", NULL, FALSE);
            } else {
                $this->db->where('la.assigned_to', $this->auth_user_id);
            }
            // if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            // //     //$this->db->where_in('lg.creator_group_id', $groups);
            //     $this->db->where("(lg.assigned_group_id IN (' . implode(",", $group_ids) . ') OR 
            //     lg.creator_group_id IN (' . implode(",", $group_ids) . '))", NULL, FALSE);
            // //     // $this->db->where_in('lg.assigned_group_id', $group_ids);
            // }
        }
        
        $group_ids_str = implode(',', $group_ids);
        $this->db->where("(l.assigned_group_id IN ($group_ids_str) OR l.created_group_id IN ($group_ids_str))", NULL, FALSE);
        $this->db->where('(l.lead_parent_id = 0 )');    // AND l.is_assigned = 1

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // echo $this->db->last_query();
        // exit;
        
        return $results;
    }

    /*public function disqualified_leads()
    {
        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        // Extract user_id values into a plain array
        $user_ids = array_column($result, 'user_id');

        //print_r($user_ids);

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
                $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 306);
        if($this->auth_user_role == 7){
            $this->db->where('la.assigned_to IN (' . $assigned_to_ids_str . ')');
        }else{
            $this->db->where('la.assigned_to', $this->auth_user_id);
        }
        //$this->db->where('la.assigned_to', $this->auth_user_id);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }*/

    public function coordinator_disqualified_leads($from_date = null, $to_date = null, $user_type = "")
    {
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        // print_r($groups);
        // exit();
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
        $cats = $this->user_categories();
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
                $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status=306 OR l.lead_status=624 OR l.lead_status=606');
        // $this->db->where('la.assigned_to',$this->auth_user_id);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        if($user_type != ""){
            if($user_type == "created"){
                $this->db->where('l.lead_created_by', $this->auth_user_id);
            }
            if($user_type == "assigned"){
                $this->db->where('la.assigned_by', $this->auth_user_id);
            }
            if($user_type == "completed"){
                $this->db->where('l.completed_by', $this->auth_user_id);
            }
        } else {
            if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
                $this->db->where_in('l.category_id', $cats);
            } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
                $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by in (' . implode(",", $group_mems) . ') or l.lead_created_by = ' . $this->auth_user_id . ' 
                    or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
            
            } else {
                // $this->db->where('la.assigned_to', $this->auth_user_id);
                $this->db->where("(la.assigned_to in (" . implode(",", $group_mems) . ") or l.lead_created_by in (" . implode(",", $group_mems) . "))");
            }
        }

        if (!empty($from_date)) {
            $this->db->where('DATE(l.updated_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.updated_at) <=', $to_date);
        }
        $this->db->group_start()
         ->where_not_in('l.lead_source', ['cc-contact'])
         ->or_where('l.lead_source IS NULL', null, false)
         ->group_end();

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }



    public function user_lead_categories()
    {
        $this->db->select('category_id');
        $this->db->from('ontime_user_categories');
        $this->db->where('user_id', $this->auth_user_id);
        $this->db->where('is_active', 1);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function get_biz_leads()
    {

        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        // print_r($groups);
        // exit();
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
	
        $this->db->select("*");
        $this->db->from('biz_leads');
        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
            $this->db->where("lead_created_by", $this->auth_user_id);
        }
        else {
            //$this->db->where("lead_created_by", $this->auth_user_id);
            $this->db->where("(lead_created_by in (" . implode(",", $group_mems) . "))");
        }
        $results = $this->db->get()->result_array();
        
        $leads = [];
        foreach ($results as $res) {
            array_push($leads, $res["biz_lead_id"]);
        }
        // print_r($leads);
        $leads = implode(',', $leads);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://crm.ontimebiz.com/api/v1/lead/crmlist?leads=" . $leads,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //print_r($response);
        return $response;
    }

     public function get_mercato_biz_leads()
    {
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        // print_r($groups);
        // exit();
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

        $this->db->select("*");
        $this->db->from('mercatobiz_leads');
        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
            $this->db->where("lead_created_by", $this->auth_user_id);
        } else {
            //$this->db->where("lead_created_by", $this->auth_user_id);
            $this->db->where("(lead_created_by in (" . implode(",", $group_mems) . "))");
        }
        $results = $this->db->get()->result_array();

        $leads = [];
        foreach ($results as $res) {
            array_push($leads, $res["biz_lead_id"]);
        }
        // print_r($leads);
        $leads = implode(',', $leads);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://crm.mercatobiz.com/api/v1/lead/crmlist?leads=" . $leads,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //print_r($response);
        return $response;
    }

     public function get_ontimevisa_leads()
    {
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        // print_r($groups);
        // exit();
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

        $this->db->select("*");
        $this->db->from('ontimevisa_leads');
        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
            $this->db->where("lead_created_by", $this->auth_user_id);
        } else {
            //$this->db->where("lead_created_by", $this->auth_user_id);
            $this->db->where("(lead_created_by in (" . implode(",", $group_mems) . "))");
        }
        $results = $this->db->get()->result_array();

        $leads = [];
        foreach ($results as $res) {
            array_push($leads, $res["biz_lead_id"]);
        }
        // print_r($leads);
        $leads = implode(',', $leads);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://crm.ontimevisa.com/api/v1/lead/crmlist?leads=" . $leads,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        //print_r($response);
        return $response;
    }

    public function lead_details($lead_id)
    {
        // echo "There";
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,ob.branch_sender_id as sender_id,lg.creator_branch,lg.assigned_branch,la.assigned_by as assigned_by_user, la.assigned_to as assigned_to_user, la.assigned_on as assigned_date');
        $this->db->from('leads as l');
        $this->db->join('lead_groups as lg', 'lg.id=l.id','left');
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

    public function md5_lead_details($lead_id)
    {
        // echo "There";
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,ob.branch_sender_id as sender_id,lg.creator_branch,lg.assigned_branch,la.assigned_by as assigned_by_user, la.assigned_to as assigned_to_user, la.assigned_on as assigned_date');
        $this->db->from('leads as l');
        $this->db->join('lead_groups as lg', 'lg.id=l.id', 'left');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id', "left");
        // $this->db->where('md5(l.id)=' . $lead_id);
        $this->db->where("MD5(l.id)", $lead_id);
        $results = $this->db->get()->row_array();
        // print_r($results);
        // exit();
        return $results;
    }

    public function lead_preview($lead_id)
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.id as category_id,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,IFNULL(lg.creator_branch,  ob.branch_name) as creator_branch, IFNULL(lg.assigned_branch, ob.branch_name) as assigned_branch');
        $this->db->from('leads as l');
                $this->db->join('lead_groups as lg', 'lg.id=l.id','left');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id', "left");
        $this->db->where('l.id', $lead_id);
        $results = $this->db->get()->row_array();
        return $results;
    }

    /**
     * [get_lead_meetings description]
     * @param  [type] $type        [upcoming=>0,completed=1,all=-1]
     * @param  string $crm_user_id [description]
     * @return [type]              [description]
     */
    public function get_lead_meetings($type, $crm_user_id = '')
    {
        $this->db->select('lm.*,u.first_name');
        $this->db->from('lead_meetings as lm');
        $this->db->join('lead_users as u', 'u.user_id=lm.customer_id');
        $this->db->join('leads as l', 'l.id=lm.lead_id');
        if ($type != "-1") {
            $this->db->where('lm.is_complete', $type);
        }
        if ($crm_user_id != '') {
            $this->db->where('lm.crm_user_id', $crm_user_id);
        }
        $results = $this->db->get()->result_array();
        return $results;
    }




    public function get_unused_id()
    {
        // Create a random user id between 1200 and 4,29,49,67,295
        $random_unique_int = 2147483648 + mt_rand(-2147482448, 2147483647);

        // Make sure the random user_id isn't already in use
        $query = $this->db->where('user_id', $random_unique_int)
            ->get_where('users');
        $query_lead_users = $this->db->where('user_id', $random_unique_int)
            ->get_where('lead_users');

        if ($query->num_rows() > 0 || $query_lead_users->num_rows() > 0) {
            $query->free_result();
            $query_lead_users->free_result();

            // If the random user_id is already in use, try again
            return $this->get_unused_id();
        }

        return $random_unique_int;
    }

    // Reports Data 
    public function prepayments($req)
    {

        // print_r($req);
        // exit();
        $cats = $this->user_categories();

        $this->db->select("leads.id as lead_id,leads.lead_type,concat(lead_users.first_name,' ',lead_users.last_name,IF(lead_users.email IS NOT NULL,', <br>',''),lead_users.email,IF(lead_users.mobile != 0,', <br>',''),lead_users.mobile) as customer,concat(users.first_name,' ',users.last_name,' (',users.employee_id,')') as requested_by,lead_action_log.action_amount as amount,lead_status.status_name as status,concat(ontime_branches.branch_name,', <br>',ontime_categories.category_name,', <br>',ontime_category_services_.service_name) as source,lead_action_log.action_on as action_on")->from("lead_action_log");
        $this->db->join("leads", "lead_action_log.lead_id = leads.id");
        $this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
        $this->db->join("users", "users.user_id = lead_action_log.action_by");
        $this->db->join("lead_status", "lead_status.id = lead_action_log.status_id");

        $this->db->join('ontime_branches', 'ontime_branches.branch_code=leads.branch_id');
        $this->db->join('ontime_categories', 'ontime_categories.category_id=leads.category_id');
        $this->db->join('ontime_category_services_', 'ontime_category_services_.service_id=leads.service_id');

        $this->db->where_in("lead_action_log.action_id", [412, 415]);
        if ($req["from"]) {
            $this->db->where("lead_action_log.action_on >=", $req["from"]);
        }
        if ($req["to"]) {
            $this->db->where("lead_action_log.action_on <=", $req["to"]);
        }
        if ($req["branches"]) {
            $branches = explode(",", $req["branches"]);
            $this->db->where_in("leads.branch_id", $branches);
        }

        if ($req["categories"]) {
            $categories = explode(",", $req["categories"]);
            $this->db->where_in("leads.category_id", $categories);
        }

        $this->db->group_by("lead_action_log.id");
        $this->db->where_in("leads.category_id", $cats);

        $data = $this->db->get()->result_array();
        return $data;
    }

    public function lead_complete($req)
    {

        // print_r($req);
        // exit();
        $cats = $this->user_categories();
        $this->db->select("leads.id as lead_id,leads.lead_type,concat(lead_users.first_name,' ',lead_users.last_name,IF(lead_users.email IS NOT NULL,', <br>',''),lead_users.email,IF(lead_users.mobile != 0,', <br>',''),lead_users.mobile) as customer,concat(users.first_name,' ',users.last_name,' (',IF(users.employee_id IS NULL,'0000',users.employee_id),')') as completed_by,concat(creator.first_name,' ',creator.last_name,' (',IF(creator.employee_id IS NULL,'0000',creator.employee_id),')') as created_by,lead_action_log.action_amount as amount,lead_status.status_name as status,concat(ontime_branches.branch_name,', <br>',ontime_categories.category_name,', <br>',ontime_category_services_.service_name) as source,lead_action_log.action_on as action_on,leads.lead_added_on as lead_created_at,CONCAT( FLOOR(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)) / 168), ' WEEKS ',MOD(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)), 24), ' HOURS ', MINUTE(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)), ' MINUTES ') AS timeline,timediff(lead_action_log.action_on,leads.lead_added_on) as mindiff,leads.order_receipt as invoice")->from("lead_action_log");
        $this->db->join("leads", "lead_action_log.lead_id = leads.id");
        $this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
        $this->db->join("users", "users.user_id = lead_action_log.action_by");
        $this->db->join("users as creator", "creator.user_id = leads.lead_created_by");
        $this->db->join("lead_status", "lead_status.id = lead_action_log.status_id");

        $this->db->join('ontime_branches', 'ontime_branches.branch_code=leads.branch_id');
        $this->db->join('ontime_categories', 'ontime_categories.category_id=leads.category_id');
        $this->db->join('ontime_category_services_', 'ontime_category_services_.service_id=leads.service_id');

        $this->db->where("lead_action_log.action_id", 410);
        // $this->db->where("leads.lead_type", "lead");
        if ($req["from"]) {
            $this->db->where("lead_action_log.action_on >=", $req["from"]);
        }
        if ($req["to"]) {
            $this->db->where("lead_action_log.action_on <=", $req["to"]);
        }
        if ($req["branches"]) {
            $branches = explode(",", $req["branches"]);
            $this->db->where_in("leads.branch_id", $branches);
        }

        if ($req["categories"]) {
            $categories = explode(",", $req["categories"]);
            $this->db->where_in("leads.category_id", $categories);
        }

        $this->db->where_in("leads.category_id", $cats);

        $data = $this->db->get()->result_array();

        //print_r($this->db->last_query());
        //exit();
        return $data;
    }

    public function zoho_leads()
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->where('l.lead_status', 309);
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();
        return $results;
    }
    public function get_leadsDatas($group_id,$from_date,$to_date)
    {
        				//Main Leads
				$this->db->select("leads.*,`leads`.`id` AS `lead_id`,`leads`.`lead_status` AS `lead_status`,`leads`.`updated_at` AS `updated_at`,`leads`.`lead_type`,`leads`.`lead_parent_id` as lead_sublead,concat(lead_users.first_name,' ',`lead_users`.`last_name`) AS customer_name,lead_users.email AS customer_email,lead_users.mobile AS customer_mobile,concat(creator.first_name,' ',`creator`.`last_name`,' (',IF(creator.employee_id IS NULL,'0000',creator.employee_id),')') AS created_by,creator_g.group_name AS creator_group_name,creator_b.branch_name AS creator_branch_name,concat(users.first_name,' ',`users`.`last_name`,' (',IF(users.employee_id IS NULL,'0000',users.employee_id),')') AS completed_by,completed_g.group_name AS completed_on_group,completed_b.branch_name AS completed_on_branch,`lead_status`.`status_name` AS `status`,concat(ontime_branches.branch_name,', ',`ontime_categories`.`category_name`,', ',ontime_category_services_.service_name) as source,`lead_action_log`.`action_on` as `action_on`,`leads`.`lead_added_on` as `lead_created_at`,CONCAT( FLOOR(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)) / 168),' WEEKS ',MOD(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),24),' HOURS ',MINUTE(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),' MINUTES ') AS timeline,`leads`.`order_receipt` AS `invoice`")->from("lead_action_log");
				$this->db->join("leads", "lead_action_log.lead_id = leads.id");
				$this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
				$this->db->join("users", "users.user_id = leads.completed_by or users.user_id = lead_action_log.action_by");
				$this->db->join("users AS creator", "creator.user_id = leads.lead_created_by");
				$this->db->join("group_members AS creator_gm", "creator_gm.user_id = creator.user_id");
				$this->db->join("groups AS creator_g", "creator_gm.group_id = creator_g.group_id");
				$this->db->join("ontime_branches AS creator_b", "creator_b.branch_code = creator_g.group_branch_id");
				$this->db->join("group_members AS completed_gm", "completed_gm.user_id = leads.completed_by or completed_gm.user_id = lead_action_log.action_by","left");
				$this->db->join("groups AS completed_g", "completed_gm.group_id = completed_g.group_id","left");
				$this->db->join("ontime_branches AS completed_b", "completed_b.branch_code = completed_g.group_branch_id");
				$this->db->join("lead_status", "lead_status.id = leads.lead_status");
				$this->db->join("ontime_branches", "ontime_branches.branch_code = leads.branch_id");
				$this->db->join("ontime_categories", "ontime_categories.category_id = leads.category_id");
				$this->db->join("ontime_category_services_", "ontime_category_services_.service_id = leads.service_id");
                $this->db->where("completed_gm.group_id",$group_id);
				//$this->db->where("leads.lead_parent_id", 0);
               // $this->db->where("leads.is_assigned", 1);
               // $this->db->where("leads.order_receipt", "0");
              //  $this->db->where('(`leads`.`lead_status` not in (305,306,309))');
				$this->db->group_by("leads.id");
				$this->db->order_by("lead_action_log.id", "DESC");
				$report_main = $this->db->get()->result_array();

				// print($this->db->last_query());
				// exit();


				$main_leads = [];
				foreach ($report_main as $mainlead) {
					array_push($main_leads, $mainlead["lead_id"]);
				}
               // echo "<pre>";print_r($main_leads);exit('text');

				$report_sub = [];
				if (count($main_leads) > 0) {
					//SubLeads
					$this->db->select("leads.*,`leads`.`id` AS `lead_id`,`leads`.`lead_status` AS `lead_status`,`leads`.`updated_at` AS `updated_at`,`leads`.`lead_type`,`leads`.`lead_parent_id` as lead_sublead,concat(lead_users.first_name,' ',`lead_users`.`last_name`) AS customer_name,lead_users.email AS customer_email,lead_users.mobile AS customer_mobile,concat(creator.first_name,' ',`creator`.`last_name`,' (',IF(creator.employee_id IS NULL,'0000',creator.employee_id),')') AS created_by,creator_g.group_name AS creator_group_name,creator_b.branch_name AS creator_branch_name,concat(users.first_name,' ',`users`.`last_name`,' (',IF(users.employee_id IS NULL,'0000',users.employee_id),')') AS completed_by,completed_g.group_name AS completed_on_group,completed_b.branch_name AS completed_on_branch,`lead_status`.`status_name` AS `status`,concat(ontime_branches.branch_name,', ',`ontime_categories`.`category_name`,', ',ontime_category_services_.service_name) as source,`lead_action_log`.`action_on` as `action_on`,`leads`.`lead_added_on` as `lead_created_at`,CONCAT( FLOOR(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)) / 168),' WEEKS ',MOD(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),24),' HOURS ',MINUTE(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),' MINUTES ') AS timeline,`leads`.`order_receipt` AS `invoice`")->from("lead_action_log");
					$this->db->join("leads", "lead_action_log.lead_id = leads.id");
					$this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
					$this->db->join("users", "users.user_id = lead_action_log.action_by");
					$this->db->join("users AS creator", "creator.user_id = leads.lead_created_by");
					$this->db->join("group_members AS creator_gm", "creator_gm.user_id = creator.user_id");
					$this->db->join("groups AS creator_g", "creator_gm.group_id = creator_g.group_id");
					$this->db->join("ontime_branches AS creator_b", "creator_b.branch_code = creator_g.group_branch_id");
					$this->db->join("group_members AS completed_gm", "completed_gm.user_id = lead_action_log.action_by");
					$this->db->join("groups AS completed_g", "completed_gm.group_id = completed_g.group_id");
					$this->db->join("ontime_branches AS completed_b", "completed_b.branch_code = completed_g.group_branch_id");
					$this->db->join("lead_status", "lead_status.id = leads.lead_status");
					$this->db->join("ontime_branches", "ontime_branches.branch_code = leads.branch_id");
					$this->db->join("ontime_categories", "ontime_categories.category_id = leads.category_id");
					$this->db->join("ontime_category_services_", "ontime_category_services_.service_id = leads.service_id");
					// $this->db->where("max(lead_action_log.created_at)");
					$this->db->where_in("leads.lead_parent_id", $main_leads);
					$this->db->group_by("leads.id");
					$this->db->order_by("leads.id", "DESC");
					$results = $this->db->get()->result_array();
                    return $results;
                    //return $data;
                  
				}
               
        
    }
    public function get_userGroup($user_id)
    {
        $this->db->select("groups.*,CONCAT(users.first_name,' ',users.last_name) as creator,count(DISTINCT group_members.group_member_id) as members_count");
        $this->db->from("groups");
        $this->db->join("group_members", "group_members.group_id=groups.group_id", "left");
        $this->db->join("users", "users.user_id=groups.created_by");
        $this->db->group_by("groups.group_id");
        $this->db->order_by("groups.group_name");
        $this->db->where('group_members.user_id',$user_id);
        $results = $this->db->get()->result_array();
        return $results;
    }
    public function assigned_by_coordinator()
    {
        $cats = $this->user_categories();
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
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
            // echo "<pre>";
            // print_r($group_mems);
            // echo "</pre>";
            // exit();
        }

        
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,creator_g.group_name AS creator_group_name,creator_b.branch_name AS creator_branch_name,`lstat`.`status_name` AS `status`, la.assigned_on as assigned_on');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join("group_members AS creator_gm", "creator_gm.user_id = creator.user_id",'left');
        $this->db->join("groups AS creator_g", "creator_gm.group_id = creator_g.group_id",'left');
        $this->db->join("ontime_branches AS creator_b", "creator_b.branch_code = creator_g.group_branch_id",'left');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');

        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {

            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR l.lead_created_by = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            // echo "Hi" . $this->db->last_query();
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by in (' . implode(",", $group_mems) . ') or l.lead_created_by = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else {
            // print_r($this->auth_user_id);
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
            // echo "This is working";
            // $this->db->where('la.assigned_to=',$this->auth_user_id);
        }

        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606))');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        // $this->db->where('la.assigned_by', $this->auth_user_id);
        $this->db->group_by('l.id');

        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // print_r(count($results));

        // exit();
        return $results;
    }

    // For Dashboard
    public function get_leads_country_summary($startDate = NULL, $endDate = NULL, $type = "all")
    {
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }

        $this->db->select('
            n.nationality_id, n.nationality_name, lu.country,
            SUM(if(ls.status_group in (\'inprogress\'), 1, 0)) as inprogress,
            SUM(if(ls.status_group in (\'Disqualified\'), 1, 0)) as disqualified,
            SUM(if(ls.status_group in (\'hold\'), 1, 0)) as onhold,
            SUM(if(ls.status_group in (\'completed\'), 1, 0)) as completed,
            SUM(if(ls.status_group in (\'not started\'), 1, 0)) as `not started`,
            count(l.id) as total_count
        ');
        $this->db->from('leads l');
        $this->db->join("lead_users lu", "lu.user_id = l.customer_id");
        $this->db->join("lead_status ls", "ls.id = l.lead_Status");
        $this->db->join("nationalities n", "n.nationality_id = lu.country", "left");
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->where("l.lead_parent_id=0");
       if(in_array($this->auth_user_role , [7])){
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by IN (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id .')');
        }

        // if($type != 'all'){
        //     if ($startDate !== NULL)
        //         $this->db->where('DATE(l.created_at) >=', $startDate);
        //     if ($endDate !== NULL)
        //         $this->db->where('DATE(l.created_at) <=', $endDate);
        // }

        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(l.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(l.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(l.created_at) <=', $endDate);
            }
        } elseif ($type == "month") {
            if ($startDate !== NULL) {
                $this->db->where('MONTH(l.created_at)', date('m', strtotime($startDate)));
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($startDate)));
            }
            if ($endDate !== NULL) {
                $this->db->where('MONTH(l.created_at)', date('m', strtotime($endDate)));
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($endDate)));
            }
        } elseif ($type == "year") {
            if ($startDate !== NULL)
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($startDate)));
            if ($endDate !== NULL)
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($endDate)));
        }
        $this->db->group_by("n.nationality_id");
        $this->db->order_by("total_count desc, n.nationality_id desc");

        $res = $this->db->get()->result_array();

        return $res;
    }

    public function get_leads_assigned_summary($startDate = NULL, $endDate = NULL, $type = "all")
    {
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }

        $this->db->select('
            SUM(if(ls.status_group in (\'inprogress\'), 1, 0)) as inprogress,
            SUM(if(ls.status_group in (\'Disqualified\'), 1, 0)) as disqualified,
            SUM(if(ls.status_group in (\'hold\'), 1, 0)) as onhold,
            SUM(if(ls.status_group in (\'completed\'), 1, 0)) as completed,
            SUM(if(ls.status_group in (\'not started\'), 1, 0)) as `not started`,
            count(l.id) as total_count,
            la.assigned_to, u.user_id, u.first_name, u.last_name,u.profile_pic
            ');
        $this->db->from('leads l');
        $this->db->join("lead_users lu", "lu.user_id = l.customer_id");
        $this->db->join("lead_status ls", "ls.id = l.lead_Status");
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        $this->db->join("users u", "u.user_id = la.assigned_to");

        // if($type != 'all'){
        //     if ($startDate !== NULL)
        //         $this->db->where('DATE(l.created_at) >=', $startDate);
        //     if ($endDate !== NULL)
        //         $this->db->where('DATE(l.created_at) <=', $endDate);
        // }

        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(l.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(l.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(l.created_at) <=', $endDate);
            }
        } elseif ($type == "month") {
            if ($startDate !== NULL) {
                $this->db->where('MONTH(l.created_at)', date('m', strtotime($startDate)));
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($startDate)));
            }
            if ($endDate !== NULL) {
                $this->db->where('MONTH(l.created_at)', date('m', strtotime($endDate)));
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($endDate)));
            }
        } elseif ($type == "year") {
            if ($startDate !== NULL)
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($startDate)));
            if ($endDate !== NULL)
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime($endDate)));
        }
        $this->db->where("l.lead_parent_id=0");
        if(in_array($this->auth_user_role, [7])){
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        $this->db->group_by("la.assigned_to");
        $this->db->order_by("inprogress desc");

        $res = $this->db->get()->result_array();

        return $res;
    }

    public function get_leads_country_summary_details($country, $status, $startDate = NULL, $endDate = NULL, $type = "all")
    {
        // echo "country=>" . $country . "<br>status=>" . $status . "<br>startDate=>" . $startDate . "<br>endDate=>" . $endDate . "<br>Type=>" . $type . "<br>";
        $groups = $this->db->select('distinct(group_id)')
            ->from("group_members")
            ->where("user_id", $this->auth_user_id)
            ->get()->result_array();

        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");

        $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }

        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }
        $this->db->select('
            u.country, lstat.status_group as status_group,
            l.created_at as created_date, date(l.updated_at) as updated_date,
            l.id, u.country_code AS customer_country_code,
            IF(u.first_name IS NOT NULL, u.first_name, u.last_name) AS customer_name,
            u.mobile as customer_mobile, u.email as customer_email,
            lstat.status_name AS current_status, COUNT(sub_leads.id) AS total_subleads,
            SUM(IF(sl_la.assigned_to IS NOT NULL, 1, 0)) AS assigned_subleads,
            SUM(IF(sub_leads.lead_status = 623, 1, 0)) AS completed_subleads');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id', 'left');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id', 'left');
        $this->db->join('leads as sub_leads', 'sub_leads.lead_parent_id = l.id', 'left');
        $this->db->join('leads_assigned as sl_la', 'sl_la.lead_id = sub_leads.id', 'left');
        $this->db->join("nationalities", "nationalities.nationality_id = u.country", "left");
        $this->db->where("l.lead_parent_id=0");

        if(in_array($this->auth_user_role, [7])){
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by IN (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id .')');
        }

        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(l.created_at) =', urldecode($startDate));
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(l.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(l.created_at) <=', $endDate);
            }
        } elseif ($type == "month") {
            if ($startDate !== NULL) {
                $this->db->where('MONTH(l.created_at)', date('m', strtotime(urldecode($startDate))));
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime(urldecode($startDate))));
            }
            if ($endDate !== NULL) {
                $this->db->where('MONTH(l.created_at)', date('m', strtotime(urldecode($endDate))));
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime(urldecode($endDate))));
            }
        } elseif ($type == "year") {
            if ($startDate !== NULL)
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime(urldecode($startDate))));
            if ($endDate !== NULL)
                $this->db->where('YEAR(l.created_at)', date('Y', strtotime(urldecode($endDate))));
        }
        // $this->db->group_by("lead_users.country,status_group");
        $this->db->where("u.country", urldecode($country));
        $this->db->where("lstat.status_group", urldecode($status));
        $this->db->group_by("l.id");
        $this->db->order_by("id desc");

        $res = $this->db->get();
        // print_r($this->db->error());
        $res = $res->result_array();
        // print_r($this->db->last_query());
        // exit();
        // $data = [];
        // foreach($res as $datum){
        //     $data[strval($datum->country)][strval($datum->status_group)] = $datum;
        // }
        // foreach($res as $datum){
        //     $data[strval($datum->country)]["country"] = $datum;
        // }
        return $res;
    }

    public function get_leads_assigned_summary_details($user_id, $status_group, $startDate = NULL, $endDate = NULL, $type = "all")
    {
        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }
        $this->db->select('
            leads.id,
            users.first_name,
            users.last_name,
            users.user_id,
            users.profile_pic,
            lstat.status_group as status_group,
            date(leads.created_at) as created_date,
            date(leads.updated_at) as updated_date,
            lead_users.country_code AS customer_country_code,
            IF(lead_users.first_name IS NOT NULL, lead_users.first_name, lead_users.last_name) AS customer_name,
            lead_users.mobile as customer_mobile,
            lead_users.email as customer_email,
            lstat.status_name AS current_status,
            ');
        $this->db->from('leads');
        $this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
        $this->db->join("lead_status as lstat", "lstat.id = leads.lead_Status");
        $this->db->join("leads_assigned", "leads_assigned.lead_id = leads.id");
        $this->db->join("users", "users.user_id = leads_assigned.assigned_to");

        // $this->db->join('lead_status ', 'lstat.id = l.lead_status');

        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(leads.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(leads.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(leads.created_at) <=', $endDate);
            }
        } elseif ($type == "month") {
            if ($startDate !== NULL) {
                $this->db->where('MONTH(leads.created_at)', date('m', strtotime($startDate)));
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($startDate)));
            }
            if ($endDate !== NULL) {
                $this->db->where('MONTH(leads.created_at)', date('m', strtotime($endDate)));
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($endDate)));
            }
        } elseif ($type == "year") {
            if ($startDate !== NULL)
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($startDate)));
            if ($endDate !== NULL)
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($endDate)));
        }
        $this->db->where("leads.lead_parent_id=0");
        $this->db->where("users.user_id", $user_id);
        $this->db->where("lstat.status_group", $status_group);
        // $this->db->order_by("total_count desc");

        $res = $this->db->get()->result_array();
        return $res;
    }

    public function get_leads_summary($startDate = NULL, $endDate = NULL, $type = "all")
    {
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }

        $this->db->select('
            IFNULL(SUM(IF(leads.lead_parent_id = 0, 1, 0)), 0) AS total_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'Disqualified\'), 1, 0)), 0) AS disqualified_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'hold\'), 1, 0)), 0) AS onhold_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'completed\'), 1, 0)), 0) AS completed_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'inprogress\'), 1, 0)), 0) AS inprogress_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'not started\'), 1, 0)), 0) AS not_started_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND leads.lead_zoho_id IS NOT NULL AND leads.lead_zoho_leadid IS NOT NULL, 1, 0)),0) AS online_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'Disqualified\') AND leads.lead_zoho_leadid IS NOT NULL, 1, 0)), 0) AS online_disqualified_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'hold\') AND leads.lead_zoho_leadid IS NOT NULL, 1, 0)), 0) AS online_onhold_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'completed\') AND leads.lead_zoho_leadid IS NOT NULL, 1, 0)), 0) AS online_completed_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'inprogress\') AND leads.lead_zoho_leadid IS NOT NULL, 1, 0)), 0) AS online_inprogress_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'not started\') AND leads.lead_zoho_leadid IS NOT NULL, 1, 0)), 0) AS online_notstarted_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND leads.lead_zoho_leadid IS NULL AND (leads.lead_zoho_leadid IS NULL OR leads.lead_zoho_leadid = ""), 1, 0)),0) AS walkin_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'Disqualified\') AND leads.lead_zoho_leadid IS NULL AND (leads.lead_zoho_leadid IS NULL OR leads.lead_zoho_leadid = ""), 1, 0)), 0) AS walkin_disqualified_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'hold\') AND leads.lead_zoho_leadid IS NULL AND (leads.lead_zoho_leadid IS NULL OR leads.lead_zoho_leadid = ""), 1, 0)), 0) AS walkin_onhold_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'completed\') AND leads.lead_zoho_leadid IS NULL AND (leads.lead_zoho_leadid IS NULL OR leads.lead_zoho_leadid = ""), 1, 0)), 0) AS walkin_completed_leads,
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'inprogress\') AND leads.lead_zoho_leadid IS NULL AND (leads.lead_zoho_leadid IS NULL OR leads.lead_zoho_leadid = ""), 1, 0)), 0) AS walkin_inprogress_leads,    
            IFNULL(SUM(IF(leads.lead_parent_id = 0 AND lstat.status_group IN (\'not started\') AND leads.lead_zoho_leadid IS NULL AND (leads.lead_zoho_leadid IS NULL OR leads.lead_zoho_leadid = ""), 1, 0)), 0) AS walkin_notstarted_leads,          
            DATE(leads.created_at) AS created_date,
            DATE(leads.updated_at) AS updated_date
        ');
        $this->db->from('leads');
        $this->db->join('lead_status as lstat', 'lstat.id = leads.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=leads.id');
        $this->db->where('leads.lead_parent_id=0');
        // $this->db->where('lstat.status_group != "not started"');
        if(in_array($this->auth_user_role, [7])){
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or  la.assigned_to in (' . implode(",", $group_mems) . ') or leads.lead_created_by IN (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or leads.lead_created_by = ' . $this->auth_user_id .')');
        }

        // if($type != 'all'){
        //     if ($startDate !== NULL)
        //         $this->db->where('DATE(leads.created_at) >=', $startDate);
        //     if ($endDate !== NULL)
        //         $this->db->where('DATE(leads.created_at) <=', $endDate);
        // }

        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(leads.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(leads.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(leads.created_at) <=', $endDate);
            }
        } elseif ($type == "month") {
            if ($startDate !== NULL) {
                $this->db->where('MONTH(leads.created_at)', date('m', strtotime($startDate)));
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($startDate)));
            }
            if ($endDate !== NULL) {
                $this->db->where('MONTH(leads.created_at)', date('m', strtotime($endDate)));
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($endDate)));
            }
        } elseif ($type == "year") {
            if ($startDate !== NULL)
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($startDate)));
            if ($endDate !== NULL)
                $this->db->where('YEAR(leads.created_at)', date('Y', strtotime($endDate)));
        } 

        return $this->db->get()->row();
    }

    public function get_potential_summary($startDate = NULL, $endDate = NULL, $type = "all")
    {
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        $this->db->select('l.*,
        u.country_code as customer_country_code,if(u.first_name is not null,u.first_name,u.last_name) as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,count(laction.id) as action_count,
        sum(if(l.lead_zoho_id is null or (l.lead_zoho_leadid is null),1,0)) over() as walkin_potentials,
        sum(if(l.lead_zoho_id is not null and (l.lead_zoho_leadid is not null),1,0)) over() as online_potentials,
        if(l.lead_zoho_id is not null and (l.lead_zoho_leadid IS NOT NULL or l.lead_zoho_leadid != ""), 1,0) as zoho_id
        ');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('lead_action_log as laction', 'laction.lead_id=l.id');

        if(in_array($this->auth_user_role, [7])){
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and (la.assigned_to in (' . implode(",", $group_mems) . ') OR l.lead_created_by in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and (la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = '.$this->auth_user_id.')');
        }

        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(l.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(l.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(l.created_at) <=', $endDate);
            }
        }

        $this->db->where('l.lead_parent_id', 0);
        $this->db->where('l.is_assigned', 1);
        // $this->db->where('lstat.status_group != "not started"');
        $this->db->group_start()
            ->where('l.order_receipt', '0')
            ->or_where('l.order_receipt', '')
            ->or_where('l.order_receipt IS NULL');
        $this->db->group_end();
        $this->db->group_start();
            $this->db->group_start();
                $this->db->where('l.lead_zoho_id IS NULL');
                $this->db->group_start();
                    $this->db->where('l.lead_by_post_user_name IS NULL');
                    $this->db->or_where_not_in('l.lead_from', ['Zoho Missed', 'Zoho AOH', 'Zoho Chats', 'Zoho CRM', 'Zoho Social', 'OntimeGOV', 'GoldenCube', 'Baraha Van']);
                $this->db->group_end();
                $this->db->where_in('l.lead_status', [605, 612, 626, 627]);
                $this->db->or_group_start();
                    $this->db->where_in('l.lead_status', [629]);
                    $this->db->where("l.contactable_date >=", date('Y-m-d H:i:s'));
                $this->db->group_end();
                $this->db->where_not_in('l.lead_status', [606, 623, 624]);
            $this->db->group_end();
            $this->db->or_group_start();
                $this->db->where('l.lead_zoho_id IS NOT NULL');
                $this->db->where_in('l.lead_from', ['Zoho Missed', 'Zoho AOH', 'Zoho Chats', 'Zoho CRM', 'Zoho Social', 'OntimeGOV', 'GoldenCube', 'Baraha Van']);
                $this->db->group_start();
                    $this->db->where_in('l.lead_status', [602, 609, 626, 627]);
                    $this->db->or_group_start();
                        $this->db->where_in('l.lead_status', [629]);
                        $this->db->where("l.contactable_date >=", date('Y-m-d H:i:s'));
                    $this->db->group_end();
                $this->db->group_end();
            $this->db->group_end();
        $this->db->group_end();        
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function get_leads_summary_details($startDate = NULL, $endDate = NULL, $type = "all")
    {
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }
        $this->db->select('
            lstat.status_name as status,
            lstat.status_group as status_group,
            date(l.created_at) as created_date,
            date(l.updated_at) as updated_date,
            l.id, 
            IF(l.lead_zoho_id IS NOT NULL AND (l.lead_zoho_leadid IS NOT NULL OR l.lead_zoho_leadid = ""), 1,0) AS lead_zoho_id,
            u.country_code AS customer_country_code,
            IF(u.first_name IS NOT NULL, u.first_name, u.last_name) AS customer_name,
            concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,
            gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_to as assigned_to,
            u.mobile as customer_mobile,
            u.email as customer_email,
            lstat.status_name AS current_status,
            IF(cu.first_name IS NOT NULL, cu.first_name,cu.last_name) AS created_user_first_name,
            cu.last_name as created_user_last_name,
            cu.email as created_user_email,
            IF(lau.first_name IS NOT NULL, lau.first_name, lau.last_name) AS lead_assigned_user_first_name,
            IF(lau.last_name IS NOT NULL, lau.last_name, lau.last_name) AS lead_assigned_user_last_name,
            lau.email as lead_assigned_email,
            COUNT(sub_leads.id) AS total_subleads,
            SUM(IF(sl_la.assigned_to IS NOT NULL, 1, 0)) AS assigned_subleads,
            SUM(IF(sub_leads.lead_status = 623, 1, 0)) AS completed_subleads
        ');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id', 'left');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by', 'left');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id', 'left');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id', 'left');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id', 'left');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id', 'left');
        $this->db->join('users as cu', 'cu.user_id = l.lead_created_by', 'left');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id', 'left');
        $this->db->join('leads as sub_leads', 'sub_leads.lead_parent_id = l.id', 'left');
        $this->db->join('leads_assigned as sl_la', 'sl_la.lead_id = sub_leads.id', 'left');
        $this->db->join('users as lau', 'lau.user_id = la.assigned_to', 'left');
        $this->db->where('l.lead_parent_id=0');
        // $this->db->where('lstat.status_group != "not started"');
        if(in_array($this->auth_user_role, [7])){
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by IN (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id .')');
        }
        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(l.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(l.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(l.created_at) <=', $endDate);
            }
        }
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        // return $this->db->get()->result_array();
        $results = $this->db->get()->result_array();

        // print_r($this->db->last_query());
        // exit();
        return $results;


    }

    public function check_alpha_pro_invoice($user_id)
    {
        $this->db->select("g.alpha_pro_invoice")->from("users as u");
        $this->db->join("group_members as gm", "u.user_id=gm.user_id");
        $this->db->join("groups as g", "g.group_id = gm.group_id");
        $this->db->where("u.is_active", 1);
        $this->db->where("gm.is_primary_group_id", 1);
        $this->db->where("u.user_id", $user_id);
        $results = $this->db->get()->result_array();
        return $results[0]['alpha_pro_invoice'];
    }

    public function user_primary_group($user_id)
    {
        $this->db->select("g.group_id, g.group_name")->from("users as u");
        $this->db->join("group_members as gm", "u.user_id=gm.user_id");
        $this->db->join("groups as g", "g.group_id = gm.group_id");
        $this->db->where("u.is_active", 1);
        $this->db->where("gm.is_primary_group_id", 1);
        $this->db->where("u.user_id", $user_id);
        $results = $this->db->get()->result_array();

        return $results[0]['group_id'];
    }

    public function get_lead_package_entries($lead_id, $lead_package_id, $gc_package_random_string = '')
    {
        /* $this->db->select("lp.*,lps.*,ls.category_id, ls.service_name");
        $this->db->from('lead_packages as lp');
        $this->db->join('lead_package_services as lps', 'lps.package_id=lp.package_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=lps.service_id');
        $this->db->join('lead_package_details as lpd', 'lpd.package_id = lp.package_id and lpd.service_id = lps.service_id'); */

        $this->db->select("ls.service_name, lpd.created_by, lpd.govt_fee, ls.category_id, ls.service_id, lpd.package_id, 
            lpd.is_direct_invoice, lpd.typing_fee, lpd.msd_key, lpd.is_pos_typing_fee, lpd.*, lps.* ");
        $this->db->from('lead_package_details as lpd');
        $this->db->join('lead_package_services as lps', 'lpd.service_id = lps.service_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=lps.service_id');

        // $this->db->where('lp.package_id', $package_id);
        $this->db->where('lpd.lead_id', $lead_id);
        $this->db->where('lpd.package_id', $lead_package_id);
        $this->db->order_by('ISNULL(lpd.show_order), lpd.show_order', 'ASC');
        // $this->db->where('lpd.gc_package_random_string', $gc_package_random_string);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function user_primary_group_branch($user_id)
    {
        $this->db->select("`g`.group_id, `g`.group_name, g.group_branch_id")->from("users as u");
        $this->db->join("group_members as gm", "u.user_id=gm.user_id");
        $this->db->join("groups as g", "g.group_id = gm.group_id");
        $this->db->where("u.is_active", 1);
        $this->db->where("gm.is_primary_group_id", 1);
        $this->db->where("u.user_id", $user_id);
        $results = $this->db->get()->result_array();
        return $results[0]['group_branch_id'];
    }

    public function card_leaddetails($lead_id)
    {
        $this->db->select('l.id as lead_id,lu.first_name as customer_name,lu.country_code as customer_country_code,lu.mobile as customer_mobile,lu.email as customer_email,us.employee_id,l.pos_cust_key');
        $this->db->from('leads as l');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->join('users as us', 'us.user_id = la.assigned_to');
        $this->db->join('lead_users as lu', 'lu.user_id=l.customer_id');
        $this->db->where('l.id', $lead_id);
        $results = $this->db->get()->row_array();
        return $results;
    }

    public function next_contactable_leads($startDate = NULL, $endDate = NULL, $type = "all"){
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }

        $this->db->select("
            sum(if(l.lead_status IN (630), 1, 0)) over() as next_contactable_leads,
            l.id,
            IF(lu.first_name IS NOT NULL, `lu`.`first_name`,lu.last_name) AS customer_name,
            lu.mobile customer_mobile,
            DATEDIFF(l.contactable_date, now()) remain_days,
            l.contactable_date,
            u.first_name as assigned_user,
            u.email as assigned_email,
            ls.status_name,
            l.lead_status
        ");
        $this->db->from('leads l');
        $this->db->join("lead_users lu",'lu.user_id = l.customer_id');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        $this->db->join("lead_status ls", "ls.id = l.lead_status");
        $this->db->join("users u",'u.user_id = la.assigned_to','left');

        if(in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        $this->db->where("l.lead_parent_id", 0);

        // $this->db->group_start();
        $this->db->group_start();
            $this->db->where_in("l.lead_status", [630]);
            $this->db->where("l.contactable_date >=", date('Y-m-d H:i:s'));
        $this->db->group_end();
        // $this->db->or_where_in("l.lead_status", [626,627]);
        // $this->db->group_end();
        $this->db->group_by('l.id');
        $query = $this->db->get();

        $data = array();
        if($query !== FALSE && $query->num_rows() > 0){
            $data = $query->result_array();
        }

        return $data;
    }

    public function gc_lead_source_leads($startDate = NULL, $endDate = NULL, $type = "all"){
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }

        $this->db->select('
            IFNULL(SUM(IF(l.lead_parent_id = 0 AND l.branch_id = 106 AND l.lead_source IN (\'Walk-in\'), 1, 0)), 0) AS gc_walkin_leads,
            IFNULL(SUM(IF(l.lead_parent_id = 0 AND l.branch_id = 106 AND l.lead_source IN (\'WhatsApp\'), 1, 0)), 0) AS gc_whatsapp_leads,
            IFNULL(SUM(IF(l.lead_parent_id = 0 AND l.branch_id = 106 AND l.lead_source IN (\'Email\'), 1, 0)), 0) AS gc_email_leads,
        ');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id', 'left');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by', 'left');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id', 'left');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id', 'left');
        $this->db->join('users as cu', 'cu.user_id = l.lead_created_by', 'left');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id', 'left');
        $this->db->join('users as lau', 'lau.user_id = la.assigned_to', 'left');
        $this->db->where('l.lead_parent_id=0');
        if(in_array($this->auth_user_role, [7])){
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by IN (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id .')');
        }
        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(l.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(l.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(l.created_at) <=', $endDate);
            }
        }
        // $this->db->group_by('l.id');
        // $this->db->order_by('l.contactable_date', 'DESC');
        // return $this->db->get()->result_array();
        $results = $this->db->get()->row();
        // echo '<pre>';print_r($this->db->last_query());exit();
        
        return $results;
    }

    public function get_leads_source_details($startDate = NULL, $endDate = NULL, $type = "all", $status = 'walkin')
    {
        $group = get_groups($this->auth_user_id);
        $this->db->select("distinct(group_members.user_id)")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        if ($startDate == NULL && $endDate == NULL) {
            $startDate = date("Y-m-d");
        }
        $this->db->select('
            lstat.status_name as status,
            lstat.status_group as status_group,
            date(l.created_at) as created_date,
            date(l.updated_at) as updated_date,
            l.id, 
            IF(l.lead_zoho_id IS NOT NULL AND (l.lead_zoho_leadid IS NOT NULL OR l.lead_zoho_leadid = ""), 1,0) AS lead_zoho_id,
            u.country_code AS customer_country_code,
            IF(u.first_name IS NOT NULL, u.first_name, u.last_name) AS customer_name,
            concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,
            gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_to as assigned_to,
            u.mobile as customer_mobile,
            u.email as customer_email,
            lstat.status_name AS current_status,
            IF(creator.first_name IS NOT NULL, creator.first_name,creator.last_name) AS created_user_first_name,
            creator.last_name as created_user_last_name, creator.email as created_user_email,
            IF(lau.first_name IS NOT NULL, lau.first_name, lau.last_name) AS lead_assigned_user_first_name,
            IF(lau.last_name IS NOT NULL, lau.last_name, lau.last_name) AS lead_assigned_user_last_name,
            lau.email as lead_assigned_email,
        ');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id', 'left');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by', 'left');
        //    $this->db->join('group_members as gm', 'gm.user_id = creator.user_id', 'left');
        // $this->db->join('groups as g ', 'g.group_id = gm.group_id', 'left');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id', 'left');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id', 'left');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id', 'left');
        $this->db->join('users as lau', 'lau.user_id = la.assigned_to', 'left');
        $this->db->where('l.lead_parent_id=0 and l.branch_id = 106');

        if($status == 'walkin'){
            $this->db->where('l.lead_source = "Walk-in"');
        } elseif($status == 'whatsapp'){
            $this->db->where('l.lead_source = "WhatsApp"');
        } elseif($status == 'email'){
            $this->db->where('l.lead_source = "Email"');
        } else {
            $this->db->where('l.lead_source = "Walk-in"');
        }

        if(in_array($this->auth_user_role, [7])){
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by IN (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id .')');
        }
        if ($type == "date") {
            if ($startDate != NULL && $endDate == NULL) {
                $this->db->where('DATE(l.created_at) =', $startDate);
            } else {
                if ($startDate !== NULL)
                    $this->db->where('DATE(l.created_at) >=', $startDate);
                if ($endDate !== NULL)
                    $this->db->where('DATE(l.created_at) <=', $endDate);
            }
        }
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();

        // print_r($this->db->last_query());
        // exit();
        return $results;


    }

        public function fetch_quotation_details($lead_id)
    {
        $this->db->select("
            qh.main_lead_id as main_lead_id, lu.first_name, lu.mobile, lu.email,
            qh.Cust_Key, qh.Created_By, qh.Payment_Type,
            GROUP_CONCAT(qh.sub_lead_id ORDER BY qh.sub_lead_id SEPARATOR ',') AS sub_lead_id,
            GROUP_CONCAT(qd.MainService ORDER BY qd.MainService SEPARATOR ',') AS MainServices,
            GROUP_CONCAT(qd.SubService ORDER BY qd.SubService SEPARATOR ',') AS SubServices,
            GROUP_CONCAT(qd.Quantity ORDER BY qd.Quantity SEPARATOR ',') AS Quantities,
            GROUP_CONCAT(qd.GovtFee ORDER BY qd.GovtFee SEPARATOR ',') AS GovtFees,
            GROUP_CONCAT(qd.TypingFee ORDER BY qd.Quo_Headnum SEPARATOR ',') AS TypingFees,
            GROUP_CONCAT(qd.IsTaxable ORDER BY qd.IsTaxable SEPARATOR ',') AS IsTaxable,
            GROUP_CONCAT(qd.TaxAmount ORDER BY qd.TaxAmount SEPARATOR ',') AS TaxAmounts,
            GROUP_CONCAT(qd.BotButton ORDER BY qd.BotButton SEPARATOR ',') AS BotButtons,
            GROUP_CONCAT(qd.CC_Key ORDER BY qd.CC_Key SEPARATOR ',') AS CC_Keys,
        ", false);
        $this->db->from('trn_quotationdetail as qd');
        $this->db->join('trn_quotationheader as qh', 'qh.Quo_Headnum = qd.Quo_Headnum', 'left');
        $this->db->join('leads as l', 'l.id = qh.main_lead_id', 'left');
        $this->db->join('lead_users as lu', 'lu.user_id = l.customer_id', 'left');
        $this->db->where('qh.main_lead_id', $lead_id);
        $this->db->where('qh.DocNumb', 1);


        $query = $this->db->get();
        return $query->row_array();
    }

    public function quotation_price_type($lead_id)
    {
        $this->db->select("
        qh.DocNumb, 
        lu.first_name,
        qh.Payment_Type,
        GROUP_CONCAT(qd.Quo_Headnum ORDER BY qd.Quo_Headnum SEPARATOR ',') AS Quo_Headnum,
        GROUP_CONCAT(qd.Quantity ORDER BY qd.Quo_Headnum SEPARATOR ',') AS Quantity,
        GROUP_CONCAT(qd.GovtFee ORDER BY qd.Quo_Headnum SEPARATOR ',') AS GovtFee,
        GROUP_CONCAT(qd.TypingFee ORDER BY qd.Quo_Headnum SEPARATOR ',') AS TypingFee,
        GROUP_CONCAT(qd.IsTaxable ORDER BY qd.Quo_Headnum SEPARATOR ',') AS IsTaxable,
        GROUP_CONCAT(qd.TaxAmount ORDER BY qd.Quo_Headnum SEPARATOR ',') AS TaxAmount
        ", false);
        $this->db->from('trn_quotationdetail as qd');
        $this->db->join('trn_quotationheader as qh', 'qh.Quo_Headnum = qd.Quo_Headnum', 'left');
        $this->db->join('leads as l', 'l.id = qh.main_lead_id', 'left');
        $this->db->join('lead_users as lu', 'lu.user_id = l.customer_id', 'left');
        $this->db->where('qh.main_lead_id', $lead_id);
        $this->db->group_by('qh.DocNumb');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_dld_available_users()
    {
        $dld_group = 58;    //get_groups($this->auth_user_id);
        // fetch all group members of the DLD group with active users in users table is_available = 1 or 0
        $this->db->select("distinct(group_members.user_id), users.user_id, users.first_name, users.last_name, users.email, users.mobile, users.country_code, users.is_available, users.is_active, group_members.group_id, group_members.is_primary_group_id
        ")->from("group_members");
        $this->db->join("users", "users.user_id=group_members.user_id");
        $this->db->where("users.is_active", 1); 
        // $this->db->where("users.is_available", 1);
        $this->db->where("group_members.group_id", $dld_group);
        if(!in_array($this->auth_user_role, [7])){
            $this->db->where("group_members.user_id", $this->auth_user_id);
        }
        $members = $this->db->get()->result_array();
        return $members;        
    }

    public function get_opened_leads_count($user_id, $group_id)
    {
        $this->db->select('COUNT(l.id) as opened_leads_count');
        $this->db->from('leads as l');
        $this->db->join('leads_assigned as la', 'la.lead_id = l.id');
        $this->db->where_not_in('l.lead_status', [305, 306, 606, 623, 624]);
        $this->db->where('la.assigned_to', $user_id);
        $this->db->where('l.created_group_id', $group_id);
        $result = $this->db->get()->row_array();
        return $result['opened_leads_count'];
    }

    public function get_lead_tasks() {
        $group = get_groups($this->auth_user_id);

        // Get all group members who are active
        $members = $this->db->select("group_members.user_id")
            ->from("group_members")
            ->join("users", "users.user_id = group_members.user_id")
            ->where("users.is_active", 1)
            ->where_in("group_members.group_id", $group)
            ->get()->result_array();
        $group_mems = array_column($members, "user_id");
        
        $this->db->select('
            l.id AS lead_id,
            l.lead_parent_id,
            l.task_created_at,
            l.task_remarks,
            l.pre_approval_status,
            l.eid_status,
            l.visa_applicant,
            l.block_category,
            l.property_value,
            l.estimated_date,
            ifnull(ts.status_group, l.task_status) as task_status,
            vs.status_name as visa_status,
            u.first_name AS assigned_user,
            u.email AS assigned_email,
            ls.status_name,
            l.lead_status,
            IF(lu.first_name IS NOT NULL, lu.first_name, lu.last_name) AS customer_name,
            lu.mobile AS customer_mobile,
            os.service_name as service_name,
            os.id as service_id,
            ocs.category_id as category_id,
            g.group_name
        ');
        $this->db->from('leads as l');
        $this->db->join('users as u', 'u.user_id = l.task_assigned', 'left');
        $this->db->join("lead_status ls", "ls.id = l.lead_status");
        $this->db->join("lead_users lu", "lu.user_id = l.customer_id");
		$this->db->join("ontime_category_services_ ocs", "ocs.id = l.service_id", 'left');
        $this->db->join("ontime_services os", "os.id = ocs.gc_service_id", 'left');
        $this->db->join("groups g", "g.group_id = os.service_group", 'left');
        $this->db->join("task_status ts", "ts.id = l.task_status", 'left');
        $this->db->join("task_status vs", "vs.id = l.visa_status", 'left');
        if (in_array($this->auth_user_role, [7])) {
            if (!empty($group_mems)) {
                $this->db->where_in("l.task_assigned", $group_mems);
            } else {
                $this->db->where("l.task_assigned", 0);
            }
        } else {
            $this->db->where("l.task_assigned", $this->auth_user_id);
        }
        $this->db->where('l.task_created', 1);
        $this->db->where_not_in('l.lead_status', [306, 606, 624]);
        $this->db->order_by('l.task_created_at', 'ASC');
        $query = $this->db->get();

        $tasks = $query->result_array();

        foreach ($tasks as &$task) {
            $task_created_at = new DateTime($task['task_created_at']);
            $end = new DateTime();
            $workingHours = 0;

            $current = $task_created_at;
            while ($current < $end) {
                // Skip weekends
                if (in_array($current->format('N'), [6, 7])) {
                    $current->modify('+1 day')->setTime(9, 0);
                    continue;
                }

                $dayStart = (clone $current)->setTime(9, 0);
                $dayEnd = (clone $current)->setTime(17, 0);

                $from = max($current, $dayStart);
                $to = min($end, $dayEnd);

                if ($from < $to) {
                    $interval = $from->diff($to);
                    $workingHours += $interval->h + ($interval->i / 60);
                }

                $current->modify('+1 day')->setTime(9, 0);
            }

            // Add SLA violated column
            $task['sla_violated'] = ($workingHours > 3 && $task['task_status'] == 'open') ? 'Yes' : 'No';
        }

        return $tasks;
    }

    public function getTasksCount() {
        $group = get_groups($this->auth_user_id);

        // Get all group members who are active
        $members = $this->db->select("group_members.user_id")
            ->from("group_members")
            ->join("users", "users.user_id = group_members.user_id")
            ->where("users.is_active", 1)
            ->where_in("group_members.group_id", $group)
            ->get()->result_array();

        $group_mems = array_column($members, "user_id");
        $this->db->select('
           	os.id,
            os.service_name, 
            COUNT(os.service_name) AS task_count
        ');
        $this->db->from('leads as l');
        $this->db->join('leads as pl', 'pl.id = l.lead_parent_id');
        $this->db->join('users as u', 'u.user_id = l.task_assigned', 'left');
        $this->db->join("lead_status ls", "ls.id = l.lead_status");
        $this->db->join("lead_users lu", "lu.user_id = l.customer_id");
		$this->db->join("ontime_category_services_ ocs", "ocs.id = l.service_id", 'left');
        $this->db->join("ontime_services os", "os.id = ocs.gc_service_id", 'left');
        $this->db->join("groups g", "g.group_id = os.service_group", 'left');
        if (in_array($this->auth_user_role, [7])) {
            if (!empty($group_mems)) {
                $this->db->where_in("l.task_assigned", $group_mems);
            } else {
                $this->db->where("l.task_assigned", 0);
            }
        } else {
            $this->db->where("l.task_assigned", $this->auth_user_id);
        }
        $this->db->where('l.task_created', 1);
        $this->db->where_not_in('pl.lead_status', [306, 606, 624]);
        $this->db->group_by('os.id');
        $this->db->order_by('l.task_created_at', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function cc_contacts($from_date = null, $to_date = null) // unassigned leads
    {
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        $this->db->select('leads.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lc.category_id as category_id, concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator, gc.group_name as creator_group')->from("leads");

        $this->db->join('lead_users as u', 'u.user_id=leads.customer_id');
        $this->db->join('users as creator', 'creator.user_id=leads.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = leads.created_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=leads.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=leads.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=leads.lead_status');
        $this->db->where('leads.is_assigned', 0);
        
        $this->db->where("(leads.created_group_id in (" . implode(",", $user_group_ids) . ") 
        or leads.assigned_group_id in (" . implode(",", $user_group_ids) . ")
        or leads.lead_created_by = " . $this->auth_user_id . ")");
        
        $this->db->where('leads.lead_parent_id', 0);
        $this->db->where('leads.lead_status not in (305,306,309,624,606)');
        $this->db->where('leads.lead_source', 'cc-contact');
        $this->db->where('leads.branch_id = 123');
        if (!empty($from_date) && $from_date != NULL && !is_array($from_date)) {
            $this->db->where('DATE(leads.created_at) >=', $from_date);
        }
        if (!empty($to_date) && $to_date != NULL && !is_array($to_date)) {
            $this->db->where('DATE(leads.created_at) <=', $to_date);
        }

        $this->db->group_by('leads.id');
        $this->db->order_by('leads.id', 'DESC');
        $results = $this->db->get();
        $results = $results->result_array();

        return $results;
    }

    public function cc_user_leads($from_date = null, $to_date = null)
    {
        // Fetch the group IDs for the user
        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->where("user_id", $this->auth_user_id);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        // Extract user_id values into a plain array
        $user_ids = array_column($result, 'user_id');

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on');
        // lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);

        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_source', 'cc-contact');
        $this->db->where('l.branch_id = 123');

        $this->db->where('l.lead_status NOT IN (305,306,624,606)');
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();

        return $results;
    }

    public function cc_created_leads($from_date = null, $to_date = null)
    {
        // $cats = $this->user_categories();

        /* For Goldencube Web Coordinator purpose */
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }
        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        // $this->db->where('group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        // Extract user_id values into a plain array
        $user_ids = array_column($result, 'user_id');

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.first_name as customer_name,
        u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,
        lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,
        concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on, l.lead_status');
        //lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id', 'left');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');
        // $this->db->join('lead_groups as lgg', 'lgg.assigned_group_id in (' . implode(",", $user_group_ids) . ')', 'inner');

        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        // $this->db->where('(l.lead_created_by in (' . implode(",", $group_mems) . '))');
        $this->db->where('l.lead_source', 'cc-contact');
        $this->db->where('l.branch_id = 123');

        if ($this->auth_user_role == 7) {
            // $this->db->where('(l.lead_created_by in (' . implode(",", $assigned_to_ids) . ') or la.assigned_to in (' . implode(",", $assigned_to_ids) . '))');
            // $this->db->where('l.lead_created_by in (' . implode(",", $assigned_to_ids) . ')');
            $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . ") or l.lead_zoho_created_by_id = " . $this->auth_user_id . ")");
        } else if ($this->auth_user_role == 2 || $this->auth_user_role == 6) {
            $this->db->where('(l.lead_created_by = ' . $this->auth_user_id . ' or la.assigned_to = ' . $this->auth_user_id . ' 
            or l.lead_zoho_created_by_id = ' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->where_in('l.lead_created_by', $assigned_to_ids);
        } else if ($this->auth_user_role == 84 && in_array(74, $user_group_ids)) {
            $group_mems = [$this->auth_user_id, 3946368694];  // goldencubeweb@goldencube.ae -- 3946368694 && 74 is GoldenCube Group
            $this->db->where_in('l.lead_created_by', $group_mems);
        } else {
            $this->db->where('l.lead_created_by', $this->auth_user_id);
        }
        // $this->db->where('lg.assigned_group_id in (' . implode(",", $user_group_ids) . ')');
        // $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . "))");
        $this->db->where('l.is_assigned', 1);
        // $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_parent_id = 0 ');

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }

        // if ($this->auth_user_role > 5) {
        //     $this->db->where('(l.lead_parent_id = 0)');
        // }

        // $this->db->where('la.assigned_by', $this->auth_user_id);
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // print_r(count($results));

        // exit();
        return $results;
    }

    public function cc_leads_reassigned($from_date = null, $to_date = null)
    {
        // $cats = $this->user_categories();
        $group_mems = [];

        // if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
        // Fetch group IDs for the authenticated user
        $groups = $this->db->select('distinct(group_id)')
            ->from("group_members")
            ->where("user_id", $this->auth_user_id)
            ->get()
            ->result_array();

        // Extract group IDs into an array
        $group_ids = array_column($groups, 'group_id');

        // Fetch distinct user IDs in the same groups
        $members = $this->db->select("distinct(group_members.user_id)")
            ->from("group_members")
            ->join("users", "users.user_id=group_members.user_id")
            ->where_in("group_members.group_id", $group_ids)
            ->get()
            ->result_array();

        // Extract user IDs into an array
        $group_mems = array_column($members, 'user_id');
        // }

        // Convert user IDs array to a comma-separated string
        $assigned_to_ids_str = implode(',', $group_mems);

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        // $assigned_to_ids_str = implode(',', $assigned_to_ids);

        // Main leads query
        $this->db->select('count(*) over() as count, l.*, u.country_code as customer_country_code, u.first_name as customer_name, 
        u.mobile as customer_mobile, u.email as customer_email, g.group_name as group_name, lc.category_name as category_name, ls.service_name as service_name, lstat.status_name as current_status, la.assigned_to as assigned_to, 
        concat(creator.first_name, " ", creator.last_name, " (", creator.employee_id, ")") as creator, 
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on');
        // ob.branch_name as branch_name
        // lg.creator_branch, lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id = l.id');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id');
        $this->db->join('users as creator', 'creator.user_id = l.lead_created_by');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code = l.branch_id');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id = l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id = l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id = l.id');
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id');

        // Add conditions for assigned_by and assigned_to
        if (!empty($assigned_to_ids_str)) {
            $this->db->where("la.assigned_by IN ($assigned_to_ids_str)", NULL, FALSE);
            $this->db->where("la.assigned_to NOT IN ($assigned_to_ids_str)", NULL, FALSE);
            //$this->db->where("lg.creator_group_id NOT IN ($assigned_to_ids_str)", NULL, FALSE);
        }
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            //$this->db->where_in('lg.creator_group_id', $group_ids);
            $this->db->where_not_in('l.created_group_id', $group_ids);
        }
        $this->db->where('l.lead_source', 'cc-contact');
        $this->db->where('l.branch_id = 123');

        $this->db->where("(l.created_group_id  in (" . implode(",", $group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $group_ids) . ") OR la.assigned_by =" . $this->auth_user_id . " OR 
        (lal.status_id = 303 and lal.action_by =" . $this->auth_user_id . "))");
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');

        if (!empty($from_date)) {
            $this->db->where('DATE(la.assigned_on) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(la.assigned_on) <=', $to_date);
        }

        if ($this->auth_user_role > 5) {
            $this->db->where('(l.lead_parent_id = 0)');
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');

        $results = $this->db->get()->result_array();
        return $results;
    }

    public function cc_leads_assigned_by_coordinator($from_date = null, $to_date = null)
    {
        $cats = $this->user_categories();
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
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
        }

        $this->db->select('count(*) over() as count, l.*, u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,
        l.created_group_id,gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on');
        // ob.branch_name as branch_name
        // lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');

        if ($this->auth_user_role == 2 || $this->auth_user_role == 6) {

            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            // echo "Hi" . $this->db->last_query();
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('((l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ') and l.lead_created_by not in (' . implode(",", $group_mems) . '))');
        } else {
            // print_r($this->auth_user_id);
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
            // echo "This is working";
            // $this->db->where('la.assigned_to=',$this->auth_user_id);
        }
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->where_in('l.assigned_group_id', $group_ids);
            //$this->db->or_where_in('lg.creator_group_id', $group_ids);
        }
        $this->db->where('l.lead_source', 'cc-contact');
        $this->db->where('l.branch_id = 123');

        $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . "))");
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('l.assigned_group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');

        if ($this->auth_user_role > 5) {
            $this->db->where('(l.lead_parent_id = 0)');
        }

        if (!empty($from_date)) {
            $this->db->where('DATE(la.assigned_on) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(la.assigned_on) <=', $to_date);
        }
        // $this->db->where('la.assigned_by', $this->auth_user_id);
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();

        return $results;
    }

    public function cc_disqualified_leads($from_date = null, $to_date = null, $user_type = "")
    {
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        // $this->db->where('group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        // Main leads query
        $this->db->select('count(*) over() as count, l.*, u.country_code as customer_country_code, u.first_name as customer_name, u.mobile as customer_mobile, u.email as customer_email, g.group_name as group_name, lc.category_name as category_name, ls.service_name as service_name, lstat.status_name as current_status,l.created_group_id,l.assigned_group_id, gc.group_name as creator_group, ga.group_name as assigned_group, concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator, la.assigned_on as assigned_on, la.assigned_to as assigned_to');
        //  lg.creator_branch, lg.assigned_branch
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id = l.id', 'left');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code = l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id = l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id = l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id = l.id', 'left');
        // $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 306);
        // $this->db->where('l.lead_status=306 OR l.lead_status=606');
        // $this->db->where('l.lead_status in (306,606,624)');
        $this->db->where('l.lead_source', 'cc-contact');
        $this->db->where('l.branch_id = 123');
        if ($user_type != "") {
            if ($user_type == "created") {
                $this->db->where('l.lead_created_by', $this->auth_user_id);
            }
            if ($user_type == "assigned") {
                $this->db->where('la.assigned_by', $this->auth_user_id);
            }
            if ($user_type == "completed") {
                $this->db->where('l.completed_by', $this->auth_user_id);
            }
        } else {
            if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
                $this->db->where("(la.assigned_to IN ($assigned_to_ids_str) OR l.lead_created_by IN ($assigned_to_ids_str))", NULL, FALSE);
                // $this->db->where("la.assigned_to IN ($assigned_to_ids_str)", NULL, FALSE);
            } else {
                $this->db->where('la.assigned_to', $this->auth_user_id);
            }
        }

        $group_ids_str = implode(',', $group_ids);
        $this->db->where("(l.assigned_group_id IN ($group_ids_str) OR l.created_group_id IN ($group_ids_str))", NULL, FALSE);
        $this->db->where('(l.lead_parent_id = 0 )');    // AND l.is_assigned = 1

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function cc_converted_leads($from_date = null, $to_date = null, $user_type = "")
    {
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        // $this->db->where('group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status, gc.group_name as creator_group, ga.group_name as assigned_group, 
        concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator, la.assigned_on as assigned_on,
        la.assigned_to as assigned_to');
        // ob.branch_name as branch_name
        // lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        // $this->db->join('lead_groups as lg', 'lg.id=l.id', 'left');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        // $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        // $this->db->join('groups as gs', 'gs.group_branch_id=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        if ($user_type != "") {
            if ($user_type == "created") {
                $this->db->where('l.lead_created_by', $this->auth_user_id);
            }
            if ($user_type == "assigned") {
                $this->db->where('la.assigned_by', $this->auth_user_id);
            }
            if ($user_type == "completed") {
                $this->db->where('l.completed_by', $this->auth_user_id);
            }
        } else {
            if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
                //$this->db->where_in('l.branch_id', $this->auth_user_id);
                $this->db->where("(la.assigned_to IN ($assigned_to_ids_str) OR l.lead_created_by IN ($assigned_to_ids_str))", NULL, FALSE);
            } else {
                $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id . ')');
            }
            // It will shown the disqualified leads also, so that it commented
            if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
                //     //$this->db->where_in('lg.creator_group_id', $groups);
                //     $this->db->where_in('lg.assigned_group_id', $group_ids);
                //     $this->db->or_where_in('lg.creator_group_id', $group_ids);
                $group_ids_str = implode(',', $group_ids);
                $this->db->where("(l.assigned_group_id IN ($group_ids_str) OR l.created_group_id IN ($group_ids_str))", NULL, FALSE);
            }
        }
        //$this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id . ')');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_source', 'cc-contact');
        $this->db->where('l.branch_id = 123');
        // $this->db->where_in('l.branch_id', $group_ids_branch); // Corrected IN condition
        // $group_ids_str = implode(',', $group_ids);
        // $this->db->where("(lg.assigned_group_id IN ($group_ids_str) OR lg.creator_group_id IN ($group_ids_str))", NULL, FALSE);
        // $this->db->where_in('lg.assigned_group_id', $group_ids);
        $this->db->where('l.lead_status', 305);
        // $this->db->or_where('l.lead_created_by',$this->auth_user_id);
        // $this->db->or_where('l.lead_status',306);

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();

        return $results;
    }
}