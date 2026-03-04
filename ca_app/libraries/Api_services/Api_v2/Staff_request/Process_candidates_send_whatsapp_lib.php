<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_send_whatsapp_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($params)
    {
        if (!isset($params['template_id'])) {
            return [
                'status' => false,
                'message' => 'No se ha proporcionado template_id'
            ];
        }
    
        $template_id = $params['template_id'];

        // Consultar plantilla de correo
        $this->db->where('id', $template_id);
        $query = $this->db->get('tbl_email_applicant_phase');
        $template_email = $query->row();

        if (!$template_email) {
            return [
                'status' => false,
                'message' => 'El template_id proporcionado es incorrecto'
            ];
        }
        
        if ($template_id == 4) { 
            $this->send_candidate_register_portal($params);
        }

        if ($template_id == 5) { 
            $this->send_recruitment_candidate_add($params);
        }

        if ($template_id == 6) { 
            $this->send_recruitment_candidate_move($params);
        }

        if ($template_id == 7) { 
            $this->send_recruitment_candidate_reject($params);
        }

        if ($template_id == 8) { 
            $this->send_recruitment_candidate_start_hiring($params);
        }

        if ($template_id == 9) { 
            $this->send_recruitment_candidate_contracted($params);
        }
        
        if ($template_id == 12) { 
            
            if (!isset($params['parameters']['candidate_id'])) {
                return [
                    'status' => false,
                    'message' => 'No se ha proporcionado candidate_id[]'
                ];
            }
            
            if (!is_array($params['parameters']['candidate_id']) || count($params['parameters']['candidate_id']) == 0) {
                return [
                    'status' => false,
                    'message' => 'No se ha proporcionado candidate_id[]'
                ];
            }
            
            $this->send_request_contracting_documents($params);
        }

        return [
            'status' => true,
            'message' => 'Notificación enviada con éxito'
        ];
    }

    private function send_recruitment_candidate_contracted($params)
    {
        $candidate_ids = explode(',', $params['candidate_ids']);
    
        $this->db->select([
            'seekers.id',
            'staff_requests.job_title AS job_title',
            'seekers.first_name',
            'seekers.mobile',
            'jobs.job_slug AS job_slug'
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.request_id = staff_requests.ID');
        $this->db->join('tbl_recruitment_candidates rs_candidates', 'rs_candidates.process_id = sr_process.id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID = rs_candidates.job_ID');
        $this->db->join('tbl_job_seekers seekers', 'rs_candidates.seeker_ID = seekers.ID'); 

        if (isset($params['candidate_ids'])) {
            $candidates_ids_chunk = array_chunk($candidate_ids, 25);
            $this->db->group_start();
            foreach($candidates_ids_chunk as $group_candidates_ids) {
                $this->db->or_where_in('seekers.ID', $group_candidates_ids);
            }
            $this->db->group_end();
        }

        if (isset($params['request_id'])) {
            $this->db->where('staff_requests.id', $params['request_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('sr_process.id', $params['process_id']);
        }

        $this->db->group_by('seekers.ID');

        $results = $this->db->get()->result();

        foreach ($results as $candidate_row) {
            $mobile = format_mobile($candidate_row->mobile);

            if (!$mobile) {
                continue;
            }

            $params = [
                'candidate_name' => trim($candidate_row->first_name),
                'position' => trim($candidate_row->job_title),
                'link_detail' => site_url('jobs/' . $candidate_row->job_slug)
            ];

            $this->send_whatsapp_notification($params, 'whatsapp/recruitment_candidate/contracted/' . $mobile);
        }
    }

    private function send_recruitment_candidate_start_hiring($params)
    {
        $candidate_ids = explode(',', $params['candidate_ids']);
    
        $this->db->select([
            'seekers.ID AS id',
            'staff_requests.job_title AS job_title',
            'seekers.first_name',
            'seekers.mobile',
            'jobs.ID AS job_id',
            'jobs.job_slug AS job_slug'
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.request_id = staff_requests.ID');
        $this->db->join('tbl_recruitment_candidates rs_candidates', 'rs_candidates.process_id = sr_process.id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID = rs_candidates.job_ID');
        $this->db->join('tbl_job_seekers seekers', 'rs_candidates.seeker_ID = seekers.ID'); 

        if (isset($params['candidate_ids'])) {
            $candidates_ids_chunk = array_chunk($candidate_ids, 25);
            $this->db->group_start();
            foreach($candidates_ids_chunk as $group_candidates_ids) {
                $this->db->or_where_in('seekers.ID', $group_candidates_ids);
            }
            $this->db->group_end();
        }

        if (isset($params['request_id'])) {
            $this->db->where('staff_requests.id', $params['request_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('sr_process.id', $params['process_id']);
        }

        $this->db->group_by('seekers.ID');

        $results = $this->db->get()->result();

        foreach ($results as $candidate_row) {
            $mobile = format_mobile($candidate_row->mobile);

            if (!$mobile) {
                continue;
            }

            $this->load->model('Recruitment_document_request');
            $url_link = $this->Recruitment_document_request->create_link($candidate_row->id, $candidate_row->job_id);

            if (!$url_link) {
                continue;
            }

            $params = [
                'candidate_name' => trim($candidate_row->first_name),
                'position' => trim($candidate_row->job_title),
                'link_document' => $url_link
            ];

            $this->send_whatsapp_notification($params, 'whatsapp/recruitment_candidate/start_hiring/' . $mobile);
        }
    }

    private function send_recruitment_candidate_reject($params)
    {
        $candidate_ids = explode(',', $params['candidate_ids']);
    
        $this->db->select([
            'seekers.id',
            'staff_requests.job_title AS job_title',
            'seekers.first_name',
            'seekers.mobile',
            'jobs.job_slug AS job_slug'
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.request_id = staff_requests.ID');
        $this->db->join('tbl_recruitment_candidates rs_candidates', 'rs_candidates.process_id = sr_process.id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID = rs_candidates.job_ID');
        $this->db->join('tbl_job_seekers seekers', 'rs_candidates.seeker_ID = seekers.ID'); 

        if (isset($params['candidate_ids'])) {
            $candidates_ids_chunk = array_chunk($candidate_ids, 25);
            $this->db->group_start();
            foreach($candidates_ids_chunk as $group_candidates_ids) {
                $this->db->or_where_in('seekers.ID', $group_candidates_ids);
            }
            $this->db->group_end();
        }

        if (isset($params['request_id'])) {
            $this->db->where('staff_requests.id', $params['request_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('sr_process.id', $params['process_id']);
        }

        $this->db->group_by('seekers.ID');

        $results = $this->db->get()->result();

        foreach ($results as $candidate_row) {
            $mobile = format_mobile($candidate_row->mobile);

            if (!$mobile) {
                continue;
            }

            $params = [
                'candidate_name' => trim($candidate_row->first_name),
                'position' => trim($candidate_row->job_title),
                'link_detail' => site_url('jobs/' . $candidate_row->job_slug)
            ];

            $this->send_whatsapp_notification($params, 'whatsapp/recruitment_candidate/reject/' . $mobile);
        }
    }

    private function send_recruitment_candidate_move($params)
    {
        $candidate_ids = explode(',', $params['candidate_ids']);
    
        $this->db->select([
            'seekers.id',
            'staff_requests.job_title AS job_title',
            'seekers.first_name',
            'seekers.mobile',
            'stages.name AS stage_name',
            'jobs.job_slug AS job_slug'
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.request_id = staff_requests.ID');
        $this->db->join('tbl_recruitment_candidates rs_candidates', 'rs_candidates.process_id = sr_process.id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID = rs_candidates.job_ID');
        $this->db->join('tbl_job_seekers seekers', 'rs_candidates.seeker_ID = seekers.ID'); 
        $this->db->join('tbl_recruitment_stages stages', 'rs_candidates.stage = stages.id');

        if (isset($params['candidate_ids'])) {
            $candidates_ids_chunk = array_chunk($candidate_ids, 25);
            $this->db->group_start();
            foreach($candidates_ids_chunk as $group_candidates_ids) {
                $this->db->or_where_in('seekers.ID', $group_candidates_ids);
            }
            $this->db->group_end();
        }

        if (isset($params['request_id'])) {
            $this->db->where('staff_requests.id', $params['request_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('sr_process.id', $params['process_id']);
        }

        $this->db->group_by('seekers.ID');

        $results = $this->db->get()->result();

        foreach ($results as $candidate_row) {
            $mobile = format_mobile($candidate_row->mobile);

            if (!$mobile) {
                continue;
            }

            $params = [
                'candidate_name' => trim($candidate_row->first_name),
                'position' => trim($candidate_row->job_title),
                'stage_name' => trim($candidate_row->stage_name),
                'link_detail' => site_url('jobs/' . $candidate_row->job_slug)
            ];

            $this->send_whatsapp_notification($params, 'whatsapp/recruitment_candidate/move/' . $mobile);
        }
    }

    private function send_recruitment_candidate_add($params)
    {
        $candidate_ids = explode(',', $params['candidate_ids']);
    
        $this->db->select([
            'seekers.id',
            'staff_requests.job_title AS job_title',
            'seekers.first_name',
            'seekers.mobile',
            'jobs.job_slug AS job_slug'
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.request_id = staff_requests.ID');
        $this->db->join('tbl_recruitment_candidates rs_candidates', 'rs_candidates.process_id = sr_process.id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID = rs_candidates.job_ID');
        $this->db->join('tbl_job_seekers seekers', 'rs_candidates.seeker_ID = seekers.ID'); 

        if (isset($params['candidate_ids'])) {
            $candidates_ids_chunk = array_chunk($candidate_ids, 25);
            $this->db->group_start();
            foreach($candidates_ids_chunk as $group_candidates_ids) {
                $this->db->or_where_in('seekers.ID', $group_candidates_ids);
            }
            $this->db->group_end();
        }

        if (isset($params['request_id'])) {
            $this->db->where('staff_requests.id', $params['request_id']);
        }

        if (isset($params['process_id'])) {
            $this->db->where('sr_process.id', $params['process_id']);
        }

        $this->db->group_by('seekers.ID');

        $results = $this->db->get()->result();

        foreach ($results as $candidate_row) {
            $mobile = format_mobile($candidate_row->mobile);

            if (!$mobile) {
                continue;
            }

            $params = [
                'candidate_name' => trim($candidate_row->first_name),
                'position' => trim($candidate_row->job_title),
                'link_detail' => site_url('jobs/' . $candidate_row->job_slug)
            ];

            $this->send_whatsapp_notification($params, 'whatsapp/recruitment_candidate/add/' . $mobile);
        }
    }

    private function send_candidate_register_portal($params)
    {
        $candidate_ids = explode(',', $params['candidate_ids']);
     
        $this->db->select([
            'first_name',
            'mobile'
        ]);
        $this->db->from('tbl_job_seekers seekers');
        $candidates_ids_chunk = array_chunk($candidate_ids, 25);
        $this->db->group_start();
        foreach($candidates_ids_chunk as $group_candidates_ids) {
            $this->db->or_where_in('seekers.ID', $group_candidates_ids);
        }
        $this->db->group_end();

        $this->db->group_by('seekers.ID');

        $results = $this->db->get()->result();

        foreach ($results as $candidate_row) {
            $mobile = format_mobile($candidate_row->mobile);

            if (!$mobile) {
                continue;
            }

            $params = [
                'candidate_name' => trim($candidate_row->first_name),
                'link_login' => site_url('login')
            ];

            $this->send_whatsapp_notification($params, 'whatsapp/candidate/register_portal/' . $mobile);
        }
    }
    
    private function send_request_contracting_documents($params)
    {
        $candidate_ids = $params['parameters']['candidate_id'];        
        $process_id = $params['parameters']['process_id'];
        //$candidate_ids = explode(',', $params['candidate_ids']);
    
        $this->db->select([
            'seekers.id',
            'staff_requests.job_title AS job_title',
            'seekers.first_name',
            'seekers.mobile',
            'jobs.ID AS job_id'
        ]);
        
        $this->db->from('tbl_staff_requests staff_requests');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.request_id = staff_requests.ID');
        $this->db->join('tbl_recruitment_candidates rs_candidates', 'rs_candidates.process_id = sr_process.id');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID = rs_candidates.job_ID');
        $this->db->join('tbl_job_seekers seekers', 'rs_candidates.seeker_ID = seekers.ID'); 
        
        if (is_array($candidate_ids) && count($candidate_ids) > 0) {
            $candidates_ids_chunk = array_chunk($candidate_ids, 25);
            $this->db->group_start();
            foreach($candidates_ids_chunk as $group_candidates_ids) {
                $this->db->or_where_in('seekers.ID', $group_candidates_ids);
            }
            $this->db->group_end();
        }

        if ($process_id) {
            $this->db->where('sr_process.id', $process_id);
        }

        $this->db->group_by('seekers.ID');

        $results = $this->db->get()->result();
                
        foreach ($results as $candidate_row) {
            $mobile = format_mobile($candidate_row->mobile);

            if (!$mobile) {
                continue;
            }
            
            if (!$candidate_row->job_id) {
                continue;
            }
            
            $this->load->model('Recruitment_document_request');
            $url_link = $this->Recruitment_document_request->create_link($candidate_row->id, $candidate_row->job_id);

            if (!$url_link) {
                continue;
            }
            
            $params = [
                'candidate_name' => trim($candidate_row->first_name),
                'job_title' => trim($candidate_row->job_title),
                'link_upload_documents' => $url_link
            ];

            $this->send_whatsapp_notification($params, 'whatsapp/recruitment_candidate/request_contracting_documents/' . $mobile);
        }
    }
    
    private function send_whatsapp_notification($params, $path_url)
    {
        $url = $this->config->item('hrm_api2_url') . "/" . $path_url;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
			CURLOPT_POSTFIELDS => $params,
			//CURLOPT_HTTPHEADER => $headers,
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            return false;
        }

        $response = @json_decode($response);

        if ($response->error == 0) {
            return true;
        }
    
        return false;
    }
}
