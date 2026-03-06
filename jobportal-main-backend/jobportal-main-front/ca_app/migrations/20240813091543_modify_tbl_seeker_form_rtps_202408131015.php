<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Modify_tbl_seeker_form_rtps_202408131015 extends CI_Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE tbl_seeker_form_rtps MODIFY COLUMN evicertia_unique_id VARCHAR(48) NULL;');
        $this->db->query('CREATE INDEX evicertia_unique_id ON tbl_seeker_form_rtps (evicertia_unique_id);');
    }

    public function down(){}
}
