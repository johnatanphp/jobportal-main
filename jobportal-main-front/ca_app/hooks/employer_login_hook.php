<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employer_login_hook
{
    public function validate()
    {
        $CI = & get_instance();
        $folder = $CI->uri->segment(1);

        if ($folder != 'employer') {
            return;
        }

        $path_url = $CI->uri->segment(1) . '/' . $CI->uri->segment(2) . '/' . $CI->uri->segment(3);

        if ($path_url == 'employer/recruitment_short_list/candidates') {
            return;
        }
        
        $user_id = $CI->session->userdata('user_id');

        if (!$user_id || $CI->session->userdata('is_job_seeker')) {
            $CI->session->set_userdata('back_from_user_login', $CI->uri->uri_string);
            redirect('login', 'refresh');
        }
    }
}
