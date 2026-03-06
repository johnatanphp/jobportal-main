<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Skills extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            's.ID AS s_id',
            's.skill_name AS s_name',
            's.active AS s_active',
            's.occupational_category_id AS s_group_occupational_id'
        ]);
        $this->db->from('tbl_skills s');

        if (isset($params['skill_active'])) {
            $this->db->where('s.active', trim($params['skill_active']));
        }

        if (isset($params['skill_favorite'])) {
            $this->db->where('s.skill_favorite', $params['skill_favorite']);
        }

        if (isset($params['group_occupational_id'])) {
            $this->db->where('s.occupational_category_id', $params['group_occupational_id']);
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->s_id,
                'name' => $row->s_name,
                'active' => (int)$row->s_active,
                'group_occupational_id' => (int)$row->s_group_occupational_id
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
