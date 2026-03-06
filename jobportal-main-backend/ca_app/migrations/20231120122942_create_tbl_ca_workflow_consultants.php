<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_tbl_ca_workflow_consultants extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'cia_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'overall_cia_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '150',
            ],
        ]);
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('overall_cia_code');
        $this->dbforge->create_table('tbl_ca_workflow_consultants');

        $this->insert_data();
    }

    public function down()
    {
        $this->dbforge->drop_table('tbl_ca_workflow_consultants');
    }

    private function insert_data()
    {
        $this->db->query("INSERT INTO tbl_ca_workflow_consultants (id,cia_code,overall_cia_code,name) VALUES
            (1,'','04','BUSINESS CONSULTANTS S.A.'),
            (2,'01','01','overall Business S.A.'),
            (3,'02','23','Overall Pharma SAC'),
            (4,'03','17','Trade Development'),
            (5,'04','22','Overall Oriente'),
            (6,'05','16','Marketing power'),
            (7,'06','05','Industry & logistics managamenty S.A.'),
            (8,'07','03','Executive solutions S.A.'),
            (9,'08','02','overall Strategy S.A.C.'),
            (10,'09','13','BIOTOSCANA FARMA DE PERU SAC'),
            (11,'10','19','CORPORACION ACEROS AREQUIPA S.A.'),
            (12,'11','35','NAANDAN JAIN PERU S.A.C.'),
            (13,'12','47','MOLITALIA S.A.'),
            (14,'13','48','QUALA PERU SAC'),
            (15,'14','49','ASSIST CARD PERU SAC'),
            (16,'15','54','SUPPLY & OPERATIONS SAC'),
            (17,'16','55','FULLCARGA SERVICIOS TRANSACCIONALES S.A.C.'),
            (18,'17','56','APOYO MATERIAL Y LOGISTICO S.A.C.'),
            (19,'18','62','TECNOFARMA S.A'),
            (20,'19','63','SUMMIT AGRO SOUTH AMERICA SPA, SUCURSAL PERÚ'),
            (21,'20','64','CHEIL PERU S.A.C.'),
            (22,'21','65','GAT PERU S.A.C'),
            (23,'22','67','TECNOLOGIA Y SOLUCIONES CONSTRUCTIVAS S.A.C.'),
            (24,'23','69','ATRIA ENERGIA SAC'),
            (25,'24','75','GANO ITOUCH S.A.C.'),
            (26,'25','76','ETERMAR - ENGENHARIA E CONSTRUÇÃO, SOCIEDAD ANONIMA SUCURSAL EN LA REPÚBLICA DEL PERÚ'),
            (27,'26','77','PROYECTOS DE INFRAESTRUCTURA DEL PERU S.A.C.'),
            (28,'27','84','FRONTERA ENERGY DEL PERÚ S.A.'),
            (29,'28','87','CONGLOMERADO ALESSIA SAC'),
            (30,'29','88','CONSTRUCCIONES MICOLLI SAC'),
            (31,'30','89','MANCORALAND SAC'),
            (32,'31','90','NOSHORE SAC'),
            (33,'32','91','SITES DEL PERU S.A.C.'),
            (34,'33','92','ACTIVA FACTORING S.A.C'),
            (35,'34','93','ATRIA SERVICIOS S.A.C.'),
            (36,'35','94','PERUANA DE INVERSIONES EN ENERGIAS RENOVABLES S.A.'),
            (37,'36','96','RUBIKA PROYECTOS S.A.C.'),
            (38,'37','97','CENTRO DE ORIENTACIÓN FAMILIAR');
        ");
    }
}
