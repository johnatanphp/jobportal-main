<?php
class Recruitment_short_list extends CI_Model
{
    public function exist_short_list_for_job_by_request_id($staff_request_id)
    {
        $this->db->from('tbl_recruitment_process recruitment_process');
        $this->db->join('tbl_post_jobs post_job', 'post_job.ID=recruitment_process.job_ID');
        $this->db->where('post_job.request_ID', $staff_request_id);
        $this->db->where('recruitment_process.sts_stage>=', '5');
     
        return $this->db->count_all_results() > 0;   
    }
    
    public function count_all_candidates_by_stage($job_id, $stage)
    {
        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_recruitment_process process', 'process.id=rs_candidate.process_id');
        
        $this->db->where('process.job_ID', $job_id);
        $this->db->where('rs_candidate.discarded', 0);
        
        if ($stage != -1) {
            $this->db->where('rs_candidate.stage', $stage);
        } else {
            $this->db->where_in('rs_candidate.stage', [5, 6, 7]);
        }
        
        return $this->db->count_all_results();
    }
    
    public function search_short_list_job_by_job_id($process_id, $filters = array())
    {
        $this->db->select(array(
            'job_seeker.*',
            'rs_candidate.stage',
            'rs_candidate.contracted'
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->where('rs_candidate.process_id', $process_id);
        
        //Mostrar etapas de Terna y Selección personal
        $this->db->where('rs_candidate.stage>=', 5);
        $this->db->where('rs_candidate.discarded', 0);
        $this->db->where('rs_candidate.stage', $filters['stage']);
        
        $this->db->order_by('rs_candidate.stage', 'ASC');
        
        $this->db->order_by('rs_candidate.creation_date', 'DESC');

        $this->db->group_by('rs_candidate.seeker_ID');

        return $this->db->get()->result();
    }

    public function select_candidate($job_seeker_id, $process_id)
    {
        $this->db->trans_start();

        //Mover candidato a la siguiente etapa
        $this->Recruitment_candidate->move_candidate(
            $process_id, 
            $job_seeker_id, 
            6
        );

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function unselect_candidate($job_seeker_id, $process_id)
    {
        $this->db->trans_start();

        //Mover candidato a la etapa anterior etapa
        $this->Recruitment_candidate->move_candidate(
            $process_id, 
            $job_seeker_id, 
            5
        );

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function register_candidate_short_list($job_id, $job_seeker_id)
    {
        $data_register = array(
            'job_ID' => $job_id,
            'seeker_ID' => $job_seeker_id
        );

        $this->db->insert('tbl_recruitment_short_list_candidates', $data_register);
    }

    public function get_scheduled_interview($job_id, $candidate_id)
    {
        $this->db->from('tbl_short_list_candidate_interview');
        $this->db->where('job_ID', $job_id);
        $this->db->where('seeker_ID', $candidate_id);

        return $this->db->get()->row();
    }

    public function schedule_interview($interview)
    {
        $job_id = $interview['job_id'];
        $candidate_id = $interview['candidate_id'];

        $this->db->where('job_ID', $job_id);
        $this->db->where('seeker_ID', $candidate_id);
        $this->db->delete('tbl_short_list_candidate_interview');
        $hour = $interview['hour'] . ' ' . $interview['code_hour'];

        $data = array(
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'date' => format_date($interview['date']),
            'hour' => format_date($hour, 'H:i'),
            'place' => $interview['place'],
            'more_details' => $interview['more_details'],
            'responsible_recruiter_ID' =>  $this->session->userdata('user_id')
        );

        return $this->db->insert('tbl_short_list_candidate_interview', $data);
    }

    private function check_candidate_in_short_list($job_id, $candidate_id)
    {
        $candidate_short_list = $this->db->get_where('tbl_recruitment_short_list_candidates', array(
            'seeker_ID' => $candidate_id,
            'job_ID' => $job_id
        ))->row();

        if ($candidate_short_list) {
            return true;
        }

        $data_insert = array(
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'is_selected' => 0,
            'seen' => 0
        );   

        return $this->db->insert('tbl_recruitment_short_list_candidates', $data_insert);
    }

    public function mark_as_seen_cv_candidate_short_list($job_id, $candidate_id)
    {
        $this->check_candidate_in_short_list($job_id, $candidate_id);

        $this->db->where('job_ID', $job_id);
        $this->db->where('seeker_ID', $candidate_id);

        $this->db->update('tbl_recruitment_short_list_candidates', array('seen' => 1));
    }
}
