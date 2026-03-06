<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Recruitment_period_rules extends CI_Controller
{	
	public function __construct()
	{
        parent::__construct();
		$this->ads = $this->Ad->get_ads();

        //Load models
        $this->load->model('Recruitment_period_rule');
    }

    public function save()
    {
        $input = $this->input->post();

        $period_rules = $this->input->post('period_rules');

        $this->db->where('1=1');
        $this->db->delete('tbl_recruitment_period_rules');

        foreach ($period_rules as $row) {
            $this->Recruitment_period_rule->create([
                'start' => $row['start'],
                'end' => !empty($row['end']) ? $row['end'] : null,
                'color' => $row['color']
            ]);
        }

        echo json_encode(['success' => true]);
    }
}
