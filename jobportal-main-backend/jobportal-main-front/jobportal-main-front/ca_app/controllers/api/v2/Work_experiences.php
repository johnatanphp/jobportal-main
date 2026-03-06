<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Work_experiences extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'we.ID AS we_id',
            'we.name AS we_name',
            'we.active AS we_active'
        ]);
        $this->db->from('tbl_work_experiences we');
    
        if (isset($params['country_id'])) {
            $this->db->where('we.country_id', trim($params['country_id']));
        }

        if (isset($params['we_id'])) {
            $this->db->where('we.ID', trim($params['we_id']));
        }

        if (isset($params['we_active'])) {
            $this->db->where('we.active', trim($params['we_active']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->we_id,
                'name' => $row->we_name,
                'active' => (int)$row->we_active
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}