<?php
class Candidate_evaluation extends CI_Model {
	
    public function get_scheduled_evaluation($job_id, $candidate_id)
    {                    
    	$this->db->from('tbl_recruitment_candidate_evaluations');
    	$this->db->where('job_ID', $job_id);
    	$this->db->where('seeker_ID', $candidate_id);

    	return $this->db->get()->row();
    }

    public function schedule_evaluation($evaluation)
    {
        $job_id = $evaluation['job_id'];
        $candidate_id = $evaluation['candidate_id'];

        $this->db->where('job_ID', $job_id);
        $this->db->where('seeker_ID', $candidate_id);
        $this->db->delete('tbl_recruitment_candidate_evaluations');
        $hour = $evaluation['hour'] . ' ' . $evaluation['code_hour'];

        $data = array(
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'date' => format_date($evaluation['date']),
            'hour' => format_date($hour, 'H:i'),
            'place' => $evaluation['place'],
            'more_details' => $evaluation['more_details'],
            'responsible_employer_ID' => $evaluation['responsible_employer_id']
        );

        return $this->db->insert('tbl_recruitment_candidate_evaluations', $data);
    }
}
