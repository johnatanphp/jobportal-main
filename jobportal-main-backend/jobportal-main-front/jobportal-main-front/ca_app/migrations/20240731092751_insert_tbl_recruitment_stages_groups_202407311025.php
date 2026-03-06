<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_tbl_recruitment_stages_groups_202407311025 extends CI_Migration
{
    public function up()
    {
        $this->db->insert('tbl_recruitment_stages_groups', [
            'id' => 2,
            'name' => 'Etapas MK'
        ]);

        $group_id = $this->db->insert_id();

        if (!$group_id) {
            return;
        }

        $categories = [
            'Reclutamiento',
            'Evaluación médica',
            'Contratación'
        ];

        $category_stages = [
            'Reclutamiento' => [
                '8' => 'Validación de datos',
                '9' => 'Llamar',
                '10' => 'Programar entrevista psicológica',
                '11' => 'Entrevista psicológica',
                '12' => 'Programar entrevista cliente',
                '13' => 'Entrevista cliente'
            ],
            'Evaluación médica' => [
                '14' => 'Programar evaluación médica',
                '15' => 'Evaluación médica'
            ],
            'Contratación' => [
                '16' => 'Aptos para contratar',
                '17' => 'Enviados a contratación'
            ]
        ];

        $stage_order = 1;

        foreach ($categories as $category) {
            $this->db->insert('tbl_recruitment_stages_categories', [
                'name' => $category
            ]);
            $category_id = $this->db->insert_id();

            $stages = $category_stages[$category] ?? [];

            foreach ($stages as $stage_id => $stage_name) {
                $this->db->insert('tbl_recruitment_stages', [
                    'id' => $stage_id,
                    'name' => $stage_name,
                    'order' => $stage_order++,
                    'active' => 1,
                    'stage_category_id' => $category_id,
                    'stage_group_id' => 2
                ]);
            }
        }
    }

    public function down(){}
}
 