<?php

class Rys_forms_answers_export extends CI_Model
{
    private $spreadsheet;
    private $filters;
    private $question_ids;

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;    
    }

    public function __construct($filters = null)
    {
        if (is_null($filters) === false) {
            $this->build($filters);
        }
    }

    public function init()
    {
        $this->load->model('Form_question');

        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);
    }

    public function build($filters = [])
    {
        $this->filters = $filters;

        $this->init();
        $this->build_header();
        $this->build_data();

        return $this;
    }

    public function build_header()
    {
        $form_id = $this->filters['form_id'];

        $header[] = 'Postulante';
        $header[] = 'Tipo Doc.';
        $header[] = 'N° Doc.';
        $header[] = 'Fecha Nac.';
        $header[] = 'Teléfono';
        $header[] = 'Cod Rys';
        $header[] = 'Rys Proceso';
        $header[] = 'Cod Solicitud';
        $header[] = 'Consultora';
        $header[] = 'Cliente';
        $header[] = 'Unidad de negocio';
        $header[] = 'Centro de costo';

        $this->db->from('tbl_rys_form_questions question');
        $this->db->where('question.form_id', $form_id);

        $questions = $this->db->get()->result();

        foreach ($questions as $key => $question) {
            $header[] = $question->name;
            $this->question_ids[$question->question_id] = $question->question_id;
        }

        if ($form_id == 1) {
            $header[] = 'Apto';
        } else if ($form_id == 2) {
            $header[] = 'Calificación';
        } else if ($form_id == 3) {
            $header[] = 'Resultado';
        }

        $this->spreadsheet->getActiveSheet()->fromArray(
            $header,
            null,
            'A1'
        );
    }

    public function build_data()
    {
        $seekers = $this->data();

        $form_id = $this->filters['form_id'];

        foreach ($seekers as $index => $row) {
            $col = [];
            $col[] = strtoupper($row->last_name . ' ' . $row->first_name);
            $col[] = document_type_abbr($row->document_type);
            $col[] = $row->document_number;
            $col[] = date('d/m/Y', strtotime($row->dob));
            $col[] = $row->mobile;
            $col[] = $row->job_id;
            $col[] = $row->job_title;
            $col[] = $row->request_id;
            $col[] = $row->consultant_name;
            $col[] = $row->client_company_name;
            $col[] = $row->business_unit_name;
            $col[] = $row->cost_center;

            $question_answers = $this->get_anwers($row->assignment_id, $this->question_ids);

            foreach ($question_answers as $answer) {
                
                if ($answer->type == 'checkbox') {
                    $answer_data = json_decode($answer->answer, true);
                    $col[] = $answer_data ? implode(',', $answer_data) : '';
                } else {
                    $col[] = $answer->answer;
                }
            }

            if ($form_id == 1) {
                $col[] = $this->Form_question->candidate_is_fit($row->assignment_id) ? 'Apto' : 'No apto';
            } else if ($form_id == 2) {
                $col[] = $this->Form_question->total_score($row->assignment_id);
            } else if ($form_id == 3) {
                $col[] = $this->Form_question->candidate_is_good($row->assignment_id) ? 'Apto' : 'Observada';
            }

            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );
        }
    }

    private function data()
    {
        $form_id = $this->filters['form_id'];
        //$job_id = $this->filters['job_id'];
        //$stage = $this->filters['stage'];

        $this->db->select([
            'form_seeker.assignment_id',
            'seeker.ID as seeker_id',
            'seeker.first_name',
            'seeker.last_name',
            'seeker.document_type',
            'seeker.document_number',
            'seeker.dob',
            'seeker.mobile',
            'job.ID AS job_id',
            'job.job_title',
            'request.ID AS request_id',
            'request.consultant_name',
            'request.client_company_name',
            'request.business_unit_name',
            'request.cost_center'
        ]);
        $this->db->from('tbl_rys_form_seekers form_seeker');
        $this->db->join('tbl_job_seekers seeker', 'seeker.ID=form_seeker.seeker_id');
        $this->db->join('tbl_recruitment_candidates rs_candidate', 
            'rs_candidate.seeker_ID=form_seeker.seeker_id'
        );
        $this->db->join('tbl_post_jobs job', 'job.ID=rs_candidate.job_ID');
        $this->db->join('tbl_staff_requests request', 'request.ID=job.request_ID', 'left');
        $this->db->where('form_seeker.form_id', $form_id);

        if (isset($this->filters['job_id']) && $this->filters['job_id'] != null) {
            $this->db->where('rs_candidate.job_ID', $this->filters['job_id']);
        }

        if (isset($this->filters['stage']) && $this->filters['stage'] != null) {
            $this->db->where('rs_candidate.stage', $this->filters['stage']);
        }

        if (isset($this->filters['start_date']) && 
            $this->filters['start_date'] && 
            isset($this->filters['end_date']) && 
            $this->filters['end_date']) {
            $this->db->where('answer_date>=', $this->filters['start_date'] . ' 00:00:00');
            $this->db->where('answer_date<=', $this->filters['end_date'] . ' 23:59:59');
        }

        $this->db->where('form_seeker.answered', 1);

        $this->db->group_by('rs_candidate.seeker_ID');
        $this->db->order_by('job.ID', 'DESC');

        return $this->db->get()->result();
    }

    public function get_anwers($a_id, $questions = [])
    {
        $str_question = implode(',', $questions);

        $this->db->select([
            'form_question.question_id',
            'form_question.type',
            'form_answer.answer'
        ]);
        $this->db->from('tbl_rys_form_questions form_question');
        $this->db->join('tbl_rys_form_sections form_section', 
            'form_section.section_id=form_question.section_id'
        );
        $this->db->join(
            'tbl_rys_form_seeker_answers form_answer', 
            'form_answer.assignment_id = "' . $a_id . '" AND form_answer.question_id = form_question.question_id', 
            'left'
        );

        $this->db->where_in('form_question.question_id', $questions);

        $this->db->where('form_question.active', 1);
        $this->db->where('form_section.active', 1);

        $this->db->order_by('form_question.question_id', 'ASC');

        return $this->db->get()->result();
    }

    public function download($filename = 'reporte-excel')
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function output()
    {
        ob_start();
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter(
            $this->spreadsheet, 
            'Xlsx'
        );
        $writer->save('php://output');
        $result_object = ob_get_clean();
        return $result_object;
    }
}
