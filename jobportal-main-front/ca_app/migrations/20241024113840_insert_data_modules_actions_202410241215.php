<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_modules_actions_202410241215 extends CI_Migration
{
    public function up()
    {
        $profile_id = 1; //Perfil empleador

        $modules = [
            'screening' => [
                'name' => 'Consultas Screening',
                'actions' => [
                    'search' => [
                        'name' => 'Buscar',
                    ],
                    'create' => [
                        'name' => 'Crear',
                    ],
                    'report_list' => [
                        'name' => 'Reporte listado',
                    ],
                ],
            ]
        ];

        foreach ($modules as $module_key => $module) {

            $this->db->insert('tbl_modules', [
                'name' => $module['name'],
                'keyword_id' => $module_key
            ]);
            $module_id = $this->db->insert_id();

            foreach ($module['actions'] as $action_key => $action) {

                $this->db->insert('tbl_modules_actions', [
                    'name' => $action['name'],
                    'keyword_id' => $action_key,
                    'module_id' => $module_id
                ]);

                $action_id = $this->db->insert_id();

                $this->db->insert('tbl_profile_actions', [
                    'profile_id' => $profile_id,
                    'action_id' => $action_id
                ]);
            }
        }
    }

    public function down(){}
}
