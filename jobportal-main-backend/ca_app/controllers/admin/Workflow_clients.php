<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Workflow_clients extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        $this->load->model('Workflow_consultant');
        $this->load->model('Business_unit');
    }
    
    public function list()
    {
        $this->db->select([
            'wc.id',
            'wc.code',
            'wc.name',
            'wc.active'
        ]);
	    $this->db->from('tbl_workflow_clients wc');
        $this->db->where('wc.company_id', $this->input->get('company_id'));
	    $result = $this->db->get()->result();

        echo json_encode([
            'data' => $result
        ]);
    }

    public function load_form_add()
    {
        $company_id = $this->input->get('company_id');

        $data = [
            'company_id' =>  $company_id
        ];

        $this->load->view('admin/workflow_clients/partials/form_input_add', $data);
    }

    public function add()
    {
        $this->db->from('tbl_workflow_clients');
        $this->db->where([
            'code' => trim($this->input->post('code')),
            'company_id' => trim($this->input->post('company_id'))
        ]);

        $client_new = $this->db->get()->row();
        $status = false;

        if ($client_new) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al agregar el registro - Cliente ya existe'
            ]);
            return;
        }
        
        $data = [
            'code' => trim($this->input->post('code')),
            'name' => trim($this->input->post('name')),
            'active' => trim($this->input->post('active')),
            'company_id' => trim($this->input->post('company_id'))
        ];

        $status = $this->db->insert('tbl_workflow_clients', $data);
    
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Registro agregado'
            ];    
            echo json_encode($response);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Error al agregar el registro'
        ]);
    }

    public function load_form_edit()
    {
        $id = $this->input->get('id');

        $this->db->from('tbl_workflow_clients');
        $this->db->where('id', $id);
        $client = $this->db->get()->row();

        $data = [
            'client' => $client
        ];

        $this->load->view('admin/workflow_clients/partials/form_input_edit', $data);
    }

    public function edit()
    {
        $id = $this->input->post('id');
        
        $this->db->from('tbl_workflow_clients');
        $this->db->where('id', $id);
        $client = $this->db->get()->row();

        $this->db->from('tbl_workflow_clients');
        $this->db->where([
            'code' => trim($this->input->post('code')),
            'company_id' => $client->company_id,
            'id!=' =>  $id
        ]);

        $client_edit = $this->db->get()->row();
        $status = false;

        if ($client_edit) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el registro - Cliente ya existe'
            ]);
            return;
        }

        $data = [
            'code' => trim($this->input->post('code')),
            'name' => trim($this->input->post('name')),
            'active' => trim($this->input->post('active'))
        ];

        $this->db->where('id', $id);
        $status = $this->db->update('tbl_workflow_clients', $data);
    
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
