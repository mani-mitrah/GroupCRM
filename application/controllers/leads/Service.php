<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('datatables');
		$this->load->helper('crypt');
		$this->load->model('app_model');
		$this->load->model('access_model');
		$this->load->model('user_model');
		$this->load->model('master_model');
		$this->load->model('leads_model');
		$this->load->model('authentication_model');
		// $this->load->library('encrypt');
	}

	public function index()
	{
		if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
			$view_data['services'] = $this->db->select("ontime_services.*, groups.group_name")->from("ontime_services")->join("groups", "groups.group_id = ontime_services.service_group", "left")->where("ontime_services.service_branch", 106)->get()->result_array();
			$data = array(
				'page_title' => 'Service List',
				'title' => 'Service List',
				'content' => $this->load->view('leads/services/index', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

    public function new() {
        if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
           if (isset($_POST['submit'])) {
				log_message('error', 'create lead package');
                $service_name = $this->input->post('service_name');
                $service_branch = $this->input->post('service_branch');
                $service_group = $this->input->post('service_group');
                $is_active = $this->input->post('is_active');
                $is_task = $this->input->post('is_task');
                // Validate required fields
                $service_name = trim($service_name);
                if (empty($service_name) || empty($service_branch)) {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Please fill all required fields.');
                    redirect('/leads/service/new');
                }
                $data = array(
                    'service_name' => $service_name,
                    'service_branch' => $service_branch,
                    'service_group' => $service_group,
                    'is_active' => $is_active ? 1 : 0,
                    'is_task' => $is_task ? 1 : 0,
                    'created_by' => $this->auth_user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                );

                // Insert the new service into the database
                $service_id = $this->mcommon->common_insert("ontime_services", $data);

                if ($service_id) {
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Service created successfully.');
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Failed to create service.');
                }
                redirect('/leads/service/new');
            }
            $view_data['branches'] = $this->db->select("ontime_branches.*")->from("ontime_branches")->get()->result_array();
            $view_data['groups'] = $this->mm->get_groups();
            $data = array(
                'page_title' => 'Create Service',
                'title' => 'Create Service',
                'content' => $this->load->view('leads/services/add', $view_data, TRUE),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }

    public function edit() {
        if ($this->verify_min_level(1) && ($this->auth_user_role == 7 || $this->auth_user_role == 89 || $this->auth_user_role == 87)) {
            $service_id = $this->input->get('id');
            if (isset($_POST['submit'])) {
                log_message('error', 'edit lead package');
                $service_name = $this->input->post('service_name');
                $service_branch = $this->input->post('service_branch');
                $service_group = $this->input->post('service_group');
                $is_active = $this->input->post('is_active');
                $is_task = $this->input->post('is_task');

                if (empty($service_name) || empty($service_branch)) {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Please fill all required fields.');
                    redirect('/leads/service/edit?id=' . $service_id);
                }

                // Update the service in the database
                $data = array(
                    'service_name' => $service_name,
                    'service_branch' => $service_branch,
                    'service_group' => $service_group,
                    'is_active' => $is_active ? 1 : 0,
                    'is_task' => $is_task ? 1 : 0,
                    'updated_at' => date('Y-m-d H:i:s'),
                );



                if ($this->mcommon->common_edit("ontime_services", $data, array("id" => $service_id))) {
                    $this->session->set_flashdata('alert', 'success');
                    $this->session->set_flashdata('alert_message', 'Service updated successfully.');
                } else {
                    $this->session->set_flashdata('alert', 'danger');
                    $this->session->set_flashdata('alert_message', 'Failed to update service.');
                }
                redirect('/leads/service/edit?id=' . $service_id);
            }

            // Fetch the existing service details
            $view_data['service'] = $this->db->select("ontime_services.*")->from("ontime_services")->where("id", $service_id)->get()->row_array();
            if (!$view_data['service']) {
                show_404();
            }
            $view_data['branches'] = $this->db->select("ontime_branches.*")->from("ontime_branches")->get()->result_array();
            $view_data['groups'] = $this->mm->get_groups();
            $data = array(
                'page_title' => 'Edit Service',
                'title' => 'Edit Service',
                'content' => $this->load->view('leads/services/edit', $view_data, TRUE),
            );
            $this->load->view('template/base_template_v2', $data);
        } else {
            redirect('login');
        }
    }
}