<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
    }

    function login() {
        //special for loginpage
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        if ($this->form_validation->run() == true) {
            if ($this->ion_auth->login($this->input->post('username'), $this->input->post('password'))) {
                $this->ion_auth->clear_login_attempts($this->input->post('username'));
                redirect('/', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('login', 'refresh');
            }
        } else {
            $data['message'] = $this->session->flashdata('message');
            $data['form_attributes'] = array("autocomplete" => "off", 'class' => 'md-float-material form-material', 'id' => 'login-form');
            $data['username'] = array(
                'name' => 'username',
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Username',
                'required' => 'required',
                'autofocus' => 'autofocus',
                'value' => $this->form_validation->set_value('username'),
            );
            $data['password'] = array(
                'name' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Password',
                'required' => 'required',
            );
            $data['csrftoken_name'] = $this->security->get_csrf_token_name();
            $data['csrftoken_value'] = $this->security->get_csrf_hash();
            $this->load->blade('user.views.login.page', $data);
        }
    }

    function logout() {
        $this->ion_auth->logout();
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('login', 'refresh');
    }


    public function index() {
        $data['add_access'] = $this->user_profile->get_user_access('Created', 'users/account');
        $data['print_limited_access'] = $this->user_profile->get_user_access('PrintLimited', 'users/account');
        $data['print_unlimited_access'] = $this->user_profile->get_user_access('PrintUnlimited', 'users/account');
        
        $data['user'] = $this->ion_auth->user()->row();
        $data['comp_name'] = $this->config->item('comp_name');
        $data['roles'] = Group::all();

        $this->load->blade('user.views.user.page', $data);
    }

    public function fetch_data() {
        $database_columns = array(
            'full_name',
            'username',
            'company',
            'groups.name',
            'groups.description',
            'created_date',
            'avatar',
            'users.id'
        );

        $from = "users";
        $where = "active = 1";
        $order_by = "users.created_date asc";
        $join[] = array('groups', 'groups.id = users.group_id', 'left');

        if ($this->input->get('sSearch') != '') {
            $sSearch = str_replace(array('.', ','), '', $this->db->escape_str($this->input->get('sSearch')));
            $where = "full_name LIKE '%" . $sSearch . "%' AND active = 1" ;
            $where .= " OR username LIKE '%" . $sSearch . "%' AND active = 1";
            $where .= " OR company LIKE '%" . $sSearch . "%' AND active = 1";
            $where .= " OR groups.description LIKE '%" . $sSearch . "%' AND active = 1";
            $where .= " OR created_date LIKE '%" . $sSearch . "%' AND active = 1";
            $where .= " OR users.id LIKE '%" . $sSearch . "%' AND active = 1";
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

            $btn_action = '';
            if($this->user_profile->get_user_access('Created', 'users/account'))
                $btn_action .= '<a href="' . base_url() . 'master/users/usermenu/' . uri_encrypt($row->id) . '" class="btn btn-success btn-icon-only btn-circle" title="' . lang('btnusermenu') . '"><i class="glyphicon glyphicon-cog"></i></a>';
            if($this->user_profile->get_user_access('Updated', 'users/account')){
                $btn_action .= '<a onclick="viewData(' . $row->id . ')" class="btn btn-warning btn-icon-only btn-circle" data-toggle="ajaxModal" title="' . lang('update') . '"><i class="fa fa-edit"></i> </a>';
                $btn_action .= '<a class="btn btn-danger btn-icon-only btn-circle" title="Reset Password" onclick="reset_password(' . $row->id . ')"><i class="fa fa-refresh"></i></a>';
            }
            if($this->user_profile->get_user_access('Deleted', 'users/account')){
                if ($row->username != $this->ion_auth->user()->row()->username) {
                    $btn_action .= '<a onclick="deleteData(' . $row->id . ')" class="btn btn-danger btn-icon-only btn-circle" title="' . lang('delete') . '"><i class="fa fa-trash-o"></i></a>';
                }
            }

            $row_value[] = $row->full_name;
            $row_value[] = ucwords($row->username);
            $row_value[] = ucwords($row->company);
            if ($row->name == 'admin') {
                $group = '<span class="label label-danger">' . ucfirst($row->description) . '</span>';
            }elseif ($row->name == 'members') {
                $group = '<span class="label label-primary">' . ucfirst($row->description) . '</span>';
            }else{
                $group = ucfirst($row->description);
            }
            $row_value[] = $group;
            $row_value[] = strftime("%b %d, %Y", strtotime($row->created_date));
            $row_value[] = '<a class="pull-left thumb-sm avatar"><img src="' . base_url() . 'assets/img/' . $row->avatar . '" class="img-circle" style="width:40%"></a>';
            $row_value[] = $btn_action;
            $new_aa_data[] = $row_value;
        }
        $selected_data['aaData'] = $new_aa_data;
        $this->output->set_content_type('application/json')->set_output(json_encode($selected_data));
    }

    public function save() {
        if ($this->input->is_ajax_request()) {
            $user_log = $this->ion_auth->user()->row();
            $check_exist = $this->ion_auth->email_check($this->input->post('email'));
            if ($check_exist > 0) {
                $status = array('status' => 'unique');
            } else {
                $username = $this->input->post('username');
                $group = array($this->input->post('role'));
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $confirm_password = $this->input->post('confirm_password');
                if($password == $confirm_password){
                    $data = array(
                        "full_name" => $this->input->post('fullname'),
                        "company" => $this->input->post('company'),
                        "group_id" => $this->input->post('role'),
                        "phone" => str_replace("_", "", $this->input->post('phone')),
                        "created_date" => date('Y-m-d H:i:s'),
                        "avatar" => 'default_avatar.jpg'
                    );
                    if ($this->ion_auth->register($username, $password, $email, $data, $group)) {
                        $data_notif = array(
                            "Full Name" => $this->input->post('fullname'),
                            "Email" => $email,
                            "Role" => Group::find($this->input->post('role'))->description,
                            "Company" => $this->input->post('company'),
                            "Phone" => str_replace("_", "", $this->input->post('phone')),
                        );
                        
                        $message = "Add " . strtolower(lang('user')) . " " . $this->input->post('fullname') . " succesfully by " . $user_log->full_name;
                        $this->activity_log->create($user_log->id, json_encode($data_notif), NULL, NULL, $message, 'C', 2);
                        $status = array('status' => 'success');
                    } else {
                        $status = array('status' => 'error');
                    }
                }
                else{
                    $status = array('status' => 'error');
                }
            }
            $data = $status;
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    function update(){
        if ($this->input->is_ajax_request()) {
            $id = (int) $this->input->post('user_id');
            $user_log = $this->ion_auth->user()->row();
            $user = $this->ion_auth->user($id)->row();
            $user_group = $this->ion_auth->get_users_groups($id)->row();
            $check_exist = 0;
            if ($user->email != $this->input->post('email')) {
                $check_exist = $this->ion_auth->email_check($this->input->post('email'));
            }
            if ($check_exist > 0) {
                $status = array('status' => 'unique');
            } else {
                $username = NULL;
                $group = array($this->input->post('role_id'));
                $data_old = array(
                    "Full Name" => $user->full_name,
                    "Email" => $user->email,
                    "Company" => $user->company,
                    "Phone" => str_replace("_", "", $user->phone),
                    "City" => $user->city,
                    "Address" => $user->address,
                    "Role " => Group::find($user->group_id)->description,
                );
                $data = array(
                    "full_name" => $this->input->post('fullname'),
                    "email" => $this->input->post('email'),
                    "company" => $this->input->post('company'),
                    "phone" => str_replace("_", "", $this->input->post('phone')),
                    "city" => $this->input->post('city'),
                    "address" => $this->input->post('address'),
                    "group_id" => $this->input->post('role_id'),
                );
                if ($this->ion_auth->update($id, $data)) {
                    if ($user_group->id != $group) {
                        $this->ion_auth->remove_from_group($user_group->id, $id);
                        $this->ion_auth->add_to_group($group, $id);
                    }
                    $data_new = array(
                        "Full Name" => $this->input->post('fullname'),
                        "Email" => $this->input->post('email'),
                        "Company" => $this->input->post('company'),
                        "Phone" => str_replace("_", "", $this->input->post('phone')),
                        "City" => $this->input->post('city'),
                        "Address" => $this->input->post('address'),
                        "Role " => Group::find($this->input->post('role_id'))->description,
                    );

                    $data_change = array_diff_assoc($data_new, $data_old);
                    $message = "Update " . strtolower(lang('user')) . " " .  $user->full_name . " succesfully by " . $user_log->full_name;
                    $this->activity_log->create($user_log->id, json_encode($data_new), json_encode($data_old), json_encode($data_change), $message, 'U', 2);
                    $status = array('status' => 'success');
                } else {
                    $status = array('status' => 'error');
                }
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
            $user = User::find($id);
            $model = array('status' => 'success', 'data' => $user);
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
            $user_log = $this->ion_auth->user()->row();
            $model = User::find($id);
            if (!empty($model)) {
                $model->active = 0;
                $update = $model->save();
                $data_notif = array(
                    "Status" => 'Active',
                );
                
                $message = "Delete " . strtolower(lang('user')) . " " .  $model->full_name . " succesfully by " . $user_log->full_name;
                $this->activity_log->create($user_log->id, NULL, json_encode($data_notif), NULL, $message, 'D', 2);
                
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

    public function reset_password() {
        if ($this->input->is_ajax_request()) {
            $id = (int) $this->input->get('id');
            $user = $this->ion_auth->user()->row();
            $model = User::find($id);
            if (!empty($model)) {
                $new_password = $this->config->item('password_default');
                $this->ion_auth->reset_password($model->username, $new_password);
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

    function profile() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
        // /* Provinsi */
        // $provinces = Province::all();
        // $province_options = array();
        // $province_options[''] = '-- Pilih Provinsi --';
        // if (!empty($provinces)) {
        //     foreach ($provinces as $province) {
        //         $province_options[$province->id_provinsi] = $province->nama_provinsi;
        //     }
        // }
        // $data['province_options'] = $province_options;
        // $data['province_properties'] = "class='form-control select2' id='provinsi' style='width: 100%'";

        /* City */
        // $data['cities'] = City::all();

        $this->load->blade('user.views.user.profile', array());
    }

    public function profile_save() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
        if ($this->input->is_ajax_request()) {
            $user = $this->ion_auth->user()->row();
            $user_group = $this->ion_auth->get_users_groups($user->id)->row();
            $first_name = ucwords($this->input->post('first_name'));
            $last_name = ucwords($this->input->post('last_name'));
            
            $check_exist = 0;
            if ($user->email != $this->input->post('email')) {
                $check_exist = $this->ion_auth->email_check($this->input->post('email'));
            }
            if ($check_exist > 0) {
                $status = array('status' => 'unique');
            } else {
                $data_old = array(
                    "Username" => $user->username,
                    "First Name" => $user->first_name,
                    "Last Name" => $user->last_name,
                    "Full Name" => $user->first_name.' '.$user->last_name,
                    "Email" => $user->email,
                    "Address" => $user->address,
                    "City" => $user->city,
                    "Phone" => str_replace("_", "", $user->phone),
                );

                $data = array(
                    "username" => $this->input->post('username'),
                    "first_name" => $first_name,
                    "last_name" => $last_name,
                    "full_name" => $first_name.' '.$last_name,
                    "email" => $this->input->post('email'),
                    "address" => $this->input->post('address'),
                    "city" => $this->input->post('city'),
                    "phone" => str_replace("_", "", $this->input->post('phone')),
                );

                if ($this->ion_auth->update($user->id, $data)) {
                    $data_new = array(
                        "Username" => $this->input->post('username'),
                        "First Name" => $first_name,
                        "Last Name" => $last_name,
                        "Full Name" => $first_name.' '.$last_name,
                        "Email" => $this->input->post('email'),
                        "Address" => $this->input->post('address'),
                        "City" => $this->input->post('city'),
                        "Phone" => str_replace("_", "", $this->input->post('phone')),
                    );

                    $data_change = array_diff_assoc($data_new, $data_old);
                    $message = "Update " . strtolower(lang('user')) . " profile " .  $user->full_name . " succesfully by " . $user->full_name;
                    $this->activity_log->create($user->id, json_encode($data_new), json_encode($data_old), json_encode($data_change), $message, 'U', 2);
                    
                    $status = array('status' => 'success');
                } else {
                    $status = array('status' => 'error');
                }
            }
            $data = $status;
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    public function profile_data() {
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
        if ($this->input->is_ajax_request()) {
            $user = $this->ion_auth->user()->row();
            $user_group = $this->ion_auth->get_users_groups($user->id)->row();
            $model = array('status' => 'success', 'data' => $user, 'data_group' => $user_group);
        } else {
            $model = array('status' => 'error', 'message' => 'Not Found.');
        }
        $data = $model;
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function change_password() {
        $this->load->blade('user.views.user.change_password');
    }

    function change_password_save() {
        if ($this->input->is_ajax_request()) {
            $user = $this->ion_auth->user()->row();
            if ($this->input->post('new_password') != $this->input->post('retype_password')) {
                $status = array('status' => 'wrong_password');
            } else {
                if ($this->ion_auth->change_password($user->username, $this->input->post('old_password'), $this->input->post('new_password'))) {
                    $user = User::find($user->id);
                    $user->password_mobile = md5($this->input->post('new_password'));
                    $update = $user->update();

                    $status = array('status' => 'success');
                } else {
                    $status = array('status' => 'error');
                }
            }

            $data = $status;
            
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    function forgot_password() {
        $this->form_validation->set_rules('email', 'Email Address', 'required');
        if ($this->form_validation->run() == false) {
            //setup the input
//            $this->data['email'] = array('name' => 'email',
//                'id' => 'email',
//            );
            //set any errors and display the form
            $this->session->set_flashdata('message', validation_errors());
            redirect("login", 'refresh');
        } else {
            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

            if ($forgotten) { //if there were no errors
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
            }
            redirect("login", 'refresh');
        }
    }

    function reset_password_backup($code = NULL) {
        if (!$code) {
            show_404();
        }
        $user = $this->ion_auth->forgotten_password_check($code);
        if ($user) {
            $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[8]|trim');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]|trim');
            if ($this->form_validation->run() == false) {
                if (!empty(validation_errors())) {
                    $this->session->set_flashdata('message_error', validation_errors());
                }
                $data['new_password'] = array(
                    'name' => 'new_password',
                    'required' => 'required',
                    'class' => 'form-control placeholder-no-fix',
                    'placeholder' => 'New Password',
                );
                $data['confirm_password'] = array(
                    'name' => 'confirm_password',
                    'required' => 'required',
                    'class' => 'form-control placeholder-no-fix',
                    'placeholder' => 'Confirm Password',
                );
                $data['user_id'] = $user->id;

                $data['form_attributes'] = array("autocomplete" => "off", 'class' => 'login-form');
                $data['code'] = $code;


                $data['message_error'] = $this->session->flashdata('message_error');
                $data['message_success'] = $this->session->flashdata('message_success');
                $this->load->blade('user.views.user.reset_password', $data);
            } else {
                if ($user->id != $this->input->post('user_id')) {
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error($this->lang->line('error_csrf'));
                } else {
                    $identity = $user->email;
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new_password'));
                    if ($change) {
                        $this->session->set_flashdata('message', 'Password Berhasil Diubah.');
                        redirect("login");
                    } else {
                        $this->session->set_flashdata('message_error', 'Password Tidak Berhasil Diubah.');
                        redirect('reset-password/' . $code);
                    }
                }
            }
        } else {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("login");
        }
    }

}

/* End of file Dashboard.php */
/* Location: ./application/modules/dashboard/controllers/Dashboard.php */