<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Postulantes_documentos extends REST_Controller {

    public function index_get()
    {
        $list_num_doc_iden = (array)$this->get('num_doc_iden');

        if (empty($list_num_doc_iden)) {
            $this->response([
                'success' => false,
                'error' => 'Se debe ingresar el campo num_doc_iden'
            ], 200);
            return;
        }

        $data_doc['validacion_competencias'] = $this->validation_competence($list_num_doc_iden);
        $data_doc['verificacion_domiciliaria'] = $this->home_verification($list_num_doc_iden);
        $data_doc['informe_competencias'] = $this->report_competence($list_num_doc_iden);
        $data_doc['referencia_laborales'] = $this->work_reference($list_num_doc_iden);
        $data_doc['evaluaciones'] = $this->evaluation_answer($list_num_doc_iden);
        $data_doc['certificado_emo'] = $this->certificate_emo($list_num_doc_iden);
        $data_doc['screening'] = $this->screening($list_num_doc_iden);
        $data_doc['certificado_covid_19'] = $this->certificate_covid19($list_num_doc_iden);
        $data_doc['otros_documentos'] = $this->other_documents($list_num_doc_iden);
        $data_doc['cv_resumen'] = $this->cv_resumes($list_num_doc_iden);
        $data_doc['documentos_identidad'] = $this->identification_documents($list_num_doc_iden);
        $data_doc['declaracion_jurada_domicilio'] = $this->domicile_affidavits($list_num_doc_iden);
        $data_doc['certificado_5ta_categoria'] = $this->certificate_5th_categories($list_num_doc_iden);
        $data_doc['declaracion_jurada_5ta_categoria'] = $this->declaration_5th_categories($list_num_doc_iden);
        $data_doc['experiencia_laboral'] = $this->work_experiences($list_num_doc_iden);
        $data_doc['estudios'] = $this->studies($list_num_doc_iden);
        $data_doc['derechohabientes'] = $this->rightful_claimants($list_num_doc_iden);
        $data_doc['otros_estudios'] = $this->other_studies($list_num_doc_iden);

        $seeker_documents = $this->build_data_seeker_documents($list_num_doc_iden, $data_doc);

        $this->response([ 
            'success' => true,
            'data' => ['postulantes' => $seeker_documents]
        ], 200);
    }

    private function build_data_seeker_documents($list_num_doc_iden, $data_doc)
    {   
        $this->db->select([
            'document_type',
            'document_number',
            'first_name',
            'last_name',
            'employee_code'
        ]);

        $this->db->from('tbl_job_seekers');
        $this->db->where_in('document_number', $list_num_doc_iden);

        $seekers = $this->db->get()->result();
        $seeker_results = [];

        foreach ($seekers as $key => $seeker) {
            $seeker_data = [
                'doc_identidad_tipo' => document_type_abbr($seeker->document_type),
                'doc_identidad_numero' => $seeker->document_number,
                'nombre' => $seeker->first_name,
                'apellidos' => $seeker->last_name,
                'trabajador_codigo' => $seeker->employee_code
            ];
            
            foreach ($data_doc as $doc_key => $doc_data) {
                $seeker_data[$doc_key] = isset($doc_data[$seeker->document_number]) ? $doc_data[$seeker->document_number] : [];
            }

            $seeker_results[] = $seeker_data;
        }

        return $seeker_results;
    }

    private function validation_competence($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        
        $this->db->where('doc.key', 'validation_competence');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source) 
            ];
        }

        return $data;
    }

    private function home_verification($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'home_verification');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source) 
            ];
        }

        return $data;
    }

    private function report_competence($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'report_competence');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source)  
            ];
        }

        return $data;
    }

    private function work_reference($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'work_reference');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source) 
            ];
        }

        return $data;
    }

    private function evaluation_answer($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);

        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'evaluation_answer');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source) 
            ];
        }

        return $data;
    }

    private function certificate_emo($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'certificate_emo');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source)
            ];
        }

        return $data;
    }

    private function screening($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'screnning');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source) 
            ];
        }

        return $data;
    }

    private function certificate_covid19($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'certificate_covid19');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->file_source) 
            ];
        }

        return $data;
    }

    private function other_documents($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_source',
            'doc.document_title',
            'doc.created_at AS doc_created_at',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible'
        ]);
        $this->db->from('tbl_recruitment_attached_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where('doc.key', 'other_documents');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_titulo' => $row->document_title,
                'archivo_url' => file_url($row->file_source) 
            ];
        }

        return $data;
    }

    private function identification_documents($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.path',
            'doc.doc_type',
            'doc_type.abbreviation AS doc_abbreviation'
        ]);
        $this->db->from('tbl_seeker_identification_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=doc.doc_type');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'archivo_doc_tipo' => $row->doc_abbreviation,
                'archivo_url' => file_url($row->path)
            ];
        }

        return $data;
    }

    private function cv_resumes($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_name',
            'doc.dated'
        ]);
        $this->db->from('tbl_seeker_resumes doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'archivo_url' => file_url($row->file_name),
                'archivo_fecha_subido' => $row->dated 
            ];
        }

        return $data;
    }

    private function domicile_affidavits($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible',
            'doc.file_path AS doc_file_path',
            '(CASE
                WHEN doc.evicertia_status = 1 THEN "PENDIENTE"
                WHEN doc.evicertia_status = 2 THEN "EN ESPERA"
                WHEN doc.evicertia_status = 3 THEN "FIRMADO"
                WHEN doc.evicertia_status = 4 THEN "RECHAZADO"
                WHEN doc.evicertia_status = 5 THEN "FALLIDO"
                WHEN doc.evicertia_status = 6 THEN "EXPIRADO"
                ELSE ""
            END) AS evicertia_status',    
            'CONCAT("' . $this->config->item('evicertia_sitio_url') . '/Evidence/EviSign/", "", doc.evicertia_unique_id) AS evicertia_evidence_url',
            'doc.created_at AS doc_created_at'
        ]);
        $this->db->from('tbl_seeker_domicile_affidavits doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_id=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID', 'left');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->doc_file_path),
                'evicertia_estado' => $row->evicertia_status,
                'evicertia_evidence_url' => $row->evicertia_evidence_url  
            ];
        }

        return $data;
    }

    private function certificate_5th_categories($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.file_path'
        ]);
        $this->db->from('tbl_seeker_certificate_5th_categories doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_id=seekers.ID');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'archivo_url' => file_url($row->file_path) 
            ];
        }

        return $data;
    }    

    private function declaration_5th_categories($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'jobs.job_title',
            'staff_requests.consultant_name',
            'staff_requests.client_company_name',
            'staff_requests.business_unit_name',
            'staff_requests.cost_center',
            'CONCAT(app_users.first_name, " ", app_users.last_name) AS staff_request_responsible',
            'doc.file_path AS doc_file_path',
            '(CASE
                WHEN doc.evicertia_status = 1 THEN "PENDIENTE"
                WHEN doc.evicertia_status = 2 THEN "EN ESPERA"
                WHEN doc.evicertia_status = 3 THEN "FIRMADO"
                WHEN doc.evicertia_status = 4 THEN "RECHAZADO"
                WHEN doc.evicertia_status = 5 THEN "FALLIDO"
                WHEN doc.evicertia_status = 6 THEN "EXPIRADO"
                ELSE ""
            END) AS evicertia_status',    
            'CONCAT("' . $this->config->item('evicertia_sitio_url') . '/Evidence/EviSign/", "", doc.evicertia_unique_id) AS evicertia_evidence_url',
            'doc.created_at AS doc_created_at'
        ]);
        $this->db->from('tbl_seeker_declaration_5th_categories doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_id=seekers.ID');
        $this->db->join('tbl_post_jobs jobs', 'doc.job_ID=jobs.ID', 'left');
        $this->db->join('tbl_staff_requests staff_requests', 'jobs.request_ID=staff_requests.ID', 'left');
        $this->db->join('tbl_employers app_users', 'staff_requests.employer_ID=app_users.ID', 'left');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'consultora_nombre' => $row->consultant_name,
                'cliente_nombre' => $row->client_company_name,
                'unidad_negocio_nombre' => $row->business_unit_name,
                'centro_costo' => $row->cost_center,
                'empleo_nombre' => $row->job_title,
                'rys_responsable' => $row->staff_request_responsible,
                'archivo_fecha_subido' => $row->doc_created_at,
                'archivo_url' => file_url($row->doc_file_path),
                'evicertia_estado' => $row->evicertia_status,
                'evicertia_evidence_url' => $row->evicertia_evidence_url  
            ];
        }

        return $data;
    }

    private function work_experiences($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.job_title',
            'doc.company_name',
            'doc.attached_certificate',
            'doc.start_date',
            'doc.end_date',
            'doc.country',
            'doc.city'
        ]);
        $this->db->from('tbl_seeker_experience doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'empleo_nombre' => $row->job_title,
                'compania' => $row->company_name,
                'fecha_inicio' => $row->start_date,
                'fecha_fin' => $row->end_date, 
                'pais' => $row->country,
                'ciudad' => $row->city,
                'certificado_url' => $row->attached_certificate ? file_url($row->attached_certificate) : '',
            ];
        }

        return $data;
    }

    private function studies($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.degree_title',
            'doc.institude',
            'doc.attached_certificate',
            'doc.start_date',
            'doc.end_date',
            'doc.country',
            'doc.city',
            'doc.major'
        ]);
        $this->db->from('tbl_seeker_academic doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'estudio_nombre' => $row->major,
                'estudio_grado' => $row->degree_title,
                'instituto_universidad' => $row->institude,
                'fecha_inicio' => $row->start_date,
                'fecha_fin' => $row->end_date, 
                'pais' => $row->country,
                'ciudad' => $row->city,
                'certificado_url' => $row->attached_certificate ? file_url($row->attached_certificate) : '',
            ];
        }

        return $data;
    }

    private function other_studies($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.name',
            'doc.institute',
            'doc.start_date',
            'doc.end_date',
            'doc.country',
            'doc.type'
        ]);
        $this->db->from('tbl_seeker_other_studies doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'estudio' => $row->name,
                'tipo' => $row->type,
                'instituto' => $row->institute,
                'fecha_inicio' => $row->start_date,
                'fecha_fin' => $row->end_date, 
                'pais' => $row->country
            ];
        }

        return $data;
    }
    
    private function rightful_claimants($seeker_ids)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'form_rtps_rc.first_name AS rc_first_name',
            'form_rtps_rc.last_name AS rc_last_name',
            'form_rtps_rc.document_type AS rc_document_type',
            'form_rtps_rc.document_number AS rc_document_number',
            'form_rtps_rc.birthdate AS rc_birthdate',
            'form_rtps_rc.gender AS rc_gender',
            'kinship.name AS rc_kinship',
            'kinship_cert.name AS kinship_cert_type',
            'form_rtps_rc.kinship_cert_code',
            'form_rtps_rc.ubigeo AS rc_ubigeo',
            'form_rtps_rc.live_same_domicile',
            'form_rtps_rc.domicile AS rc_domicile',
            'form_rtps_rc.attached_document_number AS rc_attached_document_number',
            'form_rtps_rc.kinship_cert_attached AS rc_kinship_cert_attached'
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants form_rtps_rc');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps_rc.form_ID=form_rtps.ID');
        $this->db->join('tbl_kinship kinship', 'kinship.id=form_rtps_rc.kinship');
        $this->db->join('tbl_kinship_certificates kinship_cert', 'kinship_cert.id=form_rtps_rc.kinship_cert_type');
        $this->db->join('tbl_job_seekers seekers', 'form_rtps.seeker_ID=seekers.ID');
        $this->db->where_in('seekers.document_number', $seeker_ids);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[$row->document_number][] = [
                'nombre' => $row->rc_first_name,
                'apellidos' => $row->rc_last_name,
                'doc_identidad_tipo' => document_type_abbr($row->rc_document_type),
                'doc_identidad_numero' => $row->rc_document_number,
                'fecha_nacimiento' => $row->rc_birthdate,
                'sexo' => gender_text($row->rc_gender),
                'parentesco' => $row->rc_kinship,
                'parentesco_certificado_tipo' => $row->kinship_cert_type,
                'parentesco_certificado_codigo' => $row->kinship_cert_code,
                'ubigeo' => $row->rc_ubigeo,
                'direccion' => $row->rc_domicile,
                'vive_mismo_domicilio' => $row->live_same_domicile ? 1 : 0,
                'doc_identidad_url' =>  file_url($row->rc_attached_document_number),
                'parentesco_certificado_url' =>  file_url($row->rc_kinship_cert_attached),
            ];
        }

        return $data;
    } 
}
