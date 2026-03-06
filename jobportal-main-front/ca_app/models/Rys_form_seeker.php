<?php
class Rys_form_seeker extends CI_Model
{
    public function get_assignment_by(
        $form_id, 
        $job_id, 
        $seeker_id, 
        $stage = null
    )
    {
        $this->db->select([
            'assignment_seeker.assignment_id',
            'assignment_seeker.form_id',
            'assignment_seeker.job_id',
            'assignment_seeker.affidavit_accept',
            'assignment_seeker.assignment_date',
            'assignment_seeker.seeker_id',
            'assignment_seeker.answered',
            'assignment_seeker.edit_answers',
            'assignment_seeker.answer_date',
            'assignment_seeker.active',
            'form.name AS form_name'
        ]);
        $this->db->from('tbl_rys_form_seekers assignment_seeker');
        $this->db->join('tbl_rys_forms form', 'form.form_id=assignment_seeker.form_id');
        $this->db->where('assignment_seeker.form_id', $form_id);
        //$this->db->where('assignment_seeker.job_id', $job_id);
        $this->db->where('assignment_seeker.seeker_id', $seeker_id);

        if (!empty($stage)) {
            $this->db->where('assignment_seeker.stage', $stage);
        }

        $this->db->order_by('assignment_seeker.assignment_id', 'DESC');

        return $this->db->get()->row();
    }

    public function get_assignment($assignment_id)
    {
        $this->db->select([
            'assignment_seeker.assignment_id',
            'assignment_seeker.form_id',
            'assignment_seeker.job_id',
            'assignment_seeker.affidavit_accept',
            'assignment_seeker.assignment_date',
            'assignment_seeker.seeker_id',
            'assignment_seeker.answered',
            'assignment_seeker.edit_answers',
            'assignment_seeker.active',
            'form.name AS form_name'
        ]);
        $this->db->from('tbl_rys_form_seekers assignment_seeker');
        $this->db->join('tbl_rys_forms form', 'form.form_id=assignment_seeker.form_id');
        $this->db->where('assignment_seeker.assignment_id', $assignment_id);

        return $this->db->get()->row();
    }

    public function search_all_polls(
        $seeker_id, 
        $filter = array(), 
        $per_page = 0, 
        $page = 0
    ) {
        $this->db->select([
            'form_assignment_seeker.assignment_id AS form_assignment_id',
            'form.form_ID AS form_id', 
            'form.name AS form_name',
            'form_assignment_seeker.answered AS form_poll_answered',
        ]);
        
        $this->db->from('tbl_rys_form_seekers form_assignment_seeker');
        $this->db->join('tbl_rys_forms form', 'form.form_id=form_assignment_seeker.form_id');
        $this->db->where('form_assignment_seeker.seeker_id', $seeker_id);
        $this->db->where('form_assignment_seeker.active', 1);
   
        $this->db->order_by('form_assignment_seeker.assignment_id', 'DESC');

        if ($per_page) {
            $this->db->limit($per_page, $page);
        }

        return $this->db->get()->result();
    }

    public function count_all_polls(
        $seeker_id, 
        $filter = []
    ) {
        $this->db->select([
            'form_assignment_seeker.assignment_id'
        ]);
        
        $this->db->from('tbl_rys_form_seekers form_assignment_seeker');
        $this->db->join('tbl_rys_forms form', 'form.form_id=form_assignment_seeker.form_id');
        $this->db->where('form_assignment_seeker.seeker_id', $seeker_id);
        $this->db->where('form_assignment_seeker.active', 1);

        return $this->db->count_all_results();
    }

    public function get_forms_with_affidavit_by(
        $job_id, 
        $seeker_id, 
        $stage = null
    )
    {
        $this->db->select([
            'assignment_seeker.assignment_id',
            'assignment_seeker.form_id',
             'assignment_seeker.job_id',
            'assignment_seeker.affidavit_accept',
            'assignment_seeker.assignment_date',
            'assignment_seeker.seeker_id',
            'assignment_seeker.answered',
            'assignment_seeker.edit_answers',
            'form.name AS form_name'
        ]);
        $this->db->from('tbl_rys_form_seekers assignment_seeker');
        $this->db->join('tbl_rys_forms form', 'form.form_id=assignment_seeker.form_id');
        $this->db->where('assignment_seeker.job_id', $job_id);
        $this->db->where('assignment_seeker.seeker_id', $seeker_id);
        $this->db->where('form.affidavit', 1);

        if (!empty($stage)) {
            $this->db->where('assignment_seeker.stage', $stage);
        }

        return $this->db->get()->result();
    }

    public function count_sekeer_form_assigned($job_id, $form_id, $stage)
    {
        $this->db->select('form_seeker.seeker_id');
        $this->db->from('tbl_rys_form_seekers form_seeker');
        $this->db->join('tbl_recruitment_candidates candidate', 'form_seeker.seeker_id=candidate.seeker_ID');
        $this->db->where('candidate.job_ID', $job_id);
        $this->db->where('form_seeker.form_id', $form_id);
        $this->db->where('candidate.stage', $stage);
        $this->db->where('candidate.discarded', 0);
        $this->db->where('form_seeker.active', 1);

        $this->db->group_by('form_seeker.seeker_id');
        
        return $this->db->count_all_results();
    }

    public function count_sekeer_form_unassigned($job_id, $form_id, $stage)
    {
        $count_assigned = $this->count_sekeer_form_assigned($job_id, $form_id, $stage);
        
        $count_seeker_stage = count($this->Recruitment_candidate->get_available_candidates_by_stage($job_id, $stage));

        return $count_seeker_stage - $count_assigned;
    }

    public function get_forms_assigned_by_seeker($seeker_id)
    {
        $this->db->select([
            'form_seeker.assignment_id AS form_assignment_id',
            'forms.form_id',
            'forms.name',
            'form_seeker.answered AS form_is_answered',
            'form_seeker.answer_date AS form_answer_date'
        ]);
        $this->db->from('tbl_rys_form_seekers form_seeker');
        $this->db->join('tbl_rys_forms forms', 'form_seeker.form_id=forms.form_id');

        $this->db->where('form_seeker.seeker_id', $seeker_id);
        $this->db->where('form_seeker.active', 1);

        $this->db->group_by('form_seeker.form_id');

        return $this->db->get()->result();
    }
}
