<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rys_form_affidavit_seekers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Rys_form');
        $this->load->model('Form_question');
    }

    public function export_pdf($assignment_id, $job_id)
    {
        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        $form = $this->Rys_form->get_form_by_id($assignment_form->form_id);
        $form_questions = $this->Rys_form->get_questions_by_form_id($form->form_id);
        $jobseeker = $this->Job_seeker->get_job_seeker_by_id($assignment_form->seeker_id);
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

        $data = [
            'form' => $form,
            'form_questions' => $form_questions,
            'assignment_form' => $assignment_form,
            'jobseeker' => $jobseeker,
            'job' => $job,
            'staff_request' => $staff_request
        ];

        $template = $this->load->view('jobseeker/question_forms/partials/form_affidavit', $data, true);

        $this->load->library('Mpdf/mpdf_lib');
        $this->mpdf_lib->SetDisplayMode('fullpage');
        $this->mpdf_lib->WriteHTML($template);
        $filename = $jobseeker->document_number . ' - ' . $jobseeker->last_name . ' ' . $jobseeker->first_name . '-' . $job->ID . '.pdf';
        $this->mpdf_lib->Output($filename, 'D');   
    }

    public function __export_pdf($assignment_id, $filename )
    {
        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        $form = $this->Rys_form->get_form_by_id($assignment_form->form_id);
        $form_questions = $this->Rys_form->get_questions_by_form_id($form->form_id);
        $jobseeker = $this->Job_seeker->get_job_seeker_by_id($assignment_form->seeker_id);
        $job = $this->Posted_job->get_posted_job_by_id($assignment_form->job_id);
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

        $data = [
            'form' => $form,
            'form_questions' => $form_questions,
            'assignment_form' => $assignment_form,
            'jobseeker' => $jobseeker,
            'job' => $job,
            'staff_request' => $staff_request
        ];


        $template = $this->load->view('jobseeker/question_forms/partials/form_affidavit', $data, true);

        $this->load->library('Mpdf/mpdf_lib');
        //$this->mpdf_lib->SetDisplayMode('fullpage');
        $this->mpdf_lib->WriteHTML($template);
    
        $this->mpdf_lib->Output('/home/jcanache/respaldos/' . $filename .'.pdf', 'F');

    }

    public function html_masivo($assignment_id)
    {
        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        $form = $this->Rys_form->get_form_by_id($assignment_form->form_id);
        $form_questions = $this->Rys_form->get_questions_by_form_id($form->form_id);
        $jobseeker = $this->Job_seeker->get_job_seeker_by_id($assignment_form->seeker_id);
        $job = $this->Posted_job->get_posted_job_by_id($assignment_form->job_id);
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

        $data = [
            'form' => $form,
            'form_questions' => $form_questions,
            'assignment_form' => $assignment_form,
            'jobseeker' => $jobseeker,
            'job' => $job,
            'staff_request' => $staff_request
        ];

        return $this->load->view('jobseeker/question_forms/partials/form_affidavit', $data, true);
    }

    public function masivo($job_id = 1, $stage = 7)
    {
        ini_set("pcre.backtrack_limit", "2000000");

        $this->db->select([
            'job.ID as job_id',
            'job.job_title',
            'fs.assignment_id'
        ]);
        $this->db->from('tbl_rys_form_seekers fs');
        $this->db->join('tbl_recruitment_candidates rc', 'fs.seeker_id=rc.seeker_ID AND fs.job_id=rc.job_ID');
        $this->db->join('tbl_post_jobs job', 'fs.job_id=job.ID');
        
        $this->db->where('fs.form_id', 1);
        $this->db->where('fs.answered', 1);
        $this->db->where('fs.job_id', $job_id);
        $this->db->where('rc.stage', $stage);
        
        $result = $this->db->get()->result();
       
        if (empty($result)) {
            echo 'No candidatos';
            return;
        }
        
        $template = '';

        foreach ($result as $a) {
            $template.= $this->html_masivo($a->assignment_id) . ' <pagebreak />';
        }

        $this->load->library('Mpdf/mpdf_lib');
        $this->mpdf_lib->SetDisplayMode('fullpage');
        $this->mpdf_lib->WriteHTML($template);
        
        $f = FCPATH . 'public/uploads/tmp/DJ-Postulantes-' . $a->job_id . '-' . $a->job_title . '.pdf';

        $this->mpdf_lib->Output($f, 'F');

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to('jesuscanache2017@gmail.com');

    
        $this->email->subject('Declaracion-Jurada-Postulantes-' . $a->job_id . '-' . $a->job_title . '-' . time());
        $this->email->message('Declaracion-Jurada-' . $a->job_title);     
                
        $this->email->attach(
           $f, 
        'attachment' 
        );

        $this->email->send();
    }
}
