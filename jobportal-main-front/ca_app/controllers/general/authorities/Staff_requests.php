<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_requests extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

		$this->load->model('Staff_request_authoritation');
		$this->load->model('Mof');
		$this->load->model(
			'Employer_staff_request_manage_business_unit'
		);
    }
    
    public function show()
    {
		$this->load->helper('form');

		$token = trim($this->input->get('t'));

		$authorization = $this->Staff_request_authoritation->get_authorization_by_token($token);

		if (!$authorization) {
			show_404();
		}

		$request_id = $authorization->request_ID;

		if ($authorization->type_authority_ID == 1 && 
		    $this->Staff_request_authoritation->exist_authorization_of_DR($request_id)) {
			$authorization->is_authorized = 1;
		}

		$request = $this->Staff_request->get_staff_request_by_id($request_id);

		if (!$request) {
			show_404();
		}

		$data = get_data_staff_request_internal($request_id);

		$data['authorization'] = $authorization;
		$data['ads_row'] = $this->ads;
		$data['title'] = 'Ver solicitud interna - ' . SITE_NAME;

    	$this->load->view('general/authorities/show_staff_request_view', $data);
    }

    public function authorize()
    {
    	$token = trim($this->input->post('t'));

		$authorization = $this->Staff_request_authoritation->get_authorization_by_token($token);

		if (!$authorization) {
			show_404();
		}

		if ($authorization->is_authorized == null) {
			
			$request_id = $authorization->request_ID;
			$status = $this->Staff_request_authoritation->authorize_request($token, $request_id);

			if ($status) {
				$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> Se ha autorizado la solicitud!.</div>');
				$this->notify_by_email_the_response_authority($token);

				$count_total_authorization = $this->Staff_request_authoritation->total_necessary_authorizations($request_id);
				$authorizations = $this->Staff_request_authoritation->get_count_authorizations_by_request($request_id);

				if ($authorizations == $count_total_authorization) { 
					$this->notify_by_email_the_authorization_staff_request($request_id);
				}
			} else {
				$this->session->set_flashdata('msg', '<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Ha ocurrido un error!.</div>');
			}
		}

		redirect('general/authorities/staff_requests/show?t=' . $token);
    }

    public function deny()
    {
    	$token = trim($this->input->post('t'));

		$authorization = $this->Staff_request_authoritation->get_authorization_by_token($token);

		if (!$authorization) {
			show_404();
		}

		if ($authorization->is_authorized == null) {
			
			$status = $this->Staff_request_authoritation->deny_request($token);

			if ($status) {
				$this->session->set_flashdata('msg', '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Listo!</strong> Se ha denegado la solicitud!.</div>');
			} else {
				$this->session->set_flashdata('msg', '<div class="alert alert-error"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Ha ocurrido un error!.</div>');
			}
		}

		redirect('general/authorities/staff_requests/show?t=' . $token);
    }

    private function notify_by_email_the_authorization_staff_request($request_id)
    {
    	$staff_request = $this->Staff_request->get_staff_request_by_id($request_id);
    	$obj_recruiter = $this->Employer->find($staff_request->recruiter_ID);
    	$employer_admin = $this->Employer->get_admin_employer_by_company_id($obj_recruiter->company_ID);

    	//Create data Send email recruiter 
		$data_email[] = [
			'email' => $obj_recruiter->email,
			'url_link' => site_url('employer/staff_request/staff_requests/show/' . $request_id)	
		];

		$users = $this->Employer_staff_request_manage_business_unit->get_users_by_staff_request_id($request_id);

        $emails[] = $employer_admin->email;

        foreach  ($users as $row) {
            $emails[] = $row->email;
        }

		//Create data Send email admin employer
		$data_email[] = [
			'email' => $emails,
			'url_link' => site_url('employer/staff_requests/show/' . $request_id)	
		];

		foreach ($data_email as $row_data_email) {
			
			$row_data_email['staff_request'] = $staff_request;

			$config = $this->Email_drafts->email_configuration();
			$this->email->initialize($config);
			$this->email->clear(TRUE);
			$this->email->from(ADMIN_EMAIL, SITE_NAME);
			$this->email->to($row_data_email['email']);

			$mail_message = load_email_view('email/staff_request_authorized', $row_data_email);

			$this->email->subject('Autorización completa - Solicitud de personal');
			$this->email->message($mail_message);     
			$this->email->send();
		}
    }

    private function notify_by_email_the_response_authority($token)
    {
		$authorization = $this->Staff_request_authoritation->get_authorization_by_token($token);
		$staff_request = $this->Staff_request->get_staff_request_by_id($authorization->request_ID);
		$obj_recruiter = $this->Employer->find($staff_request->recruiter_ID);

		$data_email = array(
			'email' => $obj_recruiter->email,
			'name' => $obj_recruiter->first_name,
			'authorization' => $authorization,
			'staff_request' => $staff_request,
			'url_link' => site_url('employer/staff_request/staff_requests/show/' . $staff_request->ID)	
		);

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($obj_recruiter->email);

		$mail_message = load_email_view('email/staff_request_response_authority', $data_email); 

		$this->email->subject('Autorización parcial - Solicitud de personal');
		$this->email->message($mail_message);     
		$this->email->send();
    }

    public function notify_request_to_authority()
    {
    	$request_id = $this->input->post('request_id');
    	$type_authority_id = $this->input->post('type_authority_id');

    	$authorities = $this->Staff_request_authoritation->get_authorization_type_authority($request_id, $type_authority_id);

		foreach ($authorities as $authority) {          

    	    $data_email = array(
                'name' => $authority->personal_name,
                'url_link' => site_url('general/authorities/staff_requests/show?t=' . $authority->token)  
            );

            $config = $this->Email_drafts->email_configuration();
            $this->email->initialize($config);
            $this->email->clear(TRUE);
            $this->email->from(ADMIN_EMAIL, SITE_NAME);
            $this->email->to($authority->personal_email);

            $mail_message = load_email_view('email/authorize_request', $data_email);

            $this->email->subject('Autorización requerida - Solicitud de personal');
            $this->email->message($mail_message);     
            $this->email->send();
        }

        echo json_encode(array('success' => true));
    } 
}
