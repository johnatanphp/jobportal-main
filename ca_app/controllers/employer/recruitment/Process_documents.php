<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_documents extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();

        //Load models
        $this->load->model('Recruitment_process_document');
    }

    public function index($job_id)
    {
        $data['rys_documents'] = $this->Recruitment_process_document->get_documents($job_id);
        $data['job_id'] = $job_id;

        $this->load->view('employer/recruitment/common/recruitment_document_config', $data);
    }

    public function save()
    {
        $document_ids = $this->input->post('document_ids');
        $job_id = trim($this->input->post('job_id'));

        $this->db->where('job_id', $job_id);
        $this->db->delete('tbl_recruitment_process_documents');

        foreach ($document_ids as $document_id) {
            $this->db->insert('tbl_recruitment_process_documents', [
                'job_id' => $job_id,
                'document_id' => $document_id
            ]);
        }
    }
}
