<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_sap_workflow_consultants_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'cia_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'overall_cia_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_sap_workflow_consultants');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_sap_workflow_consultants');
    }

    private function insert_data()
    {
        $this->db->query("INSERT INTO tbl_sap_workflow_consultants (id,cia_code,overall_cia_code,name) VALUES
            (NULL, '1','02','Overall Strategy S.A.C.'),
            (NULL, '2','16','Marketing power'),
            (NULL, '3','05','Industry & logistics managamenty S.A.'),
            (NULL, '4','03','Executive solutions S.A.'),
            (NULL, '5','54','SUPPLY & OPERATIONS SAC'),
            (NULL, '6','01','overall Business S.A.'),
            (NULL, '7','22','Overall Oriente'),
            (NULL, '8','17','Trade Development'),
            (NULL, '9','23','Overall Pharma SAC'),
            (NULL, '10','04','BUSINESS CONSULTANTS S.A.')
        ");
    }
}
