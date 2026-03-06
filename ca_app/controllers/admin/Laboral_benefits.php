<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Laboral_benefits extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Company');
	}

	public function index($company_id = 0)
	{
		$data['title'] = SITE_NAME . ': Gestión de beneficios laborales';
		$data['msg'] = '';
		
		$filters = [
			'company_id' => $company_id
		];
		
		//Pagination starts
		$total_rows = $this->Laboral_benefit->record_count('tbl_laboral_benefits');
		$config = pagination_configuration(base_url("admin/laboral_benefits"), $total_rows, 50, 3, 5, true);
		
		$this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(4) : 0;
		$page_num = $page - 1;
		$page_num = ($page_num < 0) ? '0' : $page_num;
		$page = $page_num*$config["per_page"];
		$data["links"] = $this->pagination->create_links();
		$data['companies'] = $this->Company->get_all_internal();
		//Pagination ends
		
		$obj_result = $this->Laboral_benefit->get_all_records($filters, $config["per_page"], $page);
		$data['result'] = $obj_result;
		$data['company_id'] = $company_id;
		$this->load->view('admin/laboral_benefits_view', $data);
		return;
	}
		
	public function add()
	{
		$data['title'] = SITE_NAME.': Gestión de beneficios laborales';
		$data['msg'] = '';
		
		$this->form_validation->set_rules('benefit_name', 'Beneficio laboral', 'trim|required|strip_all_tags');
		$this->form_validation->set_error_delimiters('<span class="err" style="padding-left:2px;">', '</span>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$company_id = $this->input->post('company_id');

		$benefit = $this->db->get_where('tbl_laboral_benefits', [
			'benefit_name' => $this->input->post('benefit_name'),
			'company_id' => $this->input->post('company_id')
		])->row();

		if ($benefit) {
			$this->session->set_flashdata('added_action', false);
			redirect('admin/laboral_benefits/' . $company_id);
			return;
		}

		$data_array = [
			'benefit_name' => $this->input->post('benefit_name'),
			'company_id' => $company_id
		];

		$this->Laboral_benefit->add_benefit($data_array);
		$this->session->set_flashdata('added_action', true);

		redirect('admin/laboral_benefits/' . $company_id);
	}
		
	public function update()
	{	
		$id = $this->input->post('benefit_id');

		$benefit = $this->Laboral_benefit->find($id);

		if (!$benefit) {
			redirect('admin/laboral_benefits');
			exit;
		}
		
		$data['title'] = SITE_NAME . ': Editar beneficio laboral';
		$data['msg'] = '';
		
		$this->form_validation->set_rules('edit_benefit_name', 'Beneficio laboral', 'trim|required|strip_all_tags');
		$this->form_validation->set_error_delimiters('<span class="err" style="padding-left:2px;">', '</span>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$benefit_update = $this->db->get_where('tbl_laboral_benefits', [
			'benefit_name' => $this->input->post('benefit_name'),
			'company_id' => $benefit->company_id,
			'id!=' => $id
		])->row();

		if ($benefit_update) {
			$this->session->set_flashdata('added_action', false);
			redirect('admin/laboral_benefits/' . $benefit->company_id);
			return;
		}

		$data_array = [
			'benefit_name' => $this->input->post('edit_benefit_name'),
			'active' => $this->input->post('active')
		];		

		$this->Laboral_benefit->update_benefit($id, $data_array);

		$this->session->set_flashdata('update_action', true);
		redirect('admin/laboral_benefits/' . $benefit->company_id);
		return;
	}

	public function get_benefit_by_id($id = '')
	{	
		if (empty($id)) {
			return;
		}
	
		$row = $this->Laboral_benefit->get_benefit_by_id($id);
		echo json_encode($row);
		exit;	
	}
		
	public function delete($id = '')
	{	
		if (empty($id)) {
			echo 'error';
			exit;
		}
		
		$obj_row = $this->Laboral_benefit->delete_benefit($id);
		echo 'done';
		exit;
	}
}
