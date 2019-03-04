<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Areas extends MX_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
    }

    public function index() {
        $data['add_access'] = $this->user_profile->get_user_access('Created', 'area');
        $data['print_limited_access'] = $this->user_profile->get_user_access('PrintLimited', 'area');
        $data['print_unlimited_access'] = $this->user_profile->get_user_access('PrintUnlimited', 'area');
        $this->load->blade('area.views.area.page', $data);
    }

    public function fetch_data() {
        $database_columns = array(
            'rowID',
            'area_name'
        );

        $from = "m_area";
        $where = "deleted = 0";
        $order_by = "rowID DESC";
        
        if ($this->input->get('sSearch') != '') {
            $sSearch = str_replace(array('.', ','), '', $this->db->escape_str($this->input->get('sSearch')));
            $where = "area_name LIKE '%" . $sSearch . "%' AND deleted = 0";
        }

        $this->datatables->set_index('rowID');
        $this->datatables->config('database_columns', $database_columns);
        $this->datatables->config('from', $from);
        $this->datatables->config('where', $where);
        $this->datatables->config('order_by', $order_by);
        $selected_data = $this->datatables->get_select_data();
        $aa_data = $selected_data['aaData'];
        $new_aa_data = array();
        
        
        foreach ($aa_data as $row) {
            $row_value = array();

            $dropdown_option = '';
            $dropdown_option .= '<div class="btn-group">';
            $dropdown_option .= '<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">';
            $dropdown_option .= lang('options');
            $dropdown_option .= '<span class="caret"></span>';
            $dropdown_option .= '</button>';
            $dropdown_option .= '<ul class="dropdown-menu">';
            if($this->user_profile->get_user_access('Updated', 'area') == 1){
                $dropdown_option .= '<li><a  href="javascript:void()" title="' . lang('update_option') . '" onclick="viewData(' . $row->rowID . ')"><i class="fa fa-pencil"></i> ' . lang('update_option') . '</a></li>';
            }
            
            if($this->user_profile->get_user_access('Deleted', 'area') == 1){
                $dropdown_option .= '<li><a  href="javascript:void()" title="' . lang('delete_option') . '" onclick="deleteData(' . $row->rowID . ')"><i class="fa fa-trash-o"></i> ' . lang('delete_option') . '</a></li>';
            }
            
            $dropdown_option .= '</ul></div>';

            $row_value[] = $dropdown_option;
            $row_value[] = $row->area_name;
            
            $new_aa_data[] = $row_value;
        }
        
        $selected_data['aaData'] = $new_aa_data;
        $this->output->set_content_type('application/json')->set_output(json_encode($selected_data));
    }

    public function save() {
        if ($this->input->is_ajax_request()) {
            $user = $this->ion_auth->user()->row();
            $id_area = $this->input->post('rowID');
            $get_area = Area::where('area_name' , $this->input->post('area_name'))->where('deleted', 0)->first();
            if (empty($id_area)) {
                if (!empty($get_area->area_name)) {
                    $status = array('status' => 'unique', 'message' => lang('already_exist'));
                }else{
                    $area_name = strtoupper($this->input->post('area_name'));
                    
                    $model = new Area();
                    $model->area_name = $area_name;
                    
                    $model->user_created = $user->id;
                    $model->date_created = date('Y-m-d');
                    $model->time_created = date('H:i:s');
                    $save = $model->save();
                    if ($save) {
                        $data_notif = array(
                            'Area Name' => $area_name
                        );
                        $message = "Add " . strtolower(lang('area')) . " " . $area_name . " succesfully by " . $user->full_name;
                        $this->activity_log->create($user->id, json_encode($data_notif), NULL, NULL, $message, 'C', 41);
                        $status = array('status' => 'success', 'message' => lang('message_save_success'));
                    } else {
                        $status = array('status' => 'error', 'message' => lang('message_save_failed'));
                    }
                }
            } elseif(!empty($id_area)) {
                $model = Area::find($id_area);
                $area_name = strtoupper($this->input->post('area_name'));
            
                $data_old = array(
                    'Area Name' => $model->area_name
                );

                $model->area_name = $area_name;
                $model->user_modified = $user->id;
                $model->date_modified = date('Y-m-d');
                $model->time_modified = date('H:i:s');
                $update = $model->save();
                if ($update) {
                    $data_new = array(
                        'Area Name' => $area_name,
                    );

                    $data_change = array_diff_assoc($data_new, $data_old);
                    $message = "Update " . strtolower(lang('area')) . " " .  $area_name . " succesfully by " . $user->full_name;
                    $this->activity_log->create($user->id, json_encode($data_new), json_encode($data_old), json_encode($data_change), $message, 'U', 41);
                    $status = array('status' => 'success', 'message' => lang('message_save_success'));
                } else {
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
            $model = array('status' => 'success', 'data' => Area::find($id));
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
            $model = Area::find($id);
            if (!empty($model)) {
                $model->deleted = 1;
                $model->user_deleted = $user->id;
                $model->date_deleted = date('Y-m-d');
                $model->time_deleted = date('H:i:s');
                $delete = $model->save();

                $data_notif = array(
                    'Area Name' => $model->area_name,
                );
                $message = "Delete " . strtolower(lang('area')) . " " .  $model->area_name . " succesfully by " . $user->full_name;
                $this->activity_log->create($user->id, NULL, json_encode($data_notif), NULL, $message, 'D', 41);
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

    function pdf(){
        $data['areas'] = Area::where('deleted', 0)->orderBy('rowID', 'DESC')->get();
        $html = $this->load->view('area/area/area_pdf', $data, true);
        $this->pdf_generator->generate($html, 'area pdf', $orientation='Portrait');
    }

    function excel(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=area.xls");
        $data['areas'] = Area::where('deleted', 0)->orderBy('rowID', 'DESC')->get();
        $this->load->view('area/area/area_pdf', $data);
    }

}
