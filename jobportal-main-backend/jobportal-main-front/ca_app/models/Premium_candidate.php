<?php
class Premium_candidate extends CI_Model {
  
    public function get_all($job_id = 0)
    {    
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        $this->db->select(
            array(
                'candidates.ID AS candidate_id',
                'candidates.email',
                'candidates.first_name',
                'candidates.last_name',
                'candidates.photo',
                'jobs.job_title',
                'candidate_stages.datetime AS last_stage_date',
                'candidate_stages.job_ID AS last_job_id'
            )
        );
        $this->db->from('tbl_recruitment_candidates rs_candidates');
        $this->db->join('tbl_job_seekers candidates', 'rs_candidates.seeker_ID=candidates.ID');
        $this->db->join('tbl_recruitment_process rs_processes', 'rs_processes.job_ID=rs_candidates.job_ID');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=rs_processes.job_ID');
        $this->db->join(
            'tbl_recruitment_log_candidate_stage candidate_stages', 
            'candidate_stages.seeker_ID=rs_candidates.seeker_ID AND rs_candidates.stage=candidate_stages.stage AND candidate_stages.job_ID=rs_processes.job_ID'
        );

        $this->db->where('rs_candidates.stage', 5);
        $this->db->where('rs_candidates.discarded', 0);
        $this->db->where('rs_processes.sts', 'finished');
        $this->db->where('rs_processes.job_ID!=', $job_id);
        $this->db->like('jobs.job_title', $job->job_title);

        $this->db->order_by('candidate_stages.datetime', 'DESC');
        
        $this->db->group_by('candidates.ID');

        return $this->db->get()->result();
    }

    public function add_candidate_to_stage($data)
    {
        $job_id = $data['job_id'];
        $stage = $data['stage'];
        $candidate_id = $data['candidate_id'];
        $from_job_id = $data['from_job_id'];

        $this->db->trans_start();

        $is_candidate_in_process = $this->Recruitment_candidate->is_candidate_in_process(
            $job_id, 
            $candidate_id
        );

        if ($is_candidate_in_process) {
            return false;
        }
                    
        $this->Recruitment_process->open_process_if_not_open($job_id);

        $this->Recruitment_candidate->register_log_candidate_stage(
            $job_id, 
            $candidate_id, 
            $stage
        );

        $data = array(
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'stage' => $stage,
            'creation_date' => date('Y-m-d')
        );

        $this->db->insert('tbl_recruitment_candidates', $data);
        
        $this->Recruitment_candidate->update_process_stage($job_id);

        $this->copy_rs_documents($candidate_id, $job_id, $from_job_id);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    private function copy_rs_documents($candidate_id, $job_id, $from_job_id)
    {
        $this->db->from('tbl_recruitment_attached_documents');
        $this->db->where('seeker_ID', $candidate_id);
        $this->db->where('job_ID', $from_job_id);
        $this->db->where_in('key', array(
            'evaluation_answer',
            'report_competence',
            'work_reference'
        ));

        $rs_documents = $this->db->get()->result();

        foreach ($rs_documents as $row) {

            $document = $this->db->get_where('tbl_recruitment_attached_documents', 
                array(
                    'key' => $row->key,
                    'file_source' => $row->file_source,
                    'seeker_ID' => $row->seeker_ID,
                    'job_ID' => $job_id
                )
            )->row();

            if ($document) {
                continue;
            }

            $data_document = array(
                'name' => $row->name,
                'file_source' => $row->file_source,
                'created_at' => date('Y-m-d H:i:s'),
                'key' => $row->key,
                'seeker_ID' => $row->seeker_ID,
                'job_ID' => $job_id
            );
            $this->db->insert('tbl_recruitment_attached_documents', $data_document);
        }
    }
}

