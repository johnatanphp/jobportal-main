<?php 
class Exam_request_update_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function run($ref_ids)
    {
        $api_url = $this->config->item('hrm_api_url') . '/programacion_examen/busqueda';
    
        $data = [
            'api_key' => $this->config->item('hrm_api_key'),
            'ref_id' => $ref_ids
        ];

        $curl_envio = new \Curl\Curl();
        $curl_envio->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl_envio->setHeader('Content-Type', 'application/json');
        $curl_envio->get($api_url, $data);

        $response =  $curl_envio->response;

        if (!$response) {
            return;
        }

        if (!$response->success) {
            return $response->message;
        } 

        $exams = $response->data->programaciones;

        foreach ($exams as $exam) {

            $status = [
                1 => 1, //Programada
                2 => 3, //Enviada
                3 => 9, //Confirmada
                4 => 5, //Asitio
                5 => 6, //No asistio
                6 => 2, //Asignada
                7 => 7 //Cancelada
            ];
            
            $schedule = $this->db->get_where('tbl_exam_request_seekers', [
                'id' => $exam->ref_id
            ])->row();

            if (!$schedule) {
                continue;
            }

            if (!isset($status[$exam->estado_id])) {
                continue;
            }
            
            $data_update['status'] = $status[$exam->estado_id];
            
            $data_update['exam_date'] = $exam->fecha;
            $data_update['exam_time'] = $exam->hora;
            $data_update['medical_center_code'] = $exam->proveedor_codigo;                
            $data_update['medical_center_name'] = $exam->proveedor_nombre;    
            $data_update['medical_center_location_code'] = $exam->proveedor_sede_id;
            $data_update['medical_center_location_name'] = $exam->proveedor_sede_nombre;
            $data_update['medical_center_location_address'] = $exam->proveedor_sede_direccion;

            $this->db->where('id', $schedule->id);
            $this->db->update('tbl_exam_request_seekers', $data_update);

            $afftected_rows = $this->db->affected_rows();

            if ($afftected_rows > 0 && $schedule->exam_type_id == 1 && in_array($schedule->status, [9, 5, 6])) {
                $this->db->where('id', $schedule->id);
                $this->db->update('tbl_exam_request_seekers', [
                    'notify_recruiter' => 1
                ]);
            }

            if (!$exam->resultado_texto) {
                $this->db->where('exam_request_seeker_id', $schedule->id);
                $this->db->delete('tbl_exam_request_results');
                continue;
            } 
        
            $result_row = $this->db->get_where('tbl_exam_request_result_types', [
                'exam_type_id' => $schedule->exam_type_id,
                'result_name' => $exam->resultado_texto
            ])
            ->row();

            if (!$result_row) {
                continue;
            }

            $data_results = [
                'exam_request_seeker_id' => $schedule->id,
                'result_file' => $exam->resultado_archivo_url,
                'created_at' => date('Y-m-d H:i:s'),
                'result_status' => $result_row->id,
                'observations' => $exam->resultado_observaciones
            ];

            $this->db->select('id');
            $this->db->from('tbl_exam_request_results');
            $this->db->where('exam_request_seeker_id', $schedule->id);
            $exam_result_row = $this->db->get()->row();

            if ($exam_result_row) {
                unset($data_results['created_at']);
                $this->db->where('id', $exam_result_row->id);
                $this->db->update('tbl_exam_request_results', $data_results); 
            }

            if (!$exam_result_row) {
                $this->db->insert('tbl_exam_request_results', $data_results);
            }           
        }        
    }
}
