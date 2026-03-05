<?php

class Recruitment_candidate_fits_export
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
        //Load models
        $this->load->model('Recruitment_candidate_fits_tmp');

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
    	$header[] = 'DNI';
        $header[] = 'Apellido';
        $header[] = 'Nombre';
        $header[] = 'Email';
        $header[] = 'Canal';
        $header[] = 'Puesto';
        $header[] = 'Cliente';
        $header[] = 'Estado';

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

        $results = $this->data();
    
    
        
        foreach ($results as $row) {
            $col = [];

            $col[] = $row->document_number;
            $col[] = $row->last_name;
            $col[] = $row->first_name;
            $col[] = $row->email;
            $col[] = $row->recruitment_channel;
            $col[] = $row->job_title;
            $col[] = $row->company_account;
            $col[] = $row->is_fit ? 'APTO' : 'NO APTO';
            
            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );

            $index++;
        }
    }

    public function data()
    {
        $channel = $this->filters['channel'];
        $job_title = $this->filters['job_title'];
        $client = $this->filters['client'];

          //Crear tabla temporal de candidatos aptos
        $this->Recruitment_candidate_fits_tmp->create_table($this->filters);

        $this->db->select([
            'js.ID AS seeker_id',
            'js.first_name',
            'js.last_name',
            'js.email',
            'js.document_number',
            'se.recruitment_channel',
            'se.job_title',
            'se.company_account',
            'se_temp.is_fit'
        ]);
        $this->db->from('tbl_job_seekers js');
        $this->db->join('tbl_seeker_entries se', 'se.seeker_id=js.ID');
        $this->db->join('tbl_recruitment_candidate_fits_tmp se_temp', 'se_temp.seeker_id=se.seeker_id');

        return $this->db->get()->result();
    }

    public function download($filename = 'reporte-excel')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: atachment;filename="' . $filename . '.xlsx"');
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
}
