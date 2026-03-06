<?php
class Staff_request_department_vacancie extends CI_Model
{	
	private $table_name = 'tbl_staff_request_department_vacancies';
	
    public function all($where = [])
    {
        $this->db->from($this->table_name);
        
        if (count($where) > 0) {
            $this->db->where($where);
        }
        
        return $this->db->get()->result();
    }
}
