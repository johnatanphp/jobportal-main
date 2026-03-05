<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Clinicas_correos extends REST_Controller
{
    public function index_get()
    {
        $this->db->select([
            'code AS medical_center_code',
            'email'
        ]);

        $this->db->from('tbl_medical_center_emails');
        $result = $this->db->get()->result();

        $data = [];
        $data_row = []; 
        
        foreach ($result as $row) {

            $data_row['proveedor_codigo'] = $row->medical_center_code;
            $data_row['email'] = $row->email;

            $data[] = $data_row;
        }

        $this->response([
            'status' => true,
            'data' => ['correos' => $data]
        ], 200);
    }
}
