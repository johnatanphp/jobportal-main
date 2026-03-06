<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Session_logout
{
    // Enables the use of CI super-global without having to define an extra variable.
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function exec()
    {
        $codes = $this->session->userdata('employer_auth_codes') ? $this->session->userdata('employer_auth_codes') : [];
        
        //dd(count($codes));
        
        if (count($codes) > 0) {
            $this->db->select([
                'token.access_token',
                'auth_codes.code AS auth_code'
            ]);
            $this->db->from('tbl_api_user_auth_codes auth_codes');
            $this->db->join('tbl_api_user_tokens token', 'auth_codes.access_token=token.access_token AND token.revoked=0', 'left');
            $this->db->where_in('auth_codes.code', $codes);
        
            $results = $this->db->get()->result();
            
            //  dd($results);
            foreach ($results as $row_token) {
                    
                if ($row_token->auth_code) {
                    $this->db->where('code', $row_token->auth_code);
                    $this->db->update('tbl_api_user_auth_codes', [
                        'expires_at' => date('Y-m-d H:00:00')
                    ]);
                }
                    
                if ($row_token->access_token) {
                    $this->db->where('access_token', $row_token->access_token);
                    $this->db->update('tbl_api_user_tokens', [
                        'revoked' => 1,
                        'revoked_at' => date('Y-m-d H:i:00')
                    ]);
                }
            }
        }
    
        $user_data = [
			'user_id' =>  null,
			'useremail' => null,
			'user_type' => NULL,
			'user_folder' => '',
			'is_user_login' => FALSE,
			'slug' => '',
			'is_job_seeker' => FALSE,
			'is_employer' => FALSE,
			'profile' => null,
			'employer_auth_codes' => null,
			'back_from_user_login' => ''	
		];

		$this->session->set_userdata($user_data);
		$this->session->unset_userdata($user_data);
    }
}
