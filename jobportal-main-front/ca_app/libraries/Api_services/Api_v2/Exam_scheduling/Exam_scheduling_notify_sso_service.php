<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_scheduling_notify_sso_service
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return apiv2_response(
                false,
                $message
            );
        }
     
        return $this->notify($params);  
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('id[]', 'id', 'required|integer|greater_than[0]');
        //$this->form_validation->set_rules('user_id', 'user_id', 'required|integer|greater_than[0]');
      
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        $ids = $params['id'] ?? [];
        $max_exam_sheduling = 10;

        if (count($ids) > $max_exam_sheduling) {
            return [false, 'Maximo de programaciones a enviar por envio es de ' . $max_exam_sheduling];
        }

        return [true, 'OK'];
    }

    public function notify($params)
    {
        $this->load->library('Exam_request/Exam_request_send_sso_lib');
        //dd($params);
        $ids = $params['id'];
        
        $result = $this->exam_request_send_sso_lib->send([
            'ids' => $ids,
            'send_email_sso' => $params['send_email_sso'] ? 1 : 0,
            'sent_by_employer_id' => $this->session_employer_lib->get_data('user_id')
        ]);
        
        list($is_success, $message, $data) = array_pad($result, 3, []);
        
        return apiv2_response($is_success, $message, $data);
    }
}
