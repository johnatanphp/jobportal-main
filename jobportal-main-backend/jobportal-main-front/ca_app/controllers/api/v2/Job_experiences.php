<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Job_experiences extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'je.ID AS je_id',
            'je.name AS je_name',
            'je.active AS je_active'
        ]);
        $this->db->from('tbl_job_experiences je');
    
        if (isset($params['exp_id'])) {
            $this->db->where('je.id', trim($params['exp_id']));
        }

        if (isset($params['exp_active'])) {
            $this->db->where('je.active', trim($params['exp_active']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->je_id,
                'name' => $row->je_name,
                'active' => (int)$row->je_active
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
