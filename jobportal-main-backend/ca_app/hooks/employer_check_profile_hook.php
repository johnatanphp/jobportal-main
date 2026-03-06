<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employer_check_profile_hook
{
    public function validate() 
    {
        $CI = & get_instance();
        
        $user_id = $CI->session->userdata('user_id');
        
        if (empty($user_id) || 
            $CI->session->userdata('is_job_seeker')) {
            return;
        }

        $folder = $CI->uri->segment(1);

        if ($folder != 'employer') {
            return;
        }

        $profile_id = $CI->session->userdata('current_profile_id');
    
        if ($profile_id != 3 && $profile_id != 2) {
            return;
        }

        $list_url = [
            3 => [
                'employer/staff_requests',
                'employer/recruitment',
                'employer/recruitment_entry',
                'employer/recruitment_requested_docs',
                'employer/rys_form_affidavit_seekers',
                'candidate/screening_show_pdf',
                'employer/recruitment_tray'
            ],
            2 => [
                'employer/staff_request',
                'employer/staff_requests',
                'employer/job_profiles',
                'employer/mofs',
                'employer/recruitment_candidates',
                'employer/recruitment_short_list',
                'candidate/screening_show_pdf',
                'employer/job_layouts'
            ],
            4 => [
                'employer/job_profiles',
                'employer/mofs',
                'employer/rys_forms/export',
                'employer/exam_requests',
                'employer/medical_centers',
                'candidate/screening_show_pdf'
            ],
            5 => [
                'employer/entry_list_foreign',
                'employer/exam_requests',
                'candidate/screening_show_pdf'
            ]
        ];

        $url_profiles =  isset($list_url[$profile_id]) ? $list_url[$profile_id] : []; 

        $success = false;

        foreach ($url_profiles as $key => $url_path) {

            $path_parts = explode('/', $url_path);
            
            $url_path_current = [];

            foreach ($path_parts as $index => $str) {
                $url_path_current[] = $CI->uri->segment($index + 1);            
            }

            $path_current = implode('/', $url_path_current);

            if ($path_current == $url_path) {
                $success = true;
                break;
            }
        }

        if ($success == false) {
            redirect('login');
        }
    }
}
