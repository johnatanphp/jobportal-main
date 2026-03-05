<?php

class Rrhh_responsible extends CI_Model
{
    public function get_employers_by_request_id($request_id)
    {
        $request = $this->Staff_request->find($request_id);

        if (!$request) {
            return [];
        }

        $this->db->select([
            'employers.ID AS id',
            'employers.first_name',
            'employers.email',
        ]);
        $this->db->from('tbl_rrhh_responsibles rrhh_responsibles');
        $this->db->join('tbl_employers employers', 'rrhh_responsibles.email=employers.email');
        $this->db->where('employers.sts', 'active');
        $this->db->where('employers.company_ID', $request->company_ID);
        $this->db->where('rrhh_responsibles.company_id', $request->company_ID);
        $this->db->where('rrhh_responsibles.cia_code', $request->no_cia);
        $this->db->where('rrhh_responsibles.client_code', $request->cod_clie);
        $this->db->where('rrhh_responsibles.business_unit_code', $request->cod_business_unit);
        
        return $this->db->get()->result();
    }
}
