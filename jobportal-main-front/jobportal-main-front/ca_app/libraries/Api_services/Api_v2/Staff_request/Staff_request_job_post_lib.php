<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staff_request_job_post_lib
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function post($params)
    {
        try { 
            $this->validate($params);
            return $this->post_job($params);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function validate($params)
    {   
        $this->form_validation->set_data($params);
        $this->form_validation->set_error_delimiters('', '');

        //Validar parametros recibidos
        $this->form_validation->set_rules('request_id', 'request_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('user_id', 'user_id', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('description', 'description', 'trim|required|max_length[30000]|min_length[15]');
        $this->form_validation->set_message('required', 'El campo %s es requerido');
       
        if ($this->form_validation->run() === FALSE) {
            $message_error = $this->form_validation->error_array();
            throw new \Exception(current($message_error), -1);
        }

        //Validar solicitud
        $staff_request = $this->Staff_request->find($params['request_id']);

        if (!$staff_request || $staff_request->request_model_id != 4) {
            throw new \Exception('EL valor de request_id es incorrecto', -1);
        }

        if ($staff_request->sts_process != '9') {
            throw new \Exception('Para poder publicar la solicitud debe estar Iniciada', -1);
        }

        //Validar fecha limite de publicacion
        $date_time_now = strtotime(date('Y-m-d'));
        $date_time_delivery = strtotime($staff_request->delivery_date);

        if ($date_time_now > $date_time_delivery) {
            throw new \Exception('La fecha de entrega de la solicitud ya expiró.', -1);
        }

        //Validar empleador que publica sea valido
        $employer = $this->Employer->find($params['user_id']);

        if (!$employer) {
            throw new \Exception('EL valor del user_id es incorrecto', -1);
        }
    }

    private function post_job($params)
    {    
        $request_id = $params['request_id'];
        $employer_id = $params['user_id'];

        $employer = $this->Employer->get_employer_by_id($employer_id);
        $job = $this->Posted_job->find(['request_ID' => $request_id]);

        $job_slug = make_job_slug($employer->company_slug, $job->job_title, $job->ID);

        $job_data = [
            'sts' => 'active',
            'job_description' => trim($params['description']),
            'job_slug' => $job_slug
        ];

        if ($job->sts != 'active') {
            $job_data['dated'] = date('Y-m-d');
            $job_data['employer_ID'] = $employer_id;
        }

        $this->db->where('request_ID', $request_id);
        $updated = $this->db->update('tbl_post_jobs', $job_data);
        
        if (!$updated) {
            return [
                'status' => false,
                'message' => 'No se pudo hacer la pulicación'
            ];
        }

        if ($this->db->trans_status() === FALSE) {
            return [
                'status' => false,
                'message' => 'Error al completar la pulicación'
            ];
        }
    
        $response_data = [
            'job_url' => site_url('jobs/' . $job_slug)
        ];

        return [
            'status' => true,
            'message' => 'La solicitud ha sido publicada',
            'data' => $response_data
        ];
    }
}
