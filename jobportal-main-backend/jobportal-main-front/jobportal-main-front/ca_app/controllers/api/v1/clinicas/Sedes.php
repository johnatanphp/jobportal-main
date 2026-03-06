<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Sedes extends REST_Controller
{
    public function index_get()
    {
        $this->db->select([
            'medical_center_code',
            'location',
            'direction',
            'active'
        ]);

        $this->db->from('tbl_medical_center_locations');
        $result = $this->db->get()->result();

        $data = [];
        $data_row = []; 
        
        foreach ($result as $row) {

            $data_row['proveedor_codigo'] = $row->medical_center_code;
            $data_row['nombre'] = $row->location;
            $data_row['direccion'] = $row->direction;
            $data_row['activo'] = $row->active;

            $data[] = $data_row;
        }

        $this->response([
            'status' => true,
            'data' => ['sedes' => $data]
        ], 200);
    }
}
