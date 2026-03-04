<?php
class Recruitment_attached_document extends CI_Model 
{
    public function delete($file_id = 0)
    {
        $this->db->where('ID', $file_id);
        return $this->db->delete('tbl_recruitment_attached_documents');
    }

    public function get_by_id($file_id)
    {
    	$this->db->from('tbl_recruitment_attached_documents');
    	$this->db->where('ID', $file_id);

    	return $this->db->get()->row();
    }

    public function get_other_file_by_id($file_id)
    {
        $this->db->select(
            array(
                'files.file_source',
                'files.ID AS file_ID',
                'files.name',
                'other_files.document_title'
            )
        );

        $this->db->from('tbl_recruitment_attached_documents files');
        $this->db->join('tbl_recruitment_seeker_other_documents other_files', 'files.ID=other_files.file_ID');

        $this->db->where('files.ID', $file_id);

        return $this->db->get()->row();
    }

    public function get_files($job_id, $candidate_id, $key)
    {
        $this->db->select([
            'documents.*'
        ]);
        $this->db->from('tbl_recruitment_attached_documents documents');
    
        $this->db->where('documents.job_ID', $job_id);
        $this->db->where('documents.seeker_ID', $candidate_id);
        $this->db->where('documents.key', $key);
      
        return $this->db->get()->result();
    }

    public function get_attachments($candidate_id, $key)
    {
        $this->db->select([
            'documents.created_at',
            'documents.seeker_ID AS seeker_id',
            'documents.ID AS file_id',
            'documents.file_source AS file_path',
            'documents.name AS file_name',
            'documents.job_id AS job_id', 
            'documents.process_id AS process_id',
            'documents.key AS document_key',
            'documents.document_title AS document_title'       
        ]);
        $this->db->from('tbl_recruitment_attached_documents documents');
        $this->db->where('documents.seeker_ID', $candidate_id);
        $this->db->where('documents.key', $key);

        $this->db->order_by('documents.created_at', 'DESC');
      
        return $this->db->get()->result();
    }

    public function remove($file_id = 0)
    {
        $this->load->library('Storage_lib');
        $rs_file = $this->get_by_id($file_id);

        if (!$rs_file) {
            return false;
        }

        if (empty($rs_file->file_source)) {
            return false;
        }

        $file_is_share = $this->db->get_where('tbl_recruitment_attached_documents', 
            array(
                'file_source' => $rs_file->file_source,
                'ID!=' => $file_id
            )
        )->row();

        $status = $this->delete($file_id);

        if ($file_is_share) {
            return true;   
        }

        $file_in_cloud = $this->storage_lib->has($rs_file->file_source);

        if ($file_in_cloud && $status) {
            $this->storage_lib->delete($rs_file->file_source);
        }

        if (!$file_in_cloud && $status) {
            $url_path = FCPATH . '/public/uploads/employer/recruitment_selection_documents/' . $rs_file->file_source;
            @unlink($url_path);
        }
    
        return $status;
    }

    public function upload_file(
        $job_id, 
        $candidate_id, 
        $document_key, 
        $config_file
    ) {                         
        $config['upload_path'] = $config_file['upload_path'];
        $config['allowed_types'] = isset($config_file['allowed_types']) ? $config_file['allowed_types'] : '*';
        $config['max_size'] = isset($config_file['max_size']) ? : '4000';
        $config['overwrite'] = true;
        $config['file_name'] = md5(uniqid(rand(0, 5000) . rand(5001, 9000), true)); 

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $response->error = $this->upload->display_errors('', '');
            return $response;
        } 

        $file_data = $this->upload->data();

        if (isset($file_data->error)) {
            return false;
        }

        $created_at = date('Y-m-d H:i:s');

        $data = array(
           'name' => $file_data['client_name'],
           'file_source' => $file_data['file_name'],
           'created_at' => $created_at,
           'key' => $document_key,
           'job_ID' => $job_id,
           'seeker_ID' => $candidate_id
        );

        $this->db->insert('tbl_recruitment_attached_documents', $data); 
    
        return $this->get_by_id($this->db->insert_id());     
    }

    public function upload_document(
        $job_id = 0, 
        $candidate_id = 0, 
        $document_key = null
    ) {
    
        $config_file = array(
            'upload_path' => FCPATH . 'public/uploads/employer/recruitment_selection_documents',
            'allowed_types' => 'gif|jpg|jpeg|png|GIF|JPG|JPEG|PNG|pdf|docx|doc'
        );

        return $this->upload_file(
            $job_id, 
            $candidate_id, 
            $document_key,
            $config_file
        );
    }

    public function get_counter_group_by_document_key(
        $job_id = null, 
        $candidate_id = 0
    )
    {
        $seeker = $this->Job_seeker->find($candidate_id);

        $this->db->select([
            'COUNT(*) AS total_count',
            '(CASE
                WHEN ers.exam_type_id = 1 THEN "certificate_emo"
                WHEN ers.exam_type_id = 2 THEN "certificate_covid19"
                ELSE ""
            END) AS document_key'
        ], false);
        
        $this->db->from('tbl_exam_request_seekers ers');
        $this->db->join(
            'tbl_exam_request_results results', 
            'ers.id=results.exam_request_seeker_id'
        );

        $this->db->where('ers.seeker_id', $candidate_id);
        $this->db->where('results.result_status!=', null);
        $this->db->group_by('ers.exam_type_id');

        $query1 = $this->db->get_compiled_select();

        $this->db->select([
            'COUNT(*) AS total_count',
            'key AS document_key'
        ]);
        $this->db->from('tbl_recruitment_attached_documents documents');
        $this->db->where('documents.seeker_ID', $candidate_id);
        $this->db->where_in('documents.key', ['certificate_emo', 'certificate_covid19']);
        $this->db->group_by('key');

        $query2 = $this->db->get_compiled_select();

        $this->db->select([
            'COUNT(*) AS total_count',
            'key AS document_key'
        ]);
        $this->db->from('tbl_recruitment_attached_documents documents');
    
        if (!is_null($job_id)) {
            $this->db->where('documents.job_ID', $job_id);
        }
        
        $this->db->where('documents.seeker_ID', $candidate_id);
        $this->db->where_not_in('documents.key', ['certificate_emo', 'certificate_covid19']);
        $this->db->group_by('key');

        $query3 = $this->db->get_compiled_select();

        $this->db->select([
            'COUNT(*) AS total_count',
            '"screnning" AS document_key'
        ]);
        
        $this->db->from('tbl_screening s');
        $this->db->where('s.document_number', $seeker->document_number);
        $this->db->where('s.response_code', 1);
        $this->db->group_by('s.document_number');

        $query4 = $this->db->get_compiled_select();
        
        $sql = "SELECT SUM(g.total_count) AS total_count, 
                       g.document_key  
                       FROM (" . $query1 . " UNION " . $query2 . " UNION " . $query3 . " UNION " . $query4 . ") g 
                       GROUP BY g.document_key;";

        $result = $this->db->query($sql)->result();
        
        $count_documents = [];

        foreach ($result as $key => $row) {
            $count_documents[$row->document_key] = $row->total_count;    
        }

        return $count_documents;
    }

    public function get_other_files(
        $job_id,
        $candidate_id,
        $stage = null
    )
    {
        $this->db->select([
            'files.file_source',
            'files.ID AS file_ID',
            'files.name',
            'files.document_title'   
        ]);

        $this->db->from('tbl_recruitment_attached_documents files');
        $this->db->where('files.job_ID', $job_id);
        $this->db->where('files.seeker_ID', $candidate_id);
        $this->db->where('key', 'other_documents');

        if ($stage !== null) {
            $this->db->where('files.stage', $stage);
        }
        
        return $this->db->get()->result();
    }

    public function upload_other_document(
       $data_input = array()
    )
    {
        $job_id = $data_input['job_id']; 
        $candidate_id = $data_input['candidate_id']; 
        $stage = $data_input['stage'];
        $document_title = trim($data_input['document_title']);

        $this->db->trans_start();

        $config_file = array(
            'upload_path' => FCPATH . 'public/uploads/employer/recruitment_selection_documents',
            'allowed_types' => '*'
        );
        
        $file =  $this->upload_file(
            $job_id, 
            $candidate_id, 
            'other_documents',
            $config_file
        );

        if (!$file) {
            return false;
        }

        $this->db->insert('tbl_recruitment_seeker_other_documents', 
            array(
                'document_title' => $document_title,
                'stage' => $stage,
                'file_ID' => $file->ID
            )
        );

        $this->db->trans_complete();

        $file = $this->get_other_file_by_id($file->ID);

        return $this->db->trans_status() === true ? $file : false;
    }
}
