<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contract_documents extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
    }

    public function list()
    {
        $user = $this->Employer->find($this->session->userdata('user_id'));

        $this->db->select([
            'doc_type.id',
            'doc_type.name',
            'doc_type.active',
            'option_type.name AS option_type_name',
            'doc_type.option_type_id',
            'doc_type.allowed_files',
            'doc_type.max_size',
            'doc_type.group_id',
            'IFNULL(document_groups.name, "Ninguno") AS group_name'
        ]);
        $this->db->from('tbl_recruitment_contract_document_types doc_type');
        $this->db->join('tbl_recruitment_document_option_types option_type', 'doc_type.option_type_id=option_type.id');
        $this->db->join('tbl_contract_document_groups document_groups', 'document_groups.id=doc_type.group_id', 'left');
        $this->db->where('doc_type.company_id', $user->company_ID);
        $result = $this->db->get()->result();        

        echo json_encode([
            'data' => $result
        ]);
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'Nombre', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('option_type_id', 'Tipo documento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('group_id', 'Grupo', 'trim|strip_all_tags');
        $this->form_validation->set_rules('active', 'Estado', 'trim|required|strip_all_tags');
        
		if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];    
            echo json_encode($response);
            return;
		}   

        $user = $this->Employer->find($this->session->userdata('user_id'));
        $company_id = $user->company_ID;

        $name = $this->input->post('name');
        $active = $this->input->post('active');

        $document_row = $this->db->get_where('tbl_recruitment_contract_document_types', [
            'company_id' => $company_id,
            'name' => $name
        ])
        ->row();

        if ($document_row) {
            $response = [
                'success' => false,
                'message' => 'Ya existe un documento con el mismo nombre'
            ];    
            echo json_encode($response);
            return;
        }

        $options = [];
        $option_type_id = $this->input->post('option_type_id');
        $max_size = null;
        $allowed_files = null;
        $group_id = $this->input->post('group_id');

        if ($option_type_id == 1) {
            $allowed_files = join(',',  $this->input->post('attach_allowed_file'));
            $max_size = $this->input->post('attach_max_size');
        }

        $data = [
            'name' => $name,
            'option_type_id' => $option_type_id,
            'max_size' => $max_size,
            'allowed_files' => $allowed_files,
            'active' => $active,
            'group_id' => $group_id ? $group_id : null,
            'company_id' => $company_id
        ];

        $this->db->insert('tbl_recruitment_contract_document_types', $data);
        $id = $this->db->insert_id();

        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al agregar el registro'
            ]);
            return;
        }
        
        $response = [
            'success' => true,
            'message' => 'Documento agregado'
        ];    

        echo json_encode($response);
    }

    public function edit()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('name', 'Nombre', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('option_type_id', 'Tipo documento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('group_id', 'Grupo', 'trim|strip_all_tags');
        $this->form_validation->set_rules('active', 'Estado', 'trim|required|strip_all_tags');
        
		if ($this->form_validation->run() === FALSE) {
            $response = [
                'success' => false,
                'message' => validation_errors()
            ];    
            echo json_encode($response);
            return;
		}   

        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $active = $this->input->post('active');
        $group_id = $this->input->post('group_id');
        $document_row = $this->db->get_where('tbl_recruitment_contract_document_types', [
            'id' => $id
        ])
        ->row();

        if (!$document_row) {
            $response = [
                'success' => false,
                'message' => 'Documento ID es invalido'
            ];    
            echo json_encode($response);
            return;
        }

        $document_update = $this->db->get_where('tbl_recruitment_contract_document_types', [
            'id!=' => $id,
            'company_id' => $document_row->company_id,
            'name' => $name
        ])
        ->row();

        if ($document_update) {
            $response = [
                'success' => false,
                'message' => 'Ya existe un documento con el mismo nombre'
            ];    
            echo json_encode($response);
            return;
        }

        $options = [];
        $option_type_id = $this->input->post('option_type_id');
        $max_size = null;
        $allowed_files = null;

        if ($option_type_id == 1) {
            $allowed_files = join(',',  $this->input->post('attach_allowed_file'));
            $max_size = $this->input->post('attach_max_size');
        }

        $data = [
            'name' => $name,
            'option_type_id' => $option_type_id,
            'max_size' => $max_size,
            'allowed_files' => $allowed_files,
            'group_id' => $group_id ? $group_id : null,
            'active' => $active
        ];

        $this->db->where('id', $id);
        $status = $this->db->update('tbl_recruitment_contract_document_types', $data);
       
        if (!$status) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al agregar el registro'
            ]);
            return;
        }

        $response = [
            'success' => true,
            'message' => 'Documento actualizado'
        ];    

        echo json_encode($response);
    }
}
