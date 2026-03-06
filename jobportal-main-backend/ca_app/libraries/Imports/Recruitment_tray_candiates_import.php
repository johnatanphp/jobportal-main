<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Recruitment_tray_candiates_import
{
    private $total_registers = 0;

    private $total_add_process = 0;

    private $company = null;

    public function __construct()
    {
        $this->load->model('Recruitment_tray_candidate');
    }

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function header($site)
    {
        $header['computrabajo'] = [
            'Nombre',	
            'Apellidos',	
            'Edad',	
            'Estado civil',	
            'Nacionalidad',	
            'Identificación',	
            'Dirección',
            'Teléfono',	
            'Email',	
            'Género'
        ];

        return $header[$site];
    }

    public function validate($data_input)
    {
        $file = $_FILES['file_import'];

        $file_info = pathinfo($file['name']);

        $this->company = $this->get_company_by_employer_id($this->session->userdata('user_id'));

        if (!$file_info) {
            return [
                'status' => false,
                'message' => "El archivo cargado no se puede encontrar"
            ];
        }

        $allowed = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        if (!in_array($file['type'], $allowed)) {
            return [
                'status' => false,
                'message' => "El archivo tiene un formato o extensión incorrecto"
            ];
        }

        $spreadsheet = IOFactory::load($_FILES['file_import']['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $num_rows = $worksheet->getHighestRow();

        if ($num_rows <= 1) {
            return [
                'status' => false,
                'message' => "El archivo está vacio"
            ];
        }
        
        if ($num_rows > (int)$this->config->item('recruitment_seeker_importer_limit') + 1) {
            return [
                'status' => false,
                'message' => "El archivo excede las " . $this->config->item('recruitment_seeker_importer_limit') . " filas permitidas"
            ];
        }

        $from_site = trim($data_input['template']);

        if (!$this->is_valid_header($worksheet, $from_site)) {
            return [
                'status' => false,
                'message' => "La cabecera del archivo no es correcta, verifique que la plantilla seleccionada sea compatible."
            ];
        }

        $client_code = trim($data_input['client_code']);

        $row_initial = 2;

        $data_success = [];
        $error_log = [];

        for ($row = $row_initial; $row <= $num_rows; $row++) {

            $data = $this->get_data_row($worksheet, $row, $from_site);

            if (empty($data)) {
                continue;
            }

            if (trim((string)$data['first_name'] ?? '') == '') {
                $error_log[] = "El nombre es requerido en la fila " . ($row);
            }

            if (trim((string)$data['last_name'] ?? '') == '') {
                $error_log[] = "El apellido es requerido en la fila " . ($row);
            }

            $email = trim($data['email']);
            
            if (!$this->is_valid_email($email)) {
                $error_log[] = "Email $email no es válido en la fila " . ($row);
            }

            if (!isset($data['document_type'])) {
                return [
                    'status' => false,
                    'message' => "Tipo documento no esta definido en la fila " . ($row)
                ];
            }

            if (!$data['document_type']) {
                return [
                    'status' => false,
                    'message' => "Tipo documento no es valido en la fila " . ($row)
                ];
            }

            $document_number = $data['document_number'];

            // if (!is_numeric($document_number)) {
            //     $error_log[] = "DNI $document_number no es válido en la fila " . ($row);
            // }

            if (strlen($document_number) < 8) {
                $error_log[] = "DNI $document_number no puede ser menor a 8 digitos en la fila " . ($row);
            }

            // if (strlen($document_number) > 9) {
            //     $error_log[] = "DNI $document_number no puede ser mayor a 9 digitos en la fila " . ($row);
            // }

            if (isset($data['dob']) && 
                $data['dob'] != '' &&
                date('Y-m-d', strtotime($data['dob'])) != $data['dob']) {         
                $error_log[] = "La fecha de nacimiento es incorrecta en la fila " . ($row);
            }

            if (isset($data['gender']) && !empty($data['gender']) && $data['gender'] != '1' && $data['gender'] != '2') {            
                $error_log[] = "Géneros es incorrecto en la fila " . ($row) . ". Géneros permitidos: Hombre y Mujer.";
            }

            $data_success[$email] = $data;
        }

        if (count($data_success) == 0 && count($error_log) == 0) {
            return [
                'status' => false,
                'message' => "No hay registros para procesar, por favor ponerse en contacto con los administradores del portal, para una pronta solución por favor enviar la plantilla con los datos que fue utilizada."
            ];
        }

        $candidates_in_process = $this->search_candidates_in_process($client_code, $data_success);

        foreach ($candidates_in_process as $row_candidate) {
            $error_log[] = "El postulante " . $row_candidate->first_name . ' con Doc. Identidad: ' . $row_candidate->document_number . ' ya tiene un proceso abierto.';
        }

        if (count($error_log) > 0) {
            return [
                'status' => false,
                'message' => 'El archivo contiene errores',
                'data' => [
                    'errors' => $error_log
                ]
            ];
        }   

        if (count($error_log) == 0) {
            $this->session->set_userdata('candidate_import_data', $data_success);
        }   

        return [
            'status' => true,
            'message' => 'Ok'
        ];
    }

    private function is_valid_header($sheet, $site)
    {
        $header = $this->header($site);
        $count_headers = 0;

        $row_header = 1;
        
        foreach ($header as $key => $header_val) {
        
            $value = trim((string)$sheet->getCell([$key + 1, $row_header])->getValue());

            if ($header_val == $value) {
                $count_headers++;
            }
        }

        return count($header) == $count_headers;
    }

    private function get_data_computrabajo($sheet, $row)
    {   
        // 1 Nombre	
        // 2 Apellidos	
        // 3 Edad	
        // 4 Estado civil	
        // 5 Nacionalidad	
        // 6 Identificación	
        // 7 Dirección	
        // 8 Teléfono	
        // 9 Email	
        // 10 Género

        $gender = trim(mb_strtolower((string)$sheet->getCell([10, $row])->getValue()));

        if ($gender == 'mujer') {
            $gender = '2';
        }
        
        if ($gender == 'hombre') {
            $gender = '1';
        }

        if (empty($gender)) {
            $gender = null;
        }

        return [
            'email' => trim(mb_strtolower((string)$sheet->getCell([9, $row])->getValue())),
            'document_type' => $this->get_document_type($this->company->country_id),
            'document_number' => trim((string)$sheet->getCell([6, $row])->getValue()),
            'first_name' => trim((string)$sheet->getCell([1, $row])->getValue()),
            'last_name' => trim((string)$sheet->getCell([2, $row])->getValue()),
            'mobile' => trim((string)$sheet->getCell([8, $row])->getValue()),
            'gender' => $gender,
            'country_id' => $this->company->country_id,
            'present_address' => trim((string)$sheet->getCell([7, $row])->getValue())
        ];
    }  

    private function get_data_row($sheet, $row, $from_site)
    {
        $data = [];

        if ($from_site == 'computrabajo') {
            $data = $this->get_data_computrabajo($sheet, $row);
        }

        // if ($from_site == 'otros') {
        //     $data = $this->get_data_others($sheet, $row);
        // }

        // if ($from_site == 'hiring') {
        //     $data = $this->get_data_hiring($sheet, $row);
        // }

        // if ($from_site == 'bumeran') {
        //     $data = $this->get_data_bumeran($sheet, $row);
        // }

        return $data;
    }

    public function save($data_input)
    {
        $data_emails = [];
        $candidates =  $this->session->userdata('candidate_import_data') ? $this->session->userdata('candidate_import_data') : [];

        if (count($candidates) == 0) {
            return [
                'status' => false,
                'message' => "No hay datos para importar"
            ];
        }

        foreach ($candidates as $data_row) {
            $data_emails[$data_row['email']] = $data_row['email'];
        }

        $data_doc_numbers = [];

        foreach ($candidates as $data_row) {
            $data_doc_numbers[$data_row['document_number']] = $data_row['document_number'];
        }

        $this->seeker_emails = $this->jobseeker_search_emails($data_emails);
        $this->seeker_doc_numbers = $this->jobseeker_search_document_numbers($data_doc_numbers);
        $this->seeker_entries_emails = $this->seeker_entries_search_emails($data_emails);

        $this->db->trans_start();

        $client_code = trim($data_input['client_code']);
        $candidates_ids = [];
        foreach ($candidates as $candidate_row) {
            $candidate_row['entry_from_site'] = 'computrabajo';
            $candidate_row['client_code'] = $client_code;

            $candidate_id = $this->register($candidate_row);

            if (!$candidate_id) {
                continue;
            }

            $candidates_ids[] = [
                'candidate_id' => $candidate_id
            ];
        }

        $tray_candidates_ids = $this->Recruitment_tray_candidate->add_candidates($client_code, $candidates_ids);

        $this->total_registers = is_array($tray_candidates_ids) && count($tray_candidates_ids) > 0 ? count($tray_candidates_ids) : 0;

        $this->db->trans_complete();
        
        $status = $this->db->trans_status();

        if (!$status) {
            return [
                'status' => false,
                'message' => "No se pudo registrar los datos, por favor ponerse en contacto con los administradores del portal, para una pronta solución por favor enviar la plantilla con los datos utilizados."
            ];
        }

        $this->session->unset_userdata('candidate_import_data');

        return [
            'status' => true,
            'message' => "Importación realizada con éxito",
            'data' => [
                'total_registers' =>  $this->total_registers
            ]
        ];
    }

    public function register($data)
    {
        $email = $data['email'];
        $document_number = $data['document_number'];
        $document_type = $data['document_type'];
        $full_mobile_phone_number = phone_number_format((string)$data['mobile']) ? phone_number_format((string)$data['mobile']) : '';
        $seeker_id = null;

        $job_seeker_info = isset($this->seeker_emails[$email]) ? $this->seeker_emails[$email] : false; 
            
        if ($job_seeker_info) {
            $seeker_id = $job_seeker_info->id;
        }

        $seeker_doc_number = isset($this->seeker_doc_numbers[$document_number]) ? $this->seeker_doc_numbers[$document_number] : false;
        
        if ($seeker_doc_number) {
            $seeker_id = $seeker_doc_number->id;
        }
        
        if (!$seeker_id) {
            $seeker_insert = [];
            $seeker_insert['email'] = $email; 
            $seeker_insert['document_type'] = $document_type;
            $seeker_insert['document_number'] = $document_number;
            $seeker_insert['first_name'] = $data['first_name'];
            $seeker_insert['last_name'] = $data['last_name']; 
            $seeker_insert['paternal_last_name'] = $data['last_name'];
            $seeker_insert['dob'] = isset($data['dob']) && $data['dob'] != '' ? $data['dob'] : null;
            $seeker_insert['gender'] = isset($data['gender']) && $data['gender'] != '' ? $data['gender'] : null; 
            $seeker_insert['maternal_last_name'] = '';
            $seeker_insert['city'] = isset($data['city']) ? $data['city'] : ''; 
            $seeker_insert['mobile'] = $full_mobile_phone_number;
            $seeker_insert['password'] = '';
            $seeker_insert['country'] = $data['country_id'];
            $seeker_insert['dated'] = date('Y-m-d H:i:s');
            $seeker_insert['sts'] = 'active';
            $seeker_insert['present_address'] = isset($data['present_address']) ? $data['present_address'] : '';

            $this->db->insert('tbl_job_seekers', $seeker_insert);

            $seeker_id = $this->db->insert_id();
        
            if (!$seeker_id) {
                return false;
            }
        }
        
        $job_seeker_entry = isset($this->seeker_entries_emails[$email]) ? $this->seeker_entries_emails[$email] : false; 
            
        if (!$job_seeker_entry) {
            $entry_insert = [];
            $entry_insert['email'] = $email;	
            $entry_insert['document_type'] = $document_type;	
            $entry_insert['document_number'] = $document_number;	
            $entry_insert['first_name'] = $data['first_name'];	
            $entry_insert['create_date'] = date('Y-m-d H:i:s');	
            $entry_insert['last_name'] = $data['last_name'];	
            $entry_insert['mobile'] = $full_mobile_phone_number;	
            //$entry_insert['recruitment_channel'] = $data['recruitment_channel'];	
            $entry_insert['entry_from_site'] = $data['entry_from_site'];	
            //$entry_insert['for_job_ID'] = $data['for_job_ID'];	
            $entry_insert['entry_key'] = create_token(70, uniqid($data['email'], true));	
            $entry_insert['notified'] = 1;	//OJO
            $entry_insert['activated'] = 1;	
            $entry_insert['seeker_id'] = $seeker_id;	
            $entry_insert['created_employer_id'] = $this->session->userdata('user_id');	

            $this->db->insert('tbl_seeker_entries', $entry_insert);
            $id = $this->db->insert_id();

            if (!$id) {
                return false;
            }
        }

        if ($job_seeker_entry) {
            $entry_update = [];
            $entry_update['document_type'] = $document_type;	
            $entry_update['document_number'] = $document_number;	
            $entry_update['seeker_id'] = $seeker_id;
            $entry_update['updated_at'] = date('Y-m-d H:i:s');
            $entry_update['updated_employer_id'] = $this->session->userdata('user_id');

            $this->db->where('id', $job_seeker_entry->id);
            $this->db->update('tbl_seeker_entries', $entry_update);
        }

        return $seeker_id;
    }

    private function is_valid_email($str)
    {
        $matches = null;
        return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
    }

    private function getValueDate($value)
    {
        if (is_numeric($value)) {
            $datetime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            return $datetime ? $datetime->format('Y-m-d') : '';
        }
        
        return $value;
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
        return [];
        // $this->db->select([
        //     'ID AS id',
        //     'email AS email',
        //     'document_type',
        //     'document_number'
        // ]);
        // $this->db->from('tbl_job_seekers');
        // $this->db->where('document_type', '1');
        // $this->db->where_in('document_number', $doc_numbers);

        // $results = $this->db->get()->result();

        // $data = [];

        // foreach ($results as $row) {
        //     $data[$row->document_number] = $row;
        // }

        // return $data;
    }

    private function search_candidates_in_process($client_code, $data)
    {
        $document_numbers = [];
        foreach ($data as $data_row) {
            $document_numbers[] = $data_row['document_number'];
        }

        $emails = [];
        foreach ($data as $data_row) {
            $emails[] = $data_row['email'];
        }

        $this->db->select([
            'candidates.document_number AS document_number',
            'candidates.first_name AS first_name',
            'candidates.last_name AS last_name'
        ]);
        $this->db->from('tbl_recruitment_tray_candidates tray_candidates');
        $this->db->join('tbl_recruitment_process rc_process', 'rc_process.id=tray_candidates.process_id');
        $this->db->join('tbl_job_seekers candidates', 'candidates.ID=tray_candidates.seeker_id');
        $this->db->where('tray_candidates.status_id!=', 3);
        $this->db->where('rc_process.tray_type_id', 3);
        $this->db->where('tray_candidates.client_code', $client_code);
        $this->db->where('tray_candidates.company_id', 1);

        $this->db->group_start();
        $this->db->where_in('candidates.email', $emails);
        $this->db->or_where_in('candidates.document_number', $document_numbers);
        $this->db->group_end();

        return $this->db->get()->result();
    }

    public function get_document_type($country_id)
    {
        $country_document_types = [
            '56' => '1',
            '49' => '27'
        ];

        return isset($country_document_types[$country_id]) ? $country_document_types[$country_id] : null;
    }

    private function get_company_by_employer_id($employer_user_id)
    {
        $employer = $this->Employer->find($employer_user_id);
        return $this->Company->find($employer->company_ID);
    }
}
