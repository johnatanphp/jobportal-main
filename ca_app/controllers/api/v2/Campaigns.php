<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Campaigns extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'campaign.id AS campaign_id',
            'campaign.name AS campaign_name',
            'campaign.active AS campaign_active',
            'campaign.client_id AS campaign_client_id',
        ]);
        $this->db->from('tbl_campaigns campaign');

        if (isset($params['campaign_id'])) {
            $this->db->where('campaign.id', trim($params['campaign_id']));
        }

        if (isset($params['campaign_active'])) {
            $this->db->where('campaign.active', trim($params['campaign_active']));
        }

        if (isset($params['client_id'])) {
            $this->db->where('campaign.client_id', trim($params['client_id']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->campaign_id,
                'name' => $row->campaign_name,
                'active' => (int)$row->campaign_active
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}