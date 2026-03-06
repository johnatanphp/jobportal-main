<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Jobseeker_import
{
    private $total_registers = 0;
    private $seeker_emails = [];
    private $seeker_doc_numbers = [];
    private $seeker_entries_emails = [];

    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function header()
    {
        return 
        [
            'Nombre',
            'Apellidos',
            'Edad',
            'Documento',
            'Ubigeo',
            'Telefono',
            'Email',
            'Canal',
            'Referido por',
            'Etapa',
            'Status candidato',
            'Puesto',
            'Cliente',
            'Gerencia',
            'Selector'   
        ];
    }

    private function get_data_row($sheet, $row)
    {       
        return [
            'email' => trim(mb_strtolower((string)$sheet->getCell([7, $row])->getValue())),
            'document_type' => '1',
            'document_number' => trim((string)$sheet->getCell([4, $row])->getValue()),
            'first_name' => trim((string)$sheet->getCell([1, $row])->getValue()),
            'last_name' => trim((string)$sheet->getCell([2, $row])->getValue()),
            'mobile' => trim((string)$sheet->getCell([6, $row])->getValue()),
            'city' => trim((string)$sheet->getCell([5, $row])->getValue()),
            'recruitment_channel' => trim(mb_strtoupper((string)$sheet->getCell([8, $row])->getValue())),
            'referred_by' => trim((string)$sheet->getCell([9, $row])->getValue()),
            'recruitment_stage' => trim((string)$sheet->getCell([10, $row])->getValue()),
            'recruitment_seeker_status' => trim((string)$sheet->getCell([11, $row])->getValue()),
            'job_title' => trim((string)$sheet->getCell([12, $row])->getValue()),
            'company_account' => trim((string)$sheet->getCell([13, $row])->getValue()),
            'management' => trim((string)$sheet->getCell([14, $row])->getValue()),
            'employer_selector' => trim((string)$sheet->getCell([15, $row])->getValue())
        ];
    }  

    private function is_valid_header($sheet)
    {
        $header = $this->header();
        $count_headers = 0;

        $row_header = 1;
        
        foreach ($header as $key => $header_val) {
        
            $value = mb_strtolower(trim((string)$sheet->getCell([$key + 1, $row_header])->getValue()));
            $header_val = mb_strtolower($header_val);

            if ($header_val == $value) {
                $count_headers++;
            }
        }

        return count($header) == $count_headers;
    }

    public function import($data_input)
    {    
        $this->load->model('Entry_job_seeker');


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

        if ($num_rows < 2) {
            return [
                'status' => false,
                'message' => "El archivo cargado está vacio"
            ];
        }
            
        if ($num_rows > (int)$this->config->item('recruitment_seeker_importer_limit') + 1) {
            return [
                'status' => false,
                'message' => "El archivo excede de 200 filas"
            ];
        }

        if ($this->is_valid_header($worksheet) == false) {
            return [
                'status' => false,
                'message' => "La cabecera del archivo no es correcta"
            ];
        }

        $row_initial = 2;

        $data_success = [];

        for ($row = $row_initial; $row <= $num_rows; $row++) {

            $data = $this->get_data_row($worksheet, $row);

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

            $document_number = $data['document_number'];

            if (!is_numeric($document_number)) {
                return [
                    'status' => false,
                    'message' => "DNI $document_number no es válido en la fila " . ($row)
                ];
            }

            if (strlen($document_number) < 8) {
                return [
                    'status' => false,
                    'message' => "DNI $document_number no puede ser menor a 8 digitos en la fila " . ($row)
                ];
            }

            if (strlen($document_number) > 9) {
                return [
                    'status' => false,
                    'message' => "DNI $document_number no puede ser mayor a 9 digitos en la fila " . ($row)
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

        $create_date = date('Y-m-d H:i:s');
        $this->db->trans_start();

        foreach ($data_success as $seeker_row) {    
            if ($this->register($seeker_row)) {
                $this->total_registers = $this->total_registers + 1;
            }
        }
        
        $this->db->trans_complete();
        
        $status = $this->db->trans_status();

        if (!$status) {
            return [
                'status' => false,
                'message' => "No se pudo registrar los datos"
            ];
        }

        return [
            'status' => true,
            'message' => "Archivo ha sido importado",
            'data' => [
                'total_registers' =>  $this->total_registers
            ]
        ];
    }

    public function register($data)
    {
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

        if (!$seeker_id) {
            $seeker_insert = [];
            $seeker_insert['document_type'] = '1';
            $seeker_insert['document_number'] = $data['document_number'];
            $seeker_insert['first_name'] = $data['first_name'];
            $seeker_insert['last_name'] = $data['last_name']; 
            $seeker_insert['paternal_last_name'] = $data['last_name'];
            $seeker_insert['maternal_last_name'] = '';
            $seeker_insert['email'] = $data['email']; 
            $seeker_insert['city'] = $data['city'];
            $seeker_insert['mobile'] = $data['mobile'];
            $seeker_insert['password'] = '';
            $seeker_insert['country'] = '56'; //Peru
            $seeker_insert['nationality'] = '56'; //Peruano
            $seeker_insert['dated'] = date('Y-m-d H:i:s');
            $seeker_insert['sts'] = 'active';

            $this->db->insert('tbl_job_seekers', $seeker_insert);

            $seeker_id = $this->db->insert_id();
        
            if (!$seeker_id) {
                return false;
            }
        }

        if ($seeker_id) {
            $seeker_update = [];
            $seeker_update['city'] = $data['city'];
            $seeker_update['mobile'] = $data['mobile'];

            $this->db->where('ID', $seeker_id);
            $this->db->update('tbl_job_seekers', $seeker_update);
        }

        $job_seeker_entry = isset($this->seeker_entries_emails[$email]) ? $this->seeker_entries_emails[$email] : false; 
            
        if (!$job_seeker_entry) {
            $entry_insert = [];
            $entry_insert['email'] = $data['email'];	
            $entry_insert['document_type'] = '1';// DNI	
            $entry_insert['document_number'] = $data['document_number'];	
            $entry_insert['first_name'] = $data['first_name'];	
            $entry_insert['create_date'] = date('Y-m-d H:i:s');	
            $entry_insert['last_name'] = $data['last_name'];	
            $entry_insert['mobile'] = $data['mobile'];	
            $entry_insert['city'] = $data['city'];	
            $entry_insert['recruitment_channel'] = $data['recruitment_channel'];	
            $entry_insert['referred_by'] = $data['referred_by'];	
            $entry_insert['recruitment_stage'] = $data['recruitment_stage'];	
            $entry_insert['recruitment_seeker_status'] = $data['recruitment_seeker_status'];	
            $entry_insert['job_title'] = $data['job_title'];	
            $entry_insert['company_account'] =  $data['company_account'];
            $entry_insert['management'] = $data['management'];	
            $entry_insert['employer_selector'] = $data['employer_selector'];	
            $entry_insert['entry_from_site'] = 'otros';	
            $entry_insert['for_job_ID'] = 0;	
            $entry_insert['entry_key'] = create_token(70, uniqid($data['email'], true));	
            $entry_insert['notified'] = 1;	
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
            $entry_update['document_type'] = '1';// DNI	
            $entry_update['document_number'] = $data['document_number'];	
            $entry_update['mobile'] = $data['mobile'];
            $entry_update['city'] = $data['city'];
            $entry_update['recruitment_channel'] = $data['recruitment_channel'];
            $entry_update['referred_by'] = $data['referred_by'];
            $entry_update['recruitment_stage'] = $data['recruitment_stage'];
            $entry_update['recruitment_seeker_status'] = $data['recruitment_seeker_status'];
            $entry_update['job_title'] = $data['job_title'];
            $entry_update['company_account'] = $data['company_account'];
            $entry_update['management'] = $data['management'];
            $entry_update['employer_selector'] = $data['employer_selector'];
            $entry_update['seeker_id'] = $seeker_id;
            $entry_update['updated_at'] = date('Y-m-d H:i:s');
            $entry_update['updated_employer_id'] = $this->session->userdata('user_id');
        
            $this->db->where('id', $job_seeker_entry->id);
            $this->db->update('tbl_seeker_entries', $entry_update);
        }

        return true;
    }

    private function is_valid_email($str)
    {
        $matches = null;
        return (1 === preg_match('/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/', $str, $matches));
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
            $data[$row->document_number] = $row;
        }

        return $data;
    }
}
