<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contact_us extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Contáctanos - ' . SITE_NAME;
		$data['msg']='';
		$data['result_cities'] = $this->City->get_all_cities();
		$data['result_countries'] = $this->Country->get_all_countries();
		
		$this->form_validation->set_rules('full_name', 'Nombre completo', 'trim|required|strip_all_tags|time_diff');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|strip_all_tags');	
		$this->form_validation->set_rules('city', 'Ciudad / Ubicación', 'trim');
		$this->form_validation->set_rules('phone', 'Teléfono', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('message', 'Mensaje / Pregunta', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('g-recaptcha-response', 'reCaptcha', 'trim|required|valid_grecaptcha');
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('contact_us_view', $data);
			return;
		}

		$data_contact_us = array(
			'full_name' => $this->input->post('full_name'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'message' => $this->input->post('message'),
			'ip_address' => $this->input->ip_address(),
			'city' =>  $this->input->post('city'),
			'dated' => date("M d, Y H:i")
		);
		
		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to(ADMIN_EMAIL);
		$mail_message = load_email_view('email/contact_us', $data_contact_us);

		$this->email->subject('Formulario de contacto');
		$this->email->message($mail_message);     
		$this->email->send();
	
		$this->session->set_userdata('timestm', date("H:i:s"));
		$this->session->set_flashdata('success_msg', '<div class="alert alert-success">Su mensaje ha sido enviado con éxito.</div>');
		redirect(base_url('contact-us'),'');
	}
}
