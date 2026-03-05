<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_excel_rys_form_answers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rys_form');
    }

    public function download($form_id, $job_id, $stage = null)
    {
        $form = $this->Rys_form->get_form_by_id($form_id);

        $filters = [
            'form_id' => $form->form_id,
            'job_id' => $job_id,
            'stage' => $stage
        ];

        $file_name = preg_replace('/\s/', '-', $form->name) . '-RESPUESTAS-' . date('YmdHis');

        $this->load->library(
            'Exports/Rys_forms_answers_export',
            $filters,
            'Rys_forms_answers_export'
        );

        $this->Rys_forms_answers_export->download(
            $file_name
        );
    }
}
