<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manage extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();

		$this->load->library('storage_lib', null, 'Storage_lib');
    }
	
	public function list($seeker_id = 0)
	{
		$row = $this->Job_seeker->find($seeker_id);

		if (!$row) {
			show_404();
		}
		
		//Resumes
		$result_resume = $this->Resume->get_records_by_seeker_id($row->ID, 5, 0);
		$data['result_resume'] = $result_resume;
		$data['seeker'] = $row;
	
		$this->load->view('embed/jobseeker/cv/manager',$data);
	}

	public function upload($seeker_id = 0)
	{
		$seeker = $this->Job_seeker->find($seeker_id);

		$file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {

			echo json_encode([
				'success' => false,
				'message' => 'Error al cargar el documento - Documento no cargado'
			]);

			return;
        }

        $allowed = [
			'image/jpeg',
			'image/png',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text'
        ];

        if (!in_array($file['type'], $allowed)) {

			echo json_encode([
				'success' => false,
				'message' => 'Tipo de archivo no es válido, solo formatos (.pdf, .doc y .docx).'
			]);

			return;
        }

        if ($file['size'] > (4 * 1048576)) {

			echo json_encode([
				'success' => false,
				'message' => 'El archivo a subir debe ser menor o igual a 4MB'
			]);

			return;
        }

        try {
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/resumes/' . md5(uniqid($seeker_id, true)) . $file_ext;
            
            $path = $this->Storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {

			echo json_encode([
				'success' => false,
				'message' => 'No se pudo subir el documento a S3'
			]);

			return;
        }

        $this->Storage_lib->setVisibility($path, 'public');

		$resume_array = [
			'seeker_ID' => $seeker_id,
			'file_name' => $path,
			'dated' => date("Y-m-d H:i:s"),
			'is_uploaded_resume' => 'yes'			
		];

		$this->Resume->add($resume_array);	
    
		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);
			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Carga CV',
				'detalle' => 'Carga CV. <a href="'. file_url($path) . '">Ver</a>',
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

		echo json_encode([
			'success' => true,
			'message' => 'CV cargado con éxito.'
		]);
	}
	
	public function delete()
	{
		$this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
		
		if ($this->form_validation->run() === FALSE) {
			echo strip_tags(validation_errors());
			exit;
		}

		$resume = $this->Resume->get_records_by_id($this->input->post('id'));

		if (!$resume) {
			show_404();
			exit;	
		}
		
		$seeker = $this->Job_seeker->find($resume->seeker_ID);

		$this->Resume->delete_by_id_seeker_id($this->input->post('id'), $resume->seeker_ID);

		if ($this->Storage_lib->has($resume->file_name)) {
       		$this->Storage_lib->delete($resume->file_name);
       	}

		try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);
			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Eliminación CV',
				'detalle' => 'Eliminación CV a las ' . date('d/m/Y \a \l\a\s H:i'),
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

       	echo "done";
	}

	public function download($resume_id = 0)
	{	
		$resume = $this->Resume->get_records_by_id($resume_id);
		
		if (!$resume) {
			show_404();
			exit;	
		}

		if (!$this->Storage_lib->has($resume->file_name)) {
			echo "CV no encontrado";
			exit;
		}
		
		$file_url = file_url($resume->file_name);
		
		$seeker = $this->Job_seeker->find($resume->seeker_ID);
		$file_name = 'cv-' . make_slug($seeker->first_name . ' ' . $seeker->last_name) . '.' . file_ext($resume->file_name);

		$data = file_get_contents($file_url);

		force_download($file_name, $data);

		exit;
	}
}
