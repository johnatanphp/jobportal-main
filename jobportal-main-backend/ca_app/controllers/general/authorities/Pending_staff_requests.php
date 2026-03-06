<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pending_staff_requests extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
		//Load helper
		$this->load->helper('form');

		//Load model
		$this->load->model('Staff_request_authoritation');

		$user_id = $this->session->userdata('user_id');
		
		if (!$this->session->userdata('is_employer')) {
			redirect('login');
		}
    }	

    public function index()
	{
		$this->search();
	}

	public function search()
	{
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Solicitudes pendientes - ' . SITE_NAME;

		//Obtener usuario en sesión
		$user_id = $this->session->userdata('user_id');
		
		$user = $this->Employer->find($user_id);

		$filters = array(
			'query' => $this->input->get('query')
		);

		$total_rows = $this->Staff_request->count_all_pending_staff_requests_by_authority_id(
			$user->email,
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

		$result_requests = $this->Staff_request->search_all_pending_staff_requests_by_authority_id(
			$user->email,
			$filters,
			$per_page, 
			$page
		);

		$data['links'] = $this->pagination->create_links();
		$data['result_requests'] = $result_requests;
		$data['total_requests'] = $total_rows;
		$data['filters'] = $filters;

		$this->load->view('general/authorities/pending_staff_requests', $data);
	}
}