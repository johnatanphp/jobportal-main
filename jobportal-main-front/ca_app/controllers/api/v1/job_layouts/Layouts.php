<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Layouts extends REST_Controller
{
    public function list_get()
    {
        $input = $this->input->get();

        if (!isset($input['code_integration'])) {
            $this->response([
                'status' => false,
                'message' => 'El parámetro - code_integration es requerido'
            ], 200);
            return;
        }        

        $this->db->query("SET SESSION group_concat_max_len = 1024 * 1024");

        $this->db->select("
            tjl.id,
            tjl.code_integration,
            tjl.code,
            tjl.job_title AS job_title,
            tq.text AS education_desirable,
            tjl.education_req_detail AS education_desirable_detail,
            tqm.text AS education_minimum,
            tjl.education_min_detail AS education_minimum_detail,
            tjl.education AS formation,
            twe.name AS experience,
            tjl.experience_detail AS experience_detail,
            GROUP_CONCAT(DISTINCT tjcs.skill_name ORDER BY tjcs.skill_name SEPARATOR '-||-') AS skills,
            GROUP_CONCAT(DISTINCT tjr.responsibility ORDER BY tjr.responsibility SEPARATOR '-||-') AS responsibilities
        ", false);

        $this->db->from('tbl_job_layouts tjl');
        $this->db->join('tbl_qualifications tq', 'tq.id = tjl.study_grade_req', 'left');
        $this->db->join('tbl_qualifications tqm', 'tqm.id = tjl.study_grade_min', 'left');
        $this->db->join('tbl_work_experiences twe', 'twe.code = tjl.experience', 'left');
        $this->db->join('tbl_job_charge_skills tjcs', 'tjcs.job_charge_id = tjl.job_charge_id', 'left');
        $this->db->join('tbl_job_layout_responsibilities tjr', 'tjr.job_layout_id = tjl.id', 'left');

        $this->db->where('tjl.code_integration', $input['code_integration']);

        $this->db->group_by('tjl.id');

        $results = $this->db->get()->result();

        $response_data = [];

        $skills_data = [];
        $responsibilities = [];

        foreach ($results as $job_layout) {
            $response_data[] = [
                'id' => $job_layout->id,
                'job_title' => $job_layout->job_title,
                'code' => $job_layout->code,
                'code_integration' => $job_layout->code_integration,
                'education_desirable' => $job_layout->education_desirable,
                'education_desirable_detail' => $job_layout->education_desirable_detail,
                'education_minimum' => $job_layout->education_minimum,
                'education_minimum_detail' => $job_layout->education_minimum_detail,
                'formation' => $job_layout->formation,
                'experience' => $job_layout->experience,
                'experience_detail' => $job_layout->experience_detail,
                'skills' => array_filter(explode('-||-', (string)$job_layout->skills)),
                'responsibilities' => array_filter(explode('-||-', (string)$job_layout->responsibilities))
            ];
        }

        $this->response([
            'status' => true,
            'message' => 'OK',
            'data' => $response_data
        ], 200);
    }
}
