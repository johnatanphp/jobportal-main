<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

class Screening_batch_import
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function header($site)
    {
        $header = [
            'DNI'
        ];

        return $header[$site];
    }

    public function validate($data_input = [])
    {
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
        
        if ($num_rows > 25) {
            return [
                'status' => false,
                'message' => "El archivo excede las 25 filas permitidas"
            ];
        }

        $row_initial = 2;

        $data_success = [];
        $error_log = [];

        for ($row = $row_initial; $row <= $num_rows; $row++) {

            $data = $this->get_data_row($worksheet, $row);

            if (empty($data)) {
                continue;
            }

            if (trim((string)$data['dni'] ?? '') == '') {
                $error_log[] = "El DNI es requerido en la fila " . ($row);
            }

            $data_success[$data['dni']] = $data;
        }

        if (count($data_success) == 0 && count($error_log) == 0) {
            return [
                'status' => false,
                'message' => "No hay registros para procesar, por favor ponerse en contacto con los administradores del portal, para una pronta solución por favor enviar la plantilla con los datos que fue utilizada."
            ];
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
            $this->session->set_userdata('screening_batch_import_data', $data_success);
        }   

        return [
            'status' => true,
            'message' => 'Ok',
            'data' => [
                'total_rows' => count($data_success)
            ]
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

    private function get_data_row($sheet, $row)
    {   
        // 1 DNI	

        return [
            'dni' => trim(mb_strtolower((string)$sheet->getCell([1, $row])->getValue())),
        ];
    }  

    public function save($data_input)
    {
        $data_emails = [];
        $screening_batch_import_data =  $this->session->userdata('screening_batch_import_data') ? $this->session->userdata('screening_batch_import_data') : [];

        if (count($screening_batch_import_data) == 0) {
            return [
                'status' => false,
                'message' => "No hay datos para importar"
            ];
        }
        	
        $data_input['batch_items'] = $screening_batch_import_data;
        
        return $this->register_batch($data_input);
        
    }

    public function register_batch($data)
    {
        $this->db->trans_start();

        $batch_data = [
            'description' => $data['batch_description'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->userdata('user_id'),
            'status_id' => 1
        ];

        $this->db->insert('tbl_screening_batch', $batch_data);
        $batch_id = $this->db->insert_id();

        $batch_items = $data['batch_items'];

        foreach ($batch_items as $item) {

            $batch_data_item = [
                'batch_id' => $batch_id,
                'document_number' => $item['dni'],
                'type_id' => $data['type'],
                'type_expense' => $data['type_expense'],
                'cost_center' => $data['cost_center'],
                'job_title' => $data['job_title'],
                'eecc_code' => $data['eecc_code'],
                'cost_center_client' => $data['cost_center_client']
            ];
            $this->db->insert('tbl_screening_batch_items', $batch_data_item);
        }

        $this->db->trans_complete();
        
        $status = $this->db->trans_status();

        if ($status == false) {
            return [
                'status' => false,
                'message' => "No se pudo registrar los datos, por favor ponerse en contacto con los administradores del portal, para una pronta solución por favor enviar la plantilla con los datos que fue utilizada."
            ];
        }

        $this->session->unset_userdata('screening_batch_import_data');

        return [
            'status' => true,
            'message' => "Importación realizada con éxito",
            'data' => [
                'id' => $batch_id
            ]
        ];
    }
}
