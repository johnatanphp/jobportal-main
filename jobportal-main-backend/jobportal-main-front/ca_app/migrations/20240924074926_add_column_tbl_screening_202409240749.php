<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_screening_202409240749 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'its_cron_update' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => true,
                'default' => 0,
            ],
            'its_data_prosecution' => [
                'type' => 'TINYINT',
                'unsigned' => TRUE,
                'null' => true,
                'default' => 0,
            ],
        ];

        $this->dbforge->add_column('tbl_screening', $fields);
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_screening', 'its_cron_update');
        $this->dbforge->drop_column('tbl_screening', 'its_data_prosecution');
    }
}
