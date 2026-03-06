<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_interviews extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		//Load models
		$this->load->model('Candidate_interview');
    	
    	//Load libraries
    	$this->load->library('aws_sns_client_lib'); 
    }

	public function schedule()
	{
		$job_id = $this->input->post('job_id');
		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		if (!$job) {
			show_404();
		}

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }

		$this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
		$this->form_validation->set_rules('candidate_id', 'Candidato', 'trim|required|strip_all_tags');
		//$this->form_validation->set_rules('responsible_employer_id', 'Empleador responsable', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('date', 'Fecha entrevista', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('hour', 'Hora entrevista', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('place', 'Lugar de la entrevista', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('additional_comment', 'Comentario adicional', 'trim|strip_all_tags');
		
		if ($this->form_validation->run() === FALSE) {
			
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos incorrectos'
			));

			return;
		}

		$all_input = $this->input->post();

        $staff_request = $this->Staff_request->find($job->request_ID);

        $all_input['responsible_employer_id'] = $staff_request && $staff_request->employer_ID ? 
                                                $staff_request->employer_ID : 
                                                $this->session->userdata('user_id');

		$trans_status = $this->Candidate_interview->schedule_interview(
			$all_input
		);

		if ($trans_status) {
			$candidate_id = $this->input->post('candidate_id');
			$this->notify_interview_candidate($job_id, $candidate_id);
		}

		echo json_encode(array(
			'success' => $trans_status
		));
	}

	public function modal_interview($job_id, $candidate_id)
	{
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

		$data['candidate_interview'] = $this->Candidate_interview->get_scheduled_interview(
			$job_id, 
			$candidate_id
		);

		$job = $this->Posted_job->get_posted_job_by_id($job_id);

		//$staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

		$data['job_id'] = $job_id;
		$data['candidate_id'] = $candidate_id;
		//$data['staff_request'] = $staff_request;

		$this->load->view('employer/recruitment/modal/candidate_schedule_interview', $data);
	}

	public function modal_interview_comments($job_id, $candidate_id)
	{
		$data['interview'] = $this->Candidate_interview->get_scheduled_interview(
			$job_id, 
			$candidate_id
		);
	
		$data['job_id'] = $job_id;
		$data['candidate_id'] = $candidate_id;
	
		$this->load->view('employer/recruitment/modal/candidate_interview_comments', $data);
	}

	public function save_comments()
	{
		$this->form_validation->set_rules('job_id', 'Empleo', 'trim|required');
		$this->form_validation->set_rules('candidate_id', 'Candidato', 'trim|required|strip_all_tags');
		$this->form_validation->set_rules('interview_comments', 'Comentario', 'trim|required|strip_all_tags');
		
		if ($this->form_validation->run() === FALSE) {
			
			echo json_encode(array(
				'success' => false,
				'message_error' => 'Hay datos incorrectos'
			));

			return;
		}

		$interview_comments = $this->input->post('interview_comments');
		$job_id = $this->input->post('job_id');
		$candidate_id = $this->input->post('candidate_id');

		$trans_status = $this->Candidate_interview->save_comments(
			$job_id,
			$candidate_id,
			$interview_comments
		);

		echo json_encode(array('success' => $trans_status));
	}

	public function load_interview($job_id, $candidate_id)
	{
		$data['candidate_evaluations'] = $this->Candidate_interview->get_scheduled_interview(
			$job_id, 
			$candidate_id
		);
	
		$this->load->view('employer/recruitment/common/candidate_interview', $data);
	}

	public function send_notification_interview()
	{
		$job_id = $this->input->post('job_id');
		$candidate_id = $this->input->post('candidate_id');

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
            exit;
        }
		
		$this->notify_interview_candidate($job_id, $candidate_id);

		echo json_encode(array('success' => true));			
	}

	private function notify_interview_candidate($job_id, $candidate_id)
    {
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
     	
     	$candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);

     	$interview = $this->Candidate_interview->get_scheduled_interview(
			$job_id, 
			$candidate_id
		);

		$responsible_employer = $this->Employer->get_employer_by_id($interview->responsible_employer_ID);

        $data_email = array(
            'name' => $candidate->first_name,
            'job' => $job,
            'interview' => $interview,
            'responsible_employer' => $responsible_employer  
        );

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($candidate->email);

        $mail_message = load_email_view('email/recruitment_candidate_interview', $data_email);

        $this->email->subject('Entrevista programada');
        $this->email->message($mail_message);     
        //Send email
        $this->email->send();

        if (!$candidate->mobile) {
            return;
        }

        try {
            //Send text message
            $sms_text = "Hola " . $candidate->first_name . ", la empresa " . 
            $responsible_employer->company_name . " te ha programado una entrevista para la fecha: " . 
            format_date($interview->date, 'd/m/Y') . ", hora: " . 
            format_date($interview->hour, 'h:i a') . ", lugar: " . 
            $interview->place . ", puesto: " . $job->job_title . ". Portal empleo Overall.";

            $this->aws_sns_client_lib->send_text_message($sms_text, $candidate->mobile);

        } catch (Exception $e) {}
    }

    public function video()
    {
        $seeker_ids = $this->input->get('candidate_ids');
        $job_id = $this->input->get('job_id');
        $massive = $this->input->get('massive');

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

        if ($massive == 0) {
        	$video_interview = $this->db->get_where(
        		'tbl_recruitment_interview_videos', [
        			'job_id' => $job_id,
        			'seeker_id' => $seeker_ids[0],
        	])->row();

            $data['video_interview'] = $video_interview;
        }

        $this->db->from('tbl_recruitment_interview_indications');
        $this->db->where('job_id', $job_id);

        $rv_indications = $this->db->get()->row();

        $data['rv_indications'] = $rv_indications;
    	$data['job_id'] = $job_id;
    	$data['seeker_ids'] = $seeker_ids;
    	$data['massive'] = $massive;

    	$this->load->view(
    		'employer/recruitment/modal/candidate_video',
    		$data
    	);
    }

    public function request_video()
    {
    	$seeker_ids = $this->input->post('seeker_ids');
    	$job_id = $this->input->post('job_id');
    	$indications = $this->input->post('indications');
 
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

        foreach ($seeker_ids as $seeker_id) {

            $request_video = $this->db->get_where('tbl_recruitment_interview_videos', [
                'job_id' => $job_id,
                'seeker_id' => $seeker_id
            ])->row();

            if ($request_video && $request_video->video_path) { 
                continue;
            }
            
            $key_access = $request_video ? $request_video->key_access : create_token(50);
            $share_token = $request_video ? $request_video->share_token : create_token(60);

            if (!$request_video) {   
                $data_request = [
                    'job_id' => $job_id,
                    'seeker_id' => $seeker_id,
                    'indications' => $indications,
                    'key_access' => $key_access,
                    'share_token' => $share_token
                ];
                $this->db->insert('tbl_recruitment_interview_videos', $data_request);
            } else {
                $data_request = [
                    'indications' => $indications,
                ];

                $this->db->where('id', $request_video->id);
                $this->db->update('tbl_recruitment_interview_videos', $data_request);
            }

            $seeker = $this->Job_seeker->get_job_seeker_by_id($seeker_id);

            $data_email['seeker'] = $seeker;
            $data_email['job'] = $job;
            $data_email['employer'] = $obj_employer;
            $data_email['url_link'] = site_url('general/jobseeker/record_video/record/' . $key_access);

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($seeker->email);

            $mail_message = load_email_view(
                'email/recruitment_request_video_seeker', 
                $data_email
            );

            $this->email->subject('Solicitud de video - ' . $obj_employer->company_name);
            $this->email->message($mail_message);     
            //Send email
            $email_status = $this->email->send();
        }
    	
        echo json_encode([
        	'success' => true
        ]);
    }

    public function share_record_video()
    {   
        $job_id = $this->input->get('job_id') ? 
                  $this->input->get('job_id') : 
                  $this->input->post('job_id');

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

    	if (!$this->input->post()) {

            $seeker_ids = $this->input->get('candidate_ids');
            
            $massive = $this->input->get('massive');

            $this->db->from('tbl_recruitment_interview_videos');
            $this->db->where_in('seeker_id', $seeker_ids);
            $this->db->where('job_id', $job_id);
            $this->db->where('video_path!=', '');

            $count_share_links = $this->db->count_all_results();

            $data['seeker_ids'] = $seeker_ids;
            $data['job_id'] = $job_id;
            $data['massive'] = $massive;
            $data['count_share_links'] = $count_share_links;

    		$this->load->view('employer/recruitment/modal/rys_share_video_email', $data);
    		return;
    	}

        $job_id = $this->input->post('job_id');
        $seeker_ids = $this->input->post('seeker_ids');

        $this->db->select([
            'seekers.*',
            'interview_videos.video_path',
            'interview_videos.share_token'
        ]);
        $this->db->from('tbl_job_seekers seekers');
        $this->db->join('tbl_recruitment_interview_videos interview_videos', 'interview_videos.seeker_id=seekers.ID');

        $this->db->where('interview_videos.job_id', $job_id);
        $this->db->where('interview_videos.video_path!=', '');
        $this->db->where_in('interview_videos.seeker_id', $seeker_ids);

        $seeker_videos = $this->db->get()->result();

    	$data_email = [
    		'seeker_videos' => $seeker_videos,
    		'job' => $job,
            'employer' => $obj_employer
    	];

    	$config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($this->input->post('emails'));

        $mail_message = load_email_view(
            'email/recruitment_video_to_share',
        	$data_email
        );

        $this->email->subject('Grabación Video Postulantes - ' . $obj_employer->company_name . ' - ' . $job->job_title);
        $this->email->message($mail_message);     
        //Send email
        $email_status = $this->email->send();

        echo json_encode([
        	'success' => $email_status
        ]);
    }

    public function remove_video()
    {
        $this->load->library('storage_lib', null, 'Storage_lib');

        $video = $this->db->get_where('tbl_recruitment_interview_videos', [
            'id' => $this->input->post('id')
        ])->row();

        if (!$video) {
            show_404();
        }

        $job = $this->Posted_job->get_posted_job_by_id($video->job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

        $status = true;
        
        try {
            $this->Storage_lib->delete($video->video_path);
        } catch (Exception $e) {}

        //if ($status) {
            $this->db->where('id', $video->id);
            $this->db->update('tbl_recruitment_interview_videos', [
                'video_path' => '',
                'qualification' => null,
                'comment' => null
            ]);
        //}

        echo json_encode([
            'success' => $status ? true : false,
            'seeker_id' => $video->seeker_id
        ]);
    }

    public function record_video_indications($job_id)
    {
        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            show_404();
        }

        $obj_employer = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        if ($obj_employer->company_ID != $job->company_ID) {
            show_404();
        }

        $this->db->from('tbl_recruitment_interview_indications');
        $this->db->where('job_id', $job_id);

        $rv_indications = $this->db->get()->row();

        if (!$this->input->post()) {

            $data['job_id'] = $job_id;
            $data['rv_indications'] = $rv_indications;

            $this->load->view('employer/recruitment/modal/interview_video_indications', $data);

            return;
        }

        if ($rv_indications) {
            $this->db->where('job_id', $job_id);
            $this->db->update('tbl_recruitment_interview_indications', [
                'video_indications' => $this->input->post('indications')
            ]);
        } else {
            $this->db->insert('tbl_recruitment_interview_indications', [
                'job_id' => $job_id,
                'video_indications' => $this->input->post('indications'),
            ]);
        }

        echo json_encode([
            'success' => true
        ]);
    }    
}
