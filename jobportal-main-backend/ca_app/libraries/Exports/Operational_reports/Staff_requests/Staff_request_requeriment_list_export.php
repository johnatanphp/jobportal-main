<?php

class Staff_request_requeriment_list_export
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

        $style_title = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'ffffff'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '002060'
                ]
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                //'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];
        
        $sheet->getStyle('A1:AN1')->applyFromArray($style_title);
    }

    public function build($filters = [])
    {
        $this->filters = $this->validate_filter($filters);

        $this->init();
        $this->build_header();
        
        if ($this->build_data()) {
            return $this;
        }

        return false;
    }
    
    private function build_sql_data_request_benefits()
    {
        $this->db->select([
            'sr_additional_benefits.request_ID AS request_id',
            'additional_benefits.benefit_name',
            'sr_additional_benefits.detail AS request_benefit_detail',
        ]);
        $this->db->from('tbl_staff_request_additional_benefits sr_additional_benefits');
        $this->db->join('tbl_laboral_benefits additional_benefits', 'additional_benefits.ID=sr_additional_benefits.benefit_ID');
       
        return $this->db->get_compiled_select();
    }
    
    private function build_sql_data_entry_form()
    {
        $this->db->select([
            'entry_forms.job_id AS job_id',
            'MIN(entry_forms.creation_date) AS entry_form_min_datetime',
            'MAX(entry_forms.creation_date) AS entry_form_max_datetime'
        ]);
        $this->db->from('tbl_seeker_form_rtps entry_forms');
       
        $this->db->group_by('entry_forms.job_id');
        return $this->db->get_compiled_select();
    }
    
    private function build_sql_data_empo()
    {
        $this->db->select([
            'exam_request_empo.job_id AS job_id',
            'MIN(exam_request_empo.created_at) AS empo_min_datetime',
            'MAX(exam_request_empo.created_at) AS empo_max_datetime'
        ]);
        $this->db->from('tbl_exam_request_seekers exam_request_empo');
        $this->db->where('exam_request_empo.active', 1);
        $this->db->where('exam_request_empo.status!=', 8);
        $this->db->group_by('exam_request_empo.job_id');
        return $this->db->get_compiled_select();
    }
    
    private function build_sql_data_selection()
    {
        $this->db->select([
            'rc_candidate.job_ID AS job_id',
            'MIN(rc_candidate.datetime) AS selection_min_datetime',
            'MAX(rc_candidate.datetime) AS selection_max_datetime'
        ]);
        $this->db->from('tbl_recruitment_log_candidate_stage rc_candidate');
        $this->db->where_in('rc_candidate.stage', [
            '6', //SELECCION
        ]);
        $this->db->group_by('rc_candidate.job_ID');
        return $this->db->get_compiled_select();
    }
    
    private function build_sql_data_deliverable()
    {
        $this->db->select([
            'rc_candidate.job_ID AS job_id',
            'MIN(rc_candidate.datetime) AS deliverable_min_datetime',
            'MAX(rc_candidate.datetime) AS deliverable_max_datetime'
        ]);
        $this->db->from('tbl_recruitment_log_candidate_stage rc_candidate');
        $this->db->where_in('rc_candidate.stage', [
            '2', //LONG LIST
            '5' // SHORT LIST
        ]);
        $this->db->group_by('rc_candidate.job_ID');
        return $this->db->get_compiled_select();
    }
    
    private function build_sql_data_contract()
    {
        $this->db->select([
            'rc_contracts.job_id AS job_id',
            'MIN(rc_contracts.hired_at) AS min_hired_at',
            'MAX(rc_contracts.hired_at) AS max_hired_at',
            'COUNT(rc_contracts.id) AS total_hired'
        ]);
        $this->db->from('tbl_recruitment_contracts rc_contracts');
        $this->db->where('rc_contracts.hired', 1);
        $this->db->group_by('rc_contracts.job_id');
        return $this->db->get_compiled_select();
    }
    
    private function build_sql_data_discarded()
    {
        $this->db->select([
            'rc_candidate.process_id AS process_id',
            'COUNT(rc_candidate.seeker_ID) AS total_discarded'
        ]);
        $this->db->from('tbl_recruitment_candidates rc_candidate');
        $this->db->where('rc_candidate.discarded', 1);
        $this->db->group_by('rc_candidate.process_id');
        return $this->db->get_compiled_select();
    }
    
    private function data()
    {
        $filter = $this->filters;

        $user = $this->Employer->find($this->session->userdata('user_id'));
        
        $sql_request_benefits = $this->build_sql_data_request_benefits();
        $sql_rc_discarded = $this->build_sql_data_discarded();
        $sql_rc_deliverable = $this->build_sql_data_deliverable();
        $sql_rc_selection = $this->build_sql_data_selection();
        $sql_rc_empo = $this->build_sql_data_empo();
        $sql_rc_entry_form = $this->build_sql_data_entry_form();
        $sql_rc_contracts = $this->build_sql_data_contract();

        $this->db->select([
            // Datos solicitudes
            'request.ID',
            'request.consultant_name',
            'request.business_unit_name',
            'request.client_company_name',
            'request.cost_center',
            'request.creation_date',
            'request.job_title',
            'request.request_type',
            'request.sts_process',
            'request.location AS request_location',
            'request.sts_process AS request_status_id',
            'request.vacancies AS vacancies',
            'request.modality_contracting AS request_modality_contracting',
            'type_reasons.name AS request_reason_name',
            'request.type_requirement AS request_type_requirement',
            'request.monthly_gross_salary AS request_base_salary',
            'IFNULL(internal_areas.area_name, job_industry.industry_name) AS request_area',
            'countries.currency_code AS request_currency_code',
            
            // Datos Asignacion solicitud
            'request_assignment.reassignment_date AS request_reassignment_date',
            
            //Datos Cancelacion solicitud
            'request_canceled.canceled_at AS request_canceled_at',
            // Datos contrataciones
            'rc_contracts.min_hired_at AS min_hired_at',
            'rc_contracts.max_hired_at AS max_hired_at',
            'rc_contracts.total_hired AS rc_total_hired',
            
            // Datos descartes
            'IFNULL(rc_discarded.total_discarded, 0) AS total_discarded',
            
            // Datos entregas
            'rc_deliverable.deliverable_min_datetime',
            'rc_deliverable.deliverable_max_datetime',
            
            // Datos seleccion reclutamiento
            'rc_selection.selection_min_datetime',
            'rc_selection.selection_max_datetime',
            
            // Dato EMPO
            'rc_empo.empo_min_datetime',
            'rc_empo.empo_max_datetime',
            
            // Datos ficha de ingreso
            'rc_entry_form.entry_form_min_datetime',
            'rc_entry_form.entry_form_max_datetime',
            
            // Datos beneficio solicitud
            'GROUP_CONCAT(DISTINCT TRIM(CONCAT(request_benefits.benefit_name, " ", IF(request_benefits.request_benefit_detail IS NOT NULL OR request_benefits.request_benefit_detail="", CONCAT(request_benefits.request_benefit_detail, " ", countries.currency_code), ""))) SEPARATOR ", ") AS request_benefit_name',
            
            // Datos solicitantes
            'app_user_recruiters.first_name AS recruiter_first_name',
           
            // Datos empleos
            'post_job.ID AS job_ID',
            'post_job.dated AS job_created_at',
            
            // Datos reclutadores asignados
            'GROUP_CONCAT(DISTINCT(app_user_employers.first_name)) AS assignment_employer_first_name',
            
            // Datos Proceso de reclutamiento
            'recruitment_process.id AS process_id',
            'recruitment_process.sts AS rs_process_status_id',
            'recruitment_process.closed_at AS rs_process_closed_at',
            
            // Datos Mof
            'mof.job_charge_id AS mof_job_charge_id',
            
            // Datos Job Layouts
            'jp.job_charge_ID AS jp_job_charge_id',
            'jl.job_charge_id AS jl_job_charge_id'
        ]);
        
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_companies companies', 'companies.ID=request.company_ID');
        $this->db->join('tbl_countries countries', 'countries.ID=companies.country_id');
        $this->db->join('tbl_internal_areas internal_areas', 'internal_areas.ID=request.belonging_area_ID', 'left');
        $this->db->join('tbl_job_industries job_industry', 'job_industry.ID=request.industry_ID', 'left');
        $this->db->join('tbl_staff_request_type_reasons type_reasons', 'type_reasons.id=request.reason_request', 'left');
        $this->db->join('tbl_job_layouts jl', 'request.job_layout_id=jl.id', 'left');
        $this->db->join('tbl_job_profiles jp', 'request.job_profile_ID=jp.ID', 'left');
        $this->db->join('tbl_mofs mof', 'request.mof_ID=mof.ID', 'left');
        $this->db->join('tbl_staff_request_canceled request_canceled', 'request_canceled.request_id=request.ID', 'left');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_staff_request_additional_benefits request_additional_benefits', 'request_additional_benefits.request_ID=request.ID', 'left');
        $this->db->join('tbl_laboral_benefits additional_benefits', 'additional_benefits.ID=request_additional_benefits.benefit_ID', 'left');
        $this->db->join('tbl_staff_request_assignments request_assignment', 'request_assignment.request_id=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');
        $this->db->join('('. $sql_request_benefits . ') AS request_benefits', 'request_benefits.request_id=request.ID', 'left');
        $this->db->join('('. $sql_rc_discarded . ') AS rc_discarded', 'rc_discarded.process_id=recruitment_process.id', 'left');
        $this->db->join('('. $sql_rc_deliverable . ') AS rc_deliverable', 'rc_deliverable.job_id=post_job.ID', 'left');
        $this->db->join('('. $sql_rc_selection . ') AS rc_selection', 'rc_selection.job_id=post_job.ID', 'left');
        $this->db->join('('. $sql_rc_empo . ') AS rc_empo', 'rc_empo.job_id=post_job.ID', 'left');
        $this->db->join('('. $sql_rc_entry_form . ') AS rc_entry_form', 'rc_entry_form.job_id=post_job.ID', 'left');
        $this->db->join('('. $sql_rc_contracts . ') AS rc_contracts', 'rc_contracts.job_id=post_job.ID', 'left');
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=request.ID', 'left');
        $this->db->join('tbl_employers app_user_employers', 'app_user_employers.ID=assigned_employers.employer_ID', 'left');
        
        $this->db->where('request.company_ID', $filter['company_id']);
        $this->db->where_in('request.request_model_id', [1, 2, 3]);
        
        if ($filter['request_year'] != 'all') {
            $this->db->where('request.creation_date>=', $filter['request_year'] . '-01-01 00:00:00');
            $this->db->where('request.creation_date<=', $filter['request_year'] . '-12-31 23:59:59');
        }

        if ($filter['status_rq'] != 'all') {
            $this->db->where('request.sts_process', $filter['status_rq']);
        }

        if ($filter['status_rs'] != 'all') {
            $this->db->where('recruitment_process.sts_stage', $filter['status_rs']);
        }

        if ($filter['type'] != 'all') {
            $this->db->where('request.request_type', $filter['type']);
        }

        if (!empty($filter['recruiter']) && $filter['recruiter'] != 'all') {
            $this->db->where('request.recruiter_ID', $filter['recruiter']);
        }

        if (!empty($filter['employer']) && $filter['employer'] != 'all') {
            $this->db->where('assigned_employers.employer_ID', $filter['employer']);
        }

        if ($user->is_admin != 'yes') {
            $this->db->where_in('request.cod_business_unit', $filter['business_unit_codes']);
        }

        $this->db->order_by('request.ID', 'asc');
        $this->db->group_by('request.ID');

        return $this->db->get()->result();
    }

    private function build_header()
    {
        $header[] = 'Consultora';
        $header[] = 'Unidad de negocio';
        $header[] = 'Empresa cliente';
        $header[] = 'Centro de costo';
        $header[] = 'Req. Id';
        $header[] = 'Req. Puesto';
        $header[] = 'Req. Creación';
        $header[] = 'MODALIDAD DE VINCULO';
        $header[] = 'AREA';
        $header[] = 'MOTIVO DE SOLICITUD';
        $header[] = 'TIPO DE SERVICIO ATENDER';
        $header[] = 'SALARIO BASE';
        $header[] = 'BONIFICACIONES ADICIONALES	';
        $header[] = 'CARGO DEL EMPLEADOR';
        $header[] = 'Req. Ubicacion';
        $header[] = 'Grupo ocupacional';
        $header[] = 'SOLICITUD ESTADO';
        $header[] = 'PROCESO ESTADO';
        $header[] = 'REASIGNADO A';
        $header[] = 'FECHA DE REASIGNACION';
        $header[] = 'FECHA PUBLICACION';
        $header[] = 'FECHA CANCELACION';
        $header[] = 'VACANTES';
        $header[] = 'VACANTES CUBIERTAS';
        $header[] = 'VACANTES PENDIENTES';
        $header[] = 'VACANTES DESCARTADAS';
        $header[] = 'FECHA DE ENTREGABLE INICIAL';
        $header[] = 'FECHA DE ENTREGABLE FINAL';
        $header[] = 'FECHA DE SELECCIÓN INICIAL';
        $header[] = 'FECHA DE SELECCIÓN FINAL';
        $header[] = 'FECHA DE EMPO INICIAL';
       	$header[] = 'FECHA DE EMPO FINAL';
        $header[] = 'FECHA DE COMPILACION DE DOCUMENTOS INICIAL';
        $header[] = 'FECHA DE COMPILACION DE DOCUMENTOS FINAL';
        $header[] = 'FECHA DE CONTRATACION INICIAL';
        $header[] = 'FECHA DE CONTRATACION FINAL';
        $header[] = 'FECHA CIERRE';
        $header[] = 'Total de días requerido';
        $header[] = 'Usuario solicitante';
        $header[] = 'Usuario empleador';
        
        $header = array_map('mb_strtoupper', $header);
        
        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }
    public function build_data()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $requests = $this->data();
        $job_charges = $this->get_charges();

        $status_types = [
            '8' =>	'No iniciado',	
            '9' =>	'Iniciado',	
            'assigned' => 'Asignada',	
            'canceled' => 'Cancelada',	
            'pending' => 'Pendiente',	
            'published' => 'Publicada',	
            'rejected' => 'Rechazada',	
            'suspended' => 'Suspendida',	
            'unassigned' =>	'Sin asignar'
        ];
        
        $process_status_types = [
            'active' =>	'Activo',	
            'finished' => 'Terminado',	
            'canceled' => 'Cancelado',
            'suspended' => 'Suspendido',	
        ];
        
        $modality_data = [
            'outsourcing' => 'Tercerización',
            'intermediation' => 'Intermediación',
            'direct_form_client'  => 'Planilla directa del cliente',
            'others' => 'Otros'
        ];

        foreach ($requests as $index => $row) {

            $sr_created_at = $row->creation_date;
            $rc_closing_date = $row->rs_process_closed_at ? $row->rs_process_closed_at : null;
            $rc_total_hired = $row->rc_total_hired ? $row->rc_total_hired : 0;
            $days_need = null;

            if ($rc_closing_date) {
                $rc_date_start = new DateTime(date('Y-m-d', strtotime($sr_created_at)));
                $rc_date_end = new DateTime(date('Y-m-d', strtotime($rc_closing_date)));
                $rc_diff = $rc_date_start->diff($rc_date_end);
                $days_need = $rc_diff->days;
            }

            $job_charge_id = null;

            if ($row->mof_job_charge_id) {
                $job_charge_id = $row->mof_job_charge_id;
            }

            if ($row->jp_job_charge_id) {
                $job_charge_id = $row->jp_job_charge_id;
            }

            if ($row->jl_job_charge_id) {
                $job_charge_id = $row->jl_job_charge_id;
            }

            $job_charge_name = isset($job_charges[$job_charge_id]) ? ($job_charges[$job_charge_id])->charge_name : '-';

            $col = [];
            $col[] = $row->consultant_name;
            $col[] = $row->business_unit_name;
            $col[] = $row->client_company_name;
            $col[] = $row->cost_center;
            $col[] = $row->ID;
            $col[] = $row->job_title;
            $col[] = $this->date_format($sr_created_at, 'd/m/Y H:i');
    
            $col[] = $modality_data[$row->request_modality_contracting] ?? '-';
            $col[] = $row->request_area ? $row->request_area : '-';
            $col[] = $row->request_reason_name ? $row->request_reason_name : '-';
            $col[] = $row->request_type_requirement ? $row->request_type_requirement : '-';
            $col[] = $row->request_base_salary ? number_format($row->request_base_salary, 2, '.', ',') . ' ' . $row->request_currency_code: '-';
        
            $col[] = $row->request_benefit_name ? $row->request_benefit_name : '-';
            
            $col[] = 'RECLUTADOR';
            $col[] = $row->request_location;
            $col[] = $job_charge_name;
            
            $col[] = $status_types[$row->request_status_id] ?? '-';
            $col[] = $process_status_types[$row->rs_process_status_id] ?? 'SIN INICIAR';    
            
            $request_reassignment_date = $row->request_reassignment_date;
            
            if ($request_reassignment_date) {
                $col[] =  $row->assignment_employer_first_name;
                $col[] = $this->date_format($request_reassignment_date, 'd/m/Y');
            } else {
                $col[] = '--';
                $col[] = '--';
            }
            
            $col[] = $row->job_created_at ? $row->job_created_at : '-';    
            $col[] = $row->request_canceled_at ? $this->date_format($row->request_canceled_at , 'd/m/Y H:i') : '-';
        
            $col[] = $row->vacancies;
            $col[] = (string)$rc_total_hired;
            $col[] = (string)($row->vacancies - $rc_total_hired);	
            $col[] = (string)$row->total_discarded;	            
            $col[] = $row->deliverable_min_datetime ? $this->date_format($row->deliverable_min_datetime, 'd/m/Y') : '-';
            $col[] = $row->deliverable_max_datetime ? $this->date_format($row->deliverable_max_datetime, 'd/m/Y') : '-';
            $col[] = $row->selection_min_datetime ? $this->date_format($row->selection_min_datetime, 'd/m/Y') : '-';
            $col[] = $row->selection_max_datetime ? $this->date_format($row->selection_max_datetime, 'd/m/Y') : '-';
            $col[] = $row->empo_min_datetime ? $this->date_format($row->empo_min_datetime, 'd/m/Y') : '-';
            $col[] = $row->empo_max_datetime ? $this->date_format($row->empo_max_datetime, 'd/m/Y') : '-';            
            $col[] = $row->entry_form_min_datetime ? $this->date_format($row->entry_form_min_datetime, 'd/m/Y') : '-';
            $col[] = $row->entry_form_max_datetime ? $this->date_format($row->entry_form_max_datetime, 'd/m/Y') : '-';
            $col[] = $row->min_hired_at ? $this->date_format($row->min_hired_at, 'd/m/Y H:i') : '-';
            $col[] = $row->min_hired_at ? $this->date_format($row->max_hired_at, 'd/m/Y H:i') : '-';
            $col[] = $rc_closing_date ? $this->date_format($rc_closing_date, 'd/m/Y H:i') : '-';
            $col[] = $days_need !== null ? $days_need : '-'; 
            $col[] = $row->recruiter_first_name;
            $col[] = $row->assignment_employer_first_name;
       
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

    public function validate_filter($filter)
    {
        if (!in_array($filter['status_rq'], array('unassigned', 'assigned', 'published', 'pending', 'rejected', 'canceled', 'all'))) {
            $filter['status_rq'] = 'all';
        }

        if (!isset($filter['status_rs'])) {
            $filter['status_rs'] = 'all';
        }
        
        if (!in_array($filter['type'], array('internal', 'external', 'all'))) {
            $filter['type'] = 'all';
        }

        return $filter;
    }

    private function get_charges()
    {
        $data = [];
        $this->db->from('tbl_job_charges');
        $this->db->where('country_id', 56);
        $results = $this->db->get()->result();

        foreach ($results as $row) {
            $data[$row->ID] = $row;
        }

        return $data;
    }

    private function date_format($date, $fomat = 'Y-m-d H:is')
    {
        $datetime = new DateTime($date);
        return $datetime->format($fomat);
    }
}
