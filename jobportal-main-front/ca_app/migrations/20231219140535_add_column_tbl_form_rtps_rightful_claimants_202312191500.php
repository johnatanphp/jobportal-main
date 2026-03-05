<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_form_rtps_rightful_claimants_202312191500 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'domicile_way_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],    
            'domicile_number' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],
            'domicile_interior' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'nationality_id' => [
                'type' => 'INT',
                'unsigned' => FALSE,
                'null' => true
            ],  
        ];

        $this->dbforge->add_column('tbl_form_rtps_rightful_claimants', $fields); 
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_form_rtps_rightful_claimants', 'domicile_way_id');
        $this->dbforge->drop_colum('tbl_form_rtps_rightful_claimants', 'domicile_number');
        $this->dbforge->drop_colum('tbl_form_rtps_rightful_claimants', 'domicile_interior');
        $this->dbforge->drop_colum('tbl_form_rtps_rightful_claimants', 'nationality_id');
    }
}
