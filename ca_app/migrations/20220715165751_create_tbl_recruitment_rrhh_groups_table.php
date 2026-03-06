<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_rrhh_groups_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => false
            ],
            'active' => [
                'type' => 'tinyint',
                'unsigned' => TRUE,
                'null' => false,
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('company_id');
        
        $this->dbforge->create_table('tbl_recruitment_rrhh_groups');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_rrhh_groups');
    }
}
