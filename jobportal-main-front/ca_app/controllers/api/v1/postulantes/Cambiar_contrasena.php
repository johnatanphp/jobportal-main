<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Cambiar_contrasena extends REST_Controller
{
    public function index_post()
    {   
        $password = trim($this->post('contrasena'));
        $seeker_id = trim($this->post('postulante_id'));

        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            $this->response([
                'status' => false,
                'message' => 'El postulante ID no se ha encontrado',
                'data' => []
            ], 200);
            return;
        }

        if (strlen($password) <= 7 || 
            strlen($password) >= 13) {
            $this->response([
                'status' => false,
                'message' => 'La contraseña debe contener como mínimo 8 y máximo 12 caracteres',
                'data' => []
            ], 200);
            return;
        }

        $user_data = [
            'password' => do_hashing($password),
            'login_attempts' => 0,
            'blocked_at' => null
        ];
        
        $this->db->where('ID', $seeker_id);
        $this->db->update('tbl_job_seekers', $user_data);

        $this->response([
            'status' => true,
            'message' => 'La contraseña ha sido cambiada con éxito.',
            'data' => []
        ], 200);
    }
}
