<?php

class Job_layout_list_export
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
        if (is_null($filters) === false) {
            $this->build($filters);
        }
    }

    public function init($filters)
    {        
        $this->filters = $filters;

        $company_id = $this->filters['company_id'];
        $company = $this->Company->find($company_id);

        $country_id = $company->country_id;

        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $this->spreadsheet->getActiveSheet();

        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'bold'  => false,
                'color' => ['rgb' => '333333'],
                'size' => 9
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]
        ]);


        //Consultar tipos de educacion
        $this->db->from('tbl_qualifications');
        $this->db->where('val', 'Estudios_t3');
        $this->db->where('country_id', $country_id);
        $result = $this->db->get()->result();
        $qualification = [];

        foreach ($result as $row) {
            $qualification[$row->ID] = $row;
        }

        $this->qualification = $qualification;

        //Consultar experiencias
        $this->db->from('tbl_work_experiences');
        $this->db->where('country_id', $country_id);
        $result = $this->db->get()->result();
        $work_experiences = [];

        foreach ($result as $row) {
            $work_experiences[$row->code] = $row;
        }

        $this->work_experiences = $work_experiences;

        //Consultar cargos laborales
        $this->db->from('tbl_job_charges');
        $this->db->where('country_id', $country_id);
        $result = $this->db->get()->result();
        $job_charges = [];

        foreach ($result as $row) {
            $job_charges[$row->ID] = $row;
        }

        $this->job_charges = $job_charges;

        //Consultar beneficios tipos
        $this->benefits = [];

        //Consultar factores tipos
        $this->db->select([
            'id',
            'name',
            'automatic',
            'active',
            'CASE id
                WHEN 1 THEN "15%"
                WHEN 2 THEN "15%"
                WHEN 3 THEN "10%"
                WHEN 4 THEN "15%"
                WHEN 5 THEN "10%"
                WHEN 6 THEN "25%"
                WHEN 7 THEN "10%"
            END AS percentage'
        ], false);
        $this->db->from('tbl_factor_types');
        $this->db->where('active', 1);
        $this->db->order_by('id', 'ASC');

        $this->factors =  $this->db->get()->result();
    }

    public function build($filters = [])
    {
        $this->init($filters);

        $this->build_header();

        $this->build_data();
    }

    private function build_header()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        $start_col_dinamic = 6;

        //INICIO FILA 1
        $sheet->setCellValue($this->get_letter_cell(1, $start_col_dinamic), 'ESTRUCTURA SALARIAL');
        $sheet->setCellValue(
            $this->get_letter_cell(1, $start_col_dinamic + 6 + (count($this->benefits) * 2)), 
            'CALIFICACIONES O COMPETENCIAS'
        );
        $sheet->setCellValue(
            $this->get_letter_cell(1, $start_col_dinamic + 9 + (count($this->benefits) * 2)), 
            'ESFUERZOS'
        );

        $style_title = [
            'font' => [
				'bold'  => true,
				'color' => ['rgb' => '333333'],
				'size' => 9
			],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'cacaca'
                    ],
                ]
            ],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'rgb' => 'ededed'
				]
			],
			'alignment' => [
                'wrapText' => true,
			]
		];

        //ESTILOS FILA 1
        $range_cell = $this->get_range_letter_cell(1, $start_col_dinamic, $start_col_dinamic + 1 + (count($this->benefits) * 2));
        $sheet->getStyle($range_cell)->applyFromArray($style_title);
        $sheet->mergeCells($range_cell);

        $range_cell = $this->get_range_letter_cell(1, $start_col_dinamic + 6 + (count($this->benefits) * 2), $start_col_dinamic + 8 + (count($this->benefits) * 2));
        $sheet->getStyle($range_cell)->applyFromArray($style_title);
        $sheet->mergeCells($range_cell);

        $range_cell = $this->get_range_letter_cell(1, $start_col_dinamic + 9 + (count($this->benefits) * 2), $start_col_dinamic + 10 + (count($this->benefits) * 2));
        $sheet->getStyle($range_cell)->applyFromArray($style_title);
        $sheet->mergeCells($range_cell);

        $sheet->getRowDimension('1')->setRowHeight(35);
        // FIN HEADER FILA 1

        //INICIO HEADER FILA 2
        $sheet->setCellValue($this->get_letter_cell(2, $start_col_dinamic), 'BÁSICO');

        $benefit_index = 0;
        foreach ($this->benefits as $benefit_row) {
            $sheet->setCellValue($this->get_letter_cell(2, $start_col_dinamic + 2 + $benefit_index), mb_strtoupper($benefit_row->benefit_name));
            $benefit_index+=2;
        }

        $factor_index = 0;
        foreach ($this->factors as $factor_row) {
            $sheet->setCellValue($this->get_letter_cell(2, $start_col_dinamic + 6 + (count($this->benefits) * 2) + $factor_index), mb_strtoupper($factor_row->name));
            $factor_index++;
        }

        //ESTILOS FILA 2
        $style_title = [
            'font' => [
				'bold'  => true,
				'color' => ['rgb' => '333333'],
				'size' => 9
			],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'cacaca'
                    ],
                ]
            ],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'rgb' => 'ededed'
				]
			],
			'alignment' => [
                'wrapText' => true,
			]
		];

		$range_cell = $this->get_range_letter_cell(2, $start_col_dinamic, $start_col_dinamic + 1);
		$sheet->getStyle($range_cell)->applyFromArray($style_title);
		$sheet->mergeCells($range_cell);

        $pivot = 0;
        foreach ($this->benefits as $index => $benefit) {
            $range_cell = $this->get_range_letter_cell(2, $start_col_dinamic + 2 + $pivot, $start_col_dinamic + 2 + $pivot + 1);
            $pivot+=2;
            
            $sheet->getStyle($range_cell)->applyFromArray($style_title);
            $sheet->mergeCells($range_cell);
        }

        $col_index = $start_col_dinamic + 5 + (count($this->benefits) * 2);
        $range_cell = $this->get_range_letter_cell(2, $col_index + 1, $col_index + count($this->factors));
		$sheet->getStyle($range_cell)->applyFromArray($style_title);

        $sheet->getRowDimension('2')->setRowHeight(50);
        //FIN HEADER FILA 2

        //INICIO HEADER FILA 3
        $header = [];

        $header[] = 'ID';
        $header[] = 'NOMBRE DEL CARGO';
        
        $header[] = 'CODIGO';
        $header[] = 'CODIGO SUNAT';
        $header[] = 'ESTADO';
        
        $header[] = 'Máximo';
        $header[] = 'Mínimo';

        foreach ($this->benefits as $row) {
            $header[] = 'Máximo';
            $header[] = 'Mínimo';    
        }
        
        $header[] = 'Estereotipo';
        $header[] = 'Categoria ocupacional';
        $header[] = 'Nivel';
        $header[] = 'Factor diferenciador';
        
        foreach ($this->factors as $factor_row) {
            $header[] = $factor_row->percentage; 
        }

        $header[] = 'Valor de puesto';
        $header[] = 'Valoración';
        $header[] = 'Observaciones';
        $header[] = 'Comentarios';
        
        if (isset($this->filters['show_url_detail_pdf']) && $this->filters['show_url_detail_pdf']) {
            $header[] = 'Url Detalle Pdf';
        }

        if (isset($this->filters['show_url_detail_excel']) && $this->filters['show_url_detail_excel']) {
            $header[] = 'Url Detalle Excel';
        }
    
        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A3'
        );

        $style_title = [
            'font' => [
				'bold'  => true,
				'color' => ['rgb' => '333333'],
				'size' => 9
			],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => [
                        'rgb' => 'cacaca'
                    ],
                ]
            ],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'rgb' => 'ededed'
				]
			],
			'alignment' => [
                'wrapText' => true,
			]
		];
       
        //Fila 3
        $range_cell = $this->get_range_letter_cell(3, 1, 50);
		$sheet->getStyle($range_cell)->applyFromArray($style_title);
        $sheet->getRowDimension('3')->setRowHeight(25);
        //FIN HEADER FILA 3
    }

    private function build_data()
    {
        $start_col_dinamic = 6;

        $sheet = $this->spreadsheet->getActiveSheet();

        $result = $this->get_all();

        $jl_ids = [];

        foreach ($result as $jl_row) {
            $jl_ids[] = $jl_row->id;
        }

        $rows = [];

        $benefits_values = $this->get_benefits($jl_ids);

        $factor_values = $this->get_factor_valuations($jl_ids);

        //dd($factor_values);

        foreach ($result as $jl_row) {

            $row = [];

            $row[] = $jl_row->id;
            $row[] = $jl_row->job_title;
            $row[] = $jl_row->code;
            $row[] = $jl_row->sunat_code;
            $row[] = $jl_row->active ? 'ACTIVO' : 'INACTIVO';

            //Mostrar beneficios
            $row[] = $jl_row->basic_minimum != null ? $jl_row->basic_minimum : '-';
            $row[] = $jl_row->basic_maximum != null ? $jl_row->basic_maximum : '-';

            $benefits = isset($benefits_values[$jl_row->id]) ? $benefits_values[$jl_row->id] : [];

            foreach ($this->benefits as $benefit_list_row) {

                $benefit_row = $this->get_benefit_row($benefits, $benefit_list_row->benefit_id);
               
                $row[] = $benefit_row ? $benefit_row->minimum : '-';
                $row[] = $benefit_row ? $benefit_row->maximum : '-';
            }

            $row[] = $jl_row->stereotype;
            $row[] = $jl_row->occupational_category_name;
            $row[] = $jl_row->occupational_category_level;
            $row[] = $jl_row->factor_differentiating;

            //Factores
            $factors = isset($factor_values[$jl_row->id]) ? $factor_values[$jl_row->id] : [];
            
            $factor_total = 0;

            foreach ($this->factors as $factor_list_row) {
                $factor_row = $this->get_factor_row($jl_row, $factors, $factor_list_row->id);
                $factor_total+=$factor_row ? $factor_row['score'] : 0;

                $row[] = $factor_row ? $factor_row['score'] : '-';
            }
            
            $row[] = $factor_total;
            $row[] = $jl_row->occupational_exams_approved ? 'OK' : 'Pendiente';
            $row[] = '-';
            $row[] = '-';

            if (isset($this->filters['show_url_detail_pdf']) && $this->filters['show_url_detail_pdf']) {
                $row[] = site_url('admin/job_layouts/export?id='. $jl_row->id . '&format=pdf&resource=1&disability=1&valorization=1&salary_structure=1');
            }
    
            if (isset($this->filters['show_url_detail_excel']) && $this->filters['show_url_detail_excel']) {
                $row[] = site_url('admin/job_layouts/export?id='. $jl_row->id . '&format=excel&resource=1&disability=1&valorization=1&salary_structure=1');
            }

            $rows[] = $row;
        }

        $this->spreadsheet->getActiveSheet()->fromArray(
            $rows,
            null,
            'A4'
        );

        //ESTILOS BODY
        $sheet->getColumnDimension('B')->setWidth(50);

        $start_col = $start_col_dinamic + 1 + (count($this->benefits) * 2);

        for ($i = $start_col; $i <= ($start_col + count($this->factors) + 8); $i++) {
            $sheet->getColumnDimension(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i))->setWidth(15);
        }
       
        $sheet->getStyle('B4:B' . (count($result) + $start_col_dinamic + 10))->applyFromArray([
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			]
		]);
    }

    private function get_all()
    {        
        $this->db->select([
            'jl.id',
            'jl.job_title',
            'jl.code',
            'jl.sunat_code',
            'jl.study_grade_min',
            'jl.experience',
            'jl.job_charge_ID AS job_charge_id',
            'jl.basic_minimum',
            'jl.basic_maximum',
            'jl.stereotype',
            'jl.occupational_exams_approved',
            'jl.factor_differentiating',
            'oc.name AS occupational_category_name',
            'oc.level AS occupational_category_level',
            'jl.active'
        ]);
        $this->db->from('tbl_job_layouts jl');
        $this->db->join(
            'tbl_occupational_categories oc', 
            'oc.id=jl.occupational_category_id', 
            'left'
        );

        if (isset($this->filters['ids'])) {
            $this->db->where_in('jl.id', $this->filters['ids']);
        }
        
        if (isset($this->filters['company_id'])) {
            $this->db->where('jl.company_id', $this->filters['company_id']);
        }
        
        return $this->db->get()->result();
    }

    private function get_benefits($ids)
    {        
        if (count($ids) == 0) {
            $ids[] = '-1';
        }

        $this->db->select([
            'lb.ID AS benefit_id',
            'lb.benefit_name',
            'jllb.minimum',
            'jllb.maximum',
            'jllb.job_layout_id'
        ]);
        $this->db->from('tbl_laboral_benefits lb');
        $this->db->join(
            'tbl_job_layout_laboral_benefits jllb', 
            'lb.ID=jllb.benefit_id'
        );
        $this->db->where_in('jllb.job_layout_id', $ids);
        $this->db->where('lb.active', 1);
        $this->db->where('lb.company_id', 1);

        $this->db->order_by('jllb.benefit_id', 'ASC');
        
        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[$row->job_layout_id][] = $row;
        }

        return $data;
    }

    private function get_benefit_row($benefits, $id)
    {
        foreach ($benefits as $benefit_row) {
            if ($id == $benefit_row->benefit_id) {
                return $benefit_row;
            }   
        }        

        return null;
    }

    public function get_factor_valuations($ids)
    {
        if (count($ids) == 0) {
            $ids[] = '-1';
        }

        $data = [];

        $this->db->select([
            'fv.id',
            'fv.name',
            'fv.grade',
            'fv.score',
            'fv.level',
            'fv.factor_type_id',
            'jl_fv.factor_id AS factor_id',
            'jl_fv.job_layout_id'
        ]);
        $this->db->from('tbl_factor_valuations fv');
        $this->db->join('tbl_job_layout_factor_valuations jl_fv', 'fv.id=jl_fv.factor_id');
        $this->db->where_in('jl_fv.job_layout_id', $ids);

        $this->db->order_by('fv.id', 'ASC');

        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[$row->job_layout_id][] = $row;
        }   

        return $data;
    }

    public function get_factor_row($jl, $factors, $factor_id, $automatic = 0)
    {   
        $education = isset($this->qualification[$jl->study_grade_min]) ? $this->qualification[$jl->study_grade_min] : null;

        if ($factor_id == 1 && $education) {
            $data['name'] = $education->text;
            $data['grade'] = $education->valorization_grade;
            $data['score'] = $education->valorization_score;
            return $data;
        }

        $experience = isset($this->work_experiences[$jl->experience]) ? $this->work_experiences[$jl->experience] : null;

        if ($factor_id == 2 && $experience) {
            $data['name'] = $experience->name;
            $data['grade'] = $experience->valorization_grade;
            $data['score'] = $experience->valorization_score;
            return $data;
        }

        $job_charge = isset($this->job_charges[$jl->job_charge_id]) ? $this->job_charges[$jl->job_charge_id] : null;

        if ($factor_id == 3 && $job_charge) {
            $data['name'] =  'Habilidades';
            $data['grade'] = $job_charge->valorization_grade;
            $data['score'] = $job_charge->valorization_score;
            
            return $data;
        }

        foreach ($factors as $factor_row) {
            if ($factor_id == $factor_row->factor_type_id) {
                return [
                    'name' => $factor_row->name,
                    'grade' => $factor_row->grade,
                    'score' => $factor_row->score
                ];
            }   
        }       
        
        return null;
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

    public function download($filename = 'reporte-excel')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
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
        return $result_object;
    }

    public function save($path_file)
    {
        ob_start();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter(
            $this->spreadsheet, 
            'Xlsx'
        );
       
        $writer->save($path_file);
    }
}
