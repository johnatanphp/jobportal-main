<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
        $this->load->model('Workflow_client');
        $this->load->model('Recruitment_tray_candidate');
    }
	
    public function search_candidate() 
	{   
        $this->form_validation->set_data($this->input->get());
        $this->form_validation->set_rules('document_number', 'Número documento', 'trim|required');

        $this->form_validation->set_message('required', '%s es requerido');

		if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            echo json_encode([
                'message' => current($errors),
                'status' => false
            ]);
            return;
        }           

        $company_id = get_session_company_id();
		$company = $this->Company->find($company_id);

        $this->db->select([
			's.ID AS id',
			's.email',
			'dt.abbreviation AS document_type_abbreviation_name',
			's.document_number',
			's.first_name',
			's.paternal_last_name',
			's.maternal_last_name',
            's.mobile'
		])
		->from('tbl_job_seekers s')
		->join('tbl_identity_document_types dt', 'dt.id=s.document_type', 'left')
        ->where('s.country', $company->country_id)
		->where('s.sts', 'active');

		$document_number = trim((string)$this->input->get('document_number'));
	
        $this->db->where('document_number', $document_number);
	
		$results = $this->db->get()->result();

        $candidate_data = [];

		foreach ($results as $candidate_row) {
            $candidate_data[] = [
                'id' => $candidate_row->id,
                'email' => $candidate_row->email,
                'document_type_abbreviation_name' => $candidate_row->document_type_abbreviation_name,
                'document_number' => $candidate_row->document_number,
                'first_name' => $candidate_row->first_name,
                'paternal_last_name' =>  $candidate_row->paternal_last_name,
                'maternal_last_name' => $candidate_row->maternal_last_name,
                'last_name' => trim($candidate_row->paternal_last_name . ' ' . $candidate_row->maternal_last_name),
                'mobile' => $candidate_row->mobile
            ];
        }

		echo json_encode([
            'message' => 'OK',
			'status' => true,
			'data' => $candidate_data,
		]);
	}

    public function add_candidates()
    {
        check_permission_action('recruitment_tray', 'add_candidates');
        
        $this->load->model('Recruitment_document_request');

        $params = $this->input->post();

        $user_id = $this->session->userdata('user_id');
        $user =  $this->Employer->get_employer_by_id($user_id);
        $client_code = trim($params['client_code']);
        $candidates = $params['candidates'];

        $this->db->select([
            'candidates.ID AS candidate_id',
            'candidates.first_name AS candidate_first_name',
            'candidates.last_name AS candidate_last_name',
            'candidates.mobile AS candidate_mobile'
        ]);
        $this->db->from('tbl_job_seekers candidates');
        $this->db->where_in('candidates.ID', $candidates);
        $result_candidates = $this->db->get()->result();

        //Validate ingreso al proceso
        foreach ($result_candidates as $candidate) {
            $this->db->from('tbl_recruitment_tray_candidates');
            $this->db->where('seeker_id', $candidate->candidate_id);
            $this->db->where('client_code', $client_code);
            $this->db->where('status_id!=', 3);
            $count_in_process = $this->db->count_all_results();

            if ($count_in_process > 0) {
                echo json_encode([
                    'status' => false,
                    'message' => 'El postulante ' . $candidate->candidate_first_name . ' ' . $candidate->candidate_last_name .  ' ya tiene un proceso corriendo en este cliente',
                ]);
                return;
            }
        }

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
            'company_ID' => $user->company_ID,
            'employer_ID' => $user->ID,
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
            echo json_encode([
                'status' => false,
                'message' => 'Proceso Job id no pudo ser creado'
            ]);
            return;
        }

        $data_open_process = [
            'job_ID' => $job_id,
			'created_at' => $current_date_time,
			'created_by' => $user->ID,
			'expiration_date' => null,
            'tray_type_id' => 3,
            'sts' => 'active'
		];

        $this->db->insert('tbl_recruitment_process', $data_open_process);
        $process_id = $this->db->insert_id();

        if (!$process_id) {
             echo json_encode([
                'status' => false,
                'message' => 'Proceso no pudo ser creado'
            ]);
            return;
        }

        foreach ($result_candidates as $candidate) {
            $candidate_id = $candidate->candidate_id;

            $data_insert = [
                'client_code' => $client_code,
                'company_id' => $user->company_ID,
                'seeker_id' => $candidate_id,
                'created_by' => $user->ID,
                'process_id' => $process_id,
                'created_at' => $current_date_time,
                'status_id' => 1 //EN PROCESO
            ];

            $this->db->insert('tbl_recruitment_tray_candidates', $data_insert);
            $tray_process_id =  $this->db->insert_id();

            if (!$tray_process_id) {
                continue;
            }

            $link_url = $this->Recruitment_document_request->create_link($candidate_id, $job_id);

            $candidate_mobile = format_mobile((string)$candidate->candidate_mobile);

            if ($candidate_mobile && $link_url) {
                $this->load->library(
                    'Whatsapp/Whatsapp_jobseeker_send_request_documents_lib', 
                    null, 
                    'Whatsapp_jobseeker_send_request_documents_lib'
                );
        
                $ws_notification = $this->Whatsapp_jobseeker_send_request_documents_lib->send([
                    'mobile' => $candidate_mobile,
                    'full_name' => $candidate->candidate_first_name . ' ' . $candidate->candidate_last_name,
                    'position' => 'Puesto laboral',
                    'linkUploadDocument' => $link_url,
                    'ccosto' => null
                ]);
        
                //Actualizar en bandeja envio de link
                $this->db->where('process_id', $process_id);
                $this->db->where('seeker_id', $candidate_id);
                $this->db->update('tbl_recruitment_tray_candidates', [
                    'link_sent' => 1
                ]);
            }
        }

        echo json_encode([
            'status' => true,
            'message' => 'Postulantes agregados',
            'data' => []
        ]);
    }

    public function copy_link()
    {
        $this->load->model('Recruitment_document_request');
        $this->load->model('Recruitment_process');
        
        $params = $this->input->post();
        $tray_process_id = $params['id'];

        $this->db->from('tbl_recruitment_tray_candidates');
        $this->db->where('id', $tray_process_id);
        $tray_process = $this->db->get()->row();

        if (!$tray_process) {
            echo json_encode([
                'status' => false,
                'message' => 'Proceso a buscar es incorrecto'
            ]);
            return;
        }

        $process = $this->Recruitment_process->find($tray_process->process_id);
        $job_id = $process->job_ID;
        $candidate_id = $tray_process->seeker_id;

        $candidate = $this->Job_seeker->find($candidate_id);

        if (!$candidate) {
            echo json_encode([
                'status' => false,
                'message' => 'Postulante id es incorrecto'
            ]);
            return;
        }

        $link_url = $this->Recruitment_document_request->create_link($candidate_id, $job_id);

        if (!$link_url) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo generar el link'
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'data' => [
                'link_url' => $link_url
            ],
            'message' => 'Link generado'
        ]);
    }

    public function resend_link()
    {
        $this->load->model('Recruitment_document_request');
        $this->load->model('Recruitment_process');
        
        $params = $this->input->post();
        $tray_process_id = $params['id'];

        $this->db->from('tbl_recruitment_tray_candidates');
        $this->db->where('id', $tray_process_id);
        $tray_process = $this->db->get()->row();

        if (!$tray_process) {
            echo json_encode([
                'status' => false,
                'message' => 'Proceso a buscar es incorrecto'
            ]);
            return;
        }

        $process = $this->Recruitment_process->find($tray_process->process_id);
        $job_id = $process->job_ID;
        $candidate_id = $tray_process->seeker_id;

        $candidate = $this->Job_seeker->find($candidate_id);

        if (!$candidate) {
            echo json_encode([
                'status' => false,
                'message' => 'Postulante id es incorrecto'
            ]);
            return;
        }

        $mobile = format_mobile((string)$candidate->mobile);

        if (!$mobile) {
            echo json_encode([
                'status' => false,
                'message' => 'El número de celular es incorrecto'
            ]);
            return;
        }

        $link_url = $this->Recruitment_document_request->create_link($candidate_id, $job_id);

        if (!$link_url) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo generar el link'
            ]);
            return;
        }

        $this->load->library(
            'Whatsapp/Whatsapp_jobseeker_send_request_documents_lib', 
            null, 
            'Whatsapp_jobseeker_send_request_documents_lib'
        );
        
        $url_params = [
            'process_id' => $process->id,
            'candidate_id' => $candidate_id
        ];
        
        $query_string = http_build_query($url_params);
        $link_url = $link_url . '&' . $query_string;
        
        $notification_whatsapp = $this->Whatsapp_jobseeker_send_request_documents_lib->send([
            'mobile' => $mobile,
            'full_name' => $candidate->first_name . ' ' . $candidate->last_name,
            'position' => 'Puesto laboral',
            'linkUploadDocument' => $link_url,
            'ccosto' => null
        ]);

        if (!$notification_whatsapp) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo enviar la notificación'
            ]);
            return;
        }

        //Actualizar en bandeja envio de link
        $this->db->where('id', $tray_process_id);
        $this->db->update('tbl_recruitment_tray_candidates', [
            'link_sent' => 1
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'Link reenviado'
        ]);
    }
    
    public function import_candidates_validate()
    {
        $this->load->library(
            'Imports/Recruitment_tray_candiates_import', 
            null, 
            'Recruitment_tray_candiates_import'
        );

        $params = $this->input->post();
        $response = $this->Recruitment_tray_candiates_import->validate($params);
    
        echo json_encode([
            'status' => $response['status'],
            'message' => $response['message'],
            'data' => $response['data'] ?? []
        ]);
    }

    public function import_candidates_save()
    {
        check_permission_action('recruitment_tray', 'add_candidates');
        
        $this->load->library(
            'Imports/Recruitment_tray_candiates_import', 
            null, 
            'Recruitment_tray_candiates_import'
        );

        $params = $this->input->post();
        $response = $this->Recruitment_tray_candiates_import->save($params);
    
        echo json_encode([
            'status' => $response['status'],
            'message' => $response['message'],
            'data' =>  $response['data'] ?? []
        ]);
    }

    public function modal_candidate_detail()
    {
        $this->load->model('Recruitment_tray_candidate');
        $this->load->model('Recruitment_document_type');
        $this->load->model('Recruitment_attached_document');
        $this->load->model('Recruitment_process');

        $params = $this->input->post();
        $tray_candidate = $this->Recruitment_tray_candidate->find(['id' => $params['tray_id']]);
        $process = $this->Recruitment_process->find($tray_candidate->process_id);

        $data['tray_candidate'] = $tray_candidate;
        $data['process'] = $process;
        $data['candidate'] = $this->Job_seeker->find($tray_candidate->seeker_id);
        $data['job'] = $this->Posted_job->get_posted_job_by_id($process->job_ID);
        $data['rys_documents'] = $this->Recruitment_document_type->all(['active' => 1, 'country_id' => 56]);
        $data['rs_document_counter'] = $this->Recruitment_attached_document->get_counter_group_by_document_key(
            null,
            $tray_candidate->seeker_id
        );
        
        $this->load->view('employer/recruitment_tray/common/content_candidate_detail', $data);
    }

    public function modal_candidate_status()
    {
        $this->load->model('Recruitment_tray_candidate');
        $this->load->model('Recruitment_tray_status');
        $this->load->model('Recruitment_contract');
        $this->load->model('Recruitment_process');
       
        $params = $this->input->post();
        
        $tray_id = $params['tray_id'];

        $tray_candidate = $this->Recruitment_tray_candidate->find(['id' => $tray_id]);
       
        if (!$tray_candidate) {
            show_404();
        }

        $rc_process = $this->Recruitment_process->find($tray_candidate->process_id);
        
        $candidate = $this->Job_seeker->find($tray_candidate->seeker_id);
        $tray_candidate_created_by = $this->Employer->find($tray_candidate->created_by);
        $tray_candidate_status = $this->Recruitment_tray_status->find(['id' => $tray_candidate->status_id]);
        $contract = $this->Recruitment_contract->find([
            'seeker_id' => $tray_candidate->seeker_id,
            'job_id' => $rc_process->job_ID
        ]);

        $this->db->select([
            'sync_logs.id AS sync_id',
            'sync_types.name AS sync_name',
            'sync_logs.created_at AS sync_created_at',
            'sync_logs.success AS sync_success',
            'sync_logs.url AS sync_url',
            'sync_logs.parameters AS sync_parameters',
            'sync_logs.response AS sync_response',
            'sync_logs.description AS sync_description'
        ]);
        $this->db->from('tbl_recruitment_contracts_synchronization_logs sync_logs');
        $this->db->join('tbl_recruitment_contracts_synchronizations sync_types', 'sync_logs.sync_id=sync_types.id');
        $this->db->where('sync_logs.seeker_id', $tray_candidate->seeker_id);
        $this->db->where('sync_logs.job_id', $rc_process->job_ID);
        $this->db->order_by('sync_logs.id', 'ASC');
        
        $sync_logs = $this->db->get()->result();

        $data = [
            'tray_candidate' => $tray_candidate,
            'tray_candidate_created_by' => $tray_candidate_created_by,
            'tray_candidate_status' => $tray_candidate_status,
            'candidate' => $candidate,
            'contract' => $contract,
            'sync_logs' => $sync_logs
        ];
        
        $this->load->view('employer/recruitment_tray/common/modal_tray_candidates_status_content', $data);
    }

    public function get_sync_log_detail()
    {
        $id = $this->input->get('id');

        $this->db->from('tbl_recruitment_contracts_synchronization_logs');
        $this->db->where('id', $id);
        $sync_row = $this->db->get()->row();

        $data['sync_row'] = $sync_row;
        $this->load->view('employer/recruitment_tray/common/modal_tray_candidates_sync_logs_content', $data);
    }
}
