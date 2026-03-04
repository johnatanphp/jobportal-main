<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission_clients extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();
    }

    public function list()
    {
        echo json_encode([
            'data' => $this->Employer->get_client_companies($this->input->get('user_id'))
        ]);
    }
    
    public function add()
    {
        $data_input = $this->input->post();

        $user_id = $data_input['user_id'];
        $row_user = $this->Employer->find($user_id);

        if (!$row_user) {
            return false;
        }

        $data_consultant = explode('|', $data_input['consultant']);
        $data_client = explode('|', $data_input['client_company']);
        $data_business_unit = explode('|', $data_input['business_unit']);
        $cost_center = $data_input['cost_center'];

        $data = [
            'recruiter_id' => $user_id,
            'no_cia' => $data_consultant[0],
            'consultant_name' => $data_consultant[1],
            'cod_clie' => $data_client[0],
            'client_company_name' => $data_client[1],
            'cod_business_unit' => $data_business_unit[0],
            'business_unit_name' => $data_business_unit[1],
            'cost_center' => $cost_center
        ];

        $this->db->select('__id__');
        $this->db->from('tbl_staff_recruiter_client_companies');
        $this->db->where($data);
        $client_row = $this->db->get()->row();

        if ($client_row) {
            echo json_encode([
                'status' => true,
                'message' => 'Permiso agregado'
            ]);
            return;
        }
    
        $status = $this->db->insert('tbl_staff_recruiter_client_companies', $data);
    
        if ($status === true) {
            echo json_encode([
                'status' => true,
                'message' => 'Permiso agregado'
            ]);
            return;
        }

        if ($status === false) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo agregar el permiso'
            ]);
            return;
        }
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

        $this->db->where('__id__', $id);
        $status = $this->db->delete('tbl_staff_recruiter_client_companies');

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
