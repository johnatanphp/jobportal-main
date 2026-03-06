<?php
class Notify_candidate_process_move_job
{
    private $params;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct($params = [])
    {
        $this->params = $params;
        $this->load->library('Email/Recruitment_process/Recruitment_process_candidate_move_stage_email');
        $this->load->library('Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_move_lib');
    }

    public function handle()
    {
        $request_id = $this->params['request_id'] ?? null;

        if (!$request_id) {
            return;
        }

        $candidate_id = $this->params['candidate_id'] ?? null;

        if (!$candidate_id) {
            return;
        }

        $notify_by_mail = $this->params['notify_by_mail'] ?? 0;

        // Notificar candidato por correo si el empleo proviene de una solicitud
        if ($notify_by_mail == 1) {
            $this->recruitment_process_candidate_move_stage_email->send($request_id, $candidate_id);
        }
        
        $notify_by_whatsapp = $this->params['notify_by_whatsapp'] ?? 0;

        // Notificar candidato por WhatsApp si el empleo proviene de una solicitud 
        // y esta el check encendido
        if ($notify_by_whatsapp == 1) {
            $this->whatsapp_recruitment_proccess_candidate_move_lib->send($request_id, $candidate_id);
        }
    }
}
