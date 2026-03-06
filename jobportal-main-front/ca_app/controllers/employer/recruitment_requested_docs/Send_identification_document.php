<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Send_identification_document extends CI_Controller
{	
	public function __construct()
    {
        parent::__construct();

        // if (!candidate_is_process_contracting()) {
        //     //show_404();
        // }
        
		//load model
		$this->load->model('Requested_document');
		$this->load->model('Jobseeker_required_document');
        $this->load->model('Jobseeker_form_rtps');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Rys_form_seeker');

        $this->load->model('Seeker_domicile_affidavit');
        $this->load->model('Seeker_declaration_5th_category');
        $this->load->model('Seeker_immigration_record');
        $this->load->model('Seeker_residency_verification');
        $this->load->model('Seeker_permission_signs_contract');

        $this->load->model('Recruitment_process_contract_document');
        $this->load->model('Recruitment_contract_document');

		//Load libraries
        $this->ads = $this->Ad->get_ads();
    }

	public function index()
	{
		$job_seeker_id = $this->input->get('seeker_id');
        $data['seeker'] = $this->Job_seeker->find($job_seeker_id);
		
        $documents = [1]; //DNI
        if (($data['seeker']->document_type != '1')) {
            $documents = [4, 7, 12, 13];
        }
        
        $data['documents'] = $this->Requested_document->get_identity_documents_by_type($job_seeker_id, $documents);
        $data['sign_contract'] = $this->Seeker_permission_signs_contract->find(['seeker_id' => $job_seeker_id]);

		$this->load->view('employer/recruitment_requested_docs/send_identification_document/index', $data);
	}

	public function upload()
	{
        $doc_type = $this->input->post('doc_type_id');
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode([
                'success' => false,
                'message' => 'Documento no cargado'
            ]));
        }

        if ($file['error'] > 0) {
            exit(json_encode([
                'success' => false,
                'message' => 'Documento no cargado - error ' .  $file['error']
            ]));
        }
        
        $allowed = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode([
                'success' => false,
                'message' => 'Tipo de archivo no es válido'
            ]));
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode([
                'success' => false,
                'message' => 'El archivo a subir debe ser menor o igual a 4MB'
            ]));
        }

        $seeker_id = $this->input->post('seeker_id');

        $file_previus = $this->Requested_document->get_identification_document(
            $seeker_id, 
            $doc_type
        );

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid($doc_type . '-' . $seeker_id, true)) . $file_ext;  

            $path = 'candidate/identification_documents/' . $file_name;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                'success' => false,
                'message' => 'No se pudo subir la imagen'
            ]));
        }

        $this->storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Requested_document->save_identification_document(
            $seeker_id,
            $doc_type,
            $path
        );

        if (!$trans_status) {
            exit(json_encode([
                'success' => false,
                'message' => 'Error al guardar la imagen'
            ]));
        }

        if ($file_previus && 
            $this->storage_lib->has($file_previus->path)) {
            $this->storage_lib->delete($file_previus->path);
        }
     
        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'message' => 'ok',
            'url_file' => $file_url
        ]);
	}
}
