<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jobseeker_fits extends CI_Controller
{   
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();
        show_404();
    }

    public function search()
    {
        $this->load->model('Seeker_entries_channel');
        $this->load->model('Seeker_entries_job');
        $this->load->model('Seeker_entries_client');
        $this->load->model('Recruitment_candidate_fits_tmp');

        $data['ads_row'] = $this->ads;
        $data['title'] = 'Candidatos Aptos';

        $filters = [
            'query' => $this->input->get('query'),
            'channel' => $this->input->get('channel', true),
            'job_title' => $this->input->get('job_title', true),
            'client' => $this->input->get('client', true)
        ];

        $data['filters'] = $filters;
        $data['channels'] = $this->Seeker_entries_channel->all(['active' => 1]);
        $data['jobs'] = $this->Seeker_entries_job->all(['active' => 1]);
        $data['clients'] = $this->Seeker_entries_client->all(['active' => 1]);
        $data['result_countries'] = $this->Country->all();

        $data['links'] = '';
        $data['result_job_seekers'] = [];

        if (!$this->input->get()) {
            $this->load->view('employer/recruitment_jobseeker_fits/jobseeker_fits/list', $data);
            return;
        }

        //Crear tabla temporal de candidatos aptos
        $this->Recruitment_candidate_fits_tmp->create_table($filters);

        $this->db->select([
            'js.ID AS seeker_id',
            'js.first_name',
            'js.last_name',
            'js.email',
            'js.document_number',
            'js.mobile',
            'se.recruitment_channel',
            'se.job_title',
            'se.company_account',
            'se_temp.is_fit'
        ]);
        $this->db->from('tbl_job_seekers js');
        $this->db->join('tbl_seeker_entries se', 'se.seeker_id=js.ID');
        $this->db->join('tbl_recruitment_candidate_fits_tmp se_temp', 'se_temp.seeker_id=se.seeker_id');

        if (isset($filters['channel']) && $filters['channel'] != '') {
            $this->db->where('se.recruitment_channel', $filters['channel']);
        }

        if (isset($filters['client']) && $filters['client'] != '') {
            $this->db->where('se.company_account', $filters['client']);
        }

        if (isset($filters['job_title']) && $filters['job_title'] != '') {
            $this->db->where('se.job_title', $filters['job_title']);
        }

        $data['result_job_seekers'] = $this->db->get()->result();
       
        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'data' => $data['result_job_seekers']
            ]);
        }
    }
    
    public function import()
    {
        $data['ads_row'] = $this->ads;
        $data['title'] = 'Importar postulantes';
        $this->load->view(
            'employer/recruitment_jobseeker_fits/jobseeker_fits/import', 
            $data
        );
    }

    public function do_import()
    {
        $this->load->library(
            'Imports/Jobseeker_import',
            null,
            'Jobseeker_import'
        );

        $all_inputs = $this->input->post();
        
        $import_data = $this->Jobseeker_import->import(
            $all_inputs
        );

        if ($import_data['status'] == true) {

            $this->session->set_flashdata(
                'msg', 
                '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <b>Registros importados con éxito</b>
                </div>'
            );

            redirect('employer/recruitment_jobseeker_fits/jobseeker_fits/import');
        }

        if ($import_data['status'] == false) {
            $this->session->set_flashdata(
                 'msg', 
                 '<div class="alert alert-danger">
                     <a href="#" class="close" data-dismiss="alert">&times;</a>
                     ' . $import_data['message'] . '
                 </div>'
             );
        
            redirect('employer/recruitment_jobseeker_fits/jobseeker_fits/import');
        }
    }

    public function show_status()
    {
        $seeker_id = $this->input->post('seeker_id');
        $seeker = $this->Job_seeker->find($seeker_id);

        //Buscar candidatos en otros procesos
        $this->db->select([
            'jobs.job_title',
            'rc.job_ID AS rys_id',
            'stages.id AS stage_id',
            'stages.name AS stage',
            'stage_logs.datetime AS stage_datetime'
        ]);
        $this->db->from('tbl_recruitment_candidates rc');
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=rc.job_ID');
        $this->db->join('tbl_recruitment_stages stages', 'stages.id=rc.stage');
        $this->db->join(
            'tbl_recruitment_log_candidate_stage stage_logs', 
            'stage_logs.job_ID=rc.job_ID AND stage_logs.seeker_ID=rc.seeker_ID AND stage_logs.stage=rc.stage',
            'left'
        );

        $this->db->where('rc.seeker_ID', $seeker_id);
        $this->db->where('rc.discarded', 0);
        $this->db->where('rc.contracted', 0);
        $rc_process = $this->db->get()->result();
        $data['rc_process'] = $rc_process;

        //Verifiar candidatos en lista negra
        $blacklist = $this->Job_seeker->get_overall_blacklist([$seeker->document_number]);
        $data['result_blacklist'] = $blacklist[$seeker->document_number] ?? [];

        $this->load->view(
            'employer/recruitment_jobseeker_fits/jobseeker_fits/common/content_show_status', 
            $data
        );
    }

    public function export()
    {
        $this->load->library(
            'Exports/Recruitment_candidate_fits_export', 
            null, 
            'Recruitment_candidate_fits_export'
        );

        $filters = [
            'channel' => $this->input->get('channel', true),
            'job_title' => $this->input->get('job_title', true),
            'client' => $this->input->get('client', true)
        ];
        
        $this->Recruitment_candidate_fits_export->build($filters);
        $this->Recruitment_candidate_fits_export->download('candidatos-aptos');
    }

    public function edit_mobile()
    {
        $this->form_validation->set_rules('seeker_id', 'Postulante Id', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('mobile_code', 'Codigo celular', 'trim|required|strip_all_tags');
        $this->form_validation->set_rules('mobile', 'N° Celular', 'trim|required|max_length[11]|strip_all_tags');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'status' => false,
                'message' => validation_errors()
            ]);
            return;
        }

        $seeker_id = $this->input->post('seeker_id');
        $seeker = $this->Job_seeker->find($seeker_id);

        if (!$seeker) {
            echo json_encode([
                'status' => false,
                'message' => 'Postulante ID es incorrecto'
            ]);
            return;
        }

        $mobile = $this->input->post('mobile_code') . ' ' . $this->input->post('mobile');

        $this->db->where('ID', $seeker_id);
        $this->db->update('tbl_job_seekers', [
            'mobile' => $mobile
        ]);

        echo json_encode([
            'status' => true,
            'message' => 'N° Celular editado con éxito',
            'mobile' => $mobile
        ]);
    }
}
