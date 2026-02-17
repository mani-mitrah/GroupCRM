<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Profile_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    

    public function services($service_id)
    {
        //print_r($service_id);exit('hdiuhdydy');


        $this->db->select('s.id,
                        s.category,
                        s.sub_category,
                        s.sub_category_two,
                        s.gender,
                        s.service_hours,
                        s.medical_number,
                        s.pregnancy,
                        s.period,
                        s.abortion,
                        s.contraceptive_pills,
                        s.x_ray');
        $this->db->from('m_services as s');
        $this->db->where('s.id', $service_id);
        $result = $this->db->get()->result();

        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "id" => $res->id,
                "category" => $res->category,
                "sub_category" => $res->sub_category,
                "sub_category_two" => $res->sub_category_two,
                "gender" => $res->gender,
                "service_hours" => $res->service_hours,
                "medical_number" => $res->medical_number,
                "pregnancy" => $res->pregnancy,

                "period" => $res->period,
                "abortion" => $res->abortion,
                "contraceptive_pills" => $res->contraceptive_pills,
                "x_ray" => $res->x_ray,
                
               
            );
        }
        return $result_array;

    }

    public function get_attachments($category,$sub_category,$gender){
        $this->db->select("*");
        $this->db->from("attachments");
        $this->db->where("category",$category);
        $this->db->where("sub_category",$sub_category);
        $this->db->where("gender",$gender);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_sub_category($category_id){
        $this->db->select("*");
        $this->db->from("m_sub_category");
        $this->db->where("main_category",$category_id);
        $this->db->where("is_active",1);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_sub_category_two($category_id,$sub_category){
        $this->db->select("*");
        $this->db->from("m_sub_categorytwo");
        $this->db->where("main_category",$category_id);
        $this->db->where("sub_category",$sub_category);
        $this->db->where("is_active",1);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_service_amount($category_id,$sub_category,$gender,$hours){
        $this->db->select("*");
        $this->db->from("service_amount");
        $this->db->where("category",$category_id);
        $this->db->where("sub_category",$sub_category);
        $this->db->where("gender",$gender);
        $this->db->where("service_hours",$hours);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_medical_amount($category_id,$hours){
        $this->db->select("*");
        $this->db->from("service_amount_mrn_yes");
        $this->db->where("category_id",$category_id);
        $this->db->where("service_hours",$hours);
        $this->db->where("is_active",1);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_user_cart_count($user_id){
        $this->db->select("*");
        $this->db->from("services_cart");
        $this->db->where("user_id",$user_id);
        $query=$this->db->get();
        return $query->num_rows();
    }

    public function get_sess_cart_count($sess_id){
        $this->db->select("*");
        $this->db->from("services_cart");
        $this->db->where("session_id",$sess_id);
        $query=$this->db->get();
        return $query->num_rows();
    }

    public function get_all_services_cart($u){
        $this->db->select("*");
        $this->db->from("services_cart");
        $this->db->where("session_id",$u);
        $query=$this->db->get();
        return $query->result();
    }

    public function update_session_cart($update_array,$u){
        $this->db->where("session_id",$u);
        $this->db->update("services_cart",$update_array);
        return $this->db->affected_rows();
    }

    public function get_user_service_cart($user_id){
        $this->db->select("*");
        $this->db->from("services_cart as sc");
        $this->db->join("companies as c","c.id=sc.company");
        $this->db->join("m_category as mc","mc.id=category");
        $this->db->join("m_sub_category as cs","cs.id=sub_category");
        $this->db->where("sc.user_id",$user_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function get_sess_service_cart($sess_id){
        $this->db->select("*");
        $this->db->from("services_cart as sc");
        $this->db->join("companies as c","c.id=sc.company");
        $this->db->join("m_category as mc","mc.id=category");
        $this->db->join("m_sub_category as cs","cs.id=sub_category");
        $this->db->join("m_service_hours as sh","sh.id=sc.service_time");
        $this->db->where("sc.session_id",$sess_id);
        $query=$this->db->get();
        return $query->result();
    }

    public function payment_success($insert_array,$userId){
        $this->db->select("*");
        $this->db->from("services_cart");
        $this->db->where("user_id",$userId);
        $query=$this->db->get();
        $result=$query->result();
        foreach($result as $res){
            $service=array(
                'user_id'=>$res->user_id,
                'company'=>$res->company,
                'medical_examination_number'=>$res->medical_examination_number,
                'category'=>$res->category,
                'sub_category_two'=>$res->sub_category_two,
                'sub_category'=>$res->sub_category,
                'visa_type'=>$res->visa_type,
                'gender'=>$res->gender,
                'pregency'=>$res->pregency,
                'menstrual_period'=>$res->menstrual_period,
                'abortion'=>$res->abortion,
                'contraceptive_pills'=>$res->contraceptive_pills,
                'x_ray'=>$res->x_ray,
                'attachment_no'=>$res->attachment_no,
                'service_time'=>$res->service_time,
                'amount'=>$res->amount,
                'payment_date'=>date('d-m-Y'),
            );
            $this->db->insert("user_service",$service);
            $insert_id=$this->db->insert_id();
            if($insert_id){
                $this->db->insert("payments",$insert_array);
                $payment_insert=$this->db->insert_id();
                $this->db->where("user_id",$userId);
                $this->db->delete("services_cart");
            }
        }
        return $payment_insert;
    }

    public function basic($user_id)
    {
        $this->db->select('
        u.user_id,
        u.username,
        u.first_name,
        u.last_name,
        u.email,
        u.mobile,
        u.profile_pic,
        u.banned,'
        );
        $this->db->from('users as u');
        $this->db->where('u.user_id', $user_id);
        $result = $this->db->get()->row_array();
        return $result;

    }

    public function profile($user_id)
    {
        $this->db->select('up.user_id,
                        up.username,
                        up.first_name,
                        up.last_name,
                           up.email,
                           up.mobile,
                           up.profile_pic,
                           up.auth_level,
                           up.device_id,
                           up.otp');
        $this->db->from('users as up');
        $this->db->where('up.user_id', $user_id);
        $result = $this->db->get()->result();

        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "user_id" => $res->user_id,
                "username" => $res->username,
                "first_name"=>$res->first_name,
                "last_name"=>$res->last_name,
                "email" => $res->email,
                "mobile" => $res->mobile,
                "profile_pic" => $res->profile_pic,
                "auth_level" => $res->auth_level,
                "device_id" => $res->device_id,
              "otp" => $res->otp,

            );
        }
        return $result_array;

    }
    public function profile1($user_id)
    {
        $this->db->select('up.user_id,
                        up.first_name,
                        up.last_name,
                           up.email,
                           up.mobile,
                           up.profile_pic,
                           up.auth_level,
                           up.device_id');
        $this->db->from('users as up');
        $this->db->where('up.user_id', $user_id);
        $result = $this->db->get()->result();

        $result_array = array();
        foreach ($result as $res) {
            $result_array[] = array(
                "user_id" => $res->user_id,
                "first_name"=>$res->first_name,
                "last_name"=>$res->last_name,
                "email" => $res->email,
                "mobile" => $res->mobile,
                "profile_pic" => $res->profile_pic,
                "auth_level" => $res->auth_level,

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

    public function check_user_existance($id)
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->where("user_id", $id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function check_for_address($user_id, $door_no, $street_name)
    {
        $this->db->select("*");
        $this->db->from("user_address");
        $this->db->where("user_id", $user_id);
        $this->db->where("door_no", $door_no);
        $this->db->where("street_name", $street_name);
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
 public function resend_otp($user_id, $otp)
    {
        print_r($user_id);exit('dygyud');
       

        $data = array(
            'otp' => $otp,
        );
        $this->db->where("user_id", $user_id);
        $this->db->update("users", $data);
        return true;
    }

    public function insert_notification($notification)
    {
        $this->db->insert("notifications", $notification);
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

        $this->db->select("*");
        $this->db->from("orders as o");
        $this->db->where("o.taken_by", $so_id);
        $this->db->group_by("o.o_id");
        $this->db->where('o.o_created_date =', date('d-m-Y'));
        $this->db->order_by("o.o_created_date", "desc");
        $this->db->where("o.order_status", 3);
        $query2 = $this->db->get();
        $result2 = $query2->result();
        $a_amount = 0;
        foreach ($result2 as $aam) {
            $a_amount += $aam->order_amount;
        }
        $count2 = $query2->num_rows();

        $final = array(
            'Kilometer' => number_format((float) $distance_travelled, 2, '.', ''),
            "amount" => ceil($travel_allowance),
            "dealers" => $visited_dealer,
            "dealers_count" => $count2,
            "remainder" => $count4,
            "remainders_count" => $count3,
            'pending_sales_count' => $count1,
            'pending_sales_amount' => $amount,
            'approved_sales_count' => $count2,
            'approved_sales_amount' => $a_amount,
        );
        return $final;
    }

    public function update_device_id($user_id, $device_id)
    {
        $update_array = array(
            'device_id' => $device_id,
        );
        $this->db->where("user_id", $user_id);
        $this->db->update("users", $update_array);
        return $this->db->affected_rows();
    }

    public function get_sub_category_three($category_id, $sub_category_id, $sub_category_two_id){
        $this->db->select("*");
        $this->db->from("wq_subcategory_three");
        $this->db->where("is_active",1);
        $this->db->where("main_category_id",$category_id);
        $this->db->where("subcategory_id",$sub_category_id);
        $this->db->where("subcategory_two_id",$sub_category_two_id);
        $query=$this->db->get();
        return $query->result();
    } 

}