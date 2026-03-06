<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Templates extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function list_get()
    {
        $data[] = [
            'id' => '12',
            'name' => 'Solicitud documentos contratación',
            'channel' => 'whatsapp',
            'parameters' => [
                'candidate_id[]',
                'process_id'
            ]
        ];
        $this->response(apiv2_response(true, 'OK', $data), self::HTTP_OK);   
    }
    
    public function send_post()
    {
        $params = $this->post();
        
        $template_id = $params['template_id'] ?? 0;
        
        $this->db->from('tbl_email_applicant_phase');
        $this->db->where('id', $template_id);
        $this->db->where('id', 12);
        $row = $this->db->get()->row();
        
        if (!$row) {
            $this->response(apiv2_response(false, 'Plantilla no existe'), self::HTTP_BAD_REQUEST);
        }
        
        // if ($row->channel == 'email') {
        //     $this->load->library(
        //         'Api_services/Api_v2/Staff_request/Process_candidates_send_email_lib',
        //         null, 
        //         'Process_candidates_send_email_lib'
        //     ); 
    
        //     $this->response(
        //         $this->Process_candidates_send_email_lib->send($this->post()), 
        //         self::HTTP_OK
        //     );
        // }
        
        if ($row->channel == 'whatsapp') {
            $this->load->library(
                'Api_services/Api_v2/Staff_request/Process_candidates_send_whatsapp_lib',
                null, 
                'Process_candidates_send_whatsapp_lib'
            ); 
    
            $this->response(
                $this->Process_candidates_send_whatsapp_lib->send($this->post()), 
                self::HTTP_OK
            );
        }
    }
}
