<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Countries extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'ID AS country_id',
            'country_name AS country_name',
            'phone_code'
        ]);
        $this->db->from('tbl_countries c');
        $this->db->where('phone_code!=', '');
        if (isset($params['country_id'])) {
            $this->db->where('c.ID', trim($params['country_id']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->country_id,
                'name' => $row->country_name,
                'phone_code' => $row->phone_code
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
