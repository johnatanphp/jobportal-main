<?php
class Rys_form_section extends CI_Model
{   
    //protected $table = 'tbl_rys_form_sections';
    //protected $primary_key = 'section_id';

    public function find($id)
    {
        $this->db->from('tbl_rys_form_sections');
        $this->db->where('section_id', $id);

        return $this->db->get()->row();
    }
}
