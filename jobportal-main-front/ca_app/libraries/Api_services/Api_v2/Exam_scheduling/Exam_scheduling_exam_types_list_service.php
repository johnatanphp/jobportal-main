<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_scheduling_exam_types_list_service
{
    public function __construct(){}

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return apiv2_response(
                false,
                $message
            );
        }
     
        return $this->list($params);  
    }

    public function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('exam_id', 'exam_id', 'integer|greater_than[0]|in_list[1,2]');
        $this->form_validation->set_rules('id', 'id', 'integer|greater_than[0]');
    
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        return [true, 'OK'];
    }

    public function list($params)
    {    
        $this->db->select([
            'exam_types.id AS id',
            'exam_types.name AS name'
        ]);

        $this->db->from('tbl_exam_emo_types exam_types'); 
        $this->db->where('exam_types.active', 1);
    
        if (isset($params['exam_id']) && $params['exam_id'] == 2) {
            $this->db->where('exam_types.id', -1);
        }

        if (isset($params['id'])) {
            $this->db->where('exam_types.id', $params['id']);
        }
        
        $this->db->where_in('exam_types.id', [
            23,
            24,
            25,
            26,
            27,
            28
        ]);
        $this->db->order_by('exam_types.id', 'ASC');

        $result = $this->db->get()->result();

        $response_data = [];

        foreach ($result as $row_data) {

            $result_data = [];

            $response_data[] = [
                'id' => (string)$row_data->id,
                'name' => (string)$row_data->name,
                'exam_id' => '1',
                'exam_name' => 'EMPO'
            ];
        }

        return apiv2_response(
            true, 
            'Ok',
            $response_data
        );
    }
}
