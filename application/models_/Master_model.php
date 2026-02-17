<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_model extends CI_Model
{
    public $parents_array;

    public function __construct()
    {
        parent::__construct();
        //$folder_obj = new Folder();
        $parents_array = array();
    }

    /**
     * Get an unused ID for user creation
     *
     * @return  int between 1200 and 4294967295
     */
    public function role_name()
    {
        $this->db->select("*");
        $this->db->from('m_roles as r');
        $this->db->order_by("r.role_id", "desc");
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function industry_name()
    {
        $this->db->select("*");
        $this->db->from('m_industries as r');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function industry_name_active()
    {
        $this->db->select("*");
        $this->db->from('m_industries as r');
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function company_name()
    {
        $this->db->select("*");
        $this->db->from('companies as r');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function role_name_active()
    {
        $this->db->select("*");
        $this->db->from('m_roles as r');
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function users()
    {
        $this->db->select("*");
        $this->db->from('users as u');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function users_active()
    {
        $this->db->select("*");
        $this->db->from('users as u');
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function company_name_active()
    {
        $this->db->select("*");
        $this->db->from('companies as r');
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function assign_user()
    {
        $this->db->select("*,cu.id as c_id");
        $this->db->from('company_users as cu');
        $this->db->join('companies as c', 'c.id=cu.company_id');
        $this->db->join('users as u', 'u.user_id=cu.user_id');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function category_name()
    {
        $this->db->select("*");
        $this->db->from('m_category as c');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function service_hours()
    {
        $this->db->select("*");
        $this->db->from('m_service_hours as c');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function attachments()
    {
        $this->db->select("*");
        $this->db->from('attachments as a');
        $this->db->join("m_category as s", "s.id=a.category");
        $this->db->join("m_sub_category as sc", "sc.id=a.sub_category");
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function get_amount()
    {
        $this->db->select("*");
        $this->db->from('service_amount as sa');
        $this->db->join("m_category as s", "s.id=sa.category");
        $this->db->join("m_sub_category as sc", "sc.id=sa.sub_category", 'left');
        $this->db->join("m_service_hours as sh", "sh.id=sa.service_hours", 'left');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function sub_category_name()
    {
        $this->db->select("*,sc.is_active as is,sc.id as sub_id");
        $this->db->from('m_sub_category as sc');
        $this->db->join('m_category as c', 'c.id=sc.main_category');

        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function sub_category_edit($id)
    {
        // print_r($id);exit('fuyfufr');
        $this->db->select("*,c.id as category_id");
        $this->db->from('m_sub_category as sct');
        $this->db->join('m_category as c', 'c.id=sct.main_category');
        $this->db->where('sct.id', $id);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
    public function sub_categorytwo_edit($id)
    {
        $this->db->select("*,c.id as category_id");
        $this->db->from('m_sub_categorytwo as sc');
        $this->db->join('m_category as c', 'c.id=sc.main_category');
        $this->db->where('sc.id', $id);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
    public function sub_category2_name()

    {
        $this->db->select("*");
        $this->db->from('m_sub_categorytwo as sc2');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function update_enquriy_status($id, $update_array)
    {
        $this->db->where("as_id", $id);
        $this->db->update("assigned_enquriy", $update_array);
        return $this->db->affected_rows();
    }
    public function update_complaints_status($id, $update_array)
    {
        $this->db->where("as_id", $id);
        $this->db->update("assigned_complaints", $update_array);
        return $this->db->affected_rows();
    }
    public function update_meeting_status($id, $update_array)
    {
        $this->db->where("as_id", $id);
        $this->db->update("assigned_meeting", $update_array);
        return $this->db->affected_rows();
    }

    public function get_group()
    {
        $this->db->select("*");
        $this->db->from("group_master");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_timeslots()
    {
        $this->db->select("crm_timeslots.*,users.first_name as creator");
        $this->db->from("crm_timeslots");
        $this->db->join("users", "users.user_id=crm_timeslots.updated_by");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_timeslot($id)
    {
        $this->db->select("crm_timeslots.*");
        $this->db->from("crm_timeslots");
        $this->db->where("timeslot_id", $id);
        $query = $this->db->get()->first_row();
        return $query;
    }


    public function get_exceptiondates()
    {
        $this->db->select("crm_timeslot_exception.*,users.first_name as creator");
        $this->db->from("crm_timeslot_exception");
        $this->db->join("users", "users.user_id=crm_timeslot_exception.updated_by");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_exceptiondate($id)
    {
        $this->db->select("crm_timeslot_exception.*");
        $this->db->from("crm_timeslot_exception");
        $this->db->where("timeslot_except_id", $id);
        $query = $this->db->get()->first_row();
        return $query;
    }


    // public function get_timeslots()
    // {
    //     $this->db->select("crm_timeslots.*,users.first_name as creator");
    //     $this->db->from("crm_timeslots");
    //     $this->db->join("users","users.user_id=crm_timeslots.updated_by");
    //     $query = $this->db->get();
    //     return $query->result();
    // }


    public function get_active_groups()
    {
        $this->db->select("*");
        $this->db->from("group_master");
        $this->db->where("status", 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function group_company()
    {
        $this->db->select("*");
        $this->db->from("group_company as gc");
        $this->db->join("group_master as gm", "gm.g_id=gc.group_id");
        $this->db->join("companies as c", "c.id=gc.company_id");
        $query = $this->db->get();
        return $query->result();
    }

    public function group_users()
    {
        $this->db->select("*");
        $this->db->from("group_users as gu");
        $this->db->join("group_master as gm", "gm.g_id=gu.group_id");
        $this->db->join("users as u", "u.user_id=gu.user_id");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_users()
    {
        $this->db->select("u.*,mr.role_name as role_name");
        $this->db->from("users as u");
        $this->db->join("m_roles as mr", "mr.role_id=u.role_id", "left");
        $this->db->where("u.role_id !=", "4");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_groups()
    {
        $this->db->select("groups.*,CONCAT(users.first_name,' ',users.last_name) as creator,count(DISTINCT group_members.group_member_id) as members_count");
        $this->db->from("groups");
        $this->db->join("group_members", "group_members.group_id=groups.group_id", "left");
        $this->db->join("users", "users.user_id=groups.created_by");
        $this->db->group_by("groups.group_id");
        $this->db->order_by("groups.group_name");
        // $this->db->where("u.role_id !=","4");
        $query = $this->db->get();
        // print_r($this->db->last_query());
        // exit();
        return $query->result_array();
    }

    public function get_user_groups($id)
    {
        $this->db->select("groups.*,IF(group_members.user_id=" . $id . ",1,0) as is_selected");
        $this->db->from("groups");
        $this->db->join("group_members", "group_members.group_id=groups.group_id and group_members.user_id = " . $id, "left");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_accessable_groups($id)
    {
        $this->db->select("groups.*,IF(users_group_accessables.ug_user_id=" . $id . ",1,0) as is_selected");
        $this->db->from("groups");
        $this->db->join("users_group_accessables", "users_group_accessables.ug_group_id=groups.group_id and users_group_accessables.ug_user_id = " . $id, "left");
        $query = $this->db->get();

        return $query->result_array();
    }

    public function crm_roles()
    {
        $this->db->select("*");
        $this->db->from('m_roles as r');
        $this->db->where('is_active', 1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function lead_services()
    {
        $this->db->select("*");
        $this->db->from('ontime_category_services as l');
        $this->db->join('ontime_categories as c', 'l.category_id=c.category_id');
        $this->db->where('l.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function lead_categories($id = "")
    {
        if ($id == "") {
            $this->db->select("*");
            $this->db->from('ontime_categories as l');
            $this->db->join('ontime_category_services as c', 'l.category_id=c.category_id and c.is_active = 1');
            $this->db->group_by("l.category_id");
            $query = $this->db->get();
            return $query->result_array();
        } else {
            // $this->db->select("*,IF(group_id=".$id.",1,0) as is_selected");
            $this->db->select("ontime_categories.*,IF(group_categories.group_id=" . $id . ",1,0) as is_selected");
            $this->db->from('ontime_categories');
            $this->db->join('group_categories', 'group_categories.category_id = ontime_categories.id and group_categories.group_id=' . $id, 'left');
            $query = $this->db->get();
            // print_r($this->db->last_query());
            return $query->result_array();
        }
    }

    public function package_services()
    {
        $this->db->select("l.id as pservice_id,l.govt_fee as govt_fee, l.typing_fee as typing_fee, p.package_id, p.package_name as package_name, s.service_name as service_name");
        $this->db->from('lead_package_services as l');
        $this->db->join('lead_packages as p', 'l.package_id=p.package_id');
        $this->db->join('ontime_category_services as s', 'l.service_id=s.service_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function user_categories()
    {
        $this->db->select("l.id as id, c.category_name as category_name, u.first_name as user_name,u.email as user_email,l.is_active as is_active");
        $this->db->from('ontime_user_categories as l');
        $this->db->join('ontime_categories as c', 'l.category_id=c.category_id');
        $this->db->join('users as u', 'l.user_id=u.user_id');
        $this->db->where('l.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function website_categories()
    {
        $this->db->select("owc.id as id, c.category_name as category_name, ow.website_name as website_name, owc.is_active as is_active");
        $this->db->from('ontime_website_category_map as owc');
        $this->db->join('ontime_categories as c', 'owc.category_id=c.category_id');
        $this->db->join('ontime_websites as ow', 'ow.id=owc.website_id');
        //$this->db->where('l.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function workflows()
    {
        $this->db->select("lw.*,ls.service_name");
        $this->db->from('lead_workflows as lw');
        $this->db->join('ontime_category_services as ls', 'ls.service_id=lw.parent_service_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function workflow_entries($workflow_id = '')
    {
        $this->db->select("lwe.*,ls.service_name,lw.workflow_name");
        $this->db->from('lead_workflow_entries as lwe');
        $this->db->join('lead_workflows as lw', 'lw.id=lwe.workflow_id');
        $this->db->join('ontime_category_services as ls', 'lwe.target_service_id=ls.service_id');
        if ($workflow_id != '') {
            $this->db->where('lwe.workflow_id', $workflow_id);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function websites($id = "")
    {
        if ($id == "") {
            $this->db->select("*")->from("ontime_websites")->where("is_active", 1);
            $result = $this->db->get()->result_array();
            return $result;
        } else {
            $this->db->select("ontime_websites.*,if(group_websites.group_web_id IS NOT NULL,'1','0') as is_selected")->where("ontime_websites.is_active", 1)->from("ontime_websites");
            $this->db->join("group_websites", "group_websites.website_id=ontime_websites.id and group_websites.group_id=" . $id, "left");
            // print_r($this->db->last_query());
            $result = $this->db->get()->result_array();
            return $result;
        }
    }
}
