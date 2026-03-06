<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_job_profile_codes_bussiness_unit_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'acronym' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ]
        ]);
        $this->dbforge->add_key('code', TRUE);
        $this->dbforge->add_key('acronym');
        $this->dbforge->create_table('tbl_job_profile_codes_bussiness_unit');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_job_profile_codes_bussiness_unit');
    }

    public function insert_data()
    {
        $business_unit = [
            'DD' => 'DES', //DESCENTRALIZACIÓN
            'MK' => 'MKT', //MARKETING
            'SI' => 'SIN',
            'HO' => 'SIN',
            'FR' => 'AME',
            'DI' => 'AME',
            //'IA' => 'IAC'
        ];

        foreach ($business_unit as $code => $acronym) {

            $this->db->insert('tbl_job_profile_codes_bussiness_unit', [
                'code' => $code,
                'acronym' => $acronym
            ]);
        }
    }
}
