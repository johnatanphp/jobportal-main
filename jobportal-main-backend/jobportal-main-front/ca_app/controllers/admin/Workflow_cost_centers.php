<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Workflow_cost_centers extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        $this->load->model('Workflow_consultant');
        $this->load->model('Workflow_client');
        $this->load->model('Business_unit');
        $this->load->model('Workflow_cost_center');
    }
    
    public function list()
    {
        $this->db->select([
            'wcc.id',
            'wcc.code',
            'wcc.active',
            'wcc.company_id',
            'wc_cia.code AS cia_code',
            'wc_cia.name AS cia_name',
            'wc.name AS client_name',
            'bu.business_unit_code',
            'bu.business_unit_name',
            'wcc.has_penalty',
        ]);
	    $this->db->from('tbl_workflow_cost_centers wcc');
        $this->db->join('tbl_business_units bu', 'bu.business_unit_code=wcc.business_unit_code AND bu.company_id=wcc.company_id');
        $this->db->join('tbl_workflow_clients wc', 'wc.code=wcc.client_code AND wc.company_id=wcc.company_id');
        $this->db->join('tbl_workflow_consultants wc_cia', 'wc_cia.code=wcc.cia_code AND wc_cia.company_id=wcc.company_id');
        $this->db->where('wc_cia.active', 1);
        $this->db->where('bu.active', 1);
        $this->db->where('wc.active', 1);
        $this->db->where('wcc.company_id', $this->input->get('company_id'));

        $this->db->group_by(['wcc.cia_code', 'wcc.business_unit_code','wcc.client_code', 'wcc.code', 'wcc.company_id']);
        
	    $result = $this->db->get()->result();

        echo json_encode([
            'data' => $result
        ]);
    }

    public function load_form_add()
    {
        $company_id = $this->input->get('company_id');

        $consultants = $this->Workflow_consultant->all([
            'company_id' => $company_id,
            'active' => 1
        ]);

        $clients = $this->Workflow_client->all([
            'company_id' => $company_id,
            'active' => 1
        ]);

        $business_units = $this->Business_unit->all([
            'company_id' => $company_id,
            'active' => 1
        ]);

        $data = [
            'company_id' =>  $company_id,
            'consultants' => $consultants,
            'clients' => $clients,
            'business_units' => $business_units
        ];

        $this->load->view('admin/workflow_cost_centers/partials/form_input_add', $data);
    }

    public function add()
    {
        $this->db->from('tbl_workflow_cost_centers');
        $this->db->where([
            'cia_code' => trim($this->input->post('cia_code')),
            'business_unit_code' => trim($this->input->post('business_unit_code')),
            'client_code' => trim($this->input->post('client_code')),
            'code' => trim($this->input->post('code')),
            'company_id' => trim($this->input->post('company_id'))
        ]);

        $client_new = $this->db->get()->row();
        $status = false;

        if ($client_new) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al agregar el registro - Centro de costo ya existe'
            ]);
            return;
        }
        
        $data = [
            'cia_code' => trim($this->input->post('cia_code')),
            'business_unit_code' => trim($this->input->post('business_unit_code')),
            'client_code' => trim($this->input->post('client_code')),
            'code' => trim($this->input->post('code')),
            'active' => trim($this->input->post('active')),
            'has_penalty' => trim($this->input->post('has_penalty')),
            'company_id' => trim($this->input->post('company_id'))
        ];

        $status = $this->db->insert('tbl_workflow_cost_centers', $data);
    
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

        $this->db->from('tbl_workflow_cost_centers');
        $this->db->where('id', $id);
        $center = $this->db->get()->row();

        $consultants = $this->Workflow_consultant->all([
            'company_id' => $center->company_id,
            'active' => 1
        ]);

        $business_units = $this->Business_unit->all([
            'company_id' => $center->company_id,
            'active' => 1
        ]);

        $clients = $this->Workflow_client->all([
            'company_id' => $center->company_id,
            'active' => 1
        ]);

        $data = [
            'consultants' => $consultants,
            'business_units' => $business_units,
            'clients' => $clients,
            'center' => $center
        ];

        $this->load->view('admin/workflow_cost_centers/partials/form_input_edit', $data);
    }

    public function edit()
    {
        $id = $this->input->post('id');
        
        $this->db->from('tbl_workflow_cost_centers');
        $this->db->where('id', $id);
        $client = $this->db->get()->row();

        $this->db->from('tbl_workflow_cost_centers');
        $this->db->where([
            'cia_code' => trim($this->input->post('cia_code')),
            'business_unit_code' => trim($this->input->post('business_unit_code')),
            'code' => trim($this->input->post('code')),
            'client_code' => trim($this->input->post('client_code')),
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
            'cia_code' => trim($this->input->post('cia_code')),
            'business_unit_code' => trim($this->input->post('business_unit_code')),
            'client_code' => trim($this->input->post('client_code')),
            'code' => trim($this->input->post('code')),
            'has_penalty' => trim($this->input->post('has_penalty')),
            'active' => trim($this->input->post('active'))
        ];

        $this->db->where('id', $id);
        $status = $this->db->update('tbl_workflow_cost_centers', $data);
    
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

    public function get_clients()
    {
        $this->db->select([
            'wc.id',
            'wc.code',
            'wc.name'       
        ]);

	    $this->db->from('tbl_workflow_clients wc');
        $this->db->where('wc.active', 1);
        $this->db->where('wc.company_id', $this->input->post('company_id'));
        $this->db->where('wc.cia_code', $this->input->post('cia_code'));
        $this->db->where('wc.business_unit_code', $this->input->post('unit_code'));

	    $result = $this->db->get()->result();

        echo json_encode([
            'success' => true,
            'clients' => $result
        ]);
    }
}
