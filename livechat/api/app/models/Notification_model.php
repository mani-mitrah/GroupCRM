<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notification_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_notifications($notification_id)
    {
        $update_array = array(
            'status' => 1,
        );
        $this->db->where("id", $notification_id);
        $this->db->update("web_push_notification", $update_array);
        return $this->db->affected_rows();
    }

    public function get_notifications_count($user_id)
    {
        $this->db->select("*");
        $this->db->from("web_push_notification");
        $this->db->order_by("id", "desc");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get();
        $output = $query->result();
        $viewed = 0;
        $not_viewed = 0;
        foreach ($output as $o) {
            if ($o->status == 0) {
                $not_viewed++;
            } else if ($o->status == 1) {
                $viewed++;
            }
        }

        $result[] = array(
            'viewed' => $viewed,
            'not_viewed' => $not_viewed,
        );

        return $result;
    }

}