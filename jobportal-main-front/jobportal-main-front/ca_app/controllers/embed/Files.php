<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Files extends CI_Controller 
{    
    public function __construct()
    {
        parent::__construct();
    }

    public function send_to_email()
    {
        $description = $this->input->post('description');
        $url = $this->input->post('url');
        $seeker_id = $this->input->post('seeker_id');

        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            echo json_encode([
                'success' => false,
                'message' => 'Postulante no encontrado'
            ]);
            return;
        }

        $data_email = [
            'seeker' => $seeker,
            'file_url' => $url,
            'description' => $description
        ];
        
        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($seeker->email);
        
        $mail_message = load_email_view('email/embed_file_url', $data_email);
        
        $this->email->subject('Enlace Descarga ' . $description);
        $this->email->message($mail_message);     
        $this->email->send();

        echo json_encode([
            'success' => true,
            'message' => 'Enlace de descarga enviado a ' . $seeker->email
        ]);
    }
}
