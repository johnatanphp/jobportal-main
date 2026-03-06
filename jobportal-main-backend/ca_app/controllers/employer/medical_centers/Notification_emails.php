<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Notification_emails extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load Models
        $this->load->model('Medical_center_email');
    }

    public function get_emails()
    {
        $mccode = $this->input->get('mc_code');

        if (empty($mccode)) {
            show_404();
        }

        $emails = $this->Medical_center_email->all([
            'code' =>  $mccode
        ]);

        $data['emails'] = $emails;
        $data['code'] = $mccode;

        $this->load->view(
            'employer/medical_centers/notification_emails/common/save_emails', 
            $data
        );
    }

    public function save()
    {
        $emails = $this->input->post('emails');
        $mccode = $this->input->post('code');

        if (empty($mccode)) {
            show_404();
        }
        
        $this->Medical_center_email->delete([
            'code' => $mccode
        ]);

        foreach ($emails as $email) {
            $data = [
                'code' => $mccode,
                'email' => trim($email)
            ];
            $this->Medical_center_email->create($data);
        }

        echo json_encode([
            'success' => true
        ]);
    }
}
