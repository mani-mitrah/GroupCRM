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
        $this->db->from('ontime_category_services as ls');
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
        $this->db->join('ontime_category_services as ls', 'ls.service_id=lwe.target_service_id');
        $this->db->where('lw.parent_service_id', $service_id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function get_package_entries($package_id)
    {
        $this->db->select("lp.*,lps.*,ls.category_id");
        $this->db->from('lead_packages as lp');
        $this->db->join('lead_package_services as lps', 'lps.package_id=lp.package_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=lps.service_id');
        $this->db->where('lp.package_id', $package_id);
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function unassigned_leads()
    {
        $this->db->select('l.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->where('l.is_assigned', 0);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
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

    public function unassigned_leads_for_user($user_categories)
    {

        $cats = $this->user_categories();
        // print_r($cats);
        // exit();
        // $cats = $user_categories;

        $this->db->select('leads.*,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,lc.category_id as category_id')->from("leads");
        $this->db->join('lead_users as u', 'u.user_id=leads.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=leads.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=leads.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=leads.service_id');
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
    }

    public function user_leads()
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        // $this->db->where('la.assigned_to',$this->auth_user_id);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status !=', 306);
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();
        return $results;
    }

    public function leads_assigned_by_coordinator()
    {
        $cats = $this->user_categories();
        if ($this->auth_user_role == 7) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
            $group_ids = [];
            foreach ($groups as $group) {
                array_push($group_ids, $group["group_id"]);
            }
            $this->db->select("distinct(group_members.user_id)")->from("group_members");
            $this->db->join("users", "users.user_id=group_members.user_id");
            $this->db->where("users.is_active", 1);
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

        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');

        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {

            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR l.lead_created_by = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7) {
            // echo "Hi" . $this->db->last_query();
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by in (' . implode(",", $group_mems) . ') or l.lead_created_by = ' . $this->auth_user_id . ' 
                or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else {
            // print_r($this->auth_user_id);
            // exit();
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
            // echo "This is working";
            // $this->db->where('la.assigned_to=',$this->auth_user_id);
        }

        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309) and `l`.`branch_id`!=100)');

        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        
        if ($this->auth_user_role > 5) {
            $this->db->where('(l.lead_parent_id = 0)');
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


    public function pos_leads_coordinator()
    {
        $cats = $this->user_categories();
        if ($this->auth_user_role == 7) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
            $group_ids = [];
            foreach ($groups as $group) {
                array_push($group_ids, $group["group_id"]);
            }
            $this->db->select("distinct(group_members.user_id)")->from("group_members");
            $this->db->join("users", "users.user_id=group_members.user_id");
            $this->db->where("users.is_active", 1);
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

        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,concat(assigned.first_name," ",assigned.last_name," (",assigned.employee_id,")") as assigned_user');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');

        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {

            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR l.lead_created_by = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7) {
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
        $this->db->join('users as assigned', 'assigned.user_id=la.assigned_to');

        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309) and `l`.`branch_id`=100)');
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

    public function converted_leads()
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id . ')');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 305);
        // $this->db->or_where('l.lead_created_by',$this->auth_user_id);
        // $this->db->or_where('l.lead_status',306);
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function converted_leads_cordinator()
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
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        // echo "<pre>";
        // print_r($group_mems);
        // echo "</pre>";
        // exit();
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        // $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 305);
        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ')  or l.lead_created_by = ' . $this->auth_user_id . ' or la.assigned_to = '.$this->auth_user_id.')');
         } else if ($this->auth_user_role == 7) {
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by in (' . implode(",", $group_mems) . ') or l.lead_created_by = ' . $this->auth_user_id . ' 
                or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');

        } else {
            $this->db->where("(la.assigned_to in (" . implode(",", $group_mems) . ") or l.lead_created_by in (" . implode(",", $group_mems) . "))");
        }
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

    public function disqualified_leads()
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 306);
        $this->db->where('la.assigned_to', $this->auth_user_id);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function coordinator_disqualified_leads()
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
        $this->db->where("users.is_active", 1);
        $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
        $group_mems = [];
        foreach ($members as $mem) {
            array_push($group_mems, $mem["user_id"]);
        }
        $cats = $this->user_categories();
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 306);
        // $this->db->where('la.assigned_to',$this->auth_user_id);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {
            $this->db->where_in('l.category_id', $cats);
        } else if ($this->auth_user_role == 7) {
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.lead_created_by in (' . implode(",", $group_mems) . ') or l.lead_created_by = ' . $this->auth_user_id . ' 
                or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        
        } else {
            // $this->db->where('la.assigned_to', $this->auth_user_id);
            $this->db->where("(la.assigned_to in (" . implode(",", $group_mems) . ") or l.lead_created_by in (" . implode(",", $group_mems) . "))");
        }
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
        $this->db->where("users.is_active", 1);
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

    public function lead_details($lead_id)
    {
        // echo "There";
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,u.customer_type,u.customer_address,u.alt_mobile,u.alt_email,u.trn_no,u.trade_no,ob.branch_name as branch_name,lc.category_name as category_name,lc.category_code,ls.service_name as service_name,ls.bot_id as bot_id,lstat.status_name as current_status,l.pos_salesorder,l.pos_invresponse,ob.branch_sender_id as sender_id');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id',"left");
        $this->db->where('l.id', $lead_id);
        $results = $this->db->get()->row_array();
        // print_r($results);
        // exit();
        return $results;
    }

    public function lead_preview($lead_id)
    {
        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.id as category_id,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
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

        $this->db->select("leads.id as lead_id,leads.lead_type,concat(lead_users.first_name,' ',lead_users.last_name,IF(lead_users.email IS NOT NULL,', <br>',''),lead_users.email,IF(lead_users.mobile != 0,', <br>',''),lead_users.mobile) as customer,concat(users.first_name,' ',users.last_name,' (',users.employee_id,')') as requested_by,lead_action_log.action_amount as amount,lead_status.status_name as status,concat(ontime_branches.branch_name,', <br>',ontime_categories.category_name,', <br>',ontime_category_services.service_name) as source,lead_action_log.action_on as action_on")->from("lead_action_log");
        $this->db->join("leads", "lead_action_log.lead_id = leads.id");
        $this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
        $this->db->join("users", "users.user_id = lead_action_log.action_by");
        $this->db->join("lead_status", "lead_status.id = lead_action_log.status_id");

        $this->db->join('ontime_branches', 'ontime_branches.branch_code=leads.branch_id');
        $this->db->join('ontime_categories', 'ontime_categories.category_id=leads.category_id');
        $this->db->join('ontime_category_services', 'ontime_category_services.service_id=leads.service_id');

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
        $this->db->select("leads.id as lead_id,leads.lead_type,concat(lead_users.first_name,' ',lead_users.last_name,IF(lead_users.email IS NOT NULL,', <br>',''),lead_users.email,IF(lead_users.mobile != 0,', <br>',''),lead_users.mobile) as customer,concat(users.first_name,' ',users.last_name,' (',IF(users.employee_id IS NULL,'0000',users.employee_id),')') as completed_by,concat(creator.first_name,' ',creator.last_name,' (',IF(creator.employee_id IS NULL,'0000',creator.employee_id),')') as created_by,lead_action_log.action_amount as amount,lead_status.status_name as status,concat(ontime_branches.branch_name,', <br>',ontime_categories.category_name,', <br>',ontime_category_services.service_name) as source,lead_action_log.action_on as action_on,leads.lead_added_on as lead_created_at,CONCAT( FLOOR(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)) / 168), ' WEEKS ',MOD(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)), 24), ' HOURS ', MINUTE(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)), ' MINUTES ') AS timeline,timediff(lead_action_log.action_on,leads.lead_added_on) as mindiff,leads.order_receipt as invoice")->from("lead_action_log");
        $this->db->join("leads", "lead_action_log.lead_id = leads.id");
        $this->db->join("lead_users", "lead_users.user_id = leads.customer_id");
        $this->db->join("users", "users.user_id = lead_action_log.action_by");
        $this->db->join("users as creator", "creator.user_id = leads.lead_created_by");
        $this->db->join("lead_status", "lead_status.id = lead_action_log.status_id");

        $this->db->join('ontime_branches', 'ontime_branches.branch_code=leads.branch_id');
        $this->db->join('ontime_categories', 'ontime_categories.category_id=leads.category_id');
        $this->db->join('ontime_category_services', 'ontime_category_services.service_id=leads.service_id');

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
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
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
				$this->db->select("leads.*,`leads`.`id` AS `lead_id`,`leads`.`lead_status` AS `lead_status`,`leads`.`updated_at` AS `updated_at`,`leads`.`lead_type`,`leads`.`lead_parent_id` as lead_sublead,concat(lead_users.first_name,' ',`lead_users`.`last_name`) AS customer_name,lead_users.email AS customer_email,lead_users.mobile AS customer_mobile,concat(creator.first_name,' ',`creator`.`last_name`,' (',IF(creator.employee_id IS NULL,'0000',creator.employee_id),')') AS created_by,creator_g.group_name AS creator_group_name,creator_b.branch_name AS creator_branch_name,concat(users.first_name,' ',`users`.`last_name`,' (',IF(users.employee_id IS NULL,'0000',users.employee_id),')') AS completed_by,completed_g.group_name AS completed_on_group,completed_b.branch_name AS completed_on_branch,`lead_status`.`status_name` AS `status`,concat(ontime_branches.branch_name,', ',`ontime_categories`.`category_name`,', ',ontime_category_services.service_name) as source,`lead_action_log`.`action_on` as `action_on`,`leads`.`lead_added_on` as `lead_created_at`,CONCAT( FLOOR(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)) / 168),' WEEKS ',MOD(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),24),' HOURS ',MINUTE(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),' MINUTES ') AS timeline,`leads`.`order_receipt` AS `invoice`")->from("lead_action_log");
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
				$this->db->join("ontime_category_services", "ontime_category_services.service_id = leads.service_id");
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
					$this->db->select("leads.*,`leads`.`id` AS `lead_id`,`leads`.`lead_status` AS `lead_status`,`leads`.`updated_at` AS `updated_at`,`leads`.`lead_type`,`leads`.`lead_parent_id` as lead_sublead,concat(lead_users.first_name,' ',`lead_users`.`last_name`) AS customer_name,lead_users.email AS customer_email,lead_users.mobile AS customer_mobile,concat(creator.first_name,' ',`creator`.`last_name`,' (',IF(creator.employee_id IS NULL,'0000',creator.employee_id),')') AS created_by,creator_g.group_name AS creator_group_name,creator_b.branch_name AS creator_branch_name,concat(users.first_name,' ',`users`.`last_name`,' (',IF(users.employee_id IS NULL,'0000',users.employee_id),')') AS completed_by,completed_g.group_name AS completed_on_group,completed_b.branch_name AS completed_on_branch,`lead_status`.`status_name` AS `status`,concat(ontime_branches.branch_name,', ',`ontime_categories`.`category_name`,', ',ontime_category_services.service_name) as source,`lead_action_log`.`action_on` as `action_on`,`leads`.`lead_added_on` as `lead_created_at`,CONCAT( FLOOR(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)) / 168),' WEEKS ',MOD(HOUR(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),24),' HOURS ',MINUTE(TIMEDIFF(lead_action_log.action_on,leads.lead_added_on)),' MINUTES ') AS timeline,`leads`.`order_receipt` AS `invoice`")->from("lead_action_log");
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
					$this->db->join("ontime_category_services", "ontime_category_services.service_id = leads.service_id");
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
        if ($this->auth_user_role == 7) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
            $group_ids = [];
            foreach ($groups as $group) {
                array_push($group_ids, $group["group_id"]);
            }
            $this->db->select("distinct(group_members.user_id)")->from("group_members");
            $this->db->join("users", "users.user_id=group_members.user_id");
            $this->db->where("users.is_active", 1);
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

        $this->db->select('l.*,u.country_code as customer_country_code,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,ob.branch_name as branch_name,lc.category_name as category_name,ls.service_name as service_name,lstat.status_name as current_status,la.assigned_to as assigned_to,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,creator_g.group_name AS creator_group_name,creator_b.branch_name AS creator_branch_name,`lstat`.`status_name` AS `status`');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join("group_members AS creator_gm", "creator_gm.user_id = creator.user_id",'left');
        $this->db->join("groups AS creator_g", "creator_gm.group_id = creator_g.group_id",'left');
        $this->db->join("ontime_branches AS creator_b", "creator_b.branch_code = creator_g.group_branch_id",'left');
        
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        // $this->db->join('leads_assigned as la','la.lead_id=l.id');

        if ($this->auth_user_role == 1 || $this->auth_user_role == 6) {

            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR l.lead_created_by = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7) {
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
        $this->db->where('(`l`.`lead_status` not in (305,306,309))');
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
}
