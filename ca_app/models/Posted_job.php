<?php
class Posted_job extends CI_Model
{
    public function find($id_or_where)
    {
        $this->db->from('tbl_post_jobs');

        if (is_array($id_or_where)) {
            $this->db->where($id_or_where);
        } else {
            $this->db->where('ID', $id_or_where);
        }

        return $this->db->get()->row();
    }

    public function get_posted_job_by_id($id)
    {
       $Q = $this->db->query("CALL get_posted_job_by_id($id)");
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->next_result();
        $Q->free_result();
        return $return;
    }

	public function add_posted_job($data_post, $questions_data, $has_questions)
    {
        $return = $this->db->insert('tbl_post_jobs', $data_post);

        $job_id = $this->db->insert_id();

        if (!$job_id || !$return) {
            return false;
        }   

        if ($has_questions == 'yes') {

            foreach ($questions_data as $id => $question) {

                $name_question = $question['name'];
                $type_question = $question['type_question'];
                $required = isset($question['required']) ? 'yes' : 'no';
                $value = null;     

                if ($type_question == 'checkbox' || 
                    $type_question == 'multiple_choice' ||
                    $type_question == 'dropdown'
                ) {
                    $options = $question['option'];
                    $value = json_encode(array('options' => $options));
                }

                if ($type_question == "multiple_choice_grid") {
                    $rows = $question['rows'];
                    $columns = $question['columns'];
                    $value = json_encode(array(
                        'rows' => $rows,
                        'columns' => $columns
                    ));
                }

                $question_data = array(
                    'question' => $name_question,
                    'type_question' => $type_question,
                    'value' => $value,
                    'required' => $required,
                    'job_ID' => $job_id
                );

                $this->db->insert('tbl_job_questions', $question_data);
            }
        }

        return $job_id;       
	}	
	
    public function update_posted_job(
        $job_id, 
        $post_jobs_data, 
        $questions_data = [], 
        $has_questions = 'no')
    {    
        $this->db->where('ID', $job_id);
        $return = $this->db->update('tbl_post_jobs', $post_jobs_data);

        if (!empty($questions_data)) {
            $this->db->where('job_ID', $job_id);
            $this->db->delete('tbl_job_questions');
        }

        if ($has_questions == 'yes') {
            foreach ($questions_data as $question_id => $question) {
                $name_question = $question['name'];
                $type_question = $question['type_question'];
                $required = isset($question['required']) ? 'yes' : 'no';
                $value = null;     

                if ($type_question == 'checkbox' || 
                    $type_question == 'multiple_choice' ||
                    $type_question == 'dropdown'
                ) {
                    $options = $question['option'];
                    $value = json_encode(array('options' => $options));
                }

                if ($type_question == "multiple_choice_grid") {
                    $rows = $question['rows'];
                    $columns = $question['columns'];
                    $value = json_encode(array(
                        'rows' => $rows,
                        'columns' => $columns
                    ));
                }

                $question_data = array(
                    'ID' => $question_id > 0 ? $question_id : null,
                    'question' => $name_question,
                    'type_question' => $type_question,
                    'value' => $value,
                    'required' => $required,
                    'job_ID' => $job_id
                );

                $this->db->insert('tbl_job_questions', $question_data);    
            }
        }

        return $return;
    }
	
	public function delete_posted_job($id)
    {
		$this->db->where('ID', $id);
		$this->db->delete('tbl_post_jobs');
	}
	
	public function delete_posted_job_by_employer_id($emp_id)
    {
		$this->db->where('employer_ID', $emp_id);
		$this->db->delete('tbl_post_jobs');
	}
	
	public function delete_posted_job_by_id_emp_id($id,$emp_id)
    {
		$this->db->where('ID', $id);
		$this->db->where('employer_ID', $emp_id);
		$return = $this->db->delete('tbl_post_jobs');
		return $return;
	}
	
	public function get_all_posted_jobs($per_page, $page)
    {
		$Q = $this->db->query("CALL get_all_posted_jobs(".$page.",".$per_page.")");	
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
		
	public function get_posted_job_by_job_id($job_id)
    {
        $this->db->select('tbl_post_jobs.*, tbl_employers.email, tbl_employers.first_name');
        $this->db->from('tbl_post_jobs');
		$this->db->join('tbl_employers', 'tbl_post_jobs.employer_ID = tbl_employers.user_ID', 'inner');
        $this->db->where('tbl_post_jobs.ID', $job_id);
		$Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function get_active_posted_job_by_id($id)
    {
       $Q = $this->db->query("CALL get_active_posted_job_by_id($id)");
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function get_posted_job_by_id_employer_id($id,$employer_id)
    {
       $Q = $this->db->query("CALL get_posted_job_by_id_employer_id($id,$employer_id)");
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function get_posted_job_by_employer_ID($employer_id, $per_page, $page)
    {
       $Q = $this->db->query("CALL get_posted_job_by_employer_id($employer_id, $page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
	
	public function get_posted_job_by_company_ID($company_id, $per_page, $page)
    {
       $Q = $this->db->query("CALL get_posted_job_by_company_ID($company_id, $page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function get_all_posted_jobs_by_company_id_frontend($company_id, $per_page, $page)
    {
       $Q = $this->db->query("CALL get_all_posted_jobs_by_company_id_frontend($company_id, $page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
    public function search_all_posted_jobs($per_page, $page, $search_parameters)
    {
		$condition='';
		foreach($search_parameters as $key=>$val){
			$condition .= "$key LIKE '%$val%' AND ";
		}
		$condition = rtrim($condition,'AND ');
        $Q = $this->db->query('CALL search_posted_jobs("'.$condition.'", '.$page.', '.$per_page.')');
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
		//echo $this->db->last_query(); exit;
        return $return;
    }
	
	public function get_featured_posted_job($per_page, $page)
    {
       $Q = $this->db->query("CALL get_featured_job($page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	//Record Count methods
	public function record_count($table_name)
    {
		return $this->db->count_all($table_name);
    }
	
	public function count_records($table_name, $db_field_name, $value)
    {
		$this->db->where($db_field_name, $value);
		$this->db->from($table_name);
		return $this->db->count_all_results();
    }
	
	public function count_active_records($table_name, $db_field_name='', $value='')
    {
		if($db_field_name!='' && $value!='')
			$this->db->where($db_field_name, $value);
        $this->db->where('last_date>', date('Y-m-d'));
		$this->db->where('sts', 'active');
		$this->db->from($table_name);
		return $this->db->count_all_results();
    }

    public function count_active_posted_jobs()
    {
        $this->db->from('tbl_post_jobs');
        $this->db->join('tbl_companies', 'tbl_companies.ID=tbl_post_jobs.company_ID');
        $this->db->join('tbl_job_industries', 'tbl_job_industries.ID=tbl_post_jobs.industry_ID');
        $this->db->where('tbl_post_jobs.last_date>', date('Y-m-d'));
        $this->db->where('tbl_post_jobs.sts', 'active');
        $this->db->where('tbl_companies.sts', 'active');

        return $this->db->count_all_results();
    }
	
	public function count_opened_job_records()
    {
		$Q = $this->db->query("CALL count_active_opened_jobs()");	
		 if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
		
    }
	
	public function search_record_count($search_parameters)
    {
		$condition='';
		foreach($search_parameters as $key=>$val){
			$condition .= "$key LIKE '%$val%' AND ";
		}
		$condition = rtrim($condition,'AND ');
		$Q = $this->db->query('CALL count_search_posted_jobs("'.$condition.'")');
		if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function count_all_posted_jobs_by_company_id_frontend($company_id)
    {
       $Q = $this->db->query("CALL count_all_posted_jobs_by_company_id_frontend($company_id)");
        if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	//Specifically front end methods
	public function get_all_posted_jobs_by_status($status, $per_page, $page)
    {
		$Q = $this->db->query('CALL get_all_posted_jobs_by_status("'.$status.'",'.$page.','.$per_page.')');	
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function get_all_opened_jobs($per_page, $page)
    {    
        $today = date('Y-m-d');
        $Q = $this->db->query('CALL get_all_opened_jobs(' . $page . ',' . $per_page . ',"' . $today . '")'); 

        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        
        $Q->next_result();
        $Q->free_result();
       
        return $return;
    }

    public function get_all_opened_jobs_by_country_id($country_id, $per_page, $page)
    {    
        $today = date('Y-m-d');
        
        $this->db->select([
            'pj.ID', 
            'pj.job_title', 
            'pj.job_slug', 
            'pj.employer_ID', 
            'pj.company_ID', 
            'pj.job_description', 
            'pj.city',
            'pj.dated', 
            'pj.last_date', 
            'pj.is_featured', 
            'pj.sts', 
            'pc.company_name', 
            'pc.company_logo', 
            'pc.company_slug', 
            'ji.industry_name' 
        ]);
        $this->db->from('tbl_post_jobs pj');
        $this->db->join('tbl_companies AS pc', 'pj.company_ID=pc.ID');
        $this->db->join('tbl_job_industries AS ji', 'pj.industry_ID=ji.ID');
        $this->db->where('pc.country_id', $country_id);
        $this->db->where('pj.sts', 'active');
        $this->db->where('pc.sts', 'active');
        $this->db->where('pj.last_date>', $today);
        $this->db->order_by('pj.ID', 'DESC');

        $this->db->limit($per_page, $page);
        
        return $this->db->get()->result();
    }

    public function count_active_posted_jobs_by_country($country_id)
    {
        $this->db->from('tbl_post_jobs');
        $this->db->join('tbl_companies', 'tbl_companies.ID=tbl_post_jobs.company_ID');
        $this->db->join('tbl_job_industries', 'tbl_job_industries.ID=tbl_post_jobs.industry_ID');
        $this->db->where('tbl_post_jobs.last_date>', date('Y-m-d'));
        $this->db->where('tbl_companies.country_id', $country_id);
        $this->db->where('tbl_post_jobs.sts', 'active');
        $this->db->where('tbl_companies.sts', 'active');

        return $this->db->count_all_results();
    }
		
    /* PA no usado */
	public function get_active_posted_job_by_company_id($company_id, $per_page, $page)
    {
       $Q = $this->db->query("CALL get_active_posted_job_by_company_id($company_id, $page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function search_posted_jobs_by_company_id(
        $company_id, 
        $filters, 
        $per_page, 
        $page
    ) 
    {
        $employer_query = isset($filters['employer_query']) ? $filters['employer_query'] : 'all';
        $employer_id = isset($filters['employer_id']) ? $filters['employer_id'] : null;
        $rs_status = isset($filters['rs_status']) ? $filters['rs_status'] : 'all';
        $rs_stage = isset($filters['rs_stage']) ? $filters['rs_stage'] : 'all'; 
        $pj_status = isset($filters['status']) ? $filters['status'] : 'all';
        $query = isset($filters['query']) ? $filters['query'] : '';
        $expired = isset($filters['expired']) ? $filters['expired'] : 'all';

        $this->db->select(array(
            'pj.ID', 
            'pj.job_title', 
            'pj.job_slug', 
            'pj.job_description', 
            'pj.employer_ID', 
            'pj.last_date', 
            'pj.dated', 
            'pj.city', 
            'pj.is_featured', 
            'pj.sts', 
            'pj.applications_count', 
            'pj.viewer_count',
            'pj.contact_person',
            'pj.request_ID AS request_ID',
            'pc.company_name', 
            'pc.company_logo', 
            'app_user_employers.email AS employer_email', 
            'app_user_employers.first_name AS employer_name',
            'recruitment_process.sts_stage AS recruiment_sts_stage',
            'recruitment_process.sts AS recruiment_sts', 
        ));
       
        $this->db->from('tbl_post_jobs pj'); 
        $this->db->join('tbl_companies pc', 'pj.company_ID=pc.ID');
        $this->db->join('tbl_employers app_user_employers', 'pj.employer_ID=app_user_employers.ID', 'left');
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=pj.ID', 'left');
        
        $this->db->where('pj.company_ID', $company_id);
        $this->db->where('pj.job_ignore', 0);
  
        if ($employer_query != 'all') {
            $this->db->where('pj.employer_ID', $employer_id);
        }

        if ($rs_stage != 'all') {
            $this->db->where('recruitment_process.sts_stage', $rs_stage);
        }
    
        if ($rs_status != 'all' && $rs_status != 'not_started') {
            $this->db->where('recruitment_process.sts', $rs_status);
        }

        //R&S NO INICIADO
        if ($rs_status == 'not_started') {
            $this->db->where('recruitment_process.sts', null);
        }

        if ($pj_status != 'all') {
            $this->db->where('pj.sts', $pj_status);
        }

       if ($expired == 'yes') {
            $this->db->where('pj.last_date<', date('Y-m-d'));
        } elseif ($expired == 'no') {
            $this->db->where('pj.last_date>', date('Y-m-d'));
        }

        if ($query != '') {
            $this->db->group_start();
            $this->db->like('pj.job_title', $query);
            $this->db->or_like('pj.ID', $query);

            $this->db->group_end();
        }

        $this->db->order_by('pj.dated', 'DESC');
        $this->db->order_by('FIELD(recruitment_process.sts, "finished", "canceled", "active", "suspended") DESC');
        
        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_all_posted_jobs_by_company_id($company_id, $filters = array()) 
    {    
        $employer_query = isset($filters['employer_query']) ? $filters['employer_query'] : 'all';
        $employer_id = isset($filters['employer_id']) ? $filters['employer_id'] : null;
        $rs_status = isset($filters['rs_status']) ? $filters['rs_status'] : 'all';
        $rs_stage = isset($filters['rs_stage']) ? $filters['rs_stage'] : 'all';
        $query = isset($filters['query']) ? $filters['query'] : '';
        $pj_status = isset($filters['status']) ? $filters['status'] : 'all';
        $expired = isset($filters['expired']) ? $filters['expired'] : 'all';

        $this->db->from('tbl_post_jobs pj'); 
        $this->db->join('tbl_recruitment_process recruitment_process', 'recruitment_process.job_ID=pj.ID', 'left');

        $this->db->where('pj.company_ID', $company_id);
        $this->db->where('pj.job_ignore', 0);
        
        if ($employer_query != 'all') {
            $this->db->where('pj.employer_ID', $employer_id);
        }

        if ($rs_stage != 'all') {
            $this->db->where('recruitment_process.sts_stage', $rs_stage);
        }
        
        if ($rs_status != 'all' && $rs_status != 'not_started') {
            $this->db->where('recruitment_process.sts', $rs_status);
        }

        //R&S NO INICIADO
        if ($rs_status == 'not_started') {
           $this->db->where('recruitment_process.sts', null);
        }


        if ($pj_status != 'all') {
            $this->db->where('pj.sts', $pj_status);
        }
        
        if ($expired == 'yes') {
            $this->db->where('pj.last_date<', date('Y-m-d'));
        } elseif ($expired == 'no') {
            $this->db->where('pj.last_date>', date('Y-m-d'));
        }

        if ($query != '') {
            $this->db->group_start();
            $this->db->like('pj.job_title', $query);
            $this->db->or_like('pj.ID', $query);

            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }   

    public function get_posted_job_by_employer(
        $employer_id, 
        $status, 
        $expired, 
        $per_page, 
        $page
    ) {

        $this->db->select('
            pj.ID, 
            pj.job_title, 
            pj.job_slug, 
            pj.job_description, 
            pj.employer_ID, 
            pj.last_date, 
            pj.dated, 
            pj.city, 
            pj.is_featured, 
            pj.sts, 
            pj.applications_count, 
            pj.viewer_count,
            pj.contact_person,
            pc.company_name, 
            pc.company_logo, 
            pe.email AS employer_email, 
            pe.first_name AS employer_name
        ');
        $this->db->from('tbl_post_jobs pj'); 
        $this->db->join('tbl_companies pc', 'pj.company_ID=pc.ID');
        $this->db->join('tbl_employers pe', 'pj.employer_ID=pe.ID');

        $this->db->where('pj.employer_ID', $employer_id);

        if ($expired == 'yes') {
            $this->db->where('pj.last_date<', date('Y-m-d'));
        } elseif ($expired == 'no') {
            $this->db->where('pj.last_date>', date('Y-m-d'));
        }
        
        if ($status != 'all') {
            $this->db->where('pj.sts', $status);
        }
        
        $this->db->order_by('pj.ID', 'DESC');
        $this->db->limit($per_page, $page);

        $Q = $this->db->get();

        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        
        $Q->free_result();

        return $return;
    }

    public function count_all_posted_jobs_by_employer(
        $employer_id, 
        $status = 'active', 
        $expired = 'no'
    ) 
    {    
        $this->db->from('tbl_post_jobs pj'); 
        $this->db->where('pj.employer_ID', $employer_id);        

        if ($expired == 'yes') {
            $this->db->where('pj.last_date<', date('Y-m-d'));
        } elseif ($expired == 'no') {
            $this->db->where('pj.last_date>', date('Y-m-d'));
        }

        if ($status != 'all') {
            $this->db->where('pj.sts', $status);
        }

        return $this->db->count_all_results();
    }	
	
	public function get_active_featured_posted_job($per_page, $page)
    {   
       $today = date('Y-m-d');
       $Q = $this->db->query('CALL get_active_featured_job(' . $page . ',' . $per_page . ',"' . $today . '")');
        
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }

		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function count_active_opened_jobs_by_company_id($company_id)
    {    
        $today = date('Y-m-d');

		$Q = $this->db->query("CALL count_active_opened_jobs_by_company_id(" . $company_id . ", '" . $today . "')");	
		 if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
		
    }
	
    //Search
    public function get_searched_jobs($filters, $per_page, $page)
    {
        $today = date('Y-m-d');
        
        $search = $filters['search'] ?? '';
        $industry = $filters['industry'] ?? '';
        $city = $filters['city'] ?? '';

        $search = $this->db->escape_like_str($search);
        
        $search_fulltext = $this->db->escape($search);
    
        $industry = $this->db->escape_like_str($industry);
        $city = $this->db->escape_like_str($city);
   
        $this->db->select('
            pj.ID, 
            pj.job_title, 
            pj.job_slug, 
            pj.employer_ID, 
            pj.company_ID, 
            pj.job_description, 
            pj.city, 
            pj.dated, 
            pj.last_date, 
            pj.is_featured, 
            pj.sts, 
            pc.company_name, 
            pc.company_logo, 
            pc.company_slug,
            MATCH(pj.job_title, pj.job_description) AGAINST('.$search_fulltext.') AS score
        ', FALSE);
        
        $this->db->from('tbl_post_jobs pj');
        $this->db->join('tbl_companies pc', 'pj.company_ID = pc.ID', 'inner');
        $this->db->join('tbl_job_industries  ji', 'pj.industry_ID = ji.ID', 'inner');

        $this->db->where('pc.country_id', $filters['country_id']);
        $this->db->where('pj.sts', 'active');
        $this->db->where('pc.sts', 'active');
        $this->db->where('pj.last_date >', $today); 

        $this->db->group_start();
            $this->db->or_like('pj.job_title', $search);
            $this->db->or_like('pj.required_skills', $search);
            $this->db->or_like('pj.city', $search);
            $this->db->or_like('pj.job_description', $search);
            $this->db->or_like('pc.company_name', $search);
            $this->db->or_like('pj.job_mode', $search);
            $this->db->or_like('ji.industry_name', $search);
            $this->db->or_where("pj.pay LIKE CONCAT('%', REPLACE('$search', ' ', '-'), '%')", NULL, FALSE);
            
        $this->db->group_end();
        
        if (!empty($industry)) {
            $this->db->like('ji.industry_name', $industry); 
        }
        
        if (!empty($city)) {
            $this->db->like('pj.city', $city); 
        }
        
        $this->db->limit($per_page, $page);

        return  $this->db->get()->result();
     
        // $Q = $this->db->query('CALL ft_search_job("' . $search . '", "' . $industry . '","' . $city . '","' . $today . '",' . $page . ', ' . $per_page . ')');
        
        // if ($Q->num_rows() > 0) {
        //     $return = $Q->result();
        // } else {
        //     $return = [];
        // }
        // $Q->next_result();
        // $Q->free_result();
        
        // return $return;
    }

    public function count_searched_job_records($filters)
    {
        $today = date('Y-m-d');
        
        $search = $filters['search'] ?? '';
        $industry = $filters['industry'] ?? '';
        $city = $filters['city'] ?? '';

        $search = $this->db->escape_like_str($search);
        
        $search_fulltext = $this->db->escape($search);
    
        $industry = $this->db->escape_like_str($industry);
        $city = $this->db->escape_like_str($city);
   
        $this->db->select('pj.ID');
        $this->db->from('tbl_post_jobs pj');
        $this->db->join('tbl_companies pc', 'pj.company_ID = pc.ID', 'inner');
        $this->db->join('tbl_job_industries  ji', 'pj.industry_ID = ji.ID', 'inner');

        $this->db->where('pc.country_id', $filters['country_id']);
        $this->db->where('pj.sts', 'active');
        $this->db->where('pc.sts', 'active');
        $this->db->where('pj.last_date >', $today); 
        
        $this->db->group_start();
            $this->db->or_like('pj.job_title', $search);
            $this->db->or_like('pj.required_skills', $search);
            $this->db->or_like('pj.city', $search);
            $this->db->or_like('pj.job_description', $search);
            $this->db->or_like('pc.company_name', $search);
            $this->db->or_like('pj.job_mode', $search);
            $this->db->or_like('ji.industry_name', $search);
            $this->db->or_where("pj.pay LIKE CONCAT('%', REPLACE('$search', ' ', '-'), '%')", NULL, FALSE);
            
        $this->db->group_end();
        
        if (!empty($industry)) {
            $this->db->like('ji.industry_name', $industry); 
        }
        
        if (!empty($city)) {
            $this->db->like('pj.city', $city); 
        }
        
        return  $this->db->count_all_results();
            
		// $Q = $this->db->query('CALL count_ft_search_job("' . $search . '","' . $industry . '","' . $city . '","' . $today . '")');	
		//  if ($Q->num_rows() > 0) {
        //     $return = $Q->row('total');
        // } else {
        //     $return = 0;
        // }
		// $Q->next_result();
        // $Q->free_result();
        // return $return;
		
    }
	
	//Search Matching
	public function get_matching_searched_jobs($param, $per_page, $page)
    {
       $Q = $this->db->query("
	SELECT pj.ID, pj.job_title, pj.job_slug, pj.employer_ID, pj.company_ID, pj.job_description, pj.city, pj.dated, pj.last_date, pj.is_featured, pj.sts, pc.company_name, pc.company_logo, pc.company_slug

	FROM `tbl_post_jobs` pj 

	INNER JOIN tbl_companies AS pc ON pj.company_ID=pc.ID

	WHERE pj.sts = 'active' AND pc.sts = 'active' 

	AND (
			".$param."
		)
    ORDER BY pj.ID DESC LIMIT 35;
");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		@$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function get_searched_group_by_title($param) {
       $Q = $this->db->query('CALL ft_search_jobs_group_by_title("'.$param.'")');
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function get_searched_group_by_city($search, $industry) {
   
        $today = date('Y-m-d');
        $Q = $this->db->query('CALL ft_search_jobs_group_by_city("' . $search . '", "' . $industry . '", "' . $today .'")');
        
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->next_result();
        $Q->free_result();
        
        return $return;
    }
	
	public function get_searched_group_by_company($filters) 
    {
        $today = date('Y-m-d');

        $search = $filters['search'] ?? '';
        $city = $filters['city'] ?? '';
        $industry = $filters['industry'] ?? '';

        $search = $this->db->escape_like_str($search);
        $city = $this->db->escape_like_str($city);
        $industry = $this->db->escape_like_str($industry);
        
        $this->db->select('pc.company_name, pc.company_slug, COUNT(pc.company_name) as score', FALSE);  
        $this->db->from('tbl_post_jobs pj');
        $this->db->join('tbl_companies pc', 'pj.company_ID = pc.ID', 'inner');
        $this->db->join('tbl_job_industries ji', 'pj.industry_ID = ji.ID', 'inner');
        
        $this->db->where('pc.country_id', $filters['country_id']);
        $this->db->where('pj.sts', 'active');
        $this->db->where('pc.sts', 'active');
        $this->db->where('pj.last_date >', $today);

        $this->db->group_start();
            $this->db->or_like('pj.job_title', $search);
            $this->db->or_like('pj.required_skills', $search);
            $this->db->or_like('pj.city', $search);
            $this->db->or_like('pj.job_description', $search);
            $this->db->or_like('pc.company_name', $search);
            $this->db->or_like('pj.job_mode', $search);
            $this->db->or_like('ji.industry_name', $search);
            
            $escaped_search = $this->db->escape_str($search);
            $this->db->or_where("pj.pay LIKE CONCAT('%', REPLACE('$escaped_search', ' ', '-'), '%')", NULL, FALSE);
            
        $this->db->group_end();

        if (!empty($city)) {
            $this->db->like('pj.city', $city); 
        }
        
        if (!empty($industry)) {
            $this->db->like('ji.industry_name', $industry); 
        }

        $this->db->group_by('pc.ID');
        $this->db->order_by('score', 'DESC');
        
        $this->db->limit(5, 0); 
        
        return $this->db->get()->result();

        // $Q = $this->db->query('CALL ft_search_jobs_group_by_company("' . $search . '", "' . $city . '", "' . $industry . '","' . $today . '")');
        
        // if ($Q->num_rows() > 0) {
        //    $return = $Q->result();
        // } else {
        //     $return = [];
        // }

        // $Q->next_result();
        // $Q->free_result();
        
        // return $return;
    }

    public function get_searched_group_by_industry($filters)
    {
        $today = date('Y-m-d');

        $search = $filters['search'] ?? '';
        $city = $filters['city'] ?? '';

        $search = $this->db->escape_like_str($search);
        $city = $this->db->escape_like_str($city);
        $today = date('Y-m-d'); 
        
        $this->db->select('ji.industry_name, COUNT(ji.industry_name) as score', FALSE);
        $this->db->from('tbl_post_jobs pj');
        $this->db->join('tbl_companies pc', 'pj.company_ID = pc.ID', 'inner');
        $this->db->join('tbl_job_industries ji', 'pj.industry_ID = ji.ID', 'inner');

        $this->db->where('pc.country_id', $filters['country_id']);
        $this->db->where('pj.sts', 'active');
        $this->db->where('pc.sts', 'active');
        $this->db->where('pj.last_date >', $today); 

        $this->db->group_start();
            $this->db->or_like('pj.job_title', $search);
            $this->db->or_like('pj.required_skills', $search);
            $this->db->or_like('pj.city', $search);
            $this->db->or_like('pj.job_description', $search);
            $this->db->or_like('pc.company_name', $search);
            $this->db->or_like('pj.job_mode', $search);
            $this->db->or_like('ji.industry_name', $search);
            
            $escaped_search = $this->db->escape_str($search);
            $this->db->or_where("pj.pay LIKE CONCAT('%', REPLACE('$escaped_search', ' ', '-'), '%')", NULL, FALSE);
            
        $this->db->group_end();

        if (!empty($city)) {
            $this->db->like('pj.city', $city); 
        }

        $this->db->group_by('ji.industry_name');
        $this->db->limit(5, 0);
        $this->db->order_by('score', 'DESC');
        
        return $this->db->get()->result();

        // $Q = $this->db->query('CALL ft_search_jobs_group_by_industry("' . $search . '", "' . $city . '","' . $today . '")');

        // if ($Q->num_rows() > 0) {
        //     $return = $Q->result();
        // } else {
        //     $return = [];
        // }
        // $Q->next_result();
        // $Q->free_result();
        // return $return;
    }
	
	public function get_searched_group_by_job_mode($param) {

        $today = date('Y-m-d');

        $Q = $this->db->query('CALL ft_search_jobs_group_by_job_mode("'.$param.'", "' . $today . '")');
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->next_result();
        $Q->free_result();
        
        return $return;
    }
	
	public function ft_job_search_filter_3($param_city, $param_company_slug, $param_title, $per_page, $page) {
       $Q = $this->db->query('CALL ft_job_search_filter_3("'.$param_city.'", "'.$param_company_slug.'", "'.$param_title.'", '.$page.', '.$per_page.')');
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function count_ft_job_search_filter_3($param_city, $param_company_slug, $param_title) {
		$Q = $this->db->query('CALL count_ft_job_search_filter_3("'.$param_city.'", "'.$param_company_slug.'", "'.$param_title.'")');	
		 if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
		
    }

	public function count_records_by_city($city_name) {
		$Q = $this->db->query("CALL count_active_records_by_city_front_end('".$city_name."')");	
		 if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
		
    }	
	
	public function job_search_by_city($param_city, $per_page, $page) {
       $Q = $this->db->query('CALL job_search_by_city("'.$param_city.'", '.$page.', '.$per_page.')');
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function count_records_by_industry($industry_id) {
		$Q = $this->db->query("CALL count_active_records_by_industry_front_end('".$industry_id."')");	
		 if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
		
    }	
	
	public function job_search_by_industry($param, $per_page, $page) {
       $Q = $this->db->query('CALL job_search_by_industry("'.$param.'", '.$page.', '.$per_page.')');
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		
		$Q->next_result();
        $Q->free_result();
        return $return;
    }

    public function search_candidates_for_job(
        $job_id, 
        $per_page, 
        $page
    )
    {
        $this->db->select([
            'applicants.ID AS applied_ID',
            'applicants.seen AS application_seen',
            //'applicants.dated AS application_date',
            'applicants.interest AS application_interest' ,
            'candidate.*'
        ]);

        $this->db->from('tbl_seeker_applied_for_job applicants');
        $this->db->join('tbl_job_seekers candidate', 'candidate.ID=applicants.seeker_ID');

        $this->db->where('applicants.job_ID', $job_id);
        $this->db->order_by('applicants.interest', 'ASC');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }

    public function count_search_candidates_for_job($job_id)
    {
        $this->db->select(
            'applicants.ID AS applied_ID'
        );

        $this->db->from('tbl_seeker_applied_for_job applicants');
        $this->db->join('tbl_job_seekers candidate', 'candidate.ID=applicants.seeker_ID');

        $this->db->where('applicants.job_ID', $job_id);
        $this->db->order_by('applicants.interest', 'ASC');

        return $this->db->count_all_results();
    }

    public function increase_viewer_count($job_id)
    {
        $this->db->where('ID', $job_id);
        $this->db->set('viewer_count', 'viewer_count + 1', FALSE);
        $this->db->update('tbl_post_jobs');
    }

    public function get_form_job_questions($job_id)
    {
        $this->db->from('tbl_job_questions');
        $this->db->where('job_ID', $job_id);

        return $this->db->get()->result();
    }

    public function get_posted_job_by_request_id($request_id)
    {    
        $this->db->from('tbl_post_jobs pj'); 
        $this->db->where('pj.request_ID', $request_id);

        return $this->db->get()->row();
    }

    public function search_opened_posted_jobs_by_company_id(
        $company_id, 
        $filters, 
        $per_page, 
        $page
    ) 
    {
        $this->db->select([
            'pj.ID', 
            'pj.job_title', 
            'pj.job_slug', 
            'pj.job_description', 
            'pj.employer_ID', 
            'pj.last_date', 
            'pj.dated', 
            'pj.city', 
            'pj.is_featured', 
            'pj.contact_person', 
            'pj.contact_email', 
            'pj.sts', 
            'pc.company_name', 
            'pc.company_logo', 
            'app_user_emp.email AS employer_email', 
            'app_user_emp.first_name AS employer_name'
        ]);
        $this->db->from('tbl_post_jobs AS pj');
        $this->db->join('tbl_companies AS pc', 'pj.company_ID=pc.ID');
        $this->db->join('tbl_employers AS app_user_emp', 'pj.employer_ID=app_user_emp.ID');
        $this->db->where('pj.company_ID', $company_id);
        $this->db->where('pj.sts', 'active');
        $this->db->where('pc.sts', 'active');
        $this->db->where('pj.last_date>', date('Y-m-d'));

        $this->db->order_by('pj.ID',  'DESC');

        $this->db->limit($per_page, $page);

        return $this->db->get()->result();
    }   

    public function  count_opened_posted_jobs_by_company_id(
        $company_id, 
        $filters = []
    ) 
    {
        $this->db->select('pj.ID');
        $this->db->from('tbl_post_jobs AS pj');
        $this->db->join('tbl_companies AS pc', 'pj.company_ID=pc.ID');
        $this->db->where('pj.company_ID', $company_id);
        $this->db->where('pj.sts', 'active');
        $this->db->where('pc.sts', 'active');
        $this->db->where('pj.last_date>', date('Y-m-d'));

        return $this->db->count_all_results();
    }   
}
