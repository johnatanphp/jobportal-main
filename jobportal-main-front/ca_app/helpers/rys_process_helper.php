<?php
if (!function_exists('rs_stage_status_process')) {
    function data_candidate_required_documents($process_id = -1, $candidate_id = -1)
    {
        $ci =& get_instance();
        $ci->load->model('Jobseeker_additional_info');
        $ci->load->model('Requested_document');
        $ci->load->model('Jobseeker_required_document');
        $ci->load->model('Jobseeker_form_rtps');
        $ci->load->model('Rys_form_seeker');
        $ci->load->model('Recruitment_candidate');
        $ci->load->model('Form_question');
        $ci->load->model('Rys_form');
        $ci->load->model('Seeker_immigration_record');
        $ci->load->model('Seeker_residency_verification');
        $ci->load->model('Seeker_permission_signs_contract');
        $ci->load->model('Recruitment_process_contract_document');
        $ci->load->model('Recruitment_contract_document');
        $ci->load->model('Recruitment_process');
        $ci->load->model('Entry_form');

        $process = $ci->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;

        $data['process_id'] = $process_id;
        $data['job_id'] = $job_id;
        $data['candidate_id'] = $candidate_id;
        $data['jobseeker'] = $ci->Job_seeker->find($candidate_id);
        $data['seeker_additional_info'] = $ci->Jobseeker_additional_info->get_record_by_userid($candidate_id);

        $data['rs_process_candidate'] = $ci->Recruitment_candidate->get_candidate_process(
            $job_id, 
            $candidate_id
        );

        $data['identity_documents'] = $ci->Requested_document->get_identity_documents(
            $candidate_id,
            true
        );

        $data['receipt_service'] = $ci->Requested_document->get_receipt_service(
            $candidate_id
        );

        $data['seeker_photo'] = $ci->Requested_document->get_photo(
            $candidate_id
        );

        $data['domicile_affidavit'] = $ci->Jobseeker_required_document->get_domicile_affidavit(
            $candidate_id
        );

        $data['declaration_5th_category'] = $ci->Jobseeker_required_document->get_declaration_5th_category(
            $candidate_id
        );

        $data['certificate_5th_category'] = $ci->Jobseeker_required_document->get_certificate_5th_category(
            $candidate_id
        );

        $data['police_records'] = $ci->Jobseeker_required_document->get_police_records(
            $candidate_id
        );

        $data['candidate_studies'] = $ci->Job_seeker->get_qualification_by_jobseeker_id(
            $candidate_id
        );

        $data['candidate_experiences'] = $ci->Job_seeker->get_experience_by_jobseeker_id(
            $candidate_id
        );

        $data['count_experience_certificates'] = $ci->Job_seeker->count_experience_with_certificate(
            $candidate_id
        );

        $data['count_study_certificates'] = $ci->Job_seeker->count_study_with_certificate(
            $candidate_id
        );

        $data['contract_documents'] = $ci->Recruitment_process_contract_document->get_documents(
            $job_id
        );
        
        $form_rtps = $ci->db->get_where('tbl_seeker_form_rtps', [
            'job_id' => $job_id,
            'seeker_ID' => $candidate_id
        ])
        ->row();

        $data['candidate_spouse'] = [];
        $data['rtps_rightful_claimants'] = [];
        $data['candidate_children'] = [];

        if ($form_rtps) {

            $data['rtps_rightful_claimants'] = $ci->Jobseeker_form_rtps->get_rightful_claimants_by_form_id(
                $form_rtps->ID
            );
            
            $data['candidate_spouse'] = $ci->Jobseeker_form_rtps->get_rightful_claimant_spouse_by_form_id(
                $form_rtps->ID
            );

            if ($ci->session->userdata('current_profile_id') == 5) {
                $data['candidate_children'] = $ci->Jobseeker_form_rtps->get_peruvian_childrens(
                    $form_rtps->ID
                );
            } else {
                $data['candidate_children'] = $ci->Jobseeker_form_rtps->get_rightful_claimant_children_by_form_id(
                    $form_rtps->ID
                );
            }
        }

        $data['form_rtps'] = $form_rtps;

        $entry_form = $ci->Entry_form->find([
            'seeker_id' => $candidate_id,
            'process_id' => $process_id 
        ]);
        
        $data['entry_form'] = $entry_form;

        $data['immigration_records'] = $ci->Seeker_immigration_record->find(['seeker_id' => $candidate_id]);
        $data['residency_verifications'] = $ci->Seeker_residency_verification->find(['seeker_id' => $candidate_id]);
        $data['sign_contract'] = $ci->Seeker_permission_signs_contract->find(['seeker_id' => $candidate_id]);

        $assignment_form = $ci->Rys_form_seeker->get_assignment_by(
            1,
            $job_id,
            $candidate_id
        );  
        
        if ($assignment_form) {
            $data['form'] = $ci->Rys_form->get_form_by_id($assignment_form->form_id);
            $data['form_questions'] = $ci->Rys_form->get_questions_by_form_id($assignment_form->form_id);
        }

        $data['assignment_form'] = $assignment_form;

        return $data;
    }
}

if (!function_exists('rs_process_status_text')) {

    function rs_process_status_text($status)
    {
        $data = array(
            'finished' => 'Terminado',
            'active' => 'Activo',
            'suspended' => 'Suspendido',
            'canceled' => 'Cancelado'
        );

        return isset($data[$status]) ? $data[$status] : $status;
    }
}

if (!function_exists('candidate_is_process_contracting')) {
    function candidate_is_process_contracting()
    {           
        $ci =& get_instance();

        $ci->load->model('Recruitment_candidate');
        $candidate_id = $ci->session->userdata('user_id');

        $request_candidate = $ci->Recruitment_candidate->get_process_to_hiring_by_seeker_id($candidate_id);

        return $request_candidate ? true : false;
    }
}

if (!function_exists('rs_stage_status_process')) {
    function rs_stage_status_process($stage)
    {
        $stage_items = get_RS_stages();

        return isset($stage_items[$stage]) ? $stage_items[$stage]: 'No iniciado';
    }
}

if (!function_exists('get_RS_stages')) {
    function get_RS_stages()
    {   
        $ci =& get_instance();

        $ci->db->from('tbl_recruitment_stages');
        $ci->db->where('stage_group_id', 1);
        $ci->db->where('active', 1);
        $results = $ci->db->get()->result();

        $stage_items = [];

        foreach ($results as $row) {
            $stage_items[$row->id] = $row->name;
        }
      
        if (!user_belong_to_company_internal()) {
            unset($stage_items[7]);
        }

        return $stage_items;
    }
}

if (!function_exists('get_rs_document_name')) {
    function get_rs_document_name($key = '')
    {
        $documents = array(
            'evaluation_answer' => 'Respuestas de la evaluación',
            'validation_competence' => 'Validación de competencias',
            'home_verification' => 'Verificación domiciliaria',
            'work_reference' => 'Referecias laborales',
            'screnning' => 'Screnning',
            'certificate_emo' => 'Certificado de Aptitud (EMO)',
            'report_psycholabor' => 'Informe Psicolaboral',
            'report_competence' => 'Informe por competencias',
            'certificate_covid19' => 'Certificado COVID-19'
        );

        return isset($documents[$key]) ? $documents[$key] : $key; 
    }
}

if (!function_exists('rys_label_video_qualificacion')) {
    function rys_label_video_qualificacion($job_id, $seeker_id, $stage)
    {
        if ($stage != 2) {
            return '';
        }

        $ci =& get_instance();

        $request_video = $ci->db->get_where('tbl_recruitment_interview_videos', [
            'job_id' => $job_id,
            'seeker_id' => $seeker_id
        ])->row();

        $label = '';
        $title = '';
        
        if (!$request_video) {
            return '';
        }

        if (empty($request_video->video_path)) {
            $color = '#cccccc';
            $title = 'Video solicitado';
        }

        if ($request_video->video_path) {
            $color = '#2483b1';
            $title = 'Video grabado';
        }
        
        if ($request_video->qualification != null) {

            $title = 'Video calificado';

            $color_data = [
                1 => '#8aca7c',
                2 => '#dc4e4e',
                3 => '#f5955b' 
            ];

            $color = $color_data[$request_video->qualification];
        }

        $label = 
                "<span title=\"$title\" style=\"padding: 2px;color: $color;\">
                    <i class=\"glyphicon glyphicon-facetime-video\" style=\"top:3px;\"></i>
                </span>";

        return $label;
    }
}

if (!function_exists('rys_documents_allowed_to_upload')) {
    function rys_documents_allowed_to_upload() {
        
        if (user_belong_to_company_internal()) {
            $doc_allowed[] = 'certificate_emo';
            $doc_allowed[] = 'screnning';
            $doc_allowed[] = 'validation_competence';
            $doc_allowed[] = 'report_competence';
            $doc_allowed[] = 'home_verification';
            $doc_allowed[] = 'work_reference';
            $doc_allowed[] = 'certificate_covid19';
            $doc_allowed[] = 'report_psycholabor';
        }

        $doc_allowed[] = 'other_documents';
        $doc_allowed[] = 'evaluation_answer';

        return $doc_allowed;  
    }
}

if (!function_exists('seeker_doc_item_approval')) {
    function seeker_doc_item_approval($rs_document) {
        
        $ci =& get_instance();

        $approved = $rs_document && $rs_document->approved ? 1 : 0;
        $approved2 = $rs_document && $rs_document->legal_approved ? 1 : 0;
        $approved3 = $rs_document && $rs_document->accounting_approved ? 1 : 0;

        return '<div>
            <span ' . ($ci->session->userdata('current_profile_id') == 3 ? 'class="signal-approved"' : '') . ' style="' . ($approved  ?  '' : 'display: none;height:0;') . '"><i class="glyphicon glyphicon-ok document-ok"></i> RRHH </span>
            <span ' . ($ci->session->userdata('current_profile_id') == 5 ? 'class="signal-approved"' : '')  . ' style="' . ($approved2  ?  '' : 'display: none;height:0;') . '"><i class="glyphicon glyphicon-ok document-ok"></i> LEGAL</span>
            <span ' . ($ci->session->userdata('current_profile_id') == 6 ? 'class="signal-approved"' : '')  . ' style="' . ($approved3  ?  '' : 'display: none;height:0;') . '"><i class="glyphicon glyphicon-ok document-ok"></i> CONTABILIDAD</span>'
        . '</div>';
    }
}

if (!function_exists('seeker_doc_item_is_approved')) {
    function seeker_doc_item_is_approved($rs_document) {
        
        $ci =& get_instance();

        if ($ci->session->userdata('current_profile_id') == 3) {
            return $rs_document && $rs_document->approved ? 1 : 0;
        }

        if ($ci->session->userdata('current_profile_id') == 5) {
            return $rs_document && $rs_document->legal_approved ? 1 : 0;
        }

        if ($ci->session->userdata('current_profile_id') == 6) {
            return $rs_document && $rs_document->accounting_approved ? 1 : 0;
        }
    }
}
