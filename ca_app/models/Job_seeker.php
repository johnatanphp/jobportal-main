<?php
class Job_seeker extends CI_Model 
{
    public function find($id)
    {
        $this->db->from('tbl_job_seekers');
        $this->db->where('ID', $id);

        return $this->db->get()->row();
    }

	public function add_job_seekers($data)
    {
        $return = $this->db->insert('tbl_job_seekers', $data);
        if ((bool) $return === TRUE) {
            return $this->db->insert_id();
        } else {
            return $return;
        }       
	}	
	
	public function update_job_seeker($id, $data)
    {
		$this->db->where('ID', $id);
		$return = $this->db->update('tbl_job_seekers', $data);
		return $return;
	}
	
	public function update($id, $data)
    {
		$this->db->where('ID', $id);
		$return=$this->db->update('tbl_job_seekers', $data);
		return $return;
	}
	
	public function delete_job_seeker($id)
    {
		$this->db->where('ID', $id);
		$this->db->delete('tbl_job_seekers');
	}
	
	public function authenticate_job_seeker($user_name, $password)
    {
        $this->db->select('*');
        $this->db->from('tbl_job_seekers');
        $this->db->where('email', $user_name);
		//$this->db->where('password', $password);	
        $this->db->limit(1);
        
        $row = $this->db->get()->row();

        if ($row && 
            (
                (substr($row->password, 0, 7) == '$2y$10$' && verify_hashing($password, $row->password)) || 
                (substr($row->password, 0, 7) != '$2y$10$' && $row->password == $password)
            )
        ) {
            /*
            if (needs_rehashing($row->password)) {
                $this->db->where('ID', $row->ID);
                $this->update('tbl_job_seekers', array('password' =>  do_hashing($password)));
            }
            */
            return $row;
        }
    
        return false;
    }
	
	public function authenticate_job_seeker_email_address($user_name)
    {
        $this->db->select('*');
        $this->db->from('tbl_job_seekers');
        $this->db->where('email', $user_name);
		$this->db->limit(1);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function authenticate_job_seeker_by_id_password($ID, $password)
    {
        $this->db->select('*');
        $this->db->from('tbl_job_seekers');
        $this->db->where('ID', $ID);
		//$this->db->where('password', $password);
		$this->db->limit(1);

        $row = $this->db->get()->row();

        if ($row) {
            $row = $this->authenticate_job_seeker($row->email, $password);
        }

        return $row;
    }
	
	public function get_all_job_seekers($per_page, $page) {
        $this->db->select('tbl_job_seekers.*');
        $this->db->from('tbl_job_seekers');
		$this->db->order_by("tbl_job_seekers.ID", "DESC"); 
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
	
	public function record_count($table_name)
    {
		return $this->db->count_all($table_name);
    }
	
	public function get_job_seeker_by_id($id)
    {
        $this->db->select('tbl_job_seekers.*');
        $this->db->from('tbl_job_seekers');
		$this->db->where('tbl_job_seekers.ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function get_job_seeker_by_old_id($id)
    {
        $this->db->select('tbl_job_seekers.*');
        $this->db->from('tbl_job_seekers');
		$this->db->where('tbl_job_seekers.old_id', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function search_all_job_seekers($per_page, $page, $search_parameters)
    {
        $this->db->select('tbl_job_seekers.*');
        $this->db->from('tbl_job_seekers');
		$this->db->like($search_parameters);
		$this->db->order_by("tbl_job_seekers.ID", "DESC"); 
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
	
	public function search_record_count($table_name, $search_parameters)
    {

		$this->db->like($search_parameters);
		$this->db->from($table_name);
		return $this->db->count_all_results();
    }
	
	public function count_records($table_name, $db_field_name, $value)
    {
		$this->db->where($db_field_name, $value);
		$this->db->from($table_name);
		return $this->db->count_all_results();
    }
	
	public function get_all_applied_jobs_by_seekers_ID($employer_id, $per_page, $page)
    {
        $Q = $this->db->query("SELECT * FROM get_applied_jobs_by_seeker_id($employer_id, $page, $per_page)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }

	public function get_experience_by_jobseeker_id($jobseeker_id)
    {
        $Q = $this->db->query("SELECT * FROM get_experience_by_jobseeker_id($jobseeker_id)");
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = array();
        }
        $Q->free_result();
        return $return;
    }
    
	public function get_qualification_by_jobseeker_id($jobseeker_id)
    {    
        // CALL get_qualification_by_jobseeker_id($jobseeker_id)");

        $this->db->select([
            'sa.*',
            'institutions.name AS institution_name'
        ]);
        $this->db->from('tbl_seeker_academic sa');
        $this->db->join('tbl_institutions institutions', 'institutions.code=sa.institution', 'left');
        $this->db->where('sa.seeker_ID', $jobseeker_id);
        $this->db->order_by('sa.start_date', 'DESC');
        
        return $this->db->get()->result();
    }
	
	public function get_grouped_skills_by_seeker_id($seeker_id)
    {
		$Q = $this->db->query("SELECT GROUP_CONCAT(skill_name SEPARATOR ', ') as skills FROM tbl_seeker_skills where seeker_ID='".$seeker_id."'");	
		if ($Q->num_rows() > 0) {
            $return = $Q->row('skills');
        } else {
            $return = '';
        }
        return $return;
	}

    public function count_experience_with_certificate($jobseeker_id)
    {
        $this->db->from('tbl_seeker_experience');
        $this->db->where('seeker_ID', $jobseeker_id);
        $this->db->where('attached_certificate!=', null);
        
        return $this->db->count_all_results();
    }

    public function count_study_with_certificate($jobseeker_id)
    {
        $this->db->from('tbl_seeker_academic');
        $this->db->where('seeker_ID', $jobseeker_id);
        $this->db->where('attached_certificate!=', null);
        
        return $this->db->count_all_results();
    }

    public function accept_legal_terms($seeker_id = 0)
    {

        return $this->db->insert('tbl_seeker_acceptance_legal_terms',

            array(

                'seeker_ID' => $seeker_id,
                'datetime' => date('Y-m-d H:i:s')
            )
        );

    }

    public function upload_picture_fb_or_linkedin($picture_url = '', $picture_name = '')
    {
        if (empty($picture_url)) {
            return false;
        }
        
        $picture_name = md5(uniqid('pic_social_networks', true)) . '.jpg';
        $real_path = realpath(APPPATH . '../public/uploads/tmp');

        $path_pic_normal = $real_path . '/candidate_pic_' . $picture_name;

        $content_picture = @file_get_contents($picture_url);
        $result_pic_normal = @file_put_contents($path_pic_normal, $content_picture);
        
        $pic_attr = @getimagesize($path_pic_normal);

        $pic_width = isset($pic_attr[0]) ? $pic_attr[0] : 0;
        $pic_height = isset($pic_attr[1]) ? $pic_attr[1] : 0;
        $pic_mime = isset($pic_attr[2]) ? $pic_attr[2] : '';
        
        $allowed_mimes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP); 

        if (!in_array($pic_mime, $allowed_mimes)) {
            @unlink($path_pic_normal);          
            return false;
        }
        
        if ($pic_width > 200 || $pic_height > 200) {
            $pic_width = 200;
            $pic_height = 200;
        }

        if (!$this->image_resize($path_pic_normal, $pic_width, $pic_height)) {
            @unlink($path_pic_normal);
            return false;
        }   

        try {
            $this->load->library('storage_lib', null, 'Storage_lib');

            $path_image = 'candidate/pic/' . $picture_name;
            $path = $this->Storage_lib->put($path_image, $path_pic_normal);

        } catch (\Exception $e) {
            $path = false;
        } 

        if ($path !== false) {
            $this->Storage_lib->setVisibility($path, 'public');
        }

        return $path;
    }

    private function image_resize($source_image, $width, $height, $new_image = '')
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source_image;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['new_image'] = $new_image;
        $config['width'] = $width;
        $config['height'] = $height;

        $this->load->library('image_lib');          
        
        $this->image_lib->initialize($config);

        return $this->image_lib->resize();
    }

    public function has_worked_in_overall($document_number)
    {
        $work_experiences = $this->get_overall_work_experiences([$document_number]);
        return count($work_experiences[$document_number] ?? []) > 0;
    }

    public function get_overall_work_experiences($document_numbers = [])
    {
        if ($this->config->item('system_payroll') == 'eplani') {
            $this->load->library('WS_overall/WS_overall_employee_work_experiences_lib', null, 'WS_overall_employee_work_experiences_lib');
            return $this->WS_overall_employee_work_experiences_lib->all($document_numbers);
        }

        if ($this->config->item('system_payroll') == 'ca') {
            $this->load->library('WS_ca/WS_ca_employee_work_experiences_lib', null, 'WS_ca_employee_work_experiences_lib');
            return $this->WS_ca_employee_work_experiences_lib->all($document_numbers);
        }

        return [];
    }

    public function get_overall_blacklist($document_numbers = [])
    {
        if ($this->config->item('system_payroll') == 'eplani') {
            $this->load->library('WS_overall/WS_overall_employee_blacklist_lib', null, 'WS_overall_employee_blacklist_lib');
            return $this->WS_overall_employee_blacklist_lib->all($document_numbers);
        }

        if ($this->config->item('system_payroll') == 'ca') {
            $this->load->library('WS_ca/WS_ca_employee_blacklist_lib', null, 'WS_ca_employee_blacklist_lib');
            return $this->WS_ca_employee_blacklist_lib->all($document_numbers);
        }

        return [];
    }

    public function get_overall_summary_data($document_numbers = [])
    {
        if ($this->config->item('system_payroll') == 'eplani') {
            $this->load->library('WS_overall/WS_overall_employee_data_lib');
            return $this->ws_overall_employee_data_lib->search($document_numbers);
        }

        return [];
    }
}
