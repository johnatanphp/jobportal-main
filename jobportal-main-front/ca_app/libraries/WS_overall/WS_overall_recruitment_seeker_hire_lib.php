<?php

class WS_overall_recruitment_seeker_hire_lib 
{   
    public function __construct()
    {
        $this->load->model('Recruitment_process');
        $this->load->model('Country');
        $this->load->model('Staff_request');
    }

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {      
        $country = $this->Recruitment_process->get_country_by_process_id($params['process_id']);
        $country_iso_code = $country && $country->iso_3166_1_alpha2 ? $country->iso_3166_1_alpha2 : null;
        
        // Migracion Peru
        if ($country_iso_code == 'PE') {
            $this->load->library('WS_overall/WS_overall_pe_recruitment_seeker_hire_lib');
            return $this->ws_overall_pe_recruitment_seeker_hire_lib->exec($params);
        }

        // Migracion Mexico
        if ($country_iso_code == 'MX') {
            $this->load->library('WS_overall/WS_overall_mx_recruitment_seeker_hire_lib');
            return $this->ws_overall_mx_recruitment_seeker_hire_lib->exec($params);
        }

        // Migracion Ecuador
        if ($country_iso_code == 'EC') {
            $this->load->library('WS_overall/WS_overall_ec_recruitment_seeker_hire_lib');
            return $this->ws_overall_ec_recruitment_seeker_hire_lib->exec($params);
        }

        return [
            'status' => false,
            'message' => 'No hay sincronización de datos para el pais del proceso del postulante'
        ];
    }
}
