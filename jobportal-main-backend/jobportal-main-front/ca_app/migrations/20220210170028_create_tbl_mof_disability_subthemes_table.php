<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_mof_disability_subthemes_table extends CI_Migration
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
                'constraint' => '180',
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ],
            'theme_id' => [
                'type' => 'INT',
                'unsigned' => TRUE
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (theme_id) REFERENCES tbl_mof_disability_themes(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_mof_disability_subthemes');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_mof_disability_subthemes');
    }
}
