<?php
class Company extends CI_Model
{
    public function find($id)
    {
        return $this->get_company_by_id($id);
    }

	public function add_company($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $return = $this->db->insert('tbl_companies', $data);
        if ((bool) $return === TRUE) {
            return $this->db->insert_id();
        } else {
            return $return;
        }       		
	}	
	
	public function update_company($id, $data)
    {
		$this->db->where('ID', $id);
		$return=$this->db->update('tbl_companies', $data);
		return $return;
	}
	
	public function delete_company($id)
    {
		$this->db->where('ID', $id);
		$this->db->delete('tbl_companies');
		return true;
	}
		
	public function search_all_companies(
        $filter, 
        $per_page, 
        $page
    )
    {
        $this->db->select([
            'companies.ID', 
            'companies.company_ruc',
            'companies.company_name', 
            'companies.company_phone', 
            'companies.company_website', 
            'companies.industry_ID', 
            'companies.company_logo',
            'companies.sts', 
            'GROUP_CONCAT(CONCAT(users.first_name)) AS admin_users',
            'industries.industry_name'
        ]);
        $this->db->from('tbl_companies companies');
        $this->db->join('tbl_employers users', 'users.company_ID=companies.ID and users.is_admin="yes"', 'left');
        $this->db->join('tbl_job_industries industries', 'industries.ID=companies.industry_ID', 'left');
        
        if ($filter['query'] != '') {
            $this->db->like('companies.company_ruc', $filter['query']);
            $this->db->or_like('companies.company_name', $filter['query']);
            //$this->db->or_like('companies.ID', $filter['query']);
        }

        $this->db->limit($per_page, $page);
        
        $this->db->group_by('companies.ID');

        return $this->db->get()->result();
    }

    public function count_all_companies($filter)
    {
        $this->db->select('companies.ID');
        $this->db->from('tbl_companies companies');
    
        if ($filter['query'] != '') {
            $this->db->like('companies.company_ruc', $filter['query']);
            $this->db->or_like('companies.company_name', $filter['query']);
            //$this->db->or_like('companies.ID', $filter['query']);
        }
        
        return $this->db->count_all_results();
    }
	
	public function record_count($table_name)
    {
		return $this->db->count_all($table_name);
    }
	
	public function get_company_by_id($id)
    {
        $this->db->select('tbl_companies.*');
        $this->db->from('tbl_companies');
		$this->db->where('tbl_companies.ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function check_slug($slug)
    {	
		$this->db->where('company_slug', $slug);
		$this->db->from('tbl_companies');
		return $this->db->count_all_results();
    }
	
	public function check_slug_edit($CID, $slug)
    {	
		$this->db->where('company_slug', $slug);
		$this->db->where('ID !=', $CID);
		$this->db->from('tbl_companies');
		return $this->db->count_all_results();
    }

	public function get_company_by_old_id($id)
    {
        $this->db->select('tbl_companies.*');
        $this->db->from('tbl_companies');
		$this->db->where('tbl_companies.old_company_id', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }

    public function upload_logo($file, $file_name = '')
    {
        if (empty($file_name)) {
            return false;
        }
    
        $this->load->library('storage_lib');

        $real_path = sys_get_temp_dir();
        $config['upload_path'] = $real_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['overwrite'] = true;
        $config['max_size'] = 2000;
        $config['file_name'] = $file_name;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file)) {
            return false;
        }
        
        $image = ['upload_data' => $this->upload->data()]; 

        $image_name = $image['upload_data']['file_name'];
        $image_extension = $image['upload_data']['file_ext'];
        
        $thumb_config2['image_library'] = 'gd2';
        $thumb_config2['source_image']  = $real_path . '/' . $image_name;
        $thumb_config2['new_image'] = $real_path . '/' . 'new_' . $image_name;
        $thumb_config2['maintain_ratio'] = TRUE;
        $thumb_config2['height'] = 250;
        $thumb_config2['width']  = 250;
        $this->image_lib->initialize($thumb_config2);
        $this->image_lib->resize();
        
        $thumb_config['image_library'] = 'gd2';
        $thumb_config['source_image'] = $real_path . '/' . $image_name;
        $thumb_config['new_image']  = $real_path . '/thumb_' . $image_name;
        $thumb_config['maintain_ratio'] = TRUE;
        $thumb_config['height'] = 50;
        $thumb_config['width']  = 70;
        
        $this->image_lib->initialize($thumb_config);
        $this->image_lib->resize();

        $path_normal = false;
        $path_thumb = false;

        try {
            $path_logo_s3 = 'company/logo';
            $path_local_normal = $real_path . '/new_' . $image_name;
            $path_local_thumb = $real_path . '/thumb_' . $image_name;
            
            $path_image_s3_normal = $path_logo_s3 . '/' . $file_name . $image_extension;
            $path_image_s3_thumb = $path_logo_s3 . '/' . $file_name . '_thumb' . $image_extension;

            $path_normal = $this->storage_lib->put($path_image_s3_normal, $path_local_normal);

            $path_thumb = $this->storage_lib->put($path_image_s3_thumb, $path_local_thumb);

            $this->storage_lib->setVisibility($path_normal, 'public');
            $this->storage_lib->setVisibility($path_thumb, 'public');
        
        } catch (Exception $e) {
            $path_normal = false;
            $path_thumb = false;
        }

        @unlink($real_path . '/new_' . $image_name);    
        @unlink($real_path . '/thumb_' . $image_name);
        @unlink($real_path . '/' . $image_name);

        if ($path_normal === false || $path_thumb === false) {
            return false;
        }

        return $path_normal;
    }

    public function get_company_by_slug($slug = '')
    {
        $this->db->from('tbl_companies');
        $this->db->where('company_slug', $slug);
        
        return $this->db->get()->row();
    }

    public function get_all_internal()
    {
        $this->db->from('tbl_companies');
        $this->db->where('system_internal', 1);
        $this->db->where('sts', 'active');

       return $this->db->get()->result();
    }
}
