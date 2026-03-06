<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class additional_info extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		//force record the data
		//validate_jobseeker_data();
    }
	
	public function index()
	{
		if(!$this->session->userdata('user_id')){
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}
		
		//Additional Info
		$row_additional = $this->Jobseeker_additional_info->get_record_by_userid($this->session->userdata('user_id'));
		
		$data['ads_row'] = $this->ads;
		$row = $this->Jobseeker_additional_info->get_record_by_userid($this->session->userdata('user_id'));
		$data['title'] = SITE_NAME.': Manage Additional Information';
		$data['row'] = $row;

		$this->form_validation->set_rules('salary_currency', 'Moneda', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('salary_min', 'Salario mínimo', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('salary_max', 'Salario máximo', 'trim|required|numeric|greater_than[0]|greater_than_equal_to[' . $this->input->post('salary_min') . ']');
		$this->form_validation->set_rules('interest', 'Intereses', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('description', 'Objetivos', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('awards', 'Logros / Premios', 'trim|strip_all_tags');
		
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('jobseeker/additional_info_view',$data);
			return;
		}
		
		$data_array = array(
			'seeker_ID' => $this->session->userdata('user_id'),
			'awards' => $this->input->post('awards'),
			'description' => $this->input->post('description'),
			'interest' => $this->input->post('interest'),
			'salary_currency' => $this->input->post('salary_currency'),
			'salary_min' => $this->input->post('salary_min'),
			'salary_max' => $this->input->post('salary_max'),
		);
	
		if ($row) {
			$this->Jobseeker_additional_info->update($row->ID, $data_array);
			$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Se actualizó información adicional con éxito. </div>');
		} else {
			$this->Jobseeker_additional_info->add($data_array);
			$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> No se pudo agregar la información adicional. </div>');
		}
		
		redirect(base_url('jobseeker/dashboard'));
	}
}
