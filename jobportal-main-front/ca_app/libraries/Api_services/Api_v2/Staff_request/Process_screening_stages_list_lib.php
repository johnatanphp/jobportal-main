<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_screening_stages_list_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function list($params)
    {
        $results_proceses = $this->get_screening_stage($params);

        foreach ($results_proceses as $row) {
            $result_data[] = [
                'id' => (int)$row->id,
                'name' => (int)$row->name
            ];
        }

        return [
            'status' => true,
            'message' => 'OK',
            'data' => $result_data
        ];       
    }
    
    public function get_screening_stage($params)
    {
        $this->db->select([
            'stage.id',
            'stage.name',
        ]);
        $this->db->from('tbl_recruitment_stages stage');
    
        $this->db->where('stage.active', true);
    
        if (is_array($params['its_screening'])) {
            $this->db->where_in('stage.its_screening', $params['its_screening']);
        } else {
            $this->db->where('stage.its_screening', $params['its_screening']);
        }
    
        return $this->db->get()->result();
    }
}
