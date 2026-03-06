<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_send_candidates extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
        $this->load->model('Workflow_client');
        $this->load->model('Job_layout');

        if (!check_permission_tray_candidates()) {
            show_404();
        }
    }

    public function modal_sent_candidates()
    {
        $params = $this->input->post();
        
        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $params['client_code'], 'company_id' => $employer->company_ID]);
        $payroll_administrators = $this->Employer->get_all_payroll_administrators_by_client($client->code, $employer->company_ID);
        $data['tray_ids'] = $params['tray_ids'];
        $data['payroll_administrators'] = $payroll_administrators;
        $data['client'] = $client;

        $this->load->view(
            'employer/recruitment_tray/recruitment_send_candidates/common/modal_sent_candidates_form', 
            $data
        );
    }

    public function sent_candidates()
    {
        $this->load->model('Recruitment_tray_candidate');

        $this->form_validation->set_rules('tray_ids[]', 'Bandeja Id', 'trim|required');
        $this->form_validation->set_rules('request_id', 'Solicitud', 'trim|required');
        $this->form_validation->set_rules('client_code', 'Cliente', 'trim|required');
        $this->form_validation->set_rules('payroll_administrators[]', 'Administradores', 'trim|required');
        $this->form_validation->set_rules('comments', 'Comentarios', 'trim|max_length[255]|strip_all_tags');
 
        $this->form_validation->set_message('required', '%s es requerido');

		if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            echo json_encode([
                'status' => false,
                'message' => current($errors)
            ]);
            return;
        }           

        $params = $this->input->post();

        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $params['client_code'], 'company_id' => $employer->company_ID]);

        if (!$client) {
            echo json_encode([
                'status' => false,
                'message' => 'Cliente es incorrecto',
            ]);
            return;
        }
        
        $request_id = trim($params['request_id']);
        $comments = trim($params['comments']);
        $candidates_tray_ids = $params['tray_ids'];
        $payroll_administrators_ids = $params['payroll_administrators'];

        //Verificar administradores
        $this->db->select([
            'payroll_users.ID AS id',
            'payroll_users.email AS email'
        ]);
        $this->db->from('tbl_employers payroll_users');
        $this->db->where('payroll_users.sts', 'active');
        $this->db->where_in('ID', $payroll_administrators_ids);
        $payroll_administrators = $this->db->get()->result();

        if (count($payroll_administrators) == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'No hay personal administrador activo para culminar la contratación',
            ]);
            return;
        }

        $this->db->select([
            'tray_candiates.id AS id',
            'tray_candiates.process_id AS process_id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candiates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candiates.process_id');
        $this->db->where_in('tray_candiates.id', $candidates_tray_ids);
        $this->db->where('(tray_candiates.status_id = 1 OR tray_candiates.status_id = 2)');
        $this->db->where('rc_process.tray_type_id', '3'); //Bandeja de contratacion
        
        $tray_candidates = $this->db->get()->result();
     
        if (count($tray_candidates) == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'Hay candidatos seleccionados que no tienen el estado correcto.',
            ]);
            return;
        }

        $this->db->trans_start();
        //Registrar el envio
        $sent_at = date('Y-m-d H:i:s');
        $sent_data = [
            'client_code' => $client->code,
            'company_id' => $client->company_id,
            'sent_at' => $sent_at,
            'sent_by' => $employer->ID,
            'comments' => $comments
        ];
        
        $this->db->insert('tbl_recruitment_tray_sent', $sent_data);
        $sent_id = $this->db->insert_id();

        if (!$sent_id) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo registar el envio',
            ]);
            return;
        }
    
        foreach ($payroll_administrators as $user_row) {
            $this->db->insert('tbl_recruitment_tray_sent_administrators', [
                'sent_id' => $sent_id,
                'employer_id' => $user_row->id
            ]);
        }
        
        $tray_ids = [];
        foreach ($tray_candidates as $tray_candidate) {
                        
            //Asignar solicitud al proceso
            $this->db->where('id', $tray_candidate->process_id);
            $this->db->where('tray_type_id', 3);
            $this->db->update('tbl_recruitment_process', [
                'request_id' => $request_id
            ]);            
            $tray_ids[] = $tray_candidate->id;
        }
    
        $this->db->where_in('id', $tray_ids);
        $this->db->where('(status_id = 1 OR status_id = 2)');
        $this->db->update('tbl_recruitment_tray_candidates', [
            'sent_id' => $sent_id,
            'status_id' => 2 //Enviados
        ]);

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        if ($trans_status !== true) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo realizar el envio a contratación',
            ]);
            return;
        }

        foreach ($payroll_administrators as $user_row) {
            $emails[] = $user_row->email;
        }

        $staff_request = $this->Staff_request->find($request_id);
        
        $candidates = $this->Recruitment_tray_candidate->get_all_candidates_by_ids($tray_ids);
        
        $data_email = [
            'url_link' => site_url('employer/recruitment_tray/process_candidates_list/index/' . $client->code),
            'comments' => $comments,
            'sent_at' => $sent_at,
            'sent_by' => $employer,
            'candidates' => $candidates,
            'staff_request' => $staff_request
        ];

        $config = $this->Email_drafts->email_configuration();
        $this->email->initialize($config);
        $this->email->clear(TRUE);
        $this->email->from(ADMIN_EMAIL, SITE_NAME);
        $this->email->to($emails);

        $mail_message = load_email_view('email/tray_candidates/notify_sent_candidates', $data_email);

        $this->email->subject('Candidatos enviados a contratación - ' . $client->name);
        $this->email->message($mail_message);     
        $this->email->send();

        echo json_encode([
            'status' => true,
            'message' => 'Los postulantes han sido enviados a contratación',
        ]);
    }

    public function modal_select_staff_requests()
    {
        $input_data = $this->input->get();
        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $input_data['client_code'], 'company_id' => $employer->company_ID]);

        $data = [
            'employer' => $employer,
            'client' => $client
        ];
        $this->load->view(
            'employer/recruitment_tray/recruitment_send_candidates/common/modal_select_staff_requests_content',
            $data
        );
    }

    public function staff_request_list()
	{
		$input_data = $this->input->get();

		$employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $input_data['client_code'], 'company_id' => $employer->company_ID]);

        $this->db->select([
            //Get data staff request
            'request.ID AS request_id',
            'request.consultant_name',
            'request.business_unit_name',
            'request.client_company_name',
            'request.cost_center',
            'request.creation_date',
            'request.job_title',
            'request.request_type AS request_type_id',
            'CASE request.request_type
                WHEN "internal" THEN "Interna"
                WHEN "external" THEN "Externa"
                ELSE request.request_type
            END AS request_type_name',
            'request.sts_process AS status_id',
            'CASE request.sts_process
                WHEN "assigned" THEN "Asignada"
                WHEN "canceled" THEN "Cancelada"
                WHEN "pending" THEN "Pendiente"
                WHEN "published" THEN "Publicada"
                WHEN "rejected" THEN "Rechazada"
                WHEN "unassigned" THEN "Sin asignar"
                ELSE request.sts_process
            END AS status_name',
            'request.employer_ID',
            'request.request_model_id',
            //Get data staff recruiter
            'app_user_recruiters.ID AS recruiter_ID',
            'app_user_recruiters.first_name AS recruiter_first_name',
            'app_user_recruiters.email AS recruiter_email',
            //Posted job
            'post_job.ID AS job_id'
        ], false);

        $this->db->from('tbl_staff_requests request');
        $this->db->join('tbl_employers app_user_recruiters', 'request.recruiter_ID=app_user_recruiters.ID');
        $this->db->join('tbl_post_jobs post_job', 'post_job.request_ID=request.ID', 'left');       
        $this->db->where('request.company_ID', $client->company_id);
        $this->db->where('request.cod_clie', $client->code);
		$this->db->where_in('request.request_model_id', [1, 2]);
        $this->db->group_by('request.ID');

        $results = $this->db->get()->result();

		echo json_encode([
			'data' => $results
		]);
	}
}
