<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_layout_migration
{
    public function __construct()
    {
        $this->load->model('Job_layout');
        $this->load->model('Mof');
        $this->load->model('Job_profile');
    }

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function migrate()
    {
        $this->db->from('__tbl_job_layouts_imports_prod');
        $this->db->where('migrate', 0);
        $this->db->where('error_log', null);

        $limit = $this->config->item('job_layout_migrate_limit') ? $this->config->item('job_layout_migrate_limit') : 0;
        
        $this->db->limit($limit);
        
        $results = $this->db->get()->result();

        //dd($results);

        foreach ($results as $row) {
            $this->create_job_layout($row);
        }
    }

    private function create_job_layout($row)
    {
        $type = trim(strtolower((string)$row->ref_profile_type));

        if ($type != '' && trim((string)$row->ref_profile_code) == '') {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'Debe tener un codigo de referencia'
            ]);
            return false;
        }

        if ($type == '' && trim((string)$row->ref_profile_code) != '') {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'Debe tener un un tipo de referencia'
            ]);
            return false;
        }

        if ($type == 'mof') {
            $data_ref = $this->get_data_mof($row);
        }

        if ($type == 'perfil') {
            $data_ref = $this->get_data_profile($row);
        }

        if ($type == '') {
            $data_ref = $this->get_data_empty($row);
        }

        if ($data_ref === false) {
            return false;
        }

        $this->db->trans_start();

        $data_row = $data_ref['data'];

        $code = $this->Job_layout->create_code($row->company_id);

        if (!$code) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'No se pudo crear el codigo interno'
            ]);
            return false;
        }

        $code_integration = $this->Job_layout->create_code_integration();

        if (!$code_integration) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'No se pudo crear el codigo de integracion'
            ]);
            return false;
        }

        $salary_min =  str_replace('.', '', trim((string)$row->salary_min) ? $row->salary_min : 0);
        $salary_min =  str_replace(',', '.', $salary_min);

        $salary_max =  str_replace('.', '', trim((string)$row->salary_max) ? $row->salary_max : 0);
        $salary_max =  str_replace(',', '.', $salary_max);

        $data_job_layout = [
            'code' => $code,
            //'code_integration' => trim($row->ref_profile_code_integration) ? trim($row->ref_profile_code_integration) : null,
            'code_integration' => $code_integration,
            'created_at' => date('Y-m-d H:i:s'),
            'version' => $data_row->version,
            'job_title' => trim($row->name),
            'risk_criteria' => trim($data_row->risk_criteria), 
            'job_charge_id' => $data_row->job_charge_id,
            'education' => trim($data_row->education),
            'study_grade_req' => $data_row->study_grade_req,
            'education_req_detail' => trim($data_row->education_req_detail),
            'study_grade_min' => $data_row->study_grade_min,
            'education_min_detail' => trim($data_row->education_min_detail),
            'experience' => $data_row->experience,
            'experience_detail' => trim($data_row->experience_detail),
            'last_update' => date('Y-m-d H:i:s'),
            'created_by_recruiter_id' => null,
            'requested' => 0,
            'active' => 1,
            'sunat_code' => trim((string)$data_row->sunat_code),
            'basic_minimum' => $salary_min,
            'basic_maximum' => $salary_max,
            'stereotype' => trim($data_row->stereotype),
            'factor_differentiating' => trim($data_row->factor_differentiating),
            'factor_differentiating_other' => $data_row->factor_differentiating_other,
            'occupational_category_id' => $data_row->occupational_category_id,
            'company_id' => $row->company_id
        ];

        $this->db->insert('tbl_job_layouts', $data_job_layout);

        $job_layout_id = $this->db->insert_id();

        if (!$job_layout_id) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'El layout no se pudo crear'
            ]);

            return false;
        }

        if (isset($data_ref['data_skills'])) {
            $this->Job_layout->add_skills($this->build_skills($data_ref['data_skills']), $job_layout_id);
        }
        
        if (isset($data_ref['data_responsibilities'])) {
            $this->Job_layout->add_responsibilities($this->build_responsibilities($data_ref['data_responsibilities']), $job_layout_id);
        }

        if  (isset($data_ref['data_factor_valuations'])) {
            $this->Job_layout->save_factor_valuations($this->build_factor_valuations($data_ref['data_factor_valuations']), $job_layout_id);
        }
        
        $this->Job_layout->save_disability_options($this->build_disability_options(), $job_layout_id);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() ===  FALSE) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'La transaccion no se pudo confirmar'
            ]);
            return false;
        }

        $this->db->where('id', $row->id);
        $this->db->update('__tbl_job_layouts_imports_prod', [
            'migrate' => 1,
            'job_layout_id' => $job_layout_id,
            'error_log' => ''
        ]);

        return true;
    }

    private function get_data_empty($row) 
    {
        $data = new stdClass();

        $data->version = 'SG-OD-002<br>Versión: 04';
        $data->risk_criteria = '';
        $data->job_charge_id = null;
        $data->education = '';
        $data->study_grade_req = null;
        $data->education_req_detail = '';
        $data->study_grade_min = null;
        $data->education_min_detail = '';
        $data->experience = '';
        $data->experience_detail = '';
        $data->sunat_code = '';
        $data->stereotype = '';
        $data->factor_differentiating = '';
        $data->factor_differentiating_other = null;
        $data->occupational_category_id =  null;

        return [
            'data' => $data
        ];
    }

    private function get_data_mof($row) 
    {
        $code = trim((string)$row->ref_profile_code);
        
        $this->db->from('tbl_mofs');

        if (ctype_digit($code)) {
            $this->db->where('ID', $code);
        } else {
            $this->db->where('code', $code);
        }
        
        $mofs = $this->db->get()->result();

        if (count($mofs) == 0) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'Codigo a buscar ' . $code . ' no existe'
            ]);
            return false;
        }

        if (count($mofs) > 1) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'Codigo a buscar ' . $code . ' existe varias veces'
            ]);
            return false;
        }

        $mof = $mofs[0];

        $responsibilities =  $this->Mof->get_responsibilities_by_mof_id($mof->ID);
        $skills =  $this->Mof->get_skills_by_mof_id($mof->ID);
        $factor_valuations =  $this->get_mof_factor_valuations_by_id($mof->ID);

        return [
            'data' => $mof,
            'data_responsibilities' => $responsibilities,
            'data_skills' => $skills,
            'data_factor_valuations' => $factor_valuations
        ];
    }

    private function get_data_profile($row) 
    {
        $code = trim((string)$row->ref_profile_code);
        
        $this->db->from('tbl_job_profiles');

        if (ctype_digit($code)) {
            $this->db->where('ID', $code);
        } else {
            $this->db->where('code', $code);
        }
        
        $jps = $this->db->get()->result();

        if (count($jps) == 0) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'Codigo a buscar ' . $code . ' no existe'
            ]);
            return false;
        }

        if (count($jps) > 1) {
            $this->db->where('id', $row->id);
            $this->db->update('__tbl_job_layouts_imports_prod', [
                'error_log' => 'Codigo ' . $code . ' a buscar existe varias veces'
            ]);
            return false;
        }

        $jp = $jps[0];

        $responsibilities =  $this->Job_profile->get_responsibilities_by_job_profile_id($jp->ID);
        $skills =  $this->Job_profile->get_skills_by_job_profile_id($jp->ID);
        $factor_valuations =  $this->get_jp_factor_valuations_by_id($jp->ID);

        $jp->job_charge_id = $jp->job_charge_ID;

        return [
            'data' => $jp,
            'data_responsibilities' => $responsibilities,
            'data_skills' => $skills,
            'data_factor_valuations' => $factor_valuations
        ];
    }

    private function  get_mof_factor_valuations_by_id($mof_id)
    {
        $this->db->from('tbl_mof_factor_valuations');
        $this->db->where('mof_id', $mof_id);
        return $this->db->get()->result();
    }

    private function  get_jp_factor_valuations_by_id($id)
    {
        $this->db->from('tbl_job_profile_factor_valuations');
        $this->db->where('job_profile_id', $id);
        return $this->db->get()->result();
    }

    private function build_disability_options()
    {
        $results_disability = $this->Job_layout->get_results_disability_options();
        
        $disability = [];

        foreach ($results_disability as $row_theme) {
 
            foreach ($row_theme->subthemes as $row_subtheme) {
                $disability[$row_subtheme->id]['allow'] = 0;
            }
        }

        return $disability;
    }

    private function build_skills($skills)
    {
        $s = [];

        foreach ($skills as $row) {
            $s[] = $row->skill_name;
        }
        return $s;
    }

    private function build_responsibilities($responsibilities)
    {
        $res = [];

        foreach ($responsibilities as $row) {
            $res[] = $row->responsibility;
        }
        return $res;
    }

    private function build_factor_valuations($factor_valuations)
    {
        $res = [];

        foreach ($factor_valuations as $row) {
            $res[] = $row->factor_id;
        }
        return $res;
    }   
}
