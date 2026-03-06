<?php  if ( ! defined('BASEPATH')) exit ('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;

class Report_excel_lib
{
    private $spreadsheet = null;

    public function __construct()
    {
        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);

        $sheet = $this->spreadsheet->getActiveSheet();
        
        $style_title = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => '000000'],
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];
    
        $sheet->getStyle('A1:BZ1')->applyFromArray($style_title);

    }

    public function build(&$data)
    {
        $worksheet = $this->spreadsheet->getActiveSheet();

        $worksheet->fromArray(
            $data,
            null,
            'A1'
        );

        return $this;
    }

    public function download($filename = 'reporte-excel')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
