<?php
class Rys_mo_document extends CI_Model {

    public function find($id)
    {
        return $this->get_by([
            'id' => $id
        ]);
    }

    public function get_by($filters)
    {
        return $this->db->get_where(
            'tbl_exam_request_results', 
            $filters
        )->row();
    }
}
 