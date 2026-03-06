<?php
class Cost_center_manager extends CI_Model
{
    public function find($id)
    {
        $this->db->from('tbl_cost_center_manager');
        $this->db->where('id', $id);

        return $this->db->get()->row();
    }

    public function get_all_managers()
    {
        $this->db->select('DISTINCT(manager)');
        $this->db->from('tbl_cost_center_manager');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();  
        } else {
            return [];  
        }
    }

    public function get_consultants_by_manager_for_external($recruiter_id, $manager)
    {
        $this->db->select([
            'companies.no_cia',
            'companies.consultant_name'
        ]);
        $this->db->from('tbl_staff_recruiter_client_companies companies');
        $this->db->join('tbl_cost_center_manager manager', '
            companies.no_cia = manager.no_cia 
            and companies.cod_clie = manager.cod_clie
            and companies.cost_center = manager.cost_center');

        $this->db->where('companies.recruiter_ID', $recruiter_id);
        $this->db->where('manager.manager', $manager);
        $this->db->group_by('companies.no_cia');

        $results = $this->db->get()->result();

        $rows_data = [];

        foreach ($results as $key => $row) {

            $rows_data[] = [
                'CONSULTORA' => $row->consultant_name,
                'NO_CIA' => $row->no_cia
            ];
        }
        return [
            'MESSAGE' => 'OK',
            'CONSULTORA' => $rows_data
        ];
    }

    public function get_business_unit_by_manager_and_company_for_external($recruiter_id, $manager, $consultant)
    {

        $this->db->select([
            'srcc.cod_business_unit',
            'srcc.business_unit_name'
        ]);
        $this->db->from('tbl_staff_recruiter_client_companies srcc');
        $this->db->join('tbl_cost_center_manager ccm', '
            srcc.no_cia = ccm.no_cia 
            and srcc.cod_clie = ccm.cod_clie
            and srcc.cost_center = ccm.cost_center');

        $this->db->where('srcc.recruiter_ID', $recruiter_id);
        $this->db->where('ccm.manager', $manager);
        $this->db->where('srcc.no_cia', $consultant);

        $this->db->group_by('srcc.cod_business_unit');

        $results = $this->db->get()->result();

        $rows_data = [];

        foreach ($results as $key => $row) {

            $rows_data[] = [
                'NAME_UNIT' => $row->business_unit_name,
                'COD_UNIT' => $row->cod_business_unit
            ];
        }
        return [
            'MESSAGE' => 'OK',
            'BUSINESS_UNIT' => $rows_data
        ];
    }


    public function get_client_by_manager_consult_and_unit_for_external($data)
    {
        $this->db->select('DISTINCT(srcc.client_company_name) AS client_company_name');
        $this->db->select([
            'srcc.cod_clie',
            'srcc.client_company_name'
        ]);

        $this->db->from('tbl_staff_recruiter_client_companies srcc');
        $this->db->join('tbl_cost_center_manager ccm', '
            srcc.no_cia = ccm.no_cia 
            and srcc.cod_clie = ccm.cod_clie
            and srcc.cost_center = ccm.cost_center');

        $this->db->where('srcc.recruiter_ID', $data['recruiter_id']);
        $this->db->where('ccm.manager', $data['manager']);
        $this->db->where('srcc.no_cia', $data['no_cia']);
        $this->db->where('srcc.cod_business_unit', $data['uni_neg']);

        $this->db->group_by('srcc.cod_clie');

        $results = $this->db->get()->result();

        $rows_data = [];

        foreach ($results as $key => $row) {

            $rows_data[] = [
                'CLIENTE' => $row->client_company_name,
                'COD_CLIE' => $row->cod_clie
            ];
        }

        return [
            'MESSAGE' => 'OK',
            'CLIENTE' => $rows_data
        ];
    }

    public function get_cost_center_by_manager_consult_unit_and_client_for_external($data)
    {
        $this->db->select('srcc.cost_center');
        $this->db->from('tbl_staff_recruiter_client_companies srcc');
        $this->db->from('tbl_cost_center_manager  ccm', '
            wcc.cia_code = ccm.no_cia 
            and wcc.client_code  = ccm.cod_clie 
            and wcc.code = ccm.cost_center');

        $this->db->where('srcc.recruiter_ID', $data['recruiter_id']);
        $this->db->where('srcc.no_cia', $data['no_cia']);
        $this->db->where('srcc.cod_business_unit', $data['uni_neg']);
        $this->db->where('srcc.cod_clie', $data['cod_clie']);
        $this->db->where('ccm.manager', $data['manager']);

        $this->db->group_by('srcc.cost_center');

        $results = $this->db->get()->result();

        $rows_data = [];

        foreach ($results as $key => $row) {

            $rows_data[] = [
                'COD_CCOSTO' => $row->cost_center
            ];
        }

        return [
            'MESSAGE' => 'OK',
            'CENTROCOSTO' => $rows_data
        ];
    }

}