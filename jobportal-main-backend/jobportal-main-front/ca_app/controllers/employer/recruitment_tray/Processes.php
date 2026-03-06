<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Processes extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();
        $this->load->model('Workflow_client');
        $this->load->model('Job_layout');
    }

    public function modal_hire_candidates()
    {
        check_permission_action('recruitment_tray', 'hire_candidates');
        
        $this->load->model('Workflow_consultant');
        $this->load->model('Job_layout');

        $params = $this->input->post();
        
        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $params['client_code'], 'company_id' => $employer->company_ID]);
        $payroll_administrators = $this->Employer->get_all_payroll_administrators_by_client($client->code, $employer->company_ID);
        $errors = [];

        $this->db->select([
            'DISTINCT(rc_process.tray_type_id) AS tray_type_id',
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->where_in('tray_candidates.id', $params['tray_ids']);
        $result_tray_types = $this->db->get()->result();

        $tray_types = [];

        foreach ($result_tray_types as $tray_type) {
            $tray_types[] = $tray_type->tray_type_id;
        }

        $this->db->select([
            'tray_candidates.id AS id',
            'rc_process.job_ID AS job_id',
            'candidates.ID AS candidate_id',
            'candidates.document_type',
            'candidates.document_number',
            'candidates.first_name',
            'candidates.last_name',
            'form_rtps.ID AS form_rtps_id',
            'form_rtps.ID AS form_rtps_evicertia_status',
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_job_seekers candidates', 'tray_candidates.seeker_id=candidates.ID');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps.job_id=rc_process.job_ID AND form_rtps.seeker_id=tray_candidates.seeker_id', 'left');
        $this->db->where_in('tray_candidates.id', $params['tray_ids']);      
        $this->db->where('(tray_candidates.status_id = 2)');
    
        $tray_candidates = $this->db->get()->result();

        foreach ($tray_candidates as $candidate) {
            if (!$candidate->form_rtps_id) {
                $errors[] = [
                    'document_number' => $candidate->document_number,
                    'first_name' => $candidate->first_name,
                    'error' => 'Postulante no tiene ficha RTPS respondida'
                ];
                continue;
            }

            if ($candidate->form_rtps_evicertia_status != 3 && $this->config->item('env') == 'production') {
                $errors[] = [
                    'document_number' => $candidate->document_number,
                    'first_name' => $candidate->first_name,
                    'error' => 'Postulante no tiene ficha RTPS firmada'
                ];
                continue;
            }
        }

        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $params['client_code'], 'company_id' => $employer->company_ID]);
        $payroll_administrators = $this->Employer->get_all_payroll_administrators_by_client($client->code, $employer->company_ID);
        $consultants = $this->Workflow_consultant->all(['company_id' => $client->company_id, 'active' => 1]);
        
        $data = [
            'errors' => $errors,
            'tray_ids' => $params['tray_ids'],
            'client' => $client,
            'consultants' => $consultants,
            'tray_types' => $tray_types
        ];

        $this->load->view('employer/recruitment_tray/common/modal_hire_candidates_content', $data);
    }

    public function hire_candidates()
    {
        check_permission_action('recruitment_tray', 'hire_candidates');
        
        $this->load->model('Recruitment_tray_candidate');
        $this->load->model('Recruitment_contract');

        $params = $this->input->post();

        $employer = $this->Employer->find($this->session->userdata('user_id'));

        $this->form_validation->set_rules('client_code', 'Cliente', 'required');
        $this->form_validation->set_rules('tray_types[]', 'Bandeja tipos', 'required');

        $tray_types = $params['tray_types'] ?? [];

        //if (in_array(2, $tray_types)) {
            $this->form_validation->set_rules('request_contract_start_date', 'Contrato desde', 'required');
            $this->form_validation->set_rules('request_contract_end_date', 'Contrato hasta', 'required');
        //}

        // if (in_array(3, $tray_types)) {
        //     $this->form_validation->set_rules('consultant_code', 'Consultora', 'required');
        //     $this->form_validation->set_rules('job_layout_id', 'Puesto', 'required');
        //     $this->form_validation->set_rules('contract_start_date', 'Contrato desde', 'required');
        //     $this->form_validation->set_rules('contract_end_date', 'Contrato hasta', 'required');
        // }

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => 'Cliente es incorrecto',
            ]);
            return;
        }

        $client = $this->Workflow_client->find(['code' => $params['client_code'], 'company_id' => $employer->company_ID]);

        if (!$client) {
            echo json_encode([
                'status' => false,
                'message' => 'Cliente es incorrecto',
            ]);
            return;
        }

        $candidates_tray_ids = $params['tray_ids'];

        $this->db->select([
            'tray_candidates.id AS id',
            'tray_candidates.client_code AS client_code',
            'tray_candidates.process_id AS process_id',
            'candidates.ID AS candidate_id',
            'candidates.document_type',
            'candidates.document_number',
            'rc_process.tray_type_id AS tray_type_id'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_job_seekers candidates', 'tray_candidates.seeker_id=candidates.ID');
        $this->db->where_in('tray_candidates.id', $candidates_tray_ids);
        $this->db->where('(tray_candidates.status_id = 2)');
        
        $tray_candidates = $this->db->get()->result();
     
        if (count($tray_candidates) == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'Hay candidatos seleccionados que no tienen el estado correcto para contratar.',
            ]);
            return;
        }

        $tray_ids = [];
        foreach ($tray_candidates as $tray_candidate) {

            $consultant_code = '';
            $job_layout_id = null;
            $contract_start_date = null;
            $contract_end_date = null;

                //if ($tray_candidate->tray_type_id == 2) {
            $contract_start_date = $params['request_contract_start_date'];
            $contract_end_date = $params['request_contract_end_date'];
                //}

            // if ($tray_candidate->tray_type_id == 3) {
            //     $contract_start_date = $params['contract_start_date'];
            //     $contract_end_date = $params['contract_end_date'];
            //     $job_layout_id = $params['job_layout_id'];
            //     $consultant_code = $params['consultant_code'];
            // }

            $insert_status = $this->Recruitment_contract->create_or_update([
                'seeker_id' => $tray_candidate->candidate_id,
                'process_id' => $tray_candidate->process_id
            ], [
                'seeker_id' => $tray_candidate->candidate_id,
                'process_id' => $tray_candidate->process_id,
                'hired' => 0,
                'hired_at' => null,
                'no_cia' => $consultant_code,
                'client_code' => $tray_candidate->client_code,
                'cod_trab' => null,
                'hired_by_user_id' => $employer->ID,
                'identification_doc_type' => $tray_candidate->document_type,
                'identification_doc_number' => $tray_candidate->document_number,
                'is_peruvian' => $tray_candidate->document_type == 1,
                'date_admission' => null,
                'total_salary' => null,
                'contract_start_date' => $contract_start_date,
                'contract_end_date' => $contract_end_date,
                'job_layout_id' => $job_layout_id,
                'synchronized' => 0
            ]);

            if ($insert_status) {
                $this->db->where_in('id', $tray_candidate->id);
                $this->db->where('(status_id = 2)');
                $this->db->update('tbl_recruitment_tray_candidates', [
                    'status_id' => 4 //PROCESANDO
                ]);

                $tray_ids[] = $tray_candidate->id;
            }
        }

        if (count($tray_ids) == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo procesar la solicitud de contratación',
            ]);
            return;
        }

        echo json_encode([
            'status' => true,
            'message' => 'Los postulantes han sido enviados a contratación',
        ]);
    }

    public function get_job_layouts()
    {	
		$params = $this->input->post();
		$results = $this->Job_layout->get_all_by_permission_clients(
			$params['consultant_code'] ?? '', 
			$params['client_code'] ?? ''
		);
		echo json_encode([
			'status' => true,
			'data' => $results
		]);
    }
}
