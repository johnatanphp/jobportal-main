<?php
class Evicertia_domicile_affidavit_lib
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

    public function send($data_sig)
    {
        $job_id = isset($data_sig['job_id']) ? $data_sig['job_id'] : null; 
    	$seeker_id = $data_sig['seeker_id'];
        $present_address = isset($data_sig['present_address']) ? $data_sig['present_address'] : null;
    
        $cia_code = isset($data_sig['cia_code']) ? $data_sig['cia_code'] : null;
        $cia_name = isset($data_sig['cia_name']) ? $data_sig['cia_name'] : null;
    	$cost_center = isset($data_sig['cost_center']) ? $data_sig['cost_center'] : null; 
    	
        if (!$cia_name || !$cost_center || !$cia_code) {
            $job = $this->Posted_job->find($job_id);
            $staff_request = $this->Staff_request->find($job->request_ID);

            $cia_code = $staff_request ? $staff_request->no_cia : null; 
            $cia_name = $staff_request ? $staff_request->consultant_name : null; 
            $cost_center = $staff_request ? $staff_request->cost_center : null; 
        }

        if (!$cia_name || !$cost_center || !$cia_code) {
            return false;
        }

        if ($present_address) {
            $this->db->where('ID', $seeker_id);
            $this->db->update('tbl_job_seekers', [
                'present_address' => $present_address
            ]);
        }

        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            return false;
        }

        $record = $this->Seeker_domicile_affidavit->get_latest_for_seeker($seeker_id);

        if ($record && $record->evicertia_status == 2) {
            return false;
        }

        if (!$record || ($record && $record->evicertia_status == 3)) {
            $record_id = $this->Seeker_domicile_affidavit->create([
                'created_at' => date('Y-m-d H:i:s'),
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'address' => $seeker->present_address,
                'evicertia_status' => 1
            ]);
        } else {
            $record_id = $record->id;

            $this->db->where('id', $record_id);
            $this->db->update('tbl_seeker_domicile_affidavits', [
                'address' => $seeker->present_address,
                'job_id' => $job_id
            ]);
        }

        $file_path = $this->get_file($seeker_id);

        if ($file_path === false) {
            return false;
        }

        $archivo_base64 = base64_encode(file_get_contents('https://overall-portal-de-empleo.s3.amazonaws.com/' . $file_path));

        //$url_push = 'https://putsreq.com/1D2iDhubyR0IQVsnCTdH';
        $url_push = site_url('evicertia_notification/seeker_requested_documents/domicile_affidavit');

        $parameters = [
            'LookupKey' => $cost_center . '-' . $record_id,
            'Issuer'    => $cia_name,
            'Subject'   => mb_strtoupper('RH-FO-005 DECLARACIÓN JURADA DE DOMICILIO'),
            "SigningParties" => [
                "Name" => mb_strtoupper($seeker->last_name . ' ' . $seeker->first_name),
                "Address" => $seeker->email,
                "SigningMethod" => "EmailPin",
                "EmailAddress" => $seeker->email,
                "LegalName" => $seeker->document_number
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
                'data_log' => json_encode(['data' => $parameters])
            ];
		
        	$this->db->where('id', $record_id);
			$this->db->update('tbl_seeker_domicile_affidavits', $data);
  			
            return false;
        }

        $respuesta_evicertia = $curl_envio->response;

        $data =  [
            'file_path' => $file_path,
        	'evicertia_status' => 2,
        	'evicertia_unique_id' => $respuesta_evicertia->uniqueId,
        	'evicertia_url_push_notification' => $url_push,
            'data_log' => json_encode(['data' => $parameters]),
            'evicertia_error' => null
        ];

		$this->db->where('id', $record_id);
		$this->db->update('tbl_seeker_domicile_affidavits', $data);

        return true;
    }

    private function get_file($seeker_id)
    {
        $path_file = 'candidate/domicile_affidavit/' . md5(uniqid($seeker_id, true)) . '.pdf';

        $this->load->library(
            'Pdf/Domicile_affidavit_cert_pdf', 
            null, 
            'Domicile_affidavit_cert_pdf'
        );

        $file_tmp = '';

        try {  
            $file_tmp = FCPATH . 'public/uploads/tmp/' . md5($path_file) . '.pdf';

            $this->Domicile_affidavit_cert_pdf->save($seeker_id, $file_tmp);

            $path_file = $this->Storage_lib->put($path_file, $file_tmp);

        } catch (\Exception $e) {
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
