<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_sap_api_users_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => false
            ],
            'api_url' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => false
            ],
            'api_username' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false
            ],
            'api_password' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false
            ],
            'api_db' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
                'null' => false
            ],  
            'active' => [
                'type' => 'TINYINT',
                'null' => false,
                'default' => 0
            ]            
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field('CONSTRAINT FOREIGN KEY (company_id) REFERENCES tbl_companies(ID)');
        $this->dbforge->create_table('tbl_sap_api_users');
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_sap_api_users');
    }
}
