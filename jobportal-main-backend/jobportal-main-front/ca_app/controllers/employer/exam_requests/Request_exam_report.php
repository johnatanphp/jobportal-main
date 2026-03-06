<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_exam_report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        show_404();
    }

    public function export()
    {   
        if (!$this->input->get('exam_type')) {
            $data['title'] = 'Exportar Programaciones';
            $data['ads_row'] = $this->ads;        
            $data['date_ranges'] = get_date_ranges();
            $this->load->view('employer/exam_requests/exam_requests/exam_request_seeker_report', $data);
            return;
        }

        $filters = [
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'exam_type' => $this->input->get('exam_type')
        ];

        $this->load->library(
            'Exports/Exam_request_seekers_export',
            $filters,
            'Exam_request_seekers_export'
        );

        $this->Exam_request_seekers_export->download();
    }
}
