<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Timeslot extends MY_Controller
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
			$view_data['page_title'] = 'Time Slots';
			$view_data['timeslots'] = $this->mm->get_timeslots();
			// print_r($view_data);
			// exit();
			$data = array(
				'title' => 'Manage TimeSlots',
				'content' => $this->load->view('admin/timeslots/show', $view_data, TRUE),
			);
			$this->load->view('template/base_template', $data);
		} else {
			redirect('login');
		}
	}

	public function create()
    {
        if ($this->verify_min_level(1)) {

            if (isset($_POST['submit'])) {
                try {

                    $timeslot_name = trim($this->input->post('timeslot_name',true));
                    $timeslot_desc = trim($this->input->post('timeslot_desc',true));

                    $exist = $this->db->select("*")->from("crm_timeslots")->where("timeslot_name", $timeslot_name)->get()->num_rows();
                    
                    if ($exist > 0) {
                        $this->session->set_flashdata('alert', array('message' => "Timeslot already exist", 'class' => 'danger'));
                        redirect($_SERVER['HTTP_REFERER']);
                    }

                    //prepare insert array
                    $insert_array = array(
                        'timeslot_name' => $timeslot_name,
                        'timeslot_desc' => $timeslot_desc,
                        'updated_by' => $_SESSION["user_id"]
                    );
                    
                    $data = $this->db->insert('crm_timeslots', $insert_array);
                    $data_id = $this->db->insert_id();
                    $this->session->set_flashdata('alert', array('message' => "Timeslot Successfully Added", 'class' => 'success'));



                    return redirect('/admin/timeslot');
                } catch (Exception $e) {
                    $this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
                    return redirect("/admin/timeslot/create");
                }
            }
            
            $view_data = array();
            $view_data['page_title'] = 'TimeSlots - Creation';
            $data = array(
                'title' => 'Timeslot - Creation',
                'content' => $this->load->view('admin/timeslots/add', $view_data, TRUE),
            );

            $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }
	// public function edit($id)
    // {
    //     if ($this->verify_min_level(1)) {
    //         $view_data = array();


    //         if (isset($_POST['submit'])) {
    //             try {

    //                 $timeslot_name = $this->input->post('timeslot_name');
    //                 $timeslot_desc = $this->input->post('timeslot_desc');

    //                 $exist = $this->db->select("*")->from("crm_timeslots")->where("timeslot_name", $timeslot_name)->get()->num_rows();
                    
    //                 if ($exist > 0) {
    //                     $this->session->set_flashdata('alert', array('message' => "Timeslot already exist", 'class' => 'danger'));
    //                     redirect($_SERVER['HTTP_REFERER']);
    //                 }
    //                 //prepare insert array
    //                 $insert_array = array(
    //                     'timeslot_name' => $timeslot_name,
    //                     'timeslot_desc' => $timeslot_desc,
    //                     'updated_by' => $_SESSION["user_id"]
    //                 );
    //                 $data = $this->db->update('crm_timeslots', $insert_array,["timeslot_id"=>$id]);
    //                 $this->session->set_flashdata('alert', array('message' => "Successfully Updated", 'class' => 'success'));

    //                 return redirect('/admin/timeslot');
    //             } catch (Exception $e) {
    //                 $this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
    //                 return redirect("/admin/timeslot/create");
    //             }
    //         }
    //         // echo $id;
    //         // exit();

    //         $view_data["default"] = $this->mm->get_timeslot($id);
            
    //         $view_data["page_title"] = "Edit Timeslot";
    //         $data = array(
    //             'title' => 'Edit Timeslot',
    //             'page_title' => 'Edit Timeslot',
    //             'content' => $this->load->view('admin/timeslots/edit', $view_data, TRUE),
    //         );
    //         $this->load->view('template/base_template', $data);
    //     } else {
    //         redirect('login');
    //     }
    // }
    public function edit($id)
    {
        if ($this->verify_min_level(1)) {
            $view_data = array();

            if (isset($_POST['submit'])) {
                try {
                    $timeslot_name = trim($this->input->post('timeslot_name',true));
                    $timeslot_desc = trim($this->input->post('timeslot_desc',true));

                    
                    $exist = $this->db
                        ->select("*")
                        ->from("crm_timeslots")
                        ->where("timeslot_name", $timeslot_name)
                        ->where("timeslot_id !=", $id) 
                        ->get()
                        ->num_rows();

                    if ($exist > 0) {
                        $this->session->set_flashdata('alert', array('message' => "Timeslot already exists", 'class' => 'danger'));
                        redirect($_SERVER['HTTP_REFERER']);
                    }

                    // Prepare update array
                    $insert_array = array(
                        'timeslot_name' => $timeslot_name,
                        'timeslot_desc' => $timeslot_desc,
                        'updated_by' => $_SESSION["user_id"]
                    );

                    // Update timeslot in the database
                    $data = $this->db->update('crm_timeslots', $insert_array, ["timeslot_id" => $id]);
                    $this->session->set_flashdata('alert', array('message' => "Successfully Updated", 'class' => 'success'));

                    return redirect('/admin/timeslot');
                } catch (Exception $e) {
                    $this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
                    return redirect("/admin/timeslot/create");
                }
            }

            // Fetch current timeslot data
            $view_data["default"] = $this->mm->get_timeslot($id);

            $view_data["page_title"] = "Edit Timeslot";
            $data = array(
                'title' => 'Edit Timeslot',
                'page_title' => 'Edit Timeslot',
                'content' => $this->load->view('admin/timeslots/edit', $view_data, TRUE),
            );
            $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }
	public function status_change($id)
	{
		$current_status = $this->mcommon->specific_row_value('crm_timeslots', array('timeslot_id' => $id), 'status');
		$change_status = ($current_status == 1) ? 0 : 1;
		$delete = $this->mcommon->common_edit('crm_timeslots', array('status' => $change_status), array('timeslot_id' => $id));
		$this->session->set_flashdata('alert', array('message' => "Status Successfully Updated", 'class' => 'success'));

		redirect('/admin/timeslot');
	}

}
