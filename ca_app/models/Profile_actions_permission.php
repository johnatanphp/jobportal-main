<?php
class Profile_actions_permission extends CI_Model 
{ 	
    public function get_employer_actions($profile_id, $employer_id)
    {
        $this->db->select([
            'modules.id AS module_id',
            'modules.name AS module_name',
            'actions.id AS action_id',
            'actions.name AS action_name',
            'permissions.action_id As permission_action_id'
        ]);
        $this->db->from('tbl_profile_actions profile_actions');
        $this->db->join('tbl_modules_actions actions', 'profile_actions.action_id=actions.id');
        $this->db->join('tbl_modules modules', 'modules.id=actions.module_id');
        $this->db->join('tbl_profile_actions_permissions permissions', 'permissions.action_id=actions.id AND permissions.employer_id=' . $employer_id, 'left');
        $this->db->where('profile_actions.profile_id', $profile_id);

        $this->db->order_by('modules.id', 'ASC');

        return $this->db->get()->result();
    }
}
