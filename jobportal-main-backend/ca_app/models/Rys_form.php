<?php
class Rys_form extends CI_Model
{   
    //protected $table = 'tbl_rys_forms';
    //protected $primary_key = 'form_id';

    public function get_form_by_id($form_id)
    {
        $this->db->from('tbl_rys_forms');
        $this->db->where('form_id', $form_id);

        return $this->db->get()->row();
    }
    
    public function all()
    {
        $this->db->from('tbl_rys_forms');
 
        return $this->db->get()->result();
    }

    public function search_all(
        $filters = [], 
        $per_page = 0, 
        $page = 0
    )
    {
        $this->db->from('tbl_rys_forms');
    
        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

        return $this->db->get()->result();
    }

    public function count_all(
        $filters = [], 
        $per_page = 0, 
        $page = 0
    )
    {
        $this->db->from('tbl_rys_forms');

        return $this->db->count_all_results();
    }

    public function get_questions_by_form_id($form_id)
    {
        $this->db->select([
            'form_question.question_id',
            'form_question.name AS question_name',
            'form_question.type AS question_type',
            'section.section_id AS section_id',
            'form_question.required',
            'form_question.options',
            'section.name AS section_name'
        ]);
        $this->db->from('tbl_rys_form_questions form_question');
        $this->db->join('tbl_rys_form_sections section', 'section.section_id=form_question.section_id');

        $this->db->where('form_question.form_id', $form_id);
        $this->db->order_by('section.order', 'ASC');
        $this->db->order_by('form_question.question_id', 'ASC');

        return $this->db->get()->result();
    }

    public function get_questions_radios_by_form_id($form_id)
    {
        $this->db->select([
            'form_question.question_id',
            'form_question.name AS question_name',
            'form_question.type AS question_type',
            'section.section_id AS section_id',
            'form_question.required',
            'form_question.options',
            'section.name AS section_name'
        ]);
        $this->db->from('tbl_rys_form_questions form_question');
        $this->db->join('tbl_rys_form_sections section', 'section.section_id=form_question.section_id');

        $this->db->where('form_question.form_id', $form_id);
        $this->db->where('form_question.type', 'radio');
        $this->db->order_by('section.order', 'ASC');
        $this->db->order_by('form_question.question_id', 'ASC');
        
        return $this->db->get()->result();
    }

    public function get_forms_by_stage($stage)
    {
        $this->db->select([
            'form.form_id',
            'form.name AS form_name'
        ]);
        $this->db->from('tbl_rys_forms form');
        $this->db->where('form.stage', $stage);

        return $this->db->get()->result();
    }
}
