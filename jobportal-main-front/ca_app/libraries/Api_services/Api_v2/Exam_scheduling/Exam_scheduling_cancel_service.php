<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_scheduling_cancel_service
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
     
        return $this->cancel($params);  
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('id[]', 'id[]', 'required');

      
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        $ids = $params['id'] ?? [];
        $max_exam_sheduling = 20;

        if (count($ids) > $max_exam_sheduling) {
            return [false, 'Maximo de programaciones a cancelar por envio es de ' . $max_exam_sheduling];
        }

        foreach ($ids as $exam_schedule_index => $exam_schedule_id) {
        
            $this->db->from('tbl_exam_request_seekers');
            $this->db->where('id', $exam_schedule_id);
            $exam_schedule =  $this->db->get()->row();

            if (!$exam_schedule) {
                return [
                    false, 
                    'EL registro ids[' . $exam_schedule_index . '] = '. $exam_schedule_id . ' no puede ser cancelado porque no existe'
                ];
            }

            if ($exam_schedule->active == 0 || $exam_schedule->status == 7) {
                return [
                    false, 
                    'EL registro ids[' . $exam_schedule_index . '] = '. $exam_schedule_id . ' no puede ser cancelado porque esta inactivo o cancelado'
                ];
            }

            if ($exam_schedule->status != 1) {
                return [
                    false, 
                    'EL registro ids[' . $exam_schedule_index . '] = '. $exam_schedule_id . ' no puede ser cancelado, solo se puede Cancelar programaciones en estado Programado RyS'
                ];
            }            
        }

        return [true, 'OK'];
    }

    public function cancel($params)
    {
        $ids = $params['id'];

        $this->db->where_in('id', $ids);
        $this->db->where('active', 1);
        $this->db->update('tbl_exam_request_seekers', [
            'status' => 7 //Cancelar
        ]);
      
        $this->load->library('Exam_request/Exam_request_cancel_lib');
        $this->exam_request_cancel_lib->run($ids);

        $data = [];
        foreach ($ids as $id) {
            $data[]['id'] = $id;
        }
    
        return apiv2_response(
            true,
            'Programaciones canceladas',
            $data
        );
    }
}
