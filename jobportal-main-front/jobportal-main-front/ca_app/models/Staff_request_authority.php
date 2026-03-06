<?php
class Staff_request_authority extends CI_Model {
    
    public function get_all_authorities()
    {
        $this->db->from('tbl_staff_request_authorities');
        $this->db->order_by('active', 'DESC');

        return $this->db->get()->result();       
    }
    
    public function save($data_authority)
    {
        $trans_status = $this->db->insert('tbl_staff_request_authorities', $data_authority);
        return $trans_status;
    }

    public function delete($authority_id)
    {
        $this->db->where('ID', $authority_id);
        return $this->db->delete('tbl_staff_request_authorities');
    }

    public function edit($data_input, $authority_id) 
    {
        $authority = $this->get_authority_by_id($authority_id);

        $data = array(
            'name' => $data_input['authority_name'],
            'email' => $data_input['authority_email'],
            'business_unit_ID' => isset($data_input['authority_business_unit']) ? $data_input['authority_business_unit'] : null,
            'active' => $data_input['authority_status'] == '1' ? 1 : 0
        );

        $this->db->where('ID', $authority_id);
        return $this->db->update('tbl_staff_request_authorities', $data);
    }
    
    public function get_authority_by_id($id) 
    {
        $this->db->from('tbl_staff_request_authorities');
        $this->db->where('ID', $id);

        return $this->db->get()->row();
    }

    public function get_authority_by_type($type) 
    {
        $this->db->from('tbl_staff_request_authorities');
        $this->db->where('type', $type);

        return $this->db->get()->row();
    }

    public function get_authorities_by_type($company_id, $type) 
    {
        $this->db->select(array(
            'business_unit.business_unit_name',
            'staff_request_authority.*'
        ));
        $this->db->from('tbl_staff_request_authorities staff_request_authority');
        $this->db->join('tbl_business_units business_unit', 'staff_request_authority.business_unit_ID=business_unit.ID', 'left');
        $this->db->where('staff_request_authority.type_authority_ID', $type);
        $this->db->where('staff_request_authority.company_id', $company_id);

        $this->db->order_by('staff_request_authority.active', 'DESC');
        
        return $this->db->get()->result();
    }

    public function get_active_authorities_by_type($company_id, $type) 
    {
        $this->db->select([
            'business_unit.business_unit_name',
            'staff_request_authority.*'
        ]);
        $this->db->from('tbl_staff_request_authorities staff_request_authority');
        $this->db->join('tbl_business_units business_unit', 'staff_request_authority.business_unit_ID=business_unit.ID', 'left');
        $this->db->where('staff_request_authority.type_authority_ID', $type);
        $this->db->where('staff_request_authority.company_id', $company_id);
        
        $this->db->where('staff_request_authority.active', 1);

        return $this->db->get()->result();
    }

    public function get_authorities_DR_by_business_unit_code($company_id, $code_business_unit)
    {
        $this->db->select([
            'staff_request_authority.*'
        ]);
        $this->db->from('tbl_staff_request_authorities staff_request_authority');
        $this->db->join('tbl_business_units business_unit', 'staff_request_authority.business_unit_ID=business_unit.ID', 'left');
        $this->db->where('staff_request_authority.type_authority_ID', 1); //DIRECTOR RESPONSABLE
        $this->db->where('business_unit.business_unit_code', $code_business_unit);
        $this->db->where('staff_request_authority.company_id', $company_id);
        
        $this->db->where('staff_request_authority.active', 1);

        return $this->db->get()->result();
    }
}
