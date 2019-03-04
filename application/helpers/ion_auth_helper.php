<?php

if (!function_exists('auth_id')) {

    function auth_id() {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $user_id = $ci->ion_auth->user()->row()->id;
        return (!empty($user_id)) ? $user_id : null;
    }

}

if (!function_exists('auth_username')) {

    function auth_username() {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $username = $ci->ion_auth->user()->row()->full_name;
        return (!empty($username)) ? $username : null;
    }

}

if (!function_exists('auth_fullname')) {

    function auth_fullname() {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $fullname = $ci->ion_auth->user()->row()->sales_name;
        return (!empty($fullname)) ? $fullname : null;
    }

}

if (!function_exists('auth_company')) {

    function auth_company() {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $company = $ci->ion_auth->user()->row()->company_id;
        return (!empty($company)) ? $company : null;
    }

}

if (!function_exists('auth_dep')) {

    function auth_dep() {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $dept = $ci->ion_auth->user()->row()->dep_id;
        return (!empty($dept)) ? $dept : null;
    }

}

if (!function_exists('auth_group_id')) {

    function auth_group_id() {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $group_id = $ci->ion_auth->user()->row()->group_id;
        return (!empty($group_id)) ? $group_id : null;
    }

}

if (!function_exists('auth_avatar')) {

    function auth_avatar() {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $avatar = $ci->ion_auth->user()->row()->avatar;
        if($avatar == NULL) {
            $src_avatar = "assets/img/blank_photo.png";
        } else {
            $src_avatar = "assets/img/avatar/".$avatar;
        }
        return $src_avatar;
    }

}

if (!function_exists('group_id')) {

    function group_id($id = null) {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $result = $ci->ion_auth->get_users_groups($id)->row();
        return (!empty($result->id)) ? $result->id : null;
    }

}

if (!function_exists('group_name')) {

    function group_name($id = null) {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $result = $ci->ion_auth->get_users_groups($id)->row();
        return (!empty($result->description)) ? $result->description : null;
    }

}

if (!function_exists('auth_group')) {

    function auth_group($group = array()) {
        $ci = & get_instance();
        $ci->load->library('ion_auth');
        $result = $ci->ion_auth->in_group($group);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

