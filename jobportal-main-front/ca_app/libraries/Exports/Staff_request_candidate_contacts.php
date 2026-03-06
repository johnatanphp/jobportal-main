<?php

class Staff_request_candidate_contacts
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
        $this->filters = $filters;

        $this->init();
        $this->build_header();
        
        if ($this->build_data()) {
            return $this;
        }

        return false;
    }

    public function get_data_build($filters = [])
    {
        $this->filters = $filters;

        $requests = $this->data();

        $request_ids = [];
        $layouts_ids = [];

        foreach ($requests AS $r) {
            $request_ids[$r->request_id] = $r->request_id;
            $layouts_ids[$r->job_layout_id] = $r->job_layout_id;
        }

        $responsabilities = $this->get_responsabilities($layouts_ids);
        //dd($responsabilities);
        
        $job_benefits = $this->get_job_benefits($request_ids);
        //dd($job_benefits);
        $data_rows = [];
        foreach ($requests as $index => $row) {
            $col = [];
        
            $col['documento_tipo'] = $row->seeker_document_type_abbr; // 'TIPO DOCUMENTO';
            $col['documento_numero'] = $row->seeker_document_number; //'NRO DOCUMENTO';
            $col['requerimiento_id'] = $row->job_id; //'ID REQUERIMIENTO';
            $col['solicitud_id'] = $row->request_id; //'ID SOLICITUD';
            $col['apellido'] = $row->seeker_last_name; //'APELLIDO';
            $col['nombre'] = $row->seeker_first_name; //'NOMBRE';
            $col['layout_codigo'] = $row->job_layout_code; //'CARGO CODIGO';
            $col['layout_codigo_integracion'] = $row->job_layout_code_integration; //'CARGO CODIGO INTEGRACION';
            $col['layout_nombre'] = $row->job_layout_job_title; //'CARGO NOMBRE';
            $col['layout_estado'] = $row->job_layout_active; //'CARGO ACTIVO';
            $col['layout_grupo_ocupacional'] = $row->charge_name; //'GRUPO OCUPACIONAL';
            $col['responsabilidades'] = isset($responsabilities[$row->job_layout_id]) ? join(', ', $responsabilities[$row->job_layout_id]) : ''; //RESPONSABILIDADES
            
            $benefit_base = ['Básico: ' . $row->request_monthly_gross_salary];

            $benefit_list = array_map(function($row){
                return $row->benefit_name . ': ' . $row->benefit_detail;
            }, $job_benefits[$row->request_id] ?? []);

            $col['plan_compensacion'] = join(', ',  array_merge($benefit_base, $benefit_list));

            $data_rows[] = $col;
        }

        echo $this->format_array($data_rows);
    }

    private function data()
    {   
        

        $this->db->select([
            'rc.job_id',
            'rc.seeker_id'
        ]);
        $this->db->from('tbl_recruitment_contracts rc');
        $this->db->where('rc.hired', 1);
        $this->db->order_by('rc.hired_at', 'DESC');
        $this->db->group_by('rc.seeker_id');

        $query = $this->db->get_compiled_select();


        $select = [
            's.ID AS seeker_id',
            's.document_type AS seeker_document_type',
            's.document_number AS seeker_document_number',
            's.first_name AS seeker_first_name',
            's.last_name AS seeker_last_name',
            'j.ID AS job_id',
            'sr.ID AS request_id',
            'sr.monthly_gross_salary AS request_monthly_gross_salary',
            'doc_type.abbreviation AS seeker_document_type_abbr'        
        ];

        if ($this->filters['layout_type'] == 'mof') {
            $select_mof = [
                'mof.ID AS mof_id',
                'mof.code AS mof_code',
                'mof.job_title AS mof_job_title',
                'mof.active AS mof_active',
                'mjch.charge_name AS mof_charge_name', 
            ];

            $select = array_merge($select, $select_mof);
        }

        //Perfil laboral
        if ($this->filters['layout_type'] == 'job_profile') {
            $select_jp = [
                //Perfiles laborales
                'jp.ID AS jp_id',
                'jp.code AS jp_code',
                'jp.job_title AS jp_job_title',
                'jp.active AS jp_active',
                'jjch.charge_name AS jp_charge_name', 
            ];

            $select = array_merge($select, $select_jp);
        }

        //Job Layouts
        if ($this->filters['layout_type'] == 'job_layout') {
            $select_jl = [
                'jl.id AS job_layout_id',
                'jl.code AS job_layout_code',
                'jl.code_integration AS job_layout_code_integration',
                'jl.job_title AS job_layout_job_title',
                'jl.active AS job_layout_active',
                'jch.charge_name AS charge_name', 
            ];
            $select = array_merge($select, $select_jl);
        }

        $this->db->select($select);
        $this->db->from('tbl_job_seekers s');
        $this->db->join('(' . $query . ') sc', 'sc.seeker_id=s.ID');
        $this->db->join('tbl_post_jobs j', 'sc.job_id=j.ID');
        $this->db->join('tbl_staff_requests sr', 'sr.ID=j.request_ID');
        
        if ($this->filters['layout_type'] == 'mof') {
            $this->db->join('tbl_mofs mof', 'mof.ID=sr.mof_ID');
            $this->db->join('tbl_job_charges mjch', 'mjch.ID=mof.job_charge_id', 'left');
        }

        if ($this->filters['layout_type'] == 'job_profile') {
            $this->db->join('tbl_job_profiles jp', 'jp.ID=sr.job_profile_ID');
            $this->db->join('tbl_job_charges jjch', 'jjch.ID=jp.job_charge_ID', 'left');
        }

        if ($this->filters['layout_type'] == 'job_layout') {
            $this->db->join('tbl_job_layouts jl', 'jl.ID=sr.job_layout_id');
            $this->db->join('tbl_job_charges jch', 'jch.ID=jl.job_charge_id', 'left');    
        }

        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=s.document_type', 'left');

    	return $this->db->get()->result();
    }

    private function build_header()
    {
        $header[] = 'TIPO DOCUMENTO';
        $header[] = 'NRO DOCUMENTO';
        $header[] = 'NOMBRE';
        $header[] = 'APELLIDO';
        $header[] = 'ID REQUERIMIENTO';
        $header[] = 'ID SOLICITUD';
        $header[] = 'CARGO CODIGO';
        $header[] = 'CARGO CODIGO INTEGRACION';
        $header[] = 'CARGO NOMBRE';
        $header[] = 'CARGO ACTIVO';
        $header[] = 'CARGO GRUPO OCUPACIONAL';
        $header[] = 'RESPONSABILIDADES';
        $header[] = 'PLAN DE COMPENSACIONES';

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

        $request_ids = [];
        $layouts_ids = [];
     
        foreach ($requests AS $r) {
            $request_ids[$r->request_id] = $r->request_id;
           
            if ($this->filters['layout_type'] == 'mof') {
                $layouts_ids[$r->mof_id] = $r->mof_id;
            }
    
            if ($this->filters['layout_type'] == 'job_profile') {
                $layouts_ids[$r->jp_id] = $r->jp_id;
            }
    
            if ($this->filters['layout_type'] == 'job_layout') {
                $layouts_ids[$r->job_layout_id] = $r->job_layout_id;
            }
        }

        $responsabilities = $this->get_responsabilities($layouts_ids);
        //dd($responsabilities);
        
        $job_benefits = $this->get_job_benefits($request_ids);
        //dd($job_benefits);

        foreach ($requests as $index => $row) {
            $col = [];
        
            $col[] = $row->seeker_document_type_abbr; // 'TIPO DOCUMENTO';
            $col[] = $row->seeker_document_number; //'NRO DOCUMENTO';
            $col[] = $row->seeker_first_name; //NOMBRE
            $col[] = $row->seeker_last_name; //APELLIDO
            $col[] = $row->job_id; //'ID REQUERIMIENTO';
            $col[] = $row->request_id; //'ID SOLICITUD';

            //Layout de puesto
            if ($this->filters['layout_type'] == 'job_layout') {
                $col[] = $row->job_layout_code; //'CARGO CODIGO';
                $col[] = $row->job_layout_code_integration; //'CARGO CODIGO INTEGRACION';
                $col[] = $row->job_layout_job_title; //'CARGO NOMBRE';
                $col[] = $row->job_layout_active; //'CARGO ACTIVO';
                $col[] = $row->charge_name; //'GRUPO OCUPACIONAL';
                $col[] = isset($responsabilities[$row->job_layout_id]) ? join(', ', $responsabilities[$row->job_layout_id]) : ''; //RESPONSABILIDADES
            }

            //Mof
            if ($this->filters['layout_type'] == 'mof') {
                $col[] = $row->mof_code; //'CARGO CODIGO';
                $col[] = ''; //'CARGO CODIGO INTEGRACION';
                $col[] = $row->mof_job_title; //'CARGO NOMBRE';
                $col[] = $row->mof_active; //'CARGO ACTIVO';
                $col[] = $row->mof_charge_name; //'GRUPO OCUPACIONAL';
                $col[] = isset($responsabilities[$row->mof_id]) ? join(', ', $responsabilities[$row->mof_id]) : ''; //RESPONSABILIDADES
            }

            //Perfil laboral
            if ($this->filters['layout_type'] == 'job_profile') {
                $col[] = $row->jp_code; //'CARGO CODIGO';
                $col[] = ''; //'CARGO CODIGO INTEGRACION';
                $col[] = $row->jp_job_title; //'CARGO NOMBRE';
                $col[] = $row->jp_active; //'CARGO ACTIVO';
                $col[] = $row->jp_charge_name; //'GRUPO OCUPACIONAL';
                $col[] = isset($responsabilities[$row->jp_id]) ? join(', ', $responsabilities[$row->jp_id]) : ''; //RESPONSABILIDADES
            }
           
            $benefit_base = ['Básico: ' . $row->request_monthly_gross_salary];

            $benefit_list = array_map(function($row){
                return $row->benefit_name . ': ' . $row->benefit_detail;
            }, $job_benefits[$row->request_id] ?? []);

            $col[] = join(', ',  array_merge($benefit_base, $benefit_list));

            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );
        }

        return true;
    }

    private function get_responsabilities($ids)
    {
        $responsabilities = [];

        if ($this->filters['layout_type'] == 'mof') {
            $responsabilities = $this->get_mof_responsabilities($ids);
        }

        if ($this->filters['layout_type'] == 'job_profile') {
            $responsabilities = $this->get_jp_responsabilities($ids);
        }

        if ($this->filters['layout_type'] == 'job_layout') {
            $responsabilities = $this->get_jl_responsabilities($ids);
        }

        return $responsabilities;
    }

    private function get_jl_responsabilities($ids)
    {
        $data = [];

        $this->db->from('tbl_job_layout_responsibilities res');
 
        $this->db->group_start();
        $batches = array_chunk($ids, 50);
        foreach ($batches as $batch) {
            $this->db->or_where_in('res.job_layout_id', $batch);
        }
        $this->db->group_end();

        $results = $this->db->get()->result();

        foreach ($results as $row) {
            $data[$row->job_layout_id][] = $row->responsibility;
        }

        return $data;
    }

    private function get_jp_responsabilities($ids)
    {
        $data = [];

        $this->db->from('tbl_job_profile_responsibilities res');
 
        $this->db->group_start();
        $batches = array_chunk($ids, 50);
        foreach ($batches as $batch) {
            $this->db->or_where_in('res.job_profile_ID', $batch);
        }
        $this->db->group_end();

        $results = $this->db->get()->result();

        foreach ($results as $row) {
            $data[$row->job_profile_ID][] = $row->responsibility;
        }

        return $data;
    }

    private function get_mof_responsabilities($ids)
    {
        $data = [];

        $this->db->from('tbl_mof_responsibilities res');
 
        $this->db->group_start();
        $batches = array_chunk($ids, 50);
        foreach ($batches as $batch) {
            $this->db->or_where_in('res.mof_ID', $batch);
        }
        $this->db->group_end();

        $results = $this->db->get()->result();

        foreach ($results as $row) {
            $data[$row->mof_ID][] = $row->responsibility;
        }

        return $data;
    }

    private function get_job_benefits($ids)
    {
        $data = [];

        $this->db->select([
            'sr_ben.benefit_ID AS benefit_id',
            'ben.benefit_name AS benefit_name',
            'sr_ben.detail AS benefit_detail',
            'sr_ben.request_ID AS request_id',
            
        ]);
        $this->db->from('tbl_staff_request_additional_benefits sr_ben');
        $this->db->join('tbl_laboral_benefits ben', 'ben.ID=sr_ben.benefit_ID');

        $this->db->group_start();
        $batches = array_chunk($ids, 50);
        foreach ($batches as $batch) {
            $this->db->or_where_in('sr_ben.request_ID', $batch);
        }
        $this->db->group_end();

        $results = $this->db->get()->result();

        foreach ($results as $row) {

            if (!$row->benefit_detail) {
                continue;
            }

            $data[$row->request_id][] = $row;
        }

        return $data;
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

    private function format_array($array)
    {
		$lines = [];
		$lines[] = '[';
		
		foreach ($array as $item) {
			$line = '    [';
			$elements = [];
			
			foreach ($item as $key => $value) {
                $value = $this->escape_mysql($value);
				if (is_string($value)) {
					$elements[] = "'$key' => '$value'";
				} else {
					$elements[] = "'$key' => $value";
				}
			}
			
			$line .= implode(', ', $elements);
			$line .= '],';
			$lines[] = $line;
		}
		
		$lines[] = ']';
		return implode("\n", $lines);
	}

    function escape_mysql($value) {
        if (is_int($value) || is_float($value)) {
            return $value;
        }
        if (is_null($value)) {
            return 'NULL';
        }
        
        // Escapa caracteres especiales
        $value = str_replace(
            ['\\', "'", '"', "\x00", "\n", "\r", "\x1a"],
            ['\\\\', "\\'", '\\"', "\\0", "\\n", "\\r", "\\Z"],
            $value
        );
        
        return $value ;
    }
}
