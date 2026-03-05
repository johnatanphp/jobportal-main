<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Countries extends CI_Controller
{	
	public function index()
	{
		$data['title'] = SITE_NAME.': Gestión de Países';
		$data['msg'] = '';
		
		$filters = [
            'query' => trim((string)$this->input->get('query', true)),
        ];

		//Pagination starts
		$total_rows = $this->Country->count_countries($filters);
		$config = pagination_configuration(base_url("admin/countries"), $total_rows, 25, 3, 5, true);
		
		$this->pagination->initialize($config);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(3) : 0;
		$page_num = $page-1;
		$page_num = ($page_num<0)?'0':$page_num;
		$page = $page_num*$config["per_page"];
		$data["links"] = $this->pagination->create_links();
		//Pagination ends
		
		$obj_result = $this->Country->search_countries($filters, $config["per_page"], $page);
		$data['result'] = $obj_result;
		$data['filters'] = $filters;
		
		$this->load->view('admin/countries/countries_view', $data);
		return;
	}
	
	public function get_country_by_id($id='')
	{
		if($id!=''){
			$row = $this->Country->get_country_by_id($id);
			$json_data = json_encode($row);
			echo $json_data;
			exit;
		}
		return;
	}

	public function add()
	{
		$data['title'] = SITE_NAME.': Countries Management';
		$data['msg'] = '';
		
		$this->form_validation->set_rules('country_name', 'Country Name', 'trim|required');
		$this->form_validation->set_rules('country_citizen', 'Nationality', 'trim|required');
		$this->form_validation->set_error_delimiters('<span class="err" style="padding-left:2px;">', '</span>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}
		
		$countries_array = array(
							'country_name' => $this->input->post('country_name'),
							'country_citizen' => $this->input->post('country_citizen')
		);
		$this->Country->add_country($countries_array);
		$this->session->set_flashdata('added_action', true);
		redirect(base_url('admin/countries'));
	}
		
	public function update()
	{	
		$id = $this->input->post('countries_id');
		if($id==''){
			redirect(base_url().'admin/countries','');
			exit;
		}
		
		$data['title'] = SITE_NAME.': Edit Page';
		$data['msg'] = '';
		
		$this->form_validation->set_rules('edit_country_name', 'Country Name', 'trim|required');
		$this->form_validation->set_rules('edit_country_citizen', 'Natonality', 'trim|required');
		$this->form_validation->set_error_delimiters('<span class="err" style="padding-left:2px;">', '</span>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}
		
		$countries_array = array(
							'country_name' => $this->input->post('edit_country_name'),
							'country_citizen' => $this->input->post('edit_country_citizen')
		);
		$this->Country->update_country($id, $countries_array);
		$this->session->set_flashdata('update_action', true);
		redirect(base_url('admin/countries'));
		return;
	}

	public function delete($id='')
	{	
		if($id==''){
			echo 'error';
			exit;
		}
		
		$obj_row = $this->Country->delete_country($id);
		echo 'done';
		exit;
	}
}