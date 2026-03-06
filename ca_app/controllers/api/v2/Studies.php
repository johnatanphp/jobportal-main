<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Studies extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'st.ID AS st_id',
            'st.text AS st_name',
            'st.active AS st_active'
        ]);
        $this->db->from('tbl_qualifications st');
        $this->db->where('st.val', 'Estudios_2');

        if (isset($params['study_id'])) {
            $this->db->where('st.ID', trim($params['study_id']));
        }

        if (isset($params['study_active'])) {
            $this->db->where('st.active', trim($params['study_active']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (int)$row->st_id,
                'name' => $row->st_name,
                'active' => (int)$row->st_active
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
