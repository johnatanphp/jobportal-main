<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employer_check_privileges_hook
{
    public function index() 
    {
        $CI = & get_instance();
        
        $user_id = $CI->session->userdata('user_id');
        
        if (empty($user_id) || 
            $CI->session->userdata('is_job_seeker')) {
            return;
        }

        $app_user = $CI->Employer->find($user_id);
        $company = $CI->Company->find($app_user->company_ID);

        if ($company->system_internal) {
            return;
        }

        $deny_list_urls = [
            'employer/overall_employees',
            'employer/all_staff_requests',
            'employer/my_assigned_staff_requests',
            'employer/staff_requests_follow_up',
            'employer/staff_requests',
            'employer/rys_forms',
            'employer/settings',
            'employer/recruitment_entry',
            'employer/entry_job_seekers',
            'general/authorities'
        ];

        foreach ($deny_list_urls as $key => $url_path) {

            $path_parts = explode('/', $url_path);
            
            $url_path_current = [];

            foreach ($path_parts as $index => $str) {
                $url_path_current[] = $CI->uri->segment($index + 1);            
            }

            $path_current = implode('/', $url_path_current);

            if ($path_current == $url_path) {
                show_404();
                break;
            }
        }
    }
}
