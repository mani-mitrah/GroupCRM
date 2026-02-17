<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exceptiondate extends MY_Controller
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
			$view_data['page_title'] = 'Exceptional Dates';
			$view_data['default'] = $this->mm->get_exceptiondates();
			// print_r($view_data);
			// exit();
			$data = array(
				'title' => 'Manage TimeSlots',
				'content' => $this->load->view('admin/exceptiondate/show', $view_data, TRUE),
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

                    $timeslot_except_date = $this->input->post('timeslot_except_date');
                    $timeslot_except_desc = trim($this->input->post('timeslot_except_desc',true));

                    $exist = $this->db->select("*")->from("crm_timeslot_exception")->where("timeslot_except_date", $timeslot_except_date)->get()->num_rows();

                    if ($exist > 0) {
                        $this->session->set_flashdata('alert', array('message' => "Exceptional Date already exist", 'class' => 'danger'));
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                    //prepare insert array
                    $insert_array = array(
                        'timeslot_except_date' => $timeslot_except_date,
                        'timeslot_except_desc' => $timeslot_except_desc,
                        'updated_by' => $_SESSION["user_id"]
                    );
                    $data = $this->db->insert('crm_timeslot_exception', $insert_array);
                    $data_id = $this->db->insert_id();
                    if ($data_id) {
                        $this->session->set_flashdata('alert', array('message' => "Exceptional Date Successfully Added", 'class' => 'success'));
                    } else {
                        $this->session->set_flashdata('alert', array('message' => "Something went Wrong. Kindly check the Slot", 'class' => 'warning'));
                    }
                    return redirect('admin/exceptiondate');
                } catch (Exception $e) {
                    $this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
                    return redirect("admin/exceptiondate/create");
                }
            }
            
            $view_data = array();
            $view_data['page_title'] = 'Exception Date - Creation';
            $data = array(
                'title' => 'Exception Date - Creation',
                'content' => $this->load->view('admin/exceptiondate/add', $view_data, TRUE),
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
    //                 $timeslot_except_date = $this->input->post('timeslot_except_date');
    //                 $timeslot_except_desc = $this->input->post('timeslot_except_desc');

    //                 $exist = $this->db->select("*")->from("crm_timeslot_exception")->where("timeslot_except_date", $timeslot_except_date)->get()->num_rows();

    //                 if ($exist > 0) {
    //                     $this->session->set_flashdata('alert', array('message' => "Exceptional Date already exist", 'class' => 'danger'));
    //                     redirect($_SERVER['HTTP_REFERER']);
    //                 }
    //                 //prepare insert array
    //                 $insert_array = array(
    //                     'timeslot_except_date' => $timeslot_except_date,
    //                     'timeslot_except_desc' => $timeslot_except_desc,
    //                     'updated_by' => $_SESSION["user_id"]
    //                 );

    //                 $data = $this->db->update('crm_timeslot_exception', $insert_array,["timeslot_except_id"=>$id]);
    //                 if ($data) {
    //                     $this->session->set_flashdata('alert', array('message' => "Successfully Updated", 'class' => 'success'));
    //                 } else {
    //                     $this->session->set_flashdata('alert', array('message' => "Something went Wrong. Kindly check the Date", 'class' => 'warning'));
    //                     return redirect('admin/exceptiondate/edit/'.$id);
    //                 }
    //                 return redirect('admin/exceptiondate');
    //             } catch (Exception $e) {
    //                 $this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
    //                 return redirect("admin/exceptiondate/create");
    //             }
    //         }
    //         // echo $id;
    //         // exit();

    //         $view_data["default"] = $this->mm->get_exceptiondate($id);
            
    //         $view_data["page_title"] = "Edit Exception Date";
    //         $data = array(
    //             'title' => 'Edit Exception Date',
    //             'page_title' => 'Edit Exception Date',
    //             'content' => $this->load->view('admin/exceptiondate/edit', $view_data, TRUE),
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
                    $timeslot_except_date = $this->input->post('timeslot_except_date');
                    $timeslot_except_desc = trim($this->input->post('timeslot_except_desc',true));


                    $exist = $this->db
                        ->select("*")
                        ->from("crm_timeslot_exception")
                        ->where("timeslot_except_date", $timeslot_except_date)
                        ->where("timeslot_except_id !=", $id) // Exclude the current record
                        ->get()
                        ->num_rows();

                    if ($exist > 0) {
                        $this->session->set_flashdata('alert', array('message' => "Exceptional Date already exists", 'class' => 'danger'));
                        redirect($_SERVER['HTTP_REFERER']);
                    }

                    // Prepare insert array
                    $insert_array = array(
                        'timeslot_except_date' => $timeslot_except_date,
                        'timeslot_except_desc' => $timeslot_except_desc,
                        'updated_by' => $_SESSION["user_id"]
                    );

                    $data = $this->db->update('crm_timeslot_exception', $insert_array, ["timeslot_except_id" => $id]);
                    if ($data) {
                        $this->session->set_flashdata('alert', array('message' => "Successfully Updated", 'class' => 'success'));
                    } else {
                        $this->session->set_flashdata('alert', array('message' => "Something went wrong. Kindly check the Date", 'class' => 'warning'));
                        return redirect('admin/exceptiondate/edit/' . $id);
                    }
                    return redirect('admin/exceptiondate');
                } catch (Exception $e) {
                    $this->session->set_flashdata('alert', array('message' => $e->getMessage(), 'class' => 'danger'));
                    return redirect("admin/exceptiondate/create");
                }
            }

            $view_data["default"] = $this->mm->get_exceptiondate($id);

            $view_data["page_title"] = "Edit Exception Date";
            $data = array(
                'title' => 'Edit Exception Date',
                'page_title' => 'Edit Exception Date',
                'content' => $this->load->view('admin/exceptiondate/edit', $view_data, TRUE),
            );
            $this->load->view('template/base_template', $data);
        } else {
            redirect('login');
        }
    }
	public function status_change($id)
	{
		$current_status = $this->mcommon->specific_row_value('crm_timeslot_exception', array('timeslot_except_id' => $id), 'status');
		$change_status = ($current_status == 1) ? 0 : 1;
		$delete = $this->mcommon->common_edit('crm_timeslot_exception', array('status' => $change_status), array('timeslot_except_id' => $id));
		$this->session->set_flashdata('alert', array('message' => "Status Successfully Updated", 'class' => 'success'));

		redirect('admin/exceptiondate');
	}

	public function dates(){
		$this->db->select('date_format(timeslot_except_date,"%Y-%m-%d") as dates');
        $this->db->from("crm_timeslot_exception");
		$this->db->where("crm_timeslot_exception.status",1);
		$query = $this->db->get()->result_array();
		$exception = [];
		foreach($query as $q){
			array_push($exception,$q["dates"]);
		}
		echo json_encode($exception);
	}

}
