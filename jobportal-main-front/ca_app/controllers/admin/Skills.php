<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Skills extends CI_Controller {

	public function __construct()
    {
        parent::__construct();

        //Load models
        $this->load->model('Job_charge');
    }

	public function index()
	{
		$data['title'] = SITE_NAME . ': Manage Skills';
		$data['msg'] = '';

		// Capturar los filtros enviados desde el formulario o la URL
		$filters = [
			'query' => trim((string)$this->input->get('query', true)), // Filtro de búsqueda
		];

		// Configuración de la paginación
		$total_rows = $this->Skill->count_skills($filters); // Ajusta esta función en el modelo para aplicar filtros
		$config = pagination_configuration(base_url("admin/skills"), $total_rows, 20, 3, 5, true); // 20 registros por página

		// Inicialización de la paginación
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$start = $page * $config['per_page']; // Calcular el offset correctamente

		// Obtener los registros paginados desde el modelo con los filtros
		$obj_result = $this->Skill->search_skills($config['per_page'], $start, $filters); // Ajusta esta función en el modelo para aplicar filtros

		// Procesar los resultados y contar las habilidades asociadas
		$obj_seeker_keywords = $this->Jobseeker_skills->get_all_grouped_skills();
		$already_array = [];
		$array_with_counter = [];

		// Recorrer los resultados paginados y agregar contadores
		foreach ($obj_result as $key => $row_already) {
			$total_skill_counter = $this->Jobseeker_skills->count_jobseeker_skills_by_skill_name($row_already->skill_name);
			$array_with_counter[$key] = [
				'ID' => $row_already->ID,
				'skill_name' => $row_already->skill_name,
				'industry_ID' => $row_already->industry_ID,
				'counter' => $total_skill_counter
			];
			array_push($already_array, $row_already->skill_name);
		}

		// Procesar las palabras clave agrupadas y eliminar duplicados
		$skills_array = [];
		foreach ($obj_seeker_keywords as $row_key) {
			if ($row_key) {
				$single_skill = strip_tags(trim($row_key->skill_name));
				if ($single_skill != '') {
					if (!in_array($single_skill, $already_array)) {
						$skills_array[$single_skill] = $row_key->total_times;
					}
				}
			}
		}

		// Ordenar las habilidades por la cantidad de veces que aparecen
		arsort($skills_array);

		// Preparar los datos para la vista
		$data['result'] = array_to_object($array_with_counter); // Convertir array a objeto para usar en la vista
		$data['keywords_result'] = $skills_array; // Palabras clave agrupadas
		$data['total_skills'] = count($skills_array); // Contar el total de habilidades
		$data['filters'] = $filters; // Pasar los filtros a la vista
		$data["links"] = $this->pagination->create_links(); // Generar los enlaces de paginación
		$data['job_charges'] = $this->Job_charge->all(['sts' => 'active', 'country_id' => 56]);

		// Cargar la vista con los datos preparados
		$this->load->view('admin/skills_view', $data);
	}
	
	public function get_skill_by_id($id=''){
		if($id!=''){
			$row = $this->Skill->get_record_by_id($id);
			$json_data = json_encode($row);
			echo $json_data;
			exit;
		}
		return;
	}
	
	public function view($id=''){
		if($id!=''){
			$row = $this->Skill->get_record_by_id($id);
			echo '<title>Email Template Preview</title>
			';
			echo $row['content'];
			exit;
		}
		return;
	}
	
	public function update(){
		
		$id = $this->input->post('sid');
		if($id==''){
			redirect(base_url().'admin/skills','');
			exit;
		}
		
		$data['title'] = SITE_NAME.': Edit Skill';
		$data['msg'] = '';
		$skill_favorite = $this->input->post('skill_favorite') ? 1 : 0;
		
		$this->form_validation->set_rules('skill_name', 'Skill', 'trim|required');
		$this->form_validation->set_rules('blend_to', 'blend_to', 'trim');
		$this->form_validation->set_error_delimiters('<span class="err" style="padding-left:2px;">', '</span>');
		
		if ($this->form_validation->run() === FALSE) {
			$this->index();
			return;
		}

		$row_actual = $this->Skill->get_record_by_id($id);
		$occupational_group = $this->input->post('occupational_group');

		$this->Skill->update_skill_favorite($row_actual['skill_name'], $skill_favorite, $occupational_group);
		
		if($this->input->post('blend_to')!=''){
			$this->Jobseeker_skills->update_skill_frequency($row_actual['skill_name'], $this->input->post('blend_to'), $skill_favorite);
			$this->Skill->delete($id);
			$this->session->set_flashdata('update_action', true);
			redirect(base_url('admin/skills'));
			return;
		}

		$data_array = array(
							'skill_name' => $this->input->post('skill_name')
		);
		$this->Skill->update($id, $data_array);
		$this->Jobseeker_skills->update_skill_frequency($row_actual['skill_name'], $this->input->post('skill_name'), $skill_favorite);
		$this->session->set_flashdata('update_action', true);
		redirect(base_url('admin/skills'));
		return;
	}
	
	public function delete($id=''){
		
		if($id==''){
			echo 'error';
			exit;
		}
		
		$this->Skill->delete($id);
		echo 'done';
		exit;
	}
	
	public function update_skill_frequency(){
		$this->form_validation->set_rules('original_skill', 'actuall skill', 'trim|required');
		$this->form_validation->set_rules('new_skill', 'new skill', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
			echo "actuall or new skill value is missing.";
			exit;
		}
		$original_skill = $this->input->post('original_skill');
		$new_skill = $this->input->post('new_skill');
		$this->Jobseeker_skills->update_skill_frequency($original_skill, $new_skill);
		echo 'done';
		
	}
	
	public function add_skill_frequency(){
		$this->form_validation->set_rules('new_skill', 'new skill', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
			echo "new skill value is missing.";
			exit;
		}
		$this->Skill->add(array('skill_name'=>$this->input->post('new_skill')));
		
	}
	
	public function add(){
		$this->form_validation->set_rules('add_skill_name', 'new skill', 'trim|required');
		if ($this->form_validation->run() === FALSE) {
			echo "new skill value is missing.";
			exit;
		}
		$this->Skill->add(array('skill_name'=>$this->input->post('add_skill_name')));
		redirect(base_url('admin/skills'));
		return;
	}
	
	public function load_ajax() {
		$end = $this->input->post('end');
		$start = $this->input->post('start');
	
		// Obtener los registros paginados de habilidades agrupadas
		$obj_seeker_keywords = $this->Jobseeker_skills->get_all_grouped_skills_by_limit($end, $start);
	
		// Generar la salida HTML
		$itt = '';
		$i = $start;
		foreach ($obj_seeker_keywords as $row_key) {
			$single_skill = strip_tags(trim($row_key->skill_name));
			if ($single_skill != '') {
				$i++;
				$skill_with_quotes = "'" . $single_skill . "'";
				$itt .= '<tr id="r_' . $i . '">
					<td width="200">' . $this->get_skills_dropdown($i) . '</td>
					<td width="200">' . $single_skill . ' (' . $row_key->total_times . ')</td>
					<td width="100">
						<a href="javascript:;" onClick="update_skill_frequency(' . $i . ',' . $skill_with_quotes . ');" class="btn btn-primary btn-xs">Update</a>
						&nbsp;
						<a href="javascript:;" onClick="add_skill_frequency(' . $i . ',' . $skill_with_quotes . ');" class="btn btn-primary btn-xs">Add</a>
					</td>
				</tr>';
			}
		}
	
		echo $itt;
	}
	
	public function get_skills_dropdown($i){
		$obj_result = $this->Skill->get_all_records();
		$options = '';
		$pre = '<select name="main_skills_'.$i.'" id="main_skills_'.$i.'">
                  <option value="" selected>-  - Skills - -</option>';
				  
				  foreach($obj_result as $row){
                  	$options.= '<option value="'.$row->skill_name.'">'.$row->skill_name.'</option>';
				  }
               $post ='</select>';
	    return $pre.$options.$post;
	}
}