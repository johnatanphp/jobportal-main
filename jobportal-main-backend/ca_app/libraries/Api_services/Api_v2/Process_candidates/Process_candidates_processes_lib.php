<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_processes_lib
{
    private $pagination_per_page;

    private $pagination_page;

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {
        try { 
            $this->validate($params);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }

        return $this->list_processes($params);
    }

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('candidate_id', 'candidate_id', 'required');
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            throw new \Exception(current($message_error), -1);
        }
    }

    public function list_processes($params)
    {
        $results = $this->get_data($params);

        $data_response = [];

        foreach ($results as $process) {

            $process_name = '';

            if ($process->geographic_country_id) {
                $process_name = $process->geographic_order_administrative1 . ', ' . $process->geographic_order_administrative2 . ', ' . $process->geographic_order_administrative3;
            }

            if ($process->commercial_premise_id) {
                $process_name = $process->commercial_premise_name;
            }

            $data_response[] = [
                'id' => $process->process_id,
                'name' => $process_name,
                'stage_id' => $process->stage_id,
                'stage_name' => $process->stage_name,
                'hired' => (int)$process->hired,
                'request' => [
                    'id' => $process->request_id,
                    'name' => $process->request_name
                ]
            ];
        }

        return apiv2_response(true, 'Ok', $data_response);
    }

    private function get_data($params)
    {
        $this->db->select([
            'sr_process.id AS process_id',
            'request.ID AS request_id',
            'request.job_title AS request_name',
            'stages.id AS stage_id',
            'stages.name AS stage_name',
            'rc.contracted AS hired',
            'sr_process.country_id AS geographic_country_id',
            'ubigeo_country.country_name AS geographic_country_name',
            'sr_process.department_id AS geographic_department_id',
            'ubigeo.order_administrative1 AS geographic_order_administrative1',
            'sr_process.province_id AS geographic_province_id',
            'ubigeo.order_administrative2 AS geographic_order_administrative2',
            'sr_process.district_id AS geographic_district_id',
            'ubigeo.order_administrative3 AS geographic_order_administrative3',
            'sr_process.commercial_premise_id AS commercial_premise_id',
            'process_cp.name AS commercial_premise_name',
        ]);
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->join('tbl_recruitment_process sr_process', 'sr_process.id=rc.process_id');
        $this->db->join('tbl_staff_requests request', 'request.ID=sr_process.request_id');
        $this->db->join('tbl_recruitment_stages stages', 'stages.id=rc.stage');
        $this->db->join('tbl_commercial_premises process_cp', 'process_cp.id=sr_process.commercial_premise_id', 'left');
        $this->db->join('tbl_countries ubigeo_country', 'sr_process.country_id=ubigeo_country.ID', 'left');
        $this->db->join('tbl_ubigeos ubigeo', 'sr_process.country_id=ubigeo.country_id AND sr_process.district_id=ubigeo.order_administrative3_code', 'left');

        $this->db->where('request.request_model_id', 4);
        $this->db->where('rc.seeker_ID', $params['candidate_id']);
        
        return $this->db->get()->result();
    }
}
