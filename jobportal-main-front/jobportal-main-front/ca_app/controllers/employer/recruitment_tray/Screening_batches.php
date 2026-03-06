<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Screening_batches extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Workflow_consultant');
        $this->load->model('Workflow_client');
        $this->load->model('Workflow_cost_center');
        $this->load->model('Recruitment_tray_screening_batch');

        //Load libraries
        $this->load->library('Aws/Aws_lambda_lib');
    }
    
	public function list()
	{
        $params = $this->input->get();
        $data = [
            'client_code' => $params['client_code']
        ];
        $this->load->view('employer/recruitment_tray/screening_batches/common/modal_screening_list_content', $data);
    }

    public function get_data_list()
	{
        $params = $this->input->get();

        $now = date('Y-m-d 00:00:00');

        $this->db->select([
            'MAX(sb.id) AS id'
        ]);
        $this->db->from('tbl_recruitment_tray_screening_batches sb');
        $this->db->where('sb.created_by', $this->session->userdata('user_id'));
        $this->db->where('sb.client_code', $params['client_code']);
        $this->db->group_by('sb.seeker_id');

        $subquery_latest_screening = $this->db->get_compiled_select();

        $this->db->select([
            'dt.abbreviation AS document_type_abbreviation_name',
            'screeening_types.name AS type_name',
            'screeening_batches.id',
            'screeening_batches.created_at',
            'screeening_batches.cost_center',
            'screeening_batches.type_expense',
            'screeening_batches.job_title',
            'screeening_batches.status_id',
            'candidates.document_number',
            'candidates.first_name',
            'candidates.last_name',
            'screening.id AS screening_id',
            'screening.created_at AS screening_created_at',
            'screening.its_data_prosecution AS screening_its_data_prosecution',
            'DATE_ADD(screening.created_at, INTERVAL 6 MONTH) AS screening_due_date',
            'DATEDIFF(DATE_ADD(screening.created_at, INTERVAL 6 MONTH), "' . $now . '") AS screening_remaining_days' 
        ]);
        $this->db->from('tbl_recruitment_tray_screening_batches screeening_batches');
        $this->db->join("(" . $subquery_latest_screening . ") AS latest_screening", 'latest_screening.id=screeening_batches.id');
        $this->db->join('tbl_screening_types screeening_types', 'screeening_types.id=screeening_batches.type_id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=screeening_batches.seeker_id');
        $this->db->join('tbl_identity_document_types dt', 'dt.id=candidates.document_type', 'left');
        $this->db->join('tbl_screening screening', 'screening.id=screeening_batches.screening_id', 'left');
        $this->db->where('screeening_batches.created_by', $this->session->userdata('user_id'));
        $this->db->where('screeening_batches.client_code', $params['client_code']);
        $this->db->order_by('screeening_batches.status_id', 'ASC');

        $screening_batches = $this->db->get()->result();

        $data = [];
        foreach ($screening_batches as $batch) {

            $files = [];
            
            if ($batch->screening_id) {
                $files = [
                    [
                        'file_url' => site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($batch->screening_id) . '/1'),
                        'description' => 'Screening completo'
                    ],
                    [
                        'file_url' => site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($batch->screening_id) . '/2'),
                        'description' => 'Screening parcial'
                    ],
                    [
                        'file_url' => site_url('candidate/screening_show_pdf/' . $this->custom_encryption->encrypt_data($batch->screening_id) . '/3'),
                        'description' => 'Anexos del Screening'
                    ]
                ];
            }

            $batch->files = $files;
            $data[] = $batch;
        }

        $data = [
            'data' => $screening_batches
        ];

        echo json_encode($data);
    }

    public function form_create()
	{
        $params = $this->input->get();

        $tray_ids = $params['tray_ids'];

        $employer = $this->Employer->find($this->session->userdata('user_id'));
        $client = $this->Workflow_client->find(['code' => $params['client_code'], 'company_id' => $employer->company_ID]);

        $this->db->select([
            'tray_candidates.id AS tray_id',
            'candidates.ID AS id',
            'candidates.email AS email',
            'candidates.document_type AS document_type',
            'candidates.document_number AS document_number',
            'candidates.first_name AS first_name',
            'candidates.last_name AS last_name',
            'COUNT(screeening_batches.id) AS total_screening_batch_in_progress'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->join('tbl_recruitment_tray_screening_batches screeening_batches', 'screeening_batches.seeker_id=tray_candidates.seeker_id AND screeening_batches.client_code=tray_candidates.client_code AND screeening_batches.status_id=1', 'left');
        $this->db->where_in('tray_candidates.id', $tray_ids);
        $this->db->where('tray_candidates.client_code', $params['client_code']);
        $this->db->group_by('tray_candidates.seeker_id');
        $this->db->order_by('total_screening_batch_in_progress', 'ASC');
        $candidates = $this->db->get()->result();

        //dd($candidates);
        $cost_centers = $this->Workflow_cost_center->get_all($employer->company_ID);

        $data = [
            'client' => $client,
            'candidates' => $candidates,
            'cost_centers' => $cost_centers
        ];
        $this->load->view('employer/recruitment_tray/screening_batches/common/modal_screening_create_content', $data);
    }

    public function create()
	{
        $this->form_validation->set_rules('tray_id[]', 'Bandeja Id', 'trim|required');
        $this->form_validation->set_rules('type', 'Tipo Screening', 'trim|required');
        $this->form_validation->set_rules('job_title', 'Puesto', 'trim|required');
        $this->form_validation->set_rules('type_expense', 'Tipo egreso', 'trim|required');
        $this->form_validation->set_rules('cost_center', 'Centro de costo', 'trim|required');
 
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

        $tray_ids = $params['tray_id'];

        $this->db->select([
            'tray_candidates.process_id',
            'tray_candidates.client_code',
            'tray_candidates.seeker_id',
            'candidates.document_number'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->where_in('status_id', [1, 2]);
        $this->db->where_in('tray_candidates.id', $tray_ids);
        $tray_candidates = $this->db->get()->result();

        if (count($tray_candidates) == 0) {
            echo json_encode([
                'status' => false,
                'message' => 'No hay candidatos aptos para crear screening'
            ]);
            return;
        }

        if (count($tray_candidates) > 10) {
            echo json_encode([
                'status' => false,
                'message' => 'Solo puede crear lotes de 10 postulantes'
            ]);
            return;
        }

        $created_at = date('Y-m-d H:i:s');
        $created_by = $this->Employer->find($this->session->userdata('user_id'));

        $consultant = $this->Workflow_consultant->get_consultant_by_cost_center($params['cost_center'], $created_by->company_ID);

        foreach ($tray_candidates as $tray_candidate) {

            $this->db->insert('tbl_recruitment_tray_screening_batches', [
                'client_code' => $tray_candidate->client_code,
                'process_id' => $tray_candidate->process_id,
                'seeker_id' => $tray_candidate->seeker_id,
                'created_at' => $created_at,
                'created_by' => $created_by->ID,
                'document_number' => $tray_candidate->document_number,
                'type_id' => $params['type'],
                'job_title' => $params['job_title'],
                'type_expense' => $params['type_expense'],
                'cost_center' => $params['cost_center'],
                'cost_center_client' => $params['cost_center_client'],
                'eecc_code' => $params['eecc_code'],
                'status_id' => 1 // Generando
            ]);

            $screening_batch_id = $this->db->insert_id();

            if (!$screening_batch_id) {
                continue;
            }

            $lambda_client = $this->aws_lambda_lib->get_client();

            if (!$lambda_client) {
                $this->db->where('id', $screening_batch_id);
                $this->db->update('tbl_recruitment_tray_screening_batches', [
                    'status_id' => 3, //Fallido
                    'error_message' => 'No se pudo conectar al cliente de la lambda, por favor revisar las credenciales o conexión'
                ]);
                continue;
            }

            $env = $this->config->item('env');
            
            $function_name = $env == 'production' ? '' : 'tray-recruitment-create-screening-test';
            
            $payload = [
                "batch_id" => $screening_batch_id,
                "type_id" => $params['type'],
                "document_number" => $tray_candidate->document_number,
                "cost_center" => $params['cost_center'],
                "job_title" => $params['job_title'],
                "created_by_name" => trim($created_by->first_name . ' ' . $created_by->last_name),
                "created_by_id" => $created_by->ID,
                "type_expense" => $params['type_expense'],
                "eecc_code" => $params['eecc_code'],
                "cost_center_client" => $params['cost_center_client'],
                "consultant_name" => $consultant ? $consultant->consultant_name : ''
            ];

            $payload_json = json_encode($payload);

            try {
                $result = $lambda_client->invoke([
                    'FunctionName' => $function_name,
                    'InvocationType' => 'Event', // Pide una respuesta asíncrona
                    'LogType' => 'Tail', // Opcional: para obtener logs de ejecución
                    'Payload' => $payload_json,
                ]);

                $result_data = [];
                $result_data['StatusCode'] = $result['StatusCode'];
                $result_data['@metadata'] = $result['@metadata'];
             
                $status_code = $result['StatusCode'];

                if ($status_code == 202) {
                    $this->db->where('id', $screening_batch_id);
                    $this->db->update('tbl_recruitment_tray_screening_batches', [
                        'lambda_response' => json_encode($result_data)
                    ]);
                }
            } catch (\Aws\Exception\AwsException $e) {
                $this->db->where('id', $screening_batch_id);
                $this->db->update('tbl_recruitment_tray_screening_batches', [
                    'status_id' => 3, //Fallido
                    'error_message' => 'Error al invocar la lambda: ' . $e->getMessage() 
                ]);
            }
        }

        echo json_encode([
            'status' => true,
            'message' => 'Lote ha sido guardado para ser procesado',
        ]);
    }

    public function logs()
    {
        $params = $this->input->get();

        $batch = $this->Recruitment_tray_screening_batch->find(['id' => $params['id']]);

        $data = [
            'batch' => $batch
        ];
        $this->load->view('employer/recruitment_tray/screening_batches/common/modal_screening_logs_content', $data);
    }
}
