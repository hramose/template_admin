<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Activity_log {

    protected $CI;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('logs');
    }

    function create($actor, $data_new, $data_old, $data_change, $message, $activity, $type) {
        $data = array(
            'id_user' => $actor,
            'data_new' => $data_new,
            'data_old' => $data_old,
            'data_change' => $data_change,
            'message' => $message,
            'created_on' => date('Y-m-d H:i:s'),
            'activity' => $activity,
            'type' => $type
        );
        
        return $this->CI->logs->create($data);
    }

    function detail_log($log_id) {
        return $this->CI->logs->find_byid($log_id);
    }

    function delete_log($type, $log_id) {
        return $this->CI->logs->delete($log_id);
    }

}
