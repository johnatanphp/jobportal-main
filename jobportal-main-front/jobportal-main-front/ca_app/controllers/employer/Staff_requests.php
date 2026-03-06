<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_requests extends CI_Controller 
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		
		//Load models
		$this->load->model('Mof');
		$this->load->model('Gantt_activity'); 
		$this->load->model('Gantt_type'); 
		$this->load->model('Job_profile');
		$this->load->model('Staff_request_assigned_employer');
		$this->load->model('Staff_request_exam_request_employer');
		$this->load->model('Job_layout');
		$this->load->model('Staff_request_assignment');

		//Load libraries
		$this->load->library(
			'Notification/Email/Notification_email_staff_request_lib'
		);
    }

	public function show($request_id)
	{
		$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

    	if (!$staff_request) {
    		show_404();
    	}

    	if ($staff_request->request_type == 'external') {
			$data = get_data_staff_request($request_id);
		}

		if ($staff_request->request_type == 'internal') {
			$data = get_data_staff_request_internal($request_id);
		}

		$data['ads_row'] = $this->ads;
		$data['title'] = 'Ver solicitud - ' . $data['request']->job_title . ' - ' . SITE_NAME;
	
		$this->load->view('employer/staff_request/show_staff_request_view', $data);
	}

	public function get_suggestions_employers()
	{
		$term = trim($this->input->get('term'));
		$employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id')); 
		
		$result_employers = $this->Employer->get_suggestions_active_employers_by_company_id($employer->company_ID, $term, 15);
		
		echo json_encode($result_employers);
	}

	public function assign_employer()
	{		
		$this->form_validation->set_rules('request_id', 'Solicitud', 'trim|required');
		$this->form_validation->set_rules('assignment_employers[]', 'Empleador', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => false,
				'message' => 'Hay datos vacios'
			]);
			return;	
		}

		$request_id = $this->input->post('request_id');
		$staff_request = $this->Staff_request->find($request_id);

		if (!$staff_request) {
			echo json_encode([
				'status' => false,
				'message' => 'Solicitud es inválida'
			]);
			return;
		}

		$assignment_employers = (array)$this->input->post('assignment_employers');
		$tracing_employers = (array)$this->input->post('tracing_employers');
		$exam_request_employers = (array)$this->input->post('exam_request_employers');

		if (count($assignment_employers) > $this->config->item('staff_request_assignment_employers_limit')) {
			echo json_encode([
				'status' => false,
				'message' => 'Solo puede asignar hasta ' . $this->config->item('staff_request_assignment_employers_limit') . ' empleadores a la solicitud'
			]);
			return;
		}

		$this->db->trans_start();
		$employer_user_id = $this->session->userdata('user_id');
		
        $this->db->where('ID', $request_id);
        $this->db->update('tbl_staff_requests', ['employer_ID' => $assignment_employers[0]]);
        
		$assignment_date = date('Y-m-d H:i:s');

		$request_assignment = $this->Staff_request_assignment->find(['request_id' => $request_id]);

		if (!$request_assignment) {
            $assignment_data = [
                'request_id' => $request_id,
                'assignment_date' => $assignment_date,
                'assigned_by_employer_id' => $employer_user_id,
            ];

            $this->db->insert('tbl_staff_request_assignments', $assignment_data);
		} else {
            $assignment_data = [
                'request_id' => $request_id,
                'assignment_date' => $assignment_date,
                'assigned_by_employer_id' => $employer_user_id,
            ];
            
            $this->db->where('request_id', $request_id);
            $this->db->update('tbl_staff_request_assignments', $assignment_data);
		}
		
		$this->db->where('request_ID', $request_id);
		$this->db->delete('tbl_staff_request_assigned_employers');
		
		foreach ($assignment_employers as $employer_id) {
			$assignment_data = [
				'date' => $assignment_date,
				'request_ID' => $request_id,
				'employer_ID' => $employer_id,
			];
		
			$this->db->insert('tbl_staff_request_assigned_employers', $assignment_data);			
		}

		$this->db->where('request_ID', $request_id);
		$this->db->delete('tbl_staff_request_supervised_employers');
		
		foreach ($tracing_employers as $employer_id) {
			$tracing_data = [
				'employer_ID' => $employer_id,
				'request_ID' => $request_id,
			];

			$this->db->insert('tbl_staff_request_supervised_employers', $tracing_data);
		}

		$this->db->where('request_id', $request_id);
		$this->db->delete('tbl_staff_request_exam_request_employers');
		
		foreach ($exam_request_employers as $employer_id) {
			$exam_request_employer_data = [
				'employer_id' => $employer_id,
				'request_id' => $request_id,
			];

			$this->db->insert('tbl_staff_request_exam_request_employers', $exam_request_employer_data);
		}

        if ($staff_request->sts_process == 'unassigned') {
			$this->db->where('ID', $request_id);
			$this->db->update('tbl_staff_requests', ['sts_process' => 'assigned']);
        }
        
        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

		if ($trans_status) {
			$this->notify_assignment_staff_request_by_email($request_id);
			$this->notify_supervision_staff_request_by_email($request_id);

			$this->session->set_flashdata('success', 'Solicitud asignada con éxito');
			echo json_encode([
				'status' => true
			]);
			return;
		}

		echo json_encode([
			'status' => false,
			'message' => 'No se pudo asignar la solicitud al empleador'
		]);
	}

	public function reassign_employer()
	{
		$this->form_validation->set_rules('request_id', 'Solicitud', 'trim|required');
		$this->form_validation->set_rules('assignment_employers[]', 'Empleador', 'trim|required');
		$this->form_validation->set_rules('reason_reassing', 'Motivo', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => false,
				'message' => validation_errors('<span class="error">', '</span>')
			]);
		
			return;	
		}
		
		$request_id = $this->input->post('request_id');
		$assignment_employers = (array)$this->input->post('assignment_employers');
		$reason_reassing = $this->input->post('reason_reassing');
		$tracing_employers = (array)$this->input->post('tracing_employers');
		$exam_request_employers = (array)$this->input->post('exam_request_employers');

		if (count($assignment_employers) > $this->config->item('staff_request_assignment_employers_limit')) {
			echo json_encode([
				'status' => false,
				'message' => 'Solo puede asignar hasta ' . $this->config->item('staff_request_assignment_employers_limit') . ' empleadores a la solicitud'
			]);
			return;
		}
		
		$this->db->trans_start();
		
		$this->db->where('ID', $request_id);
        $this->db->update('tbl_staff_requests', ['employer_ID' => $assignment_employers[0]]);
        
		$request_assignment = $this->Staff_request_assignment->find(['request_id' => $request_id]);
		$employer_user_id = $this->session->userdata('user_id');
		$assignment_date = date('Y-m-d H:i:s');
        
		if (!$request_assignment) {
            $assignment_data = [
                'request_id' => $request_id,
                'reassignment_date' => $assignment_date,
                'reassigned_by_employer_id' => $employer_user_id,
                'reassignment_reason' => $reason_reassing
            ];
	
            $this->db->insert('tbl_staff_request_assignments', $assignment_data);
        } else {
            $assignment_data = [
                'reassignment_date' => $assignment_date,
                'reassigned_by_employer_id' => $employer_user_id,
                'reassignment_reason' => $reason_reassing
            ];
            $this->db->where('request_id', $request_id);
            $this->db->update('tbl_staff_request_assignments', $assignment_data);
        }
      
		$this->db->where('request_ID', $request_id);
		$this->db->delete('tbl_staff_request_assigned_employers');

		foreach ($assignment_employers as $employer_id) {
			$assignment_data = [
				'date' => $assignment_date,
				'request_ID' => $request_id,
				'employer_ID' => $employer_id,
				'reason_reassign' => $reason_reassing
			];
		
			$this->db->insert('tbl_staff_request_assigned_employers', $assignment_data);			
		}

		$this->db->where('request_ID', $request_id);
		$this->db->delete('tbl_staff_request_supervised_employers');
		
		foreach ($tracing_employers as $employer_id) {
			$tracing_data = [
				'employer_ID' => $employer_id,
				'request_ID' => $request_id,
			];

			$this->db->insert('tbl_staff_request_supervised_employers', $tracing_data);
		}

		$this->db->where('request_id', $request_id);
		$this->db->delete('tbl_staff_request_exam_request_employers');
		
		foreach ($exam_request_employers as $employer_id) {
			$exam_request_employer_data = [
				'employer_id' => $employer_id,
				'request_id' => $request_id,
			];

			$this->db->insert('tbl_staff_request_exam_request_employers', $exam_request_employer_data);
		}
        
        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

		if ($trans_status) {
			$this->notify_assignment_staff_request_by_email($request_id);
			$this->notify_supervision_staff_request_by_email($request_id);

			$this->session->set_flashdata('success', 'Solicitud reasignada con éxito');
			echo json_encode([
				'status' => true
			]);
			return;
		}

		echo json_encode([
			'status' => false,
			'message' => 'No se pudo asignar la solicitud al empleador'
		]);
	}

	public function delete_assignment()
	{
		$request_id = $this->input->post('request_id');
		
		$this->db->trans_start();
        
        $staff_request = $this->Staff_request->find($request_id);

        if (!$staff_request || $staff_request->sts_process != 'assigned') {
			echo json_encode([
				'status' => false,
				'message' => 'Solicitud no apta para eliminar la asignación'
			]);	

			return;
        }

        $this->db->where('ID', $request_id);
        $this->db->update('tbl_staff_requests', ['employer_ID' => null]);
        
        if ($staff_request->sts_process == 'assigned') {
			$this->db->where('ID', $request_id);
			$this->db->update('tbl_staff_requests', ['sts_process' => 'unassigned']);
        }

		$this->db->where('request_ID', $request_id);
		$this->db->delete('tbl_staff_request_assigned_employers');		

		$this->db->where('request_ID', $request_id);
		$this->db->delete('tbl_staff_request_supervised_employers');

		$this->db->where('request_id', $request_id);
		$this->db->delete('tbl_staff_request_exam_request_employers');

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();
		
		if ($trans_status) {
			$this->session->set_flashdata('success', 'Asignación eliminada');

			echo json_encode([
				'status' => true,
				'message' => 'ok'
			]);	

			return;
		}

		echo json_encode(array(
			'status' => $status,
			'message' => 'No se pudo eliminar la solicitud'
		));	
	}

	public function modal_assign_employer($request_id)
	{
		$employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);
	
		if ($staff_request) {
			$data['request'] = $staff_request;
			$data['result_employers']  = $this->Employer->get_internal_by_profile_id($employer->company_ID, 1);
			$data['result_exam_request_employers'] = $this->Employer->get_internal_by_profile_id($employer->company_ID, 7);

			$this->load->view('employer/staff_request/modal/assign_staff_request_employer', $data);
		}
	}

	public function modal_reassign_employer($request_id)
	{
		$employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

		if (!$staff_request) {
			show_404();
		}

		$result_assigned_employers = $this->Staff_request_assigned_employer->all(['request_ID' => $request_id]);

		$assigned_employers = [];

		foreach ($result_assigned_employers as $assigned) {
			$assigned_employers[$assigned->employer_ID] = $assigned->employer_ID;
		}

		$result_trancing_employers = $this->Staff_request->get_supervisors_employers_by_request_id($request_id);
		$tracing_employers = [];

		foreach ($result_trancing_employers as $tracing) {
			$tracing_employers[$tracing->employer_ID] = $tracing->employer_ID;
		}

		$result_request_employers =  $this->Staff_request_exam_request_employer->all(['request_id' => $request_id]);
		$exam_request_employers = [];

		foreach ($result_request_employers as $exam_employer) {
			$exam_request_employers[$exam_employer->employer_id] = $exam_employer->employer_id;
		}

		$data['request'] = $staff_request;
		$data['tracing_employers'] = $tracing_employers;
		$data['assigned_employers'] = $assigned_employers;
		$data['exam_request_employers'] = $exam_request_employers;
		$data['result_employers'] = $this->Employer->get_internal_by_profile_id($employer->company_ID, 1);
		$data['result_exam_request_employers'] = $this->Employer->get_internal_by_profile_id($employer->company_ID, 7);
		$this->load->view('employer/staff_request/modal/reassign_staff_request_employer', $data);
	}

	public function reject($request_id)
	{
		$obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
		
		if (!$obj_employer) {
			show_404();
		}

		if (!$obj_employer->is_admin && 
		    !is_staff_request_user_manage($obj_employer->ID, $request_id)) {
			show_404();
		}

		$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

		if (!$staff_request || $staff_request->sts_process != 'unassigned') {
			show_404();
		}
		
		$this->form_validation->set_rules('reason', 'Motivo del rechazo', 'trim|required|strip_all_tags');

		if ($this->form_validation->run() === FALSE) {
			$data['title'] = 'Rechazar solicitud personal - ' . SITE_NAME;
			$data['request'] = $staff_request;
			$data['ads_row'] = $this->ads;
			$this->load->view('employer/staff_request/reject_staff_request_view', $data);
			return;
		}

		$reason = $this->input->post('reason');
		
		$trans_status = $this->Staff_request->reject_staff_request(
			$reason, 
			$request_id
		);

		if ($trans_status) {
			$this->session->set_flashdata('success', 'Solicitud ha sido rechazada');
		}

		if (!$trans_status) {
			flash_message('danger', 'No se pudo rechazar la solicitud, vuelve a intentar.');
			redirect('employer/staff_requests/reject/' . $request_id);
			return;
		}

		redirect('employer/staff_requests/show/' . $request_id);
	}

	public function cancel($request_id)
	{
		$obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));		
		$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

		if (!$staff_request || 
		    $staff_request->sts_process == 'canceled') {
			show_404();
		}
			
		$this->form_validation->set_rules('reason', 'Motivo de la cancelación', 'trim|required|strip_all_tags');

		if ($this->form_validation->run() === FALSE) {
			$data['title'] = 'Cancelar solicitud personal - ' . SITE_NAME;
			$data['request'] = $staff_request;
			$this->load->view('employer/staff_request/cancel_staff_request_view', $data);
			return;
		}

		$reason = trim($this->input->post('reason'));

		$this->db->trans_start();

        $data = [
            'sts_process' => 'canceled'
        ];

        $this->db->where('ID', $request_id);
        $this->db->update('tbl_staff_requests', $data);
        
        $sr_canceled = $this->db->get_where('tbl_staff_request_canceled', [
            'request_id' => $request_id
        ])->row();
            
        if ($sr_canceled) {
            $this->db->where('request_id', $request_id);
            $this->db->update('tbl_staff_request_canceled', [
                'canceled_at' => date('Y-m-d H:i:s'),
                'canceled_by_employer_id' => $obj_employer->ID,
                'reason' => $reason
            ]);
        }  else {
            $this->db->insert('tbl_staff_request_canceled', [
                'request_id' => $request_id,
                'canceled_at' => date('Y-m-d H:i:s'),
                'canceled_by_employer_id' => $obj_employer->ID,
                'reason' => $reason
            ]);
        }
        
        $posted_job = $this->Posted_job->get_posted_job_by_request_id($request_id);

        if ($posted_job) {            
            // Cancelar también el proceso de reclutamiento
            // De la solicitud de personal            
            $data_recruitment_process = [
                'sts' => 'canceled'
            ];

            $this->db->where('job_ID', $posted_job->ID);
            $this->db->where('sts', 'active');
            
            $this->db->update('tbl_recruitment_process', $data_recruitment_process);  
        }

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();
		
		if (!$trans_status) {
			flash_message('danger', 'No se pudo cancelar la solicitud, vuelve a intentar.');
			redirect('employer/staff_requests/cancel/' . $request_id);
			return;
		}

		$this->notify_cancellation_request($request_id);

		redirect('employer/staff_requests/show/' . $request_id);
	}

	private function notify_cancellation_request($request_id)
    {
    	$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);
    	$obj_recruiter = $this->Employer->find($staff_request->recruiter_ID);

    	//Create data Send email recruiter 
		$data_email = [
			'employer_recruiter' => $obj_recruiter,
			'staff_request' => $staff_request,
			'url_link' => site_url('employer/staff_request/staff_requests/show/' . $request_id),	
		];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($obj_recruiter->email);

		$mail_message = load_email_view('email/staff_request_notify_cancellation', $data_email);

		$this->email->subject('Solicitud personal Cancelada');
		$this->email->message($mail_message);     
		$this->email->send();	
    }

	private function notify_assignment_staff_request_by_email($request_id)
    {
    	$staff_request = $this->Staff_request->find($request_id);
    	$obj_recruiter = $this->Employer->find($staff_request->recruiter_ID);
    	$obj_employer = $this->Employer->find($staff_request->employer_ID);

    	//Create data Send email recruiter 
		$data_email = [
			'email_for' => 'staff_recruiter',
			'url_link' => site_url('employer/staff_request/staff_requests/show/' . $request_id),	
			'staff_request' => $staff_request
		];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($obj_recruiter->email);

		$mail_message = load_email_view('email/staff_request_assigned', $data_email);

		$this->email->subject('Solicitud personal asignada');
		$this->email->message($mail_message);     
		$this->email->send();

		$employers = $this->Staff_request_assigned_employer->all(['request_ID' => $request_id]);
		$employer_emails = [];

		foreach ($employers as $row) {
			$employer_row = $this->Employer->find($row->employer_ID);
			$employer_emails[] = $employer_row->email;
		}

		//Create data Send email employer
		$data_email = [
			'email_for' => 'employer',
			'url_link' => site_url('employer/staff_requests/show/' . $request_id),
			'staff_request' => $staff_request	
		];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($employer_emails);

		$mail_message = load_email_view('email/staff_request_assigned', $data_email);

		$this->email->subject('Solicitud personal asignada');
		$this->email->message($mail_message);     
		$this->email->send();
    }

    private function notify_supervision_staff_request_by_email($request_id)
    {
    	$employers = $this->Staff_request->get_supervisors_employers_by_request_id(
    		$request_id
    	);

    	if (!$employers) {
    		return;
    	}

    	$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);
    	
		foreach ($employers as $row_employer) {

	    	$obj_employer = $this->Employer->get_employer_by_id($row_employer->employer_ID);

			$data_email = array(
				'staff_request' => $staff_request,
				'email' => $obj_employer->email,
				'name' => $obj_employer->first_name,
				'url_link' => site_url('employer/staff_requests_follow_up')	
			);

			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($obj_employer->email);

			$mail_message = load_email_view('email/staff_request_supervised', $data_email);

			$this->email->subject('Solicitud personal en seguimiento');
			$this->email->message($mail_message);     
			$this->email->send();
		}
    }

	public function create_rys_process()
	{
		$request_id = $this->input->post('request_id');

		$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);

		if (!$staff_request) {
			echo json_encode([
				'status' => false,
				'message' => 'Solicitud no encontrada'
			]);
			return;
		}	

		$company = $this->Company->find($staff_request->company_ID);

		if (!$company) {
			echo json_encode([
				'status' => false,
				'message' => 'Compañia de la Solicitud no encontrada'
			]);
			return;
		}	

		$country = $this->Country->find($company->country_id);

		if (!$country) {
			echo json_encode([
				'status' => false,
				'message' => 'País de la Solicitud no encontrada'
			]);
			return;
		}	

		$user =  $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		if (!$user) {
			echo json_encode([
				'status' => false,
				'message' => 'Usuario en sesión es incorrecto'
			]);
			return;
		}	

		$current_date_time = date('Y-m-d H:i:s');

		$laboral_benefit_list = $this->Staff_request->get_additional_benefits_by_request_id($request_id);
		$laboral_benefit_array = [];

		foreach ($laboral_benefit_list as $row_benefit) {
			$laboral_benefit_array[] = $row_benefit->benefit_name;
		}

		$staff_request->position_experience_time = '';
		$staff_request->level_education = '';

		if ($staff_request->job_profile_ID) {
			$profile = $this->Job_profile->find($staff_request->job_profile_ID);
			$qualification = $this->Qualification->get_record_by_id($profile->study_grade_min);

			$staff_request->position_experience_time = $profile ? $profile->experience : '';
			$staff_request->level_education = $qualification ? $qualification['text'] : '';	
		}

		if ($staff_request->job_layout_id) {
			$job_layout = $this->Job_layout->find($staff_request->job_layout_id);
			$qualification = $this->Qualification->get_record_by_id($job_layout->study_grade_min);

			$staff_request->position_experience_time = $job_layout ? $job_layout->experience : '';
			$staff_request->level_education = $qualification ? $qualification['text'] : '';
		}
		
		$job_array = [
			'industry_ID' => 66, //Otros
			'job_title' => humanize($staff_request->job_title),
			'vacancies' => $staff_request->vacancies,
			'job_mode' => $staff_request->job_mode,
			'payment_currency' => $country->currency_code,
			'minimum_payment' => $staff_request->minimum_salary,
			'maximum_payment' => $staff_request->maximum_salary,
			'experience' => $staff_request->position_experience_time,
			'last_date' => date("Y-m-d", strtotime($current_date_time . "+ 30 day")),
			'country' => $country->country_name,
			'city' => $staff_request->department,
			'qualification' => $staff_request->level_education,
			'job_description' => '',
			'company_ID' => $staff_request->company_ID,
			'employer_ID' => $user->ID,
			'required_skills' => '',
			'ip_address' => $this->input->ip_address(),
			'sts' => 'active',
			'dated' => $current_date_time,
			'has_questions' => 'no',
			'show_salary_in_ad' => 'no',
			'laboral_benefits' => join(',', $laboral_benefit_array),
			'allow_people_disability' => 'no',
			'job_description' => 'Buscamos empleados que estén motivados, sean adaptables y estén dispuestos a aprender.',
			'request_ID' => $request_id
		];
		
		$job_id = $this->Posted_job->add_posted_job($job_array, [], 'no');
		$job_slug = make_job_slug($user->company_slug, $staff_request->job_title, $job_id);

		if (!$job_id) {
			echo json_encode([
				'status' => false,
				'message' => 'Empleo no pudo ser creado'
			]);
			return;
		}
		
		$this->Posted_job->update_posted_job($job_id, ['job_slug' => $job_slug]);

		$data_open_process = [
            'job_ID' => $job_id,
            'sts' => 'active'
		];

        $status = $this->db->insert('tbl_recruitment_process', $data_open_process);

		if (!$status) {
			echo json_encode([
				'status' => false,
				'message' => 'Proceso RyS no pudo ser creado'
			]);

			return;
		}

		$this->Staff_request->update_status_process($staff_request->ID, 'published');

		echo json_encode([
			'status' => true,
			'message' => 'Proceso RyS creado'
		]);
	}

	public function observe()
	{
		$request_id = $this->input->post('request_id');
		$observation =  trim($this->input->post('observation'));

		$data = [
			'observation' => $observation
		];
		$this->db->where('ID', $request_id);
		$status = $this->db->update('tbl_staff_requests', $data);

		if ($status) {
			$this->notify_observation($request_id);
			$this->session->set_flashdata('success', 'Se envio la observación al solicitante');

			echo json_encode([
				'success' => true
			]);
			return;
		}

		echo json_encode([
			'success' => false,
			'message' => 'No se pudo enviar la observación'
		]);
	}

	private function notify_observation($request_id)
    {
    	$staff_request = $this->Staff_request->find($request_id);
    	$obj_recruiter = $this->Employer->find($staff_request->recruiter_ID);

    	//Create data Send email recruiter 
		$data_email = [
			'employer_recruiter' => $obj_recruiter,
			'staff_request' => $staff_request,
			'url_link' => site_url('employer/staff_request/staff_requests/show/' . $request_id),	
		];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($obj_recruiter->email);

		$mail_message = load_email_view('email/staff_request_notify_observation', $data_email);

		$this->email->subject('Solicitud personal #'. $staff_request->ID . ' - ' . $staff_request->job_title . ' Observada');
		$this->email->message($mail_message);     
		$this->email->send();	
    }

	public function show_assigned_employers()
	{
		$request_id = $this->input->get('request_id');

		$this->db->select([
			'employer.email AS employer_email',
			'employer.first_name AS employer_first_name',
		]);
		$this->db->from('tbl_staff_request_assigned_employers assigned_employers');
		$this->db->join('tbl_employers employer', 'employer.ID=assigned_employers.employer_ID');
		$this->db->where('assigned_employers.request_ID', $request_id);
		$assigned_employer = $this->db->get()->result();

		echo json_encode([
			'data' => $assigned_employer
		]);
	}
}
