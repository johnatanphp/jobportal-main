<?php
class Form_rtps_send_sign_evicertia_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __construct()
    {
    	$this->load->Library('Storage_lib', null, 'Storage_lib');
    }

    public function send($form_rtps_id)
    {
        $form_rtps = $this->db->get_where('tbl_seeker_form_rtps', [
            'ID' => $form_rtps_id
        ])->row();

        if (!$form_rtps) {
            return false;
        }

        if ($form_rtps->evicertia_status && in_array($form_rtps->evicertia_status, [2, 3])) {
            return false;
        }

        $job = $this->Posted_job->find($form_rtps->job_id);
       
        if (!$job) {
            return false;
        }

        $staff_request = $this->Staff_request->find($job->request_ID);
        
        if (!$staff_request) {
            echo "request bad";
            return false;
        }

        $seeker = $this->Job_seeker->find($form_rtps->seeker_ID);
        
        if (!$seeker) {
            return false;
        }

        $file_path = $this->get_file($form_rtps);

        if ($file_path === false) {
            echo "file bad";
            return false;
        }

        $archivo_base64 = base64_encode(file_get_contents('https://overall-portal-de-empleo.s3.amazonaws.com/' . $file_path));

        //$url_push = 'https://putsreq.com/1D2iDhubyR0IQVsnCTdH';
        $url_push = site_url('evicertia_notification/seeker_requested_documents/form_rtps');

        $cia_code = $staff_request->no_cia;
        $cia_name = $staff_request->consultant_name;
        $cost_center = str_replace('&', '', $staff_request->cost_center);
    
        $parameters = [
            'LookupKey' => trim($form_rtps->document_number) . '/' . $form_rtps->ID,
            'Issuer'    => $cia_name,
            'Subject'   => mb_strtoupper('DOCUMENTOS LABORALES'),
            "SigningParties" => [
                "Name" => mb_strtoupper($form_rtps->last_name . ' ' . $form_rtps->first_name),
                "Address" => trim($form_rtps->email),
                "SigningMethod" => "EmailPin",
                "EmailAddress" => trim($form_rtps->email),
                "LegalName" => $form_rtps->document_number
            ],
            'Document' => $archivo_base64,
            'InterestedParties' => [],//$this->get_interested($job_id),
            'Options' => [
                "CommitmentOptions" => "Accept",
                "EvidenceAccessControlMethod" => "Public",
                "CostCentre" => $cost_center,
                "TimeToLive" => '21600',
                "PushNotificationUrl" => $url_push,
                "PushNotificationFilter" => [
                    "Processed","Sent","Delivered","Signed",
                    "Rejected","FullySigned","Closed"
                ]
            ]
        ];

        $curl_envio = new \Curl\Curl();
        $curl_envio->setHeader('Content-Type', 'application/json');

        if ($cia_code == 54) {
            $curl_envio->setHeader('Authorization', 'Basic ' . $this->config->item('evicertia_api_key_54'));
        } else {
            $curl_envio->setHeader('Authorization', 'Basic ' . $this->config->item('evicertia_api_key'));
        }

        $curl_envio->post($this->config->item('evicertia_api_url') . 'EviSign/Submit', $parameters);

        if ($curl_envio->error) {
        
            $data = [
            	'evicertia_error' => $curl_envio->errorMessage,
                'evicertia_data_log' => json_encode(['data' => $parameters])
            ];
		
        	$this->db->where('ID', $form_rtps->ID);
			$this->db->update('tbl_seeker_form_rtps', $data);
  			
            return false;
        }

        $respuesta_evicertia = $curl_envio->response;

        $data =  [
            'form_rtps_file_path' => $file_path,
            'evicertia_send_date' => date('Y-m-d H:i:s'),
        	'evicertia_status' => 2,
        	'evicertia_unique_id' => $respuesta_evicertia->uniqueId,
        	'evicertia_url_push_notification' => $url_push,
            'evicertia_data_log' => json_encode(['data' => $parameters]),
            'evicertia_error' => null
        ];

        $this->db->where('ID', $form_rtps->ID);
        $this->db->update('tbl_seeker_form_rtps', $data);

        return true;
    }

    private function get_file($form_rtps)
    {
        $path_file = 'candidate/form_rtps/' . md5(uniqid($form_rtps->ID, true)) . '.pdf';

        $this->load->library(
            'Pdf/Form_rtps_pdf', 
            null, 
            'Form_rtps_pdf'
        );

        $file_tmp = '';

        try {  
            //$file_tmp = FCPATH . 'public/uploads/tmp/' . md5($path_file) . '.pdf';

            $file_tmp = sys_get_temp_dir() . '/' . md5($path_file);
            $this->Form_rtps_pdf->save($form_rtps, $file_tmp);
            $path_file = $this->Storage_lib->put($path_file, $file_tmp);

        } catch (\Exception $e) {
            
            echo $e->getMessage();

            $path_file = false;
        } 

        if (!empty($file_tmp)) {
            @unlink($file_tmp);
        }
        
        if ($path_file === false) {
            return false;
        }

        $this->Storage_lib->setVisibility($path_file, 'public');

        return $path_file;
    }

    private function get_interested($job_id)
    {
        $this->db->select([
            'app_user.email'
        ]);
        $this->db->from('tbl_recruitment_rrhh_assignments rrhh');
        $this->db->join('tbl_employers app_user', 'rrhh.rrhh_user_ID=app_user.ID');
        $this->db->where('rrhh.job_ID', $job_id);
        $users = $this->db->get()->result();

        $interested = [];

        foreach ($users as $key => $row) {
            $interested[]['Address'] = $row->email;
        }

        return $interested;
    }
}
