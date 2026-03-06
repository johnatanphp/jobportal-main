<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jobseeker_validate_data_hook
{
    public function validate()
    {
        $CI = & get_instance();
        $folder = $CI->uri->segment(1);

        if ($folder != 'jobseeker') {
            return;
        }
        
        $user_id = $CI->session->userdata('user_id');

        if (!$user_id || !$CI->session->userdata('is_job_seeker')) {
            return;
        }

        $path_array = [
            $CI->uri->segment(2),
            $CI->uri->segment(3)
        ];

        $path = implode('/', array_filter($path_array));

        if ($path == 'legal_terms/accept' || 
            $path == 'my_account') {
            return;
        }

        if (!is_jobseeker_data_complete()) {
            redirect('jobseeker/my_account');
            exit;
        }
    }
}
