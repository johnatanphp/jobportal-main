<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contract_documents_candidate_search_service
{
    private $pagination_per_page;

    private $pagination_page;

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec($params)
    {
        list($is_success, $message) = $this->validate($params);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }
     
        return  $this->search_documents($params);  
    }

    public function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('process_id', 'process_id', 'required|integer');
        $this->form_validation->set_rules('candidate_id', 'candidate_id', 'required|integer');
   
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        $form_is_success = $this->form_validation->run();
        $message_error = $this->form_validation->error_array();

        if (!$form_is_success && current($message_error) === false) {
            return [false, 'Debe indicar algún filtro para realizar la búsqueda'];
        }

        if (!$form_is_success) {  
           return [false, current($message_error)];
        }

        $candidate_id = $params['candidate_id'];
        
        $this->db->select('ID AS id');
        $this->db->from('tbl_job_seekers');
        $this->db->where('ID', $candidate_id);
        $candidate = $this->db->get()->row();

        if (!$candidate) {
            return [false, 'Candidato Id no es válido'];
        }

        return [true, 'Ok'];
    }

    public function search_documents($params)
    {
        $process_id = $params['process_id'];
        $candidate_id = $params['candidate_id'];

        $seeker_documents = $this->build_data_seeker_documents($process_id, $candidate_id);

        return [
            'status' => true,
            'message' => 'Ok',
            'data' => $seeker_documents
        ];
    }

    private function build_data_seeker_documents($process_id, $candidate_id)
    {   
        $this->db->select([
            'documents.*',
            'option_type.name AS type_name',
            'document_group.id AS document_group_id',
            'document_group.name AS document_group_name'
        ]);
        $this->db->from('tbl_recruitment_contract_document_types documents');
        $this->db->join('tbl_recruitment_document_option_types option_type', 'documents.option_type_id=option_type.id');
        $this->db->join('tbl_staff_requests requests', 'requests.company_ID=documents.company_id');
        $this->db->join('tbl_recruitment_process process', 'process.request_id=requests.ID');
        $this->db->join('tbl_contract_document_groups document_group', 'document_group.id=documents.group_id', 'left');
        $this->db->where('process.id', $process_id);
        $this->db->where_in('documents.id', [
            1, 2, 3, 7, 10, 14, 15
        ]);
        $this->db->where('documents.active', 1);
        
        $documents = $this->db->get()->result();

        $data = [];

        foreach ($documents as $doc) {

            $data_documents = [];

            //Documentos adjuntos
            if ($doc->option_type_id == 1) {
                $data_documents = $this->recruitment_contract_documents($doc->id, $candidate_id);
            }
           
            //Documentos programados / Logica
            if ($doc->option_type_id == 2) {
                // Documento de identidad
                if ($doc->id == 1) {
                    $data_documents = $this->identification_documents($candidate_id);
                }

                $entry_form = $this->entry_form($process_id, $candidate_id);

                // Ficha de ingreso
                if ($doc->document_group_id == 1 && $entry_form) {
                    $data_documents[] = [
                        'file_url' => site_url('general/jobseeker/form_rtps/view/' . $this->custom_encryption->encrypt_data($entry_form->id, 1)),
                        'signature' => [
                            'status_name' => $entry_form->signature_status
                        ]
                    ];
                }

                $spouse = $this->get_spouse(@$entry_form->id);
           
                // Copia documento identidad Conyugue
                if ($doc->id == 7 && $spouse && $spouse->rc_attached_document_number) {
                    $data_documents[] = [
                        'file_url' => file_url($spouse->rc_attached_document_number),
                    ];
                }

                // Copia documento identidad Derechohabinetes
                if ($doc->id == 10) {
                    $data_documents = $this->get_data_identification_document_rightful_claimants(@$entry_form->id);
                }

                // Certificados de estudios
                if ($doc->id == 9) {
                    $data_documents = $this->studies($candidate_id);
                }

                // Certificados de trabajos
                if ($doc->id == 13) {
                    $data_documents = $this->work_experiences($candidate_id);
                }
            }
            
            $group = null;  

            if ($doc->document_group_id) {
                $group = [
                    'id' => $doc->document_group_id,
                    'name' => $doc->document_group_name
                ];
            }

            $data[] = [
                'id' => $doc->id,
                'name' => $doc->name,
                'type' => [
                    'id' => $doc->option_type_id,
                    'name' => $doc->type_name,
                ],
                'group' => $group,
                'data' => $data_documents
            ];
        }
        
        return $data;
    }

    private function identification_documents($seeker_id)
    {
        $this->db->select([
            'seekers.ID AS seeker_id',
            'seekers.document_number',
            'doc.path',
            'doc_type.id AS id',
            'doc_type.name AS name'
        ]);
        $this->db->from('tbl_seeker_identification_documents doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->join('tbl_identity_document_types doc_type', 'doc_type.id=doc.doc_type');
        $this->db->where('seekers.ID', $seeker_id);

        $document = $this->db->get()->row();
       
        if (!$document) {
            return [];
        }

        $data[] = [
            'identification_document_type_name' => $document->name,
            'file_url' => file_url($document->path)
        ];
        
        return $data;
    }

    private function entry_form($process_id, $seeker_id)
    {
        $this->load->model('Recruitment_process');

        $process = $this->Recruitment_process->find(['id' => $process_id]);
        $job_id = $process->job_ID;

        $this->db->select([
            'form_rtps.ID AS id',
            '(CASE
                WHEN form_rtps.evicertia_status = 1 THEN "PENDIENTE"
                WHEN form_rtps.evicertia_status = 2 THEN "EN ESPERA"
                WHEN form_rtps.evicertia_status = 3 THEN "FIRMADO"
                WHEN form_rtps.evicertia_status = 4 THEN "RECHAZADO"
                WHEN form_rtps.evicertia_status = 5 THEN "FALLIDO"
                WHEN form_rtps.evicertia_status = 6 THEN "EXPIRADO"
                ELSE "-"
            END) AS signature_status'
        ]);
        $this->db->from('tbl_seeker_form_rtps form_rtps');
        
        $this->db->where('form_rtps.seeker_ID', $seeker_id);
        $this->db->where('form_rtps.job_ID', $job_id);
        $this->db->order_by('form_rtps.ID', 'DESC');

        $form_rtps = $this->db->get()->row();

        return $form_rtps;
    }

    private function work_experiences($seeker_id)
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
            'doc.job_level',
            'doc.city'
        ]);
        $this->db->from('tbl_seeker_experience doc');
        $this->db->join('tbl_job_seekers seekers', 'doc.seeker_ID=seekers.ID');
        $this->db->where('doc.attached_certificate!=', '');
        $this->db->where('seekers.ID', $seeker_id);

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[] = [
                'job_position' => $row->job_title,
                'job_level' => $row->job_level,
                'company_name' => $row->company_name,
                'start_date' => $row->start_date,
                'end_date' => $row->end_date, 
                'file_url' => $row->attached_certificate ? file_url($row->attached_certificate) : null,
            ];
        }

        return $data;
    }

    private function studies($seeker_id)
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
        $this->db->where('seekers.ID', $seeker_id);
        $this->db->where('doc.attached_certificate!=', '');

        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $key => $row) {
            $data[] = [
                'degree_title' => $row->degree_title,
                'career' => $row->major,
                'start_date' => $row->start_date,
                'end_date' => $row->end_date, 
                'file_url' => $row->attached_certificate ? file_url($row->attached_certificate) : null,
            ];
        }

        return $data;
    }

    private function get_spouse($form_rtps_id)
    {
        $this->db->select([
            'form_rtps_rc.first_name AS rc_first_name',
            'form_rtps_rc.last_name AS rc_last_name',
            'form_rtps_rc.document_type AS rc_document_type',
            'form_rtps_rc.document_number AS rc_document_number',
            'form_rtps_rc.birthdate AS rc_birthdate',
            'form_rtps_rc.gender AS rc_gender',
            'form_rtps_rc.attached_document_number AS rc_attached_document_number',
            'kinship.name AS rc_kinship_name'
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants form_rtps_rc');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps_rc.form_ID=form_rtps.ID');
        $this->db->join('tbl_kinship kinship', 'kinship.id=form_rtps_rc.kinship');
        $this->db->where('form_rtps.ID', $form_rtps_id);
        $this->db->where('(form_rtps_rc.kinship = 1 OR form_rtps_rc.kinship = 2)');
        
        return $this->db->get()->row();
    }

    private function get_data_identification_document_rightful_claimants($form_rtps_id)
    {
        $this->db->select([
            'form_rtps_rc.first_name AS rc_first_name',
            'form_rtps_rc.last_name AS rc_last_name',
            'form_rtps_rc.document_type AS rc_document_type',
            'form_rtps_rc.document_number AS rc_document_number',
            'form_rtps_rc.birthdate AS rc_birthdate',
            'form_rtps_rc.gender AS rc_gender',
            'form_rtps_rc.attached_document_number AS rc_attached_document_number',
            'kinship.name AS rc_kinship_name'
        ]);
        $this->db->from('tbl_form_rtps_rightful_claimants form_rtps_rc');
        $this->db->join('tbl_seeker_form_rtps form_rtps', 'form_rtps_rc.form_ID=form_rtps.ID');
        $this->db->join('tbl_kinship kinship', 'kinship.id=form_rtps_rc.kinship');
        $this->db->where('form_rtps.ID', $form_rtps_id);
        $this->db->where('form_rtps_rc.kinship!=', 1);
        $this->db->where('form_rtps_rc.kinship!=', 2);
         
        $results = $this->db->get()->result();
        $data = [];

        foreach ($results as $row) {
            $data[] = [
                'identification_document_type_name' => document_type_abbr($row->rc_document_type),
                'identification_document_number' => $row->rc_document_number,
                'first_name' => $row->rc_first_name,
                'last_name' => $row->rc_last_name,
                'kinship' => $row->rc_kinship_name,
                'file_url' => file_url($row->rc_attached_document_number),
            ];
        }

        return $data;
    }

    private function recruitment_contract_documents($document_id, $seeker_id)
    {
        $this->db->select([
            'contract_documents.file_path AS document_path',
            'document_types.id AS document_type_id',
            'document_types.name AS document_type_name'
        ]);
        $this->db->from('tbl_recruitment_contract_documents contract_documents');
        $this->db->join('tbl_recruitment_contract_document_types document_types', 'document_types.id=contract_documents.document_id');
        $this->db->where('contract_documents.seeker_id', $seeker_id);
        $this->db->where('document_types.id', $document_id);
  
        $results = $this->db->get()->result();

        $data = [];
        
        $documents = [];

        foreach ($results as $row) {
            $documents[] = [
                'file_url' => file_url($row->document_path)
            ]; 
        }

        return $documents;
    }
}
