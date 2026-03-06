<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function seeker_check_permission($action)
    {
        $user_id = $this->session->userdata('user_id');
        $is_app_user = !$this->session->userdata('is_job_seeker');

        if (!$user_id || !$is_app_user) {
            redirect('login');
            return;
        }

        $profile_id = $this->session->userdata('current_profile_id');

        $this->db->select('p.id');
        $this->db->from('tbl_permissions_profiles pp');
        $this->db->join('tbl_permissions p', 'pp.permission_id=p.id');
        $this->db->where('p.action', $action);
        $this->db->where('pp.profile_id', $profile_id);
        
        $count = $this->db->count_all_results();

        if ($count == 0) {
            show_404();
        }
    }
}
