<?php
class Country extends CI_Model 
{    
    public function all($where = [])
    {
        $this->db->from('tbl_countries');

        if (count($where) > 0) {
            $this->db->where($where);
        }
        
        return $this->db->get()->result();
    }

	public function add_country($data)
    {
        $return = $this->db->insert('tbl_countries', $data);
        if ((bool) $return === TRUE) {
            return $this->db->insert_id();
        } else {
            return $return;
        }       	
	}	
	
	public function update_country($id, $data)
    {
		$this->db->where('ID', $id);
		$return=$this->db->update('tbl_countries', $data);
		return $return;
	}
	
	public function delete_country($id)
    {
		$this->db->where('ID', $id);
		$this->db->delete('tbl_countries');
	}

	public function get_all_countries() 
    {    
        $this->db->select('*');
        $this->db->from('tbl_countries');
        $this->db->order_by("country_name", "ASC");
        
        $Q = $this->db->get();

        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }

    public function search_countries($filters, $per_page, $page) 
    {    
        $this->db->select('*');
        $this->db->from('tbl_countries');

        if (trim($filters['query']) != '') {
            $this->db->like('country_name', $filters['query']);
            $this->db->or_like('iso_3166_1_alpha2', $filters['query']);
            $this->db->or_like('iso_3166_1_alpha3', $filters['query']);
        }

        $this->db->limit($per_page, $page);
        $this->db->order_by("country_name", "ASC");
        
        return $this->db->get()->result();
    }
    
    public function count_countries($filters) 
    {    
        $this->db->select('ID');
        $this->db->from('tbl_countries');

        if (trim($filters['query']) != '') {
            $this->db->like('country_name', $filters['query']);
            $this->db->or_like('iso_3166_1_alpha2', $filters['query']);
            $this->db->or_like('iso_3166_1_alpha3', $filters['query']);
        }

        return $this->db->count_all_results();
    }
    
	public function record_count($table_name)
    {
		return $this->db->count_all($table_name);
    }
    
    public function find($id_or_where) 
    {
        $this->db->select('*');
        $this->db->from('tbl_countries');

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('ID', $id_or_where);
        }
	
        return $this->db->get()->row();
    }

	public function get_country_by_id($id) 
    {
        $this->db->select('*');
        $this->db->from('tbl_countries');
		$this->db->where('ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }

    public function get_country_by_company_id($company_id)
    {
        $this->db->select([
            'countries.*'
        ]);
        $this->db->from('tbl_countries countries');
        $this->db->join('tbl_companies companies', 'companies.country_id=countries.ID');
		$this->db->where('companies.ID', $company_id);
        return $this->db->get()->row();
    }
}
