<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_documents extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Recruitment_attached_document');
        $this->load->model('Exam_request_result_type');
        $this->load->model('Recruitment_document_type');
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_tray_candidate');

        //Load libraries
        $this->load->library('Storage_lib');
    }

    public function list($process_id, $candidate_id = 0, $document_key = null)
    {
        $tray_candidate = $this->Recruitment_tray_candidate->find([
            'process_id' => $process_id, 
            'seeker_id' => $candidate_id
        ]);

        $data['process_id'] = $process_id;
		$data['candidate_id'] = $candidate_id;
        $data['tray_candidate'] = $tray_candidate;
		$data['document'] = $this->Recruitment_document_type->find_by_key($document_key);
		$data['attached_files'] = $this->Recruitment_attached_document->get_attachments(
			$candidate_id,
			$document_key
		);

		$this->load->view('employer/recruitment_tray/recruitment_documents/common/recruitment_document_content', $data);
    }

    public function upload()
    {
        $request_data = $this->input->post();

        $this->form_validation->set_rules('process_id', 'Proceso Id', 'trim|required');
        $this->form_validation->set_rules('candidate_id', 'Candidato Id', 'trim|required');
        $this->form_validation->set_rules('document', 'Documento', 'trim|required');

        if ($request_data['document'] == 'other_documents') {
            $this->form_validation->set_rules('document_title', 'Titulo Documento', 'trim|required|max_length[45]');
        }
        
        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Hay datos incorrectos'
            ]);
            return;
        }

        $process_id = $request_data['process_id']; 
        $seeker_id = $request_data['candidate_id'];
        $document_key = $request_data['document'];
        $document_title = $request_data['document_title'] ?? null;
        $loaded_by = $this->session->userdata('user_id');

        $process = $this->Recruitment_process->find($process_id);

        if (!$process) {
            echo json_encode([
                'status' => false,
                'message' => 'Proceso es incorrecto'
            ]);
            return;
        }

        $job_id = $process->job_ID;

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        // if ($obj_employer->company_ID != $process->company_id) {
        //     echo json_encode([
        //         'status' => false,
        //         'message' => 'Proceso no pertenece a la compañia del usuario en sesión'
        //     ]);
        //     return;
        // }

        $file = isset($_FILES['file']) ? $_FILES['file'] : null;

        //dd($_POST);

        if (is_null($file)) {
            exit(json_encode([
                'status' => false,
                'message' => '¡Debe cargar un documento!'
            ]));
        }

        $document_row = $this->Recruitment_document_type->find_by_key($document_key);

        if (!$document_row) {
            exit(json_encode([
                'status' => false,
                'message' => '¡Documento key es invalido!'
            ]));
        }

        $mime_types_allowed = get_mime_types(explode(',', $document_row->allowed_files));

        if (!in_array($file['type'], $mime_types_allowed)) {
            exit(json_encode([
                'status' => false,
                'message' => 'Tipo de archivo no es válido'
            ]));
        }
        
        if ($file['size'] > ($document_row->max_size * 1048576)) {
            exit(json_encode([
                'status' => false,
                'message' => 'El archivo a subir debe ser menor o igual a 4MB'
            ]));
        }
       
        try {
        
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid('rys-documents-' . $process_id . '-' . $document_key . '-' . $seeker_id, true)) . $file_ext;  
            
            $path = 'employer/recruitment_selection_documents/' . $file_name;

            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                'status' => false,
                'message' => 'No se pudo subir el documento'
            ]));
        }

        $this->storage_lib->setVisibility($path, 'public');

        $data = [
            'name' => $file['name'],
            'file_source' => $path,
            'created_at' => date('Y-m-d H:i:s'),
            'key' => $document_key,
            'document_title' => $document_key == 'other_documents' ? $document_title : null,
            'process_id' => $process_id,
            'job_ID' => $job_id,
            'seeker_ID' => $seeker_id,
            'loaded_by' => $loaded_by
        ];

        $this->db->insert('tbl_recruitment_attached_documents', $data); 
        $file_id = $this->db->insert_id();

        if (!$file_id) {
            exit(json_encode([
                'status' => false,
                'message' => 'Error al guardar el documento'
            ]));
        }
     
        $file_url = file_url($path);

        echo json_encode([
            'status' => true,
            'message' => 'Ok',
            'data' => [
                'original_file_name' => $file['name'],
                'url_file' => $file_url,
                'file_id' => $file_id
            ]
        ]);
    }

    public function delete()
    {
        $file_id = $this->input->post('file_id');

        $document = $this->Recruitment_attached_document->get_by_id($file_id);

        if (!$document) {
            echo json_encode([
                'status' => false,
                'message' => 'Documento no ha sido encontrado'
            ]);
            return;
        }

        $trans_status = $this->Recruitment_attached_document->remove(
            $file_id
        );

        if (!$trans_status) {
            echo json_encode([
                'status' => false,
                'message' => 'Documento no ha sido eliminado'
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Documento ha sido eliminado'
        ]);
    }
}
