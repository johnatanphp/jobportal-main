<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Postulaciones extends REST_Controller
{
    public function index_get()
    {
        $job_seeker_id = $this->get('postulante_id');

        $this->db->select([
            'applied_job.ID AS id',
            'applied_job.dated AS fecha_postulacion',
            'posted_jobs.ID AS empleo_id',
            'posted_jobs.job_title AS empleo_nombre',
            //posted_jobs.dated AS fecha_registro',
            //'posted_jobs.sts AS estado',
            //'posted_jobs.country AS pais',
            //'posted_jobs.city AS ciudad',
            //'posted_jobs.last_date AS fecha_finalizacion',
            //'posted_jobs.qualification AS educacion',
            //'(case posted_jobs.experience WHEN "fresh" THEN "Sin experiencia" WHEN "<1" THEN "Menos de 1 año" ELSE posted_jobs.experience END) AS experiencia',
            //'(case posted_jobs.job_mode WHEN "full_time" THEN "Full-Time" WHEN "part_time" THEN "Part-Time" WHEN "per_hours" THEN "Por horas" WHEN "weekends" THEN "Fines de semana" WHEN "telecommuting" THEN "Teletrabajo" ELSE NULL END) AS jornada_laboral',
            //'IF(posted_jobs.payment_currency != "", CONCAT(posted_jobs.payment_currency, " ", posted_jobs.minimum_payment, " - ", posted_jobs.maximum_payment), NULL) AS pago',
            //'posted_jobs.payment_currency AS moneda_salario',
            //'posted_jobs.minimum_payment AS salario_minimo',
            //'posted_jobs.maximum_payment AS salario_maximo',
            //'posted_jobs.vacancies AS vacantes',
            //'posted_jobs.job_description AS descripcion',
            //'posted_jobs.required_skills AS habilidades_requeridas',
            //'posted_jobs.laboral_benefits AS beneficios', 
            //'industry.industry_name AS area',
            //'employer.email AS empleador_email',
            //'employer.first_name AS empleador_nombre',
            'company.ID AS empresa_id',
            'company.company_name AS empresa_nombre'
        ]);

        $this->db->from('tbl_seeker_applied_for_job applied_job');
        $this->db->join('tbl_post_jobs posted_jobs', 'applied_job.job_ID=posted_jobs.ID');
        $this->db->join('tbl_companies company', 'company.ID=posted_jobs.company_ID');
        //$this->db->join('tbl_employers employer', 'employer.ID=posted_jobs.employer_ID');
        //$this->db->join('tbl_job_industries industry', 'industry.ID=posted_jobs.industry_ID');

        $this->db->where('applied_job.seeker_ID', $job_seeker_id);

        $result_applications = $this->db->get()->result();
    
        $this->response([
            'status' => true,
            'message' => 'ok',
            'data' => [
                'postulaciones' => $result_applications
            ]
        ], 200);
    }

    public function eliminar_post()
    {
        $applied_id = $this->post('postulacion_id');

        $this->db->from('tbl_seeker_applied_for_job');
        $this->db->where('ID', $applied_id);
        $applied_job = $this->db->get()->row();

        if (!$applied_job) {
            $this->response([
                'status' => false,
                'message' => 'Postulación no encontrada',
                'data' => []
            ], 200);
            return;
        }

        $status = $this->Applied_jobs->delete_applied_job_by_id_seeker_id($applied_job->ID, $applied_job->seeker_ID);

        if (!$status) {
            $this->response([
                'status' => false,
                'message' => 'No se pudo quitar la postulación',
                'data' => []
            ], 200);
            return;
        }

        $this->response([
            'status' => true,
            'message' => 'Postulación eliminada',
            'data' => []
        ], 200);
    }

    public function aplicar_post()
    {
        $job_seeker_id = (int)$this->post('postulante_id');
        $job_id = (int)$this->post('empleo_id');

        $job_seeker = $this->Job_seeker->get_job_seeker_by_id($job_seeker_id);
    
        if (!$job_seeker) {
            $this->response([
                'status' => false,
                'message' => 'Postulante id no existe', 
                'data' => []
            ], 200);
            exit;
        }

        $job = $this->Posted_job->get_active_posted_job_by_id($job_id);
        
        if (!$job) {
            $this->response([
                'status' => false,
                'message' => 'Empleo id no existe o está inactivo',
                'data' => []
            ], 200);
            exit;
        }
        
        $is_already_applied = $this->Applied_jobs->count_applied_job_by_seeker_and_job_id($job_seeker_id, $job_id);
        
        if ($is_already_applied > 0){
            $this->response([
                'status' => false,
                'message' => 'Ya el postulante aplicó a este empleo',
                'data' => [] 
            ], 200);
        }

        $dated = date('Y-m-d H:i:s');

        $data_applied = [
            'dated' => $dated,
            'seeker_ID' => $job_seeker_id,
            'job_ID' => $job_id,
            'employer_ID' => $job->employer_ID
        ];

        $job_answers = [];

        $applied_id = $this->Applied_jobs->add_applied_job($data_applied, $job_answers);

        if ($applied_id) {
            $this->response([
                'status' => true,
                'message' => 'Postulación realizada',
                'data' => [
                    'postulacion_id' => trim($applied_id)
                ]
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No se ha podido aplicar al empleo', 
                'data' => []
            ], 200);
        }
    }
}
