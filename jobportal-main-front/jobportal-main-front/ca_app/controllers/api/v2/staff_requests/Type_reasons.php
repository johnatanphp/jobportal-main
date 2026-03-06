<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Type_reasons extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'type_reasons.id AS type_reasons_id',
            'type_reasons.name AS type_reasons_name',
            'type_reasons.active AS type_reasons_active'
        ]);
        $this->db->from('tbl_staff_request_type_reasons type_reasons');

        if (isset($params['reason_id'])) {
            $this->db->where('id', trim($params['reason_id']));
        }

        if (isset($params['reason_active'])) {
            $this->db->where('active', trim($params['reason_active']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => $row->type_reasons_id,
                'name' => $row->type_reasons_name,
                'active' => (int)$row->type_reasons_active
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
