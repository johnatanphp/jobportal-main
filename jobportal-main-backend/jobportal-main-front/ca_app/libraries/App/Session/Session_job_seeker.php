<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Session_job_seeker
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function create($userRow)
    {   
        if (!$userRow) {
            return;
        }

        $slug = @$userRow->company_slug;
        
        $user_data = [
            'user_id' => $userRow->ID,
            'user_email' => $userRow->email,
            'first_name' => $userRow->first_name,
            'last_name' => $userRow->last_name,
            'slug' => $slug,
            //'user_type' => $user_type,
            'user_folder' => '',
            'is_user_login' => TRUE,
            'is_job_seeker' => TRUE,
            'is_employer' => FALSE,
            'current_profile_id' => FALSE,
            'user_dashboard' => 'jobseeker/dashboard',
            'menu' => 1,
            'request_documents' => false
        ];

        $this->session->set_userdata($user_data);
    }
    
    public function logout()
    {
        $this->load->library('App/Session/Session_logout');
        $this->session_logout->exec();
    }
}
