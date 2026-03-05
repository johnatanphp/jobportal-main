<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry_form_base extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!candidate_is_process_contracting()) {
            show_404();
        }

        //Load models
        $this->load->model('Recruitment_contract_document_type');
        $this->load->model('Entry_form');
        $this->load->model('Recruitment_process');

        $this->ads = $this->Ad->get_ads();
    }

    public function show($contract_document_type_id = 0, $process_id = 0)
    {
        $contract_document_type = $this->Recruitment_contract_document_type->get_info_by_id($contract_document_type_id);

        if (!$contract_document_type) {
            show_404();
        }

        $seeker_id = $this->session->userdata('user_id');

        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);

        $country_alpha2_code = @$process_country->iso_3166_1_alpha2;

        // Si la ficha de ingreso es de Peru.
        if ($country_alpha2_code == 'PE') {
            redirect('jobseeker/form_rtps/show/' . $contract_document_type_id . '/' . $process_id);
            return;
        }

        // Si la ficha de ingreso es de Mexico.
        if ($country_alpha2_code == 'MX') {
            redirect('jobseeker/entry_form/mx/form_mx/show/' . $contract_document_type_id . '/' . $process_id);
            return;
        }

        // Si la ficha de ingreso es de Ecuador.
        if ($country_alpha2_code == 'EC') {
            redirect('jobseeker/entry_form/ec/form_ec/show/' . $contract_document_type_id . '/' . $process_id);
            return;
        }

        echo "Ficha de ingreso para: " . $process_country->country_name . " No esta definida";
    }
}
