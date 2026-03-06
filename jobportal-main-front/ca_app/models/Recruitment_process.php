<?php
class Recruitment_process extends CI_Model {

    public function find($where_or_id = [])
    {
        $this->db->from('tbl_recruitment_process rs_process');
        if (is_array($where_or_id)) {
            $this->db->where($where_or_id);
        } else {
            $this->db->where('id', $where_or_id);
        }
        
        return $this->db->get()->row();
    }

    public function get_process_by_id($process_id)
    {
        $this->db->select(array(
            'rs_process.*',
            'rs_stages.id AS stage',
            'rs_stages.id AS stage_id',
            'rs_stages.name AS stage_name'
        ));

        $this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('tbl_recruitment_stages rs_stages', 'rs_stages.id=rs_process.sts_stage', 'left');

        $this->db->where('rs_process.id', $process_id);
                
        return $this->db->get()->row();
    }

    public function get_process_by_job_id($job_id)
    {
        $this->db->select(array(
            'rs_process.*',
            'rs_candidate.stage'
        ));

        $this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('tbl_recruitment_candidates rs_candidate', 'rs_process.job_ID=rs_candidate.job_ID', 'left');
         
        $this->db->where('rs_process.job_ID', $job_id);
        $this->db->order_by('stage', 'DESC');
        $this->db->group_by('rs_process.job_ID');
        
        return $this->db->get()->row();
    }

    public function open_process($job_id)
    {  
        $data_open_process = array(
            'job_ID' => $job_id,
            'sts' => 'active'
        );

        return $this->db->insert('tbl_recruitment_process', $data_open_process);
    }

    public function finish_process($job_id, $note, $rrhh_user_ids)
    {
        $this->db->trans_start();
        /*
        $stage_selection = 6; //Etapa de Selección
        $stage_contracting_process = 7; //Etapa de proceso de contratación

        $candidates = $this->Recruitment_candidate->get_available_candidates_by_stage(
            $job_id, 
            $stage_selection
        );

        //Mover candidatos a PROCESO DE CONTRATACIÓN
        foreach ($candidates as $row_candidate) {
            $this->Recruitment_candidate->move_candidate(
                $job_id, 
                $row_candidate->ID, 
                $stage_contracting_process
            );
        }

        //Asignar proceso a el personal de RR. HH.
        foreach ($rrhh_user_ids as $user_id) {

            $this->db->where('job_ID', $job_id);
            $this->db->where('rrhh_user_ID', $user_id);
            $this->db->delete('tbl_recruitment_rrhh_assignments');

            $data_assignment = array(
                'job_ID' => $job_id,
                'date_assignment' => date('Y-m-d'),
                'rrhh_user_ID' => $user_id
            );

            $this->db->insert('tbl_recruitment_rrhh_assignments', $data_assignment);
        }
        */
        
        //Terminar proceso 
        $data_update_sts = array(
            'sts' => 'finished',
            'note' => $note
        );

        $this->update_process_sts($job_id, $data_update_sts);
        
        $trans_status = $this->db->trans_complete();

        // if ($trans_status == true) {
            
        //     $this->send_email_requesting_documents_to_candidates(
        //         $job_id,
        //         $candidates
        //     );
        // }

        return $trans_status;
    }

    public function finish_process_external($job_id, $note)
    {
        $this->db->trans_start();

        //Terminar proceso 
        $data_update = [
            'sts' => 'finished',
            'note' => $note
        ];

        $this->update_process_sts($job_id, $data_update);
        
        $trans_status = $this->db->trans_complete();

        return $trans_status;
    }

    public function resume_process($job_id)
    {
        $this->db->trans_start();

        $data_update_sts = array(
            'sts' => 'active',
            'resumed' => 1,
            'note' => ''
        );

        $trans_status = $this->Recruitment_process->update_process_sts(
            $job_id, 
            $data_update_sts
        );

        $posted_job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$posted_job) {
            return false;
        }

        // Reanudar también la solicitud personal
        // Del proceso de reclutamiento si tiene un requerimiento asociado
        
        if ($posted_job->request_ID != null) {
            
            $data_update_sts = array(
                'sts_process' => 'published'
            );

            $this->db->where('ID', $posted_job->request_ID);
            $this->db->where('sts_process', 'suspended');
            $this->db->update('tbl_staff_requests', $data_update_sts);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function update_process_sts($job_id, $data_update)
    { 

        $process_status = $data_update['sts'] ?? null;

        if ($process_status == 'finished') {
            $data_update['closed_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('job_ID', $job_id);
        $trans_status = $this->db->update('tbl_recruitment_process', $data_update);

       

        if ($process_status == 'suspended' || $process_status == 'finished') {
            $this->db->where('ID', $job_id);
            $this->db->update('tbl_post_jobs', [
                'sts' => 'inactive'
            ]);
        }

        if ($process_status == 'active') {
            $this->db->where('ID', $job_id);
            $this->db->update('tbl_post_jobs', [
                'sts' => 'active'
            ]);
        }

        return $trans_status;
    }

    public function open_process_if_not_open($job_id)
    {
        $recruitment_process = $this->get_process_by_job_id($job_id);

        if ($recruitment_process) {
            return true;
        }
        
        return $this->open_process($job_id);
    }

    public function suspend_process($job_id, $note)
    {
        $this->db->trans_start();

        $data_update_sts = array(
            'sts' => 'suspended',
            'note' => $note
        );

        $trans_status = $this->Recruitment_process->update_process_sts(
            $job_id, 
            $data_update_sts
        );

        $posted_job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$posted_job) {
            return false;
        }

        // Cancelar también la solicitud personal
        // Del proceso de reclutamiento si tiene un requerimiento asociado
        
        if ($posted_job->request_ID != null) {
            
            $data_update_sts = array(
                'sts_process' => 'suspended'
            );

            $this->db->where('ID', $posted_job->request_ID);
            $this->db->where('sts_process', 'published');
            $this->db->update('tbl_staff_requests', $data_update_sts);
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_rrhh_users_assigned_by_job_id($job_id)
    {
        $this->db->select(array(
            'app_users.email',
            'app_users.first_name',
            'app_users.last_name',
        ));
        $this->db->from('tbl_recruitment_rrhh_assignments rrhh_assignment');
        $this->db->join('tbl_employers app_users', 'rrhh_assignment.rrhh_user_ID=app_users.ID');
        $this->db->where('rrhh_assignment.job_ID', $job_id);
        
        return $this->db->get()->result();
    }

    private function send_email_requesting_documents_to_candidates($job_id, $candidates)
    {
        foreach ($candidates as $row_candidate) {
            $this->Recruitment_candidate->send_email_requesting_documents_to_candidate(
                $job_id,
                $row_candidate->ID
            );
        }
    }

    public function update_process_stage($process_id)
    {   
        $this->load->model('Recruitment_candidate');
        
        $stage = $this->Recruitment_candidate->get_last_stage_with_candidates($process_id);

        $this->db->where('id', $process_id);
        
        return $this->db->update('tbl_recruitment_process', array(
            'sts_stage' => $stage
        ));
    }

    public function get_selected_stages($job_id)
    {
        $this->db->select([
            'stages.id AS id',
            'stages.name AS name'
        ]);
        $this->db->from('tbl_recruitment_process_stages process_stages');
        $this->db->join('tbl_recruitment_stages stages', 'process_stages.stage_id=stages.id');
        $this->db->where('process_stages.job_id', $job_id);
        $this->db->where('stages.active', 1);
        $this->db->where('stages.stage_group_id', 1);

        $results = $this->db->get()->result();

        if (count($results) > 0) {
            return $results;
        }

        $this->db->select([
            'stages.id AS id',
            'stages.name AS name'
        ]);
        $this->db->from('tbl_recruitment_stages stages');
        $this->db->where('stages.active', 1);
        $this->db->where('stages.stage_group_id', 1);

        return $this->db->get()->result();
    }

    public function get_country_by_process_id($process_id)
    {
        $company = $this->get_company_by_process_id($process_id);

        $this->db->select([
            'countries.*'
        ]);
        $this->db->from('tbl_countries countries');
        $this->db->join('tbl_companies companies', 'companies.country_id=countries.ID');
		$this->db->where('companies.ID', $company->ID);
        return $this->db->get()->row();
    }

    public function get_company_by_process_id($process_id)
    {
        $process = $this->Recruitment_process->find($process_id);
        $company_id = null;
          
        if ($process->job_ID) {
            $job = $this->Posted_job->find($process->job_ID);
            $company_id = $job->company_ID;
        } else {
            $staff_request = $this->Staff_request->find($process->request_id);
            $company_id = $staff_request->company_ID;            
        }

        $this->db->select([
            'companies.*'
        ]);
        $this->db->from('tbl_companies companies');
        $this->db->where('ID', $company_id);
        return $this->db->get()->row();
    }
}
