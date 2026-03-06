<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Entry_seekers_other_site_import
{
    private $total_registers = 0;
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function header($site)
    {
        $header['otros'] = [
            'DNI', 
            'Nombres', 
            'Apellidos',   
            'Telefono',
            'Correo',
            'Fuente',
            'Fecha de nacimiento (YYYY-MM-DD)'
        ];

        $header['bumeran'] = [
            'Nombre',  
            'Apellido',    
            'Edad',    
            'Fecha de nacimiento', 
            'Nacionalidad',   
            'Género',  
            'Estado Civil',    
            'Ciudad',  
            'Lugar de residencia', 
            'Dirección',   
            'Celular', 
            'Telefono',  
            'E-Mail', 
            'Tipo Documento',  
            'Nro. Documento'
        ];

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

        $header['hiring'] = [
            'Nombre',  
            'Apellido',
            'E-mail', 
            'Localidad',   
            'Provincia',   
            'Pais',    
            'DNI', 
            'Telefono'
        ];

        return $header[$site];
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

    private function get_data_bumeran($sheet, $row)
    {       
        $gender = trim((string)$sheet->getCell([6, $row])->getValue());

        if ($gender == 'masculino') {
            $gender = '1';
        } else {
            $gender = '2';
        }

        return [
            'email' => trim((string)$sheet->getCell([13, $row])->getValue()),
            'document_type' => null,
            'document_number' => trim((string)$sheet->getCell([15, $row])->getValue()),
            'first_name' => trim((string)$sheet->getCell([1, $row])->getValue()),
            'last_name' => trim((string)$sheet->getCell([2, $row])->getValue()),
            'mobile' => trim((string)$sheet->getCell([11, $row])->getValue()),
            'gender' => $gender,
        ];
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

        $company = $this->get_company_by_process($this->params['process_id']);

        return [
            'email' => trim(mb_strtolower((string)$sheet->getCell([9, $row])->getValue())),
            'document_type' => $this->get_document_type($company ? $company->country_id : null),
            'document_number' => trim((string)$sheet->getCell([6, $row])->getValue()),
            'first_name' => trim((string)$sheet->getCell([1, $row])->getValue()),
            'last_name' => trim((string)$sheet->getCell([2, $row])->getValue()),
            'mobile' => trim((string)$sheet->getCell([8, $row])->getValue()),
            'gender' => $gender,
            'recruitment_channel' => 'COMPUTRABAJO',
            'present_address' => trim((string)$sheet->getCell([7, $row])->getValue()),
            'country_id' => $company ? $company->country_id : null
        ];
    }  

    private function get_data_hiring($sheet, $row)
    {       
        return [
            'email' => trim((string)$sheet->getCell([3, $row])->getValue()),
            'document_type' => null,
            'document_number' => trim((string)$sheet->getCell([7, $row])->getValue()),
            'first_name' => trim((string)$sheet->getCell([1, $row])->getValue()),
            'last_name' => trim((string)$sheet->getCell([2, $row])->getValue()),
            'mobile' => trim((string)$sheet->getCell([8, $row])->getValue()),
        ];
    }  

    private function get_data_others($sheet, $row)
    {       
        $dob = trim((string)$sheet->getCell([7, $row])->getValue());
        $company = $this->get_company_by_process($this->params['process_id']);

        return [
            'email' => trim(mb_strtolower((string)$sheet->getCell([5, $row])->getValue())),
            'document_type' => $this->get_document_type($company ? $company->country_id : null),
            'document_number' => trim((string)$sheet->getCell([1, $row])->getValue()),
            'first_name' => trim((string)$sheet->getCell([2, $row])->getValue()),
            'last_name' => trim((string)$sheet->getCell([3, $row])->getValue()),
            'mobile' => trim((string)$sheet->getCell([4, $row])->getValue()),
            'dob' => $dob != '' ? $this->getValueDate($dob) : '',
            'recruitment_channel' => trim(mb_strtoupper((string)$sheet->getCell([6, $row])->getValue())),
            'country_id' => $company ? $company->country_id : null
        ];
    }  

    private function get_data_row($sheet, $row, $from_site)
    {
        $data = [];

        if ($from_site == 'computrabajo') {
            $data = $this->get_data_computrabajo($sheet, $row);
        }

        if ($from_site == 'otros') {
            $data = $this->get_data_others($sheet, $row);
        }

        // if ($from_site == 'hiring') {
        //     $data = $this->get_data_hiring($sheet, $row);
        // }

        // if ($from_site == 'bumeran') {
        //     $data = $this->get_data_bumeran($sheet, $row);
        // }

        return $data;
    }

    public function import($data_input)
    {
        $this->params = $data_input;

        $this->load->model('Recruitment_process');
        $this->load->model('Recruitment_candidate');

        $file = $_FILES['file_import'];

        $file_info = pathinfo($file['name']);
        
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
                'message' => "El archivo tiene un formato incorrecto"
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

        $row_initial = 2;

        $data_success = [];

        for ($row = $row_initial; $row <= $num_rows; $row++) {

            $data = $this->get_data_row($worksheet, $row, $from_site);

            //dd($data);

            if (empty($data) || 
                empty($data['email'])  || 
                empty($data['first_name']) || 
                empty($data['last_name'])) {
                continue;
            }

            $email = trim($data['email']);
            
            if (!$this->is_valid_email($email)) {
                return [
                    'status' => false,
                    'message' => "Email $email no es válido en la fila " . ($row)
                ];
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
            //     return [
            //         'status' => false,
            //         'message' => "DNI $document_number no es válido en la fila " . ($row)
            //     ];
            // }

            if (strlen($document_number) < 8) {
                return [
                    'status' => false,
                    'message' => "El documento de identidad $document_number no puede ser menor a 8 digitos en la fila " . ($row)
                ];
            }

            // if (strlen($document_number) > 9) {
            //     return [
            //         'status' => false,
            //         'message' => "DNI $document_number no puede ser mayor a 9 digitos en la fila " . ($row)
            //     ];
            // }

            if (isset($data['dob']) && 
                $data['dob'] != '' &&
                date('Y-m-d', strtotime($data['dob'])) != $data['dob']) {            
                return [
                    'status' => false,
                    'message' => "La fecha de nacimiento es incorrecta en la fila " . ($row)
                ];
            }

            if (isset($data['gender']) && !empty($data['gender']) && $data['gender'] != '1' && $data['gender'] != '2') {            
                return [
                    'status' => false,
                    'message' => "Géneros es incorrecto en la fila " . ($row) . ". Géneros permitidos: Hombre y Mujer."
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
                    'message' => "La Fuente es incorrecta en la fila " . ($row) . ". Fuentes permitidas: " . join(', ', $list_channels) . "."
                ];
            }

            $data_success[$email] = $data;
        }

        $data_emails = [];

        if (count($data_success) == 0) {
            return [
                'status' => false,
                'message' => "No hay registros para procesar, por favor ponerse en contacto con los administradores del portal, para una pronta solución por favor enviar la plantilla con los datos que fue utilizada."
            ];
        }

        foreach ($data_success as $data_row) {
            $data_emails[$data_row['email']] = $data_row['email'];
        }

        $data_doc_numbers = [];

        foreach ($data_success as $data_row) {
            $data_doc_numbers[$data_row['document_number']] = $data_row['document_number'];
        }

        $this->seeker_emails = $this->jobseeker_search_emails($data_emails);
        $this->seeker_doc_numbers = $this->jobseeker_search_document_numbers($data_doc_numbers);
        $this->seeker_entries_emails = $this->seeker_entries_search_emails($data_emails);

        $this->db->trans_start();

        $create_date = date('Y-m-d H:i:s');
        $add_rys_process = isset($data_input['add_rys_process']) ? true : false;
        $process_id = $data_input['process_id'];
        $process = $this->Recruitment_process->find($process_id);

        foreach ($data_success as $seeker_row) {

            $seeker_row['entry_from_site'] = $from_site;
            $seeker_row['for_job_ID'] = $process->job_ID;
            $seeker_row['process_id'] = $process_id;
            $seeker_row['notify_by_mail'] = $data_input['notify_candidate_by_mail'] ?? 0;
            $seeker_row['notify_by_whatsapp'] = $data_input['notify_candidate_by_whatsapp'] ?? 0;

            if ($this->register($seeker_row, $add_rys_process)) {
                $this->total_registers = $this->total_registers + 1;
            }
        }

        $this->Recruitment_process->update_process_stage($process_id);

        $this->db->trans_complete();
        
        $status = $this->db->trans_status();

        if (!$status) {
            return [
                'status' => false,
                'message' => "No se pudo registrar los datos, por favor ponerse en contacto con los administradores del portal, para una pronta solución por favor enviar la plantilla con los datos que fue utilizada."
            ];
        }

        return [
            'status' => true,
            'message' => "Importación realizada con éxito",
            'data' => [
                'total_registers' =>  $this->total_registers
            ]
        ];

        return true;
    }

    public function register($data, $add_rys_process = false)
    {
        $email = $data['email'];
        $document_type = $data['document_type'];
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
        
        $full_mobile_phone_number = phone_number_format((string)$data['mobile']) ? phone_number_format((string)$data['mobile']) : '';

        if (!$seeker_id) {

            $seeker_insert = [];
            $seeker_insert['email'] = $data['email']; 
            $seeker_insert['document_type'] = $document_type;
            $seeker_insert['document_number'] = $data['document_number'];
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
            $entry_insert['email'] = $data['email'];	
            $entry_insert['document_type'] = $document_type;	
            $entry_insert['document_number'] = $data['document_number'];	
            $entry_insert['first_name'] = $data['first_name'];	
            $entry_insert['create_date'] = date('Y-m-d H:i:s');	
            $entry_insert['last_name'] = $data['last_name'];	
            $entry_insert['mobile'] = $full_mobile_phone_number;	
            $entry_insert['recruitment_channel'] = $data['recruitment_channel'];	
            $entry_insert['entry_from_site'] = $data['entry_from_site'];	
            $entry_insert['for_job_ID'] = $data['for_job_ID'];	
            $entry_insert['entry_key'] = create_token(70, uniqid($data['email'], true));	
            $entry_insert['notified'] = 0;
            $entry_insert['notify_by_whatsapp'] = $data['notify_by_whatsapp'];	
            $entry_insert['notify_by_mail'] = $data['notify_by_mail'];		
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
            $entry_update['document_number'] = $data['document_number'];	
            $entry_update['recruitment_channel'] = $data['recruitment_channel'];
            $entry_update['seeker_id'] = $seeker_id;
            $entry_update['updated_at'] = date('Y-m-d H:i:s');
            $entry_update['updated_employer_id'] = $this->session->userdata('user_id');

            $this->db->where('id', $job_seeker_entry->id);
            $this->db->update('tbl_seeker_entries', $entry_update);
        }

        if ($add_rys_process) {
            $this->add_candidate_to_stage(
                $data['process_id'],
                $data['for_job_ID'], 
                $seeker_id, 
                0
            );
        }

        return true;
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

        return $data;
    }

    public function add_candidate_to_stage($process_id, $job_id, $candidate_id, $stage = 0)
    {
        if ($this->Recruitment_candidate->exist_candidate_in_process($process_id, $candidate_id)) {
            return;
        }

        $this->db->insert('tbl_recruitment_candidates', [
            'process_id' => $process_id,
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'stage' => $stage,
            'creation_date' => date('Y-m-d')
        ]);
       
        $this->db->delete('tbl_recruitment_log_candidate_stage', [
            'job_ID' => $job_id,
            'seeker_ID' => $candidate_id,
            'stage' => $stage
        ]);
        
        $this->db->insert('tbl_recruitment_log_candidate_stage', [
            'seeker_ID' => $candidate_id,
            'job_ID' => $job_id,
            'stage' => $stage,
            'datetime' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_document_type($country_id)
    {
        $country_document_types = [
            '56' => '1',
            '49' => '27'
        ];

        return isset($country_document_types[$country_id]) ? $country_document_types[$country_id] : null;
    }

    private function get_company_by_process($process_id)
    {
        $process = $this->Recruitment_process->find($process_id);
        $job_id = $process->job_ID;
        $job = $this->Posted_job->find($job_id);

        return $this->Company->find($job->company_ID);
    }
}
