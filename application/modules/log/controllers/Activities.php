<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Activities extends MX_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
        $this->load->helper('indonesian_date');
    }

    public function index() {
        $this->load->blade('log.views.log.list_group');
    }

    public function fetch_data_group() {
        $user = $this->ion_auth->user()->row();
            
        $database_columns = array(
            'logs.id_logs',
            'logs.type',
            'b.menu AS type_caption',
            'MAX(logs.created_on) as created_on'
        );

        $from = "logs";
        $group_by = 'logs.type';
        $order_by = "MAX(logs.created_on) desc";
        $join[] = array('reference_logs as b', 'b.code = logs.type', 'left');

        if(!$this->ion_auth->is_admin()){
            $where = 'logs.id_user = '.$user->id;
        }
        else{
            $where = '';
        }

        if ($this->input->get('sSearch') != '') {
            $sSearch = str_replace(array('.', ','), '', $this->db->escape_str($this->input->get('sSearch')));
            $where .= "b.menu LIKE '%" . $sSearch . "%'";
            $where .= "OR DATE_FORMAT(logs.created_on,'%d %M %Y') LIKE '%" . $sSearch . "%'";
        }

        $this->datatables->set_index('logs.id_logs');
        $this->datatables->config('database_columns', $database_columns);
        $this->datatables->config('from', $from);
        $this->datatables->config('join', $join);
        $this->datatables->config('where', $where);
        $this->datatables->config('group_by', $group_by);
        $this->datatables->config('order_by', $order_by);
        $selected_data = $this->datatables->get_select_data();
        $aa_data = $selected_data['aaData'];
        $new_aa_data = array();
        foreach ($aa_data as $row) {
            $row_value = array();
            $row_value[] = $row->id_logs;
            $row_value[] = $row->type_caption;
            $row_value[] = indonesian_format($row->created_on) . ' ' .date('H:i:s', strtotime($row->created_on));
            $row_value[] = $row->type;
            $new_aa_data[] = $row_value;
        }
        $selected_data['aaData'] = $new_aa_data;
        $this->output->set_content_type('application/json')->set_output(json_encode($selected_data));
    }

    // List Setelah Grup
    public function lists($type) {
        $menu = ReferenceLog::where('code', $type)->first();
        if (!empty($menu->menu)) {
            $title = $menu->menu;
        }
        else{
            $title = '-';
        }
        
        $data = array(
            'title' => $title,
            'type' => $type
        );
        $this->load->blade('log.views.log.list', $data);
    }

    public function fetch_data($type) {
        $user = $this->ion_auth->user()->row();
        
        $database_columns = array(
            'logs.id_logs',
//            'concat(users.first_name, " ", users.last_name) As full_name',
            'users.full_name',
            'groups.description',
            '(CASE logs.activity
                WHEN "C" THEN "Create"
                WHEN "D" THEN "Delete"
                WHEN "U" THEN "Update"
                ELSE "-"
             END) AS activity',
            'logs.message',
            'logs.created_on',
        );

        $from = "logs";

        $join[] = array('users', 'users.id = logs.id_user', 'left');
        $join[] = array('users_groups', 'users_groups.user_id = users.id', 'left');
        $join[] = array('groups', 'groups.id = users_groups.group_id', 'left');

        $where = "type = '" . $type . "' ";
        
        if(!$this->ion_auth->is_admin()){
            $where .= ' AND logs.id_user = '.$user->id;
        }

        $order_by = "logs.created_on desc";

        if ($this->input->get('sSearch') != '') {
            $sSearch = str_replace(array('.', ','), '', $this->db->escape_str($this->input->get('sSearch')));
            $where .= "And (CASE logs.activity
                WHEN 'C' THEN 'Create'
                WHEN 'U' THEN 'Update'
                WHEN 'D' THEN 'Delete'
                ELSE '-'
             END) LIKE '%" . $sSearch . "%'";
            //$where .= "OR concat(users.first_name, ' ', users.last_name) LIKE '%" . $sSearch . "%'";
            $where .= "OR users.full_name LIKE '%" . $sSearch . "%'";
            $where .= "OR groups.description LIKE '%" . $sSearch . "%'";
            $where .= "OR logs.message LIKE '%" . $sSearch . "%'";
            $where .= "OR DATE_FORMAT(logs.created_on,'%d %M %Y') LIKE '%" . $sSearch . "%'";
        }

        $this->datatables->set_index('logs.id_logs');
        $this->datatables->config('database_columns', $database_columns);
        $this->datatables->config('from', $from);
        $this->datatables->config('join', $join);
        $this->datatables->config('where', $where);
        $this->datatables->config('order_by', $order_by);
        $selected_data = $this->datatables->get_select_data();
        $aa_data = $selected_data['aaData'];
        $new_aa_data = array();
        foreach ($aa_data as $row) {
            $row_value = array();
            $row_value[] = $row->id_logs;
            $row_value[] = $row->full_name;
            $row_value[] = $row->description;
            if ($row->activity == 'Create') {
                $aktivitas = '<span class="label label-success">Create</span>';
            } else if ($row->activity == 'Update') {
                $aktivitas = '<span class="label label-warning">Update</span>';
            } else {
                $aktivitas = '<span class="label label-danger">Delete</span>';
            }
            $row_value[] = $aktivitas;
            $row_value[] = $row->message;
            $row_value[] = indonesian_format($row->created_on) . ' ' .date('H:i:s', strtotime($row->created_on));
            $row_value[] = $row->id_logs;
            $new_aa_data[] = $row_value;
        }
        $selected_data['aaData'] = $new_aa_data;
        $this->output->set_content_type('application/json')->set_output(json_encode($selected_data));
    }

    function detail($id) {
        $user = $this->ion_auth->user()->row();
        $log = $this->activity_log->detail_log($id);
        if ($log->activity == 'C') {
            $activity = "Create";
        } else if ($log->activity == 'U') {
            $activity = "Update";
        } else {
            $activity = "Delete";
        }
        
        $menu = ReferenceLog::where('code', $log->type)->first();
        if (!empty($menu->menu)) {
            $title = $menu->menu;
            $type = $log->type;
        }
        else{
            $title = '-';
            $type = '';
        }
        
        $data = array(
            'title' => $title,
            'type' => $type,
            'log' => $log,
            'activity' => $activity
        );
        $this->load->blade('log.views.log.detail', $data);
    }

}
