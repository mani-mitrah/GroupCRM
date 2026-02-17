<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Commonauth_model extends CI_Model
{
    public $authdb;

    public function __construct()
    {
        parent::__construct();
        $this->authdb = $this->load->database('auth', TRUE);
    }

    public function select_table($table)
    {
        $this->authdb->select('*');
        $this->authdb->from($table);
        $query = $this->authdb->get();
        $result = $query->result();
        return $result;
    }
    public function record_counts($table)
    {
        $this->authdb->select('*');
        $this->authdb->from($table);
        $num_results = $this->authdb->count_all_results();
        return $num_results;
    }

    public function specific_record_counts($table, $constraint_array)
    {
        $this->authdb->select('*');
        $this->authdb->from($table);
        $this->authdb->where($constraint_array);
        $num_results = $this->authdb->count_all_results();
        return $num_results;
    }

    public function specific_record_counts_other($table, $constraint_array)
    {
        $this->authdb->select('*');
        $this->authdb->from($table);
        $this->authdb->where($constraint_array);
        $num_results = $this->authdb->count_all_results();
        return $num_results;
    }

    public function specific_row($table, $constraint_array = '')
    {
        $this->authdb->select('*');
        $this->authdb->from($table);
        if (!empty($constraint_array)) {
            $this->authdb->where($constraint_array);
        }
        $result = $this->authdb->get()->row_array();
        return $result;
    }

    public function specific_row_value($table, $constraint_array = '', $get_field)
    {
        $this->authdb->select($get_field);
        $this->authdb->from($table);
        if (!empty($constraint_array)) {
            $this->authdb->where($constraint_array);
        }
        $result = $this->authdb->get()->row_array();
        return $result[$get_field];
    }

    public function records_all($table, $constraint_array = '', $order_by = '')
    {
        $this->authdb->select('*');
        $this->authdb->from($table);
        if (!empty($constraint_array)) {
            $this->authdb->where($constraint_array);
        }
        if (!empty($order_by)) {
            $this->authdb->order_by($order_by);
        }
        $results = $this->authdb->get()->result();
        return $results;
    }

    public function specific_fields_records_all($table, $constraint_array = '', $get_field_array = '')
    {
        if (!empty($get_field_array)) {
            $this->authdb->select($get_field_array);
        } else {
            $this->authdb->select('*');
        }
        $this->authdb->from($table);
        if (!empty($constraint_array)) {
            $this->authdb->where($constraint_array);
        }
        $results = $this->authdb->get()->result_array();
        return $results;
    }

    public function common_insert($table, $data)
    {
        $this->authdb->insert($table, $data);
        $result = $this->authdb->insert_id();
        return $result;
    }

    public function common_edit($table, $data, $where_array)
    {
        $this->authdb->trans_start();
        $this->authdb->update($table, $data, $where_array);
        $this->authdb->trans_complete();
        if ($this->authdb->affected_rows() == '1') {
            return true;
        } else {
            if ($this->authdb->trans_status() === false) {
                return false;
            }
            return true;
        }
    }

    public function common_delete($table, $where_array)
    {
        $this->authdb->delete($table, $where_array);
        if ($this->authdb->affected_rows() == '1') {
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
        $query = $this->authdb->query("SELECT * FROM $table ORDER BY $pm_key DESC LIMIT 1");
        $result = $query->result_array();
        return $result;
    }

    public function common_table_last_updated($table, $pm_key, $date_column)
    {
        $this->authdb->select($date_column);
        $this->authdb->from($table);
        $this->authdb->order_by($pm_key, 'desc');
        $this->authdb->limit('1');
        $result = $this->authdb->get()->row_array();
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

        $from_email_address = $authdbvars->app_email;
        $from_email_name = $authdbvars->app_name;
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

        $this->authdb->select($get_field);

        $this->authdb->from($table);
        if (!empty($constraint_array)) {
            $this->authdb->where($constraint_array);
        }

        if ($groupBy != '') {
            $this->authdb->group_by($groupBy);
        }

        if (!empty($orderby)) {
            $this->authdb->order_by($orderby);
        }

        if ($limit != '') {
            $this->authdb->limit($limit);
        }
        if (!empty($constraint_array)) {
            foreach ($joinArr as $tableName => $condition) {
                $this->authdb->join($tableName, $condition, '=');
            }
        }

        $results = $this->authdb->get()->result();

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
        $this->authdb->set("$field", "$field+1", false);
        if ($where != '') {
            $this->authdb->where($where);
        }
        if ($trans_set != '') {
            foreach ($trans_set as $row => $val) {
                $val_array[] = $val;

            }
            $this->authdb->where_in('naming_series_id', $val_array);
        }
        $this->authdb->update($table);
        return $result = $this->authdb->affected_rows();
    }

    public function validate_vendor($table, $vendor_id)
    {
        $this->authdb->where('vendor_id', $vendor_id);
        $query = $this->authdb->get($table);
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
        $this->authdb->select(implode(',', $fields), false);
        $this->authdb->from($table);
        foreach ($joinArr as $tableName => $condition) {
            $this->authdb->join($tableName, $condition, 'left');
        }
        if (!empty($constraint_array)) {
            $this->authdb->where($constraint_array);
        }

        if (!empty($orderby)) {
            $this->authdb->order_by($orderby);
        }

        if ($groupBy != '') {
            $this->authdb->group_by($groupBy);
        }

        if ($limitValue != '') {
            $this->authdb->limit($limitValue);
        }
        if ($distinct != '') {
            $this->authdb->limit($limitValue);
        }

        $results = $this->authdb->get();
        return $results;
    }

    public function validate_insert($table, $qr_code, $data)
    {
        $this->authdb->where('qr_code', $qr_code);
        $query = $this->authdb->get($table);
        if ($query->num_rows() > 0) {
            $result = 1;
            return $result;
        } else {
            $this->authdb->insert($table, $data);
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
}