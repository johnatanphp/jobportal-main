<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rys_list_mo_documents extends CI_Controller
{
    public function search($job_id, $seeker_id)
    {
        $this->db->select([
            'doc_types.name AS doc_name',
            'doc_types.id AS doc_type_id',
            'documents.approved AS doc_approved',
            'documents.document_path AS doc_path',
            'documents.upload_date AS upload_date',
            'documents.id AS doc_id'
        ]);

        $this->db->from('tbl_exam_request_results documents');
        $this->db->join(
            'tbl_recruitment_candidates rys_seeker', 
            'documents.job_id=rys_seeker.job_ID AND rys_seeker.seeker_ID=documents.seeker_id'
        );
        $this->db->join('tbl_exam_request_types doc_types', 'documents.document_id=doc_types.id');
        
        $this->db->where('rys_seeker.job_ID', $job_id);
        $this->db->where('rys_seeker.seeker_ID', $seeker_id);

        $data['documents'] = $this->db->get()->result();
        $data['job_id'] = $job_id;
        $data['seeker_id'] = $seeker_id;

        $this->load->view('employer/exam_requests/common/list_mo_documents', $data);
    }
}
