<?php

class Exam_request_result extends CI_Model
{
    //protected $table = 'tbl_exam_request_results';
    //protected $primary_key = 'id';

    public function find($id)
    {
        $this->db->from('tbl_exam_request_results');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    /* Pendiente */
    public function get_documents_for_exam_type($exam_type_id)
    {
    	$exam_type_ids = [];

        if ($exam_type_id == 4) { //EMO + COVID 19
            $exam_type_ids = [1, 2];
        } else {
            $exam_type_ids[] = $exam_type_id ;
        }

        $this->db->select([
        	'request_document_types.exam_type_id',
        	'request_document_types.document_type_id',
        	'rys_document_types.key as document_key',
        ]);
        $this->db->from('tbl_exam_request_document_types request_document_types');
        $this->db->join('tbl_recruitment_document_types rys_document_types', 
            'request_document_types.document_type_id=rys_document_types.id'
        );

        $this->db->where_in('request_document_types.exam_type_id', $exam_type_ids);

        return $this->db->get()->result();
    }

    public function search_candidates(
        $filter, 
        $per_page = 0, 
        $page = 0
    )
    {
        $this->db->select([
            'DISTINCT(recruitment_candidate.seeker_ID)',
            'COUNT(recruitment_candidate.seeker_ID) as count_doc_without_uploading',
            'candidate.*',
            'recruitment_candidate.*',
            'job.*',
            'exam_request_seekers.request_id AS exam_request_id',
            'exam_request_seekers.exam_date',
            'medical_center.name AS medical_center_name',
            'exam_request_types.name AS exam_request_type_name'
        ]);
        
        $this->db->from('tbl_exam_request_results exam_results');
        $this->db->join('tbl_exam_request_seekers exam_request_seekers', 
            'exam_results.exam_request_seeker_id=exam_request_seekers.id'
        );
        $this->db->join('tbl_recruitment_candidates recruitment_candidate', 
            'recruitment_candidate.job_ID=exam_request_seekers.job_id AND exam_request_seekers.seeker_id=recruitment_candidate.seeker_ID'
        );
        
        $this->db->join('tbl_job_seekers candidate', 'candidate.ID=recruitment_candidate.seeker_ID');
        $this->db->join('tbl_post_jobs job', 'job.ID=recruitment_candidate.job_ID');
        $this->db->join('tbl_medical_centers medical_center', 
            'medical_center.code=exam_request_seekers.medical_center_code'
        );

        $this->db->join('tbl_exam_request_types exam_request_types', 
            'exam_request_types.id=exam_request_seekers.exam_type_id' 
        );

        $this->db->where('recruitment_candidate.discarded', 0);
        $this->db->where('exam_request_seekers.status', 5);
        $this->db->where('exam_request_seekers.active', 1);
        $this->db->where('exam_results.loaded', $filter['loaded']);

        if (isset($filter['company_id'])) {
            $this->db->where('job.company_ID', $filter['company_id']);
        }
        
        $query = trim($filter['query']);

        if ($query != '') {
            $this->db->group_start();
            $query_is_digit = ctype_digit(strval($query));

            if ($query_is_digit) {
                $this->db->like('candidate.document_number', $query);
            } else {
                $this->db->like('candidate.first_name', $query);
                $this->db->or_like('candidate.last_name', $query);
            }

            $this->db->group_end();
        }

        $this->db->group_by([
            'recruitment_candidate.job_ID', 
            'recruitment_candidate.seeker_ID', 
            'exam_request_seekers.exam_type_id'
        ]);

        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

        return $this->db->get()->result();
    }

    public function count_candidates(
        $filter
    )
    {
        $this->db->select([
            'recruitment_candidate.seeker_ID',
            'COUNT(recruitment_candidate.seeker_ID) as count_doc_without_uploading'
        ]);
        
        $this->db->from('tbl_exam_request_results exam_results');
        $this->db->join('tbl_exam_request_seekers exam_request_seekers', 
            'exam_results.exam_request_seeker_id=exam_request_seekers.id'
        );
        $this->db->join('tbl_recruitment_candidates recruitment_candidate', 
            'recruitment_candidate.job_ID=exam_request_seekers.job_id AND exam_request_seekers.seeker_id=recruitment_candidate.seeker_ID'
        );
        
        $this->db->join('tbl_job_seekers candidate', 'candidate.ID=recruitment_candidate.seeker_ID');
        $this->db->join('tbl_post_jobs job', 'job.ID=recruitment_candidate.job_ID');
        
        $this->db->where('recruitment_candidate.discarded', 0);
        $this->db->where('exam_results.loaded', 0);
        $this->db->where('exam_request_seekers.status', 5);
        $this->db->where('exam_request_seekers.active', 1);
        $this->db->where('exam_results.loaded', $filter['loaded']);

        if (isset($filter['company_id'])) {
            $this->db->where('job.company_ID', $filter['company_id']);
        }

        $query = trim($filter['query']);

        if ($query != '') {
            $this->db->group_start();
            $query_is_digit = ctype_digit(strval($query));

            if ($query_is_digit) {
                $this->db->like('candidate.document_number', $query);
            } else {
                $this->db->like('candidate.first_name', $query);
                $this->db->or_like('candidate.last_name', $query);
            }

            $this->db->group_end();
        }

        $this->db->group_by([
            'recruitment_candidate.job_ID', 
            'recruitment_candidate.seeker_ID', 
            'exam_request_seekers.exam_type_id'
        ]);

        return $this->db->count_all_results();
    }

    public function get_exam_results_by_seeker($job_id, $seeker_id) 
    {
        $this->db->select([
            'documents.id AS exam_result_id',
            'documents.type',
            'documents.result_status',
            'exam_doc_types.name AS exam_name',
            'exam_doc_types.id AS exam_type_id',
            'document_types.name AS exam_document_name',
            'doc_files.file_source AS file_path',
            'doc_files.loaded_by',
            'doc_files.id AS doc_id',
            'exam_request_seekers.id AS seeker_request_id',
            'exam_request_seekers.exam_date AS exam_date'
        ]);

        $this->db->from(
            'tbl_exam_request_results documents'
        );
        $this->db->join('tbl_exam_request_seekers exam_request_seekers', 
            'exam_request_seekers.id=documents.exam_request_seeker_id'
        );

        $this->db->join('tbl_recruitment_document_types document_types', 
            'document_types.key=documents.document_type'
        );

        $this->db->join('tbl_exam_request_types exam_doc_types', 'documents.exam_type_id=exam_doc_types.id');
        $this->db->join('tbl_recruitment_attached_documents doc_files', 
            'doc_files.ID=documents.result_file_id',
            'left'
        );
  
        $this->db->where('exam_request_seekers.job_id', $job_id);
        $this->db->where('exam_request_seekers.seeker_id', $seeker_id);
        $this->db->where('exam_request_seekers.status', 5);
        $this->db->where('exam_request_seekers.active', 1);

        return $this->db->get()->result();
    }

    public function register_results($exam_request_seeker_exam)
    {
        $result_options = [];

        //EMO
        if ($exam_request_seeker_exam->exam_type_id == 1) {
            $result_options[1][] = [
                'document_type' => 1, //Certificado EMO
                'document_key' => 'certificate_emo',
                'type' => null
            ];

            $result_options[1][] = [
                'document_type' => 2, //Resultado
                'document_key' => 'results_emo',
                'type' => null
            ];
        }

        //COVID-19
        if ($exam_request_seeker_exam->exam_type_id == 2) {
            
            $covid19_options = explode(',', $exam_request_seeker_exam->exam_doc_type);
            foreach ($covid19_options as $type) {
                $result_options[2][] = [
                    'document_type' => 10, //Certificado covid-19
                    'document_key' => 'certificate_covid19',
                    'type' => $type //Tipo de examen
                ];

                $result_options[2][] = [
                    'document_type' => 11, //Resultado covid-19
                    'document_key' => 'results_covid19',
                    'type' => $type
                ];
            }
        }

        //Screening
        if ($exam_request_seeker_exam->exam_type_id == 3) {
            $result_options[3][] = [
                'document_type' => 3, //Certificado Screening
                'document_key' => 'certificate_screnning',
                'type' => null
            ];

            $result_options[3][] = [
                'document_type' => 4, //Resultado Screenig
                'document_key' => 'results_screnning',
                'type' => null
            ];
        }

        //Registrar solicitud de documentos del examen realizado
        foreach ($result_options as $exam_type_id => $results) {

            foreach ($results as $key => $row_result) {
                $data_results = [
                    'exam_request_seeker_id' => $exam_request_seeker_exam->exam_request_seeker_id,
                    'document_type' => $row_result['document_key'],
                    'type' => $row_result['type'],
                    'exam_type_id' => $exam_type_id
                ];

                $this->db->insert('tbl_exam_request_results', $data_results);
            }
        }
    }

    public function get_name_file($exam_type_id, $type)
    {   
        $this->load->model('Exam_document');

        $exam_type = $this->Exam_document->find($exam_type_id);
        $row_type = null;

        if ($exam_type_id == 2) {
            $row_type = $this->db->get_where('tbl_exam_covid19_types', [
                'id' => $type
            ])->row();
        }

        return $exam_type->name . ($row_type ? ' - ' . $row_type->name : '');
    }
}
