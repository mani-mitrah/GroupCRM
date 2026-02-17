<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Profile_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function basic($user_id)
    {
        $this->db->select('
        u.user_id,
        u.username,
        u.first_name,
        u.last_name,
        u.profile_pic,
        u.email,
        u.mobile,
        u.banned,
        e.id as role_id,
        e.role_name'
        );
        $this->db->from('users as u');
        $this->db->join("em_roles as e", "e.auth_level=u.auth_level");
        $this->db->where('u.user_id', $user_id);
        $query = $this->db->get();
        return $query->result();

    }

    public function update_device_id($user_id, $device_id)
    {
        $data = array(
            'firebase_instance_id' => $device_id,
        );
        $this->db->where("user_id", $user_id);
        $this->db->update("users", $data);
        return true;
    }

    public function profile($user_id)
    {
        $this->db->select('up.user_id,
                        up.username,
						   up.email,
                           up.mobile,
                           up.profile_pic,
                           up.auth_level,
                           up.firebase_instance_id as device_id,
                           em.role_name');
        $this->db->from('users as up');
        $this->db->join('em_roles as em', 'em.auth_level=up.auth_level');
        $this->db->where('up.user_id', $user_id);
        $result = $this->db->get()->result();

        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "user_id" => $res->user_id,
                "username" => $res->username,
                "email" => $res->email,
                "mobile" => $res->mobile,
                "profile_pic" => $res->profile_pic,
                "auth_level" => $res->auth_level,
                "device_id" => $res->device_id,
                "role_name" => $res->role_name,
            );
        }
        return $result_array;

    }

    public function get_user_by_email($email)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("email", $email);
        $this->db->where('banned', '0');
        $query = $this->db->get();
        return $query->result();
    }

    public function check_email_existance($email)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("email", $email);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_email_existance_company($email, $company_id)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("email", $email);
        $this->db->where("company_id", $company_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_user_existance($id)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("user_id", $id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function update_user_password($email, $password)
    {
        $data = array(
            'passwd' => $password,
        );
        $this->db->where("email", $email);
        $this->db->update("users", $data);
        return true;
    }

    public function insert_notification($notification)
    {
        $this->db->insert("web_push_notification", $notification);
        return $this->db->insert_id();
    }

    public function get_dashboard($so_id)
    {
        //Working Days//
        $dateOne = new DateTime($from);
        $dateTwo = new DateTime($to);
        $days = $diff = $dateTwo->diff($dateOne)->format("%a");

        $this->db->select('*');
        $this->db->from("leave_request");
        $this->db->where("user_id", $so_id);
        $this->db->where('created_date =', date('d-m-Y'));
        $query = $this->db->get();
        $leave = $query->num_rows();

        $worked_days = (int) $days - $leave;

        //Distance Traveled//
        $this->db->select('*');
        $this->db->from("travel_history");
        $this->db->where("user_id", $so_id);
        $this->db->where("dealer_id!=", 0);
        $this->db->where('created_date =', date('d-m-Y'));
        $query1 = $this->db->get();
        $result = $query1->result();
        $distance_travelled = 0;
        $travel_allowance = 0;
        foreach ($result as $res) {
            $distance_travelled += $res->distance;
            $travel_allowance += $res->amount;
        }
        //Visited dealers//
        $this->db->select('*');
        $this->db->from("travel_history");
        $this->db->where("user_id", $so_id);
        $this->db->group_by("dealer_id");
        $this->db->where("dealer_id!=", 0);
        $this->db->where('created_date =', date('d-m-Y'));
        $query2 = $this->db->get();
        $visited_dealer = $query2->num_rows();
        $final = array();

        $this->db->select("*");
        $this->db->from("assigned_dealers as dr");
        $this->db->where("dr.ad_so", $so_id);
        $this->db->order_by("dr.ad_created_date", "desc");
        $query2 = $this->db->get();
        $result2 = $query2->result();
        $count2 = $query2->num_rows();

        $this->db->select("*");
        $this->db->from("set_remainders as r");
        $this->db->where("r.user_id", $so_id);
        $this->db->where('r.created_date =', date('d-m-Y'));
        $query4 = $this->db->get();
        $result4 = $query4->result();
        $count4 = $query4->num_rows();

        $this->db->select("*");
        $this->db->from("set_remainders as r");
        $this->db->where("r.user_id", $so_id);
        $query3 = $this->db->get();
        $result3 = $query3->result();
        $count3 = $query3->num_rows();

        $this->db->select("*");
        $this->db->from("orders as o");
        $this->db->where("o.taken_by", $so_id);
        $this->db->group_by("o.o_id");
        $this->db->where('o.o_created_date =', date('d-m-Y'));
        $this->db->order_by("o.o_created_date", "desc");
        $this->db->where("o.order_status", 2);
        $query = $this->db->get();
        $result = $query->result();
        $amount = 0;
        $dealers_id = array();
        foreach ($result as $am) {
            $amount += $am->order_amount;
        }
        $count1 = $query->num_rows();

        $final = array(
            'Kilometer' => number_format((float) $distance_travelled, 2, '.', ''),
            "amount" => ceil($travel_allowance),
            "dealers" => $visited_dealer,
            "dealers_count" => $count2,
            "remainder" => $count4,
            "remainders_count" => $count3,
            'sales_count' => $count1,
            'sales' => $amount,
        );

        return $final;

    }

    public function get_notification($user_id)
    {
        $this->db->select("*");
        $this->db->from("web_push_notification");
        $this->db->where("user_id", $user_id);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        return $query->result();
    }

}