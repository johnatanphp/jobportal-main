<?php

class WS_ca_recruitment_seeker_hire_lib 
{   
    private $period_year;
    private $period_month;

    public function __construct()
    {
        //Load models
        $this->load->model('Recruitment_contract');
        $this->load->model('Recruitment_contracts_synchronization_log');

        //Load lbraries
        $this->load->library('WS_ca/WS_ca_api_overall_identity_token_lib', null, 'WS_ca_api_overall_identity_token_lib');

        $this->period_year = null;
        $this->period_month = null;
    }

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($parameters)
    {
        $this->load->model('Job_layout');

        $seeker_id = $parameters['seeker_id'];
        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            throw new \Exception(
                "Postulante ID no encontrado", 
                1
            );
        }

        $job_id = $parameters['job_id'];
        $rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'seeker_ID' => $seeker_id,
            'job_id' => $job_id
        ])->row();

        if (!$rtps) {
            throw new \Exception(
                "Candidato no tiene la Ficha de declaración jurada de información personal completada.", 
                1
            );
        }
        
        $job = $this->Posted_job->find($job_id);

        if (!$job) {
            throw new \Exception(
                "Codigo empleo no es correcto.", 
                1
            );
        }
        
        $staff_request = $this->Staff_request->find($job->request_ID);

        $no_cia = $staff_request && $staff_request->no_cia ? $staff_request->no_cia : $parameters['no_cia'];
        $parameters['no_cia'] = $no_cia;

        // Registrar trabajador
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 1
        ])->row();

        if ($sync && $sync->active) {
            $register_employee = $this->register_employee($parameters);

            if (!$register_employee) {
                return [
                    'status' => false,
                    'message' => '¡Contratación no se pudo realizar!'
                ];
            }
        }
        
        // Registrar datos academicos
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 2
        ])->row();

        if ($sync && $sync->active) {
            $this->register_academic($parameters);
        }

        // Registrar familiares
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 3
        ])->row();

        if ($sync && $sync->active) {
            $this->register_family_members($parameters);
        }

        //Registrar Foto
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 4
        ])->row();

        if ($sync && $sync->active) {
            $this->register_photo($parameters);
        }
        
        // Registrar CV
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 5
        ])->row();

        if ($sync && $sync->active) {
            $this->register_cv($parameters);
        }

        //Registrar Scan / Foto Documento de identidad
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 6
        ])->row();

        if ($sync && $sync->active) {
            $this->register_scanner_identification_document($parameters);
        }

        //Registrar certificados de trabajo
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 7
        ])->row();

        if ($sync && $sync->active) {
            $this->register_certificate_work($parameters);
        }

        //Registrar Documento identidad familiar
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 8
        ])->row();

        if ($sync && $sync->active) {
            $this->register_family_identification_document($parameters, 8);
        }

        //Certificado familiar
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 9
        ])->row();

        if ($sync && $sync->active) {
            $this->register_certificate_family($parameters, 9);
        }

        // //Certificados de estudios
        // $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
        //     'id' => 10
        // ])->row();

        // if ($sync && $sync->active) {
        //     $this->register_certificate_studies($parameters, 10);
        // }

        // //Permiso firma contrato
        // $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
        //     'id' => 11
        // ])->row();

        // if ($sync && $sync->active && $seeker->document_type != 1) {
        //     $this->register_permission_signs_contracts($parameters, 11);
        // }

        //Otros documentos
        $sync = $this->db->get_where('tbl_recruitment_contracts_synchronizations', [
            'id' => 12
        ])->row();

        if ($sync && $sync->active) {
            $this->register_other_documents($parameters, 12);
        }

        //Si todos los datos fueron enviado marcalo como sincronizado
        $this->update_synchronization($job_id, $seeker_id);
        
        return [
            'status' => true,
            'message' => 'Contratación ha sido realizada'
        ];
    }

    private function register_employee($parameters)
    {
        $job_id = $parameters['job_id'];
        $seeker_id = $parameters['seeker_id'];

        //Buscar datos para la contratacion del postulante
        $seeker = $this->get_job_seeker_to_hire($job_id, $seeker_id);

        if (!$seeker) {
            throw new \Exception("Candidato no está habilitado para ser contratado", 1);
        }
        
        if (strtotime($parameters['contract_start_date']) < strtotime(date('Y-m-d'))) {
            throw new \Exception("La fecha de inicio de contrato debe ser igual o mayor a la fecha actual", 1);
        }

        if (strtotime($parameters['contract_end_date']) < strtotime($parameters['contract_start_date'])) {
            throw new \Exception("La fecha fin de contrato debe ser igual o mayor a la fecha de inicio de contrato", 1);
        }

        $job_layout = $this->Job_layout->find($seeker->job_layout_id);

        //dd($seeker);

        $env_prod = $this->config->item('env') == 'production';

        //Obtener total cantidad de derechohabientes
        $this->db->select([
            'rightful_claimants.document_number',
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants rightful_claimants');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps.ID=rightful_claimants.form_ID');
        $this->db->where('form_rtps.seeker_ID', $seeker_id);
        $this->db->where('form_rtps.job_id', $job_id);
        $rightful_claimants_count = $this->db->count_all_results();

        //Obtener total de hijos
        $this->db->select([
            'rightful_claimants.document_number',
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants rightful_claimants');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps.ID=rightful_claimants.form_ID');
        $this->db->where('form_rtps.seeker_ID', $seeker_id);
        $this->db->where('form_rtps.job_id', $job_id);
        $this->db->where_in('rightful_claimants.kinship', [4, 5]);
        $children_count = $this->db->count_all_results();

        //$contract_type_model = explode('||', $parameters['contract_type_model']);

        $consultant_code = get_consultant_code($seeker->no_cia);

		$fields = [
            "database" => $this->config->item('ca_api_database'),
            "cod_empresa" => $consultant_code,
            "cod_categoria" => $this->get_employee_category_code($parameters['employee_category_id']),
            "cod_trabajador" => $seeker->document_number, 
            "apellido_paterno" => mb_strtoupper($seeker->paternal_last_name),
            "apellido_materno" => mb_strtoupper($seeker->maternal_last_name),
            "nombres" => mb_strtoupper($seeker->first_name),
            "cod_tipo_doc" => $seeker->document_sunat_code,
            "nro_doc" => $seeker->document_number,
            "cod_sexo" => $this->get_gender_code($seeker->gender),
            "cod_estado_civil" => $this->get_civil_status_code($seeker->civil_status),
            "fecha_nacimiento" => date('d/m/Y', strtotime($seeker->dob)),
            "cod_ubigeo_nac" => trim($seeker->born_department) != '' ? $this->get_ubigeo_code($seeker->born_department . ', ' . $seeker->born_province . ', ' . $seeker->born_district) : $this->get_ubigeo_code($seeker->city),
            "cod_afp" => $seeker->pension_affiliation == 'AFP' ? $this->get_afp_code($seeker->pension_name_afp) : '000006',
            "cuispp" => $seeker->pension_affiliation == 'AFP' ? $seeker->pension_cuspp : '',
            "fecha_afiliacion" => $seeker->pension_affiliation == 'AFP' ? date('d/m/Y', strtotime($seeker->pension_affiliation_date)) : '',
            "flg_jubilado" => $seeker->pension_affiliation == 'AFP' ? ($seeker->pension_retired ?  'S' : 'N') : 'N',
            "flg_domicilio" => $seeker->domiciled ? 'S' : 'N',
            "flg_discapacidad" => $seeker->rtps_disability ? 'S': 'N',
            "cod_nivel_academico" => $this->get_academic_level_code($seeker->degree_obtained),
            "flg_madre_trabajadora" => "N",
            "cod_tipo_planilla" => "01",
            "cod_proyecto" => $this->get_proyect_code($seeker->cod_clie),
            "cod_unidad_negocio" => $seeker->business_unit_code,
            "cod_centro_costo" => $env_prod ? $seeker->cost_center_code : $seeker->cost_center_code,
            "cod_area" => "-1",
            "cod_localidad" => "-1",
            "cod_cargo" => $job_layout ? $consultant_code . $job_layout->code_integration : '01P00002',
            "fecha_ingreso" => date("d/m/Y", strtotime($parameters['contract_start_date'])), //Ajustar
            "cod_motivo_ingreso" => $this->get_hiring_type_reason_code($seeker->reason_request_code),
            "sueldo" => number_format($seeker->monthly_gross_salary, 2, ".", ""),
            "cod_moneda_basico" => "01",
            "flg_pago_cuenta" => trim($seeker->bank_name) != '' ? 'S': 'N',
            "num_cuenta_pago" => "",
            "cod_banco_pago" => $this->get_bank_name_code($seeker->bank_name),
            "cod_moneda_pago" => $this->get_currency_code('PEN'),
            "cod_tipo_trabajador" => $parameters['employee_type_id'],
            "cod_estado_laboral" => "11",
            "cod_eps" => "0",
            "flg_sctr_salud" => "N",
            "sctr_salud" => "0",
            "flg_sctr_pension" => "N",
            "sctr_pension" => "0",
            "fecha_sctr_inicio" => "",
            "fecha_sctr_fin" => "",
            "essalud_vida" => "0",
            "flg_vida_ley" => "N",
            "cod_vida_ley" => "",
            "tasa_vida_ley" => "",
            "flg_sindicato" => "N",
            "flg_regimen_alternativo" => "N",
            "flg_jornada_maxima" => "N",
            "flg_horario_nocturno" => "N",
            "cod_alcance_cargo" => "00",
            "flg_asignacion_familiar" => $rightful_claimants_count > 0 ? "S" : "N",
            "flg_reloj" => "N",
            "fotocheck" => $seeker->document_number,
            "cod_tipo_contrato" => trim($parameters['contract_type_model_code'] ?? ''),
            "inicio_contrato" => date("d/m/Y", strtotime($parameters['contract_start_date'])),
            "fin_contrato" => date("d/m/Y", strtotime($parameters['contract_end_date'])),
            "cod_via" => $seeker->way_id ? $this->get_way_code($seeker->way_id) : '-1',
            "direccion" => $seeker->present_address,
            "numero" => $seeker->address_number ? $seeker->address_number : '',
            "interior" => $seeker->domicile_interior ? $seeker->domicile_interior : '',
            "cod_zona" => "-1",
            "cod_ubigeo" => $this->get_ubigeo_code($seeker->city),
            "referencia" => "",
            "telefonos" => "",
            "telefono2" => trim($this->remove_phone_prefix($seeker->mobile)),
            "telefono3" => "",
            "fax" => "",
            "numero_hijos" => strval($children_count),
            "fecha_cese" => "",
            "cod_motivo_cese" => "",
            "tipo_comision_afp" => "",
            "flg_adelanto" => "N",
            "porcen_adelanto" => "0",
            "monto_adelanto" => "0",
            "correo_personal" => $seeker->email,
            "correo_corporativo" => "",
            "usuario" => $this->config->item('ca_api_cod_user'),
            "dni_jefe" => "",
            "condicion_laboral" => "C",
            "cod_banco_cts" => $this->get_bank_name_code($seeker->payment_cts_bank_name),
            "cod_moneda_cts" => $this->get_currency_code($seeker->payment_cts_currency),
            "num_cuenta_cts" => '',
            "flg_interbancario_cts" => "N",
            "cod_banco_cts_interbancario" => "",
            "num_cuenta_cts_interbancario" => "",
            "cod_pais_emisor" => "",
            "cod_nacionalidad" => $this->get_nationality_code($seeker->nationality), //Si el documento es DNI Nacionalidad es Peru
            "flg_interbancario_pago" => "N",
            "cod_banco_pago_interbancario" => "",
            "num_cuenta_pago_interbancario" => "",
            "cod_cliente_ruc" => "",
            "cod_Establecimiento" => "",
            "codigo_estructura_costo" => trim($seeker->eecc_code) != '' ? trim($seeker->eecc_code) : '',
            "linea_estructura_costo" => trim($seeker->eecc_form_id) != '' ? trim($seeker->eecc_form_id) : ''
        ];

        $headers = [
            "Content-Type: application/json"
        ];

		$url = $this->config->item('ca_api_url') . "/Personal/RegistraTrabajador";
    
		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($fields),
			CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config->item('ca_api_auth_user') . ':' . $this->config->item('ca_api_auth_password'),
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

        //Registrar postulante en sistema de nomina
		$response = curl_exec($ch);

        if ($response === false) {
            $response = curl_error($ch);
        }

		curl_close($ch);

        $response_api = json_decode($response);

        $this->Recruitment_contracts_synchronization_log->register([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 1,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($fields),
            'response' => $response,
            'success' => isset($response_api->Success) && $response_api->Success
        ]);
       
        //Marcar postulante como contratado
        if (isset($response_api->Success) && $response_api->Success) {
            $this->create_contract($seeker, $parameters);
            return true;
        }
        
        return false;
    }

    private function register_academic($parameters)
    {   
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $seeker = $this->Job_seeker->find($seeker_id);

        $this->db->select([
            'seeker_ID',	
            'degree_level',	
            'degree_title',	
            'major',	
            'institude',	
            'country',	
            'city',	
            'start_date',	
            'end_date',
            'institution_educational_type_id',
            'institution_type_id',
            'institution_educational_class_id',
            'institution',
            'career',
            'tuition_number'
        ]);
        $this->db->from('tbl_seeker_academic');
        $this->db->where('seeker_ID', $seeker_id);
        $results = $this->db->get()->result();

        if (count($results) == 0) {
            return true;
        }

        $detail_seeker_academic = [];

        foreach ($results as $seeker_academic) {

            if (!$seeker_academic->end_date) {
                continue;
            }

            $start_date = $seeker_academic->start_date;
            $end_date =  $seeker_academic->end_date;

            $diff = abs(strtotime($end_date) - strtotime($start_date));
            $diff_years = floor($diff / (365*60*60*24));
            $diff_months = floor(($diff - $diff_years * 365*60*60*24) / (30*60*60*24));
        
            $seeker_academic = [
                "Cod_Nivel_Academico" => $this->get_academic_level_code($seeker_academic->degree_title),
                "Titulo" => $seeker_academic->major,
                "Cod_Pais" => $this->get_country_code($seeker_academic->country),
                "Cod_Institucion_Educativa" => $seeker_academic->institution_educational_type_id ? $seeker_academic->institution_educational_type_id : "1",
                "Cod_Tipo_Institucion" => $seeker_academic->institution_type_id ? $seeker_academic->institution_type_id : "9",
                "Cod_Clase_Institucion_Educativa" => $seeker_academic->institution_educational_class_id ? $seeker_academic->institution_educational_class_id : $this->get_educational_institution_class_code($seeker_academic->degree_title),
                "Cod_Carrera" => $seeker_academic->career ? $seeker_academic->career : "999999",
                "Cod_Institucion_Sunat" => $seeker_academic->institution ? $seeker_academic->institution : "",
                "Numero_Colegiatura" => $seeker_academic->tuition_number ? $seeker_academic->tuition_number : "",
                "Desde" => date('d/m/Y', strtotime($seeker_academic->start_date)),
                "Hasta" => $seeker_academic->end_date ? date('d/m/Y', strtotime($seeker_academic->end_date)) : "",
                "Year_Egreso" => $seeker_academic->end_date ? date('Y', strtotime($seeker_academic->end_date)) : "",
                "Duracion_Year" => strval($diff_years),
                "Duracion_Meses" => strval($diff_months),
                "Duracion_Dias" => '0',
                "Cod_Institucion_Educativa_Otros" => ""
            ];

            $detail_seeker_academic[] = $seeker_academic;
        }

        if (count($detail_seeker_academic) == 0) {
            return true;
        }

        $academic_data[] = [
            "NumDocIdent_Colaborador" => $seeker->document_number,
            "Detalle" => $detail_seeker_academic
        ];

        $fields = [
            "database" => $this->config->item('ca_api_database'),
            "Cod_Empresa" => get_consultant_code($no_cia),
            "Cod_Usuario" => $this->config->item('ca_api_cod_user'),
            "Formacion_Academica" => $academic_data
        ];

        //dd($fields);

        $url = $this->config->item('ca_api_url') . "/Personal/RegistraFormacionAcademica";

        $headers = [
            "Content-Type: application/json"
        ];

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($fields),
			CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config->item('ca_api_auth_user') . ':' . $this->config->item('ca_api_auth_password'),
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);
		$response = curl_exec($ch);

        if ($response === false) {
            $response = curl_error($ch);
        }

		curl_close($ch);

		$response_api = json_decode($response);

        $this->Recruitment_contracts_synchronization_log->register([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 2,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($fields),
            'response' => $response,
            'success' => isset($response_api->Success) && $response_api->Success
        ]);

        return true;
    }

    private function register_family_members($parameters)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $seeker = $this->Job_seeker->find($seeker_id);

        $this->db->select([
            'seeker.document_number AS seeker_document_number',
            'seeker.present_address AS seeker_present_address',
            'seeker.city AS seeker_city',
            'doc_type.sunat_code AS document_sunat_code',
            'kinship.iplani_code AS family_bond_code', 
            'kinship_certificates.iplani_code AS family_bond_support_code',
            'kinship_certificates.id AS kinship_certificate_id',
            'rightful_claimants.first_name',
            'rightful_claimants.document_number',
            'rightful_claimants.document_type',
            'rightful_claimants.paternal_last_name',
            'rightful_claimants.maternal_last_name',
            'rightful_claimants.gender',
            'rightful_claimants.birthdate',
            'rightful_claimants.kinship',
            'rightful_claimants.domicile',
            'rightful_claimants.ubigeo', 
            'rightful_claimants.nationality_id', 
            'rightful_claimants.domicile_way_id', 
            'rightful_claimants.domicile_number', 
            'rightful_claimants.domicile_interior', 
            'rightful_claimants.live_same_domicile',
            'rightful_claimants.kinship_cert_code AS kinship_cert_code'
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants rightful_claimants');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps.ID=rightful_claimants.form_ID');
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID=form_rtps.seeker_ID');
        $this->db->join('tbl_identity_document_types doc_type', 
            'doc_type.id=rightful_claimants.document_type'
        );
        $this->db->join('tbl_kinship kinship', 
            'kinship.id=rightful_claimants.kinship',
            'left'
        );
        $this->db->join('tbl_kinship_certificates kinship_certificates', 
            'kinship_certificates.id=rightful_claimants.kinship_cert_type',
            'left'
        );
        $this->db->where('form_rtps.seeker_ID', $seeker_id);
        $this->db->where('form_rtps.job_id', $job_id);

        $results = $this->db->get()->result();

        if (count($results) == 0) {
            return true;
        }

        $detail_seeker_family = [];

        foreach ($results as $seeker_family) {

            $seeker_family_data = [
                "Apellidos_Paterno_Familiar" => $seeker_family->paternal_last_name,
                "Apellidos_Materno_Familiar" => $seeker_family->maternal_last_name,
                "Nombres_Familiar" => $seeker_family->first_name,
                "Cod_documento_Identidad" => $seeker_family->document_sunat_code,
                "NumDocIdent_Familiar" => $seeker_family->document_number,
                "Estado_Familiar" => "A",
                "Cod_Vinculo_Familiar" => $seeker_family->family_bond_code,
                "Cod_Sustenta_Vinculo" => $seeker_family->family_bond_support_code,
                "Cod_Parentesco" => $this->get_kinship_code($seeker_family->kinship),
                "Telefonos" => trim($seeker->mobile) != '' ? trim($this->remove_phone_prefix($seeker->mobile)) : '0',
                "Fecha_nacimiento_Familiar" => date('d/m/Y', strtotime($seeker_family->birthdate)),
                "Flg_Carga_Familiar" => "S",
                "Flg_Beneficiario" => "S",
                "Flg_Domicilio" => "S",
                "Nacionalidad" => $this->get_family_nationality_code($seeker_family->nationality_id),
                "Flg_Genero" => $this->get_gender_code($seeker_family->gender),
                "Numero_DocPaternidad" => $seeker_family->kinship_certificate_id  == 10 || $seeker_family->kinship_certificate_id == 12 ? trim($seeker_family->kinship_cert_code) : '',
                "Fecha_Alta" => date('d/m/Y'),
                "Fecha_Baja" => "",
                "Cod_Motivo_Cese" => "",
                "Numero_RIHME" => $seeker_family->kinship_certificate_id == 4 ? trim($seeker_family->kinship_cert_code) : '',
                "Cod_Via" => $seeker_family->live_same_domicile ? ($seeker->way_id ? $this->get_way_code($seeker->way_id) : '-1') : ($seeker_family->domicile_way_id ? $this->get_way_code($seeker_family->domicile_way_id) : '-1'),
                "Cod_Ubigeo" => $seeker_family->live_same_domicile ? $this->get_ubigeo_code($seeker->city) : $this->get_ubigeo_code($seeker_family->ubigeo),
                "Direccion" => $seeker_family->live_same_domicile ? $seeker_family->seeker_present_address : $seeker_family->domicile,
                "Numero" => $seeker_family->live_same_domicile ? trim((string)$seeker->address_number) : trim((string)$seeker_family->domicile_number),
                "Interior" => $seeker_family->live_same_domicile ? trim((string)$seeker->domicile_interior) : trim((string)$seeker_family->domicile_interior),
                "Cod_Zona" => "-1",
                "Zona" => "",
                "Referencia" => "",
                "Flg_EPS" => "N",
                "Fecha_Afiliacion_EPS" => ""
            ];

            $detail_seeker_family[] = $seeker_family_data;
        }

        $family_data[] = [
            "NumDocIdent_Colaborador" => $seeker->document_number,
            "Detalle" => $detail_seeker_family
        ];

        $fields = [
            "database" => $this->config->item('ca_api_database'),
            "Cod_Empresa" => get_consultant_code($no_cia),
            "Cod_Usuario" => $this->config->item('ca_api_cod_user'),
            "Familiares" => $family_data
        ];

        //dd($fields);

        $url = $this->config->item('ca_api_url') . "/Personal/RegistraFamiliares";

        $headers = [
            "Content-Type: application/json"
        ];

		$curl_options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($fields),
			CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $this->config->item('ca_api_auth_user') . ':' . $this->config->item('ca_api_auth_password'),
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);
		$response = curl_exec($ch);

        if ($response === false) {
            $response = curl_error($ch);
        }

		curl_close($ch);

		$response_api = json_decode($response);

        $this->Recruitment_contracts_synchronization_log->register([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 3,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($fields),
            'response' => $response,
            'success' => isset($response_api->Success) && $response_api->Success
        ]);

        return true;
    }

    public function register_photo($parameters)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'photo.file_path',
            'seekers.document_number AS seeker_document_number'
        ]);
        $this->db->from('tbl_recruitment_contract_documents photo');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID=photo.seeker_id');
        $this->db->where('photo.seeker_id', $seeker_id);
        $this->db->where('photo.document_id', 2); //Foto para Overall
        $this->db->order_by('photo.created_at', 'DESC');

        $photo = $this->db->get()->row();

        if (!$photo) {
            return true;
        }

        $file_tmp_data = file_get_contents(file_url($photo->file_path));

        if (!$file_tmp_data) {
            return false;
        }
        
        $file_tmp_path = tempnam(sys_get_temp_dir(), 'rphoto');
        @file_put_contents($file_tmp_path, $file_tmp_data);
        $file_contet_type = mime_content_type($file_tmp_path);

        $token = $this->WS_ca_api_overall_identity_token_lib->generate();

        $headers = [
			"Authorization: Bearer " . $token,
			"Content-Type: multipart/form-data"
        ];

		$fields = [
			'cod_empresa' => get_consultant_code($no_cia),
			'numero_docid' => $photo->seeker_document_number,
			'file' => curl_file_create($file_tmp_path, $file_contet_type, 'Foto.' . file_ext($photo->file_path))
		];

		$url = $this->config->item('ca_api_base_url') . "/photos/" . $this->config->item('ca_api_base_application_name') . "/photos";
        
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_HTTPHEADER => $headers
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);

        if ($response === false) {
            $response = curl_error($ch);
        }

		curl_close($ch);

        @unlink($file_tmp_path);

        $response_api = json_decode($response);

        $this->Recruitment_contracts_synchronization_log->register([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 4,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($fields),
            'response' => $response,
            'success' => isset($response_api->success) && $response_api->success
        ]);

        return true;
    }

    private function register_cv($parameters)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'cv.file_name AS file_path',
            'seekers.document_number AS seeker_document_number'
        ]);
        $this->db->from('tbl_seeker_resumes cv');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID=cv.seeker_ID');
        $this->db->where('cv.seeker_ID', $seeker_id);
        $this->db->order_by('cv.dated', 'DESC');
        $cv_row = $this->db->get()->row();

        if (!$cv_row) {
            return true;
        }

        //dd($cv_row);

        $file_tmp_data = file_get_contents(file_url($cv_row->file_path));
        $file_tmp_path = tempnam(sys_get_temp_dir(), 'rcv');
        @file_put_contents($file_tmp_path, $file_tmp_data);
        $file_contet_type = mime_content_type($file_tmp_path);

        $token = $this->WS_ca_api_overall_identity_token_lib->generate();

        $headers = [
			"Authorization: Bearer " . $token,
			"Content-Type: multipart/form-data"
        ];

		$fields = [
			"cod_empresa" => get_consultant_code($no_cia),
			"numero_docid" => $cv_row->seeker_document_number,
            "descripcion" => "CV Candidato",
            "flg_firmado" => "S",
            "cod_tipo_documento" => "02",
			"file" => curl_file_create($file_tmp_path, $file_contet_type, 'Cv.' . file_ext($cv_row->file_path))
		];

		$url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";
    
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_HTTPHEADER => $headers
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);

        if ($response === false) {
            $response = curl_error($ch);
        }

		curl_close($ch);

        @unlink($file_tmp_path);

        $response_api = json_decode($response);

        $this->Recruitment_contracts_synchronization_log->register([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 5,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($fields),
            'response' => $response,
            'success' => isset($response_api->success) && $response_api->success
        ]);

        return true;
    }

    private function register_scanner_identification_document($parameters)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'document.path AS file_path',
            'seekers.document_number AS seeker_document_number',
            'doc_types.name AS document_name',
            'doc_types.sunat_code AS document_type_sunat_code'
        ]);
        $this->db->from('tbl_seeker_identification_documents document');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID=document.seeker_ID');
        $this->db->join('tbl_identity_document_types doc_types', 'doc_types.id=document.doc_type AND seekers.document_type=document.doc_type');
        $this->db->where('document.seeker_ID', $seeker_id);
        
        $document = $this->db->get()->row();

        //dd($document);

        if (!$document) {
            return true;
        }

        $file_tmp_data = file_get_contents(file_url($document->file_path));
        $file_tmp_path = tempnam(sys_get_temp_dir(), 'rsid');
        @file_put_contents($file_tmp_path, $file_tmp_data);
        $file_contet_type = mime_content_type($file_tmp_path);

        //dd($file_tmp_path);

        $token = $this->WS_ca_api_overall_identity_token_lib->generate();

        $headers = [
			"Authorization: Bearer " . $token,
			"Content-Type: multipart/form-data"
        ];

		$fields = [
			"cod_empresa" => get_consultant_code($no_cia),
			"numero_docid" => $document->seeker_document_number,
            "descripcion" => $document->document_name,
            "flg_firmado" => "S",
            "cod_tipo_documento" => '02',
			"file" => curl_file_create($file_tmp_path, $file_contet_type, 'Documento.' . file_ext($document->file_path))
		];

		$url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";
    
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_HTTPHEADER => $headers
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
        
        if ($response === false) {
            $response = curl_error($ch);
        }
        
        @unlink($file_tmp_path);
        curl_close($ch);

        $response_api = json_decode($response);

        $this->Recruitment_contracts_synchronization_log->register([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 6,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($fields),
            'response' => $response,
            'success' => isset($response_api->success) && $response_api->success
        ]);

        return true;
    }

    public function register_certificate_work($parameters)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'cert_experiences.ID AS id',
            'cert_experiences.attached_certificate AS file_path',
            'cert_experiences.job_title',
            'seekers.document_number AS seeker_document_number'
        ]);
        $this->db->from('tbl_seeker_experience cert_experiences');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID=cert_experiences.seeker_ID');
        $this->db->where('cert_experiences.seeker_ID', $seeker_id);
        $cert_experiences = $this->db->get()->result();

        foreach ($cert_experiences as $row) {

            if (trim($row->file_path) == '') {
                continue;
            }

            $file_tmp_data = file_get_contents(file_url($row->file_path));
            $file_tmp_path = tempnam(sys_get_temp_dir(), 'cexperiencies');
            @file_put_contents($file_tmp_path, $file_tmp_data);
            $file_contet_type = mime_content_type($file_tmp_path);

            $token = $this->WS_ca_api_overall_identity_token_lib->generate();

            $headers = [
                "Authorization: Bearer " . $token,
                "Content-Type: multipart/form-data"
            ];

            $fields = [
                "cod_empresa" => get_consultant_code($no_cia),
                "numero_docid" => $row->seeker_document_number,
                "descripcion" => "Certificado laboral - " . $row->job_title,
                "flg_firmado" => "S",
                "cod_tipo_documento" => "02",
                "file" => curl_file_create($file_tmp_path, $file_contet_type, 'Certificado-laboral.' . file_ext($row->file_path))
            ];

            $env_prod = $this->config->item('env') == 'production';

            $url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";
    
            $options = [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => $headers
            ];

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);

            if ($response === false) {
                $response = curl_error($ch);
            }

            curl_close($ch);

            @unlink($file_tmp_path);

            $response_api = json_decode($response);

            $this->Recruitment_contracts_synchronization_log->register([
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'sync_id' => 7,
                'description' => $row->job_title,
                'ref_id' => $row->id,
                'url' => $url,
                'created_at' => date('Y-m-d H:i:s'),
                'parameters' => json_encode($fields),
                'response' => $response,
                'success' => isset($response_api->success) && $response_api->success
            ]);
        }

        return true;
    }

    public function register_certificate_studies($parameters, $sync_id)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'cert_academic.ID AS id',
            'cert_academic.attached_certificate AS file_path',
            'cert_academic.degree_title',
            'seekers.document_number AS seeker_document_number'
        ]);
        $this->db->from('tbl_seeker_academic cert_academic');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID=cert_academic.seeker_ID');
        $this->db->where('cert_academic.seeker_ID', $seeker_id);
        $cert_academic = $this->db->get()->result();

        foreach ($cert_academic as $row) {

            if (trim($row->file_path) == '' || trim($row->degree_title) == '') {
                continue;
            }

            $file_tmp_data = file_get_contents(file_url($row->file_path));
            $file_tmp_path = tempnam(sys_get_temp_dir(), 'cestudies');
            @file_put_contents($file_tmp_path, $file_tmp_data);
            $file_contet_type = mime_content_type($file_tmp_path);

            $token = $this->WS_ca_api_overall_identity_token_lib->generate();

            $headers = [
                "Authorization: Bearer " . $token,
                "Content-Type: multipart/form-data"
            ];

            $fields = [
                "cod_empresa" => get_consultant_code($no_cia),
                "numero_docid" => $row->seeker_document_number,
                "descripcion" => "Certificado estudio - " . $row->degree_title,
                "flg_firmado" => "S",
                "cod_tipo_documento" => "02",
                "file" => curl_file_create($file_tmp_path, $file_contet_type, 'Certificado-estudio.' . file_ext($row->file_path))
            ];

            $env_prod = $this->config->item('env') == 'production';

            $url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";        

            $options = [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => $headers
            ];

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);

            if ($response === false) {
                $response = curl_error($ch);
            }

            curl_close($ch);

            @unlink($file_tmp_path);

            $response_api = json_decode($response);

            $this->Recruitment_contracts_synchronization_log->register([
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'sync_id' => $sync_id,
                'description' => $row->degree_title,
                'ref_id' => $row->id,
                'url' => $url,
                'created_at' => date('Y-m-d H:i:s'),
                'parameters' => json_encode($fields),
                'response' => $response,
                'success' => isset($response_api->success) && $response_api->success
            ]);
        }

        return true;
    }

    public function register_family_identification_document($parameters, $sync_id)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'rightful_claimants.ID AS id',
            'rightful_claimants.first_name',
            'rightful_claimants.document_number',
            'rightful_claimants.attached_document_number AS file_path',
            'doc_type.name AS document_name',
            'doc_type.sunat_code AS document_sunat_code',
            'seeker.document_number AS seeker_document_number',
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants rightful_claimants');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps.ID=rightful_claimants.form_ID');
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID=form_rtps.seeker_ID');
        $this->db->join('tbl_identity_document_types doc_type', 
            'doc_type.id=rightful_claimants.document_type'
        );
        $this->db->where('form_rtps.seeker_ID', $seeker_id);
        $this->db->where('form_rtps.job_id', $job_id);

        $results = $this->db->get()->result();
       
        foreach ($results as $row) {

            if (trim($row->file_path) == '') {
                continue;
            }

            $file_tmp_data = file_get_contents(file_url($row->file_path));
            $file_tmp_path = tempnam(sys_get_temp_dir(), 'docidenfamily');
            @file_put_contents($file_tmp_path, $file_tmp_data);
            $file_contet_type = mime_content_type($file_tmp_path);
    
            //dd($file_tmp_path);
    
            $token = $this->WS_ca_api_overall_identity_token_lib->generate();
    
            $headers = [
                "Authorization: Bearer " . $token,
                "Content-Type: multipart/form-data"
            ];
    
            $fields = [
                "cod_empresa" => get_consultant_code($no_cia),
                "numero_docid" => $row->seeker_document_number,
                "descripcion" => 'Documento identidad familiar - ' . $row->document_name . ' ' . $row->document_number,
                "flg_firmado" => "S",
                "cod_tipo_documento" => '02',
                "file" => curl_file_create($file_tmp_path, $file_contet_type, 'Documento-identidad-familiar-' . $row->document_number . '.' . file_ext($row->file_path))
            ];
    
            $env_prod = $this->config->item('env') == 'production';
            
            $url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";
    
            $options = [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => $headers
            ];
    
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            
            if ($response === false) {
                $response = curl_error($ch);
            }
            
            @unlink($file_tmp_path);
            curl_close($ch);
    
            $response_api = json_decode($response);
    
            $this->Recruitment_contracts_synchronization_log->register([
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'sync_id' => $sync_id,
                'description' => $row->document_name . ' ' . $row->document_number,
                'ref_id' => $row->id,
                'url' => $url,
                'created_at' => date('Y-m-d H:i:s'),
                'parameters' => json_encode($fields),
                'response' => $response,
                'success' => isset($response_api->success) && $response_api->success
            ]);    
        }

        return true;
    }

    public function register_certificate_family($parameters, $sync_id)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'rightful_claimants.ID AS id',
            'rightful_claimants.first_name',
            'rightful_claimants.document_number',
            'rightful_claimants.kinship_cert_attached AS file_path',
            'seeker.document_number AS seeker_document_number',
            'kinship_certificates.name AS cert_name',
            'doc_type.name AS document_name'
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants rightful_claimants');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps.ID=rightful_claimants.form_ID');
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID=form_rtps.seeker_ID');
        $this->db->join('tbl_identity_document_types doc_type', 
            'doc_type.id=rightful_claimants.document_type'
        );
        $this->db->join('tbl_kinship_certificates kinship_certificates', 
            'kinship_certificates.id=rightful_claimants.kinship_cert_type',
            'left'
        );

        $this->db->where('form_rtps.seeker_ID', $seeker_id);
        $this->db->where('form_rtps.job_id', $job_id);

        $results = $this->db->get()->result();
       
        foreach ($results as $row) {

            if (trim($row->file_path) == '' || trim($row->cert_name) == '') {
                continue;
            }

            $file_tmp_data = file_get_contents(file_url($row->file_path));
            $file_tmp_path = tempnam(sys_get_temp_dir(), 'certfamily');
            @file_put_contents($file_tmp_path, $file_tmp_data);
            $file_contet_type = mime_content_type($file_tmp_path);
    
            //dd($file_tmp_path);
    
            $token = $this->WS_ca_api_overall_identity_token_lib->generate();
    
            $headers = [
                "Authorization: Bearer " . $token,
                "Content-Type: multipart/form-data"
            ];
    
            $fields = [
                "cod_empresa" => get_consultant_code($no_cia),
                "numero_docid" => $row->seeker_document_number,
                "descripcion" => 'Certificado familia - ' .  $row->document_name . ' ' . $row->document_number . ' - '. $row->cert_name,
                "flg_firmado" => "S",
                "cod_tipo_documento" => '02',
                "file" => curl_file_create($file_tmp_path, $file_contet_type, 'Certificado-familia-' . $row->document_number . '.' . file_ext($row->file_path))
            ];
    
            $env_prod = $this->config->item('env') == 'production';

            $url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";
                
            $options = [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => $headers
            ];
    
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            
            if ($response === false) {
                $response = curl_error($ch);
            }
            
            @unlink($file_tmp_path);
            curl_close($ch);
    
            $response_api = json_decode($response);
    
            $this->Recruitment_contracts_synchronization_log->register([
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'sync_id' => $sync_id,
                'description' => $row->document_name . ' ' . $row->document_number,
                'ref_id' => $row->id,
                'url' => $url,
                'created_at' => date('Y-m-d H:i:s'),
                'parameters' => json_encode($fields),
                'response' => $response,
                'success' => isset($response_api->success) && $response_api->success
            ]);    
        }

        return true;
    }

    private function register_other_documents($parameters, $sync_id)
    {
        $job_id = $parameters['job_id'];
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];
        $job = $this->Posted_job->find($job_id);
        $seeker = $this->Job_seeker->find($seeker_id);

        $this->db->select([
            'doc_types.id',
            'doc_types.name'
        ]);
        $this->db->from('tbl_recruitment_contract_document_types doc_types');
        $this->db->where('doc_types.option_type_id', 1);// Solo tipo adjunto
        $this->db->where('doc_types.company_id', $job->company_ID);
        $this->db->where('doc_types.active', 1);
        $this->db->where_not_in('id', [2]); //Ignorar Foto

        $doc_results = $this->db->get()->result();

        foreach ($doc_results as $doc_row) {

            $this->db->select([
                'doc.id',
                'doc.file_path',
            ]);
            $this->db->from('tbl_recruitment_contract_documents doc');
            $this->db->where('doc.seeker_id', $seeker_id);
            $this->db->where('doc.document_id', $doc_row->id);
            $seeker_doc = $this->db->get()->row();

            if (!$seeker_doc) {
                continue;
            }

            $file_tmp_data = file_get_contents(file_url($seeker_doc->file_path));
            $file_tmp_path = tempnam(sys_get_temp_dir(), 'documentsothers');
            @file_put_contents($file_tmp_path, $file_tmp_data);
            $file_contet_type = mime_content_type($file_tmp_path);
    
            //dd($file_tmp_path);
    
            $token = $this->WS_ca_api_overall_identity_token_lib->generate();
    
            $headers = [
                "Authorization: Bearer " . $token,
                "Content-Type: multipart/form-data"
            ];
            
            $doc_description =  $doc_row->name;

            $doc_description = str_replace(
                array('(en caso aplique)', '(Caso aplique)', '(Solo para extranjeros)'),
                array('', '', ''),
                $doc_description
            );

            $doc_description = trim($doc_description);

            $fields = [
                "cod_empresa" => get_consultant_code($no_cia),
                "numero_docid" => $seeker->document_number,
                "descripcion" => $doc_description,
                "flg_firmado" => "S",
                "cod_tipo_documento" => '02',
                "file" => curl_file_create($file_tmp_path, $file_contet_type, $this->ss($doc_description) . '.' . file_ext($seeker_doc->file_path))
            ];
    
            $env_prod = $this->config->item('env') == 'production';

            $url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";
               
            $options = [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $fields,
                CURLOPT_HTTPHEADER => $headers
            ];
    
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            
            if ($response === false) {
                $response = curl_error($ch);
            }
            
            @unlink($file_tmp_path);
            curl_close($ch);
    
            $response_api = json_decode($response);
    
            $this->Recruitment_contracts_synchronization_log->register([
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'sync_id' => $sync_id,
                'description' => $doc_description,
                'ref_id' => $seeker_doc->id,
                'url' => $url,
                'created_at' => date('Y-m-d H:i:s'),
                'parameters' => json_encode($fields),
                'response' => $response,
                'success' => isset($response_api->success) && $response_api->success
            ]);    
        }
    
        return true;
    }

    private function register_permission_signs_contracts($parameters, $sync_id)
    {
        $job_id = $parameters['job_id']; 
        $seeker_id = $parameters['seeker_id']; 
        $no_cia = $parameters['no_cia'];

        $this->db->select([
            'document.file_path AS file_path',
            'seekers.document_number AS seeker_document_number',
        ]);
        $this->db->from('tbl_seeker_permission_signs_contracts document');
        $this->db->join('tbl_job_seekers seekers', 'seekers.ID=document.seeker_id');
        $this->db->where('document.seeker_id', $seeker_id);
        
        $document = $this->db->get()->row();

        //dd($document);

        if (!$document) {
            return true;
        }

        $file_tmp_data = file_get_contents(file_url($document->file_path));
        $file_tmp_path = tempnam(sys_get_temp_dir(), 'pscid');
        @file_put_contents($file_tmp_path, $file_tmp_data);
        $file_contet_type = mime_content_type($file_tmp_path);

        //dd($file_tmp_path);

        $token = $this->WS_ca_api_overall_identity_token_lib->generate();

        $headers = [
			"Authorization: Bearer " . $token,
			"Content-Type: multipart/form-data"
        ];

		$fields = [
			"cod_empresa" => get_consultant_code($no_cia),
			"numero_docid" => $document->seeker_document_number,
            "descripcion" => 'Permiso para firma contrato',
            "flg_firmado" => "S",
            "cod_tipo_documento" => '02',
			"file" => curl_file_create($file_tmp_path, $file_contet_type, 'Permiso-para-firma-contrato.' . file_ext($document->file_path))
		];

		$url = $this->config->item('ca_api_base_url') . "/files/" . $this->config->item('ca_api_base_application_name') . "/documents";
       
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_HTTPHEADER => $headers
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
        
        if ($response === false) {
            $response = curl_error($ch);
        }
        
        @unlink($file_tmp_path);
        curl_close($ch);

        $response_api = json_decode($response);

        $this->Recruitment_contracts_synchronization_log->register([
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => $sync_id,
            'url' => $url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($fields),
            'response' => $response,
            'success' => isset($response_api->success) && $response_api->success
        ]);

        return true;
    }

    public function create_contract($seeker, $parameters)
    {
        $this->db->trans_start();

        $job_id = $parameters['job_id'];
        $seeker_id = $parameters['seeker_id'];

        $date_admission = isset($parameters['date_admission']) ? $parameters['date_admission'] : null; 
        $total_salary = isset($parameters['total_salary']) ? $parameters['total_salary'] : null;
        $contract_start_date = isset($parameters['contract_start_date']) ? $parameters['contract_start_date'] : null;
        $contract_end_date = isset($parameters['contract_end_date']) ? $parameters['contract_end_date'] : null;
        $employee_category_id = isset($parameters['employee_category_id']) ? $parameters['employee_category_id'] : null;
        $employee_type_id = isset($parameters['employee_type_id']) ? $parameters['employee_type_id'] : null;
        $contract_type_model_code = isset($parameters['contract_type_model_code']) ? $parameters['contract_type_model_code'] : null;
    
        $this->Recruitment_contract->create_or_update([
            'seeker_id' => $seeker_id,
            'job_id' => $job_id
        ], [
            'seeker_id' => $seeker_id,
            'job_id' => $job_id,
            'hired' => 1,
            'hired_at' => date('Y-m-d H:i:s'),
            'no_cia' => $parameters['no_cia'],
            'cod_trab' => null,
            'hired_by_user_id' => $this->session->userdata('user_id'),
            'identification_doc_type' => $seeker->document_type_id,
            'identification_doc_number' => $seeker->document_number,
            'is_peruvian' => $seeker->document_type_id == 1,
            'date_admission' => $date_admission,
            'total_salary' => $total_salary,
            'contract_start_date' => $contract_start_date,
            'contract_end_date' => $contract_end_date,
            'period_year' => $this->period_year,
            'period_month' => $this->period_month,
            'employee_category_id' => $employee_category_id,
            'employee_type_id' => $employee_type_id,
            'contract_type_model_code' => $contract_type_model_code
        ]);

        $job = $this->Posted_job->find($job_id);

        // if (!empty($employee_code)) {   
        //     $this->db->where('ID', $seeker_id);
        //     $this->db->update('tbl_job_seekers', [
        //         'employee_code' => $employee_code
        //     ]);
        // }
        
        //Actualizar registro del proceso rys del candidato
        $this->db->where('job_ID', $job_id);
        $this->db->where('seeker_ID', $seeker_id);
        $this->db->update('tbl_recruitment_candidates', [
            'contracted' => 1,
            'hiring_date' => date('Y-m-d H:i:s')
        ]);

        $staff_request = $this->Staff_request->find($job->request_ID);

        if ($staff_request) {
            $count_seeker_hired = $this->Recruitment_candidate->count_hired_candidates($job_id);

            //Si la cantidad de vacantes de la solicitud de candidatos
            //estan contratados cerrar el proceso RyS
            if ($staff_request->vacancies == $count_seeker_hired) {
                $this->db->where('job_ID', $job_id);
                $this->db->update('tbl_recruitment_process', [
                    'sts' => 'finished'
                ]);
            }
        }
        
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    private function get_job_seeker_to_hire($job_id, $seeker_id)
    {
        $this->db->select([
            'seeker.*',
            'seeker.way_id',
            'seeker.address_number',
            'doc_type.sunat_code AS document_sunat_code',
            'doc_type.id AS document_type_id',
            //Datos Ficha RTPS
            'form_rtps.ID AS rtps_form_id',
            'form_rtps.degree_obtained',
            'form_rtps.bank_account_number',
            'form_rtps.bank_account_type',
            'form_rtps.bank_name',
            'form_rtps.born_department',
            'form_rtps.born_province',
            'form_rtps.born_district',
            'form_rtps.domiciled',
            'form_rtps.pension_affiliation',
            'form_rtps.pension_affiliation_date',
            'form_rtps.pension_name_afp',
            'form_rtps.pension_cuspp',
            'form_rtps.pension_retired',
            'form_rtps.bank_name',
            'form_rtps.payment_cts_bank_name',
            'form_rtps.payment_cts_currency',
            'form_rtps.nationality AS nationality',
            'form_rtps.disability AS rtps_disability',
            // Datos Solicitud
            'staff_requests.no_cia AS no_cia',
            'staff_requests.ID AS request_id',
            'staff_requests.cod_business_unit AS business_unit_code',
            'staff_requests.cost_center AS cost_center_code',
            'staff_requests.cod_clie AS cod_clie',
            'staff_requests.monthly_gross_salary',
            'staff_requests.reason_request AS reason_request_code',
            'staff_requests.job_layout_id AS job_layout_id',
            'staff_requests.eecc_code AS eecc_code',
            'staff_requests.eecc_form_id AS eecc_form_id',
            //Datos empleo
            'jobs.ID AS job_id'
        ]);

        $this->db->from('tbl_recruitment_candidates rs_seeker');
        $this->db->join('tbl_job_seekers seeker', 'rs_seeker.seeker_ID=seeker.ID');
        $this->db->join('tbl_identity_document_types doc_type', 
            'doc_type.id=seeker.document_type'
        );
        $this->db->join('tbl_seeker_form_rtps form_rtps', 
            'form_rtps.seeker_ID=seeker.ID AND form_rtps.job_id=rs_seeker.job_ID'
        );
        $this->db->join('tbl_post_jobs jobs', 'rs_seeker.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'staff_requests.ID=jobs.request_ID');

        $this->db->where('rs_seeker.job_ID', $job_id);
        $this->db->where('rs_seeker.seeker_ID', $seeker_id);
        $this->db->where('rs_seeker.stage', 7);
        $this->db->where('rs_seeker.contracted', 0);

        return $this->db->get()->row();
    }

    private function get_rtps_rightful_claimants($form_id)
    {
        $this->db->select([
            'rightful_claimants.first_name',
            'doc_type.sunat_code AS document_sunat_code',
            'kinship.iplani_code AS kinship_iplani_code', 
            'kinship_certificates.iplani_code AS kinship_cert_iplani_code',
            'rightful_claimants.document_number',
            'rightful_claimants.paternal_last_name',
            'rightful_claimants.maternal_last_name',
            'rightful_claimants.gender',
            'rightful_claimants.birthdate',
            'rightful_claimants.kinship',
            'rightful_claimants.domicile',
            'rightful_claimants.ubigeo', 
            'rightful_claimants.live_same_domicile',
            'rightful_claimants.kinship_cert_code'
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants rightful_claimants');
        $this->db->join('tbl_identity_document_types doc_type', 
            'doc_type.id=rightful_claimants.document_type'
        );
        $this->db->join('tbl_kinship kinship', 
            'kinship.id=rightful_claimants.kinship',
            'left'
        );
        $this->db->join('tbl_kinship_certificates kinship_certificates', 
            'kinship_certificates.id=rightful_claimants.kinship_cert_type',
            'left'
        );
        $this->db->where('form_ID', $form_id);

        return $this->db->get()->result();
    }
    
    private function get_kinship_code($kinship)
    {
        $list_kinship = [
            '1' => '01', //Cónyuge - ESPOSO(A)
            '2' => '09', //Concubina(o) - CONCUBINA
            '3' => '02', //Gestante - HIJO(A)
            '4' => '02', //Hijo Menor de Edad - HIJO(A)
            '5' => '02', //Hijo Mayor de Edad Incapacitado Permanente - HIJO(A)
            '6' => '02' //Hijo mayor de edad hasta 24 años con estudios
        ];

        return isset($list_kinship[$kinship]) ? $list_kinship[$kinship] : '';
    }

    private function get_gender_code($gender)
    {
        $gender_data = [
            '1' => '1', //Hombre
            '2' => '2' //Mujer
        ];

        return isset($gender_data[$gender]) ? $gender_data[$gender] : null;
    }

    private function get_nationality_code($nationality)
    {
        $country = $this->db->get_where('tbl_countries', [
            'ID' => $nationality
        ])->row();

        if (!$country) {
            return null;
        }

        $country_name = mb_strtoupper($country->country_name);
    
        $country_name = str_replace(
            ['Á', 'É', 'Í', 'Ó', 'Ú'],
            ['A', 'E', 'I', 'O', 'U'],
            $country_name
        );

        $nationality = $this->db->get_where('tbl_ca_nationalities', [
            'name' => $country_name
        ])->row();

        if (!$nationality) {
            return null;
        }

        return $nationality->code;
    }

    private function get_family_nationality_code($nationality)
    {
        $country = $this->db->get_where('tbl_countries', [
            'ID' => $nationality
        ])->row();

        if (!$country) {
            return null;
        }

        $country_name = mb_strtoupper( $country->country_name);
    
        $country_name = str_replace(
            ['Á', 'É', 'Í', 'Ó', 'Ú'],
            ['A', 'E', 'I', 'O', 'U'],
            $country_name
        );

        $nationality = $this->db->get_where('tbl_ca_family_member_nationalities', [
            'name' => $country_name
        ])->row();

        if (!$nationality) {
            return null;
        }

        return $nationality->code;
    }

    private function get_country_code($country)
    {
        $country_name = mb_strtoupper($country);
    
        $country_name = str_replace(
            ['Á', 'É', 'Í', 'Ó', 'Ú'],
            ['A', 'E', 'I', 'O', 'U'],
            $country_name
        );

        $country_row = $this->db->get_where('tbl_ca_countries', [
            'name' => $country_name
        ])->row();

        if (!$country_row) {
            return null;
        }

        return $country_row->code;
    }

    private function get_civil_status_code($civil_status)
    {
        $civil_status_data = [
            '1' => '01', //Soltero
            '2' => '02', //Casado
            '3' => '04', //Divorciado
            '4' => '05' //Conviviente
        ];

        return isset($civil_status_data[$civil_status]) ? 
            $civil_status_data[$civil_status] : 
            null;
    }

    private function get_academic_level_code($study)
    {
        if (!$study) {
            return null;
        }   

        $study_data = [
            'Doctorado/Titulado' => '21',
            'Doctorado/Egresado' => '21',
            'Doctorado/En Curso' => '19',
            'Doctorado/Trunco' => '19', 
            'Maestría/Titulado' => '18',
            'Maestría/Egresado' => '18',
            'Maestría/En Curso' => '16',
            'Maestría/Trunco' => '16', 
            'Universitario/Titulado' => '13',
            'Universitario/Egresado' => '13',
            'Universitario/Trunco' => '12',
            'Universitario/Colegiado' => '07',
            'Universitario/Bachiller' => '14',
            'Universitario/Ciclos Fina' => '12',
            'Universitario/Intermedios' => '12',
            'Universitario/Ciclos Inic' => '12',
            'Técnico/Titulado' => '09',
            'Técnico/Egresado' => '09',
            'Técnico/Trunco' => '08',
            'Técnico/Ciclos Finales' => '08',
            'Técnico/Ciclos Intermedio' => '08',
            'Técnico/Ciclos Iniciales' => '08',
            'Secundaria' => '07',
            'Primaria' => '05'
        ];

        return isset($study_data[$study]) ? 
            $study_data[$study] : 
            "";
    }

    private function get_educational_institution_class_code($study)
    {
        if (!$study) {
            return null;
        }   

        $study_data = [
            'Doctorado/Titulado' => '3',
            'Doctorado/Egresado' => '3',
            'Doctorado/En Curso' => '3',
            'Doctorado/Trunco' => '3', 
            'Maestría/Titulado' => '3',
            'Maestría/Egresado' => '3',
            'Maestría/En Curso' => '3',
            'Maestría/Trunco' => '3', 
            'Universitario/Titulado' => '3',
            'Universitario/Egresado' => '3',
            'Universitario/Trunco' => '3',
            'Universitario/Colegiado' => '3',
            'Universitario/Bachiller' => '3',
            'Universitario/Ciclos Fina' => '3',
            'Universitario/Intermedios' => '3',
            'Universitario/Ciclos Inic' => '3',
            'Técnico/Titulado' => '2',
            'Técnico/Egresado' => '2',
            'Técnico/Trunco' => '2',
            'Técnico/Ciclos Finales' => '2',
            'Técnico/Ciclos Intermedio' => '2',
            'Técnico/Ciclos Iniciales' => '2',
            'Secundaria' => '1',
            'Primaria' => '1'
        ];

        return isset($study_data[$study]) ? 
            $study_data[$study] : 
            "";
    }

    private function get_bank_account_type_code(
        $bank_account_type
    )
    {
        $bank_account_type_data = [
            'Ahorro' => 'A',
            'Corriente' => 'C'
        ];

        return isset($bank_account_type_data[$bank_account_type]) ? 
            $bank_account_type_data[$bank_account_type] : 
            "";
    }

    public function get_bank_name_code($bank_name)
    {
        $bank_name_data = [
            'Banco Cencosud' => '000066',
            'Banco de Comercio' => '000004',
            'Banco de Crédito del Perú' => '000005',
            'Banco Falabella' => '000008',
            'Banco GNB Perú' => '000059',
            'Banco Interamericano de Finanzas (BanBif)' => '000011',
            'Banco Ripley' => '000040',
            'Banco Santander Perú' => '000015',
            'BBVA Continental' => '000003',
            'Citibank Perú' => '000002',
            'Interbank' => '000012',
            'MiBanco' => '000020',
            'Scotiabank Perú' => '000016',
            'Banco de la Nación' => '000006'    
        ];

        return isset($bank_name_data[$bank_name]) ? 
            $bank_name_data[$bank_name] : 
            "";
    }

    private function get_ubigeo_code($city)
    {
        $city = mb_strtoupper($city);
    
        $city = str_replace(
            ['Á', 'É', 'Í', 'Ó', 'Ú'],
            ['A', 'E', 'I', 'O', 'U'],
            $city
        );

        $ubigeo_parts = explode(',', $city);

        if (count($ubigeo_parts) < 3) {
            return null;
        }

        $ubigeo = $this->db->get_where('tbl_ca_ubigeos', [
            'order_administrative1' => trim($ubigeo_parts[0]),
            'order_administrative2' => trim($ubigeo_parts[1]),
            'order_administrative3' => trim($ubigeo_parts[2])
        ])->row();

        return $ubigeo ? $ubigeo->code : null;
    }

    public function get_afp_code($afp_name)
    {
        $afp_name = trim($afp_name);

        $data = [
            'HABITAT' => '25',
            'INTEGRA' => '21',
            'PRIMA' => '24',
            'PROFUTURO' => '23',
        ];

        return isset($data[$afp_name]) ? 
            $data[$afp_name] : 
            "";
    }

    public function get_currency_code($currency)
    {   
        $currency = trim($currency);

        $data = [
            'PEN' => '01',
            'USD' => '02',
        ];

        return isset($data[$currency]) ? 
            $data[$currency] : 
            "";
    }

    public function get_employee_category_code($category_id)
    {
        $category_id = trim($category_id);

        $employee_category = $this->db->from('tbl_ca_employee_categories')
            ->where('portal_code', $category_id)
            ->get()
            ->row();

        return $employee_category ? $employee_category->code : '';
    }

    public function get_way_code($way_id)
    {
        return  str_pad($way_id, 2, '0', STR_PAD_LEFT); 
    }

    private function get_hiring_type_reason_code($jp_code)
    {
        $type_reason = $this->db->get_where('tbl_ca_hiring_type_reasons', [
            'portal_code' => $jp_code
        ])->row();

        return $type_reason ? $type_reason->code : null;
    }
    
    private function remove_phone_prefix($phone)
    {
        $parts = explode(' ', $phone);
        return end($parts);
    }
            
    private function get_contract_totals($period_year, $period_month, $no_cia)
    {
        $this->db->select([
            'SUM(total_salary) AS total_salary',
            'COUNT(seeker_id) AS total_seekers',
        ]);
        $this->db->from('tbl_recruitment_contracts');
        $this->db->where('period_year', $period_year);
        $this->db->where('period_month', $period_month);
        $this->db->where('no_cia', $no_cia);
        $this->db->where('is_peruvian', 0);

        return $this->db->get()->row();
    }

    private function update_synchronization($job_id, $seeker_id)
    {
        $this->db->from('tbl_recruitment_contracts_synchronization_logs');
        $this->db->where('job_id', $job_id);
        $this->db->where('seeker_id', $seeker_id);
        $this->db->where('success', 0);
        $synchronizations_errors = $this->db->count_all_results();

        if ($synchronizations_errors == 0) {
            $this->db->where('job_id', $job_id);
            $this->db->where('seeker_id', $seeker_id);
            $this->db->update('tbl_recruitment_contracts', [
                'synchronized' => 1
            ]);
        }
    }

    private function validate_required_documents($parameters)
    {
        $job_id = $parameters['job_id'];
        $seeker_id = $parameters['seeker_id'];

        $form_rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'seeker_id' => $seeker_id,
            'job_id' => $job_id
        ])->row();
    
        $doc_requerired = [
            'identification_document' => 'Doc. identidad', 
            //'residency_verifications' => 'Verificación de residencia',
            'children_identification_document' => 'Copia Doc. Identidad Hijos menores de edad', 
            'spouse_certificates_cohabitation' => 'Acta de matrimonio o certificado del cónyuge',  
        ];
    
        foreach ($doc_requerired as $doc_key => $doc_name) {

            $this->db->select([
                'document_key',
                'approved AS rrhh_approved',
                'legal_approved AS legal_approved'
            ])
            ->from('tbl_recruitment_seeker_documents')
            ->where('seeker_ID', $seeker_id)
            ->where_in('document_key', $doc_key)
            ->where('job_id', $job_id);
            
            $row = $this->db->get()->row();

            if ($doc_key == 'spouse_certificates_cohabitation') {
                $spouse = $this->Jobseeker_form_rtps->get_rightful_claimant_spouse_by_form_id(@$form_rtps->ID);
                if (!($spouse && $spouse->document_type == '1' && $spouse->kinship == 1)) {
                    continue;
                }
            }

            if ($doc_key == 'children_identification_document' && 
                count($this->Jobseeker_form_rtps->get_peruvian_childrens(@$form_rtps->ID)) == 0) {
                continue;
            } 

            if (!$row || !$row->legal_approved) {
                throw new \Exception('El documento ' . $doc_name . ' debe ser aprobado por el área legal', 1);
            }

            if (!$row || !$row->rrhh_approved) {
                throw new \Exception('El documento ' . $doc_name . ' debe ser aprobado por el área de RRHH', 1);
            }
        }
    }

    private function get_proyect_code($client_code)
    {
        return ltrim($client_code, 'C');
    }

    private function ss($cadena)
    {	
		//Reemplazamos la A y a
		$cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
		array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
		array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
		$cadena );

		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
		);

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
			' ',
			'-',
			$cadena
		);

		$cadena = preg_replace('([^A-Za-z0-9\-])', '', $cadena);
		
		return $cadena;
	}
}
