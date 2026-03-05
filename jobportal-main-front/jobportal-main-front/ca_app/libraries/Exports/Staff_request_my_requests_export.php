<?php

class Staff_request_my_requests_export
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

        $this->db->select([
            //Datos solicitud
            'request.ID',
            'request.creation_date',
            'request.consultant_name',
            'request.business_unit_name',
            'request.client_company_name',
            'request.cost_center',
            'request.job_title',
            'request.request_type',
            'request.sts_process',
            //Datos solicitante
            'app_user_recruiters.ID AS recruiter_ID',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'app_user_recruiters.email AS recruiter_email',
            //request assigned to employer 
            'GROUP_CONCAT(DISTINCT(app_user_employers.first_name)) AS employer_first_name',
            //Datos R&S
            'recruitment_process.sts_stage AS rs_process_status',
            'recruitment_process.job_ID AS rs_job_ID'
        ]);
    	
        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
	    $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=post_job.ID', 'left');
		$this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.request_ID=request.ID', 'left');
        $this->db->join('tbl_employers app_user_employers', 'app_user_employers.ID=assigned_employers.employer_ID', 'left');

        $this->db->where('request.recruiter_ID', $filter['recruiter_id']);
   
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

        if (!empty($filter['employer']) && $filter['employer'] != 'all') {
            $this->db->where('assigned_employers.employer_ID', $filter['employer']);
        }

    	$this->db->order_by('request.ID', 'DESC');
		$this->db->group_by('request.ID');

    	return $this->db->get()->result();
    }

    private function build_header()
    {
        $header[] = 'Consultora';
        $header[] = 'Unidad de negocio';
        $header[] = 'Empresa cliente';
        $header[] = 'Centro de costo';
        $header[] = 'Req ID';
        $header[] = 'Puesto';
        $header[] = 'Fecha solicitud';
        $header[] = 'Usuario solicitante';
        $header[] = 'Usuario asignados';
       
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

        foreach ($requests as $index => $row) {
            $col = [];
            $col[] = $row->consultant_name;
            $col[] = $row->business_unit_name;
            $col[] = $row->client_company_name;
            $col[] = $row->cost_center;
            $col[] = $row->ID;
            $col[] = $row->job_title;
            $col[] = $row->creation_date;
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
        header('Content-Disposition: attachment;filename="' . $filename . '"');
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
}
