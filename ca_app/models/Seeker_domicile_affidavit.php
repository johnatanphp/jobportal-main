<?php
class Seeker_domicile_affidavit extends CI_Model
{
    public function create($data)
    {
        $this->db->insert('tbl_seeker_domicile_affidavits', $data);
        return $this->db->insert_id();
    }

    public function get_for_job_id($job_id, $seeker_id)
    {
        $this->db->from('tbl_seeker_domicile_affidavits');
        $this->db->where('job_id', $job_id);
        $this->db->where('seeker_id', $seeker_id);

        return $this->db->get()->row();
    }

    public function get_latest_for_seeker($seeker_id)
    {
        $this->db->from('tbl_seeker_domicile_affidavits');
        $this->db->where('seeker_id', $seeker_id);

        $this->db->order_by('id', 'desc');

        return $this->db->get()->row();
    }
}
