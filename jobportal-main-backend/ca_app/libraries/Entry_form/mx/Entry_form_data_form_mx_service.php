<?php 
class Entry_form_data_form_mx_service
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Staff_request');
		$this->load->model('Identity_document_type');
        $this->load->model('Kinship');
        $this->load->model('Civil_status');
        $this->load->model('Gender');
        $this->load->model('Entry_form_mx');
    }

    public function get_data($process_id, $seeker_id)
    {
        $data['title'] = "Ficha de datos del trabajador - " . SITE_NAME;
        $jobseeker = $this->Job_seeker->find($seeker_id);
        $entry_form_mx = $this->Entry_form_mx->get_last_record_by_seeker_id($seeker_id);
        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);
        $document_types = $this->Identity_document_type->all(['country_id' => $process_country->ID, 'active' => 1]);
        
        // Partida de nacimiento
        $rightful_claimants_document_types = $this->Identity_document_type->all(['country_id' => $process_country->ID, 'key' => 'PN']);
        
        $data = [
            'jobseeker' => $jobseeker,
            'entry_form' => $entry_form_mx,
            'process_country' => $process_country,
            'rightful_claimants' => $this->Entry_form_mx->get_rightful_claimants(@$entry_form_mx->entry_form_id),
            'result_countries' => $this->Country->get_all_countries(),
            'document_types' => $document_types,
            'rightful_claimants_document_types' => array_merge($document_types, $rightful_claimants_document_types),
            'kinship_types' => $this->Kinship->all(['active' => 1]),
            'civil_status' => $this->Civil_status->all(['active' => 1]),
            'genders' => $this->Gender->all(['active' => 1]),
            'process_id' => $process_id
        ];
        
        return $data;
    }
}
