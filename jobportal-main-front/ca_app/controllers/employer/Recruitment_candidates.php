<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_candidates extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
	
		//Load models
		$this->load->model('Recruitment_process');
		$this->load->model('Recruitment_candidate');
		$this->load->model('Recruitment_short_list');
		$this->load->model('Recruitment_attached_document');
		$this->load->model('Rys_form');
		$this->load->model('Form_question');
		$this->load->model('Rys_form_seeker');
		$this->load->model('Recruitment_stage');
		$this->load->model('Recruitment_process_document_stage');
		$this->load->model('Candidate_interview');
		$this->load->model('Recruitment_document_type');
		$this->load->model('Staff_request');
		
		//Load libraries
		$this->load->library('rys_seeker_fit_helper');	
    	
    	$this->ads = $this->Ad->get_ads();
    }

    public function add_candidate()
	{	
		$process_id = $this->input->post('process_id');

		$process = $this->Recruitment_process->find($process_id);

		if (!$process) {
				echo json_encode(array(
				'status' => false,
				'message' => 'Proceso no encontrado'
			));
			return;
		}

		$job_id = $process->job_ID;

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			echo json_encode(array(
				'status' => false,
				'message' => 'Proceso no tiene empleo asignado'
			));
			return;
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
			echo json_encode(array(
				'status' => false,
				'message' => 'Empresa restringida'
			));
			return;
        }

		$email = $this->input->post('email');
		$document_number = $this->input->post('document_number');
		$seeker_id = (int)$this->input->post('job_seeker_id');
		$stage = $this->input->post('stage');

		if (!$seeker_id) {
			$seeker_response = $this->create_seeker($email, $document_number);

			if ($seeker_response['status'] == false) {
				echo json_encode([
					'status' => false,
					'message' => $seeker_response['message']
				]);
				return;
			}

			$seeker_id = $seeker_response['seeker_id'];
		}

		if (!$seeker_id) {
			echo json_encode([
				'status' => false,
				'message' => 'Postulante ID es inválido'
			]);
			return;
		}

		$errors = [];
		
		$errors = $this->Recruitment_candidate->verify_candidate(
			$process_id,
			$seeker_id
		);

		if (count($errors) > 0) {
			echo json_encode([
				'status' => false,
				'message' => $errors[0]
			]);
			return;
		}

		$this->form_validation->set_rules('process_id', 'Proceso', 'trim|required');
		$this->form_validation->set_rules('stage', 'Etapa', 'trim|required|in_list[0,1,2,3,4,5,6]');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array(
				'status' => false,
				'message' => 'Hay datos incorrectos'
			));
			return;
		}

		$trans_status = $this->Recruitment_candidate->add_candidate(
			$process_id,
			$seeker_id,
			$stage
		);

		if (!$trans_status) {
			echo json_encode([
				'status' => false,
				'message' => 'No se pudo agregar el postulante al proceso'
			]);
			return;
		}

		$notify_candidate_by_mail = $this->input->post('notify_candidate_by_mail');
		$notify_candidate_by_whatsapp = $this->input->post('notify_candidate_by_whatsapp');

		if ($notify_candidate_by_mail && $job->request_ID) {
			$this->load->library('Email/Recruitment_process/Recruitment_process_candidate_add_email');
			$this->recruitment_process_candidate_add_email->send($job->request_ID, $seeker_id);
		}

		if ($notify_candidate_by_whatsapp && $job->request_ID) {
			$this->load->library('Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_add_lib');
			$this->whatsapp_recruitment_proccess_candidate_add_lib->send($job->request_ID, $seeker_id);
		}

		echo json_encode([
			'status' => $trans_status,
			'message' => 'OK'
		]);
	}

	private function create_seeker($email, $document_number)
	{
		if ($this->Job_seeker->authenticate_job_seeker_email_address($email)) {
			return [
				'status' => false,
				'message' => 'Email ' . $email . ' ya esta en uso'
			];
		}; 

		$this->load->library(
			'Hrm_api/Hrm_api_employee_info_search', 
			null, 
			'Hrm_api_employee_info_search'
		);

		$employee = $this->Hrm_api_employee_info_search->run($document_number);

		if (!isset($employee[0])) {
			return [
				'status' => false,
				'message' => 'No existe postulante en eplani'
			];
		}

		$employee = $employee[0];

		$password = create_random_password();

		$last_name = $employee->apellido_paterno . ' ' . $employee->apellido_materno;

		$doc_type_list = [
			'01' => '1',
			'04' => '4',
			'07' => '7',
			'26' => '26'
		];
		
		$document_type = isset($doc_type_list[$employee->tipo_documento]) ? $doc_type_list[$employee->tipo_documento] : null;

		$country = '56';
		// if ($employee->nombre_pais == 'PERÚ') {
		// 	$country = '56';
		// }

		$nationality = null;
		if ($employee->nacionalidad == 'PERU') {
			$nationality = '56';
		}

		$city = trim($employee->nombre_ubigeo);
		$city_part = explode('/', $city);

		if (count($city_part) == 3 && $country == '56') {
			$city_data = array_map(function($item){
				return ucfirst(mb_strtolower(trim($item)));
			}, $city_part);
	
			$city = join(", ", $city_data);
		}

		$genders = [
			1 => '1',
			2 => '2'
		];

		$gender = isset($genders[$employee->sexo]) ? $genders[$employee->sexo] : null; 

		$civil_status_list = [
			'Soltero (a)' => '1'
		];
		
		$civil_status = isset($civil_status_list[$employee->estado_civil]) ? $civil_status_list[$employee->estado_civil] : null;

		$dob = date('Y-m-d', strtotime($employee->fecha_nacimiento));
		$mobile = $employee->telefono_movil;

		$dated = date('Y-m-d H:i:s');
		$document_number = trim($employee->nro_documento);

		$this->db->select([
			'ID'
		]);
		$this->db->from('tbl_job_seekers');
		$this->db->where('document_number', $document_number);

		if (!empty($document_type)) {
			$this->db->where('document_type', $document_type);
		}

		$seeker_doc_number = $this->db->get()->row();

		if ($seeker_doc_number) {
			return [
				'status' => false,
				'message' => 'Documento de identidad ya esta registrado en portal'
			];
		}

		$this->db->insert('tbl_job_seekers', [ 
			'dated' => $dated,
			'document_type' => $document_type,
			'document_number' => $document_number,
			'first_name' => $employee->nombre,
			'last_name' => $last_name, 
			'paternal_last_name' => $employee->apellido_paterno,
			'maternal_last_name' => $employee->apellido_materno,
			'email' => $email, 
			'password' => do_hashing($password),
			'country' => $country,
			'city' => $city,
			'gender'  => $gender,
			'nationality' => $nationality,
			'dob' => $dob,
			'mobile' => $mobile,
			'civil_status' => $civil_status,
			'sts' => 'active'
		]);

		$seeker_id = $this->db->insert_id();
	
		if (!$seeker_id) {
			return [
				'status' => false,
				'message' => 'Error al crear la cuenta del postulante'
			];
		}

		$seeker = $this->Job_seeker->find($seeker_id);

	    $data_email = [
            'jobseeker' => $seeker,
            'password' => $password,
            'url_link' => site_url('login')  
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($seeker->email);

        $mail_message = load_email_view('email/notify_entry_jobseeker', $data_email);

        $this->email->subject('Cuenta Postulante - Portal de empleo');
        $this->email->message($mail_message);     
     
        //Send email
        $this->email->send();

		return [
			'status' => true,
			'message' => 'Ok',
			'seeker_id' => $seeker_id
		];
	}

	public function remove_candidates_stage()
	{	
		$this->form_validation->set_rules('process_id', 'Proceso', 'trim|required');
		$this->form_validation->set_rules('candidate_ids[]', 'Etapa', 'trim|required');

		$process_id = $this->input->post('process_id');

		$process = $this->Recruitment_process->find($process_id);

		if (!$process) {
			show_404();
		}

		$job_id = $process->job_ID;
		$candidate_ids = (array)$this->input->post('candidate_ids');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos incorrectos'
			));
			return;
		}

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
			exit;
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		$trans_status = $this->Recruitment_candidate->remove_all_candidates_stage(
			$process_id, 
			$candidate_ids
		);
	
		echo json_encode(array(
			'success' => $trans_status
		));
	}

	public function move_candidates_stage()
	{
		$this->form_validation->set_rules('process_id', 'Empleo', 'trim|required');
		$this->form_validation->set_rules('stage', 'Etapa', 'trim|required|in_list[0,1,2,3,4,5,6]');

		if ($this->form_validation->run() === FALSE) {
			show_404();
		}

		$process_id = $this->input->post('process_id');

		$process = $this->Recruitment_process->find($process_id);

		if (!$process) {
			show_404();
		}

		$job = $this->Posted_job->get_posted_job_by_id($process->job_ID);

		if (!$job) {
			show_404();
			exit;
		}

		$candidate_ids = (array)$this->input->post('candidate_ids');
		$stage = $this->input->post('stage');
        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

		$result_data = $this->Recruitment_candidate->move_candidates_stage($this->input->post());

		echo json_encode([
			'success' => $result_data['status'],
			'message' => $result_data['message'],
			'data' => $result_data['data'] ?? []
		]);
	}

	public function move_candidates_hired()
	{	
		$obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		$this->form_validation->set_rules('process_id', 'Proceso', 'trim|required');
		$this->form_validation->set_rules('candidate_ids[]', 'Candidatos', 'trim|required');
		$this->form_validation->set_rules('rrhh_user_id[]', 'RRHH usuarios', 'trim|required');

		if ($this->form_validation->run() === FALSE) {

			$params = $this->input->get();
			$process = $this->Recruitment_process->find($params['process_id']);

			if (!$process) {
				show_404();
			}

			$job_id = $process->job_ID;

			$job = $this->Posted_job->find($job_id);
			$request = $this->Staff_request->find($job->request_ID);

			//Obtener rrhh usuarios
			$rrhh_users = $this->db->select([
				'app_users.first_name',
				'app_users.email',
				'app_users.ID AS id',
			])
			->from('tbl_employers app_users')
			->join(
				'tbl_employer_profiles user_profiles', 
				'user_profiles.user_id=app_users.ID',
			)
			->where('app_users.company_ID', $obj_employer->company_ID)
			->where('app_users.sts', 'active')
			->where('user_profiles.profile_id', 3);
		
			$rrhh_users = $rrhh_users->get()->result();

			$data['rrhh_users'] = json_encode($rrhh_users);

			//Mostrar grupos disponibles con usuarios agregados
			$rrhh_groups = [];			
			//Buscar el grupo seleccionado actualmente del proceso
			$rrhh_group_selected = [];
		

			//Obtener rrhh asignados
			$data['rrhh_responsibles'] = $this->Staff_request->get_rrhh_responsibles($request->ID);
			$data['process'] = $process;
			$data['job'] = $job;

			$this->load->view('employer/recruitment/modal/move_candidates_hired_forms', $data);
			return;
		}

		$this->load->library(
			'App/Recruitment_candidate/Recruitment_candidate_move_hired', 
			null, 
			'Recruitment_candidate_move_hired'
		);

		$status = $this->Recruitment_candidate_move_hired->move($this->input->post());

		echo json_encode([
			'status' => $status['status'],
			'message' => $status['message'], 
			'data' => []
		]);
	}

	public function verify_candidate()
	{
		$job_id = $this->input->post('job_id');
		$candidate_id = $this->input->post('seeker_id');		
		$errors = array(); 

		$errors = $this->Recruitment_candidate->verify_candidate(
			$job_id,
			$candidate_id
		);

		echo json_encode(array(
			'success' => true,
			'errors' => $errors
		));
	}

	public function stop_tracking_candidate()
	{
		$this->form_validation->set_rules('process_id', 'Preceso', 'trim|required');
		$this->form_validation->set_rules('jobseeker_id', 'Candidato', 'trim|required');
		$this->form_validation->set_rules('comments', 'Comentarios', 'trim|strip_all_tags');

		if ($this->form_validation->run() === FALSE) {
			
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos incorrectos'
			));
			return;
		}

		$process_id = $this->input->post('process_id');
		$process = $this->Recruitment_process->find($process_id);

		if (!$process) {
			show_404();
		}

		$job_id = $process->job_ID;
		
		$jobseeker_id = $this->input->post('jobseeker_id');
		$comments = $this->input->post('comments');

		$notify_candidate_by_mail = $this->input->post('notify_candidate_by_mail');
		$notify_candidate_by_whatsapp = $this->input->post('notify_candidate_by_whatsapp');

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
			exit;
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		$rejected_date = date('Y-m-d');
		$rejected_time = date('H:i:s');

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		$request_data = [
			'process_id' => $process_id, 
			'jobseeker_id' => $jobseeker_id,  
			'comments' => $comments,
			'rejected_date' => $rejected_date,
			'rejected_time' => $rejected_time,
			'user_id' => $this->session->userdata('user_id')
		];

		$trans_status = $this->Recruitment_candidate->stop_tracking_candidate($request_data);

		if (!$trans_status) {
			echo json_encode([
				'success' => $trans_status
			]);
			return;
		}

		if ($notify_candidate_by_mail == 1 && $job->request_ID) {
			$this->load->library(
				'Email/Recruitment_process/Recruitment_process_candidate_reject_email', 
				null, 
				'Recruitment_process_candidate_reject_email'
			);
			$this->Recruitment_process_candidate_reject_email->send($job->request_ID, $jobseeker_id);
		}

		if ($notify_candidate_by_whatsapp == 1 && $job->request_ID) {
			$this->load->library(
				'Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_reject_lib', 
				null, 
				'Whatsapp_recruitment_proccess_candidate_reject'
			);
			$this->Whatsapp_recruitment_proccess_candidate_reject->send($job->request_ID, $jobseeker_id);
		}

		echo json_encode([
			'success' => $trans_status
		]);
	}

	public function follow_up_candidate()
	{
		$this->form_validation->set_rules('process_id', 'Preceso', 'trim|required');
		$this->form_validation->set_rules('jobseeker_id', 'Candidato', 'trim|required');
	
		if ($this->form_validation->run() === FALSE) {
			
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos incorrectos'
			));
			return;
		}

		$process_id = $this->input->post('process_id');
		$process = $this->Recruitment_process->find($process_id);

		if (!$process) {
			show_404();
		}

		$job_id = $process->job_ID;
		$jobseeker_id = $this->input->post('jobseeker_id');

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
			exit;
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }
	
		$trans_status = $this->Recruitment_candidate->follow_up_candidate($process_id, $jobseeker_id);

		echo json_encode(array(
			'success' => $trans_status
		));
	}

    public function modal_active_process_candidate($candidate_id, $process_id)
    {
    	$data['candidate_process'] = $this->Recruitment_candidate->get_active_process_candidate(
    		$candidate_id,
    		$process_id
    	);

    	$this->load->view('employer/recruitment/modal/active_process_candidate', $data);
    }

    public function search_all_candidates()
	{
		$term = trim($this->input->get('term'));
		$result = $this->Recruitment_candidate->get_suggestions_candidates($term, 15);
		echo json_encode($result);
	}

   	public function search_stage_candidates()
    {
    	$term = $this->input->get('term');
    	$job_id = $this->input->get('job_id');
    	$current_stage = $this->input->get('current_stage');
    	
    	$result = $this->Recruitment_candidate->_get_suggestions_candidates(
    		$term, 
    		$job_id,
    		$current_stage
    	);

    	echo json_encode($result);
    }
	
	public function detail_process_job($process_id, $candidate_id)
	{
        $this->load->library(
            'Component/Recruitment_candidate/Recruitment_candidate_process_detail_loader'
        );
        
        $params = [
            'process_id' => $process_id,
            'candidate_id' => $candidate_id, 
            'config' => [
                'contract_documents_show' => true
            ] 
        ];
        $this->recruitment_candidate_process_detail_loader->render($params);
	}

	public function search_candidates()
	{
		$this->load->library(
			'Jobseeker/Jobseeker_work_experience_overall_lib', 
			null, 
			'Jobseeker_work_experience_overall_lib'
		);

		$process_id = $this->input->post('process_id');
		$rs_process = $this->Recruitment_process->get_process_by_id($process_id);

		if (!$rs_process) {
			show_404();
		}

		$job_id = $rs_process->job_ID;
		$stage = $this->input->post('stage');
		$filter_is_fit = trim((string)$this->input->post('is_fit'));
		$filter_search = trim((string)$this->input->post('search'));

		$employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		$job = $this->Posted_job->get_posted_job_by_id($job_id);
		$company = $this->Company->find($job->company_ID);

		$stage_all = new stdClass();
		$stage_all->id = -1;
		$stage_all->name = 'TODOS';
		$stages[] = $stage_all;

		$stages = array_merge($stages, $this->Recruitment_process->get_selected_stages($job_id));

		foreach ($stages as $stage_row) {
			$stage_row->count_candidates = $this->Recruitment_candidate->count_all_candidates_by_stage(
				$job_id, 
				$stage_row->id
			);
			$stage_items[] = $stage_row;
		}

		if (($stage != -1 && !$this->Recruitment_stage->find(['id' => $stage])) || $stage == 'false') {
			$stage = $rs_process->sts_stage;
		}
	
		$this->db->select([
            'job_seeker.*',
            'seeker_applied.ID AS seeker_applied_ID',
            'rs_candidate.*',
			'rc_stage.name AS stage_name',
			'(SELECT screening.its_data_prosecution 
				FROM tbl_screening screening 
				WHERE screening.seeker_id = rs_candidate.seeker_ID
				LIMIT 1) AS its_data_prosecution'
		]);

		$this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('tbl_recruitment_candidates rs_candidate', 'rs_candidate.process_id=rs_process.id');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
		$this->db->join('tbl_recruitment_stages rc_stage', 'rc_stage.ID=rs_candidate.stage');
        $this->db->join('tbl_seeker_applied_for_job seeker_applied', 'seeker_applied.job_ID=rs_process.job_ID AND seeker_applied.seeker_ID=rs_candidate.seeker_ID', 'left');
        $this->db->where('rs_candidate.process_id', $process_id);

		if ($stage > -1) {
			$this->db->where('rs_candidate.stage', $stage);
		}

		if ($filter_search != '') {
			$this->db->group_start();
			$this->db->like('job_seeker.first_name', $filter_search, 'both');
			$this->db->or_like('job_seeker.paternal_last_name', $filter_search, 'both'); 
			$this->db->or_like('job_seeker.maternal_last_name', $filter_search, 'both'); 
			$this->db->or_like('job_seeker.email', $filter_search, 'both');
			$this->db->or_like('job_seeker.document_number', $filter_search, 'both'); 
			$this->db->group_end();
		}

		if ($this->input->post('creation_date')) {
			$this->db->where('rs_candidate.creation_date', $this->input->post('creation_date'));
		}

		if ($this->input->post('update_date')) {
			$this->db->where('rs_candidate.update_date', $this->input->post('update_date') == '-1' ? null : $this->input->post('update_date'));
		}

        $this->db->order_by('rs_candidate.discarded', 'ASC');
        $this->db->order_by('rs_candidate.is_new', 'DESC');
        $this->db->order_by('rs_candidate.creation_date', 'DESC');

        $candidates =  $this->db->get()->result();
		
		$result_seekers = [];
		$employee_doc_numbers = [];

		foreach ($candidates as $row_candidate) {
			if (empty($row_candidate->document_number)) {
				continue;
			}

			$employee_doc_numbers[] = $row_candidate->document_number;
		}	

		$cache_seeker_work_experiences = [];

		if ($company->system_internal) {
			$cache_seeker_work_experiences = $this->Jobseeker_work_experience_overall_lib->get_all(
				$employee_doc_numbers, 
				$company->country_id
			);
		}
		
		foreach ($candidates as $row_candidate) {
			 
			$form_dj_is_expired = $this->rys_seeker_fit_helper->form_is_expired(
				1,
				$row_candidate->ID
			);

			$row_candidate->form_dj_is_expired = $form_dj_is_expired;

			$is_fit = $this->rys_seeker_fit_helper->is_fit(
				$job->ID, 
				$row_candidate->ID
			);
	
			$row_candidate->is_fit = $is_fit;

			$seeker_work_exp_overall = $cache_seeker_work_experiences[$row_candidate->document_number] ?? [];
			$row_candidate->alert_work_exp_text = $seeker_work_exp_overall['work_exp_time_description'] ?? '';
			$row_candidate->alert_work_exp_color = $seeker_work_exp_overall['work_exp_time_color'] ?? '';
		
			//Filtrar
			if ($filter_is_fit !== '' && 
			    $row_candidate->is_fit !== (bool)$filter_is_fit) {
				continue;
			}

			$result_seekers[] = $row_candidate;
		}
		
		$data['job'] = $job;
		$data['rs_process'] = $rs_process;
		$data['all_candidates'] = $result_seekers;
		$data['current_stage'] = $stage;
		$data['stage_items'] = $stage_items;
		$data['stage_forms'] = $this->Rys_form->get_forms_by_stage($stage);
		$data['user_belong_to_company_internal'] = user_belong_to_company_internal();
		$data['rys_documents'] = $this->Recruitment_process_document_stage->get_documents($job_id, $stage);
		$data['filters'] = [
			'search' => $filter_search,
			'is_fit' => $filter_is_fit
		];

		$data['label'] = $this->input->post('label');
		
		$data_html = $this->load->view(
			'employer/recruitment/recruitment_candidates/common/search_list_candidates', 
			$data, 
			true
		);

		echo json_encode([
			'data' => $data_html
		]);
	}

	public function search_candidates_block()
	{
		$job_id = $this->input->post('job_id');
		$stage = $this->input->post('stage');

		$this->db->select([
			'creation_date',
			'update_date',
			'COUNT(seeker_ID) AS total_candidates'
		]);

		$this->db->from('tbl_recruitment_candidates');
		$this->db->where('job_ID', $job_id);
		$this->db->where('stage', $stage);
	
		$this->db->group_by(['creation_date', 'update_date']);
		$results = $this->db->get()->result();

		$data['blocks'] = $results;

		$data_html = $this->load->view(
			'employer/recruitment/recruitment_candidates/common/search_list_blocks', 
			$data, 
			true
		);

		echo json_encode([
			'data' => $data_html
		]);
	}

	public function get_premium_candidates($job_id = 0)
	{
		$this->load->model('Premium_candidate');

		$results_data['results'] = $this->Premium_candidate->get_all($job_id);
		$this->load->view('employer/recruitment/common/premium_candidates', $results_data);
	}

	public function add_premium_candidate()
	{
		$job_id = $this->input->post('job_id');

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

		$this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
		$this->form_validation->set_rules('candidate_id', 'Candidato', 'trim|required');
		$this->form_validation->set_rules('stage', 'Etapa', 'trim|required|in_list[0,1,2,3,4,5,6,7]');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos incorrectos'
			));
			return;
		}
	
		$this->load->model('Premium_candidate');
		$all_input = $this->input->post();
		$trans_status = $this->Premium_candidate->add_candidate_to_stage($all_input);
		
		echo json_encode(array(
			'success' => $trans_status
		));
	}

	public function show_rs_process($process_id = 0)
	{
		$stage_items = [];

		$rs_process = $this->Recruitment_process->get_process_by_id($process_id);

		$stages = $this->Recruitment_process->get_selected_stages($rs_process->job_ID);

		foreach ($stages as $stage_row) {
			$stage_row->count_candidates = $this->Recruitment_candidate->count_all_candidates_by_stage(
				$rs_process->job_ID, 
				$stage_row->id
			);
			$stage_items[] = $stage_row;
		}

		$data['rs_stages'] = $stage_items;
		$data['rs_process'] = $rs_process;

		$this->load->view(
			'employer/recruitment/modal/show_recruitment_process',
			$data
		);
	}

	public function data_rs_stage_candidates()
	{
		$process_id = $this->input->post('process_id');
		$stage_id = $this->input->post('stage');

		$process = $this->Recruitment_process->get_process_by_id($process_id);

		$stage_items = get_RS_stages();

		if (!$process) {
			$stage_id = 0;
		}

		if (!isset($stage_items[$stage_id])) {
			$stage_id = $process->sts_stage;
		}
	
		$candidates = $this->Recruitment_candidate->get_candidates_by_stage($process_id, $stage_id);
	
		$data['result_candidates'] = $candidates;
		$data['process'] = $process;
			
		$this->load->view('employer/recruitment/common/list_stage_candidates', $data);
	}

	public function save_rrhh_assignment()
	{
		$obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		$this->form_validation->set_rules('process_id', 'Proceso', 'trim|required');
		$this->form_validation->set_rules('rrhh_user_id[]', 'Candidatos', 'trim|required');

		if ($this->form_validation->run() === FALSE) {

			$params = $this->input->get();

			$process_id = $params['process_id'];
			$rs_process = $this->Recruitment_process->get_process_by_id($process_id);
			$job_id = $rs_process->job_ID;
			$job = $this->Posted_job->find($job_id);
			
			//Obtener rrhh usuarios
			$rrhh_users = $this->db->select([
				'app_users.first_name',
				'app_users.email',
				'app_users.ID AS id',
			])
			->from('tbl_employers app_users')
			->join(
				'tbl_employer_profiles user_profiles', 
				'user_profiles.user_id=app_users.ID'
			)
			->where('app_users.sts', 'active')
			->where('user_profiles.profile_id', 3);
		
			$rrhh_users = $rrhh_users->get()->result();

			//Mostrar grupos disponibles con usuarios agregados
			$rrhh_groups = [];	
			$rrhh_group_selected = [];

			//Obtener usuarios asignados manualmente
			$manual_rrhh_users = $this->db->select([
				'app_users.first_name',
				'app_users.email',
				'app_users.ID AS id',
			])
			->from('tbl_employers app_users')
			->join(
				'tbl_recruitment_rrhh_assignments rrhh_assignments', 
				'app_users.ID=rrhh_assignments.rrhh_user_ID'
			)
			->where('rrhh_assignments.job_ID', $job_id)
			->where('app_users.sts', 'active')
			->get()
			->result();

			$data = [
				'job' => $job,
				'rs_process' => $rs_process,
				'rrhh_users' => json_encode($rrhh_users),
				'manual_rrhh_users' => $manual_rrhh_users,
				'rrhh_group_selected' => $rrhh_group_selected,
				'rrhh_groups' => $rrhh_groups
			];
			
			$this->load->view('employer/recruitment/modal/save_rrhh_assignments_form', $data);
			return;
		}

		$process_id = $this->input->post('process_id');	
		$rs_process = $this->Recruitment_process->find($process_id);
		$job_id = $rs_process->job_ID;

		$rrhh_ids = $this->input->post('rrhh_group_id');
		$group_id = isset($rrhh_ids[0]) ? $rrhh_ids[0] : null;

		$rrhh_users = $this->db->from('tbl_recruitment_rrhh_group_users')
		     ->where('rrhh_group_id', $group_id)
			 ->get()
			 ->result();

		$this->db->where('job_ID', $job_id)
		     ->delete('tbl_recruitment_rrhh_assignments');

		//Guardar usuarios rrhh del grupo seleccionado
		foreach ($rrhh_users as $user) {
			$data_assignment = [
				'job_ID' => $job_id,
				'date_assignment' => date('Y-m-d'),
				'rrhh_user_ID' => $user->user_id
			];

			$this->db->insert('tbl_recruitment_rrhh_assignments', $data_assignment);
		}

		$manual_rrhh_users = (array)$this->input->post('rrhh_user_id');

		foreach ($manual_rrhh_users as $user_id) {
			$data_assignment = [
				'job_ID' => $job_id,
				'date_assignment' => date('Y-m-d'),
				'rrhh_user_ID' => $user_id,
				'manual' => 1
			];

			$this->db->insert('tbl_recruitment_rrhh_assignments', $data_assignment);
		}

		$this->db->where('job_id', $job_id)
		     ->delete('tbl_recruitment_rrhh_group_assignments');

		if ($group_id) {
			$this->db->insert('tbl_recruitment_rrhh_group_assignments', [
				'job_id' => $job_id,
				'rrhh_group_id' => $group_id
			]);
		}
				
		$this->load->Library(
			'Email/Recruitment/Recruitment_notify_assignment_hiring_email', 
			null, 
			'Recruitment_notify_assignment_hiring_email'
		);

		$this->Recruitment_notify_assignment_hiring_email->send($job_id);

		echo json_encode([
			'status' => true,
			'message' => 'Cambios realizados con éxito'
		]);
	}

	public function remove_hiring()
	{
		if (!user_belong_to_company_internal()) {
			echo json_encode([
				'success' => false,
				'message' => 'Operacion no puede ser procesada!'
			]);
			return;
		}

		$process_id = $this->input->post('process_id');
		$rs_process = $this->db->get_where('tbl_recruitment_process', [
			'id' => $process_id
		])->row();
		
		if (!($rs_process && $rs_process->sts == 'active')) {
			echo json_encode([
				'success' => false,
				'message' => '¡RyS no valido!'
			]);
			return;
		}

		$seeker_id = $this->input->post('seeker_id');

		$rs_candidate = $this->db->get_where('tbl_recruitment_candidates', [
			'process_id' => $process_id,
			'seeker_ID' => $seeker_id,
			'contracted' => 0,
			'stage' => '7',
			'discarded' => 0
		])->row();

		if (!$rs_candidate) {
			echo json_encode([
				'success' => false,
				'message' => 'Candidato no se puede quitar!'
			]);
			return;
		}

		$this->Recruitment_candidate->remove_candidate($process_id, $seeker_id);

		echo json_encode([
			'success' => true,
			'message' => '¡Candidato removido!'
		]);
	}

	public function mark_reentry()
	{
		if (!user_belong_to_company_internal()) {
			echo json_encode([
				'success' => false,
				'message' => 'Operacion no puede ser procesada!'
			]);
			return;
		}

		$job_id = $this->input->post('job_id');

		$rs_process = $this->db->get_where('tbl_recruitment_process', [
			'job_ID' => $job_id
		])->row();
		
		if (!($rs_process && $rs_process->sts == 'active')) {
			echo json_encode([
				'success' => false,
				'message' => '¡RyS no valido!'
			]);
			return;
		}

		$seeker_id = $this->input->post('seeker_id');

		$rs_candidate = $this->db->get_where('tbl_recruitment_candidates', [
			'seeker_ID' => $seeker_id,
			'job_ID!=' => $job_id,
			'discarded' => 0,
			'contracted' => 0
		])->row();

		if (!$rs_candidate) {
			echo json_encode([
				'success' => false,
				'message' => '¡Candidato no se puede marcar como reingreso!'
			]);
			return;
		}

		$this->db->where('job_ID', $job_id);
		$this->db->where('seeker_ID', $seeker_id);
		$this->db->where('contracted', 0);
		$this->db->where('stage', '7');
		$this->db->where('discarded', 0);
		$this->db->update('tbl_recruitment_candidates', ['contracted' => 1]);

		echo json_encode([
			'success' => true,
			'message' => '¡Candidato marcado como contratado!'
		]);
	}

	public function search_seekers() 
	{
		$company_id = get_session_company_id();

		$company = $this->Company->find($company_id);

		$type = $this->input->post('type');
		
		$this->db->select([
			's.ID AS id',
			's.email',
			'dt.abbreviation AS document_type',
			's.document_number',
			's.first_name',
			's.paternal_last_name',
			's.maternal_last_name',
			'"portal" AS source'
		])
		->from('tbl_job_seekers s')
		->join('tbl_identity_document_types dt', 'dt.id=s.document_type', 'left')
		->where('s.sts', 'active');

		$email = trim((string)$this->input->post('email'));

		if ($email != '') {
			$this->db->where('email', $email);
			$this->db->limit(1);	
		}

		$document_type = trim((string)$this->input->post('document_type'));
		$document_number = trim((string)$this->input->post('document_number'));

		if ($document_type!= '' && 
		    $document_number != '') {
				$this->db->where('document_type', $document_type);
				$this->db->where('document_number', $document_number);
		}

		$first_name = trim((string)$this->input->post('first_name'));

		if ($first_name != '') {
			$this->db->like('first_name', $first_name);
			$this->db->limit(2500);
		}

		$paternal_last_name = trim((string)$this->input->post('paternal_last_name'));

		if ($paternal_last_name != '') {
			$this->db->like('paternal_last_name', $paternal_last_name);
			$this->db->limit(2500);
		}
		
		$maternal_last_name = trim((string)$this->input->post('maternal_last_name'));

		if ($maternal_last_name != '') {
			$this->db->like('maternal_last_name', $maternal_last_name);
			$this->db->limit(2500);
		}
				
		$results = $this->db->get()->result();

		if ($document_number != '' && 
		    count($results) == 0 && 
			$this->config->item('eplani_api_enabled') == '1' && 
			get_session_company_id() == 1) {

			$this->load->library(
				'Hrm_api/Hrm_api_employee_info_search', 
				null, 
				'Hrm_api_employee_info_search'
			);
	
			$employee = $this->Hrm_api_employee_info_search->run($document_number);
	
			if (isset($employee[0])) {

				$employee = $employee[0];

				$seeker_data = [
					'id' => null,
					'email' => $employee->email,
					'document_type' => '',
					'document_number' => $employee->nro_documento,
					'first_name' => $employee->nombre,
					'paternal_last_name' =>  $employee->apellido_paterno,
					'maternal_last_name' => $employee->apellido_materno,
					'source' => 'eplani'
				];
				$results[] = $seeker_data;
			}
		}

		echo json_encode([
			'data' => $results,
			'message' => 'OK',
			'status' => true
		]);
	}
}
