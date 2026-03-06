<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_form_answers extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_rys_seeker_requested_documents');
    }

    public function download($job_id)
    {
        $filename = 'Lista-postulantes-documento-solicitados.xlsx';
        $filters = [
            'job_id' => $job_id
        ];

        $this->Report_rys_seeker_requested_document->build(
            $filters
        )->download($filename);
    }
}
