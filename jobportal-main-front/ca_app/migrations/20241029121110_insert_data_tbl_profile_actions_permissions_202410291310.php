<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_profile_actions_permissions_202410291310 extends CI_Migration
{
    public function up()
    {
        $emails = explode(',', $this->config->item('screening_permissions_users'));

        $emails = array_map('trim', $emails);

        if (count($emails) == 0) {
            return;
        }

        $this->db->select([
            'e.ID AS employer_id'
        ]);
        $this->db->from('tbl_employers e');
        $this->db->where_in('e.email', $emails);
        $employers = $this->db->get()->result();

        $this->db->select([
            'a.id AS action_id'
        ]);
        $this->db->from('tbl_modules_actions a');
        $this->db->join('tbl_modules m', 'm.id=a.module_id');
        $this->db->where('m.keyword_id', 'screening');
        $actions = $this->db->get()->result();

        foreach ($employers as $row) {
            foreach ($actions as $action_row) {
                $this->db->insert('tbl_profile_actions_permissions', [
                    'profile_id' => 1, // Perfil empleador
                    'action_id' => $action_row->action_id,
                    'employer_id' => $row->employer_id
                ]);
            }
        }
    }

    public function down(){}
}
