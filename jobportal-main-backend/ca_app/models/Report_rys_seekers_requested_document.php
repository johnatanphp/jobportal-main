<?php

class Report_rys_seekers_requested_document extends CI_Model
{
	private $spreadsheet;
	private $sheet;

    public function __construct()
    {
    	$this->load->model('Recruitment_attached_document');
    	$this->load->model('Requested_document');
    	$this->load->model('Jobseeker_form_rtps');
    	$this->load->model('Jobseeker_required_document');

        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);

        $this->sheet = $this->spreadsheet->getActiveSheet();
        
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
    
        $this->sheet->getStyle('A1:BZ1')->applyFromArray($style_title);
    }

    public function build($filters = [])
    {
    	$this->filters = $filters;

    	$this->build_header();
    	$this->build_data();

    	return $this;
    }

    private function build_header()
    {
    	$header[] = 'RAZON SOCIAL';
        $header[] = 'TIPO';
        $header[] = 'AREA';
        $header[] = 'APELLIDOS Y NOMBRES';
        $header[] = 'TIPO DE DOCUMENTO';
        $header[] = 'N° DOCUMENTO';
        $header[] = 'FECHA DE INGRESO';
        $header[] = 'VIGENCIA DEL CONTRATO';
        $header[] = 'CARGO';
        $header[] = 'SUELDO';
        $header[] = 'MOTIVO DE CONTRATACIÓN';
		$header[] = 'Solicitud del Cliente';
		$header[] = 'RH-FO-004 Requerimiento de Personal';
		$header[] = 'Currículum Vitae';
		$header[] = 'Informe Psicolaboral (Staff)';
		$header[] = 'RH-FO-007 Informe por Competencias';
		$header[] = 'RH-FO-008 Verificación Laboral';
		$header[] = 'Screening (en caso aplique)';
		$header[] = 'Verificación Domiciliaria (en caso aplique)';
		$header[] = 'Certificado de Aptitud (EMO)';
		$header[] = 'Copia Documento de identidad (DNI, Carnet de extranjería, PTP, otros)';
		$header[] = 'Fotos';
		$header[] = 'GP-FO-004 Ficha de datos del trabajador RTPS';
		$header[] = 'RH-FO-005 Declaración Jurada Domicilio';
		$header[] = 'RH-FO-006 Declaración Jurada de 5ta categoría (en caso aplique)';
		$header[] = 'Certificado 5ta Categoría (en caso aplique)';
		$header[] = 'Copia DNI Cónyuge (en caso aplique)';
		$header[] = 'Certificado de Estudios (en caso aplique)';
		$header[] = 'Copia DNI Hijos Menores de Edad (en caso aplique)';
		$header[] = 'Recibo de Agua o Luz o Teléfono (en caso aplique)';
		$header[] = 'Antecedentes policiales (Caso aplique) o OCN Interpol Lima (Caso aplique)';
		$header[] = 'Certificados de trabajo (en caso aplique)';

        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }

    public function build_data()
    {
		$sheet = $this->spreadsheet->getActiveSheet();

    	$this->db->select([
    		'seeker.ID AS seeker_id',
    		'seeker.first_name',
    		'seeker.last_name',
    		'seeker.document_type',
    		'seeker.document_number',
    		'request.consultant_name',
    		'job.ID AS job_id',
    		'job.job_title',
    		'job.request_ID AS request_id',
    		'request.contract_time_qty',
    		'request.contract_time_duration',
    		'request.salary_range',
    		'request.reason_request',
    		'request.start_date_work',
    		'request.industry_ID AS industry_id'
    	]);

    	$this->db->from('tbl_recruitment_candidates rys_seeker');
    	$this->db->join('tbl_job_seekers seeker', 'seeker.ID=rys_seeker.seeker_ID');
    	$this->db->join('tbl_post_jobs job', 'rys_seeker.job_ID=job.ID');
		$this->db->join('tbl_staff_requests request', 'job.request_ID=request.ID', 'left');
		
		$this->db->where('rys_seeker.job_ID', $this->filters['job_id']);

		if (isset($this->filters['seeker_id'])) {
			$this->db->where('rys_seeker.seeker_ID', $this->filters['seeker_id']);
		}

		$this->db->where('rys_seeker.stage', 7);

		$seekers = $this->db->get()->result();

		$i = 0;
		foreach ($seekers as $index => $row) {
			
			$col = [];

			$col[] =  $row->consultant_name; //Consultora
			$col[] =  ''; //Tipo
			$col[] =  $row->industry_id ? $this->Industry->get_industries_by_id($row->industry_id)->industry_name : ''; //Area
			$col[] =  mb_strtoupper($row->last_name . ' ' . $row->first_name);
			$col[] = document_type_abbr($row->document_type);
            $col[] = $row->document_number;
            $col[] = $row->start_date_work ? date('d/m/Y', strtotime($row->start_date_work)) : '';
            $col[] = $row->contract_time_qty . ' ' . $row->contract_time_duration;
            $col[] = $row->job_title;
            $col[] = $row->salary_range;
            $col[] = reason_request_text($row->reason_request);
            
			$col[] = $row->request_id ? 'X' : 'NA';
			$col[] = $row->request_id ? 'X' : 'NA';
			$col[] = 'X';

			$document_psycholabor = $this->Recruitment_attached_document->get_files(
				$row->job_id,
				$row->seeker_id,
				'report_psycholabor'
			);
	
			$col[] = $document_psycholabor ? 'X' : 'NA';

			$document_competences = $this->Recruitment_attached_document->get_files(
				$row->job_id,
				$row->seeker_id,
				'report_competence'
			);
			
			$col[] = $document_competences ? 'X' : 'NA';

			$work_references = $this->Recruitment_attached_document->get_files(
				$row->job_id,
				$row->seeker_id,
				'work_reference'
			);
			
			$col[] = $work_references ? 'X' : 'NA';

			$screnning = $this->Recruitment_attached_document->get_files(
				$row->job_id,
				$row->seeker_id,
				'screnning'
			);
			
			$col[] = $screnning ? 'X' : 'NA';

			$home_verification = $this->Recruitment_attached_document->get_files(
				$row->job_id,
				$row->seeker_id,
				'home_verification'
			);

			$col[] = $home_verification ? 'X' : 'NA';

			$certificate_emo = $this->Recruitment_attached_document->get_files(
				$row->job_id,
				$row->seeker_id,
				'certificate_emo'
			);

			$col[] = $certificate_emo ? 'X' : 'NA';

			$identification_document = $this->Requested_document->get_identification_document(
				$row->seeker_id
			);

			$col[] = $identification_document ? 'X' : 'NA';

			$candidate_photo = $this->Requested_document->get_photo(
				$row->seeker_id
			);

			$col[] = $candidate_photo ? 'X' : 'NA';

			
			$form_rtps = $this->Jobseeker_form_rtps->get_form_rtps_by_jobseeker_id(
				$row->seeker_id
			);

			$col[] = $form_rtps ? 'X' : 'NA';
			
			
			$domicile_affidavit = $this->Jobseeker_required_document->get_domicile_affidavit(
				$row->seeker_id
			);
		
			$col[] = $domicile_affidavit ? 'X' : 'NA';

			$declaration_5th_category = $this->Jobseeker_required_document->get_declaration_5th_category(
				$row->seeker_id
			);

			$col[] = $declaration_5th_category ? 'X' : 'NA';

			$certificate_5th_category = $this->Jobseeker_required_document->get_certificate_5th_category(
				$row->seeker_id
			);
			
			$col[] = $certificate_5th_category ? 'X' : 'NA';

			$candidate_spouse = $this->Jobseeker_form_rtps->get_rightful_claimant_spouse_by_form_id(
				@$form_rtps->form_ID
			);

			$col[] = $candidate_spouse ? 'X' : 'NA';

			$candidate_studies = $this->Job_seeker->get_qualification_by_jobseeker_id(
				$row->seeker_id
			);

			$col[] = $candidate_studies ? 'X' : 'NA';

			$candidate_children = $this->Jobseeker_form_rtps->get_rightful_claimant_children_by_form_id(
				@$form_rtps->form_ID
			);

			$col[] = $candidate_children ? 'X' : 'NA';
			
			$receipt_service = $this->Requested_document->get_receipt_service(
				$row->seeker_id
			);

			$col[] = $receipt_service ? 'X' : 'NA';

			$police_records = $this->Jobseeker_required_document->get_police_records(
				$row->seeker_id
			);
			
			$col[] = $police_records ? 'X' : 'NA';

			$candidate_experiences = $this->Job_seeker->get_experience_by_jobseeker_id(
				$row->seeker_id
			);

			$col[] = $candidate_experiences ? 'X' : 'NA';

	        $this->spreadsheet->getActiveSheet()->fromArray(
	            $col,
	            null,
	            'A' . ($index + 2)
	        );

			$this->spreadsheet->getActiveSheet()->getCell('F' . ($index + 2))->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		}
    }

    public function download($filename = 'reporte-excel')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
