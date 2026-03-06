<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Session_employer
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }
    
    public function create($userRow)
    {   
        $this->load->model('Employer_profile');

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
            'is_job_seeker' => FALSE,
            'is_employer' => FALSE,
            'current_profile_id' => FALSE,
            'user_dashboard' => ''
        ];

        $user_data = array_merge($user_data, $this->get_user_data_profile($userRow->ID));    
        
        $this->session->set_userdata($user_data);
    }

    public function select_profile($profile_id)
    {
        $user_data = $this->get_user_data_profile($this->session->userdata('user_id'), $profile_id);

        if (empty($user_data)) {
            return;
        }

        $this->session->set_userdata($user_data);
    }
    
    private function get_user_data_profile($user_id, $profile_id = 0)
    {
        if (!$profile_id) {

            $profiles = $this->Employer_profile->get_active_profiles($user_id);

            if (!$profiles) {
                return [];
            }

            $profile_id = ($profiles[0])->id;
        }

        $this->db->select([
            'profiles.id',
            'profiles.session_key',
            'profiles.folder',
            'profiles.dashboard',
            'profiles.name'
        ]);
        $this->db->from('tbl_employer_profiles user_profile');
        $this->db->join('tbl_profiles profiles', 'user_profile.profile_id=profiles.id');
        $this->db->where('profiles.active', 1);
        $this->db->where('user_profile.user_id', $user_id);
        $this->db->where('user_profile.profile_id', $profile_id);

        $profile_row = $this->db->get()->row();

        if (!$profile_row) {
            return [];
        }

        $user_data = [
            'user_folder' => '',
            'is_job_seeker' => FALSE,
            'is_employer' => FALSE
        ];

        $user_data['is_employer'] = TRUE;
        $user_data[$profile_row->session_key] = TRUE;
        $user_data['user_folder'] = $profile_row->folder;
        $user_data['current_profile_id'] = $profile_row->id;
        $user_data['user_dashboard'] = $profile_row->dashboard; 
        $user_data['profile'] = $profile_row; 

        return $user_data;
    }
    
    public function logout()
    {
        $this->load->library('App/Session/Session_logout');
        $this->session_logout->exec();
    }
}
