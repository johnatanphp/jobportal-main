<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class List_users extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

    }

    public function index()
    {
        $this->search();
    }

    public function search()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Cuentas de usuarios';

        $filters = array(
            'query' => $this->input->get('query')
        );

        $row = $this->Employer->get_employer_by_id($this->session->userdata('user_id'));

        //Pagination starts
        $total_rows = $this->Employer->count_search_users($row->company_ID, $filters);
    
        $config = pagination_configuration(
            current_url(), 
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
        $page_num = $page-1;
        $page_num = ($page_num<0)?'0':$page_num;
        $page = $page_num*$config["per_page"];
        $data["links"] = $this->pagination->create_links();
        //Pagination ends

        //Employers by company
        $result_employers = $this->Employer->search_users($row->company_ID, $filters, $config["per_page"], $page);
        $company_logo = ($row->company_logo)?$row->company_logo:'no_logo.jpg';

        $data['row'] = $row;
        $data['result_employers'] = $result_employers;
        $data['company_logo'] = $company_logo;
        $data['filters'] = $filters;
        $this->load->view('employer/users/users_list', $data);
    }
}
