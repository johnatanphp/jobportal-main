<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_notifications extends CI_Controller
{
    public function __construct()
	{
        parent::__construct();

        $this->load->model('Staff_request');
    }

    public function send()
    {
        $notification_type = $this->input->post('notification_type');

        if ($notification_type == 'document_request') {
            $data_response = $this->send_notification_document_request();
            echo json_encode($data_response);
            return;
        }

        if ($notification_type == 'gratitude') {
            $data_response = $this->send_notification_gratitude();
            echo json_encode($data_response);
            return;
        }

        echo json_encode([
            'success' => false,
            'message' => 'El tipo de notificación seleccionada es incorrecta'
        ]);
    }

    private function send_notification_document_request()
    {
        $this->load->model('Recruitment_document_request');
        $this->load->model('Recruitment_candidate');
        $this->load->model('Recruitment_process');

        $process_id = $this->input->post('process_id');
        $process = $this->Recruitment_process->find($process_id);
        
        if (!$process) {
            return [
                'success' => false,
                'message' => 'Proceso de reclutamiento no encontrado'
            ];
        }
        
        $job_id = $process->job_ID;
        
        $seeker_ids = $this->input->post('seeker_ids');

        $job = $this->Posted_job->find($job_id);

        $staff_request = $this->Staff_request->find($job->request_ID);

        $this->db->select([
            'ID',
            'first_name',
            'last_name',
            'mobile'
        ]);
        $this->db->from('tbl_job_seekers');
        $this->db->where_in('ID', $seeker_ids);
        $results = $this->db->get()->result();

        if (count($results) == 0) {
            return [
                'success' => false,
                'message' => 'No existen postulantes para notificar'
            ];
        }

        foreach ($results as $row_seeker) {
            $mobile = format_mobile($row_seeker->mobile);

            if (!$mobile) {
                return [
                    'success' => false,
                    'message' => 'Candidato ' . $row_seeker->first_name . ' ' . $row_seeker->last_name . ' no tiene teléfono valido para enviar la notificación.'
                ];
            }
            
            $in_process = $this->Recruitment_candidate->is_candidate_active_in_other_process($job_id, $row_seeker->ID);

            if ($in_process) {
                return [
                    'success' => false,
                    'message' => 'Candidato ' . $row_seeker->first_name . ' ' . $row_seeker->last_name . ' está en otro proceso. Se solicita que se elimine del proceso donde no se está utilizando.'
                ];
            }
        }

        foreach ($results as $row_seeker) {
            $mobile = format_mobile($row_seeker->mobile);

            if (!$mobile) {
                continue;
            }

            $url = $this->config->item('hrm_api2_url') . '/whatsapp/employee/request-send-link/' . $mobile;

            $link_url = $this->Recruitment_document_request->create_link($row_seeker->ID, $job_id);

            if (!$link_url) {
                continue;
            }
            
            $url_params = [
                'process_id' => $process->id,
                'candidate_id' => $row_seeker->ID
            ];
            
            $query_string = http_build_query($url_params);
            $link_url = $link_url . '&' . $query_string;
               
            $params = [
                'full_name' => trim($row_seeker->last_name . ' ' . $row_seeker->first_name),
                'position' => $job->job_title,
                'linkUploadDocument' => $link_url,
                'ccosto' => $staff_request ? $staff_request->cost_center : null
            ];
    
            $options = [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_TIMEOUT => 5 //Segundos
            ];
    
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response == false) {
                continue;
            }

            $response_api = @json_decode($response);
        
            if ($response_api->error != 0) {
                continue;
            }
        }

        return [
            'success' => true,
            'message' => 'Notificación enviada'
        ];
    }

    private function send_notification_gratitude()
    {
        $job_id = $this->input->post('job_id');
        $seeker_ids = $this->input->post('seeker_ids');

        $job = $this->Posted_job->find($job_id);

        $staff_request = $this->Staff_request->find($job->request_ID);

        $this->db->select([
            'ID',
            'first_name',
            'last_name',
            'mobile'
        ]);
        $this->db->from('tbl_job_seekers');
        $this->db->where_in('ID', $seeker_ids);
        $results = $this->db->get()->result();

        if (count($results) == 0) {
            return [
                'success' => false,
                'message' => 'No existen postulantes para notificar'
            ];
        }

        foreach ($results as $row_seeker) {
            $mobile = format_mobile($row_seeker->mobile);

            if (!$mobile) {
                return [
                    'success' => false,
                    'message' => 'Candidato ' . $row_seeker->first_name . ' ' . $row_seeker->last_name . ' no tiene teléfono valido para enviar la notificación.'
                ];
            }
        }

        foreach ($results as $row_seeker) {
            $mobile = format_mobile($row_seeker->mobile);
         
            $url = $this->config->item('hrm_api2_url') . '/whatsapp/employee/gratitude/' . $mobile;

            $params = [
                'position' => $job->job_title,
                'ccosto' => $staff_request ? $staff_request->cost_center : null
            ];
    
            $options = [
                CURLOPT_URL => $url,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_TIMEOUT => 5 //Segundos
            ];
    
            $ch = curl_init();
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            curl_close($ch);
    
            if ($response == false) {
                continue;
            }

            $response_api = @json_decode($response);
        
            if ($response_api->error != 0) {
                continue;
            }
        }

        return [
            'success' => true,
            'message' => 'Notificación enviada'
        ];
    }
}
