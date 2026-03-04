<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recruitment_process_add_candidate 
{
    private $data = [];

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function build($params = [])
	{
        // Obtener Plantilla
        $this->db->where('id', 5);
        $template_email = $this->db->get('tbl_email_applicant_phase')->row();

        if (!$template_email) {
            return null;
        }

        $body = str_replace([
            '{{candidate_name}}',
            '{{job_title}}',
            '{{url_link}}',
        ],
        [
            $params['candidate_name'],
            $params['job_title'],
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
