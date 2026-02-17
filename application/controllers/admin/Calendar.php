<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Calendar extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->library('form_validation');
	}

	public function workspace()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$view_data['page_title'] = 'Calendar Groups';
			$view_data['users'] = $this->mm->get_calendar_groups();
			$data = array(
				'title' => 'Manage Calendar Groups',
				'content' => $this->load->view('admin/calendar/workspace', $view_data, TRUE),
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

					$group_name = trim($this->input->post('calendar_name',true));
					$group_desc = trim($this->input->post('calendar_desc',true));
					// $group_cats = $this->input->post("group_categories[]");
					$group_webs = $this->input->post("group_websites[]");
					$exist = $this->db->select("*")->from("calendar_group")->where("calendar_name", $group_name)->get()->num_rows();
					// print_r($exist);
					// exit();
					if ($exist > 0) {
						$this->session->set_flashdata('alert', array('message' => "Calendar already exist", 'class' => 'danger'));
						redirect($_SERVER['HTTP_REFERER']);
					}

					//prepare insert array
					$insert_array = array(
						'calendar_name' => $group_name,
						'calendar_desc' => $group_desc,
						'created_by' => $_SESSION["user_id"]
					);

					$data = $this->db->insert('calendar_group', $insert_array);
					$data_id = $this->db->insert_id();
					// print_r();
					// print_r($data_id);
					// print_r($this->input->post("group_categories[]"));
					// exit();
					// $bulk_gc = [];
					// foreach ($group_cats as $gc) {
					// 	$this->db->insert('group_categories', array("group_id" => $data_id, "category_id" => $gc, "created_by" => $this->auth_user_id));
					// }
					$ins_settings = array(
						'calendar_id' => $data_id ,
					);
					$data_settings = $this->db->insert('calendar_settings', $ins_settings);
					foreach ($group_webs as $gw) {
						$gw = $this->db->insert('calendar_website', array("calendar_id" => $data_id, "website_id" => $gw, "created_by" => $this->auth_user_id));
					}
					// exit();
					log_message('error', $this->db->last_query());
					if ($data > 0 && $data != NULL) {
						$this->session->set_flashdata('alert', array('message' => 'Calendar Group added successfully', 'class' => 'success'));
						redirect('admin/calendar/workspace');
					} else {
						$this->session->set_flashdata('alert', array('message' => "<b>" . $group_name . '</b> - is already exists or Contact Support.', 'class' => 'danger'));
						redirect('admin/calendar/workspace');
					}
				} catch (Exception $e) {
					// return view("")
					show_error($e);
					log_message('error: ', $e);
					$this->session->set_flashdata('alert', array('message' => "Something went wrong. Cannot create group at this moment.", 'class' => 'danger'));
					redirect('admin/calendar/add');
				}
			}
			$view_data['page_title'] = 'Add Calendar Group';
			// $view_data['categories'] = $this->mm->lead_categories();
			$view_data['websites'] = $this->mm->websites();
			$view_data['branches'] = $this->db->select("*")->from("ontime_branches")->where("is_active", 1)->get()->result_array();


			$data = array(
				'title' => 'Add Group',
				'content' => $this->load->view('admin/calendar/add', $view_data, TRUE),
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
				$group_name = trim($this->input->post('calendar_name',true));
				$group_desc = trim($this->input->post('calendar_desc',true));
				// $group_cats = $this->input->post("group_categories[]");
				$group_webs = $this->input->post("group_websites[]");
				// $group_branch = $this->input->post("group_branch_id");
				// $exist = $this->db->select("*")->from("calendar_group")->where("calendar_name", $group_name)->get()->num_rows();
				$exist = $this->db->select("*")
                  ->from("calendar_group")
                  ->where("calendar_name", $group_name)
                  ->where("calendar_id !=", $id)
                  ->get()
                  ->num_rows();
				// print_r($exist);
				// exit();
				if ($exist > 0) {
					$this->session->set_flashdata('alert', array('message' => "Calendar already exist", 'class' => 'danger'));
					redirect($_SERVER['HTTP_REFERER']);
				}

				//prepare insert array
				$update_array = array(
					'calendar_name' => $group_name,
					'calendar_desc' => $group_desc,
					'created_by' => $_SESSION["user_id"],
					// 'group_branch_id' => $group_branch
				);
				//insert values in database
				$update = $this->mcommon->common_edit('calendar_group', $update_array, array('calendar_id' => $id));
				// $bulk_gc = [];
				// $this->db->delete('group_categories', array("group_id" => $id));
				$this->db->delete('calendar_website', array("calendar_id" => $id));
				// foreach ($group_cats as $gc) {
				// 	$gc = $this->db->insert('group_categories', array("group_id" => $id, "category_id" => $gc, "created_by" => $this->auth_user_id));
				// }
				foreach ($group_webs as $gw) {
					$gw = $this->db->insert('calendar_website', array("calendar_id" => $id, "website_id" => $gw, "created_by" => $this->auth_user_id));
				}

				if ($update > 0 && $gw != 0) {
					$this->session->set_flashdata('alert', array('message' => 'Calendar successfully updated!', 'class' => 'success'));
					return redirect('admin/calendar/workspace');
				} else {
					$this->session->set_flashdata('alert', array('message' => 'Something went wrong. Please try again later', 'class' => 'danger'));
				}
			}
			// echo $id;
			// exit();
			$view_data['default'] = $this->mcommon->specific_row('calendar_group', array('calendar_id' => $id));
			// $view_data['categories'] = $this->mm->lead_categories($id);
			$view_data['page_title'] = 'Edit Calendar Group';
			$view_data['websites'] = $this->mm->calendar_websites($id);
			// $view_data['branches'] = $this->db->select("*")->from("ontime_branches")->where("is_active", 1)->get()->result_array();

			$data = array(
				'title' => 'Users',
				'content' => $this->load->view('admin/calendar/edit', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function status_change($id)
	{
		$current_status = $this->mcommon->specific_row_value('calendar_group', array('calendar_id' => $id), 'status');
		$change_status = ($current_status == 1) ? 0 : 1;
		$delete = $this->mcommon->common_edit('calendar_group', array('status' => $change_status), array('calendar_id' => $id));

		redirect('admin/calendar/workspace');
	}

	public function edit_notification($id)
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			// echo "<pre>";
			// print_r($_POST);
			// echo "</pre>";exit;
			//$view_data['page_title'] = 'Time Slots';
			$view_data['notification'] = $this->mm->get_calendarnotificationsettings($id);
			// print_r($view_data);
			// exit();
			$data = array(
				'title' => 'Calendar Notification',
				'content' => $this->load->view('admin/calendar/edit_notification', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function save_notification(){
		if(isset($_POST)){
			try {
				// Collect form data
				$s_email_booked = $this->input->post('s_email_booked') === 'on' ? 1 : 0;
				$s_email_rescheduled = $this->input->post('s_email_rescheduled') === 'on' ? 1 : 0;
				$s_email_cancelled = $this->input->post('s_email_cancelled') === 'on' ? 1 : 0;
				$s_email_complete = $this->input->post('s_email_complete') === 'on' ? 1 : 0;
				$s_email_noshow = $this->input->post('s_email_noshow') === 'on' ? 1 : 0;

				$s_email_before_num = $this->input->post('s_email_before_num');
				$s_email_before_num_value = $this->input->post('s_email_before_num_value');

				$c_email_booked = $this->input->post('c_email_booked') === 'on' ? 1 : 0;
				$c_email_rescheduled = $this->input->post('c_email_rescheduled') === 'on' ? 1 : 0;
				$c_email_cancelled = $this->input->post('c_email_cancelled') === 'on' ? 1 : 0;
				$c_email_complete = $this->input->post('c_email_complete') === 'on' ? 1 : 0;
				$c_email_noshow = $this->input->post('c_email_noshow') === 'on' ? 1 : 0;

				$c_email_before_num = $this->input->post('c_email_before_num');
				$c_email_before_num_value = $this->input->post('c_email_before_num_value');
				$calendar_id = $this->input->post('calendar_id');

				// Process time conversion
				$s_email_times = $this->process_and_format_times($s_email_before_num, $s_email_before_num_value);
				//  $s_email_times = $this->process_time_values($s_email_before_num, $s_email_before_num_value);
				$c_email_times = $this->process_and_format_times($c_email_before_num, $c_email_before_num_value);
				//  $c_email_times = $this->process_time_values($c_email_before_num, $c_email_before_num_value);
				// Prepare data for insertion
				$data = array(
					's_email_booked' => $s_email_booked,
					's_email_rescheduled' => $s_email_rescheduled,
					's_email_cancelled' => $s_email_cancelled,
					's_email_complete' => $s_email_complete,
					's_email_noshow' => $s_email_noshow,
					's_email_times' => $s_email_times,
					's_email_before_num' => json_encode($s_email_before_num),
					's_email_before_num_value' => json_encode($s_email_before_num_value),
					'c_email_booked' => $c_email_booked,
					'c_email_rescheduled' => $c_email_rescheduled,
					'c_email_cancelled' => $c_email_cancelled,
					'c_email_complete' => $c_email_complete,
					'c_email_noshow' => $c_email_noshow,
					'c_email_times' => $c_email_times,
					'c_email_before_num' => json_encode($c_email_before_num),
					'c_email_before_num_value' => json_encode($c_email_before_num_value),
					'calendar_id' => $calendar_id
				);

				// Insert data into database
				// $this->Calendar_settings_model->insert_settings($data);
				$exist = $this->db->select("*")->from("calendar_settings")->where("calendar_id", $calendar_id)->get()->num_rows();
				if ($exist > 0) {
					$result = $this->mcommon->common_edit('calendar_settings', $data, array('calendar_id' => $calendar_id));
				} else {
					$result = $this->db->insert('calendar_settings', $data);
				}


				if ($result > 0 && $result != NULL) {
					$this->session->set_flashdata('alert', array('message' => 'Calendar Notification updated successfully', 'class' => 'success'));
					redirect('admin/calendar/notifications');
				} else {
					$this->session->set_flashdata('alert', array('message' => 'Calendar not Updated', 'class' => 'danger'));
					redirect('admin/calendar/notifications');
				}
			} catch (Exception $e) {
				// return view("")
				show_error($e);
				log_message('error: ', $e);
				$this->session->set_flashdata('alert', array('message' => "Something went wrong. Cannot create group at this moment.", 'class' => 'danger'));
				redirect('admin/calendar/add');
			}
		}
	}




	public function notifications()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			//$view_data['page_title'] = 'Time Slots';
			$view_data['users'] = $this->mm->get_calendar_groups();
			// print_r($view_data);
			// exit();
			$data = array(
				'title' => 'Calendar Notification',
				'content' => $this->load->view('admin/calendar/notification', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function preferences()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$view_data['users'] = $this->mm->get_calendar_groups();
			// print_r($view_data);
			// exit();
			$data = array(
				'title' => 'Calendar Preference',
				'content' => $this->load->view('admin/calendar/preference', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}
	private function process_and_format_times($numbers, $units)
	{
		// Step 1: Convert to minutes
		$minutes_array = $this->process_time_values($numbers, $units);
		$sum = array_sum(array_map('intval', $minutes_array));
		$formatted_times = $this->minutes_to_hhmmss($sum);
		return isset($formatted_times)? $formatted_times : '00:00:00';
	}
	private function minutes_to_hhmmss($total_minutes)
	{
		// Calculate hours, minutes, and seconds
		$hours = floor($total_minutes / 60);
		$minutes = $total_minutes % 60;
		$seconds = 0; // If you need to include seconds, you can adjust this part

		// Format as hh:mm:ss
		return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
	}

	private function process_time_values($numbers, $units)
	{
		$results = array();
		foreach ($numbers as $key => $number) {
			$unit = isset($units[$key]) ? $units[$key] : '';
			if ($number && $unit) {
				$results[] = $this->convert_to_minutes($number, $unit);
			}
		}
		return $results;
	}

	private function convert_to_minutes($value, $unit)
	{
		switch ($unit) {
			case 'days':
				return $value * 1440; // 24 hours * 60 minutes
			case 'hours':
				return $value * 60;
			case 'minutes':
				return $value;
			default:
				return 0;
		}
	}


	public function edit_preferences($id)
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();

			
			$view_data['preference'] = $this->mm->get_calendarnotificationsettings($id);
			$data = array(
				'title' => 'Calendar Preference',
				'content' => $this->load->view('admin/calendar/edit_preference', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function save_preference(){
		if (isset($_POST)) {
			try {
				// Get data from POST request and store in individual variables
				$scheduling_interval_hr = $this->input->post('scheduling_interval_hr');
				$scheduling_interval_mi = $this->input->post('scheduling_interval_mi');
				$scheduling_interval = $scheduling_interval_hr.":".$scheduling_interval_mi.":"."00";
				$terms_and_conditions = $this->input->post('terms_and_conditions')=="on" ? 'enable' : 'disable';
				$terms_and_conditions_data = $this->input->post('terms_and_conditions_data') ? $this->input->post('terms_and_conditions_data'): '' ;
				$cancellations_and_reschedule = $this->input->post('cancellations_and_reschedule')=="on" ? 'enable' : 'disable';
				$cancellation_and_rescheduling_set_value = $this->input->post('cancellation_and_rescheduling_set_value');
				$minimum_booking_notice_set_value = $this->input->post('minimum_booking_notice_set_value');
				$maximum_booking_notice_set_value = $this->input->post('maximum_booking_notice_set_value');
				$buffer_time_hr = $this->input->post('buffer_time_hr');
				$buffer_time_min = $this->input->post('buffer_time_min');
				$buffer_time = $buffer_time_hr.":".$buffer_time_min.":"."00";
				$calendar_id = $this->input->post('calendar_id');

				$minimum_booking_notice_set_value_store = $minimum_booking_notice_set_value[1].":".$minimum_booking_notice_set_value[2].":"."00";
				$maximum_booking_notice_set_value_store = $maximum_booking_notice_set_value[1].":".$maximum_booking_notice_set_value[2].":"."00";
				$cancellation_and_rescheduling_set_value_store= $cancellation_and_rescheduling_set_value[1].":".$cancellation_and_rescheduling_set_value[2].":"."00";

				// $minimum_booking_notice_set_value_store=$this->process_and_format_times($minimum_booking_notice_set_value, ['days','hours','minutes']);
				// $maximum_booking_notice_set_value_store=$this->process_and_format_times($maximum_booking_notice_set_value, ['days','hours','minutes']);
				// $cancellation_and_rescheduling_set_value_store=$this->process_and_format_times($cancellation_and_rescheduling_set_value, ['days','hours','minutes']);

				// Prepare data for insertion
				$data = array(
					'scheduling_interval' => $scheduling_interval,
					'terms_and_conditions' => $terms_and_conditions,
					'terms_and_conditions_data' => $terms_and_conditions_data,
					'cancellations_and_reschedule' => $cancellations_and_reschedule,
					'cancellation_and_rescheduling_set_value' => json_encode($cancellation_and_rescheduling_set_value),
					'cancellation_and_rescheduling_time' => $cancellation_and_rescheduling_set_value_store,
					'minimum_booking_notice_set_value' => json_encode($minimum_booking_notice_set_value),
					'minimum_booking_notice_time' => $minimum_booking_notice_set_value_store,
					'maximum_booking_notice_set_value' => json_encode($maximum_booking_notice_set_value),
					'maximum_booking_notice_time' => $maximum_booking_notice_set_value_store,
					'buffer_time'=>$buffer_time,
					'calendar_id' => $calendar_id
				);

				// Insert data into database
				// $this->Calendar_settings_model->insert_settings($data);
				$exist = $this->db->select("*")->from("calendar_settings")->where("calendar_id", $calendar_id)->get()->num_rows();
				if ($exist > 0) {
					$result = $this->mcommon->common_edit('calendar_settings', $data, array('calendar_id' => $calendar_id));
				} else {
					$result = $this->db->insert('calendar_settings', $data);
				}


				if ($result > 0 && $result != NULL) {
					$this->session->set_flashdata('alert', array('message' => 'Calendar Preferences updated successfully', 'class' => 'success'));
					redirect('admin/calendar/preferences');
				} else {
					$this->session->set_flashdata('alert', array('message' => 'Calendar not Updated', 'class' => 'danger'));
					redirect('admin/calendar/preferences');
				}
			} catch (Exception $e) {
				// return view("")
				show_error($e);
				log_message('error: ', $e);
				$this->session->set_flashdata('alert', array('message' => "Something went wrong. Cannot create group at this moment.", 'class' => 'danger'));
				redirect('admin/calendar/preferences');
			}
		}
	}

	public function booking_notice()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$data = array(
				'title' => 'Manage TimeSlots',
				'content' => $this->load->view('admin/calendar/booking', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}


	public function cancellation_and_reschedule()
	{
		if ($this->verify_min_level(1)) {
			$view_data = array();
			$data = array(
				'title' => 'Manage TimeSlots',
				'content' => $this->load->view('admin/calendar/cancellation', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}
}
