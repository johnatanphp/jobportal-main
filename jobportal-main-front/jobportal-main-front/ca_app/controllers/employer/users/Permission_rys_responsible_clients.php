<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Permission_rys_responsible_clients extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Validate that the employer is an administrator
        validate_employer_admin();

        $this->load->model('Employer_profile');
    }

    public function index($user_id = 0)
    {   
        $user = $this->Employer->find($user_id);

        if (!$user) {
            show_404();
        }

        $data['ads_row'] = $this->ads;
        $data['user'] = $user;
        $data['title'] = 'Gestionar Clientes - RyS Responsables';
                
        $this->load->view('employer/users/permission_rys_responsible_clients/index', $data);
    }

    public function permissions($user_id = 0)
    {
        $user = $this->Employer->find($user_id);

        $this->db->select([
            'clients_permissions.id AS permission_id',
            'consultants.code AS consultant_code',
            'consultants.name AS consultant_name',
            'clients.code AS client_code',
            'clients.name AS client_name',
        ]);
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code=cost_centers.cia_code');
        $this->db->join('tbl_workflow_clients clients', 'clients.code=cost_centers.client_code AND cost_centers.cia_code=consultants.code');
        $this->db->join('tbl_employer_responsibles_clients_permissions clients_permissions', 'clients_permissions.cia_code=consultants.code AND clients_permissions.client_code=cost_centers.client_code');
        $this->db->where('clients_permissions.employer_id', $user_id);
        $this->db->where('cost_centers.company_id', $user->company_ID);
        $this->db->where('consultants.company_id', $user->company_ID);
        $this->db->where('clients.company_id', $user->company_ID);
    
        $this->db->group_by(['consultants.code', 'clients.code']);
        
        $results = $this->db->get()->result();

        echo json_encode([
            'data' => $results
        ]);
    }

    public function clients($user_id = 0)
    {
        $params = $this->input->get();
        $user = $this->Employer->find($user_id);

        if (!$user) {
            echo json_encode([
                'data' => []
            ]);
            return;
        }

        $this->db->select([
            "CONCAT(consultants.code, '__' ,clients.code) AS id",
            'pjl.id AS permission_id',
            'consultants.code AS consultant_code',
            'consultants.name AS consultant_name',
            'clients.code AS client_code',
            'clients.name AS client_name'
        ], false);
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code=cost_centers.cia_code');
        $this->db->join('tbl_workflow_clients clients', 'clients.code=cost_centers.client_code AND cost_centers.cia_code=consultants.code');
        $this->db->join('tbl_employer_responsibles_clients_permissions pjl', 'pjl.cia_code=consultants.code AND pjl.client_code=cost_centers.client_code AND pjl.employer_id=' . $user_id, 'left');
        $this->db->where('cost_centers.company_id', $user->company_ID);
        $this->db->where('consultants.company_id', $user->company_ID);
        $this->db->where('clients.company_id', $user->company_ID);
        $this->db->having('permission_id IS NULL');
        $this->db->group_by(['consultants.code', 'clients.code']);
        
        $results = $this->db->get()->result();

        echo json_encode([
            'data' => $results
        ]);
    }

    public function add_clients()
    {
        $params = $this->input->post();
        $clients = $params['clients'] ?? [];

        $user = $this->Employer->find($params['employer_id']);

        foreach ($clients as $client_value) {

            list($consultant_code, $client_code) = explode('__', $client_value);

            $permission_clients = $this->db->get_where('tbl_employer_responsibles_clients_permissions',   [
                'employer_id' => $user->ID,
                'cia_code' => $consultant_code,
                'client_code' => $client_code,
                'company_id' => $user->company_ID
            ])->row();

            if (!$permission_clients) {
                $this->db->insert('tbl_employer_responsibles_clients_permissions',   [
                    'employer_id' => $user->ID,
                    'cia_code' => $consultant_code,
                    'client_code' => $client_code,
                    'company_id' => $user->company_ID
                ]);
            }
        }

        echo json_encode([
            'status' => true,
            'message' => 'Se han agregado los clientes al Layout de puesto'
        ]);
    }

    public function remove_clients()
    {
        $params = $this->input->post();

        $ids = $params['ids'] ?? [];

        $user = $this->Employer->find($params['employer_id']);

        $this->db->where_in('id', $ids);
        $this->db->where('employer_id', $user->ID);
        $this->db->where('company_id', $user->company_ID);
        $this->db->delete('tbl_employer_responsibles_clients_permissions');
 
        echo json_encode([
            'status' => true,
            'message' => 'Se han removido los clientes del Layout de puesto'
        ]);
    }
}
