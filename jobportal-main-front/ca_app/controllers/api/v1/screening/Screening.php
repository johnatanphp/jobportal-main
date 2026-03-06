<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Screening extends REST_Controller
{
    public function index_get()
    {
        $input = $this->input->get();

        if((!isset($input['start_date']))) {
            $this->response([
                'status' => false,
                'message' => 'El parámetro - start_date es requerido'
            ], 200);
            return;
        }

        if(!isset($input['end_date'])) {
            $this->response([
                'status' => false,
                'message' => 'El parámetro - end_date es requerido'
            ], 200);
            return;
        }

        $start_date  = $input['start_date'];
        $end_date = $input['end_date'];

        $this->db->select([
            'ts.id AS ts_id', //Screening Id
            'ts_type.name AS ts_type_name', //Tipo Screening
            'ts.created_at AS ts_created_at', //Fecha Screening
            'ts.document_number AS ts_seeker_document_number', //Nro Doc. Identidad persona
            'IF(ts.first_name<>"", ts.first_name, JSON_UNQUOTE(JSON_EXTRACT(ts.response, \'$.data.firstname\'))) AS ts_seeker_name', //Nombre Persona
            'IF(ts.last_name<>"", ts.last_name, CONCAT(JSON_UNQUOTE(JSON_EXTRACT(ts.response, \'$.data.lastname\')), " ", JSON_UNQUOTE(JSON_EXTRACT(ts.response, \'$.data.secondSurname\')))) AS ts_seeker_last_name', //Apellido Persona
            'IF(ts.job_title<>"", ts.job_title, sr.job_title) AS ts_job_title', //Puesto
            'sr.ID AS sr_id', //Solicitud Id
            'consultants.code AS ts_cia_code', //Cod Consultora
            'consultants.name AS ts_cia_name', //Consultora
            'clients.code AS ts_client_code', //Cod. Cliente
            'clients.name AS ts_client_name', //Cliente
            'business_units.business_unit_code AS ts_business_unit_code', //Cod. Unidad de negocio
            'business_units.business_unit_name AS ts_business_unit_name', //Unidad de negocio
            'ts.cost_center AS ts_cost_center_code', //Centro de costo
            'IF(ts.type_expense<>"", ts.type_expense, sr.type_expense) AS ts_type_expense', //Tipo Egreso
            'IF(ts.eecc_code<>"", ts.eecc_code, sr.eecc_code) AS ts_eecc_code', //Codigo estructura de costo
            'IFNULL(employer.first_name, sr_employer.first_name) AS ts_requested_by_first_name', //Solicitado por
            'ts.cost_center_client AS ts_cost_center_client' //Centro de costo cliente
        ]);
        $this->db->from('tbl_screening ts');
        $this->db->join('tbl_screening_types ts_type', 'ts.type_id=ts_type.id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=ts.job_id', 'left');
        $this->db->join('tbl_staff_requests sr', 'jobs.request_ID=sr.ID', 'left');
        $this->db->join('tbl_employers sr_employer', 'sr_employer.ID=sr.employer_ID', 'left');
        $this->db->join('tbl_employers employer', 'employer.ID=ts.created_by', 'left');
        $this->db->join('tbl_workflow_cost_centers cost_centers', 'SUBSTRING(ts.cost_center, 4, 2) = cost_centers.business_unit_code AND cost_centers.code=ts.cost_center AND cost_centers.company_id=1', 'left');
        $this->db->join('tbl_workflow_clients clients', 'SUBSTRING(ts.cost_center, 4, 2) = cost_centers.business_unit_code AND cost_centers.client_code=clients.code AND clients.company_id=1', 'left');
        $this->db->join('tbl_workflow_consultants consultants', 'SUBSTRING(ts.cost_center, 4, 2) = cost_centers.business_unit_code AND consultants.code=cost_centers.cia_code AND consultants.company_id=1', 'left');
        $this->db->join('tbl_business_units business_units', 'SUBSTRING(ts.cost_center, 4, 2) = cost_centers.business_unit_code AND business_units.business_unit_code=cost_centers.business_unit_code AND business_units.company_id=1', 'left');
                
        $this->db->where('ts.response_code', 1); //Solicitud correcta
        $this->db->where('ts.created_at>=', $start_date);
        $this->db->where('ts.created_at<=', $end_date);

        $this->db->order_by('ts.created_at', 'ASC');

        $this->db->group_by('ts.id');

        $data =  $this->db->get()->result();

        $this->response([
            'status' => true,
            'data' => $data,
            'message' => 'OK'
        ], 200);
    }
}