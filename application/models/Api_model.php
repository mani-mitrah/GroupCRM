<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_model extends CI_Model
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

    /**
     * Hash Password
     *
     * Using PHP 5.5's native password hash function,
     * falling back to crypt for older versions of PHP.
     *
     * @param   string  The raw (supplied) password
     * @param   string  The random salt (only used for PHP versions < 5.5)
     * @return  string  the hashed password
     */
    public function hash_passwd($password, $random_salt = '')
    {
        // If no salt provided for older PHP versions, make one
        if (!is_php('5.5') && empty($random_salt)) $random_salt = $this->random_salt();

        // PHP 5.5+ uses new password hashing function
        if (is_php('5.5'))
        {
            return password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        }

        // PHP < 5.5 uses crypt
        else
        {
            return crypt($password, '$2y$10$' . $random_salt);
        }
    }

    /*public function register($user_array)
    {
        # Starting Transaction
        $this->db->trans_start();
        # Strict Transaction
        $this->db->trans_strict(true);

        $user_result = $this->user_registration($user_array);
        error_log("user_result: ".print_r($user_result,true));
        $company_id = $this->company_registration($user_array);
        error_log("company_id: ".$company_id);
        $user_company_array = array(
            'user_id' => $user_result['user_id'],
            'company_id' => $company_id
        );
        $user_company_id = $this->assign_company_user($user_company_array);
        error_log("user_company_id: ".$user_company_id);

        $group_id = $this->create_company_group($company_id);
        # Completing transaction
        $this->db->trans_complete();
        
        
        if ($this->db->trans_status() === false || $user_result['affected_rows']<1 || $company_id<1 || $user_company_id <1 || $group_id <1)
        {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            # Everything is Perfect.
            # Committing data to the database.
            $this->db->trans_commit();
            return true;
        }
    }

    private function user_registration($user_array)
    {
        $unused_id = $this->get_unused_id();
        $insert_array = array(
            'user_id' =>$unused_id,
            'first_name' => $user_array['first_name'],
            'last_name' => $user_array['last_name'],
            'country' => $user_array['country'],
            'country_code' => $user_array['country_code'],
            'mobile' => $user_array['mobile'],
            'otp' => $user_array['otp'],
            'email' => $user_array['email'],
            'auth_level' => $user_array['auth_level'],
            'passwd' => $user_array['password']
        );
        $this->db->insert('users', $insert_array);
        $result = $this->db->affected_rows();
        $result_array = array('affected_rows'=>$result,'user_id'=>$unused_id);
        return $result_array;
    }
    private function company_registration($user_array)
    {

        $insert_array = array('company_name'=>$user_array['company'],
                              'package'  =>$user_array['package'],
                              'industry'  =>$user_array['industry'],
                              'is_active' =>'0',
                              'domain'  => $user_array['domain']
        );
        $this->db->insert('companies', $insert_array);
        $result = $this->db->insert_id();
        return $result;
    }
    private function assign_company_user($user_company_array)
    {
        $this->db->insert('company_users', $user_company_array);
        $result = $this->db->insert_id();
        return $result;
    }*/
    

    

    
    

    

    
}

    