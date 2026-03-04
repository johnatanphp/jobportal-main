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

    public function validate($input_data)
    {
        $input_data = count($input_data) == 0 ? ['0'] : $input_data;

        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

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
        $this->db->select([
            'screening.id AS id',
            'screening.created_at AS created_at',
            'screening.response AS response'
        ]);
        $this->db->from('tbl_screening screening');
        $this->db->where('screening.response_code', 1);
        $this->db->where('screening.document_number', $input_data['identification_document_number']);

        $results = $this->db->get()->result();
        $response_data = [];

        foreach ($results as $row) {

            $result_data = json_decode($row->response);
        
            $data = $result_data->data ?? [];
            
            $prosecution_data = $this->get_prosecution_data($data->data ?? []);

            // $observed_results = array_filter($prosecution_data, function($row){
            //     $part_negative_list = ['DEMANDADO', 'DENUNCIADO', 'IMPUTADO', 'INFRACTOR', 'INVESTIGADO'];
            //     return in_array($row->Parte, $part_negative_list);
            // });

            $created_at = $this->convert_to_utc($row->created_at);

            $response_data[] = [
                'created_at' => $created_at,
                'is_observed' => count($prosecution_data) > 0 ? 1 : 0,
                //'prosecutor_data' => $prosecution_data
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

    public function convert_to_utc($datetime)
    {
        $datetime_local = new DateTime($datetime, new DateTimeZone('America/Lima'));
        $datetime_local->setTimezone(new DateTimeZone('UTC'));
        return $datetime_local->format('Y-m-d\TH:i:s\Z');
    }
}
