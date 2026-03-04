<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Chains extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'chain.id AS chain_id',
            'chain.name AS chain_name',
            'chain.active AS chain_active',
            'country.ID AS country_id',
            'country.country_name AS country_name'
        ]);
        $this->db->from('tbl_chains chain');
        $this->db->join('tbl_countries country', 'country.ID=chain.country_id');

        if (isset($params['country_id'])) {
            $this->db->where('chain.country_id', trim($params['country_id']));
        }

        if (isset($params['chain_id'])) {
            $this->db->where('chain.id', trim($params['chain_id']));
        }

        if (isset($params['chain_active'])) {
            $this->db->where('chain.active', trim($params['chain_active']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {

            $country = [
                'id' => (int)$row->country_id,
                'name' => $row->country_name
            ];

            $response_data[] = [
                'id' => (int)$row->chain_id,
                'name' => $row->chain_name,
                'active' => (int)$row->chain_active,
                'country' => $country
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
