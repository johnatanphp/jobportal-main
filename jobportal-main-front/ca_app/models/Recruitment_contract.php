<?php

class Recruitment_contract extends CI_Model
{
    public function find($id_or_where)
    {
        $this->db->from('tbl_recruitment_contracts');

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->get()->row();
    }

    public function create($data)
    {
        $this->db->insert('tbl_recruitment_contracts', $data);

        return $this->db->insert_id();
    }

    public function update($id_or_where, $data)
    {
        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }

        return $this->db->update('tbl_recruitment_contracts', $data);
    }

    public function delete($id_or_where)
    {
        if (is_array($id_or_where) && count($id_or_where) == 0) {
            return false;
        }

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('id', $id_or_where);
        }
        
        return $this->db->delete('tbl_recruitment_contracts');
    }

    public function all($where = [])
    {
        $this->db->from('tbl_recruitment_contracts');

        if (count($id_or_where) > 0) {
            $this->db->where($where);
        }

        return $this->db->get()->result();
    }

    public function create_or_update($where, $data)
    {
        $record = $this->find($where);

        if ($record) {
            return $this->update($where, $data);
        } else {
            return $this->create($data);
        }
    }

    public function update_sync($process_id, $seeker_id)
    {
        $this->db->from('tbl_recruitment_contracts_synchronization_logs');
        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_id', $seeker_id);
        $this->db->where('success', 0);
        $synchronizations_errors = $this->db->count_all_results();

        if ($synchronizations_errors == 0) {
            $this->db->where('process_id', $process_id);
            $this->db->where('seeker_id', $seeker_id);
            $this->db->update('tbl_recruitment_contracts', [
                'synchronized' => 1
            ]);
        }
    }
}
