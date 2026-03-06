<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report_seeker_applications extends CI_Controller
{	
    private $spreadsheet;
    private $question_ids;

	public function __construct()
    {
        parent::__construct();

        $this->load->model('Form_question');

        $this->spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->spreadsheet->setActiveSheetIndex(0);
    }

    public function export()
    {
        $this->build_header();
        $this->build_data();
        $this->download('reporte-postulaciones');
    }

    private function data()
    {
        $sql = "select  
        j.id as 'job_id', 
        j.job_title,
        a.dated as 'date_application',
        (case
            when s.document_type = 'dni' then 'Dni'
            when s.document_type = 'foreign_card' then 'Carnet de extranjería'
            when s.document_type = 'passport' then 'Pasaporte'
            else s.document_type
        end) as 'doc_iden_type',
        s.document_number as 'doc_iden_num',
        s.first_name,
        s.last_name,
        s.email,
        s.dob,
        s.city,
        (case
            when s.gender = 'male' then 'hombre'
            when s.gender = 'female' then 'mujer'
            else s.gender
        end) as 'gender',
        
        (case
            when s.civil_status = 'single' then 'soltero'
            when s.civil_status = 'married' then 'casado'
            when s.civil_status = 'divorced' then 'divorciado'
            when s.civil_status = 'cohabiting' then 'conviviente'
            else s.civil_status
        end) as 'civil_status',
        
        s.mobile,
        s.present_address,
        group_concat(concat('https://overall-portal-de-empleo.s3.amazonaws.com/','' , doc_emo.file_source)) as 'emo',
        group_concat(concat('https://overall-portal-de-empleo.s3.amazonaws.com/','' , doc_s.file_source)) as 'screening',
        group_concat(concat('https://overall-portal-de-empleo.s3.amazonaws.com/', '', doc_cv19.file_source)) as 'covid19',
        fs.assignment_id
                
        FROM tbl_post_jobs j     
        INNER JOIN tbl_seeker_applied_for_job a ON a.job_ID  = j.ID
        INNER JOIN tbl_job_seekers s ON s.ID  = a.seeker_ID 
        LEFT JOIN tbl_recruitment_attached_documents doc_emo ON doc_emo.job_ID=a.job_ID AND doc_emo.seeker_ID= a.seeker_ID AND doc_emo.key='certificate_emo'
        LEFT JOIN tbl_recruitment_attached_documents doc_s ON doc_s.job_ID=a.job_ID AND doc_s.seeker_ID= a.seeker_ID AND doc_s.key='screnning'
        LEFT JOIN tbl_recruitment_attached_documents doc_cv19 ON doc_cv19.job_ID=a.job_ID AND doc_cv19.seeker_ID= a.seeker_ID AND doc_cv19.key='certificate_covid19'
        LEFT JOIN tbl_rys_form_seekers fs ON fs.seeker_id=s.ID AND fs.form_id=1 AND fs.active=1

        WHERE j.job_title IN (
            'Operario PT',
            'Operario de Limpieza', 
            'Operario de Producción',
            'Operario de Almacen',
            'Operador de Montacarga',
            'Montacarguista',
            'Operador de Grua',
            'Auxiliar de Producción',
            'Auxiliar de Producto Terminado',
            'Todista',
            'Estibador',
            'Gruero',
            'Promotor', 
            'Promotor de ventas',
            'Asesor de Ventas',
            'Asesor Comercial',
            'Degustador',
            'Impulsador',
            'Jardinero'
        )
        
        GROUP BY j.ID, s.ID";
        
        return $this->db->query($sql)->result();
    }

    public function build_header()
    {
        $form_id = 1;//$this->filters['form_id'];

        $header[] = 'EMPLEO ID';	
        $header[] = 'EMPLEO';	
        $header[] = 'FECHA POSTULACIÓN';	
        $header[] = 'TIPO DOC IDENTIDAD';	
        $header[] = 'NUM DOC IDENTIDAD';	
        $header[] = 'NOMBRES';	
        $header[] = 'APELLIDOS';	
        $header[] = 'EMAIL';	
        $header[] = 'FECHA DE NACIMIENTO';	
        $header[] = 'UBIGEO';	
        $header[] = 'SEXO';	
        $header[] = 'ESTADO CIVIL'; 
        $header[] = 'N CELULAR';	
        $header[] = 'DIRECCIÓN ACTUAL'; 
        $header[] = 'EMO';	
        $header[] = 'Screening';	
        $header[] = 'Covid-19';

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

        $form_id = 1;

        foreach ($seekers as $index => $row) {
            $col = [];
            $col[] = $row->job_id;
            $col[] = $row->job_title;
            $col[] = $row->date_application;
            
            $col[] = $row->doc_iden_type;
            $col[] = $row->doc_iden_num;
            $col[] = $row->first_name;
            $col[] = $row->last_name;
            $col[] = $row->email;
            $col[] = $row->dob;
            $col[] = $row->city;
            $col[] = $row->gender;
            $col[] = $row->civil_status;
            $col[] = $row->mobile;
            
            $col[] = $row->present_address;
            $col[] = $row->emo;
            $col[] = $row->screening;
            $col[] = $row->covid19;
            
            if ($row->assignment_id) {
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
            }

            $this->spreadsheet->getActiveSheet()->fromArray(
                $col,
                null,
                'A' . ($index + 2)
            );
        }
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
	