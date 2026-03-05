<?php
class Recruitment_contracts_synchronization_log extends CI_Model {

    public function find($id_or_where)
    {
        $this->db->from('tbl_recruitment_contracts_synchronization_logs');

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_recruitment_contracts_synchronization_logs');

        if (is_array($where) && count($where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }

    public function register($sync_parameters)
    {
    	$this->db->from('tbl_recruitment_contracts_synchronization_logs');
        $this->db->where('process_id', $sync_parameters['process_id']);
        $this->db->where('seeker_id', $sync_parameters['seeker_id']);
        $this->db->where('sync_id', $sync_parameters['sync_id']); //Registro trabajador

        if (isset($sync_parameters['ref_id'])) {
            $this->db->where('ref_id', $sync_parameters['ref_id']);
        }

        $count_registers = $this->db->count_all_results();

        if ($count_registers > 0) {
            $this->db->where('process_id', $sync_parameters['process_id']);
            $this->db->where('seeker_id', $sync_parameters['seeker_id']);
            $this->db->where('sync_id', $sync_parameters['sync_id']); //Registro trabajador
            
            if (isset($sync_parameters['ref_id'])) {
                $this->db->where('ref_id', $sync_parameters['ref_id']);
            }

            $this->db->update('tbl_recruitment_contracts_synchronization_logs', $sync_parameters);
            return;
        }

        $this->db->insert('tbl_recruitment_contracts_synchronization_logs', $sync_parameters);
    }
}
