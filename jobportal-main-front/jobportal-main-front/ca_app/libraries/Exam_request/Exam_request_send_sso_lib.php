<?php 
class Exam_request_send_sso_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send($params)
    {
        //dd($params);
        $employer_id = $params['sent_by_employer_id']; 
        $user_current = $this->Employer->find($employer_id);
        $exam_request_ids = $params['ids'];
        
        $this->db->select([
            'js.ID AS seeker_id',
            'js.paternal_last_name',
            'js.maternal_last_name',     
            'js.first_name',
            'js.last_name',
            'js.document_number',
            'doc_type.id AS document_type_id',
            'js.email',
            'js.city',
            'js.dob',
            'js.mobile',
            'js.present_address',
            'ers.scheduled_date',
            'ers.ubigeo',
            'ers.comment',
            'ers.id',
            'ers.id AS ers_id',
            'ers.exam_type_id',
            'ers.exam_doc_type',
            'ers.job_id',
            'ers.medical_center_code',
            'ers.medical_center_location_code',
            'jobs.job_title',
            'staff_requests.ID AS request_id',
            'staff_requests.no_cia AS no_cia',
            'staff_requests.cod_clie AS cod_clie',
            'staff_requests.client_company_name AS client_company_name',
            'staff_requests.cod_business_unit AS business_unit_code',
            'staff_requests.cost_center AS cost_center'
        ]);

        $this->db->from('tbl_recruitment_candidates rs');
        $this->db->join('tbl_job_seekers js', 'js.ID=rs.seeker_ID');
        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=js.document_type');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.id=rs.process_id');
        $this->db->join('tbl_exam_request_seekers ers', 'ers.job_id=recruitment_process.job_ID AND rs.seeker_ID=ers.seeker_id');
        $this->db->join('tbl_post_jobs jobs', 'ers.job_id=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID');
        
        if (count($exam_request_ids) > 0) {
            $this->db->where_in('ers.id', $exam_request_ids);
        }

        $this->db->where('ers.status', 1);
        $this->db->where('ers.active', 1);
        $this->db->where('ers.schedule_id', null);
        
        $this->db->group_by('ers.id');
        
        $seekers = $this->db->get()->result();
        
        //dd($seekers);
        
        if (count($seekers) == 0) {
            return [false, 'No hay programaciones a enviar.'];
        }

        $seeker_exams = [];
        $exam_seeker_ids = [];
        $request_id = null;
        $job_id = null;

        foreach ($seekers as $exam) {

            $exam_seeker_ids[] = $exam->ers_id;
            $request_id = $exam->request_id;
            $job_id = $exam->job_id;
            $exam_type = $exam->exam_doc_type;

            $product_id = $this->get_hrm_product_id($exam->exam_type_id, $exam_type);

            if (empty($product_id)) {
                return [false, 'Postulante ' . $exam->first_name  . ' no tiene Exam. tipo válido'];
            }

            $ubigeo = $exam->ubigeo;
            $ubigeo_code = $this->get_ubigeo_code($ubigeo);

            if (empty($ubigeo_code)) {
                return [false, 'Postulante ' . $exam->first_name  . ' no tiene Ubigeo válido'];
            }

            list($departament, $province, $district) = explode(',' , $ubigeo);

            $seeker_exams[] = [
                'ref_id' => $exam->id,
                'rys_id' => $exam->job_id,
                'rys_tipo' => $exam->business_unit_code == 'TM' ? 2 : 0,
                'postulante_id' => $exam->seeker_id,
                'cia' => $exam->no_cia,
                'clie' => $exam->cod_clie,
                'clie_nombre' => $exam->client_company_name,
                'ccosto' => $exam->cost_center,
                'tipo_egreso' => '',
                'email' => $this->mask_email($exam->email),
                'doc_ident_tipo' => $exam->document_type_id,
                'doc_iden_numero' => $exam->document_number,
                'apellido_paterno' => trim($exam->paternal_last_name),
                'apellido_materno' => trim($exam->maternal_last_name),
                'nombre' => trim($exam->first_name),
                'fecha_nacimiento' => $exam->dob,
                'departamento' => $departament,
                'provincia' => $province,
                'distrito' => $district,
                'direccion' => trim((string)$exam->present_address),
                'telefono' => $this->mask_mobile_number($exam->mobile),
                'examen_fecha' => $exam->scheduled_date,
                'examen_id' => $exam->exam_type_id,
                'puesto_nombre' => $exam->job_title,
                'solicitante_nombre' => trim($user_current->first_name . ' ' . $user_current->last_name),
                'solicitante_email' => $this->mask_email($user_current->email),
                'observaciones' => $exam->comment,
                'ubigeo_codigo' => $ubigeo_code,
                'producto_id' => $product_id,
                'proveedor_codigo' => $exam->medical_center_code,
                'proveedor_sede_id' => $exam->medical_center_location_code,
                'eecc_codigo' => '',
                'eecc_descripcion' => ''
            ];

            //dd($seeker_exams);
        }

        $url = $this->config->item('hrm_api_url') . '/programacion_examen/registro_solicitud_portal_empleo';
       // dd($url);
        $api_params = [
            'api_key' => $this->config->item('hrm_api_key'),
            'examenes' => $seeker_exams
        ];

        $response = $this->curl_lib->exec($url, 'POST', $api_params);
         //dd($response);
        $response = json_decode($response, true);
       
        $message = isset($response['message']) ? $response['message'] : 'No se pudo registrar la programación HRM API';
       // dd($url);
        if ($message != 'OK') {
            return [false, $message];
        }

        $this->db->where_in('id', $exam_seeker_ids);
        $this->db->update('tbl_exam_request_seekers', ['notified_sso' => 1]);
        
        $notify_sso = $params['send_email_sso'] ?? 0;
        
        if ($notify_sso) {
            
            $staff_request = $this->Staff_request->find($request_id);
    
            $users = $this->Employer->get_internal_by_profile_id($staff_request->company_ID, 4);
          
            $emails = [];
    
            foreach ($users as $user) {
                $emails[] = $user->email;
            }
            
            $data_email = [
                'staff_request' => $staff_request,
                'exam_candidates' => $this->get_exam_candidates($exam_request_ids),
                'sent_at' => date('Y-m-d H:i'),
                'sent_by' => $user_current,
                'url_link' => $this->config->item('hrm_url') . '/programacion_examen/programacion/listado'
            ];
            
            $this->email->init();
            $this->email->to($emails);
            $this->email->subject('Solicitud de evaluaciones médicas');
            $this->email->message(load_email_view('email/notify_sso_schedule', $data_email));     
    
            if (count($emails) > 0) {
                $this->email->send();
            }
        }
       
        $data = [];
        foreach ($seeker_exams as $exam) {
            $data[]['id'] = $exam['ref_id'];
        }
    
        return [true, 'Programaciones enviadas a SSO', $data];
    }
    
    private function get_hrm_product_id($exam_type_id, $exam_doc_type)
    {
        $this->db->from('tbl_exam_request_product_codes');
        $this->db->where('exam_type_id', $exam_type_id);        
        $this->db->where('cod_portal', $exam_doc_type);

        $row = $this->db->get()->row();

        return $row ? $row->hrm_id : null;
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
    
    public function mask_email($email)
    {
        if ($this->config->item('env') != 'production') {
            $emails = explode(",", $this->config->item('email_notifications'));
            return isset($emails[0]) ? trim((string)$emails[0]) : null;
        }
        
        return $email;
    }
    
    public function mask_mobile_number($mobile_number)
    {
        if ($this->config->item('env') != 'production') {
            return $this->config->item('mobile_notifications');
        }
        
        return $mobile_number;
    }
    
    private function get_exam_candidates($exam_ids)
    {
        $this->db->select([
            'candidates.ID AS id',
            'candidates.first_name AS first_name',
            'candidates.last_name AS last_name',
            'document_types.abbreviation AS document_type_abbreviation_name',
            'document_types.name AS document_type_name',
            'candidates.document_number AS document_number'
        ]);
        $this->db->from('tbl_job_seekers candidates');
        $this->db->join('tbl_exam_request_seekers exam_candidates', 'candidates.ID=exam_candidates.seeker_id');
        $this->db->join('tbl_identity_document_types document_types', 'document_types.id=candidates.document_type');
        $this->db->where_in('exam_candidates.id', $exam_ids);
        return $this->db->get()->result();
    }
}
