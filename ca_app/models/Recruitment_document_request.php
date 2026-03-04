<?php
class Recruitment_document_request extends CI_Model 
{
    public function create_link($seeker_id, $job_id = null, $created_by = null)
    {
        $token = create_token(75, uniqid(microtime(true)));

        $data_link = [
            'seeker_id' => $seeker_id,
            'job_id' => $job_id,
            'token' => $token,
            'token_created_at' => date('Y-m-d H:i:s'),
            'created_by' => $created_by ? $created_by : ($this->session->userdata('user_id') ? $this->session->userdata('user_id') : null)
        ];

        $this->db->insert('tbl_recruitment_document_requests', $data_link);
        $link_id = $this->db->insert_id();

        if (!$link_id) {
            return false;
        }
       
        $link_url = site_url('jobseeker_document_requests_auth_login?t=' . $token);
        
        return $link_url;
    }
}
