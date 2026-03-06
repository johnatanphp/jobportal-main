<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Internal_areas extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
    }
    
    public function list()
    {
	    $this->db->from('tbl_internal_areas');
        $this->db->where('country_id', $this->input->get('country_id'));
	    $result = $this->db->get()->result();

        echo json_encode([
            'data' => $result
        ]);
    }

    public function add()
    {
        $data = [
            'area_name' => trim($this->input->post('name')),
            'active' => trim($this->input->post('active')),
            'country_id' => trim($this->input->post('country_id'))
        ];

        $this->db->insert('tbl_internal_areas', $data);
        $id = $this->db->insert_id();

        $response = [];

        if ($id) {
            $response = [
                'success' => true,
                'message' => 'Área agregada'
            ];    
            echo json_encode($response);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Error al agregar el registro'
        ]);
    }

    public function edit()
    {
        $data = [
            'area_name' => trim($this->input->post('name')),
            'active' => trim($this->input->post('active'))
        ];

        $this->db->where('ID', $this->input->post('id'));
        $status = $this->db->update('tbl_internal_areas', $data);
    
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Registro actualizado'
            ];    
            echo json_encode($response);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el registro'
        ]);
    }
}
