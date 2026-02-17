<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Group extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->library('form_validation');
		$this->load->library('session');
	}

	public function index()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$view_data['page_title'] = 'Groups';
			$view_data['users'] = $this->mm->get_groups();
			// print_r($view_data);
			// exit();
			$data = array(
				'title' => 'Manage Groups',
				'content' => $this->load->view('admin/group/show', $view_data, TRUE),
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
			$view_data['page_title'] = 'Groups';
			$data = array(
				'title' => 'New Group',
				'content' => $this->load->view('admin/group/add', $view_data, TRUE),
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
				try {

					$group_name = $this->input->post('group_name');
					$group_desc = $this->input->post('group_desc');
					$group_cats = $this->input->post("group_categories[]");
					$group_webs = $this->input->post("group_websites[]");
					$group_branch = $this->input->post("group_branch_id");
					$alpha_pro_invoice = $this->input->post('alpha_pro_invoice');

					$exist = $this->db->select("*")->from("groups")->where("group_name", $group_name)->get()->num_rows();
					// print_r($exist);
					// exit();
					if ($exist > 0) {
						$this->session->set_flashdata('alert', array('message' => "Group already exist", 'class' => 'danger'));
						redirect($_SERVER['HTTP_REFERER']);
					}

					//prepare insert array
					$insert_array = array(
						'group_name' => $group_name,
						'group_desc' => $group_desc,
						'created_by' => $_SESSION["user_id"],
						'group_branch_id' => $group_branch,
						'alpha_pro_invoice' => $alpha_pro_invoice,
					);

					$data = $this->db->insert('groups', $insert_array);
					$data_id = $this->db->insert_id();
					// print_r();
					// print_r($data_id);
					// print_r($this->input->post("group_categories[]"));
					// exit();
					// $bulk_gc = [];
					foreach ($group_cats as $gc) {
						$this->db->insert('group_categories', array("group_id" => $data_id, "category_id" => $gc, "created_by" => $this->auth_user_id));
					}
					foreach ($group_webs as $gw) {
						$gw = $this->db->insert('group_websites', array("group_id" => $data_id, "website_id" => $gw, "created_by" => $this->auth_user_id));
					}
					// exit();
					log_message('error', $this->db->last_query());
					if ($data > 0 && $data != NULL) {
						$this->session->set_flashdata('alert', array('message' => 'Group added successfully', 'class' => 'success'));
						redirect('admin/group');
					} else {
						$this->session->set_flashdata('alert', array('message' => "<b>" . $group_name . '</b> - is already exists or Contact Support.', 'class' => 'danger'));
						redirect('admin/group');
					}
				} catch (Exception $e) {
					// return view("")
					show_error($e);
					log_message('error: ', $e);
					$this->session->set_flashdata('alert', array('message' => "Something went wrong. Cannot create group at this moment.", 'class' => 'danger'));
					redirect('admin/group/add');
				}
			}
			$view_data['page_title'] = 'Add Group';
			$view_data['categories'] = $this->mm->lead_categories();
			$view_data['websites'] = $this->mm->websites();
			$view_data['branches'] = $this->db->select("*")->from("ontime_branches")->where("is_active", 1)->get()->result_array();


			$data = array(
				'title' => 'Add Group',
				'content' => $this->load->view('admin/group/add', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	// public function edit($id)
	// {
	// 	if ($this->verify_min_level(1)) {
	// 		$view_data = array();


	// 		if (isset($_POST['submit'])) {
	// 			//Receive Values
	// 			$group_name = $this->input->post('group_name');
	// 			$group_desc = $this->input->post('group_desc');
	// 			$group_cats = $this->input->post("group_categories[]");
	// 			$group_webs = $this->input->post("group_websites[]");
	// 			$group_branch = $this->input->post("group_branch_id");
	// 			// $exist = $this->db->select("*")->from("groups")->where("group_name", $group_name)->get()->num_rows();
	// 			// print_r($exist);
	// 			// exit();

	// 			$exist = $this->db
    //             ->where('group_name', $group_name)
    //             ->where('group_id !=', $id) // Exclude the current group by ID
    //             ->from('groups')
    //             ->count_all_results();
	// 			if ($exist > 0) {
	// 				$this->session->set_flashdata('alert', array('message' => "Group already exist", 'class' => 'danger'));
	// 				redirect($_SERVER['HTTP_REFERER']);
	// 			}

	// 			//prepare insert array
	// 			$update_array = array(
	// 				'group_name' => $group_name,
	// 				'group_desc' => $group_desc,
	// 				'created_by' => $_SESSION["user_id"],
	// 				'group_branch_id' => $group_branch
	// 			);
	// 			//insert values in database
	// 			$update = $this->mcommon->common_edit('groups', $update_array, array('group_id' => $id));

	// 			// $bulk_gc = [];
	// 			$this->db->delete('group_categories', array("group_id" => $id));
	// 			$this->db->delete('group_websites', array("group_id" => $id));
	// 			$gc_success = true;

	// 			foreach ($group_cats as $gc) {
	// 				$gc = $this->db->insert('group_categories', array("group_id" => $id, "category_id" => $gc, "created_by" => $this->auth_user_id));
	// 			}
	// 			foreach ($group_webs as $gw) {
	// 				$gw = $this->db->insert('group_websites', array("group_id" => $id, "website_id" => $gw, "created_by" => $this->auth_user_id));
	// 			}

	// 			if ($update > 0 && $gc != 0 && $gw != 0) {
	// 				$this->session->set_flashdata('alert', array('message' => 'Group successfully updated!', 'class' => 'success'));
	// 				return redirect('admin/group');
	// 			} else {
	// 				$this->session->set_flashdata('alert', array('message' => 'Something went wrong. Please try again later', 'class' => 'danger'));
	// 			}
	// 		}
	// 		// echo $id;
	// 		// exit();
	// 		$view_data['default'] = $this->mcommon->specific_row('groups', array('group_id' => $id));
	// 		$view_data['categories'] = $this->mm->lead_categories($id);
	// 		$view_data['page_title'] = 'Edit Group';
	// 		$view_data['websites'] = $this->mm->websites($id);
	// 		$view_data['branches'] = $this->db->select("*")->from("ontime_branches")->where("is_active", 1)->get()->result_array();

	// 		$data = array(
	// 			'title' => 'Users',
	// 			'content' => $this->load->view('admin/group/edit', $view_data, TRUE),
	// 		);
	// 		$this->load->view('template/base_template', $data);
	// 	} else {
	// 		redirect('login');
	// 	}
	// }
	public function edit($id)
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();

			if (isset($_POST['submit'])) {
				// Receive Values
				$group_name  = $this->input->post('group_name');
				$group_desc  = $this->input->post('group_desc');
				$group_cats  = $this->input->post("group_categories[]");
				$group_webs  = $this->input->post("group_websites[]");
				$group_branch = $this->input->post("group_branch_id");
				$alpha_pro_invoice = $this->input->post('alpha_pro_invoice');

				// Check for duplicate group name excluding the current group
				$exist = $this->db
					->where('group_name', $group_name)
					->where('group_id !=', $id) // Exclude the current group by ID
					->from('groups')
					->count_all_results();

				if ($exist > 0) {
					$this->session->set_flashdata('alert', array(
						'message' => "Group name already exists",
						'class' => 'danger'
					));
					redirect($_SERVER['HTTP_REFERER']);
				}

				// Prepare update array
				$update_array = array(
					'group_name'      => $group_name,
					'group_desc'      => $group_desc,
					'created_by'      => $_SESSION["user_id"],
					'group_branch_id' => $group_branch,
					'alpha_pro_invoice' => $alpha_pro_invoice,
				);

				// Perform the update
				$update = $this->mcommon->common_edit('groups', $update_array, array('group_id' => $id));

				// Delete old categories and websites
				$this->db->delete('group_categories', array("group_id" => $id));
				$this->db->delete('group_websites', array("group_id" => $id));

				// Insert updated categories
				$gc_success = true;
				foreach ($group_cats as $gc) {
					$insert_gc = $this->db->insert('group_categories', array(
						"group_id"    => $id,
						"category_id" => $gc,
						"created_by"  => $this->auth_user_id
					));
					if (!$insert_gc) {
						$gc_success = false;
					}
				}

				// Insert updated websites
				$gw_success = true;
				foreach ($group_webs as $gw) {
					$insert_gw = $this->db->insert('group_websites', array(
						"group_id"    => $id,
						"website_id"  => $gw,
						"created_by"  => $this->auth_user_id
					));
					if (!$insert_gw) {
						$gw_success = false;
					}
				}

				// Check for success
				if ($update && $gc_success && $gw_success) {
					$this->session->set_flashdata('alert', array(
						'message' => 'Group successfully updated!',
						'class' => 'success'
					));
					return redirect('admin/group');
				} else {
					$this->session->set_flashdata('alert', array(
						'message' => 'Something went wrong. Please try again later',
						'class' => 'danger'
					));
				}
			}

			// Load view data
			$view_data['default']    = $this->mcommon->specific_row('groups', array('group_id' => $id));
			$view_data['categories'] = $this->mm->lead_categories($id);
			$view_data['page_title'] = 'Edit Group';
			$view_data['websites']   = $this->mm->websites($id);
			$view_data['branches']   = $this->db->select("*")
											->from("ontime_branches")
											->where("is_active", 1)
											->get()
											->result_array();
		// Load view template
			$data = array(
				'title'   => 'Users',
				'content' => $this->load->view('admin/group/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}
	public function group_members()
	{
		$g_id = $_GET["group_id"];
		// echo $g_id;
		if ($g_id == "") {
			$this->db->select("concat(users.first_name,' ',users.last_name,' ( ',m_roles.role_name,' )') as text,users.user_id as id");
			$this->db->from("group_members");
			$this->db->join("users", "users.id=group_members.user_id");
			$this->db->where("users.is_active", 1);
			$this->db->where("group_members.status", 1);
			$res = $this->db->get();
			// print_r($this->db->last_query());
			// return json_encode($res->result());
			echo json_encode($res->result_array());
		} else {
			$this->db->select("concat(users.first_name,' ',users.last_name,' ( ',m_roles.role_name,' )') as text,users.user_id as id");
			$this->db->from("group_members");
			$this->db->join("users", "users.user_id=group_members.user_id");
			$this->db->join("m_roles", "m_roles.role_id=users.role_id");
			$this->db->where("group_members.status", 1);
			$this->db->where("users.is_active", 1);
			$this->db->where("group_members.group_id", $g_id);
			$res = $this->db->get();
			// print_r($res->result_array());0
			// print_r($res->result_array());
			// print_r($this->db->last_query());
			echo json_encode($res->result_array());
		}
	}

	public function status_change($id)
	{
		$current_status = $this->mcommon->specific_row_value('groups', array('group_id' => $id), 'status');
		$change_status = ($current_status == 1) ? 0 : 1;
		$delete = $this->mcommon->common_edit('groups', array('status' => $change_status), array('group_id' => $id));
		$this->session->set_flashdata('alert', array('message' => 'Success! Status Successfully Updated.', 'class' => 'success'));
		redirect('admin/group');
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
}
