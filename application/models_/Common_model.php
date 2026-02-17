<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * [record_counts description]
	 * @param  [type] $user_id [users id]
	 * @return [INT]   user's id [description]
	 * @author Ganesh Ananthan
	 */

	public function record_counts($table)
	{
		$this->db->select('*');
		$this->db->from($table);
		$num_results = $this->db->count_all_results();
		return $num_results;
	}

	public function specific_record_counts($table,$constraint_array)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($constraint_array);
		$num_results = $this->db->count_all_results();
		return $num_results;
	}

	public function specific_record_counts_other($table,$constraint_array)
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($constraint_array);
		$num_results = $this->db->count_all_results();
		return $num_results;
	}

	public function specific_row($table,$constraint_array='')
	{
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($constraint_array))
		{
			$this->db->where($constraint_array);
		}
		$result= $this->db->get()->row_array();
		return $result;
	}
	

	public function specific_row_value($table,$constraint_array='',$get_field)
	{
		$this->db->select($get_field);
		$this->db->from($table);
		if(!empty($constraint_array))
		{
			$this->db->where($constraint_array);
		}
		$result= $this->db->get()->row_array();
		return $result[$get_field];
	}

	public function records_all($table, $constraint_array='', $order_by='')
	{
		$this->db->select('*');
		$this->db->from($table);
		if(!empty($constraint_array))
		{
			$this->db->where($constraint_array);
		}
		if(!empty($order_by))
		{
			$this->db->order_by($order_by);
		}
		$results= $this->db->get()->result();
		return $results;
	}

	public function specific_fields_records_all($table, $constraint_array='',$get_field_array='',$group_by='')
	{
		if(!empty($get_field_array))
		{
			$this->db->select($get_field_array);
		}
		else
		{
			$this->db->select('*');
		}
		$this->db->from($table);
		if(!empty($constraint_array))
		{
			$this->db->where($constraint_array);
		}
		if(!empty($group_by))
		{
			$this->db->group_by($group_by);
		}
		$results= $this->db->get()->result_array();
		return $results;
	}

	public function common_insert($table,$data)
	{
	    $this->db->insert($table, $data);
		$result = $this->db->insert_id();
		return $result;
	}

	public function common_insert_and_return($table,$data)
	{
	    $done = $this->db->insert($table, $data);
		if($done) {
			$new_id = $this->db->insert_id();
			$this->db->select("*");
			$this->db->from($table);
			$this->db->where("id",$new_id);
			return $this->db->get()->result();
		} else {
			return false;
		}
	}

	public function common_edit($table,$data,$where_array)
	{
		$this->db->trans_start();
		$this->db->update($table , $data , $where_array);
		$this->db->trans_complete();
		if ($this->db->affected_rows() == '1') {
		    return TRUE;
		} else {
		    if ($this->db->trans_status() === FALSE) {
		        return false;
		    }
		    return true;
		}
	}

	public function common_delete($table,$where_array)
	{
	   $this->db->delete($table, $where_array);
	   if ($this->db->affected_rows() == '1') {
		    return TRUE;
		} else {
		    return FALSE;
		}
	}
	
	public function in_array_rec($needle, $haystack, $strict = false) 
	{
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_rec($needle, $item, $strict))) {
	            return true;
	        }
	    }
	    return 0;
	}
	
	public function last_record($table,$pm_key,$date_column)
	{ 
			$query = $this->db->query("SELECT * FROM $table ORDER BY $pm_key DESC LIMIT 1");
			$result = $query->result_array();
				return $result;
	}

	public function common_table_last_updated($table,$pm_key,$date_column)
	{
		$this->db->select($date_column);
		$this->db->from($table);
		$this->db->order_by($pm_key,'desc');
		$this->db->limit('1');
		$result= $this->db->get()->row_array();
		return $this->time_elapsed_string($result[$date_column]);
	}

	public function time_elapsed_string($datetime, $full = false) 
	{
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	function clean_url($string)
	{
	    $url=strtolower($string);
	    $url=str_replace(array("'",'"'), '', $url);
	    $url=str_replace(array(' ','+', '!', '&','-','/','.'), '-', $url);
	    $url=str_replace("?", "", $url);
	    $url=str_replace("---", "-", $url);
	    $url=str_replace("--", "-", $url);
	    return $url;
	}

	public function sendEmailWithTemplate($email_array)
	{
		$this->load->library('email');
		$this->email->set_newline("\r\n");

		$from_email_address=$this->dbvars->app_email;
		$from_email_name=$this->dbvars->app_name;
		$to_email_address=$email_array['to_email'];
		$email_subject=$email_array['subject'];
		$email_message=$email_array['message'];

		// Set to, from, message, etc.
		$this->email->from($from_email_address, $from_email_name);
	    $this->email->to($to_email_address);
	    $this->email->subject($email_subject);
	    $this->email->message($email_message);
	    $this->email->send();

		if(isset($email_array['cc']))
		{
			$email_cc=$email_array['cc'];
			$this->email->cc($email_cc);
		}
		if(isset($email_array['bcc']))
		{
			$email_bcc=$email_array['bcc'];
			$this->email->cc($email_bcc);
		}

    	echo $this->email->print_debugger();
		$result = $this->email->send();
	}
  	//  Dropdown Menu Simple
	/**
	* @param $get_field - mention only two params like KEY & VALUE
	- If you want CONCAT two or more fields in the Key OR Value section. pass like that
	- array( CONCAT(user_firstname, '.', user_surname) AS Key, fieldName as Value)
	*/
	public function Dropdown($table, $get_field, $constraint_array='', $groupBy='', $orderby='', $limit='', $optionType='', $joinArr='')
	{

		$this->db->select($get_field);

		$this->db->from($table);
		if(!empty($constraint_array))
		{
			$this->db->where($constraint_array);
		}

		if($groupBy != '')
		{
			$this->db->group_by($groupBy);
		}

		if(!empty($orderby))
		{
			$this->db->order_by($orderby);
		}

		if($limit != '')
		{
			$this->db->limit($limit);
		}
		if(!empty($constraint_array))
		{
			foreach ($joinArr as $tableName => $condition)
			{
			$this->db->join($tableName, $condition, '=');
			}
		}

		$results = $this->db->get()->result();

		$options = array();

		if($optionType == '')
		{
			$options[''] = "-- Select --";
		}
		
		foreach($results as $item)
		{
			$options[$item->Key] = $item->Value;

		}	
		return $options;
	} 


    function get_domain($url)
	{
  		$pieces = parse_url($url);
  		$domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
  		if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
    	return $regs['domain'];
  		}
  		return false;
	}

	public function update_service_status($id,$update_array){
		$this->db->where("as_id",$id);
		$this->db->update("assigned_service",$update_array);
		return $this->db->affected_rows();
	}
}
