<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_recruitment_rrhh_assignments_202207151730 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'manual' => [
                'type' => 'tinyint',
                'null' => false,
                'default' => 0
            ]
        ];

        $this->dbforge->add_column('tbl_recruitment_rrhh_assignments', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_recruitment_rrhh_assignments', 'manual');
    }
}
