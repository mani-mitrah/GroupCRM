<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->is_logged_in();
    }

    public function index()
    {
        if( ! empty( $this->auth_role ) )
        {  
            if($this->auth_role=='agent')
            {
            	$view_data = array();
                $data = array(
                    'title' => 'Live Chat',
                    'content' => $this->load->view('pages/agent_dashboard', $view_data, TRUE),
                );
                $this->load->view('base/agent_template', $data);
            }
            else if($this->auth_role=='admin')
            {
                echo $this->auth_username; 
            }
            else
            {
                echo $this->auth_username;    
            }
        }
        else
        {
        	redirect('login','refresh');
        }
    }

    public function listener()
    {
       
        $this->load->view('pages/listener', TRUE);
            
    }

    public function receive($msg)
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        //@extract($_POST);
        //$value = $this->input->post();
        
            //$time = date('r');
        
        //echo 'hai {$_POST["id"]} \n \n';
        //$time = date('r');
        //$date = date('Y-m-d H:i:s');
        //$id = ' Ganesh'.$message_text;
        echo "data: The server time is: {$msg}\n\n";
        flush();
        
    }


}