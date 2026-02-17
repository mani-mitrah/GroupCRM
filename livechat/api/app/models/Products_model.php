<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Products_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_products_list($category_id)
    {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where("cat_id", $category_id);
        $this->db->where("is_active", 1);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function get_products($category_id, $company_id)
    {
        $this->db->select("*,p.*");
        $this->db->from('products as p');
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->where("p.cat_id", $category_id);
        $this->db->where("p.company_id", $company_id);
        $this->db->where("p.is_active", 1);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("sk.product_id", $res->product_id);
            $this->db->where("sk.is_active", 1);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();
            $products = array();
            foreach ($result1 as $res1) {
                $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res1->id), 'stock');
                $products[] = array(
                    "product_code" => $res1->id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                    "stocks_left" => $stock,
                );
            }
            if (!empty($result1)) {
                $result_array[] = array(
                    "product_id" => $res->product_id,
                    "company_id" => $res->company_id,
                    "product_name" => $res->product_name,
                    "cover_pic" => $res->photo,
                    "category_id" => $res->cat_id,
                    "products" => $products,
                );
            }

        }
        return $result_array;
    }

    public function get_featured_product($company_id)
    {
        $this->db->select("*,p.*");
        $this->db->from('products as p');
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->where("p.feature_product", 1);
        $this->db->where("p.company_id", $company_id);
        $this->db->where("p.is_active", 1);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("sk.product_id", $res->product_id);
            $this->db->where("sk.is_active", 1);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();

            $products = array();
            foreach ($result1 as $res1) {
                $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res1->id), 'stock');
                $products[] = array(
                    "product_code" => $res1->id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                    "stocks_left" => $stock,
                );
            }
            if (!empty($result1)) {
                $result_array[] = array(
                    "product_id" => $res->product_id,
                    "company_id" => $res->company_id,
                    "product_name" => $res->product_name,
                    "cover_pic" => $res->photo,
                    "category_id" => $res->cat_id,
                    "products" => $products,
                );
            }

        }
        return $result_array;
    }

    public function get_products_by_keyword($keyword, $company_id)
    {

        $this->db->select("*,p.*");
        $this->db->from('products as p');
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->like('p.product_name', $keyword, 'both');
        $this->db->where("p.is_active", 1);
        $this->db->where("p.company_id", $company_id);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("sk.product_id", $res->product_id);
            $this->db->where("sk.is_active", 1);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();

            $products = array();
            foreach ($result1 as $res1) {
                $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res1->id), 'stock');
                $products[] = array(
                    "product_code" => $res1->id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                    "stocks_left" => $stock,
                );
            }
            if (!empty($result1)) {
                $result_array[] = array(
                    "product_id" => $res->product_id,
                    "company_id" => $res->company_id,
                    "product_name" => $res->product_name,
                    "cover_pic" => $res->photo,
                    "category_id" => $res->cat_id,
                    "products" => $products,
                );
            }

        }
        return $result_array;

    }

    public function get_products_by_category_keyword($keyword)
    {
        $this->db->select("*");
        $this->db->from('category as c');
        $this->db->like('c.category_name', $keyword, 'both');
        $this->db->where("c.status", 1);
        $query = $this->db->get();
        $result = $query->result();
        //return $result;
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("p.cat_id", $res->id);
            $this->db->where("sk.is_active", 1);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();
            $products = array();
            foreach ($result1 as $res1) {
                $products[] = array(
                    "product_name" => $res1->product_name,
                    "product_code" => $res1->id,
                    "product_id" => $res1->product_id,
                    "category_id" => $res1->cat_id,
                    "company_id" => $res1->company_id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "cover_pic" => $res1->photo,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                );
            }
            if (!empty($result1)) {
                $result_array[] = array(
                    "category_name" => $res->category_name,
                    "products" => $products,
                );
            }

        }
        return $result_array;
    }

    public function get_category_list($company_id)
    {
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where("company_id", $company_id);
        $this->db->where("status", 1);
        $query = $this->db->get();
        $result = $query->result();

        $this->db->select('*');
        $this->db->from('category');
        $this->db->where("is_default", 1);
        $this->db->where("status", 1);
        $query2 = $this->db->get();
        $result2 = $query2->result();
        $add_attributes = array_merge($result, $result2);
        //return array_unique($add_attributes);
        // return array_unique($add_attributes);
        return array_unique(array_merge($result, $result2), SORT_REGULAR);
    }

    public function get_product_details($product_code)
    {
        $this->db->select("*,sk.*,p.*");
        $this->db->from('products_sku as sk');
        $this->db->join("products as p", "p.product_id=sk.product_id");
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->join("set_uom as u", "u.u_id=sk.unit");
        $this->db->where("sk.id", $product_code);
        $this->db->where("sk.is_active", 1);
        $this->db->where("p.is_active", 1);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("sk.is_active", 1);
            $this->db->where("p.product_id", $res->product_id);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();

            $products = array();
            foreach ($result1 as $res1) {
                $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res1->id), 'stock');
                $products[] = array(
                    "product_code" => $res1->id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                    "stocks_left" => $stock,
                );
            }
            $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res->id), 'stock');
            $result_array[] = array(
                "id" => $res->id,
                "product_id" => $res->product_id,
                "company_id" => $res->company_id,
                "unit" => $res->unit,
                "selling_price" => $res->selling_price,
                "dealer_price" => $res->dealer_price,
                "cat_id" => $res->cat_id,
                "product_name" => $res->product_name,
                "product_desc" => str_replace(PHP_EOL, '', strip_tags($res->product_desc)),
                "feature_product" => $res->feature_product,
                "p_created_date" => $res->p_created_date,
                "photo" => $res->photo,
                "u_id" => $res->u_id,
                "uom" => $res->uom,
                "stocks_left" => $stock,
                'sku' => $products,
            );
        }
        return $result_array;
    }

    public function most_sold_roducts($company_id)
    {
        $this->db->select("ot.ori_product,COUNT(ot.ori_product) as count_id");
        $this->db->from("order_items as ot");
        $this->db->group_by("ot.ori_product");
        // $this->db->join("products as p", "p.product_id=ot.ori_product");
        // $this->db->join("set_uom as u", "u.u_id=ot.ori_uom");
        $this->db->order_by("count_id", "desc");
        $this->db->limit(10);
        $query = $this->db->get();
        $product_result = $query->result();
        $products = array();
        foreach ($product_result as $pr) {
            $products[] = $pr->ori_product;
        }

        $this->db->select("*,p.*");
        $this->db->from('products as p');
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->where_in('p.product_id', $products);
        $this->db->where("company_id", $company_id);
        $this->db->where("p.is_active", 1);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("sk.product_id", $res->product_id);
            $this->db->where("sk.is_active", 1);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();

            $products = array();
            foreach ($result1 as $res1) {
                $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res1->id), 'stock');
                $products[] = array(
                    "product_code" => $res1->id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                    "stocks_left" => $stock,
                );
            }
            if (!empty($result1)) {
                $result_array[] = array(
                    "product_id" => $res->product_id,
                    "company_id" => $res->company_id,
                    "product_name" => $res->product_name,
                    "cover_pic" => $res->photo,
                    "category_id" => $res->cat_id,
                    "products" => $products,
                );
            }

        }
        return $result_array;

    }

    public function recent_products($company_id)
    {
        $this->db->select("*,p.*");
        $this->db->from('products as p');
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->order_by('p.product_id', "desc");
        $this->db->where("p.is_active", 1);
        $this->db->where("company_id", $company_id);
        $this->db->limit(10);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("sk.product_id", $res->product_id);
            $this->db->where("p.is_active", 1);
            $this->db->where("sk.is_active", 1);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();

            $products = array();
            foreach ($result1 as $res1) {
                $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res1->id), 'stock');
                $products[] = array(
                    "product_code" => $res1->id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                    "stocks_left" => $stock,
                );
            }
            if (!empty($result1)) {
                $result_array[] = array(
                    "product_id" => $res->product_id,
                    "company_id" => $res->company_id,
                    "product_name" => $res->product_name,
                    "cover_pic" => $res->photo,
                    "category_id" => $res->cat_id,
                    "products" => $products,
                );
            }

        }
        return $result_array;
    }

    public function all_products($company_id)
    {
        $this->db->select("*,p.*");
        $this->db->from('products as p');
        $this->db->join("product_image as pi", "pi.product_id=p.product_id", "left");
        $this->db->where("p.company_id", $company_id);
        $this->db->where("p.is_active", 1);
        $query = $this->db->get();
        $result = $query->result();
        $result_array = array();
        foreach ($result as $res) {
            $this->db->select("*");
            $this->db->from('products as p');
            $this->db->join("products_sku as sk", "sk.product_id=p.product_id");
            $this->db->where("sk.product_id", $res->product_id);
            $this->db->where("sk.is_active", 1);
            $this->db->join("set_uom as u", "u.u_id=sk.unit");
            $query1 = $this->db->get();
            $result1 = $query1->result();

            $products = array();
            foreach ($result1 as $res1) {
                $stock = $this->mcommon->specific_row_value('stocks', array('pro_id' => $res1->id), 'stock');
                $products[] = array(
                    "product_code" => $res1->id,
                    "uom_id" => $res1->unit,
                    "uom" => $res1->uom,
                    "dealer_price" => $res1->dealer_price,
                    "selling_price" => $res1->selling_price,
                    "stocks_left"=>$stock
                );
            }
            if (!empty($result1)) {
                $result_array[] = array(
                    "product_id" => $res->product_id,
                    "company_id" => $res->company_id,
                    "product_name" => $res->product_name,
                    "cover_pic" => $res->photo,
                    "category_id" => $res->cat_id,
                    "products" => $products,
                );
            }

        }
        return $result_array;
    }

}