<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/Api_v2_Controller.php';

class Recruitment_candidate_rejected extends Api_v2_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function list_get()
    {
        $params = $this->get();

        $this->db->select([
            'rc.process_id',
            'rc.seeker_ID AS candidate_id',
            'rc.comments',
            'rc.creation_date',
            'rc.update_date',
            'rc.rejected_date',
            'rc.rejected_time',
            'rc.rejected_by_user',
        ]);
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->where('rc.discarded', 1);

        // if (isset($params['rejected_by_user'])) {
        //     $this->db->where('rc.rejected_by_user', trim($params['rejected_by_user']));
        // }

        if (isset($params['candidate_id'])) {
            $this->db->where('rc.seeker_ID', trim($params['candidate_id']));
        }

        if (isset($params['process_id'])) {
            $this->db->where('rc.process_id', trim($params['process_id']));
        }

        $results = $this->db->get()->result();

        $response_data = [];
        foreach ($results as $row) {
            $response_data[] = [
                'process_id' => (int)$row->process_id,
                'candidate_id' => (int)$row->candidate_id,
                'comments' => $row->comments,
                'creation_date' => $row->creation_date,
                'update_date' => $row->update_date,
                'rejected_date' => $row->rejected_date,
                'rejected_time' => $row->rejected_time,
                'rejected_by_user' => (int)$row->rejected_by_user,
            ];
        }

        $this->response(
            apiv2_response(true, 'OK', $response_data),
            200
        );
    }

    public function update_post()
    {
        $this->load->library(
            'Api_services/Api_v2/Process_candidates/Process_candidates_rejected_lib'
        );

        $this->response(
            $this->process_candidates_rejected_lib->reject($this->post()), 
            self::HTTP_OK
        );
    }
}
