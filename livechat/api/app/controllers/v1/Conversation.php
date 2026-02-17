<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class Conversation extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        require(APPPATH.'libraries/Conv.php');
        require(APPPATH.'libraries/Message.php');
        $this->load->helper('curl');
        $this->load->model('app_model');
    }

    //mysql
    public function ongoing_get($agent_id)
    {
        $agent_conversations = $this->app_model->get_ongoing_conversations($agent_id);

        if(!empty($agent_conversations))
        {
            return $this->response($agent_conversations,200);
        }
        else
        {
            return $this->response("no conversations",404);
        }
    }

    //pgsql
    public function expand_get($conv_id)
    {
        //$conv_id = $this->get('conv_id');
        $msg_array = array();
        $msg_results = $this->postgres->expand_conversation($conv_id);
        foreach ($msg_results as $key => $value) {
            $msg_object = new Message();
            $msg_object->set_id($value['id']);
            $msg_object->set_text($value['message_text']);
            $msg_object->set_type($value['message_type']);
            $name = $value['full_name'];
            // Check [payload] if message is sent by customer or agent
            if($name=='User') {
                $payload_json = json_decode($value['payload']);
                if(isset($payload_json->agent)) {
                    $name = 'Agent';
                    $msg_object->set_agent($payload_json->agent);
                    $msg_object->set_agent_name($payload_json->agent_name);
                }
            }
            $msg_object->set_name($name);
            $msg_object->set_timestamp($value['sent_on']);
            $msg_array[] = $msg_object;
        }

        if(!empty($msg_array))
        {
            $this->response($msg_array, 200);

        } else {
            $error_array = array('status' => 'error', 'message' => 'no messages in conversation');
            $this->response($error_array, 404);
        }
    }

    public function recent_messages_post() {
        $conv_id = $this->post("conv_id");
        $timestamp = $this->post("timestamp");

        $msg_array = array();

        // convert timestamp format
        //$time = strtotime($timestamp);
        //$new_format = date('Y-m-d H:i:s',$time);

        $where_string = "sent_on >'".$timestamp."' AND conversationId='".$conv_id."'";
        //$where_string = "botId='".$bot_id."'";
        $all_convs = $this->postgres->specific_fields_records_all('web_messages',$where_string);
        
        foreach ($all_convs as $key => $value) {
            $msg_object = new Message();
            $msg_object->set_id($value['id']);
            $msg_object->set_text($value['message_text']);
            $msg_object->set_type($value['message_type']);
            $name = $value['full_name'];
            // Check [payload] if message is sent by customer or agent
            if($name=='User') {
                $payload_json = json_decode($value['payload']);
                if(isset($payload_json->agent)) {
                    $name = 'Agent';
                    $msg_object->set_agent($payload_json->agent);
                    $msg_object->set_agent_name($payload_json->agent_name);
                }
            }
            $msg_object->set_name($name);
            $msg_object->set_timestamp($value['sent_on']);
            $msg_array[] = $msg_object;
        }
        return $this->response($msg_array, 200);
    }

    public function last_message_get($conv_id)
    {
        $msg_array = array();
        $msg_results = $this->postgres->last_message($conv_id);
        foreach ($msg_results as $key => $value) {
            $msg_object = new Message();
            $msg_object->set_id($value['id']);
            $msg_object->set_text($value['message_text']);
            $msg_object->set_type($value['message_type']);
            $msg_object->set_name($value['full_name']);
            $msg_object->set_timestamp($value['sent_on']);
            $msg_array[] = $msg_object;
        }

        if(!empty($msg_array))
        {
            $this->response($msg_array, 200);

        } else {
            $error_array = array('status' => 'error', 'message' => 'no messages in conversation');
            $this->response($error_array, 404);
        }
    }

    public function pause_post() {
        $bot_id = $this->post("bot_id");
        $user_id = $this->post("user_id");
        $conv_id = $this->post("conv_id");
        $name = $this->post("name");
        $email = $this->post("email");
        $mobile = $this->post("mobile");

        $pause_url = config_item('session_pause_url');
        $pause_url = str_replace("{user_id}", $user_id, $pause_url);
        $pause_url = str_replace("{bot_id}", $bot_id, $pause_url);
        $status_code = send_post_request($pause_url);
        
        if($status_code == 200) {
            $create_temp_record = $this->mcommon->common_edit('conversations',array('customer_name'=>$name,'customer_email'=>$email,'customer_mobile'=>$mobile), array('botId'=>$bot_id,'userId'=>$user_id,'thread_id'=>$conv_id));
            if($create_temp_record>=1) {
                return $this->response("OK", 200);
            }
            else {
                return $this->response("Failed to update customer data", 200);
            }
        } else {
            return $this->response("Pause failed", 500);
        }
    }

    public function end_chat_get($bot_id, $user_id, $pg_id) {
        $unpause_url = config_item('session_unpause_url');

        // unpause the chat first
        $unpause_url = str_replace("{user_id}", $user_id, $unpause_url);
        $unpause_url = str_replace("{bot_id}", $bot_id, $unpause_url);
        $status_code = send_post_request($unpause_url);
        
        if($status_code == 200) 
        {
            // update conversations "is_completed" status to true
            $completed = $this->mcommon->common_edit('conversations',array('is_completed'=>1),array('pg_id'=>$pg_id));
            if($completed) {
                return $this->response("OK", 200);    
            } else {
                return $this->response("Failed to update conversations->is_completed field", 500);
            }
        } else {
            return $this->response("failed to unpause conversation", 500);
        }
    }

    public function send_msg_post() {
        $bot_id = $this->post("bot_id");
        $user_id = $this->post("user_id");
        $text = $this->post("text");
        $agent = $this->post("agent");
        $agent_name = $this->post("agent_name");

        $send_msg_url = config_item('send_message_url');
        $send_msg_url = str_replace("{bot_id}", $bot_id, $send_msg_url);
        $send_msg_url = str_replace("{user_id}", $user_id, $send_msg_url);

        $send_msg_url = preg_replace('/\s+/',' ',$send_msg_url);
        $param_array = array("type"=>"text","text"=>$text,
            "agent"=>$agent,"agent_name"=>$agent_name);

        $reply_json = send_message_curl($send_msg_url, $param_array);
        return $this->response($reply_json, 200);
    }
    
    public function paused_get()
    {
        $last_timestamp = $this->get('timestamp');

        while (true) {
          $get_last = $this->app_model->get_last_conversation($last_timestamp);
          //echo "last updated timestamp ".$get_last[0]->last_updated_at." <br/>";
          //echo "last timestamp ".$last_timestamp;
          if ($get_last[0]->last_updated_at>$last_timestamp) {
            $paused_array = $this->app_model->get_paused_conversations();
             return $this->response($paused_array, 200); 
          }
          // Short pause before next check.
          sleep(1); 

        } 
    }

    public function agent_takeover_get()
    {
        $pg_id = $this->get('pg_id');
        $agent_id = $this->get('agent_id');

        $insert_agent_chat = $this->mcommon->common_insert('agent_chats',array('agent_id'=>$agent_id,'conversation_id'=>$pg_id));
        if($insert_agent_chat >= 1)
        {
            $update_conversation = $this->mcommon->common_edit('conversations',array('is_assigned'=>1),array('pg_id'=>$pg_id));
            if($update_conversation)
            {
                $current_time = date('Y-m-d H:i:s.uP', strtotime(date('Y-m-d H:i:sP')));
                $update_pg = $this->postgres->common_edit('hitl_sessions',array('last_heard_on'=>$current_time),array('id'=>$pg_id));    
                if($update_pg)
                {
                    return $this->response("Agent taken over",200);
                }
                else
                {
                    return $this->response("unable to update in hitl_sessions",500);
                }
            } 
            else
            {
                return $this->response("unable to update in conversations",500);
            }
        }
        else
        {
            return $this->response("unable to insert in agent_chats",500);
        } 
    }

    public function test_token_get()
    {
        return $this->response(get_bearer_token(), 200);
    }

    public function check_post()
    {
        $post_data = json_decode(file_get_contents('php://input'));
        $pg_id = $post_data->pg_id;
        $botId = $post_data->botId;
        $channel=$post_data->channel;
        $userId=$post_data->userId;
        $full_name=$post_data->full_name;
        $user_image_url=$post_data->user_image_url;
        $last_event_on=$post_data->last_event_on;
        $last_heard_on=$post_data->last_heard_on;
        $paused=$post_data->paused;
        $paused_trigger=$post_data->paused_trigger;
        $thread_id=$post_data->thread_id;

        //check if the record exists
        $check = $this->mcommon->specific_record_counts('conversations',array('pg_id'=>$pg_id));
        if($check > 0)
        {
            //which means already the conversation record exists
            //so update the values
            $update_array = array('pg_id' => $pg_id,
                                  'botId' => $botId,
                                  'channel' => $channel,
                                  'userId' => $userId,
                                  'full_name' => $full_name,
                                  'user_image_url' => $user_image_url,
                                  'last_event_on' => $last_event_on,
                                  'last_heard_on' => $last_heard_on,
                                  'paused' => $paused,
                                  'paused_trigger' => $paused_trigger,
                                  'thread_id' => $thread_id,
                            );
            $update = $this->mcommon->common_edit('conversations',$update_array,array('pg_id'=>$pg_id));
            if($update)
            {
                $is_assigned = $this->mcommon->specific_row_value('conversations',array('pg_id'=>$pg_id),'is_assigned');
                return $this->response($is_assigned,200);
            }
            else
            {
                return $this->response("Server error",500);
            }
        }
        else
        {
            //which means this is a new conversation so add new record
            $insert_array = array('pg_id' => $pg_id,
                                  'botId' => $botId,
                                  'channel' => $channel,
                                  'userId' => $userId,
                                  'full_name' => $full_name,
                                  'user_image_url' => $user_image_url,
                                  'last_event_on' => $last_event_on,
                                  'last_heard_on' => $last_heard_on,
                                  'paused' => $paused,
                                  'paused_trigger' => $paused_trigger,
                                  'thread_id' => $thread_id,
                            );
            $insert = $this->mcommon->common_insert('conversations',$insert_array);
            if($insert >= 1)
            {
                return $this->response('inserted',200);
            }
            else
            {
                return $this->response("Server error",500);
            }
        }
    }

    
    public function random_quote_get()
    {
        return $this->response($this->app_model->get_random_quote(),200);
    }

    public function history_get($agent_id)
    {
        $conversation_array = array();
        $agent_conversations = $this->app_model->get_history($agent_id);
        foreach ($agent_conversations as $key => $value) {
            $conv_object = new Conv();
            $conv_object->set_id($value['id']);
            $conv_object->set_bot_id($value['botId']);
            $conv_object->set_agent_id($value['agent_id']);
            $conv_object->set_user_id($value['userId']);
            $conv_object->set_conversation_id($value['thread_id']);
            $conv_object->set_full_name($value['customer_name']);
            $conv_object->set_email($value['customer_email']);
            $conv_object->set_mobile($value['customer_mobile']);
            $conv_object->set_last_seen($value['last_heard_on']);

            $conversation_array[] = $conv_object;
        }

        if(!empty($conversation_array))
        {
            $this->response($conversation_array, 200);

        } else {
            $error_array = array('status' => 'error', 'message' => 'no conversations');
            $this->response($error_array, 400);
        }
    }

    public function welcome_message_get($company_id) {
        $default_welcome_message = config_item("default_welcome_message");
        $message = $this->mcommon->specific_row_value("chatbot_messages", array("company_id"=>$company_id), "welcome_message");
        if(empty($message)) {
            return $this->response($default_welcome_message, 200);
        } else {
            return $this->response($message, 200);
        } 
    }

    public function validate_name_post()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        if(!preg_match("/(.+)( )(.+)/", $input['name']))
        {
            $this->response(array('message'=>'Invalid name'),400);
        }
        else{
            $this->response(array('message'=>'Valid name'),200); 
        }
    }

    public function validate_email_post()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        if(!filter_var($input['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->response(array('message'=>'Invalid Email'),400);
        }
        else{
            $this->response(array('message'=>'Valid Email'),200); 
        }
    }

    public function validate_mobile_post()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        $filtered_phone_number = filter_var($input['mobile'], FILTER_SANITIZE_NUMBER_INT);
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 14) 
        {
            $this->response(array('message'=>'Invalid mobile'),400);
        } 
        else 
        {
            $this->response(array('message'=>'Valid mobile'),200);   
        }
    }

    public function yesno_get()
    {
        $choices_array = array();
        $choice = array();

        $choice['title'] = "YES";
        $choice['value'] = "YES";
        array_push($choices_array, $choice);

        $choice['title'] = "NO";
        $choice['value'] = "NO";
        array_push($choices_array, $choice);

        $this->response($choices_array,200);
    }

}