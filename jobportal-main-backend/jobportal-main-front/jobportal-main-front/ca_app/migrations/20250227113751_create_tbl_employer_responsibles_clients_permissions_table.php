<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employer_responsibles_clients_permissions_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'employer_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ],
            'cia_code' => [
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => false
            ],
            'client_code' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('cia_code');
        $this->dbforge->add_key('client_code');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (employer_id) REFERENCES tbl_employers(ID)');
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (company_id) REFERENCES tbl_companies(ID)');
        $this->dbforge->create_table('tbl_employer_responsibles_clients_permissions');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employer_responsibles_clients_permissions');
    }
}
