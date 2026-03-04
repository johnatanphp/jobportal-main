<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screening_jobseeker_search_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function create($params)
    {
        if (!$this->config->item('screening_api_enabled')) {
             return [
                'status' => false,
                'message' => 'Se ha desactivado temporalmente las consultas al Proveedor de Screening.',
            ];
        }

        //Load models
        $this->load->model('Posted_job');
        $this->load->model('Staff_request');
        $this->load->model('Job_seeker');

        $this->db->from('tbl_identity_document_types');
        $doc_types = $this->db->get()->result();

        $doc_types_list = [];
        foreach ($doc_types as $doc_type) {
            $doc_types_list[$doc_type->id] = $doc_type->id;
        }
        
        $seeker_id = isset($params['seeker_id']) ? trim($params['seeker_id']) : null;
        $jobseeker = $this->Job_seeker->find($seeker_id);

        $document_number = trim($params['document_number']);

        if (empty($document_number)) {
            return [
                'status' => false,
                'message' => 'El documento de identidad debe ser ingresado',
            ];
        }

        $cost_center = trim($params['cost_center']);
        
        if (empty($cost_center)) {
            return [
                'status' => false,
                'message' => 'El centro de costo debe ser ingresado',
            ];
        }

        $type = trim($params['type']);

        if (empty($type)) {
            return [
                'status' => false,
                'message' => 'El tipo de screening debe ser ingresado',
            ];
        }
 
        $staff_request = null;
        $job_title = '';
        $type_expense = '';
        $eecc_code = '';
        $cost_center_client = '';

        $job_id = isset($params['job_id']) ? trim($params['job_id']) : null;

        if ($job_id != null) {
            $job = $this->Posted_job->find($job_id);

            if (!$job) {
                return [
                    'status' => false,
                    'message' => 'Puesto Id no encontrado'
                ];
            }

            $job_title = trim($job->job_title);
            $staff_request = $this->Staff_request->find($job->request_ID);
        }

        if ($staff_request) {
            $type_expense = trim((string)$staff_request->type_expense);
            $eecc_code = trim((string)$staff_request->eecc_code);
            $cost_center_client = trim((string)$staff_request->cost_center_client);
        }
        
        $date = date('Y-m-d H:i:s');
        
        $created_by_id = $params['created_by_id'] ?? $this->session->userdata('user_id');

        $sc_created_by = $this->Employer->find($created_by_id);

        if (!$sc_created_by) {
            return [
                'status' => false,
                'message' => 'Empleador solicitante es incorrecto',
            ];
        }

        $consultant = $this->get_consultant_by_cost_center($cost_center);

        if (!$consultant) {
            return [
                'status' => false,
                'message' => 'Centro de costo no se pudo encontrar',
            ];
        }

        $job_title = isset($params['job_title']) ? trim($params['job_title']) : $job_title;
        $type_expense = isset($params['type_expense']) ? trim($params['type_expense']) : $type_expense;
        $eecc_code = isset($params['eecc_code']) ? trim($params['eecc_code']) : $eecc_code;
        $cost_center_client = isset($params['cost_center_client']) ? trim($params['cost_center_client']) : $cost_center_client;
        $consultant_name = $consultant->consultant_name;

        //Obtener mes por tipo de solicitud solicitada
        $sc_add_month = $this->get_month_of_validity($staff_request ? $staff_request->request_model_id : null);
        
        $api_product_types = [
			'1' => 'BASIC_PLUS',
			'2' => 'VIP'
		];

		$product = isset($api_product_types[$type]) ? $api_product_types[$type] : ''; 

        if ($product == '') {
            return [
                'status' => false,
                'message' => 'Tipo de screening es incorrecto',
            ];
        }

        $api_return = $this->call_api([
			'product' => $product,
			'documentNumber' => $document_number,
			'customer' => $cost_center,
            'aditionalInformation' => $job_title,
            'voucherNumber' => mb_strtoupper(trim($sc_created_by->first_name . ' ' . $sc_created_by->last_name)),
            'tipoegreso' => $type_expense,
            'estructuracosto' => $eecc_code,
            'cecogeneral' => $cost_center_client,
            'razonsocialcliente' => $consultant_name
		]);
    
        $response = json_decode($api_return['response'], true);
        $response_code = isset($response['responseCode']) ? $response['responseCode'] : 0;
        $response_message = isset($response['responseMessage']) ? $response['responseMessage'] : 'Error desconocido';
        $parameters = $api_return['parameters'];

        $first_name = trim($response['data']['firstname'] ?? '');
        $first_last_name = trim($response['data']['lastname'] ?? '');
        $second_last_name = trim($response['data']['secondSurname'] ?? '');

        $data = [
            'document_type' => isset($jobseeker->document_type) ? $jobseeker->document_type : null,
            'document_number' => $document_number,
            'first_name' => $first_name,
            'last_name' => trim($first_last_name . ' ' . $second_last_name),
            'seeker_id' => $seeker_id,
            'created_at' => $date,
            'type_id' => $type,
            'job_id' => $job_id,
            'job_title' => $job_title,
            'no_cia' => $staff_request ? $staff_request->no_cia : '',
            'client_code' => $staff_request ? $staff_request->cod_clie : '',
            'business_unit_code' => $staff_request ? $staff_request->cod_business_unit : '',
            'cost_center' => $cost_center,
            'parameters' => json_encode($parameters),
            'response_code' => $response_code,
            'response' => json_encode($response),
            'created_by' => $sc_created_by->ID,
            'due_date' => date('Y-m-d H:i:s', strtotime("+" . $sc_add_month . " months", strtotime($date))),
            'type_expense' => $type_expense,
            'eecc_code' => $eecc_code,
            'cost_center_client' => $cost_center_client
        ];

        $this->db->insert('tbl_screening', $data);
        $id = $this->db->insert_id();

        if (!$id) {
            return [
                'status' => false,
                'message' => 'No se pudo registrar el screening'
            ];
        }

        if ($response_code == 0) {
            return [
                'status' => false,
                'message' => $response_message,
                'data' => [
                    'id' => $id
                ]
            ];
        }

        return [
            'status' => true,
            'message' => 'Screening ha sido creado con éxito',
            'data' => [
                'id' => $id
            ]
        ];
    }

    public function get_token()
	{
		$headers = [
			"Content-Type: application/json"
        ];

		$post = [
			'username' => $this->config->item('screening_api_username'),
			'password' => $this->config->item('screening_api_password')
		];

		$url = $this->config->item('screening_api_url') . "/lc/vlex";

		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($post),
			CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->config->item('screening_api_login_timeout') //Segundos
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response);

		return isset($response->sessionKey) ? $response->sessionKey : null;
	}

    public function call_api($parameters)
    {
        $token = $this->get_token();

		$headers = [
			"Authorization: Bearer " . $token,
			"Content-Type: application/json"
        ];

		$url = $this->config->item('screening_api_url') . "/sc/gjr";

		$options = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => json_encode($parameters),
			CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->config->item('screening_api_timeout') //Segundos
		];

		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);

		return [
			'response' => $response,
			'parameters' => $parameters,
			'header' => $headers
		];
    }

    private function get_month_of_validity($request_model_id = null)
    {
        $month_default = 6;

        if (!$request_model_id) {
            return $month_default;
        }

        //Consultar vigencia del screening dependiendo del modelo de la solicitud del empleo
        $this->db->from('tbl_screening_staff_request_model_validities');
        $this->db->where('request_model_id', $request_model_id);
        $sc_request_model_validity = $this->db->get()->row();

        return $sc_request_model_validity && $sc_request_model_validity->month_validity ? 
               $sc_request_model_validity->month_validity : $month_default;
    }

    private function get_consultant_by_cost_center($cost_center, $company_id = 1) 
    {
        $this->db->select([
            'consultant.code AS consultant_code',
            'consultant.name AS consultant_name'
        ]);
        $this->db->from('tbl_workflow_cost_centers cost_center');
        $this->db->join('tbl_workflow_consultants consultant', 'consultant.code=cost_center.cia_code AND consultant.company_id="' . $company_id . '"');
        $this->db->where('cost_center.code', $cost_center);
        $this->db->where('cost_center.company_id', $company_id);
        $this->db->group_by('consultant.code');
        
        return $this->db->get()->row();
    }
}
