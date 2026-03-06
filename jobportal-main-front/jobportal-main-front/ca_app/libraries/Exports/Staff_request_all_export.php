<?php

class Staff_request_all_export
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

    private function data()
    {
        $filter = $this->filters;

        $user = $this->Employer->find($this->session->userdata('user_id'));

        $this->db->select([
            'rc_logs.job_ID AS job_id',
            'MIN(rc_logs.datetime) AS min_datetime',
            'MAX(rc_logs.datetime) AS max_datetime'
        ]);
        $this->db->from('tbl_recruitment_log_candidate_stage rc_logs');
        $this->db->where('rc_logs.stage', 7);
        $this->db->group_by('rc_logs.job_ID');
        $sql_rc_log = $this->db->get_compiled_select();

        $this->db->select([
            'rc_contracts.job_id AS job_id',
            'MAX(rc_contracts.hired_at) AS hired_at',
            'COUNT(rc_contracts.id) AS total_hired'
        ]);
        $this->db->from('tbl_recruitment_contracts rc_contracts');
        $this->db->where('rc_contracts.hired', 1);
        $this->db->group_by('rc_contracts.job_id');
        $sql_rc_contracts = $this->db->get_compiled_select();

        $this->db->select([
            //Get data staff request
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
            'rc_log.min_datetime AS rc_min_datetime',
            'rc_log.max_datetime AS rc_max_datetime',
            'rc_contracts.hired_At AS rc_hired_at',
            'rc_contracts.total_hired AS rc_total_hired',
            //Get data staff recruiter
            'app_user_recruiters.first_name AS recruiter_first_name',
            //Posted job
            'post_job.ID AS job_ID',
            'post_job.ID AS rs_ID',
            //request assigned to employer 
            'GROUP_CONCAT(DISTINCT(app_user_employers.first_name)) AS employer_first_name',
            'recruitment_process.sts AS rs_process_status_id',
            'recruitment_process.closed_at AS rs_process_closed_at',
            'mof.job_charge_id AS mof_job_charge_id',
            'jp.job_charge_ID AS jp_job_charge_id',
            'jl.job_charge_id AS jl_job_charge_id', 
        ]);
        
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_job_layouts jl', 'request.job_layout_id=jl.id', 'left');
        $this->db->join('tbl_job_profiles jp', 'request.job_profile_ID=jp.ID', 'left');
        $this->db->join('tbl_mofs mof', 'request.mof_ID=mof.ID', 'left');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');
        $this->db->join('('. $sql_rc_log . ') AS rc_log', 'rc_log.job_id=post_job.ID', 'left');
        $this->db->join('('. $sql_rc_contracts . ') AS rc_contracts', 'rc_contracts.job_id=post_job.ID', 'left');

        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=request.ID', 'left');
        $this->db->join('tbl_employers app_user_employers', 'app_user_employers.ID=assigned_employers.employer_ID', 'left');
        
        $this->db->where('request.company_ID', $filter['company_id']);
        
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

        if ($filter['query'] != '') {
            $this->db->group_start();
            $this->db->like('request.ID', $filter['query']);
            $this->db->or_like('request.job_title', $filter['query']);
            $this->db->group_end();
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

        // $this->db->order_by('FIELD(request.sts_process, 
        //     "unassigned", 
        //     "assigned",
        //     "published", 
        //     "pending", 
        //     "suspended", 
        //     "rejected", 
        //     "canceled")'
        // );

        $this->db->order_by('request.ID', 'asc');
        //$this->db->order_by('recruitment_process.sts_stage', 'desc');
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
        $header[] = 'Req. Fecha solicitud';
        $header[] = 'Req. Vacantes';
        $header[] = 'N° Postulantes Contratados';
        $header[] = 'Req. Ubicacion';
        $header[] = 'Grupo ocupacional';
        $header[] = 'Req. Estado';
        $header[] = 'Id Proceso RyS';
        $header[] = 'Estado Proceso RyS';
        $header[] = 'Derivación Contratación Inicial';
        $header[] = 'Derivación Contratación Final';
        $header[] = 'Fecha cierre';
        $header[] = 'Total de días requerido';
        $header[] = 'Usuario solicitante';
        $header[] = 'Usuario empleador';
       
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

        foreach ($requests as $index => $row) {

            $sr_created_at = $row->creation_date;
            $sent_to_hiring_min = $row->rc_min_datetime ? $row->rc_min_datetime : null;
            $sent_to_hiring_max = $row->rc_max_datetime ? $row->rc_max_datetime : null;
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
            $col[] = $row->vacancies;
            $col[] = (string)$rc_total_hired;
            $col[] = $row->request_location;
            $col[] = $job_charge_name;
            $col[] = $status_types[$row->request_status_id] ?? '-';
            $col[] = $row->job_ID;
            $col[] = $process_status_types[$row->rs_process_status_id] ?? '-'; ;
            $col[] = $sent_to_hiring_min ? $this->date_format($sent_to_hiring_min, 'd/m/Y H:i') : '-';
            $col[] = $sent_to_hiring_max ? $this->date_format($sent_to_hiring_max, 'd/m/Y H:i') : '-';
            $col[] = $rc_closing_date ? $this->date_format($rc_closing_date, 'd/m/Y H:i') : '-';
            $col[] = $days_need !== null ? $days_need : '-'; 
            $col[] = $row->recruiter_first_name;
            $col[] = $row->employer_first_name;
       
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

        $filter['query'] = trim((string)$filter['query']);
    
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
