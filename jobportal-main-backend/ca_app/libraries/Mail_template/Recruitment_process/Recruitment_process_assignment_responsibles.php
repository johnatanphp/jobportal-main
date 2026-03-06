<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recruitment_process_assignment_responsibles 
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function build($params = [])
	{
        // Obtener Plantilla
        $this->db->where('id', 2);
        $template_email = $this->db->get('tbl_email_applicant_phase')->row();

        if (!$template_email) {
            return [];
        }

        $body = str_replace([
            '{{employer_name}}',
            '{{job_title}}',
            '{{url_link}}',
        ],
        [
            $params['employer_name'] ?? 'Estimado/a',
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
