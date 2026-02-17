 <?php defined('BASEPATH') OR exit('No direct script access allowed');

class Message {

  public $id;
  public $text;
  public $name;
  public $type;
  public $timestamp;
  public $agent;
  public $agent_name;

  function __construct() {
  }

  function get_id() {
    return $this->id;
  }

  function get_text() {
    return $this->text;
  }

  function get_name() {
    return $this->name;
  }  

  function get_type() {
    return $this->type;
  }

  function get_agent() {
    return $this->agent;
  }

  function get_agent_name() {
    return $this->agent_name;
  }

  function get_timestamp() {
    return $this->timestamp;
  }

  function set_id($i) {
    $this->id = $i;
  }

  function set_text($t) {
    $this->text = $t;
  }

  function set_name($n) {
    $this->name = $n;
  }

  function set_type($t) {
    $this->type = $t;
  }

  function set_agent($a) {
    $this->agent = $a;
  }

  function set_agent_name($name) {
    $this->agent_name = $name;
  }  

  function set_timestamp($ts) {
    $this->timestamp = $ts;
  }

}
