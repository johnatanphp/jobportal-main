<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_mof_codes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'job_title' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
                'unique' => TRUE
            ],
            'acronym' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
                'unique' => TRUE
            ],
            'number_correlative' => [
                'type' => 'INT',
                'unsigned' => TRUE,
            ],
            'active' => [
                'type' => 'TINYINT',
                'default' => 1
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);        
        $this->dbforge->create_table('tbl_mof_codes');

        //Insert codes
        //$this->insert_mof_codes();

        //Update codes
        //$this->update_mof_codes();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_mof_codes');
    }

    private function insert_mof_codes()
	{
		$data_code['ABOGADO'] = 'ABO';
        $data_code['ADMINISTRADOR'] = 'ADM';
        $data_code['ANALISTA'] = 'ANA';
        $data_code['ASESOR'] = 'ASE';
        $data_code['AUDITOR'] = 'AUD';
        $data_code['AUXILIAR'] = 'AUX';
        $data_code['CAPACITADOR'] = 'CAP';
        $data_code['CHOFER'] = 'CHO';
        $data_code['COMMUNITY'] = 'COM';
        $data_code['CONDUCTOR'] = 'CON';
        $data_code['CONSERJE'] = 'CNJ';
        $data_code['CONSULTOR'] = 'COS';
        $data_code['CONTADOR'] = 'CTD';
        $data_code['CONTROLLER'] = 'CTF';
        $data_code['COORDINADOR'] = 'COO';
        $data_code['DIRECTOR'] = 'DIR';
        $data_code['DISEÑADOR'] = 'DIS';
        $data_code['EJECUTIVO'] = 'EJE';
        $data_code['ENCARGADO'] = 'ENC';
        $data_code['ENFERMERO'] = 'ENF';
        $data_code['GERENTE'] = 'GER';
        $data_code['GESTOR'] = 'GES';
        $data_code['JEFE'] = 'JEF';
        $data_code['MÉDICO'] = 'MED';
        $data_code['MENSAJERO'] = 'MEN';
        $data_code['OPERARIO'] = 'OPE';
        $data_code['PROGRAMADOR'] = 'PRO';
        $data_code['PSICOLOGO'] = 'PSI';
        $data_code['RECEPCIONISTA'] = 'REC';
        $data_code['SECRETARIA'] = 'SEC';
        $data_code['SEGURIDAD'] = 'SEG';
        $data_code['SUPERVISOR'] = 'SUP';
        $data_code['TELEOPERADOR'] = 'TEL';

		foreach ($data_code as $key => $row) {

			$data = [
				'job_title' => $key,
				'acronym' => $row,
				'number_correlative' => 1
			];
			$this->db->insert('tbl_mof_codes', $data);
		}
	}

    private function update_mof_codes()
	{
		$this->db->from('tbl_mof_codes');
		$r = $this->db->get()->result();

		foreach ($r as $row) {

			$this->db->from('tbl_mofs');
			$this->db->like('code', $row->acronym, 'after');
			
			$results = $this->db->get()->result();
		
			$array_max = [];

			foreach ($results as $mof) {

				$match = $this->match('/^([a-zA-Z\s]*)-([0-9]*)$/', $mof->code);

				if (empty($match)) {
					continue;
				}
				
				$acronym = $match[1][0];
				$number = intval($match[2][0]);

				$array_max[$number] = $number;
			}

			if (empty($array_max)) {
				continue;
			}

			$correlative = max($array_max);

			$this->db->where('id', $row->id);
			$this->db->update('tbl_mof_codes', ['number_correlative' => $correlative + 1]);
		}
	}

    private function match($exp, $str)
	{
		$match = [];
		preg_match($exp, $str, $match, PREG_OFFSET_CAPTURE);

		return $match;
	}
}
