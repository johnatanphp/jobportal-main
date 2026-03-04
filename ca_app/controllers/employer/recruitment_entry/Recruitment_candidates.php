<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_candidates extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
    
		$this->load->model('Recruitment_candidate');
        $this->load->model('Recruitment_attached_document');
        $this->load->model('Jobseeker_form_rtps');
        $this->load->model('Recruitment_process_document');
        $this->load->model('Recruitment_document_type');
        $this->load->model('Employee_category');
        $this->load->model('Employee_type');
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_contract');
        $this->load->model('Recruitment_contracts_synchronization_log');
        
        //load libraries
        $this->load->library('WS_hrmgo/WS_hrmgo_contract_model_lib');
    }

    public function show_process($process_id, $candidate_id)
    {
        $process = $this->Recruitment_process->find($process_id);

        if (!$process) {
            show_404();
        }

        $process_candidate = $this->Recruitment_candidate->get_candidate_by_process_id(
            $process_id, 
            $candidate_id
        );
        
        if (!$process_candidate) {
            show_404();
        }

        $job_id = $process->job_ID;
        $row_applied = $this->Applied_jobs->get_applied_job_by_seeker_and_job_id($candidate_id, $job_id);
        $row_applied_id = @$row_applied->ID;
        $answers_applicant = $this->Applied_jobs->get_answers_applicant($row_applied_id);
        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $rs_contract = $this->Recruitment_contract->find([
            'process_id' => $process_id,
            'seeker_id' => $candidate_id
        ]);
        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);
        $process_company = $this->Recruitment_process->get_company_by_process_id($process_id);

        $sync_log_errors = $this->Recruitment_contracts_synchronization_log->all([
            'process_id' => $process_id,
            'seeker_id' => $candidate_id,
            'success' => 0
        ]);

        $data['title'] = 'Proceso de contratación del candidato - ' . SITE_NAME;
        $data['ads_row'] = $this->ads;
        $data['process'] = $process;
        $data['job'] = $job;
        $data['applied_id'] = $row_applied_id;
        $data['candidate'] = $candidate;
        $data['rs_process_candidate'] = $process_candidate; 
        $data['rs_contract'] = $rs_contract; 
        $data['rs_document_counter'] = $this->Recruitment_attached_document->get_counter_group_by_document_key($job_id, $candidate_id);
        $data['process_country'] = $process_country;
        $data['process_company'] = $process_company;
        $data['result_answers_applicant'] = $answers_applicant;
        $data['staff_request'] = $this->Staff_request->find($job->request_ID);
        $data['rys_documents'] = $this->Recruitment_document_type->all(['active' => 1, 'country_id' => $process_country->ID]);
        $data['sync_log_errors'] = $sync_log_errors;

        $this->load->view('employer/recruitment_entry/candidate/selection_process', $data);
    }

    public function request_documents()
    {
        $job_id = $this->input->post('job_id');
        $candidate_id = $this->input->post('candidate_id');
        
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);

        if (!$job || !$job->request_ID) {
            echo json_encode(['success' => false]);
            return;
        }

        $this->load->library('Email/Recruitment_process/Recruitment_process_candidate_start_hiring_email');

        $email_sent = $this->recruitment_process_candidate_start_hiring_email->send($job->request_ID, $candidate_id);

        echo json_encode(['success' => $email_sent ? true : false]);
    }

    public function document_approve_reject()
    {
        $this->load->model('Jobseeker_required_document');

        $all_input = $this->input->post();

        $trans_status = $this->Jobseeker_required_document->change_approval_document(
            $all_input
        );
        
        $data_output['success'] = $trans_status;

        if ($trans_status) {

            $rs_document = $this->Jobseeker_required_document->get_recruitment_document(
                $all_input['seeker_id'], 
                $all_input['document'],
                isset($all_input['ref_id']) ? $all_input['ref_id'] : null,
                $all_input['job_id'] 
            );

            if ($this->session->userdata('current_profile_id') == 3) {
                $data_output['approve'] = $rs_document ? $rs_document->approved : 0;
            }

            if ($this->session->userdata('current_profile_id') == 5) {
                $data_output['approve'] = $rs_document ? $rs_document->legal_approved : 0;
            }

            if ($this->session->userdata('current_profile_id') == 6) {
                $data_output['approve'] = $rs_document ? $rs_document->accounting_approved : 0;
            }
            
            // $this->send_notify_approved_completed(
            //     $all_input['job_id'], 
            //     $all_input['seeker_id'], 
            //     $all_input['document'], 
            //     $data_output['approve']
            // );
        }

        echo json_encode($data_output);
    }

    public function save_document_comments()
    {
        $this->load->model('Jobseeker_required_document');

        $all_input = $this->input->post();
        
        $trans_status = $this->Jobseeker_required_document->save_document_comments($all_input);

        echo json_encode(
            array(
                'success' => $trans_status
            )
        );
    }

    public function modal_rs_document_comment()
    {
        $this->load->model('Jobseeker_required_document');

        $input = $this->input->post();

        $candidate_id = $input['seeker_id']; 
        $document_id = $input['document'];
        $job_id = $input['job_id'];
        $ref_id = isset($input['ref_id']) ? $input['ref_id'] : null;

        $data['rs_document'] = $this->Jobseeker_required_document->get_recruitment_document(
            $candidate_id, 
            $document_id,
            $ref_id,
            $job_id
        );

        $data['candidate_id'] = $candidate_id;
        $data['document_id'] = $document_id;
        $data['ref_id'] = $ref_id;
        $data['job_id'] = $job_id;
        
        $this->load->view('employer/recruitment_entry/modal/rs_document_comment', $data);
    }

    public function export_excel_rtps_cantidate_list($job_id)
    {
        $this->load->model('Jobseeker_form_rtps');
        $this->Jobseeker_form_rtps->export_excel_form_rtps_candidates(
            $job_id
        );
    }

    public function modal_hire_candidate($process_id, $seeker_id)
    {  
        $process = $this->Recruitment_process->find($process_id);
        $job = $this->Posted_job->get_posted_job_by_id($process->job_ID);
        $staff_request = $this->Staff_request->find($job->request_ID);
        $process_candidate = $this->Recruitment_candidate->get_candidate_by_process_id($process_id, $seeker_id);
        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);
        $process_company = $this->Recruitment_process->get_company_by_process_id($process_id);

        $contract_models = [];

        if ($process_country && $process_country->iso_3166_1_alpha2 != 'PE') {
            $contract_models = $this->ws_hrmgo_contract_model_lib->all([
                'company_id' => $process_company->ID
            ]);
        }
         
        $data = [
            'staff_request' => $staff_request,
            'process_candidate' => $process_candidate,
            'process_country' => $process_country,
            'contract_models' => $contract_models,
            'consultants' => [],
        ];
    
        $this->load->view('employer/recruitment_entry/candidate/common/modal_hire_candidate_content', $data);
    }

    public function hire_candidate()
    {
        $this->load->model('Recruitment_contract');
                
        try {
            $input = $this->input->post();
            $trans_status = false;
            $data_response = [];

            if ($this->config->item('system_payroll') == 'eplani') {
                $this->load->library('WS_overall/WS_overall_recruitment_seeker_hire_lib');

                $response = $this->ws_overall_recruitment_seeker_hire_lib->exec($input);

                $data_response = [
                    'status' => $response['status'],
                    'message' => $response['message'],
                ];

                if (!$response['status'] && isset($response['rightful_claimants_error'])) {
                    $data_response['rightful_claimants_error'] = $response['rightful_claimants_error'];
                }
            }

            if ($this->config->item('system_payroll') == 'ca') {
                $this->load->library('WS_ca/WS_ca_recruitment_seeker_hire_lib');

                $response = $this->ws_ca_recruitment_seeker_hire_lib->send($input);

                $data_response = [
                    'status' => $response['status'],
                    'message' => $response['message'],
                ];
            }            
        } catch (\Exception $e) {

            if ($e->getCode() == 1) {
                echo json_encode([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
                return;
            }
        }

        $process = $this->Recruitment_process->find($input['process_id']);

        $job = $this->Posted_job->find($process->job_ID);

        // Enviar Notificaiones si estan encendidas
        if ($data_response['status'] == true && $job->request_ID) {
           
            //Notificar al postulante por correo
            if (isset($input['notify_candidate_by_mail']) && $input['notify_candidate_by_mail'] == 1) {
                $this->load->library('Email/Recruitment_process/Recruitment_process_candidate_contracted_email');
                $this->recruitment_process_candidate_contracted_email->send($job->request_ID, $input['seeker_id']);
            }

            //Notificar al postulante por WhatsApp
            if (isset($input['notify_candidate_by_whatsapp']) && $input['notify_candidate_by_whatsapp'] == 1) {
                $this->load->library('Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_contracted_lib');
                $this->whatsapp_recruitment_proccess_candidate_contracted_lib->send($job->request_ID, $input['seeker_id']);
            }
            
            //Notificar al reclutador por correo
            if (isset($input['notify_employer_by_mail']) && $input['notify_employer_by_mail'] == 1) {
                $this->load->library('Email/Recruitment_process/Recruitment_process_candidate_to_payroll_system_email');
                $this->recruitment_process_candidate_to_payroll_system_email->send($job->request_ID, $input['seeker_id']);
            }
        }

        echo json_encode($data_response);
    }

    public function evicertia_status($seeker_id, $document_key)
    {
        $this->load->view('employer/recruitment_entry/candidate/modal/evicertia_status_seeker_documents');
    }

    public function report_periods()
    {
        $data['title'] = "Reporte Auto-determlnación de porcentajes - " . SITE_NAME;
        $data['ads_row'] = $this->ads;
        $data['consultants'] = [];

        $this->load->view('employer/recruitment_entry/recruitment/report_periods', $data);
    }

    public function generate_report_periods()
    {
        $this->load->library('Exports/Report_periods_export', null, 'Report_periods_export');

        $params = [
            'no_cia' => $this->input->get('no_cia'),
            'period_year' => $this->input->get('period_year'), 
            'period_month' => $this->input->get('period_month')
        ];

        $this->Report_periods_export->build($params)
                                    ->download('Reporte-Auto-determlnación-porcentaje');
    }

    public function get_foraign_totals()
    {
        try {
            $input = $this->input->post();

            $this->load->library(
                'Recruitment/Recruitment_seeker_hire_lib', 
                null, 
                'Recruitment_seeker_hire_lib'
            );

            $totals = $this->Recruitment_seeker_hire_lib->get_foraign_totals(
                $this->input->post('date_admission'),
                $this->input->post('no_cia')
            );

            echo json_encode([
                'success' => true,
                'period' => $totals['period'],
                'total_trab' => $totals['total_employees_foreign_to_hire'],
                'total_amount' => $totals['total_salary_foreign_available']
            ]);
            return;
        } catch (\Exception $e) {

            if ($e->getCode() == 1) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                return;
            }
        }
    }

    public function notify_approved_completed($seeker_id)
    {        
        $form_rtps = $this->Jobseeker_form_rtps->get_form_rtps_by_jobseeker_id($seeker_id);

        $doc_requerired = [
            'identification_document',
            'residency_verifications',
            'children_identification_document', 
            'spouse_certificates_cohabitation' 
        ];
    
        foreach ($doc_requerired as $doc_key) {
            $this->db->select([
                'document_key',
                'approved AS rrhh_approved',
                'legal_approved AS legal_approved'
            ]);
            $this->db->from('tbl_recruitment_seeker_documents');
            $this->db->where('seeker_ID', $seeker_id); 
            $this->db->where_in('document_key', $doc_key);
            $this->db->order_by('id', 'DESC');

            $row = $this->db->get()->row();

            if ($doc_key == 'spouse_certificates_cohabitation') {
                $spouse = $this->Jobseeker_form_rtps->get_rightful_claimant_spouse_by_form_id(@$form_rtps->form_ID);
                if (!($spouse && $spouse->document_type == '1' && $spouse->kinship == 1)) {
                    continue;
                }
            }

            if ($doc_key == 'children_identification_document' && 
                count($this->Jobseeker_form_rtps->get_peruvian_childrens(@$form_rtps->form_ID)) == 0) {
                continue;
            } 

            if ($this->session->userdata('current_profile_id') == 3 && (!$row || !$row->rrhh_approved)) {
                return false;
            }

            if ($this->session->userdata('current_profile_id') == 5 && (!$row || !$row->legal_approved)) {
                return false;
            }
        }

        return true;
    }

    private function send_notify_approved_completed(
        $job_id, 
        $seeker_id, 
        $document, 
        $approve
    )
    {   
        if ($this->session->userdata('current_profile_id') == 6) {
            return;
        }

        $seeker = $this->Job_seeker->find($seeker_id);
        
        if ($seeker->document_type == '1') {
            return;
        }

        if (!$approve) {
            return;
        }

        $doc_requerired = [
            'identification_document',
            'residency_verifications',
            'children_identification_document', 
            'spouse_certificates_cohabitation'  
        ];
        
        if (!in_array($document, $doc_requerired)) {
            return;
        }

        if (!$this->notify_approved_completed($seeker_id)) {
            return;
        }

        $job = $this->Posted_job->find($job_id);
        
        $profile_id = $this->session->userdata('current_profile_id');
        
        $data_email = [
            'seeker' => $seeker,
            'job' => $job,
            'profile_id' => $profile_id,
            'url_link' => site_url('employer/recruitment_entry/recruitment_candidates/show_process/' . $job_id . '/' . $seeker_id),   
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $mail_message = load_email_view('email/rys_documents_approved_completed', $data_email);

        $rrhh_emails = [];
        $legal_emails = [];

        //Listar usuarios LEGAL
        $users = $this->Employer->get_internal_by_profile_id($job->company_ID, 5);

        foreach ($users as $user) {
            $legal_emails[] = $user->email;
        }

        //Listar usuarios RRHH
        $this->db->select([
            'user.email'
        ]);
        $this->db->from('tbl_recruitment_rrhh_assignments rrhh_assignments');
        $this->db->join('tbl_employers user', 'user.ID=rrhh_assignments.rrhh_user_ID'); 
        $this->db->where('rrhh_assignments.job_ID', $job_id);

        $users = $this->db->get()->result();

        foreach ($users as $user) {
            $rrhh_emails[] = $user->email;
        }
            
        if ($profile_id == 3) {
            $this->email->subject('Documentos Aprobados por RRHH - ' . $seeker->first_name . ' ' . $seeker->last_name);
            $this->email->to($legal_emails);
        }

        if ($profile_id == 5) {
            $this->email->subject('Documentos Aprobados por LEGAL - ' . $seeker->first_name . ' ' . $seeker->last_name);
            $this->email->to($rrhh_emails);
        }

        $this->email->message($mail_message);     
        $this->email->send();         
    }

    public function get_sync_logs()
    {
        $process_id = $this->input->get('process_id');
        $seeker_id = $this->input->get('seeker_id');

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
        $this->db->where('sync_logs.seeker_id', $seeker_id);
        $this->db->where('sync_logs.process_id', $process_id);
        $this->db->order_by('sync_logs.id', 'ASC');
        
        $results = $this->db->get()->result();

        echo json_encode([
            'data' => $results
        ]);
    }

    public function get_sync_log_detail()
    {
        $id = $this->input->get('id');

        $this->db->from('tbl_recruitment_contracts_synchronization_logs');
        $this->db->where('id', $id);
        $sync_row = $this->db->get()->row();

        $data['sync_row'] = $sync_row;
        $this->load->view('employer/recruitment_entry/candidate/common/modal_content_show_sync_logs_detail', $data);
    }
}
