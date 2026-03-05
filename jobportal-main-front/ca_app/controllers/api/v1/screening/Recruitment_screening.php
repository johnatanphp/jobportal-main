<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Recruitment_screening extends REST_Controller
{
    public function register_post()
    {
        $input_data = $this->input->post();

        //dd($input_data);
        $batch_id = $input_data['batch_id'];
        $batch = $this->db->from('tbl_recruitment_tray_screening_batches')
                          ->where('id', $batch_id)
                          ->get()
                          ->row();
                          
        if (!$batch) {
            $this->response([
                'status' => false,
                'message' => 'No se encontro el batch id'
            ], 200);
            return;
        }

        $staff_request = null;
        $jobseeker = $this->Job_seeker->find($batch->seeker_id);
        $created_at = date('Y-m-d H:i:s');
        $screening_parameters = $input_data['screening_parameters'] ?? '';
        $screening_response = $input_data['screening_response'] ?? '';
        $response = json_decode($screening_response, true);
        $response_code = isset($response['responseCode']) ? $response['responseCode'] : 0;
        $first_name = trim($response['data']['firstname'] ?? '');
        $first_last_name = trim($response['data']['lastname'] ?? '');
        $second_last_name = trim($response['data']['secondSurname'] ?? '');
        $response_message = isset($response['responseMessage']) ? $response['responseMessage'] : 'Error desconocido';
        $sc_add_month = 6;

        $data = [
            'document_type' => isset($jobseeker->document_type) ? $jobseeker->document_type : null,
            'document_number' => $batch->document_number,
            'first_name' => $first_name,
            'last_name' => trim($first_last_name . ' ' . $second_last_name),
            'seeker_id' => $batch->seeker_id,
            'created_at' => $created_at,
            'type_id' => $batch->type_id,
            'job_id' => null,
            'job_title' => $batch->job_title,
            'no_cia' => $staff_request ? $staff_request->no_cia : '',
            'client_code' => $staff_request ? $staff_request->cod_clie : '',
            'business_unit_code' => $staff_request ? $staff_request->cod_business_unit : '',
            'cost_center' => $batch->cost_center,
            'parameters' => $screening_parameters,
            'response_code' => $response_code,
            'response' => $screening_response,
            'created_by' => $batch->created_by,
            'due_date' => date('Y-m-d H:i:s', strtotime("+" . $sc_add_month . " months", strtotime($created_at))),
            'type_expense' => $batch->type_expense,
            'eecc_code' => $batch->eecc_code,
            'cost_center_client' => $batch->cost_center_client
        ];

        //dd($data);
        $this->db->trans_start();

        $this->db->insert('tbl_screening', $data);
        $screening_id = $this->db->insert_id();

        $status_option = [
            '1' => '2', // Generado
            '0' => '3' //Fallido
        ];

        if ($screening_id) {
            $this->db->where('id', $batch_id);
            $this->db->update('tbl_recruitment_tray_screening_batches', [
                'screening_id' => $screening_id,
                'status_id' => $status_option[$response_code],
                'error_message' => $response_code == 0 ? $response_message : ''
            ]);
        }
       
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $this->response([
                'status' => true,
                'message' => 'Screening registrado',
                'data' => [
                    'id' => (string)$screening_id
                ]
            ], 200);
            return;
        }
        
        $this->response([
            'status' => false,
            'message' => 'No se pudo registrar el screening'
        ], 200);
    }
}
