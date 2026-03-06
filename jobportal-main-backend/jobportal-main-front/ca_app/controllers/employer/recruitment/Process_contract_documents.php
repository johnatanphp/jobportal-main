<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_contract_documents extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();

        //Load models
        $this->load->model('Recruitment_process_contract_document');
    }

    public function index($job_id)
    {
        $data['documents'] = $this->Recruitment_process_contract_document->get_documents($job_id);
        $data['job_id'] = $job_id;

        $this->load->view('employer/recruitment/common/recruitment_process_contract_documents', $data);
    }

    public function save()
    {
        $document_ids = $this->input->post('document_ids');
        $job_id = trim($this->input->post('job_id'));

        $this->db->where('job_id', $job_id);
        $this->db->delete('tbl_recruitment_process_contract_documents');

        foreach ($document_ids as $document_id) {
            $this->db->insert('tbl_recruitment_process_contract_documents', [
                'job_id' => $job_id,
                'document_id' => $document_id
            ]);
        }
    }
}
