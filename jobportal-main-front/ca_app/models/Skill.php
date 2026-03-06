<?php
class Skill extends CI_Model {
	
	private $table_name = 'tbl_skills';
	    
	public function add($data){
		$return = $this->db->insert($this->table_name, $data);
		if ((bool) $return === TRUE) {
			return $this->db->insert_id();
		} else {
			return $return;
		}       
	}	
	
	public function update($id, $data){
		$this->db->where('ID', $id);
		$return=$this->db->update($this->table_name, $data);
		return $return;
	}
	
	
	public function delete($id){
		$this->db->where('ID', $id);
		$this->db->delete($this->table_name);
	}
	
	public function get_record_by_id($id) {
        $this->db->select('*');
        $this->db->from($this->table_name);
		$this->db->where('ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row_array();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	public function get_all_records() {
        $this->db->select('*');
        $this->db->from($this->table_name);
		$this->db->order_by("skill_name", "ASC");
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
	
	public function get_all_skills() {
        $this->db->select('skill_name');
        $this->db->from($this->table_name);
		$this->db->order_by("skill_name", "ASC");
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
        $Q->free_result();
        return $return;
    }
	
	public function get_skills_by_skill_name($skill_name) {
        $this->db->select('skill_name');
        $this->db->from($this->table_name);
		$this->db->where("skill_name", $skill_name);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
    	
    public function update_skill_favorite($original_skill, $skill_favorite = false, $occupational_group = null) {
        $this->db->set('skill_favorite', $skill_favorite);
        $this->db->set('occupational_category_id', $occupational_group);
        $this->db->where('skill_name', $original_skill);
        $this->db->update($this->table_name);
    }
	
	public function record_count($table_name) {
		return $this->db->count_all($table_name);
    }

    public function count_skills($filters = [])
    {
        $this->db->from($this->table_name);

        if (!empty($filters['query'])) {
            $this->db->like('skill_name', $filters['query']);
        }

        return $this->db->count_all_results();
    }

    public function search_skills($limit, $start, $filters = [])
    {
        $this->db->select('*');
        $this->db->from('tbl_skills');

        // Aplicar filtros si están definidos
        if (!empty($filters['query'])) {
            $this->db->like('skill_name', $filters['query']); // Filtro por nombre de habilidad
        }

        // Aplicar límites y offset para la paginación
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        // Retornar los resultados
        return $query->result();
    }

    public function get_paginated_records($limit, $start) {
        $this->db->limit($limit, $start); // Aplicar el límite y el offset correctamente
        $this->db->order_by('ID', 'ASC'); // Ordenar por un índice optimizado, como 'ID'
        $query = $this->db->get('tbl_skills'); // Reemplaza con el nombre correcto de tu tabla
        return $query->result();
    }
	
	public function get_records_by_id($id) {
        $this->db->select('*');
        $this->db->from($this->table_name);
		$this->db->where('ID', $id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
}
