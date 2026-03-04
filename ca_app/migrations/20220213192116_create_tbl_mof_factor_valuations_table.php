<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_mof_factor_valuations_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'factor_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
            'mof_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (factor_id) REFERENCES tbl_factor_valuations(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('mof_id');
        $this->dbforge->create_table('tbl_mof_factor_valuations');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_mof_factor_valuations');
    }
}
