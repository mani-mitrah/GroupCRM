<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('leads_model');
		$this->load->model('access_model');
		$this->load->model('app_model');
		$this->load->model('order_model');
	}

	public function prepayments()
	{
		if ($this->verify_min_level(1) && ($this->auth_user_role > 5 || $this->auth_user_role == 1)) {
			$view_data = [];
			$view_data["branches"] = $this->db->select("*")->from("ontime_branches")->where("is_active", 1)->get()->result_array();
			$view_data["categories"] = $this->db->select("*")->from("ontime_categories")->where("is_active", 1)->get()->result_array();
			$view_data["services"] = $this->db->select("*")->from("ontime_category_services_")->where("is_active", 1)->get()->result_array();

			if (isset($_GET)) {
				$view_data["req"] = $_GET;
			}
			$data = array(
				'page_title' => 'Reports - PrePayments',
				'title' => 'Reports - PrePayments',
				'content' => $this->load->view('reports/prepayments', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function prepayments_getDtdata()
	{
		$data["data"] = $this->leads_model->prepayments($_GET);
		echo json_encode($data);
	}

	public function lead_complete()
	{
		if ($this->verify_min_level(1)  && ($this->auth_user_role > 5 || $this->auth_user_role == 1)) {
			$view_data = [];
			$view_data["branches"] = $this->db->select("*")->from("ontime_branches")->where("is_active", 1)->get()->result_array();
			$view_data["categories"] = $this->db->select("*")->from("ontime_categories")->where("is_active", 1)->get()->result_array();
			$view_data["services"] = $this->db->select("*")->from("ontime_category_services_")->where("is_active", 1)->get()->result_array();

			if (isset($_GET)) {
				$view_data["req"] = $_GET;
			}
			$data = array(
				'page_title' => 'Reports - Leads ',
				'title' => 'Reports - Leads',
				'content' => $this->load->view('reports/lead_completion', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		} else {
			redirect('login');
		}
	}

	public function lead_complete_getDtdata()
	{
		// print_r($_GET);
		// exit();
		$data["data"] = $this->leads_model->lead_complete($_GET);
		// print_r($this->db->last_query());
		echo json_encode($data);
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



	public function statreport()
	{
		if ($this->verify_min_level(1)  && ($this->auth_user_role > 5 || $this->auth_user_role == 1) ) {
			if($this->auth_user_role == 87){
				$view_data["page_title"] = "Report";
				$super_access = "";
				$this->db->select('distinct(ontime_branches.branch_name) as branch_name,ontime_branches.branch_code as branch_code,groups.group_name');
				$this->db->from('group_members AS gm');
				$this->db->join('groups', 'groups.group_id = gm.group_id');
				$this->db->join('ontime_branches', 'ontime_branches.branch_code = `groups`.group_branch_id`');
				$this->db->where('groups.status', 1);
			}else{
				$view_data["page_title"] = "Report";
				$super_access = "";
				$this->db->select('ontime_branches.branch_name as branch_name,ontime_branches.branch_code as branch_code,groups.group_name');
				$this->db->from('group_members AS gm');
				$this->db->join('groups', 'groups.group_id = gm.group_id');
				$this->db->join('ontime_branches', 'ontime_branches.branch_code = `groups`.group_branch_id`');
				$this->db->where('groups.status', 1);
				$this->db->where('gm.user_id', $this->auth_user_id);	
			}
			$accessable_branch = $this->db->get()->result_array();
			// print_r($accessable_branch);
			// exit();
			$view_data["branches"] = $accessable_branch;
			$data = array(
				'page_title' => 'Reports ',
				'title' => 'Reports',
				'content' => $this->load->view('reports/reports', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		}
	}

	public function statreport_getDtdata()
	{
		
		if ($this->verify_min_level(1) && ($this->auth_user_role > 5 || $this->auth_user_role == 1)) {
				if (isset($_REQUEST["report_type"])) {
					// print_r($_REQUEST);
					// exit();
	
	
					$groups = $this->db->select('distinct(group_id)')->from("group_members")->where("user_id", $this->auth_user_id)->get()->result_array();
					// print_r($groups);
					// exit();
					$group_ids = [];
					foreach ($groups as $group) {
						array_push($group_ids, $group["group_id"]);
					}
	
					//Main Leads
					// $this->db->select("*")->from("sub_lead_qualified")->where("(creator_by_group_id in (" . implode(',', $group_ids) . ") or completed_by_group_id in (" . implode(',', $group_ids) . ") or assigned_to_group_id in (" . implode(',', $group_ids) . "))");
					// $this->db->select("*")->from("sub_lead_qualified");
					$this->db->select("*")->from("exported_sublead_qualified_report_data");
	
					//Disqualified 
					if ($_REQUEST["report_type"] == "received") {
						$this->db->where("(lead_status in (306,305,302,303,304,307,308,310,311,312))");
						
						/*
						if ($_REQUEST["from_date"] != NULL && $_REQUEST["from_date"] != "") {
							//$this->db->where("date(lead_action_log.created_at) >=", $_REQUEST["from_date"]);
							$this->db->where("((date(lead_last_action_at) >= '" . $_REQUEST["from_date"] . "') OR date(lead_created_at) >= '" . $_REQUEST["from_date"] . "'))");
						}
						if ($_REQUEST["to_date"] != NULL && $_REQUEST["to_date"] != "") {
							//$this->db->where("date(lead_last_action_at) <=", $_REQUEST["to_date"]);
							$this->db->where("((date(lead_last_action_at) <= '" . $_REQUEST["to_data"] . "') OR (date(lead_created_at) <= '" . $_REQUEST["to_data"] . "'))");
						}*/
						if ($_REQUEST["from_date"] != NULL && $_REQUEST["from_date"] != "") {
							$from_date = $_REQUEST["from_date"];
						}
	
						if ($_REQUEST["to_date"] != NULL && $_REQUEST["to_date"] != "") {
							$to_date = $_REQUEST["to_date"];
						}
	
						if (isset($from_date) && isset($to_date)) {
							// Define a date range for both lead_last_action_at and lead_created_at
							$date_range = "BETWEEN '$from_date 00:00:01' AND '$to_date 23:59:59'";
							$this->db->where("lead_last_action_at $date_range");
							$this->db->where("lead_created_at $date_range");
						}
	
					}
	
					// if ($_REQUEST["report_type"] == "completed") {
					// 	$this->db->where("(lead_status in (305))");
					// 	if ($_REQUEST["from_date"] != NULL && $_REQUEST["from_date"] != "") {
					// 		//$this->db->where("date(lead_last_action_at) >=", $_REQUEST["from_date"]);
					// 		$this->db->where("((date(lead_last_action_at) >= '" . $_REQUEST["from_date"] . "') OR (date(lead_created_at) >= '" . $_REQUEST["from_date"] . "'))");
					// 	}
					// 	if ($_REQUEST["to_date"] != NULL && $_REQUEST["to_date"] != "") {
					// 		//$this->db->where("date(lead_last_action_at) <=", $_REQUEST["to_date"]);
					// 		$this->db->where("((date(lead_last_action_at) <= '" . $_REQUEST["to_date"] . "') OR (date(lead_created_at) <= '" . $_REQUEST["to_date"] . "'))");
					// 	}
					// }
	
					// if ($_REQUEST["report_type"] == "processing") {
					// 	$this->db->where("(lead_status in (302,303,304,307,308,310,311,312))");
					// 	if ($_REQUEST["from_date"] != NULL && $_REQUEST["from_date"] != "") {
					// 		$this->db->where("date(lead_created_at) >=", $_REQUEST["from_date"]);
					// 	}
					// 	if ($_REQUEST["to_date"] != NULL && $_REQUEST["to_date"] != "") {
					// 		$this->db->where("date(lead_created_at) <=", $_REQUEST["to_date"]);
					// 	}
					// }
	
					if ($_REQUEST["report_type"] == "created") {
						// $this->db->where("(lead_status in (302,303,304,307,308,310,311,312))");
						$this->db->where("(creator_by_group_id in (" . implode(',', $group_ids) . "))");
						if ($_REQUEST["from_date"] != NULL && $_REQUEST["from_date"] != "") {
							$this->db->where("date(lead_created_at) >=", $_REQUEST["from_date"]);
						}
						if ($_REQUEST["to_date"] != NULL && $_REQUEST["to_date"] != "") {
							$this->db->where("date(lead_created_at) <=", $_REQUEST["to_date"]);
						}
					} else {
						$this->db->where("(completed_by_group_id in (" . implode(',', $group_ids) . ") or assigned_to_group_id in (" . implode(',', $group_ids) . "))");
					}
	
					if ($_REQUEST["report_type"] != "created" && isset($_REQUEST["branch_id"])) {
						if ($_REQUEST["branch_id"] != "") {
							// creator_by_branch_id
							// assigned_to_branch_id
							// completed_by_branch_id
							if ($_REQUEST["branch_id"] == 109) {
								$this->db->where("(assigned_to_branch_id in (109,111) or completed_by_branch_id in (109,111))");
							} else {
								$this->db->where("(assigned_to_branch_id =".$_REQUEST["branch_id"]." or completed_by_branch_id = ".$_REQUEST["branch_id"].")");
							}
						}
					}
	
					if ($_REQUEST["report_type"] == "created" && isset($_REQUEST["branch_id"])) {
						if ($_REQUEST["branch_id"] != "") {
							// creator_by_branch_id
							// assigned_to_branch_id
							// completed_by_branch_id
							if ($_REQUEST["branch_id"] == 109) {
								$this->db->where("(creator_by_branch_id in (109,111))");
							} else {
								$this->db->where("(creator_by_branch_id =".$_REQUEST["branch_id"].")");
							}
						}
					}
	
	
	
					$report_main = $this->db->get()->result_array();
					// print_r($this->db->last_query());exit();
					// print_r($report_main);
					// exit();
					$report["data"] = $report_main;
					echo json_encode($report);
					exit();
				}
				else {
					$dummy["data"] = [];
					echo json_encode($dummy);
					exit();
				}
		}
	}


	//new reports : shefeek
	/*public function final_reports()
	{

		$array_final= array();
		
		//$this->db->select("*")->from("all_lead_completed_report");
		//$this->input->post('something')
		
		$this->db->select("first_name,last_name,employee_id");
		$this->db->from("users");
		$this->db->where("users.user_id", $this->auth_user_id);
		$query5 = $this->db->get();
		$staff = $query5->result_array();
		if(!empty($staff)){
			$sname=$staff[0]['first_name'].' '.$staff[0]['last_name'].' ('.$staff[0]['employee_id'].')';
		}
		if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
			// Fetch group IDs for the authenticated user
			$groups = $this->db->select('distinct(group_id)')
								->from("group_members")
								->where("user_id", $this->auth_user_id)
								->get()
								->result_array();
			
			// Extract group IDs into an array
			$group_ids = array_column($groups, 'group_id');
	
			// Fetch distinct user IDs in the same groups
			$members = $this->db->select("distinct(group_members.user_id)")
								->from("group_members")
								->join("users", "users.user_id=group_members.user_id")
								->where_in("group_members.group_id", $group_ids)
								// ->where("users.is_active", 1)
								->get()
								->result_array();
	
			// Extract user IDs into an array
			$staffs1=array();
			if(!empty($members)){ $i=0;
				foreach($members as $mm){
					$this->db->select("first_name,last_name,employee_id");
					$this->db->from("users");
					$this->db->where("users.user_id", $mm['user_id']);
					$query6 = $this->db->get();
					$staffs = $query6->result_array();
					if(!empty($staffs)){
						$snames=$staffs[0]['first_name'].' '.$staff[0]['last_name'].' ('.$staff[0]['employee_id'].')';
						$staffs1[$i]=$snames;
						$i++;
					}
				}
			}
		}
		

		//if ($this->verify_min_level(1)  && ($this->auth_user_role > 5 || $this->auth_user_role == 1)) {
		if ($this->verify_min_level(1)) {
			if($this->input->post("report_type")) {
		
				//Main Leads
				$this->db->select("*")->from("all_lead_completed_report");
				//SELECT * FROM `all_lead_completed_report` where id=34788 or main_lead=34788;



				//Disqualified 
				if ($this->input->post("report_type") == "disqualified") {
					$this->db->where("status","Disqualified");
				}

				if ($this->input->post("report_type") == "completed") {
					$this->db->where("status","completed");
				}
				if ($this->input->post("report_type") == "first_assigned") {
					$this->db->where("first_assigned_to IS NOT NULL");
				}
				if ($this->input->post("report_type") == "completed_other_leads") {
					$this->db->where("invoice!='0'");
					$this->db->where("lead_parent_id",0);
					$this->db->where("total_no_subleads",0);
				}

				if ($this->input->post("report_type") == "processing") {
					$this->db->where("status","Inprogress");
				}
				if ($this->input->post("from_date") != NULL && $this->input->post("from_date") != "") {
					$this->db->where("date(created_time) >=", $this->input->post("from_date"));
				}
				if ($this->input->post("to_date") != NULL && $this->input->post("to_date") != "") {
					$this->db->where("date(created_time) <=", $this->input->post("to_date"));
				}
				
				if ($this->input->post("branch_id")) {
					if ($this->input->post("branch_id") != "" && $this->input->post("branch_id") != "all") {
						$this->db->group_start();  // Start a group for branch conditions
				
						if ($this->input->post("report_type") == "all") {
							//$this->db->where("status", "completed");
						}
				
						if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
							$this->db->where("created_branch", $this->input->post("branch_id"));
							$this->db->or_where("assigned_branch", $this->input->post("branch_id"));
							$this->db->or_where("completed_on_branch", $this->input->post("branch_id"));
							$this->db->or_where_in("created_by", $staffs1);
							$this->db->or_where_in("assigned_to", $staffs1);
							$this->db->or_where_in("completed_by", $staffs1);

						} else if ($this->auth_user_role != 87){
							$this->db->group_start(); 
							$this->db->where("created_by", $sname);
							$this->db->or_where("assigned_to", $sname);
							$this->db->or_where("completed_by", $sname);
							$this->db->group_end();
							
							$this->db->group_start();  // Start another group for user role conditions
							$this->db->where("created_branch", $this->input->post("branch_id"));
							$this->db->or_where("assigned_branch", $this->input->post("branch_id"));
							$this->db->or_where("completed_on_branch", $this->input->post("branch_id"));
							$this->db->group_end();  // End group for user role conditions
						}else{
							$this->db->group_start();  // Start another group for user role conditions
							$this->db->where("created_branch", $this->input->post("branch_id"));
							$this->db->or_where("assigned_branch", $this->input->post("branch_id"));
							$this->db->or_where("completed_on_branch", $this->input->post("branch_id"));
							$this->db->group_end();	
						}
				
						$this->db->group_end();  // End group for branch conditions
					}
				}
				


				//$this->db->where("id","34788");
				//$this->db->where("main_lead","34788");



				// $view_data["request"] = $this->input->post(true);
				$view_data["request"] = $_POST;
				$report_views = $this->db->get()->result();
				// echo $this->db->last_query();
				// echo $this->db->last_query(); exit;

				// echo "<pre>";
				// print_r($report_views);
				// die();

					foreach($report_views as $r){
								$array_final[$r->id]['main_lead'] =$r->main_lead;
								$array_final[$r->id]['sub_lead'] =$r->sub_lead;
								$array_final[$r->id]['customer_name'] =$r->customer_name;
								$array_final[$r->id]['SOURCE'] =$r->SOURCE;
								$array_final[$r->id]['created_by'] =$r->created_by;
								$array_final[$r->id]['created_time'] =$r->created_time;
								$array_final[$r->id]['created_dep'] =$r->created_dep;
								$array_final[$r->id]['created_branch'] =$r->created_branch;
								$array_final[$r->id]['first_assigned_to'] =$r->first_assigned_to;
								$array_final[$r->id]['first_assigned_at'] =$r->first_assigned_to?$r->first_assigned_at:'';
								$array_final[$r->id]['assigned_to'] =$r->assigned_to;
								$array_final[$r->id]['Assigned_time'] =$r->Assigned_time;
								$array_final[$r->id]['assigned_dep'] =$r->assigned_dep;
								$array_final[$r->id]['assigned_branch'] =$r->assigned_branch;
								$array_final[$r->id]['Description'] =$r->Description;
								$array_final[$r->id]['remark'] =$r->remark;
								$array_final[$r->id]['invoice'] =$r->invoice;
								$array_final[$r->id]['status'] =$r->status;
								$array_final[$r->id]['Req_type'] =$r->Req_type;
								$array_final[$r->id]['govt_fee'] =$r->govt_fee;
								$array_final[$r->id]['typing_fee'] =$r->typing_fee;

								$array_final[$r->id]['pos_GovtFees'] =$r->pos_GovtFees;
								$array_final[$r->id]['pos_TypeFee'] =$r->pos_TypeFee;
								$array_final[$r->id]['pos_Card_Amnt'] =$r->pos_Card_Amnt;
								$array_final[$r->id]['pos_Disc_Amnt'] =$r->pos_Disc_Amnt;
								$array_final[$r->id]['pos_Tax_Amnt'] =$r->pos_Tax_Amnt;
								$array_final[$r->id]['pos_Tot_Amt'] =$r->pos_Tot_Amt;
								$array_final[$r->id]['pos_Tot_Revn'] =$r->pos_Tot_Revn;
								$array_final[$r->id]['pos_CreatedBy'] =$r->pos_CreatedBy;

								$array_final[$r->id]['disqualified_remarks'] =$r->disqualified_remarks;
								$array_final[$r->id]['disqualified_date'] =$r->disqualified_date;
								$array_final[$r->id]['disqualified_by'] =$r->disqualified_by;
								$array_final[$r->id]['disqualified_dep'] =$r->disqualified_dep;
								$array_final[$r->id]['disqualified_branch'] =$r->disqualified_branch;
								$array_final[$r->id]['last_action_date'] =$r->last_action_date;


								//if($r->final_collected_amnt>0 && ($r->status_id==305 OR $r->status_id==310 OR $r->status_id==311)) {
								//if($r->status_id==305 OR $r->status_id==310 OR $r->status_id==311) {
									$array_final[$r->id]['completed_time'] =($r->status=='Completed')?$r->completed_time:'';
								//}
									$array_final[$r->id]['final_collected_amnt'] +=$r->final_collected_amnt ;
									
									$array_final[$r->id]['pmt_rec_no'] .=$r->pos_pmt_number.','.$r->pmt_rec_no.',';
									$array_final[$r->id]['completed_by'] =($r->status=='Completed')?$r->completed_by:'';
									$array_final[$r->id]['completed_dep'] =($r->status=='Completed')?$r->completed_on_group:'';
									$array_final[$r->id]['completed_branch'] =($r->status=='Completed')?$r->completed_on_branch:'';	
									$array_final[$r->id]['status'] =$r->status;
									$array_final[$r->id]['Duration'] = ($r->status=='Completed')?$r->Duration:'';
								
									
								//}

								$array_final[$r->id]['lead_source'] =$r->lead_source;
								$array_final[$r->id]['lead_zoho_id'] =$r->lead_zoho_id;
								$array_final[$r->id]['lead_ad_campaign'] =$r->lead_ad_campaign;
								$array_final[$r->id]['lead_ad_campaign_id'] =$r->lead_ad_campaign_id;
								$array_final[$r->id]['lead_zoho_created_by'] =$r->lead_zoho_created_by;
								$array_final[$r->id]['lead_zoho_chat_id'] =$r->lead_zoho_chat_id;
								$array_final[$r->id]['lead_zoho_channel'] =$r->lead_zoho_channel;
								$array_final[$r->id]['lead_from'] =$r->lead_from;
								
								$array_final[$r->id]['alpha_pro_lpo_amount'] =$r->alpha_pro_lpo_amount;
								$array_final[$r->id]['alpha_pro_approx_profit'] =$r->alpha_pro_approx_profit;
					}

			}
			//echo "string";
			//print_r($array_final);
				$this->db->select("users.*,User_Groups.group_name");
				$this->db->from("users");
				$this->db->join("User_Groups", "User_Groups.email = users.email");
				$this->db->where("users.user_id", $this->auth_user_id);
				$query1 = $this->db->get();
				$result1 = $query1->result_array();
				if(!empty($result1)){
					$pa=$result1[0]['group_name'];
					$pa1=$result1[1]['group_name'];
				}else{
					$pa="";
				}
				

			$view_data["reports"] = $array_final;
			$view_data["page_title"] = "Report";
			if($this->auth_user_role == 7 || $this->auth_user_role == 89) {
				if($pa=='Management'||$pa1=='Management'){
					// $view_data["branches"] = $this->db->select("*")->from("ontime_branches")->where("(is_active =1 or id = 100)")->get()->result_array();
					$view_data["branches"] = $this->db->select("ontime_branches.*,groups.group_name")->from("ontime_branches")->join("groups", "groups.group_branch_id = ontime_branches.branch_code")->where("(is_active =1 or id = 100)")->get()->result_array();
				}else{
				
				$this->db->select("ontime_branches.*,groups.group_name");
				$this->db->from("ontime_branches");
				$this->db->join("groups", "groups.group_branch_id = ontime_branches.branch_code");
				$this->db->join("group_members", "group_members.group_id = groups.group_id");
				$this->db->where("group_members.user_id", $this->auth_user_id);
				$this->db->where("(ontime_branches.is_active = 1 OR ontime_branches.id = 100)");
				$this->db->limit(25, 0); // LIMIT 0, 25
				$query = $this->db->get();
				$result = $query->result_array();
				//echo $this->db->last_query();

				$view_data["branches"] = $result;//$this->db->select("*")->from("ontime_branches")->where("(is_active =1 or id = 100)")->get()->result_array();
				}
			}elseif($this->auth_user_role == 87){
				$this->db->select("distinct(`branch_name`),groups.group_name");
				$this->db->from("ontime_branches");
				$this->db->join("groups", "groups.group_branch_id = ontime_branches.branch_code");
				$this->db->join("group_members", "group_members.group_id = groups.group_id");
				$this->db->where("(ontime_branches.is_active = 1 OR ontime_branches.id = 100)");
				$query = $this->db->get();
				$result = $query->result_array();
				$view_data["branches"] = $result;//$this->db->select("*")->from("ontime_branches")->where("(is_active =1 or id = 100)")->get()->result_array();
			}else{
				$this->db->select("ontime_branches.*,groups.group_name");
				$this->db->from("ontime_branches");
				$this->db->join("groups", "groups.group_branch_id = ontime_branches.branch_code");
				$this->db->join("group_members", "group_members.group_id = groups.group_id");
				$this->db->where("group_members.user_id", $this->auth_user_id);
				$this->db->where("(ontime_branches.is_active = 1 OR ontime_branches.id = 100)");
				$this->db->limit(25, 0); // LIMIT 0, 25
				$query = $this->db->get();
				$result = $query->result_array();
				$view_data["branches"] = $result;//$this->db->select("*")->from("ontime_branches")->where("(is_active =1 or id = 100)")->get()->result_array();
			}
			$view_data["user_id"]=$this->auth_user_id;
			$data = array(
				'page_title' => 'Reports ',
				'title' => 'Reports',
				'content' => $this->load->view('reports/reports_all', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		}
	}	*/

	public function final_reports()
	{
		$array_final= array();
		$this->db->select("first_name,last_name,employee_id");
		$this->db->from("users");
		$this->db->where("users.user_id", $this->auth_user_id);
		$query5 = $this->db->get();
		$staff = $query5->result_array();

		if(!empty($staff)){
			$sname=$staff[0]['first_name'].' '.$staff[0]['last_name'].' ('.$staff[0]['employee_id'].')';
		}

		if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
			// Fetch group IDs for the authenticated user
			$groups = $this->db->select('distinct(group_id)')
				->from("group_members")->where("user_id", $this->auth_user_id)
				->get()->result_array();
			
			// Extract group IDs into an array
			$group_ids = array_column($groups, 'group_id');
	
			// Fetch distinct user IDs in the same groups
			$members = $this->db->select("distinct(group_members.user_id)")
				->from("group_members")
				->join("users", "users.user_id=group_members.user_id")
				->where_in("group_members.group_id", $group_ids)
				->where("users.is_active", 1)
				->get()->result_array();

			// echo $this->db->last_query(); exit;
			// Extract user IDs into an array
			$staffs1=array();
			if(!empty($members)){ $i=0;
				foreach($members as $mm){
					$this->db->select("first_name,last_name,employee_id");
					$this->db->from("users");
					$this->db->where("users.user_id", $mm['user_id']);
					$query6 = $this->db->get();
					$staffs = $query6->result_array();
					if(!empty($staffs)){
						$snames=$staffs[0]['first_name'].' '.$staffs[0]['last_name'].' ('.$staffs[0]['employee_id'].')';
						$staffs1[$i]=$snames;
						$i++;
					}
				}
			}
		}

		if ($this->verify_min_level(1)) {
			$user_id = $this->auth_user_id;
			$this->db->select("groups.*");
			$this->db->from("groups");
			$this->db->join("group_members", "group_members.group_id=groups.group_id and group_members.user_id = " . $user_id, "left");
			$this->db->join("users_group_report", "users_group_report.ug_user_id=group_members.user_id and users_group_report.ug_group_id=groups.group_id and users_group_report.ug_user_id = " . $user_id);

			$query = $this->db->get();
			$group_result = $query->result_array();

			if(empty($group_result)){
				$this->db->select("groups.*");
				$this->db->from("groups");
				$this->db->join("group_members", "group_members.group_id=groups.group_id and group_members.user_id = " . $user_id, "left");
				$this->db->where("groups.status=", 1);
				$this->db->where("group_members.user_id", $user_id);
				
				$query = $this->db->get();
				$group_result = $query->result_array();
			}
			
			$view_data["groups"] = $group_result;
			// echo '<pre>';print_r($view_data["groups"]); exit;
			$group_names = array_column($group_result, 'group_name');

			if($this->input->post("report_type")) {
		
				//Main Leads
				// $this->db->select("*")->from("all_lead_completed_report");
				$this->db->select("*")->from("exported_final_report_data");
				// $this->db->select("*")->from("final_report_view");

				$this->db->group_start()
				->where_not_in('lead_source', ['cc-contact'])
				->or_where('lead_source IS NULL', null, false)
				->group_end();

				if ($this->input->post("report_type") == "disqualified") {
					$this->db->where("status","Disqualified");
				}

				if ($this->input->post("report_type") == "completed") {
					$this->db->where("status","completed");
				}
				if ($this->input->post("report_type") == "first_assigned") {
					$this->db->where("first_assigned_to IS NOT NULL");
				}
				if ($this->input->post("report_type") == "completed_other_leads") {
					$this->db->where("invoice!='0'");
					$this->db->where("lead_parent_id",0);
					$this->db->where("total_no_subleads",0);
				}

				if ($this->input->post("report_type") == "processing") {
					$this->db->where("status","Inprogress");
				}
				if ($this->input->post("from_date") != NULL && $this->input->post("from_date") != "") {
					$this->db->where("date(created_time) >=", $this->input->post("from_date"));
				}
				if ($this->input->post("to_date") != NULL && $this->input->post("to_date") != "") {
					$this->db->where("date(created_time) <=", $this->input->post("to_date"));
				}
				
				if($this->auth_user_role != 87){
					$this->db->group_start();  // Start a group for branch conditions
			
					if ($this->auth_user_role == 7 || $this->auth_user_role == 89) {
						if(!empty($this->input->post("group_name")) && $this->input->post("group_name") != "" && $this->input->post("group_name") != NULL){
							$this->db->group_start();  
								$this->db->where("created_dep", $this->input->post("group_name"));
								$this->db->or_where("assigned_dep", $this->input->post("group_name"));
								$this->db->or_where("completed_on_group", $this->input->post("group_name"));
							$this->db->group_end();
						} else {
							$this->db->group_start();  
								$this->db->where_in("created_dep", $group_names);
								$this->db->or_where_in("assigned_dep", $group_names);
								$this->db->or_where_in("completed_on_group", $group_names);
							$this->db->group_end();
						}
						$this->db->group_start();  
							$this->db->or_where_in("created_by", $staffs1);
							$this->db->or_where_in("assigned_to", $staffs1);
							$this->db->or_where_in("completed_by", $staffs1);
						$this->db->group_end();

					} else {
						$this->db->group_start(); 
							$this->db->where("created_by", $sname);
							$this->db->or_where("assigned_to", $sname);
							$this->db->or_where("completed_by", $sname);
						$this->db->group_end();
						if(!empty($this->input->post("group_name")) && $this->input->post("group_name") != "" && $this->input->post("group_name") != NULL){
							$this->db->group_start();  // Start another group for user role conditions
								$this->db->where("created_dep", $this->input->post("group_name"));
								$this->db->or_where("assigned_dep", $this->input->post("group_name"));
								$this->db->or_where("completed_on_group", $this->input->post("group_name"));
							$this->db->group_end();  // End group for user role conditions
						} else {
							$this->db->group_start();
								$this->db->where_in("created_dep", $group_names);
								$this->db->or_where_in("assigned_dep", $group_names);
								$this->db->or_where_in("completed_on_group", $group_names);
							$this->db->group_end();	
						}
					}

					/*else if($this->auth_user_role == 87){
						if(!empty($this->input->post("group_name")) && $this->input->post("group_name") != "" && $this->input->post("group_name") != NULL){
							$this->db->group_start();  // Start another group for user role conditions
								$this->db->where("created_dep", $this->input->post("group_name"));
								$this->db->or_where("assigned_dep", $this->input->post("group_name"));
								$this->db->or_where("completed_on_group", $this->input->post("group_name"));
							$this->db->group_end();	
						} else {
							$this->db->group_start(); 
								$this->db->where_in("created_dep", $group_names);
								$this->db->or_where_in("assigned_dep", $group_names);
								$this->db->or_where_in("completed_on_group", $group_names);
							$this->db->group_end();	
						}
					} */

					$this->db->group_end();  // End group for branch conditions
				}

				$view_data["request"] = $_POST;
				$report_views = $this->db->get()->result();
				if($this->auth_user_id == 1143711453){
					echo $this->db->last_query();exit;
				}
				// echo $this->db->last_query();
				// echo $this->db->last_query(); exit;

				// echo "<pre>";
				// print_r($report_views);
				// die();

				foreach($report_views as $r){
					$array_final[$r->id]['main_lead'] =$r->main_lead;
					$array_final[$r->id]['sub_lead'] =$r->sub_lead;
					$array_final[$r->id]['customer_name'] =$r->customer_name;
					$array_final[$r->id]['SOURCE'] =$r->SOURCE;
					$array_final[$r->id]['created_by'] =$r->created_by;
					$array_final[$r->id]['created_time'] =$r->created_time;
					$array_final[$r->id]['created_dep'] =$r->created_dep;
					$array_final[$r->id]['created_branch'] =$r->created_branch;
					$array_final[$r->id]['first_assigned_to'] =$r->first_assigned_to;
					$array_final[$r->id]['first_assigned_at'] =$r->first_assigned_to?$r->first_assigned_at:'';
					$array_final[$r->id]['assigned_to'] =$r->assigned_to;
					$array_final[$r->id]['Assigned_time'] =$r->Assigned_time;
					$array_final[$r->id]['assigned_dep'] =$r->assigned_dep;
					$array_final[$r->id]['assigned_branch'] =$r->assigned_branch;
					$array_final[$r->id]['Description'] =$r->Description;
					$array_final[$r->id]['remark'] =$r->remark;
					$array_final[$r->id]['invoice'] =$r->invoice;
					$array_final[$r->id]['status'] =$r->status;
					$array_final[$r->id]['Req_type'] =$r->Req_type;
					$array_final[$r->id]['govt_fee'] =$r->govt_fee;
					$array_final[$r->id]['typing_fee'] =$r->typing_fee;

					$array_final[$r->id]['pos_GovtFees'] =$r->pos_GovtFees;
					$array_final[$r->id]['pos_TypeFee'] =$r->pos_TypeFee;
					$array_final[$r->id]['pos_Card_Amnt'] =$r->pos_Card_Amnt;
					$array_final[$r->id]['pos_Disc_Amnt'] =$r->pos_Disc_Amnt;
					$array_final[$r->id]['pos_Tax_Amnt'] =$r->pos_Tax_Amnt;
					$array_final[$r->id]['pos_Tot_Amt'] =$r->pos_Tot_Amt;
					$array_final[$r->id]['pos_Tot_Revn'] =$r->pos_Tot_Revn;
					$array_final[$r->id]['pos_CreatedBy'] =$r->pos_CreatedBy;

					$array_final[$r->id]['disqualified_remarks'] =$r->disqualified_remarks;
					$array_final[$r->id]['disqualified_date'] =$r->disqualified_date;
					$array_final[$r->id]['disqualified_by'] =$r->disqualified_by;
					$array_final[$r->id]['disqualified_dep'] =$r->disqualified_dep;
					$array_final[$r->id]['disqualified_branch'] =$r->disqualified_branch;
					$array_final[$r->id]['last_action_date'] =$r->last_action_date;

					$array_final[$r->id]['completed_time'] =($r->status=='Completed')?$r->completed_time:'';
					$array_final[$r->id]['final_collected_amnt'] +=$r->final_collected_amnt ;
					
					$array_final[$r->id]['pmt_rec_no'] .=$r->pos_pmt_number.','.$r->pmt_rec_no.',';
					$array_final[$r->id]['completed_by'] =($r->status=='Completed')?$r->completed_by:'';
					$array_final[$r->id]['completed_dep'] =($r->status=='Completed')?$r->completed_on_group:'';
					$array_final[$r->id]['completed_branch'] =($r->status=='Completed')?$r->completed_on_branch:'';	
					$array_final[$r->id]['status'] =$r->status;
					$array_final[$r->id]['Duration'] = ($r->status=='Completed')?$r->Duration:'';

					$array_final[$r->id]['lead_source'] =$r->lead_source;
					$array_final[$r->id]['lead_zoho_id'] =$r->lead_zoho_id;
					$array_final[$r->id]['lead_ad_campaign'] =$r->lead_ad_campaign;
					$array_final[$r->id]['lead_ad_campaign_id'] =$r->lead_ad_campaign_id;
					$array_final[$r->id]['lead_zoho_created_by'] =$r->lead_zoho_created_by;
					$array_final[$r->id]['lead_zoho_chat_id'] =$r->lead_zoho_chat_id;
					$array_final[$r->id]['lead_zoho_channel'] =$r->lead_zoho_channel;
					$array_final[$r->id]['lead_from'] =$r->lead_from;
					
					$array_final[$r->id]['alpha_pro_lpo_amount'] =$r->alpha_pro_lpo_amount;
					$array_final[$r->id]['alpha_pro_approx_profit'] =$r->alpha_pro_approx_profit;

					$array_final[$r->id]['is_corporate'] =$r->is_corporate;
					$array_final[$r->id]['applicant_name'] =$r->applicant_name;
				}

			}

			$view_data["reports"] = $array_final;
			$view_data["page_title"] = "Report";


			/* Fetch the group list */
			/*$user_id = $this->auth_user_id;
			$this->db->select("groups.*,IF(group_members.user_id=" . $user_id . ",1,0) as is_selected");
			$this->db->from("groups");
			$this->db->join("group_members", "group_members.group_id=groups.group_id and group_members.user_id = " . $user_id, "left");
			$this->db->where("groups.status=", 1);
			$this->db->where("group_members.user_id", $user_id);
			
			$query = $this->db->get();
			$result = $query->result_array();
			$view_data["groups"] = $result; */

			

			$data = array(
				'page_title' => 'Reports ',
				'title' => 'Reports',
				'content' => $this->load->view('reports/reports_all', $view_data, TRUE),
			);
			$this->load->view('template/base_template_v2', $data);
		}
	}

}