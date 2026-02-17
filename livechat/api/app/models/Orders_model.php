<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Orders_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_cart($cart_id)
    {
        $this->db->select("*");
        $this->db->from("bag");
        $this->db->where("bag_id", $cart_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_bag_items($cart_id)
    {
        $this->db->select("*");
        $this->db->from("bag_items");
        $this->db->where("bag_id", $cart_id);
        $this->db->where("bg_status", 1);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_orders($orders)
    {
        $this->db->insert("orders", $orders);
        return $this->db->insert_id();
    }

    public function delete_cart($cart_id)
    {
        $this->db->where("bag_id", $cart_id);
        $this->db->delete("bag");
        $this->db->where("bag_id", $cart_id);
        $this->db->delete("bag_items");
        return $this->db->affected_rows();
    }

    public function get_orders($order_id)
    {
        $company = $this->mcommon->specific_row_value('orders', array('o_id' => $order_id), 'company_id');

        $this->db->select('*');
        $this->db->from('orders as o');
        $this->db->join("order_items as oi", "oi.ori_order_id=o.o_id");
        $this->db->join("set_uom as su", "su.u_id=oi.ori_uom");
        $this->db->join("products as p", "p.product_id=oi.ori_product");
        $this->db->where("o.o_id", $order_id);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from("products_sku");
            $this->db->where("product_id", $res->product_id);
            $this->db->where("unit", $res->u_id);
            $query = $this->db->get();
            $ret = $query->row();
            $result_array[] = array(
                'order_item_id' => $res->order_item_id,
                'product' => $res->product_name,
                'price' => $ret->selling_price,
                'uom' => $res->uom,
                'quantity' => $res->ori_quantity,
                'amount' => $res->ori_amount,

            );
        }
        $this->db->select('*');
        $this->db->from('orders as o');
        $this->db->join("order_items as oi", "oi.ori_order_id=o.o_id");
        $this->db->join("set_uom as su", "su.u_id=oi.ori_uom");
        $this->db->join("users as u", "u.user_id=o.customer");
        $this->db->join("customer_address as ua", "ua.id=o.address");
        $this->db->join("assign_pincode as ap", "ap.pincode=ua.pincode");
        $this->db->join("pincode as pc", "pc.id=ap.pincode");
        $this->db->join("products as p", "p.product_id=oi.ori_product");
        $this->db->where("o.o_id", $order_id);
        $this->db->group_by("oi.ori_order_id");
        $query = $this->db->get();
        $result = $query->result();
        $output = array();
        $overall_amount = 0;
        foreach ($result as $res) {
            $overall_amount += $res->ori_amount;
            $this->db->select("*");
            $this->db->from("em_branches");
            $this->db->where("id", $res->branch_id);
            $query = $this->db->get();
            $ret = $query->row();
            $branch_latitude = $ret->branch_latitude;
            $branch_longitude = $ret->branch_longitude;

            $this->db->select("*");
            $this->db->from("em_companies");
            $this->db->where("id", $res->company_id);
            $query3 = $this->db->get();
            $ret3 = $query3->row();
            $free_delivery = $ret3->free_delivery_amount;

//Distance//
            if ($branch_latitude != '' && $branch_longitude != '') {
                $this->db->select("*");
                $this->db->from("delivery_charges");
                $this->db->where("company_id", $company);
                $this->db->limit(1);
                $this->db->order_by("dl_chrg_id", 'desc');
                $query2 = $this->db->get();
                $ret2 = $query2->row();
                $delivery_charge = $ret2->del_chrg;

                $earthRadius = 6371000;
                $latFrom = deg2rad($branch_latitude);
                $lonFrom = deg2rad($branch_longitude);
                $latTo = deg2rad($res->user_latitude);
                $lonTo = deg2rad($res->user_longitude);

                $lonDelta = $lonTo - $lonFrom;
                $a = pow(cos($latTo) * sin($lonDelta), 2) +
                pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
                $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

                $angle = atan2(sqrt($a), $b);
                $angle * $earthRadius;
                $distance = $angle * $earthRadius;
                $corrected_distance = round($distance / 1000, 2);
                //Distance Calculation//
                $distance = round($corrected_distance);
            } else {
                $distance = 0;
            }

            //Delivery Charge//

            $this->db->select("*");
            $this->db->from("country");
            $this->db->where('id', $res->country);
            $query0 = $this->db->get();
            $ret0 = $query0->row();
            $country_name = $ret0->country;

            $this->db->select("*");
            $this->db->from("states");
            $this->db->where('id', $res->state);
            $query1 = $this->db->get();
            $ret1 = $query1->row();
            $state_name = $ret1->name;

            $this->db->select("*");
            $this->db->from("city");
            $this->db->where('id', $res->city);
            $query2 = $this->db->get();
            $ret2 = $query2->row();
            $city_name = $ret2->city;

            $address = $address1 . ',' . $city_name . ',' . $state_name . ',' . $pincodes . ',' . $country_name;

            $delivery_amount = $res->delivery_amount;
            $gst = $res->order_sgst + $res->order_cgst;
            $gst_amount = ($gst / 100) * $res->cart_amount;
            $mode = $this->mcommon->specific_row_value('payment_details', array('order_id' => $res->o_id), 'payment_mode');
            $output[] = array(
                'order_id' => $res->o_id,
                'agent_code' => $res->agent,
                'order_status' => $res->order_status,
                'mode_of_payment' => $mode,
                'out_of_delivery_date' => $res->out_of_delivery_date,
                'delivered_date' => $res->delivered_date,
                'order_date' => $res->o_created_date,
                'gst' => $gst,
                'gst_amount' => $gst_amount,
                'order_amount' => $res->order_amount,
                'overall_amount' => $overall_amount,
                'total_saving' => $res->order_amount - $overall_amount,
                'total_amount' => $res->order_amount + $gst_amount + $delivery_amount,
                'order_cgst' => $res->order_cgst,
                'order_sgst' => $res->order_sgst,
                'user_name' => $res->first_name . ' ' . $res->last_name,
                'address' => $res->address1 . ',' . $res->address2 . ',' . $city_name . '-' . $res->pincode . ',' . $state_name . ',' . $country_name,
                'distance_travelled' => $distance,
                'delivery_charge' => $delivery_amount,
                'products' => $result_array,
            );
        }
        return $output;
    }

    public function get_orders_all($user_id)
    {
        $this->db->select('*');
        $this->db->from('orders as o');
        // $this->db->join("order_items as oi", "oi.ori_order_id=o.o_id");
        // $this->db->join("set_uom as su", "su.u_id=oi.ori_uom");
        // $this->db->join("products as p", "p.product_id=oi.ori_product");
        $this->db->where("o.customer", $user_id);
        $this->db->order_by("o.o_id", "desc");
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();

        foreach ($result as $res) {

            $this->db->select('*');
            $this->db->from('orders as o');
            $this->db->join("order_items as oi", "oi.ori_order_id=o.o_id");
            $this->db->join("set_uom as su", "su.u_id=oi.ori_uom");
            $this->db->join("products as p", "p.product_id=oi.ori_product");
            // $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            // $this->db->where("sk.unit", $res->ori_uom);
            $this->db->where("o.o_id", $res->o_id);
            $this->db->order_by("o.o_id", "desc");
            $query = $this->db->get();
            $result = $query->result();
            $result_array = array();
            foreach ($result as $res1) {
                $selling_price = $this->mcommon->specific_row_value('products_sku', array('product_id' => $res1->product_id, 'unit' => $res1->ori_uom), 'selling_price');
                $result_array[] = array(
                    'order_item_id' => $res1->order_item_id,
                    'product' => $res1->product_name,
                    'uom' => $res1->uom,
                    'quantity' => $res1->ori_quantity,
                    'price' => $selling_price,
                    'amount' => $res1->ori_amount,
                );
            }
            $mode = $this->mcommon->specific_row_value('payment_details', array('order_id' => $res->o_id), 'payment_mode');

            $this->db->select("*");
            $this->db->from("em_companies");
            $this->db->where("id", $res->company_id);
            $query3 = $this->db->get();
            $ret3 = $query3->row();
            $free_delivery = $ret3->free_delivery_amount;

            if ($res->order_amount >= $free_delivery) {
                $delivery_amount = 0;
            } else {
                $delivery_amount = $res->delivery_amount;
            }
            $gst = $res->order_sgst + $res->order_cgst;
            $gst_amount = ($gst / 100) * $res->cart_amount;
            $output[] = array(
                'order_id' => $res->o_id,
                'agent_code' => $res->agent,
                'mode_of_payment' => $mode,
                'order_status' => $res->order_status,
                'out_for_delivery_date' => $res->out_for_delivery_date,
                'out_for_delivery_time' => $res->out_for_delivery_time,
                'delivered_date' => $res->delivered_date,
                'delivered_time' => $res->delivered_time,
                'order_date' => $res->o_created_date,
                'delivery_amount' => $delivery_amount,
                'gst_amount' => $gst_amount,
                'order_amount' => $res->order_amount,
                'total_amount' => $res->order_amount + $gst_amount + $delivery_amount,
                'products' => $result_array,
            );

        }
        return $output;

    }

    public function re_order($order_id)
    {
        $this->db->select("*");
        $this->db->from("orders");
        $this->db->where("o_id", $order_id);
        $query = $this->db->get();
        $ret = $query->row();
        $orders = array();

        $orders = array(
            'customer' => $ret->customer,
            'address' => $ret->address,
            'company_id' => $ret->company_id,
            'branch_id' => $ret->branch_id,
            'cart_amount' => $ret->cart_amount,
            'cart_original_amount' => $ret->cart_original_amount,
            'order_amount' => $ret->cart_original_amount,
            'order_cgst' => $ret->order_cgst,
            'order_sgst' => $ret->order_sgst,
            'order_status' => 1,
            'delivery_amount' => $ret->delivery_amount,
            'remarks' => 'App Order',
            'approved_by' => 0,
            'o_created_time' => date("g:i A"),
            'o_created_date' => date('d-m-Y'),
        );
        $this->db->insert("orders", $orders);
        $o_id = $this->db->insert_id();
        $this->db->select("*");
        $this->db->from("order_items");
        $this->db->where("ori_order_id", $order_id);
        $query = $this->db->get();
        $result = $query->result();
        $order_items = array();
        foreach ($result as $res) {
            $order_items[] = array(
                'ori_order_id' => $o_id,
                'ori_uom' => $res->ori_uom,
                'ori_category' => $res->ori_category,
                'ori_product' => $res->ori_product,
                'ori_amount' => $res->ori_amount,
                'ori_cgst' => $res->ori_cgst,
                'ori_sgst' => $res->ori_sgst,
                'ori_quantity' => $res->ori_quantity,
                'ori_created_date' => date('d-m-Y'),
            );

        }
        $this->db->insert_batch('order_items', $order_items);
        return $o_id;
    }

    public function update_stock($id, $stock_update)
    {
        $this->db->where("pro_id", $id);
        $this->db->update("stocks", $stock_update);
        return $this->db->affected_rows();
    }

}