<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate_register extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        //Load Models
        $this->load->model('Identity_document_type');
        $this->load->model('Civil_status');
        $this->load->model('Disability');
        $this->load->model('Gender');
        $this->load->model('Workflow_client');
        $this->load->model('Recruitment_tray_candidate');

        if (!check_permission_tray_candidates()) {
            redirect('login');
        }
    }
	
    public function form_create()
    {
        $params = $this->input->get();
        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $params['client_code'], 'company_id' => $employer->company_ID]);
        $company = $this->Company->find($client->company_id);
		$country = $this->Country->find($company->country_id);

        $data['client'] = $client;
		$data['departments'] = $this->Ubigeo->get_all_departments();
		$data['document_types'] = $this->Identity_document_type->all(['country_id' => $company->country_id, 'active' => 1]);
		$data['civil_status'] = $this->Civil_status->all(['active' => 1]);
		$data['disabilities'] = $this->Disability->all(['active' => 1]);
		$data['genders'] = $this->Gender->all(['active' => 1]);
		$data['country'] = $country;
		$data['result_countries'] = $this->Country->get_all_countries();
		$data['ubigeos'] = $this->Ubigeo->get_all_by_country_id($company->country_id);

        $this->load->view('employer/recruitment_tray/common/content_tab_register_candidate', $data);
    }

	public function create()
    {
        check_permission_action('recruitment_tray', 'add_candidates');
        
        $this->form_validation->set_rules('client_code', 'Cliente', 'trim|required|strip_all_tags');	
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[tbl_job_seekers.email]|strip_all_tags');	
		$this->form_validation->set_rules('pass', 'Contraseña', 'trim|required|min_length[8]|password_strength');
		$this->form_validation->set_rules('full_name', 'Nombre', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('document_type', 'Tipo de documento', 'trim|required|in_list_db[tbl_identity_document_types.id]');
		$this->form_validation->set_rules('document_number', 'Número de documento', 'trim|required|integer');
		$this->form_validation->set_rules('gender', 'Sexo', 'trim|required|in_list_db[tbl_genders.id]');
		$this->form_validation->set_rules('dob_day', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_month', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('dob_year', 'DOB', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('civil_status', 'Estado civil', 'trim|required|in_list_db[tbl_civil_status.id]');
		$this->form_validation->set_rules('country', 'Country', 'trim|required|in_list_db[tbl_countries.ID]|strip_all_tags');	
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required|in_list_db[tbl_countries.ID]|strip_all_tags');
		$this->form_validation->set_rules('full_mobile_phone_number', 'Teléfono móvil', 'trim|required|is_valid_phone_number');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|integer');
	
		if ($this->input->post('country') == '56') {
			$this->form_validation->set_rules('department', 'Departamento', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('province', 'Provincia', 'trim|required|strip_all_tags');
			$this->form_validation->set_rules('district', 'Distritos', 'trim|required|strip_all_tags');
		} else {
			$this->form_validation->set_rules('city', 'City', 'trim|required|strip_all_tags');
		}

		$check_disability = $this->input->post('check_disability');

		if (isset($check_disability)) {
			$this->form_validation->set_rules('disability', 'Discapacidad', 'trim|required|in_list_db[tbl_disabilities.id]');
		}	

		$this->form_validation->set_message('required', 'El campo %s es requerido');
		$this->form_validation->set_message('is_unique', 'El valor ingresado en el campo %s ya existe');
		
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		$current_date = date("Y-m-d H:i:s");
		
		$password = $this->input->post('pass');	
		
		if ($this->form_validation->run() === FALSE ) {
			echo json_encode([
				'status' => false,
				'message' =>  validation_errors('<div class="error">', '</div>')
			]);
			return;
		}

		$this->db->select('ID');
		$this->db->from('tbl_job_seekers');
		$this->db->where('document_type', $this->input->post('document_type'));
		$this->db->where('document_number', $this->input->post('document_number'));
		$seeker_row = $this->db->get()->row();

		if ($seeker_row) {
			echo json_encode([
				'status' => false,
				'message' =>  'Documento de identidad ya esta registrado en un postulante'
			]);
			return;
		}

		$city = $this->input->post('city');

		if ($this->input->post('country') == '56') {

			$ubigeo_data = [
				$this->input->post('department'), 
				$this->input->post('province'),
				$this->input->post('district') 
			];

			$city = join(", ", $ubigeo_data); 
		}

		$photo_base64 = $this->input->post('photo');
		$photo_path = false;
		$photo_tmp_path = false;

		if ($photo_base64) {
			$photo_tmp_path = base64_to_image($photo_base64);
		}

		if ($photo_tmp_path) {
			try {
				$photo_path_new = tempnam(sys_get_temp_dir(), rand(1, 99999)) . '.png';

				$thumb_config = [];
				$thumb_config['image_library'] = 'gd2';
				$thumb_config['source_image'] = $photo_tmp_path;
				$thumb_config['new_image'] = $photo_path_new;
				$thumb_config['maintain_ratio'] = TRUE;
				$thumb_config['height']	= 320;
				$thumb_config['width'] = 320;
		
				$this->image_lib->initialize($thumb_config);
				$this->image_lib->resize();

				$this->load->library('storage_lib');
				$file_name =  md5(uniqid(time(), true)) . '.png';
				$photo_path = $this->storage_lib->put('candidate/pic/' . $file_name, $photo_path_new);
				@unlink($photo_tmp_path);
				@unlink($photo_path_new);
			} catch (\Exception $e) {
				$photo_path = false;
			} 
			$this->storage_lib->setVisibility($photo_path, 'public');
		}

		$job_seeker_array = [
			'first_name' => $this->input->post('full_name'),
			'paternal_last_name' => $this->input->post('paternal_last_name'),
			'maternal_last_name' => $this->input->post('maternal_last_name'),
			'last_name' => $this->input->post('paternal_last_name') . ' ' . $this->input->post('maternal_last_name'),
			'document_number' => $this->input->post('document_number'),
			'document_type' => $this->input->post('document_type'),
			'email' => $this->input->post('email'),
			'password' => do_hashing($password),
			'dob' => $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_day'),
			'mobile' => $this->input->post('full_mobile_phone_number'),
			'home_phone' => '',
			'present_address' => $this->input->post('current_address'),
			'country' => $this->input->post('country'),
			'city' => $this->input->post('city'),
			'nationality' => $this->input->post('nationality'),
			'gender' => $this->input->post('gender'),
			'ip_address' => $this->input->ip_address(),
			'dated' => $current_date,
			'city' => $city,
			'civil_status' => $this->input->post('civil_status'),
			'disability' => isset($check_disability) ? $this->input->post('disability') : null,
			'photo' => $photo_path ? $photo_path : null,
			'facebook' => '',
			'linkedin' => '',
			'twitter' => '',
            'its_reniec' => $this->input->post('reniec') ? 1 : 0,
		];
		
		$seeker_id = $this->Job_seeker->add_job_seekers($job_seeker_array);

		if (!$seeker_id) {
			echo json_encode([
				'status' => false,
				'message' => 'Postulante no se pudo registrar.'
			]);
			return;
		}
		$this->Jobseeker_additional_info->add(['seeker_ID' => $seeker_id]);
		$this->Job_seeker->accept_legal_terms($seeker_id);

		$this->db->insert('tbl_seeker_config', [
			'key' => 'receive_job_ads',
			'value' => $this->input->post('receive_job_ads') == 'true' ? '1' : '0',
			'seeker_ID' => $seeker_id
		]);

        $client_code = $this->input->post('client_code');

		$candidates[] = [
			'candidate_id' => $seeker_id
		];
		
		$tray_ids = $this->Recruitment_tray_candidate->add_candidates($client_code, $candidates);

        if ($tray_ids === false) {
            echo json_encode([
				'status' => false,
				'message' => 'No se pudo agregar el candidato al proceso'
			]);
        }

		$this->Recruitment_tray_candidate->send_link($tray_ids);

		echo json_encode([
			'status' => true,
			'message' => 'Postulante agregado con éxito.'
		]);
	}

	public function check_seeker()
	{
		$document_type = trim($this->input->post('doc_type'));
		$document_number = trim($this->input->post('doc_number'));

		$this->db->select([
			'ID',
			'email',
			'first_name',
			'last_name'
		]);
		$this->db->from('tbl_job_seekers');
		$this->db->where('document_type', trim($this->input->post('doc_type')));
		$this->db->where('document_number', $document_number);
		$seeker = $this->db->get()->row();

		if ($seeker) {
			echo json_encode([
				'status' => false,
				'seeker_id' => $seeker ? $seeker->ID : 0, 
				'email' => $seeker ? $seeker->email : '',
				'first_name' => $seeker ? $seeker->first_name : '',
				'last_name' => $seeker ? $seeker->last_name : ''
			]);

			return;
		}

		$this->load->library(
			'Jobseeker/Jobseeker_search_info_lib', 
			null, 
			'Jobseeker_search_info_lib'
		);

		$seeker_data = $this->Jobseeker_search_info_lib->search($document_type, $document_number);

		if (isset($seeker_data->document_number)) {
			
			echo json_encode([
				'status' => true,
				'seeker_id' => 0,
				'document_type' => $document_type,
				'first_name' => $seeker_data->first_name,
				'paternal_last_name' => $seeker_data->paternal_last_name,
				'maternal_last_name' => $seeker_data->maternal_last_name,
				'gender' => $seeker_data->gender,
				'dob' => $seeker_data->dob,
				'civil_status' => $seeker_data->civil_status,
				'address' => $seeker_data->present_address,
				'ubigeo_code' => $seeker_data->ubigeo_code,
				'ubigeo_text' => $seeker_data->ubigeo_text,
				'photo' => $seeker_data->photo
			]);
			return;
		}

		echo json_encode([
			'status' => true,
			'seeker_id' => 0 
		]);
	}

	public function modal_edit()
	{
		$params = $this->input->post();
		$candidate = $this->Job_seeker->find($params['candidate_id']);
		$countries = $this->Country->get_all_countries();
		
		$data = [
			'candidate' => $candidate,
			'result_countries' => $countries
		];

		$this->load->view('employer/recruitment_tray/common/modal_edit_candidates_content', $data);
	}

	public function edit()
	{
		$this->form_validation->set_rules('id', 'Postulante Id', 'trim|required');
        $this->form_validation->set_rules('mobile_phone_number', 'Teléfono Móvil', 'trim|required|is_valid_phone_number');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

		$params = $this->input->post();
        $seeker_id = $params['id'];
        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            echo json_encode([
                'status' => false,
                'message' => 'Postulante ID es incorrecto'
            ]);
            return;
        }

        $mobile = $params['mobile_phone_number'];

        $this->db->where('ID', $seeker_id);
        $this->db->update('tbl_job_seekers', [
            'mobile' => $mobile
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'Datos han sido actualizados'
        ]);
	}
}
