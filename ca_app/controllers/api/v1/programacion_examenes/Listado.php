<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Listado extends REST_Controller
{
    public function index_get()
    {
        $input = $this->input->get();
        
        $examen_fecha_inicio = $this->input->get('examen_fecha_inicio', true);
        $examen_fecha_fin = $this->input->get('examen_fecha_fin', true);

        if (trim($examen_fecha_inicio.$examen_fecha_fin) != '' && (!isset($input['examen_fecha_inicio']) || !isset($input['examen_fecha_fin']))) {
            $this->response([
                'status' => false,
                'error' => 'Debe ingresar los parámetros - examen_fecha_inicio y examen_fecha_fin son requeridos'
            ], 200);

            return;
        }

        $this->save_tmp_product_code();

        $covid19_types = [];

        $this->db->from('tbl_exam_covid19_types');
        $result_covid19 = $this->db->get()->result();

        foreach ($result_covid19 as $row) {
            $covid19_types[$row->id] = $row->name;
        }

        $this->db->select([
            'seeker.document_type',
            'seeker.document_number',
            'seeker.first_name',
            'seeker.last_name',
            'seeker.email',
            'seeker.gender',
            'seeker.mobile',
            'seeker.present_address',
            'seeker.dob',
            'seeker.employee_code',
            'exam_request_seekers.exam_date',
            'exam_request_seekers.exam_time',
            'exam_request_types.name AS exam_type_name',
            'medical_centers.code AS medical_center_code',
            'medical_centers.name AS medical_center_name',
            'jobs.job_title',
            'staff_requests.no_cia',
            'staff_requests.consultant_name',
            'staff_requests.cod_clie',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'recruitment_documents.file_source AS exam_result_file_path',
            'result_types.result_name AS exam_result_name',
            'exam_request_seekers.exam_doc_type AS exam_doc_type',
            'exam_request_seekers.exam_type_id'
        ]);

        $this->db->join('tbl_exam_request_seekers exam_request_seekers', 'exam_request_seekers.id=seeker_exams.exam_request_seeker_id');
        $this->db->join('tbl_medical_centers medical_centers', 'medical_centers.code=exam_request_seekers.medical_center_code');
        $this->db->join('tbl_exam_request_types exam_request_types', 'exam_request_types.id=seeker_exams.exam_type_id');
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID=exam_request_seekers.seeker_id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=exam_request_seekers.job_id');
        $this->db->join('tbl_staff_requests staff_requests', 'staff_requests.ID=jobs.request_ID', 'left');

        $this->db->join(
            'tbl_exam_request_results exam_results', 
            'exam_results.exam_request_seeker_id=seeker_exams.exam_request_seeker_id AND exam_results.exam_type_id=seeker_exams.exam_type_id AND exam_results.document_type IN ("certificate_emo", "certificate_covid19", "certificate_screnning")', 
            'left'
        );
        $this->db->join(
            'tbl_recruitment_attached_documents recruitment_documents', 
            'recruitment_documents.ID=exam_results.result_file_id', 
            'left'
        );
        $this->db->join(
            'tbl_exam_request_result_types result_types', 
            'result_types.id=exam_results.result_status', 
            'left'
        );

        $this->db->where('exam_request_seekers.active', 1);
        $this->db->where('seeker_exams.realized', 1);
        
        if ($examen_fecha_inicio && $examen_fecha_fin) {
            $this->db->where('exam_request_seekers.exam_date>=', $examen_fecha_inicio);
            $this->db->where('exam_request_seekers.exam_date<=', $examen_fecha_fin);
        }

        $this->db->group_by(['seeker_exams.exam_request_seeker_id', 'seeker_exams.exam_type_id']);

        $result = $this->db->get()->result();

        $data = [];
        $data_row = []; 
        
        foreach ($result as $row) {

            $exam_doc_type = $row->exam_doc_type;

            if ($row->exam_type_id == 2) {
                $exam_doc_type = isset($covid19_types[$exam_doc_type]) ? $covid19_types[$exam_doc_type] : ""; 
            }

            $data_row['doc_identidad_tipo'] = document_type_abbr($row->document_type);
            $data_row['doc_identidad_numero'] = $row->document_number;
            $data_row['nombres'] = $row->first_name;
            $data_row['apellidos'] = $row->last_name;
            $data_row['email'] = $row->email;
            $data_row['sexo'] = gender_text($row->gender);
            $data_row['fecha_nacimiento'] = $row->dob;
            $data_row['direccion'] = $row->present_address;
            $data_row['telefono_movil'] = $row->mobile;
            $data_row['trabajador_codigo'] = $row->employee_code;
            $data_row['producto_codigo'] = $this->get_product_code($row->exam_type_id, $exam_doc_type);
            $data_row['proveedor_codigo'] = $row->medical_center_code;
            $data_row['proveedor_nombre'] = $row->medical_center_name;
            $data_row['examen_fecha'] = $row->exam_date;
            $data_row['examen_hora'] = $row->exam_time;
            $data_row['examen_nombre'] = $row->exam_type_name;
            $data_row['examen_resultado'] = $row->exam_result_name;
            $data_row['examen_certificado'] = $row->exam_result_file_path ? file_url($row->exam_result_file_path) : null;
            $data_row['examen_tipo'] = $exam_doc_type;
            $data_row['empleo_nombre'] = $row->job_title;
            $data_row['consultora_codigo'] = $row->no_cia;
            $data_row['consultora'] = $row->consultant_name;
            $data_row['cliente_codigo'] = $row->cod_clie;
            $data_row['cliente'] = $row->client_company_name;
            $data_row['unidad_negocio'] = $row->business_unit_name;
            $data_row['centro_costo'] = $row->cost_center;

            $data[] = $data_row;
        }

        $this->response([
            'status' => true,
            'data' => ['examenes' => $data]
        ], 200);
    }

    private function save_tmp_product_code()
    {
        $query = $this->db->query("
            SELECT '1' AS 'exam_type_id', name, product_code FROM tbl_exam_emo_types 
                UNION ALL 
            SELECT '2' AS 'exam_type_id', name, product_code FROM tbl_exam_covid19_types;"
        );
        
        $product_codes = [];

        foreach ($query->result() as $row)
        {   
            $product_codes[$row->exam_type_id][$row->name] = $row->product_code; 
        }

        $this->product_codes = $product_codes;
    }

    private function get_product_code($exam_type_id, $exam_doc_type)
    {
        return isset($this->product_codes[$exam_type_id][$exam_doc_type]) ? 
            $this->product_codes[$exam_type_id][$exam_doc_type] :
            null;  
    }
}
