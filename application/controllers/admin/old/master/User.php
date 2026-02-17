<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();
			$view_data['page_title'] = 'Users';
			$view_data['users'] = $this->mm->get_users();
			$data = array(
				'title' => 'Manage Users',
				'content' => $this->load->view('admin/user/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function create()
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();
			$view_data['default'] = $this->mm->users();
			$view_data['page_title'] = 'Users';
			$data = array(
				'title' => 'New User',
				'content' => $this->load->view('admin/user/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
	public function add()
	{
		if( $this->verify_min_level(1))
		{
		    $view_data = array();
			if(isset($_POST['submit']))
			{
				//Receive Values
				$role_id = $this->input->post('role_id');
				$first_name = $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$country = $this->input->post('country');
				$country_code = $this->input->post('country_code');
				$mobile = $this->input->post('mobile');
				$username = $this->input->post('username');
				$email = $this->input->post('email');
				$language = $this->input->post('language');
				$passwd = $this->input->post('passwd');
				$c_password = $this->input->post('c_password');
				$company_id = $this->input->post('company');

				if ($passwd !=  $c_password ) {
	                $validation_rules[] = array(
	                    'field' => 'password',
	                    'label' => 'password',
	                    'rules' => [
	                        'trim',
	                        'required',
	                        'matches[password]',
	                        'rules' => 'callback_valid_password',
	                    ],
	                    'errors' => [
	                        'required' => 'The password field is required.',

	                    ],
	                );
	                $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]');
	            }
				
				//Set validation Rules 
				$this->form_validation->set_rules('role_id', 'Role Name', 'required');
				$this->form_validation->set_rules('first_name', 'First Name', 'required');
				$this->form_validation->set_rules('last_name', 'Last Name', 'required');
				$this->form_validation->set_rules('country', 'Country', 'required');
				$this->form_validation->set_rules('country_code', 'Country Code', 'required');
				$this->form_validation->set_rules('mobile', 'Mobile', 'required');
				$this->form_validation->set_rules('username', 'Username', 'required');
				$this->form_validation->set_rules('email', 'Email', 'required');
				$this->form_validation->set_rules('passwd', 'Password', 'required');
				$this->form_validation->set_rules('language', 'Language', 'required');

				//check is the validation returns no error
	            if ($this->form_validation->run() == TRUE)
	            {

	            	//prepare insert array
	            	$user_id=$this->get_unused_id();
	            	$insert_array = array(
	            		'user_id' => $user_id,
	            		'role_id'=>($role_id == 1) ? 4 : $role_id,
	            		'first_name'=>$first_name,
	            		'last_name'=>$last_name,
	            		'auth_level' => 1,
	            		'country'=>$country,
	            		'country_code'=>$country_code,
	            		'mobile'=>$mobile,
	            		'username'=>$username,
	            		'email'=>$email,
	            		'language'=>$language,
	            		'passwd' => $this->hash_passwd($passwd),
	            		//'c_password'=>$c_password,
	            		'created_at' => date("Y-m-d h:i:s"),
	            	);
	            	//insert values in database
	            	$this->db->insert('users', $insert_array);
	                 $insert = $user_id;
	            	if($insert > '0')
	            	{
	            		//insert into company user table
	            		//$cu_insert_array = $this->mcommon->common_insert('company_users',array('user_id'=>$user_id,'company_id'=>$company_id));
	            		$this->session->set_flashdata('alert_success', 'User added successfully!');
	            		redirect('admin/master/user/add');
	            	}
	            	else
	            	{
	            		$this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
	            	}
	            }
	        }
	        $view_data['role'] = $this->mm->crm_roles();
	        $view_data['companies'] = $this->mcommon->specific_fields_records_all('companies');
	        $view_data['page_title'] = 'Add Users';
			$data = array(
				'title' => 'Users',
				'content' => $this->load->view('admin/user/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}

	public function edit($id)
	{
		if( $this->verify_min_level(1))
		{
			$view_data = array();


		if(isset($_POST['submit']))
		{
			//Receive Values
			$role_id = $this->input->post('role_id');
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			$country = $this->input->post('country');
			$country_code = $this->input->post('country_code');
			$mobile = $this->input->post('mobile');
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$language = $this->input->post('language');
			$passwd = $this->input->post('passwd');
			$c_password = $this->input->post('c_password');
			$is_active = $this->input->post('is_active');

			if ($passwd !=  $c_password ) {
                $validation_rules[] = array(
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => [
                        'trim',
                        'required',
                        'matches[password]',
                        'rules' => 'callback_valid_password',
                    ],
                    'errors' => [
                        'required' => 'The password field is required.',

                    ],
                );
                $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]');
            }

			//Set validation Rules 
			$this->form_validation->set_rules('role_id', 'Role Name', 'required');
			$this->form_validation->set_rules('first_name', 'First Name', 'required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('country_code', 'Country Code', 'required');
			$this->form_validation->set_rules('mobile', 'Mobile', 'required');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');
			$this->form_validation->set_rules('language', 'Language', 'required');

			//check is the validation returns no error
            if ($this->form_validation->run() == TRUE)
            {
            	//prepare insert array
            	$update_array = array(
            		'user_id' => $id,
            		'role_id'=>($role_id == 1) ? 4 : $role_id,
            		'first_name'=>$first_name,
            		'last_name'=>$last_name,
            		'auth_level' => 1,
            		'country'=>$country,
            		'country_code'=>$country_code,
            		'mobile'=>$mobile,
            		'username'=>$username,
            		'email'=>$email,
            		'language'=>$language,
            		'passwd' => $this->hash_passwd($passwd),
            		//'c_password'=>$c_password,
            		'created_at' => date("Y-m-d h:i:s"),
            		'is_active'=>$is_active,
            		);
            	//insert values in database
            	$update = $this->mcommon->common_edit('users',$update_array,array('user_id'=>$id));
            	if($update > '0')
            	{
            		$this->session->set_flashdata('alert_success', 'Users updated successfully!');
            		redirect('admin/master/user/create');
            	}
            	else
            	{
            		$this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
            	}
            }
        }
            $view_data['default']=$this->mcommon->specific_row('users',array('user_id'=>$id));
            $view_data['role'] = $this->mm->role_name_active();
            $view_data['companies'] = $this->mcommon->specific_fields_records_all('companies');
            $view_data['page_title'] = 'Edit Users';
			$data = array(
				'title' => 'Users',
				'content' => $this->load->view('admin/user/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
			
		}
		else
		{
			redirect('login');
		}
	}
	public function status_change($id)
	{

		$current_status = $this->mcommon->specific_row_value('users',array('user_id'=>$id),'is_active');
		$change_status = ($current_status==1)?0:1;
		$delete = $this->mcommon->common_edit('users',array('is_active'=>$change_status),array('user_id'=>$id));

		redirect('admin/master/user/create');
	}
	 public function get_unused_id()
    {
        // Create a random user id between 1200 and 4294967295
        $random_unique_int = 2147483648 + mt_rand(-2147482448, 2147483647);

        // Make sure the random user_id isn't already in use
        $query = $this->db->where('user_id', $random_unique_int)
            ->get_where('users');

        if ($query->num_rows() > 0) {
            $query->free_result();

            // If the random user_id is already in use, try again
            return $this->get_unused_id();
        }

        return $random_unique_int;
    }
    public function hash_passwd($password, $random_salt = '')
    {
        // If no salt provided for older PHP versions, make one
        if (!is_php('5.5') && empty($random_salt)) {
            $random_salt = $this->random_salt();
        }

        // PHP 5.5+ uses new password hashing function
        if (is_php('5.5')) {
            return password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        }

        // PHP < 5.5 uses crypt
        else {
            return crypt($password, '$2y$10$' . $random_salt);
        }
    }
     public function random_salt()
	{
		$this->CI->load->library('encryption');

		$salt = substr( bin2hex( $this->CI->encryption->create_key(64) ), 0, 22 );

		return strlen( $salt ) != 22 
			? substr( md5( mt_rand() ), 0, 22 )
			: $salt;
    }

}