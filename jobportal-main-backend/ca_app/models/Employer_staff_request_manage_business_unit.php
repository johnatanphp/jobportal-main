<?php
 
class Employer_staff_request_manage_business_unit extends CI_Model
{
    public function get_business_units_by_user_id($user_id)
    {
        $user = $this->Employer->find($user_id);

        $this->db->select([
            'bu.business_unit_code'
        ]);
        $this->db->from('tbl_employer_staff_request_manage_business_units pbu');
        $this->db->join(
            'tbl_business_units bu', 
            'bu.business_unit_code=pbu.business_unit_code'
        );
        $this->db->where('bu.active', 1);
        $this->db->where('bu.company_id', $user->company_ID);
        $this->db->where('pbu.user_id', $user->ID);
        
        $business_units = $this->db->get()->result();

        $business_unit_codes = [];
        
        foreach ($business_units as $row) {
            $business_unit_codes[] = $row->business_unit_code;
        }

        if (empty($business_unit_codes)) {
            $business_unit_codes[] = -1;
        }

        return $business_unit_codes;
    }

    public function get_users_by_staff_request_id($staff_request_id)
    {
        $this->load->model('Staff_request');

        $staff_request = $this->Staff_request->find($staff_request_id);

        $this->db->select([
            'users.ID',
            'users.email',
            'users.first_name'
        ]);
        $this->db->from('tbl_employer_staff_request_manage_business_units pbu');
        $this->db->join(
            'tbl_business_units bu', 
            'bu.business_unit_code=pbu.business_unit_code'
        );
        $this->db->join(
            'tbl_employers users', 
            'users.ID=pbu.user_id'
        );
        $this->db->join(
            'tbl_employer_profiles employer_profiles', 
            'employer_profiles.user_id=pbu.user_id'
        );
        
        $this->db->where('employer_profiles.profile_id', 1); //Reclutador
        $this->db->where('users.sts', 'active'); 
        $this->db->where('bu.active', 1);
        $this->db->where('bu.company_id', $staff_request->company_ID);
        $this->db->where('pbu.business_unit_code', $staff_request->cod_business_unit);
        
        $this->db->group_by('users.ID');
        
        return $this->db->get()->result();
    }
}
