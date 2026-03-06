<?php
require_once ("App_console.php");

class Migration_workflow_employer_permission_type_recruiters extends App_console  
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
        $this->db->where('e.type IS NOT NULL');
        $this->db->where('migration_type', 0);

        if ($limit) {
            $this->db->limit($limit);
        }
        
        $results = $this->db->get()->result();
        
        foreach ($results as $row) {
        
            $type = $row->type;
            
            if ($type == 'external-internal') {
                
                $pexternal = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '2', //Perfil solicitante
                    'action_id' => '5', //Accion crear solicitud externa
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$pexternal) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '2', //Perfil solicitante
                        'action_id' => '5', //Accion crear solicitud externa
                        'employer_id' => $row->ID
                    ]);
                }
                
                $pinternal = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '2', //Perfil solicitante
                    'action_id' => '6', //Accion crear solicitud interna
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$pinternal) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '2', //Perfil solicitante
                        'action_id' => '6', //Accion crear solicitud interna
                        'employer_id' => $row->ID
                    ]);
                }
            }
             
            if ($type == 'internal') {
                
                $pinternal = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '2', //Perfil solicitante
                    'action_id' => '6', //Accion crear solicitud interna
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$pinternal) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '2', //Perfil solicitante
                        'action_id' => '6', //Accion crear solicitud interna
                        'employer_id' => $row->ID
                    ]);
                }
            }
            
            if ($type == 'external') {
                
                $pexternal = $this->db->get_where('tbl_profile_actions_permissions', [
                    'profile_id' => '2', //Perfil solicitante
                    'action_id' => '5', //Accion crear solicitud externa
                    'employer_id' => $row->ID
                ])->row();
                
                if (!$pexternal) {
                    $this->db->insert('tbl_profile_actions_permissions', [
                        'profile_id' => '2', //Perfil solicitante
                        'action_id' => '5', //Accion crear solicitud externa
                        'employer_id' => $row->ID
                    ]);
                }
            }

            $this->db->where('ID', $row->ID);
            $this->db->update('tbl_employers', [
                'migration_type' => 1
            ]);
        }
    }
}
