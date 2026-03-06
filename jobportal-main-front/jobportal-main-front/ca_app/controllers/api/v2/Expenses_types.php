<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Expenses_types extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'et.id AS id',
            'et.name AS name'
        ]);
        $this->db->from('tbl_expense_types et');
        $this->db->where_in('et.id', [
            'EODT' , 'EOF', 'EONF'
        ]);
        $this->db->where('et.active', '1');

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'id' => (string)$row->id,
                'name' => $row->name
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }
}
