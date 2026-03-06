<?php
class Staff_request_gantt extends CI_Model {

    public function get_gantt_by_request_id($request_id)
    {
        $this->db->select([
            'sr_gantt.ID',
            'sr_gantt.creation_date',
            'sr_gantt.ignore_weekend',
            'sr_gantt.type_id',
            'sr_gantt.request_ID',
            'gantt_type.name AS gantt_type_name'
        ]);
        $this->db->from('tbl_staff_request_gantt sr_gantt');
        $this->db->join('tbl_gantt_types gantt_type', 'sr_gantt.type_id=gantt_type.id');

        $this->db->where('request_ID', $request_id);

        return $this->db->get()->result();
    }

    public function get_gantt_by_request_type_id($request_id, $type_id)
    {
        $this->db->from('tbl_staff_request_gantt_activities');
        $this->db->join('tbl_staff_request_gantt', 'tbl_staff_request_gantt.request_ID=tbl_staff_request_gantt_activities.request_ID');
        $this->db->where('tbl_staff_request_gantt_activities.request_ID', $request_id);
        $this->db->where('tbl_staff_request_gantt_activities.type_id', $type_id);

        return $this->db->get()->row();
    }

    public function get_data_gantt_chart_by_request_id($request_id, $type_id = null)
    {
        $this->db->from('tbl_staff_request_gantt_activities gantt_request');
        $this->db->join('tbl_gantt_activities gantt_activity', 'gantt_request.activity_ID=gantt_activity.ID');

        if($type_id !== null) {
            $this->db->where('gantt_request.type_id', $type_id);
        }

        $this->db->where('request_ID', $request_id);
        $this->db->order_by('position', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_activities_by_request_id($request_id)
    {
        $this->db->from('tbl_staff_request_gantt_activities gantt_request');
        $this->db->join('tbl_gantt_activities gantt_activity', 'gantt_request.activity_ID=gantt_activity.ID');
        $this->db->where('request_ID', $request_id);
        $this->db->order_by('position', 'ASC');

        return $this->db->get()->result();
    }
    
    public function find($id_or_filter)
    {
        $this->db->from('tbl_staff_request_gantt');

        if (is_array($id_or_filter)) {
            $this->db->where($id_or_filter);
        } else {
            $this->db->where('ID', $id_or_filter);
        }

        return $this->db->get()->row();
    }

    public function all($filter = [])
    {
        $this->db->from('tbl_staff_request_gantt');

        if (is_array($filter) && count($filter) > 0) {
            $this->db->where($id_or_filter);
        }

        return $this->db->get()->result();
    }
}
