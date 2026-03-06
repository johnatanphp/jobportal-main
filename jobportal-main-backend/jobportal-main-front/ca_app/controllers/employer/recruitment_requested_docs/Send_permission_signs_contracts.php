<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Send_permission_signs_contracts extends CI_Controller
{	
	public function __construct()
    {
        parent::__construct();

        // if (!candidate_is_process_contracting()) {
        //     //show_404();
        // }
        
		//load model
        $this->load->model('Recruitment_candidate');
        $this->load->model('Seeker_permission_signs_contract');

		//Load libraries
        $this->ads = $this->Ad->get_ads();
    }

	public function upload()
	{
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
            'image/jpeg',
            'image/png',
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

        $file_previus = $this->Seeker_permission_signs_contract->find(['seeker_id' => $seeker_id]);

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid($seeker_id, true)) . $file_ext;  

            $path = 'candidate/permission_signs_contracts/' . $file_name;
            
            $path = $this->storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                    'success' => false,
                    'message' => 'Error al cargar el documento - No se pudo subir la imagen'
                ])
            );
        }

        $this->storage_lib->setVisibility($path, 'public');

        $trans_status = $this->Seeker_permission_signs_contract->create_or_update(
            ['seeker_id' => $seeker_id], 
            ['seeker_id' => $seeker_id, 'file_path' => $path]
        );

        if (!$trans_status) {
            exit(json_encode([
                    'success' => false,
                    'message' => 'Error al guardar la imagen'
                ])
            );
        }

        if ($file_previus && 
            $this->storage_lib->has($file_previus->file_path)) {
            $this->storage_lib->delete($file_previus->file_path);
        }
     
        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'message' => 'Documento cargado',
            'url_file' => $file_url
        ]);
	}
}
