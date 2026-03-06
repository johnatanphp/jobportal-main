<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_skills_202409261832 extends CI_Migration
{
    public function up()
    {
        // Verificar si el campo 'skill_favorite' ya existe en la tabla 'tbl_skills'
        if (!$this->db->field_exists('skill_favorite', 'tbl_skills')) {
            $fields = [
                'skill_favorite' => [
                    'type' => 'TINYINT',
                    'unsigned' => TRUE,
                    'null' => true,
                    'default' => 0,
                ],
            ];
    
            $this->dbforge->add_column('tbl_skills', $fields);
        }
    }
    
    public function down()
    {
        // Eliminar el campo 'skill_favorite' si existe
        if ($this->db->field_exists('skill_favorite', 'tbl_skills')) {
            $this->dbforge->drop_column('tbl_skills', 'skill_favorite');
        }
    }
}
