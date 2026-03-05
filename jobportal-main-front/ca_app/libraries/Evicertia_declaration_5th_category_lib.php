<?php
class Evicertia_declaration_5th_category_lib 
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

    public function send(
    	$job_id,
    	$seeker_id
    )
    {
    	$job = $this->Posted_job->get_posted_job_by_id($job_id);
    	$staff_request = $this->Staff_request->find($job->request_ID);
    	$seeker = $this->Job_seeker->get_job_seeker_by_id($seeker_id);

        $record = $this->Seeker_declaration_5th_category->get_for_job_id($job_id, $seeker_id);

        if ($record && ($record->evicertia_status == 3 || 
            $record->evicertia_status == 2)) {
            return false;
        }
        
        if (!$record) {
            $record_id = $this->Seeker_declaration_5th_category->create([
                'created_at' => date('Y-m-d H:i:s'),
                'job_id' => $job_id,
                'seeker_id' => $seeker_id,
                'evicertia_status' => 1
            ]);
        } else {
            $record_id = $record->id;
        }

        $file_path = $this->get_file($seeker_id, $job_id);

        if ($file_path === false) {
            return false;
        }

        $archivo_base64 = base64_encode(file_get_contents('https://overall-portal-de-empleo.s3.amazonaws.com/' . $file_path));

        //$url_push = 'https://putsreq.com/1D2iDhubyR0IQVsnCTdH';
        $url_push = site_url('evicertia_notification/seeker_requested_documents/declaration_5th_category');

        $parameters = [
            'LookupKey' => $staff_request->cost_center . '-' . $record_id,
            'Issuer'    => $staff_request->consultant_name,
            'Subject'   => mb_strtoupper('RH-FO-006 DECLARACIÓN JURADA RENTA DE 5TA CATEGORÍA'),
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
                "CostCentre" => $staff_request->cost_center	,
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

        if ($staff_request->no_cia == 54) {
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
			$this->db->update('tbl_seeker_declaration_5th_categories', $data);
		
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
		$this->db->update('tbl_seeker_declaration_5th_categories', $data);
		
        return true;
    }

    private function get_file($seeker_id, $job_id)
    {
        $path_file = 'candidate/declaration_5th_category/' . md5(uniqid($job_id . '-' . $seeker_id, true)) . '.pdf';

        $this->load->library(
            'Pdf/Declaration_5th_category_pdf', 
            null, 
            'Declaration_5th_category_pdf'
        );

        $file_tmp = '';

        try {  
            $file_tmp = FCPATH . 'public/uploads/tmp/' . md5($path_file) . '.pdf';

            $this->Declaration_5th_category_pdf->save($seeker_id, $file_tmp);

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
