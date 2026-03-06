<?php

function check_permission($action)
{
    $CI = & get_instance();

    $user_id = $CI->session->userdata('user_id');
    $is_app_user = !$CI->session->userdata('is_job_seeker');

    if (!$user_id || !$is_app_user) {
        $CI->session->set_userdata('back_from_user_login', $CI->uri->uri_string);
        redirect('login', 'refresh');
        return;
    }

    $profile_id = $CI->session->userdata('current_profile_id');

    $CI->db->select('p.id');
    $CI->db->from('tbl_permissions_profiles pp');
    $CI->db->join('tbl_permissions p', 'pp.permission_id=p.id');
    $CI->db->where('p.action', $action);
    $CI->db->where('pp.profile_id', $profile_id);
    
    $count = $CI->db->count_all_results();

    if ($count == 0) {
        show_404();
    }
}

function check_permission_tray_candidates($user_id = null)
{
    $CI = & get_instance();

    $user_id = $user_id ? $user_id : $CI->session->userdata('user_id');
    
    $employer = $CI->Employer->find($user_id);

    if (!$employer || !in_array($employer->rrhh_type_id, [1, 2])) {
        return false;
    }

    return true;
}

function has_permission_module($module_keyword_id) 
{
    $ci = & get_instance();

    $employer_id = $ci->session->userdata('user_id');
    $profile_id = $ci->session->userdata('current_profile_id');

    $ci->db->select([
        'permissions.action_id As permission_action_id'
    ]);
    $ci->db->from('tbl_profile_actions profile_actions');
    $ci->db->join('tbl_modules_actions actions', 'profile_actions.action_id=actions.id');
    $ci->db->join('tbl_modules modules', 'modules.id=actions.module_id');
    $ci->db->join('tbl_profile_actions_permissions permissions', 'permissions.action_id=actions.id');
    $ci->db->where('permissions.profile_id', $profile_id);
    $ci->db->where('permissions.employer_id', $employer_id);
    $ci->db->where('modules.keyword_id', $module_keyword_id);

    return $ci->db->count_all_results() > 0;
}

function has_permission_action($module_keyword_id, $action_keyword_id)
{
    $ci = & get_instance();
    $employer_id = $ci->session->userdata('user_id');
    $profile_id = $ci->session->userdata('current_profile_id');

    $ci->db->select([
        'permissions.action_id As permission_action_id'
    ]);
    $ci->db->from('tbl_profile_actions profile_actions');
    $ci->db->join('tbl_modules_actions actions', 'profile_actions.action_id=actions.id');
    $ci->db->join('tbl_modules modules', 'modules.id=actions.module_id');
    $ci->db->join('tbl_profile_actions_permissions permissions', 'permissions.action_id=actions.id');
    $ci->db->where('permissions.profile_id', $profile_id);
    $ci->db->where('permissions.employer_id', $employer_id);
    $ci->db->where('modules.keyword_id', $module_keyword_id);
    $ci->db->where('actions.keyword_id', $action_keyword_id);

    return $ci->db->count_all_results() > 0;
}

function check_permission_action($module_keyword_id, $action_keyword_id)
{
    if (!has_permission_action($module_keyword_id, $action_keyword_id)) {
        show_404();
        //redirect('no_permissions/index/' . $module_keyword_id . '/' . $action_keyword_id);
        return;
    }
}
