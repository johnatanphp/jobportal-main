<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_seekers_requested_documents extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_rys_seekers_requested_document');
    }

    public function download($job_id, $seeker_id = null)
    {
        $filename = 'Lista-postulantes-documento-solicitados';

        $filters = [
            'job_id' => $job_id
        ];

        if (!is_null($seeker_id)) {
            $filters['seeker_id'] = $seeker_id;
        }

        $this->Report_rys_seekers_requested_document->build(
            $filters
        )->download($filename);
    }
}
