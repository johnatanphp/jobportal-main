<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff_request_create 
{
    private $data = [];

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function build($params)
    {
        // Obtener Plantilla
        // Plantilla notificar creacion solicitud
        $this->db->where('id', 1); 
        $template_email = $this->db->get('tbl_email_applicant_phase')->row();

        if (!$template_email) {
            return false;
        }
        
        $body = str_replace(
            [
                '{{recruitment_name}}',
                '{{request_id}}',
                '{{job_title}}',
                '{{url_link}}',
            ],
            [
                $params['recruiter_name'] ?? 'Estimado/a',
                $params['request_id'] ?? '',
                $params['job_title'] ?? '',
                $params['url_link'] ?? site_url('login')
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
