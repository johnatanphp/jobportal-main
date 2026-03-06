<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_rrhh_responsibles_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
                'null' => false
            ],
            'document_number' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
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
            'business_unit_code' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => false
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => false
            ]
        ]);

        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (company_id) REFERENCES tbl_companies(ID)');
        $this->dbforge->add_key('email');
        $this->dbforge->add_key(['email', 'company_id', 'cia_code', 'client_code', 'business_unit_code'], true);
        $this->dbforge->create_table('tbl_rrhh_responsibles');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_rrhh_responsibles');
    }
}
