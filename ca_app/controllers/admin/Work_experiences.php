<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Work_experiences extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
    }
    
    public function list()
    {
	    $this->db->from('tbl_work_experiences');
        $this->db->where('country_id', $this->input->get('country_id'));
	    $result = $this->db->get()->result();

        echo json_encode([
            'data' => $result
        ]);
    }

    public function add()
    {
        $data = [
            'name' => trim($this->input->post('name')),
            'valorization_score' => trim($this->input->post('valorization_score')),
            'valorization_grade' => trim($this->input->post('valorization_grade')),
            'active' => trim($this->input->post('active')),
            'country_id' => trim($this->input->post('country_id'))
        ];

        $this->db->insert('tbl_work_experiences', $data);
        $id = $this->db->insert_id();

        $response = [];

        if ($id) {

            $this->db->where('id', $id);
            $this->db->update('tbl_work_experiences', [
                'code' => $id
            ]);

            $response = [
                'success' => true,
                'message' => 'Experiencia laboral agregada'
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
            'name' => trim($this->input->post('name')),
            'valorization_score' => trim($this->input->post('valorization_score')),
            'valorization_grade' => trim($this->input->post('valorization_grade')),
            'active' => trim($this->input->post('active'))
        ];

        $this->db->where('id', $this->input->post('id'));
        $status = $this->db->update('tbl_work_experiences', $data);
    
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Actualización realizada'
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
