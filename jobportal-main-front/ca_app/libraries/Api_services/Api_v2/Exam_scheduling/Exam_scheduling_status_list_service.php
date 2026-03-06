<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Exam_scheduling_status_list_service
{
    public function __construct()
	{
		//Load models
        $this->load->model('Exam_document');
        $this->load->model('Exam_request_seeker');
        $this->load->model('Exam_request_schedule');
    }

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
        return [true, 'OK'];
    }

    public function list($params)
    {    
        $this->db->select([
            'exam_request_status.id AS status_id',
            'exam_request_status.name AS status_name',
            'exam_request_status.color AS status_color',
            'exam_request_status.order AS status_order'
        ]);

        $this->db->from('tbl_exam_request_status exam_request_status'); 
        $this->db->where('exam_request_status.active', 1);
        $this->db->where('exam_request_status.id!=', 8);

        if (isset($params['id'])) {
            $this->db->where('exam_request_status.id', $params['id']);
        }

        $this->db->order_by('exam_request_status.order', 'ASC');

        $result = $this->db->get()->result();

        $response_data = [];

        foreach ($result as $row_data) {

            $result_data = [];

            $response_data[] = [
                'id' => (string)$row_data->status_id,
                'name' => (string)$row_data->status_name,
                'bg_color' => (string)$row_data->status_color,
                'order' => (string)$row_data->status_order
            ];
        }

        return apiv2_response(
            true, 
            'Ok',
            $response_data
        );
    }
}
