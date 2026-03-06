<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_forms extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
/*
        if (!is_jobseeker_data_complete()) {
            redirect('jobseeker/my_account');
            exit;
        }
*/
        $this->load->model('Rys_form_seeker');
    }

    public function index()
    {
        $this->search();   
    }

    public function search()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Mis encuestas - ' . SITE_NAME;

        //Obtener filtros
        $filters = [];

        //Obtener usuario en sesión
        $user_id = $this->session->userdata('user_id');

        $total_rows = $this->Rys_form_seeker->count_all_polls(
            $user_id, 
            $filters
        );
    
        $config = pagination_configuration(pagination_url(), $total_rows, 15, 3, 5, true, true, true);

        $this->pagination->initialize($config);
        
        $page = (int)$this->input->get('page');
        $page_num = $page - 1;
        $per_page = $config['per_page'];
        $page_num = ($page_num < 0) ? '0' : $page_num;
        $page = $page_num * $config["per_page"];

        $results = $this->Rys_form_seeker->search_all_polls(
            $user_id, 
            $filters,
            $per_page, 
            $page
        );

        $data['links'] = $this->pagination->create_links();
        $data['results'] = $results;
        $data['total_rows'] = $total_rows;

        $this->load->view('jobseeker/polls/my_polls_view', $data);
    }
}
