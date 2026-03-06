<?php

class Cconfig
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        $this->load_config_site();
        $this->load_config_company();
        $this->load_config_jobseeker();
    }

    //Load config site
    public function load_config_site()
    { 
        $ci =& get_instance();
        
        if ($ci->db->table_exists('tbl_app_config')) {

            foreach ($ci->db->get('tbl_app_config')->result_array() AS $config) {
                $ci->config->set_item($config['key'], $config['value']);
            }
        }
    }

    public function load_config_company()
    {
        $ci =& get_instance();
        $ci->load->model('Employer');

        if (!$ci->session->userdata('is_employer')) {
            return;
        }

        $user = $ci->Employer->find($ci->session->userdata('user_id'));

        $result = $ci->db->get_where('tbl_company_config', ['company_ID' => $user->company_ID])->result_array();
        $ci->load->model('Company_account_setting');

        foreach ($result as $setting) {
            $ci->Company_account_setting->set_item($setting['key'], $setting['value']);
        }      
    }

    public function load_config_jobseeker()
    {
        $ci =& get_instance();

        $user_id = $ci->session->userdata('user_id');

        if ($ci->session->userdata('is_job_seeker')) {
            $user = $ci->db->get_where('tbl_job_seekers', ['ID' => $user_id])->row();
        }
        
        $ci->load->model('Jobseeker_account_setting');

        if (!empty($user->ID)) {

            $result = $ci->db->get_where('tbl_seeker_config', ['seeker_ID' => $user->ID])->result_array();
            $ci->load->model('Jobseeker_account_setting');
            foreach ($result as $setting) {
                $ci->Jobseeker_account_setting->set_item($setting['key'], $setting['value']);
            }
        }      
    }
}
