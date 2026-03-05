<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidates extends CI_Controller 
{	
	public function __construct()
    {
        parent::__construct();
        //Load models
		$this->load->model('Mof');
        $this->load->model('Recruitment_short_list');
        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_candidate');    
        $this->load->model('Staff_request_assigned_employer');
        $this->load->model('Recruitment_stage');
        
        //Helper
        $this->load->library('rys_seeker_fit_helper');	

        //Load libraries
        $this->load->library('aws_sns_client_lib');
        $this->load->library('rys_seeker_fit_helper');	

		$this->ads = $this->Ad->get_ads();

        validate_short_list_session_or_token();
    }

    public function show($process_id, $stage = 0)
    {
        show_404();
    }
    
    public function show_process($process_id, $stage_id = 5)
    {
        $rs_process = $this->Recruitment_process->find(['id' => $process_id]);
        
        if (!$rs_process) {
            show_404();
        }
        
        $job_id = $rs_process->job_ID;
        
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }
        
        if ($stage_id < 5 || $stage_id > 7) {
            $stage_id = $rs_process->sts_stage;
        }

        $stage_items = get_RS_stages();
        $stage_items[7] = 'CONTRATACIÓN';
    
        $filters = [
            'stage' => $stage_id
        ];
        
        $candidates = $this->Recruitment_short_list->search_short_list_job_by_job_id(
            $process_id,
            $filters
        );

        $rs_process->count_candidates = $this->Recruitment_short_list->count_all_candidates_by_stage($job_id, -1);
        
        for ($stage_index = 5; $stage_index < count($stage_items); $stage_index++) { 
            $data['count_candidates_stage'][$stage_index] = $this->Recruitment_short_list->count_all_candidates_by_stage(
                $job_id, 
                $stage_index
            );
        }
        
        $data['stage'] = $this->Recruitment_stage->find(['id' => $stage_id]);
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Terna o Short List recibida - Solicitud personal - ' . SITE_NAME;
        $data['job'] = $job;
        $data['rs_process'] = $rs_process;
        $data['all_candidates'] = $candidates;
        $data['stage_items'] = $stage_items;
        $data['filters'] = $filters;

        $this->load->view('employer/recruitment_short_list/received_short_list_view', $data);
    }
    
    public function select_candidate()
    {
        $this->form_validation->set_rules('process_id', 'Proceso Id', 'trim|required');
        $this->form_validation->set_rules('job_seeker_id', 'Candidato', 'trim|required|strip_all_tags');
    
        if ($this->form_validation->run() === FALSE) {
            
            echo json_encode(array(
                'success' => false,
                'message_error' => 'Hay datos incorrectos'
            ));

            return;
        }

        $jobseeker_id = $this->input->post('job_seeker_id');
        $process_id = $this->input->post('process_id');

        $trans_status = $this->Recruitment_short_list->select_candidate(
            $jobseeker_id, 
            $process_id
        );

        echo json_encode(array(
            'success' => $trans_status
        ));
    }

    public function unselect_candidate()
    {
        $this->form_validation->set_rules('process_id', 'Proceso Id', 'trim|required');
        $this->form_validation->set_rules('job_seeker_id', 'Candidato', 'trim|required|strip_all_tags');
    
        if ($this->form_validation->run() === FALSE) {
            
            echo json_encode(array(
                'success' => false,
                'message_error' => 'Hay datos incorrectos'
            ));

            return;
        }

        $job_seeker_id = $this->input->post('job_seeker_id');
        $process_id = $this->input->post('process_id');

        $trans_status = $this->Recruitment_short_list->unselect_candidate(
            $job_seeker_id, 
            $process_id
        );

        echo json_encode(array(
            'success' => $trans_status
        ));
    }

    public function notify_selection()
    {
        $process_id = $this->input->post('process_id');
        
        $rs_process = $this->Recruitment_process->find(['id' => $process_id]);
        
        if (!$rs_process) {
            echo json_encode(array(
                'success' => false
            ));
            return;
        }
        
        $job_id = $rs_process->job_ID;
        
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job || !$job->request_ID) {
            echo json_encode(array(
                'success' => false
            ));
            return;
        }

        $staff_request = $this->Staff_request->find($job->request_ID);
        
        $this->db->select([
            'employers.email'
        ]);
        $this->db->from('tbl_staff_request_assigned_employers assigned_employers');
        $this->db->join('tbl_employers employers', 'employers.ID=assigned_employers.employer_ID');
        $this->db->where('assigned_employers.request_ID', $staff_request->ID);
        $this->db->where('employers.sts', 'active');
        $assigned_employers = $this->db->get()->result();
    
        $emails = [];
        
        foreach ($assigned_employers as $employer) {
            $emails[] = $employer->email;
        }
        
        if (count($emails) == 0) {
            echo json_encode(array(
                'success' => false
            ));
            return;
        }
        
        $data_email = [
            'url_link' => site_url('employer/recruitment_processes/' . $job_id),   
            'staff_request' => $staff_request
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($emails);

        $mail_message = load_email_view('email/short_list_notify_selection_candidates', $data_email);

        $this->email->subject('Notificación - Terna o Short List seleccionada');
        $this->email->message($mail_message);     
        $sts_send = $this->email->send();

        echo json_encode(array(
            'success' => $sts_send
        ));
    }

    public function check_download_rs_documents($job_id = 0, $candidate_id = 0)
    {
        $this->load->library(
            'Recruitment/Recruitment_document_short_list_lib', 
            null, 
            'Recruitment_document_short_list_lib'
        );
       
        $check_download = $this->Recruitment_document_short_list_lib->check_download(
            $job_id, 
            $candidate_id
        );

        echo json_encode([
            'check_download' => $check_download
        ]);
    }

    public function download_rs_documents($job_id = 0, $candidate_id = 0)
    {
        $this->load->library(
            'Recruitment/Recruitment_document_short_list_lib', 
            null, 
            'Recruitment_document_short_list_lib'
        );
       
        $this->Recruitment_document_short_list_lib->download(
            $job_id, 
            $candidate_id
        );
    }

    public function detail_rs_process($process_id, $candidate_id)
	{
	    $this->load->library(
			'Component/Recruitment_candidate/Recruitment_candidate_process_detail_loader'
		);
		
		$params = [
    		'process_id' => $process_id,
    		'candidate_id' => $candidate_id,  
		];
		$this->recruitment_candidate_process_detail_loader->render($params);	  
	}
}
