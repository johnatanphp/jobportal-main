<?php
require_once ("App_console.php");

class Register_jobseeker_entries extends App_console  
{
    public function __construct()
    {
        parent::__construct();  
    }

    public function run()
    {
        if (!$this->config->item('seeker_entries_import_tmp_limit')) {
            return;
        }

        $this->db->select('*');
        $this->db->from('tbl_seeker_entries_import_tmp');
        $this->db->where('register', 0);
        $this->db->where('error_log', null);
        
        $this->db->limit($this->config->item('seeker_entries_import_tmp_limit'));

        $results = $this->db->get()->result_array();

        $data_success = [];

        foreach ($results as $data_row) {

            $data = $this->validate_data($data_row);

            if ($data['status'] == false) {
                $this->db->where('id', $data_row['id']);
                $this->db->update('tbl_seeker_entries_import_tmp', [
                    'error_log' => $data['message']
                ]);
                continue;
            }

            $data_row['email'] = trim(mb_strtolower($data_row['email']));
            $data_row['document_number'] = trim($data_row['document_number']);
            $data_row['first_name'] = trim($data_row['first_name']);
            $data_row['last_name'] = trim($data_row['last_name']);
            $data_row['mobile'] = trim($data_row['mobile']);
            $data_row['city'] = trim($data_row['city']);
            $data_row['recruitment_channel'] = trim($data_row['recruitment_channel']);
            $data_row['referred_by'] = trim($data_row['referred_by']);
            $data_row['recruitment_stage'] = trim($data_row['recruitment_stage']);
            $data_row['recruitment_seeker_status'] = trim($data_row['recruitment_seeker_status']);
            $data_row['job_title'] = trim($data_row['job_title']);
            $data_row['company_account'] = trim($data_row['company_account']);
            $data_row['management'] = trim($data_row['management']);
            $data_row['employer_selector'] = trim($data_row['employer_selector']);
            		
            $data_success[$data_row['email']] = $data_row;
        }

        if (count($data_success) == 0) {
            return;
        }

        $data_emails = [];
        $data_doc_numbers = [];

        foreach ($data_success as $row) {
            $data_emails[$row['email']] = $row['email'];
            $data_doc_numbers[$row['document_number']] = $row['document_number'];
        }

        $this->seeker_emails = $this->jobseeker_search_emails($data_emails);
        $this->seeker_doc_numbers = $this->jobseeker_search_document_numbers($data_doc_numbers);
        $this->seeker_entries_emails = $this->seeker_entries_search_emails($data_emails);

        foreach ($data_success as $seeker_row) {
            $register_status = $this->register($seeker_row);

            if (!$register_status) {
                $this->db->where('id', $seeker_row['id']);
                $this->db->update('tbl_seeker_entries_import_tmp', [
                    'register' => 0,
                    'error_log' => 'No se pudo registrar por favor. Error en completar la transaccion'
                ]);

                continue;
            }

            $this->db->where('id', $seeker_row['id']);
            $this->db->update('tbl_seeker_entries_import_tmp', [
                'register' => 1,
                'error_log' => null
            ]);
        }
    }

    public function register($data)
    {
        $this->db->trans_start();

        $email = $data['email'];
        $document_number = $data['document_number'];
        $seeker_id = null;

        $job_seeker_info = isset($this->seeker_emails[$email]) ? $this->seeker_emails[$email] : false; 
            
        if ($job_seeker_info) {
            $seeker_id = $job_seeker_info->id;
        }

        $seeker_doc_number = isset($this->seeker_doc_numbers[$document_number]) ? $this->seeker_doc_numbers[$document_number] : false;
        
        if ($seeker_doc_number) {
            $seeker_id = $seeker_doc_number->id;
        }

        if ($seeker_id && $data['mobile'] != '') {
            $this->db->where('ID', $seeker_id);
            $this->db->update('tbl_job_seekers', [
                'mobile' => $data['mobile']
            ]);
        }

        if (!$seeker_id) {
            $this->db->insert('tbl_job_seekers', [
                'document_type' => '1',
                'document_number' => $data['document_number'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'], 
                'paternal_last_name' => $data['last_name'],
                'maternal_last_name' => '',
                'email' => $data['email'], 
                'city' => $data['city'],
                'mobile' => $data['mobile'],
                'password' => '',
                'country' => 'Perú',
                'nationality' => 'peruano(a)',
                'dated' => date('Y-m-d H:i:s'),
                'sts' => 'active'
            ]);

            $seeker_id = $this->db->insert_id();
        
            if (!$seeker_id) {
                return false;
            }
        }

        $job_seeker_entry = isset($this->seeker_entries_emails[$email]) ? $this->seeker_entries_emails[$email] : false; 
            
        if (!$job_seeker_entry) {
            $data_insert = [
                'document_type' => '1',
                'document_number' => $data['document_number'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'mobile' => $data['mobile'],
                'city' => $data['city'],
                'recruitment_channel' => $data['recruitment_channel'],
                'referred_by' => $data['referred_by'],
                'recruitment_stage' => $data['recruitment_stage'],
                'recruitment_seeker_status' => $data['recruitment_seeker_status'],
                'job_title' => $data['job_title'],
                'company_account' => $data['company_account'],
                'management' => $data['management'],
                'employer_selector' => $data['employer_selector'],
                'seeker_id' => $seeker_id,
                'create_date' => date('Y-m-d H:i:s'),
                'created_employer_id' => null,
                'activated' => 1,
                'notified' => 1,
                'entry_key' => create_token(80, uniqid($data['email'], true)),
                'for_job_ID' => 0,
                'entry_from_site' => 'otros'
            ];
            
            $this->db->insert('tbl_seeker_entries', $data_insert);
            $id = $this->db->insert_id();

            if (!$id) {
                return false;
            }
        }

        if ($job_seeker_entry) {
            $data_update = [
                'mobile' => $data['mobile'],
                'city' => $data['city'],
                'recruitment_channel' => $data['recruitment_channel'],
                'referred_by' => $data['referred_by'],
                'recruitment_stage' => $data['recruitment_stage'],
                'recruitment_seeker_status' => $data['recruitment_seeker_status'],
                'job_title' => $data['job_title'],
                'company_account' => $data['company_account'],
                'management' => $data['management'],
                'employer_selector' => $data['employer_selector'],
                'seeker_id' => $seeker_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_employer_id' => null,
                'entry_key' => create_token(80, uniqid($data['email'], true))
            ];
            $this->db->where('id', $job_seeker_entry->id);
            $this->db->update('tbl_seeker_entries', $data_update);
        }

        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    private function validate_data($data)
    {
        if (empty($data) || 
            empty($data['email'])  || 
            empty($data['first_name']) || 
            empty($data['last_name'])) {
            return [
                'status' => false,
                'message' => "Dato incompleto"
            ];
        }

        $email = trim($data['email']);
        
        if (!$this->is_valid_email($email)) {
            return [
                'status' => false,
                'message' => "Email $email no es válido"
            ];
        }

        $document_number = $data['document_number'];

        if (!is_numeric($document_number)) {
            return [
                'status' => false,
                'message' => "DNI $document_number no es válido"
            ];
        }

        if (strlen($document_number) < 8) {
            return [
                'status' => false,
                'message' => "DNI $document_number no puede ser menor a 8 digitos"
            ];
        }

        if (strlen($document_number) > 9) {
            return [
                'status' => false,
                'message' => "DNI $document_number no puede ser mayor a 9 digitos"
            ];
        }

        $list_channels = [
            'COMPUTRABAJO',
            'BUMERAN',
            'HIRING',
            'OTROS'
        ];

        if (!in_array($data['recruitment_channel'], $list_channels)) {
            return [
                'status' => false,
                'message' => "La Fuente es incorrecta."
            ];
        }

        return [
            'status' => true
        ];
    }
    
    public function jobseeker_search_emails($emails)
    {
        $this->db->select([
            'ID AS id',
            'email AS email'
        ]);
        $this->db->from('tbl_job_seekers');
        $this->db->where_in('email', $emails);

        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[mb_strtolower($row->email)] = $row;
        }

        return $data;
    }

    public function seeker_entries_search_emails($emails)
    {
        $this->db->select([
            'ID AS id',
            'email AS email'
        ]);
        $this->db->from('tbl_seeker_entries');
        $this->db->where_in('email', $emails);

        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[mb_strtolower($row->email)] = $row;
        }

        return $data;
    }

    public function jobseeker_search_document_numbers($doc_numbers)
    {
        $this->db->select([
            'ID AS id',
            'email AS email',
            'document_type',
            'document_number'
        ]);
        $this->db->from('tbl_job_seekers');
        $this->db->where('document_type', '1');
        $this->db->where_in('document_number', $doc_numbers);

        $results = $this->db->get()->result();

        $data = [];

        foreach ($results as $row) {
            $data[trim($row->document_number)] = $row;
        }

        return $data;
    }

    private function is_valid_email($str)
    {
        $matches = null;
        return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
    }
}
