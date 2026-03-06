<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class List_mo_documents extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Exam_request_result_type');
    }

    public function search($job_id, $seeker_id)
    {
        $this->db->select([
            'doc_types.name AS doc_name',
            'doc_types.id AS doc_type_id',
            'rys_exam_documents.result_status',
            'rys_exam_documents.document_path AS doc_path',
            'rys_exam_documents.certificate_path AS certificate_path',
            'rys_exam_documents.certificate_result_status',
            'rys_exam_documents.upload_date AS upload_date',
            'rys_exam_documents.id AS doc_id',
            'user.first_name AS user_first_name'
        ]);

        $this->db->from('tbl_exam_request_results rys_exam_documents');
        $this->db->join('tbl_exam_request_seekers exam_request_seekers',
            'exam_request_seekers.id=rys_exam_documents.exam_request_seeker_id'
        );
        $this->db->join(
            'tbl_recruitment_candidates rys_seeker', 
            'rys_exam_documents.job_id=rys_seeker.job_ID AND rys_seeker.seeker_ID=rys_exam_documents.seeker_id'
        );
        $this->db->join('tbl_exam_request_types doc_types', 'rys_exam_documents.document_id=doc_types.id');

        $this->db->join('tbl_employers user', 
            'rys_exam_documents.user_id=user.ID',
            'left'
        );
    
        $this->db->where('rys_seeker.job_ID', $job_id);
        $this->db->where('rys_seeker.seeker_ID', $seeker_id);
        $this->db->where('exam_request_seekers.active', 1);
        
        $data['documents'] = $this->db->get()->result();
        $data['job_id'] = $job_id;
        $data['seeker_id'] = $seeker_id;

        $this->load->view('employer/recruitment/common/rys_list_mo_documents', $data);
    }
}
