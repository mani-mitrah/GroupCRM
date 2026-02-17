<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pg_model extends CI_Model
{
    public $pgdb;

    public function __construct()
    {
        parent::__construct();    
        //$pgdb = $this->load->database('pg', TRUE);
        $this->pgdb = $this->load->database('pg', TRUE);
    }

    /**
     * [record_counts description]
     * @param  [type] $user_id [users id]
     * @return [INT]   user's id [description]
     * @author Ganesh Ananthan
     */

    public function record_counts($table)
    {
        $this->pgdb->select('*');
        $this->pgdb->from($table);
        $num_results = $this->pgdb->count_all_results();
        return $num_results;
    }

    public function specific_record_counts($table, $constraint_array)
    {
        $this->pgdb->select('*');
        $this->pgdb->from($table);
        $this->pgdb->where($constraint_array);
        $num_results = $this->pgdb->count_all_results();
        return $num_results;
    }

    public function specific_record_counts_other($table, $constraint_array)
    {
        $this->pgdb->select('*');
        $this->pgdb->from($table);
        $this->pgdb->where($constraint_array);
        $num_results = $this->pgdb->count_all_results();
        return $num_results;
    }

    public function specific_row($table, $constraint_array = '')
    {
        $this->pgdb->select('*');
        $this->pgdb->from($table);
        if (!empty($constraint_array)) {
            $this->pgdb->where($constraint_array);
        }
        $result = $this->pgdb->get()->row_array();
        return $result;
    }

    public function specific_row_value($table, $constraint_array = '', $get_field)
    {
        $this->pgdb->select($get_field);
        $this->pgdb->from($table);
        if (!empty($constraint_array)) {
            $this->pgdb->where($constraint_array);
        }
        $result = $this->pgdb->get()->row_array();
        return $result[$get_field];
    }

    public function records_all($table, $constraint_array = '', $order_by = '')
    {
        $this->pgdb->select('*');
        $this->pgdb->from($table);
        if (!empty($constraint_array)) {
            $this->pgdb->where($constraint_array);
        }
        if (!empty($order_by)) {
            $this->pgdb->order_by($order_by);
        }
        $results = $this->pgdb->get()->result();
        return $results;
    }

    public function specific_fields_records_all($table, $constraint_array = '', $get_field_array = '')
    {
        if (!empty($get_field_array)) {
            $this->pgdb->select($get_field_array);
        } else {
            $this->pgdb->select('*');
        }
        $this->pgdb->from($table);
        if (!empty($constraint_array)) {
            $this->pgdb->where($constraint_array);
        }
        $results = $this->pgdb->get()->result_array();
        return $results;
    }

    public function common_insert($table, $data)
    {
        $this->pgdb->insert($table, $data);
        $result = $this->pgdb->insert_id();
        return $result;
    }

    public function common_edit($table, $data, $where_array)
    {
        $this->pgdb->trans_start();
        $this->pgdb->update($table, $data, $where_array);
        $this->pgdb->trans_complete();
        if ($this->pgdb->affected_rows() == '1') {
            return true;
        } else {
            if ($this->pgdb->trans_status() === false) {
                return false;
            }
            return true;
        }
    }

    public function common_delete($table, $where_array)
    {
        $this->pgdb->delete($table, $where_array);
        if ($this->pgdb->affected_rows() == '1') {
            return true;
        } else {
            return false;
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

    public function last_record($table, $pm_key, $date_column)
    {
        $query = $this->pgdb->query("SELECT * FROM $table ORDER BY $pm_key DESC LIMIT 1");
        $result = $query->result_array();
        return $result;
    }

    public function common_table_last_updated($table, $pm_key, $date_column)
    {
        $this->pgdb->select($date_column);
        $this->pgdb->from($table);
        $this->pgdb->order_by($pm_key, 'desc');
        $this->pgdb->limit('1');
        $result = $this->pgdb->get()->row_array();
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

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function clean_url($string)
    {
        $url = strtolower($string);
        $url = str_replace(array("'", '"'), '', $url);
        $url = str_replace(array(' ', '+', '!', '&', '-', '/', '.'), '-', $url);
        $url = str_replace("?", "", $url);
        $url = str_replace("---", "-", $url);
        $url = str_replace("--", "-", $url);
        return $url;
    }

    public function sendEmailWithTemplate($email_array)
    {
        $this->load->library('email');
        $this->email->set_newline("\r\n");

        $from_email_address = $pgdbvars->app_email;
        $from_email_name = $pgdbvars->app_name;
        $to_email_address = $email_array['to_email'];
        $email_subject = $email_array['subject'];
        $email_message = $email_array['message'];

        // Set to, from, message, etc.
        $this->email->from($from_email_address, $from_email_name);
        $this->email->to($to_email_address);
        $this->email->subject($email_subject);
        $this->email->message($email_message);
        $this->email->send();

        if (isset($email_array['cc'])) {
            $email_cc = $email_array['cc'];
            $this->email->cc($email_cc);
        }
        if (isset($email_array['bcc'])) {
            $email_bcc = $email_array['bcc'];
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
    public function Dropdown($table, $get_field, $constraint_array = '', $groupBy = '', $orderby = '', $limit = '', $optionType = '', $joinArr = '')
    {

        $this->pgdb->select($get_field);

        $this->pgdb->from($table);
        if (!empty($constraint_array)) {
            $this->pgdb->where($constraint_array);
        }

        if ($groupBy != '') {
            $this->pgdb->group_by($groupBy);
        }

        if (!empty($orderby)) {
            $this->pgdb->order_by($orderby);
        }

        if ($limit != '') {
            $this->pgdb->limit($limit);
        }
        if (!empty($constraint_array)) {
            foreach ($joinArr as $tableName => $condition) {
                $this->pgdb->join($tableName, $condition, '=');
            }
        }

        $results = $this->pgdb->get()->result();

        $options = array();

        if ($optionType == '') {
            $options[''] = "-- Select --";
        }

        foreach ($results as $item) {
            $options[$item->Key] = $item->Value;

        }
        return $options;
    }

    public function dataUpdate($table, $field, $where, $trans_set = '')
    {
        $this->pgdb->set("$field", "$field+1", false);
        if ($where != '') {
            $this->pgdb->where($where);
        }
        if ($trans_set != '') {
            foreach ($trans_set as $row => $val) {
                $val_array[] = $val;

            }
            $this->pgdb->where_in('naming_series_id', $val_array);
        }
        $this->pgdb->update($table);
        return $result = $this->pgdb->affected_rows();
    }

    public function validate_vendor($table, $vendor_id)
    {
        $this->pgdb->where('vendor_id', $vendor_id);
        $query = $this->pgdb->get($table);
        if ($query->num_rows() > 0) {
            $result = 1;
            return $result;
        } else {
            $result = 2;
            return $result;
        }
    }

    // Generate Naming Series
    public function generateSeries($naming, $transaction_id)
    {
        //This can be deleted after changing naming series to array form
        $naming_avoid = $naming;
        if (!is_array($naming)) {
            $naming = array('0' => $naming);
        }
        //End of delete
        foreach ($naming as $key) {
            $naminglist[$key] = explode('_', $key);
        }
        foreach ($naminglist as $row => $val) {
            $namingtest1[$row] = $val[0];
            $namingtest2[$row] = $val[1];
        }
        foreach ($namingtest1 as $row => $val) {
            $const_array = array(
                'naming_series_id' => $val,
                'transaction_id' => $transaction_id,
            );
            $currentValue = $this->specific_row_value('set_naming_series', $const_array, 'current_value');
            $prefixLength = $this->specific_row_value('set_naming_series', $const_array, 'prefix_id');
            $result[$row] = $namingtest2[$row] . '/' . str_pad($currentValue, $prefixLength, 0, STR_PAD_LEFT);

        }
        //This can be deleted after changing naming series to array form
        if (!is_array($naming_avoid)) {
            foreach ($result as $key => $value) {
                $inter = $value;
            }
            return $inter;
        }
        //End of delete
        return $result;
    }

    public function join_records_all($fields, $table, $joinArr, $constraint_array = '', $groupBy = '', $orderby = '', $limitValue = '', $distinct = '')
    {
        $this->pgdb->select(implode(',', $fields), false);
        $this->pgdb->from($table);
        foreach ($joinArr as $tableName => $condition) {
            $this->pgdb->join($tableName, $condition, 'left');
        }
        if (!empty($constraint_array)) {
            $this->pgdb->where($constraint_array);
        }

        if (!empty($orderby)) {
            $this->pgdb->order_by($orderby);
        }

        if ($groupBy != '') {
            $this->pgdb->group_by($groupBy);
        }

        if ($limitValue != '') {
            $this->pgdb->limit($limitValue);
        }
        if ($distinct != '') {
            $this->pgdb->limit($limitValue);
        }

        $results = $this->pgdb->get();
        return $results;
    }

    public function validate_insert($table, $qr_code, $data)
    {
        $this->pgdb->where('qr_code', $qr_code);
        $query = $this->pgdb->get($table);
        if ($query->num_rows() > 0) {
            $result = 1;
            return $result;
        } else {
            $this->pgdb->insert($table, $data);
        }
    }

    public function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }
    
    public function user_company($id)
    {
     $this->pgdb->select("*");
        $this->pgdb->from("users as u");
        $this->pgdb->join("em_companies as c","c.id=u.company_id");
        $this->pgdb->where('u.user_id',$id); 
        $result=$this->pgdb->get()->result();
        return $result;
    }
    public function get_notifications_user($auth_level)
    {
        $this->pgdb->select("*");
        $this->pgdb->from("users");
        $this->pgdb->where("auth_level",$auth_level);
           
        $num_results=$this->pgdb->get()->result();
        return $num_results;
    }

    public function expand_conversation($conv_id) {
        $sql = "select * from web_messages where  
        message_type in ('text', 'quick_reply', 'session_reset') 
        and \"conversationId\" = ".$conv_id." order by \"sent_on\" ASC";
        $query = $this->pgdb->query($sql);
        return $query->result_array();
    }

    public function last_message($conv_id) {
        $sql = "select * from web_messages where  
        message_type in ('text', 'quick_reply', 'session_reset') 
        and \"conversationId\" = ".$conv_id." order by \"sent_on\" DESC limit 1";
        $query = $this->pgdb->query($sql);
        return $query->result_array();
    }
    
}