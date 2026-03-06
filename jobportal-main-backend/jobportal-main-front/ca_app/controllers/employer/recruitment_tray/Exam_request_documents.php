<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_request_documents extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Recruitment_attached_document');
        $this->load->model('Exam_request_result_type');
        $this->load->model('Recruitment_document_type');
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_tray_candidate');

        //Load libraries
        $this->load->library('Storage_lib');
        $this->load->library('Exam_request/Exam_request_get_all_lib');
    }

    public function list($process_id, $candidate_id = 0, $document_key = null)
    {
        $tray_candidate = $this->Recruitment_tray_candidate->find([
            'process_id' => $process_id, 
            'seeker_id' => $candidate_id
        ]);

        $data['process_id'] = $process_id;
		$data['candidate_id'] = $candidate_id;
        $data['tray_candidate'] = $tray_candidate;
        $data['document'] = $this->Recruitment_document_type->find_by_key($document_key);
		
		$data['exam_request_results'] = $this->exam_request_get_all_lib->results(
            $process_id,
            $candidate_id, 
            $document_key
        );

		$this->load->view('employer/recruitment_tray/recruitment_documents/common/exam_request_result_content', $data);
    }
}
