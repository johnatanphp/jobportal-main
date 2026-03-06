<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rys_seeker_fit_helper
{   
    private $ci;

    public function __construct()
    {
        $this->ci = & get_instance();

        $this->ci->load->model('Rys_form_seeker');
        $this->ci->load->model('Form_question');
    }

    public function is_fit($job_id, $seeker_id, $stage = null)
    {
        $seeker_is_fit = null;

        $this->ci->db->from('tbl_rys_forms');
        $forms = $this->ci->db->get()->result();

        foreach ($forms as $key => $form) {
            
            $assignment = $this->ci->Rys_form_seeker->get_assignment_by(
                $form->form_id, 
                $job_id, 
                $seeker_id
            );                     

            //Verificar respuestas del formulario - Declaración jurada
            if ($form->form_id == 1 && $assignment && $assignment->answered == 1) {
                $seeker_is_fit = $this->ci->Form_question->candidate_is_fit($assignment->assignment_id);
            }

            //Verificar respuestas del formulario - Evaluación
            //Si la calificación es mayor a 12 el candidato es aprovado
            if ($form->form_id == 2 && $assignment && $assignment->answered == 1) {
                $seeker_is_fit = $this->ci->Form_question->total_score($assignment->assignment_id) > 12;
            }

            //Verificar respuestas del formulario - Antecedentes Médicos
            if ($form->form_id == 3 && $assignment && $assignment->answered == 1) {
                $seeker_is_fit = $this->ci->Form_question->candidate_is_good($assignment->assignment_id);
            }
        }

        //Verificar si todos los mo documentos estan aprovados 
/*
        $mo_doc_approved = $this->exist_mo_doc_approved(
            $job_id, 
            $seeker_id, 
            $stage
        );

        if ($mo_doc_approved === null) {
            return $seeker_is_fit;
        }

        $seeker_is_fit = $mo_doc_approved;
*/
        return $seeker_is_fit;
    }

    public function is_fit_to_move($job_id, $seeker_id, $stage = null)
    {
        $rs_seeker = $this->ci->db->get_where('tbl_recruitment_candidates', [
            'job_ID' => $job_id,
            'seeker_ID' => $seeker_id
        ])->row();

        //ignorar respuestas
        if ($rs_seeker->ignore_form_answers) {
            return true;
        }

        $seeker_is_fit = true;

        $this->ci->db->from('tbl_rys_forms');
        $forms = $this->ci->db->get()->result();

        foreach ($forms as $key => $form) {
            
            $assignment = $this->ci->Rys_form_seeker->get_assignment_by(
                $form->form_id, 
                $job_id, 
                $seeker_id
            );                     

            //Verificar respuestas del formulario - Declaración jurada
            // if ($form->form_id == 1 && $assignment && $assignment->answered == 1) {
            //     $seeker_is_fit = $this->ci->Form_question->candidate_is_fit($assignment->assignment_id);
            // }

            //Verificar respuestas del formulario - Evaluación
            //Si la calificación es mayor a 12 el candidato es aprovado
            if ($form->form_id == 2 && $assignment && $assignment->answered == 1) {
                $seeker_is_fit = $this->ci->Form_question->total_score($assignment->assignment_id) > 12;
            }
        }

        return $seeker_is_fit;
    }

    private function exist_mo_doc_approved(
        $job_id, 
        $seeker_id, 
        $stage
    )
    {
        $this->ci->db->from('tbl_exam_request_results mo_document');
        $this->ci->db->join(
            'tbl_rys_exam_document_stages mo_doc_stage', 
            'mo_doc_stage.document_id=mo_document.document_id'
        );
        $this->ci->db->where('mo_document.job_id', $job_id);
        $this->ci->db->where('mo_document.seeker_id', $seeker_id);
        $this->ci->db->where('mo_doc_stage.stage', $stage);

        $result = $this->ci->db->get()->result();

        if (!$result) {
            return null;
        }

        foreach ($result as $key => $doc) {
            if ($doc->approved == 0) {
                return false;
            }
        }

        return true;
    }

    public function form_is_expired($form_id, $seeker_id, $stage = null)
    {
        if ($form_id != 1) {
            return false;
        }

        $this->ci->db->from('tbl_rys_form_seekers');
        $this->ci->db->where('form_id', $form_id);
        $this->ci->db->where('seeker_id', $seeker_id);

        if (is_null($stage) == false) {
            $this->ci->db->where('stage', $stage);
        }

        $this->ci->db->order_by('assignment_id', 'DESC');

        $form_seeker = $this->ci->db->get()->row();

        if (!$form_seeker || !$form_seeker->answer_date) {
            return false;
        }

        $now_date = new DateTime('now');
        $answer_date = new DateTime($form_seeker->answer_date);
        $interval = $now_date->diff($answer_date);

        return $interval->m != 0;
    }
}
