<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission_job_charges extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        $this->load->model('Employer_profile');
    }

    public function get_job_charges()
    {
        $user = $this->Employer->find($this->input->get('user_id'));        
        $company = $this->Company->find($user->company_ID);

        $query_areas = $this->db->query("
            SELECT 
                ID AS charge_id, 
                charge_name AS charge_name
            FROM tbl_job_charges jc
            WHERE jc.ID NOT IN(
                SELECT 
                    pjc.charge_id 
                FROM tbl_employer_permission_job_charges pjc
                WHERE pjc.user_id=$user->ID
            ) AND jc.sts='active' AND jc.country_id=$company->country_id"
        );

        $areas = $query_areas->result();

        echo json_encode([
            'data' =>  $areas
        ]);
    }

    public function get_list()
    {
        $user = $this->Employer->find($this->input->get('user_id'));
        $company = $this->Company->find($user->company_ID);

        $this->db->select([
            'pjc.id',
            'jc.ID AS charge_id',
            'jc.charge_name AS charge_name'
        ]);
        $this->db->from('tbl_employer_permission_job_charges pjc');
        $this->db->join(
            'tbl_job_charges jc', 
            'jc.ID=pjc.charge_id'
        );
        $this->db->where('jc.sts', 'active');
        $this->db->where('jc.country_id', $company->country_id);
        $this->db->where('pjc.user_id', $user->ID);
        
        $results = $this->db->get()->result();

        echo json_encode([
            'data' =>  $results
        ]);
    }

    public function add()
    {
        $user_id = $this->input->post('user_id');

        $user = $this->Employer->find($user_id);

        if (!$user) {
            show_404();
        }

        $ids = $this->input->post('ids');

        foreach ($ids as $charge_id) {

            $this->db->from('tbl_employer_permission_job_charges');
            $this->db->where('charge_id', $charge_id);
            $this->db->where('user_id', $user_id);
            
            $permission = $this->db->get()->row();

            if ($permission) {
                continue;
            }

            $data = [
                'user_id' => $user_id,
                'charge_id' => $charge_id
            ];
            $this->db->insert('tbl_employer_permission_job_charges', $data);
        }

        echo json_encode([
            'status' => true,
            'message' => 'Permiso agregado'
        ]);
    }

    public function delete()
    {
        $id = $this->input->post('id');

        if (trim($id) == '') {
            echo json_encode([
                'status' => false,
                'message' => 'Id debe ser ingresado'
            ]);
            return;
        }

        $this->db->where('id', $id);
        $status = $this->db->delete('tbl_employer_permission_job_charges');

        if ($status === true) {

            echo json_encode([
                'status' => true,
                'message' => 'OK'
            ]);
            return;
        }

        if ($status === false) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo registrar el permiso'
            ]);
            return;
        }
    }
}
