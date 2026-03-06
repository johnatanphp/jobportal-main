<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rys_assign_mo_documents extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($job_id)
    {
        $stages = $this->db->get_where('tbl_recruitment_stages', [
                'active' => 1, 'id!=' => 0
            ])
            ->result();
        $data['stages'] = $stages;
        $data['documents'] = $this->get_documents($job_id);
        $data['job_id'] = $job_id;

        $this->load->view(
            'employer/recruitment/modal/assign_mo_documents', 
            $data
        );
    }

    private function get_documents($job_id)
    {
        $this->db->select([
            'document.id AS document_id',
            'document.name AS document_name',
            'document_stage.exam_date',
            'document_stage.stage',
            'document_stage.type_exam_covid19'
        ]);

        $this->db->from('tbl_exam_request_types document');
        $this->db->join(
            'tbl_rys_exam_document_stages document_stage', 
            'document_stage.document_id=document.id AND document_stage.job_id="' . $job_id . '"',
            'left'
        );

        return $this->db->get()->result();
    }

    public function save()
    {
        $job_id = $this->input->post('job_id');
        $documents = $this->input->post('documents') ? $this->input->post('documents') : [];

        $this->db->trans_start();

        foreach ($documents as $doc) {

            if (!isset($doc['doc_id']) || 
                isset($doc['exam_date']) && trim($doc['exam_date']) == '') {
                continue;
            }

            $document_id = $doc['doc_id'];

            $stage = $doc['stage'];
        
            $this->db->where('job_id', $job_id);
            $this->db->where('document_id', $document_id);
            $this->db->delete('tbl_rys_exam_document_stages');

            if (trim($stage) != '') {

                $data_stage = [
                    'job_id' => $job_id,
                    'stage' => $stage,
                    'exam_date' => $doc['exam_date'],
                    'document_id' => $document_id
                ];

                if (isset($doc['type_exam_covid19']) && 
                    $doc['type_exam_covid19'] != '') {
                    $data_stage['type_exam_covid19'] = $doc['type_exam_covid19'];
                }

                $this->db->insert('tbl_rys_exam_document_stages', $data_stage);
            }               
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode([
            'success' => $status
        ]);
    }
}
