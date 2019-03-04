<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_menu extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index($id_user) {
        $data['add_access'] = $this->user_profile->get_user_access('Created', 'users/account');
        $data['print_limited_access'] = $this->user_profile->get_user_access('PrintLimited', 'users/account');
        $data['print_unlimited_access'] = $this->user_profile->get_user_access('PrintUnlimited', 'users/account');
        
        $data['id_user'] = uri_decrypt($id_user);
        $data['menus'] = Menu::where('status', '1')->orderBy('parent_menu_id', 'asc')->orderBy('menu_name', 'asc')->get();
        $data['user'] = $this->ion_auth->user()->row();
        $this->load->blade('user.views.user_menu.page', $data);
    }

    public function fetch_data($id_user = "") {
        $database_columns = array(
            'users_menu.id_user_menu',
            'menu.menu_name',
            'sub_menu.menu_name as nama_sub_menu',
            '(CASE users_menu.StatusUsermenu
                WHEN "1" THEN "Active"
                WHEN "0" THEN "Not Active"
                ELSE "-"
             END) AS StatusUsermenu'
        );

        $from = "users";
        $where = "users_menu.user_id =". $id_user;
        $order_by = "menu.parent_menu_id asc, menu.menu_name asc";
        $join[] = array('users_menu', 'users_menu.user_id = users.id', '');
        $join[] = array('menu', 'menu.menu_id = users_menu.menu_id', '');
        $join[] = array('menu as sub_menu', 'sub_menu.menu_id = menu.parent_menu_id', 'left');

        if ($this->input->get('sSearch') != '') {
            $sSearch = str_replace(array('.', ','), '', $this->db->escape_str($this->input->get('sSearch')));
            $where = "menu.menu_name LIKE '%" . $sSearch . "%' AND users_menu.user_id =". $id_user;
            $where .= " OR (CASE users_menu.StatusUsermenu
                WHEN '1' THEN 'Active'
                WHEN '0' THEN 'Not Active'
                ELSE '-'
             END) LIKE '%" . $sSearch . "%' AND users_menu.user_id =". $id_user;
        }

        $this->datatables->set_index('users.id');
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

            $dropdown_option = '<div class="btn-group" style="position:absolute !important; display: block; !important">';
            $dropdown_option .= '<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">';
            $dropdown_option .= lang('options');
            $dropdown_option .= '<span class="caret"></span>';
            $dropdown_option .= '</button>';
            $dropdown_option .= '<ul class="dropdown-menu">';
            
            if($this->user_profile->get_user_access('Updated', 'users/account')){
                $dropdown_option .= '<li><a href="javascript:void()" title="' . lang('update_option') . '" onclick="edit_usermenu(' . $row->id_user_menu . ')"><i class="fa fa-pencil"></i> ' . lang('update_option') . '</a></li>';
            }
            
            if($this->user_profile->get_user_access('Deleted', 'users/account')){
            
                $dropdown_option .= '<li><a href="javascript:void()" title="' . lang('delete_option') . '" onclick="delete_usermenu(' . $row->id_user_menu . ')"><i class="fa fa-trash-o"></i> ' . lang('delete_option') . '</a></li>';
            }

            $dropdown_option .= '</ul></div>';

            $row_value[] = $dropdown_option;
            $row_value[] = $row->menu_name;
            $row_value[] = ($row->nama_sub_menu == '') ? 'Parent' : ucwords($row->nama_sub_menu);
            $row_value[] = ($row->StatusUsermenu == 'Active' ) ? '<span class="label label-success">'.lang('active').'</span>' : '<span class="label label-warning">'.lang('notactive').'</span>';
            $new_aa_data[] = $row_value;
        }
        $selected_data['aaData'] = $new_aa_data;
        $this->output->set_content_type('application/json')->set_output(json_encode($selected_data));
    }

    public function save() {
        if ($this->input->is_ajax_request()) {
            $user = $this->ion_auth->user()->row();
            $usermenu_code = UserMenu::where(
                'user_id', $this->input->post('user_id')
            )->where(
                'menu_id', $this->input->post('menu_code')
            )->first();

            $id_user_menu = $this->input->post('rowID');
            if (empty($id_user_menu)) {
                if (empty($usermenu_code->menu_id)) {
                    $user_id = $this->input->post('user_id');
                    $menu_code = $this->input->post('menu_code');
                    $availabled = $this->input->post('availabled');
                    $created = $this->input->post('created');
                    $viewed = $this->input->post('viewed');
                    $updated = $this->input->post('updated');
                    $deleted = $this->input->post('deleted');
                    $approved = $this->input->post('approved');
                    $verified = $this->input->post('verified');
                    $fullaccess = $this->input->post('fullaccess');
                    $printlimited = $this->input->post('printlimited');
                    $printunlimited = $this->input->post('printunlimited');
                    $status = $this->input->post('status');
                    if(empty($status)){
                        $status = '0';
                    }else{
                        $status = '1';
                    }
                    $model = new UserMenu();
                    $model->user_id = $user_id;
                    $model->menu_id = $menu_code;
                    $model->availabled = $availabled;
                    $model->created = $created;
                    $model->viewed = $viewed;
                    $model->updated = $updated;
                    $model->deleted = $deleted;
                    $model->approved = $approved;
                    $model->verified = $verified;
                    $model->fullaccess = $fullaccess;
                    $model->printlimited = $printlimited;
                    $model->printunlimited = $printunlimited;
                    $model->statususermenu = $status;
                    $save = $model->save();
                    if ($save) {
                        $data_notif = array(
                            'User' => User::find($user_id)->full_name,
                            'Menu' => Menu::find($menu_code)->menu_name,
                            'Availabled' => ($availabled == '1') ? 'Yes' : 'No',
                            'Created' => ($created == '1') ? 'Yes' : 'No',
                            'Viewed' => ($viewed == '1') ? 'Yes' : 'No',
                            'Updated' => ($updated == '1') ? 'Yes' : 'No',
                            'Deleted' => ($deleted == '1') ? 'Yes' : 'No',
                            'Approved' => ($approved == '1') ? 'Yes' : 'No',
                            'Verified' => ($verified == '1') ? 'Yes' : 'No',
                            'Fullaccess' => ($fullaccess == '1') ? 'Yes' : 'No',
                            'Print Limited' => ($printlimited == '1') ? 'Yes' : 'No',
                            'Print Unlimited' => ($printunlimited == '1') ? 'Yes' : 'No',
                            'Status Usermenu' => ($status == '1') ? 'Active' : 'Not Active'
                        );
                        
                        $message = "Add " . strtolower(lang('usermenu')) . " " . User::find($user_id)->full_name . " succesfully by " . $user->full_name;
                        $this->activity_log->create($user->id, json_encode($data_notif), NULL, NULL, $message, 'C', 3);
                        $status = array('status' => 'success', 'message' => lang('message_save_success'));
                    } else {
                        $status = array('status' => 'error', 'message' => lang('message_save_failed'));
                    }
                }else{
                    $status = array('status' => 'unique', 'message' => lang('already_menu_exist'));
                }
            } elseif(!empty($id_user_menu)) {
                $saving = false;
                if (!empty($usermenu_code->menu_id)) {
                    if($this->input->post('menu_code_tmp') != $this->input->post('menu_code')){
                        $status = array('status' => 'unique', 'message' => lang('already_menu_exist'));
                        $this->output->set_content_type('application/json')->set_output(json_encode($status));
                    }else{
                        $saving = true;
                    }
                }
                else {
                    $saving = true;
                }

                if($saving == true){
                    $model = UserMenu::find($id_user_menu);
                    $menu_code = $this->input->post('menu_code');
                    $availabled = $this->input->post('availabled');
                    $created = $this->input->post('created');
                    $viewed = $this->input->post('viewed');
                    $updated = $this->input->post('updated');
                    $deleted = $this->input->post('deleted');
                    $approved = $this->input->post('approved');
                    $verified = $this->input->post('verified');
                    $fullaccess = $this->input->post('fullaccess');
                    $printlimited = $this->input->post('printlimited');
                    $printunlimited = $this->input->post('printunlimited');
                    $status = $this->input->post('status');
                    if(empty($status)){
                        $status = '0';
                    }else{
                        $status = '1';
                    }
                    $data_old = array(
                        'Menu' => Menu::find($model->menu_id)->menu_name,
                        'Availabled' => ($model->Availabled == '1') ? 'Yes' : 'No',
                        'Created' => ($model->Created == '1') ? 'Yes' : 'No',
                        'Viewed' => ($model->Viewed == '1') ? 'Yes' : 'No',
                        'Updated' => ($model->Updated == '1') ? 'Yes' : 'No',
                        'Deleted' => ($model->Deleted == '1') ? 'Yes' : 'No',
                        'Approved' => ($model->Approved == '1') ? 'Yes' : 'No',
                        'Verified' => ($model->Verified == '1') ? 'Yes' : 'No',
                        'Fullaccess' => ($model->FullAccess == '1') ? 'Yes' : 'No',
                        'Print Limited' => ($model->PrintLimited == '1') ? 'Yes' : 'No',
                        'Print Unlimited' => ($model->PrintUnlimited == '1') ? 'Yes' : 'No',
                        'Status Usermenu' => ($model->StatusUsermenu == '1') ? 'Active' : 'Not Active'
                    );

                    $model->menu_id = $menu_code;
                    $model->availabled = $availabled;
                    $model->created = $created;
                    $model->viewed = $viewed;
                    $model->updated = $updated;
                    $model->deleted = $deleted;
                    $model->approved = $approved;
                    $model->verified = $verified;
                    $model->fullaccess = $fullaccess;
                    $model->printlimited = $printlimited;
                    $model->printunlimited = $printunlimited;
                    $model->statususermenu = $status;
                    $update = $model->save();
                    if ($update) {
                        $data_new = array(
                            'Menu' => Menu::find($menu_code)->menu_name,
                            'Availabled' => ($availabled == '1') ? 'Yes' : 'No',
                            'Created' => ($created == '1') ? 'Yes' : 'No',
                            'Viewed' => ($viewed == '1') ? 'Yes' : 'No',
                            'Updated' => ($updated == '1') ? 'Yes' : 'No',
                            'Deleted' => ($deleted == '1') ? 'Yes' : 'No',
                            'Approved' => ($approved == '1') ? 'Yes' : 'No',
                            'Verified' => ($verified == '1') ? 'Yes' : 'No',
                            'Fullaccess' => ($fullaccess == '1') ? 'Yes' : 'No',
                            'Print Limited' => ($printlimited == '1') ? 'Yes' : 'No',
                            'Print Unlimited' => ($printunlimited == '1') ? 'Yes' : 'No',
                            'Status Usermenu' => ($status == '1') ? 'Active' : 'Not Active'
                        );

                        $data_change = array_diff_assoc($data_new, $data_old);
                        
                        $message = "Update " . strtolower(lang('usermenu')) . " " .  User::find($model->user_id)->full_name . " succesfully by " . $user->full_name;
                        $this->activity_log->create($user->id, json_encode($data_new), json_encode($data_old), json_encode($data_change), $message, 'U', 3);

                        $status = array('status' => 'success', 'message' => lang('message_save_success'));
                    } else {
                        $status = array('status' => 'error', 'message' => lang('message_save_failed'));
                    }
                }
                else {
                    $status = array('status' => 'error', 'message' => lang('message_save_failed'));
                }
            } else {
                $status = array('status' => 'error', 'message' => lang('message_save_failed'));
            }

            $data = $status;
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function view() {
        if ($this->input->is_ajax_request()) {
            $id = (int) $this->input->get('id');
            $user_menu = UserMenu::find($id);
            $model = array('status' => 'success', 'data' => $user_menu);
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
            $model = UserMenu::find($id);
            if (!empty($model)) {
                $model->StatusUsermenu = 0;
                $update = $model->save();
                
                $data_notif = array(
                    "Menu" => Menu::find($model->menu_id)->menu_name,
                    "Status Usermenu" => ($model->StatusUsermenu == '1') ? 'Active' : 'Not Active'
                );
                
                $message = "Delete " . strtolower(lang('usermenu')) . " " .  User::find($model->user_id)->full_name . " succesfully by " . $user->full_name;
                $this->activity_log->create($user->id, NULL, json_encode($data_notif), NULL, $message, 'D', 3);
               
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

/* End of file Dashboard.php */
/* Location: ./application/modules/dashboard/controllers/Dashboard.php */