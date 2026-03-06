<?php

class Exam_request_candidates_status_export
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
    	$header[] = 'Nro';
        $header[] = 'DNI';
        $header[] = 'Apellido';
        $header[] = 'Nombre';
        $header[] = 'Ubigeo';
        $header[] = 'Fecha';
        $header[] = 'Hora';
        $header[] = 'Comentarios';
        $header[] = 'Centro Medico';
        $header[] = 'Sede';
        $header[] = 'Resultado';
        $header[] = 'Estado';

        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }

    public function build_data()
    {
		$sheet = $this->spreadsheet->getActiveSheet();
    
        $results = $this->get_data();

        foreach ($results as $index => $row) {
            $col = [];
            $col[] = $index + 1;
            $col[] = $row->candidate_document_number;
            $col[] = mb_strtoupper((string)$row->candidate_last_name);
            $col[] = mb_strtoupper((string)$row->candidate_first_name);
            $col[] = $row->exam_ubigeo;
            $col[] = $row->exam_date;
            $col[] = $row->exam_time;
            $col[] = $row->exam_comment;
            $col[] = $row->exam_medical_center_name;
            $col[] = $row->exam_medical_center_location_name;
            $col[] = trim((string)$row->exam_result_name) != '' ? $row->exam_result_name : '-';
            $col[] = mb_strtoupper((string)$row->exam_status_name);

            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );
        }

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

    public function get_data()
    {
        $request_id = $this->filters['request_id'];

        $this->db->select([
            'requests.ID AS request_id',
            'requests.job_title AS request_job_title',
            'requests.employer_ID AS request_employer_id',
            'requests.recruiter_ID AS request_recruiter_id',
            'seekers.document_number AS candidate_document_number',
            'seekers.first_name AS candidate_first_name',
            'seekers.last_name AS candidate_last_name',
            'er_seekers.exam_date AS exam_date',
            'er_seekers.exam_time AS exam_time',
            'er_seekers.ubigeo AS exam_ubigeo',
            'er_seekers.comment AS exam_comment',
            'er_seekers.medical_center_name AS exam_medical_center_name',
            'er_seekers.medical_center_location_name AS exam_medical_center_location_name',
            'er_status.name AS exam_status_name',
            'er_result_types.result_name AS exam_result_name'
        ]);
        $this->db->from('tbl_exam_request_seekers er_seekers');
        $this->db->join('tbl_job_seekers seekers', 'er_seekers.seeker_id=seekers.ID');
        $this->db->join('tbl_exam_request_status er_status', 'er_status.id=er_seekers.status');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=er_seekers.job_id');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=jobs.request_ID');
        $this->db->join('tbl_exam_request_results er_results', 'er_results.exam_request_seeker_id=er_seekers.id', 'left');
        $this->db->join('tbl_exam_request_result_types er_result_types', 'er_result_types.id=er_results.result_status', 'left');
        

        $this->db->where('requests.ID', $request_id);
        $this->db->where('er_seekers.notify_recruiter', 1);

        return $this->db->get()->result();
    }

    public function save($filepath = '')
    {
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, "Xlsx");
        $writer->save($filepath);

        return file_exists($filepath) ? $filepath : false;
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
