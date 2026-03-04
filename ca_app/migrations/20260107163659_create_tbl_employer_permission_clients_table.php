<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employer_permission_clients_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'consultant_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false
            ],
            'employer_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key(['consultant_code', 'client_code']);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (employer_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->create_table('tbl_employer_permission_clients');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employer_permission_clients');
    }
}
