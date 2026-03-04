<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_mof_disability_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'grade' => [
                'type' => 'INT',
            ],
            'item_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
            'mof_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (item_id) REFERENCES tbl_mof_disability_items(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('mof_id');
        $this->dbforge->create_table('tbl_mof_disability');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_mof_disability');
    }
}
