<?php
class Employer_profile extends CI_Model 
{ 	
    public function get_by_id($user_id, $profile_id)
    {
        $where = [
            'user_id' => $user_id,
            'profile_id' => $profile_id
        ];

        return $this->db->get_where('tbl_employer_profiles', $where)->row();
    }

 	public function add($data)
 	{ 
		return $this->db->insert('tbl_employer_profiles', $data);
	}	

    public function delete($user_id, $profile_id)
    {
        $where = [
            'user_id' => $user_id,
            'profile_id' => $profile_id
        ];

        $this->db->where($where);
        $this->db->delete('tbl_employer_profiles');
    }

    public function get_active_profiles($user_id)
    {
        $this->db->select([
            'profiles.id',
            'profiles.name',
            'profiles.session_key',
            'profiles.folder',
            'profiles.dashboard'
        ]);
        $this->db->from('tbl_employer_profiles user_profile');
        $this->db->join('tbl_profiles profiles', 'user_profile.profile_id=profiles.id');
        $this->db->where('user_id', $user_id);
        $this->db->where('profiles.active', 1);
        
        $this->db->order_by('profiles.id', 'ASC');

        return $this->db->get()->result();
    }
}
