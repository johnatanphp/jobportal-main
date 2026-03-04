<?php
class Manage_employer extends CI_Model {
 		
	public function search_employers($company_id, $per_page, $page) {
        $this->db->select('tbl_employers.user_ID AS ID, app_users.dated, app_users.email, app_users.first_name, app_users.last_name, tbl_employers.company_ID, tbl_employers.sts, tbl_employers.top_employer, ');
        $this->db->from('tbl_employers');
        $this->db->join('tbl_employers app_users', 'app_users.ID=tbl_employers.user_ID');
        $this->db->where('tbl_employers.company_ID', $company_id);
		$this->db->order_by('tbl_employers.user_ID', 'DESC'); 
		$this->db->limit($per_page, $page);
        
        return $this->db->get()->result();
    }
			
	public function count_search_employers($company_id) {

		$this->db->from('tbl_employers');
        $this->db->join('tbl_employers app_users', 'app_users.ID=tbl_employers.user_ID');
        $this->db->where('tbl_employers.company_id', $company_id);
        
		return $this->db->count_all_results();
    }	
}
