<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class JobList extends REST_Controller
{
    public function index_get()
    {
        $input_data = $this->input->get();

        list($is_success, $message) = $this->validate($input_data);

        if (!$is_success) {
            return [
                'status'  => false,
                'message' => $message
            ];
        }

        return $this->get_response($input_data);
    }

    private function validate(array $input_data)
    {
        if (empty($input_data['code_integration'])) {
            return [false, 'El campo Código de integración es requerido.'];
        }
        return [true, 'OK'];
    }

    private function get_response(array $input_data)
    {
        $results = $this->db
            ->select('*')
            ->from('tbl_job_layouts job_layouts')
            ->join('tbl_companies c', 'c.ID=job_layouts.company_id')
            ->where('job_layouts.code_integration', $input_data['code_integration'])
            ->get()
            ->result();

        $data = array_map(function ($row) {
            if (is_array($row)) {
                $row = (object)$row;
            }
            if (is_string($row)) {
                $row = json_decode($row);
            }
            
            if (isset($row->response)) {
                $decodedResponse = json_decode($row->response);
                if (json_last_error() === JSON_ERROR_NONE && isset($decodedResponse->data)) {
                    return $decodedResponse->data;
                } else {
                    return [];
                }
            } else {
                return $row;
            }
        }, $results);

        return $this->response([
            'status' => true,
            'data'   => ['jobs' => $data]
        ], 200);
    }


}
