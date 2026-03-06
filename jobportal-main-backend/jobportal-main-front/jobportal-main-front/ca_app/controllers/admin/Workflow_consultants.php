<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Workflow_consultants extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        $this->load->model('Workflow_consultant');
    }
    
    public function list()
    {
        $result = $this->Workflow_consultant->all([
            'company_id' => $this->input->get('company_id')
        ]);

        echo json_encode([
            'data' => $result
        ]);
    }

    public function add()
    {
        $cia = $this->db->get_where('tbl_workflow_consultants', [
            'code' => trim($this->input->post('no_cia')),
            'company_id' => trim($this->input->post('company_id'))
        ])->row();

        if ($cia) {
            echo json_encode([
                'success' => false,
                'message' =>  'Error al agregar el registro - Consultora ya existe'
            ]);
            return;
        }

        $data = [
            'code' => trim($this->input->post('no_cia')),
            'name' => trim($this->input->post('name')),
            'type_service' => trim($this->input->post('type_service')),
            'active' => trim($this->input->post('active')),
            'company_id' => trim($this->input->post('company_id'))
        ];

        $status = $this->db->insert('tbl_workflow_consultants', $data);
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Consultora agregada'
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
        $id = $this->input->post('id');

        $this->db->from('tbl_workflow_consultants');
        $this->db->where('id', $id);
        $cia_row = $this->db->get()->row();

        $cia = $this->db->get_where('tbl_workflow_consultants', [
            'code' => trim($this->input->post('no_cia')),
            'company_id' => $cia_row->company_id,
            'id!=' =>  $id
        ])->row();

        if ($cia) {
            echo json_encode([
                'success' => false,
                'message' =>  'Error al actualizar el registro - Consultora ya existe'
            ]);
            return;
        }

        $data = [
            'code' => trim($this->input->post('no_cia')),
            'name' => trim($this->input->post('name')),
            'type_service' => trim($this->input->post('type_service')),
            'active' => trim($this->input->post('active'))
        ];

        $this->db->where('id', $this->input->post('id'));
        $status = $this->db->update('tbl_workflow_consultants', $data);
    
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Consultora actualizada'
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
