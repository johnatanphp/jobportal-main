<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Contract_documents extends CI_Controller 
{	
	public function __construct()
    {
        parent::__construct();

        $this->load->model('Recruitment_process_contract_document');
        $this->load->model('Recruitment_contract_document');
        $this->load->model('Recruitment_contract_document_type');
        $this->load->model('Recruitment_candidate');
        
        // if (!candidate_is_process_contracting()) {
        // //    show_404();
        // }
        
        $this->ads = $this->Ad->get_ads();
    }
	public function index()
	{
        $document_id = $this->input->get('document_id');
		$seeker_id = $this->input->get('seeker_id');

        $document = $this->Recruitment_contract_document_type->find($document_id);

        if (!$document) {
            show_404();
        }

        if ($document->option_type_id != 1) {
            show_404();
        }

		$data['ads_row'] = $this->ads;
		$data['title'] = $document->name . ' - ' . SITE_NAME;
        
        $data['document'] = $document;
        $data['seeker_id'] = $seeker_id;
		$data['file'] = $this->db->get_where('tbl_recruitment_contract_documents', [
            'seeker_id' => $seeker_id,
            'document_id' => $document->id
        ])
        ->row();
    
		$this->load->view('employer/recruitment_requested_docs/contract_documents/index', $data);
	}

	public function upload()
	{
        $document_id = $this->input->post('document_id');
        $seeker_id = $this->input->post('seeker_id');
    
        $document = $this->Recruitment_contract_document_type->find($document_id);

        if (!$document) {
            show_404();
        }

        $rs_candidate = $this->Recruitment_candidate->get_process_to_hiring_by_seeker_id($seeker_id);

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

        $mime_types_allowed = get_mime_types(explode(',', $document->allowed_files));

        if (!in_array($file['type'], $mime_types_allowed)) {
             exit(json_encode([
                'success' => false,
                'message' => 'Tipo de archivo no es válido'
            ]));
        }

        if ($file['size'] > ($document->max_size * 1048576)) {
            exit(json_encode([
                'success' => false,
                'message' => 'El archivo a subir debe ser menor o igual a ' . ($document->max_size) . 'MB'
            ]));
        }

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : '';
            $file_name = md5(uniqid($document->id . '-' . $seeker_id, true)) . $file_ext;     
            $path = 'contract_documents/' . $file_name;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                'success' => false,
                'message' => 'No se pudo subir el documento'
            ]));
        }

        $this->storage_lib->setVisibility($path, 'public');

        $this->db->where('seeker_id', $seeker_id);
        $this->db->where('document_id', $document->id);
        $this->db->delete('tbl_recruitment_contract_documents'); 

        $data = [
            'name' => $file['name'],
            'file_path' => $path,
            'created_at' => date('Y-m-d H:i:s'),
            'document_id' => $document->id,
            'job_id' => $rs_candidate->job_ID,
            'seeker_id' => $seeker_id
         ];
 
         $this->db->insert('tbl_recruitment_contract_documents', $data); 
         $file_id = $this->db->insert_id();
 
         if (!$file_id) {
             exit(json_encode([
                    'success' => false,
                    'message' => 'Error al guardar el documento'
                ])
             );
         }
      
         echo json_encode([
            'success' => true,
            'message' => 'Documento cargado',
            'file_url' => file_url($path)
        ]);
	}
}
