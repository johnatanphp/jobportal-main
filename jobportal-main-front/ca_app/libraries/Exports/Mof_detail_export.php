<?php

class Mof_detail_export
{
    private $spreadsheet;
    private $mof;
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
        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        $this->mof = $this->Mof->find($this->filters['id']);
    }

    public function build($filters = [])
    {
        $this->filters = $filters;

        $this->init();
        $this->build_description();
        $this->build_skills();
        $this->build_responsibilities();
        $this->build_indicators();

        if ($this->filters['resource'] == 1) {
            $this->build_resources();
        }

        if ($this->filters['disability'] == 1) {
            $this->build_disabilities();
            $this->build_disabilities_valorization();
        }
        
        if ($this->filters['valorization'] == 1) {                  
            $this->build_factor_valuations();
            $this->build_factor_valuations_total();
        }

        if ($this->filters['salary_structure'] == 1) {                  
            $this->build_salary_structure();
        }

        $this->spreadsheet->removeSheetByIndex(0);

        return $this;
    }

    public function build_description()
    {
        $mof = $this->mof;

        $row = [
            'Id',
            'Código',
            'Nombre',
            'Grupo ocupacional',
            'Áreas perteneciente',
            'Criterio de riesgo',
            'Edu. Deseable - Grado de estudio',
            'Edu. Deseable - Detalle',
            'Edu. Mínima - Grado de estudio',
            'Edu. Detalle',
            'Formación',
            'Experiencia Años',
            'Experiencia Detalle'
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Descripción General'
        );

        $rows[] = $row;
        
        $charge = $this->Job_charge->get_job_charge_by_id($mof->job_charge_id);
        $study_grade_req = $this->Qualification->get_record_by_id($mof->study_grade_req);
        
        $study_grade_min = $this->Qualification->get_record_by_id($mof->study_grade_min);

        $belonging_areas = [];
        foreach ($this->Mof->get_belonging_areas_by_mof_id($mof->ID) as $area_row) {
            $belonging_areas[] = $area_row->area_name;
        }

        $row = [
            $mof->ID,
            $mof->code,
            $mof->job_title,
            $charge ? $charge->charge_name : '-', //Grupo ocupacional
            join(', ', $belonging_areas), //Areas perteneciente
            $mof->risk_criteria, //Criterio de riesgo
            isset($study_grade_req['text']) ? $study_grade_req['text'] : '',
            $mof->education_req_detail,
            isset($study_grade_min['text']) ? $study_grade_min['text'] : '',
            $mof->education_min_detail,
            $mof->education,
            ($this->Work_experience->find(['code' => $mof->experience]))->name,
            $mof->experience_detail 
        ];

        $rows[] = $row;

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_skills()
    {
        $mof = $this->mof;
        $mof_id = $this->filters['id'];
        $skills = $this->Mof->get_skills_by_mof_id($mof_id);

        $row = [
            'Habilidad',
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Habilidades'
        );

        $rows[] = $row;

        foreach ($skills as $skill_row) {
            $row = [
                $skill_row->skill_name
            ];

            $rows[] = $row;
        }

        $skills = $this->db->get_where('tbl_job_charge_skills', [
            'job_charge_id' => $mof->job_charge_id
        ])->result();

        foreach ($skills as $skill_row) {
            $row = [
                $skill_row->skill_name
            ];

            $rows[] = $row;
        }

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_responsibilities()
    {
        $mof_id = $this->filters['id'];
        $responsibilities = $this->Mof->get_responsibilities_by_mof_id($mof_id);

        $row = [
            'Responsabilidades',
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Responsabilidades'
        );

        $rows[] = $row;

        foreach ($responsibilities as $responsibility_row) {
            $row = [
                $responsibility_row->responsibility
            ];

            $rows[] = $row;
        }

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_indicators()
    {
        $mof_id = $this->filters['id'];
        $indicators = $this->Mof->get_indicators_by_mof_id($mof_id);
        		
        $row = [
            'Indicador',
            'Fórmula',
            'Fuente de información'
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Indicadores'
        );

        $rows[] = $row;

        foreach ($indicators as $indicator_row) {
            $row = [
                $indicator_row->indicator,
                $indicator_row->formula,
                $indicator_row->source_info
            ];

            $rows[] = $row;
        }

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_resources()
    {
        $mof_id = $this->filters['id'];
      
        $row = [
            'Recurso',
            'Tipo / Valor',
            'Tipo egreso',
            'Realizar en',
            'Encargado'
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Recursos'
        );

        $rows[] = $row;

        $rys_stages = [
            '2' => 'LONG LIST',
            '5' => 'SHORT LIST',
            '6' => 'SELECCIÓN'
        ];

        $resources_types = [
            'type_screening' => 'Tipo Screening',
            'type_emo' => 'Tipo EMO',
            'exam_type_covid' => 'Examen COVID 19',
            'exams_complementary' => 'Exámenes complementarios',
            'home_verification' => 'Verificación domiciliaria',
            'credit_verification' => 'Verificación crediticia',
            'labor_verification' => 'Verificación laboral',
            'degrees_titles_verification' => 'Verificación de grados y titulos',
            'degrees_titles_person_verification' => 'Verificación de grados y titulos presenciales'
        ];

        foreach ($resources_types as $resource_key => $resource_name) {

            $resource_row = $this->Mof->get_resource_by_name($resource_key, $mof_id);

            if (!$resource_row) {
                $row = [    
                    $resource_name,
                    '-',
                    '-',
                    '-',
                    '-'  
                ];
                $rows[] = $row;
                continue;
            }

            $resource_type = $resource_row->resource_value;

            if ($resource_key == 'exam_type_covid') {

                $covid_array = get_options_exam_type_covid();
                $covid19_values = explode(',', $resource_type);
                
                $covid19_list = [];
                foreach ($covid19_values as $val) {
                  $val = trim($val);
                  
                  if (isset($covid_array[$val])) {
                    $covid19_list[] = $covid_array[$val];
                  }
                }

                $resource_type = implode(', ', $covid19_list);
            }

            $row = [    
                $resource_name,
                $resource_type,
                $resource_row->type_expense,
                isset($rys_stages[$resource_row->perform_on_stage]) ? $rys_stages[$resource_row->perform_on_stage] : '-',
                !empty($resource_row->staff_in_charge) ? $resource_row->staff_in_charge : '-'  
            ];

            $rows[] = $row;
        }

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_disabilities()
    {
        $mof = $this->mof;
        $mof_id = $this->filters['id'];
        $disabilities = $this->Mof->get_results_disability($mof_id);

        $row = [
            'Discapacidad',
            'Categoría',
            'Sub Categoría',
            'Nivel',
            'Grado'
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Discapacidades'
        );

        $rows[] = $row;

        foreach ($disabilities as $row_theme) {

            foreach ($row_theme->subthemes as $row_subtheme)  {
            
                foreach ($row_subtheme->sections as $row_section) {

                    foreach ($row_section->items as $key => $row_item) {
                            
                        $row = [
                            $row_theme->name,
                            $row_subtheme->name,
                            $row_section->name,
                            $row_item->name,
                            $row_item->grade
                        ];

                        $rows[] = $row;
                    }   
                }
            }
        }

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_disabilities_valorization()
    {
        $mof = $this->mof;
        $mof_id = $this->filters['id'];
        $disabilities = $this->Mof->disability_values($mof_id);

        $row = [
            'Tipo de discapacidad',
            'Valorización',
            'Nivel de riesgo',
            'Puesto adaptable con discapacidad',
            'Detalle'
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Discapacidades Valorización'
        );

        $rows[] = $row;

        $disability_risk = 1;

        $value_risk_level = [
            1 => 'BAJO',
            2 => 'TOLERABLE',
            3 => 'ALTO'
        ]; 

        $value_adaptable_position = [
            1 => 'ADAPTABLE',
            2 => 'ADAPTABLE PARCIALMENTE',
            3 => 'NO ADAPTABLE'
        ];
        
        $value_measures = [
            1 => 'LAS "CONDICIONES DE TRABAJO" DEL PUESTO SON ADECUADAS PARA INCLUIR PERSONAL CON DISCAPACIDAD',
            2 => 'REQUIERE REVISIÓN POR LA GERENCIA/JEFATURA DEL PUESTO PARA DESPLEGAR RECURSOS ADICIONALES ANTES DE ASIGNAR PERSONAL CON DISCAPACIDAD',
            3 => 'LAS "CONDICIONES DE TRABAJO" DEL PUESTO NO ES APTO PARA PERSONAS CON DISCAPACIDAD'
        ];
    
        foreach ($disabilities as $value) {
            $value_status = 0;

            if ($value['value'] >= 15 && $value['value'] <= 17) {
                $value_status = 1;
                $disability_risk = 0;
            }
            
            if ($value['value'] >= 18 && $value['value'] <= 20) {
                $value_status = 2;
                $disability_risk = 0;
            }

            if ($value['value'] >= 21 && $value['value'] <= 45) {
                $value_status = 3;
            }
            
            $disability_value_info = isset($value_measures[$value_status]) ? $value_measures[$value_status] : '-'; 

            $row = [
                $value['theme'] . ' ' . '(' . $value['subtheme'],
                $value['value'],
                isset($value_risk_level[$value_status]) ? $value_risk_level[$value_status] : '-',
                isset($value_adaptable_position[$value_status]) ? $value_adaptable_position[$value_status] : '-',
                $disability_value_info
            ];

            $rows[] = $row;
        }

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_factor_valuations()
    {
        $mof = $this->mof;
        $mof_id = $this->filters['id'];
        
        $factor_valuations = $this->Mof->get_factor_valuations($mof_id);

        $row = [
            'Tema',
            'Factor',
            'Detalle',
            'Grado',
            'Puntaje'
        ];

        $rows[] = $row;

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Valorización de puesto'
        );

        foreach ($factor_valuations as $row_factor) {
            $row = [
                $row_factor['name'],
                $row_factor['mof_factor_level'] ? $row_factor['mof_factor_level'] : '-', 
                $row_factor['mof_factor_name'] ? $row_factor['mof_factor_name'] : '-', 
                $row_factor['mof_factor_grade'] ? $row_factor['mof_factor_grade'] : '-',
                $row_factor['mof_factor_score'] ? $row_factor['mof_factor_score'] : '-',
            ];

            if ($row_factor['automatic'] == 1) {

                $row = [
                    $row_factor['name'],
                    $row_factor['mof_factor_name'] ? $row_factor['mof_factor_name'] : '-',
                    '',
                    $row_factor['mof_factor_grade'] ? $row_factor['mof_factor_grade'] : '-',
                    $row_factor['mof_factor_score'] ? $row_factor['mof_factor_score'] : '-',
                ];
            }

            $rows[] = $row;
        }

        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_factor_valuations_total()
    {
        $mof = $this->mof;
        $mof_id = $this->filters['id'];
        
        $factor_valuation_total = $this->Mof->get_factor_total_score($mof_id);

        $row = [
            'Total',
        ];

        $rows[] = $row;

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Valorización Total'
        );
    
        $row = [
            $factor_valuation_total
        ];

        $rows[] = $row;
    
        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
    }

    public function build_salary_structure()
    {
        $mof = $this->mof;
        $mof_id = $this->filters['id'];
        
        $company = $this->Company->find($mof->company_id);
        $country = $this->Country->find($company->country_id);

        $factor_valuation_total = $this->Mof->get_factor_total_score($mof_id);

        $row = [
            'Beneficio',
            'Mínimo (' . ($country ? $country->currency_code : '') . ')',
            'Máximo (' . ($country ? $country->currency_code : '') . ')'
        ];

        $rows[] = $row;

        $rows[] = [
            'Básico',
            $mof->basic_minimum,
            $mof->basic_maximum
        ];

        $benefits = $this->Mof->get_benefits($mof->company_id, $mof_id);

        foreach ($benefits as $benefit_row) {
            $rows[] = [
                $benefit_row->benefit_name,
                $benefit_row->minimum,
                $benefit_row->maximum
            ];
        }

        $rows[] = [
            'Estereotipo',
            $mof->stereotype
        ];

        $rows[] = [
            'Factor diferenciador',
            $mof->factor_differentiating
        ];

        if ($mof->factor_differentiating == 'Otro') {
            $rows[] = [
                'Factor diferenciador Otro',
                $mof->factor_differentiating_other
            ];
        }

        $occupational_category = $this->Occupational_category->find($mof->occupational_category_id);
        
        $rows[] = [
            'Categoría ocupacional',
            $occupational_category ? $occupational_category->name : '-'
        ];

        $rows[] = [
            'Nivel Categoría ocupacional',
            $occupational_category ? $occupational_category->level : '-'
        ];

        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet(
            $this->spreadsheet, 
            'Estructura salarial'
        );
        
        $myWorkSheet->fromArray(
            $rows,
            null,
            'A1'
        );

        $this->spreadsheet->addSheet($myWorkSheet);
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
}
