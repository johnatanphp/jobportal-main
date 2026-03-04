<?php
class Mof extends CI_Model 
{    
    public function find($mof_id)
    {
        $this->db->from('tbl_mofs');
        $this->db->where('ID', $mof_id);
        return $this->db->get()->row();
    }

    public function create($data_input) 
    {
        $this->db->trans_start();

        $data_mof_skills = isset($data_input['skills']) ? $data_input['skills'] : [];
        $data_mof_responsibilities = $data_input['responsibilities'];
        $data_belonging_areas = $data_input['belonging_areas'];
        $data_mof_indicators = isset($data_input['indicators']) ? $data_input['indicators'] : [];
        $data_mof_resources = $data_input['resources'];
        $data_mof_disability = isset($data_input['disability']) ? $data_input['disability'] : [];
        $data_mof_factor_valuations = isset($data_input['factor']) ? $data_input['factor'] : [];
        $data_mof_benefits = isset($data_input['benefits']) ? $data_input['benefits'] : [];

        $created_at = date('Y-m-d H:i:s');
        $last_update = isset($data_input['last_update']) ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data_input['last_update']))) : $created_at;
        $created_by_user_id = isset($data_input['created_by_user_id']) ? $data_input['created_by_user_id'] : null;
        $requested = isset($data_input['requested']) ? $data_input['requested'] : 0;
        $active = isset($data_input['active']) ? $data_input['active'] : 1;
        $code = isset($data_input['code']) ? $data_input['code'] : null;
        $sunat_code = isset($data_input['sunat_code']) ? $data_input['sunat_code'] : null;

        $code = $this->create_code($data_input['job_title'], $data_input['company_id']);
        
        $data_mof = [
            'code' => $code ? $code : null,
            'version' => 'SG-OD-002<br>Versión: 04',
            'job_title' => $data_input['job_title'],
            'job_charge_id' => $data_input['occupational_group'],
            'risk_criteria' => $data_input['risk_criteria'],
            'education' => $data_input['education'],
            'study_grade_req' => $data_input['study_grade_req'],
            'education_req_detail' => $data_input['education_req_detail'],
            'study_grade_min' => $data_input['study_grade_min'],
            'education_min_detail' => $data_input['education_min_detail'],
            'experience' => $data_input['experience'],
            'experience_detail' => $data_input['experience_detail'],
            'created_at' => $created_at,
            'last_update' => $last_update,
            'created_by_user_id' => $created_by_user_id,
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

        $this->db->insert('tbl_mofs', $data_mof);

        $mof_id = $this->db->insert_id();

        $this->save_mof_belonging_areas($data_belonging_areas, $mof_id); 
        $this->save_mof_skills($data_mof_skills, $mof_id);
        $this->save_mof_responsibilities($data_mof_responsibilities, $mof_id);
        $this->save_indicators($data_mof_indicators, $mof_id);
        $this->save_benefits($data_mof_benefits, $mof_id);

        if (isset($data_input['check_validate_resources'])) {
            $this->save_resources($data_mof_resources, $mof_id);
        }

        if (isset($data_input['check_validate_disability'])) {
            $this->save_disability($data_mof_disability, $mof_id);
        }   

        if (isset($data_input['check_validate_factor'])) {
            $this->save_factor_valuations($data_mof_factor_valuations, $mof_id);
        }
    
        $this->db->trans_complete();

        return $this->db->trans_status() === true ? $mof_id : false;
    }

    public function create_by_recruiter($all_input)
    {
        $all_input['active'] = 0;
        $all_input['requested'] = 1;
        $all_input['created_by_user_id'] = $this->session->userdata('user_id');

        return $this->create($all_input);
    }

    public function edit($data_input, $mof_id)
    {
        $mof = $this->find($mof_id);

        $data_mof = [
            'version' => 'SG-OD-002<br>Versión: 04',
            'job_title' => $data_input['job_title'],
            'job_charge_id' => $data_input['occupational_group'],
            'risk_criteria' => $data_input['risk_criteria'],
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
            'factor_differentiating_other' => isset($data_input['factor_differentiating']) && $data_input['factor_differentiating'] == 'Otro' ? $data_input['factor_differentiating_other'] : null,
        ];

        if (isset($data_input['code'])) {
            $data_mof['code'] = $data_input['code'];
        }
    
        if (!isset($data_input['code']) && 
            mb_strtoupper(trim($data_input['job_title'])) != mb_strtoupper($mof->job_title)) {
            $code = $this->create_code($data_input['job_title'], $mof->company_id);
            $data_mof['code'] = $code ? $code : ($mof->code ? $mof->code : null);
        }
    
        if (isset($data_input['active'])) {
            $data_mof['active'] = $data_input['active'];
        }

        if (isset($data_input['sunat_code'])) {
            $data_mof['sunat_code'] = $data_input['sunat_code'];
        }

        if (isset($data_input['occupational_category'])) {
            $data_mof['occupational_category_id'] = $data_input['occupational_category'];
        }

        $data_belonging_areas = $data_input['belonging_areas'];
        $data_mof_skills = isset($data_input['skills']) ? $data_input['skills'] : [];
        $data_mof_responsibilities = $data_input['responsibilities'];
        $data_mof_indicators = isset($data_input['indicators']) ? $data_input['indicators'] : [];
        $data_mof_resources = isset($data_input['resources']) ? $data_input['resources'] : [];
        $data_mof_disability = isset($data_input['disability']) ? $data_input['disability'] : [];
        $data_mof_factor_valuations = isset($data_input['factor']) ? $data_input['factor'] : [];
        $data_mof_benefits = isset($data_input['benefits']) ? $data_input['benefits'] : [];

        $this->db->trans_start();

        $this->db->where('ID', $mof_id);
        $this->db->update('tbl_mofs', $data_mof);
        
        $this->save_mof_belonging_areas($data_belonging_areas, $mof_id);
        $this->save_mof_skills($data_mof_skills, $mof_id);        
        $this->save_mof_responsibilities($data_mof_responsibilities, $mof_id);
        $this->save_indicators($data_mof_indicators, $mof_id);

        $this->db->where('mof_id', $mof_id);
        $this->db->delete('tbl_mof_laboral_benefits');
        $this->save_benefits($data_mof_benefits, $mof_id);

        $this->db->where('mof_id', $mof_id);
        $this->db->delete('tbl_mof_resources');

        if (isset($data_input['check_validate_resources'])) {
            $this->save_resources($data_mof_resources, $mof_id);
        }

        $this->db->where('mof_id', $mof_id);
        $this->db->delete('tbl_mof_disability');

        if (isset($data_input['check_validate_disability'])) {
            $this->save_disability($data_mof_disability, $mof_id);
        }   

        $this->db->where('mof_id', $mof_id);
        $this->db->delete('tbl_mof_factor_valuations');

        if (isset($data_input['check_validate_factor'])) {
            $this->save_factor_valuations($data_mof_factor_valuations, $mof_id);
        }
        
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    private function save_mof_belonging_areas($belonging_areas, $mof_id)
    {
        $this->db->where('mof_ID', $mof_id);
        $this->db->delete('tbl_mof_belonging_areas');

        foreach ($belonging_areas as $area_id) {
    
            $data = array(
                'belonging_area_ID' => $area_id,
                'mof_ID' => $mof_id
            );

            $this->db->insert('tbl_mof_belonging_areas', $data);
        }
    }

    private function save_mof_skills($skills, $mof_id)
    {
        $this->db->where('mof_ID', $mof_id);
        $this->db->delete('tbl_mof_skills');

        foreach ($skills as $skill) {
            $skill = trim($skill);
            
            if ($skill == '') {
                continue;
            }

            $data = array(
                'skill_name' => $skill,
                'mof_ID' => $mof_id
            );

            $this->db->insert('tbl_mof_skills', $data);
        }
    }

    private function save_indicators($indicators, $mof_id)
    {
        $this->db->where('mof_ID', $mof_id);
        $this->db->delete('tbl_mof_indicators');

        foreach ($indicators as $row_indicator) {
            
            $indicator = $row_indicator['indicator'];
            $formula = $row_indicator['formula'];
            $source_info = $row_indicator['source_info'];

            if ($indicator == '' || 
                $formula == '' || 
                $source_info == '') {
                continue;
            }

            $data = array(
                'indicator' => $indicator,
                'formula' => $formula,
                'source_info' => $source_info,
                'mof_ID' => $mof_id
            );

            $this->db->insert('tbl_mof_indicators', $data);
        }
    }

    private function save_benefits($benefits, $mof_id)
    {
        foreach ($benefits as $benefit_id => $row) {
            $maximum = $row['maximum'];
            $minimum = $row['minimum'];
            
            $data = [
                'mof_id' => $mof_id,
                'benefit_id' => $benefit_id,
                'minimum' => $minimum,
                'maximum' => $maximum
            ];

            $this->db->insert('tbl_mof_laboral_benefits', $data);
        }
    }

    private function save_mof_responsibilities($responsibilities, $mof_id)
    {
        $this->db->where('mof_ID', $mof_id);
        $this->db->delete('tbl_mof_responsibilities');

        foreach ($responsibilities as $responsibility) {
        
            $responsibility = trim($responsibility);
            
            if ($responsibility == '') {
                continue;
            }

            $data = array(
                'responsibility' => $responsibility,
                'mof_ID' => $mof_id
            );

            $this->db->insert('tbl_mof_responsibilities', $data);
        }
    }

    public function search_all_mofs($filter, $per_page, $page)
    {
        $this->db->select([
            'mof.ID',
            'mof.code',
            'mof.job_title',
            'mof.active',
            'mof.requested',
            'GROUP_CONCAT(internal_area.area_name SEPARATOR ", ") AS  belonging_areas',
            'c.company_name'
        ]);

        $this->db->from('tbl_mofs mof');
        $this->db->join('tbl_companies c', 'c.ID=mof.company_id');
        $this->db->join('tbl_mof_belonging_areas mof_belonging_area', 'mof_belonging_area.mof_ID=mof.ID', 'left');
        $this->db->join('tbl_internal_areas internal_area', 'mof_belonging_area.belonging_area_ID=internal_area.ID', 'left');

        if ($filter['company_id']) {
            $this->db->where('mof.company_id', $filter['company_id']);
        }

        if ($filter['requested'] != '') {
            $this->db->where('requested', $filter['requested']);
        }

        if ($filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }
        
        if ($filter['query'] != '') {
            $this->db->group_start();
            $this->db->like('mof.code', $filter['query']);
            $this->db->or_like('mof.job_title', $filter['query']);
            $this->db->group_end();
        }

        $this->db->group_by('mof.ID');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_all_mofs($filter = array())
    {
        $this->db->select('mof.ID');
        $this->db->from('tbl_mofs mof');
        
        if ($filter['company_id'] != '') {
            $this->db->where('mof.company_id', $filter['company_id']);
        }

        if ($filter['requested'] != '') {
            $this->db->where('requested', $filter['requested']);
        }

        if ($filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }

        if ($filter['query'] != '') {
            $this->db->group_start();
            $this->db->like('mof.code', $filter['query']);
            $this->db->or_like('mof.job_title', $filter['query']);
            $this->db->group_end();
        }

        return $this->db->count_all_results();  
    }

    public function get_mof_by_id($mof_id)
    {
        $this->db->from('tbl_mofs');
        $this->db->where('ID', $mof_id);

        return $this->db->get()->row();
    }

    public function get_mof_by_code($code_mof)
    {
        $this->db->select(array(
            'mof.*',
            'internal_area.area_name AS belonging_area_name'
        ));
        
        $this->db->from('tbl_mofs mof');
        $this->db->join('tbl_internal_areas internal_area', 'internal_area.ID=mof.belonging_area');
        $this->db->where('code', $code_mof);

        return $this->db->get()->row();
    }
    
    public function get_belonging_areas_by_mof_id($mof_id)
    {
        $this->db->from('tbl_mof_belonging_areas mof_belonging_area');
        $this->db->join('tbl_internal_areas internal_area', 'mof_belonging_area.belonging_area_ID=internal_area.ID');
        $this->db->where('mof_belonging_area.mof_id', $mof_id);

        return $this->db->get()->result();
    }

    public function get_skills_by_mof_id($mof_id)
    {
        $this->db->from('tbl_mof_skills');
        $this->db->where('mof_id', $mof_id);

        return $this->db->get()->result();
    }

    public function get_responsibilities_by_mof_id($mof_id)
    {
        $this->db->from('tbl_mof_responsibilities');
        $this->db->where('mof_ID', $mof_id);

        return $this->db->get()->result();
    }

    public function get_indicators_by_mof_id($mof_id)
    {
        $this->db->from('tbl_mof_indicators');
        $this->db->where('mof_ID', $mof_id);

        return $this->db->get()->result();
    }

    public function update($id, $data)
    {
		$this->db->where('ID', $id);
		return $this->db->update('tbl_mofs', $data);
	}

    public function get_mofs_by_belonging_area_id($area_id)
    {
          $this->db->select(array(
            'mof.ID AS mof_id',
            'mof.job_title',
            'mof.code'
        ));

        $this->db->from('tbl_mofs mof');
        $this->db->join('tbl_mof_belonging_areas mof_belonging_area', 'mof_belonging_area.mof_ID=mof.ID');
        $this->db->where('mof_belonging_area.belonging_area_ID', $area_id);
        $this->db->where('mof.active', 1);
        $this->db->order_by('mof.code', 'ASC');

        return $this->db->get()->result();
    }

    public function update_sts($mof_id) {

        $mof = $this->get_mof_by_id($mof_id);

        if (!$mof) {
            return false;
        }   

        $data_update = array(
            'active' => !$mof->active
        );
        
        $this->db->where('ID', $mof_id);
        return $this->db->update('tbl_mofs', $data_update);
    }

    private function save_resources($resources, $mof_id)
    {
        $this->db->where('mof_ID', $mof_id);
        $this->db->delete('tbl_mof_resources');

        $key_resources = [
            'access', 
            'trainings', 
            'infrastructure', 
            'type_screening', 
            'type_emo', 
            'home_verification',
            'exam_type_covid',
            'credit_verification',
            'labor_verification',
            'degrees_titles_verification',
            'degrees_titles_person_verification',
            'exams_complementary'
        ];

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

            if (!in_array($resource_type, $key_resources)) {
                continue;
            }

            $protocol_detail = '';

            if ($resource_type == 'type_emo' && in_array('PROTOCOLO 10. ESTABLECIDO POR EL CLIENTE', $resource['value'])) {
                $protocol_detail = $resource['protocol_detail'];
            }

            $data_resource = [
                'resource' => $resource_type,    
                'staff_in_charge' => $resource['staff_charge'],
                'perform_on_stage ' => isset($resource['perform_on_stage']) ? $resource['perform_on_stage'] : null,
                'resource_value' => $resource_value,
                'type_expense' => $type_expense,
                'protocol_detail' => $protocol_detail,
                'mof_ID' => $mof_id
            ];

            $this->db->insert('tbl_mof_resources', $data_resource);
        }
    }

    public function get_resource_by_name($resource, $mof_id)
    {
        $this->db->from('tbl_mof_resources');
        $this->db->where('resource', $resource);
        $this->db->where('mof_ID', $mof_id);
        
        return $this->db->get()->row();
    }

    public function get_disability_eligibles($mof_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $this->db->from('tbl_mof_disability_eligibles');
        $this->db->where('mof_id', $mof_id);

        return $this->db->get()->result();
    }

    public function save_disability_eligibles($disability_eligibles, $mof_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $this->db->where('mof_id', $mof_id);
        $this->db->delete('tbl_mof_disability_eligibles');
        
        foreach ($disability_eligibles as $key => $row) {

            $data = [
                'disability' =>  $row['disability'],
                'resources' => $row['resources'],
                'mof_id' => $mof_id
            ];

            $this->db->insert('tbl_mof_disability_eligibles', $data);
        }
    }

    public function search_all_by_user($filter, $per_page, $page)
    {
        $this->db->select([
            'mof.ID',
            'mof.code',
            'mof.job_title',
            'mof.active',
            'GROUP_CONCAT(internal_area.area_name SEPARATOR ", ") AS  belonging_areas'
        ]);

        $this->db->from('tbl_mofs mof');
        $this->db->join('tbl_mof_belonging_areas mof_belonging_area', 'mof_belonging_area.mof_ID=mof.ID', 'left');
        $this->db->join('tbl_internal_areas internal_area', 'mof_belonging_area.belonging_area_ID=internal_area.ID', 'left'); 

        $this->db->where('mof.company_id', $filter['company_id']);
        
        if ($filter['area_id'] != '') {
            $this->db->where_in('internal_area.ID', [$filter['area_id']]);
        }

        if ($filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }
        
        if ($filter['query'] != '') {
            $this->db->like('mof.code', $filter['query']);
            $this->db->or_like('mof.job_title', $filter['query']);
        }

        if (isset($filter['permission_area_ids'])) {
            $filter['permission_area_ids'][-1] = -1;
            $this->db->where_in('internal_area.ID', $filter['permission_area_ids']);
        }

        if (isset($filter['permission_job_charge_ids'])) {
            $filter['permission_job_charge_ids'][-1] = -1;
            $this->db->where_in('mof.job_charge_id', $filter['permission_job_charge_ids']);
        }
      
        $this->db->group_by('mof.ID');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_all_by_user($filter = [])
    {
        $this->db->select([
            'mof.ID'
        ]);

        $this->db->from('tbl_mofs mof');
        $this->db->join('tbl_mof_belonging_areas mof_belonging_area', 'mof_belonging_area.mof_ID=mof.ID', 'left');
        $this->db->join('tbl_internal_areas internal_area', 'mof_belonging_area.belonging_area_ID=internal_area.ID', 'left');
        $this->db->where('mof.company_id', $filter['company_id']);

        if ($filter['area_id'] != '') {
            $this->db->where_in('internal_area.ID', [$filter['area_id']]);
        }

        if ($filter['status'] != '') {
            $this->db->where('active', $filter['status']);
        }
        
        if ($filter['query'] != '') {
            $this->db->like('mof.code', $filter['query']);
            $this->db->or_like('mof.job_title', $filter['query']);
        }

        if (isset($filter['permission_area_ids'])) {
            $filter['permission_area_ids'][-1] = -1;
            $this->db->where_in('internal_area.ID', $filter['permission_area_ids']);
        }

        if (isset($filter['permission_job_charge_ids'])) {
            $filter['permission_job_charge_ids'][-1] = -1;
            $this->db->where_in('mof.job_charge_id', $filter['permission_job_charge_ids']);
        }

        $this->db->group_by('mof.ID');

        return $this->db->count_all_results();  
    }

    public function get_results_disability($mof_id = 0)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $this->db->from('tbl_mof_disability_themes');
        $this->db->where('active', 1);
        $result = $this->db->get()->result();

        foreach ($result as $row_theme) {

            $this->db->from('tbl_mof_disability_subthemes');
            $this->db->where('active', 1);
            $this->db->where('theme_id', $row_theme->id);

            $row_theme->subthemes = $this->db->get()->result();

            foreach ($row_theme->subthemes as $row_subthemes) {

                $this->db->from('tbl_mof_disability_sections');
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
                    $this->db->from('tbl_mof_disability_items items');
                    $this->db->join('tbl_mof_disability disability', 'items.id=disability.item_id AND disability.mof_id="' . $mof_id . '"', 'left');
                    
                    $this->db->where('active', 1);
                    $this->db->where('section_id', $row_item->id);
        
                    $row_item->items = $this->db->get()->result();
                }
            }
        }

        return $result;
    }

    private function save_disability($disability, $mof_id)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        foreach ($disability as $key => $row) {
            $grade = $row['grade'];
            $item_id = $row['item_id'];

            $mof_disability = $this->db->get_where('tbl_mof_disability', [
                'item_id' => $item_id,
                'mof_id' => $mof_id
            ])->row();

            if ($mof_disability) {

                $this->db->where('mof_id', $mof_id);
                $this->db->where('item_id', $item_id);
                $this->db->update('tbl_mof_disability', [
                    'grade' => $grade
                ]);

            } else {

                $this->db->insert('tbl_mof_disability', [
                    'grade' => $grade,
                    'mof_id' => $mof_id,
                    'item_id' => $item_id
                ]);
            }
        }
    }

    public function disability_values($mof_id)
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
                    $this->db->from('tbl_mof_disability disability');
                    $this->db->join('tbl_mof_disability_items items', 'items.id=disability.item_id');
                    $this->db->join('tbl_mof_disability_sections sections', 'sections.id=items.section_id');
                    $this->db->join('tbl_mof_disability_subthemes subthemes', 'subthemes.id=sections.subtheme_id');
                    $this->db->join('tbl_mof_disability_themes themes', 'themes.id=subthemes.theme_id');
                    $this->db->where('disability.mof_id', $mof_id);
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

    public function is_diff_section_disability_grade($mof_id, $old_disability_grade_values)
    {
        if (!$this->config->item('mof_jp_change_new')) {
            return [];
        }

        $old_disability_value_sum = array_sum(array_column($old_disability_grade_values, 'value'));
        $new_disability_value_sum = array_sum(array_column($this->disability_values($mof_id), 'value'));

        return $old_disability_value_sum != $new_disability_value_sum;
    }

    public function is_allow_disability_eligible($mof_id)
    {
        $disability_values = $this->disability_values($mof_id);

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

    public function create_code(
        $job_title, 
        $company_id
    )
    {       
        $job_title_acronym = substr(mb_strtoupper(trim($job_title)), 0, 3);  

        if (strlen($job_title_acronym) != 3) {
            return false;
        }

        $row = $this->db->query(
            "SELECT id, number_correlative, acronym  FROM tbl_mof_codes WHERE acronym = '" . $job_title_acronym . "' AND company_id = '" . $company_id . "' FOR UPDATE;"
        )->row();

        $acronym = '';
        $number_correlative = 0;
        $code_id = 0;

        if ($row) {
            $code_id = $row->id;
            $acronym = $row->acronym;
            $number_correlative = $row->number_correlative;
        }
        
        if (!$row) {
            $this->db->insert('tbl_mof_codes', [
                'job_title' => $job_title,
                'acronym' => $job_title_acronym,
                'number_correlative' => 1,
                'active' => 1, 
                'company_id' => $company_id
            ]);

            $code_id = $this->db->insert_id();

            if (!$code_id) {
                return false;
            } 

            $acronym = $job_title_acronym;
            $number_correlative = 1;
        }

        $code = $acronym . '-' . str_pad($number_correlative, 3, "0", STR_PAD_LEFT);

        $this->db->where('id', $code_id);
        $this->db->update('tbl_mof_codes', ['number_correlative' => $number_correlative + 1]);

        return $code;
    }

    public function get_factor_valuations($mof_id = 0)
    {
        $mof  = $this->find($mof_id);

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
                'mof_fv.factor_id AS mof_factor_id'
            ]);
            $this->db->from('tbl_factor_valuations fv');
            $this->db->join('tbl_mof_factor_valuations mof_fv', 'fv.id=mof_fv.factor_id AND mof_fv.mof_id="' . $mof_id . '"', 'left');
            $this->db->where('factor_type_id', $factor_type->id);
            
            $this->db->order_by('fv.id', 'ASC');

            $factors = $this->db->get()->result();

            $data[$factor_type->id]['mof_factor_id'] = null;
            $data[$factor_type->id]['mof_factor_name'] = null;
            $data[$factor_type->id]['mof_factor_grade'] = null;
            $data[$factor_type->id]['mof_factor_score'] = null;
            $data[$factor_type->id]['mof_factor_level'] = null;

            if ($factor_type->automatic == 0) {
                foreach ($factors as $factor) {
                    $data[$factor_type->id]['factors'][] = [
                        'id' => $factor->id,
                        'name' => $factor->name,
                        'grade' => $factor->grade,
                        'score' => $factor->score,
                        'level' => $factor->level
                    ];
                    
                    if ($factor->mof_factor_id) {
                        $data[$factor_type->id]['mof_factor_id'] = $factor->mof_factor_id;
                        $data[$factor_type->id]['mof_factor_name'] = $factor->name;
                        $data[$factor_type->id]['mof_factor_grade'] = $factor->grade;
                        $data[$factor_type->id]['mof_factor_score'] = $factor->score;
                        $data[$factor_type->id]['mof_factor_level'] = $factor->level;
                    }
                }
            }

            if ($factor_type->automatic == 1) {
            
                $data[$factor_type->id]['factors'][] = [];
                
                if ($factor_type->id == 1) {
            
                    if ($mof) {
                        $education = $this->Qualification->find($mof->study_grade_min);
                        $data[$factor_type->id]['mof_factor_id'] = 0;
                        $data[$factor_type->id]['mof_factor_name'] = $education->text;
                        $data[$factor_type->id]['mof_factor_grade'] = $education->valorization_grade;
                        $data[$factor_type->id]['mof_factor_score'] = $education->valorization_score;
                    }
                }

                if ($factor_type->id == 2) {
                
                    if ($mof) {
                        $experience = $this->Work_experience->find($mof->experience);
                        $data[$factor_type->id]['mof_factor_id'] = 0;
                        $data[$factor_type->id]['mof_factor_name'] = $experience->name;
                        $data[$factor_type->id]['mof_factor_grade'] = $experience->valorization_grade;
                        $data[$factor_type->id]['mof_factor_score'] = $experience->valorization_score;
                    }
                }

                if ($factor_type->id == 3) {
                 
                    if ($mof) {
                        $job_charge = $this->Job_charge->find($mof->job_charge_id);

                        $skills = $this->db->get_where('tbl_job_charge_skills', [
                            'job_charge_id' => $mof->job_charge_id
                        ])->result();
                
                        $array_skills = [];
                
                        foreach ($skills as $skill) {
                            $array_skills[] = mb_strtoupper($skill->skill_name);
                        }
                        sort($array_skills);

                        $data[$factor_type->id]['mof_factor_id'] = 0;
                        $data[$factor_type->id]['mof_factor_name'] = join(',', $array_skills);
                        $data[$factor_type->id]['mof_factor_grade'] = $job_charge ? $job_charge->valorization_grade : null;
                        $data[$factor_type->id]['mof_factor_score'] = $job_charge ? $job_charge->valorization_score : null;
                    }
                }
            }
        }
        return $data;
    }

    public function save_factor_valuations($factors, $mof_id)
    {
        $this->db->where('mof_id', $mof_id);
        $this->db->delete('tbl_mof_factor_valuations');

        foreach ($factors as $factor_id) {

            if (empty($factor_id)) {
                continue;
            }
            
            $this->db->insert('tbl_mof_factor_valuations', [
                'mof_id' => $mof_id,
                'factor_id' => $factor_id
            ]);
        }
    }

    public function get_factor_total_score($mof_id)
    {
        $mof  = $this->find($mof_id);

        $this->db->select('SUM(fv.score) AS total_score');
        $this->db->from('tbl_mof_factor_valuations mof_fv');
        $this->db->join('tbl_factor_valuations fv', 'fv.id=mof_fv.factor_id');
        $this->db->join('tbl_factor_types ft', 'ft.id=fv.factor_type_id');
        $this->db->where('mof_fv.mof_id', $mof_id);
        $this->db->where('ft.automatic', 0);

        $row = $this->db->get()->row();

        $total_score = $row ? $row->total_score : 0;

        $education = $this->Qualification->find($mof->study_grade_min);
        $total_score+=$education ? $education->valorization_score : 0;

        $experience = $this->Work_experience->find($mof->experience);
        $total_score+=$experience ? $experience->valorization_score : 0;

        $job_charge = $this->Job_charge->find($mof->job_charge_id);
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

    public function get_benefits($company_id = 0, $mof_id = 0)
    {
        $this->db->select([
            'lb.ID AS benefit_id',
            'lb.benefit_name',
            'mlb.minimum',
            'mlb.maximum'
        ]);
        $this->db->from('tbl_laboral_benefits lb');
        $this->db->join(
            'tbl_mof_laboral_benefits mlb', 
            'lb.ID=mlb.benefit_id AND mlb.mof_id=' . $mof_id, 
            'left'
        );
        $this->db->where('lb.active', 1);
        $this->db->where('lb.company_id', $company_id);

        return $this->db->get()->result();
    }
}
