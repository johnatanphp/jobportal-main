<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Commercial_premises extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        if (!isset($params['query']) || strlen(trim($params['query'])) < 3) {
            $data = [
                'status' => false,
                'message' => 'Debe ingresar al menos 3 caracteres para la búsqueda.',
                'data' => [],
            ];
            $this->response($data, self::HTTP_OK);
            return;
        }

        $this->db->select([
            'cp.id AS cp_id',
            'cp.name AS cp_name',
            'cp.active AS cp_active',
            'country.ID AS country_id',
            'country.country_name AS country_name'
        ]);
        $this->db->from('tbl_commercial_premises cp');
        $this->db->join('tbl_countries country', 'country.ID=cp.country_id');

        if (isset($params['country_id'])) {
            $this->db->where('cp.country_id', trim($params['country_id']));
        }

        if (isset($params['cp_id'])) {
            $this->db->where('cp.id', trim($params['cp_id']));
        }

        if (isset($params['cp_active'])) {
            $this->db->where('cp.active', trim($params['cp_active']));
        }

        if (isset($params['chain_id'])) {
            $this->db->where_in('cp.chain_id', $params['chain_id']);
        }

        if (isset($params['query']) && strlen(trim($params['query'])) >= 3) {
            $query_terms = explode(' ', trim($params['query']));
    
            foreach ($query_terms as $term) {
                $this->db->like('cp.name', $term);
            }
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {

            $country = [
                'id' => (int)$row->country_id,
                'name' => $row->country_name
            ];

            $response_data[] = [
                'id' => (int)$row->cp_id,
                'name' => $row->cp_name,
                'active' => (int)$row->cp_active,
                'country' => $country
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
