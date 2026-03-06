<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission_internal_areas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        $this->load->model('Employer_profile');
    }

    public function get_areas()
    {
        $user = $this->Employer->find($this->input->get('user_id'));        
        $company = $this->Company->find($user->company_ID);

        $query_areas = $this->db->query("
            SELECT 
                ID AS area_id, 
                area_name AS area_name
            FROM tbl_internal_areas ia
            WHERE ia.ID NOT IN(
                SELECT 
                    pia.area_id 
                FROM tbl_employer_permission_internal_areas pia 
                WHERE pia.user_id=$user->ID
            ) AND ia.active=1 AND ia.country_id=$company->country_id"
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
            'pia.id',
            'ia.ID AS area_id',
            'ia.area_name'
        ]);
        $this->db->from('tbl_employer_permission_internal_areas pia');
        $this->db->join(
            'tbl_internal_areas ia', 
            'ia.ID=pia.area_id'
        );
        $this->db->where('ia.active', 1);
        $this->db->where('ia.country_id', $company->country_id);
        $this->db->where('pia.user_id', $user->ID);
        
        $areas = $this->db->get()->result();

        echo json_encode([
            'data' =>  $areas
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

        foreach ($ids as $area_id) {

            $this->db->from('tbl_employer_permission_internal_areas');
            $this->db->where('area_id', $area_id);
            $this->db->where('user_id', $user_id);
            
            $permission_area = $this->db->get()->row();

            if ($permission_area) {
                continue;
            }

            $data = [
                'user_id' => $user_id,
                'area_id' => $area_id
            ];
            $this->db->insert('tbl_employer_permission_internal_areas', $data);
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
        $status = $this->db->delete('tbl_employer_permission_internal_areas');

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
                'message' => 'No se pudo registrar el cliente'
            ]);
            return;
        }
    }
}
