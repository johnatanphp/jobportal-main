<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_employee_types_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => false,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'active' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_employee_types');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_employee_types');
    }

    private function insert_data()
    {
        $types = [
            '19' => 'EJECUTIVO',
            '20' => 'OBRERO',
            '21' => 'EMPLEADO',
            '22' => 'TRABAJADOR PORTUARIO',
            '23' => 'PRACTICANTE SENATI',
            '24' => 'PENSIONISTA O CESANTE',
            '26' => 'PENSIONISTA - LEY 28320',
            '27' => 'CONSTRUCCIÓN CIVIL',
            '28' => 'PILOTO Y COPILOTO DE AVIA. COM.',
            '29' => 'MARÍTIMO, FLUVIAL O LACUSTRE',
            '30' => 'PERIODISTA',
            '31' => 'TRAB. DE LA INDUSTRIA DE CUERO',
            '32' => 'MINERO DE MINA DE SOCAVÓN',
            '33' => 'PESCADOR',
            '36' => 'PESCADOR - LEY 28320',
            '37' => 'MINERO DE TAJO ABIERTO',
            '38' => 'MINERO DE INDUSTRIA MINERA METALÚRGICA',
            '56' => 'ARTISTA - LEY DEL ARTISTA - LEY 28131',
            '64' => 'AGRARIO DEPENDIENTE D.LEG 885',
            '65' => 'TRABAJADOR ACTIVIDAD ACUÍCOLA',
            '66' => 'PESCADOR Y PROCESADOR ARTESANAL INDEPEND',
            '80' => 'PERSONA QUE GENERA SOLO INGRESOS DE CUARTA CATEGORÍA',
            '81' => 'PERSONA CON CONVENIO DE APRENDIZAJE CON PREDOMINIO EN LA EMPRESA',
            '82' => 'PERSONA CON CONVENIO DE PRÁCTICAS PRE - PROFESIONALES',
            '83' => 'PERSONA CON CONVENIO DE PRÁCTICAS PROFESIONALES',
            '84' => 'PERSONA CON CONVENIO DE CAPACITACIÓN LABORAL JUVENIL',
            '85' => 'PERSONA CON CONVENIO DE PASANTÍA EN LA EMPRESA',
            '86' => 'DOCENTE Y/O CATEDRÁTICO CON CONVENIO DE PASANTÍA',
            '87' => 'PERSONA CON CONVENIO DE REINSERCIÓN LABORAL',
            '98' => 'PERSONA QUE GENERA INGRESOS DE CUARTA - QUINTA CATEGORÍA'
        ];

        foreach ($types as $id => $row) {

            $this->db->insert('tbl_employee_types', [
                'id' => $id,
                'name' => $row
            ]);
        }
    }
}
