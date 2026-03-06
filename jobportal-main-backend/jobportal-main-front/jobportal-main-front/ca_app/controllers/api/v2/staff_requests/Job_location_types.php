<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Job_location_types extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'job_location_types.id AS jlt_id',
            'job_location_types.name AS jlt_name',
            'job_location_types.active AS jlt_active'
        ]);
        $this->db->from('tbl_staff_request_job_location_types job_location_types');

        if (isset($params['job_location_id'])) {
            $this->db->where('id', trim($params['job_location_id']));
        }

        if (isset($params['job_location_active'])) {
            $this->db->where('active', trim($params['job_location_active']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->jlt_id,
                'name' => $row->jlt_name,
                'active' => (int)$row->jlt_active
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
