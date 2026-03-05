<?php

class Jobseeker_form_rtps extends CI_Model
{
    public function get_last_record($job_id, $seeker_id)
    {
        $form_rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'job_id' => $job_id,
            'seeker_ID' => $seeker_id
        ])
        ->row();

        if ($form_rtps) {
            return $form_rtps;
        }
        
		return $this->db->from('tbl_seeker_form_rtps')
            ->where('seeker_ID', $seeker_id)
            ->order_by('ID', 'DESC')
            ->get()
            ->row();
    }

	public function get_form_rtps_by_jobseeker_id($jobseeker_id)
	{
        $this->db->select(
            array(
                'form_rtps.*',
                'job_seekers.*',
                'form_rtps.ID AS form_ID',
                'job_seekers.ID AS seeker_ID'
            )
        );
        
		$this->db->from('tbl_seeker_form_rtps form_rtps');
        $this->db->join('tbl_job_seekers job_seekers', 'job_seekers.ID=form_rtps.seeker_ID');

		$this->db->where('form_rtps.seeker_ID', $jobseeker_id);
		
		return $this->db->get()->row();
	}

    public function get_form_rtps_by_id($id)
    {
        $this->db->select(
            array(
                'form_rtps.*',
                'job_seekers.*',
                'form_rtps.ID AS form_ID',
                'job_seekers.ID AS seeker_ID'
            )
        );

        $this->db->from('tbl_seeker_form_rtps form_rtps');
        $this->db->join('tbl_job_seekers job_seekers', 'job_seekers.ID=form_rtps.seeker_ID');

        $this->db->where('form_rtps.ID', $id);
        
        return $this->db->get()->row();
    }

    public function get_rightful_claimants_by_form_id($form_id)
    {
        $this->db->select([
            'rtps_rightful_claimants.*',
            'kinship.name AS kinship_name',
            'kinship_cert.name AS kinship_cert_name'
        ]);

    	$this->db->from('tbl_form_rtps_rightful_claimants rtps_rightful_claimants');
    	$this->db->join('tbl_kinship kinship', 
            'kinship.id=rtps_rightful_claimants.kinship', 
            'left'
        );
        $this->db->join('tbl_kinship_certificates kinship_cert', 
            'kinship_cert.id=rtps_rightful_claimants.kinship_cert_type', 
            'left'
        );
        $this->db->where('form_ID', $form_id);

    	return $this->db->get()->result();
    }

    public function get_form_company($seeker_id, $job_id)
    {
        $this->db->select('twc.name, twc.ruc');
        $this->db->from('tbl_seeker_form_rtps frtps');
        $this->db->join('tbl_post_jobs pj', 'frtps.job_id = pj.ID', 'inner');
        $this->db->join('tbl_staff_requests sr', 'pj.request_ID = sr.ID', 'inner');
        $this->db->join('tbl_workflow_consultants twc', 'twc.code = sr.no_cia AND twc.company_id = sr.company_ID', 'inner');
        $this->db->where('frtps.seeker_ID', $seeker_id);
        $this->db->where('frtps.job_id', $job_id);

        $query = $this->db->get();

    	return $query->row();
    }

    public function get_rightful_claimant_children_by_form_id($form_id)
    {
        $this->db->from('tbl_form_rtps_rightful_claimants');
        $this->db->where('form_ID', $form_id);
        //Si parentesco es Hijo
        $this->db->where('(kinship = 4 OR kinship = 5 OR kinship = 6)');

        return $this->db->get()->result();
    }

    public function count_rightful_claimant_children_by_form_id($form_id)
    {
        $this->db->from('tbl_form_rtps_rightful_claimants');
        $this->db->where('form_ID', $form_id);

        //Si parentesco es Hijo
        $this->db->where('(kinship = 4 OR kinship = 5 OR kinship = 6)');

        return $this->db->count_all_results();
    }

    public function get_rightful_claimant_spouse_by_form_id($form_id)
    {
        $this->db->from('tbl_form_rtps_rightful_claimants');
        $this->db->where('form_ID', $form_id);
        
        //Si parentesco es pareja
        $this->db->where('(kinship = 1 OR kinship = 2)');

        return $this->db->get()->row();
    }

    public function get_form_rtps_candidates($job_id)
    {
        $this->db->select(
            array(
                'form_rtps.*',
                'job_seekers.*',
                'form_rtps.ID AS form_ID',
                'job_seekers.ID AS seeker_ID'
            )
        );
        
        $this->db->from('tbl_recruitment_candidates rs_candidates');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'rs_candidates.seeker_ID=form_rtps.seeker_ID');
        $this->db->join('tbl_job_seekers job_seekers', 'job_seekers.ID=form_rtps.seeker_ID');

        $this->db->where('rs_candidates.job_ID', $job_id);
        $this->db->where('rs_candidates.stage', 7);
        $this->db->where('rs_candidates.discarded', 0);

        return $this->db->get()->result();
    }

    public function get_peruvian_childrens($form_id)
    {
        $this->db->from('tbl_form_rtps_rightful_claimants');
        $this->db->where('form_ID', $form_id);
        //Si parentesco es Hijo
        $this->db->where('(kinship = 4 OR kinship = 5 OR kinship = 6)');
        $this->db->where('document_type', '1');

        return $this->db->get()->result();
    }

    public function export_excel_form_rtps_candidates($job_id)
    {
        $data_rows = [];

        $candidae_list = $this->get_form_rtps_candidates($job_id);

        $data_columns = [
            'Nombre y Apellidos',
            'Documento de identidad',
            'N° documento',
            'Fecha de nacimiento',
            'Lugar de nacimiento',
            'Departamento',
            'Provincia',
            'Distrito',
            'Nacionalidad',
            'Domiciliado',
            'Sexo',
            'Discapacidad',
            'Estado civil',
            'N° de hijos',
            'Teléfono domicilio',
            'Celular',
            'Email',
            'Domicilio',
            'Departamento',
            'Provincia',
            'Distrito',
            'Referencia',
            'Nivel educativo',
            'Especialidad',
            'Grado obtenido',
            '¿Ha trabajado en el Corporativo Overall?',
            'Afiliado a',
            'Fecha de Afiliación',
            'Jubilado',
            'Nombre AFP',
            'CUSPP',
            'Depósito en cuenta',
            'Cheque',
            'Entidad Bancaria (depósito)',
            'Nº de Cuenta',
            'Tipo de cuenta',
            'Peridiocidad de la retribución',
            'Tipo de Remuneración'
        ];
    
        $data_rows[] = $data_columns;

        foreach ($candidae_list as $row) {

            $n_children = $this->Jobseeker_form_rtps->count_rightful_claimant_children_by_form_id(
              $row->form_ID
            );
        
            $data_columns = [
                $row->first_name . ' ' . $row->last_name, //Nombre y Apellidos
                document_type_text($row->document_type), //'Documento de identidad'
                $row->document_number,//'N° documento'
                format_date($row->birthdate, 'd/m/Y'),//'Fecha de nacimiento'
                $row->place_birth, //'Lugar de nacimiento'
                $row->born_department, //'Departamento'
                $row->born_province, //'Provincia',
                $row->born_district, //'Distrito',
                $row->nationality, //'Nacionalidad',
                $row->domiciled ? 'SI' : 'NO', //'Domiciliado',
                gender_text($row->gender), //'Sexo',
                $row->disability ? 'SI' : 'NO', //'Discapacidad',
                civil_status_text($row->civil_status), //'Estado civil',
                $n_children, //'N° de hijos',
                $row->home_phone, //'Teléfono domicilio',
                $row->cell_phone, //'Celular',
                $row->email, //'Email',
                $row->domicile, //'Domicilio',
                $row->department, //'Departamento',
                $row->province, //'Provincia',
                $row->district, //'Distrito',
                $row->reference, //'Referencia',
                $row->level_education, //'Nivel educativo',
                $row->specialty, //'Especialidad',
                $row->degree_obtained, //'Grado obtenido',
                $row->worked_in_overall ? 'SI' : 'NO', //'¿Ha trabajado en el Corporativo Overall?',
                $row->pension_affiliation, //'Afiliado a',
                ($row->pension_affiliation_date != null ? format_date($row->pension_affiliation_date, 'd/m/Y') : ''), //'Fecha de Afiliación',
                ($row->pension_retired != null ? ($row->pension_retired ? 'SI' : 'NO') : ''), //'Jubilado',
                $row->pension_name_afp, //'Nombre AFP',
                $row->pension_cuspp, //'CUSPP',
                'SI', //'Depósito en cuenta',
                'NO', //'Cheque',
                $row->bank_name, //'Entidad Bancaria (depósito)',
                $row->bank_account_number . ' ', //'Nº de Cuenta',
                $row->bank_account_type, //'Tipo de cuenta',
                salary_delivery_period_text($row->payment_period), //'Peridiocidad de la retribución',
                $row->type_remuneration //'Tipo de Remuneración'
            ];    

            $data_rows[] = $data_columns;
        }

        $this->load->library('report_excel_lib');
        $this->report_excel_lib->build($data_rows);
        $this->report_excel_lib->download('Listado-RTPS-postulantes');
    }
}
 