<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Candidate_hiring_documents_service
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

    private function validate($params)
    {
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('identification_document_type_id', 'identification_document_type_id', 'required|in_list[1,4,7,12,26]');
        $this->form_validation->set_rules('identification_document_number', 'identification_document_number', 'required|integer|greater_than[0]');
   
        $this->form_validation->set_message('required', 'El campo %s es requerido');

        $form_is_success = $this->form_validation->run();
        $message_error = $this->form_validation->error_array();

        if (!$form_is_success && current($message_error) === false) {
            return [false, 'Debe indicar algún filtro para realizar la búsqueda'];
        }

        if (!$form_is_success) {  
           return [false, current($message_error)];
        }

        return [true, 'Ok'];
    }

    public function search_documents($params)
    {
        $doc_identification_type = $params['identification_document_type_id'];
        $doc_identification_number = $params['identification_document_number'];

        $this->db->select('ID AS id');
        $this->db->from('tbl_job_seekers');
        $this->db->where('document_type', $doc_identification_type);
        $this->db->where('document_number', $doc_identification_number);
        $candidate = $this->db->get()->row();

        if (!$candidate) {
             return [
                'status' => true,
                'message' => 'Candidato no fue encontrado',
                'data' => []
            ];
        }

        $this->db->select('ID AS id');
        $this->db->from('tbl_seeker_form_rtps form_rtps');
        $this->db->where('form_rtps.seeker_ID', $candidate->id);
        $this->db->order_by('form_rtps.ID', 'DESC');
        $form_rtps = $this->db->get()->row();

        $data_doc['identity_document'] = $this->identification_documents($candidate->id);
        $data_doc['form_rtps'] = $this->form_rtps($candidate->id);
        $data_doc['work_experience'] = $this->work_experiences($candidate->id);
        $data_doc['studies'] = $this->studies($candidate->id);
        $data_doc['spouse'] = $this->spouse_data($form_rtps ? $form_rtps->id : null);
        $data_doc['rightful_claimants'] = $this->rightful_claimants($form_rtps ? $form_rtps->id : null);
        $data_doc['other_contract_documents'] = $this->recruitment_contract_documents($candidate->id);

        $seeker_documents = $this->build_data_seeker_documents($candidate->id, $data_doc);

        return [
            'status' => true,
            'message' => 'Ok',
            'data' => $seeker_documents
        ];
    }

    private function build_data_seeker_documents($candidate_id, $candidate_documents)
    {   
        $this->db->select([
            'document_type',
            'document_number',
            'first_name',
            'last_name',
            'employee_code'
        ]);

        $this->db->from('tbl_job_seekers');
        $this->db->where('id', $candidate_id);

        $seeker = $this->db->get()->row();
        
        $candidate_data = [
            'identification_document_type' => document_type_abbr($seeker->document_type),
            'identification_document_number' => $seeker->document_number,
            'first_name' => $seeker->first_name,
            'last_name' => $seeker->last_name,
        ];
        
        return array_merge($candidate_data, $candidate_documents);
    }

    private function identification_documents($seeker_id)
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
        $this->db->where('seekers.ID', $seeker_id);

        $document = $this->db->get()->row();
       
        if (!$document) {
            return [];
        }

        $data[] = [
            'type' => $document->doc_abbreviation,
            'document_url' => file_url($document->path)
        ];
        
        return $data;
    }

    private function form_rtps($seeker_id)
    {
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
        $this->db->order_by('form_rtps.ID', 'DESC');

        $form_rtps = $this->db->get()->row();

        if (!$form_rtps) {
            return [];
        }
    
        $data[] = [
            'document_url' => site_url('general/jobseeker/form_rtps/view/' . $this->custom_encryption->encrypt_data($form_rtps->id, 1)),
            'signature_status' => 'EN ESPERA',
        ];
        
        return $data;
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
                'certificate_document_url' => $row->attached_certificate ? file_url($row->attached_certificate) : '',
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
                'certificate_document_url' => $row->attached_certificate ? file_url($row->attached_certificate) : '',
            ];
        }

        return $data;
    }

    private function spouse_data($form_rtps_id)
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
        
        $spouse = $this->db->get()->row();
        $data = [];

        if ($spouse) {
            $data = [
                'identification_document_type' => document_type_abbr($spouse->rc_document_type),
                'identification_document_number' => $spouse->rc_document_number,
                'first_name' => $spouse->rc_first_name,
                'last_name' => $spouse->rc_last_name,
                'gender' => gender_text($spouse->rc_gender),
                'kinship' => $spouse->rc_kinship_name,
                'identification_document_url' => file_url($spouse->rc_attached_document_number),
            ];
        }

        return $data;
    }

    private function rightful_claimants($form_rtps_id)
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
                'identification_document_type' => document_type_abbr($row->rc_document_type),
                'identification_document_number' => $row->rc_document_number,
                'first_name' => $row->rc_first_name,
                'last_name' => $row->rc_last_name,
                'gender' => gender_text($row->rc_gender),
                'kinship' => $row->rc_kinship_name,
                'identification_document_url' => file_url($row->rc_attached_document_number),
            ];
        }

        return $data;
    }

    private function recruitment_contract_documents($seeker_id)
    {
        $this->db->select([
            'contract_documents.file_path AS document_path',
            'document_types.id AS document_type_id',
            'document_types.name AS document_type_name'
        ]);
        $this->db->from('tbl_recruitment_contract_documents contract_documents');
        $this->db->join('tbl_recruitment_contract_document_types document_types', 'document_types.id=contract_documents.document_id');
        $this->db->where('contract_documents.seeker_id', $seeker_id);
        $this->db->where('document_types.active', 1);
        
        $results = $this->db->get()->result();

        $data = [];
        
        $documents = [];

        foreach ($results as $row) {
            $documents[$row->document_type_id] = [
                'id' => $row->document_type_id,
                'name' => $row->document_type_name,
              
            ]; 
            $documents[$row->document_type_id]['files'][]['url'] =  file_url($row->document_path);
        }

        foreach ($documents as $row) {
            $data[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'documents' => $row['files']
              
            ]; 
        }

        return $data;
    }
}
