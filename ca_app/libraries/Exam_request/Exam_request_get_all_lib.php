<?php 
class Exam_request_get_all_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function results($job_id, $seeker_id, $doc_key)
    {
        $exam_type_id = 0;

        if ($doc_key == 'certificate_emo') {
            $exam_type_id = 1;
        }

        if ($doc_key == 'certificate_covid19') {
            $exam_type_id = 2;
        }
        
        $this->db->select([
            'results.id AS id',
            'results.created_at AS result_date',
            '"Programado" AS result_origin',
            'results.result_file AS result_file',
            'result_types.result_name AS result_name'
        ]);
        
        $this->db->from('tbl_exam_request_seekers ers');
        $this->db->join('tbl_exam_request_results results', 'ers.id=results.exam_request_seeker_id');
        $this->db->join('tbl_exam_request_result_types result_types', 'result_types.id=results.result_status');

        //$this->db->where('ers.job_id', $job_id);
        $this->db->where('ers.seeker_id', $seeker_id);
        $this->db->where('ers.exam_type_id', $exam_type_id);
        
        $query1 = $this->db->get_compiled_select();

        $this->db->select([
            'documents.ID AS id',
            'documents.created_at AS result_date',
            '"Adjunto" AS result_origin',
            'documents.file_source AS result_file',
            '"" AS result_name'
        ]);
        $this->db->from('tbl_recruitment_attached_documents documents');
    
        //$this->db->where('documents.job_ID', $job_id);
        $this->db->where('documents.seeker_ID', $seeker_id);
        $this->db->where('documents.key', $doc_key);
       
        $query2 = $this->db->get_compiled_select();

        return $this->db->query($query1 . " UNION " . $query2 . " order by result_date DESC")->result();
    }
}
