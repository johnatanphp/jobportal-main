<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Form_rtps extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!candidate_is_process_contracting()) {
           // show_404();
        }

        //load model
        $this->load->model('Jobseeker_form_rtps');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Staff_request');
        $this->load->model('Bank'); 
		$this->load->model('Identity_document_type');
        $this->load->model('Kinship');
        $this->load->model('Way');
        $this->load->model('Institution_educational_type');
		$this->load->model('Institution_educational_class');
		$this->load->model('Institution_type');
		$this->load->model('Institution');
		$this->load->model('Career');
        $this->load->model('Disability');
        $this->load->model('Civil_status');
        $this->load->model('Gender');
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_contract_document_type');

        //Load libraries
        $this->load->library('upload');
       
        $this->ads = $this->Ad->get_ads();
    }

    public function index($contract_document_type_id = 0, $process_id = 0)
    {
        $job_seeker_id = $this->session->userdata('user_id');

        $contract_document_type = $this->Recruitment_contract_document_type->get_info_by_id($contract_document_type_id);

        if (!$contract_document_type) {
            show_404();
        }

        // Si el documento no pertenece al Grupo Ficha de ingreso
        // No mostrar el formulario
        if ($contract_document_type->group_id != 1) {
            show_404();
        }

        $process = $this->Recruitment_process->find($process_id);

        if (!$process) {
            show_404();
        }

       // dd($process);

        $jobseeker = $this->Job_seeker->find($job_seeker_id);

        $rs_candidate = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($job_seeker_id);

        if (!$rs_candidate) {
            show_404();
        }

        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);

        if ($process_country->iso_3166_1_alpha2 != 'PE') {
            show_404();
        }

        $seeker_additional_info = $this->Jobseeker_additional_info->get_record_by_userid($job_seeker_id);

        $job_id = $process->job_ID; 

        //dd($job_seeker_id);

        $form_rtps = $this->Jobseeker_form_rtps->get_last_record($job_id,  $job_seeker_id);

        if ($form_rtps && $form_rtps->evicertia_status == 3 && $form_rtps->job_id == $job_id) {
            redirect('jobseeker/form_rtps/show/' . $contract_document_type_id . '/' . $process_id);
        }

        //$worked_in_overall = false;

        $worked_in_overall = $this->Job_seeker->has_worked_in_overall(
            $jobseeker->document_number
        );
        
        $country_id = $process_country->ID;
       
        $data['title'] = "Ficha de datos del trabajador - " . SITE_NAME;
        $data['ads_row'] = $this->ads;    
        $data['jobseeker'] = $jobseeker;
        $data['form_rtps'] = $form_rtps;
        $data['result_ubigeos'] = $this->Ubigeo->get_all_records();
        $data['rightful_claimants'] = $this->Jobseeker_form_rtps->get_rightful_claimants_by_form_id(@$form_rtps->ID);
        $data['result_cities'] = $this->City->get_all_cities();
        $data['result_countries'] = $this->Country->get_all_countries();
        
        $data['result_departments'] = $this->Ubigeo->get_all_departments();
        $data['result_provinces'] = $this->Ubigeo->get_provinces_by(@$form_rtps->born_department);
        $data['result_districts'] = $this->Ubigeo->get_districts_by(@$form_rtps->born_province);

        @list($current_department, $current_province, $current_district) = explode(',', $jobseeker->city); 
        $data['provinces'] = $this->Ubigeo->get_provinces_by($current_department);
        $data['districts'] = $this->Ubigeo->get_districts_by($current_province);

        $data['document_types'] = $this->Identity_document_type->all(['country_id' => $country_id, 'active' => 1]);
        $data['result_studies'] = $this->Job_seeker->get_qualification_by_jobseeker_id($job_seeker_id);
        $data['result_banks'] = $this->Bank->get_all_banks();
        $data['worked_in_overall'] = $worked_in_overall;
        $data['seeker_additional_info'] = $seeker_additional_info;
        $data['kinship_types'] = $this->Kinship->all(['active' => 1]);
        $data['result_ways'] = $this->Way->all(['active' => 1]);
        $data['disabilities'] = $this->Disability->all(['active' => 1]); 
        $data['civil_status'] = $this->Civil_status->all(['active' => 1]);
		$data['genders'] = $this->Gender->all(['active' => 1]);
        $data['contract_document_type'] = $contract_document_type;
        $data['process_country'] = $process_country;
        $data['process'] = $process;

        $this->form_validation->set_rules('first_name', 'Primer nombre', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('second_name', 'Segundo nombre', 'trim|strip_all_tags');
        $this->form_validation->set_rules('third_name', 'Tercer nombre', 'trim|strip_all_tags');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('dob', 'Fecha de nacimiento', 'trim|required|is_date_format[Y-m-d]');
        $this->form_validation->set_rules('civil_status', 'Estado civil', 'trim|required|in_list_db[tbl_civil_status.id]');
		$this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list_db[tbl_genders.id]');
        $this->form_validation->set_rules('full_mobile_phone_number', 'Teléfono móvil', 'required|is_valid_phone_number');
        $this->form_validation->set_rules('present_address', 'Domicilio actual', 'trim|required|max_length[100]|strip_all_tags');
        $this->form_validation->set_rules('place_birth', 'Lugar de nacimiento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('level_education', 'Nivel educativo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('specialty', 'Especialidad', 'trim|strip_all_tags');
        $this->form_validation->set_rules('degree_obtained', 'Grado obtenido', 'trim|strip_all_tags');        
        $this->form_validation->set_rules('pension_affiliation', 'Afiliado a', 'trim|strip_all_tags');
        $this->form_validation->set_rules('bank_name', 'Nombre de la entidad', 'trim|required|strip_all_tags');
        
        $i_have_account_number = $this->input->post('i_have_account_number') ? 1 : 0;

        if ($i_have_account_number) {
            $bank_name = $this->input->post('bank_name');
            $bank_selected = $this->Bank->get_by_bank_name($bank_name);

            // Verificamos que se haya obtenido la configuración; de lo contrario, podemos asignar validación por defecto
            if ($bank_selected && isset($bank->account_digits)) {
                $account_digits = (int) $bank->account_digits;
            } else {
                // Si no se encuentra o no está definido, se valida solo que sean dígitos
                $account_digits = 0;
            }

            if ($bank_selected && isset($bank->cci_digits)) {
                $cci_digits = (int) $bank->cci_digits;
            } else {
                // Si no se encuentra o no está definido, se valida solo que sean dígitos
                $cci_digits = 0;
            }

            // Configurar la regla de validación para el campo "bank_account_number"
            if ($account_digits > 0) {
                $this->form_validation->set_rules(
                    'bank_account_number',
                    'Número de cuenta',
                    'required|numeric|regex_match[/^[0-9]{' . $account_digits . '}$/]',
                    array(
                        'numeric'     => 'El número de cuenta debe ser numérico.',
                        'regex_match' => 'El número de cuenta debe tener exactamente ' . $account_digits . ' dígitos.'
                    )
                );
            } else {
                $this->form_validation->set_rules(
                    'bank_account_number',
                    'Número de cuenta',
                    'required|numeric',
                    array(
                        'numeric' => 'El número de cuenta solo puede contener números.'
                    )
                );
            }

            // Configurar la regla de validación para el campo "bank_interbank_account_number"
            if ($cci_digits > 0) {
                $this->form_validation->set_rules(
                    'bank_interbank_account_number',
                    'Número de cuenta',
                    'required|numeric|regex_match[/^[0-9]{' . $cci_digits . '}$/]',
                    array(
                        'numeric'     => 'El número de cuenta interbancario debe ser numérico.',
                        'regex_match' => 'El número de cuenta interbancario debe tener exactamente ' . $cci_digits . ' dígitos.'
                    )
                );
            } else {
                $this->form_validation->set_rules(
                    'bank_interbank_account_number',
                    'Número de cuenta',
                    'required|numeric',
                    array(
                        'numeric' => 'El número de cuenta interbancario solo puede contener números.'
                    )
                );
            }

            $this->form_validation->set_rules('bank_account_type', 'Tipo de cuenta', 'trim|required|strip_all_tags');
        }

        $this->form_validation->set_rules('fifth_category_income', '5ta Categoria', 'trim|required|in_list[1,0]');
        $this->form_validation->set_rules('driver_license', 'Licencia de conducir', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('unionized', 'Sindicalizado', 'trim|strip_all_tags');
        $this->form_validation->set_rules('degree_obtained_year', 'Año de egreso', 'trim|strip_all_tags');
        $this->form_validation->set_rules('degree_obtained_institution', 'Institución educativa', 'trim|strip_all_tags');
        $this->form_validation->set_rules('payment_cts_bank_name', 'Pago CTS Banco', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('payment_cts_currency', 'Pago CTS Moneda', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|strip_all_tags');
        $this->form_validation->set_rules('facebook', 'Facebook', 'trim|strip_all_tags');
        $this->form_validation->set_rules('way_id', 'Domicilio Via', 'trim|strip_all_tags');
        $this->form_validation->set_rules('address_number', 'Domicilio numero', 'trim|strip_all_tags');
        $this->form_validation->set_rules('disability_type', 'Discapacidad', 'trim|required|strip_all_tags');

        $this->form_validation->set_rules('emergency_contact_kinship', 'Parentesco', 'trim|strip_all_tags');

        if ($this->input->post('emergency_contact_kinship') != '') {
            $this->form_validation->set_rules('emergency_contact_full_mobile_phone_number', 'Teléfono de Emergencia', 'trim|required|is_valid_phone_number');
        }
        
        $level_education = $this->input->post('level_education');
        if ($level_education == 'Superior' || $level_education == 'Ténico') {
            $this->form_validation->set_rules('degree_obtained_type', 'Grado obtenido', 'trim|required|strip_all_tags');
        }
        
        $born_country = $this->input->post('born_country');
        if ($born_country == $country_id) {
            $this->form_validation->set_rules('born_department', 'Departamento', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('born_province', 'Provincia', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('born_district', 'Distrito', 'trim|required|strip_all_tags');
        }

        $current_country = $this->input->post('current_country');
        if ($current_country == $country_id) {
            $this->form_validation->set_rules('current_department', 'Departamento', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('current_province', 'Provincia', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('current_district', 'Distrito', 'trim|required|strip_all_tags');
        }
    
        $this->form_validation->set_rules('pension_affiliated', '¿Eres afiliado?', 'trim|required|in_list[1,0]');
        
        $pension_affiliated = $this->input->post('pension_affiliated');
        $pension_name = $this->input->post('pension_name');

        if ($pension_affiliated == 1) {
            $this->form_validation->set_rules('pension_name', 'Afiliado a ', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('pension_change', '¿Desea cambiar de sistema de pensiones? ', 'trim|required|in_list[1,0]');

            if ($pension_name == 'AFP') {
                $this->form_validation->set_rules('pension_type', 'Nombre AFP ', 'trim|required|strip_all_tags');
            }

            $pension_change = $this->input->post('pension_change');

            if ($pension_change == 1) {
                $this->form_validation->set_rules('pension_change_type', 'Nombre AFP ', 'trim|required|strip_all_tags');
            }
        } else {
            $this->form_validation->set_rules('pension_join_name', 'Seleccione un sistema de pensiones', 'trim|required');

            $pension_join_name = $this->input->post('pension_join_name');
        
            if ($pension_join_name == 'AFP') {
                $this->form_validation->set_rules('pension_join_type', 'Nombre de pensión', 'trim|required|strip_all_tags|strip_all_tags');
            }
        }
  
        $fifth_category_income = $this->input->post('fifth_category_income');

        if ($fifth_category_income === 1) {
            $this->form_validation->set_rules('attached_document_license', 'Adjuntar doc lincencia', 'trim|required|strip_all_tags');
        }

        $rightful_claimants = (array)$this->input->post('rightful_claimants');
        
        $this->form_validation->set_message('required', 'El campo %s es requerido');
		$this->form_validation->set_message('is_unique', 'El campo %s ya existe');

        if ($this->form_validation->run() === FALSE) {

            if (count($this->input->post()) > 0) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Hay datos incorrectos. ' . current($this->form_validation->error_array())
                ]);
            } else {
                $this->load->view('jobseeker/requested_documents/form_rtps_view', $data);
            }
            return;
        }

        //Cancelar firma digital si esta pendiente
        if ($form_rtps && $form_rtps->evicertia_status == 2) {
            $this->load->library(
                'Form_rtps/Form_rtps_cancel_sign_evicertia_lib', 
                null , 
                'Form_rtps_cancel_sign_evicertia_lib'
            );
    
            if (!$this->Form_rtps_cancel_sign_evicertia_lib->send($form_rtps->ID)) {
                flash_message('danger', 'Ha ocurrido un error al tratar de editar los datos, no se pudo cancelar la firma actual.');
                 echo json_encode([
                    'status' => false,
                    'data' => [
                        'redirect_url' => site_url('jobseeker/form_rtps/show/' . $contract_document_type_id . '/' . $process_id)
                    ]
                ]);
                return;
            }
        }

        $data_input = $this->input->post();

        $home_phone = '';
    
        if ($this->input->post('home_phone') != '') {
            $home_phone = $this->input->post('home_phone_code')  . ' ' . $this->input->post('home_phone');
        }

        $staff_request = null;
        
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);   
               
        $mobile = trim($this->input->post('full_mobile_phone_number'));
        $disability = (int)$this->input->post('disability_type') > 0;
      
        $data_form = [
            'creation_date' => date('Y-m-d H:i:s'),
            'version' => 'GP-FO-004<br>Versión: 07',
            'first_name' => trim((string)$this->input->post('first_name')),
            'second_name' => trim((string)$this->input->post('second_name')),
            'third_name' => trim((string)$this->input->post('third_name')),
            'paternal_last_name' => trim((string)$this->input->post('paternal_last_name')),
            'maternal_last_name' => trim((string)$this->input->post('maternal_last_name')),
            'last_name' => trim((string)$this->input->post('paternal_last_name') . ' ' . $this->input->post('maternal_last_name')),
            'email' => $jobseeker->email,
            'document_type' => $jobseeker->document_type,
            'document_number' => $jobseeker->document_number,
            'cell_phone' => $mobile,
            // 'home_phone' => $jobseeker->home_phone ? trim($jobseeker->home_phone) : '',
            'birthdate' => $this->input->post('dob'),
            'gender' => $this->input->post('gender') ? $this->input->post('gender') : null,
            'nationality' => $this->input->post('born_country'),
            'civil_status' => $this->input->post('civil_status') ? $this->input->post('civil_status') : null,
            'n_children' => 0,
            'disability' => $disability,
            'disability_type' => $disability ? $this->input->post('disability_type') : null,
            'domiciled' => $this->input->post('domiciled'),
            'worked_in_overall' => $worked_in_overall ? 1 : 0,
            'born_country' => $born_country,
            'born_department' => $born_country == '56' ? $this->input->post('born_department') : '',
            'born_province' => $born_country == '56' ? $this->input->post('born_province') : '',
            'born_district' => $born_country == '56' ? $this->input->post('born_district') : '',
            'place_birth' => $this->input->post('place_birth'),
            'department' => $current_country == '56' ? $this->input->post('current_department') : '',
            'province' => $current_country == '56' ? $this->input->post('current_province') : '',
            'district' => $current_country == '56' ? $this->input->post('current_district') : '',
            'domicile' => $this->input->post('present_address'),
            'reference' => $this->input->post('present_address'),
            'level_education' => $this->input->post('level_education'),
            'specialty' => $this->input->post('specialty'),
            // 'degree_obtained' => $this->input->post('degree_obtained'),
            
            'pension_affiliated' => $pension_affiliated,
            'pension_name' => $pension_affiliated ? $pension_name : '',
            'pension_type' => $pension_affiliated && $pension_name == 'AFP' ? $this->input->post('pension_type') : '',
            'pension_change' => $pension_affiliated ? $this->input->post('pension_change') : null,
            'pension_change_type' => $pension_affiliated && $this->input->post('pension_change') == '1' ? $this->input->post('pension_change_type') : null,
            'pension_join' => $pension_affiliated == 0 && $this->input->post('pension_join_name') != '' ? 1 : 0,
            'pension_join_name' => $pension_affiliated == 0 ? $this->input->post('pension_join_name') : '',
            'pension_join_type' => $pension_affiliated == 0 && $this->input->post('pension_join_name') == 'AFP' ? $this->input->post('pension_join_type') : '',
         
            // 'pension_retired' => $pension_name == 'AFP' ? $this->input->post('pension_retired') : null,
            // 'pension_affiliation_date' => $pension_name == 'AFP' ? format_date($this->input->post('pension_affiliation_date')) : null,
            // 'pension_cuspp' => $pension_name == 'AFP' ? $this->input->post('pension_cuspp') : '',
            'bank_name' => $this->input->post('bank_name'),
            'bank_account_number' => $i_have_account_number ? trim($this->input->post('bank_account_number')) : '',
            'bank_account_type' => $i_have_account_number ? trim($this->input->post('bank_account_type')) : 'Ahorro',
            'bank_interbank_account_number' => $i_have_account_number ? trim($this->input->post('bank_interbank_account_number')) : '',
            'payment_period' => $staff_request ? $staff_request->salary_delivery_period : '',
            'type_remuneration' => '',
            'seeker_ID' => $job_seeker_id,
            'job_id' => $job_id,
            'fifth_category_income' => $this->input->post('fifth_category_income'),
            'attached_document_license' => $this->input->post('attached_document_license') ? $this->input->post('attached_document_license') : '',
            'driver_license' => $this->input->post('driver_license') ? 1 : 0,
            'driver_license_type' => $this->input->post('driver_license') ? $this->input->post('driver_license_type') : null,
            'unionized' => $this->input->post('unionized') ? 1 : 0,
            'degree_obtained_year' => $this->input->post('degree_obtained_year') ? $this->input->post('degree_obtained_year') : null,
            'degree_obtained_type' => $this->input->post('degree_obtained_type') ? $this->input->post('degree_obtained_type') : null,
            'degree_obtained_institution' => $this->input->post('degree_obtained_institution'),
            'payment_cts_bank_name' => $this->input->post('payment_cts_bank_name'),
            'payment_cts_currency' => $this->input->post('payment_cts_currency'),
            'form_rtps_file_path' => null,
            'evicertia_send_date' => null,
        	'evicertia_status' => 1,
        	'evicertia_unique_id' => null,
        	'evicertia_url_push_notification' => null,
            'evicertia_data_log' => null,
            'evicertia_error' => null
        ];        

        $this->db->trans_start();
    
        $form_rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'seeker_ID' => $job_seeker_id,
            'job_id' => $job_id
        ])->row();

        if ($form_rtps) {
            $form_id = $form_rtps->ID;
            $this->db->where('ID', $form_id);
            $this->db->update('tbl_seeker_form_rtps', $data_form);
        } else {
            $this->db->insert('tbl_seeker_form_rtps', $data_form);
            $form_id = $this->db->insert_id();
        }

        //Save RTPS
        $this->db->where('form_ID', $form_id);
        $this->db->delete('tbl_form_rtps_rightful_claimants');

        foreach ($rightful_claimants as $person) {

            $kinship = $person['kinship'];
            $ubigeo = isset($person['ubigeo']) ? $person['ubigeo'] : ''; 

            $data_person = [
                'first_name' => $person['first_name'],
                'paternal_last_name' => $person['paternal_last_name'],
                'maternal_last_name' => $person['maternal_last_name'],
                'last_name' => $person['paternal_last_name'] . ' ' . $person['maternal_last_name'],
                'document_type' => $person['document_type'],
                'document_number' => $person['document_number'],
                'birthdate' => format_date($person['birthdate']),
                'gender' => $person['gender'],
                'kinship' => $kinship,
                'kinship_cert_type' => isset($person['family_bond_cert_type']) && $person['family_bond_cert_type'] ? $person['family_bond_cert_type'] : 0,
                'kinship_cert_attached' => isset($person['attached_cert_cohabitation']) && $person['attached_cert_cohabitation'] ? $person['attached_cert_cohabitation'] : null,
                // 'kinship_cert_code' => $person['family_bond_cert_code'],
                // 'place_birth' => $kinship == '4' || $kinship == '5' || $kinship == '6' ? $person['place_birth'] : '',
                // 'place_birth_certificate' => $kinship == '4' || $kinship == '5' || $kinship == '6' ? $person['place_birth_certificate'] : '',
                'live_same_domicile' => $person['live_same_domicile'],
                'domicile' => isset($person['domicile']) && !$person['live_same_domicile'] ? $person['domicile'] : '',
                'domicile_number' => isset($person['domicile_number']) && !$person['live_same_domicile'] ? $person['domicile_number'] : null,
                'domicile_way_id' => isset($person['domicile_way_id']) && !$person['live_same_domicile'] ? $person['domicile_way_id'] : null,
                // 'domicile_interior' => isset($person['domicile_interior']) && !$person['live_same_domicile'] ? $person['domicile_interior'] : null,
                'ubigeo' =>  $ubigeo,
                'attached_document_number' => $person['attached_document'],
                // 'nationality_id' => $person['nationality'] ? $person['nationality'] : null,
                'form_ID' => $form_id
            ];
            
            $this->db->insert('tbl_form_rtps_rightful_claimants', $data_person);
        }

        //Save Education 
        $educations = $this->input->post('education') ? $this->input->post('education') : [];

        foreach ($educations as $education_data) {

            $education_id = $education_data['id'];

            $studying = $education_data['studying'] == "true";
            $start_date = $education_data['year_start_date'] . "-" . $education_data['month_start_date'] . '-01';
            $end_date = null;

            if (!$studying) {
                $end_date = $education_data['year_end_date'] . "-" . $education_data['month_end_date'] . '-01';
            }
        
            $edu_array = [
                'seeker_ID' => $job_seeker_id,
                'degree_title' => $education_data['degree_title'],
                'major' => $education_data['major_subject'],
                'country' => $education_data['edu_country'],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'institution_educational_type_id' => isset($education_data['institution_educ_type']) && $education_data['institution_educ_type'] ? $education_data['institution_educ_type']: null,
                'institution_educational_class_id' => isset($education_data['institution_educ_class']) && $education_data['institution_educ_class'] ? $education_data['institution_educ_class'] : null,
                'institution_type_id' => isset($education_data['institution_type']) && $education_data['institution_type'] ? $education_data['institution_type'] : null,
                'institution' => isset($education_data['institution']) && $education_data['institution'] ? $education_data['institution'] : null,
                'career' => isset($education_data['career']) && $education_data['career']  ? $education_data['career'] : null,
                'tuition_number' => isset($education_data['tuition_number']) && $education_data['tuition_number'] ? $education_data['tuition_number'] : null
            ];
            
            if ($this->Jobseeker_academic->find($education_id)) {
                $this->db->where('ID', $education_id);   
                $this->db->update('tbl_seeker_academic', $edu_array);
            } else {
                $edu_array['dated'] = date("Y-m-d H:i:s");
                $this->db->insert('tbl_seeker_academic', $edu_array);
            }
        }
        
		$this->db->trans_complete();

		$trans_status = $this->db->trans_status(); 

        $city = '';

        if ($current_country == $country_id) {
            $city = $this->input->post('current_department') . ', ' .  $this->input->post('current_province') . ', ' .  $this->input->post('current_district');
        } else {
            $city = $this->input->post('city');
        }
        
        $seeker_name = trim((string)$this->input->post('first_name')) . ' '. trim((string)$this->input->post('second_name'));
        
        //Actualizar datos postulante
        $seeker_data_update = [
            'first_name' => trim($seeker_name),
            'paternal_last_name' => trim((string)$this->input->post('paternal_last_name')),
            'maternal_last_name' => trim((string)$this->input->post('maternal_last_name')),
            'last_name' => trim((string)$this->input->post('paternal_last_name') . ' ' . $this->input->post('maternal_last_name')),
            'dob' => $this->input->post('dob'),
            'gender' => $this->input->post('gender') ? $this->input->post('gender') : null,
            'civil_status' => $this->input->post('civil_status') ? $this->input->post('civil_status') : null,
            'disability' => $disability ? $this->input->post('disability_type') : null,
            'mobile' => $mobile,
            'nationality' => $this->input->post('born_country'),
            'country' => $current_country,
            'city'  => $city,
            'present_address' => $this->input->post('present_address'),
            'linkedin' => $this->input->post('linkedin'),
            'facebook' => $this->input->post('facebook'),
            'way_id' => $this->input->post('way_id') ? $this->input->post('way_id') : null,
            'address_number' => $this->input->post('address_number') ? $this->input->post('address_number') : null,
            'domicile_interior' =>  $this->input->post('domicile_interior') ? $this->input->post('domicile_interior') : null,
        ];

        $this->Job_seeker->update($job_seeker_id, $seeker_data_update);

        //Actualizar adicional info postulante
        $emergency_contact_kinship = $this->input->post('emergency_contact_kinship');
        $contact_emergency_mobile = trim($this->input->post('emergency_contact_full_mobile_phone_number'));

        if ($seeker_additional_info) {
            $this->Jobseeker_additional_info->update($seeker_additional_info->ID, [
                'seeker_ID' => $job_seeker_id,
                'emergency_contact_kinship' => $emergency_contact_kinship != '' ? $this->input->post('emergency_contact_kinship') : null,
                'emergency_contact_name' => $emergency_contact_kinship != '' ? $this->input->post('emergency_contact_name') : null,
                'emergency_contact_mobile' => $emergency_contact_kinship != '' ? $contact_emergency_mobile : null
            ]);
        } else {
            $this->Jobseeker_additional_info->add([
                'seeker_ID' => $job_seeker_id,
                'emergency_contact_kinship' => $emergency_contact_kinship != '' ? $this->input->post('emergency_contact_kinship') : null,
                'emergency_contact_name' => $emergency_contact_kinship != '' ? $this->input->post('emergency_contact_name') : null,
                'emergency_contact_mobile' => $emergency_contact_kinship != '' ? $contact_emergency_mobile : null
            ]);
        }

        if ($trans_status != true) {
            log_message('error', 'No se pudo guardar la Ficha RTPS');
            flash_message('danger', 'Ha ocurrido un error al tratar de guardar los datos.');
            echo json_encode([
                'status' => false,
                'data' => [
                    'redirect_url' => site_url('jobseeker/form_rtps/index/' . $contract_document_type_id . '/' . $process_id)
                ]
            ]);
            return;           
        }

        flash_message('success', 'Los datos de la Ficha RTPS han sido guardados');

        echo json_encode([
            'status' => true,
            'data' => [
                'redirect_url' => site_url('jobseeker/form_rtps/show/' . $contract_document_type_id . '/' . $process_id)
            ]
        ]);
    }

    public function show($contract_document_type_id = 0, $process_id = 0)
    {
        $contract_document_type = $this->Recruitment_contract_document_type->get_info_by_id($contract_document_type_id);

        if (!$contract_document_type) {
            show_404();
        }

        $process = $this->Recruitment_process->find($process_id);

        if (!$process) {
            show_404();
        }

        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);

        if ($process_country->iso_3166_1_alpha2 != 'PE') {
            show_404();
        }

        $job_seeker_id = $this->session->userdata('user_id');

        $data['title'] = "Ficha de datos del trabajador - " . SITE_NAME;
        $data['ads_row'] = $this->ads;

        $rs_candidate = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($job_seeker_id);

        if (!$rs_candidate) {
            show_404();
        }

        $form_rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'seeker_ID' => $job_seeker_id,
            'job_id' => $process->job_ID  
        ])->row();

        if (!$form_rtps) {
            redirect('jobseeker/form_rtps/index/' . $contract_document_type_id . '/' . $process_id);
        }

        $job = $this->Posted_job->find($form_rtps->job_id);
        $staff_request = null;

        if ($job) {
            $staff_request = $this->Staff_request->find($job->request_ID);
        }
        
        $data['company'] = $this->Jobseeker_form_rtps->get_form_company(
            $job_seeker_id, $rs_candidate->job_ID
        );
        $data['staff_request'] = $staff_request;
        $data['form_rtps'] = $form_rtps;
        $data['rtps_rightful_claimants'] = $this->Jobseeker_form_rtps->get_rightful_claimants_by_form_id(
            $form_rtps->ID
        );
        $data['contract_document_type'] = $contract_document_type;
        $data['process_country'] = $process_country;
        $data['process'] = $process;

        $this->load->view('jobseeker/requested_documents/show_form_rtps_view', $data);
    }

    public function upload_document()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        $seeker_id = $this->session->userdata('user_id');
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/rtps_rightful_claimants/identification_documents/' . md5(uniqid($seeker_id, true)) . $file_ext;
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        echo json_encode([
            'success' => true,
            'url_file' => file_url($path),
            'file_name' => $path
        ]);
    }

    public function upload_license()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        $seeker_id = $this->session->userdata('user_id');
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/rtps_rightful_claimants/license_documents/' . md5(uniqid($seeker_id, true)) . $file_ext;
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        echo json_encode([
            'success' => true,
            'url_file' => file_url($path),
            'file_name' => $path
        ]);
    }

    public function upload_cert_cohabitation()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        $seeker_id = $this->session->userdata('user_id');
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/rtps_rightful_claimants/certificates/' . md5(uniqid($seeker_id, true)) . $file_ext;
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        echo json_encode([
            'success' => true,
            'url_file' => file_url($path),
            'file_name' => $path
        ]);
    }

    public function get_family_bond_certs($kinship_id)
    {
        $certs = $this->db->get_where('tbl_kinship_certificates', [
            'kinship_id' => $kinship_id
        ])->result();

        echo json_encode([
            'data' => $certs
        ]);
    }

    public function sign_affidavit()
    {
        $this->load->library(
            'Form_rtps/Form_rtps_send_sign_evicertia_lib', 
            null, 
            'Form_rtps_send_sign_evicertia_lib'
        );

        $status = $this->Form_rtps_send_sign_evicertia_lib->send($this->input->post('id'));        
        
        echo json_encode([
            'success' => $status
        ]);
    }

    public function form_education()
    {
        $institution_educ_type = $this->input->post('institution_educ_type');
        $institution_type = $this->input->post('institution_type');
        $institution = $this->input->post('institution');
        
		$data['degrees_studies'] = $this->Qualification->get_all_records_by_val('Estudios');
		$data['countries'] = $this->Country->get_all_countries();
		$data['institution_educ_types'] = $this->Institution_educational_type->all(['active' => 1]);
		$data['institution_educ_class'] = $this->Institution_educational_class->all(['active' => 1]);
		$data['institution_types'] = $this->Institution_type->all(['active' => 1]);

        $data['institutions'] = $this->Institution->all([
			'active' => 1, 
			'institution_educational_type_id' => $institution_educ_type, 
			'institution_type_id' => $institution_type
		]);
		$data['careers'] = $this->Career->get_all_by_institution_code($institution);

		$this->load->view('jobseeker/requested_documents/common/form_rtps_add_education', $data);
    }
}
