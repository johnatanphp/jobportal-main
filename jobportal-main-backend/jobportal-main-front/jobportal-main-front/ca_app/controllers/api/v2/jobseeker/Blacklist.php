<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Blacklist extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
    }

    public function search_get()
    {
        $params = $this->get();

        $this->form_validation->set_data(count($params) == 0 ? [0] : $params);
        
        $doc_numbers = $params['document_number'] ?? [];

        if (count($doc_numbers) == 0) {
            $this->form_validation->set_rules('document_number[]', 'document_number[]', 'trim|required');
        }

        foreach ($doc_numbers as $index => $doc_number) {
            $this->form_validation->set_rules('document_number[' . $index . ']', 'document_number[' . $index . ']', 'trim|required');
        }

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message = $this->form_validation->error_array();
                $this->response([
                    'status' => false,
                    'message' => reset($message),
                    'data' => []                
                ], self::HTTP_OK
            );
            return;
        }

        $this->load->library('WS_overall/WS_overall_employee_blacklist_lib', null, 'WS_overall_employee_blacklist_lib');
        
        $data_list_doc_numbers = $this->WS_overall_employee_blacklist_lib->all($doc_numbers);

        foreach ($doc_numbers as $doc_number) {

            $data_doc_numbers[] = [
                'document_number'  => (string)$doc_number,
                'blacklist' => (int)(isset($data_list_doc_numbers[$doc_number]) ? ($data_list_doc_numbers[$doc_number])->blacklist : 0),
                'blacklist_observation' => (string)(isset($data_list_doc_numbers[$doc_number]) ? ($data_list_doc_numbers[$doc_number])->blacklist_observation : ''), 
            ];
        }

        $response_data = [
            'status' => true,
            'message' => 'Ok',
            'data' => $data_doc_numbers
        ];
        $this->response($response_data, self::HTTP_OK);
    }
}
