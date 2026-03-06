<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate_create_candidate_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function create($params)
    {
        list($is_success, $message) = $this->validate($params);
     
        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }
        
        return  $this->create_candidate($params);  
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
        $this->form_validation->set_rules('first_name', 'first_name', 'trim|required');
        $this->form_validation->set_rules('paternal_last_name', 'paternal_last_name', 'trim|required');
        $this->form_validation->set_rules('maternal_last_name', 'maternal_last_name', 'trim|required');
        $this->form_validation->set_rules('identification_document_type_id', 'identification_document_type_id', 'trim|required|integer');
        $this->form_validation->set_rules('identification_document_number', 'identification_document_number', 'trim|required|integer|min_length[7]');
        $this->form_validation->set_rules('birth_date', 'birth_date', 'required|valid_date');
        $this->form_validation->set_rules('mobile', 'mobile', 'required|is_valid_phone_number');
        $this->form_validation->set_rules('gender', 'gender', 'required|in_list[1,2]');
        $this->form_validation->set_rules('country_id', 'country_id', 'required');
        $this->form_validation->set_rules('department_id', 'department_id', 'required');
        $this->form_validation->set_rules('province_id', 'province_id', 'required');
        $this->form_validation->set_rules('district_id', 'district_id', 'required');
               
        $this->form_validation->set_message('required', 'El campo %s es requerido');
        
        $email = trim($params['email']);
        $document_type = trim($params['identification_document_type_id']);
        $document_number = trim($params['identification_document_number']);

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        if (!$this->check_ubigeo($params['country_id'], $params['department_id'], $params['province_id'], $params['district_id'])) {
            return [false, 'La ubicación del ubigeo actual es incorrecta'];
        }

        $this->db->select('id');
        $this->db->from('tbl_identity_document_types');
        $this->db->where('id', $document_type);
        $count_document_type = $this->db->count_all_results();

        if ($count_document_type == 0) {
            return [false, 'El tipo de documento es inválido'];
        }

        $this->db->select('ID');
        $this->db->from('tbl_job_seekers');
        $this->db->where('email', $email);
        $count_email = $this->db->count_all_results();

        if ($count_email > 0) {
            return [false, 'El email ya está registrado'];
        }

        $this->db->select('ID');
        $this->db->from('tbl_job_seekers');
        $this->db->where('document_type', $document_type);
        $this->db->where('document_number', $document_number);
        $count_document = $this->db->count_all_results();

        if ($count_document > 0) {
            return [false, 'El tipo y número de documento ya está registrado'];
        }

        return [true, 'OK'];
    }

    public function create_candidate($params)
    {   
        $first_name = trim($params['first_name']);
        $paternal_last_name = trim($params['paternal_last_name']);
        $maternal_last_name = trim($params['maternal_last_name']);
        $document_number = trim($params['identification_document_number']);

        $current_date = date('Y-m-d H:i:s');

        $candidate_data = [
            'email' => trim($params['email']),
			'first_name' => $first_name,
			'paternal_last_name' => $paternal_last_name,
			'maternal_last_name' => $maternal_last_name,
			'last_name' => trim($paternal_last_name . ' ' . $maternal_last_name),
			'document_number' => $document_number,
			'document_type' => trim($params['identification_document_type_id']),
			'password' => do_hashing($document_number),
			'dob' => $params['birth_date'],
			'mobile' => trim($params['mobile']),
			'country' => $params['country_id'],
			'department_id' => $params['department_id'],
            'province_id' => $params['province_id'],
            'district_id' => $params['district_id'],
			'gender' => $params['gender'],
            'nationality' => $params['country_id'] == 56 && $params['identification_document_type_id'] == 1 ? 56 : null,
			'ip_address' => $this->input->ip_address(),
			'dated' => $current_date,
            'sts' => 'active'
		];

        $this->db->trans_start();

        $candidate_id = $this->Job_seeker->add_job_seekers($candidate_data);

        if (!$candidate_id) {
            return [
                'status' => false, 
                'message' => '¡EL candidato no pudo ser creado!',
            ];
        }

		$this->Jobseeker_additional_info->add(['seeker_ID' => $candidate_id]);
		$this->Job_seeker->accept_legal_terms($candidate_id);

		$this->db->insert('tbl_seeker_config', [
            'key' => 'receive_job_ads',
            'value' => 0,
            'seeker_ID' => $candidate_id
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return [
                'status' => false,
                'message' => 'Error al crear y asignar el postulante al proceso'
            ];
        }

        $params['candidate_id'] = $candidate_id;

        $this->send_notification_candidates($params);

        return [
            'status' => true,
            'message' => 'Candidato ha sido creado',
            'data' => [
                'id' => (int)$candidate_id
            ]
        ];  
    }

    private function send_notification_candidates($params)
    {
        $send_email_candidate = $params['send_email_candidate'] ?? 0;

        if ($send_email_candidate == 1) {
            $this->send_email_candidate($params['candidate_id']);
        }

        $send_whatsapp_candidate = $params['send_whatsapp_candidate'] ?? 0;

        if ($send_whatsapp_candidate == 1) {
            $this->send_whatsapp_candidate($params['candidate_id']);
        }
    }

    public function check_ubigeo($country_id, $department_id, $province_id, $district_id)
    {
        $count = $this->db->get_where(
            'tbl_ubigeos', [
                'country_id' => $country_id,
                'order_administrative1_code' => $department_id,
                'order_administrative2_code' => $province_id,
                'order_administrative3_code' => $district_id
            ]
        )->num_rows();

        if ($count == 0) {
            return false;
        }

        return true;
    }

    private function send_email_candidate($candidate_id)
    {
        $this->load->library('Mail_template/Candidate/Candidate_registration');

        $candidate = $this->Job_seeker->find($candidate_id);

        if (!$candidate) {
            return false;
        }

        $emails = $candidate->email;

        $mail_vars = $this->candidate_registration->build([
            'candidate_name' => $candidate->first_name,
            'url_link' => site_url('login')
        ]);

        $this->email->init();
        $this->email->to($emails);
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }

    private function send_whatsapp_candidate($candidate_id)
    {
        $this->load->library('Whatsapp/Candidate/Whatsapp_candidate_registration_lib');
        $this->whatsapp_candidate_registration_lib->send($candidate_id);
    }
}
