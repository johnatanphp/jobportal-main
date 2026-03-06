<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Form_rtps extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        show_404();
        
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

        //Load libraries
        $this->load->library('upload');
       
        $this->ads = $this->Ad->get_ads();
    }

    public function index($job_seeker_id = 0)
    {
        $jobseeker = $this->Job_seeker->find($job_seeker_id);

        if (!$jobseeker) {
            show_404();
        }

        $rs_candidate = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($job_seeker_id);

        if (!$rs_candidate) {
            $this->load->view('embed/jobseeker/requested_documents/common/list_requested_documents_error_view', []);
            return;
        }

        $seeker_additional_info = $this->Jobseeker_additional_info->get_record_by_userid($job_seeker_id);

        $job_id = $rs_candidate->job_ID;

        $form_rtps = $this->Jobseeker_form_rtps->get_last_record(0,  $job_seeker_id);

        if ($form_rtps && $form_rtps->evicertia_status == 3 && $form_rtps->job_id == $job_id) {
            redirect(site_url_embed('embed/jobseeker/form_rtps/show/' . $job_seeker_id));
        }

        $worked_in_overall = $this->Job_seeker->has_worked_in_overall(
            $jobseeker->document_number
        );
        
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
        $data['result_studies'] = $this->Job_seeker->get_qualification_by_jobseeker_id($job_seeker_id);
        $data['document_types'] = $this->Identity_document_type->all();

        @list($current_department, $current_province, $current_district) = explode(',', $jobseeker->city); 
        $data['provinces'] = $this->Ubigeo->get_provinces_by($current_department);
        $data['districts'] = $this->Ubigeo->get_districts_by($current_province);
        
        $data['seeker_studies'] = $this->Job_seeker->get_qualification_by_jobseeker_id(
            $job_seeker_id
        );

        $data['result_banks'] = $this->Bank->get_all_banks();        
        $data['worked_in_overall'] = $worked_in_overall;
        $data['seeker_additional_info'] = $seeker_additional_info;
        $data['kinship_types'] = $this->Kinship->all(['active' => 1]);
        $data['result_ways'] = $this->Way->all(['active' => 1]);
        $data['disabilities'] = $this->Disability->all(['active' => 1]);     
        
        $this->form_validation->set_rules('domiciled', 'Domiciliado', 'trim|required|in_list[1,0]');
        $this->form_validation->set_rules('mobile_code', 'Movil codigo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('mobile', 'Movil', 'trim|required|numeric');
        $this->form_validation->set_rules('present_address', 'Domicilio actual', 'trim|required|max_length[100]|strip_all_tags');
        $this->form_validation->set_rules('place_birth', 'Lugar de nacimiento', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('level_education', 'Nivel educativo', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('specialty', 'Especialidad', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('degree_obtained', 'Grado obtenido', 'trim|required|strip_all_tags');        
        $this->form_validation->set_rules('pension_affiliation', 'Afiliado a', 'trim|strip_all_tags');
        $this->form_validation->set_rules('bank_name', 'Nombre de la entidad', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('fifth_category_income', '5ta Categoria', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('driver_license', 'Licencia de conducir', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('unionized', 'Sindicalizado', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('degree_obtained_year', 'Año de egreso', 'trim|strip_all_tags');
        $this->form_validation->set_rules('degree_obtained_institution', 'Institución educativa', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('payment_cts_bank_name', 'Banco CTS', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('payment_cts_currency', 'Pago CTS Moneda', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('linkedin', 'Linkedin', 'trim|strip_all_tags');
        $this->form_validation->set_rules('facebook', 'Facebook', 'trim|strip_all_tags');
        $this->form_validation->set_rules('way_id', 'Domicilio Via', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('address_number', 'Domicilio numero', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('nationality', 'Nacionalidad', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('disability_type', 'Discapacidad', 'trim|required|strip_all_tags');

        $this->form_validation->set_rules('emergency_contact_kinship', 'Parentesco', 'trim|strip_all_tags');

        if ($this->input->post('emergency_contact_kinship') != '') {
            $this->form_validation->set_rules('emergency_contact_name', 'Parentesco Nombre', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('emergency_contact_mobile_code', 'Emergencia Teléfono código', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('emergency_contact_mobile', 'Emergencia Teléfono', 'trim|required|numeric');
        }

        $born_country = $this->input->post('born_country');

        if ($born_country == '56') {
            $this->form_validation->set_rules('born_department', 'Departamento', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('born_province', 'Provincia', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('born_district', 'Distrito', 'trim|required|strip_all_tags');
        }

        $current_country = $this->input->post('current_country');
        if ($current_country == '56') {
            $this->form_validation->set_rules('current_department', 'Departamento', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('current_province', 'Provincia', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('current_district', 'Distrito', 'trim|required|strip_all_tags');
        }

        $pension_affiliation = $this->input->post('pension_affiliation');
        
        if ($pension_affiliation == 'AFP') {
            $this->form_validation->set_rules('pension_affiliation_date', 'Fecha de afiliación', 'trim|strip_all_tags');
            $this->form_validation->set_rules('pension_name_afp', 'NOmbre AFP', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('pension_retired', 'Jubilado', 'trim|required|strip_all_tags');
            $this->form_validation->set_rules('pension_cuspp', 'CUSPP', 'trim|strip_all_tags');
        }

        $rightful_claimants = (array)$this->input->post('rightful_claimants');
        
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('embed/jobseeker/requested_documents/form_rtps', $data);
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
                redirect(site_url_embed('embed/jobseeker/requested_documents/form_rtps/show/' . $job_seeker_id));
                return;
            }
        }

        $home_phone = '';
        $mobile = $this->input->post('mobile_code') . ' ' . $this->input->post('mobile');

        if ($this->input->post('home_phone') != '') {
            $home_phone = $this->input->post('home_phone_code')  . ' ' . $this->input->post('home_phone');
        }

        $staff_request = null;
        
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);   
               
        $disability = (int)$this->input->post('disability_type') > 0;

        $data_form = [
            'creation_date' => date('Y-m-d H:i:s'),
            'version' => 'GP-FO-004<br>Versión: 07',
            'first_name' => $jobseeker->first_name,
            'paternal_last_name' => $jobseeker->paternal_last_name,
            'maternal_last_name' => $jobseeker->maternal_last_name,
            'last_name' => $jobseeker->last_name,
            'email' => $jobseeker->email,
            'document_type' => $jobseeker->document_type,
            'document_number' => $jobseeker->document_number,
            'cell_phone' => $mobile,
            'home_phone' => trim($jobseeker->home_phone),
            'birthdate' => $jobseeker->dob,
            'gender' => $jobseeker->gender,
            'nationality' => $this->input->post('nationality'),
            'civil_status' => $jobseeker->civil_status,
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
            'degree_obtained' => $this->input->post('degree_obtained'),
            'pension_affiliation' => $this->input->post('pension_affiliation'),
            'pension_retired' => $pension_affiliation == 'AFP' ? $this->input->post('pension_retired') : null,
            'pension_affiliation_date' => $pension_affiliation == 'AFP' ? ($this->input->post('pension_affiliation_date') ? format_date($this->input->post('pension_affiliation_date')) : null) : null,
            'pension_name_afp' => $pension_affiliation == 'AFP' ? $this->input->post('pension_name_afp') : '',
            'pension_cuspp' => $pension_affiliation == 'AFP' ? $this->input->post('pension_cuspp') : '',
            'bank_name' => $this->input->post('bank_name'),
            //'bank_account_number' => $this->input->post('bank_account_number'),
            'bank_account_type' => 'Ahorro',
            'payment_period' => $staff_request ? $staff_request->salary_delivery_period : '',
            'type_remuneration' => '',
            'seeker_ID' => $job_seeker_id,
            'job_id' => $job_id,
            'fifth_category_income' => $this->input->post('fifth_category_income'),
            'driver_license' => $this->input->post('driver_license') ? 1 : 0,
            'driver_license_type' => $this->input->post('driver_license') ? $this->input->post('driver_license_type') : null,
            'unionized' => $this->input->post('unionized') ? 1 : 0,
            'degree_obtained_year' => $this->input->post('degree_obtained_year') ? $this->input->post('degree_obtained_year') : null,
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
                'kinship_cert_type' => $person['family_bond_cert_type'],
                'kinship_cert_code' => $person['family_bond_cert_code'],
                'place_birth' => $kinship == '4' || $kinship == '5' || $kinship == '6' ? $person['place_birth'] : '',
                'place_birth_certificate' => $kinship == '4' || $kinship == '5' || $kinship == '6' ? $person['place_birth_certificate'] : '',
                'live_same_domicile' => $person['live_same_domicile'],
                'domicile' => isset($person['domicile']) && !$person['live_same_domicile'] ? $person['domicile'] : '',
                'domicile_number' => isset($person['domicile_number']) && !$person['live_same_domicile'] ? $person['domicile_number'] : null,
                'domicile_way_id' => isset($person['domicile_way_id']) && !$person['live_same_domicile'] ? $person['domicile_way_id'] : null,
                'domicile_interior' => isset($person['domicile_interior']) && !$person['live_same_domicile'] ? $person['domicile_interior'] : null,
                'ubigeo' =>  $ubigeo,
                'attached_document_number' => $person['attached_document'],
                'kinship_cert_attached' => $person['attached_cert_cohabitation'],
                'nationality_id' => $person['nationality'] ? $person['nationality'] : null,
                'form_ID' => $form_id
            ];
            
            $this->db->insert('tbl_form_rtps_rightful_claimants', $data_person);
        }

        //Save Education 
        $educations = $this->input->post('education');

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

        if ($current_country == '56') {
            $city = $this->input->post('current_department') . ', ' .  $this->input->post('current_province') . ', ' .  $this->input->post('current_district');
        } else {
            $city = $this->input->post('city');
        }
    
        $seeker_data_update = [
            'disability' => $disability ? $this->input->post('disability_type') : null,
            'mobile' => $mobile,
            'nationality' => $this->input->post('nationality'),
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

        $contact_emergency_mobile = $this->input->post('emergency_contact_mobile_code') . ' ' . $this->input->post('emergency_contact_mobile');

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
            flash_message('danger', 'Ha ocurrido un error al tratar de guardar los datos.');
            redirect(site_url_embed('embed/jobseeker/requested_documents/form_rtps'));
            return;           
        }

        flash_message('success', 'Los datos de la Ficha RTPS han sido guardados');
        redirect(site_url_embed('embed/jobseeker/requested_documents/form_rtps/show/' . $job_seeker_id));
    }

    public function show($job_seeker_id = 0)
    {
        //$job_seeker_id = $this->session->userdata('user_id');

        $data['title'] = "Ficha de datos del trabajador - " . SITE_NAME;
        $data['ads_row'] = $this->ads;

        $rs_candidate = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($job_seeker_id);

        if (!$rs_candidate) {
            $this->load->view('embed/jobseeker/requested_documents/common/list_requested_documents_error_view', $data);
            return;
        }
    
        $form_rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'seeker_ID' => $job_seeker_id,
            'job_id' => $rs_candidate->job_ID  
        ])->row();

        if (!$form_rtps) {
            redirect(site_url_embed('embed/jobseeker/requested_documents/form_rtps/index/' . $job_seeker_id));
        }

        $data['form_rtps'] = $form_rtps;
        $data['rtps_rightful_claimants'] = $this->Jobseeker_form_rtps->get_rightful_claimants_by_form_id(
            $form_rtps->ID
        );
        
        $this->load->view('embed/jobseeker/requested_documents/form_rtps_show', $data);
    }

    public function upload_document($seeker_id = 0)
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

        //$seeker_id = $this->session->userdata('user_id');
        
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

    public function upload_cert_cohabitation($seeker_id = 0)
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

        //$seeker_id = $this->session->userdata('user_id');
        
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

    public function get_family_bond_certs()
    {   
        $kinship_id = $this->input->get('family_bond_id');

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

		$this->load->view('embed/jobseeker/requested_documents/common/form_rtps_add_education', $data);
    }
}
