<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class JobLayout extends REST_Controller
{
    public function index_get()
    {
        // ✅ Obtener parámetros GET
        $input = $this->input->get();

        // ✅ Validar que venga el parámetro layout_id
        if(!isset($input['layout_id'])) {
            $this->response([
                'status' => false,
                'message' => 'El parámetro - layout_id es requerido'
            ], 200);
            return;
        }

        $layout_id = (int) $input['layout_id'];

        // ✅ Construcción del query
        $this->db->select("
            tjl.id,
            tjl.job_title AS job,
            tq.text AS education_req,
            tjl.education_req_detail AS description_req,
            tqm.text AS education_min,
            tjl.education_min_detail AS description_min,
            tjl.education AS formation,
            twe.name AS experiencie_year,
            tjl.experience_detail AS description_exp,
            GROUP_CONCAT(DISTINCT tjcs.skill_name ORDER BY tjcs.skill_name SEPARATOR ', ') AS skills,
            GROUP_CONCAT(DISTINCT tjr.responsibility ORDER BY tjr.responsibility SEPARATOR ', ') AS responsibilities
        ", false); // false => evita que CodeIgniter escape la query

        $this->db->from('tbl_job_layouts tjl');
        $this->db->join('tbl_qualifications tq', 'tq.id = tjl.study_grade_req', 'left');
        $this->db->join('tbl_qualifications tqm', 'tqm.id = tjl.study_grade_min', 'left');
        $this->db->join('tbl_work_experiences twe', 'twe.code = tjl.experience', 'left');
        $this->db->join('tbl_job_charge_skills tjcs', 'tjcs.job_charge_id = tjl.job_charge_id', 'left');
        $this->db->join('tbl_job_layout_responsibilities tjr', 'tjr.job_layout_id = tjl.id', 'left');

        // ✅ Condición dinámica con el layout_id recibido
        $this->db->where('tjl.id', $layout_id);

        // ✅ Agrupación para que GROUP_CONCAT funcione correctamente
        $this->db->group_by([
            'tjl.id',
            'tjl.job_title',
            'tq.text',
            'tjl.education_req_detail',
            'tqm.text',
            'tjl.education_min_detail',
            'tjl.education',
            'twe.name',
            'tjl.experience_detail'
        ]);

        // ✅ Ejecutar query
        $data = $this->db->get()->result();

        // ✅ Respuesta
        if ($data) {
            $this->response([
                'status' => true,
                'data' => $data,
                'message' => 'OK'
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron datos para el layout_id proporcionado'
            ], 200);
        }
    }
}
