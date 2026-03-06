<?php
class Entry_job_seeker extends CI_Model
{
	public function __construct(){}

	public function search_all(
		$job_id, 
		$per_page, 
		$page
	) {
        $this->db->from('tbl_seeker_entries entry_job_seekers');
        $this->db->join('tbl_post_jobs jobs', 'entry_job_seekers.for_job_ID=jobs.ID');
        $this->db->where('for_job_ID', $job_id);

		if ($per_page > 0) {
			$this->db->limit($per_page, $page);
		}

        return $this->db->get()->result();
	}

	public function count_all($job_id)
	{
        $this->db->from('tbl_seeker_entries entry_job_seekers');
        $this->db->where('for_job_ID', $job_id);
        
        return $this->db->count_all_results();
	}

	public function get_job_seeker_by_email($email)
	{
        $this->db->from('tbl_seeker_entries');
        $this->db->where('email', $email);
       	return $this->db->get()->row();
	}

	public function get_job_seeker_by_key($key)
	{
        $this->db->from('tbl_seeker_entries');
        $this->db->where('entry_key', $key);
       	return $this->db->get()->row();
	}

	public function entry_job_seeker($data_input)
	{
		$key = $data_input['entry_key'];

		$entry_job_seeker = $this->get_job_seeker_by_key($key);

		if (!$entry_job_seeker || $entry_job_seeker->activated) {
			return false;
		}
		
		$email = $entry_job_seeker->email;

		$entry_job_seeker = $this->get_job_seeker_by_email($email);

		if (!$entry_job_seeker) {
			return false;
		}

		$password = $data_input['new_password'];

		$data_job_seeker = [
			'email' => $email,
			'first_name' => $data_input['first_name'],
			'paternal_last_name' => $data_input['paternal_last_name'],
			'maternal_last_name' => $data_input['maternal_last_name'],
			'last_name' => $data_input['paternal_last_name'] . ' ' . $data_input['maternal_last_name'],
			'document_type' => '1',
			'document_number' => $entry_job_seeker->document_number,
			'mobile' => $entry_job_seeker->mobile,
			//'gender' => $entry_job_seeker->gender,
			'password' => $password 
		];
		
		$this->db->trans_start();

		$seeker_id = $this->Job_seeker->add_job_seekers($data_job_seeker);

		$this->Jobseeker_additional_info->add([
			'seeker_ID' => $seeker_id
		]);

		$job_array = [
			'seeker_ID' => $seeker_id,
			'job_ID' =>  $entry_job_seeker->for_job_ID,
			//'employer_ID' => $row->employer_ID,
			'dated' => date("Y-m-d H:i:s")
		];

		$this->Applied_jobs->add_applied_job($job_array);

		$this->db->where('email', $email);
		$this->db->update('tbl_seeker_entries', [
			'activated' => 1
		]);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}
}
