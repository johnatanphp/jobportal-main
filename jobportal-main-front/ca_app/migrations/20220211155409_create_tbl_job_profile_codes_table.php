<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_profile_codes_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
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
        $this->dbforge->create_table('tbl_job_profile_codes');

        //Insert codes
        //$this->insert_job_profile_codes();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_profile_codes');
    }

    private function insert_job_profile_codes()
	{
        $this->db->from('tbl_job_profiles');
		$r = $this->db->get()->result();

		foreach ($r as $jp) {

			$match = $this->match('/^([a-zA-Z\s]*)-([a-zA-Z\s]{1})-([0-9]*)$/', $jp->code);

			if (empty($match)) {
				continue;
			}

			$acronym = $match[1][0] . '-' . $match[2][0];
			$correlative = intval($match[3][0]);

			$this->db->from('tbl_job_profile_codes');
			$this->db->where('acronym', $acronym);
			$row = $this->db->get()->row();

			if (!$row) {
				$this->db->insert('tbl_job_profile_codes', [
					'acronym' => $acronym,
					'number_correlative' => $correlative + 1,
					'active' => 1
				]);
				continue;
			}

			if (($correlative + 1) > $row->number_correlative) {
				$this->db->where('id', $row->id);
				$this->db->update('tbl_job_profile_codes', ['number_correlative' => $correlative + 1]);
			}
		}
	}

    private function match($exp, $str)
	{
		$match = [];
		preg_match($exp, $str, $match, PREG_OFFSET_CAPTURE);

		return $match;
	}
}
