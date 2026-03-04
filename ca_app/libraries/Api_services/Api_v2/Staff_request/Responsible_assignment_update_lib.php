<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Responsible_assignment_update_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function update($request_id, $input_data)
    {
        $input_data['request_id'] = $request_id;

        list($is_success, $message) = $this->validate($input_data);

        if (!$is_success) {
            return [
                'status' => $is_success,
                'message' => $message
            ];
        }

        return $this->update_assignments($input_data);
    }

    private function validate($input_data)
    {
        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('request_id', 'request_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('employer_id[]', 'employer_id[]', 'required');

        $this->form_validation->set_message('required', 'El campo %s es requerido');

        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            return [false, current($message_error)];
        }

        $request_id = $input_data['request_id'];
        $staff_request = $this->Staff_request->find($request_id);

        if (!$staff_request) {
            return [false, 'El valor request_id no es válido'];
        }

        if (!is_array($input_data['employer_id'])) {
            return [false, 'El valor employer_id debe ser un array de datos'];
        }

        $company_id = $staff_request->company_ID;

        $this->db->select('count(ID) AS total');
        $this->db->from('tbl_employers');
        $this->db->where('company_ID', $company_id);
        $this->db->where_in('ID', $input_data['employer_id']);
        	
        $responsibles_total = $this->db->count_all_results();

        if (count($input_data['employer_id']) != $responsibles_total) {
            return [false, 'Hay valores en employer_id[] que no existen o no pertenecen a la empresa de la solicitud'];
        }

        $this->db->select('count(ID) AS total');
        $this->db->from('tbl_employers');
        $this->db->where('company_ID', $company_id);
        $this->db->where('sts', 'active');
        $this->db->where_in('ID', $input_data['employer_id']);
        	
        $responsibles_total = $this->db->count_all_results();

        if (count($input_data['employer_id']) != $responsibles_total) {
            return [false, 'Hay valores en employer_id[] que no existen o no estan activos en el sistema'];
        }

        return [true, 'OK'];
    }

    private function update_assignments($input_data)
    {
        $request_id = $input_data['request_id'];
        $staff_request = $this->Staff_request->find($request_id);

        $this->db->trans_start();

        $this->db->where('request_ID', $request_id);
        $this->db->delete('tbl_staff_request_assigned_employers');
        
        $responsibles = $input_data['employer_id'];

        $date = date('Y-m-d H:i:s');

        foreach ($responsibles as $res_employer_id) {

            $this->db->insert('tbl_staff_request_assigned_employers', [
                'request_ID' => $request_id,
                'employer_ID' => $res_employer_id,
                'date' => $date
            ]);
        }

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        if ($trans_status && isset($input_data['send_email_responsibles']) && $input_data['send_email_responsibles'] == 1) {
            $this->send_emails($staff_request);
        }
    
        return apiv2_response(
            $trans_status, 
            $trans_status ? 'Actualización realizada' : 'Error al actualizar los datos'
        );
    }

    private function send_emails($staff_request)
    {    
        $this->db->select([
            'employers.email'
        ]);
        $this->db->from('tbl_employers employers');   
        $this->db->join('tbl_staff_request_assigned_employers assigned_employers', 'assigned_employers.employer_ID=employers.ID');
        $this->db->where('assigned_employers.request_ID', $staff_request->ID);
    
        $employers = $this->db->get()->result();

        $emails = [];

        foreach ($employers as $row_employer) {
            $emails[] = $row_employer->email;
        }

        if (count($emails) == 0) {
            return;
        }

        $recruiter = $this->Employer->find($staff_request->recruiter_ID);
        
        $this->load->library('Mail_template/Recruitment_process/Recruitment_process_assignment_responsibles');

        $mail_vars = $this->recruitment_process_assignment_responsibles->build([
            'employer_name' => 'Estimado/a',
            'job_title' => $staff_request->job_title,
            'url_link' => site_url('login')
        ]);

        $this->email->init();
        $this->email->to($emails);
        
        if ($recruiter->email) {
            $this->email->cc($recruiter->email);
        }
        
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }
}
