<?php

class entry_form_ec extends CI_Model
{
    public function get_by_entry_form_id($id)
    {
         $this->db->select([
            'entry_form.id AS entry_form_id',
            'entry_form.created_at',
            'entry_form.form_country_id',
            'entry_form.seeker_id',
            'entry_form.process_id',
            'entry_form.has_digital_signature',
            'entry_form_data.email',
            'entry_form_data.identity_document_type_id',
            'entry_form_data.identity_document_number',
            'entry_form_data.first_name',
            'entry_form_data.second_name',
            'entry_form_data.third_name',
            'entry_form_data.paternal_last_name',
            'entry_form_data.maternal_last_name',
            'entry_form_data.birthdate',
            'entry_form_data.gender_id',
            'entry_form_data.civil_status_id',
            'entry_form_data.mobile_phone',
            'entry_form_data.home_phone',
            'entry_form_data.address',
            'entry_form_data.social_security_number',
            'entry_form_data.origin_country_id',
            'entry_form_data.visa_type'
        ]);
        $this->db->from('tbl_entry_form entry_form');
        $this->db->join('tbl_countries countries', 'countries.ID=entry_form.form_country_id');
        $this->db->join('tbl_entry_form_ec entry_form_data', 'entry_form_data.entry_form_id=entry_form.id');
        $this->db->where('entry_form.id', $id);
        
        return $this->db->get()->row();
    }

    public function get_last_record_by_seeker_id($seeker_id)
    {   
        $this->db->select([
            'entry_form.id'
        ]);
        $this->db->from('tbl_entry_form entry_form');
        $this->db->join('tbl_countries countries', 'countries.ID=entry_form.form_country_id');
        $this->db->where('entry_form.seeker_id', $seeker_id);
        $this->db->where('countries.iso_alfa2_code', 'EC'); //Ecuador
        $this->db->order_by('id', 'DESC');
        
        $entry_form = $this->db->get()->row();

        if ($entry_form) {
            return $this->get_by_entry_form_id($entry_form->id);
        }

        return null;
    }

    public function get_rightful_claimants($entry_form_id)
    {   
        $this->db->select([
            'rightful_claimants.id',
            'rightful_claimants.entry_form_id',
            'rightful_claimants.first_name',
            'rightful_claimants.second_name',
            'rightful_claimants.paternal_last_name',
            'rightful_claimants.maternal_last_name',
            'rightful_claimants.kinship_id',
            'kinship.name AS kinship_name',
            'rightful_claimants.gender_id',
            'rightful_claimants.identity_document_type_id',
            'rightful_claimants.identity_document_number',
            'rightful_claimants.birthdate'
        ]);
        $this->db->from('tbl_entry_form_ec_rightful_claimants rightful_claimants');
        $this->db->join('tbl_kinship kinship', 'kinship.id=rightful_claimants.kinship_id', 'left');
        $this->db->where('rightful_claimants.entry_form_id', $entry_form_id);
 
        return $this->db->get()->result();
    }
}
