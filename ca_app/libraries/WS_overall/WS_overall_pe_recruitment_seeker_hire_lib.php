<?php

class WS_overall_pe_recruitment_seeker_hire_lib 
{   
    private $period_year;

    private $period_month;

    public function __construct()
    {
        $this->period_year = null;
        $this->period_month = null;

        //Load models
        $this->load->model('Recruitment_contract');
        $this->load->model('Recruitment_contracts_synchronization_log');
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_candidate');

        //Load libraries
        $this->load->library('WS_hrmgo/WS_hrmgo_api_token_lib');
    }

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {
        $process = $this->Recruitment_process->find($params['process_id']);

        //dd($process);
        if (!$process || !$process->job_ID) {
            $error = 'Proceso no encontrado o no tiene un empleo asignado';
            return [
                'status' => false,
                'message' => $error
            ];
        }

        $params['job_id'] = $process->job_ID;

        list($status, $message) = $this->validate($params);

        if ($status === false) {

            $this->save_error($params, $message);

            return [
                'status' => $status,
                'message' => $message
            ];
        }
        
        $process_id = $params['process_id'];
        $seeker_id = $params['seeker_id'];
       
        //Migrar trabajador
        $register_employee = $this->register_employee($params);

        if (!$register_employee) {

            $message = 'Candidato no se pudo contratar';
            $this->save_error($params, $message);

            return [
                'status' => false,
                'message' => $message
            ];
        }

        //registrar employee api hrmgo
        $this->register_employee_portal_intake($params);

        //Migrar derechohabientes
        $this->register_rtps_rightful_claimants($params);

        //Actualizar sincronizacion de la migracion 
        $this->Recruitment_contract->update_sync($process_id, $seeker_id);

        return [
            'status' => true,
            'message' => 'Candidato contratado con éxito'
        ];
    }

    public function register_employee_portal_intake($params)
    {
        $process_id = $params['process_id'];
        $seeker_id = $params['seeker_id'];

        $contract_candidate = $this->get_job_candidate_to_hire($process_id, $seeker_id);

        if (!$contract_candidate) {
            return false;
        }

        $no_cia = empty($contract_candidate->request_no_cia) ? $params['no_cia'] : $contract_candidate->request_no_cia;

        if ($no_cia == '54') {
            return false;
        }

        $hrmgo_api = $this->ws_hrmgo_api_token_lib->get_token($contract_candidate->company_id);
        
        if (!$hrmgo_api) {
            return false;
        }

        // Paso 2: Preparar payload para la siguiente llamada
        $parameters = [
            'document_type'       => $contract_candidate->document_sunat_code,
            'document_number'     => $contract_candidate->document_number,
            'cod_banco'           => $this->get_bank_name_code($contract_candidate->bank_name),
            'bank_account'        => $contract_candidate->bank_account_number,
            'bank_account_int'    => $contract_candidate->bank_interbank_account_number ?? '',
            'currency'            => 'PEN',
            'bank_account_type'   => $this->get_bank_account_type_code($contract_candidate->bank_account_type),
            'entry_date'          => isset($params['entry_date']) ? $params['entry_date'] : date('Y-m-d'),
        ];

        $api_url = $hrmgo_api->api_url . "/employeePortalIntake";
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $hrmgo_api->token 
        ];

        $curl_options = [
            CURLOPT_URL => $api_url,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_POSTFIELDS => json_encode($parameters),
            CURLOPT_HTTPHEADER => $headers,
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curl_options);

        $response = curl_exec($ch);
        curl_close($ch);

        $json_response = @json_decode($response);
        $hired = (!empty($json_response) && isset($json_response->data->id)) ? 1 : 0;

        // Log de sincronización
        $this->Recruitment_contracts_synchronization_log->register([
            'process_id' => $process_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 13,
            'url' => $api_url,
            'created_at' => date('Y-m-d H:i:s'),
            'parameters' => json_encode($parameters),
            'response' => $response,
            'success' => $hired
        ]);

        return true;
    }

    public function validate($params)
    {   
        $process_id = $params['process_id'];
        $seeker_id = $params['seeker_id'];
        $job_id = $params['job_id'];
        $ignore_rightful_claimants = $params['ignore_rightful_claimants'] ?? 0;

        $rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'seeker_ID' => $seeker_id,
            'job_id' => $job_id
        ])->row();

        if (!$rtps) {
            return [
                false,
                'Candidato no tiene la Ficha de declaración jurada de información personal completada.'
            ];
        }

        $seeker = $this->get_job_candidate_to_hire($process_id, $seeker_id);

        //dd($seeker);

        if (!$seeker) {
            return [
                false,
                'Candidato no está habilitado para ser contratado'
            ];
        }

        return [
            true, 'OK'
        ];
    }

    private function register_employee($params)
    {
        $process_id = $params['process_id'];
        $job_id = $params['job_id'];
        $seeker_id = $params['seeker_id'];

        $contract_candidate = $this->get_job_candidate_to_hire($process_id, $seeker_id);

        //dd($contract_candidate);
        if (!$contract_candidate) {
            return false;
        }

        if ($contract_candidate->hired == 1) {
            return true;
        }

        $no_cia = empty($contract_candidate->request_no_cia) ? $params['no_cia'] : $contract_candidate->request_no_cia;

        if ($this->config->item('env') != 'production') {
            $no_cia = '99';
        }

        $parameters = [
            "pno_cia" => $no_cia,
            "papellido_paterno" => mb_strtoupper($contract_candidate->paternal_last_name),
            "papellido_materno" => mb_strtoupper($contract_candidate->maternal_last_name),
            "pprimer_nombre" => mb_strtoupper($contract_candidate->first_name),
            "psegundo_nombre" => mb_strtoupper(trim((string)$contract_candidate->second_name)),
            "ptercer_nombre" => mb_strtoupper(trim((string)$contract_candidate->third_name)),
            "ptipo_doc_iden" => $contract_candidate->document_sunat_code,
            "pnum_doc_iden" => $contract_candidate->document_number,
            "pfec_nac" => date('Y-m-d', strtotime($contract_candidate->dob)),
            "pnacionalidad" => $this->get_nationality_code($contract_candidate->nationality),
            "psexo" => $this->get_gender_code($contract_candidate->gender),
            "pest_civil" => $this->get_civil_status_code($contract_candidate->civil_status),
            "ptelefono_movil" => $this->remove_phone_prefix($contract_candidate->mobile),
            "pemail" => $contract_candidate->email,
            "pdire_via" => $contract_candidate->present_address,
            "pdireccion" => "-",
            "pdire_tipo_via" => "01",
            "pcod_cate" => "1",
            "pcod_nivel" => $this->get_nivel_code($contract_candidate->level_education, $contract_candidate->degree_obtained_type),
            "pcod_pais_emisor" => "604", //Perú
            "pcod_banco" => $this->get_bank_name_code($contract_candidate->bank_name),
            "pmoneda_cta_bancaria" => "SOL",
            "pnro_cta_bancaria" => '-',
            "ptipo_cta_bancaria" => $this->get_bank_account_type_code($contract_candidate->bank_account_type),
            "pcod_banco_cta_bancaria" => $this->get_bank_name_code($contract_candidate->bank_name),
            'pcodigo_ubigeo' => $this->get_ubigeo_code($contract_candidate->city),
            'pid_req_portal' => ($contract_candidate->request_id ? $contract_candidate->request_id : '0') . '|' . $process_id
        ];

        //dd($parameters);
    
        $api_url = $this->config->item('hrm_api2_url') . "/insert-new-worker";
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->config->item('hrm_api2_bearer_token')
        ];

		$curl_options = [
			CURLOPT_URL => $api_url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
			CURLOPT_POSTFIELDS => json_encode($parameters),
			CURLOPT_HTTPHEADER => $headers,
		];

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);

		$response = curl_exec($ch);

        curl_close($ch);
      
        $json_response = @json_decode($response);

        //dd($parameters);

        $hired = 0;
        $cod_trab = null;
        $hired_at = date('Y-m-d H:i:s');

        if ($json_response && isset($json_response->status)) {
            $hired = $json_response->status == '01' || $json_response->status == '02' ? 1 : 0;
            $cod_trab = $hired ? $json_response->pcod_trab : null;
        }
      
        //Registrar log de migracion
        $this->Recruitment_contracts_synchronization_log->register([
            'process_id' => $process_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 1,
            'url' => $api_url,
            'created_at' => $hired_at,
            'parameters' => json_encode($parameters),
            'response' => $response,
            'success' => $hired
        ]);

        $trans_contract = $this->create_contract([
            'process_id' => $process_id,
            'seeker_id' => $seeker_id,
            'contract_candidate' => $contract_candidate,
            'cod_trab' => $cod_trab,
            'hired' => $hired,
            'hired_at' => $hired_at,
            'job_id' => $job_id,
            'no_cia' => $no_cia,
            'contract_start_date' => $params['contract_start_date'],
            'contract_end_date' => $params['contract_end_date'],
            'employee_category_id' => $params['employee_category_id'] ?? null,
            'employee_type_id' => $params['employee_type_id'] ?? null
        ]);

        if ($hired && $trans_contract) {
            return true;
        }

        return false;
    }

    private function register_rtps_rightful_claimants($params)
    {
        $process_id = $params['process_id'];
        $seeker_id = $params['seeker_id'];

        $contract_candidate = $this->get_job_candidate_to_hire($process_id, $seeker_id);

        if (!$contract_candidate) {
            return false;
        }
        
        $rightful_claimants = $this->get_data_rtps_rightful_claimants($contract_candidate->rtps_form_id);

        $data_rightful_claimants = [];

        foreach ($rightful_claimants as $row) {
            $created_at = date('Y-m-d H:i:s');

            $api_url = $this->config->item('hrm_api2_url') . "/insert-new-deptrab";
            $headers = [
                "Content-Type: application/json",
                "Authorization: Bearer " . $this->config->item('hrm_api2_bearer_token')
            ];

            $no_cia = $contract_candidate->no_cia;

            if ($this->config->item('env') != 'production') {
                $no_cia = '99';
            }
            
            $parameters = [
                'pno_cia' => $no_cia,
                'pcod_trab' => $contract_candidate->cod_trab,
                'pape_paterno' => $row->paternal_last_name,
                'pape_materno' => $row->maternal_last_name,
                'pnombre' => $row->first_name,
                'pcod_vinculo' => $row->kinship_eplani_code,
                'ptipo_doc_iden' => $row->document_sunat_code,
                'pnum_doc_iden' => $row->document_number,
                'psexo' => $this->get_gender_code($row->gender),
                'pfec_nac' => date('Y-m-d', strtotime($row->birthdate)),
                "pnum_doc_pater" => $contract_candidate->document_number,
                "ptipo_doc_pater" => "1",
                'pdire_tipo_via' => '01',
                'pind_domicilio' => $row->live_same_domicile ? '1' : '0',
                'pdire_via' => $row->live_same_domicile ? $contract_candidate->present_address : $row->domicile,
                'pcodigo_ubigeo' => $row->live_same_domicile ? $this->get_ubigeo_code($contract_candidate->city) : $this->get_ubigeo_code($row->ubigeo)
            ];   

            //dd($parameters);
            $curl_options = [
                CURLOPT_URL => $api_url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 15,
                CURLOPT_POSTFIELDS => json_encode($parameters),
                CURLOPT_HTTPHEADER => $headers,
            ];
    
            $ch = curl_init();
            curl_setopt_array($ch, $curl_options);
    
            $response = curl_exec($ch);
    
            curl_close($ch);
          
            $json_response = @json_decode($response);
    
            $success = 0;
          
            if ($json_response && isset($json_response->status)) {
                $success = $json_response->status == '01' || $json_response->status == '02' ? 1 : 0;
            }

            //Registrar log de migracion
            $this->Recruitment_contracts_synchronization_log->register([
                'process_id' => $process_id,
                'seeker_id' => $seeker_id,
                'sync_id' => 3,
                'url' => $api_url,
                'created_at' => $created_at,
                'description' => $row->document_number,
                'ref_id' => $row->id,
                'parameters' => json_encode($parameters),
                'response' => $response,
                'success' => $success
            ]);
        }

        return true;
    }

    public function create_contract($input)
    {
        $contract_candidate = $input['contract_candidate'];
        $seeker_id = $input['seeker_id'];
        $job_id = $input['job_id'];
        $employee_code = $input['cod_trab'];
        $no_cia = $input['no_cia'];
        $date_admission = isset($input['date_admission']) ? $input['date_admission'] : null; 
        $total_salary = isset($input['total_salary']) ? $input['total_salary'] : null;
        $contract_start_date = isset($input['contract_start_date']) ? $input['contract_start_date'] : null;
        $contract_end_date = isset($input['contract_end_date']) ? $input['contract_end_date'] : null;
        $employee_category_id = isset($input['employee_category_id']) ? $input['employee_category_id'] : null;
        $employee_type_id = isset($input['employee_type_id']) ? $input['employee_type_id'] : null;
        $process_id = $input['process_id'];
        $hired = $input['hired'];
        $hired_at = $input['hired_at'];

        $this->db->trans_start();

        $this->Recruitment_contract->create_or_update([
            'process_id' => $process_id,
            'seeker_id' => $seeker_id
        ], [
            'process_id' => $process_id,
            'seeker_id' => $seeker_id,
            'job_id' => $job_id,
            'hired' => $input['hired'],
            'hired_at' => $input['hired_at'],
            'no_cia' => $no_cia,
            'cod_trab' => $employee_code,
            'hired_by_user_id' => $this->session->userdata('user_id'),
            'identification_doc_type' => $contract_candidate->document_type_id,
            'identification_doc_number' => $contract_candidate->document_number,
            'is_peruvian' => $contract_candidate->document_type_id == 1,
            'date_admission' => $date_admission,
            'total_salary' => $total_salary,
            'contract_start_date' => $contract_start_date,
            'contract_end_date' => $contract_end_date,
            'employee_category_id' => $employee_category_id,
            'employee_type_id' => $employee_type_id,
            'period_year' => $this->period_year,
            'period_month' => $this->period_month,
        ]);

        if (!empty($employee_code)) {   
            $this->db->where('ID', $seeker_id);
            $this->db->update('tbl_job_seekers', [
                'employee_code' => $employee_code
            ]);
        }
        
        //Actualizar registro del proceso rys del candidato
        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_ID', $seeker_id);
        $this->db->update('tbl_recruitment_candidates', [
            'contracted' => $input['hired'],
            'hiring_date' => $input['hired_at']
        ]);

        if ($hired) {
            $this->db->where('process_id', $process_id);
            $this->db->where('seeker_id', $seeker_id);
            $this->db->update('tbl_recruitment_tray_candidates', [
                'status_id' => 3 //CONTRATADO
            ]);
        }

        if (!$hired) {
            $this->db->where('process_id', $process_id);
            $this->db->where('seeker_id', $seeker_id);
            $this->db->update('tbl_recruitment_tray_candidates', [
                'status_id' => 5 //FALLIDO
            ]);
        }

        $job = $this->Posted_job->find($job_id);
        $staff_request = $this->Staff_request->find($job->request_ID);

        if ($staff_request) {
            $count_seeker_hired = $this->Recruitment_candidate->count_hired_candidates($process_id);

            //Si la cantidad de vacantes de la solicitud de candidatos
            //estan contratados cerrar el proceso RyS
            if ($staff_request->vacancies == $count_seeker_hired) {
                $data_update_sts = [
                    'sts' => 'finished',
                ];

                $this->Recruitment_process->update_process_sts($job_id, $data_update_sts);
            }
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    private function get_job_candidate_to_hire($process_id, $seeker_id)
    {
        $this->db->select([
            'rc_candidates.process_id AS process_id',
            'rc_candidates.seeker_id AS seeker_ID'
        ]);
        $this->db->from('tbl_recruitment_candidates rc_candidates');
        $this->db->join('tbl_recruitment_process t1_rc_process',  'rc_candidates.process_id=t1_rc_process.id');
        $this->db->where('rc_candidates.seeker_ID', $seeker_id);
        $this->db->where('t1_rc_process.id', $process_id);
        $this->db->where_in('rc_candidates.stage', [7, 17]);
        $this->db->where('rc_candidates.discarded', 0);
        
        $subquery_rc_candidates = $this->db->get_compiled_select();

        $this->db->select([
            'tc.process_id AS process_id',
            'tc.seeker_id AS seeker_ID'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tc');
        $this->db->join('tbl_recruitment_process t2_rc_process', 'tc.process_id=t2_rc_process.id');
        $this->db->where('t2_rc_process.tray_type_id', 3); //Procesos que vengan de la bandeja sin solicitudes
        $this->db->where('tc.seeker_id', $seeker_id);
        $this->db->where('t2_rc_process.id', $process_id); 
   
        $subquery_tray_candidates = $this->db->get_compiled_select();

        $sql_rc_candidates = "(" . $subquery_rc_candidates . ") UNION (" . $subquery_tray_candidates . ")";

        $this->db->select([
            'candidates.ID AS seeker_id',
            'candidates.email AS email',
            'candidates.dob AS dob',
            'candidates.nationality AS nationality',
            'candidates.gender AS gender',
            'candidates.civil_status AS civil_status',
            'candidates.mobile AS mobile',
            'candidates.city AS city',
            'candidates.present_address AS present_address',
            'form_rtps.first_name AS first_name',
            'form_rtps.second_name AS second_name',
            'form_rtps.third_name AS third_name',
            'form_rtps.paternal_last_name AS paternal_last_name',
            'form_rtps.maternal_last_name AS maternal_last_name',
            'form_rtps.document_number AS document_number', 
            'form_rtps.ID AS rtps_form_id',
            'form_rtps.degree_obtained',
            'form_rtps.level_education',
            'form_rtps.degree_obtained_type',
            'form_rtps.bank_account_number',
            'form_rtps.bank_account_type',
            'form_rtps.bank_name',
            'form_rtps.bank_interbank_account_number',
            'doc_type.sunat_code AS document_sunat_code',
            'doc_type.id AS document_type_id',
            'contracts.hired AS hired',
            'contracts.no_cia AS no_cia',
            'contracts.cod_trab AS cod_trab',
            'staff_requests.no_cia AS request_no_cia',
            'staff_requests.ID AS request_id',
            'jobs.ID AS job_id',
            'jobs.company_ID AS company_id',
            'rs_process.id AS process_id' 
        ]);

        $this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('(' . $sql_rc_candidates . ') AS rs_seeker', 'rs_process.id=rs_seeker.process_id');
        $this->db->join('tbl_job_seekers candidates', 'rs_seeker.seeker_ID=candidates.ID');
        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=candidates.document_type');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps.seeker_ID=candidates.ID AND form_rtps.job_id=rs_process.job_ID');
        $this->db->join('tbl_post_jobs jobs', 'rs_process.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'staff_requests.ID=jobs.request_ID', 'left');
        $this->db->join('tbl_recruitment_contracts contracts', 'contracts.process_id=rs_process.id AND contracts.seeker_id=rs_seeker.seeker_ID', 'left');
        $this->db->where('rs_process.id', $process_id);
        $this->db->where('candidates.ID', $seeker_id);

        return $this->db->get()->row();
    }

    private function get_data_rtps_rightful_claimants($rtps_form_id)
    {
        $this->db->select([
            'rightful_claimants.id',
            'rightful_claimants.first_name',
            'doc_type.sunat_code AS document_sunat_code',
            'kinship.iplani_code AS kinship_eplani_code', 
            'kinship_certificates.iplani_code AS kinship_cert_eplani_code',
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
        $this->db->where('rightful_claimants.form_ID', $rtps_form_id);

        return $this->db->get()->result();
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
        $nationality_data = [
            '2' => '9023', //alemán(a)
            '4' => '9063', //argentino(a)
            '7' => '9097', //boliviano(a)
            '8' => '9105', //brasileño(a)
            '10' => '9149', //canadiense
            '13' => '9169', //colombiano(a)
            '11' => '9211', //chileno(a)
            '12' => '9215', //chino(a)
            '18' => '9239', //ecuatoriano(a)
            '40' => '9383', //israelí
            '56' => '9589', //peruano(a)
            '69' => '9845', //uruguayo(a)
            '70' => '9850' //venezolano(a)
        ];

        return isset($nationality_data[$nationality]) ? 
            $nationality_data[$nationality] : 
            '9589';
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

    private function get_nivel_code($level_education, $degree_obtained_type)
    {
        $study_data = [
            'Superior/Trunco' => '12',
            'Superior/Egresado' => '13',
            'Superior/Encurso' => '12',
            'Ténico/Trunco' => '08',
            'Ténico/Encurso' => '08',
            'Ténico/Egresado' => '09',
            'Otros' => '07',
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

        $study = '';
        $level_education = trim((string)$level_education);
        $degree_obtained_type = trim((string)$degree_obtained_type);

        if ($level_education != '' &&  $degree_obtained_type != '' ) {
            $study = $level_education . '/' . $degree_obtained_type;
        }

        if (isset($study_data[$study])) {
            return $study_data[$study];
        }

        if (isset($study_data[$level_education])) {
            return $study_data[$level_education];
        }

        return null;
    }

    private function get_bank_account_type_code(
        $bank_account_type
    )
    {
        $bank_account_type_data = [
            'Ahorro' => 'A',
            'Corriente' => 'C',
            'Interbancario' => 'I'
        ];

        return isset($bank_account_type_data[$bank_account_type]) ? 
            $bank_account_type_data[$bank_account_type] : 
            "";
    }

    public function get_bank_name_code($bank_name)
    {
        $bank_name_data = [
            'Banco Azteca' => '',
            'Banco Cencosud' => '76',
            'Banco de Comercio' => '23',
            'Banco de Crédito del Perú' => '02',
            'Banco Falabella' => '63',
            'Banco GNB Perú' => '53',
            'Banco Interamericano de Finanzas (BanBif)' => '38',
            'Banco Pichincha' => '35',
            'Banco Ripley' => '74',
            'Banco Santander Perú' => '',
            'BBVA Continental' => '11',
            'Citibank Perú' => '07',
            'ICBC PERU BANK' => '',
            'Interbank' => '03',
            'MiBanco' => '49',
            'Scotiabank Perú' => '09',
            'Banco de la Nación' => '18'    
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

        $ubigeo = $this->db->get_where('tbl_eplani_ubigeos', [
            'order_administrative1' => trim($ubigeo_parts[0]),
            'order_administrative2' => trim($ubigeo_parts[1]),
            'order_administrative3' => trim($ubigeo_parts[2])
        ])->row();

        return $ubigeo ? $ubigeo->code : null;
    }

    private function remove_phone_prefix($phone)
    {
        $phone = phone_number_format($phone);
        
        return str_replace('+51', '', $phone);
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

    private function get_error($input)
    {
        //Verificar log de sincronizacion si trae error api eplani
        //Si el la tabla EPLANI.PRC_INS_PLM_MTRABAJADOR_DEP viene en la respuesta notificar al usuario
        $job_id = $input['job_id'];
        $seeker_id = $input['seeker_id'];
 
        $contract_log = $this->db->get_where('tbl_recruitment_contracts_synchronization_logs', [
            'job_id' => $job_id,
            'seeker_id' => $seeker_id,
            'sync_id' => 1
        ])->row();

		if ($contract_log) {
			$response = @json_decode($contract_log->response);
	
			$message = '';
			if ($response && isset($response->MESSAGE)) {
				$message = $response->MESSAGE;
			}

			if (strpos($message, 'EPLANI.PRC_INS_PLM_MTRABAJADOR_DEP') !== false) {
                return [
                    'status' => false,
                    'rightful_claimants_error' => true,
                    'message' => '¡Contratación no se pudo realizar!'
                ];
			}
		}

        return [
            'status' => false,
            'message' => '¡Contratación no se pudo realizar!'
        ];
    }

    private function save_error($params, $error) 
    {
        $seeker_id = $params['seeker_id'];
        $process_id = $params['process_id'];

        $this->db->where('seeker_id', $seeker_id);
        $this->db->where('process_id', $process_id);
        $this->db->update('tbl_recruitment_contracts', [
            'synchronization_error' => $error
        ]);
        
        $this->db->set('synchronization_attempts', 'synchronization_attempts+1', FALSE);
        $this->db->where('seeker_id', $seeker_id);
        $this->db->where('process_id', $process_id);
        $this->db->update('tbl_recruitment_contracts');

        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_id', $seeker_id);
        $this->db->update('tbl_recruitment_tray_candidates', [
            'status_id' => 5 //FALLIDO
        ]);
    }
}
