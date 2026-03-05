<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_document_stages extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();

        //Load models
        $this->load->model('Recruitment_process_document_stage');
        $this->load->model('Recruitment_stage');
    }

    public function index()
    {
        $job_id = $this->input->get('job_id');
        $data['job_id'] = $job_id;
        $data['curent_stage_id'] = $this->input->get('stage_id');
       
        $data['stages'] = $this->Recruitment_stage->all(['active' => 1, 'stage_group_id' => 1]);

        $this->load->view('employer/recruitment/process_document_stages/common/document_stage_config', $data);
    }

    public function save()
    {
        $documents = $this->input->post('document_ids');

        $job_id = trim($this->input->post('job_id'));
        $stage_id = trim($this->input->post('stage_id'));

        $this->db->where('job_id', $job_id);
        $this->db->where('stage_id', $stage_id);
        $this->db->delete('tbl_recruitment_process_documents_stages');

        foreach ($documents as $document_id) {

            $this->db->insert('tbl_recruitment_process_documents_stages', [
                'job_id' => $job_id,
                'document_id' => $document_id,
                'stage_id' => $stage_id
            ]);
        }

        echo json_encode([
            'status' =>  true,
            'message' => 'OK'
        ]);
    }
}
