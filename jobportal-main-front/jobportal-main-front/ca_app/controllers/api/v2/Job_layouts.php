<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Job_layouts extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $employer_id = $this->session_employer_lib->get_data('user_id');
        $employer = $this->Employer->find($employer_id);

        $this->db->select([
            'jl.id AS jl_id',
            'jl.code AS jl_code',
            'jl.job_title AS jl_name',
            'jl.active AS jl_active',
            'jl.occupational_category_id AS jl_group_occupational_id'
        ]);
        $this->db->from('tbl_job_layouts jl');
        $this->db->where('jl.company_id', $employer->company_ID);
        
        if (isset($params['job_layout_id'])) {
            $this->db->where('jl.id', trim($params['job_layout_id']));
        }

        if (isset($params['job_layout_code'])) {
            $this->db->where('jl.code', trim($params['job_layout_code']));
        }

        if (isset($params['job_layout_active'])) {
            $this->db->where('jl.active', trim($params['job_layout_active']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->jl_id,
                'code' => $row->jl_code,
                'name' => $row->jl_name,
                'active' => (int)$row->jl_active,
                'group_occupational_id' => (int)$row->jl_group_occupational_id
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
