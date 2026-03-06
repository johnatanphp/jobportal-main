<?php

if (!function_exists('user_belong_to_company_internal')) {
    function user_belong_to_company_internal() {

        static $__cache_user_belong_to_company_internal = NULL;

        if (!is_null($__cache_user_belong_to_company_internal) ) {
            return $__cache_user_belong_to_company_internal;
        }
            
        $ci =& get_instance();
        $ci->db->select([
            'tbl_companies.system_internal'
        ]);
        $ci->db->from('tbl_employers');
        $ci->db->join('tbl_companies', 'tbl_companies.ID=tbl_employers.company_ID');
        $ci->db->where('tbl_employers.ID', $ci->session->userdata('user_id'));
        $row = $ci->db->get()->row();

        $__cache_user_belong_to_company_internal = $row && $row->system_internal;

        return $__cache_user_belong_to_company_internal;
    }
}

if (!function_exists('get_session_company_id')) {
    function get_session_company_id() {

        static $__cache_get_session_company_id = NULL;

        if (!is_null($__cache_get_session_company_id) ) {
            return $__cache_get_session_company_id;
        }
            
        $ci =& get_instance();
        $ci->db->select([
            'employer.company_ID'
        ]);
        $ci->db->from('tbl_employers employer');
        $ci->db->where('employer.ID', $ci->session->userdata('user_id'));
        $row = $ci->db->get()->row();
        $__cache_get_session_company_id = $row && $row->company_ID ? $row->company_ID : false;

        return $__cache_get_session_company_id;
    }
}
