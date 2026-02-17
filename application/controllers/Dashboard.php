<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('leads_model');
		$this->load->model('access_model');
		$this->load->model('app_model');
		$this->load->model('order_model');
		$this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

	}

	public function index()
	{
		if ($this->verify_min_level(1)) {
			if ($this->auth_user_role == 1) {
				redirect("/admin/user");
			}

			$view_data = array();
			// redirect("/dashboard/index?period=today");
			
			$startDate = date('Y-m-d');
			$endDate = date('Y-m-d');
			$period = "all";

			if(isset($_GET["all"]) && $_GET["all"] == 'true'){
				$startDate = NULL;
				$endDate = NULL;
			}

			if (isset($_GET["period"])) 
			{
				$period = $_GET["period"];
				switch ($period) {
					case 'yesterday':
						$startDate = date('Y-m-d', strtotime('-1 day'));
						$endDate = date('Y-m-d', strtotime('-1 day'));
						break;
					case 'today':
						$startDate = date('Y-m-d');
						$endDate = date('Y-m-d');
						break;
					case 'this_week':
						$startDate = date('Y-m-d', strtotime('monday this week'));
						$endDate = date('Y-m-d');
						break;
					case 'this_month':
						$startDate = date('Y-m-01');
						$endDate = date('Y-m-t');
						break;
					case 'this_year':
						$startDate = date('Y-01-01');
						$endDate = date('Y-12-31');
						break;
					case 'date' :
						$startDate = $_GET["from_date"];
						$endDate = $_GET["to_date"];
						break;
					default:
						show_404();
						return;
				}
			}
			// $type = (isset($_GET["period"])) ? "date" : "all";
			$type = (isset($_GET["all"])) ? "all" : "date" ;
	
			$category_array = $this->access_model->get_my_access_categories();
			$view_data['unassigned_leads_count'] = $this->unassigned_leads_for_user_count($startDate, $endDate);
			$view_data['accepted_leads_count'] = $this->user_leads_count($startDate, $endDate);
			$view_data['assigned_leads_count'] = $this->leads_assigned_by_coordinator_count($startDate, $endDate);
			$view_data['reassigned_leads_count'] = $this->leads_reassigned_count($startDate, $endDate);
			$view_data['created_leads_count'] = $this->created_leads_count($startDate, $endDate);
			$view_data['disqualified_leads_count'] = $this->disqualified_leads_count($startDate, $endDate);
			$view_data['converted_leads_count'] = $this->converted_leads_count($startDate, $endDate);

			$lead_counts = $this->leads_model->get_leads_summary($startDate, $endDate, $type);
			// $lead_counts_details = $this->leads_model->get_leads_summary_details($startDate, $endDate, $type);	
			// $potential_leads = $this->leads_model->get_potential_summary($startDate, $endDate, $type);

			$view_data["lead_counts"] = $lead_counts;
			// $view_data["lead_counts_details"] = $lead_counts_details;
			// $view_data["potential_leads"] = $potential_leads;

			$nationality_lead_counts = $this->leads_model->get_leads_country_summary($startDate, $endDate, $type);
			$view_data["nationality_lead_counts"] = $nationality_lead_counts;

			$assigned_lead_counts = $this->leads_model->get_leads_assigned_summary($startDate, $endDate, $type);
			$view_data["assigned_lead_counts"] = $assigned_lead_counts;

			$next_contactable_leads = $this->leads_model->next_contactable_leads($startDate, $endDate, $type);
			$view_data["next_contactable_leads"] = $next_contactable_leads;

			$gc_lead_source_leads = $this->leads_model->gc_lead_source_leads($startDate, $endDate, $type);
			$view_data["gc_lead_source_leads"] = $gc_lead_source_leads;
			
			// $view_data["latest_lead_action"] = $this->db->select("lead_action_log.lead_id,lead_actions.action_name,lead_actions.id as action_id,lead_action_log.remarks as log_remarks")->from("lead_action_log")->join("leads", "leads.id = lead_action_log.lead_id")->join("lead_actions", "lead_actions.id=lead_action_log.action_id")->where("lead_action_log.action_by", $this->auth_user_id)->order_by("lead_action_log.action_on", "desc")->limit(8)->get()->result_array();

			$tasks_count = $this->leads_model->getTasksCount();
			$view_data["tasks_count"] = $tasks_count;
			$tasks = $this->leads_model->get_lead_tasks($this->auth_user_id);
			$view_data["tasks"] = $tasks;

			$view_data["startDate"] = ($startDate ? $startDate : "");
			$view_data["endDate"] = ($endDate ? $endDate : "");
			$view_data["date"] = ($endDate ? $endDate : date("Y-m-d"));
			$view_data['dld_available_users'] = $this->leads_model->get_dld_available_users($startDate, $endDate, $type);

			$data = array(
				'page_title' => 'Dashboard',
				'title' => 'Dashboard',
				'content' => $this->load->view('pages/dashboard', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	/* public function v2()
	{
		if ($this->verify_min_level(1)) {
			if ($this->auth_user_role == 1) {
				redirect("/admin/user");
			}

			$view_data = array();

			//website access for the user
			$accessible_websites = $this->access_model->get_my_access_sites($this->auth_user_id);

			$access_array = $this->access_model->get_my_access_categories($this->auth_user_id);

			if (in_array(113, $access_array)) {
				if ($this->auth_user_role == 2) {
					redirect("/orders/property/new");
				}
				if ($this->auth_user_role > 5) {
					redirect("/orders/property");
				}
			}
			// echo "<pre>";
			// print_r($access_array);
			// echo "</pre>";
			// exit();

			$groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
			// print_r($groups);
			// exit();
			$group_ids = [];
			foreach ($groups as $group) {
				array_push($group_ids, $group["group_id"]);
			}
			// $this->db->select("distinct(group_members.user_id)")->from("group_members");
			// $this->db->join("users", "users.user_id=group_members.user_id");
			// $this->db->where("users.is_active", 1);
			// $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
			// $group_mems = [];
			// foreach ($members as $mem) {
			// 	array_push($group_mems, $mem["user_id"]);
			// }
			// echo "<pre>";
			// print_r($group_ids);
			// echo "</pre>";
			// exit();

			$view_data['accessible_websites'] = $accessible_websites;
			//leads dashboard
			// $user_categories = $this->leads_model->user_lead_categories();
			// $category_array = array();
			// foreach ($user_categories as $key => $value) {
			// 	array_push($category_array, $value['category_id']);
			// }

			$category_array = $this->leads_model->user_categories();

			// print_r($category_array);
			// exit();
			if ($this->auth_user_role > 5 || $this->auth_user_role == 1) {

				$total["total"] = $this->db->select("count(leads.id) as total")->from("leads")->join("lead_users", "lead_users.user_id=leads.customer_id")->where_in("leads.category_id", $category_array)->get()->first_row()->total;
				$total["salesql"] = $this->db->select("count(leads.id) as total")->from("leads")->join("lead_users", "lead_users.user_id=leads.customer_id")->where("lead_users.mobile IS NOT NULL and lead_users.email is not null and lead_users.mobile != 0")->where_in("leads.category_id", $category_array)->get()->first_row()->total;
				$total["marketingql"] = $this->db->select("count(leads.id) as total")->from("leads")->join("lead_users", "lead_users.user_id=leads.customer_id")->where("(((lead_users.mobile IS NULL or lead_users.mobile = 0) and lead_users.email is not null) or ((lead_users.mobile IS not NULL and lead_users.mobile != 0) and lead_users.email is null))")->where_in("leads.category_id", $category_array)->get()->first_row()->total;

				// ->("select count(leads.id) as total from leads join lead_users on  and leads.category_id")->first_row(); 
			} else {
				$total["total"] = $this->db->select("count(leads.id) as total")->from("leads")->join("lead_users", "lead_users.user_id=leads.customer_id")->join("leads_assigned", "leads_assigned.lead_id=leads.id")->where("leads_assigned.assigned_to", $this->auth_user_id)->get()->first_row()->total;

				$total["salesql"] = $this->db->select("count(leads.id) as total")->from("leads")->join("lead_users", "lead_users.user_id=leads.customer_id")->where("lead_users.mobile IS NOT NULL and lead_users.email is not null and lead_users.mobile != 0")->join("leads_assigned", "leads_assigned.lead_id=leads.id")->where("leads_assigned.assigned_to", $this->auth_user_id)->get()->first_row()->total;

				$total["marketingql"] = $this->db->select("count(leads.id) as total")->from("leads")->join("lead_users", "lead_users.user_id=leads.customer_id")->where("(((lead_users.mobile IS NULL or lead_users.mobile = 0) and lead_users.email is not null) or ((lead_users.mobile IS not NULL and lead_users.mobile != 0) and lead_users.email is null))")->join("leads_assigned", "leads_assigned.lead_id=leads.id")->where("leads_assigned.assigned_to", $this->auth_user_id)->get()->first_row()->total;
			}

			$view_data["lead_counts"] = $total;

			$view_data["latest_lead_action"] = $this->db->select("lead_action_log.lead_id,lead_actions.action_name,lead_actions.id as action_id,lead_action_log.remarks as log_remarks")->from("lead_action_log")->join("leads", "leads.id = lead_action_log.lead_id")->join("lead_actions", "lead_actions.id=lead_action_log.action_id")->where("lead_action_log.action_by", $this->auth_user_id)->order_by("lead_action_log.action_on", "desc")->limit(8)->get()->result_array();
			
			// echo "<pre>";
			// print_r($view_data["latest_lead_action"]);
			// // print_r($this->db->last_query());
			// echo "</pre>";
			// exit();
			// (select count(leads.id) from leads join lead_users on lead_users.user_id = leads.customer_id where lead_users.mobile IS NOT NULL and lead_users.email is not null and lead_users.mobile != 0) as salesql, 
			// (select count(leads.id) from leads join lead_users on lead_users.user_id = leads.customer_id where ((lead_users.mobile IS NULL or lead_users.mobile = 0) and lead_users.email is not null) or ((lead_users.mobile IS not NULL and lead_users.mobile != 0) and lead_users.email is null)) as mql

			if (!empty($category_array)) {
				$view_data['unassigned_leads'] = 0;
				$view_data['accepted_leads'] = $this->leads_model->user_leads();
				$view_data['assigned_leads'] = $this->leads_model->leads_assigned_by_coordinator();
				$view_data['pos_leads'] = $this->leads_model->pos_leads_coordinator();

				if ($this->auth_user_role > 5) {
					$view_data['converted_leads'] = $this->leads_model->converted_leads_cordinator();
					$view_data['disqualified_leads'] = $this->leads_model->coordinator_disqualified_leads();
				} else {
					$view_data['converted_leads'] = $this->leads_model->converted_leads();
					$view_data['disqualified_leads'] = $this->leads_model->disqualified_leads();
				}
			} else {
				$view_data['unassigned_leads'] = array();
				$view_data['accepted_leads'] = array();
				$view_data['converted_leads'] = array();
				$view_data['disqualified_leads'] = array();
				$view_data['assigned_leads'] = [];
			}

			//Baraha
			if ($this->auth_user_role == 1 || $this->auth_user_role > 5) {
				$baraha_orders = $this->order_model->baraha_orders();
				$baraha_meetings = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 501, 'enquiry' => 3));
				$baraha_enquiries = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 501, 'enquiry' => 1));
				$baraha_complaints = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 501, 'enquiry' => 2));

				$visa_meetings = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 503, 'enquiry' => 3));
				$visa_enquiries = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 503, 'enquiry' => 1));
				$visa_complaints = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 503, 'enquiry' => 2));

				$business_meetings = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 504, 'enquiry' => 3));
				$business_enquiries = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 504, 'enquiry' => 1));
				$business_complaints = $this->mcommon->specific_fields_records_all('enquiry', array('website_id' => 504, 'enquiry' => 2));
			}

			if ($this->auth_user_role == 2) {
				$baraha_orders = $this->order_model->baraha_csa_orders();
				$baraha_meetings = $this->app_model->csa_meetings(501);
				$baraha_enquiries = $this->app_model->csa_enquiries(501);
				$baraha_complaints = $this->app_model->csa_complaints(501);

				//visa
				$visa_meetings = $this->app_model->csa_meetings(503);
				$visa_enquiries = $this->app_model->csa_enquiries(503);
				$visa_complaints = $this->app_model->csa_complaints(503);

				$business_meetings = $this->app_model->csa_meetings(504);
				$business_enquiries = $this->app_model->csa_enquiries(504);
				$business_complaints = $this->app_model->csa_complaints(504);
			}

			$view_data['baraha_orders'] = $baraha_orders;
			$view_data['baraha_meetings'] = $baraha_meetings;
			$view_data['baraha_enquiries'] = $baraha_enquiries;
			$view_data['baraha_complaints'] = $baraha_complaints;

			// $view_data['visa_orders'] = $visa_orders;
			$view_data['visa_orders'] = [];
			$view_data['visa_meetings'] = $visa_meetings;
			$view_data['visa_enquiries'] = $visa_enquiries;
			$view_data['visa_complaints'] = $visa_complaints;

			// $view_data['business_orders'] = $business_orders;
			$view_data['business_orders'] = [];
			$view_data['business_meetings'] = $business_meetings;
			$view_data['business_enquiries'] = $business_enquiries;
			$view_data['business_complaints'] = $business_complaints;

			if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
				$view_data["date"] = date("Y-m-d");
				if($_GET["date"]) {
					$view_data["date"] = $_GET["date"];
				}

				$groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
				// print_r($groups);
				// exit();
				$group_ids = [];
				foreach ($groups as $group) {
					array_push($group_ids, $group["group_id"]);
				}
				$this->db->select("distinct(group_members.user_id)")->from("group_members");
				$this->db->join("users", "users.user_id=group_members.user_id");
				$this->db->where("users.is_active", 1);
				$members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
				$group_mems = [];
				foreach ($members as $mem) {
					array_push($group_mems, $mem["user_id"]);
				}

				// Total leads created today.
				// Total leads received today.
				// Pending leads to be actioned
				// Total number of qualified leads this month.
				// Total number of disqualified leads this month

				$this->db->select("count(distinct leads.id) as counts,lead_status.status_group as status_group")->from("leads");
				$this->db->join("leads_assigned", "leads.id = leads_assigned.lead_id", "left");
				$this->db->join("lead_status", "lead_status.id = leads.lead_status");
				$this->db->where("(leads.lead_created_by in (" . implode(",", $group_mems) . ") or leads_assigned.assigned_to in (" . implode(",", $group_mems) . "))");
				$this->db->group_by("lead_status.status_group");
				$stat_counts = $this->db->get()->result_array();

				$this->db->select("SUM(IF(leads.lead_status=301,1,0)) as created,SUM(IF(leads.lead_status=302,1,0)) as accepted,SUM(IF(leads.lead_status=305,1,0)) as confirmed,SUM(IF(leads.lead_status=306,1,0)) as disqualified,count(leads.id) as total")->from("leads");
				$this->db->join("leads_assigned", "leads.id = leads_assigned.lead_id", "left");
				$this->db->where("(leads.lead_created_by in (" . implode(",", $group_mems) . ") or leads_assigned.assigned_to in (" . implode(",", $group_mems) . "))");
				$lead_count = $this->db->get()->first_row();

				$this->db->select("count(distinct leads.id) as total")->from("leads");
				$this->db->join("leads_assigned", "leads.id = leads_assigned.lead_id", "left");
				$this->db->where("(leads.lead_created_by in (" . implode(",", $group_mems) . "))")->where("(date(leads.created_at) = date('".$view_data["date"]."'))");
				$created = $this->db->get()->first_row();

				$this->db->select("count(distinct leads.id) as total")->from("leads");
				$this->db->join("leads_assigned", "leads.id = leads_assigned.lead_id", "left");
				$this->db->where("(leads.lead_created_by not in (" . implode(",", $group_mems) . ") and leads_assigned.assigned_to in (" . implode(",", $group_mems) . "))")->where("(date(leads.created_at) = date('".$view_data["date"]."') or date(leads_assigned.assigned_on) = date('".$view_data["date"]."'))");
				$received = $this->db->get()->first_row();

				$this->db->select("SUM(IF(leads.lead_status = 306,1,0)) as disqualified,sum(IF(leads.id,1,0)) as total")->from("leads");
				$this->db->join("leads_assigned", "leads.id = leads_assigned.lead_id", "left");
				$this->db->where("(leads.lead_created_by in (" . implode(",", $group_mems) . ") or leads_assigned.assigned_to in (" . implode(",", $group_mems) . "))");
				$this->db->where("(month(leads.created_at) = month('".$view_data["date"]."'))");
				$qualified = $this->db->get()->first_row();

				echo "<pre>";
				print_r($stat_counts);
				echo "</pre>";
				exit();
				$group_stat["opened"] = $lead_count->created + $lead_count->accepted;
				$group_stat["today_created"] = $created->total;
				$group_stat["today_received"] = $received->total;
				$group_stat["qualified"] = $qualified;

				$view_data['group_stat'] = $group_stat;
			}

			$data = array(
				'page_title' => 'Dashboard',
				'title' => 'Dashboard',
				'content' => $this->load->view('pages/dashboard', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	} */

	public function fetch_leads_summary()
	{
		$id = $_GET["id"] == 'walk_in' ? 0 : ($_GET["id"] == 'online' ? 1 : 2);
		$status = $_GET["status"];
		$startDate = $_GET["startDate"];
		$endDate = $_GET["endDate"];
		$type = $_GET["type"];
		$request = $_GET["request"];

		if($id == 2){
			if($status == 'assigned')
			{
				$data = $this->leads_model->leads_assigned_by_coordinator($startDate, $endDate, $type);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			} else if($status == 'accepted'){
				$data = $this->leads_model->user_leads($startDate, $endDate, $type);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			}  else if($status == 'unassigned'){
				$data = $this->leads_model->unassigned_leads_for_user($startDate, $endDate);
				$transformedData = array_map(function($item) {
					$item['assigned_user_name'] = '';
					$item['assigned_group'] = '';
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			} else if($status == 'reassigned'){
				$data = $this->leads_model->leads_reassigned($startDate, $endDate, $type);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			}  else if($status == 'created'){
				$data = $this->leads_model->created_leads($startDate, $endDate, $type);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			}  else if($status == 'converted' || $status == 'Completed'){
				$data = $this->leads_model->converted_leads($startDate, $endDate);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			} else if($status == 'Disqualified'){
				$data = $this->leads_model->disqualified_leads($startDate, $endDate);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			}
		} 

		if($id != 2){ // Online or Walk in
			if($request == 'potential')
			{
				$data = $this->leads_model->get_potential_summary($startDate, $endDate, $type);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use ($id) {
					return ($id != 2) ? ($item['zoho_id'] == $id) : true;
				});
			} else {
				$data = $this->leads_model->get_leads_summary_details($startDate, $endDate, $type);
				$transformedData = array_map(function($item) {
					if ($item['assigned_to'] == $this->auth_user_id) {
						$item['assigned_user_name'] = 'Self';
					} else {
						$assigned_user_data = get_user_display_data($item['assigned_to']);
						$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
					}
					return $item;
				}, $data);
				$filteredData = array_filter($transformedData, function($item) use($status, $id) {
					if($id != 2){
						return ($item['status_group'] == $status && $item['lead_zoho_id'] ==  $id);
					}else{
						return ($item['status_group'] == $status);
					}
				});
			}
		} 

		$result['data'] = array_values($filteredData);
		echo json_encode($result);
	}

	public function access()
	{
		$this->load->model('access_model');

		$access_array = $this->access_model->get_my_access($this->auth_user_id);
		$accessible_websites = array();
		foreach ($access_array as $key => $value) {
			array_push($accessible_websites, $value['id']);
		}
		if (in_array(501, $accessible_websites)) {
			echo "You have access to baraha";
		} else {
			echo "Oops";
		}


		if (in_array(502, $accessible_websites)) {
			echo "You have access to gov";
		} else {
			echo "Oops";
		}

		if (in_array(503, $accessible_websites)) {
			echo "You have access to trustee";
		} else {
			echo "Oops";
		}
	}

	public function user_categories()
    {
        $this->db->select("DISTINCT(group_categories.category_id)")->from("group_categories");
        $this->db->join("groups", "groups.group_id=group_categories.group_id and groups.status=1");
        $this->db->join("group_members", "groups.group_id=group_members.group_id and group_members.user_id = " . $this->auth_user_id . " and group_members.status=1");
        $query = $this->db->get();
        $categories = $query->result_array();
        $cats = [];
        foreach ($categories as $category) {
            array_push($cats, $category["category_id"]);
        }
        return $cats;
    }

	public function user_leads_count($from_date = null, $to_date = null)
    {
        // Fetch the group IDs for the user
        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->where("user_id",$this->auth_user_id);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        // Extract user_id values into a plain array
        $user_ids = array_column($result, 'user_id');

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('count(*) as count');
        // lg.creator_branch,lg.assigned_branch');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
		$this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);

        $this->db->where('l.is_assigned', 1);
        $this->db->where("(l.order_receipt = '0' OR l.order_receipt IS NULL)");
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_status NOT IN (306, 624,606)');
        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
        
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        // $results = $this->db->get()->result_array();
		$results = $this->db->get()->num_rows();

        return $results;
    }

	public function leads_assigned_by_coordinator_count($from_date = null, $to_date = null)
    {
        $cats = $this->user_categories();
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
            $group_ids = [];
            foreach ($groups as $group) {
                array_push($group_ids, $group["group_id"]);
            }
            $this->db->select("distinct(group_members.user_id)")->from("group_members");
            $this->db->join("users", "users.user_id=group_members.user_id");
            $members = $this->db->where_in("group_members.group_id", $group_ids)->get()->result_array();
            $group_mems = [];
            foreach ($members as $mem) {
                array_push($group_mems, $mem["user_id"]);
            }
        }

        $this->db->select('count(*) as count');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');

        if ($this->auth_user_role == 2 || $this->auth_user_role == 6) {
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('(l.category_id in (' . implode(",", $cats) . ') OR la.assigned_by =' . $this->auth_user_id . ')');
        } else if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
            $this->db->where('((l.category_id in (' . implode(",", $cats) . ') OR la.assigned_to in (' . implode(",", $group_mems) . ') or l.biz_assigned in (' . implode(",", $group_mems) . ') or l.biz_assigned = ' . $this->auth_user_id . ' OR la.assigned_by =' . $this->auth_user_id . ') and l.lead_created_by not in (' . implode(",", $group_mems) . '))');
        } else {
            $this->db->join('leads_assigned as la', 'la.lead_id=l.id and la.assigned_to = ' . $this->auth_user_id);
        }
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->where_in('l.assigned_group_id', $group_ids);
        }

        $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . "))");
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('l.assigned_group_id in (' . implode(",", $user_group_ids) . ')');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        
        if ($this->auth_user_role > 5) {
            $this->db->where('(l.lead_parent_id = 0)');
        }
        if (!empty($from_date)) {
            $this->db->where('DATE(la.assigned_on) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(la.assigned_on) <=', $to_date);
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');

        // $results = $this->db->get()->result_array();
		$results = $this->db->get()->num_rows();
		// print_r($this->db->last_query());
		// exit;
 
        return $results;
    }

	public function leads_reassigned_count($from_date = null, $to_date = null)
    {
        $group_mems = [];
		$groups = $this->db->select('distinct(group_id)')
							->from("group_members")
							->where("user_id", $this->auth_user_id)
							->get()
							->result_array();
		
		$group_ids = array_column($groups, 'group_id');
		$members = $this->db->select("distinct(group_members.user_id)")
							->from("group_members")
							->join("users", "users.user_id=group_members.user_id")
							->where_in("group_members.group_id", $group_ids)
							->get()
							->result_array();

		$group_mems = array_column($members, 'user_id');

        // Convert user IDs array to a comma-separated string
        $assigned_to_ids_str = implode(',', $group_mems);

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;

        // Main leads query
        $this->db->select('count(*) as count');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id');
        $this->db->join('users as creator', 'creator.user_id = l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id = l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id = l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id = l.id');
        $this->db->join('lead_action_log as lal', 'lal.lead_id = l.id');

        if (!empty($assigned_to_ids_str)) {
            $this->db->where("la.assigned_by IN ($assigned_to_ids_str)", NULL, FALSE);
            $this->db->where("la.assigned_to NOT IN ($assigned_to_ids_str)", NULL, FALSE);
        }
        if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
            $this->db->where_not_in('l.created_group_id', $group_ids);
        }
        $this->db->where("(l.created_group_id  in (" . implode(",", $group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $group_ids) . ") OR la.assigned_by =" . $this->auth_user_id . " OR 
        (lal.status_id = 303 and lal.action_by =" . $this->auth_user_id . "))");
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');

        if (!empty($from_date)) {
            $this->db->where('DATE(la.assigned_on) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(la.assigned_on) <=', $to_date);
        }
        if ($this->auth_user_role > 5) {
            $this->db->where('(l.lead_parent_id = 0)');
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');

        // $results = $this->db->get()->result_array();
		$results = $this->db->get()->num_rows();

        return $results;
    }

	public function created_leads_count($from_date = null, $to_date = null)
    {
        $user_groups = $this->db->select('distinct(group_id)')
				->from("group_members")->where("user_id", $this->auth_user_id)
				->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        // Main query to fetch user IDs for the group IDs
        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();

        $result = $query->result_array();
        $user_ids = array_column($result, 'user_id');

        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);
        $this->db->select('count(*) as count');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');

        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');
        if($this->auth_user_role == 7 ){
            $this->db->where("(l.created_group_id  in (" . implode(",", $user_group_ids) . ")  or l.assigned_group_id  in (" . implode(",", $user_group_ids) . ") or l.lead_zoho_created_by_id = " . $this->auth_user_id . ")");
        } else if ($this->auth_user_role == 2 || $this->auth_user_role == 6){
            $this->db->where('(l.lead_created_by = ' . $this->auth_user_id . ' or la.assigned_to = ' . $this->auth_user_id . ' 
            or l.lead_zoho_created_by_id = ' . $this->auth_user_id . ')');
        } else if($this->auth_user_role == 86 || $this->auth_user_role == 89){
            $this->db->where_in('l.lead_created_by', $assigned_to_ids);
        } else if($this->auth_user_role == 84 && in_array(74, $user_group_ids)) {
            $group_mems = [ $this->auth_user_id, 3946368694 ];  
            $this->db->where_in('l.lead_created_by', $group_mems);
        } else{
            $this->db->where('l.lead_created_by', $this->auth_user_id);
        }

        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.order_receipt', "0");
        $this->db->where('(`l`.`lead_status` not in (305,306,309,624,606) and `l`.`branch_id`!=100)');
        $this->db->where('(l.lead_parent_id = 0 OR l.is_assigned = 1)');
        $this->db->where('l.lead_parent_id = 0 ');

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }
 
        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        // $results = $this->db->get()->result_array();
		$results = $this->db->get()->num_rows();

        return $results;
    }

	public function disqualified_leads_count($from_date = null, $to_date = null, $user_type = "")
    {
        $groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('count(*) as count');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id = l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id = l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id = l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id = l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id = l.id');
        $this->db->where('l.is_assigned', 1);
        $this->db->where('l.lead_status', 306);

		if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
			$this->db->where("(la.assigned_to IN ($assigned_to_ids_str) OR l.lead_created_by IN ($assigned_to_ids_str))", NULL, FALSE);
		} else {
			$this->db->where('la.assigned_to', $this->auth_user_id);
		}
        
        $group_ids_str = implode(',', $group_ids);
        $this->db->where("(l.assigned_group_id IN ($group_ids_str) OR l.created_group_id IN ($group_ids_str))", NULL, FALSE);
        $this->db->where('(l.lead_parent_id = 0 AND l.is_assigned = 1)');

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        // $results = $this->db->get()->result_array();
		$results = $this->db->get()->num_rows();
        // echo $this->db->last_query();
        // exit;
        return $results;
    }

	public function converted_leads_count($from_date = null, $to_date = null, $user_type = "")
    {   
        $groups = $this->db->select('distinct(group_id)')
				->from("group_members")->where("user_id", $this->auth_user_id)
				->get()->result_array();

        $group_ids = [];
        foreach ($groups as $group) {
            array_push($group_ids, $group["group_id"]);
        }

        $this->db->select('group_id');
        $this->db->from('group_members');
        $this->db->where('user_id', $this->auth_user_id);
        $subquery = $this->db->get_compiled_select();

        $this->db->select('user_id');
        $this->db->from('group_members');
        $this->db->where("group_id IN ($subquery)", NULL, FALSE);
        $this->db->order_by('user_id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();

        $user_ids = array_column($result, 'user_id');
        $assigned_to_ids = $user_ids;
        $assigned_to_ids_str = implode(',', $assigned_to_ids);

        $this->db->select('count(*) as count');
        $this->db->from('leads as l');
        $this->db->join('lead_users as u', 'u.user_id=l.customer_id');
        $this->db->join('users as creator', 'creator.user_id=l.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = l.created_group_id');
        $this->db->join('groups as ga ', 'ga.group_id = l.assigned_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=l.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=l.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=l.lead_status');
        $this->db->join('leads_assigned as la', 'la.lead_id=l.id');

		if($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89){
			$this->db->where("(la.assigned_to IN ($assigned_to_ids_str) OR l.lead_created_by IN ($assigned_to_ids_str))", NULL, FALSE);
		}else{
			$this->db->where('(la.assigned_to = ' . $this->auth_user_id . ' or l.lead_created_by = ' . $this->auth_user_id . ')');
		}
		if ($this->auth_user_role == 7 || $this->auth_user_role == 86 || $this->auth_user_role == 89) {
			$group_ids_str = implode(',', $group_ids);
			$this->db->where("(l.assigned_group_id IN ($group_ids_str) OR l.created_group_id IN ($group_ids_str))", NULL, FALSE);
		}

        $this->db->where('l.is_assigned', 1);

        $this->db->where('l.lead_status', 305);

        if (!empty($from_date)) {
            $this->db->where('DATE(l.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(l.created_at) <=', $to_date);
        }

        $this->db->group_by('l.id');
        $this->db->order_by('l.contactable_date', 'DESC');
        // $results = $this->db->get()->result_array();
		$results = $this->db->get()->num_rows();

        return $results;
    }

	public function unassigned_leads_for_user_count($from_date = null, $to_date = null)
    {
        $user_groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
        $user_group_ids = [];
        foreach ($user_groups as $group) {
            array_push($user_group_ids, $group["group_id"]);
        }

        $this->db->select('count(*) as count')->from("leads");
        $this->db->join('lead_users as u', 'u.user_id=leads.customer_id');
        $this->db->join('users as creator', 'creator.user_id=leads.lead_created_by');
        $this->db->join('group_members as gm', 'gm.user_id = creator.user_id');
        $this->db->join('groups as g ', 'g.group_id = gm.group_id');
        $this->db->join('groups as gc ', 'gc.group_id = leads.created_group_id');
        $this->db->join('ontime_categories as lc', 'lc.category_id=leads.category_id');
        $this->db->join('ontime_category_services_ as ls', 'ls.service_id=leads.service_id');
        $this->db->join('lead_status as lstat', 'lstat.id=leads.lead_status');
        $this->db->where('leads.is_assigned', 0);

        $this->db->where("(leads.created_group_id  in (" . implode(",", $user_group_ids) . ")  or leads.lead_created_by = " . $this->auth_user_id . ")");
        
        $this->db->where('leads.lead_parent_id', 0);
        $this->db->where('leads.lead_status not in (305,306,309,624,606)');
        $this->db->where('leads.branch_id != 100');
		$this->db->where('leads.is_pos_lead !=', 1);

        if (!empty($from_date)) {
            $this->db->where('DATE(leads.created_at) >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('DATE(leads.created_at) <=', $to_date);
        }

        $this->db->group_by('leads.id');
        $this->db->order_by('leads.id', 'DESC');
        // $results = $this->db->get();
        // $results = $results->result_array();
		$results = $this->db->get()->num_rows();
        
        return $results;
    }

	public function get_leads_country_summary_details()
	{
		$country = $_GET["country"];
		$status = $_GET["status"];
		$startDate = $_GET["startDate"];
		$endDate = $_GET["endDate"];
		$type = $_GET["type"];

		$data["data"] = $this->leads_model->get_leads_country_summary_details($country, $status, $startDate, $endDate, $type);
		echo json_encode($data);
	}

	public function get_leads_assigned_summary_details()
	{
		$user = $_GET["user"];
		$status = $_GET["status"];
		$startDate = $_GET["startDate"];
		$endDate = $_GET["endDate"];
		$type = $_GET["type"];

		$data["data"] = $this->leads_model->get_leads_assigned_summary_details($user, $status, $startDate, $endDate, $type);
		echo json_encode($data);
	}

	public function fetch_leads_source_details()
	{
		$id = $_GET["id"];
		$status = $_GET["status"];
		$startDate = $_GET["startDate"];
		$endDate = $_GET["endDate"];
		$type = $_GET["type"];
		$request = $_GET["request"];

		$data = $this->leads_model->get_leads_source_details($startDate, $endDate, $type, $status);
		$transformedData = array_map(function($item) {
			if ($item['assigned_to'] == $this->auth_user_id) {
				$item['assigned_user_name'] = 'Self';
			} else {
				$assigned_user_data = get_user_display_data($item['assigned_to']);
				$item['assigned_user_name'] = $assigned_user_data['first_name'] . ' ' . $assigned_user_data['last_name'];
			}
			return $item;
		}, $data);

		$result['data'] = array_values($transformedData);
		echo json_encode($result);
	}
}