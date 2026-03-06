<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Form_seekers extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load model
		$this->load->model('Rys_form');
		$this->load->model('Rys_form_seeker');
		$this->load->model('Recruitment_process');
		$this->load->model('Form_question');
		$this->load->model('Rys_form_question');
		//Load library
		$this->load->library('rys_seeker_fit_helper');
	}

	public function show_answer(
		$form_id, 
		$job_id, 
		$seeker_id
	)
	{
		$data['title'] = 'Respuestas formulario - '. SITE_NAME;
		
		$rs_process = $this->Recruitment_process->get_process_by_job_id($job_id);
		
		$assignment_form = $this->Rys_form_seeker->get_assignment_by(
			$form_id,
			$job_id,
			$seeker_id
		);

		$data['form'] = $this->Rys_form->get_form_by_id(
			$form_id
		);

		if ($assignment_form) {
			$data['jobseeker'] = $this->Job_seeker->get_job_seeker_by_id(
				$assignment_form->seeker_id
			);

			$data['form_questions'] = $this->Rys_form_question->get_active_by_form_id(
				$assignment_form->form_id
			);
		}

		$data['assignment_form'] = $assignment_form;
		$data['rs_process'] = $rs_process;
		$data['job_id'] = $job_id;
		
		$this->load->view('employer/recruitment/modal/sworn_declaration_answer', $data);
	}
}
