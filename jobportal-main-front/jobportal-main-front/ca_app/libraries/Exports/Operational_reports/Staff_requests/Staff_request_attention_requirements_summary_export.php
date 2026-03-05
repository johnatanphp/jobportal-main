<?php

class Staff_request_attention_requirements_summary_export
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
        
        $this->db->from('tbl_job_charges');
        $this->db->where('country_id', 56);
        $this->db->order_by('ID', 'ASC');
        $this->job_charges = $this->db->get()->result();
    }

    public function init()
    {
        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);

        $sheet = $this->spreadsheet->getActiveSheet();
        
        //Dar estilo primera fila
        $sheet->setCellValue('F1', 'GRUPO OCUPACIONAL');

        $style_title = [
			'font' => [
				'bold'  => true,
				'color' => ['rgb' => 'ffffff'],
				'size' => 9
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'rgb' => '06357D'
				]
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			]
		];

        $range_cell = $this->get_range_letter_cell(1, 6, 6 + count($this->job_charges) - 1);
        $sheet->getStyle($range_cell)->applyFromArray($style_title);
        $sheet->mergeCells($range_cell);

        //Dar estilo segunda fila
		$style_title = [
			'font' => [
				'bold'  => true,
				'color' => ['rgb' => 'ffffff'],
				'size' => 9
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'rgb' => '06357D'
				]
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			]
		];

		$range_cell = $this->get_range_letter_cell(2, 1, 30);
		$sheet->getStyle($range_cell)->applyFromArray($style_title);
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

    private function data()
    {
        $filter = $this->filters;

        $user_id = $filter['user_id'];
        $business_unit_codes = $this->Employer_staff_request_manage_business_unit->get_business_units_by_user_id($user_id);

        $this->db->select([
            'request.creation_date',
            'request.business_unit_name',
            'request.cod_business_unit',
            'SUM(request.vacancies) AS total_vacancies',
            'COUNT(request.ID) AS total_requests'
        ]);
    	
        $this->db->from('tbl_staff_requests request');
        $this->db->where('request.company_ID', $filter['company_id']);
        $this->db->where('request.creation_date>=', $filter['year'] . '-01-01 00:00:00');
        $this->db->where('request.creation_date<=', $filter['year'] . '-12-31 23:59:59');
        $this->db->where('request.request_model_id', $filter['request_model_id']);
        $this->db->where_in('request.cod_business_unit', $business_unit_codes);
        $this->db->group_by(['MONTH(request.creation_date)', 'request.cod_business_unit']);

    	return $this->db->get()->result();
    }

    private function build_header()
    {
        $header[] = 'MES';
        $header[] = 'UNIDAD DE NEGOCIO';
        $header[] = 'REQUERIMIENTOS';
        $header[] = 'VACANTES';
        $header[] = 'TOTAL DE CONTRATADOS AL MES';
    
        foreach ($this->job_charges as $charge) {
            $header[] = mb_strtoupper($charge->charge_name);
        }

        $header[] = 'CANCELADOS';
      
        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A2'
        );
    }

    public function build_data()
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $requests = $this->data();

        foreach ($requests as $index => $row) {
            $col = [];
            $col[] = mb_strtoupper(_date_locale_format(strtotime($row->creation_date), 'MMMM'));
            $col[] = $row->business_unit_name;
            $col[] = $row->total_requests;
            $col[] = $row->total_vacancies;
            $col[] = (string)$this->get_total_hired_per_month($row->creation_date, $row->cod_business_unit);   

            $request_ids = $this->get_request_ids($row->creation_date, $row->cod_business_unit);

            foreach ($this->job_charges as $charge) {
                $col[] = (string)$this->get_total_request_job_charges($charge->ID, $request_ids);
            }
    
            $col[] = (string)$this->get_total_request_canceled($request_ids);
            
            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 3)
            );
        }

        return true;
    }

    private function get_total_request_hired($request_ids)
    {
        $this->db->select([
            'rc.id'
        ]);
        
        $this->db->from('tbl_recruitment_contracts rc');
        $this->db->join('tbl_post_jobs jobs', 'rc.job_id=jobs.ID');
        $this->db->join('tbl_staff_requests s', 's.ID=jobs.request_ID');
        $this->db->where_in('s.ID', $request_ids);
        $this->db->where('rc.hired', 1);
        
        return $this->db->count_all_results();
    }

    private function get_request_ids($date, $business_unit_code)
    {
        $filter = $this->filters;

        $d = new DateTime($date);
        $d->modify('first day of this month');
        $date_from = $d->format('Y-m-d 00:00:00');

        $d->modify('last day of this month');
        $date_end = $d->format('Y-m-d 23:59:59');

        $this->db->select([
            's.ID as id'
        ]);
        
        $this->db->from('tbl_staff_requests s');
        $this->db->where('s.company_ID', $filter['company_id']);
        $this->db->where('s.request_model_id', $filter['request_model_id']);
        $this->db->where('s.cod_business_unit', $business_unit_code);
        $this->db->where('s.creation_date>=', $date_from);
        $this->db->where('s.creation_date<=', $date_end);
        
        $results = $this->db->get()->result();

        $request_ids = [];
        foreach ($results as $row) {
            $request_ids[] = $row->id;
        }

        return $request_ids;
    }

    private function get_total_hired_per_month($date, $business_unit_code)
    {
        $filter = $this->filters;

        $d = new DateTime($date);
        $d->modify('first day of this month');
        $date_from = $d->format('Y-m-d 00:00:00');

        $d->modify('last day of this month');
        $date_end = $d->format('Y-m-d 23:59:59');

        $this->db->select([
            'rc.id'
        ]);
        
        $this->db->from('tbl_recruitment_contracts rc');
        $this->db->join('tbl_post_jobs jobs', 'rc.job_id=jobs.ID');
        $this->db->join('tbl_staff_requests s', 's.ID=jobs.request_ID');
        $this->db->where('s.request_model_id', $filter['request_model_id']);
        $this->db->where('s.company_ID', $filter['company_id']);
        $this->db->where('s.cod_business_unit', $business_unit_code);
        $this->db->where('rc.hired', 1);
        $this->db->where('rc.hired_at>=', $date_from);
        $this->db->where('rc.hired_at<=', $date_end);
        
        return $this->db->count_all_results();
    }

    private function get_total_request_canceled($request_ids)
    {
        $this->db->select([
            'SUM(r.vacancies) AS total'
        ]);
        $this->db->from('tbl_staff_requests r');
        $this->db->where_in('r.ID', $request_ids);
        $this->db->where('(r.sts_process = "rejected" OR r.sts_process = "canceled")');

        $row_total = $this->db->get()->row();

        return $row_total->total ? $row_total->total : '0';
    }

    private function get_total_request_job_charges($job_charge_id, $request_ids)
    {
        $filter = $this->filters;
        $mof_charge_total = null;
        $jp_charge_total = null;
        $jl_charge_total = null;

        if ($filter['request_model_id'] == 1) {
            $this->db->select([
                'SUM(r.vacancies) AS total'
            ]);
            
            $this->db->from('tbl_mofs mof');
            $this->db->join('tbl_staff_requests r', 'r.mof_ID=mof.ID');
            $this->db->where('mof.job_charge_id', $job_charge_id);
            $this->db->where_in('r.ID', $request_ids);
        
            $mof_charge_total = $this->db->get()->row();
        }

        if ($filter['request_model_id'] == 2 || $filter['request_model_id'] == 3) {
            $this->db->select([
                'SUM(r.vacancies) AS total'
            ]);
            
            $this->db->from('tbl_job_profiles jp');
            $this->db->join('tbl_staff_requests r', 'r.job_profile_ID=jp.ID');
            $this->db->where('jp.job_charge_ID', $job_charge_id);
            $this->db->where_in('r.ID', $request_ids);
        
            $jp_charge_total = $this->db->get()->row();
        }

        $this->db->select([
            'SUM(r.vacancies) AS total'
        ]);
        $this->db->from('tbl_job_layouts jl');
        $this->db->join('tbl_staff_requests r', 'r.job_layout_id=jl.id');
        $this->db->where('jl.job_charge_id', $job_charge_id);
        $this->db->where_in('r.ID', $request_ids);
      
        $jl_charge_total = $this->db->get()->row();

        return ($jp_charge_total && isset($jp_charge_total->total) ? (int)$jp_charge_total->total : 0) + 
               ($jl_charge_total && isset($jl_charge_total->total) ? (int)$jl_charge_total->total : 0) + 
               ($mof_charge_total && isset($mof_charge_total->total) ? (int)$mof_charge_total->total : 0);
    }

    private function get_letter_cell($row, $col)
	{
		$letter_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);

		return $letter_col . $row;
	}

    private function get_range_letter_cell($row, $start_col, $end_col)
	{
		return $this->get_letter_cell($row, $start_col) . ':' . $this->get_letter_cell($row, $end_col); 
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
}
