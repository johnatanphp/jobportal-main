<?php

class Seeker_screening extends CI_Model
{
    public function get_last_by_document_number($document_number)
    {
        $this->db->from('tbl_screening');
        $this->db->where('document_number', $document_number);
        $this->db->order_by('ID', 'DESC');
        return $this->db->get()->row();
    }

    public function get_all_results(
        $params,
        $limit = -1
    )
    {
        $now = date('Y-m-d H:i:s');
        
        $this->load->library(
			'Screening/Screening_jobseeker_search_historical_lib', 
			null , 
			'Screening_jobseeker_search_historical_lib'
		);

        $job_id = isset($params['job_id']) ? $params['job_id'] : null;
        $seeker_id = isset($params['seeker_id']) ? $params['seeker_id'] : null;
        $document_number = $params['document_number'];

        //Table base tbl_recruitment_attached_documents
        $this->db->select([
            'attached_docs.ID AS id',
            '"" AS type_name',
            'file_source AS file_path',
            'created_at AS created_at',
            '"attach" AS origin',
            'pj.ID AS job_id',
            'pj.job_title AS job_title',
            '"NULL" AS its_data_prosecution',
            'srm_validity.month_validity',
            '"NULL" AS due_date',
            '"NULL" AS remaining_days'
        ]);
        
        $this->db->from('tbl_recruitment_attached_documents attached_docs');
        $this->db->join('tbl_job_seekers s', 'attached_docs.seeker_ID=s.ID');
        $this->db->join('tbl_post_jobs pj', 'pj.ID=attached_docs.job_ID');
        $this->db->join('tbl_staff_requests sr', 'sr.ID=pj.request_ID', 'left');
        $this->db->join('tbl_screening_staff_request_model_validities srm_validity', 'srm_validity.request_model_id=sr.request_model_id', 'left');
        $this->db->where('s.document_number', $document_number);
        $this->db->where('attached_docs.key', 'screnning');
        
        if (!empty($job_id)) {
            $this->db->where('attached_docs.job_id', $job_id);
        }

        if (!empty($seeker_id)) {
            $this->db->where('attached_docs.seeker_ID', $seeker_id);
        }

        //Table base tbl_screening
        $query1 = $this->db->get_compiled_select();

        $this->db->select([
            's.id AS id',
            'st.name AS type_name',
            '"" AS file_path',
            's.created_at AS created_at',
            '"requested" AS origin',
            'pj.ID AS job_id',
            'pj.job_title AS job_title',
            's.its_data_prosecution AS its_data_prosecution',
            'srm_validity.month_validity',
            'DATE_ADD(s.created_at, INTERVAL IFNULL(srm_validity.month_validity, 6) MONTH) AS due_date',
            'DATEDIFF(DATE_ADD(s.created_at, INTERVAL IFNULL(srm_validity.month_validity, 6) MONTH), "' . $now . '") AS remaining_days'
        ]);
        
        $this->db->from('tbl_screening s');
        $this->db->join('tbl_screening_types st', 'st.id=s.type_id', 'left');
        $this->db->join('tbl_post_jobs pj', 'pj.ID=s.job_id', 'left');
        $this->db->join('tbl_staff_requests sr', 'sr.ID=pj.request_ID', 'left');
        $this->db->join('tbl_screening_staff_request_model_validities srm_validity', 'srm_validity.request_model_id=sr.request_model_id', 'left');

        $this->db->where('s.document_number', $document_number);
        $this->db->where('s.response_code', 1);
        
        if (!empty($job_id)) {
            $this->db->where('s.job_id', $job_id);
        }

        if (!empty($seeker_id)) {
            $this->db->where('s.seeker_id', $seeker_id);
        }

        $query2 = $this->db->get_compiled_select();

        $limit_str = $limit > 0 ? " LIMIT " . $limit : "";

        $sql = "SELECT s.id,
                       s.type_name, 
                       s.file_path, 
                       s.created_at, 
                       s.origin,
                       s.job_id,
                       s.job_title,
                       s.its_data_prosecution,
                       s.due_date,
                       s.remaining_days
                FROM  (" . $query1 . " UNION " . $query2 . " ) s
                ORDER BY s.created_at DESC$limit_str";

        $result_1 = $this->db->query($sql)->result();

        $result_2 = $this->Screening_jobseeker_search_historical_lib->search($document_number);

        return array_merge($result_1, $result_2);
    }
}
