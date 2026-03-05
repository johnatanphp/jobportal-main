<?php
class Job_profile extends CI_Model
{   
    public function find($job_profile_id)
    {
        $this->db->from('tbl_job_profiles');
        $this->db->where('ID', $job_profile_id);
        return $this->db->get()->row();
    }

    public function create($data_input) 
    {
        $data_skills = isset($data_input['skills']) ? $data_input['skills'] : [];
        $data_responsibilities = $data_input['responsibilities'];
        $data_resources = $data_input['resources'];
        $data_benefits = isset($data_input['benefits']) ? $data_input['benefits'] : [];
        
        $this->db->trans_start();
        $active = isset($data_input['active']) ? $data_input['active'] : 1;
        $requested = isset($data_input['requested']) ? $data_input['requested'] : 0;
        
        $created_by_recruiter = isset($data_input['created_by_recruiter_ID']) ? $data_input['created_by_recruiter_ID'] : null;

        $data_consultant = explode('|', $data_input['consultant_name']);
        $data_client = explode('|', $data_input['client_company_name']);
        $data_business_unit = explode('|', $data_input['business_unit_name']);
        
        $sunat_code = isset($data_input['sunat_code']) ? $data_input['sunat_code'] : null;

        $data_factor_valuations = isset($data_input['factor']) ? $data_input['factor'] : [];
        $data_disability = isset($data_input['disability']) ? $data_input['disability'] : [];

        $code = $this->create_code($data_business_unit[0], $data_consultant[0], $data_input['company_id']);
        
        $data_job_profile = [
            'code' => $code ? $code : null,
            'version' => 'SG-OD-002<br>Versión: 04',
            'no_cia' => $data_consultant[0],
            'consultant_name' => $data_consultant[1],
            'cod_clie' => $data_client[0],
            'client_company_name' => $data_client[1],
            'cod_business_unit' => $data_business_unit[0],
            'business_unit_name' => $data_business_unit[1],
            'cost_center' => $data_input['cost_center'],
            'job_title' => $data_input['job_title'],
            'risk_criteria' => isset($data_input['risk_criteria']) ? $data_input['risk_criteria'] : null, 
            'job_charge_ID' => isset($data_input['occupational_group']) ? $data_input['occupational_group'] : null,
            'education' => $data_input['education'],
            'study_grade_req' => $data_input['study_grade_req'],
            'education_req_detail' => $data_input['education_req_detail'],
            'study_grade_min' => $data_input['study_grade_min'],
            'education_min_detail' => $data_input['education_min_detail'],
            'experience' => $data_input['experience'],
            'experience_detail' => $data_input['experience_detail'],
            'last_update' => date('Y-m-d'),
            'created_by_recruiter_ID' => $created_by_recruiter,
            'requested' => $requested,
            'active' => $active,
            'sunat_code' => $sunat_code,
            'basic_minimum' => isset($data_input['basic_minimum']) ? $data_input['basic_minimum'] : null,
            'basic_maximum' => isset($data_input['basic_maximum']) ? $data_input['basic_maximum'] : null,
            'food_minimum' => isset($data_input['food_minimum']) ? $data_input['food_minimum'] : null,
            'food_maximum' => isset($data_input['food_maximum']) ? $data_input['food_maximum'] : null,
            'mobility_minimum' => isset($data_input['mobility_minimum']) ? $data_input['mobility_minimum'] : null,
            'mobility_maximum' => isset($data_input['mobility_maximum']) ? $data_input['mobility_maximum'] : null,
            'bonuses_commissions_minimum' => isset($data_input['bonuses_commissions_minimum']) ? $data_input['bonuses_commissions_minimum'] : null,
            'bonuses_commissions_maximum' => isset($data_input['bonuses_commissions_maximum']) ? $data_input['bonuses_commissions_maximum'] : null,
            'stereotype' => isset($data_input['stereotype']) ? $data_input['stereotype'] : null ,
            'factor_differentiating' => isset($data_input['factor_differentiating']) ? $data_input['factor_differentiating'] : null,
            'factor_differentiating_other' => isset($data_input['factor_differentiating']) && $data_input['factor_differentiating'] == 'Otro' ? $data_input['factor_differentiating_other'] : null,
            'occupational_category_id' => isset($data_input['occupational_category']) ? $data_input['occupational_category'] : null,
            'company_id' => $data_input['company_id']
        ];

        $this->db->insert('tbl_job_profiles', $data_job_profile);

        $job_profile_id = $this->db->insert_id();
    
        $this->add_skills($data_skills, $job_profile_id);
        $this->add_responsibilities($data_responsibilities, $job_profile_id);
        $this->save_benefits($data_benefits, $job_profile_id);

        if (isset($data_input['check_validate_resources'])) {
            $this->add_resources($data_resources, $job_profile_id);
        }

        if (isset($data_input['check_validate_disability'])) {
            $this->save_disability($data_disability, $job_profile_id);
        }
        
        if (isset($data_input['check_validate_factor'])) {
            $this->save_factor_valuations($data_factor_valuations, $job_profile_id);
        }
        
        $this->db->trans_complete();

        return $this->db->trans_status() === true ? $job_profile_id : false;
    }

    public function create_by_recruiter($all_input)
    {
        $all_input['active'] = 0;
        $all_input['requested'] = 1;
        $all_input['created_by_recruiter_ID'] = $this->session->userdata('user_id');

        return $this->create($all_input);
    }

    public function edit($data_input, $job_profile_id)
    {
        $job_profile = $this->find($job_profile_id);

        $data_skills = isset($data_input['skills']) ? $data_input['skills'] : [];
        $data_responsibilities = $data_input['responsibilities'];
        $data_resources = $data_input['resources'];
        $data_disability = isset($data_input['disability']) ? $data_input['disability'] : [];
        $data_factor_valuations = isset($data_input['factor']) ? $data_input['factor'] : [];
        $data_benefits = isset($data_input['benefits']) ? $data_input['benefits'] : [];

        $data_consultant = explode('|', $data_input['consultant_name']);
        $data_client = explode('|', $data_input['client_company_name']);
        $data_business_unit = explode('|', $data_input['business_unit_name']);

        $data_job_profile = [
            'version' => 'SG-OD-002<br>Versión: 04',
            'no_cia' => $data_consultant[0],
            'consultant_name' => $data_consultant[1],
            'cod_clie' => $data_client[0],
            'client_company_name' => $data_client[1],
            'cod_business_unit' => $data_business_unit[0],
            'business_unit_name' => $data_business_unit[1],
            'cost_center' => $data_input['cost_center'],
            'job_title' => $data_input['job_title'],
            'risk_criteria' => $data_input['risk_criteria'], 
            'job_charge_ID' => $data_input['occupational_group'],
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
            'food_minimum' => isset($data_input['food_minimum']) ? $data_input['food_minimum'] : null,
            'food_maximum' => isset($data_input['food_maximum']) ? $data_input['food_maximum'] : null,
            'mobility_minimum' => isset($data_input['mobility_minimum']) ? $data_input['mobility_minimum'] : null,
            'mobility_maximum' => isset($data_input['mobility_maximum']) ? $data_input['mobility_maximum'] : null,
            'bonuses_commissions_minimum' => isset($data_input['bonuses_commissions_minimum']) ? $data_input['bonuses_commissions_minimum'] : null,
            'bonuses_commissions_maximum' => isset($data_input['bonuses_commissions_maximum']) ? $data_input['bonuses_commissions_maximum'] : null,
            'stereotype' => isset($data_input['stereotype']) ? $data_input['stereotype'] : null ,
            'factor_differentiating' => isset($data_input['factor_differentiating']) ? $data_input['factor_differentiating'] : null,
            'factor_differentiating_other' => isset($data_input['factor_differentiating']) && $data_input['factor_differentiating'] == 'Otro' ? $data_input['factor_differentiating_other'] : null
        ];

        //if (isset($data_input['code'])) {
        //    $data_job_profile['code'] = $data_input['code'];
        //}

        if (isset($data_input['active'])) {
            $data_job_profile['active'] = $data_input['active'];
        }

        if (isset($data_input['sunat_code'])) {
            $data_job_profile['sunat_code'] = $data_input['sunat_code'];
        }

        if (isset($data_input['occupational_category'])) {
            $data_job_profile['occupational_category_id'] = $data_input['occupational_category'];
        }

        if (empty($job_profile->code) || 
            ($data_consultant[0] != $job_profile->no_cia || $data_business_unit[0] != $job_profile->cod_business_unit)) {
            $code = $this->create_code($data_business_unit[0], $data_consultant[0], $job_profile->company_id);
            $data_job_profile['code'] = $code ? $code : null;
        }
        
        $this->db->trans_start();
     
        $this->db->where('ID', $job_profile_id);
        $this->db->update('tbl_job_profiles', $data_job_profile);
        
        $this->db->where('job_profile_ID', $job_profile_id);
        $this->db->delete('tbl_job_profile_skills');
        $this->add_skills($data_skills, $job_profile_id);

        $this->db->where('job_profile_ID', $job_profile_id);
        $this->db->delete('tbl_job_profile_responsibilities');
        $this->add_responsibilities($data_responsibilities, $job_profile_id);
        
        $this->db->where('job_profile_id', $job_profile_id);
        $this->db->delete('tbl_job_profile_laboral_benefits');
        $this->save_benefits($data_benefits, $job_profile_id);

        $this->db->where('job_profile_ID', $job_profile_id);
        $this->db->delete('tbl_job_profile_resources');

        if (isset($data_input['check_validate_resources'])) {
            $this->add_resources($data_resources, $job_profile_id);
        }

        $this->db->where('job_profile_id', $job_profile_id);
        $this->db->delete('tbl_job_profile_disability');

        if (isset($data_input['check_validate_disability'])) {
            $this->save_disability($data_disability, $job_profile_id);
        }
        
        $this->db->where('job_profile_id', $job_profile_id);
        $this->db->delete('tbl_job_profile_factor_valuations');

        if (isset($data_input['check_validate_factor'])) {
            $this->save_factor_valuations($data_factor_valuations, $job_profile_id);
        }
        
        $this->db->trans_complete();
    
        return $this->db->trans_status();
    }

    private function add_skills($skills, $mof_id)
    {
        foreach ($skills as $skill) {
            $skill = trim($skill);
            
            if ($skill == '') {
                continue;
            }

            $data = array(
                'skill_name' => $skill,
                'job_profile_ID' => $mof_id
            );

            $this->db->insert('tbl_job_profile_skills', $data);
        }
    }

    private function add_responsibilities($responsibilities, $mof_id)
    {
        foreach ($responsibilities as $responsibility) {
        
            $responsibility = trim($responsibility);
            
            if ($responsibility == '') {
                continue;
            }

            $data = array(
                'responsibility' => $responsibility,
                'job_profile_ID' => $mof_id
            );

            $this->db->insert('tbl_job_profile_responsibilities', $data);
        }
    }

    public function search_all_for_user($filter, $per_page, $page)
    {
        $this->db->from('tbl_job_profiles job_profile');
        
        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }

        if (isset($filter['company_id']) && $filter['company_id'] != '') {
            $this->db->where('company_id', $filter['company_id']);
        }

        if (isset($filter['no_cia']) && $filter['no_cia'] != '') {
            $this->db->where('job_profile.no_cia', $filter['no_cia']);
        }

        if (isset($filter['cod_business_unit']) && $filter['cod_business_unit'] != '') {
            $this->db->where('job_profile.cod_business_unit', $filter['cod_business_unit']);
        }

        if (isset($filter['cod_clie']) && $filter['cod_clie'] != '') {
            $this->db->where('job_profile.cod_clie', $filter['cod_clie']);
        }

        if (isset($filter['cost_center']) && $filter['cost_center'] != '') {
            $this->db->where('job_profile.cost_center', $filter['cost_center']);
        }

        if (isset($filter['permission_cost_centers'])) {
            $filter['permission_cost_centers'][-1] = -1;
            $this->db->where_in('job_profile.cost_center', $filter['permission_cost_centers']);
        }

        if ($filter['query'] != '') {
            $this->db->like('job_profile.job_title', $filter['query']);
            $this->db->or_like('job_profile.code', $filter['query']);
            $this->db->or_like('job_profile.ID', $filter['query']);
            $this->db->or_like('job_profile.consultant_name', $filter['query']);
            $this->db->or_like('job_profile.business_unit_name', $filter['query']);
            $this->db->or_like('job_profile.client_company_name', $filter['query']);
            $this->db->or_like('job_profile.cost_center', $filter['query']);      
        }

        $this->db->order_by('job_profile.active', 'DESC');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_all_for_user($filter = [])
    {
        $this->db->select('job_profile.ID');
        $this->db->from('tbl_job_profiles job_profile');

        if (isset($filter['company_id']) && $filter['company_id'] != '') {
            $this->db->where('company_id', $filter['company_id']);
        }
        
        if (isset($filter['requested']) && $filter['requested'] != '') {
            $this->db->where('requested', $filter['requested']);
        }

        if (isset($filter['status']) && $filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }

        if (isset($filter['no_cia']) && $filter['no_cia'] != '') {
            $this->db->where('job_profile.no_cia', $filter['no_cia']);
        }

        if (isset($filter['cod_business_unit']) && $filter['cod_business_unit'] != '') {
            $this->db->where('job_profile.cod_business_unit', $filter['cod_business_unit']);
        }

        if (isset($filter['cod_clie']) && $filter['cod_clie'] != '') {
            $this->db->where('job_profile.cod_clie', $filter['cod_clie']);
        }

        if (isset($filter['cost_center']) && $filter['cost_center'] != '') {
            $this->db->where('job_profile.cost_center', $filter['cost_center']);
        }
        
        if (isset($filter['permission_cost_centers'])) {
            $filter['permission_cost_centers'][-1] = -1;
            $this->db->where_in('job_profile.cost_center', $filter['permission_cost_centers']);
        }
               
        if ($filter['query'] != '') {
            $this->db->like('job_profile.code', $filter['query']);
            $this->db->or_like('job_profile.job_title', $filter['query']);
            $this->db->or_like('job_profile.ID', $filter['query']);
            $this->db->or_like('job_profile.consultant_name', $filter['query']);
            $this->db->or_like('job_profile.business_unit_name', $filter['query']);
            $this->db->or_like('job_profile.client_company_name', $filter['query']);
            $this->db->or_like('job_profile.cost_center', $filter['query']); 
        }

        return $this->db->count_all_results(); 
    }

    public function search_all($filter, $per_page, $page)
    {
        $this->db->select([
            'job_profile.*',
            'c.company_name'
        ]);
        $this->db->from('tbl_job_profiles job_profile');
        $this->db->join('tbl_companies c', 'c.ID=job_profile.company_id');

        if ($filter['company_id']) {
            $this->db->where('job_profile.company_id', $filter['company_id']);
        }

        if ($filter['requested'] != '') {
            $this->db->where('requested', $filter['requested']);
        }

        if ($filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }

        if ($filter['query'] != '') {
            $this->db->group_start();
            $this->db->like('job_profile.job_title', $filter['query']);
            $this->db->or_like('job_profile.code', $filter['query']);
            $this->db->or_like('job_profile.ID', $filter['query']);
            $this->db->or_like('job_profile.consultant_name', $filter['query']);
            $this->db->or_like('job_profile.business_unit_name', $filter['query']);
            $this->db->or_like('job_profile.client_company_name', $filter['query']);
            $this->db->or_like('job_profile.cost_center', $filter['query']);      
            $this->db->group_end();
        }

        $this->db->order_by('job_profile.active', 'DESC');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_all($filter = array())
    {
        $this->db->select('job_profile.ID');
        $this->db->from('tbl_job_profiles job_profile');
        
        if ($filter['company_id']) {
            $this->db->where('job_profile.company_id', $filter['company_id']);
        }

        if ($filter['requested'] != '') {
            $this->db->where('requested', $filter['requested']);
        }

        if ($filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }

        if ($filter['query'] != '') {
            $this->db->group_start();
            $this->db->like('job_profile.code', $filter['query']);
            $this->db->or_like('job_profile.job_title', $filter['query']);
            $this->db->or_like('job_profile.ID', $filter['query']);
            $this->db->or_like('job_profile.consultant_name', $filter['query']);
            $this->db->or_like('job_profile.business_unit_name', $filter['query']);
            $this->db->or_like('job_profile.client_company_name', $filter['query']);
            $this->db->or_like('job_profile.cost_center', $filter['query']);
            $this->db->group_end(); 
        }

        return $this->db->count_all_results();  
    }

    public function get_job_profile_by_id($job_profile_id)
    {
        $this->db->from('tbl_job_profiles');
        $this->db->where('ID', $job_profile_id);

        return $this->db->get()->row();
    }

    public function get_skills_by_job_profile_id($mof_id)
    {
        $this->db->from('tbl_job_profile_skills');
        $this->db->where('job_profile_ID', $mof_id);

        return $this->db->get()->result();
    }

    public function get_responsibilities_by_job_profile_id($mof_id)
    {
        $this->db->from('tbl_job_profile_responsibilities');
        $this->db->where('job_profile_ID', $mof_id);

        return $this->db->get()->result();
    }

    public function update_sts($job_profile_id) {

        $job_profile = $this->get_job_profile_by_id($job_profile_id);

        if (!$job_profile) {
            return false;
        }   

        $data_update = array(
            'active' => !$job_profile->active
        );
        
        $this->db->where('ID', $job_profile_id);
        return $this->db->update('tbl_job_profiles', $data_update);
    }

    public function update($id, $data)
    {
		$this->db->where('ID', $id);
		return $this->db->update('tbl_job_profiles', $data);
	}

    public function get_data_job_profiles(
        $company_id,
        $consultant,
        $business_unit,
        $client_company,
        $cost_center)
    {
        $this->db->select(array(
            'ID',
            'job_title'
        ));
        $this->db->from('tbl_job_profiles');
        $this->db->where('company_id', $company_id);
        $this->db->where('no_cia', $consultant);
        $this->db->where('cod_business_unit', $business_unit);
        $this->db->where('cod_clie', $client_company);
        $this->db->where('cost_center', $cost_center);
        $this->db->where('active', 1);

        return $this->db->get()->result();
    }

    private function add_resources($resources, $job_profile_id)
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
                'job_profile_ID' => $job_profile_id
            ];

            $this->db->insert('tbl_job_profile_resources', $data_resource);
        }
    }

    public function get_resource_by_name($resource, $job_profile_id)
    {
        $this->db->from('tbl_job_profile_resources');
        $this->db->where('resource', $resource);
        $this->db->where('job_profile_ID', $job_profile_id);
        
        return $this->db->get()->row();
    }

    public function get_results_disability($job_profile_id = -1)
    {
        $this->db->from('tbl_job_profile_disability_themes');
        $this->db->where('active', 1);
        $result = $this->db->get()->result();

        foreach ($result as $row_theme) {

            $this->db->from('tbl_job_profile_disability_subthemes');
            $this->db->where('active', 1);
            $this->db->where('theme_id', $row_theme->id);

            $row_theme->subthemes = $this->db->get()->result();

            foreach ($row_theme->subthemes as $row_subthemes) {

                $this->db->from('tbl_job_profile_disability_sections');
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
                    $this->db->from('tbl_job_profile_disability_items items');
                    $this->db->join('tbl_job_profile_disability disability', 'items.id=disability.item_id AND disability.job_profile_id="' . $job_profile_id . '"', 'left');
                    
                    $this->db->where('active', 1);
                    $this->db->where('section_id', $row_item->id);
        
                    $row_item->items = $this->db->get()->result();
                }
            }
        }

        return $result;
    }

    private function save_disability($disability, $job_profile_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        foreach ($disability as $key => $row) {
            $grade = $row['grade'];
            $item_id = $row['item_id'];

            $mof_disability = $this->db->get_where('tbl_job_profile_disability', [
                'item_id' => $item_id,
                'job_profile_id' => $job_profile_id
            ])->row();

            if ($mof_disability) {

                $this->db->where('job_profile_id', $job_profile_id);
                $this->db->where('item_id', $item_id);
                $this->db->update('tbl_job_profile_disability', [
                    'grade' => $grade
                ]);

            } else {

                $this->db->insert('tbl_job_profile_disability', [
                    'grade' => $grade,
                    'job_profile_id' => $job_profile_id,
                    'item_id' => $item_id
                ]);
            }
        }
    }

    public function disability_values($job_profile_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

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
                    $this->db->from('tbl_job_profile_disability disability');
                    $this->db->join('tbl_job_profile_disability_items items', 'items.id=disability.item_id');
                    $this->db->join('tbl_job_profile_disability_sections sections', 'sections.id=items.section_id');
                    $this->db->join('tbl_job_profile_disability_subthemes subthemes', 'subthemes.id=sections.subtheme_id');
                    $this->db->join('tbl_job_profile_disability_themes themes', 'themes.id=subthemes.theme_id');
                    $this->db->where('disability.job_profile_id', $job_profile_id);
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

    public function get_disability_eligibles($job_profile_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $this->db->from('tbl_job_profile_disability_eligibles');
        $this->db->where('job_profile_id', $job_profile_id);

        return $this->db->get()->result();
    }

    public function save_disability_eligibles($disability_eligibles, $job_profile_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $this->db->where('job_profile_id', $job_profile_id);
        $this->db->delete('tbl_job_profile_disability_eligibles');
        
        foreach ($disability_eligibles as $key => $row) {

            $data = [
                'disability' =>  $row['disability'],
                'resources' => $row['resources'],
                'job_profile_id' => $job_profile_id
            ];

            $this->db->insert('tbl_job_profile_disability_eligibles', $data);
        }
    }

    public function is_diff_section_disability_grade($mof_id, $old_disability_grade_values)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $old_disability_value_sum = array_sum(array_column($old_disability_grade_values, 'value'));
        $new_disability_value_sum = array_sum(array_column($this->disability_values($mof_id), 'value'));

        return $old_disability_value_sum != $new_disability_value_sum;
    }

    public function is_allow_disability_eligible($job_profile_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $disability_values = $this->disability_values($job_profile_id);

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

    public function create_code($cod_business_unit, $no_cia, $company_id)
    {       
        $cia_row = $this->db->get_where('tbl_workflow_consultants', [
            'code' => $no_cia,
            'company_id' => $company_id
        ])->row();

        if (!$cia_row || trim((string)$cia_row->type_service) == '') {
            return false;
        }

        $business_unit_row = $this->db->get_where('tbl_business_units', [
            'business_unit_code' => $cod_business_unit,
            'company_id' => $company_id
        ])->row();

        if (!$business_unit_row || trim($business_unit_row->acronym_code) == '') {
            return false;
        }

        $acronym_business_unit = $business_unit_row->acronym_code;
        $acronym_cia = $cia_row->type_service;

        $acronym = $acronym_business_unit . '-' . $acronym_cia;

        $row = $this->db->query(
            "SELECT id, number_correlative, acronym  FROM tbl_job_profile_codes WHERE acronym = '" . $acronym . "' AND company_id = '" . $company_id . "'FOR UPDATE;"
        )->row();

        if (!$row) {
            $data = [
                'acronym' => $acronym,
                'number_correlative' => 1,
                'active' => 1,
                'company_id' => $company_id
            ];
            $this->db->insert('tbl_job_profile_codes', $data);

            $row = $this->db->get_where('tbl_job_profile_codes', [
                'acronym' => $acronym,
                'company_id' => $company_id
            ])->row();
        }

        if (!$row) {
            return false;
        }

        $code = $row->acronym . '-' . str_pad($row->number_correlative, 3, "0", STR_PAD_LEFT);

        $this->db->where('id', $row->id);
        $this->db->update('tbl_job_profile_codes', ['number_correlative' => $row->number_correlative + 1]);

        return $code;
    }

    public function get_factor_valuations($job_profile_id = 0)
    {
        $jp = $this->find($job_profile_id);

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
                'jp_fv.factor_id AS jp_factor_id'
            ]);
            $this->db->from('tbl_factor_valuations fv');
            $this->db->join('tbl_job_profile_factor_valuations jp_fv', 'fv.id=jp_fv.factor_id AND jp_fv.job_profile_id="' . $job_profile_id . '"', 'left');
            $this->db->where('factor_type_id', $factor_type->id);
            $this->db->order_by('fv.id', 'ASC');

            $factors = $this->db->get()->result();

            $data[$factor_type->id]['jp_factor_id'] = null;
            $data[$factor_type->id]['jp_factor_name'] = null;
            $data[$factor_type->id]['jp_factor_grade'] = null;
            $data[$factor_type->id]['jp_factor_score'] = null;
            $data[$factor_type->id]['jp_factor_level'] = null;

            if ($factor_type->automatic == 0) {
                foreach ($factors as $factor) {
                    $data[$factor_type->id]['factors'][] = [
                        'id' => $factor->id,
                        'name' => $factor->name,
                        'grade' => $factor->grade,
                        'score' => $factor->score,
                        'level' => $factor->level
                    ];
                    
                    if ($factor->jp_factor_id) {
                        $data[$factor_type->id]['jp_factor_id'] = $factor->jp_factor_id;
                        $data[$factor_type->id]['jp_factor_name'] = $factor->name;
                        $data[$factor_type->id]['jp_factor_grade'] = $factor->grade;
                        $data[$factor_type->id]['jp_factor_score'] = $factor->score;
                        $data[$factor_type->id]['jp_factor_level'] = $factor->level;
                    }
                }
            }

            if ($factor_type->automatic == 1) {

                $data[$factor_type->id]['factors'][] = [];
                
                if ($factor_type->id == 1) {
            
                    if ($jp) {
                        $education = $this->Qualification->find($jp->study_grade_min);
                        $data[$factor_type->id]['jp_factor_id'] = 0;
                        $data[$factor_type->id]['jp_factor_name'] = $education ? $education->text : '';
                        $data[$factor_type->id]['jp_factor_grade'] = $education ? $education->valorization_grade : '';
                        $data[$factor_type->id]['jp_factor_score'] = $education ? $education->valorization_score : '';
                    }
                }

                if ($factor_type->id == 2) {
                
                    if ($jp) {
                        $experience = $this->Work_experience->find($jp->experience);
                        $data[$factor_type->id]['jp_factor_id'] = 0;
                        $data[$factor_type->id]['jp_factor_name'] = $experience->name;
                        $data[$factor_type->id]['jp_factor_grade'] = $experience->valorization_grade;
                        $data[$factor_type->id]['jp_factor_score'] = $experience->valorization_score;
                    }
                }

                if ($factor_type->id == 3) {
                 
                    if ($jp) {
                        $job_charge = $this->Job_charge->find($jp->job_charge_ID);

                        $skills = $this->db->get_where('tbl_job_charge_skills', [
                            'job_charge_id' => $jp->job_charge_ID
                        ])->result();
                
                        $array_skills = [];
                
                        foreach ($skills as $skill) {
                            $array_skills[] = mb_strtoupper($skill->skill_name);
                        }
                        sort($array_skills);

                        $data[$factor_type->id]['jp_factor_id'] = 0;
                        $data[$factor_type->id]['jp_factor_name'] = join(',', $array_skills);
                        $data[$factor_type->id]['jp_factor_grade'] = $job_charge ? $job_charge->valorization_grade : null;
                        $data[$factor_type->id]['jp_factor_score'] = $job_charge ? $job_charge->valorization_score : null;
                    }
                }
            }
        }
        return $data;
    }

    public function save_factor_valuations($factors, $job_profile_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $this->db->where('job_profile_id', $job_profile_id);
        $this->db->delete('tbl_job_profile_factor_valuations');

        foreach ($factors as $factor_id) {

            if (empty($factor_id)) {
                continue;
            }
            
            $this->db->insert('tbl_job_profile_factor_valuations', [
                'job_profile_id' => $job_profile_id,
                'factor_id' => $factor_id
            ]);
        }
    }

    public function get_factor_total_score($job_profile_id)
    {
        $jp  = $this->find($job_profile_id);

        $this->db->select('SUM(fv.score) AS total_score');
        $this->db->from('tbl_job_profile_factor_valuations jp_fv');
        $this->db->join('tbl_factor_valuations fv', 'fv.id=jp_fv.factor_id');
        $this->db->join('tbl_factor_types ft', 'ft.id=fv.factor_type_id');
        $this->db->where('jp_fv.job_profile_id', $job_profile_id);
        $this->db->where('ft.automatic', 0);

        $row = $this->db->get()->row();

        $total_score = $row ? $row->total_score : 0;

        $education = $this->Qualification->find(@$jp->study_grade_min);
        $total_score+=$education ? $education->valorization_score : 0;

        $experience = $this->Work_experience->find(@$jp->experience);
        $total_score+=$experience ? $experience->valorization_score : 0;

        $job_charge = $this->Job_charge->find(@$jp->job_charge_ID);
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

    private function save_benefits($benefits, $jp_id)
    {
        foreach ($benefits as $benefit_id => $row) {
            $maximum = $row['maximum'];
            $minimum = $row['minimum'];
            
            $data = [
                'job_profile_id' => $jp_id,
                'benefit_id' => $benefit_id,
                'minimum' => $minimum,
                'maximum' => $maximum
            ];

            $this->db->insert('tbl_job_profile_laboral_benefits', $data);
        }
    }

    public function get_benefits($company_id = 0, $jp_id = 0)
    {
        $jp_id = empty($jp_id) ? 0 : $jp_id;

        $this->db->select([
            'lb.ID AS benefit_id',
            'lb.benefit_name',
            'jplb.minimum',
            'jplb.maximum'
        ]);
        $this->db->from('tbl_laboral_benefits lb');
        $this->db->join(
            'tbl_job_profile_laboral_benefits jplb', 
            'lb.ID=jplb.benefit_id AND jplb.job_profile_id=' . $jp_id, 
            'left'
        );
        $this->db->where('lb.active', 1);
        $this->db->where('lb.company_id', $company_id);

        return $this->db->get()->result();
    }
}
