<?php
require_once ("App_console.php");

class Tray_candidates_process_hiring extends App_console  
{
    public function __construct()
    {
        parent::__construct();
        
        //Load models
        $this->load->model('Recruitment_process');
    }

    public function run()
    {
        $this->load->library('WS_overall/WS_overall_recruitment_seeker_hire_lib');

        $this->db->select([
            'tray_candidates.process_id',
            'tray_candidates.seeker_id',
            'rc_contracts.contract_start_date',
            'rc_contracts.contract_end_date',
            'rc_contracts.no_cia'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_recruitment_contracts rc_contracts', 'rc_contracts.process_id=rc_process.id AND rc_contracts.seeker_id=tray_candidates.seeker_id');
        $this->db->where('tray_candidates.status_id', 4);
        $this->db->limit(5);
        $result = $this->db->get()->result();
   
        foreach ($result as $tray) {
            //dd($tray);
            $response = $this->ws_overall_recruitment_seeker_hire_lib->exec([
                'process_id' => $tray->process_id,
                'seeker_id' => $tray->seeker_id,
                'contract_start_date' => $tray->contract_start_date,
                'contract_end_date' => $tray->contract_end_date,
                'no_cia' => $tray->no_cia
            ]);
             
            $process = $this->Recruitment_process->find($tray->process_id);

            // Enviar Notificaciones
            if (isset($response['status']) && $response['status'] == true && $process->request_id) {
              $this->send_notifications($process->request_id, $tray->seeker_id);
            }
        }
    }

    private function send_notifications($request_id, $seeker_id)
    {
        //Notificar al postulante por correo
        $this->load->library('Email/Recruitment_process/Recruitment_process_candidate_contracted_email');
        $this->recruitment_process_candidate_contracted_email->send($request_id, $seeker_id);
        
        //Notificar al postulante por WhatsApp
        $this->load->library('Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_contracted_lib');
        $this->whatsapp_recruitment_proccess_candidate_contracted_lib->send($request_id, $seeker_id);
        
        //Notificar al reclutador por correo
        $this->load->library('Email/Recruitment_process/Recruitment_process_candidate_to_payroll_system_email');
        $this->recruitment_process_candidate_to_payroll_system_email->send($request_id, $seeker_id);
    }
}
