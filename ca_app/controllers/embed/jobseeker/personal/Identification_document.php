<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Identification_document extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();

        //Load model
        $this->load->model('Requested_document');

        //Load libraries
		$this->load->library('storage_lib', null, 'Storage_lib');
    }

    public function list($seeker_id)
    {
        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            show_404();
        }

        $data['seeker'] = $seeker;
		$data['documents'] = $this->Requested_document->get_identity_documents($seeker_id);
		$this->load->view('embed/jobseeker/personal/identification_documents', $data);
    }

    public function upload()
	{
        $doc_type = $this->input->get('type', 1);
        $seeker_id = $this->input->get('seeker_id');

        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento']
            ));
        }

        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
            'application/pdf'
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

        $file_previus = $this->Requested_document->get_identification_document(
            $seeker_id, 
            $doc_type
        );

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid($doc_type . '-' . $seeker_id, true)) . $file_ext;  

            $path = 'candidate/identification_documents/' . $file_name;
            
            $path = $this->Storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                    'error' => 'Error al cargar el documento - No se pudo subir la imagen'
                ])
            );
        }

        $this->Storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Requested_document->save_identification_document(
            $seeker_id,
            $doc_type,
            $path
        );

        if (!$trans_status) {
            exit(json_encode([
                    'error' => 'Error al guardar la imagen'
                ])
            );
        }

        if ($file_previus && 
            $this->Storage_lib->has($file_previus->path)) {
            $this->Storage_lib->delete($file_previus->path);
        }
     
        $file_url = file_url($path);

        try {
			$this->load->library(
				'Hrm_api/Hrm_api_notificacion_cambio_legajo_lib', 
				null, 
				'Hrm_api_notificacion_cambio_legajo_lib'
			);

            $doc = $this->db->get_where('tbl_identity_document_types', [
                'id' => $doc_type
            ])->row();

			$this->Hrm_api_notificacion_cambio_legajo_lib->notificar([
				'titulo' => 'Carga Documento de identidad',
				'detalle' => 'Carga ' . $doc->name . ' <a href="'. $file_url . '">Ver</a>',
				'doc_iden_num' => $seeker->document_number
			]);

		} catch (Exception $e) {}

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
	}
}