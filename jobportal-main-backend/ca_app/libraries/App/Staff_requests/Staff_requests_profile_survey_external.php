<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_requests_profile_survey_external
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function save($data, $request_id)
    {
        $this->load->model('Staff_request');

        $this->db->trans_start();
       
        //Editar solicitud
        $this->db->where('ID', $request_id);
        $this->db->update('tbl_staff_requests', $data['data_staff_request']);

        $data_staff_request = $data['data_staff_request'];

        //Registrar horarios de trabajos
        $this->Staff_request->add_working_hours($data['data_working_hours'], $request_id);

        //Registrar beneficios laborales
        $this->Staff_request->add_additional_benefits($data['data_additional_benefits'], $request_id);

        //Registrar conocimientos informaticos requeridos para el puesto
        $this->Staff_request->add_computing($data['data_computing'], $request_id);

        //Regisrar languajes requeridos para el puesto
        $this->Staff_request->add_languages($data['data_languages'], $request_id);
        
        //Registrar funciones requeridos para el puesto
        $this->Staff_request->add_job_functions($data['data_job_functions'], $request_id);

        //Registrar competencias laborales
        $charge_id = $data_staff_request['charge_ID'];
        $this->Staff_request->add_fixed_competences($charge_id, $request_id);

        //Registrar competencias laborales de la solicitud
        $this->Staff_request->add_additional_competences($data['data_additional_competences'], $request_id);

        $profile_survey_date = date('Y-m-d H:i:s');

        $profile_survey_logs = [
            'employer_id' => $this->session->userdata('user_id'),
            'request_id' => $request_id,
            'date' => $profile_survey_date
        ];
        $this->db->insert('tbl_staff_request_profile_survey_logs', $profile_survey_logs);

        $this->db->trans_complete();

        $trans_status = $this->db->trans_status();

        if (!$trans_status) {
            return false;
        }

        $staff_request = $this->Staff_request->find($request_id);
        $recruiter = $this->Employer->find($staff_request->recruiter_ID);
        $employer = $this->Employer->find($this->session->userdata('user_id'));

        $email_params = [
            'staff_request' => $staff_request,
            'recruiter' => $recruiter,
            'employer' => $employer,
            'profile_survey_date' => $profile_survey_date
        ];

        $config = $this->Email_drafts->email_configuration();
		$this->email->initialize($config);
		$this->email->clear(TRUE);
		$this->email->from(ADMIN_EMAIL, SITE_NAME);
		$this->email->to($recruiter->email);
		$this->email->subject('Levantamiento de Perfil realizado');
		$this->email->message(load_email_view('email/staff_requests/profile_survey_notification', $email_params));     
		$this->email->send();

        return true;
    }
}
