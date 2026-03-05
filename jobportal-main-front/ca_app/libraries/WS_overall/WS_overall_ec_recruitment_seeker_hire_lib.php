<?php

class WS_overall_ec_recruitment_seeker_hire_lib 
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
        if (!$process) {
            $error = 'Proceso no encontrado';
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

        //Actualizar sincronizacion de la migracion 
        $this->Recruitment_contract->update_sync($process_id, $seeker_id);

        return [
            'status' => true,
            'message' => 'Candidato contratado con éxito'
        ];
    }

    public function validate($params)
    {   
        $process_id = $params['process_id'];
        $seeker_id = $params['seeker_id'];
 
        $entry_form = $this->db->get_where('tbl_entry_form', [
            'seeker_id' => $seeker_id,
            'process_id' => $process_id
        ])->row();

        if (!$entry_form) {
            return [
                false,
                'Candidato no tiene la Ficha de ingreso completada.'
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
        $seeker_id = $params['seeker_id'];
        $job_id = $params['job_id'];
        $contract_type_model = explode('||', $params['contract_type_model']);

        $contract_candidate = $this->get_job_candidate_to_hire($process_id, $seeker_id);

        //dd($contract_candidate);
        if (!$contract_candidate) {
            return false;
        }

        if ($contract_candidate->hired == 1) {
            return true;
        }

        $no_cia = empty($contract_candidate->consultant_code) ? $params['no_cia'] : $contract_candidate->consultant_code;
        
        $rightful_claimants_data = [];
        $rightful_claimants = $this->get_rightful_claimants($contract_candidate->entry_form_id);
           
        foreach ($rightful_claimants as $row_rightful_claimant) {
            $rightful_claimants_data[] = [
                'family_bond_id' => [
                    'code' => $row_rightful_claimant->kinship_hrmgo_code
                ],
                'type_id_document' => [
                    'code' => $this->get_identity_document_type_code($row_rightful_claimant->identity_document_type_id),
                ],
                'document_number' => $row_rightful_claimant->identity_document_number,
                'lastname_pat' => $row_rightful_claimant->paternal_last_name,
                'lastname_mat' => $row_rightful_claimant->maternal_last_name,
                'name' => $row_rightful_claimant->first_name,
                'second_name' => $row_rightful_claimant->second_name,
                'gender' => $this->get_gender_code($row_rightful_claimant->gender_id),
                'dob' => $row_rightful_claimant->birthdate,
                'address_info' => '',
                'document_attach_number' => ''
            ];
        }
        
        //dd($rightful_claimants_data);
        
        $parameters = [
            "employee_id" => $contract_candidate->seeker_id,
            "primer_nombre" => $contract_candidate->first_name,
            "segundo_nombre" => $contract_candidate->second_name,
            "tercer_nombre" => $contract_candidate->third_name,
            "primer_apellido" => $contract_candidate->paternal_last_name,
            "segundo_apellido" => $contract_candidate->maternal_last_name,
            "dob" => $contract_candidate->dob,   
            "gender" => $this->get_gender_code($contract_candidate->gender),
            "typeDocument" => [
                "code" => $this->get_identity_document_type_code($contract_candidate->document_type_id),
                // "description" => "DNI",
                // "short_description" => "DNI"
            ],
            "numero_documento" => $contract_candidate->document_number,
            "nationality" => [
                "code" => $this->get_nationality_code($contract_candidate->nationality),
                // "description" => "MÉXICO",
                // "abbreviation" => ""
            ],     
            "civilStatus" => [
                "code" => $this->get_civil_status_code($contract_candidate->civil_status),
                // "description" => "Soltero (a)",
                // "abbreviation" => "S"
            ],
            "phone" => null,
            "phone_cell" => $contract_candidate->mobile,
            "email" => $contract_candidate->email,
            "email_corporate" => null,
            "contact_emergency_name" => null,
            "phone_emergency" => null,
            "phone_cell_emergency" => null,
            "comentario" => null,
            "direction" => [
                "road" => [
                    "code" => "02",
                    "name" => ""
                ],
                "zone" => [
                    "code" => "99",
                    "description" => ""
                ],
                "inside" => null,
                "number" => null,
                "ubigeo" => [
                    "code" => "",
                    "district" => "",
                    "province" => "",
                    "department" => ""
                ],
                "address" => $contract_candidate->present_address,
                "reference" => null,
                "zone_description" => ""
            ],
            "academic_data" => [
                "career" => [
                    "code" => 0,
                    "description" => ""
                ],
                "senior_year" => null,
                "institution_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "education_level" => [
                    "code" => "0",
                    "description" => ""
                ]
            ],
            "afp_snp" => [
                "id" => [
                    "code" => "0",
                    "description" => "",
                    "abbreviation" => ""
                ],
                "name" => [
                    "code" => "0",
                    "description" => "",
                    "abbreviation" => ""
                ],
                "number" => "",
                "fecha_devengue" => "00-0000",
                "affiliation_date" => "0000-00-00"
            ],
            "social_security" => [
                "affiliation_date" => "0000-00-00",
                "membership_number" => $contract_candidate->social_security_number,
                "social_security_type" => [
                    "code" => "0",
                    "name" => "No definido"
                ]
            ],
            "bank_accounts_data" => [
                "bank" => [
                    "code" => 0,
                    "name" => "",
                    "abbreviation" => ""
                ],
                "coin" => [
                    "code" => "USD",
                    "name" => "USD"
                ],
                "bank_account" => null,
                "bank_account_int" => "",
                "type_bank_account" => [
                    "code" => 0,
                    "description" => "",
                    "abbreviation" => ""
                ]
            ],
            "jobs" => [
                "branch" => [
                    "code" => $contract_candidate->consultant_code,
                    "name" => $contract_candidate->consultant_name,
                    "economic_activity" => [
                        "code" => 0,
                        "name" => ""
                    ]
                ],
                "business_unit" => [
                    "code" => $contract_candidate->business_unit_code,
                    "name" => $contract_candidate->business_unit_name
                ],
                "business_partner" => [
                    "code" => $contract_candidate->client_code,
                    "description" => $contract_candidate->client_name,
                    "type_document" => [
                        "code" => "0",
                        "description" => "",
                        "abbreviation" => ""
                    ],
                    "economic_activity" => [
                        "code" => 0,
                        "name" => ""
                    ]
                ],
                "cost_center" => [
                    "code" => $contract_candidate->cost_center_code,
                    "description" => $contract_candidate->cost_center_name,
                ],
                "office" => [
                    "code" => "0",
                    "description" => "No definido"
                ],
                "department" => [
                    "code" => 0,
                    "name" => ""
                ],
                "designation" => [
                    "code" => 0,
                    "name" => ""
                ],
                "establishment" => [
                    "code" => "0000",
                    "name" => "No definido",
                    "secuence" => "0"
                ],
                "position" => [
                    "code" => $contract_candidate->jl_code_integration,
                    "name" => $contract_candidate->jl_job_title
                ],
                "contract_template" => [
                    "code" => (string)$contract_type_model[0],
                    "name" => (string)$contract_type_model[1]
                ],
                "salary" => $contract_candidate->monthly_gross_salary,
                "salary_coin" => [
                    "code" => "USD",
                    "name" => "USD",
                //    "integration_code1" => "DOLAR"
                ],
                "company_date_start" => $params['contract_start_date'],
                "fecha_cese_temp" => null,
                "company_date_end" => null,
                "aud_alta" => 1,
                "adm" => null,
                "assist" => null,
                "comercial" => null,
                "eps" => 0,
                "sctr" => 0,
                "vida_ley" => 0,
                "pago_quincena" => $contract_candidate->salary_delivery_period == 'biweekly' ? 1 : 0,
                "part_time" => false,
                "back_office" => false,
                "fiscalizado" => false,
                "category_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "shop_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "form_type_id" => [
                    "code" => "0",
                    "description" => ""
                ],
                "situation_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "center_job_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "chain_store_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "service_type_id" => [
                    "code" => "0",
                    "description" => ""
                ],
                "bank_account_cts" => [
                    "bank_cts" => [
                        "code" => 0,
                        "name" => "",
                        "abbreviation" => ""
                    ],
                    "coin_cts" => [
                        "code" => null,
                        "name" => null
                    ],
                    "bank_account_cts" => "-"
                ],
                "employee_type_id" => [
                    "code" => "0",
                    "description" => ""
                ],
                "labor_situation_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "productive_unit_id" => [
                    "code" => 0,
                    "description" => ""
                ],
                "reason_termination" => [
                    "code" => 0,
                    "description" => ""
                ],
                "integration_branch" => null,
                "integration_secuencia" => null,
                "integration_grupo_calc" => null
            ],
            "beneficiaries" => count($rightful_claimants_data) > 0 ? $rightful_claimants_data : null
        ];

        $hrmgo_api = $this->ws_hrmgo_api_token_lib->get_token($contract_candidate->company_id);
        
        if (!$hrmgo_api) {
            return false;
        }

        $api_url = $hrmgo_api->api_url . "/employees/employeeWebService";
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

        //dd($parameters);

        $hired = 0;
        $cod_trab = null;
        $hired_at = date('Y-m-d H:i:s');

        if ($json_response && isset($json_response->status)) {
            $hired = $json_response->status == '200';
            $cod_trab = null;
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
            'employee_type_id' => $params['employee_type_id'] ?? null,
            'contract_type_model_code' => $contract_type_model[0] ?? null,
            'contract_type_model_name' => $contract_type_model[1] ?? null,
        ]);

        if ($hired && $trans_contract) {
            return true;
        }

        return false;
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
        $contract_type_model_code = $input['contract_type_model_code'] ?? null;
        $contract_type_model_name = $input['contract_type_model_name'] ?? null;

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
            'contract_type_model_code' => $contract_type_model_code,
            'contract_type_model_name' => $contract_type_model_name
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
            'entry_form_data.entry_form_id',
            'entry_form_data.first_name AS first_name',
            'entry_form_data.second_name AS second_name',
            'entry_form_data.third_name AS third_name',
            'entry_form_data.paternal_last_name AS paternal_last_name',
            'entry_form_data.maternal_last_name AS maternal_last_name',
            'entry_form_data.social_security_number',
            'candidates.document_number AS document_number',
            'candidates.dob AS dob',
            'candidates.nationality AS nationality',
            'candidates.gender AS gender',
            'candidates.civil_status AS civil_status',
            'candidates.mobile AS mobile',
            'candidates.city AS city',
            'candidates.present_address AS present_address',
            'doc_type.id AS document_type_id',
            'contracts.hired AS hired',
            'contracts.no_cia AS no_cia',
            'contracts.cod_trab AS cod_trab',
            'staff_requests.no_cia AS consultant_code',
            'staff_requests.consultant_name AS consultant_name',
            'staff_requests.cod_clie AS client_code',
            'staff_requests.client_company_name AS client_name',
            'staff_requests.cod_business_unit AS business_unit_code',
            'staff_requests.business_unit_name AS business_unit_name',
            'staff_requests.cost_center AS cost_center_code',
            'staff_requests.cost_center AS cost_center_name',
            'staff_requests.ID AS request_id',
            'staff_requests.monthly_gross_salary',
            'staff_requests.salary_delivery_period',
            'jobs.ID AS job_id',
            'jobs.company_ID AS company_id',
            'rs_process.id AS process_id',
            'job_layouts.job_title AS jl_job_title',
            'job_layouts.code_integration AS jl_code_integration', 
        ]);

        $this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('(' . $sql_rc_candidates . ') AS rs_seeker', 'rs_process.id=rs_seeker.process_id');
        $this->db->join('tbl_job_seekers candidates', 'rs_seeker.seeker_ID=candidates.ID');
        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=candidates.document_type');
        $this->db->join('tbl_entry_form entry_form', 'entry_form.seeker_id=candidates.ID AND entry_form.process_id=rs_process.id');
        $this->db->join('tbl_entry_form_ec entry_form_data', 'entry_form_data.entry_form_id=entry_form.id');
        $this->db->join('tbl_post_jobs jobs', 'rs_process.job_ID=jobs.ID', 'left');
        $this->db->join('tbl_staff_requests staff_requests', 'staff_requests.ID=jobs.request_ID', 'left');
        $this->db->join('tbl_job_layouts job_layouts', 'job_layouts.id=staff_requests.job_layout_id', 'left');
        $this->db->join('tbl_recruitment_contracts contracts', 'contracts.process_id=rs_process.id AND contracts.seeker_id=rs_seeker.seeker_ID', 'left');
        $this->db->where('rs_process.id', $process_id);
        $this->db->where('candidates.ID', $seeker_id);

        return $this->db->get()->row();
    }

    public function get_rightful_claimants($entry_form_id)
    {   
        $this->db->select([
            'rightful_claimants.id',
            'rightful_claimants.entry_form_id',
            'rightful_claimants.first_name',
            'rightful_claimants.second_name',
            'rightful_claimants.paternal_last_name',
            'rightful_claimants.maternal_last_name',
            'rightful_claimants.kinship_id',
            'kinship.name AS kinship_name',
            'kinship.iplani_code AS kinship_hrmgo_code',
            'rightful_claimants.gender_id',
            'rightful_claimants.identity_document_type_id',
            'rightful_claimants.identity_document_number',
            'rightful_claimants.birthdate'
        ]);
        $this->db->from('tbl_entry_form_ec_rightful_claimants rightful_claimants');
        $this->db->join('tbl_kinship kinship', 'kinship.id=rightful_claimants.kinship_id', 'left');
        $this->db->where('rightful_claimants.entry_form_id', $entry_form_id);
 
        return $this->db->get()->result();
    }
    
    private function get_gender_code($gender)
    {
        $gender_data = [
            '1' => 'Male',  //Hombre
            '2' => 'Female' //Mujer
        ];

        return isset($gender_data[$gender]) ? $gender_data[$gender] : null;
    }

    private function get_identity_document_type_code($document_type_id)
    {
        $this->db->from('tbl_identity_document_types');
        $this->db->where('id', $document_type_id);
        $document = $this->db->get()->row();

        return $document ? $document->hrmgo_code : null;
    }

    private function get_nationality_code($nationality_id)
    {
        $this->db->from('tbl_countries');
        $this->db->where('ID', $nationality_id);
        $country = $this->db->get()->row();

        return $country ? $country->hrmgo_nationality_code : null;
    }

    private function get_civil_status_code($civil_status_id)
    {
        $this->db->from('tbl_civil_status');
        $this->db->where('id', $civil_status_id);
        $civil_status = $this->db->get()->row();

        return $civil_status ? $civil_status->hrmgo_code : null;
    }

    private function remove_phone_prefix($phone)
    {
        $parts = explode(' ', $phone);
        return end($parts);
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
