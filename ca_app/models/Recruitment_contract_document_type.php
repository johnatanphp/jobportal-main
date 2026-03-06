<?php

class Recruitment_contract_document_type extends CI_Model
{
    public function find($id)
    {
        $this->db->from('tbl_recruitment_contract_document_types');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function get_info_by_id($id)
    {
        $this->db->select([
            'cdt.id AS id',
            'cdt.name AS name',
            'cdt.company_id AS company_id',
            'cdt.group_id AS group_id',
            'companies.company_name AS company_name',
            'companies.country_id AS country_id',
            'country.country_name AS country_name',
            'country.flag_icon AS country_flag_icon',
            'country.iso_3166_1_alpha2 AS country_alpha2_code',
        ]);
        $this->db->from('tbl_recruitment_contract_document_types cdt');
        $this->db->join('tbl_companies companies', 'companies.ID=cdt.company_id');
        $this->db->join('tbl_countries country', 'country.ID=companies.country_id');
    
        $this->db->where('cdt.id', $id);

        return $this->db->get()->row();
    }

    public function all($where = [])
    {
        $this->db->from('tbl_recruitment_contract_document_types');
        $this->db->where($where);
        return $this->db->get()->result();
    }
}
