<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requests extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        
        //Load model
        $this->load->model('Exam_document');
        $this->load->model('Medical_center');
        $this->load->model('Medical_center_email');
        $this->load->model('Exam_request_seeker');
        $this->load->model('Exam_request');
        $this->load->model('Exam_request_result');
        $this->load->model('Exam_request_schedule');
        $this->load->model('Staff_request');
        $this->load->model('Mof');
        $this->load->model('Job_profile');
        $this->load->model('Exam_request_overall_email');
    }

    public function list_rys()
    {
        $data['title'] = 'Solicitud de Programaciones';
        $data['ads_row'] = $this->ads;

        $user = $this->Employer->find($this->session->userdata('user_id'));

        $filters = [
            'query' => $this->input->get('query', true),
            'company_id' => $user->company_ID,
            'exam_type_id' => $this->input->get('exam_type_id', true),
            'status' => $this->input->get('status', true),
            'date_start' => $this->input->get('date_start'),
            'date_end' => $this->input->get('date_end')
        ];
        
        $total_rows = $this->Exam_request_schedule->count_rys_processes(
            $filters
        );
    
        $config = pagination_configuration(
            pagination_url(), 
            $total_rows, 
            15, 
            3, 
            5, 
            true,
            true, 
            true
        );

        $page = $this->input->get('page', true);

        $result_candidates = $this->Exam_request_schedule->search_rys_processes(
            $filters,
            $config["per_page"],
            ($page - 1) * $config["per_page"]
        );

        $data['links'] = $this->pagination->create_links();
        $data['rys_total'] = $total_rows;
        $data['rys_processes'] = $result_candidates; 
        $data['filters'] = $filters;

        $this->load->view('employer/exam_requests/exam_requests/rys_processes', $data); 
    }

    public function list($schedule_id = 0)
    {
        $schedule = $this->Exam_request_schedule->find($schedule_id);

        $job = $this->Posted_job->get_posted_job_by_id($schedule->job_id);

        if (!$schedule) {
            show_404();
        }

        $data['title'] = 'Bandeja de programaciones';
        $data['ads_row'] = $this->ads;

        $user_id = $this->session->userdata('user_id');
        
        $filters = [
            'exam_type_id' => $this->input->get('exam_type_id', true),
            'medical_center' => $this->input->get('medical_center', true),
            'status' => $this->input->get('status', true),
            'status_oc' => $this->input->get('status_oc', true)
        ];

        $data['medical_centers'] = $this->Medical_center->get_all();

        $data['requests'] = $this->Exam_request->search_requests_by_schedule_id(
            $schedule_id, 
            $filters
        );
        
        $data['exam_schedule'] = $schedule;
        $data['job'] = $job;
        $data['filters'] = $filters;

        $this->load->view('employer/exam_requests/exam_requests/list_requests', $data); 
    }

    public function show($request_id)
    {
        $data['title'] = 'Detalle Solicitud';
        $data['ads_row'] = $this->ads;

        $request = $this->Exam_request->find($request_id);

        if (!$request) {
            show_404();
        }

        $job = $this->Posted_job->get_posted_job_by_id($request->job_id);

        $medical_center = $this->Medical_center->find(
            $request->medical_center_code
        );

        $medical_center_location = $this->db->get_where('tbl_medical_center_locations', [
            'id' => $request->medical_center_location_id
        ])->row();

        $document = $this->Exam_document->find($request->exam_type_id);

        $user = $this->Employer->find($request->user_id);

        $seekers = $this->Exam_request_seeker->get_seekers($request_id);

        $data['request'] = $request;
        $data['request_oc_ids'] = $this->Exam_request->get_oc_ids_created($request_id);
        $data['request_seekers'] = $seekers;
        $data['document'] = $document;
        $data['medical_center'] = $medical_center;
        $data['medical_center_location'] = $medical_center_location;
        $data['job'] = $job;
        $data['user'] = $user;
        
        $this->load->view('employer/exam_requests/exam_requests/detail', $data);
    }

    public function create($schedule_id)
    {
        $data['title'] = 'Crear solicitud de envio';
        $data['ads_row'] = $this->ads;

        $exam_schedule = $this->Exam_request_schedule->find($schedule_id);

        $data['exam_schedule'] = $exam_schedule;
        $data['job'] = $this->Posted_job->get_posted_job_by_id($exam_schedule->job_id);
        
        $data['exam_type'] = $this->Exam_document->find($exam_schedule->exam_type_id); 

        $this->load->view('employer/exam_requests/exam_requests/modal/create_request', $data);
    }

    public function do_create()
    {
        $schedule_id = $this->input->post('schedule_id');

        $exam_schedule = $this->Exam_request_schedule->find($schedule_id);

        $job_id = $exam_schedule->job_id;
        $exam_type_id = $exam_schedule->exam_type_id;
        $seekers = $this->input->post('seekers');
       
        if (empty($seekers)) {
            echo json_encode([
                'success' => false,
                'error' => 'Deben selecionar al menos 1 postulante'
            ]);
            return;
        }

        $document = $this->Exam_document->find($exam_type_id);

        $this->db->trans_start();

        $medical_center_codes = [];
        $medical_center_location_ids = [];

        foreach ($seekers as $seeker) {
            
            if (!isset($seeker['seeker_id'])) {
                continue;
            }

            $medical_center = $this->Medical_center->create_from_data(
               $seeker['medical_center']
            );
            
            $medical_center_location_id = $seeker['medical_center_location'] ? 
                $seeker['medical_center_location'] : null;

            $data_seekers = [
                'exam_date' => $seeker['exam_date'],
                'medical_center_code' => $medical_center->code,
                'medical_center_location_id' => $medical_center_location_id, 
                'status' => 2
            ];

            $exam_types = exam_request_exam_types($exam_type_id);

            foreach ($exam_types as $row_exam_type_id) {
                if ($row_exam_type_id == 1) {

                    $this->db->where('exam_request_seeker_id', $seeker['scheduled_exam_id']);
                    $this->db->where('exam_type_id', $row_exam_type_id);
                    $this->db->update('tbl_exam_request_seekers', [
                        'exam_doc_type' => $seeker['emo_type'],
                        'protocol_extra' => $seeker['protocol_extra']
                    ]);
                }

                if ($row_exam_type_id == 3) {

                    $this->db->where('exam_request_seeker_id', $seeker['scheduled_exam_id']);
                    $this->db->where('exam_type_id', $row_exam_type_id);
                    $this->db->update('tbl_exam_request_seekers', [
                        'exam_doc_type' => $seeker['screening_type']
                    ]);
                }
            }

            $this->db->where('id', $seeker['scheduled_exam_id']);
            $this->db->update('tbl_exam_request_seekers', $data_seekers);

            $medical_center_codes[] = $medical_center->code;
            $medical_center_location_ids[] = $seeker['medical_center_location'];
        }

        $this->Exam_request->create_and_assign_group_by_medical_center(
            $schedule_id,
            $job_id,
            $exam_type_id,
            $medical_center_codes,
            $medical_center_location_ids
        );

        $this->Exam_request_schedule->update_status($schedule_id);

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        echo json_encode([
            'success' => $trans_status,
        ]);
    }
   
    public function search_seekers($schedule_id)
    {
        $medical_centers = [];

        $schedule = $this->Exam_request_schedule->find($schedule_id);

        $job = $this->Posted_job->get_posted_job_by_id($schedule->job_id);

        $data['document'] = $this->db->get_where('tbl_exam_request_types', [
            'id' => $schedule->exam_type_id
        ])->row();

        $staff_request = null;

        if ($job->request_ID != null) {

            $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);
        }  

        $seekers = $this->Exam_request_schedule->search_seekers(
            $schedule_id
        );

        $data['exam_schedule'] = $schedule;
        $data['staff_request'] = $staff_request;
        $data['seekers'] = $seekers;

        $build_data = [];

        $data_seekers = array_merge($data, $build_data);

        $list_seekers_view = $this->load->view(
            'employer/exam_requests/exam_requests/common/list_seeker_by_stage', 
            $data_seekers,
            true
        );

        $data_medical_centers['medical_centers'] = $medical_centers;

        $medical_centers_view = $this->load->view(
            'employer/exam_requests/exam_requests/modal/selected_medical_centers', 
            $data_medical_centers,
            true
        );

        echo json_encode([
            'list_seekers' => $list_seekers_view,
            'medical_centers' => $medical_centers_view
        ]);
    }

    public function edit($request_id)
    {
        $data['title'] = 'Editar solicitud de examen';
        $data['ads_row'] = $this->ads;

        $exam_request = $this->Exam_request->find($request_id);

        if (!$exam_request) {
            show_404();
        }

        $medical_centers = [];
        
        $job = $this->Posted_job->get_posted_job_by_id($exam_request->job_id);

        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

        $data['staff_request'] = $staff_request;
        $data['exam_request'] = $exam_request;   
        $data['job'] = $job;
        $data['document'] = $this->Exam_document->find($exam_request->exam_type_id);

        $data['seekers'] = $this->Exam_request_seeker->get_enabled_seekers(
            $exam_request->request_id
        );

        $build_data = [];

        $data = array_merge($data, $build_data);

        $list_seekers_view = $this->load->view(
            'employer/exam_requests/exam_requests/modal/edit_request', 
            $data, 
            true
        );

        $data_medical_centers['medical_centers'] = $medical_centers;
        $medical_centers_view = $this->load->view(
            'employer/exam_requests/exam_requests/modal/selected_medical_centers', 
            $data_medical_centers,
            true
        );        

        echo json_encode([
            'list_seekers' => $list_seekers_view,
            'medical_centers' => $medical_centers_view
        ]);
    }

    public function do_edit()
    {
        $exam_request_id = $this->input->post('request_id');
        $exam_request = $this->Exam_request->find($exam_request_id);

        $seekers = $this->input->post('seekers');
       
        if (empty($seekers)) {
            echo json_encode([
                'success' => false,
                'error' => 'Deben selecionar al menos 1 postulante'
            ]);
            return;
        }

        $document = $this->Exam_document->find($exam_request->exam_type_id);

        $this->db->trans_start();

        $medical_center_codes = [];
        $medical_center_location_ids = [];

        foreach ($seekers as $seeker) {
            $medical_center = $this->Medical_center->create_from_data(
                $seeker['medical_center']
            );

            $medical_center_location_id = $seeker['medical_center_location'] ? 
            $seeker['medical_center_location'] : null;

            $data_seekers = [
                'exam_date' => $seeker['exam_date'],
                'medical_center_code' => $medical_center->code,
                'medical_center_location_id' => $medical_center_location_id,
            ];

            $exam_types = exam_request_exam_types($document->id);

            foreach ($exam_types as $row_exam_type_id) {

                if ($row_exam_type_id == 1) {
                    $this->db->where('exam_request_seeker_id', $seeker['scheduled_exam_id']);
                    $this->db->where('exam_type_id', $row_exam_type_id);
                    $this->db->update('tbl_exam_request_seekers', [
                        'exam_doc_type' => $seeker['emo_type'],
                        'protocol_extra' => $seeker['protocol_extra']
                    ]);
                }

                if ($row_exam_type_id == 3) {
                    $this->db->where('exam_request_seeker_id', $seeker['scheduled_exam_id']);
                    $this->db->where('exam_type_id', $row_exam_type_id);
                    $this->db->update('tbl_exam_request_seekers', [
                        'exam_doc_type' => $seeker['screening_type']
                    ]);
                }
            }

            if ($seeker['medical_center_location'] != $exam_request->medical_center_location_id ||
                $medical_center->code != $exam_request->medical_center_code) {
                $data_seekers['status'] = 2;
                $data_seekers['exam_time'] = null;
                $data_seekers['candidate_notified'] = 0;
            }

            $this->db->where('id', $seeker['scheduled_exam_id']);
            $this->db->update('tbl_exam_request_seekers', $data_seekers);
        
            $medical_center_codes[] = $medical_center->code;
            $medical_center_location_ids[] = $seeker['medical_center_location'];
        }
  
        $this->Exam_request->create_and_assign_group_by_medical_center(
            $exam_request->schedule_id,
            $exam_request->job_id,
            $exam_request->exam_type_id, 
            $medical_center_codes,
            $medical_center_location_ids,
            $exam_request->request_id
        );

        $this->Exam_request_schedule->update_status($exam_request->schedule_id);

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        echo json_encode([
            'success' => $trans_status,
        ]);
    }

    public function export($request_id)
    {
        $filters = [
            'request_id' => $request_id
        ];

        $this->load->library(
            'Exports/Exam_request_form_medical_center_export', 
            $filters,
            'Exam_request_form_medical_center_export'
        );

        $this->Exam_request_form_medical_center_export->download('Solicitud-examen');
    }
    
    public function confirm_send($request_id)
    {
        $request = $this->Exam_request->find($request_id);

        $data['request'] = $request;
        $data['overall_emails'] = $this->Exam_request_overall_email->all();
        $data['medical_center_emails'] = $this->Medical_center_email->search_by([
            'code' => $request->medical_center_code
        ]);

        $this->load->view(
            'employer/exam_requests/exam_requests/modal/confirm_send', 
            $data
        );
    }

    public function send()
    {
        $this->load->library(
            'Email/Exam_request/Exam_request_notify_medical_center_email',
            null,
            'Exam_request_notify_medical_center_email'
        );

        $exam_requests = (array)$this->input->post('exam_requests');
        $emails = (array)$this->input->post('emails');
        $comment = $this->input->post('comment', true);

        foreach ($exam_requests as $exam_request_id) {
            
            $exam_request = $this->Exam_request->find($exam_request_id);

            $email_status = $this->Exam_request_notify_medical_center_email->send(
                $exam_request->request_id,
                $emails,
                $comment
            );
            
            $data_update = [];

            if ($comment != '') {
                $data_update['send_comment'] = $comment;
            }

            $data_update['send_copy_emails'] = join($emails, ',');

            if ($exam_request->status == 1) {

                $data_update['status'] = 2;
                
                $this->db->where('request_id', $exam_request->request_id);
                $this->db->update('tbl_exam_request_seekers', [
                    'status' => 3
                ]);
            }

            if (count($data_update) > 0) {
                $this->Exam_request->update($exam_request->request_id, $data_update); 
                $this->Exam_request_schedule->update_status($exam_request->schedule_id);
            }
        }

        echo json_encode([
            'success' => true
        ]);
    }

    public function resend()
    {
        $this->load->library(
            'Email/Exam_request/Exam_request_notify_medical_center_email',
            null,
            'Exam_request_notify_medical_center_email'
        );

        $exam_requests = (array)$this->input->post('exam_requests');

        foreach ($exam_requests as $exam_request_id) {
            
            $exam_request = $this->Exam_request->find($exam_request_id);

            if ($exam_request->status != 2) {
                continue;
            }

            $this->Exam_request_notify_medical_center_email->send(
                $exam_request->request_id
            );
        }

        echo json_encode([
            'success' => true
        ]);
    }

    public function create_oc($request_id)
    {
        $data['title'] = 'Crear OC - Solicitud de examen';
        $data['ads_row'] = $this->ads;

        $exam_request = $this->Exam_request->find($request_id);

        if (!$exam_request || $exam_request->oc_status != 1) {
            //show_404();
        }

        $job = $this->Posted_job->get_posted_job_by_id($exam_request->job_id);
        
        $staff_request = $this->Staff_request->get_staff_request_by_id($job->request_ID);

        $medical_center = $this->Medical_center->find(
            $exam_request->medical_center_code
        );
    
        $data['medical_center'] = $medical_center;

        $data['document'] = $this->db->get_where('tbl_exam_request_types', [
            'id' => $exam_request->exam_type_id
        ])->row();

        $data['exam_request'] = $exam_request;
        $data['job'] = $job;

        $data['seekers'] = $this->Exam_request_seeker->get_enabled_seekers(
            $exam_request->request_id
        );

        $this->load->view('employer/exam_requests/exam_requests/create_oc', $data);
    }

    public function do_create_oc()
    {
        $exam_request_id = $this->input->post('request_id');
        $exam_request = $this->Exam_request->find($exam_request_id);

        $seekers = $this->input->post('seekers');
    
        if (empty($seekers)) {
            echo json_encode([
                'success' => false,
                'error' => 'Deben selecionar al menos 1 postulante'
            ]);
            return;
        }

        $this->db->trans_start();

        $this->Exam_request->update($exam_request_id, [
            'oc_status' => 2
        ]);

        foreach ($seekers as $seeker) {
            
            $request_seeker_id = isset($seeker['request_seeker_id']) ? 
                $seeker['request_seeker_id'] : false;

            $request_seeker = $this->Exam_request_seeker->find($request_seeker_id);
            
            if (!$request_seeker) {
                continue;
            }

            $exam_types = exam_request_exam_types($request_seeker->exam_type_id);
            $exam_realized = 0;

            foreach ($exam_types as $row_exam_type_id) {

                $seeker_exam_realized = $seeker[$row_exam_type_id]['realized'];
                $seeker_exam_price = $seeker[$row_exam_type_id]['price'];

                $this->db->where('exam_request_seeker_id', $request_seeker_id);
                $this->db->where('exam_type_id', $row_exam_type_id);
                
                $this->db->update('tbl_exam_request_seekers', [
                    'realized' => $seeker_exam_realized,
                    'price' => $seeker_exam_realized && !empty($seeker_exam_price) ? $seeker_exam_price : null
                ]);

                $exam_request_seeker_exam = $this->Exam_request_seeker_exam->get_exam_type($request_seeker_id, $row_exam_type_id);

                if ($exam_request_seeker_exam->realized) {
                    $exam_realized = 1;
                    $this->Exam_request_result->register_results($exam_request_seeker_exam);
                }
            }

            $this->db->where('id', $request_seeker_id);
            $this->db->update('tbl_exam_request_seekers', [
                'status' => $exam_realized == 1 ? 5 : 6
            ]);
        }

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        if ($trans_status) {
            $this->load->library(
                'Email/Exam_request/Exam_request_notify_seekers_without_attending_email',
                null,
                'Exam_request_notify_seekers_without_attending_email'
            );

            //Notificar a reclutadores que hay candidatos sin 
            //Asistir a citas programadas
            $this->Exam_request_notify_seekers_without_attending_email->send(
                $exam_request_id
            );
        }

        echo json_encode([
            'success' => $trans_status,
            'request_id' => $exam_request_id
        ]);

    }

    public function do_reschedule()
    {
        $seekers = $this->input->post('seekers');
        $exam_date = $this->input->post('exam_date');
        $request_id = $this->input->post('request_id');
        
        $this->db->trans_start();

        foreach ($seekers as $key => $exam_seeker_id) {

            $data = [
                'exam_date' => $exam_date,
                'status' => 4,
                'candidate_notified' => 0
            ];

            $this->Exam_request_seeker->update($exam_seeker_id, $data);             
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        if ($status) {
            $this->session->set_flashdata('success_action', true);

            $this->load->library(
                'Email/Exam_request/Exam_request_notify_reschedule_email',
                null,
                'Exam_request_notify_reschedule_email'
            );

            $this->Exam_request_notify_reschedule_email->send($request_id);
        }

        echo json_encode([
            'success' => $status
        ]);
    }

    public function do_load_time()
    {
        $seekers = $this->input->post('exam_seekers');
        $exam_time = $this->input->post('time');
        
        $this->db->trans_start();

        foreach ($seekers as $key => $exam_seeker_id) {

            $data = [
                'exam_time' => date('H:i', strtotime($exam_time)),
                'status' => 3,
                'candidate_notified' => 0
            ];

            $this->Exam_request_seeker->update($exam_seeker_id, $data);             
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode([
            'success' => $status
        ]);
    }

    public function notify_candidate()
    {
        if (!$this->input->post()) {
            $notification_type = $this->input->get('notification_type');
            $exam_seeker_ids = (array)$this->input->get('exam_seekers');

            $this->db->from('tbl_exam_request_seekers');
            $this->db->where_in('id', $exam_seeker_ids);
            $this->db->where('exam_time!=', null);

            if ($notification_type == 2) {
                $this->db->where('status', 4);
            }
            
            $count_notify = $this->db->count_all_results();

            $data['count_notify'] = $count_notify;
            $data['exam_seekers'] = $exam_seeker_ids;
            $data['notification_type'] = $notification_type;

            $this->load->view('employer/exam_requests/exam_requests/modal/confirm_notify_seekers', $data);

            return;
        }

        $exam_seeker_ids = (array)$this->input->post('exam_seekers');

        if (count($exam_seeker_ids) > 0) {
            $notification_type = $this->input->post('notification_type');

            $data = [
                'notification_type' => $notification_type,
                'notify_candidate' => 1,
                'candidate_notified' => 1
            ];

            $this->db->where_in('id', $exam_seeker_ids);
            $this->db->where('exam_time!=', null);

            if ($notification_type == 2) {
                $this->db->where('status', 4);
            }
            $this->db->update('tbl_exam_request_seekers', $data);
        }

        echo json_encode([
            'success' => true
        ]);
    }

    public function get_medical_center_locations($medical_center_code = 0)
    {
        $locations = $this->db->get_where('tbl_medical_center_locations', [
            'medical_center_code' => $medical_center_code,
            'active' => 1
        ])->result();

        echo json_encode([
            'locations' => $locations
        ]);
    }
}
