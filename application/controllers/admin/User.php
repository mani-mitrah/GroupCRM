<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$view_data['page_title'] = 'Users';
			$view_data['users'] = $this->mm->get_users();
			$data = array(
				'title' => 'Manage Users',
				'content' => $this->load->view('admin/user/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function create()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$view_data['default'] = $this->mm->users();
			$view_data['page_title'] = 'Users';
			$data = array(
				'title' => 'New User',
				'content' => $this->load->view('admin/user/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}
	public function add()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			if (isset($_POST['submit'])) {
				//Receive Values
				$role_id = $this->input->post('role_id');
				$first_name = $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$country = $this->input->post('country');
				$country_code = $this->input->post('country_code');
				$mobile = $this->input->post('mobile');
				$username = $this->input->post('username');
				$email = $this->input->post('email');
				$employee_id = $this->input->post('employee_id');
				$pos_user_id = $this->input->post('pos_user_id');
				$language = $this->input->post('language');
				$passwd = $this->input->post('passwd');
				$c_password = $this->input->post('c_password');
				$company_id = $this->input->post('company');
				$paylink_option = $this->input->post('paylink_option');
				$is_sales = $this->input->post('is_sales');

				if ($passwd !=  $c_password) {
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
				// $this->form_validation->set_rules('country', 'Country', 'required');
				$this->form_validation->set_rules('country_code', 'Country Code', 'required');
				$this->form_validation->set_rules('mobile', 'Mobile', 'required');
				//$this->form_validation->set_rules('username', 'Username', 'required');
				$this->form_validation->set_rules('email', 'Email', 'required');
				$this->form_validation->set_rules('passwd', 'Password', 'required');
				$this->form_validation->set_rules('language', 'Language', 'required');
				$this->form_validation->set_rules('primary_group', 'Primary Group', 'required');

				//check is the validation returns no error
				if ($this->form_validation->run() == TRUE) {
					//prepare insert array
					$user_id = $this->get_unused_id();
					$insert_array = array(
						'user_id' => $user_id,
						'role_id' => ($role_id == 1) ? 4 : $role_id,
						'first_name' => $first_name,
						'last_name' => $last_name,
						'auth_level' => 1,
						'country' => isset($country) ? $country : "United Arab Emirates",
						'country_code' => $country_code,
						'mobile' => $mobile,
						//'username'=>$username,
						'email' => $email,
						'employee_id' => $employee_id,
						'pos_user_id' => $pos_user_id,
						'language' => $language,
						'passwd' => $this->hash_passwd($passwd),
						//'c_password'=>$c_password,
						'created_at' => date("Y-m-d h:i:s"),
						'paylink_option' => $paylink_option,
						'is_sales' => $is_sales,
					);
					//insert values in database
					$user = $this->db->insert('users', $insert_array);
					log_message('error', $this->db->last_query());

					if ($user > 0) {
						$groups = $this->input->post("groups");
						$cal_grp = $this ->input->post('users_calendar_group_accessables');
						$users_group_accessables = $this->input->post("users_group_accessables");
						$users_group_report = $this->input->post("users_group_report");
						$cal_br = $this->input->post('users_calendar_branch_accessables');
						$primary_group = $this->input->post("primary_group");

						if (count($groups) > 0) $this->db->delete("group_members", array("user_id" => $user_id));
						foreach ($groups as $group) {
							$group = array(
								"group_id" => $group,
								"user_id" => $user_id,
								"created_by" => $_SESSION["user_id"],
								"is_primary_group_id" => ($primary_group == $group) ? 1 : 0
							);
							$id = $this->db->insert('group_members', $group);
						}

						if (count($users_group_accessables) > 0) $this->db->delete("users_group_accessables", array("ug_user_id" => $user_id));
						foreach ($users_group_accessables as $group) {
							$group = array(
								"ug_group_id" => $group,
								"ug_user_id" => $user_id,
								"ug_created_by" => $_SESSION["user_id"]
							);
							$u_id = $this->db->insert('users_group_accessables', $group);
						}

						if (count($users_group_report) > 0) $this->db->delete("users_group_report", array("ug_user_id" => $user_id));
						foreach ($users_group_report as $group) {
							$group = array(
								"ug_group_id" => $group,
								"ug_user_id" => $user_id,
								"ug_created_by" => $_SESSION["user_id"]
							);
							$ug_id = $this->db->insert('users_group_report', $group);
						}

						if (count($cal_grp) > 0) $this->db->delete("calendar_members", array("user_id" => $user_id));
						foreach ($cal_grp as $group) {
							$group = array(
								"calendar_id" => $group,
								"user_id" => $user_id,
								"calendar_branch" => $cal_br, 
								"created_by" => $_SESSION["user_id"]
							);
							$id = $this->db->insert('calendar_members', $group);
						}

						$receiver_email = trim($email);
						$customer_name = $first_name;
						$sender_name = "OnTime Group CRM";
			
						$subject = "Welcome to OnTime Group CRM";
						$message = 'Dear '. $customer_name.'<br></br>
							Your username: ' . $receiver_email . '<br>
							Your Password: Welcome@123<br>
							login Link: <a href="'.getenv('CRM_URL').'login">'.getenv('CRM_URL').'login</a><br><br>
							Please use the above link to log in and reset your password to continue using the CRM.<br>';

						$email_array = array(
							'email' => $receiver_email,
							'subject' => $subject,
							'template' => 'mails/template',
							'from_name' => $sender_name,
							'message' => $message,
						);
						$send_mail = send_template_email($email_array);
						log_message('error', $send_mail);
						
						// insert into company user table
						// $cu_insert_array = $this->mcommon->common_insert('company_users',array('user_id'=>$user_id,'company_id'=>$company_id));
						$this->session->set_flashdata('alert', 'success');
						$this->session->set_flashdata('alert_message', 'User added successfully!');
						redirect('admin/user');
					} else {
						// print_r($insert_array);
						// exit();
						// log_message('error',print_r($insert_array));
						$this->session->set_flashdata('alert', 'danger');
						$this->session->set_flashdata('alert_message', 'Something went wrong. Please try again later');
					}
				} else {

					$this->session->set_flashdata('alert', 'danger');
					$this->session->set_flashdata('alert_message', validation_errors());
					print_r(validation_errors());
					exit();
				}
			}
			$view_data['role'] = $this->mm->crm_roles();
			$view_data['companies'] = $this->mcommon->specific_fields_records_all('companies');
			$view_data['page_title'] = 'Add Users';
			$view_data['groups'] = $this->mm->get_groups();
			$view_data['calendar'] = $this->mm->get_calendar_groups();
			$view_data['calendar_branch'] = $this->mm->get_calendar_branches(); 
			$view_data['access_groups'] = $view_data['report_groups'] = $this->mm->get_groups();
			// $view_data['groups'] = $this->mcommon->specific_fields_records_all("groups", ["status" => 1], ["group_id", "group_name"]);
			$data = array(
				'title' => 'Users',
				'content' => $this->load->view('admin/user/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function edit($id)
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();


			if (isset($_POST['submit'])) {
				//Receive Values
				$role_id = $this->input->post('role_id');
				$first_name = $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$country = $this->input->post('country');
				$country_code = $this->input->post('country_code');
				$mobile = $this->input->post('mobile');
				$username = $this->input->post('username');
				$email = $this->input->post('email');
				$employee_id = $this->input->post('employee_id');
				$pos_user_id = $this->input->post('pos_user_id');
				$language = $this->input->post('language');
				$passwd = $this->input->post('passwd');
				$c_password = $this->input->post('c_password');
				$is_active = $this->input->post('is_active');
				$is_sales = $this->input->post('is_sales');
				$paylink_option = $this->input->post('paylink_option');

				if (isset($passwd)) {
					if ($passwd != NULL) {

						if ($passwd !=  $c_password) {
							$validation_rules[] = array(
								'field' => 'password',
								'label' => 'password',
								'rules' => [
									'trim',
									'required',
									'matches[password]',
								],
								'errors' => [
									'required' => 'The password field is required.',

								],
							);
							$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]');
						}
					}
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
				$this->form_validation->set_rules('primary_group', 'Primary Group', 'required');

				//check is the validation returns no error
				if ($this->form_validation->run() == TRUE) {
					//prepare insert array
					$update_array = array(
						'user_id' => $id,
						'role_id' => ($role_id == 1) ? 4 : $role_id,
						'first_name' => $first_name,
						'last_name' => $last_name,
						'auth_level' => 1,
						'country' => $country,
						'country_code' => $country_code,
						'mobile' => $mobile,
						'username' => $username,
						'email' => $email,
						'employee_id' => $employee_id,
						'pos_user_id' => $pos_user_id,
						'language' => $language,
						//'c_password'=>$c_password,
						'created_at' => date("Y-m-d h:i:s"),
						'updated_by' => $this->auth_user_id,
						'is_active' => $is_active,
						'banned' => ($is_active == 0) ? '1' : '0',
						'paylink_option' => $paylink_option,
						'is_sales' => $is_sales,
					);
				
					if (isset($passwd)) {
						if ($passwd != NULL) $update_array['passwd'] = $this->hash_passwd($passwd);
					}
					//insert values in database
					$update = $this->mcommon->common_edit('users', $update_array, array('user_id' => $id));
	
					if ($update > 0) {
						$groups = $this->input->post("groups");
						$cal_grp = $this ->input->post('users_calendar_group_accessables');
						$users_group_accessables = $this->input->post("users_group_accessables");
						$users_group_report = $this->input->post("users_group_report");
						$cal_br = $this->input->post('users_calendar_branch_accessables');
						$primary_group = $this->input->post("primary_group");
						// print_r($groups);
						// exit();
						if (count($groups) > 0) $this->db->delete("group_members", array("user_id" => $id));
						foreach ($groups as $group) {
							$group = array(
								"group_id" => $group,
								"user_id" => $id,
								"created_by" => $_SESSION["user_id"],
								"is_primary_group_id" => ($primary_group == $group) ? 1 : 0
							);
							$u_id = $this->db->insert('group_members', $group);
						}

						$this->db->delete("users_group_accessables", array("ug_user_id" => $id));
						if (count($users_group_accessables) > 0) {
							foreach ($users_group_accessables as $group) {
								$group = array(
									"ug_group_id" => $group,
									"ug_user_id" => $id,
									"ug_created_by" => $_SESSION["user_id"]
								);
								$u_id = $this->db->insert('users_group_accessables', $group);
							}
						}

						$this->db->delete("users_group_report", array("ug_user_id" => $id));
						if (count($users_group_report) > 0) {
							foreach ($users_group_report as $group) {
								$group = array(
									"ug_group_id" => $group,
									"ug_user_id" => $id,
									"ug_created_by" => $_SESSION["user_id"]
								);
								$ug_id = $this->db->insert('users_group_report', $group);
							}
						}


						if (count($cal_grp) > 0) $this->db->delete("calendar_members", array("user_id" => $id));
						foreach ($cal_grp as $group) {
							$group = array(
								"calendar_id" => $group,
								"user_id" => $id,
								"calendar_branch" => $cal_br,
								"created_by" => $_SESSION["user_id"]
							);
							$u_id = $this->db->insert('calendar_members', $group);
						}


						$this->session->set_flashdata('alert_success', 'Users updated successfully!');
						redirect('admin/user');
					} else {
						$this->session->set_flashdata('alert_danger', 'Something went wrong. Please try again later');
					}
				}
			}
			$view_data['default'] = $this->mcommon->specific_row('users', array('user_id' => $id));
			$view_data['role'] = $this->mm->role_name_active();
			$view_data['companies'] = $this->mcommon->specific_fields_records_all('companies');
			$view_data['page_title'] = 'Edit Users';
			$view_data['groups'] = $this->mm->get_user_groups($id);
			$view_data['calendar'] = $this->mm->get_calendar_data($id);
			$view_data['calendar_branch'] = $this->mm->get_calendar_branch($id); 
			$view_data['access_groups'] = $this->mm->get_accessable_groups($id);
			$view_data['report_groups'] = $this->mm->get_report_groups($id);
			$view_data['primary_group'] = $this->mm->get_user_primary_group($id);
			// print_r($view_data['groups']);
			// exit();
			$data = array(
				'title' => 'Users',
				'content' => $this->load->view('admin/user/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}
	public function status_change($id)
	{

		$current_status = $this->mcommon->specific_row_value('users', array('user_id' => $id), 'is_active');
		$change_status = ($current_status == 1) ? 0 : 1;
		$delete = $this->mcommon->common_edit('users', array('is_active' => $change_status), array('user_id' => $id));

		redirect('admin/user/create');
	}
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

		$salt = substr(bin2hex($this->CI->encryption->create_key(64)), 0, 22);

		return strlen($salt) != 22
			? substr(md5(mt_rand()), 0, 22)
			: $salt;
	}

	public function checkemail()
	{
		$email = $this->input->get('email');
		$check_email = $this->mcommon->specific_record_counts('users', array('email' => $email));
		echo json_encode($check_email);
	}

	public function change_user_availability()
	{
		$is_available = $this->input->post('is_available');
		$id = $this->auth_user_id;
		$delete = $this->mcommon->common_edit('users', array('is_available' => $is_available), array('user_id' => $id));
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('status' => 'success', 'message' => 'User availability updated successfully.')));
	}
}