<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate extends CI_Controller {
	
	public function __construct() 
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		
		$this->load->library('Url_signer/Url_signer_lib');
    }
	
	public function index($id = '')
	{
		$this->profile($id);
	}

	public function view_profile($id = '')
	{
		$this->profile($id, true);
	}

	private function profile($id, $view_modal = false)
	{
		$data['ads_row'] = $this->ads;
				
		if ($this->session->userdata('is_employer') != true && 
			$this->session->userdata('is_admin_login') != true
		) {
			$this->session->set_userdata('back_from_user_login',$this->uri->uri_string);
			$this->session->set_flashdata('msg', 'Please login as a employer to view the candidate profile.');
			redirect(base_url('login'));
			return;	
		}
		
		$seeker_id = $this->custom_encryption->decrypt_data($id);
		
		$row = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
		
		if (!$row) {
			show_404();
		}

		$data = jobseeker_cv_data($seeker_id);
		
		if ($view_modal) {
			$this->load->view('jobseeker/modal/candidate_view', $data);
		} 
	}

	public function show_cv_job_application($seeker_id, $job_id)
	{
		$data['ads_row'] = $this->ads;

		if ($this->session->userdata('is_employer') != true && 
			$this->session->userdata('is_admin_login') != true
		) {
			$this->session->set_userdata('back_from_user_login',$this->uri->uri_string);
			$this->session->set_flashdata('msg', 'Inicie sesión como empleador para ver la postulación del candidato.');
			redirect(base_url('login'));
			return;	
		}
		
		$row = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
		
		if (!$row) {
			show_404();
		}

		$row_applied = $this->Applied_jobs->get_applied_job_by_seeker_and_job_id($seeker_id, $job_id);

		$data = jobseeker_cv_data($seeker_id);
		$data['result_answers_applicant'] = array();

		if (isset($row_applied->ID)) {
			$data['result_answers_applicant'] = $this->Applied_jobs->get_answers_applicant($row_applied->ID);
			$data['applied_id'] = $row_applied->ID;
		}	

		$this->load->view('jobseeker/modal/candidate_view', $data); 
	}

	public function view_application($applied_id = 0)
	{
		$data['ads_row'] = $this->ads;

		if ($this->session->userdata('is_employer') != true && 
			$this->session->userdata('is_admin_login') != true
		) {
			$this->session->set_userdata('back_from_user_login',$this->uri->uri_string);
			$this->session->set_flashdata('msg', 'Inicie sesión como empleador para ver la postulación del candidato.');
			redirect(base_url('login'));
			return;	
		}

		$row_applied = $this->Applied_jobs->get_applied_job_by_id($applied_id);

		if (!$row_applied) {
			show_404();
		}
		
		$seeker_id = $row_applied->seeker_ID;
		
		$row = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
		
		if (!$row) {
			show_404();
		}

		//if the application has not been seen mark as seen
		if ($row_applied->seen != 1) {
			$this->Applied_jobs->mark_as_seen_application($applied_id);
		}

		$data = jobseeker_cv_data($seeker_id);
		$data['result_answers_applicant'] = $this->Applied_jobs->get_answers_applicant($applied_id);
		$data['applied_id'] = $applied_id;

		$this->load->view('jobseeker/modal/candidate_view', $data); 
	}

	public function export_cv_pdf($data_encrypt = null)
	{			
        $current_url = site_url($this->uri->uri_string());
        
        if ($_SERVER['QUERY_STRING']) {
            $current_url .= '?' . $_SERVER['QUERY_STRING'];
        }
        
		$url_is_valid = $this->url_signer_lib->validate($current_url);
		
		if (!$url_is_valid) {
		    show_404();
		}
					
	    $data_params = $this->custom_encryption->decrypt_data($data_encrypt, 1);
		$seeker_id = $data_params['seeker_id'] ?? 0;
		
		if ($this->session->userdata('is_job_seeker') == TRUE && 
		    $this->session->userdata('user_id') !=  $seeker_id) {
		    show_404();
		}
		
		$row = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
		
		if (!$row) {
			show_404();
		}

		$data = jobseeker_cv_data($seeker_id);
	
		$html = $this->load->view('jobseeker/common/cv_v2_template_view', $data, true);
		
		$this->load->library('Mpdf/mpdf_lib');
		$this->mpdf_lib->SetDisplayMode('fullpage');
		$this->mpdf_lib->curlAllowUnsafeSslRequests = true;
		$this->mpdf_lib->WriteHTML($html);
		$this->mpdf_lib->Output();
	}

	public function export_cv_application_pdf($applied_id = 0)
	{
		if ($this->session->userdata('is_employer') != true && 
			$this->session->userdata('is_admin_login') != true
		) {
			$this->session->set_userdata('back_from_user_login',$this->uri->uri_string);
			$this->session->set_flashdata('msg', 'Inicie sesión como empleador para exportar en PDF la postulación del candidato.');
			redirect(base_url('login'));
			return;	
		}

		$row_applied = $this->Applied_jobs->get_applied_job_by_id($applied_id);

		if (!$row_applied) {
			show_404();
		}
		
		$seeker_id = $row_applied->seeker_ID;
		
		$row = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
		
		if (!$row) {
			show_404();
		}

		$show_questions = $this->input->get('show_questions');

		$data = jobseeker_cv_data($seeker_id);
		
		$data['result_answers_applicant'] = $this->Applied_jobs->get_answers_applicant($applied_id);
		$data['show_questions'] = $show_questions;
		
		$html = $this->load->view('jobseeker/common/cv_v2_template_view', $data, true);
		
		$this->load->library('Mpdf/mpdf_lib');
		$this->mpdf_lib->SetDisplayMode('fullpage');
		$this->mpdf_lib->curlAllowUnsafeSslRequests = true;
		$this->mpdf_lib->WriteHTML($html);
		$this->mpdf_lib->Output();
	}

	public function export_cv_word($seeker_id = 0)
	{
		show_404();
		/*
		if (!$this->session->userdata('user_id') &&
			$this->session->userdata('is_employer') != true &&
			$this->session->userdata('is_admin_login') != true) {

			$this->session->set_userdata('back_from_user_login',$this->uri->uri_string);
			$this->session->set_flashdata('msg', 'Por favor inicie sesión para ver la página.');
			redirect(base_url('login'));
			return;	
		}

		if ($this->session->userdata('is_employer') != true &&
			$this->session->userdata('is_admin_login') != true &&
			$this->session->userdata('user_id') != $seeker_id) {

			show_404();
		}
	
		$row = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
		
		if (!$row) {		
			show_404();
		}

		$this->load->library('cv_word');

		$data = jobseeker_cv_data($seeker_id);

		$this->cv_word->build_cv_word($data);

		$this->cv_word->download('curriculum.docx');
		*/
	}

	public function export_cv_application_word($applied_id = 0)
	{
	    show_404();
		// if ($this->session->userdata('is_employer') != true &&
		// 	$this->session->userdata('is_admin_login') != true
		// ) {
		// 	$this->session->set_userdata('back_from_user_login',$this->uri->uri_string);
		// 	$this->session->set_flashdata('msg', 'Inicie sesión como empleador para exportar en PDF la postulación del candidato.');
		// 	redirect(base_url('login'));
		// 	return;	
		// }
		
		// $row_applied = $this->Applied_jobs->get_applied_job_by_id($applied_id);

		// if (!$row_applied) {
		// 	show_404();
		// }

		// $seeker_id = $row_applied->seeker_ID;
		
		// $row = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
		
		// if (!$row) {
		// 	show_404();		
		// }

		// $show_questions = $this->input->get('show_questions');

		// $data = jobseeker_cv_data($seeker_id);
		
		// $data['result_answers_applicant'] = $this->Applied_jobs->get_answers_applicant($applied_id);
		// $data['show_questions'] = $show_questions;

		// $this->load->library('cv_word');

		// $this->cv_word->build_cv_word($data);

		// $this->cv_word->download('curriculum.docx');
	}

	public function load_rs_documents(
		$job_id = 0, 
		$candidate_id = 0, 
		$document_key = null
	)
	{
		$this->load->model('Recruitment_attached_document');
		$this->load->model('Exam_request_result_type');
		$this->load->model('Recruitment_document_type');

		$data['job_id'] = $job_id;
		$data['candidate_id'] = $candidate_id;
		$data['document'] = $this->Recruitment_document_type->find_by_key($document_key);

		$data['attached_files'] = $this->Recruitment_attached_document->get_files(
			$job_id, 
			$candidate_id,
			$document_key
		);

		$this->load->view('employer/recruitment/common/recruitment_documents', $data);
	}

	public function load_rs_other_documents(
		$job_id = 0, 
		$candidate_id = 0
	)
	{
		$this->load->model('Recruitment_attached_document');
		$this->load->model('Recruitment_document_type');

		$data['job_id'] = $job_id;
		$data['candidate_id'] = $candidate_id;
		$data['document'] = $this->Recruitment_document_type->find(13);

		$data['attached_files'] = $this->Recruitment_attached_document->get_other_files(
			$job_id, 
			$candidate_id
		);

		$this->load->view('employer/recruitment/common/recruitment_other_documents', $data);
	}

	public function load_document_screening(
		$job_id = 0, 
		$seeker_id = 0
	)
	{
		$this->load->model('Seeker_screening');
		$this->load->model('Recruitment_document_type');

		$data['job_id'] = $job_id;
		$data['candidate_id'] = $seeker_id;
		$document_key = 'screnning';
		$data['document'] = $this->Recruitment_document_type->find_by_key($document_key);
		
		$seeker = $this->Job_seeker->find($seeker_id);

        $screening_results = $this->Seeker_screening->get_all_results([
			'document_number' => $seeker->document_number
		]);

		$data['screening_results'] = $screening_results;

		$this->load->view('employer/recruitment/screening/common/screening_documents', $data);
	}

	public function load_document_exam_request_results(
		$job_id = 0, 
		$seeker_id = 0,
		$document_key = ''
	)
	{
		$this->load->model('Recruitment_document_type');
		$this->load->library(
            'Exam_request/Exam_request_get_all_lib', 
            null, 
            'Exam_request_get_all_lib'
        );

		$data['job_id'] = $job_id;
		$data['candidate_id'] = $seeker_id;
		$data['document'] = $this->Recruitment_document_type->find_by_key($document_key);
		
		$seeker = $this->Job_seeker->find($seeker_id);

		$data['exam_request_results'] = $this->Exam_request_get_all_lib->results(
            $job_id,
            $seeker_id, 
            $document_key
        );

		$this->load->view('employer/recruitment/exam_request_results/common/exam_request_result_documents', $data);
	}

	public function load_detail_requested_documents($process_id, $seeker_id)
	{
        $data = data_candidate_required_documents($process_id, $seeker_id);
		$this->load->view('employer/common/candidate_requested_documents', $data);
	}

	public function search_experience_overall($seeker_id)
	{
		$seeker = $this->Job_seeker->get_job_seeker_by_id($seeker_id);

		if (!$seeker) {
			show_404();
		}

		$seeker_experiences = $this->Job_seeker->get_overall_work_experiences([$seeker->document_number]);
		$data['experiences'] = $seeker_experiences[$seeker->document_number] ?? [];
	
		$this->load->view('employer/common/candidate_experiences_overall', $data);
	}

	public function export_pdf_entry_form($job_id, $candidate_id)
	{
		$this->load->model('Mof');
		$this->load->model('Requested_document');
		$this->load->model('Jobseeker_required_document');
		$this->load->model('Jobseeker_form_rtps');
		$this->load->model('Recruitment_attached_document');
		$this->load->model('Seeker_certificate_5th_category');
		$this->load->model('Seeker_document_photo');
		$this->load->model('Seeker_identification_document');
		$this->load->model('Recruitment_contract_document');
		
		$candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);
		$job = $this->Posted_job->get_posted_job_by_id($job_id);
		$staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

		$data['candidate'] = $candidate;
		$data['job'] = $job;
		$data['staff_request'] = $staff_request;
       
		$html = $this->load->view('employer/recruitment/common/candidate_document_entry_form', $data, true);

		$this->load->library('Mpdf/mpdf_lib');
		$this->mpdf_lib->SetDisplayMode('fullpage');
		$this->mpdf_lib->WriteHTML($html);
		$this->mpdf_lib->Output();
	}

	public function entry_job_seeker($key = '')
	{
		$this->load->model('Entry_job_seeker');
		
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Ingreso postulante - ' . SITE_NAME;

		$jobseeker = $this->Entry_job_seeker->get_job_seeker_by_key($key);

		if (!$jobseeker || $jobseeker->activated) {
			show_404();
		}

		$data['jobseeker'] = $jobseeker;

		$this->form_validation->set_rules('first_name', 'Nombre', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('paternal_last_name', 'Apellido paterno', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('maternal_last_name', 'Apellido materno', 'trim|required|strip_all_tags');

		$this->form_validation->set_rules('new_password', 'nueva contraseña', 'trim|required|min_length[6]|strip_all_tags');
		$this->form_validation->set_rules('confirm_password', 'confirmar contraseña', 'trim|required|matches[new_password]|strip_all_tags');
		
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('jobseeker/entry_job_seeker_view', $data);
			return;
		} 

		$all_input = $this->input->post();
		$trans_status = $this->Entry_job_seeker->entry_job_seeker($all_input);
		
		if ($trans_status) {
			$this->session->set_flashdata('success_msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> Por favor ingresa a tu perfil para completar el proceso de registro.</div>');
			redirect('login');	
		} else {
			redirect('candidate/entry_job_seeker');
		}
	}
	
	public function download_requested_documents(
		$job_id,
        $seeker_id = 0
    )
    {
		/*
		if ($this->session->userdata('is_employer') != true && 
			$this->session->userdata('is_admin_login') != true
		) {
			$this->session->set_userdata('back_from_user_login',$this->uri->uri_string);
			$this->session->set_flashdata('msg', 'Inicie sesión como empleador para ver la postulación del candidato.');
			redirect(base_url('login'));
			return;	
		}
		*/

        //$this->load->model('Requested_document');
        $this->load->library(
        	'Utils/Seeker_requested_documents_lib', 
        	null, 
        	'Seeker_requested_documents_lib'
        );
       
        $this->Seeker_requested_documents_lib->download($job_id, $seeker_id);
    }

    public function cv_data($seeker_id = 0)
    {
    	if ($this->session->userdata('is_employer') != true && 
			$this->session->userdata('is_admin_login') != true
		) {
			show_404();
			return;
		}

   		$data = jobseeker_cv_data($seeker_id);

   		$this->load->view('jobseeker/common/cv_template_view', $data); 
    }

	public function cancel_rtps($rtps_id)
	{

		$this->load->library(
			'Form_rtps/Form_rtps_cancel_sign_evicertia_lib', 
			null , 
			'Form_rtps_cancel_sign_evicertia_lib'
		);

		var_dump($this->Form_rtps_cancel_sign_evicertia_lib->send($rtps_id));

	}
	public function video_interview($job_id, $seeker_id)
	{
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

		$video_interview = $this->db->get_where(
			'tbl_recruitment_interview_videos', [
				'job_id' => $job_id,
				'seeker_id' => $seeker_id,
		])->row();

		$data['video_interview'] = $video_interview;
    	$data['job_id'] = $job_id;
    	$data['seeker_id'] = $seeker_id;

    	$this->load->view(
    		'employer/recruitment/common/video_interview',
    		$data
    	);
	}

	public function form_candidate_list($job_id, $seeker_id)
	{
		$this->load->model('Rys_form_seeker');

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

		$forms = $this->Rys_form_seeker->get_forms_assigned_by_seeker($seeker_id); 

		$data['forms'] = $forms;
		$data['job_id'] = $job_id;

    	$this->load->view(
    		'employer/recruitment/common/candidate_form_list',
    		$data
    	);
	}

	public function form_detail($form_assignment_id, $job_id = 0)
	{
		$this->load->model('Rys_form');
		$this->load->model('Rys_form_question');
		$this->load->model('Rys_form_seeker');
		$this->load->model('Form_question');

		$assignment_form = $this->Rys_form_seeker->get_assignment($form_assignment_id);

		$data['assignment_form'] = $assignment_form;
		$data['form'] = $this->Rys_form->get_form_by_id($assignment_form->form_id);
		$data['form_questions'] = $this->Rys_form_question->get_active_by_form_id($assignment_form->form_id);
		$data['jobseeker'] = $this->Job_seeker->find($assignment_form->seeker_id);
		$data['job_id'] =  $job_id;

    	$this->load->view(
    		'jobseeker/question_forms/partials/form_answer',
    		$data
    	);
	}

	public function screening_show_pdf($screening_id = null, $display_type = 0)
    {
		if (!($this->session->userdata('user_id') && $this->session->userdata('is_employer') === true)) {
			show_404();
		}

		$screening_value_id = $this->custom_encryption->decrypt_data($screening_id);

		if (!ctype_digit($screening_value_id)) {
			show_404();
		}

        $this->load->library('Pdf/Screening_jobseeker_pdf');

        $this->screening_jobseeker_pdf->show($screening_value_id, $display_type);
    }
}
