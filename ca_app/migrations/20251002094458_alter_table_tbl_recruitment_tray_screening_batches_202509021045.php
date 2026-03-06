<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_recruitment_tray_screening_batches_202509021045 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'lambda_response' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'error_message' => [
                'type' => 'TEXT',
                'null' => false,
            ],
        ];

        $this->dbforge->add_column('tbl_recruitment_tray_screening_batches', $fields); 
    }

    public function down(){}
}
