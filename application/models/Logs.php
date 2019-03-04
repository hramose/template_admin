<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logs extends CI_Model {

    protected $table = 'logs';

    function find_byid($log_id) {
        $this->db->select('logs.*, users.first_name, users.last_name, users.full_name, groups.description');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = logs.id_user');
        $this->db->join('users_groups', 'users_groups.user_id = logs.id_user');
        $this->db->join('groups', 'groups.id = users_groups.group_id');
        $this->db->where('logs.id_logs', $log_id);
        $query = $this->db->get();
        return $query->row();
    }

    function create($data) {
        return $this->db->insert($this->table, $data);
    }

    function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

}
