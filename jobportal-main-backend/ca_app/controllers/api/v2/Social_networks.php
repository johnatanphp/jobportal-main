<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Social_networks extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'sn.ID AS sn_id',
            'sn.name AS sn_name'
        ]);
        $this->db->from('tbl_social_networks sn');
        $this->db->where('sn.active', 1);
    
        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->sn_id,
                'name' => $row->sn_name
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}