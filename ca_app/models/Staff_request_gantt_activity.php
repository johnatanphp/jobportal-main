<?php
class Staff_request_gantt_activity extends CI_Model
{
    public function find($id_or_filter)
    {
        $this->db->from('tbl_staff_request_gantt_activities');

        if (is_array($id_or_filter)) {
            $this->db->where($id_or_filter);
        } else {
            $this->db->where('id', $id_or_filter);
        }

        return $this->db->get()->row();
    }

    public function all($filter = [])
    {
        $this->db->from('tbl_staff_request_gantt_activities');

        if (is_array($filter) && count($filter) > 0) {
            $this->db->where($id_or_filter);
        }

        return $this->db->get()->result();
    }

    public function get_start_date_gantt($gantt_id)
    {
        $this->db->from('tbl_staff_request_gantt_activities');
        $this->db->where('gantt_id', $gantt_id);
        $this->db->order_by('start_date', 'ASC');
        $this->db->limit(1);

        $Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            return $Q->row()->start_date;
        }

        return null;
    }

    public function get_end_date_gantt($gantt_id)
    {
        $this->db->from('tbl_staff_request_gantt_activities');
        $this->db->where('gantt_id', $gantt_id);
        $this->db->order_by('end_date', 'DESC');
        $this->db->limit(1);

        $Q = $this->db->get();
        
        if ($Q->num_rows() > 0) {
            return $Q->row()->end_date;
        }

        return null;
    }

    public function get_gantt_activities($gantt_id)
    {
        $this->db->select([
            'gantt_activities.ID AS activity_id',
            'gantt_activities.activity_name',
            'sr_gantt_activities.start_date',
            'sr_gantt_activities.end_date',
            'sr_gantt_activities.other_activity'
        ]);
        $this->db->from('tbl_staff_request_gantt sr_gantt');
        $this->db->join('tbl_staff_request_gantt_activities sr_gantt_activities', 'sr_gantt_activities.gantt_id=sr_gantt.ID');
        $this->db->join('tbl_gantt_activities gantt_activities', 'gantt_activities.ID=sr_gantt_activities.activity_ID');
        $this->db->where('sr_gantt.ID', $gantt_id);

        $this->db->order_by('sr_gantt_activities.position', 'ASC');

        return $this->db->get()->result();
    }
}
