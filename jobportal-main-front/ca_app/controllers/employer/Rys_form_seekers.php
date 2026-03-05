<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rys_form_seekers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    
        //Load models
        $this->load->model('Recruitment_candidate');
        $this->load->model('Rys_form');
        $this->load->model('Rys_form_seeker');
    }

    public function assign_form()
    {  
        $job_id = $this->input->post('job_id');
        $form_id = $this->input->post('form_id');
        $stage = $this->input->post('stage');
        $send_type = $this->input->post('send_type');

        $form = $this->Rys_form->get_form_by_id($form_id);

        if (!$form) {
            return false;
        }   

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        if (!$job) {
            return false;
        }

        $candidates = $this->Recruitment_candidate->get_available_candidates_by_stage(
            $job_id,
            $stage
        );

        if (!$candidates) {
            return false;
        }

        foreach ($candidates as $candidate) {
            
            $assignment_id = 0;

            $seeker_form = $this->Rys_form_seeker->get_assignment_by(
                $form->form_id, 
                $job_id, 
                $candidate->ID
            );

            if ($seeker_form && 
                $seeker_form->active &&
                $send_type == 2) {
                continue;
            }

            if ($seeker_form) {
                $assignment_id = $seeker_form->assignment_id;

                $this->db->where('assignment_id', $assignment_id);
                $this->db->update('tbl_rys_form_seekers', [
                    'active' => 1
                ]);

            } else {
                $this->db->insert('tbl_rys_form_seekers', [
                    'form_id' => $form->form_id,
                    'seeker_id' => $candidate->ID,
                    //'job_id' => $job_id,
                    'stage' => $stage,
                    'assignment_date' => date('Y-m-d H:i:s')
                ]);
                $assignment_id = $this->db->insert_id();
            }

            if ($seeker_form && 
                $seeker_form->answered) {
                continue;
            }

            $data_email = [
                'name' => $candidate->first_name,
                'job' => $job,
                'url_link' => site_url('jobseeker/forms/answer/' . $assignment_id)  
            ];

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($candidate->email);

            $mail_message = load_email_view('email/notify_sending_rys_form_to_seeker', $data_email);

            $this->email->subject('RyS - ' . $form->name);
            $this->email->message($mail_message);     
            //Send email
            $this->email->send();
        }
    }

    public function unassign_form()
    {
        $job_id = $this->input->post('job_id');
        $form_id = $this->input->post('form_id');
        $stage = $this->input->post('stage');

        $seekers = $this->Recruitment_candidate->get_available_candidates_by_stage(
            $job_id,
            $stage
        );

        $assignment_ids = [];

        foreach ($seekers as $seeker) {

            $seeker_form = $this->Rys_form_seeker->get_assignment_by(
                $form_id, 
                $job_id, 
                $seeker->ID
            );

            if ($seeker_form) {
                $assignment_ids[$seeker_form->assignment_id] = $seeker_form->assignment_id;
            }
        }

        if (count($assignment_ids) > 0) {

            $this->db->where('answered', 0);
            $this->db->where_in('assignment_id', $assignment_ids);
            $this->db->update('tbl_rys_form_seekers', [
                'active' => 0
            ]);
        }

        echo json_encode([
            'assign_rows' => $this->Rys_form_seeker->count_sekeer_form_assigned($job_id, $form_id, $stage)
        ]);
    }

    public function edit_answers()
    {
        $id = $this->input->post('id');
        $job_id = $this->input->post('job_id');

        $this->db->where('assignment_id', $id);
        $trans_status = $this->db->update('tbl_rys_form_seekers', [
            'edit_answers' => 1
        ]);
        
        $this->notify_to_seeker_by_email($id, $job_id);

        echo json_encode([
            'success' => $trans_status
        ]);
    }

    public function notify_to_seeker()
    {
        $assignment_id = $this->input->post('id');
        $job_id = $this->input->post('job_id');

        $this->notify_to_seeker_by_email($assignment_id, $job_id);
       
        echo json_encode([
            'success' => true
        ]);
    }

    private function notify_to_seeker_by_email($assignment_id, $job_id)
    {
        $seeker_form = $this->db->get_where('tbl_rys_form_seekers', [
            'assignment_id' => $assignment_id
        ])->row();

        $form = $this->Rys_form->get_form_by_id($seeker_form->form_id);

        $job = $this->Posted_job->get_posted_job_by_id($job_id);

        $seeker = $this->Job_seeker->get_job_seeker_by_id($seeker_form->seeker_id);

        $data_email = [
            'name' => $seeker->first_name,
            'job' => $job,
            'form' => $form,
            'url_link' => site_url('jobseeker/forms/answer/' . $seeker_form->assignment_id)  
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($seeker->email);

        $mail_message = load_email_view('email/notify_sending_rys_form_to_seeker', $data_email);

        $this->email->subject('RyS - ' . $form->name);
        $this->email->message($mail_message);     
        
        //Send email
        return $this->email->send();
    }
}
