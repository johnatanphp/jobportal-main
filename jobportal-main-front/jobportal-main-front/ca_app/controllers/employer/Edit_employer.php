<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_Employer extends CI_Controller {
	public function index(){
		echo "you are not allow to access this page directly";
		exit;
	}
	
	public function profile()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
	}
	
	public function summary()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		$this->form_validation->set_rules('content', 'summary', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('cid', 'ID', 'trim|required|strip_all_tags');
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}
		$summary_array = array(
							'company_description'	=> $this->input->post('content')
		);
		$this->Company->update_company($this->input->post('cid'), $summary_array);
		$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> El resumen se ha actualizado con éxito. </div>');
		echo "done";
	}
}
