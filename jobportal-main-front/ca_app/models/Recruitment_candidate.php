<?php
class Recruitment_candidate extends CI_Model
{  
    public function get_candidates_by_stage($process_id, $stage_id)
    {
        $this->db->select(array(
            'job_seeker.*',
            'seeker_applied.ID AS seeker_applied_ID',
            'rs_candidate.*'
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_seeker_applied_for_job seeker_applied', 'seeker_applied.job_ID=rs_candidate.job_ID AND seeker_applied.seeker_ID=rs_candidate.seeker_ID', 'left');
        $this->db->where('rs_candidate.process_id', $process_id);
        $this->db->where('rs_candidate.stage', $stage_id);
        $this->db->order_by('rs_candidate.discarded', 'ASC');
        $this->db->order_by('rs_candidate.is_new', 'DESC');
        $this->db->order_by('rs_candidate.creation_date', 'DESC');

        return $this->db->get()->result();
    }

    public function get_candidates_no_selected_by_job_id($job_id)
    {
        $this->db->select(array(
            'job_seeker.*',
            'seeker_applied.ID AS seeker_applied_ID',
            'rs_candidate.*'
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_seeker_applied_for_job seeker_applied', 'seeker_applied.job_ID=rs_candidate.job_ID AND seeker_applied.seeker_ID=rs_candidate.seeker_ID', 'left');
        $this->db->where('rs_candidate.job_ID', $job_id);
        $this->db->where('rs_candidate.stage<', 6);
    
        return $this->db->get()->result();
    }

    public function get_candidates_in_hiring_process_by_job_id($job_id)
    {
        $this->db->select(array(
            'job_seeker.*',
            'seeker_applied.ID AS seeker_applied_ID',
            'rs_candidate.*'
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_seeker_applied_for_job seeker_applied', 'seeker_applied.job_ID=rs_candidate.job_ID AND seeker_applied.seeker_ID=rs_candidate.seeker_ID', 'left');
        $this->db->where('rs_candidate.job_ID', $job_id);
        $this->db->where('rs_candidate.stage', 7);
    
        return $this->db->get()->result();
    }

    public function get_available_candidates_by_stage($job_id, $stage)
    {
        $this->db->select(array(
            'job_seeker.*',
            'seeker_applied.ID AS seeker_applied_ID',
            'rs_candidate.*'
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_seeker_applied_for_job seeker_applied', 'seeker_applied.job_ID=rs_candidate.job_ID AND seeker_applied.seeker_ID=rs_candidate.seeker_ID', 'left');
        $this->db->where('rs_candidate.job_ID', $job_id);
        $this->db->where('rs_candidate.stage', $stage);
        $this->db->where('rs_candidate.discarded', 0);
        
        $this->db->order_by('rs_candidate.discarded', 'DESC');

        return $this->db->get()->result();
    }

    public function count_all_candidates_by_stage($job_id, $stage)
    {
        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_recruitment_process process', 'process.id=rs_candidate.process_id');
        
        $this->db->where('process.job_ID', $job_id);
        
        if ($stage != -1) {
            $this->db->where('rs_candidate.stage', $stage);
        }
        
        return $this->db->count_all_results();
    }

    public function get_suggestions_candidates($term, $limit = 25)
    {
        $this->db->select([
            'candidate.*',
        ]);
        
        $this->db->from('tbl_job_seekers candidate');
        $this->db->where('candidate.sts', 'active');

        $this->db->group_start();
        $this->db->like('candidate.email', $term, 'after');        
        //$this->db->or_like('candidate.first_name', $term, 'after');
        //$this->db->or_like('candidate.last_name', $term, 'after');
        $this->db->group_end();

        $this->db->order_by('candidate.dated', 'DESC');

        $this->db->limit($limit);

        $job_seekers = $this->db->get()->result();
        
        $data = array();

        foreach ($job_seekers as $row_job_seeker) {
            
            $data[] = array(
                'value' => $row_job_seeker->ID, 
                'label' => $row_job_seeker->first_name . ' - ' . $row_job_seeker->email,
                'job_seeker_id' => $row_job_seeker->ID,
            );
        }
        return $data;
    }

    public function remove_all_candidates_stage($process_id, $candidate_ids)
    {
        $this->db->trans_start();

        foreach ($candidate_ids as $candidate_id) {
            $this->remove_candidate($process_id, $candidate_id);
        }

        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }
    
    public function remove_candidate($process_id, $candidate_id)
    {
        $this->remove_register_log_candidate($process_id, $candidate_id);

        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_ID', $candidate_id);    
        $this->db->delete('tbl_recruitment_candidates');
    
        return $this->Recruitment_process->update_process_stage($process_id);
    }

    public function move_candidates_stage($input)
    {
        $process_id = $input['process_id'];
        $process = $this->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;
        $job = $this->Posted_job->find($job_id);
	    $candidate_ids = $input['candidate_ids'];
		$stage = $input['stage'];
        $notify_candidate_by_mail = $input['notify_candidate_by_mail'] ?? 0;
        $notify_candidate_by_whatsapp = $input['notify_candidate_by_whatsapp'] ?? 0;
        
        $errors = null;
       
        $user_belong_to_company_internal = user_belong_to_company_internal();
        $success_candidates_ids = [];

        foreach ($candidate_ids as $candidate_id) {

            $rs_seeker = $this->get_candidate_in_process($process_id, $candidate_id);

            $candidate = $this->Job_seeker->get_job_seeker_by_id($candidate_id);

            if (!$candidate) {
                continue;
            }

            $candidate_name = $candidate->first_name . ' '. $candidate->last_name;

            if ($this->is_candidate_discarded($process_id, $candidate_id)) {
                $errors.= '- El candidato "' . $candidate_name . '" está descartado y no está ' . 
                          'disponible para moverlo de etapa <br />';
            }

            if ($user_belong_to_company_internal) {
                if ($this->is_candidate_active_in_other_process($process_id, $candidate_id)) {
                    $errors.= '- El candidato "' . $candidate_name . '" está en otro proceso activo y no está ' . 
                              'disponible para moverlo de etapa <br />';
                }

                if ($this->candidate_has_unanswered_forms($process_id, $candidate_id, $rs_seeker->stage)) {
                    
                    $errors.= '- El candidato "' . $candidate_name . '" tiene formularios sin responder o formularios con respuesta expiradas<br />';
                }

                // if ($this->rys_seeker_fit_helper->is_fit_to_move($job_id, $candidate_id, $rs_seeker->stage) === false) {
                //     $errors.= '- El candidato "' . $candidate_name . '" no está apto para pasarlo de etapa, por favor verficar respuesta de formularios<br />';
                // } 
            }
        }

        if ($errors !== null) {
            return [
                'status' => false,
                'message' => $errors,
            ];
        }

        $this->db->trans_start();

        foreach ($candidate_ids as $candidate_id) {
            if ($this->move_candidate($process_id, $candidate_id, $stage)) {
                $success_candidates_ids[] = $candidate_id;
            }
        }

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        if (!$trans_status) {
            return [
                'status' => false,
                'message' => 'No se pudo completar la transacción, por favor vuelva a intentarlo',
            ];
        }

        if (count($success_candidates_ids) == 0) {
            return [
                'status' => false,
                'message' => 'No se movio ningún candidato',
            ];
        }

        //Notificar a postulantes
        if ($notify_candidate_by_mail == 1 || $notify_candidate_by_whatsapp == 1) {
            foreach ($success_candidates_ids as $candidate_id) {
                $this->queue_lib->push('Notify_candidate_process_move_job', [
                    'candidate_id' => $candidate_id,
                    'request_id' => $job->request_ID,
                    'notify_by_mail' => $notify_candidate_by_mail,
                    'notify_by_whatsapp' => $notify_candidate_by_whatsapp
                ]);
            }
        }

        return [
            'status' => true,
            'message' => 'Postulantes movidos',
            'data' => [
                'count_success' => count($success_candidates_ids)
            ]
        ]; 
    }

    public function stop_tracking_candidate($request_data)
    {
        $this->db->where('process_id', $request_data['process_id']);
        $this->db->where('seeker_ID', $request_data['jobseeker_id']);
        
        $data_update = array(
            'discarded' => 1,
            'comments' => $request_data['comments'],
            'rejected_date' => $request_data['rejected_date'],
            'rejected_time' => $request_data['rejected_time'],
            'rejected_by_user' => $request_data['user_id'],
        );
    
        // Ejecutar la actualización en la base de datos y retornar el resultado
        return $this->db->update('tbl_recruitment_candidates', $data_update);
    }

    public function follow_up_candidate($process_id, $candidate_id)
    {        
        /*
        if (user_belong_to_company_internal()) {

            $this->db->from('tbl_recruitment_candidates list_candidate');
            $this->db->join('tbl_recruitment_process rs_process', 'list_candidate.job_ID=rs_process.job_ID');
            $this->db->where('rs_process.sts', 'active');
            $this->db->where('seeker_ID', $candidate_id);
            $this->db->where('discarded', 0);
            //$this->db->where('stage!=', 0);

            $count_candidate = $this->db->count_all_results();

            if ($count_candidate > 0) {
                return false;
            }
        }
        */
        
        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_ID', $candidate_id);

        $data_update = array(
            'discarded' => 0,
        );
        
        return $this->db->update('tbl_recruitment_candidates', $data_update);
    }

    public function get_last_stage_with_candidates($process_id)
    {
        $process = $this->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;

        $this->db->select([
            'process_stages.stage_id'
        ]);
        $this->db->from('tbl_recruitment_process_stages process_stages');
        $this->db->where('process_stages.job_id', $job_id);
        $this->db->order_by('process_stages.stage_id', 'ASC');
        $last_stage_selected_row = $this->db->get()->row();
        
        $this->db->select([
            'rs_candidate.stage AS stage_id'
        ]);
        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->where('rs_candidate.process_id', $process_id);
        $this->db->order_by('rs_candidate.stage', 'DESC');
        
        $last_stage_candidate_row = $this->db->get()->row();
        
        return $last_stage_candidate_row ? $last_stage_candidate_row->stage_id : ($last_stage_selected_row ? $last_stage_selected_row->stage_id : 0);
    }

    public function count_candidate_active_process($candidate_id, $job_id)
    {
        $this->db->from('tbl_recruitment_candidates list_candidate');
        $this->db->join('tbl_recruitment_process rs_process', 'list_candidate.job_ID=rs_process.job_ID');
        $this->db->where('rs_process.sts', 'active');
        $this->db->where('list_candidate.discarded', 0);
        $this->db->where('list_candidate.seeker_ID', $candidate_id);
        $this->db->where('list_candidate.job_ID!=', $job_id);
        $this->db->where('list_candidate.contracted', 0);

        return $this->db->count_all_results();
    }

    public function search_candidates_by_job_id($job_id, $search_query, $filters = array())
    {
        $this->db->select(array(
            'job_seeker.*',
            'rs_candidate.*'
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->where('rs_candidate.job_ID', $job_id);
        
        $this->db->group_start();        
        $this->db->like('job_seeker.first_name', $search_query, 'both');
        $this->db->or_like('job_seeker.email', $search_query, 'both');
        $this->db->group_end();

        if ($filters['stage'] != '' && $filters['stage'] != 'all') {
            $this->db->where('rs_candidate.stage', $filters['stage']);
        }

        if ($filters['sts_discarded'] != '' && $filters['sts_discarded'] != 'all') {
            $this->db->where('rs_candidate.discarded', $filters['sts_discarded']);
        }

        $this->db->order_by('rs_candidate.stage', 'DESC');
        $this->db->order_by('rs_candidate.discarded', 'ASC');
        $this->db->order_by('rs_candidate.creation_date', 'DESC');

        return $this->db->get()->result();
    }

    public function count_candidates_by_job_id($job_id, $search_query, $filters = array())
    {
        $this->db->select(array(
            'job_seeker.ID',
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->where('rs_candidate.job_ID', $job_id);
        $this->db->group_start();        
        $this->db->like('job_seeker.first_name', $search_query, 'both');
        $this->db->or_like('job_seeker.email', $search_query, 'both');
        $this->db->group_end();

        if ($filters['stage'] != '' && $filters['stage'] != 'all') {
            $this->db->where('rs_candidate.stage', $filters['stage']);
        }

        if ($filters['sts_discarded'] != '' && $filters['sts_discarded'] != 'all') {
            $this->db->where('rs_candidate.discarded', $filters['sts_discarded']);
        }
        
        return $this->db->count_all_results();
    }
    
    public function is_candidate_in_process($job_id, $candidate_id)
    {
        $this->db->from('tbl_recruitment_candidates candidate');
        $this->db->where('seeker_ID', $candidate_id);
        $this->db->where('job_ID', $job_id);
    
        return $this->db->count_all_results() > 0;
    }

    public function get_active_process_candidate($candidate_id, $process_id)
    {
        $this->db->select([
            'rs_process.id AS process_id',
            'rs_process.job_ID AS job_ID',
            'rs_candidate.creation_date',
            'rs_candidate.stage AS stage',
            'job.job_title AS job_title',
            'job.request_ID AS request_ID'
        ]);
        $this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('tbl_post_jobs job', 'job.ID=rs_process.job_id');
        $this->db->join('tbl_recruitment_candidates rs_candidate', 'rs_process.id=rs_candidate.process_id');
        $this->db->where('rs_candidate.seeker_ID', $candidate_id);
        $this->db->where('rs_candidate.discarded', 0);
        $this->db->where('rs_candidate.contracted', 0);
        
        if ($process_id) {
            $this->db->where('rs_candidate.process_id!=', $process_id);
        }
        
        $this->db->where('rs_process.sts', 'active');

        return $this->db->get()->result();
    }

    public function _get_suggestions_candidates($term, $job_id, $current_stage, $limit = 10)
    {
        $this->db->select(
            array(
                'candidate.*',
            )
        );
        
        $this->db->from('tbl_job_seekers candidate');
        $this->db->join('tbl_recruitment_candidates rs_candidate', 'candidate.ID=rs_candidate.seeker_ID');
        $this->db->where('rs_candidate.job_ID', $job_id);
        
        $this->db->group_start();        
        $this->db->like('candidate.first_name', $term, 'both');
        $this->db->or_like('candidate.email', $term, 'both');
        $this->db->group_end();

        $this->db->order_by('rs_candidate.discarded', 'ASC');
        $this->db->group_by('candidate.ID');

        $this->db->limit($limit);
    
        $job_seekers = $this->db->get()->result();
        
        $data = array();

        foreach ($job_seekers as $row_job_seeker) {
            $data[] = array(
                'value' => $row_job_seeker->ID, 
                'name' => $row_job_seeker->first_name,
                'email' => $row_job_seeker->email,
                'url_avatar' => img_pic_candidate($row_job_seeker->photo)
            );
        }
        return $data;
    }

    public function move_candidate(
        $process_id, 
        $candidate_id, 
        $to_stage
    )
    {
        $process = $this->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;
    
        $rs_seeker = $this->get_candidate_in_process($process_id, $candidate_id);

        if ($this->is_candidate_discarded($process_id, $candidate_id)) {
            return false;
        }

        $user_belong_to_company_internal = user_belong_to_company_internal();

        if ($user_belong_to_company_internal) {
            if ($this->is_candidate_active_in_other_process($process_id, $candidate_id)) {
                return false;
            }

            if ($this->candidate_has_unanswered_forms($process_id, $candidate_id, $rs_seeker->stage)) {
                return false;
            }   

            // if ($this->rys_seeker_fit_helper->is_fit_to_move($job_id, $candidate_id, $rs_seeker->stage) === false) {
            //     return false;
            // }   
        }

        $this->register_log_candidate_stage($process_id, $candidate_id, $to_stage);

        $data = array(
            'stage' => $to_stage,
            'is_new' => 0,
            'update_date' => date('Y-m-d')
        );

        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_ID', $candidate_id);
        $this->db->where('discarded', 0);

        $this->db->update('tbl_recruitment_candidates', $data);
        
        return $this->Recruitment_process->update_process_stage($process_id);
    }
    
    public function register_log_candidate_stage($process_id, $candidate_id, $stage)
    {
        $process = $this->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;
      
        $this->db->delete('tbl_recruitment_log_candidate_stage', array(
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'stage' => $stage
        ));
                
        $data = array(
            'seeker_ID' => $candidate_id,
            'job_ID' => $job_id,
            'stage' => $stage,
            'datetime' => date('Y-m-d H:i:s')
        );

        return $this->db->insert('tbl_recruitment_log_candidate_stage', $data);
    }

    public function remove_register_log_candidate($process_id, $candidate_id)
    {
        $process = $this->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;

        $this->db->where('seeker_ID', $candidate_id);
        $this->db->where('job_ID', $job_id);
        $this->db->delete('tbl_recruitment_log_candidate_stage');
    }

    public function verify_candidate($process_id, $candidate_id)
    {           
        $errors = [];

        $jobseeker = $this->Job_seeker->find($candidate_id);

        if (!$jobseeker) {
            $errors[] = '¡El candidato no se encontró!';
            return $errors;
        }
        
        $process_country = $this->Recruitment_process->get_country_by_process_id($process_id);
        $process_company = $this->Recruitment_process->get_company_by_process_id($process_id);
        
        $is_candidate_in_process = $this->exist_candidate_in_process($process_id, $candidate_id);
          
        if ($is_candidate_in_process) {
            $errors[] = 'Este candidato ya está agregado en este proceso';
            return $errors;
        }

        //Verificar si el postulante esta en lista negra en sistema nomina
        if ($process_country->iso_3166_1_alpha2 == 'PE' && $process_company->system_internal) {      
            $blacklist = $this->Job_seeker->get_overall_blacklist([$jobseeker->document_number]);

            $candidate_in_blacklist = isset($blacklist[$jobseeker->document_number]) && 
                                      ($blacklist[$jobseeker->document_number])->blacklist == 1;

            if ($candidate_in_blacklist) {
                $errors[] = 'Este candidato está en la lista negra.';
            }
        }
        
        return $errors;
    }

    public function is_candidate_discarded(
        $process_id, 
        $candidate_id)
    {
        $this->db->select('seeker_ID');
        $this->db->from('tbl_recruitment_candidates');
        $this->db->where('discarded', 1);
        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_ID', $candidate_id);
        
        return $this->db->count_all_results() > 0;
    }

    public function is_candidate_active_in_other_process(
        $process_id, 
        $candidate_id
    )
    {
        $this->db->select('rs.candiate.seeker_ID');
        $this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('tbl_recruitment_candidates rs_candidate', 'rs_process.id=rs_candidate.process_id');
        
        $this->db->join('tbl_post_jobs jobs', 'jobs.ID=rs_process.job_ID');
        $this->db->join('tbl_companies companies', 'companies.ID=jobs.company_ID');

        $this->db->where('rs_candidate.seeker_ID', $candidate_id);
        $this->db->where('rs_process.id!=', $process_id);
        $this->db->where('rs_candidate.contracted', 0);
        $this->db->where('rs_candidate.discarded', 0);
        $this->db->where('rs_process.sts', 'active');
        $this->db->where('companies.system_internal', 1);
        
        return $this->db->count_all_results() > 0;
    }

    public function get_candidate_in_process($process_id, $candidate_id)
    {
        $this->db->from('tbl_recruitment_process rs_process');
        $this->db->join('tbl_recruitment_candidates rs_candidate', 'rs_process.id=rs_candidate.process_id');
        $this->db->where('rs_candidate.seeker_ID', $candidate_id);
        $this->db->where('rs_candidate.process_id', $process_id);
    
        return $this->db->get()->row();
    }

    public function candidate_has_unanswered_forms(
        $process_id,
        $seeker_id,
        $stage
    )
    {
        $this->db->from('tbl_rys_forms form');
        $this->db->where('form.stage', $stage);

        $forms = $this->db->get()->result();

        foreach ($forms as $key => $form) {
            
            $this->db->from('tbl_rys_form_seekers form_seeker');
            $this->db->join('tbl_recruitment_candidates candidate',
                'candidate.seeker_ID=form_seeker.seeker_id'
            );

            $this->db->where('candidate.process_id', $process_id);
            $this->db->where('form_seeker.form_id', $form->form_id);
            $this->db->where('form_seeker.seeker_id', $seeker_id);
            $this->db->where('candidate.stage', $stage);
            $this->db->where('form_seeker.active', 1);

            $form_seeker = $this->db->get()->row();
 
            if ($form_seeker && $form_seeker->ignore_form_answers == 1) {
                return false;
            }

            if ($form->required == 1 && 
                (!$form_seeker || $form_seeker->answered == 0)) {
                return true;
            }
            
            if ($form->required == 0 && 
                $form_seeker && 
                $form_seeker->answered == 0) {
                return true;
            }

            // if ($form_seeker && 
            //     $form_seeker->form_id == 1 && 
            //     $form_seeker->answered == 1) {
                
            //     $now_date = new DateTime('now');
            //     $answer_date = new DateTime($form_seeker->answer_date);
            //     $interval = $now_date->diff($answer_date);

            //     if ($interval->m != 0) {
            //         return true;
            //     }
            // }
        }

        return false;
    }

    public function count_hired_candidates($process_id)
    {
        $this->db->where('process_id', $process_id);
        $this->db->where('contracted', 1);
        $this->db->where('stage', 7);
        
        $this->db->from('tbl_recruitment_candidates');

        return $this->db->count_all_results();
    }

    public function get_process_to_hiring_by_seeker_id($seeker_id)
    {
        //Verificar que el link provenga de la bandeja de reclutamiento tray
        if ($this->session->userdata('request_documents') == true) {
            
            $link_id = $this->session->userdata('request_documents_link_id');
            
            $this->db->select([
                'recruitment_process.id AS process_id',
                'recruitment_process.job_id AS job_ID',
                'doc_requests.seeker_id AS seeker_ID',
            ]);
    
            $this->db->from('tbl_recruitment_document_requests doc_requests');
            $this->db->join('tbl_recruitment_process recruitment_process', 'doc_requests.job_id=recruitment_process.job_ID');
            $this->db->join('tbl_recruitment_tray_candidates tray_candidates', 'doc_requests.seeker_id=tray_candidates.seeker_id AND tray_candidates.process_id=recruitment_process.id');

            $this->db->where('doc_requests.id', $link_id);
            $this->db->where('tray_candidates.status_id!=', 3); //En proceso o enviado
    
            $doc_requests = $this->db->get()->row();

            if ($doc_requests) {
                return $doc_requests;
            }
        }

        //Verificar que el link provenga de la bandeja de reclutamiento y seleccion - Cualquier etapa
        if ($this->session->userdata('request_documents') == true) {

            $link_id = $this->session->userdata('request_documents_link_id');

            $this->db->select([
                'rs_process.id AS process_id',
                'rs_process.job_ID AS job_ID',
                'rs_candidate.seeker_ID AS seeker_ID',
            ]);
    
            $this->db->from('tbl_recruitment_document_requests doc_requests');
            $this->db->join('tbl_recruitment_process rs_process', 'rs_process.job_ID=doc_requests.job_id');
            $this->db->join('tbl_recruitment_candidates rs_candidate', 'rs_candidate.seeker_ID=doc_requests.seeker_id AND rs_process.id=rs_candidate.process_id');
            $this->db->where('doc_requests.id', $link_id);
            $this->db->where('rs_candidate.discarded', 0);
            $this->db->where('rs_candidate.contracted', 0);

            $this->db->order_by('rs_process.id', 'DESC');
            
            return $this->db->get()->row();
        }

        //Verificar que el link provenga de la bandeja de reclutamiento y seleccion - Etapa CONTRATACION
        $this->db->select([
            'rs_process.id AS process_id',
            'rs_process.job_ID AS job_ID',
            'rs_candidate.seeker_ID AS seeker_ID',
        ]);
    
        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_recruitment_process rs_process', 'rs_candidate.process_id=rs_process.id');
        $this->db->where('rs_candidate.seeker_ID', $seeker_id);
        $this->db->where('rs_candidate.stage', 7);
        $this->db->where('rs_candidate.discarded', 0);
        $this->db->where('rs_candidate.contracted', 0);
        $this->db->order_by('rs_process.id', 'DESC');
    
        return $this->db->get()->row();
    }

    public function get_candidates_by_job_id($job_id)
    {
        $this->db->select(array(
            'job_seeker.*',
            'rs_candidate.*'
        ));

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->where('rs_candidate.job_ID', $job_id);

        return $this->db->get()->result();
    }
    
    public function get_candidate_process($job_id, $candidate_id)
    {
        $this->db->select([
            'job_seeker.*',
            'seeker_applied.ID AS seeker_applied_ID',
            'rs_candidate.*'
        ]);

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_seeker_applied_for_job seeker_applied', 'seeker_applied.job_ID=rs_candidate.job_ID AND seeker_applied.seeker_ID=rs_candidate.seeker_ID', 'left');
        $this->db->where('rs_candidate.job_ID', $job_id);
        $this->db->where('rs_candidate.seeker_ID', $candidate_id);

        return $this->db->get()->row();
    }
    
    /** New Refactoring  */

    public function get_candidate_by_process_id($process_id, $candidate_id)
    {
        $this->db->select([
            'job_seeker.*',
            'rs_candidate.*',
            'rs_stage.name AS stage_name',
            'seeker_applied.ID AS seeker_applied_ID',
        ]);

        $this->db->from('tbl_recruitment_candidates rs_candidate');
        $this->db->join('tbl_recruitment_process rs_process', 'rs_process.id=rs_candidate.process_id');
        $this->db->join('tbl_recruitment_stages rs_stage', 'rs_stage.id=rs_candidate.stage');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=rs_candidate.seeker_ID');
        $this->db->join('tbl_seeker_applied_for_job seeker_applied', 'seeker_applied.job_ID=rs_process.job_ID AND seeker_applied.seeker_ID=rs_candidate.seeker_ID', 'left');
        $this->db->where('rs_candidate.process_id', $process_id);
        $this->db->where('rs_candidate.seeker_ID', $candidate_id);

        return $this->db->get()->row();
    }

    public function add_candidate($process_id, $candidate_id, $stage = 0)
    {
        $this->load->model('Recruitment_process');

        $process = $this->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;

        $is_candidate_in_process = $this->exist_candidate_in_process($process_id, $candidate_id);

        if ($is_candidate_in_process) {
            return false;
        }
                    
        $this->Recruitment_process->open_process_if_not_open($job_id);

        $this->register_log_candidate_stage($process_id, $candidate_id, $stage);

        $data = [
            'process_id' => $process_id,
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'stage' => $stage,
            'creation_date' => date('Y-m-d')
        ];

        $this->db->insert('tbl_recruitment_candidates', $data);
        
        return $this->Recruitment_process->update_process_stage($process_id);
    }

    public function exist_candidate_in_process($process_id, $candidate_id)
    {
        $this->db->from('tbl_recruitment_candidates candidate');
        $this->db->where('process_id', $process_id);
        $this->db->where('seeker_ID', $candidate_id);
        
        return $this->db->count_all_results() > 0;
    }

    public function count_active_process($process_id, $candidate_id)
    {
        $this->db->from('tbl_recruitment_candidates list_candidate');
        $this->db->join('tbl_recruitment_process rs_process', 'list_candidate.process_id=rs_process.id');
        $this->db->where('rs_process.sts', 'active');
        $this->db->where('list_candidate.discarded', 0);
        $this->db->where('list_candidate.seeker_ID', $candidate_id);
        $this->db->where('list_candidate.process_id!=', $process_id);
        $this->db->where('list_candidate.contracted', 0);

        return $this->db->count_all_results();
    }
}
