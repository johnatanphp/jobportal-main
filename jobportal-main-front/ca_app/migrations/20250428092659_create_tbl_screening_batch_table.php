<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_screening_batch_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'default' => '',
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
            ],
            'status_id' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => false,
                'default' => '1'
            ],
            'created_by' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
        ]);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (created_by) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_screening_batch');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_screening_batch');
    }
}
