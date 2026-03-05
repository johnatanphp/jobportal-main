<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Candidate_registration 
{
    private $data = [];

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function build($params = [])
	{
        // Obtener Plantilla
        $this->db->where('id', 4);
        $template_email = $this->db->get('tbl_email_applicant_phase')->row();

        if (!$template_email) {
            return null;
        }

        $body = str_replace([
            '{{candidate_name}}',
            '{{url_link}}',
        ],
        [
            $params['candidate_name'],
            $params['url_link']
        ],       
            $template_email->body
        );

        //dd($body);
    
        $data_email = [
            'body' => $body
        ];
        
        return [
            'subject' => $template_email->subject,
            'content' => load_email_view('email/notify_stage_candidate', $data_email)
        ];
	}
}
