<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_ca_hiring_type_reasons_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'unique' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '80',
            ],
            'jp_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('jp_code');
        $this->dbforge->create_table('tbl_ca_hiring_type_reasons');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_ca_hiring_type_reasons');
    }

    public function insert_data()
    {
        $records = [
            ['NUEVA VACANTE' , '01', '01'],
            ['REEMPLAZO' ,'02', 'replacement'],
            ['INICIO DE SERVICIO' ,'03', '03'],
            ['CAMPAÑA' ,'04', '04'],
            ['LICENCIA' ,'05', 'license'],
            ['VACACIONES' ,'06', 'vacations'],
            ['NUEVO PUESTO' ,'07', 'new'],
            ['INCREMENTO DE PRODUCCÍON' ,'08', '08'],
            ['PROYECTO' ,'09', '09'],
            ['TRIANGULACIÓN' ,'10', '10']
        ];

        foreach ($records as $row) {
            $this->db->insert('tbl_ca_hiring_type_reasons', [
                'code' => $row[1],
                'name' => $row[0],
                'jp_code' => $row[2],
            ]);
        }
    }
}
