<?php

class Report_periods_export
{
	private $spreadsheet;
    private $request = null;
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
        
        $sheet->getStyle('A1:BZ1')->applyFromArray($style_title);
    }
    
    public function build($filters = [])
    {
    	$this->filters = $filters;

        $this->init();
    	$this->build_header();
    	$this->build_data();

    	return $this;
    }

    private function build_header()
    {
    	$header[] = 'Periodo';
        $header[] = 'Consultora';
        $header[] = 'Porcentaje Trabajadores';
        $header[] = 'Total Trabajadores';
        $header[] = 'Trabajadores Nacionales';
        $header[] = 'Trabajadores Extranjeros';
        $header[] = 'Trabajadores Extranjeros por Contratar';

        $header[] = 'Porcentaje Remuneración';
        $header[] = 'Total remuneraciones'; 
        $header[] = 'Remuneraciones trabajadores nacionales';
        $header[] = 'Remuneraciones trabajadores extranjeros';
        $header[] = 'Remuneraciones trabajadores extranjeros por contratar';

        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }

    public function build_data()
    {
        $index = 0;
		$sheet = $this->spreadsheet->getActiveSheet();

        $no_cia = $this->filters['no_cia'];
        $period_year = $this->filters['period_year'];
        $period_month = $this->filters['period_month'];

        $totals = [];

        try {
            $this->load->library(
                'Recruitment/Recruitment_seeker_hire_lib', 
                null, 
                'Recruitment_seeker_hire_lib'
            );
            $totals = $this->Recruitment_seeker_hire_lib->calculate_foreign_totals($period_year, $period_month, $no_cia);
        } catch (\Exception $e) {}
    
        $col = [];
        $col[] = $period_year . $period_month;
        $col[] = $this->get_consultant($no_cia);
        $col[] = isset($totals['total_employees_foreign_percentage']) ? $totals['total_employees_foreign_percentage'] : '';
        $col[] = isset($totals['total_employees']) ? $totals['total_employees'] : '';
        $col[] = isset($totals['total_employees_national']) ? $totals['total_employees_national'] : '';
        $col[] = isset($totals['total_employees_foreign']) ? $totals['total_employees_foreign'] : '';
        $col[] = isset($totals['total_employees_foreign_max']) ? $totals['total_employees_foreign_max'] : '';
        $col[] = isset($totals['total_salary_foreign_percentage']) ? $totals['total_salary_foreign_percentage'] : '';
        $col[] = isset($totals['total_salary']) ? $totals['total_salary'] : '';
        $col[] = isset($totals['total_salary_national']) ? $totals['total_salary_national'] : '';
        $col[] = isset($totals['total_salary_foreign']) ? $totals['total_salary_foreign'] : '';
        $col[] = isset($totals['total_salary_foreign_max']) ? $totals['total_salary_foreign_max'] : '';

        $this->spreadsheet->getActiveSheet()->fromArray(
            $col,
            null,
            'A' . ($index + 2)
        );

        $this->spreadsheet->getActiveSheet()
            ->getCell('E' . ($index + 4))
            ->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    }

    public function download($filename = 'reporte-excel')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
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

    private function get_consultant($no_cia)
    {
        $cias = [
            '01' => 'OVERALL BUSINESS S.A.',
            '02' => 'OVERALL STRATEGY S.A.C.',
            '03' => 'EXECUTIVE SOLUTIONS S.A.',
            '04' => 'BUSINESS CONSULTANTS S.A.',
            '07' =>	'OVERALL SPORTS S.A.',
            '16' =>	'MARKETING POWER S.A.C.',
            '17' =>	'TRADE DEVELOPMENT S.A.C.',
            '22' => 'OVERALL ORIENTE S.A.C.',
            '48' => 'QUALA PERU SAC',
            '54' => 'SUPPLY & OPERATIONS SAC',
            '56' => 'APOYO MATERIAL Y LOGISTICO S.A.C.'
        ];

        return isset($cias[$no_cia]) ? $cias[$no_cia] : $no_cia; 
    }
}
