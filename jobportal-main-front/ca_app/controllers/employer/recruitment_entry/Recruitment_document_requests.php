<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_document_requests extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        //load models
        $this->load->model('Recruitment_document_request');
        $this->load->model('Staff_request');
    }

    public function copy_link()
    {
        $seeker_id = $this->input->post('seeker_id');
        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            echo json_encode([
                'success' => false,
                'message' => 'Postulante ID no existe',
            ]);
            return;
        }

        $job_id = $this->input->post('job_id');
        
        $link_url = $this->Recruitment_document_request->create_link($seeker_id, $job_id);

        if (!$link_url) {
            echo json_encode([
                'success' => false,
                'message' => 'Link de ingreso no se pudo generar',
            ]);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Link ha sido creado',
            'link_url' => $link_url
        ]);
    }

    public function  send_link_by_whatsapp()
    {
        $seeker_id = $this->input->post('seeker_id');
        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            echo json_encode([
                'success' => false,
                'message' => 'Postulante ID no existe',
            ]);
            return;
        }

        $mobile = format_mobile($seeker->mobile);

        if (!$mobile) {
            echo json_encode([
                'success' => false,
                'message' => 'No se puede enviar el mensaje, N° Celular no es válido',
            ]);
            return;
        }

        $job_id = $this->input->post('job_id');
        $job = $this->Posted_job->find($job_id);

        if (!$job) {
            echo json_encode([
                'success' => false,
                'message' => 'Empleo no es válido',
            ]);
            return;
        }

        if (!$job->request_ID) {
            echo json_encode([
                'success' => false,
                'message' => 'Este proceso no tiene una solicitud asociada',
            ]);
            return;
        }
        
        $this->load->library(
            'Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_start_hiring_lib'
        );

        $whatsapp_link_sent = $this->whatsapp_recruitment_proccess_candidate_start_hiring_lib->send($job->request_ID, $seeker_id);
        
        if ($whatsapp_link_sent) {
            echo json_encode([
                'success' => true,
                'message' => 'Link ha sido enviado'
            ]);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'No se pudo enviar el mensaje'
        ]);
    }
}
