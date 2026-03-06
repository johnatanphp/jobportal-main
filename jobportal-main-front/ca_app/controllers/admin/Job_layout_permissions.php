<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Job_layout_permissions extends CI_Controller
{    
    public function __construct()
    {
        parent::__construct();
        $this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Job_layout');
    }
    
    public function clients()
    {
        $params = $this->input->get();
        $job_layout = $this->Job_layout->find($params['job_layout_id']);

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
        $this->db->join('tbl_job_layout_permission_clients pjl', 'pjl.consultant_code=consultants.code AND pjl.client_code=cost_centers.client_code AND pjl.job_layout_id=' . $job_layout->id, 'left');
        $this->db->where('cost_centers.company_id', $job_layout->company_id);
        $this->db->where('consultants.company_id', $job_layout->company_id);
        $this->db->where('clients.company_id', $job_layout->company_id);
        $this->db->having('permission_id IS NULL');
        $this->db->group_by(['consultants.code', 'clients.code']);
        
        $results = $this->db->get()->result();

        echo json_encode([
            'data' => $results
        ]);
    }

    public function permission_clients()
    {
        $params = $this->input->get();
        $job_layout = $this->Job_layout->find($params['job_layout_id']);

        $this->db->select([
            'pjl.id AS permission_id',
            'consultants.code AS consultant_code',
            'consultants.name AS consultant_name',
            'clients.code AS client_code',
            'clients.name AS client_name',
        ]);
        $this->db->from('tbl_workflow_cost_centers cost_centers');
        $this->db->join('tbl_workflow_consultants consultants', 'consultants.code=cost_centers.cia_code');
        $this->db->join('tbl_workflow_clients clients', 'clients.code=cost_centers.client_code AND cost_centers.cia_code=consultants.code');
        $this->db->join('tbl_job_layout_permission_clients pjl', 'pjl.consultant_code=consultants.code AND pjl.client_code=cost_centers.client_code');
        $this->db->where('pjl.job_layout_id', $job_layout->id);
        $this->db->where('cost_centers.company_id', $job_layout->company_id);
        $this->db->where('consultants.company_id', $job_layout->company_id);
        $this->db->where('clients.company_id', $job_layout->company_id);
    
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
        $job_layout_id = $params['job_layout_id'];

        foreach ($clients as $client_value) {

            list($consultant_code, $client_code) = explode('__', $client_value);

            $permission_clients = $this->db->get_where('tbl_job_layout_permission_clients',   [
                'job_layout_id' => $job_layout_id,
                'consultant_code' => $consultant_code,
                'client_code' => $client_code
            ])->row();

            if (!$permission_clients) {
                $this->db->insert('tbl_job_layout_permission_clients',   [
                    'job_layout_id' => $job_layout_id,
                    'consultant_code' => $consultant_code,
                    'client_code' => $client_code
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
        $job_layout_id = $params['job_layout_id'];

        $this->db->where_in('id', $ids);
        $this->db->where('job_layout_id', $job_layout_id);
        $this->db->delete('tbl_job_layout_permission_clients');
 
        echo json_encode([
            'status' => true,
            'message' => 'Se han removido los clientes del Layout de puesto'
        ]);
    }
}
