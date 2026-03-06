<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_cost_center_manager_table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'manager' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'no_cia' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'cod_clie' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'cost_center' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('tbl_cost_center_manager');
        $this->insert_data_to_table();
    
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_cost_center_manager');
    }

    public function insert_data_to_table()
    {
        $data = array(
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C19416923001',
                'cost_center' => '02-MK04-AMDI-231'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100030838',
                'cost_center' => '16-MK01-GWYI-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100035121',
                'cost_center' => '16-MK03-MOLI-226'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100052050',
                'cost_center' => '16-MK01-PERF-224'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100113610',
                'cost_center' => '16-MK04-BACK-235'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100113610',
                'cost_center' => '16-MK04-BACK-245'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100113610',
                'cost_center' => '16-MK04-BACK-249'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100113610',
                'cost_center' => '16-MK04-BACK-248'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100113610',
                'cost_center' => '16-MK04-BACK-234'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100119227',
                'cost_center' => '16-MK01-3MMM-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100152941',
                'cost_center' => '16-MK01-KIMB-217'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '02',
                'cod_clie' => 'C20100154308',
                'cost_center' => '02-MK01-FERN-220'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20100190797',
                'cost_center' => '16-MK01-GLOR-209'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '02',
                'cod_clie' => 'C20100617332',
                'cost_center' => '02-MK01-RINT-226'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '02',
                'cod_clie' => 'C20100919002',
                'cost_center' => '02-MK01-COLG-232'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '02',
                'cod_clie' => 'C20101031773',
                'cost_center' => '02-MK01-PFUN-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '02',
                'cod_clie' => 'C20255172884',
                'cost_center' => '02-MK01-SANC-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20263158327',
                'cost_center' => '16-MK04-DIAG-220'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20263317573',
                'cost_center' => '16-MK04-DIRS-245'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20330822661',
                'cost_center' => '16-MK01-HENK-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20330822661',
                'cost_center' => '16-MK04-HENK-204'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20347100316',
                'cost_center' => '16-MK01-ADID-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20348735692',
                'cost_center' => '16-MK04-BIMB-208'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20358161431',
                'cost_center' => '16-MK04-DIAL-245'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20370146994',
                'cost_center' => '16-MK04-ACER-226'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '02',
                'cod_clie' => 'C20375755344',
                'cost_center' => '02-MK01-LGEL-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '03',
                'cod_clie' => 'C20375755344',
                'cost_center' => '03-MK04-LGEL-214'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20455188688',
                'cost_center' => '16-MK04-B&JC-245'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20467539842',
                'cost_center' => '16-MK04-DEPR-245'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '02',
                'cod_clie' => 'C20471744493',
                'cost_center' => '02-MK01-CCIN-201'
            ),
            array(
                'manager' => 'ARNALDO AQUIJE',
                'no_cia' => '16',
                'cod_clie' => 'C20482596933',
                'cost_center' => '16-MK04-DVAR-245'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20487686019',
                'cost_center' => '16-MK04-CINC-245'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20494318483',
                'cost_center' => '16-MK04-INOB-245'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '03',
                'cod_clie' => 'C20505243537',
                'cost_center' => '03-MK04-ZTEC-201'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20511063184',
                'cost_center' => '16-MK01-LENO-201'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20514720127',
                'cost_center' => '16-MK04-OSTR-251'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20522121208',
                'cost_center' => '16-MK04-PYAL-245'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20523317815',
                'cost_center' => '16-MK01-HASB-201'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20535589331',
                'cost_center' => '16-MK04-ALGL-226'
            ),
            array(
                'manager' => 'CARLOS QUIROZ',
                'no_cia' => '16',
                'cod_clie' => 'C20544563441',
                'cost_center' => '16-MK04-MAPE-224'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20550914809',
                'cost_center' => '16-MK04-QUAL-240'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20554389784',
                'cost_center' => '16-MK01-CINE-201'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20563983176',
                'cost_center' => '16-MK04-INDA-245'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20574782768',
                'cost_center' => '16-MK04-OLAR-245'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20600633873',
                'cost_center' => '16-MK04-EMCO-245'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20602178073',
                'cost_center' => '16-MK04-TSIS-245'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20603138831',
                'cost_center' => '16-MK01-ACCO-224'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20608323725',
                'cost_center' => '16-MK01-DASI-201'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20608718657',
                'cost_center' => '16-MK01-FORE-201'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20609102277',
                'cost_center' => '16-MK04-TCPH-201'
            ),
            array(
                'manager' => 'MARIA FATIMA FIGUEROA',
                'no_cia' => '16',
                'cod_clie' => 'C20610822909',
                'cost_center' => '16-MK04-VACR-245'
            ),
            
        );

        $this->db->insert_batch('tbl_cost_center_manager', $data);
    }
}
