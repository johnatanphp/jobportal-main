<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_column_tbl_seeker_academic_202312281220 extends CI_Migration
{
    public function up()
    {
        $fields = [
            'institution_educational_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],    
            'institution_type_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ],
            'institution_educational_class_id' => [
                'type' => 'INT',
                'unsigned' => TRUE,
                'null' => true
            ], 
            'institution' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => true
            ],   
            'career' => [
                'type' => 'VARCHAR',
                'constraint' => '32',
                'null' => true
            ],   
            'tuition_number' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
                'null' => true
            ]
        ];

        $this->dbforge->add_column('tbl_seeker_academic', $fields); 

        $this->db->query('ALTER TABLE tbl_seeker_academic ADD CONSTRAINT FOREIGN KEY (institution_educational_type_id) REFERENCES tbl_institution_educational_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_seeker_academic ADD CONSTRAINT FOREIGN KEY (institution_type_id) REFERENCES tbl_institution_types(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_seeker_academic ADD CONSTRAINT FOREIGN KEY (institution_educational_class_id) REFERENCES tbl_institution_educational_classes(id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_seeker_academic ADD CONSTRAINT FOREIGN KEY (institution) REFERENCES tbl_institutions(code) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->db->query('ALTER TABLE tbl_seeker_academic ADD CONSTRAINT FOREIGN KEY (career) REFERENCES tbl_careers(code) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }

    public function down()
    {
        $this->dbforge->drop_colum('tbl_seeker_academic', 'institution_educational_type_id');
        $this->dbforge->drop_colum('tbl_seeker_academic', 'institution_type_id');
        $this->dbforge->drop_colum('tbl_seeker_academic', 'institution_educational_class_id');
        $this->dbforge->drop_colum('tbl_seeker_academic', 'institution');
        $this->dbforge->drop_colum('tbl_seeker_academic', 'career');
        $this->dbforge->drop_colum('tbl_seeker_academic', 'tuition_number');
    }
}
