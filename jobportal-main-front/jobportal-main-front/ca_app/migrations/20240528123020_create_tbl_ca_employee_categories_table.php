<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_ca_employee_categories_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
            'portal_code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => false
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_ca_employee_categories');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_ca_employee_categories');
    }

    public function insert_data()
    {
        $this->db->query("
            INSERT INTO tbl_ca_employee_categories (code, portal_code,name) VALUES
                ('-1', 1,'SIN CATEGORIA'),
                ('01', 2,'EJECUTIVO'),
                ('02', 3,'OBRERO'),
                ('03', 4,'EMPLEADO'),
                ('04', 5,'OFICIAL'),
                ('05', 6,'OPERARIO'),
                ('06', 7,'PEON'),
                ('07', 8,'GERENCIA'),
                ('08', 9,'JEFE'),
                ('11', 10,'FUNCIONARIO'),
                ('12', 11,'PROFESIONAL'),
                ('13', 12,'TECNICO'),
                ('14', 13,'AUXILIAR'),
                ('15', 14,'PRACTICANTE'),
                ('21', 15,'FUNCIONARIO PÚBLICO'),
                ('22', 16,'DIRECTIVO PÚBLICO');
        ");
    }
}
