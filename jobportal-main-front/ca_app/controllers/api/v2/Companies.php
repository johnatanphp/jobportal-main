<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Companies extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'ID AS company_id',
            'company_ruc AS company_ruc',
            'company_name AS company_name',
            'sts AS company_sts'
        ]);
        $this->db->from('tbl_companies c');
        $this->db->where('system_internal', 1);
        $this->db->where('sts', 'active');

        if (isset($params['company_id'])) {
            $this->db->where('c.ID', trim($params['company_id']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->company_id,
                'name' => $row->company_name,
                'ruc' => $row->company_ruc,
                'status' => $row->company_sts
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}