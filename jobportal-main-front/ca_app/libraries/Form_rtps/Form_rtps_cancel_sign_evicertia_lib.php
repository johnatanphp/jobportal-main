<?php
class Form_rtps_cancel_sign_evicertia_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct(){}

    public function send($form_rtps_id)
    {
        $form_rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'ID' => $form_rtps_id
        ])->row();

        if (!$form_rtps) {
            return false;
        }

        if ($form_rtps->evicertia_status != 2) {
            return false;
        }

        $job = $this->Posted_job->find($form_rtps->job_id);
       
        if (!$job) {
            return false;
        }

        $staff_request = $this->Staff_request->find($job->request_ID);
        
        if (!$staff_request) {
            return false;
        }

        $seeker = $this->Job_seeker->find($form_rtps->seeker_ID);
        
        if (!$seeker) {
            return false;
        }

        $cia_code = $staff_request->no_cia;
        
        $parameters = [
            'uniqueid' => $form_rtps->evicertia_unique_id,
            'comments' =>  "Cancelado por errores en el documento",
            'Quietly' => 0
        ];

        $curl_cancel = new \Curl\Curl();
        $curl_cancel->setHeader('Content-Type', 'application/json');

        if ($cia_code == 54) {
            $curl_cancel->setHeader('Authorization', 'Basic ' . $this->config->item('evicertia_api_key_54'));
        } else {
            $curl_cancel->setHeader('Authorization', 'Basic ' . $this->config->item('evicertia_api_key'));
        }

        $curl_cancel->post($this->config->item('evicertia_api_url') . 'EviSign/Cancel', $parameters);

        if ($curl_cancel->error) {
            $data = [
            	'evicertia_error' => $curl_cancel->errorMessage,
                'evicertia_data_log' => json_encode(['data' => $parameters, 'response' => $curl_cancel->response])
            ];
		
        	$this->db->where('ID', $form_rtps->ID);
			$this->db->update('tbl_seeker_form_rtps', $data);
  			
            return false;
        }

        $evicertia_response = $curl_cancel->response->uniqueId;
        
        if (!$evicertia_response) {
            $data = [
            	'evicertia_error' => 'Error al cancelar la firma',
                'evicertia_data_log' => json_encode(['data' => $parameters, 'response' => $curl_cancel->response])
            ];
		
        	$this->db->where('ID', $form_rtps->ID);
			$this->db->update('tbl_seeker_form_rtps', $data);
  			
            return false;
        }

        $data =  [
            'form_rtps_file_path' => null,
            'evicertia_send_date' => null,
        	'evicertia_status' => 1,
        	'evicertia_unique_id' => null,
        	'evicertia_url_push_notification' => null,
            'evicertia_data_log' => json_encode(['data' => $parameters, 'response' => $curl_cancel->response]),
            'evicertia_error' => null
        ];

        $this->db->where('ID', $form_rtps->ID);
        $this->db->update('tbl_seeker_form_rtps', $data);

        return true;
    }
}
