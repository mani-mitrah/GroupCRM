<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Agent extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        //require(APPPATH.'libraries/Agent.php');
        $this->load->model('app_model');
    }

    /* written by 007 */
    public function expand_get($agent_id='')
    {
    	$agent = $this->app_model->get_agent($agent_id);
    	if(!empty($agent)) {
    		return $this->response($agent, 200);
    	} else {
    		return $this->response(array("no agent found"), 404);
    	}
    }
}