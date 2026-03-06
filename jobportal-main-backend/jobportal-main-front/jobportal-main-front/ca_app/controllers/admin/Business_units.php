<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Business_units extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
    }
    
    public function list()
    {
	    $this->db->from('tbl_business_units');
        $this->db->where('company_id', $this->input->get('company_id'));
	    $result = $this->db->get()->result();

        echo json_encode([
            'data' => $result
        ]);
    }

    public function add()
    {
        $data = [
            'business_unit_code' => trim($this->input->post('code')),
            'business_unit_name' => trim($this->input->post('name')),
            'acronym_code' => trim($this->input->post('acronym_code')),
            'active' => trim($this->input->post('active')),
            'company_id' => trim($this->input->post('company_id'))
        ];

        $status = $this->db->insert('tbl_business_units', $data);
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Unidad de negocio agregada'
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
            'business_unit_code' => trim($this->input->post('code')),
            'business_unit_name' => trim($this->input->post('name')),
            'acronym_code' => trim($this->input->post('acronym_code')),
            'active' => trim($this->input->post('active'))
        ];

        $this->db->where('ID', $this->input->post('id'));
        $status = $this->db->update('tbl_business_units', $data);
    
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Unidad de negocio actualizada'
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
