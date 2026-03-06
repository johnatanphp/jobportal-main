<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Change_Password extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();
		
		//force record the data
		//validate_jobseeker_data();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = SITE_NAME.': Cambiar contraseña';
		
		//Get info job seeker
		$jobseeker = $this->Job_seeker->get_job_seeker_by_id($this->session->userdata('user_id'));

		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($jobseeker->ID);
		
		$old_password_required = $jobseeker->password !== NULL;

		if ($old_password_required) {
			$this->form_validation->set_rules('old_password', 'Contraseña actual', 'trim|required');
		}

		$this->form_validation->set_rules('new_password', 'Nueva contraseña', 'trim|required|min_length[8]|password_strength');
		$this->form_validation->set_rules('confirm_password', 'Confirmar contraseña', 'trim|required|matches[new_password]');
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		if ($this->form_validation->run() == FALSE) {
			$data['old_password_required'] = $old_password_required;
			$this->load->view('jobseeker/change_password_view', $data);
			return;
		} else {
			
			if ($old_password_required) {
				$old_password = $this->input->post('old_password');
				$rs = $this->Job_seeker->authenticate_job_seeker_by_id_password($jobseeker->ID, $old_password);
				
				if (!$rs) {
					$this->session->set_flashdata('msg', '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Contraseña actual no es correcta.</div>');
					redirect(base_url('jobseeker/change_password'));
					return;
				}
			}

			$jobseeker_array = array(
				'password' => do_hashing($this->input->post('new_password'))
			);
			
			$this->Job_seeker->update($jobseeker->ID, $jobseeker_array);
			$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> Contraseña guardada exitosamente.</div>');
			redirect(base_url('jobseeker/change_password'));
			return;	
		}
	}
}
