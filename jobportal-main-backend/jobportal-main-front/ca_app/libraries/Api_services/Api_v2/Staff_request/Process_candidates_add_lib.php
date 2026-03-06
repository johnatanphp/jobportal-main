<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_candidates_add_lib
{
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function add($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }
     
        return  $this->add_candidate($params);  
    }

    public function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('process_id', 'process_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('candidate_id', 'candidate_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('stage_id', 'stage_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('send_email_candidate', 'send_email_candidate', 'in_list[1,0]');
        $this->form_validation->set_rules('send_whatsapp_candidate', 'send_whatsapp_candidate', 'in_list[1,0]');
        
        $source_id = $params['source']['id'] ?? null;
        
        if ($source_id !== null) {
            $this->form_validation->set_rules('source[id]', 'source[id]', 'required|trim|integer|greater_than[0]');
        }
        
        if ($source_id == 1) {
            $this->form_validation->set_rules('source[social_network_id]', 'source[social_network_id]', 'required|trim|integer|greater_than[0]');
        }
        
        if ($source_id == 2) {
            $this->form_validation->set_rules('source[referred_by][identification_document_number]', 'source[referred_by][identification_document_number]', 'required|trim');
            $this->form_validation->set_rules('source[referred_by][first_name]', 'source[referred_by][first_name]', 'required|trim');
            $this->form_validation->set_rules('source[referred_by][last_name]', 'source[referred_by][last_name]', 'required|trim');
        }
      
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }
        
        if ($source_id) {
            $source = $this->db->get_where('tbl_recruitment_sources', [
                'id' => $source_id,
                'active' => 1
            ])->row();
            
            if (!$source) {
                return [false, 'El valor de source[id] no es válido o esta inactivo'];
            }
        }
       
        if ($source_id == 1) {
            $social_network = $this->db->get_where('tbl_social_networks', [
                'id' => $params['source']['social_network_id'] ,
                'active' => 1
            ])->row();
            
            if (!$social_network) {
                return [false, 'El valor de source[social_network_id] no es válido o esta inactivo'];
            }
        }
        
        $process_row = $this->get_data_by_process_id($params['process_id']);

        if (!$process_row) {
            return [false, 'Proceso no encontratado'];
        }

        if ($process_row->request_sts_process != '9') {
            return [false, 'El proceso pertenece a una solicitud que no ha sido iniciada'];
        }

        if (!$process_row->job_id) {
            return [false, 'El proceso no tiene un job_id vinculado'];
        }

        $seeker = $this->Job_seeker->find($params['candidate_id']);

        if (!$seeker) {
            return [false, 'Postulante no se encontro'];
        }

        if ($seeker->sts != 'active') {
            return [false, 'Cuenta del postulante no esta activa'];
        }

        $this->db->from('tbl_recruitment_stages');
        $this->db->where('stage_group_id', $process_row->request_stage_group_id);
        $this->db->where('id', $params['stage_id']);
        $stage_row = $this->db->get()->row();

        if (!$stage_row) {
            return [false, 'La etapa ingresada no existe o no pertenece al grupo de etapas de la solicitud'];
        }

        $this->db->from('tbl_recruitment_candidates');
        $this->db->where('process_id', $params['process_id']);
        $this->db->where('seeker_ID', $params['candidate_id']);
        $rc_seeker = $this->db->get()->row();

        if ($rc_seeker) {
            return [false, 'El postulante ya existe en el proceso ingresado'];
        }

        return [true, 'OK'];
    }

    public function add_candidate($params)
    {
        $employer_id = $this->session_employer_lib->get_data('user_id');
        $employer = $this->Employer->find($employer_id);
        
        $process = $this->get_data_by_process_id($params['process_id']);

        $insert_seeker = $this->db->insert('tbl_recruitment_candidates', [
            'seeker_ID' => $params['candidate_id'],
            'process_id' => $params['process_id'],
            'job_ID' => $process->job_id,
            'stage' => $params['stage_id'],
            'creation_date' => date('Y-m-d H:i:s'),
            'created_by' => $employer->ID
        ]);

        if ($insert_seeker === false) {
            return [
                'status' => false,
                'message' => 'No se pudo agregar el candidato al proceso'
            ];
        }
            
        $source_id = $params['source']['id'] ?? null;
        $social_network_id = null;
        $referred_by_identification_document_number = '';
        $referred_by_first_name = '';
        $referred_by_last_name = '';
        
        // Fuente Redes sociales
        if ($source_id == 1) {
            $social_network_id = $params['source']['social_network_id'];
        }
        
        // Fuente Referido
        if ($source_id == 2) {
            $referred_by = $params['source']['referred_by'];
            $referred_by_identification_document_number = $referred_by['identification_document_number'];
            $referred_by_first_name = $referred_by['first_name'];
            $referred_by_last_name = $referred_by['last_name'];
        }
        
        if ($source_id !== null) {
            $insert_source = $this->db->insert('tbl_recruitment_candidate_sources', [
                'seeker_id' => $params['candidate_id'],
                'process_id' => $params['process_id'],
                'source_id' => $source_id,
                'social_network_id' => $social_network_id,
                'referred_by_identification_document_number' => $referred_by_identification_document_number,
                'referred_by_first_name' => $referred_by_first_name,
                'referred_by_last_name' => $referred_by_last_name
            ]);
            
            if ($insert_source === false) {
                return [
                    'status' => false,
                    'message' => 'No se pudo guardar el origen al proceso'
                ];
            }
        }
        
        $this->load->library(
            'Api_services/Api_v2/Staff_request/Process_candidates_list_lib',
            null, 
            'Process_candidates_list_lib'
        );

        $candidate_result = $this->Process_candidates_list_lib->list_candidates([
            'process_id' => $params['process_id'],
            'candidate_id' => $params['candidate_id']
        ]); 

        $candidate_data = $candidate_result['data'][0] ?? [];

        $send_email_candidate = $params['send_email_candidate'] ?? 0;

        if ($send_email_candidate) {
            $this->send_email_candidate($process, $params['candidate_id']);
        }

        $send_whatsapp_candidate = $params['send_whatsapp_candidate'] ?? 0;

        if ($send_whatsapp_candidate) {
            $this->send_whatsapp_candidate($process, $params['candidate_id']);
        }
        
        return [
            'status' => true,
            'message' => 'Candidato ha sido agregado',
            'data' => $candidate_data
        ];  
    }

    private function get_data_by_process_id($process_id = 0)
    {
        $this->db->select([
            'jobs.ID AS job_id',
            'jobs.job_title AS job_title',
            'jobs.job_slug AS job_slug',
            'requests.ID AS request_id',
            'requests.sts_process AS request_sts_process',
            'requests.stage_group_id AS request_stage_group_id'
        ]);
        $this->db->from('tbl_post_jobs jobs');
        $this->db->join('tbl_staff_requests requests', 'requests.ID=jobs.request_ID');
        $this->db->join('tbl_recruitment_process process', 'process.request_id=requests.ID');
        $this->db->where('process.id', $process_id);

        return $this->db->get()->row();
    }

    private function send_email_candidate($process, $candidate_id)
    {
        $candidate = $this->Job_seeker->find($candidate_id);

        $this->load->library('Mail_template/Recruitment_process/Recruitment_process_add_candidate');

        $emails[] = $candidate->email;

        $mail_vars = $this->recruitment_process_add_candidate->build([
            'candidate_name' => $candidate->first_name,
            'job_title' => $process->job_title,
            'url_link' => $process->job_slug ? site_url('jobs/' . $process->job_slug) : site_url('login')
        ]);

        $this->email->init();
        $this->email->to($emails);
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }

    private function send_whatsapp_candidate($process, $candidate_id)
    {
        $this->load->library('Whatsapp/Recruitment_process/Whatsapp_recruitment_proccess_candidate_add_lib');
        $this->whatsapp_recruitment_proccess_candidate_add_lib->send($process->request_id, $candidate_id);
    }
}
