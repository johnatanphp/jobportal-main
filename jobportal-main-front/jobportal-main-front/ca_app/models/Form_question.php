<?php
class Form_question extends CI_Model
{    
    public function __construct()
    {
        $this->load->model('Rys_form');
        $this->load->model('Rys_form_seeker');
    }

    public function total_score($assignment_id)
    {
        $query = $this->db->query("
            SELECT 
            answer.answer,
            form_question.options,
            form_question.answer AS answer_correct,
            form_question.score
            FROM tbl_rys_forms form
            INNER JOIN tbl_rys_form_questions form_question ON form_question.form_id=form.form_id
            INNER JOIN tbl_rys_form_seeker_answers answer ON answer.question_id=form_question.question_id AND form_question.form_id=answer.form_id

            WHERE answer.assignment_id = '" . $assignment_id . "'
        ");

        $result = $query->result();
        $score = 0;

        foreach ($result as $row) {

            if ($row->answer_correct == $row->answer) {
                $score+= $row->score;
            }
        }

        return $score;
    }

    public function is_correct($assignment_id, $question_id)
    {
        $query = $this->db->query("
            SELECT 
            answer.answer,
            form_question.options,
            form_question.answer AS answer_correct
            FROM tbl_rys_forms form
            INNER JOIN tbl_rys_form_questions form_question ON form_question.form_id=form.form_id
            INNER JOIN tbl_rys_form_seeker_answers answer ON answer.question_id=form_question.question_id AND form_question.form_id=answer.form_id

            WHERE answer.assignment_id = '" . $assignment_id . "' AND form_question.question_id = '" . $question_id . "'
        ");

        $row = $query->row();

        return $row->answer_correct == $row->answer;
    }

    public function candidate_is_fit($assignment_id)
    {
        $form_assignment = $this->Rys_form_seeker->get_assignment(
            $assignment_id
        );

        $seeker = $this->Job_seeker->get_job_seeker_by_id($form_assignment->seeker_id);

        //Calcular la edad del postulante
        //Si es igual o mayor a 65 - postulante con riesgo
        
        $age = get_age($seeker->dob);

        if ($age >= 65) {
            return false;
        }

        //Calcular ICM del postulante
        //Si es igual o mayor a 40 - postulante con riesgo

        $seeker_weight = $this->get_answer(
            $form_assignment->assignment_id,
            3
        );

        $seeker_height = $this->get_answer(
            $form_assignment->assignment_id,
            4
        );

        $icm = $seeker_weight->answer / ($seeker_height->answer * $seeker_height->answer);
    
        if (round($icm, 2) >= 35) {
            return false;
        }

        //Revisar respuestas candidato
        //Si al menos una es SI - postulante es observable

        $questions = $this->Rys_form->get_questions_radios_by_form_id($form_assignment->form_id);

        foreach ($questions as $key => $question) {
    
            $question_answer = $this->get_answer(
                $form_assignment->assignment_id,
                $question->question_id
            );

            if (!$question_answer) {
                continue;
            }

            if ($question_answer->answer == 'SI') {
                return false;
            }
        }

        return true;
    }

    public function candidate_is_good($assignment_id)
    {
        $form_assignment = $this->Rys_form_seeker->get_assignment(
            $assignment_id
        );

        $questions = $this->Rys_form->get_questions_radios_by_form_id($form_assignment->form_id);

        foreach ($questions as $key => $question) {
    
            $question_answer = $this->get_answer(
                $form_assignment->assignment_id,
                $question->question_id
            );

            if ($question_answer->answer == 'SI') {
                return false;
            }
        }

        //Revisar Si tiene otras enfermedades
        $question_answer = $this->get_answer($form_assignment->assignment_id, 57);

        if (trim($question_answer->answer) != '') {
            return false;
        }

        return true;
    }

    public function get_answer(
        $assignment_id, 
        $question_id
    )
    {
        $this->db->select([
            'answer.*'
        ]);

        $this->db->from('tbl_rys_form_seekers form_seeker');
        $this->db->join('tbl_rys_form_seeker_answers answer', 'form_seeker.assignment_id=answer.assignment_id');
        $this->db->where('form_seeker.assignment_id', $assignment_id);
        $this->db->where('answer.question_id', $question_id);

        return $this->db->get()->row();
    }
}
