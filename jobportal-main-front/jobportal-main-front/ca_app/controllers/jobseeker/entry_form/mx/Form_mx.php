<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * Gestiona las operaciones para la Ficha de ingreso de Mexico
 * MX = Mexico 
 * 
 */

class Form_mx extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        if (!candidate_is_process_contracting()) {
            show_404();
        }

        //load model
        $this->load->model('Recruitment_contract_document_type');
        $this->load->model('Recruitment_process');
        $this->load->model('Entry_form');
        $this->load->model('Entry_form_mx');
        $this->ads = $this->Ad->get_ads();
    }

    public function index($contract_document_type_id = 0, $process_id = 0)
    {
        $contract_document_type = $this->Recruitment_contract_document_type->get_info_by_id($contract_document_type_id);

        if (!$contract_document_type) {
            show_404();
        }

        // Si el documento no pertenece al Grupo Ficha de ingreso
        // No mostrar el formulario
        if ($contract_document_type->group_id != 1) {
            show_404();
        }

        $seeker_id = $this->session->userdata('user_id');
      
        $entry_form = $this->Entry_form->find([
            'process_id' => $process_id,
            'seeker_id' => $seeker_id
        ]);

        $this->load->library('Entry_form/mx/Entry_form_data_form_mx_service');
        $data = $this->entry_form_data_form_mx_service->get_data($process_id, $seeker_id);
        $data['ads_row'] = $this->ads;
        $data['contract_document_type'] = $contract_document_type;
        $this->load->view('jobseeker/entry_form/mx/form', $data);
    }

    public function show($contract_document_type_id = 0, $process_id = 0)
    {
        $contract_document_type = $this->Recruitment_contract_document_type->get_info_by_id($contract_document_type_id);

        if (!$contract_document_type) {
            show_404();
        }

        // Si el documento no pertenece al Grupo Ficha de ingreso
        // No mostrar el formulario
        if ($contract_document_type->group_id != 1) {
            show_404();
        }

        $seeker_id = $this->session->userdata('user_id');
      
        $entry_form = $this->Entry_form->find([
            'process_id' => $process_id,
            'seeker_id' => $seeker_id
        ]);

        // Si la ficha no existe redirigir al formulario de registro
        if (!$entry_form) {
            redirect('jobseeker/entry_form/mx/form_mx/index/' . $contract_document_type_id . '/' . $process_id);
        }

        $this->load->library('Entry_form/mx/Entry_form_data_show_mx_service');
        $data = $this->entry_form_data_show_mx_service->get_data($process_id, $seeker_id);
        $data['ads_row'] = $this->ads;
        $data['contract_document_type'] = $contract_document_type;
        $this->load->view('jobseeker/entry_form/mx/show', $data);
    }

    public function save()
    {
        $data_input = $this->input->post();
        $job_seeker_id = $this->session->userdata('user_id');

        $rs_candidate = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($job_seeker_id);

        if (!$rs_candidate) {
             echo json_encode([
                'status' => false,
                'message' => 'Proceso vinculado a esta ficha esta cerrado'
            ]);
            return;
        }

        $this->form_validation->set_rules('document_type_id', 'Tipo Doc. Identidad', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('document_number', 'Nro Doc. Identidad', 'trim|required|max_length[20]strip_all_tags');
        $this->form_validation->set_rules(
            'email', 
            'Email', 
            'trim|required|valid_email|edit_is_unique[tbl_job_seekers.email.' . $job_seeker_id .']|strip_all_tags', [
                'edit_is_unique' => 'El Email ingresado ya existe'
            ]);
        $this->form_validation->set_rules('first_name', 'Primer nombre', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('second_name', 'Segundo nombre', 'trim|strip_all_tags');
        $this->form_validation->set_rules('third_name', 'Tercer nombre', 'trim|strip_all_tags');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('dob', 'Fecha de nacimiento', 'trim|required|is_date_format[Y-m-d]');
        $this->form_validation->set_rules('civil_status', 'Estado civil', 'trim|required|in_list_db[tbl_civil_status.id]');
		$this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list_db[tbl_genders.id]');
        $this->form_validation->set_rules('full_mobile_phone_number', 'Teléfono móvil', 'required|is_valid_phone_number');
        $this->form_validation->set_rules('present_address', 'Dirección', 'trim|required|max_length[100]|strip_all_tags');
        $this->form_validation->set_rules('is_foreign', 'Eres extranjero', 'trim|required|in_list[1,0]');

        $is_foreign = $data_input['is_foreign'] ?? 0;

        if ($is_foreign) {
            $this->form_validation->set_rules('origin_country_id', 'Pais de origen', 'trim|required');
            $this->form_validation->set_rules('visa_type', 'Tipo de Visa', 'trim|required|strip_all_tags');
        }

        $this->form_validation->set_rules('social_security_number', 'Nro Seguridad Social', 'trim|required|max_length[20]|strip_all_tags');
        $this->form_validation->set_message('required', 'El campo %s es requerido');
		$this->form_validation->set_message('is_unique', 'El campo %s ya existe');

        if ($this->form_validation->run() === FALSE) {

            if (count($this->input->post()) > 0) {
                echo json_encode([
                    'status' => false,
                    'message' => 'Hay datos incorrectos. ' . current($this->form_validation->error_array())
                ]);
            } 
            return;
        }

        //$process_id = $rs_candidate->process_id;
        //dd($data_input);
        $process_id = $data_input['process_id'];
        $email = trim(strtolower($data_input['email']));
        $identity_document_type_id = $data_input['document_type_id'];
        $identity_document_number = trim($data_input['document_number']);
        $mobile = trim($data_input['full_mobile_phone_number']);
        $name = trim($data_input['first_name'] . ' ' . $data_input['second_name'] . ' ' . $data_input['third_name']);

        $contract_document_type_id = $data_input['contract_document_type_id'];
        
        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);

        $form_country_id = $process_country->ID;

        $data_entry_form = [
            'created_at' => date('Y-m-d H:i:s'),
            'seeker_id' => $job_seeker_id,
            'process_id' => $process_id,
            'has_digital_signature' => 0, //Envio de firma digital - Apagado
            'form_country_id' => $form_country_id
        ];        

        $this->db->trans_start();
    
        $entry_form = $this->db->get_where('tbl_entry_form', [
            'seeker_id' => $job_seeker_id,
            'process_id' => $process_id
        ])->row();

        $entry_form_id = null;
        $nationality_id = $is_foreign == 1 ? $data_input['origin_country_id'] : $form_country_id;

        $entry_form_mx = [
            'identity_document_type_id' => $identity_document_type_id,
            'identity_document_number' => $identity_document_number,
            'first_name' => trim((string)$data_input['first_name']),
            'second_name' => trim((string)$data_input['second_name']),
            'third_name' => trim((string)$data_input['third_name']),
            'paternal_last_name' => trim((string)$this->input->post('paternal_last_name')),
            'maternal_last_name' => trim((string)$this->input->post('maternal_last_name')),
            'email' => $email,
            'mobile_phone' => $mobile,
            'birthdate' => $this->input->post('dob'),
            'gender_id' => $this->input->post('gender') ? $this->input->post('gender') : null,
            'civil_status_id' => $this->input->post('civil_status') ? $this->input->post('civil_status') : null,
            'address' => $this->input->post('present_address'),
            'work_city' => $data_input['work_city'] ?? '',
            'social_security_number' => trim($data_input['social_security_number']),
            'origin_country_id' => $nationality_id,
            'visa_type' =>  $is_foreign == 1 ? $data_input['visa_type'] : null
        ];
    
        if ($entry_form) {
            // Actualizar ficha de ingreso Tabla base
            $entry_form_id = $entry_form->id;
    
            //Actualizar ficha de ingreso para el pais
            $this->db->where('entry_form_id', $entry_form_id);
            $this->db->update('tbl_entry_form_mx', $entry_form_mx);
        } else {
            // Registrar ficha de ingreso tabla base
            $this->db->insert('tbl_entry_form', $data_entry_form);
            $entry_form_id = $this->db->insert_id();

            // Registrar ficha de ingreso tabla datos por pais
            $entry_form_mx['entry_form_id'] = $entry_form_id;
            $this->db->insert('tbl_entry_form_mx', $entry_form_mx);
        }

        //Guardar derechohabientes
        $this->db->where('entry_form_id', $entry_form_id);
        $this->db->delete('tbl_entry_form_mx_rightful_claimants');

        $rightful_claimants = (array)($data_input['rightful_claimants'] ?? []);

        //Registar derechohabientes para la ficha de mexico
        foreach ($rightful_claimants as $person) {

            $kinship = $person['kinship'];

            $data_person = [
                'identity_document_type_id' => $person['document_type'],
                'identity_document_number' => trim($person['document_number']),
                'first_name' => trim($person['first_name']),
                'second_name' => trim($person['second_name'] ?? ''),
                'paternal_last_name' => trim($person['paternal_last_name']),
                'maternal_last_name' => trim($person['maternal_last_name']),
                'gender_id' => $person['gender'],
                'birthdate' => $person['birthdate'],
                'kinship_id' => $kinship,
                'entry_form_id' => $entry_form_id
            ];
            
            $this->db->insert('tbl_entry_form_mx_rightful_claimants', $data_person);
        }
   
		$this->db->trans_complete();

		$trans_status = $this->db->trans_status(); 

        if ($trans_status !== true) {
            log_message('error', 'No se pudo guardar la Ficha de ingreso mx');
            flash_message('danger', 'Ha ocurrido un error al tratar de guardar los datos.');
            echo json_encode([
                'status' => false,
                'data' => [
                    'redirect_url' => site_url('jobseeker/entry_form/entry_form_base/form/' . $document_type_id . '/' . $process_id)
                ]
            ]);
            return;           
        }
     
        //Actualizar datos Tabla postulante
        $seeker_data_update = [
            'document_type' => $identity_document_type_id,
            'document_number' => $identity_document_number,
            'email' => $email,
            'first_name' => trim((string)$name),
            'paternal_last_name' => trim((string)$this->input->post('paternal_last_name')),
            'maternal_last_name' => trim((string)$this->input->post('maternal_last_name')),
            'last_name' => trim((string)$this->input->post('paternal_last_name') . ' ' . $this->input->post('maternal_last_name')),
            'dob' => $this->input->post('dob'),
            'gender' => $this->input->post('gender') ? $this->input->post('gender') : null,
            'civil_status' => $this->input->post('civil_status') ? $this->input->post('civil_status') : null,
            'mobile' => $mobile,
            'country' => $form_country_id,
            'nationality' => $nationality_id,
            'present_address' => $this->input->post('present_address')
        ];

        $this->Job_seeker->update($job_seeker_id, $seeker_data_update);

        flash_message('success', 'Los datos de la Ficha de ingreso han sido guardados');

        echo json_encode([
            'status' => true,
            'data' => [
                'redirect_url' => site_url('jobseeker/entry_form/mx/form_mx/show/' . $contract_document_type_id . '/' . $process_id)
            ]
        ]);
    }
}
