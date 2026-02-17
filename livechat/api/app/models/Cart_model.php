<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cart_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_user($user_id)
    {
        return $user_id;
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("user_id", $user_id);
        $this->db->where("banned", '0');
        $query = $this->db->get();
        $ret = $query->row();
        if ($ret) {
            return $ret->user_id;
        } else {
            return 0;
        }
    }

    public function check_branch($branch_id)
    {
        $this->db->select('*');
        $this->db->from('em_branches');
        $this->db->where("id", $branch_id);
        $query = $this->db->get();
        $ret = $query->row();
        if ($ret) {
            return $ret->id;
        } else {
            return 0;
        }
    }

    public function get_product_amount($cart_item_id, $cart_id)
    {
        $this->db->select('*');
        $this->db->from('bag_items as bi');
        $this->db->where("bi.bg_id", $cart_item_id);
        $this->db->where("bi.bag_id", $cart_id);
        $query = $this->db->get();
        $ret = $query->row();
        $unit = $ret->uom;
        $product = $ret->product;

        $this->db->select('*');
        $this->db->from('products_sku');
        $this->db->where("product_id", $product);
        $this->db->where("unit", $unit);
        $query = $this->db->get();
        $ret = $query->row();
        if ($ret) {
            return $ret->selling_price;
        } else {
            return 0;
        }
    }

    public function check_bag($user_id, $company_id)
    {
        $this->db->select('*');
        $this->db->from('bag as b');
        $this->db->where("b.user_id", $user_id);
        $this->db->where("b.company_id", $company_id);
        $this->db->where("b.bag_status", 1);
        $query = $this->db->get();
        $ret = $query->row();
        if ($ret) {
            return $ret->bag_id;
        } else {
            return 0;
        }

    }

    public function insert_bag($user_id, $branch_id, $company_id, $address_id)
    {
        $insert_array = array(
            'user_id' => $user_id,
            'branch_id' => $branch_id,
            'company_id' => $company_id,
            'address' => $address_id,
            'bag_status' => 1,
            'b_created_time' => date("g:i A"),
            'b_created_date' => date('d-m-Y'),
        );
        $this->db->insert("bag", $insert_array);
        return $this->db->insert_id();
    }
    public function get_cart($cart_id, $coupon, $referal_amount, $applied_referal_code)
    {
        $this->db->select("*,b.company_id as com");
        $this->db->from("bag as b");
        $this->db->where("b.bag_id", $cart_id);
        $this->db->join("customer_address as ca", "ca.id=b.address");
        $this->db->join("assign_pincode as ap", "ap.pincode=ca.pincode");
        $this->db->join("pincode as p", "p.id=ap.pincode");
        $query = $this->db->get();
        $result = $query->result();
        $ret = $query->row();
        $branch = $ret->branch_id;
        $user_id = $ret->user_id;
        $bag_amount = $ret->bag_amount;
        $original_amount = $ret->original_amount;
        $gst = $ret->bag_sgst + $ret->bag_cgst;

        $user_latitude = $ret->user_latitude;
        $user_longitude = $ret->user_longitude;
        $user_zip_code = $ret->pincode;
        $address1 = $ret->address1;
        $address2 = $ret->address2;
        $state = $ret->state;
        $city = $ret->city;
        $country = $ret->country;
        $pin = $ret->pincode;

        $this->db->select("*");
        $this->db->from("em_branches");
        $this->db->where("id", $branch);
        $query1 = $this->db->get();
        $result1 = $query1->result();
        $ret1 = $query1->row();
        $branch_latitude = $ret1->branch_latitude;
        $branch_longitude = $ret1->branch_longitude;
        $company_id = $ret1->company_id;

        $this->db->select("*");
        $this->db->from("em_companies");
        $this->db->where("id", $ret->com);
        $query2 = $this->db->get();
        $ret2 = $query2->row();
        $free_delivery = $ret2->free_delivery_amount;

        if ($branch_latitude != '' && $branch_longitude != '') {
            $this->db->select("*");
            $this->db->from("delivery_charges");
            $this->db->where("company_id", $company_id);
            $this->db->limit(1);
            $this->db->order_by("dl_chrg_id", 'desc');
            $query2 = $this->db->get();
            $ret2 = $query2->row();
            $delivery_amount = $ret2->del_chrg;

            $earthRadius = 6371000;
            $latFrom = deg2rad($branch_latitude);
            $lonFrom = deg2rad($branch_longitude);
            $latTo = deg2rad($user_latitude);
            $lonTo = deg2rad($user_longitude);

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

        $products = array();
        $this->db->select("*,bi.*,u.*,c.*,p.*,bi.uom as punit");
        $this->db->from("bag_items as bi");
        $this->db->where("bi.bag_id", $cart_id);
        $this->db->join("products as p", "p.product_id=bi.product");
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->join("category as c", "c.id=bi.category");
        $this->db->join("set_uom as u", "u.u_id=bi.uom");

        $query = $this->db->get();
        $items = $query->result();
        $dealer_price = 0;
        $final_dealer_price = 0;
        foreach ($items as $i) {
            $dealer_price = $this->mcommon->specific_row_value('products_sku', array('product_id' => $i->product_id, 'unit' => $i->punit), 'dealer_price');
            $sku_id = $this->mcommon->specific_row_value('products_sku', array('product_id' => $i->product_id, 'unit' => $i->punit), 'id');
            $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $sku_id), 'stock');
            $final_dealer_price += $dealer_price * $i->quantity;

            $products[] = array(
                'cart_item_id' => $i->bg_id,
                'category' => $i->category_name,
                'product_id' => $i->product_id,
                'product_name' => $i->product_name,
                'product_image' => $i->photo,
                'uom' => $i->uom,
                'quantity' => $i->quantity,
                'amount' => $i->bg_amount,
                'stock_left' => $stock,
            );
        }

        if (!empty($items)) {

            $this->db->select("*");
            $this->db->from("applied_coupon");
            $this->db->where("user_id", $user_id);
            $this->db->where("coupon_id", $coupon);
            $this->db->where("is_used", 0);
            $query = $this->db->get();
            $applied_coupon = $query->result();
            if ($original_amount >= $free_delivery) {
                $delivery_charge = 0;
            } else {
                $delivery_charge = $distance * $delivery_amount;
            }

            if (!empty($applied_coupon)) {
                $this->db->select("*");
                $this->db->from("coupon");
                $this->db->where("id", $coupon);
                $query = $this->db->get();
                $ret = $query->row();
                $coupon_name = $ret->coupon_name;
                $coupon_value = $ret->coupon_value;

                $coupon_applied = 1;
                $coupon_id = $ret->id;
                $applied_coupon_amount = $original_amount - $coupon_value;
                $final_amount = $delivery_charge + $applied_coupon_amount;
            } else {
                $coupon_name = 0;
                $coupon_value = 0;
                $coupon_applied = 0;
                $coupon_id = 0;
                $applied_coupon_amount = 0;
                $final_amount = $delivery_charge + $original_amount;
            }

            $this->db->select("*");
            $this->db->from("country");
            $this->db->where('id', $country);
            $query0 = $this->db->get();
            $ret0 = $query0->row();
            $country_name = $ret0->country;

            $this->db->select("*");
            $this->db->from("states");
            $this->db->where('id', $state);
            $query1 = $this->db->get();
            $ret1 = $query1->row();
            $state_name = $ret1->name;

            $this->db->select("*");
            $this->db->from("city");
            $this->db->where('id', $city);
            $query2 = $this->db->get();
            $ret2 = $query2->row();
            $city_name = $ret2->city;

            // $this->db->select("*");
            // $this->db->from("pincode");
            // $this->db->where('id', $pin);
            // $query3 = $this->db->get();
            // $ret3 = $query3->row();
            // $pincodes = $ret3->pincode;
            if (!empty($applied_referal_code)) {
                $calc_amount = $final_amount - $referal_amount;
                $referal_code = 1;
            } else {
                $calc_amount = $final_amount;
                $referal_code = 0;
            }

            $gst_amount = ($gst / 100) * $bag_amount;
            $f_amount = $calc_amount + $gst_amount;
            $output = array(
                'cart_id' => $cart_id,
                'user_id' => $user_id,
                'company_id' => $company_id,
                'branch_id' => $branch,
                'cart_amount' => $original_amount,
                'applied_coupon_amount' => $applied_coupon_amount,
                'applied_coupon' => $coupon_applied,
                'applied_referal_code' => $referal_code,
                'referal_amount' => $referal_amount,
                'coupon_name' => $coupon_name,
                'coupon_id' => $coupon_id,
                'delivery_charge' => $delivery_charge,
                'distance' => $distance,
                'gst_percent' => $gst,
                'gst_amount' => $gst_amount,
                'savings' => $original_amount - $f_amount,
                'products_original_amount' => $final_dealer_price,
                'products_saving' => $final_dealer_price - $original_amount,
                'final_amount' => $f_amount,
                'address' => $address1 . ',' . $address2 . ',' . $city_name . '-' . $pin . ',' . $state_name . ',' . $country_name,
                'distance' => $distance,
                'products' => $products,
            );

            return $output;
        }

    }

    public function delete_cart_item($cart_item_id, $cart_id)
    {
        $this->db->where("bg_id", $cart_item_id);
        $this->db->delete("bag_items");
        $affect = $this->db->affected_rows();
        return $affect;
    }

    public function get_branch_id($user_id, $company_id)
    {
        $this->db->select("*");
        $this->db->from("users as u");
        $this->db->where("u.user_id", $user_id);
        $this->db->join("customer_address as ca", "ca.customer_id=u.user_id");
        $this->db->join("assign_pincode as ap", "ap.pincode=ca.pincode");
        $this->db->join("pincode as p", "p.id=ap.pincode");
        $this->db->where("ap.company_id", $company_id);
        $this->db->order_by("ca.id", "asc");
        $this->db->limit(1);
        $query = $this->db->get();
        $ret = $query->row();
        $branch = $ret->branch_id;
        return $branch;
    }

    public function get_address_id($user_id)
    {
        $this->db->select("ca.id");
        $this->db->from("users as u");
        $this->db->where("u.user_id", $user_id);
        $this->db->join("customer_address as ca", "ca.customer_id=u.user_id");
        $this->db->order_by("ca.id", "asc");
        $this->db->limit(1);
        $query = $this->db->get();
        $ret = $query->row();
        $address = $ret->id;
        return $address;
    }

    public function check_assigned_pincode($address_id, $company_id)
    {
        $this->db->select('*');
        $this->db->from("customer_address as ca");
        $this->db->join("assign_pincode as ap", "ap.pincode=ca.pincode");
        $this->db->where("ca.id", $address_id);
        $this->db->where("ap.company_id", $company_id);
        $query = $this->db->get();
        $ret = $query->row();
        $pin = $ret->pincode;
        return $pin;
    }

    public function cart_count($cart_id)
    {
        $this->db->select("*");
        $this->db->from("bag_items");
        $this->db->where("bag_id", $cart_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function genereate_branch_id($address)
    {
        $this->db->select("*");
        $this->db->from("customer_address");
        $this->db->where('id', $address);
        $query = $this->db->get();
        $ret = $query->row();
        $pincode = $ret->pincode;

        $this->db->select("*");
        $this->db->from("assign_pincode as ap");
        $this->db->where("ap.pincode", $pincode);
        $query1 = $this->db->get();
        $result1 = $query1->result();
        $ret1 = $query1->row();
        return $ret1->branch_id;

    }

    public function get_cart_by_user_id($user_id)
    {
        $this->db->select("*");
        $this->db->from("bag");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get();
        $ret = $query->row();
        return $ret->bag_id;
    }

    public function get_cart_item_stock($cart_item_id)
    {
        $this->db->select("*");
        $this->db->from("bag_items");
        $this->db->where("bg_id", $cart_item_id);
        $query = $this->db->get();
        $ret = $query->row();
        $product_id = $ret->product;
        $uom = $ret->uom;

        $this->db->select("*");
        $this->db->from("products_sku");
        $this->db->where("product_id", $product_id);
        $this->db->where("unit", $uom);
        $query1 = $this->db->get();
        $ret1 = $query1->row();
        $product_sku_id = $ret1->id;
        //return $product_sku_id;
        $this->db->select("*");
        $this->db->from("stocks");
        $this->db->where("pro_id", $product_sku_id);
        $query2 = $this->db->get();
        $ret2 = $query2->row();
        return $ret2->stock;
    }

}