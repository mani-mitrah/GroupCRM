<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kanban extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
        $this->load->helper('datatables');
        date_default_timezone_set('Asia/Dubai');
        $this->load->helper('crypt');
        $this->load->model('app_model');
        $this->load->model('access_model');
        $this->load->model('user_model');
        $this->load->model('master_model');
        $this->load->model('leads_model');
        $this->load->model('authentication_model');
        $this->load->model('kanban_model');
        $this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    }
    public function kanban_leads()
    {
        if ($this->verify_min_level(1)) {
            $start_date =  $this->input->get('start_date') ?? date('Y-m-d', strtotime('-30 days'));
            $end_date =  $this->input->get('end_date') ?? date('Y-m-d');
            $view_data = [];
            $assigned_leads = $this->kanban_model->user_leads($start_date, $end_date);
            $conatcted_one_time = $this->kanban_model->first_attempt_leads($start_date, $end_date);
            $conatcted_two_time = $this->kanban_model->second_attempt_leads($start_date, $end_date);
            $conatcted_third_time = $this->kanban_model->third_attempt_leads($start_date, $end_date);
            $disqualified = $this->kanban_model->disqualified($start_date, $end_date);
            $quotation_prospecting = $this->kanban_model->quotation_prospecting($start_date, $end_date);
            $paid_quoted = $this->kanban_model->paid_quoted($start_date, $end_date);
            $completed_won = $this->kanban_model->completed_won($start_date, $end_date);
            $list_groups = $this->kanban_model->list_groups();
            $group_set = $this->kanban_model->get_lead_groups($start_date, $end_date);
            $unique_groups = [
                'assigned_group' => [],
                'created_group' => []
            ];

            if (!empty($group_set)) {
                $temp_assigned = [];
                $temp_created = [];

                foreach ($group_set as $item) {
                    // Process assigned groups with IDs
                    if (!empty($item['assigned_group'])) {
                        $temp_assigned[$item['assigned_group_id']] = [
                            'id' => $item['assigned_group_id'],
                            'name' => $item['assigned_group']
                        ];
                    }

                    // Process created groups with IDs
                    if (!empty($item['created_group'])) {
                        $temp_created[$item['created_group_id']] = [
                            'id' => $item['created_group_id'],
                            'name' => $item['created_group']
                        ];
                    }
                }

                $unique_groups['assigned_group'] = array_values($temp_assigned);
                $unique_groups['created_group'] = array_values($temp_created);
            }
            // echo '<pre>';var_dump($unique_groups);exit;
            $group_members = $this->kanban_model->group_members();

            // Get or initialize columns from session
            $default_columns = ['todo', 'first', 'second', 'junk'];
            $saved_columns = $this->session->userdata('selected_columns') ?? $default_columns;

            // Pass columns to view
            $view_data['saved_columns'] = $saved_columns;

            $view_data['start_date'] = $start_date;
            $view_data['end_date'] = $end_date;
            $view_data['quotation_prospecting'] = $quotation_prospecting['data'];
            $view_data['paid_quoted'] = $paid_quoted['data'];
            $view_data['completed_won'] = $completed_won['data'];
            $view_data['assigned_leads'] = $assigned_leads['data'];
            // echo '<pre>'; print_r($disqualified['data'],); '</pre>'; die();

            $view_data['conatcted_one_time'] = $conatcted_one_time['data'];
            $view_data['conatcted_two_time'] = $conatcted_two_time['data'];
            $view_data['conatcted_third_time'] = $conatcted_third_time['data'];
            $view_data['disqualified'] = $disqualified['data'];
            $view_data['list_groups'] = $list_groups;
            $view_data['unique_groups'] =  $unique_groups;
            $view_data['group_members'] = $group_members;


            // Initialize cumulative counts
            $combined_counts = [
                'total' => 0,
                'Transaction' => 0,
                'Cross-Sale' => 0,
                'Lead' => 0,
            ];

            // Function to add counts
            $addCounts = function ($source) use (&$combined_counts) {
                foreach ($source['counts'] as $key => $value) {
                    if (isset($combined_counts[$key])) {
                        $combined_counts[$key] += $value;
                    }
                }
            };

            // Add each count group
            $addCounts($assigned_leads);
            $addCounts($conatcted_one_time);
            $addCounts($conatcted_two_time);
            $addCounts($conatcted_third_time);
            $addCounts($disqualified);
            $addCounts($quotation_prospecting);
            $addCounts($paid_quoted);
            $addCounts($completed_won);
            // Pass total counts
            $view_data['counts'] = $combined_counts;
            $data = array(
                'page_title' => 'Leads',
                'title' => 'Leads',
                'content' => $this->load->view('leads/kanban/kanban_leads', $view_data, TRUE),
            );
            $this->load->view('template/kanban_template_v1', $data);
        } else {
            redirect('login');
        }
    }
    public function kanban_view($lead_id = null)
    {
        if ($this->verify_min_level(1)) {
            $view_data = [];

            $view_data['timeline'] = $this->leads_model->lead_timeline($lead_id);
            $view_data['lead_details'] = $this->leads_model->lead_details($lead_id);
            $created_by_user_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'lead_created_by');
            $assigned_by = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_by');
            $assigned_to = $this->mcommon->specific_row_value('leads_assigned', array('lead_id' => $lead_id), 'assigned_to');


            if ($created_by_user_id != $assigned_by) {
                $view_data['lead_assigned_details_excess'] = $this->mcommon->specific_row('users', array('user_id' => $assigned_by));
            }

            $view_data['lead_created_details'] = $this->mcommon->specific_row('users', array('user_id' => $created_by_user_id));
            $view_data['lead_assigned_details'] = $this->mcommon->specific_row('users', array('user_id' => $assigned_to));
            $view_data['upcoming_meetings'] = $this->mcommon->specific_fields_records_all('lead_meetings', array('lead_id' => $lead_id, 'crm_user_id' => $this->auth_user_id, 'is_complete' => 0));
            $view_data['lead_attachments'] = $this->mcommon->specific_fields_records_all('lead_attachments', array('lead_id' => $lead_id));
            // $view_data['complete_actions'] = $this->mcommon->specific_fields_records_all('lead_actions', array('id >' => 409, 'id <=' => 411));
            $view_data['auth_user_privilege'] = $this->mcommon->specific_row('m_roles_privilege', array('role_id' => $this->auth_user_role));
            $view_data['auth_user_data'] = $this->authentication_model->get_user_data($this->auth_user_id);
            $view_data['lead_status_304_remarks'] = $this->mcommon->specific_fields_records_all('lead_action_log', array('lead_id' => $lead_id, 'status_id' => 304));
            $lead_ids_raw =  $this->kanban_model->get_lead_id($lead_id);
            $lead_ids = array_column($lead_ids_raw, 'id');
            $view_data["latest_lead_action_open"] = $this->db->select("lead_action_log.lead_id,lead_action_log.action_on,lead_actions.action_name,lead_actions.id as action_id,lead_action_log.remarks as log_remarks")->from("lead_action_log")->join("leads", "leads.id = lead_action_log.lead_id")->join("lead_actions", "lead_actions.id=lead_action_log.action_id")->where_in('lead_id', $lead_ids)->where_not_in("lead_action_log.status_id", [305, 306, 623])->order_by("lead_action_log.action_on", "desc")->limit(5)->get()->result_array(); //->where("lead_action_log.action_by", $this->auth_user_id)
            $view_data["latest_lead_action_closed"] = $this->db->select("lead_action_log.lead_id,lead_action_log.action_on,lead_actions.action_name,lead_actions.id as action_id,lead_action_log.remarks as log_remarks")->from("lead_action_log")->join("leads", "leads.id = lead_action_log.lead_id")->join("lead_actions", "lead_actions.id=lead_action_log.action_id")->where_in('lead_id', $lead_ids)->where_in("lead_action_log.status_id", [305, 623])->order_by("lead_action_log.action_on", "desc")->limit(5)->get()->result_array(); //->where("lead_action_log.action_by", $this->auth_user_id)

            $lead_packages = $this->mcommon->specific_fields_records_all('lead_package_details', ['lead_id' => $lead_id]);

            // 1. Collect all unique service IDs from packages
            $all_service_ids = [];
            foreach ($lead_packages as $pkg) {
                if (!empty($pkg['service_id'])) {
                    $ids = explode(',', $pkg['service_id']); // handle multiple service_ids
                    foreach ($ids as $sid) {
                        $all_service_ids[] = trim($sid);
                    }
                }
            }
            $all_service_ids = array_unique($all_service_ids);
            if (!empty($all_service_ids)) {
                // 2. Get all related services
                $lead_services = $this->mcommon->specific_fields_records_all(
                    'ontime_category_services_',
                    ['service_id IN (' . implode(',', $all_service_ids) . ')' => null]
                );
            }

            // 3. Map service_id to service_name
            $service_map = [];
            foreach ($lead_services as $service) {
                $service_map[$service['id']] = $service['service_name'];
            }

            // 4. Prepare output arrays
            $package_fees_array = [];
            $package_service_names_array = [];
            $package_created_by_array = [];
            $user_name_map = [];

            foreach ($lead_packages as $package) {
                // --- Fees Array ---
                $package_fees_array[] = [
                    'gov_fee'           => $package['gov_fee'] ?? 0,
                    'typing_fee'        => $package['typing_fee'] ?? 0,
                    'card_amount'       => $package['card_amount'] ?? 0,
                    'sub_total'         => $package['sub_total'] ?? 0,
                    'payment_type'      => $package['payment_type'] ?? '',
                    'is_direct_invoice' => $package['is_direct_invoice'] ?? 0,
                ];

                // --- Service Names ---
                $service_ids = !empty($package['service_id']) ? explode(',', $package['service_id']) : [];
                $names = [];
                foreach ($service_ids as $sid) {
                    $sid = trim($sid);
                    if (isset($service_map[$sid])) {
                        $names[] = $service_map[$sid];
                    }
                }
                $package_service_names_array[] = !empty($names) ? implode(', ', $names) : 'Unknown Service';

                // --- Created By Name ---
                $created_by = $package['created_by'] ?? null;
                if (!isset($user_name_map[$created_by])) {
                    $user_data_firstname = $this->mcommon->specific_row_value('users', ['user_id' => $created_by], 'first_name');
                    $user_data_lastname = $this->mcommon->specific_row_value('users', ['user_id' => $created_by], 'last_name');

                    $user_name_map[$created_by] = !empty($user_data)
                        ? trim($user_data_firstname . ' ' . $user_data_lastname)
                        : 'Unknown User';
                }

                $package_created_by_array[] = $user_name_map[$created_by];
            }

            // 5. Final Output to View
            $view_data['package_fees_array'] = $package_fees_array;
            $view_data['package_service_names_array'] = $package_service_names_array;
            $view_data['package_created_by_array'] = $package_created_by_array;

            // $email_request_id = $this->mcommon->specific_row_value('leads', array('id' => $lead_id), 'email_request_id');
            // if (!empty($email_request_id)) {
            //     $email_request_id = (int) $email_request_id;
            //     $get_convo = $this->request_conversation($email_request_id);
            //     $view_data['conversations'] = $get_convo;
            //     $view_data['request_id'] = $email_request_id;
            // }

            $data = array(
                'page_title' => 'Deals',
                'title' => 'Deals',
                'content' => $this->load->view('leads/kanban/view_v3', $view_data, TRUE),
            );
            $this->load->view('template/base_template_modal_v3', $data);
        } else {
            redirect('login');
        }
    }

    public function request_conversation($request_id)
    {
        // $url = 'https://94.200.55.118:2080/api/v3/requests/'.$request_id.'/conversations';
        // $api_key = 'D488A202-F17D-41FE-A52B-11973561539B';

        $url = 'https://94.200.55.118:5000/api/v3/requests/' . $request_id . '/conversations';
        $api_key = '171016F2-9943-418C-AD51-56E7E7C7DF4E';

        $headers = [
            "authtoken: $api_key"
        ];
        $ch = curl_init();
        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url); // Use the URL with query parameters
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set the headers
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (like verify=False in requests)
        // Execute the cURL session
        $response = curl_exec($ch);
        // echo '<pre>';print_r($response);exit;
        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
        } else {
            // Print the response
            $result = json_decode($response);
            $conversations = $result->conversations;
            return $conversations;
        }

        curl_close($ch);
    }

    public function get_columns()
    {
        $columns = $this->session->userdata('selected_columns') ?? ['todo', 'first', 'second', 'junk'];
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['columns' => $columns]));
    }
    public function save_columns()
    {
        $columns = $this->input->post('columns');
        $page = $this->input->post('page');
        if ($page == 'kanban_gc') {
            $default_columns = ['paid_leads', 'admin_leads', 'dld_leads', 'visa_leads', 'eid_leads', 'moh_leads'];
        } else {
            $default_columns = ['todo', 'first', 'second', 'third'];
        }
        $columns_to_save = ($columns) ?? $default_columns;
        $column = ($page == 'kanban_gc') ? 'kanban_columns' : 'selected_columns';

        $this->session->set_userdata($column, $columns_to_save);
        echo json_encode(['status' => 'success', 'columns' => $columns_to_save]);
    }
    public function get_assigned_users()
    {
        $group_ids = $this->input->post('group_ids');

        // Validate input
        if (empty($group_ids)) {
            echo json_encode(['status' => 'error', 'message' => 'No groups selected']);
            return;
        }

        // Extract numeric IDs from "group_XX" format
        $clean_ids = [];
        foreach ($group_ids as $group_id) {
            // Split the string and get numeric part
            $parts = explode('_', $group_id);
            if (count($parts) === 2 && is_numeric($parts[1])) {
                $clean_ids[] = (int)$parts[1];
            }
        }

        // Remove duplicates and empty values
        $clean_ids = array_unique(array_filter($clean_ids));

        if (empty($clean_ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid group selection']);
            return;
        }

        $group_members = $this->kanban_model->group_members($clean_ids);
        echo json_encode(['status' => 'success', 'group_members' => $group_members]);
    }

    public function kanban_gc()
    {
        if ($this->verify_min_level(1)) {
            $start_date =  $this->input->get('start_date') ?? date('Y-m-d', strtotime('-30 days'));
            $end_date =  $this->input->get('end_date') ?? date('Y-m-d');
            $view_data = [];
            $gc_leads = $this->kanban_model->get_gc_leads($start_date, $end_date);
            
            $results = [
                'paid_leads' => [],
                'admin_leads' => [],
                'dld_leads' => [],
                'visa_leads' => [],
                'eid_leads' => [],
                'moh_leads' => [],
                'other_leads' => [],
                'custom_leads' => [],
                'completed_leads' => [],
            ];

            foreach ($gc_leads['data'] as $row) {
                $lead_ids = explode(',', $row['sub_lead_ids']);
                $sub_leads = $this->kanban_model->get_leads_status($lead_ids);

                $sub_lead_type = [];
                $grouped = [
                    'id' => [],
                    'msd_key' => [],
                    'lead_status' => [],
                    'remarks' => []
                ];

                foreach ($sub_leads as $row2) {
                    $grouped['id'][] = $row2['id'];
                    $grouped['lead_category'][$row2['id']] = $row2['lead_category'];
                    $grouped['lead_status'][$row2['id']] = $row2['lead_status'];
                    $grouped['remarks'][$row2['id']] = $row2['remarks'];
                    $grouped['msd_key'][$row2['id']] = $row2['msd_key'];
                    $grouped['sla'][$row2['id']] = $row2['sla'];
                    $grouped['lead_added_on'][$row2['id']] = $row2['lead_added_on'];
                    $sub_lead_type[] = $row2['lead_category'];
                }
                $contactable_dates = [];
                $missingCategory = [];
                foreach ($grouped['lead_category'] as $key => $lead_category) {
                    if ($grouped['lead_status'][$key] == 301) {
                        $days = ($grouped['sla'][$key] == 0 || $grouped['sla'][$key] == '') ? ' +2 days' : ('+' . $grouped['sla'][$key] . 'days');
                        $contactable_dates[$lead_category] = date('Y-m-d H:i:s', strtotime($grouped['lead_added_on'][$key] . $days));
                        $missingCategory[] = $lead_category;
                    }
                }

                $row['total_no_subleads'] = count($grouped['id']);

                $row['msd_key'] = $grouped['msd_key'];
                $row['remarks'] = $grouped['remarks'];
                $row['lead_type'] = $grouped['lead_category'];
                $row['sub_leads_status'] = $grouped['lead_status'];
                $row['no_of_open_subleads'] = count(array_filter($grouped['lead_status'], function ($status) {
                    return $status != 305;
                }));
                $row['no_of_closed_subleads'] = count(array_filter($grouped['lead_status'], function ($status) {
                    return $status == 305;
                }));
                $leadCategory = explode(",", $row['lead_category']);
                $custom_order = ['admin_leads', 'dld_leads', 'visa_leads', 'eid_leads', 'moh_leads', 'custom_leads', 'other_leads'];

                $order_map = array_flip($custom_order);
                usort($sub_lead_type, function ($a, $b) use ($order_map) {
                    return ($order_map[$a] ?? PHP_INT_MAX) <=> ($order_map[$b] ?? PHP_INT_MAX);
                });

                $category = '';
                if ((count($leadCategory) == 1 && $leadCategory[0] == 'paid_leads' && $row['no_of_open_subleads'] != 0)) {
                    $category = 'paid_leads';
                } else if (!(in_array('paid_leads', $leadCategory)) || $row['no_of_open_subleads'] == 0) {
                    $category = 'completed_leads';
                } else {
                    if (count($missingCategory) > 1) {
                        foreach ($custom_order as $lead) {
                            $category = '';
                            if (in_array($lead, $missingCategory)) {
                                $category = $lead;
                                break;
                            }
                        }
                    } else {
                        $category = $missingCategory[0];
                    }
                }

                $row['contactable_date'] = ($category != 'completed_leads' && $category != 'paid_leads') ? $contactable_dates[$category] : $row['due_date'];
                $results[$category][] = $row;
            }
            $total_amount = 0;
            $completed_leads = $results['completed_leads'];
            if (!empty($completed_leads)) {
                foreach ($completed_leads as $key => $completed_lead) {
                    $total_amount += $completed_lead['amount'];
                }
            }
            $group_set = $this->kanban_model->get_lead_groups($start_date, $end_date);
            $unique_groups = [
                'assigned_group' => [],
                'created_group' => []
            ];

            if (!empty($group_set)) {
                $temp_assigned = [];
                $temp_created = [];

                foreach ($group_set as $item) {
                    if (!empty($item['assigned_group'])) {
                        $temp_assigned[$item['assigned_group_id']] = [
                            'id' => $item['assigned_group_id'],
                            'name' => $item['assigned_group']
                        ];
                    }

                    // Process created groups with IDs
                    if (!empty($item['created_group'])) {
                        $temp_created[$item['created_group_id']] = [
                            'id' => $item['created_group_id'],
                            'name' => $item['created_group']
                        ];
                    }
                }

                $unique_groups['assigned_group'] = array_values($temp_assigned);
                $unique_groups['created_group'] = array_values($temp_created);
            }
            $group_members = $this->kanban_model->group_members();

            // Get or initialize columns from session
            $default_columns = ['paid_leads', 'admin_leads', 'dld_leads', 'visa_leads', 'eid_leads', 'moh_leads', 'custom_leads', 'completed_leads', 'other_leads'];
            if (!$this->session->userdata('kanban_columns')) {
                $this->session->set_userdata('kanban_columns', $default_columns);
            }
            $saved_columns = $this->session->userdata('kanban_columns') ?? $default_columns;

            // Pass columns to view
            $view_data['saved_columns'] = $saved_columns;

            $view_data['start_date'] = $start_date;
            $view_data['end_date'] = $end_date;
            $view_data['gc_leads'] = $results;
            $view_data['total_amount'] = $total_amount;
            $view_data['unique_groups'] =  $unique_groups;
            $view_data['group_members'] = $group_members;

            // Initialize cumulative counts
            $combined_counts = [
                'total' => 0,
                'Transaction' => 0,
                'Cross-Sale' => 0,
                'Lead' => 0,
            ];

            // Function to add counts
            $addCounts = function ($source) use (&$combined_counts) {
                foreach ($source['counts'] as $key => $value) {
                    if (isset($combined_counts[$key])) {
                        $combined_counts[$key] += $value;
                    }
                }
            };

            $addCounts($gc_leads);
            $view_data['counts'] = $combined_counts;
            $data = array(
                'page_title' => 'Leads',
                'title' => 'Leads',
                'content' => $this->load->view('leads/kanban/kanban_gc_leads', $view_data, TRUE),
            );
            $this->load->view('template/kanban_template_v1', $data);
        } else {
            redirect('login');
        }
    }
    public function get_kgc_columns()
    {
        $columns = $this->session->userdata('kanban_columns') ??  ['paid_leads', 'admin_leads', 'dld_leads', 'visa_leads', 'eid_leads', 'moh_leads'];
        $this->output->set_content_type('application/json')->set_output(json_encode(['columns' => $columns]));
    }
    public function export_data()
    {
        $lead_ids = explode(",", $_POST['lead_ids']);
        $lead_type = $_POST['lead_type'];
        $url = $_POST['url'];
        if (empty(array_filter($lead_ids))) {
            $this->session->set_flashdata('alert', 'danger');
            $this->session->set_flashdata('alert_danger', 'No data found');
            redirect('/leads/kanban/' . $url);
        }
        $this->db->select('*');
        $this->db->from('exported_final_report_data');
        $this->db->where_in('main_lead ', $lead_ids);
        $this->db->or_where_in('lead_parent_id', $lead_ids);
        $query = $this->db->get();
        $result = $query->result_array();
        if (empty($result)) {
            $this->session->set_flashdata('alert_danger', 'No data found');
            redirect('/leads/kanban/' . $url);
        }
        $this->session->set_flashdata('alert_danger', '');
        $filename =  $lead_type . 'export' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Open PHP output stream
        $output = fopen('php://output', 'w');

        // Write column headers
        fputcsv($output, array_keys($result[0]));

        // Write data rows
        foreach ($result as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
}
