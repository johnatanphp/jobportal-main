<?php
class Rys_form_question extends CI_Model
{   
    //protected $table = 'tbl_rys_form_questions';
    //protected $primary_key = 'question_id';

    public function find($id)
    {
        $this->db->from('tbl_rys_form_questions');
        $this->db->where('question_id', $id);

        return $this->db->get()->row();
    }

    public function get_active_by_form_id($form_id)
    {
        $this->db->select([
            'form_question.question_id',
            'form_question.name AS question_name',
            'form_question.type AS question_type',
            'section.section_id AS section_id',
            'form_question.required',
            'form_question.options',
            'form_question.answer',
            'section.name AS section_name'
        ]);
        $this->db->from('tbl_rys_form_questions form_question');
        $this->db->join('tbl_rys_form_sections section', 'section.section_id=form_question.section_id');

        $this->db->where('form_question.form_id', $form_id);
        
        $this->db->where('form_question.active', 1);
        $this->db->where('section.active', 1);

        $this->db->order_by('section.order', 'ASC');
        $this->db->order_by('form_question.question_id', 'ASC');
        
        return $this->db->get()->result();
    }

    public function attach_file()
    {


    }

}
