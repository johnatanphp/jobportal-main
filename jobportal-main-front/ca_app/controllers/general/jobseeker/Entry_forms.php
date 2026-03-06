<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Entry_forms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        //Load models
        $this->load->model('Entry_form');
    }

    public function load_view($entry_form_id = 0)
    {        
        $entry_form = $this->Entry_form->find([
            'id' => $entry_form_id
        ]);

        if (!$entry_form) {
            show_404();
        }

        $country = $this->Country->get_country_by_id($entry_form->form_country_id);

        $country_alpha2_code = $country->iso_3166_1_alpha2;

        // Ficha de ingreso Mexico
        if ($country_alpha2_code == 'MX') {
            $this->load->library('Entry_form/mx/Entry_form_data_show_mx_service');
            $data = $this->entry_form_data_show_mx_service->get_data($entry_form->process_id, $entry_form->seeker_id);
            $this->load->view('jobseeker/entry_form/mx/common/employer_show', $data);
            return;
        }

         // Ficha de ingreso Ecuador
        if ($country_alpha2_code == 'EC') {
            $this->load->library('Entry_form/ec/Entry_form_data_show_ec_service');
            $data = $this->entry_form_data_show_ec_service->get_data($entry_form->process_id, $entry_form->seeker_id);
            $this->load->view('jobseeker/entry_form/ec/common/employer_show', $data);
            return;
        }
    }

    public function download($entry_form_id = 0)
    {
        $user_id = $this->session->userdata('user_id');

        if (!$user_id) {
            show_404();
        }

        $entry_form = $this->Entry_form->find([
            'id' => $entry_form_id
        ]);

        if (!$entry_form) {
            show_404();
        }
        
        if ($this->session->userdata('is_job_seeker') && $user_id != $entry_form->seeker_id) {
            show_404();
        }

        $country = $this->Country->get_country_by_id($entry_form->form_country_id);

        $country_alpha2_code = $country->iso_3166_1_alpha2;

        // Ficha de ingreso Mexico
        if ($country_alpha2_code == 'MX') {
            $this->load->library('Pdf/Entry_form/mx/Entry_form_mx_pdf');
            $this->entry_form_mx_pdf->download($entry_form_id);
            return;
        }

        // Ficha de ingreso Ecuador
        if ($country_alpha2_code == 'EC') {
            $this->load->library('Pdf/Entry_form/ec/Entry_form_ec_pdf');
            $this->entry_form_ec_pdf->download($entry_form_id);
            return;
        }
    }
}
