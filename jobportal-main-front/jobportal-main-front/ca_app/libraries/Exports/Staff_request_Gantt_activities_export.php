<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Staff_request_Gantt_activities_export
{
	private $spreadsheet = null;

	private $lowest_date = null;

	private $highest_date = null;
	
	private $ci = null;

	public function __construct()
	{
		$this->ci = & get_instance();
		$this->ci->load->model('Staff_request');
		$this->ci->load->model('Gantt_type');
		$this->ci->load->model('Staff_request_gantt_activity');

		$this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$this->spreadsheet->setActiveSheetIndex(0);
		$this->spreadsheet->getActiveSheet()->setTitle('GANTT DE ACTIVIDADES RYS');
	}

	public function build_gantt($gantt_id)
	{
		$this->data_gantt = $this->ci->Staff_request_gantt->find($gantt_id);
		$this->gantt_type = $this->ci->Gantt_type->find($this->data_gantt->type_id);
		$this->data_gantt_chart = $this->ci->Staff_request_gantt_activity->get_gantt_activities($gantt_id);
		$this->lowest_date = $this->ci->Staff_request_gantt_activity->get_start_date_gantt($gantt_id);
		$this->highest_date = $this->ci->Staff_request_gantt_activity->get_end_date_gantt($gantt_id);
		
		$this->create_banner();

		$this->create_data_request();

		$this->create_header_months();
	
		$this->create_table_gantt();	
	}

	private function create_data_request()
	{	
		$staff_request = $this->ci->Staff_request->find($this->data_gantt->request_ID);

		$sheet = $this->spreadsheet->getActiveSheet();
		$sheet->setCellValue('A5', ' SOLICITUD CODIGO: ' . $staff_request->ID);
		$sheet->setCellValue('A6', ' SOLICITUD NOMBRE: ' . $staff_request->job_title);
		$sheet->setCellValue('A7', ' TIPO GANTT: ' . $this->gantt_type->name);

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

		$range_cell = $this->get_range_letter_cell(5, 1, 2);
		$sheet->getStyle($range_cell)->applyFromArray($style_title);
		$sheet->mergeCells($range_cell);

		$range_cell = $this->get_range_letter_cell(6, 1, 2);
		$sheet->getStyle($range_cell)->applyFromArray($style_title);
		$sheet->mergeCells($range_cell);
		
		$range_cell = $this->get_range_letter_cell(7, 1, 2);
		$sheet->getStyle($range_cell)->applyFromArray($style_title);
		$sheet->mergeCells($range_cell);
	}

	private function create_banner()
	{
		$total_col_days =  $this->get_diff_days($this->lowest_date, $this->highest_date) + 1;

		$sheet = $this->spreadsheet->getActiveSheet();

		$obj_drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

		$obj_drawing->setPath(FCPATH . '/public/images/overall_blue.png');
		$obj_drawing->setCoordinates('A1');
		$obj_drawing->setHeight(40);
		$obj_drawing->setOffsetX(5);
		$obj_drawing->setOffsetY(5);
		$obj_drawing->setWorksheet($sheet);
		
		$sheet->setCellValue('A4', 'GANTT DE ACTIVIDADES RYS');
		
		$style_title = [
			// 'borders' => [
			// 	'outline' => [
			// 		'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			// 		'color' => [
			// 			'rgb' => '000000'
			// 		],
			// 	]
			// ],
			'font' => [
				'bold'  => true,
				'color' => ['rgb' => 'ffffff'],
				'size' => 13
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

		$range_cell = $this->get_range_letter_cell(4, 1, 9);
	
		$sheet->getStyle($range_cell)->applyFromArray($style_title);
		$sheet->mergeCells($range_cell);
	}

	public function create_table_gantt()
	{
		$sheet = $this->spreadsheet->getActiveSheet();
		
		$total_col_days =  $this->get_diff_days($this->lowest_date, $this->highest_date) + 1;

		$this->create_header_table_gantt();

		$row = 11;
		$n_task = 1;
		
		foreach ($this->data_gantt_chart as $row_data) {
			
			$activity_name = $row_data->activity_name;

			if($activity_name == 'OTROS') {
				$activity_name = $row_data->other_activity;
			}
			
			$start_date = $row_data->start_date;
			$end_date = $row_data->end_date;

			$sheet->setCellValue([1, $row], ($n_task++));
			$sheet->setCellValue([2, $row], $activity_name);
			$sheet->setCellValue([3, $row], _date_locale_format(strtotime($start_date), 'dd/MM/y'));
			$sheet->setCellValue([4, $row], _date_locale_format(strtotime($end_date), 'dd/MM/y'));

			$style_data = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						'color' => [
							'rgb' => '000000'
						],
					]
				],
				
				'font' => [
					'bold'  => true,
					'color' => ['rgb' => '111111'],
					'size' => 10
				],
			];

			$range_cell = $this->get_range_letter_cell($row, 1, $total_col_days + 4);
	
			$sheet->getStyle($range_cell)->applyFromArray($style_data);

			$this->select_date_range($row, $start_date, $end_date);
			
			$row++;
		}
	}

	private function create_header_table_gantt()
	{
		$start_date_task_time = new DateTime($this->lowest_date);

		$total_col_days =  $this->get_diff_days($this->lowest_date, $this->highest_date) + 1;

		$sheet = $this->spreadsheet->getActiveSheet();
		$row_start = 10;

		$sheet->setCellValue([1, $row_start], 'N°');
		$sheet->setCellValue([2, $row_start], 'Actividad');
		$sheet->setCellValue([3, $row_start], 'Fecha de inicio');
		$sheet->setCellValue([4, $row_start], 'Fecha final');
		
		$sheet->getColumnDimensionByColumn(1)->setWidth(4);
		$sheet->getColumnDimensionByColumn(2)->setAutoSize(true);
		$sheet->getColumnDimensionByColumn(3)->setAutoSize(true);
		$sheet->getColumnDimensionByColumn(4)->setAutoSize(true);
		
		for ($i = 4; $i < $total_col_days + 4; $i++) {
			$sheet->setCellValue([$i + 1, $row_start], $start_date_task_time->format('d'));
			$start_date_task_time->add(new DateInterval('P1D'));
		}

		$style_header = [
			
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => [
						'rgb' => '000000'
					],
				]
			],
			'font' => [
				'bold'  => true,
				'color' => ['rgb' => 'ffffff'],
				'size' => 10
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

		$range_cell = $range_cell = $this->get_range_letter_cell($row_start, 1, $total_col_days + 4);
	
		$sheet->getStyle($range_cell)->applyFromArray($style_header);
	}

	private function create_header_months()
	{
		$sheet = $this->spreadsheet->getActiveSheet();

		$start_date = new DateTime($this->lowest_date);
		$end_date = new DateTime($this->highest_date);

		$start_date_month = (new DateTime($this->lowest_date))->modify('first day of this month');
		$end_date_month = (new DateTime($this->highest_date))->modify('first day of this month');
		$diff_month = $start_date_month->diff($end_date_month)->format('%M') + 1;
	
		$start_index_col = 4;
		$start_index_row = 8;

		for ($i = 0; $i < $diff_month; $i++) { 
			
			$end_date_temp = new DateTime($start_date->format('Y-m-d'));
			$end_date_temp->modify('last day of this month');

			if ($end_date_temp > $end_date) {
				$end_date_temp = $end_date;
			}

			$diff_days = $this->get_diff_days($start_date->format('Y-m-d'), $end_date_temp->format('Y-m-d'));	

			$sheet->setCellValue([
					$start_index_col + 1, 
					$start_index_row
				], 
				ucwords(_date_locale_format(strtotime($start_date->format('Y-m-d')), 'MMMM y'))
			);
			
			$range_cell = $this->get_range_letter_cell($start_index_row, $start_index_col + 1, $start_index_col + $diff_days + 1);

			$style_data = [
				'borders' => [
					'allBorders' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
						'color' => [
							'rgb' => '000000'
						],
					]
				],
				'font' => [
					'bold'  => true,
					'color' => ['rgb' => 'ffffff'],
					'size' => 10
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
			
			$sheet->getStyle($range_cell)->applyFromArray($style_data);			
			
			$sheet->mergeCells($range_cell);

			$start_index_col = ($start_index_col + $diff_days) + 1;

			$start_date = $end_date_temp;
			
			$start_date->add(new DateInterval('P1D'));
		}
	}

	private function select_date_range($row_index, $start_date, $end_date)
	{
		$start_index_col = $this->get_diff_days($this->lowest_date, $start_date) + 1;
		$end_index_col = $this->get_diff_days($start_date, $end_date);
		
		$last_index_col = $start_index_col + $end_index_col;

		$range_cell = $this->get_range_letter_cell($row_index, $start_index_col + 4, $last_index_col + 4);

		$style_cell = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'rgb' => '2E86C1'
				]
        	]
        ];

		$this->spreadsheet->getActiveSheet()->getStyle($range_cell)->applyFromArray($style_cell);

		if (!$this->data_gantt->ignore_weekend) {
			return;
		}

		$start_index = $this->get_diff_days($start_date, $end_date) + 1;
		$datetime_tmp = new DateTime($start_date);

		for ($col_index = 1; $col_index <= $start_index; $col_index++) {
			
			$format_day = $datetime_tmp->format('l');
			
			if ($format_day == 'Saturday' || $format_day == 'Sunday') {
			
				$style_cell = [
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'startColor' => [
							'rgb' => 'FFFFFF'
						]
					]
				];

				$range_cell = $this->get_range_letter_cell($row_index, $start_index_col + $col_index + 3, $start_index_col + $col_index + 3);
				$this->spreadsheet->getActiveSheet()->getStyle($range_cell)->applyFromArray($style_cell);
			}

			$datetime_tmp->add(new DateInterval('P1D'));			
		}
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

	private function get_diff_days($start_date, $end_date)
	{
		$datetime1 = new DateTime($start_date);
		$datetime2 = new DateTime($end_date);

		$interval = $datetime1->diff($datetime2);

		return $interval->format('%a');
	}

	public function download($filename = 'gantt-de-actividades-RYS')
	{
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$obj_writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
		$obj_writer->save('php://output');
	}

	public function getOutput()
	{
		ob_start();
		$obj_writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
		$obj_writer->save('php://output');
		$result_object = ob_get_clean();
		return $result_object;
	}
}
