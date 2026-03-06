<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rys_forms extends CI_Controller 
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Rys_form');
        $this->load->model('Rys_form_question');
        $this->load->model('Rys_form_section');
    }

    public function index()
    {
        $this->search();
    }

    public function search()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Formularios creados - ' . SITE_NAME;

        //Obtener filtros
        $filters = [];
    
        $total_rows = $this->Rys_form->count_all(
            $filters
        );
    
        $config = pagination_configuration(
            pagination_url(), 
            $total_rows, 
            $this->config->item('rows_per_page_in_searches') ? $this->config->item('rows_per_page_in_searches') : 10, 
            3, 
            5, 
            true, 
            true, 
            true
        );

        $this->pagination->initialize($config);
        
        $page = (int)$this->input->get('page');
        $page_num = $page - 1;
        $per_page = $config['per_page'];
        $page_num = ($page_num < 0) ? '0' : $page_num;
        $page = $page_num * $config["per_page"];

        $forms = $this->Rys_form->search_all(
            $filters,
            $per_page
        );

        $data['links'] = $this->pagination->create_links();
        $data['filters'] = $filters;
        $data['forms'] = $forms;
        $data['total_rows'] = $total_rows;

        $this->load->view('employer/rys_forms/list', $data);
    }

    public function save($form_id = 0)
    {
        $data['ads_row'] = $this->ads;
        $form = $this->Rys_form->get_form_by_id($form_id);
        
        $data['form'] = $form;
        $data['title'] = ($form ? 'Editar' : 'Crear') . ' formulario - ' . SITE_NAME;

        $this->load->view('employer/rys_forms/save', $data);
    }

    public function do_save()
    {
        $this->db->trans_start();

        $form_id = $this->input->post('form_id');

        $form = $this->Rys_form->get_form_by_id($form_id);

        $form_data = [
            'name' => $this->input->post('name'),
            'stage' => $this->input->post('stage'),
            'header' => $this->input->post('header'),
            'footer' => $this->input->post('footer')
        ];

        if ($form) {
            $this->db->where('form_id', $form_id);
            $this->db->update('tbl_rys_forms', $form_data);

            $this->db->where('form_id', $form_id);
            $this->db->update('tbl_rys_form_sections', ['active' => 0]);
            
            $this->db->where('form_id', $form_id);
            $this->db->update('tbl_rys_form_questions', ['active' => 0]);

        } else {
          $this->db->insert('tbl_rys_forms', $form_data);
            $form_id = $this->db->insert_id();
        }
        
        $sections = (array)$this->input->post('sections');

        foreach ($sections as $section_id => $section) {
           

            $data_section = [
                'name' => $section['name'],
                'active' => 1,
                'form_id' => $form_id
            ]; 
            $section_row = $this->Rys_form_section->find($section_id);


            if ($section_row) {
                $this->db->where('section_id', $section_id);
                $this->db->update('tbl_rys_form_sections', $data_section);
            } else {
                $this->db->insert('tbl_rys_form_sections', $data_section);
                $section_id = $this->db->insert_id();
            }
            
            $questions = (array)$section['question'];

            foreach ($questions as $question_id => $question) {

                $question_row = $this->Rys_form_question->find($question_id);

                $name_question = $question['name'];
                $type_question = $question['type_question'];
                $required = isset($question['required']) ? 1 : 0;
                $value = null;     

                if ($type_question == 'checkbox' || 
                    $type_question == 'radio' ||
                    $type_question == 'dropdown'
                ) {
                    $options = $question['option'];

                    $option_value = [];

                    foreach ($options as $key => $value) {
                        $value = trim($value);
                        if ($value == '') {
                            continue;
                        }
                        $option_value[]['value'] = $value;
                    }
                    $value = json_encode(['options' => $option_value]);
                }

                $question_data = [
                    'form_id' => $form_id,
                    'section_id' => $section_id,
                    'name' => $name_question,
                    'type' => $type_question,
                    'options' => $value,
                    'required' => $required,
                    'score' => !empty($question['score']) ? $question['score'] : null,
                    'answer' => trim($question['answer']),
                    'active' => 1
                ];

                if ($question_row) {
                    $this->db->where('question_id', $question_id);
                    $this->db->update('tbl_rys_form_questions', $question_data);
                } else {
                    $this->db->insert('tbl_rys_form_questions', $question_data);
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === true) {
             $this->session->set_flashdata(
                'msg', 
                '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a> 
                    ¡Formulario guardado con éxito!
                </div>'
            );
        } else {
            $this->session->set_flashdata(
                'msg', 
                '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a> 
                    ¡Formulario no se pudo guardar!
                </div>'
            );
        }

        redirect('employer/rys_forms');
    }

    public function get_sections($form_id = 0)
    {
        $this->db->from('tbl_rys_form_sections');
        $this->db->where('form_id', $form_id);
        $this->db->where('active', 1);

        $results = $this->db->get()->result();

        echo json_encode([
            'sections' => $results
        ]);
    }

    public function get_questions($section_id = 0)
    {
        $this->db->from('tbl_rys_form_questions');
        $this->db->where('section_id', $section_id);
        $this->db->where('active', 1);

        $results = $this->db->get()->result();

        echo json_encode([
            'questions' => $results
        ]);
    }

    public function export()
    {   
        if (!$this->input->get('form_id')) {
            $data['title'] = 'Exportar Formularios';
            $data['ads_row'] = $this->ads;        
            $data['forms'] = $this->Rys_form->all();
            $data['date_ranges'] = get_date_ranges();
            $this->load->view('employer/rys_forms/export', $data);
            return;
        }

        $filters = [
            'form_id' => $this->input->get('form_id'),
            'job_id' => null,
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date') 
        ];

        $this->load->library(
            'Exports/Rys_forms_answers_export',
            $filters,
            'Rys_forms_answers_export'
        );

        $this->Rys_forms_answers_export->download(
            'Formularios-respuestas-' . date('YmdHis')
        );
    }
}
