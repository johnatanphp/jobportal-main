<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_blocks extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }

    public function list()
    {
        $job_id = $this->input->post('job_id');
		$stage = $this->input->post('stage_id');

		$this->db->select([
			'creation_date',
			'update_date',
			'COUNT(seeker_ID) AS total_candidates'
		]);

		$this->db->from('tbl_recruitment_candidates');
		$this->db->where('job_ID', $job_id);
		$this->db->where('stage', $stage);
	
		$this->db->group_by(['creation_date', 'update_date']);
		$results = $this->db->get()->result();

		$data['blocks'] = $results; 
        $data['job_id'] = $job_id;

        $this->load->view(
			'employer/recruitment/joseeker_blocks/common/modal_change_blocks_candidates', 
			$data
		);
    }

    public function move()
    {
        $job_id = $this->input->post('job_id');
        $seekers_ids = $this->input->post('candidate_ids');

        $creation_date = $this->input->post('creation_date');
        $update_date = $this->input->post('update_date');

        $this->db->where('job_ID', $job_id);
        $this->db->where_in('seeker_ID', $seekers_ids);
        $this->db->update('tbl_recruitment_candidates', [
            'creation_date' => $creation_date,
            'update_date' => !empty($update_date) ? $update_date : null
        ]);

        echo json_encode([
            'success' => true,
            'message' => '¡Se ha movido los postulante de bloque!'
        ]);
    }
}