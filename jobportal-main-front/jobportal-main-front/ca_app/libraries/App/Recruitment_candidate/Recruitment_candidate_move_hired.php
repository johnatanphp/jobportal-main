<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_candidate_move_hired
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function move($data_input)
    {
        $this->load->model('Recruitment_candidate');

        $process_id = $data_input['process_id'];
        $process = $this->Recruitment_process->find($process_id);

        if (!$process) {
            return [
                'status' => false,
                'message' => 'No hay un proceso para el pase a contratación',
            ];
        }

        $job_id = $process->job_ID;
        $job = $this->Posted_job->find($job_id);
        $staff_request = $this->Staff_request->find($job->request_ID);

        $candidate_ids = $data_input['candidate_ids'];
        $rrhh_user_ids = (array)$data_input['rrhh_group_id'];
        $manual_rrhh_user_ids = isset($data_input['rrhh_user_id']) ? $data_input['rrhh_user_id'] : []; 
        $group_id = isset($rrhh_user_ids[0]) ? $rrhh_user_ids[0] : null;

        $this->db->trans_start();

        $this->db->select([
            'ID',
            'first_name',
            'last_name',
            'mobile'
        ]);
        $this->db->from('tbl_job_seekers');
        $this->db->where_in('ID', $candidate_ids);
        $seekers = $this->db->get()->result();

        foreach ($seekers as $seeker) {

            $candidate_id = $seeker->ID;

            $rs_seeker = $this->Recruitment_candidate->get_candidate_in_process($process_id, $candidate_id);

            $candidate_name = $seeker->first_name . ' '. $seeker->last_name;

            if ($this->Recruitment_candidate->is_candidate_discarded($process_id, $candidate_id)) {
                return [
                    'status' => false,
                    'message' => 'El candidato "' . $candidate_name . '" está descartado y no está ' . 
                    'disponible para moverlo de etapa',
                    'data' => []
                ];
            }

            if ($this->Recruitment_candidate->is_candidate_active_in_other_process($process_id, $candidate_id)) {
                return [
                    'status' => false,
                    'message' => 'El candidato "' . $candidate_name . '" está en otro proceso activo y no está ' . 
                    'disponible para moverlo de etapa',
                    'data' => []
                ];
            }

            if ($this->Recruitment_candidate->candidate_has_unanswered_forms($process_id, $candidate_id, $rs_seeker->stage)) {
                return [
                    'status' => false,
                    'message' => 'El candidato "' . $candidate_name . '" tiene formularios sin responder',
                    'data' => []
                ];
            }   

            // if ($this->rys_seeker_fit_helper->is_fit_to_move($job_id, $candidate_id, $rs_seeker->stage) === false) {
            //     return [
            //         'status' => false,
            //         'message' => 'El candidato "' . $candidate_name . '" no esta apto para poder ser enviado a contratación',
            //         'data' => []
            //     ];
            // }   

            $this->Recruitment_candidate->move_candidate($process_id, $candidate_id, 7);
        }

        $rrhh_users = $this->db->from('tbl_recruitment_rrhh_group_users')
             ->where('rrhh_group_id', $group_id)
             ->get()
             ->result();

		$this->db->where('job_ID', $job_id)
             ->delete('tbl_recruitment_rrhh_assignments');


        //Guardar usurios rrhh del grupo seleccionado
		foreach ($rrhh_users as $user) {
			$data_assignment = [
				'job_ID' => $job_id,
				'date_assignment' => date('Y-m-d'),
				'rrhh_user_ID' => $user->user_id
            ];

			$this->db->insert('tbl_recruitment_rrhh_assignments', $data_assignment);
		}

        foreach ($manual_rrhh_user_ids as $user_id) {
			$data_assignment = [
				'job_ID' => $job_id,
				'date_assignment' => date('Y-m-d'),
				'rrhh_user_ID' => $user_id,
				'manual' => 1
			];

			$this->db->insert('tbl_recruitment_rrhh_assignments', $data_assignment);
		}

        $this->db->where('job_id', $job_id)
             ->delete('tbl_recruitment_rrhh_group_assignments');
        
        if ($group_id) {   
            $this->db->insert('tbl_recruitment_rrhh_group_assignments', [
                'job_id' => $job_id,
                'rrhh_group_id' => $group_id
            ]);
        }

        $this->db->trans_complete();
    
        $trans_status = $this->db->trans_status();   
    
        if (!$trans_status) {
            return [
                'status' => false,
                'message' => 'No se pudo mover los candidatos',
                'data' => []
            ];
        }

        // Enviar notificaciones solicitandos los doumentos a los postulantes
        // para completar la contratacion
        foreach ($seekers as $seeker) {

            if (!$job->request_ID) {
                continue;
            }

            $notify_whatsapp = isset($data_input['notify_candidate_by_whatsapp']) && $data_input['notify_candidate_by_whatsapp'] == 1;

            $this->queue_lib->push('Notify_candidate_hired_job', [
                'candidate_id' => $seeker->ID,
                'request_id' => $job->request_ID,
                'notify_whatsapp' => $notify_whatsapp
            ]);
        }
        
        return [
            'status' => true,
            'message' => 'Candidatos movidos con éxito',
            'data' => []
        ];
    }
}
