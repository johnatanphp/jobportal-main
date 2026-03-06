<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_stages extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();

        //Load models
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_stage');
        $this->load->model('Recruitment_candidate');
    }

    public function index()
    {
        $params = $this->input->get();
        $job_id = $params['job_id'];
        $data['job_id'] = $job_id;
        $data['stages'] = $this->Recruitment_stage->all(['active' => 1, 'stage_group_id' => 1]);
        $data['selected_stages'] = $this->Recruitment_process->get_selected_stages($job_id);
        $this->load->view('employer/recruitment/process_stages/common/form_process_stages', $data);
    }

    public function save()
    {
        $params = $this->input->post();
        $job_id = trim($params['job_id']);
        $stages = $params['stages'] ?? [];
        
        $this->db->where('job_id', $job_id);
        $this->db->delete('tbl_recruitment_process_stages');

        $stages = array_merge($stages, [6, 7]);

        foreach ($stages as $stage_id) {
            $this->db->insert('tbl_recruitment_process_stages', [
                'job_id' => $job_id,
                'stage_id' => $stage_id
            ]);
        }

        $this->Recruitment_process->update_process_stage($job_id);

        echo json_encode([
            'status' =>  true,
            'message' => 'Se asignaron las etapas al proceso'
        ]);
    }
}
