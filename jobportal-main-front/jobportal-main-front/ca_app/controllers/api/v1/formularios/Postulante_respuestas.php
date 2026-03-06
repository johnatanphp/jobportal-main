<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Postulante_respuestas extends REST_Controller
{
    public function index_get()
    {
        $input = $this->input->get();

        $formulario_ids = isset($input['formulario_ids']) ? (array)$input['formulario_ids'] : [];

        if (count($formulario_ids) == 0) {
            $this->response([
                'status' => false,
                'error' => 'Debe ingresar los parámetros - formulario_ids es requerido'
            ], 200); 
            return;
        }

        if (!isset($input['fecha_respuesta_inicio']) || 
            !isset($input['fecha_respuesta_fin'])) {
            $this->response([
                'status' => false,
                'error' => 'Debe ingresar los parámetros - fecha_respuesta_inicio y fecha_respuesta_fin son requeridos'
            ], 200);

            return;
        }
 
        $this->db->select([
            'form.ref_id AS ref_form_id',
            'seeker.document_number',
            'seeker.first_name',
            'seeker.last_name',
            'form_seeker.assignment_id',
            'form_seeker.form_id',
            'form_seeker.answer_date as form_answer_date'
        ]);

        $this->db->from('tbl_rys_form_seekers form_seeker');
        $this->db->join('tbl_rys_forms form', 'form.form_id=form_seeker.form_id');
        $this->db->join(
            'tbl_recruitment_candidates rys_seeker', 
            'rys_seeker.seeker_ID = form_seeker.seeker_id'
        );
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID = form_seeker.seeker_id');

        $this->db->where('rys_seeker.stage', 7);
        $this->db->where('rys_seeker.discarded', 0);
        $this->db->where('form_seeker.answered', 1);
        $this->db->where('form_seeker.active', 1);
        $this->db->where_in('form.ref_id', $formulario_ids);
        $this->db->where('form_seeker.answer_date >=', trim($input['fecha_respuesta_inicio']));
        $this->db->where('form_seeker.answer_date <=', trim($input['fecha_respuesta_fin']));

        if (isset($input['nro_doc_identidad'])) {
            $this->db->where('seeker.document_number', $input['nro_doc_identidad']);
        }
        
        $this->db->order_by('form_seeker.assignment_id', 'DESC');
        
        $this->db->group_by('form_seeker.seeker_ID');
        $this->db->group_by('form_seeker.form_id');
        
        $result = $this->db->get()->result();

        $data = [];
        $data_row = []; 
        
        foreach ($result as $row) {
            
            $data_row['nro_doc_identidad'] = $row->document_number;
            $data_row['nombres'] = $row->first_name;
            $data_row['apellidos'] = $row->last_name;
            $data_row['fecha_respuesta'] = $row->form_answer_date;
            $data_row['formulario_id'] = $row->ref_form_id;

            $questions = $this->search_questions($row);

            $data_answers = [];

            foreach ($questions as $question) {
                $data_answers[] = [
                    'pregunta_id' => $question->ref_question_id,
                    'pregunta' => $question->question_name,
                    'respuesta' => $this->format_answer($question)
                ];
            }

            $data_row['respuestas'] = $data_answers;

            $data[] = $data_row;
        }

        $this->response([
            'status' => true,
            'data' => $data
        ], 200);
    }

    private function search_questions($row)
    {
        $this->db->select([
            'form_question.ref_id AS ref_question_id',
            'form_question.name AS question_name',
            'form_question.type AS question_type',
            'form_answer.answer question_answer'
        ]);
        $this->db->from('tbl_rys_form_questions form_question');
        $this->db->join(
            'tbl_rys_form_seeker_answers form_answer', 
            'form_question.question_id=form_answer.question_id AND form_answer.assignment_id="' . $row->assignment_id . '"'
        );

        $this->db->where('form_question.form_id', $row->form_id);

        return $this->db->get()->result();
    }

    private function format_answer($row)
    {        
        $is_answer_multiple = $row->question_type == 'checkbox';
        
        if ($is_answer_multiple == false) {
            return $row->question_answer;
        }

        $answer_multiple = @json_decode($row->question_answer, true);

        if (!$answer_multiple) {
            return null;
        }

        return count($answer_multiple) == 1 ? $answer_multiple[0] : $answer_multiple;
    }
}
