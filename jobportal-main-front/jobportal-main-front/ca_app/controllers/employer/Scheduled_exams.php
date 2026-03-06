<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Scheduled_exams extends CI_Controller
{
	public function __construct()
	{
        parent::__construct();
	
		//Load models
        $this->load->model('Exam_document');
        $this->load->model('Exam_request_seeker');
        $this->load->model('Exam_request_schedule');
        $this->load->model('Exam_request_status');
 
        //Load Libray
        $this->load->library('storage_lib', null, 'Storage_lib');

    	$this->ads = $this->Ad->get_ads();
    }

    public function schedule(
        $job_id, 
        $exam_type_id = 1,
        $stage = ''
    )
    {
        $data['exam_document'] = $this->Exam_document->find($exam_type_id);
        $data['exam_status'] = $this->Exam_request_status->all(['active' => 1]);
        $data['stage'] = $stage;
        $data['job_id'] = $job_id;

        $this->load->view(
            'employer/recruitment/modal/exam_request_schedule',
            $data
        );
    }

    public function exam_schedule(
        $job_id,
        $stage = 0
    )
    {
        $this->load->model('Job_profile');
        $this->load->model('Mof');
        $this->load->model('Job_layout');

        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);
        $schedule_seekers = $this->input->post('schedule_seekers');

        $exam_type_ids = [];

        foreach ($schedule_seekers as $schedule_id) {
            $schedule_data = explode('-', $schedule_id);
            $exam_type_ids+= explode(',', $schedule_data[1]);
        }
        
        $exam_type_ids = array_values(array_unique($exam_type_ids));

        $data = [];

        $data['exam_type_ids'] = $exam_type_ids;

        foreach ($exam_type_ids as $exam_type_id) {
            
            $this->db->select([
                'cod_portal AS exam_type_name'
            ]);
            $this->db->from('tbl_exam_request_product_codes');
            $this->db->where('exam_type_id', $exam_type_id); 
            $exam_types = $this->db->get()->result();

            if ($exam_type_id == 1) {
                $data+= $this->Exam_request_seeker->build_data_for_emo($staff_request);
                $data['emo_exam_types'] = $exam_types;
            }

            if ($exam_type_id == 2) {
                $data+= $this->Exam_request_seeker->build_data_for_covid19($staff_request);
                $data['covid_exam_types'] = $exam_types;
            }
        }

        //dd($data);

        $this->load->view(
            'employer/recruitment/modal/assing_date',
            $data
        );
    }	

    public function save_schedule()
    { 
        $seekers = $this->input->post('seekers');
        $exam_date = $this->input->post('exam_date');
        $job_id = $this->input->post('job_id');
        $exam_type_ids = $this->input->post('document_id');

        $this->db->trans_start();

        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

        foreach ($seekers as $key => $seeker_id) {
            $schedule_data = explode('-', $seeker_id);
            $seeker_id = $schedule_data[0];
            $exam_type_ids = explode(',', $schedule_data[1]);

            foreach ($exam_type_ids as $exam_type_id) {

                $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create(
                    $job_id, 
                    $seeker_id, 
                    $exam_type_id
                );
    
                $request_seeker = $this->Exam_request_seeker->find($exam_request_seeker_id);

                if ($request_seeker && $request_seeker->exam_type_id == 4) {
                    break;
                }
    
                if ($request_seeker && 
                    ($request_seeker->status != 8 && 
                    $request_seeker->status != 1 && 
                    $request_seeker->status != 6 &&
                    $request_seeker->status != 7)) {
                    continue;
                }
             
                if ($request_seeker && $request_seeker->status == 1 && $request_seeker->notified_sso) {
                    continue;
                }

                if ($request_seeker && $request_seeker->status == 6 || $request_seeker->status == 7) {

                    $this->load->library(
                        'Exam_request/Exam_request_cancel_lib', 
                        null, 
                        'Exam_request_cancel_lib'
                    );

                    $this->Exam_request_cancel_lib->run([$exam_request_seeker_id]);

                    $this->db->where('id', $exam_request_seeker_id);
                    $this->db->update('tbl_exam_request_seekers', [
                        'active' => 0
                    ]);
    
                    $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create(
                        $job_id, 
                        $seeker_id, 
                        $exam_type_id
                    );
                }
    
                if (!$exam_request_seeker_id) {
                    continue;
                }
                
                $data_exam_seeker = [
                    'scheduled_date' => $exam_date,
                    'status' => 1
                ];
    
                $data_exam_type = $this->Exam_request_seeker->build_data_by_document(
                    $staff_request, 
                    $exam_type_id
                );

                $exam_seeker = isset($data_exam_type[$exam_type_id]) ? $data_exam_type[$exam_type_id] : [];
                $data_exam_seeker['type_expense'] = isset($exam_seeker->type_expense) && $exam_seeker ? $exam_seeker->type_expense : null;
    
                if ($exam_type_id == 1) {
                    $data_exam_seeker['exam_doc_type'] = isset($data_exam_type['resource_emo']) && ($data_exam_type['resource_emo'])->resource_value ? ($data_exam_type['resource_emo'])->resource_value : $this->input->post('emo_type');
                }

                if ($exam_type_id == 2) {
                    $data_exam_seeker['exam_doc_type'] = join(',', (array)$this->input->post('covid19_type'));
                }

                $this->db->where('id', $exam_request_seeker_id);
                $this->db->update('tbl_exam_request_seekers', $data_exam_seeker);
            }
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode([
            'success' => $status
        ]);
    }

    public function search_seekers($job_id)
    {
        if ($this->input->get('sync') == 1) {
            $this->db->select([
                'ers.id'
            ]);
            $this->db->from('tbl_exam_request_seekers ers');
            $this->db->where('ers.job_id', $job_id);
            $this->db->where('ers.active', 1);
            $this->db->where('ers.status>=', 1);
            $this->db->where('ers.notified_sso', 1);
            $result = $this->db->get()->result();

            $ref_ids  = [];
            foreach ($result as $row) {
                $ref_ids[] = $row->id;
            }

            $this->load->library(
                'Exam_request/Exam_request_update_lib', 
                null, 
                'Exam_request_update_lib'
            );

            $this->Exam_request_update_lib->run($ref_ids);
        }

        $stage = $this->input->get('stage');
        $quote_status = $this->input->get('quote_status');
        $exam_types = $this->input->get('document_id') ? $this->input->get('document_id') : [1];

        $data['job_id'] = $job_id;
        $data['ubigeos'] = $this->Ubigeo->get_all_records();
        $data['exam_document'] = $this->Exam_document->find(1);
        $data['exam_types'] = $exam_types;
        
        $data['seekers'] = $this->Exam_request_seeker->get_scheduled_seekers(
            $job_id, 
            $stage,
            $exam_types,
            $quote_status
        );
        
        $this->load->view(
            'employer/recruitment/common/exam_request_schedule_seekers', 
            $data
        );
    }

    public function notify_to_sso()
    {
        $job_id = $this->input->post('job_id');
        $job = $this->Posted_job->get_posted_job_by_id($job_id);
        $stage = $this->input->post('stage');
        
        $this->db->select([
            'ers.id'
        ]);
        $this->db->from('tbl_recruitment_candidates rs');
        $this->db->join('tbl_exam_request_seekers ers', 'ers.job_id=rs.job_ID AND rs.seeker_ID=ers.seeker_id');
   
        $this->db->where('ers.job_id', $job_id);
        
        if ($stage != '') {
            $this->db->where('rs.stage', $stage);
        }
        
        $this->db->where('ers.status', 1);
        $this->db->where('ers.active', 1);
        $this->db->where('ers.schedule_id', null);

        $seekers = $this->db->get()->result();

        if (count($seekers) == 0) {
            echo json_encode([
                'seekers_available' => 0,
                'success' => false
            ]);
            return; 
        }

        $exam_seeker_ids = [];

        foreach ($seekers as $exam) {
            $exam_seeker_ids[] = $exam->id;
        }
        
        $this->load->library('Exam_request/Exam_request_send_sso_lib');
             
        $result = $this->exam_request_send_sso_lib->send([
            'ids' => $exam_seeker_ids,
            'send_email_sso' => 1,
            'sent_by_employer_id' => $this->session->userdata('user_id')
        ]);
        
        list($is_success, $message, $data) = array_pad($result, 3, []);
        
        if ($is_success) {
            echo json_encode([
                'message' => $message,
                'success' => true,
                'seekers_available' => 1
            ]);
            return;
        }
        
        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
    }

    public function remove_schedule()
    {
        $job_id = $this->input->post('job_id');
        $schedule_ids = $this->input->post('seekers');
        $ref_ids = [];

        foreach ($schedule_ids as $schedule_id) {

            $schedule_data = explode('-', $schedule_id);
            $seeker_id = $schedule_data[0];
            $exam_type_ids = explode(',', $schedule_data[1]);

            foreach ($exam_type_ids as $exam_type_id) {

                $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create(
                    $job_id, 
                    $seeker_id, 
                    $exam_type_id
                );

                $this->db->select([
                    'ers.id',
                    'ers.id AS exam_request_seeker_id',
                    'ers.status',
                    'ers.notified_sso',
                    'ers.schedule_id',
                    'ers.exam_type_id AS ers_exam_type_id'
                ]);
        
                $this->db->from('tbl_exam_request_seekers ers');
                $this->db->where('ers.job_ID', $job_id);
                $this->db->where('ers.seeker_ID', $seeker_id);
                $this->db->where('ers.exam_type_id', $exam_type_id);
                $this->db->where('ers.active', 1);
                
                $row = $this->db->get()->row();

                if ($row->status != 1 && 
                    $row->status != 8 && 
                    $row->status != 4) {
                    continue;
                }

                if (!$row->notified_sso) {
                    $this->db->where('id', $row->exam_request_seeker_id);
                    $this->db->update('tbl_exam_request_seekers', [
                        'active' => 0
                    ]);
                    
                    $this->Exam_request_schedule->update_status($row->schedule_id);

                    if ($row->ers_exam_type_id == 4 ) {
                        break;
                    }
                
                    continue;
                }

                $ref_ids[] = $row->id;
            }
        }

        if (count($ref_ids) > 0) {
            $this->load->library(
                'Exam_request/Exam_request_cancel_lib', 
                null, 
                'Exam_request_cancel_lib'
            );
            $this->Exam_request_cancel_lib->run($ref_ids);

            $this->load->library(
                'Exam_request/Exam_request_update_lib', 
                null, 
                'Exam_request_update_lib'
            );
            $this->Exam_request_update_lib->run($ref_ids);
        }

        echo json_encode([
            'success' => true
        ]);
    }

    public function save_field_values()
    {
        $job_id = $this->input->post('job_id');    
        $field = $this->input->post('field');
        $value = $this->input->post('value');

        $schedule_id = $this->input->post('schedule_id');
        $schedule_data = explode('-', $schedule_id);
        $seeker_id = $schedule_data[0];
        $exam_type_ids = explode(',', $schedule_data[1]);

        foreach ($exam_type_ids as $exam_type_id) {

            $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create(
                $job_id, 
                $seeker_id, 
                $exam_type_id
            );
    
            if ($field == 'comment') {
                $data['comment'] = trim($value);
            }
    
            if ($field == 'ubigeo') {
                $data['ubigeo'] = trim($value);
            }

            $data['notified_sso'] = 0;
    
            $this->db->where('id', $exam_request_seeker_id);
            $this->db->update('tbl_exam_request_seekers', $data);
        }
    
        echo json_encode([
            'success' => true
        ]);
    }

    public function upload_file_high()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;
        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $exam_type_id = $this->input->post('document_id');

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        try {
        
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid('file-high-' . $job_id . '-' . $seeker_id, true)) . $file_ext;  

            $path = 'employer/recruitment_selection_documents/' . $file_name;
            
            $path = $this->Storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                    'error' => 'Error al cargar el documento - No se pudo subir el documento'
                ])
            );
        }

        $this->Storage_lib->setVisibility($path, 'public');

        $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create(
            $job_id, 
            $seeker_id, 
            $exam_type_id
        );

        $data = [
            'file_high_path' => $path
        ];

        $this->db->where('id', $exam_request_seeker_id);
        $trans_status = $this->db->update('tbl_exam_request_seekers', $data);                       
    
        if (!$trans_status) {
            exit(json_encode([
                    'error' => 'Error al guardar la imagen'
                ])
            );
        }

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }

    public function remove_file_high()
    {
        $seeker = $this->db->get_where('tbl_exam_request_seekers', [
            'id' => $this->input->post('id'),
            'active' => 1
        ])->row();

        if (!$seeker) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $status = $this->Storage_lib->delete($seeker->file_high_path);

        if ($status) {
            $this->Exam_request_seeker->update($seeker->id, [
                'file_high_path' => null
            ]);
        }

        echo json_encode([
            'success' => true
        ]);
    } 

    public function upload_file_oc()
    {
        $file = isset($_FILES['file']) ? $_FILES['file'] : null;
        $job_id = $this->input->post('job_id');
        $seeker_id = $this->input->post('seeker_id');
        $exam_type_id = $this->input->post('document_id');

        if (is_null($file)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - Documento no cargado']
            ));
        }

        $allowed = [
            'image/jpeg',
            'image/jpg',
            'application/pdf'
        ];

        if (!in_array($file['type'], $allowed)) {
             exit(json_encode(
                ['error' => 'Error al cargar el documento - Tipo de archivo no es válido'])
            );
        }

        if ($file['size'] > (4 * 1048576)) {
            exit(json_encode(
                ['error' => 'Error al cargar el documento - El archivo a subir debe ser menor o igual a 4MB'])
            );
        }

        try {
        
            $file_ext = file_ext($file['name']) != '' ? '.' . file_ext($file['name']) : ''; 
            $file_name = md5(uniqid('file-oc-' . $job_id . '-' . $seeker_id, true)) . $file_ext;  

            $path = 'employer/recruitment_selection_documents/' . $file_name;
            
            $path = $this->Storage_lib->put($path, $file['tmp_name']);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path === false) {
            exit(json_encode([
                    'error' => 'Error al cargar el documento - No se pudo subir el documento'
                ])
            );
        }

        $this->Storage_lib->setVisibility($path, 'public');

        $exam_request_seeker_id = $this->Exam_request_seeker->find_or_create(
            $job_id, 
            $seeker_id, 
            $exam_type_id
        );

        $data = [
            'file_oc_path' => $path
        ];
        $this->db->where('id', $exam_request_seeker_id);
        $trans_status = $this->db->update('tbl_exam_request_seekers', $data);                       

        if (!$trans_status) {
            exit(json_encode([
                    'error' => 'Error al guardar la imagen'
                ])
            );
        }

        $file_url = file_url($path);

        echo json_encode([
            'success' => true,
            'url_file' => $file_url
        ]);
    }

    public function remove_file_oc()
    {
        $seeker = $this->db->get_where('tbl_exam_request_seekers', [
            'id' => $this->input->post('id'),
            'active' => 1
        ])->row();

        if (!$seeker) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $status = $this->Storage_lib->delete($seeker->file_oc_path);

        if ($status) {
            $this->Exam_request_seeker->update($seeker->id, [
                'file_oc_path' => null
            ]);
        }

        echo json_encode([
            'success' => true
        ]);
    }    

    private function get_ubigeo_code($city)
    {
        $city = mb_strtoupper((string)$city);
    
        $city = str_replace(
            ['Á', 'É', 'Í', 'Ó', 'Ú'],
            ['A', 'E', 'I', 'O', 'U'],
            $city
        );

        $ubigeo_parts = explode(',', $city);

        if (count($ubigeo_parts) < 3) {
            return null;
        }

        $ubigeo = $this->db->get_where('tbl_eplani_ubigeos', [
            'order_administrative1' => trim($ubigeo_parts[0]),
            'order_administrative2' => trim($ubigeo_parts[1]),
            'order_administrative3' => trim($ubigeo_parts[2])
        ])->row();

        return $ubigeo ? $ubigeo->code : null;
    }

    private function get_hrm_product_id($exam_type_id, $exam_doc_type)
    {
        $this->db->from('tbl_exam_request_product_codes');
        $this->db->where('exam_type_id', $exam_type_id);        
        $this->db->where('cod_portal', $exam_doc_type);

        $row = $this->db->get()->row();

        return $row ? $row->hrm_id : null;
    }

    public function notify_exam_request_employers()
    {
        $this->load->model('Staff_request_exam_request_employer');

        $job_id = $this->input->post('job_id');
        $job = $this->Posted_job->find($job_id);
        $staff_request = $this->Staff_request->find($job->request_ID);

        $exam_request_employers = $this->Staff_request_exam_request_employer->all(['request_id' => $staff_request->ID]);

        if (count($exam_request_employers) == 0) {
            echo json_encode([
                'success' => false,
                'message' => 'La solicitud del empleo no tiene gestores de citas para notificarles.'
            ]);
            return;
        }

        $emails = [];

        foreach ($exam_request_employers as $employer) {
            $employer_info = $this->Employer->find($employer->employer_id);
            $emails[] = $employer_info->email;
        }

        //Create data Send email recruiter 
		$data_email = [
			'job' => $job,
			'url_link' => site_url('employer/recruitment_scheduled_exams/scheduled_exams/search'),	
		];

		$config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($emails);

		$mail_message = load_email_view('email/exam_requests/notify_exam_request_employers', $data_email);

		$this->email->subject('Programación examen - #'. $job->ID . ' - ' . $job->job_title);
		$this->email->message($mail_message);     
        
        $email_status = false;

        try {
            $email_status = $this->email->send();	
        } catch (Exeption $e) {}
    
        echo json_encode([
            'success' => $email_status ? true : false,
            'message' => $email_status ? 'Notificación enviada' : 'No se pudo enviar la notificación'
        ]);
    }
}
