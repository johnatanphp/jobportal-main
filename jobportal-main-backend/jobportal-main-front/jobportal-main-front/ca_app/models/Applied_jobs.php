<?php
class Applied_jobs extends CI_Model 
{    
	public function add_applied_job($applied_data, $answers_data = array())
    {
        $this->db->trans_start();
    
        $this->db->insert('tbl_seeker_applied_for_job', $applied_data);

        $applied_id = $this->db->insert_id();

        //Save answers to the questions
        foreach ($answers_data as $question_id => $answer) {
            
            $question = $this->Job_question->get_question_by_id($question_id);
            $type_question = $question->type_question;

            if ($type_question != 'checkbox' && $type_question != 'multiple_choice_grid') {

                $answer_data = array(
                    'answer_value' => trim($answer['value']),
                    'type_question' => $type_question,
                    'question_ID' => $question_id,
                    'applied_ID' => $applied_id 
                );

                $this->db->insert('tbl_seeker_applied_job_answers_values', $answer_data);
            }
            
            if ($type_question == 'checkbox') {
                
                $answer_values = (array)$answer['value'];

                foreach ($answer_values as $key => $value) {
                    
                    $value = trim($value);
                    
                    $answer_data = array(
                        'answer_value' => $value,
                        'type_question' => $type_question,
                        'question_ID' => $question_id,
                        'applied_ID' => $applied_id 
                    );

                    $this->db->insert('tbl_seeker_applied_job_answers_values', $answer_data);
                }
            }

            if ($type_question == 'multiple_choice_grid') {
                
                $radio_options = (array)$answer['value'];

                foreach ($radio_options as $key => $value) {
                        
                    $selection_values = explode("-", $value);
                
                    $answer_data = array(
                        'answer_value' => $selection_values[1],
                        'type_question' => $type_question,
                        'answer_value_row' => $selection_values[0],
                        'answer_value_column' => $selection_values[1],
                        'question_ID' => $question_id,
                        'applied_ID' => $applied_id 
                    );

                    $this->db->insert('tbl_seeker_applied_job_answers_values', $answer_data);
                }
            }
        }

        //Increase applications count
        $this->increase_applications_count($applied_data['job_ID']);    

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return $applied_id;
        }

        return false; 
	}	
	
	public function update_applied_job($id, $data){
		$this->db->where('ID', $id);
		$return=$this->db->update('tbl_seeker_applied_for_job', $data);
		return $return;
	}
	
	public function delete_applied_job($id){
		$this->db->where('ID', $id);
		$this->db->delete('tbl_seeker_applied_for_job');
	}
	
	public function delete_applied_job_by_employer_id($emp_id){
		$this->db->where('employer_ID', $emp_id);
		$this->db->delete('tbl_seeker_applied_for_job');
	}
	public function delete_applied_job_by_seeker_id($seeker_id){
		$this->db->where('seeker_ID', $seeker_id);
		$this->db->delete('tbl_seeker_applied_for_job');
	}
	
	public function delete_applied_job_by_posted_job_id($posted_job_id){
		$this->db->where('job_ID', $posted_job_id);
		$this->db->delete('tbl_seeker_applied_for_job');
	}
	
	public function delete_applied_job_by_id_seeker_id($applied_id, $seeker_id)
    {
        $this->db->trans_start();
        
        $applied_job = $this->get_applied_job_by_id($applied_id);

        if (!$applied_job) {
            return false;
        }

        $this->db->where('ID', $applied_id);
        $this->db->where('seeker_ID', $seeker_id);
        $this->db->delete('tbl_seeker_applied_for_job');

        //Delete answers 
        $this->db->where('applied_ID', $applied_id);
        $this->db->delete('tbl_seeker_applied_job_answers_values');

        //Decrease applications count
        $this->decrease_applications_count($applied_job->job_ID);    

        $this->db->trans_complete();

        return $this->db->trans_status(); 
	}
			
	public function get_applied_job_by_id($id) {
        $this->db->select(array(
            'seeker_applied_job.*',
            'job_seeker.*'
        ));
        
        $this->db->from('tbl_seeker_applied_for_job seeker_applied_job');
        $this->db->join('tbl_job_seekers job_seeker', 'job_seeker.ID=seeker_applied_job.seeker_ID');
		$this->db->where('seeker_applied_job.ID', $id);
    
        return$this->db->get()->row();
    }
	
	public function get_applied_job_by_seeker_id($seeker_id) {
        $this->db->select('tbl_seeker_applied_for_job.*');
        $this->db->from('tbl_seeker_applied_for_job');
		$this->db->where('tbl_seeker_applied_for_job.ID', $seeker_id);
        $Q = $this->db->get();
        if ($Q->num_rows() > 0) {
            $return = $Q->row();
        } else {
            $return = 0;
        }
        $Q->free_result();
        return $return;
    }
	
	public function count_applied_job_by_seeker_and_job_id($seeker_id, $job_id) {
        $this->db->from('tbl_seeker_applied_for_job');
		$this->db->where('tbl_seeker_applied_for_job.seeker_ID', $seeker_id);
		$this->db->where('tbl_seeker_applied_for_job.job_ID', $job_id);
		return $this->db->count_all_results();
    }

    public function get_applied_job_by_seeker_and_job_id($seeker_id, $job_id) {
        
        $this->db->from('tbl_seeker_applied_for_job');
        $this->db->where('tbl_seeker_applied_for_job.seeker_ID', $seeker_id);
        $this->db->where('tbl_seeker_applied_for_job.job_ID', $job_id);
        
        return $this->db->get()->result();
    }
	
	public function get_applied_job_by_employer_id($employer_id, $per_page, $page) {
        $Q = $this->db->query("CALL get_applied_jobs_by_employer_id(".$employer_id.", ".$page.",".$per_page.")");	
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function count_applied_job_by_employer_id($employer_id) {
        $Q = $this->db->query("CALL count_applied_jobs_by_employer_id(".$employer_id.")");	
        if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function count_records($table_name, $db_field_name, $value) {
		$this->db->where($db_field_name, $value);
		$this->db->from($table_name);
		return $this->db->count_all_results();
    }
	
	public function get_applied_jobs_by_jobseeker_id($jobseeker_id, $per_page, $page) {
        $Q = $this->db->query("CALL get_applied_jobs_by_jobseeker_id(".$jobseeker_id.", ".$page.",".$per_page.")");	
        if ($Q->num_rows() > 0) {
            $return = $Q->result();
        } else {
            $return = [];
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }
	
	public function count_applied_job_jobseeker_id($jobseeker_id) {
        $Q = $this->db->query("CALL count_applied_jobs_by_jobseeker_id(".$jobseeker_id.")");	
        if ($Q->num_rows() > 0) {
            $return = $Q->row('total');
        } else {
            $return = 0;
        }
		$Q->next_result();
        $Q->free_result();
        return $return;
    }

    private function increase_applications_count($job_id)
    {
        $this->db->where('ID', $job_id);
        $this->db->set('applications_count', 'applications_count + 1', FALSE);
        $this->db->update('tbl_post_jobs');
    }

    private function decrease_applications_count($job_id)
    {
        $this->db->where('ID', $job_id);
        $this->db->set('applications_count', 'applications_count - 1', FALSE);
        $this->db->update('tbl_post_jobs');
    }

    public function get_answers_applicant($applied_id)
    {
        $this->db->select(array(            
            'job_questions.question AS question',
            'applied_answers.ID AS answer_ID',
            'applied_answers.question_ID AS question_ID',
            'applied_answers.applied_ID AS applied_ID',
            'applied_answers.type_question AS type_question' 
        ));

        $this->db->from('tbl_job_questions job_questions');
        $this->db->join('tbl_seeker_applied_job_answers_values applied_answers', 'applied_answers.question_ID=job_questions.ID');
        $this->db->where('applied_answers.applied_ID', $applied_id);
        $this->db->group_by('applied_answers.question_ID');
    
        return $this->db->get()->result();
    }

    public function get_answers_by_id($answer_id)
    {
        $this->db->select(array(
            'applied_answers.type_question AS type_question',
            'applied_answers.answer_value AS answer_value',
            'applied_answers.answer_value_row AS answer_value_row',
            'applied_answers.answer_value_column AS answer_value_column',             
        ));

        $this->db->from('tbl_seeker_applied_job_answers_values applied_answers');
        $this->db->where('applied_answers.ID', $answer_id);
        
        return $this->db->get()->result();
    }

    public function get_answers_to_question($question_id, $applied_id)
    {
        $this->db->select(array(
            'applied_answers.type_question AS type_question',
            'applied_answers.answer_value AS answer_value',
            'applied_answers.answer_value_row AS answer_value_row',
            'applied_answers.answer_value_column AS answer_value_column',             
        ));

        $this->db->from('tbl_seeker_applied_job_answers_values applied_answers');
        $this->db->where('applied_answers.question_ID', $question_id);
        $this->db->where('applied_answers.applied_ID', $applied_id);

        return $this->db->get()->result();
    }

    public function mark_as_interesting_cv(
        $applied_id, 
        $cv_interesting
    )
    {   
        $job_applied = $this->get_applied_job_by_id($applied_id);
        
        $cv_interesting = $cv_interesting == 'yes' ? 'yes' : 'no';
        
        $data_update = [
            'interest' => $cv_interesting
        ];

        $this->db->where('ID', $applied_id);
        $this->db->update('tbl_seeker_applied_for_job', $data_update);

        return true;
    }

    public function mark_as_seen_application($applied_id)
    {
        $this->db->where('ID', $applied_id);
        $this->db->update('tbl_seeker_applied_for_job', array('seen' => 1));
    }
}
