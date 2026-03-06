<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_request_authorities extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
		//Load models
    	$this->load->model('Staff_request_authority');
		$this->load->model('Company');
		$this->load->model('Business_unit');
    }

	public function index($company_id = 0, $authority_type = 0)
	{
		$data['title'] = 'Gestionar autoridades - ' . SITE_NAME;
		$data['msg'] = '';
	
		if ($authority_type < 1 || $authority_type > 4) {
			$data['companies'] = $this->Company->get_all_internal();
			$data['company_id'] = $company_id;
			
			$this->load->view('admin/staff_request/manage_authorities_view', $data);
			return;
		}

		$this->form_validation->set_rules('authority_name', 'Nombre de la autoridad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('authority_email', 'Email de la autoridad', 'trim|required|valid_email|strip_all_tags');
		
		if ($authority_type == 1) { // DIRECTOR RESPONSABLE
			$this->form_validation->set_rules('authority_business_unit', 'Unidad de negocio', 'trim|required');
		}

		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

		if ($this->form_validation->run() === FALSE) {
			$data['result_authorities'] = $this->Staff_request_authority->get_authorities_by_type($company_id, $authority_type);
			$data['authority_type'] = $authority_type;
			$data['company_id'] = $company_id;

			$this->load->view('admin/staff_request/authorities_list_view', $data);
			return;
		}
	}

	public function create($company_id, $authority_type)
	{
		if ($authority_type < 1 || $authority_type > 4) {
			redirect('staff_request_authorities');
		}

		$this->form_validation->set_rules('authority_name', 'Nombre de la autoridad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('authority_email', 'Email de la autoridad', 'trim|required|valid_email|strip_all_tags');
		
		if ($authority_type == 1) { // DIRECTOR RESPONSABLE
			$this->form_validation->set_rules('authority_business_unit', 'Unidad de negocio', 'trim|required');
		}

		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

		if ($this->form_validation->run() === FALSE) {
			$data['title'] = 'Nueva autoridad - ' . SITE_NAME;
			$data['authority_type'] = $authority_type;
			$data['company_id'] = $company_id;
			$data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $company_id]);

			$this->load->view('admin/staff_request/authority_new_view', $data);
			return;
		}

		$data_autoriry = array(
			'name' => $this->input->post('authority_name'),
			'email' => $this->input->post('authority_email'),
			'type_authority_ID' => $authority_type,
			'business_unit_ID' => $authority_type == 1 ? $this->input->post('authority_business_unit') : null,
			'company_id' => $company_id
		);

		$trans_status = $this->Staff_request_authority->save($data_autoriry);

		if ($trans_status) {
			$this->session->set_flashdata('add_action', true);
		}

		redirect('admin/staff_request_authorities/' . $authority_type);
	}

	public function edit($authority_id)
	{
		$authority = $this->Staff_request_authority->get_authority_by_id($authority_id);

		if (!$authority) {
			show_404();
		}

		$this->form_validation->set_rules('authority_name', 'Nombre de la autoridad', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('authority_email', 'Email de la autoridad', 'trim|required|valid_email|strip_all_tags');
		$this->form_validation->set_rules('authority_status', 'Estado', 'trim|required|strip_all_tags');

		if ($authority->type_authority_ID == 1) { // DIRECTOR RESPONSABLE
			$this->form_validation->set_rules('authority_business_unit', 'Unidad de negocio', 'trim|required');
		}

		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');

		if ($this->form_validation->run() === FALSE) {
			$data['title'] = 'Editar autoridad - ' . SITE_NAME;
			$data['authority'] = $authority;
			$data['business_units'] = $this->Business_unit->all(['active' => 1, 'company_id' => $authority->company_id]);
			$this->load->view('admin/staff_request/authority_edit_view', $data);
			return;
		}

		$all_inputs = $this->input->post();
		$trans_status = $this->Staff_request_authority->edit($all_inputs, $authority_id);

		if ($trans_status) {
			$this->session->set_flashdata('update_action', true);
		}

		redirect('admin/staff_request_authorities/' . $authority->company_id . '/' . $authority->type_authority_ID);
	}	

	public function delete_authority()
	{
		$authority_id = $this->input->post('authority_id');
		$trans_status = $this->Staff_request_authority->delete($authority_id);
		
		if ($trans_status) {
			$this->session->set_flashdata('delete_action', true);	
		}
	}
}
