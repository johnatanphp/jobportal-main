<?php

class Entry_form_mx extends CI_Model
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
            'entry_form_mx.email',
            'entry_form_mx.identity_document_type_id',
            'entry_form_mx.identity_document_number',
            'entry_form_mx.first_name',
            'entry_form_mx.second_name',
            'entry_form_mx.third_name',
            'entry_form_mx.paternal_last_name',
            'entry_form_mx.maternal_last_name',
            'entry_form_mx.birthdate',
            'entry_form_mx.gender_id',
            'entry_form_mx.civil_status_id',
            'entry_form_mx.mobile_phone',
            'entry_form_mx.home_phone',
            'entry_form_mx.address',
            'entry_form_mx.social_security_number',
            'entry_form_mx.origin_country_id',
            'entry_form_mx.visa_type'
        ]);
        $this->db->from('tbl_entry_form entry_form');
        $this->db->join('tbl_countries countries', 'countries.ID=entry_form.form_country_id');
        $this->db->join('tbl_entry_form_mx entry_form_mx', 'entry_form_mx.entry_form_id=entry_form.id');
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
        $this->db->where('countries.iso_alfa2_code', 'MX'); //Mexico
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
        $this->db->from('tbl_entry_form_mx_rightful_claimants rightful_claimants');
        $this->db->join('tbl_kinship kinship', 'kinship.id=rightful_claimants.kinship_id', 'left');
        $this->db->where('rightful_claimants.entry_form_id', $entry_form_id);
 
        return $this->db->get()->result();
    }
}
