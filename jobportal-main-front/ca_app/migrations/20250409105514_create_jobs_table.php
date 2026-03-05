<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_jobs_table extends CI_Migration
{
    public function up()
    {
        $fields = array(
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'queue' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'payload' => array(
                'type' => 'LONGTEXT',
            ),
            'attempts' => array(
                'type' => 'TINYINT',
                'constraint' => 3,
                'unsigned' => TRUE,
                'default' => 0
            ),
            'status' => array(
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'default' => 0,
                'null' => false,
            ),
            'reserved_at' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
                'null' => TRUE,
            ),
            'available_at' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
            ),
            'created_at' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
            ),
        );

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE); // Clave primaria
        $this->dbforge->add_key('queue'); // Índice para la cola
        $this->dbforge->create_table('jobs', TRUE); // TRUE para añadir IF NOT EXISTS
    }

    public function down()
    {
        $this->dbforge->drop_table('jobs');
    }
}
