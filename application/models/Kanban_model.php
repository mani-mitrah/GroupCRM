<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kanban_model extends CI_Model
{
    public function __construct()
    {
        $parents_array = array();
    }

    public function user_leads($from_date = null, $to_date = null)
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

        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,la.assigned_to as assigned_to,la.assigned_by as assigned_by');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }

        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (301,302,303,607,608,609,601,602)');
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        $this->db->where('TIMESTAMPDIFF(HOUR, l.lead_added_on, NOW()) <', 24);

        // Grouping and filtering
        $this->db->group_by('l.id');
        $this->db->having('action_count <=', 2);
        // var_dump($type,$type !== null && $type != 'all');exit;
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        $counts = $this->get_count($results);
        return [
            'data' => $results,
            'counts' => $counts
        ];
    }
    public function first_attempt_leads($from_date = null, $to_date = null)
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

        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,la.assigned_to as assigned_to,la.assigned_by as assigned_by');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (301,302,303,607,608,609,601,602)');
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        $this->db->where('TIMESTAMPDIFF(HOUR, l.lead_added_on, NOW()) >', 24);
        // Grouping and filtering
        $this->db->group_by('l.id');
        $this->db->having('action_count <=', 2);
        // var_dump($type,$type !== null && $type != 'all');exit;
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        $counts = $this->get_count($results);

        return [
            'data' => $results,
            'counts' => $counts
        ];
    }

    public function second_attempt_leads($from_date = null, $to_date = null)
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

        //print_r($result);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name, (
            SELECT COUNT(*) 
            FROM lead_action_log 
            WHERE lead_action_log.lead_id = l.id 
            AND lead_action_log.status_id = 304
        ) AS status_304_count,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,la.assigned_to as assigned_to,la.assigned_by as assigned_by

        ');

        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        // Joining lead_action_log to count actions
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (304,320,321)');
        $this->db->group_by('l.id');
        $this->db->having('status_304_count', 1); // Only include leads with more than 2 actions
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        $counts = $this->get_count($results);
        return [
            'data' => $results,
            'counts' => $counts
        ];
        // print_r($this->db->last_query());
        // exit();
    }

    public function third_attempt_leads($from_date = null, $to_date = null)
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

        //print_r($result);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name, (
            SELECT COUNT(*) 
            FROM lead_action_log 
            WHERE lead_action_log.lead_id = l.id 
            AND lead_action_log.status_id = 304
        ) AS status_304_count,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,la.assigned_to as assigned_to,la.assigned_by as assigned_by
        ');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        // Joining lead_action_log to count actions
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (304,320,321)');
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        $this->db->group_by('l.id');
        $this->db->having('status_304_count', 2); // Only include leads with more than 2 actions
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        $counts = $this->get_count($results);
        return [
            'data' => $results,
            'counts' => $counts
        ];
        // print_r($this->db->last_query());
    }

    public function disqualified($from_date = null, $to_date = null)
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

        //print_r($result);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,la.assigned_to as assigned_to,la.assigned_by as assigned_by');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        // Joining lead_action_log to count actions
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (306,606,624)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        $counts = $this->get_count($results);
        return [
            'data' => $results,
            'counts' => $counts
        ];
        // print_r($this->db->last_query());
        // exit();
    }

    public function quotation_prospecting($from_date = null, $to_date = null)
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

        //print_r($result);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,sum(case when lal.action_id IN (415,417,418) then lal.action_amount else 0 end) as amount,la.assigned_to as assigned_to,la.assigned_by as assigned_by');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');


        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        // Joining lead_action_log to count actions
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (642,643,644)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        $counts = $this->get_count($results);
        $totalAmount = $this->get_total_amount($results);
        if(!empty($results)){
            $results[0]['total_amount'] = $totalAmount;
        }
        return [
            'data' => $results,
            'counts' => $counts
        ];
        // print_r($this->db->last_query());
        // exit();
    }


    public function paid_quoted($from_date = null, $to_date = null)
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
        //print_r($result);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,sum(case when lal.action_id IN (415,417,418) then lal.action_amount else 0 end) as amount,la.assigned_to as assigned_to,la.assigned_by as assigned_by');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        // Joining lead_action_log to count actions
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (310,311,312)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        $counts = $this->get_count($results);
        $totalAmount = $this->get_total_amount($results);
        if(!empty($results)){
            $results[0]['total_amount'] = $totalAmount;
        }
        return [
            'data' => $results,
            'counts' => $counts
        ];
        // print_r($this->db->last_query());
        // exit();
    }

    public function completed_won($from_date = null, $to_date = null)
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
        //print_r($result);
        $this->db->select('count(*) over() as count, l.*,u.country_code as customer_country_code,u.country as country,u.nationality as nationality,u.first_name as customer_name,u.mobile as customer_mobile,u.email as customer_email,g.group_name as group_name,lc.category_name as category_name,concat(creator.first_name," ",creator.last_name," (",creator.employee_id,")") as creator,ls.service_name as service_name,lstat.status_name as current_status,
        gc.group_name as creator_group, ga.group_name as assigned_group, la.assigned_on as assigned_on,COUNT(lal.id) as action_count,ob.branch_name as branch_name,
        CASE
        WHEN (( l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" ) AND l.branch_id = 106 )THEN "Lead"
        WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
        WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
        ELSE "Lead"
        END AS Req_type,sum(case when lal.action_id IN (415,417,418) then lal.action_amount else 0 end) as amount,la.assigned_to as assigned_to,la.assigned_by as assigned_by');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');
        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        // Joining lead_action_log to count actions
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (305)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        // print_r($this->db->last_query());
        // exit();
        $counts = $this->get_count($results);
        $totalAmount = $this->get_total_amount($results);
        if(!empty($results)){
            $results[0]['total_amount'] = $totalAmount;
        }
        return [
            'data' => $results,
            'counts' => $counts
        ];;
    }

    private function get_count($results)
    {
        $counts = [
            'total' => 0,
            'Transaction' => 0,
            'Cross-Sale' => 0,
            'Lead' => 0,
        ];
        foreach ($results as $row) {
            $counts['total']++;
            if ($row['Req_type'] === 'Transaction') {
                $counts['Transaction']++;
            } elseif ($row['Req_type'] === 'Cross-Sale') {
                $counts['Cross-Sale']++;
            } elseif ($row['Req_type'] === 'Lead') {
                $counts['Lead']++;
            }
        }
        return $counts;
    }

    public function list_groups()
    {
        $this->db->select("groups.*,IF(group_members.user_id=" . $this->auth_user_id . ",1,0) as is_selected");
        $this->db->from("groups");
        $this->db->where("groups.status=", 1);
        $this->db->join("group_members", "group_members.group_id=groups.group_id and group_members.user_id = " . $this->auth_user_id);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function group_members($group_ids = null)
    {
        // Get default groups if none provided
        if (empty($group_ids)) {
            $groups = get_groups($this->auth_user_id);
            $group_ids = array_column($groups, 'group_id');

            // Fallback if no groups found
            if (empty($group_ids)) {
                return [];
            }
        }

        $this->db->select("
                DISTINCT(group_members.user_id), 
                CONCAT(users.first_name, ' ', users.last_name) AS full_name,
                users.email,
                users.profile_pic
            ")
            ->from("group_members")
            ->join("users", "users.user_id = group_members.user_id")
            ->where("users.is_active", 1)
            ->where_in("group_members.group_id", $group_ids);

        return $this->db->get()->result_array();
    }

    public function get_lead_id($lead_id)
    {
        $this->db->select("id")
            ->from("leads")
            ->group_start()
            ->where("leads.id", $lead_id)
            ->or_where("leads.lead_parent_id", $lead_id)
            ->group_end();

        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_lead_groups($from_date = null, $to_date = null)
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
        $this->db
            ->distinct() // Ensure unique rows
            ->select([
                'l.id AS lead_id',  // Include lead ID for grouping
                'ag.group_id AS assigned_group_id',
                'ag.group_name AS assigned_group',
                'cg.group_id AS completed_group_id',
                'cg.group_name AS completed_group',
                'crg.group_id AS created_group_id',
                'crg.group_name AS created_group'
            ])
            ->from('leads AS l')
            ->join('leads_assigned AS la', 'la.lead_id = l.id', 'left')

            // Assigned to group
            ->join('users AS assigned_user', 'assigned_user.user_id = la.assigned_to', 'left')
            ->join(
                'group_members AS agm',
                'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1',
                'left'
            )
            ->join('groups AS ag', 'ag.group_id = agm.group_id', 'left')

            // Completed by group
            ->join(
                'group_members AS cgm',
                'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1',
                'left'
            )
            ->join('groups AS cg', 'cg.group_id = cgm.group_id', 'left')

            // Created by group
            ->join(
                'group_members AS crgm',
                'crgm.user_id = l.lead_created_by AND crgm.is_primary_group_id = 1',
                'left'
            )
            ->join('groups AS crg', 'crg.group_id = crgm.group_id', 'left')
            ->group_by('l.id');  // Group by lead ID

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }

        return $this->db->get()->result_array();
    }

    //GC

    public function get_gc_leads($from_date = null, $to_date = null)
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
        $this->db->select('count(*) over() as count,
                l.id, l.lead_status, l.country_options, 
                l.lead_created_by, l.contactable_date as due_date,
                l.lead_added_on, l.total_no_subleads,
                l.no_of_open_subleads, l.no_of_closed_subleads,
                l.created_at, l.updated_at,
                u.country_code as customer_country_code,
                u.country as country,
                u.nationality as nationality,
                u.first_name as customer_name,
                u.mobile as customer_mobile,
                u.email as customer_email,
                g.group_name as group_name,
                lc.category_name as category_name,
                CONCAT(creator.first_name, " ", creator.last_name, " (", creator.employee_id, ")") as creator,
                ls.service_name as service_name,
                lstat.status_name as current_status,
                gc.group_name as creator_group,
                ga.group_name as assigned_group,
                la.assigned_on as assigned_on,
                COUNT(lal.id) as action_count,
                ob.branch_name as branch_name,
                CASE
                    WHEN l.lead_zoho_id IS NOT NULL OR l.lead_from = "GoldenCube" THEN "Lead"
                    WHEN g.group_name IS NOT NULL AND (g.group_name = COALESCE(cg.group_name, ag.group_name)) THEN "Transaction"
                    WHEN g.group_name IS NOT NULL THEN "Cross-Sale"
                    ELSE "Lead"
                END AS Req_type,

                (select sum(action_amount) from lead_action_log where lead_id = l.id and action_id IN (415,417,418)) as amount,
                la.assigned_to as assigned_to,
                la.assigned_by as assigned_by,
                GROUP_CONCAT(DISTINCT sub.id) AS sub_lead_ids,
                GROUP_CONCAT( DISTINCT 
                    CASE 
                        WHEN sub.lead_status = 305 AND sub.msd_key IN (69,13) THEN \'admin_leads\'
                        WHEN sub.lead_status = 305 AND sub.msd_key IN (66) AND sub.remarks LIKE CONCAT(\'%\', \'visa\', \'%\') THEN \'visa_leads\'
                        WHEN sub.lead_status = 305 AND sub.msd_key IN (64) AND sub.remarks LIKE CONCAT(\'%\', \'dld\', \'%\') THEN \'dld_leads\'
                        WHEN sub.lead_status = 305 AND sub.msd_key IN (81,67,12) THEN \'eid_leads\'
                        WHEN sub.lead_status = 305 AND sub.msd_key IN (65,55,11) THEN \'moh_leads\'
                        WHEN sub.lead_status = 305 AND sub.msd_key = 0 THEN \'custom_leads\'
                        WHEN sub.lead_status = 305 AND sub.msd_key IN (66,64) THEN \'other_leads\'
                        WHEN sub.lead_status = 305 AND sub.msd_key NOT IN (66,65,55,11,81,67,12,69,13,64,0) THEN \'other_leads\'
                        ELSE \'paid_leads\'
                    END ) AS lead_category
        ');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('ontime_branches as ob', 'ob.branch_code=l.branch_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        // Sub-lead join
        $this->db->join('leads as sub', "sub.lead_parent_id = l.id", 'inner');

        // Modified joins with primary group check
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id AND gm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as g', 'g.group_id = gm.group_id', 'left');

        // New joins for completed_by group
        $this->db->join('group_members as cgm', 'cgm.user_id = l.completed_by AND cgm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as cg', 'cg.group_id = cgm.group_id', 'left');

        // Existing joins
        $this->db->join('groups as gc', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join("leads_assigned la", "la.lead_id = l.id");
        // New joins for assigned user's group
        $this->db->join('users as assigned_user', 'assigned_user.user_id = la.assigned_to', 'left');
        $this->db->join('group_members as agm', 'agm.user_id = assigned_user.user_id AND agm.is_primary_group_id = 1', 'left');
        $this->db->join('groups as ag', 'ag.group_id = agm.group_id', 'left');

        if (in_array($this->auth_user_role, [7])) {
            $this->db->where('(la.assigned_to in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->where('(la.assigned_to = ' . $this->auth_user_id . ')');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        // Joining lead_action_log to count actions
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id', 'left');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status IN (305,310,311,312)');
        $this->db->where('l.branch_id IN (106)');
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        $results = $this->db->get()->result_array();
        //  print_r($this->db->last_query());
        // exit();
        $counts = $this->get_count($results);
        return [
            'data' => $results,
            'counts' => $counts
        ];
    }

    public function get_leads_status($lead_ids)
    {
        $ids_string = implode(',', $lead_ids);
        $this->db->select("
            l.id,
            l.lead_status,
            l.remarks,
            l.msd_key,
            lps.sla,
            l.lead_added_on,
            CASE 
                WHEN l.id IN ($ids_string) AND l.msd_key IN (69,13) THEN 'admin_leads'
                WHEN l.id IN ($ids_string) AND l.msd_key IN (66) AND l.remarks LIKE '%visa%' THEN 'visa_leads'
                WHEN l.id IN ($ids_string) AND l.msd_key IN (64) AND l.remarks LIKE '%dld%' THEN 'dld_leads'
                WHEN l.id IN ($ids_string) AND l.msd_key IN (81,67,12) THEN 'eid_leads'
                WHEN l.id IN ($ids_string) AND l.msd_key IN (65,55,11) THEN 'moh_leads'
                WHEN l.id IN ($ids_string) AND l.msd_key = 0 THEN 'custom_leads'
                ELSE 'other_leads'
            END AS lead_category
        ");
        $this->db->from('leads as l');
        $this->db->join('lead_package_details as lpd', 'lpd.lead_id = l.lead_parent_id and lpd.msd_key = l.msd_key', 'left');
        $this->db->join('lead_package_services as lps', 'lps.package_id = lpd.package_id and lps.msd_key = l.msd_key', 'left');
        $this->db->where_in('l.id', $lead_ids);
        $query = $this->db->get();
        return $query->result_array();
    }
    private function get_total_amount($results)
    {
        $totalAmount = 0;
        if(!empty($results)){
            foreach($results as $result)
            {
                $totalAmount += $result['amount'];
            }
        }
        return $totalAmount;

    }
}
