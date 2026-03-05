<?php
class Job_layout extends CI_Model
{   
    public function find($job_layout_id)
    {
        $this->db->from('tbl_job_layouts');
        $this->db->where('id', $job_layout_id);
        return $this->db->get()->row();
    }

    public function create($data_input) 
    {
        $data_skills = isset($data_input['skills']) ? $data_input['skills'] : [];
        $data_responsibilities = $data_input['responsibilities'];
        $data_resources = $data_input['resources'] ?? [];
        $data_benefits = isset($data_input['benefits']) ? $data_input['benefits'] : [];
        
        $this->db->trans_start();
        $active = isset($data_input['active']) ? $data_input['active'] : 1;
        $requested = isset($data_input['requested']) ? $data_input['requested'] : 0;
        $created_by_recruiter = isset($data_input['created_by_recruiter_id']) ? $data_input['created_by_recruiter_id'] : null; 
        $sunat_code = isset($data_input['sunat_code']) ? $data_input['sunat_code'] : null;

        $data_factor_valuations = isset($data_input['factor']) ? $data_input['factor'] : [];
        $data_disability = isset($data_input['disability']) ? $data_input['disability'] : [];

        $code = $this->create_code($data_input['company_id']);

        if (!$code) {
            return false;
        }

        $code_integration = $this->create_code_integration();

        if (!$code_integration) {
            return false;
        }
        
        $data_job_layout = [
            'code' => $code ? $code : null,
            'code_integration' => $code_integration ? $code_integration : null,
            'created_at' => date('Y-m-d H:i:s'),
            'version' => 'SG-OD-002<br>Versión: 04<br>Revisión: 01',
            'job_title' => $data_input['job_title'],
            'risk_criteria' => isset($data_input['risk_criteria']) ? $data_input['risk_criteria'] : null, 
            'job_charge_id' => isset($data_input['occupational_group']) ? $data_input['occupational_group'] : null,
            'education' => $data_input['education'],
            'study_grade_req' => $data_input['study_grade_req'],
            'education_req_detail' => $data_input['education_req_detail'],
            'study_grade_min' => $data_input['study_grade_min'],
            'education_min_detail' => $data_input['education_min_detail'],
            'experience' => $data_input['experience'],
            'experience_detail' => $data_input['experience_detail'],
            'last_update' => date('Y-m-d'),
            'created_by_recruiter_id' => $created_by_recruiter,
            'requested' => $requested,
            'active' => $active,
            'sunat_code' => $sunat_code,
            'basic_minimum' => isset($data_input['basic_minimum']) ? $data_input['basic_minimum'] : null,
            'basic_maximum' => isset($data_input['basic_maximum']) ? $data_input['basic_maximum'] : null,
            'stereotype' => isset($data_input['stereotype']) ? $data_input['stereotype'] : null ,
            'factor_differentiating' => isset($data_input['factor_differentiating']) ? $data_input['factor_differentiating'] : null,
            'factor_differentiating_other' => isset($data_input['factor_differentiating']) && $data_input['factor_differentiating'] == 'Otro' ? $data_input['factor_differentiating_other'] : null,
            'occupational_category_id' => isset($data_input['occupational_category']) && $data_input['occupational_category'] != '' ? $data_input['occupational_category'] : null,
            'company_id' => $data_input['company_id']
        ];

        $this->db->insert('tbl_job_layouts', $data_job_layout);

        $job_layout_id = $this->db->insert_id();
    
        $this->add_skills($data_skills, $job_layout_id);
        $this->add_responsibilities($data_responsibilities, $job_layout_id);
        $this->save_benefits($data_benefits, $job_layout_id);

        if (isset($data_input['check_validate_resources'])) {
            $this->add_resources($data_resources, $job_layout_id);
        }

        if (isset($data_input['check_validate_disability'])) {
            $this->save_disability_options($data_disability, $job_layout_id);
        }
        
        if (isset($data_input['check_validate_factor'])) {
            $this->save_factor_valuations($data_factor_valuations, $job_layout_id);
        }
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }

        //Sincronizar cargo en sistema nomina CA
        if ($this->config->item('system_payroll') == 'ca' && $data_input['company_id'] == '1') {
            $this->load->library('WS_ca/WS_ca_charge_lib', null, 'WS_ca_charge_lib');
            $this->WS_ca_charge_lib->sync($job_layout_id);
        }

        //Sincronizar cargo en sistema nomina EPLANI
        if ($this->config->item('system_payroll') == 'eplani' && $data_input['company_id'] == '1') {
            $this->load->library('WS_overall/WS_overall_charge_lib', null, 'WS_overall_charge_lib');
            $this->WS_overall_charge_lib->add($job_layout_id);
        }

        //Crear cargo en SAP
        if ($this->config->item('system_accounting') == 'sap') {
            $this->load->library('WS_sap/WS_sap_charge_lib', null, 'WS_sap_charge_lib');
            $this->WS_sap_charge_lib->add($job_layout_id);
        }

        return $job_layout_id;
    }

    public function create_by_recruiter($all_input)
    {
        $all_input['active'] = 0;
        $all_input['requested'] = 1;
        $all_input['created_by_recruiter_id'] = $this->session->userdata('user_id');

        return $this->create($all_input);
    }

    public function edit($data_input, $job_layout_id)
    {
        $job_layout = $this->find($job_layout_id);

        $data_skills = isset($data_input['skills']) ? $data_input['skills'] : [];
        $data_responsibilities = $data_input['responsibilities'];
        $data_resources = $data_input['resources'] ?? [];
        $data_disability = isset($data_input['disability']) ? $data_input['disability'] : [];
        $data_factor_valuations = isset($data_input['factor']) ? $data_input['factor'] : [];
        $data_benefits = isset($data_input['benefits']) ? $data_input['benefits'] : [];

        $data_job_layout = [
            'version' => 'SG-OD-002<br>Versión: 04<br>Revisión: 01',
            'job_title' => $data_input['job_title'],
            'risk_criteria' => $data_input['risk_criteria'], 
            'job_charge_id' => $data_input['occupational_group'],
            'education' => $data_input['education'],
            'study_grade_req' => $data_input['study_grade_req'],
            'education_req_detail' => $data_input['education_req_detail'],
            'study_grade_min' => $data_input['study_grade_min'],
            'education_min_detail' => $data_input['education_min_detail'],
            'experience' => $data_input['experience'],
            'experience_detail' => $data_input['experience_detail'],
            'last_update' => date('Y-m-d'),
            'basic_minimum' => isset($data_input['basic_minimum']) ? $data_input['basic_minimum'] : null,
            'basic_maximum' => isset($data_input['basic_maximum']) ? $data_input['basic_maximum'] : null,
            'stereotype' => isset($data_input['stereotype']) ? $data_input['stereotype'] : null ,
            'factor_differentiating' => isset($data_input['factor_differentiating']) ? $data_input['factor_differentiating'] : null,
            'factor_differentiating_other' => isset($data_input['factor_differentiating']) && $data_input['factor_differentiating'] == 'Otro' ? $data_input['factor_differentiating_other'] : null
        ];

        if (isset($data_input['active'])) {
            $data_job_layout['active'] = $data_input['active'];
        }

        if (isset($data_input['sunat_code'])) {
            $data_job_layout['sunat_code'] = $data_input['sunat_code'];
        }

        $data_job_layout['occupational_category_id'] = null;

        if (isset($data_input['occupational_category']) && $data_input['occupational_category'] != '') {
            $data_job_layout['occupational_category_id'] = $data_input['occupational_category'];
        }

        //Crear codigo puesto laboral
        if (trim((string)$job_layout->code) == '') {
            $code = $this->create_code($job_layout->company_id);
            
            if ($code) {
                $data_job_layout['code'] = $code;
            }
        }

        //Generar codigo integracion puesto laboral
        if (trim((string)$job_layout->code_integration) == '') {
            $code_integration = $this->create_code_integration();

            if ($code_integration) {
                $data_job_layout['code_integration'] = $code_integration;
            }
        }
        
        $this->db->trans_start();
     
        $this->db->where('id', $job_layout_id);
        $this->db->update('tbl_job_layouts', $data_job_layout);
        
        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_skills');
        $this->add_skills($data_skills, $job_layout_id);

        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_responsibilities');
        $this->add_responsibilities($data_responsibilities, $job_layout_id);
        
        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_laboral_benefits');
        $this->save_benefits($data_benefits, $job_layout_id);

        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_resources');

        if (isset($data_input['check_validate_resources'])) {
            $this->add_resources($data_resources, $job_layout_id);
        }

        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_disability_options');

        if (isset($data_input['check_validate_disability'])) {
            $this->save_disability_options($data_disability, $job_layout_id);
        }
        
        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_factor_valuations');

        if (isset($data_input['check_validate_factor'])) {
            $this->save_factor_valuations($data_factor_valuations, $job_layout_id);
        }
        
        $this->db->trans_complete();
    
        $trans_status = $this->db->trans_status();

        if (!$trans_status) {
            return false;
        }

        //Sincronizar cargo en sistema nomina
        if ($this->config->item('system_payroll') == 'ca' && $job_layout->company_id == '1') {
            $this->load->library('WS_ca/WS_ca_charge_lib', null, 'WS_ca_charge_lib');
            $this->WS_ca_charge_lib->sync($job_layout_id);
        }

        //Sincronizar cargo en sistema nomina EPLANI
        if ($this->config->item('system_payroll') == 'eplani' && $job_layout->company_id == '1') {
            $this->load->library('WS_overall/WS_overall_charge_lib', null, 'WS_overall_charge_lib');
            $this->WS_overall_charge_lib->add($job_layout_id);
        }

        //Actualizar cargo SAP
        if ($this->config->item('system_accounting') == 'sap') {   
            $this->load->library('WS_sap/WS_sap_charge_lib', null, 'WS_sap_charge_lib');
            $this->WS_sap_charge_lib->update($job_layout_id);
        }  

        return $trans_status;
    }

    public function add_skills($skills, $job_layout_id)
    {
        foreach ($skills as $skill) {
            $skill = trim($skill);
            
            if ($skill == '') {
                continue;
            }

            $data = array(
                'skill_name' => $skill,
                'job_layout_id' => $job_layout_id
            );

            $this->db->insert('tbl_job_layout_skills', $data);
        }
    }

    public function add_responsibilities($responsibilities, $job_layout_id)
    {
        foreach ($responsibilities as $responsibility) {
        
            $responsibility = trim($responsibility);
            
            if ($responsibility == '') {
                continue;
            }

            $data = array(
                'responsibility' => $responsibility,
                'job_layout_id' => $job_layout_id
            );

            $this->db->insert('tbl_job_layout_responsibilities', $data);
        }
    }

    public function get_job_layout_by_id($job_layout_id)
    {
        $this->db->from('tbl_job_layouts');
        $this->db->where('id', $job_layout_id);

        return $this->db->get()->row();
    }

    public function get_skills_by_job_layout_id($job_layout_id)
    {
        $this->db->from('tbl_job_layout_skills');
        $this->db->where('job_layout_id', $job_layout_id);

        return $this->db->get()->result();
    }

    public function get_responsibilities_by_job_layout_id($job_layout_id)
    {
        $this->db->from('tbl_job_layout_responsibilities');
        $this->db->where('job_layout_id', $job_layout_id);

        return $this->db->get()->result();
    }

    public function update_sts($job_layout_id) {

        $job_layout = $this->get_job_layout_by_id($job_layout_id);

        if (!$job_layout) {
            return false;
        }   

        $data_update = array(
            'active' => !$job_layout->active
        );
        
        $this->db->where('id', $job_layout_id);
        return $this->db->update('tbl_job_layouts', $data_update);
    }

    public function update($id, $data)
    {
		$this->db->where('id', $id);
		return $this->db->update('tbl_job_layouts', $data);
	}

    public function get_data_job_layouts($company_id)
    {
        $this->db->select(array(
            'id',
            'job_title'
        ));
        $this->db->from('tbl_job_layouts');
        $this->db->where('company_id', $company_id);
        $this->db->where('active', 1);

        return $this->db->get()->result();
    }

    public function add_resources($resources, $job_layout_id)
    {
        foreach ($resources as $key => $resource) {

            $resource_type = $key;
            $type_expense = '';
            $resource_value = isset($resource['value']) ? $resource['value'] : '';

            if ($resource_type == 'type_screening' || 
                $resource_type == 'type_emo' ||
                $resource_type == 'exam_type_covid') {
                $resource_value =  implode(',', (array)$resource_value);
                $type_expense = $resource['type_expense'];
            }

            if ($resource_value == '') {
                continue;
            }

            $protocol_detail = null;

            if ($resource_type == 'type_emo' && in_array('PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE', $resource['value'])) {
                $protocol_detail = $resource['protocol_detail'];
            }

            $data_resource = [ 
                'resource' => $resource_type,
                'resource_value' => $resource_value,
                'perform_on_stage ' => isset($resource['perform_on_stage']) ? $resource['perform_on_stage'] : null,
                'protocol_detail' => $protocol_detail,
                'type_expense' => $type_expense,
                'staff_in_charge' => $resource['staff_charge'],
                'job_layout_id' => $job_layout_id
            ];

            $this->db->insert('tbl_job_layout_resources', $data_resource);
        }
    }

    public function get_resource_by_name($resource, $job_layout_id)
    {
        $this->db->from('tbl_job_layout_resources');
        $this->db->where('resource', $resource);
        $this->db->where('job_layout_id', $job_layout_id);
        
        return $this->db->get()->row();
    }

    public function get_results_disability($job_layout_id = -1)
    {
        $this->db->from('tbl_job_layout_disability_themes');
        $this->db->where('active', 1);
        $result = $this->db->get()->result();

        foreach ($result as $row_theme) {

            $this->db->from('tbl_job_layout_disability_subthemes');
            $this->db->where('active', 1);
            $this->db->where('theme_id', $row_theme->id);

            $row_theme->subthemes = $this->db->get()->result();

            foreach ($row_theme->subthemes as $row_subthemes) {

                $this->db->from('tbl_job_layout_disability_sections');
                $this->db->where('active', 1);
                $this->db->where('subtheme_id', $row_subthemes->id);
    
                $row_subthemes->sections = $this->db->get()->result();
                
                foreach ($row_subthemes->sections as $row_item) {

                    $this->db->select([
                        'items.id AS item_id',
                        'items.name AS name',
                        'items.grade_info',
                        'disability.grade'
                    ]);
                    $this->db->from('tbl_job_layout_disability_items items');
                    $this->db->join('tbl_job_layout_disability disability', 'items.id=disability.item_id AND disability.job_layout_id="' . $job_layout_id . '"', 'left');
                    
                    $this->db->where('active', 1);
                    $this->db->where('section_id', $row_item->id);
        
                    $row_item->items = $this->db->get()->result();
                }
            }
        }

        return $result;
    }

    public function get_results_disability_options($job_layout_id = -1)
    {
        $this->db->from('tbl_job_layout_disability_themes');
        $this->db->where('active', 1);
        $result = $this->db->get()->result();

        foreach ($result as $row_theme) {

            $this->db->select([
                'subthemes.id',
                'subthemes.name',
                'disability_options.subtheme_id AS jl_subtheme_id',
                'disability_options.disability_allow AS jl_disability_allow',
            ]);
            $this->db->from('tbl_job_layout_disability_subthemes subthemes');
            $this->db->join('tbl_job_layout_disability_options disability_options', 'subthemes.id=disability_options.subtheme_id AND disability_options.job_layout_id="' . $job_layout_id . '"', 'left');
                
            $this->db->where('subthemes.active', 1);
            $this->db->where('subthemes.theme_id', $row_theme->id);

            $row_theme->subthemes = $this->db->get()->result();
        }

        return $result;
    }

    public function save_disability_options($disability, $job_layout_id)
    {
        foreach ($disability as $subtheme_id => $row) {
            $disability_allow = $row['allow'];
        
            $jl_disability = $this->db->get_where('tbl_job_layout_disability_options', [
                'subtheme_id' => $subtheme_id,
                'job_layout_id' => $job_layout_id
            ])->row();

            if ($jl_disability) {
                $this->db->where('job_layout_id', $job_layout_id);
                $this->db->where('subtheme_id', $subtheme_id);
                $this->db->update('tbl_job_layout_disability_options', [
                    'disability_allow' => $disability_allow
                ]);

            } else {

                $this->db->insert('tbl_job_layout_disability_options', [
                    'disability_allow' => $disability_allow,
                    'job_layout_id' => $job_layout_id,
                    'subtheme_id' => $subtheme_id
                ]);
            }
        }
    }

    public function disability_values($job_layout_id)
    {
        $themes['DISCAPACIDADES FÍSICAS'] = ['MIEMBRO SUPERIOR', 'MIEMBRO INFERIOR'];
        $themes['DISCAPACIDADES SENSORIALES'] = ['VISUAL', 'AUDITIVA', 'DE LENGUAJE'];

        $items = [
            [['DEHAMBULACIÓN', 'APREHENSIÓN', 'COMUNICACIÓN'], 3],
            [['MENTALES', 'FÍSICOS'], 2],
            [['ROL EN LAS ACTIVIDADES'], 1],
            [['ENTORNO FÍSICO (INSTALACIONES / RUIDO / LUMINOSIDAD)'], 1]
        ];

        $row_value = [];

        foreach ($themes as $theme => $subthemes) {
            foreach ($subthemes as $subtheme) {
                $value = 0;

                foreach ($items as $item) {

                    $this->db->select('SUM(disability.grade) as sum_grade');
                    $this->db->from('tbl_job_layout_disability disability');
                    $this->db->join('tbl_job_layout_disability_items items', 'items.id=disability.item_id');
                    $this->db->join('tbl_job_layout_disability_sections sections', 'sections.id=items.section_id');
                    $this->db->join('tbl_job_layout_disability_subthemes subthemes', 'subthemes.id=sections.subtheme_id');
                    $this->db->join('tbl_job_layout_disability_themes themes', 'themes.id=subthemes.theme_id');
                    $this->db->where('disability.job_layout_id', $job_layout_id);
                    $this->db->where('themes.name', $theme);
                    $this->db->where('subthemes.name', $subtheme);
                    $this->db->where_in('items.name', $item[0]);
                    
                    $sum_row = $this->db->get()->row();

                    $value+= ($sum_row->sum_grade * $item[1]);
                }

                $row_value[] = [
                    'theme' => $theme,
                    'subtheme' => $subtheme,
                    'value' => $value
                ];
            }
        }

        return $row_value;
    }

    public function get_disability_eligibles($job_layout_id)
    {
        $this->db->from('tbl_job_layout_disability_eligibles');
        $this->db->where('job_layout_id', $job_layout_id);

        return $this->db->get()->result();
    }

    public function save_disability_eligibles($disability_eligibles, $job_layout_id)
    {
        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_disability_eligibles');
        
        foreach ($disability_eligibles as $key => $row) {

            $data = [
                'disability' =>  $row['disability'],
                'resources' => $row['resources'],
                'job_layout_id' => $job_layout_id
            ];

            $this->db->insert('tbl_job_layout_disability_eligibles', $data);
        }
    }

    public function is_diff_section_disability_grade($job_layout_id, $old_disability_grade_values)
    {
        $old_disability_value_sum = array_sum(array_column($old_disability_grade_values, 'value'));
        $new_disability_value_sum = array_sum(array_column($this->disability_values($job_layout_id), 'value'));

        return $old_disability_value_sum != $new_disability_value_sum;
    }

    public function is_allow_disability_eligible($job_layout_id)
    {
        $disability_values = $this->disability_values($job_layout_id);

        if (array_sum(array_column($disability_values, 'value')) == 0) {
            return false;
        }

        foreach ($disability_values as $row) {

            if ($row['value'] >= 21 && $row['value'] <= 45) {
                return false;    
            }
        }

        return true;
    }

    public function create_code($company_id)
    {       
        $company = $this->Company->find($company_id);

        if (!$company) {
            return false;
        }

        $country = $this->Country->find($company->country_id);

        if (!$country) {
            return fals;
        }

        $country_id = $country->ID;
        $acronym = trim($country->iso_3166_1_alpha3);

        $row = $this->db->query(
            "SELECT id, number_correlative, acronym  FROM tbl_job_layout_codes WHERE country_id = '" . $country_id . "' FOR UPDATE;"
        )->row();

        if (!$row) {
            $data = [
                'acronym' => $acronym,
                'number_correlative' => 1,
                'country_id' => $country_id,
                'active' => 1
            ];
            $this->db->insert('tbl_job_layout_codes', $data);

            $row = $this->db->get_where('tbl_job_layout_codes', [
                'country_id' => $country_id
            ])->row();
        }

        if (!$row) {
            return false;
        }

        $code = $row->acronym . '-' . str_pad($row->number_correlative, 4, "0", STR_PAD_LEFT);

        $this->db->where('id', $row->id);
        $trans_update = $this->db->update('tbl_job_layout_codes', ['number_correlative' => $row->number_correlative + 1]);

        if (!$trans_update) {
            return false;
        }

        return $code;
    }

    public function get_factor_valuations($job_layout_id = 0)
    {
        $jl = $this->find($job_layout_id);

        $data = [];

        $this->db->from('tbl_factor_types');
        $this->db->where('active', 1);
        $results = $this->db->get()->result();

        foreach ($results as $factor_type) {
            
            $data[$factor_type->id]['name'] = $factor_type->name;
            $data[$factor_type->id]['automatic'] = $factor_type->automatic;
            $data[$factor_type->id]['id'] = $factor_type->id;
            
            $this->db->select([
                'fv.id',
                'fv.name',
                'fv.grade',
                'fv.score',
                'fv.level',
                'jp_fv.factor_id AS value_factor_id'
            ]);
            $this->db->from('tbl_factor_valuations fv');
            $this->db->join('tbl_job_layout_factor_valuations jp_fv', 'fv.id=jp_fv.factor_id AND jp_fv.job_layout_id="' . $job_layout_id . '"', 'left');
            $this->db->where('factor_type_id', $factor_type->id);
            $this->db->order_by('fv.id', 'ASC');

            $factors = $this->db->get()->result();

            $data[$factor_type->id]['value_factor_id'] = null;
            $data[$factor_type->id]['value_factor_name'] = null;
            $data[$factor_type->id]['value_factor_grade'] = null;
            $data[$factor_type->id]['value_factor_score'] = null;
            $data[$factor_type->id]['value_factor_level'] = null;

            if ($factor_type->automatic == 0) {
                foreach ($factors as $factor) {
                    $data[$factor_type->id]['factors'][] = [
                        'id' => $factor->id,
                        'name' => $factor->name,
                        'grade' => $factor->grade,
                        'score' => $factor->score,
                        'level' => $factor->level
                    ];
                    
                    if ($factor->value_factor_id) {
                        $data[$factor_type->id]['value_factor_id'] = $factor->value_factor_id;
                        $data[$factor_type->id]['value_factor_name'] = $factor->name;
                        $data[$factor_type->id]['value_factor_grade'] = $factor->grade;
                        $data[$factor_type->id]['value_factor_score'] = $factor->score;
                        $data[$factor_type->id]['value_factor_level'] = $factor->level;
                    }
                }
            }

            if ($factor_type->automatic == 1) {

                $data[$factor_type->id]['factors'][] = [];
                
                if ($factor_type->id == 1) {
            
                    if ($jl) {
                        $education = $this->Qualification->find($jl->study_grade_min);
                        $data[$factor_type->id]['value_factor_id'] = 0;
                        $data[$factor_type->id]['value_factor_name'] = $education ? $education->text : '';
                        $data[$factor_type->id]['value_factor_grade'] = $education ? $education->valorization_grade : '';
                        $data[$factor_type->id]['value_factor_score'] = $education ? $education->valorization_score : '';
                    }
                }

                if ($factor_type->id == 2) {
                
                    if ($jl) {
                        $experience = $this->Work_experience->find($jl->experience);
                        $data[$factor_type->id]['value_factor_id'] = 0;
                        $data[$factor_type->id]['value_factor_name'] = $experience->name;
                        $data[$factor_type->id]['value_factor_grade'] = $experience->valorization_grade;
                        $data[$factor_type->id]['value_factor_score'] = $experience->valorization_score;
                    }
                }

                if ($factor_type->id == 3) {
                 
                    if ($jl) {
                        $job_charge = $this->Job_charge->find($jl->job_charge_id);

                        $skills = $this->db->get_where('tbl_job_charge_skills', [
                            'job_charge_id' => $jl->job_charge_id
                        ])->result();
                
                        $array_skills = [];
                
                        foreach ($skills as $skill) {
                            $array_skills[] = mb_strtoupper($skill->skill_name);
                        }
                        sort($array_skills);

                        $data[$factor_type->id]['value_factor_id'] = 0;
                        $data[$factor_type->id]['value_factor_name'] = join(',', $array_skills);
                        $data[$factor_type->id]['value_factor_grade'] = $job_charge ? $job_charge->valorization_grade : null;
                        $data[$factor_type->id]['value_factor_score'] = $job_charge ? $job_charge->valorization_score : null;
                    }
                }
            }
        }
        return $data;
    }

    public function save_factor_valuations($factors, $job_layout_id)
    {
        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_factor_valuations');

        foreach ($factors as $factor_id) {

            if (empty($factor_id)) {
                continue;
            }
            
            $this->db->insert('tbl_job_layout_factor_valuations', [
                'job_layout_id' => $job_layout_id,
                'factor_id' => $factor_id
            ]);
        }
    }

    public function get_factor_total_score($job_layout_id)
    {
        $jl  = $this->find($job_layout_id);

        $this->db->select('SUM(fv.score) AS total_score');
        $this->db->from('tbl_job_layout_factor_valuations jp_fv');
        $this->db->join('tbl_factor_valuations fv', 'fv.id=jp_fv.factor_id');
        $this->db->join('tbl_factor_types ft', 'ft.id=fv.factor_type_id');
        $this->db->where('jp_fv.job_layout_id', $job_layout_id);
        $this->db->where('ft.automatic', 0);

        $row = $this->db->get()->row();

        $total_score = $row ? $row->total_score : 0;

        $education = $this->Qualification->find(@$jl->study_grade_min);
        $total_score+=$education ? $education->valorization_score : 0;

        $experience = $this->Work_experience->find(@$jl->experience);
        $total_score+=$experience ? $experience->valorization_score : 0;

        $job_charge = $this->Job_charge->find(@$jl->job_charge_id);
        $total_score+=$job_charge ? $job_charge->valorization_score : 0;

        return $total_score;
    }

    public function get_resources($id)
    {
        $data['type_emo'] = $this->get_resource_by_name('type_emo', $id);
		$data['exam_type_covid'] = $this->get_resource_by_name('exam_type_covid', $id);
        $data['exams_complementary'] = $this->get_resource_by_name('exams_complementary', $id);

        return $data;
    }

    public function get_change_resources($id, $old_resorces = [])
    {
        $resource_emo = $this->get_resource_by_name('type_emo', $id);
		$resource_exam_covid19 = $this->get_resource_by_name('exam_type_covid', $id);
        $resource_exams_complementary = $this->get_resource_by_name('exams_complementary', $id);

		$resource_change = [];

		if (@$resource_emo->resource_value != @($old_resorces['type_emo'])->resource_value) {
			$resource_change[] = 'EMO';
		}

		if (@$resource_exam_covid19->resource_value != @($old_resorces['exam_type_covid'])->resource_value) {
			$resource_change[] = 'COVID-19';
		}

		if (@$resource_exams_complementary->resource_value != @($old_resorces['exams_complementary'])->resource_value) {
			$resource_change[] = 'Exámenes complementarios';
		}

        return $resource_change;
    }

    public function save_benefits($benefits, $jl_id)
    {
        foreach ($benefits as $benefit_id => $row) {
            $maximum = $row['maximum'];
            $minimum = $row['minimum'];
            
            $data = [
                'job_layout_id' => $jl_id,
                'benefit_id' => $benefit_id,
                'minimum' => $minimum,
                'maximum' => $maximum
            ];

            $this->db->insert('tbl_job_layout_laboral_benefits', $data);
        }
    }

    public function get_benefits($company_id = 0, $jl_id = 0)
    {
        $jl_id = empty($jl_id) ? 0 : $jl_id;

        $this->db->select([
            'lb.ID AS benefit_id',
            'lb.benefit_name',
            'jplb.minimum',
            'jplb.maximum'
        ]);
        $this->db->from('tbl_laboral_benefits lb');
        $this->db->join(
            'tbl_job_layout_laboral_benefits jplb', 
            'lb.ID=jplb.benefit_id AND jplb.job_layout_id=' . $jl_id, 
            'left'
        );
        $this->db->where('lb.active', 1);
        $this->db->where('lb.company_id', $company_id);

        return $this->db->get()->result();
    }

    public function all($where = [])
    {
    	$this->db->from('tbl_job_layouts');

		if (count($where) > 0) {
			$this->db->where($where);
		}

    	return $this->db->get()->result();
	}

    public function create_code_integration()
    {     
        $row = $this->db->query(
            "SELECT id, number_correlative, acronym  FROM tbl_job_layout_code_integrations WHERE 1=1 FOR UPDATE;"
        )->row();

        if (!$row) {
            return false;
        }

        $code = $row->acronym . str_pad($row->number_correlative, 5, "0", STR_PAD_LEFT);

        $this->db->where('id', $row->id);
        $trans_update = $this->db->update('tbl_job_layout_code_integrations', ['number_correlative' => $row->number_correlative + 1]);

        if (!$trans_update) {
            return false;
        }

        return $code;
    }

    public function get_all_by_permission_clients($consultant_code, $client_code)
    {
        $employer = $this->Employer->find($this->session->userdata('user_id'));

        //Listar Layouts con permisos consultora, clientes indicados
        $this->db->select([
            'jl.id AS id',
            'jl.code AS code',
            'jl.code_integration AS code_integration',
            'jl.job_title AS job_title',
            'pjl.id AS permission_id'
        ]);
        $this->db->from('tbl_job_layouts jl');		
        $this->db->join('tbl_job_layout_permission_clients pjl', 'pjl.job_layout_id=jl.id');
        $this->db->where('pjl.consultant_code', $consultant_code);
        $this->db->where('pjl.client_code', $client_code);
        $this->db->where('jl.active', 1);
        $this->db->where('jl.company_id', $employer->company_ID);

        $sql_jl_with_permissions = $this->db->get_compiled_select();

        //Listar Layouts sin ningun permisos consultora, clientes
        $this->db->select([
            'jl.id AS id',
            'jl.code AS code',
            'jl.code_integration AS code_integration',
            'jl.job_title AS job_title',
            'pjl.id AS permission_id'
        ]);
        $this->db->from('tbl_job_layouts jl');		
        $this->db->join('tbl_job_layout_permission_clients pjl', 'pjl.job_layout_id=jl.id', 'left');
        $this->db->where('jl.active', 1);
        $this->db->where('jl.company_id', $employer->company_ID);
        $this->db->having('permission_id IS NULL');

        $sql_jl_without_permissions = $this->db->get_compiled_select();

        $sql = "SELECT 
                    jl.id AS id,
                    jl.code AS code,
                    jl.code_integration AS code_integration, 
                    jl.job_title AS job_title 
                FROM (" . $sql_jl_with_permissions . " UNION " . $sql_jl_without_permissions . ") jl GROUP BY jl.id";
        
        return $this->db->query($sql)->result();
    }

    public function get_sql_allowed_job_layouts_by_employer($employer_id)
    {
        $sql_workflow_employer_permissions = $this->Employer->get_sql_wf_employer_permissions_cost_centers($employer_id);
        
        $employer = $this->Employer->find($employer_id);

        //Listar Layouts con permisos consultora, clientes indicados
        $this->db->select([
            'jl.id AS id',
            'pjl.id AS permission_id'
        ]);
        $this->db->from('tbl_job_layouts jl');		
        $this->db->join('tbl_job_layout_permission_clients pjl', 'pjl.job_layout_id=jl.id');
        $this->db->join('tbl_employer_permission_clients AS employer_permission_clients', 'pjl.consultant_code=employer_permission_clients.consultant_code AND pjl.client_code=employer_permission_clients.client_code');
        $this->db->where('jl.company_id', $employer->company_ID);
        $this->db->where('employer_permission_clients.employer_id', $employer_id);
        $sql_jl_with_permissions = $this->db->get_compiled_select();

        //Listar Layouts sin ningun permisos consultora, clientes
        $this->db->select([
            'jl.id AS id',
            'pjl.id AS permission_id'
        ]);
        $this->db->from('tbl_job_layouts jl');		
        $this->db->join('tbl_job_layout_permission_clients pjl', 'pjl.job_layout_id=jl.id', 'left');
        $this->db->where('jl.company_id', $employer->company_ID);
        $this->db->having('permission_id IS NULL');

        $sql_jl_without_permissions = $this->db->get_compiled_select();

        $sql = "SELECT 
                    jl.id AS id
                FROM (" . $sql_jl_with_permissions . " UNION " . $sql_jl_without_permissions . ") jl GROUP BY jl.id";

        return $sql;
    }

    public function has_permission_employer($job_layout_id)
    {
        $sql_allowed_job_layouts = $this->get_sql_allowed_job_layouts_by_employer($this->session->userdata('user_id'));

        $this->db->select('job_layout.id');
        $this->db->from('tbl_job_layouts job_layout');
        $this->db->join('(' . $sql_allowed_job_layouts . ') AS job_layouts_per', 'job_layouts_per.id=job_layout.id');
        $this->db->where('job_layout.id', $job_layout_id);

        return $this->db->count_all_results() > 0;
    }
}
