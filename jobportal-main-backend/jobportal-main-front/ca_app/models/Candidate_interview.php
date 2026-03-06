<?php
class Candidate_interview extends CI_Model {
	
    public function get_scheduled_interview($job_id, $candidate_id)
    {
    	$this->db->from('tbl_recruitment_candidate_interview');
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
        $this->db->delete('tbl_recruitment_candidate_interview');

        $hour = $interview['hour'] . ' ' . $interview['code_hour'];

        $data = array(
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'date' => format_date($interview['date']),
            'hour' => format_date($hour, 'H:i'),
            'place' => $interview['place'],
            'additional_comment' => $interview['additional_comment'],
            'responsible_employer_ID' => $interview['responsible_employer_id']
        );

        return $this->db->insert('tbl_recruitment_candidate_interview', $data);
    }

    public function save_comments($job_id, $candidate_id, $comments)
    {
        $data = array(
            'interview_comments' => $comments,
        );

        $this->db->where('job_ID', $job_id);
        $this->db->where('seeker_ID', $candidate_id);

        return $this->db->update(
            'tbl_recruitment_candidate_interview', 
            $data
        );
    }

    public function get_interview_comments($job_id, $candidate_id)
    {
        return $this->get_scheduled_interview($job_id, $candidate_id);
    }
}
