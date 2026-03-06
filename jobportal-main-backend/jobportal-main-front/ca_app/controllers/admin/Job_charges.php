<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_charges extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        $this->load->model('Job_charge');
    }
    
    public function list()
    {
	    $this->db->from('tbl_job_charges');
        $this->db->where('country_id', $this->input->get('country_id'));
	    $result = $this->db->get()->result();

        echo json_encode([
            'data' => $result
        ]);
    }

    public function add()
    {
        $data = [
            'charge_name' => trim($this->input->post('name')),
            'valorization_score' => trim($this->input->post('valorization_score')),
            'valorization_grade' => trim($this->input->post('valorization_grade')),
            'sts' => trim($this->input->post('status')),
            'country_id' => trim($this->input->post('country_id'))
        ];

        $this->db->insert('tbl_job_charges', $data);
        $id = $this->db->insert_id();

        $response = [];

        if ($id) {
            $response = [
                'success' => true,
                'message' => 'Grupo ocupacional agregado'
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
            'charge_name' => trim($this->input->post('name')),
            'valorization_score' => trim($this->input->post('valorization_score')),
            'valorization_grade' => trim($this->input->post('valorization_grade')),
            'sts' => trim($this->input->post('status'))
        ];

        $this->db->where('ID', $this->input->post('id'));
        $status = $this->db->update('tbl_job_charges', $data);
    
        $response = [];

        if ($status) {
            $response = [
                'success' => true,
                'message' => 'Grupo ocupacional actualizada'
            ];    
            echo json_encode($response);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el registro'
        ]);
    }

    public function get_skills()
    {
        $skills = $this->db->from('tbl_job_charge_skills')
        ->where([
            'job_charge_id' => $this->input->get('job_charge_id')
        ])
        ->order_by('id', 'DESC')
        ->get()
        ->result();

        echo json_encode([
            'success' => true,
            'skills' => $skills
        ]);
    }

    public function skill_add()
    {
        $job_charge_id = $this->input->post('job_charge_id');

        $skill_name = trim($this->input->post('skill_name'));

        if ($skill_name  == '') {
            echo json_encode([
                'success' => false,
                'message' => 'La habilidad no puede estar vacía'
            ]);

            return;
        }

        $jc_row = $this->db->get_where('tbl_job_charge_skills', [
            'skill_name' => $skill_name,
            'job_charge_id' => $job_charge_id
        ])->row();

        if ($jc_row) {
            echo json_encode([
                'success' => false,
                'message' => 'La habilidad ya existe'
            ]);

            return;
        }

        $this->db->insert('tbl_job_charge_skills', [
            'skill_name' => $skill_name,
            'job_charge_id' => $job_charge_id
        ]);

        $skill_id = $this->db->insert_id();

        echo json_encode([
            'success' => true,
            'skill_name' => $skill_name,
            'skill_id' => $skill_id
        ]);
    }

    public function skill_remove()
    {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $status = $this->db->delete('tbl_job_charge_skills');

        echo json_encode([
            'success' => $status
        ]);
    }
}
