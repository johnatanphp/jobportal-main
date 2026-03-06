<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Rrhh_assignment_update_lib
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
            return apiv2_response(
                false, 
                $message
            );
        }

        return $this->update_assignments($input_data);
    }

    private function validate($input_data)
    {
        $this->form_validation->set_data($input_data);
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('request_id', 'request_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('employer_id[]', 'employer_id[]', 'required');
        $this->form_validation->set_rules('send_email_rrhh', 'send_email_rrhh', 'in_list[1,0]');

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

        $job = $this->Posted_job->find([
            'request_ID' => $request_id
        ]);

        if (!$job) {
            return [false, 'La solicitud no tiene un job_id válido'];
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

        $job = $this->Posted_job->find([
            'request_ID' => $request_id
        ]);

        $this->db->trans_start();

        $this->db->where('job_ID', $job->ID);
        $this->db->delete('tbl_recruitment_rrhh_assignments');
        
        $rrhh_employers = $input_data['employer_id'];

        $date = date('Y-m-d H:i:s');

        foreach ($rrhh_employers as $rrhh_employer_id) {

            $this->db->insert('tbl_recruitment_rrhh_assignments', [
                'job_ID' => $job->ID,
                'rrhh_user_ID' => $rrhh_employer_id,
                'date_assignment' => $date,
                'manual' => 1
            ]);
        }

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        if (!$trans_status) {
            return apiv2_response(
                false, 
                'Error al actualizar los datos'
            );
        }

        $send_email_rrhh = $input_data['send_email_rrhh'] ?? 0;

        if ($send_email_rrhh == 1) {
            $this->send_emails($staff_request, $job);
        }

        return apiv2_response(
            true, 
            'Actualización realizada'
        );
    }

    private function send_emails($staff_request, $job)
    {
        $this->db->select([
            'employers.email'
        ]);
        $this->db->from('tbl_employers employers');   
        $this->db->join('tbl_recruitment_rrhh_assignments rrhh_assignments', 'rrhh_assignments.rrhh_user_ID=employers.ID');
        $this->db->where('rrhh_assignments.job_ID', $job->ID);
    
        $employers = $this->db->get()->result();

        $emails = [];

        foreach ($employers as $row_employer) {
            $emails[] = $row_employer->email;
        }

        if (count($emails) == 0) {
            return;
        }

        $this->load->library('Mail_template/Recruitment_process/Recruitment_process_assignment_hiring');

        $mail_vars = $this->recruitment_process_assignment_hiring->build([
            'job_title' => $staff_request->job_title,
            'url_link' => site_url('login')
        ]);

        $this->email->init();
        $this->email->to($emails);
        $this->email->subject($mail_vars['subject']);
        $this->email->message($mail_vars['content']);
        $this->email->send();
    }
}
