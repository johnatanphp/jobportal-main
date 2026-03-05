<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Insert_data_tbl_job_profiles_codes_table_2022_02_15_1531 extends CI_Migration
{
    public function up()
    {
        //Insert job profiles
        $this->insert_job_profile_codes();
    }

    public function down(){}

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
