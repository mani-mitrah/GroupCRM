<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Address_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insert_user_address($insert_array)
    {
        $this->db->insert("customer_address", $insert_array);
        return $this->db->insert_id();
    }

    public function check_for_address($city, $addres_line1, $pincode, $company_id, $user_id, $is_active)
    {
        $this->db->select("*");
        $this->db->from("customer_address");
        $this->db->where("city", $city);
        $this->db->where("address1", $addres_line1);
        $this->db->where("pincode", $pincode);
        $this->db->where("company_id", $company_id);
        $this->db->where("customer_id", $user_id);
        $this->db->where("dis_active", $is_active);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_for_address_id($address_id)
    {
        $this->db->select("*");
        $this->db->from("customer_address");
        $this->db->where("id", $address_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_address_number($address_id)
    {
        $this->db->select("*");
        $this->db->from("customer_address");
        $this->db->where("id", $address_id);
        $query = $this->db->get();
        $ret = $query->row();
        $customer_id = $ret->customer_id;

        $this->db->select("*");
        $this->db->from("customer_address");
        $this->db->where("customer_id", $customer_id);
        $query = $this->db->get();
        return $query->num_rows();

    }

    public function check_for_user_address($user_id)
    {
        $this->db->select("*");
        $this->db->from("customer_address");
        $this->db->where("customer_id", $user_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_cart_address($user_id)
    {
        $this->db->select("*");
        $this->db->from("bag");
        $this->db->where("user_id", $user_id);
        $this->db->order_by("bag_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $ret = $query->row();
        $this->db->select("*,ca.id as add_id");
        $this->db->from("customer_address as ca");
        $this->db->where("ca.id", $ret->address);
        $this->db->join("country as c", "c.id=ca.country");
        $this->db->join("states as s", "s.id=ca.state");
        $this->db->join("city as cc", "cc.id=ca.city");
        $this->db->join("pincode as p", "p.id=ca.pincode");
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "id" => $res->add_id,
                "address1" => $res->address1,
                "address2" => $res->address2,
                "country" => $res->country,
                "state" => $res->name,
                "city" => $res->city,
                "pincode" => $res->pincode,
                "company_id" => $res->company_id,
                "customer_id" => $res->customer_id,
                "user_latitude" => $res->user_latitude,
                "user_longitude" => $res->user_longitude,
            );
        }
        return $result_array;
    }

    public function get_user_address($user_id)
    {
        $this->db->select("*,ca.id as add_id");
        $this->db->from("customer_address as ca");
        $this->db->where("ca.customer_id", $user_id);
        $this->db->join("country as c", "c.id=ca.country");
        $this->db->join("states as s", "s.id=ca.state");
        $this->db->join("city as cc", "cc.id=ca.city");
        $this->db->join("pincode as p", "p.id=ca.pincode");
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "id" => $res->add_id,
                "address1" => $res->address1,
                "address2" => $res->address2,
                "country" => $res->country,
                "state" => $res->name,
                "city" => $res->city,
                "pincode" => $res->pincode,
                "company_id" => $res->company_id,
                "customer_id" => $res->customer_id,
                "user_latitude" => $res->user_latitude,
                "user_longitude" => $res->user_longitude,
            );
        }
        return $result_array;
    }

    public function get_user_address_default($user_id)
    {
        $this->db->select("*");
        $this->db->from("customer_address");
        $this->db->where("customer_id", $user_id);
        $this->db->order_by("id", "asc");
        $query = $this->db->get();
        $ret = $query->row();
        $this->db->select("*,ca.id as add_id");
        $this->db->from("customer_address as ca");
        $this->db->where("ca.id", $ret->id);
        $this->db->join("country as c", "c.id=ca.country");
        $this->db->join("states as s", "s.id=ca.state");
        $this->db->join("city as cc", "cc.id=ca.city");
        $this->db->join("pincode as p", "p.id=ca.pincode");
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "id" => $res->add_id,
                "address1" => $res->address1,
                "address2" => $res->address2,
                "country" => $res->country,
                "state" => $res->name,
                "city" => $res->city,
                "pincode" => $res->pincode,
                "company_id" => $res->company_id,
                "customer_id" => $res->customer_id,
                "user_latitude" => $res->user_latitude,
                "user_longitude" => $res->user_longitude,
            );
        }
        return $result_array;
    }

    public function check_branch($company_id, $pincode)
    {
        $this->db->select("*");
        $this->db->from("assign_pincode");
        $this->db->where("pincode", $pincode);
        $this->db->where("company_id", $company_id);
        $query1 = $this->db->get();
        $ret1 = $query1->row();
        return $ret1->id;
    }

    public function update_user_address($update_array, $address_id)
    {
        $this->db->where("id", $address_id);
        $this->db->update("customer_address", $update_array);
        return $this->db->affected_rows();
    }

    public function delete_user_address($address_id)
    {
        $this->db->where("id", $address_id);
        $this->db->delete("customer_address");
        return $this->db->affected_rows();
    }

    public function get_pincodes($country_id, $state_id, $city_id)
    {
        $this->db->select("*");
        $this->db->from("pincode");
        $this->db->where("country_id", $country_id);
        $this->db->where("state_id", $state_id);
        $this->db->where("city_id", $city_id);
        $query = $this->db->get();
        return $query->result();
    }

}