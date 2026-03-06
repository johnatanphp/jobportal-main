<?php
class Recruitment_tray_candidate extends CI_Model 
{	
	public function find($where = [])
    {    
	    $this->db->from('tbl_recruitment_tray_candidates');
        
        if (count($where) > 0) {
            $this->db->where($where);
        }
        
	    return $this->db->get()->row();
    }

    public function get_all_candidates_by_ids($tray_ids)
    {
        $this->db->select([
            'tray_candidates.id AS tray_id',
            'candidates.ID AS id',
            'candidates.first_name AS first_name',
            'candidates.last_name AS last_name',
            'candidates.document_number AS document_number',
            'dt.abbreviation AS document_type_abbreviation_name',
            'recruitment_tray_doc_percentage_progress(tray_candidates.seeker_id, rc_process.job_ID) AS doc_percentage_progress'
        ]);
        
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_recruitment_tray_status tray_candidates_status', 'tray_candidates.status_id=tray_candidates_status.id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->join('tbl_identity_document_types dt', 'dt.id=candidates.document_type', 'left');
        $this->db->where_in('tray_candidates.id', $tray_ids);

        return $this->db->get()->result();
    }
    
    public function get_all_candidates_by_process_id($process_id, $candidate_ids)
    {
        $this->db->select([
            'tray_candidates.id AS tray_id',
            'candidates.ID AS id',
            'candidates.first_name AS first_name',
            'candidates.last_name AS last_name',
            'candidates.document_number AS document_number',
            'dt.abbreviation AS document_type_abbreviation_name',
            'recruitment_tray_doc_percentage_progress(tray_candidates.seeker_id, rc_process.job_ID) AS doc_percentage_progress'
        ]);
        
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_recruitment_tray_status tray_candidates_status', 'tray_candidates.status_id=tray_candidates_status.id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->join('tbl_identity_document_types dt', 'dt.id=candidates.document_type', 'left');
        $this->db->where('tray_candidates.process_id', $process_id);
        $this->db->where_in('tray_candidates.seeker_id', $candidate_ids);

        return $this->db->get()->result();
    }

    public function add_candidates($client_code, $candidates)
    {
        $this->load->model('Recruitment_document_request');
        
        $user_id = $this->session->userdata('user_id');
        $user =  $this->Employer->get_employer_by_id($user_id);
        $created_by = $user->ID;
        $company_id = $user->company_ID;

        $current_date_time = date('Y-m-d H:i:s');
        $job_title = 'Empleo proceso';

        $job_array = [
            'industry_ID' => 66, //Otros
            'job_title' => $job_title,
            'vacancies' => 1,
            'job_mode' => 'full_mode',
            'payment_currency' => null,
            'minimum_payment' => null,
            'maximum_payment' => null,
            'experience' => '',
            'last_date' => date("Y-m-d", strtotime($current_date_time . "- 30 day")),
            'country' => '-',
            'city' => '',
            'qualification' => '',
            'job_description' => '',
            'company_ID' => $company_id,
            'employer_ID' => $created_by,
            'required_skills' => '',
            'ip_address' => '',
            'dated' => $current_date_time,
            'has_questions' => 'no',
            'show_salary_in_ad' => 'no',
            'laboral_benefits' => '',
            'allow_people_disability' => 'no',
            'job_description' => '',
            'sts' => 'inactive',
            'job_ignore' => 1
        ];
        
        $job_id = $this->Posted_job->add_posted_job($job_array, [], 'no');  

        if (!$job_id) {
            return false;
        }

        $data_open_process = [
            'job_ID' => $job_id,
			'created_at' => $current_date_time,
			'created_by' => $created_by,
			'expiration_date' => null,
            'tray_type_id' => 3,
            'sts' => 'active'
		];

        $this->db->insert('tbl_recruitment_process', $data_open_process);
        $process_id = $this->db->insert_id();

        if (!$process_id) {
            return false;
        }

        $tray_ids = [];
        foreach ($candidates as $candidate) {
            $candidate_id = $candidate['candidate_id'];

            $data_candidate = [
                'client_code' => $client_code,
                'company_id' => $company_id,
                'seeker_id' => $candidate_id,
                'created_by' => $created_by,
                'process_id' => $process_id,
                'created_at' => $current_date_time,
                'status_id' => 1
            ];

            $this->db->insert('tbl_recruitment_tray_candidates', $data_candidate);

            $tray_process_id =  $this->db->insert_id();

            if (!$tray_process_id) {
                continue;
            }

            $tray_ids[] = $tray_process_id;
        }

        return count($tray_ids) > 0 ? $tray_ids : false;
    }

    public function send_link($tray_ids = [])
    {
        $this->db->select([
            'candidates.ID AS candidate_id',
            'candidates.first_name AS first_name',
            'candidates.last_name AS last_name',
            'candidates.mobile AS mobile',
            'rc_process.job_ID AS job_id',
            'tray_candidates.process_id AS process_id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->where_in('tray_candidates.id', $tray_ids);
        $results = $this->db->get()->result();

        foreach ($results as $candidate) {

            $link_url = $this->Recruitment_document_request->create_link($candidate->candidate_id, $candidate->job_id);

            $candidate_mobile = format_mobile((string)$candidate->mobile);

            if ($candidate_mobile && $link_url) {
                $this->load->library(
                    'Whatsapp/Whatsapp_jobseeker_send_request_documents_lib'
                );
        
                $ws_notification = $this->whatsapp_jobseeker_send_request_documents_lib->send([
                    'mobile' => $candidate_mobile,
                    'full_name' => $candidate->first_name . ' ' . $candidate->last_name,
                    'position' => 'Puesto laboral',
                    'linkUploadDocument' => $link_url,
                    'ccosto' => null
                ]);
        
                //Actualizar en bandeja envio de link
                $this->db->where('process_id', $candidate->process_id);
                $this->db->where('seeker_id', $candidate->candidate_id);
                $this->db->update('tbl_recruitment_tray_candidates', [
                    'link_sent' => 1
                ]);
            }
        }
    }
}
