<?php
require_once ("App_console.php");

class Migration_workflow_employer_permission_type_rrhh extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        $limit = 100;
        $this->migrate($limit);
    }

    public function migrate($limit)
    {
        $this->db->from('tbl_employers e');
        $this->db->where('e.rrhh_type_id IS NOT NULL');
        $this->db->where('migration_rrhh', 0);

        if ($limit) {
            $this->db->limit($limit);
        }
        
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
        
            $type = $row->rrhh_type_id;
            
            if ($type == '1') { // Reclutador
                
                $rread = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '3',
                    'action_id' => '7',
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$rread) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '3',
                        'action_id' => '7',
                        'employer_id' => $row->ID
                    ]);
                }
                
                $radd = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '3', 
                    'action_id' => '8',
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$radd) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '3',
                        'action_id' => '8', 
                        'employer_id' => $row->ID
                    ]);
                }
            }
             
            if ($type == '2') { //Administrador de nomina
                
                $rread = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '3', 
                    'action_id' => '7',
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$rread) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '3',
                        'action_id' => '7',
                        'employer_id' => $row->ID
                    ]);
                }
                
                $radd = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '3',
                    'action_id' => '8',
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$radd) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '3',
                        'action_id' => '8',
                        'employer_id' => $row->ID
                    ]);
                }
                
                $rcontract = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '3',
                    'action_id' => '9',
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$rcontract) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '3',
                        'action_id' => '9',
                        'employer_id' => $row->ID
                    ]);
                }
            }
            
            $this->db->where('ID', $row->ID);
            $this->db->update('tbl_employers', [
                'migration_rrhh' => 1
            ]);
        }
    }
}
