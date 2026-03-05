<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_expense_types_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => '15',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '120',
                'null' => false
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ]
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_expense_types');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_expense_types');
    }

    public function insert_data()
    {
        $this->db->query("
            INSERT INTO tbl_expense_types (id, name) VALUES
                ('ED','EGRESO DIRECTORES'),
                ('EI','EGRESO INTERNO'),
                ('EINP','EGRESO INTERNO NO PRESUPUESTADO'),
                ('EODT','EGRESO OPERATIVO DESCONTABLE AL TRABAJADOR'),
                ('EOF','EGRESO OPERATIVO FACTURABLE'),
                ('EOFDP','EGRESO OPERATIVO FACTURABLE DENTRO DE PROPUESTA'),
                ('EOFF','EGRESO OPERATIVO FACTURABLES FINANCIADOS'),
                ('EOFI','EGRESO OPERATIVO FACTURABLE POR INCREMENTO DE LA PRODUCCIÓN'),
                ('EOFNP','EGRESO OPERATIVO FACTURABLE NO PAGADO'),
                ('EOK','EGRESO OPERATIVO POR KARDEX'),
                ('EONF','EGRESO OPERATIVO NO FACTURABLE'),
                ('EONFP','EGRESO OPERATIVO NO FACTURABLE PAGADO'),
                ('EP','EGRESO DE PLANILLAS'),
                ('EPL','EGRESO DE PLANILLAS - EPLANI'),
                ('ESKY','EGRESO ESKY'),
                ('MIXTO','EGRESO MIXTO'),
                ('UIU','OPOIU'),
                ('XEPL','EGRESO NO PLANILLA - EPLANI');
        ");
    }
}
