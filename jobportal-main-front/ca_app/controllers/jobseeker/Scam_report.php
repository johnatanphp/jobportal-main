<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scam_Report extends CI_Controller {
	
	public function index()
	{
		$data = array();
		
		if(!$this->session->userdata('is_job_seeker')){
			echo 'No has iniciado sesión con una cuenta de búsqueda de empleo. Vuelva a iniciar sesión con una cuenta de búsqueda de empleo para enviar el mensaje.';
			exit;	
		}

		$this->form_validation->set_rules('reason', 'Mensaje', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('scjid', 'ID', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'trim|required|valid_grecaptcha', array('m'));

		$this->form_validation->set_message('required', 'El campo %s es requerido');
		
		$this->form_validation->set_error_delimiters('<span class="err" style="padding-left:2px;">', '</span>');
		
		if ($this->form_validation->run() === FALSE) {
			$data['msg'] = validation_errors();
			echo json_encode($data);
			exit;
		}

		$row = $this->Posted_job->get_active_posted_job_by_id($this->input->post('scjid'));
	
		if(!$row){
			$data['msg'] = 'Algo salió mal: no se encontró el empleo!';
			echo json_encode($data);
			exit;	
		}
		
		if($this->session->userdata('is_job_seeker')!=TRUE){
			$data['msg'] = 'No has iniciado sesión con una cuenta de búsqueda de empleo. Por favor vuelva a iniciar sesión con una cuenta de búsqueda de empleo para poder enviar este formulario.';
			echo json_encode($data);
			exit;
		}
		
		$employer_email = $row->employer_email;
		$employer_name = $row->first_name;

		$current_date_time = date("Y-m-d H:i:s");
		
		$d_array = array(
			'reason' => $this->input->post('reason'),
			'job_ID' => $this->input->post('scjid'),
			'user_ID' => $this->session->userdata('user_id'),
			'dated' => $current_date_time,
			'ip_address' => $this->input->ip_address()
		);
		
		$this->Scam->add($d_array);
		
		//Sending email
		$row_email = $this->Email->get_records_by_id(8);
		
		$seeker_id = $this->session->userdata('user_id');
		
		$config = $this->Email_drafts->email_configuration();
		
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($employer_email);
		
		$data_email = array(
			'employer_name' => $employer_name,
			'jobseeker_name' => $this->session->userdata('first_name'),
			'jobseeker_email' => $this->session->userdata('user_email'),
			'job_title' => $row->job_title,
			'company_name' => $row->company_name,
			'reason' => $this->input->post('reason'),
			'dated' => $current_date_time
		);

		$mail_message = load_email_view('email/scam_report', $data_email);

		$this->email->subject('Nuevo mensaje - Empleo "' . $row->job_title . '"');
		$this->email->message($mail_message);     
		$this->email->send();
		
		$this->session->set_userdata('timestm', date("H:i:s"));
		
		$data['msg'] = 'done';
	
		echo json_encode($data);
	}
}
