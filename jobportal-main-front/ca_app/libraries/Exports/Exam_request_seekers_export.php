<?php

class Exam_request_seekers_export
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
        $this->load->model('Exam_document');
        
        $this->spreadsheet = null;

        if (is_null($filters) === false) {
            $this->build($filters);
        }
    }

    public function init()
    {
        $this->exam_type = $this->Exam_document->find($this->filters['exam_type']);
        
        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);

        $sheet = $this->spreadsheet->getActiveSheet();

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(FCPATH . 'public/images/overall_white.png');
        $drawing->setHeight(40);
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        $drawing->setCoordinates('A1');

        $style_title = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'ffffff'],
                'size' => 15
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
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('E1:Z1');
     
        $style_title = [
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'ffffff'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '002060'
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ];
    
        $sheet->getStyle('A3:BZ3')->applyFromArray($style_title);

        if (!isset($this->filters['export_type']) || $this->filters['export_type'] == '1') {
            $sheet->setCellValue('E1', 'SOLICITUD PARA LA TOMA DE ' . $this->exam_type->description);
        }

        if (isset($this->filters['export_type']) && $this->filters['export_type'] == '2') {
            $sheet->setCellValue('E1', 'CANCELACIÓN PARA LA TOMA DE ' . $this->exam_type->description);
        }
    }

    public function build($filters = [])
    {
        $this->filters = $filters;

        $this->init();
        $this->build_header();
        $this->build_data();

        return $this;
    }

    private function data()
    {
        $this->db->select([
            'seeker.document_number',
            'seeker.document_type',
            'seeker.first_name',
            'seeker.paternal_last_name',
            'seeker.maternal_last_name',
            'seeker.dob',
            'job.job_title',
            'staff_request.consultant_name',
            'staff_request.client_company_name',
            'staff_request.cost_center',
            'staff_request.type_expense',
            'staff_request.eecc_code',
            'staff_request.eecc_description',
            'request_seeker.exam_date',
            'request_seeker.exam_doc_type',
            'request_seeker.protocol_extra',
            'medical_center.name AS medical_center_name',
            'medical_center.city AS medical_center_city',
            'medical_center_location.ubication AS medical_center_location',
            'request_seeker.medical_center_location_name',
            'result_types.result_name'        
        ]);

        $this->db->from('tbl_exam_request_seekers request_seeker');
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID=request_seeker.seeker_id');
        $this->db->join(
            'tbl_post_jobs job', 
            'request_seeker.job_id=job.ID'
        );
        
        $this->db->join(
            'tbl_medical_centers medical_center', 
            'request_seeker.medical_center_code=medical_center.code', 
            'left'
        );

        $this->db->join(
            'tbl_medical_center_locations medical_center_location', 
            'request_seeker.medical_center_location_id=medical_center_location.id', 
            'left'
        );

        $this->db->join(
            'tbl_exam_request_results results', 
            'results.exam_request_seeker_id=request_seeker.id AND results.exam_type_id=' . $this->filters['exam_type'] . ' AND results.document_type IN("certificate_emo", "certificate_covid19")', 
            'left'
        );

        $this->db->join('tbl_exam_request_result_types result_types', 
            'result_types.id=results.result_status', 
            'left'
        );

        $this->db->join(
            'tbl_staff_requests staff_request', 
            'job.request_ID=staff_request.ID', 
            'left'
        );

        $this->db->where('request_seeker.exam_type_id', $this->filters['exam_type']);
        $this->db->where('request_seeker.active', 1);
        
        if (isset($this->filters['start_date']) && 
            $this->filters['start_date'] && 
            isset($this->filters['end_date']) && 
            $this->filters['end_date']) {
            $this->db->where('request_seeker.exam_date>=', $this->filters['start_date'] . ' 00:00:00');
            $this->db->where('request_seeker.exam_date<=', $this->filters['end_date'] . ' 23:59:59');
        }

        if (isset($this->filters['request_seeker_id'])) {
            $this->db->where('request_seeker.id', $this->filters['request_seeker_id']);
        }

        return $this->db->get()->result();
    }

    private function build_header()
    {
        $header[] = 'N°';
        $header[] = 'APELLIDO PATERNO';
        $header[] = 'APELLIDO MATERNO';
        $header[] = 'NOMBRES';
        $header[] = 'DNI';
        $header[] = 'FECHA DE NACIMIENTO';
        $header[] = 'CARGO / PUESTO DE TRABAJO';
        $header[] = 'EMPRESA / CONSULTORA';
        $header[] = 'CLIENTE / INTERNO';
        $header[] = 'CENTRO DE COSTO';
        $header[] = 'TIPO EGRESO';
        $header[] = 'EECC';

        if ($this->filters['exam_type'] == 1) {
            $header[] = 'TIPO DE EXAMEN MÉDICO OCUP.';
            $header[] = 'PROTOCOLO DE EMO';
        }

        if ($this->filters['exam_type'] == 2) {
            $header[] = 'EXAMEN COVID-19';
        } 

        if ($this->filters['exam_type'] == 3) {
            $header[] = 'TIPO SCREENING';
        } 

        $header[] = 'FECHA EXAMEN';
        $header[] = 'CLÍNICA';
        $header[] = 'SEDE (Ciudad)';
        $header[] = 'RESULTADO';

        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A3'
        );
    }

    public function build_data()
    {
        $seekers = $this->data();

        $sheet = $this->spreadsheet->getActiveSheet();

        foreach ($seekers as $index => $row) {
            
            $col = [];

            $col[] = $index + 1; //N°
            $col[] = mb_strtoupper($row->paternal_last_name); //Apellido Paterno
            $col[] = mb_strtoupper($row->maternal_last_name); //Apellido Materno
            $col[] =  mb_strtoupper($row->first_name); //Nombres
            $col[] = $row->document_number; //Doc Identidad
            $col[] = date('d/m/Y', strtotime($row->dob));//Fecha de nac
            $col[] = mb_strtoupper($row->job_title); //Cargo
            $col[] = mb_strtoupper($row->consultant_name); //Consultora
            $col[] = mb_strtoupper($row->client_company_name); //Cliente
            $col[] = $row->cost_center; //Centro Costo
            $col[] = $row->type_expense; //Tipo Egreso
            $col[] = $row->eecc_code . ' - ' . $row->eecc_description; //EECC

            //Doc EMO
            if ($this->filters['exam_type'] == 1) {
                //TIPO DE EXAMEN MÉDICO OCUP.
                $col[] = 'PRE - INGRESO'; 
                //PROTOCOLO DE EMO (Llenado por el área de SSO)
                $col[] = $row->exam_doc_type . ' ' . $row->protocol_extra; 
            }

            //Doc COVID-19
            if ($this->filters['exam_type'] == 2) {
                //$col[] = $this->get_covid19_types($row->exam_doc_type);
                $col[] = $row->exam_doc_type; 
            } 

            //Doc Screening
            if ($this->filters['exam_type'] == 3) {
                $col[] = $row->exam_doc_type; 
            }

            $col[] = date('d/m/Y', strtotime($row->exam_date)); //Fecha examen
            $col[] = $row->medical_center_name; //Centro medico
            $col[] = $row->medical_center_location ? $row->medical_center_location : $row->medical_center_location_name; //Sede
            $col[] = $row->result_name; //Resultado

            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 4)
            );

            $this->spreadsheet->getActiveSheet()
                ->getCell('E' . ($index + 4))
                ->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }
    }

    private function get_covid19_types($covid19_types)
    {
        $covid_array = get_options_exam_type_covid();
        $covid19_values = explode(',', $covid19_types);
        $types = [];

        foreach ($covid19_values as $val) {
            $val = trim($val);

            if (isset($covid_array[$val])) {
                $types[] = $covid_array[$val];
            }
        } 

        return join(',', $types);
    }

    public function download($filename = 'reporte-excel')
    {
        $exam_type_name = $this->exam_type->name;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $exam_type_name .'.xlsx"');
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
