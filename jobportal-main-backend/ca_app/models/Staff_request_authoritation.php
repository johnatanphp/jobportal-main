<?php
class Staff_request_authoritation extends CI_Model {

    public function get_request_authorization($type_authority, $request_id)
    {
        $this->db->from('tbl_staff_request_authorizations');
        $this->db->where('request_ID', $request_id);
        $this->db->where('type_authority_ID', $type_authority);
        
        if ($type_authority == 1) { // DIRECTOR RESPOSANBLE
           $this->db->where('is_authorized IS NOT NULL');
        }

        return $this->db->get()->row();
    }    

    public function get_request_authorization_no_null($type_authority, $request_id)
    {
        $this->db->from('tbl_staff_request_authorizations');
        $this->db->where('request_ID', $request_id);
        $this->db->where('type_authority_ID', $type_authority);
        
        return $this->db->get()->row();
    }    

    public function get_authorization_by_token($token)
    {
        $this->db->from('tbl_staff_request_authorizations authorization');
        $this->db->join('tbl_type_authorities type_authority', 'authorization.type_authority_ID=type_authority.ID');
        $this->db->where('authorization.token', $token);

        return $this->db->get()->row();
    }

    public function get_request_authorities_by_request($request_id)
    {        
        $this->db->from('tbl_staff_request_authorizations');
        $this->db->where('request_ID', $request_id);

        return $this->db->get()->result();
    }

    public function get_count_authorizations_by_request($request_id)
    {
        $type_authority_ids = $this->get_authorities_type_ids_by_request($request_id);

        $this->db->from('tbl_staff_request_authorizations');
        $this->db->where('is_authorized', 1);
        $this->db->where_in('type_authority_ID', $type_authority_ids);
        $this->db->where('request_ID', $request_id);

        return  $this->db->count_all_results();
    }

    public function authorize_request($token, $request_id)
    {
        $this->db->trans_start();

        $data = array(
            'is_authorized' => 1,
            'date' => date('Y-m-d H:i:s')
        );

        $this->db->where('token', $token);
        $this->db->update('tbl_staff_request_authorizations', $data);
        
        $count_total = $this->total_necessary_authorizations($request_id);
        $count_authorizations = $this->get_count_authorizations_by_request($request_id);

        if ($count_authorizations == $count_total) {
            $this->db->where('ID', $request_id);
            $this->db->update('tbl_staff_requests', array('sts_process' => 'unassigned'));
        }

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function deny_request($token)
    {
        $data = array(
            'is_authorized' => 0,
            'date' => date('Y-m-d H:i:s')
        );

        $this->db->where('token', $token);
        return $this->db->update('tbl_staff_request_authorizations', $data);
    }

    public function create_request_for_authorizations($authorities, $request_id)
    {
        foreach ($authorities as $type_authority => $row_authority) {
            if ($type_authority == 1) { //DIRECTOR RESPONSABLE
                $authorities_DR = $row_authority;
                foreach ($authorities_DR as $row_dr) {
                    $part = explode(',', $row_dr);
                    $this->create_approval_request($part[0], $part[1], $type_authority, $request_id);        
                }
                continue;
            }
            $part = explode(',', $row_authority);
            $this->create_approval_request($part[0], $part[1], $type_authority, $request_id);
        }
    }

    private function create_approval_request(
        $personal_name, 
        $personal_email, 
        $type_authority, 
        $request_id
    )
    {
        $authorization_personal = $this->db->get_where('tbl_staff_request_authorizations', 
            [
                'request_ID' => $request_id,
                'type_authority_ID' => $type_authority,
                'personal_email' => $personal_email,
                'personal_name' => $personal_name
            ]
        )->row();

        if ($authorization_personal) {
            return false;
        }

        $authorization = $this->db->get_where('tbl_staff_request_authorizations', 
            [
                'request_ID' => $request_id,
                'type_authority_ID' => $type_authority,
                'is_authorized' => 1
            ]
        )->row();

        if ($authorization) {
            return false;
        }

        $this->db->where('request_ID', $request_id);
        $this->db->where('type_authority_ID', $type_authority);
        $this->db->where('is_authorized', null);
        $this->db->delete('tbl_staff_request_authorizations');

        $data = array(
            'personal_name' => $personal_name,
            'personal_email' => $personal_email,
            'token' => create_token(40),
            'type_authority_ID' => $type_authority,
            'request_ID' => $request_id
        );

        $this->db->insert('tbl_staff_request_authorizations', $data);
    }

    public function exist_authorization_of_DR($request_id)
    {
        $this->db->from('tbl_staff_request_authorizations');
        $this->db->where('is_authorized IS NOT NULL');
        $this->db->where('type_authority_ID', 1); //DIRECTOR RESPONSABLE
        $this->db->where('request_ID', $request_id);

        return  $this->db->count_all_results() > 0;
    }

    public function get_authorization_type_authority($request_id, $type_authority_id)
    {
        $this->db->from('tbl_staff_request_authorizations authorization');
        $this->db->join('tbl_type_authorities type_authority', 'authorization.type_authority_ID=type_authority.ID');
        $this->db->where('authorization.type_authority_ID', $type_authority_id);
        $this->db->where('authorization.request_ID', $request_id);

        return $this->db->get()->result();
    }

    public function get_authorizations_unanswered($request_id, $email_authority)
    {
        $this->db->from('tbl_staff_request_authorizations authorization');
        $this->db->where('authorization.is_authorized', null);
        $this->db->where('authorization.personal_email', $email_authority);
        $this->db->where('authorization.request_ID', $request_id);

        return $this->db->get()->result();
    }

    public function total_necessary_authorizations($request_id)
    {
        return count($this->get_authorities_type_ids_by_request($request_id));
    }

    public function get_authorities_type_ids_by_request($request_id)
    {
        $this->db->select([
            'DISTINCT(type_authority_ID) AS type_authority_id'
        ]);
        $this->db->from('tbl_staff_request_authorizations authorization');
        $this->db->where('authorization.request_ID', $request_id);

        $result = $this->db->get()->result();

        $type_authority_ids  = [];

        foreach ($result as $row) {
            $type_authority_ids[$row->type_authority_id] = $row->type_authority_id;

        }
        
        return $type_authority_ids;
    }
 }
