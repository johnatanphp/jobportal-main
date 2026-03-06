<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_table_tbl_countries_202510171030 extends CI_Migration
{
    public function up()
    {
        $fields = [    
            'flag_icon' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->dbforge->add_column('tbl_countries', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_column('tbl_countries', 'flag_icon');
    }
}
