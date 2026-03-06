<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jobseeker_Login_Hook {

    public function validate_jobseeker_login()
    {
        $CI = & get_instance();
        $folder = $CI->uri->segment(1);
        
        if ($folder != 'jobseeker') {
            return;
        }
        
        $user_id = $CI->session->userdata('user_id');
        
        if (!$user_id || !$CI->session->userdata('is_job_seeker')) {
            $CI->session->set_userdata('back_from_user_login', $CI->uri->uri_string);
            redirect('login', 'refresh');
        }
    }
}
