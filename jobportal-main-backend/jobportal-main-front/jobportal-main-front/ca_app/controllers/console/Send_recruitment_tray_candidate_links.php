<?php
require_once ("App_console.php");

class Send_recruitment_tray_candidate_links extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $this->db->select([
            'tray_candidates.id AS id',
            'tray_candidates.process_id',
            'rc_process.job_ID AS job_id',
            'tray_candidates.created_by AS candidate_created_by',
            'candidates.ID AS candidate_id',
            'candidates.first_name AS candidate_first_name',
            'candidates.last_name AS candidate_last_name',
            'candidates.mobile AS candidate_mobile',
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_job_seekers candidates', 'tray_candidates.seeker_id=candidates.ID');
        $this->db->where('tray_candidates.link_sent', 0);
        $this->db->where('rc_process.tray_type_id', 3); //Bandeja contratacion sin solicitudes
        $this->db->limit(1);
        
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
            $this->send_whatsapp_notification($row);
        }
    }

    private function send_whatsapp_notification($row)
    {
        $this->load->model('Recruitment_document_request');

        $candidate_id = $row->candidate_id;
        $job_id = $row->job_id;
        $created_by = $row->candidate_created_by;

        $link_url = $this->Recruitment_document_request->create_link($candidate_id, $job_id, $created_by);

        if (!$link_url) {
            return false;
        }
        
        $this->load->library(
            'Whatsapp/Whatsapp_jobseeker_send_request_documents_lib', 
            null, 
            'Whatsapp_jobseeker_send_request_documents_lib'
        );
        
        $url_params = [
            'process_id' => $row->process_id,
            'candidate_id' => $candidate_id
        ];
        
        $query_string = http_build_query($url_params);
        $link_url = $link_url . '&' . $query_string;
        
        $mobile = format_mobile((string)$row->candidate_mobile);

        if (!$mobile) {
            return false;
        }

        $notification_whatsapp = $this->Whatsapp_jobseeker_send_request_documents_lib->send([
            'mobile' => $mobile,
            'full_name' => $row->candidate_first_name . ' ' . $row->candidate_last_name,
            'position' => 'Puesto laboral',
            'linkUploadDocument' => $link_url,
            'ccosto' => null
        ]);

        if (!$notification_whatsapp) {
            return false;
        }

        //Actualizar en bandeja envio de link
        $this->db->where('id', $row->id);
        $this->db->update('tbl_recruitment_tray_candidates', [
            'link_sent' => 1
        ]);
        return true;
    }
}
