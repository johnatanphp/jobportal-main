<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_recruitment_contract_document_types_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'option_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'allowed_files' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'max_size' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'company_id' => [
                'type' => 'INT',
                'unsigned' => false,
                'null' => true
            ],
            'active' => [
                'type' => 'TINYINT',
                'unsigned' => true,
                'default' => 1
            ]   
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('company_id');
        
        $this->dbforge->create_table('tbl_recruitment_contract_document_types');

        $this->db->query('ALTER TABLE tbl_recruitment_contract_document_types ADD CONSTRAINT FOREIGN KEY (option_type_id) REFERENCES tbl_recruitment_document_option_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');

        $this->insert_data();
    }

    public function insert_data()
    {
        $doc = [
            'Copia Doc. de identidad (DNI, Carnet de extranjería, PTP, otros)',
            'Fotos',
            'GP-FO-004 Declaración jurada de información personal del trabajador',
            'RH-FO-005 Declaración Jurada Domicilio',
            'RH-FO-006 Declaración Jurada de 5ta categoría (en caso aplique)',
            'Certificado 5ta Categoría (en caso aplique)',
            'Copia DNI Cónyuge (en caso aplique)',
            'Acta de matrimonio o certificado de convivencia del cónyuge (en caso aplique)',
            'Certificado de Estudios (en caso aplique)',
            'Copia DNI Hijos Menores de Edad (en caso aplique)',
            'Recibo de Agua o Luz o Teléfono (en caso aplique',
            'Antecedentes policiales (Caso aplique) o OCN Interpol Lima (Caso aplique)',
            'Certificados de trabajo (en caso aplique)',
            'Record migratorio vigente (Solo para extranjeros)',
            'Verificación de residencia (Solo para extranjeros)',
            'Declaración jurada: DECLARACIÓN JURADA PARA FACTOR DE RIESGO'
        ];

        foreach ($doc as $doc_name) {

            $data = [
                'company_id' => 1,
                'name' => $doc_name,
                'active' => 1
            ];

            $this->db->insert('tbl_recruitment_contract_document_types', $data);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_recruitment_contract_document_types');
    }
}
