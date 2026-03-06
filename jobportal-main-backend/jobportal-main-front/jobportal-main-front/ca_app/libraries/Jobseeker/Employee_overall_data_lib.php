<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employee_overall_data_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function migrate($document_numbers = [])
    {  
        $this->load->model('Job_seeker');

        $time = time();
        $employee_doc_numbers = [];

        if (count($document_numbers) > 0) {
            $this->db->select([
                'document_number'
            ]);
            $this->db->from('tbl_employee_overall_data');
            $this->db->where_in('document_number', $document_numbers);
            $this->db->where('life_time>=', $time);
            
            $results = $this->db->get()->result();

            foreach ($results as $employee) {
                $employee_doc_numbers[] = $employee->document_number;
            }
        }

        $doc_numbers_not_found = array_diff($document_numbers, $employee_doc_numbers);

        if (count($doc_numbers_not_found) == 0) {
            return;
        }

        //dd($doc_numbers_not_found);
        //Refrescar la data de trabajadores
        $this->refresh_data($doc_numbers_not_found);
    }

    private function refresh_data($document_numbers)
    {
        //Obtener Info Empleados de Overall
        $employee_summary_info = $this->get_employee_summary_info($document_numbers);
    
        foreach ($document_numbers as $doc_number) {

            $sumary_info = $employee_summary_info[$doc_number] ?? null;

            $working_days = $sumary_info ? $sumary_info->dias_en_overall : 0;
            $blacklisted = $sumary_info ? $sumary_info->blacklist : 0;
            $blacklist_detail = $sumary_info ? trim((string)$sumary_info->blacklist_observacion) : '';
            $status = $sumary_info ? $sumary_info->estado : 'N';
            $life_time = time() + 43200;

            $employee_data[$doc_number]['document_number'] = $doc_number;
            $employee_data[$doc_number]['overall_accumulated_working_time_days'] = $working_days;
            $employee_data[$doc_number]['blacklisted'] = $blacklisted;
            $employee_data[$doc_number]['blacklist_detail'] = $blacklist_detail;
            $employee_data[$doc_number]['status'] = $status;
            $employee_data[$doc_number]['life_time'] = $life_time;
        }

        $this->db->select([
            'document_number'
        ]);
        $this->db->from('tbl_employee_overall_data');
        $this->db->where_in('document_number', $document_numbers);        
        $result_document_numbers = $this->db->get()->result_array();

        $list_document_numbers = array_column($result_document_numbers, 'document_number');

        $inserts = [];
        $updates = [];

        foreach ($employee_data as $employee_data) {
            if ($employee_data['document_number'] && in_array($employee_data['document_number'], $list_document_numbers)) {
                $updates[] = $employee_data;
            } else {
                $inserts[] = $employee_data;
            }
        }

        //Registar data
        if (count($inserts) > 0) {
            $this->db->insert_batch('tbl_employee_overall_data', $inserts);
        }
        
        //Actualizar existente
        if (count($updates) > 0) {
            $this->db->update_batch('tbl_employee_overall_data', $updates, 'document_number');
        }
    }

    private function get_employee_summary_info($document_numbers)
    {
        $employee_summary_info = $this->Job_seeker->get_overall_summary_data($document_numbers);

        $data = [];

        foreach ($employee_summary_info as $row) {
            $data[$row->documento_identidad_numero] = $row; 
        }

        return $data;
    }
}
