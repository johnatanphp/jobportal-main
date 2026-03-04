<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Send_study_certificates extends CI_Controller
{	
	public function __construct()
    {
        parent::__construct();

        // if (!candidate_is_process_contracting()) {
        //     //show_404();
        // }
        
		//load model
        $this->load->model('Recruitment_candidate');

		//Load libraries
        $this->ads = $this->Ad->get_ads();
    }

    public function index()
    {
        $data['title'] = 'Certificados de estudios - ' . SITE_NAME;
        $data['ads_row'] = $this->ads;

        $job_seeker_id = $this->input->get('seeker_id');
        $result_studies = $this->Job_seeker->get_qualification_by_jobseeker_id($job_seeker_id);        
        $data['result_studies'] = $result_studies;     
        $data['seeker_id'] = $job_seeker_id;         

        $this->load->view('employer/recruitment_requested_docs/send_study_certificates/index', $data);
    }

    public function upload()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        $seeker_id = $this->input->post('seeker_id');
        $study_id = $this->input->post('study_id');

        $file_previus = $this->Jobseeker_academic->find(
            $study_id
        );
        
        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';    
            $path = 'candidate/study_certificates/' .  md5(uniqid($study_id . $seeker_id, true)) . $file_ext;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - No se pudo subir el documento'])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Jobseeker_academic->save_certificate(
            $study_id, 
            $seeker_id, 
            $path
        );

        if (!$trans_status) {
            exit(json_encode(
                ['error' => 'Error al guardar el documento']
            ));
        }

        if ($file_previus && 
            $file_previus->attached_certificate &&
            $this->storage_lib->has($file_previus->attached_certificate)) {
            $this->storage_lib->delete($file_previus->attached_certificate);
        }

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }
}
