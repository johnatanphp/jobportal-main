<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Overall_emails extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Exam_request_overall_email');
    }

    public function get_emails()
    {
        $emails = $this->Exam_request_overall_email->all();
        $data['emails'] = $emails;
     
        $this->load->view(
            'employer/exam_requests/overall_emails/common/save_emails', 
            $data
        );
    }

    public function save()
    {
        $emails = $this->input->post('emails');
        
        $this->Exam_request_overall_email->delete([
            '1' => '1'
        ]);

        foreach ($emails as $email) {
            $data = [
                'email' => trim($email)
            ];
            $this->Exam_request_overall_email->create($data);
        }

        echo json_encode([
            'success' => true
        ]);
    }
}
