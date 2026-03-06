<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Recruitment_sources extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'recruitment_sources.id AS source_id',
            'recruitment_sources.name AS source_name'
        ]);
        $this->db->from('tbl_recruitment_sources recruitment_sources');
        $this->db->where('recruitment_sources.active', 1);
        
        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->source_id,
                'name' => $row->source_name,
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
