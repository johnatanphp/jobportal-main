<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Autenticar extends REST_Controller
{
    public function index_post()
    {
        $this->load->library(
            'App/Auth/Auth_job_seeker_login', 
            null, 
            'Auth_job_seeker_login'
        );

        $email = $this->post('email');
        $password = $this->post('contrasena');

        $seeker_login = $this->Auth_job_seeker_login->login($email, $password);

        if ($seeker_login['success'] === true) {

            $jobseeker = $this->Job_seeker->find($seeker_login['data']['seeker_id']);

            $data = [
                'id' => trim($jobseeker->ID),
                'nombres' => trim($jobseeker->first_name),
                'apellidos' => trim(trim($jobseeker->paternal_last_name) . ' ' . trim($jobseeker->maternal_last_name)),
                'email' => trim($jobseeker->email),
                'pais' => country_text(trim($jobseeker->country)),
                'fecha_nacimiento' => trim($jobseeker->dob),
                'sexo' => gender_text($jobseeker->gender),
                'ubicacion' => trim($jobseeker->city),
                'telefono_movil' => trim($jobseeker->mobile),
                'telefono_residencial' => trim((string)$jobseeker->home_phone),
                'doc_iden_tipo' => document_type_text($jobseeker->document_type),
                'doc_iden_numero' => trim($jobseeker->document_number),
                'avatar_url' => img_pic_candidate($jobseeker->photo)
            ];
            
            $this->response([
                'status' => true,
                'message' => 'Bienvenido ' . trim($jobseeker->first_name),
                'data' => [
                    'postulante' => $data
                ]
            ], 200);

            return;
        }

        $this->response(
            array(
                'status' => false,
                'message' => $seeker_login['message'],
                'data' => []  
        ), 200);
    }
}
