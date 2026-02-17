<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class App_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function user_accessable_websites()
    {
        // $query = 'SELECT DISTINCT `ontime_websites`.`id`,ontime_websites.website_name from group_categories 
        // inner join groups on groups.group_id=group_categories.group_id and groups.status=1
        // inner join group_members on groups.group_id=group_members.group_id and group_members.user_id = 678064846 and group_members.status=1
        // inner join ontime_website_category_map on ontime_website_category_map.category_id = group_categories.category_id and ontime_website_category_map.is_active = 1
        // inner join ontime_websites on ontime_websites.id = ontime_website_category_map.website_id and ontime_websites.is_active = 1';

        if ($this->auth_user_role == 6) {

            $this->db->select("DISTINCT(ontime_websites.id)")->from("group_categories");
            $this->db->join("groups", "groups.group_id=group_categories.group_id and groups.status=1");
            $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
            $this->db->join("ontime_website_category_map", "ontime_website_category_map.category_id = group_categories.category_id and ontime_website_category_map.is_active = 1");
            $this->db->join("ontime_websites", "ontime_websites.id = ontime_website_category_map.website_id and ontime_websites.is_active = 1");
            $query = $this->db->get();
            $categories = $query->result_array();
            $cats = [];
            foreach ($categories as $category) {
                array_push($cats, $category["id"]);
            }
        } else if ($this->auth_user_role == 7) {
            $cats = [];
            $group_ids = $this->db->select("group_id")->from("group_members")->where("user_id", $this->auth_user_id)->group_by("group_members.group_id")->get()->result_array();

            $g_ids = [];
            foreach ($group_ids as $g_id) {
                array_push($g_ids, $g_id["group_id"]);
            }

            $result = $this->db->select("DISTINCT(group_websites.website_id) as id")->from("group_websites")->where_in("group_websites.group_id", $g_ids)->get()->result_array();
            foreach ($result as $res) {
                array_push($cats, $res["id"]);
            }
        }
        // print_r($cats);
        // exit();
        return $cats;
    }

    public function unassigned_enquiries($website_id = "")
    {
        $cats = $this->user_accessable_websites();
        $this->db->select('e.*, es.status_name as status_name,ontime_websites.website_name,ontime_websites.short_name');
        $this->db->from('enquiry as e');
        $this->db->join('company_enquiries as ce', 'ce.enquiry_id=e.id', 'left');
        $this->db->join('enquiry_status es', 'e.enquiry_status=es.id', 'left');
        // $this->db->join()
        $this->db->join("ontime_websites", "ontime_websites.id=e.website_id");
        $this->db->where_in("e.website_id", $cats);
        $this->db->where(array('ce.is_assigned' => 0));
        if ($website_id != "") $this->db->where(array('e.website_id' => $website_id));
        $query = $this->db->get();
        $results = $query->result_array();
        return $results;
    }


    public function unassigned_complaints($website_id)
    {
        $this->db->select('e.*, es.status_name as status_name');
        $this->db->from('enquiry as e');
        $this->db->join('company_complaints as cc', 'cc.enquiry_id=e.id', 'left');
        $this->db->join('enquiry_status es', 'e.enquiry_status=es.id', 'left');
        $this->db->where(array('cc.is_assigned' => 0, 'e.website_id' => $website_id, 'e.enquiry' => 2));
        $results = $this->db->get()->result_array();
        return $results;
    }


    public function unassigned_meetings($website_id)
    {
        $this->db->select('e.*, es.status_name as status_name');
        $this->db->from('enquiry as e');
        $this->db->join('company_meetings as cm', 'cm.enquiry_id=e.id', 'left');
        $this->db->join('enquiry_status es', 'e.enquiry_status=es.id', 'left');
        $this->db->where(array('cm.is_assigned' => 0, 'e.website_id' => $website_id, 'e.enquiry' => 3));
        $results = $this->db->get()->result_array();
        return $results;
    }


    public function csa_enquiries($website_id = "")
    {
        $cats = $this->user_accessable_websites();
        // print_r($cats);
        // exit();

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
        if ($cats == NULL) $cats = [];

        $this->db->select('e.*, es.status_name as status_name,es.id as status_id,ontime_websites.website_name,ontime_websites.short_name,concat(users.first_name," ",users.last_name," (",IF(users.employee_id IS NOT NULL,users.employee_id,"0000"),")<br>on <small>",enquiry_action_log.action_on,"<small>") as last_followup');
        $this->db->from('enquiry as e');
        $this->db->join('company_enquiries as ce', 'ce.enquiry_id=e.id', 'left');
        $this->db->join('assigned_enquiries as ae', 'ae.enquiry_id=e.id', 'left');
        $this->db->join('enquiry_status es', 'e.enquiry_status=es.id', 'left');
        $this->db->join("ontime_websites", "ontime_websites.id=e.website_id");
        $this->db->join("enquiry_action_log", "enquiry_action_log.enquiry_id = e.id and  `enquiry_action_log`.`action_id` = (select action_id from `enquiry_action_log` where `enquiry_action_log`.`enquiry_id` = `e`.`id` order by `enquiry_action_log`.`id` desc limit 1)", "left");
        $this->db->join("users", "enquiry_action_log.action_by=users.user_id", "left");
        $this->db->where("(e.website_id in (" . implode(',', $cats) . ") and (ce.is_assigned = 0 OR ce.is_assigned IS NULL)) or (ae.assigned_user in (" . implode(',', $group_mems) . "))");
        // $this->db->where("(ce.is_assigned = 0 OR ce.is_assigned IS NULL)");
        // $this->db->where("(ce.is_assigned = 0 OR ce.is_assigned IS NULL OR as.assigned_user in (" . implode(',', $group_mems) . "))");
        // $this->db->where(array('ae.assigned_user' => $this->auth_user_id));
        $this->db->group_by("e.id");
        $query = $this->db->get();
        // print_r($this->db->last_query());
        // exit();
        $results = [];
        if ($query) $results = $query->result_array();
        return $results;
    }

    public function csa_complaints($website_id)
    {
        $this->db->select('e.*');
        $this->db->from('enquiry as e');
        $this->db->join('company_complaints as ce', 'ce.enquiry_id=e.id', 'left');
        $this->db->join('assigned_complaints as ae', 'ae.enquiry_id=e.id', 'left');
        $this->db->where(array('ae.assigned_user' => $this->auth_user_id, 'e.website_id' => $website_id, 'e.enquiry' => 2));
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function csa_meetings($website_id)
    {
        $this->db->select('e.*');
        $this->db->from('enquiry as e');
        $this->db->join('company_meetings as ce', 'ce.enquiry_id=e.id', 'left');
        $this->db->join('assigned_meetings as ae', 'ae.enquiry_id=e.id', 'left');
        $this->db->where(array('ae.assigned_user' => $this->auth_user_id, 'e.website_id' => $website_id, 'e.enquiry' => 3));
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function enquiries($website_id = "")
    {
        $cats = $this->user_accessable_websites();
        $this->db->select('e.*, es.status_name as status_name,es.id as status_id,ontime_websites.website_name,ontime_websites.short_name,concat(users.first_name," ",users.last_name," (",IF(users.employee_id IS NOT NULL,users.employee_id,"0000"),")<br>on <small>",enquiry_action_log.action_on,"<small>") as last_followup');
        $this->db->from('enquiry as e');
        $this->db->join("ontime_websites", "ontime_websites.id=e.website_id");
        $this->db->join('enquiry_status es', 'e.enquiry_status=es.id', 'left');
        $this->db->join('assigned_enquiries as ae', 'ae.enquiry_id=e.id', 'left');
        $this->db->join("enquiry_action_log", "enquiry_action_log.enquiry_id = e.id and  `enquiry_action_log`.`action_id` = (select action_id from `enquiry_action_log` where `enquiry_action_log`.`enquiry_id` = `e`.`id` order by `enquiry_action_log`.`id` desc limit 1)");
        $this->db->join("users", "enquiry_action_log.action_by=users.user_id");
        if ($website_id != "") $this->db->where(array('e.website_id' => $website_id));
        $this->db->where('ae.assigned_user', $this->auth_user_id);
        $this->db->where_in("e.website_id", $cats);
        $this->db->group_by("e.id");
        $query = $this->db->get();

        $results = $query->result_array();
        // print_r($this->db->last_query());
        // exit();
        return $results;
    }

    public function complaints($website_id)
    {
        $this->db->select('e.*, es.status_name as status_name');
        $this->db->from('enquiry as e');
        $this->db->join('enquiry_status es', 'e.enquiry_status=es.id', 'left');
        $this->db->where(array('e.website_id' => $website_id, 'e.enquiry' => 2));
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function meetings($website_id)
    {
        $this->db->select('e.*, es.status_name as status_name');
        $this->db->from('enquiry as e');
        $this->db->join('enquiry_status es', 'e.enquiry_status=es.id', 'left');
        $this->db->where(array('e.website_id' => $website_id, 'e.enquiry' => 3));
        $results = $this->db->get()->result_array();
        return $results;
    }
    public function get_branches($branch_id)
    {
        $this->db->select('br.*');
        $this->db->from('ontime_branches as br');
        $this->db->where(array('br.id' => $branch_id));
        $results = $this->db->get()->result_array();
        return $results;
    }
    public function branches()
    {
        $this->db->select('br.*,ed.id as lead_id');
        $this->db->from('ontime_branches as br');
        $this->db->join('leads ed', 'br.id=ed.branch_id', 'left');
        $this->db->group_by('branch_name');
        // $this->db->get();
        //echo $this->db->last_query();die;
        $results = $this->db->get()->result_array();
        return $results;
    }
    public function get_payment_status($lead_id)
    {
        $this->db->select('br.*,ed.id as lead_id');
        $this->db->from('ontime_branches as br');
        $this->db->join('leads ed', 'br.id=ed.branch_id', 'inner');
        $this->db->where(array('ed.id' => $lead_id));
        $this->db->group_by('branch_name');
        // $this->db->get();
        //echo $this->db->last_query();die;
        $results = $this->db->get()->result_array();
        return $results;
    }

    public function get_last_action_log($enquiry_id)
    {
        $this->db->select('eal.*, u.first_name, ea.action_name');
        $this->db->from('enquiry_action_log as eal');
        $this->db->join('users as u', 'eal.action_by=u.user_id');
        //$this->db->join('enquiry_status as es', 'eal.status_id=es.id');
        $this->db->join('enquiry_actions as ea', 'eal.action_id=ea.id');
        $this->db->where('eal.enquiry_id', $enquiry_id);
        $this->db->order_by('eal.id', 'DESC');
        $this->db->limit('1');
        $results = $this->db->get()->result_array();
        $user = '';
        $action_on = '';
        $action_name = '';
        foreach ($results as $key => $value) {
            $user = $value['first_name'];
            $action_on = date('d M Y H:i', strtotime($value['action_on']));
            $action_name = $value['action_name'];
        }

        if ($action_name == '' || $user == '' || $action_on == '') {
            return '--';
        } else {
            return [$action_name, $user, $action_on];
            // return $action_name. ' by <br/>'.$user.' <br />'. ' on <br />'.$action_on;    
        }
    }
}
