 <?php defined('BASEPATH') OR exit('No direct script access allowed');

class Conv {

  public $id;
  public $bot_id;
  public $user_id;
  public $agent_id;
  public $conversation_id;
  public $full_name;
  public $email;
  public $mobile;
  public $title;
  public $last_seen;
  public $messages_array = array();

  function __construct() {
  }

  function get_id() {
    return $this->id;
  }

  function get_bot_id() {
    return $this->bot_id;
  }

  function get_user_id() {
    return $this->user_id;
  }

  function get_agent_id() {
    return $this->agent_id;
  }  

  function get_conversation_id() {
    return $this->conversation_id;
  }

  function get_full_name() {
    return $this->full_name;
  }

  function get_email() {
    return $this->email;
  }

  function get_mobile() {
    return $this->mobile;
  }

  function get_title() {
    return $this->title;
  }

  function get_messages() {
    return $this->messages_array;
  }

  function get_last_seen() {
    return $this->last_seen;
  }

  function set_id($i) {
    $this->id = $i;
  }

  function set_bot_id($id) {
    $this->bot_id = $id;
  }

  function set_user_id($id) {
    $this->user_id = $id;
  }

  function set_agent_id($id) {
    $this->agent_id = $id;
  }  

  function set_conversation_id($id) {
    $this->conversation_id = $id;
  }

  function set_full_name($name) {
    $this->full_name = $name;
  }

  function set_email($mail) {
    $this->email = $mail;
  }

  function set_mobile($m) {
    $this->mobile = $m;
  }

  function set_title($t) {
    $this->title = $t;
  }

  function set_message_array($array) {
    $this->messages_array = $array;
  } 

  function add_message($msg_object) {
    $this->messages_array[] = $msg_object;
  } 

  function set_last_seen($ts) {
    $this->last_seen = $ts;
  }

}
