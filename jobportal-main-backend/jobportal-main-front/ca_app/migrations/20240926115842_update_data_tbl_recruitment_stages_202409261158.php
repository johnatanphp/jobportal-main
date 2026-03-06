<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_data_tbl_recruitment_stages_202409261158 extends CI_Migration
{
    public function up() {
        $this->db->query("UPDATE tbl_recruitment_stages SET its_screening = 1 WHERE name = 'Programar entrevista cliente' OR name = 'Programar evaluación médica'");
    }

    public function down() {
        $this->db->query("UPDATE tbl_recruitment_stages SET its_screening = NULL WHERE name = 'Programar entrevista cliente' OR name = 'Programar evaluación médica'");
    }
}
