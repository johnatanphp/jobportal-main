<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Forms extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Rys_form');
        $this->load->model('Rys_form_seeker');
        $this->load->model('Form_question');
        $this->load->model('Rys_form_question');  
    }

    public function answer($assignment_id)
    {
        $data['ads_row'] = $this->ads;

        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        if (!$assignment_form || $assignment_form->active == 0) {
            show_404();
        }

        $seeker_id = $this->session->userdata('user_id');

        if ($assignment_form->seeker_id != $seeker_id) {
            show_404();
        }

        if ($assignment_form->answered && 
            $assignment_form->edit_answers == 0) {
            redirect('jobseeker/forms/show/' . $assignment_id);
        }

        $form = $this->Rys_form->get_form_by_id($assignment_form->form_id);

        if (!$form) {
            show_404();
        }

        $jobseeker = $this->Job_seeker->get_job_seeker_by_id($seeker_id);
        
        $form_questions = $this->Rys_form_question->get_active_by_form_id($assignment_form->form_id);

        $data['title'] = 'Responder Encuesta - ' . SITE_NAME;
        $data['form'] = $form;
        $data['form_questions'] = $form_questions;
        $data['jobseeker'] = $jobseeker;
        $data['assignment_form'] = $assignment_form;

        $this->load->view('jobseeker/question_forms/answer_view', $data);
    }

    public function do_answer()
    {
        $this->db->trans_start();

        $assignment_id = $this->input->post('assignment_id');

        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        if (!$assignment_form || $assignment_form->active == 0) {
            show_404();
        }

        $seeker_id = $this->session->userdata('user_id');

        if ($assignment_form->seeker_id != $seeker_id) {
            show_404();
        }

        if (!$assignment_form || (
            $assignment_form->answered &&
            $assignment_form->edit_answers == 0)
        ) {
            show_404();
        }

        $form_id = $assignment_form->form_id;
        $job_id = $assignment_form->job_id;

        $this->db->where('assignment_id', $assignment_id);
        $this->db->delete('tbl_rys_form_seeker_answers');

        $questions = $this->input->post('form_question');
   
        foreach ($questions as $question_id => $row) {

            $row_question =  $this->db->get_where('tbl_rys_form_questions', ['question_id' => $question_id])->row();

            $value = is_array($row) ? json_encode($row) : $row;

            if ($row_question->type == 'file') {
               $value = $this->attach_file($assignment_id, $question_id);
            }

            if (trim($value) == "" && $row_question->type == 'file') {
                $value = $row;
            }

            $data = [
                'assignment_id' => $assignment_id,
                'form_id' => $form_id,
                'job_id' => $job_id,
                'question_id' => $question_id,
                'seeker_id' => $seeker_id,
                'answer' => $value
            ];
            $this->db->insert('tbl_rys_form_seeker_answers', $data);
        }
        
        if ($form_id == 2) {

            $data = [
                'assignment_id' => $assignment_id,
                'form_id' => $form_id,
                'job_id' => $job_id,
                'question_id' => 72,
                'seeker_id' => $seeker_id,
                'answer' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('tbl_rys_form_seeker_answers', $data);
        }

        $this->db->where('assignment_id', $assignment_id);
        $this->db->update('tbl_rys_form_seekers', [
            'answered' => 1,
            'answer_date' => date('Y-m-d H:i:s'),
            'edit_answers' => 0,
            'affidavit_accept' => 0,
            'affidavit_date' => null
        ]);

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        if ($trans_status === true) {
            flash_message('success', '<b>¡Muy bien!</b> Las Respuestas fueron enviadas correctamente.');
            redirect('jobseeker/forms/show/' . $assignment_id);
        } else {
            flash_message('danger', '<b>¡Valla!</b> Las respuestas no se pudieron enviar, por favor vuelve a intentar.');
            redirect('jobseeker/forms/answer/' . $assignment_id);
        }
    }

    private function attach_file($assignment_id, $question_id)
    {
        $file_tmp = isset($_FILES['form_question']['tmp_name'][$question_id]) ? trim($_FILES['form_question']['tmp_name'][$question_id]) : '';

        if ($file_tmp == '') {
            return "";
        }

        $file_name = isset($_FILES['form_question']['name'][$question_id]) ? trim($_FILES['form_question']['name'][$question_id]) : '';

        if ($file_name == '') {
            return "";
        }

        $file_type = $_FILES['form_question']['type'][$question_id];
        $file_size = $_FILES['form_question']['size'][$question_id];

        $allowed = [
            'text/plain',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'image/gif', 
            'image/png', 
            'image/jpeg', 
            'image/bmp', 
            'image/webp',
            'audio/midi', 
            'audio/mpeg', 
            'audio/webm', 
            'audio/ogg', 
            'audio/wav',
            'video/webm', 
            'video/ogg',
            'video/mp4'
        ];

        if (!in_array($file_type, $allowed)) {
            return "";
        }

        if ($file_size > (2 * 1048576)) {
            return "";
        }

        try {
            $this->load->library('storage_lib');

            $file_ext = file_ext($file_name) != '' ? '.' . file_ext($file_name) : '';    
            $path_value = 'rys_forms/attached_file/' . md5(uniqid($assignment_id . '-' . $question_id, true)) . $file_ext;
            
            $path_value = $this->storage_lib->put($path_value, $file_tmp);

        } catch (\Exception $e) {
            $path_value = false;
        } 

        if ($path_value === false) {
            return "";
        }

        $this->storage_lib->setVisibility($path_value, 'public');

        return file_url($path_value);
    }

    public function show($assignment_id)
    {
        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);
        
        if (!$assignment_form || $assignment_form->active == 0) {
            show_404();
        }

        $seeker_id = $this->session->userdata('user_id');

        if ($assignment_form->seeker_id != $seeker_id) {
            show_404();
        }

        $form = $this->Rys_form->get_form_by_id($assignment_form->form_id);

        if (!$form) {
            show_404();
        }
        
        $jobseeker = $this->Job_seeker->get_job_seeker_by_id($seeker_id);

        $form_questions = $this->Rys_form_question->get_active_by_form_id($assignment_form->form_id);

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Respuestas formulario - ' . SITE_NAME;
        $data['form'] = $form;
        $data['form_questions'] = $form_questions;
        $data['jobseeker'] = $jobseeker;
        $data['assignment_form'] = $assignment_form;

        $this->load->view('jobseeker/question_forms/show_answers_view', $data);
    }

    public function accept_affidavit($assignment_id)
    {
        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        if (!$assignment_form || $assignment_form->active == 0) {
            show_404();
        }

        $seeker_id = $this->session->userdata('user_id');

        if ($assignment_form->seeker_id != $seeker_id || 
            $assignment_form->answered == 0) {
            show_404();
        }

        $form = $this->Rys_form->get_form_by_id($assignment_form->form_id);

        if (!$form) {
            show_404();
        }
        
        $jobseeker = $this->Job_seeker->get_job_seeker_by_id($seeker_id);

        $form_questions = $this->Rys_form_question->get_active_by_form_id($assignment_form->form_id);

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Aceptar declaración jurada - ' . SITE_NAME;
        $data['form'] = $form;
        $data['form_questions'] = $form_questions;
        $data['jobseeker'] = $jobseeker;
        $data['assignment_form'] = $assignment_form;

        $this->load->view('jobseeker/question_forms/accept_affidavit_view', $data);
    }

    public function do_accept_affidavit()
    {
        $assignment_id = $this->input->post('assignment_id');

        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        if (!$assignment_form || $assignment_form->active == 0) {
            show_404();
        }
        
        $seeker_id = $this->session->userdata('user_id');

        if (!$assignment_form || 
            $assignment_form->seeker_id != $seeker_id ||
            $assignment_form->answered == 0 || 
            $assignment_form->affidavit_accept == 1) {
            show_404();
        }

        $this->db->where('assignment_id', $assignment_id);
        $trans_status = $this->db->update('tbl_rys_form_seekers', [
            'affidavit_accept' => 1,
            'affidavit_date' => date('Y-m-d H:i:s')
        ]);

        if ($trans_status === true) {
            flash_message('success', '<b>¡Muy bien!</b> Declaración jurada aceptada.');
            $this->notify_affidavit_seeker_by_email($assignment_id);
        } else {
            flash_message('danger', '<b>¡Valla!</b> La declaración jurada no pudo ser enviada.');
        }

        redirect('jobseeker/forms/accept_affidavit/' . $assignment_id);
    }

    private function notify_affidavit_seeker_by_email($assignment_id)
    {
        $assignment_form = $this->Rys_form_seeker->get_assignment($assignment_id);

        $form = $this->Rys_form->get_form_by_id($assignment_form->form_id);

        //$job = $this->Posted_job->get_posted_job_by_id($assignment_form->job_id);
        
        $seeker = $this->Job_seeker->get_job_seeker_by_id($assignment_form->seeker_id);

        $data_email = array(
            'name' => $seeker->first_name,
            //'job' => $job, 
            'form' => $form
        );

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($seeker->email);

        $mail_message = load_email_view('email/notify_rys_affidavit_seeker', $data_email);

        $this->email->subject('Aceptación de la declaración Jurada - ' . $form->name);
        $this->email->message($mail_message);     
        //Send email
        $this->email->send();
    }
}
