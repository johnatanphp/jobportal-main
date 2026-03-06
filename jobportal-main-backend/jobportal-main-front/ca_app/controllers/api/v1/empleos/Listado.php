<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Listado extends REST_Controller
{
    public function index_get()
    {
        $filters = [
            'job_id' => $this->get('empleo_id'),
            'job_title' => $this->get('empleo_nombre'),
            'job_city' => $this->get('empleo_ciudad'),
        ];

        $this->db->select([
            'posted_jobs.ID AS id',
            'posted_jobs.job_title AS nombre',
            'posted_jobs.dated AS fecha_registro',
            '(case posted_jobs.sts WHEN "active" THEN "activo" WHEN "inactive" THEN "inactivo" WHEN "blocked" THEN "bloqueado" WHEN "pending" THEN "pendiente" ELSE NULL END) AS estado',
            'posted_jobs.country AS pais',
            'posted_jobs.city AS ciudad',
            'posted_jobs.last_date AS fecha_finalizacion',
            'posted_jobs.qualification AS educacion',
            'experiences.name AS experiencia',
            '(case posted_jobs.job_mode WHEN "full_time" THEN "Full-Time" WHEN "part_time" THEN "Part-Time" WHEN "per_hours" THEN "Por horas" WHEN "weekends" THEN "Fines de semana" WHEN "telecommuting" THEN "Teletrabajo" ELSE NULL END) AS jornada_laboral',
            'IF(posted_jobs.payment_currency != "", CONCAT(posted_jobs.payment_currency, " ", posted_jobs.minimum_payment, " - ", posted_jobs.maximum_payment), NULL) AS pago',
            'posted_jobs.payment_currency AS moneda_salario',
            'posted_jobs.minimum_payment AS salario_minimo',
            'posted_jobs.maximum_payment AS salario_maximo',
            'posted_jobs.vacancies AS vacantes',
            'posted_jobs.job_description AS descripcion',
            'posted_jobs.required_skills AS habilidades_requeridas',
            'posted_jobs.laboral_benefits AS beneficios',
            'CONCAT("' . site_url('jobs/') . '", "", posted_jobs.job_slug) AS url', 
            'industry.industry_name AS area',
            'company.ID AS empresa_id',
            'company.company_name AS empresa_nombre',
            'company.company_logo AS empresa_logo'
        ]);

        $this->db->from('tbl_post_jobs posted_jobs');
        $this->db->join('tbl_companies company', 'company.ID=posted_jobs.company_ID');
        $this->db->join('tbl_job_industries industry', 'industry.ID=posted_jobs.industry_ID');
        $this->db->join('tbl_work_experiences experiences', 'experiences.code=posted_jobs.experience');

        $this->db->where('company.sts', 'active');
        $this->db->where('posted_jobs.sts', 'active');
        $this->db->where('last_date>', date('Y-m-d'));
    
        $job_id = isset($filters['job_id']) ? trim($filters['job_id']) : '';
        
        if ($job_id != '') {
            $this->db->where('posted_jobs.ID', $job_id);
        }

        $job_title = isset($filters['job_title']) ? trim($filters['job_title']) : '';
        
        if ($job_title != '' && strlen($job_title) > 2) {
            $this->db->like('posted_jobs.job_title', $this->db->escape_like_str($job_title), 'both');
        }

        $job_city = isset($filters['job_city']) ? trim($filters['job_city']) : '';

        if ($job_city != '' && strlen($job_city) > 2) {
            $this->db->like('posted_jobs.city', $this->db->escape_like_str($job_city), 'both');
        }
        
        $this->db->order_by('posted_jobs.ID', 'DESC');

        $result_posted_jobs = $this->db->get()->result();

        $jobs = [];

        foreach ($result_posted_jobs as $row) {

            $job_row = [
                "id" => $row->id,
                "nombre" => $row->nombre,
                "fecha_registro" => $row->fecha_registro,
                "estado" => $row->estado,
                "pais" => $row->pais,
                "ciudad" => $row->ciudad,
                "fecha_finalizacion" => $row->fecha_finalizacion,
                "educacion" => $row->educacion,
                "experiencia" => $row->experiencia,
                "jornada_laboral" => $row->jornada_laboral,
                "pago" => $row->pago,
                "moneda_salario" => $row->moneda_salario,
                "salario_minimo" => $row->salario_minimo,
                "salario_maximo" => $row->salario_maximo,
                "vacantes" => $row->vacantes,
                "descripcion" => $row->descripcion,
                "habilidades_requeridas" => $row->habilidades_requeridas,
                "beneficios" => $row->beneficios,
                "url" => $row->url,
                "area" => $row->area,
                "empresa_id" => $row->empresa_id,
                "empresa_nombre" => $row->empresa_nombre,
                "empresa_logo" => img_pic_company($row->empresa_logo, 'thumb')
            ];

            $jobs[] = $job_row;
        }
    
        $this->response([
            'status' => true,
            'message' => 'ok',
            'data' => $jobs
        ], 200);
    }
}
