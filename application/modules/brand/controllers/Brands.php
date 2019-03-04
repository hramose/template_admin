<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Brands extends MX_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('login', 'refresh');
        }
    }

    public function index() {
        $data['add_access'] = $this->user_profile->get_user_access('Created', 'brand');
        $data['print_limited_access'] = $this->user_profile->get_user_access('PrintLimited', 'brand');
        $data['print_unlimited_access'] = $this->user_profile->get_user_access('PrintUnlimited', 'brand');
        $this->load->blade('brand.views.brand.page', $data);
    }

    public function fetch_data() {
        $database_columns = array(
            'rowID',
            'brand_name'
        );

        $from = "m_brand";
        $where = "deleted = 0";
        $order_by = "rowID DESC";
        
        if ($this->input->get('sSearch') != '') {
            $sSearch = str_replace(array('.', ','), '', $this->db->escape_str($this->input->get('sSearch')));
            $where = "brand_name LIKE '%" . $sSearch . "%' AND deleted = 0";
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
            if($this->user_profile->get_user_access('Updated', 'brand') == 1){
                $dropdown_option .= '<li><a  href="javascript:void()" title="' . lang('update_option') . '" onclick="viewData(' . $row->rowID . ')"><i class="fa fa-pencil"></i> ' . lang('update_option') . '</a></li>';
            }
            
            if($this->user_profile->get_user_access('Deleted', 'brand') == 1){
                $dropdown_option .= '<li><a  href="javascript:void()" title="' . lang('delete_option') . '" onclick="deleteData(' . $row->rowID . ')"><i class="fa fa-trash-o"></i> ' . lang('delete_option') . '</a></li>';
            }
            
            $dropdown_option .= '</ul></div>';

            $row_value[] = $dropdown_option;
            $row_value[] = $row->brand_name;
            
            $new_aa_data[] = $row_value;
        }
        
        $selected_data['aaData'] = $new_aa_data;
        $this->output->set_content_type('application/json')->set_output(json_encode($selected_data));
    }

    public function save() {
        if ($this->input->is_ajax_request()) {
            $user = $this->ion_auth->user()->row();
            $id_brand = $this->input->post('rowID');
            $get_brand = Brand::where('brand_name' , $this->input->post('brand_name'))->where('deleted', 0)->first();
            if (empty($id_brand)) {
                if (!empty($get_brand->brand_name)) {
                    $status = array('status' => 'unique', 'message' => lang('already_exist'));
                }else{
                    $brand_name = strtoupper($this->input->post('brand_name'));
                    
                    $model = new Brand();
                    $model->brand_name = $brand_name;
                    
                    $model->user_created = $user->id;
                    $model->date_created = date('Y-m-d');
                    $model->time_created = date('H:i:s');
                    $save = $model->save();
                    if ($save) {
                        $data_notif = array(
                            'Brand Name' => $brand_name
                        );
                        $message = "Add " . strtolower(lang('brand')) . " " . $brand_name . " succesfully by " . $user->full_name;
                        $this->activity_log->create($user->id, json_encode($data_notif), NULL, NULL, $message, 'C', 24);
                        $status = array('status' => 'success', 'message' => lang('message_save_success'));
                    } else {
                        $status = array('status' => 'error', 'message' => lang('message_save_failed'));
                    }
                }
            } elseif(!empty($id_brand)) {
                $model = Brand::find($id_brand);
                $brand_name = strtoupper($this->input->post('brand_name'));
            
                $data_old = array(
                    'Brand Name' => $model->brand_name
                );

                $model->brand_name = $brand_name;
                $model->user_modified = $user->id;
                $model->date_modified = date('Y-m-d');
                $model->time_modified = date('H:i:s');
                $update = $model->save();
                if ($update) {
                    $data_new = array(
                        'Brand Name' => $brand_name,
                    );

                    $data_change = array_diff_assoc($data_new, $data_old);
                    $message = "Update " . strtolower(lang('brand')) . " " .  $brand_name . " succesfully by " . $user->full_name;
                    $this->activity_log->create($user->id, json_encode($data_new), json_encode($data_old), json_encode($data_change), $message, 'U', 24);
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
            $model = array('status' => 'success', 'data' => Brand::find($id));
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
            $model = Brand::find($id);
            if (!empty($model)) {
                $model->deleted = 1;
                $model->user_deleted = $user->id;
                $model->date_deleted = date('Y-m-d');
                $model->time_deleted = date('H:i:s');
                $delete = $model->save();

                $data_notif = array(
                    'Brand Name' => $model->brand_name,
                );
                $message = "Delete " . strtolower(lang('brand')) . " " .  $model->brand_name . " succesfully by " . $user->full_name;
                $this->activity_log->create($user->id, NULL, json_encode($data_notif), NULL, $message, 'D', 24);
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
        $data['brands'] = Brand::where('deleted', 0)->orderBy('rowID', 'DESC')->get();
        $html = $this->load->view('brand/brand/brand_pdf', $data, true);
        $this->pdf_generator->generate($html, 'brand pdf', $orientation='Portrait');
    }

    function excel(){
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=brand.xls");
        $data['brands'] = Brand::where('deleted', 0)->orderBy('rowID', 'DESC')->get();
        $this->load->view('brand/brand/brand_pdf', $data);
    }

}
