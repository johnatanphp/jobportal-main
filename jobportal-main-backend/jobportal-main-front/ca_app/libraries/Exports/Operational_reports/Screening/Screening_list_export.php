<?php

class Screening_list_export
{
    private $spreadsheet;
    private $filters;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;    
    }

    public function __construct($filters = null)
    {
        $this->load->model('Employer_staff_request_manage_business_unit');

        $this->spreadsheet = null;

        if (is_null($filters) === false) {
            $this->build($filters);
        }
    }

    public function init()
    {
        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);

        $sheet = $this->spreadsheet->getActiveSheet();
    }

    public function build($filters = [])
    {
        $this->filters = $filters;

        $this->init();
        $this->build_header();
        
        if ($this->build_data()) {
            return $this;
        }

        return false;
    }

    private function build_header()
    {
        $header[] = 'ID';
        $header[] = 'FECHA SOLICITUD';
        $header[] = 'TIPO';	
        $header[] = 'DNI SOLICITADO';	
        $header[] = 'NOMBRE SOLICITADO';	
        $header[] = 'APELLIDO SOLICITADO';	
        $header[] = 'REQ ID';	
        $header[] = 'REQ NOMBRE';	
        $header[] = 'CONSULTORA';	
        $header[] = 'CLIENTE';	
        $header[] = 'UNIDAD DE NEGOCIO';
        $header[] = 'CENTRO DE COSTO';	
        $header[] = 'TIPO EGRESO';
        $header[] = 'ESTRUCTURA DE COSTO';
        $header[] = 'CENTRO DE COSTO CLIENTE';
        $header[] = 'REQ SOLICITANTE';
        $header[] = 'NOMBRE DEL JEFE INMEDIATO';
        $header[] = 'CREADO POR';
       
        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }

    private function data()
    {
        $filter = $this->filters;

        $user_id = $filter['user_id'];
        $business_unit_codes = $this->Employer_staff_request_manage_business_unit->get_business_units_by_user_id($user_id);

        $this->db->select([
            'ts.id AS ts_id', //Screening Id
            'ts_type.name AS ts_type_name', //Tipo Screening
            'ts.created_at AS ts_created_at', //Fecha Screening
            'ts.document_number AS ts_seeker_document_number', //Nro Doc. Identidad persona
            'ts.first_name AS ts_seeker_name', //Nombre Persona
            'ts.last_name AS ts_seeker_last_name', //Apellido Persona
            'IF(ts.job_title<>"", ts.job_title, sr.job_title) AS ts_job_title', //Puesto
            'sr.ID AS sr_id', //Solicitud Id
            'sr.name_immediate_boss AS sr_name_immediate_boss', //Nombre del jefe inmediato 
            'consultants.code AS ts_cia_code', //Cod Consultora
            'consultants.name AS ts_cia_name', //Consultora
            'clients.code AS ts_client_code', //Cod. Cliente
            'clients.name AS ts_client_name', //Cliente
            'business_units.business_unit_code AS ts_business_unit_code', //Cod. Unidad de negocio
            'business_units.business_unit_name AS ts_business_unit_name', //Unidad de negocio
            'ts.cost_center AS ts_cost_center_code', //Centro de costo
            'IF(ts.type_expense<>"", ts.type_expense, sr.type_expense) AS ts_type_expense', //Tipo Egreso
            'IF(ts.eecc_code<>"", ts.eecc_code, sr.eecc_code) AS ts_eecc_code', //Codigo estructura de costo
            'sr_employer.first_name AS ts_requested_by_first_name', //Solicitado por
            'ts.cost_center_client AS ts_cost_center_client', //Centro de costo cliente
            'employer.first_name AS ts_created_by_first_name', //Screening realizado por
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
        
        if (isset($filter['permission_business_unit']) && $filter['permission_business_unit'] == true) {
            $this->db->where_in('cost_centers.business_unit_code', $business_unit_codes);
        }

        if (isset($filter['start_date'])) {
            $this->db->where('ts.created_at>=', $filter['start_date']);
        }

        if (isset($filter['end_date'])) {
            $this->db->where('ts.created_at<=', $filter['end_date']);
        }

        $this->db->order_by('ts.created_at', 'ASC');

        $this->db->group_by('ts.id');

        return $this->db->get()->result();
    }

    public function build_data()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $requests = $this->data();

        foreach ($requests as $index => $row) {
            $col = [];
            $col[] = $row->ts_id;
            $col[] = $row->ts_created_at;
            $col[] = mb_strtoupper($row->ts_type_name);
            $col[] = $row->ts_seeker_document_number;
            $col[] = $row->ts_seeker_name;
            $col[] = $row->ts_seeker_last_name; 
            $col[] = $row->sr_id;
            $col[] = mb_strtoupper((string)$row->ts_job_title);
            $col[] = $row->ts_cia_name;
            $col[] = $row->ts_client_name;
            $col[] = $row->ts_business_unit_name;
            $col[] = $row->ts_cost_center_code;
            $col[] = $row->ts_type_expense; 
            $col[] = $row->ts_eecc_code; 
            $col[] = $row->ts_cost_center_client;
            $col[] = mb_strtoupper((string)$row->ts_requested_by_first_name);
            $col[] = mb_strtoupper((string)$row->sr_name_immediate_boss);
            $col[] = mb_strtoupper((string)$row->ts_created_by_first_name);

            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );
        }

        return true;
    }

    public function download($filename = 'reporte-excel.xlsx')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename .'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function save($filepath = '')
    {
        \PhpOffice\PhpSpreadsheet\Shared\File::setUseUploadTempDirectory(true);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, "Xlsx");
        $writer->save($filepath);
    }

    public function output()
    {
        ob_start();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter(
            $this->spreadsheet, 
            'Xlsx'
        );
        $writer->save('php://output');
        $result_object = ob_get_clean();
    }
}
