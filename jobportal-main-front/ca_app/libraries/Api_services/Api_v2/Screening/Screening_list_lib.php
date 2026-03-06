<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screening_list_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function list($input_data)
    {
        list($is_success, $message) = $this->validate($input_data);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }

        return $this->get_response($input_data);
    }

    private function validate($input_data)
    {
        $input_data = count($input_data) == 0 ? ['0'] : $input_data;

        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_rules('identification_document_number', 'identification_document_number', 'required');
       
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        return [true, 'OK'];
    }

    private function get_response($input_data)
    {
        $this->load->library('Url_signer/Url_signer_lib');
        
        $this->db->select([
            'screening.id AS id',
            'screening.created_at AS created_at',
            'screening.response AS response',
            'screening.cost_center',
            'created_by.ID AS created_by_id',
            'created_by.first_name AS created_by_first_name',
            'created_by.last_name AS created_by_last_name'
        ]);
        $this->db->from('tbl_screening screening');
        $this->db->join('tbl_employers created_by', 'created_by.ID=screening.created_by', 'left');
        $this->db->where('screening.response_code', 1);

        if (isset($input_data['id'])) {
            $this->db->where('screening.id', $input_data['id']);
        }

        $this->db->where('screening.document_number', $input_data['identification_document_number']);

        $results = $this->db->get()->result();
        $response_data = [];

        foreach ($results as $row) {

            $result_data = json_decode($row->response);
        
            $data = $result_data->data ?? [];
            
            $prosecution_data = $this->get_prosecution_data($data->data ?? []);

            $screening_token_id = $this->custom_encryption->encrypt_data($row->id, 1);
            
            $created_by = null;

            if ($row->created_by_id) {
                $created_by = [
                    'id' => $row->created_by_id,
                    'first_name' => (string)$row->created_by_first_name,
                    'last_name' => (string)$row->created_by_last_name
                ];
            }

            $response_data[] = [
                'id' => (string)$row->id,
                'created_at' => $row->created_at,
                'created_by' => $created_by,
                'cost_center_id' => $row->cost_center,
                'files' => [
                   [
                    'type' => '1',
                    'file_url' => $this->url_signer_lib->sign(site_url('general/jobseeker/screening/view/' . $screening_token_id . '/1')),
                    'description' => 'Screening Completo'
                   ],
                   [
                    'type' => '2',
                    'file_url' =>  $this->url_signer_lib->sign(site_url('general/jobseeker/screening/view/' . $screening_token_id . '/2')),
                    'description' => 'Screening Parcial'
                   ],
                   [
                    'type' => '3',
                    'file_url' =>  $this->url_signer_lib->sign(site_url('general/jobseeker/screening/view/' . $screening_token_id . '/3')),
                    'description' => 'Anexos del Screening'
                   ],
                ], 
                'fiscalia_data' => $prosecution_data,
                // 'portal_id' => (string)$row->id,
                // 'portal_search_date' => $row->created_at,
            ];
        }

        return apiv2_response(true, 'OK', $response_data);
    }

    private function get_prosecution_data($data)
    {
        $prosecution_data = [];

        foreach ($data as $row_prosecution) {
        
            if (!@$row_prosecution->Fiscalia) {
                continue;
            }
            $prosecution_data[] = $row_prosecution->Fiscalia;
        }

        return $prosecution_data;
    }
}
