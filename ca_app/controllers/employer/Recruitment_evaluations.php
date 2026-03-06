<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_evaluations extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		//Load models
		$this->load->model('Candidate_evaluation');
    	
    	//Load libraries
    	$this->load->library('aws_sns_client_lib'); 
    }

    public function schedule_evaluation()
	{
		$job_id = $this->input->post('job_id');
		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		$this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
		$this->form_validation->set_rules('candidate_id', 'Candidato', 'trim|required|strip_all_tags');
		//$this->form_validation->set_rules('responsible_employer_id', 'Empleador responsable', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('date', 'Fecha evaluación', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('hour', 'Hora evaluación', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('place', 'Lugar de la evaluación', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('more_details', 'Más detalles', 'trim|strip_all_tags');
		
		if ($this->form_validation->run() === FALSE) {
			
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos incorrectos'
			));

			return;
		}

		$all_input = $this->input->post();

		$staff_request = $this->Staff_request->find($job->request_ID);

        $all_input['responsible_employer_id'] = $staff_request && $staff_request->employer_ID ? 
                                                $staff_request->employer_ID : 
                                                $this->session->userdata('user_id');

		$trans_status = $this->Candidate_evaluation->schedule_evaluation(
			$all_input
		);

		if ($trans_status) {
			$candidate_id = $this->input->post('candidate_id');
			$this->notify_scheduled_evaluation_to_candidate(
				$job_id, 
				$candidate_id
			);
		}

		echo json_encode(array(
			'success' => $trans_status
		));
	}

	public function modal_schedule_evaluation($job_id, $candidate_id)
	{
		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		$data['schedule_evaluation'] = $this->Candidate_evaluation->get_scheduled_evaluation(
			$job_id, 
			$candidate_id
		);
		
		$staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

		$data['job_id'] = $job_id;
		$data['candidate_id'] = $candidate_id;
		$data['staff_request'] = $staff_request;

		$this->load->view('employer/recruitment/modal/candidate_schedule_evaluation', $data);
	}

	public function send_notification_schedule_evaluation()
	{
		$job_id = $this->input->post('job_id');
		$candidate_id = $this->input->post('candidate_id');

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }
		
		$this->notify_scheduled_evaluation_to_candidate($job_id, $candidate_id);

		echo json_encode(array('success' => true));			
	}

	private function notify_scheduled_evaluation_to_candidate($job_id, $candidate_id)
    {
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
     	
     	$candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);

     	$scheduled_evaluation = $this->Candidate_evaluation->get_scheduled_evaluation(
			$job_id, 
			$candidate_id
		);

		$responsible_employer = $this->Employer->get_employer_by_id(
			$scheduled_evaluation->responsible_employer_ID
		);

        $data_email = array(
            'name' => $candidate->first_name,
            'job' => $job,
            'scheduled_evaluation' => $scheduled_evaluation,
            'responsible_employer' => $responsible_employer  
        );

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($candidate->email);

        $mail_message = load_email_view('email/recruitment_scheduled_evaluation', $data_email);

        $this->email->subject('Evaluación programada');
        $this->email->message($mail_message);     
        //Send email
        $this->email->send();

		if (!$candidate->mobile) {
			return;
		}

		try {

		} catch (Exception $e) {
			//Send text message
			$sms_text = "Hola " . $candidate->first_name . ", la empresa " . 
			$responsible_employer->company_name . " te ha programado una evaluación para la fecha: " . 
			format_date($scheduled_evaluation->date, 'd/m/Y') . ", hora: " . 
			format_date($scheduled_evaluation->hour, 'h:i a') . ", lugar: " . 
			$scheduled_evaluation->place . ", puesto: " . $job->job_title . ". Portal empleo Overall.";

			$this->aws_sns_client_lib->send_text_message($sms_text, $candidate->mobile);
		}
    }
}
