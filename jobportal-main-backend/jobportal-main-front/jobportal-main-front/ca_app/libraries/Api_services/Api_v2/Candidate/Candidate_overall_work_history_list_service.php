<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate_overall_work_history_list_service
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }
     
        return  $this->search($params);  
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('candidate_id', 'candidate_id', 'trim|required|integer');
      
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        $form_is_success = $this->form_validation->run();
        $message_error = $this->form_validation->error_array();

        if (!$form_is_success && current($message_error) === false) {
            return [false, 'Debe indicar algún filtro para realizar la búsqueda'];
        }

        if (!$form_is_success) {  
           return [false, current($message_error)];
        }

        return [true, 'OK'];
    }

    public function search($params)
    {
        $candidate = $this->Job_seeker->find($params['candidate_id']);

        $data = [];
        
        if (!$candidate) {
            return apiv2_response(
                true,
                'OK',
                $data
            );
        }

        $result_data = $this->Job_seeker->get_overall_work_experiences([$candidate->document_number]);
        $results_work_history = $result_data[$candidate->document_number] ?? [];

        foreach ($results_work_history as $row) {

            $dt_date_admission = $this->parse_datetime($row->date_admission);

            if ($row->date_termination) {
                $dt_date_termination = $this->parse_datetime($row->date_termination);
            }

            $data[] = [
                'consultant_id' => trim($row->consultant_code),
                'consultant_name' => trim($row->consultant_name),
                'client_id' => trim($row->client_code),
                'client_name' => trim($row->client_name),
                'admission_at' => $dt_date_admission !== false ? $dt_date_admission->format('Y-m-d H:i:s') : '',
                'finished_at' => $dt_date_termination !== false ? $dt_date_termination->format('Y-m-d H:i:s') : '',
                'status_name' => $row->employee_status,
                'form_sheet_name' => $row->sheet_name
            ];
        }
        
        return apiv2_response(
            true,
            'OK',
            $data
        );
    }

    private function parse_datetime($date)
    {
        $date = str_replace('a.m.', 'am', $date);
        $date = str_replace('p.m.', 'pm', $date);

        $format = 'd/m/Y h:i:s a';
        return DateTime::createFromFormat($format, $date);
    }
}
