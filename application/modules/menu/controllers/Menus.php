<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends MX_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
    }

    public function index() {
        $data['add_access'] = $this->user_profile->get_user_access('Created', 'menu');
        $data['print_limited_access'] = $this->user_profile->get_user_access('PrintLimited', 'menu');
        $data['print_unlimited_access'] = $this->user_profile->get_user_access('PrintUnlimited', 'menu');
        
        $data['parent_menus'] = Menu::where('parent_menu_id', 0)->where('status', '1')->orderBy('menu_name', 'ASC')->get();
        $data['menu_id'] = count(Menu::orderBy('menu_name', 'ASC')->get()) + 1;
        $this->load->blade('menu.views.menu.page', $data);
    }

    public function fetch_data() {
        $this->load->helper('indonesian_date');
        
        $database_columns = array(
            'menu.menu_id',
            'menu.menu_name',
            'menu.menu_code',
            'sub_menu.menu_name as nama_sub_menu',
            'menu.menu_link',
            '(CASE menu.status
                WHEN "1" THEN "Active"
                WHEN "0" THEN "Not Active"
                ELSE "-"
             END) AS status'
        );

        $from = "menu";
        $where = "";
        $order_by = "menu.menu_code ASC";
        $join[] = array('menu as sub_menu', 'sub_menu.menu_id = menu.parent_menu_id', 'left');

        if ($this->input->get('sSearch') != '') {
            $sSearch = str_replace(array('.', ','), '', $this->db->escape_str($this->input->get('sSearch')));
            $where = "menu.menu_name LIKE '%" . $sSearch . "%'";
            $where .= " OR menu.menu_code LIKE '%" . $sSearch . "%'";
            $where .= " OR sub_menu.menu_name LIKE '%" . $sSearch . "%'";
            $where .= " OR menu.menu_link LIKE '%" . $sSearch . "%'";
            $where .= " OR (CASE menu.status
                WHEN '1' THEN 'Active'
                WHEN '0' THEN 'Not Active'
                ELSE '-'
             END) LIKE '%" . $sSearch . "%'";
        }

        $this->datatables->set_index('menu.menu_id');
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

            $dropdown_option = '';
            $dropdown_option .= '<div class="btn-group" style="position:absolute !important; display: block; !important">';
            $dropdown_option .= '<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">';
            $dropdown_option .= lang('options');
            $dropdown_option .= '<span class="caret"></span>';
            $dropdown_option .= '</button>';
            $dropdown_option .= '<ul class="dropdown-menu">';
            if($this->user_profile->get_user_access('Updated', 'menu'))
                $dropdown_option .= '<li><a href="javascript:void()" title="' . lang('update_option') . '" onclick="viewData(' . $row->menu_id . ')"><i class="fa fa-pencil"></i> '. lang('update_option') . '</a></li>';
            if($this->user_profile->get_user_access('Deleted', 'menu'))
                $dropdown_option .= '<li><a href="javascript:void()" title="' . lang('delete_option') . '" onclick="deleteData(' . $row->menu_id . ')"><i class="fa fa-trash-o"></i> ' . lang('delete_option') . '</a></li>';
            $dropdown_option .= '</ul></div>';

            $row_menu_code = '';
            $row_menu_code .= '<div class="pull-left">';
            $row_menu_code .= '<div id="view_' . $row->menu_id . '">' . $row->menu_code . '</div>';
            $row_menu_code .= '<div id="input_' . $row->menu_id . '" style="display: none;"><input type="text" class="form-control input-xs" id="menu_code_' . $row->menu_id . '" value="' . $row->menu_code . '" onkeyup="angka(this)" style="width: 50%;text-align:center" /></div>';
            $row_menu_code .= '</div>';
            $row_menu_code .= '<div class="pull-right">';
            $row_menu_code .= '<div>';

            if($this->user_profile->get_user_access('Updated', 'menu')){
                $row_menu_code .= '<a onclick="changeOrderMenu(' . $row->menu_id . ')" id="link_' . $row->menu_id . '"><i class="fa fa-edit"></i> ' . lang('change') . '</a>';
                $row_menu_code .= '<a onclick="saveOrderMenu(' . $row->menu_id . ')" id="save_' . $row->menu_id . '" style="display: none;"><i class="fa fa-save"></i> ' . lang('save_menu') . '</a>';
            }
            
            $row_menu_code .= '</div></div>';

            $row_value[] = $dropdown_option;
            $row_value[] = $row_menu_code;
            $row_value[] = $row->menu_name;
            $row_value[] = ($row->nama_sub_menu == '') ? 'Parent' : ucwords($row->nama_sub_menu);
            $row_value[] = $row->menu_link;
            $row_value[] = ($row->status == 'Active') ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Not Active</span';
            $new_aa_data[] = $row_value;
        }
        $selected_data['aaData'] = $new_aa_data;
        $this->output->set_content_type('application/json')->set_output(json_encode($selected_data));
    }

    public function save() {
        if ($this->input->is_ajax_request()) {
            $user = $this->ion_auth->user()->row();
            $menu_id = $this->input->post('menu_id');
            if (empty($menu_id)) {
                $model = new Menu();
                $menu_code = $this->input->post('menu_code');
                $menu_name = $this->input->post('menu_name');
                $menu_link = $this->input->post('menu_link');
                $lang = $this->input->post('menu_language');
                $parent_menu_id = $this->input->post('menu_parent');
                $status = $this->input->post('status');
                if(empty($status)){
                    $status = '0';
                }else{
                    $status = '1';
                }
                $parent_name = Menu::find($parent_menu_id);
                if(empty($parent_name)){
                    $parent_name = '-';
                }else{
                    $parent_name = $parent_name->menu_name;
                }

                $model->menu_code = $menu_code;
                $model->menu_name = $menu_name;
                $model->menu_link = $menu_link;
                $model->lang = $lang;
                $model->parent_menu_id = $parent_menu_id;
                $model->status = $status;
                $model->date_created = date('Y-m-d H:i:s');
                $model->date_modified = date('Y-m-d H:i:s');
                $save = $model->save();
                if ($save) {
                    $data_notif = array(
                        "Menu Code" => $menu_code,
                        "Menu Name" => $menu_name,
                        "Menu Link" => $menu_link,
                        "Lang" => $lang,
                        "Parent Menu" => $parent_name,
                        "Status" => $status == 1 ? 'Active' : 'Not Active'
                    );

                    $message = "Add " . strtolower(lang('menu')) . " " . $menu_name . " succesfully by " . $user->full_name;
                    $this->activity_log->create($user->id, json_encode($data_notif), NULL, NULL, $message, 'C', 1);
                    $status = array('status' => 'success');
                } else {
                    $status = array('status' => 'error');
                }
            } elseif(!empty($menu_id)) {
                $menu_id = (int) $this->input->post('menu_id');
                $model = Menu::find($menu_id);
                $menu_code = $this->input->post('menu_code');
                $menu_name = $this->input->post('menu_name');
                $menu_link = $this->input->post('menu_link');
                $lang = $this->input->post('menu_language');
                $parent_menu_id = $this->input->post('menu_parent');
                $status = $this->input->post('status');
                if(empty($status)){
                    $status = '0';
                }else{
                    $status = '1';
                }

                $parent_name = Menu::find($parent_menu_id);
                if(empty($parent_name)){
                    $parent_name = '-';
                }else{
                    $parent_name = $parent_name->menu_name;
                }

                $parent_name_old = Menu::find($model->parent_menu_id);
                if(empty($parent_name_old)){
                    $parent_name_old = '-';
                }else{
                    $parent_name_old = $parent_name_old->menu_name;
                }

                $data_old = array(
                    "Menu Code" => $model->menu_code,
                    "Menu Name" => $model->menu_name,
                    "Menu Link" => $model->menu_link,
                    "Lang" => $model->lang,
                    "Parent Menu" => $parent_name_old,
                    "Status" => $model->status == 1 ? 'Active' : 'Not Active'
                );

                $model->menu_code = $menu_code;
                $model->menu_name = $menu_name;
                $model->menu_link = $menu_link;
                $model->lang = $lang;
                $model->parent_menu_id = $parent_menu_id;
                $model->status = $status;
                $model->date_modified = date('Y-m-d H:i:s');
                $update = $model->save();
                if ($update) {
                    $data_new = array(
                        "Menu Code" => $menu_code,
                        "Menu Name" => $menu_name,
                        "Menu Link" => $menu_link,
                        "Lang" => $lang,
                        "Parent Menu" => $parent_name,
                        "Status" => $status == 1 ? 'Active' : 'Not Active',
                    );

                    $data_change = array_diff_assoc($data_new, $data_old);
                    $message = "Update " . strtolower(lang('menu')) . " " .  $menu_name . " succesfully by " . $user->full_name;
                    $this->activity_log->create($user->id, json_encode($data_new), json_encode($data_old), json_encode($data_change), $message, 'U', 1);

                    $status = array('status' => 'success');
                } else {
                    $status = array('status' => 'error');
                }
            } else {
                $status = array('status' => 'error');
            }
            $data = $status;
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function update() {
        if ($this->input->is_ajax_request()) {
            $id = (int) $this->input->get('menu_id');
            $menu_code = (int) $this->input->get('menu_code');
            $user = $this->ion_auth->user()->row();
            $model = Menu::find($id);
            if (!empty($model)) {

                $data_old = array(
                   "Menu Code" => $model->menu_code
                );

                $model->menu_code = $menu_code;
                $model->date_modified = date('Y-m-d H:i:s');
                $update = $model->save();
                $data_new = array(
                   "Menu Code" => $menu_code
                );

                $data_change = array_diff_assoc($data_new, $data_old);
                $message = "Update " . strtolower(lang('menu')) . " " .  $model->menu_name . " succesfully by " . $user->full_name;
                $this->activity_log->create($user->id, json_encode($data_new), json_encode($data_old), json_encode($data_change), $message, 'U', 1);
                $status = array('status' => 'success');
            } else {
                $status = array('status' => 'error');
            }
        } else {
            $status = array('status' => 'error');
        }
        $data = $status;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function view() {
        if ($this->input->is_ajax_request()) {
            $id = (int) $this->input->get('id');
            $model = array('status' => 'success', 'data' => Menu::find($id));
        } else {
            $model = array('status' => 'error', 'message' => 'Not Found.');
        }
        $data = $model;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    public function delete() {
        if ($this->input->is_ajax_request()) {
            $id = (int) $this->input->get('id');
            $user = $this->ion_auth->user()->row();
            $model = Menu::find($id);
            if (!empty($model)) {

                $parent_name = Menu::find($model->parent_menu_id);
                if(empty($parent_name)){
                    $parent_name = '-';
                }else{
                    $parent_name = $parent_name->menu_name;
                }
                $delete = $model->delete();
                $data_notif = array(
                    "Menu Code" => $model->menu_code,
                    "Menu Name" => $model->menu_name,
                    "Menu Link" => $model->menu_link,
                    "Lang" => $model->lang,
                    "Parent Menu" => $parent_name,
                    "Status" => $model->status == 1 ? 'Active' : 'Not Active'
                );

                $message = "Delete " . strtolower(lang('menu')) . " " .  $model->menu_name . " succesfully by " . $user->full_name;
                $this->activity_log->create($user->id, NULL, json_encode($data_notif), NULL, $message, 'D', 1);
                $status = array('status' => 'success');
            } else {
                $status = array('status' => 'error');
            }
        } else {
            $status = array('status' => 'error');
        }
        $data = $status;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}
