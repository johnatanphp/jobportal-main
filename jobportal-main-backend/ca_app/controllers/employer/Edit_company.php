<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Edit_Company extends CI_Controller {
	
	public function __construct(){
        parent::__construct();
		$this->ads = '';
		$this->ads = $this->Ad->get_ads();
    }
	
	public function index()
	{
		$data['ads_row'] = $this->ads;
		$data['msg']='';
		$data['result_cities'] = $this->City->get_all_cities();
		$data['result_countries'] = $this->Country->get_all_countries();
		$data['result_industries'] = $this->Industry->get_all_industries();

		$row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		if ($row->is_admin != 'yes') {
			return;
		}
	
		$data['row'] = $row;
		$data['title'] = $row->company_name .' - Editar perfil';
		
		$this->form_validation->set_rules('company_ruc', 'RUC de la empresa', 'trim|required|edit_is_unique[tbl_companies.company_ruc.' . $row->CID . ']|strip_all_tags');
		$this->form_validation->set_rules('company_country', 'país', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('company_city', 'ubicaciónd', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('company_name', 'nombre de la empresa', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('industry_id', 'Industria', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('company_location', 'dirección', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('company_description', 'Descripción de la empresa', 'trim|required|strip_all_tags|secure');
		$this->form_validation->set_rules('company_phone', 'teléfono empresa', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('no_of_employees', 'N° de empleados', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('company_website', 'página web de la empresa', 'trim|required|strip_all_tags');
		$this->form_validation->set_error_delimiters('<div class="errowbox"><div class="erormsg">', '</div></div>');
		
		$data['industry_id'] = (set_value('industry_id'))?set_value('industry_id'):$row->industry_ID;
		$data['ownership_type'] = (set_value('ownership_type'))?set_value('ownership_type'):$row->ownership_type;
		$data['company_ruc'] = (set_value('company_ruc'))?set_value('company_ruc') : $row->company_ruc;
		$data['company_name'] = (set_value('company_name'))?set_value('company_name'):$row->company_name;
		$data['company_location'] = (set_value('company_location'))?set_value('company_location'):$row->company_location;
		$data['company_country'] = (set_value('company_country'))?set_value('company_country'):$row->company_country;
		$data['company_city'] = (set_value('company_city'))?set_value('company_city'):$row->company_city;
		$data['company_phone'] = (set_value('company_phone'))?set_value('company_phone'):$row->company_phone;
		$data['company_website'] = (set_value('company_website'))?set_value('company_website'):$row->company_website;
		$data['no_of_employees'] = (set_value('no_of_employees'))?set_value('no_of_employees'):$row->no_of_employees;
		$data['company_description'] = (set_value('company_description'))?set_value('company_description'):$row->company_description;
		
		//$ip_address = ($row->ip_address == '' ) ? $this->input->ip_address() : $row->ip_address;
		
		if ($this->form_validation->run() === FALSE) {
			$this->load->view('employer/edit_company_profile_view',$data);
			return;
		}

		$company_slug = make_slug($this->input->post('company_name'));
		$is_slug = $this->Company->check_slug_edit($row->company_ID, $company_slug);
		
		if($is_slug>0){
			$company_slug.='-'.time();
		}

		list($country_id, $country_name) = explode('-', $this->input->post('company_country'));

		$company_array = array(
			'company_ruc' => $this->input->post('company_ruc'),
			'company_name' => $this->input->post('company_name'),
			'industry_ID' => $this->input->post('industry_id'),
			'company_phone' => $this->input->post('company_phone'),
			'company_location' => $this->input->post('company_location'),
			'company_country' => $country_name,
			'country_id' => $country_id,
			'company_city' => $this->input->post('company_city'),
			'company_website' => $this->input->post('company_website'),
			'no_of_employees' => $this->input->post('no_of_employees'),
			'company_description' => $this->input->post('company_description'),
			'company_slug' => $company_slug,
			'ownership_type' => $this->input->post('ownership_type')
		);
		
		$this->Company->update_company($row->company_ID, $company_array);

		$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> El perfil de su empresa ha sido actualizado.</div>');
		$this->session->set_userdata('slug',$company_slug);
		redirect(base_url('employer/edit_company'));
	}

/*
	public function upload_logo()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		if (!empty($_FILES['upload_logo']['name'])) {
			
			$obj_row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
			
			if ($obj_row->is_admin != 'yes') {
				return;
			}

			$real_path = realpath(APPPATH . '../public/uploads/employer/');
			$config['upload_path'] = $real_path;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['overwrite'] = true;
			$config['max_size'] = 2000;
			$config['file_name'] = md5($obj_row->company_ID . '_' . time());
			$this->upload->initialize($config);
			
			if ($this->upload->do_upload('upload_logo')) {
				if ($obj_row->company_logo) {
					@unlink($real_path . '/' . $obj_row->company_logo);	
					@unlink($real_path . '/thumb/' . $obj_row->company_logo);
				}
			} else {
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('msg', '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> ' . strip_tags($error['error']).' </div>');
				redirect(base_url('employer/dashboard'));
				exit;
			}
	
			$image = array('upload_data' => $this->upload->data());	
			$image_name = $image['upload_data']['file_name'];
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = $real_path . '/' . $image_name;
			$thumb_config['new_image']	= $real_path . '/thumb/' . $image_name;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['height']	= 50;
			$thumb_config['width']	= 70;
			
			$this->image_lib->initialize($thumb_config);
			$this->image_lib->resize();
			
			$thumb_config2['image_library'] = 'gd2';
			$thumb_config2['source_image']	= $real_path . '/' . $image_name;
			$thumb_config2['new_image']	= $real_path . '/' . $image_name;
			$thumb_config2['maintain_ratio'] = TRUE;
			$thumb_config2['height'] = 250;
			$thumb_config2['width']	 = 250;
			$this->image_lib->initialize($thumb_config2);
			$this->image_lib->resize();
			
			$photo_array = array('company_logo' => $image_name);
			$this->Company->update_company($obj_row->company_ID, $photo_array);
			$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Logo de la empresa cargado con éxito. </div>');
		}
		
		$redirect = base_url('employer/dashboard');
		
		if ($this->agent->is_referral()) {
			$redirect = $this->agent->referrer();	
		}
		
		redirect($redirect);
	}
	*/

	public function upload_logo()
	{
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		if (!empty($_FILES['upload_logo']['name'])) {
			
			$obj_row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));
			
			if ($obj_row->is_admin != 'yes') {
				return;
			}

			$file_name = md5(uniqid('company_logo_' . $obj_row->company_ID, true));

			$path_company_logo = $this->Company->upload_logo('upload_logo', $file_name);

			if ($path_company_logo === false) {
				$this->session->set_flashdata('msg', '<div class="alert alert-danger"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Error!</strong> No se pudo cargar la imagen </div>');
				redirect('employer/dashboard');
				return;
			}
			
			$photo_array = ['company_logo' => $path_company_logo];
			$this->Company->update_company($obj_row->company_ID, $photo_array);
			
			$this->session->set_flashdata('msg', '<div class="alert alert-success"> <a href="#" class="close" data-dismiss="alert">&times;</a> <strong>Listo!</strong> Logo de la empresa cargado con éxito. </div>');
		}
		
		$redirect = base_url('employer/dashboard');
		
		if ($this->agent->is_referral()) {
			$redirect = $this->agent->referrer();	
		}
		
		redirect($redirect);
	}

	public function delete_company_logo() 
	{	
		if (!$this->session->userdata('user_id')) {
			echo 'Your session has been expired, please re-login first.';
			exit;	
		}

		$obj_row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

		if ($obj_row->is_admin != 'yes') {
			return;
		}

		$this->Company->update_company($obj_row->company_ID, ['company_logo' => '']);

		//$real_path = realpath(APPPATH . '../public/uploads/employer/');
		//@unlink($real_path . '/' . $obj_row->company_logo);	
		//@unlink($real_path . '/thumb/' . $obj_row->company_logo);
		
		echo "done";
	}
}
